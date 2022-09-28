<?php

class Consulta_global_cliente extends CI_Controller
{

    private $permisos;

    public function __construct()
    {
        parent::__construct();
        $this->permisos = $this->permisos_lib->control();
        // load Pagination library
        $this->load->library('pagination');

        // load URL helper
        $this->load->helper('url');
    }

    public function index()
    {
        //OBTENGO EL ID DEL USUARIO AUTENTICADO, QUE ESTA RELACIONADO DIRECTAMENTE CON EL CLIENTE
        $user = $this->ion_auth->user()->row();
        $user_id = $user->id;
        $mostrar = false;

        ini_set('memory_limit', '-1');
        $vista_interna = array(
            'permisos_efectivos' => $this->permisos,
        );

        $fecha_ingreso       = null; //$this->input->post('fecha_ingreso');
        if (!is_null($this->input->post('pieza_id'))) {
            $this->session->set_userdata('filtroConsultaGlobalPiezaId', $this->input->post('pieza_id'));
            $pieza_id             = $this->input->post('pieza_id');
        } else {
            $pieza_id = $this->session->userdata('filtroConsultaGlobalPiezaId');
        }
        if (!is_null($this->input->post('barra_externa'))) {
            $this->session->set_userdata('filtroConsultaGlobalBarraExterna', $this->input->post('barra_externa'));
            $barra_externa             = $this->input->post('barra_externa');
        } else {
            $barra_externa = $this->session->userdata('filtroConsultaGlobalBarraExterna');
        }
        if (!is_null($this->input->post('comprobante'))) {
            $this->session->set_userdata('filtroConsultaGlobalComprobante', $this->input->post('comprobante'));
            $comprobante             = $this->input->post('comprobante');
        } else {
            $comprobante = $this->session->userdata('filtroConsultaGlobalComprobante');
        }

        /*
        if (!is_null($this->input->post('cliente'))) {
            $this->session->set_userdata('filtroConsultaGlobalCliente', $this->input->post('cliente'));
            $cliente             = $this->input->post('cliente');
        } else {
            $cliente = $this->session->userdata('filtroConsultaGlobalCliente');
        }
        */
        $cliente = $user_id;

        if (!is_null($this->input->post('servicio'))) {
            $this->session->set_userdata('filtroConsultaGlobalServicio', $this->input->post('servicio'));
            $servicio             = $this->input->post('servicio');
        } else {
            $servicio = $this->session->userdata('filtroConsultaGlobalServicio');
        }
        if (!is_null($this->input->post('cartero'))) {
            $this->session->set_userdata('filtroConsultaGlobalCartero', $this->input->post('cartero'));
            $cartero             = $this->input->post('cartero');
        } else {
            $cartero = $this->session->userdata('filtroConsultaGlobalCartero');
        }
        if (!is_null($this->input->post('hoja_ruta_id'))) {
            $this->session->set_userdata('filtroConsultaGlobalHojaRutaId', $this->input->post('hoja_ruta_id'));
            $hoja_ruta_id = $this->input->post('hoja_ruta_id');
        } else {
            $hoja_ruta_id = $this->session->userdata('filtroConsultaGlobalHojaRutaId');
        }
        if (!is_null($this->input->post('despacho_id'))) {
            $this->session->set_userdata('filtroConsultaGlobalDespachoId', $this->input->post('despacho_id'));
            $despacho_id = $this->input->post('despacho_id');
        } else {
            $despacho_id = $this->session->userdata('filtroConsultaGlobalDespachoId');
        }
        if (!is_null($this->input->post('sucursal'))) {
            $this->session->set_userdata('filtroConsultaGlobalSucursal', $this->input->post('sucursal'));
            $sucursal             = $this->input->post('sucursal');
        } else {
            $sucursal = $this->session->userdata('filtroConsultaGlobalSucursal');
        }
        if (!is_null($this->input->post('estado'))) {
            $this->session->set_userdata('filtroConsultaGlobalEstado', $this->input->post('estado'));
            $estado = $this->input->post('estado');
        } else {
            $estado = $this->session->userdata('filtroConsultaGlobalEstado');
        }
        if (!is_null($this->input->post('destinatario'))) {
            $this->session->set_userdata('filtroConsultaGlobalDestinatario', $this->input->post('destinatario'));
            $destinatario = $this->input->post('destinatario');
        } else {
            $destinatario = $this->session->userdata('filtroConsultaGlobalDestinatario');
        }
        if (!is_null($this->input->post('domicilio'))) {
            $this->session->set_userdata('filtroConsultaGlobalDomicilio', $this->input->post('domicilio'));
            $domicilio = $this->input->post('domicilio');
        } else {
            $domicilio = $this->session->userdata('filtroConsultaGlobalDomicilio');
        }
        if (!is_null($this->input->post('codigo_postal'))) {
            $this->session->set_userdata('filtroConsultaGlobalCodigoPostal', $this->input->post('codigo_postal'));
            $codigo_postal = $this->input->post('codigo_postal');
        } else {
            $codigo_postal = $this->session->userdata('filtroConsultaGlobalCodigoPostal');
        }
        if (!is_null($this->input->post('localidad'))) {
            $this->session->set_userdata('filtroConsultaGlobalLocalidad', $this->input->post('localidad'));
            $localidad = $this->input->post('localidad');
        } else {
            $localidad = $this->session->userdata('filtroConsultaGlobalLocalidad');
        }
        if (!is_null($this->input->post('fecha_cambio_estado'))) {
            $this->session->set_userdata('filtroConsultaGlobalFechaCambioEstado', $this->input->post('fecha_cambio_estado'));
            $fecha_cambio_estado = $this->input->post('fecha_cambio_estado');
        } else {
            $fecha_cambio_estado = $this->session->userdata('filtroConsultaGlobalFechaCambioEstado');
        }
        $visitas             = null; //$this->input->post('visitas');
        if (!is_null($this->input->post('rendicion_id'))) {
            $this->session->set_userdata('filtroConsultaGlobalRendicionId', $this->input->post('rendicion_id'));
            $rendicion_id = $this->input->post('rendicion_id');
        } else {
            $rendicion_id = $this->session->userdata('filtroConsultaGlobalRendicionId');
        }
        if (!is_null($this->input->post('recibio'))) {
            $this->session->set_userdata('filtroConsultaGlobalRecibio', $this->input->post('recibio'));
            $recibio = $this->input->post('recibio');
        } else {
            $recibio = $this->session->userdata('filtroConsultaGlobalRecibio');
        }

        if (!is_null($this->input->post('documento'))) {
            $this->session->set_userdata('filtroConsultaGlobalDocumento', $this->input->post('documento'));
            $documento = $this->input->post('documento');
        } else {
            $documento = $this->session->userdata('filtroConsultaGlobalDocumento');
        }
        if (!is_null($this->input->post('datos_varios_1'))) {
            $this->session->set_userdata('filtroConsultaGlobalDatosVarios1', $this->input->post('datos_varios_1'));
            $datos_varios_1 = $this->input->post('datos_varios_1');
        } else {
            $datos_varios_1 = $this->session->userdata('filtroConsultaGlobalDatosVarios1');
        }
        if (!is_null($this->input->post('datos_varios_2'))) {
            $this->session->set_userdata('filtroConsultaGlobalDatosVarios2', $this->input->post('datos_varios_2'));
            $datos_varios_2 = $this->input->post('datos_varios_2');
        } else {
            $datos_varios_2 = $this->session->userdata('filtroConsultaGlobalDatosVarios2');
        }
        if (!is_null($this->input->post('datos_varios_3'))) {
            $this->session->set_userdata('filtroConsultaGlobalDatosVarios3', $this->input->post('datos_varios_3'));
            $datos_varios_3 = $this->input->post('datos_varios_3');
        } else {
            $datos_varios_3 = $this->session->userdata('filtroConsultaGlobalDatosVarios3');
        }

        $vinculo             = null; //$this->input->post('vinculo');
        //$datos_varios_1      = $this->input->post('datos_varios_1');
        //$datos_varios_2      = $this->input->post('datos_varios_2');
        //$datos_varios_3      = $this->input->post('datos_varios_3');
        $datos_varios_4      = null; //$this->input->post('datos_varios_3');

        $fecha_pieza_desde = null;
        $fecha_pieza_hasta = null;
        if (!is_null($this->input->post('fecha_pieza_desde'))) {
            $this->session->set_userdata('filtroConsultaGlobalFechaPiezaDesde', $this->input->post('fecha_pieza_desde'));
            $fecha_pieza_desde = $this->input->post('fecha_pieza_desde');
        } else {
            $fecha_pieza_desde = $this->session->userdata('filtroConsultaGlobalFechaPiezaDesde');
        }

        if (!is_null($this->input->post('fecha_pieza_hasta'))) {
            $this->session->set_userdata('filtroConsultaGlobalFechaPiezaHasta', $this->input->post('fecha_pieza_hasta'));
            $fecha_pieza_hasta = $this->input->post('fecha_pieza_hasta');
        } else {
            $fecha_pieza_hasta = $this->session->userdata('filtroConsultaGlobalFechaPiezaHasta');
        }

        if (!is_null($this->input->post('procesar'))) {
            $this->session->set_userdata('filtroConsultaGlobalProcesar', $this->input->post('procesar'));
            $procesar = $this->input->post('procesar');
        } else {
            $procesar = $this->session->userdata('filtroConsultaGlobalProcesar');
        }

        if ($procesar != '1') {
            $vista_interna["time_total"] = "Proceso aún no realizado. Faltan aplicar filtros.";
            $vista_interna["results"] = null;
        } else {
            // load db and model
            $this->load->database();

            $time_start = microtime(true);

            // init params
            $params = array();
            $limit_per_page = 50;
            $start_index = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

            $total_records = $this->flash_md->getConsultasGlobalesTotalCliente(
                $fecha_ingreso,
                $pieza_id,
                $barra_externa,
                $comprobante,
                $cliente,
                $servicio,
                $cartero,
                $hoja_ruta_id,
                $despacho_id,
                $sucursal,
                $estado,
                $destinatario,
                $domicilio,
                $codigo_postal,
                $localidad,
                $fecha_cambio_estado,
                $visitas,
                $rendicion_id,
                $recibio,
                $documento,
                $vinculo,
                $datos_varios_1,
                $datos_varios_2,
                $datos_varios_3,
                $datos_varios_4,
                $fecha_pieza_desde,
                $fecha_pieza_hasta
            );
            //var_dump(total_records);die;
            if ($total_records > 0) {
                // get current page records
                $vista_interna["results"] =  $this->flash_md->getConsultasGlobalesPaginadoCliente(
                    $fecha_ingreso,
                    $pieza_id,
                    $barra_externa,
                    $comprobante,
                    $cliente,
                    $servicio,
                    $cartero,
                    $hoja_ruta_id,
                    $despacho_id,
                    $sucursal,
                    $estado,
                    $destinatario,
                    $domicilio,
                    $codigo_postal,
                    $localidad,
                    $fecha_cambio_estado,
                    $visitas,
                    $rendicion_id,
                    $recibio,
                    $documento,
                    $vinculo,
                    $datos_varios_1,
                    $datos_varios_2,
                    $datos_varios_3,
                    $datos_varios_4,
                    $fecha_pieza_desde,
                    $fecha_pieza_hasta,
                    $limit_per_page,
                    $start_index
                );
                $i = 0;
                foreach ($vista_interna["results"] as $value) {
                    $query_hdr_actual = "SELECT sp.hoja_ruta_id 
				FROM flash_subpiezas sp
				WHERE sp.id = (SELECT MAX(sp1.id) FROM flash_subpiezas sp1 WHERE sp1.pieza_id = $value->pieza_id)"; //(hrd actual)
                    $query_despacho_actual = "select pd.despacho_id 
				FROM flash_piezas_despacho_piezas pd 
				WHERE pd.id = (SELECT MAX(pd3.id) FROM flash_piezas_despacho_piezas pd3 where pd3.pieza_id = $value->pieza_id)"; //(despacho actual)

                    $result_hdr = $this->db->query($query_hdr_actual)->row();
                    $result_despacho = $this->db->query($query_despacho_actual)->row();
                    $cartero = "";
                    if ($result_hdr != null && $result_hdr != "") {
                        $query_cartero_actual = "SELECT sc.apellido_nombre cartero
							FROM flash_hojas_rutas hdr
							INNER JOIN flash_sucursales_carteros sc ON sc.id = hdr.cartero_id
							WHERE hdr.id = $result_hdr->hoja_ruta_id"; //(cartero actual)
                        //echo $query_cartero_actual ;die;
                        $result_cartero_actual = $this->db->query($query_cartero_actual)->row();
                        $cartero = $result_cartero_actual->cartero;
                    }

                    $results[] = array(
                        'fecha_ingreso' => $value->fecha_ingreso,
                        'pieza_id' => $value->pieza_id,
                        'descripcion_paquete' => $value->descripcion_paquete,
                        'barcode_externo' => $value->barcode_externo,
                        'comprobante' => $value->comprobante,
                        'cliente' => $value->cliente,
                        'servicio' => $value->servicio,
                        'cartero' => $cartero,
                        'hoja_ruta_id' => $result_hdr->hoja_ruta_id,
                        'despacho_id' => $result_despacho->despacho_id,
                        'sucursal' => $value->sucursal,
                        'destinatario' => $value->destinatario,
                        'domicilio' => $value->domicilio,
                        'codigo_postal' => $value->codigo_postal,
                        'localidad' => $value->localidad,
                        'fecha_cambio_estado' => $value->fecha_cambio_estado,
                        'estado_actual' => $value->estado_actual,
                        'rendicion_id' => $value->rendicion_id,
                        'recibio' => $value->recibio,
                        'documento' => $value->documento,
                        'vinculo' => $value->vinculo,
                        'datos_varios' => $value->datos_varios,
                        'datos_varios_1' => $value->datos_varios_1,
                        'datos_varios_2' => $value->datos_varios_2,
                        'datos_varios_3' => $value->datos_varios_3,
                        'fecha_pieza_desde' => $fecha_pieza_desde,
                        'fecha_pieza_hasta' => $fecha_pieza_hasta,
                        'mail_dest' => $value->mail_dest,
                        'celular_dest' => $value->celular_dest,
                        'remito' => $value->remito,
                    );
                    //$i++;

                }
                $vista_interna["results"] = $results;
                

                //var_dump ($vista_interna["results"]);die;
                $config['base_url'] = base_url() . 'piezas/consulta_global/index';
                $config['total_rows'] = $total_records;
                $config['per_page'] = $limit_per_page;
                $config["uri_segment"] = 4;
                $config['num_links'] = 20;
                $config['page_query_string'] = FALSE;
                $config['full_tag_open'] = "<ul class='pagination'>";
                $config['full_tag_close'] = "</ul>";
                $config['num_tag_open'] = '<li>';
                $config['num_tag_close'] = '</li>';
                $config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
                $config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
                $config['next_tag_open'] = "<li>";
                $config['next_tagl_close'] = "</li>";
                $config['prev_tag_open'] = "<li>";
                $config['prev_tagl_close'] = "</li>";
                $config['first_tag_open'] = "<li>";
                $config['first_tagl_close'] = "</li>";
                $config['last_tag_open'] = "<li>";
                $config['last_tagl_close'] = "</li>";

                $this->pagination->initialize($config);

                // build paging links
                $vista_interna["links"] = $this->pagination->create_links();
            }else{
                $mostrar = true;
            }
            
            $time_end = microtime(true);
            $time_total = number_format($time_end - $time_start, 2);

            $vista_interna["time_total"] = "Proceso ejecutado en $time_total seg. Total de registro $total_records.";
        }

        $vista_interna["mostrar"] = $mostrar;

        $vista_externa = array(
            'title'          => ucwords("Consulta de Piezas"),
            'contenido_main' => $this->load->view('components/piezas/consultas_globales/consulta_global_para_cliente', $vista_interna, true),
        );

        $this->load->view('template/backend', $vista_externa);
    }

    public function exportarAExcel()
    {
        //OBTENGO EL ID DEL USUARIO AUTENTICADO, QUE ESTA RELACIONADO DIRECTAMENTE CON EL CLIENTE
        $user = $this->ion_auth->user()->row();
        $user_id = $user->id;

        ini_set('memory_limit', '-1');
        ob_start();
        $fecha_ingreso       = null; //$this->input->post('fecha_ingreso');
        $pieza_id            = $this->session->userdata('filtroConsultaGlobalPiezaId');
        $barra_externa       = $this->session->userdata('filtroConsultaGlobalBarraExterna');
        $comprobante         = $this->session->userdata('filtroConsultaGlobalComprobante');
        //$cliente             = $this->session->userdata('filtroConsultaGlobalCliente');
        $cliente             = $user_id;

        $servicio            = $this->session->userdata('filtroConsultaGlobalServicio');
        $cartero             = $this->session->userdata('filtroConsultaGlobalCartero');
        $hoja_ruta_id        = $this->session->userdata('filtroConsultaGlobalHojaRutaId');
        $despacho_id         = $this->session->userdata('filtroConsultaGlobalDespachoId');
        $sucursal            = $this->session->userdata('filtroConsultaGlobalSucursal');
        $estado              = $this->session->userdata('filtroConsultaGlobalEstado');
        $destinatario        = $this->session->userdata('filtroConsultaGlobalDestinatario');
        $domicilio           = $this->session->userdata('filtroConsultaGlobalDomicilio');
        $codigo_postal       = $this->session->userdata('filtroConsultaGlobalCodigoPostal');
        $localidad           = $this->session->userdata('filtroConsultaGlobalLocalidad');
        $fecha_cambio_estado = $this->session->userdata('filtroConsultaGlobalFechaCambioEstado');
        $visitas             = null; //$this->input->post('visitas');
        $rendicion_id        = $this->session->userdata('filtroConsultaGlobalRendicionId');
        $recibio             = $this->session->userdata('filtroConsultaGlobalRecibio');
        $documento           = $this->session->userdata('filtroConsultaGlobalDocumento');
        $vinculo             = null; //$this->input->post('vinculo');
        $datos_varios_1      = null; //$this->input->post('datos_varios_1');
        $datos_varios_2      = null; //$this->input->post('datos_varios_2');
        $datos_varios_3      = null; //$this->input->post('datos_varios_3');
        $datos_varios_4      = null;
        $fecha_pieza_desde   = $this->session->userdata('filtroConsultaGlobalFechaPiezaDesde');
        $fecha_pieza_hasta   = $this->session->userdata('filtroConsultaGlobalFechaPiezaHasta');
        $consultas_globales = $this->flash_md->getConsultasGlobalesClientes(
            $fecha_ingreso,
            $pieza_id,
            $barra_externa,
            $comprobante,
            $cliente,
            $servicio,
            $cartero,
            $hoja_ruta_id,
            $despacho_id,
            $sucursal,
            $estado,
            $destinatario,
            $domicilio,
            $codigo_postal,
            $localidad,
            $fecha_cambio_estado,
            $visitas,
            $rendicion_id,
            $recibio,
            $documento,
            $vinculo,
            $datos_varios_1,
            $datos_varios_2,
            $datos_varios_3,
            $datos_varios_4,
            $fecha_pieza_desde,
            $fecha_pieza_hasta
        );
        //load our new PHPExcel library
        $this->load->library('excel');
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('Consulta Global');
        //set cabeceras
        $this->excel->getActiveSheet()->setCellValue("A1", 'Fecha Ingreso');
        $this->excel->getActiveSheet()->setCellValue("B1", 'Pieza');
        $this->excel->getActiveSheet()->setCellValue("C1", 'Codigo');
        $this->excel->getActiveSheet()->setCellValue("D1", 'Comprobante');
        $this->excel->getActiveSheet()->setCellValue("E1", 'Cliente');
        $this->excel->getActiveSheet()->setCellValue("F1", 'Servicio');
        $this->excel->getActiveSheet()->setCellValue("G1", 'Cartero');
        $this->excel->getActiveSheet()->setCellValue("H1", 'Hoja de Ruta');
        $this->excel->getActiveSheet()->setCellValue("I1", 'Despacho');
        $this->excel->getActiveSheet()->setCellValue("J1", 'Sucursal');
        $this->excel->getActiveSheet()->setCellValue("K1", 'Estado');
        $this->excel->getActiveSheet()->setCellValue("L1", 'Destinatario');
        $this->excel->getActiveSheet()->setCellValue("M1", 'Domicilio');
        $this->excel->getActiveSheet()->setCellValue("N1", 'Cod. Postal');
        $this->excel->getActiveSheet()->setCellValue("O1", 'Localidad');
        $this->excel->getActiveSheet()->setCellValue("P1", 'Fecha cambio estado');
        $this->excel->getActiveSheet()->setCellValue("Q1", 'Visitas');
        $this->excel->getActiveSheet()->setCellValue("R1", 'Rendición');
        $this->excel->getActiveSheet()->setCellValue("S1", 'Recibí');
        $this->excel->getActiveSheet()->setCellValue("T1", 'Documento');
        $this->excel->getActiveSheet()->setCellValue("U1", 'Vínculo');
        $this->excel->getActiveSheet()->setCellValue("V1", 'Datos Varios');
        $this->excel->getActiveSheet()->setCellValue("w1", 'Remito');
        $this->excel->getActiveSheet()->setCellValue("x1", 'Datos Varios 1');
        $this->excel->getActiveSheet()->setCellValue("Y1", 'Datos Varios 2');
        $this->excel->getActiveSheet()->setCellValue("Z1", 'Datos Varios 3');
        $this->excel->getActiveSheet()->setCellValue("AA1", 'Mail');
        $this->excel->getActiveSheet()->setCellValue("AB1", 'Celular');

        // set cells values
        $this->load->library('simplehtmldom_1_5/simple_html_dom');
        $i = 2;
        foreach ($consultas_globales as $value) {
            // echo $trs->find('td',1)->innertext;
            $query_hdr_actual = "SELECT sp.hoja_ruta_id 
				FROM flash_subpiezas sp
				WHERE sp.id = (SELECT MAX(sp1.id) FROM flash_subpiezas sp1 WHERE sp1.pieza_id = $value->pieza_id)"; //(hrd actual)
            $query_despacho_actual = "select pd.despacho_id 
                         FROM flash_piezas_despacho_piezas pd 
                         WHERE pd.id = (SELECT MAX(pd3.id) FROM flash_piezas_despacho_piezas pd3 where pd3.pieza_id = $value->pieza_id)"; //(despacho actual)

            $result_hdr = $this->db->query($query_hdr_actual)->row();
            $result_despacho = $this->db->query($query_despacho_actual)->row();
            $cartero = "";
            if ($result_hdr != null && $result_hdr != "") {
                $query_cartero_actual = "SELECT sc.apellido_nombre cartero
                                                 FROM flash_hojas_rutas hdr
                                                 INNER JOIN flash_sucursales_carteros sc ON sc.id = hdr.cartero_id
                                                 WHERE hdr.id = $result_hdr->hoja_ruta_id"; //(cartero actual)
                //echo $query_cartero_actual ;die;
                $result_cartero_actual = $this->db->query($query_cartero_actual)->row();
                $cartero = $result_cartero_actual->cartero;
            }
            $this->excel->getActiveSheet()->setCellValue("A$i", $value->fecha_ingreso);
            $this->excel->getActiveSheet()->setCellValue("B$i", $value->pieza_id);
            $this->excel->getActiveSheet()->setCellValueExplicit("C$i", strval($value->barcode_externo));
            $this->excel->getActiveSheet()->setCellValueExplicit("D$i", strval($value->comprobante));
            $this->excel->getActiveSheet()->setCellValue("E$i", $value->cliente);
            $this->excel->getActiveSheet()->setCellValue("F$i", $value->servicio);
            $this->excel->getActiveSheet()->setCellValue("G$i", $cartero);
            $this->excel->getActiveSheet()->setCellValue("H$i", $result_hdr->hoja_ruta_id);
            $this->excel->getActiveSheet()->setCellValue("I$i", $result_despacho->despacho_id);
            $this->excel->getActiveSheet()->setCellValue("J$i", $value->sucursal);
            $this->excel->getActiveSheet()->setCellValue("K$i", $value->estado_actual);
            $this->excel->getActiveSheet()->setCellValue("L$i", $value->destinatario);
            $this->excel->getActiveSheet()->setCellValue("M$i", $value->domicilio);
            $this->excel->getActiveSheet()->setCellValue("N$i", $value->codigo_postal);
            $this->excel->getActiveSheet()->setCellValue("O$i", $value->localidad);
            $this->excel->getActiveSheet()->setCellValue("P$i", $value->fecha_cambio_estado);
            $this->excel->getActiveSheet()->setCellValue("Q$i", ''); //$value->visitas);
            $this->excel->getActiveSheet()->setCellValue("R$i", $value->rendicion_id);
            $this->excel->getActiveSheet()->setCellValue("S$i", $value->recibio);
            $this->excel->getActiveSheet()->setCellValue("T$i", $value->documento);
            $this->excel->getActiveSheet()->setCellValue("U$i", $value->vinculo);
            $this->excel->getActiveSheet()->setCellValue("V$i", $value->datos_varios);
            $this->excel->getActiveSheet()->setCellValue("W$i", $value->remito);
            $this->excel->getActiveSheet()->setCellValue("X$i", $value->datos_varios_1);
            $this->excel->getActiveSheet()->setCellValue("Y$i", $value->datos_varios_2);
            $this->excel->getActiveSheet()->setCellValue("Z$i", $value->datos_varios_3);
            $this->excel->getActiveSheet()->setCellValue("AA$i", $value->mail_dest);
            $this->excel->getActiveSheet()->setCellValue("AB$i", $value->celular_dest);

            $i++;
        }

        $this->excel->setActiveSheetIndex(0);
        $filename = 'Consultas_globales.xls'; //save our workbook as this file name

        header('Content-Type: application/vnd.ms-excel'); //mime type

        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name

        header('Cache-Control: max-age=0'); //no cache

        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format

        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

        //force user to download the Excel file without writing it to server's HD
        ob_end_clean();
        $objWriter->save('php://output');
    }
}

/* End of file piezas.php */
/* Location: ./system/application/controllers/piezas.php */
