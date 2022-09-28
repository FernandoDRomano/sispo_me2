<?php

class Rendiciones extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->data->rendiciones = array();
        $this->data->sucursales = new Sucursal();
        $this->data->fecha_hasta = "";
        $this->data->clientes    = Cliente::where('cliente_estado_id', Cliente::ESTADO_ACTIVO)->get();
        /*$this->data([
            'rendiciones'  => $rendiciones,
            'cliente_id'   => $cliente_id ? $cliente_id : false,
            'rendicion_id' => $rendicion_id ? $rendicion_id : false,
            'clientes'     => Cliente::where('cliente_estado_id', Cliente::ESTADO_ACTIVO)->get(),
        ]);*/
        $this->title('rendiciones');
        $this->view = 'components/piezas/rendiciones/rendiciones_list';
        $this->template('template/backend');
    }

    public function filtro()
    {
        $cliente_id   = $this->input->post('cliente_id');
        $rendicion_id = $this->input->post('rendicion_id');

        $rendiciones = $this->flash_md->getRendiciones($cliente_id, $rendicion_id, $this->is_user());

        $this->data([
            'rendiciones'  => $rendiciones,
            'cliente_id'   => $cliente_id ? $cliente_id : false,
            'rendicion_id' => $rendicion_id ? $rendicion_id : false,
            'clientes'     => Cliente::where('cliente_estado_id', Cliente::ESTADO_ACTIVO)->get(),
        ]);

        $this->title('Rendiciones');
        $this->view = 'components/piezas/rendiciones/rendiciones_list';
        $this->template('template/backend');
    }

    public function add()
    {
        set_time_limit(1800);
        if ($this->input->post('enviar_form')) {
            $cliente_id      = $this->input->post('cliente_id') != '' ? $this->input->post('cliente_id') : false;
            $departamento_id = $this->input->post('departamento_id') != '' ? $this->input->post('departamento_id') : false;
            $servicio_id     = $this->input->post('servicio_id') != '' ? $this->input->post('servicio_id') : false;
            $numero          = $this->input->post('numero') != '' ? $this->input->post('numero') : false;
            $sucursal_id     = $this->input->post('sucursal_id') != '' ? $this->input->post('sucursal_id') : false;
            $fecha_hasta     = $this->input->post('fecha_hasta') != '' ? new DateTime($this->input->post('fecha_hasta')) : null;
            $sucursal_id_apostada     = $this->input->post('sucursal_id_apostada') != '' ? $this->input->post('sucursal_id_apostada') : false;
            $fecha_desde     = $this->input->post('fecha_desde') != '' ? new DateTime($this->input->post('fecha_desde')) : null;
            $excluir = [];
            $params = $this->input->post();
            //Extraigo de los parametros las ids de las piezas a excluir en la grabacion de la rendicion
            foreach ($params as $key=>$value){//echo substr($key, strpos($key, "_"))."<br/>";
                if(substr($key, 0,strpos($key, "_")) == "excluir" ) {
                    array_push($excluir,(int)substr($key, strpos($key, "_")+1));
                }
            }
            $busqueda = $this->input->post('busqueda') != '' ? $this->input->post('busqueda') : null;
            //Graba el encabezado sin la cantidad de piezas

            $fecha_hasta = $fecha_hasta->format('Y-m-d 23:59:59');
            $fecha_desde = $fecha_desde->format('Y-m-d 00:00:00');

            $data = array(
                'clientes_id'     => $this->input->post('cliente_id'),
                'departamento_id' => $this->input->post('departamento_id'),
                'usuario_id'      => $this->usuario->id,
                'create_user_id'      => $this->usuario->id,
                'fecha_hasta'     => $fecha_hasta,
                'fecha_desde'     => $fecha_desde,
                //'piezas' => $this->input->post('cantidad_elementos'),
                'create' => date("Y-m-d H:i:s"),
                'update' => date("Y-m-d H:i:s"),
            );
            //Traigo el ID de la rendicion creada para crear la tabla flash_rendiciones_piezas
            $rendicion_id = $this->codegen_model->add('flash_rendiciones', $data);
            //Traigo las piezas en execuciones de sql limitadas.

            $result = $this->flash_md->grabarDetalleRendicion($cliente_id,
                $departamento_id,
                $sucursal_id,
                $servicio_id,
                $numero,
                $rendicion_id,
                $fecha_hasta,
                $sucursal_id_apostada,
                $fecha_desde,
                $excluir,
                $busqueda);

            $rendicion         = Rendicion::findOrFail($rendicion_id);
            $rendicion->piezas = $result['cantidad_piezas'];
            $rendicion->save();
            /* Auditoria */
            $user_row = $this->ion_auth->user()->row();
            $data     = array(
                'user_id'     => $user_row->id,
                'categoria'   => 'RENDICIONES',
                'descripcion' => 'Rendici&oacute;n: ' . $rendicion_id . '. Nueva Rendici&oacuten.',
                'origen'      => '',
                'destino'     => '',
            );
            $this->codegen_model->add('users_log', $data);
            /* END: Auditoria */
            $this->session->set_flashdata('registroOK', $rendicion_id);
            redirect(base_url() . 'piezas/rendiciones');
        }

        $this->data->fecha_hasta = "";
        $this->data->rendiciones = [];
        $this->data->servicios   = [];
        $this->data->clientes    = $this->codegen_model->get('flash_clientes', '*', '');
        $this->data->sucursal_id = -1;
        $this->data->sucursales  = $this->is_admin() ? $this->codegen_model->get('flash_sucursales', '*', '') : $this->codegen_model->get('flash_sucursales', '*', 'id = ' . $this->usuario->sucursal_id);
        $this->data->sucursales_apostadas  = $this->codegen_model->get('flash_sucursales', '*', '') ;
        //var_dump($this->data->sucursales);die;
        $this->title('rendiciones');
        $this->view = 'components/piezas/rendiciones/rendiciones_add';
        $this->template('template/backend');
    }

    public function post_add()
    {
        $user_row = $this->ion_auth->user()->row();
        $piezas   = $this->flash_md->getPiezasRendicion($this->input->post('cliente_id'), $this->input->post('departamento_id'));

        if ($piezas) {
            $data = array(
                'cliente_id'      => $this->input->post('cliente_id'),
                'departamento_id' => $this->input->post('departamento_id'),
                'usuario_id'      => $user_row->id,
                'create' => date("Y-m-d H:i:s"),
                'update' => date("Y-m-d H:i:s"),
            );
            $rendicion = $this->codegen_model->add('flash_rendiciones', $data);
            $contador  = 0;
            $fecha     = date('Y-m-d');
            foreach ($piezas as $f) {
                $data = array(
                    'rendicion_id' => $rendicion,
                    'pieza_id'     => $f->id,
                    'create' => date("Y-m-d H:i:s"),
                    'update' => date("Y-m-d H:i:s"),
                );
                $this->codegen_model->add('flash_rendiciones_piezas', $data);

                $data = array(
                    'rendicion_id'    => $rendicion,
                    'fecha_rendicion' => $fecha,
                );
                $this->codegen_model->edit('flash_piezas', $data, 'id', $f->id);

                $contador = $contador + 1;
            }

            $data = array('piezas' => $contador);
            $this->codegen_model->edit('flash_rendiciones', $data, 'id', $rendicion);
            $this->session->set_flashdata('registroOK', 'procesado');
        } else {
            $this->session->set_flashdata('registroKO', 'procesado');
        }

        redirect(base_url() . 'piezas/rendiciones');
    }

    public function detalle($id)
    {
    	$this->data->rendicion_id = $id;
        $this->data->piezas = $this->flash_md->getALLPiezasRendicion($id);
        $this->title('rendiciones');
        $this->view = 'components/piezas/rendiciones/rendiciones_view';
        $this->template('template/backend');
    }

    public function delete($id)
    {
        set_time_limit(1800);
        $this->codegen_model->delete('flash_rendiciones_piezas', 'rendicion_id', $id);
        $this->codegen_model->delete('flash_rendiciones', 'id', $id);
        /* Auditoria */
        $user_row = $this->ion_auth->user()->row();
        $data     = array(
            'user_id'     => $user_row->id,
            'categoria'   => 'RENDICIONES',
            'descripcion' => 'Rendici&oacute;n: ' . $id . '. Eliminar Rendición.',
            'origen'      => '',
            'destino'     => '',
        );
        $this->codcoegen_model->add('users_log', $data);
        /* END: Auditoria */

    }

    public function recibio()
    {
        $vista_interna = array(
            'permisos_efectivos' => $this->permisos,
            'result'             => $this->flash_md->getALLPiezasRendicion(0),
        );

        $vista_externa = array(
            'title'          => ucwords("clientes"),
            'contenido_main' => $this->load->view('components/piezas/rendiciones/recibio_add', $vista_interna, true),
        );

        $this->load->view('template/view', $vista_externa);
    }

     public function exportar()
    {
        $rendicion_id = $this->input->post('exportar_rendicion_id');
        $impresion_tipo         = $this->input->post('exportar_impresion_tipo');
        $limite                 = $this->input->post('exportar_limite_inferior');
        if ($impresion_tipo == 'pdf') {
            $this->exportarAPDF($rendicion_id, $limite);
        }

        if ($impresion_tipo == 'excel') {
            $this->exportarAExcel($rendicion_id, $limite);}

    }
    
    public function exportarAPDF($id, $limite)
    {
        set_time_limit(12000);
        ini_set('memory_limit', '-1');
        $this->load->library('simplehtmldom_1_5/simple_html_dom');
        $this->load->library('Pdf_rendiciones');
        $piezas              = $this->flash_md->getALLPiezasRendicionAgrupadas($id, $limite);
        $rendiciones         = $this->flash_md->getRendicionesXComprobantes($id, $limite);
        $rendicion           = Rendicion::whereId($id)->first();
        $cliente             = Cliente::whereId($rendicion->clientes_id)->first();
        $clienteDepartamento = ClienteDepartamento::whereId($rendicion->departamento_id)->first();
        //Set custom header data
        $pdf = new Pdf_rendiciones('L', 'mm', 'A4', true, 'UTF-8', false); //: portrait, L:landscape

        $pdf->rendicion_id  = $id;
        $pdf->cliente       = $cliente->nombre;
        $pdf->domicilio     = $cliente->domicilio;
        $pdf->provincia     = $cliente->provincia;
        $pdf->codigo_postal = $cliente->codigo_postal;
        $pdf->departamento  = $clienteDepartamento->nombre;
        $pdf->SetTitle('Rendiciones');
        $pdf->SetHeaderMargin(300);
        $pdf->SetTopMargin(200);
        $pdf->setFooterMargin(20);
        $pdf->SetAutoPageBreak(true);
        $pdf->SetAuthor('Author');
        $pdf->SetDisplayMode('real', 'default');
        $pdf->Write(5, 'CodeIgniter TCPDF Integration');

        // set header and footer fonts
        $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP + 30, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

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

        // Add a page
        // This method has several options, check the source code documentation for more information.
        $pdf->AddPage('L', 'A4');

        // create some HTML content
        $subtable = '<table border="1" cellspacing="6" cellpadding="4"><tr><td>a</td><td>b</td></tr><tr><td>c</td><td>d</td></tr></table>';

        $html = '
            <table style="border-bottom-width: 1px solid;" width="100%">
                <tr>
                    <th width="15%">C. Ing.</th>
                    <th width="15%">Fecha</th>
                    <th width="15%">Piezas</th>
                    <th width="55%"></th>
                </tr>
            </table>
            <table style="padding-top:10px" > ';
        $table_contenido = "";
        foreach ($rendiciones as $rendicion) {
            $table_contenido .= '<tr>
                                        <td width="15%">' . $rendicion->numero . '</td>
                                        <td width="15%">' . $rendicion->fecha . '</td>
                                        <td width="15%">' . $rendicion->cantidad . '</td>
                                        <td width="55%"></td>
                                    </tr>';
        }

        $cierre_table = '</table>';
        $pdf->writeHTML($html . $table_contenido . $cierre_table, true, false, true, false, '');

        $pdf->AddPage('L', 'A4');
        $i = 0;
        $j = 0;
        //for ($i ; $i < (count($piezas)/1000); $i++){
        $html_detalle = '
                <table style="border-bottom-width: 1px solid;" width="100%">
                    <tr>
                        <th>C. Ing.</th>
                        <th>Fecha</th>
                        <th>Pieza</th>
                        <th>Codigo</th>
                        <th>Destinatario</th>
                        <th>Domicilio</th>
                        <th>Datos</th>
                        <th>C.P.</th>
                        <th>Servicio</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                    </tr>
                </table>
                <table style="padding-top:10px;font-size:8px" > ';
        $table_contenido_detalle = "";

        for ($j=0; $j < count($piezas); $j++) {
            $table_contenido_detalle .= '<tr>
                                             <td>' . $piezas[$j]->comprobante . '</td>
                                             <td>' . $piezas[$j]->fecha_comprobante . '</td>
                                             <td>' . $piezas[$j]->pieza_id . '</td>
                                             <td>' . $piezas[$j]->barcode_externo . '</td>
                                             <td>' . $piezas[$j]->destinatario . '</td>
                                             <td>' . $piezas[$j]->domicilio . '</td>
                                             <td>' . $piezas[$j]->datos_varios . '</td>
                                             <td>' . $piezas[$j]->codigo_postal . '</td>
                                             <td>' . $piezas[$j]->servicio . '</td>
                                             <td>' . $piezas[$j]->estado . '</td>
                                             <td>' . $piezas[$j]->fecha_estado . '</td>
                                         </tr>';
        }
        $cierre_table_detalle = '</table>';
        // $html_detalle.$table_contenido_detalle.$cierre_table_detalle;//die;
        // output the HTML content
        sleep(3);
        $pdf->writeHTML($html_detalle . $table_contenido_detalle . $cierre_table_detalle, true, false, true, false, '');
        $pdf->Output('rendicion_20.pdf', 'D');
        //ob_clean();
        //exit;
        // }
    }

    public function exportarAExcel($id, $limite)
    {

        ob_start();
        $piezas              = $this->flash_md->getALLPiezasRendicionAgrupadas($id, $limite);
        $rendiciones         = $this->flash_md->getRendicionesXComprobantes($id, $limite);
        $rendicion           = Rendicion::whereId($id)->first();
        $cliente             = Cliente::whereId($rendicion->clientes_id)->first();
        $clienteDepartamento = ClienteDepartamento::whereId($rendicion->departamento_id)->first();

        //load our new PHPExcel library
        $this->load->library('excel');
        //Activa la hoja nro 0
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('Rendiciones');
        //set cabeceras
        $this->excel->getActiveSheet()->setCellValue("A1", 'Rendicion:');
        $this->excel->getActiveSheet()->setCellValue("B1", $id);
        $this->excel->getActiveSheet()->setCellValue("A2", 'Cliente: ');
        $this->excel->getActiveSheet()->setCellValue("B2", $cliente->nombre);
        $this->excel->getActiveSheet()->setCellValue("A3", 'Domicilio: ');
        $this->excel->getActiveSheet()->setCellValue("B3", $cliente->domicilio);
        $this->excel->getActiveSheet()->setCellValue("A4", 'Provincia: ');
        $this->excel->getActiveSheet()->setCellValue("B4", $cliente->provincia);
        $this->excel->getActiveSheet()->setCellValue("A5", 'Codigo postal: ');
        $this->excel->getActiveSheet()->setCellValue("B5", $cliente->codigo_postal);
        $this->excel->getActiveSheet()->setCellValue("A6", 'Departamento');
        $this->excel->getActiveSheet()->setCellValue("B6", $clienteDepartamento->nombre);
        $this->excel->getActiveSheet()->setCellValue("A7", 'Comprobante');
        $this->excel->getActiveSheet()->setCellValue("B7", 'Fecha');
        $this->excel->getActiveSheet()->setCellValue("C7", 'Piezas');

        $linea = 8;
        foreach ($rendiciones as $rendicion) {
            // echo $trs->find('td',1)->innertext;
            $this->excel->getActiveSheet()->setCellValueExplicit("A$linea", $rendicion->numero); //numero
            $this->excel->getActiveSheet()->setCellValue("B$linea", $rendicion->fecha); //fecha
            $this->excel->getActiveSheet()->setCellValue("C$linea", $rendicion->cantidad); //pieza
            $linea++;
        }
        $this->excel->createSheet(1);
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(1);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('Piezas');
        //set cabeceras
        $this->excel->getActiveSheet()->setCellValue("A1", 'Rendicion:');
        $this->excel->getActiveSheet()->setCellValue("B1", $id);
        $this->excel->getActiveSheet()->setCellValue("A2", 'Cliente: ');
        $this->excel->getActiveSheet()->setCellValue("B2", $cliente->nombre);
        $this->excel->getActiveSheet()->setCellValue("A3", 'Domicilio: ');
        $this->excel->getActiveSheet()->setCellValue("B3", $cliente->domicilio);
        $this->excel->getActiveSheet()->setCellValue("A4", 'Provincia: ');
        $this->excel->getActiveSheet()->setCellValue("B4", $cliente->provincia);
        $this->excel->getActiveSheet()->setCellValue("A5", 'Codigo postal: ');
        $this->excel->getActiveSheet()->setCellValue("B5", $cliente->codigo_postal);
        $this->excel->getActiveSheet()->setCellValue("A6", 'Departamento');
        $this->excel->getActiveSheet()->setCellValue("B6", $clienteDepartamento->nombre);
        $this->excel->getActiveSheet()->setCellValue("A7", 'Comprobante');
        $this->excel->getActiveSheet()->setCellValue("B7", 'Fecha');
        $this->excel->getActiveSheet()->setCellValue("C7", 'Pieza');
        $this->excel->getActiveSheet()->setCellValue("D7", 'Codigo');
        $this->excel->getActiveSheet()->setCellValue("E7", 'Destinatario');
        $this->excel->getActiveSheet()->setCellValue("F7", 'Domicilio');
        $this->excel->getActiveSheet()->setCellValue("G7", 'Datos');
        $this->excel->getActiveSheet()->setCellValue("H7", 'Codigo Postal');
        $this->excel->getActiveSheet()->setCellValue("I7", 'Servicio');
        $this->excel->getActiveSheet()->setCellValue("J7", 'Estado');
        $this->excel->getActiveSheet()->setCellValue("K7", 'Fecha estado');
        $this->excel->getActiveSheet()->setCellValue("L7", 'Datos Varios 3');
        $linea = 8;
        foreach ($piezas as $pieza) {
            // echo $trs->find('td',1)->innertext;
            $this->excel->getActiveSheet()->setCellValueExplicit("A$linea", $pieza->comprobante); //numero
            $this->excel->getActiveSheet()->setCellValue("B$linea", $pieza->fecha_comprobante); //fecha
            $this->excel->getActiveSheet()->setCellValue("C$linea", $pieza->pieza_id); //pieza
            $this->excel->getActiveSheet()->setCellValue("D$linea", $pieza->barcode_externo); //pieza
            $this->excel->getActiveSheet()->setCellValue("E$linea", $pieza->destinatario); //Destinatario
            $this->excel->getActiveSheet()->setCellValue("F$linea", $pieza->domicilio); //Domicilio
            $this->excel->getActiveSheet()->setCellValue("G$linea", $pieza->datos_varios); //Domicilio
            $this->excel->getActiveSheet()->setCellValue("H$linea", $pieza->codigo_posta); //Codigo postal
            $this->excel->getActiveSheet()->setCellValue("I$linea", $pieza->servicio); //Servicio
            $this->excel->getActiveSheet()->setCellValue("J$linea", $pieza->estado); //Estado
            $this->excel->getActiveSheet()->setCellValue("K$linea", $pieza->fecha_estado); //Cambio de Estado
            $this->excel->getActiveSheet()->setCellValue("L$linea", $pieza->datos_varios_3); //Datos varios 3
            $linea++;
        }

        $this->excel->setActiveSheetIndex(0);

        $security = new PHPExcel_DocumentSecurity();
        $this->excel->setSecurity($security);
        $filename = 'rendiciones.xls'; //save our workbook as this file name

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

    public function consultas_rendiciones()
    {
        $vista_interna = array(
            'permisos_efectivos' => $this->permisos,
            'results'            => []
        );

        $vista_externa = array(
            'title'          => ucwords("Consultas por Rendiciones"),
            'contenido_main' => $this->load->view('components/piezas/rendiciones/consultas/consultas_rendiciones', $vista_interna, true),
        );

        $this->load->view('template/backend', $vista_externa);
    }

    public function consultas_rendiciones_filtro()
    {
        $periodo_desde = $this->input->post('periodo_desde') != "" ? $this->input->post('periodo_desde') : false;
        $periodo_hasta = $this->input->post('periodo_hasta') != "" ? $this->input->post('periodo_hasta') : false;

        $vista_interna = array(
            'permisos_efectivos' => $this->permisos,
            'results'            => $this->flash_md->getRendicionesFechaRendicion($periodo_desde, $periodo_hasta),
            'periodo_desde'      => $periodo_desde,
            'periodo_hasta'      => $periodo_hasta,
        );

        $vista_externa = array(
            'title'          => ucwords("Listado de Rendiciones"),
            'contenido_main' => $this->load->view('components/piezas/rendiciones/consultas/consultas_rendiciones', $vista_interna, true),
        );

        $this->load->view('template/backend', $vista_externa);
    }

    public function exportar_consulta_rendiciones()
    {
        $periodo_desde = $this->input->post('excel_desde') != "" ? $this->input->post('excel_desde') : false;
        $periodo_hasta = $this->input->post('excel_hasta') != "" ? $this->input->post('excel_hasta') : false;

        $hojas_rutas = $this->flash_md->getRendicionesFechaRendicion($periodo_desde, $periodo_hasta);

        //load our new PHPExcel library
        $this->load->library('excel');
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('Consulta por Rendiciones');
        //set cabeceras
        $this->excel->getActiveSheet()->setCellValue("A1", 'Sucursal');
        $this->excel->getActiveSheet()->setCellValue("B1", 'C.I.');
        $this->excel->getActiveSheet()->setCellValue("C1", 'Fecha Comp.');
        $this->excel->getActiveSheet()->setCellValue("D1", 'Cliente');
        $this->excel->getActiveSheet()->setCellValue("E1", 'Nro.');
        $this->excel->getActiveSheet()->setCellValue("F1", 'Fecha');
        $this->excel->getActiveSheet()->setCellValue("G1", 'Total Piezas');
        // set cells values
        $this->load->library('simplehtmldom_1_5/simple_html_dom');
        $i = 2;
        foreach ($hojas_rutas as $value) {
            // echo $trs->find('td',1)->innertext;
            $this->excel->getActiveSheet()->setCellValue("A$i", $value->sucursal);
            $this->excel->getActiveSheet()->setCellValueExplicit("B$i", $value->numero);
            $this->excel->getActiveSheet()->setCellValue("C$i", $value->fecha_comprobante);
            $this->excel->getActiveSheet()->setCellValue("D$i", $value->cliente);
            $this->excel->getActiveSheet()->setCellValue("E$i", $value->rendicion_id);
            $this->excel->getActiveSheet()->setCellValue("F$i", $value->fecha);
            $this->excel->getActiveSheet()->setCellValue("G$i", $value->piezas);
            $i++;
        }

        $this->excel->setActiveSheetIndex(0);
        $filename = 'Listado_Rendiciones.xls'; //save our workbook as this file name

        header('Content-Type: application/vnd.ms-excel'); //mime type

        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name

        header('Cache-Control: max-age=0'); //no cache

        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format

        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');
    }
}

/* End of file rendiciones.php */
/* Location: ./system/application/controllers/rendiciones.php */
