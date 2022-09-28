<?php

class Piezas_talonarios_responsables extends CI_Controller {

	private $permisos;
	
	function __construct() {
		parent::__construct();
		$this->permisos = $this->permisos_lib->control();
		$this->load->model('flash_md');
	}	
	
	function index(){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'results' => $this->flash_md->getResponsablesComprobantes()
		);

		$vista_externa = array(			
			'title' => ucwords("responsables talonarios"),
			'contenido_main' => $this->load->view('components/piezas/piezas_talonarios_responsables/piezas_talonarios_responsables_list', $vista_interna, true)
		);		
		
		$this->load->view('template/backend', $vista_externa);
	}

	function add(){
		if ($this->input->post('enviar_form')){
			$data = array(
					'sucursal_id' => $this->input->post('sucursal_id'),
					'apellido' => $this->input->post('apellido'),
					'nombre' => $this->input->post('nombre')
				);
			$this->codegen_model->add('flash_piezas_talonarios_responsables',$data);
			redirect(base_url().'piezas/piezas_talonarios_responsables');
		}		  
   
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'sucursales' => $this->codegen_model->get('flash_sucursales', '*', '')
		);

		$vista_externa = array(			
			'title' => ucwords("responsables talonarios"),
			'contenido_main' => $this->load->view('components/piezas/piezas_talonarios_responsables/piezas_talonarios_responsables_add', $vista_interna, true)
		);		  
   
		$this->load->view('template/backend', $vista_externa);
	}	

	function edit($id){
		if ($this->input->post('enviar_form')){
			$data = array(
					'sucursal_id' => $this->input->post('sucursal_id'),
					'apellido' => $this->input->post('apellido'),
					'nombre' => $this->input->post('nombre')
				);
			$this->codegen_model->edit('flash_piezas_talonarios_responsables',$data,'id',$this->input->post('id'));
			redirect(base_url().'piezas/piezas_talonarios_responsables');
		}

		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'result' => $this->codegen_model->row('flash_piezas_talonarios_responsables','*','id = '.$id),
			'sucursales' => $this->codegen_model->get('flash_sucursales', '*', '')
		);

		$vista_externa = array(			
			'title' => ucwords("responsables talonarios"),
			'contenido_main' => $this->load->view('components/piezas/piezas_talonarios_responsables/piezas_talonarios_responsables_edit', $vista_interna, true)
		);		  
   
		$this->load->view('template/backend', $vista_externa);
	}

	function view($id){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'result' => $this->codegen_model->row('flash_piezas_talonarios_responsables','*','id = '.$id)
		);

		$vista_externa = array(			
			'title' => ucwords("responsables talonarios"),
			'contenido_main' => $this->load->view('components/piezas/piezas_talonarios_responsables/piezas_talonarios_responsables_view', $vista_interna, true)
		);		  
   
		$this->load->view('template/fancybox', $vista_externa);
	}

	function json($id){
		$json = $this->codegen_model->row('flash_piezas_talonarios_responsables','*','id = '.$id);
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}

	function json_all(){
		$json = $this->codegen_model->get('flash_piezas_talonarios_responsables','*','');
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}
	
	function delete($id){
		$this->codegen_model->delete('flash_piezas_talonarios_responsables','id',$id);             
	}
}

/* End of file piezas_talonarios_responsables.php */
/* Location: ./system/application/controllers/piezas_talonarios_responsables.php */