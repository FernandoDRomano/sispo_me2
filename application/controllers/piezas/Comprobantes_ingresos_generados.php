<?php

class Comprobantes_ingresos_generados extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $vista_interna = array(
            'permisos_efectivos' => $this->permisos,
            'comprobante_generados' => $this->flash_md->getListaComprobantesGenerados(),//ComprobanteGenerado::all()->sortByDesc("create"),
            'responsables' => $this->codegen_model->get('flash_piezas_talonarios_responsables', '*', '')
        );

        $vista_externa = array(
            'title' => ucwords("Generar Etiquetas"),
            'contenido_main' => $this->load->view('components/piezas/comprobantes_ingresos_generados/comprobantes_ingresos_generados_list', $vista_interna, true)
        );

        $this->load->view('template/backend', $vista_externa);
    }

    public function add()
    {
        
        
        if ($this->input->post('etiquetas_total') > 0) {
            $data = array(
                'responsable_id' => $this->input->post('responsable_id'),
                'etiquetas_total' => $this->input->post('etiquetas_total'),
                'etiquetas_disponibles' => $this->input->post('etiquetas_total'),
                'create' => date("Y-m-d H:i:s"),
                'update' => date("Y-m-d H:i:s"),
            );
            $talonario = $this->codegen_model->add('flash_piezas_talonarios',$data);

            for ($i=1; $i <= $this->input->post('etiquetas_total') ; $i++) {
                $data = array('talonario_id' => $talonario, 'create' => date("Y-m-d H:i:s"),'update' => date("Y-m-d H:i:s"),);
                $comprobante = $this->codegen_model->add('flash_comprobantes_ingresos_generados',$data);

                $data = array('numero' => sprintf("%06d", $comprobante).sprintf("%06d", rand(100000, 999999)));
                $this->codegen_model->edit('flash_comprobantes_ingresos_generados',$data,'id',$comprobante);
            }
        }

        redirect(base_url('piezas/comprobantes_ingresos_generados'));
    }

    public function imprimir()
    {
        $comprobante_generado_id = $this->input->post('id');
        $comprobante_generado = ComprobanteGenerado::findOrFail($comprobante_generado_id);

        if($comprobante_generado->estado == ComprobanteGenerado::ESTADO_DISPONIBLE)
        {
            $comprobante_generado->estado = ComprobanteGenerado::ESTADO_IMPRESO;
            $comprobante_generado->save();
        }

        $this->data->estado = ComprobanteGenerado::estadoNombre($comprobante_generado->estado);
        $this->json();
    }

}

/* End of file comprobantes_ingresos_generados.php */
/* Location: ./system/application/controllers/comprobantes_ingresos_generados.php */