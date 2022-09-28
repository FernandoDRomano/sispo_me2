<?php

class Liquidaciones_ddis extends CI_Controller {

	private $permisos;
	
	function __construct() {
		parent::__construct();
		$this->permisos = $this->permisos_lib->control();
	}	
	
	function index(){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'results' => $this->getLiquidacionesDDis()
		);

		$vista_externa = array(			
			'title' => ucwords("Liquidaciones DDIs"),
			'contenido_main' => $this->load->view('components/liquidaciones/liquidaciones_ddis/liquidaciones_ddis_list', $vista_interna, true)
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
			'title' => ucwords("Liquidaciones DDIs"),
			'contenido_main' => $this->load->view('components/liquidaciones/liquidaciones_ddis/liquidaciones_ddis_list', $vista_interna, true)
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
                $autorizador_id = $this->input->post('filtro_autorizador_id') != ''?$this->input->post('filtro_autorizador_id'):false;
                if($periodo_desde) $desde = new DateTime($periodo_desde);
                if($periodo_hasta) $hasta = new DateTime($periodo_hasta);
                if($periodo_alta_desde) $alta_desde = new DateTime($periodo_alta_desde);
                if($periodo_alta_hasta) $alta_hasta = new DateTime($periodo_alta_hasta);

                $results = $this->flash_md->getCarterosPendientesLiquidar($desde, $hasta,$alta_desde,$alta_hasta, $sucursal_id);

                $autorizadores = $this->getLiquidacionesAutorizadores();

                $vista_interna = array(
			'permisos_efectivos' => $this->permisos,
                        'sucursales' => $this->codegen_model->get('flash_sucursales','*',''),
                        'results' =>  $results,
                        'periodo_desde' => $periodo_desde,
                        'periodo_hasta' => $periodo_hasta,
                        'periodo_alta_desde' => $periodo_alta_desde,
                        'periodo_alta_hasta' => $periodo_alta_hasta,
                        'sucursal_id' => $sucursal_id,
                        'autorizador_id' => $autorizador_id,
                        'autorizadores' => $autorizadores,
		);

                $vista_externa = array(			
                            'title' => ucwords("Liquidaciones DDIs"),
                            'contenido_main' => $this->load->view('components/liquidaciones/liquidaciones_ddis/liquidaciones_ddis_add', $vista_interna, true)
                    );		
		
                $this->load->view('template/backend', $vista_externa);}
        }
        
        public function addFiltroEdit()
        {
            if ($this->input->post('filtro_enviar_form')){
                $periodo_desde = $this->input->post('filtro_fecha_desde') != ''?$this->input->post('filtro_fecha_desde'):false;
                $periodo_hasta = $this->input->post('filtro_fecha_hasta') != ''?$this->input->post('filtro_fecha_hasta'):false;
                $periodo_alta_desde = $this->input->post('filtro_alta_desde') != ''?$this->input->post('filtro_alta_desde'):false;
                $periodo_alta_hasta = $this->input->post('filtro_alta_hasta') != ''?$this->input->post('filtro_alta_hasta'):false;
                $sucursal_id = $this->input->post('filtro_sucursal_id') != ''?$this->input->post('filtro_sucursal_id'):false;
                $autorizador_id = $this->input->post('filtro_autorizador_id') != ''?$this->input->post('filtro_autorizador_id'):false;
                $liquidacion_ddi_id = $this->input->post('filtro_liquidacion_ddi_id') != ''?$this->input->post('filtro_liquidacion_ddi_id'):false;
                if($periodo_desde) $desde = new DateTime($periodo_desde);
                if($periodo_hasta) $hasta = new DateTime($periodo_hasta);
                if($periodo_alta_desde) $alta_desde = new DateTime($periodo_alta_desde);
                if($periodo_alta_hasta) $alta_hasta = new DateTime($periodo_alta_hasta);

                $results = $this->flash_md->getCarterosPendientesLiquidar($desde, $hasta,$alta_desde,$alta_hasta, $sucursal_id);
                $liquidacion = $this->codegen_model->row('flash_liquidaciones_ddis','*','id = '.$liquidacion_ddi_id);
                $sucursal = $this->codegen_model->row('flash_sucursales','*','id = '.$liquidacion->sucursal_id);
                $autorizadores = $this->getLiquidacionesAutorizadores();
//                var_dump($results);die;
    //                echo $this->db->last_query();die;
                $vista_interna = array(
                        'permisos_efectivos' => $this->permisos,
                        'liquidacion' => $liquidacion,
                        'results' => $results,
                        'sucursal' => $sucursal,
                        'autorizadores' => $autorizadores,
                        'periodo_desde' => $periodo_desde,
                        'periodo_hasta' => $periodo_hasta,
                        'periodo_alta_desde' => $periodo_alta_desde,
                        'periodo_alta_hasta' => $periodo_alta_hasta,
                        'sucursal_id' => $sucursal_id,
                        'autorizador_id' => $autorizador_id,

                );

                $vista_externa = array(			
                            'title' => ucwords("Liquidaciones DDIs"),
                            'contenido_main' => $this->load->view('components/liquidaciones/liquidaciones_ddis/liquidaciones_ddis_edit', $vista_interna, true)
                    );		
		
                $this->load->view('template/backend', $vista_externa);}
        }
        
	function add(){
	    
	    
		if ($this->input->post('enviar_form')){
                        //GRABAR ENCABEZADO
                        $periodo_desde = $this->input->post('periodo_desde') != ''?$this->input->post('periodo_desde'):false;
                        $periodo_hasta = $this->input->post('periodo_hasta') != ''?$this->input->post('periodo_hasta'):false;
                        $periodo_alta_desde = $this->input->post('periodo_alta_desde') != ''?$this->input->post('periodo_alta_desde'):false;
                        $periodo_alta_hasta = $this->input->post('periodo_alta_hasta') != ''?$this->input->post('periodo_alta_hasta'):false;
                        $liquidacion_ddi_autorizador_id = $this->input->post('liquidacion_ddi_autorizador_id');
                        if($periodo_desde) $desde = new DateTime($periodo_desde);
                        if($periodo_hasta) $hasta = new DateTime($periodo_hasta);
                        if($periodo_alta_desde) $alta_desde = new DateTime($periodo_alta_desde);
                        if($periodo_alta_hasta) $alta_hasta = new DateTime($periodo_alta_hasta);

                        $sucursal_id = $this->input->post('sucursal_id');
                        
                        $user_row = $this->ion_auth->user()->row();
                        
			$data = array(
					'sucursal_id' => $sucursal_id,
					'periodo_desde' => $desde->format('Y-m-d'),
					'periodo_hasta' => $hasta->format('Y-m-d'),
                                        'periodo_alta_desde' => $alta_desde->format('Y-m-d'),
					'periodo_alta_hasta' => $alta_hasta->format('Y-m-d'),
					'create' => date('Y-m-d H:m:s'),
                                        'create_user_id' => $user_row->id,
				);
                               // var_dump($data);die;
			$liquidacion_ddi_id = $this->codegen_model->add('flash_liquidaciones_carteros',$data);
                        //GRABAR HDRS DETALLE
                        $this->saveLiquidacionesHdrsDetalles($liquidacion_ddi_id,$liquidacion_ddi_autorizador_id,$desde, $hasta,$alta_desde,$alta_hasta, $sucursal_id);
                        
			redirect(base_url().'liquidaciones/liquidaciones_ddis/edit/'.$liquidacion_ddi_id);
		}		  

                $autorizadores = $this->getLiquidacionesAutorizadores();
                
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
                        'sucursales' => $this->codegen_model->get('flash_sucursales','*',''),
                        'autorizadores' => $autorizadores,
                        'results' => []
		);

		$vista_externa = array(			
			'title' => ucwords("Liquidaciones DDIs"),
			'contenido_main' => $this->load->view('components/liquidaciones/liquidaciones_ddis/liquidaciones_ddis_add', $vista_interna, true)
		);		  
   
		$this->load->view('template/backend', $vista_externa);
	}	

	function edit($id){
	    
	    
            if ($this->input->post('enviar_form')){
                //GRABAR ENCABEZADO
                $periodo_desde = $this->input->post('periodo_desde') != ''?$this->input->post('periodo_desde'):false;
                $periodo_hasta = $this->input->post('periodo_hasta') != ''?$this->input->post('periodo_hasta'):false;
                $periodo_alta_desde = $this->input->post('periodo_alta_desde') != ''?$this->input->post('periodo_alta_desde'):false;
                $periodo_alta_hasta = $this->input->post('periodo_alta_hasta') != ''?$this->input->post('periodo_alta_hasta'):false;
                $liquidacion_ddi_autorizador_id = $this->input->post('liquidacion_ddi_autorizador_id');
                if($periodo_desde) $desde = new DateTime($periodo_desde);
                if($periodo_hasta) $hasta = new DateTime($periodo_hasta);
                if($periodo_alta_desde) $alta_desde = new DateTime($periodo_alta_desde);
                if($periodo_alta_hasta) $alta_hasta = new DateTime($periodo_alta_hasta);

                $sucursal_id = $this->input->post('sucursal_id');

                $user_row = $this->ion_auth->user()->row();

                $data = array(
                                'sucursal_id' => $sucursal_id,
                                'periodo_desde' => $desde->format('Y-m-d'),
                                'periodo_hasta' => $hasta->format('Y-m-d'),
                                'periodo_alta_desde' => $alta_desde->format('Y-m-d'),
                                'periodo_alta_hasta' => $alta_hasta->format('Y-m-d'),
                                'update' => date('Y-m-d H:m:s'),
                                'update_user_id' => $user_row->id,
                        );

                //GRABAR HDRS DETALLE
                $this->saveEditLiquidacionesHdrsDetalles($id,$liquidacion_ddi_autorizador_id,$desde, $hasta,$alta_desde,$alta_hasta, $sucursal_id);
                        
                $this->codegen_model->edit('flash_liquidaciones_ddis',$data,'id',$id);
                redirect(base_url().'liquidaciones/liquidaciones_ddis/edit/'.$id);
            }

            $liquidacion = $this->codegen_model->row('flash_liquidaciones_carteros','*','id = '.$id);
            $sucursal = $this->codegen_model->row('flash_sucursales','*','id = '.$liquidacion->sucursal_id);
            $autorizadores = $this->getLiquidacionesAutorizadores();

            $vista_interna = array(
                    'permisos_efectivos' => $this->permisos,
                    'liquidacion' => $liquidacion,
                    'results' => $this->getLiquidacionesHdrsDetalles($liquidacion->id),
                    'sucursal' => $sucursal,
                    'autorizadores' => $autorizadores,
                    'autorizador_id' => $results[0]->autorizador_id,
                    'periodo_desde' => $liquidacion->periodo_desde,
                    'periodo_hasta' => $liquidacion->periodo_hasta,
                    'periodo_alta_desde' => $liquidacion->periodo_alta_desde,
                    'periodo_alta_hasta' => $liquidacion->periodo_alta_hasta,
                    'sucursal_id' => $liquidacion->sucursal_id,
                    'autorizador_id' => $liquidacion->autorizador_id,
                   
            );

            $vista_externa = array(			
                    'title' => ucwords("Liquidaciones DDIs"),
                    'contenido_main' => $this->load->view('components/liquidaciones/liquidaciones_ddis/liquidaciones_ddis_edit', $vista_interna, true)
            );		  

            $this->load->view('template/backend', $vista_externa);
	}

	function view($id){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'result' => $this->codegen_model->row('flash_liquidaciones_ddis','*','id = '.$id)
		);

		$vista_externa = array(			
			'title' => ucwords("liquidaciones_ddis"),
			'contenido_main' => $this->load->view('components/liquidaciones/liquidaciones_ddis/liquidaciones_ddis_view', $vista_interna, true)
		);		  
   
		$this->load->view('template/view', $vista_externa);
	}

	function json($id){
		$json = $this->codegen_model->row('flash_liquidaciones_ddis','*','id = '.$id);
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}

	function json_all(){
		$json = $this->codegen_model->get('flash_liquidaciones_ddis','*','');
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}
	
	function delete($id){
                $this->codegen_model->delete('flash_liquidaciones_ddis_detalles','liquidacion_cartero_id',$id);
		$this->codegen_model->delete('flash_liquidaciones_ddis','id',$id);             
        }
    
    function build_sorter($clave) {
        return function ($a, $b) use ($clave) {
            return strnatcmp($a[$clave], $b[$clave]);
        };
    }
    
    public function exportarAExcel($liquidacion_cartero_id){
            set_time_limit(1800);
            ini_set('memory_limit','-1');
	    $liquidacion = $this->codegen_model->row('flash_liquidaciones_carteros','*','id = '.$liquidacion_cartero_id);
            $por_zonas =  $this->flash_md->getInfoDdisXZona($liquidacion_cartero_id,$liquidacion->periodo_desde,$liquidacion->periodo_hasta);
            $por_ddis = $this->flash_md->getInfoDdisXDdis($liquidacion_cartero_id);
            $por_devoluciones = $this->flash_md->getInfoDdisXDevoluciones($liquidacion_cartero_id);
            //$hdr_rendidas = $this->flash_md->getHojasRutasRendidasDdis($liquidacion_cartero_id);
            $hdr_despachadas = $this->flash_md->getHojasRutasDespachadasDdis($liquidacion_cartero_id);
//            $acreditacionesDdis = $this->flash_md->getAcreditacionesDdis($liquidacion_cartero_id);
//            $DescuentosDdis = $this->flash_md->getDescuentosDdis($liquidacion_cartero_id);
//            var_dump($liquidacion);die;
            $liquidacion_resumen = $this->flash_md->getliquidacionesDdisResumen($liquidacion_cartero_id);
            $liquidacion_resumen_clientes = $this->flash_md->getliquidacionesDdisResumenClientes($liquidacion_cartero_id,$liquidacion->sucursal_id,$liquidacion->periodo_desde,$liquidacion->periodo_hasta);
            
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
            $numformat= '###,###,##0.00';
            $styleArray = array(
                'font'  => array(
                            'bold'  => false,
                            //'color' => array('rgb' => 'FF0GGG'),
                            'size'  => 11,
                            'name'  => 'Calibri'
                            ),
                'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('rgb' => '000000')
                    )
                )
                );
            
            $styleArray1 = array(
                'font'  => array(
                            'bold'  => true,
                            //'color' => array('rgb' => 'FF0GGG'),
                            'size'  => 9,
                            'name'  => 'Calibri'
                            ),
                );
            
           
            foreach ($por_zonas as $value) {
                    // echo $trs->find('td',1)->innertext;
                    $this->excel->getActiveSheet()->setCellValue("A$i",$value->IdRecorrido);
                    $this->excel->getActiveSheet()->setCellValue("B$i",$value->zona);
                    $this->excel->getActiveSheet()->setCellValue("C$i",$value->servicio);
                    $this->excel->getActiveSheet()->setCellValue("D$i",$value->cliente);
                    //$this->excel->getActiveSheet()->getStyle("E$i")->getNumberFormat()->setFormatCode('###,###,###.##');
                    $this->excel->getActiveSheet()->getStyle("E$i")->getNumberFormat()->setFormatCode($numformat);
                    $this->excel->getActiveSheet()->setCellValue("E$i",$value->precio);
                    $this->excel->getActiveSheet()->setCellValue("G$i",$value->piezas);
                    $i++;
            }
            $this->excel->createSheet(1);
            //$this->excel->addSheet($nuevaPesta単a);
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
            foreach ($por_ddis as $value) {
                    // echo $trs->find('td',1)->innertext;
                    $this->excel->getActiveSheet()->setCellValue("A$i",$value->cartero);
                    $this->excel->getActiveSheet()->setCellValue("B$i",$value->tipo_cartero);
                    $this->excel->getActiveSheet()->setCellValue("C$i",$value->servicio);
                    $this->excel->getActiveSheet()->setCellValue("D$i",$value->cliente);
                    $this->excel->getActiveSheet()->setCellValue("E$i",$value->hoja_ruta);
                    //$this->excel->getActiveSheet()->getStyle("F$i")->getNumberFormat()->setFormatCode('###,###,###.##');
                    $this->excel->getActiveSheet()->getStyle("F$i")->getNumberFormat()->setFormatCode($numformat);
                    $this->excel->getActiveSheet()->setCellValue("F$i",$value->precio);
                    
                    $this->excel->getActiveSheet()->setCellValue("G$i",$value->suma_piezas);
                    $i++;
            }
            
            $this->excel->createSheet(2);
            //$this->excel->addSheet($nuevaPesta単a);
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
            
            $this->excel->createSheet(3);
            //$this->excel->addSheet($nuevaPesta単a);
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
            
            $this->excel->createSheet(4);
            //$this->excel->addSheet($nuevaPesta単a);
            $this->excel->setActiveSheetIndex(4);
            //name the worksheet
            $this->excel->getActiveSheet()->setTitle('Resumen');
            //set cabeceras
            $this->excel->getActiveSheet()->setCellValue("A1",'Cartero');
            $this->excel->getActiveSheet()->setCellValue("B1",'Zona');
            $this->excel->getActiveSheet()->setCellValue("C1",'Simples');
            $this->excel->getActiveSheet()->setCellValue("D1",'');
            $this->excel->getActiveSheet()->setCellValue("E1",'Acuses');
            $this->excel->getActiveSheet()->setCellValue("F1",'');
            $col = "F";
            
            //$this->excel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
            $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(33);
            $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(23,5);
            $cont_clientes = 0;
            $this->excel->getActiveSheet()->getStyle('C1:Z1000')->applyFromArray($styleArray1);
            
            //Chequeo si alguno de los clientes y servicios no tiene piezas para todos los carteros, si no la tiene lo elimino
            $i = 0;
            $liquidacion_resumen_clientes_array[] = null;
            for ($h = 0 ; $h < count($liquidacion_resumen_clientes) ; $h++) {
                $query_piezas_en_columna ="SELECT (CASE WHEN SUM(lcd.cantidad_piezas) > 0 THEN SUM(lcd.cantidad_piezas) ELSE 0 END) AS cantidad_piezas 
                                     FROM flash_liquidaciones_carteros lc
                                     INNER JOIN flash_liquidaciones_carteros_detalles lcd ON lc.`id` = lcd.`liquidacion_cartero_id`
                                     WHERE lc.`id` = $liquidacion_cartero_id
                                     AND lcd.cliente_id = ".$liquidacion_resumen_clientes[$h]->cliente_id ."
                                     AND lcd.servicio_id = ". $liquidacion_resumen_clientes[$h]->servicio_id;
                //echo  $query_piezas_en_columna;die;                   
                $piezas_en_columna = $this->db->query($query_piezas_en_columna)->row();
                
               
                //echo  $h."   ". $liquidacion_cartero_id."   ".$liquidacion_resumen_clientes[$h]->cliente_id."  ".$liquidacion_resumen_clientes[$h]->servicio_id."   ".$piezas_en_columna->cantidad_piezas."<br/>   "; 
                
                if ((int)$piezas_en_columna->cantidad_piezas > (int)0){ $liquidacion_resumen_clientes_array[$i] = $liquidacion_resumen_clientes[$h]; $i++; }
     //           echo $piezas_en_columna->cantidad_piezas."<br>";
            }
            //var_dump($liquidacion_resumen_clientes_array);
            //die;
            
            //echo count($liquidacion_resumen_clientes_array);die;
            
            foreach ($liquidacion_resumen_clientes_array as $value) {
                if ($value->valor_entrega != NULL){
                    $this->excel->getActiveSheet()->setCellValue($col++."1",'');
                    $this->excel->getActiveSheet()->setCellValue($col++."1",$value->cliente." - ".$value->servicio);
                    $cont_clientes++;
                }
            }
            //sumo otra columna para que vaya el precio de las entregas de ese cliente especial
            $col++;
            $this->excel->getActiveSheet()->setCellValue($col++."1",'Total de Piezas');
            $this->excel->getActiveSheet()->setCellValue($col++."1",'Productividad');
            $this->excel->getActiveSheet()->setCellValue($col++."1",'Adicionales');
            $this->excel->getActiveSheet()->setCellValue($col++."1",'Descuentos');
            $this->excel->getActiveSheet()->setCellValue($col++."1",'Total a Pagar');
            $this->excel->getActiveSheet()->setCellValue($col++."1",'Observaciones');
            
            $col = "A";
            $fila = 2;
            $col_width = 8;
            $query_carteros = "SELECT lc.id,
                                sct.`nombre` tipo_cartero,
                                lcd.cartero_id,
                                sc.apellido_nombre cartero,
                                sc.observaciones,
                                hdr.zona_id,
                                z.nombre zona,
                                lcd.cliente_id,
                                c.nombre cliente,
                                sum(lcd.cantidad_piezas) piezas
                            FROM flash_liquidaciones_carteros lc
                            INNER JOIN flash_liquidaciones_carteros_detalles lcd on lc.id = lcd.liquidacion_cartero_id
                            INNER JOIN flash_hojas_rutas hdr ON hdr.cartero_id = lcd.cartero_id
                            INNER JOIN flash_sucursales_carteros sc on sc.id = lcd.cartero_id 
                            INNER JOIN flash_clientes c on c.id = lcd.cliente_id
                            INNER JOIN flash_sucursales_zonas z on z.id = hdr.zona_id
                            INNER JOIN flash_sucursales_carteros_tipos sct ON sc.cartero_tipo_id = sct.id
                            WHERE lc.id = $liquidacion_cartero_id
                            GROUP BY lcd.cartero_id
                            ORDER BY TRIM(BOTH FROM sc.apellido_nombre), c.nombre";
                //SE marca con AMARILLOP por que arriba ya esta definida liquidacion_resumen
               // echo $query_carteros;die;
                
                $carteros = $this->db->query($query_carteros)->result();

               $cartero_id = $liquidacion_resumen[0]->cartero_id; 
               $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth($col_width);
               //A cliente no lo tomo porque siempre va a ser distintos clientes si el resultado de la query da bien
               foreach ($carteros as $cartero) {
                    $piezas_clientes = 0;
                    $total_a_pagar = 0;
                    $piezas_valor_array = array();

                    $piezas_valor = [];
                    $this->excel->getActiveSheet()->setCellValue("A".$fila,$cartero->cartero." (".$cartero->tipo_cartero.")");
                    $this->excel->getActiveSheet()->setCellValue("B".$fila,$cartero->zona);
                    $this->excel->getActiveSheet()->setCellValue("C".$fila,"");
                    $this->excel->getActiveSheet()->setCellValue("D".$fila,"");
                    $this->excel->getActiveSheet()->setCellValue("E".$fila,"");
                    $this->excel->getActiveSheet()->setCellValue("F".$fila,"");
                    $columna = "G";
                                         
                    $query_total_piezas = "SELECT SUM(lcd.`cantidad_piezas`) cantidad_piezas
                                    FROM flash_liquidaciones_carteros lc
                                    INNER JOIN flash_liquidaciones_carteros_detalles lcd ON lc.`id` = lcd.`liquidacion_cartero_id`
                                    INNER JOIN flash_servicios s on s.id = lcd.servicio_id 
                                    WHERE lc.`id` = $liquidacion_cartero_id
                                    AND lcd.`cartero_id` = $cartero->cartero_id
                                    AND s.grupo_id NOT IN ( 4,14,9,5)
                                    GROUP BY lcd.`cartero_id`";
                    //echo $query_total_piezas;die;
                    $total_piezas = $this->db->query($query_total_piezas)->row();
                    
                    $query_predeterminado = "   SELECT * FROM flash_ddis_valores_predeterminados_entregas 
                                                WHERE cartero_id = $cartero->cartero_id
                                                 AND habilitado = 1";
                    $predeterminado = $this->db->query($query_predeterminado)->row(); 
                    
                    $query_productividad = "   SELECT * FROM flash_sucursales_carteros 
                                                WHERE id = $cartero->cartero_id";
                    $productividad = $this->db->query($query_productividad)->row(); 
                    //if ($cartero->cartero_id = 456) {var_dump($liquidacion_resumen_clientes);die;}
                    //var_dump($liquidacion_resumen_clientes_array);die;
                   
                    // PARA LOS CLIENTES QUE TIENEN VALORES ESPECIALES DE ENTREGA
                    for($i = 0; $i < count($liquidacion_resumen_clientes_array); $i++){
                        if ($liquidacion_resumen_clientes_array[$i] != null){
                        //Primero veo si tiene valor especial por cliente y cartero y zona
                        $query_valor_especial_entrega = "SELECT ve.cliente_id, c.nombre cliente, ve.pago valor_especial_entrega
                                                FROM flash_ddis_valores_entregas ve     
                                                INNER JOIN flash_clientes c ON c.id = ve.cliente_id
                                                WHERE ve.habilitado = 1
                                                AND ve.cliente_id = ".$liquidacion_resumen_clientes_array[$i]->cliente_id."  
                                                AND ve.zona_id = ".$cartero->zona_id ."
                                                AND ve.cartero_id = ". $cartero->cartero_id ."
                                                AND ve.servicio_id = ". $liquidacion_resumen_clientes_array[$i]->servicio_id;
                                   //       echo($query_valor_especial_entrega);die;
//                        if ($cartero->cartero_id  == 26 && $liquidacion_resumen_clientes[$i]->cliente_id ==151 && $liquidacion_resumen_clientes[$i]->servicio_id ==3140){echo $query_valor_especial_entrega;die;}
                        $valor_especial_entrega = $this->db->query($query_valor_especial_entrega)->row();
                        
                        $cantidad_piezas = 0;
                        $query_piezas =" SELECT SUM(lcd.cantidad_piezas) cantidad_piezas
                                                FROM flash_liquidaciones_carteros lc
                                                INNER JOIN flash_liquidaciones_carteros_detalles lcd ON lc.`id` = lcd.`liquidacion_cartero_id`
                                                WHERE lc.`id` = $liquidacion_cartero_id
                                                AND lcd.`cartero_id` = $cartero->cartero_id
                                                AND lcd.cliente_id = ".$liquidacion_resumen_clientes_array[$i]->cliente_id ."
                                                AND lcd.servicio_id = ". $liquidacion_resumen_clientes_array[$i]->servicio_id;
//                        if ($cartero->cartero_id  == 26 && $liquidacion_resumen_clientes[$i]->cliente_id ==3){echo $query_piezas;die;}
                        $piezas = $this->db->query($query_piezas)->row(); 
                        
                        if ($piezas->cantidad_piezas > 0){ $cantidad_piezas = $piezas->cantidad_piezas;}
                        if ($valor_especial_entrega != NULL){//echo "entra por valopr espeial por todo";die;//if ( $cartero->cartero_id) {echo "1";die;}
                            $this->excel->getActiveSheet()->setCellValue($columna++.$fila,$cantidad_piezas);
                            $col_aux = $columna++;
                            $this->excel->getActiveSheet()->getStyle($col_aux.$fila)->getNumberFormat()->setFormatCode($numformat);
                            $this->excel->getActiveSheet()->setCellValue($col_aux.$fila,$valor_especial_entrega->valor_especial_entrega); 
                            $total_a_pagar += $cantidad_piezas * $valor_especial_entrega->valor_especial_entrega;
                            //$piezas_valor[$valor_especial_entrega->valor_especial_entrega] = $cantidad_piezas;
                            $piezas_valor_array[] = array("valor" => $valor_especial_entrega->valor_especial_entrega,"cantidad" => $cantidad_piezas);
                        }else{//if ( $cartero->cartero_id) {echo "2";die;}
                            //Si no tiene valor especial por cliente y cartero me fijo si tiene valor especial solo por cliente
                            $query_valor_entrega = "SELECT ve.cliente_id, c.nombre cliente, ve.pago valor_entrega
                                                FROM flash_ddis_valores_entregas ve     
                                                JOIN flash_clientes c ON c.id = ve.cliente_id
                                                WHERE ve.habilitado = 1
                                                AND ve.cartero_id = 0 
                                                AND ve.cliente_id = ".$liquidacion_resumen_clientes_array[$i]->cliente_id."
                                                AND ve.servicio_id = ". $liquidacion_resumen_clientes_array[$i]->servicio_id."
                                                AND ve.zona_id = ".$cartero->zona_id;
                            $valor_entrega = $this->db->query($query_valor_entrega)->row(); 
                           
                            $valor = 0;
                            //Si tampoco tiene precio especial por cliente entonces pongo el precio predeterminado.
                        if ($valor_entrega->valor_entrega == NULL || $valor_entrega->valor_entrega == '' ){//echo "entra por valopr predetermina";die;//if ( $cartero->cartero_id) {echo "3";die;}
                            $valor = $predeterminado->valor;//if ( $cartero->cartero_id==456) {echo "1 ".$valor;die;}
                        } else{
                            $valor = $valor_entrega->valor_entrega;//echo "entra por valopr entrega";die;//if ( $cartero->cartero_id==456) {echo "3 ". $valor;die;}
                        }
                                $col_aux = $columna++;
                                $this->excel->getActiveSheet()->setCellValue($col_aux.$fila,$cantidad_piezas);
                                $col_aux = $columna++;
                                $this->excel->getActiveSheet()->getStyle($col_aux.$fila)->getNumberFormat()->setFormatCode($numformat);
                                $this->excel->getActiveSheet()->getColumnDimension($col_aux)->setWidth($col_width);
                                $this->excel->getActiveSheet()->setCellValue($col_aux.$fila,$valor);
                                $total_a_pagar += $cantidad_piezas * $valor;
                                $piezas_valor_array[] = array("valor" => $valor, "cantidad" => $cantidad_piezas);
                                //if ( $cartero->cartero_id==456) {echo "3 ". var_dump($piezas_valor_array);die;}
                        }
                        $piezas_clientes += $cantidad_piezas;
                        $cartero_id = $value->cartero_id;
                        }
                    }
                    
                    //if ($cartero->cartero_id = 456) {var_dump($piezas_valor_array);die;}
                    $this->excel->getActiveSheet()->setCellValue($columna++.$fila,$total_piezas->cantidad_piezas);
                    $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth($col_width);
                    $this->excel->getActiveSheet()->setCellValue("C".$fila,$total_piezas->cantidad_piezas - $piezas_clientes);
                    $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth($col_width);
                    $this->excel->getActiveSheet()->getStyle("D".$fila)->getNumberFormat()->setFormatCode($numformat);
                    $this->excel->getActiveSheet()->setCellValue("D".$fila,$predeterminado->valor);
                    

                    $total_a_pagar += ($total_piezas->cantidad_piezas - $piezas_clientes)*$predeterminado->valor;
                    //$piezas_valor[$predeterminado->valor] = $total_piezas->cantidad_piezas - $piezas_clientes;
                    $piezas_valor_array[] = array("valor" =>$predeterminado->valor,"cantidad" => $total_piezas->cantidad_piezas - $piezas_clientes);
                    $col_aux = $columna++;
                    $this->excel->getActiveSheet()->getColumnDimension($col_aux)->setWidth(6,14);
                    $this->excel->getActiveSheet()->setCellValue($col_aux.$fila,$productividad->productividad);
                    //Traigo las acreditaciones
                    $query_acreditaciones = "SELECT (CASE WHEN SUM(importe) IS NULL THEN 0 ELSE SUM(importe) END ) AS importe
                                        FROM flash_ddis_acreditaciones
                                        WHERE cartero_id = $cartero->cartero_id";
                    $acreditaciones = $this->db->query($query_acreditaciones)->row(); 
                    $col_aux = $columna++;
                    $this->excel->getActiveSheet()->getStyle($col_aux.$fila)->getNumberFormat()->setFormatCode($numformat);
                    $this->excel->getActiveSheet()->setCellValue($col_aux.$fila,$acreditaciones->importe);
                    //Traigo los descuentos
                    $query_descuentos = "SELECT (CASE WHEN SUM(importe) IS NULL THEN 0 ELSE SUM(importe) END ) AS importe 
                                        FROM flash_ddis_descuentos
                                        WHERE cartero_id = $cartero->cartero_id";
                    $descuentos = $this->db->query($query_descuentos)->row(); 
                    $col_aux = $columna++;
                    $this->excel->getActiveSheet()->getStyle($col_aux.$fila)->getNumberFormat()->setFormatCode($numformat);
                    $this->excel->getActiveSheet()->setCellValue($col_aux.$fila,$descuentos->importe);
                    //Calculo el total a pagar
                    ////////ORDENO EL ARRAY piezas_valor_array
                    //if($cartero->cartero_id == 456){
                    //var_dump ($piezas_valor_array);
                    // if($cartero->cartero_id == 456) {var_dump($piezas_valor_array);die;}

                    foreach ($piezas_valor_array as $key => $row) {
                        $aux[$key] = $row['valor'];
                    }
                    
                    //array_multisort($aux, SORT_ASC, $piezas_valor_array);
                   // var_dump($this->build_sorter('valor'));
                   usort($piezas_valor_array, $this->build_sorter('valor'));
                    
                  //  if($cartero->cartero_id == 456) {var_dump($piezas_valor_array);die;}

                    //ksort($piezas_valor);
                    /*var_dump($piezas_valor_array);die;
                    uasort($piezas_valor_array, 'sort_by_orden');
                        function sort_by_orden ($a, $b) {
                            return $a['orden'] - $b['orden'];
                        }*/
                    $suma = 0;
                    $primera_pasada = true;
                    
                    ////////Productividad MAYOR que CERO
                    //echo $productividad->productividad."</br>";
                        if ($productividad->productividad > 0){
                            foreach ($piezas_valor_array as $key => $value) {//echo $value['cantidad']."<br/>";//if ( $cartero->cartero_id == 456) {echo $key."     ".var_dump($value)."<br>";}
                                if ( $suma < $productividad->productividad){
                                    $suma += $value['cantidad'];//echo $cartero->cartero_id."....". $suma."<br>";
                                }else{
                                    //if ( $cartero->cartero_id == 456) {echo $suma."<br>";}
                                    if($suma >= $productividad->productividad && $primera_pasada){
                                        $valor = $value['valor'];
                                        $cantidad = $suma - $productividad->productividad;
                                        $total_a_pagar = $valor * $cantidad;
                                        $total_a_pagar += $valor * $value['cantidad'];
                                        $primera_pasada = false;
                                        //if ( $cartero->cartero_id == 456) {echo "primera pasada total a pagar ". $total_a_pagar."<br>";}
                                    }else{
                                        $total_a_pagar += (float)$value['valor'] * (float)$value['cantidad'];
                                        //echo (float)$value['valor'] * (float)$value['cantidad'];die;
                                        //if ( $cartero->cartero_id == 456) {echo "segundas pasadas total a pagar ".$value['valor']." * ".$value['cantidad']."  ". $total_a_pagar."<br>";}
                                      // if ( $cartero->cartero_id == 456) {echo count($piezas_valor_array)."<br>";echo $suma."..8888888888..".$productividad->productividad."<br>";}die;
                                    }
                                }
                                //if ( $cartero->cartero_id == 456) {var_dump($piezas_valor_array);echo $suma."....".$productividad->productividad."<br>";}
                               // if ( $cartero->cartero_id == 456) {echo $valor ."  ---   ".$cantidad ."  ---   ".$total_a_pagar ."<br>" ; }
                            }
                             //if ( $cartero->cartero_id == 456) {var_dump($piezas_valor_array);echo $suma."....".$productividad->productividad."<br>";}
                            if ($suma <= $productividad->productividad){
                                $total_a_pagar = 0;
                            }
                        }
                        //if ( $cartero->cartero_id == 456) {echo "FINAL ". $total_a_pagar;die;}
                        //if ( $cartero->cartero_id == 737) {var_dump($piezas_valor_array);echo "FINAL ". $total_a_pagar;die;}
                    ///////FIN PROD MAYOR QUE CERO
                    $col_aux = $columna++;
                    $this->excel->getActiveSheet()->getStyle($col_aux.$fila)->getNumberFormat()->setFormatCode($numformat);
                    $this->excel->getActiveSheet()->getStyle($col_aux.$fila)->getNumberFormat()->setFormatCode("$#,##0.00");
                    $this->excel->getActiveSheet()->setCellValue($col_aux.$fila,($total_a_pagar + $acreditaciones->importe - $descuentos->importe));
                    $this->excel->getActiveSheet()->setCellValue($columna.$fila,$cartero->observaciones);
                    $fila++;
                    $sheet = $this->excel->setActiveSheetIndex(4); 
                    $highestRow = $sheet->getHighestDataRow("A");
                }
                $filas = 0;
                $celda = "";
                
                $filas++;
                for($x=1; $x<$highestRow; $x++){
                    //echo "mmm  ".'A'.$x."   ". $this->excel->getActiveSheet()->getCell('A'.$x)->getValue()." </br>";
                    if ($this->excel->getActiveSheet()->getCell('A'.$x)->getValue() != "") {
                        $filas++;
                        $celda = "A".$x;
                       // $col_sum += $this->excel->getActiveSheet()->getCell('C'.$x)->getValue();
                    }
                }
                //die;
                
                $sheet->setCellValue("C".($filas+1), "=SUM(C2:C".($filas).")");
                $sheet->getStyle("D".($filas+1))->getNumberFormat()->setFormatCode("$#,##0.00");
                $sheet->setCellValue("D".($filas+1), "=SUM(D2:D".($filas).")");
                //echo $this->excel->getActiveSheet()->getCell($x.'1')->getValue()." </br>";
                for($x = "G"; $this->excel->getActiveSheet()->getCell($x.'1')->getValue() != "Total a Pagar" ; $x++){
                    //echo $x."<br/>";
                    if ($this->excel->getActiveSheet()->getCell($x."1")->getValue() != "") {
                        $sheet->setCellValue($x.($filas+1),"=SUM(".$x."2:".$x."".($filas).")");
                        $x++;
                        $sheet->getStyle($x.($filas+1))->getNumberFormat()->setFormatCode("$#,##0.00");
                        $sheet->setCellValue($x.($filas+1),"=SUM(".$x."2:".$x."".($filas).")");
                        
                    }
                }
                $x--;
                $sheet->getStyle($x.($filas+1))->getNumberFormat()->setFormatCode("$#,##0.00");
                $sheet->setCellValue($x.($filas+1), "=SUM(".$x."2:".$x."".($filas).")");
                
                //Doy el formato de grilla a los resultados
                $x++;$filas++;
                $this->excel->getActiveSheet()->getStyle("A1:".$x.$filas)->applyFromArray($styleArray);
                $this->excel->getActiveSheet()->getStyle("A1:".$x."1")->applyFromArray($styleArray1);
                $this->excel->getActiveSheet()->getStyle($x."1:".$x.$filas)->applyFromArray($styleArray1);
                
                
            $this->excel->setActiveSheetIndex(0);
            $filename='Info_DDIs.xls'; //save our workbook as this file name

            header('Content-Type: application/vnd.ms-excel'); //mime type

            header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name

            header('Cache-Control: max-age=0'); //no cache

            //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
            //if you want to save it as .XLSX Excel 2007 format

            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5'); 

            //force user to download the Excel file without writing it to server's HD
            $objWriter->save('php://output');
    }

    function getLiquidacionesAutorizadores($id = null){
        $where = $id == null?" ":" WHERE la.id = ".$id;
        $query = "  SELECT la.id , u.id usuario_id, u.sucursal_id, u.nombre,u.apellido 
                            FROM flash_ddis_liquidaciones_autorizadores la
                            INNER JOIN users u ON  la.user_id = u.id
                             $where";
        
        return $this->db->query($query)->result();
    }
    
    function getLiquidacionesDDis($id = null){
        $where = $id == null?" ":" WHERE l.id = ".$id;
        $query = " SELECT 
                    l.*,
                    s.nombre sucursal
                  FROM  flash_liquidaciones_carteros l
                  INNER JOIN flash_sucursales s ON l.`sucursal_id` = s.`id`
                             $where";
        
        return $this->db->query($query)->result();
    }
    
    function getLiquidacionesHdrsDetalles($id = null){
        //$where = $id == null?" ":" AND la.id = ".$id;
        $query = "  SELECT  hdrsd.hoja_de_ruta_id hdr_id,
                            hdrsd.servicio_nombre servicio, 
                            hdrsd.precio_unitario precio_cliente, 
                            c.nombre,sc.apellido_nombre,
                            hdrsd.* 
                    FROM `flash_liquidaciones_carteros_detalles` hdrsd
                    INNER JOIN flash_clientes c ON c.id = hdrsd.cliente_id
                    INNER JOIN flash_sucursales_carteros sc ON sc.id = hdrsd.cartero_id
                    INNER JOIN flash_servicios s ON s.`id` = hdrsd.`servicio_id`
                    WHERE liquidacion_cartero_id = ".$id." "
                ///. $where
                ;

        return $this->db->query($query)->result();
    }
    
    function saveLiquidacionesHdrsDetalles($liquidacion_ddi_id,$liquidacion_ddi_autorizador_id,$desde, $hasta,$alta_desde,$alta_hasta, $sucursal_id){
        
        
        //Borro el detalle de HDRs de esta liquidacion
        $delete = $this->deleteLiquidacionesDdisHdrsDetalles($liquidacion_ddi_id);
        //Traigo la misma consulta mostrada en pantalla, desde el servidor
        $results = $this->flash_md->getCarterosPendientesLiquidar($desde, $hasta,$alta_desde,$alta_hasta, $sucursal_id);
        $i = 0;
        $user_row = $this->ion_auth->user()->row();
        foreach ($results as $value) {
            $data_detalle[$i] = array(
                    'liquidacion_ddi_id' => $liquidacion_ddi_id,
                    'cartero_id' => $value->cartero_id,
                    'cliente_id' => $value->cliente_id,
                    'hoja_de_ruta_id' => $value->hdr_id,
                    'autorizador_id' => $liquidacion_ddi_autorizador_id,
                    'servicio_id' => $value->codigo_servicio,
                    'servicio_nombre' => $value->servicio,
                    'estado_id' => $value->estado_id,
                    'estado_nombre' => $value->estado_nombre,
                    'cantidad_piezas' => $value->cantidad_piezas,
                    'precio_unitario' => $value->precio_cliente,
                    'observaciones' => $value->observaciones,
                    'create' =>  date('Y-m-d H:m:s'),
                    'create_user_id' =>  $user_row->id,
            );
            $i++;
        }
        $result = $this->db->insert_batch('flash_liquidaciones_ddis_hdrs_detalles', $data_detalle);
//        echo $this->db->last_query();die;
        return $result;
    }
    
    function saveEditLiquidacionesHdrsDetalles($liquidacion_ddi_id,$liquidacion_ddi_autorizador_id,$desde, $hasta,$alta_desde,$alta_hasta, $sucursal_id){
        
        
        //Borro el detalle de HDRs de esta liquidacion
        $delete = $this->deleteLiquidacionesDdisHdrsDetalles($liquidacion_ddi_id);
        //Traigo la misma consulta mostrada en pantalla, desde el servidor
        $results = $this->flash_md->getCarterosPendientesLiquidar($desde, $hasta,$alta_desde,$alta_hasta, $sucursal_id);
        $i = 0;
        foreach ($results as $value) {
            $data_detalle[$i] = array(
                    'liquidacion_ddi_id' => $liquidacion_ddi_id,
                    'cartero_id' => $value->cartero_id,
                    'cliente_id' => $value->cliente_id,
                    'hoja_de_ruta_id' => $value->hdr_id,
                    'autorizador_id' => $liquidacion_ddi_autorizador_id,
                    'servicio_id' => $value->codigo_servicio,
                    'servicio_nombre' => $value->servicio,
                    'estado_id' => $value->estado_id,
                    'estado_nombre' => $value->estado_nombre,
                    'cantidad_piezas' => $value->cantidad_piezas,
                    'precio_unitario' => $value->precio_cliente,
                    'observaciones' => $value->observaciones,
                    'update' =>  date('Y-m-d H:m:s'),
                    'update_user_id' =>  $user_row->id,
            );
            $i++;
        }
        $result = $this->db->insert_batch('flash_liquidaciones_ddis_hdrs_detalles', $data_detalle);
//        echo $this->db->last_query();die;
        return $result;
    }
    
    function saveLiquidacionesAcreditacionesDetalles($id = null){
        $where = $id == null?" ":" WHERE la.id = ".$id;
        $query = "  SELECT la.id , u.id usuario_id, u.sucursal_id, u.nombre,u.apellido, date_format(la.fecha_autorizacion,'%d-%m-%Y') fecha_autorizacion 
                            FROM flash_liquidaciones_ddis_autorizadores la
                            INNER JOIN users u ON  la.user_id = u.id
                            .$where";
                $autorizadores = $this->db->query($query)->result();
                
        return $this->db->query($query)->result();
    }
    
    function saveLiquidacionesDescuentosDetalles($id = null){
        $where = $id == null?" ":" WHERE la.id = ".$id;
        $query = "  SELECT la.id , u.id usuario_id, u.sucursal_id, u.nombre,u.apellido, date_format(la.fecha_autorizacion,'%d-%m-%Y') fecha_autorizacion 
                            FROM flash_liquidaciones_ddis_autorizadores la
                            INNER JOIN users u ON  la.user_id = u.id
                            .$where";
                $autorizadores = $this->db->query($query)->result();
                
        return $this->db->query($query)->result();
    }
    
    function deleteLiquidacionesDdisHdrsDetalles($liquidacion_ddi_id){
        $this->codegen_model->delete('flash_liquidaciones_ddis_hdrs_detalles','liquidacion_ddi_id',$liquidacion_ddi_id);
    }
}

/* End of file liquidaciones_ddis.php */
/* Location: ./system/application/controllers/liquidaciones_ddis.php */