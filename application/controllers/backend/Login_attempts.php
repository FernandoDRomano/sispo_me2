<?php

class Login_attempts extends CI_Controller {

	private $permisos;

	function __construct() {
		parent::__construct();
		$this->permisos = $this->permisos_lib->control();
	}	
	
	function index(){		
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'results' => $this->codegen_model->getWhereJoin('', 'l.*, u.username AS nombre_usuario', '', 'login_attempts l', 'users u', 'l.user_id = u.id')
		);

		$vista_externa = array(			
			'title' => ucwords("login attempts"),
			'contenido_main' => $this->load->view('backend/login_attempts/login_attempts_list', $vista_interna, true)
		);		
		
		$this->load->view('template/backend', $vista_externa);
	}
}

/* End of file login_attempts.php */
/* Location: ./system/application/controllers/login_attempts.php */