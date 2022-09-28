<?php

class Hojas_rutas extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getHDRPendientes()
    {
        $periodo_desde = $this->input->post('periodo_desde') != '' ? $this->input->post('periodo_desde') : false;
        $periodo_hasta = $this->input->post('periodo_hasta') != '' ? $this->input->post('periodo_hasta') : false;
        $date_desde    = new DateTime($periodo_desde);
        $date_hasta    = new DateTime($periodo_hasta);
        if ($periodo_desde) {
            $query = $this->db->where('hdr.create >= ', $date_desde->format('Y-m-d 00:00:00'));
        }
        if ($periodo_hasta) {
            $query = $this->db->where('hdr.create <= ', $date_hasta->format('Y-m-d 23:59:59'));
        }
        $sucursal_id = $this->input->post('sucursal_id') != '' ? $this->input->post('sucursal_id') : false;
        if ($sucursal_id) {
            $query = $this->db->where('hdr.sucursal_id = ', (int) $sucursal_id);
        }

        $query = $this->db
            ->select('  hdr.id hdr_id, sc.apellido_nombre, DATE_FORMAT(hdr.create,"%d-%m-%Y") fecha_creacion, COUNT(*) piezas ')
            ->join('flash_subpiezas sp', 'hdr.id = sp.hoja_ruta_id', 'left')
            ->join('flash_sucursales_carteros sc', 'sc.id = hdr.cartero_id')
            ->join('flash_piezas p', 'sp.pieza_id = p.id')
            ->join('flash_comprobantes_ingresos_servicios cis', 'p.servicio_id = cis.id')
            ->join('flash_servicios s', 'cis.servicio_id = s.id')
            ->where('hdr.fecha_baja IS NULL')
            ->where('hdr.estado <> '.Hoja::ESTADO_CANCELADA)
            ->where('s.`grupo_id` <> 4 ') //sin finishing
            ->group_by('hdr.id')
            ->order_by('hdr.create ')
            ->get('flash_hojas_rutas hdr');
        //echo $this->db->last_query();die;
        $json = $query->result();

        if ($json) {
            echo json_encode(array('grilla' => $json));
        } else {
            echo json_encode(array('status' => 'none'));
        }

    }

    public function getHDRPendientesXCarteros()
    {
        $periodo_desde = $this->input->post('periodo_desde') != '' ? $this->input->post('periodo_desde') : false;
        $periodo_hasta = $this->input->post('periodo_hasta') != '' ? $this->input->post('periodo_hasta') : false;
        $cartero_id    = $this->input->post('cartero_id') != '' ? $this->input->post('cartero_id') : false;
        $sucursal_id   = $this->input->post('sucursal_id') != '' ? $this->input->post('sucursal_id') : false;
        if ($cartero_id) {
            $query = $this->db->where('hdr2.cartero_id =', (int) $cartero_id);
        }
        if ($sucursal_id) {
            $query = $this->db->where('hdr2.sucursal_id = ', (int) $sucursal_id);
        }
        if ($periodo_desde) {
            $date_desde = new DateTime($periodo_desde);
            $query      = $this->db->where('ci2.create >= ', $date_desde->format('Y-m-d 00:00:00'));
        }
        if ($periodo_hasta) {
            $date_hasta = new DateTime($periodo_hasta);
            $query      = $this->db->where('ci2.create <= ', $date_hasta->format('Y-m-d 23:59:59'));
        }
        $query = $this->db
            ->select('  c2.nombre cliente,
			sc2.apellido_nombre,
			sp2.pieza_id,
			s2.nombre servicio,
			hdr2.id hoja_ruta_id,
			p2.barcode_externo codigo_externo,
			DATE_FORMAT(hdr2.create, "%d-%m-%Y") fecha_creacion_hdr,
			DATE_FORMAT(ci2.create, "%d-%m-%Y") fecha_ingreso,
			ci2.numero,
			p2.destinatario, p2.domicilio, p2.localidad'
		    )
            ->join('flash_hojas_rutas hdr2', 'hdr2.id = sp2.hoja_ruta_id')
            ->join('flash_sucursales_carteros sc2', 'sc2.id = hdr2.cartero_id')
            ->join('flash_piezas p2', 'sp2.pieza_id = p2.id')
            ->join('flash_comprobantes_ingresos ci2', 'p2.comprobante_ingreso_id = ci2.id')
            ->join('flash_sucursales suc2', 'hdr2.sucursal_id = suc2.id')
            ->join('flash_clientes c2', 'c2.id = ci2.cliente_id')
            ->join('flash_comprobantes_ingresos_servicios cis2', 'cis2.id = p2.servicio_id')
            ->join('flash_servicios s2', 's2.id = cis2.servicio_id')
            ->join('flash_piezas_estados_variables ev2', 'ev2.id = sp2.pieza_estado_id')//porque busco las hdr pendientes independiente el estado actual de la pieza
            ->where('p2.tipo_id = ' . Pieza::TIPO_NORMAL)
            ->where('ev2.pieza_estado_id NOT IN ('.PiezaEstado::ESTADOS_RENDICIONES.','.PiezaEstado::ESTADOS_ORGANIZATIVOS.')')
            ->where('hdr2.estado NOT IN ('.Hoja::ESTADO_CANCELADA.')')
            ->where('s2.grupo_id <> 4') 
            ->order_by('sp2.pieza_id,sp2.id DESC')
            ->get('flash_subpiezas sp2');
            //echo($this->db->last_query());die;
            $consulta_interna = $this->db->last_query();
            $consulta_externa = "SELECT result.* 
				 FROM ( ".$consulta_interna ." ) result
	    			 GROUP BY result.pieza_id";
	    			// echo $consulta_externa;die;
        	$json_exe = $this->db->query($consulta_externa);
        	$json = $json_exe->result();
        
        if ($json) {
            echo json_encode(array('grilla' => $json));
        } else {
            echo json_encode(array('status' => 'none'));
        }

    }

    public function getHDRPendientesXClientes()
    {
        $periodo_desde = $this->input->post('periodo_desde') != '' ? $this->input->post('periodo_desde') : false;
        $periodo_hasta = $this->input->post('periodo_hasta') != '' ? $this->input->post('periodo_hasta') : false;
        $cliente_id    = $this->input->post('cliente_id') != '' ? $this->input->post('cliente_id') : false;
        $sucursal_id   = $this->input->post('sucursal_id') != '' ? $this->input->post('sucursal_id') : false;

        if ($cliente_id) {
            $query = $this->db->where('ci.cliente_id =', (int) $cliente_id);
        }
        if ($sucursal_id) {
             $query = $this->db->where('(p.sucursal_id = '.(int)$sucursal_id.' OR ci.sucursal_id = '. (int) $sucursal_id.')' );
                    //$query = $this->db->or_where('ci.sucursal_id = ', (int) $sucursal_id);
        }
        if ($periodo_desde) {
            $date_desde = new DateTime($periodo_desde);
            $query      = $this->db->where('ci.create >= ', $date_desde->format('Y-m-d 00:00:00'));
        }
        if ($periodo_hasta) {
            $date_hasta = new DateTime($periodo_hasta);
            $query      = $this->db->where('ci.create <= ', $date_hasta->format('Y-m-d 23:59:59'));
        }

        $query = $this->db
            ->select('suc.nombre sucursal, ci.cantidad cantidad,DATE_FORMAT(ci.create,"%d-%m-%Y") fecha_ingreso,
                                ci.numero, c.nombre cliente, s.nombre servicio, p.id pieza_id, p.destinatario,
                                p.domicilio, p.localidad, p.barcode_externo codigo_externo
                                ')
            ->join('flash_comprobantes_ingresos ci', 'p.comprobante_ingreso_id = ci.id')
            ->join('flash_comprobantes_ingresos_servicios cis', 'p.servicio_id = cis.id')
            ->join('flash_servicios s', 'cis.servicio_id = s.id')
            ->join('flash_piezas_estados_variables ev', 'p.estado_id = ev.id')
            ->join('flash_piezas_estados e', 'e.id = ev.pieza_estado_id')
            ->join('flash_clientes c', 'ci.cliente_id = c.id')
            ->join('flash_sucursales suc', 'ci.sucursal_id = suc.id')
            ->where('p.tipo_id = ' . Pieza::TIPO_NORMAL)
            ->where('e.id NOT IN (' . PiezaEstado::ESTADOS_RENDICIONES . ')')
            ->where('ev.`pieza_estado_id` <> '.PiezaEstado::ESTADOS_ORGANIZATIVOS)
            //->where('hdr.estado NOT IN ('.Hoja::ESTADO_CANCELADA.')')
            ->where('s.grupo_id <> 4') // Sin finishing
            ->get('flash_piezas p');
	//echo($this->db->last_query());die;
        $json = $query->result();

        if ($json) {
            echo json_encode(array('grilla' => $json));
        } else {
            echo json_encode(array('status' => 'none'));
        }

    }

    public function getHDRXDistribuidor($sin_grupo)
    {
        $periodo_desde = $this->input->post('periodo_desde') != '' ? $this->input->post('periodo_desde') : false;
        $periodo_hasta = $this->input->post('periodo_hasta') != '' ? $this->input->post('periodo_hasta') : false;
        $date_desde    = new DateTime($periodo_desde);
        $date_hasta    = new DateTime($periodo_hasta);

        if ($periodo_desde) {
            $query = $this->db->where('hdr.create >= ', $date_desde->format('Y-m-d 00:00:00'));
        }
        if ($periodo_hasta) {
            $query = $this->db->where('hdr.create <= ', $date_hasta->format('Y-m-d 23:59:59'));
        }
        if ($sin_grupo != null) {
            $query = $this->db->where(' s.grupo_id <> 4');//sin FINISHING
        }
        $query = $this->db
            ->select('hdr.id hoja_ruta_id,
                            d.apellido_nombre,
                            DATE_FORMAT(hdr.create,"%d-%m-%Y")    fecha_creacion,
                            COUNT(sp.hoja_ruta_id )    cantidad,
                            c.nombre cliente,
                            s.nombre servicio,
                            suc.nombre sucursal')
            ->join('flash_subpiezas sp', 'hdr.id = sp.hoja_ruta_id')
            ->join('flash_distribuidores d', 'hdr.distribuidor_id = d.id')
            ->join('flash_piezas p', 'sp.pieza_id = p.id')
            ->join('flash_comprobantes_ingresos_servicios cis', 'p.servicio_id = cis.id')
            ->join('flash_comprobantes_ingresos ci', 'ci.id = p.comprobante_ingreso_id')
            ->join('flash_servicios s', 's.id = cis.servicio_id')
            ->join('flash_clientes c', 'ci.cliente_id = c.id')
            ->join('flash_sucursales suc', 'hdr.sucursal_id = suc.id')
            ->where('hdr.distribuidor_id IS NOT NULL')
            ->group_by('hdr.id, hdr.distribuidor_id, cis.servicio_id, ci.cliente_id, p.sucursal_id')
            ->get('flash_hojas_rutas hdr');
        //echo $this->db->last_query();die;
        $json = $query->result();

        if ($json) {
            echo json_encode(array('grilla' => $json));
        } else {
            echo json_encode(array('status' => 'none'));
        }

    }

    public function getLiquidacionEdit()
    {
        $liquidacion_id = $this->input->post('liquidacion_id');

        $query = $this->db
            ->select('ld.*, ld.remito_cliente as remito, ld.comprobante_ingreso as numero, c.nombre')
            ->join('flash_liquidaciones l', 'l.id = ld.liquidacion_id')
            ->join('flash_clientes c', 'c.id = l.cliente_id')
            ->where('ld.liquidacion_id = ' . $liquidacion_id)
            ->get('flash_liquidaciones_detalles ld');

        $query_resumen = $this->db
            ->select('ld.servicio_id AS codigo, ld.servicio AS servicio, SUM(ld.cantidad) AS cantidad ')
            ->where('ld.liquidacion_id = ' . $liquidacion_id)
            ->group_by('ld.servicio_id')
            ->get('flash_liquidaciones_detalles ld');
//                echo $this->db->last_query();die;
        $json         = $query->result();
        $json_resumen = $query_resumen->result();
        if ($json) {
            echo json_encode(array('grilla' => $json, 'resumen' => $json_resumen));
        } else {
            echo json_encode(array('status' => 'none'));
        }

    }

    public function comprobanteExistente($numero)
    {

        $query = $this->db
            ->select(' id ')
            ->where('numero = "' . $numero . '"')
            ->get('flash_comprobantes_ingresos');

        $json = $query->result();
        if ($json) {
            echo json_encode($json);
        } else {
            echo json_encode(array('status' => 'El comprobante no existe'));
        }

    }

    public function getPiezasPendientesLiquidarXCliente()
    {
        $cliente_id = $this->input->post('cliente_id');

        $query = $this->db
            ->select('c.nombre,  ci.numero, COUNT(p.id) AS piezas ')
            ->join('flash_piezas p', 'p.comprobante_ingreso_id = ci.id')
            ->join('flash_clientes c', 'c.id = ci.cliente_id')
            ->where('liquidacion_id = 0')
            ->where('ci.cliente_id = ' . $cliente_id)
            ->group_by('ci.id')
            ->get('flash_comprobantes_ingresos ci');

        $json = $query->result();
        if ($json) {
            echo json_encode(array('grilla' => $json));
        } else {
            echo json_encode(array('status' => 'none'));
        }

    }

    public function getPiezasPendientesLiquidarXPeriodo()
    {
        $periodo_desde = $this->input->post('periodo_desde') != '' ? $this->input->post('periodo_desde') : false;
        $periodo_hasta = $this->input->post('periodo_hasta') != '' ? $this->input->post('periodo_hasta') : false;
        $date_desde    = new DateTime($periodo_desde);
        $date_hasta    = new DateTime($periodo_hasta);
        if ($periodo_desde) {
            $query = $this->db->where('ci.create >= ', $date_desde->format('Y-m-d'));
        }

        if ($periodo_hasta) {
            $query = $this->db->where('ci.create <= ', $date_hasta->format('Y-m-d'));
        }

        $query = $this->db
            ->select('c.nombre,  ci.numero, s.nombre AS servicio, ci.create AS fecha, COUNT(p.id) AS cantidad')
            ->join('flash_piezas p', 'p.comprobante_ingreso_id = ci.id')
            ->join('flash_comprobantes_ingresos_servicios cis', 'cis.comprobante_ingreso_id = ci.id')
            ->join('flash_servicios s', 's.id = cis.servicio_id')
            ->join('flash_clientes c', 'c.id = ci.cliente_id')
            ->where('liquidacion_id = 0')
            ->group_by('s.id')
            ->get('flash_comprobantes_ingresos ci');

        $json = $query->result();
        if ($json) {
            echo json_encode(array('grilla' => $json));
        } else {
            echo json_encode(array('status' => 'none'));
        }

    }

    public function getCarterosPendientesLiquidar()
    {
        $periodo_desde = $this->input->post('periodo_desde') != '' ? $this->input->post('periodo_desde') : false;
        $periodo_hasta = $this->input->post('periodo_hasta') != '' ? $this->input->post('periodo_hasta') : false;
        $sucursal_id   = $this->input->post('sucursal_id') != '' ? $this->input->post('sucursal_id') : false;
        $date_desde    = new DateTime($periodo_desde);
        $date_hasta    = new DateTime($periodo_hasta);
        if ($periodo_desde) {
            $query = $this->db->where('hdr.fecha_baja >= ', $date_desde->format('Y-m-d'));
        }

        if ($periodo_hasta) {
            $query = $this->db->where('hdr.fecha_baja <= ', $date_hasta->format('Y-m-d'));
        }

        if ($sucursal_id) {
            $query = $this->db->where('sc.sucursal_id = ', $sucursal_id);
        }

        $query = $this->db
            ->select('  hdr.id AS hdr_id,
                                DATE_FORMAT(ci.fecha_pedido,"%d-%m-%Y") AS fecha,
                                sp.pieza_id,
                                hdr.cartero_id AS cartero_id,
                                sc.apellido_nombre,
                                s.id AS codigo_servicio,
                                s.nombre AS servicio,
                                ci.cliente_id,
                                c.nombre,
                                pe.precio,
                                COUNT(sp.pieza_id) AS cantidad_piezas,
                                pe.precio as precio_cliente,
                                (COUNT(sp.pieza_id) * pe.precio) AS precio_cartero,
                                p.estado_id')
            ->join('flash_piezas p', 'p.id = sp.pieza_id')
            ->join('flash_hojas_rutas hdr', 'hdr.id = sp.hoja_ruta_id')
            ->join('flash_comprobantes_ingresos ci', 'ci.id = p.comprobante_ingreso_id')
            ->join('flash_comprobantes_ingresos_servicios cis', 'cis.id = p.servicio_id AND ci.id = cis.comprobante_ingreso_id')
            ->join('flash_servicios s', 's.id = cis.servicio_id')
            ->join('flash_clientes c', 'c.id = ci.cliente_id')
            ->join('flash_clientes_precios_especiales pe', 'pe.servicio_id = cis.servicio_id AND pe.cliente_id = ci.cliente_id')
            ->join('flash_sucursales_carteros sc', 'sc.id = hdr.cartero_id')
            ->where('hdr.fecha_baja IS NOT NULL')
            ->group_by('hdr.cartero_id, p.servicio_id,  hdr.id')
            ->order_by('hdr.id')
            ->get('flash_subpiezas sp');

        $json = $query->result();
        if ($json) {
            echo json_encode(array('grilla' => $json));
        } else {
            echo json_encode(array('status' => 'none'));
        }

    }

    public function serviciosPorComprobante($numero)
    {

        $query = $this->db
            ->select('cis.id, s.nombre')
            ->join('flash_comprobantes_ingresos_servicios cis', 'ci.id = cis.comprobante_ingreso_id')
            ->join('flash_servicios s', 'cis.servicio_id = s.id')
            ->where('ci.numero = ' . $numero)
            ->get('flash_comprobantes_ingresos ci');
        $json = $query->result();
//            echo $this->db->last_query();die;
        if ($json) {
            echo json_encode($json);
        } else {
            echo json_encode(array('status' => 'none'));
        }

    }

    public function getCantidadPiezasSimplesPorHDR($numero, $hdr_id, $servicio_id)
    {

        $query = $this->db
            ->select('COUNT(p.id) cantidad')
            ->join('flash_subpiezas sp', 'hdr.id = sp.hoja_ruta_id')
            ->join('flash_piezas p', 'sp.pieza_id = p.id')
            ->join('flash_comprobantes_ingresos ci', 'p.comprobante_ingreso_id = ci.id')
            ->where('p.tipo_id = 1')
            ->where('ci.numero = ' . $numero)
            ->where('p.servicio_id = ' . $servicio_id)
            ->where('sp.hoja_ruta_id = ' . $hdr_id)
            ->get('flash_hojas_rutas hdr');
        $json = $query->result();
//            echo $this->db->last_query();die;
        if ($json) {
            echo json_encode($json);
        } else {
            echo json_encode(array('status' => 'none'));
        }

    }

    public function traerPiezasIguales()
    {
        $codigo_barra     = (int) $this->input->post('codigo_barra');
        $codigo_barra_str = '"' . $this->input->post('codigo_barra') . '"';

        $user_row = $this->ion_auth->user()->row();
        if (!$this->ion_auth->in_group(array(1, 2), $user_row->id)) {
            $query = $this->db->where('p.sucursal_id', $user_row->sucursal_id);
        }

        $query = $this->db
            ->select('ci.numero numero, p.barcode_externo,  p.id pieza_id')
            ->join('flash_comprobantes_ingresos ci', 'p.comprobante_ingreso_id = ci.id')
        //->where('p.barcode_externo <> ""')
            ->where('p.id =' . $codigo_barra)
            ->group_by('ci.id')
            ->get('flash_piezas p');

        $json = $query->result();
        if ($json) {
            echo json_encode(array('grilla' => $json));
        } else {
            if (!$this->ion_auth->in_group(array(1, 2), $user_row->id)) {
                $query = $this->db->where('p.sucursal_id', $user_row->sucursal_id);
            }

            $query = $this->db
                ->select('ci.numero numero, p.barcode_externo,  p.id pieza_id')
                ->join('flash_comprobantes_ingresos ci', 'p.comprobante_ingreso_id = ci.id')
                ->where('p.barcode_externo <> ""')
                ->where('p.barcode_externo =' . $codigo_barra_str)
                ->group_by('ci.id')
                ->get('flash_piezas p');

            $json = $query->result();
            if ($json) {
                echo json_encode(array('grilla' => $json));
            } else {
                echo json_encode(array('status' => 'La pieza no se encuentra disponible'));
            }

        }
//            echo $this->db->last_query();die;

    }

        public function add_piezas_simples(){
            //Si la HDR no existe Grabo encabezado
            $hoja = Hoja::whereId($this->input->post('hdr_id'))->first();
            //echo $hoja->id;DIE;
            if ($hoja->id == ''){
                $data = array(
                    'observaciones' => $this->input->post('observaciones'),
                    'fecha_entrega' => formatdate($this->input->post('fecha_entrega')),
                    'piezas' => $this->input->post('cantidad'),
                );
                if($this->input->post('cartero_id'))
                {
                    $data['cartero_id'] = $this->input->post('cartero_id');
                    $data['zona_id'] = $this->input->post('zona_id');
                }
                else
                {
                    $data['distribuidor_id'] = $this->input->post('distribuidor_id');
                    $data['transporte_id'] = $this->input->post('transporte_id');
                }
                $user_row = $this->ion_auth->user()->row();
                $data['estado'] = Hoja::ESTADO_INICIADA;
                $data['sucursal_id'] = $user_row->sucursal_id;
                $data['create_user_id'] = $user_row->id;
                $data['create'] = date("Y-m-d H:i:s");
                $hoja = Hoja::create($data);
            }
            
            //Con la HDR creada comienzo a grabar las piezas en la tabla FLASH_SUBPIEZAS
            $comprobante_ingreso_numero =  $this->input->post('codigo_barra_simple');
            $comprobante_ingreso = Comprobante::whereNumero($comprobante_ingreso_numero)->first();
            $servicio_id = $this->input->post('servicio_id');
            $cantidad = $this->input->post('cantidad');
            //Agrego la sucursal del usuario porque si bien muestra la cantidad disponible de piezas simples para agregar a una
            //HDR , esta tomando cualquier pieza SIMPLE de un SERVICIO específico de un CI específico, pero de cualquier SUCURSAL
            $user_row = $this->ion_auth->user()->row();
            $sucursal_id = $user_row->sucursal_id;
            $sql = " INSERT  INTO flash_subpiezas sp (sp.hoja_ruta_id, sp.pieza_id, sp.liquidacion_id, sp.cantidad, sp.pieza_estado_id, sp.create_user_id, sp.create) 
                    SELECT 
                            $hoja->id as hoja_ruta_id, p.id as pieza_id, 0, 1, ".Pieza::ESTADO_EN_DISTRIBUCION." , ".$user_row->id." ,CURRENT_TIMESTAMP()
                     FROM `flash_piezas` `p`
                    INNER JOIN flash_piezas_estados_variables ev on p.estado_id = ev.id
                    WHERE  ev.pieza_estado_id IN (".PiezaEstado::ESTADOS_INICIALES.",".PiezaEstado::ESTADOS_ORGANIZATIVOS.")
                     AND p.tipo_id = ".Pieza::TIPO_SIMPLE." 
                     AND p.servicio_id = ".$servicio_id." 
                     AND p.comprobante_ingreso_id = ".$comprobante_ingreso->id."
                     AND p.sucursal_id = ".$sucursal_id." 
                     LIMIT ".$cantidad;
            //Ejecuto la QUERY de INSERCION
            $query = $this->db->query($sql);
            
            //Cambio el estado de las piezas en la hdr a EN DISTRIBUCION
            $sql_update = "  UPDATE flash_piezas p
                                INNER JOIN flash_subpiezas sp ON p.id = sp.pieza_id
                                INNER JOIN flash_hojas_rutas hdr ON sp.hoja_ruta_id = hdr.id
                                SET p.estado_id = ".Pieza::ESTADO_EN_DISTRIBUCION.",
                                    p.hoja_ruta_id = ".  $hoja->id.",
                                    p.update_user_id = ". $user_row->id .",
                                    p.update = CURRENT_TIMESTAMP()
                                  WHERE p.tipo_id = ".Pieza::TIPO_SIMPLE."
                                   AND p.servicio_id = ".$servicio_id."
                                   AND hdr.id = ".  $hoja->id." 
                                   AND p.sucursal_id = ".$sucursal_id;

            //Ejecuto la query de update
            $query = $this->db->query($sql_update);
            //Traigo los datos de la HDR guardada
            $query_hdr = 'SELECT hdr.id hdr_id, "---" piezas, ci.cliente_id, c.nombre cliente, cis.id servicio_id, s.nombre servicio, COUNT(*) piezas, pt.nombre tipo_pieza
                            FROM flash_hojas_rutas hdr
                            INNER JOIN flash_subpiezas sp ON sp.hoja_ruta_id = hdr.id
                            INNER JOIN flash_piezas p ON p.id = sp.pieza_id
                            INNER JOIN flash_comprobantes_ingresos ci ON p.comprobante_ingreso_id = ci.id
                            INNER JOIN flash_clientes c ON ci.cliente_id = c.id
                            INNER JOIN flash_comprobantes_ingresos_servicios cis ON p.servicio_id = cis.id
                            INNER JOIN flash_servicios s ON cis.servicio_id = s.id
                            INNER JOIN flash_piezas_tipos pt ON p.tipo_id = pt.id
                            WHERE p.tipo_id = '.Pieza::TIPO_SIMPLE.'  
                              AND p.servicio_id = '.$servicio_id.' 
                              AND hdr.id = '.$hoja->id.' 
                              AND p.comprobante_ingreso_id = '.$comprobante_ingreso->id.' 
                               AND p.sucursal_id = '.$sucursal_id.' 
                             GROUP BY p.comprobante_ingreso_id';
            //echo $query_hdr;die;
            $json = $this->db->query($query_hdr);
            /* Auditoria */
            $user_row = $this->ion_auth->user()->row();
            $data = array(
                'user_id' => $user_row->id,
                'categoria' => 'HOJAS DE RUTAS',
                'descripcion' => 'Hoja de Ruta: ' . $hoja->id . '. Añadir Piezas Simples a Hoja de Ruta.',
                'origen' => '',
                'destino' => '',
            );
            $this->codegen_model->add('users_log',$data);
            /* END: Auditoria */
           echo json_encode($json->result());
        }

    public function delete_piezas_simples()
    {
    
        $servicio_id  = $this->input->post('servicio_id');
        $hoja_ruta_id = $this->input->post('hoja_ruta_id');
        $cantidad     = $this->input->post('cantidad');
        $user_row     = $this->ion_auth->user()->row();
        $sucursal_id  = $user_row->sucursal_id;

        $sql_update_estado_piezas = "  UPDATE flash_piezas p
                                            INNER JOIN flash_subpiezas sp ON p.id = sp.pieza_id
                                            INNER JOIN flash_hojas_rutas hdr ON sp.hoja_ruta_id = hdr.id
                                            SET estado_id = " . Pieza::ESTADO_EN_GESTION . ",
                                                p.update_user_id = ". $user_row->id .",
                                                p.update = CURRENT_TIMESTAMP()
                                             WHERE p.tipo_id = " . Pieza::TIPO_SIMPLE . "
                                              AND p.servicio_id = " . $servicio_id . "
                                              AND hdr.id = " . $hoja_ruta_id . "
                                              AND p.sucursal_id = " . $sucursal_id;
        //echo $sql_update_estado_piezas;die;
        $update = $this->db->query($sql_update_estado_piezas);

        $sql = "   DELETE sp FROM flash_subpiezas sp
                        INNER JOIN flash_hojas_rutas hdr ON sp.hoja_ruta_id = hdr.id
                        INNER JOIN flash_piezas p ON p.id = sp.pieza_id
                        WHERE p.tipo_id = " . Pieza::TIPO_SIMPLE . "
                         AND p.servicio_id = " . $servicio_id . "
                         AND hdr.id = " . $hoja_ruta_id . "
                         AND p.sucursal_id = " . $sucursal_id;

        $data = array(
            'user_id'  => $user_row->id,
            'consulta' => $sql,
            'fecha'    => date('Y-m-d H:i:s'));
        $this->codegen_model->add('auditoria', $data);

        /* Auditoria */
        $user_row = $this->ion_auth->user()->row();
        $data = array(
            'user_id' => $user_row->id,
            'categoria' => 'HOJAS DE RUTAS',
            'descripcion' => 'Hoja de Ruta: ' . $hoja_ruta_id . '. Eliminar Piezas Simples a Hoja de Ruta.',
            'origen' => '',
            'destino' => '',
        );
        $this->codegen_model->add('users_log',$data);
        /* END: Auditoria */


        $respuesta = $this->db->query($sql);
        echo json_encode(array("respuesta" => $this->db->affected_rows())); //true: exito, flase: error
    }

    public function getPiezaNormal()
    {
        $codigo          = (int) $this->input->post('codigo_barra');
        $codigo_str      = '"' . $this->input->post('codigo_barra') . '"';
        $piezas_cargadas = $this->input->post('piezas_ids');
        $buscar_por      = $this->input->post('buscar_por');
        $user_row = $this->ion_auth->user()->row();
        //var_dump($_POST);die;
        //Traigo los datos de la pieza
        if (intval($buscar_por) == 1) {
            $pieza = $this->codegen_model->get('flash_piezas', 'id, estado_id', 'id = ' . $codigo.' AND sucursal_id = '.$user_row->sucursal_id);
           
        }

        if (intval($buscar_por) == 2) {
            $pieza = $this->codegen_model->get('flash_piezas', 'id, estado_id', 'barcode_externo = ' . $codigo_str.' AND sucursal_id = '.$user_row->sucursal_id);
        }

        //Si la consulta trae mas de un ID de pieza solicita que ingrese el ID de la pieza
        if (count($pieza) > 1){
            echo json_encode(array('status' => 'Existen '.count($pieza)." piezas con el mismo CODIGO de BARRAS"));
            return;
        };
        //echo json_encode(array('respuesta' => var_dump($pieza)));
        if ($pieza == null) {
            echo json_encode(array('status' => 'La pieza no esta disponible'));
            return;
        }
        
        if (count($pieza) == 1){
            $pieza = $pieza[0];
        };

        // load URL helper //Chequeo que la pieza este en un edtado condicional
        $this->load->helper('novedades');
        $respuesta = validar_HDR($pieza->estado_id,PIEZA_ESTADO_EN_DISTRIBUCION);
        
        if($respuesta != ""){echo json_encode(array('respuesta' => $respuesta));return;}
        
        //Chequeo si la pieza ya fue agregada a la HDR
        $piezas_cargadas_array = explode(',', $piezas_cargadas);
        if (in_array($pieza->id, $piezas_cargadas_array)) {echo json_encode(array('status' => 'La pieza ya fue agregada'));return;}

        //Agrego la comprobacion para ver si la pieza esta fisicamente en la sucursal del uusuario
        $user_row = $this->ion_auth->user()->row();
        $query    = $this->db->where('p.sucursal_id', $user_row->sucursal_id);

        $query = $this->db
            ->select('p.barcode_externo barcode_externo,p.id pieza_id, s.nombre servicio, p.domicilio domicilio, p.codigo_postal codigo_postal,p.localidad, p.destinatario, c.nombre cliente')
            ->join('flash_comprobantes_ingresos ci', 'ci.id = p.comprobante_ingreso_id')
            ->join('flash_clientes c', 'c.id = ci.cliente_id')
            ->join('flash_comprobantes_ingresos_servicios cis', 'cis.id = p.servicio_id')
            ->join('flash_servicios s', 'cis.servicio_id = s.id')
            ->join('flash_piezas_estados_variables ev', 'p.estado_id = ev.id')
            ->where('ev.pieza_estado_id IN (' . PiezaEstado::ESTADOS_INICIALES . ',' . PiezaEstado::ESTADOS_ORGANIZATIVOS . ')')
            ->where('p.id = ' . $pieza->id)
            ->get('flash_piezas p');

        $json = $query->result();
        if ($json) {
            array_push($json, $this->add_pieza_normal($_POST, $pieza->id));

            echo json_encode($json);
        } else {
            echo json_encode(array('status' => 'La pieza no esta disponible!'));
        }
    }

        function add_pieza_normal($post, $pieza_id){
            $hoja_ruta_id = $post['hdr_id']!= ''?$post['hdr_id']:FALSE;
            $user_row = $this->ion_auth->user()->row();
            if (!$hoja_ruta_id){
                //Grabo encabezado
                $data = array(
                            'observaciones' => $this->input->post('observaciones'),
                            'fecha_entrega' => formatdate($this->input->post('fecha_entrega')),
                            'piezas' => $this->input->post('cantidad'),
                            
                        );
                        if($this->input->post('cartero_id'))
                        {
                            $data['cartero_id'] = $this->input->post('cartero_id');
                            $data['zona_id'] = $this->input->post('zona_id');
                        }
                        else
                        {
                            $data['distribuidor_id'] = $this->input->post('distribuidor_id');
                            $data['transporte_id'] = $this->input->post('transporte_id');
                        }
                        
                        $data['estado'] = Hoja::ESTADO_INICIADA;
                        $data['sucursal_id'] = $user_row->sucursal_id;
                        $data['create_user_id'] = $user_row->id;
                        $data['create'] = date("Y-m-d H:i:s");
                        //var_dump($data);die;
                        $hoja = Hoja::create($data);
                        //echo($this->db->last_query());die;

            }else{
                $hoja = Hoja::whereId($hoja_ruta_id)->first();
                $hoja->update_user_id  = $user_row->id;
                $hoja->update  = date("Y-m-d H:i:s");
                $hoja->save();
            }
            //Grabo la pieza Normal dentro de la HDR
            $estado_id = Pieza::ESTADO_EN_DISTRIBUCION;
            $data = array(
                "hoja_ruta_id" => $hoja->id,
                "pieza_id"  => $pieza_id,
                "cantidad" => 1,
                "pieza_estado_id" => $estado_id,
                "create_user_id" => $user_row->id,
                "create" => date("Y-m-d H:i:s")
            );
            
            $subpiezas = $this->codegen_model->add('flash_subpiezas',$data);
            //Actualizo el estado de la tabla PIEZAS
            $data_update_piezas = array(
                "estado_id"  => $estado_id,
                "hoja_ruta_id" => $hoja->id,
                "update_user_id" => $user_row->id,
                "update" => date("Y-m-d H:i:s")
            );
            
            //Actualizo Novedades
            $update_novedades = "   UPDATE flash_piezas_novedades n
                                    INNER JOIN flash_piezas p ON p.id = n.pieza_id
                                    SET estado_actual_id = p.estado_id,
                                        estado_nuevo_id = " . $estado_id . ",
                                            n.update = '" . date("Y-m-d H:i:s") . "'
                                             n.update_user_id = ".$user_row->id."
                                              WHERE pieza_id = " . $pieza_id;
            
            $this->db->query($update_novedades);
            $this->codegen_model->edit('flash_piezas',$data_update_piezas,'id',$pieza_id);
            
            //Actualizo Tracking
            $array_piezas_tracking[]     = array(
                'pieza_id'              => $pieza_id,
                'usuario_id'            => $user_row->id,
                'estado_id'             => $estado_id, 
                'create'                => date("Y-m-d H:i:s"),
                'update'                => date("Y-m-d H:i:s"),
                'create_user_id'        =>  $user_row->id,
                'update_user_id'         => $user_row->id,
            );
            $this->db->insert_batch('flash_piezas_tracking', $array_piezas_tracking);

            $Data = array('codigo_barra' => $pieza_id,'estado_id' => $estado_id,'buscar_por' => 1,'UserId' => $user_row->id);// Modo Test Sispo , 'Sispo' => 'Sispo'
    		//print_r($Data);
    		$PHPRespuesta = $this->CURL("POST", "http://sistema.sppflash.com.ar" ."/api/flame/ApisExternas/AjaxAdministrarEstadosDeSispo.php", $Data);//laravelapis.sppflash.com.ar
            //print_r($PHPRespuesta);

            return $hoja->id;


        }

        function CURL($method,$url, $data = null, $Bearer = 'abcd') {
    		$ch = curl_init();
    		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    		$postjson = json_encode($data);
    		if(!empty($data)) {
    			switch ($method) {
    				case "POST":
    					curl_setopt($ch, CURLOPT_POST, true);
    					if ($data){
    						curl_setopt($ch, CURLOPT_POSTFIELDS, $postjson);
    					}
    					break;
    				case "PUT":
    					curl_setopt($ch, CURLOPT_PUT, 1);
    					break;
    				default:
    					if ($data){
    						$url = sprintf("%s?%s", $url, http_build_query($data));
    					}
    			}
    			curl_setopt($ch, CURLOPT_URL, $url);
    			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    			$authorization = "Authorization: Bearer abcd"; // Prepare the authorisation token
    			curl_setopt($ch, CURLOPT_HTTPHEADER , array("cache-control: no-cache", $authorization));
    		} else{
    		}
    		$result = curl_exec($ch);
    		$DataResultado = curl_getinfo($ch);
    		$ResultadoDecode = json_decode($result, true);
    		if($ResultadoDecode){
    			$Respuesta = array_merge($DataResultado,json_decode($result, true));
    		}else{
    			$result = json_encode(array('json-data' => false));
    			$Respuesta = array_merge($DataResultado,json_decode($result, true));
    		}
    		if( $Respuesta["http_code"] == 200 ){
    			curl_close($ch);
    			return $Respuesta;
    		}else{
    			curl_close($ch);
    			return $Respuesta;
    		}
    		
    	}
    	
        public function delete_pieza_normal(){
            $pieza_id = $this->input->post('pieza_id');
            $user_row = $this->ion_auth->user()->row();
            $data = array(
                'user_id' => $user_row->id,
               'consulta'=> "Ajax hdr delete_pieza_normal: DELETE FROM flash_subpiezas WHERE pieza_id = ".$pieza_id,
                'fecha' => date('Y-m-d H:i:s'));
            $this->codegen_model->add('auditoria', $data);
            $this->codegen_model->delete('flash_subpiezas','pieza_id',$pieza_id);  

            //Para dejar el estado de la pieza como estaba antes de borrarla consulto tracking
            $query_tracking =  $this->db
            ->select(' estado_id, id ')
            ->where( " pieza_id = ".$pieza_id)
            ->order_by( " id DESC ")
            ->limit(3)
            ->get('flash_piezas_tracking')->result_array();
          
        if (count($query_tracking)==1){ return json_encode(array("respuesta" => "El tracking tinene un solo estado"));}
        
        if (count($query_tracking)==2){
            $estado_actual_id = $query_tracking[1]['estado_id'];
            $estado_nuevo_id = $query_tracking[1]['estado_id'];
            $tracking_id = $query_tracking[0]['id'];
        }else{
            if(count($query_tracking)>2){
                $estado_actual_id = $query_tracking[2]['estado_id'];
                $estado_nuevo_id = $query_tracking[1]['estado_id'];
                $tracking_id = $query_tracking[0]['id'];
            }
        }
        $data_update_piezas = array(
            "estado_id"  => $estado_nuevo_id,
            "update_user_id" => $user_row->id,
            "update" => date("Y-m-d H:i:s")
            // "hoja_ruta_id" => 0,
        );
        //Actualizo el estado en piezas
        $this->codegen_model->edit('flash_piezas',$data_update_piezas,'id',$pieza_id);
        //Actualizo el estado en novedades
        $update_novedades = " UPDATE flash_piezas_novedades n
                                        INNER JOIN flash_piezas p ON p.id = n.pieza_id
                                        SET estado_nuevo_id = p.estado_id,
                                            estado_actual_id = " . intval($estado_actual_id) . ",
                                                n.update = '" . date("Y-m-d H:i:s") . "'
                                                    n.update_user_id = ".$user_row->id."
                                                        WHERE pieza_id = " . $pieza_id;
        $this->db->query($update_novedades); 
        //Elimino el ultimo tracking creado
        $delete_tracking = " DELETE FROM flash_piezas_tracking WHERE id = ".$tracking_id;
        $this->db->query($delete_tracking);  

            echo json_encode(array("respuesta" => $this->db->affected_rows())); //true: exito, flase: error
        }

    public function traerPiezasSimplesPorComprobanteEstadosYServicio()
    {
        ini_set('memory_limit', '-1');
        $codigo_barras_id = $this->input->post('codigo_barra');
        $cantidad         = $this->input->post('cantidad');
        //$estados_id = $this->input->post('estados_id');
        $comprobante_servicio_id = $this->input->post('servicio_id');

        //Traigo el CI_id
        $comprobante_ingreso_id = Comprobante::whereNumero($codigo_barras_id)->first();
        //Solo se trae las piezas que esten en la sucursal fisicamente
        $user_row = $this->ion_auth->user()->row();
        $query    = $this->db->where('p.sucursal_id', $user_row->sucursal_id);

        $query = $this->db
            ->select(' p.barcode, p.id, p.destinatario, p.domicilio, p.localidad, p.codigo_postal, s.nombre as servicio, p.barcode_externo')
            ->join('flash_comprobantes_ingresos_servicios cis', 'p.servicio_id = cis.id')
            ->join('flash_comprobantes_ingresos ci', 'ci.id = cis.comprobante_ingreso_id')
            ->join('flash_servicios s', 's.id = cis.servicio_id')
            ->join('flash_piezas_estados_variables ev', 'ev.id = p.estado_id')
            ->where('ci.id = ' . $comprobante_ingreso_id->id)
            ->where('cis.id = ' . $comprobante_servicio_id)
            ->where('ev.pieza_estado_id IN (' . PiezaEstado::ESTADOS_INICIALES . ',' . PiezaEstado::ESTADOS_ORGANIZATIVOS . ')')
            ->where('p.tipo_id = ' . Pieza::TIPO_SIMPLE)
            ->limit($cantidad)
            ->get('flash_piezas p');
        $json = $query->result();
        //echo $this->db->last_query();die;
        if ($json) {
            echo json_encode($json);
        } else {
            echo json_encode(array('status' => 'none'));
        }

    }

    public function analizarCSV()
    {  // echo "En analisis...<br>";
    
    
        $user_row = $this->ion_auth->user()->row();
        ini_set('memory_limit','-1');
	    set_time_limit(1800);
    	if (count($_FILES) == 0) {
            echo json_encode(array('error' => ' Seleccione el archivo a cargar con el botón BROWSE.'));return;
        }
        $hdr_id = $this->input->post('hdr_id');

        $path = "";
        if ($_SERVER['HTTP_HOST'] == "localhost") {
// For local
            $path = $_SERVER['DOCUMENT_ROOT'] . 'gestionpostal/assets/upload/';
        } else {
            // For Web
            $path = $_SERVER['DOCUMENT_ROOT'] . '/assets/upload/';
        }
        //Anulo el if anterior
 $path = $_SERVER['DOCUMENT_ROOT'] . '/assets/upload/';

        $file_name               = 'hojas_rutas' . date('Y_m_d_His') . '.csv';
        $config['upload_path']   = $path;
        $config['allowed_types'] = 'csv';
        $config['max_size']      = 1024;
        $config['max_width']     = 1024;
        $config['max_height']    = 1024;
        $config['enclosure']     = ';';

        $this->load->library('upload', $config);
        //Chequeo que tenga extension CSV
        $filename_aux = $_FILES['file']['tmp_name'];
        //Copio el archivo en el direcorio de destino con el nombre que quiero
        move_uploaded_file($_FILES['file']['tmp_name'], $path . $file_name); //base_url().'assets/upload/piezas_fechayHora.csv';
        //echo  $path . $file_name;die;
        $file = fopen($path . $file_name, "r");
        
        $cantidad_piezas = 0;
        $piezas          = null;
        $array_temp      = fgetcsv($file, 0, ";");
        $info['piezas_leidas'] = count($array_temp);

        fclose($file);
        $respuesta = null;
        //Transformo los codigos externos en un string separados por ; y encerrados entre ("")
        //var_dump($array_temp);die;
        $barcode_externos =  '"'. implode("\",\"", $array_temp).'"';
        //echo $barcode_externos;die;
        //Traigo los ids de todos esos codigos externos desde la tabla FLASH_PIEZAS
        $sql_piezas_ids = " SELECT p.id FROM flash_piezas p
                             INNER JOIN flash_piezas_estados_variables ev on p.estado_id = ev.id
                              WHERE barcode_externo IN ($barcode_externos) 
                               AND p.sucursal_id = $user_row->sucursal_id
                                AND ev.pieza_estado_id IN (" . PiezaEstado::ESTADOS_INICIALES . "," . PiezaEstado::ESTADOS_ORGANIZATIVOS . ")";
                         //   echo $sql_piezas_ids;die;
        $results_piezas_ids = $this->db->query($sql_piezas_ids);
        $piezas_ids = $results_piezas_ids->result();
         
        $cantidad_piezas = count($piezas_ids);
        $info['cantidad_piezas'] = $cantidad_piezas;
        if ($cantidad_piezas > 0) {
            if (count($piezas_ids) <= 2000) {
                //chequear si hay piezas asignadas a otra/s HDR QUE TODAVIA ESTEN EN ESTADO INICIAL PORQUE SI ESTAN EN ORGANIZATIVO SI SE PUEDE
                //ASIGNAR A OTRA HDR
                $query = "  SELECT sp.hoja_ruta_id, p.barcode_externo barcode_externo, ev.nombre estado_pieza,
                                (CASE WHEN hdr.estado = " . Hoja::ESTADO_INICIADA . " THEN 'INICIADA'
                                      WHEN hdr.estado = " . Hoja::ESTADO_CERRADA . " THEN 'CERRADA'
                                      WHEN hdr.estado = " . Hoja::ESTADO_BAJA . " THEN 'BAJA'
                                        WHEN hdr.estado = " . Hoja::ESTADO_ARCHIVADA . " THEN 'ARCHIVADA'
                                        WHEN hdr.estado = " . Hoja::ESTADO_CANCELADA . " THEN 'CANCELADA'
                                 END) AS estado_hdr
                                FROM flash_subpiezas sp
                                inner join flash_hojas_rutas hdr on sp.hoja_ruta_id = hdr.id
                                INNER JOIN flash_piezas p ON p.id = sp.pieza_id
                                INNER JOIN flash_piezas_estados_variables ev on p.estado_id = ev.id
                                WHERE sp.pieza_id IN (SELECT id
                                                   FROM flash_piezas
                                                   WHERE barcode_externo IN ($barcode_externos)
                                                    AND sucursal_id = $user_row->sucursal_id )
                                AND ev.pieza_estado_id IN (" . PiezaEstado::ESTADOS_DESPACHO . "," . PiezaEstado::ESTADOS_RENDICIONES . ")";
//                echo $query;die;
                $respuesta = $this->db->query($query);
                if (count($respuesta->result()) == 0) {
                    //Agrego los datos a la HDR seleccionada
                    echo json_encode(array("mensaje" => "OK",
                        "path"                           => $path . $file_name,
                        "info"                           => $info)
                    );
                } else {
                    echo json_encode(array('repetidos'=>$respuesta->result(),'info'=>$info));
                }
            } else {
                echo json_encode(array("mensaje" => "La cantidad de datos supera el número máximo de 2000",'info'=>$info));
            }
        } else {
            echo json_encode(array("mensaje" => "El archivo no tiene datos o el formato no es el correcto",'info'=>$info));
        }
    }
}

/* End of file Piezas.php */
/* Location: ./system/application/controllers/Piezas.php */
