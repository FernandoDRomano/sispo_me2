<?php

class Permisos extends CI_Controller {

	private $permisos;

	function __construct() {
		parent::__construct();
		$this->permisos = $this->permisos_lib->control();
	}	
	
	function index(){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'results' => $this->sudaca_backend_md->permisos_getAll()
		);

		$vista_externa = array(			
			'title' => ucwords("permisos"),
			'contenido_main' => $this->load->view('backend/permisos/permisos_list', $vista_interna, true)
		);		
		
		$this->load->view('template/backend', $vista_externa);
	}

	function add(){
		if ($this->input->post('enviar_form')){
			$data = array(
					'menu_id' => $this->input->post('menu_id'),
					'group_id' => $this->input->post('group_id'),
					'read' => $this->input->post('read'),
					'insert' => $this->input->post('insert'),
					'update' => $this->input->post('update'),
					'delete' => $this->input->post('delete'),
					'exportar' => $this->input->post('exportar'),
					'imprimir' => $this->input->post('imprimir')
				);
			$this->codegen_model->add('permisos',$data);
			redirect(base_url().'backend/permisos');
		}

		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'grupos' => $this->sudaca_backend_md->groups_getAll(),
			'menus' => $this->sudaca_backend_md->menus_getForPermisos()
		);

		$vista_externa = array(			
			'title' => ucwords("permisos"),
			'contenido_main' => $this->load->view('backend/permisos/permisos_add', $vista_interna, true)
		);		
		
		$this->load->view('template/backend', $vista_externa);
	}	

	function edit($id){
		if ($this->input->post('enviar_form')){
			$data = array(
					'menu_id' => $this->input->post('menu_id'),
					'group_id' => $this->input->post('group_id'),
					'read' => $this->input->post('read'),
					'insert' => $this->input->post('insert'),
					'update' => $this->input->post('update'),
					'delete' => $this->input->post('delete'),
					'exportar' => $this->input->post('exportar'),
					'imprimir' => $this->input->post('imprimir')
				);
			$this->codegen_model->edit('permisos',$data,'id',$this->input->post('id'));
			redirect(base_url().'backend/permisos/');
		}

		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'result' => $this->codegen_model->row('permisos','*','id = '.$id),
			'grupos' => $this->sudaca_backend_md->groups_getAll(),
			'menus' => $this->sudaca_backend_md->menus_getForPermisos()
		);

		$vista_externa = array(			
			'title' => ucwords("permisos"),
			'contenido_main' => $this->load->view('backend/permisos/permisos_edit', $vista_interna, true)
		);		
		
		$this->load->view('template/backend', $vista_externa);
	}

	function view($id){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'result' => $this->codegen_model->row('permisos','*','id = '.$id)
		);

		$vista_externa = array(			
			'title' => ucwords("permisos"),
			'contenido_main' => $this->load->view('backend/permisos/permisos_view', $vista_interna, true)
		);		
		
		$this->load->view('template/view', $vista_externa);
	}
	
	function delete($ID){
		$this->codegen_model->delete('permisos','id',$ID);             
		redirect(base_url().'backend/permisos/');
	}
}

/* End of file permisos.php */
/* Location: ./system/application/controllers/permisos.php */