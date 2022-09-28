<?php

class Modificar extends CI_Controller
{
    private $permisos;
    public function __construct()
    {
        parent::__construct();
        $this->permisos = $this->permisos_lib->control();
    }
    public function index()
    {
        $piezas = [];
        if ($this->input->is_post('form-piezas')) {
            $piezas = $this->codegen_model->get('flash_piezas','*',' barcode_externo = '.$this->input->post('codigo_barra'));
        }
        $vista_interna = array(
                'permisos_efectivos' => $this->permisos,
                'piezas' =>$piezas,
        );
        
        $vista_externa = array(
            'title'          => ucwords("piezas"),
            'contenido_main' => $this->load->view('components/piezas/piezas/modificar.php', $vista_interna, true),
        );
        $this->load->view('template/backend', $vista_externa);
    }
    public function datos()
    {
        
    }
    
    
    
    
    
    
    public function edit($id)
    {
        
        if ($this->input->post('enviar_form')) {
            $user_row = $this->ion_auth->user()->row();
            $data = array(
                'usuario_id'      => $this->input->post('usuario_id'),
                'servicio_id'     => $this->input->post('servicio_id'),
                'tipo_id'         => $this->input->post('tipo_id'),
                'sucursal_id'     => $this->input->post('sucursal_id'),
                'estado_id'       => $this->input->post('estado_id'),
                'cantidad'        => $this->input->post('cantidad'),
                'barcode'         => $this->input->post('barcode'),
                'barcode_externo' => $this->input->post('barcode_externo'),
                'destinatario'    => $this->input->post('destinatario'),
                'domicilio'       => $this->input->post('domicilio'),
                'codigo_postal'   => $this->input->post('codigo_postal'),
                'localidad'       => $this->input->post('localidad'),
                'vista'           => $this->input->post('vista'),
                'recibio'         => $this->input->post('recibio'),
                'documento'       => $this->input->post('documento'),
                'vinculo'         => $this->input->post('vinculo'),
                'datos_varios'    => $this->input->post('datos_varios'),
                'datos_varios_1'  => $this->input->post('datos_varios_1'),
                'datos_varios_2'  => $this->input->post('datos_varios_2'),
                //'create'          => $this->input->post('create'),
                'update'          => $this->input->post('update'),
                'update_user_id'          => $user_row->id,
                'datos_varios_3'  => $this->input->post('datos_varios_3'),
                'email_dest'  => $this->input->post('email_dest'),
                'celular_dest'  => $this->input->post('celular_dest'),
            );
            $this->codegen_model->edit('flash_piezas', $data, 'id', $this->input->post('id'));
            /* Auditoria */
            
            $data     = array(
                'user_id'     => $user_row->id,
                'categoria'   => 'PIEZAS',
                'descripcion' => 'Pieza: ' . $this->input->post('id') . '. Editar Pieza.',
                'origen'      => '',
                'destino'     => '',
            );
            $this->codegen_model->add('users_log', $data);
            /* END: Auditoria */
            redirect(base_url() . 'piezas');
        }
        $vista_interna = array(
            'permisos_efectivos' => $this->permisos,
            'result'             => $this->codegen_model->row('flash_piezas', '*', 'id = ' . $id),
        );
        $vista_externa = array(
            'title'          => ucwords("piezas"),
            'contenido_main' => $this->load->view('components/piezas/piezas_edit', $vista_interna, true),
        );
        $this->load->view('template/backend', $vista_externa);
    }
    public function view($id)
    {
        $vista_interna = array(
            'permisos_efectivos' => $this->permisos,
            'result'             => $this->codegen_model->row('flash_piezas', '*', 'id = ' . $id),
        );
        $vista_externa = array(
            'title'          => ucwords("piezas"),
            'contenido_main' => $this->load->view('components/piezas/piezas_view', $vista_interna, true),
        );
        $this->load->view('template/view', $vista_externa);
    }
    public function json($id)
    {
        $json = $this->codegen_model->row('flash_piezas', '*', 'id = ' . $id);
        if ($json) {
            echo json_encode($json);
        } else {
            echo json_encode(array('status' => 'none'));
        }
    }
    public function json_all()
    {
        $json = $this->codegen_model->get('flash_piezas', '*', '');
        if ($json) {
            echo json_encode($json);
        } else {
            echo json_encode(array('status' => 'none'));
        }
    }
    public function delete($id)
    {
        $mensaje = "";
        $mensajeOk = "";
        $despachos = $this->codegen_model->get('flash_piezas_despacho_piezas','*' ,'pieza_id = '. $id);
        if (count($despachos) > 0) $mensaje .= "La pieza $id tiene despachos. ";
        $hojas_rutas = $this->codegen_model->get('flash_subpiezas','*', 'pieza_id = '. $id);
        if (count($hojas_rutas) > 0) $mensaje .= "La pieza $id tiene Hojas de Ruta.";
        
        if (count($despachos) == 0 && count($hojas_rutas) == 0){
            $pieza = $this->codegen_model->row('flash_piezas','*', 'id ='. $id);
            $comprobante_servicio = $this->codegen_model->row('flash_comprobantes_ingresos_servicios', '*', 'id='.$pieza->servicio_id);
            $this->codegen_model->delete('flash_piezas_paquetes', 'pieza_id', $id);
            $this->codegen_model->delete('flash_piezas', 'id', $id);
            
            /* Auditoria */
            $user_row = $this->ion_auth->user()->row();
            $data     = array(
                'user_id'     => $user_row->id,
                'categoria'   => 'PIEZAS y PIEZAS PAQUETES',
                'descripcion' => 'Pieza: ' . $id . '. Eliminar Pieza.',
                'origen'      => '',
                'destino'     => '',
            );
            
            $data_update     = array(
                'disponible'     => $comprobante_servicio->disponible + 1,
                'update'   => date("Y-m-d H:i:s"),
                'user_update' => $user_row->id,
            );
            $update_comprobante = " UPDATE flash_comprobantes_ingresos_servicios cis
                                    
                                    SET cis.disponible = ".($comprobante_servicio->disponible + 1)."  
                                     WHERE cis.comprobante_ingreso_id = $comprobante_servicio->comprobante_ingreso_id
                                      AND cis.servicio_id = $comprobante_servicio->servicio_id" ;
            //echo $update_comprobante;die;
            $update = $this->db->query($update_comprobante);
            $this->codegen_model->add('users_log', $data);
            $mensajeOk = " Se elimin贸 la pieza $id";
        /* END: Auditoria */
        }
        $this->session->set_flashdata('mensaje', $mensaje);
        $this->session->set_flashdata('mensajeOk', $mensajeOk);
        redirect(base_url('piezas/piezas/piezas_list'));
    }
    public function marcarPiezasPorComprobante()
    {
        $piezas = array();
        if ($this->input->is_post()) {
            $numero      = trim($this->input->post('numero'));
            $comprobante = $this->codegen_model->row('flash_comprobantes_ingresos', '*', ' numero = ' . $numero);
            if ($comprobante != null) {
                $piezas = $this->codegen_model->get('flash_piezas', '*', ' comprobante_ingreso_id = ' . $comprobante->id);
                foreach ($piezas as $value) {
                    if ($value->barcode_externo != '') {
                        $data = array(
                            'barcode_externo' => "A" . $value->barcode_externo,
                        );
                        $this->codegen_model->edit('flash_piezas', $data, 'id', $value->id);
                        /* Auditoria */
                        $user_row = $this->ion_auth->user()->row();
                        $data     = array(
                            'user_id'     => $user_row->id,
                            'categoria'   => 'PIEZAS',
                            'descripcion' => 'Pieza: ' . $value->id . '. Marcar Piezas Por Comprobantes.',
                            'origen'      => '',
                            'destino'     => '',
                        );
                        $this->codegen_model->add('users_log', $data);
                        /* END: Auditoria */
                    }
                }
                $piezas = $this->codegen_model->get('flash_piezas', '*', ' comprobante_ingreso_id = ' . $comprobante->id);
            } else {
                $this->session->set_flashdata('registro', $numero);
            }
        }
        $vista_interna = array(
            'permisos_efectivos' => $this->permisos,
            'piezas'             => $piezas,
        );
        $vista_externa = array(
            'title'          => ucwords("Marcar Piezas por Comprobante"),
            'contenido_main' => $this->load->view('components/piezas/mantenimiento/marcar_piezas', $vista_interna, true),
        );
        $this->load->view('template/backend', $vista_externa);
    }
    
    public function piezas_list()
    {
        $piezas = [];
        if ($this->input->is_post()) {
            $piezas = $this->codegen_model->get('flash_piezas','*',' id = '.$this->input->post('pieza_id'));
        }
        $vista_interna = array(
                'permisos_efectivos' => $this->permisos,
                'piezas' =>$piezas,
        );
        
        $vista_externa = array(
            'title'          => ucwords("piezas"),
            'contenido_main' => $this->load->view('components/piezas/piezas/piezas_list', $vista_interna, true),
        );
        $this->load->view('template/backend', $vista_externa);
    }
}
/* End of file piezas.php */
/* Location: ./system/application/controllers/piezas.php */
