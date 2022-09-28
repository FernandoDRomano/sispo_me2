<?php

class Sucursales_zonas extends CI_Controller {

	private $permisos;
	
	function __construct() {
		parent::__construct();
		$this->permisos = $this->permisos_lib->control();
		$this->load->model('flash_md');
	}	
	
	function index(){
		$user_row = $this->ion_auth->user()->row();
        if (!$this->ion_auth->in_group(array(1, 2), $user_row->id)) $sucursales = $this->codegen_model->get('flash_sucursales', 'id, nombre', 'id = '.$user_row->sucursal_id);        
        else $sucursales = $this->codegen_model->get('flash_sucursales', 'id, nombre', '');

		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'results' => array(),
			'sucursales' => $sucursales
		);

		$vista_externa = array(			
			'title' => ucwords("zonas"),
			'contenido_main' => $this->load->view('components/sucursales/sucursales_zonas/sucursales_zonas_list', $vista_interna, true)
		);		
		
		$this->load->view('template/backend', $vista_externa);
	}

	function filtro(){
		$user_row = $this->ion_auth->user()->row();
        if (!$this->ion_auth->in_group(array(1, 2), $user_row->id)) $sucursales = $this->codegen_model->get('flash_sucursales', 'id, nombre', 'id = '.$user_row->sucursal_id);        
        else $sucursales = $this->codegen_model->get('flash_sucursales', 'id, nombre', '');
		
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'results' => $this->flash_md->getZonas($this->input->post('sucursal'), $this->input->post('buscar')),
			'sucursales' => $sucursales
		);

		$vista_externa = array(			
			'title' => ucwords("zonas"),
			'contenido_main' => $this->load->view('components/sucursales/sucursales_zonas/sucursales_zonas_list', $vista_interna, true)
		);	
		
		$this->load->view('template/backend', $vista_externa);
	}

	function add(){
		if ($this->input->post('enviar_form')){
			$data = array(
					'sucursal_id' => $this->input->post('sucursal_id'),
					'nombre' => $this->input->post('nombre'),
					'activo' => $this->input->post('activo'),
					'create' => date("Y-m-d H:i:s"),
				);
			$this->codegen_model->add('flash_sucursales_zonas',$data);
			redirect(base_url().'sucursales/sucursales_zonas');
		}		  
   
                $user_row = $this->ion_auth->user()->row();
                if (!$this->ion_auth->in_group(array(1, 2), $user_row->id)) $sucursales = $this->codegen_model->get('flash_sucursales', 'id, nombre', 'id = '.$user_row->sucursal_id);        
                else $sucursales = $this->codegen_model->get('flash_sucursales', 'id, nombre', '');
        
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'sucursales' => $sucursales//$this->codegen_model->get('flash_sucursales','*','')
		);

		$vista_externa = array(			
			'title' => ucwords("zonas"),
			'contenido_main' => $this->load->view('components/sucursales/sucursales_zonas/sucursales_zonas_add', $vista_interna, true)
		);		  
   
		$this->load->view('template/backend', $vista_externa);
	}	

	function edit($id){
		if ($this->input->post('enviar_form')){
			$data = array(
					'sucursal_id' => $this->input->post('sucursal_id'),
					'nombre' => $this->input->post('nombre'),
					'activo' => $this->input->post('activo'),
					'update' => date('Y-m-d H:m:s')
				);
			$this->codegen_model->edit('flash_sucursales_zonas',$data,'id',$this->input->post('id'));
			redirect(base_url().'sucursales/sucursales_zonas');
		}

		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'result' => $this->codegen_model->row('flash_sucursales_zonas','*','id = '.$id),
			'sucursales' => $this->codegen_model->get('flash_sucursales','*','')
		);

		$vista_externa = array(			
			'title' => ucwords("zonas"),
			'contenido_main' => $this->load->view('components/sucursales/sucursales_zonas/sucursales_zonas_edit', $vista_interna, true)
		);		  
   
		$this->load->view('template/backend', $vista_externa);
	}

	function view($id){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'result' => $this->codegen_model->row('flash_sucursales_zonas','*','id = '.$id)
		);

		$vista_externa = array(			
			'title' => ucwords("zonas"),
			'contenido_main' => $this->load->view('components/sucursales/sucursales_zonas/sucursales_zonas_view', $vista_interna, true)
		);		  
   
		$this->load->view('template/fancybox', $vista_externa);
	}

	function json($id){
		$json = $this->codegen_model->row('flash_sucursales_zonas','*','id = '.$id);
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}

	function json_all(){
		$json = $this->codegen_model->get('flash_sucursales_zonas','*','');
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}
	
	function delete($id){
		$this->codegen_model->delete('flash_sucursales_zonas','id',$id);             
	}
}

/* End of file sucursales_zonas.php */
/* Location: ./system/application/controllers/sucursales_zonas.php */