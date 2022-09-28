<?php

class Consultas extends CI_Controller {	
	
	function __construct() {
		parent::__construct();
	}	
	
	public function getDistribucionesPendientes(){
//          var_dump($_POST);
            $periodo_desde = $this->input->post('periodo_desde') != ''?$this->input->post('periodo_desde'):false;
            $periodo_hasta = $this->input->post('periodo_hasta') != ''?$this->input->post('periodo_hasta'):false;
            $date_desde = new DateTime($periodo_desde);
            $date_hasta = new DateTime($periodo_hasta);
            if($periodo_desde){
                $query = $this->db->where('ci.fecha_pedido >= ', $date_desde->format('Y-m-d 00:00:00'));
            }
            if($periodo_hasta){
                $query = $this->db->where('ci.fecha_pedido <= ', $date_hasta->format('Y-m-d 23:59:59'));
            }
            $sucursal_id = $this->input->post('sucursal_id') != ''?$this->input->post('sucursal_id'):false;
            if ($sucursal_id){
                $query = $this->db->where('hdr.sucursal_id = ', (int)$sucursal_id);
            }
            
            $query = $this->db
                    ->select('  DATE_FORMAT(ci.fecha_pedido,"%d-%m-%Y") as fecha_ingreso,
                                p.id pieza_id, 
                                ci.id comprobante_ingreso_id, 
                                c.nombre cliente,
                                s.nombre servicio,
                                sc.apellido_nombre cartero,
                                hdr.id hdr_id,
                                DATE_FORMAT(hdr.create,"%d-%m-%Y") as hdr_creacion,
                                p.destinatario,
                                p.domicilio,
                                p.localidad,
                                p.codigo_postal,
                                ev.nombre estado')
                    ->join('flash_subpiezas sp', 'p.id = sp.pieza_id')
                    ->join('flash_comprobantes_ingresos_servicios cis', 'p.servicio_id = cis.id')
                    ->join('flash_comprobantes_ingresos ci', 'p.comprobante_ingreso_id = ci.id AND p.servicio_id = cis.id')
                    ->join('flash_servicios s', 'cis.servicio_id = s.id')
                    ->join('flash_clientes c', 'c.id = ci.cliente_id')
                    ->join('flash_hojas_rutas hdr', 'hdr.id = sp.hoja_ruta_id')
                    ->join('flash_sucursales_carteros sc', 'hdr.cartero_id = sc.id', 'left')
                    ->join('flash_piezas_estados_variables ev', 'p.estado_id = ev.id')
                    ->get('flash_piezas p');
//            echo $this->db->last_query();die;
            $json = $query->result();
           
            if($json) echo json_encode(array('grilla' => $json));
            else echo json_encode(array('status' => 'none'));
	}

        //////////////////////////DESDE AQUI BORRAR/////////////////////////////////////////
	public function estados($id){
		$json = $this->codegen_model->get('flash_piezas_estados_variables','*','pieza_estado_id = '.$id);
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}
        
        public function analizarCsv(){
//            echo var_dump($_REQUEST)."<br/>";
//            echo var_dump($_POST)."<br/>";die;
//            echo var_dump($_GET)."<br/>";
//            echo var_dump($_FILES)."<br/>";
            $servicio_cantidad_disponible =$this->input->post('servicio_cantidad_disponible');
            $path = 'd:\\upload'; //base_url().'assets/upload';
            $config['upload_path']   = $path; 
            $config['allowed_types'] = 'csv'; 
            $config['max_size']      = 100; 
            $config['max_width']     = 1024; 
            $config['max_height']    = 768;  
            $config['enclosure']    = ';';  

            $this->load->library('upload', $config);
            //Copio el archivo en el direcorio de destino con el nombre que quiero
            move_uploaded_file( $_FILES['file']['tmp_name'],'d:\\upload\piezas.csv');

            $file = fopen($path.'\piezas.csv',"r");
            $cantidad_piezas = 0;
            $piezas = null;
            while(! feof($file))
              {
                $piezas[$cantidad_piezas] = (fgetcsv($file, 10000,";"));
                $cantidad_piezas++;
              }
            fclose($file);
//            echo var_dump($piezas)."<br/>";
            $respuesta = null;
            if (intval($cantidad_piezas) > intval($this->input->post('cantidad_servicios_'.$this->input->post('servicio_id'))+intval(1))){//+1 para tener en cuenta el encabezado
                echo json_encode(array('error' => 'Error, la cantidad de piezas que intenta ingresar excede el limite disponible para ese servicio.'));
            }else{
                for($i=1; $i < count($piezas); $i++){
                    for($j=0 ; $j < count($piezas[$i]) ; $j++){
                        if($piezas[$i] != NULL)
                            $respuesta[$i][$j] = $piezas[$i][$j];
                    } 
                }
                $comprobante_servicio = $this->codegen_model->row('flash_comprobantes_ingresos_servicios','*','id = '.$this->input->post('servicio_id'));
                $servicio = $this->codegen_model->row('flash_servicios','*','id = '.$comprobante_servicio->servicio_id);
                $respuesta['servicio']['nombre'] = $servicio->nombre;
                echo json_encode($respuesta);
            }
	}
        
        public function traerCantidadPiezas(){
                $codigo_barras_id =  $this->input->post('codigo_barra');
		$json = $this->codegen_model->row('flash_piezas','*','id = '.$codigo_barras_id);
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}
        
        public function traerCantidadPiezasPorComprobante(){
                $codigo_barras_id =  $this->input->post('codigo_barra');
                $query = $this->db
                        ->select('COUNT(*) as cantidad')
                        ->join('flash_comprobantes_ingresos_servicios cis', 'p.servicio_id = cis.id')
                        ->join('flash_comprobantes_ingresos ci', 'ci.id = cis.comprobante_ingreso_id')
                        ->where('ci.id = '.$codigo_barras_id)
                        ->where('p.estado_id = '.Pieza::ESTADO_EN_DISTRIBUCION)
                        ->where('p.tipo_id = '.Pieza::TIPO_SIMPLE)
                        ->get('flash_piezas p');
                
		$json = $query->result();
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}
        
        public function traerCantidadPiezasPorComprobanteYServicio(){
                $Comprobante_ingreso =  Comprobante::whereNumero($this->input->post('codigo_barra'))->first();
                $servicio_id =  $this->input->post('servicio_id');

                $query = $this->db
                        ->select('COUNT(*) as cantidad')
                        ->join('flash_comprobantes_ingresos_servicios cis', 'cis.id = p.servicio_id ')
                        ->join('flash_comprobantes_ingresos ci', 'ci.id = p.comprobante_ingreso_id')
                        ->where('p.comprobante_ingreso_id = '.$Comprobante_ingreso->id)
                        ->where('p.estado_id IN ('.Pieza::ESTADO_EN_DISTRIBUCION.','.Pieza::ESTADO_EN_GESTION.','.Pieza::ESTADO_NO_RESPONDE.','.Pieza::ESTADO_EN_TRNSITO.')')
                        ->where('p.tipo_id = '.Pieza::TIPO_SIMPLE)
                        ->where('cis.servicio_id = '.$servicio_id)
                        //->where('p.id')
                        ->get('flash_piezas p');
                
		$json = $query->result();
                //echo($this->db->last_query());die;
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}
        
        public function traerPiezasSimplesPorComprobante(){
            $codigo_barras_id =  $this->input->post('codigo_barra');
            $cantidad = $this->input->post('cantidad');
            $cargados = $this->input->post('cargados');
            if ($cargados == '') $cargados = 0;
            $query = $this->db
                    ->select(' p.barcode, p.id, p.destinatario, p.domicilio, p.localidad, p.codigo_postal ')
                    ->join('flash_comprobantes_ingresos_servicios cis', 'p.servicio_id = cis.id')
                        ->join('flash_comprobantes_ingresos ci', 'ci.id = cis.comprobante_ingreso_id')
                    ->where('ci.numero = '.$codigo_barras_id)
                    ->where('p.estado_id IN ('.Pieza::ESTADO_EN_DISTRIBUCION.','.Pieza::ESTADO_EN_GESTION.','.Pieza::ESTADO_NO_RESPONDE.','.Pieza::ESTADO_EN_TRNSITO.')')
                    ->where('p.tipo_id = '.Pieza::TIPO_SIMPLE)
                    ->where('p.id NOT IN ('.$cargados.')')
                    ->limit($cantidad)
                    ->get('flash_piezas p');
            //echo($this->db->last_query());die;
            $json = $query->result();
            if($json) echo json_encode($json);
            else echo json_encode(array('status' => 'none'));
	}
        
        public function traerPiezasSimplesPorComprobanteYEstados(){
            $codigo_barras_id =  $this->input->post('codigo_barra');
            $cantidad = $this->input->post('cantidad');
            $cargados = $this->input->post('cargados');
            $estados_id = $this->input->post('estados_id');
            if ($cargados == '') $cargados = 0;
            $subquery = $this->db
                    ->select('pieza_id')
                    ->get('flash_subpiezas');
            $subjson = $subquery->result();
            $no_disponibles = "";
            foreach ($subjson as $value) {
                if ($no_disponibles == ""){ $no_disponibles = $value->pieza_id;}
                else{$no_disponibles = $no_disponibles.",".$value->pieza_id;}
            }
            if ($no_disponibles=="") $no_disponibles=0;
            $no_disponibles = $cargados.",".$no_disponibles;
            
            $query = $this->db
                    ->select(' p.barcode, p.id, p.destinatario, p.domicilio, p.localidad, p.codigo_postal, s.nombre as servicio, p.barcode_externo')
                    ->join('flash_comprobantes_ingresos_servicios cis', 'p.servicio_id = cis.id')
                    ->join('flash_comprobantes_ingresos ci', 'ci.id = cis.comprobante_ingreso_id')
                    ->join('flash_servicios s', 's.id = cis.servicio_id')
                    ->join('flash_subpiezas sp', 'sp.pieza_id = p.id','left')
                    ->where('ci.numero = '.$codigo_barras_id)
                    ->where('p.estado_id IN ( '.$estados_id.')')
                    ->where('p.tipo_id = '.Pieza::TIPO_SIMPLE)
                    ->where('p.id NOT IN ('.$no_disponibles.')')
                    ->limit($cantidad)
                    ->get('flash_piezas p');
            $json = $query->result();
            if($json) echo json_encode($json);
            else echo json_encode(array('status' => 'none'));
	}
        
        public function traerPiezasSimplesPorComprobanteEstadosYServicio(){
            $codigo_barras_id =  $this->input->post('codigo_barra');
            $cantidad = $this->input->post('cantidad');
            $cargados = $this->input->post('cargados');
            $estados_id = $this->input->post('estados_id');
            $comprobante_servicio_id =  $this->input->post('servicio_id');
            if ($cargados == '') $cargados = 0;
            $subquery = $this->db
                    ->select('pieza_id')
                    ->get('flash_subpiezas');
            $subjson = $subquery->result();
            $no_disponibles = "";
            foreach ($subjson as $value) {
                if ($no_disponibles == ""){ $no_disponibles = $value->pieza_id;}
                else{$no_disponibles = $no_disponibles.",".$value->pieza_id;}
            }
            if ($no_disponibles=="") $no_disponibles=0;
            $no_disponibles = $cargados.",".$no_disponibles;
            
            $query = $this->db
                    ->select(' p.barcode, p.id, p.destinatario, p.domicilio, p.localidad, p.codigo_postal, s.nombre as servicio, p.barcode_externo')
                    ->join('flash_comprobantes_ingresos_servicios cis', 'p.servicio_id = cis.id')
                    ->join('flash_comprobantes_ingresos ci', 'ci.id = cis.comprobante_ingreso_id')
                    ->join('flash_servicios s', 's.id = cis.servicio_id')
                    ->join('flash_subpiezas sp', 'sp.pieza_id = p.id','left')
                    ->where('ci.numero = '.$codigo_barras_id)
                    ->where('p.estado_id IN ( '.$estados_id.')')
                    ->where('p.tipo_id = '.Pieza::TIPO_SIMPLE)
                    ->where('p.id NOT IN ('.$no_disponibles.')')
                    ->where('cis.id = '.$comprobante_servicio_id)
                    ->limit($cantidad)
                    ->get('flash_piezas p');
            $json = $query->result();
//            echo $this->db->last_query();die;
            if($json) echo json_encode($json);
            else echo json_encode(array('status' => 'none'));
	}
        
        public function comprobanteExistente($numero){
                
            $query = $this->db
                    ->select(' id ')
                    ->where('numero = "'.$numero.'"')
                    ->get('flash_comprobantes_ingresos');

            $json = $query->result();
            //echo $this->db->last_query();die;
            if($json) echo json_encode($json);
            else echo json_encode(array('status' => 'El comprobante no existe'));
	}

        public function getConsultasGlobales(){
//          var_dump($_POST);die;
            $fecha_ingreso = $this->input->post('fecha_ingreso')!=''?$this->input->post('fecha_ingreso'):false;
            $pieza_id = $this->input->post('pieza_id');
            $barra_externa = $this->input->post('barra_externa');
            $comprobante = $this->input->post('comprobante');//numero
            $cliente = $this->input->post('cliente');//nombre
            $servicio = $this->input->post('servicio');
            $cartero = $this->input->post('cartero');//cartero apellido_nombre
            $hoja_ruta_id = $this->input->post('hoja_ruta_id');
            $despacho_id = $this->input->post('despacho_id');
            $sucursal = $this->input->post('sucursal');//nombre
            $estado = $this->input->post('estado');//nombre
            $destinatario = $this->input->post('destinatario');
            $domicilio = $this->input->post('domicilio');
            $codigo_postal = $this->input->post('codigo_postal');
            $localidad = $this->input->post('localidad');
            $fecha_cambio_estado = $this->input->post('fecha_cambio_estado') != ''?$this->input->post('fecha_cambio_estado'):FALSE;
            $visitas = $this->input->post('visitas');
            $rendicion_id = $this->input->post('rendicion_id');
            $recibio = $this->input->post('recibio');
            $documento = $this->input->post('documento');
            $vinculo = $this->input->post('vinculo');
            $datos_varios_1 = $this->input->post('datos_varios_1');
            $datos_varios_2 = $this->input->post('datos_varios_2');
            $datos_varios_3 = $this->input->post('datos_varios_3');
            
            if ($fecha_ingreso){
                $fecha_ingreso = new DateTime($fecha_ingreso);
                $query = $this->db->where('ci.create >= ', $fecha_ingreso->format('Y-m-d 00:00:00'));
            }
            if ($pieza_id != '') $query = $this->db->where('p.id =', (int)$pieza_id);
            if ($barra_externa != '') $query = $this->db->like('p.barcode_externo',$barra_externa );
            if ($comprobante != '') $query = $this->db->like('ci.numero',$comprobante );
            if ($cliente != '') $query = $this->db->like('c.nombre',$cliente );
            if ($servicio != '') $query = $this->db->like('s.nombre',$servicio );
            if ($cartero != '') $query = $this->db->like('sc.apellido_nombre',$cartero );
            if ($hoja_ruta_id != '') $query = $this->db->where('hdr.id = ',$hoja_ruta_id );
            if ($despacho_id != '') $query = $this->db->where('d.id = ',$despacho_id );
            if ($sucursal != '') $query = $this->db->like('suc.nombre',$sucursal );
            if ($estado != '') $query = $this->db->like('ev.nombre',$estado );
            if ($destinatario != '') $query = $this->db->like('p.destinatario',$destinatario );
            if ($domicilio != '') $query = $this->db->like('p.domicilio',$domicilio );
            if ($codigo_postal != '') $query = $this->db->like('p.codigo_postal',$codigo_postal );
            if ($localidad != '') $query = $this->db->like('p.localidad',$localidad );
            
            if($fecha_cambio_estado){
                $fecha_cambio_estado = new DateTime($fecha_cambio_estado);
                $query = $this->db->where('n.update >= ', $fecha_cambio_estado->format('Y-m-d 00:00:00'));
            }
            if ($rendicion_id != '') $query = $this->db->where('rp.rendicion_id = ',$rendicion_id );
            if ($recibio != '') $query = $this->db->like('p.recibio',$recibio );
            if ($documento != '') $query = $this->db->like('p.documento',$documento );
            if ($vinculo != '') $query = $this->db->like('p.vinculo',$vinculo );
            if ($datos_varios_1 != '') $query = $this->db->like('p.datos_varios',$datos_varios_1 );
            if ($datos_varios_2 != '') $query = $this->db->like('p.datos_varios_1',$datos_varios_2 );
            if ($datos_varios_3 != '') $query = $this->db->like('p.datos_varios_2',$datos_varios_3 );

           $query = $this->db
                            ->select(' (CASE WHEN ci.create IS NULL THEN "" ELSE DATE_FORMAT(ci.create,"%d-%m-%Y") END) AS fecha_ingreso ,
                                        (CASE WHEN p.id IS NULL THEN "" ELSE p.id END) AS pieza_id ,
                                        (CASE WHEN p.barcode_externo IS NULL THEN "" ELSE p.barcode_externo END) AS barcode_externo ,
                                        (CASE WHEN ci.numero IS NULL THEN "" ELSE ci.numero END) AS comprobante ,
                                        (CASE WHEN c.nombre IS NULL THEN "" ELSE c.nombre END) AS cliente ,
                                        (CASE WHEN s.nombre IS NULL THEN "" ELSE s.nombre END) AS servicio ,
                                        (CASE WHEN sc.apellido_nombre IS NULL THEN "" ELSE sc.apellido_nombre END) AS cartero ,
                                        (CASE WHEN hdr.id IS NULL THEN "" ELSE hdr.id END) AS hoja_ruta_id ,
                                        (CASE WHEN d.id IS NULL THEN "" ELSE d.id END) AS despacho_id ,
                                        (CASE WHEN suc.nombre IS NULL THEN "" ELSE suc.nombre END) AS sucursal ,
                                        (CASE WHEN n.estado_actual_id IS NULL THEN "" ELSE n.estado_actual_id END) AS estado_actual_id ,
                                        (CASE WHEN p.destinatario IS NULL THEN "" ELSE p.destinatario END) AS destinatario ,
                                        (CASE WHEN p.domicilio IS NULL THEN "" ELSE p.domicilio END) AS domicilio ,
                                        (CASE WHEN p.codigo_postal IS NULL THEN "" ELSE p.codigo_postal END) AS codigo_postal ,
                                        (CASE WHEN p.localidad IS NULL THEN "" ELSE p.localidad END) AS localidad ,
                                        (CASE WHEN n.update IS NULL THEN "" ELSE DATE_FORMAT(n.update,"%d-%m-%Y") END) AS fecha_cambio_estado ,
                                        (CASE WHEN ev.nombre IS NULL THEN "" ELSE ev.nombre END) AS estado_actual ,
                                        (CASE WHEN rp.rendicion_id IS NULL THEN "" ELSE rp.rendicion_id END) AS rendicion_id ,
                                        (CASE WHEN p.recibio IS NULL THEN "" ELSE p.recibio END) AS recibio ,
                                        (CASE WHEN p.documento IS NULL THEN "" ELSE p.documento END) AS documento ,
                                        (CASE WHEN p.vinculo IS NULL THEN "" ELSE p.vinculo END) AS vinculo ,
                                        (CASE WHEN p.datos_varios IS NULL THEN "" ELSE p.datos_varios END) AS datos_varios ,
                                        (CASE WHEN p.datos_varios_1 IS NULL THEN "" ELSE p.datos_varios_1 END) AS datos_varios_1 ,
                                        (CASE WHEN p.datos_varios_2 IS NULL THEN "" ELSE p.datos_varios_2 END) AS datos_varios_2 ')
                            ->join('flash_comprobantes_ingresos ci', 'ci.id = p.comprobante_ingreso_id')
                            ->join('flash_clientes c', 'c.id = ci.cliente_id')
                            ->join('flash_comprobantes_ingresos_servicios cis', 'cis.id = p.servicio_id')
                            ->join('flash_servicios s', 'cis.servicio_id = s.id')
                            ->join('flash_subpiezas sp', 'sp.pieza_id = p.id','left')
                            ->join('flash_hojas_rutas hdr', 'hdr.id = sp.hoja_ruta_id','left')
                            ->join('flash_sucursales_carteros sc', 'sc.id = hdr.cartero_id','left')
                            ->join('flash_piezas_despacho_piezas pd', 'pd.pieza_id = p.id','left')
                            ->join('flash_piezas_despacho d', 'd.id = pd.despacho_id','left')
                            ->join('flash_sucursales suc', 'suc.id = p.sucursal_id')
                            ->join('flash_piezas_novedades n', 'n.pieza_id = p.id','left')
                            ->join('flash_piezas_estados_variables ev', 'ev.id = n.estado_actual_id','left')
                            ->join('flash_rendiciones_piezas rp', 'p.id =rp.pieza_id','left')
                            ->where('p.tipo_id = 2')
                            
//                            ->where('liquidacion_id = 0')
//                            ->group_by('cis.servicio_id')
//                            ->order_by('ci.create ')
//                            ->having('cantidad > 0 ')
                            ->get('flash_piezas p');

            $json = $query->result();
            $filtros = $_POST;
           
            //echo $this->db->last_query();die;
            if($json) echo json_encode(array('grilla' => $json, 'filtros' => $filtros));
            else echo json_encode(array('status' => 'none'));
	}
        
        public function verificar(){
            
            $codigo = (int)$this->input->post('codigo');
            $comprobante_ingreso_id = '"'.$this->input->post('comprobante_ingreso_id').'"';
            $codigo_tipo = "id";
            $pieza = $this->codegen_model->row('flash_piezas','*','id = '.$codigo.' AND comprobante_ingreso_id = '.$comprobante_ingreso_id);
            if ($pieza == NULL){
                $codigo = '"'.$this->input->post('codigo').'"';
                $codigo_tipo = 'barcode_externo';
                $pieza = $this->codegen_model->row('flash_piezas','*','barcode_externo = '.$codigo.' AND comprobante_ingreso_id = '.$comprobante_ingreso_id);
                if($pieza == NULL){
                    echo json_encode(array('status' => 'La pieza no existe'));
                }
            }

            if ($pieza != NULL){
                $data = array(
                                'verifico_id' => 1,
                                'update' => date('Y-m-d H:m:s')
                            );
                if ($codigo_tipo == 'id')
                    $this->codegen_model->edit('flash_piezas',$data,'id',$codigo);
                if ($codigo_tipo == 'barcode_externo')
                    $this->codegen_model->edit('flash_piezas',$data,'barcode_externo',$this->input->post('codigo'));
//                echo $this->db->last_query();die;
                echo json_encode(array('status' => 'Verificado'));
            }
            //echo json_encode(array('status' => 'tome'));
        }
        
        function getPiezasSinVerificar($comprobante_id){
                $query = $this->db
                        ->select(' p.id, p.destinatario, p.domicilio, p.localidad, p.codigo_postal ')
                        ->where('(p.verifico_id = 2 or p.verifico_id is null)')//1=Verificado 2=Sin Verificar
                        ->where('p.comprobante_ingreso_id = '.$comprobante_id)
                        ->get('flash_piezas p');
//                echo($this->db->last_query());die;
                $json = $query->result();
                if($json) echo json_encode($json);
                else echo json_encode(array('status' => 'none'));
        }
        
        function getListadoOperativo(){
            $periodo_desde = $this->input->post('periodo_desde') != ''?$this->input->post('periodo_desde'):false;
            $periodo_hasta = $this->input->post('periodo_hasta') != ''?$this->input->post('periodo_hasta'):false;
            $date_desde = new DateTime($periodo_desde);
            $date_hasta = new DateTime($periodo_hasta);
            //var_dump($_POST);die;
            if($periodo_desde){
                $query_ingresos = $this->db->where('ci.create >= ', $date_desde->format('Y-m-d 00:00:00'));
                //$query_rendidas = $this->db->where('ci.create >= ', $date_desde->format('Y-m-d 00:00:00'));
            }
            if($periodo_hasta){
                $query_ingresos = $this->db->where('ci.create <= ', $date_hasta->format('Y-m-d 23:59:59'));
                //$query_rendidas = $this->db->where('ci.create <= ', $date_hasta->format('Y-m-d 23:59:59'));
            }
                $query_ingresos = $this->db
                        ->select('  suc.id sucursal_id,
                                    suc.nombre sucursal,
                                    g.id grupo_id,
                                    g.nombre producto,
                                    SUM(cis.cantidad ) cantidad
                                ')
                        ->join('flash_comprobantes_ingresos_servicios cis', 'ci.id = cis.comprobante_ingreso_id')
                        ->join('flash_servicios s', 'cis.servicio_id = s.id')
                        ->join('flash_clientes c', 'ci.cliente_id = c.id')
                        ->join('flash_clientes_tipos ct', 'c.tipo_cliente_id = ct.id')
                        ->join('flash_sucursales suc', 'ci.sucursal_id = suc.id')
                        ->join('flash_clientes_departamentos cd', 'ci.departamento_id = cd.id')
                        ->join('flash_servicios_grupos g', 's.grupo_id = g.id')
                        ->group_by('ci.sucursal_id, g.id')
                        ->order_by('suc.nombre,g.nombre')
                        ->get('flash_comprobantes_ingresos ci');
                
                if($periodo_desde){
                    $query_rendidas = $this->db->where('ci.create >= ', $date_desde->format('Y-m-d 00:00:00'));
                }
                if($periodo_hasta){
                    $query_rendidas = $this->db->where('ci.create <= ', $date_hasta->format('Y-m-d 23:59:59'));
                }
                $query_rendidas = $this->db
                        ->select('  suc.id sucursal_id,
                                    suc.nombre sucursal,
                                    g.id grupo_id,
                                    g.nombre producto,
                                    COUNT(cis.cantidad ) cantidad
                                ')
                        ->join('flash_comprobantes_ingresos ci', 'ci.id = p.comprobante_ingreso_id')
                        ->join('flash_comprobantes_ingresos_servicios cis', 'cis.id = p.servicio_id')
                        ->join('flash_servicios s', 'cis.servicio_id = s.id')
                        ->join('flash_clientes c', 'ci.cliente_id = c.id')
                        ->join('flash_clientes_tipos ct', 'c.tipo_cliente_id = ct.id')
                        ->join('flash_sucursales suc', 'ci.sucursal_id = suc.id')
                        ->join('flash_clientes_departamentos cd', 'ci.departamento_id = cd.id')
                        ->join('flash_servicios_grupos g', 's.grupo_id = g.id')
                        ->join('flash_rendiciones_piezas rp', 'rp.pieza_id = p.id')
                        ->group_by('ci.sucursal_id, s.grupo_id')
                        ->order_by('suc.nombre,s.nombre')
                        ->get('flash_piezas p');
		//echo $this->db->last_query();die;
                $query_grupos = $this->db
                        ->select('  id, nombre')
                        ->order_by('nombre')
                        ->get('flash_servicios_grupos');
                
                $query_sucursales = $this->db
                        ->select('  id, nombre')
                        ->order_by('nombre')
                        ->get('flash_sucursales');
                $json_ingresos = $query_ingresos->result();
                $json_rendidas = $query_rendidas->result();
                $json_grupos = $query_grupos->result();
                $json_sucursales = $query_sucursales->result();
                $ingresadas = [];
                $rendidas = [];
                $pendientes = [];
                $tiene_ingreso =  false;
                $tiene_rendida =  false;
                $tiene_pendiente =  false;
                
                $matriz_resultatos = null;
                $fila = 3;
                $columna = "B";
                foreach ($json_grupos as $grupo) {
                    foreach ($json_sucursales as $sucursal) {
                        //echo $sucursal->nombre." - ".$grupo->nombre;
                        foreach ($json_ingresos as $ingreso) {
                                if($grupo->id == $ingreso->grupo_id && $sucursal->id == $ingreso->sucursal_id){
                                    //echo " ".$ingreso->cantidad." - ";
                                    array_push($ingresadas, $ingreso->cantidad);
                                    $tiene_ingreso = true; break;
                                }
                        }
                        foreach ($json_rendidas as $rendicion) {
                                if($grupo->id == $rendicion->grupo_id && $sucursal->id == $rendicion->sucursal_id){
                                    //echo " ".$rendicion->cantidad." - ";
                                    array_push($rendidas,$rendicion->cantidad );
                                    $resta = $ingresadas[count($ingresadas)-1] - $rendidas[count($rendidas)-1];
                                    //echo " ".$resta."  ";
                                    array_push($pendientes, ($resta));
                                    $tiene_rendida = TRUE;
                                    $tiene_pendiente = TRUE;
                                    break;
                                }
                        }
                        if (!$tiene_ingreso) array_push($ingresadas, '0');
                        if (!$tiene_rendida) array_push($rendidas, '0');
                        $resta_1 =  $ingresadas[count($ingresadas)-1] - $rendidas[count($rendidas)-1];
                        if (!$tiene_pendiente) array_push($pendientes,$resta_1 );
                        
                        $matriz_resultatos[$columna][$fila] = $ingresadas[count($ingresadas)-1];
                        $columna++;
                        $matriz_resultatos[$columna][$fila] = $rendidas[count($rendidas)-1];
                        $columna++;
                        $matriz_resultatos[$columna][$fila] = $pendientes[count($pendientes)-1];
                        $columna++;
                        //echo "<br/>";
                        $tiene_ingreso =  false;
                        $tiene_rendida =  false;
                        $tiene_pendiente =  false;
                    }
                    $fila++;
                    $columna = "B";
                    
                }
                $totales_columnas = [];
                end($matriz_resultatos);
                
                for ($c = "B"; $c <= key($matriz_resultatos) ; $c++){
                    for($f = 0; $f <= count($matriz_resultatos) ; $f++){//echo $matriz_resultatos[$c][$f]."<br/>";
                        $suma += $matriz_resultatos[$c][$f];
                    }
                    array_push($totales_columnas, $suma);
                    $suma = 0;
                }
                //var_dump($totales_columnas);die;
                if($json_rendidas && $json_ingresos) 
                    echo json_encode(array('ingresadas' => $ingresadas,
                                            'rendidas'=> $rendidas, 
                                            'pendientes'=> $pendientes,
                                            'sucursales'=> $json_sucursales,
                                            'grupos'=> $json_grupos,
                                            'totales_columnas'=> $totales_columnas,));
                else echo json_encode(array('status' => 'none'));
        }
        
        function getListadoVtasProductos(){
            $periodo_desde = $this->input->post('periodo_desde') != ''?$this->input->post('periodo_desde'):false;
            $periodo_hasta = $this->input->post('periodo_hasta') != ''?$this->input->post('periodo_hasta'):false;
            $date_desde = new DateTime($periodo_desde);
            $date_hasta = new DateTime($periodo_hasta);
//            var_dump($_POST);die;
            if($periodo_desde){
                $query_cantidad = $this->db->where('l.periodo_desde >= ', $date_desde->format('Y-m-d 00:00:00'));
                //$query_ventas = $this->db->where('ci.create >= ', $date_desde->format('Y-m-d 00:00:00'));
            }
            if($periodo_hasta){
                $query_cantidad = $this->db->where('l.periodo_hasta <= ', $date_hasta->format('Y-m-d 23:59:59'));
                //$query_ventas = $this->db->where('ci.create <= ', $date_hasta->format('Y-m-d 23:59:59'));
            }
                /*$query_cantidad = $this->db
                        ->select('  suc.id sucursal_id,
                                    suc.nombre sucursal, 
                                    g.id grupo_id,
                                    g.nombre grupo, 
                                    SUM(ld.cantidad) cantidad, 
                                    SUM(ld.precio*ld.cantidad) ventas, 
                                    (SUM(ld.precio*ld.cantidad))/SUM(ld.cantidad) precio_promedio')
                        ->join('flash_liquidaciones l', 'l.id = ld.liquidacion_id')
                        ->join('flash_servicios s', 'ld.servicio_id = s.id')
                        ->join('flash_servicios_grupos g', 's.grupo_id = g.id')
                        ->join('flash_sucursales suc', 'l.sucursal_id = suc.id')
                        ->join('flash_comprobantes_ingresos ci', ' ci.numero = ld.comprobante_ingreso')
                        ->where('ci.liquidacion_id = l.id')
                        ->group_by('l.sucursal_id, s.grupo_id')
                        ->order_by('suc.nombre,g.nombre')
                        ->get('flash_liquidaciones_detalles ld');*/
                        
                $query_cantidad = $this->db
                            ->select('  suc.id sucursal_id, 
                                        suc.nombre sucursal, 
                                        g.id grupo_id,
                                        g.nombre grupo, 
                                        SUM(lc.cant_a_liquidar) cantidad,
                                        SUM(lc.precio*lc.cant_a_liquidar) ventas,
                                        (SUM(lc.precio*lc.cant_a_liquidar))/SUM(lc.cant_a_liquidar) precio_promedio
                                    ')
                            ->join('flash_liquidaciones_clientes l', 'l.id = lc.liquidacion_cliente_id')
                            ->join('flash_servicios s', 'lc.servicio_id = s.id')
                            ->join('flash_servicios_grupos g', 's.grupo_id = g.id')
                            ->join('flash_sucursales suc', 'l.sucursal_id = suc.id')
                            ->join('flash_comprobantes_ingresos ci', ' ci.id = lc.comprobante_ingreso_id')
                            ->where('ci.liquidacion_id = l.id')

                            ->group_by('l.sucursal_id, g.id')
                            ->order_by('suc.nombre,g.nombre')
                            ->get('flash_liquidaciones_comprobantes lc');
                            
                //echo($this->db->last_query());die;
                if($periodo_desde){
                    $query_ventas = $this->db->where('l.periodo_desde >= ', $date_desde->format('Y-m-d 00:00:00'));
                }
                if($periodo_hasta){
                    $query_ventas = $this->db->where('l.periodo_hasta <= ', $date_hasta->format('Y-m-d 23:59:59'));
                }
                /*$query_ventas = $this->db
                        ->select('  suc.id sucursal_id,
                                    suc.nombre sucursal, 
                                    g.id grupo_id,
                                    g.nombre grupo, 
                                    SUM(ld.cantidad) cantidad, 
                                    SUM(ld.precio*ld.cantidad) ventas, 
                                    (SUM(ld.precio*ld.cantidad))/SUM(ld.cantidad) precio_promedio')
                        ->join('flash_liquidaciones l', 'l.id = ld.liquidacion_id')
                        ->join('flash_servicios s', 'ld.servicio_id = s.id')
                        ->join('flash_servicios_grupos g', 's.grupo_id = g.id')
                        ->join('flash_sucursales suc', 'l.sucursal_id = suc.id')
                        ->join('flash_comprobantes_ingresos ci', ' ci.numero = ld.comprobante_ingreso')
                        ->where('ci.liquidacion_id = l.id')
                        ->group_by('l.sucursal_id, s.grupo_id')
                        ->order_by('suc.nombre,g.nombre')
                        ->get('flash_liquidaciones_detalles ld');*/
                        
                $query_ventas = $this->db
                            ->select('  suc.id sucursal_id,
                                        suc.nombre sucursal,
                                        g.id grupo_id, 
                                        g.nombre grupo, 
                                        SUM(lc.cant_a_liquidar) cantidad,
                                        SUM(lc.precio*lc.cant_a_liquidar) ventas,
                                        (SUM(lc.precio*lc.cant_a_liquidar))/SUM(lc.cant_a_liquidar) precio_promedio
                                    ')
                            ->join('flash_servicios s', 'lc.servicio_id = s.id')
                            ->join('flash_servicios_grupos g', 's.grupo_id = g.id')
                            ->join('flash_liquidaciones_clientes l', 'l.id = lc.liquidacion_cliente_id')
                            ->join('flash_sucursales suc', 'l.sucursal_id = suc.id')
                            ->group_by('l.sucursal_id, g.id')
                            ->order_by('suc.nombre,g.nombre')
                            ->get('flash_liquidaciones_comprobantes lc');
                //echo($this->db->last_query());die;
                $query_grupos = $this->db
                        ->select('  id, nombre')
                        ->order_by('nombre')
                        ->get('flash_servicios_grupos');
                
                $query_sucursales = $this->db
                        ->select('  id, nombre')
                        ->order_by('nombre')
                        ->get('flash_sucursales');
                $json_cantidad = $query_cantidad->result();
                $json_ventas = $query_ventas->result();
                $json_grupos = $query_grupos->result();
                $json_sucursales = $query_sucursales->result();
                $cantidades = [];
                $ventas = [];
                $precios_promedio = [];
                $tiene_cantidad =  false;
                $tiene_ventas =  false;
                $tiene_precio_promedio =  false;
                //$tiene_pendiente =  false;
                $matriz_resultatos = null;
                $fila = 3;
                $columna = "B";
                foreach ($json_grupos as $grupo) {
                    foreach ($json_sucursales as $sucursal) {
                        //echo $sucursal->nombre." - ".$grupo->nombre;
                        foreach ($json_cantidad as $cantidad) {
                                if($grupo->id == $cantidad->grupo_id && $sucursal->id == $cantidad->sucursal_id){
                                    //echo " ".$ingreso->cantidad." - ";
                                    array_push($cantidades, $cantidad->cantidad);
                                    $tiene_cantidad = true; break;
                                }
                        }
                        foreach ($json_ventas as $venta) {
                                if($grupo->id == $venta->grupo_id && $sucursal->id == $venta->sucursal_id /*&& $grupo->id == 1*/){
                                    //echo " ".$rendicion->cantidad." - ";
                                    array_push($ventas,$venta->ventas );
                                    if ($cantidades[count($cantidades)-1] > 0){
                                       // echo $ventas[count($ventas)-1]."   -   ".$cantidades[count($cantidades)-1]."<br/>";
                                        $division = ($ventas[count($ventas)-1] > 0)?$ventas[count($ventas)-1] /$cantidades[count($cantidades)-1]:0;
                                    }else{
                                    $division = 0;}
                                    //var_dump($division);
                                    array_push($precios_promedio, (round($division,3)));
                                    $tiene_ventas = TRUE;
                                    $tiene_precio_promedio = TRUE;
                                    break;
                                }
                               
                        }         
//                      
                       
                        if (!$tiene_cantidad){ array_push($cantidades, '0');}
                        if (!$tiene_ventas) {array_push($ventas, '0');}
                        if (!$tiene_precio_promedio){ array_push($precios_promedio, '0');}

                        $matriz_resultatos[$columna][$fila] = $cantidades[count($cantidades)-1];
                        $columna++;
                        $matriz_resultatos[$columna][$fila] = $ventas[count($ventas)-1];
                        $columna++;
                        $matriz_resultatos[$columna][$fila] = $ventas[count($precios_promedio)-1];
                        $columna++;
                        $tiene_cantidad =  false;
                        $tiene_ventas =  false;
                        $tiene_precio_promedio =  false;
                    }
                    $fila++;
                    $columna = "B";
                }
                
                $totales_columnas = [];
                end($matriz_resultatos);
                
                for ($c = "B"; $c <= key($matriz_resultatos) ; $c++){
                    for($f = 0; $f <= count($matriz_resultatos) ; $f++){
                        $suma += $matriz_resultatos[$c][$f];
                        
                    }
                    array_push($totales_columnas, $suma);
                    $suma = 0;
                }
                // die;
                 //var_dump($totales_columnas);die;
                 if($json_cantidad && $json_ventas) 
                    echo json_encode(array('cantidades' => $cantidades,
                                            'ventas'=> $ventas, 
                                            'precios_promedio'=> $precios_promedio,
                                            'sucursales'=> $json_sucursales,
                                            'grupos'=> $json_grupos,
                                            'totales_columnas'=> $totales_columnas,));
                else echo json_encode(array('status' => 'none'));
        }
        
        function getListadoVtasClientes(){
            $periodo_desde = $this->input->post('periodo_desde') != ''?$this->input->post('periodo_desde'):false;
            $periodo_hasta = $this->input->post('periodo_hasta') != ''?$this->input->post('periodo_hasta'):false;
            $date_desde = new DateTime($periodo_desde);
            $date_hasta = new DateTime($periodo_hasta);
            //var_dump($_POST);die;
            if($periodo_desde){
                $query_vtas_clientes = $this->db->where('lc.fecha>= ', $date_desde->format('Y-m-d 00:00:00'));
            }
            if($periodo_hasta){
                $query_vtas_clientes = $this->db->where('lc.fecha<= ', $date_hasta->format('Y-m-d 23:59:59'));
            }
                $query_vtas_clientes = $this->db
                        ->select('  c.nombre cliente,
                                        SUM(lc.cant_a_liquidar) cantidad, 
                                        SUM(lc.precio*lc.cant_a_liquidar) ventas, 
                                        (SUM(lc.precio*lc.cant_a_liquidar))/SUM(lc.cant_a_liquidar) precio_promedio
                                ')
                        ->join('flash_liquidaciones_clientes l', 'l.id = lc.liquidacion_cliente_id')
                        ->join('flash_sucursales suc', 'l.sucursal_id = suc.id')
                        ->join('flash_clientes c', 'l.cliente_id = c.id')
                        ->group_by('c.id')
                        ->order_by('c.nombre')
                        ->get('flash_liquidaciones_comprobantes lc');
                //echo($this->db->last_query());die;
                $json_vtas_clientes = $query_vtas_clientes->result();

                if($json_vtas_clientes) 
                    echo json_encode(array('vtas_clientes' => $json_vtas_clientes,
                        ));
                else echo json_encode(array('status' => 'none'));
        }
        
        /*function getListadoIngresosFueraTiempo(){
            $dias = $this->input->post('dias');
            $query_periodo_hasta =   'SELECT DATE_SUB(NOW(), INTERVAL '.$dias.' DAY) dias ';
            //$date_hasta = $this->db->result($query_periodo_hasta
            $date_hasta = $this->db->query($query_periodo_hasta)->result();
            //var_dump($_POST);die;
            $query_ingresos = $this->db
                        ->select('  c.nombre cliente, s.nombre servicio,
                                    ci.sucursal_id, 
                                    suc.nombre sucursal,
                                    ci.numero, 
                                    DATE_FORMAT(ci.fecha_pedido,"%d-%m-%Y") fecha_pedido, 
                                    ci.cantidad , 
                                    COUNT(*) rendidas, 
                                    (ci.cantidad-COUNT(*)) pendientes, 
                                    DATE_FORMAT(MAX(r.create),"%d-%m-%Y") ult_fecha_rendicion')
                        ->join('flash_piezas p', 'ci.id = p.comprobante_ingreso_id')
                        ->join('flash_rendiciones_piezas rp', 'p.id = rp.pieza_id')
                        ->join('flash_rendiciones r', 'r.id = rp.rendicion_id')
                        ->join('flash_sucursales suc', 'ci.sucursal_id = suc.id')
                        ->join('flash_clientes c', 'ci.cliente_id = c.id')
                        ->join('flash_comprobantes_ingresos_servicios cis', 'ci.id = cis.comprobante_ingreso_id')
                        ->join('flash_servicios s', 'cis.servicio_id = s.id')
                        ->where('ci.create <= ', $date_hasta[0]->dias)
                        ->where('s.`grupo_id` <> 4')
                        ->group_by('ci.sucursal_id, `ci`.`id`, s.id')
                        ->order_by('suc.nombre, ci.numero, ci.fecha_pedido')
                        ->get('flash_comprobantes_ingresos ci');
                //echo($this->db->last_query());die;
                $json_ingresos = $query_ingresos->result();

                if($json_ingresos) 
                    echo json_encode(array('vtas_clientes' => $json_ingresos,
                        ));
                else echo json_encode(array('status' => 'none'));
        }*/
        
        function getListadoPreciosClientes(){
            $periodo_desde = $this->input->post('periodo_desde') != ''?$this->input->post('periodo_desde'):false;
            $periodo_hasta = $this->input->post('periodo_hasta') != ''?$this->input->post('periodo_hasta'):false;
            $date_desde = new DateTime($periodo_desde);
            $date_hasta = new DateTime($periodo_hasta);
            //var_dump($_POST);die;
            if($periodo_desde){
                $query_precios_clientes = $this->db->where('ci.create >= ', $date_desde->format('Y-m-d 00:00:00'));
            }
            if($periodo_hasta){
                $query_precios_clientes = $this->db->where('ci.create <= ', $date_hasta->format('Y-m-d 23:59:59'));
            }
                $query_precios_clientes = $this->db
                        ->select('  ci.sucursal_id sucursal,
                                    suc.nombre sucursal, 
                                    ci.cliente_id, 
                                    c.nombre cliente, 
                                    s.nombre servicio, 
                                    sg.nombre grupo , 
                                    sum(ci.cantidad) cantidad, 
                                    pe.precio precio_unitario
                                ')
                        ->join('flash_comprobantes_ingresos_servicios cis', 'ci.id = cis.comprobante_ingreso_id')
                        ->join('flash_servicios s', 'cis.servicio_id = s.id')
                        ->join('flash_clientes_precios_especiales pe', 'pe.servicio_id = s.id and pe.cliente_id = ci.cliente_id')
                        ->join('flash_clientes c', 'c.id = ci.cliente_id')
                        ->join('flash_servicios_grupos sg', 'sg.id = s.grupo_id')
                        ->join('flash_sucursales suc', 'suc.id = ci.sucursal_id')
                        ->group_by('ci.cliente_id, cis.servicio_id, ci.id')
                        ->order_by('suc.nombre, c.nombre')
                        ->get('flash_comprobantes_ingresos ci');
                //echo($this->db->last_query());die;
                $json_precios_clientes = $query_precios_clientes->result();

                if($json_precios_clientes) 
                    echo json_encode(array('vtas_clientes' => $json_precios_clientes,
                                            'consulta_escrita' =>$this->db->last_query()));
                else echo json_encode(array('status' => 'none'));
        }
        
        function getListadoFacturacionSucursal(){
            $periodo_desde = $this->input->post('periodo_desde') != ''?$this->input->post('periodo_desde'):false;
            $periodo_hasta = $this->input->post('periodo_hasta') != ''?$this->input->post('periodo_hasta'):false;
            $date_desde = new DateTime($periodo_desde);
            $date_hasta = new DateTime($periodo_hasta);
            //var_dump($_POST);die;
            if($periodo_desde){
                $query_facturacion_sucursal = $this->db->where('ld.fecha>= ', $date_desde->format('Y-m-d 00:00:00'));
            }
            if($periodo_hasta){
                $query_facturacion_sucursal = $this->db->where('ld.fecha<= ', $date_hasta->format('Y-m-d 23:59:59'));
            }
                $query_facturacion_sucursal = $this->db
                        ->select('  suc.nombre sucursal, FORMAT(SUM(ld.cantidad),0,"de_DE") cantidad, FORMAT(SUM(ld.cantidad*ld.precio),2,"de_DE") facturacion ')
                        ->join('flash_liquidaciones_detalles ld', 'l.id = ld.liquidacion_id')
                        ->join('flash_sucursales suc', 'suc.id = l.sucursal_id')
                        ->group_by('l.sucursal_id')
                        ->order_by('suc.nombre')
                        ->get('flash_liquidaciones l');
                //echo($this->db->last_query());die;
                $json_facturacion_sucursal = $query_facturacion_sucursal->result();

                if($json_facturacion_sucursal) 
                    echo json_encode(array('facturacion_sucursal' => $json_facturacion_sucursal,));
                else echo json_encode(array('status' => 'none'));
        }
        
        function getListadoDespachos(){
            $periodo_desde = $this->input->post('periodo_desde') != ''?$this->input->post('periodo_desde'):false;
            $periodo_hasta = $this->input->post('periodo_hasta') != ''?$this->input->post('periodo_hasta'):false;
            $date_desde = new DateTime($periodo_desde);
            $date_hasta = new DateTime($periodo_hasta);
            //var_dump($_POST);die;
            if($periodo_desde){
                $query_facturacion_sucursal = $this->db->where('l.create >= ', $date_desde->format('Y-m-d 00:00:00'));
            }
            if($periodo_hasta){
                $query_facturacion_sucursal = $this->db->where('l.create <= ', $date_hasta->format('Y-m-d 23:59:59'));
            }
                $query_facturacion_sucursal = $this->db
                        ->select('  suc.nombre sucursal, SUM(ld.cantidad) cantidad, SUM(ld.cantidad*ld.precio) facturacion ')
                        ->join('flash_liquidaciones_detalles ld', 'l.id = ld.liquidacion_id')
                        ->join('flash_sucursales suc', 'suc.id = l.sucursal_id')
                        ->group_by('l.sucursal_id')
                        ->order_by('suc.nombre')
                        ->get('flash_liquidaciones l');
                //echo($this->db->last_query());die;
                $json_facturacion_sucursal = $query_facturacion_sucursal->result();

                if($json_facturacion_sucursal) 
                    echo json_encode(array('facturacion_sucursal' => $json_facturacion_sucursal,));
                else echo json_encode(array('status' => 'none'));
        }
}

/* End of file Piezas.php */
/* Location: ./system/application/controllers/Piezas.php */