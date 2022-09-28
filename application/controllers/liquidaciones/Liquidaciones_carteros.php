<?php

class Liquidaciones_carteros extends CI_Controller {

	private $permisos;
	
	function __construct() {
		parent::__construct();
		$this->permisos = $this->permisos_lib->control();
	}	
	
	function index(){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
                        'results' => []//$this->codegen_model->get('flash_liquidaciones_carteros','*','')
		);

		$vista_externa = array(			
			'title' => ucwords("liquidaciones/liquidaciones_carteros"),
			'contenido_main' => $this->load->view('components/liquidaciones/liquidaciones_carteros/liquidaciones_carteros_list', $vista_interna, true)
		);		
		
		$this->load->view('template/backend', $vista_externa);
	}

        public function filtro()
        {
            $liquidacion_cartero_id = $this->input->post('liquidacion_cartero_id') != ''?$this->input->post('liquidacion_cartero_id'):NULL;

            $results = $this->flash_md->getLiquidacionesCarteros($liquidacion_cartero_id);
            
            $vista_interna = array(
                'permisos_efectivos' => $this->permisos,
                'results' => $results,
            );

            $vista_externa = array(			
			'title' => ucwords("Liquidaciones Carteros"),
			'contenido_main' => $this->load->view('components/liquidaciones/liquidaciones_carteros/liquidaciones_carteros_list', $vista_interna, true)
		);		
		
            $this->load->view('template/backend', $vista_externa);
        }
    
        public function addFiltro()
        {
            if ($this->input->post('filtro_enviar_form')){
                
                $periodo_desde = $this->input->post('filtro_fecha_desde') != ''?$this->input->post('filtro_fecha_desde'):false;
                $periodo_hasta = $this->input->post('filtro_fecha_hasta') != ''?$this->input->post('filtro_fecha_hasta'):false;
                $periodo_alta_desde = $this->input->post('filtro_alta_desde') != ''?$this->input->post('filtro_alta_desde'):false;
                $periodo_alta_hasta = $this->input->post('filtro_alta_hasta') != ''?$this->input->post('filtro_alta_hasta'):false;
                $sucursal_id = $this->input->post('filtro_sucursal_id') != ''?$this->input->post('filtro_sucursal_id'):false;
                
                if($periodo_desde) $desde = new DateTime($periodo_desde);
                if($periodo_hasta) $hasta = new DateTime($periodo_hasta);
                if($periodo_alta_desde) $alta_desde = new DateTime($periodo_alta_desde);
                if($periodo_alta_hasta) $alta_hasta = new DateTime($periodo_alta_hasta);

                $results = $this->flash_md->getCarterosPendientesLiquidar($desde, $hasta,$alta_desde,$alta_hasta, $sucursal_id);

                $vista_interna = array(
			'permisos_efectivos' => $this->permisos,
                        'sucursales' => $this->codegen_model->get('flash_sucursales','*',''),
                        'results' =>  $results,
                        'periodo_desde' => $periodo_desde,
                        'periodo_hasta' => $periodo_hasta,
                        'periodo_alta_desde' => $periodo_alta_desde,
                        'periodo_alta_hasta' => $periodo_alta_hasta,
                        'sucursal_id' => $sucursal_id
		);

                $vista_externa = array(			
                            'title' => ucwords("Liquidaciones Carteros"),
                            'contenido_main' => $this->load->view('components/liquidaciones/liquidaciones_carteros/liquidaciones_carteros_add', $vista_interna, true)
                    );		
		
                $this->load->view('template/backend', $vista_externa);}
        }
        
	function add(){
	    
	    
		if ($this->input->post('enviar_form')){
                        $periodo_desde = $this->input->post('periodo_desde') != ''?$this->input->post('periodo_desde'):false;
                        $periodo_hasta = $this->input->post('periodo_hasta') != ''?$this->input->post('periodo_hasta'):false;
                        $periodo_alta_desde = $this->input->post('periodo_alta_desde') != ''?$this->input->post('periodo_alta_desde'):false;
                        $periodo_alta_hasta = $this->input->post('periodo_alta_hasta') != ''?$this->input->post('periodo_alta_hasta'):false;

                        if($periodo_desde) $desde = new DateTime($periodo_desde);
                        if($periodo_hasta) $hasta = new DateTime($periodo_hasta);
                        if($periodo_alta_desde) $alta_desde = new DateTime($periodo_alta_desde);
                        if($periodo_alta_hasta) $alta_hasta = new DateTime($periodo_alta_hasta);

                        $sucursal_id = $this->input->post('sucursal_id');
			$data = array(
					'sucursal_id' => $sucursal_id,
					'periodo_desde' => $desde->format('Y-m-d'),
					'periodo_hasta' => $hasta->format('Y-m-d'),
                                        'periodo_alta_desde' => $alta_desde->format('Y-m-d'),
					'periodo_alta_hasta' => $alta_hasta->format('Y-m-d'),
					'create' => date('Y-m-d H:m:s'),
					'update' => $this->input->post('update')
				);
			$liquidacion_cartero_id = $this->codegen_model->add('flash_liquidaciones_carteros',$data);
                        //Traigo la misma consulta mostrada en pantalla, desde el servidor
                        $results = $this->flash_md->getCarterosPendientesLiquidar($desde, $hasta,$alta_desde,$alta_hasta, $sucursal_id);
                        $i = 0;
                        foreach ($results as $value) {
                            $data_detalle[$i] = array(
                                    'liquidacion_cartero_id' => $liquidacion_cartero_id,
                                    'cartero_id' => $value->cartero_id,
                                    'cliente_id' => $value->cliente_id,
                                    'hoja_de_ruta_id' => $value->hdr_id,
                                    'servicio_id' => $value->codigo_servicio,
                                    'servicio_nombre' => $value->servicio,
                                    'estado_id' => $value->estado_id,
                                    'estado_nombre' => $value->estado_nombre,
                                    'cantidad_piezas' => $value->cantidad_piezas,
                                    'precio_unitario' => $value->precio_cliente,
                                    'create' =>  date('Y-m-d H:m:s'),
                            );
                            $i++;
                        }
                        $this->db->insert_batch('flash_liquidaciones_carteros_detalles', $data_detalle);
			redirect(base_url().'liquidaciones/liquidaciones_carteros');
		}		  
   
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
                        'sucursales' => $this->codegen_model->get('flash_sucursales','*',''),
                        'results' => []
		);

		$vista_externa = array(			
			'title' => ucwords("liquidaciones/liquidaciones_carteros"),
			'contenido_main' => $this->load->view('components/liquidaciones/liquidaciones_carteros/liquidaciones_carteros_add', $vista_interna, true)
		);		  
   
		$this->load->view('template/backend', $vista_externa);
	}	

	function edit($id){
		if ($this->input->post('enviar_form')){
			$data = array(
					'sucursal_id' => $this->input->post('sucursal_id'),
					'periodo_desde' => $this->input->post('periodo_desde'),
					'periodo_hasta' => $this->input->post('periodo_hasta'),
					'create' => $this->input->post('create'),
					'update' => $this->input->post('update')
				);
			$this->codegen_model->edit('flash_liquidaciones_carteros',$data,'id',$this->input->post('id'));
			redirect(base_url().'liquidaciones/liquidaciones_carteros');
		}

		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'result' => $this->codegen_model->row('flash_liquidaciones_carteros','*','id = '.$id)
		);

		$vista_externa = array(			
			'title' => ucwords("liquidaciones/liquidaciones_carteros"),
			'contenido_main' => $this->load->view('components/liquidaciones/liquidaciones_carteros/liquidaciones_carteros_edit', $vista_interna, true)
		);		  
   
		$this->load->view('template/backend', $vista_externa);
	}

	function view($id){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'result' => $this->codegen_model->row('flash_liquidaciones_carteros','*','id = '.$id)
		);

		$vista_externa = array(			
			'title' => ucwords("liquidaciones/liquidaciones_carteros"),
			'contenido_main' => $this->load->view('components/liquidaciones/liquidaciones_carteros/liquidaciones_carteros_view', $vista_interna, true)
		);		  
   
		$this->load->view('template/view', $vista_externa);
	}

	function json($id){
		$json = $this->codegen_model->row('flash_liquidaciones_carteros','*','id = '.$id);
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}

	function json_all(){
		$json = $this->codegen_model->get('flash_liquidaciones_carteros','*','');
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}
	
	function delete($id){
                $this->codegen_model->delete('flash_liquidaciones_carteros_detalles','liquidacion_cartero_id',$id);
		$this->codegen_model->delete('flash_liquidaciones_carteros','id',$id);             
        }
        
        public function exportarAExcel($liquidacion_cartero_id){
            ini_set('memory_limit','-1');
	    $liquidacion = $this->codegen_model->row('flash_liquidaciones_carteros','*','id = '.$liquidacion_cartero_id);
            $por_zonas = $this->flash_md->getInfoCarterosXZona($liquidacion_cartero_id,$liquidacion->periodo_desde,$liquidacion->periodo_hasta);
            $por_carteros = $this->flash_md->getInfoCarterosXCarteros($liquidacion_cartero_id,$liquidacion->periodo_desde,$liquidacion->periodo_hasta,$liquidacion->periodo_alta_desde,$liquidacion->periodo_alta_hasta);
            $por_devoluciones = $this->flash_md->getInfoCarterosXDevoluciones($liquidacion_cartero_id);
            $hdr_rendidas = $this->flash_md->getHojasRutasRendidas($liquidacion_cartero_id);
            $hdr_despachadas = $this->flash_md->getHojasRutasDespachadas($liquidacion_cartero_id);
            //load our new PHPExcel library
            $this->load->library('excel');
            //activate worksheet number 1
            $this->excel->setActiveSheetIndex(0);
            //name the worksheet
            $this->excel->getActiveSheet()->setTitle('por zonas');
            //set cabeceras
            $this->excel->getActiveSheet()->setCellValue("A1",'Id Recorrido');
            $this->excel->getActiveSheet()->setCellValue("B1",'recorrido');
            $this->excel->getActiveSheet()->setCellValue("C1",'Servicio');
            $this->excel->getActiveSheet()->setCellValue("D1",'Cliente');
            $this->excel->getActiveSheet()->setCellValue("E1",'Precio Cliente');
            $this->excel->getActiveSheet()->setCellValue("G1",'SumaDeCantidad');

            // set cells values
            $this->load->library('simplehtmldom_1_5/simple_html_dom'); 
            $i = 2;
            foreach ($por_zonas as $value) {
                    // echo $trs->find('td',1)->innertext;
                    $this->excel->getActiveSheet()->setCellValue("A$i",$value->IdRecorrido);
                    $this->excel->getActiveSheet()->setCellValue("B$i",$value->zona);
                    $this->excel->getActiveSheet()->setCellValue("C$i",$value->servicio);
                    $this->excel->getActiveSheet()->setCellValue("D$i",$value->cliente);
                    $this->excel->getActiveSheet()->getStyle("E$i")->getNumberFormat()->setFormatCode('###,###,###.##');
                    $this->excel->getActiveSheet()->setCellValue("E$i", $value->precio);
                    $this->excel->getActiveSheet()->setCellValue("G$i",$value->piezas);
                    $i++;
            }
            $this->excel->createSheet(1);
            //$this->excel->addSheet($nuevaPestaña);
            $this->excel->setActiveSheetIndex(1);
            //name the worksheet
            $this->excel->getActiveSheet()->setTitle('por carteros');
            //set cabeceras
            $this->excel->getActiveSheet()->setCellValue("A1",'Cartero');
            $this->excel->getActiveSheet()->setCellValue("B1",'Tipo cartero');
            $this->excel->getActiveSheet()->setCellValue("C1",'Servicio');
            $this->excel->getActiveSheet()->setCellValue("D1",'Nombre');
            $this->excel->getActiveSheet()->setCellValue("E1",'Hoja de Ruta');
            $this->excel->getActiveSheet()->setCellValue("F1",'Precio Cliente');
            $this->excel->getActiveSheet()->setCellValue("G1",'Suma de cantidad');

            // set cells values
            $this->load->library('simplehtmldom_1_5/simple_html_dom'); 
            $i = 2;
            foreach ($por_carteros as $value) {
                    // echo $trs->find('td',1)->innertext;
                    $this->excel->getActiveSheet()->setCellValue("A$i",$value->cartero);
                    $this->excel->getActiveSheet()->setCellValue("B$i",$value->tipo_cartero);
                    $this->excel->getActiveSheet()->setCellValue("C$i",$value->servicio);
                    $this->excel->getActiveSheet()->setCellValue("D$i",$value->cliente);
                    $this->excel->getActiveSheet()->setCellValue("E$i",$value->hoja_ruta);
                    //$this->excel->getActiveSheet()->getStyle("F$i")->getNumberFormat()->setFormatCode('###,###,###.##');
                    $this->excel->getActiveSheet()->setCellValue("F$i", $value->precio);
                    $this->excel->getActiveSheet()->setCellValue("G$i",$value->suma_piezas);
                    $i++;
            }
            
            $this->excel->createSheet(2);
            //$this->excel->addSheet($nuevaPestaña);
            $this->excel->setActiveSheetIndex(2);
            //name the worksheet
            $this->excel->getActiveSheet()->setTitle('devoluciones');
            //set cabeceras
            $this->excel->getActiveSheet()->setCellValue("A1",'idCartero');
            $this->excel->getActiveSheet()->setCellValue("B1",'Apellido y Nombre');
            $this->excel->getActiveSheet()->setCellValue("C1",'Id Servicios');
            $this->excel->getActiveSheet()->setCellValue("D1",'Servicios');
            $this->excel->getActiveSheet()->setCellValue("E1",'Id Estado');
            $this->excel->getActiveSheet()->setCellValue("F1",'Estado');
            $this->excel->getActiveSheet()->setCellValue("G1",'Cuenta del Id Pieza');

            // set cells values
            $this->load->library('simplehtmldom_1_5/simple_html_dom'); 
            $i = 2;
            foreach ($por_devoluciones as $value) {
                    // echo $trs->find('td',1)->innertext;
                    $this->excel->getActiveSheet()->setCellValue("A$i",$value->id);
                    $this->excel->getActiveSheet()->setCellValue("B$i",$value->cartero);
                    $this->excel->getActiveSheet()->setCellValue("C$i",$value->id_servicio);
                    $this->excel->getActiveSheet()->setCellValue("D$i",$value->servicio);
//                    $this->excel->getActiveSheet()->getStyle("E$i")->getNumberFormat()->setFormatCode('###,###,###.##');
                    $this->excel->getActiveSheet()->setCellValue("E$i", $value->id_estado);
                    $this->excel->getActiveSheet()->setCellValue("F$i",$value->nombre);
                    $this->excel->getActiveSheet()->setCellValue("G$i",$value->suma_piezas);
                    $i++;
            }
            
//            $this->excel->createSheet(3);
//            //$this->excel->addSheet($nuevaPestaña);
//            $this->excel->setActiveSheetIndex(3);
//            //name the worksheet
//            $this->excel->getActiveSheet()->setTitle('hr rendidas');
//            //set cabeceras
//            $this->excel->getActiveSheet()->setCellValue("A1",'idHojaRuta');
//            $this->excel->getActiveSheet()->setCellValue("B1",'ApellidoyNombre');
////            $this->excel->getActiveSheet()->setCellValue("C1",'Hojas Rendidas');
//            $this->excel->getActiveSheet()->setCellValue("C1",'Fecha');
//
//            // set cells values
//            $this->load->library('simplehtmldom_1_5/simple_html_dom'); 
//            $i = 2;
//            foreach ($hdr_rendidas as $value) {
//                    // echo $trs->find('td',1)->innertext;
//                    $this->excel->getActiveSheet()->setCellValue("A$i",$value->id);
//                    $this->excel->getActiveSheet()->setCellValue("B$i",$value->apellido_nombre);
////                    $this->excel->getActiveSheet()->setCellValue("C$i",$value->hojas);
//                    $this->excel->getActiveSheet()->setCellValue("C$i",$value->fecha_baja);
//                    $i++;
//            }
            
            $this->excel->createSheet(3);
            //$this->excel->addSheet($nuevaPestaña);
            $this->excel->setActiveSheetIndex(3);
            //name the worksheet
            $this->excel->getActiveSheet()->setTitle('hr rendidas');
            //set cabeceras
            $this->excel->getActiveSheet()->setCellValue("A1",'idHojaRuta');
            $this->excel->getActiveSheet()->setCellValue("B1",'ApellidoyNombre');
//            $this->excel->getActiveSheet()->setCellValue("C1",'Hojas Rendidas');
            $this->excel->getActiveSheet()->setCellValue("C1",'FechaRendicion');
            $this->excel->getActiveSheet()->setCellValue("D1",'Fecha');

            // set cells values
            $this->load->library('simplehtmldom_1_5/simple_html_dom'); 
            $i = 2;
            foreach ($hdr_despachadas as $value) {
                    // echo $trs->find('td',1)->innertext;
                    $this->excel->getActiveSheet()->setCellValue("A$i",$value->id);
                    $this->excel->getActiveSheet()->setCellValue("B$i",$value->apellido_nombre);
//                    $this->excel->getActiveSheet()->setCellValue("C$i",$value->hojas);
                    $this->excel->getActiveSheet()->setCellValue("C$i",$value->fecha_baja);
                    $this->excel->getActiveSheet()->setCellValue("D$i", $value->fecha_creacion);
                    $i++;
            }
            
            $this->excel->setActiveSheetIndex(0);
            $filename='Info_carteros.xls'; //save our workbook as this file name

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

/* End of file liquidaciones/liquidaciones_carteros.php */
/* Location: ./system/application/controllers/liquidaciones/liquidaciones_carteros.php */