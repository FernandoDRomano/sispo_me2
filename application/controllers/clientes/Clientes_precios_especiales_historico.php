<?php

class Clientes_precios_especiales_historico extends CI_Controller  {

	private $permisos;
	
	function __construct() {
		parent::__construct();
		//$this->permisos = $this->permisos_lib->control();
		$this->load->model('flash_md');
	}	
	
	function index(){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'results' => array(),
			'grupos' => $this->codegen_model->get('flash_servicios_grupos', 'id, nombre', ''),
			'servicios' => array(),//$this->codegen_model->get('flash_servicios', 'id, nombre', ''),
			'clientes' => $this->codegen_model->get('flash_clientes','*',''),
			'servicio_id' => 0,
		);

		$vista_externa = array(			
			'title' => ucwords("historico de precios especiales"),
			'contenido_main' => $this->load->view('components/clientes/clientes_precios_especiales/clientes_precios_especiales_historico', $vista_interna, true)
		);		
		
		$this->load->view('template/backend', $vista_externa);
	}

	function filtro(){
			$cliente_id =  $this->input->post('cliente_id');
			$fecha_desde = $this->input->post('fecha_desde');
			$fecha_hasta = $this->input->post('fecha_hasta');
			$servicio_id = $this->input->post('servicio_id');

			$query = $this->db
                    ->select('s.id, pe.nombre')
                    ->join('flash_clientes_precios_especiales pe', 'pe.servicio_id = s.id')
                    ->where('pe.cliente_id = '.$cliente_id)
                    ->get('flash_servicios s');
            //echo $this->db->last_query();die;
            $json = $query->result();

			$vista_interna = array(
					'permisos_efectivos' => $this->permisos,
					'cliente_id' => $cliente_id,
					//'grupo_servicio_id' => $this->input->post('grupo_servicio_id'),
					'servicio_id' => $servicio_id,
					'fecha_desde' => $fecha_desde,
					'fecha_hasta' => $fecha_hasta,
					'clientes' => $this->codegen_model->get('flash_clientes','*',''),
					//'activo' => $this->input->post('activo'),
					'results' => $this->flash_md->getHistoricoPreciosEspeciales($cliente_id, $fecha_desde, $fecha_hasta, $servicio_id),
					'servicios' => $json,
				);
				
				
				$vista_externa = array(			
					'title' => ucwords("historico de precios especiales"),
					'contenido_main' => $this->load->view('components/clientes/clientes_precios_especiales/clientes_precios_especiales_historico', $vista_interna, true)
				);		
				
				$this->load->view('template/backend', $vista_externa);
	}

        
	public function exportarAExcel(){
		
		$cliente_id =  $this->input->post('excel_cliente_id');
		$fecha_desde = $this->input->post('excel_desde');
		$fecha_hasta = $this->input->post('excel_hasta');
		$servicio_id = $this->input->post('excel_servicio_id');

		$resultados = $this->flash_md->getHistoricoPreciosEspeciales($cliente_id, $fecha_desde, $fecha_hasta, $servicio_id);

		//load our new PHPExcel library
		$this->load->library('excel');
		//activate worksheet number 1
		$this->excel->setActiveSheetIndex(0);
		//name the worksheet
		$this->excel->getActiveSheet()->setTitle('Histórico de Precios Especiales');
		//set cabeceras
		$this->excel->getActiveSheet()->setCellValue("A1",'Cliente');
		$this->excel->getActiveSheet()->setCellValue("B1",'Servicio');
		$this->excel->getActiveSheet()->setCellValue("C1",'Fecha Vto.');
		$this->excel->getActiveSheet()->setCellValue("D1",'Ult. Act.');
		$this->excel->getActiveSheet()->setCellValue("E1",'Precio Aterior');
		$this->excel->getActiveSheet()->setCellValue("F1",'Precio Nuevo');
		$this->excel->getActiveSheet()->setCellValue("G1",'Usuario');
		
		// set cells values
		$this->load->library('simplehtmldom_1_5/simple_html_dom'); 
		$i = 2;
		foreach ($resultados as $value) {
				// echo $trs->find('td',1)->innertext;
				$this->excel->getActiveSheet()->setCellValue("A$i",$value->cliente);
				$this->excel->getActiveSheet()->setCellValue("B$i",$value->servicio);
				$this->excel->getActiveSheet()->setCellValue("C$i",$value->fecha_vto);
				$this->excel->getActiveSheet()->setCellValue("D$i",$value->ultima_actualizacion);
				$this->excel->getActiveSheet()->setCellValue("E$i",$value->precio_anterior);
				$this->excel->getActiveSheet()->setCellValue("F$i",$value->precio_nuevo);
				$this->excel->getActiveSheet()->setCellValue("G$i",$value->username);
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
}

/* End of file clientes_precios_especiales.php */
/* Location: ./system/application/controllers/clientes_precios_especiales.php */