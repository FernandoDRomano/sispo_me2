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

    function clientes_contado(){
        $vista_interna = array(
            'permisos_efectivos' => $this->permisos,
            'results' => array(),
            'estados' => $this->codegen_model->get('flash_clientes_estados', 'id, nombre', ''),
            'tipos' => $this->codegen_model->get('flash_clientes_tipos', 'id, nombre', '')
        );

        $vista_externa = array(
            'title' => ucwords("clientes"),
            'contenido_main' => $this->load->view('components/clientes/clientes/clientes_contado_list', $vista_interna, true)
        );

        $this->load->view('template/backend', $vista_externa);
    }

    function clientes_cta_cte(){
        $vista_interna = array(
            'permisos_efectivos' => $this->permisos,
            'results' => array(),
            'estados' => $this->codegen_model->get('flash_clientes_estados', 'id, nombre', ''),
            'tipos' => $this->codegen_model->get('flash_clientes_tipos', 'id, nombre', '')
        );

        $vista_externa = array(
            'title' => ucwords("clientes"),
            'contenido_main' => $this->load->view('components/clientes/clientes/clientes_ctacte_list', $vista_interna, true)
        );

        $this->load->view('template/backend', $vista_externa);
    }

    function filtro($vista = NULL){
                if ($vista != null) $vista = '/'.$vista;
                if ($vista == null) $vista = '/clientes_list';
        $vista_interna = array(
            'permisos_efectivos' => $this->permisos,
            'results' => $this->flash_md->getClientes($this->input->post('estado'), $this->input->post('tipo'), $this->input->post('buscar')),
            'estados' => $this->codegen_model->get('flash_clientes_estados', 'id, nombre', ''),
            'tipos' => $this->codegen_model->get('flash_clientes_tipos', 'id, nombre', ''),
            //'pais' => $this->codegen_model->get('flash_paises'),
            //'provincia' => $this->codegen_model->get(''),
            //'localidad' => $this->codegen_model->get('')
        );

        $vista_externa = array(
            'title' => ucwords("clientes"),
            'contenido_main' => $this->load->view('components/clientes/clientes'.$vista, $vista_interna, true)
        );

        $this->load->view('template/backend', $vista_externa);
    }

    public function pre_liquidacion(){
        $user = $this->ion_auth->user()->row();
        $cliente = $this->codegen_model->row('flash_clientes', '*', 'user_id = ' . $user->id);

        $vista_interna = array(
            'permisos_efectivos' => $this->permisos,
            'results' => array(),
            'departamentos' => $this->codegen_model->get('flash_clientes_departamentos d', '*', 'd.cliente_id = ' . $cliente->id),
            'sucursales' => $this->codegen_model->get('flash_sucursales', '*', ''),
            'cliente' => $cliente
        );

        $vista_externa = array(
            'title' => ucwords("Pre Liquidaciones"),
            'contenido_main' => $this->load->view('components/clientes/clientes_pre_liquidacion/pre_liquidacion', $vista_interna, true)
        );

        $this->load->view('template/backend', $vista_externa);
    }

    function add($vista=null){
        if ($vista != null) $vista = '/'.$vista;

        $user_auth = $this->ion_auth->user()->row();

        if ($this->input->post('enviar_form')){

            //INICIAR TRANSACCION
            $this->db->trans_start();

            //ALTA DEL USUARIO PARA EL CLIENTE
            $username = $this->input->post('username');
            $email    = $this->input->post('email');
            $password = $this->input->post('password');
            $group = array('4'); //GRUPO CLIENTE 4

            $additional_data = array(
                //'sucursal_id' => $this->input->post('sucursal_id'),
                'nombre' => $this->input->post('nombre'),
                'apellido' => ' ',
                'telefono'  => $this->input->post('telefonos'),
                'celular' => '',
                'foto' => '',
                'active' => 1,
                'idioma' => 'spanish',
            );

            $user_id = $this->ion_auth->register($username, $password, $email, $additional_data, $group);
            
            //SI SE CREO EL USUARIO
            if($user_id){

                //OBTENGO LA RUTA DEL ARCHIVO
                if (!empty($_FILES['archivo']['tmp_name'])) {
                    $archivo = $this->archivo_upload('archivo', 'clientes');
                }else{
                    $archivo = '';
                }

                $data = array(
                    //'cliente_estado_id' => $this->input->post('cliente_estado_id'),
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
                    'ejecutivo_comercial' => $this->input->post('ejecutivo_comercial'),
                    //NUEVOS CAMPOS
                    'dni' => $this->input->post('dni'),
                    'altura' => $this->input->post('altura'),
                    'esquina_1' => $this->input->post('esquina_1'),
                    'esquina_2' => $this->input->post('esquina_2'),
                    'piso' => $this->input->post('piso'),
                    'departamento' => $this->input->post('departamento'),
                    'pais' => $this->input->post('pais'),
                    'archivo' => $archivo,
                    'user_id' => $user_id,
                    'create_user_id' => $user_auth->id
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

            }

            //FIN DE LA TRANSACCION
            $this->db->trans_complete();

//                        $_POST['enviar_form'] = 0;
//                        $this->edit($cliente->id, 'datos_principales');
            

            if ($vista == null) $vista = '/clientes_edit';
            //redirect(base_url().'clientes/clientes/edit/'.$cliente.'/null'.$vista);
            redirect(base_url().'clientes/clientes/edit/'.$cliente.'/'.$vista);
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
            'paises' => $this->codegen_model->get('flash_paises', '*', ''),
            'iva' => $this->codegen_model->get('flash_iva', '*', ''),
            'ejecutivo_comercial' => $query->result(),
        );
               
        if ($vista == null) $vista = '/clientes_add';
        
        $vista_externa = array(
            'title' => ucwords("clientes"),
            'contenido_main' => $this->load->view('components/clientes/clientes'.$vista, $vista_interna, true)
        );

        $this->load->view('template/backend', $vista_externa);
    }

    private function archivo_upload($field_name, $carpeta){
		$this->load->library('upload');
		$config['upload_path'] = './uploads/'.$carpeta.'/'; 
		$config['file_name'] = time() . md5(rand()).'.pdf';
		//$config['overwrite'] = TRUE;
		$config["allowed_types"] = 'pdf';
		$this->upload->initialize($config);
		if (!$this->upload->do_upload($field_name)){
		    $error = array('error' => $this->upload->display_errors());
		}

	    return $config['file_name'];
	}

    private function eliminar_archivo_servidor($carpeta, $archivo){
		if(!empty($archivo)){
            //OBTENGO LA RUTA DEL ARCHIVO A ELIMINAR
            $upload_path = './uploads/'. $carpeta .'/' . $archivo;
            
            //ELIMINO EL ARCHIVO
            if(unlink($upload_path)) {
            }
        }
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
                                        'activo' => 1,
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

    function edit($id, $tab = null, $vista = null)
    {
        $cliente = $this->codegen_model->row('flash_clientes', '*', 'id = ' . $id);
        $user_auth = $this->ion_auth->user()->row();

        //echo $id. "  ".$tab."  ".$vista;die;
        if ($vista != null) $vista = '/'.$vista;

        if ($this->input->post('enviar_form')) {

            //INICIAR TRANSACCION
            $this->db->trans_start();

            //OBTENGO LA RUTA DEL ARCHIVO
            if (!empty($_FILES['archivo']['tmp_name'])) {
                $archivo = $this->archivo_upload('archivo', 'clientes');
                $this->eliminar_archivo_servidor('clientes', $cliente->archivo);
            }else{
				$archivo = $cliente->archivo;
			}

            $data = array(
                //'cliente_estado_id' => $this->input->post('cliente_estado_id'),
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
                'flash_paqueteria_tipo_id' => $this->input->post('flash_paqueteria_tipo_id'),
                'update' => date('Y-m-d H:m:s'),
                //NUEVOS CAMPOS
                'dni' => $this->input->post('dni'),
                'altura' => $this->input->post('altura'),
                'esquina_1' => $this->input->post('esquina_1'),
                'esquina_2' => $this->input->post('esquina_2'),
                'piso' => $this->input->post('piso'),
                'departamento' => $this->input->post('departamento'),
                'pais' => $this->input->post('pais'),
                'archivo' => $archivo,
                'update_user_id' => $user_auth->id
            );
            $this->codegen_model->edit('flash_clientes', $data, 'id', $this->input->post('id'));

            //ACTUALIZO EL NOMBRE EN EL USUARIO
            if($this->input->post('cliente_estado_id') == 2){ //2 = INACTIVO
                $data = array(
                    'nombre' => $this->input->post('nombre'),
                    'active' => '0'
                );
            }else{
                $data = array(
                    'nombre' => $this->input->post('nombre'),
                    'active' => '1'
                );
            }
            
            $this->codegen_model->edit('users', $data, 'id', $cliente->user_id);

            $data = array(
                'cliente_id' => $this->input->post('id'),
                'contacto' => $this->input->post('contacto_contacto'),
                'telefonos' => $this->input->post('contacto_telefonos'),
                'emails' => $this->input->post('contacto_emails')
            );
            //Comrpuebo si ya existe o no en la tabla de contactos depende de eso lo agrego o lo actualizo
            $contacto = $this->codegen_model->row('flash_clientes_contactos', 'id', 'cliente_id = ' . $this->input->post('id'));

            if ($contacto == null) {
                $this->codegen_model->add('flash_clientes_contactos', $data);
            } else {
                $this->codegen_model->edit('flash_clientes_contactos', $data, 'cliente_id', $this->input->post('id'));
            }

            $data = array(
                'cliente_id' => $this->input->post('id'),
                'responsable' => $this->input->post('responsable_responsable'),
                'telefonos' => $this->input->post('responsable_telefonos'),
                'emails' => $this->input->post('responsable_emails')
            );
            $responsable = $this->codegen_model->row('flash_clientes_responsables', 'id', 'cliente_id = ' . $this->input->post('id'));

            if ($responsable == null) {
                $this->codegen_model->add('flash_clientes_responsables', $data);
            } else {
                $this->codegen_model->edit('flash_clientes_responsables', $data, 'cliente_id', $this->input->post('id'));
            }

            //FIN DE LA TRANSACCION
            $this->db->trans_complete();

            if ($vista == null) $vista = '';
            redirect(base_url().'clientes/clientes'.$vista);
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
        //echo $this->db->last_query();die;
        $query_parametros = $this->db
            ->select('*')
            ->from('flash_paqueteria_parametros pp')
            ->join('flash_paqueteria_tipos pt', 'pt.id = pp.flash_paqueteria_tipo_id')
            ->where('pp.cliente_id', $id)
            ->get();

        $query_paqueteria_tipos = $this->db
            ->select('*')
            ->from('flash_clientes c')
            ->join('flash_paqueteria_tipos pt', 'pt.id = c.flash_paqueteria_tipo_id')
            ->where('c.id', $id)
            ->get();

        $parametros_select = $query_paqueteria_tipos->row();
        $parametros_select = split(',', $parametros_select->parametros);

        $vista_interna = array(
            'permisos_efectivos' => $this->permisos,
            'result' => $this->codegen_model->row('flash_clientes', '*, DATE_FORMAT(fecha_ingreso,"%d-%m-%Y") as fecha_ingreso', 'id = ' . $id),
            'contacto' => $this->codegen_model->row('flash_clientes_contactos', '*', 'cliente_id = ' . $id),
            'responsable' => $this->codegen_model->row('flash_clientes_responsables', '*', 'cliente_id = ' . $id),
            'estados' => $this->codegen_model->get('flash_clientes_estados', '*', ''),
            'tipos' => $this->codegen_model->get('flash_clientes_tipos', '*', ''),
            'provincias' => $this->codegen_model->get('ubicacion_provincias', '*', ''),
            'localidades' => $this->codegen_model->get('ubicacion_localidades', '*', 'id = ' . $cliente->localidad),
            'paises' => $this->codegen_model->get('flash_paises', '*', ''),
            'user' => $this->codegen_model->row('users', '*', 'id = '. $cliente->user_id),
            'iva' => $this->codegen_model->get('flash_iva', '*', ''),
            'ejecutivo_comercial' => $query->result(),
            'servicios' =>$query_servicios->result(),
            'tab' => $tab,
            'paqueteria_tipos' => $this->codegen_model->get('flash_paqueteria_tipos', '*', ''),
            'paqueteria_parametros' => $query_parametros->result(),
            'paqueteria_tipos_select' => $parametros_select,
        );

        if (null == $vista) {
            $vista = '/clientes_edit';
        }

        $vista_externa = array(
            'title' => ucwords("clientes"),
            'contenido_main' => $this->load->view('components/clientes/clientes'.$vista, $vista_interna, true)
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
        //OBTENGO EL CLIENTE
        $cliente = $this->codegen_model->row('flash_clientes', '*', 'id = ' . $id);
        
        //ELIMINO EL USUARIO - NO SE ELIMINA SE LO INACTIVA
        //$this->ion_auth->delete_user($cliente->user_id);
        $this->codegen_model->edit('users', ['active' => '0'], 'id', $cliente->user_id);

        /*
        if(!empty($cliente->archivo)){
            //OBTENGO LA RUTA DEL ARCHIVO A ELIMINAR
            $upload_path = './uploads/clientes/' . $cliente->archivo;
            
            //ELIMINO EL ARCHIVO
            if(unlink($upload_path)) {
            }
        }
        */
        
        //ELIMINO EL CLIENTE - NO SE ELIMINA SE LO INACTIVA
        //$this->codegen_model->delete('flash_clientes','id',$id); 
        $this->codegen_model->edit('flash_clientes', ['cliente_estado_id' => '2'], 'id', $cliente->id);
    }

    function changeStatus($id){
        //OBTENGO EL CLIENTE
        $cliente = $this->codegen_model->row('flash_clientes', '*', 'id = ' . $id);
        
        //ACTIVAR EL USUARIO
        $this->codegen_model->edit('users', ['active' => '1'], 'id', $cliente->user_id);

        //ACTIVO EL CLIENTE
        $this->codegen_model->edit('flash_clientes', ['cliente_estado_id' => '1'], 'id', $cliente->id);
    }

    function deleteServicioAsociado($cliente_id,$id,$tab){
        $this->codegen_model->delete('flash_clientes_precios_especiales','id',$id);
                redirect(base_url().'clientes/clientes/edit/'.$cliente_id.'/'.$tab);
    }
    
    function addParametrosPaqueteria(){        
        if ($this->input->post('enviar_form')){
                        $user_row = $this->ion_auth->user()->row();
                        $cliente_id = $this->input->post('cliente_id_form');
                        $flash_paqueteria_tipo_id = $this->input->post('paqueteria_tipo_id_form');
                        $parametro_paqueteria = $this->input->post('parametro_paqueteria_form');
                        $valor_parametro = $this->input->post('valor_parametro_form');
                        $tab = $this->input->post('parametro_paqueteria_tab');
                        $rango_min = $this->input->post('rango_min_form');
                        $rango_max = $this->input->post('rango_max_form');
                        
                        $rango = null;
                        //Borro los parametros previos
                        $query_delete = 'DELETE FROM flash_paqueteria_parametros WHERE cliente_id = '.$cliente_id.' AND parametro = "'.$parametro_paqueteria.'"';
                        $this->db->query($query_delete);
//                        echo($this->db->last_query());die;
//            var_dump($_POST);die;
                        if($rango_max != '' && $rango_min != ''){ $rango = $rango_min." ".$rango_max;}
                        
                        $data = array(
                                'cliente_id' => $cliente_id,
                                'flash_paqueteria_tipo_id' => $flash_paqueteria_tipo_id,
                                'parametro' => $rango != null?$rango:$parametro_paqueteria,
                                'valor' => $valor_parametro,
                                'create_user_id' => $user_row->id,
                            );
                        
                        $this->codegen_model->add('flash_paqueteria_parametros',$data);
                        redirect(base_url().'clientes/clientes/edit/'.$cliente_id.'/'.$tab);
        }

        $parametros_paqueteria_results = "  SELECT pt.* 
                                            FROM flash_clientes c
                                            INNER JOIN flash_paqueteria_tipos pt ON pt.id = c.flash_paqueteria_tipo_id
                                            WHERE c.id = $cliente_id" ;
        $parametros_paqueteria = $this->db->query($parametros_paqueteria_results)->row();

        $parametros_array = split(',', $parametros_paqueteria->parametros);
        
        $vista_interna = array(
            'permisos_efectivos' => $this->permisos,
            'parametros_paqueteria' => $parametros_array,
            'cliente_id' => $cliente_id,
            'grupos' => $this->codegen_model->get('flash_servicios_grupos','*',''),
            'servicios' => $this->codegen_model->get('flash_servicios','*','id NOT IN (SELECT servicio_id FROM flash_clientes_precios_especiales WHERE cliente_id = '.$cliente_id.')'),
            'empresas' => $this->codegen_model->get('flash_empresas', '*', ''),
            'tab' => 'parametros-paqueteria',
        );
        $vista_externa = array(
            'title' => ucwords("Parametros de Paqueteria"),
            'contenido_main' => $this->load->view('components/clientes/clientes/paqueteria_parametros/paqueteria_parametros_add', $vista_interna, true)
        );
        $this->load->view('template/view', $vista_externa);
    }
    
    function deleteParametrosPaqueteria(){
        $cliente_id = $this->input->post('cliente_id_form_delete');
        $tab = $this->input->post('parametro_paqueteria_tab');
        $this->codegen_model->delete('flash_paqueteria_parametros','cliente_id',$cliente_id);
        redirect(base_url().'clientes/clientes/edit/'.$cliente_id.'/'.$tab);
    }

    function exportarExcel(){
        
    }
}

/* End of file clientes.php */
/* Location: ./system/application/controllers/clientes.php */