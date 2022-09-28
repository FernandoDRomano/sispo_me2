<?php

class Hojas_rutas_alta extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if($this->input->is_post())
        {
            $this->save(true);
            redirect(base_url('hojas_rutas/hojas_rutas'));
        }

        $this->set_data();

        $this->title("Alta hoja de ruta");
        $this->view('components/hojas_rutas/hojas_rutas_alta/hojas_rutas_index');
        $this->template('template/backend');
    }

    public function hoja($id)
    {      //  var_dump($_POST);die;
        $hoja = Hoja::findOrFail($id);
        if($hoja->estado == Hoja::ESTADO_INICIADA)
        {
            $this->editar($id);
        }
        else
        {
            $this->ver($id);
        }
    }

    public function editar($id)
    {   //echo "hojas_rutas_edit";die;
        if($this->input->is_post())
        {
            $this->save(false);
            redirect(base_url('hojas_rutas/hojas_rutas'));
        }

        $hoja = Hoja::findOrFail($id);

        $this->set_data();
        //$piezas = $this->flash_md->getPiezasPorHDR($hoja->id);
        $piezas = $this->flash_md->getPiezasYEstadosPorHDR($hoja->id);
        $this->data->piezas = $piezas;
        $this->data->hoja = $hoja;
        
        $this->title("Editar hoja de ruta");

        $this->view('components/hojas_rutas/hojas_rutas_alta/hojas_rutas_edit');

        $this->template('template/backend');
    }

    public function ver($id)
    {
        $hoja = Hoja::findOrFail($id);

        $this->data->estados = PiezaEstadoVariable::wherePiezaEstadoId(PiezaEstado::ESTADOS_RENDICIONES)->get();
        $this->data->hoja = $hoja;
        $this->data->piezas = $this->flash_md->getPiezasYEstadosPorHDR($hoja->id);
        $this->title("Ver hoja de ruta");
        $this->view('components/hojas_rutas/hojas_rutas_alta/hojas_rutas_view');
        $this->template('template/backend');
    }


    private function set_data()
    {
        if ($this->is_user())
        {
            $carteros = Cartero::whereSucursalId($this->usuario->sucursal_id)->whereActivo(1)->get();
            $zonas = Zona::whereSucursalId($this->usuario->sucursal_id)->get();
        }
        else
        {
            $carteros = Cartero::whereActivo(1)->get();
            $zonas = Zona::all();
        }
        
        $this->data([
            'carteros' => $carteros,
            'zonas' => $zonas,
            'distribuidores' => $this->codegen_model->get('flash_distribuidores','*',''),
            'transportes' => $this->codegen_model->get('flash_transportes','*',''),
        ]);
    }
    
    private function save($create)
    {       
        
        
        //var_dump($_POST)."<br/>";
        //Grabo encabezado
        $cantidad_filas = $this->input->post('contador_submit_simple');
        $cantidad_total_piezas = 0;
        for($i=0; $i<$cantidad_filas; $i++){
            $cantidad_total_piezas += $this->input->post('cantidad_'.$i);
        }
        $data = array(
            'observaciones' => $this->input->post('observaciones'),
            'fecha_entrega' => formatdate($this->input->post('fecha_entrega')),
            'piezas' => $cantidad_total_piezas,
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

        $data['estado'] = $this->input->post('estado');
        $data['sucursal_id'] = $this->usuario->sucursal_id;

        if($create)
        {   
            $hoja = Hoja::create($data);
        }
        //Con la HDR creada comienzo a grabar las piezas en la tabla FLASH_SUBPIEZAS
        if ($this->input->post('piezas_ids')){
            echo "normal";
        }else{
            for ($i = 0 ; $i < $cantidad_filas; $i++){
                $comprobante_ingreso_numero =  $this->input->post('comprobante_'.$i);
                $comprobante_ingreso = Comprobante::whereNumero($comprobante_ingreso_numero)->first();
                $servicio_id = $this->input->post('servicios_'.$i);
                $cantidad = $this->input->post('cantidad_'.$i);
                
                $sql = " INSERT  INTO flash_subpiezas (hoja_ruta_id, pieza_id, liquidacion_id, cantidad) 
                        SELECT 
                                $hoja->id as hoja_ruta_id, p.id as pieza_id, 0, 1
                        FROM `flash_piezas` `p`
                        WHERE `p`.`id` NOT IN(SELECT
                                                sp.pieza_id
                                              FROM flash_subpiezas AS sp
                                              WHERE sp.pieza_id IS NOT NULL)
                        AND p.tipo_id = ".PIEZA_TIPO_SIMPLE." 
                        AND p.servicio_id = ".$servicio_id." 
                        AND p.comprobante_ingreso_id = ".$comprobante_ingreso->id."
                        and p.sucursal_id = $this->usuario->sucursal_id
                        LIMIT ".$cantidad;
          
            //Ejecuto la QUERY de INSERCION
            $query = $this->db->query($sql);
            //Actualizo el estado de las piezas cargadas a EN DISTRIBUCION
            $sql_update = " UPDATE flash_piezas p
                                    SET estado_id = ".Pieza::ESTADO_EN_DISTRIBUCION."
                                    WHERE `p`.`id` IN(SELECT
                                                          sp.pieza_id
                                                          FROM flash_subpiezas AS sp
                                                          WHERE sp.pieza_id IS NOT NULL)
                                    AND p.tipo_id = 1 
                                    AND p.servicio_id = ".$servicio_id." 
                                    AND p.comprobante_ingreso_id = ".$comprobante_ingreso->id."
                                     and p.sucursal_id = $this->usuario->sucursal_id
                                    LIMIT ".$cantidad;//SIMPLES
            //Ejecuto la QUERY de UPDATE
            //echo $sql_update."<br/>";
            $query = $this->db->query($sql_update);
            }
        }
        $this->session->set_flashdata('registro', $hoja->barcode);
    }
    
    public function exportarHDRPendientes(){
            $periodo_desde = $this->input->post('excel_desde') != ""?$this->input->post('excel_desde'):false;
            $periodo_hasta = $this->input->post('excel_hasta') != ""?$this->input->post('excel_hasta'):false;
            $sucursal_id = $this->input->post('excel_sucursal_id') != ""?$this->input->post('excel_sucursal_id'):false;
            
            $hojas_rutas = $this->flash_md->getHDRPendientes($periodo_desde,$periodo_hasta,$sucursal_id,4);//sinfinishing
            //load our new PHPExcel library
            $this->load->library('excel');
            //activate worksheet number 1
            $this->excel->setActiveSheetIndex(0);
            //name the worksheet
            $this->excel->getActiveSheet()->setTitle('Hojas de Rutas Pendientes');
            //set cabeceras
            $this->excel->getActiveSheet()->setCellValue("A1",'HDR');
            $this->excel->getActiveSheet()->setCellValue("B1",'Cartero');
            $this->excel->getActiveSheet()->setCellValue("C1",'Piezas');
            $this->excel->getActiveSheet()->setCellValue("D1",'Fecha Creación');
            // set cells values
            $this->load->library('simplehtmldom_1_5/simple_html_dom'); 
            $i = 2;
            foreach ($hojas_rutas as $value) {
                    // echo $trs->find('td',1)->innertext;
                    $this->excel->getActiveSheet()->setCellValue("A$i",$value->hdr_id);
                    $this->excel->getActiveSheet()->setCellValue("B$i",$value->apellido_nombre);
                    $this->excel->getActiveSheet()->setCellValue("C$i",$value->piezas);
                    $this->excel->getActiveSheet()->setCellValue("D$i",$value->fecha_creacion);
                    $i++;
            }
            
            $this->excel->setActiveSheetIndex(0);
            $filename='Listado_Hojas_Rutas_pendiente_de_rendir.xls'; //save our workbook as this file name

            header('Content-Type: application/vnd.ms-excel'); //mime type

            header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name

            header('Cache-Control: max-age=0'); //no cache

            //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
            //if you want to save it as .XLSX Excel 2007 format

            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5'); 

            //force user to download the Excel file without writing it to server's HD
            $objWriter->save('php://output');
    }
    
        public function exportarHDRPendientesXCarteros(){
        $periodo_desde = $this->input->post('excel_desde') != ""?$this->input->post('excel_desde'):false;
        $periodo_hasta = $this->input->post('excel_hasta') != ""?$this->input->post('excel_hasta'):false;
        $cartero_id = $this->input->post('excel_cartero_id') != ""?$this->input->post('excel_cartero_id'):false;
        $sucursal_id = $this->input->post('excel_sucursal_id') != ""?$this->input->post('excel_sucursal_id'):false;

        $hojas_rutas = $this->flash_md->getHDRPendientesXCarteros($periodo_desde,$periodo_hasta,$cartero_id,$sucursal_id);

        $headers = array('Cliente', 'Cartero','Codigo','Fecha Ing.','C.I.','Pieza ID','Servicio','Hoja de Ruta ID','Fecha HR','Destinatario','Domicilio','Localidad');

        $i=0;
        $result = NULL;
        foreach ($hojas_rutas as $value) {
         $result[$i] =  array($value->cliente,$value->apellido_nombre,$value->codigo_externo,$value->fecha_ingreso,$value->numero,$value->pieza_id,
                     $value->servicio,$value->hoja_ruta_id,$value->fecha_creacion_hdr,$value->destinatario,$value->domicilio,$value->localidad);
         $i++;
         }         
        // Create file and make it writable
        $file = fopen('hdr_pendientes_carteros.csv', 'w');

        // Add BOM to fix UTF-8 in Excel

        fputs($file, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));

        // Headers
        // Set ";" as delimiter

        fputcsv($file, $headers, ";");

        // Rows
        // Set ";" as delimiter

        foreach ($result as $row) {
            fputcsv($file, $row, ";");
        }

        // Close file

        fclose($file);

        // Send file to browser for download

        $dest_file = 'hdr_pendientes_carteros.csv';
        $file_size = filesize($dest_file);

        header("Content-Type: text/csv; charset=utf-8");
        header("Content-disposition: attachment; filename=\"'hdr_pendientes_carteros.csv\"");
        header("Content-Length: " . $file_size);
        readfile($dest_file);

        die();
      
    }
    
    public function exportarHDRPendientesXClientes(){
        $periodo_desde = $this->input->post('excel_desde') != ""?$this->input->post('excel_desde'):false;
        $periodo_hasta = $this->input->post('excel_hasta') != ""?$this->input->post('excel_hasta'):false;
        $cliente_id = $this->input->post('excel_cliente_id') != ""?$this->input->post('excel_cliente_id'):false;
        $sucursal_id = $this->input->post('excel_sucursal_id') != ""?$this->input->post('excel_sucursal_id'):false;
        $hojas_rutas = $this->flash_md->getHDRPendientesXClientes($periodo_desde,$periodo_hasta,$cliente_id,$sucursal_id);


        $headers = array('Sucursal', 'Fecha Ingreso','C.I.','Cant. Total','Cliente','Servicio','Código','Pieza','Destinatario','Domicilio','Localidad');

        $i=0;
        $result = NULL;
        foreach ($hojas_rutas as $value) {
         $result[$i] =  array($value->sucursal,$value->fecha_ingreso,$value->numero,$value->cantidad,$value->cliente,$value->servicio,
                     $value->codigo_externo,$value->pieza_id,$value->destinatario,$value->domicilio,$value->localidad);
         $i++;
         }         
        // Create file and make it writable
        $file = fopen('hdr_pendientes_clientes.csv', 'w');

        // Add BOM to fix UTF-8 in Excel

        fputs($file, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));

        // Headers
        // Set ";" as delimiter

        fputcsv($file, $headers, ";");

        // Rows
        // Set ";" as delimiter

        foreach ($result as $row) {
            fputcsv($file, $row, ";");
        }

        // Close file

        fclose($file);

        // Send file to browser for download

        $dest_file = 'hdr_pendientes_clientes.csv';
        $file_size = filesize($dest_file);

        header("Content-Type: text/csv; charset=utf-8");
        header("Content-disposition: attachment; filename=\"'hdr_pendientes_clientes.csv\"");
        header("Content-Length: " . $file_size);
        readfile($dest_file);

        die();
    }
    

    public function hojas_rutas_alta_masiva_add()
    {
        
        
        $user_row = $this->ion_auth->user()->row();
        if($this->input->is_post())
        {
            ini_set('memory_limit','-1');
		    set_time_limit(1800);

            $path = $this->input->post('path_piezas_add');
            $hdr_id = $this->input->post('hdr_id_add');
            $hoja_ruta = $this->codegen_model->row("flash_hojas_rutas","*","id = ".$hdr_id);
            if ($hdr_id > 0){    
                $file = fopen($path,"r");
                           
                $array_temp = fgetcsv($file, 0,";");
                $this->session->flashdata('piezas_leidas',count($array_temp));
                
                fclose($file);
                $piezas_barcodes = '"'.implode("\",\"", $array_temp).'"';
                $sql_piezas_ids = " SELECT p.id FROM flash_piezas p
                                         INNER JOIN flash_piezas_estados_variables ev on p.estado_id = ev.id
                                          WHERE barcode_externo IN ($piezas_barcodes) 
                                           AND p.sucursal_id = $user_row->sucursal_id
                                            AND ev.pieza_estado_id IN (" . PiezaEstado::ESTADOS_INICIALES . "," . PiezaEstado::ESTADOS_ORGANIZATIVOS . ")";
                //echo $sql_piezas_ids;die;
    
                $results_piezas_ids = $this->db->query($sql_piezas_ids);
                $piezas_ids = $results_piezas_ids->result();
    
                $this->session->set_flashdata('cantidad_piezas', count($piezas_ids));
                
                if (count($array_temp) != count($piezas_ids)) {
                    echo "La cantidad de CODIGOS DE BARRA (".count($array_temp).") no coincide con la cantidad de PIEZAS ENCONTRADAS (".count($piezas_ids).") ";
                    die;
                    //$this->session->set_flashdata('cantidad_piezas', "La cantidad de CODIGOS DE BARRA no coincide con la cantidad de PIEZAS ENCONTRADAS");
                    //return;
                }
            
                if (count($piezas_ids) > 2000) {echo "Error!: se estan tratando de grabar ".count($piezas_ids)." piezas";die;}
                
                foreach ($piezas_ids as $value) {
                    $piezas_ids_array[] = array(
                                                "hoja_ruta_id" => $hdr_id,
                                                "pieza_id" => $value->id,
                                                "liquidacion_id" => 0,
                                                "cantidad" => 1,
                                                "pieza_estado_id" => Pieza::ESTADO_EN_DISTRIBUCION,
                                                "create" => date("Y-m-d H:i:s")
                                            );
                }
                $inserted_rows = $this->db->insert_batch('flash_subpiezas', $piezas_ids_array);
                $query_ejecutada = $this->db->last_query();
                
                 /* Auditoria */
                $user_row = $this->ion_auth->user()->row();
                $data     = array(
                    'user_id'     => $user_row->id,
                    'categoria'   => 'HOJAS DE RUTAS ALTA MASIVA',
                    'descripcion' => 'Hoja de Ruta: ' . $hdr_id . '. Estado: ' . $hoja_ruta->estado . ' Query: '.$query_ejecutada,
                    'origen'      => '',
                    'destino'     => '',
                );
                $this->codegen_model->add('users_log', $data);
                /* END: Auditoria */
                
                $this->session->set_flashdata('piezas_insertadas_hdr', $inserted_rows);  
               
            	
                
                $where_pieza_id = [];
                //Actualizo el estado de la tabla PIEZAS 18_09_2018
                for($i = 0; $i < count($piezas_ids); $i++){
                    $data_update_piezas[] = array(
                        "id" => $piezas_ids[$i]->id,
                        "estado_id"  => Pieza::ESTADO_EN_DISTRIBUCION,
                        "hoja_ruta_id" => $hdr_id,
                    );
                    array_push($where_pieza_id, $piezas_ids[$i]->id);
                }
                 //Antes de actualizar el estado en la tabla piezas, actualizo novedades
                //Para eso traigo solo las piezas normales
                $where_pieza_id = implode(",",$where_pieza_id);
                $update_novedades = " UPDATE flash_piezas_novedades n
                                                    INNER JOIN flash_piezas p ON p.id = n.pieza_id
                                                    SET estado_actual_id = p.estado_id,
                                                        estado_nuevo_id = " . Pieza::ESTADO_EN_DISTRIBUCION . "
                                                           WHERE pieza_id IN (" . $where_pieza_id.") 
                                                                AND p.tipo_id = ".Pieza::TIPO_NORMAL;
                $this->db->query($update_novedades);  

                
                $update_rows = $this->db->update_batch('flash_piezas',$data_update_piezas,'id');
                $query_ejecutada_update = $this->db->last_query();
                
                //Inserto en la tabla tracking
                //Filtro del array piezas_ids solo las piezas Normales
                $ids_normales = "SELECT p.id 
                                    FROM flash_piezas p
                                        WHERE id IN ( ".$where_pieza_id." )
                                            AND p.tipo_id = ". Pieza::TIPO_NORMAL;
                $ids_normales = $this->db->query($ids_normales);

                $array_piezas_tracking = []; 
                for($i = 0 ; $i < count($piezas_ids); $i++){
                     $element_pieza =array(
                        'pieza_id'              => $piezas_ids[$i]->id,
                        'usuario_id'            => $user_row->id,
                        'estado_id'             => Pieza::ESTADO_EN_DISTRIBUCION, 
                        'create'                => date("Y-m-d H:i:s"),
                        'update'                => date("Y-m-d H:i:s"),
                        'create_user_id'        =>  $user_row->id,
                        'update_user_id'         => $user_row->id,
                    );
                    array_push($array_piezas_tracking, $element_pieza);
                }
                //var_dump($array_piezas_tracking);die;
                $this->db->insert_batch('flash_piezas_tracking', $array_piezas_tracking);	
                /* Auditoria */
                $user_row = $this->ion_auth->user()->row();
                $data     = array(
                    'user_id'     => $user_row->id,
                    'categoria'   => 'HOJAS DE RUTAS ALTA MASIVA update de piezas',
                    'descripcion' => 'Hoja de Ruta: ' . $hdr_id . '. Estado: ' . $hoja_ruta->estado . ' Query: '.$query_ejecutada_update,
                    'origen'      => '',
                    'destino'     => '',
                );
                $this->codegen_model->add('users_log', $data);
                /* END: Auditoria */
                
                $this->session->set_flashdata('piezas_actualizadas', $update_rows);  
                $this->session->set_flashdata('info', 1); 
                $this->session->set_flashdata('hdr_id', $hdr_id); 
                redirect(base_url('hojas_rutas/hojas_rutas_alta/hojas_rutas_alta_masiva_add'));
            }else{
                $this->session->set_flashdata('info', 1); 
                $this->session->set_flashdata('hdr_id', " El nro. de HDR no puede ser 0 (CERO)"); 
                redirect(base_url('hojas_rutas/hojas_rutas_alta/hojas_rutas_alta_masiva_add')); 
            }
        }

       // $this->set_data();

        $this->title("Alta hoja de ruta Importación masiva de piezas");
        $this->view('components/hojas_rutas/hojas_rutas_alta/hojas_rutas_alta_masiva_add');
        $this->template('template/backend');
        
    }    
    
    public function exportarDetalleHDRAExcel(){
            $hdr_id = $this->input->post('hdr_id');

            $hoja = Hoja::findOrFail($hdr_id);

            $hdr_detalles = $this->flash_md->getPiezasYEstadosPorHDR($hoja->id);
            
            //load our new PHPExcel library
            $this->load->library('excel');
            //activate worksheet number 1
            $this->excel->setActiveSheetIndex(0);
            //name the worksheet
            $this->excel->getActiveSheet()->setTitle('Consulta Global');
            //set cabeceras
            $this->excel->getActiveSheet()->setCellValue("A1",'HDR:');
            $this->excel->getActiveSheet()->setCellValue("B1",$hoja->id);
            if($hoja->cartero_id){
                $this->excel->getActiveSheet()->setCellValue("A2",'Cartero:');
                $this->excel->getActiveSheet()->setCellValue("B2",$hoja->cartero->apellido_nombre);
            }          
            if($hoja->distribuidor_id){
                $this->excel->getActiveSheet()->setCellValue("A2",'Distribuidor:');
                $this->excel->getActiveSheet()->setCellValue("B2",$hoja->distribuidor->apellido_nombre); 
            }  
            $this->excel->getActiveSheet()->setCellValue("A3",'Fecha Entrega:');
            $this->excel->getActiveSheet()->setCellValue("B3",$hoja->fecha_entrega->format('d-m-Y'));
            $this->excel->getActiveSheet()->setCellValue("A4",'Zona:');
            $this->excel->getActiveSheet()->setCellValue("B4",$hoja->zona->nombre);
            $this->excel->getActiveSheet()->setCellValue("A5",'Estado:');
            $this->excel->getActiveSheet()->setCellValue("B5",$hoja->estado_nombre);
            $this->excel->getActiveSheet()->setCellValue("C5",$hoja->fecha_baja?$hoja->fecha_baja->format('d-m-Y'):'');
            $this->excel->getActiveSheet()->setCellValue("A7",'HDR');
            $this->excel->getActiveSheet()->setCellValue("B7",'Pieza');
            $this->excel->getActiveSheet()->setCellValue("C7",'Cliente');
            $this->excel->getActiveSheet()->setCellValue("D7",'Servicio');
            $this->excel->getActiveSheet()->setCellValue("E7",'Descripcion');
            $this->excel->getActiveSheet()->setCellValue("F7",'Estado');
            // set cells values
            $this->load->library('simplehtmldom_1_5/simple_html_dom'); 
            $i = 8;
            foreach ($hdr_detalles as $value) {
                    // echo $trs->find('td',1)->innertext;
                    $this->excel->getActiveSheet()->setCellValue("A$i",$value->barcode);
                    $this->excel->getActiveSheet()->setCellValue("B$i",$value->tipo_id == Pieza::TIPO_SIMPLE?'('.$value->cantidad.' piezas)':$value->barcode);
                    $this->excel->getActiveSheet()->setCellValue("C$i",$value->cliente);
                    $this->excel->getActiveSheet()->setCellValue("D$i",$value->servicio);
                    $this->excel->getActiveSheet()->setCellValue("E$i",($value->novedades != null)?$value->novedades->count():"");
                    $this->excel->getActiveSheet()->setCellValue("F$i",$value->estado);
                    $i++;
            }
            
            $this->excel->setActiveSheetIndex(0);
            $filename='Hoja de Ruta.xls'; //save our workbook as this file name

            header('Content-Type: application/vnd.ms-excel'); //mime type

            header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name

            header('Cache-Control: max-age=0'); //no cache

            //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
            //if you want to save it as .XLSX Excel 2007 format

            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5'); 

            //force user to download the Excel file without writing it to server's HD
            $objWriter->save('php://output');
    }    
}

/* End of file Hojas_rutas_alta.php */
/* Location: ./system/application/controllers/Hojas_rutas_alta.php */