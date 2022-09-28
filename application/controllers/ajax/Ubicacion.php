<?php

class Ubicacion extends CI_Controller {	
	
	function __construct() {
		parent::__construct();
	}	
	
	public function provincia($query){
		$json = $this->codegen_model->row('ubicacion_provincias','*','nombre LIKE "%'.$query.'%"');
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}
	
	public function provincias(){
		$query = $this->input->get('search');
		$json = $this->codegen_model->get('ubicacion_provincias','*','nombre LIKE "%'.$query.'%"');
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}
	
	public function departamentos(){
		$query = $this->input->get('search');
		$id = $this->input->get('provincia');
		$json = $this->codegen_model->get('ubicacion_departamentos','*','nombre LIKE "%'.$query.'%" AND provincia_id = '.$id);
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}

	public function localidades(){
		$id = $this->input->get('search');
		$json = $this->codegen_model->get('ubicacion_localidades','*','departamento_id = '.$id);
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}
}

/* End of file Ubicacion.php */
/* Location: ./system/application/controllers/Ubicacion.php */