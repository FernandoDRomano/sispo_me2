<?php

class Hojas_rutas extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        // load Pagination library
        $this->load->library('pagination');

        // load URL helper
        $this->load->helper('url');
    }

    public function index()
    {
        $this->data->hojas = array();
        if ($this->is_user()) {
            $this->data->sucursales = Sucursal::whereId($this->usuario->sucursal_id)->get();
        }

        if ($this->is_admin()) {
            $this->data->sucursales = Sucursal::all();
        }

        if (!is_null($this->input->post('sucursal_id'))) {
            $this->session->set_userdata('filtroHojasRutasSucursalId', $this->input->post('sucursal_id'));
            $sucursal_id = $this->input->post('sucursal_id');
        } else {
            $sucursal_id = $this->session->userdata('filtroHojasRutasSucursalId');
        }

        if (!is_null($this->input->post('procesar'))) {
            $this->session->set_userdata('filtroHojasRutasProcesar', $this->input->post('procesar'));
            $procesar = $this->input->post('procesar');
        } else {
            $procesar = $this->session->userdata('filtroHojasRutasProcesar');
        }

        if (!is_null($this->input->post('hdr_id'))) {
            $this->session->set_userdata('filtroHojasRutasHdrId', $this->input->post('hdr_id'));
            $hdr_id = $this->input->post('hdr_id');
        } else {
            $hdr_id = $this->session->userdata('filtroHojasRutasHdrId');
        }
        
        if ($procesar != '1') {
            $this->data->time_total = "Proceso aún no realizado. Faltan aplicar filtros.";
            $this->data->hojas = null;
        } else {
            // load db and model
            $this->load->database();

            $time_start = microtime(true);

            // init params
            $params = array();
            $limit_per_page = 50;
            $start_index = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
            $total_records = $this->flash_md->getListaHojasRutasTotal($sucursal_id, $hdr_id);
            if ($total_records > 0) {
                // get current page records
                $hojas =  $this->flash_md->getListaHojasRutas($sucursal_id, $limit_per_page, $start_index, $hdr_id);
                foreach ($hojas as &$hoja) {
                    $hoja->baja = $this->check_baja_id($hoja->id);
                }
                $this->data->hojas = $hojas;

                $config['base_url'] = base_url() . 'hojas_rutas/hojas_rutas/index';
                $config['total_rows'] = $total_records;
                $config['per_page'] = $limit_per_page;
                $config["uri_segment"] = 4;
                $config['num_links'] = 20;
                $config['page_query_string'] = FALSE;
                $config['full_tag_open'] = "<ul class='pagination'>";
                $config['full_tag_close'] ="</ul>";
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
                $this->data->links = $this->pagination->create_links();
            }
            $time_end = microtime(true);
            $time_total = number_format($time_end - $time_start, 2);

            $this->data->time_total = "Proceso ejecutado en $time_total seg. Total de registro $total_records.";
        }



        $this->title('Hojas de Rutas');
        $this->view = 'components/hojas_rutas/hojas_rutas/hojas_rutas_list';
        $this->template('template/backend');
    }
    /*
    public function filtro()
    {
        $sucursal_id = $this->input->post('sucursal_id');

        $hojas = $this->flash_md->getListaHojasRutas($sucursal_id); //$query->get();

        foreach ($hojas as &$hoja) {
            $hoja->baja = $this->check_baja_id($hoja->id);
        }
        if ($this->is_user()) {
            $this->data->sucursales = Sucursal::whereId($this->usuario->sucursal_id)->get();
        }

        if ($this->is_admin()) {
            $this->data->sucursales = Sucursal::all();
        }

        $this->data->hojas = $hojas;
        $this->title("hojas de rutas");
        $this->view = 'components/hojas_rutas/hojas_rutas/hojas_rutas_list';
        $this->template('template/backend');
    }
    */
    public function archivadas()
    {
        $this->data->hojas = array();
        if ($this->is_user()) {
            $this->data->sucursales = Sucursal::whereId($this->usuario->sucursal_id);
        }

        if ($this->is_admin()) {
            $this->data->sucursales = Sucursal::all();
        }

        $this->title('Hojas de Rutas');
        $this->view = 'components/hojas_rutas/hojas_rutas/archivadadas';
        $this->template('template/backend');
    }

    public function filtro_archivadas()
    {
        $sucursal_id = $this->input->post('sucursal_id');

        $query = Hoja::where('estado', '==', Hoja::ESTADO_ARCHIVADA);
        $query = Hoja::orderBy('create', 'asc');

        if ($this->is_user()) {
            $query->where('sucursal_id', $this->usuario->sucursal_id);
        }

        if ($sucursal_id != '') {
            $query->where('sucursal_id', $sucursal_id);
        }

        $hojas = $query->get();
        foreach ($hojas as &$hoja) {
            $hoja->baja = $this->check_baja_id($hoja->id);
        }
        if ($this->is_user()) {
            $this->data->sucursales = Sucursal::whereId($this->usuario->sucursal_id);
        }

        if ($this->is_admin()) {
            $this->data->sucursales = Sucursal::all();
        }

        $this->data->hojas = $hojas;
        $this->title("hojas de rutas");
        $this->view = 'components/hojas_rutas/hojas_rutas/archivadadas';
        $this->template('template/backend');
    }

    public function estado()
    {
        
        
        $hoja_id      = $this->input->post('hoja_id');
        $estado       = $this->input->post('estado');
        $hoja         = Hoja::findOrFail($hoja_id);
        $hoja->estado = $estado;
        $hoja->save();

        /* Auditoria */
        $user_row = $this->ion_auth->user()->row();
        $data     = array(
            'user_id'     => $user_row->id,
            'categoria'   => 'HOJAS DE RUTAS',
            'descripcion' => 'Hoja de Ruta: ' . $hoja_id . '. Estado: ' . $estado . '. Cambio de Estado Hoja de Ruta.',
            'origen'      => '',
            'destino'     => '',
        );
        $this->codegen_model->add('users_log', $data);
        /* END: Auditoria */

        if ($estado == Hoja::ESTADO_CANCELADA) {
            $user_row = $this->ion_auth->user()->row();
            $data     = array(
                'user_id'  => $user_row->id,
                'consulta' => "ESTADO: CANCELADA: DELETE FROM flash_subpiezas WHERE hoja_ruta_id = " . $hoja_id,
                'fecha'    => date('Y-m-d H:i:s'));
            $this->codegen_model->add('auditoria', $data);

            $data = array();
            $data_normales = [];
            
            foreach ($hoja->piezas as $pieza) {
                if($pieza->tipo_id == pieza::TIPO_NORMAL){
                    array_push($data_normales,$pieza->id);
                 }else{
                    $data[] = array(
                        'id'        => $pieza->id,
                        'estado_id' => Pieza::ESTADO_EN_GESTION,
                    );
                }
            }
        //Hay que dejar el estado de las piezas como estaba antes de la HDR
        
        foreach($data_normales as $pieza_id){
            $query_tracking =  $this->db
                ->select(' estado_id, id ')
                ->where( " pieza_id = ".$pieza_id)
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
            //echo $estado_actual_id."    ".$estado_nuevo_id."    ".$tracking_id."<\br>";
            $this->codegen_model->edit('flash_piezas', $data_pieza, 'id', $pieza_id);

            $update_novedades = " UPDATE flash_piezas_novedades n
                                            INNER JOIN flash_piezas p ON p.id = n.pieza_id
                                            SET estado_nuevo_id = p.estado_id,
                                                estado_actual_id = " . intval($estado_actual_id) . ",
                                                 n.update = '" . date("Y-m-d H:i:s") . "'
                                                  WHERE pieza_id = " . $pieza_id;
            $this->db->query($update_novedades);
            //Elimina el ultimo traking creado
            $delete_tracking = " DELETE FROM flash_piezas_tracking WHERE id = ".$tracking_id;
            $this->db->query($delete_tracking);
            
            
        }
            if(count($data) > 0){
                $this->db->update_batch('flash_piezas', $data, 'id');
            }
            $this->db->delete('flash_subpiezas', array("hoja_ruta_id" => $hoja_id));
            $this->data->success = true;
        }
        $this->json();
    }

    public function novedad()
    {
        
        
        $subpieza_id = $this->input->post('subpieza_id');
        $estado      = $this->input->post('estado');
        $subpieza    = Subpieza::findOrFail($subpieza_id);
        $pieza       = $subpieza->pieza;

        $data_novedad = array(
            'usuario_id'       => $this->usuario->id,
            'pieza_id'         => $subpieza->pieza->id,
            'estado_actual_id' => $pieza->estado_id,
            'estado_nuevo_id'  => $estado,
            'create' => date("Y-m-d H:i:s"),
        );
        $data_tracking = array(
            'usuario_id'       => $this->usuario->id,
            'pieza_id'         => $subpieza->pieza->id,
            'estado_id' => $estado,
            'create' => date("Y-m-d H:i:s"),
        );
        $this->codegen_model->add('flash_piezas_novedades', $data_novedad);
        //Tracking
        $this->codegen_model->add('flash_piezas_tracking', $data_tracking);

        $pieza->estado_id = $estado;
        $pieza->save();

	/* Auditoria */
        $user_row = $this->ion_auth->user()->row();
        $data     = array(
            'user_id'     => $user_row->id,
            'categoria'   => 'HOJAS DE RUTAS',
            'descripcion' => 'Pieza: ' . $subpieza->pieza->id . '. Estado: ' . $estado . '. Novedad Hoja de Ruta.',
            'origen'      => '',
            'destino'     => '',
        );
        $this->codegen_model->add('users_log', $data);
        /* END: Auditoria */
        
        //Grabar el pieza_estado_id en la tabla Subpiezas
        $subpieza->pieza_estado_id = $estado;
        $subpieza->save();
        
        $this->data->estado       = $estado;
        $this->data->estadoNombre = Pieza::estadoNombre($estado);
        $this->data->novedades    = $subpieza->pieza->novedades->count();
        $this->data->success      = true;
        $this->json();
    }

    /**
     * @param $id
     */
    public function view($id)
    {
        $hoja = Hoja::findOrFail($id);

        $this->data->hoja   = $hoja;
        $this->data->piezas = $this->flash_md->getPiezasPorHDRGroupSimples($id);
        //$this->data->piezas = $hoja->piezas()->groupBy('id')->get();

        $this->title("Ver hoja de ruta");
        $this->view = 'components/hojas_rutas/hojas_rutas_alta/hojas_rutas_view';
        $this->template('template/backend');
    }

    public function check_baja()
    {
        $id               = $this->input->get('hoja_id');
        $this->data->baja = $this->check_baja_id($id);
        $this->json();
    }

    public function check_baja_tabla()
    {
        $ids   = $this->input->get('ids');
        $bajas = [];
        foreach ($ids as $id) {
            $bajas[$id] = $this->check_baja_id($id);
        }

        $this->data->bajas = $bajas;
        $this->json();
    }

    /**
     * @param $id
     * @return mixed
     */
    private function check_baja_id($id)
    {   ini_set("max_execution_time", 1800);
        $hoja = $this->codegen_model->get('flash_hojas_rutas','*', 'id = '.$id);//Hoja::findOrFail($id);

        $baja = true;
        
        if ($hoja->estado == Hoja::ESTADO_BAJA) {
            $baja = false;
        }

        $query_normales = " SELECT p.id pieza_id, p.estado_id estado_id, ev.pieza_estado_id estado_general_id
                            FROM flash_piezas p 
                            INNER JOIN flash_subpiezas sp ON sp.pieza_id = p.id 
                            INNER JOIN flash_piezas_estados_variables ev ON sp.pieza_estado_id = ev.id
                            WHERE p.tipo_id = ".Pieza::TIPO_NORMAL."
                            AND sp.hoja_ruta_id = ".$id;//$hoja->piezas()->whereTipoId(Pieza::TIPO_NORMAL)->get();
//        echo $query_normales;die;
        $normales = $this->db->query($query_normales);
        //echo "normales ".$id.": ".count($normales->result())."<br/>";
        if ($baja && count($normales->result()) > 0) {
            foreach ($normales->result() as $pieza) {//echo var_dump($pieza)."<br/>";//echo $pieza->id. " - ".$pieza->estado_general_id."<br/>";{echo var_dump($pieza)."<br/>";
                if ($pieza->estado_general_id != PiezaEstado::ESTADOS_RENDICIONES && $pieza->estado_general_id != PiezaEstado::ESTADOS_ORGANIZATIVOS) {
                    $baja = false;
                }
            }
        }
        //die;
        return $baja;
    }

/*    public function baja()
    {
        set_time_limit(1800);
        $id   = $this->input->post('hoja_id');
        $hoja = Hoja::findOrFail($id);
        $subpiezas = Subpieza::where('hoja_ruta_id', '=', $id)->get();
        $subpiezas_simples = " SELECT * 
                                FROM flash_subpiezas sp
                                INNER JOIN flash_piezas p ON p.id = sp.pieza_id
                                left join flash_piezas_estados_variables ev on ev.id = sp.pieza_estado_id
                                WHERE (sp.pieza_estado_id = 0 OR ev.pieza_estado_id <> ".PiezaEstado::ESTADOS_RENDICIONES." OR ev.pieza_estado_id <> ".PiezaEstado::ESTADOS_ORGANIZATIVOS. ") 
                                 AND p.tipo_id = 1
                                 AND sp.hoja_ruta_id =  ".$id;
        
        $subpiezas_simples = $this->db->query($subpiezas_simples)->result();
//        
        $subpiezas_normales = " SELECT *, ev.pieza_estado_id estado_general_id
                                FROM flash_subpiezas sp
                                INNER JOIN flash_piezas p ON p.id = sp.pieza_id
                                left join flash_piezas_estados_variables ev on ev.id = sp.pieza_estado_id
                                WHERE (sp.pieza_estado_id = 0 OR ev.pieza_estado_id <> ".PiezaEstado::ESTADOS_RENDICIONES." OR ev.pieza_estado_id <> ".PiezaEstado::ESTADOS_ORGANIZATIVOS. ") 
                                 AND p.tipo_id = 2
                                 AND sp.hoja_ruta_id =  ".$id;

        $subpiezas_normales = $this->db->query($subpiezas_normales)->result();

        $user_row = $this->ion_auth->user()->row();
        //Luego que tengo las subpiezas simples pendientes de novedad (NULL) tiene que marcarse como ENTREGADAS
        if ($subpiezas_simples != null) {
            foreach ($subpiezas_simples as $subpieza_simple) {
                //Busco si alguna pieza simple no tiene novedad la marco como ENTREGADA
                $sql_insert   = "INSERT INTO flash_piezas_novedades (pieza_id, cantidad, usuario_id,  estado_actual_id, estado_nuevo_id)  
                                  VALUES(  $subpieza_simple->pieza_id,
                                        1,
                                        $user_row->id, 
                                            $subpieza_simple->estado_id,".
                                                Pieza::ESTADO_ENTREGADA.")
                                         ON DUPLICATE KEY UPDATE 
                                            estado_actual_id= $subpieza_simple->estado_id , estado_nuevo_id=" . Pieza::ESTADO_ENTREGADA . ",flash_piezas_novedades.update=" . "'" . date('Y-m-d H:m:s') . "'";

                $query_insert = $this->db->query($sql_insert);
                
//                    $data = array(
//                        'pieza_id'         => $subpieza_simple->pieza_id,
//                        //'cantidad' => 1,
//                        'usuario_id'       => $user_row->id,
//                        'estado_actual_id' => $subpieza_simple->estado_id,
//                        'estado_nuevo_id'  => Pieza::ESTADO_ENTREGADA,
//                        //'create' => date('Y-m-d'),
//                        'update'           => date('Y-m-d'),
//                    );
//                    Novedad::updateOrCreate($data);
                    //Tracking
                    $data_tracking = array(
                        'usuario_id'    => $user_row->id,
                        'pieza_id'      => $subpieza_simple->pieza_id,
                        'estado_id'     => Pieza::ESTADO_ENTREGADA,
                    );
                    $this->codegen_model->add('flash_piezas_tracking', $data_tracking);
                    //Actualizo la tabla piezas
                    $data_pieza = array(
                                    'estado_id' => Pieza::ESTADO_ENTREGADA,
                                );
                    $this->codegen_model->edit('flash_piezas', $data_pieza, 'id', $subpieza_simple->pieza_id);
                    //Actualizo pieza_estado_id en la tabla subpiezas
                    $data_subpieza = array(
                                    'pieza_estado_id' => Pieza::ESTADO_ENTREGADA,
                                );
                    $this->codegen_model->edit('flash_subpiezas', $data_subpieza, 'pieza_id', $subpieza_simple->pieza_id);
                
            }
        }
        $baja_HDR = true;
        if ($subpiezas_normales != null) {
            $this->data->mensaje = '<div class="alert alert-success">Debido a la implementacion del Historico de HDR podria tener que volver a dar novedad a la piezas</div>';
            foreach ($subpiezas_normales as $subpieza_normal) {
                //var_dump(count($this->codegen_model->get('flash_piezas_novedades','id','pieza_id = '.$pieza_normal->id)))."<BR/>";
                //Busco si alguna sub_pieza normal no tiene pieza_estado_id en RENDICIONES U ORGANIZATIVO
                if ($subpieza_normal->estado_general_id != PiezaEstado::ESTADOS_RENDICIONES 
                        && $subpieza_normal->estado_general_id != PiezaEstado::ESTADOS_ORGANIZATIVOS
                            || $subpieza_normal->pieza_estado_id = 0) {
                    $baja_HDR            = false;
                    $this->data->mensaje = $this->data->mensaje . ' La pieza ' . $subpieza_normal->pieza_id . ' no tiene cargada una novedad.<br/>';
                    $this->data->baja    = false;
                }
            }
        }
        if ($baja_HDR) {
            $hoja->estado       = Hoja::ESTADO_BAJA;
            $hoja->fecha_baja   = date('Y-m-d');
            $this->data->estado = $hoja->estadoNombre;
            $this->data->fecha  = $hoja->fecha_baja->format('d-m-Y');
            $this->data->mensaje = "";
            $this->data->baja   = $hoja->save();
            
            /* Auditoria 
            $user_row = $this->ion_auth->user()->row();
            $data     = array(
                'user_id'     => $user_row->id,
                'categoria'   => 'HOJAS DE RUTAS',
                'descripcion' => 'Hoja de Ruta: ' . $id . '. Estado: ' . $hoja->estado . '. Baja Hoja de Ruta.',
                'origen'      => '',
                'destino'     => '',
            );
            $this->codegen_model->add('users_log', $data);
            /* END: Auditoria 
        }
        $this->json();
    }*/
    
       public function baja()
    {
        
        
        set_time_limit(1800);
        $id   = $this->input->post('hoja_id');
        $hoja = Hoja::findOrFail($id);
        $subpiezas = Subpieza::where('hoja_ruta_id', '=', $id)->get();
        $subpiezas_simples = " SELECT * 
                                FROM flash_subpiezas sp
                                INNER JOIN flash_piezas p ON p.id = sp.pieza_id
                                left join flash_piezas_estados_variables ev on ev.id = sp.pieza_estado_id
                                WHERE (sp.pieza_estado_id = 0 OR (ev.pieza_estado_id <> ".PiezaEstado::ESTADOS_RENDICIONES. " AND ev.pieza_estado_id <> ".PiezaEstado::ESTADOS_ORGANIZATIVOS. ")) 
                                 AND p.tipo_id = 1
                                 AND sp.hoja_ruta_id =  ".$id;
//echo $subpiezas_simples;die;
        $subpiezas_simples = $this->db->query($subpiezas_simples)->result();

        $subpiezas_normales = " SELECT *, ev.pieza_estado_id estado_general_id
                                FROM flash_subpiezas sp
                                INNER JOIN flash_piezas p ON p.id = sp.pieza_id
                                left join flash_piezas_estados_variables ev on ev.id = sp.pieza_estado_id
                                WHERE (sp.pieza_estado_id = 0 OR ev.pieza_estado_id <> ".PiezaEstado::ESTADOS_RENDICIONES." OR ev.pieza_estado_id <> ".PiezaEstado::ESTADOS_ORGANIZATIVOS. ") 
                                 AND p.tipo_id = 2
                                 AND sp.hoja_ruta_id =  ".$id;

        $subpiezas_normales = $this->db->query($subpiezas_normales)->result();

        $user_row = $this->ion_auth->user()->row();
        //Luego que tengo las subpiezas simples pendientes de novedad (NULL) tiene que marcarse como ENTREGADAS
        if ($subpiezas_simples != null) {
            foreach ($subpiezas_simples as $subpieza_simple) {
                //Busco si alguna pieza simple no tiene novedad la marco como ENTREGADA
                $sql_insert   = "INSERT INTO flash_piezas_novedades (pieza_id, cantidad, usuario_id,  estado_actual_id, estado_nuevo_id)  
                                  VALUES(  $subpieza_simple->pieza_id,
                                        1,
                                        $user_row->id, 
                                            $subpieza_simple->estado_id,".
                                                Pieza::ESTADO_ENTREGADA.")
                                         ON DUPLICATE KEY UPDATE 
                                            estado_actual_id= $subpieza_simple->estado_id , estado_nuevo_id=" . Pieza::ESTADO_ENTREGADA . ",flash_piezas_novedades.update=" . "'" . date('Y-m-d H:m:s') . "'";

                $query_insert = $this->db->query($sql_insert);
                
//                    $data = array(
//                        'pieza_id'         => $subpieza_simple->pieza_id,
//                        //'cantidad' => 1,
//                        'usuario_id'       => $user_row->id,
//                        'estado_actual_id' => $subpieza_simple->estado_id,
//                        'estado_nuevo_id'  => Pieza::ESTADO_ENTREGADA,
//                        //'create' => date('Y-m-d'),
//                        'update'           => date('Y-m-d'),
//                    );
//                    Novedad::updateOrCreate($data);
                    //Tracking
                    $data_tracking = array(
                        'usuario_id'    => $user_row->id,
                        'pieza_id'      => $subpieza_simple->pieza_id,
                        'estado_id'     => Pieza::ESTADO_ENTREGADA,
                        'create' => date("Y-m-d H:i:s"),
                    );
                    $this->codegen_model->add('flash_piezas_tracking', $data_tracking);
                    //Actualizo la tabla piezas
                    $data_pieza = array(
                                    'estado_id' => Pieza::ESTADO_ENTREGADA,
                                );
                    $this->codegen_model->edit('flash_piezas', $data_pieza, 'id', $subpieza_simple->pieza_id);
                    //Actualizo pieza_estado_id en la tabla subpiezas
                    /*$data_subpieza = array(
                                    'pieza_estado_id' => Pieza::ESTADO_ENTREGADA,
                                );
                    $this->codegen_model->edit('flash_subpiezas', $data_subpieza, 'pieza_id', $subpieza_simple->pieza_id);*/
                    $query_update_subpiezas = " UPDATE flash_subpiezas SET pieza_estado_id = ".Pieza::ESTADO_ENTREGADA." 
                                                WHERE pieza_id = ".$subpieza_simple->pieza_id." 
                                                AND hoja_ruta_id =  ".$id;

                    $this->db->query($query_update_subpiezas);
                
            }
        }
        $baja_HDR = true;
        if ($subpiezas_normales != null) {
            $this->data->mensaje = '<div class="alert alert-success">Debido a la implementacion del Historico de HDR podria tener que volver a dar novedad a la piezas</div>';
            foreach ($subpiezas_normales as $subpieza_normal) {
                //var_dump(count($this->codegen_model->get('flash_piezas_novedades','id','pieza_id = '.$pieza_normal->id)))."<BR/>";
                //Busco si alguna sub_pieza normal no tiene pieza_estado_id en RENDICIONES U ORGANIZATIVO
                if ($subpieza_normal->estado_general_id != PiezaEstado::ESTADOS_RENDICIONES 
                        && $subpieza_normal->estado_general_id != PiezaEstado::ESTADOS_ORGANIZATIVOS
                            || $subpieza_normal->pieza_estado_id = 0) {
                    $baja_HDR            = false;
                    $this->data->mensaje = $this->data->mensaje . ' La pieza ' . $subpieza_normal->pieza_id . ' no tiene cargada una novedad.<br/>';
                    $this->data->baja    = false;
                }
            }
        }
        if ($baja_HDR) {
            $hoja->estado       = Hoja::ESTADO_BAJA;
            $hoja->fecha_baja   = date('Y-m-d');
            $this->data->estado = $hoja->estadoNombre;
            $this->data->fecha  = $hoja->fecha_baja->format('d-m-Y');
            $this->data->mensaje = "";
            $this->data->baja   = $hoja->save();
            
            /* Auditoria */
            $user_row = $this->ion_auth->user()->row();
            $data     = array(
                'user_id'     => $user_row->id,
                'categoria'   => 'HOJAS DE RUTAS',
                'descripcion' => 'Hoja de Ruta: ' . $id . '. Estado: ' . $hoja->estado . '. Baja Hoja de Ruta.',
                'origen'      => '',
                'destino'     => '',
            );
            $this->codegen_model->add('users_log', $data);
            /* END: Auditoria */
        }
        $this->json();
    }

    public function editar()
    {
        
        
        if ($this->input->post('enviar_form')) {
            $cantidad    = $this->input->post('cantidad');
            $servicio_id = $this->input->post('servicio_id');
            $hdr_id      = $this->input->post('hoja_ruta_id');
            $numero      = $this->input->post('numero');
            //borro las piezas de la hdr
            $user_row = $this->ion_auth->user()->row();
            $data     = array(
                'user_id'  => $user_row->id,
                'consulta' => "Controller HDR Editar: DELETE FROM flash_subpiezas WHERE hoja_ruta_id = " . $hdr_id,
                'fecha'    => date('Y-m-d H:i:s'));
            $this->codegen_model->add('auditoria', $data);
            $piezas = $this->flash_md->getCantidadPiezasSimplesPorHDR($numero, $hdr_id, $servicio_id);
            for ($i = 0; $i < $cantidad; $i++) {
                $this->codegen_model->delete('flash_subpiezas', 'pieza_id', $piezas[$i]->pieza_id);
                $data = array(
                    'estado_id' => Pieza::ESTADO_EN_GESTION,
                );
                $this->codegen_model->edit('flash_piezas', $data, 'id', $piezas[$i]->pieza_id);
            }
            /* Auditoria */
            $user_row = $this->ion_auth->user()->row();
            $data     = array(
                'user_id'     => $user_row->id,
                'categoria'   => 'HOJAS DE RUTAS',
                'descripcion' => 'Hoja de Ruta: ' . $hdr_id . '. Editar Hoja de Ruta.',
                'origen'      => '',
                'destino'     => '',
            );
            $this->codegen_model->add('users_log', $data);
            /* END: Auditoria */

            $this->session->set_flashdata('registro', $numero);
            $this->session->set_flashdata('cantidad', $cantidad);
            $this->session->set_flashdata('hdr_id', $hdr_id);
            redirect(base_url() . 'hojas_rutas/hojas_rutas/editar');
        }

        $vista_interna = array(
            'permisos_efectivos' => $this->permisos,
        );

        $vista_externa = array(
            'title'          => ucwords("Editar Hojas de Rutas"),
            'contenido_main' => $this->load->view('components/hojas_rutas/hojas_rutas/hojas_rutas_edit', $vista_interna, true),
        );

        $this->load->view('template/backend', $vista_externa);
    }
}

/* End of file hojas_rutas.php */
/* Location: ./system/application/controllers/hojas_rutas.php */
