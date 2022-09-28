<?php

class Listado_vtas_clientes extends CI_Controller {

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
                    'title' => ucwords("Listados de Ventas por Clientes"),
                    'contenido_main' => $this->load->view('components/piezas/consultas_globales/listado_vtas_clientes', $vista_interna, true)
            );		

            $this->load->view('template/backend', $vista_externa);
	}

        public function exportarAExcel(){
            $periodo_desde = $this->input->post('excel_desde') != ""?$this->input->post('excel_desde'):false;
            $periodo_hasta = $this->input->post('excel_hasta') != ""?$this->input->post('excel_hasta'):false;

            $results = $this->flash_md->getListadoVtasClientes($periodo_desde,$periodo_hasta);

            //load our new PHPExcel library
            $this->load->library('excel');
            //activate worksheet number 1
            $this->excel->setActiveSheetIndex(0);
            //name the worksheet
            $this->excel->getActiveSheet()->setTitle('Listado Ventas por Cliente');
            //set cabeceras
            $this->excel->getActiveSheet()->setCellValue("A1",'MES');
            $this->excel->getActiveSheet()->setCellValue("B1",'VENTAS');
            $this->excel->getActiveSheet()->mergeCells("B1:D1");
            $this->excel->getActiveSheet()->setCellValue("A2",'Clientes');
            $this->excel->getActiveSheet()->setCellValue("B2",'Piezas');
            $this->excel->getActiveSheet()->setCellValue("C2",'Ventas');
            $this->excel->getActiveSheet()->setCellValue("D2",'Precio Promedio');
            // set cells values
            $this->load->library('simplehtmldom_1_5/simple_html_dom'); 
            $i = 3;
            $suma_col_1 = 0;
            $suma_col_2 = 0;
            $suma_col_3 = 0;
            foreach ($results as $value) {
                // echo $trs->find('td',1)->innertext;
                $suma_col_1 += $value->cantidad;
                $suma_col_2 += $value->ventas;
                $suma_col_3 += $value->precio_promedio;
                $this->excel->getActiveSheet()->setCellValue("A$i",$value->cliente);
                $this->excel->getActiveSheet()->setCellValue("B$i",$value->cantidad);
                $this->excel->getActiveSheet()->setCellValue("C$i",$value->ventas);
                $this->excel->getActiveSheet()->setCellValue("D$i",$value->precio_promedio);
                $i++;
            }
            $this->excel->getActiveSheet()->setCellValue("A$i","Totales");
            $this->excel->getActiveSheet()->setCellValue("B$i",$suma_col_1);
            $this->excel->getActiveSheet()->setCellValue("C$i",$suma_col_2);
            $this->excel->getActiveSheet()->setCellValue("D$i",$suma_col_3);
            $this->excel->setActiveSheetIndex(0);
            $filename='Listado_Ventas_por_Cliente.xls'; //save our workbook as this file name

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