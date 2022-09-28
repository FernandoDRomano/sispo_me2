<?php

class Plantillas extends MY_Controller{
    
    public function __construct(){
        parent::__construct();
        $this->permisos = $this->permisos_lib->control();
        $this->load->model('Plantilla_md');
        $this->load->helper('url');
    }

    public function index()
    {
        // Armo Array de la vista interna, asignandole Permisos, el Cliente obtenido y la bandera Data //
        $vista_interna = array(
            'permisos_efectivos' => $this->permisos,
            'results' => $this->Plantilla_md->getPlantillas()
        );

        // Armo Array de la vista externa, agrego titulo y cargo la vista enviandole el Array de vista interna //
        $vista_externa = array(
            'title'          => ucwords("Gesti&#243;n de Plantillas"),
            'contenido_main' => $this->load->view('components/plantilla/plantilla_list', $vista_interna, true),
        );

        // Cargo vista //
        $this->load->view('template/backend', $vista_externa);

    }

    public function filtro($vista = NULL){
        if ($vista != null) $vista = '/'.$vista;
        if ($vista == null) $vista = '/plantilla_list';

        $vista_interna = array(
            'permisos_efectivos' => $this->permisos,
            'results' => $this->Plantilla_md->getPlantillas($this->input->post('buscar')),
        );

        $vista_externa = array(
            'title' => ucwords("clientes"),
            'contenido_main' => $this->load->view('components/plantilla'.$vista, $vista_interna, true)
        );

        $this->load->view('template/backend', $vista_externa);
    }

    public function create(){

        $vista_interna = array(
            'permisos_efectivos' => $this->permisos,
        );
                
        $vista_externa = array(
            'title' => ucwords("Nueva Plantilla"),
            'contenido_main' => $this->load->view('components/plantilla/create', $vista_interna, true)
        );

        $this->load->view('template/backend', $vista_externa);
    }

    public function store(){
        //DATOS
        $data['nombre'] = trim($this->input->post('nombre'));
        $data['descripcion'] = trim($this->input->post('descripcion'));
        $userSesion = $this->ion_auth->user()->row();
        $data['user_id'] = $userSesion->id;

        $cantidadKilos = trim($this->input->post('cantidad_fila_kilos'));
        $cantidadBultos = trim($this->input->post('cantidad_fila_bultos'));
        $cantidadMetroCubico = trim($this->input->post('cantidad_fila_metro_cubico'));
        $cantidadPalets = trim($this->input->post('cantidad_fila_palets'));
        $cantidadGestionFlota = trim($this->input->post('cantidad_fila_gestion_flota'));
        $cantidadWareHouse = trim($this->input->post('cantidad_fila_warehouse'));
        $cantidadCobranza = trim($this->input->post('cantidad_fila_cobranza'));
        $cantidadValorAgregado = trim($this->input->post('cantidad_fila_valor_declarado'));
        $cantidadPesoAforado = trim($this->input->post('cantidad_fila_peso_aforado'));
        $cantidadDistancia = trim($this->input->post('cantidad_fila_distancia'));

        $plantilla_id = $this->codegen_model->add('flash_plantillas_paqueteria', $data);
        
        if($cantidadKilos > 0){
            for ($i=1 ; $i <= $cantidadKilos ; $i++) { 
                //Obtiene datos de los campos          
                $plantilla_id = $plantilla_id;
                $rangoInicio =  trim($this->input->post('rinicio_kilos' . $i));
                $rangoFin = trim($this->input->post('rfin_kilos' . $i));
                $precio_rango = trim($this->input->post('precio_kilos' . $i));
                $bandera_tipo = trim($this->input->post('opciones_tipo_kilos' . $i));
          
                if(!empty($rangoInicio) && !empty($rangoFin) && !empty($precio_rango) && !empty($bandera_tipo)) {
                    $this->Plantilla_md->insertDetalleKilos($rangoInicio, $rangoFin, $precio_rango, $bandera_tipo, $plantilla_id);
                }
            }
        }

        if($cantidadBultos > 0){
            for ($i=1 ; $i <= $cantidadBultos ; $i++) { 
                //Obtiene datos de los campos          
                $plantilla_id = $plantilla_id;
                $rangoInicio =  trim($this->input->post('rinicio_bultos' . $i));
                $rangoFin = trim($this->input->post('rfin_bultos' . $i));
                $precio_rango = trim($this->input->post('precio_bultos' . $i));
                $bandera_tipo = trim($this->input->post('opciones_tipo_bultos' . $i));
          
                if(!empty($rangoInicio) && !empty($rangoFin) && !empty($precio_rango) && !empty($bandera_tipo)) {
                    $this->Plantilla_md->insertDetalleBultos($rangoInicio, $rangoFin, $precio_rango, $bandera_tipo, $plantilla_id);
                }
            }
        }

        if($cantidadMetroCubico > 0){
            for ($i=1 ; $i <= $cantidadMetroCubico ; $i++) { 
                //Obtiene datos de los campos          
                $plantilla_id = $plantilla_id;
                $rangoInicio =  trim($this->input->post('rinicio_mc' . $i));
                $rangoFin = trim($this->input->post('rfin_mc' . $i));
                $precio_rango = trim($this->input->post('precio_mc' . $i));
                $bandera_tipo = trim($this->input->post('opciones_tipo_mc' . $i));
          
                if(!empty($rangoInicio) && !empty($rangoFin) && !empty($precio_rango) && !empty($bandera_tipo)) {
                    $this->Plantilla_md->insertDetalleMetroCubico($rangoInicio, $rangoFin, $precio_rango, $bandera_tipo, $plantilla_id);
                }
            }
        }

        if($cantidadPalets > 0){
            for ($i=1 ; $i <= $cantidadPalets ; $i++) { 
                //Obtiene datos de los campos          
                $plantilla_id = $plantilla_id;
                $rangoInicio =  trim($this->input->post('rinicio_palets' . $i));
                $rangoFin = trim($this->input->post('rfin_palets' . $i));
                $precio_rango = trim($this->input->post('precio_palets' . $i));
                $bandera_tipo = trim($this->input->post('opciones_tipo_palets' . $i));
          
                if(!empty($rangoInicio) && !empty($rangoFin) && !empty($precio_rango) && !empty($bandera_tipo)) {
                    $this->Plantilla_md->insertDetallePalets($rangoInicio, $rangoFin, $precio_rango, $bandera_tipo, $plantilla_id);
                }
            }
        }

        if($cantidadGestionFlota > 0){
            for ($i=1 ; $i <= $cantidadGestionFlota ; $i++) { 
                //Obtiene datos de los campos          
                $plantilla_id = $plantilla_id;
                $precio_rango = trim($this->input->post('precio_gf' . $i));
                $bandera_tipo = trim($this->input->post('opcion_horas_gf' . $i));
          
                switch ($bandera_tipo) {
                    case 1:
                        $opcion_vehiculos = 1; $opcion_horas = 1; break;
                    case 2:
                        $opcion_vehiculos = 1; $opcion_horas = 2; break;
                    case 3:
                        $opcion_vehiculos = 2; $opcion_horas = 1; break;
                    case 4:
                        $opcion_vehiculos = 2; $opcion_horas = 2; break;
                    case 5:
                        $opcion_vehiculos = 3; $opcion_horas = 1; break;
                    case 6:
                        $opcion_vehiculos = 3; $opcion_horas = 2; break;
                    case 7:
                        $opcion_vehiculos = 4; $opcion_horas = 1; break;
                    case 8:
                        $opcion_vehiculos = 4; $opcion_horas = 2; break;
                    case 9:
                        $opcion_vehiculos = 5; $opcion_horas = 1; break;
                    case 10:
                        $opcion_vehiculos = 5; $opcion_horas = 2; break;
                }

                if(!empty($precio_rango) && !empty($opcion_vehiculos) && !empty($opcion_horas)) {
                    $this->Plantilla_md->insertDetalleGestionFlota($opcion_vehiculos, $opcion_horas, $precio_rango, $plantilla_id);
                }
            }
        }

        if($cantidadWareHouse > 0){
            for ($i=1 ; $i <= $cantidadWareHouse ; $i++) { 
                //Obtiene datos de los campos          
                $plantilla_id = $plantilla_id;
                $rangoInicio =  trim($this->input->post('rinicio_wh' . $i));
                $rangoFin = trim($this->input->post('rfin_wh' . $i));
                $precio_rango = trim($this->input->post('precio_wh' . $i));
          
                if(!empty($rangoInicio) && !empty($rangoFin) && !empty($precio_rango)) {
                    $this->Plantilla_md->insertDetalleWareHouse($rangoInicio, $rangoFin, $precio_rango, $plantilla_id);
                }
            }
        }

        if($cantidadCobranza > 0){
            $i = 1;

            $plantilla_id = $plantilla_id;
            $valor=  trim($this->input->post('valor_cobranza' . $i));
            $tipo = trim($this->input->post('tipo_cobranza' . $i));

            if(!empty($valor)  && !empty($tipo)){
                $this->Plantilla_md->insertDetalleCobranza($valor, $tipo, $plantilla_id);
            }
        }

        if($cantidadValorAgregado > 0){
            $i = 1;

            $plantilla_id = $plantilla_id;
            $valor =  trim($this->input->post('valor_declarado' . $i));
            $tipo = trim($this->input->post('tipo_valor_declarado' . $i));

            if(!empty($valor) && !empty($tipo)){
                $this->Plantilla_md->insertDetalleValorDeclarado($valor, $tipo, $plantilla_id);
            }
        }

        if($cantidadPesoAforado > 0){
            $i = 1;

            $plantilla_id = $plantilla_id;
            $valor=  trim($this->input->post('valor_peso_aforado' . $i));

            if(!empty($valor)){
                $this->Plantilla_md->insertDetallePesoAforado($valor, $plantilla_id);
            }
        }

        if($cantidadDistancia > 0){
            for ($i=1 ; $i <= $cantidadDistancia ; $i++) { 
                //Obtiene datos de los campos          
                $plantilla_id = $plantilla_id;
                $rangoInicio =  trim($this->input->post('rinicio_distancia' . $i));
                $rangoFin = trim($this->input->post('rfin_distancia' . $i));
                $precio_rango = trim($this->input->post('precio_distancia' . $i));
          
                if(!empty($rangoInicio) && !empty($rangoFin) && !empty($precio_rango)) {
                    $this->Plantilla_md->insertDetalleDistancia($rangoInicio, $rangoFin, $precio_rango, $plantilla_id);
                }
            }
        }


        redirect('/plantillas/plantillas/index', 'refresh');
    }

    public function edit($id){
        $vista_interna = array(
            'permisos_efectivos' => $this->permisos,
            'plantilla' => $this->codegen_model->row('flash_plantillas_paqueteria', '*', 'id = ' . $id),
            
            'kilos' => $this->codegen_model->get('flash_plantilla_detalle_tarifa_kg', '*', 'plantilla_id = ' . $id),
            'bultos' => $this->codegen_model->get('flash_plantilla_detalle_tarifa_bultos', '*', 'plantilla_id = ' . $id),
            'metrosCubicos' => $this->codegen_model->get('flash_plantilla_detalle_tarifa_metro_cubico', '*', 'plantilla_id = ' . $id),
            'palets' => $this->codegen_model->get('flash_plantilla_detalle_tarifa_palets', '*', 'plantilla_id = ' . $id),
            'warehouse' => $this->codegen_model->get('flash_plantilla_detalle_tarifa_warehouse', '*', 'plantilla_id = ' . $id),
            'gestionFlota' => $this->codegen_model->get('flash_plantilla_detalle_tarifa_gestion_flota', '*', 'plantilla_id = ' . $id),
            'valorDeclarado' => $this->codegen_model->row('flash_plantilla_detalle_tarifa_valor_declarado', '*', 'plantilla_id = ' . $id),
            'cobranza' => $this->codegen_model->row('flash_plantilla_detalle_tarifa_cobranza', '*', 'plantilla_id = ' . $id),
            'pesoAforado' => $this->codegen_model->row('flash_plantilla_detalle_tarifa_peso_aforado', '*', 'plantilla_id = ' . $id),
            'distancia' => $this->codegen_model->get('flash_plantilla_detalle_tarifa_distancia', '*', 'plantilla_id = ' . $id),
        );
                
        $vista_externa = array(
            'title' => ucwords("Editar Plantilla"),
            'contenido_main' => $this->load->view('components/plantilla/edit', $vista_interna, true)
        );

        $this->load->view('template/backend', $vista_externa);
    }

    function update($id){
        //DATOS
        $data['nombre'] = trim($this->input->post('nombre'));
        $data['descripcion'] = trim($this->input->post('descripcion'));
        $userSesion = $this->ion_auth->user()->row();
        $data['user_id'] = $userSesion->id;

        $cantidadKilos = trim($this->input->post('cantidad_fila_kilos'));
        $cantidadBultos = trim($this->input->post('cantidad_fila_bultos'));
        $cantidadMetroCubico = trim($this->input->post('cantidad_fila_metro_cubico'));
        $cantidadPalets = trim($this->input->post('cantidad_fila_palets'));
        $cantidadGestionFlota = trim($this->input->post('cantidad_fila_gestion_flota'));
        $cantidadWareHouse = trim($this->input->post('cantidad_fila_warehouse'));
        $cantidadCobranza = trim($this->input->post('cantidad_fila_cobranza'));
        $cantidadValorAgregado = trim($this->input->post('cantidad_fila_valor_declarado'));
        $cantidadPesoAforado = trim($this->input->post('cantidad_fila_peso_aforado'));
        $cantidadDistancia = trim($this->input->post('cantidad_fila_distancia'));

        //INICIO TRANSACCIÓN
        $this->db->trans_start();

        //ACTUALIZAR PLANTILLA
        $this->codegen_model->edit('flash_plantillas_paqueteria', $data, 'id', $id);

        //ELIMINO TODAS LAS FILAS DE DETALLES
        $this->codegen_model->delete('flash_plantilla_detalle_tarifa_kg','plantilla_id', $id);
        if($cantidadKilos > 0){
            for ($i=1 ; $i <= $cantidadKilos ; $i++) { 
                //Obtiene datos de los campos          
                $plantilla_id = $id;
                $rangoInicio =  trim($this->input->post('rinicio_kilos' . $i));
                $rangoFin = trim($this->input->post('rfin_kilos' . $i));
                $precio_rango = trim($this->input->post('precio_kilos' . $i));
                $bandera_tipo = trim($this->input->post('opciones_tipo_kilos' . $i));
          
                if(!empty($rangoInicio) && !empty($rangoFin) && !empty($precio_rango) && !empty($bandera_tipo)) {
                    $this->Plantilla_md->insertDetalleKilos($rangoInicio, $rangoFin, $precio_rango, $bandera_tipo, $plantilla_id);
                }
            }
        }

        //ELIMINO TODAS LAS FILAS DE DETALLES
        $this->codegen_model->delete('flash_plantilla_detalle_tarifa_bultos','plantilla_id', $id);
        if($cantidadBultos > 0){
            for ($i=1 ; $i <= $cantidadBultos ; $i++) { 
                //Obtiene datos de los campos          
                $plantilla_id = $id;
                $rangoInicio =  trim($this->input->post('rinicio_bultos' . $i));
                $rangoFin = trim($this->input->post('rfin_bultos' . $i));
                $precio_rango = trim($this->input->post('precio_bultos' . $i));
                $bandera_tipo = trim($this->input->post('opciones_tipo_bultos' . $i));
          
                if(!empty($rangoInicio) && !empty($rangoFin) && !empty($precio_rango) && !empty($bandera_tipo)) {
                    $this->Plantilla_md->insertDetalleBultos($rangoInicio, $rangoFin, $precio_rango, $bandera_tipo, $plantilla_id);
                }
            }
        }

        //ELIMINO TODAS LAS FILAS DE DETALLES
        $this->codegen_model->delete('flash_plantilla_detalle_tarifa_metro_cubico','plantilla_id', $id);
        if($cantidadMetroCubico > 0){
            for ($i=1 ; $i <= $cantidadMetroCubico ; $i++) { 
                //Obtiene datos de los campos          
                $plantilla_id = $id;
                $rangoInicio =  trim($this->input->post('rinicio_mc' . $i));
                $rangoFin = trim($this->input->post('rfin_mc' . $i));
                $precio_rango = trim($this->input->post('precio_mc' . $i));
                $bandera_tipo = trim($this->input->post('opciones_tipo_mc' . $i));
          
                if(!empty($rangoInicio) && !empty($rangoFin) && !empty($precio_rango) && !empty($bandera_tipo)) {
                    $this->Plantilla_md->insertDetalleMetroCubico($rangoInicio, $rangoFin, $precio_rango, $bandera_tipo, $plantilla_id);
                }
            }
        }

        //ELIMINO TODAS LAS FILAS DE DETALLES
        $this->codegen_model->delete('flash_plantilla_detalle_tarifa_palets','plantilla_id', $id);
        if($cantidadPalets > 0){
            for ($i=1 ; $i <= $cantidadPalets ; $i++) { 
                //Obtiene datos de los campos          
                $plantilla_id = $id;
                $rangoInicio =  trim($this->input->post('rinicio_palets' . $i));
                $rangoFin = trim($this->input->post('rfin_palets' . $i));
                $precio_rango = trim($this->input->post('precio_palets' . $i));
                $bandera_tipo = trim($this->input->post('opciones_tipo_palets' . $i));
          
                if(!empty($rangoInicio) && !empty($rangoFin) && !empty($precio_rango) && !empty($bandera_tipo)) {
                    $this->Plantilla_md->insertDetallePalets($rangoInicio, $rangoFin, $precio_rango, $bandera_tipo, $plantilla_id);
                }
            }
        }

        //ELIMINO TODAS LAS FILAS DE DETALLES
        $this->codegen_model->delete('flash_plantilla_detalle_tarifa_warehouse','plantilla_id', $id);
        if($cantidadWareHouse > 0){
            for ($i=1 ; $i <= $cantidadWareHouse ; $i++) { 
                //Obtiene datos de los campos          
                $plantilla_id = $id;
                $rangoInicio =  trim($this->input->post('rinicio_wh' . $i));
                $rangoFin = trim($this->input->post('rfin_wh' . $i));
                $precio_rango = trim($this->input->post('precio_wh' . $i));
          
                if(!empty($rangoInicio) && !empty($rangoFin) && !empty($precio_rango)) {
                    $this->Plantilla_md->insertDetalleWareHouse($rangoInicio, $rangoFin, $precio_rango, $plantilla_id);
                }
            }
        }

        //ELIMINO TODAS LAS FILAS DE DETALLES
        $this->codegen_model->delete('flash_plantilla_detalle_tarifa_gestion_flota','plantilla_id', $id);
        if($cantidadGestionFlota > 0){
            for ($i=1 ; $i <= $cantidadGestionFlota ; $i++) { 
                //Obtiene datos de los campos          
                $plantilla_id = $id;
                $precio_rango = trim($this->input->post('precio_gf' . $i));
                $bandera_tipo = trim($this->input->post('opcion_horas_gf' . $i));
          
                switch ($bandera_tipo) {
                    case 1:
                        $opcion_vehiculos = 1; $opcion_horas = 1; break;
                    case 2:
                        $opcion_vehiculos = 1; $opcion_horas = 2; break;
                    case 3:
                        $opcion_vehiculos = 2; $opcion_horas = 1; break;
                    case 4:
                        $opcion_vehiculos = 2; $opcion_horas = 2; break;
                    case 5:
                        $opcion_vehiculos = 3; $opcion_horas = 1; break;
                    case 6:
                        $opcion_vehiculos = 3; $opcion_horas = 2; break;
                    case 7:
                        $opcion_vehiculos = 4; $opcion_horas = 1; break;
                    case 8:
                        $opcion_vehiculos = 4; $opcion_horas = 2; break;
                    case 9:
                        $opcion_vehiculos = 5; $opcion_horas = 1; break;
                    case 10:
                        $opcion_vehiculos = 5; $opcion_horas = 2; break;
                }

                if(!empty($precio_rango) && !empty($opcion_vehiculos) && !empty($opcion_horas)) {
                    $this->Plantilla_md->insertDetalleGestionFlota($opcion_vehiculos, $opcion_horas, $precio_rango, $plantilla_id);
                }
            }
        }

        //ELIMINO TODAS LAS FILAS DE DETALLES
        $this->codegen_model->delete('flash_plantilla_detalle_tarifa_cobranza','plantilla_id', $id);
        if($cantidadCobranza > 0){
            $i = 1;

            $plantilla_id = $id;
            $valor=  trim($this->input->post('valor_cobranza' . $i));
            $tipo = trim($this->input->post('tipo_cobranza' . $i));

            if(!empty($valor)  && !empty($tipo)){
                $this->Plantilla_md->insertDetalleCobranza($valor, $tipo, $plantilla_id);
            }
        }

        //ELIMINO TODAS LAS FILAS DE DETALLES
        $this->codegen_model->delete('flash_plantilla_detalle_tarifa_valor_declarado','plantilla_id', $id);
        if($cantidadValorAgregado > 0){
            $i = 1;

            $plantilla_id = $id;
            $valor =  trim($this->input->post('valor_declarado' . $i));
            $tipo = trim($this->input->post('tipo_valor_declarado' . $i));

            if(!empty($valor) && !empty($tipo)){
                $this->Plantilla_md->insertDetalleValorDeclarado($valor, $tipo, $plantilla_id);
            }
        }

        //ELIMINO TODAS LAS FILAS DE DETALLES
        $this->codegen_model->delete('flash_plantilla_detalle_tarifa_peso_aforado','plantilla_id', $id);
        if($cantidadPesoAforado > 0){

            $i = 1;

            $plantilla_id = $id;
            $valor=  trim($this->input->post('valor_peso_aforado' . $i));

            if(!empty($valor)){
                $this->Plantilla_md->insertDetallePesoAforado($valor, $plantilla_id);
            }
        }

        //ELIMINO TODAS LAS FILAS DE DETALLES
        $this->codegen_model->delete('flash_plantilla_detalle_tarifa_distancia','plantilla_id', $id);
        if($cantidadDistancia > 0){
            for ($i=1 ; $i <= $cantidadDistancia ; $i++) { 
                //Obtiene datos de los campos          
                $plantilla_id = $id;
                $rangoInicio =  trim($this->input->post('rinicio_distancia' . $i));
                $rangoFin = trim($this->input->post('rfin_distancia' . $i));
                $precio_rango = trim($this->input->post('precio_distancia' . $i));
          
                if(!empty($rangoInicio) && !empty($rangoFin) && !empty($precio_rango)) {
                    $this->Plantilla_md->insertDetalleDistancia($rangoInicio, $rangoFin, $precio_rango, $plantilla_id);
                }
            }
        }

        //FINALIZO TRANSACCION
        $this->db->trans_complete();

        redirect('/plantillas/plantillas/index', 'refresh');
        
    }

    function view($id){
        $vista_interna = array(
			'permisos_efectivos' => $this->permisos,
            'plantilla' => $this->codegen_model->row('flash_plantillas_paqueteria', '*', 'id = ' . $id),
            
            'kilos' => $this->codegen_model->get('flash_plantilla_detalle_tarifa_kg', '*', 'plantilla_id = ' . $id),
            'bultos' => $this->codegen_model->get('flash_plantilla_detalle_tarifa_bultos', '*', 'plantilla_id = ' . $id),
            'metrosCubicos' => $this->codegen_model->get('flash_plantilla_detalle_tarifa_metro_cubico', '*', 'plantilla_id = ' . $id),
            'palets' => $this->codegen_model->get('flash_plantilla_detalle_tarifa_palets', '*', 'plantilla_id = ' . $id),
            'warehouse' => $this->codegen_model->get('flash_plantilla_detalle_tarifa_warehouse', '*', 'plantilla_id = ' . $id),
            'gestionFlota' => $this->codegen_model->get('flash_plantilla_detalle_tarifa_gestion_flota', '*', 'plantilla_id = ' . $id),
            'valorDeclarado' => $this->codegen_model->row('flash_plantilla_detalle_tarifa_valor_declarado', '*', 'plantilla_id = ' . $id),
            'cobranza' => $this->codegen_model->row('flash_plantilla_detalle_tarifa_cobranza', '*', 'plantilla_id = ' . $id),
            'pesoAforado' => $this->codegen_model->row('flash_plantilla_detalle_tarifa_peso_aforado', '*', 'plantilla_id = ' . $id),
            'distancia' => $this->codegen_model->get('flash_plantilla_detalle_tarifa_distancia', '*', 'plantilla_id = ' . $id),
		);

		$vista_externa = array(			
			'title' => ucwords("Detalle Plantilla"),
			'contenido_main' => $this->load->view('components/plantilla/view', $vista_interna, true)
		);		  
   
		$this->load->view('template/view', $vista_externa);
    }

    function delete($id){
        $this->codegen_model->delete('flash_plantillas_paqueteria','id',$id);
    }

}