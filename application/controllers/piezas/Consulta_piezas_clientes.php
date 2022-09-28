<?php


class Consulta_piezas_clientes extends CI_Controller {

	private $permisos;
	
	function __construct() {
		parent::__construct();
		$this->permisos = $this->permisos_lib->control();
	}	
	
	function index(){
            if ($this->ion_auth->isAdmin())
            {
                $clientes = Cliente::all();
                $sucursales = $this->flash_md->getSucursales();
            }
            else
            {
                $user_row = $this->ion_auth->user()->row();
                $clientes = $this->db
                                    ->select('c.*')
                                    ->from('flash_clientes c')
                                    ->join('flash_comprobantes_ingresos ci', 'c.id = ci.cliente_id')
                                    ->join('flash_piezas p', 'p.comprobante_ingreso_id = ci.id')
                                    ->join('flash_subpiezas sp', 'sp.pieza_id = p.id')
                                    ->join('flash_hojas_rutas hdr', 'sp.hoja_ruta_id = hdr.id')
                                    ->join('flash_sucursales_carteros sc', 'sc.id = hdr.cartero_id')
                                    ->join('flash_sucursales s', 's.id = sc.sucursal_id')
                                    ->where('s.id = ', $user_row->sucursal_id)
                                    ->group_by('c.id')
                                    ->get();
                $clientes = $clientes->result();
                $sucursales = Sucursal::whereId($user_row->sucursal_id)->get();
            }
            
            
            $vista_interna = array(
                     'permisos_efectivos' => $this->permisos,
                     'sucursales' => $sucursales,
                     'clientes' => $clientes,
                     'results' => []
             );

            $vista_externa = array(			
                    'title' => ucwords("Estados de piezas por clientes"),
                    'contenido_main' => $this->load->view('components/piezas/piezas/consulta_piezas_clientes', $vista_interna, true)
            );		
		
		$this->load->view('template/backend', $vista_externa);
	}

    function filter(){
        set_time_limit(1800);
        $cliente = $this->input->post('cliente');
        $cliente_id = $this->input->post('cliente_id');
        $periodo_desde = $this->input->post('periodo_desde');
        $periodo_hasta = $this->input->post('periodo_hasta');
        if ($this->ion_auth->isAdmin())
            {
                $clientes = Cliente::all();
                $sucursales = $this->flash_md->getSucursales();
            }
            else
            {
                $user_row = $this->ion_auth->user()->row();
                $clientes = $this->db
                                    ->select('c.*')
                                    ->from('flash_clientes c')
                                    ->join('flash_comprobantes_ingresos ci', 'c.id = ci.cliente_id')
                                    ->join('flash_piezas p', 'p.comprobante_ingreso_id = ci.id')
                                    ->join('flash_subpiezas sp', 'sp.pieza_id = p.id')
                                    ->join('flash_hojas_rutas hdr', 'sp.hoja_ruta_id = hdr.id')
                                    ->join('flash_sucursales_carteros sc', 'sc.id = hdr.cartero_id')
                                    ->join('flash_sucursales s', 's.id = sc.sucursal_id')
                                    ->where('s.id = ', $user_row->sucursal_id)
                                    ->group_by('c.id')
                                    ->get();
                $clientes = $clientes->result();
                $sucursales = Sucursal::whereId($user_row->sucursal_id)->get();
            }
            
            $where = "";
            $nexo = " WHERE ";
            if ($cliente_id > 0) {
                $where =  $nexo." ci.cliente_id =  ".$cliente_id;
                $nexo = " AND ";
             }   
            if ($periodo_desde != "") {
                $periodo_desde = new DateTime($periodo_desde);
                $where .= $nexo." ci.create >= '".$periodo_desde->format('Y-m-d 00:00:00')."'";
                $nexo = " AND ";
             }   
            if ($periodo_hasta != "") {
                $periodo_hasta = new DateTime($periodo_hasta);
                $where .= $nexo." ci.create <= '".$periodo_hasta->format('Y-m-d 23:59:59')."'";
                $nexo = " AND ";
             }   

            $consulta = "  SELECT ci.id comprobante_ingreso_id, ci.cliente_id, ci.numero numero_comprobante, ci.create fecha_comprobante,
                            cis.servicio_id servicio_id, s.nombre servicio, cis.cantidad piezas_por_servicio, ev.nombre estado, ev.id estado_id
                            FROM flash_comprobantes_ingresos ci
                            INNER JOIN flash_piezas p on ci.id = p.comprobante_ingreso_id
                            INNER JOIN flash_comprobantes_ingresos_servicios cis on p.servicio_id = cis.id
                            INNER JOIN flash_servicios s on s.id = cis.servicio_id
                            INNER JOIN flash_piezas_estados_variables ev ON p.estado_id = ev.id
                            $where
                            GROUP by ci.id, cis.servicio_id, p.estado_id";
//            $consulta = "SELECT result.numero, result.fecha_comprobante, result.servicio, result.fecha_rendicion, result.estado, result.piezas_por_estado, 
//                                SUM(result.piezas_por_estado) AS piezas_con_estado, 
//                                result.cantidad_total AS piezas_en_comprobante
//                        FROM 
//                        (SELECT ci.`numero`, DATE_FORMAT(ci.`create`,'%d-%m-%Y') fecha_comprobante,  s.`nombre` servicio, DATE_FORMAT(ci.`create`,'%d-%m-%Y') fecha_rendicion,ev.`nombre` estado, 
//                                COUNT(*) AS piezas_por_estado , 
//                                (SELECT COUNT(p.id) FROM flash_piezas p 
//                                 WHERE p.`comprobante_ingreso_id` = ci.`id`  ) cantidad_total
//                        FROM flash_piezas p
//                        INNER JOIN flash_comprobantes_ingresos ci ON p.`comprobante_ingreso_id` = ci.`id`
//                        INNER JOIN flash_comprobantes_ingresos_servicios cis ON p.`servicio_id` = cis.`id`
//                        INNER JOIN flash_servicios s ON cis.`servicio_id` = s.`id`
//                        INNER JOIN flash_piezas_estados_variables ev ON p.`estado_id` = ev.`id`
//                        INNER JOIN flash_clientes c ON ci.`cliente_id` = c.`id`
//                        INNER JOIN flash_rendiciones_piezas rp ON rp.pieza_id = p.id
//                        INNER JOIN flash_rendiciones r ON r.`id` = rp.`rendicion_id`
//                        $where
//                        AND ev.`pieza_estado_id` IN (2) #EN RENDICION
//                        AND p.id IN (SELECT pieza_id FROM flash_rendiciones_piezas p ) #Que esten RENDIDAS
//                        AND s.grupo_id <> 4       
//                        GROUP BY cis.`servicio_id`, cis.`comprobante_ingreso_id`, p.`estado_id`) AS result
//                        GROUP BY result.numero, result.piezas_por_estado WITH ROLLUP";
            //echo $consulta;die;
            $result_exe = $this->db->query($consulta);
            $results = $result_exe->result();
//            echo $cliente;die;
           $vista_interna = array(
                   'permisos_efectivos' => $this->permisos,
                   'cliente' => $cliente,
                   'cliente_id' => $cliente_id,
                   'periodo_desde' => $periodo_desde,
                   'periodo_hasta' => $periodo_hasta,
                   'results' => $results,
//                        'flag' => 'piezas'
           );
            $vista_externa = array(			
                   'title' => ucwords("Estados de piezas por clientes"),
                   'contenido_main' => $this->load->view('components/piezas/piezas/consulta_piezas_clientes', $vista_interna, true)
            );		

            $this->load->view('template/backend', $vista_externa);
	}
        
        public function exportarPiezasClientesAExcel(){
            set_time_limit(1800);
        $cliente_id = $this->input->post('excel_cliente_id');
        $periodo_desde = $this->input->post('excel_desde');
        $periodo_hasta = $this->input->post('excel_hasta');
        if ($this->ion_auth->isAdmin())
            {
                $clientes = Cliente::all();
                $sucursales = $this->flash_md->getSucursales();
            }
            else
            {
                $user_row = $this->ion_auth->user()->row();
                $clientes = $this->db
                                    ->select('c.*')
                                    ->from('flash_clientes c')
                                    ->join('flash_comprobantes_ingresos ci', 'c.id = ci.cliente_id')
                                    ->join('flash_piezas p', 'p.comprobante_ingreso_id = ci.id')
                                    ->join('flash_subpiezas sp', 'sp.pieza_id = p.id')
                                    ->join('flash_hojas_rutas hdr', 'sp.hoja_ruta_id = hdr.id')
                                    ->join('flash_sucursales_carteros sc', 'sc.id = hdr.cartero_id')
                                    ->join('flash_sucursales s', 's.id = sc.sucursal_id')
                                    ->where('s.id = ', $user_row->sucursal_id)
                                    ->group_by('c.id')
                                    ->get();
                $clientes = $clientes->result();
                $sucursales = Sucursal::whereId($user_row->sucursal_id)->get();
            }
            
            $where = "";
            $nexo = " WHERE ";
            if ($cliente_id > 0) {
                $where =  $nexo." c.id =  ".$cliente_id;
                $nexo = " AND ";
             }   
            if ($periodo_desde != "") {
                $periodo_desde = new DateTime($periodo_desde);
                $where .= $nexo." ci.create >= '".$periodo_desde->format('Y-m-d 00:00:00')."'";
                $nexo = " AND ";
             }   
            if ($periodo_hasta != "") {
                $periodo_hasta = new DateTime($periodo_hasta);
                $where .= $nexo." ci.create <= '".$periodo_hasta->format('Y-m-d 23:59:59')."'";
                $nexo = " AND ";
             }   

            $consulta = "SELECT result.numero, result.fecha_comprobante, result.servicio, result.fecha_rendicion, result.estado, result.piezas_por_estado, 
                                SUM(result.piezas_por_estado) AS piezas_con_estado, 
                                result.cantidad_total AS piezas_en_comprobante
                        FROM 
                        (SELECT ci.`numero`, DATE_FORMAT(ci.`create`,'%d-%m-%Y') fecha_comprobante,  s.`nombre` servicio, DATE_FORMAT(ci.`create`,'%d-%m-%Y') fecha_rendicion,ev.`nombre` estado, 
                                COUNT(*) AS piezas_por_estado , 
                                (SELECT COUNT(p.id) FROM flash_piezas p 
                                 WHERE p.`comprobante_ingreso_id` = ci.`id`  ) cantidad_total
                        FROM flash_piezas p
                        INNER JOIN flash_comprobantes_ingresos ci ON p.`comprobante_ingreso_id` = ci.`id`
                        INNER JOIN flash_comprobantes_ingresos_servicios cis ON p.`servicio_id` = cis.`id`
                        INNER JOIN flash_servicios s ON cis.`servicio_id` = s.`id`
                        INNER JOIN flash_piezas_estados_variables ev ON p.`estado_id` = ev.`id`
                        INNER JOIN flash_clientes c ON ci.`cliente_id` = c.`id`
                        INNER JOIN flash_rendiciones_piezas rp ON rp.pieza_id = p.id
                        INNER JOIN flash_rendiciones r ON r.`id` = rp.`rendicion_id`
                        $where
                        AND ev.`pieza_estado_id` IN (2) #EN RENDICION
                        AND p.id IN (SELECT pieza_id FROM flash_rendiciones_piezas p ) #Que esten RENDIDAS
                        AND s.grupo_id <> 4       
                        GROUP BY cis.`servicio_id`, cis.`comprobante_ingreso_id`, p.`estado_id`) AS result
                        GROUP BY result.numero, result.piezas_por_estado WITH ROLLUP";

            $result_exe = $this->db->query($consulta);
            $results = $result_exe->result();

            //load our new PHPExcel library
            $this->load->library('excel');
            //activate worksheet number 1
            $this->excel->setActiveSheetIndex(0);
            //name the worksheet
            $this->excel->getActiveSheet()->setTitle('Estados de Piezas por Cliente');
            //set cabeceras
            $this->excel->getActiveSheet()->setCellValue("A1",'Nro. Comp.');
            $this->excel->getActiveSheet()->setCellValue("B1",'Fecha Comp.');
            $this->excel->getActiveSheet()->setCellValue("C1",'Servicio.');
            $this->excel->getActiveSheet()->setCellValue("D1",'Fecha Rendición.');
            $this->excel->getActiveSheet()->setCellValue("E1",'Estado');
            $this->excel->getActiveSheet()->setCellValue("F1",'Piezas con Estado');
            $this->excel->getActiveSheet()->setCellValue("G1",'Piezas en Comprobante');
            // set cells values
            $this->load->library('simplehtmldom_1_5/simple_html_dom'); 
            $i = 2;
            foreach ($results as $value) {
                    // echo $trs->find('td',1)->innertext;
                if ($value->piezas_por_estado != NULL){
                    $this->excel->getActiveSheet()->setCellValue("A$i",$value->numero);
                    $this->excel->getActiveSheet()->setCellValue("B$i",$value->fecha_comprobante);
                    $this->excel->getActiveSheet()->setCellValue("C$i",$value->servicio);
                    $this->excel->getActiveSheet()->setCellValue("D$i",$value->fecha_rendicion);
                    $this->excel->getActiveSheet()->setCellValue("E$i",$value->estado);
                    $this->excel->getActiveSheet()->setCellValue("F$i",$value->piezas_por_estado);
                    $this->excel->getActiveSheet()->setCellValue("G$i",$value->piezas_estado);
                }else{
                    $this->excel->getActiveSheet()->setCellValue("A$i",$value->numero);
                    $this->excel->getActiveSheet()->setCellValue("B$i","");
                    $this->excel->getActiveSheet()->setCellValue("C$i","");
                    $this->excel->getActiveSheet()->setCellValue("D$i","");
                    $this->excel->getActiveSheet()->setCellValue("E$i","TOTAL: ");
                    $this->excel->getActiveSheet()->setCellValue("F$i",$value->piezas_con_estado);
                    $this->excel->getActiveSheet()->setCellValue("G$i",$value->piezas_en_comprobante);
                }
                    $i++;
            }
            
            $this->excel->setActiveSheetIndex(0);
            $filename='Consultas_estados_piezas_clientes.xls'; //save our workbook as this file name

            header('Content-Type: application/vnd.ms-excel'); //mime type

            header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name

            header('Cache-Control: max-age=0'); //no cache

            //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
            //if you want to save it as .XLSX Excel 2007 format

            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5'); 

            //force user to download the Excel file without writing it to server's HD
            $objWriter->save('php://output');
    }
    


       
        public function exportarPendientesXCarterosAExcel(){
            $fecha_ingreso = $this->input->post('fecha_ingreso');
            $pieza_id = $this->input->post('pieza_id');
            $barra_externa = $this->input->post('barra_externa');
            $comprobante = $this->input->post('comprobante');
            $cliente = $this->input->post('cliente');
            $servicio = $this->input->post('servicio');
            $cartero = $this->input->post('cartero');
            $hoja_ruta_id = $this->input->post('hoja_ruta_id');
            $despacho_id = $this->input->post('despacho_id');
            $sucursal = $this->input->post('sucursal');
            $estado = $this->input->post('estado');
            $destinatario = $this->input->post('destinatario');
            $domicilio = $this->input->post('domicilio');
            $codigo_postal = $this->input->post('codigo_postal');
            $localidad = $this->input->post('localidad');
            $fecha_cambio_estado = $this->input->post('fecha_cambio_estado');
            $visitas = $this->input->post('visitas');
            $rendicion_id = $this->input->post('rendicion_id');
            $recibio = $this->input->post('recibio');
            $documento = $this->input->post('documento');
            $datos_varios_1 = $this->input->post('datos_varios_1');
            $datos_varios_2 = $this->input->post('datos_varios_2');
            $datos_varios_3 = $this->input->post('datos_varios_3');

            $consultas_globales = $this->flash_md->getConsultasGlobales($fecha_ingreso, $pieza_id, $barra_externa,$comprobante,$cliente, $servicio, $cartero,
                                            $hoja_ruta_id,$despacho_id,$sucursal,$estado,$destinatario,$domicilio,$codigo_postal,$localidad,
                                            $fecha_cambio_estado,$visitas,$rendicion_id,$recibio,$documento,$datos_varios_1,$datos_varios_2,$datos_varios_3);
            //load our new PHPExcel library
            $this->load->library('excel');
            //activate worksheet number 1
            $this->excel->setActiveSheetIndex(0);
            //name the worksheet
            $this->excel->getActiveSheet()->setTitle('Consulta Global');
            //set cabeceras
            $this->excel->getActiveSheet()->setCellValue("A1",'Fecha Ingreso');
            $this->excel->getActiveSheet()->setCellValue("B1",'Pieza');
            $this->excel->getActiveSheet()->setCellValue("C1",'Codigo');
            $this->excel->getActiveSheet()->setCellValue("D1",'Comprobante');
            $this->excel->getActiveSheet()->setCellValue("E1",'Cliente');
            $this->excel->getActiveSheet()->setCellValue("F1",'Servicio');
            $this->excel->getActiveSheet()->setCellValue("G1",'Cartero');
            $this->excel->getActiveSheet()->setCellValue("H1",'Hoja de Ruta');
            $this->excel->getActiveSheet()->setCellValue("I1",'Despacho');
            $this->excel->getActiveSheet()->setCellValue("J1",'Sucursal');
            $this->excel->getActiveSheet()->setCellValue("K1",'Estado');
            $this->excel->getActiveSheet()->setCellValue("L1",'Destinatario');
            $this->excel->getActiveSheet()->setCellValue("M1",'Domicilio');
            $this->excel->getActiveSheet()->setCellValue("N1",'Cod. Postal');
            $this->excel->getActiveSheet()->setCellValue("O1",'Localidad');
            $this->excel->getActiveSheet()->setCellValue("P1",'Fecha cambio estado');
            $this->excel->getActiveSheet()->setCellValue("Q1",'Visitas');
            $this->excel->getActiveSheet()->setCellValue("R1",'Rendición');
            $this->excel->getActiveSheet()->setCellValue("S1",'Recibió');
            $this->excel->getActiveSheet()->setCellValue("T1",'Documento');
            $this->excel->getActiveSheet()->setCellValue("U1",'Datos Varios');
            $this->excel->getActiveSheet()->setCellValue("V1",'Datos Varios 1');
            $this->excel->getActiveSheet()->setCellValue("W1",'Datos Varios 2');
            // set cells values
            $this->load->library('simplehtmldom_1_5/simple_html_dom'); 
            $i = 2;
            foreach ($consultas_globales as $value) {
                    // echo $trs->find('td',1)->innertext;
                    $this->excel->getActiveSheet()->setCellValue("A$i",$value->fecha_ingreso);
                    $this->excel->getActiveSheet()->setCellValue("B$i",$value->pieza_id);
                    $this->excel->getActiveSheet()->setCellValue("C$i",$value->barra_externa);
                    $this->excel->getActiveSheet()->setCellValue("D$i",$value->comprobante);
                    $this->excel->getActiveSheet()->setCellValue("E$i",$value->cliente);
                    $this->excel->getActiveSheet()->setCellValue("F$i",$value->servicio);
                    $this->excel->getActiveSheet()->setCellValue("G$i",$value->cartero);
                    $this->excel->getActiveSheet()->setCellValue("H$i",$value->hoja_ruta_id);
                    $this->excel->getActiveSheet()->setCellValue("I$i",$value->despacho_id);
                    $this->excel->getActiveSheet()->setCellValue("J$i",$value->sucursal);
                    $this->excel->getActiveSheet()->setCellValue("K$i",$value->estado);
                    $this->excel->getActiveSheet()->setCellValue("L$i",$value->destinatario);
                    $this->excel->getActiveSheet()->setCellValue("M$i",$value->domicilio);
                    $this->excel->getActiveSheet()->setCellValue("N$i",$value->codigo_postal);
                    $this->excel->getActiveSheet()->setCellValue("O$i",$value->localidad);
                    $this->excel->getActiveSheet()->setCellValue("P$i",$value->fecha_cambio_estado);
                    $this->excel->getActiveSheet()->setCellValue("Q$i",$value->visitas);
                    $this->excel->getActiveSheet()->setCellValue("R$i",$value->rendicion_id);
                    $this->excel->getActiveSheet()->setCellValue("S$i",$value->recibio);
                    $this->excel->getActiveSheet()->setCellValue("T$i",$value->documento);
                    $this->excel->getActiveSheet()->setCellValue("U$i",$value->datos_varios);
                    $this->excel->getActiveSheet()->setCellValue("V$i",$value->datos_varios_1);
                    $this->excel->getActiveSheet()->setCellValue("W$i",$value->datos_varios_2);
                    $i++;
            }
            
            $this->excel->setActiveSheetIndex(0);
            $filename='Consultas_globales.xls'; //save our workbook as this file name

            header('Content-Type: application/vnd.ms-excel'); //mime type

            header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name

            header('Cache-Control: max-age=0'); //no cache

            //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
            //if you want to save it as .XLSX Excel 2007 format

            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5'); 

            //force user to download the Excel file without writing it to server's HD
            $objWriter->save('php://output');
    }
    
    function HDRXDistribuidor(){
            $vista_interna = array(
                    'permisos_efectivos' => $this->permisos,
                    //'sucursales' => $this->flash_md->getSucursales(),
                    //'comprobantes' => $this->flash_md->getComprobantesIngresos(),
                    'flag' => 'piezas'
            );

            $vista_externa = array(			
                    'title' => ucwords("Envíos por distribuidor"),
                    'contenido_main' => $this->load->view('components/hojas_rutas/consultas_hojas_rutas/hojas_rutas_por_distribuidor', $vista_interna, true)
            );		

            $this->load->view('template/backend', $vista_externa);
    }
    
    function exportarAPdf(){
        $hoja_ruta_id = $this->input->post('pdf_hoja_ruta_id');
        $auditoria = $this->input->post('pdf_auditoria');
        $hoja_ruta = $this->codegen_model->row('flash_hojas_rutas hdr','hdr.*, DATE_FORMAT(hdr.create, "%d/%m/%Y") hdr_create','hdr.id='.$hoja_ruta_id);
        if ($hoja_ruta->cartero_id != NULL) $this->exportarHDRCartero($hoja_ruta,$auditoria);
        if ($hoja_ruta->distribuidor_id != NULL) $this->exportarHDRDistribuidor($hoja_ruta);
    }
    
    function exportarHDRDistribuidor($hoja_ruta){
        $this->load->library('simplehtmldom_1_5/simple_html_dom'); 
        $this->load->library('Pdf_noheader_nofooter');
        $hdr_piezas = $this->flash_md->getPiezasHDRDistribuidor($hoja_ruta->id);
        //Set custom header data
        $custom_layout = array(220, 340);
        $pdf = new Pdf_noheader_nofooter('L', 'mm', $custom_layout, true, 'UTF-8', false); //: portrait, L:landscape
        
        $pdf->SetTitle('Hoja de Ruta a Distribuidor'); 
        $pdf->SetHeaderMargin(300); 
        $pdf->SetTopMargin(200); 
        $pdf->setFooterMargin(20); 
        $pdf->SetAutoPageBreak(true); 
        $pdf->SetAuthor('Author'); 
        $pdf->SetDisplayMode('real', 'default'); 
        $pdf->Write(5, 'CodeIgniter TCPDF Integration'); 
        
            // set header and footer fonts
            $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
            $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

            // set default monospaced font
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

            // set margins
            $pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT);
            $pdf->SetHeaderMargin(0);
           // $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

            // set auto page breaks
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

            // set image scale factor
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

            // set some language-dependent strings (optional)
            if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
                require_once(dirname(__FILE__).'/lang/eng.php');
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
            $pdf->AddPage('L',$custom_layout);

            $titulo = '<table border="0" cellspacing="6" cellpadding="4" style="margin-bottom:20px">
                            <tr>
                                <td style="font-size:20px;font-style: italic;font-weight:bold">Hoja de Ruta a Distribuidor</td>
                            </tr>
                            <tr>
                                <td style="font-size:11px">Número de Hoja de Ruta: '.$hoja_ruta->id.'</td>
                                
                            </tr>
                            <tr>
                                <td style="font-size:11px">Distribuidor: '.$hdr_piezas[0]->distribuidor.'</td>
                            </tr>
                        </table>';
            
            $html = '
            <table style="border-bottom-width: 1px solid;" width="100%">
                <tr>
                    <th>Comp. Ingreso</th>
                    <th>Servicio</th>
                    <th>Código</th>
                    <th>Destinatario</th>
                    <th>Domicilio</th>
                    <th>Localidad</th>
                    <th>Código Postal</th>
                    <th>Cantidad</th>
                </tr>
            </table>
            <table style="padding-top:10px" > ';
            $table_contenido = "";
            $total = 0;
            foreach($hdr_piezas as $piezas){
               $total += doubleval($piezas->cantidad);
               $table_contenido  .= '<tr>
                                        <td>'.$piezas->comprobante_ingreso.'</td>
                                        <td>'.$piezas->servicio.'</td>
                                        <td>'.$piezas->barcode_externo.'</td>
                                        <td>'.$piezas->destinatario.'</td>
                                        <td>'.$piezas->domicilio.'</td>
                                        <td>'.$piezas->localidad.'</td>
                                        <td>'.$piezas->codigo_postal.'</td>
                                        <td>'.$piezas->cantidad.'</td>
                                    </tr>';
            }
            $cierre_table = '</table>';   
            $pdf->writeHTML($titulo.$html.$table_contenido.$cierre_table, true, false, true, false, '');
            $fin_pagina = '
                            <table border="0" cellspacing="6" cellpadding="4" style="margin-bottom:20px">
                                <tr>
                                     <td style="font-size:11px;font-weight:bold;text-align:left">Total de piezas: '.$total.'</td>
                                </tr>
                                <tr>
                                    <td style="font-size:11px;font-weight:bold;text-align:left">'.$hoja_ruta->observaciones.'</td>
                                </tr>
                                <tr>
                                    <td style="font-size:11px;font-style: italic;font-weight:bold;text-align:left">.........................<br/>FIRMA</td>
                                </tr>
                            </table>';
            $pdf->writeHTML($fin_pagina, true, false, true, false, '');
            $pdf->SetY(-30);
            $pdf->writeHTMLCell(0, 2, '', '', "<hr>", 0, false);
            setlocale(LC_ALL,"es_ES");
            $user_row = $this->ion_auth->user()->row();
            $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
            $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
//            echo $dias[date('w')]." ".date('d')." de ".$meses[date('n')-1]. " del ".date('Y')."<br/>" ;
            $pdf->SetY(-29);
            $pdf->Cell(0, 2, $dias[date('w')].", ".date('d')." de ".$meses[date('n')-1]. " del ".date('Y')." - ".$user_row->apellido.', '.$user_row->nombre, 0, false, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Output('Hojas_Rutas_Distribuidor.pdf', 'D');
    }
    
    function exportarHDRCartero($hoja_ruta,$auditoria){
        $this->load->library('simplehtmldom_1_5/simple_html_dom'); 
        $this->load->library('Pdf_noheader_nofooter');
        $hdr_piezas_normales = $this->flash_md->getPiezasNormalesHDRCarteros($hoja_ruta->id);
        $hdr_piezas_simples = $this->flash_md->getPiezasSimplesHDRCarteros($hoja_ruta->id);
        //Set custom header data
        $custom_layout = array(220, 340);
        $pdf = new Pdf_noheader_nofooter('P', 'mm', $custom_layout, true, 'UTF-8', false); //: portrait, L:landscape
        
        $pdf->SetTitle('Hoja de Ruta a Carteros'); 
        $pdf->SetHeaderMargin(300); 
        $pdf->SetTopMargin(200); 
        $pdf->setFooterMargin(20); 
        $pdf->SetAutoPageBreak(true); 
        $pdf->SetAuthor('Author'); 
        $pdf->SetDisplayMode('real', 'default'); 
        $pdf->Write(5, 'CodeIgniter TCPDF Integration'); 
        
            // set header and footer fonts
            $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
            $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

            // set default monospaced font
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

            // set margins
            $pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT);
            $pdf->SetHeaderMargin(0);
            $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

            // set auto page breaks
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

            // set image scale factor
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

            // set some language-dependent strings (optional)
            if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
                require_once(dirname(__FILE__).'/lang/eng.php');
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
            $pdf->AddPage('P',$custom_layout);
            $cartero_id = $hdr_piezas_normales[0]->cartero_id != ''?$hdr_piezas_normales[0]->cartero_id:$hdr_piezas_simples[0]->cartero_id ;
            $cartero_nombre = $hdr_piezas_normales[0]->cartero != ''?$hdr_piezas_normales[0]->cartero:$hdr_piezas_simples[0]->cartero ;
            $titulo = '<table border="0" cellspacing="6" cellpadding="4" style="margin-bottom:20px">
                            <tr>
                                <td style="font-size:20px;font-style: italic;font-weight:bold" colspan="2">Hoja de Ruta a Cartero</td>
                            </tr>
                            <tr>
                                <td style="font-size:11px" colspan="2">Número de Hoja de Ruta: '.$hoja_ruta->id.'</td>
                            </tr>
                            <tr>
                                <td style="font-size:11px">Fecha</td>
                                <td style="font-size:11px">Cartero:  '.$cartero_id.' - '.$cartero_nombre.'</td>
                            </tr>
                            <tr>
                                <td style="font-size:11px" colspan="2">piezas con Datos</td>
                            </tr>
                        </table>';
            
            $tabla_normales = '
            <table style="border-bottom-width: 1px solid;" width="100%">
                <tr>
                    <th>Comp. Ingreso</th>
                    <th>Servicio</th>
                    <th>Fecha C.I.</th>
                    <th style="text-align:right">Cantidad</th>
                    <th style="text-align:right">Devoluciones</th>
                </tr>
            </table>
            <table style="padding-top:10px" > ';
            $tabla_normales_contenido = "";
            $total = 0;
            foreach($hdr_piezas_normales as $piezas){
               $total += doubleval($piezas->cantidad);
               $tabla_normales_contenido  .= '<tr>
                                                <td>'.$piezas->comprobante_ingreso.'</td>
                                                <td>'.$piezas->servicio.'</td>
                                                <td>'.$piezas->fecha_comprobante.'</td>
                                                <td style="text-align:right">'.$piezas->cantidad.'</td>
                                                <td></td>
                                            </tr>';
            }
            $cierre_tabla_normales = '  <tr>
                                            <td></td>
                                            <td></td>
                                            <td style="text-align:right">Total</td>
                                            <td style="text-align:right">'.$total.'</td>
                                            <td></td>
                                        </tr>
                                    </table>';   
            $pdf->writeHTML($titulo.$tabla_normales.$tabla_normales_contenido.$cierre_tabla_normales, true, false, true, false, '');
            
            $tabla_simples = '
                <table border="0" cellspacing="6" cellpadding="4" style="margin-bottom:20px">
                    <tr>
                        <td style="font-size:11px" colspan="2">piezas sin Datos</td>
                    </tr>
                </table>
                <table style="border-bottom-width: 1px solid;" width="100%">
                    <tr>
                        <th>Comp. Ingreso</th>
                        <th>Servicio</th>
                        <th>Fecha C.I.</th>
                        <th style="text-align:right">Cantidad</th>
                        <th style="text-align:right">Devoluciones</th>
                    </tr>
                </table>
                <table style="padding-top:10px" > ';
            $tabla_simples_contenido = "";
            $total = 0;
            foreach($hdr_piezas_simples as $piezas){
               $total += doubleval($piezas->cantidad);
               $tabla_simples_contenido  .= '<tr>
                                                <td>'.$piezas->comprobante_ingreso.'</td>
                                                <td>'.$piezas->servicio.'</td>
                                                <td>'.$piezas->fecha_comprobante.'</td>
                                                <td style="text-align:right">'.$piezas->cantidad.'</td>
                                                <td></td>
                                            </tr>';
            }
            $cierre_tabla_simples = '  <tr>
                                            <td></td>
                                            <td></td>
                                            <td style="text-align:right">Total</td>
                                            <td style="text-align:right">'.$total.'</td>
                                            <td></td>
                                        </tr>
                                    </table>';   
            $pdf->writeHTML($tabla_simples.$tabla_simples_contenido.$cierre_tabla_simples, true, false, true, false, '');
            $user_row = $this->ion_auth->user()->row();
            $fin_pagina = '
                            <table border="0" cellspacing="6" cellpadding="4" style="margin-bottom:20px">
                                <tr>
                                     <td style="font-size:11px;text-align:left">Firma Cartero</td>
                                     <td style="font-size:11px;text-align:left">Aclaración Firma</td>
                                </tr>
                                <tr>
                                    <td style="font-size:11px;text-align:left" colspan="2">Sale a reparto el día: '.  date('d/m/Y').'</td>
                                </tr>
                                <tr>
                                    <td style="font-size:11px;text-align:left">Fecha creación hoja ('.$hoja_ruta->hdr_create.')</td>
                                    <td style="font-size:11px;text-align:left">'.$user_row->id.' - '.$user_row->apellido.', '.$user_row->nombre.'</td>
                                </tr>
                            </table>';
            $pdf->SetY(-50);
            $pdf->writeHTML($fin_pagina, true, false, true, false, '');
            if($auditoria == 'true'){
                $pdf->AddPage('P',$custom_layout);
                $titulo = ' <table border="0" cellspacing="6" cellpadding="4" style="margin-bottom:20px">
                                <tr>
                                    <td style="font-size:11px;">Hoja de Ruta: <br/>Nº'.$hoja_ruta->id.'</td>
                                    <td style="font-size:11px">Recorrido <br/>'.$hdr_piezas_normales->zona.'</td>
                                    <td style="font-size:11px">Cartero:  '.$hdr_piezas_normales[0]->cartero_id.' - '.$hdr_piezas_normales[0]->cartero.'</td>
                                </tr>
                                <tr>
                                    <td style="text-align:right" colspan="3">Firma/Aclaración/Doc B.P. - Buzón Vínculos</td>
                                </tr>
                            </table>';
                $dateTime = new DateTime('now', new DateTimeZone('America/Argentina/Buenos_Aires')); 
                $tabla_auditoria = '
                <table style="border-bottom-width: 1px solid;" width="100%">
                    <tr>
                        <th colspan="5">'.$dateTime->format("H:i:s A").' Tipo/Num/Fec. Emi.  Referencia Laterales</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </table>
                <table style="padding-top:10px" > ';
                $tabla_auditoria_contenido = "";
                $total = 0;
                $hdr_piezas_normales_auditoria = $this->flash_md->getPiezasNormalesHDRCarterosAuditoria($hoja_ruta->id);
                foreach($hdr_piezas_normales_auditoria as $piezas){
                   $tabla_auditoria_contenido  .= '<tr>
                                                    <td>'.$piezas->destinatario.'<br/>'.$piezas->domicilio.'<br/>'.$piezas->codigo_postal.' - '.$piezas->localidad.'</td>
                                                    <td>'.$piezas->servicio.'<br/>'.$piezas->barcode_externo.' / '.$piezas->pieza_id.'<br/>'.$piezas->cliente.'</td>
                                                </tr>
                                                <hr>';
                }
                $cierre_auditoria_normales = '</table>';   
                $pdf->writeHTML($titulo.$tabla_auditoria.$tabla_auditoria_contenido.$cierre_auditoria_normales, true, false, true, false, '');
            }
            $pdf->Output('Hojas_Rutas_Carteros.pdf', 'D');
//            $url = base_url('hojas_rutas/hojas_rutas');
//            header('Location: '.$url);
    }
 
    public function exportarHDRDistribuidorAExcel(){
        
            $desde = $this->input->post('excel_desde')!=''?$this->input->post('excel_desde'):NULL;
            $hasta = $this->input->post('excel_hasta')!=''?$this->input->post('excel_hasta'):NULL;
           
            $hojas_rutas = $this->flash_md->getHDRXDistribuidor($desde,$hasta);

            //load our new PHPExcel library
            $this->load->library('excel');
            //activate worksheet number 1
            $this->excel->setActiveSheetIndex(0);
            //name the worksheet
            $this->excel->getActiveSheet()->setTitle('HDR por Distribuidor');
            //set cabeceras
            $this->excel->getActiveSheet()->setCellValue("A1",'Nro Hoja de Ruta');
            $this->excel->getActiveSheet()->setCellValue("B1",'Distribuidor');
            $this->excel->getActiveSheet()->setCellValue("C1",'Fecha creación');
            $this->excel->getActiveSheet()->setCellValue("D1",'Cliente');
            $this->excel->getActiveSheet()->setCellValue("E1",'Servicio');
            $this->excel->getActiveSheet()->setCellValue("F1",'Sucursal');
            $this->excel->getActiveSheet()->setCellValue("G1",'Cantidad');
            // set cells values
            $this->load->library('simplehtmldom_1_5/simple_html_dom'); 
            $i = 2;
            foreach ($hojas_rutas as $value) {
                    // echo $trs->find('td',1)->innertext;
                    $this->excel->getActiveSheet()->setCellValue("A$i",$value->hoja_ruta_id);
                    $this->excel->getActiveSheet()->setCellValue("B$i",$value->apellido_nombre);
                    $this->excel->getActiveSheet()->setCellValue("C$i",$value->fecha_creacion);
                    $this->excel->getActiveSheet()->setCellValue("D$i",$value->cliente);
                    $this->excel->getActiveSheet()->setCellValue("E$i",$value->servicio);
                    $this->excel->getActiveSheet()->setCellValue("F$i",$value->sucursal);
                    $this->excel->getActiveSheet()->setCellValue("G$i",$value->cantidad);
                    $i++;
            }
            
            $this->excel->setActiveSheetIndex(0);
            $filename='Hojas_Rutas_Distribuidor.xls'; //save our workbook as this file name

            header('Content-Type: application/vnd.ms-excel'); //mime type

            header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name

            header('Cache-Control: max-age=0'); //no cache

            //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
            //if you want to save it as .XLSX Excel 2007 format

            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5'); 

            //force user to download the Excel file without writing it to server's HD
            $objWriter->save('php://output');
    }
    
    function hdr_cartero_distribuidor(){
            if ($this->ion_auth->isAdmin())
            {
                $sucursales = $this->flash_md->getSucursales();
            }
            else
            {
                $user_row = $this->ion_auth->user()->row();
                $sucursales = Sucursal::whereId($user_row->sucursal_id)->get();
            }
            
            $vista_interna = array(
                    'permisos_efectivos' => $this->permisos,
                    'sucursales' => $sucursales,
                    'results' => []
            );

            $vista_externa = array(			
                    'title' => ucwords("Envios por carteros"),
                    'contenido_main' => $this->load->view('components/hojas_rutas/consultas_hojas_rutas/hojas_rutas_carteros_distribuidores', $vista_interna, true)
            );		

            $this->load->view('template/backend', $vista_externa);
    }
    
    function hdr_cartero_distribuidor_filtro(){
        $periodo_desde = $this->input->post('periodo_desde') != ""?$this->input->post('periodo_desde'):false;
        $periodo_hasta = $this->input->post('periodo_hasta') != ""?$this->input->post('periodo_hasta'):false;
        $sucursal = $this->input->post('sucursal_id');

        if ($this->ion_auth->isAdmin())
        {
            $sucursales = $this->flash_md->getSucursales();
        }
        else
        {
            $user_row = $this->ion_auth->user()->row();
            $sucursales = Sucursal::whereId($user_row->sucursal_id)->get();
        }
        
        $vista_interna = array(
            'permisos_efectivos' => $this->permisos,
            'results' => $this->flash_md->hdr_cartero_distribuidor($periodo_desde,$periodo_hasta,$sucursal),
            'periodo_desde' => $periodo_desde,
            'periodo_hasta' => $periodo_hasta,
            'sucursales' => $sucursales,
            'sucursal_id' => $sucursal,
        );

        $vista_externa = array(			
                'title' => ucwords("Envios por carteros"),
                'contenido_main' => $this->load->view('components/hojas_rutas/consultas_hojas_rutas/hojas_rutas_carteros_distribuidores', $vista_interna, true)
        );		

        $this->load->view('template/backend', $vista_externa);
    }
    
    public function exportar_hoja_de_ruta_carteros(){
            $periodo_desde = $this->input->post('excel_desde') != ""?$this->input->post('excel_desde'):false;
            $periodo_hasta = $this->input->post('excel_hasta') != ""?$this->input->post('excel_hasta'):false;
            $sucursal = $this->input->post('excel_sucursal_id');
            $sucursal_nombre = Sucursal::whereId($sucursal)->first();

            $hojas_rutas = $this->flash_md->hdr_cartero_distribuidor($periodo_desde,$periodo_hasta,$sucursal);

            //load our new PHPExcel library
            $this->load->library('excel');
            //activate worksheet number 1
            $this->excel->setActiveSheetIndex(0);
            //name the worksheet
            $this->excel->getActiveSheet()->setTitle('HDR por Carteros');
            //set cabeceras
            $this->excel->getActiveSheet()->setCellValue("A1",'Nro Hoja de Ruta');
            $this->excel->getActiveSheet()->setCellValue("B1",'Fecha Entrega');
            $this->excel->getActiveSheet()->setCellValue("C1",'Cartero');
            $this->excel->getActiveSheet()->setCellValue("D1",'Servicio');
            $this->excel->getActiveSheet()->setCellValue("E1",'Piezas');
            $this->excel->getActiveSheet()->setCellValue("F1",'Fecha baja');
            $this->excel->getActiveSheet()->setCellValue("G1",'Sucursal');
            // set cells values
            $this->load->library('simplehtmldom_1_5/simple_html_dom'); 
            $i = 2;
            foreach ($hojas_rutas as $value) {
                    // echo $trs->find('td',1)->innertext;
                    $this->excel->getActiveSheet()->setCellValue("A$i",$value->hdr_id);
                    $this->excel->getActiveSheet()->setCellValue("B$i",$value->fecha_entrega);
                    $this->excel->getActiveSheet()->setCellValue("C$i",$value->cartero);
                    $this->excel->getActiveSheet()->setCellValue("D$i",$value->servicio);
                    $this->excel->getActiveSheet()->setCellValue("E$i",$value->piezas);
                    $this->excel->getActiveSheet()->setCellValue("F$i",$value->fecha_baja);
                    $this->excel->getActiveSheet()->setCellValue("G$i",$sucursal_nombre->nombre);
                    $i++;
            }
            
            $this->excel->setActiveSheetIndex(0);
            $filename='Hojas_Rutas_Distribuidor.xls'; //save our workbook as this file name

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

/* End of file piezas.php */
/* Location: ./system/application/controllers/piezas.php */