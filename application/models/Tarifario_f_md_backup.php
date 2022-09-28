<?php 

class Tarifario_f_md extends CI_Model {

    function getTarifarios($buscar = null){
        /*
        SELECT 
        T.id, C.id AS idCliente, C.nombre AS nombreCliente, E.id AS idEsquema, E.nombre AS nombreEsquema
        FROM flash_tarifario T 
        INNER JOIN flash_clientes C ON C.id = T.cliente_id
        INNER JOIN flash_esquema E ON E.id = T.esquema_id
        */

        if(is_null($buscar) || $buscar == ''){
            $query = $this->db
                        ->select('T.id, C.id AS idCliente, C.nombre AS nombreCliente, E.id AS idEsquema, E.nombre AS nombreEsquema')
                        ->join('flash_clientes C', 'C.id = T.cliente_id', 'inner')
                        ->join('flash_esquema E', 'E.id = T.esquema_id', 'inner')
                        ->get('flash_tarifario T');
            
            return $query->result();
        }

        $query = $this->db
                    ->select('T.id, C.id AS idCliente, C.nombre AS nombreCliente, E.id AS idEsquema, E.nombre AS nombreEsquema')
                    ->join('flash_clientes C', 'C.id = T.cliente_id', 'inner')
                    ->join('flash_esquema E', 'E.id = T.esquema_id', 'inner')
                    ->like('C.nombre', $buscar)
                    ->or_like('E.nombre', $buscar)
                    ->get('flash_tarifario T');
            
        return $query->result();
    }

    function validarQueElclienteNoTengaEsquemaPrevio($cliente){
        
        $query = $this->db
                        ->select('count(*) cantidad')
                        ->where('cliente_id', $cliente)
                        ->get('flash_tarifario T');

        return $query->row();
        
    }
    
    
    
    /**
	 * Obtiene el precio de un paquete dado el tarifario personalizado de un cliente
	 *
	 * @param [type] $idCliente
	 * @param [type] $paquete [$zonaA,$zonaB,$peso,...] fila de la tabla piezas_paquetes
	 * @return void
	 */
	function getPrecioPaquete($cliente_id, $paquete){
		bcscale(2);
		$precio = 0;
		
		 $esquema = $this->getConfiguracionTarifarioPorZona($cliente_id, $paquete['zonaA'], $paquete['zonaB']);
		
		//METROS CUBICOS
        if ($esquema[0]['metrosCubicos'] && $paquete['metro_cubico']) {
            //OBTENGO EL TARIFARIO DE METROS CUBICOS PARA EL CLIENTE 
            $precio = $this->getPrecioPorMetrosCubicos($cliente_id, $esquema, $paquete, $precio);
        
        //KILOS
        }else if ($esquema[0]['kilos'] && $paquete['peso']) {
            //oBTENGO EL TARIFARIO DE KILOS PARA EL CLIENTE
            $precio = $this->getPrecioPorKilos($cliente_id, $esquema, $paquete, $precio);
        }

        //BULTOS
        if ($esquema[0]['bultos'] && $paquete['bultos']) {
            //OBTENGO EL TARIFARIO DE BULTOS PARA EL CLIENTE
            $precio = $this->getPrecioPorBultos($cliente_id, $esquema, $paquete, $precio);
        }
        
        //PALETS
        if ($esquema[0]['palets'] && $paquete['palets']) {
            //OBTENGO EL TARIFARIO DE PALETS PARA EL CLIENTE 
            $precio = $this->getPrecioPorPalets($cliente_id, $esquema, $paquete, $precio);
        }

		return $precio;

	}
    
    function getPrecioPorKilos($cliente_id, $esquema, $paquete, $precio){
        //OBTENGO EL TARIFARIO DE KILOS PARA EL CLIENTE 
        
        $kilos = $this->getKilos($cliente_id, $paquete['zonaA'], $paquete['zonaB']);
        
        $peso = $paquete['peso'];
        
        if($esquema[0]['pesoAforado']){
            $aforo = $this->getAforo($cliente_id, $paquete["zonaA"], $paquete["zonaB"]);

            if(!empty($aforo->id)){
                list($alto, $largo, $ancho) = explode('x', strtolower($paquete['dimensiones']));
                //$m3 = ($alto * $largo * $ancho) / 1000000;
		        //$pesoAforado = $m3 / $aforo->valor;
		        
		        $dimensiones = ($alto * $largo * $ancho);
		        $pesoAforado = bcdiv( $dimensiones, $aforo->valor );
		        
		        //INSERTAR EL PESO VOLUMETRICO EN LA TABLA
		        $this->insertarPesoVolumetrico($pesoAforado, $paquete['pieza_id']);
		        
		        $peso = $pesoAforado > $paquete['peso'] ? $pesoAforado : $paquete['peso'];
		        
            }
            
        }
            
        foreach ($kilos as $t) {
            //BANDERA 1 = BLOQUE
            //BANDERA 2 = UNIDAD
            $desde = floatval($t->desde);
            $hasta = floatval($t->hasta);
            $bandera = intval($t->bandera);
            $prec = floatval($t->precio);
            $peso = floatval($peso);
            
			if ($bandera == 1 && $peso >= $desde && $peso <= $hasta) {
				$precio = bcadd($precio, $prec);
			}
			
			if ($bandera == 2 && $peso >= $desde && $peso <= $hasta) {
				$precio = bcadd($precio, bcmul($peso, $prec));
			}
        
		}//end foreach

        if($precio == 0 && !empty($aforo->id)){
            $descripcion = $paquete['descripcion_paquete'];
            $error = "El peso volumetrico ({$peso}kg) del paquete {$descripcion} excede la configuraci&oacute;n del tarifario";
        }
        
        return ["precio" => $precio, "error" => $error];
        //return $precio;
    }
    
    function getPrecioPorBultos($cliente_id, $esquema, $paquete, $precio){
        $error = "";

        $bultos = $this->getBultos($cliente_id, $paquete['zonaA'], $paquete['zonaB']);
        
        $bulto = $paquete['bultos'];

        foreach ($bultos as $t) {
            //BANDERA 1 = BLOQUE
            //BANDERA 2 = UNIDAD
            $desde = floatval($t->desde);
            $hasta = floatval($t->hasta);
            $bandera = intval($t->bandera);
            $prec = floatval($t->precio);
            $bulto = floatval($bulto);
            
            if ($bandera == 1 && $bulto >= $desde && $bulto <= $hasta) {
                $precio = bcadd($precio, $prec);
            }
            
            if ($bandera == 2 && $bulto >= $desde && $bulto <= $hasta) {
                $precio = bcadd($precio, bcmul($bulto, $prec));
            }
        
        }
        
        return ["precio" => $precio, "error" => $error];
        //return $precio;
    }
    
    function getPrecioPorPalets($cliente_id, $esquema, $paquete, $precio){
        $error = "";

        $palets = $this->getPalets($cliente_id, $paquete['zonaA'], $paquete['zonaB']);
    
        $palet = $paquete['palets'];

        foreach ($palets as $t) {
            //BANDERA 1 = BLOQUE
            //BANDERA 2 = UNIDAD
            $desde = floatval($t->desde);
            $hasta = floatval($t->hasta);
            $bandera = intval($t->bandera);
            $prec = floatval($t->precio);
            $palet = floatval($palet);
            
            if ($bandera == 1 && $palet >= $desde && $palet <= $hasta) {
                $precio = bcadd($precio, $prec);
            }
            
            if ($bandera == 2 && $palet >= $desde && $palet <= $hasta) {
                $precio = bcadd($precio, bcmul($palet, $prec));
            }
        
        }
          
        return ["precio" => $precio, "error" => $error];  
        //return $precio;
    }
    
    function getPrecioPorMetrosCubicos($cliente_id, $esquema, $paquete, $precio){
        $error = "";
        
        $metrosCubicos = $this->getMetrosCubicos($cliente_id, $paquete['zonaA'], $paquete['zonaB']);

        $mc = $paquete['metro_cubico'];

        foreach ($metrosCubicos as $t) {
            //BANDERA 1 = BLOQUE
            //BANDERA 2 = UNIDAD
            $desde = floatval($t->desde);
            $hasta = floatval($t->hasta);
            $bandera = intval($t->bandera);
            $prec = floatval($t->precio);
            $mc = floatval($mc);
            
            if ($bandera == 1 && $mc >= $desde && $mc <= $hasta) {
                $precio = bcadd($precio, $prec);
            }
            
            if ($bandera == 2 && $mc >= $desde && $mc <= $hasta) {
                $precio = bcadd($precio, bcmul($mc, $prec));
            }
        
        }
        
        //return $precio;
        return ["precio" => $precio, "error" => $error];
    }
    
    //OBTENIENDO SOBRE EL TOTAL DE PRODUCTOS
    /*
    function getPrecioValorDeclarado($cliente_id, $paquete, $lista_precios){
        
        $valorDeclarado = $this->getValorDeclarado($cliente_id, $paquete['zonaA'], $paquete['zonaB']);
        $precio = 0;
        $precios = [];
        
        if($valorDeclarado->tipo == "porcentaje"){
            
            for($i = 0; $i < count($lista_precios); $i++){
              
                $porcentaje = $valorDeclarado->valor / 100;
                $precio = bcmul($lista_precios[$i], $porcentaje);
                array_push($precios, $precio);
                
            }

            
        }
        
        if($valorDeclarado->tipo == "monto"){
            
            for($i = 0; $i < count($lista_precios); $i++){
              
                //$precio = bcadd($lista_precios[$i], $valorDeclarado->valor);
                array_push($precios, $valorDeclarado->valor);
                
            }
            
        }
        
        return $precios;
    }
    */

    function getPrecioValorDeclarado($cliente_id, $paquetes){
        $precios = [];
        
        foreach($paquetes as $paquete){
            $precio = 0;
            
            $esquema = $this->getConfiguracionTarifarioPorZona($cliente_id, $paquete['zonaA'], $paquete['zonaB']);

            if($esquema[0]['valorDeclarado'] && $paquete['monto_valor_declarado']){
                /*
                if($paquete['monto_valor_declarado'] <= 0 || $paquete['monto_valor_declarado'] == ""){
                    $descripcion = $paquete['descripcion_paquete'];
                    $error = "El paquete {$paquete['descripcion']} no tiene un valor declarado";
                }
                */

                $valorDeclarado = $this->getValorDeclarado($cliente_id, $paquete['zonaA'], $paquete['zonaB']);

                if($valorDeclarado->tipo == "porcentaje"){
                    $porcentaje = $valorDeclarado->valor / 100;
                    $precio = bcmul($paquete['monto_valor_declarado'], $porcentaje);
                    array_push($precios, $precio);
                }

                if($valorDeclarado->tipo == "monto"){
                    //$precio = bcadd($lista_precios[$i], $valorDeclarado->valor);
                    array_push($precios, $valorDeclarado->valor);     
                }

            }else{
                array_push($precios, 0);
            }
        }
        
        return $precios;
    }
    
    function getPrecioCobranza($cliente_id, $paquetes){
        $precios = [];
        
        foreach($paquetes as $paquete){
            $precio = 0;
            
            $esquema = $this->getConfiguracionTarifarioPorZona($cliente_id, $paquete['zonaA'], $paquete['zonaB']);
            
            //print_r($esquema[0]['cobranza']);

            if($esquema[0]['cobranza'] == "TRUE" && $paquete['monto_cobranza']){

                $cobranza = $this->getValorCobranza($cliente_id, $paquete['zonaA'], $paquete['zonaB']);

                if($cobranza->tipo == "porcentaje"){
                    $porcentaje = $cobranza->valor / 100;
                    $precio = bcmul($paquete['monto_cobranza'], $porcentaje);
                    array_push($precios, $precio);
                }

                if($cobranza->tipo == "monto"){
                    //$precio = bcadd($lista_precios[$i], $cobranza->valor);
                    array_push($precios, $cobranza->valor);     
                }

            }else{
                array_push($precios, 0);
            }
        }
        
        return $precios;
    }
    

    function getKilos($cliente, $zonaA, $zonaB){
        $query = "SELECT K.* 
        FROM flash_tarifario T
        INNER JOIN flash_clientes C ON T.cliente_id = C.id
        INNER JOIN flash_esquema E ON T.esquema_id = E.id
        INNER JOIN sub_esquema S ON S.esquema_id = E.id
        INNER JOIN flash_sub_esquema_kg K ON S.id = K.sub_esquema_id
        WHERE 
        C.id = $cliente
        AND
        S.zonaA = $zonaA
        AND
        S.zonaB = $zonaB
        ";

        $resultado = $this->db->query($query);
        
        $r = $resultado->result();
        
        return $r;
    }
    
    function getBultos($cliente, $zonaA, $zonaB){
        $query = "SELECT B.* 
        FROM flash_tarifario T
        INNER JOIN flash_clientes C ON T.cliente_id = C.id
        INNER JOIN flash_esquema E ON T.esquema_id = E.id
        INNER JOIN sub_esquema S ON S.esquema_id = E.id
        INNER JOIN flash_sub_esquema_bultos B ON S.id = B.sub_esquema_id
        WHERE 
        C.id = $cliente
        AND
        S.zonaA = $zonaA
        AND
        S.zonaB = $zonaB
        ";

        $resultado = $this->db->query($query);
        $r = $resultado->result();
        
        return $r;
    }

    function getPalets($cliente, $zonaA, $zonaB){
        $query = "SELECT P.* 
        FROM flash_tarifario T
        INNER JOIN flash_clientes C ON T.cliente_id = C.id
        INNER JOIN flash_esquema E ON T.esquema_id = E.id
        INNER JOIN sub_esquema S ON S.esquema_id = E.id
        INNER JOIN flash_sub_esquema_palets P ON S.id = P.sub_esquema_id
        WHERE 
        C.id = $cliente
        AND
        S.zonaA = $zonaA
        AND
        S.zonaB = $zonaB
        ";

        $resultado = $this->db->query($query);
        $r = $resultado->result();
        
        return $r;
    }
    
    function getMetrosCubicos($cliente, $zonaA, $zonaB){
        $query = "SELECT M.* 
        FROM flash_tarifario T
        INNER JOIN flash_clientes C ON T.cliente_id = C.id
        INNER JOIN flash_esquema E ON T.esquema_id = E.id
        INNER JOIN sub_esquema S ON S.esquema_id = E.id
        INNER JOIN flash_sub_esquema_metros_cubicos M ON S.id = M.sub_esquema_id 
        WHERE 
        C.id = $cliente
        AND
        S.zonaA = $zonaA
        AND
        S.zonaB = $zonaB
        ";

        $resultado = $this->db->query($query);
        $r = $resultado->result();
        
        return $r;
    }   
    
    function getAforo($cliente, $zonaA, $zonaB){
        $query = "SELECT PA.* 
        FROM flash_tarifario T
        INNER JOIN flash_clientes C ON T.cliente_id = C.id
        INNER JOIN flash_esquema E ON T.esquema_id = E.id
        INNER JOIN sub_esquema S ON S.esquema_id = E.id
        INNER JOIN flash_sub_esquema_peso_aforado PA ON S.id = PA.sub_esquema_id
        WHERE 
        C.id = $cliente
        AND
        S.zonaA = $zonaA
        AND
        S.zonaB = $zonaB
        ";

        $resultado = $this->db->query($query);

        return $resultado->row();
    }
    
    function getValorDeclarado($cliente, $zonaA, $zonaB){
        $query = "SELECT VD.* 
        FROM flash_tarifario T
        INNER JOIN flash_clientes C ON T.cliente_id = C.id
        INNER JOIN flash_esquema E ON T.esquema_id = E.id
        INNER JOIN sub_esquema S ON S.esquema_id = E.id
        INNER JOIN flash_sub_esquema_valor_declarado VD ON S.id = VD.sub_esquema_id
        WHERE 
        C.id = $cliente
        AND
        S.zonaA = $zonaA
        AND
        S.zonaB = $zonaB
        ";

        $resultado = $this->db->query($query);

        return $resultado->row();
    }

    function getValorCobranza($cliente, $zonaA, $zonaB){
        $query = "SELECT COB.* 
        FROM flash_tarifario T
        INNER JOIN flash_clientes C ON T.cliente_id = C.id
        INNER JOIN flash_esquema E ON T.esquema_id = E.id
        INNER JOIN sub_esquema S ON S.esquema_id = E.id
        INNER JOIN flash_sub_esquema_cobranza COB ON S.id = COB.sub_esquema_id
        WHERE 
        C.id = $cliente
        AND
        S.zonaA = $zonaA
        AND
        S.zonaB = $zonaB
        ";

        $resultado = $this->db->query($query);

        return $resultado->row();
    }

    function getPaquetesSinLiquidarParaEsteTarifario($cliente){
        $query = "SELECT COUNT(*) cantidadPiezasSinLiquidar
                  FROM flash_piezas P 
                  INNER JOIN flash_piezas_paquetes PP ON PP.pieza_id = P.id
                  INNER JOIN flash_comprobantes_ingresos CI ON CI.id = P.comprobante_ingreso_id
                  WHERE 
                    CI.cliente_id = $cliente
                    AND
                    CI.liquidacion_id = 0
                    AND 
                    ( CI.estado_liquidacion = 0 OR CI.estado_liquidacion = 13 ) #ESTADO 13 ANULADO, SE PUEDE VOLVER A LIQUIDAR";

        $resultado = $this->db->query($query);

        return $resultado->row();
    }
    
    function insertarPesoVolumetrico($peso, $pieza){
        $data = array(
            'peso_volumetrico' => $peso
        );
        
        $this->db->where('pieza_id', $pieza);
        
        $this->db->update('flash_piezas_paquetes', $data);
    }
    
    function getConfiguracionTarifarioPorZona($cliente, $zonaA, $zonaB){
        $query = "SELECT 
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
                
                WHERE C.id = $cliente
                AND S.zonaA = $zonaA
                AND S.zonaB = $zonaB
                ";
                
        $resultado = $this->db->query($query);
        $esquema = $resultado->result_array();

        return $esquema;

    }
    
    function getPaquetes($cliente_id, $periodo_desde, $periodo_hasta, $departamento_id, $sucursal_id){
        $date_desde = new DateTime($periodo_desde);
        $date_hasta = new DateTime($periodo_hasta);
            
        if($periodo_desde){
            $query = $this->db->where('ci.create >= ', $date_desde->format('Y-m-d 00:00:00'));
        }
        
        if($periodo_hasta){
            $query = $this->db->where('ci.create <= ', $date_hasta->format('Y-m-d 23:59:59'));
        }
        
        if ($departamento_id){
            $query = $this->db->where('ci.departamento_id =', (int)$departamento_id);
        }
        
        if ($sucursal_id){
            $query = $this->db->where('ci.sucursal_id = ', (int)$sucursal_id);
        }
        
        $paquetes = $this->db->select('  
            c.nombre,
            ci.id comprobante_ingreso_id,
            ci.numero, 
            cis.remito, 
            DATE_FORMAT(ci.create,"%d-%m-%Y") AS fecha,
            pp.*,
            p.codigo_postal_destino,
            p.codigo_postal_origen,
            p.domicilio,
            p.barcode_externo')
                ->join('flash_piezas p', 'p.id = pp.pieza_id')
                ->join('flash_comprobantes_ingresos ci', 'p.comprobante_ingreso_id = ci.id')
                ->join('flash_clientes c', 'ci.cliente_id = c.id')
                ->join('flash_comprobantes_ingresos_servicios cis', 'p.servicio_id = cis.id')
                ->where('ci.cliente_id = ' . $cliente_id)
                ->where('(ci.estado_liquidacion <> 2 OR ci.estado_liquidacion IS NULL)')
                ->where('ci.cantidad IS NOT NULL')
                 //AGREGADO PARA QUE ME TRAIGA LAS PIEZAS PAQUETES QUE NO TENGAN UNA LIQUIDACIÓN
                ->where('ci.liquidacion_id = 0') //AGREGADO
                ->where('cis.servicio_id = 2') //2:Paqueteria
                ->group_by('ci.id, cis.servicio_id, cis.remito')
                ->order_by('ci.create ')
                ->get('flash_piezas_paquetes pp');
                
        return $paquetes;
    }
}