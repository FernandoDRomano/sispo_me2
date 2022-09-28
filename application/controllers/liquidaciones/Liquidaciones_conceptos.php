<?php

class Liquidaciones_conceptos extends CI_Controller {

	private $permisos;
	
	function __construct() {
		parent::__construct();
		$this->permisos = $this->permisos_lib->control();
	}	
	
	function index(){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'results' => $this->codegen_model->get('flash_liquidaciones_conceptos','*','')
		);

		$vista_externa = array(			
			'title' => ucwords("liquidaciones/liquidaciones_conceptos"),
			'contenido_main' => $this->load->view('components/liquidaciones/liquidaciones_conceptos/liquidaciones_conceptos_list', $vista_interna, true)
		);		
		
		$this->load->view('template/backend', $vista_externa);
	}

	function add(){
	    
	    
		if ($this->input->post('enviar_form')){
                        $user_row = $this->ion_auth->user()->row();
			$data = array(
					'concepto' => $this->input->post('concepto'),
					'operacion' => $this->input->post('operacion'),
					'create' => date('Y-m-d H:m:s'),
					'create_user_id' => $user_row->id,
				);
			$this->codegen_model->add('flash_liquidaciones_conceptos',$data);
			redirect(base_url().'liquidaciones/liquidaciones_conceptos');
		}		  
   
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos
		);

		$vista_externa = array(			
			'title' => ucwords("liquidaciones/liquidaciones_conceptos"),
			'contenido_main' => $this->load->view('components/liquidaciones/liquidaciones_conceptos/liquidaciones_conceptos_add', $vista_interna, true)
		);		  
   
		$this->load->view('template/backend', $vista_externa);
	}	

	function edit($id){
	    
	    
		if ($this->input->post('enviar_form')){
                        $user_row = $this->ion_auth->user()->row();
			$data = array(
					'concepto' => strtoupper($this->input->post('concepto')),
					'operacion' => $this->input->post('operacion'),
					'update' => date('Y-m-d H:m:s'),
					'update_user_id' => $user_row->id
				);
			$this->codegen_model->edit('flash_liquidaciones_conceptos',$data,'id',$this->input->post('id'));
			redirect(base_url().'liquidaciones/liquidaciones_conceptos');
		}

		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'result' => $this->codegen_model->row('flash_liquidaciones_conceptos','*','id = '.$id)
		);

		$vista_externa = array(			
			'title' => ucwords("liquidaciones/liquidaciones_conceptos"),
			'contenido_main' => $this->load->view('components/liquidaciones/liquidaciones_conceptos/liquidaciones_conceptos_edit', $vista_interna, true)
		);		  
   
		$this->load->view('template/backend', $vista_externa);
	}

	function view($id){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'result' => $this->codegen_model->row('flash_liquidaciones_conceptos','*','id = '.$id)
		);

		$vista_externa = array(			
			'title' => ucwords("liquidaciones/liquidaciones_conceptos"),
			'contenido_main' => $this->load->view('components/liquidaciones/liquidaciones_conceptos/liquidaciones_conceptos_view', $vista_interna, true)
		);		  
   
		$this->load->view('template/view', $vista_externa);
	}

	function json($id){
		$json = $this->codegen_model->row('flash_liquidaciones_conceptos','*','id = '.$id);
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}

	function json_all(){
		$json = $this->codegen_model->get('flash_liquidaciones_conceptos','*','');
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}
	
	function delete($id){
		$this->codegen_model->delete('flash_liquidaciones_conceptos','id',$id);             
	}
}

/* End of file liquidaciones/liquidaciones_conceptos.php */
/* Location: ./system/application/controllers/liquidaciones/liquidaciones_conceptos.php */