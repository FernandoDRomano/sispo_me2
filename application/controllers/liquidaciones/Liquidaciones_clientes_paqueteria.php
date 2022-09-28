<?php

class Liquidaciones_clientes_paqueteria extends MY_Controller {

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
			'liquidaciones' => array()
		);

		$vista_externa = array(			
			'title' => ucwords("Liquidaciones"),
			'contenido_main' => $this->load->view('components/liquidaciones/liquidaciones_clientes_paqueteria/liquidaciones_clientes_paqueteria_list', $vista_interna, true)
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
            if($cliente_id != '')
                 $this->db->where('cliente_id', $cliente_id);

            if($this->is_user())
                $this->db->where('sucursal_id', $this->usuario->sucursal_id);

            if($sucursal_id != '')
                $this->db->where('sucursal_id', $sucursal_id);

            if($departamento_id != '')
                $this->db->where('departamento_id', $departamento_id);

            if($fecha_desde != '')
                $this->db->where('create >=', $fecha_desde.' 00:00:00');

            if($fecha_hasta != '')
                $this->db->where('create <=', $fecha_hasta.' 23:59:59');
           
//            var_dump($query->toSql());
            $query = $this->db
                    ->select('*')
                    ->get('flash_liquidaciones_clientes_paquetes');
            
            $liquidaciones = $query->result();
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

            $this->title('Liquidaciones Clientes');
            $this->view = 'components/liquidaciones/liquidaciones_clientes_paqueteria/liquidaciones_clientes_paqueteria_list';
            $this->template('template/backend');
        }

	function add(){
	    
	    
		if ($this->input->post('enviar_form')){
                    //Graba el encabezado
//                    foreach ($_POST as $key => $value) {
//                        echo $key."   ".$value."</br>";
//                    };
//                    var_dump($_POST);die;
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
					'factura' => $this->input->post('factura'),
					'periodo_desde' => $date_desde,
					'periodo_hasta' => $date_hasta,
					'obeservaciones' => $this->input->post('obeservaciones'),
					'estado_id' => $this->input->post('estado_id'),
                                        'iva' => $this->input->post('iva'),
                                        'create_user_id' => $this->usuario->id,
				);
//                        var_dump($data);die;
			$liquidacion_cliente_id = $this->codegen_model->add('flash_liquidaciones_clientes_paquetes',$data);

                        $cantidad_filas = $this->input->post('cantidad_elementos');

                        if ($liquidacion_cliente_id ==! NULL){
                            for ($j = 0; $j < $cantidad_filas; $j++) {
                                $datos = explode("|", $this->input->post('fila_'.$j));
//                                var_dump($datos);die;
                                $estado_liquidacion = 11;//10=Sin Liquidar, 11=En proceso, 12=Liquidado
                                $comprobante_liquidacion_id = 0;
                                //LA liquidacion solo queda en estado 2 si se la cierra del botno CONFIRMAR LIQUIDACION
//                                echo $this->input->post('cantidad_'.$j)."   ---  ".$this->input->post('cantidad_liquidar_'.$j)."   ".$this->input->post('cantidad_liquidada_'.$j);die;
//                                if ((int)$this->input->post('cantidad_'.$j)
//                                        === (int)$this->input->post('cantidad_liquidar_'.$j)+(int)$this->input->post('cantidad_liquidada_'.$j))
//                                        {$estado_liquidacion = 2; $comprobante_liquidacion_id = $liquidacion_cliente_id;} //0=Sin Liquidar, 1=En proceso, 2=Liquidado
                                $liquidacion_comprobantes[$j] = array(
                                        'liquidacion_cliente_id' => $liquidacion_cliente_id,
                                        'comprobante_ingreso_id' => $datos[0],
                                        'remito_cliente' => $datos[1],
                                        'fecha' => formatdate($datos[4]),
                                        'servicio_id' => 2, //2 cod. paqueteria en tabla flash_servicios
                                        'servicio' => $datos[6],
                                        'cant_total' => $datos[7],
                                        'cant_a_liquidar' => 0,//$this->input->post('cantidad_liquidar_'.$j),
                                        'precio' => $datos[9],
                                        'create' =>  date('Y-m-d H:m:s'),
                                );
                                    $comprobantes[] = array(
                                               'id' => $datos[0],
                                               'estado_liquidacion' => $estado_liquidacion, //10=Sin Liquidar, 11=En proceso, 12=Liquidado
                                               'liquidacion_id' => $liquidacion_cliente_id
                                         );
                                    
                            }
//                            var_dump($comprobantes);die;
                            $this->db->insert_batch('flash_liquidaciones_comprobantes_paquetes', $liquidacion_comprobantes);
                            //echo $this->db->last_query();die;
                            $this->db->update_batch('flash_comprobantes_ingresos', $comprobantes, 'id'); 
                        }
                       redirect(base_url().'liquidaciones/liquidaciones_clientes_paqueteria');		  
                }
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
                        'clientes' => $this->flash_md->getClientes(Cliente::ESTADO_ACTIVO,  2),
                        'departamentos' => $this->codegen_model->get('flash_clientes_departamentos','distinct(nombre)',''),
                        'sucursales' => $this->codegen_model->get('flash_sucursales','*',''),
		);

		$vista_externa = array(			
			'title' => ucwords("Liquidaciones"),
			'contenido_main' => $this->load->view('components/liquidaciones/liquidaciones_clientes_paqueteria/liquidaciones_clientes_paqueteria_add', $vista_interna, true)
		);		  
   
		$this->load->view('template/backend', $vista_externa);
	}	

	function edit($id){
	    
	    
		if ($this->input->post('enviar_form')){                  //  var_dump($post);die;
			$data = array(
					'factura' => $this->input->post('factura'),
                                        'estado_id' => 12,
					'update' => date('Y-m-d H:m:s')
				);
			$this->codegen_model->edit('flash_liquidaciones_clientes_paquetes',$data,'id',$id);
			redirect(base_url().'liquidaciones/liquidaciones_clientes_paqueteria/edit/'.$id);
		}
                //Cabecera
                $result =  $this->codegen_model->row('flash_liquidaciones_clientes_paquetes','*','id = '.$id);
                $query = " SELECT ci.numero ,cp.*, DATE_FORMAT(cp.create, '%d-%m-%Y') fecha_liq,
                                    pp.descripcion_paquete, 
                                    pp.dimensiones, 
                                    pp.peso, 
                                    pp.bultos, 
                                    pp.dias_entrega,
                                    p.barcode_externo codigo
                           FROM  flash_liquidaciones_comprobantes_paquetes cp
                           INNER JOIN flash_comprobantes_ingresos ci ON ci.id = cp.comprobante_ingreso_id
                           INNER JOIN flash_servicios s ON s.id = cp.servicio_id
                           INNER JOIN flash_piezas p ON p.comprobante_ingreso_id = cp.comprobante_ingreso_id
                           INNER JOIN flash_piezas_paquetes pp ON pp.pieza_id = p.id
                           WHERE cp.liquidacion_cliente_id = $id";
                //Detalles
//                $query_exe = $this->db->query($query);
                $detalles = $this->db->query($query)->result();
                
                $query = "   SELECT pt.*, pt.tipo nombre 
                            FROM flash_clientes c
                            INNER JOIN flash_paqueteria_tipos pt ON c.`flash_paqueteria_tipo_id` = pt.`id`
                            WHERE c.id = $result->cliente_id";
                $paqueteria_tipo = $this->db->query($query)->result();
                
		        $vista_interna = array(
			            'permisos_efectivos' => $this->permisos,
                        'clientes' => $this->flash_md->getClientes(Cliente::ESTADO_ACTIVO,  2),
                        'paqueterias' => $paqueteria_tipo,
//                        'cliente' => $this->codegen_model->$row("flash_clientes",'*','id = '.$result->cliente_id),
			            'result' => $result,
                        'detalles' => $detalles ,//$detalles,
                        'departamentos' => $this->codegen_model->get('flash_clientes_departamentos','*','cliente_id = '.$result->cliente_id),
                        'sucursales' => $this->codegen_model->get('flash_sucursales','*',''),
                        'tipo_factura' => "A" 
		        );
                
		$vista_externa = array(			
			'title' => ucwords("Liquidaciones"),
			'contenido_main' => $this->load->view('components/liquidaciones/liquidaciones_clientes_paqueteria/liquidaciones_clientes_paqueteria_edit', $vista_interna, true)
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
			'contenido_main' => $this->load->view('components/liquidaciones/liquidaciones_clientes_paqueteria/liquidaciones_clientes_paqueteria_view', $vista_interna, true)
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
        
        function anular($liquidacion_cliente_id){
            
            //Traigo todos los CI involucrados
            $query_liquidaciones_comprobantes = "SELECT lci.comprobante_ingreso_id ci_id FROM flash_comprobantes_ingresos ci 
                                            INNER JOIN flash_liquidaciones_comprobantes_paquetes lci ON lci.`comprobante_ingreso_id` = ci.id
                                            INNER JOIN flash_liquidaciones_clientes_paquetes lc ON lc.`id` = lci.`liquidacion_cliente_id`
                                            WHERE lc.id = ".$liquidacion_cliente_id;
            $liquidaciones_comprobantes = $this->db->query($query_liquidaciones_comprobantes)->result();
            
            //Por cada CI invlolucrado consulto si esta en otra liquidacion con cant_a_liquidar mayor a cero
            foreach ($liquidaciones_comprobantes as $value) {
                //Pregunto si el ci involucrado esta en otra liquidacion
                $query_ask = "  SELECT ci.* FROM flash_comprobantes_ingresos ci
                                INNER JOIN flash_liquidaciones_comprobantes_paquetes lci ON lci.`comprobante_ingreso_id` = ci.`id`
                                INNER JOIN flash_liquidaciones_clientes_paquetes lc ON lc.`id` = lci.`liquidacion_cliente_id`
                                WHERE ci.id IN (
                                            SELECT ci2.id FROM flash_comprobantes_ingresos ci2
                                            INNER JOIN flash_liquidaciones_comprobantes_paquetes lci ON lci.`comprobante_ingreso_id` = ci2.id
                                            INNER JOIN flash_liquidaciones_clientes_paquetes lc ON lc.`id` = lci.`liquidacion_cliente_id`
                                            WHERE lc.id = $liquidacion_cliente_id)
                                AND lc.id <> $liquidacion_cliente_id
                                AND ci.id = $value->ci_id
                                GROUP BY lc.`id`";
                $ask = $this->db->query($query_ask)->result();
                //Volver el estado_liquidacion de los CI invlolucrados a 0
                $estado_liquidacion = 10;
                if (count($ask) > 0) $estado_liquidacion = 11;
                $query_back_ci =    "UPDATE  flash_comprobantes_ingresos ci 
                                        SET ci.estado_liquidacion = $estado_liquidacion
                                            ci.liquidacion_id = 10
                                        WHERE ci.id = ".$value->ci_id;
                $ask = $this->db->query($query_back_ci);
            }
            
            //Borrar los Ajustes de esa liquidacion_cliente_id;
//            $query_delete_ajustes = "DELETE FROM flash_liquidaciones_ajustes WHERE liquidacion_cliente_id = ".$liquidacion_cliente_id;
//            $delete_ajustes = $this->db->query($query_delete_ajustes);

            //Borrar los registros involucrados en la tabla flash_liquidaciones_comprobantes
            $query_delete_lci = "DELETE FROM flash_liquidaciones_comprobantes_paquetes  
                                 WHERE liquidacion_cliente_id = ".$liquidacion_cliente_id;
            $delete_lci = $this->db->query($query_delete_lci);
                        //echo $this->db->last_query()."<br>";die;
            //Borro el encabezado en la tabla flash_liquidaciones_clientes
            $query_delete_lc = "DELETE FROM flash_liquidaciones_clientes_paquetes WHERE id = ".$liquidacion_cliente_id;
            $delete_lc = $this->db->query($query_delete_lc);
            
	}
        
        function exportarAPdf() {          
//            var_dump($_POST);die;
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
             $tipo_factura = $this->input->post('pdf_tipo_factura');
             $paqueteria = $this->codegen_model->row("flash_paqueteria_tipos","*","id = ".$this->input->post('pdf_paqueteria_id'));
             //$cantidad_elementos = $this->input->post('cantidad_elementos');
             $imprimir = $this->input->post('pdf_imprimir');
             if($periodo_desde) $date_desde = new DateTime($periodo_desde);
             if($periodo_hasta) $date_hasta = new DateTime($periodo_hasta);

             if ($tipo_factura == "A"){  //               var_dump($_POST);die;
                $result =  $this->codegen_model->row('flash_liquidaciones_clientes','*','id = '.$liquidacion_id);
                 $query = " SELECT ci.numero ,cp.*, DATE_FORMAT(cp.create, '%d-%m-%Y') fecha_liq,
                                    pp.descripcion_paquete, 
                                    pp.dimensiones, 
                                    pp.peso, 
                                    pp.bultos, 
                                    pp.dias_entrega,
                                    p.barcode_externo codigo
                           FROM  flash_liquidaciones_comprobantes_paquetes cp
                           INNER JOIN flash_comprobantes_ingresos ci ON ci.id = cp.comprobante_ingreso_id
                           INNER JOIN flash_servicios s ON s.id = cp.servicio_id
                           INNER JOIN flash_piezas p ON p.comprobante_ingreso_id = cp.comprobante_ingreso_id
                           INNER JOIN flash_piezas_paquetes pp ON pp.pieza_id = p.id
                           WHERE cp.liquidacion_cliente_id = $liquidacion_id";
                //Detalles
//                $query_exe = $this->db->query($query);
                $detalles = $this->db->query($query)->result();

                 $html_encabezado = '<table class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered" border="0">
                                                    <thead>
                                                        <tr>
                                                            <th style="border-bottom:.5px solid #000000;">CI</th>
                                                            <th style="border-bottom:.5px solid #000000;">Remito</th>
                                                            <th style="border-bottom:.5px solid #000000;">Fecha</th>
                                                            <th style="border-bottom:.5px solid #000000;">Descripcion</th>
                                                            <th style="border-bottom:.5px solid #000000;text-align:center">Dimensiones</th>
                                                            <th style="border-bottom:.5px solid #000000;text-align:center">Peso</th>
                                                            <th style="border-bottom:.5px solid #000000;text-align:center">Bultos</th>
                                                            <th style="border-bottom:.5px solid #000000;text-align:center">Codigo</th>
                                                            <th style="border-bottom:.5px solid #000000;text-align:center">Valor<br>Calculado</th>
                                                        </tr>
                                                        <tr>
                                                            <th colspan="9"></th>
                                                        </tr>
                                                    </thead>
                                    <tbody id="body-grilla">';
                $suma_total = 0;
                $iva = 0.21;
                //var_dump($facturaa);die;
                foreach ($detalles as $value) {
                      $precio = number_format($value->precio,3);
                      $importe = $value->cant_a_liquidar*$precio;
                      if ($value->liquidacion_ajuste_id == 0){
                      $html_cuerpo    .= '<tr>
                                            <td>'.$value->numero.'</td>
                                            <td>'.$value->remito_cliente.'</td>
                                            <td>'.$value->fecha.'</td>
                                            <td>'.$value->descripcion_paquete.'</td>
                                            <td>'.$value->dimensiones.'</td>
                                            <td style="text-align:right;">'.$value->peso.'</td>
                                            <td style="text-align:right;">'.$value->bultos.'</td>
                                            <td style="text-align:right;">'.$value->codigo.'</td>
                                            <td style="text-align:right;">'.$value->precio.'</td>
                                        </tr>';
                     }
                      
                    $contador++;
                    $suma_total += $value->precio;
                    //$iva = $iva + $importe * $iva_puro;
                 }
                 $iva_solo = $suma_total * $iva;
                 $total_iva = $iva_solo + $suma_total;
                 $html_cierre = '</tbody></table>';
                 $html_total = '<tr>
                                               <td class=""></td>
                                                <td class=""></td>
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
                                                <td class=""></td>
                                                <td class=""></td>
                                                <td class=""><strong>Total:</strong></td>
                                                <td style="text-align:right"><strong>'.number_format($total_iva,3).'</strong></td>
                                            </tr>';
                                 //$('#grilla').append(cabecera+body+total+cierre); 
                 
                 $html_pdf_detalle = $html_encabezado.$html_cuerpo.$html_total.$html_cierre; 
                 
             }
             if ($imprimir == "liquidacionx"){}
             if ($imprimir == "facturaa"){}
             if ($imprimir == "pendientes"){}
             if ($imprimir == "clientespendientes"){}
             
             $this->load->library('Pdf'); 
             $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false); 
             //$pdf->Header($sucursal, $liquidacion_nro);
             $pdf->setSucursal($sucursal);
             $pdf->SetLiquidacionNro($liquidacion_id);
             $pdf->SetCliente(Cliente::whereId($cliente_id)->first()->nombre);
             $pdf->SetFacturaNro($factura_nro);
             $pdf->SetPeriodoDesde($periodo_desde);
             $pdf->SetPeriodoHasta($periodo_hasta);
             $pdf->SetDepartamento($departamento);
             $pdf->SetPaqueteria($paqueteria->tipo);
             
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
                                $liquidacion_nro, Cliente::whereId($cliente_id)->first()->nombre, $factura_nro, $periodo_desde, $periodo_hasta, $sucursal,$departamento,$paqueteria->tipo);
            $pdf->setFooterData(array(0,64,0), array(0,64,128));

            // set header and footer fonts
            $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
            $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

            // set default monospaced font
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

            // set margins
            $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP+40, PDF_MARGIN_RIGHT);
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
             $pdf->writeHTML($html_pdf_detalle."<br/>", true, false, true, false, ''); 
             
             $pdf->Output('liquidacion .pdf', 'I');
        }
        
}

/* End of file liquidaciones/liquidaciones_clientes.php */
/* Location: ./system/application/controllers/liquidaciones/liquidaciones_clientes.php */