<?php

class Transportes extends CI_Controller {

	private $permisos;
	
	function __construct() {
		parent::__construct();
		$this->permisos = $this->permisos_lib->control();
	}	
	
	function index(){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'results' => $this->codegen_model->get('flash_transportes','*','')
		);

		$vista_externa = array(			
			'title' => ucwords("transportes"),
			'contenido_main' => $this->load->view('components/sucursales/transportes/transportes_list', $vista_interna, true)
		);		
		
		$this->load->view('template/backend', $vista_externa);
	}

	function add(){
		if ($this->input->post('enviar_form')){
			$data = array(
					'nombre' => $this->input->post('nombre'),
					'descripcion' => $this->input->post('descripcion')
				);
			$this->codegen_model->add('flash_transportes',$data);
			redirect(base_url().'sucursales/transportes');
		}		  
   
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos
		);

		$vista_externa = array(			
			'title' => ucwords("transportes"),
			'contenido_main' => $this->load->view('components/sucursales/transportes/transportes_add', $vista_interna, true)
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
			$this->codegen_model->edit('flash_transportes',$data,'id',$this->input->post('id'));
			redirect(base_url().'sucursales/transportes');
		}

		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'result' => $this->codegen_model->row('flash_transportes','*','id = '.$id)
		);

		$vista_externa = array(			
			'title' => ucwords("transportes"),
			'contenido_main' => $this->load->view('components/sucursales/transportes/transportes_edit', $vista_interna, true)
		);		  
   
		$this->load->view('template/backend', $vista_externa);
	}

	function view($id){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'result' => $this->codegen_model->row('flash_transportes','*','id = '.$id)
		);

		$vista_externa = array(			
			'title' => ucwords("transportes"),
			'contenido_main' => $this->load->view('components/sucursales/transportes/transportes_view', $vista_interna, true)
		);		  
   
		$this->load->view('template/fancybox', $vista_externa);
	}

	function json($id){
		$json = $this->codegen_model->row('flash_transportes','*','id = '.$id);
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}

	function json_all(){
		$json = $this->codegen_model->get('flash_transportes','*','');
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}
	
	function delete($id){
		$this->codegen_model->delete('flash_transportes','id',$id);             
	}
}

/* End of file transportes.php */
/* Location: ./system/application/controllers/transportes.php */