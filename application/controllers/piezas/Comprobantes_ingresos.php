<?php

use Illuminate\Database\Capsule\Manager as DB;

class Comprobantes_ingresos extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index($flag = null)
    {
        ini_set('memory_limit','-1');
        $query = Comprobante::orderBy('fecha_pedido', 'desc');
        if ($this->is_user()) {
            $query->where('sucursal_id', $this->usuario->sucursal_id);
        }
        $comprobantes = $query->get();
//        echo($this->db->last_query());die;
        $this->data([
            'comprobantes'    => [], //$comprobantes,
            'empresa_id'      => false,
            'cliente_id'      => false,
            'departamento_id' => false,
            'empresas'        => Empresa::all(),
            'clientes'        => Cliente::where('cliente_estado_id', Cliente::ESTADO_ACTIVO)->get(),
            'departamentos'   => [], // ClienteDepartamento::all(),
            'flag'            => ($flag != null) ? $flag : 'piezas',
        ]);

        $this->title('comprobantes de ingresos');
        $this->view = 'components/piezas/comprobantes_ingresos/comprobantes_ingresos_list';
        $this->template('template/backend');
    }

    public function filtro()
    {
        $empresa_id      = $this->input->post('empresa');
        $cliente_id      = $this->input->post('cliente_id');
        $departamento_id = $this->input->post('dpto');

        $query = Comprobante::orderBy('create', 'desc');

        if ($this->is_user()) {
            $query->where('sucursal_id', $this->usuario->sucursal_id);
        }

        if ($empresa_id) {
            $query->where('empresa_id', $empresa_id);
        }

        if ($cliente_id) {
            $query->where('cliente_id', $cliente_id);
        }

        if ($departamento_id) {
            $query->where('departamento_id', $departamento_id);
        }

        $comprobantes = $query->get();
//echo($this->db->last_query());die;

        $this->data([
            'comprobantes'    => $comprobantes,
            'empresa_id'      => $empresa_id ? $empresa_id : false, //Empresa::whereDefault(1)->first()->id,
            'cliente_id'      => $cliente_id ? $cliente_id : false,
            'departamento_id' => $departamento_id ? $departamento_id : false,
            'empresas'        => Empresa::all(),
            'clientes'        => Cliente::where('cliente_estado_id', Cliente::ESTADO_ACTIVO)->get(),
            'departamentos'   => ClienteDepartamento::all(),
        ]);

        $this->title('comprobantes de ingresos');
        $this->view = 'components/piezas/comprobantes_ingresos/comprobantes_ingresos_list';
        $this->template('template/backend');
    }

    public function piezas($id = null, $flag = null)
    {      // var_dump($_POST);die;
        set_time_limit(1800);
        if ($flag == null) {
            $flag = $this->input->post('flag');
        }

        if ($id != null && $this->input->post('numero') == '') {
            if ($this->is_user()):
                $comprobante = Comprobante::where('sucursal_id', $this->usuario->sucursal_id)->findOrFail($id);
            else:
                $comprobante = Comprobante::findOrFail($id);
            endif;
        } else {
            $numero = trim($this->input->post('numero'));
            if ($this->is_user()):
                $comprobante = Comprobante::where('sucursal_id', $this->usuario->sucursal_id)->whereNumero($numero)->first();
            else:
                $comprobante = Comprobante::whereNumero($numero)->first();
            endif;

        }

        if ($comprobante != null) {
            if ($flag == "piezas") {
                $comprobante_servicios = $this->flash_md->getPiezasPorServiciosPorComprobante($comprobante->id);
                usort($comprobante_servicios, function ($a, $b) {
                    return strcmp($b->creacion, $a->creacion);
                });
            }
        } else {
            $this->session->set_flashdata('registro', $numero);
            redirect(base_url('piezas/piezas/alta_piezas_masivas'));
        }

        $this->data([
            'comprobante'           => $comprobante,
            'comprobante_servicios' => $comprobante_servicios, //ComprobanteServicio::whereComprobanteIngresoId($comprobante->id)->get(),
            'servicios'             => $this->flash_md->getServiciosPorComprobante($comprobante->id),
            'estados'               => PiezaEstado::all(),
            'tipos'                 => PiezaTipo::all(),
            'cliente'               => Cliente::whereId($comprobante->cliente_id)->first(),
        ]);

        if ($flag == 'piezas') {
            $this->title('comprobante ' . $comprobante->numero . ' - alta de piezas');
            $this->view = 'components/piezas/comprobantes_ingresos/comprobantes_ingresos_piezas';
        }
        if ($flag == 'piezas_masivas') {
            $this->title('comprobante ' . $comprobante->numero . ' - alta masiva de piezas');
            $this->view = 'components/piezas/comprobantes_ingresos/comprobantes_ingresos_piezas_masivas';
        }
        $this->template('template/backend');
    }

    public function piezas_add()
    {
        set_time_limit(1800);
        ini_set('memory_limit', '-1');
        $servicio_id         = $this->input->post('servicio_id');
        $cantidad            = $this->input->post('cantidad');
        $barcode_externo     = $this->input->post('barcode_externo');
        $destinatario        = $this->input->post('destinatario');
        $domicilio           = $this->input->post('domicilio');
        $codigo_postal       = $this->input->post('codigo_postal');
        $localidad           = $this->input->post('localidad');
        $datos_varios        = $this->input->post('datos_varios');
        $datos_varios_1      = $this->input->post('datos_varios_1');
        $datos_varios_2      = $this->input->post('datos_varios_2');
        $comprobante_id      = $this->input->post('comprobante_id');
        $descripcion_paquete = $this->input->post('descripcion_paquete');
        $dimensiones         = $this->input->post('dimensiones');
        $peso                = $this->input->post('peso');
        $bulto               = $this->input->post('bulto');
        $servicio_base_id    = $this->input->post('servicio_base_id');
        $comprobante_ingreso = Comprobante::whereId($comprobante_id)->first();
        $user_row = $this->ion_auth->user()->row();
        $datos_varios_3      = $this->input->post('datos_varios_3');
        $email_dest      = $this->input->post('email_dest');
        $celular_dest      = $this->input->post('celular_dest');
        
        //       var_dump($_POST);die;
        foreach ($this->input->post('fila') as $i) {
            $comprobante_servicio = ComprobanteServicio::whereId($servicio_id[$i])->first();
            $servicio             = Servicio::whereId($comprobante_servicio->servicio_id)->first();
            //echo $cantidad[$i]."   -   ";
            for ($j = 0; $j < $cantidad[$i]; $j++) {
                $cantidad_modficado = 1;
                $array_piezas[]     = array(
                    'usuario_id'             => $this->usuario->id,
                    'servicio_id'            => $servicio_id[$i],
                    'tipo_id'                => $servicio->acuse == 1 ? PIEZA_TIPO_NORMAL : PIEZA_TIPO_SIMPLE,
                    'sucursal_id'            => $comprobante_ingreso->sucursal_id, //Modificado las piezas cargadas deberian tener la sucursal del CI $this->usuario->sucursal_id,
                    'estado_id'              => Pieza::ESTADO_EN_GESTION,
                    'cantidad'               => $cantidad_modficado,
                    'comprobante_ingreso_id' => $comprobante_id,
                    'barcode_externo'        => $barcode_externo[$i],
                    'destinatario'           => $destinatario[$i],
                    'domicilio'              => $domicilio[$i],
                    'codigo_postal'          => $codigo_postal[$i],
                    'localidad'              => $localidad[$i],
                    'datos_varios'           => $datos_varios[$i],
                    'datos_varios_1'         => $datos_varios_1[$i],
                    'datos_varios_2'         => $datos_varios_2[$i],
                    'datos_varios_3'         => $datos_varios_3[$i],
                    'create_user_id'         => $user_row->id,
                    'create'                 => date("Y-m-d H:i:s"),
                    'update'                 => date("Y-m-d H:i:s"),
                    'mail_dest'             => $email_dest[$i],
                    'celular_dest'           => $celular_dest[$i],
                    'hoja_ruta_id'              => null,
                );
                if($servicio->acuse == 1 && $barcode_externo[$i] == ""){
                    $this->session->set_flashdata('mensaje', "La pieza ".$destinatario[$i]."</br>"
                                                              .$domicilio[$i]."</br>"
                                                              .$codigo_postal[$i]."</br>"
                                                              .$localidad[$i]."</br>"
                                                              .$email_dest[$i]."</br>"
                                                              .$celular_dest[$i]."</br> NO tiene CODIGO de BARRAS"  );
                    redirect(base_url('piezas/comprobantes_ingresos/index/piezas'));
                }
            }

            //echo "cantidad de piezas antes de ingresar: ".count($array_piezas)."<br/>";
            $this->db->insert_batch('flash_piezas', $array_piezas);
             //echo $this->db->last_query();die;
            //Inserto el primer ID que se creo en la tabla piezas en el array_novedades
            //de ahi deduzco los otros ids y los guardo en el array para luego
            //establecer las novedades en piezas novedades y tracking siempre y cuando sea tipo 2
            $this->alta_flash_piezas_novedades($array_piezas);
            
            //echo $this->db->last_query();die;
            //Si el servicio es 2 (paqueteria) deberia tener una sola pieza y un solo servicio en el comprobante por lo que pregunto por [1]
            // para ingresarla en piezas paquetes
//            var_dump($servicio_base_id[1]) ;die;
            if ($servicio_base_id[1] == 2){ 
                //Traigo el ultimo ID insertado
                $piezas_insertadas = $this->codegen_model->row('flash_piezas','*','id = '.$this->db->insert_id());
                $array_piezas_paquetes[]     = array(
                                        'pieza_id' => $piezas_insertadas->id,
                                        'descripcion_paquete' => $descripcion_paquete[1],
                                        'dimensiones' => $dimensiones[1],
                                        'peso' => $peso[1],
                                        'bultos' => $bulto[1],
                                        'dias_entrega' => '',
                                        'create'                 => date("Y-m-d H:i:s"),
                                        'update'                 => date("Y-m-d H:i:s"),
                                        'create_user_id' => $user_row->id,
                                        );
                $this->db->insert_batch('flash_piezas_paquetes', $array_piezas_paquetes);
                log_message('error', 'Comprobantes_ingresos.php.piezas_add(): ' . date("Y-m-d"));
//                 echo($this->db->last_query());die;
            }
            
            
            /* Auditoria */
            $data     = array(
                'user_id'     => $user_row->id,
                'categoria'   => 'COMPROBANTES',
                'descripcion' => 'Comprobante: ' . $comprobante_ingreso->numero . '. Añadir Piezas SIMPLES o NORMALES.',
                'origen'      => '',
                'destino'     => '',
            );
            $this->codegen_model->add('users_log', $data);
            
            $data     = array(
                'user_id'     => $user_row->id,
                'categoria'   => 'PIEZAS_PAQUETES',
                'descripcion' => 'Comprobante: ' . $comprobante_ingreso->numero . '. Añadir Piezas_paquetes.',
                'origen'      => '',
                'destino'     => '',
            );
            $this->codegen_model->add('users_log', $data);
            /* END: Auditoria */

            $sql = "UPDATE flash_piezas p
                    SET barcode = CONCAT( REPEAT( '0', 6 - LENGTH( p.id) ) , p.id)
                WHERE comprobante_ingreso_id = " . $comprobante_id . " AND servicio_id = " . $servicio_id[$i];

            
            $this->db->query($sql);
            $comprobante_servicio->disponible = $comprobante_servicio->disponible - $cantidad[$i];
            //echo $servicio_id[$i]."  - ".$comprobante_servicio->disponible."<br/>";
            $comprobante_servicio->save();
            unset($array_piezas);
            //unset($array_piezas_novedades);
            //$comprobante = Comprobante::findOrFail($comprobante_servicio->comprobante_ingreso_id);
        }

        $comprobante = Comprobante::findOrFail($comprobante_id);

        $this->session->set_flashdata('registro', $comprobante->numero);
        redirect(base_url('piezas/comprobantes_ingresos/index/piezas'));
    }

    private function alta_flash_piezas_novedades($array_piezas){
        $array_piezas_novedades = [];
        $pieza_novedad_id = $this->db->insert_id();
        //echo "primer id insertado    ".$pieza_novedad_id."<br/>";
        array_push($array_piezas_novedades ,$pieza_novedad_id);
        $pieza_novedad_id++;
        //echo "la cantidada de piezas es ".count($array_piezas);
        for ($p = 0; $p < count($array_piezas)-1; $p++) {
            array_push($array_piezas_novedades, $pieza_novedad_id );
            $pieza_novedad_id++;
        }
        //var_dump($array_piezas_novedades);
        //traigo todas las piezas para discriminar cuales con NORMALES
        $hwere_piezas_id = implode(',',$array_piezas_novedades);
        $piezas_normales_insertadas = $this->codegen_model->get('flash_piezas','id',' tipo_id = 2 AND id IN ( '.$hwere_piezas_id.')');
        unset($array_piezas_novedades);
        //Cuando tengo los IDs de las piezas NORMALES que se insertaron
        //Actualizo las tablas de Trackeo y de Novedades
        //creo el array para insertar en la tabla de novedades
        //$array_piezas_aux[]=array();
        //var_dump($array_piezas_aux);
        //echo "piezas normales insertadas ".empty($piezas_normales_insertadas);var_dump($piezas_normales_insertadas);
        if(!empty($piezas_normales_insertadas)){
            
            foreach($piezas_normales_insertadas as $pieza_normal){
                    $array_piezas_aux[]     = array(
                        'pieza_id'              => $pieza_normal->id,
                        'cantidad'              => 1,
                        'usuario_id'            => $this->usuario->id,
                        'estado_actual_id'      => Pieza::ESTADO_EN_GESTION, 
                        'estado_nuevo_id'       => Pieza::ESTADO_EN_GESTION,
                        'create'                => date("Y-m-d H:i:s"),
                        'update'                => date("Y-m-d H:i:s"),
                        'create_user_id'        =>  $this->usuario->id,
                        'update_user_id'         => $this->usuario->id,
                    );
                    $array_piezas_tracking_aux[]     = array(
                        'pieza_id'              => $pieza_normal->id,
                        'usuario_id'            => $this->usuario->id,
                        'estado_id'             => Pieza::ESTADO_EN_GESTION, 
                        'create'                => date("Y-m-d H:i:s"),
                        'update'                => date("Y-m-d H:i:s"),
                        'create_user_id'        =>  $this->usuario->id,
                        'update_user_id'         => $this->usuario->id,
                    );
            }
        }
            // echo "el auxiliar es ".count($array_piezas_aux);var_dump($array_piezas_aux);
        if (isset($array_piezas_aux)){
            //echo "ya para insertar";
            //var_dump($array_piezas_aux);
            $this->db->insert_batch('flash_piezas_novedades', $array_piezas_aux);
            $this->db->insert_batch('flash_piezas_tracking', $array_piezas_tracking_aux);
            unset($array_piezas_aux);
            unset($array_piezas_tracking_aux);
        }
        return;
    }

    public function piezas_masivas_add_xls()
    {
        set_time_limit(1800); //1800 segundos ejecucion PHP
        //ini_set('max_execution_time', 1800); //1800 segundos ejecucion mysql
        // ini_set('upload_max_filesize', '10M');

        $filename        = $this->input->post('filename');
        $cantidad_piezas = $this->input->post('cantidad_piezas');

        $path = "";
        if ($_SERVER['HTTP_HOST'] == "localhost") {
// For local
            $path = $_SERVER['DOCUMENT_ROOT'] . 'gestionpostal/assets/upload/';
        } else {
            // For Web
            $path = $_SERVER['DOCUMENT_ROOT'] . '/assets/upload/';
        }
        $this->load->library('excel');
        $this->load->library('chunkReadFilter');
        $objReader = PHPExcel_IOFactory::createReader('Excel5');

        $comprobante_servicio = ComprobanteServicio::whereId($this->input->post('servicio_id'))->first();

        $servicio_id         = $this->input->post('servicio_id');
        $comprobante_id      = $comprobante_servicio->comprobante_ingreso_id; //$this->input->post('comprobante_id');
        $servicio_acuse      = Servicio::whereId($comprobante_servicio->servicio_id)->first();
        $comprobante_ingreso = Comprobante::whereId($comprobante_id)->first();
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
        //echo "comienzo principal ".date("H:i:s")."<br/>";
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
            $objReader->setReadDataOnly(true);
            /**  Load only the rows that match our filter from $inputFileName to a PHPExcel Object  **/
            $objPHPExcel = $objReader->load($path . $filename);
            $sheetData   = $objPHPExcel->getActiveSheet(); //->toArray(null,true,true,true);

            //echo "comienzo bloque ".date("H:i:s")."<br/>";
            $i = 0;
            for ($x; $x <= $startRow + $chunkSize + 1; $x++) {
                //echo $i."  -  ".$x."  -  ".trim($sheetData[$x]['E'])."<br/>";
                $array_piezas[$i] = array(
                    'usuario_id'             => $this->usuario->id,
                    'servicio_id'            => $servicio_id,
                    'tipo_id'                => $servicio_acuse->acuse == 1 ? PIEZA_TIPO_NORMAL : PIEZA_TIPO_SIMPLE,
                    'sucursal_id'            => $comprobante_ingreso->sucursal_id, //Modificado las piezas cargadas deberian tener la sucursal del CI $this->usuario->sucursal_id,
                    'estado_id'              => Pieza::ESTADO_EN_GESTION,
                    'comprobante_ingreso_id' => $comprobante_id,
                    'cantidad'               => 1,
                    'barcode_externo'        => $sheetData->getCell("E" . $x)->getValue(), //trim($sheetData[$x]['E']),
                    'destinatario'           => $sheetData->getCell("A" . $x)->getValue(), //trim($sheetData[$x]['A']),
                    'domicilio'              => $sheetData->getCell("B" . $x)->getValue(), //trim($sheetData[$x]['B']),
                    'codigo_postal'          => $sheetData->getCell("C" . $x)->getValue(), //trim($sheetData[$x]['C']),
                    'localidad'              => $sheetData->getCell("D" . $x)->getValue(), //trim($sheetData[$x]['D']),
                    'datos_varios'           => $sheetData->getCell("F" . $x)->getValue(), //trim($sheetData[$x]['F']),
                    'datos_varios_1'         => $sheetData->getCell("G" . $x)->getValue(), //trim($sheetData[$x]['G']),
                    'datos_varios_2'         => $sheetData->getCell("H" . $x)->getValue(), //trim($sheetData[$x]['H']),
                    'datos_varios_3'         => $sheetData->getCell("I" . $x)->getValue(), //trim($sheetData[$x]['H']),
                    'mail_dest'             => $sheetData->getCell("J" . $x)->getValue(), //trim($sheetData[$x]['H']),
                    'celular_dest'           => $sheetData->getCell("K" . $x)->getValue(), //trim($sheetData[$x]['H']),
                    'create'                 => date("Y-m-d H:i:s"),
                    'update'                 => date("Y-m-d H:i:s"),
                    'hoja_ruta_id'                 => null,
                    
                );
                $i++;
            }
            //var_dump($array_piezas);die;
            //echo "fin bloque ".date("H:i:s")."<br/>";
            //echo "Cantidad de piezas ".count($array_piezas)."<br/>";
            $this->db->insert_batch('flash_piezas', $array_piezas);
            $this->alta_flash_piezas_novedades($array_piezas);
            //echo($this->db->last_query());die;
            /* Auditoria */
            $user_row = $this->ion_auth->user()->row();
            $ip = $_SERVER['REMOTE_ADDR']?:($_SERVER['HTTP_X_FORWARDED_FOR']?:$_SERVER['HTTP_CLIENT_IP']);
            $data     = array(
                'user_id'     => $user_row->id,
                'categoria'   => 'COMPROBANTES',
                'descripcion' => 'Nuevo Comprobante: ' . $comprobante_ingreso->numero . 'Piezas masivas desde XLS.',
                'origen'      => 'archivo_excel: '.$filename.' ip: '.$ip,
                'destino'     => '',
            );
            $this->codegen_model->add('users_log', $data);
            /* END: Auditoria */

            //    Free up some of the memory
            $objPHPExcel->disconnectWorksheets();
            unset($objPHPExcel);
            unset($array_piezas);
            unset($data);
            if ($final) {
                break;
            }

        }
        //die;
        //echo "fin principal ".date("H:i:s")."<br/>";
        //echo "comienzo update ".date("H:i:s")."<br/>";
        $sql = "UPDATE flash_piezas p
                    SET barcode = CONCAT( REPEAT( '0', 6 - LENGTH( p.id) ) , p.id)
                WHERE comprobante_ingreso_id = " . $comprobante_id . " AND servicio_id = " . $servicio_id;
        $this->db->query($sql);
        // echo "fin update ".date("H:i:s")."<br/>";die;
        $resta          = $comprobante_servicio->disponible - $cantidad_piezas;
        $sql_disponible = " UPDATE flash_comprobantes_ingresos_servicios SET disponible = " . $resta . " where id = " . $comprobante_servicio->id;
        $this->db->query($sql_disponible);
        //$comprobante_servicio->disponible = $comprobante_servicio->disponible - $cantidad_piezas;
        //$comprobante_servicio->save();
        $comprobante = $this->codegen_model->row("flash_comprobantes_ingresos", " * ", "id = " . $comprobante_servicio->comprobante_ingreso_id);
        $this->session->set_flashdata('registro', $comprobante->numero);
        redirect(base_url('piezas/comprobantes_ingresos/index/piezas_masivas'));
    }

    /*public function piezas_masivas_add()
    {
        $filename = $this->input->post('filename');
//        $path = 'd:\\upload\\';//base_url().'assets/upload/';
        //        $file = fopen($path.$filename,"r");
        //        $cantidad_piezas = 0;
        //        $piezas = null;
        //        while(! feof($file))
        //          {
        //            $piezas[$cantidad_piezas] = (fgetcsv($file, 10000,";"));
        //            $cantidad_piezas++;
        //          }
        $path = $_SERVER['DOCUMENT_ROOT'] . '/assets/upload/';

        $file            = fopen($path . $filename, "r");
        $cantidad_piezas = 0;
        $piezas          = null;
        while (!feof($file)) {
            $piezas[$cantidad_piezas] = (fgetcsv($file, 10000, ";"));
            $cantidad_piezas++;
        }

        fclose($file);
        for ($i = 1; $i < count($piezas); $i++) {
            for ($j = 0; $j < count($piezas[$i]); $j++) {
                if ($piezas[$i] != null) {
                    $respuesta[$i][$j] = $piezas[$i][$j];
                }

//                            var_dump($respuesta[$i][$j])."<br/>";
            }
        }
        echo "llega";die;
        $comprobante_servicio            = $this->codegen_model->row('flash_comprobantes_ingresos_servicios', '*', 'id = ' . $this->input->post('servicio_id'));
        $servicio                        = $this->codegen_model->row('flash_servicios', '*', 'id = ' . $comprobante_servicio->servicio_id);
        $respuesta['servicio']['nombre'] = $servicio->nombre;

        $contador    = count($respuesta);
        $servicio_id = $this->input->post('servicio_id');
        //$tipo_id = 2; //2=Piezas Normales 1=Piezas Simples
        //$cantidad = $contador-1;
        //echo $contador;die;
        $comprobante_id = $comprobante_servicio->comprobante_ingreso_id; //$this->input->post('comprobante_id');
        set_time_limit(1800);
        ini_set('memory_limit', '-1');
//        echo date("H:m:s")."<br/>";
        //$comprobante_servicio = ComprobanteServicio::whereId($servicio_id[$i])->first();
        $servicio_acuse = Servicio::whereId($comprobante_servicio->servicio_id)->first();
        for ($i = 1; $i < $contador; $i++) {
            $array_piezas[$i] = array(
                'usuario_id'             => $this->usuario->id,
                'servicio_id'            => $servicio_id,
                'tipo_id'                => $servicio_acuse->acuse == 1 ? PIEZA_TIPO_NORMAL : PIEZA_TIPO_SIMPLE,
                'sucursal_id'            => $this->usuario->sucursal_id,
                'estado_id'              => Pieza::ESTADO_EN_GESTION,
                'comprobante_ingreso_id' => $comprobante_id,
                'cantidad'               => 1,
                'barcode_externo'        => $respuesta[$i][4],
                'destinatario'           => $respuesta[$i][0],
                'domicilio'              => $respuesta[$i][1],
                'codigo_postal'          => $respuesta[$i][2],
                'localidad'              => $respuesta[$i][3],
                'datos_varios'           => $respuesta[$i][5],
                'datos_varios_1'         => $respuesta[$i][6],
                'datos_varios_2'         => $respuesta[$i][7],
                'datos_varios_3'         => $respuesta[$i][8],
                'create'                 => date("Y-m-d H:i:s"),
                'update'                 => date("Y-m-d H:i:s"),
            );
        }
        var_dump($array_piezas);die;
        echo date("H:m:s") . "<br/>";
        $this->db->insert_batch('flash_piezas', $array_piezas);
        $piezas_guardadas = Pieza::whereComprobanteIngresoId($comprobante_id)->get();
        foreach ($piezas_guardadas as $key => $value) {
            $data[] = array(
                'id'      => $value->id,
                'barcode' => sprintf("%06d", $value->id),
            );
        }
        $this->db->update_batch('flash_piezas', $data, 'id');
//        $pieza->barcode = sprintf("%06d", $pieza->id);
        //        $pieza->save();
        $cantidad_piezas--; //Quito el encabezado
        //$comprobante_servicio = ComprobanteServicio::where('id',$servicio_id)->first();
        $comprobante_servicio->disponible = $comprobante_servicio->disponible - $cantidad_piezas;
        $comprobante_servicio->save();
        $comprobante = Comprobante::findOrFail($comprobante_servicio->comprobante_ingreso_id);
        $this->session->set_flashdata('registro', $comprobante->numero);
//        echo date("H:m:s")."<br/>";
        redirect(base_url('piezas/comprobantes_ingresos/index/piezas_masivas'));
    }*/

    public function view($id)
    {
        $comprobante                       = Comprobante::findOrFail($id);
        $this->data->comprobante_servicios = ComprobanteServicio::whereComprobanteIngresoId($comprobante->id)->get();
//        echo $this->db->last_query();die;

        $this->title('comprobantes de ingresos');
        $this->view = 'components/piezas/comprobantes_ingresos/comprobantes_ingresos_view';
        $this->template('template/fancybox');
    }

    public function archivar()
    {
        $comprobante_id      = $this->input->post('comprobante_id');
        $estado              = $this->input->post('estado');
        $comprobante         = Comprobante::findOrFail($comprobante_id);
        $comprobante->estado = $estado;
        $comprobanteNumero   = $comprobante->numero;
        $comprobante->save();

        if ($estado == Comprobante::ESTADO_CANCELADA) {
            foreach ($comprobante->piezas as $pieza) {
                $comprobante->estado_id = Comprobante::ESTADO_EN_GESTION;
                $comprobante->save();

                /* Auditoria */
                $user_row = $this->ion_auth->user()->row();
                $data     = array(
                    'user_id'     => $user_row->id,
                    'categoria'   => 'COMPROBANTES',
                    'descripcion' => 'Comprobante: ' . $comprobanteNumero . '. Estado : ' . $comprobante->estado_id . '. Archivar.',
                    'origen'      => '',
                    'destino'     => '',
                );
                $this->codegen_model->add('users_log', $data);
                /* END: Auditoria */

            }
        }

        $this->data->success = true;
        //echo var_dump($this->json());die;
        //        $this->json();
        print json_encode($this->data);

    }

    public function exportarAPDF()
    {
        $comprobante_ingreso_id = $this->input->post('pdf_comprobante_ingreso_id');
        $impresion_tipo         = $this->input->post('impresion_tipo');
        $servicio_id            = $this->input->post('pdf_servicio_id');
        $limite                 = $this->input->post('pdf_limite_inferior');
        //var_dump($_POST);die;
        if ($impresion_tipo == 'acuse') {
            $this->exportarAcuseAPDF($comprobante_ingreso_id, $servicio_id, $limite);
        }

        if ($impresion_tipo == 'etiqueta') {$this->exportarEtiquetasAPDF($comprobante_ingreso_id, $servicio_id, $limite);}

    }

    public function exportarAcuseAPDF($comprobante_ingreso_id, $servicio_id, $limite)
    {

        //set_time_limit(1800000); //1800 segundos ejecucion PHP
        ini_set('max_execution_time', 18000); //1800 segundos ejecucion mysql
        ini_set('memory_limit', '-1');
        $this->load->library('simplehtmldom_1_5/simple_html_dom');
        $this->load->library('Pdf_noheader');
        $piezas = $this->flash_md->getPiezasPorServiciosPorComprobanteSinGroup( /*44,71*/$comprobante_ingreso_id, $servicio_id, $limite);
        //Set custom header data
        $custom_layout = array(220, 340);
        $pdf           = new Pdf_noheader('P', 'mm', 'Legal', true, 'UTF-8', false); //: portrait, L:landscape

        $pdf->SetTitle('Acuse');
        $pdf->SetAutoPageBreak(true);
        $pdf->SetAuthor('Author');
        $pdf->SetDisplayMode('real', 'default');
        $pdf->Write(5, 'CodeIgniter TCPDF Integration');

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set auto page breaks
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
            require_once dirname(__FILE__) . '/lang/eng.php';
            $pdf->setLanguageArray($l);
        }

        // ---------------------------------------------------------

        // set default font subsetting mode
        $pdf->setFontSubsetting(true);

        // Set font
        // dejavusans is a UTF-8 Unicode font, if you only need to
        // print standard ASCII chars, you can use core fonts like
        // helvetica or times to reduce file size.
        $pdf->SetFont('dejavusans', '', 8, '', true);
        $pdf->SetMargins(2, 2, 2);
        // Add a page
        // This method has several options, check the source code documentation for more information.
        $pdf->AddPage('P', $custom_layout, false, false);

        // create some HTML content
        //$subtable = '<table border="1" cellspacing="6" cellpadding="4"><tr><td>a</td><td>b</td></tr><tr><td>c</td><td>d</td></tr></table>';
        //Para que aparezca un borde poner border:inset 0; en style
        $table_contenido = "";
        //$pdf->Image(APPPATH.'media/logo_flash.gif', 1, 1, 2.5, 0.6);
        $image_file   = APPPATH . 'media/logo_flash.gif';
        $image_square = APPPATH . 'media/square.gif';
        $style        = array(
            'border'   => false,
            'position' => 'absolute',
//                        'align' => 'C',
            //                        'stretch' => false,
            //                        'fitwidth' => true,
            //                        'cellfitalign' => '',
            //                        'border' => false,
            'hpadding' => '10',
            'vpadding' => '20',
//                        'fgcolor' => array(0,0,0),
            //                        'bgcolor' => array(255,255,255),
            'text'     => true,
            'font'     => 'helvetica',
            'fontsize' => 8,
//                        'stretchtext' => 4
        );

        $table_contenido = '';
        $contador        = 0;
        set_time_limit(18000);
        $aux = 0;
        foreach ($piezas as $pieza) {//var_dump($pieza);die;
            //if ($aux > 500 ) break;
            $aux++;
//            for($i=0;$i<20;$i++){
            //                $pieza = $piezas[0];
            if ($contador > 4) {
                $pdf->AddPage('P', $custom_layout, false, false);
                $contador = 0;
            }

            $params          = $pdf->serializeTCPDFtagParameters(array($pieza->pieza_id, 'C39', '', '', 40, 10, 0.4, array('position' => 'S', 'border' => false, 'padding' => 1, 'fgcolor' => array(0, 0, 0), 'bgcolor' => array(255, 255, 255), 'text' => true, 'font' => 'helvetica', 'fontsize' => 7, 'stretchtext' => 4), ''));
            $table_contenido = '<table width="720px" border="0" style="border-collapse: separate;border-spacing: 5px 0px;border-bottom-width: 0px solid;">
                                    <tr>
                                        <td width="132.3px" align="center" padding="0px" rowspan="5" style="margin-top:0px;margin-bottom:0px;"><img src="' . $image_file . '" width="94.5px" height="20px"/>
                                                                                           <label style="line-height:7px;font-size:7px;text-align:center">R.N.P.S.P. 687</label><br/>
                                                                                           <label style="line-height:7px;font-size:7px;text-align:center">Tel: 0381-4200922 PBX</label>
                                                                                           <label style="line-height:7px;font-size:7px;text-align:center">info@correoflash.com</label>
                                                                                           <label style="line-height:7px;font-size:7px;text-align:center">www.correoflash.com</label>
                                        </td>
                                        <td width="151.2px" height="10px"  style="font-size:7.5px;">Cliente:</td>
                                        <td width="132.3px" ></td>
                                        <td width="75.6px"></td>
                                        <td width="75.6px"></td>
                                        <td width="189px" rowspan="2"><tcpdf method="write1DBarcode" params="' . $params . '" /></td>
                                    </tr>
                                    <tr>

                                        <td width="100%" height="10px" style="font-size:8px" colspan="4">' . $pieza->cliente . ' - ' . $pieza->departamento . '</td>


                                    </tr>
                                    <tr>

                                        <td width="151.2px" height="10px" style="font-size:7.5px">' . $pieza->precio_especial_nombre . '</td>
                                        <td width="132.3px"></td>
                                        <td width="75.6px"></td>
                                        <td width="75.6px"></td>
                                        <td width="189px"></td>
                                    </tr>
                                    <tr>

                                        <td width="151.2px" height="8px" style="font-size:7.5px">DESTINATARIO</td>
                                        <td  colspan="3" width="100%">' . $pieza->destinatario . '</td>

                                    </tr>
                                    <tr>

                                        <td width="151.2px" height="8px" style="font-size:7.5px">DOMICILIO</td>
                                        <td width="100%" colspan="3" style="font-size:7.5px">' . $pieza->domicilio . '</td>
                                        <td width="75.6px"></td>
                                        <td width="75.6px"></td>
                                        <td width="189px"></td>
                                    </tr>
                                    <tr>
                                        <td width="132.3px" text-align="center"  rowspan="6" >
                                                                                <img src="' . $image_square . '" align="middle" width="9px" height="9px"/><label style="font-size:5.6px;margin-top:0px;margin-bottom:3px;">01-NO RESPONDE</label><br>
                                                                                <img src="' . $image_square . '" align="middle" width="9px" height="9px"/><label style="font-size:5.6px;margin-top:0px;margin-bottom:3px;">02-NO EXISTE EL Nº</label><br>
                                                                                <img src="' . $image_square . '" align="middle" width="9px" height="9px"/><label style="font-size:5.6px;margin-top:0px;margin-bottom:3px;">03-NO EXISTE DIR</label><br>
                                                                                <img src="' . $image_square . '" align="middle" width="9px" height="9px"/><label style="font-size:5.6px;margin-top:0px;margin-bottom:3px;">05-DEST DESC.</label><br>
                                                                                <img src="' . $image_square . '" align="middle" width="9px" height="9px"/><label style="font-size:5.6px;margin-top:0px;margin-bottom:3px;">06-DCLIO ABAND</label><br>
                                                                                <img src="' . $image_square . '" align="middle" width="9px" height="9px"/><label style="font-size:5.6px;margin-top:0px;margin-bottom:3px;">07-DCLIO INSUF</label><br>
                                                                                <img src="' . $image_square . '" align="middle" width="9px" height="9px"/><label style="font-size:5.6px;margin-top:0px;margin-bottom:3px;">08-SE MUDO</label><br>
                                                                                <img src="' . $image_square . '" align="middle" width="9px" height="9px"/><label style="font-size:5.6px;margin-top:0px;margin-bottom:3px;">10-FALLECIO</label><br>
                                                                                <img src="' . $image_square . '" align="middle" width="9px" height="9px"/><label style="font-size:5.6px;margin-top:0px;margin-bottom:3px;">12-SE NIEGA A REC</label><br>
                                                                                <img src="' . $image_square . '" align="middle" width="9px" height="9px"/><label style="font-size:5.6px;margin-top:0px;margin-bottom:3px;">OTROS ____________</label></td>
                                        <td width="151.2px" style="font-size:7.5px">CP LOCALIDAD</td>
                                        <td width="300px" colspan="3" style="font-size:8px">' . $pieza->codigo_postal . '   ' . $pieza->localidad . '</td>
                                        <td width="189px" style="text-align:center;font-size:9px">.......................................<br/>       DNI</td>
                                    </tr>
                                    <tr>

                                        <td width="151.2px" height="8px"style="font-size:7.5px">DATOS VARIOS</td>
                                        <td width="100px"  colspan="1">' . $pieza->datos_varios . '</td>
                                        <td width="189px" colspan="2">EMAIL: '.$pieza->email.'</td>
                                    </tr>
                                    <tr>
                                        <td height="8px" width="151.2px" style="line-height:20px;font-size:7.5px;border-left-style:solid; 1px solid black;border-top-style:solid; 1px solid black;border-right-style:solid; 1px solid black;">FECHA 1 VISITA____/____/____</td>
                                        <td width="132.3px" width="151.2px" style="line-height:10px;font-size:7.5px;border-left-style:solid; 1px solid black;border-top-style:solid; 1px solid black;border-right-style:solid; 1px solid black;"><label style="text-align:center">DESCRIPCION DEL LUGAR</label><br/>FRENTE</td>
                                        <td width="75.6px" style="text-align:center;line-height:20px;font-size:7.5px;border-left-style:solid; 1px solid black;border-top-style:solid; 1px solid black;border-right-style:solid; 1px solid black;">LATERALES</td>
                                        <td width="75.6px" style="text-align:center;line-height:20px;font-size:7.5px;border-left-style:solid; 1px solid black;border-top-style:solid; 1px solid black;border-right-style:solid; 1px solid black;border-bottom-style:solid; 1px solid black;">DISTRIBUIDOR</td>
                                        <td width="189px"  style="text-align:center;font-size:9px"><br/>.......................................<br/>       ACLARACION</td>
                                    </tr>
                                    <tr>

                                        <td width="151.2px" style="line-height:20px;font-size:7.5px;border-left-style:solid; 1px solid black;border-top-style:solid; 1px solid black;border-right-style:solid; 1px solid black;">FECHA 2 VISITA____/____/____</td>
                                        <td width="132.3px" width="151.2px" style="line-height:20px;font-size:7.5px;border-left-style:solid; 1px solid black;border-top-style:solid; 1px solid black;border-right-style:solid; 1px solid black;">VENTANA</td>
                                        <td width="75.6px" style="text-align:center;line-height:20px;font-size:7.5px;border-left-style:solid; 1px solid black;border-top-style:solid; 1px solid black;border-right-style:solid; 1px solid black;border-bottom-style:solid; 1px solid black;"> DER   |   IZQ </td>
                                        <td width="75.6px" style="line-height:20px;font-size:7.5px;border-left-style:solid; 1px solid black;border-top-style:solid; 1px solid black;border-right-style:solid; 1px solid black;border-bottom-style:solid; 1px solid black;"></td>
                                        <td width="189px"></td>
                                    </tr>
                                    <tr>

                                        <td width="151.2px" cellpadding="10" style="line-height:20px;font-size:7.5px;border-left-style:solid; 1px solid black;border-top-style:solid; 1px solid black;border-right-style:solid; 1px solid black;border-bottom-style:solid; 1px solid black;"><br>FECHA 3 VISITA____/____/____</td>
                                        <td width="132.3px" width="151.2px" style="line-height:20px;font-size:7.5px;border-left-style:solid; 1px solid black;border-top-style:solid; 1px solid black;border-right-style:solid; 1px solid black;border-bottom-style:solid; 1px solid black;">PUERTA</td>
                                        <td width="75.6px" style="line-height:20px;font-size:7.5px;border-left-style:solid; 1px solid black;border-top-style:solid; 1px solid black;border-right-style:solid; 1px solid black;border-bottom-style:solid; 1px solid black;"></td>
                                        <td width="75.6px"></td>
                                        <td width="189px"></td>
                                    </tr>
                                    <tr>

                                        <td width="151.2px" style="line-height:10px;font-size:7.5px;">F ING' . $pieza->fecha_ingreso . '    CI ' . $pieza->comprobante_numero . '</td>
                                        <td width="151.2px" style="line-height:10px;font-size:7.5px;">BARRA EXT  ' . $pieza->barcode_externo . '</td>
                                        <td width="75.6px"></td>
                                        <td width="120px" style="text-align:center">...................................<br/>       vinculo</td>
                                        <td width="120px" style="text-align:center">...................................<br/>       firma</td>
                                    </tr></table><br/>';
            $contador++;
            $pdf->writeHTML($table_contenido, true, false, true, false, '');
        }
        // $path = $_SERVER['DOCUMENT_ROOT'] .'/assets/upload/acuse_'.date(YmdHis).'pdf';
        // $pdf->Output($path, 'F');
        $pdf->Output('Acuses.pdf', 'I');
    }

    public function exportarEtiquetasAPDF($comprobante_ingreso_id, $servicio_id, $limite)
    {
        set_time_limit(18000); //1800 segundos ejecucion PHP
        $this->load->library('simplehtmldom_1_5/simple_html_dom');
        $this->load->library('Pdf_noheader_nofooter');
        $piezas = $this->flash_md->getPiezasPorServiciosPorComprobanteSinGroup($comprobante_ingreso_id, $servicio_id, $limite);
        //Set custom header data
        $custom_layout = array(32, 65);
        $pdf           = new Pdf_noheader_nofooter('L', 'mm', $custom_layout, true, 'UTF-8', false); //: portrait, L:landscape

        $pdf->SetTitle('Acuse');
        //$pdf->SetAutoPageBreak(true);
        $pdf->SetAuthor('Author');
        $pdf->SetDisplayMode('real', 'default');
        $pdf->Write(5, 'CodeIgniter TCPDF Integration');

        // set default monospaced font
        //$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set auto page breaks
        $pdf->SetAutoPageBreak(true, 0);

        // set image scale factor
        //$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
            require_once dirname(__FILE__) . '/lang/eng.php';
            $pdf->setLanguageArray($l);
        }
        // ---------------------------------------------------------

        // set default font subsetting mode
        $pdf->setFontSubsetting(true);

        // Set font
        // dejavusans is a UTF-8 Unicode font, if you only need to
        // print standard ASCII chars, you can use core fonts like
        // helvetica or times to reduce file size.
        $pdf->SetFont('dejavusans', '', 8, '', true);
        $pdf->SetMargins(2, 2, 2);
        // set Rotate
        $params = $pdf->serializeTCPDFtagParameters(array(90));
        // Add a page
        // This method has several options, check the source code documentation for more information.

        // create some HTML content
        //$subtable = '<table border="1" cellspacing="6" cellpadding="4"><tr><td>a</td><td>b</td></tr><tr><td>c</td><td>d</td></tr></table>';
        //Para que aparezca un borde poner border:inset 0; en style

        $table_contenido = "";
        //$pdf->Image(APPPATH.'media/logo_flash.gif', 1, 1, 2.5, 0.6);
        $image_file_rotate = APPPATH . 'media/logo_flash_rotate.gif';
        $rnpsp687_rotate   = APPPATH . 'media/rnpsp687_rotate.gif';

        $table_contenido = '';
        $i               = 0;
        $aux             = 0;
        foreach ($piezas as $pieza) {
            //if ($aux > 500) break;
            $aux++;
            $pdf->AddPage('L', $custom_layout, false, false);
            $y    = 5;
            $x    = $pdf->GetX();
            $y    = $pdf->GetY();
            $xpos = 5;
            $ypos = 25;
            // The width is set to the the same as the cell containing the name.
            // The Y position is also adjusted slightly.
            $pdf->write1DBarcode($pieza->pieza_id, 'C39', $xpos, $ypos, 55, 5, 0.4, $style, 'M');
            //Reset X,Y so wrapping cell wraps around the barcode's cell.
            $pdf->SetXY($x, $y);
//            $pdf->Cell(105, 51, $c->nome, 1, 0, 'C', FALSE, '', 0, FALSE, 'C', 'B');
            $table_contenido = '<table width="140px" height="189px" border="0" style="">
                                    <tr>
                                        <td width="20px" rowspan="6">
                                            <img src="' . $image_file_rotate . '" width="10px" height="65.6px"/>
                                        </td>
                                        <td width="15px" rowspan="6">
                                            <img src="' . $rnpsp687_rotate . '" width="7.5px" height="56px"/>
                                        </td>
                                        <td width="140px" style="font-size:7px;line-height:10px" colspan="4">' . $pieza->destinatario . '</td>
                                    </tr>
                                    <tr>

                                        <td width="140px" style="font-size:7px;line-height:10px" colspan="4">' . substr($pieza->domicilio, 0, 26) . '</td>

                                    </tr>

                                    <tr>

                                        <td width="140px" style="font-size:7px;line-height:10px" colspan="4">' . $pieza->codigo_postal . '  ' . $pieza->localidad . '</td>

                                    </tr>
                                    <tr>

                                        <td  width="140px" style="font-size:7px;line-height:15px;text-align:right" colspan="4">' . $pieza->comprobante_numero . '</td>

                                    </tr>
                                    <tr>
                                        <td width="100px" colspan="2" style="line-height:25px;">' . $pieza->fecha_ingreso . '</td>
                                        <td width="65px" colspan="2" style="line-height:25px;">' . $pieza->pieza_id . '</td>
                                        <td width="140px" style="font-size:7px;line-height:25px" colspan="4"align="left"></td>

                                    </tr>

                                    <tr>



                                    </tr>
                                </table>';
            $pdf->writeHTML($table_contenido, true, false, true, false, '');
        }

//            echo $html.$table_contenido.$cierre_table;die;

        $pdf->Output('etiquetas.pdf', 'I');
    }

    protected function view_index($view)
    {
        $this->view = $view;
    }

    public function modificar_comprobantes_comprobar()
    {
        if ($this->input->is_post()) {
            $numero = trim($this->input->post('numero'));
            $query  = Comprobante::whereNumero($numero)->whereEstado(ComprobanteGenerado::ESTADO_USADO)->whereEstadoLiquidacion(0);
            if ($comprobante = $query->first()) {
                redirect(base_url('piezas/comprobantes_ingresos/modificar_comprobantes_index/' . $comprobante->id));
            } else {
                $this->session->set_flashdata('registro', $numero);
            }
        }

        $this->title('Modificación de comprobantes de ingresos');
        $this->view_index('components/piezas/comprobantes_ingresos/comprobantes_ingresos_index');
        $this->template('template/backend');
    }

    public function modificar_comprobantes_index($comprobante_id)
    {
        $comprobante         = Comprobante::findOrFail($comprobante_id);
        $comprobante_detalle = $this->flash_md->getComprobanteDetalle($comprobante->id);

        $query_servicios = $this->db
            ->select('pe.cliente_id , pe.id, s.codigo, pe.nombre, pe.precio, pe.dias, pe.servicio_id')
            ->from('flash_servicios s')
            ->join('flash_clientes_precios_especiales pe', 's.id = pe.servicio_id')
            ->where('pe.cliente_id', $comprobante->cliente_id)
            ->get();
//        echo $this->db->last_query();die;
        $this->data([
            'comprobante'         => $comprobante,
            'empresas'            => Empresa::all(),
            'sucursales'          => $this->is_user() ? Sucursal::whereId($this->usuario->sucursal_id)->get() : Sucursal::all(),
            'clientes'            => Cliente::select('id', DB::raw('CONCAT(nombre," (",nombre_fantasia,")") as name'))->where('cliente_estado_id', Cliente::ESTADO_ACTIVO)->get(),
            'cliente'             => Cliente::findOrFail($comprobante->cliente_id),
            'servicios'           => $query_servicios->result(), //ClientePrecioEspecial::whereClienteId($comprobante->cliente_id)->get(),
            'departamentos'       => ClienteDepartamento::whereClienteId($comprobante->cliente_id)->get(),
            'comprobante_detalle' => $comprobante_detalle,
        ]);

        $this->title('comprobantes de ingresos');
        $this->view = 'components/piezas/comprobantes_ingresos/comprobantes_ingresos_edit';
        $this->template('template/backend');
    }

    public function agregar_servicio()
    {
        $cantidad     = 0;
        $etiqueta     = $this->codegen_model->row('flash_comprobantes_ingresos_generados', '*', 'id = ' . $this->input->post('numero_id'));
        $talonario    = $this->codegen_model->row('flash_piezas_talonarios', '*', 'id = ' . $etiqueta->talonario_id);
        $comprobante  = Comprobante::findOrFail($this->input->post('comprobante_ingreso_id'));
        $fecha_pedido = $this->input->post('fecha_pedido');
        $fecha_pedido = new DateTime($fecha_pedido);
        $servicio_id  = $this->input->post('servicio_' . $i);
        
        //Modificar flash_comprobantes_ingresos
        $data = array(
            //'id' => $comprobante->id,
            'cliente_id'      => $this->input->post('cliente_id'),
            'empresa_id'      => $this->input->post('empresa_id'),
            'sucursal_id'     => $this->input->post('sucursal_id'),
            'departamento_id' => $this->input->post('departamento_id'),
            'fecha_pedido'    => $fecha_pedido->format('Y-m-d 00:00:00'),
            'create'          => $fecha_pedido->format('Y-m-d 00:00:00')
        );
        $this->codegen_model->edit('flash_comprobantes_ingresos', $data, 'id', $comprobante->id);
        /* Auditoria */
        $user_row = $this->ion_auth->user()->row();
        $data     = array(
            'user_id'     => $user_row->id,
            'categoria'   => 'COMPROBANTES',
            'descripcion' => 'Modificar Comprobante: ' . $comprobante->numero . '. Modificar comprobantes de ingresos.',
            'origen'      => '',
            'destino'     => '',
        );
        $this->codegen_model->add('users_log', $data);
        /* END: Auditoria */

        //Servicios Agregados
        if ($this->input->post('cantidad_servicios') > 0) {
            for ($i = 1; $i <= $this->input->post('cantidad_servicios'); $i++) {
                if ($this->input->post('servicio_' . $i)) {
                    $data = array(
                        'comprobante_ingreso_id' => $comprobante->id,
                        'servicio_id'            => $this->input->post('servicio_' . $i),
                        'cantidad'               => $this->input->post('cantidad_' . $i),
                        'disponible'             => $this->input->post('cantidad_' . $i),
                        'remito'                 => $this->input->post('remito_' . $i) != '' ? $this->input->post('remito_' . $i) : 0,
                        'create'                 => date("Y-m-d H:i:s"),
                        'update'                 => date("Y-m-d H:i:s"),
                    );
                    $this->codegen_model->add('flash_comprobantes_ingresos_servicios', $data);
                    $cantidad += $this->input->post('cantidad_' . $i);

                    /* Auditoria */
                    $user_row = $this->ion_auth->user()->row();
                    $data     = array(
                        'user_id'     => $user_row->id,
                        'categoria'   => 'COMPROBANTES',
                        'descripcion' => 'Comprobante: ' . $comprobante->numero . 'Servicio agregado: ' . $this->input->post('servicio_' . $i) . '. Servicios agregados.',
                        'origen'      => '',
                        'destino'     => '',
                    );
                    $this->codegen_model->add('users_log', $data);
                    /* END: Auditoria */

                }
            }
        }
        //Servicios a modificar la cantidad
        foreach ($_POST as $key => $value) {
//echo $key.'   '.$value."<br/>";
            if (substr($key, 0, 18) == 'cantidad_modificar') {
                $cantidad_array       = split('_', $key);
                $comprobante_servicio = ComprobanteServicio::whereId($cantidad_array[2])->first();
                $comprobante          = Comprobante::whereId($comprobante_servicio->comprobante_ingreso_id)->first();
                $cantidad             = $value;
//                echo $comprobante_servicio->cantidad .'  '.$comprobante_servicio->disponible;die;
                if ((int) $value < ((int) $comprobante_servicio->cantidad - (int) $comprobante_servicio->disponible)) {
                    $this->session->set_flashdata('registroNOOK', $this->session->flashdata('registroOK') . "<br/>Al comprobante <strong>" . $comprobante->numero . "</strong> se le asignó una cantidad errónea");
                } else {
                    $cantidad_disponible = (int) $value - ((int) $comprobante_servicio->cantidad - (int) $comprobante_servicio->disponible);
                    $data                = array('cantidad' => $value, 'disponible' => $cantidad_disponible);
                    $this->codegen_model->edit('flash_comprobantes_ingresos_servicios', $data, 'id', $cantidad_array[2]);

                    /* Auditoria */
                    $user_row = $this->ion_auth->user()->row();
                    $data     = array(
                        'user_id'     => $user_row->id,
                        'categoria'   => 'COMPROBANTES',
                        'descripcion' => 'Comprobante: ' . $comprobante->numero . 'Se modificó la cantidad a: ' . $value . ', disponible a ' . $cantidad_disponible . '. Servicios a modificar cantidad.',
                        'origen'      => '',
                        'destino'     => '',
                    );
                    $this->codegen_model->add('users_log', $data);
                    /* END: Auditoria */

                    $this->session->set_flashdata('registroOK', "El comprobante <strong>" . $comprobante->numero . "</strong> se modificó correctamente");
                }
            }
        }
        $cantidad_piezas = $this->codegen_model->row("flash_comprobantes_ingresos_servicios", "SUM(cantidad) as cantidad", " comprobante_ingreso_id = " . $comprobante->id);
        //Solo modifico la cantidad de los servicios que se agregaron
        $data_ci = array('cantidad' => intval($cantidad_piezas->cantidad));
        $this->codegen_model->edit('flash_comprobantes_ingresos', $data_ci, 'id', $comprobante->id);

        redirect(base_url() . 'piezas/comprobantes_ingresos/modificar_comprobantes_index/' . $comprobante->id);
    }

    public function borrar_servicio($comprobante_ingreso_id, $servicio_id)
    {
        $piezas = $this->flash_md->getPiezasPorServiciosPorComprobanteSinGroup($comprobante_ingreso_id, $servicio_id);
        foreach ($piezas as $pieza) {
            $this->codegen_model->delete('flash_piezas', 'id', $pieza->id);
        }
        $this->codegen_model->delete('flash_comprobantes_ingresos_servicios', 'id', $comprobante_ingreso_id . " AND servicio_id = " . $servicio_id);
        /* Auditoria */
        $comprobante          = Comprobante::whereId($comprobante_ingreso_id)->first();
        $user_row = $this->ion_auth->user()->row();
        $data     = array(
            'user_id'     => $user_row->id,
            'categoria'   => 'COMPROBANTES',
            'descripcion' => 'Comprobante: ' . $comprobante->numero . 'Servicio borrado: ' . $servicio_id . '. Borrar Servicio.',
            'origen'      => '',
            'destino'     => '',
        );
        $this->codegen_model->add('users_log', $data);
        /* END: Auditoria */

        redirect(base_url() . '/piezas/comprobantes_ingresos/modificar_comprobantes_index/' . $comprobante_ingreso_id);
    }

    public function liberar_comprobante()
    {
        set_time_limit(180);
        $comprobante_ingreso_id = $this->input->post('liberar_comprobante_id');
        $comprobante_ingreso    = Comprobante::whereId($comprobante_ingreso_id)->first();
        //Borro los servicios del comprobante
        $this->codegen_model->deleteWhere('flash_piezas', 'comprobante_ingreso_id', $comprobante_ingreso_id);
        $this->codegen_model->deleteWhere('flash_comprobantes_ingresos_servicios', 'comprobante_ingreso_id', $comprobante_ingreso_id);
        $this->codegen_model->deleteWhere('flash_comprobantes_ingresos', 'id', $comprobante_ingreso_id);
        $data = array('estado' => ComprobanteGenerado::ESTADO_IMPRESO);
        $this->codegen_model->edit('flash_comprobantes_ingresos_generados', $data, 'numero', $comprobante_ingreso->numero);
        /* Auditoria */
        $user_row = $this->ion_auth->user()->row();
        $data     = array(
            'user_id'     => $user_row->id,
            'categoria'   => 'COMPROBANTES',
            'descripcion' => 'Comprobante: ' . $comprobante_ingreso->numero . '. Liberar Comprobante.',
            'origen'      => '',
            'destino'     => '',
        );
        $this->codegen_model->add('users_log', $data);
        /* END: Auditoria */

        redirect(base_url() . 'piezas/comprobantes_ingresos/modificar_comprobantes_comprobar');
    }

    public function verificar($comprobante_ingreso_id)
    {
        $vista_interna = array(
            'permisos_efectivos'     => $this->permisos,
            'result'                 => $this->flash_md->getPiezas($comprobante_ingreso_id),
            'comprobante_ingreso_id' => $comprobante_ingreso_id,
        );

        $vista_externa = array(
            'title'          => ucwords("clientes"),
            'contenido_main' => $this->load->view('components/piezas/comprobantes_ingresos/comprobantes_verificar', $vista_interna, true),
        );

        $this->load->view('template/view', $vista_externa);
    }

    public function consultas_comprobantes_ingresos()
    {
       /* if ($this->ion_auth->isAdmin()) {
            $sucursales = $this->flash_md->getSucursales();
        } else {
            $user_row   = $this->ion_auth->user()->row();
            $sucursales = Sucursal::whereId($user_row->sucursal_id)->get();
        }*/

        //A pedido de Alvaro para que todas las personas puedan ver todas las sucursales
        $sucursales = $this->codegen_model->get('flash_sucursales','*','');
        
        $vista_interna = array(
            'permisos_efectivos' => $this->permisos,
            'sucursales'         => $sucursales,
            'clientes' => $this->codegen_model->get('flash_clientes','*',''),
            'results'            => []
        );
        //var_dump(Cliente::where('cliente_estado_id', Cliente::ESTADO_ACTIVO)->get());die;
        $vista_externa = array(
            'title'          => ucwords("Listado de Ingresos"),
            'contenido_main' => $this->load->view('components/piezas/comprobantes_ingresos/consultas/consultas_comprobantes_ingresos', $vista_interna, true),
        );

        $this->load->view('template/backend', $vista_externa);
    }
        public function consultas_comprobantes_ingresos_filtro()
    {
        $periodo_desde = $this->input->post('periodo_desde') != "" ? $this->input->post('periodo_desde') : false;
        $periodo_hasta = $this->input->post('periodo_hasta') != "" ? $this->input->post('periodo_hasta') : false;
        $sucursal      = $this->input->post('sucursal_id');
        $cliente_id      = $this->input->post('cliente_id');

        if ($this->ion_auth->isAdmin()) {
            $sucursales = $this->flash_md->getSucursales();
        } else {
            $user_row   = $this->ion_auth->user()->row();
            $sucursales = Sucursal::whereId($user_row->sucursal_id)->get();
        }

        $vista_interna = array(
            'permisos_efectivos' => $this->permisos,
            'results'            => $this->flash_md->comprobantes_ingresos_fecha_sucursal($periodo_desde, $periodo_hasta, $sucursal, $cliente_id),
            'periodo_desde'      => $periodo_desde,
            'periodo_hasta'      => $periodo_hasta,
            'sucursales'         => $sucursales,
            'sucursal_id'        => $sucursal,
            'clientes' => $this->codegen_model->get('flash_clientes','*',''),
            'cliente_id' => $cliente_id,
            'cliente_nombre' => $this->codegen_model->get('flash_clientes','nombre','id='.$cliente_id),
        );
        //var_dump($this->codegen_model->get('flash_clientes','nombre','id='.$cliente_id));die;
        $vista_externa = array(
            'title'          => ucwords("Listado de Ingresos"),
            'contenido_main' => $this->load->view('components/piezas/comprobantes_ingresos/consultas/consultas_comprobantes_ingresos', $vista_interna, true),
        );

        $this->load->view('template/backend', $vista_externa);
    }

    public function exportar_consulta_comprobantes_ingresos()
    {
        ob_start();
        $periodo_desde   = $this->input->post('excel_desde') != "" ? $this->input->post('excel_desde') : false;
        $periodo_hasta   = $this->input->post('excel_hasta') != "" ? $this->input->post('excel_hasta') : false;
        $sucursal        = $this->input->post('excel_sucursal_id');
        $sucursal_nombre = Sucursal::whereId($sucursal)->first();
        $cliente_id        = $this->input->post('excel_cliente_id');
        $cliente_nombre = $this->input->post('cliente_nombre') != "" ? $this->input->post('cliente_nombre') : false;

        $hojas_rutas = $this->flash_md->comprobantes_ingresos_fecha_sucursal($periodo_desde, $periodo_hasta, $sucursal, $cliente_id);

        //load our new PHPExcel library
        $this->load->library('excel');
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('Listado de Ingresos');
        //set cabeceras
        $this->excel->getActiveSheet()->setCellValue("A1", 'C.I.');
        $this->excel->getActiveSheet()->setCellValue("B1", 'Cliente');
        $this->excel->getActiveSheet()->setCellValue("C1", 'Tipo');
        $this->excel->getActiveSheet()->setCellValue("D1", 'Departamento');
        $this->excel->getActiveSheet()->setCellValue("E1", 'Servicio');
        $this->excel->getActiveSheet()->setCellValue("F1", 'Grupo');
        $this->excel->getActiveSheet()->setCellValue("G1", 'Piezas');
        $this->excel->getActiveSheet()->setCellValue("H1", 'Precio');
        $this->excel->getActiveSheet()->setCellValue("I1", 'Total');
        $this->excel->getActiveSheet()->setCellValue("J1", 'Fecha');
        $this->excel->getActiveSheet()->setCellValue("K1", 'Sucursal');
        // set cells values
        $this->load->library('simplehtmldom_1_5/simple_html_dom');
        $i = 2;
        foreach ($hojas_rutas as $value) {
            // echo $trs->find('td',1)->innertext;
            $this->excel->getActiveSheet()->setCellValueExplicit("A$i", $value->numero);
            $this->excel->getActiveSheet()->setCellValue("B$i", $value->cliente);
            $this->excel->getActiveSheet()->setCellValue("C$i", $value->tipo);
            $this->excel->getActiveSheet()->setCellValue("D$i", $value->departamento);
            $this->excel->getActiveSheet()->setCellValue("E$i", $value->servicio);
            $this->excel->getActiveSheet()->setCellValue("F$i", $value->grupo);
            $this->excel->getActiveSheet()->setCellValue("G$i", $value->piezas);
            $this->excel->getActiveSheet()->getStyle("H$i")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
            $this->excel->getActiveSheet()->setCellValue("H$i", $value->precio);
            $this->excel->getActiveSheet()->getStyle("I$i")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
            $this->excel->getActiveSheet()->setCellValue("I$i", $value->subtotal);
            $this->excel->getActiveSheet()->setCellValue("J$i", $value->fecha_creacion);
            $this->excel->getActiveSheet()->setCellValue("K$i", $value->sucursal);
            $i++;
        }

        $this->excel->setActiveSheetIndex(0);
        $filename = 'Listado_Ingresos.xls'; //save our workbook as this file name

        header('Content-Type: application/vnd.ms-excel'); //mime type

        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name

        header('Cache-Control: max-age=0'); //no cache

        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format

        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

        //force user to download the Excel file without writing it to server's HD
        ob_end_clean();
        $objWriter->save('php://output');
    }

    public function consulta_precio_cliente_servicios()
    {
        if ($this->ion_auth->isAdmin()) {
            $vista_interna = array(
                'permisos_efectivos' => $this->permisos,
                'results'            => []
            );
        }

        $vista_externa = array(
            'title'          => ucwords("Listado de Liquidaciones"),
            'contenido_main' => $this->load->view('components/piezas/comprobantes_ingresos/consultas/consultas_precios_clientes_servicios', $vista_interna, true),
        );

        $this->load->view('template/backend', $vista_externa);
    }

    public function consulta_precio_cliente_servicios_filtro()
    {
        $periodo_desde = $this->input->post('periodo_desde') != "" ? $this->input->post('periodo_desde') : false;
        $periodo_hasta = $this->input->post('periodo_hasta') != "" ? $this->input->post('periodo_hasta') : false;

        $vista_interna = array(
            'permisos_efectivos' => $this->permisos,
            'results'            => $this->flash_md->precio_por_cliente_servicio($periodo_desde, $periodo_hasta),
            'periodo_desde'      => $periodo_desde,
            'periodo_hasta'      => $periodo_hasta,
        );

        $vista_externa = array(
            'title'          => ucwords("Listado de Ingresos"),
            'contenido_main' => $this->load->view('components/piezas/comprobantes_ingresos/consultas/consultas_precios_clientes_servicios', $vista_interna, true),
        );

        $this->load->view('template/backend', $vista_externa);
    }

    public function exportar_consulta_precio_cliente_servicios()
    {
        $periodo_desde = $this->input->post('excel_desde') != "" ? $this->input->post('excel_desde') : false;
        $periodo_hasta = $this->input->post('excel_hasta') != "" ? $this->input->post('excel_hasta') : false;

        $hojas_rutas = $this->flash_md->precio_por_cliente_servicio($periodo_desde, $periodo_hasta);

        //load our new PHPExcel library
        $this->load->library('excel');
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('Listado liquidaciones');
        //set cabeceras
        $this->excel->getActiveSheet()->setCellValue("A1", 'Liq. ID');
        $this->excel->getActiveSheet()->setCellValue("B1", 'Provincia');
        $this->excel->getActiveSheet()->setCellValue("C1", 'Cliente');
        $this->excel->getActiveSheet()->setCellValue("D1", 'Departamento');
        $this->excel->getActiveSheet()->setCellValue("E1", 'Servicio');
        $this->excel->getActiveSheet()->setCellValue("F1", 'Grupo');
        $this->excel->getActiveSheet()->setCellValue("G1", 'Fecha');
        $this->excel->getActiveSheet()->setCellValue("H1", 'Fact.Nro.');
        $this->excel->getActiveSheet()->setCellValue("I1", 'Comp. Ing.');
        $this->excel->getActiveSheet()->setCellValue("J1", 'Piezas');
        $this->excel->getActiveSheet()->setCellValue("K1", 'Precio U.');
        // set cells values
        $this->load->library('simplehtmldom_1_5/simple_html_dom');
        $i = 2;
        foreach ($hojas_rutas as $value) {
            // echo $trs->find('td',1)->innertext;
            $this->excel->getActiveSheet()->setCellValue("A$i", $value->liquidacion_id);
            $this->excel->getActiveSheet()->setCellValue("B$i", $value->sucursal);
            $this->excel->getActiveSheet()->setCellValue("C$i", $value->cliente);
            $this->excel->getActiveSheet()->setCellValue("D$i", $value->departamento);
            $this->excel->getActiveSheet()->setCellValue("E$i", $value->servicio);
            $this->excel->getActiveSheet()->setCellValue("F$i", $value->grupo);
            $this->excel->getActiveSheet()->setCellValue("G$i", $value->fecha_creacion);
            $this->excel->getActiveSheet()->setCellValue("H$i", $value->factura);
            $this->excel->getActiveSheet()->setCellValue("I$i", $value->numero);
            $this->excel->getActiveSheet()->setCellValue("J$i", $value->piezas);
            $this->excel->getActiveSheet()->setCellValue("K$i", $value->precio);
            $i++;
        }

        $this->excel->setActiveSheetIndex(0);
        $filename = 'Listado_Liquidaciones.xls'; //save our workbook as this file name

        header('Content-Type: application/vnd.ms-excel'); //mime type

        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name

        header('Cache-Control: max-age=0'); //no cache

        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format

        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');
    }

    public function eliminar_comprobantes_comprobar()
    {
        if ($this->input->is_post()) {
            $numero = trim($this->input->post('numero'));
            $query  = Comprobante::whereNumero($numero)->whereEstado(ComprobanteGenerado::ESTADO_USADO)->whereLiquidacionId(0);
            if ($comprobante = $query->first()) {
                redirect(base_url('piezas/comprobantes_ingresos/eliminar_comprobantes_index/' . $comprobante->id));
            } else {
                $this->session->set_flashdata('registro', $numero);
            }
        }

        $this->title('Eliminar comprobantes de ingresos');
        $this->view_index('components/piezas/comprobantes_ingresos/comprobantes_ingresos_index_delete');
        $this->template('template/backend');
    }

    public function eliminar_comprobantes_index($comprobante_id)
    {
        $comprobante         = Comprobante::findOrFail($comprobante_id);
        $comprobante_detalle = $this->flash_md->getComprobanteDetalle($comprobante->id);

        $query_servicios = $this->db
            ->select('pe.cliente_id , pe.id, s.codigo, pe.nombre, pe.precio, pe.dias, pe.servicio_id')
            ->from('flash_servicios s')
            ->join('flash_clientes_precios_especiales pe', 's.id = pe.servicio_id')
            ->where('pe.cliente_id', $comprobante->cliente_id)
            ->get();

        $query_hojas_rutas = "SELECT '' pieza_id, sp.hoja_ruta_id, suc.nombre sucursal, count(*) piezas
                                FROM flash_piezas p
                                INNER JOIN  flash_subpiezas sp ON sp.pieza_id = p.id
                                INNER JOIN flash_sucursales suc ON p.sucursal_id = suc.id
                                WHERE p.comprobante_ingreso_id = " . $comprobante_id . "
                                AND p.tipo_id = 1
                                GROUP BY p.comprobante_ingreso_id , p.sucursal_id
                                UNION
                                SELECT p.id , sp.hoja_ruta_id, suc.nombre sucursal,1 piezas
                                FROM flash_piezas p
                                INNER JOIN  flash_subpiezas sp ON sp.pieza_id = p.id
                                INNER JOIN flash_sucursales suc ON p.sucursal_id = suc.id
                                WHERE p.comprobante_ingreso_id =  " . $comprobante_id . "
                                AND p.tipo_id = 2 ";

        $query_despachos = " SELECT '' pieza_id , pdp.despacho_id, suc.nombre sucursal, count(*) piezas
                                FROM flash_piezas p
                                INNER JOIN  flash_piezas_despacho_piezas pdp ON pdp.pieza_id = p.id
                                INNER JOIN flash_sucursales suc ON p.sucursal_id = suc.id
                                WHERE p.comprobante_ingreso_id =  " . $comprobante_id . "
                                AND p.tipo_id = 1
                                GROUP BY p.comprobante_ingreso_id , p.sucursal_id
                                UNION
                                SELECT p.id pieza_id ,pdp.despacho_id, suc.nombre sucursal,1 piezas
                                FROM flash_piezas p
                                INNER JOIN  flash_piezas_despacho_piezas pdp ON pdp.pieza_id = p.id
                                INNER JOIN flash_sucursales suc ON p.sucursal_id = suc.id
                                WHERE p.comprobante_ingreso_id =  " . $comprobante_id . "
                                AND p.tipo_id = 2 ";

        //echo $query_despachos;die;
        date_default_timezone_set('America/Argentina/Tucuman');
        //Dentro de las 24 Hrs de creado
        $date1 = new DateTime('now');
        $date2 = new DateTime($comprobante->create);

        $diff               = $date1->diff($date2);
        $habilitar_eliminar = false;
        $user_row           = $this->ion_auth->user()->row();
        $hojas_rutas        = $this->db->query($query_hojas_rutas);
        $despachos          = $this->db->query($query_despachos);

        if ($diff->days == 0) {
            $habilitar_eliminar = true;
        } else {
            if ($diff->days > 0 && $diff->days <= 3) {
                if ($this->ion_auth->in_group(array(15), $user_row->id)) {
                    $habilitar_eliminar = true;
                }

            } else {
                if ($diff->days > 3) {
                    if ($this->ion_auth->in_group(array(1, 2), $user_row->id)) {
                        $habilitar_eliminar = true;
                    }

                }
            }
        }

        if (count($hojas_rutas->result()) > 0
            && $this->ion_auth->in_group(array(15), $user_row->id)) {
            $habilitar_eliminar = true;
        }

        $this->data([
            'comprobante'         => $comprobante,
            'empresas'            => Empresa::all(),
            'sucursales'          => $this->is_user() ? Sucursal::whereId($this->usuario->sucursal_id)->get() : Sucursal::all(),
            'clientes'            => Cliente::select('id', DB::raw('CONCAT(nombre," (",nombre_fantasia,")") as name'))->where('cliente_estado_id', Cliente::ESTADO_ACTIVO)->get(),
            'cliente'             => Cliente::findOrFail($comprobante->cliente_id),
            'servicios'           => $query_servicios->result(), //ClientePrecioEspecial::whereClienteId($comprobante->cliente_id)->get(),
            'departamentos'       => ClienteDepartamento::whereClienteId($comprobante->cliente_id)->get(),
            'comprobante_detalle' => $comprobante_detalle,
            'hojas_rutas'         => $hojas_rutas->result(),
            'despachos'           => $despachos->result(),
            'habilitar_eliminar'  => $habilitar_eliminar,
        ]);

        $this->title('Eliminar comprobante de ingreso');
        $this->view = 'components/piezas/comprobantes_ingresos/comprobantes_ingresos_delete';
        $this->template('template/backend');
    }

    public function eliminar_comprobante_ingreso()
    {
        set_time_limit(180);
        $comprobante_ingreso_id = $this->input->post('comprobante_ingreso_id');
        $comprobante_ingreso    = Comprobante::whereId($comprobante_ingreso_id)->first();
        //Si el comprobante tenia servicio 2(Paqueteria) entonces borro el registro de flash_piezas_paquetes
        $comprobante_ingreso_servicios = $this->codegen_model->get('flash_comprobantes_ingresos_servicios', '*', 'comprobante_ingreso_id = '.$comprobante_ingreso_id);
        
        foreach ($comprobante_ingreso_servicios as $value){
            if ($value->servicio_id == 2){//Sercivio de paqueteria
                $piezas_paquetes = $this->codegen_model->get('flash_piezas', '*', 'comprobante_ingreso_id = '.$comprobante_ingreso_id);
                if ($piezas_paquetes != NULL){
                    foreach ($piezas_paquetes as $value) {
                        $this->codegen_model->deleteWhere('flash_piezas_paquetes', 'pieza_id', $value->id);
                    }
                }
            }
        }

        //Borro las piezas - servicios y finalmente el comprobante
        $this->codegen_model->deleteWhere('flash_piezas', 'comprobante_ingreso_id', $comprobante_ingreso_id);
        $this->codegen_model->deleteWhere('flash_comprobantes_ingresos_servicios', 'comprobante_ingreso_id', $comprobante_ingreso_id);
        $this->codegen_model->deleteWhere('flash_comprobantes_ingresos', 'id', $comprobante_ingreso_id);

        $data = array('estado' => ComprobanteGenerado::ESTADO_IMPRESO);
        $this->codegen_model->edit('flash_comprobantes_ingresos_generados', $data, 'numero', $comprobante_ingreso->numero);
        /* Auditoria */
        $user_row = $this->ion_auth->user()->row();
        $data     = array(
            'user_id'     => $user_row->id,
            'categoria'   => 'COMPROBANTES',
            'descripcion' => 'Comprobante: ' . $comprobante_ingreso->numero . '. Eliminar Ingreso de Comprobante.',
            'origen'      => '',
            'destino'     => '',
        );
        $this->codegen_model->add('users_log', $data);
        /* END: Auditoria */

        redirect(base_url() . 'piezas/comprobantes_ingresos/eliminar_comprobantes_comprobar');
    }

    public function cantidad_piezas_simples_edit()
    {
        if ($this->input->is_post()) {
            $this->post_index();
        }

        $this->title("Editar Novedades");
        $this->view = 'components/piezas/comprobantes_ingresos/comprobantes_cantidad_piezas_simples_edit';
        $this->template('template/backend');
    }

    public function editNovedadesPiezasSimples($comprobante_ingreso_id, $servicio_id, $estado_actual_id)
    {
        if ($this->input->post('enviar_form')) {
            $estado_id = ($this->input->post('estado_id')) ? $this->input->post('estado_id') : null;
            if ($estado_id != null) {
                $update_piezas = " UPDATE flash_piezas
                                    SET estado_id = " . $estado_id . "
                                    WHERE comprobante_ingreso_id = " . $comprobante_ingreso_id . "
                                        AND servicio_id = " . $servicio_id . "
                                            AND estado_id = " . $estado_actual_id;

                $user_row = $this->ion_auth->user()->row();
                //OJO IMPORTANTE QUE PIEZA_ID EN NOVEDADES ESTE COMO AGREGADO COMO UNIQUE
                $update_novedades = "INSERT INTO flash_piezas_novedades (pieza_id, cantidad, usuario_id,  estado_actual_id, estado_nuevo_id)
                                        SELECT p.id, 1, " . $user_row->id . ", p.estado_id," . $estado_id . "
                                        FROM  flash_piezas p
                                            JOIN flash_comprobantes_ingresos_servicios cis ON cis.id = p.servicio_id
                                            JOIN flash_piezas_estados_variables ev ON p.estado_id = ev.id
                                             WHERE `p`.`comprobante_ingreso_id` = " . $comprobante_ingreso_id . "
                                             AND `cis`.`id` = " . $servicio_id . "
                                              AND `p`.`tipo_id` = " . Pieza::TIPO_SIMPLE . "
                                                AND `p`.`estado_id` = " . $estado_actual_id . "
                                                 ON DUPLICATE KEY UPDATE estado_actual_id= p.estado_id , estado_nuevo_id=" . $estado_id . ",flash_piezas_novedades.update=" . "'" . date('Y-m-d H:m:s') . "'";

                $exe_novedades = $this->db->query($update_novedades);
                $exe_piezas    = $this->db->query($update_piezas);

                /* Auditoria */
                $comprobante    = Comprobante::whereId($comprobante_ingreso_id)->first();
                $user_row = $this->ion_auth->user()->row();
                $data     = array(
                    'user_id'     => $user_row->id,
                    'categoria'   => 'COMPROBANTES',
                    'descripcion' => 'Comprobante: ' . $comprobante->numero . '. Editar Novedades Piezas Simples.',
                    'origen'      => '',
                    'destino'     => '',
                );
                $this->codegen_model->add('users_log', $data);
                /* END: Auditoria */

                $this->session->set_flashdata('registroOk', $comprobante_ingreso_id);
                redirect(base_url() . 'piezas/novedades/index_edit_simples/' /*.$pieza_id*/);

            }
        }
        $vista_interna = array(
            'estados_iniciales'     => PiezaEstadoVariable::wherePiezaEstadoId(PiezaEstado::ESTADOS_INICIALES)->get(),
            'estados'               => PiezaEstadoVariable::wherePiezaEstadoId(PiezaEstado::ESTADOS_RENDICIONES)->get(),
            'estados_organizativos' => PiezaEstadoVariable::wherePiezaEstadoId(PiezaEstado::ESTADOS_ORGANIZATIVOS)->get(),
            'estados_despachos'     => PiezaEstadoVariable::wherePiezaEstadoId(PiezaEstado::ESTADOS_DESPACHO)->get(),

        );
        $vista_externa = array(
            'title'          => ucwords("Estados de la pieza"),
            'contenido_main' => $this->load->view('components/piezas/piezas_novedades/piezas_novedades_edit_simples_popup', $vista_interna, true),
        );
        $this->load->view('template/view', $vista_externa);
    }

    public function modificar_comprobantes_servicios($comprobante_servicio_id)
    {
        if ($this->input->post('enviar_form')) {          //  var_dump($_POST);die;
            $mensaje                         = "";
            $servicio_id                     = ($this->input->post('servicio_id_p')) ? $this->input->post('servicio_id_p') : null;
            $cantidad_total_servicio         = ($this->input->post('cantidad_total_servicio_p')) ? $this->input->post('cantidad_total_servicio_p') : null;
            $comprobante_ingreso_servicio_id = ($this->input->post('comprobante_ingreso_servicio_id')) ? $this->input->post('comprobante_ingreso_servicio_id') : null;
            $comprobante_ingreso_servicio    = ComprobanteServicio::whereId($comprobante_servicio_id)->first();
            $comprobante_ingreso             = Comprobante::whereId($comprobante_ingreso_servicio->comprobante_ingreso_id)->first();
            $user_row                        = $this->ion_auth->user()->row();

            $query_suma_cantidad_servicios = "SELECT SUM(cantidad) cantidad_total_ci
                                                FROM flash_comprobantes_ingresos_servicios
                                                WHERE comprobante_ingreso_id = $comprobante_ingreso_servicio->comprobante_ingreso_id
                                                AND id <> $comprobante_ingreso_servicio_id";

            $suma_cantidad_servicios_result = $this->db->query($query_suma_cantidad_servicios);
            $suma_cantidad_servicios        = $suma_cantidad_servicios_result->row();

            if (($suma_cantidad_servicios->cantidad_total_ci + $cantidad_total_servicio) > $comprobante_ingreso->cantidad) {
                $mensaje = "La cantidad seleccionada para el servicio supera la cantidad total de piezas del C.I.";
                $this->session->set_flashdata('mensaje_error', $mensaje);
                redirect(base_url() . 'piezas/comprobantes_ingresos/modificar_comprobantes_index/' . $comprobante_ingreso_servicio->comprobante_ingreso_id);
            }
            // Disminución: si el servicio no tiene HDRs ni DESPACHOS (no necesita permisos)
            $query_despachos = "  SELECT COUNT(*) cantidad
                                        FROM flash_piezas p
                                        INNER JOIN flash_piezas_despacho_piezas pdp ON pdp.pieza_id = p.id
                                        WHERE p.servicio_id = $comprobante_ingreso_servicio_id
                                        GROUP BY p.servicio_id";

            $query_hdr = "  SELECT COUNT(*) cantidad
                                        FROM flash_piezas p
                                        INNER JOIN flash_subpiezas sp ON sp.pieza_id = p.id
                                        WHERE p.servicio_id = $comprobante_ingreso_servicio_id
                                        GROUP BY p.servicio_id";

            $query_no_disponibles_para_borrar = "SELECT p.id
                                                        FROM flash_piezas p
                                                        INNER JOIN flash_subpiezas sp ON p.id = sp.pieza_id
                                                        WHERE p.servicio_id = $comprobante_ingreso_servicio_id
                                                UNION
                                                SELECT p.id
                                                        FROM flash_piezas p
                                                        INNER JOIN flash_piezas_despacho_piezas pdp ON p.id = pdp.pieza_id
                                                        AND p.servicio_id = $comprobante_ingreso_servicio_id
                                                UNION
                                                SELECT p.id
                                                        FROM flash_piezas p
                                                        INNER JOIN flash_rendiciones_piezas rp ON p.id = rp.pieza_id
                                                        AND p.servicio_id = $comprobante_ingreso_servicio_id";

            $query_despachos_result                  = $this->db->query($query_despachos);
            $query_hdr_result                        = $this->db->query($query_hdr);
            $query_no_disponibles_para_borrar_result = $this->db->query($query_no_disponibles_para_borrar);

            $despachos                  = $query_despachos_result->row();
            $hdrs                       = $query_hdr_result->row();
            $no_disponibles_para_borrar = $query_no_disponibles_para_borrar_result->result();

            $modificar_cantidad = false;
            //Disminucion sin hdr ni despacho
            if ($cantidad_total_servicio <= $comprobante_ingreso_servicio->cantidad
                && $despachos->cantidad == 0 && $hdrs->cantidad == 0) {
                $modificar_cantidad = true;
            }
            //Disminucion con HDR o DESPACHO
            if ($cantidad_total_servicio < $comprobante_ingreso_servicio->cantidad
                && ($despachos->cantidad > 0 || $hdrs->cantidad > 0)) {
                $cantidad_disponible_disminuir = $comprobante_ingreso_servicio->cantidad - count($no_disponibles_para_borrar);
                if ($cantidad_total_servicio <= $cantidad_disponible_disminuir) {
                    $modificar_cantidad = true;
                } else {
                    $mensaje = "No se puede disminuir $cantidad_total_servicio, solo $cantidad_disponible_disminuir";
                }
            }
//            echo "Lo que se puede borrar es $cantidad_disponible_disminuir <br/>";
            //            echo "lo que se quiere borrar es $cantidad_total_servicio <br/>";
            //            echo "lo que no se piede borrar es ".count($no_disponibles_para_borrar)." <br/>";
            //            echo "Modificar es $modificar_cantidad <br/>";
            //            echo $mensaje;die;
            //Incremento con o sin HDR o DESPACHO hasta un 5%
            if ($cantidad_total_servicio > $comprobante_ingreso_servicio->cantidad) {
                $porcentaje = 5 * $comprobante_ingreso_servicio->cantidad / 100;
                if ($cantidad_total_servicio <= $porcentaje) {
                    $modificar_cantidad = true;
                } else {
//Incremento con o sin HDR o DESPACHO
                    if ($this->ion_auth->in_group(array(15), $user_row->id)) {
                        $modificar_cantidad = true;
                    } else {
                        $mensaje = "No tiene permisos para realizar este cambio (+%5)";
                    }
                }
            }
            
            //Si la pieza esta en rendicion, estado de rendicion, HDR o Despacho no se la puede borrar
            if ($modificar_cantidad) {
                $update_comprobante_ingreso_servicio = " UPDATE flash_comprobantes_ingresos_servicios
                                                            SET servicio_id = " . $servicio_id . " ,
                                                                cantidad = " . $cantidad_total_servicio . "
                                                            WHERE id = " . $comprobante_ingreso_servicio_id;
                
                $update_comprobante_ingreso_servicio_results = $this->db->query($update_comprobante_ingreso_servicio);
                //Borrar de la tabla piezas

                /* Auditoria */
                $user_row = $this->ion_auth->user()->row();
                $data     = array(
                    'user_id'     => $user_row->id,
                    'categoria'   => 'COMPROBANTES',
                    'descripcion' => 'Comprobante: ' . $comprobante_ingreso->numero . '. Modificar Comprobantes de Servicios.',
                    'origen'      => '',
                    'destino'     => '',
                );
                $this->codegen_model->add('users_log', $data);
                /* END: Auditoria */

                $mensaje = "Los cambios se guardaron correctamente";
                $this->session->set_flashdata('registroOk', $mensaje);
                redirect(base_url() . 'piezas/comprobantes_ingresos/modificar_comprobantes_index/' . $comprobante_ingreso_servicio->comprobante_ingreso_id);
            } else {
                
            //Modificar solo servicio solo si las piezas no tienen rendiciones y el ci no esta liquidado
            if ($cantidad_total_servicio == $comprobante_ingreso_servicio->cantidad) {
                $query_rendiciones = "  SELECT COUNT(*) cantidad
                                        FROM flash_piezas p
                                        INNER JOIN flash_rendiciones_piezas rp ON p.id = rp.pieza_id
                                        WHERE p.comprobante_ingreso_id = $comprobante_ingreso->id
                                        AND p.servicio_id = ".$comprobante_ingreso_servicio_id;
                $rendiciones_count = $this->db->query($query_rendiciones)->row();
//                echo $query_rendiciones->cantidad;die;
                if ($rendiciones_count->cantidad == 0 || $comprobante_ingreso->estado_liquidacion < 2)  {
                        $mensaje = "El CI ya esta liquidado o las piezas estan en rendiciones";
                        $this->session->set_flashdata('mensaje_error', $mensaje);
                        redirect(base_url() . 'piezas/comprobantes_ingresos/modificar_comprobantes_index/' . $comprobante_ingreso_servicio->comprobante_ingreso_id);
                }
                if ($rendiciones_count->cantidad == 0 && $comprobante_ingreso->estado_liquidacion < 2 ){
                    
                    $update_comprobante_ingreso_servicio = " UPDATE flash_comprobantes_ingresos_servicios
                                                                SET servicio_id = " . $servicio_id . " 
                                                                WHERE id = " . $comprobante_ingreso_servicio_id;
//                    echo $update_comprobante_ingreso_servicio;die;
                    $update_comprobante_ingreso_servicio_results = $this->db->query($update_comprobante_ingreso_servicio);
                    //Borrar de la tabla piezas

                    /* Auditoria */
                    $user_row = $this->ion_auth->user()->row();
                    $data     = array(
                        'user_id'     => $user_row->id,
                        'categoria'   => 'COMPROBANTES',
                        'descripcion' => 'Comprobante: ' . $comprobante_ingreso->numero . '. Modificar solo el Servicio.',
                        'origen'      => '',
                        'destino'     => '',
                    );
                    $this->codegen_model->add('users_log', $data);
                    /* END: Auditoria */

                    $mensaje = "Los cambios se guardaron correctamente";
                    $this->session->set_flashdata('registroOk', $mensaje);
                    redirect(base_url() . 'piezas/comprobantes_ingresos/modificar_comprobantes_index/' . $comprobante_ingreso_servicio->comprobante_ingreso_id);
                
                } else {
                    $this->session->set_flashdata('mensaje_error', $mensaje);
                    redirect(base_url() . 'piezas/comprobantes_ingresos/modificar_comprobantes_index/' . $comprobante_ingreso_servicio->comprobante_ingreso_id);
                }
            }
            
                $this->session->set_flashdata('mensaje_error', $mensaje);
                redirect(base_url() . 'piezas/comprobantes_ingresos/modificar_comprobantes_index/' . $comprobante_ingreso_servicio->comprobante_ingreso_id);
            }
        }
        
        $query_comprobante_servicio = " SELECT ci.id comprobante_ingreso_id,
                                                    ci.numero,
                                                    ci.cantidad cantidad_total_ci,
                                                    cis.id comprobante_ingreso_servicio_id,
                                                    cis.cantidad cantidad_total_servicio,
                                                    cis.disponible cantidad_disponible_servicio ,
                                                    cis.servicio_id servicio_id,
                                                    s.nombre servicio,
                                                    s.acuse,
                                                    cis.remito,
                                                    ci.liquidacion_id liquidacion_id
                                            FROM flash_comprobantes_ingresos ci
                                            INNER JOIN flash_comprobantes_ingresos_servicios cis ON ci.id = cis.comprobante_ingreso_id
                                            INNER JOIN flash_servicios s ON cis.servicio_id = s.id

                                            WHERE cis.id = $comprobante_servicio_id";

        $comprobante_servicio_result = $this->db->query($query_comprobante_servicio);
        $comprobante_servicio        = $comprobante_servicio_result->row();
        $query_servicios             = "SELECT cpe.id precio_especial_id, s.nombre servicio, s.id servicio_id
                            FROM flash_comprobantes_ingresos ci
                            INNER JOIN flash_clientes_precios_especiales cpe ON ci.cliente_id = cpe.cliente_id
                            INNER JOIN flash_servicios s ON s.id = cpe.servicio_id
                            WHERE ci.id = $comprobante_servicio->comprobante_ingreso_id";
        $query_servicios_result = $this->db->query($query_servicios);
        $vista_interna          = array(
            'comprobante_servicio' => $comprobante_servicio,
            'servicios'            => $query_servicios_result->result(),
        );

        $vista_externa = array(
            'title'          => ucwords("Estados de la pieza"),
            'contenido_main' => $this->load->view('components/piezas/comprobantes_ingresos/comprobante_ingreso_servicio_edit_popup', $vista_interna, true),
        );
        $this->load->view('template/view', $vista_externa);
    }

    public function generar_piezas_disponibles_servicios($comprobante_servicio_id)
    {
        if ($this->input->post('enviar_form')) {
            $mensaje = "";
//            var_dump($_POST);die;
            $user_row                        = $this->ion_auth->user()->row();
            $usuario_id                      = $user_row->id;
            $servicio_id                     = ($this->input->post('servicio_id')) ? $this->input->post('servicio_id') : null;
            $sucursal_id                     = ($this->input->post('sucursal_id')) ? $this->input->post('sucursal_id') : null;
            $comprobante_ingreso_id          = ($this->input->post('comprobante_ingreso_id')) ? $this->input->post('comprobante_ingreso_id') : null;
            $comprobante_ingreso_servicio_id = ($this->input->post('comprobante_ingreso_servicio_id')) ? $this->input->post('comprobante_ingreso_servicio_id') : null;
            $comprobante_ingreso_servicio    = ComprobanteServicio::whereId($servicio_id)->first();
//            var_dump($comprobante_ingreso_servicio->comprobante_ingreso_id);die;
            $comprobante_ingreso             = Comprobante::whereId($comprobante_ingreso_servicio->comprobante_ingreso_id)->first();
            $cantidad                        = ($this->input->post('piezas_a_generar')) ? $this->input->post('piezas_a_generar') : null;
            $fecha                           = "'" . date("Y-m-d H:i:s") . "'";
            //$fecha_datetime = strtotime($fecha);
            //echo $usuario_id." ".$comprobante_ingreso_servicio_id." ". $sucursal_id." ". $comprobante_ingreso_id." ". $cantidad." ". $fecha;die;
            $query_call_function = " CALL insertar_piezas_simples($usuario_id,$comprobante_ingreso_servicio_id, $sucursal_id, $comprobante_ingreso_id, $cantidad, $fecha)";
            $query_call_function_exe = $this->db->query($query_call_function);

            /* Auditoria */
            $user_row = $this->ion_auth->user()->row();
            $data     = array(
                'user_id'     => $user_row->id,
                'categoria'   => 'COMPROBANTES',
                'descripcion' => 'Comprobante: ' . $comprobante_ingreso->numero . '. Generar Piezas Disponibles por Servicios.',
                'origen'      => '',
                'destino'     => '',
            );
            $this->codegen_model->add('users_log', $data);
            /* END: Auditoria */

            redirect(base_url() . 'piezas/comprobantes_ingresos/modificar_comprobantes_index/' . $comprobante_ingreso_id);
        }

        $query_comprobante_servicio = " SELECT ci.id comprobante_ingreso_id,
                                                    ci.numero, $sucursal_id
                                                    ci.cantidad cantidad_total_ci,
                                                    cis.id comprobante_ingreso_servicio_id,
                                                    cis.cantidad cantidad_total_servicio,
                                                    cis.disponible cantidad_disponible_servicio ,
                                                    cis.servicio_id servicio_id,
                                                    s.nombre servicio,
                                                    s.acuse,
                                                    cis.remito,
                                                    ci.liquidacion_id liquidacion_id
                                            FROM flash_comprobantes_ingresos ci
                                            INNER JOIN flash_comprobantes_ingresos_servicios cis ON ci.id = cis.comprobante_ingreso_id
                                            INNER JOIN flash_servicios s ON cis.servicio_id = s.id

                                            WHERE cis.id = $comprobante_servicio_id";

        $comprobante_servicio_result = $this->db->query($query_comprobante_servicio);
        $comprobante_servicio        = $comprobante_servicio_result->row();
        $query_servicios             = "SELECT cpe.id precio_especial_id, s.nombre servicio, s.id servicio_id
                            FROM flash_comprobantes_ingresos ci
                            INNER JOIN flash_clientes_precios_especiales cpe ON ci.cliente_id = cpe.cliente_id
                            INNER JOIN flash_servicios s ON s.id = cpe.servicio_id
                            WHERE ci.id = $comprobante_servicio->comprobante_ingreso_id";
        $query_servicios_result = $this->db->query($query_servicios);

        $vista_interna = array(
            'comprobante_servicio' => $comprobante_servicio,
            'servicios'            => $query_servicios_result->result(),
            'sucursales'           => Sucursal::all(),
        );

        $vista_externa = array(
            'title'          => ucwords("Generar Piezas Flatantes"),
            'contenido_main' => $this->load->view('components/piezas/comprobantes_ingresos/generar_piezas_faltantes_popup', $vista_interna, true),
        );
        $this->load->view('template/view', $vista_externa);

    }
    
    public function consulta_liquidaciones_clientes()
    {
        if ($this->ion_auth->isAdmin()) {
            $vista_interna = array(
                'permisos_efectivos' => $this->permisos,
                'results'            => []
            );
        }

        $vista_externa = array(
            'title'          => ucwords("Listado de liquidaciones de Clientes"),
            'contenido_main' => $this->load->view('components/piezas/comprobantes_ingresos/consultas/consultas_liquidaciones_clientes', $vista_interna, true),
        );

        $this->load->view('template/backend', $vista_externa);
    }
    
    public function consulta_liquidaciones_clientes_filtro()
    {
        $periodo_desde = $this->input->post('periodo_desde') != "" ? $this->input->post('periodo_desde') : false;
        $periodo_hasta = $this->input->post('periodo_hasta') != "" ? $this->input->post('periodo_hasta') : false;

        $vista_interna = array(
            'permisos_efectivos' => $this->permisos,
            'results'            => $this->flash_md->getListadoLiquidacionesClientes($periodo_desde, $periodo_hasta),
            'periodo_desde'      => $periodo_desde,
            'periodo_hasta'      => $periodo_hasta,
        );

        $vista_externa = array(
            'title'          => ucwords("Listado de Ingresos"),
            'contenido_main' => $this->load->view('components/piezas/comprobantes_ingresos/consultas/consultas_liquidaciones_clientes', $vista_interna, true),
        );

        $this->load->view('template/backend', $vista_externa);
    }

    public function exportar_consulta_liquidaciones_clientes()
    {
        $periodo_desde = $this->input->post('excel_desde') != "" ? $this->input->post('excel_desde') : false;
        $periodo_hasta = $this->input->post('excel_hasta') != "" ? $this->input->post('excel_hasta') : false;

        $hojas_rutas = $this->flash_md->getListadoLiquidacionesClientes($periodo_desde, $periodo_hasta);

        //load our new PHPExcel library
        $this->load->library('excel');
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('Listado liquidaciones');
        //set cabeceras
        $this->excel->getActiveSheet()->setCellValue("A1", 'Liq. ID');
        $this->excel->getActiveSheet()->setCellValue("B1", 'Provincia');
        $this->excel->getActiveSheet()->setCellValue("C1", 'Cliente');
        $this->excel->getActiveSheet()->setCellValue("D1", 'Departamento');
        $this->excel->getActiveSheet()->setCellValue("E1", 'Servicio');
        $this->excel->getActiveSheet()->setCellValue("F1", 'Grupo');
        $this->excel->getActiveSheet()->setCellValue("G1", 'Fecha');
        $this->excel->getActiveSheet()->setCellValue("H1", 'Fact.Nro.');
        $this->excel->getActiveSheet()->setCellValue("I1", 'Comp. Ing.');
        $this->excel->getActiveSheet()->setCellValue("J1", 'Piezas');
        $this->excel->getActiveSheet()->setCellValue("K1", 'Precio U.');
        // set cells values
        $this->load->library('simplehtmldom_1_5/simple_html_dom');
        $i = 2;

        foreach ($hojas_rutas as $value) {
            // echo $trs->find('td',1)->innertext;
            $this->excel->getActiveSheet()->setCellValue("A$i", $value->liquidacion_id);
            $this->excel->getActiveSheet()->setCellValue("B$i", $value->sucursal);
            $this->excel->getActiveSheet()->setCellValue("C$i", $value->cliente);
            $this->excel->getActiveSheet()->setCellValue("D$i", $value->departamento);
            $this->excel->getActiveSheet()->setCellValue("E$i", $value->servicio);
            $this->excel->getActiveSheet()->setCellValue("F$i", $value->grupo);
            $this->excel->getActiveSheet()->setCellValue("G$i", $value->fecha_creacion);
            $this->excel->getActiveSheet()->setCellValue("H$i", $value->factura);
            $this->excel->getActiveSheet()->setCellValue("I$i", $value->numero);
            $this->excel->getActiveSheet()->setCellValue("J$i", $value->piezas);
            $this->excel->getActiveSheet()->getStyle("K$i")->getNumberFormat()->setFormatCode('#,###0.000'); 
            $this->excel->getActiveSheet()->setCellValue("K$i", number_format($value->precio,3));
            $i++;
        }

        $this->excel->setActiveSheetIndex(0);
        $filename = 'Listado_Liquidaciones.xls'; //save our workbook as this file name

        header('Content-Type: application/vnd.ms-excel'); //mime type

        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name

        header('Cache-Control: max-age=0'); //no cache

        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format

        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');
    }
    
    function modificarDatosPaqueteria($pieza_id){
        if ($this->input->post('enviar_form')) {
            $dimensiones = $this->input->post('dimensiones');
            $peso = $this->input->post('peso');
            $bulto = $this->input->post('bulto');

            $query = " UPDATE flash_piezas_paquetes SET "
                    . " dimensiones = '".$dimensiones."', peso = ".$peso.", bultos = ".$bulto." "
                    . " WHERE pieza_id = ".$pieza_id;
//            echo $query; die;
            $result = $this->db->query($query);
            
            $query_comprobante = "SELECT ci.id "
                . "FROM flash_comprobantes_ingresos ci "
                . "INNER JOIN flash_piezas p ON p.comprobante_ingreso_id = ci.id "
                . "WHERE p.id =  ".$pieza_id;
//            echo $query_comprobante;die;
            $comprobante_ingreso = $this->db->query($query_comprobante)->row();
        
//            var_dump($result);die;
            if ($result){ 
                $this->session->set_flashdata('mensaje', "La pieza fue modificada correctamente");
            }else{
                $this->session->set_flashdata('mensaje', "La pieza no pudo ser modificada");
            }
            redirect(base_url().'piezas/piezas');
        }
        $pieza_paquete = $this->codegen_model->row('flash_piezas_paquetes','*','pieza_id = '.$pieza_id);
        $query = "SELECT numero "
                . "FROM flash_comprobantes_ingresos ci "
                . "INNER JOIN flash_piezas p ON p.comprobante_ingreso_id = ci.id "
                . "WHERE p.id =  ".$pieza_id;

        $comprobante_ingreso = $this->db->query($query)->row();
                
        $vista_interna = array(
            'pieza_paquete' => $pieza_paquete,
            'permisos_efectivos' => $this->permisos,
            'comprobante_ingreso_numero' => $comprobante_ingreso->numero,
        );

        $vista_externa = array(
            'title'          => ucwords("Modificar datos de paqueteria"),
            'contenido_main' => $this->load->view('components/piezas/comprobantes_ingresos/pieza_paqueteria_edit', $vista_interna, true),
        );

        $this->load->view('template/view', $vista_externa);
    }
    
}

/* End of file comprobantes_ingresos.php */
/* Location: ./system/application/controllers/comprobantes_ingresos.php */
