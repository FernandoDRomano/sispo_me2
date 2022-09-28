<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {
	
	private $permisos;
	private $issa;

	function __construct() {
		parent::__construct();
		$this->permisos = $this->permisos_lib->control();
		$this->issa = $this->permisos_lib->isSuperAdmin();
	}

	public function index(){
		$user = $this->ion_auth->user()->row();

		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'issa' => $this->issa,
		);

		$vista_externa = array(			
			'title' => ucwords("bienvenido ".$user->apellido.', '.$user->nombre),
			'contenido_main' => $this->load->view('backend/dashboard/dashboard', $vista_interna, true)
		);		
		
		$this->load->view('template/backend', $vista_externa);
	}

	public function micuenta(){
		$user = $this->ion_auth->user()->row();

		if ($this->input->post('enviar_form')) {
			if (!empty($_FILES['foto']['tmp_name'])) {
				$img = $this->backend_lib->imagen_upload('foto', 'users');
			}else{
				$foto = $this->codegen_model->row('users', 'id, foto','id = '.$user->id);
				$img = $foto->foto;
			}

			$data = array(
				'nombre' => ucwords(strtolower($this->input->post('nombre'))),
				'apellido' => ucwords(strtolower($this->input->post('apellido'))),
				'telefono'	=> $this->input->post('telefono'),
				'celular' => $this->input->post('celular'),
				'foto' => $img
			);
			$this->ion_auth->update($user->id, $data);
			redirect("backend/dashboard", 'refresh');
		}

		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'issa' => $this->issa,
			'grupos' => $this->sudaca_backend_md->groups_getAll(),
			'grupo' => $this->codegen_model->row('users_groups','*','id = '.$user->id),
			'result' => $this->codegen_model->row('users','*','id = '.$user->id)
		);

		$vista_externa = array(			
			'title' => ucwords("Mi Cuenta"),
			'contenido_main' => $this->load->view('backend/users/users_account', $vista_interna, true)
		);		
		
		$this->load->view('template/backend', $vista_externa);
	}

	public function accesos_directos(){
		$this->data['padres'] = $this->sudaca_md->getAccesosDirectosPadres($this->session->userdata('user_id'));
		$this->data['hijos'] = $this->sudaca_md->getAccesosDirectosHijos($this->session->userdata('user_id'));
		$this->load->view('backend/dashboard/dashboard_acccesos_directos', $this->data);
	}

	public function ultimos_accesos(){
		$this->data['acessos'] = $this->sudaca_md->getUltimosAccesos($this->session->userdata('user_id'));
		$this->load->view('backend/dashboard/dashboard_ultimos_acccesos', $this->data);
	}

	public function mensaje_comerciales(){

		$clientes = [];
		$precios_servicios = [];
		$comercial = [];
		//VERIFICO SI EL USUARIO ES GRUPO COMERCIAL
		$sql = $this->db
					->select("group_id")
					->where("user_id", $this->session->userdata('user_id')) 
					->where("group_id", 4)//4:comerciales
					->get("users_groups");
		$comercial = $sql->result();
		
		if(count($comercial) >0){     

			$query_precios_especiales = $this->db
								->select(' c.nombre, DATE_FORMAT(pe.fecha_vto,"%d-%m-%Y") as fecha_vto ')
								->join('flash_clientes c', 'c.id = pe.cliente_id')
								->where('DATEDIFF(pe.fecha_vto,CURRENT_TIMESTAMP)  <= 3')
								->where("'s.fecha_vto' > '0000-00-00'")
								->group_by("c.id")
								->get('flash_clientes_precios_especiales pe');

			$query_precios_servicios = $this->db
								->select(' s.nombre, DATE_FORMAT(s.fecha_vto,"%d-%m-%Y") as fecha_vto ')
								->where('DATEDIFF(s.fecha_vto,CURRENT_TIMESTAMP)  <= 3')
								->where("'s.fecha_vto' > '0000-00-00'")
								->get('flash_servicios s');
			//echo($this->db->last_query());
			$clientes = $query_precios_especiales->result();
			$precios_servicios = $query_precios_servicios->result();
		}
		$this->data['clientes'] =  $clientes;
		$this->data['servicios'] = $precios_servicios;
		$this->data['comercial'] = $comercial;
		$this->load->view('backend/dashboard/dashboard_mensaje_comerciales', $this->data);
	}
}