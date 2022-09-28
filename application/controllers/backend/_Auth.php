<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->lang->load('auth', 'spanish');
		$this->load->helper('language');
	}

	public function index(){
		if (!$this->ion_auth->logged_in()){
			redirect('backend/auth/login', 'refresh');
		}elseif (!$this->ion_auth->is_admin()){
			return show_error('You must be an administrator to view this page.');
		}else{
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->data['users'] = $this->ion_auth->users()->result();
			foreach ($this->data['users'] as $k => $user){
				$this->data['users'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
			}
	   		redirect(base_url('backend/dashboard'), 'refresh');
		}
	}

	public function logout(){
		$logout = $this->ion_auth->logout();
		redirect(base_url().'web_ctrl', 'refresh');
	}

	public function login(){
		$this->form_validation->set_rules('identity', 'Identity', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');
		$b_email = 0;
		if ($this->form_validation->run() == true){
			if ($this->ion_auth->username_check($this->input->post('identity'))) {
				$remember = (bool) $this->input->post('remember');
				if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember) == TRUE){
					$data = array(
							'user_id' => $this->session->userdata('user_id'),
							'ip_address' => $this->input->ip_address(),
							'login' => $this->input->post('identity')
					);
					$this->codegen_model->add('login_attempts',$data);
					redirect(base_url('backend/dashboard'), 'refresh');
				}else{
					$cantidad_intentos = $this->ion_auth->get_attempts_num($this->input->post('identity'));
					if ( $cantidad_intentos != 0 && $cantidad_intentos <= 3) {
						$cantidad = 4 - $cantidad_intentos;
						$b_email = 1;
						$mensaje = '<div class="alert alert-block alert-danger fade in"><button data-dismiss="alert" class="close close-sm" type="button"><i class="fa fa-times"></i></button><strong>Error!</strong> Le quedan '.$cantidad.' intentos antes de bloquear su cuenta.</div>';
						$this->session->set_flashdata('message', $mensaje);
					}else if ($this->ion_auth->get_attempts_num($this->input->post('identity')) == 4) {
						$b_email = 1;
						$mensaje = '<div class="alert alert-block alert-danger fade in"><button data-dismiss="alert" class="close close-sm" type="button"><i class="fa fa-times"></i></button><strong>Error!</strong> Lo sentimos se ha bloqueado su cuenta, en unos minutos recibirá un email con instrucciones para desbloquearla, sentimos las molestias pero velamos por su seguridad.</div>';
						$this->session->set_flashdata('message', $mensaje);
					}else{
						$data = array(
								'user' => $this->input->post('identity'),
								'password' => $this->input->post('password'),
								'ip_address' => $this->input->ip_address()
						);
						$this->codegen_model->addNoAudit('login_errors',$data);
						$mensaje = '<div class="alert alert-block alert-danger fade in"><button data-dismiss="alert" class="close close-sm" type="button"><i class="fa fa-times"></i></button><strong>Error!</strong> Los datos ingresados son incorrectos.</div>';
						$this->session->set_flashdata('message', $mensaje);
					}

					/*if ($b_email == 1) {
						$email = $this->codegen_model->row('configuraciones','*','id = 3');
						$remitente = $this->codegen_model->row('configuraciones','*','id = 4');
						$datos = array(
							'mensaje_head' => 'Ha recibido un nuevo mensaje.',
							'Email' => $this->input->post('identity'),
							'Mensaje' => 'Intento fallido al querer loguearse, por favor ingrese los datos correctos de lo contrario se bloqueara su cuenta.'
						);
						$email = array(
							'vista' => 'backend/emails/contactar_administrador/email',
							'titulo' => 'Intento fallido de login',
							'email_destino' => $this->input->post('identity')
						);

						$this->backend_lib->enviarEmail($datos, $email);
					}*/

					redirect(base_url().'backend/auth/login');
				}
			}else{
				$data = array(
						'user' => $this->input->post('identity'),
						'password' => $this->input->post('password'),
						'ip_address' => $this->input->ip_address()
				);
				$this->codegen_model->addNoAudit('login_errors',$data);
				$mensaje = '<div class="alert alert-block alert-danger fade in"><button data-dismiss="alert" class="close close-sm" type="button"><i class="fa fa-times"></i></button><strong>Error!</strong> El usuario ingresado es incorrecto.</div>';
				$this->session->set_flashdata('message', $mensaje);
				redirect(base_url().'backend/auth/login');
			}
		}else{
			$texto = $this->codegen_model->row('configuraciones','*','id = 1');
			$data_plantilla = array(
				'title' => 'Login',
				'texto' => $texto->valor,
				'ip' => $this->input->ip_address()
			);
			$datos_plantilla["title"] = ucwords("login");
			$datos_plantilla["contenido_main"] = $this->parser->parse('backend/auth/login', $data_plantilla, true);
			$this->load->view('template/login', $datos_plantilla);
		}
	}

	public function change_password(){
		$this->form_validation->set_rules('old', $this->lang->line('change_password_validation_old_password_label'), 'required');
		$this->form_validation->set_rules('new', $this->lang->line('change_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
		$this->form_validation->set_rules('new_confirm', $this->lang->line('change_password_validation_new_password_confirm_label'), 'required');

		if (!$this->ion_auth->logged_in()){
			redirect('backend/auth/login', 'refresh');
		}

		$user = $this->ion_auth->user()->row();

		if ($this->form_validation->run() == false)		{
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
			$this->data['old_password'] = array(
				'name' => 'old',
				'id'   => 'old',
				'type' => 'password',
				'class' => 'form-control'
			);
			$this->data['new_password'] = array(
				'name' => 'new',
				'id'   => 'new',
				'type' => 'password',
				'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
				'class' => 'form-control'
			);
			$this->data['new_password_confirm'] = array(
				'name' => 'new_confirm',
				'id'   => 'new_confirm',
				'type' => 'password',
				'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
				'class' => 'form-control',
			);
			$this->data['user_id'] = array(
				'name'  => 'user_id',
				'id'    => 'user_id',
				'type'  => 'hidden',
				'value' => $user->id,
				'class' => 'form-control',
			);

			$datos_plantilla["title"] = ucwords("Cambiar password");
			$datos_plantilla["container_id"] = "login";
			$datos_plantilla["contenido_main"] = $this->load->view('backend/auth/change_password', $this->data, true);

			$this->load->view('template/backend', $datos_plantilla);
		}
		else{
			$identity = $this->session->userdata($this->config->item('identity', 'ion_auth'));

			$change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));

			if ($change){
				$mensaje = '<div class="alert alert-block alert-success fade in"><button data-dismiss="alert" class="close close-sm" type="button"><i class="fa fa-times"></i></button><strong>Error!</strong> La contraseña ha sido cambiada exitosamente.</div>';
				$this->session->set_flashdata('message', $mensaje);
				$this->logout();
			}
			else{
				$mensaje = '<div class="alert alert-block alert-danger fade in"><button data-dismiss="alert" class="close close-sm" type="button"><i class="fa fa-times"></i></button><strong>Error!</strong> La contraseña actual ingresada es incorrecta.</div>';
				$this->session->set_flashdata('message', $mensaje);
				redirect('backend/auth/change_password', 'refresh');
			}
		}
	}

	public function forgot_password(){
		$this->form_validation->set_rules('email', $this->lang->line('forgot_password_validation_email_label'), 'required');
		if ($this->form_validation->run() == true){
            $identity = $this->ion_auth->where('email', strtolower($this->input->post('email')))->users()->row();
            if(empty($identity)) {
                $mensaje = '<div class="alert alert-block alert-danger fade in"><button data-dismiss="alert" class="close close-sm" type="button"><i class="fa fa-times"></i></button><strong>Error!</strong> No hay registro de dirección de correo electrónico que ha ingresado.</div>';
				$this->session->set_flashdata('message', $mensaje);
				redirect("backend/auth/login", 'refresh');
            }

			$forgotten = $this->ion_auth->forgotten_password($identity->{$this->config->item('identity', 'ion_auth')});

			if ($forgotten){
				$mensaje = '<div class="alert alert-block alert-success fade in"><button data-dismiss="alert" class="close close-sm" type="button"><i class="fa fa-times"></i></button> Se envio un Email para poder Restablecer la contraseña.</div>';
				$this->session->set_flashdata('message', $mensaje);

				$datos = array(
					'mensaje_head' => 'Ha recibido un nuevo mensaje.',
					'Email' => $forgotten['identity'],
					'Mensaje' => 'Reinicio de clave.',
					'identity' => $forgotten['identity'],
					'forgotten_password_code' => $forgotten['forgotten_password_code']
				);
				$email = array(
					'vista' => 'backend/auth/email/forgot_password',
					'titulo' => 'Reinicio de clave',
					'email_destino' => $forgotten['identity']
				);

				$this->backend_lib->enviarEmail($datos, $email);

				redirect("backend/auth/login", 'refresh');
			}else{
				$mensaje = '<div class="alert alert-block alert-danger fade in"><button data-dismiss="alert" class="close close-sm" type="button"><i class="fa fa-times"></i></button><strong>Error!</strong> '.$this->ion_auth->errors().'</div>';
				redirect("backend/auth/forgot_password", 'refresh');
			}
		}else{
			$this->data['email'] = array('name' => 'email', 'id' => 'email');

			if ( $this->config->item('identity', 'ion_auth') == 'username' ){
				$this->data['identity_label'] = $this->lang->line('forgot_password_username_identity_label');
			}else{
				$this->data['identity_label'] = $this->lang->line('forgot_password_email_identity_label');
			}

			//set any errors and display the form
			$m = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$mensaje = '<div class="alert alert-block alert-danger fade in"><button data-dismiss="alert" class="close close-sm" type="button"><i class="fa fa-times"></i></button><strong>Error!</strong> '.$m.'</div>';
			$this->data['message'] = $mensaje;
			//$this->_render_page('backend/auth/forgot_password', $this->data);

			$vista_externa = array(
				'title' => ucwords("Olvide Mi Contraseña"),
				'contenido_main' => $this->load->view('backend/auth/forgot_password', $this->data, true)
			);

			$this->load->view('template/view', $vista_externa);
		}
	}

	public function reset_password($code = NULL){
		if (!$code) show_404();

		$user = $this->ion_auth->forgotten_password_check($code);

		if ($user){
			$this->form_validation->set_rules('new', $this->lang->line('reset_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
			$this->form_validation->set_rules('new_confirm', $this->lang->line('reset_password_validation_new_password_confirm_label'), 'required');

			if ($this->form_validation->run() == false){

				$this->session->flashdata('message', validation_errors());

				$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
				$this->data['new_password'] = array(
					'name' => 'new',
					'id'   => 'new',
					'type' => 'password',
					'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
				);
				$this->data['new_password_confirm'] = array(
					'name' => 'new_confirm',
					'id'   => 'new_confirm',
					'type' => 'password',
					'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
				);
				$this->data['user_id'] = array(
					'name'  => 'user_id',
					'id'    => 'user_id',
					'type'  => 'hidden',
					'value' => $user->id,
				);
				$this->data['csrf'] = $this->_get_csrf_nonce();
				$this->data['code'] = $code;

				//$this->_render_page('backend/auth/reset_password', $this->data);

				$texto = $this->codegen_model->row('configuraciones','*','id = 15');

				$this->data['title'] = 'Recuperar password';
				$this->data['texto'] = $texto->valor;
				$this->data['ip'] = $this->input->ip_address();

				$datos_plantilla["title"] = ucwords("Recuperar password");
				$datos_plantilla["contenido_main"] = $this->parser->parse('backend/auth/reset_password', $this->data, true);
				$this->load->view('template/login', $datos_plantilla);

			}else{
				// do we have a valid request?
				if ($this->_valid_csrf_nonce() === FALSE || $user->id != $this->input->post('user_id')){
					//something fishy might be up
					$this->ion_auth->clear_forgotten_password_code($code);
					show_error($this->lang->line('error_csrf'));
				}else{
					// reinicia el password
					$identity = $user->{$this->config->item('identity', 'ion_auth')};
					$change = $this->ion_auth->reset_password($identity, $this->input->post('new'));
					if ($change){
						// reinicia las sessiones incorrectas
						$this->ion_auth->clear_login_attempts($user->email);
						$this->session->set_flashdata('message', $this->ion_auth->messages());
						$this->logout();
					}else{
						$this->session->set_flashdata('message', $this->ion_auth->errors());
						redirect('backend/auth/reset_password/' . $code, 'refresh');
					}
				}
			}
		}else{
			$mensaje = '<div class="alert alert-block alert-danger fade in"><button data-dismiss="alert" class="close close-sm" type="button"><i class="fa fa-times"></i></button><strong>Error!</strong> El codigo es incorrecto, por favor vuelva a intentar..</div>';
			$this->session->set_flashdata('message', $mensaje);
			redirect("backend/auth/login", 'refresh');
		}
	}

	private function _get_csrf_nonce(){
		$this->load->helper('string');
		$key   = random_string('alnum', 8);
		$value = random_string('alnum', 20);
		$this->session->set_flashdata('csrfkey', $key);
		$this->session->set_flashdata('csrfvalue', $value);
		return array($key => $value);
	}

	private function _valid_csrf_nonce(){
		if ($this->input->post($this->session->flashdata('csrfkey')) !== FALSE && $this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue')) {
			return TRUE;
		}else{
			return FALSE;
		}
	}

	private function _render_page($view, $data=null, $render=false){
		$this->viewdata = (empty($data)) ? $this->data: $data;
		$view_html = $this->load->view($view, $this->viewdata, $render);
		if (!$render) return $view_html;
	}
}
