<?php

class Menus extends CI_Controller {

	private $permisos;

	function __construct() {
		parent::__construct();
		$this->permisos = $this->permisos_lib->control();
	}		
	
	function index(){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'results' => $this->sudaca_backend_md->menus_getAll()
		);

		$vista_externa = array(			
			'title' => ucwords("menus"),
			'contenido_main' => $this->load->view('backend/menus/menus_list', $vista_interna, true)
		);		
		
		$this->load->view('template/backend', $vista_externa);
	}

	function add(){
		if ($this->input->post('enviar_form')){
			$data = array(
					'descripcion' => $this->input->post('descripcion'),
					'link' => $this->input->post('link'),
					'parent' => $this->input->post('parent'),
					'iconpath' => $this->input->post('iconpath'),
					'active' => $this->input->post('active'),
					'dashboard' => $this->input->post('dashboard')
				);
			$this->codegen_model->add('menus',$data);
			redirect(base_url().'backend/menus');
		}		 

		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'parents' => $this->codegen_model->get('menus','*','')
		);

		$vista_externa = array(			
			'title' => ucwords("menus"),
			'contenido_main' => $this->load->view('backend/menus/menus_add', $vista_interna, true)
		);		
		
		$this->load->view('template/backend', $vista_externa);
	}	

	function edit($id){
		if ($this->input->post('enviar_form')){
			$data = array(
					'descripcion' => $this->input->post('descripcion'),
					'link' => $this->input->post('link'),
					'parent' => $this->input->post('parent'),
					'iconpath' => $this->input->post('iconpath'),
					'active' => $this->input->post('active'),
					'dashboard' => $this->input->post('dashboard')
				);
			$this->codegen_model->edit('menus',$data,'id',$this->input->post('id'));
			redirect(base_url().'backend/menus/');
		}

		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'result' => $this->codegen_model->row('menus','*','id = '.$id),
			'parents' => $this->codegen_model->get('menus','*','')
		);

		$vista_externa = array(			
			'title' => ucwords("menus"),
			'contenido_main' => $this->load->view('backend/menus/menus_edit', $vista_interna, true)
		);		
		
		$this->load->view('template/backend', $vista_externa);
	}

	function view($id){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'result' => $this->codegen_model->row('menus','*','id = '.$id)
		);

		$vista_externa = array(			
			'title' => ucwords("menus"),
			'contenido_main' => $this->load->view('backend/menus/menus_view', $vista_interna, true)
		);		
		
		$this->load->view('template/view', $vista_externa);
	}
	
	function delete($ID){
		$this->codegen_model->delete('menus','id',$ID);             
		redirect(base_url().'backend/menus/');
	}
}

/* End of file menus.php */
/* Location: ./system/application/controllers/menus.php */