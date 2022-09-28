<?php

class Flash_piezas extends CI_Controller {

	private $permisos;
	
	function __construct() {
		parent::__construct();
		$this->permisos = $this->permisos_lib->control();
	}	
	
	function index(){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'results' => $this->codegen_model->get('flash_piezas','*','')
		);

		$vista_externa = array(			
			'title' => ucwords("flash_piezas"),
			'contenido_main' => $this->load->view('components/flash_piezas/flash_piezas_list', $vista_interna, true)
		);		
		
		$this->load->view('template/backend', $vista_externa);
	}

	function add(){
		if ($this->input->post('enviar_form')){
			$data = array(
					'usuario_id' => $this->input->post('usuario_id'),
					'servicio_id' => $this->input->post('servicio_id'),
					'tipo_id' => $this->input->post('tipo_id'),
					'sucursal_id' => $this->input->post('sucursal_id'),
					'estado_id' => $this->input->post('estado_id'),
					'comprobante_ingreso_id' => $this->input->post('comprobante_ingreso_id'),
					'cantidad' => $this->input->post('cantidad'),
					'barcode' => $this->input->post('barcode'),
					'barcode_externo' => $this->input->post('barcode_externo'),
					'destinatario' => $this->input->post('destinatario'),
					'domicilio' => $this->input->post('domicilio'),
					'codigo_postal' => $this->input->post('codigo_postal'),
					'localidad' => $this->input->post('localidad'),
					'vista' => $this->input->post('vista'),
					'recibio' => $this->input->post('recibio'),
					'documento' => $this->input->post('documento'),
					'vinculo' => $this->input->post('vinculo'),
					'datos_varios' => $this->input->post('datos_varios'),
					'datos_varios_1' => $this->input->post('datos_varios_1'),
					'datos_varios_2' => $this->input->post('datos_varios_2'),
					'verifico_id' => $this->input->post('verifico_id'),
					'rendicion_id' => $this->input->post('rendicion_id'),
					'create' => $this->input->post('create'),
					'update' => $this->input->post('update')
				);
			$this->codegen_model->add('flash_piezas',$data);
			redirect(base_url().'flash_piezas');
		}		  
   
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos
		);

		$vista_externa = array(			
			'title' => ucwords("flash_piezas"),
			'contenido_main' => $this->load->view('components/flash_piezas/flash_piezas_add', $vista_interna, true)
		);		  
   
		$this->load->view('template/backend', $vista_externa);
	}	

	function edit($id){
		if ($this->input->post('enviar_form')){
			$data = array(
					'usuario_id' => $this->input->post('usuario_id'),
					'servicio_id' => $this->input->post('servicio_id'),
					'tipo_id' => $this->input->post('tipo_id'),
					'sucursal_id' => $this->input->post('sucursal_id'),
					'estado_id' => $this->input->post('estado_id'),
					'comprobante_ingreso_id' => $this->input->post('comprobante_ingreso_id'),
					'cantidad' => $this->input->post('cantidad'),
					'barcode' => $this->input->post('barcode'),
					'barcode_externo' => $this->input->post('barcode_externo'),
					'destinatario' => $this->input->post('destinatario'),
					'domicilio' => $this->input->post('domicilio'),
					'codigo_postal' => $this->input->post('codigo_postal'),
					'localidad' => $this->input->post('localidad'),
					'vista' => $this->input->post('vista'),
					'recibio' => $this->input->post('recibio'),
					'documento' => $this->input->post('documento'),
					'vinculo' => $this->input->post('vinculo'),
					'datos_varios' => $this->input->post('datos_varios'),
					'datos_varios_1' => $this->input->post('datos_varios_1'),
					'datos_varios_2' => $this->input->post('datos_varios_2'),
					'verifico_id' => $this->input->post('verifico_id'),
					'rendicion_id' => $this->input->post('rendicion_id'),
					'create' => $this->input->post('create'),
					'update' => $this->input->post('update')
				);
			$this->codegen_model->edit('flash_piezas',$data,'id',$this->input->post('id'));
			redirect(base_url().'flash_piezas');
		}

		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'result' => $this->codegen_model->row('flash_piezas','*','id = '.$id)
		);

		$vista_externa = array(			
			'title' => ucwords("flash_piezas"),
			'contenido_main' => $this->load->view('components/flash_piezas/flash_piezas_edit', $vista_interna, true)
		);		  
   
		$this->load->view('template/backend', $vista_externa);
	}

	function view($id){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'result' => $this->codegen_model->row('flash_piezas','*','id = '.$id)
		);

		$vista_externa = array(			
			'title' => ucwords("flash_piezas"),
			'contenido_main' => $this->load->view('components/flash_piezas/flash_piezas_view', $vista_interna, true)
		);		  
   
		$this->load->view('template/view', $vista_externa);
	}

	function json($id){
		$json = $this->codegen_model->row('flash_piezas','*','id = '.$id);
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}

	function json_all(){
		$json = $this->codegen_model->get('flash_piezas','*','');
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}
	
	function delete($id){
		$this->codegen_model->delete('flash_piezas','id',$id);             
	}
}

/* End of file flash_piezas.php */
/* Location: ./system/application/controllers/flash_piezas.php */