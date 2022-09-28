<?php

class Precios extends CI_Controller {	
	
	function __construct() {
		parent::__construct();
	}	
	
	public function servicios(){
		$json = $this->codegen_model->get('flash_servicios','*','');
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}

	public function precios_especiales(){
		$json = $this->codegen_model->get('flash_clientes_precios_especiales','*','');
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}
        
        public function precios_especiales_x_cliente($cliente_id){
            $query = $this->db
                        ->select('pe.*, s.nombre as nombre_servicio, s.codigo as codigo_servicio, s.precio as precio_lista')
                        ->join('flash_servicios s', 's.id = pe.servicio_id')
                        ->where('pe.cliente_id', $cliente_id)
                        ->get('flash_clientes_precios_especiales pe');
            $json = $query->result();
            //$this->codegen_model->get('flash_clientes_precios_especiales','*','cliente_id = '.$cliente_id);
            if($json) echo json_encode($json);
            else echo json_encode(array('status' => 'none'));
	}
        
         public function getActualizacionesPendientesXCliente($cliente_id){
             $query = $this->db
                        ->select('ap.id')
                        ->join('flash_actualizacion_precios_especiales pe', 'pe.actualizacion_id = ap.id')
                        ->join('flash_clientes_precios_especiales c', 'c.id = pe.cliente_precio_especial_id')
                        ->where('c.cliente_id', $cliente_id)
                        ->where('fecha_aprobacion IS NULL')
                        ->where('fecha_rechazo IS NULL')
                        ->where('tipo_id = 5')
                        ->get('flash_actualizacion_precios ap');
            $json = $query->result();
            if($json) echo json_encode($json);
            else echo json_encode(NULL);
	}
}

/* End of file Precios.php */
/* Location: ./system/application/controllers/Precios.php */