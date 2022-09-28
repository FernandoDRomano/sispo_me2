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
            $this->excel->getActiveSheet()->setCellValue("F1",'Piezas');
            $this->excel->getActiveSheet()->setCellValue("G1",'Rendidas');
            $this->excel->getActiveSheet()->setCellValue("H1",'Pendientes');
            $this->excel->getActiveSheet()->setCellValue("I1",'Fecha Ult. Rendición');
            // set cells values
            $this->load->library('simplehtmldom_1_5/simple_html_dom'); 
            $i = 2;
            foreach ($results as $value) {
                    if (intval($value->pendientes) > intval(0)){
                        // echo $trs->find('td',1)->innertext;
                        $this->excel->getActiveSheet()->setCellValue("A$i",$value->sucursal);
                        $this->excel->getActiveSheet()->setCellValue("B$i",$value->cliente);
                        $this->excel->getActiveSheet()->setCellValue("C$i",$value->servicio);
                        $this->excel->getActiveSheet()->setCellValue("D$i",$value->numero);
                        $this->excel->getActiveSheet()->setCellValue("E$i",$value->fecha_pedido);
                        $this->excel->getActiveSheet()->setCellValue("F$i",$value->cantidad);
                        $this->excel->getActiveSheet()->setCellValue("G$i",$value->rendidas);
                        $this->excel->getActiveSheet()->setCellValue("H$i",$value->pendientes);
                        $this->excel->getActiveSheet()->setCellValue("I$i",$value->ult_fecha_rendicion);
                        $i++;
                    }
            }
            
            $this->excel->setActiveSheetIndex(0);
            $filename='Listado_Facturacion_por_Sucursal.xls'; //save our workbook as this file name

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