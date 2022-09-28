<?php

class Novedades extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function grabarPiezaNormal()
    {
        $mensaje    = '';
        $codigo     = (int) $this->input->post('codigo_barra');
        $codigo_str = '"' . $this->input->post('codigo_barra') . '"';
        //$piezas_cargadas = $this->input->post('piezas_ids');
        $buscar_por = $this->input->post('buscar_por');
        //Traigo los datos de la pieza dependiendo si se busca por ID o CODIGO EXTERNO
        if (intval($buscar_por) == 1) {
            $pieza = $this->codegen_model->row('flash_piezas', 'id', 'id = ' . $codigo);
        }

        if (intval($buscar_por) == 2) {
            $pieza = $this->codegen_model->row('flash_piezas', 'id', 'barcode_externo = ' . $codigo_str);
        }

        if ($pieza == null) {
            echo json_encode(array('status' => 'La pieza no existe'));
            return;
        }

        $user_row = $this->ion_auth->user()->row();

        $query = $this->db
            ->select(' s.nombre servicio, (CASE WHEN  p.barcode_externo IS NULL THEN "" ELSE p.barcode_externo END) AS barcode_externo,
                                    p.id pieza_id, p.domicilio domicilio, p.codigo_postal_destino codigo_postal,L.nombre as localidad_destino, p.destinatario')
            ->join('flash_comprobantes_ingresos_servicios cis', 'p.servicio_id = cis.id')
            ->join('flash_servicios s', 's.id = cis.servicio_id')
            ->join('flash_piezas_estados_variables ev', 'p.estado_id = ev.id')
            ->join('ubicacion_localidades L', 'L.id = p.localidad_destino')
            ->where('ev.pieza_estado_id IN (' . PiezaEstado::ESTADOS_INICIALES . ',' . PiezaEstado::ESTADOS_ORGANIZATIVOS . ')')
            ->where('p.sucursal_id = ' . $user_row->sucursal_id)
            ->where('p.tipo_id = ' . Pieza::TIPO_NORMAL)
            ->where('p.id = ' . $pieza->id)
            ->get('flash_piezas p');

        $json = $query->result();
        if ($json) {
            
            
            
            
            array_push($json, $this->add_pieza_normal($_POST, $pieza->id));

            /* Auditoria */
            $user_row = $this->ion_auth->user()->row();
            $data = array(
                'user_id' => $user_row->id,
                'categoria' => 'NOVEDADES',
                'descripcion' => 'Pieza: ' . $pieza->id . '. Novedades Piezas Normales.',
                'origen' => '',
                'destino' => '',
            );
            $this->codegen_model->add('users_log',$data);
            /* END: Auditoria */

            echo json_encode($json);
        } else {
            echo json_encode(array('status' => 'La pieza no esta disponible'));
        }
    }

    public function add_pieza_normal($post, $pieza_id)
    {
        $despacho_id = $post['despacho_id'] != '' ? $post['despacho_id'] : false;
        if (!$despacho_id) {
            //Grabo encabezado
            $data = array(
                'origen_id'         => $this->input->post('origen_id'),
                'destino_id'        => $this->input->post('destino_id'),
                'usuario_origen_id' => $this->input->post('usuario_origen_id'),
                'transporte_id'     => $this->input->post('transporte_id'),
                'fecha_envio'       => formatdate($this->input->post('fecha_envio')),
                'piezas'            => 1,
                'estado'            => $this->input->post('enviar') == 1 ? Despacho::ESTADO_ENVIADO : Despacho::ESTADO_INICIADO,
            );
            $despacho = Despacho::create($data);
        } else {
            $despacho = Despacho::whereId($despacho_id)->first();
        }
        //Grabo la pieza Normal dentro del Despacho
        $data = array(
            "despacho_id" => $despacho->id,
            "pieza_id"    => $pieza_id,
        );
        $this->codegen_model->add('flash_piezas_despacho_piezas', $data);
        $data_pieza = array(
            "estado_id" => Pieza::ESTADO_EN_TRNSITO,
        );
        $this->codegen_model->edit('flash_piezas', $data_pieza, 'id', $pieza_id);

        /* Auditoria */
        $user_row = $this->ion_auth->user()->row();
        $data = array(
            'user_id' => $user_row->id,
            'categoria' => 'NOVEDADES',
            'descripcion' => 'Despacho: ' . $despacho_id .  '. Pieza: ' . $pieza_id . '. Añadir Piezas Normales a Despacho.',
            'origen' => '',
            'destino' => '',
        );
        $this->codegen_model->add('users_log',$data);
        /* END: Auditoria */

        return $despacho->id;
    }

    public function delete_pieza_normal()
    {
        $pieza_id    = $this->input->post('pieza_id');
        $despacho_id = $this->input->post('despacho_id');
        $user_row    = $this->ion_auth->user()->row();
        $data        = array(
            'user_id'  => $user_row->id,
            'consulta' => "Piezas normal quitada del Despacho: DELETE FROM flash_piezas_despacho_piezas WHERE pieza_id = " . $pieza_id,
            'fecha'    => date('Y-m-d H:i:s'));
        $this->codegen_model->add('auditoria', $data);
        $data_pieza = array(
            "estado_id" => Pieza::ESTADO_EN_GESTION,
        );
        $this->codegen_model->edit('flash_piezas', $data_pieza, 'id', $pieza_id);
        $this->codegen_model->delete('flash_piezas_despacho_piezas', 'pieza_id', $pieza_id . ' AND despacho_id = ' . $despacho_id);

        /* Auditoria */
        $user_row = $this->ion_auth->user()->row();
        $data = array(
            'user_id' => $user_row->id,
            'categoria' => 'NOVEDADES',
            'descripcion' => 'Despacho: ' . $despacho_id .  '. Pieza: ' . $pieza_id . '. Eliminar Piezas Normales de Despacho.',
            'origen' => '',
            'destino' => '',
        );
        $this->codegen_model->add('users_log',$data);
        /* END: Auditoria */

        echo json_encode(array("respuesta" => $this->db->affected_rows())); //true: exito, flase: error
    }

    public function grabarPiezasSimples()
    {
        //Si el despacho no existe grabo el encabezado
        $despacho = Despacho::whereId($this->input->post('despacho_id'))->first();
        //echo $hoja->id;DIE;
        if ($despacho->id == '') {
            $data = array(
                'origen_id'         => $this->input->post('origen_id'),
                'destino_id'        => $this->input->post('destino_id'),
                'usuario_origen_id' => $this->input->post('usuario_origen_id'),
                'transporte_id'     => $this->input->post('transporte_id'),
                'fecha_envio'       => formatdate($this->input->post('fecha_envio')),
                'piezas'            => 1,
                'estado'            => $this->input->post('enviar') == 1 ? Despacho::ESTADO_ENVIADO : Despacho::ESTADO_INICIADO,
            );
            $despacho = Despacho::create($data);
        } else {
            $despacho = Despacho::whereId($despacho->id)->first();
        }

        //Con el Despacho creado comienzo a grabar las piezas en la tabla FLASH_PIEZAS_DESPACHO_PIEZAS
        $comprobante_ingreso_numero = $this->input->post('codigo_barra_simple');
        $comprobante_ingreso        = Comprobante::whereNumero($comprobante_ingreso_numero)->first();
        $servicio_id                = $this->input->post('servicio_id');
        $cantidad                   = $this->input->post('cantidad');
        //Agrego la sucursal del usuario porque si bien muestra la cantidad disponible de piezas simples para agregar a un
        //DESPACHO , esta tomando cualquier pieza SIMPLE de un SERVICIO específico de un CI específico, pero de cualquier SUCURSAL
        $user_row    = $this->ion_auth->user()->row();
        $sucursal_id = $user_row->sucursal_id;
        $sql         = "INSERT  INTO flash_piezas_despacho_piezas (despacho_id, pieza_id)
                    SELECT
                            $despacho->id as despacho_id, p.id as pieza_id
                    FROM `flash_piezas` `p`
                    INNER JOIN flash_piezas_estados_variables ev on p.estado_id = ev.id
                    WHERE ev.pieza_estado_id IN (" . PiezaEstado::ESTADOS_INICIALES . ',' . PiezaEstado::ESTADOS_ORGANIZATIVOS . ")
                    AND p.tipo_id = " . PIEZA_TIPO_SIMPLE . "
                     AND p.servicio_id = " . $servicio_id . " " . "
                     AND p.comprobante_ingreso_id = " . $comprobante_ingreso->id . "
                     AND p.sucursal_id = " . $sucursal_id . "
                     LIMIT " . $cantidad;
        //Ejecuto la QUERY de INSERCION
        $query = $this->db->query($sql);

        //Agrego la sucursal del usuario porque si bien muestra la cantidad disponible de piezas simples para agregar a un
        //DESPACHO , esta tomando cualquier pieza SIMPLE de un SERVICIO específico de un CI específico, pero de cualquier SUCURSAL
        //Actualizo el estado de las piezas cargadas a EN TRANSITO
        $sql_update = " UPDATE flash_piezas p
                            INNER JOIN flash_piezas_estados_variables ev on p.estado_id = ev.id
                            INNER JOIN flash_piezas_despacho_piezas pdp on p.id = pdp.pieza_id
                                SET estado_id = " . Pieza::ESTADO_EN_TRNSITO . "
                            WHERE ev.pieza_estado_id IN (" . PiezaEstado::ESTADOS_INICIALES . ',' . PiezaEstado::ESTADOS_ORGANIZATIVOS . ")
                            AND p.tipo_id = " . PIEZA_TIPO_SIMPLE . "
                            AND p.servicio_id = " . $servicio_id . " " . "
                             AND p.comprobante_ingreso_id = " . $comprobante_ingreso->id . "
                             AND p.sucursal_id = " . $sucursal_id . "
                             AND pdp.despacho_id = $despacho->id";

        //Ejecuto la query de update
        // echo $sql_update;die;
        $query = $this->db->query($sql_update);
        //Traigo los datos del despacho guardadao
        $query_despacho_piezas = $this->db
            ->select('pd.id despacho_id, s.nombre servicio, cis.id servicio_id, count(*) cantidad')
            ->join('flash_piezas_despacho_piezas pdp', 'pd.id = pdp.despacho_id')
            ->join('flash_piezas p', 'pdp.pieza_id = p.id')
            ->join('flash_comprobantes_ingresos_servicios cis', 'p.servicio_id = cis.id')
            ->join('flash_servicios s', 'cis.servicio_id = s.id')
            ->where('pd.id = ' . $despacho->id)
            ->where('p.tipo_id = ' . PIEZA_TIPO_SIMPLE)
            ->where('p.servicio_id = ' . $servicio_id)
            ->where('p.comprobante_ingreso_id = ' . $comprobante_ingreso->id)
            ->where('p.sucursal_id = ' . $sucursal_id)
            ->group_by('pdp.despacho_id')
            ->get('flash_piezas_despacho pd');

        $json = $query_despacho_piezas->result();

        if ($json) {
            /* Auditoria */
            $user_row = $this->ion_auth->user()->row();
            $data = array(
                'user_id' => $user_row->id,
                'categoria' => 'NOVEDADES',
                'descripcion' => 'Despacho: ' . $despacho->id . '. Grabar Piezas Simples a Despacho.',
                'origen' => '',
                'destino' => '',
            );
            $this->codegen_model->add('users_log',$data);
            /* END: Auditoria */

            echo json_encode($json);
        } else {
            echo json_encode(array('status' => 'La pieza no esta disponible'));
        }
    }

    public function delete_piezas_simples()
    {

        $servicio_id = $this->input->post('servicio_id');
        $despacho_id = $this->input->post('despacho_id');
        $cantidad    = $this->input->post('cantidad');
        $user_row    = $this->ion_auth->user()->row();

        $sql_update = "UPDATE  flash_piezas p
                    INNER JOIN flash_piezas_despacho_piezas pdp on p.id = pdp.pieza_id
                    SET p.estado_id = " . Pieza::ESTADO_EN_GESTION . "
                     WHERE pdp.despacho_id = " . $despacho_id . "
                     AND p.servicio_id = " . $servicio_id . "
                     AND p.sucursal_id = " . $user_row->sucursal_id;
        $query1 = $this->db->query($sql_update);
        //echo $this->db->last_query();die;
        $sql_delete = "DELETE  pdp
                            FROM flash_piezas_despacho_piezas pdp
                            INNER JOIN flash_piezas p ON p.id = pdp.pieza_id
                            WHERE pdp.despacho_id = " . $despacho_id . "
                             AND p.servicio_id = " . $servicio_id . "
                             AND p.sucursal_id = " . $user_row->sucursal_id;

        $data = array(
            'user_id'  => $user_row->id,
            'consulta' => $sql_delete,
            'fecha'    => date('Y-m-d H:i:s'));
        $this->codegen_model->add('auditoria', $data);
        $respuesta = $this->db->query($sql_delete);

        /* Auditoria */
        $user_row = $this->ion_auth->user()->row();
        $data = array(
            'user_id' => $user_row->id,
            'categoria' => 'NOVEDADES',
            'descripcion' => 'Despacho: ' . $despacho_id . '. Eliminar Piezas Simples de Despacho.',
            'origen' => '',
            'destino' => '',
        );
        $this->codegen_model->add('users_log',$data);
        /* END: Auditoria */

        echo json_encode(array("respuesta" => $this->db->affected_rows())); //true: exito, flase: error
    }

    /*public function traerSimplesDisponiblesPorComprobanteServicio()
    {
        $Comprobante_ingreso = Comprobante::whereNumero($this->input->post('codigo_barra'))->first();
        $servicio_id         = $this->input->post('servicio_id');

        //Traigo las piezas disponibles solo de las que tiene el usuario en su sucursal
        $user_row = $this->ion_auth->user()->row();
        $query    = $this->db->where('p.sucursal_id', $user_row->sucursal_id);

        $query = $this->db
            ->select('COUNT(p.id) cantidad')
            ->join('flash_comprobantes_ingresos_servicios cis', 'cis.id = p.servicio_id ')
            ->join('flash_piezas_estados_variables ev', 'p.estado_id = ev.id ')
            ->where('p.comprobante_ingreso_id = ' . $Comprobante_ingreso->id)
            ->where('cis.id = ' . $servicio_id)
            ->where('p.tipo_id = ' . Pieza::TIPO_SIMPLE)
            ->where('ev.pieza_estado_id IN (' . PiezaEstado::ESTADOS_INICIALES . ',' . PiezaEstado::ESTADOS_ORGANIZATIVOS . ',' . PiezaEstado::ESTADOS_DESPACHO . ')')
            ->get('flash_piezas p');

        $json = $query->result();
        //Traigo las novedades del comprobante y servicio seleccionados
        $query_novedades = $this->db
            ->select('ci.numero, s.nombre servicio, ev.nombre estado, count(*) piezas')
            ->join('flash_piezas p', 'p.id = n.pieza_id ')
            ->join('flash_comprobantes_ingresos ci', 'p.comprobante_ingreso_id = ci.id')
            ->join('flash_comprobantes_ingresos_servicios cis', 'p.servicio_id = cis.id')
            ->join('flash_servicios s', 'cis.servicio_id = s.id')
            ->join('flash_piezas_estados_variables ev', 'ev.id = p.estado_id')
            ->where('p.comprobante_ingreso_id = ' . $Comprobante_ingreso->id)
            ->where('p.servicio_id = ' . $servicio_id)
            ->where('p.tipo_id = ' . Pieza::TIPO_SIMPLE)
            ->group_by('p.estado_id ')
            ->get('flash_piezas_novedades n');

        $json_novedades = $query_novedades->result();
//            echo($this->db->last_query());die;
        //echo($this->db->last_query());die;
        if ($json) {
            echo json_encode(array("cantidad" => $json, "novedades" => $json_novedades));
        } else {
            echo json_encode(array('status' => 'none'));
        }

    }*/
    
    public function traerSimplesDisponiblesPorComprobanteServicio()
    {
        $Comprobante_ingreso = Comprobante::whereNumero($this->input->post('codigo_barra'))->first();
        $servicio_id         = $this->input->post('servicio_id');
        $hdr_id = $this->input->post('hdr_id');
        //Traigo las piezas disponibles solo de las que tiene el usuario en su sucursal
        //10-06-2018. Y que no esten ya RENDIDAS ni LIQUIDADAS
        $user_row = $this->ion_auth->user()->row();
        $query    = $this->db->where('p.sucursal_id', $user_row->sucursal_id);
        if ($hdr_id != "") {$query    = $this->db->where('p.hoja_ruta_id', $hdr_id);}
        
        $query = $this->db
            ->select('COUNT(p.id) cantidad')
            ->join('flash_comprobantes_ingresos_servicios cis', 'cis.id = p.servicio_id ')
            ->join('flash_piezas_estados_variables ev', 'p.estado_id = ev.id ')
            ->where('p.comprobante_ingreso_id = ' . $Comprobante_ingreso->id)
            ->where('cis.id = ' . $servicio_id)
            ->where('p.tipo_id = ' . Pieza::TIPO_SIMPLE)
            ->where('ev.pieza_estado_id IN (' . PiezaEstado::ESTADOS_INICIALES . ',' . PiezaEstado::ESTADOS_ORGANIZATIVOS . ',' . PiezaEstado::ESTADOS_DESPACHO . ')')
            ->get('flash_piezas p');
        //echo $this->db->last_query();die;
        $json = $query->result();
        //Traigo las novedades del comprobante y servicio seleccionados
        $query_novedades = $this->db
            ->select('ci.numero, s.nombre servicio, ev.nombre estado, count(*) piezas')
            ->join('flash_piezas p', 'p.id = n.pieza_id ')
            ->join('flash_comprobantes_ingresos ci', 'p.comprobante_ingreso_id = ci.id')
            ->join('flash_comprobantes_ingresos_servicios cis', 'p.servicio_id = cis.id')
            ->join('flash_servicios s', 'cis.servicio_id = s.id')
            ->join('flash_piezas_estados_variables ev', 'ev.id = p.estado_id')
            ->where('p.comprobante_ingreso_id = ' . $Comprobante_ingreso->id)
            ->where('p.servicio_id = ' . $servicio_id)
            ->where('p.tipo_id = ' . Pieza::TIPO_SIMPLE)
            ->group_by('p.estado_id ')
            ->get('flash_piezas_novedades n');

        $json_novedades = $query_novedades->result();
        //echo($this->db->last_query());die;
        //echo($this->db->last_query());die;
        if ($json) {
            echo json_encode(array("cantidad" => $json, "novedades" => $json_novedades));
        } else {
            echo json_encode(array('status' => 'none'));
        }

    }

/*    public function grabar_novedades_piezas_simples()
    {
        ini_set('memory_limit', '-1');
        $estado_id           = $this->input->post('estado_id');
        $comprobante_numero  = $this->input->post('codigo_barra');
        $comprobante_ingreso = Comprobante::whereNumero($comprobante_numero)->first();
        $servicio_id         = $this->input->post('servicio');
        $cantidad            = $this->input->post('cantidad');
        $hdr_id            = $this->input->post('hdr_id');
        $user_row = $this->ion_auth->user()->row();

        $where_hdr = "";
        if ($hdr_id != ""){ $where_hdr = " AND sp.hoja_ruta_id = ".$hdr_id;}
        
        //OJO IMPORTANTE QUE PIEZA_ID EN NOVEDADES ESTE COMO AGREGADO COMO UNIQUE
        $sql_insert = "INSERT INTO flash_piezas_novedades (pieza_id, cantidad, usuario_id,  estado_actual_id, estado_nuevo_id)
                            SELECT p.id, 1, " . $user_row->id . ", p.estado_id," . $estado_id . "
                            FROM  flash_piezas p
                                JOIN flash_comprobantes_ingresos_servicios cis ON cis.id = p.servicio_id
                                JOIN flash_piezas_estados_variables ev ON p.estado_id = ev.id
                                JOIN flash_subpiezas sp on sp.pieza_id = p.id
                                 WHERE p.sucursal_id = " . $user_row->sucursal_id . "
                                 AND `p`.`comprobante_ingreso_id` = " . $comprobante_ingreso->id . "
                                 AND `cis`.`id` = " . $servicio_id . "
                                  AND `p`.`tipo_id` = " . Pieza::TIPO_SIMPLE . "
                                  AND `ev`.`pieza_estado_id` IN (" . PiezaEstado::ESTADOS_INICIALES . "," . PiezaEstado::ESTADOS_ORGANIZATIVOS . "," . PiezaEstado::ESTADOS_DESPACHO . ")
                                   $where_hdr
                                    LIMIT " . $cantidad . "
                                     ON DUPLICATE KEY UPDATE estado_actual_id= p.estado_id , estado_nuevo_id=" . $estado_id . ",flash_piezas_novedades.update=" . "'" . date('Y-m-d H:i:s') . "'";
        //echo $sql_insert;die;
        $query_insert = $this->db->query($sql_insert);

        $query_update_piezas = "UPDATE flash_piezas p
                                    JOIN flash_comprobantes_ingresos_servicios cis ON cis.id = p.servicio_id
                                    JOIN flash_piezas_estados_variables ev ON p.estado_id = ev.id
                                    JOIN flash_piezas_novedades n ON p.id  = n.pieza_id
                                    JOIN flash_subpiezas sp ON sp.pieza_id = p.id
                                    SET p.estado_id = n.estado_nuevo_id
                                     WHERE p.sucursal_id = " . $user_row->sucursal_id . "
                                     AND `p`.`comprobante_ingreso_id` = " . $comprobante_ingreso->id . "
                                     AND `cis`.`id` = " . $servicio_id . "
                                     AND `p`.`tipo_id` = " . Pieza::TIPO_SIMPLE . "
                                     $where_hdr
                                     AND `ev`.`pieza_estado_id` IN (" . PiezaEstado::ESTADOS_INICIALES . "," . PiezaEstado::ESTADOS_ORGANIZATIVOS . "," . PiezaEstado::ESTADOS_DESPACHO . ")";
        //$sql .= $values . $on_duplicate;
        //echo $query_update_piezas;die;
        $query = $this->db->query($query_update_piezas);

        $query_select_respuesta = $this->db
            ->select('ci.numero, s.nombre servicio, ev.nombre estado, count(*) piezas')
            ->join('flash_piezas p', 'p.id = n.pieza_id ')
            ->join('flash_comprobantes_ingresos ci', 'p.comprobante_ingreso_id = ci.id')
            ->join('flash_comprobantes_ingresos_servicios cis', 'p.servicio_id = cis.id')
            ->join('flash_servicios s', 'cis.servicio_id = s.id')
            ->join('flash_piezas_estados_variables ev', 'ev.id = p.estado_id')
            ->where('p.comprobante_ingreso_id = ' . $comprobante_ingreso->id)
            ->where('p.servicio_id = ' . $servicio_id)
            ->where('p.tipo_id = ' . Pieza::TIPO_SIMPLE)
            ->where('p.sucursal_id = ' . $user_row->sucursal_id)
            ->group_by('p.estado_id ')
            ->get('flash_piezas_novedades n');
        //echo($this->db->last_query());die;
        $json_novedades = $query_select_respuesta->result();
        if ($json_novedades) {
            /* Auditoria 
            $user_row = $this->ion_auth->user()->row();
            $data = array(
                'user_id' => $user_row->id,
                'categoria' => 'NOVEDADES',
                'descripcion' => 'Comprobante Ingreso: ' . $comprobante_ingreso->id . '. Grabar Novedades a Piezas Simples.',
                'origen' => '',
                'destino' => '',
            );
            $this->codegen_model->add('users_log',$data);
            /* END: Auditoria 

            echo json_encode(array("novedades" => $json_novedades));
        } else {
            echo json_encode(array('status' => 'none'));
        }

    }
*/
    public function grabar_novedades_piezas_simples() //como en el server
    {
        
        ini_set('memory_limit', '-1');
        $estado_id           = $this->input->post('estado_id');
        $comprobante_numero  = $this->input->post('codigo_barra');
        $comprobante_ingreso = Comprobante::whereNumero($comprobante_numero)->first();
        $servicio_id         = $this->input->post('servicio');
        $cantidad            = $this->input->post('cantidad');
        $hdr_id            = $this->input->post('hdr_id');
        $user_row = $this->ion_auth->user()->row();

        $where_hdr = "";
        if ($hdr_id != ""){ $where_hdr = " AND sp.hoja_ruta_id = ".$hdr_id;}
        
        //OJO IMPORTANTE QUE PIEZA_ID EN NOVEDADES ESTE COMO AGREGADO COMO UNIQUE
        $sql_insert = "INSERT INTO flash_piezas_novedades (pieza_id, cantidad, usuario_id,  estado_actual_id, estado_nuevo_id)
                            SELECT p.id, 1, " . $user_row->id . ", p.estado_id," . $estado_id . "
                            FROM  flash_piezas p
                                JOIN flash_comprobantes_ingresos_servicios cis ON cis.id = p.servicio_id
                                JOIN flash_piezas_estados_variables ev ON p.estado_id = ev.id
                                JOIN flash_subpiezas sp on sp.pieza_id = p.id
                                 WHERE p.sucursal_id = " . $user_row->sucursal_id . "
                                 AND `p`.`comprobante_ingreso_id` = " . $comprobante_ingreso->id . "
                                 AND `cis`.`id` = " . $servicio_id . "
                                  AND `p`.`tipo_id` = " . Pieza::TIPO_SIMPLE . "
                                  AND `ev`.`pieza_estado_id` IN (" . PiezaEstado::ESTADOS_INICIALES . "," . PiezaEstado::ESTADOS_ORGANIZATIVOS . "," . PiezaEstado::ESTADOS_DESPACHO . ")
                                   $where_hdr
                                    LIMIT " . $cantidad . "
                                     ON DUPLICATE KEY UPDATE estado_actual_id= p.estado_id , estado_nuevo_id=" . $estado_id . ",flash_piezas_novedades.update=" . "'" . date('Y-m-d H:i:s') . "'";
        //echo $sql_insert;die;
        $query_insert = $this->db->query($sql_insert);

        $query_update_piezas = "UPDATE flash_piezas p
                                    JOIN flash_comprobantes_ingresos_servicios cis ON cis.id = p.servicio_id
                                    JOIN flash_piezas_estados_variables ev ON p.estado_id = ev.id
                                    JOIN flash_piezas_novedades n ON p.id  = n.pieza_id
                                    JOIN flash_subpiezas sp ON sp.pieza_id = p.id
                                    SET p.estado_id = n.estado_nuevo_id
                                     WHERE p.sucursal_id = " . $user_row->sucursal_id . "
                                     AND `p`.`comprobante_ingreso_id` = " . $comprobante_ingreso->id . "
                                     AND `cis`.`id` = " . $servicio_id . "
                                     AND `p`.`tipo_id` = " . Pieza::TIPO_SIMPLE . "
                                     $where_hdr
                                     AND `ev`.`pieza_estado_id` IN (" . PiezaEstado::ESTADOS_INICIALES . "," . PiezaEstado::ESTADOS_ORGANIZATIVOS . "," . PiezaEstado::ESTADOS_DESPACHO . ")";
        //$sql .= $values . $on_duplicate;
        //echo $query_update_piezas;die;
        $query = $this->db->query($query_update_piezas);

        //Si la pieza esta em HDR busco la ultima HDR donde esta para grabar el nuevo estado
        if ($hdr_id != ""){
            $query_update_piezas_en_hdr = "UPDATE flash_subpiezas sp
                                            INNER JOIN flash_piezas p ON sp.pieza_id = p.id
                                            JOIN flash_piezas_estados_variables ev ON p.estado_id = ev.id
                                            SET sp.pieza_estado_id = p.estado_id
                                            WHERE sp.hoja_ruta_id = ".$hdr_id."    
                                             AND p.estado_id = " . $estado_id . "           
                                                AND p.hoja_ruta_id = ".$hdr_id." 
                                                    AND `p`.`tipo_id` = " . Pieza::TIPO_SIMPLE ;
        //echo $query_update_piezas_en_hdr;die;
            $query1 = $this->db->query($query_update_piezas_en_hdr);
        }
        $query_select_respuesta = $this->db
            ->select('ci.numero, s.nombre servicio, ev.nombre estado, count(*) piezas')
            ->join('flash_piezas p', 'p.id = n.pieza_id ')
            ->join('flash_comprobantes_ingresos ci', 'p.comprobante_ingreso_id = ci.id')
            ->join('flash_comprobantes_ingresos_servicios cis', 'p.servicio_id = cis.id')
            ->join('flash_servicios s', 'cis.servicio_id = s.id')
            ->join('flash_piezas_estados_variables ev', 'ev.id = p.estado_id')
            ->where('p.comprobante_ingreso_id = ' . $comprobante_ingreso->id)
            ->where('p.servicio_id = ' . $servicio_id)
            ->where('p.tipo_id = ' . Pieza::TIPO_SIMPLE)
            ->where('p.sucursal_id = ' . $user_row->sucursal_id)
            ->group_by('p.estado_id ')
            ->get('flash_piezas_novedades n');
        //echo($this->db->last_query());die;
        $json_novedades = $query_select_respuesta->result();
        if ($json_novedades) {
            /* Auditoria */
            $user_row = $this->ion_auth->user()->row();
            $data = array(
                'user_id' => $user_row->id,
                'categoria' => 'NOVEDADES',
                'descripcion' => 'Comprobante Ingreso: ' . $comprobante_ingreso->id . '. Grabar Novedades a Piezas Simples.',
                'origen' => '',
                'destino' => '',
            );
            $this->codegen_model->add('users_log',$data);
            /* END: Auditoria */

            echo json_encode(array("novedades" => $json_novedades));
        } else {
            echo json_encode(array('status' => 'none'));
        }

    }
    
    

    
	function CURL($method,$url, $data = null, $Bearer = 'abcd') {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		$postjson = json_encode($data);
		if(!empty($data)) {
			switch ($method) {
				case "POST":
					curl_setopt($ch, CURLOPT_POST, true);
					if ($data){
						curl_setopt($ch, CURLOPT_POSTFIELDS, $postjson);
					}
					break;
				case "PUT":
					curl_setopt($ch, CURLOPT_PUT, 1);
					break;
				default:
					if ($data){
						$url = sprintf("%s?%s", $url, http_build_query($data));
					}
			}
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
			$authorization = "Authorization: Bearer abcd"; // Prepare the authorisation token
			curl_setopt($ch, CURLOPT_HTTPHEADER , array("cache-control: no-cache", $authorization));
		} else{
		}
		$result = curl_exec($ch);
		$DataResultado = curl_getinfo($ch);
		$ResultadoDecode = json_decode($result, true);
		if($ResultadoDecode){
			$Respuesta = array_merge($DataResultado,json_decode($result, true));
		}else{
			$result = json_encode(array('json-data' => false));
			$Respuesta = array_merge($DataResultado,json_decode($result, true));
		}
		if( $Respuesta["http_code"] == 200 ){
			curl_close($ch);
			return $Respuesta;
		}else{
			curl_close($ch);
			return $Respuesta;
		}
		
	}
	
	
    function grabar_novedad_pieza_normal(){
            $PHPRespuestaString ="";
            //Trae todas las piezas excepto que esten en estado de rendicion o dentro del los IDS que ya se agregaron
            
            
            $codigo = (int)$this->input->post('codigo_barra');
            $codigo_str = '"'.$this->input->post('codigo_barra').'"';
            $CodigoReal = $this->input->post('codigo_barra');
            
            $estado_id = $this->input->post('estado_id');
            $buscar_por = $this->input->post('buscar_por');
            $user_row = $this->ion_auth->user()->row();
            //Traigo los datos de la pieza dependiendo si se busca por ID o CODIGO EXTERNO
            
            if (intval($buscar_por) == 1) $pieza = $this->codegen_model->get('flash_piezas','id, estado_id','id = '.$codigo.' AND sucursal_id = '.$user_row->sucursal_id);
            if (intval($buscar_por) == 2) $pieza = $this->codegen_model->get('flash_piezas','id','barcode_externo = '.$codigo_str.' AND sucursal_id = '.$user_row->sucursal_id);
            
            //Si la consulta trae mas de un ID de pieza solicita que ingrese el ID de la pieza
            if (count($pieza) > 1){
                echo json_encode(array('status' => 'Existen '.count($pieza)." piezas con el mismo CODIGO de BARRAS"));
                return;
            };
            // echo $this->db->last_query();
            if ($pieza == NULL){
                echo json_encode(array('status' => 'La pieza no esta disponible'));
                return;
            };

            if (count($pieza) == 1){
                $pieza = $pieza[0];
            };
            
            // load URL helper
            $this->load->helper('novedades');
            $respuesta = validar_novedad($pieza->estado_id,$estado_id);

            if ($respuesta == ""){
                
                 /* Auditoria grabar novedades*/
                 $user_row = $this->ion_auth->user()->row();
                 $data = array(
                     'user_id' => $user_row->id,
                     'categoria' => 'NOVEDADES NORMAL',
                     'descripcion' => 'Pieza: ' . $pieza->id . '. Grabar Novedades a Piezas Normales RESPUESTA.' . $respuesta,
                     'origen' => '',
                     'destino' => '',
                 );
                 $this->codegen_model->add('users_log',$data);
                 /* END: Auditoria */
            
            $actualizada = false;
            //Si es que la pieza esta en una HDR busco la ultima HDR en la que está. Para actulizarle el estado
            $sql_get_ultima_hdr = " SELECT sp.id sp_id, sp.create, sp.hoja_ruta_id, sp.pieza_id FROM flash_subpiezas sp
                                    
                                    WHERE sp.pieza_id = $pieza->id
                                     ORDER BY sp.create DESC 
                                     LIMIT 1";
            //echo $sql_get_ultima_hdr;die;
            $ultima_hdr = $this->db->query($sql_get_ultima_hdr)->row();
            
            /* Auditoria grabar novedades*/
            $user_row = $this->ion_auth->user()->row();
            $data = array(
                'user_id' => $user_row->id,
                'categoria' => 'NOVEDADES NORMAL',
                'descripcion' => 'Pieza: ' . $pieza->id . '. esta en HDR.' . $ultima_hdr->sp_id,
                'origen' => '',
                'destino' => '',
            );
            $this->codegen_model->add('users_log',$data);
            /* END: Auditoria */
            
            //Una vez encontrada, actualizo el pieza_estado_id de la pieza dentro de la HDR para que luego se pueda
            //dar de baja a la HDR
            if ($ultima_hdr->sp_id != '' && $ultima_hdr->sp_id != NULL){
                $sql_update_novedad_subpiezas = " UPDATE flash_subpiezas sp
                                                    SET sp.pieza_estado_id = $estado_id
                                                     WHERE sp.id = $ultima_hdr->sp_id ";
                $this->db->query($sql_update_novedad_subpiezas);
                $actualizada = true;
            }

             /* Auditoria grabar novedades*/
               $user_row = $this->ion_auth->user()->row();
               $data = array(
                   'user_id' => $user_row->id,
                   'categoria' => 'NOVEDADES NORMAL actualiza flash_subpiezas',
                   'descripcion' => 'Pieza: ' . $pieza->id . '. actualizada.' . $actualizada,
                   'origen' => '',
                   'destino' => '',
                   'create_user_id' => $user_row->id,
                   'update_user_id' => $user_row->id
               );
               $this->codegen_model->add('users_log',$data);
                //echo $this->db->last_query();die;
               /* END: Auditoria */
               
               
            $query = $this->db
                        ->select('p.id pieza_id, p.domicilio, L.nombre as localidad_destino, p.codigo_postal_destino, ev.nombre estado')
                        ->join('flash_piezas_estados_variables ev', ' p.estado_id = ev.id')
                        ->join('ubicacion_localidades L', 'L.id = p.localidad_destino')
                        ->where('ev.pieza_estado_id <> '.PiezaEstado::ESTADOS_RENDICIONES)
                        ->where('p.id = '.$pieza->id)
                        ->where('p.sucursal_id = '.$user_row->sucursal_id)
                        ->get('flash_piezas p');
            //          echo $this->db->last_query();die;
            
            $json = $query->row();
            
            
            
            
//            echo $this->db->last_query();die;
            if($json){ 
                
                //Inserto o actualizo la novedad de la pieza
                $sql_insert   = "INSERT INTO flash_piezas_novedades (pieza_id, cantidad, usuario_id,  estado_actual_id, estado_nuevo_id)  
                                SELECT p.id, 1, ".$user_row->id.", p.estado_id,".$estado_id. "
                                FROM  flash_piezas p
                                    JOIN flash_comprobantes_ingresos_servicios cis ON cis.id = p.servicio_id
                                    JOIN flash_piezas_estados_variables ev ON p.estado_id = ev.id
                                     WHERE p.sucursal_id = ".$user_row->sucursal_id."
                                     AND p.id = ".$pieza->id."
                                         ON DUPLICATE KEY UPDATE estado_actual_id= p.estado_id , estado_nuevo_id=" . $estado_id . ",flash_piezas_novedades.update=" . "'" . date('Y-m-d H:i:s') . "'";
            
                $query_insert = $this->db->query($sql_insert);
               // echo $this->db->last_query();die;
                //actualizo el estado de la pieza en la tabla Piezas
                $query_update_piezas = "UPDATE flash_piezas p
                                        JOIN flash_comprobantes_ingresos_servicios cis ON cis.id = p.servicio_id
                                        JOIN flash_piezas_estados_variables ev ON p.estado_id = ev.id
                                        JOIN flash_piezas_novedades n ON p.id  = n.pieza_id
                                        SET p.estado_id = n.estado_nuevo_id
                                         WHERE p.id = ".$pieza->id;
                $query = $this->db->query($query_update_piezas);
                
                //Inserto novedad en tabla flash_piezas_tracking
                $sql_insert_tracking   = "INSERT INTO flash_piezas_tracking (pieza_id, usuario_id,  estado_id)  
                                 VALUES ($pieza->id, $user_row->id, $estado_id )";
                $json_insert_tracking = $this->db->query($sql_insert_tracking);
                //$this->escritura($pieza->id,$estado_id,'/home/sispoc5/public_html/application/controllers/ajax/Novedades.php');
                
                
                
                $sql_get_cliente = "
                                    SELECT ci.cliente_id as 'cliente'
                                    FROM flash_piezas as fp
                                    inner join flash_comprobantes_ingresos as ci on fp.comprobante_ingreso_id = ci.id
                                    where fp.id = $pieza->id
                                    LIMIT 1
                                ";
                //echo $sql_get_ultima_hdr;die;
                $clienteid = $this->db->query($sql_get_cliente)->row()->cliente;
                
                //Una vez encontrada, actualizo el pieza_estado_id de la pieza dentro de la HDR para que luego se pueda
                //dar de baja a la HDR
                if ($clienteid == 2288){
                    /* ENVIO DE DATOS EN JSON A LA API DE igalfer  comienza aqui */
                    $Data = array('codigo' => $CodigoReal,'estado_id' => $estado_id,'buscar_por' => $buscar_por,'UserId' => $user_row->id,'Plataforma' => '/home/sispoc5/public_html/application/controllers/ajax/Novedades.php');
                    
                    
                    // Modo Test Sispo , 'Sispo' => 'Sispo'
            		$PHPRespuesta = $this->CURL("POST", "http://igalfer.sppflash.com.ar/api/Ripcord", $Data);
            		if($PHPRespuesta["http_code"] == 200){
            			$PHPRespuestaString = print_r($PHPRespuesta['Respuesta']['Datos'][1]['Extra'],true);
            			//$PHPRespuestaString = print_r($PHPRespuesta,true);
            		}else{
            			$PHPRespuestaString ="Error En Envio:" . print_r($PHPRespuesta,true);
            		}
                }
                if ($clienteid == 627 or $clienteid == 1927){
            		if($estado_id>1){
                        $Data = array('codigo_barra' => $CodigoReal,'estado_id' => $estado_id,'buscar_por' => $buscar_por,'UserId' => $user_row->id);// Modo Test Sispo , 'Sispo' => 'Sispo'
                		//print_r($Data);
                		$PHPRespuesta = $this->CURL("POST", "http://sistema.sppflash.com.ar" ."/api/flame/ApisExternas/AjaxAdministrarEstadosDeSispo.php", $Data);//laravelapis.sppflash.com.ar
                        //print_r($PHPRespuesta);
            		}
                }
                
                
                
                
                // ENVIO DE DATOS EN JSON A LA API DE igalfer  comienza aqui /
            /*
                $Data = array('codigo' => $codigo_str,'estado_id' => $estado_id,'buscar_por' => '$buscar_por','UserId' => $user_row->id);// Modo Test Sispo , 'Sispo' => 'Sispo'
        		$PHPRespuesta = $this->CURL("POST", "http://igalfer.sppflash.com.ar/api/Ripcord", $Data);
        		if($PHPRespuesta["http_code"] == 200){
        			$PHPRespuestaString = print_r($PHPRespuesta['Respuesta']['Datos'][1]['Extra'],true);
        			//$PHPRespuestaString = print_r($PHPRespuesta,true);
        		}else{
        			$PHPRespuestaString ="Error En Envio:" . print_r($PHPRespuesta,true);;
        		}
        		*/
                
                
                
                
                /* Auditoria */
                $user_row = $this->ion_auth->user()->row();
                $data = array(
                    'user_id' => $user_row->id,
                    'categoria' => 'NOVEDADES',
                    'descripcion' => 'Pieza: ' . $pieza->id . '. Grabar Novedades a Piezas Normales.' . $PHPRespuestaString,
                    'origen' => '',
                    'destino' => '',
                );
                $this->codegen_model->add('users_log',$data);
                /* END: Auditoria */
                
                
                echo json_encode($json);
            }else{
                if ($actualizada) {
                    
                }else{
                    echo json_encode(array('status' => 'La pieza no esta disponible'));
                }
            }
            
        /* ENVIO DE DATOS EN JSON A LA API DE DAFITI  comienza aqui */
            
            //comienzo del curl
            /*
                    $curl = curl_init();
                    
                    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
                    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
            
                    curl_setopt_array($curl, array(
                      CURLOPT_URL => "http://boveda.sppflash.com.ar/api/dafiti/envios/?id=".$pieza->id,
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_ENCODING => "",
                      CURLOPT_MAXREDIRS => 10,
                      CURLOPT_TIMEOUT => 30,
                      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                      CURLOPT_CUSTOMREQUEST => "GET",
                    //  CURLOPT_POSTFIELDS => $data,
                    ));
                    
                    $response = curl_exec($curl);
                    $err = curl_error($curl);
                    
                    curl_close($curl);
            */
                    /*
                    if ($err) {
                      echo "cURL Error #:" . $err;
                    } else {
                      echo $response;
                    } 
                    */
            /* ENVIO DE DATOS EN JSON A LA API DE DAFITI  hasta aca  */    
            
            
                }else{
                    echo json_encode(array('respuesta' => $respuesta));
                }
            
            
            
    }


    public function traerNovedadPiezaNormal()
    {
        $codigo     = (int) $this->input->post('codigo_barra');
        $codigo_str = '"' . $this->input->post('codigo_barra') . '"';
        $buscar_por = $this->input->post('buscar_por');
        $user_row       = $this->ion_auth->user()->row();
        //Traigo los datos de la pieza
        //Traigo los datos de la pieza dependiendo si se busca por ID o CODIGO EXTERNO
        if (intval($buscar_por) == 1) {
            $pieza = $this->codegen_model->row('flash_piezas', 'id', 'id = ' . $codigo.' AND sucursal_id = '.$user_row->sucursal_id);
        }

        if (intval($buscar_por) == 2) {
            $pieza = $this->codegen_model->row('flash_piezas', 'id', 'barcode_externo = ' . $codigo_str.' AND sucursal_id = '.$user_row->sucursal_id);
        }


        if ($pieza == null) {
            echo json_encode(array('status' => 'La pieza no existe'));
            return;
        };

        
        $query_sucursal = !$this->ion_auth->in_group(array(1, 2), $user_row->id) ? ' AND p.sucursal_id = ' . $user_row->sucursal_id : '';
        $query          = '  SELECT p.id pieza_id, p.domicilio, L.nombre localidad_destino, p.codigo_postal_destino, ev.nombre estado
                        FROM flash_piezas p
                        INNER JOIN flash_piezas_estados_variables ev ON p.estado_id = ev.id
                        INNER JOIN ubicacion_localidades L ON L.id = p.localidad_destino
                        LEFT JOIN flash_rendiciones_piezas rp ON rp.pieza_id = p.id
                        WHERE rp.pieza_id IS NULL
                        AND p.id = ' . $pieza->id .
            $query_sucursal;

        $json = $this->db->query($query);

        if ($json) {
            echo json_encode(array("novedades" => $json->result()));
        } else {
            echo json_encode(array('status' => 'none'));
        }

    }

    public function traerNovedadesPiezasSimples()
    {
        $Comprobante_ingreso = Comprobante::whereNumero($this->input->post('codigo_barra'))->first();
        $servicio_id         = $this->input->post('servicio_id');

        //Traigo las piezas disponibles solo de las que tiene el usuario en su sucursal
        $user_row = $this->ion_auth->user()->row();
        //$query = $this->db->where('p.sucursal_id', $user_row->sucursal_id);

        //Traigo las novedades del comprobante y servicio seleccionados (quite sucursal))
        $query_novedades = " SELECT ci.id comprobante_ingreso_id, ci.numero, s.nombre servicio,p.servicio_id servicio_id, ev.nombre estado, p.estado_id, count(*) piezas
                                 FROM flash_piezas p
                                 INNER JOIN flash_piezas_novedades n ON p.id = n.pieza_id
                                 INNER JOIN flash_comprobantes_ingresos ci ON p.comprobante_ingreso_id = ci.id
                                 INNER JOIN flash_comprobantes_ingresos_servicios cis ON p.servicio_id = cis.id
                                 INNER JOIN flash_servicios s ON cis.servicio_id = s.id
                                 INNER JOIN flash_piezas_estados_variables ev ON ev.id = p.estado_id
                                 LEFT JOIN flash_rendiciones_piezas rp ON rp.pieza_id = p.id
                                 WHERE rp.pieza_id IS NULL

                                 AND p.tipo_id = " . Pieza::TIPO_SIMPLE . "
                                 AND p.comprobante_ingreso_id = " . $Comprobante_ingreso->id . "
                                 AND p.servicio_id = " . $servicio_id . "
                                  GROUP BY p.estado_id";

        $json_novedades = $this->db->query($query_novedades);
//            echo($this->db->last_query());die;
        //echo($this->db->last_query());die;
        if ($json_novedades) {
            echo json_encode(array("novedades" => $json_novedades->result()));
        } else {
            echo json_encode(array('status' => 'none'));
        }

    }
    
    function escritura($pieza, $estado, $origen){
		$myfile = fopen("registro_log.txt", "a") or die("Unable to open file!");
		$fecha_hora = date('m-d-Y h:i:s a', time());
		$txt = "Pieza: " . $pieza . "\t" . "Estado: " . $estado . "\t" . "Fecha_Hora: " . $fecha_hora . "\t" . "Origen: " . $origen . "\r\n" . "/--------------------------/" . "\r\n";
		fwrite($myfile, $txt);
		fclose($myfile);
	}
}

/* End of file Piezas.php */
/* Location: ./system/application/controllers/Piezas.php */
