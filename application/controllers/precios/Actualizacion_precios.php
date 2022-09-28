<?php

class Actualizacion_precios extends CI_Controller {

	private $permisos;
	
	function __construct() {
		parent::__construct();
		$this->permisos = $this->permisos_lib->control();
		$this->load->model('flash_md');
	}	
	
	function index(){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'results' => $this->flash_md->getActualizarPrecios(6), //6 en la tabla Opciones_valores es Servicios(precios de lista)
                        'clientes' => $this->codegen_model->get('flash_clientes','*',''),
		);

		$vista_externa = array(			
			'title' => ucwords("actualizacion precios"),
			'contenido_main' => $this->load->view('components/precios/actualizacion_precios/actualizacion_precios_list', $vista_interna, true)
		);		
		
		$this->load->view('template/backend', $vista_externa);
	}
        
	function add() {
            if ($this->input->post('enviar_form')) {
                $user_row = $this->ion_auth->user()->row();
                $data = array(
                    'usuario_creacion_id' => $user_row->id,
                    'tipo_id' => 6,//$this->input->post('tipo_id'),
                    'fecha_creacion' => date('Y-m-d')
                );
                $actualizacion = $this->codegen_model->add('flash_actualizacion_precios', $data);
                $servicios = $this->codegen_model->get('flash_servicios', '*', '');
                foreach ($servicios as $f) {
                    if ($this->input->post('input_' . $f->id)) {
                        $data = array(
                            'actualizacion_id' => $actualizacion,
                            'servicio_id' => $f->id,
                            'precio_actual' => $f->precio,
                            'precio_nuevo' => $this->input->post('input_' . $f->id)
                        );
                        $this->codegen_model->add('flash_actualizacion_precios_servicios', $data);
                    }
                }
                redirect(base_url().'precios/actualizacion_precios');
            }

            $vista_interna = array(
                'permisos_efectivos' => $this->permisos,
                'servicios' => $this->codegen_model->get('flash_servicios', '*', ''),
            );

            $vista_externa = array(
                'title' => ucwords("actualizacion precios"),
                'contenido_main' => $this->load->view('components/precios/actualizacion_precios/actualizacion_precios_add', $vista_interna, true)
            );

            $this->load->view('template/backend', $vista_externa);
    }	

    function edit($id) {
        if ($this->input->post('enviar_form')) {
            $data = array('update' => date('Y-m-d H:m:s'));
            $this->codegen_model->edit('flash_actualizacion_precios', $data, 'id', $id);

            $actualizacion = $this->codegen_model->row('flash_actualizacion_precios', '*', 'id = ' . $id);

            $servicios = $this->codegen_model->get('flash_actualizacion_precios_servicios', '*', 'actualizacion_id = ' . $id);

            foreach ($servicios as $f) {
                $data = array(
                    'precio_nuevo' => $this->input->post('input_' . $f->id),
                    'update' => date('Y-m-d H:m:s')
                );
                $this->codegen_model->edit('flash_actualizacion_precios_servicios', $data, 'id', $f->id);
            }

            redirect(base_url() . 'precios/actualizacion_precios/edit/' . $id);
        }

        $vista_interna = array(
            'permisos_efectivos' => $this->permisos,
            'result' => $this->codegen_model->row('flash_actualizacion_precios', '*', 'id = ' . $id),
            //'tipos' => $this->codegen_model->get('opciones_variables', '*', 'opcion_id = 3'),
            'servicios' => $this->flash_md->getActualizarPreciosServicio($id)
        );

        $vista_externa = array(
            'title' => ucwords("actualizacion precios"),
            'contenido_main' => $this->load->view('components/precios/actualizacion_precios/actualizacion_precios_edit', $vista_interna, true)
        );

        $this->load->view('template/backend', $vista_externa);
    }

    function view($id){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'result' => $this->codegen_model->row('flash_actualizacion_precios','*','id = '.$id)
		);

		$vista_externa = array(			
			'title' => ucwords("actualizacion precios"),
			'contenido_main' => $this->load->view('components/precios/actualizacion_precios/actualizacion_precios_view', $vista_interna, true)
		);		  
   
		$this->load->view('template/fancybox', $vista_externa);
	}
	
	function aprobacion($id) {
            $user_row = $this->ion_auth->user()->row();
            $data = array(
                'usuario_aprobacion_id' => $user_row->id,
                'fecha_aprobacion' => date('Y-m-d'),
                'update' => date('Y-m-d H:m:s')
            );
            $this->codegen_model->edit('flash_actualizacion_precios', $data, 'id', $id);

            $actualizacion = $this->codegen_model->row('flash_actualizacion_precios', '*', 'id = ' . $id);

            $servicios = $this->codegen_model->get('flash_actualizacion_precios_servicios', '*', 'actualizacion_id = ' . $id);
            $cont = 0;
            foreach ($servicios as $f) {
                $data = array(
                        'precio' => $f->precio_nuevo,
                        'update' => date('Y-m-d H:m:s')
                    );
                //Los precios de la tabla precios especiales que son iguales a los precios de la tabla servicios
                // son los clintes que tienen PRECIO DE LISTA una vez que se modifica el precio de lista se
                //  modifica el PRECIO en precios especiales
                $servicios_precios_especiales = $this->codegen_model->get('flash_clientes_precios_especiales', '*', 'servicio_id = ' . $f->servicio_id.
                                                                                                                    ' AND precio = '.'"'.$f->precio_actual.'"');
            
                foreach($servicios_precios_especiales as $value){
                    //echo $f->id."  -  ".$value->servicio_id."<br/>"  ;$cont++;
                     $query_update = "UPDATE flash_clientes_precios_especiales pe SET pe.precio = ".$f->precio_nuevo.", pe.update = '".date('Y-m-d H:m:s')
                                    ."' WHERE id = ".$value->id;
                     //echo $query_update."<br/>";
                     $this->db->query($query_update);
                }
              $this->codegen_model->edit('flash_servicios', $data, 'id', $f->servicio_id);
            };
            redirect(base_url() . 'precios/actualizacion_precios/edit/' . $id);
    }
        
	function rechazo($id) {
            $user_row = $this->ion_auth->user()->row();
            $data = array(
                'usuario_rechazo_id' => $user_row->id,
                'fecha_rechazo' => date('Y-m-d'),
                'update' => date('Y-m-d H:m:s')
            );
            $this->codegen_model->edit('flash_actualizacion_precios', $data, 'id', $id);

            redirect(base_url() . 'precios/actualizacion_precios/edit/' . $id);
    }

    function indexActualizacionPreciosPorcentajes(){
	    if ($this->input->post('enviar_form')){
			$data = array(
					'cliente_id' => $this->input->post('cliente_id'),
					'grupo_servicio_id' => $this->input->post('grupo_servicio_id'),
					'servicio_id' => $this->input->post('servicio_id'),
					'nombre' => $this->input->post('nombre'),
					'precio' => $this->input->post('precio'),
					'activo' => $this->input->post('activo')
				);
			$this->codegen_model->add('flash_clientes_precios_especiales',$data);
			redirect(base_url().'clientes/clientes_precios_especiales');
		}		  
   
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'servicios' => $this->codegen_model->get('flash_servicios','*',''),
            'clientes' => $this->codegen_model->get('flash_clientes','*',''),
            'grupos' => $this->codegen_model->get('flash_servicios_grupos','*',''),
			'results' => array(),
			'servicio_id' => 0,
		);

		$vista_externa = array(			
			'title' => ucwords("Actualización Precios"),
			'contenido_main' => $this->load->view('components/precios/actualizacion_precios_porcentajes', $vista_interna, true)
		);		  
   
		$this->load->view('template/backend', $vista_externa);
	}

	function filtroPreciosPorcentajes(){
		
		$servicio_id = $this->input->post('servicio_id');
        $grupo_id = $this->input->post('grupo_id');

        if($grupo_id > 0) $query = $this->db->where('g.id', $grupo_id);
        if($servicio_id > 0) $query = $this->db->where('s.id', $servicio_id);

        $query = $this->db
                        ->select('g.id as grupo_id,g.nombre as grupo, s.id as servicio_id, s.nombre servicio , s.precio')
                        ->join('flash_servicios_grupos g', 'g.id = s.grupo_id')
                        ->order_by('servicio')
                        ->get('flash_servicios s');
        $result = $query->result();

		//echo $cliente_id;
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'results' => $result,
			'grupos' => $this->codegen_model->get('flash_servicios_grupos', 'id, nombre', ''),
			'servicios' => $this->codegen_model->get('flash_servicios', 'id, nombre', ''),
			'clientes' => $this->codegen_model->get('flash_clientes','*',''),
			'servicio_id' => $servicio_id,
			
		);

		$vista_externa = array(			
			'title' => ucwords("Actualización Precios"),
			'contenido_main' => $this->load->view('components/precios/actualizacion_precios_porcentajes', $vista_interna, true)
		);		
		
		$this->load->view('template/backend', $vista_externa);
	}

	function guardarPreciosPorcentajes(){
		
		$servicio_id = $this->input->post("servicio_id");
		$porcentaje = $this->input->post("porcentaje");
		$fecha_vto = new DateTime($this->input->post("fecha_vto"));
		$fecha_vto = $fecha_vto->format('Y-m-d');
		$user_row = $this->ion_auth->user()->row();
		$servicios_id = $this->input->post("servicios_id");

        if ($servicios_id != NULL && $porcentaje != ""){
            foreach ($this->input->post("servicios_id") as $value){
                $servicios_id[] = $value;
            }
            
            $data = array(
                'usuario_creacion_id' => $user_row->id,
                'tipo_id' => 3,//En la tabla opciones el 3 es el id de Servicios,
                'fecha_creacion' => date('Y-m-d')
            );
            
            $actualizacion = $this->codegen_model->add('flash_actualizacion_precios', $data);
            $servicios = $this->codegen_model->get('flash_servicios','*','id  IN ('.implode(",",$servicios_id).')');
            //echo($this->db->last_query());die;  
            
                foreach ($servicios as $f) {
                    //Actualizo la tabla ACTUALIZACION_PRECIOS
                    $precio_nuevo = floatval($f->precio) + (floatval($f->precio) * floatval($porcentaje) / 100);
                        $data = array(
                                        'actualizacion_id' => $actualizacion,
                                        'precio_actual' => $f->precio ,
                                        'precio_nuevo' => $precio_nuevo,
                                        'fecha_vto_anterior' => $f->fecha_vto,
                                        'fecha_vto_nueva' => $fecha_vto,
                                        'create' => date('Y-m-d'),
                                        'update' => NULL,
                                        'create_user_id' => $user_row->id,
                                        'update_user_id' => NULL,

                                );
                        $this->codegen_model->add('flash_actualizacion_precios_servicios',$data);
                    //Actualizo la tabla CLIENTES_PRECIOS_ESPECIALES
                        $data2 = array(
                                'precio' => $precio_nuevo,
                                'fecha_vto' => $fecha_vto,
                                'update_user_id' => $user_row->id,
                                'update' => date('Y-m-d'),
                        );
                        $this->codegen_model->edit('flash_servicios',$data2,'id',$f->id);
                    
                }
            }
            //echo $cliente_id;
            $vista_interna = array(
                'permisos_efectivos' => $this->permisos,
                'servicios' => $this->codegen_model->get('flash_servicios','*',''),
                'clientes' => $this->codegen_model->get('flash_clientes','*',''),
                'grupos' => $this->codegen_model->get('flash_servicios_grupos','*',''),
                'results' => array(),
                'servicio_id' => 0,
            );
        
		$vista_externa = array(			
			'title' => ucwords("precios especiales"),
			'contenido_main' => $this->load->view('components/precios/actualizacion_precios_porcentajes', $vista_interna, true)
		);		
		
		$this->load->view('template/backend', $vista_externa);
	}
}

/* End of file actualizacion_precios.php */
/* Location: ./system/application/controllers/actualizacion_precios.php */