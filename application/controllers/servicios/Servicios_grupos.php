<?php

class Servicios_grupos extends CI_Controller {

	private $permisos;
	
	function __construct() {
		parent::__construct();
		$this->permisos = $this->permisos_lib->control();
	}	
	
	function index(){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'results' => $this->codegen_model->get('flash_servicios_grupos','*','')
		);

		$vista_externa = array(			
			'title' => ucwords("grupos"),
			'contenido_main' => $this->load->view('components/servicios/servicios_grupos/servicios_grupos_list', $vista_interna, true)
		);		
		
		$this->load->view('template/backend', $vista_externa);
	}

	function add(){
		if ($this->input->post('enviar_form')){
			$data = array(
					'nombre' => $this->input->post('nombre')
				);
			$this->codegen_model->add('flash_servicios_grupos',$data);
			redirect(base_url().'servicios/servicios_grupos');
		}		  
   
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos
		);

		$vista_externa = array(			
			'title' => ucwords("grupos"),
			'contenido_main' => $this->load->view('components/servicios/servicios_grupos/servicios_grupos_add', $vista_interna, true)
		);		  
   
		$this->load->view('template/backend', $vista_externa);
	}	

	function edit($id){
		if ($this->input->post('enviar_form')){
			$data = array(
					'nombre' => $this->input->post('nombre'),
					'update' => date('Y-m-d H:m:s')
				);
			$this->codegen_model->edit('flash_servicios_grupos',$data,'id',$this->input->post('id'));
			redirect(base_url().'servicios/servicios_grupos');
		}

		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'result' => $this->codegen_model->row('flash_servicios_grupos','*','id = '.$id)
		);

		$vista_externa = array(			
			'title' => ucwords("grupos"),
			'contenido_main' => $this->load->view('components/servicios/servicios_grupos/servicios_grupos_edit', $vista_interna, true)
		);		  
   
		$this->load->view('template/backend', $vista_externa);
	}

	function view($id){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'result' => $this->codegen_model->row('flash_servicios_grupos','*','id = '.$id)
		);

		$vista_externa = array(			
			'title' => ucwords("grupos"),
			'contenido_main' => $this->load->view('components/servicios/servicios_grupos/servicios_grupos_view', $vista_interna, true)
		);		  
   
		$this->load->view('template/fancybox', $vista_externa);
	}

	function json($id){
		$json = $this->codegen_model->row('flash_servicios_grupos','*','id = '.$id);
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}

	function json_all(){
		$json = $this->codegen_model->get('flash_servicios_grupos','*','');
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}
	
	function delete($id){
		$this->codegen_model->delete('flash_servicios_grupos','id',$id);             
	}
}

/* End of file servicios_grupos.php */
/* Location: ./system/application/controllers/servicios_grupos.php */