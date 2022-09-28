<?php

class Clientes_responsables extends CI_Controller {

	private $permisos;
	
	function __construct() {
		parent::__construct();
		$this->permisos = $this->permisos_lib->control();
	}	
	
	function index(){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'results' => $this->codegen_model->get('flash_clientes_responsables','*','')
		);

		$vista_externa = array(			
			'title' => ucwords("responsables"),
			'contenido_main' => $this->load->view('components/clientes/clientes_responsables/clientes_responsables_list', $vista_interna, true)
		);		
		
		$this->load->view('template/backend', $vista_externa);
	}

	function add(){
                       
		if ($this->input->post('enviar_form')){ 
			$data = array(
					'cliente_id' => $this->input->post('cliente_id'),
					'responsable' => $this->input->post('responsable'),
					'telefonos' => $this->input->post('telefonos'),
					'emails' => $this->input->post('emails')
				);
			$this->codegen_model->add('flash_clientes_responsables',$data);
			redirect(base_url().'clientes/clientes_responsables');
		}		  
   
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos
		);

		$vista_externa = array(			
			'title' => ucwords("responsables"),
			'contenido_main' => $this->load->view('components/clientes/clientes_responsables/clientes_responsables_add', $vista_interna, true)
		);		  
   
		$this->load->view('template/backend', $vista_externa);
	}	

	function edit($id){
	    
	    
		if ($this->input->post('enviar_form')){
			$data = array(
					'cliente_id' => $this->input->post('cliente_id'),
					'responsable' => $this->input->post('responsable'),
					'telefonos' => $this->input->post('telefonos'),
					'emails' => $this->input->post('emails'),
					'update' => date('Y-m-d H:m:s')
				);
			$this->codegen_model->edit('flash_clientes_responsables',$data,'id',$this->input->post('id'));
			redirect(base_url().'clientes/clientes_responsables');
		}

		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'result' => $this->codegen_model->row('flash_clientes_responsables','*','id = '.$id)
		);

		$vista_externa = array(			
			'title' => ucwords("responsables"),
			'contenido_main' => $this->load->view('components/clientes/clientes_responsables/clientes_responsables_edit', $vista_interna, true)
		);		  
   
		$this->load->view('template/backend', $vista_externa);
	}

	function view($id){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'result' => $this->codegen_model->row('flash_clientes_responsables','*','id = '.$id)
		);

		$vista_externa = array(			
			'title' => ucwords("responsables"),
			'contenido_main' => $this->load->view('components/clientes/clientes_responsables/clientes_responsables_view', $vista_interna, true)
		);		  
   
		$this->load->view('template/fancybox', $vista_externa);
	}

	function json($id){
		$json = $this->codegen_model->row('flash_clientes_responsables','*','id = '.$id);
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}

	function json_all(){
		$json = $this->codegen_model->get('flash_clientes_responsables','*','');
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}
	
	function delete($id){
		$this->codegen_model->delete('flash_clientes_responsables','id',$id);             
	}
}

/* End of file clientes_responsables.php */
/* Location: ./system/application/controllers/clientes_responsables.php */