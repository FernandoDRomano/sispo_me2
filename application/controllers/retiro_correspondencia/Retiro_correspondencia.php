<?php


class Retiro_correspondencia extends CI_Controller {

	private $permisos;
	
	function __construct() {
		parent::__construct();
		$this->permisos = $this->permisos_lib->control();
	}	
	
	function index(){
            $vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'results_pendientes' => $this->flash_md->getRetiroCorrespondencia('P'),
                        'results_recibidos' => $this->flash_md->getRetiroCorrespondencia('R'),
                        'results_cancelados' => $this->flash_md->getRetiroCorrespondencia('C'),
                        'sucursales' => Sucursal::all(),
                        'clientes' => $this->codegen_model->get('flash_clientes','*','')
            );

            $vista_externa = array(			
                    'title' => ucwords("retiro_correspondencia"),
                    'contenido_main' => $this->load->view('components/piezas/retiro_correspondencia/retiro_correspondencia_list', $vista_interna, true)
            );		

            $this->load->view('template/backend', $vista_externa);
	}

        public function filtro()
        {
            $sucursal_id = $this->input->post('sucursal_id');

            $vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'results_pendientes' => $this->flash_md->getRetiroCorrespondencia('P',$sucursal_id),
                        'results_recibidos' => $this->flash_md->getRetiroCorrespondencia('R',$sucursal_id),
                        'results_cancelados' => $this->flash_md->getRetiroCorrespondencia('C',$sucursal_id),
                        'sucursales' => Sucursal::all(),
                        'clientes' => $this->codegen_model->get('flash_clientes','*','')
            );

            $vista_externa = array(			
                    'title' => ucwords("retiro_correspondencia"),
                    'contenido_main' => $this->load->view('components/piezas/retiro_correspondencia/retiro_correspondencia_list', $vista_interna, true)
            );		

            $this->load->view('template/backend', $vista_externa);
        }
        
	function add(){
		if ($this->input->post('enviar_form')){
                        $f = strtotime($this->input->post('fecha_retirar'));
                        $fecha_retirar = date('Y-m-d',$f );
			$data = array(
					'fecha_retirar' => $fecha_retirar,
					'hora_retirar' => $this->input->post('hora_retirar'),
					'cliente_id' => $this->input->post('cliente_id'),
					'domicilio' => $this->input->post('domicilio'),
					'servicio_id' => $this->input->post('servicio'),
					'pasado_a' => $this->input->post('pasado_a'),
					'hora_limite_retirar' => $this->input->post('hora_limite_retirar'),
					'observaciones' => $this->input->post('observaciones'),
					'aviso' => $this->input->post('aviso'),
					'estado' => $this->input->post('estado'),
                                        'sucursal_id' => $this->usuario->sucursal_id,
					'user_id_create' => $this->ion_auth->user()->row()->id,
					'user_id_update' => $this->input->post('user_id_update'),
					'date_create' => date('Y-m-d H:m:s'),
					'date_update' => $this->input->post('date_update')
				);
			$this->codegen_model->add('flash_retiro_correspondencia',$data);
			redirect(base_url().'retiro_correspondencia/retiro_correspondencia/');
		}		  
   
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
                        'servicios' => $this->codegen_model->get('flash_servicios','*',''),
                        'clientes' => $this->codegen_model->get('flash_clientes','*','')
		);

		$vista_externa = array(			
			'title' => ucwords("retiro_correspondencia"),
			'contenido_main' => $this->load->view('components/piezas/retiro_correspondencia/retiro_correspondencia_add', $vista_interna, true)
		);		  
   
		$this->load->view('template/backend', $vista_externa);
	}	

	function edit($id){
		if ($this->input->post('enviar_form')){
                    $f = strtotime($this->input->post('fecha_retirar'));
                    $fecha_retirar = date('Y-m-d',$f );
			$data = array(
					'fecha_retirar' => $fecha_retirar,
					'hora_retirar' => $this->input->post('hora_retirar'),
					'cliente_id' => $this->input->post('cliente_id'),
					'domicilio' => $this->input->post('domicilio'),
					'servicio_id' => $this->input->post('servicio'),
					'pasado_a' => $this->input->post('pasado_a'),
					'hora_limite_retirar' => $this->input->post('hora_limite_retirar'),
					'observaciones' => $this->input->post('observaciones'),
					'aviso' => $this->input->post('aviso'),
					'estado' => $this->input->post('estado'),
                                        'sucursal_id' => $this->usuario->sucursal_id,
					'user_id_update' => $this->ion_auth->user()->row()->id,
					'date_update' => date('Y-m-d H:m:s')
				);
			$this->codegen_model->edit('flash_retiro_correspondencia',$data,'id',$this->input->post('id'));
			redirect(base_url().'retiro_correspondencia/retiro_correspondencia');
		}
                $retiro_correspondencia = $this->codegen_model->row('flash_retiro_correspondencia','*','id = '.$id);
                $cliente = $this->codegen_model->row('flash_clientes','*','id = '.$retiro_correspondencia->cliente_id);
                $servicio = $this->codegen_model->row('flash_clientes_precios_especiales','*','id = '.$retiro_correspondencia->servicio_id);
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'result' => $retiro_correspondencia,
                        'servicios' => $this->codegen_model->get('flash_clientes_precios_especiales','*','cliente_id = '.$cliente->id),
                        'cliente' => $cliente,
                        'servicio' => $servicio,
		);

		$vista_externa = array(			
			'title' => ucwords("retiro_correspondencia"),
			'contenido_main' => $this->load->view('components/piezas/retiro_correspondencia/retiro_correspondencia_edit', $vista_interna, true)
		);		  
   
		$this->load->view('template/backend', $vista_externa);
	}

	function view($id){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
                        'result' => $this->flash_md->getViewRetiroCorrespondencia($id),
                                    
			//'result' => $this->codegen_model->row('flash_retiro_correspondencia','*','id = '.$id)
		);

		$vista_externa = array(			
			'title' => ucwords("retiro_correspondencia"),
			'contenido_main' => $this->load->view('components/piezas/retiro_correspondencia/retiro_correspondencia_view', $vista_interna, true)
		);		  
   
		$this->load->view('template/view', $vista_externa);
	}

	function json($id){
		$json = $this->codegen_model->row('flash_retiro_correspondencia','*','id = '.$id);
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}

	function json_all(){
		$json = $this->codegen_model->get('flash_retiro_correspondencia','*','');
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}
	
	function delete($id){
		$this->codegen_model->delete('flash_retiro_correspondencia','id',$id);             
	}
}

/* End of file retiro_correspondencia.php */
/* Location: ./system/application/controllers/retiro_correspondencia.php */