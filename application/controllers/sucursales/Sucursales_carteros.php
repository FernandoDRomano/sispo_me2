<?php

class Sucursales_carteros extends CI_Controller {

	private $permisos;
	
	function __construct() {
		parent::__construct();
		$this->permisos = $this->permisos_lib->control();
		$this->load->model('flash_md');
	}	
	
	function index(){
            $user_row = $this->ion_auth->user()->row();
            if (!$this->ion_auth->in_group(array(1, 2), $user_row->id)) $sucursales = $this->codegen_model->get('flash_sucursales', 'id, nombre', 'id = '.$user_row->sucursal_id);        
            else $sucursales = $this->codegen_model->get('flash_sucursales', 'id, nombre', '');
            
            $carteros_tipos = $this->codegen_model->get('flash_sucursales_carteros_tipos', 'id, nombre', '');
            
            $vista_interna = array(
                    'permisos_efectivos' => $this->permisos,
                    'results' => $this->flash_md->getCarteros(),
                    'sucursales' => $sucursales,
                    'carteros_tipos' => $carteros_tipos
            );

            $vista_externa = array(			
                    'title' => ucwords("carteros"),
                    'contenido_main' => $this->load->view('components/sucursales/sucursales_carteros/sucursales_carteros_list', $vista_interna, true)
            );

            $this->load->view('template/backend', $vista_externa);
	}

    function filtro(){
        $user_row = $this->ion_auth->user()->row();
        if (!$this->ion_auth->in_group(array(1, 2), $user_row->id)) $sucursales = $this->codegen_model->get('flash_sucursales', 'id, nombre', 'id = '.$user_row->sucursal_id);        
        else $sucursales = $this->codegen_model->get('flash_sucursales', 'id, nombre', '');
        
        $carteros_tipos = $this->codegen_model->get('flash_sucursales_carteros_tipos', 'id, nombre', '');
        
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'results' => $this->flash_md->getCarteros($this->input->post('sucursal'), $this->input->post('buscar'), $this->input->post('cartero_tipo_id')),
			'sucursales' => $sucursales,
            'carteros_tipos' => $carteros_tipos
		);

		$vista_externa = array(			
			'title' => ucwords("carteros"),
			'contenido_main' => $this->load->view('components/sucursales/sucursales_carteros/sucursales_carteros_list', $vista_interna, true)
		);
		
		$this->load->view('template/backend', $vista_externa);
	}

	function add(){
        $user_row = $this->ion_auth->user()->row();
        if (!$this->ion_auth->in_group(array(1, 2), $user_row->id)) $sucursales = $this->codegen_model->get('flash_sucursales', 'id, nombre', 'id = '.$user_row->sucursal_id);        
        else $sucursales = $this->codegen_model->get('flash_sucursales', 'id, nombre', '');

		if ($this->input->post('enviar_form')){
		    
			//VALIDAR QUE NO EXISTAN DOS CARTERO CON EL MISMO NOMBRE APELLIDO
		    $this->db->select('*');
            $this->db->from('flash_sucursales_carteros');
            $this->db->where('flash_sucursales_carteros.apellido_nombre = ', $this->input->post('apellido_nombre'));
            $q = $this->db->get();
            if(count($q->result())>0){
        		$vista_interna = array(
        			'permisos_efectivos' => $this->permisos,
        			'MSJ' => 'Apellido nombre ('. $q->result()[0]->apellido_nombre . ') existente. No se puede generar otro cartero con el mismo Apellido nombre.',
                    'carteros_tipos' => $this->codegen_model->get('flash_sucursales_carteros_tipos','*',''),
					'paises' => $this->codegen_model->get('flash_paises', '*', ''),
            		'iva' => $this->codegen_model->get('flash_iva', '*', ''),
        		);
        		$vista_externa = array(			
        			'title' => ucwords("carteros"),
        			'contenido_main' => $this->load->view('components/sucursales/sucursales_carteros/sucursales_carteros_add', $vista_interna, true)
        		);		  
        		$this->load->view('template/backend', $vista_externa);
                return;
            }
            
			//VALIDAR QUE NO EXISTAN DOS CARTEROS CON EL MISMO EMAIL
		    $this->db->select('*');
            $this->db->from('flash_sucursales_carteros');
            $this->db->where('flash_sucursales_carteros.email = ', $this->input->post('email'));
            $q = $this->db->get();
            if(count($q->result())>0){
        		$vista_interna = array(
        			'permisos_efectivos' => $this->permisos,
        			'MSJ' => 'Email ('. $q->result()[0]->email . ') existente. No se puede generar otro cartero con el mismo Email.',
                    'carteros_tipos' => $this->codegen_model->get('flash_sucursales_carteros_tipos','*',''),
					'paises' => $this->codegen_model->get('flash_paises', '*', ''),
            		'iva' => $this->codegen_model->get('flash_iva', '*', ''),
        		);
        		$vista_externa = array(			
        			'title' => ucwords("carteros"),
        			'contenido_main' => $this->load->view('components/sucursales/sucursales_carteros/sucursales_carteros_add', $vista_interna, true)
        		);		  
        		$this->load->view('template/backend', $vista_externa);
                return;
            }

			//VALIDAR QUE NO EXISTAN DOS CARTEROS CON EL MISMO DNI_CUIT_CUIL
		    $this->db->select('*');
            $this->db->from('flash_sucursales_carteros');
            $this->db->where('flash_sucursales_carteros.dni_cuit_cuil = ', $this->input->post('dni_cuit_cuil'));
            $q = $this->db->get();
            if(count($q->result())>0){
        		$vista_interna = array(
        			'permisos_efectivos' => $this->permisos,
        			'MSJ' => 'DNI/CUIT/CUIL ('. $q->result()[0]->dni_cuit_cuil . ') existente. No se puede generar otro cartero con el mismo DNI/CUIT/CUIL.',
                    'carteros_tipos' => $this->codegen_model->get('flash_sucursales_carteros_tipos','*',''),
					'paises' => $this->codegen_model->get('flash_paises', '*', ''),
            		'iva' => $this->codegen_model->get('flash_iva', '*', ''),
        		);
        		$vista_externa = array(			
        			'title' => ucwords("carteros"),
        			'contenido_main' => $this->load->view('components/sucursales/sucursales_carteros/sucursales_carteros_add', $vista_interna, true)
        		);		  
        		$this->load->view('template/backend', $vista_externa);
                return;
            }

			//OBTENGO LA RUTA DE LA IMAGEN DE AFIP
			if (!empty($_FILES['afip_imagen']['tmp_name'])) {
				$afip_imagen = $this->image_upload('afip_imagen', 'carteros');
			}else{
				$afip_imagen = '';
			}

			//OBTENGO LA RUTA DE LA IMAGEN DEL CARNET DE CONDUCIR
			if (!empty($_FILES['carnet_conductor_imagen']['tmp_name'])) {
				$carnet_conductor_imagen = $this->image_upload('carnet_conductor_imagen', 'carteros');
			}else{
				$carnet_conductor_imagen = '';
			}


			$data = array(
					'sucursal_id' => $this->input->post('sucursal_id'),
					'apellido_nombre' => $this->input->post('apellido_nombre'),
					'domicilio' => $this->input->post('domicilio'),
					'localidad' => $this->input->post('localidad'),
					'codigo_postal' => $this->input->post('codigo_postal'),
					'telefono' => $this->input->post('telefono'),
					'cartero_tipo_id' => $this->input->post('cartero_tipo_id'),
					'productividad' => $this->input->post('productividad') != "" ? $this->input->post('productividad') : 0,
					'observaciones' => $this->input->post('observaciones'),
                    'create' => date("Y-m-d H:i:s"),
					'email' => $this->input->post('email'),
					//NUEVO
					'create_user_id' => $user_row->id,
					'dni_cuit_cuil' => $this->input->post('dni_cuit_cuil'),
                    'altura' => $this->input->post('altura'),
                    'esquina_1' => $this->input->post('esquina_1'),
                    'esquina_2' => $this->input->post('esquina_2'),
                    'piso' => $this->input->post('piso'),
                    'departamento' => $this->input->post('departamento'),
                    'pais' => $this->input->post('pais'),
                    'provincia' => $this->input->post('provincia'),
					'condicion_fiscal' => $this->input->post('condicion_fiscal'),
					'ingresos_brutos' => $this->input->post('ingresos_brutos'),
					'inicio_actividades' => $this->input->post('inicio_actividades'),
					'afip_fecha_vencimiento' => $this->input->post('afip_fecha_vencimiento') != "" ? $this->input->post('afip_fecha_vencimiento') : null,
					'afip_imagen' => $afip_imagen,
					'carnet_conductor_fecha_vencimiento' => $this->input->post('carnet_conductor_fecha_vencimiento') != "" ? $this->input->post('carnet_conductor_fecha_vencimiento') : null,
					'carnet_conductor_imagen' => $carnet_conductor_imagen
                    
				);
			$this->codegen_model->add('flash_sucursales_carteros',$data);
			redirect(base_url().'sucursales/sucursales_carteros');
		}		  
   
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
            'carteros_tipos' => $this->codegen_model->get('flash_sucursales_carteros_tipos','*',''),
			'paises' => $this->codegen_model->get('flash_paises', '*', ''),
            'iva' => $this->codegen_model->get('flash_iva', '*', ''),
		);

		$vista_externa = array(			
			'title' => ucwords("carteros"),
			'contenido_main' => $this->load->view('components/sucursales/sucursales_carteros/sucursales_carteros_add', $vista_interna, true)
		);		  
   
		$this->load->view('template/backend', $vista_externa);
	}	

	private function image_upload($field_name, $carpeta){
		$this->load->library('upload');
		$config['upload_path'] = './uploads/'.$carpeta.'/'; 
		$config['file_name'] = time() . md5(rand()).'.jpg';
		//$config['overwrite'] = TRUE;
		$config["allowed_types"] = 'jpg|jpeg|png';		
		$this->upload->initialize($config);
		if (!$this->upload->do_upload($field_name)){
		    $error = array('error' => $this->upload->display_errors());
		}

	    return $config['file_name'];
	}

	private function eliminar_imagen_servidor($carpeta, $imagen){
		if(!empty($imagen)){
            //OBTENGO LA RUTA DEL ARCHIVO A ELIMINAR
            $upload_path = './uploads/'. $carpeta .'/' . $imagen;
            
            //ELIMINO EL ARCHIVO
            if(unlink($upload_path)) {
            }
        }
	}

	function edit($id){

		$user_row = $this->ion_auth->user()->row();
		$cartero = $this->codegen_model->row('flash_sucursales_carteros', '*', 'id = ' . $id);

		if ($this->input->post('enviar_form')){
		    
		    $this->db->select('*');
            $this->db->from('flash_sucursales_carteros');
            $this->db->where('flash_sucursales_carteros.id != ', $this->input->post('id'));
            $this->db->where('flash_sucursales_carteros.email like', $this->input->post('email'));
            $q = $this->db->get();
            if(count($q->result())>0){
        		$vista_interna = array(
        			'permisos_efectivos' => $this->permisos,
        			'result' => $this->codegen_model->row('flash_sucursales_carteros','*','id = '.$id),
        			'MSJ' => '(Email) ya existe en el cartero: '. $q->result()[0]->apellido_nombre,
        			'sucursales' => $this->codegen_model->get('flash_sucursales','*',''),
					'carteros_tipos' => $this->codegen_model->get('flash_sucursales_carteros_tipos','*',''),
					'paises' => $this->codegen_model->get('flash_paises', '*', ''),
					'provincias' => $this->codegen_model->get('ubicacion_provincias', '*', ''),
					'localidades' => $this->codegen_model->get('ubicacion_localidades', '*', 'id = ' . $cartero->localidad),
					'iva' => $this->codegen_model->get('flash_iva', '*', '')
        		);
        		$vista_externa = array(			
        			'title' => ucwords("carteros"),
        			'contenido_main' => $this->load->view('components/sucursales/sucursales_carteros/sucursales_carteros_edit', $vista_interna, true)
        		);
        		$this->load->view('template/backend', $vista_externa);
                return;
            }
            
		    $this->db->select('*');
            $this->db->from('flash_sucursales_carteros');
            $this->db->where('flash_sucursales_carteros.id != ', $this->input->post('id'));
            $this->db->where('flash_sucursales_carteros.apellido_nombre like', $this->input->post('apellido_nombre'));
            $q = $this->db->get();
            if(count($q->result())>0){
        		$vista_interna = array(
        			'permisos_efectivos' => $this->permisos,
        			'result' => $this->codegen_model->row('flash_sucursales_carteros','*','id = '.$id),
        			'MSJ' => '(Apellido nombre) Ya existente.',
        			'sucursales' => $this->codegen_model->get('flash_sucursales','*',''),
					'carteros_tipos' => $this->codegen_model->get('flash_sucursales_carteros_tipos','*',''),
					'paises' => $this->codegen_model->get('flash_paises', '*', ''),
					'provincias' => $this->codegen_model->get('ubicacion_provincias', '*', ''),
					'localidades' => $this->codegen_model->get('ubicacion_localidades', '*', 'id = ' . $cartero->localidad),
					'iva' => $this->codegen_model->get('flash_iva', '*', '')
        		);
        		$vista_externa = array(			
        			'title' => ucwords("carteros"),
        			'contenido_main' => $this->load->view('components/sucursales/sucursales_carteros/sucursales_carteros_edit', $vista_interna, true)
        		);
        		$this->load->view('template/backend', $vista_externa);
                return;
            }

			//VALIDAR QUE NO EXISTAN DOS CARTEROS CON EL MISMO DNI_CUIT_CUIL
		    $this->db->select('*');
            $this->db->from('flash_sucursales_carteros');
			$this->db->where('flash_sucursales_carteros.id != ', $this->input->post('id'));
            $this->db->where('flash_sucursales_carteros.dni_cuit_cuil = ', $this->input->post('dni_cuit_cuil'));
            $q = $this->db->get();
            if(count($q->result())>0){
        		$vista_interna = array(
        			'permisos_efectivos' => $this->permisos,
					'result' => $this->codegen_model->row('flash_sucursales_carteros','*','id = '.$id),
        			'MSJ' => 'DNI/CUIT/CUIL ('. $q->result()[0]->dni_cuit_cuil . ') existente en el cartero ' . $q->result()[0]->apellido_nombre,
                    'sucursales' => $this->codegen_model->get('flash_sucursales','*',''),
					'carteros_tipos' => $this->codegen_model->get('flash_sucursales_carteros_tipos','*',''),
					'paises' => $this->codegen_model->get('flash_paises', '*', ''),
					'provincias' => $this->codegen_model->get('ubicacion_provincias', '*', ''),
					'localidades' => $this->codegen_model->get('ubicacion_localidades', '*', 'id = ' . $cartero->localidad),
					'iva' => $this->codegen_model->get('flash_iva', '*', '')
        		);
        		$vista_externa = array(			
        			'title' => ucwords("carteros"),
        			'contenido_main' => $this->load->view('components/sucursales/sucursales_carteros/sucursales_carteros_edit', $vista_interna, true)
        		);		  
        		$this->load->view('template/backend', $vista_externa);
                return;
            }

			//OBTENGO LA RUTA DE LA IMAGEN DE AFIP
			if (!empty($_FILES['afip_imagen']['tmp_name'])) {
				$afip_imagen = $this->image_upload('afip_imagen', 'carteros');
				$this->eliminar_imagen_servidor('carteros', $cartero->afip_imagen);
			}else{
				$bandera = $this->input->post('eliminar_afip_imagen');
				if($bandera){
					$afip_imagen = "";
					$this->eliminar_imagen_servidor('carteros', $cartero->afip_imagen);
				}else{
					$afip_imagen = $cartero->afip_imagen;
				}
			}

			//OBTENGO LA RUTA DE LA IMAGEN DEL CARNET DE CONDUCIR
			if (!empty($_FILES['carnet_conductor_imagen']['tmp_name'])) {
				$carnet_conductor_imagen = $this->image_upload('carnet_conductor_imagen', 'carteros');
				$this->eliminar_imagen_servidor('carteros', $cartero->carnet_conductor_imagen);
			}else{
				$bandera = $this->input->post('eliminar_carnet_conductor_imagen');
				if($bandera){
					$carnet_conductor_imagen = "";
					$this->eliminar_imagen_servidor('carteros', $cartero->carnet_conductor_imagen);
				}else{
					$carnet_conductor_imagen = $cartero->carnet_conductor_imagen;
				}
			}

			$data = array(
					'sucursal_id' => $this->input->post('sucursal_id'),
					'apellido_nombre' => $this->input->post('apellido_nombre'),
					'domicilio' => $this->input->post('domicilio'),
					'localidad' => $this->input->post('localidad'),
					'codigo_postal' => $this->input->post('codigo_postal'),
					'telefono' => $this->input->post('telefono'),
					'email' => $this->input->post('email'),
					'activo' => $this->input->post('activo'),
					'update' => date('Y-m-d H:m:s'),
					'cartero_tipo_id' => $this->input->post('cartero_tipo_id'),
					'productividad' => $this->input->post('productividad'),
					'observaciones' => $this->input->post('observaciones'),
					//NUEVO
					'update_user_id' => $user_row->id,
					'update' => date('Y-m-d h:i:s', time()),
					'dni_cuit_cuil' => $this->input->post('dni_cuit_cuil'),
                    'altura' => $this->input->post('altura'),
                    'esquina_1' => $this->input->post('esquina_1'),
                    'esquina_2' => $this->input->post('esquina_2'),
                    'piso' => $this->input->post('piso'),
                    'departamento' => $this->input->post('departamento'),
                    'pais' => $this->input->post('pais'),
                    'provincia' => $this->input->post('provincia'),
					'condicion_fiscal' => $this->input->post('condicion_fiscal'),
					'ingresos_brutos' => $this->input->post('ingresos_brutos'),
					'inicio_actividades' => $this->input->post('inicio_actividades'),
					'afip_fecha_vencimiento' => $this->input->post('afip_fecha_vencimiento') != "" ? $this->input->post('afip_fecha_vencimiento') : null,
					'afip_imagen' => $afip_imagen,
					'carnet_conductor_fecha_vencimiento' => $this->input->post('carnet_conductor_fecha_vencimiento') != "" ? $this->input->post('carnet_conductor_fecha_vencimiento') : null,
					'carnet_conductor_imagen' => $carnet_conductor_imagen
			);
			$this->codegen_model->edit('flash_sucursales_carteros',$data,'id',$this->input->post('id'));
			redirect(base_url().'sucursales/sucursales_carteros');
		}

		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'result' => $this->codegen_model->row('flash_sucursales_carteros','*','id = '.$id),
			'sucursales' => $this->codegen_model->get('flash_sucursales','*',''),
            'carteros_tipos' => $this->codegen_model->get('flash_sucursales_carteros_tipos','*',''),
			'paises' => $this->codegen_model->get('flash_paises', '*', ''),
			'provincias' => $this->codegen_model->get('ubicacion_provincias', '*', ''),
            'localidades' => $this->codegen_model->get('ubicacion_localidades', '*', 'id = ' . $cartero->localidad),
			'iva' => $this->codegen_model->get('flash_iva', '*', '')
		);

		$vista_externa = array(			
			'title' => ucwords("carteros"),
			'contenido_main' => $this->load->view('components/sucursales/sucursales_carteros/sucursales_carteros_edit', $vista_interna, true)
		);
   
		$this->load->view('template/backend', $vista_externa);
	}

	function view($id){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'result' => $this->codegen_model->row('flash_sucursales_carteros','*','id = '.$id)
		);

		$vista_externa = array(			
			'title' => ucwords("carteros"),
			'contenido_main' => $this->load->view('components/sucursales/sucursales_carteros/sucursales_carteros_view', $vista_interna, true)
		);		  
   
		$this->load->view('template/fancybox', $vista_externa);
	}

	function json($id){
		$json = $this->codegen_model->row('flash_sucursales_carteros','*','id = '.$id);
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}

	function json_all(){
		$json = $this->codegen_model->get('flash_sucursales_carteros','*','');
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}
	
	function delete($id){
		$user = $this->ion_auth->user()->row();
		$data = array(
			'activo' => 0,
			'deleted_user_id' => $user->id,
			'deleted' => date('Y-m-d h:i:s', time())
		);
		$this->codegen_model->edit('flash_sucursales_carteros', $data, 'id', $id);
		//$this->codegen_model->delete('flash_sucursales_carteros','id',$id);             
	}

	function getSucursales(){
		$pais = $this->input->get('pais');

		$query = $this->db
                    ->select('S.id, S.nombre')
                    ->where('S.pais_id =' . $pais)
                    ->get('flash_sucursales as S');

        $json = $query->result();

		if($json) echo json_encode($json);
	}
}

/* End of file sucursales_carteros.php */
/* Location: ./system/application/controllers/sucursales_carteros.php */