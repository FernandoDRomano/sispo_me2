<?php

class Login_errors extends CI_Controller {

	private $permisos;

	function __construct() {
		parent::__construct();
		$this->permisos = $this->permisos_lib->control();
	}	
	
	function index(){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'results' => $this->codegen_model->get('login_errors','id,user,password,date,ip_address','')
		);

		$vista_externa = array(			
			'title' => ucwords("login attempts"),
			'contenido_main' => $this->load->view('backend/login_errors/login_errors_list', $vista_interna, true)
		);		
		
		$this->load->view('template/backend', $vista_externa);
	}
}

/* End of file login_errors.php */
/* Location: ./system/application/controllers/login_errors.php */