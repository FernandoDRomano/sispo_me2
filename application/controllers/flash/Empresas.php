<?php

class Empresas extends CI_Controller {

	private $permisos;
	
	function __construct() {
		parent::__construct();
		$this->permisos = $this->permisos_lib->control();
	}	
	
	function index(){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'results' => $this->codegen_model->get('flash_empresas','*','')
		);

		$vista_externa = array(			
			'title' => ucwords("empresas"),
			'contenido_main' => $this->load->view('components/flash/empresas/empresas_list', $vista_interna, true)
		);		
		
		$this->load->view('template/backend', $vista_externa);
	}

	function add(){
		if ($this->input->post('enviar_form')){
			$data = array(
					'nombre' => $this->input->post('nombre'),
					'descripcion' => $this->input->post('descripcion')
				);
			$this->codegen_model->add('flash_empresas',$data);
			redirect(base_url().'flash/empresas');
		}		  
   
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos
		);

		$vista_externa = array(			
			'title' => ucwords("empresas"),
			'contenido_main' => $this->load->view('components/flash/empresas/empresas_add', $vista_interna, true)
		);		  
   
		$this->load->view('template/backend', $vista_externa);
	}	

	function edit($id){
		if ($this->input->post('enviar_form')){
			$data = array(
					'nombre' => $this->input->post('nombre'),
					'descripcion' => $this->input->post('descripcion')
				);
			$this->codegen_model->edit('flash_empresas',$data,'id',$this->input->post('id'));
			redirect(base_url().'flash/empresas');
		}

		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'result' => $this->codegen_model->row('flash_empresas','*','id = '.$id)
		);

		$vista_externa = array(			
			'title' => ucwords("empresas"),
			'contenido_main' => $this->load->view('components/flash/empresas/empresas_edit', $vista_interna, true)
		);		  
   
		$this->load->view('template/backend', $vista_externa);
	}

	function view($id){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'result' => $this->codegen_model->row('flash_empresas','*','id = '.$id)
		);

		$vista_externa = array(			
			'title' => ucwords("empresas"),
			'contenido_main' => $this->load->view('components/flash/empresas/empresas_view', $vista_interna, true)
		);		  
   
		$this->load->view('template/fancybox', $vista_externa);
	}

	function json($id){
		$json = $this->codegen_model->row('flash_empresas','*','id = '.$id);
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}

	function json_all(){
		$json = $this->codegen_model->get('flash_empresas','*','');
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}
	
	function delete($id){
		$this->codegen_model->delete('flash_empresas','id',$id);             
	}
}

/* End of file empresas.php */
/* Location: ./system/application/controllers/empresas.php */