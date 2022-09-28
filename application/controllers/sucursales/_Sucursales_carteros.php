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
			'results' => $this->flash_md->getCarteros($this->input->post('sucursal'), 
                                                                    $this->input->post('buscar'),
                                                                    $this->input->post('cartero_tipo_id')),
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
			$data = array(
					'sucursal_id' => $this->input->post('sucursal_id'),
					'apellido_nombre' => $this->input->post('apellido_nombre'),
					'domicilio' => $this->input->post('domicilio'),
					'localidad' => $this->input->post('localidad'),
					'codigo_postal' => $this->input->post('codigo_postal'),
					'telefono' => $this->input->post('telefono'),
					'activo' => $this->input->post('activo'),
                                        'cartero_tipo_id' => $this->input->post('cartero_tipo_id'),
				);
			$this->codegen_model->add('flash_sucursales_carteros',$data);
			redirect(base_url().'sucursales/sucursales_carteros');
		}		  
   
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'sucursales' => $sucursales,//$this->codegen_model->get('flash_sucursales','*',''),
                        'carteros_tipos' => $this->codegen_model->get('flash_sucursales_carteros_tipos','*','')
		);

		$vista_externa = array(			
			'title' => ucwords("carteros"),
			'contenido_main' => $this->load->view('components/sucursales/sucursales_carteros/sucursales_carteros_add', $vista_interna, true)
		);		  
   
		$this->load->view('template/backend', $vista_externa);
	}	

	function edit($id){
		if ($this->input->post('enviar_form')){
			$data = array(
					'sucursal_id' => $this->input->post('sucursal_id'),
					'apellido_nombre' => $this->input->post('apellido_nombre'),
					'domicilio' => $this->input->post('domicilio'),
					'localidad' => $this->input->post('localidad'),
					'codigo_postal' => $this->input->post('codigo_postal'),
					'telefono' => $this->input->post('telefono'),
					'activo' => $this->input->post('activo'),
					'update' => date('Y-m-d H:m:s'),
                                        'cartero_tipo_id' => $this->input->post('cartero_tipo_id')
				);
			$this->codegen_model->edit('flash_sucursales_carteros',$data,'id',$this->input->post('id'));
			redirect(base_url().'sucursales/sucursales_carteros');
		}

		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'result' => $this->codegen_model->row('flash_sucursales_carteros','*','id = '.$id),
			'sucursales' => $this->codegen_model->get('flash_sucursales','*',''),
                        'carteros_tipos' => $this->codegen_model->get('flash_sucursales_carteros_tipos','*','')
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
		$this->codegen_model->delete('flash_sucursales_carteros','id',$id);             
	}
}

/* End of file sucursales_carteros.php */
/* Location: ./system/application/controllers/sucursales_carteros.php */