<?php

class Liquidaciones extends CI_Controller {	
	
	protected $domicilio_array = [];
	
	function __construct() {
		parent::__construct();
		$this->load->model('Tarifario_f_md');
	}	
        
        public function getPiezasPendientesLiquidar(){
//                        var_dump($_POST);die;
            $cliente_id = $this->input->post('cliente_id');
            $periodo_desde = $this->input->post('periodo_desde') != ''?$this->input->post('periodo_desde'):false;
            $periodo_hasta = $this->input->post('periodo_hasta') != ''?$this->input->post('periodo_hasta'):false;
            $date_desde = new DateTime($periodo_desde);
            $date_hasta = new DateTime($periodo_hasta);
            if($periodo_desde){
                $query = $this->db->where('ci.create >= ', $date_desde->format('Y-m-d 00:00:00'));
            }
            if($periodo_hasta){
                $query = $this->db->where('ci.create <= ', $date_hasta->format('Y-m-d 23:59:59'));
            }
            $departamento_id = $this->input->post('departamento_id') != ''?$this->input->post('departamento_id'):false;
            $sucursal_id = $this->input->post('sucursal_id') != ''?$this->input->post('sucursal_id'):false;
            if ($departamento_id){
                $query = $this->db->where('ci.departamento_id =', (int)$departamento_id);
            }
            if ($sucursal_id){
                $query = $this->db->where('ci.sucursal_id = ', (int)$sucursal_id);
            }
            
            $query = $this->db
                    ->select('  CONCAT(c.id,s.nombre,"") as unido,
                                c.nombre,
                                ci.numero, 
                                cis.remito, 
                                DATE_FORMAT(ci.create,"%d-%m-%Y") AS fecha,
                                s.id AS servicio_id,
                                s.nombre AS servicio, 
                                cis.cantidad AS cantidad,
                                (CASE WHEN `cpe`.`precio` IS NOT NULL THEN cpe.precio	ELSE s.precio END) AS precio, 
                                (CASE WHEN `cpe`.`precio` IS NOT NULL THEN ci.cantidad * cpe.precio ELSE s.precio * ci.cantidad END) AS importe, 
                                (CASE WHEN `cpe`.`nombre` IS NOT NULL THEN `cpe`.`nombre` ELSE s.nombre END) AS `servicio_especial`')
                    ->join('flash_comprobantes_ingresos_servicios cis', 'ci.id = cis.comprobante_ingreso_id')
                    ->join('flash_servicios s', 'cis.servicio_id = s.id')
                    ->join('flash_clientes_precios_especiales cpe', 'cpe.cliente_id = ci.cliente_id AND cis.servicio_id = cpe.servicio_id','left')
                    ->join('flash_clientes c', 'ci.cliente_id = c.id')
                    ->where('ci.cliente_id = '.$cliente_id)
                    ->where('ci.liquidacion_id = 0')
                    ->where('ci.cantidad IS NOT NULL')
                    ->where('cis.servicio_id <> 2') //2:Paqueteria
                    ->order_by('ci.create ')
                    ->get('flash_comprobantes_ingresos ci');
            //echo $this->db->last_query();die;
            if($periodo_desde){
                $query_resumen = $this->db->where('ci.create >= ', $date_desde->format('Y-m-d 00:00:00'));
            }
            if($periodo_hasta){
                $query_resumen = $this->db->where('ci.create <= ', $date_hasta->format('Y-m-d 23:59:59'));
            }
            if ($departamento_id){
                $query_resumen = $this->db->where('ci.departamento_id =', (int)$departamento_id);
            }
            if ($sucursal_id){
                $query_resumen = $this->db->where('ci.sucursal_id = ', (int)$sucursal_id);
            }
            //echo $this->db->last_query();die;
            $query_resumen = $this->db
                            ->select('  s.id AS codigo,
                                        s.nombre AS servicio,
                                        SUM(cis.cantidad) AS cantidad')
                            ->join('flash_comprobantes_ingresos_servicios cis', 'ci.id = cis.comprobante_ingreso_id')
                            ->join('flash_servicios s', 'cis.servicio_id = s.id')
                            ->join('flash_clientes_precios_especiales pe', 'pe.cliente_id = ci.cliente_id AND cis.servicio_id = pe.servicio_id')
                            ->join('flash_clientes c', 'ci.cliente_id = c.id')
                            ->where('ci.cliente_id = '.$cliente_id)
                            ->where('liquidacion_id = 0')
                            ->where('cis.servicio_id <> 2') //2:Paqueteria
                            ->group_by('cis.servicio_id')
                            ->order_by('ci.create ')
                            ->having('cantidad > 0 ')
                            ->get('flash_comprobantes_ingresos ci');
            //echo $this->db->last_query();die;
            $json = $query->result();
            $json_resumen = $query_resumen->result();
            
            if($json) echo json_encode(array('grilla' => $json, 'resumen' => $json_resumen));
            else echo json_encode(array('status' => 'none'));
	}
        
        public function getLiquidacionEdit(){
            $liquidacion_id = $this->input->post('liquidacion_id');

            $query = $this->db
                    ->select('ld.*, ld.remito_cliente as remito, ld.comprobante_ingreso as numero, c.nombre')
                    ->join('flash_liquidaciones l', 'l.id = ld.liquidacion_id')
                    ->join('flash_clientes c', 'c.id = l.cliente_id')
                    ->where('ld.liquidacion_id = '.$liquidacion_id)
                    ->get('flash_liquidaciones_detalles ld');
            // echo $this->db->last_query();die;
            $query_resumen = $this->db
                    ->select('ld.servicio_id AS codigo, ld.servicio AS servicio, SUM(ld.cantidad) AS cantidad ')
                    ->where('ld.liquidacion_id = '.$liquidacion_id)
                    ->group_by('ld.servicio_id')
                    ->get('flash_liquidaciones_detalles ld');
//                echo $this->db->last_query();die;
            $json = $query->result();
            $json_resumen = $query_resumen->result();
            if($json) echo json_encode(array('grilla' => $json,'resumen' => $json_resumen));
            else echo json_encode(array('status' => 'none'));
        }
    
        public function comprobanteExistente($numero){
                
            $query = $this->db
                    ->select(' id ')
                    ->where('numero = "'.$numero.'"')
                    ->get('flash_comprobantes_ingresos');

            $json = $query->result();
            if($json) echo json_encode($json);
            else echo json_encode(array('status' => 'El comprobante no existe'));
	}
        
//        function __construct()
//        { 
//            parent::__construct(); 
//            
//        } 

        public function getPiezasPendientesLiquidarXCliente(){
            $cliente_id = $this->input->post('cliente_id');
            
            $query = $this->db
                    ->select('c.nombre,  ci.numero, COUNT(p.id) AS piezas ')
                    ->join('flash_piezas p', 'p.comprobante_ingreso_id = ci.id')
                    ->join('flash_clientes c', 'c.id = ci.cliente_id')
                    ->where('liquidacion_id = 0')
                    ->where('ci.cliente_id = '.$cliente_id)
                    ->group_by('ci.id')
                    ->get('flash_comprobantes_ingresos ci');
            
            $json = $query->result();
//            echo $this->db->last_query();die;
            if($json) echo json_encode(array('grilla' => $json));
            else echo json_encode(array('status' => 'none'));
        }
        
        public function getPiezasPendientesLiquidarXPeriodo(){
            $periodo_desde = $this->input->post('periodo_desde') != ''?$this->input->post('periodo_desde'):false;
            $periodo_hasta = $this->input->post('periodo_hasta') != ''?$this->input->post('periodo_hasta'):false;
            $date_desde = new DateTime($periodo_desde);
            $date_hasta = new DateTime($periodo_hasta);
            if($periodo_desde) $query = $this->db->where('ci.create >= ', $date_desde->format('Y-m-d 00:00:00'));
            if($periodo_hasta) $query = $this->db->where('ci.create <= ', $date_hasta->format('Y-m-d 23:59:59'));
            
            $query = $this->db
                    ->select('c.nombre,  ci.numero, s.nombre AS servicio, ci.create AS fecha, COUNT(p.id) AS cantidad')
                    ->join('flash_piezas p', 'p.comprobante_ingreso_id = ci.id')
                    ->join('flash_comprobantes_ingresos_servicios cis', 'cis.comprobante_ingreso_id = ci.id')
                    ->join('flash_servicios s', 's.id = cis.servicio_id')
                    ->join('flash_clientes c', 'c.id = ci.cliente_id')
                    ->where('liquidacion_id = 0')
                    ->where('cis.servicio_id <> 2') //2:Paqueteria
                    ->group_by('s.id')
                    ->get('flash_comprobantes_ingresos ci');
            
            $json = $query->result();
            //echo $this->db->last_query();die;
            if($json) echo json_encode(array('grilla' => $json));
            else echo json_encode(array('status' => 'none'));
        }
        
//        public function getCarterosPendientesLiquidar(){
//            $periodo_desde = $this->input->post('periodo_desde') != ''?$this->input->post('periodo_desde'):false;
//            $periodo_hasta = $this->input->post('periodo_hasta') != ''?$this->input->post('periodo_hasta'):false;
//            $sucursal_id = $this->input->post('sucursal_id') != ''?$this->input->post('sucursal_id'):false;
//            $date_desde = new DateTime($periodo_desde);
//            $date_hasta = new DateTime($periodo_hasta);
//            if($periodo_desde) $query = $this->db->where('hdr.fecha_baja >= ', $date_desde->format('Y-m-d'));
//            if($periodo_hasta) $query = $this->db->where('hdr.fecha_baja <= ', $date_hasta->format('Y-m-d'));
//            if($sucursal_id) $query = $this->db->where('sc.sucursal_id = ', $sucursal_id);
//            
//            $query = $this->db
//                    ->select('  hdr.id AS hdr_id,
//                                DATE_FORMAT(ci.fecha_pedido,"%d-%m-%Y") AS fecha, 
//                                sp.pieza_id,
//                                hdr.cartero_id AS cartero_id,
//                                sc.apellido_nombre,
//                                s.id AS codigo_servicio,
//                                s.nombre AS servicio,
//                                ci.cliente_id,
//                                c.nombre,
//                                pe.precio,
//                                COUNT(sp.pieza_id) AS cantidad_piezas,
//                                pe.precio as precio_cliente,
//                                (COUNT(sp.pieza_id) * pe.precio) AS precio_cartero,
//                                p.estado_id')
//                    ->join('flash_piezas p', 'p.id = sp.pieza_id')
//                    ->join('flash_hojas_rutas hdr', 'hdr.id = sp.hoja_ruta_id')
//                    ->join('flash_comprobantes_ingresos ci', 'ci.id = p.comprobante_ingreso_id')
//                    ->join('flash_comprobantes_ingresos_servicios cis', 'cis.id = p.servicio_id AND ci.id = cis.comprobante_ingreso_id')
//                    ->join('flash_servicios s', 's.id = cis.servicio_id')
//                    ->join('flash_clientes c', 'c.id = ci.cliente_id')
//                    ->join('flash_clientes_precios_especiales pe', 'pe.servicio_id = cis.servicio_id AND pe.cliente_id = ci.cliente_id')
//                    ->join('flash_sucursales_carteros sc', 'sc.id = hdr.cartero_id')
//                    ->where('hdr.fecha_baja IS NOT NULL')
//                    //->where('hdr.id NOT IN (SELECT hoja_de_ruta_id FROM flash_liquidaciones_carteros_detalles)')
//                    ->group_by('hdr.cartero_id, p.servicio_id,  hdr.id')
//                    ->order_by('hdr.id')
//                    ->get('flash_subpiezas sp');
//            
//            $json = $query->result();
//            //echo $this->db->last_query();die;
//            if($json) echo json_encode(array('grilla' => $json));
//            else echo json_encode(array('status' => 'none'));
//        }
        
        function getClientesPendientesXSucursal(){
            $sucursal_id = $this->input->post('sucursal_id');
            $fecha_ci_desde = $this->input->post('fecha_ci_desde') != ''?$this->input->post('fecha_ci_desde'):false;
            $fecha_ci_hasta = $this->input->post('fecha_ci_hasta') != ''?$this->input->post('fecha_ci_hasta'):false;
            $date_desde = new DateTime($fecha_ci_desde);
            $date_hasta = new DateTime($fecha_ci_hasta);
            if($fecha_ci_desde) $query = $this->db->where('ci.create >= ', $date_desde->format('Y-m-d 00:00:00'));
            if($fecha_ci_hasta) $query = $this->db->where('ci.create <= ', $date_hasta->format('Y-m-d 23:59:59'));
            
            $query = $this->db
                    ->select('c.nombre')
                    ->join('flash_clientes c', 'ci.cliente_id = c.id')
                    ->where('ci.liquidacion_id = 0')
                    ->where('ci.cantidad IS NOT NULL')
                    ->where('c.tipo_cliente_id = 2')
                    ->where('ci.sucursal_id = '.$sucursal_id)
                    ->group_by('c.id')
                    ->get('flash_comprobantes_ingresos ci');
            
            $json = $query->result();
            //echo $this->db->last_query();die;
            if($json) echo json_encode(array('grilla' => $json));
            else echo json_encode(array('status' => 'none'));
        }
                
        public function getLiquidacionCliente(){
            //var_dump($_POST);die;
            $cliente_id = $this->input->post('cliente_id');
            $periodo_desde = $this->input->post('periodo_desde') != ''?$this->input->post('periodo_desde'):false;
            $periodo_hasta = $this->input->post('periodo_hasta') != ''?$this->input->post('periodo_hasta'):false;
            $date_desde = new DateTime($periodo_desde);
            $date_hasta = new DateTime($periodo_hasta);
            if($periodo_desde){
                $query = $this->db->where('ci.create >= ', $date_desde->format('Y-m-d 00:00:00'));
            }
            if($periodo_hasta){
                $query = $this->db->where('ci.create <= ', $date_hasta->format('Y-m-d 23:59:59'));
            }
            $departamento_id = $this->input->post('departamento_id') != ''?$this->input->post('departamento_id'):false;
            $sucursal_id = $this->input->post('sucursal_id') != ''?$this->input->post('sucursal_id'):false;
            if ($departamento_id){
                $query = $this->db->where('ci.departamento_id =', (int)$departamento_id);
            }
            if ($sucursal_id){
                $query = $this->db->where('ci.sucursal_id = ', (int)$sucursal_id);
            }
            
            $query = $this->db
                    ->select('  CONCAT(c.id,s.nombre,"") as unido,
                                c.nombre,
                                ci.id comprobante_ingreso_id,
                                ci.numero, 
                                cis.remito, 
                                DATE_FORMAT(ci.create,"%d-%m-%Y") AS fecha,
                                s.id AS servicio_id,
                                s.nombre AS servicio, 
                                sum(cis.cantidad) AS cantidad,
                                (( SELECT CASE  WHEN la.cantidad IS  NULL THEN SUM(t.`cant_a_liquidar`) 
                                                ELSE SUM(t.`cant_a_liquidar`+la.cantidad) 
                                            END 
                                            FROM flash_liquidaciones_comprobantes t 
                                            LEFT JOIN flash_liquidaciones_ajustes la ON t.id = la.liquidacion_comprobante_id
                                            WHERE t.comprobante_ingreso_id = ci.id
                                            AND t.servicio_id = cis.servicio_id)) AS cant_liquidada,
                                (CASE WHEN `cpe`.`precio` IS NOT NULL THEN cpe.precio	ELSE s.precio END) AS precio, 
                                (CASE WHEN `cpe`.`precio` IS NOT NULL THEN ci.cantidad * cpe.precio ELSE s.precio * ci.cantidad END) AS importe, 
                                (CASE WHEN `cpe`.`nombre` IS NOT NULL THEN `cpe`.`nombre` ELSE s.nombre END) AS `servicio_especial`')
                    ->join('flash_comprobantes_ingresos_servicios cis', 'ci.id = cis.comprobante_ingreso_id')
                    ->join('flash_servicios s', 'cis.servicio_id = s.id')
                    ->join('flash_clientes_precios_especiales cpe', 'cpe.cliente_id = ci.cliente_id AND cis.servicio_id = cpe.servicio_id','left')
                    ->join('flash_clientes c', 'ci.cliente_id = c.id')
                    ->where('ci.cliente_id = '.$cliente_id)
                    ->where('(ci.estado_liquidacion <> 2 OR ci.estado_liquidacion IS NULL)')
                    ->where('ci.cantidad IS NOT NULL')
                    ->where('cis.servicio_id <> 2') //2:Paqueteria
                    //->where('cpe.fecha_vto < CURRENT_DATE()')
                    ->group_by('ci.id, cis.servicio_id, cis.remito')
                    ->order_by('ci.create ')
                    ->get('flash_comprobantes_ingresos ci');
            //echo $this->db->last_query();die;
            $where = "";
            if($periodo_desde){
                $where .= ' AND ci.create >= '. '"'.$date_desde->format('Y-m-d 00:00:00').'"';
            }
            if($periodo_hasta){
                $where .= ' AND ci.create <= '. '"'.$date_hasta->format('Y-m-d 23:59:59').'"' ;
            }
            if ($departamento_id){
                $where .= ' AND ci.departamento_id = '. (int)$departamento_id;
            }
            if ($sucursal_id){
                $where .= ' AND ci.sucursal_id = '. (int)$sucursal_id;
            }
            //echo $this->db->last_query();die;
            $query_resumen  = ' SELECT  s.id AS codigo,
                                s.nombre AS servicio, 
                                SUM(IFNULL(( SELECT CASE  WHEN la.cantidad IS  NULL THEN cis.cantidad - SUM(t.cant_a_liquidar) 
                                                ELSE cis.cantidad - SUM(t.cant_a_liquidar+la.cantidad) 
                                            END 
                                            FROM flash_liquidaciones_comprobantes t 
                                            LEFT JOIN flash_liquidaciones_ajustes la ON t.id = la.liquidacion_comprobante_id
                                            WHERE t.comprobante_ingreso_id = ci.id
                                            AND t.servicio_id = cis.servicio_id ),cis.cantidad)) AS cantidad
                    FROM flash_comprobantes_ingresos ci
                    INNER JOIN flash_comprobantes_ingresos_servicios cis ON ci.id = cis.comprobante_ingreso_id
                    INNER JOIN flash_servicios s ON cis.servicio_id = s.id
                    LEFT JOIN flash_clientes_precios_especiales cpe ON cpe.cliente_id = ci.cliente_id AND cis.servicio_id = cpe.servicio_id
                    INNER JOIN flash_clientes c ON ci.cliente_id = c.id
                    WHERE ci.cliente_id = '.$cliente_id.' 
                     AND  (ci.estado_liquidacion <> 2 OR ci.estado_liquidacion IS NULL)
                     AND  ci.cantidad IS NOT NULL
                     AND cis.servicio_id <> 2
                    
                    '.$where.' 
                     GROUP BY cis.servicio_id
                     ORDER BY ci.create ';
            //echo $query_resumen;die;
            $json = $query->result();
            $json_resumen = $this->db->query($query_resumen)->result();
            
            if($json) echo json_encode(array('grilla' => $json, 'resumen' => $json_resumen));
            else echo json_encode(array('status' => 'none'));
	}
        
        public function getLiquidacionClientesPendientes(){
           
            $cliente_id = $this->input->post('cliente_id');
            $periodo_desde = $this->input->post('periodo_desde') != ''?$this->input->post('periodo_desde'):false;
            $periodo_hasta = $this->input->post('periodo_hasta') != ''?$this->input->post('periodo_hasta'):false;
            $date_desde = new DateTime($periodo_desde);
            $date_hasta = new DateTime($periodo_hasta);
            if($periodo_desde){
                $query = $this->db->where('ci.create >= ', $date_desde->format('Y-m-d 00:00:00'));
            }
            if($periodo_hasta){
                $query = $this->db->where('ci.create <= ', $date_hasta->format('Y-m-d 23:59:59'));
            }
            $departamento_id = $this->input->post('departamento_id') != ''?$this->input->post('departamento_id'):false;
            $sucursal_id = $this->input->post('sucursal_id') != ''?$this->input->post('sucursal_id'):false;
            if ($departamento_id){
                $query = $this->db->where('ci.departamento_id =', (int)$departamento_id);
            }
            if ($sucursal_id){
                $query = $this->db->where('ci.sucursal_id = ', (int)$sucursal_id);
            }
            
            $query = $this->db
                    ->select('  CONCAT(c.id,s.nombre,"") as unido,
                                c.nombre,
                                ci.id comprobante_ingreso_id,
                                ci.numero, 
                                cis.remito, 
                                DATE_FORMAT(ci.create,"%d-%m-%Y") AS fecha,
                                s.id AS servicio_id,
                                s.nombre AS servicio, 
                                cis.cantidad AS cantidad,
                                (CASE WHEN `cpe`.`precio` IS NOT NULL THEN cpe.precio	ELSE s.precio END) AS precio, 
                                (CASE WHEN `cpe`.`precio` IS NOT NULL THEN ci.cantidad * cpe.precio ELSE s.precio * ci.cantidad END) AS importe, 
                                (CASE WHEN `cpe`.`nombre` IS NOT NULL THEN `cpe`.`nombre` ELSE s.nombre END) AS `servicio_especial`')
                    ->join('flash_comprobantes_ingresos_servicios cis', 'ci.id = cis.comprobante_ingreso_id')
                    ->join('flash_servicios s', 'cis.servicio_id = s.id')
                    ->join('flash_clientes_precios_especiales cpe', 'cpe.cliente_id = ci.cliente_id AND cis.servicio_id = cpe.servicio_id','left')
                    ->join('flash_clientes c', 'ci.cliente_id = c.id')
                    ->where('ci.cliente_id = '.$cliente_id)
                    ->where('(ci.estado_liquidacion <> 2 OR ci.estado_liquidacion IS NULL)')
                    ->where('ci.cantidad IS NOT NULL')
                    ->where('cis.servicio_id <> 2') //2:Paqueteria
                    //->where('cpe.fecha_vto < CURRENT_DATE()')
                    ->order_by('ci.create ')
                    ->get('flash_comprobantes_ingresos ci');
            

            if($periodo_desde){
                $query_resumen = $this->db->where('ci.create >= ', $date_desde->format('Y-m-d 00:00:00'));
            }
            if($periodo_hasta){
                $query_resumen = $this->db->where('ci.create <= ', $date_hasta->format('Y-m-d 23:59:59'));
            }
            if ($departamento_id){
                $query_resumen = $this->db->where('ci.departamento_id =', (int)$departamento_id);
            }
            if ($sucursal_id){
                $query_resumen = $this->db->where('ci.sucursal_id = ', (int)$sucursal_id);
            }

            $query_resumen = $this->db
                            ->select('  s.id AS codigo,
                                        s.nombre AS servicio,
                                        SUM(cis.cantidad) AS cantidad')
                            ->join('flash_comprobantes_ingresos_servicios cis', 'ci.id = cis.comprobante_ingreso_id')
                            ->join('flash_servicios s', 'cis.servicio_id = s.id')
                            ->join('flash_clientes_precios_especiales pe', 'pe.cliente_id = ci.cliente_id AND cis.servicio_id = pe.servicio_id')
                            ->join('flash_clientes c', 'ci.cliente_id = c.id')
                            ->where('ci.cliente_id = '.$cliente_id)
                            ->where('(ci.estado_liquidacion <> 2 OR ci.estado_liquidacion IS NULL)')
                            ->where('cis.servicio_id <> 2') //2:Paqueteria
                            //->where('pe.fecha_vto < CURRENT_DATE()')
                            ->group_by('cis.servicio_id')
                            ->order_by('ci.create ')
                            ->having('cantidad > 0 ')
                            ->get('flash_comprobantes_ingresos ci');
            $json = $query->result();
            $json_resumen = $query_resumen->result();
            
            if($json) echo json_encode(array('grilla' => $json, 'resumen' => $json_resumen));
            else echo json_encode(array('status' => 'none'));
            
	   }
        
        function getLiquidacionesClientesPendientesXClientesXSucursal(){
            $sucursal_id = $this->input->post('sucursal_id');
            $fecha_ci_desde = $this->input->post('fecha_ci_desde') != ''?$this->input->post('fecha_ci_desde'):false;
            $fecha_ci_hasta = $this->input->post('fecha_ci_hasta') != ''?$this->input->post('fecha_ci_hasta'):false;
            $date_desde = new DateTime($fecha_ci_desde);
            $date_hasta = new DateTime($fecha_ci_hasta);
            if($fecha_ci_desde) $query = $this->db->where('ci.create >= ', $date_desde->format('Y-m-d 00:00:00'));
            if($fecha_ci_hasta) $query = $this->db->where('ci.create <= ', $date_hasta->format('Y-m-d 23:59:59'));
            
            $query = $this->db
                    ->select('c.nombre, ci.departamento_id, cd.nombre departamento')
                    ->join('flash_clientes c', 'ci.cliente_id = c.id')
                    ->join('flash_clientes_departamentos cd', 'cd.id = ci.departamento_id')
                   ->where('(ci.estado_liquidacion <> 2 OR ci.estado_liquidacion IS NULL)')
                   ->where('ci.liquidacion_id = 0')
                    ->where('ci.cantidad IS NOT NULL')
                    ->where('c.tipo_cliente_id = 2')
                    ->where('ci.sucursal_id = '.$sucursal_id)
                    ->group_by('c.id, , cd.id')
                    ->get('flash_comprobantes_ingresos ci');
            
            $json = $query->result();
            //echo $this->db->last_query();die;
            if($json) echo json_encode(array('grilla' => $json));
            else echo json_encode(array('status' => 'none'));
        }
               
        	
	public function getOperacion($concepto_id){
		$json = $this->codegen_model->row('flash_liquidaciones_conceptos','*','id = '.$concepto_id);
//                echo $this->db->last_query();die;
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}
	
    public function getAcreditacionGeneral(){
        $tipo_concepto_id = $this->input->post('tipo_concepto_id');
        $acreditacion_id = $this->input->post('acreditacion_id');
        
        $query = $this->db
                ->select('la.id, la.importe')
                ->where('la.id = '.$acreditacion_id)
                ->where('la.tipo_concepto_id = '.$tipo_concepto_id)
                ->get('flash_liquidaciones_ddis_acreditaciones la');
        
        $json = $query->result();
//            echo $this->db->last_query();die;
        if($json) echo json_encode(array('grilla' => $json));
        else echo json_encode(array('status' => 'none'));
    }
    
    public function getDescuentoGeneral(){
        $tipo_concepto_id = $this->input->post('tipo_concepto_id');
        $descuento_id = $this->input->post('descuento_id');
        
        $query = $this->db
                ->select('la.id, la.importe')
                ->where('la.id = '.$descuento_id)
                ->where('la.tipo_concepto_id = '.$tipo_concepto_id)
                ->get('flash_liquidaciones_ddis_acreditaciones la');
        
        $json = $query->result();
//            echo $this->db->last_query();die;
        if($json) echo json_encode(array('grilla' => $json));
        else echo json_encode(array('status' => 'none'));
    }
    
        public function getLiquidacionClientePaqueteria(){
//          var_dump($_POST);die;
            $cliente_id = $this->input->post('cliente_id');
            $periodo_desde = $this->input->post('periodo_desde') != ''?$this->input->post('periodo_desde'):false;
            $periodo_hasta = $this->input->post('periodo_hasta') != ''?$this->input->post('periodo_hasta'):false;
            $date_desde = new DateTime($periodo_desde);
            $date_hasta = new DateTime($periodo_hasta);
            $departamento_id = $this->input->post('departamento_id') != ''?$this->input->post('departamento_id'):false;
            $sucursal_id = $this->input->post('sucursal_id') != ''?$this->input->post('sucursal_id'):false;
            
        //codigo de Santiago

        // Si hay resultado utilizo el tarifario personalizado nuevo
        $queryTarifarioNuevo = "SELECT 
            T.id as tarifario, 
            C.nombre as cliente, 
            E.nombre as esquema, 
            IF(COUNT(K.id) > 0, 'TRUE', 'FALSE') as kilos, 
            IF(COUNT(B.id) > 0, 'TRUE', 'FALSE') as bultos, 
            IF(COUNT(P.id) > 0, 'TRUE', 'FALSE') as palets, 
            IF(COUNT(M.id) > 0, 'TRUE', 'FALSE') as metrosCubicos, 
            IF(COUNT(W.id) > 0, 'TRUE', 'FALSE') as warehouse, 
            IF(COUNT(G.id) > 0, 'TRUE', 'FALSE') as gestionFlota, 
            IF(COUNT(COB.id) > 0, 'TRUE', 'FALSE') as cobranza, 
            IF(COUNT(V.id) > 0, 'TRUE', 'FALSE') as valorDeclarado, 
            IF(COUNT(PA.id) > 0, 'TRUE', 'FALSE') as pesoAforado
            FROM flash_tarifario as T
            INNER JOIN flash_clientes as C ON C.id = T.cliente_id
            INNER JOIN flash_esquema as E ON E.id = T.esquema_id
            INNER JOIN sub_esquema as S ON S.esquema_id = E.id
            LEFT JOIN flash_sub_esquema_kg as K ON K.sub_esquema_id = S.id
            LEFT JOIN flash_sub_esquema_bultos as B ON B.sub_esquema_id = S.id
            LEFT JOIN flash_sub_esquema_palets as P ON P.sub_esquema_id = S.id
            LEFT JOIN flash_sub_esquema_metros_cubicos as M ON M.sub_esquema_id = S.id
            LEFT JOIN flash_sub_esquema_warehouse as W ON W.sub_esquema_id = S.id
            LEFT JOIN flash_sub_esquema_gestion_flota as G ON G.sub_esquema_id = S.id
            LEFT JOIN flash_sub_esquema_valor_declarado as V ON V.sub_esquema_id = S.id
            LEFT JOIN flash_sub_esquema_cobranza as COB ON COB.sub_esquema_id = S.id
            LEFT JOIN flash_sub_esquema_peso_aforado as PA ON PA.sub_esquema_id = S.id
            WHERE C.id = ?";

        $queryTarifarioNuevo = $this->db->query($queryTarifarioNuevo, [$cliente_id]);
        $esquema = $queryTarifarioNuevo->result_array();
    
        if ($queryTarifarioNuevo !== FALSE && $queryTarifarioNuevo->num_rows() > 0) {
            
            $paquetes = $this->Tarifario_f_md->getPaquetes($cliente_id, $periodo_desde, $periodo_hasta, $departamento_id, $sucursal_id);

            if ($paquetes !== FALSE && $paquetes->num_rows() > 0) {
                $paquetes = $paquetes->result_array();
            
                $valor_calculado = [];
                $precios_unitarios = [];
                $errores = [];
                
                foreach ($paquetes as $paquete) {
                    //$valor_calculado[] = $this->Tarifario_f_md->getPrecioPaquete($cliente_id, $paquete, $esquema, $errores);
                    $lista[] = $this->Tarifario_f_md->getPrecioPaquete($cliente_id, $paquete);
                }
                
            }
            
            
            for($i = 0; $i < count($lista); $i++){
                $precios_unitarios[] = $lista[$i]["precio"];
                if(!empty($lista[$i]["error"])){
                    $errores[] = $lista[$i]["error"];
                }
            }
            
            
            
            //CALCULAR EL VALOR DECLARADO
            if($esquema[0]['valorDeclarado'] == "TRUE" and count($precios_unitarios) > 0){
                //$valorDeclarado = $this->Tarifario_f_md->getPrecioValorDeclarado($cliente_id, $paquetes, $precios_unitarios);            
                $valorDeclarado = $this->Tarifario_f_md->getPrecioValorDeclarado($cliente_id, $paquetes);   
            }

            //CALCULAR COBRANZA
            if($esquema[0]['cobranza'] == "TRUE" and count($precios_unitarios) > 0){
                $cobranza = $this->Tarifario_f_md->getPrecioCobranza($cliente_id, $paquetes);   
            }
            
            //CALCULO EL VALOR CALCULADO
            /*
            if(count($valorDeclarado) > 0){

                for($i = 0; $i < count($valorDeclarado); $i++){
                    $valor_calculado[] = $valorDeclarado[$i] + $precios_unitarios[$i];
                }

            }else{

                for($i = 0; $i < count($precios_unitarios); $i++){
                    $valor_calculado[] = $precios_unitarios[$i];
                }

            }
            */
            
            /*
            print_r("Paquetes: ");
            print_r($paquetes);
            
            print_r("Precios Unitarios: ");
            print_r($precios_unitarios);
            
            print_r("Valor Declarado: ");
            print_r($valorDeclarado);
            
            print_r("Cobranza: ");
            print_r($cobranza);
            */

            if(count($valorDeclarado) == 0 && count($cobranza) == 0){
                //print_r("Valor Declarado = 0 && Cobranza = 0");
                for($i = 0; $i < count($precios_unitarios); $i++){
                    $valor_calculado[] = $precios_unitarios[$i];
                }
            }
            
            if(count($valorDeclarado) > 0 && count($cobranza) == 0){
                //print_r("Valor Declarado > 0 && Cobranza = 0");
                for($i = 0; $i < count($valorDeclarado); $i++){
                    $valor_calculado[] = $valorDeclarado[$i] + $precios_unitarios[$i];
                }
            }
            
            if(count($valorDeclarado) == 0 && count($cobranza) > 0){
                //print_r("Valor Declarado = 0 && Cobranza > 0");
                for($i = 0; $i < count($cobranza); $i++){
                    $valor_calculado[] = $cobranza[$i] + $precios_unitarios[$i];
                }
            }

            if(count($valorDeclarado) > 0 && count($cobranza) > 0){
                //print_r("Valor Declarado > 0 && Cobranza > 0");
                for($i = 0; $i < count($valorDeclarado); $i++){
                    $valor_calculado[] = $valorDeclarado[$i] + $cobranza[$i] + $precios_unitarios[$i];
                }
            }
            

            //print_r($valor_calculado);
            //die;
            
            //VUELVO A CONSULTAR LOS MISMOS PAQUETES, POR SI SE ACTUALIZO EL PESO VOLUMETRICO A LA HORA DE CALCULAR EL PRECIO
            $paquetesActualizados = $this->Tarifario_f_md->getPaquetes($cliente_id, $periodo_desde, $periodo_hasta, $departamento_id, $sucursal_id);
            $paquetesActualizados = $paquetesActualizados->result_array();
            
            //if ($paquetes) echo json_encode(array('grilla' => $paquetes, 'valor_calculado' => $valor_calculado, 'formula_usada' => $formula_usada));
            if ($paquetes) echo json_encode(array('grilla' => $paquetesActualizados, 'valor_calculado' => $valor_calculado, 'formula_usada' => $formula_usada, 'valor_declarado' => $valorDeclarado, 'cobranza' => $cobranza, 'precios_unitario' => $precios_unitarios, 'errores' => $errores));
            else echo json_encode(array('status' => 'none'));
            die;
        
            
        } else {
            $query = $this->db->select('  
            c.nombre,
            ci.id comprobante_ingreso_id,
            ci.numero, 
            cis.remito, 
            DATE_FORMAT(ci.create,"%d-%m-%Y") AS fecha,
            pt.id paqueteria_tipo_id,
            pt.tipo paqueteria_tipo,
            pparam.parametro parametro,
            pparam.valor parametro_valor,
            pp.descripcion_paquete,
            pp.dimensiones,
            pp.peso,
            pp.bultos,
            pp.dias_entrega,
            p.codigo_postal_destino,
            p.domicilio,
            p.barcode_externo')
                ->join('flash_piezas p', 'p.id = pp.pieza_id')
                ->join('flash_comprobantes_ingresos ci', 'p.comprobante_ingreso_id = ci.id')
                ->join('flash_clientes c', 'ci.cliente_id = c.id')
                ->join('flash_paqueteria_tipos pt', 'c.flash_paqueteria_tipo_id = pt.id')
                ->join('flash_paqueteria_parametros pparam', 'pt.id = pparam.flash_paqueteria_tipo_id')
                ->join('flash_comprobantes_ingresos_servicios cis', 'p.servicio_id = cis.id')
                ->where('ci.cliente_id = ' . $cliente_id)
                ->where('(ci.estado_liquidacion <> 2 OR ci.estado_liquidacion IS NULL)')
                ->where('ci.cantidad IS NOT NULL')
                ->where('cis.servicio_id = 2') //2:Paqueteria
                ->group_by('ci.id, cis.servicio_id, cis.remito')
                ->order_by('ci.create ')
                ->get('flash_piezas_paquetes pp');
            //            echo $this->db->last_query();die;

            $json = $query->result();

            $valor_calculado[] = NULL;
            $i = 0;




            $valor_calculado[] = NULL;
            $i = 0;
            foreach ($json as $value) {
                echo("switch");
                switch ($value->paqueteria_tipo_id) {
                    case 2:
                        $valor_calculado[$i] = $this->corteMinimo($value->peso, $cliente_id);
                        $i++;
                        break;
                    case 3:
                        $valor_calculado[$i] = $this->rangosPeso($value->peso, $cliente_id);
                        $i++;
                        break;
                    case 4:
                        $valor_calculado[$i] = $this->pesoVolumetrico($value->dimensiones, $cliente_id);
                        $i++;
                        break;
                    case 5:
                        $valor_calculado[$i] = $this->tarifaPorCodigoPostal($value->codigo_postal, $cliente_id);
                        $i++;
                        break;
                    case 6:
                        $valor_calculado[$i] = $this->tarifaPlanaPorDomicilio($value->domicilio, $value->codigo_postal, $cliente_id);
                        $i++;
                        break;
                    case 7:
                        $valor_calculado[$i] = $this->tarifaPlanaPorKilogramos($value->peso, $cliente_id);
                        $i++;
                        break;
                    case 8:
                        $valor_calculado[$i] = $this->tarifaPlanaPorBulto($value->bultos, $cliente_id);
                        $i++;
                        break;
                    case 9:
                        $valor_calculado[$i] = $this->tarifaPorDistancia($cliente_id);
                        $i++;
                        break;
                    case 10:
                        $valor_calculado[$i] = $this->corteMinimoPorBulto($value->bultos, $cliente_id);
                        $i++;
                        break;
                    case 11:
                        $valor_calculado[$i] = $this->plazoEntrega();
                        $i++;
                        break;
                }
            }
//
        }
        
            if($json) echo json_encode(array('grilla' => $json, 'valor_calculado' => $valor_calculado, 'formula_usada' => $formula_usada));
            else echo json_encode(array('status' => 'none'));
	}
        
        function en_rango($val, $min, $max){
            if ($val >= $min && $val <= $max ){ 
                return TRUE;
            }else{                
                return FALSE; 
            }
        }
        
        function corteMinimo($kilogramosIngresados, $cliente_id){         
            $parametros = $this->codegen_model->get("flash_paqueteria_parametros",'*', 'cliente_id = '.$cliente_id);
            //var_dump($parametros);die;
            $valorCorteMinimo = $parametros[0]->valor;
            $corteMinimo = $parametros[1]->valor;
            $valorKilosAdicional = $parametros[2]->valor;
            //echo "aqui: ".$valorCorteMinimo."  ". $kilogramosIngresados."  ".$corteMinimo."  ".$valorKilosAdicional;
            $valorCalculado = ( $valorCorteMinimo) + (($kilogramosIngresados - $corteMinimo) * $valorKilosAdicional);
           // echo $valorCalculado;die;
            return $valorCalculado;
        }
        
        function rangosPeso($kilogramosIngresados,$cliente_id){
            $parametros = $this->codegen_model->get("flash_paqueteria_parametros",'*', 'flash_paqueteria_tipo_id = 3 AND cliente_id = '.$cliente_id);
            //var_dump($parametros);die;
            foreach ($parametros as $value) {
                $array = split(" ", $value->parametro);
               // echo $array[0].$array[1]."<br/>";
                if ($array[0].$array[1] == "valor_kiligramo_adicional"){
                        $kg_adicional = $value->valor;
                }else{
                        if ($this->en_rango($kilogramosIngresados, $array[0], $array[1])){  
                            $valor_rango = $value->valor;
    //                        $tope_rango = $array[5];
                        }
                }

            }
            //die;
           // echo $kilogramosIngresados."  -  ".$valor_rango."  -  ".$tope_rango." -  ".$kg_adicional."<br/>";
            $valorCalculado = $valor_rango;

            return $valorCalculado;
        }
        
        function pesoVolumetrico($dimensiones,$cliente_id){
            $parametros = $this->codegen_model->get("flash_paqueteria_parametros",'*', 'flash_paqueteria_tipo_id = 4 AND cliente_id = '.$cliente_id);
//            var_dump($parametros);die;
            $aforo = $parametros[0]->valor;
            $precio_por_unidad = $parametros[1]->valor;
//            $query_precio = "SELECT pe.precio importe FROM flash_clientes_precios_especiales pe
//                            WHERE cliente_id = $cliente_id
//                            AND servicio_id = 2"; //2= paqueteria
//            $precio = $this->db->query($query_precio)->row();
            
            $dimensiones_array = split("x", $dimensiones);

            $volumen = doubleval($dimensiones_array[0])*doubleval($dimensiones_array[1])*doubleval($dimensiones_array[2]);
//            echo $dimensiones."   ".$volumen."   ".$aforo."   ".$precio_por_unidad."<br>";
            $valorCalculado = floatval(str_replace(",", ".", $volumen)) *  floatval(str_replace(",",".", $aforo)) * floatval(str_replace(",", ".", $precio_por_unidad));

            return $valorCalculado;
        }
        
        function tarifaPlanaPorKilogramos($kilogramos,$cliente_id){
            $parametros = $this->codegen_model->get("flash_paqueteria_parametros",'*', 'flash_paqueteria_tipo_id = 7 AND cliente_id = '.$cliente_id);
            $valor_por_kg = $parametros[0]->valor;
            $valorCalculado = $valor_por_kg * $kilogramos ;
//                        echo $valor_por_kg."  -  ".$kilogramos."<br/>";
            return $valorCalculado;
        }
        
        function tarifaPlanaPorBulto($bultos,$cliente_id){
            $parametros = $this->codegen_model->get("flash_paqueteria_parametros",'*', 'flash_paqueteria_tipo_id = 8 AND cliente_id = '.$cliente_id);
            $valor_por_bulto = $parametros[0]->valor;
//            foreach ($parametros as $value) {
//                $bultos_array = split(" ", $value->parametro);
////                var_dump($value);die;
//                if ($bultos_array[1] == $bultos){
//                    $valor_bultos = $value->valor ;
//                }
//            }
            //echo $valor_bultos."  -  ".$bultos."<br/>";
            $valorCalculado = $valor_por_bulto * $bultos ;
            return $valorCalculado;
        }
        
        function tarifaPorCodigoPostal($codigo_postal,$cliente_id){
            $parametros = $this->codegen_model->get("flash_paqueteria_parametros",'*', 'flash_paqueteria_tipo_id = 5 AND cliente_id = '.$cliente_id);
//            var_dump($parametros);die;
            foreach ($parametros as $value) {
                $array = split(" ", $value->parametro);
                    if ($this->en_rango($codigo_postal, $array[0], $array[1])){  
                        $valor_rango = $value->valor;
//                        $tope_rango = $array[5];
                    }
            }
//            foreach ($parametros as $value) {
//                $str_cod_post = str_replace("(", "", $value->parametro);
//                $str_cod_post = str_replace(")", "", $str_cod_post);
//                $str_cod_post = str_replace(" ", ",", $str_cod_post);
//                $codigo_postal_array  = split(",", $str_cod_post);
////                var_dump($codigo_postal);die;
//                if (in_array($codigo_postal, $codigo_postal_array)){
//                    $valor_codigo_postal = $value->valor ;
//                    $rango_de_cp = $codigo_postal_array[1];
//                }
//            }
//            echo $valor_rango."   ".$codigo_postal."<br/>";
            $valorCalculado = $valor_rango ;
            return $valorCalculado;
        }
        
        function tarifaPlanaPorDomicilio($domicilio,$codigo_postal,$cliente_id){
            
            $parametros = $this->codegen_model->get("flash_paqueteria_parametros",'*', 'flash_paqueteria_tipo_id = 6 AND cliente_id = '.$cliente_id);
//            var_dump($parametros);die;
            if(in_array( $domicilio.$codigo_postal,$this->domicilio_array)){
                $valor_envio = 0; 
                
            }else{
               $valor_envio = $parametros[0]->valor; 
               $this->domicilio_array[]= $domicilio.$codigo_postal;
            }
//            echo $domicilio.$codigo_postal."  -  ".$valor_envio."<br/>";
            $valorCalculado = $valor_envio ;
            return $valorCalculado;
        }
        
//        function tarifaPorDistancia($cliente_id){
//            $query = $this->db
//                    ->select('  
//                                c.nombre,
//                                ci.id comprobante_ingreso_id,
//                                ci.numero, 
//                                cis.remito, 
//                                DATE_FORMAT(ci.create,"%d-%m-%Y") AS fecha,
//                                pt.id paqueteria_tipo_id,
//                                pt.tipo paqueteria_tipo,
//                                pparam.parametro parametro,
//                                pparam.valor parametro_valor,
//                                pp.descripcion_paquete,
//                                pp.dimensiones,
//                                pp.peso,
//                                pp.bultos,
//                                pp.dias_entrega,
//                                p.codigo_postal,
//                                p.domicilio')
//                    ->join('flash_piezas p', 'p.id = pp.pieza_id')
//                    ->join('flash_comprobantes_ingresos ci', 'p.comprobante_ingreso_id = ci.id')
//                    ->join('flash_clientes c', 'ci.cliente_id = c.id')
//                    ->join('flash_paqueteria_tipos pt', 'c.flash_paqueteria_tipo_id = pt.id')
//                    ->join('flash_paqueteria_parametros pparam', 'pt.id = pparam.flash_paqueteria_tipo_id')
//                    ->join('flash_comprobantes_ingresos_servicios cis', 'p.servicio_id = cis.id')
//                    ->join('flash_piezas_despacho_piezas pdp', 'p.id = pdp.pieza_id')
//                    ->where('ci.cliente_id = '.$cliente_id)
//                    ->where('(ci.estado_liquidacion <> 2 OR ci.estado_liquidacion IS NULL)')
//                    ->where('ci.cantidad IS NOT NULL')
//                    ->where('cis.servicio_id <> 2') //2:Paqueteria
//                    ->group_by('ci.id, cis.servicio_id, cis.remito')
//                    ->order_by('ci.create ')
//                    ->get('flash_piezas_paquetes pp');
////            echo $this->db->last_query();die;
//           $json = $query->result();
//           
//            $parametros = $this->codegen_model->get("flash_paqueteria_parametros",'*', 'flash_paqueteria_tipo_id = 5');
//            foreach ($parametros as $value) {
//                $str_cod_post = str_replace("(", "", $value->parametro);
//                $str_cod_post = str_replace(")", "", $str_cod_post);
//                $str_cod_post = str_replace(" ", ",", $str_cod_post);
//                $codigo_postal_array  = split(",", $str_cod_post);
////                var_dump($codigo_postal);die;
//                if (in_array($codigo_postal, $codigo_postal_array)){
//                    $valor_codigo_postal = $value->valor ;
//                    $rango_de_cp = $codigo_postal_array[1];
//                }
//            }
////            echo $rango_de_cp."  -  ".$valor_codigo_postal."<br/>";
//            $valorCalculado = $rango_de_cp * $valor_codigo_postal ;
//            return $valorCalculado;
//        }
        
         function corteMinimoPorBulto($bultos_ingresados,$cliente_id){
            $parametros = $this->codegen_model->get("flash_paqueteria_parametros",'*', 'flash_paqueteria_tipo_id = 10 AND cliente_id = '.$cliente_id);
//            var_dump($parametros);die;
            $valorCorteMinimo = $parametros[0]->valor;
            $corteMinimo = $parametros[1]->valor;
            $valorBultoAdicional = $parametros[2]->valor;
//            echo $valorCorteMinimo."  ".$bultos_ingresados."   ".$corteMinimo."  ".$valorBultoAdicional."<br>";
            $valorCalculado = ( $valorCorteMinimo) + (($bultos_ingresados - $corteMinimo) * $valorBultoAdicional);
            return $valorCalculado;
        }
        
}

/* End of file Piezas.php */