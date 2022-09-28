<?php

class Liquidaciones_ddis_descuentos extends CI_Controller {

	private $permisos;
	
	function __construct() {
		parent::__construct();
		$this->permisos = $this->permisos_lib->control();
	}	
	
	function index(){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'results' => $this->getConceptosDescuentos()
		);

		$vista_externa = array(			
			'title' => ucwords("Conceptos Descuentos"),
			'contenido_main' => $this->load->view('components/liquidaciones/liquidaciones_ddis_descuentos/liquidaciones_ddis_descuentos_list', $vista_interna, true)
		);		
		
		$this->load->view('template/backend', $vista_externa);
	}

	function add(){
		if ($this->input->post('enviar_form')){
			$data = array(
					'concepto' => $this->input->post('concepto'),
					'importe' => $this->input->post('importe'),
					'tipo_concepto_id' => $this->input->post('tipo_concepto_id'),
					'user_id_create' => $user_row->id,
				);
			$this->codegen_model->add('flash_liquidaciones_ddis_descuentos',$data);
			redirect(base_url().'liquidaciones/liquidaciones_ddis_descuentos');
		}		  
   
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos
		);

		$vista_externa = array(			
			'title' => ucwords("Conceptos Descuentos"),
			'contenido_main' => $this->load->view('components/liquidaciones/liquidaciones_ddis_descuentos/liquidaciones_ddis_descuentos_add', $vista_interna, true)
		);		  
   
		$this->load->view('template/backend', $vista_externa);
	}	

	function edit($id){
		if ($this->input->post('enviar_form')){
			$data = array(
                                    'concepto' => $this->input->post('concepto'),
                                    'importe' => $this->input->post('importe'),
                                    'tipo_concepto_id' => $this->input->post('tipo_concepto_id'),
                                    'update' => date("Y-m-d H:i:s"),
                                    'user_id_update' => $user_row->id
				);
			$this->codegen_model->edit('flash_liquidaciones_ddis_descuentos',$data,'id',$id);
			redirect(base_url().'liquidaciones/liquidaciones_ddis_descuentos');
		}
                $result = $this->getConceptosDescuentos($id);
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'result' => $result[0]
		);

		$vista_externa = array(			
			'title' => ucwords("Conceptos Descuentos"),
			'contenido_main' => $this->load->view('components/liquidaciones/liquidaciones_ddis_descuentos/liquidaciones_ddis_descuentos_edit', $vista_interna, true)
		);		  
   
		$this->load->view('template/backend', $vista_externa);
	}

	function view($id){
                $result = $this->getConceptosDescuentos($id);
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'result' => $result[0]
		);

		$vista_externa = array(			
			'title' => ucwords("Conceptos Descuentos"),
			'contenido_main' => $this->load->view('components/liquidaciones/liquidaciones_ddis_descuentos/liquidaciones_ddis_descuentos_view', $vista_interna, true)
		);		  
   
		$this->load->view('template/view', $vista_externa);
	}

	function json($id){
		$json = $this->codegen_model->row('flash_liquidaciones_ddis_descuentos','*','id = '.$id);
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}

	function json_all(){
		$json = $this->codegen_model->get('flash_liquidaciones_ddis_descuentos','*','');
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}
	
	function delete($id){
		$this->codegen_model->delete('flash_liquidaciones_ddis_descuentos','id',$id);             
	}
                
        function getConceptosDescuentos($id = null){
            $where = $id == null?" ":" WHERE cd.id = ".$id;
            $query = "  SELECT  cd.*, 
                        (CASE 	WHEN cd.tipo_concepto_id = 1 THEN 'GENERAL' 
                                WHEN cd.tipo_concepto_id = 2 THEN 'INDIVIDUAL' 
                                ELSE '' END) tipo_concepto
                        FROM flash_liquidaciones_ddis_descuentos cd"
                        .$where;
                        return $this->db->query($query)->result();
        }
}

/* End of file liquidaciones_ddis_descuentos.php */
/* Location: ./system/application/controllers/liquidaciones_ddis_descuentos.php */