<?php

use RestServer\RestController;
require APPPATH . '/libraries/RestController.php';
require APPPATH . '/libraries/Format.php';



class RestServer extends RestController {

	function __construct() {
		parent::__construct();
	}	

	public function test_get(){
	    $array = array("hola","Mundo","CodeIgniter");
	    $this->response($array);
	}	
}
