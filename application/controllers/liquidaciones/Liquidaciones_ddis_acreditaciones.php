<?php

class Liquidaciones_ddis_acreditaciones extends CI_Controller {

	private $permisos;
	
	function __construct() {
		parent::__construct();
		$this->permisos = $this->permisos_lib->control();
	}	
	
	function index(){
                $query = "SELECT la.id acreditacion_id, sc.id cartero_id, sc.apellido_nombre cartero, concepto, 
                                    la.observaciones, importe, la.fecha_autorizacion,
                                    date_format(fecha_desde,'%d-%m-%Y') fecha_desde, 
                                    date_format(fecha_hasta,'%d-%m-%Y') fecha_hasta, 
                                    autorizador_id, 
                                    (CASE WHEN tipo_concepto = 'G' THEN 'GENERAL'
                                            WHEN tipo_concepto = 'I' THEN 'INDIVIDUAL'
                                    END) AS tipo_concepto
                            FROM flash_ddis_acreditaciones la
                            INNER JOIN flash_sucursales_carteros sc ON la.cartero_id = sc.id";
//                echo $query;die;
                $results = $this->db->query($query)->result();
                
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'results' => $results,
                        'carteros' => $this->codegen_model->get('flash_sucursales_carteros','*',''),
                        'acreditaciones' => $this->codegen_model->get('flash_ddis_acreditaciones','*',''),
                        'autorizadores' => $this->getDdisAutorizadores(),
		);

		$vista_externa = array(			
			'title' => ucwords("Liquidacion Acreditaciones"),
			'contenido_main' => $this->load->view('components/liquidaciones/liquidaciones_ddis_acreditaciones/liquidaciones_ddis_acreditaciones_add', $vista_interna, true)
		);		
		
		$this->load->view('template/backend', $vista_externa);
	}

	function add(){
	    
	    
		if ($this->input->post('enviar_form')){
                        $user_row = $this->ion_auth->user()->row();
                        $valido_desde = $this->input->post('valido_desde');
                        $valido_hasta = $this->input->post('valido_hasta');
                        if ($valido_desde){
                            $valido_desde = new DateTime($valido_desde);
                        }
                        if($valido_hasta){
                            $valido_hasta = new DateTime($valido_hasta);
                        }

			$data = array(
					'cartero_id' => $this->input->post('cartero_id'),
                                        'concepto' => $this->input->post('concepto'),
                                        'importe' => $this->input->post('importe'),
                                        'tipo_concepto' => $this->input->post('tipo_concepto'),
                                        'autorizador_id' => $this->input->post('liquidacion_ddi_autorizador_id'),
                                        'fecha_autorizacion' => NULL,
                                        'fecha_desde' => $valido_desde->format('Y-m-d') ,
                                        'fecha_hasta' => $valido_hasta->format('Y-m-d'),
                                        'observaciones' => $this->input->post('observaciones'),
					'create' => date('Y-m-d H:m:s'),
					'user_id_create' => $user_row->id,
				);

			$this->codegen_model->add('flash_ddis_acreditaciones',$data);
//                        echo $this->db->last_query();die;
			redirect(base_url().'liquidaciones/liquidaciones_ddis_acreditaciones');
		}		  
   
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos
		);

		$vista_externa = array(			
			'title' => ucwords("Conceptos de Acreditaciones"),
			'contenido_main' => $this->load->view('components/liquidaciones/liquidaciones_ddis_acreditaciones/liquidaciones_ddis_acreditaciones_add', $vista_interna, true)
		);		  
   
		$this->load->view('template/backend', $vista_externa);
	}	

	function edit($acreditacion_id = null){
	    
	    
		if ($this->input->post('enviar_form')){
                    $user_row = $this->ion_auth->user()->row();
                    $valido_desde = $this->input->post('fecha_desde_p');
                    $valido_hasta = $this->input->post('fecha_hasta_p');
                    $acreditacion_id = $this->input->post('acreditacion_id_p');
                    if ($valido_desde){
                        $valido_desde = new DateTime($valido_desde);
                    }
                    if($valido_hasta){
                        $valido_hasta = new DateTime($valido_hasta);
                    }

			$data = array(
					'cartero_id' => $this->input->post('cartero_id_p'),
                                        'concepto' => $this->input->post('concepto_p'),
                                        'importe' => $this->input->post('importe_p'),
                                        'tipo_concepto' => $this->input->post('tipo_concepto_p'),
                                        'autorizador_id' => $this->input->post('liquidacion_ddi_autorizador_id_p'),
                                        'fecha_autorizacion' => NULL,
                                        'fecha_desde' => $valido_desde->format('Y-m-d') ,
                                        'fecha_hasta' => $valido_hasta->format('Y-m-d'),
                                        'observaciones' => $this->input->post('observaciones_p'),
					'create' => date('Y-m-d H:m:s'),
					'user_id_create' => $user_row->id,
				);
                    $this->codegen_model->edit('flash_ddis_acreditaciones',$data,'id',$acreditacion_id);
//                    echo $this->db->last_query();die;
                    redirect(base_url().'liquidaciones/liquidaciones_ddis_acreditaciones');
		}
                
                
                $result = $this->getAcreditaciones($acreditacion_id);
//                var_dump($result);die;
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'result' => $result[0],
                        'autorizadores' => $this->getDdisAutorizadores(),
                        'carteros' => $this->codegen_model->get('flash_sucursales_carteros','*',''),
		);

		$vista_externa = array(			
			'title' => ucwords("Conceptos de Acreditaciones"),
			'contenido_main' => $this->load->view('components/liquidaciones/liquidaciones_ddis_acreditaciones/liquidaciones_ddis_acreditaciones_edit', $vista_interna, true)
		);		  
   
		$this->load->view('template/view', $vista_externa);
	}

	function view($id){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'result' => $this->codegen_model->row('flash_liquidaciones_ddis_acreditaciones','*','id = '.$id)
		);

		$vista_externa = array(			
			'title' => ucwords("Conceptos de Acreditaciones"),
			'contenido_main' => $this->load->view('components/liquidaciones/liquidaciones_ddis_acreditaciones/liquidaciones_ddis_acreditaciones_view', $vista_interna, true)
		);		  
   
		$this->load->view('template/view', $vista_externa);
	}

	function json($id){
		$json = $this->codegen_model->row('flash_liquidaciones_ddis_acreditaciones','*','id = '.$id);
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}

	function json_all(){
		$json = $this->codegen_model->get('flash_liquidaciones_ddis_acreditaciones','*','');
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}
	
	function delete($id){
		$this->codegen_model->delete('flash_ddis_acreditaciones','id',$id);       
	}
        
        function getAcreditaciones($id = null){
            $where = $id == null?"": " WHERE la.id = ".$id;
            $query = "  SELECT la.id acreditacion_id, sc.id cartero_id, sc.apellido_nombre cartero, concepto, 
                                    la.observaciones, importe, 
                                    date_format(fecha_desde,'%d-%m-%Y') fecha_desde, 
                                    date_format(fecha_hasta,'%d-%m-%Y') fecha_hasta, 
                                    autorizador_id, 
                                    CASE WHEN tipo_concepto = 'G' THEN 'GENERAL'
                                            WHEN tipo_concepto = 'I' THEN 'INDIVIDUAL'
                                    END AS tipo_concepto
                            FROM flash_ddis_acreditaciones la
                            INNER JOIN flash_sucursales_carteros sc ON la.cartero_id = sc.id "
                        .$where;
                        return $this->db->query($query)->result();
        }
        
                
        function getDdisAutorizadores($id = null){
            $where = $id == null?" ":" WHERE la.id = ".$id;
            $query = "  SELECT  la.id , 
                                CONCAT(u.apellido,', ',u.nombre) apellidonombre,
                                (CASE WHEN la.nivel_autorizacion = 1 THEN 'GERENCIA' WHEN la.nivel_autorizacion = 2 THEN 'SUPERVISOR' ELSE '' END) nivel_autorizacion,
                                (CASE WHEN la.activo = 0 THEN 'INACTIVO' WHEN la.activo = 1 THEN 'ACTIVO' ELSE '' END) estado, 
                                u.sucursal_id, u.nombre,u.apellido, u.id usuario_id 
                        FROM flash_ddis_liquidaciones_autorizadores la
                        INNER JOIN users u ON  la.user_id = u.id "
                        .$where;
                        return $this->db->query($query)->result();
        }
        
        function getLiquidacionesAcreditacionesDetalles($id = null, $acreditacion_id = null){
            $and = $acreditacion_id == null?" ":" AND ac.id = ".$acreditacion_id;
            $query = "  SELECT lad.id acreditacion_ddi_detalle_id, c.apellido_nombre cartero, ac.concepto acreditacion,
                        CASE WHEN ac.tipo_concepto_id = 1 THEN 'GENERAL'
                             WHEN ac.tipo_concepto_id = 2 THEN 'INDIVIDUAL' END tipo_concepto, ac.importe, 
                             CONCAT(u.apellido,', ',u.nombre) apellido_nombre,
                             DATE_FORMAT(lad.fecha_autorizacion,'%d-%m-%Y') fecha_autorizacion,
                             lad.autorizador_id,
                             l.id liquidacion_ddi_id
                        FROM flash_liquidaciones_ddis l
                        INNER JOIN flash_ddis_acreditaciones_detalle lad ON lad.liquidacion_ddi_id = l.id
                        INNER JOIN flash_ddis_liquidaciones_autorizadores a ON lad.autorizador_id = a.id
                        INNER JOIN flash_ddis_acreditaciones ac ON lad.acreditacion_id = ac.id
                        INNER JOIN flash_sucursales_carteros c ON lad.cartero_id = c.id
                        INNER JOIN users u ON a.user_id = u.id
                        WHERE l.id = ".$id." "
                        . $and;
//echo $query;die;
        return $this->db->query($query)->result();
    }
    
    public function autorizarAcreditacionDdi($acreditacion_id){
        
        
            $user_row = $this->ion_auth->user()->row();
            
            $query = " UPDATE flash_ddis_acreditaciones lad "
                    . "SET  "
//                    . "lad.autorizador_id = ".$user_row->id.", " Lo saco porque no puedo actualizar este campo por foreginkey porque 
                                                                 //No puede ser distinto el que yo puse que iba a autorizar del que autoriza
                                                                 //Es decir que solo tendria que permitir apretar el boton del autorizacion al 
                                                                 //autorizador logueado
                          . "lad.fecha_autorizacion  = CASE WHEN lad.`fecha_autorizacion` IS NULL THEN '".date('Y-m-d H:m:s')."' ELSE NULL END,"
                          . "lad.update = '".date('Y-m-d H:m:s')."', "
                          . "lad.user_id_update = ". $user_row->id." "
                    . "WHERE lad.id = ".$acreditacion_id;
//echo $query;die;
            $json = $this->db->query($query);
            $this->index();
        }
        
}

/* End of file liquidaciones_ddis_acreditaciones.php */
/* Location: ./system/application/controllers/liquidaciones_ddis_acreditaciones.php */