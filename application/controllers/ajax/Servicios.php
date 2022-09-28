<?php

class Servicios extends CI_Controller {	
	
	function __construct() {
		parent::__construct();
	}	
	
	public function servicios($id){
		$json = $this->codegen_model->get('flash_servicios','*','grupo_id ='.$id);
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}
        
        public function codigoExistente(){
                $codigo = $_POST['codigo'];
		$json = $this->codegen_model->row('flash_servicios','codigo','codigo = '.$codigo);
		echo json_encode($json);
	}
        
        public function getActualizacionesPendientes(){
		$json = $this->codegen_model->row('flash_actualizacion_precios','id','fecha_aprobacion IS NULL AND fecha_rechazo IS NULL AND tipo_id = 6');
		echo json_encode($json);
	}
        
        function getServiciosPorComprobante($comprobante_numero){
            $comprobante = $this->codegen_model->row('flash_comprobantes_ingresos','id','numero='.$comprobante_numero);
            $query = $this->db
                            ->select('s.id, s.nombre, cis.disponible, cis.id comprobante_servicio_id')
                            ->join('flash_servicios s', 's.id = cis.servicio_id')
                            ->where('cis.comprobante_ingreso_id = '.$comprobante->id)
                            ->get('flash_comprobantes_ingresos_servicios cis');
           
            $json = $query->result();
//            echo $this->db->last_query();die;
            if($json) echo json_encode($json);
            else echo json_encode(array('status' => 'none'));
        }
        
        function getServiciosNombresPorComprobante($comprobante_numero){

            $comprobante = Comprobante::whereNumero($comprobante_numero)->first();
            $query = $this->db
                            ->select('s.id, s.nombre, cis.disponible, cis.id comprobante_servicio_id')
                            ->join('flash_servicios s', 's.id = cis.servicio_id')
                            ->where('cis.comprobante_ingreso_id = '.$comprobante->id)
                            ->get('flash_comprobantes_ingresos_servicios cis');

            $json = $query->result();
            if($json) echo json_encode($json);
            else echo json_encode(array('status' => 'none'));
        }
        
        function getServiciosPorCliente($cliente_id){
            
            $query = $this->db
                            ->select('pe.cliente_id, pe.id, s.codigo, pe.nombre, pe.precio, pe.dias')
                            ->join('flash_clientes_precios_especiales pe', 's.id = pe.servicio_id')
                            ->where('pe.cliente_id = '.$cliente_id)
                            ->get('flash_servicios s');
           
            $json = $query->result();
            if($json) echo json_encode($json);
            else echo json_encode(array('status' => 'none'));
        }
        
        function traerPiezasComprobantesServiciosSimples(){
            $Comprobante_ingreso =  Comprobante::whereNumero($this->input->post('codigo_barra'))->first();
            $servicio_id =  $this->input->post('servicio_id');
            
            $user_row = $this->ion_auth->user()->row();
            $query = $this->db
                            ->select('`ci`.`id`           `comprobante_ingreso_id`,
                                    `ci`.`numero`,
                                    `s`.`id`            `servicio_id`,
                                    `s`.`nombre`        `servicio`,
                                    SUM(`cis`.`cantidad`)    `cantidad_piezas`,
                                    (	SELECT COUNT(p.id) 
                                          FROM flash_piezas p 
                                          INNER JOIN flash_subpiezas sp ON sp.pieza_id = p.id 
                                          INNER JOIN flash_comprobantes_ingresos_servicios ciser ON p.comprobante_ingreso_id = ciser.comprobante_ingreso_id AND p.servicio_id = ciser.id
                                          INNER JOIN flash_comprobantes_ingresos cing ON p.comprobante_ingreso_id = cing.id
                                          WHERE p.comprobante_ingreso_id = '.$Comprobante_ingreso->id.'
                                          AND ciser.servicio_id = cis.servicio_id
                                          AND cis.remito = ciser.remito
                                    ) cantidad_piezas_carteros,
                                    `cis`.`remito`,
                                    cis.id comprobante_ingreso_servicio_id')
                            ->join('flash_comprobantes_ingresos_servicios cis', 'ci.id = cis.comprobante_ingreso_id')
                            ->join('flash_servicios s', 'cis.servicio_id = s.id')
                           // ->join('flash_piezas p', 'ci.id = p.comprobante_ingreso_id')
                            ->where('ci.id = '.$Comprobante_ingreso->id)
                            ->group_by('cis.id, servicio_id')
                            ->get('flash_comprobantes_ingresos ci');
            echo $this->db->last_query();die;
            return $query->result();
//            //Traigo las piezas disponibles solo de las que tiene el usuario en su sucursal
//            $user_row = $this->ion_auth->user()->row();
//            //$query = $this->db->where('p.sucursal_id', $user_row->sucursal_id);
//
//            //Traigo las novedades del comprobante y servicio seleccionados (quite sucursal))
//            $query_cantidad_piezas = "  SELECT ci.id comprobante_ingreso_id, ci.numero, s.nombre servicio,p.servicio_id servicio_id, p.estado_id, COUNT(*) piezas 
//                                        FROM flash_piezas p
//                                        INNER JOIN flash_comprobantes_ingresos ci ON p.comprobante_ingreso_id = ci.id
//                                        INNER JOIN flash_comprobantes_ingresos_servicios cis ON p.servicio_id = cis.id
//                                        INNER JOIN flash_servicios s ON cis.servicio_id = s.id
//                                        AND p.comprobante_ingreso_id = 6680 
//                                        AND p.servicio_id = 9120 
//                                        AND s.acuse = 0
//                                        GROUP BY p.servicio_id ";
//            echo $query_cantidad_piezas;die;
//            $json_novedades = $this->db->query($query_novedades);
//
//            if($json_novedades) echo json_encode(array("novedades"=>$json_novedades->result()));
//            else echo json_encode(array('status' => 'none'));
	}
}

/* End of file Servicios.php */
/* Location: ./system/application/controllers/Servicios.php */
