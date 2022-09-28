<?php

class Liquidaciones extends MY_Controller {
	
	function __construct() {
		parent::__construct();
		$this->permisos = $this->permisos_lib->control();
	}	
	
	function index(){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
                        'clientes' => $this->codegen_model->get('flash_clientes','*',''),
                        'departamentos' => $this->codegen_model->get('flash_clientes_departamentos','*',''),
                        'sucursales' => $this->codegen_model->get('flash_sucursales','*',''),
			'liquidaciones' => array()//$this->codegen_model->get('flash_liquidaciones','*','')
		);

		$vista_externa = array(			
			'title' => ucwords("Liquidaciones"),
			'contenido_main' => $this->load->view('components/liquidaciones/liquidaciones_list', $vista_interna, true)
		);		
		
		$this->load->view('template/backend', $vista_externa);
	}

        public function filtro()
        {
            $cliente_id = $this->input->post('cliente_id');
            $departamento_id = $this->input->post('departamento_id');
            $sucursal_id = $this->input->post('sucursal_id');
            $fecha_desde = $this->input->post("fecha_desde") != ''?formatdate($this->input->post("fecha_desde")):'';
            $fecha_hasta = $this->input->post("fecha_hasta") != ''?formatdate($this->input->post("fecha_hasta")):'';
//            echo $fecha_desde."  ".$fecha_hasta;
//            var_dump($_POST);
            $query = Liquidacion::orderBy('create','asc');

            if($cliente_id != '')
                $query->where('cliente_id', $cliente_id);

            if($this->is_user())
                $query->where('sucursal_id', $this->usuario->sucursal_id);

            if($sucursal_id != '')
                $query->where('sucursal_id', $sucursal_id);

            if($departamento_id != '')
                $query->where('departamento_id', $departamento_id);

            if($fecha_desde != '')
                $query->where('create','>=', $fecha_desde.' 00:00:00');

            if($fecha_hasta != '')
                $query->where('create','<=', $fecha_hasta.' 23:59:59');
           
            //var_dump($query->toSql());

            $liquidaciones = $query->get();
//             echo $this->db->last_query();die;
            $this->data([
                'liquidaciones' => $liquidaciones,
                'fecha_desde' => $fecha_desde? $fecha_desde : FALSE,
                'fecha_hasta' => $fecha_hasta? $fecha_hasta : FALSE,
                //'empresa_id' => $empresa_id ? $empresa_id : Empresa::whereDefault(1)->first()->id,
                'cliente_id' => $cliente_id ? $cliente_id : false,
                'departamento_id' => $departamento_id ? $departamento_id : false,
                'sucursal_id' => $sucursal_id ? $sucursal_id : false,
                //'empresas' => Empresa::all(),
                'clientes' => Cliente::where('cliente_estado_id',Cliente::ESTADO_ACTIVO)->get(),
                'departamentos' => ClienteDepartamento::all(),
                'sucursales' => $this->codegen_model->get('flash_sucursales','*',''),
            ]);

            $this->title('Liquidaciones');
            $this->view = 'components/liquidaciones/liquidaciones_list';
            $this->template('template/backend');
        }

	function add(){
	    
	    
		if ($this->input->post('enviar_form')){
                    //Graba el encabezado
                        $liquidacion_nro = $this->codegen_model->row('flash_liquidaciones','max(nro) as numero', '1 = 1');
                        $date_desde = formatdate($this->input->post('periodo_desde'));
                        $date_hasta = formatdate($this->input->post('periodo_hasta'));
                        $departamento_id = $this->input->post('departamento_id');
                        $sucursal_id = $this->input->post('sucursal_id');
                        $cliente_id = $this->input->post('cliente_id');
			$data = array(
					'usuario_id' => $this->usuario->id,
					'cliente_id' => $cliente_id,
					'departamento_id' => $departamento_id,
                                        'sucursal_id' => $sucursal_id,
					'nro' => $liquidacion_nro->numero + 1,
					'factura' => $this->input->post('factura'),
					'periodo_desde' => $date_desde,
					'periodo_hasta' => $date_hasta,
					'obeservaciones' => $this->input->post('obeservaciones'),
					'estado_id' => $this->input->post('estado_id'),
                                        'iva' => $this->input->post('iva'),
				);
			$liquidacion_id = $this->codegen_model->add('flash_liquidaciones',$data);
                        //Graba el detalle
                        //Traigo los datos del detalle segun el encabezado que se FILTRA
                        $liquidaciones = $this->flash_md->getPiezasPendientesLiquidar($date_desde, $date_hasta, $departamento_id,$sucursal_id, $cliente_id);
                        //echo "El total de filas es: ". count($liquidaciones)."<br
                        foreach ($liquidaciones as $value) {
                            //Le agregue el servicio_id hay que fijarse que grabe y usarlo como consulta en la pantalla de edicion
                            $data_detalle = array(
                                    'liquidacion_id' => $liquidacion_id,
                                    'comprobante_ingreso' => $value->numero,
                                    'remito_cliente' => $value->remito,
                                    'fecha' => formatdate($value->fecha),
                                    'servicio_id' => $value->servicio_id,
                                    'servicio' => $value->servicio,
                                    'cantidad' => $value->cantidad,
                                    'precio' => $value->precio,
                                    'create' =>  date('Y-m-d H:m:s'),
                            );

                            $this->codegen_model->add('flash_liquidaciones_detalles',$data_detalle);
                            $comprobante_ingresos = Comprobante::whereNumero($value->numero)->get();
                            //echo "El total de comprobantes_ingresos es: ".count($comprobante_ingresos)."<br>";
                            foreach ($comprobante_ingresos as $value_ingreso) {
                                $value_ingreso->liquidacion_id = $liquidacion_id;
                                $value_ingreso->save();   
                            }
                        }
                        //echo "El total de detalles procesados es: ". $cont."<br/>";die;
                        //$this->db->insert_batch('flash_liquidaciones_detalles', $data_detalle);
                        $this->session->set_flashdata('registro', $liquidacion_id);
                       redirect(base_url().'liquidaciones/liquidaciones');
		}		  
   
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
                        'clientes' => $this->flash_md->getClientes(Cliente::ESTADO_ACTIVO,  2),
                        'departamentos' => $this->codegen_model->get('flash_clientes_departamentos','distinct(nombre)',''),
                        'sucursales' => $this->codegen_model->get('flash_sucursales','*',''),
		);

		$vista_externa = array(			
			'title' => ucwords("Liquidaciones"),
			'contenido_main' => $this->load->view('components/liquidaciones/liquidaciones_add', $vista_interna, true)
		);		  
   
		$this->load->view('template/backend', $vista_externa);
	}	

	function edit($id){
	    
	    
		if ($this->input->post('enviar_form')){
			$data = array(
					'factura' => $this->input->post('factura'),
					'update' => date('Y-m-d H:m:s')
				);
			$this->codegen_model->edit('flash_liquidaciones',$data,'id',$id);
			redirect(base_url().'liquidaciones/liquidaciones');
		}

                $result =  $this->codegen_model->row('flash_liquidaciones','*','id = '.$id);

		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
                        'clientes' => $this->flash_md->getClientes(Cliente::ESTADO_ACTIVO,  2),
			'result' => $result,
                        'detalles' => $this->codegen_model->get('flash_liquidaciones_detalles','*','liquidacion_id = '.$id),
                        'departamentos' => $this->codegen_model->get('flash_clientes_departamentos','*','cliente_id = '.$result->cliente_id),
                        'sucursales' => $this->codegen_model->get('flash_sucursales','*','')
		);

		$vista_externa = array(			
			'title' => ucwords("Liquidaciones"),
			'contenido_main' => $this->load->view('components/liquidaciones/liquidaciones_edit', $vista_interna, true)
		);		  
   
		$this->load->view('template/backend', $vista_externa);
	}

	function view($id){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'result' => $this->codegen_model->row('flash_liquidaciones','*','id = '.$id)
		);

		$vista_externa = array(			
			'title' => ucwords("Liquidaciones"),
			'contenido_main' => $this->load->view('components/liquidaciones/liquidaciones_view', $vista_interna, true)
		);		  
   
		$this->load->view('template/view', $vista_externa);
	}

	function json($id){
		$json = $this->codegen_model->row('flash_liquidaciones','*','id = '.$id);
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}

	function json_all(){
		$json = $this->codegen_model->get('flash_liquidaciones','*','');
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}
	
	function delete($id){
		$this->codegen_model->delete('flash_liquidaciones','id',$id);             
	}
        
        function anular($id){
            
            
            $comprobantes = $this->codegen_model->get('flash_comprobantes_ingresos','*','liquidacion_id = '.$id);
            $liquidación = Liquidacion::whereId($id)->first();
            $liquidación->estado_id = 2; //2= Anulada
            $liquidación->save();
            foreach ($comprobantes as $value) {echo $value->numero."<br/>";
                $data = array(
                                'liquidacion_id' => 0,
                                'update' =>  date('Y-m-d H:m:s'),
                            );
                $this->codegen_model->edit('flash_comprobantes_ingresos',$data,'numero',$value->numero);
            }
	}

        function exportarAPdf() {
             $liquidacion_nro = $this->input->post('pdf_liquidacion_nro');
             $liquidacion_id = $this->input->post('pdf_liquidacion_id');
             $cliente_id = $this->input->post('pdf_cliente_id');
             $factura_nro = $this->input->post('pdf_factura_nro');
             $periodo_desde = $this->input->post('pdf_periodo_desde') != ''?$this->input->post('pdf_periodo_desde'):false;
             $periodo_hasta = $this->input->post('pdf_periodo_hasta') != ''?$this->input->post('pdf_periodo_hasta'):false;
             $sucursal = $this->input->post('pdf_sucursal');
             $departamento = $this->input->post('pdf_departamento');
             $sucursal_id = $this->input->post('pdf_sucursal_id');
             $departamento_id = $this->input->post('pdf_departamento_id');
             $iva_puro = $this->input->post('pdf_iva_puro');
             //$cantidad_elementos = $this->input->post('cantidad_elementos');
             $imprimir = $this->input->post('imprimir');
            if($periodo_desde) $date_desde = new DateTime($periodo_desde);
            if($periodo_hasta) $date_hasta = new DateTime($periodo_hasta);

             if ($imprimir == "facturab"){
                 $facturab = $this->flash_md->getLiquidacionEdit($liquidacion_id);
                 $html_encabezado = '<table class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Comprobante ing.</th>
                                                            <th>Remito Cliente</th>
                                                            <th>Fecha</th>
                                                            <th>Servicio</th>
                                                            <th>Cantidad</th>
                                                            <th>Precio</th>
                                                            <th>Importe</th>
                                                        </tr>
                                                    </thead>
                                    <tbody id="body-grilla">';
                $suma_total = 0;
                $iva = 0.21;//$('#iva').val();
                //$('#cantidad_elementos').val(data['grilla'].length);

                foreach ($facturab['grilla'] as $value) {
                      $precio_con_iva = $value->precio * $iva + $value->precio;
                      $importe = $precio_con_iva * $value->cantidad;
                      $html_cuerpo    .= '<tr>
                                            <td>'.$value->numero.'</td>
                                            <td>'.$value->remito.'</td>
                                            <td>'.$value->fecha.'</td>
                                            <td>'.$value->servicio.'</td>
                                            <td>'.$value->cantidad.'</td>
                                            <td>'.number_format ( $precio_con_iva, 3).'</td>
                                            <td>'.number_format($importe,3).'</td>
                                        </tr>';
                    // }

                     $contador++;
                     $suma_total = $suma_total + $importe;
                 };
                 $html_cierre = '</tbody></table>';
                 $html_total = '<tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><strong>Total</strong></td>
                                <td><strong>'.number_format($suma_total,3).'</strong></td>
                            </tr>';
                 
                 $html_pdf_detalle = $html_encabezado.$html_cuerpo.$html_total.$html_cierre; 
                 
                 $html_cabecera_resumen = '<table class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered" style="width:50%">
                                                    <thead>
                                                        <tr>
                                                            <th>Código</th>
                                                            <th>Servicio</th>
                                                            <th>Cantidad</th>
                                                        </tr>
                                                    </thead>';
                                 $html_body_resumen = '<tbody id="body-grilla-resumen">';
                                 foreach ($facturab['resumen'] as $value) {
                                     
                                      $html_body_resumen    .= '<tr id="" class="nueva">
                                                            <td>'.$value->codigo.'</td>
                                                            <td>'.$value->servicio.'</td>
                                                            <td>'.$value->cantidad.'</td>
                                                        </tr>';
                                 };
                                 $html_cierre_resumen = '</tbody></table>';
                                 $html_pdf_resumen = ($html_cabecera_resumen.$html_body_resumen.$html_cierre_resumen);

                 
             }

             if ($imprimir == "facturaa"){
                 $facturaa = $this->flash_md->getLiquidacionEdit($liquidacion_id);
                 $html_encabezado = '<table class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Comprobante ing.</th>
                                                            <th>Remito Cliente</th>
                                                            <th>Fecha</th>
                                                            <th>Servicio</th>
                                                            <th>Cantidad</th>
                                                            <th>Precio</th>
                                                            <th>Importe</th>
                                                        </tr>
                                                    </thead>
                                    <tbody id="body-grilla">';
                $suma_total = 0;
                $iva = 1.21;//$('#iva').val();
                foreach ($facturaa['grilla'] as $value) {
                      $precio = $value->precio;
                      $importe = $precio * $value->cantidad;
                      $html_cuerpo    .= '<tr>
                                            <td>'.$value->numero.'</td>
                                            <td>'.$value->remito.'</td>
                                            <td>'.$value->fecha.'</td>
                                            <td>'.$value->servicio.'</td>
                                            <td>'.$value->cantidad.'</td>
                                            <td style="text-align:right">'.number_format ( $precio, 3).'</td>
                                            <td style="text-align:right">'.number_format($importe,3).'</td>
                                        </tr>';
                    // }

                    $contador++;
                    $suma_total = $suma_total + $importe;
                    //$iva = $iva + $importe * $iva_puro;
                 };
                 $total_iva = $suma_total * $iva;
                 $iva_solo = $total_iva - $suma_total;
                 $html_cierre = '</tbody></table>';
                 $html_total = '<tr>
                                               <td class=""></td>
                                                <td class=""></td>
                                                <td class=""></td>
                                                <td class=""></td>
                                                <td class=""></td>
                                                <td class=""><strong>Sub-Total:</strong></td>
                                                <td style="text-align:right"><strong>'.number_format($suma_total,3).'</strong></td>
                                            </tr>
                                            <tr>
                                                <td class=""></td>
                                                <td class=""></td>
                                                <td class=""></td>
                                                <td class=""></td>
                                                <td class=""></td>
                                                <td class=""><strong>IVA:</strong></td>
                                                <td style="text-align:right"><strong>'.number_format($iva_solo,3).'</strong></td>
                                            </tr>
                                            <tr>
                                                <td class=""></td>
                                                <td class=""></td>
                                                <td class=""></td>
                                                <td class=""></td>
                                                <td class=""></td>
                                                <td class=""><strong>Total:</strong></td>
                                                <td style="text-align:right"><strong>'.number_format($total_iva,3).'</strong></td>
                                            </tr>';
                                 //$('#grilla').append(cabecera+body+total+cierre); 
                 
                 $html_pdf_detalle = $html_encabezado.$html_cuerpo.$html_total.$html_cierre; 
                 
                 $html_cabecera_resumen = '<table class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered" style="width:50%">
                                                    <thead>
                                                        <tr>
                                                            <th>Código</th>
                                                            <th>Servicio</th>
                                                            <th>Cantidad</th>
                                                        </tr>
                                                    </thead>';
                                 $html_body_resumen = '<tbody id="body-grilla-resumen">';
                                 foreach ($facturaa['resumen'] as $value) {
                                     
                                      $html_body_resumen    .= '<tr id="" class="nueva">
                                                            <td>'.$value->codigo.'</td>
                                                            <td style="width:50%">'.$value->servicio.'</td>
                                                            <td style="text-align:right">'.$value->cantidad.'</td>
                                                        </tr>';
                                 };
                                 $html_cierre_resumen = '</tbody></table>';
                                 $html_pdf_resumen = ($html_cabecera_resumen.$html_body_resumen.$html_cierre_resumen);
                 
             }
             if ($imprimir == "liquidacionx"){}
             if ($imprimir == "facturaa"){}
             if ($imprimir == "pendientes"){}
             if ($imprimir == "clientespendientes"){}
             
             $this->load->library('Pdf'); 
             $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false); 
             //$pdf->Header($sucursal, $liquidacion_nro);
             $pdf->setSucursal($sucursal);
             $pdf->SetLiquidacionNro($liquidacion_nro);
             $pdf->SetCliente(Cliente::whereId($cliente_id)->first()->nombre);
             $pdf->SetFacturaNro($factura_nro);
             $pdf->SetPeriodoDesde($periodo_desde);
             $pdf->SetPeriodoHasta($periodo_hasta);
             $pdf->SetDepartamento($departamento);
             
             $pdf->SetTitle('Liquidaciones'); 
             $pdf->SetHeaderMargin(300); 
             $pdf->SetTopMargin(200); 
             $pdf->setFooterMargin(20); 
             $pdf->SetAutoPageBreak(true); 
             $pdf->SetAuthor('Author'); 
             $pdf->SetDisplayMode('real', 'default'); 
             $pdf->Write(5, 'CodeIgniter TCPDF Integration'); 
             // add a page 
             
             // set default header data
            
            $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128),  
                                $liquidacion_nro, Cliente::whereId($cliente_id)->first()->nombre, $factura_nro, $periodo_desde, $periodo_hasta, $sucursal,$departamento);
            $pdf->setFooterData(array(0,64,0), array(0,64,128));

            // set header and footer fonts
            $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
            $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

            // set default monospaced font
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

            // set margins
            $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP+30, PDF_MARGIN_RIGHT);
            $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
            $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

            // set auto page breaks
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

            // set image scale factor
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

            // set some language-dependent strings (optional)
            if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
                require_once(dirname(__FILE__).'/lang/eng.php');
                $pdf->setLanguageArray($l);
            }

            // ---------------------------------------------------------

            // set default font subsetting mode
            $pdf->setFontSubsetting(true);

            // Set font
            // dejavusans is a UTF-8 Unicode font, if you only need to
            // print standard ASCII chars, you can use core fonts like
            // helvetica or times to reduce file size.
            $pdf->SetFont('dejavusans', '', 8, '', true);

            // Add a page
            // This method has several options, check the source code documentation for more information.
            $pdf->AddPage();
       
//           echo $html_pdf_detalle."<br/>".$html_pdf_resumen;die;
             $pdf->writeHTML($html_pdf_detalle."<br/>".$html_pdf_resumen, true, false, true, false, ''); 
             
             $pdf->Output('liquidacion .pdf', 'I');
        }
        
         function exportarAPdf_old() {
             $htmlLiquidacionSinIva = $this->input->post('htmlLiquidacionSinIva').'<br/><br/>';
             $htmlResumenLiquidacionSinIva = $this->input->post('htmlResumenLiquidacionSinIva');
             $liquidacion_nro = $this->input->post('pdf_liquidacion_nro');
             $cliente_id = $this->input->post('pdf_cliente_id');
             $factura_nro = $this->input->post('pdf_factura_nro');
             $periodo_desde = $this->input->post('pdf_periodo_desde');
             $periodo_hasta = $this->input->post('pdf_periodo_hasta');
             $sucursal = $this->input->post('pdf_sucursal');
             $cantidad_elementos = $this->input->post('cantidad_elementos');
             $departamento = $this->input->post('pdf_departamento');
             $this->load->library('simplehtmldom_1_5/simple_html_dom'); 
             $htmlDetalle = str_get_html($htmlLiquidacionSinIva);
             $htmlDetalle->find('table',0)->class = '';
             $htmlDetalle->find('table',0)->style = 'font-size:9px';
             $htmlDetalle->find('table',0)->border = '1px';
             //Modifico las cabeceras
             $htmlDetalle->find('th',0)->style = 'text-align:center;width:15%';
             $htmlDetalle->find('th',1)->style = 'text-align:center;width:15%';
             $htmlDetalle->find('th',2)->style = 'text-align:center;width:10%';
             $htmlDetalle->find('th',3)->style = 'text-align:center;width:30%';
             $htmlDetalle->find('th',4)->style = 'text-align:center;width:10%';
             $htmlDetalle->find('th',5)->style = 'text-align:center;width:10%';
             $htmlDetalle->find('th',6)->style = 'text-align:center;width:10%';
             //Modifico los registros
             foreach ($htmlDetalle->find('tr') as $tr) {
                // var_dump($tr);
                 if ($tr->find('td',2)->outertext != NULL){
                    $tr->style = 'vertical-align:middle';
                    $tr->find('td',0)->style = 'text-align:center;width:15%';
                    $tr->find('td',1)->style = 'text-align:center;width:15%';
                    $tr->find('td',2)->style = 'text-align:center;width:10%';
                    $tr->find('td',3)->style = 'text-align:left;width:30%';
                    $tr->find('td',4)->style = 'text-align:right;width:10%';
                    $tr->find('td',5)->style = 'text-align:right;width:10%';
                    $tr->find('td',6)->style = 'text-align:right;width:10%';
                 }
             }
             $htmlDetalle->find('tr',0)->style = 'font-weight:bold;font-size:9px;text-align:center';
             
             $htmlResumen = str_get_html($htmlResumenLiquidacionSinIva);
             $htmlResumen->find('table',0)->class = '';
             $htmlResumen->find('table',0)->style = 'font-size:9px;width:50%;margin-top:10px';
             $htmlResumen->find('table',0)->border = '0px';
             foreach ($htmlResumen->find('tr') as $tr) {
                // var_dump($tr);
                 if ($tr->find('td',2)->outertext != NULL){
                    $tr->style = 'vertical-align:middle';
                    $tr->find('td',0)->style = 'text-align:center';
                    $tr->find('td',1)->style = 'text-align:left';
                    $tr->find('td',2)->style = 'text-align:center';
                 }
             }
             $htmlResumen->find('tr',0)->style = 'font-weight:bold;font-size:9px;text-align:center';
             
             $this->load->library('Pdf'); 
             $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false); 
             //$pdf->Header($sucursal, $liquidacion_nro);
             $pdf->setSucursal($sucursal);
             $pdf->SetLiquidacionNro($liquidacion_nro);
             $pdf->SetCliente(Cliente::whereId($cliente_id)->first()->nombre);
             $pdf->SetFacturaNro($factura_nro);
             $pdf->SetPeriodoDesde($periodo_desde);
             $pdf->SetPeriodoHasta($periodo_hasta);
             $pdf->SetDepartamento($departamento);
//             var_dump($liquidacion_nro." ".Cliente::whereId($cliente_id)->first()->nombre."  ".$factura_nro."  ".$periodo_desde."  ".$periodo_hasta);die;
             $pdf->SetTitle('Liquidaciones'); 
             $pdf->SetHeaderMargin(300); 
             $pdf->SetTopMargin(200); 
             $pdf->setFooterMargin(20); 
             $pdf->SetAutoPageBreak(true); 
             $pdf->SetAuthor('Author'); 
             $pdf->SetDisplayMode('real', 'default'); 
             $pdf->Write(5, 'CodeIgniter TCPDF Integration'); 
             // add a page 
             
             // set default header data
            
            $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128),  
                                $liquidacion_nro, Cliente::whereId($cliente_id)->first()->nombre, $factura_nro, $periodo_desde, $periodo_hasta, $sucursal,$departamento);
            $pdf->setFooterData(array(0,64,0), array(0,64,128));

            // set header and footer fonts
            $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
            $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

            // set default monospaced font
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

            // set margins
            $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP+30, PDF_MARGIN_RIGHT);
            $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
            $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

            // set auto page breaks
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

            // set image scale factor
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

            // set some language-dependent strings (optional)
            if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
                require_once(dirname(__FILE__).'/lang/eng.php');
                $pdf->setLanguageArray($l);
            }

            // ---------------------------------------------------------

            // set default font subsetting mode
            $pdf->setFontSubsetting(true);

            // Set font
            // dejavusans is a UTF-8 Unicode font, if you only need to
            // print standard ASCII chars, you can use core fonts like
            // helvetica or times to reduce file size.
            $pdf->SetFont('dejavusans', '', 8, '', true);

            // Add a page
            // This method has several options, check the source code documentation for more information.
            $pdf->AddPage();
       
        
             $pdf->writeHTML($htmlDetalle->save()."<br/>".$htmlResumen->save(), true, false, true, false, ''); 
             
             $pdf->Output('liquidacion .pdf', 'I');
        }
}

/* End of file liquidaciones/liquidaciones.php */
/* Location: ./system/application/controllers/liquidaciones/liquidaciones.php */