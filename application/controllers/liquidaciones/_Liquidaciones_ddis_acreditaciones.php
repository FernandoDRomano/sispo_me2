<?php

class Liquidaciones_ddis_acreditaciones extends CI_Controller {

	private $permisos;
	
	function __construct() {
		parent::__construct();
		$this->permisos = $this->permisos_lib->control();
	}	
	
	function index(){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'results' => $this->getConceptosAcreditaciones()
		);

		$vista_externa = array(			
			'title' => ucwords("Conceptos de Acreditaciones"),
			'contenido_main' => $this->load->view('components/liquidaciones/liquidaciones_ddis_acreditaciones/liquidaciones_ddis_acreditaciones_list', $vista_interna, true)
		);		
		
		$this->load->view('template/backend', $vista_externa);
	}

	function add(){
		if ($this->input->post('enviar_form')){
                        $user_row = $this->ion_auth->user()->row();
			$data = array(
					'concepto' => $this->input->post('concepto'),
					'importe' => $this->input->post('importe'),
					'tipo_concepto_id' => $this->input->post('tipo_concepto_id'),
					'user_id_create' => $user_row->id,
				);
			$this->codegen_model->add('flash_liquidaciones_ddis_acreditaciones',$data);
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

	function edit($id){
		if ($this->input->post('enviar_form')){
                    $user_row = $this->ion_auth->user()->row();
                    $data = array(
                                    'concepto' => $this->input->post('concepto'),
                                    'importe' => $this->input->post('importe'),
                                    'tipo_concepto_id' => $this->input->post('tipo_concepto_id'),
                                    'update' => date("Y-m-d H:i:s"),
                                    'user_id_update' => $user_row->id
                            );
                    $this->codegen_model->edit('flash_liquidaciones_ddis_acreditaciones',$data,'id',$id);
//                    echo $this->db->last_query();die;
                    redirect(base_url().'liquidaciones/liquidaciones_ddis_acreditaciones');
		}
                $result = $this->getConceptosAcreditaciones($id);
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'result' => $result[0]
		);

		$vista_externa = array(			
			'title' => ucwords("Conceptos de Acreditaciones"),
			'contenido_main' => $this->load->view('components/liquidaciones/liquidaciones_ddis_acreditaciones/liquidaciones_ddis_acreditaciones_edit', $vista_interna, true)
		);		  
   
		$this->load->view('template/backend', $vista_externa);
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
		$this->codegen_model->delete('flash_liquidaciones_ddis_acreditaciones','id',$id);             
	}
        
        function getConceptosAcreditaciones($id = null){
            $where = $id == null?" ":" WHERE ca.id = ".$id;
            $query = "  SELECT  ca.*, 
                        (CASE 	WHEN ca.tipo_concepto_id = 1 THEN 'GENERAL' 
                                WHEN ca.tipo_concepto_id = 2 THEN 'INDIVIDUAL' 
                                ELSE '' END) tipo_concepto
                        FROM flash_liquidaciones_ddis_acreditaciones ca"
                        .$where;
                        return $this->db->query($query)->result();
        }
}

/* End of file liquidaciones_ddis_acreditaciones.php */
/* Location: ./system/application/controllers/liquidaciones_ddis_acreditaciones.php */