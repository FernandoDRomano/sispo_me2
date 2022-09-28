<?php

class Liquidaciones_ddis_acreditaciones_detalle extends CI_Controller {

	private $permisos;
	
	function __construct() {
		parent::__construct();
		$this->permisos = $this->permisos_lib->control();
	}	
	
	function index(){
                
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'results' => $this->codegen_model->get('flash_liquidaciones_ddis_acreditaciones_detalle','*',''),
                        'carteros' => $this->codegen_model->get('flash_sucursales_carteros','*',''),
                        'acreditaciones' => $this->codegen_model->get('flash_liquidaciones_ddis_acreditaciones','*',''),
                        'autorizadores' => $this->getDdisAutorizadores(),
		);

		$vista_externa = array(			
			'title' => ucwords("Liquidacion Acreditaciones"),
			'contenido_main' => $this->load->view('components/liquidaciones/liquidaciones_ddis_acreditaciones_detalle/liquidaciones_ddis_acreditaciones_detalle_add', $vista_interna, true)
		);		
		
		$this->load->view('template/backend', $vista_externa);
	}

	function add(){
		if ($this->input->post('enviar_form')){
			$data = array(
					'liquidacion_ddi_id' => $this->input->post('liquidacion_ddi_id'),
					'cartero_id' => $this->input->post('cartero_id'),
					'acreditacion_id' => $this->input->post('acreditacion_id'),
					'observaciones' => $this->input->post('observaciones'),
					'importe' => $this->input->post('importe'),
					'tipo_concepto_id' => $this->input->post('tipo_concepto_id'),
					'autorizador_id' => $this->input->post('autorizador_id'),
					'fecha_autorizacion' => $this->input->post('fecha_autorizacion'),
					'create' => $this->input->post('create'),
					'create_user_id' => $this->input->post('create_user_id'),
				);
			$this->codegen_model->add('flash_liquidaciones_ddis_acreditaciones_detalle',$data);
			redirect(base_url().'liquidaciones/liquidaciones_ddis_acreditaciones_detalle');
		}		  
   
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos
		);

		$vista_externa = array(			
			'title' => ucwords("Liquidacion Acreditaciones"),
			'contenido_main' => $this->load->view('components/liquidaciones/liquidaciones_ddis_acreditaciones_detalle/liquidaciones_ddis_acreditaciones_detalle_add', $vista_interna, true)
		);		  
   
		$this->load->view('template/backend', $vista_externa);
	}	

	function edit($id){
		if ($this->input->post('enviar_form')){
                    $user_row = $this->ion_auth->user()->row();
//                    var_dump($_POST);die;
                    $data = array(
                                    'liquidacion_ddi_id' => $id,
                                    'cartero_id' => $this->input->post('cartero_id'),
                                    'acreditacion_id' => $this->input->post('acreditacion_id'),
                                    'observaciones' => $this->input->post('observaciones'),
                                    'importe' => $this->input->post('importe'),
                                    'tipo_concepto_id' => $this->input->post('tipo_concepto_id'),
                                    'autorizador_id' => $this->input->post('liquidacion_ddi_autorizador_id'),
                                    'fecha_autorizacion' => NULL,
                                    'create' => date('Y-m-d H:m:s'),
                                    'create_user_id' => $user_row->id,
                            );
//                            var_dump($data);die;
                    $this->codegen_model->add('flash_liquidaciones_ddis_acreditaciones_detalle',$data);
//                    echo base_url().'liquidaciones/liquidaciones_ddis_acreditaciones_detalle/edit/'.$id;die;
                    redirect(base_url().'liquidaciones/liquidaciones_ddis_acreditaciones_detalle/edit/'.$id);
		}

                $results = $this->getLiquidacionesAcreditacionesDetalles($id);
		$vista_interna = array(
                        'permisos_efectivos' => $this->permisos,
			'results' => $results,
                        'carteros' => $this->codegen_model->get('flash_sucursales_carteros','*',''),
                        'acreditaciones' => $this->codegen_model->get('flash_liquidaciones_ddis_acreditaciones','*',''),
                        'autorizadores' => $this->getDdisAutorizadores(),
			'result' => $this->codegen_model->row('flash_liquidaciones_ddis_acreditaciones_detalle','*','id = '.$id),
                        'liquidacion_ddi_id' => $id
		);

		$vista_externa = array(			
			'title' => ucwords("Liquidacion Acreditaciones"),
			'contenido_main' => $this->load->view('components/liquidaciones/liquidaciones_ddis_acreditaciones_detalle/liquidaciones_ddis_acreditaciones_detalle_edit', $vista_interna, true)
		);		  
   
		$this->load->view('template/backend', $vista_externa);
	}

	function view($id){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'result' => $this->codegen_model->row('flash_liquidaciones_ddis_acreditaciones_detalle','*','id = '.$id)
		);

		$vista_externa = array(			
			'title' => ucwords("Liquidacion Acreditacion"),
			'contenido_main' => $this->load->view('components/liquidaciones/liquidaciones_ddis_acreditaciones_detalle/liquidaciones_ddis_acreditaciones_detalle_view', $vista_interna, true)
		);		  
   
		$this->load->view('template/view', $vista_externa);
	}

	function json($id){
		$json = $this->codegen_model->row('flash_liquidaciones_ddis_acreditaciones_detalle','*','id = '.$id);
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}

	function json_all(){
		$json = $this->codegen_model->get('flash_liquidaciones_ddis_acreditaciones_detalle','*','');
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}
	
	function delete($id){
		$this->codegen_model->delete('flash_liquidaciones_ddis_acreditaciones_detalle','id',$id);             
	}
        
        function getDdisAutorizadores($id = null){
            $where = $id == null?" ":" WHERE la.id = ".$id;
            $query = "  SELECT  la.id , 
                                CONCAT(u.apellido,', ',u.nombre) apellidonombre,
                                (CASE WHEN la.nivel_autorizacion = 1 THEN 'GERENCIA' WHEN la.nivel_autorizacion = 2 THEN 'SUPERVISOR' ELSE '' END) nivel_autorizacion,
                                (CASE WHEN la.activo = 0 THEN 'INACTIVO' WHEN la.activo = 1 THEN 'ACTIVO' ELSE '' END) estado, 
                                u.sucursal_id, u.nombre,u.apellido, u.id usuario_id 
                        FROM flash_liquidaciones_ddis_autorizadores la
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
                        INNER JOIN flash_liquidaciones_ddis_acreditaciones_detalle lad ON lad.liquidacion_ddi_id = l.id
                        INNER JOIN flash_liquidaciones_ddis_autorizadores a ON lad.autorizador_id = a.id
                        INNER JOIN flash_liquidaciones_ddis_acreditaciones ac ON lad.acreditacion_id = ac.id
                        INNER JOIN flash_sucursales_carteros c ON lad.cartero_id = c.id
                        INNER JOIN users u ON a.user_id = u.id
                        WHERE l.id = ".$id." "
                        . $and;
//echo $query;die;
        return $this->db->query($query)->result();
    }
    
    public function autorizarAcreditacionDdi($liquidacion_ddi_id, $acreditacion_ddi_detalle_id){
            $user_row = $this->ion_auth->user()->row();
            
            $query = " UPDATE flash_liquidaciones_ddis_acreditaciones_detalle lad "
                    . "SET  "
//                    . "lad.autorizador_id = ".$user_row->id.", " Lo saco porque no puedo actualizar este campo por foreginkey porque 
                                                                 //No puede ser distinto el que yo puse que iba a autorizar del que autoriza
                                                                 //Es decir que solo tendria que permitir apretar el boton del autorizacion al 
                                                                 //autorizador logueado
                          . "lad.fecha_autorizacion  = CASE WHEN lad.`fecha_autorizacion` IS NULL THEN '".date('Y-m-d H:m:s')."' ELSE NULL END,"
                          . "lad.update = '".date('Y-m-d H:m:s')."', "
                          . "lad.update_user_id = ". $user_row->id." "
                    . "WHERE lad.id = ".$acreditacion_ddi_detalle_id;
//echo $query;die;
            $json = $this->db->query($query);
            redirect(base_url().'liquidaciones/liquidaciones_ddis_acreditaciones_detalle/edit/'.$liquidacion_ddi_id);
        }
        
}

/* End of file liquidaciones_ddis_acreditaciones_detalle.php */
/* Location: ./system/application/controllers/liquidaciones_ddis_acreditaciones_detalle.php */