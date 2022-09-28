<?php

class Groups extends CI_Controller {

	private $permisos;

	function __construct() {
		parent::__construct();
		$this->permisos = $this->permisos_lib->control();
	}	
	
	function index(){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'results' => $this->sudaca_backend_md->groups_getAll()
		);

		$vista_externa = array(			
			'title' => ucwords("groups"),
			'contenido_main' => $this->load->view('backend/groups/groups_list', $vista_interna, true)
		);		
		
		$this->load->view('template/backend', $vista_externa);
	}

	function add(){
		if ($this->input->post('enviar_form')){
			$data = array(
					'name' => $this->input->post('name'),
					'description' => $this->input->post('description')
				);
			$grupo = $this->codegen_model->add('groups',$data);

			$data = array(
					'menu_id' => 7,
					'group_id' => $grupo,
					'read' => 1,
					'insert' => 1,
					'update' => 1,
					'delete' => 1,
					'exportar' => 1,
					'imprimir' => 1
				);
			$this->codegen_model->add('permisos',$data);
			redirect(base_url().'backend/groups');
		}

		$vista_interna = array(
			'permisos_efectivos' => $this->permisos
		);

		$vista_externa = array(			
			'title' => ucwords("groups"),
			'contenido_main' => $this->load->view('backend/groups/groups_add', $vista_interna, true)
		);		
		
		$this->load->view('template/backend', $vista_externa);
	}	

	function edit($id){
		if ($this->input->post('enviar_form')){
			$data = array(
					'name' => $this->input->post('name'),
					'description' => $this->input->post('description')
				);
			$this->codegen_model->edit('groups',$data,'id',$this->input->post('id'));
			redirect(base_url().'backend/groups/');
		}

		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'result' => $this->codegen_model->row('groups','*','id = '.$id)
		);

		$vista_externa = array(			
			'title' => ucwords("groups"),
			'contenido_main' => $this->load->view('backend/groups/groups_edit', $vista_interna, true)
		);		
		
		$this->load->view('template/backend', $vista_externa);
	}

	function view($id){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'result' => $this->codegen_model->row('groups','*','id = '.$id)
		);

		$vista_externa = array(			
			'title' => ucwords("groups"),
			'contenido_main' => $this->load->view('backend/groups/groups_view', $vista_interna, true)
		);		
		
		$this->load->view('template/view', $vista_externa);
	}
	
	function delete($ID){
		$this->codegen_model->delete('groups','id',$ID);             
		redirect(base_url().'backend/groups/');
	}
}

/* End of file groups.php */
/* Location: ./system/application/controllers/groups.php */