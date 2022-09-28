<?php

class Web extends CI_Controller {

	function __construct() {
		parent::__construct();
	}	

	public function index(){	
		$vista_interna = array(
			'result' => ''
		);

		$vista_externa = array(			
			'contenido_main' => $this->load->view('frontend/public/index', $vista_interna, true),	
			'configuracion' => $this->frontend_lib->configuraciones(ucwords("bienvenido a la web"), '')
		);		
		
		$this->load->view('template/frontend', $vista_externa);
	}

}
