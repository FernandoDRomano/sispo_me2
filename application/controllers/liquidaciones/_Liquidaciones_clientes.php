<?php

class Liquidaciones_clientes extends MY_Controller {

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
			'contenido_main' => $this->load->view('components/liquidaciones/liquidaciones_clientes/liquidaciones_clientes_list', $vista_interna, true)
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
                    ->get('flash_liquidaciones_clientes');
            
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
            $this->view = 'components/liquidaciones/liquidaciones_clientes/liquidaciones_clientes_list';
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
                        
			$liquidacion_cliente_id = $this->codegen_model->add('flash_liquidaciones_clientes',$data);

                        $cantidad_filas = $this->input->post('cantidad_elementos');
                        
                        if ($liquidacion_cliente_id ==! NULL){
                            for ($j = 0; $j < $cantidad_filas; $j++) {
                                $datos = explode("|", $this->input->post('fila_'.$j));
                                //var_dump($datos);echo $this->input->post('cantidad_liquidar_'.$j);die;
                                if ($this->input->post('cantidad_liquidar_'.$j) <> 0){
                                    $estado_liquidacion = 1;//0=Sin Liquidar, 1=En proceso, 2=Liquidado
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
                                            'servicio_id' => $datos[5],
                                            'servicio' => $datos[6],
                                            'cant_total' => $datos[7],
                                            'cant_a_liquidar' => $this->input->post('cantidad_liquidar_'.$j),
                                            'precio' => $datos[9],
                                            'create' =>  date('Y-m-d H:m:s'),
                                    );
                                    $comprobantes[] = array(
                                               'id' => $datos[0],
                                               'estado_liquidacion' => $estado_liquidacion, //0=Sin Liquidar, 1=En proceso, 2=Liquidado
                                               'liquidacion_id' => $liquidacion_cliente_id
                                         );
                                    }
                            }
                            $this->db->insert_batch('flash_liquidaciones_comprobantes', $liquidacion_comprobantes);
                            //echo $this->db->last_query();die;
                            $this->db->update_batch('flash_comprobantes_ingresos', $comprobantes, 'id'); 
                        }
                       redirect(base_url().'liquidaciones/liquidaciones_clientes');		  
                }
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
                        'clientes' => $this->flash_md->getClientes(Cliente::ESTADO_ACTIVO,  2),
                        'departamentos' => $this->codegen_model->get('flash_clientes_departamentos','distinct(nombre)',''),
                        'sucursales' => $this->codegen_model->get('flash_sucursales','*',''),
		);

		$vista_externa = array(			
			'title' => ucwords("Liquidaciones"),
			'contenido_main' => $this->load->view('components/liquidaciones/liquidaciones_clientes/liquidaciones_clientes_add', $vista_interna, true)
		);		  
   
		$this->load->view('template/backend', $vista_externa);
	}	

	function edit($id){
	    
	    
		if ($this->input->post('enviar_form')){
			$data = array(
					'factura' => $this->input->post('factura'),
					'update' => date('Y-m-d H:m:s')
				);
			$this->codegen_model->edit('flash_liquidaciones_clientes',$data,'id',$id);
			redirect(base_url().'liquidaciones/liquidaciones_clientes/edit/'.$id);
		}

                $result =  $this->codegen_model->row('flash_liquidaciones_clientes','*','id = '.$id);
                $query = "
                            SELECT   lc.liquidacion_cliente_id,
                                    lc.id liquidacion_comprobante_id,
                                    `ci`.`numero` `comprobante_ingreso`,
                                    lc.`remito_cliente` remito_cliente,
                                    ci.`fecha_pedido` fecha_pedido,
                                    lc.`servicio_id`, lc.`servicio`,
                                    lc.`cant_total`,
                                    lc.`cant_a_liquidar`,
                                    lc.`precio`,
                                    DATE_FORMAT(lc.fecha, '%d-%m-%Y') `fecha_liq`,
                                    0 liquidacion_ajuste_id
                            FROM  `flash_liquidaciones_comprobantes` `lc`   
                            JOIN `flash_liquidaciones_clientes` `l` ON `l`.`id` = `lc`.`liquidacion_cliente_id` 
                            JOIN `flash_comprobantes_ingresos` `ci` ON `lc`.`comprobante_ingreso_id` = `ci`.`id` 
                            WHERE `lc`.`liquidacion_cliente_id` = $id 
                            UNION 
                            SELECT  la2.liquidacion_cliente_id ,
                                    la2.liquidacion_comprobante_id liquidacion_comprobante_id,
                                    ci.`numero` comprobante_ingreso,
                                    lci.remito_cliente remito_cliente,
                                    ci.`fecha_pedido` fecha_pedido,
                                    lci.servicio_id servicio_id, 
                                    lci.servicio servicio,
                                    '--' cant_total,
                                    la2.cantidad `cant_a_liquidar`,
                                    la2.importe precio,
                                    DATE_FORMAT(la2.create, '%d-%m-%Y') `fecha_liq`,
                                    la2.id liquidacion_ajuste_id
                            FROM flash_liquidaciones_ajustes la2
                            INNER JOIN flash_liquidaciones_clientes l ON l.id = la2.liquidacion_cliente_id
                            INNER JOIN flash_liquidaciones_comprobantes lci ON la2.liquidacion_comprobante_id = lci.id
                            INNER JOIN flash_comprobantes_ingresos ci ON lci.comprobante_ingreso_id = ci.id 
                            WHERE la2.liquidacion_cliente_id = $id
                            ORDER BY  liquidacion_comprobante_id,fecha_liq asc";
//                echo $query;die;
                $query_exe = $this->db->query($query);
                $detalles = $query_exe->result();
//                $query = $this->db
//                            ->select('lc.*, ci.numero comprobante_ingreso, date_format(lc.fecha,"%d-%m-%Y") fecha_liq')
//                            ->join('flash_comprobantes_ingresos ci','lc.comprobante_ingreso_id = ci.id')
//                            ->where('lc.liquidacion_cliente_id = '.$id)
//                            ->get('flash_liquidaciones_comprobantes lc');
//                $detalles = $query->result();
//                echo $this->db->last_query();die;
                $query_resumen = "SELECT result.*, SUM(result.cant_a_liquidar) cant_a_liquidar FROM (SELECT   lc.liquidacion_cliente_id,
                                    lc.id liquidacion_comprobante_id,
                                    `ci`.`numero` `comprobante_ingreso`,
                                    lc.`remito_cliente` remito_cliente,
                                    ci.`fecha_pedido` fecha_pedido,
                                    lc.`servicio_id`, lc.`servicio`,
                                    lc.`cant_total`,
                                    lc.`cant_a_liquidar`,
                                    lc.`precio`,
                                    DATE_FORMAT(lc.fecha, '%d-%m-%Y') `fecha_liq`,
                                    0 liquidacion_ajuste_id
                            FROM  `flash_liquidaciones_comprobantes` `lc`   
                            JOIN `flash_liquidaciones_clientes` `l` ON `l`.`id` = `lc`.`liquidacion_cliente_id` 
                            JOIN `flash_comprobantes_ingresos` `ci` ON `lc`.`comprobante_ingreso_id` = `ci`.`id` 
                            WHERE `lc`.`liquidacion_cliente_id` = $id 
                            UNION 
                            SELECT  la2.liquidacion_cliente_id ,
                                    la2.liquidacion_comprobante_id liquidacion_comprobante_id,
                                    ci.`numero` comprobante_ingreso,
                                    lci.remito_cliente remito_cliente,
                                    ci.`fecha_pedido` fecha_pedido,
                                    lci.servicio_id servicio_id, 
                                    lci.servicio servicio,
                                    '--' cant_total,
                                    la2.cantidad `cant_a_liquidar`,
                                    la2.importe precio,
                                    DATE_FORMAT(la2.create, '%d-%m-%Y') `fecha_liq`,
                                    la2.id liquidacion_ajuste_id
                            FROM flash_liquidaciones_ajustes la2
                            INNER JOIN flash_liquidaciones_clientes l ON l.id = la2.liquidacion_cliente_id
                            INNER JOIN flash_liquidaciones_comprobantes lci ON la2.liquidacion_comprobante_id = lci.id
                            INNER JOIN flash_comprobantes_ingresos ci ON lci.comprobante_ingreso_id = ci.id
                            WHERE la2.liquidacion_cliente_id = $id) as result
                            GROUP BY result.servicio_id
                            ORDER BY  liquidacion_comprobante_id,fecha_liq asc";
                //echo $query_resumen;die;
                $resumen = $this->db->query($query_resumen)->result();
                //var_dump($resumen);die;
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
                        'clientes' => $this->flash_md->getClientes(Cliente::ESTADO_ACTIVO,  2),
			'result' => $result,
                        'detalles' => $detalles,
                        'resumen' => $resumen,
                        'departamentos' => $this->codegen_model->get('flash_clientes_departamentos','*','cliente_id = '.$result->cliente_id),
                        'sucursales' => $this->codegen_model->get('flash_sucursales','*',''),
                        'tipo_factura' => "A" 
		);
                
		$vista_externa = array(			
			'title' => ucwords("Liquidaciones"),
			'contenido_main' => $this->load->view('components/liquidaciones/liquidaciones_clientes/liquidaciones_clientes_edit', $vista_interna, true)
		);		  
   
		$this->load->view('template/backend', $vista_externa);
	}

        function edit_factura_b($id){
            
            
		if ($this->input->post('enviar_form')){
			$data = array(
					'factura' => $this->input->post('factura'),
					'update' => date('Y-m-d H:m:s')
				);
			$this->codegen_model->edit('flash_liquidaciones_clientes',$data,'id',$id);
			redirect(base_url().'liquidaciones/liquidaciones_clientes/edit/'.$id);
		}

                $result =  $this->codegen_model->row('flash_liquidaciones_clientes','*','id = '.$id);
                $query = "
                            SELECT   lc.liquidacion_cliente_id,
                                    lc.id liquidacion_comprobante_id,
                                    `ci`.`numero` `comprobante_ingreso`,
                                    lc.`remito_cliente` remito_cliente,
                                    ci.`fecha_pedido` fecha_pedido,
                                    lc.`servicio_id`, lc.`servicio`,
                                    lc.`cant_total`,
                                    lc.`cant_a_liquidar`,
                                    lc.`precio` + (lc.`precio` * 0.21) as precio,
                                    DATE_FORMAT(lc.fecha, '%d-%m-%Y') `fecha_liq`,
                                    0 liquidacion_ajuste_id
                            FROM  `flash_liquidaciones_comprobantes` `lc`   
                            JOIN `flash_liquidaciones_clientes` `l` ON `l`.`id` = `lc`.`liquidacion_cliente_id` 
                            JOIN `flash_comprobantes_ingresos` `ci` ON `lc`.`comprobante_ingreso_id` = `ci`.`id` 
                            WHERE `lc`.`liquidacion_cliente_id` = $id 
                            UNION 
                            SELECT  la2.liquidacion_cliente_id ,
                                    la2.liquidacion_comprobante_id liquidacion_comprobante_id,
                                    ci.`numero` comprobante_ingreso,
                                    lci.remito_cliente remito_cliente,
                                    ci.`fecha_pedido` fecha_pedido,
                                    lci.servicio_id servicio_id, 
                                    lci.servicio servicio,
                                    '--' cant_total,
                                    la2.cantidad `cant_a_liquidar`,
                                    la2.importe + (la2.importe * 0.21) as precio,
                                    DATE_FORMAT(la2.create, '%d-%m-%Y') `fecha_liq`,
                                    la2.id liquidacion_ajuste_id
                            FROM flash_liquidaciones_ajustes la2
                            INNER JOIN flash_liquidaciones_clientes l ON l.id = la2.liquidacion_cliente_id
                            INNER JOIN flash_liquidaciones_comprobantes lci ON la2.liquidacion_comprobante_id = lci.id
                            INNER JOIN flash_comprobantes_ingresos ci ON lci.comprobante_ingreso_id = ci.id 
                            WHERE la2.liquidacion_cliente_id = $id
                            ORDER BY  liquidacion_comprobante_id,fecha_liq asc";
//                echo $query;die;
                $query_exe = $this->db->query($query);
                $detalles = $query_exe->result();
//                $query = $this->db
//                            ->select('lc.*, ci.numero comprobante_ingreso, date_format(lc.fecha,"%d-%m-%Y") fecha_liq')
//                            ->join('flash_comprobantes_ingresos ci','lc.comprobante_ingreso_id = ci.id')
//                            ->where('lc.liquidacion_cliente_id = '.$id)
//                            ->get('flash_liquidaciones_comprobantes lc');
//                $detalles = $query->result();
//                echo $this->db->last_query();die;
                $query_resumen = "SELECT result.*, SUM(result.cant_a_liquidar) cant_a_liquidar FROM (SELECT   lc.liquidacion_cliente_id,
                                    lc.id liquidacion_comprobante_id,
                                    `ci`.`numero` `comprobante_ingreso`,
                                    lc.`remito_cliente` remito_cliente,
                                    ci.`fecha_pedido` fecha_pedido,
                                    lc.`servicio_id`, lc.`servicio`,
                                    lc.`cant_total`,
                                    lc.`cant_a_liquidar`,
                                    lc.`precio`,
                                    DATE_FORMAT(lc.fecha, '%d-%m-%Y') `fecha_liq`,
                                    0 liquidacion_ajuste_id
                            FROM  `flash_liquidaciones_comprobantes` `lc`   
                            JOIN `flash_liquidaciones_clientes` `l` ON `l`.`id` = `lc`.`liquidacion_cliente_id` 
                            JOIN `flash_comprobantes_ingresos` `ci` ON `lc`.`comprobante_ingreso_id` = `ci`.`id` 
                            WHERE `lc`.`liquidacion_cliente_id` = $id 
                            UNION 
                            SELECT  la2.liquidacion_cliente_id ,
                                    la2.liquidacion_comprobante_id liquidacion_comprobante_id,
                                    ci.`numero` comprobante_ingreso,
                                    lci.remito_cliente remito_cliente,
                                    ci.`fecha_pedido` fecha_pedido,
                                    lci.servicio_id servicio_id, 
                                    lci.servicio servicio,
                                    '--' cant_total,
                                    la2.cantidad `cant_a_liquidar`,
                                    la2.importe precio,
                                    DATE_FORMAT(la2.create, '%d-%m-%Y') `fecha_liq`,
                                    la2.id liquidacion_ajuste_id
                            FROM flash_liquidaciones_ajustes la2
                            INNER JOIN flash_liquidaciones_clientes l ON l.id = la2.liquidacion_cliente_id
                            INNER JOIN flash_liquidaciones_comprobantes lci ON la2.liquidacion_comprobante_id = lci.id
                            INNER JOIN flash_comprobantes_ingresos ci ON lci.comprobante_ingreso_id = ci.id
                            WHERE la2.liquidacion_cliente_id = $id) as result
                            GROUP BY result.servicio_id
                            ORDER BY  liquidacion_comprobante_id,fecha_liq asc";
                //echo $query_resumen;die;
                $resumen = $this->db->query($query_resumen)->result();
                //var_dump($resumen);die;
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
                        'clientes' => $this->flash_md->getClientes(Cliente::ESTADO_ACTIVO,  2),
			'result' => $result,
                        'detalles' => $detalles,
                        'resumen' => $resumen,
                        'departamentos' => $this->codegen_model->get('flash_clientes_departamentos','*','cliente_id = '.$result->cliente_id),
                        'sucursales' => $this->codegen_model->get('flash_sucursales','*',''),
                        'tipo_factura' => "B" 
		);
                
		$vista_externa = array(			
			'title' => ucwords("Liquidaciones"),
			'contenido_main' => $this->load->view('components/liquidaciones/liquidaciones_clientes/liquidaciones_clientes_edit', $vista_interna, true)
		);		  
   
		$this->load->view('template/backend', $vista_externa);
	}
        
        function edit_factura_x($id){
            
            
		if ($this->input->post('enviar_form')){
			$data = array(
					'factura' => $this->input->post('factura'),
					'update' => date('Y-m-d H:m:s')
				);
			$this->codegen_model->edit('flash_liquidaciones_clientes',$data,'id',$id);
			redirect(base_url().'liquidaciones/liquidaciones_clientes/edit/'.$id);
		}

                $result =  $this->codegen_model->row('flash_liquidaciones_clientes','*','id = '.$id);
                $query = "
                            SELECT   lc.liquidacion_cliente_id,
                                    lc.id liquidacion_comprobante_id,
                                    `ci`.`numero` `comprobante_ingreso`,
                                    lc.`remito_cliente` remito_cliente,
                                    ci.`fecha_pedido` fecha_pedido,
                                    lc.`servicio_id`, lc.`servicio`,
                                    lc.`cant_total`,
                                    lc.`cant_a_liquidar`,
                                    lc.`precio`  precio,
                                    DATE_FORMAT(lc.fecha, '%d-%m-%Y') `fecha_liq`,
                                    0 liquidacion_ajuste_id
                            FROM  `flash_liquidaciones_comprobantes` `lc`   
                            JOIN `flash_liquidaciones_clientes` `l` ON `l`.`id` = `lc`.`liquidacion_cliente_id` 
                            JOIN `flash_comprobantes_ingresos` `ci` ON `lc`.`comprobante_ingreso_id` = `ci`.`id` 
                            WHERE `lc`.`liquidacion_cliente_id` = $id 
                            UNION 
                            SELECT  la2.liquidacion_cliente_id ,
                                    la2.liquidacion_comprobante_id liquidacion_comprobante_id,
                                    ci.`numero` comprobante_ingreso,
                                    lci.remito_cliente remito_cliente,
                                    ci.`fecha_pedido` fecha_pedido,
                                    lci.servicio_id servicio_id, 
                                    lci.servicio servicio,
                                    '--' cant_total,
                                    la2.cantidad `cant_a_liquidar`,
                                    la2.importe as precio,
                                    DATE_FORMAT(la2.create, '%d-%m-%Y') `fecha_liq`,
                                    la2.id liquidacion_ajuste_id
                            FROM flash_liquidaciones_ajustes la2
                            INNER JOIN flash_liquidaciones_clientes l ON l.id = la2.liquidacion_cliente_id
                            INNER JOIN flash_liquidaciones_comprobantes lci ON la2.liquidacion_comprobante_id = lci.id
                            INNER JOIN flash_comprobantes_ingresos ci ON lci.comprobante_ingreso_id = ci.id 
                            WHERE la2.liquidacion_cliente_id = $id
                            ORDER BY  liquidacion_comprobante_id,fecha_liq asc";
//                echo $query;die;
                $query_exe = $this->db->query($query);
                $detalles = $query_exe->result();
//                $query = $this->db
//                            ->select('lc.*, ci.numero comprobante_ingreso, date_format(lc.fecha,"%d-%m-%Y") fecha_liq')
//                            ->join('flash_comprobantes_ingresos ci','lc.comprobante_ingreso_id = ci.id')
//                            ->where('lc.liquidacion_cliente_id = '.$id)
//                            ->get('flash_liquidaciones_comprobantes lc');
//                $detalles = $query->result();
//                echo $this->db->last_query();die;
                $query_resumen = "SELECT result.*, SUM(result.cant_a_liquidar) cant_a_liquidar FROM (SELECT   lc.liquidacion_cliente_id,
                                    lc.id liquidacion_comprobante_id,
                                    `ci`.`numero` `comprobante_ingreso`,
                                    lc.`remito_cliente` remito_cliente,
                                    ci.`fecha_pedido` fecha_pedido,
                                    lc.`servicio_id`, lc.`servicio`,
                                    lc.`cant_total`,
                                    lc.`cant_a_liquidar`,
                                    lc.`precio`,
                                    DATE_FORMAT(lc.fecha, '%d-%m-%Y') `fecha_liq`,
                                    0 liquidacion_ajuste_id
                            FROM  `flash_liquidaciones_comprobantes` `lc`   
                            JOIN `flash_liquidaciones_clientes` `l` ON `l`.`id` = `lc`.`liquidacion_cliente_id` 
                            JOIN `flash_comprobantes_ingresos` `ci` ON `lc`.`comprobante_ingreso_id` = `ci`.`id` 
                            WHERE `lc`.`liquidacion_cliente_id` = $id 
                            UNION 
                            SELECT  la2.liquidacion_cliente_id ,
                                    la2.liquidacion_comprobante_id liquidacion_comprobante_id,
                                    ci.`numero` comprobante_ingreso,
                                    lci.remito_cliente remito_cliente,
                                    ci.`fecha_pedido` fecha_pedido,
                                    lci.servicio_id servicio_id, 
                                    lci.servicio servicio,
                                    '--' cant_total,
                                    la2.cantidad `cant_a_liquidar`,
                                    la2.importe precio,
                                    DATE_FORMAT(la2.create, '%d-%m-%Y') `fecha_liq`,
                                    la2.id liquidacion_ajuste_id
                            FROM flash_liquidaciones_ajustes la2
                            INNER JOIN flash_liquidaciones_clientes l ON l.id = la2.liquidacion_cliente_id
                            INNER JOIN flash_liquidaciones_comprobantes lci ON la2.liquidacion_comprobante_id = lci.id
                            INNER JOIN flash_comprobantes_ingresos ci ON lci.comprobante_ingreso_id = ci.id
                            WHERE la2.liquidacion_cliente_id = $id) as result
                            GROUP BY result.servicio_id
                            ORDER BY  liquidacion_comprobante_id,fecha_liq asc";
                //echo $query_resumen;die;
                $resumen = $this->db->query($query_resumen)->result();
                //var_dump($resumen);die;
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
                        'clientes' => $this->flash_md->getClientes(Cliente::ESTADO_ACTIVO,  2),
			'result' => $result,
                        'detalles' => $detalles,
                        'resumen' => $resumen,
                        'departamentos' => $this->codegen_model->get('flash_clientes_departamentos','*','cliente_id = '.$result->cliente_id),
                        'sucursales' => $this->codegen_model->get('flash_sucursales','*',''),
                        'tipo_factura' => "X" 
		);
                
		$vista_externa = array(			
			'title' => ucwords("Liquidaciones"),
			'contenido_main' => $this->load->view('components/liquidaciones/liquidaciones_clientes/liquidaciones_clientes_edit', $vista_interna, true)
		);		  
   
		$this->load->view('template/backend', $vista_externa);
	}
        
        function addAjustes($comprobante_ingreso, $comprobante_ingreso_servicio_id, $liquidacion_cliente_id,$liquidacion_comprobante_id,$precio){
            
            
                if ($this->input->post('enviar_form')){
                    $importe_query = "SELECT 
                                        (CASE WHEN `cpe`.`precio` IS NOT NULL THEN cpe.precio	ELSE s.precio END) AS precio
                                      FROM `flash_comprobantes_ingresos_servicios` `cis`
                                      INNER JOIN flash_comprobantes_ingresos ci ON ci.`id` = cis.`comprobante_ingreso_id`
                                      JOIN `flash_servicios` `s` ON `cis`.`servicio_id` = `s`.`id`
                                      LEFT JOIN `flash_clientes_precios_especiales` `cpe` ON `cpe`.`cliente_id` = `ci`.`cliente_id` AND `cis`.`servicio_id` = `cpe`.`servicio_id`
                                      JOIN `flash_clientes` `c` ON `ci`.`cliente_id` = `c`.`id`
                                      WHERE cis.`servicio_id` = $comprobante_ingreso_servicio_id
                                      LIMIT 1 ";
//                    var_dump($_POST);die;
                    $importe_result = $this->db->query($importe_query)->row();
                    
                    //echo var_dump($_POST);die;
                    $precio = ($this->input->post('operacion_id') == 'RESTA')?(double)$precio*(-1):(double)$precio;
                    $cantidad = ($this->input->post('operacion_id') == 'RESTA')?(int)$this->input->post('cantidad')*(-1):(int)$this->input->post('cantidad');
//                    var_dump($_POST);die;
//                    $concepto = $this->codegen->row('flash_liquidaciones_conceptos','*','id = '.$this->input->post('liquidacion_concepto_id'));
                   // echo $cantidad;die;
                    $data = array(
                                'liquidacion_cliente_id' => $this->input->post('liquidacion_cliente_id'),
                                'liquidacion_concepto_id' => $this->input->post('liquidacion_concepto_id'),
                                'liquidacion_comprobante_id' => $liquidacion_comprobante_id,
                                'comentario' => $this->input->post('comentario'),
                                'importe' => $precio,
                                'cantidad' => $cantidad,
                                'create' => date('Y-m-d H:m:s'),
                                'create_user_id' => $this->usuario->id
                            );
                    $this->codegen_model->add('flash_liquidaciones_ajustes',$data);
                    //Actualizo la cantidad liquidada en el encabezado de liquidacion
                    $cant_a_liquidar = $this->codegen_model->row('flash_liquidaciones_comprobantes','*','id = '.$liquidacion_comprobante_id);
                    //echo $cant_a_liquidar->cant_a_liquidar+$cantidad;die;
                    $data_liquidacion_comprobantes = array(
                                                            'cant_a_liquidar' => $cant_a_liquidar->cant_a_liquidar+$cantidad,
                                                            );

                    redirect(base_url().'liquidaciones/liquidaciones_clientes/edit/'.$liquidacion_cliente_id);
		}
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
                        'comprobante_ingreso_id' => $comprobante_ingreso,
                        'comprobante_ingreso_servicio_id' => $comprobante_ingreso_servicio_id,
                        'liquidacion_cliente_id' => $liquidacion_cliente_id,
                        'conceptos' => $this->codegen_model->get('flash_liquidaciones_conceptos','*','')
//			'result' => $this->codegen_model->row('flash_liquidaciones','*','id = '.$id)
		);

		$vista_externa = array(			
			'title' => ucwords("Agregar Ajuste"),
			'contenido_main' => $this->load->view('components/liquidaciones/liquidaciones_clientes/liquidaciones_clientes_ajustes', $vista_interna, true)
		);		  
   
		$this->load->view('template/view', $vista_externa);
	}
    
        function deleteAjuste($liquidacion_cliente_id, $liquidacion_ajuste_id){
		$this->codegen_model->delete('flash_liquidaciones_ajustes','id',$liquidacion_ajuste_id);
                redirect(base_url().'liquidaciones/liquidaciones_clientes/edit/'.$liquidacion_cliente_id);
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
        
        function anular($liquidacion_cliente_id){
            
            //Traigo todos los CI involucrados
            $query_liquidaciones_comprobantes = "SELECT lci.comprobante_ingreso_id ci_id FROM flash_comprobantes_ingresos ci 
                                            INNER JOIN flash_liquidaciones_comprobantes lci ON lci.`comprobante_ingreso_id` = ci.id
                                            INNER JOIN flash_liquidaciones_clientes lc ON lc.`id` = lci.`liquidacion_cliente_id`
                                            WHERE lc.id = ".$liquidacion_cliente_id;
            $liquidaciones_comprobantes = $this->db->query($query_liquidaciones_comprobantes)->result();
            
            //Por cada CI invlolucrado consulto si esta en otra liquidacion con cant_a_liquidar mayor a cero
            foreach ($liquidaciones_comprobantes as $value) {
                //Pregunto si el ci involucrado esta en otra liquidacion
                $query_ask = "  SELECT ci.* FROM flash_comprobantes_ingresos ci
                                INNER JOIN flash_liquidaciones_comprobantes lci ON lci.`comprobante_ingreso_id` = ci.`id`
                                INNER JOIN flash_liquidaciones_clientes lc ON lc.`id` = lci.`liquidacion_cliente_id`
                                WHERE ci.id IN (
                                            SELECT ci2.id FROM flash_comprobantes_ingresos ci2
                                            INNER JOIN flash_liquidaciones_comprobantes lci ON lci.`comprobante_ingreso_id` = ci2.id
                                            INNER JOIN flash_liquidaciones_clientes lc ON lc.`id` = lci.`liquidacion_cliente_id`
                                            WHERE lc.id = $liquidacion_cliente_id)
                                AND lc.id <> $liquidacion_cliente_id
                                AND ci.id = $value->ci_id
                                GROUP BY lc.`id`";
                $ask = $this->db->query($query_ask)->result();
                //Volver el estado_liquidacion de los CI invlolucrados a 0
                $estado_liquidacion = 0;
                if (count($ask) > 0) $estado_liquidacion = 1;
                $query_back_ci =    "UPDATE  flash_comprobantes_ingresos ci 
                                        SET ci.estado_liquidacion = $estado_liquidacion
                                        WHERE ci.id = ".$value->ci_id;
                $ask = $this->db->query($query_back_ci);
            }
            
            //Borrar los Ajustes de esa liquidacion_cliente_id;
            $query_delete_ajustes = "DELETE FROM flash_liquidaciones_ajustes WHERE liquidacion_cliente_id = ".$liquidacion_cliente_id;
            $delete_ajustes = $this->db->query($query_delete_ajustes);

            //Borrar los registros involucrados en la tabla flash_liquidaciones_comprobantes
            $query_delete_lci = "DELETE FROM flash_liquidaciones_comprobantes  
                                 WHERE liquidacion_cliente_id = ".$liquidacion_cliente_id;
            $delete_lci = $this->db->query($query_delete_lci);
                        //echo $this->db->last_query()."<br>";die;
            //Borro el encabezado en la tabla flash_liquidaciones_clientes
            $query_delete_lc = "DELETE FROM flash_liquidaciones_clientes WHERE id = ".$liquidacion_cliente_id;
            $delete_lc = $this->db->query($query_delete_lc);
//            echo $query_back_ci."</br>";
//            echo $query_delete_ajustes."</br>";
//            echo $query_delete_lci."</br>";
//            echo $query_delete_lc."</br>";die;
            
	}

        function confirmar($liquidacion_cliente_id){
            
            $query_liquidaciones_comprobantes = "   UPDATE flash_liquidaciones_clientes 
                                                        SET estado_id = 2
                                                    WHERE id = ".$liquidacion_cliente_id;
            $query_update = $this->db->query($query_liquidaciones_comprobantes);
            
            //Cierro todos los CI invloucrados en la LIQUIDACION
            $query_comprobantes = "UPDATE  flash_comprobantes_ingresos ci 
                                    INNER JOIN flash_liquidaciones_comprobantes lci ON lci.`comprobante_ingreso_id` = ci.id
                                    INNER JOIN flash_liquidaciones_clientes lc ON lc.`id` = lci.`liquidacion_cliente_id`
                                    SET ci.`estado_liquidacion` = 2
                                    WHERE lc.id = ".$liquidacion_cliente_id;
            $omprobantes_update = $this->db->query($query_comprobantes);
            //redirect(base_url().'liquidaciones/liquidaciones_clientes');
         }   
        function exportarAPdf() {          //  var_dump($_POST);die;
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
             //$cantidad_elementos = $this->input->post('cantidad_elementos');
             $imprimir = $this->input->post('pdf_imprimir');
            if($periodo_desde) $date_desde = new DateTime($periodo_desde);
            if($periodo_hasta) $date_hasta = new DateTime($periodo_hasta);

             if ($tipo_factura == "B"){  //               var_dump($_POST);die;
                 $result =  $this->codegen_model->row('flash_liquidaciones_clientes','*','id = '.$id);
                $query = "SELECT result.*, SUM(result.cant_a_liquidar) cant_a_liquidar FROM (SELECT   lc.liquidacion_cliente_id,
                                    lc.id liquidacion_comprobante_id,
                                    `ci`.`numero` `comprobante_ingreso`,
                                    lc.`remito_cliente` remito_cliente,
                                    ci.`fecha_pedido` fecha_pedido,
                                    lc.`servicio_id`, lc.`servicio`,
                                    lc.`cant_total`,
                                    lc.`cant_a_liquidar`,
                                    lc.`precio` + (lc.`precio` * 0.21) as precio,
                                    DATE_FORMAT(lc.fecha, '%d-%m-%Y') `fecha_liq`,
                                    0 liquidacion_ajuste_id
                            FROM  `flash_liquidaciones_comprobantes` `lc`   
                            JOIN `flash_liquidaciones_clientes` `l` ON `l`.`id` = `lc`.`liquidacion_cliente_id` 
                            JOIN `flash_comprobantes_ingresos` `ci` ON `lc`.`comprobante_ingreso_id` = `ci`.`id` 
                            WHERE `lc`.`liquidacion_cliente_id` = $liquidacion_id 
                            UNION 
                            SELECT  la2.liquidacion_cliente_id ,
                                    la2.liquidacion_comprobante_id liquidacion_comprobante_id,
                                    ci.`numero` comprobante_ingreso,
                                    lci.remito_cliente remito_cliente,
                                    ci.`fecha_pedido` fecha_pedido,
                                    lci.servicio_id servicio_id, 
                                    lci.servicio servicio,
                                    '--' cant_total,
                                    la2.cantidad `cant_a_liquidar`,
                                    la2.importe + (la2.importe * 0.21) as precio,
                                    DATE_FORMAT(la2.create, '%d-%m-%Y') `fecha_liq`,
                                    la2.id liquidacion_ajuste_id
                            FROM flash_liquidaciones_ajustes la2
                            INNER JOIN flash_liquidaciones_clientes l ON l.id = la2.liquidacion_cliente_id
                            INNER JOIN flash_liquidaciones_comprobantes lci ON la2.liquidacion_comprobante_id = lci.id
                            INNER JOIN flash_comprobantes_ingresos ci ON lci.comprobante_ingreso_id = ci.id
                            WHERE la2.liquidacion_cliente_id = $liquidacion_id) as result
                            GROUP BY result.comprobante_ingreso,result.servicio_id, result.remito_cliente
                            ORDER BY  liquidacion_comprobante_id,fecha_liq asc";
                //echo $query;die;
                $query_exe = $this->db->query($query);
                $facturab = $query_exe->result();
//                $query = $this->db
//                            ->select('lc.*, ci.numero comprobante_ingreso, date_format(lc.fecha,"%d-%m-%Y") fecha_liq')
//                            ->join('flash_comprobantes_ingresos ci','lc.comprobante_ingreso_id = ci.id')
//                            ->where('lc.liquidacion_cliente_id = '.$id)
//                            ->get('flash_liquidaciones_comprobantes lc');
//                $detalles = $query->result();
//                echo $this->db->last_query();die;
                $query_resumen = "SELECT result.*, SUM(result.cant_a_liquidar) cant_a_liquidar FROM (SELECT   lc.liquidacion_cliente_id,
                                    lc.id liquidacion_comprobante_id,
                                    `ci`.`numero` `comprobante_ingreso`,
                                    lc.`remito_cliente` remito_cliente,
                                    ci.`fecha_pedido` fecha_pedido,
                                    lc.`servicio_id`, lc.`servicio`,
                                    lc.`cant_total`,
                                    lc.`cant_a_liquidar`,
                                    lc.`precio`,
                                    DATE_FORMAT(lc.fecha, '%d-%m-%Y') `fecha_liq`,
                                    0 liquidacion_ajuste_id
                            FROM  `flash_liquidaciones_comprobantes` `lc`   
                            JOIN `flash_liquidaciones_clientes` `l` ON `l`.`id` = `lc`.`liquidacion_cliente_id` 
                            JOIN `flash_comprobantes_ingresos` `ci` ON `lc`.`comprobante_ingreso_id` = `ci`.`id` 
                            WHERE `lc`.`liquidacion_cliente_id` = $liquidacion_id 
                            UNION 
                            SELECT  la2.liquidacion_cliente_id ,
                                    la2.liquidacion_comprobante_id liquidacion_comprobante_id,
                                    ci.`numero` comprobante_ingreso,
                                    lci.remito_cliente remito_cliente,
                                    ci.`fecha_pedido` fecha_pedido,
                                    lci.servicio_id servicio_id, 
                                    lci.servicio servicio,
                                    '--' cant_total,
                                    la2.cantidad `cant_a_liquidar`,
                                    la2.importe precio,
                                    DATE_FORMAT(la2.create, '%d-%m-%Y') `fecha_liq`,
                                    la2.id liquidacion_ajuste_id
                            FROM flash_liquidaciones_ajustes la2
                            INNER JOIN flash_liquidaciones_clientes l ON l.id = la2.liquidacion_cliente_id
                            INNER JOIN flash_liquidaciones_comprobantes lci ON la2.liquidacion_comprobante_id = lci.id
                            INNER JOIN flash_comprobantes_ingresos ci ON lci.comprobante_ingreso_id = ci.id
                            WHERE la2.liquidacion_cliente_id = $liquidacion_id) as result
                            GROUP BY result.servicio_id
                            ORDER BY  liquidacion_comprobante_id,fecha_liq asc";
                //echo $query_resumen;die;
                $resumen = $this->db->query($query_resumen)->result();
                // $facturaa = $this->flash_md->getLiquidacionEdit($liquidacion_id);
                 $html_encabezado = '<table class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered" border="0">
                                                    <thead>
                                                        <tr>
                                                            <th>Comp. ing.</th>
                                                            <th>Remito</th>
                                                            <th style="text-align:center;">Fecha</th>
                                                            <th>Servicio</th>
                                                            <th style="text-align:center;">Cant Liquidar</th>
                                                            <th style="text-align:center;">Precio</th>
                                                            <th style="text-align:center;">Importe</th>
                                                        </tr>
                                                    </thead>
                                    <tbody id="body-grilla">';
                $suma_total = 0;
                $iva = 0.21;//$('#iva').val();
                //var_dump($facturaa);die;
                foreach ($facturab as $value) {
                      $precio = $value->precio;
                      $importe = $value->cant_a_liquidar*$precio;
                      if ($value->liquidacion_ajuste_id == 0){
                      $html_cuerpo    .= '<tr>
                                            <td>'.$value->comprobante_ingreso.'</td>
                                            <td>'.$value->remito_cliente.'</td>
                                            <td>'.$value->fecha_pedido.'</td>
                                            <td>'.$value->servicio.'</td>
                                            <td style="text-align:right;">'.$value->cant_a_liquidar.'</td>
                                            <td style="text-align:right">'.number_format ( $precio, 3).'</td>
                                            <td style="text-align:right">'.number_format($importe,3).'</td>
                                        </tr>';
                     }
                      
                    $contador++;
                    $suma_total = $suma_total + $importe;
                    //$iva = $iva + $importe * $iva_puro;
                 }
                 $total_iva = $suma_total * $iva;
                 $iva_solo =  $suma_total - $total_iva;
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
                                            ';
                                 //$('#grilla').append(cabecera+body+total+cierre); 
                 
                 $html_pdf_detalle = $html_encabezado.$html_cuerpo.$html_total.$html_cierre; 
                 
                 $html_cabecera_resumen = '<table class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered" style="width:70%">
                                                    <thead>
                                                        <tr>
                                                            <th>Código</th>
                                                            <th>Servicio</th>
                                                            <th colspan="2" style="text-align:right">Cantidad</th>
                                                        </tr>
                                                    </thead>';
                                 $html_body_resumen = '<tbody id="body-grilla-resumen">';
                                 foreach ($resumen as $value) {
                                     
                                      $html_body_resumen    .= '<tr id="" class="nueva">
                                                            <td>'.$value->servicio_id.'</td>
                                                            <td style="width:50%">'.$value->servicio.'</td>
                                                            <td style="text-align:right">'.$value->cant_a_liquidar.'</td>
                                                        </tr>';
                                 };
                                 $html_cierre_resumen = '</tbody></table>';
                                 $html_pdf_resumen = ($html_cabecera_resumen.$html_body_resumen.$html_cierre_resumen);
              
             }

             if ($tipo_factura == "A"){  //               var_dump($_POST);die;
                $result =  $this->codegen_model->row('flash_liquidaciones_clientes','*','id = '.$id);
                $query = "SELECT result.*, SUM(result.cant_a_liquidar) cant_a_liquidar FROM (SELECT   lc.liquidacion_cliente_id,
                                    lc.id liquidacion_comprobante_id,
                                    `ci`.`numero` `comprobante_ingreso`,
                                    lc.`remito_cliente` remito_cliente,
                                    ci.`fecha_pedido` fecha_pedido,
                                    lc.`servicio_id`, lc.`servicio`,
                                    lc.`cant_total`,
                                    lc.`cant_a_liquidar`,
                                    lc.`precio`,
                                    DATE_FORMAT(lc.fecha, '%d-%m-%Y') `fecha_liq`,
                                    0 liquidacion_ajuste_id
                            FROM  `flash_liquidaciones_comprobantes` `lc`   
                            JOIN `flash_liquidaciones_clientes` `l` ON `l`.`id` = `lc`.`liquidacion_cliente_id` 
                            JOIN `flash_comprobantes_ingresos` `ci` ON `lc`.`comprobante_ingreso_id` = `ci`.`id` 
                            WHERE `lc`.`liquidacion_cliente_id` = $liquidacion_id 
                            UNION 
                            SELECT  la2.liquidacion_cliente_id ,
                                    la2.liquidacion_comprobante_id liquidacion_comprobante_id,
                                    ci.`numero` comprobante_ingreso,
                                    lci.remito_cliente remito_cliente,
                                    ci.`fecha_pedido` fecha_pedido,
                                    lci.servicio_id servicio_id, 
                                    lci.servicio servicio,
                                    '--' cant_total,
                                    la2.cantidad `cant_a_liquidar`,
                                    la2.importe precio,
                                    DATE_FORMAT(la2.create, '%d-%m-%Y') `fecha_liq`,
                                    la2.id liquidacion_ajuste_id
                            FROM flash_liquidaciones_ajustes la2
                            INNER JOIN flash_liquidaciones_clientes l ON l.id = la2.liquidacion_cliente_id
                            INNER JOIN flash_liquidaciones_comprobantes lci ON la2.liquidacion_comprobante_id = lci.id
                            INNER JOIN flash_comprobantes_ingresos ci ON lci.comprobante_ingreso_id = ci.id
                            WHERE la2.liquidacion_cliente_id = $liquidacion_id) as result
                            GROUP BY result.comprobante_ingreso,result.servicio_id, result.remito_cliente
                            ORDER BY  liquidacion_comprobante_id,fecha_liq asc";
                //echo $query;die;
                $query_exe = $this->db->query($query);
                $facturaa = $query_exe->result();
//                $query = $this->db
//                            ->select('lc.*, ci.numero comprobante_ingreso, date_format(lc.fecha,"%d-%m-%Y") fecha_liq')
//                            ->join('flash_comprobantes_ingresos ci','lc.comprobante_ingreso_id = ci.id')
//                            ->where('lc.liquidacion_cliente_id = '.$id)
//                            ->get('flash_liquidaciones_comprobantes lc');
//                $detalles = $query->result();
//                echo $this->db->last_query();die;
                $query_resumen = "SELECT result.*, SUM(result.cant_a_liquidar) cant_a_liquidar FROM (SELECT   lc.liquidacion_cliente_id,
                                    lc.id liquidacion_comprobante_id,
                                    `ci`.`numero` `comprobante_ingreso`,
                                    lc.`remito_cliente` remito_cliente,
                                    ci.`fecha_pedido` fecha_pedido,
                                    lc.`servicio_id`, lc.`servicio`,
                                    lc.`cant_total`,
                                    lc.`cant_a_liquidar`,
                                    lc.`precio`,
                                    DATE_FORMAT(lc.fecha, '%d-%m-%Y') `fecha_liq`,
                                    0 liquidacion_ajuste_id
                            FROM  `flash_liquidaciones_comprobantes` `lc`   
                            JOIN `flash_liquidaciones_clientes` `l` ON `l`.`id` = `lc`.`liquidacion_cliente_id` 
                            JOIN `flash_comprobantes_ingresos` `ci` ON `lc`.`comprobante_ingreso_id` = `ci`.`id` 
                            WHERE `lc`.`liquidacion_cliente_id` = $liquidacion_id 
                            UNION 
                            SELECT  la2.liquidacion_cliente_id ,
                                    la2.liquidacion_comprobante_id liquidacion_comprobante_id,
                                    ci.`numero` comprobante_ingreso,
                                    lci.remito_cliente remito_cliente,
                                    ci.`fecha_pedido` fecha_pedido,
                                    lci.servicio_id servicio_id, 
                                    lci.servicio servicio,
                                    '--' cant_total,
                                    la2.cantidad `cant_a_liquidar`,
                                    la2.importe precio,
                                    DATE_FORMAT(la2.create, '%d-%m-%Y') `fecha_liq`,
                                    la2.id liquidacion_ajuste_id
                            FROM flash_liquidaciones_ajustes la2
                            INNER JOIN flash_liquidaciones_clientes l ON l.id = la2.liquidacion_cliente_id
                            INNER JOIN flash_liquidaciones_comprobantes lci ON la2.liquidacion_comprobante_id = lci.id
                            INNER JOIN flash_comprobantes_ingresos ci ON lci.comprobante_ingreso_id = ci.id
                            WHERE la2.liquidacion_cliente_id = $liquidacion_id) as result
                            GROUP BY result.servicio_id
                            ORDER BY  liquidacion_comprobante_id,fecha_liq asc";
                //echo $query_resumen;die;
                $resumen = $this->db->query($query_resumen)->result();
                // $facturaa = $this->flash_md->getLiquidacionEdit($liquidacion_id);
                 $html_encabezado = '<table class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered" border="0">
                                                    <thead>
                                                        <tr>
                                                            <th>Comp. ing.</th>
                                                            <th>Remito</th>
                                                            <th style="text-align:center;">Fecha</th>
                                                            <th>Servicio</th>
                                                            <th style="text-align:center;">Cant Liquidar</th>
                                                            <th style="text-align:center;">Precio</th>
                                                            <th style="text-align:center;">Importe</th>
                                                        </tr>
                                                    </thead>
                                    <tbody id="body-grilla">';
                $suma_total = 0;
                $iva = 0.21;//$('#iva').val();
                //var_dump($facturaa);die;
                foreach ($facturaa as $value) {
                      $precio = $value->precio;
                      
                      $importe = $value->cant_a_liquidar * $precio;
                      //echo $importe.'  '.$precio;die;
                      if ($value->liquidacion_ajuste_id == 0){
                      $html_cuerpo    .= '<tr>
                                            <td>'.$value->comprobante_ingreso.'</td>
                                            <td>'.$value->remito_cliente.'</td>
                                            <td>'.$value->fecha_pedido.'</td>
                                            <td>'.$value->servicio.'</td>
                                            <td style="text-align:right;">'.$value->cant_a_liquidar.'</td>
                                            <td style="text-align:right">'.$precio.'</td>
                                            <td style="text-align:right">'.number_format($importe,3).'</td>
                                        </tr>';
                     }
                      
                    $contador++;
                    $suma_total = $suma_total + $importe;
                    //$iva = $iva + $importe * $iva_puro;
                 }
                 $iva_solo = $suma_total * $iva;
                 $total_iva = $suma_total + $iva_solo ;
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
                 
                 $html_cabecera_resumen = '<table class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered" style="width:70%">
                                                    <thead>
                                                        <tr>
                                                            <th>Código</th>
                                                            <th>Servicio</th>
                                                            <th colspan="2" style="text-align:right">Cantidad</th>
                                                        </tr>
                                                    </thead>';
                                 $html_body_resumen = '<tbody id="body-grilla-resumen">';
                                 foreach ($resumen as $value) {
                                     
                                      $html_body_resumen    .= '<tr id="" class="nueva">
                                                            <td>'.$value->servicio_id.'</td>
                                                            <td style="width:50%">'.$value->servicio.'</td>
                                                            <td style="text-align:right">'.$value->cant_a_liquidar.'</td>
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
             $pdf->SetLiquidacionNro($liquidacion_id);
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
                                $liquidacion_nro, Cliente::whereId($cliente_id)->first()->nombre, $factura_nro, $periodo_desde, $periodo_hasta, $sucursal,$departamento,'');
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
        
}

/* End of file liquidaciones/liquidaciones_clientes.php */
/* Location: ./system/application/controllers/liquidaciones/liquidaciones_clientes.php */