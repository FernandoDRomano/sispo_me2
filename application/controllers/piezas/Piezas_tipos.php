<?php

class Piezas_tipos extends CI_Controller {

	private $permisos;
	
	function __construct() {
		parent::__construct();
		$this->permisos = $this->permisos_lib->control();
	}	
	
	function index(){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'results' => $this->codegen_model->get('flash_piezas_tipos','*','')
		);

		$vista_externa = array(			
			'title' => ucwords("tipos"),
			'contenido_main' => $this->load->view('components/piezas/piezas_tipos/piezas_tipos_list', $vista_interna, true)
		);		
		
		$this->load->view('template/backend', $vista_externa);
	}

	function add(){
		if ($this->input->post('enviar_form')){
			$data = array(
					'nombre' => $this->input->post('nombre'),
					'descripcion' => $this->input->post('descripcion')
				);
			$this->codegen_model->add('flash_piezas_tipos',$data);
			redirect(base_url().'piezas/piezas_tipos');
		}		  
   
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos
		);

		$vista_externa = array(			
			'title' => ucwords("tipos"),
			'contenido_main' => $this->load->view('components/piezas/piezas_tipos/piezas_tipos_add', $vista_interna, true)
		);		  
   
		$this->load->view('template/backend', $vista_externa);
	}	

	function edit($id){
		if ($this->input->post('enviar_form')){
			$data = array(
					'nombre' => $this->input->post('nombre'),
					'descripcion' => $this->input->post('descripcion')
				);
			$this->codegen_model->edit('flash_piezas_tipos',$data,'id',$this->input->post('id'));
			redirect(base_url().'piezas/piezas_tipos');
		}

		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'result' => $this->codegen_model->row('flash_piezas_tipos','*','id = '.$id)
		);

		$vista_externa = array(			
			'title' => ucwords("tipos"),
			'contenido_main' => $this->load->view('components/piezas/piezas_tipos/piezas_tipos_edit', $vista_interna, true)
		);		  
   
		$this->load->view('template/backend', $vista_externa);
	}

	function view($id){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'result' => $this->codegen_model->row('flash_piezas_tipos','*','id = '.$id)
		);

		$vista_externa = array(			
			'title' => ucwords("tipos"),
			'contenido_main' => $this->load->view('components/piezas/piezas_tipos/piezas_tipos_view', $vista_interna, true)
		);		  
   
		$this->load->view('template/fancybox', $vista_externa);
	}

	function json($id){
		$json = $this->codegen_model->row('flash_piezas_tipos','*','id = '.$id);
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}

	function json_all(){
		$json = $this->codegen_model->get('flash_piezas_tipos','*','');
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}
	
	function delete($id){
		$this->codegen_model->delete('flash_piezas_tipos','id',$id);             
	}
}

/* End of file piezas_tipos.php */
/* Location: ./system/application/controllers/piezas_tipos.php */