<?php

class Sudaca_errores extends CI_Controller {

	function __construct() {
		parent::__construct();
	}	

	public function _404(){
		$vista_interna = array(
			'error' => ''
		);

		$vista_externa = array(			
			'title' => ucwords("pagina no encontrada"),
			'contenido_main' => $this->load->view('backend/error/404', $vista_interna, true)
		);		
		
		$this->load->view('template/error', $vista_externa);
	}

}