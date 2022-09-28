<?php

class Configuraciones extends CI_Controller {

	private $permisos;

	function __construct() {
		parent::__construct();
		$this->permisos = $this->permisos_lib->control();
	}	
	
	function index(){
		if ($this->input->post('enviar_form')){
			$data = array('valor' => $this->input->post('nombre_sitio'));
			$this->codegen_model->edit('configuraciones',$data,'id',1);

			$data = array('valor' => $this->input->post('metadata'));
			$this->codegen_model->edit('configuraciones',$data,'id',2);

			$data = array('valor' => $this->input->post('email'));
			$this->codegen_model->edit('configuraciones',$data,'id',3);

			$data = array('valor' => $this->input->post('remitente'));
			$this->codegen_model->edit('configuraciones',$data,'id',4);

			$data = array('valor' => $this->input->post('telefono'));
			$this->codegen_model->edit('configuraciones',$data,'id',5);

			$data = array('valor' => $this->input->post('celular'));
			$this->codegen_model->edit('configuraciones',$data,'id',6);

			$data = array('valor' => $this->input->post('direccion'));
			$this->codegen_model->edit('configuraciones',$data,'id',7);

			$data = array('valor' => $this->input->post('latitud'));
			$this->codegen_model->edit('configuraciones',$data,'id',8);

			$data = array('valor' => $this->input->post('longitud'));
			$this->codegen_model->edit('configuraciones',$data,'id',9);

			$data = array('valor' => $this->input->post('facebook'));
			$this->codegen_model->edit('configuraciones',$data,'id',10);

			$data = array('valor' => $this->input->post('twitter'));
			$this->codegen_model->edit('configuraciones',$data,'id',11);

			$data = array('valor' => $this->input->post('google_plus'));
			$this->codegen_model->edit('configuraciones',$data,'id',12);

			$data = array('valor' => $this->input->post('descripcion_sitio'));
			$this->codegen_model->edit('configuraciones',$data,'id',13);

			$data = array('valor' => $this->input->post('email_registro_usuario'));
			$this->codegen_model->edit('configuraciones',$data,'id',14);
			
			redirect(base_url().'backend/configuraciones/');
		}

		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'nombre_sitio' => $this->codegen_model->row('configuraciones','*','id = 1'),
			'metadata' => $this->codegen_model->row('configuraciones','*','id = 2'),
			'email' => $this->codegen_model->row('configuraciones','*','id = 3'),
			'remitente' => $this->codegen_model->row('configuraciones','*','id = 4'),
			'telefono' => $this->codegen_model->row('configuraciones','*','id = 5'),
			'celular' => $this->codegen_model->row('configuraciones','*','id = 6'),
			'direccion' => $this->codegen_model->row('configuraciones','*','id = 7'),
			'latitud' => $this->codegen_model->row('configuraciones','*','id = 8'),
			'longitud' => $this->codegen_model->row('configuraciones','*','id = 9'),
			'facebook' => $this->codegen_model->row('configuraciones','*','id = 10'),
			'twitter' => $this->codegen_model->row('configuraciones','*','id = 11'),
			'google_plus' => $this->codegen_model->row('configuraciones','*','id = 12'),
			'descripcion_sitio' => $this->codegen_model->row('configuraciones','*','id = 13'),
			'email_registro_usuario' => $this->codegen_model->row('configuraciones','*','id = 14')
		);

		$vista_externa = array(			
			'title' => ucwords("configuraciones"),
			'contenido_main' => $this->load->view('backend/configuraciones/configuraciones', $vista_interna, true)
		);		
		
		$this->load->view('template/backend', $vista_externa);
	}
}

/* End of file configuraciones.php */
/* Location: ./system/application/controllers/configuraciones.php */