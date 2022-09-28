<?php

class Clientes_departamentos extends CI_Controller {

	private $permisos;
	
	function __construct() {
		parent::__construct();
		$this->permisos = $this->permisos_lib->control();
		$this->load->model('flash_md');
	}	
	
	function index(){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'results' => array()
		);

		$vista_externa = array(			
			'title' => ucwords("departamentos"),
			'contenido_main' => $this->load->view('components/clientes/clientes_departamentos/clientes_departamentos_list', $vista_interna, true)
		);		
		
		$this->load->view('template/backend', $vista_externa);
	}

	function filtro(){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'results' => $this->flash_md->getDepartamentos($this->input->post('buscar'))
		);

		$vista_externa = array(			
			'title' => ucwords("departamentos"),
			'contenido_main' => $this->load->view('components/clientes/clientes_departamentos/clientes_departamentos_list', $vista_interna, true)
		);			
		
		$this->load->view('template/backend', $vista_externa);
	}

	function add(){
		if ($this->input->post('enviar_form')){
			$data = array(
					'cliente_id' => $this->input->post('cliente_id'),
					'nombre' => $this->input->post('nombre'),
					'nombre_contacto' => $this->input->post('nombre_contacto'),
					'telefonos' => $this->input->post('telefonos'),
					'emails' => $this->input->post('emails')
				);
			$this->codegen_model->add('flash_clientes_departamentos',$data);
			redirect(base_url().'clientes/clientes_departamentos');
		}		  
   
   		$clientes = $this->codegen_model->get('flash_clientes', '*', '');
            
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'clientes' => "[".$clientes_str."]",
		);

		$vista_externa = array(			
			'title' => ucwords("departamentos"),
			'contenido_main' => $this->load->view('components/clientes/clientes_departamentos/clientes_departamentos_add', $vista_interna, true)
		);		  
   
		$this->load->view('template/backend', $vista_externa);
	}	

	function edit($id){
	    
	    
		if ($this->input->post('enviar_form')){
			$data = array(
					'cliente_id' => $this->input->post('cliente_id'),
					'nombre' => $this->input->post('nombre'),
					'nombre_contacto' => $this->input->post('nombre_contacto'),
					'telefonos' => $this->input->post('telefonos'),
					'emails' => $this->input->post('emails'),
					'update' => date('Y-m-d H:m:s')
				);
			$this->codegen_model->edit('flash_clientes_departamentos',$data,'id',$this->input->post('id'));
			redirect(base_url().'clientes/clientes_departamentos');
		}

                $clientes = $this->codegen_model->get('flash_clientes', '*', '');
                $clientes_array = [];
                foreach ($clientes as $f){
                    array_push($clientes_array, "{ id: ".$f->id.", name: '".$f->nombre."'}, ");
                    //echo "{ id: ".$f->id.", name: '".$f->nombre."'}, ";
                }
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'result' => $this->codegen_model->row('flash_clientes_departamentos','*','id = '.$id),
			'clientes' => $clientes_array,
		);

		$vista_externa = array(			
			'title' => ucwords("departamentos"),
			'contenido_main' => $this->load->view('components/clientes/clientes_departamentos/clientes_departamentos_edit', $vista_interna, true)
		);		  
   
		$this->load->view('template/backend', $vista_externa);
	}

	function view($id){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'result' => $this->codegen_model->row('flash_clientes_departamentos','*','id = '.$id)
		);

		$vista_externa = array(			
			'title' => ucwords("departamentos"),
			'contenido_main' => $this->load->view('components/clientes/clientes_departamentos/clientes_departamentos_view', $vista_interna, true)
		);		  
   
		$this->load->view('template/fancybox', $vista_externa);
	}

	function json($id){
		$json = $this->codegen_model->row('flash_clientes_departamentos','*','id = '.$id);
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}

	function json_all(){
		$json = $this->codegen_model->get('flash_clientes_departamentos','*','');
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}
	
	function delete($id){
		$this->codegen_model->delete('flash_clientes_departamentos','id',$id);             
	}
}

/* End of file clientes_departamentos.php */
/* Location: ./system/application/controllers/clientes_departamentos.php */