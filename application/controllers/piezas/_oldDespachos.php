<?php

class Despachos extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        //$this->set_data();
        $query = Despacho::whereIn('estado', [Despacho::ESTADO_INICIADO, Despacho::ESTADO_CANCELADO]);
        if (!$this->is_admin()) {
            $query->whereOrigenId($this->usuario->sucursal_id);
        }

        $despachos = $query->get();

        $this->data([
            'despachos' => $despachos,
        ]);
        $this->title("Despachos");
        $this->view = 'components/piezas/piezas_despachos/index';
        $this->template('template/backend');
    }

    public function nuevo()
    {
        if ($this->input->is_post()) {
            $this->post_nuevo();
        }

        $this->set_data();

        $this->title("Nuevo Despacho");
        $this->view = 'components/piezas/piezas_despachos/nuevo';
        $this->template('template/backend');
    }

    private function post_nuevo()
    {
        ini_set('memory_limit', '-1');
        set_time_limit(1800);
        $piezas_id_str         = $this->input->post('piezas_ids');
        $piezas                = $piezas_id_str != '' ? split(",", $piezas_id_str) : [];
        $piezas_simples_id_str = $this->input->post('piezas_simples_ids');
        $piezas_simples        = $piezas_simples_id_str != '' ? split(",", $piezas_simples_id_str) : [];
        $piezas_todas          = array_merge($piezas, $piezas_simples);
        //var_dump($piezas);
        //var_dump($piezas_simples);

        //die;
        $despacho = Despacho::create([
            'origen_id'         => $this->input->post('origen_id'),
            'destino_id'        => $this->input->post('destino_id'),
            'usuario_origen_id' => $this->input->post('usuario_origen_id'),
            'transporte_id'     => $this->input->post('transporte_id'),
            'fecha_envio'       => formatdate($this->input->post('fecha_envio')),
            'piezas'            => count($piezas),
            'estado'            => $this->input->post('enviar') == 1 ? Despacho::ESTADO_ENVIADO : Despacho::ESTADO_INICIADO,
        ]);

        if ($piezas_todas != null) {
            $i = 0;
            foreach ($piezas_todas as $pieza_id) {
                $array_datos[$i] = array(
                    'despacho_id' => $despacho->id,
                    'pieza_id'    => $pieza_id,
                );
                $i++;
            }
            //Guardo las piezas simples
            $this->db->insert_batch('flash_piezas_despacho_piezas', $array_datos);
            //Establezco el estado a las piezas dentro del despacho
            //Luego actualizo flash_piezas, flash_piezas_novedades, flash_piezas_tracking
           // $this->alta_flash_piezas_novedades($piezas);

            //Saco este trozo de codigo porque no tiene sentido hacerle un update vacio a la piezas
            //            $piezas_guardadas = $this->flash_md->getPiezasPorDespacho($despacho->id);
            //            $data = array();
            //            foreach ($piezas_guardadas as $key => $value)

//            {
            //                $data[] = array(
            //                    'id' => $value->id,
            //                    //QUITADO 02-08-2017 para no pisar el estado del traking 'estado_id' => Pieza::ESTADO_EN_TRNSITO,

//                );
            //
            //            }
            //            $this->db->update_batch('flash_piezas', $data, 'id');
            /* Auditoria */
            $user_row = $this->ion_auth->user()->row();
            $data     = array(
                'user_id'     => $user_row->id,
                'categoria'   => 'DESPACHOS',
                'descripcion' => 'Despacho: ' . $despacho->id . '. Nuevo Despacho.',
                'origen'      => '',
                'destino'     => '',
            );
            $this->codegen_model->add('users_log', $data);
            /* END: Auditoria */

            $this->session->set_flashdata('registro', $despacho->barcode);
        }
        redirect(base_url('piezas/despachos'));
    }

    public function archivados()
    {
        $this->data->despachos = Despacho::whereEstado(Despacho::ESTADO_ARCHIVADO_ORIGEN)->get();

        $this->title("Despachos Archivados en Origen");
        $this->view = 'components/piezas/piezas_despachos/archivados';
        $this->template('template/backend');
    }

    public function ver($despacho_id)
    {
        $this->data->despacho = Despacho::findOrFail($despacho_id);

        $this->title("Ver Despacho Archivado en Origen");
        $this->view = 'components/piezas/piezas_despachos/ver';
        $this->template('template/backend');
    }

    public function borrarDespacho()
    {
        set_time_limit(1800);
        ini_set('memory_limit', '-1');
        $despacho_id = $this->input->post('despacho_delete_id');
        $despacho    = Despacho::findOrFail($despacho_id);
        $despacho->estado = Despacho::ESTADO_CANCELADO;
        $despacho->save();

        //Libero las piezas
        $i           = 0;
        $array_datos = [];
        foreach ($despacho->piezas as $pieza) {
            $array_datos[$i] = array(
                'id'        => $pieza->id,
                'estado_id' => Pieza::ESTADO_EN_GESTION,
            );
            $i++;
            if($pieza->tipo_id == Pieza::TIPO_NORMAL){
                $this->actualizar_piezas_estado_por_despacho($despacho,$pieza);
            }
        }
        if (count($array_datos) > 0) {
            $this->db->update_batch('flash_piezas', $array_datos, 'id');
        }

        //Elimino las piezas del despacho
        $this->db->delete('flash_piezas_despacho_piezas', array('despacho_id' => $despacho_id));
        //Elimino el despacho
        $this->db->delete('flash_piezas_despacho', array('id' => $despacho_id));

        /* Auditoria */
        $user_row = $this->ion_auth->user()->row();
        $data     = array(
            'user_id'     => $user_row->id,
            'categoria'   => 'DESPACHOS',
            'descripcion' => 'Despacho: ' . $despacho_id . '. Borrar Despacho.',
            'origen'      => '',
            'destino'     => '',
        );
        $this->codegen_model->add('users_log', $data);
        /* END: Auditoria */

        $this->data->success = true;
        redirect(base_url('piezas/despachos'));
    }

    public function piezas_estado()
    {
        $despacho_id      = $this->input->post('despacho_id');
        $estado           = $this->input->post('estado');
        $piezas           = $this->input->post('piezas');
        $tipo_id          = $this->input->post('tipo_id');
        $despacho         = Despacho::findOrFail($despacho_id);
        $despacho->estado = $estado;
        $despacho->save();

        if ($estado == Despacho::ESTADO_CANCELADO) {
            if ($tipo_id == Pieza::TIPO_NORMAL) {
                foreach ($despacho->piezas as $pieza) {
                    $this->actualizar_piezas_estado_por_despacho($despacho,$pieza);
                    //$pieza->estado_id = Pieza::ESTADO_EN_GESTION;
                    //$pieza->save();
                }
            } else {
                $cantidad = $this->input->post('cantidad');
                for ($i = 0; $i < $cantidad; $i++) {
                    $pieza            = $piezas[$i];
                    $pieza->estado_id = Pieza::ESTADO_EN_GESTION;
                    $pieza->save();
                }
            }
        }

        if ($estado == Despacho::ESTADO_RECIBIDO) {
            $despacho->fecha_recepcion    = date('Y-m-d');
            $despacho->usuario_destino_id = $this->usuario->id;
            $despacho->save();
            if ($tipo_id == Pieza::TIPO_NORMAL) {

                foreach ($despacho->piezas as $pieza) {
                    if (!in_array($pieza->id, $piezas)) {
                        $pieza->estado_id = Pieza::ESTADO_PERDIDA;
                    } else {
                        $this->actualizar_piezas_estado_por_despacho($despacho,$pieza);
                        //QUITADO 02-08-2017 para no pisar el estado del traking $pieza->estado_id = Pieza::ESTADO_EN_GESTION;
                        $pieza->sucursal_id = $this->usuario->sucursal_id;
                    }
                    $pieza->save();
                }
            } else {
                $cantidad = $this->input->post('cantidad');
                $cont     = 0;
                foreach ($despacho->piezas as $pieza) {
                    if ($cont < $cantidad) {
                        //QUITADO 02-08-2017 para no pisar el estado del traking $pieza->estado_id = Pieza::ESTADO_EN_GESTION;
                        $pieza->sucursal_id = $this->usuario->sucursal_id;
                    } else {
                        $pieza->estado_id = Pieza::ESTADO_PERDIDA;
                    }
                    $cont++;
                    $pieza->save();
                }
            }
        }

        /* Auditoria */
        $user_row = $this->ion_auth->user()->row();
        $data     = array(
            'user_id'     => $user_row->id,
            'categoria'   => 'DESPACHOS',
            'descripcion' => 'Despacho: ' . $despacho_id . '. Estado: ' . $estado . '. Piezas Estados.',
            'origen'      => '',
            'destino'     => '',
        );
        $this->codegen_model->add('users_log', $data);
        /* END: Auditoria */

        $this->data->success = true;
        $this->json();
    }

    private function actualizar_piezas_estado_por_despacho($despacho, $pieza){
        $user_row = $this->ion_auth->user()->row();
        /*DESPACHO CANCELADO*/
        //Si el despacho se cancela traigo los estados anteriores de tracking
        if($despacho->estado == Despacho::ESTADO_CANCELADO){
            $query_tracking =  $this->db
            ->select(' estado_id, id ')
            ->where( " pieza_id = ".$pieza->id)
            ->order_by( " id DESC ")
            ->limit(3)
            ->get('flash_piezas_tracking')->result_array();
          
        if (count($query_tracking)==1){ return json_encode(array("respuesta" => "El tracking tinene un solo estado"));}

        if (count($query_tracking)==2){
            $estado_actual_id = $query_tracking[1]['estado_id'];
            $estado_nuevo_id = $query_tracking[1]['estado_id'];
            $tracking_id = $query_tracking[0]['id'];
        }else{
            if(count($query_tracking)>2){
                $estado_actual_id = $query_tracking[2]['estado_id'];
                $estado_nuevo_id = $query_tracking[1]['estado_id'];
                $tracking_id = $query_tracking[0]['id'];
            }
        }
        
            $data_pieza = array(
                "estado_id" => $estado_nuevo_id//Pieza::ESTADO_EN_TRNSITO,
            );
            //var_dump($result_tracking[0]->estado_id);
            
            $this->codegen_model->edit('flash_piezas', $data_pieza, 'id', $pieza->id);
            $this->codegen_model->delete('flash_piezas_despacho_piezas', 'pieza_id', $pieza->id . ' AND despacho_id = ' . $despacho->id);
             
            $update_novedades = " UPDATE flash_piezas_novedades n
                                            INNER JOIN flash_piezas p ON p.id = n.pieza_id
                                            SET estado_nuevo_id = p.estado_id,
                                                estado_actual_id = " . intval($estado_actual_id) . "
                                                   WHERE pieza_id = " . $pieza->id;
            $this->db->query($update_novedades);
    
            //Elimina el ultimo traking creado
            $delete_tracking = " DELETE FROM flash_piezas_tracking WHERE id = ".$tracking_id;
            $this->db->query($delete_tracking);
            //Si el despacho se recibe busco el estado significativo y actualizo

            //Piezas
            //Novedades
            //Tracking
        }
        /*DESPACHO RECIBIDO*/
        if($despacho->estado == Despacho::ESTADO_RECIBIDO){

            $query = $this->db
                    ->select('ev.id as estado_id')
                    ->join('flash_piezas_estados_variables ev', 'd.destino_id = ev.sucursal_id AND ev.pieza_estado_id = 1') //1 iniciales
                    ->where('d.id = '. $despacho->id)
                        ->get('flash_piezas_despacho d');
            
            $row = $query->row();
            
            if (isset($row))
            {
                $estado_id =  $row->estado_id;
            }else{
                    $estado_id = Pieza::ESTADO_RECIBIDA;
            }
            $data_pieza = array(
                "estado_id" => $estado_id//Pieza::ESTADO_EN_TRNSITO,
            );
            
            
            $update_novedades = " UPDATE flash_piezas_novedades n
                                            INNER JOIN flash_piezas p ON p.id = n.pieza_id
                                            SET estado_actual_id = p.estado_id,
                                                estado_nuevo_id = " . $estado_id . "
                                                    WHERE pieza_id = " . $pieza->id;
            
                    $array_piezas_tracking[]     = array(
                        'pieza_id'              => $pieza->id,
                        'usuario_id'            => $user_row->id,
                        'estado_id'             => $estado_id, 
                        //'create'                => date("Y-m-d H:i:s"),
                        'update'                => date("Y-m-d H:i:s"),
                        'create_user_id'        =>  $user_row->id,
                        'update_user_id'         => $user_row->id,
                    );
                    //Primero actualizo novedades y despues piezas     
                    $this->db->query($update_novedades);        
                    $this->codegen_model->edit('flash_piezas', $data_pieza, 'id', $pieza->id);                                  
                    //inserto en la tabla flash_piezas_tracking
                    $this->db->insert_batch('flash_piezas_tracking', $array_piezas_tracking);	
        }
        
    }

    public function piezas_estado_sin_verificar()
    {
        ini_set('memory_limit', '-1');
        $despacho_id                  = $this->input->post('despacho_id');
        $estado                       = $this->input->post('estado');
        $despacho                     = Despacho::findOrFail($despacho_id);
        $despacho->estado             = $estado;
        $despacho->fecha_recepcion    = date('Y-m-d');
        $despacho->usuario_destino_id = $this->usuario->id;
        $user_row                     = $this->ion_auth->user()->row();

        $query = $this->db
        ->select('ev.id as estado_id')
        ->join('flash_piezas_estados_variables ev', 'd.destino_id = ev.sucursal_id AND ev.pieza_estado_id = 1') //1 INICIALES
        ->where('d.id = '. $despacho_id)
        ->get('flash_piezas_despacho d');

        $row = $query->row();
        $estado_id = isset($row)?$row->estado_id:Pieza::ESTADO_EN_GESTION;
        $i = 0;
        foreach ($despacho->piezas as $pieza) {
            $array_datos[$i] = array(
                'id'          => $pieza->id,
                'estado_id'   => $pieza->tipo_id==Pieza::TIPO_NORMAL?$estado_id:Pieza::ESTADO_EN_GESTION,
                'sucursal_id' => $this->usuario->sucursal_id,
            );
            $i++;
            //Actualizo Novedades solo para las piezas normales
            if($pieza->tipo_id == Pieza::TIPO_NORMAL){
                $update_novedades = " UPDATE flash_piezas_novedades n
                                                    INNER JOIN flash_piezas p ON p.id = n.pieza_id
                                                    SET estado_actual_id = p.estado_id,
                                                        estado_nuevo_id = " . $estado_id . "
                                                           WHERE pieza_id = " . $pieza->id;

                //Actualizo Tracking solo para las piezas Normales
                $array_piezas_tracking[]     = array(
                    'pieza_id'              => $pieza->id,
                    'usuario_id'            => $user_row->id,
                    'estado_id'             => $estado_id, 
                    'create'                => date("Y-m-d H:i:s"),
                    'update'                => date("Y-m-d H:i:s"),
                    'create_user_id'        =>  $user_row->id,
                    'update_user_id'         => $user_row->id,
                ); 
                
                //Primero actualizo novedades y despues piezas     
                $this->db->query($update_novedades);        
            }
        }
        //Actualizo en batch piezas
        $this->db->update_batch('flash_piezas', $array_datos, 'id');
        //inserto en la tabla flash_piezas_tracking
        $this->db->insert_batch('flash_piezas_tracking', $array_piezas_tracking);
        /* Auditoria */
        $user_row = $this->ion_auth->user()->row();
        $data     = array(
            'user_id'     => $user_row->id,
            'categoria'   => 'DESPACHOS',
            'descripcion' => 'Despacho: ' . $despacho_id . '. Estado: ' . $estado . '. Piezas Estado Sin Verificar.',
            'origen'      => '',
            'destino'     => '',
        );
        $this->codegen_model->add('users_log', $data);
        /* END: Auditoria */

        $this->data->success = true;
        $despacho->save();
        $this->json();
    }

    public function editar($despacho_id)
    {
        if ($this->input->is_post()) {
            $this->post_editar($despacho_id);
        }

        $this->data->despacho = $despacho = Despacho::findOrFail($despacho_id);
        $this->data->piezas   = $this->flash_md->getDespachoPiezasAgrupadas($despacho_id);

        foreach ($this->data->piezas as $value) {
            if(isset($this->data->piezas_ids)){
                if ( $this->data->piezas_ids == '') {
                    $this->data->piezas_ids .= $value->id;
                } else {
                    $this->data->piezas_ids .= ',' . $value->id;
                }
            }
        }

        $this->data([
            'despacho'    => $this->data->despacho,
            'transportes' => Transporte::all(),
            'usuarios'    => $this->is_user() ? Usuario::where("sucursal_id", "=", $this->usuario->sucursal_id)->where("id", "=", $this->data->despacho->usuario_origen_id)->get() : Usuario::all(),
            'sucursales'  => $this->is_user() ? Sucursal::where("id", "!=", $this->usuario->sucursal_id)->get() : Sucursal::all(),
        ]);

        $this->title("Editar Despacho");
        $this->view = 'components/piezas/piezas_despachos/editar';
        $this->template('template/backend');
    }

    public function post_editar($despacho_id)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(1800);
        $piezas_id_str         = $this->input->post('piezas_ids');
        $piezas                = $piezas_id_str != '' ? split(",", $piezas_id_str) : [];
        $piezas_simples_id_str = $this->input->post('piezas_simples_ids');
        $piezas_simples        = $piezas_simples_id_str != '' ? split(",", $piezas_simples_id_str) : [];
        $piezas_todas          = array_merge($piezas, $piezas_simples);
        $despacho              = Despacho::findOrFail($despacho_id);
        $despacho->update([
            'origen_id'         => $this->input->post('origen_id'),
            'destino_id'        => $this->input->post('destino_id'),
            'usuario_origen_id' => $this->input->post('usuario_origen_id'),
            'transporte_id'     => $this->input->post('transporte_id'),
            'fecha_envio'       => formatdate($this->input->post('fecha_envio')),
            'piezas'            => $despacho->piezas + $this->input->post('cantidad_servicios'),
            'estado'            => $this->input->post('enviar') == 1 ? Despacho::ESTADO_ENVIADO : Despacho::ESTADO_INICIADO,
        ]);
        if ($piezas_todas != null) {
            $i = 0;
            foreach ($piezas_todas as $pieza_id) {
                $array_datos[$i] = array(
                    'despacho_id' => $despacho->id,
                    'pieza_id'    => $pieza_id,
                );
                $i++;
            }
            $this->db->insert_batch('flash_piezas_despacho_piezas', $array_datos);

            /* Auditoria */
            $user_row = $this->ion_auth->user()->row();
            $data     = array(
                'user_id'     => $user_row->id,
                'categoria'   => 'DESPACHOS',
                'descripcion' => 'Despacho: ' . $despacho_id . '. Editar Despacho.',
                'origen'      => '',
                'destino'     => '',
            );
            $this->codegen_model->add('users_log', $data);
            /* END: Auditoria */

            $this->session->set_flashdata('registro', $despacho->barcode);
        }

        redirect(base_url('piezas/despachos'));
    }

    private function set_data()
    {

        $this->data([
            //'despachos' => $despachos,
            //'despacho_ids' => $despacho_ids,
            //            'piezas' => $piezas,
            //            'numeros' => $numeros,
            'transportes'     => Transporte::all(),
            'usuarios'        => $this->is_user() ? Usuario::where("id", "=", $this->usuario->id)->get() : Usuario::all(),
            'sucursales'      => $this->is_user() ? Sucursal::where("id", "!=", $this->usuario->sucursal_id)->get() : Sucursal::all(),
            'sucursal_origen' => $this->is_user() ? Sucursal::where("id", "=", $this->usuario->sucursal_id)->get() : Sucursal::all(),
            //'comprobantesPiezasSimples' => $comprobantesPiezasSimples
        ]);
    }

    public function exportarAPDF()
    {
        $this->load->library('simplehtmldom_1_5/simple_html_dom');
        $this->load->library('Pdf_despachos');
        $despacho_id     = $this->input->post('pdf_despacho_id');
        $despacho_piezas = $this->flash_md->getALLPiezasDespachos($despacho_id);
        //Set custom header data
        $custom_layout = array(220, 340);
        $pdf           = new Pdf_despachos('P', 'mm', $custom_layout, true, 'UTF-8', false); //: portrait, L:landscape

        $pdf->SetTitle('Despachos');
        $pdf->SetHeaderMargin(300);
        $pdf->SetTopMargin(200);
        $pdf->setFooterMargin(20);
        $pdf->SetAutoPageBreak(true);
        $pdf->SetAuthor('Author');
        $pdf->SetDisplayMode('real', 'default');
        $pdf->Write(5, 'CodeIgniter TCPDF Integration');

        // set header and footer fonts
        $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(0);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
            require_once dirname(__FILE__) . '/lang/eng.php';
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
        $pdf->AddPage('P', $custom_layout);

        // create some HTML content
        //            $pdf->Write(20, "DETALLE DESPACHOS IMPRESION", '', false, "L", true);
        //            $pdf->Write(14, "Número despacho: ".$despacho_id, '', false, "L", true);
        //            $pdf->Write(14, "Destino: ".$despacho_piezas[0]->sucursal, '', false, "L", true);
        $titulo = '<table border="0" cellspacing="6" cellpadding="4" style="margin-bottom:20px">
                            <tr>
                                <td style="font-size:20px;font-style: italic;font-weight:bold">DETALLE DESPACHOS IMPRESION</td>
                            </tr>
                            <tr>
                                <td style="font-size:11px">Número de Despacho: ' . $despacho_id . '</td>
                            </tr>
                            <tr>
                                <td style="font-size:11px">Destino del Despacho: ' . $despacho_piezas[0]->sucursal . '</td>
                            </tr>
                            <tr>
                                <td style="font-size:20px"></td>
                            </tr>
                        </table>';

        $html = '
            <table style="border-bottom-width: 1px solid;" width="100%">
                <tr>
                    <th>Cliente</th>
                    <th>Comp. Ingreso ID</th>
                    <th>Servicio</th>
                    <th align="right">Piezas</th>
                </tr>
            </table>
            <table style="padding-top:10px" border="0" > ';
        $table_contenido = "";
        $total           = 0;
        foreach ($despacho_piezas as $despacho) {
            $total += doubleval($despacho->cantidad_piezas);
            $table_contenido .= '<tr>
                                        <td>' . $despacho->cliente . '</td>
                                        <td>' . $despacho->numero . '</td>
                                        <td>' . $despacho->servicio . '</td>
                                        <td align="right">' . $despacho->cantidad_piezas . '</td>
                                    </tr>';
        }
        $sumatoria = '  <tr>
                                <td></td>
                                <td style="font-weight:bold;text-align:right">TOTAL</td>
                                <td style="font-weight:bold;text-align:right">' . $total . '</td>
                            </tr>';
        $cierre_table = '</table>';
        $pdf->writeHTML($titulo . $html . $table_contenido . $sumatoria . $cierre_table, true, false, true, false, '');

        //echo strftime("%A, %d de %B de %Y");die;
        //echo 'Antes de setlocale strftime devuelve: '.strftime("%A, %d de %B de %Y", $dateTime).'<br/>';die;
        $fin_pagina = '<table border="0" cellspacing="6" cellpadding="4" style="margin-bottom:20px">
                            <tr>
                                <td style="font-size:11px;font-style: italic;font-weight:bold;text-align:right">FIRMA DESPACHANTE.........................</td>
                            </tr>
                            <tr>
                                <td style="font-size:11px;font-style: italic;font-weight:bold;text-align:right"></td>
                            </tr>
                            <tr>
                                <td style="font-size:11px;font-style: italic;font-weight:bold;text-align:right">FIRMA RECEPTOR..........................</td>
                            </tr>
                        </table>';
        $pdf->writeHTMLCell($w, $h, 100, 240, $fin_pagina, 0, false);
        $pdf->Output('despacho.pdf', 'I');
    }

    public function consultas_despachos()
    {
        $vista_interna = array(
            'permisos_efectivos' => $this->permisos,
            'results'            => []
        );

        $vista_externa = array(
            'title'          => ucwords("Consultas por Despachos"),
            'contenido_main' => $this->load->view('components/piezas/piezas_despachos/consultas/consultas_despachos', $vista_interna, true),
        );

        $this->load->view('template/backend', $vista_externa);
    }

    public function consultas_despachos_filtro()
    {
        $periodo_desde = $this->input->post('periodo_desde') != "" ? $this->input->post('periodo_desde') : false;
        $periodo_hasta = $this->input->post('periodo_hasta') != "" ? $this->input->post('periodo_hasta') : false;
        $dias          = $this->input->post('dias') != "" ? $this->input->post('dias') : '';

        $vista_interna = array(
            'permisos_efectivos' => $this->permisos,
            'results'            => $this->flash_md->getDespachosFechaCreacion($periodo_desde, $periodo_hasta, $dias),
            'periodo_desde'      => $periodo_desde,
            'periodo_hasta'      => $periodo_hasta,
            'dias'               => $dias,
        );

        $vista_externa = array(
            'title'          => ucwords("Consultas por Despachos"),
            'contenido_main' => $this->load->view('components/piezas/piezas_despachos/consultas/consultas_despachos', $vista_interna, true),
        );

        $this->load->view('template/backend', $vista_externa);
    }

    public function exportar_consulta_despachos()
    {
        $periodo_desde = $this->input->post('excel_desde') != "" ? $this->input->post('excel_desde') : false;
        $periodo_hasta = $this->input->post('excel_hasta') != "" ? $this->input->post('excel_hasta') : false;
        $dias          = $this->input->post('excel_dias') != "" ? $this->input->post('excel_dias') : 0;

        $despachos = $this->flash_md->getDespachosFechaCreacion($periodo_desde, $periodo_hasta, $dias);

        //load our new PHPExcel library
        $this->load->library('excel');
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('Despachos');
        //set cabeceras
        $this->excel->getActiveSheet()->setCellValue("A1", 'Cliente');
        $this->excel->getActiveSheet()->setCellValue("B1", 'Fecha Ing.');
        $this->excel->getActiveSheet()->setCellValue("C1", 'Despacho');
        $this->excel->getActiveSheet()->setCellValue("D1", 'Fecha');
        $this->excel->getActiveSheet()->setCellValue("E1", 'Origen');
        $this->excel->getActiveSheet()->setCellValue("F1", 'Destino');
        $this->excel->getActiveSheet()->setCellValue("G1", 'servicios');
        $this->excel->getActiveSheet()->setCellValue("H1", 'Piezas');
        $this->excel->getActiveSheet()->setCellValue("I1", 'Estado');
        $this->excel->getActiveSheet()->setCellValue("J1", 'Demora');
        // set cells values
        $this->load->library('simplehtmldom_1_5/simple_html_dom');
        $i = 2;
        foreach ($despachos as $value) {
            // echo $trs->find('td',1)->innertext;
            $start_ts = strtotime($value->fecha_ingreso);
            $end_ts   = strtotime($value->create);
            $diff     = $end_ts - $start_ts;
            $diff     = round($diff / 86400);
            if ($diff >= $dias) {
                $this->excel->getActiveSheet()->setCellValue("A$i", $value->cliente);
                $this->excel->getActiveSheet()->setCellValue("B$i", $value->fecha_ingreso);
                $this->excel->getActiveSheet()->setCellValue("C$i", $value->despacho_id);
                $this->excel->getActiveSheet()->setCellValue("D$i", $value->create);
                $this->excel->getActiveSheet()->setCellValue("E$i", $value->origen);
                $this->excel->getActiveSheet()->setCellValue("F$i", $value->destino);
                $this->excel->getActiveSheet()->setCellValue("G$i", $value->servicio);
                $this->excel->getActiveSheet()->setCellValue("H$i", $value->piezas);
                $this->excel->getActiveSheet()->setCellValue("I$i", $value->estado);
                $this->excel->getActiveSheet()->setCellValue("J$i", $diff);
                $i++;
            }
        }

        $this->excel->setActiveSheetIndex(0);
        $filename = 'Listado_despachos.xls'; //save our workbook as this file name

        header('Content-Type: application/vnd.ms-excel'); //mime type

        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name

        header('Cache-Control: max-age=0'); //no cache

        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format

        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');
    }
}

/* End of file Despachos.php */
/* Location: ./system/application/controllers/Despachos.php */
