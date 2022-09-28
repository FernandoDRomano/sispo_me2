<?php

class Despachos extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function grabarPiezaNormal()
    {
        $mensaje    = '';    
        $codigo     = (int) $this->input->post('codigo_barra');
        $codigo_str = '"' . $this->input->post('codigo_barra') . '"';
        //$piezas_cargadas = $this->input->post('piezas_ids');
        $buscar_por = $this->input->post('buscar_por');
        //Traigo los datos de la pieza dependiendo si se busca por ID o CODIGO EXTERNO
        if (intval($buscar_por) == 1) {
            $pieza = $this->codegen_model->row('flash_piezas', 'id', 'id = ' . $codigo);
        }

        if (intval($buscar_por) == 2) {
            $pieza = $this->codegen_model->row('flash_piezas', 'id', 'barcode_externo = ' . $codigo_str);
        }

        if ($pieza == null) {
            echo json_encode(array('status' => 'La pieza no existe'));
            return;
        }

        // load URL helper //Chequeo que la pieza este en un edtado condicional
        //$this->load->helper('novedades');
        //$respuesta = validar_DESPACHO($pieza->estado_id);
        //if($respuesta != ""){echo json_encode(array('respuesta' => $respuesta));return;}

        $user_row = $this->ion_auth->user()->row();

        $query = $this->db
            ->select(' s.nombre servicio, (CASE WHEN  p.barcode_externo IS NULL THEN "" ELSE p.barcode_externo END) AS barcode_externo,
                                    p.id pieza_id, p.domicilio domicilio, p.codigo_postal codigo_postal,p.localidad, p.destinatario')
            ->join('flash_comprobantes_ingresos_servicios cis', 'p.servicio_id = cis.id')
            ->join('flash_servicios s', 's.id = cis.servicio_id')
            ->join('flash_piezas_estados_variables ev', 'p.estado_id = ev.id')
            ->where('ev.pieza_estado_id IN (' . PiezaEstado::ESTADOS_INICIALES . ',' . PiezaEstado::ESTADOS_ORGANIZATIVOS . ')')
            ->where('ev.id NOT IN (' . Pieza::ESTADO_EN_GESTION . ')')
            ->where('p.sucursal_id = ' . $user_row->sucursal_id)
            ->where('p.tipo_id = ' . Pieza::TIPO_NORMAL)
            ->where('p.id = ' . $pieza->id)
            ->get('flash_piezas p');
           //echo $this->db->last_query();die;
        $json = $query->result();

        if ($json) {
            array_push($json, $this->add_pieza_normal($_POST, $pieza->id));
            echo json_encode($json);
        } else {
            echo json_encode(array('status' => 'La pieza no esta disponible'));
        }
    }

    private function alta_flash_piezas_novedades($despacho_id, $user_row, $pieza_id){
        $query = $this->db
        ->select('ev.id as estado_id')
        ->join('flash_piezas_estados_variables ev', 'd.destino_id = ev.sucursal_id AND ev.pieza_estado_id = 4') //4 DESPACHO
        ->where('d.id = '. $despacho_id)
        ->get('flash_piezas_despacho d');

        $row = $query->row();
        if (isset($row))
        {
            $estado_id =  $row->estado_id;
        }else{
             $estado_id = Pieza::ESTADO_EN_TRNSITO;
        }
        $data_pieza = array(
            "estado_id" => $estado_id//Pieza::ESTADO_EN_TRNSITO,
        );
        //Actualizo la tabla flash_piezas_novedades
        $update_novedades = " UPDATE flash_piezas_novedades n
                                        INNER JOIN flash_piezas p ON p.id = n.pieza_id
                                        SET estado_actual_id = p.estado_id,
                                            estado_nuevo_id = " . $estado_id . ",
                                              n.update = '" . date("Y-m-d H:i:s") . "',
                                               n.update_user_id = ".$user_row->id."
                                                WHERE pieza_id = " . $pieza_id;

        $array_piezas_tracking[]     = array(
            'pieza_id'              => $pieza_id,
            'usuario_id'            => $user_row->id,
            'estado_id'             => $estado_id, 
            'create'                => date("Y-m-d H:i:s"),
            'update'                => date("Y-m-d H:i:s"),
            'create_user_id'        =>  $user_row->id,
            'update_user_id'         => $user_row->id,
        );
        //Primero actualizo novedades y despues piezas     
        $this->db->query($update_novedades);        
        $this->codegen_model->edit('flash_piezas', $data_pieza, 'id', $pieza_id);                                  
        //inserto en la tabla flash_piezas_tracking
        $this->db->insert_batch('flash_piezas_tracking', $array_piezas_tracking);

        $Data = array('codigo_barra' => $pieza_id,'estado_id' => $estado_id,'buscar_por' => 1,'UserId' => $user_row->id);// Modo Test Sispo , 'Sispo' => 'Sispo'
		//print_r($Data);
		$PHPRespuesta = $this->CURL("POST", "http://sistema.sppflash.com.ar" ."/api/flame/ApisExternas/AjaxAdministrarEstadosDeSispo.php", $Data);//laravelapis.sppflash.com.ar
        //print_r($PHPRespuesta);

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
	

    public function add_pieza_normal($post, $pieza_id)
    {
        $despacho_id = $post['despacho_id'] != '' ? $post['despacho_id'] : false;
        $user_row = $this->ion_auth->user()->row();
        if (!$despacho_id) {
            //Grabo encabezado
            $data = array(
                'origen_id'         => $this->input->post('origen_id'),
                'destino_id'        => $this->input->post('destino_id'),
                'usuario_origen_id' => $this->input->post('usuario_origen_id'),
                'transporte_id'     => $this->input->post('transporte_id'),
                'fecha_envio'       => formatdate($this->input->post('fecha_envio')),
                'piezas'            => 1,
                'estado'            => $this->input->post('enviar') == 1 ? Despacho::ESTADO_ENVIADO : Despacho::ESTADO_INICIADO,
                'create_user_id' => $user_row->id
            );
            $despacho = Despacho::create($data);
        } else {
            $despacho = Despacho::whereId($despacho_id)->first();
        }
        //Grabo la pieza Normal dentro del Despacho
        $data = array(
            "despacho_id" => $despacho->id,
            "pieza_id"    => $pieza_id,
        );
        $this->db->insert('flash_piezas_despacho_piezas', $data);
        //Alta de estado en flash_piezas_novedades y flash_piezas_tracking
        $this->alta_flash_piezas_novedades($despacho->id, $user_row, $pieza_id );
        
        
        /* Auditoria */
          $data = array(
            'user_id' => $user_row->id,
            'categoria' => 'DESPACHOS',
            'descripcion' => 'Despacho: ' . $despacho->id . '. Pieza Normal: ' . $pieza_id . '. Añadir Pieza Normal a Despacho.',
            'origen' => '',
            'destino' => '',
        );
        $this->codegen_model->add('users_log',$data);
        /* END: Auditoria */

        return $despacho->id;
    }

    public function delete_pieza_normal()
    {
        $pieza_id    = $this->input->post('pieza_id');
        $despacho_id = $this->input->post('despacho_id');
        $user_row    = $this->ion_auth->user()->row();
        $data        = array(
            'user_id'  => $user_row->id,
            'consulta' => "Piezas normal quitada del Despacho: DELETE FROM flash_piezas_despacho_piezas WHERE pieza_id = " . $pieza_id,
            'fecha'    => date('Y-m-d H:i:s'));
        $this->codegen_model->add('auditoria', $data);
           
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
        

        $data_pieza = array(
            "estado_id" => $estado_nuevo_id//Pieza::ESTADO_EN_TRNSITO,
        );
        //var_dump($result_tracking[0]->estado_id);
        
        $this->codegen_model->edit('flash_piezas', $data_pieza, 'id', $pieza_id);
        $this->codegen_model->delete('flash_piezas_despacho_piezas', 'pieza_id', $pieza_id . ' AND despacho_id = ' . $despacho_id);
         
        $update_novedades = " UPDATE flash_piezas_novedades n
                                        INNER JOIN flash_piezas p ON p.id = n.pieza_id
                                        SET estado_nuevo_id = p.estado_id,
                                            estado_actual_id = " . intval($estado_actual_id) . ",
                                                n.update_user_id = ".$user_row->id."
                                                    WHERE pieza_id = " . $pieza_id;
        $this->db->query($update_novedades);

        //Elimina el ultimo traking creado
        $delete_tracking = " DELETE FROM flash_piezas_tracking WHERE id = ".$tracking_id;
        $this->db->query($delete_tracking);


        /* Auditoria */
        $user_row = $this->ion_auth->user()->row();
        $data = array(
            'user_id' => $user_row->id,
            'categoria' => 'DESPACHOS',
            'descripcion' => 'Despacho: ' . $despacho_id . '. Pieza Normal: ' . $pieza_id . '. Borrar Pieza Normal de Despacho.',
            'origen' => '',
            'destino' => '',
        );
        $this->codegen_model->add('users_log',$data);
        /* END: Auditoria */

        echo json_encode(array("respuesta" => $this->db->affected_rows())); //true: exito, flase: error
    }


    public function grabarPiezasSimples()
    {
        //Si el despacho no existe grabo el encabezado
        $despacho = Despacho::whereId($this->input->post('despacho_id'))->first();
        //Chequeo si el usuario esta logueado. Si no lo esta corto el proceso
        $user_row    = $this->ion_auth->user()->row();
        if ($despacho == NULL) {
            $data = array(
                'origen_id'         => $this->input->post('origen_id'),
                'destino_id'        => $this->input->post('destino_id'),
                'usuario_origen_id' => $this->input->post('usuario_origen_id'),
                'transporte_id'     => $this->input->post('transporte_id'),
                'fecha_envio'       => formatdate($this->input->post('fecha_envio')),
                'piezas'            => 1,
                'estado'            => $this->input->post('enviar') == 1 ? Despacho::ESTADO_ENVIADO : Despacho::ESTADO_INICIADO,
                'create_user_id'    => $user_row->id
            );
            $despacho = Despacho::create($data);
        } else {
            $despacho = Despacho::whereId($despacho->id)->first();
        }

        //Con el Despacho creado comienzo a grabar las piezas en la tabla FLASH_PIEZAS_DESPACHO_PIEZAS
        $comprobante_ingreso_numero = $this->input->post('codigo_barra_simple');
        $comprobante_ingreso        = Comprobante::whereNumero($comprobante_ingreso_numero)->first();
        $servicio_id                = $this->input->post('servicio_id');
        $cantidad                   = $this->input->post('cantidad');
        //Agrego la sucursal del usuario porque si bien muestra la cantidad disponible de piezas simples para agregar a un
        //DESPACHO , esta tomando cualquier pieza SIMPLE de un SERVICIO específico de un CI específico, pero de cualquier SUCURSAL
        
        $sucursal_id = $user_row->sucursal_id;
        $sql         = "INSERT  INTO flash_piezas_despacho_piezas (despacho_id, pieza_id)
                    SELECT
                            $despacho->id as despacho_id, p.id as pieza_id
                    FROM `flash_piezas` `p`
                    INNER JOIN flash_piezas_estados_variables ev on p.estado_id = ev.id
                    WHERE ev.pieza_estado_id IN (" . PiezaEstado::ESTADOS_INICIALES . ',' . PiezaEstado::ESTADOS_ORGANIZATIVOS . ")
                    AND p.tipo_id = " . PIEZA_TIPO_SIMPLE . "
                     AND p.servicio_id = " . $servicio_id . " " . "
                     AND p.comprobante_ingreso_id = " . $comprobante_ingreso->id . "
                     AND p.sucursal_id = " . $sucursal_id . "
                     LIMIT " . $cantidad;
        //Ejecuto la QUERY de INSERCION
        $this->db->query($sql);

        //Agrego la sucursal del usuario porque si bien muestra la cantidad disponible de piezas simples para agregar a un
        //DESPACHO , esta tomando cualquier pieza SIMPLE de un SERVICIO específico de un CI específico, pero de cualquier SUCURSAL
        //Actualizo el estado de las piezas cargadas a EN TRANSITO
        $sql_update = " UPDATE flash_piezas p
                            INNER JOIN flash_piezas_estados_variables ev on p.estado_id = ev.id
                            INNER JOIN flash_piezas_despacho_piezas pdp on p.id = pdp.pieza_id
                                SET estado_id = " . Pieza::ESTADO_EN_TRNSITO . ",
                                    p.update_user_id = ".$user_row->id.",
                                     p.update = '". date("Y-m-d H:i:s")."'
                              WHERE ev.pieza_estado_id IN (" . PiezaEstado::ESTADOS_INICIALES . ',' . PiezaEstado::ESTADOS_ORGANIZATIVOS . ")
                            AND p.tipo_id = " . PIEZA_TIPO_SIMPLE . "
                            AND p.servicio_id = " . $servicio_id . " " . "
                             AND p.comprobante_ingreso_id = " . $comprobante_ingreso->id . "
                             AND p.sucursal_id = " . $sucursal_id . "
                             AND pdp.despacho_id = $despacho->id";
//echo $sql_update;die;
        //Ejecuto la query de update
        $this->db->query($sql_update);
        //Traigo los datos del despacho guardadao
        $query_despacho_piezas = $this->db
            ->select('pd.id despacho_id, s.nombre servicio, cis.id servicio_id, count(*) cantidad')
            ->join('flash_piezas_despacho_piezas pdp', 'pd.id = pdp.despacho_id')
            ->join('flash_piezas p', 'pdp.pieza_id = p.id')
            ->join('flash_comprobantes_ingresos_servicios cis', 'p.servicio_id = cis.id')
            ->join('flash_servicios s', 'cis.servicio_id = s.id')
            ->where('pd.id = ' . $despacho->id)
            ->where('p.tipo_id = ' . PIEZA_TIPO_SIMPLE)
            ->where('p.servicio_id = ' . $servicio_id)
            ->where('p.comprobante_ingreso_id = ' . $comprobante_ingreso->id)
            ->where('p.sucursal_id = ' . $sucursal_id)
            ->group_by('pdp.despacho_id')
            ->get('flash_piezas_despacho pd');

        $json = $query_despacho_piezas->result();

        /* Auditoria */
        $user_row = $this->ion_auth->user()->row();
        $data = array(
            'user_id' => $user_row->id,
            'categoria' => 'DESPACHOS',
            'descripcion' => 'Despacho: ' . $despacho->id . '. Grabar Pieza Simple a Despacho.',
            'origen' => '',
            'destino' => '',
        );
        $this->codegen_model->add('users_log',$data);
        /* END: Auditoria */

        if ($json) {
            echo json_encode($json);
        } else {
            echo json_encode(array('status' => 'La pieza no esta disponible'));
        }
    }

    public function delete_piezas_simples()
    {
        

        $servicio_id = $this->input->post('servicio_id');
        $despacho_id = $this->input->post('despacho_id');
        $cantidad    = $this->input->post('cantidad');
        $user_row    = $this->ion_auth->user()->row();

        $sql_update = "UPDATE  flash_piezas p
                    INNER JOIN flash_piezas_despacho_piezas pdp on p.id = pdp.pieza_id
                    SET p.estado_id = " . Pieza::ESTADO_EN_GESTION . "
                     WHERE pdp.despacho_id = " . $despacho_id . "
                     AND p.servicio_id = " . $servicio_id . "
                     AND p.sucursal_id = " . $user_row->sucursal_id;
        $query1 = $this->db->query($sql_update);

        $sql_delete = "DELETE  pdp
                            FROM flash_piezas_despacho_piezas pdp
                            INNER JOIN flash_piezas p ON p.id = pdp.pieza_id
                            WHERE pdp.despacho_id = " . $despacho_id . "
                             AND p.servicio_id = " . $servicio_id . "
                             AND p.sucursal_id = " . $user_row->sucursal_id;

        $data = array(
            'user_id'  => $user_row->id,
            'consulta' => $sql_delete,
            'fecha'    => date('Y-m-d H:i:s'));
        $this->codegen_model->add('auditoria', $data);

        $respuesta = $this->db->query($sql_delete);

        /* Auditoria */
        $user_row = $this->ion_auth->user()->row();
        $data = array(
            'user_id' => $user_row->id,
            'categoria' => 'DESPACHOS',
            'descripcion' => 'Despacho: ' . $despacho_id . '. Eliminar Pieza Simple a Despacho.',
            'origen' => '',
            'destino' => '',
        );
        $this->codegen_model->add('users_log',$data);
        /* END: Auditoria */

        echo json_encode(array("respuesta" => $this->db->affected_rows())); //true: exito, flase: error
    }

    public function traerPiezasSimplesPorComprobanteEstadosYServicio()
    {
        ini_set('memory_limit', '-1');
        $comprobante_ingreso     = $this->input->post('codigo_barra');
        $comprobante_ingreso_id  = Comprobante::whereNumero($comprobante_ingreso)->first();
        $cantidad                = $this->input->post('cantidad');
        $comprobante_servicio_id = $this->input->post('servicio_id');

        //Solo se trae las piezas que esten en la sucursal fisicamente
        $user_row = $this->ion_auth->user()->row();

        $query = $this->db
            ->select(' * ')
            ->join('flash_comprobantes_ingresos ci', 'p.comprobante_ingreso_id = ci.id')
            ->join('flash_piezas_estados_variables ev', 'p.estado_id = ev.id')
            ->where('ci.id = ' . $comprobante_ingreso_id->id)
            ->where('ev.pieza_estado_id IN (' . PiezaEstado::ESTADOS_INICIALES . ',' . PiezaEstado::ESTADOS_ORGANIZATIVOS . ')') //CAMBIADO 23_07_2017->where('p.estado_id IN ( '.$estados_id.')' )
            ->where('p.tipo_id = ' . Pieza::TIPO_SIMPLE)
            ->where('p.sucursal_id = ' . $user_row->sucursal_id)
            ->where('p.servicio_id = ' . $comprobante_servicio_id)
            ->get('flash_piezas p');
        $json = $query->result();
        //echo $this->db->last_query();die;
        if ($json) {
            echo json_encode($json);
        } else {
            echo json_encode(array('status' => 'none'));
        }

    }

    public function analizarXLS(){
        ini_set("memory_limit", "-1");
        ini_set("pcre.backtrack_limit", "100000000");
        ini_set("pcre.recursion_limit", "100000000");
        set_time_limit(1000);
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
        
        $array_ids = [];
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
                $codigo = $objWorksheet->getCell('A' . $rowIndex)->getValue();
                if($codigo != 'id') array_push($array_ids,$codigo);

            }
        }
        $str_ids = implode(',',$array_ids);
        //var_dump($str_ids);die;

        //CONDICIONALES 
        $user_row = $this->ion_auth->user()->row();

        $query = $this->db
            ->select(' p.id')
            ->join('flash_comprobantes_ingresos_servicios cis', 'p.servicio_id = cis.id')
            ->join('flash_servicios s', 's.id = cis.servicio_id')
            ->join('flash_piezas_estados_variables ev', 'p.estado_id = ev.id')
            ->where('ev.pieza_estado_id IN (' . PiezaEstado::ESTADOS_INICIALES . ',' . PiezaEstado::ESTADOS_ORGANIZATIVOS . ')')
            ->where('ev.id NOT IN (' . Pieza::ESTADO_EN_GESTION . ')')
            ->where('p.sucursal_id = ' . $user_row->sucursal_id)
            ->where('p.tipo_id = ' . Pieza::TIPO_NORMAL)
            ->where('p.id IN ('.$str_ids.')' )
            ->get('flash_piezas p');
           //echo $this->db->last_query();
        $json_result = $query->result();
        //convierto el resultado en array para ver cuales piezas no cumplen con las condiciones
        $json_array = [];
        foreach($json_result as $value ){
            array_push($json_array,intval($value->id));
        }
        $diferencia = array_diff($array_ids,$json_array);
        $str_diferencia = implode(', ',$diferencia);
        $piezas_rechazadas = [];
        if(count($diferencia) > 0){
            $piezas_rechazadas = $str_diferencia;
        }

        $respuesta['despacho']['piezas_ok'] = $json_array;
        $respuesta['despacho']['filename'] = $file_name;
        $respuesta['despacho']['piezas_nook'] = $piezas_rechazadas;
        echo json_encode($respuesta);
        //$cantidad_piezas = $highestRow - 1; //Resto el titulo de la cantidad de filas
    }

}

/* End of file Piezas.php */
/* Location: ./system/application/controllers/Piezas.php */
