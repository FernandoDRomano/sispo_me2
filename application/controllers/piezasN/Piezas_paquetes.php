<?php


use Illuminate\Database\Capsule\Manager as DB;

class Piezas_paquetes extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->permisos = $this->permisos_lib->control();
        $this->load->model('Pieza_md_n');
        $this->load->helper('url');
    }

    function index(){
            $vista_interna = array(
                'permisos_efectivos' => $this->permisos,
                'paquetes' => [],
                //'clientes' => Cliente::select('id', DB::raw('CONCAT(nombre," (",nombre_fantasia,")") as name'))->where('cliente_estado_id',Cliente::ESTADO_ACTIVO)->get(),
                'comprobante_servicios' => array(),
                'zonas' => $this->codegen_model->get('flash_tarifario_zonas', '*'),
                'paises' => $this->codegen_model->get('flash_paises', '*')
            );

            $vista_externa = array(			
                'title' => ucwords("Piezas Paquetes Nuevo"),
                'contenido_main' => $this->load->view('components/piezasN/piezas_paquetes/piezas_paquetes_add', $vista_interna, true)
            );		

            $this->load->view('template/rubenbackend', $vista_externa);
    }

    function nuevo(){
        $vista_interna = array(
                'permisos_efectivos' => $this->permisos,
                'paquetes' => [],
                'clientes' => Cliente::select('id', DB::raw('CONCAT(nombre," (",nombre_fantasia,")") as name'))->where('cliente_estado_id',Cliente::ESTADO_ACTIVO)->get(),
                'comprobante_servicios' => array(),
                'zonas' => $this->codegen_model->get('flash_tarifario_zonas', '*'),
        );

        $vista_externa = array(			
                'title' => ucwords("Piezas Paquetes Nuevo"),
                'contenido_main' => $this->load->view('components/piezasN/piezas_paquetes/piezas_paquetes_add_2', $vista_interna, true)
        );		

        $this->load->view('template/rubenbackend', $vista_externa);
    }

    function getPaqueteriaXCliente($cliente_id = null, $cliente = null, $pais_origen = null){
        $cliente_id = ($cliente_id != NULL)?$cliente_id:$this->input->post('list_cliente_id');
        $cliente = ($cliente != NULL)?$cliente:$this->input->post('list_cliente');
        $pais_origen = ($pais_origen != NULL)?$pais_origen:$this->input->post('list_pais_id');
        
        $query = $this->db
                ->select(' pt.*')
                ->join('flash_paqueteria_tipos pt', 'c.flash_paqueteria_tipo_id = pt.id')
                ->where('c.id = '.$cliente_id)
                ->get('flash_clientes c');
        
        $query_paquetes_cliente = $this->db
                ->select(' ci.numero comprobante_ingreso, ci.fecha_pedido, pp.descripcion_paquete, pp.peso, pp.dimensiones, pp.bultos , pp.palets, pp.pieza_id, pp.metro_cubico, pp.monto_valor_declarado, pp.monto_cobranza, pp.warehouse, pp.tipo_vehiculo, pp.tipo_hora, pp.cantidad_hora')
                ->join('flash_piezas p', 'ci.id = p.comprobante_ingreso_id')
                ->join('flash_comprobantes_ingresos_servicios cis', 'p.servicio_id = cis.id')
                ->join('flash_piezas_paquetes pp', 'p.id = pp.pieza_id')
                ->where('ci.cliente_id = '.$cliente_id)
                ->order_by('ci.create DESC')
                ->get('flash_comprobantes_ingresos ci');

                
        $query_tarifario = $this->db
                ->select('T.*')
                ->where('T.cliente_id = '. $cliente_id)
                ->get('flash_tarifario T');
                
        $paqueteria = $query->row();
        $paquetes = $query_paquetes_cliente->result();
        $tarifario = $query_tarifario->row();

        //$dep = $this->codegen_model->get('flash_clientes_departamentos', '*', 'cliente_id', $cliente_id);
        //print_r($dep);

        $vista_interna = array(
            'cliente_id'    => $cliente_id,
            'paqueteria_tipo' => $paqueteria->tipo,
            'paquetes'      => $paquetes,
            'cliente'      => $cliente,
            'pais_origen' => $pais_origen,
            'paises' => $this->codegen_model->get('flash_paises', '*'),
            'clientes' => Cliente::select('id', DB::raw('CONCAT(nombre," (",nombre_fantasia,")") as name'))->where('cliente_estado_id',Cliente::ESTADO_ACTIVO)->where('pais', $pais_origen)->get(),
            'localidades' => $this->codegen_model->get('ubicacion_localidades', '*'),
            'zonas' => $this->codegen_model->get('flash_tarifario_zonas', '*'),
            //'departamentos' => $this->flash_md->getDepartamentos($cliente_id)
            'departamentos' => $this->flash_md->getDepartamentos_altapieza($cliente_id),
            'tarifario' => $tarifario
        );

        $vista_externa = array(			
            'title' => ucwords("Piezas Paquetes Nuevo"),
            'contenido_main' => $this->load->view('components/piezasN/piezas_paquetes/piezas_paquetes_add', $vista_interna, true)
        );		

        $this->load->view('template/rubenbackend', $vista_externa);
    }

    function getPaqueteriaXClienteNuevo($cliente_id = null, $cliente = null){
        $cliente_id = ($cliente_id != NULL)?$cliente_id:$this->input->post('list_cliente_id');
        $cliente = ($cliente != NULL)?$cliente:$this->input->post('list_cliente');
        
        $query = $this->db
                ->select(' pt.*')
                ->join('flash_paqueteria_tipos pt', 'c.flash_paqueteria_tipo_id = pt.id')
                ->where('c.id = '.$cliente_id)
                ->get('flash_clientes c');
        
        $query_paquetes_cliente = $this->db
                ->select(' ci.numero comprobante_ingreso, ci.fecha_pedido, pp.descripcion_paquete, pp.peso, pp.dimensiones, pp.bultos , pp.palets, pp.pieza_id, pp.metro_cubico, pp.monto_valor_declarado, pp.monto_cobranza, pp.warehouse, pp.tipo_vehiculo, pp.tipo_hora, pp.cantidad_hora')
                ->join('flash_piezas p', 'ci.id = p.comprobante_ingreso_id')
                ->join('flash_comprobantes_ingresos_servicios cis', 'p.servicio_id = cis.id')
                ->join('flash_piezas_paquetes pp', 'p.id = pp.pieza_id')
                ->where('ci.cliente_id = '.$cliente_id)
                ->order_by('ci.create DESC')
                ->get('flash_comprobantes_ingresos ci');

                
        $query_tarifario = $this->db
                ->select('T.*')
                ->where('T.cliente_id = '. $cliente_id)
                ->get('flash_tarifario T');
                
        $paqueteria = $query->row();
        $paquetes = $query_paquetes_cliente->result();
        $tarifario = $query_tarifario->row();

        //$dep = $this->codegen_model->get('flash_clientes_departamentos', '*', 'cliente_id', $cliente_id);
        //print_r($dep);

        $vista_interna = array(
            'cliente_id'    => $cliente_id,
            'paqueteria_tipo' => $paqueteria->tipo,
            'paquetes'      => $paquetes,
            'cliente'      => $cliente,
            'clientes' => Cliente::select('id', DB::raw('CONCAT(nombre," (",nombre_fantasia,")") as name'))->where('cliente_estado_id',Cliente::ESTADO_ACTIVO)->get(),
            'localidades' => $this->codegen_model->get('ubicacion_localidades', '*'),
            'provincias' => $this->codegen_model->get('ubicacion_provincias', '*'),
            'zonas' => $this->codegen_model->get('flash_tarifario_zonas', '*'),
            //'departamentos' => $this->flash_md->getDepartamentos($cliente_id)
            'departamentos' => $this->flash_md->getDepartamentos_altapieza($cliente_id),
            'tarifario' => $tarifario
        );

        $vista_externa = array(			
            'title' => ucwords("Piezas Paquetes Nuevo"),
            'contenido_main' => $this->load->view('components/piezasN/piezas_paquetes/piezas_paquetes_add_2', $vista_interna, true)
        );		

        $this->load->view('template/rubenbackend', $vista_externa);
    }

    function getClientes(){
        //$stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        //$request = json_decode($stream_clean);
        
        $resultado = $this->Pieza_md_n->getClientes($this->input->get('pais'));

        echo json_encode(["data" => $resultado]);
    }

    function getLocalidadesPorProvincia(){
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $request = json_decode($stream_clean);

        $resultado = $this->Pieza_md_n->getLocalidadesPorProvincia($request->provincia, $request->zona);
        //$cliente = $request->cliente;

        echo json_encode(["data" => $resultado]);
    }

    function getTarifarioXCliente(){
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $request = json_decode($stream_clean);

        $resultado = $this->Pieza_md_n->getMetodosTarifariosXCliente($request->cliente, $request->origen, $request->destino);
        //$cliente = $request->cliente;

        echo json_encode(["data" => $resultado]);
    }

    function getGestionFlotaXCliente(){
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $request = json_decode($stream_clean);

        $resultado = $this->Pieza_md_n->getGestionFlotaXCliente($request->cliente, $request->origen, $request->destino);

        echo json_encode(["data" => $resultado]);
    }

    function comprobarLocalidaDentroDeZonaEsquema(){
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $request = json_decode($stream_clean);

        $resultado = $this->Pieza_md_n->validarLocalidadEsteContenidaEnZonaDelEsquemaTarifario($request->cliente, $request->localidad, $request->opcion);

        echo json_encode(["data" => $resultado]);
    }

    function comprobarZonaDentroDelEsquema(){
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $request = json_decode($stream_clean);

        $resultado = $this->Pieza_md_n->validarZonaDelEsquemaTarifario($request->cliente, $request->zona, $request->opcion);

        echo json_encode(["data" => $resultado]);
    }

    function getLocalidadesPorZona(){
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $request = json_decode($stream_clean);

        $resultado = $this->Pieza_md_n->getLocalidadesPorZona($request->zona);

        echo json_encode(["data" => $resultado]);
    }

    function getPaisPorZona(){
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $request = json_decode($stream_clean);

        $resultado = $this->Pieza_md_n->getPaisPorZona($request->zona);

        echo json_encode(["data" => $resultado]);
    }

    function getProvinciasPorZona(){
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $request = json_decode($stream_clean);

        $resultado = $this->Pieza_md_n->getProvinciasPorZona($request->zona);

        echo json_encode(["data" => $resultado]);
    }

    function geocodificar(){
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $request = json_decode($stream_clean);

        $pais = $request->pais;
        $provincia = $request->provincia;
        $localidad = $request->localidad;
        $cp = $request->CP;
        $calle = $request->calle;
        $altura = $request->altura;

        $data = [
            "pais" => $pais,
            "provincia" => $provincia,
            "localidad" => $localidad,
            "cp" => $cp,
            "calle" => $calle,
            "altura" => $altura
        ];


        if(strlen($pais) > 0 and strlen($calle) > 0 and strlen($altura) and strlen($localidad) > 0 and strlen($provincia) > 0){

            $localizacion= $calle . " " . $altura . ',' . $localidad . ',' . $provincia . ',' . $pais;

            //echo json_encode(["data" => $localizacion]);
            //die;

            switch ($pais) {
                case '1':
                    $code = "AR";
                    break;

                case '2':
                    $code = "BO";
                    break;

                case '3':
                    $code = "PE";
                    break;

                default:
                    # code...
                    break;
            }

            //Obtener los resultados JSON de la peticion - Peticion a google maps para obtener coordenadas
            $geo = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($localizacion).'&sensor=false&components=country:'.$code.'&key=AIzaSyBoUqa9B9TMlrAqgKiXI1vsZa_bo_iESIw');
            $geo = json_decode($geo, true);

            //print_r($geo);

            try{
                if ($geo['status'] == 'OK' && !empty($geo['results'][0]['geometry']['location']['lat']) && !empty($longitud = $geo['results'][0]['geometry']['location']['lng']) ) {
                    // Obtener los valores
                    $latitud = $geo['results'][0]['geometry']['location']['lat'];
                    $longitud = $geo['results'][0]['geometry']['location']['lng'];

                    $condicion = 1;
                    $mensaje = "Geodecodificada";
                }
                else{ // La geo no fue correcta

                    $condicion = 2;
                    $mensaje = "Corregir Direccion";
                }

            }catch(\Throwable $th){
                echo $th;
            }
            
            //return response()->json(["mensaje" => $mensaje, "condicion" => $condicion, "latitud" => $latitud, "longitud" => $longitud, "geo" => $geo]);
            echo json_encode(["mensaje" => $mensaje, "condicion" => $condicion, "latitud" => $latitud, "longitud" => $longitud, "geo" => $geo]);
        }else{
            $condicion = 3;
            $mensaje = "Faltan datos para Geocodificar";
            
            echo json_encode(["mensaje" => $mensaje, "condicion" => $condicion]);
            //return response()->json(["mensaje" => $mensaje, "condicion" => $condicion]);
        }

    }

    function comprobarKiloEnReglaEsquema(){
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $request = json_decode($stream_clean);

        $resultado = $this->Pieza_md_n->validarKiloEnReglaEsquema($request->cliente, $request->zonaA, $request->zonaB, $request->peso);

        echo json_encode(["data" => $resultado]);
    }

    function comprobarBultoEnReglaEsquema(){
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $request = json_decode($stream_clean);

        $resultado = $this->Pieza_md_n->validarBultoEnReglaEsquema($request->cliente, $request->zonaA, $request->zonaB, $request->bulto);

        echo json_encode(["data" => $resultado]);
    }

    function comprobarPaletEnReglaEsquema(){
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $request = json_decode($stream_clean);

        $resultado = $this->Pieza_md_n->validarPaletEnReglaEsquema($request->cliente, $request->zonaA, $request->zonaB, $request->palet);

        echo json_encode(["data" => $resultado]);
    }

    function comprobarMetroCubicoEnReglaEsquema(){
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $request = json_decode($stream_clean);

        $resultado = $this->Pieza_md_n->validarMetroCubicoEnReglaEsquema($request->cliente, $request->zonaA, $request->zonaB, $request->metroCubico);

        echo json_encode(["data" => $resultado]);
    }

    function comprobarWarehouseEnReglaEsquema(){
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $request = json_decode($stream_clean);

        $resultado = $this->Pieza_md_n->validarWarehouseEnReglaEsquema($request->cliente, $request->zonaA, $request->zonaB, $request->warehouse);

        echo json_encode(["data" => $resultado]);
    }

    function comprobarGestionFlotaEnReglaEsquema(){
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $request = json_decode($stream_clean);

        $gestionFlota = $request->gestionFlota;

        switch ($gestionFlota) {
            case '1':
                $tipo_vehiculo = "1";
                $tipo_hora = "1";
                break;
            
            case '2':
                $tipo_vehiculo = "1";
                $tipo_hora = "2";
                break;

            case '3':
                $tipo_vehiculo = "2";
                $tipo_hora = "1";
                break;

            case '4':
                $tipo_vehiculo = "2";
                $tipo_hora = "2";
                break;

            case '5':
                $tipo_vehiculo = "3";
                $tipo_hora = "1";
                break;

            case '6':
                $tipo_vehiculo = "3";
                $tipo_hora = "2";
                break;
            
            case '7':
                $tipo_vehiculo = "4";
                $tipo_hora = "1";
                break;

            case '8':
                $tipo_vehiculo = "4";
                $tipo_hora = "2";
                break;

            case '9':
                $tipo_vehiculo = "5";
                $tipo_hora = "1";
                break;

            case '10':
                $tipo_vehiculo = "5";
                $tipo_hora = "2";
                break;
            
            default:
                $tipo_vehiculo = "0";
                $tipo_hora = "0";
                break;
        }

        $resultado = $this->Pieza_md_n->validarGestionFlotaEnReglaEsquema($request->cliente, $request->zonaA, $request->zonaB, $tipo_vehiculo, $tipo_hora);

        echo json_encode(["data" => $resultado]);
    }


    public function add()
    {
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $request = json_decode($stream_clean);

        //echo json_encode(["data" => $request]);

        $cliente_id            = $request->cliente_id;
        $departamento_id       = $request->departamento_id;
        $barcode_externo       = $request->barcode_externo;
        $destinatario          = $request->destinatario;
        $domicilio             = $request->domicilio;
        $codigo_postal_destino = $request->codigo_postal_destino;
        $codigo_postal_origen  = $request->codigo_postal_origen;
        $localidad_destino     = $request->localidad_destino;
        $localidad_origen      = $request->localidad_origen;
        $datos_varios          = $request->datos_varios;
        $datos_varios_1        = $request->datos_varios_1;
        $datos_varios_2        = $request->datos_varios_2;
        //$comprobante_id      = $request->comprobante_id;
        $descripcion_paquete   = $request->descripcion_paquete;
        $dimensiones           = $request->dimensiones;
        $peso                  = $request->peso;
        $bulto                 = $request->bulto;
        $palet                 = $request->palet;
        $metroCubico           = $request->metroCubico;
        $importe               = $request->importe;
        $zonaA                 = $request->zonaA;
        $zonaB                 = $request->zonaB;
        $servicio_base_id      = $request->servicio_base_id;
        $user_row = $this->ion_auth->user()->row();
        $monto_valor_declarado = $request->precioValorDeclarado;
        $monto_cobranza        = $request->precioCobranza;
        
        $warehouse             = $request->warehouse;
        $gestionFlota          = $request->gestionFlota;
        $cantidad_horas        = $request->cantidadHora;
        $tipo_vehiculo         = null;
        $tipo_hora             = null;
        $nombre_gestion_flota  = null; 

        //DATOS AGREGADOS DE DESTINO
        $pais_destino = $request->pais_destino;
        $provincia_destino = $request->provincia_destino;
        $piso = $request->piso;
        $departamento_oficina = $request->departamento_oficina;
        $esquina_1 = $request->esquina_1;
        $esquina_2 = $request->esquina_2;
        $observaciones_destino = $request->observaciones_destino;

        //GEOCODIFICACION
        $geocodificada = $request->geocodificada;
        $latitud = $request->latitud;
        $longitud = $request->longitud;

        if(!empty($gestionFlota)){

            switch ($gestionFlota) {
                case '1':
                    $tipo_vehiculo = "1";
                    $tipo_hora = "1";
                    $nombre_gestion_flota = "Moto hora comun";
                    break;
                
                case '2':
                    $tipo_vehiculo = "1";
                    $tipo_hora = "2";
                    $nombre_gestion_flota = "Moto hora especial";
                    break;
    
                case '3':
                    $tipo_vehiculo = "2";
                    $tipo_hora = "1";
                    $nombre_gestion_flota = "Auto hora comun";
                    break;
    
                case '4':
                    $tipo_vehiculo = "2";
                    $tipo_hora = "2";
                    $nombre_gestion_flota = "Auto hora especial";
                    break;
    
                case '5':
                    $tipo_vehiculo = "3";
                    $tipo_hora = "1";
                    $nombre_gestion_flota = "Camioneta hora comun";
                    break;
    
                case '6':
                    $tipo_vehiculo = "3";
                    $tipo_hora = "2";
                    $nombre_gestion_flota = "Camioneta hora especial";
                    break;
                
                case '7':
                    $tipo_vehiculo = "4";
                    $tipo_hora = "1";
                    $nombre_gestion_flota = "Furgon hora comun";
                    break;
    
                case '8':
                    $tipo_vehiculo = "4";
                    $tipo_hora = "2";
                    $nombre_gestion_flota = "Furgon hora especial";
                    break;
    
                case '9':
                    $tipo_vehiculo = "5";
                    $tipo_hora = "1";
                    $nombre_gestion_flota = "Camion hora comun";
                    break;
    
                case '10':
                    $tipo_vehiculo = "5";
                    $tipo_hora = "2";
                    $nombre_gestion_flota = "Camion hora especial";
                    break;
                
                default:
                    $tipo_vehiculo = null;
                    $tipo_hora = null;
                    $nombre_gestion_flota = null;
                    break;
            }

            $descripcion_paquete = "Gestion Flota";

        }
 
        //Traigo un comprobante disponible
        $comprobante_ingreso_generado = ComprobanteGenerado::whereEstado(ComprobanteGenerado::ESTADO_DISPONIBLE)->first();

        //Creo un comprobante de ingreso
        $comprobante_ingreso_id = $this->crearComprobanteIngreso($comprobante_ingreso_generado, $user_row, $cliente_id, $departamento_id);
        $comprobante_ingreso = Comprobante::whereId($comprobante_ingreso_id)->first();
        //Creo el servicio de paqueteria para ese comprobante
        $comprobante_ingreso_servicio_id = $this->crearComprobanteIngresoServicio($comprobante_ingreso);
        $comprobante_ingreso_servicio = ComprobanteServicio::whereId($comprobante_ingreso_servicio_id)->first();
        $servicio  = Servicio::whereId($comprobante_ingreso_servicio->servicio_id)->first();
        //echo $cantidad[$i]."   -   ";
        $cantidad_modficado = 1;
        date_default_timezone_set('America/Argentina/Tucuman');
        $array_piezas     = array(
            'usuario_id'             => $this->usuario->id,
            'servicio_id'            => $comprobante_ingreso_servicio->id,
            'tipo_id'                => $servicio->acuse == 1 ? PIEZA_TIPO_NORMAL : PIEZA_TIPO_SIMPLE,
            'sucursal_id'            => $comprobante_ingreso->sucursal_id, //Modificado las piezas cargadas deberian tener la sucursal del CI $this->usuario->sucursal_id,
            'estado_id'              => Pieza::ESTADO_EN_GESTION,
            'cantidad'               => $cantidad_modficado,
            'comprobante_ingreso_id' => $comprobante_ingreso->id,
            'barcode_externo'        => $barcode_externo,
            'destinatario'           => $destinatario,
            'domicilio'              => $domicilio,
            'codigo_postal_destino'  => $codigo_postal_destino,
            'codigo_postal_origen'   => $codigo_postal_origen,
            'localidad_destino'      => $localidad_destino,
            'localidad_origen'       => $localidad_origen,
            'datos_varios'           => $datos_varios,
            'datos_varios_1'         => $datos_varios_1,
            'datos_varios_2'         => $datos_varios_2,
            'create_user_id'         => $user_row->id,
            'create'                 => date("Y-m-d H:i:s"),
            'update'                 => date("Y-m-d H:i:s"),
            //NUEVO
            'pais_destino'           => $pais_destino,
            'provincia_destino'      => $provincia_destino,
            'piso'                   => $piso,
            'departamento_oficina'   => $departamento_oficina,
            'esquina_1'              => $esquina_1,
            'esquina_2'              => $esquina_2,
            'observaciones_destino'  => $observaciones_destino
        );

        $this->codegen_model->add('flash_piezas', $array_piezas);

        //Creo el registro en Piezas_paquetes
        $piezas_insertada = $this->codegen_model->row('flash_piezas', '*', 'id = ' . $this->db->insert_id());
        $array_piezas_paquetes     = array(
            'pieza_id' => $piezas_insertada->id,
            'descripcion_paquete' => $descripcion_paquete,
            'dimensiones' => $dimensiones,
            'peso' => $peso,
            'bultos' => $bulto,
            'palets' => $palet,
            'metro_cubico' => $metroCubico,
            //VALIDAR CON LA TABLA DE SANTIAGO
            'monto_valor_declarado' => $monto_valor_declarado, //UTILIZADO PARA REALIZAR EL CALCULO DEL VALOR DECLARADO
            'monto_cobranza' => $monto_cobranza, //UTILIZADO PARA REALIZAR EL CALCULO DEL VALOR DECLARADO

            //WAREHOUSE Y GESTION DE FLOTA
            'warehouse' => $warehouse,
            'tipo_vehiculo' => $tipo_vehiculo,
            'tipo_hora' => $tipo_hora,
            'cantidad_hora' => $cantidad_horas,
            'nombre_gestion_flota' => $nombre_gestion_flota,

            'zonaA' => $zonaA,
            'zonaB' => $zonaB,
            
            //'dias_entrega' => '',
            'create' => date("Y-m-d H:i:s"),
            'update' => date("Y-m-d H:i:s"),
            'create_user_id' => $user_row->id,
        );

        $this->codegen_model->add('flash_piezas_paquetes', $array_piezas_paquetes);

        //INSERT GEOCODIFICACION
        if($geocodificada == "1"){
            $array_geocodificacion = array(
                'pieza_id'    => $piezas_insertada->id,
                'latitud'     => $latitud,
                'longitud'    => $longitud,
            );

            $this->codegen_model->add('flash_piezas_coordenadas', $array_geocodificacion);
        }
        //END INSERT GEOCODIFICACION

        if ($servicio->acuse == 1) {
            //Grabo el estado en novedades
            $array_piezas_aux[]     = array(
                'pieza_id'              => $piezas_insertada->id,
                'cantidad'              => 1,
                'usuario_id'            => $this->usuario->id,
                'estado_actual_id'      => Pieza::ESTADO_EN_GESTION,
                'estado_nuevo_id'       => Pieza::ESTADO_EN_GESTION,
                'create'                => date("Y-m-d H:i:s"),
                'update'                => date("Y-m-d H:i:s"),
                'create_user_id'        =>  $this->usuario->id,
                'update_user_id'         => $this->usuario->id,
            );

            $array_piezas_tracking_aux[]     = array(
                'pieza_id'              => $piezas_insertada->id,
                'usuario_id'            => $this->usuario->id,
                'estado_id'             => Pieza::ESTADO_EN_GESTION,
                'create'                => date("Y-m-d H:i:s"),
                'update'                => date("Y-m-d H:i:s"),
                'create_user_id'        =>  $this->usuario->id,
                'update_user_id'         => $this->usuario->id,
            );

            if (isset($array_piezas_aux)) {
                $this->db->insert_batch('flash_piezas_novedades', $array_piezas_aux);
                $this->db->insert_batch('flash_piezas_tracking', $array_piezas_tracking_aux);
                unset($array_piezas_aux);
                unset($array_piezas_tracking_aux);

                //Pongo el barcode como una pieza NORMAL
                $sql = "UPDATE flash_piezas p
                SET barcode = CONCAT( REPEAT( '0', 6 - LENGTH( p.id) ) , p.id)
                WHERE p.id = " . $piezas_insertada->id;

                $this->db->query($sql);
            }
        }
        //     echo($this->db->last_query());die;

        //AUDITORIA
        $data     = array(
            'user_id'     => $user_row->id,
            'categoria'   => 'PIEZAS_PAQUETES',
            'descripcion' => 'Comprobante: ' . $comprobante_ingreso->numero . '. Agregar Piezas_paquetes.',
            'origen'      => '',
            'destino'     => '',
            'create_user_id' => $this->usuario->id,
            'update_user_id' => $this->usuario->id,
        );
        $this->codegen_model->add('users_log', $data);
        //END AUDITORIA

        $sql = "UPDATE flash_piezas p
                SET barcode = CONCAT( REPEAT( '0', 6 - LENGTH( p.id) ) , p.id)
            WHERE comprobante_ingreso_id = " . $comprobante_ingreso->id . " AND servicio_id = " . $servicio->id;
        $this->db->query($sql);
        unset($array_piezas);

        $cliente = Cliente::whereId($cliente_id)->first();
        
        //$this->getPaqueteriaXCliente($cliente->id, $cliente->nombre);
        echo json_encode(["status" => 201]);

    }





    public function crearComprobanteIngreso($comprobante_ingreso_generado, $user, $cliente_id, $departamento_id)
    {
        $etiqueta = $this->codegen_model->row('flash_comprobantes_ingresos_generados', '*', 'id = ' . $comprobante_ingreso_generado->id);
        $talonario = $this->codegen_model->row('flash_piezas_talonarios', '*', 'id = ' . $etiqueta->talonario_id);

        $data = array(
            'talonario_id' => $talonario->id,
            'empresa_id' => null,
            'sucursal_id' => $user->sucursal_id,
            'cliente_id' => $cliente_id,
            'departamento_id' => $departamento_id,
            'numero' => $comprobante_ingreso_generado->numero,
            'cantidad' => 1, //Siempre creamos 1 comp. con un Serv. con 1 pieza
            'estado' => 0,
            'estado_liquidacion'     => 0,
            'fecha_pedido' => date('Y-m-d')
        );
        $comprobante = $this->codegen_model->add('flash_comprobantes_ingresos', $data);
        $data = array('etiquetas_disponibles' => $talonario->etiquetas_disponibles - 1);
        $this->codegen_model->edit('flash_piezas_talonarios', $data, 'id', $talonario->id);

        /* Auditoria */
        $user_row = $this->ion_auth->user()->row();
        $data = array(
            'user_id' => $user_row->id,
            'categoria' => 'COMPROBANTE PARA PAQUETE',
            'descripcion' => 'Nuevo Comprobante: ' . $comprobante,
            'origen' => '',
            'destino' => '',
            'create_user_id' => $user_row->id,
            'update_user_id' => $this->usuario->id,
        );
        $this->codegen_model->add('users_log', $data);
        /* END: Auditoria */
        return $comprobante;
    }






    public function crearComprobanteIngresoServicio($comprobante_ingreso)
    {
        $data = array(
            'comprobante_ingreso_id' => $comprobante_ingreso->id,
            'servicio_id' => 2, //Paqueteria generica
            'cantidad' => 1, //Siempre es un paquete
            'disponible' => 0, //Porque ya se agrega la unica pieza disponible a este servicio
            'remito' => 0
        );

        $comprobante_ingreso_servicio = $this->codegen_model->add('flash_comprobantes_ingresos_servicios', $data);

        $comprobante_generado = ComprobanteGenerado::whereNumero($comprobante_ingreso->numero)->first();

        $query_udpate_comprobante_generado = "UPDATE flash_comprobantes_ingresos_generados 
                                                SET estado = " . ComprobanteGenerado::ESTADO_USADO . "
                                              WHERE id = " . $comprobante_generado->id;

        $this->db->query($query_udpate_comprobante_generado);

        return $comprobante_ingreso_servicio;
    }
    
}

