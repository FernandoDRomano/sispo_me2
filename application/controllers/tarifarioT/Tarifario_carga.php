<?php
//Controlador de la GUI de Paqueteria, donde ingresa los valores y se calculan los montos
use Illuminate\Database\Capsule\Manager as DB;

class Tarifario_carga extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('tarifario_md');
    }


    public function index()
    {
        //  Realizo la consulta de todos los clientes con su servicio paqueteria        
        $query_lista_clientes_servicio = $this->db
            ->select('fc.id, fc.nombre, fs.nombre servicio')
            ->join('flash_clientes_precios_especiales fcps', 'fcps.cliente_id = fc.id', 'inner')
            ->join('flash_servicios fs', 'fcps.servicio_id = fs.id', 'inner')
            ->order_by('fc.nombre', 'asc')
            ->where('fs.id = 2')
            ->get('flash_clientes fc');

        // Compruebo si se encontraron Clientes //
        $lista_clientes_servicio = $query_lista_clientes_servicio->result();

        // Armo Array de la vista interna, asignandole Permisos, el Cliente
        $vista_interna = array(
            'permisos_efectivos'    => $this->permisos,
            'clientes'                => $lista_clientes_servicio,
        );

        // Cargo vista 
        $this->load->view('template/backend', array(
            'title'          => ucwords("Paqueteria"),
            'contenido_main' => $this->load->view('components/tarifario/carga', $vista_interna, true),
        ));
    }



    public function getTarifarios()
    {
        $idCliente = $this->input->get('cliente_id');
        $idServicio = $this->input->get('servicio');

        $zonasClienteQuery = "SELECT DISTINCT
            CONCAT(COALESCE(fstk.zonaA,''), COALESCE(fstb.zonaA,''),COALESCE(fstp.zonaA,'')) AS 'zonaAt',
            CONCAT(COALESCE(fstk.zonaB,''), COALESCE(fstb.zonaB,''),COALESCE(fstp.zonaB,'')) AS 'zonaBt'
            FROM
                flash_servicios_paqueteria fsp
            LEFT JOIN 
                flash_servicios_tarifas_kg fstk ON fstk.id  = fsp.tarifario_kg_id
            LEFT JOIN 
                flash_servicios_tarifas_bultos fstb ON fstb.id  = fsp.tarifario_bultos_id
            LEFT JOIN 
                flash_servicios_tarifas_palets fstp ON fstp.id  = fsp.tarifario_palets_id
            LEFT JOIN 
                flash_servicios_tarifas_cobranza fstc ON fstc.id  = fsp.tarifario_cobranza_id
            WHERE cliente_id = $idCliente";

        $zonasClienteQuery = $this->db->query($zonasClienteQuery);
        if ($zonasClienteQuery) {
            $zonasCliente = $zonasClienteQuery->result_array();
        }

        $result = [];
        $result['kg']  = $this->tarifario_md->getTarifasKgPorCliente($idCliente);
        $result['bultos'] = $this->tarifario_md->getTarifasBultosPorCliente($idCliente);
        $result['palets'] = $this->tarifario_md->getTarifasPaletsPorCliente($idCliente);
        $result['zonasA'] =  array_unique(array_column($zonasCliente, 'zonaAt'));
        $result['zonasB'] =  array_unique(array_column($zonasCliente, 'zonaBt'));

        $lista_zonas = $this->tarifario_md->getTarifasZonas();;

        foreach ($result['zonasA'] as $key => $zonaId) {
            foreach ($lista_zonas as $list) {
                if ($list->id_provincia_bandera == $zonaId) {
                    $result['zonasA'][$key] = ['id' => $zonaId, 'nombre' => "$list->provincia ($list->ubicacion)"];
                }
            }
        }
        foreach ($result['zonasB'] as $key => $zonaId) {
            foreach ($lista_zonas as $list) {
                if ($list->id_provincia_bandera == $zonaId) {
                    $result['zonasB'][$key] = ['id' => $zonaId, 'nombre' => "$list->provincia ($list->ubicacion)"];
                }
            }
        }

        /*  echo '<pre>';
        print_r($result);
        echo '</pre>';
        die; */
        echo json_encode($result);
        die;
    }

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
        $descripcion_paquete = $this->input->post('descripcion_paquete');
        //$comprobante_id      = $this->input->post('comprobante_id');
        $dimensiones         = $this->input->post('dimensiones');
        $peso                = $this->input->post('peso');
        $bulto               = $this->input->post('bulto');
        $palet               = $this->input->post('palet');
        $zonaA               = $this->input->post('zonaA');
        $zonaB               = $this->input->post('zonaB');
        $servicio_base_id    = $this->input->post('servicio_base_id');
        $user_row = $this->ion_auth->user()->row();
        //        var_dump($_POST);die;
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
            'create'                 => date("Y-m-d H:i:s")
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
            'zonaA' => $zonaA,
            'zonaB' => $zonaB,
            'dias_entrega' => '',
            'create' => date("Y-m-d"),
            'create_user_id' => $user_row->id,
        );

        $this->codegen_model->add('flash_piezas_paquetes', $array_piezas_paquetes);
        //                 echo($this->db->last_query());die;
        /* Auditoria */

        $data     = array(
            'user_id'     => $user_row->id,
            'categoria'   => 'PIEZAS_PAQUETES',
            'descripcion' => 'Comprobante: ' . $comprobante_ingreso->numero . '. Añadir Piezas_paquetes.',
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
        $this->getPaqueteriaXCliente($cliente->id, $cliente->nombre);
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






    function getPaqueteriaXCliente($cliente_id = null, $cliente = null)
    {
        $cliente_id = ($cliente_id != NULL) ? $cliente_id : $this->input->post('list_cliente_id');
        $cliente = ($cliente != NULL) ? $cliente : $this->input->post('list_cliente');
        $query = $this->db
            ->select(' pt.*')
            ->join('flash_paqueteria_tipos pt', 'c.flash_paqueteria_tipo_id = pt.id')
            ->where('c.id = ' . $cliente_id)
            ->get('flash_clientes c');
        //            echo $this->db->last_query();
        $query_paquetes_cliente = $this->db
            ->select(' ci.numero comprobante_ingreso, pp.*')
            ->join('flash_piezas p', 'ci.id = p.comprobante_ingreso_id')
            ->join('flash_comprobantes_ingresos_servicios cis', 'p.servicio_id = cis.id')
            ->join('flash_piezas_paquetes pp', 'p.id = pp.pieza_id')
            ->where('ci.cliente_id = ' . $cliente_id)
            ->order_by('ci.create DESC')
            ->get('flash_comprobantes_ingresos ci');
        $paqueteria = $query->row();
        $paquetes = $query_paquetes_cliente->result();
        $vista_interna = array(
            'cliente_id'    => $cliente_id,
            'paqueteria_tipo'      => $paqueteria->tipo,
            'paquetes'      => $paquetes,
            'cliente'      => $cliente,
            'clientes' => Cliente::select('id', DB::raw('CONCAT(nombre," (",nombre_fantasia,")") as name'))->where('cliente_estado_id', Cliente::ESTADO_ACTIVO)->get(),
        );

        $vista_externa = array(
            'title' => ucwords("Paqueteria"),
            'contenido_main' => $this->load->view('components/tarifario/carga', $vista_interna, true)
        );

        $this->load->view('template/backend', $vista_externa);
    }
}



/*
        SELECT
            cliente_id,
            GROUP_CONCAT(tarifario_kg_id) AS 'kg',
            GROUP_CONCAT(tarifario_bultos_id) AS 'bultos',
            GROUP_CONCAT(tarifario_cobranza_id) AS 'cobranza',
            GROUP_CONCAT(tarifario_palets_id) AS 'palets'
            FROM flash_servicios_paqueteria
            WHERE cliente_id = $idCliente
            GROUP BY cliente_id

            */


    /*
    public function index()
    {
        //  Realizo la consulta de todos los clientes con su servicio paqueteria        
        $query_lista_clientes_servicio = $this->db
            ->select('fc.id, fc.nombre, fs.nombre servicio')
            ->join('flash_clientes_precios_especiales fcps', 'fcps.cliente_id = fc.id', 'inner')
            ->join('flash_servicios fs', 'fcps.servicio_id = fs.id', 'inner')
            ->order_by('fc.nombre', 'asc')
            ->where('fs.id = 2')
            ->get('flash_clientes fc');

        // Compruebo si se encontraron Clientes //
        $lista_clientes_servicio = $query_lista_clientes_servicio->result();

        if ($lista_clientes_servicio) {
            $existenClientes = true;
        } else {
            $existenClientes = false;
        }

        $nombre_cliente = '';
        $nombre_tipo_calculo = 0;

        $msj = 'recien entra';

        if ($this->input->is_post()) {

            $tipo_calculo = $this->input->post('c_selectOpcionCalculo');

            $nombre_cliente = $this->input->post('c_combobox_clientes');
            $nombre_cliente_edit = '"' . str_replace('_', ' ', $nombre_cliente) . '"';
            $query_id_cliente = $this->codegen_model->row('flash_clientes', 'id', 'nombre = ' . $nombre_cliente_edit);
            $cliente_id = $query_id_cliente->id;


            $nombre_cliente = $this->codegen_model->get('flash_clientes', 'nombre', 'id = ' . $cliente_id);
            //VER
            $query_serv_asoc = 1;

            if (!empty($query_serv_asoc)) {

                $query_tarifarios_asociados = $this->codegen_model->get('flash_servicios_paqueteria', '*', 'cliente_id = ' . $cliente_id);

                if (!empty($query_tarifarios_asociados)) {

                    $tarifarios_asociados = $query_tarifarios_asociados;

                    $array_tarifas = [];

                    foreach ($tarifarios_asociados as $tarifa_ind) {
                        $tarifa = $tarifa_ind->tarifario_id;

                        $query_tarifa = $this->codegen_model->get('flash_servicios_paqueteria_tarifas', '*', 'id = ' . $tarifa);

                        if (!empty($query_tarifa)) {
                            $msj = 'llega3';
                        }

                        foreach ($query_tarifa as $tarifa) {

                            //swtich
                            //NOMBRE
                            $nombre_tipo_calculo = 2;

                            //switch 
                            //buscar en bultos

                            array_push($array_tarifas, $tarifa);
                        }
                    }
                } else {
                    $tarifarios_asociados = 'No se encontraron tarifas asociadas.';
                }
            }
        } else {
            $valor = '(No ingreso aun por POST)';
        }

        // Armo Array de la vista interna, asignandole Permisos, el Cliente obtenido y la bandera Data //
        $vista_interna = array(
            'permisos_efectivos'    => $this->permisos,
            'client'                => $lista_clientes_servicio,
            'data'                  => $existenClientes,
            'val'                   => $valor,
            'nombre_cliente'        => $nombre_cliente,
            'tarifas'               => $array_tarifas,
            'tipo_calculo'          => $nombre_tipo_calculo,
        );

        // Armo Array de la vista externa, agrego titulo y cargo la vista enviandole el Array de vista interna //
        $vista_externa = array(
            'title'          => ucwords("Paqueteria"),
            'contenido_main' => $this->load->view('components/tarifario/carga', $vista_interna, true),
        );

        // Cargo vista //
        $this->load->view('template/backend', $vista_externa);
    }
    */
