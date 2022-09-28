<?php

class Retiro_correspondencia extends CI_Controller {	
	
	function __construct() {
		parent::__construct();
	}	
	
	public function servicios_x_cliente($cliente_id){
                $cliente_id = $cliente_id;
		$json = $this->codegen_model->get('flash_clientes_precios_especiales','*','cliente_id = '.$cliente_id);
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}
}

/* End of file Clientes.php */
/* Location: ./system/application/controllers/Clientes.php */