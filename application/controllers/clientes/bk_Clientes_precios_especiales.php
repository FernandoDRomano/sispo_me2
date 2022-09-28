<?php

class Clientes_precios_especiales extends CI_Controller {

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
			'grupos' => $this->codegen_model->get('flash_servicios_grupos', 'id, nombre', ''),
			'servicios' => $this->codegen_model->get('flash_servicios', 'id, nombre', '')
		);

		$vista_externa = array(			
			'title' => ucwords("precios especiales"),
			'contenido_main' => $this->load->view('components/clientes/clientes_precios_especiales/clientes_precios_especiales_list', $vista_interna, true)
		);		
		
		$this->load->view('template/backend', $vista_externa);
	}

	function filtro(){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'results' => $this->flash_md->getPreciosEspeciales($this->input->post('grupo'), $this->input->post('servicio'), $this->input->post('activo'), $this->input->post('buscar')),
			'grupos' => $this->codegen_model->get('flash_servicios_grupos', 'id, nombre', ''),
			'servicios' => $this->codegen_model->get('flash_servicios', 'id, nombre', '')
		);

		$vista_externa = array(			
			'title' => ucwords("precios especiales"),
			'contenido_main' => $this->load->view('components/clientes/clientes_precios_especiales/clientes_precios_especiales_list', $vista_interna, true)
		);		
		
		$this->load->view('template/backend', $vista_externa);
	}

	function add(){
		if ($this->input->post('enviar_form')){
			$data = array(
					'cliente_id' => $this->input->post('cliente_id'),
					'grupo_servicio_id' => $this->input->post('grupo_servicio_id'),
					'servicio_id' => $this->input->post('servicio_id'),
					'nombre' => $this->input->post('nombre'),
					'precio' => $this->input->post('precio'),
					'activo' => $this->input->post('activo')
				);
			$this->codegen_model->add('flash_clientes_precios_especiales',$data);
			redirect(base_url().'clientes/clientes_precios_especiales');
		}		  
   
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'clientes' => $this->codegen_model->get('flash_clientes','*',''),
			'grupos' => $this->codegen_model->get('flash_servicios_grupos','*','')
		);

		$vista_externa = array(			
			'title' => ucwords("precios especiales"),
			'contenido_main' => $this->load->view('components/clientes/clientes_precios_especiales/clientes_precios_especiales_add', $vista_interna, true)
		);		  
   
		$this->load->view('template/backend', $vista_externa);
	}	

	function edit($id){
	    
	    
		if ($this->input->post('enviar_form')){
			$data = array(
					'cliente_id' => $this->input->post('cliente_id'),
					'grupo_servicio_id' => $this->input->post('grupo_servicio_id'),
					'servicio_id' => $this->input->post('servicio_id'),
					'nombre' => $this->input->post('nombre'),
					'precio' => $this->input->post('precio'),
					'activo' => $this->input->post('activo'),
					'update' => date('Y-m-d H:m:s')
				);
			$this->codegen_model->edit('flash_clientes_precios_especiales',$data,'id',$this->input->post('id'));
			redirect(base_url().'clientes/clientes_precios_especiales');
		}

		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'result' => $this->codegen_model->row('flash_clientes_precios_especiales','*','id = '.$id),
			'clientes' => $this->codegen_model->get('flash_clientes','*',''),
			'grupos' => $this->codegen_model->get('flash_servicios_grupos','*','')
		);

		$vista_externa = array(			
			'title' => ucwords("precios especiales"),
			'contenido_main' => $this->load->view('components/clientes/clientes_precios_especiales/clientes_precios_especiales_edit', $vista_interna, true)
		);		  
   
		$this->load->view('template/backend', $vista_externa);
	}

	function view($id){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'result' => $this->codegen_model->row('flash_clientes_precios_especiales','*','id = '.$id)
		);

		$vista_externa = array(			
			'title' => ucwords("precios especiales"),
			'contenido_main' => $this->load->view('components/clientes/clientes_precios_especiales/clientes_precios_especiales_view', $vista_interna, true)
		);		  
   
		$this->load->view('template/fancybox', $vista_externa);
	}

	function json($id){
		$json = $this->codegen_model->row('flash_clientes_precios_especiales','*','id = '.$id);
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}

	function json_all(){
		$json = $this->codegen_model->get('flash_clientes_precios_especiales','*','');
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}
	
	function delete($id){
		$this->codegen_model->delete('flash_clientes_precios_especiales','id',$id);             
	}
        
        function listado(){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'results' => $this->flash_md->getServiciosClientesALL(),
		);

		$vista_externa = array(			
			'title' => ucwords("Listado Servicios por Cliente"),
			'contenido_main' => $this->load->view('components/clientes/clientes_precios_especiales/clientes_precios_especiales_listado', $vista_interna, true)
		);		
		
		$this->load->view('template/backend', $vista_externa);
	}
        
        public function exportarAExcel(){
            $clientes_servicios = $this->flash_md->getServiciosClientesALL();
            //load our new PHPExcel library
            $this->load->library('excel');
            //activate worksheet number 1
            $this->excel->setActiveSheetIndex(0);
            //name the worksheet
            $this->excel->getActiveSheet()->setTitle('Servicios por clientes');
            //set cabeceras
            $this->excel->getActiveSheet()->setCellValue("A1",'Cliente');
            $this->excel->getActiveSheet()->setCellValue("B1",'CUIT');
            $this->excel->getActiveSheet()->setCellValue("C1",'IVA');
            $this->excel->getActiveSheet()->setCellValue("D1",'Servicio');
            $this->excel->getActiveSheet()->setCellValue("E1",'Precio');
            
            // set cells values
            $this->load->library('simplehtmldom_1_5/simple_html_dom'); 
            $i = 2;
            foreach ($clientes_servicios as $value) {
                    // echo $trs->find('td',1)->innertext;
                    $this->excel->getActiveSheet()->setCellValue("A$i",$value->cliente);
                    $this->excel->getActiveSheet()->setCellValue("B$i",$value->cuit);
                    $this->excel->getActiveSheet()->setCellValue("C$i",$value->iva);
                    $this->excel->getActiveSheet()->setCellValue("D$i",$value->servicio);
                    $this->excel->getActiveSheet()->setCellValue("E$i",$value->precio);
                    $i++;
            }
            
            $this->excel->setActiveSheetIndex(0);
            $filename='Clientes_servicios.xls'; //save our workbook as this file name

            header('Content-Type: application/vnd.ms-excel'); //mime type

            header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name

            header('Cache-Control: max-age=0'); //no cache

            //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
            //if you want to save it as .XLSX Excel 2007 format

            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5'); 

            //force user to download the Excel file without writing it to server's HD
            $objWriter->save('php://output');
    }

	function indexPreciosEspecialesPorcentajes(){
	    if ($this->input->post('enviar_form')){
			$data = array(
					'cliente_id' => $this->input->post('cliente_id'),
					'grupo_servicio_id' => $this->input->post('grupo_servicio_id'),
					'servicio_id' => $this->input->post('servicio_id'),
					'nombre' => $this->input->post('nombre'),
					'precio' => $this->input->post('precio'),
					'activo' => $this->input->post('activo')
				);
			$this->codegen_model->add('flash_clientes_precios_especiales',$data);
			redirect(base_url().'clientes/clientes_precios_especiales');
		}		  
   
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'clientes' => $this->codegen_model->get('flash_clientes','*',''),
			'results' => array(),
			'cliente_id' => 0,
		);

		$vista_externa = array(			
			'title' => ucwords("precios especiales"),
			'contenido_main' => $this->load->view('components/clientes/clientes_precios_especiales/clientes_precios_especiales_edit_porc', $vista_interna, true)
		);		  
   
		$this->load->view('template/backend', $vista_externa);
	}

	function filtroPreciosEspecialesPorcentajes(){
		//var_dump($_POST);
		$cliente_id = $this->input->post('cliente_id');

		//echo $cliente_id;
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'results' => $this->flash_md->getPreciosEspecialesXCliente($this->input->post('grupo'), $this->input->post('servicio'), $this->input->post('activo'), $this->input->post('buscar'), $this->input->post('cliente_id')),
			'grupos' => $this->codegen_model->get('flash_servicios_grupos', 'id, nombre', ''),
			'servicios' => $this->codegen_model->get('flash_servicios', 'id, nombre', ''),
			'clientes' => $this->codegen_model->get('flash_clientes','*',''),
			'cliente_id' => $cliente_id,
			
		);

		$vista_externa = array(			
			'title' => ucwords("precios especiales"),
			'contenido_main' => $this->load->view('components/clientes/clientes_precios_especiales/clientes_precios_especiales_edit_porc', $vista_interna, true)
		);		
		
		$this->load->view('template/backend', $vista_externa);
	}

	function guardarPreciosEspecialesPorcentajes(){
		
		$cliente_id = $this->input->post("cliente_id");
		$porcentaje = $this->input->post("porcentaje");
		$fecha_vto = new DateTime($this->input->post("fecha_vto"));
		$fecha_vto = $fecha_vto->format('Y-m-d');
		$user_row = $this->ion_auth->user()->row();
		$servicios_id = $this->input->post("servicios_id");

		if ($servicios_id != NULL && $porcentaje != ""){
			foreach ($servicios_id as $value){
				$servicios_id[] = $value;
			}
			
			$data = array(
				'usuario_creacion_id' => $user_row->id,
				'tipo_id' => 5,//En la tabla opciones el 5 es el id de Precios Especiales,
				'fecha_creacion' => date('Y-m-d')
			);
			
			$actualizacion = $this->codegen_model->add('flash_actualizacion_precios', $data);
			$precios_especiales = $this->codegen_model->get('flash_clientes_precios_especiales','*','cliente_id = '.$this->input->post('cliente_id').' AND servicio_id IN ('.implode(",",$servicios_id).')');
			//echo($this->db->last_query());die;  
			
				foreach ($precios_especiales as $f) {
					//Actualizo la tabla ACTUALIZACION_PRECIOS_ESPECIALES
					$precio_nuevo = floatval($f->precio) + (floatval($f->precio) * floatval($porcentaje) / 100);
						$data = array(
										'actualizacion_id' => $actualizacion,
										'cliente_precio_especial_id' => $f->id,
										'servicio_id' => $f->servicio_id,
										'precio_anterior' => $f->precio ,
										'precio_nuevo' => $precio_nuevo,
										'fecha_vto_anterior' => $f->fecha_vto,
										'fecha_vto_posterior' => $fecha_vto,
										'activo' => 1,
										'user_id_create' => $user_row->id,
										'user_id_update' => NULL,
										'date_create' => date('Y-m-d'),
										'date_update' => NULL,
								);
						$this->codegen_model->add('flash_actualizacion_precios_especiales',$data);
					//Actualizo la tabla CLIENTES_PRECIOS_ESPECIALES
						$data2 = array(
								'precio' => $precio_nuevo,
								'fecha_vto' => $fecha_vto,
								'update_user_id' => $user_row->id,
								'update' => date('Y-m-d'),
						);
						
						$this->codegen_model->edit('flash_clientes_precios_especiales',$data2,'id',$f->id);
						//echo $this->db->last_query();die;
						
				}
			}
		//echo $cliente_id;
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'clientes' => $this->codegen_model->get('flash_clientes','*',''),
			'results' => array(),
			'cliente_id' => 0,
		);

		$vista_externa = array(			
			'title' => ucwords("precios especiales"),
			'contenido_main' => $this->load->view('components/clientes/clientes_precios_especiales/clientes_precios_especiales_edit_porc', $vista_interna, true)
		);		
		
		$this->load->view('template/backend', $vista_externa);
	}
	
	
}

/* End of file clientes_precios_especiales.php */
/* Location: ./system/application/controllers/clientes_precios_especiales.php */