<?php


use Illuminate\Database\Capsule\Manager as DB;

class Piezas_paquetes extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    function index(){
            $vista_interna = array(
                    'permisos_efectivos' => $this->permisos,
                    'paquetes' => [],
//                    'clientes' => $this->codegen_model->get('flash_clientes','*',''),
//                    'departamentos' => $this->codegen_model->get('flash_clientes_departamentos','*',''),
//                    'sucursales' => $this->codegen_model->get('flash_sucursales','*',''),
                    'clientes' => Cliente::select('id', DB::raw('CONCAT(nombre," (",nombre_fantasia,")") as name'))->where('cliente_estado_id',Cliente::ESTADO_ACTIVO)->get(),
                    'comprobante_servicios' => array()
            );

            $vista_externa = array(			
                    'title' => ucwords("Piezas Paquetes"),
                    'contenido_main' => $this->load->view('components/piezas/piezas_paquetes/piezas_paquetes_add', $vista_interna, true)
            );		

            $this->load->view('template/backend', $vista_externa);
    }
//
//    public function filtro()
//    {
//        $empresa_id      = $this->input->post('empresa');
//        $cliente_id      = $this->input->post('cliente_id');
//        $departamento_id = $this->input->post('dpto');
//
//        $query = Comprobante::orderBy('create', 'desc');
//
//        if ($this->is_user()) {
//            $query->where('sucursal_id', $this->usuario->sucursal_id);
//        }
//
//        if ($empresa_id) {
//            $query->where('empresa_id', $empresa_id);
//        }
//
//        if ($cliente_id) {
//            $query->where('cliente_id', $cliente_id);
//        }
//
//        if ($departamento_id) {
//            $query->where('departamento_id', $departamento_id);
//        }
//
//        $comprobantes = $query->get();
////echo($this->db->last_query());die;
//
//        $this->data([
//            'comprobantes'    => $comprobantes,
//            'empresa_id'      => $empresa_id ? $empresa_id : false, //Empresa::whereDefault(1)->first()->id,
//            'cliente_id'      => $cliente_id ? $cliente_id : false,
//            'departamento_id' => $departamento_id ? $departamento_id : false,
//            'empresas'        => Empresa::all(),
//            'clientes'        => Cliente::where('cliente_estado_id', Cliente::ESTADO_ACTIVO)->get(),
//            'departamentos'   => ClienteDepartamento::all(),
//        ]);
//
//        $this->title('comprobantes de ingresos');
//        $this->view = 'components/piezas/comprobantes_ingresos/comprobantes_ingresos_list';
//        $this->template('template/backend');
//    }
//
//    public function piezas($id = null, $flag = null)
//    {      // var_dump($_POST);die;
//        set_time_limit(1800);
//        if ($flag == null) {
//            $flag = $this->input->post('flag');
//        }
//
//        if ($id != null && $this->input->post('numero') == '') {
//            if ($this->is_user()):
//                $comprobante = Comprobante::where('sucursal_id', $this->usuario->sucursal_id)->findOrFail($id);
//            else:
//                $comprobante = Comprobante::findOrFail($id);
//            endif;
//        } else {
//            $numero = trim($this->input->post('numero'));
//            if ($this->is_user()):
//                $comprobante = Comprobante::where('sucursal_id', $this->usuario->sucursal_id)->whereNumero($numero)->first();
//            else:
//                $comprobante = Comprobante::whereNumero($numero)->first();
//            endif;
//
//        }
//
//        if ($comprobante != null) {
//            if ($flag == "piezas") {
//                $comprobante_servicios = $this->flash_md->getPiezasPorServiciosPorComprobante($comprobante->id);
//                usort($comprobante_servicios, function ($a, $b) {
//                    return strcmp($b->creacion, $a->creacion);
//                });
//            }
//        } else {
//            $this->session->set_flashdata('registro', $numero);
//            redirect(base_url('piezas/piezas/alta_piezas_masivas'));
//        }
//
//        $this->data([
//            'comprobante'           => $comprobante,
//            'comprobante_servicios' => $comprobante_servicios, //ComprobanteServicio::whereComprobanteIngresoId($comprobante->id)->get(),
//            'servicios'             => $this->flash_md->getServiciosPorComprobante($comprobante->id),
//            'estados'               => PiezaEstado::all(),
//            'tipos'                 => PiezaTipo::all(),
//            'cliente'               => Cliente::whereId($comprobante->cliente_id)->first(),
//        ]);
//
//        if ($flag == 'piezas') {
//            $this->title('comprobante ' . $comprobante->numero . ' - alta de piezas');
//            $this->view = 'components/piezas/comprobantes_ingresos/comprobantes_ingresos_piezas';
//        }
//        if ($flag == 'piezas_masivas') {
//            $this->title('comprobante ' . $comprobante->numero . ' - alta masiva de piezas');
//            $this->view = 'components/piezas/comprobantes_ingresos/comprobantes_ingresos_piezas_masivas';
//        }
//        $this->template('template/backend');
//    }
//
    //obtener el token de seguridad de mis envios
   function token(){
        $api_keys = array(
        'apikey' => 'Ff1X1AgNgmQZsl4n9SdBoof7SRXrwA20xGXMMQDPioHXLVUPJX',
        'secretkey' => 'E0QN0FAVAHE4CKZ8'
        );
        $payload = json_encode($api_keys);
        $access_token = null;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($curl, CURLOPT_URL,
        'https://misenvios.com.ar/servicios/api/Tokens');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($payload))
        );
        $curl_res = curl_exec($curl);

        curl_close($curl);
        if( $curl_res ){
        $res = json_decode($curl_res, true);
        if( isset($res["isError"]) && !$res["isError"])
        $access_token = $res["result"]["access_token"];
        }
        return $access_token;
        }
        //termina la funcion del token

        //funcion de solicitud de la api mis envios
    function solicitud($barcode_externo,$domicilio,$codigo_postal,$localidad,$destinatario,$bulto,$dimensiones,$peso){
    
         $numero=''; 
        $calle='';
        $domicilio=strrev($domicilio);
        $n =strlen($domicilio);
        For($i=0;$i<$n;$i++)
        {
            $val= $domicilio[$i];
            if(is_numeric($domicilio[$i]))
            {$numero=$numero.$val;}
            else
            {$calle=$calle.$val;}
        }

        $numero=(int)(strrev($numero));
        $calle=strrev($calle);

        
        $bulto               = (int)$bulto;

     
        $alto='';
        $ancho='';
        $profundidad='';
        $n =strlen($dimensiones);
        for($i=1;$i<=$n;$i++)
        
        {
           
            if($dimensiones[$i]=='X')
            {$dimensiones=explode('X', $dimensiones);
            break;}
            if($dimensiones[$i]=='x')
            {$dimensiones=explode('x', $dimensiones);
            break;}
    
        }
        $alto=$dimensiones[0];
        $ancho=$dimensiones[1];
        $profundidad=$dimensiones[2];

        $peso               = (int)$peso;

        $paramsolicitud = array(
        'identificadorExterno' => $barcode_externo ,
        'locationID' => 2122 ,
        'origen' => array(
        'calle' => '',
        'altura' => '',
        'esquina' => '',
        'esquina2' => '',
        'barrio' => '',
        'localidad' => '',
        'partido' => '',
        'provincia' => '',
        'codigoPostal' => '',
        'observacion' => '',
        'piso' => '',
        'depto' => ''
        ),
        'destino' => array(
        'calle' => $calle ,
        'altura' => $numero ,
        'esquina' => '',
        'esquina2' => '',
        'barrio' => '',
        'localidad' => $localidad ,
        'partido' => '',
        'provincia' => 'tucuman',
        'codigoPostal' => $codigo_postal ,
        'observacion' => '',
        'piso' => '',
        'depto' => ''
        ),
        'destinatario' => array(
        'nombre' => $destinatario ,
        'email' => 'sistemas@correoflash.com',
        'phone' => '3815600094',
        'dni' => '',
        'comentario' => ''
        ),
        'productos' => array(
        array(
        'descripcion' => 'Producto' ,
        'cantidad' => $bulto ,
        'alto' => $alto,
        'ancho' => $ancho,
        'profundidad' => $profundidad,
        'tiempo' =>1 ,
        'peso' => $peso
        )
        )
        );
        $token=Piezas_paquetes::token();
        $payload = json_encode($paramsolicitud);
        $solicitudID = null;
        $codigoSeguimiento = null;
        $fecha = null;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($curl, CURLOPT_URL,
        "https://misenvios.com.ar/servicios/api/Solicitud");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($payload),
        "Authorization: Bearer ".$token)
        );
        $curl_res = curl_exec($curl);
        curl_close($curl);

        if( $curl_res ){
        $res = json_decode($curl_res, true);
        if( isset($res["isError"]) && !$res["isError"]){
        $solicitudID = $res["result"]["solicitudID"];
        $codigoSeguimiento = 
        $res["result"]["codigoSeguimiento"];
        $fecha = $res["result"]["fecha"];
        }}
        echo "<script>console.log('" .$curl_res. "');</script>";
        echo "<script>console.log('" .'Token Verficaci���n: '.$token. "');</script>";
        echo "<script>console.log('" .'SolicitudID: '.$solicitudID. "');</script>";
        echo "<script>console.log('" .'Codigo Seguimiento: '.$codigoSeguimiento. "');</script>";
        echo "<script>console.log('" .'Fecha: '.$fecha. "');</script>";
        
        
        echo "<script>console.log('--------DATOS DE DESTINO-------')</script>";
        echo "<script>console.log('" .'Calle: '.$calle. "');</script>";
        echo "<script>console.log('" .'Numero: '.$numero. "');</script>";
        echo "<script>console.log('" .'Localidad: '.$localidad. "');</script>";
        echo "<script>console.log('" .'CP: '.$codigo_postal. "');</script>";
        
        echo "<script>console.log('--------DATOS PAQUETE-------')</script>";
        echo "<script>console.log('" .'peso: '.$peso. "');</script>";
        echo "<script>console.log('" .'alto: '.$alto. "');</script>";
        echo "<script>console.log('" .'ancho: '.$ancho. "');</script>";
        echo "<script>console.log('" .'profundidad: '.$profundidad. "');</script>";
         echo "<script>console.log('" .'cantidad: '.$bulto. "');</script>";
         
        }
        //termina la funci���n de solicitud de mis envios

    public function add()
    {
        $cliente_id         = $this->input->post('cliente_id');
        $departamento_id    = $this->input->post('departamento_id');
        $barcode_externo     = $this->input->post('barcode_externo');
        $destinatario        = $this->input->post('destinatario');
        $domicilio           = $this->input->post('domicilio');
        $codigo_postal       = $this->input->post('codigo_postal');
        $localidad           = $this->input->post('localidad');
        $datos_varios        = $this->input->post('datos_varios');
        $datos_varios_1      = $this->input->post('datos_varios_1');
        $datos_varios_2      = $this->input->post('datos_varios_2');
        //$comprobante_id      = $this->input->post('comprobante_id');
        $descripcion_paquete = $this->input->post('descripcion_paquete');
        $dimensiones         = $this->input->post('dimensiones');
        $peso                = $this->input->post('peso');
        $bulto               = $this->input->post('bulto');
        $servicio_base_id    = $this->input->post('servicio_base_id');
        $user_row = $this->ion_auth->user()->row();
        
        if($cliente_id == 625){
        Piezas_paquetes::solicitud($barcode_externo,$domicilio,$codigo_postal,$localidad,$destinatario,$bulto,$dimensiones,$peso);}
        
        // var_dump($_POST);die;
		if( !$departamento_id )
			if( $departamento = ClienteDepartamento::whereClienteId($cliente_id)->first() )
				$departamento_id = $departamento->id;
            
        //Traigo un comprobante disponible
        $comprobante_ingreso_generado = ComprobanteGenerado::whereEstado(ComprobanteGenerado::ESTADO_DISPONIBLE)->first();
        //var_dump($comprobante_ingreso_generado);die;
        // echo '<pre>' . var_export($comprobante_ingreso_generado, true) . '</pre>';
        
        //Creo un comprobante de ingreso
        $comprobante_ingreso_id = $this->crearComprobanteIngreso($comprobante_ingreso_generado,$user_row, $cliente_id, $departamento_id);
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
            'codigo_postal'          => $codigo_postal,
            'localidad'              => $localidad,
            'datos_varios'           => $datos_varios,
            'datos_varios_1'         => $datos_varios_1,
            'datos_varios_2'         => $datos_varios_2,
            'create_user_id'         => $user_row->id,
            'create'                 => date("Y-m-d H:i:s"),
            'update'                 => date("Y-m-d H:i:s"),
        );
        $this->codegen_model->add('flash_piezas',$array_piezas);
        //Creo el registro en Piezas_paquetes
        $piezas_insertada = $this->codegen_model->row('flash_piezas','*','id = '.$this->db->insert_id());
        $array_piezas_paquetes     = array(
                                'pieza_id' => $piezas_insertada->id,
                                'descripcion_paquete' => $descripcion_paquete,
                                'dimensiones' => $dimensiones,
                                'peso' => $peso,
                                'bultos' => $bulto,
                                'dias_entrega' => '',
                                'create' => date("Y-m-d H:i:s"),
                                'update' => date("Y-m-d H:i:s"),
                                'create_user_id' => $user_row->id,
                                );

        $this->codegen_model->add('flash_piezas_paquetes',$array_piezas_paquetes);
        if($servicio->acuse == 1){
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
            if (isset($array_piezas_aux)){
                //echo "ya para insertar";
                //var_dump($array_piezas_aux);
                $this->db->insert_batch('flash_piezas_novedades', $array_piezas_aux);
                $this->db->insert_batch('flash_piezas_tracking', $array_piezas_tracking_aux);
                unset($array_piezas_aux);
                unset($array_piezas_tracking_aux);

                //Pongo el barcode como una pieza NORMAL
                $sql = "UPDATE flash_piezas p
                SET barcode = CONCAT( REPEAT( '0', 6 - LENGTH( p.id) ) , p.id)
                WHERE p.id = ".$piezas_insertada->id;

                $this->db->query($sql);
            }  
          
        }
//     echo($this->db->last_query());die;

        /* Auditoria */

        $data     = array(
            'user_id'     => $user_row->id,
            'categoria'   => 'PIEZAS_PAQUETES',
            'descripcion' => 'Comprobante: ' . $comprobante_ingreso->numero . '. A単adir Piezas_paquetes.',
            'origen'      => '',
            'destino'     => '',
        );
        $this->codegen_model->add('users_log', $data);
        /* END: Auditoria */

        $sql = "UPDATE flash_piezas p
                SET barcode = CONCAT( REPEAT( '0', 6 - LENGTH( p.id) ) , p.id)
            WHERE comprobante_ingreso_id = " . $comprobante_ingreso->id . " AND servicio_id = " . $servicio->id;
        $this->db->query($sql);
        unset($array_piezas);
//        echo $comprobante_ingreso->numero;die;
//        $mensaje = "Comprobante Nro: ".$comprobante_ingreso->numero."<br/> Descripcion: ".$descripcion_paquete." <br/> Correspondiente al ID de pieza: ".$piezas_insertada->id. " cuya descripcion es: ";
//        $mensaje_descripcion = " Descripcion Paquete: ".$descripcion_paquete." <br/> Peso: ".$peso." <br/> Dimensiones: ".$dimensiones." <br/> Bultos: ".$bulto ;
//        $this->session->set_flashdata('registro', $comprobante_ingreso->numero);
//        $this->session->set_flashdata('mensaje', $mensaje);
//        $this->session->set_flashdata('mensaje_descripcion', $mensaje_descripcion);
        $cliente = Cliente::whereId($cliente_id)->first();
        $this->getPaqueteriaXCliente($cliente->id,$cliente->nombre);
    }
    
    public function crearComprobanteIngreso($comprobante_ingreso_generado,$user,$cliente_id, $departamento_id){
        $etiqueta = $this->codegen_model->row('flash_comprobantes_ingresos_generados', '*', 'id = '.$comprobante_ingreso_generado->id);
        $talonario = $this->codegen_model->row('flash_piezas_talonarios', '*', 'id = '.$etiqueta->talonario_id);
       
        $data = array(
            'talonario_id' => $talonario->id,
            'empresa_id' => null,
            'sucursal_id' => $user->sucursal_id,
            'cliente_id' => $cliente_id,
            'departamento_id' => $departamento_id,
            'numero' => $comprobante_ingreso_generado->numero,
            'cantidad' => 1,//Siempre creamos 1 comp. con un Serv. con 1 pieza
            'estado' => 0,
            'estado_liquidacion'     => 0,
            'fecha_pedido' => date('Y-m-d'),
            'create' => date("Y-m-d H:i:s"),
            'update' => date("Y-m-d H:i:s"),
        );
        $comprobante = $this->codegen_model->add('flash_comprobantes_ingresos',$data);
        $data = array('etiquetas_disponibles' => $talonario->etiquetas_disponibles - 1);
        $this->codegen_model->edit('flash_piezas_talonarios',$data,'id',$talonario->id);

        /* Auditoria */
        $user_row = $this->ion_auth->user()->row();
        $data = array(
            'user_id' => $user_row->id,
            'categoria' => 'COMPROBANTE PARA PAQUETE',
            'descripcion' => 'Nuevo Comprobante: ' . $comprobante,
            'origen' => '',
            'destino' => '',
        );
        $this->codegen_model->add('users_log',$data);
        /* END: Auditoria */
        return $comprobante;
    }
    
    public function crearComprobanteIngresoServicio($comprobante_ingreso){
        $data = array(
             'comprobante_ingreso_id' => $comprobante_ingreso->id,
             'servicio_id' => 2, //Paqueteria generica
             'cantidad' => 1, //Siempre es un paquete
             'disponible' => 0, //Porque ya se agrega la unica pieza disponible a este servicio
             'remito' => 0,
             'create' => date("Y-m-d H:i:s"),
             'update' => date("Y-m-d H:i:s"),
         );

         $comprobante_ingreso_servicio = $this->codegen_model->add('flash_comprobantes_ingresos_servicios',$data);

        $comprobante_generado = ComprobanteGenerado::whereNumero($comprobante_ingreso->numero)->first();

        $query_udpate_comprobante_generado = "UPDATE flash_comprobantes_ingresos_generados 
                                                SET estado = ".ComprobanteGenerado::ESTADO_USADO."
                                              WHERE id = ".$comprobante_generado->id;

         $this->db->query($query_udpate_comprobante_generado);

        return $comprobante_ingreso_servicio;
    
    }

    function getPaqueteriaXCliente($cliente_id = null, $cliente = null){
        $cliente_id = ($cliente_id != NULL)?$cliente_id:$this->input->post('list_cliente_id');
        $cliente = ($cliente != NULL)?$cliente:$this->input->post('list_cliente');
        $query = $this->db
                ->select(' pt.*')
                ->join('flash_paqueteria_tipos pt', 'c.flash_paqueteria_tipo_id = pt.id')
                ->where('c.id = '.$cliente_id)
                ->get('flash_clientes c');
//            echo $this->db->last_query();
        $query_paquetes_cliente = $this->db
                ->select(' ci.numero comprobante_ingreso, pp.descripcion_paquete, pp.peso, pp.dimensiones, pp.bultos , pp.pieza_id')
                ->join('flash_piezas p', 'ci.id = p.comprobante_ingreso_id')
                ->join('flash_comprobantes_ingresos_servicios cis', 'p.servicio_id = cis.id')
                ->join('flash_piezas_paquetes pp', 'p.id = pp.pieza_id')
                ->where('ci.cliente_id = '.$cliente_id)
                ->order_by('ci.create DESC')
                ->get('flash_comprobantes_ingresos ci');
        $paqueteria = $query->row();
        $paquetes = $query_paquetes_cliente->result();
       $vista_interna = array(
            'cliente_id'    => $cliente_id,
            'paqueteria_tipo'      => $paqueteria->tipo,
            'paquetes'      => $paquetes,
            'cliente'      => $cliente,
            'clientes' => Cliente::select('id', DB::raw('CONCAT(nombre," (",nombre_fantasia,")") as name'))->where('cliente_estado_id',Cliente::ESTADO_ACTIVO)->get(),
        );

        $vista_externa = array(			
                    'title' => ucwords("Paqueteria"),
                    'contenido_main' => $this->load->view('components/piezas/piezas_paquetes/piezas_paquetes_add', $vista_interna, true)
            );		

            $this->load->view('template/backend', $vista_externa);
    }
    
}

/* End of file comprobantes_ingresos.php */
/* Location: ./system/application/controllers/comprobantes_ingresos.php */
