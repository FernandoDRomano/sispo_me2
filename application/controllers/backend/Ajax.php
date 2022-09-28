<?php

class Ajax extends CI_Controller {

	function __construct() {
		parent::__construct();
	}	
	
	function deleteIMG(){
		$data = array('imagen' => "");
		$tabla = $this->input->post('tabla');
		$id = $this->input->post('id');
		$this->codegen_model->edit($tabla,$data,'id',$id);
	}

	function deleteFoto(){
		$data = array('foto' => "");
		$tabla = 'users';
		$id = $this->input->post('id');
		$this->codegen_model->edit($tabla,$data,'id',$id);
	}

	function cambiarValor(){
		$id = $this->input->post('id');
		$tabla = $this->input->post('tabla');
		$campo = $this->input->post('campo');
		$contenido = $this->codegen_model->row($tabla, 'id, '.$campo,'id = '.$id);
		switch ($contenido->$campo) {
			case 0:
				echo $valor = 1;
				break;
			
			case 1:
				echo $valor = 0;
				break;
		}
		$data = array($campo => $valor);
		$this->codegen_model->edit($tabla,$data,'id',$id);
	}
}

/* End of file auditoria.php */
/* Location: ./system/application/controllers/ajax.php */