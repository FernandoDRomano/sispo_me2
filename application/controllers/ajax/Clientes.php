<?php

class Clientes extends CI_Controller {	
	
	function __construct() {
		parent::__construct();
	}	
	
	public function cliente($query){
		$json = $this->codegen_model->row('flash_clientes','*','id ='.$query);
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}

	public function departamentos($query){
		$json = $this->codegen_model->get('flash_clientes_departamentos','*','cliente_id ='.$query);
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}
        
        public function servicios($query){
            
            $query = $this->db
                    ->select('s.id, s.nombre')
                    ->join('flash_comprobantes_ingresos ci', 'ci.id = cis.comprobante_ingreso_id')
                    ->join('flash_servicios s', 's.id = cis.servicio_id')
                    ->where('ci.cliente_id = '.$query)
                    ->group_by('s.id')
                    ->get('flash_comprobantes_ingresos_servicios cis');
            //echo $this->db->last_query();die;
            $json = $query->result();
            if($json) echo json_encode($json);
            else echo json_encode(array('status' => 'none'));
	}
        
        public function serviciosXCliente($cliente_id){
            
            $query = $this->db
                    ->select('s.id, pe.nombre')
                    ->join('flash_clientes_precios_especiales pe', 'pe.servicio_id = s.id')
                    ->where('pe.cliente_id = '.$cliente_id)
                    ->get('flash_servicios s');
            //echo $this->db->last_query();die;
            $json = $query->result();
            if($json) echo json_encode($json);
            else echo json_encode(array('status' => 'none'));
	}
        
    public function autocompletarClientes(){
    	
        $query = $this->db
                ->select('id, nombre as text')
                ->where('nombre like "%'.$_GET['q'].'%"')
                ->get('flash_clientes');
        $json = $query->result();
       // header('Content-Type: application/json');
        echo json_encode($json);
        //die();
	}
	
    public function paqueteria($id){
		$json = "   SELECT pt.*, pt.tipo nombre 
                            FROM flash_clientes c
                            INNER JOIN flash_paqueteria_tipos pt ON c.`flash_paqueteria_tipo_id` = pt.`id`
                            WHERE c.id = $id";
                $result = $this->db->query($json)->result();
//                var_dump($result);die;
		if($json) echo json_encode($result);
		else echo json_encode(array('status' => 'none'));
	}
}

/* End of file Clientes.php */
/* Location: ./system/application/controllers/Clientes.php */