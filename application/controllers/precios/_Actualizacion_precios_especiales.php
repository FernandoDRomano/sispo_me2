<?php

class Actualizacion_precios_especiales extends  MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }	
	
    function index(){
         $this->data([
            'results' => $this->flash_md->getActualizarPrecios(5), //5 en la tabla Opciones_valores es Precios Especuakes
            'actualizaciones_pendientes' => $this->codegen_model->get('flash_actualizacion_precios','*','fecha_aprobacion IS NULL 
                                                                                                         AND fecha_rechazo IS NULL
                                                                                                         AND tipo_id = 5 '),
            'actualizaciones_caducas' => $this->codegen_model->get('flash_actualizacion_precios','*','fecha_aprobacion IS NULL 
                                                                                                      AND fecha_rechazo IS NULL
                                                                                                      AND DATEDIFF(CURRENT_DATE(),fecha_creacion) > 7
                                                                                                      AND tipo_id = 5 ')
        ]);
        
        $this->title("Actualizacion precios especiales");
        $this->view = 'components/precios/actualizacion_precios_especiales/actualizacion_precios_especiales_list';
        $this->template('template/backend');
    }
    
    function filtro($cliente_id = null){
        if($cliente_id === null) $cliente_id = $this->input->post('cliente_id');
        $vista_interna = array(
                'permisos_efectivos' => $this->permisos,
                'clientes' => $this->codegen_model->get('flash_clientes','*',''),
        );

        $vista_externa = array(			
                'title' => ucwords("precios especiales"),
                'contenido_main' => $this->load->view('components/precios/actualizacion_precios/actualizacion_precios_list', $vista_interna, true)
        );		

        $this->load->view('template/backend', $vista_externa);
    }
        
    function add(){
        if ($this->input->post('enviar_form')){
            $user_row = $this->ion_auth->user()->row();
            $data = array(
                'usuario_creacion_id' => $user_row->id,
                'tipo_id' => 5,//En la tabla opciones el 5 es el id de Precios Especiales,
                'fecha_creacion' => date('Y-m-d')
            );
            $actualizacion = $this->codegen_model->add('flash_actualizacion_precios', $data);
            $precios_especiales = $this->codegen_model->get('flash_clientes_precios_especiales','*','cliente_id = '.$this->input->post('cliente_id'));
            
            foreach ($precios_especiales as $f) {
                if ($this->input->post('nombre_'.$f->id)) {        			
                    $data = array(
                                    'actualizacion_id' => $actualizacion,
                                    'cliente_precio_especial_id' => $f->id,
                                    'servicio_id' => $f->servicio_id,
                                    'nombre_anterior' => $f->nombre,
                                    'nombre_nuevo' => $this->input->post('nombre_'.$f->id),
                                    'precio_anterior' => $f->precio,
                                    'precio_nuevo' => $this->input->post('precio_'.$f->id),
                                    'dias_anterior' => $f->dias,
                                    'dias_nuevo' => $this->input->post('dias_'.$f->id),
                                    'activo' => $this->input->post('activo_'.$f->id),
                                    'user_id_create' => $user_row->id,
                                    'user_id_update' => NULL,
                                    'date_create' => date('Y-m-d'),
                                    'date_update' => NULL,
                            );
                    $this->codegen_model->add('flash_actualizacion_precios_especiales',$data);
                }
            }  
            redirect(base_url().'precios/actualizacion_precios_especiales');
        }		  

        $vista_interna = array(
                'permisos_efectivos' => $this->permisos,
                'clientes' => $this->codegen_model->get('flash_clientes','*','')
        );

        $vista_externa = array(			
                'title' => ucwords("actualizacion_precios_especiales"),
                'contenido_main' => $this->load->view('components/precios/actualizacion_precios_especiales/actualizacion_precios_especiales_add', $vista_interna, true)
        );		  

        $this->load->view('template/backend', $vista_externa);
    }	

    function edit($id){
        if ($this->input->post('enviar_form')){
            $user_row = $this->ion_auth->user()->row();
            $data = array('update' => date('Y-m-d H:m:s'));
            $this->codegen_model->edit('flash_actualizacion_precios', $data, 'id', $id);
            
            $actualizacion = $this->codegen_model->row('flash_actualizacion_precios', '*', 'id = ' . $id);

            $actualizacion_precios_especiales = $this->codegen_model->get('flash_actualizacion_precios_especiales','*','actualizacion_id = '.$actualizacion->id);

            foreach ($actualizacion_precios_especiales as $f) {
                if ($this->input->post('nombre_'.$f->id)) {        			
                    $data = array(
                                    'nombre_nuevo' => $this->input->post('nombre_'.$f->id),
                                    'precio_nuevo' => $this->input->post('precio_'.$f->id),
                                    'dias_nuevo' => $this->input->post('dias_'.$f->id),
                                    'activo' => $this->input->post('activo_'.$f->id),
                                    'user_id_update' => $user_row->id,
                                    'date_update' => date('Y-m-d'),
                            );
                     $this->codegen_model->edit('flash_actualizacion_precios_especiales',$data,'id',$f->id);
                }
            }  
             redirect(base_url().'precios/actualizacion_precios_especiales');
        }

       $vista_interna = array(
                'permisos_efectivos' => $this->permisos,
                'actualizacion_precios' => $this->codegen_model->row('flash_actualizacion_precios', '*', 'id = ' . $id),
                'results' => $this->flash_md->getActualizarPreciosEspeciales($id),
                'clientes' => $this->codegen_model->get('flash_clientes','*','')
        );

        $vista_externa = array(			
                'title' => ucwords("actualizacion_precios_especiales"),
                'contenido_main' => $this->load->view('components/precios/actualizacion_precios_especiales/actualizacion_precios_especiales_edit', $vista_interna, true)
        );

        $this->load->view('template/backend', $vista_externa);
    }

    function aprobacion($id){
            $user_row = $this->ion_auth->user()->row();
            $data = array(
                'usuario_aprobacion_id' => $user_row->id,
                'fecha_aprobacion' => date('Y-m-d'),
                'update' => date('Y-m-d H:m:s')
            );
            $this->codegen_model->edit('flash_actualizacion_precios', $data, 'id', $id);

            $actualizacion = $this->codegen_model->row('flash_actualizacion_precios', '*', 'id = ' . $id);

            $actualizacion_precios_especiales = $this->codegen_model->get('flash_actualizacion_precios_especiales','*','actualizacion_id = '.$id);
		
            foreach ($actualizacion_precios_especiales as $f) {
                    $data = array(
                            'nombre' => $f->nombre_nuevo,
                            'precio' => $f->precio_nuevo,
                            'dias' => $f->dias_nuevo,
                            'activo' => $f->activo,
                            'update' => date('Y-m-d H:m:s')
                    );
                    $this->codegen_model->edit('flash_clientes_precios_especiales',$data,'id',$f->cliente_precio_especial_id);
            }
            redirect(base_url().'precios/actualizacion_precios_especiales/edit/'.$id);
	}
        
        function rechazo($id) {
            $user_row = $this->ion_auth->user()->row();
            $data = array(
                'usuario_rechazo_id' => $user_row->id,
                'fecha_rechazo' => date('Y-m-d'),
                'update' => date('Y-m-d H:m:s')
            );
            $this->codegen_model->edit('flash_actualizacion_precios', $data, 'id', $id);

            redirect(base_url() . 'precios/actualizacion_precios_especiales/edit/' . $id);
        }
    
	function view($id){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'result' => $this->codegen_model->row('flash_actualizacion_precios_especiales','*','id = '.$id)
		);

		$vista_externa = array(			
			'title' => ucwords("actualizacion_precios_especiales"),
			'contenido_main' => $this->load->view('components/precios/actualizacion_precios_especiales/actualizacion_precios_especiales_view', $vista_interna, true)
		);		  
   
		$this->load->view('template/view', $vista_externa);
	}

	function json($id){
		$json = $this->codegen_model->row('flash_actualizacion_precios_especiales','*','id = '.$id);
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}

	function json_all(){
		$json = $this->codegen_model->get('flash_actualizacion_precios_especiales','*','');
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}
	
	function delete($id){
		$this->codegen_model->delete('flash_actualizacion_precios_especiales','id',$id);             
	}
        
        
    
    private function set_data()
    {

        $disponibles = Pieza::whereEstadoId(Pieza::ESTADO_EN_GESTION)->get();
        $piezas = [];
        $numeros = [];
        foreach ($disponibles as $pieza)
        {
            $numero = new stdClass;
            $numero->id = $pieza->id;
            $numero->barcode = $pieza->barcode;
            $numeros[] = $numero;

            if($pieza->barcode_externo)
            {
                $numero = new stdClass;
                $numero->id = $pieza->id;
                $numero->barcode = $pieza->barcode_externo;
                $numeros[] = $numero;
            }

            $p = new stdClass;
            $p->id = $pieza->id;
            $p->descripcion = $pieza->descripcion;
            $p->tipo = $pieza->tipo_id;
            $p->cantidad = $pieza->cantidad;
            $p->barcode = $pieza->barcode;
            $p->barcode_externo = $pieza->barcode_externo;
            $piezas[$pieza->id] = $p;
        }

        $query = Despacho::where('estado', Despacho::ESTADO_INICIADO)
            ->orWhere('estado', Despacho::ESTADO_CANCELADO);
        if($this->is_user())
        {
            $query->whereOrigenId($this->usuario->sucursal_id);
        }
        $despachos = $query->get();

        $despacho_ids = [];
        foreach ($despachos as $i => $despacho)
        {
            $item = new stdClass;
            $item->id = $i;
            $item->name = $despacho->id;
            $despacho_ids[] = $item;
        }

        $this->data([
            'despachos' => $despachos,
            'despacho_ids' => $despacho_ids,
            'piezas' => $piezas,
            'numeros' => $numeros,
            'transportes' => Transporte::all(),
            'usuarios' => Usuario::all(),
            'sucursales' => $this->is_user() ? Sucursal::where("id", "!=", $this->usuario->sucursal_id)->get() : Sucursal::all(),
        ]);
    }
    

}

/* End of file actualizacion_precios_especiales.php */
/* Location: ./system/application/controllers/actualizacion_precios_especiales.php */