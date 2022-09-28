<?php

class Sucursales extends CI_Controller {

	private $permisos;
	
	function __construct() {
		parent::__construct();
		$this->permisos = $this->permisos_lib->control();
	}	
	
	function index(){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'results' => $this->codegen_model->get('flash_sucursales','*','')
		);

		$vista_externa = array(			
			'title' => ucwords("sucursales"),
			'contenido_main' => $this->load->view('components/sucursales/sucursales/sucursales_list', $vista_interna, true)
		);		
		
		$this->load->view('template/backend', $vista_externa);
	}

	function add(){
		if ($this->input->post('enviar_form')){
			$data = array(
					'nombre' => $this->input->post('nombre'),
					'descripcion' => $this->input->post('descripcion')
				);
			$this->codegen_model->add('flash_sucursales',$data);
			redirect(base_url().'sucursales/sucursales');
		}		  
   
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos
		);

		$vista_externa = array(			
			'title' => ucwords("sucursales"),
			'contenido_main' => $this->load->view('components/sucursales/sucursales/sucursales_add', $vista_interna, true)
		);		  
   
		$this->load->view('template/backend', $vista_externa);
	}	

	function edit($id){
		if ($this->input->post('enviar_form')){
			$data = array(
					'nombre' => $this->input->post('nombre'),
					'descripcion' => $this->input->post('descripcion'),
					'update' => date('Y-m-d H:m:s')
				);
			$this->codegen_model->edit('flash_sucursales',$data,'id',$this->input->post('id'));
			redirect(base_url().'sucursales/sucursales');
		}

		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'result' => $this->codegen_model->row('flash_sucursales','*','id = '.$id)
		);

		$vista_externa = array(			
			'title' => ucwords("sucursales"),
			'contenido_main' => $this->load->view('components/sucursales/sucursales/sucursales_edit', $vista_interna, true)
		);		  
   
		$this->load->view('template/backend', $vista_externa);
	}

	function view($id){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'result' => $this->codegen_model->row('flash_sucursales','*','id = '.$id)
		);

		$vista_externa = array(			
			'title' => ucwords("sucursales"),
			'contenido_main' => $this->load->view('components/sucursales/sucursales/sucursales_view', $vista_interna, true)
		);		  
   
		$this->load->view('template/fancybox', $vista_externa);
	}

	function json($id){
		$json = $this->codegen_model->row('flash_sucursales','*','id = '.$id);
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}

	function json_all(){
		$json = $this->codegen_model->get('flash_sucursales','*','');
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}
	
	function delete($id){
		$this->codegen_model->delete('flash_sucursales','id',$id);             
	}
}

/* End of file sucursales.php */
/* Location: ./system/application/controllers/sucursales.php */