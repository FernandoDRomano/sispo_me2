<?php
class Piezas extends CI_Controller {
	function __construct() {
		parent::__construct();
	}
	public function comprobantesGenerados(){
		$json = $this->codegen_model->get('flash_comprobantes_ingresos_generados','id, numero AS name','');
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}
	public function estados($id){ 
		$json = $this->codegen_model->get('flash_piezas_estados_variables','*','pieza_estado_id = '.$id);
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}
        public function analizarCsv(){
            //echo $path;die;
            if (count($_FILES) == 0) {
                 echo json_encode(array('error' => ' Seleccione el archivo a cargar con el botón BROWSE.'));return;
            }
            $servicio_cantidad_disponible =$this->input->post('servicio_cantidad_disponible');
            $path = $_SERVER['DOCUMENT_ROOT'] .'/assets/upload/';
            $file_name = 'piezas'.date('Y_m_d_His').'.csv';
            $config['upload_path']   = $path;
            $config['allowed_types'] = 'csv';
            $config['max_size']      = 1024;
            $config['max_width']     = 1024;
            $config['max_height']    = 1024;
            $config['enclosure']    = ';';
            //echo $path.$file_name;die;
            $this->load->library('upload', $config);
            //Copio el archivo en el direcorio de destino con el nombre que quiero
            move_uploaded_file( $_FILES['file']['tmp_name'], $path.$file_name);//base_url().'assets/upload/piezas_fechayHora.csv';
            $file = fopen($path.$file_name,"r");
            $cantidad_piezas = 0;
            $piezas = null;
            while(! feof($file))
              {
                $piezas[$cantidad_piezas] = (fgetcsv($file, 10000,";"));
                $cantidad_piezas++;
              }
            fclose($file);
            $respuesta = null;
            if (intval($cantidad_piezas) > intval($this->input->post('cantidad_servicios_'.$this->input->post('servicio_id'))+intval(1))){//+1 para tener en cuenta el encabezado
                echo json_encode(array('error' => ' No dispone la cantidad ingresada para este servicio.'));
            }
            else{
                $comprobante_servicio = $this->codegen_model->row('flash_comprobantes_ingresos_servicios','*','id = '.$this->input->post('servicio_id'));
                $servicio = $this->codegen_model->row('flash_servicios','*','id = '.$comprobante_servicio->servicio_id);
                $respuesta['servicio']['nombre'] = $servicio->nombre;
                $respuesta['servicio']['cantidad_piezas'] = count($respuesta);
                $respuesta['servicio']['filename'] = $file_name;
                echo json_encode($respuesta);
            }
	}
        public function analizarXls(){
            ini_set("memory_limit", "-1");
            set_time_limit(0);
            if (count($_FILES) == 0) {
                 echo json_encode(array('error' => ' Seleccione el archivo a cargar con el bot��n BROWSE.'));return;
            }
            $servicio_cantidad_disponible = $this->input->post('servicio_cantidad_disponible');
            $path = "";
            if($_SERVER['HTTP_HOST'] == "localhost"){// For local
                $path = $_SERVER['DOCUMENT_ROOT'] .'gestionpostal/assets/upload/';
            }
            else{ // For Web
                $path = $_SERVER['DOCUMENT_ROOT'] .'/assets/upload/';
            }
            $file_name = 'piezas'.date('Y_m_d_His').'.xls';
            $config['upload_path']   = $path;
            $config['allowed_types'] = 'xls';
            $config['max_size']      = 1024;
            $config['max_width']     = 1024;
            $config['max_height']    = 1024;
            $config['enclosure']    = ';';
            //echo $path.$file_name;die;
            $this->load->library('upload', $config);
            //Copio el archivo en el direcorio de destino con el nombre que quiero
            move_uploaded_file( $_FILES['file']['tmp_name'], $path.$file_name);//base_url().'assets/upload/piezas_fechayHora.csv';
            $this->load->library('excel');
            $objReader = PHPExcel_IOFactory::createReader('Excel5');
            $objReader->setReadDataOnly(true);
            $objPHPExcel = $objReader->load($path.$file_name);
            $objWorksheet = $objPHPExcel->getActiveSheet();
            $highestColumm = $objPHPExcel->setActiveSheetIndex(0)->getHighestColumn();
            $highestRow = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
            $falta_barcode = false;
            foreach ($objWorksheet->getRowIterator() as $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false); // This loops all cells,
                // even if it is not set.
                // By default, only cells
                // that are set will be
                // iterated. Itero las columnas
                foreach ($cellIterator as $cell) {
                    $cell->getValue();
                    $rowIndex = $row->getRowIndex();
                    $codigo = $objWorksheet->getCell('E' . $rowIndex)->getValue();
                    if($codigo == ""){
                        $falta_barcode = true;
                    }
                }
            }
            $cantidad_piezas = $highestRow - 1; //Resto el titulo de la cantidad de filas
            $respuesta = null;
            if (intval($cantidad_piezas) > intval($this->input->post('cantidad_servicios_'.$this->input->post('servicio_id'))+intval(1))){//+1 para tener en cuenta el encabezado
                echo json_encode(array('error' => ' No dispone la cantidad ingresada para este servicio.'));
            }
            else{
                $comprobante_servicio = $this->codegen_model->row('flash_comprobantes_ingresos_servicios','*','id = '.$this->input->post('servicio_id'));
                $servicio = $this->codegen_model->row('flash_servicios','*','id = '.$comprobante_servicio->servicio_id);
                $mensaje = "";
                if($servicio->acuse == 1 && $falta_barcode){
                    $mensaje = "Existen piezas que no poseen CODIGO de BARRA";
                    echo json_encode(array('error' => ' Revise el Excel, hay piezas SIN CODIGO DE BARRAS.'));
                }else{
                    $respuesta['servicio']['nombre'] = $servicio->nombre;
                    $respuesta['servicio']['cantidad_piezas'] = $cantidad_piezas;
                    $respuesta['servicio']['filename'] = $file_name;
                    $respuesta['servicio']['mensaje'] = $mensaje;
                    echo json_encode($respuesta);
                }
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
        public function traerSimplesDisponiblesPorComprobanteServicio(){
                $Comprobante_ingreso =  Comprobante::whereNumero($this->input->post('codigo_barra'))->first();
                $servicio_id =  $this->input->post('servicio_id');
                //Traigo las piezas disponibles solo de las que tiene el usuario en su sucursal
                $user_row = $this->ion_auth->user()->row();
                if (!$this->ion_auth->in_group(array(1, 2), $user_row->id)) $query = $this->db->where('p.sucursal_id', $user_row->sucursal_id);
                $query = $this->db
                        ->select('COUNT(p.id) AS cantidad')
                        ->join('flash_comprobantes_ingresos_servicios cis', 'cis.id = p.servicio_id ')
                        ->where('p.comprobante_ingreso_id = '.$Comprobante_ingreso->id)
                        ->where('cis.servicio_id = '.$servicio_id)
                        ->where('p.tipo_id = '.Pieza::TIPO_SIMPLE)
                        ->where('p.estado_id IN ('.Pieza::ESTADO_EN_DISTRIBUCION.','.Pieza::ESTADO_EN_GESTION.','.Pieza::ESTADO_NO_RESPONDE.','.Pieza::ESTADO_EN_TRNSITO.')')
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
            echo($this->db->last_query());die;
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
            ini_set('memory_limit', '-1');
            $codigo_barras_id =  $this->input->post('codigo_barra');
            $cantidad = $this->input->post('cantidad');
            $cargados = $this->input->post('cargados');
            $estados_id = $this->input->post('estados_id');
            $comprobante_servicio_id =  $this->input->post('servicio_id');
            if ($cargados == '') $cargados = 0;
            /*$cargados = preg_split("/[\s]+/", $cargados);
            $this->db->group_start();
            $sale_ids_chunk = array_chunk($cargados,25);
            foreach($sale_ids_chunk as $sale_ids)
            {
                $this->db->where_not_in('p.id', $sale_ids);
            }
            $this->db->group_end();*/
            //Solo se trae las piezas que esten en la sucursal fisicamente
            $user_row = $this->ion_auth->user()->row();
            if (!$this->ion_auth->in_group(array(1, 2), $user_row->id)) $query = $this->db->where('p.sucursal_id', $user_row->sucursal_id);
            $query = $this->db
                    ->select(' p.barcode, p.id, p.destinatario, p.domicilio, p.localidad, p.codigo_postal, s.nombre as servicio, p.barcode_externo')
                    ->join('flash_comprobantes_ingresos_servicios cis', 'p.servicio_id = cis.id')
                    ->join('flash_comprobantes_ingresos ci', 'ci.id = cis.comprobante_ingreso_id')
                    ->join('flash_servicios s', 's.id = cis.servicio_id')
                    ->join('flash_subpiezas sp', 'sp.pieza_id = p.id','left')
                    ->join('flash_piezas_despacho_piezas pdp', 'pdp.pieza_id = p.id','left')
                    ->join('flash_piezas_despacho pd', 'pd.id = pdp.despacho_id','left')
                    ->join('flash_piezas_estados_variables ev', 'ev.id = p.estado_id')
                    ->where('ci.numero = '.$codigo_barras_id)
                    ->where('ev.pieza_estado_id NOT IN (2)')//CAMBIADO 23_07_2017->where('p.estado_id IN ( '.$estados_id.')' )
                    ->where('p.tipo_id = '.Pieza::TIPO_SIMPLE)
                    ->where('sp.id IS NULL')
                    ->where('(pdp.id is null  OR pdp.id IS NOT NULL AND pd.estado = 3)')
                    ->where('cis.id = '.$comprobante_servicio_id)
                    ->limit($cantidad)
                    ->get('flash_piezas p');
            $json = $query->result();
            //echo $this->db->last_query();die;
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
        	// ini_set('memory_limit', '-1');
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
            /* Solucion temporal solo traer los 2 ultomos meses */
            $query = $this->db->where('ci.create >= date_sub(curdate(), interval 2 month)');
            /* END: Solucion temporal solo traer los 2 ultomos meses */
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
                                        p.estado_id AS estado_actual_id ,
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
                            ->join('flash_piezas_estados_variables ev', 'ev.id = p.estado_id','left')
                            ->join('flash_rendiciones_piezas rp', 'p.id =rp.pieza_id','left')
                            ->where('p.tipo_id = 2')
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
        function getPieza(){
//            var_dump($_POST);
            $codigo = (int)$this->input->post('codigo_barra');
            $codigo_str = '"'.$this->input->post('codigo_barra').'"';
            $piezas_cargadas = $this->input->post('piezas_ids');
            $buscar_por = $this->input->post('buscar_por');
            //Traigo los datos de la pieza dependiendo si se busca por ID o CODIGO EXTERNO
            if (intval($buscar_por) == 1) $pieza = $this->codegen_model->row('flash_piezas','id','id = '.$codigo);
            if (intval($buscar_por) == 2) $pieza = $this->codegen_model->row('flash_piezas','id','barcode_externo = '.$codigo_str);
            if ($pieza == NULL){
                echo json_encode(array('status' => 'La pieza no esta disponible'));
                return;
            }
            //Chequeo si la pieza ya fue agregada a la HDR
            $piezas_cargadas_array = explode(',',$piezas_cargadas);
            if (in_array($pieza->id, $piezas_cargadas_array)){ echo json_encode(array('status' => 'La pieza ya fue agregada'));return;}
            $query = $this->db
                        ->select('p.barcode_externo barcode_externo,p.id pieza_id, s.nombre servicio, p.domicilio domicilio, p.codigo_postal codigo_postal,p.localidad, p.destinatario')
                        ->join('flash_comprobantes_ingresos ci', 'ci.id = p.comprobante_ingreso_id')
                        ->join('flash_clientes c', 'c.id = ci.cliente_id')
                        ->join('flash_comprobantes_ingresos_servicios cis', 'cis.id = p.servicio_id')
                        ->join('flash_servicios s', 'cis.servicio_id = s.id')
                        ->join('flash_subpiezas sp', 'p.id = sp.pieza_id', 'left')
                        ->where('sp.id IS NULL')
                        ->where('p.estado_id not in (SELECT id FROM flash_piezas_estados_variables WHERE pieza_estado_id = 2 )')//En Disdribucion
                        ->where('p.id = '.$pieza->id)
                        ->get('flash_piezas p');
                    //  echo $this->db->last_query();die;
            $json = $query->result();
            if($json){ echo json_encode($json);
            }else{
                    echo json_encode(array('status' => 'La pieza no esta disponible'));
                }
            }
        function getPiezasSimplesDisponiblesHDR(){
//            var_dump($_POST);
            //ini_set('memory_limit', '264M');
            $comprobante_ingreso_numero =  $this->input->post('codigo_barra_simple');
            $cantidad = $this->input->post('cantidad');
            $cargados = $this->input->post('cargados');
            //$estados_id = $this->input->post('estados_id');
            $comprobante_servicio_id =  $this->input->post('servicio_id');
//            if ($cargados == '') $cargados = 0;
//
//            $cargados = preg_split("/[\s]+/", $cargados);
//            $this->db->group_start();
//            $sale_ids_chunk = array_chunk($cargados,25);
//            foreach($sale_ids_chunk as $sale_ids)
//            {
//                $this->db->where_not_in('p.id', $sale_ids);
//            }
//            $this->db->group_end();
            $query = $this->db
                    ->select(' p.barcode, p.id, p.destinatario, p.domicilio, p.localidad, p.codigo_postal, s.nombre as servicio, p.barcode_externo')
                    ->join('flash_comprobantes_ingresos_servicios cis', 'p.servicio_id = cis.id')
                    ->join('flash_comprobantes_ingresos ci', 'ci.id = cis.comprobante_ingreso_id')
                    ->join('flash_servicios s', 's.id = cis.servicio_id')
                    ->join('flash_subpiezas sp', 'sp.pieza_id = p.id','left')
                    ->where('ci.numero = '.$comprobante_ingreso_numero)
                    ->where('p.estado_id not in (SELECT id FROM flash_piezas_estados_variables WHERE pieza_estado_id = 2 )')
                    ->where('p.tipo_id = '.Pieza::TIPO_SIMPLE)
                     ->where('sp.id IS NULL')
                    ->where('cis.id = '.$comprobante_servicio_id)
                    ->limit($cantidad)
                    ->get('flash_piezas p');
            //traigo solo los ids de las piezas simples para llevarlos en un string a la grabacion
            $query_ids = $this->db
                    ->select('p.id')
                    ->join('flash_comprobantes_ingresos_servicios cis', 'p.servicio_id = cis.id')
                    ->join('flash_comprobantes_ingresos ci', 'ci.id = cis.comprobante_ingreso_id')
                    ->join('flash_servicios s', 's.id = cis.servicio_id')
                    ->join('flash_subpiezas sp', 'sp.pieza_id = p.id','left')
                    ->where('ci.numero = '.$comprobante_ingreso_numero)
                    ->where('p.estado_id not in (SELECT id FROM flash_piezas_estados_variables WHERE pieza_estado_id = 2 )')
                    ->where('p.tipo_id = '.Pieza::TIPO_SIMPLE)
                     ->where('sp.id IS NULL')
                    ->where('cis.id = '.$comprobante_servicio_id)
                    ->limit($cantidad)
                    ->get('flash_piezas p');
           // echo $this->db->last_query();die;
           // $json = $query->result();
            $json_ids = $query_ids->result();
            $piezas_simples_ids = "";
            foreach ($json_ids as $value) {
                if ($piezas_simples_ids == ""){
                    $piezas_simples_ids = $value->id;
                }else{
                    $piezas_simples_ids .= ",".$value->id;
                }
            }
            //echo count($json_ids);die;
            if($json_ids) echo json_encode(array('piezas_simples_ids' => $piezas_simples_ids,'piezas_simples'=>$json_ids));
            else echo json_encode(array('status' => 'none'));
        }
        function getPiezasSimplesDisponiblesHDR_HDR(){
//            var_dump($_POST);
            $comprobante_ingreso_numero =  $this->input->post('codigo_barra_simple');
            $cantidad = $this->input->post('cantidad');
            $cargados = $this->input->post('cargados');
            //$estados_id = $this->input->post('estados_id');
            $comprobante_servicio_id =  $this->input->post('servicio_id');
//            if ($cargados == '') $cargados = 0;
//
//            $cargados = preg_split("/[\s]+/", $cargados);
//            $this->db->group_start();
//            $sale_ids_chunk = array_chunk($cargados,25);
//            foreach($sale_ids_chunk as $sale_ids)
//            {
//                $this->db->where_not_in('p.id', $sale_ids);
//            }
//            $this->db->group_end();
            $query = $this->db
                    ->select(' p.barcode, p.id, p.destinatario, p.domicilio, p.localidad, p.codigo_postal, s.nombre as servicio, p.barcode_externo')
                    ->join('flash_comprobantes_ingresos_servicios cis', 'p.servicio_id = cis.id')
                    ->join('flash_comprobantes_ingresos ci', 'ci.id = cis.comprobante_ingreso_id')
                    ->join('flash_servicios s', 's.id = cis.servicio_id')
                    ->join('flash_subpiezas sp', 'sp.pieza_id = p.id','left')
                    ->where('ci.numero = '.$comprobante_ingreso_numero)
                    ->where('p.estado_id not in (SELECT id FROM flash_piezas_estados_variables WHERE pieza_estado_id = 2 )')
                    ->where('p.tipo_id = '.Pieza::TIPO_SIMPLE)
                     ->where('sp.id IS NULL')
                    ->where('cis.id = '.$comprobante_servicio_id)
                    ->limit($cantidad)
                    ->get('flash_piezas p');
            //traigo solo los ids de las piezas simples para llevarlos en un string a la grabacion
            $query_ids = $this->db
                    ->select('p.id')
                    ->join('flash_comprobantes_ingresos_servicios cis', 'p.servicio_id = cis.id')
                    ->join('flash_comprobantes_ingresos ci', 'ci.id = cis.comprobante_ingreso_id')
                    ->join('flash_servicios s', 's.id = cis.servicio_id')
                    ->join('flash_subpiezas sp', 'sp.pieza_id = p.id','left')
                    ->where('ci.numero = '.$comprobante_ingreso_numero)
                    ->where('p.estado_id not in (SELECT id FROM flash_piezas_estados_variables WHERE pieza_estado_id = 2 )')
                    ->where('p.tipo_id = '.Pieza::TIPO_SIMPLE)
                     ->where('sp.id IS NULL')
                    ->where('cis.id = '.$comprobante_servicio_id)
                    ->limit($cantidad)
                    ->get('flash_piezas p');
            //  echo $this->db->last_query();die;
            //$json = $query->result();
            $json_ids = $query_ids->result();
            $piezas_simples_ids = "";
            foreach ($json_ids as $value) {
                if ($piezas_simples_ids == ""){
                    $piezas_simples_ids = $value->id;
                }else{
                    $piezas_simples_ids .= ",".$value->id;
                }
            }
            $json = $query->result();
            if($json) echo json_encode($json);
            else echo json_encode(array('status' => 'none'));
        }
        function getCantidadPiezasSimplesDisponiblesHDR_HDR(){
//            var_dump($_POST);
            $comprobante_ingreso_numero =  $this->input->post('codigo_barra_simple');
            $cantidad = $this->input->post('cantidad');
            $cargados = $this->input->post('cargados');
            //$estados_id = $this->input->post('estados_id');
            $comprobante_servicio_id =  $this->input->post('servicio_id');
//            if ($cargados == '') $cargados = 0;
//
//            $cargados = preg_split("/[\s]+/", $cargados);
//            $this->db->group_start();
//            $sale_ids_chunk = array_chunk($cargados,25);
//            foreach($sale_ids_chunk as $sale_ids)
//            {
//                $this->db->where_not_in('p.id', $sale_ids);
//            }
//            $this->db->group_end();
            $query = $this->db
                    ->select(' p.barcode, p.id, p.destinatario, p.domicilio, p.localidad, p.codigo_postal, s.nombre as servicio, p.barcode_externo')
                    ->join('flash_comprobantes_ingresos_servicios cis', 'p.servicio_id = cis.id')
                    ->join('flash_comprobantes_ingresos ci', 'ci.id = cis.comprobante_ingreso_id')
                    ->join('flash_servicios s', 's.id = cis.servicio_id')
                    ->join('flash_subpiezas sp', 'sp.pieza_id = p.id','left')
                    ->where('ci.numero = '.$comprobante_ingreso_numero)
                    ->where('p.estado_id not in (SELECT id FROM flash_piezas_estados_variables WHERE pieza_estado_id = 2 )')
                    ->where('p.tipo_id = '.Pieza::TIPO_SIMPLE)
                     ->where('sp.id IS NULL')
                    ->where('cis.id = '.$comprobante_servicio_id)
                    ->limit($cantidad)
                    ->get('flash_piezas p');
            //traigo solo los ids de las piezas simples para llevarlos en un string a la grabacion
            $query_ids = $this->db
                    ->select('p.id')
                    ->join('flash_comprobantes_ingresos_servicios cis', 'p.servicio_id = cis.id')
                    ->join('flash_comprobantes_ingresos ci', 'ci.id = cis.comprobante_ingreso_id')
                    ->join('flash_servicios s', 's.id = cis.servicio_id')
                    ->join('flash_subpiezas sp', 'sp.pieza_id = p.id','left')
                    ->where('ci.numero = '.$comprobante_ingreso_numero)
                    ->where('p.estado_id not in (SELECT id FROM flash_piezas_estados_variables WHERE pieza_estado_id = 2 )')
                    ->where('p.tipo_id = '.Pieza::TIPO_SIMPLE)
                     ->where('sp.id IS NULL')
                    ->where('cis.id = '.$comprobante_servicio_id)
                    ->limit($cantidad)
                    ->get('flash_piezas p');
            //  echo $this->db->last_query();die;
            //$json = $query->result();
            $json_ids = $query_ids->result();
            $piezas_simples_ids = "";
            foreach ($json_ids as $value) {
                if ($piezas_simples_ids == ""){
                    $piezas_simples_ids = $value->id;
                }else{
                    $piezas_simples_ids .= ",".$value->id;
                }
            }
            $json = $query->result();
            if($json) echo json_encode($json);
            else echo json_encode(array('status' => 'none'));
        }
        function getPiezasSimplesDisponiblesDespachos(){
//            var_dump($_POST);
            //ini_set('memory_limit', '264M');
            $comprobante_ingreso_numero =  $this->input->post('codigo_barra_simple');
            $cantidad = $this->input->post('cantidad');
            $cargados = $this->input->post('cargados');
            //$estados_id = $this->input->post('estados_id');
            $comprobante_servicio_id =  $this->input->post('servicio_id');
//            if ($cargados == '') $cargados = 0;
//
//            $cargados = preg_split("/[\s]+/", $cargados);
//            $this->db->group_start();
//            $sale_ids_chunk = array_chunk($cargados,25);
//            foreach($sale_ids_chunk as $sale_ids)
//            {
//                $this->db->where_not_in('p.id', $sale_ids);
//            }
//            $this->db->group_end();
//            $query = $this->db
//                    ->select(' p.barcode, p.id, p.destinatario, p.domicilio, p.localidad, p.codigo_postal, s.nombre as servicio, p.barcode_externo')
//                    ->join('flash_comprobantes_ingresos_servicios cis', 'p.servicio_id = cis.id')
//                    ->join('flash_comprobantes_ingresos ci', 'ci.id = cis.comprobante_ingreso_id')
//                    ->join('flash_servicios s', 's.id = cis.servicio_id')
//                    ->join('flash_subpiezas sp', 'sp.pieza_id = p.id','left')
//                    ->where('ci.numero = '.$comprobante_ingreso_numero)
//                    ->where('p.estado_id not in (SELECT id FROM flash_piezas_estados_variables WHERE pieza_estado_id = 2 )')
//                    ->where('p.tipo_id = '.Pieza::TIPO_SIMPLE)
//                     ->where('sp.id IS NULL')
//                    ->where('cis.id = '.$comprobante_servicio_id)
//                    ->limit($cantidad)
//                    ->get('flash_piezas p');
             //echo $this->db->last_query();die;
            //traigo solo los ids de las piezas simples para llevarlos en un string a la grabacion
//            $query_ids = $this->db
//                    ->select('p.id')
//                    ->join('flash_comprobantes_ingresos_servicios cis', 'p.servicio_id = cis.id')
//                    ->join('flash_comprobantes_ingresos ci', 'ci.id = cis.comprobante_ingreso_id')
//                    ->join('flash_servicios s', 's.id = cis.servicio_id')
//                    ->join('flash_subpiezas sp', 'sp.pieza_id = p.id','left')
//                    ->join('flash_piezas_despacho_piezas dp', 'dp.pieza_id = p.id','left')
//                    ->join('flash_piezas_despacho pd', 'dp.despacho_id = pd.id')
//                    ->where('ci.numero = '.$comprobante_ingreso_numero)
//                    ->where('p.estado_id not in (SELECT id FROM flash_piezas_estados_variables WHERE pieza_estado_id = 2 )')
//                    ->where('p.tipo_id = '.Pieza::TIPO_SIMPLE)
//                    ->where('sp.id IS NULL')
//                    ->where('(dp.id IS NULL OR (dp.id is not null AND pd.estado = 3))')
//                    ->where('cis.id = '.$comprobante_servicio_id)
//                    ->limit($cantidad)
//                    ->get('flash_piezas p');
            $query_ids = $this->db
                    ->select(' p.id')
                    ->join('flash_comprobantes_ingresos_servicios cis', 'p.servicio_id = cis.id')
                    ->join('flash_comprobantes_ingresos ci', 'ci.id = cis.comprobante_ingreso_id')
                    ->join('flash_servicios s', 's.id = cis.servicio_id')
                    ->join('flash_subpiezas sp', 'sp.pieza_id = p.id','left')
                    ->join('flash_piezas_despacho_piezas pdp', 'pdp.pieza_id = p.id','left')
                    ->join('flash_piezas_despacho pd', 'pd.id = pdp.despacho_id','left')
                    ->where('ci.numero = '.$comprobante_ingreso_numero)
                    ->where('p.estado_id not in (SELECT id FROM flash_piezas_estados_variables WHERE pieza_estado_id = 2 )' )
                    ->where('p.tipo_id = '.Pieza::TIPO_SIMPLE)
                    ->where('sp.id IS NULL')
                    ->where('(pdp.id is null  OR pdp.id IS NOT NULL AND pd.estado = 3)')
                    ->where('cis.id = '.$comprobante_servicio_id)
                    ->limit($cantidad)
                    ->get('flash_piezas p');
            //echo $this->db->last_query();die;
           // $json = $query->result();
            $json_ids = $query_ids->result();
            $piezas_simples_ids = "";
            foreach ($json_ids as $value) {
                if ($piezas_simples_ids == ""){
                    $piezas_simples_ids = $value->id;
                }else{
                    $piezas_simples_ids .= ",".$value->id;
                }
            }
            //echo count($piezas_simples_ids);die;
            if($json_ids) echo json_encode(array('piezas_simples_ids' => $piezas_simples_ids,'piezas_simples'=>$json_ids));
            else echo json_encode(array('status' => 'none'));
        }
        /*function getPiezaParaNovedad(){//Trae todas las piezas excepto que esten en estado de rendicion o dentro del los IDS que ya se agregaron
            //var_dump($_POST);
            $codigo = (int)$this->input->post('codigo_barra');
            $codigo_str = '"'.$this->input->post('codigo_barra').'"';
            $piezas_cargadas = $this->input->post('piezas_ids');
            $piezas_cargadas_array = split(",", $piezas_cargadas);
            $buscar_por = $this->input->post('buscar_por');
            //Traigo los datos de la pieza
            //Traigo los datos de la pieza dependiendo si se busca por ID o CODIGO EXTERNO
            if (intval($buscar_por) == 1) $pieza = $this->codegen_model->row('flash_piezas','id','id = '.$codigo);
            if (intval($buscar_por) == 2) $pieza = $this->codegen_model->row('flash_piezas','id','barcode_externo = '.$codigo_str);
            if ($pieza == NULL){
                echo json_encode(array('status' => 'La pieza no esta disponible'));
                return;
            };
            if (in_array($pieza->id, $piezas_cargadas_array) ){
                echo json_encode(array('status' => 'La pieza ya esta en la lista'));
                return;
            }
            $query = $this->db
                        ->select('p.id pieza_id, p.domicilio, p.localidad, p.codigo_postal, ev.nombre estado')
                        ->join('flash_piezas_estados_variables ev', ' p.estado_id = ev.id')
                        ->join('flash_piezas_estados e', 'e.id = ev.pieza_estado_id')
                        ->where('e.id <> 2')
                        ->where('p.id = '.$pieza->id)
                        ->get('flash_piezas p');
                      //echo $this->db->last_query();die;
            $json = $query->row();
            if($json){ echo json_encode($json);
            }else{
                    echo json_encode(array('status' => 'La pieza no esta disponible'));
                }
            }
            */
        public function traerPiezasIgualesParaNovedad(){
            $codigo_barra = (int)$this->input->post('codigo_barra');
            $codigo_barra_str = '"'.$this->input->post('codigo_barra').'"';
            $user_row = $this->ion_auth->user()->row();
            if (!$this->ion_auth->in_group(array(1, 2), $user_row->id)) $query = $this->db->where('p.sucursal_id', $user_row->sucursal_id);
            $query = $this->db
                    ->select('ci.numero numero, p.barcode_externo,  p.id pieza_id')
                    ->join('flash_comprobantes_ingresos ci', 'p.comprobante_ingreso_id = ci.id')
                    ->join('flash_piezas_estados_variables ev', ' p.estado_id = ev.id')
                    ->join('flash_piezas_estados e', 'e.id = ev.pieza_estado_id')
                    ->where('p.id ='.$codigo_barra)
                    ->where('e.id <> 2')
                    ->group_by('ci.id')
                    ->get('flash_piezas p');
            $json = $query->result();
            if($json){
                echo json_encode(array('grilla' => $json));
            }else{
                if (!$this->ion_auth->in_group(array(1, 2), $user_row->id)) $query = $this->db->where('p.sucursal_id', $user_row->sucursal_id);
                $query = $this->db
                        ->select('ci.numero numero, p.barcode_externo,  p.id pieza_id')
                        ->join('flash_comprobantes_ingresos ci', 'p.comprobante_ingreso_id = ci.id')
                        ->join('flash_piezas_estados_variables ev', ' p.estado_id = ev.id')
                        ->join('flash_piezas_estados e', 'e.id = ev.pieza_estado_id')
                        ->where('p.barcode_externo ='.$codigo_barra_str)
                        ->where('e.id <> 2')
                        ->where('p.barcode_externo <> ""')
                        ->group_by('ci.id')
                        ->get('flash_piezas p');
//echo $this->db->last_query();die;
                $json = $query->result();
                if($json) echo json_encode(array('grilla' => $json));
                else echo json_encode(array('status' => 'La pieza no se encuentra disponible'));
            }
        }
        public function getLinksImpresionAcusesEtiquetas($comprobante_ingreso_id, $servicio_id)
        {
            //echo $comprobante_ingreso_id."   ----   ".$servicio_id;
            $piezas = $this->flash_md->getPiezasPorServiciosPorComprobanteSinGroup(/*44,71*/$comprobante_ingreso_id,  $servicio_id);
           // echo (count($piezas) / 500);
            $inicio = 0;
            $links = [];
            for ($i = 0; $i < count($piezas)/500 ; $i++) {
                array_push($links, $inicio);
                $inicio += 500;
            }
            //var_dump($links);die;
            if($piezas) echo json_encode(array('links' => $links));
                else echo json_encode(array('status' => 'No se econtraron piezas cargadas'));
            //var_dump(count($piezas));die;
        }
        
        function getPaqueteriaTipoXCliente($cliente_id){
            $query = $this->db
                    ->select(' pt.*')
                    ->join('flash_paqueteria_tipos pt', 'c.flash_paqueteria_tipo_id = pt.id')
                    ->where('c.id = '.$cliente_id)
                    ->get('flash_clientes c');
            $json = $query->result();
            if($json) echo json_encode(array('paqueteria' => $json));
            else echo json_encode(array('status' => 'none'));
        }
        
        
        
    public function modificar(){
        ini_set('memory_limit', '-1');
        $codigo_barra = $this->input->post('codigo_barra');
        if($codigo_barra == ''){
            echo json_encode(array('success' => 'La pieza: No Tiene identificador.'));
            exit;
        }
        $piezas_id = $this->input->post('piezas_id');
        $buscar_por = $this->input->post('buscar_por');
        $Anteponer = $this->input->post('Anteponer');
        $Destinatario = $this->input->post('Destinatario');
        $Domicilio = $this->input->post('Domicilio');
        $CP = $this->input->post('CP');
        $Localidad = $this->input->post('Localidad');
        $Barcode_externo = $this->input->post('Barcode_externo');
        $Datos_varios = $this->input->post('Datos_varios');
        $Datos_varios_1 = $this->input->post('Datos_varios_1');
        $Datos_varios_2 = $this->input->post('Datos_varios_2');
        $Datos_varios_3 = $this->input->post('Datos_varios_3');
        
        
        $user_row = $this->ion_auth->user()->row();
        $identificafor = '';
        if($buscar_por == 1){
            $identificafor = 'id';
            
        }else{
            $identificafor = 'barcode_externo';
        }
        $query = $this->db
        ->select('p.id pieza_id')
        ->where("p.$identificafor = '$codigo_barra'")
        ->get('flash_piezas as p');
        $json = $query->row();
        if ($json) {
            //echo json_encode($json);
            $query = $this->db
            ->select('p.id pieza_id')
            ->where("p.$identificafor = '$codigo_barra'")
            ->where('p.sucursal_id = '.$user_row->sucursal_id)
            ->get('flash_piezas as p');
            $json = $query->row();
            if ($json) {
                //echo json_encode($json);
                /*
                $query = $this->db
                ->select('p.id pieza_id')
                ->join('flash_piezas_estados_variables ev', 'p.estado_id = ev.id')
                ->where("p.$identificafor = '$codigo_barra'")
                ->where('p.sucursal_id = '.$user_row->sucursal_id)
                ->where('ev.pieza_estado_id <> '.PiezaEstado::ESTADOS_RENDICIONES)
                ->get('flash_piezas as p');
                */
                $json = $query->row();
                if ($json) {
                    //echo json_encode($json);
                    /*
                    $query = $this->db
                    ->select('p.id pieza_id')
                    ->join('flash_piezas_estados_variables ev', 'p.estado_id = ev.id')
                    ->where("p.$identificafor = '$codigo_barra'")
                    ->where('p.sucursal_id = '.$user_row->sucursal_id)
                    ->where('ev.pieza_estado_id <> '.PiezaEstado::ESTADOS_RENDICIONES)
                    ->get('flash_piezas as p');
                    $json = $query->row();
                    */
                    if ($json) {
                        $sql_update = "
                        UPDATE flash_piezas as p SET 
                        p.destinatario = '$Destinatario'
                        , p.domicilio = '$Domicilio'
                        , p.codigo_postal = '$CP'
                        , p.localidad = '$Localidad'
                        , p.barcode_externo = '$Barcode_externo'
                        , p.update_user_id = '$user_row->id'
                        , p.datos_varios = '$Datos_varios'
                        , p.datos_varios_1 = '$Datos_varios_1'
                        , p.datos_varios_2 = '$Datos_varios_2'
                        , p.datos_varios_3 = '$Datos_varios_3'
                        
                        WHERE p.$identificafor = '$codigo_barra'";
                        $Retorno = $this->db->query($sql_update);
                        $MSJ = print_r($this->input->post(),true);
                        //print_r($MSJ);
                        if($Retorno){
                            /*
                            $data     = array(
                                'user_id'     => $user_row->id,
                                'categoria'   => 'modificar_piezas',
                                'descripcion' => $codigo_barra,
                                'origen'      => '',
                                'destino'     => '',
                            );
                            $this->codegen_model->add('users_log', $data);
                            */
                            echo json_encode(array('success' => 'La pieza: ' . $codigo_barra . ' fue editada.'));
                        }else{
                            echo json_encode(array('status' => 'La pieza: ' . $codigo_barra . ' no fue editada.'));
                        }
                    }else{
                        echo json_encode(array('status' => 'La pieza: ' . $codigo_barra . ' esta rendida.'));
                    }
                }else{
                    echo json_encode(array('status' => 'La pieza: ' . $codigo_barra . ' esta rendida.'));
                }
            }else{
                echo json_encode(array('status' => 'La pieza: ' . $codigo_barra . ' esta en otra sucursal.'));
            }

        }else{
            echo json_encode(array('status' => 'La pieza: ' . $codigo_barra . ' no fue encontrada'));
        }
    }
    
    public function buscar_para_modificar(){
        ini_set('memory_limit', '-1');
        $codigo_barra = $this->input->post('codigo_barra');
        $buscar_por = $this->input->post('buscar_por');
        if($codigo_barra == ''){
            echo json_encode(array('status' => 'La pieza: No Tiene identificador.'));
            exit;
        }
        $identificafor = '';
        if($buscar_por == 1){
            $identificafor = 'id';
            
        }else{
            $identificafor = 'barcode_externo';
        }
        $query = $this->db
        ->select('p.destinatario as destinatario, p.domicilio as domicilio, p.codigo_postal as codigo_postal , p.localidad as localidad, p.barcode_externo as barcode_externo  , p.datos_varios as datos_varios, p.datos_varios_1 as datos_varios_1, p.datos_varios_2 as datos_varios_2, p.datos_varios_3 as datos_varios_3')
        ->where("p.$identificafor = '$codigo_barra'")
        ->get('flash_piezas as p');
        
        $json = $query->row();
        if ($json) {
            echo json_encode(array('respuesta' => $json));
        }else{
            echo json_encode(array('status' => 'La pieza: ' . $codigo_barra . ' no fue encontrada'));
        }
    }
}


/* End of file Piezas.php */
/* Location: ./system/application/controllers/Piezas.php */