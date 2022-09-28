<?php
//Controlador de la GUI de Comerciales, donde elije que campos podran llenarse en Paqueteria
 use Illuminate\Database\Capsule\Manager as DB;

class Tarifario_seleccion_opciones extends MY_Controller {

   // private $permisos;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('tarifario_md');
       // $this->permisos = $this->permisos_lib->control();
    }

    public function index()
    {
        $mensaje = $_SESSION['mensajes'];
        //Declaracion de variables
        $resultado_comparacion = 0;
        $contador_comparaciones = 0;
        $resultado_insert = '';  
        $zonaA = '';
        $zonaB = '';       

        $lista_clientes_servicio = $this->tarifario_md->getClientesServicios();
        $lista_zonas = $this->tarifario_md->getTarifasZonas();

        $lista_zonas2 = $this->tarifario_md->getTarifasZonas2();


        $vista_interna = array(
            'permisos_efectivos'    => $this->permisos,
            'clientes'              => $lista_clientes_servicio,
            'zonas' => $lista_zonas,
            'zonas_' => $lista_zonas2,
            'mensaje' => $mensaje,
        );

// Armo Array de la vista externa, agrego titulo y cargo la vista enviandole el Array de vista interna //
        $vista_externa = array(
            'title'          => ucwords("Comerciales"),
            'contenido_main' => $this->load->view('components/tarifario/seleccion_opciones', $vista_interna, true)
        );

        $_SESSION['mensajes'] = '';
// Cargo vista //
        $this->load->view('template/backend', $vista_externa);
    }



    public function ajax_validar_campos_grabados(){
        $parametros = $this->input->post("parametros");

        $cliente_id = $parametros["cliente"];
        $opcion_elegida_tipo = $parametros["tipo_opcion"];
        $zonaA = $parametros["zonaA"];
        $zonaB = $parametros["zonaB"];
        $datos = $parametros["matriz"];

        $id_tarifa = $parametros["id_tarifa"];

             //   print_r($datos);
        
        $resultado_comparacion_campos = comparacion($cliente_id, $opcion_elegida_tipo, $zonaA, $zonaB, $datos, $id_tarifa);

        $result = [];
        $result['msj']  = 'llega ok';
        $result['datos']  = $datos[0];
        $result['cliente']  = $cliente_id;
        $result['zonaA']  = $zonaA;
        $result['zonaB']  = $zonaB;
        $result['tipo']  = $opcion_elegida_tipo;
        $result['comparacion'] = $resultado_comparacion_campos;

        echo json_encode($result);
        die;
    }

    public function registrar(){
        //Obtiene datos de los campos labels
        $id_cliente = trim($this->input->post('cliente'));
        $tipo_opcion_calculo = intval(trim($this->input->post('opciones_tarifario')));
        $userSesion = $this->ion_auth->user()->row();
        $user = $userSesion->id;

        $cantidad_campos = trim($this->input->post('cantidad_campos'));

        $zonaA = trim($this->input->post('zonaA'));
        $zonaB = trim($this->input->post('zonaB'));
        
       //Escoge segun la opcion seleccionada al cargar datos (kilos, bultos,...)
        switch ($tipo_opcion_calculo) {
            case 1:
            // Kilos
                for ($i=1; $i <= $cantidad_campos; $i++) {    
                    //Obtiene datos de los campos labels            
                    $rangoInicio =  trim($this->input->post('rinicio_kilos' . $i));
                    $rangoFin = trim($this->input->post('rfin_kilos' . $i));
                    $precio_rango = trim($this->input->post('precio_kilos' . $i));
                    $bandera_tipo = trim($this->input->post('opciones_tipo_kilos' . $i));

                    if(!empty($rangoInicio) && !empty($rangoFin) && !empty($precio_rango) && !empty($bandera_tipo)) {
                        $mensaje = insert($tipo_opcion_calculo, $zonaA, $zonaB, $rangoInicio, $rangoFin, $precio_rango, $bandera_tipo, $id_cliente, $user);

                       // print_r($mensaje);
                    }
                    else{
                        $mensaje = 'no entro en if';
                    }
                }               
                break;
            case 2:
            //Bultos
                for ($i=1; $i <= $cantidad_campos; $i++) {    
                    //Obtiene datos de los campos labels            
                    $rangoInicio =  trim($this->input->post('rinicio_bultos' . $i));
                    $rangoFin = trim($this->input->post('rfin_bultos' . $i));
                    $precio_rango = trim($this->input->post('precio_bultos' . $i));
                    $bandera_tipo = trim($this->input->post('opciones_tipo_bultos' . $i));

                    if(!empty($rangoInicio) && !empty($rangoFin) && !empty($precio_rango) && !empty($bandera_tipo)) {
                        $mensaje = insert($tipo_opcion_calculo, $zonaA, $zonaB, $rangoInicio, $rangoFin, $precio_rango, $bandera_tipo, $id_cliente, $user);

                       // print_r($mensaje);
                    }
                    else{
                        $mensaje = 'no entro en if';
                    }
                }            
                break;
            case 3:
            //Cobranza
                for ($i=1; $i <= $cantidad_campos; $i++) {    
                    //Obtiene datos de los campos labels            
                    $porcentaje =  trim($this->input->post('porcentaje_cobranza' . $i));

                    if(!empty($porcentaje)) {
                        $mensaje = insert($tipo_opcion_calculo, $zonaA, $zonaB, $porcentaje, NULL, NULL, NULL, $id_cliente, $user);

                       // print_r($mensaje);
                    }
                    else{
                        $mensaje = 'no entro en if';
                    }
                }            
                break;                
            case 4:
            //Valor declarado
                for ($i=1; $i <= $cantidad_campos; $i++) {    
                    //Obtiene datos de los campos labels            
                    $porcentaje =  trim($this->input->post('porcentaje_valor_declarado' . $i));

                    if(!empty($porcentaje)) {
                        $mensaje = insert($tipo_opcion_calculo, $zonaA, $zonaB, $porcentaje, NULL, NULL, NULL, $id_cliente, $user);

                       // print_r($mensaje);
                    }
                    else{
                        $mensaje = 'no entro en if';
                    }
                }               
                break;   
            case 5:
            //Opcion metro cubico

                for ($i=1; $i <= $cantidad_campos; $i++) { 
                    $rangoInicio =  trim($this->input->post('rinicio_mc' . $i));
                    $rangoFin = trim($this->input->post('rfin_mc' . $i));
                    $precio_rango = trim($this->input->post('precio_mc' . $i));
                    $bandera_tipo = trim($this->input->post('opciones_tipo_mc' . $i));

                    if(!empty($rangoInicio) && !empty($rangoFin) && !empty($precio_rango) && !empty($bandera_tipo)) {
                        $mensaje = insert($tipo_opcion_calculo, $zonaA, $zonaB, $rangoInicio, $rangoFin, $precio_rango, $bandera_tipo, $id_cliente, $user);

                       // print_r($mensaje);
                    }
                    else{
                        $mensaje = 'no entro en if';
                    }
                }

                break;                             
            case 6:
            //Opcion palets

                for ($i=1; $i <= $cantidad_campos; $i++) { 
                    //Obtiene datos de los campos labels
                    $rangoInicio =  trim($this->input->post('rinicio_palets' . $i));
                    $rangoFin = trim($this->input->post('rfin_palets' . $i));
                    $precio_cantidad = trim($this->input->post('precio_palets'.$i));
                    $bandera_tipo = trim($this->input->post('opciones_tipo_palets' . $i));

                    if(!empty($rangoInicio) && !empty($rangoFin) && !empty($precio_cantidad) && !empty($bandera_tipo)) {
                        $mensaje = insert($tipo_opcion_calculo, $zonaA, $zonaB, $rangoInicio, $rangoFin, $precio_cantidad, $bandera_tipo, $id_cliente, $user);

                    }
                    else{
                        $mensaje = 'no entro en if';
                    }
                }

                break;
            case 7:
            //Opcion gestion flota

                for ($i=1; $i <= $cantidad_campos; $i++) { 
                    $opcion = intval(trim($this->input->post('opcion_horas_gf'.$i)));
                    $precio = trim($this->input->post('precio_gf'.$i));

                    switch ($opcion) {
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

                    if(!empty($opcion_vehiculos) && !empty($opcion_horas) && !empty($precio)){
                        $mensaje = insert($tipo_opcion_calculo, $zonaA, $zonaB, $opcion_vehiculos, $opcion_horas, $precio, NULL, $id_cliente, $user);

                    }
                    else{
                        $mensaje = 'no entro en if';
                    }
                }

                break;                
            case 8:
            //Opcion warehouse

                for ($i=1; $i <= $cantidad_campos; $i++) { 
                    //Obtiene datos de los campos labels
                    $cantidad = trim($this->input->post('cantidad_wh'.$i));
                    $precio = trim($this->input->post('precio_wh'.$i));

                    if(!empty($cantidad) && !empty($precio)){
                        $mensaje = insert($tipo_opcion_calculo, $zonaA, $zonaB, $cantidad, NULL, $precio, NULL, $id_cliente, $user);

                    }
                    else{
                        $mensaje = 'no entro en if';
                    }
                }

                break;                
            default:

        }

       // var_dump($this->session);
        $this->session->set_flashdata('mensajes', $mensaje);
    //    $_SESSION['mensajes'] = $mensaje;
     //   $_SESSION->markAsFlashdata('mensajes');
     //   print_r($mensaje);
     //   die;
   
        redirect(base_url() . 'tarifarioT/tarifario_seleccion_opciones');
    }   


    public function listar()
    {
        $id = $_GET["id"];
        $bandera_edicion_exitosa = $_GET["bandera_edicion"];
        $bandera_opcion_tarifario = $_GET["bandera_opcion_tarifario"];

        $lista_zonas = $this->tarifario_md->getTarifasZonas2();


        $query_opciones_cliente = $this->db
        ->select('count(tarifario_kg_id) tarifas_kg
            , count(tarifario_bultos_id) tarifas_bultos
            , count(tarifario_cobranza_id) tarifas_cobranza
            , count(tarifario_valor_declarado_id) tarifas_valor_declarado
            , count(tarifario_metro_cubico_id) tarifas_metro_cubico
            , count(tarifario_palets_id) tarifas_palets
            , count(tarifario_warehouse_id) tarifario_warehouse
            , count(tarifario_gestion_flota_id) tarifario_gflota')
        ->where('cliente_id = ' . $id)
        ->get('flash_servicios_paqueteria');
        $opciones = $query_opciones_cliente ->result();


        // Armo Array de la vista interna, asignandole Permisos y listas de tarifas
        $vista_interna = array(
            'permisos_efectivos'    => $this->permisos,
            'id_cliente'            => $id,
            'opciones_configuradas' => $opciones,
            /*'lista_tarifas_bultos'  => $lista_tarifas_bultos,
            'lista_tarifas_palets'  => $lista_tarifas_palets,
            'lista_tarifas_kg'      => $lista_tarifas_kg,*/
            'bandera_edicion' => $bandera_edicion_exitosa,
            'bandera_opcion_tarifario' => $bandera_opcion_tarifario,
            'zonas' => $lista_zonas
        );

// Armo Array de la vista externa, agrego titulo y cargo la vista enviandole el Array de vista interna //
        $vista_externa = array(
            'title'          => ucwords("Comerciales - Edit registros"),
            'contenido_main' => $this->load->view('components/tarifario/seleccion_opciones_list', $vista_interna, true)
        );
// Cargo vista //
        $this->load->view('template/backend', $vista_externa);        
    }


    public function ajax_registros_unicos(){
        $parametros = $this->input->post("parametros");

        $cliente_id = $parametros["cliente"];
        $opcion_elegida = $parametros["tipo_opcion"];  
        $zonaA = $parametros["zonaA"];
        $zonaB = $parametros["zonaB"]; 

        $detalle = null; 

        switch ($opcion_elegida) {
            case '3':
                $tarifas_opcion = $this->tarifario_md->getTarifasCobranzaPorCliente($cliente_id, $zonaA, $zonaB); 
                break;
            case '4':
                $tarifas_opcion = $this->tarifario_md->getTarifasValorDeclaroPorCliente($cliente_id, $zonaA, $zonaB); 
                break;                
            case '5':
                $tarifas_opcion = $this->tarifario_md->getTarifasMetroCubicoPorCliente($cliente_id, $zonaA, $zonaB); 
                break;
            case '7':
                $tarifas_opcion = $this->tarifario_md->getTarifasGestionFlotaPorCliente($cliente_id, $zonaA, $zonaB); 

                $detalle = $tarifas_opcion;
                break;
        }


        $result = [];
        $result['msj']  = 'llega ok';
        $result['tarifas'] = count($tarifas_opcion);
        $result['detalle'] = $detalle;

        echo json_encode($result);
        die;              
    }


    public function ajax_listar_tarifas_opcion(){
        $parametros = $this->input->post("parametros");

        $cliente_id = $parametros["cliente"];
        $opcion_elegida = $parametros["opcion"];

        switch ($opcion_elegida) {
            case '1':
                $tarifas_opcion = $this->tarifario_md->getTarifasKgPorCliente($cliente_id, NULL, NULL);
                break;
            case '2':
                $tarifas_opcion = $this->tarifario_md->getTarifasBultosPorCliente($cliente_id, NULL, NULL); 
                break;
            case '3':
                $tarifas_opcion = $this->tarifario_md->getTarifasCobranzaPorCliente($cliente_id, NULL, NULL); 
                break;
            case '4':
                $tarifas_opcion = $this->tarifario_md->getTarifasValorDeclaroPorCliente($cliente_id, NULL, NULL); 
                break;
            case '5':
                $tarifas_opcion = $this->tarifario_md->getTarifasMetroCubicoPorCliente($cliente_id, NULL, NULL); 
                break;                
            case '6':
                $tarifas_opcion = $this->tarifario_md->getTarifasPaletsPorCliente($cliente_id, NULL, NULL); 
                break;
            case '7':
                $tarifas_opcion = $this->tarifario_md->getTarifasGestionFlotaPorCliente($cliente_id, NULL, NULL); 
                break;
            case '8':
                $tarifas_opcion = $this->tarifario_md->getTarifasWarehousePorCliente($cliente_id, NULL, NULL); 
                break;                
        }

        $result = [];
        $result['msj']  = 'llega ok';
        $result['tarifas'] = $tarifas_opcion;

        echo json_encode($result);
        die;
    }


    public function consulta(){
        $id = $_GET["id"];
        $opcion = $_GET["opcion"];
       // print_r('llega');//  echo ('<br>'); print_r($id); echo ('<br>'); print_r($opcion);//  die;

        $lista_zonas = $this->tarifario_md->getTarifasZonas2();


        switch ($opcion) {
            case '1':
                $tarifa = $this->tarifario_md->getTarifaKilos($id);

                if(!is_null($tarifa[0])){
                    $tarifa_datos = $tarifa;
                }

                break;
            
            case '2':
                $tarifa = $this->tarifario_md->getTarifaBultos($id);

                if(!is_null($tarifa[0])){
                    $tarifa_datos = $tarifa;
                }

                break;
            case '3':
                $tarifa = $this->tarifario_md->getTarifaCobranza($id);

                if(!is_null($tarifa[0])){
                    $tarifa_datos = $tarifa;
                }

                break; 
            case '4':
                $tarifa = $this->tarifario_md->getTarifaValorDeclarado($id);

                if(!is_null($tarifa[0])){
                    $tarifa_datos = $tarifa;
                }

                break; 
            case '5':
                $tarifa = $this->tarifario_md->getTarifaMetroCubico($id);

                if(!is_null($tarifa[0])){
                    $tarifa_datos = $tarifa;
                }

                break; 
            case '6':
                $tarifa = $this->tarifario_md->getTarifaPalets($id);

                if(!is_null($tarifa[0])){
                    $tarifa_datos = $tarifa;
                }

                break;  
            case '7':
                $tarifa = $this->tarifario_md->getTarifaGestionFlota($id);

                if(!is_null($tarifa[0])){
                    $tarifa_datos = $tarifa;
                }

                break;     
            case '8':
                $tarifa = $this->tarifario_md->getTarifaWareHouse($id);

                if(!is_null($tarifa[0])){
                    $tarifa_datos = $tarifa;
                }

                break;                                                                                                
        }


        $vista_interna = array(
            'permisos_efectivos' => $this->permisos,
            'tarifa'     => $tarifa_datos,
            'lista_zonas' => $lista_zonas,
            'opcion_tarifario' => $opcion,
        );

        $vista_externa = array(         
            'title' => ucwords("Edicion"),
            'contenido_main' => $this->load->view('components/tarifario/seleccion_opciones_edit', $vista_interna, true)
        );        
   
        $this->load->view('template/backend', $vista_externa);
    }

    public function edicion(){
        $bandera_edicion_exitosa = false;
        $bandera_opcion = 0;

        $cliente_id = intval(trim($this->input->post('cliente')));
        $opcion = intval(trim($this->input->post('opcion_tarifario')));
        $zonaA = intval(trim($this->input->post('zonaA')));
        $zonaB = intval(trim($this->input->post('zonaB')));    
        $id_tarifa = intval(trim($this->input->post('identificador_tarifa')));    

        switch ($opcion) {
            case 1:
                $desde = doubleval(trim($this->input->post('desde')));
                $hasta = doubleval(trim($this->input->post('hasta')));
                $precio = doubleval(trim($this->input->post('precio')));
                $tipo = intval(trim($this->input->post('tipo')));

                $datos = [[$desde, $hasta, $precio, $tipo]];

/*
                print_r($datos);
                echo('<br>');
                print_r($cliente_id);
                echo('<br>');
                print_r($opcion);
                echo('<br>');
                print_r($id_tarifa);
                echo('<br>');
                print_r($zonaA);
                echo('<br>');
                print_r($zonaB);
                echo('<br>');


                print_r(gettype($cliente_id));
                echo('<br>');
                print_r(gettype($opcion));
                echo('<br>');
                print_r(gettype($id_tarifa));
                echo('<br>');
                print_r(gettype($zonaA));
                echo('<br>');
                print_r(gettype($zonaB));
                echo('<br>');                
*/

                $resultado_comparacion_campos = comparacion($cliente_id, $opcion, $zonaA, $zonaB, $datos, $id_tarifa);
              //print_r(count($resultado_comparacion_campos)); var_dump($resultado_comparacion_campos);

                if(count($resultado_comparacion_campos) == 0){
                    $data = array(
                        'zonaA' => $zonaA,
                        'zonaB' => $zonaB,
                        'desde_cant_unid_kg' => $desde,
                        'hasta_cant_unid_kg' => $hasta,
                        'precio_Corte_kg' => $precio,
                        'bandera_Corte_kg' => $tipo,
                    );

                    $resultado_edicion = $this->codegen_model->edit('flash_servicios_tarifas_kg',$data,'id',$id_tarifa);  



                    if($resultado_edicion){
                        $bandera_edicion_exitosa = true;
                    }    

                             
                } 
                $bandera_opcion = 1;   

                break;
            case 2:
                $desde = doubleval(trim($this->input->post('desde')));
                $hasta = doubleval(trim($this->input->post('hasta')));
                $precio = doubleval(trim($this->input->post('precio')));
                $tipo = intval(trim($this->input->post('tipo')));

                $datos = [[$desde, $hasta, $precio, $tipo]];

                $resultado_comparacion_campos = comparacion($cliente_id, $opcion, $zonaA, $zonaB, $datos, $id_tarifa);
              //print_r(count($resultado_comparacion_campos)); var_dump($resultado_comparacion_campos);

                if(count($resultado_comparacion_campos) == 0){
                    $data = array(
                        'zonaA' => $zonaA,
                        'zonaB' => $zonaB,
                        'desde_cant_unid_bultos' => $desde,
                        'hasta_cant_unid_bultos' => $hasta,
                        'precio_Corte_bultos' => $precio,
                        'bandera_Corte_Bultos' => $tipo,
                    );

                    $resultado_edicion = $this->codegen_model->edit('flash_servicios_tarifas_bultos',$data,'id',$id_tarifa);  



                    if($resultado_edicion){
                        $bandera_edicion_exitosa = true;
                    }    

                             
                } 
                $bandera_opcion = 2;   

                break; 
            case 3:
                $porcentaje = doubleval(trim($this->input->post('porcentaje')));

                $datos = [[$porcentaje]];

                $resultado_comparacion_campos = comparacion($cliente_id, $opcion, $zonaA, $zonaB, $datos, $id_tarifa);
              //print_r(count($resultado_comparacion_campos)); var_dump($resultado_comparacion_campos);

                if(count($resultado_comparacion_campos) == 0){
                    $data = array(
                        'porcentaje' => $porcentaje,
                    );

                    $resultado_edicion = $this->codegen_model->edit('flash_servicios_tarifas_cobranza',$data,'id',$id_tarifa);  

                    if($resultado_edicion){
                        $bandera_edicion_exitosa = true;
                    }         
                } 
                $bandera_opcion = 3;   

                break;   
            case 4:
                $porcentaje = doubleval(trim($this->input->post('porcentaje')));

                $datos = [[$porcentaje]];

                $resultado_comparacion_campos = comparacion($cliente_id, $opcion, $zonaA, $zonaB, $datos, $id_tarifa);
              //print_r(count($resultado_comparacion_campos)); var_dump($resultado_comparacion_campos);

                if(count($resultado_comparacion_campos) == 0){
                    $data = array(
                        'porcentaje' => $porcentaje,
                    );

                    $resultado_edicion = $this->codegen_model->edit('flash_servicios_tarifas_valor_declarado',$data,'id',$id_tarifa);  

                    if($resultado_edicion){
                        $bandera_edicion_exitosa = true;
                    }         
                } 
                $bandera_opcion = 4;   

                break;                                        
            case 5:
                $desde = doubleval(trim($this->input->post('desde')));
                $hasta = doubleval(trim($this->input->post('hasta')));
                $precio = doubleval(trim($this->input->post('precio')));
                $tipo = intval(trim($this->input->post('tipo')));

                $datos = [[$desde, $hasta, $precio, $tipo]];

                $resultado_comparacion_campos = comparacion($cliente_id, $opcion, $zonaA, $zonaB, $datos, $id_tarifa);
              //print_r(count($resultado_comparacion_campos)); var_dump($resultado_comparacion_campos);

                if(count($resultado_comparacion_campos) == 0){
                    $data = array(
                        'zonaA' => $zonaA,
                        'zonaB' => $zonaB,
                        'desde_cantidad_metro_cubico' => $desde,
                        'hasta_cantidad_metro_cubico' => $hasta,
                        'precio_mc' => $precio,
                        'bandera_bloque_unidad' => $tipo,
                    );

                    $resultado_edicion = $this->codegen_model->edit('flash_servicios_tarifas_metro_cubico',$data,'id',$id_tarifa);  



                    if($resultado_edicion){
                        $bandera_edicion_exitosa = true;
                    }    

                             
                } 
                $bandera_opcion = 5;   

                break; 
            case 6:
                $desde = doubleval(trim($this->input->post('desde')));
                $hasta = doubleval(trim($this->input->post('hasta')));
                $precio = doubleval(trim($this->input->post('precio')));
                $tipo = intval(trim($this->input->post('tipo')));

                $datos = [[$desde, $hasta, $precio, $tipo]];

                $resultado_comparacion_campos = comparacion($cliente_id, $opcion, $zonaA, $zonaB, $datos, $id_tarifa);
              //print_r(count($resultado_comparacion_campos)); var_dump($resultado_comparacion_campos);

                if(count($resultado_comparacion_campos) == 0){
                    $data = array(
                        'zonaA' => $zonaA,
                        'zonaB' => $zonaB,
                        'desde_cantidad_palets' => $desde,
                        'hasta_cantidad_palets' => $hasta,
                        'precio_palets' => $precio,
                        'bandera_palets' => $tipo,
                    );

                    $resultado_edicion = $this->codegen_model->edit('flash_servicios_tarifas_palets',$data,'id',$id_tarifa);  



                    if($resultado_edicion){
                        $bandera_edicion_exitosa = true;
                    }    

                             
                } 
                $bandera_opcion = 6;   

                break; 
            case 7:
                $precio = doubleval(trim($this->input->post('precio')));

                if(count($resultado_comparacion_campos) == 0){
                    $data = array(
                        'precio_gf' => $precio,
                    );

                    $resultado_edicion = $this->codegen_model->edit('flash_servicios_tarifas_gestion_flota',$data,'id',$id_tarifa);  


                    if($resultado_edicion){
                        $bandera_edicion_exitosa = true;
                    }    

                             
                } 
                $bandera_opcion = 7;   

                break;                 
            case 8:
                $cantidad = doubleval(trim($this->input->post('cantidad')));
                $precio = doubleval(trim($this->input->post('precio')));

                $datos = [[$cantidad, $precio]];

                $resultado_comparacion_campos = comparacion($cliente_id, $opcion, $zonaA, $zonaB, $datos, $id_tarifa);
              //print_r(count($resultado_comparacion_campos)); var_dump($resultado_comparacion_campos);

                if(count($resultado_comparacion_campos) == 0){
                    $data = array(
                        'zonaA' => $zonaA,
                        'zonaB' => $zonaB,
                        'cantidad_wh' => $cantidad,
                        'precio_wh' => $precio,
                    );

                    $resultado_edicion = $this->codegen_model->edit('flash_servicios_tarifas_warehouse',$data,'id',$id_tarifa);  


                    if($resultado_edicion){
                        $bandera_edicion_exitosa = true;
                    }    

                             
                } 
                $bandera_opcion = 8;   

                break;                 
        }


        if($bandera_opcion != 0){
            redirect(base_url().'tarifarioT/Tarifario_seleccion_opciones/listar?id=' . $cliente_id . '&bandera_edicion=' . $bandera_edicion_exitosa . '&bandera_opcion_tarifario=' . $bandera_opcion);             
        }
    }



    public function ajax_eliminacion(){
        $parametros = $this->input->post("parametros");
        $id = $parametros["id"];
        $opcion_elegida = $parametros["opcion"];

        $mensaje = 'no se borro correctamente';
        $banderaBorrado = 0;

     //   print_r($id);  // echo ('<br>'); print_r($id); echo ('<br>'); print_r($opcion_elegida); echo ('<br>');
     
        switch ($opcion_elegida) {
            case '1': //Kilos
                $tarifa = $this->tarifario_md->getTarifaKilos($id);

                if(!is_null($tarifa[0])){
                    //print_r($tarifa[0]->id); 
                    $tarifa_id = $tarifa[0]->id;
                    $resultado_query1 = $this->codegen_model->delete('flash_servicios_tarifas_kg', 'id', $tarifa_id);
                }

                break;
            case '2': //Bultos
                $tarifa = $this->tarifario_md->getTarifaBultos($id);

                if(!is_null($tarifa[0])){
                    //print_r($tarifa[0]->id); 
                    $tarifa_id = $tarifa[0]->id;
                    $resultado_query1 = $this->codegen_model->delete('flash_servicios_tarifas_bultos', 'id', $tarifa_id);
                }
                
                break;  
            case '3': //Cobranza
                $tarifa = $this->tarifario_md->getTarifaCobranza($id);

                if(!is_null($tarifa[0])){
                    //print_r($tarifa[0]->id);
                    $tarifa_id = $tarifa[0]->id;
                    $resultado_query1 = $this->codegen_model->delete('flash_servicios_tarifas_cobranza', 'id', $tarifa_id);
                }
                
                break;     
            case '4': //Valor declarado
                $tarifa = $this->tarifario_md->getTarifaValorDeclarado($id);

                if(!is_null($tarifa[0])){
                    //print_r($tarifa[0]->id); 
                    $tarifa_id = $tarifa[0]->id;
                    $resultado_query1 = $this->codegen_model->delete('flash_servicios_tarifas_valor_declarado', 'id', $tarifa_id);
                }
                
                break;
            case '5': //Metro cubico
                $tarifa = $this->tarifario_md->getTarifaMetroCubico($id);

                if(!is_null($tarifa[0])){
                    //print_r($tarifa[0]->id); 
                    $tarifa_id = $tarifa[0]->id;
                    $resultado_query1 = $this->codegen_model->delete('flash_servicios_tarifas_metro_cubico', 'id', $tarifa_id);
                }
                
                break; 
            case '6': // Palets
                $tarifa = $this->tarifario_md->getTarifaPalets($id);

                if(!is_null($tarifa[0])){
                    //print_r($tarifa[0]->id); 
                    $tarifa_id = $tarifa[0]->id;
                    $resultado_query1 = $this->codegen_model->delete('flash_servicios_tarifas_palets', 'id', $tarifa_id);
                }
                
                break;                                                                            
            case '7': //Gestion de Flota
                $tarifa = $this->tarifario_md->getTarifaGestionFlota($id);

                if(!is_null($tarifa[0])){
                    //print_r($tarifa[0]->id); 
                    $tarifa_id = $tarifa[0]->id;
                    $resultado_query1 = $this->codegen_model->delete('flash_servicios_tarifas_gestion_flota', 'id', $tarifa_id);
                }
                
                break; 
            case '8': // WareHouse
                $tarifa = $this->tarifario_md->getTarifaWareHouse($id);

                if(!is_null($tarifa[0])){
                    //print_r($tarifa[0]->id); 
                    $tarifa_id = $tarifa[0]->id;
                    $resultado_query1 = $this->codegen_model->delete('flash_servicios_tarifas_warehouse', 'id', $tarifa_id);
                }
                
                break;                           
        }


        if($resultado_query1){
            $mensaje = 'se borro correctamente';
        }

        $result = [];
        $result['msj']  = $mensaje;
        $result['estado'] = $resultado_query1;


        echo json_encode($result);
       
        die;
    }
 
}




function insert($tipo_opcion_calculo, $zonaA, $zonaB, $variableA=NULL, $variableB=NULL, $variableC=NULL, $bandera_tipo=NULL, $id_cliente, $user) { 
    $ci =& get_instance();
    $resultado_consulta = "antes de entrar al switch";

    switch ($tipo_opcion_calculo) {
        case '1':
            $tarifa_bultos = array(
                'zonaA' => $zonaA,
                'zonaB' => $zonaB,         
                'desde_cant_unid_kg' => $variableA,
                'hasta_cant_unid_kg' => $variableB,
                'precio_Corte_kg' => $variableC,
                'bandera_Corte_kg' => $bandera_tipo,
            ); 
            $query_insert_registro_tarifa = $ci->codegen_model->add('flash_servicios_tarifas_kg',$tarifa_bultos);

            $paqueteria = array(
                'cliente_id' => $id_cliente,
                'tarifario_kg_id' => $query_insert_registro_tarifa,
                'create_user_id' => $user,
            );
            
            break;
        case '2':
          //  print_r('llega 2');

          //  print_r('inicio:' . $variableA); echo('<br>');  print_r('fin:' . $variableB); echo('<br>'); print_r('precio:' . $variableC);   echo('<br>');print_r('tipo:' . $bandera_tipo);   echo('<br>');


            $tarifa_bultos = array(
                'zonaA' => $zonaA,
                'zonaB' => $zonaB,         
                'desde_cant_unid_bultos' => $variableA,
                'hasta_cant_unid_bultos' => $variableB,
                'precio_Corte_bultos' => $variableC,
                'bandera_Corte_Bultos' => $bandera_tipo,
            ); 
            $query_insert_registro_tarifa = $ci->codegen_model->add('flash_servicios_tarifas_bultos',$tarifa_bultos);

           // var_dump($tarifa_bultos);

            $paqueteria = array(
                'cliente_id' => $id_cliente,
                'tarifario_bultos_id' => $query_insert_registro_tarifa,
                'create_user_id' => $user,
            );

            break;
        case '3':
            $tarifa = array(
                'zonaA' => $zonaA,
                'zonaB' => $zonaB,         
                'porcentaje' => $variableA,
            ); 
            $query_insert_registro_tarifa = $ci->codegen_model->add('flash_servicios_tarifas_cobranza',$tarifa);

            $paqueteria = array(
                'cliente_id' => $id_cliente,
                'tarifario_cobranza_id' => $query_insert_registro_tarifa,
                'create_user_id' => $user,
            );

            break;            
        case '4':
            $tarifa = array(
                'zonaA' => $zonaA,
                'zonaB' => $zonaB,         
                'porcentaje' => $variableA,
            ); 
            $query_insert_registro_tarifa = $ci->codegen_model->add('flash_servicios_tarifas_valor_declarado',$tarifa);

            $paqueteria = array(
                'cliente_id' => $id_cliente,
                'tarifario_valor_declarado_id' => $query_insert_registro_tarifa,
                'create_user_id' => $user,
            );    
            break;
        case '5':
            $tarifa = array(
                'zonaA' => $zonaA,
                'zonaB' => $zonaB,         
                'desde_cantidad_metro_cubico' => $variableA,
                'hasta_cantidad_metro_cubico' => $variableB,
                'precio_mc' => $variableC,      
                'bandera_bloque_unidad' => $bandera_tipo,     
            ); 
            $query_insert_registro_tarifa = $ci->codegen_model->add('flash_servicios_tarifas_metro_cubico',$tarifa);

            $paqueteria = array(
                'cliente_id' => $id_cliente,
                'tarifario_metro_cubico_id' => $query_insert_registro_tarifa,
                'create_user_id' => $user,
            );    
            break;            
        case '6':

            $tarifa = array(
                'zonaA' => $zonaA,
                'zonaB' => $zonaB,         
                'desde_cantidad_palets' => $variableA,
                'hasta_cantidad_palets' => $variableB,
                'precio_palets' => $variableC,
                'bandera_palets' => $bandera_tipo,
            ); 
            $query_insert_registro_tarifa = $ci->codegen_model->add('flash_servicios_tarifas_palets',$tarifa);

            $paqueteria = array(
                'cliente_id' => $id_cliente,
                'tarifario_palets_id' => $query_insert_registro_tarifa,
                'create_user_id' => $user,
            );

            break;
        case '7':

            $tarifa = array(
                'zonaA' => $zonaA,
                'zonaB' => $zonaB,   
                'tipo_vehiculo' => $variableA,   
                'tipo_hora' => $variableB,
                'precio_gf' => $variableC,
            ); 
            $query_insert_registro_tarifa = $ci->codegen_model->add('flash_servicios_tarifas_gestion_flota',$tarifa);

            $paqueteria = array(
                'cliente_id' => $id_cliente,
                'tarifario_gestion_flota_id' => $query_insert_registro_tarifa,
                'create_user_id' => $user,
            );

            break;            
        case '8':
            $tarifa = array(
                'zonaA' => $zonaA,
                'zonaB' => $zonaB,
                'cantidad_wh' => $variableA,
                'precio_wh' => $variableC,
            );

            $query_insert_registro_tarifa = $ci->codegen_model->add('flash_servicios_tarifas_warehouse',$tarifa);

            $paqueteria = array(
                'cliente_id' => $id_cliente,
                'tarifario_warehouse_id' => $query_insert_registro_tarifa,
                'create_user_id' => $user,
            );            
            
            break;
        default:
            $resultado_consulta = '...EN CONSTRUCCION...2';
    }

    if(!empty($query_insert_registro_tarifa)){
        $query_insert_paqueteria = $ci->codegen_model->add('flash_servicios_paqueteria',$paqueteria);

        if(!empty($query_insert_paqueteria)){
            $resultado_consulta = 'se grabaron correctamente.';
        }
        else{
            $resultado_consulta = 'no se grabo correctamente.';
        }
    } 

    return $resultado_consulta;
}



function comparacion($cliente_id, $opcion_elegida_tipo, $zonaA, $zonaB, $datos, $id_tarifa=NULL){

    $ci =& get_instance();

    $array_resultado_comparacion = [];
    $contador_lineas_campos = 1;

    switch ($opcion_elegida_tipo) {
        case 1:
/*
                print_r($datos);
                echo('<br>');
                print_r($cliente_id);
                echo('<br>');
                print_r($opcion_elegida_tipo);
                echo('<br>');
                print_r($id_tarifa);
                echo('<br>');
                print_r($zonaA);
                echo('<br>');
                print_r($zonaB);
                echo('<br>');


                print_r(gettype($cliente_id));
                echo('<br>');
                print_r(gettype($opcion_elegida_tipo));
                echo('<br>');
                print_r(gettype($id_tarifa));
                echo('<br>');
                print_r(gettype($zonaA));
                echo('<br>');
                print_r(gettype($zonaB));
                echo('<br>');    
*/

            foreach($datos as $dato){

                $queryA = $ci->tarifario_md->getComparacionTarifasKilos_desde($cliente_id, $dato[0], $zonaA, $zonaB, $id_tarifa);
                $queryB = $ci->tarifario_md->getComparacionTarifasKilos_hasta($cliente_id, $dato[1], $zonaA, $zonaB, $id_tarifa);
                $queryC = $ci->tarifario_md->getComparacionTarifasKilos_igualacion($cliente_id, $dato[0], $dato[1], $zonaA, $zonaB, $id_tarifa);
               // var_dump($queryA[0]->cantidad);

                if($queryA[0]->cantidad > 0){
                    $array_resultado_comparacion = [
                        "nombre" => 'desde',
                        "valor_campo" => $dato[0], 
                        "linea" => $contador_lineas_campos,
                    ];

                    break; 
                }

                if($queryB[0]->cantidad > 0){
                    $array_resultado_comparacion = [
                        "nombre" => 'hasta',
                        "valor_campo" => $dato[1], 
                        "linea" => $contador_lineas_campos,
                    ];

                    break; 
                }

                if($queryC[0]->cantidad > 0){
                    $array_resultado_comparacion = [
                        "nombre" => 'desde/hasta',
                        "valor_campo" => $dato[1], 
                        "linea" => $contador_lineas_campos,
                    ];

                    break; 
                }                

                $contador_lineas_campos = $contador_lineas_campos + 1;
            }

            break;
        case 2:

            foreach($datos as $dato){
                $queryA = $ci->tarifario_md->getComparacionTarifasBultos_desde($cliente_id, $dato[0], $zonaA, $zonaB, $id_tarifa);
                $queryB = $ci->tarifario_md->getComparacionTarifasBultos_hasta($cliente_id, $dato[1], $zonaA, $zonaB, $id_tarifa);
                $queryC = $ci->tarifario_md->getComparacionTarifasBultos_igualacion($cliente_id, $dato[0], $dato[1], $zonaA, $zonaB, $id_tarifa);
                //var_dump($queryA[0]->cantidad);         

                if($queryA[0]->cantidad > 0){
                    $array_resultado_comparacion = [
                        "nombre" => 'desde',
                        "valor_campo" => $dato[0], 
                        "linea" => $contador_lineas_campos,
                    ];

                    break; 
                }

                if($queryB[0]->cantidad > 0){
                    $array_resultado_comparacion = [
                        "nombre" => 'hasta',
                        "valor_campo" => $dato[1], 
                        "linea" => $contador_lineas_campos,
                    ];

                    break; 
                }


                if($queryC[0]->cantidad > 0){
                    $array_resultado_comparacion = [
                        "nombre" => 'desde/hasta',
                        "valor_campo" => $dato[1], 
                        "linea" => $contador_lineas_campos,
                    ];

                    break; 
                }     

                $contador_lineas_campos = $contador_lineas_campos + 1;
            }

            break;
        case 3:
            foreach($datos as $dato){
                $queryA = $ci->tarifario_md->getComparacionTarifasCobranza($cliente_id, $dato[0], $zonaA, $zonaB, $id_tarifa);
              //  var_dump($queryA[0]->cantidad);

                if($queryA[0]->cantidad > 0){
                    $array_resultado_comparacion = [
                        "nombre" => 'porcentaje',
                        "valor_campo" => $dato[0], 
                        "linea" => $contador_lineas_campos,
                    ];

                    break; 
                }

                $contador_lineas_campos = $contador_lineas_campos + 1;
            }

            break;            
        case 4:
            foreach($datos as $dato){
                $queryA = $ci->tarifario_md->getComparacionTarifasValorDeclarado($cliente_id, $dato[0], $zonaA, $zonaB, $id_tarifa);
              //  var_dump($queryA[0]->cantidad);

                if($queryA[0]->cantidad > 0){
                    $array_resultado_comparacion = [
                        "nombre" => 'porcentaje',
                        "valor_campo" => $dato[0], 
                        "linea" => $contador_lineas_campos,
                    ];

                    break; 
                }

                $contador_lineas_campos = $contador_lineas_campos + 1;
            }

            break;  
        case 5:
            foreach($datos as $dato){
                $queryA = $ci->tarifario_md->getComparacionTarifasMetroCubico_desde($cliente_id, $dato[0], $zonaA, $zonaB, $id_tarifa);
                $queryB = $ci->tarifario_md->getComparacionTarifasMetroCubico_hasta($cliente_id, $dato[1], $zonaA, $zonaB, $id_tarifa);
                $queryC = $ci->tarifario_md->getComparacionTarifasMetroCubico_igualacion($cliente_id, $dato[0], $dato[1], $zonaA, $zonaB, $id_tarifa);
                //var_dump($queryA[0]->cantidad);

                if($queryA[0]->cantidad > 0){
                    $array_resultado_comparacion = [
                        "nombre" => 'desde',
                        "valor_campo" => $dato[0], 
                        "linea" => $contador_lineas_campos,
                    ];

                    break; 
                }

                if($queryB[0]->cantidad > 0){
                    $array_resultado_comparacion = [
                        "nombre" => 'hasta',
                        "valor_campo" => $dato[1], 
                        "linea" => $contador_lineas_campos,
                    ];

                    break; 
                }


                if($queryC[0]->cantidad > 0){
                    $array_resultado_comparacion = [
                        "nombre" => 'desde/hasta',
                        "valor_campo" => $dato[1], 
                        "linea" => $contador_lineas_campos,
                    ];

                    break; 
                }     

                $contador_lineas_campos = $contador_lineas_campos + 1;
            }
            break;    
        case 6:
            foreach($datos as $dato){
                $queryA = $ci->tarifario_md->getComparacionTarifasPalets_desde($cliente_id, $dato[0], $zonaA, $zonaB, $id_tarifa);
                $queryB = $ci->tarifario_md->getComparacionTarifasPalets_hasta($cliente_id, $dato[1], $zonaA, $zonaB, $id_tarifa);
                $queryC = $ci->tarifario_md->getComparacionTarifasPalets_igualacion($cliente_id, $dato[0], $dato[1], $zonaA, $zonaB, $id_tarifa);

                //var_dump($queryA[0]->cantidad);

                if($queryA[0]->cantidad > 0){
                    $array_resultado_comparacion = [
                        "nombre" => 'desde',
                        "valor_campo" => $dato[0], 
                        "linea" => $contador_lineas_campos,
                    ];

                    break; 
                }

                if($queryB[0]->cantidad > 0){
                    $array_resultado_comparacion = [
                        "nombre" => 'hasta',
                        "valor_campo" => $dato[1], 
                        "linea" => $contador_lineas_campos,
                    ];

                    break; 
                }

                if($queryC[0]->cantidad > 0){
                    $array_resultado_comparacion = [
                        "nombre" => 'desde/hasta',
                        "valor_campo" => $dato[1], 
                        "linea" => $contador_lineas_campos,
                    ];

                    break; 
                }                         

                $contador_lineas_campos = $contador_lineas_campos + 1;
            }


            break;
        case 7:// Gestion de flota
            foreach($datos as $dato){

                switch ($dato[0]) {
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


                $queryA = $ci->tarifario_md->getComparacionTarifasGestionFlota($cliente_id, $opcion_vehiculos, $opcion_horas, $zonaA, $zonaB);
                //var_dump($queryA[0]->cantidad);


                if($queryA[0]->cantidad > 0){
                    $array_resultado_comparacion = [
                        "nombre" => 'Vehiculo // Hora',
                        "valor_campo" => $dato[0], 
                        "linea" => $contador_lineas_campos,
                    ];

                    break; 
                }

                $contador_lineas_campos = $contador_lineas_campos + 1;
            }

            break;
        case 8: //Warehouse
            foreach($datos as $dato){
                $queryA = $ci->tarifario_md->getComparacionTarifasWareHouse($cliente_id, $dato[0], $zonaA, $zonaB, $id_tarifa);
            //  print_r($id_tarifa);

                if($queryA[0]->cantidad > 0){
                    $array_resultado_comparacion = [
                        "nombre" => 'Cantidad',
                        "valor_campo" => $dato[0], 
                        "linea" => $contador_lineas_campos,
                    ];

                    break; 
                }

                $contador_lineas_campos = $contador_lineas_campos + 1;
            }

            break;            
    }

    return $array_resultado_comparacion;
}

