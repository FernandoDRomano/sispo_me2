<?php

class Tarifario extends MY_Controller{
    
    public function __construct(){
        parent::__construct();
        $this->permisos = $this->permisos_lib->control();
        $this->load->model('Tarifario_md_n');
        $this->load->helper('url');
    }

    function create(){
        $vista_interna = array(
            'permisos_efectivos' => $this->permisos,
            'clientes' => $this->codegen_model->get('flash_clientes', '*'),
            'zonas' => $this->codegen_model->get('flash_tarifario_zonas', '*'),
            'plantillas' => $this->codegen_model->get('flash_plantillas_paqueteria', '*')
        );
                
        $vista_externa = array(
            'title' => ucwords("Tarifario"),
            'contenido_main' => $this->load->view('components/tarifarioN/create', $vista_interna, true)
        );

        $this->load->view('template/backend', $vista_externa);
    }

    function store(){
        //DATOS
        $zonaA = trim($this->input->post('zona_origen'));
        $zonaB = trim($this->input->post('zona_destino'));
        $cliente_id = trim($this->input->post('cliente'));
        $userSesion = $this->ion_auth->user()->row();
        $user_id = $userSesion->id;

        $cantidadKilos = trim($this->input->post('cantidad_fila_kilos'));
        $cantidadBultos = trim($this->input->post('cantidad_fila_bultos'));
        $cantidadMetroCubico = trim($this->input->post('cantidad_fila_metro_cubico'));
        $cantidadPalets = trim($this->input->post('cantidad_fila_palets'));
        $cantidadGestionFlota = trim($this->input->post('cantidad_fila_gestion_flota'));
        $cantidadWareHouse = trim($this->input->post('cantidad_fila_warehouse'));
        $cantidadCobranza = trim($this->input->post('cantidad_fila_cobranza'));
        $cantidadValorAgregado = trim($this->input->post('cantidad_fila_valor_declarado'));
        $cantidadPesoAforado = trim($this->input->post('cantidad_fila_peso_aforado'));

        //INSERTO EL SERVICIO DE PAQUETERIA
        $servicio_id = $this->Tarifario_md_n->insertServicioPaqueteria($cliente_id, $user_id);

        if($cantidadKilos > 0){
            for ($i=1 ; $i <= $cantidadKilos ; $i++) { 

                $zonaA = trim($this->input->post('zona_origen'));;
                $zonaB = trim($this->input->post('zona_destino'));
                $desde =  trim($this->input->post('rinicio_kilos' . $i));
                $hasta = trim($this->input->post('rfin_kilos' . $i));
                $precio = trim($this->input->post('precio_kilos' . $i));
                $bandera = trim($this->input->post('opciones_tipo_kilos' . $i));
          
                if(!empty($desde) 
                    && !empty($hasta) 
                    && !empty($precio) 
                    && !empty($bandera)) {

                    $kg_id = $this->Tarifario_md_n->insertDetalleKilos($zonaA, $zonaB, $desde, $hasta, $precio, $bandera, $servicio_id);
                    
                }
            }

        }

        if($cantidadBultos > 0){
            for ($i=1 ; $i <= $cantidadBultos ; $i++) { 
                //Obtiene datos de los campos          
                $zonaA = trim($this->input->post('zona_origen'));;
                $zonaB = trim($this->input->post('zona_destino'));
                $desde =  trim($this->input->post('rinicio_bultos' . $i));
                $hasta = trim($this->input->post('rfin_bultos' . $i));
                $precio = trim($this->input->post('precio_bultos' . $i));
                $bandera = trim($this->input->post('opciones_tipo_bultos' . $i));
          
                if(!empty($desde) 
                    && !empty($hasta) 
                    && !empty($precio) 
                    && !empty($bandera)) {
                    
                    $bulto_id = $this->Tarifario_md_n->insertDetallesBultos($zonaA, $zonaB, $desde, $hasta, $precio, $bandera, $servicio_id);
                }
            }
        }

        if($cantidadMetroCubico > 0){
            for ($i=1 ; $i <= $cantidadMetroCubico ; $i++) { 
                //Obtiene datos de los campos          
                $zonaA = trim($this->input->post('zona_origen'));;
                $zonaB = trim($this->input->post('zona_destino'));
                $desde =  trim($this->input->post('rinicio_mc' . $i));
                $hasta = trim($this->input->post('rfin_mc' . $i));
                $precio = trim($this->input->post('precio_mc' . $i));
                $bandera = trim($this->input->post('opciones_tipo_mc' . $i));
          
                if(!empty($desde) 
                    && !empty($hasta) 
                    && !empty($precio) 
                    && !empty($bandera)) {

                    $metro_id = $this->Tarifario_md_n->insertDetallesMetroCubico($zonaA, $zonaB, $desde, $hasta, $precio, $bandera, $servicio_id);
                }
            }
        }

        if($cantidadPalets > 0){
            for ($i=1 ; $i <= $cantidadPalets ; $i++) { 
                //Obtiene datos de los campos          
                $zonaA = trim($this->input->post('zona_origen'));;
                $zonaB = trim($this->input->post('zona_destino'));
                $desde =  trim($this->input->post('rinicio_palets' . $i));
                $hasta = trim($this->input->post('rfin_palets' . $i));
                $precio = trim($this->input->post('precio_palets' . $i));
                $bandera = trim($this->input->post('opciones_tipo_palets' . $i));
          
                if(!empty($desde) 
                    && !empty($hasta) 
                    && !empty($precio) 
                    && !empty($bandera)) {

                    $palet_id = $this->Tarifario_md_n->insertDetallesPalets($zonaA, $zonaB, $desde, $hasta, $precio, $bandera, $servicio_id);
                }
            }
        }

        if($cantidadGestionFlota > 0){
            for ($i=1 ; $i <= $cantidadGestionFlota ; $i++) { 
                //Obtiene datos de los campos          
                $zonaA = trim($this->input->post('zona_origen'));;
                $zonaB = trim($this->input->post('zona_destino'));
                $precio = trim($this->input->post('precio_gf' . $i));
                $bandera_tipo = trim($this->input->post('opcion_horas_gf' . $i));
          
                switch ($bandera_tipo) {
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

                if(!empty($precio) 
                    && !empty($vehiculo) 
                    && !empty($hora)) {
                        
                    $flota_id = $this->Tarifario_md_n->insertDetallesGestionFlota($zonaA, $zonaB, $vehiculo, $hora, $precio, $servicio_id);
                }
            }
        }

        if($cantidadWareHouse > 0){
            for ($i=1 ; $i <= $cantidadWareHouse ; $i++) { 
                //Obtiene datos de los campos          
                $zonaA = trim($this->input->post('zona_origen'));
                $zonaB = trim($this->input->post('zona_destino'));
                $inicio =  trim($this->input->post('rinicio_wh' . $i));
                $fin = trim($this->input->post('rfin_wh' . $i));
                $precio = trim($this->input->post('precio_wh' . $i));
          
                if(!empty($inicio) 
                    && !empty($fin) 
                    && !empty($precio)) {

                    $warehouse_id = $this->Tarifario_md_n->insertDetallesWarehouse($zonaA, $zonaB, $inicio, $fin, $precio, $servicio_id);
                }
            }
        }

        if($cantidadCobranza > 0){
            $i = 0;

            $zonaA = trim($this->input->post('zona_origen'));
            $zonaB = trim($this->input->post('zona_destino'));
            $porcentaje = trim($this->input->post('porcentaje_cobranza' . $i));

            if(!empty($porcentaje)){
                $cobranza_id = $this->Tarifario_md_n->insertDetallesCobranza($zonaA, $zonaB, $porcentaje, $servicio_id);
            }
        }

        if($cantidadValorAgregado > 0){
            $i = 0;

            $zonaA = trim($this->input->post('zona_origen'));
            $zonaB = trim($this->input->post('zona_destino'));
            $porcentaje =  trim($this->input->post('porcentaje_valor_declarado' . $i));

            if(!empty($porcentaje)){
                $valor_declarado_id = $this->Tarifario_md_n->insertDetallesValorDeclarado($zonaA, $zonaB, $porcentaje, $servicio_id);
            }
        }

        if($cantidadPesoAforado > 0){
            $i = 0;

            $zonaA = trim($this->input->post('zona_origen'));
            $zonaB = trim($this->input->post('zona_destino'));
            $valor =  trim($this->input->post('valor_peso_aforado' . $i));

            if(!empty($valor)){
                $peso_aforado_id = $this->Tarifario_md_n->insertDetallesPesoAforado($zonaA, $zonaB, $valor, $servicio_id);
            }
        }

        //GRABAR FLASH SERVICIO PAQUETERIA
        //$cliente_id = trim($this->input->post('cliente'));
        //$userSesion = $this->ion_auth->user()->row();
        //$user_id = $userSesion->id;
        
        /*
        $this->Tarifario_md_n->insertServicioPaqueteria(
            $cliente_id, $kg_id, $bulto_id, $cobranza_id,
            $valor_declarado_id, $metro_id, $palet_id,
            $warehouse_id, $flota_id, $peso_aforado_id,
            $user_id
        );
        */

        echo "Se grabaron todos los datos";

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
        ];
        
        if($json){
            echo json_encode($json);
        }else{
            echo json_encode("");
        } 
    }

}