<?php

class Sudaca_controller extends CI_Controller {

	function __construct() {
		parent::__construct();
	}	

	function index(){
		redirect(base_url().'web_ctrl');
	}	
}

/* End of file contenido.php */
/* Location: ./system/application/controllers/sudaca_controller.php */