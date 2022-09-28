<?php

class Distribuidores extends CI_Controller {

	private $permisos;
	
	function __construct() {
		parent::__construct();
		$this->permisos = $this->permisos_lib->control();
	}	
	
	function index(){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'results' => $this->codegen_model->get('flash_distribuidores','*','')
		);

		$vista_externa = array(			
			'title' => ucwords("distribuidores"),
			'contenido_main' => $this->load->view('components/sucursales/distribuidores/distribuidores_list', $vista_interna, true)
		);		
		
		$this->load->view('template/backend', $vista_externa);
	}

	function add(){
		if ($this->input->post('enviar_form')){
			$data = array(
					'apellido_nombre' => $this->input->post('apellido_nombre'),
					'domicilio' => $this->input->post('domicilio'),
					'localidad' => $this->input->post('localidad'),
					'codigo_postal' => $this->input->post('codigo_postal'),
					'telefonos' => $this->input->post('telefonos'),
					'activo' => $this->input->post('activo')
				);
			$this->codegen_model->add('flash_distribuidores',$data);
			redirect(base_url().'sucursales/distribuidores');
		}		  
   
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos
		);

		$vista_externa = array(			
			'title' => ucwords("distribuidores"),
			'contenido_main' => $this->load->view('components/sucursales/distribuidores/distribuidores_add', $vista_interna, true)
		);		  
   
		$this->load->view('template/backend', $vista_externa);
	}	

	function edit($id){
		if ($this->input->post('enviar_form')){
			$data = array(
					'apellido_nombre' => $this->input->post('apellido_nombre'),
					'domicilio' => $this->input->post('domicilio'),
					'localidad' => $this->input->post('localidad'),
					'codigo_postal' => $this->input->post('codigo_postal'),
					'telefonos' => $this->input->post('telefonos'),
					'activo' => $this->input->post('activo'),
					'update' => date('Y-m-d H:m:s')
				);
			$this->codegen_model->edit('flash_distribuidores',$data,'id',$this->input->post('id'));
			redirect(base_url().'sucursales/distribuidores');
		}

		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'result' => $this->codegen_model->row('flash_distribuidores','*','id = '.$id)
		);

		$vista_externa = array(			
			'title' => ucwords("distribuidores"),
			'contenido_main' => $this->load->view('components/sucursales/distribuidores/distribuidores_edit', $vista_interna, true)
		);		  
   
		$this->load->view('template/backend', $vista_externa);
	}

	function view($id){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'result' => $this->codegen_model->row('flash_distribuidores','*','id = '.$id)
		);

		$vista_externa = array(			
			'title' => ucwords("distribuidores"),
			'contenido_main' => $this->load->view('components/sucursales/distribuidores/distribuidores_view', $vista_interna, true)
		);		  
   
		$this->load->view('template/fancybox', $vista_externa);
	}

	function json($id){
		$json = $this->codegen_model->row('flash_distribuidores','*','id = '.$id);
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}

	function json_all(){
		$json = $this->codegen_model->get('flash_distribuidores','*','');
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}
	
	function delete($id){
		$this->codegen_model->delete('flash_distribuidores','id',$id);             
	}
}

/* End of file distribuidores.php */
/* Location: ./system/application/controllers/distribuidores.php */