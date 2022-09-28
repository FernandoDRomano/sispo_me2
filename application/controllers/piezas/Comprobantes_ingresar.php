<?php

 use Illuminate\Database\Capsule\Manager as DB;

class Comprobantes_ingresar extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if($this->input->is_post())
        {
            $numero = trim($this->input->post('numero'));
            $query = ComprobanteGenerado::whereNumero($numero)->whereEstado(ComprobanteGenerado::ESTADO_IMPRESO);
            if($comprobante = $query->first())
            {
                redirect(base_url('piezas/comprobantes_ingresar/alta/'.$comprobante->id));
            }
            else
            {
                $this->session->set_flashdata('registro', $numero);
            }
        }

        $this->title('comprobantes de ingresos');
        $this->view('components/piezas/comprobantes_ingresar/comprobantes_ingresar_comprobar');
        $this->template('template/backend');
    }

    public function alta($id)
    {
        $this->data([
            'numeros' => ComprobanteGenerado::findOrFail($id),
            'empresas' => Empresa::all(),
            'sucursales' => $this->is_user() ? Sucursal::whereId($this->usuario->sucursal_id)->get() : Sucursal::all(),
            'clientes' => Cliente::select('id', DB::raw('CONCAT(nombre," (",nombre_fantasia,")") as name'))->where('cliente_estado_id',Cliente::ESTADO_ACTIVO)->get(),
            'servicios' => Servicio::all(),
            'departamentos' => ClienteDepartamento::all(),
        ]);

        $this->title('comprobantes de ingresos');
        $this->view('components/piezas/comprobantes_ingresar/comprobantes_ingresar_list');
        $this->template('template/backend');
    }

    public function add()
    {
        
        
        $cantidad = 0;
        $etiqueta = $this->codegen_model->row('flash_comprobantes_ingresos_generados', '*', 'id = '.$this->input->post('numero_id'));
        $talonario = $this->codegen_model->row('flash_piezas_talonarios', '*', 'id = '.$etiqueta->talonario_id);

        $data = array(
            'talonario_id' => $talonario->id,
            'empresa_id' => $this->input->post('empresa_id'),
            'sucursal_id' => $this->input->post('sucursal_id'),
            'cliente_id' => $this->input->post('cliente_id'),
            'departamento_id' => $this->input->post('departamento_id'),
            'numero' => $this->input->post('numero'),
            'estado' => 0,
            'estado_liquidacion' => 0,
            'fecha_pedido' => date('Y-m-d'),
            'create'  => date("Y-m-d H:i:s"),
            'update' => date("Y-m-d H:i:s"),
        );
        $comprobante = $this->codegen_model->add('flash_comprobantes_ingresos',$data);
        $data = array('etiquetas_disponibles' => $talonario->etiquetas_disponibles - 1);
        $this->codegen_model->edit('flash_piezas_talonarios',$data,'id',$talonario->id);

        /* Auditoria */
        $user_row = $this->ion_auth->user()->row();
        $data = array(
            'user_id' => $user_row->id,
            'categoria' => 'COMPROBANTES',
            'descripcion' => 'Nuevo Comprobante: ' . $comprobante,
            'origen' => '',
            'destino' => '',
        );
        $this->codegen_model->add('users_log',$data);
        /* END: Auditoria */

        if($this->input->post('cantidad_servicios') > 0)
        {
            for($i=1; $i <= $this->input->post('cantidad_servicios'); $i++)
            {
                if($this->input->post('servicio_'.$i))
                {
                    $data = array(
                        'comprobante_ingreso_id' => $comprobante,
                        'servicio_id' => $this->input->post('servicio_'.$i),
                        'cantidad' => $this->input->post('cantidad_'.$i),
                        'disponible' => $this->input->post('cantidad_'.$i),
                        'remito' => $this->input->post('remito_'.$i)!=''?$this->input->post('remito_'.$i):0,
                        'create' => date("Y-m-d H:i:s"),
                        'update' => date("Y-m-d H:i:s"),
                    );
                    $this->codegen_model->add('flash_comprobantes_ingresos_servicios',$data);
//                    echo $this->db->last_query()."<br/>";
                    $cantidad = $cantidad + $this->input->post('cantidad_'.$i);
                }
            }
        }

        $data = array('cantidad' => $cantidad);
        $this->codegen_model->edit('flash_comprobantes_ingresos',$data,'id',$comprobante);
//        echo $this->db->last_query()."<br/>";
        $comprobante_generado = ComprobanteGenerado::whereNumero($this->input->post('numero'))->firstOrFail();
        $comprobante_generado->estado = ComprobanteGenerado::ESTADO_USADO;
        $comprobante_generado->save();
//        echo $this->db->last_query()."<br/>";die;
        $this->session->set_flashdata('registroOK', $comprobante_generado->numero);

        redirect(base_url('piezas/comprobantes_ingresar'));
    }
}

/* End of file comprobantes_ingresos.php */
/* Location: ./system/application/controllers/comprobantes_ingresos.php */