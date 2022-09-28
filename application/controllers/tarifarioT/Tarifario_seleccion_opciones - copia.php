<?php
//Controlador de la GUI de Comerciales, donde elije que campos podran llenarse en Paqueteria
 use Illuminate\Database\Capsule\Manager as DB;

class Tarifario_seleccion_opciones extends MY_Controller {

   // private $permisos;
    public function __construct()
    {
        parent::__construct();
       // $this->permisos = $this->permisos_lib->control();
    }

    public function index()
    {
        //Declaracion de variables
        $resultado_comparacion = 0;
        $contador_comparaciones = 0;
        $resultado_insert = '';  

        $zonaA = '';
        $zonaB = '';       

        if($this->input->is_post()) //Ingresando por POST
        {
            //Obtiene datos de los campos labels
            $id_cliente = trim($this->input->post('c_cliente'));
            $tipo_opcion_calculo = trim($this->input->post('c_selectCampos'));
            $userSesion = $this->ion_auth->user()->row();
            $user = $userSesion->id;

            $zonaA = trim($this->input->post('c_zonaA'));
            $zonaB = trim($this->input->post('c_zonaB'));
               
            //Escoge segun la opcion seleccionada al cargar datos (kilos, bultos,...)
            switch ($tipo_opcion_calculo) {
                //Opcion palets
                case '6':
                    for ($i=1; $i < 11; $i++) { 
                        //Obtiene datos de los campos labels
                        $cantidad = trim($this->input->post('c_cantidad'.$i));
                        $precio_cantidad = trim($this->input->post('c_precioP'.$i));

                        if(!empty($cantidad) && !empty($precio_cantidad)){
                            //Evalua si los datos ya existen
                            $resultado_comparacion = comparacion($cantidad, NULL, $id_cliente, $zonaA, $zonaB, $tipo_opcion_calculo);
                            $contador_comparaciones = $contador_comparaciones + $resultado_comparacion;

                            if($resultado_comparacion == 0){
                                //Si los datos no existen se graban
                                $resultado_insert = insert($tipo_opcion_calculo, $zonaA, $zonaB, $cantidad, NULL, $precio_cantidad, NULL, $id_cliente, $user);
                            }

                        }
                        else{
                            $mensaje = 'no entro en if';
                        }
                    }

                    break;
                default:
                    //Opcion bultos - Opcion kg.
                    if($tipo_opcion_calculo == 1 or $tipo_opcion_calculo == 2 or $tipo_opcion_calculo == 4){
                        for ($i=1; $i < 11; $i++) {    
                            //Obtiene datos de los campos labels            
                            $rangoInicio =  trim($this->input->post('c_rinicio' . $i));
                            $rangoFin = trim($this->input->post('c_rfin' . $i));
                            $precio_rango = trim($this->input->post('c_precio' . $i));
                            $bandera_tipo = trim($this->input->post('c_opciones_tipo' . $i));

                            if(!empty($rangoInicio) && !empty($rangoFin) && !empty($precio_rango) && !empty($bandera_tipo)) {
                                //Evalua si los datos ya existen
                                $resultado_comparacion = comparacion($rangoInicio, $rangoFin, $id_cliente, $zonaA, $zonaB, $tipo_opcion_calculo);
                                $contador_comparaciones = $contador_comparaciones + $resultado_comparacion;

                                if($resultado_comparacion == 0){
                                    //Si los datos no existen se graban
                                    $resultado_insert = insert($tipo_opcion_calculo, $zonaA, $zonaB, $rangoInicio, $rangoFin, $precio_rango, $bandera_tipo, $id_cliente, $user);
                                }
                            }
                            else{
                                $mensaje = 'no entro en if';
                            }
                        }
                    }
                    break;
            }

        }           
       

//  Realiza la consulta de todos los clientes con su servicio paqueteria
        $query_lista_clientes_servicio = $this->db
        ->select('fc.id, fc.nombre, fs.nombre servicio')
        ->join('flash_clientes_precios_especiales fcps', 'fcps.cliente_id = fc.id', 'inner')
        ->join('flash_servicios fs', 'fcps.servicio_id = fs.id','inner')
        ->order_by('fc.nombre', 'asc')
        ->where('fs.id = 2')
        ->get('flash_clientes fc');
// Comprueba resultado de la consulta
        $lista_clientes_servicio = $query_lista_clientes_servicio ->result();

        if ($lista_clientes_servicio) {
            $existenClientes = true;
        }else{
            $existenClientes = false;
        }

// Realizo la consulta de todos los tarifarios (de kg) de la base de datos.
        $query_tarifas_kg= $this->db
        ->select('fspt.id, fc.id cliente, fspt.desde_cant_unid_kg, fspt.hasta_cant_unid_kg, fspt.precio_Corte_kg, fspt.bandera_Corte_kg, fspt.zonaA, fspt.zonaB')
        ->join('flash_servicios_paqueteria fsp', 'fc.id = fsp.cliente_id', 'inner')
        ->join('flash_servicios_tarifas_kg fspt', 'fsp.tarifario_kg_id = fspt.id', 'inner')
        ->get('flash_clientes fc');
// Comprueba resultado de la consulta
        $lista_tarifas_kg = $query_tarifas_kg ->result();

// Realizo la consulta de todos los tarifarios (de bultos) de la base de datos.
        $query_tarifas_bultos = $this->db
        ->select('fspt.id, fc.id cliente, fspt.desde_cant_unid_bultos, fspt.hasta_cant_unid_bultos, fspt.precio_Corte_bultos, fspt.bandera_Corte_Bultos, fspt.zonaA, fspt.zonaB')
        ->join('flash_servicios_paqueteria fsp', 'fc.id = fsp.cliente_id', 'inner')
        ->join('flash_servicios_tarifas_bultos fspt', 'fsp.tarifario_bultos_id = fspt.id', 'inner')
        ->get('flash_clientes fc');
// Comprueba resultado de la consulta
        $lista_tarifas_bultos = $query_tarifas_bultos ->result();

// Realizo la consulta de todos los tarifarios (de bultos) de la base de datos.
        $query_tarifas_cobranza = $this->db
        ->select('fspt.id, fc.id cliente, fspt.desde_monto_cobranza, fspt.hasta_monto_cobranza, fspt.precio_Corte_cobranza, fspt.bandera_Corte_cobranza, fspt.zonaA, fspt.zonaB')
        ->join('flash_servicios_paqueteria fsp', 'fc.id = fsp.cliente_id', 'inner')
        ->join('flash_servicios_tarifas_cobranza fspt', 'fsp.tarifario_cobranza_id = fspt.id', 'inner')
        ->get('flash_clientes fc');
// Comprueba resultado de la consulta
        $lista_tarifas_cobranza = $query_tarifas_cobranza ->result();


// Realizo la consulta de todos los tarifarios (de palets) de la base de datos.
        $query_tarifas_palets = $this->db
        ->select('fspt.id, fc.id cliente, fspt.cantidad_palet, fspt.precio_palet, fspt.zonaA, fspt.zonaB')
        ->join('flash_servicios_paqueteria fsp', 'fc.id = fsp.cliente_id', 'inner')
        ->join('flash_servicios_tarifas_palets fspt', 'fsp.tarifario_palets_id = fspt.id', 'inner')
        ->get('flash_clientes fc');
// Comprueba resultado de la consulta
        $lista_tarifas_palets = $query_tarifas_palets ->result();

//Obtiene las zonas
        $lista_zonas = cargaZonas();
        

// Armo Array de la vista interna, asignandole Permisos, lista de clientes, listas de zonas, listas de tarifas (bultos, palets..) y resultados-evauaciones //
        $vista_interna = array(
            'permisos_efectivos'    => $this->permisos,
            'clientes'              => $lista_clientes_servicio,
            'datos_clientes'        => $existenClientes,
            'tarifas_kg'            => $lista_tarifas_kg,
            'tarifas_bultos'        => $lista_tarifas_bultos,
            'tarifas_cobranza'      => $lista_tarifas_cobranza,
            'tarifas_palets'        => $lista_tarifas_palets,
            'resultado_grabado'     => $resultado_insert,
            'observacion_grabado' => $contador_comparaciones,
            'zonas' => $lista_zonas,
            'zonaA' => $zonaA,
            'zonaB' => $zonaB
        );

// Armo Array de la vista externa, agrego titulo y cargo la vista enviandole el Array de vista interna //
        $vista_externa = array(
            'title'          => ucwords("Comerciales"),
            'contenido_main' => $this->load->view('components/tarifario/seleccion_opciones', $vista_interna, true)
        );

// Cargo vista //
        $this->load->view('template/backend', $vista_externa);
    
    }

    public function listar($id)
    {
//1) Kg
        $query_tarifas_kg = $this->db
        ->select('fsp.id paqueteria_tarifa, fspt.id, fc.id cliente, fspt.desde_cant_unid_kg, fspt.hasta_cant_unid_kg, fspt.precio_Corte_kg, fspt.bandera_Corte_kg, fspt.zonaA, fspt.zonaB')
        ->join('flash_servicios_paqueteria fsp', 'fc.id = fsp.cliente_id', 'inner')
        ->join('flash_servicios_tarifas_kg fspt', 'fsp.tarifario_kg_id = fspt.id', 'inner')
        ->where('fc.id = ' . $id)
        ->get('flash_clientes fc');
        $lista_tarifas_kg = $query_tarifas_kg ->result();

//2) Bultos
        $query_tarifas_bultos = $this->db
        ->select('fsp.id paqueteria_tarifa, fspt.id, fc.id cliente, fspt.desde_cant_unid_bultos, fspt.hasta_cant_unid_bultos, fspt.precio_Corte_bultos, fspt.bandera_Corte_Bultos, fspt.zonaA, fspt.zonaB')
        ->join('flash_servicios_paqueteria fsp', 'fc.id = fsp.cliente_id', 'inner')
        ->join('flash_servicios_tarifas_bultos fspt', 'fsp.tarifario_bultos_id = fspt.id', 'inner')
        ->where('fc.id = ' . $id)
        ->get('flash_clientes fc');
        $lista_tarifas_bultos = $query_tarifas_bultos ->result();


// 6) Palets
        $query_tarifas_palets = $this->db
        ->select('fsp.id paqueteria_tarifa, fspt.id, fc.id cliente, fspt.cantidad_palet, fspt.precio_palet, fspt.zonaA, fspt.zonaB')
        ->join('flash_servicios_paqueteria fsp', 'fc.id = fsp.cliente_id', 'inner')
        ->join('flash_servicios_tarifas_palets fspt', 'fsp.tarifario_palets_id = fspt.id', 'inner')
        ->where('fc.id = ' . $id)
        ->get('flash_clientes fc');
        $lista_tarifas_palets = $query_tarifas_palets ->result();

//Obtiene zonas
        $lista_zonas = cargaZonas();

        // Armo Array de la vista interna, asignandole Permisos y listas de tarifas
        $vista_interna = array(
            'permisos_efectivos'    => $this->permisos,
            'id_cliente'            => $id,
            'lista_tarifas_bultos'  => $lista_tarifas_bultos,
            'lista_tarifas_palets'  => $lista_tarifas_palets,
            'lista_tarifas_kg'      => $lista_tarifas_kg,
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


    public function edit($id){
        //Obtiene zonas
        $lista_zonas = cargaZonas();

        //Declara variables
        $valor_columna = 0;
        $clasificacion = 0;
        $tarifa_bultos = null;

        //carga tarifas
        $tarifa_kg = detalles_tarifa_kg($id);
        $tarifa_bultos = detalles_tarifa_bultos($id);
        $tarifa_palets = detalles_tarifa_palets($id);

        if ($this->input->post('enviar_form')){ //Ingresa por POST
            $tipo_tarifa = 0;
//Comprueba tarifas vacias
            if(!empty($tarifa_kg)){
                $tipo_tarifa = 1;
            }

            if(!empty($tarifa_bultos)){
                $tipo_tarifa = 2;
            }

            if(!empty($tarifa_palets)){
                $tipo_tarifa = 6;
            }

//Decide sobre que valores esta haciendo la edicion
            switch ($tipo_tarifa) {
                case 1: //Kg
                //Obtiene valores de los labels
                    $id_tarifa = trim($this->input->post('id'));
                    $zonaA = trim($this->input->post('c_zonaA'));
                    $zonaB = trim($this->input->post('c_zonaB'));
                    $desde = trim($this->input->post('c_desde'));
                    $hasta = trim($this->input->post('c_hasta'));
                    $cliente_id = trim($this->input->post('cliente'));
                //Comprueba los valores
                    if($desde == 0 or $hasta == 0 or $desde > $hasta){
                        $valor_columna = $valor_columna + 1;
                    }
                //Modifica el tipo para hacer la consulta (SQL tiene estos valores como DECIMAL)
                    $desde = doubleval($desde);
                    $hasta = doubleval($hasta);
                //Compara valor DESDE
                    $query_sub1 = $this->db
                    ->select('count(fspt.id) as cantidad')
                    ->join('flash_servicios_paqueteria fsp','fsp.tarifario_kg_id = fspt.id','left')
                    ->where('fspt.desde_cant_unid_kg <= ' . $desde)
                    ->where('fspt.hasta_cant_unid_kg >= ' . $desde)
                    ->where('fspt.zonaA = ' . $zonaA)
                    ->where('fspt.zonaB = ' . $zonaB)
                    ->where('fsp.cliente_id = ' . $cliente_id)
                    ->where('fspt.id <>' . $id_tarifa)
                    ->get('flash_servicios_tarifas_kg fspt');
                    $query_subA = $query_sub1->result();
                //Asigna resultado de la compracion
                    foreach ($query_subA as $qA) {
                        $valor_columna = $valor_columna + $qA->cantidad;
                    }
                //Compara valor DESDE
                    $query_sub2 = $this->db
                    ->select('count(fspt.id) as cantidad')
                    ->join('flash_servicios_paqueteria fsp','fsp.tarifario_kg_id = fspt.id','left')
                    ->where('fspt.desde_cant_unid_kg <= ' . $hasta)
                    ->where('fspt.hasta_cant_unid_kg >= ' . $hasta)
                    ->where('fspt.zonaA = ' . $zonaA)
                    ->where('fspt.zonaB = ' . $zonaB)            
                    ->where('fsp.cliente_id = ' . $cliente_id)
                    ->where('fspt.id <>' . $id_tarifa)
                    ->get('flash_servicios_tarifas_kg fspt');
                    $query_subB = $query_sub2->result();
                //Asigna resultado de la compracion
                    foreach ($query_subB as $qB) {
                        $valor_columna = $valor_columna + $qB->cantidad;
                    }
                //Si pasan la comprabacion los valores entonces carga los datos en Bds
                    if($valor_columna == 0){
                        //Crea array a cargar
                        $data = array(
                            'zonaA' => $zonaA,
                            'zonaB' => $zonaB,
                            'desde_cant_unid_kg' => $desde,
                            'hasta_cant_unid_kg' => $hasta,
                            'precio_Corte_kg' => $this->input->post('c_precio'),
                            'bandera_Corte_kg' => $this->input->post('c_tipo'),
                        );
                        $this->codegen_model->edit('flash_servicios_tarifas_kg',$data,'id',$id_tarifa); 

                        redirect(base_url().'tarifarioT/Tarifario_seleccion_opciones/listar/'.$cliente_id);
                    }

                    break;
                case 2: //Bultos
                    $id_tarifa = $this->input->post('id');
                    $zonaA = $this->input->post('c_zonaA');
                    $zonaB = $this->input->post('c_zonaB');
                    $desde = $this->input->post('c_desde');
                    $hasta = $this->input->post('c_hasta');
                    $cliente_id = $this->input->post('cliente');

                    if($desde == 0 or $hasta == 0 or $desde > $hasta){
                        $valor_columna = $valor_columna + 1;
                    }
                    //Compara valor DESDE
                    $query_sub1 = $this->db
                    ->select('count(fspt.id) as cantidad')
                    ->join('flash_servicios_paqueteria fsp','fsp.tarifario_bultos_id = fspt.id','left')
                    ->where('fspt.desde_cant_unid_bultos <= ' . $desde)
                    ->where('fspt.hasta_cant_unid_bultos >= ' . $desde)
                    ->where('fspt.zonaA = ' . $zonaA)
                    ->where('fspt.zonaB = ' . $zonaB)
                    ->where('fsp.cliente_id = ' . $cliente_id)
                    ->where('fspt.id <>' . $id_tarifa)
                    ->get('flash_servicios_tarifas_bultos fspt');
                    $query_subA = $query_sub1->result();
                    //Asigna resultado de la compracion
                    foreach ($query_subA as $qA) {
                        $valor_columna = $valor_columna + $qA->cantidad;
                    }
                    //Compara 
                    $query_sub2 = $this->db
                    ->select('count(fspt.id) as cantidad')
                    ->join('flash_servicios_paqueteria fsp','fsp.tarifario_bultos_id = fspt.id','left')
                    ->where('fspt.desde_cant_unid_bultos <= ' . $hasta)
                    ->where('fspt.hasta_cant_unid_bultos >= ' . $hasta)
                    ->where('fspt.zonaA = ' . $zonaA)
                    ->where('fspt.zonaB = ' . $zonaB)            
                    ->where('fsp.cliente_id = ' . $cliente_id)
                    ->where('fspt.id <>' . $id_tarifa)
                    ->get('flash_servicios_tarifas_bultos fspt');
                    $query_subB = $query_sub2->result();
                    //Asigna resultado de la compracion
                    foreach ($query_subB as $qB) {
                        $valor_columna = $valor_columna + $qB->cantidad;
                    }

                //Si pasan la comprabacion los valores entonces carga los datos en Bds
                    if($valor_columna == 0){
                        //Crea array a cargar
                        $data = array(
                            'zonaA' => $zonaA,
                            'zonaB' => $zonaB,
                            'desde_cant_unid_bultos' => $desde,
                            'hasta_cant_unid_bultos' => $hasta,
                            'precio_Corte_bultos' => $this->input->post('c_precio'),
                            'bandera_Corte_Bultos' => $this->input->post('c_tipo'),
                        );
                        $this->codegen_model->edit('flash_servicios_tarifas_bultos',$data,'id',$id_tarifa); 

                        redirect(base_url().'tarifarioT/Tarifario_seleccion_opciones/listar/'.$cliente_id);
                    }

                    break;
                case '6': //Palets
                    $id_tarifa = $this->input->post('id');
                    $zonaA = $this->input->post('c_zonaA');
                    $zonaB = $this->input->post('c_zonaB');
                    $cantidad_palet = $this->input->post('c_cantidad');
                    $cliente_id = $this->input->post('cliente');

                    if($cantidad_palet == 0){
                        $valor_columna = $valor_columna + 1;
                    }

                    $query_sub1 = $this->db
                    ->select('count(fspt.id) as cantidad')
                    ->join('flash_servicios_paqueteria fsp','fsp.tarifario_palets_id = fspt.id','left')
                    ->where('fspt.cantidad_palet = ' . $cantidad_palet)
                    ->where('fspt.zonaA = ' . $zonaA)
                    ->where('fspt.zonaB = ' . $zonaB)
                    ->where('fsp.cliente_id = ' . $cliente_id)
                    ->where('fspt.id <>' . $id_tarifa)
                    ->get('flash_servicios_tarifas_palets fspt');
                    $query_subA = $query_sub1->result();

                    foreach ($query_subA as $qA) {
                        $valor_columna = $valor_columna + $qA->cantidad;
                    }

                    if($valor_columna == 0){
                        $data = array(
                            'zonaA' => $zonaA,
                            'zonaB' => $zonaB,
                            'cantidad_palet' => $cantidad_palet,
                            'precio_palet' => $this->input->post('c_precio'),
                        );
                        $this->codegen_model->edit('flash_servicios_tarifas_palets',$data,'id',$id_tarifa); 

                        redirect(base_url().'tarifarioT/Tarifario_seleccion_opciones/listar/'.$cliente_id);
                    }

                    break;
                default:
                    # code...
                    break;
            }  

        }

        $vista_interna = array(
            'permisos_efectivos' => $this->permisos,
            'tarifa_kg'     => $tarifa_kg,
            'tarifa_bultos' => $tarifa_bultos,
            'tarifa_palets' => $tarifa_palets,
            'consulta' => $valor_columna,
            'lista_zonas' => $lista_zonas
        );

        $vista_externa = array(         
            'title' => ucwords("Edicion"),
            'contenido_main' => $this->load->view('components/tarifario/seleccion_opciones_edit', $vista_interna, true)
        );        
   
        $this->load->view('template/backend', $vista_externa);
    }


    public function delete($id){
        $tarifa_kg = detalles_tarifa_kg($id);
        if(!empty($tarifa_kg)){
            foreach ($tarifa_kg as $tarifa) {
                $this->codegen_model->delete('flash_servicios_tarifas_kg','id',$tarifa->id); 
            }  
        }

        $tarifa_bultos = detalles_tarifa_bultos($id);
        if(!empty($tarifa_bultos)){
            foreach ($tarifa_bultos as $tarifa) {
                $this->codegen_model->delete('flash_servicios_tarifas_bultos','id',$tarifa->id); 
            }  
        }

        $tarifa_palets = detalles_tarifa_palets($id);
        if(!empty($tarifa_palets)){
            foreach ($tarifa_palets as $tarifa) {
                $this->codegen_model->delete('flash_servicios_tarifas_palets','id',$tarifa->id); 
            }  
        }           
    }
}


function insert($tipo_opcion_calculo, $zonaA, $zonaB, $rangoInicio_cantidad, $rangoFin=NULL, $precio_rango_cantidad, $bandera_tipo=NULL, $id_cliente, $user) { 
    $ci =& get_instance();
    $resultado_consulta = "antes de entrar al switch";

    switch ($tipo_opcion_calculo) {
        case '1':
            $tarifa_bultos = array(
                'zonaA' => $zonaA,
                'zonaB' => $zonaB,         
                'desde_cant_unid_kg' => $rangoInicio_cantidad,
                'hasta_cant_unid_kg' => $rangoFin,
                'precio_Corte_kg' => $precio_rango_cantidad,
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

            $tarifa_bultos = array(
                'zonaA' => $zonaA,
                'zonaB' => $zonaB,         
                'desde_cant_unid_bultos' => $rangoInicio_cantidad,
                'hasta_cant_unid_bultos' => $rangoFin,
                'precio_Corte_bultos' => $precio_rango_cantidad,
                'bandera_Corte_Bultos' => $bandera_tipo,
            ); 
            $query_insert_registro_tarifa = $ci->codegen_model->add('flash_servicios_tarifas_bultos',$tarifa_bultos);

            $paqueteria = array(
                'cliente_id' => $id_cliente,
                'tarifario_bultos_id' => $query_insert_registro_tarifa,
                'create_user_id' => $user,
            );

            break;
        case '4':
            $tarifa_cobranza = array(
                'zonaA' => $zonaA,
                'zonaB' => $zonaB,         
                'desde_monto_cobranza' => $rangoInicio_cantidad,
                'hasta_monto_cobranza' => $rangoFin,
                'precio_Corte_cobranza' => $precio_rango_cantidad,
                'bandera_Corte_cobranza' => $bandera_tipo,
            ); 
            $query_insert_registro_tarifa = $ci->codegen_model->add('flash_servicios_tarifas_cobranza',$tarifa_cobranza);

            $paqueteria = array(
                'cliente_id' => $id_cliente,
                'tarifario_cobranza_id' => $query_insert_registro_tarifa,
                'create_user_id' => $user,
            );        
            break;
        case '6':

            $tarifa = array(
                'zonaA' => $zonaA,
                'zonaB' => $zonaB,         
                'cantidad_palet' => $rangoInicio_cantidad,
                'precio_palet' => $precio_rango_cantidad,
            ); 
            $query_insert_registro_tarifa = $ci->codegen_model->add('flash_servicios_tarifas_palets',$tarifa);

            $paqueteria = array(
                'cliente_id' => $id_cliente,
                'tarifario_palets_id' => $query_insert_registro_tarifa,
                'create_user_id' => $user,
            );

            break;
        default:
            $resultado_consulta = '...EN CONSTRUCCION...2';
            break;
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


function comparacion($desde_cantidad, $hasta=NULL, $cliente_id, $zonaA, $zonaB, $tipo_opcion_calculo){
    $ciA =& get_instance();
    $valor_columna = 0;


    switch($tipo_opcion_calculo){
        case '1': // Kilos
            $query_sub1 = $ciA->db
            ->select('count(fspt.id) as cantidad')
            ->join('flash_servicios_paqueteria fsp','fsp.tarifario_bultos_id = fspt.id','left')
            ->where('fspt.desde_cant_unid_kg <= ' . $desde_cantidad)
            ->where('fspt.hasta_cant_unid_kg >= ' . $desde_cantidad)
            ->where('fspt.zonaA = ' . $zonaA)
            ->where('fspt.zonaB = ' . $zonaB)
            ->where('fsp.cliente_id = ' . $cliente_id)
            ->get('flash_servicios_tarifas_kg fspt');
            if($query_sub1){
                $query_subA = $query_sub1->result();
            }
           
            foreach ($query_subA as $qA) {
                $valor_columna = $valor_columna + $qA->cantidad;
            }

            $query_sub2 = $ciA->db
            ->select('count(fspt.id) as cantidad')
            ->join('flash_servicios_paqueteria fsp','fsp.tarifario_bultos_id = fspt.id','left')
            ->where('fspt.desde_cant_unid_kg <= ' . $hasta)
            ->where('fspt.hasta_cant_unid_kg >= ' . $hasta)
            ->where('fspt.zonaA = ' . $zonaA)
            ->where('fspt.zonaB = ' . $zonaB)    
            ->where('fsp.cliente_id = ' . $cliente_id)
            ->get('flash_servicios_tarifas_kg fspt');
            if($query_sub2){
                $query_subB = $query_sub2->result();
            }

            foreach ($query_subB as $qB) {
                $valor_columna = $valor_columna + $qB->cantidad;
            }
            break;
        case '2': // Bultos
            $query_sub1 = $ciA->db
            ->select('count(fspt.id) as cantidad')
            ->join('flash_servicios_paqueteria fsp','fsp.tarifario_bultos_id = fspt.id','left')
            ->where('fspt.desde_cant_unid_bultos <= ' . $desde_cantidad)
            ->where('fspt.hasta_cant_unid_bultos >= ' . $desde_cantidad)
            ->where('fspt.zonaA = ' . $zonaA)
            ->where('fspt.zonaB = ' . $zonaB)
            ->where('fsp.cliente_id = ' . $cliente_id)
            ->get('flash_servicios_tarifas_bultos fspt');
            if($query_sub1){
                $query_subA = $query_sub1->result();
            }
           
            foreach ($query_subA as $qA) {
                $valor_columna = $valor_columna + $qA->cantidad;
            }

            $query_sub2 = $ciA->db
            ->select('count(fspt.id) as cantidad')
            ->join('flash_servicios_paqueteria fsp','fsp.tarifario_bultos_id = fspt.id','left')
            ->where('fspt.desde_cant_unid_bultos <= ' . $hasta)
            ->where('fspt.hasta_cant_unid_bultos >= ' . $hasta)
            ->where('fspt.zonaA = ' . $zonaA)
            ->where('fspt.zonaB = ' . $zonaB)    
            ->where('fsp.cliente_id = ' . $cliente_id)
            ->get('flash_servicios_tarifas_bultos fspt');
            if($query_sub2){
                $query_subB = $query_sub2->result();
            }

            foreach ($query_subB as $qB) {
                $valor_columna = $valor_columna + $qB->cantidad;
            }

            break;
        case '4':
            $query_sub1 = $ciA->db
            ->select('count(fspt.id) as cantidad')
            ->join('flash_servicios_paqueteria fsp','fsp.tarifario_cobranza_id = fspt.id','left')
            ->where('fspt.desde_monto_cobranza <= ' . $desde_cantidad)
            ->where('fspt.hasta_monto_cobranza >= ' . $desde_cantidad)
            ->where('fspt.zonaA = ' . $zonaA)
            ->where('fspt.zonaB = ' . $zonaB)
            ->where('fsp.cliente_id = ' . $cliente_id)
            ->get('flash_servicios_tarifas_cobranza fspt');
            if($query_sub1){
                $query_subA = $query_sub1->result();
            }
           
            foreach ($query_subA as $qA) {
                $valor_columna = $valor_columna + $qA->cantidad;
            }

            $query_sub2 = $ciA->db
            ->select('count(fspt.id) as cantidad')
            ->join('flash_servicios_paqueteria fsp','fsp.tarifario_cobranza_id = fspt.id','left')
            ->where('fspt.desde_monto_cobranza <= ' . $hasta)
            ->where('fspt.hasta_monto_cobranza >= ' . $hasta)
            ->where('fspt.zonaA = ' . $zonaA)
            ->where('fspt.zonaB = ' . $zonaB)    
            ->where('fsp.cliente_id = ' . $cliente_id)
            ->get('flash_servicios_tarifas_cobranza fspt');
            if($query_sub2){
                $query_subB = $query_sub2->result();
            }

            foreach ($query_subB as $qB) {
                $valor_columna = $valor_columna + $qB->cantidad;
            }

            break;
        case '6': //Palets
            $query_sub1 = $ciA->db
            ->select('count(fspt.id) as cantidad')
            ->join('flash_servicios_paqueteria fsp','fsp.tarifario_palets_id = fspt.id','left')
            ->where('fspt.cantidad_palet = ' . $desde_cantidad)
            ->where('fspt.zonaA = ' . $zonaA)
            ->where('fspt.zonaB = ' . $zonaB)
            ->where('fsp.cliente_id = ' . $cliente_id)
            ->get('flash_servicios_tarifas_palets fspt');
            if($query_sub1){
                $query_subA = $query_sub1->result();
            }
           
            foreach ($query_subA as $qA) {
                $valor_columna = $valor_columna + $qA->cantidad;
            }

            break;
        default:
            //code block
            break;
    }



    return $valor_columna;
}

function cargaZonas(){

    $ci =& get_instance();
    
    $ci->db2 = $ci->load->database('tarifario', TRUE);

    $query_zonas = $ci->db2
    ->select('tp.ID, tp.Nombre provincia, tl.*, IF(tl.es_capital,"Capital","Interior") ubicacion, concat(tp.ID, 0, tl.es_capital) id_provincia_bandera', FALSE)
    ->join('tarifario_provincia tp', 'tp.ID = tl.tarifario_provincia_id', 'left')
    ->where('tp.Trabaja = 1')
    ->where('tp.ID IS NOT NULL')
    ->group_by('tp.Nombre')
    ->group_by('tl.es_capital')
    ->get('tarifario_localidad tl');
    $lista_zonas = $query_zonas ->result();

    return $lista_zonas;
}

function detalles_tarifa_kg($id){
    $ciA =& get_instance();

    $query_tarifa_kg = $ciA->db
    ->select('ft.id, fsp.cliente_id cliente, ft.desde_cant_unid_kg, ft.hasta_cant_unid_kg, ft.precio_Corte_kg, ft.bandera_Corte_kg, ft.zonaA, ft.zonaB')
   ->join('flash_servicios_tarifas_kg ft', 'ft.id = fsp.tarifario_kg_id', 'inner')
   ->where('fsp.id = ' . $id)
   ->get('flash_servicios_paqueteria fsp');
    $tarifa_kg = $query_tarifa_kg ->result();

    return $tarifa_kg;
}

function detalles_tarifa_bultos($id){
    $ciA =& get_instance();

    $query_tarifa_bultos = $ciA->db
    ->select('ft.id, fsp.cliente_id cliente, ft.desde_cant_unid_bultos, ft.hasta_cant_unid_bultos, ft.precio_Corte_bultos, ft.bandera_Corte_Bultos, ft.zonaA, ft.zonaB')
   ->join('flash_servicios_tarifas_bultos ft', 'ft.id = fsp.tarifario_bultos_id', 'inner')
   ->where('fsp.id = ' . $id)
   ->get('flash_servicios_paqueteria fsp');
    $tarifa_bultos = $query_tarifa_bultos ->result();

    return $tarifa_bultos;
}

function detalles_tarifa_palets($id){
    $ciA =& get_instance();

    $query_tarifa_palets = $ciA->db
    ->select('ft.id, fsp.cliente_id cliente, ft.cantidad_palet, ft.precio_palet, ft.zonaA, ft.zonaB')
   ->join('flash_servicios_tarifas_palets ft', 'ft.id = fsp.tarifario_palets_id', 'inner')
   ->where('fsp.id = ' . $id)
   ->get('flash_servicios_paqueteria fsp');
    $tarifa_palets = $query_tarifa_palets ->result();

    return $tarifa_palets;
}