<?php

class Auditoria extends CI_Controller {

	private $permisos;
	
	function __construct() {
		parent::__construct();
		$this->permisos = $this->permisos_lib->control();
	}	
	
	function index(){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'results' => $this->codegen_model->get('auditoria','*','')
		);

		$vista_externa = array(			
			'title' => ucwords("auditoria"),
			'contenido_main' => $this->load->view('components/auditoria/auditoria_list', $vista_interna, true)
		);		
		
		$this->load->view('template/backend', $vista_externa);
	}

	function add(){
		if ($this->input->post('enviar_form')){
			$data = array(
					'user_id' => $this->input->post('user_id'),
					'consulta' => $this->input->post('consulta'),
					'fecha' => $this->input->post('fecha')
				);
			$this->codegen_model->add('auditoria',$data);
			redirect(base_url().'auditoria');
		}		  
   
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos
		);

		$vista_externa = array(			
			'title' => ucwords("auditoria"),
			'contenido_main' => $this->load->view('components/auditoria/auditoria_add', $vista_interna, true)
		);		  
   
		$this->load->view('template/backend', $vista_externa);
	}	

	function edit($id){
		if ($this->input->post('enviar_form')){
			$data = array(
					'user_id' => $this->input->post('user_id'),
					'consulta' => $this->input->post('consulta'),
					'fecha' => $this->input->post('fecha')
				);
			$this->codegen_model->edit('auditoria',$data,'id',$this->input->post('id'));
			redirect(base_url().'auditoria');
		}

		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'result' => $this->codegen_model->row('auditoria','*','id = '.$id)
		);

		$vista_externa = array(			
			'title' => ucwords("auditoria"),
			'contenido_main' => $this->load->view('components/auditoria/auditoria_edit', $vista_interna, true)
		);		  
   
		$this->load->view('template/backend', $vista_externa);
	}

	function view($id){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'result' => $this->codegen_model->row('auditoria','*','id = '.$id)
		);

		$vista_externa = array(			
			'title' => ucwords("auditoria"),
			'contenido_main' => $this->load->view('components/auditoria/auditoria_view', $vista_interna, true)
		);		  
   
		$this->load->view('template/view', $vista_externa);
	}

	function json($id){
		$json = $this->codegen_model->row('auditoria','*','id = '.$id);
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}

	function json_all(){
		$json = $this->codegen_model->get('auditoria','*','');
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}
	
	function delete($id){
		$this->codegen_model->delete('auditoria','id',$id);             
	}
}

/* End of file auditoria.php */
/* Location: ./system/application/controllers/auditoria.php */