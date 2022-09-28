<?php

class Opciones extends CI_Controller {

	private $permisos;

	function __construct() {
		parent::__construct();
		$this->permisos = $this->permisos_lib->control();
	}	
	
	function index(){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'results' => $this->codegen_model->get('opciones','id,nombre,descripcion','')
		);

		$vista_externa = array(			
			'title' => ucwords("opciones"),
			'contenido_main' => $this->load->view('backend/opciones/opciones_list', $vista_interna, true)
		);		
		
		$this->load->view('template/backend', $vista_externa);
	}

	function add(){
		if ($this->input->post('enviar_form')){
			$data = array(
					'nombre' => $this->input->post('nombre'),
					'descripcion' => $this->input->post('descripcion')
				);
			$this->codegen_model->add('opciones',$data);
			redirect(base_url().'backend/opciones');
		}

		$vista_interna = array(
			'permisos_efectivos' => $this->permisos
		);

		$vista_externa = array(			
			'title' => ucwords("opciones"),
			'contenido_main' => $this->load->view('backend/opciones/opciones_add', $vista_interna, true)
		);		
		
		$this->load->view('template/backend', $vista_externa);
	}	

	function edit($id){
		if ($this->input->post('enviar_form')){
			$data = array(
					'nombre' => $this->input->post('nombre'),
					'descripcion' => $this->input->post('descripcion')
				);
			$this->codegen_model->edit('opciones',$data,'id',$this->input->post('id'));
			redirect(base_url().'backend/opciones/');
		}

		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'result' => $this->codegen_model->row('opciones','*','id = '.$id)
		);

		$vista_externa = array(			
			'title' => ucwords("opciones"),
			'contenido_main' => $this->load->view('backend/opciones/opciones_edit', $vista_interna, true)
		);		
		
		$this->load->view('template/backend', $vista_externa);
	}

	function view($id){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'result' => $this->codegen_model->row('opciones','*','id = '.$id)
		);

		$vista_externa = array(			
			'title' => ucwords("opciones"),
			'contenido_main' => $this->load->view('backend/opciones/opciones_view', $vista_interna, true)
		);		
		
		$this->load->view('template/view', $vista_externa);
	}
	
	function delete($ID){
		$this->codegen_model->delete('opciones','id',$ID);             
		redirect(base_url().'backend/opciones/');
	}

	function opcion($id){
		if ($this->input->post('enviar_form')){
			$data = array(
				'opcion_id' => $id,
				'nombre' => $this->input->post('nombre')
			);
			$this->codegen_model->add('opciones_variables',$data);
			redirect(base_url().'backend/opciones/opcion/'.$id);
		}

		$opcion = $this->codegen_model->row('opciones','*','id = '.$id);

		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'results' => $this->sudaca_md->getOpciones($id)
		);

		$vista_externa = array(			
			'title' => ucwords("opciones")." - ".$opcion->nombre,
			'contenido_main' => $this->load->view('backend/opciones/opciones_opcion', $vista_interna, true)
		);		
		
		$this->load->view('template/view', $vista_externa);
	}

	function deleteOpcion(){
		$ID =  $this->input->post('id');
		$this->codegen_model->delete('opciones_variables','id',$ID);             
	}
}

/* End of file opciones.php */
/* Location: ./system/application/controllers/opciones.php */