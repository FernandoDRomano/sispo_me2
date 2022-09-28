<?php

class Novedades extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if ($this->input->is_post()) {
            $this->post_index();
        }

        $this->data([
            'estados_iniciales'     => PiezaEstadoVariable::wherePiezaEstadoId(PiezaEstado::ESTADOS_INICIALES)->get(),
            'estados'               => PiezaEstadoVariable::wherePiezaEstadoId(PiezaEstado::ESTADOS_RENDICIONES)->get(),
            'estados_organizativos' => PiezaEstadoVariable::wherePiezaEstadoId(PiezaEstado::ESTADOS_ORGANIZATIVOS)->get(),
            'estados_despachos'     => PiezaEstadoVariable::wherePiezaEstadoId(PiezaEstado::ESTADOS_DESPACHO)->get(),
        ]);

        $this->title("Novedades");
        $this->view = 'components/piezas/piezas_novedades/piezas_novedades';
        $this->template('template/backend');
    }

    public function post_index()
    {
        $piezas = $this->input->post('piezas');
        //Al colocar una pieza en ESTADO ORGANIZATIVO borrarla de la HDR Asignada
        $estados_organizativos       = $this->flash_md->getEstadosVariables(PiezaEstado::ESTADOS_ORGANIZATIVOS); //3 = Organizativos
        $array_estados_organizativos = [];
        //Traigo todos los estados variables que pertenezcan al ESTADO ORGANIZATIVO
        foreach ($estados_organizativos as $value) {
            $array_estados_organizativos[] = $value->id;
        }

        foreach ($piezas as $pieza) {
            $pieza_array = split('-', $pieza);
            $pieza       = Pieza::findOrFail($pieza_array[0]);
            $novedad     = Novedad::wherePiezaId($pieza_array[0])->first();

            if (count($novedad) > 0) {
                $data = array(
                    'id'               => $novedad->id,
                    'usuario_id'       => $this->usuario->id,
                    'pieza_id'         => $pieza_array[0],
                    'cantidad'         => 1,
                    'estado_actual_id' => $pieza->estado_id,
                    'estado_nuevo_id'  => $pieza_array[1],
                    'update'           => date("Y-m-d H:i:s"),
                );
                $this->codegen_model->edit('flash_piezas_novedades', $data, 'id', $novedad->id);
            } else {
                $novedad = Novedad::create([
                    'usuario_id'       => $this->usuario->id,
                    'pieza_id'         => $pieza_array[0],
                    'cantidad'         => 1,
                    'estado_actual_id' => $pieza->estado_id,
                    'estado_nuevo_id'  => $pieza_array[1],
                ]);
            }
            /* Auditoria */
            $user_row = $this->ion_auth->user()->row();
            $data     = array(
                'user_id'     => $user_row->id,
                'categoria'   => 'NOVEDADES',
                'descripcion' => 'Novedad: ' . $novedad->id . '. Pieza: ' . $pieza_array[0] . '. Nuevo Estado Organizativo: ' . $pieza_array[1] . '. Novedades.',
                'origen'      => '',
                'destino'     => '',
            );
            $this->codegen_model->add('users_log', $data);
            /* END: Auditoria */

            //Tracking
            $data_tracking = array(
                'usuario_id' => $this->usuario->id,
                'pieza_id'   => $pieza_array[0],
                'estado_id'  => $pieza_array[1],
                'create' => date("Y-m-d H:i:s"),
                'update' => date("Y-m-d H:i:s"),
            );

            $this->codegen_model->add('flash_piezas_tracking', $data_tracking);
            $pieza->estado_id = $pieza_array[1];
            $pieza->save();
            //Si el estado de la pieza es algun estado ORGANIZATIVO entonces la quito de la HDR anterior
            //Dejandola lista para la proxima HDR a asignarla

            if (in_array($pieza->estado_id, $array_estados_organizativos)) {
                $this->codegen_model->delete('flash_subpiezas', 'pieza_id', $pieza->id);
                //Comento el siguiente codigo porque arriba lo primero que hace es grabar la novedad
                //incluso antes de grabar el nuevo estado en la pieza. Porque aqui vuelve a grabarla? esta sobre escribiendo
                //lo que ya grabo al principio
                /*$data = array(
                'id'               => $novedad->id,
                'usuario_id'       => $this->usuario->id,
                'pieza_id'         => $pieza_array[0],
                'cantidad'         => 1,
                'estado_actual_id' => $pieza->estado_id,
                'estado_nuevo_id'  => $pieza_array[1],//1, esto habia cambiado, si arriba graba un estado organi. a la pieza la novedad nueva tiene que ser tambien igual no EN GESTION
                'update'           => date("Y-m-d H:i:s")
                );
                //var_dump($data);die;
                $this->codegen_model->edit('flash_piezas_novedades', $data, 'id', $novedad->id);*/
                $data = array(
                    'user_id'  => $this->usuario->id,
                    'consulta' => "EstadoOrganizativo " . $pieza->estado_id . " DELETE SUBPIEZAS EN NOVEDADES Delete from flash_subpiezas where pieza_id = " . $pieza->id,
                    'fecha'    => date('Y-m-d H:i:s'));
                $this->codegen_model->add('auditoria', $data);
            }
        }
        $this->session->set_flashdata('registro', 'precesado');
        redirect(base_url('piezas/novedades'));
    }

    public function piezas_simples_novedades()
    {
        if ($this->input->is_post()) {
            $this->grabar_novedades_piezas_simples();
        }

        $this->data([
            'estados_iniciales'     => PiezaEstadoVariable::wherePiezaEstadoId(PiezaEstado::ESTADOS_INICIALES)->get(),
            'estados'               => $this->flash_md->getEstadosPiezasSimples(),
            'estados_organizativos' => PiezaEstadoVariable::wherePiezaEstadoId(PiezaEstado::ESTADOS_ORGANIZATIVOS)->get(),

        ]);

        $this->title("Novedades");
        $this->view = 'components/piezas/piezas_novedades/piezas_simples_novedades';
        $this->template('template/backend');
    }

    public function index_edit($pieza_id = null)
    {
        if ($this->input->is_post()) {
            $this->post_index();
        }

        $this->title("Editar Novedades");
        $this->view = 'components/piezas/piezas_novedades/piezas_novedades_edit';
        $this->template('template/backend');
    }

    public function editNovedadesPiezasNormales($pieza_id)
    {
        if ($this->input->post('enviar_form')) {
            $estado_id = ($this->input->post('estado_id')) ? $this->input->post('estado_id') : null;
            if ($estado_id != null) {
                $update_piezas = " UPDATE flash_piezas SET estado_id = " . $estado_id . " WHERE id = " . $pieza_id;

                $update_novedades = " UPDATE flash_piezas_novedades n
                                        INNER JOIN flash_piezas p ON p.id = n.pieza_id
                                        SET estado_actual_id = p.estado_id,
                                            estado_nuevo_id = " . $estado_id . ",
                                             n.update = '" . date("Y-m-d H:i:s") . "'
                                               WHERE pieza_id = " . $pieza_id;

                $select_tracking = " SELECT MAX(id) tracking_id FROM flash_piezas_tracking WHERE pieza_id = " . $pieza_id;

                $tracking_id = $this->db->query($select_tracking);
                $tracking_id = $tracking_id->row();

                if ($tracking_id->tracking_id != '') {
                    $update_tracking = " UPDATE flash_piezas_tracking SET estado_id = " . $estado_id . " WHERE id = " . $tracking_id->tracking_id;
                    $exe_tracking    = $this->db->query($update_tracking);

                }

                $exe_novedades = $this->db->query($update_novedades);
                $exe_piezas    = $this->db->query($update_piezas);

                /* Auditoria */
                $user_row = $this->ion_auth->user()->row();
                $data     = array(
                    'user_id'     => $user_row->id,
                    'categoria'   => 'NOVEDADES',
                    'descripcion' => 'Pieza: ' . $pieza_id . '. Nuevo estado: ' . $estado_id . '. Editar Novedades Piezas Normales.',
                    'origen'      => '',
                    'destino'     => '',
                );
                $this->codegen_model->add('users_log', $data);
                /* END: Auditoria */

                $this->session->set_flashdata('registroOk', $pieza_id);
                redirect(base_url() . 'piezas/novedades/index_edit/' /*.$pieza_id*/);

            }
        }
        $vista_interna = array(
            'estados_iniciales'     => PiezaEstadoVariable::wherePiezaEstadoId(PiezaEstado::ESTADOS_INICIALES)->get(),
            'estados'               => PiezaEstadoVariable::wherePiezaEstadoId(PiezaEstado::ESTADOS_RENDICIONES)->get(),
            'estados_organizativos' => PiezaEstadoVariable::wherePiezaEstadoId(PiezaEstado::ESTADOS_ORGANIZATIVOS)->get(),
            'estados_despachos'     => PiezaEstadoVariable::wherePiezaEstadoId(PiezaEstado::ESTADOS_DESPACHO)->get(),
            'pieza_id'              => $pieza_id,
        );
        $vista_externa = array(
            'title'          => ucwords("Estados de la pieza"),
            'contenido_main' => $this->load->view('components/piezas/piezas_novedades/piezas_novedades_edit_popup', $vista_interna, true),
        );
        $this->load->view('template/view', $vista_externa);
    }

    public function index_edit_simples()
    {
        if ($this->input->is_post()) {
            $this->post_index();
        }

        $this->title("Editar Novedades");
        $this->view = 'components/piezas/piezas_novedades/piezas_simples_novedades_edit';
        $this->template('template/backend');
    }

    public function editNovedadesPiezasSimples($comprobante_ingreso_id, $servicio_id, $estado_actual_id)
    {
        if ($this->input->post('enviar_form')) {
            $estado_id = ($this->input->post('estado_id')) ? $this->input->post('estado_id') : null;
            if ($estado_id != null) {
                $update_piezas = " UPDATE flash_piezas
                                    SET estado_id = " . $estado_id . "
                                    WHERE comprobante_ingreso_id = " . $comprobante_ingreso_id . "
                                        AND servicio_id = " . $servicio_id . "
                                            AND estado_id = " . $estado_actual_id;

                $user_row = $this->ion_auth->user()->row();
                //OJO IMPORTANTE QUE PIEZA_ID EN NOVEDADES ESTE COMO AGREGADO COMO UNIQUE
                $update_novedades = "INSERT INTO flash_piezas_novedades (pieza_id, cantidad, usuario_id,  estado_actual_id, estado_nuevo_id)
                                        SELECT p.id, 1, " . $user_row->id . ", p.estado_id," . $estado_id . "
                                        FROM  flash_piezas p
                                            JOIN flash_comprobantes_ingresos_servicios cis ON cis.id = p.servicio_id
                                            JOIN flash_piezas_estados_variables ev ON p.estado_id = ev.id
                                             WHERE `p`.`comprobante_ingreso_id` = " . $comprobante_ingreso_id . "
                                             AND `cis`.`id` = " . $servicio_id . "
                                              AND `p`.`tipo_id` = " . Pieza::TIPO_SIMPLE . "
                                                AND `p`.`estado_id` = " . $estado_actual_id . "
                                                 ON DUPLICATE KEY UPDATE estado_actual_id= p.estado_id , estado_nuevo_id=" . $estado_id . ",flash_piezas_novedades.update=" . "'" . date('Y-m-d H:m:s') . "'";

                $exe_novedades = $this->db->query($update_novedades);
                $exe_piezas    = $this->db->query($update_piezas);

                /* Auditoria */
                $user_row = $this->ion_auth->user()->row();
                $data     = array(
                    'user_id'     => $user_row->id,
                    'categoria'   => 'NOVEDADES',
                    'descripcion' => 'Pieza: ' . $pieza_id . '. Nuevo estado: ' . $estado_id . '. Editar Novedades Piezas Simples.',
                    'origen'      => '',
                    'destino'     => '',
                );
                $this->codegen_model->add('users_log', $data);
                /* END: Auditoria */

                $this->session->set_flashdata('registroOk', $comprobante_ingreso_id);
                redirect(base_url() . 'piezas/novedades/index_edit_simples/' /*.$pieza_id*/);

            }
        }
        $vista_interna = array(
            'estados_iniciales'     => PiezaEstadoVariable::wherePiezaEstadoId(PiezaEstado::ESTADOS_INICIALES)->get(),
            'estados'               => PiezaEstadoVariable::wherePiezaEstadoId(PiezaEstado::ESTADOS_RENDICIONES)->get(),
            'estados_organizativos' => PiezaEstadoVariable::wherePiezaEstadoId(PiezaEstado::ESTADOS_ORGANIZATIVOS)->get(),
            'estados_despachos'     => PiezaEstadoVariable::wherePiezaEstadoId(PiezaEstado::ESTADOS_DESPACHO)->get(),

        );
        $vista_externa = array(
            'title'          => ucwords("Estados de la pieza"),
            'contenido_main' => $this->load->view('components/piezas/piezas_novedades/piezas_novedades_edit_simples_popup', $vista_interna, true),
        );
        $this->load->view('template/view', $vista_externa);
    }

}

/* End of file Novedades.php */
/* Location: ./system/application/controllers/Novedades.php */
