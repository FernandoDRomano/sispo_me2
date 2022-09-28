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
								->select(' c.nombre, DATE_FORMAT(pe.fecha_vto,"%d-%m-%Y") as fecha_vto, DATEDIFF(pe.fecha_vto, CURRENT_TIMESTAMP() ) as dias ')
								->join('flash_clientes c', 'c.id = pe.cliente_id')
								->where('DATEDIFF(pe.fecha_vto,CURRENT_TIMESTAMP)  < 3')
								->where("'s.fecha_vto' > '0000-00-00'")
								->group_by("c.id")
								->get('flash_clientes_precios_especiales pe');

			$query_precios_servicios = $this->db
								->select(' s.nombre, DATE_FORMAT(s.fecha_vto,"%d-%m-%Y") as fecha_vto, DATEDIFF(s.fecha_vto, CURRENT_TIMESTAMP() ) as dias ')
								->where('DATEDIFF(s.fecha_vto,CURRENT_TIMESTAMP)  < 3')
								->where("'s.fecha_vto' > '0000-00-00'")
								->get('flash_servicios s');
			//echo($this->db->last_query());
			$clientes = $query_precios_especiales->result();
			$precios_servicios = $query_precios_servicios->result();
		}
		//var_dump((int)$clientes[0]->dias);die;
		$mensaje_clientes = "";
		$mensaje_servicios = "";
		if (count($clientes) > 0){
			if ((int)$clientes[0]->dias > 0 && (int)$clientes[0]->dias < 3) $mensaje_clientes = "Los siguientes CLIENTES tienen PRECIOS ESPECIALES por Vencer";
			if ((int)$clientes[0]->dias <= 0) $mensaje_clientes = "Los siguientes CLIENTES tienen PRECIOS ESPECIALES vencidos";
		}
		if (count($precios_servicios) > 0){
			if ((int)$precios_servicios[0]->dias > 0 && $precios_servicios->dias < 3) $mensaje_servicios = "Los siguientes SERVICIOS tienen Precios por Vencer";
			if ((int)$precios_servicios[0]->dias <= 0) $mensaje_servicios = "Los siguientes SERVICIOS tienen PRECIOS vencidos";
		}
		$this->data['clientes'] =  $clientes;
		$this->data['servicios'] = $precios_servicios;
		$this->data['comercial'] = $comercial;
		$this->data['mensaje_clientes'] = $mensaje_clientes;
		$this->data['mensaje_servicios'] = $mensaje_servicios;
		$this->load->view('backend/dashboard/dashboard_mensaje_comerciales', $this->data);
	}
}