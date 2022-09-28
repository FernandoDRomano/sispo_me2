<?php

class {controller_name} extends CI_Controller {

	private $permisos;
	
	function __construct() {
		parent::__construct();
		$this->permisos = $this->permisos_lib->control();
	}	
	
	function index(){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'results' => $this->codegen_model->get('{table}','*','')
		);

		$vista_externa = array(			
			'title' => ucwords("{controller_name_l}"),
			'contenido_main' => $this->load->view('{carpeta}/{controller_name_l}_list', $vista_interna, true)
		);		
		
		$this->load->view('template/backend', $vista_externa);
	}

	function add(){
		if ($this->input->post('enviar_form')){
			$data = array(
					{edit_data}
				);
			$this->codegen_model->add('{table}',$data);
			redirect(base_url().'{controller_name_l}');
		}		  
   
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos
		);

		$vista_externa = array(			
			'title' => ucwords("{controller_name_l}"),
			'contenido_main' => $this->load->view('{carpeta}/{controller_name_l}_add', $vista_interna, true)
		);		  
   
		$this->load->view('template/backend', $vista_externa);
	}	

	function edit($id){
		if ($this->input->post('enviar_form')){
			$data = array(
					{edit_data}
				);
			$this->codegen_model->edit('{table}',$data,'{primaryKey}',$this->input->post('{primaryKey}'));
			redirect(base_url().'{controller_name_l}');
		}

		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'result' => $this->codegen_model->row('{table}','*','{primaryKey} = '.$id)
		);

		$vista_externa = array(			
			'title' => ucwords("{controller_name_l}"),
			'contenido_main' => $this->load->view('{carpeta}/{controller_name_l}_edit', $vista_interna, true)
		);		  
   
		$this->load->view('template/backend', $vista_externa);
	}

	function view($id){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'result' => $this->codegen_model->row('{table}','*','{primaryKey} = '.$id)
		);

		$vista_externa = array(			
			'title' => ucwords("{controller_name_l}"),
			'contenido_main' => $this->load->view('{carpeta}/{controller_name_l}_view', $vista_interna, true)
		);		  
   
		$this->load->view('template/view', $vista_externa);
	}

	function json($id){
		$json = $this->codegen_model->row('{table}','*','{primaryKey} = '.$id);
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}

	function json_all(){
		$json = $this->codegen_model->get('{table}','*','');
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}
	
	function delete($id){
		$this->codegen_model->delete('{table}','{primaryKey}',$id);             
	}
}

/* End of file {controller_name_l}.php */
/* Location: ./system/application/controllers/{controller_name_l}.php */