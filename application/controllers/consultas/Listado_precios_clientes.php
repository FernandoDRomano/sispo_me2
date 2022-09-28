<?php



class Listado_precios_clientes extends CI_Controller {



	private $permisos;

	

	function __construct() {

		parent::__construct();

		$this->permisos = $this->permisos_lib->control();

	}	

	

	function index(){

            $vista_interna = array(
                
                    'permisos_efectivos' => $this->permisos,
                    'periodo_desde' => "",
                    'periodo_hasta' => "",

            );

            $vista_externa = array(			

                    'title' => ucwords("Listados de Precios por Clientes"),

                    'contenido_main' => $this->load->view('components/piezas/consultas_globales/listado_precios_clientes', $vista_interna, true)

            );		



            $this->load->view('template/backend', $vista_externa);

	}



        public function exportarAExcel(){

            $periodo_desde = $this->input->post('excel_desde') != ""?$this->input->post('excel_desde'):false;

            $periodo_hasta = $this->input->post('excel_hasta') != ""?$this->input->post('excel_hasta'):false;



            $results = $this->flash_md->getListadoPreciosClientes($periodo_desde,$periodo_hasta);


            ob_start();
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

            $this->excel->getActiveSheet()->setCellValue("D1",'Grupo');

            //$this->excel->getActiveSheet()->setCellValue("E1",'Cantidad');

            $this->excel->getActiveSheet()->setCellValue("E1",'Precio Unitario');

            // set cells values

            $this->load->library('simplehtmldom_1_5/simple_html_dom'); 

            $i = 2;

            foreach ($results as $value) {

                    // echo $trs->find('td',1)->innertext;

                    $this->excel->getActiveSheet()->setCellValue("A$i",$value->sucursal);

                    $this->excel->getActiveSheet()->setCellValue("B$i",$value->cliente);

                    $this->excel->getActiveSheet()->setCellValue("C$i",$value->servicio);

                    $this->excel->getActiveSheet()->setCellValue("D$i",$value->grupo);

                    //$this->excel->getActiveSheet()->setCellValue("E$i",$value->cantidad);
                    $this->excel->getActiveSheet()->getStyle("E$i")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
                    $this->excel->getActiveSheet()->setCellValue("E$i",$value->precio_unitario);
                    
                    $i++;

            }

            

            $this->excel->setActiveSheetIndex(0);

            $filename='Listado_Precios_por_Cliente.xls'; //save our workbook as this file name



            /*header('Content-Type: application/vnd.ms-excel'); //mime type



            header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name



            header('Cache-Control: max-age=0'); //no cache
*/
header('Content-type: application/vnd.ms-excel');
 header("Content-Disposition: attachment; filename=$filename");
 header("Pragma: no-cache");
 header("Expires: 0");


            //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)

            //if you want to save it as .XLSX Excel 2007 format



            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5'); 



            //force user to download the Excel file without writing it to server's HD
            ob_end_clean();
            $objWriter->save('php://output');

    }

}



/* End of file piezas.php */

/* Location: ./system/application/controllers/piezas.php */