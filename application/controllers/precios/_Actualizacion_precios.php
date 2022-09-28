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
}

/* End of file actualizacion_precios.php */
/* Location: ./system/application/controllers/actualizacion_precios.php */