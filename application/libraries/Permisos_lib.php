<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class permisos_lib
{
	
	public function __construct()
	{
		$this->load->model('codegen_model');
		$this->load->model('sudaca_backend_md');
	}

	public function __call($method, $arguments)
	{
		if (!method_exists( $this->ion_auth_model, $method) )
		{
			throw new Exception('Undefined method Ion_auth::' . $method . '() called');
		}

		return call_user_func_array( array($this->ion_auth_model, $method), $arguments);
	}

	public function __get($var)
	{
		return get_instance()->$var;
	}

	public function control(){		
		// urls
		$link = $this->uri->segment(2);
		$link_total = $this->uri->segment(1).'/'.$this->uri->segment(2);

		// controla la session
		if(!$this->session->userdata('user_id')) redirect(base_url().'backend/auth/login', 'refresh');	

		// valida los permisos
		$grupo = $this->sudaca_backend_md->permisos_buscarGrupo($this->session->userdata('user_id'));
		$menu = $this->sudaca_backend_md->permisos_buscarPermiso($link, $grupo->group_id);
		if ($menu) {
			if($menu->read == 0){
				redirect('backend/dashboard', 'refresh');	
			}
		}else{
			redirect('backend/dashboard', 'refresh');
		}
			

		// registra log
		$this->backend_lib->log();
		
		// retorna permisos
		$menu = $this->sudaca_backend_md->permisos_getByLink($link_total);
		return $permisos = $this->codegen_model->permisos_efectivos($menu->id, $this->session->userdata('user_id'));
	}

	public function isSuperAdmin(){
		$user_row = $this->ion_auth->user()->row();
       	if (!$this->ion_auth->in_group(1, $user_row->id)) return 0;
       	else return 1;
	}

	
}