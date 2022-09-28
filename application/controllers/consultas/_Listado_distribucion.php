<?php

class Listado_distribucion extends MY_Controller
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
                'title' => ucwords("Listado de Distribuciones Pendientes de Rendir"),
                'contenido_main' => $this->load->view('components/piezas/consultas_globales/listado_distribuciones_pendientes_rendir', $vista_interna, true)
        );		

        $this->load->view('template/backend', $vista_externa);
    }
    
    function consultas_distribucion_filtro(){
        $periodo_desde = $this->input->post('periodo_desde') != ""?$this->input->post('periodo_desde'):false;
        $periodo_hasta = $this->input->post('periodo_hasta') != ""?$this->input->post('periodo_hasta'):false;
        $sucursal_id = $this->input->post('sucursal_id') != ""?$this->input->post('sucursal_id'):false;
        $dias = $this->input->post('dias') != ""?$this->input->post('dias'):0;
        $vista_interna = array(
            'permisos_efectivos' => $this->permisos,
            'results' => $this->flash_md->getListadoDistribucionPendientesRendir($periodo_desde,$periodo_hasta,$sucursal_id,$dias),
            'periodo_desde' => $periodo_desde,
            'periodo_hasta' => $periodo_hasta,
            'sucursal_id' => $sucursal_id,
            'dias' => $dias,
            'sucursales' => Sucursal::all(),
        );

        $vista_externa = array(			
                'title' => ucwords("Listado de Distribuciones Pendientes de Rendir"),
                'contenido_main' => $this->load->view('components/piezas/consultas_globales/listado_distribuciones_pendientes_rendir', $vista_interna, true)
        );		

        $this->load->view('template/backend', $vista_externa);
    }
    
    public function exportar_consulta_distribucion(){
            $periodo_desde = $this->input->post('excel_desde') != ""?$this->input->post('excel_desde'):false;
            $periodo_hasta = $this->input->post('excel_hasta') != ""?$this->input->post('excel_hasta'):false;
            $sucursal_id = $this->input->post('excel_sucursal_id') != ""?$this->input->post('excel_sucursal_id'):false;
            $dias = $this->input->post('excel_dias') != ""?$this->input->post('excel_dias'):false;
            
            $hojas_rutas = $this->flash_md->getListadoDistribucionPendientesRendir($periodo_desde,$periodo_hasta,$sucursal_id,$dias);
            //load our new PHPExcel library
            $this->load->library('excel');
            //activate worksheet number 1
            $this->excel->setActiveSheetIndex(0);
            //name the worksheet
            $this->excel->getActiveSheet()->setTitle('Distrib. Pendientes de Rendir');
            //set cabeceras
            $this->excel->getActiveSheet()->setCellValue("A1",'Fecha Ing.');
            $this->excel->getActiveSheet()->setCellValue("B1",'Pieza');
            $this->excel->getActiveSheet()->setCellValue("C1",'C.I.');
            $this->excel->getActiveSheet()->setCellValue("D1",'Cliente');
            $this->excel->getActiveSheet()->setCellValue("E1",'Servicio');
            $this->excel->getActiveSheet()->setCellValue("F1",'Cartero');
            $this->excel->getActiveSheet()->setCellValue("G1",'H.R.');
            $this->excel->getActiveSheet()->setCellValue("H1",'Fecha Creación');
            $this->excel->getActiveSheet()->setCellValue("I1",'Destinatario');
            $this->excel->getActiveSheet()->setCellValue("J1",'Domicilio');
            $this->excel->getActiveSheet()->setCellValue("K1",'C.P.');
            $this->excel->getActiveSheet()->setCellValue("L1",'Estado');
            // set cells values
            $this->load->library('simplehtmldom_1_5/simple_html_dom'); 
            $i = 2;
            foreach ($hojas_rutas as $value) {
                    // echo $trs->find('td',1)->innertext;
                    $this->excel->getActiveSheet()->setCellValue("A$i",$value->fecha_ingreso);
                    $this->excel->getActiveSheet()->setCellValue("B$i",$value->pieza_id);
                    $this->excel->getActiveSheet()->setCellValue("C$i",$value->numero);
                    $this->excel->getActiveSheet()->setCellValue("D$i",$value->cliente);
                    $this->excel->getActiveSheet()->setCellValue("E$i",$value->servicio);
                    $this->excel->getActiveSheet()->setCellValue("F$i",$value->cartero);
                    $this->excel->getActiveSheet()->setCellValue("G$i",$value->hoja_ruta_id);
                    $this->excel->getActiveSheet()->setCellValue("H$i",$value->fecha_creacion);
                    $this->excel->getActiveSheet()->setCellValue("I$i",$value->destinatario);
                    $this->excel->getActiveSheet()->setCellValue("J$i",$value->domicilio);
                    $this->excel->getActiveSheet()->setCellValue("K$i",$value->codigo_postal);
                    $this->excel->getActiveSheet()->setCellValue("L$i",$value->estado);
                    $i++;
            }
            
            $this->excel->setActiveSheetIndex(0);
            $filename='Listado_distribuciones_pendiente_de_rendir.xls'; //save our workbook as this file name

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