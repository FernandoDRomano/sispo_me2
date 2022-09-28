<?php

class Liquidaciones_ddis_autorizadores extends CI_Controller {

	private $permisos;
	
	function __construct() {
		parent::__construct();
		$this->permisos = $this->permisos_lib->control();
	}	
	
	function index(){
                
            $results = $this->getDdisAutorizadores();
            $vista_interna = array(
                    'permisos_efectivos' => $this->permisos,
                    'results' => count($results > 0 )?$results:[],
            );

            $vista_externa = array(			
                    'title' => ucwords("Autorizadores de Liquidaciones"),
                    'contenido_main' => $this->load->view('components/liquidaciones/liquidaciones_ddis_autorizadores/liquidaciones_ddis_autorizadores_list', $vista_interna, true)
            );		

            $this->load->view('template/backend', $vista_externa);
	}

	function add(){
		if ($this->input->post('enviar_form')){
                        $user_row = $this->ion_auth->user()->row();
			$data = array(
					'user_id' => $this->input->post('usuario_id'),
					'nivel_autorizacion' => $this->input->post('nivel_autorizacion'),
                                        'activo' => $this->input->post('estado'),
					'create_user_id' => $user_row->id,
				);
			$this->codegen_model->add('flash_ddis_liquidaciones_autorizadores',$data);
//                         echo $this->db->last_query();die;
			redirect(base_url().'liquidaciones/liquidaciones_ddis_autorizadores');
		}		  

		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
                        'usuarios' => $this->flash_md->getUsers()
		);
              

		$vista_externa = array(			
			'title' => ucwords("Autorizadores de Liquidaciones"),
			'contenido_main' => $this->load->view('components/liquidaciones/liquidaciones_ddis_autorizadores/liquidaciones_ddis_autorizadores_add', $vista_interna, true)
		);		  
   
		$this->load->view('template/backend', $vista_externa);
	}	

	function edit($id){
		if ($this->input->post('enviar_form')){
                    $user_row = $this->ion_auth->user()->row();
                    $data = array(
                                'user_id' => $this->input->post('usuario_id'),
                                'nivel_autorizacion' => $this->input->post('nivel_autorizacion'),
                                'activo' => $this->input->post('estado'),
                                'update_user_id' => $user_row->id,
                            );
                    $this->codegen_model->edit('flash_ddis_liquidaciones_autorizadores',$data,'id',$id);
//                     echo $this->db->last_query();die;
                    redirect(base_url().'liquidaciones/liquidaciones_ddis_autorizadores');
		}

                $results = $this->getDdisAutorizadores($id);
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'results' => $results[0]
		);

		$vista_externa = array(			
			'title' => ucwords("Autorizadores de Liquidaciones"),
			'contenido_main' => $this->load->view('components/liquidaciones/liquidaciones_ddis_autorizadores/liquidaciones_ddis_autorizadores_edit', $vista_interna, true)
		);		  
   
		$this->load->view('template/backend', $vista_externa);
	}

	function view($id){
                $results = $this->getDdisAutorizadores($id);
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'result' => $results[0]
		);
                
		$vista_externa = array(			
			'title' => ucwords("Autorizadores de Liquidaciones"),
			'contenido_main' => $this->load->view('components/liquidaciones/liquidaciones_ddis_autorizadores/liquidaciones_ddis_autorizadores_view', $vista_interna, true)
		);		  
   
		$this->load->view('template/view', $vista_externa);
	}

	function json($id){
		$json = $this->codegen_model->row('flash_ddis_liquidaciones_autorizadores','*','id = '.$id);
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}

	function json_all(){
		$json = $this->codegen_model->get('flash_ddis_liquidaciones_autorizadores','*','');
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}
	
	function delete($id){
		$this->codegen_model->delete('flash_ddis_liquidaciones_autorizadores','id',$id);             
	}
        
        function getDdisAutorizadores($id = null){
            $where = $id == null?" ":" WHERE la.id = ".$id;
            $query = "  SELECT  la.id , 
                                (CASE WHEN la.nivel_autorizacion = 1 THEN 'GERENCIA' WHEN la.nivel_autorizacion = 2 THEN 'SUPERVISOR' ELSE '' END) nivel_autorizacion,
                                (CASE WHEN la.activo = 0 THEN 'INACTIVO' WHEN la.activo = 1 THEN 'ACTIVO' ELSE '' END) estado, 
                                u.sucursal_id, u.nombre,u.apellido, u.id usuario_id 
                        FROM flash_ddis_liquidaciones_autorizadores la
                        INNER JOIN users u ON  la.user_id = u.id "
                        .$where;
                        return $this->db->query($query)->result();
        }
    }
/* End of file liquidaciones_autorizadores.php */
/* Location: ./system/application/controllers/liquidaciones_autorizadores.php */