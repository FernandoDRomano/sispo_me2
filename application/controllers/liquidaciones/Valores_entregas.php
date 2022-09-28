<?php

class Valores_entregas extends CI_Controller {

	private $permisos;
	
	function __construct() {
		parent::__construct();
		$this->permisos = $this->permisos_lib->control();
	}	
	
	function index(){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'results' => $this->codegen_model->get('flash_valores_entregas','*','')
		);

		$vista_externa = array(			
			'title' => ucwords("valores_entregas"),
			'contenido_main' => $this->load->view('components/liquidaciones/liquidaciones_ddis/valores_entregas/valores_entregas_list', $vista_interna, true)
		);		
		
		$this->load->view('template/backend', $vista_externa);
	}

	function add(){
	    
	    
		if ($this->input->post('enviar_form')){
//                    var_dump($_REQUEST);die;
                        $user_row = $this->ion_auth->user()->row();
                        $cliente_id = $this->input->post('cliente_id');
                        $servicio_id = $this->input->post('servicio_id');
                        $zona_id = $this->input->post('zona_id');
                        $cartero_id = $this->input->post('cartero_id');
                        //Busco primero con CARTERO porque es el precio especial por cliente y cartero
                        if ($cartero_id != "") {
                            $and_cartero_id = ' AND cartero_id = '.$cartero_id;
                        }else{
                            $and_cartero_id = ' AND cartero_id = 0 ';
                        }
                        $valor_entrega = ' SELECT * FROM flash_ddis_valores_entregas
                                           WHERE cliente_id = '.$cliente_id.
                                           ' AND servicio_id = '.$servicio_id.
                                           ' AND zona_id = '.$zona_id.
                                           $and_cartero_id.
                                           ' AND habilitado = 1';
                        $valor_entrega = $this->db->query($valor_entrega)->row();
                        //Si ya existe un valor para esa carga...entonces la inhabilito y creo una nueva
                        if ($valor_entrega != NULL){
                            $query_deshabilitar = " UPDATE flash_ddis_valores_entregas 
                                                    SET habilitado = 0 
                                                    WHERE id = $valor_entrega->id";
                        //echo $query_deshabilitar;die;
                            $this->db->query($query_deshabilitar);
                        }
                        
			$data = array(
					'cliente_id' => $this->input->post('cliente_id'),
					'servicio_id' => $this->input->post('servicio_id'),
					'zona_id' => $this->input->post('zona_id'),
                                        'cartero_id' => $this->input->post('cartero_id'),
					'pago' => $this->input->post('valor'),
					'observaciones' => $this->input->post('observaciones'),
                                        'habilitado' => 1,
					'create' => date('Y-m-d H:m:s'),
					'create_user_id' => $user_row->id,
				);
                        //var_dump($data);die;
			$this->codegen_model->add('flash_ddis_valores_entregas',$data);
                        //echo $this->db->last_query();die;
                        $query =   "SELECT ve.id valor_entrega_id,ve.cartero_id, sc.apellido_nombre cartero,
                                    (CASE WHEN ve.habilitado = 0 THEN 'DESHABILITADO'
                                          WHEN ve.habilitado = 1 THEN 'HABILITADO' END) habilitado,
                                            c.id cliente_id, c.nombre cliente,s.id servicio_id, s.nombre servicio, sz.nombre zona, ve.pago valor
                                    FROM flash_ddis_valores_entregas ve
                                    INNER JOIN flash_clientes c ON c.id = ve.cliente_id
                                    INNER JOIN flash_servicios s ON s.id = ve.servicio_id
                                    INNER JOIN flash_sucursales_zonas sz ON sz.id = ve.zona_id
                                    LEFT JOIN flash_sucursales_carteros sc ON sc.id = ve.cartero_id";
                        $resutls = $this->db->query($query)->result();
                        
                        $vista_interna = array(
                            'permisos_efectivos' => $this->permisos,
                            'clientes' => $this->codegen_model->get("flash_clientes",'*',''),
                            'zonas' => $this->codegen_model->get("flash_sucursales_zonas",'*',''),
                            'cliente_id' => $this->input->post('cliente_id'),
                            'cliente' => $this->input->post('cliente'),
                            'cartero_id' => $this->input->post('cartero_id'),
                            'cartero' => $this->input->post('cartero'),
                            'servicio_id' => $this->input->post('servicio_id'),
                            'zona_id' => $this->input->post('zona_id'),
                            'zona' => $this->input->post('zona'),
                            'results' => $resutls,
                            'carteros' => $this->codegen_model->get("flash_sucursales_carteros",'*',''),
                        );

                        $vista_externa = array(			
                                'title' => ucwords("Configuración de Valores de Entregas"),
                                'contenido_main' => $this->load->view('components/liquidaciones/liquidaciones_ddis/valores_entregas/valores_entregas_add', $vista_interna, true)
                        );		  

		}		  
                if (!$vista_interna){
                    $query =   "SELECT ve.id valor_entrega_id,ve.cartero_id, sc.apellido_nombre cartero,ve.id valor_entrega_id,
                                (CASE WHEN ve.habilitado = 0 THEN 'DESHABILITADO'
                                     WHEN ve.habilitado = 1 THEN 'HABILITADO' END) habilitado,
                                        c.id cliente_id, c.nombre cliente,s.id servicio_id, s.nombre servicio, sz.nombre zona, ve.pago valor
                                FROM flash_ddis_valores_entregas ve
                                INNER JOIN flash_clientes c ON c.id = ve.cliente_id
                                INNER JOIN flash_servicios s ON s.id = ve.servicio_id
                                INNER JOIN flash_sucursales_zonas sz ON sz.id = ve.zona_id
                                LEFT JOIN flash_sucursales_carteros sc ON sc.id = ve.cartero_id";
                    
                    $resutls = $this->db->query($query)->result();
                        
                    $vista_interna = array(
                            'permisos_efectivos' => $this->permisos,
                            'clientes' => $this->codegen_model->get("flash_clientes",'*',''),
                            'zonas' => $this->codegen_model->get("flash_sucursales_zonas",'*',''),
                            'carteros' => $this->codegen_model->get("flash_sucursales_carteros",'*',''),
                            'results' => $resutls
                    );
                }
		$vista_externa = array(			
			'title' => ucwords("Configuración de Valores de Entregas"),
			'contenido_main' => $this->load->view('components/liquidaciones/liquidaciones_ddis/valores_entregas/valores_entregas_add', $vista_interna, true)
		);		  
   
		$this->load->view('template/backend', $vista_externa);
	}	

	function edit($id){
	    
	    
		if ($this->input->post('enviar_form')){
                    $user_row = $this->ion_auth->user()->row();
                    $data = array(
                                'cliente_id' => $this->input->post('cliente_id_p'),
                                'servicio_id' => $this->input->post('servicio_id_p'),
                                'zona_id' => $this->input->post('zona_id_p'),
                                'pago' => $this->input->post('valor_p'),
                                'observaciones' => $this->input->post('observaciones_p'),
                                'update' => date('Y-m-d H:m:s'), 
                                'update_user_id' => $user_row->id,
				);
                    $this->codegen_model->edit('flash_ddis_valores_entregas',$data,'id',$id);
//                    echo $this->db->last_query();die;
                    if ($this->input->post('cartero_id_p') > 0){
                        $data_cartero = array(
                                    'ddi_valor_entrega_id' => $id,
                                    'cartero_id' => $this->input->post('cartero_id_p'),
                                    'pago_especial' => $this->input->post('valor_p'),
                                    'observaciones' => $this->input->post('observaciones_p'),
                                    'update' => date('Y-m-d H:m:s'),
                                    'update_user_id' => $user_row->id,
                            );
                        $this->codegen_model->edit('flash_ddis_valores_especiales_entregas',$data_cartero,'ddi_valor_entrega_id',$id);
//                        echo $this->db->last_query();die;
                        if ($this->db->affected_rows() == 0){
                            $this->codegen_model->add('flash_valores_especiales_entregas',$data_cartero);
                        }
                    }
                        
                    redirect(base_url().'liquidaciones/valores_entregas/add');
		}

                $query =   "SELECT ve.id valor_entrega_id,vee.cartero_id,vee.cartero_id, sc.apellido_nombre cartero,ve.id valor_entrega_id,
                                    c.id cliente_id, c.nombre cliente,s.id servicio_id, s.nombre servicio, sz.nombre zona, sz.id zona_id, ve.pago valor,
                                    ve.observaciones
                                FROM flash_ddis_valores_entregas ve
                                INNER JOIN flash_clientes c ON c.id = ve.cliente_id
                                INNER JOIN flash_servicios s ON s.id = ve.servicio_id
                                INNER JOIN flash_sucursales_zonas sz ON sz.id = ve.zona_id
                                LEFT JOIN flash_ddis_valores_especiales_entregas vee ON vee.ddi_valor_entrega_id = ve.id
                                LEFT JOIN flash_sucursales_carteros sc ON sc.id = vee.cartero_id
                            WHERE ve.id = ".$id;
//                echo $query;die;
                $result = $this->db->query($query)->row();
                
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
                        'clientes' => $this->codegen_model->get("flash_clientes",'*',''),
                        'zonas' => $this->codegen_model->get("flash_sucursales_zonas",'*',''),
                        'carteros' => $this->codegen_model->get("flash_sucursales_carteros",'*',''),
                        'servicio_id' => $result->servicio_id,
                       // 'servicios' => $this->codegen_model->get("flash_servicios",'*',''),
			'result' => $result
		);
//                var_dump($vista_interna);die;
		$vista_externa = array(			
			'title' => ucwords("Valores de Entregas"),
			'contenido_main' => $this->load->view('components/liquidaciones/liquidaciones_ddis/valores_entregas/valores_entregas_edit', $vista_interna, true)
		);		  
   
		$this->load->view('template/view', $vista_externa);
	}

	function view($id){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'result' => $this->codegen_model->row('flash_valores_entregas','*','id = '.$id)
		);

		$vista_externa = array(			
			'title' => ucwords("valores_entregas"),
			'contenido_main' => $this->load->view('components/liquidaciones_ddis/valores_entregas/valores_entregas_view', $vista_interna, true)
		);		  
   
		$this->load->view('template/view', $vista_externa);
	}

	function json($id){
		$json = $this->codegen_model->row('flash_valores_entregas','*','id = '.$id);
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}

	function json_all(){
		$json = $this->codegen_model->get('flash_valores_entregas','*','');
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}
	
	function delete(){
            $id = $this->input->post('filtro_liquidacion_ddi_id');
            $this->codegen_model->delete('flash_ddis_valores_especiales_entregas','ddi_valor_entrega_id',$id);   
            $this->codegen_model->delete('flash_ddis_valores_entregas','id',$id);     
            $query =   "SELECT ve.id valor_entrega_id, sc.apellido_nombre cartero,ve.id valor_entrega_id,
                                           c.id cliente_id, c.nombre cliente,s.id servicio_id, s.nombre servicio, sz.nombre zona, ve.pago valor
                                   FROM flash_ddis_valores_entregas ve
                                   INNER JOIN flash_clientes c ON c.id = ve.cliente_id
                                   INNER JOIN flash_servicios s ON s.id = ve.servicio_id
                                   INNER JOIN flash_sucursales_zonas sz ON sz.id = ve.zona_id
                                   LEFT JOIN flash_sucursales_carteros sc ON sc.id = ve.cartero_id";
                       $resutls = $this->db->query($query)->result();

                       $vista_interna = array(
                           'permisos_efectivos' => $this->permisos,
                           'clientes' => $this->codegen_model->get("flash_clientes",'*',''),
                           'zonas' => $this->codegen_model->get("flash_sucursales_zonas",'*',''),
                           'results' => $resutls,
                           'carteros' => $this->codegen_model->get("flash_sucursales_carteros",'*',''),
                       );

                        $vista_externa = array(			
                                'title' => ucwords("Configuración de Valores de Entregas"),
                                'contenido_main' => $this->load->view('components/liquidaciones/liquidaciones_ddis/valores_entregas/valores_entregas_add', $vista_interna, true)
                        );
                        $this->load->view('template/backend', $vista_externa);
	}
        
 	function add_predeterminado(){
 	    
 	    
		if ($this->input->post('enviar_form')){
//                    var_dump($_REQUEST);die;
                        $user_row = $this->ion_auth->user()->row();
                        $cartero_id = $this->input->post('cartero_id');
                        $valor = $this->input->post('valor');
                        $observaciones = $this->input->post('observaciones');
                        $valor_predeterminado_entrega = ' SELECT * FROM flash_ddis_valores_predeterminados_entregas
                                                            WHERE cartero_id = '.$cartero_id.
                                                            ' AND habilitado = 1';
                        
                        $valor_predeterminado_entrega = $this->db->query($valor_predeterminado_entrega)->row();
                        if ($valor_predeterminado_entrega != NULL){
                            $query_deshabilitar = " UPDATE flash_ddis_valores_predeterminados_entregas 
                                                    SET habilitado = 0 
                                                    WHERE id = $valor_predeterminado_entrega->id";
//                            echo $query_deshabilitar;die;
                            $this->db->query($query_deshabilitar);
                        }
                        
			$data = array(
					'cartero_id' => $cartero_id,
					'valor' => $valor,
					'observaciones' => $observaciones,
                                        'habilitado' => 1,
					'create' => date('Y-m-d H:m:s'),
					'create_user_id' => $user_row->id,
				);
//                                var_dump($data);die;
			$this->codegen_model->add('flash_ddis_valores_predeterminados_entregas',$data);
//                        echo $this->db->last_query();die;
//			redirect(base_url().'liquidaciones/valores_entregas/add');
                        $query =   "SELECT vpe.id,vpe.valor, sc.apellido_nombre cartero, vpe.habilitado
                                    FROM flash_ddis_valores_predeterminados_entregas vpe
                                    INNER JOIN flash_sucursales_carteros sc on vpe.cartero_id = sc.id";
                        
                        $resutls = $this->db->query($query)->result();
                        
                        $vista_interna = array(
                            'permisos_efectivos' => $this->permisos,
                            'carteros' => $this->codegen_model->get("flash_sucursales_carteros",'*',''),
                            'cartero' => $this->input->post('cartero'),
                            'cartero_id' => $this->input->post('cartero_id'),
                            'valor' => $valor,
                            'observaciones' => $observaciones,
                            'results' => $resutls,
                        );

                        $vista_externa = array(			
                                'title' => ucwords("Configuración de Valores Predeterminados de Entregas"),
                                'contenido_main' => $this->load->view('components/liquidaciones/liquidaciones_ddis/valores_entregas/valores_entregas_predeterminados_add', $vista_interna, true)
                        );		  

		}		  
                if (!$vista_interna){
                    $query =   "SELECT vpe.id,vpe.valor, sc.apellido_nombre cartero, vpe.habilitado
                                FROM flash_ddis_valores_predeterminados_entregas vpe
                                INNER JOIN flash_sucursales_carteros sc on vpe.cartero_id = sc.id";
                    
                    $resutls = $this->db->query($query)->result();
                        
                    $vista_interna = array(
                            'permisos_efectivos' => $this->permisos,
                            'carteros' => $this->codegen_model->get("flash_sucursales_carteros",'*',''),
                            'results' => $resutls
                    );
                }
		$vista_externa = array(			
			'title' => ucwords("Configuración de Valores Predeterminados de Entregas"),
			'contenido_main' => $this->load->view('components/liquidaciones/liquidaciones_ddis/valores_entregas/valores_entregas_predeterminados_add', $vista_interna, true)
		);		  
   
		$this->load->view('template/backend', $vista_externa);
	}	
        
        function edit_predeterminado($id){
            
            
		if ($this->input->post('enviar_form')){
                    $user_row = $this->ion_auth->user()->row();
                    $data = array(
                                'sucursal_id' => $this->input->post('sucursal_id_p'),
                                'grupo_id' => $this->input->post('grupo_id_p'),
                                'valor' => $this->input->post('valor_p'),
                                'observaciones' => $this->input->post('observaciones_p'),
                                'update' => date('Y-m-d H:m:s'),
                                'update_user_id' => $user_row->id,
				);
                    $this->codegen_model->edit('flash_ddis_valores_predeterminados_entregas',$data,'id',$id);
//                    echo $this->db->last_query();die;
                        
                    redirect(base_url().'liquidaciones/valores_entregas/add_predeterminado');
		}

                $query =   "SELECT vpe.id, sg.id grupo_id,sg.nombre grupo,vpe.valor, vpe.sucursal_id, vpe.observaciones
                                    FROM flash_ddis_valores_predeterminados_entregas vpe
                                    INNER JOIN flash_servicios_grupos sg ON vpe.grupo_servicio_id = sg.id
                            WHERE vpe.id = ".$id;
//                echo $query;die;
                $result = $this->db->query($query)->row();
                
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
                        'sucursales' => $this->codegen_model->get("flash_sucursales",'*',''),
                        'grupos' => $this->codegen_model->get("flash_servicios_grupos",'*',''),
                        'grupo_id' => $result->grupo_id,
                        'sucursal_id' => $result->sucursal_id,
                       // 'servicios' => $this->codegen_model->get("flash_servicios",'*',''),
			'result' => $result
		);
//                var_dump($vista_interna);die;
		$vista_externa = array(			
			'title' => ucwords("Valores de Entregas Predeterminados"),
			'contenido_main' => $this->load->view('components/liquidaciones/liquidaciones_ddis/valores_entregas/valores_entregas_predeterminados_edit', $vista_interna, true)
		);		  
   
		$this->load->view('template/view', $vista_externa);
	}

        function delete_predeterminado($id){
		$this->codegen_model->delete('flash_ddis_valores_predeterminados_entregas','id',$id);             
	}
}

/* End of file valores_entregas.php */
/* Location: ./system/application/controllers/valores_entregas.php */