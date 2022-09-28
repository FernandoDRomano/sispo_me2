<?php

class Clientes_precios_especiales extends CI_Controller {

	private $permisos;
	
	function __construct() {
		parent::__construct();
		$this->permisos = $this->permisos_lib->control();
		$this->load->model('flash_md');
	}	
	
	function index(){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'results' => array(),
			'grupos' => $this->codegen_model->get('flash_servicios_grupos', 'id, nombre', ''),
			'servicios' => $this->codegen_model->get('flash_servicios', 'id, nombre', '')
		);

		$vista_externa = array(			
			'title' => ucwords("precios especiales"),
			'contenido_main' => $this->load->view('components/clientes/clientes_precios_especiales/clientes_precios_especiales_list', $vista_interna, true)
		);		
		
		$this->load->view('template/backend', $vista_externa);
	}

	function filtro(){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'results' => $this->flash_md->getPreciosEspeciales($this->input->post('grupo'), $this->input->post('servicio'), $this->input->post('activo'), $this->input->post('buscar')),
			'grupos' => $this->codegen_model->get('flash_servicios_grupos', 'id, nombre', ''),
			'servicios' => $this->codegen_model->get('flash_servicios', 'id, nombre', '')
		);

		$vista_externa = array(			
			'title' => ucwords("precios especiales"),
			'contenido_main' => $this->load->view('components/clientes/clientes_precios_especiales/clientes_precios_especiales_list', $vista_interna, true)
		);		
		
		$this->load->view('template/backend', $vista_externa);
	}

	function add(){
		if ($this->input->post('enviar_form')){
			$data = array(
					'cliente_id' => $this->input->post('cliente_id'),
					'grupo_servicio_id' => $this->input->post('grupo_servicio_id'),
					'servicio_id' => $this->input->post('servicio_id'),
					'nombre' => $this->input->post('nombre'),
					'precio' => $this->input->post('precio'),
					'activo' => $this->input->post('activo')
				);
			$this->codegen_model->add('flash_clientes_precios_especiales',$data);
			redirect(base_url().'clientes/clientes_precios_especiales');
		}		  
   
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'clientes' => $this->codegen_model->get('flash_clientes','*',''),
			'grupos' => $this->codegen_model->get('flash_servicios_grupos','*','')
		);

		$vista_externa = array(			
			'title' => ucwords("precios especiales"),
			'contenido_main' => $this->load->view('components/clientes/clientes_precios_especiales/clientes_precios_especiales_add', $vista_interna, true)
		);		  
   
		$this->load->view('template/backend', $vista_externa);
	}	

	function edit($id){
	    
	    
		if ($this->input->post('enviar_form')){
			$data = array(
					'cliente_id' => $this->input->post('cliente_id'),
					'grupo_servicio_id' => $this->input->post('grupo_servicio_id'),
					'servicio_id' => $this->input->post('servicio_id'),
					'nombre' => $this->input->post('nombre'),
					'precio' => $this->input->post('precio'),
					'activo' => $this->input->post('activo'),
					'update' => date('Y-m-d H:m:s')
				);
			$this->codegen_model->edit('flash_clientes_precios_especiales',$data,'id',$this->input->post('id'));
			redirect(base_url().'clientes/clientes_precios_especiales');
		}

		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'result' => $this->codegen_model->row('flash_clientes_precios_especiales','*','id = '.$id),
			'clientes' => $this->codegen_model->get('flash_clientes','*',''),
			'grupos' => $this->codegen_model->get('flash_servicios_grupos','*','')
		);

		$vista_externa = array(			
			'title' => ucwords("precios especiales"),
			'contenido_main' => $this->load->view('components/clientes/clientes_precios_especiales/clientes_precios_especiales_edit', $vista_interna, true)
		);		  
   
		$this->load->view('template/backend', $vista_externa);
	}

	function view($id){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'result' => $this->codegen_model->row('flash_clientes_precios_especiales','*','id = '.$id)
		);

		$vista_externa = array(			
			'title' => ucwords("precios especiales"),
			'contenido_main' => $this->load->view('components/clientes/clientes_precios_especiales/clientes_precios_especiales_view', $vista_interna, true)
		);		  
   
		$this->load->view('template/fancybox', $vista_externa);
	}

	function json($id){
		$json = $this->codegen_model->row('flash_clientes_precios_especiales','*','id = '.$id);
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}

	function json_all(){
		$json = $this->codegen_model->get('flash_clientes_precios_especiales','*','');
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}
	
	function delete($id){
		$this->codegen_model->delete('flash_clientes_precios_especiales','id',$id);             
	}
}

/* End of file clientes_precios_especiales.php */
/* Location: ./system/application/controllers/clientes_precios_especiales.php */