<?php

class Esquema extends MY_Controller{
    
    public function __construct(){
        parent::__construct();
        $this->permisos = $this->permisos_lib->control();
        $this->load->model('Esquema_md');
        $this->load->helper('url');
    }

    public function index(){
        // Armo Array de la vista interna, asignandole Permisos, el Cliente obtenido y la bandera Data //
        $vista_interna = array(
            'permisos_efectivos' => $this->permisos,
            'results' => $this->Esquema_md->getEsquemas()
        );

        // Armo Array de la vista externa, agrego titulo y cargo la vista enviandole el Array de vista interna //
        $vista_externa = array(
            'title'          => ucwords("Gesti&#243;n de Esquemas"),
            'contenido_main' => $this->load->view('components/esquema/esquema_list', $vista_interna, true),
        );

        // Cargo vista //
        $this->load->view('template/backend', $vista_externa);

    }

    public function filtro($vista = NULL){
        if ($vista != null) $vista = '/'.$vista;
        if ($vista == null) $vista = '/esquema_list';

        $vista_interna = array(
            'permisos_efectivos' => $this->permisos,
            'results' => $this->Esquema_md->getEsquemas($this->input->post('buscar')),
        );

        $vista_externa = array(
            'title' => ucwords("Gesti&#243;n de Esquemas"),
            'contenido_main' => $this->load->view('components/esquema'.$vista, $vista_interna, true)
        );

        $this->load->view('template/backend', $vista_externa);
    }

    function create(){
        $vista_interna = array(
            'permisos_efectivos' => $this->permisos,
            'zonas' => $this->codegen_model->get('flash_tarifario_zonas', '*'),
            'plantillas' => $this->codegen_model->get('flash_plantillas_paqueteria', '*')
        );
                
        $vista_externa = array(
            'title' => ucwords("Nuevo Esquema Tarifario"),
            'contenido_main' => $this->load->view('components/esquema/create', $vista_interna, true)
        );

        $this->load->view('template/backend', $vista_externa);
    }

    function store(){
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $request = json_decode($stream_clean);
        
        $errors = [];
        
        //DATOS
        $nombre = $request->nombre;
        $cantidad = $request->cantidad;
        $userSesion = $this->ion_auth->user()->row();
        $user_id = $userSesion->id;
        
        //INICIO TRANSACCIÓN
        //$this->db->trans_start();

        //INSERTAR EL ESQUEMA
        $esquema_id = $this->Esquema_md->insertEsquema($nombre, $user_id);

        for ($x=0 ; $x < $cantidad ; $x++) { 
            $subEsquema = "sub_esquema_$x";

            //OBTENGO EL SUB ESQUEMA
            $esquema = $request->$subEsquema;
            //RECORRO OBTENGO LOS VALORES DE LAS CANTIDADES
            $cantidadKilos = $request->$subEsquema->cantidadKilos;
            $cantidadBultos = $request->$subEsquema->cantidadBultos;
            $cantidadMetroCubico = $request->$subEsquema->cantidadMetroCubico;
            $cantidadPalets = $request->$subEsquema->cantidadPalets;
            $cantidadGestionFlota = $request->$subEsquema->cantidadGestionFlota;
            $cantidadWareHouse = $request->$subEsquema->cantidadWareHouse;
            $cantidadCobranza = $request->$subEsquema->cantidadCobranza;
            $cantidadValorAgregado = $request->$subEsquema->cantidadValorDeclarado;
            $cantidadPesoAforado = $request->$subEsquema->cantidadPesoAforado;
            $cantidadDistancia = $request->$subEsquema->cantidadDistancia;
            $zonaA = $request->$subEsquema->zonaA;
            $zonaB = $request->$subEsquema->zonaB;
            //TRAIGO EL ID DE LA PLANTILLA Y LO INSERTO EN EL SUB ESQUEMA, PERO ESTE SOLO SERVIRA
            //PARA OBTENER EL NOMBRE DE LA PLANTILLA, NO HAY UNA REFERENCIA FK.
            $plantilla_id = $request->$subEsquema->plantilla_id;

            //INSERTO EL SUB ESQUEMA
            $sub_esquema_id = $this->Esquema_md->insertSubEsquema($esquema_id, $zonaA, $zonaB, $plantilla_id);

            //INSERTO DATOS PARA KG
            if($cantidadKilos > 0){
                for ($i=0; $i < count($request->$subEsquema->kilos->kilos); $i++) { 
                    //Trabajar los datos de kilos
                    $desde = trim($request->$subEsquema->kilos->kilos[$i]->desde);
                    $hasta = trim($request->$subEsquema->kilos->kilos[$i]->hasta);
                    $precio = trim($request->$subEsquema->kilos->kilos[$i]->precio);
                    $bandera = trim($request->$subEsquema->kilos->kilos[$i]->bandera);

                    if(!empty($desde) && !empty($hasta) && !empty($precio) && !empty($bandera)){
                        $this->Esquema_md->insertDetalleKilos($zonaA, $zonaB, $desde, $hasta, $precio, $bandera, $sub_esquema_id);
                    }else{
                        $mensaje = "Error: no se pudo insertar los detalles de kilos, alguno de los campos se encuentra vacio.";
                        array_push($errors, $mensaje);
                    }

                }
            }

            //INSERTO DATO PARA BULTOS
            if($cantidadBultos > 0){
                for ($i=0; $i < count($request->$subEsquema->bultos->bultos); $i++) { 
                    //Trabajar los datos de bultos
                    $desde = trim($request->$subEsquema->bultos->bultos[$i]->desde);
                    $hasta = trim($request->$subEsquema->bultos->bultos[$i]->hasta);
                    $precio = trim($request->$subEsquema->bultos->bultos[$i]->precio);
                    $bandera = trim($request->$subEsquema->bultos->bultos[$i]->bandera);

                    if(!empty($desde) && !empty($hasta) && !empty($precio) && !empty($bandera)){
                        $this->Esquema_md->insertDetalleBultos($zonaA, $zonaB, $desde, $hasta, $precio, $bandera, $sub_esquema_id);
                    }else{
                        $mensaje = "Error: no se pudo insertar los detalles de bultos, alguno de los campos se encuentra vacio.";
                        array_push($errors, $mensaje);
                    }

                }
            }

            //INSERTO DATO PARA METROS CUBICOS
            if($cantidadMetroCubico > 0){
                for ($i=0; $i < count($request->$subEsquema->metrosCubicos->metrosCubicos); $i++) { 
                    //Trabajar los datos de metros cubicos
                    $desde = trim($request->$subEsquema->metrosCubicos->metrosCubicos[$i]->desde);
                    $hasta = trim($request->$subEsquema->metrosCubicos->metrosCubicos[$i]->hasta);
                    $precio = trim($request->$subEsquema->metrosCubicos->metrosCubicos[$i]->precio);
                    $bandera = trim($request->$subEsquema->metrosCubicos->metrosCubicos[$i]->bandera);

                    if(!empty($desde) && !empty($hasta) && !empty($precio) && !empty($bandera)){
                        $this->Esquema_md->insertDetalleMetroCubico($zonaA, $zonaB, $desde, $hasta, $precio, $bandera, $sub_esquema_id);
                    }else{
                        $mensaje = "Error: no se pudo insertar los detalles de metros cubicos, alguno de los campos se encuentra vacio.";
                        array_push($errors, $mensaje);
                    }

                }
            }

            //INSERTO DATO PARA PALETS
            if($cantidadPalets > 0){
                for ($i=0; $i < count($request->$subEsquema->palets->palets); $i++) { 
                    //Trabajar los datos de palets
                    $desde = trim($request->$subEsquema->palets->palets[$i]->desde);
                    $hasta = trim($request->$subEsquema->palets->palets[$i]->hasta);
                    $precio = trim($request->$subEsquema->palets->palets[$i]->precio);
                    $bandera = trim($request->$subEsquema->palets->palets[$i]->bandera);

                    if(!empty($desde) && !empty($hasta) && !empty($precio) && !empty($bandera)){
                        $this->Esquema_md->insertDetallePalets($zonaA, $zonaB, $desde, $hasta, $precio, $bandera, $sub_esquema_id);
                    }else{
                        $mensaje = "Error: no se pudo insertar los detalles de palets, alguno de los campos se encuentra vacio.";
                        array_push($errors, $mensaje);
                    }

                }
            }

            //INSERTO DATO PARA WAREHOUSE
            if($cantidadWareHouse > 0){
                for ($i=0; $i < count($request->$subEsquema->warehouse->warehouse); $i++) { 
                    //Trabajar los datos de warehouse
                    $desde = trim($request->$subEsquema->warehouse->warehouse[$i]->desde);
                    $hasta = trim($request->$subEsquema->warehouse->warehouse[$i]->hasta);
                    $precio = trim($request->$subEsquema->warehouse->warehouse[$i]->precio);

                    if(!empty($desde) && !empty($hasta) && !empty($precio)){
                        $this->Esquema_md->insertDetalleWareHouse($zonaA, $zonaB, $desde, $hasta, $precio, $sub_esquema_id);
                    }else{
                        $mensaje = "Error: no se pudo insertar los detalles de warehouse, alguno de los campos se encuentra vacio.";
                        array_push($errors, $mensaje);
                    }

                }
            }

            //INSERTO DATO PARA GESTION DE FLOTA
            if($cantidadGestionFlota > 0){
                for ($i=0; $i < count($request->$subEsquema->gestionFlota->gestionFlota); $i++) { 
                    //Trabajar los datos de gestion flota
                    $tipo = trim($request->$subEsquema->gestionFlota->gestionFlota[$i]->tipo);
                    $precio = trim($request->$subEsquema->gestionFlota->gestionFlota[$i]->precio);

                    switch ($tipo) {
                        case 1:
                            $vehiculo = 1; 
                            $hora = 1; 
                            break;
                        case 2:
                            $vehiculo = 1; 
                            $hora = 2; 
                            break;
                        case 3:
                            $vehiculo = 2; 
                            $hora = 1;
                            break;
                        case 4:
                            $vehiculo = 2; 
                            $hora = 2; 
                            break;
                        case 5:
                            $vehiculo = 3; 
                            $hora = 1; 
                            break;
                        case 6:
                            $vehiculo = 3; 
                            $hora = 2; 
                            break;
                        case 7:
                            $vehiculo = 4; 
                            $hora = 1; 
                            break;
                        case 8:
                            $vehiculo = 4; 
                            $hora = 2; 
                            break;
                        case 9:
                            $vehiculo = 5; 
                            $hora = 1; 
                            break;
                        case 10:
                            $vehiculo = 5; 
                            $hora = 2; 
                            break;
                    }

                    if(!empty($precio) && !empty($vehiculo) && !empty($hora)) {    
                        $this->Esquema_md->insertDetalleGestionFlota($zonaA, $zonaB, $vehiculo, $hora, $precio, $sub_esquema_id);
                    }else{
                        $mensaje = "Error: no se pudo insertar los detalles de gestión de flota, alguno de los campos se encuentra vacio.";
                        array_push($errors, $mensaje);
                    }


                }
            }

            //INSERTO DATO PARA COBRANZA
            if($cantidadCobranza > 0){
                
                //Trabajar los datos de cobranza
                $valor = trim($request->$subEsquema->cobranza->cobranza->valor);
                $tipo = trim($request->$subEsquema->cobranza->cobranza->tipo);

                if(!empty($valor) && !empty($tipo)){
                    $this->Esquema_md->insertDetalleCobranza($zonaA, $zonaB, $valor, $tipo, $sub_esquema_id);
                }else{
                    $mensaje = "Error: no se pudo insertar los detalles de cobranza, alguno de los campos se encuentra vacio.";
                    array_push($errors, $mensaje);
                }
               
            }

            //INSERTO DATO PARA VALOR DECLARADO
            if($cantidadValorAgregado > 0){
                
                //Trabajar los datos de valor declarado
                $valor = trim($request->$subEsquema->valorDeclarado->valorDeclarado->valor);
                $tipo = trim($request->$subEsquema->valorDeclarado->valorDeclarado->tipo);

                if(!empty($valor) && !empty($tipo)){
                    $this->Esquema_md->insertDetalleValorDeclarado($zonaA, $zonaB, $valor, $tipo, $sub_esquema_id);
                }else{
                    $mensaje = "Error: no se pudo insertar los detalles de valor declarado, alguno de los campos se encuentra vacio.";
                    array_push($errors, $mensaje);
                }
               
            }

            //INSERTO DATO PARA PESO AFORADO
            if($cantidadPesoAforado > 0){
                
                //Trabajar los datos de valor declarado
                $valor = trim($request->$subEsquema->pesoAforado->pesoAforado->valor);

                if(!empty($valor)){
                    $this->Esquema_md->insertDetallePesoAforado($zonaA, $zonaB, $valor, $sub_esquema_id);
                }else{
                    $mensaje = "Error: no se pudo insertar los detalles de peso aforado, alguno de los campos se encuentra vacio.";
                    array_push($errors, $mensaje);
                }
               
            }

            //INSERTO DATOS PARA DISTANCIA
            if($cantidadDistancia > 0){
                for ($i=0; $i < count($request->$subEsquema->distancia->distancia); $i++) { 
                    //Trabajar los datos de kilos
                    $desde = trim($request->$subEsquema->distancia->distancia[$i]->desde);
                    $hasta = trim($request->$subEsquema->distancia->distancia[$i]->hasta);
                    $precio = trim($request->$subEsquema->distancia->distancia[$i]->precio);

                    if(!empty($desde) && !empty($hasta) && !empty($precio)){
                        $this->Esquema_md->insertDetalleDistancia($zonaA, $zonaB, $desde, $hasta, $precio, $sub_esquema_id);
                    }else{
                        $mensaje = "Error: no se pudo insertar los detalles de distancia, alguno de los campos se encuentra vacio.";
                        array_push($errors, $mensaje);
                    }

                }
            }

        }
        
        //FINALIZO TRANSACCION
        //$this->db->trans_complete();

        if(count($errors) > 0){
            echo json_encode(["status" => 500, "errors" => $errors]);
        }else{
            echo json_encode(["status" => 201]);
        }

        //redirect('/plantillas/plantillas/index', 'refresh');

    }

    function getDetallePlantilla($id){

        $json = [
            "plantilla" => $this->codegen_model->row('flash_plantillas_paqueteria', '*', 'id = ' . $id),
            "kilos" => $this->codegen_model->get('flash_plantilla_detalle_tarifa_kg', '*', 'plantilla_id = ' . $id),
            "bultos" => $this->codegen_model->get('flash_plantilla_detalle_tarifa_bultos', '*', 'plantilla_id = ' . $id),
            "metro_cubico" => $this->codegen_model->get('flash_plantilla_detalle_tarifa_metro_cubico', '*', 'plantilla_id = ' . $id),
            "palets" => $this->codegen_model->get('flash_plantilla_detalle_tarifa_palets', '*', 'plantilla_id = ' . $id),
            "warehouse" => $this->codegen_model->get('flash_plantilla_detalle_tarifa_warehouse', '*', 'plantilla_id = ' . $id),
            "gestion_flota" => $this->codegen_model->get('flash_plantilla_detalle_tarifa_gestion_flota', '*', 'plantilla_id = ' . $id),
            "valor_declarado" => $this->codegen_model->row('flash_plantilla_detalle_tarifa_valor_declarado', '*', 'plantilla_id = ' . $id),
            "cobranza" => $this->codegen_model->row('flash_plantilla_detalle_tarifa_cobranza', '*', 'plantilla_id = ' . $id),
            "peso_aforado" => $this->codegen_model->row('flash_plantilla_detalle_tarifa_peso_aforado', '*', 'plantilla_id = ' . $id),   
            "distancia" => $this->codegen_model->get('flash_plantilla_detalle_tarifa_distancia', '*', 'plantilla_id = ' . $id),
        ];
        
        if($json){
            echo json_encode($json);
        }else{
            echo json_encode("");
        } 
    }

    function view($id){
        /*
        CUANDO LO MOSTRABA EN EL MODAL, TENIA ESTA CONFIGURACION

        $subEsquemas = $this->codegen_model->get('sub_esquema', '*', 'esquema_id = ' . $id);
        $ids = [];
        //OBTENGO LOS IDS DE LOS SUB ESQUEMAS
        for ($i=0; $i < count($subEsquemas); $i++) { 
            array_push($ids, json_encode( (int)$subEsquemas[$i]->id ) );
        }

        $vista_interna = array(
			'permisos_efectivos' => $this->permisos,
            'esquema' => $this->codegen_model->row('flash_esquema', '*', 'id = ' . $id),
            'subEsquemas' => $this->codegen_model->get('sub_esquema', '*', 'esquema_id = ' . $id),

            'kilos' => $this->Esquema_md->getKilosPorSubEsquema($ids),
            'bultos' => $this->Esquema_md->getBultosPorSubEsquema($ids),
            'metrosCubicos' => $this->Esquema_md->getMetrosCubicosPorSubEsquema($ids),
            'palets' => $this->Esquema_md->getPaletsPorSubEsquema($ids),
            'warehouse' => $this->Esquema_md->getWarehousesPorSubEsquema($ids),
            'gestionFlota' => $this->Esquema_md->getGestionFlotasPorSubEsquema($ids),
            'valorDeclarado' => $this->Esquema_md->getValorDeclaradosPorSubEsquema($ids),
            'cobranza' => $this->Esquema_md->getCobranzasPorSubEsquema($ids),
            'pesoAforado' => $this->Esquema_md->getPesoAforadosPorSubEsquema($ids)
		);

		$vista_externa = array(			
			'title' => ucwords("Detalle Esquema"),
			'contenido_main' => $this->load->view('components/esquema/view', $vista_interna, true)
		);		  
   
		$this->load->view('template/view', $vista_externa);
        */

        $subEsquemas = $this->codegen_model->get('sub_esquema', '*', 'esquema_id = ' . $id);
        $ids = [];
        //OBTENGO LOS IDS DE LOS SUB ESQUEMAS
        for ($i=0; $i < count($subEsquemas); $i++) { 
            array_push($ids, json_encode( (int)$subEsquemas[$i]->id ) );
        }

        $vista_interna = array(
			'permisos_efectivos' => $this->permisos,
            'esquema' => $this->codegen_model->row('flash_esquema', '*', 'id = ' . $id),
            'subEsquemas' => $this->codegen_model->get('sub_esquema', '*', 'esquema_id = ' . $id),
            'zonas' => $this->codegen_model->get('flash_tarifario_zonas', '*'),
            'plantillas' => $this->codegen_model->get('flash_plantillas_paqueteria', '*'),
            
            'kilos' => $this->Esquema_md->getKilosPorSubEsquema($ids),
            'bultos' => $this->Esquema_md->getBultosPorSubEsquema($ids),
            'metrosCubicos' => $this->Esquema_md->getMetrosCubicosPorSubEsquema($ids),
            'palets' => $this->Esquema_md->getPaletsPorSubEsquema($ids),
            'warehouse' => $this->Esquema_md->getWarehousesPorSubEsquema($ids),
            'gestionFlota' => $this->Esquema_md->getGestionFlotasPorSubEsquema($ids),
            'valorDeclarado' => $this->Esquema_md->getValorDeclaradosPorSubEsquema($ids),
            'cobranza' => $this->Esquema_md->getCobranzasPorSubEsquema($ids),
            'pesoAforado' => $this->Esquema_md->getPesoAforadosPorSubEsquema($ids),
            'distancia' => $this->Esquema_md->getDistanciaPorSubEsquema($ids),
		);

		$vista_externa = array(			
			'title' => ucwords("Detalle Esquema"),
			'contenido_main' => $this->load->view('components/esquema/view2', $vista_interna, true)
		);		  
   
		$this->load->view('template/backend', $vista_externa);
    }

    function edit($id){
        $subEsquemas = $this->codegen_model->get('sub_esquema', '*', 'esquema_id = ' . $id);
        $ids = [];
        //OBTENGO LOS IDS DE LOS SUB ESQUEMAS
        for ($i=0; $i < count($subEsquemas); $i++) { 
            array_push($ids, json_encode( (int)$subEsquemas[$i]->id ) );
        }

        $vista_interna = array(
			'permisos_efectivos' => $this->permisos,
            'esquema' => $this->codegen_model->row('flash_esquema', '*', 'id = ' . $id),
            'subEsquemas' => $this->codegen_model->get('sub_esquema', '*', 'esquema_id = ' . $id),
            'zonas' => $this->codegen_model->get('flash_tarifario_zonas', '*'),
            'plantillas' => $this->codegen_model->get('flash_plantillas_paqueteria', '*'),

            'kilos' => $this->Esquema_md->getKilosPorSubEsquema($ids),
            'bultos' => $this->Esquema_md->getBultosPorSubEsquema($ids),
            'metrosCubicos' => $this->Esquema_md->getMetrosCubicosPorSubEsquema($ids),
            'palets' => $this->Esquema_md->getPaletsPorSubEsquema($ids),
            'warehouse' => $this->Esquema_md->getWarehousesPorSubEsquema($ids),
            'gestionFlota' => $this->Esquema_md->getGestionFlotasPorSubEsquema($ids),
            'valorDeclarado' => $this->Esquema_md->getValorDeclaradosPorSubEsquema($ids),
            'cobranza' => $this->Esquema_md->getCobranzasPorSubEsquema($ids),
            'pesoAforado' => $this->Esquema_md->getPesoAforadosPorSubEsquema($ids),
            'distancia' => $this->Esquema_md->getDistanciaPorSubEsquema($ids),
		);

		$vista_externa = array(			
			'title' => ucwords("Editar Esquema"),
			'contenido_main' => $this->load->view('components/esquema/edit', $vista_interna, true)
		);		  
   
		$this->load->view('template/backend', $vista_externa);

    }

    function update($id){
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $request = json_decode($stream_clean);

        $errors = [];
        
        //DATOS
        $nombre = $request->nombre;
        $cantidad = $request->cantidad;
        $userSesion = $this->ion_auth->user()->row();
        $user_id = $userSesion->id;
        
        //INICIO TRANSACCIÓN
        $this->db->trans_start();

        //ACTUALIZAR PLANTILLA
        $data["nombre"] = $nombre;
        $data["user_id"] = $user_id;
        $this->codegen_model->edit('flash_esquema', $data, 'id', $id);

        //ELIMINAR LOS SUB ESQUEMAS
        $this->codegen_model->delete('sub_esquema', 'esquema_id', $id);


        for ($x=0 ; $x < $cantidad ; $x++) { 
            $subEsquema = "sub_esquema_$x";

            //OBTENGO EL SUB ESQUEMA
            $esquema = $request->$subEsquema;
            //RECORRO OBTENGO LOS VALORES DE LAS CANTIDADES
            $cantidadKilos = $request->$subEsquema->cantidadKilos;
            $cantidadBultos = $request->$subEsquema->cantidadBultos;
            $cantidadMetroCubico = $request->$subEsquema->cantidadMetroCubico;
            $cantidadPalets = $request->$subEsquema->cantidadPalets;
            $cantidadGestionFlota = $request->$subEsquema->cantidadGestionFlota;
            $cantidadWareHouse = $request->$subEsquema->cantidadWareHouse;
            $cantidadCobranza = $request->$subEsquema->cantidadCobranza;
            $cantidadValorAgregado = $request->$subEsquema->cantidadValorDeclarado;
            $cantidadPesoAforado = $request->$subEsquema->cantidadPesoAforado;
            $cantidadDistancia = $request->$subEsquema->cantidadDistancia;
            $zonaA = $request->$subEsquema->zonaA;
            $zonaB = $request->$subEsquema->zonaB;
            //TRAIGO EL ID DE LA PLANTILLA Y LO INSERTO EN EL SUB ESQUEMA, PERO ESTE SOLO SERVIRA
            //PARA OBTENER EL NOMBRE DE LA PLANTILLA, NO HAY UNA REFERENCIA FK.
            $plantilla_id = $request->$subEsquema->plantilla_id;

            //INSERTO EL SUB ESQUEMA
            $sub_esquema_id = $this->Esquema_md->insertSubEsquema($id, $zonaA, $zonaB, $plantilla_id);

            //INSERTO DATOS PARA KG
            if($cantidadKilos > 0){
                for ($i=0; $i < count($request->$subEsquema->kilos->kilos); $i++) { 
                    //Trabajar los datos de kilos
                    $desde = trim($request->$subEsquema->kilos->kilos[$i]->desde);
                    $hasta = trim($request->$subEsquema->kilos->kilos[$i]->hasta);
                    $precio = trim($request->$subEsquema->kilos->kilos[$i]->precio);
                    $bandera = trim($request->$subEsquema->kilos->kilos[$i]->bandera);

                    if(!empty($desde) && !empty($hasta) && !empty($precio) && !empty($bandera)){
                        $this->Esquema_md->insertDetalleKilos($zonaA, $zonaB, $desde, $hasta, $precio, $bandera, $sub_esquema_id);
                    }else{
                        $mensaje = "Error: no se pudo insertar los detalles de kilos, alguno de los campos se encuentra vacio.";
                        array_push($errors, $mensaje);
                    }

                }
            }

            //INSERTO DATO PARA BULTOS
            if($cantidadBultos > 0){
                for ($i=0; $i < count($request->$subEsquema->bultos->bultos); $i++) { 
                    //Trabajar los datos de bultos
                    $desde = trim($request->$subEsquema->bultos->bultos[$i]->desde);
                    $hasta = trim($request->$subEsquema->bultos->bultos[$i]->hasta);
                    $precio = trim($request->$subEsquema->bultos->bultos[$i]->precio);
                    $bandera = trim($request->$subEsquema->bultos->bultos[$i]->bandera);

                    if(!empty($desde) && !empty($hasta) && !empty($precio) && !empty($bandera)){
                        $this->Esquema_md->insertDetalleBultos($zonaA, $zonaB, $desde, $hasta, $precio, $bandera, $sub_esquema_id);
                    }else{
                        $mensaje = "Error: no se pudo insertar los detalles de bultos, alguno de los campos se encuentra vacio.";
                        array_push($errors, $mensaje);
                    }

                }
            }

            //INSERTO DATO PARA METROS CUBICOS
            if($cantidadMetroCubico > 0){
                for ($i=0; $i < count($request->$subEsquema->metrosCubicos->metrosCubicos); $i++) { 
                    //Trabajar los datos de metros cubicos
                    $desde = trim($request->$subEsquema->metrosCubicos->metrosCubicos[$i]->desde);
                    $hasta = trim($request->$subEsquema->metrosCubicos->metrosCubicos[$i]->hasta);
                    $precio = trim($request->$subEsquema->metrosCubicos->metrosCubicos[$i]->precio);
                    $bandera = trim($request->$subEsquema->metrosCubicos->metrosCubicos[$i]->bandera);

                    if(!empty($desde) && !empty($hasta) && !empty($precio) && !empty($bandera)){
                        $this->Esquema_md->insertDetalleMetroCubico($zonaA, $zonaB, $desde, $hasta, $precio, $bandera, $sub_esquema_id);
                    }else{
                        $mensaje = "Error: no se pudo insertar los detalles de metros cubicos, alguno de los campos se encuentra vacio.";
                        array_push($errors, $mensaje);
                    }

                }
            }

            //INSERTO DATO PARA PALETS
            if($cantidadPalets > 0){
                for ($i=0; $i < count($request->$subEsquema->palets->palets); $i++) { 
                    //Trabajar los datos de palets
                    $desde = trim($request->$subEsquema->palets->palets[$i]->desde);
                    $hasta = trim($request->$subEsquema->palets->palets[$i]->hasta);
                    $precio = trim($request->$subEsquema->palets->palets[$i]->precio);
                    $bandera = trim($request->$subEsquema->palets->palets[$i]->bandera);

                    if(!empty($desde) && !empty($hasta) && !empty($precio) && !empty($bandera)){
                        $this->Esquema_md->insertDetallePalets($zonaA, $zonaB, $desde, $hasta, $precio, $bandera, $sub_esquema_id);
                    }else{
                        $mensaje = "Error: no se pudo insertar los detalles de palets, alguno de los campos se encuentra vacio.";
                        array_push($errors, $mensaje);
                    }

                }
            }

            //INSERTO DATO PARA WAREHOUSE
            if($cantidadWareHouse > 0){
                for ($i=0; $i < count($request->$subEsquema->warehouse->warehouse); $i++) { 
                    //Trabajar los datos de warehouse
                    $desde = trim($request->$subEsquema->warehouse->warehouse[$i]->desde);
                    $hasta = trim($request->$subEsquema->warehouse->warehouse[$i]->hasta);
                    $precio = trim($request->$subEsquema->warehouse->warehouse[$i]->precio);

                    if(!empty($desde) && !empty($hasta) && !empty($precio)){
                        $this->Esquema_md->insertDetalleWareHouse($zonaA, $zonaB, $desde, $hasta, $precio, $sub_esquema_id);
                    }else{
                        $mensaje = "Error: no se pudo insertar los detalles de warehouse, alguno de los campos se encuentra vacio.";
                        array_push($errors, $mensaje);
                    }

                }
            }

            //INSERTO DATO PARA GESTION DE FLOTA
            if($cantidadGestionFlota > 0){
                for ($i=0; $i < count($request->$subEsquema->gestionFlota->gestionFlota); $i++) { 
                    //Trabajar los datos de gestion flota
                    $tipo = trim($request->$subEsquema->gestionFlota->gestionFlota[$i]->tipo);
                    $precio = trim($request->$subEsquema->gestionFlota->gestionFlota[$i]->precio);

                    switch ($tipo) {
                        case 1:
                            $vehiculo = 1; 
                            $hora = 1; 
                            break;
                        case 2:
                            $vehiculo = 1; 
                            $hora = 2; 
                            break;
                        case 3:
                            $vehiculo = 2; 
                            $hora = 1;
                            break;
                        case 4:
                            $vehiculo = 2; 
                            $hora = 2; 
                            break;
                        case 5:
                            $vehiculo = 3; 
                            $hora = 1; 
                            break;
                        case 6:
                            $vehiculo = 3; 
                            $hora = 2; 
                            break;
                        case 7:
                            $vehiculo = 4; 
                            $hora = 1; 
                            break;
                        case 8:
                            $vehiculo = 4; 
                            $hora = 2; 
                            break;
                        case 9:
                            $vehiculo = 5; 
                            $hora = 1; 
                            break;
                        case 10:
                            $vehiculo = 5; 
                            $hora = 2; 
                            break;
                    }

                    if(!empty($precio) && !empty($vehiculo) && !empty($hora)) {    
                        $this->Esquema_md->insertDetalleGestionFlota($zonaA, $zonaB, $vehiculo, $hora, $precio, $sub_esquema_id);
                    }else{
                        $mensaje = "Error: no se pudo insertar los detalles de gestión de flota, alguno de los campos se encuentra vacio.";
                        array_push($errors, $mensaje);
                    }


                }
            }

            //INSERTO DATO PARA COBRANZA
            if($cantidadCobranza > 0){
                
                //Trabajar los datos de cobranza
                $valor = trim($request->$subEsquema->cobranza->cobranza->valor);
                $tipo = trim($request->$subEsquema->cobranza->cobranza->tipo);

                if(!empty($valor) && !empty($tipo)){
                    $this->Esquema_md->insertDetalleCobranza($zonaA, $zonaB, $valor, $tipo, $sub_esquema_id);
                }else{
                    $mensaje = "Error: no se pudo insertar los detalles de cobranza, alguno de los campos se encuentra vacio.";
                    array_push($errors, $mensaje);
                }
               
            }

            //INSERTO DATO PARA VALOR DECLARADO
            if($cantidadValorAgregado > 0){
                
                //Trabajar los datos de valor declarado
                $valor = trim($request->$subEsquema->valorDeclarado->valorDeclarado->valor);
                $tipo = trim($request->$subEsquema->valorDeclarado->valorDeclarado->tipo);

                if(!empty($valor) && !empty($tipo)){
                    $this->Esquema_md->insertDetalleValorDeclarado($zonaA, $zonaB, $valor, $tipo, $sub_esquema_id);
                }else{
                    $mensaje = "Error: no se pudo insertar los detalles de valor declarado, alguno de los campos se encuentra vacio.";
                    array_push($errors, $mensaje);
                }
               
            }

            //INSERTO DATO PARA PESO AFORADO
            if($cantidadPesoAforado > 0){
                
                //Trabajar los datos de valor declarado
                $valor = trim($request->$subEsquema->pesoAforado->pesoAforado->valor);

                if(!empty($valor)){
                    $this->Esquema_md->insertDetallePesoAforado($zonaA, $zonaB, $valor, $sub_esquema_id);
                }else{
                    $mensaje = "Error: no se pudo insertar los detalles de peso aforado, alguno de los campos se encuentra vacio.";
                    array_push($errors, $mensaje);
                }
               
            }

            //INSERTO DATOS PARA DISTANCIA
            if($cantidadDistancia > 0){
                for ($i=0; $i < count($request->$subEsquema->distancia->distancia); $i++) { 
                    //Trabajar los datos de kilos
                    $desde = trim($request->$subEsquema->distancia->distancia[$i]->desde);
                    $hasta = trim($request->$subEsquema->distancia->distancia[$i]->hasta);
                    $precio = trim($request->$subEsquema->distancia->distancia[$i]->precio);

                    if(!empty($desde) && !empty($hasta) && !empty($precio)){
                        $this->Esquema_md->insertDetalleDistancia($zonaA, $zonaB, $desde, $hasta, $precio, $sub_esquema_id);
                    }else{
                        $mensaje = "Error: no se pudo insertar los detalles de distancia, alguno de los campos se encuentra vacio.";
                        array_push($errors, $mensaje);
                    }

                }
            }

        }
        
        //FINALIZO TRANSACCION
        $this->db->trans_complete();

        if(count($errors) > 0){
            echo json_encode(["status" => 500, "errors" => $errors]);
        }else{
            echo json_encode(["status" => 204]);
        }

    }

    function delete($id){
        $this->codegen_model->delete('flash_esquema','id',$id);
        redirect('/esquema/esquema/index', 'refresh');    
    }

    function comprobarQueNoEsteAsignadoAClientes(){
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $request = json_decode($stream_clean);

        $resultado = $this->Esquema_md->comprobarQueNoEsteAsignadoAClientes($request->id);

        echo json_encode(["data" => $resultado]);
    }

}