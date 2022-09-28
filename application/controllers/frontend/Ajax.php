<?php

class Ajax extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('sudaca_frontend_md');

		$this->lang->load('frontend', $this->frontend_lib->loadIdioma());
	}	
		
}

/* End of file ajax.php */
/* Location: ./system/application/controllers/ajax.php */