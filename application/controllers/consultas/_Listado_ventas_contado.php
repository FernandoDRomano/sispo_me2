<?php

class Listado_ventas_contado extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
       $vista_interna = array(
                'permisos_efectivos' => $this->permisos,
                'results' => [],
                'sucursales' => Sucursal::all(),
        );

        $vista_externa = array(			
                'title' => ucwords("Listado Ventas Contado"),
                'contenido_main' => $this->load->view('components/piezas/consultas_globales/listado_ventas_contado', $vista_interna, true)
        );		

        $this->load->view('template/backend', $vista_externa);
    }
    
    function consultas_ventas_contado_filtro(){
        $periodo_desde = $this->input->post('periodo_desde') != ""?$this->input->post('periodo_desde'):false;
        $periodo_hasta = $this->input->post('periodo_hasta') != ""?$this->input->post('periodo_hasta'):false;
        $sucursal_id = $this->input->post('sucursal_id') != ""?$this->input->post('sucursal_id'):false;

        $vista_interna = array(
            'permisos_efectivos' => $this->permisos,
            'results' => $this->flash_md->getListadoVentasContado($periodo_desde,$periodo_hasta,$sucursal_id),
            'periodo_desde' => $periodo_desde,
            'periodo_hasta' => $periodo_hasta,
            'sucursal_id' => $sucursal_id,
            'sucursales' => Sucursal::all(),
        );

        $vista_externa = array(			
                'title' => ucwords("Listado Ventas Contado"),
                'contenido_main' => $this->load->view('components/piezas/consultas_globales/listado_ventas_contado', $vista_interna, true)
        );		

        $this->load->view('template/backend', $vista_externa);
    }
    
    public function exportar_ventas_contado(){
            $periodo_desde = $this->input->post('excel_desde') != ""?$this->input->post('excel_desde'):false;
            $periodo_hasta = $this->input->post('excel_hasta') != ""?$this->input->post('excel_hasta'):false;
            $sucursal_id = $this->input->post('excel_sucursal_id') != ""?$this->input->post('excel_sucursal_id'):false;
            
            $hojas_rutas = $this->flash_md->getListadoVentasContado($periodo_desde,$periodo_hasta,$sucursal_id);
            //load our new PHPExcel library
            $this->load->library('excel');
            //activate worksheet number 1
            $this->excel->setActiveSheetIndex(0);
            //name the worksheet
            $this->excel->getActiveSheet()->setTitle('Listado Vtas. Contado');
            //set cabeceras
            $this->excel->getActiveSheet()->setCellValue("A1",'Comp. Ing.');
            $this->excel->getActiveSheet()->setCellValue("B1",'Fecha');
            $this->excel->getActiveSheet()->setCellValue("C1",'Cliente');
            $this->excel->getActiveSheet()->setCellValue("D1",'Servicio');
            $this->excel->getActiveSheet()->setCellValue("E1",'Cantidad');
            $this->excel->getActiveSheet()->setCellValue("F1",'Precio');

            // set cells values
            $this->load->library('simplehtmldom_1_5/simple_html_dom'); 
            $i = 2;
            foreach ($hojas_rutas as $value) {
                    // echo $trs->find('td',1)->innertext;
                    $this->excel->getActiveSheet()->setCellValue("A$i",$value->numero);
                    $this->excel->getActiveSheet()->setCellValue("B$i",$value->fecha_pedido);
                    $this->excel->getActiveSheet()->setCellValue("C$i",$value->cliente);
                    $this->excel->getActiveSheet()->setCellValue("D$i",$value->servicio);
                    $this->excel->getActiveSheet()->setCellValue("E$i",$value->cantidad);
                    $this->excel->getActiveSheet()->setCellValue("F$i",$value->precio);
                    $i++;
            }
            
            $this->excel->setActiveSheetIndex(0);
            $filename='Listado_ventas_contado.xls'; //save our workbook as this file name

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

/* End of file Despachos.php */
/* Location: ./system/application/controllers/Despachos.php */