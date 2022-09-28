<?php

use Illuminate\Database\Capsule\Manager as DB;

class Piezas_paquetes_masiva extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->permisos = $this->permisos_lib->control();
        $this->load->model('Pieza_md_n');
        $this->load->helper('url');
    }

    function index(){
            $vista_interna = array(
                'permisos_efectivos' => $this->permisos,
                'paquetes' => [],
                //'clientes' => Cliente::select('id', DB::raw('CONCAT(nombre," (",nombre_fantasia,")") as name'))->where('cliente_estado_id',Cliente::ESTADO_ACTIVO)->get(),
                'comprobante_servicios' => array(),
                'zonas' => $this->codegen_model->get('flash_tarifario_zonas', '*'),
                'paises' => $this->codegen_model->get('flash_paises', '*')
            );

            $vista_externa = array(			
                'title' => ucwords("Piezas Paquetes Masivo Nuevo"),
                'contenido_main' => $this->load->view('components/piezasN/piezas_paquetes_masiva/piezas_paquetes_masiva_add', $vista_interna, true)
            );		

            $this->load->view('template/rubenbackend', $vista_externa);
    }

    function getPaqueteriaXCliente($cliente_id = null, $cliente = null, $pais_origen = null, $cantidad_piezas = null, $errores = null, $insertadas = null){
        $cliente_id = ($cliente_id != NULL)?$cliente_id:$this->input->post('list_cliente_id');
        $cliente = ($cliente != NULL)?$cliente:$this->input->post('list_cliente');
        $pais_origen = ($pais_origen != NULL)?$pais_origen:$this->input->post('list_pais_id');

        $query_tarifario = $this->db
                ->select('T.*')
                ->where('T.cliente_id = '. $cliente_id)
                ->get('flash_tarifario T');

        $query_paquetes_cliente = $this->db
                ->select(' ci.numero comprobante_ingreso, ci.fecha_pedido, pp.descripcion_paquete, pp.peso, pp.dimensiones, pp.bultos , pp.palets, pp.pieza_id, pp.metro_cubico, pp.monto_valor_declarado, pp.monto_cobranza, pp.warehouse, pp.tipo_vehiculo, pp.tipo_hora, pp.cantidad_hora')
                ->join('flash_piezas p', 'ci.id = p.comprobante_ingreso_id')
                ->join('flash_comprobantes_ingresos_servicios cis', 'p.servicio_id = cis.id')
                ->join('flash_piezas_paquetes pp', 'p.id = pp.pieza_id')
                ->where('ci.cliente_id = '.$cliente_id)
                ->order_by('ci.create DESC')
                ->get('flash_comprobantes_ingresos ci');

        $tarifario = $query_tarifario->row();
        $paquetes = $query_paquetes_cliente->result();

        $vista_interna = array(
            'cliente_id' => $cliente_id,
            'cliente' => $cliente,
            'paquetes' => $paquetes,
            'pais_origen' => $pais_origen,
            'paises' => $this->codegen_model->get('flash_paises', '*'),
            'clientes' => Cliente::select('id', DB::raw('CONCAT(nombre," (",nombre_fantasia,")") as name'))->where('cliente_estado_id',Cliente::ESTADO_ACTIVO)->where('pais', $pais_origen)->get(),
            'localidades' => $this->codegen_model->get('ubicacion_localidades', '*'),
            'zonas' => $this->codegen_model->get('flash_tarifario_zonas', '*'),
            'departamentos' => $this->flash_md->getDepartamentos_altapieza($cliente_id),
            'tarifario' => $tarifario,
            'errores' => $errores,
            'cantidad_piezas' => $cantidad_piezas,
            'insertadas' => $insertadas
        );

        $vista_externa = array(			
            'title' => ucwords("Piezas Paquetes Masivo Nuevo"),
            'contenido_main' => $this->load->view('components/piezasN/piezas_paquetes_masiva/piezas_paquetes_masiva_add', $vista_interna, true)
        );		

        $this->load->view('template/rubenbackend', $vista_externa);
    }

    function uploadExcel(){
        set_time_limit(1800); //1800 segundos ejecucion PHP
        ini_set("memory_limit", "-1");
        //set_time_limit(0);
        
        if (count($_FILES) == 0) {
            echo json_encode(array('error' => ' Seleccione el archivo a cargar con el boton BROWSE.'));return;
        }
        
        //$servicio_cantidad_disponible = $this->input->post('servicio_cantidad_disponible');
        $path = "";

        $path = $_SERVER['DOCUMENT_ROOT'] .'/assets/upload/';

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
        move_uploaded_file( $_FILES['archivo']['tmp_name'], $path.$file_name);//base_url().'assets/upload/piezas_fechayHora.csv';
        
        $this->load->library('excel');
        $this->load->library('chunkReadFilter');
        
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


        /** Advise the Reader that we only want to load cell data, not formatting **/
        /**  Define how many rows we want to read for each "chunk"  **/
        $chunkSize = 1000;
        /**  Create a new Instance of our Read Filter  **/
        $chunkFilter = new chunkReadFilter();
        /**  Tell the Reader that we want to use the Read Filter that we've Instantiated  **/
        $objReader->setReadFilter($chunkFilter);
        /**  Loop to read our worksheet in "chunk size" blocks  **/
        /**  $startRow is set to 2 initially because we always read the headings in row #1  **/
        $final = false;
        //echo $cantidad_piezas;die;
        $controlador = 0;
        $x           = 2;

        $insertadas = 0;

        $errores = [];
        $user_row = $this->ion_auth->user()->row();
        $zonaA = $this->input->post('zona_origen');
        $zonaB = $this->input->post('zona_destino');
        $cliente_id = $this->input->post('cliente_id');
        $cliente = Cliente::select(DB::raw('CONCAT(nombre," (",nombre_fantasia,")") as name'))->where('cliente_estado_id',Cliente::ESTADO_ACTIVO)->where('id', $cliente_id)->first();
        $pais_origen = $this->input->post('pais_origen');
        $departamento_id = $this->input->post('departamento_id');

        for ($startRow = 0; $startRow <= ($cantidad_piezas); $startRow += $chunkSize) {
            $suma = $controlador + $chunkSize;
////            echo $suma."<br/>";
            if ($suma > $cantidad_piezas) {
                $chunkSize = $cantidad_piezas - $startRow;
                $final     = true;
            } else {
                $controlador += $chunkSize;
            }
            
            
            //echo $chunkSize."<br/>";
            /**  Tell the Read Filter, the limits on which rows we want to read this iteration  **/
            //echo $startRow."   -   ".$chunkSize."<br/>";
            $chunkFilter->setRows($startRow + 2, $chunkSize);
            //$objReader->setReadDataOnly(true);
            /**  Load only the rows that match our filter from $inputFileName to a PHPExcel Object  **/
            $objPHPExcel = $objReader->load($path.$file_name);
            $sheetData   = $objPHPExcel->getActiveSheet(); //->toArray(null,true,true,true);
            
            

            //echo "comienzo bloque ".date("H:i:s")."<br/>";

            for ($x; $x <= $startRow + $chunkSize + 1; $x++) {
        
                //BUSCAR PAIS, PROVINCIA, LOCALIDAD (IDS) PARA ALMACENAR EN LA TABLA FLASH PIEZAS Y TAMBIEN PARA GEOCODIFICAR
                $localidad_origen = $sheetData->getCell("A" . $x)->getValue();
                $pais = $sheetData->getCell("E" . $x)->getValue();
                $provincia = $sheetData->getCell("F" . $x)->getValue();
                $localidad_destino = $sheetData->getCell("G" . $x)->getValue();
                $calle = $sheetData->getCell("I" . $x)->getValue();
                $numero = $sheetData->getCell("J" . $x)->getValue();
                $dimensiones = $sheetData->getCell("R" . $x)->getValue();
                $peso = $sheetData->getCell("Q" . $x)->getValue();
                $descripcion_paquete = $sheetData->getCell("P" . $x)->getValue();
                $latitud = "";
                $longitud = "";
                

                //BUSCAR PAIS
                $pais_id = $this->Pieza_md_n->getPaisPorNombre($pais);
                
                if(!is_null($pais_id)){
                    $pais_id = $pais_id->id;
                }else{
                    $error = [
                        "mensaje" => "No se encontro el Pa&iacute;s en la Base de datos",
                        "fila" => $x
                    ];
                    $errores[$x] = $error;
                    continue;
                }

                //BUSCAR PROVINCIAS
                $provincia_id = $this->Pieza_md_n->getProvinciaPorNombre($provincia, $pais_id);

                if(!is_null($provincia_id)){
                    $provincia_id = $provincia_id->id;
                }else{
                    $error = [
                        "mensaje" => "No se encontro la Provincia en la Base de datos",
                        "fila" => $x
                    ];
                    $errores[$x] = $error;
                    continue;
                }

                //BUSCAR LOCALIDADES
                $localidad_origen_id = $this->Pieza_md_n->getLocalidadPorNombreYZona($localidad_origen, $zonaA);

                if(!is_null($localidad_origen_id)){
                    $localidad_origen_id = $localidad_origen_id->id;
                }else{
                    $error = [
                        "mensaje" => "No se encontro la Localidad de origen en la Base de datos",
                        "fila" => $x
                    ];
                    $errores[$x] = $error;
                    continue;
                }

                $localidad_destino_id = $this->Pieza_md_n->getLocalidadPorNombre($localidad_destino, $provincia_id);

                if(!is_null($localidad_destino_id)){
                    $localidad_destino_id = $localidad_destino_id->id;
                }else{
                    $error = [
                        "mensaje" => "No se encontro la Localidad de destino en la Base de datos",
                        "fila" => $x
                    ];
                    $errores[$x] = $error;
                    continue;
                }

                //VALIDAR QUE LAS LOCALIDADES SE ENCUENTREN DENTRO DE LAS ZONAS DEL ESQUEMA TARIFARIO DEL CLIENTE
                $validarZonaA = $this->Pieza_md_n->validarNombreLocalidadEsteContenidaEnZonaDelEsquemaTarifario($cliente_id, $localidad_origen);
                $validarZonaB = $this->Pieza_md_n->validarLocalidadEsteContenidaEnZonaDelEsquemaTarifario($cliente_id, $localidad_destino_id, 2);

                if($validarZonaA->resultado == "FALSE"){
                    $error = [
                        "mensaje" => "La Localidad de origen no se encuentra dentro del esquema tarifario del cliente",
                        "fila" => $x
                    ];
                    $errores[$x] = $error;
                    continue;
                }

                if($validarZonaB->resultado == "FALSE"){
                    $error = [
                        "mensaje" => "La Localidad de destino no se encuentra dentro del esquema tarifario del cliente",
                        "fila" => $x
                    ];
                    $errores[$x] = $error;
                    continue;
                }

                //VALIDAR DESCRIPCION DEL PAQUETE
                if(empty($descripcion_paquete)){
                    $error = [
                        "mensaje" => "La descripci&oacute;n del paquete es requerida",
                        "fila" => $x
                    ];
                    $errores[$x] = $error;
                    continue; 
                }

                //VALIDAR DIMENSIONES
                $patron = "/[0-9]+(x|X)[0-9]+(x|X)[0-9]+$/";
        
                if (!preg_match($patron, $dimensiones)) {
                    $error = [
                        "mensaje" => "La dimensiones del paquete tiene un formato incorrecto",
                        "fila" => $x
                    ];
                    $errores[$x] = $error;
                    continue;  
                }

                //VALIDAR PESO        
                if (!is_numeric($peso)) {
                    $error = [
                        "mensaje" => "El Peso del paquete tiene un formato incorrecto",
                        "fila" => $x
                    ];
                    $errores[$x] = $error;
                    continue;  
                }

                //GEOCODIFICAR
                $geocodificacion = $this->geocodificar($pais, $provincia, $localidad_destino, $calle, $numero);

                if($geocodificacion["condicion"] == 1){
                   $latitud = $geocodificacion["latitud"];
                   $longitud = $geocodificacion["longitud"];
                }else{
                    $error = [
                        "mensaje" => "Faltan datos para Geocodificar la Direcci&oacute;n de destino",
                        "fila" => $x
                    ];
                    $errores[$x] = $error;
                    continue;
                }

                //Traigo un comprobante disponible
                $comprobante_ingreso_generado = ComprobanteGenerado::whereEstado(ComprobanteGenerado::ESTADO_DISPONIBLE)->first();

                //Creo un comprobante de ingreso
                $comprobante_ingreso_id = $this->crearComprobanteIngreso($comprobante_ingreso_generado, $user_row, $cliente_id, $departamento_id);
                $comprobante_ingreso = Comprobante::whereId($comprobante_ingreso_id)->first();
                //Creo el servicio de paqueteria para ese comprobante
                $comprobante_ingreso_servicio_id = $this->crearComprobanteIngresoServicio($comprobante_ingreso);
                $comprobante_ingreso_servicio = ComprobanteServicio::whereId($comprobante_ingreso_servicio_id)->first();
                $servicio  = Servicio::whereId($comprobante_ingreso_servicio->servicio_id)->first();
                //echo $cantidad[$i]."   -   ";
                $cantidad_modficado = 1;
                date_default_timezone_set('America/Argentina/Tucuman');

                //INICIAR TRANSACCION
                $this->db->trans_start();

                //INSERT PIEZAS
                $array_piezas = array(
                    
                    'usuario_id'             => $this->usuario->id,
                    'servicio_id'            => $comprobante_ingreso_servicio->id,
                    'tipo_id'                => $servicio->acuse == 1 ? PIEZA_TIPO_NORMAL : PIEZA_TIPO_SIMPLE,
                    'sucursal_id'            => $comprobante_ingreso->sucursal_id, //Modificado las piezas cargadas deberian tener la sucursal del CI $this->usuario->sucursal_id,
                    'estado_id'              => Pieza::ESTADO_EN_GESTION,
                    'cantidad'               => $cantidad_modficado,
                    'comprobante_ingreso_id' => $comprobante_ingreso->id,
                    
                    'localidad_origen'       => $localidad_origen_id, //$sheetData->getCell("A" . $x)->getValue(),
                    'codigo_postal_origen'   => $sheetData->getCell("B" . $x)->getValue(),
                    'destinatario'           => $sheetData->getCell("C" . $x)->getValue(),
                    'barcode_externo'        => $sheetData->getCell("D" . $x)->getValue(), 
                    'pais_destino'           => $pais_id, //$sheetData->getCell("E" . $x)->getValue(),
                    'provincia_destino'      => $provincia_id, //$sheetData->getCell("F" . $x)->getValue(),
                    'localidad_destino'      => $localidad_destino_id, //$sheetData->getCell("G" . $x)->getValue(),
                    'codigo_postal_destino'  => $sheetData->getCell("H" . $x)->getValue(),
                    'domicilio'              => $calle . ' ' . $numero,
                    'piso'                   => $sheetData->getCell("K" . $x)->getValue(),
                    'departamento_oficina'   => $sheetData->getCell("L" . $x)->getValue(),
                    'datos_varios'           => $sheetData->getCell("M" . $x)->getValue(),
                    'datos_varios_1'         => $sheetData->getCell("N" . $x)->getValue(),
                    'datos_varios_2'         => $sheetData->getCell("O" . $x)->getValue(),
                    'create_user_id'         => $user_row->id,
                    'create'                 => date("Y-m-d H:i:s"),
                    'update'                 => date("Y-m-d H:i:s"),
                    'hoja_ruta_id'           => null,
                );

                $pieza_id = $this->codegen_model->add('flash_piezas', $array_piezas);
                //END INSERT PIEZAS

                //INSERT PIEZAS PAQUETES
                $array_piezas_paquetes = array(
                    'pieza_id' => $pieza_id,
                    'descripcion_paquete' => $sheetData->getCell("P" . $x)->getValue(),
                    'peso' => $sheetData->getCell("Q" . $x)->getValue(),
                    'dimensiones' => $sheetData->getCell("R" . $x)->getValue(),
        
                    'zonaA' => $zonaA,
                    'zonaB' => $zonaB,
                    
                    //'dias_entrega' => '',
                    'create' => date("Y-m-d H:i:s"),
                    'update' => date("Y-m-d H:i:s"),
                    'create_user_id' => $user_row->id,
                );

                $this->codegen_model->add('flash_piezas_paquetes', $array_piezas_paquetes);
                //END INSERT PIEZAS PAQUETES

                
                //INSERT GEOCODIFICACION
                if($geocodificacion["condicion"] == 1 && !empty($latitud) && !empty($longitud)){
                    $array_geocodificacion = array(
                        'pieza_id'    => $pieza_id,
                        'latitud'     => $latitud,
                        'longitud'    => $longitud,
                    );

                    $this->codegen_model->add('flash_piezas_coordenadas', $array_geocodificacion);
                }
                //END INSERT GEOCODIFICACION
                
                if ($servicio->acuse == 1) {
                    //Grabo el estado en novedades
                    $array_piezas_aux[]     = array(
                        'pieza_id'              => $pieza_id,
                        'cantidad'              => 1,
                        'usuario_id'            => $user_row->id,
                        'estado_actual_id'      => Pieza::ESTADO_EN_GESTION,
                        'estado_nuevo_id'       => Pieza::ESTADO_EN_GESTION,
                        'create'                => date("Y-m-d H:i:s"),
                        'update'                => date("Y-m-d H:i:s"),
                        'create_user_id'        =>  $user_row->id,
                        'update_user_id'         => $user_row->id,
                    );
        
                    $array_piezas_tracking_aux[]     = array(
                        'pieza_id'              => $pieza_id,
                        'usuario_id'            => $user_row->id,
                        'estado_id'             => Pieza::ESTADO_EN_GESTION,
                        'create'                => date("Y-m-d H:i:s"),
                        'update'                => date("Y-m-d H:i:s"),
                        'create_user_id'        =>  $user_row->id,
                        'update_user_id'         => $user_row->id,
                    );
        
                    if (isset($array_piezas_aux)) {
                        $this->db->insert_batch('flash_piezas_novedades', $array_piezas_aux);
                        $this->db->insert_batch('flash_piezas_tracking', $array_piezas_tracking_aux);
                        unset($array_piezas_aux);
                        unset($array_piezas_tracking_aux);
        
                        //Pongo el barcode como una pieza NORMAL
                        $sql = "UPDATE flash_piezas p
                        SET barcode = CONCAT( REPEAT( '0', 6 - LENGTH( p.id) ) , p.id)
                        WHERE p.id = " . $pieza_id;
        
                        $this->db->query($sql);
                    }
                }
                //     echo($this->db->last_query());die;
        
                //AUDITORIA
                $data     = array(
                    'user_id'     => $user_row->id,
                    'categoria'   => 'PIEZAS_PAQUETES',
                    'descripcion' => 'Comprobante: ' . $comprobante_ingreso->numero . '. Agregar Piezas paquetes Masiva.',
                    'origen'      => '',
                    'destino'     => '',
                    'create_user_id' => $user_row->id,
                    'update_user_id' => $user_row->id,
                );
                $this->codegen_model->add('users_log', $data);
                //END AUDITORIA
        
                $sql = "UPDATE flash_piezas p
                        SET barcode = CONCAT( REPEAT( '0', 6 - LENGTH( p.id) ) , p.id)
                    WHERE comprobante_ingreso_id = " . $comprobante_ingreso->id . " AND servicio_id = " . $servicio->id;
                $this->db->query($sql);
                
                unset($array_piezas);

                $this->db->trans_complete();
        
                $insertadas++;
            
            }
            
            /*
            echo "<pre>";
            var_dump($errores);
            echo "</pre>";
            
            var_dump("LLEGO AL FINAL");
            */
            
           
            $objPHPExcel->disconnectWorksheets();
            unset($objPHPExcel);
            unset($array_piezas);
            unset($data);
            if ($final) {
                //echo "final";
                break;
            }

        }

        $this->getPaqueteriaXCliente($cliente_id, $cliente->name, $pais_origen, $cantidad_piezas, $errores, $insertadas);

    }

    
    function geocodificar($pais, $provincia, $localidad, $calle, $altura){

        if(strlen($pais) > 0 and strlen($calle) > 0 and strlen($altura) and strlen($localidad) > 0 and strlen($provincia) > 0){

            $localizacion= $calle . " " . $altura . ',' . $localidad . ',' . $provincia . ',' . $pais;

            //echo json_encode(["data" => $localizacion]);
            //die;

            switch ($pais) {
                case '1':
                    $code = "AR";
                    break;

                case '2':
                    $code = "BO";
                    break;

                case '3':
                    $code = "PE";
                    break;

                default:
                    # code...
                    break;
            }

            //Obtener los resultados JSON de la peticion - Peticion a google maps para obtener coordenadas
            $geo = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($localizacion).'&sensor=false&components=country:'.$code.'&key=AIzaSyBoUqa9B9TMlrAqgKiXI1vsZa_bo_iESIw');
            $geo = json_decode($geo, true);

            try{
                if ($geo['status'] == 'OK' && !empty($geo['results'][0]['geometry']['location']['lat']) && !empty($longitud = $geo['results'][0]['geometry']['location']['lng']) ) {
                    // Obtener los valores
                    $latitud = $geo['results'][0]['geometry']['location']['lat'];
                    $longitud = $geo['results'][0]['geometry']['location']['lng'];

                    $condicion = 1;
                    $mensaje = "Geodecodificada";
                }
                else{ // La geo no fue correcta

                    $condicion = 2;
                    $mensaje = "Corregir Direccion";
                }

            }catch(\Throwable $th){
                echo $th;
            }
            
            //return response()->json(["mensaje" => $mensaje, "condicion" => $condicion, "latitud" => $latitud, "longitud" => $longitud, "geo" => $geo]);
            return ["mensaje" => $mensaje, "condicion" => $condicion, "latitud" => $latitud, "longitud" => $longitud, "geo" => $geo];
        }else{
            $condicion = 3;
            $mensaje = "Faltan datos para Geocodificar";
            
            return ["mensaje" => $mensaje, "condicion" => $condicion];
            //return response()->json(["mensaje" => $mensaje, "condicion" => $condicion]);
        }

    }

    public function crearComprobanteIngreso($comprobante_ingreso_generado, $user, $cliente_id, $departamento_id)
    {
        $etiqueta = $this->codegen_model->row('flash_comprobantes_ingresos_generados', '*', 'id = ' . $comprobante_ingreso_generado->id);
        $talonario = $this->codegen_model->row('flash_piezas_talonarios', '*', 'id = ' . $etiqueta->talonario_id);

        $data = array(
            'talonario_id' => $talonario->id,
            'empresa_id' => null,
            'sucursal_id' => $user->sucursal_id,
            'cliente_id' => $cliente_id,
            'departamento_id' => $departamento_id,
            'numero' => $comprobante_ingreso_generado->numero,
            'cantidad' => 1, //Siempre creamos 1 comp. con un Serv. con 1 pieza
            'estado' => 0,
            'estado_liquidacion'     => 0,
            'fecha_pedido' => date('Y-m-d')
        );
        $comprobante = $this->codegen_model->add('flash_comprobantes_ingresos', $data);
        $data = array('etiquetas_disponibles' => $talonario->etiquetas_disponibles - 1);
        $this->codegen_model->edit('flash_piezas_talonarios', $data, 'id', $talonario->id);

        /* Auditoria */
        $user_row = $this->ion_auth->user()->row();
        $data = array(
            'user_id' => $user_row->id,
            'categoria' => 'COMPROBANTE PARA PAQUETE',
            'descripcion' => 'Nuevo Comprobante: ' . $comprobante,
            'origen' => '',
            'destino' => '',
            'create_user_id' => $user_row->id,
            'update_user_id' => $this->usuario->id,
        );
        $this->codegen_model->add('users_log', $data);
        /* END: Auditoria */
        return $comprobante;
    }

    public function crearComprobanteIngresoServicio($comprobante_ingreso)
    {
        $data = array(
            'comprobante_ingreso_id' => $comprobante_ingreso->id,
            'servicio_id' => 2, //Paqueteria generica
            'cantidad' => 1, //Siempre es un paquete
            'disponible' => 0, //Porque ya se agrega la unica pieza disponible a este servicio
            'remito' => 0
        );

        $comprobante_ingreso_servicio = $this->codegen_model->add('flash_comprobantes_ingresos_servicios', $data);

        $comprobante_generado = ComprobanteGenerado::whereNumero($comprobante_ingreso->numero)->first();

        $query_udpate_comprobante_generado = "UPDATE flash_comprobantes_ingresos_generados 
                                                SET estado = " . ComprobanteGenerado::ESTADO_USADO . "
                                              WHERE id = " . $comprobante_generado->id;

        $this->db->query($query_udpate_comprobante_generado);

        return $comprobante_ingreso_servicio;
    }

}

