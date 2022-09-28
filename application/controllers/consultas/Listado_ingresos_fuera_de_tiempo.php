<?php

class Listado_ingresos_fuera_de_tiempo extends CI_Controller {

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
                    'title' => ucwords("Listados de Ingresos Fuera de Tiempo"),
                    'contenido_main' => $this->load->view('components/piezas/consultas_globales/listado_ingresos_fuera_de_tiempo', $vista_interna, true)
            );		

            $this->load->view('template/backend', $vista_externa);
	}
        
        function filter(){
            $dias = $this->input->post('dias');
            $query_periodo_hasta =   'SELECT DATE_SUB(NOW(), INTERVAL '.$dias.' DAY) dias ';
            //$date_hasta = $this->db->result($query_periodo_hasta
            $date_hasta = $this->db->query($query_periodo_hasta)->result();
            //var_dump($_POST);die;
            $query_ingresos = $this->db
                        ->select('  suc.nombre sucursal, c.nombre cliente, s.nombre servicio,
                                    ci.numero, ci.fecha_pedido,ci.cantidad total_comprobante ,
                                    cis.cantidad cantidad_servicio,
                                    SUM((SELECT COUNT(pie.id) 
                                            FROM flash_piezas pie 
                                            INNER JOIN flash_rendiciones_piezas rp ON rp.pieza_id = pie.id
                                            WHERE  pie.comprobante_ingreso_id = ci.id
                                            AND rp.pieza_id = pie.id
                                            AND pie.servicio_id = cis.id
                                             )) piezas_rendidas,
                                    SUM((SELECT COUNT(pie.id) 
                                            FROM flash_piezas pie 
                                            LEFT JOIN flash_rendiciones_piezas rp ON rp.pieza_id = pie.id
                                            WHERE  pie.comprobante_ingreso_id = ci.id
                                            AND rp.pieza_id IS NULL
                                            AND pie.servicio_id = cis.id
                                             )) piezas_pendientes,
                                    ((SELECT date_format(MAX(r.create),"%d-%m-%Y") 
                                            FROM flash_piezas pie 
                                            INNER JOIN flash_rendiciones_piezas rp ON rp.pieza_id = pie.id
                                            INNER JOIN flash_rendiciones r ON rp.rendicion_id = r.id
                                            WHERE  pie.comprobante_ingreso_id = ci.id
                                            AND rp.pieza_id = pie.id
                                            AND pie.servicio_id = cis.id
                                             )) fecha_ultima_rendicion,
                                             ci.sucursal_id, ci.cliente_id, cis.servicio_id')
                        ->join('flash_comprobantes_ingresos_servicios cis', 'ci.id = cis.comprobante_ingreso_id')
                        ->join('flash_sucursales suc', 'ci.sucursal_id = suc.id')
                        ->join('flash_clientes c', 'ci.cliente_id = c.id')
                        ->join('flash_servicios s', 'cis.servicio_id = s.id')
                        ->where('ci.create <= ', $date_hasta[0]->dias)
                        ->where('s.grupo_id <> 4')
                        ->group_by('cis.id, ci.id'/*'ci.sucursal_id, ci.id, s.id'*/)
                        ->order_by('suc.nombre, ci.numero, ci.fecha_pedido')
                        ->having(' piezas_pendientes > 0')
                        ->get('flash_comprobantes_ingresos ci');
               // echo($this->db->last_query());die;
                $results = $query_ingresos->result();

                 $vista_interna = array(
                                        'permisos_efectivos' => $this->permisos,
                                        'results' => $results,
                                        'filtro' => $dias
                                    );
                 $vista_externa = array(			
                    'title' => ucwords("Listados de Ingresos Fuera de Tiempo"),
                    'contenido_main' => $this->load->view('components/piezas/consultas_globales/listado_ingresos_fuera_de_tiempo', $vista_interna, true)
                );		
                $this->load->view('template/backend', $vista_externa);
        }

        public function exportarAExcel(){
            $dias = $this->input->post('excel_dias') != ""?$this->input->post('excel_dias'):false;

            $results = $this->flash_md->getListadoIngresosFueraTiempo($dias);

            //load our new PHPExcel library
            $this->load->library('excel');
            //activate worksheet number 1
            $this->excel->setActiveSheetIndex(0);
            //name the worksheet
            $this->excel->getActiveSheet()->setTitle('Rendiciones');
            //set cabeceras
            $this->excel->getActiveSheet()->setCellValue("A1",'Sucursal');
            $this->excel->getActiveSheet()->setCellValue("B1",'Cliente');
            $this->excel->getActiveSheet()->setCellValue("C1",'Servicio');
            $this->excel->getActiveSheet()->setCellValue("D1",'Comp. Ing.');
            $this->excel->getActiveSheet()->setCellValue("E1",'Fecha Ing.');
            $this->excel->getActiveSheet()->setCellValue("F1",'Piezas en CI');
            $this->excel->getActiveSheet()->setCellValue("G1",'Piezas en Servicio');
            $this->excel->getActiveSheet()->setCellValue("H1",'Rendidas');
            $this->excel->getActiveSheet()->setCellValue("I1",'Pendientes');
            $this->excel->getActiveSheet()->setCellValue("J1",'Fecha Ult. Rendición');
            // set cells values
            $this->load->library('simplehtmldom_1_5/simple_html_dom'); 
            $i = 2;
            foreach ($results as $value) {
                    
                        // echo $trs->find('td',1)->innertext;
                        $this->excel->getActiveSheet()->setCellValue("A$i",$value->sucursal);
                        $this->excel->getActiveSheet()->setCellValue("B$i",$value->cliente);
                        $this->excel->getActiveSheet()->setCellValue("C$i",$value->servicio);
                        $this->excel->getActiveSheet()->setCellValue("D$i",$value->numero);
                        $this->excel->getActiveSheet()->setCellValue("E$i",$value->fecha_pedido);
                        $this->excel->getActiveSheet()->setCellValue("F$i",$value->total_comprobante);
                        $this->excel->getActiveSheet()->setCellValue("G$i",$value->cantidad_servicio);
                        $this->excel->getActiveSheet()->setCellValue("H$i",$value->piezas_rendidas);
                        $this->excel->getActiveSheet()->setCellValue("I$i",$value->piezas_pendientes);
                        $this->excel->getActiveSheet()->setCellValue("J$i",$value->fecha_ultima_rendicion);
                        $i++;
                    
            }
            
            $this->excel->setActiveSheetIndex(0);
            $filename='Tiempo_de_rendicion_excedido.xls'; //save our workbook as this file name

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