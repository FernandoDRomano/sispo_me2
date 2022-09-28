<?php

class Piezas_estados extends CI_Controller {

	private $permisos;
	
	function __construct() {
		parent::__construct();
		$this->permisos = $this->permisos_lib->control();
	}	
	
	function index(){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'results' => $this->codegen_model->get('flash_piezas_estados','*','')
		);

		$vista_externa = array(			
			'title' => ucwords("estados"),
			'contenido_main' => $this->load->view('components/piezas/piezas_estados/piezas_estados_list', $vista_interna, true)
		);		
		
		$this->load->view('template/backend', $vista_externa);
	}

	function add(){
		if ($this->input->post('enviar_form')){
			$data = array(
					'nombre' => $this->input->post('nombre'),
					'descripcion' => $this->input->post('descripcion')
				);
			$this->codegen_model->add('flash_piezas_estados',$data);
			redirect(base_url().'piezas/piezas_estados');
		}		  
   
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos
		);

		$vista_externa = array(			
			'title' => ucwords("estados"),
			'contenido_main' => $this->load->view('components/piezas/piezas_estados/piezas_estados_add', $vista_interna, true)
		);		  
   
		$this->load->view('template/backend', $vista_externa);
	}	

	function edit($id){
		if ($this->input->post('enviar_form')){
			$data = array(
					'nombre' => $this->input->post('nombre'),
					'descripcion' => $this->input->post('descripcion')
				);
			$this->codegen_model->edit('flash_piezas_estados',$data,'id',$this->input->post('id'));
			redirect(base_url().'piezas/piezas_estados');
		}

		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'result' => $this->codegen_model->row('flash_piezas_estados','*','id = '.$id)
		);

		$vista_externa = array(			
			'title' => ucwords("estados"),
			'contenido_main' => $this->load->view('components/piezas/piezas_estados/piezas_estados_edit', $vista_interna, true)
		);		  
   
		$this->load->view('template/backend', $vista_externa);
	}

	function view($id){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'result' => $this->codegen_model->row('flash_piezas_estados','*','id = '.$id)
		);

		$vista_externa = array(			
			'title' => ucwords("estados"),
			'contenido_main' => $this->load->view('components/piezas/piezas_estados/piezas_estados_view', $vista_interna, true)
		);		  
   
		$this->load->view('template/fancybox', $vista_externa);
	}

	function json($id){
		$json = $this->codegen_model->row('flash_piezas_estados','*','id = '.$id);
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}

	function json_all(){
		$json = $this->codegen_model->get('flash_piezas_estados','*','');
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}
	
	function delete($id){
		$this->codegen_model->delete('flash_piezas_estados','id',$id);             
	}

	function variables($id){
		if ($this->input->post('enviar_form')){
			$data = array(
				'pieza_estado_id' => $id,
				'nombre' => $this->input->post('nombre')
			);
			$this->codegen_model->add('flash_piezas_estados_variables',$data);
			redirect(base_url().'piezas/piezas_estados/variables/'.$id);
		}		  
   
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'variables' => $this->codegen_model->get('flash_piezas_estados_variables', '*', 'pieza_estado_id = '.$id)
		);

		$vista_externa = array(			
			'title' => ucwords("estados"),
			'contenido_main' => $this->load->view('components/piezas/piezas_estados/piezas_estados_variables', $vista_interna, true)
		);		  
   
		$this->load->view('template/backend', $vista_externa);
	}
}

/* End of file piezas_estados.php */
/* Location: ./system/application/controllers/piezas_estados.php */