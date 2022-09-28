<?php

class Listado_vtas_productos extends CI_Controller {

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
                    'title' => ucwords("Listados de Ventas por Productos"),
                    'contenido_main' => $this->load->view('components/piezas/consultas_globales/listado_vtas_productos', $vista_interna, true)
            );		

            $this->load->view('template/backend', $vista_externa);
	}
        
        public function exportarAExcel(){
            $periodo_desde = $this->input->post('excel_desde') != ""?$this->input->post('excel_desde'):false;
            $periodo_hasta = $this->input->post('excel_hasta') != ""?$this->input->post('excel_hasta'):false;

            $results = $this->flash_md->getListadoVtasProductos($periodo_desde,$periodo_hasta);

            //var_dump($results['sucursales']);die;
            //load our new PHPExcel library
            $this->load->library('excel');
            //activate worksheet number 1
            $this->excel->setActiveSheetIndex(0);
            //name the worksheet
            $this->excel->getActiveSheet()->setTitle('Listado Ventas por Productos');
            //set cabeceras
            $this->excel->getActiveSheet()->setCellValue("A1",'MES');
            $this->excel->getActiveSheet()->setCellValue("A2",'PRODUCTO');
            $col_inicio = "B";
            $col_final = "B";
            foreach ($results['sucursales'] as $value) {//echo ($col++)."<br/>";
            //echo $col++."1";
                $this->excel->getActiveSheet()->setCellValue($col_inicio."1",$value->nombre);
                $this->excel->getActiveSheet()->setCellValue($col_inicio++."2","Ingreso");
                $this->excel->getActiveSheet()->setCellValue($col_inicio++."2","Ventas");
                $this->excel->getActiveSheet()->setCellValue($col_inicio++."2","Precio Promedio");
                $col_final++;
                $col_final++;
              // echo $col_inicio.'1'.','. $col_final."1<br/>";
                //$this->excel->getActiveSheet()->mergeCells($col_inicio.'1:' .$col_final.'1');
                $col_inicio = $col_final++;
                $col_inicio++;
            };
            //Columnas de totales
            $this->excel->getActiveSheet()->setCellValue($col_inicio."1","TOTALES");
            $this->excel->getActiveSheet()->setCellValue($col_inicio++."2","Piezas");
            $this->excel->getActiveSheet()->setCellValue($col_inicio++."2","Ventas");
            $this->excel->getActiveSheet()->setCellValue($col_inicio++."2","Precio Promedio");
            $col_final++;
            $col_final++;

            //Fin columna de totales
            
            // set cells values
            $this->load->library('simplehtmldom_1_5/simple_html_dom'); 

            $matriz_totales = NULL;
            $suma_fila_cantidades = 0;
            $suma_fila_ventas = 0;
            $suma_fila_precio_promedio = 0;
            $col = "A";
            $row = 3;
            $i = 0;
            $suma1_v = 0;
            $suma2_v = 0;
            $suma3_v = 0;
            foreach ($results['grupos'] as $grupo) {
                $this->excel->getActiveSheet()->setCellValue($col.$row,$grupo->nombre);
                foreach ($results['sucursales'] as $sucursal) {
                    $cantidad = $results['cantidades'][$i];
                    $venta = $results['ventas'][$i];
                    $precio_promedio = $cantidad > 0?$venta/$cantidad:0;//$results['precios_promedio'][$i];
                    $suma_fila_cantidades += $cantidad;
                    $suma_fila_ventas += $venta;
                    $suma_fila_precio_promedio += $cantidad > 0?$venta/$cantidad:0;//$precio_promedio;

                    $col++;
                    $this->excel->getActiveSheet()->setCellValue($col.$row,$cantidad);
                    $matriz_totales[$col][$row] = $cantidad;
                    $col++;
                    $this->excel->getActiveSheet()->setCellValue($col.$row,$venta);
                    $matriz_totales[$col][$row] = $venta;
                    $col++;
                    $this->excel->getActiveSheet()->setCellValue($col.$row,  number_format($precio_promedio,3,',','.'));
                    $matriz_totales[$col][$row] = $precio_promedio;
                    $i++;
                    
                }
                //3 ultimas Columnas de totales por filas
                $col++;
                $this->excel->getActiveSheet()->setCellValue($col.$row,$suma_fila_cantidades);
                $matriz_totales[$col][$row] = $cantidad;
                $col++;
                $this->excel->getActiveSheet()->setCellValue($col.$row,$suma_fila_ventas);
                $matriz_totales[$col][$row] = $venta;
                $col++;
                $this->excel->getActiveSheet()->setCellValue($col.$row,$cantidad > 0?number_format($suma_fila_ventas/$suma_fila_cantidades,3,',','.'):0);
                $matriz_totales[$col][$row] = number_format($precio_promedio, 3, ',', '.');//echo $suma_fila_ventas."    ".$suma_fila_cantidades."</br>";
                $col = "A";
                $row++;
                $suma1_v += $suma_fila_cantidades;
                $suma2_v += $suma_fila_ventas;
                $suma3_v += $suma_fila_precio_promedio;
                $suma_fila_cantidades = 0;
                $suma_fila_ventas = 0;
                $suma_fila_precio_promedio = 0;
                //Las sumas totales de columnas las pongo en el primer lugar de cada ARRAY
            };
            //Recorro la matriz para sumar los totales
            $suma = 0;
            $col =  $this->excel->getActiveSheet()->getHighestColumn();
            $row =  $this->excel->getActiveSheet()->getHighestrow();
            $titulo_total = $row+1;
            $contador = 0;
            $this->excel->getActiveSheet()->setCellValue("A".$titulo_total,"TOTALES");
            //var_dump($matriz_totales[3][3]);die;
            //echo "B" >= $col;die;
            $limite = count($results['sucursales'])*3;
            for ($columna = "B"; $contador <= $limite ; $columna++){
                for($fila = 3; $fila <= $row ; $fila++){
                    $suma += $matriz_totales[$columna][$fila];
                }
                //if($columna=="Y") echo $suma."<br/>";
                
                if ($contador < (count($results['sucursales'])*3)){//echo $suma;die;
                    $this->excel->getActiveSheet()->setCellValue($columna.$fila,$suma);
                }else{//SUMAS  de las columnas totales que van en la ultima fila echo $columna."   ".$fila." ".$suma."</br>";
                    $this->excel->getActiveSheet()->setCellValue($columna.$fila,$suma1_v);
                    $columna++;
                    $this->excel->getActiveSheet()->setCellValue($columna.$fila,$suma2_v);
                    $columna++;
                    $this->excel->getActiveSheet()->setCellValue($columna.$fila,$suma3_v);
                }
                $suma = 0;
                $contador++;
            }

            $this->excel->setActiveSheetIndex(0);
            $filename='Listado_Ventas_por_Productos.xls'; //save our workbook as this file name

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