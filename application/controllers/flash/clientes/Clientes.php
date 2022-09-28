<?php

class Clientes extends CI_Controller {

	private $permisos;

	function __construct() {
		parent::__construct();
		$this->permisos = $this->permisos_lib->control();
		$this->load->model('flash_md');
	}

	function index(){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'results' => array(),
			'estados' => $this->codegen_model->get('flash_clientes_estados', 'id, nombre', ''),
			'tipos' => $this->codegen_model->get('flash_clientes_tipos', 'id, nombre', '')
		);

		$vista_externa = array(
			'title' => ucwords("clientes"),
			'contenido_main' => $this->load->view('components/clientes/clientes/clientes_list', $vista_interna, true)
		);

		$this->load->view('template/backend', $vista_externa);
	}

	function filtro(){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'results' => $this->flash_md->getClientes($this->input->post('estado'), $this->input->post('tipo'), $this->input->post('buscar')),
			'estados' => $this->codegen_model->get('flash_clientes_estados', 'id, nombre', ''),
			'tipos' => $this->codegen_model->get('flash_clientes_tipos', 'id, nombre', '')
		);

		$vista_externa = array(
			'title' => ucwords("clientes"),
			'contenido_main' => $this->load->view('components/clientes/clientes/clientes_list', $vista_interna, true)
		);

		$this->load->view('template/backend', $vista_externa);
	}

	function add(){
		if ($this->input->post('enviar_form')){
			$data = array(
				'cliente_estado_id' => $this->input->post('cliente_estado_id'),
				'tipo_cliente_id' => $this->input->post('tipo_cliente_id'),
				'nombre' => $this->input->post('nombre'),
				'nombre_fantasia' => $this->input->post('nombre_fantasia'),
				'flash_iva_id' => $this->input->post('iva_id'),
				'cuit' => $this->input->post('cuit'),
				'domicilio' => $this->input->post('domicilio'),
				'localidad' => $this->input->post('localidad'),
				'provincia' => $this->input->post('provincia'),
				'codigo_postal' => $this->input->post('codigo_postal'),
				'telefonos' => $this->input->post('telefonos'),
				'fecha_ingreso' => $this->input->post('fecha_ingreso') != ""?formatdate($this->input->post('fecha_ingreso')):formatdate(date()),
				'observaciones' => $this->input->post('observaciones'),
				'ejecutivo_comercial' => $this->input->post('ejecutivo_comercial')
			);
			$cliente = $this->codegen_model->add('flash_clientes',$data);
                        
			$data = array(
				'cliente_id' => $cliente,
				'contacto' => $this->input->post('contacto_contacto'),
				'telefonos' => $this->input->post('contacto_telefonos'),
				'emails' => $this->input->post('contacto_emails')
			);
			$this->codegen_model->add('flash_clientes_contactos',$data);

			$data = array(
				'cliente_id' => $cliente,
				'responsable' => $this->input->post('responsable_responsable'),
				'telefonos' => $this->input->post('responsable_telefonos'),
				'emails' => $this->input->post('responsable_emails')
			);
			$this->codegen_model->add('flash_clientes_responsables',$data);
                        
                        $data = array(
				'cliente_id' => $cliente,
				'nombre' => 'Sin departamento',
			);
			$this->codegen_model->add('flash_clientes_departamentos',$data);
//                        $_POST['enviar_form'] = 0;
//                        $this->edit($cliente->id, 'datos_principales');
			redirect(base_url().'clientes/clientes/edit/'.$cliente);
		}

                $query = $this->db
                                    ->select('u.id, u.username')
                                    ->join('users_groups ug', 'ug.user_id = u.id')
                                    ->join('groups g', 'g.id = ug.group_id')
                                    ->where('g.id =', 3)
                                    ->get('users u');
                
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'estados' => $this->codegen_model->get('flash_clientes_estados', '*', ''),
			'tipos' => $this->codegen_model->get('flash_clientes_tipos', '*', ''),
			'provincias' => $this->codegen_model->get('ubicacion_provincias', '*', ''),
                        'iva' => $this->codegen_model->get('flash_iva', '*', ''),
                        'ejecutivo_comercial' => $query->result(),
                    );

		$vista_externa = array(
			'title' => ucwords("clientes"),
			'contenido_main' => $this->load->view('components/clientes/clientes/clientes_add', $vista_interna, true)
		);
		$this->load->view('template/backend', $vista_externa);
	}

        function addPreciosEspeciales($id){
            
            
            
		if ($this->input->post('enviar_form')){// qecho var_dump($_POST);die;
                        $precio_lista = ($this->input->post('precio_lista'))?$this->input->post('precio_lista'):NULL;
                        $nombre_especial_servicio = ($this->input->post('nombre_especial_servicio') != '')?$this->input->post('nombre_especial_servicio'):NULL;
                        $servicio_id = $this->input->post('servicio_id');
                        $cliente_id = $this->input->post('cliente_id');
                        $empresa_id = $this->input->post('empresa_id') != ''?$this->input->post('empresa_id'):0;
                        //Si esta el repcio de lista es porque tiene que tomar el precio del servicio de la tabla Servicios
                        //de lo contrario tomara el precio establecido en el formulario.
                        $precio = 0;
                        if ($precio_lista != NULL){
                            $precio = $this->codegen_model->row('flash_servicios','precio','id = '.$servicio_id);
                            $precio = $precio->precio;
                        }else{
                            $precio = $this->input->post('precio');
                        }
                        //Si el nombre especial vino en el post utilizo ese, de lo contrario
                        //utilizo el nombre especial que viene del formulario
                        if ($nombre_especial_servicio == NULL){
                            $nombre_especial_servicio = $this->codegen_model->row('flash_servicios','nombre','id = '.$servicio_id);
                            $nombre_especial_servicio = $nombre_especial_servicio->nombre;
                        }
                        
			$data = array(
					'cliente_id' => $cliente_id,
                                        'servicio_id' => $servicio_id,
                                        'nombre' => $nombre_especial_servicio,
                                        'precio' => $precio,
                                        'dias' => $this->input->post('dias'),
                                        'empresa_id' => $empresa_id,
                                        'update' => date('Y-m-d H:m:s')
					
				);
//                        echo $cliente_id  ."<br/>  ";
//                        echo $servicio_id."<br/>  ";
//                        echo $nombre_especial_servicio."<br/>  ";
//                        echo $precio."<br/>  ";
//                        echo $this->input->post('dias')."<br/>  ";
//                        echo  date('Y-m-d H:m:s');
                        
			$this->codegen_model->add('flash_clientes_precios_especiales',$data);
			redirect(base_url().'clientes/clientes/edit/'.$cliente_id.'/servicios-asociados');
		}		  
                
		$vista_interna = array(
			//'permisos_efectivos' => $this->permisos,
			'cliente_id' => $id,
			'grupos' => $this->codegen_model->get('flash_servicios_grupos','*',''),
                        'servicios' => $this->codegen_model->get('flash_servicios','*','id NOT IN (SELECT servicio_id FROM flash_clientes_precios_especiales WHERE cliente_id = '.$id.')'),
                        'empresas' => $this->codegen_model->get('flash_empresas', '*', ''),
		);
		$vista_externa = array(			
			'title' => ucwords("precios especiales"),
			'contenido_main' => $this->load->view('components/clientes/clientes/clientes_add_precios_especiales', $vista_interna, true)
		);		  
		$this->load->view('template/view', $vista_externa);
	}
        
	function edit($id,$tab=null){
	    
	    
		if ($this->input->post('enviar_form')){
			$data = array(
					'cliente_estado_id' => $this->input->post('cliente_estado_id'),
					'tipo_cliente_id' => $this->input->post('tipo_cliente_id'),
					'nombre' => $this->input->post('nombre'),
					'nombre_fantasia' => $this->input->post('nombre_fantasia'),
					'flash_iva_id' => $this->input->post('iva_id'),
					'cuit' => $this->input->post('cuit'),
					'domicilio' => $this->input->post('domicilio'),
					'localidad' => $this->input->post('localidad'),
					'provincia' => $this->input->post('provincia'),
					'codigo_postal' => $this->input->post('codigo_postal'),
					'telefonos' => $this->input->post('telefonos'),
					'fecha_ingreso' => formatdate($this->input->post('fecha_ingreso')),
					'observaciones' => $this->input->post('observaciones'),
					'ejecutivo_comercial' => $this->input->post('ejecutivo_comercial'),
					'update' => date('Y-m-d H:m:s')
				);
			$this->codegen_model->edit('flash_clientes',$data,'id',$this->input->post('id'));

			$data = array(
                                'cliente_id' => $this->input->post('id'),
				'contacto' => $this->input->post('contacto_contacto'),
				'telefonos' => $this->input->post('contacto_telefonos'),
				'emails' => $this->input->post('contacto_emails')
			);
                        //Comrpuebo si ya existe o no en la tabla de contactos depende de eso lo agrego o lo actualizo
                        $contacto = $this->codegen_model->row('flash_clientes_contactos','id','cliente_id = '.$this->input->post('id'));
                        
                        if ($contacto == null){
                            $this->codegen_model->add('flash_clientes_contactos',$data);
                        }else{
                            $this->codegen_model->edit('flash_clientes_contactos',$data,'cliente_id',$this->input->post('id'));
                        }
                        
			$data = array(
                                'cliente_id' => $this->input->post('id'),
				'responsable' => $this->input->post('responsable_responsable'),
				'telefonos' => $this->input->post('responsable_telefonos'),
				'emails' => $this->input->post('responsable_emails')
			);
                        $responsable = $this->codegen_model->row('flash_clientes_responsables','id','cliente_id = '.$this->input->post('id'));
                        
                        if ($responsable == null){
                            $this->codegen_model->add('flash_clientes_responsables',$data);
                        }else{
                            $this->codegen_model->edit('flash_clientes_responsables',$data,'cliente_id',$this->input->post('id'));
                        }
                        
			redirect(base_url().'clientes/clientes');
		}

                $query = $this->db
                                    ->select('u.id, u.username')
                                    ->join('users_groups ug', 'ug.user_id = u.id')
                                    ->join('groups g', 'g.id = ug.group_id')
                                    ->where('g.id =', 3)
                                    ->get('users u');

                $query_servicios = $this->db
                                    ->select('pe.cliente_id , pe.id, s.codigo, pe.nombre, pe.precio, pe.dias')
                                    ->from('flash_servicios s')
                                    ->join('flash_clientes_precios_especiales pe', 's.id = pe.servicio_id')
                                    ->where('pe.cliente_id', $id)
                                    ->get();
                
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'result' => $this->codegen_model->row('flash_clientes','*, DATE_FORMAT(fecha_ingreso,"%d-%m-%Y") as fecha_ingreso','id = '.$id),
			'contacto' => $this->codegen_model->row('flash_clientes_contactos','*','cliente_id = '.$id),
			'responsable' => $this->codegen_model->row('flash_clientes_responsables','*','cliente_id = '.$id),
			'estados' => $this->codegen_model->get('flash_clientes_estados', '*', ''),
			'tipos' => $this->codegen_model->get('flash_clientes_tipos', '*', ''),
			'provincias' => $this->codegen_model->get('ubicacion_provincias', '*', ''),
                        'iva' => $this->codegen_model->get('flash_iva', '*', ''),
                        'ejecutivo_comercial' => $query->result(),
                        'servicios' =>$query_servicios->result(),
                        'tab' => $tab);
                         

		$vista_externa = array(
			'title' => ucwords("clientes"),
			'contenido_main' => $this->load->view('components/clientes/clientes/clientes_edit', $vista_interna, true)
		);

		$this->load->view('template/backend', $vista_externa);
	}

	function view($id){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'result' => $this->codegen_model->row('flash_clientes','*','id = '.$id)
		);

		$vista_externa = array(
			'title' => ucwords("clientes"),
			'contenido_main' => $this->load->view('components/clientes/clientes/clientes_view', $vista_interna, true)
		);

		$this->load->view('template/fancybox', $vista_externa);
	}

	function json($id){
		$json = $this->codegen_model->row('flash_clientes','*','id = '.$id);
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}

	function json_all(){
		$json = $this->codegen_model->get('flash_clientes','*','');
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}

	function delete($id){
		$this->codegen_model->delete('flash_clientes','id',$id);
	}
        
        function deleteServicioAsociado($cliente_id,$id,$tab){
		$this->codegen_model->delete('flash_clientes_precios_especiales','id',$id);
                redirect(base_url().'clientes/clientes/edit/'.$cliente_id.'/'.$tab);
	}
}

/* End of file clientes.php */
/* Location: ./system/application/controllers/clientes.php */