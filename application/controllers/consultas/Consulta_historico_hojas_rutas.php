<?php

class Consulta_historico_hojas_rutas extends CI_Controller {

	private $permisos;
	
	function __construct() {
		parent::__construct();
		$this->permisos = $this->permisos_lib->control();
	}	
	
        function index(){
            
            $vista_interna = array(
                    'permisos_efectivos' => $this->permisos,
                    'results' => [],
            );

            $vista_externa = array(			
                    'title' => ucwords("Consulta de piezas en Hojas de Ruta"),
                    'contenido_main' => $this->load->view('components/hojas_rutas/hojas_rutas/hojas_rutas_historico', $vista_interna, true)
            );

            $this->load->view('template/backend', $vista_externa);
	}

        public function filtro()
        {
            $buscar_por = $this->input->post('buscar_por');
            $filtro = "";
            if ($buscar_por == 1){ //pieza_id
                $filtro = " WHERE sp.pieza_id = ".$this->input->post('codigo_barra');
            }else{
                if ($buscar_por == 2){ //codigo_externo
                    $query_get_pieza_id = " SELECT id pieza_id FROM flash_piezas WHERE barcode_externo = ".'"'.$this->input->post('codigo_barra').'"';
                    $pieza = $this->db->query($query_get_pieza_id)->row();
                    $filtro = " WHERE sp.pieza_id = ".$pieza->pieza_id;
                }else{
                    if ($buscar_por == 3){ //numero_comprobante codigo_barra_simple
                        
                    }
                }
            }
            //Query de subpiezas con pieza_estado_id
            $query = "  SELECT sp.pieza_id,hdr.id hdr_id,hdr.sucursal_id, hdr.cartero_id, hdr.transporte_id,
                                date_format(hdr.fecha_entrega,'%d-%m-%Y') fecha, sp.create, hdr.estado hdr_estado_id, ev.nombre estado, e.nombre estado_categoria
                        FROM flash_hojas_rutas hdr
                        INNER JOIN flash_subpiezas sp ON hdr.id = sp.hoja_ruta_id
                        INNER JOIN flash_piezas p ON sp.pieza_id = p.id
                        LEFT JOIN flash_piezas_estados_variables ev ON sp.pieza_estado_id = ev.id
                        LEFT JOIN flash_piezas_estados e ON ev.pieza_estado_id = e.id
                        ".$filtro;

            $resultados = $this->db->query($query)->result();
            if (count($resultados) == 0){
                $query = "  SELECT sp.pieza_id,hdr.id hdr_id,hdr.sucursal_id, hdr.cartero_id, hdr.transporte_id,
                                    date_format(hdr.fecha_entrega,'%d-%m-%Y') fecha, sp.create, hdr.estado hdr_estado_id, ev.nombre estado, e.nombre estado_categoria
                            FROM flash_hojas_rutas hdr
                            INNER JOIN flash_subpiezas sp ON hdr.id = sp.hoja_ruta_id
                            INNER JOIN flash_piezas p ON sp.pieza_id = p.id
                            INNER JOIN flash_piezas_estados_variables ev ON p.estado_id = ev.id
                            INNER JOIN flash_piezas_estados e ON ev.pieza_estado_id = e.id
                            ".$filtro;
 
                $resultados = $this->db->query($query)->result();
            }
            // Esto queda para futuro si quieren hacer por CI
//            $query = "  SELECT ci.id, p.id, p.servicio_id, p.estado_id, sp.hoja_ruta_id, COUNT(*) cantidad, sp.create fecha  FROM flash_comprobantes_ingresos ci
//                        INNER JOIN flash_piezas p ON p.comprobante_ingreso_id = ci.id
//                        INNER JOIN flash_subpiezas sp ON p.id = sp.pieza_id
//                        WHERE ci.id = 179
//                        AND p.tipo_id = 1
//                        GROUP BY p.servicio_id, sp.hoja_ruta_id, sp.create
//                        ".$filtro;

            $vista_interna = array(
                    'permisos_efectivos' => $this->permisos,
                    'results' => $resultados,
                    'comprobante_ingreso' => $this->input->post('comprobante_ingreso'),
                    'pieza_id' => $this->input->post('codigo_barra'),
                    'buscar_por' => $this->input->post('buscar_por'),
            );
            
            $vista_externa = array(			
                    'title' => ucwords("Piezas asignadas a carteros"),
                    'contenido_main' => $this->load->view('components/hojas_rutas/hojas_rutas/hojas_rutas_historico', $vista_interna, TRUE)
                    );
            
            $this->load->view('template/backend', $vista_externa);
        }

        public function exportarPiezasXCarterosExcel(){
        
            $comprobante_numero = $this->input->post('excel_comprobante_numero')!=''?$this->input->post('excel_comprobante_numero'):NULL;
            $sucursal_id = $this->input->post('excel_sucursal_id')!=''?$this->input->post('excel_sucursal_id'):NULL;
            //var_dump($_POST);die;
            $hojas_rutas = $this->flash_md->getCantidadPiezasXHDRXCarteros($comprobante_numero,$sucursal_id);

            //load our new PHPExcel library
            $this->load->library('excel');
            //activate worksheet number 1
            $this->excel->setActiveSheetIndex(0);
            //name the worksheet
            $this->excel->getActiveSheet()->setTitle('Piezas Asignadas a Carteros');
            //set cabeceras
            $this->excel->getActiveSheet()->setCellValue("A1",'Sucursal Cartero');
            $this->excel->getActiveSheet()->setCellValue("B1",'Fecha HDR');
            $this->excel->getActiveSheet()->setCellValue("C1",'HDR');
            $this->excel->getActiveSheet()->setCellValue("D1",'Cartero');
            $this->excel->getActiveSheet()->setCellValue("E1",'Servicio');
            $this->excel->getActiveSheet()->setCellValue("F1",'C.I.');
            $this->excel->getActiveSheet()->setCellValue("G1",'Piezas');
            // set cells values
            $this->load->library('simplehtmldom_1_5/simple_html_dom'); 
            $i = 2;
            foreach ($hojas_rutas as $value) {
                    // echo $trs->find('td',1)->innertext;
                    $this->excel->getActiveSheet()->setCellValue("A$i",$value->sucursal_cartero);
                    $this->excel->getActiveSheet()->setCellValue("B$i",$value->fecha_hdr);
                    $this->excel->getActiveSheet()->setCellValue("C$i",$value->hdr_id);
                    $this->excel->getActiveSheet()->setCellValue("D$i",$value->cartero);
                    $this->excel->getActiveSheet()->setCellValue("E$i",$value->servicio);
                    $this->excel->getActiveSheet()->setCellValue("F$i",$value->numero);
                    $this->excel->getActiveSheet()->setCellValue("G$i",$value->piezas);
                    $i++;
            }
            
            $this->excel->setActiveSheetIndex(0);
            $filename='Piezas_asignadas_a_carteros.xls'; //save our workbook as this file name

            header('Content-Type: application/vnd.ms-excel'); //mime type

            header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name

            header('Cache-Control: max-age=0'); //no cache

            //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
            //if you want to save it as .XLSX Excel 2007 format

            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5'); 

            //force user to download the Excel file without writing it to server's HD
            $objWriter->save('php://output');
    }

        public function exportarAExcel(){
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
            $vinculo = $this->input->post('vinculo');
            $datos_varios_1 = $this->input->post('datos_varios_1');
            $datos_varios_2 = $this->input->post('datos_varios_2');
            $datos_varios_3 = $this->input->post('datos_varios_3');

            $consultas_globales = $this->flash_md->getConsultasGlobales($fecha_ingreso, $pieza_id, $barra_externa,$comprobante,$cliente, $servicio, $cartero,
                                            $hoja_ruta_id,$despacho_id,$sucursal,$estado,$destinatario,$domicilio,$codigo_postal,$localidad,
                                            $fecha_cambio_estado,$visitas,$rendicion_id,$recibio,$documento,$vinculo,$datos_varios_1,$datos_varios_2,$datos_varios_3);
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
            $this->excel->getActiveSheet()->setCellValue("U1",'Vínculo');
            $this->excel->getActiveSheet()->setCellValue("V1",'Datos Varios');
            $this->excel->getActiveSheet()->setCellValue("W1",'Datos Varios 1');
            $this->excel->getActiveSheet()->setCellValue("X1",'Datos Varios 2');
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
                    $this->excel->getActiveSheet()->setCellValue("U$i",$value->vinculo);
                    $this->excel->getActiveSheet()->setCellValue("V$i",$value->datos_varios);
                    $this->excel->getActiveSheet()->setCellValue("W$i",$value->datos_varios_1);
                    $this->excel->getActiveSheet()->setCellValue("X$i",$value->datos_varios_2);
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
}

/* End of file piezas.php */
/* Location: ./system/application/controllers/piezas.php */