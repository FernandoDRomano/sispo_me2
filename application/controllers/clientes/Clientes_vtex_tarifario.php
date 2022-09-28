<?php

class Clientes_vtex_tarifario extends CI_Controller
{

	private $permisos;

	function __construct()
	{
		parent::__construct();
		$this->permisos = $this->permisos_lib->control();
		$this->load->helper('url');
	}

	function index()
	{
		$queryTarifarios = "SELECT id 'idTarifario', nombre 'nombreTarifario'  
		FROM vtex_tarifarios";

		$tarifarios = $this->db->query($queryTarifarios)->result();

		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'tarifarios' => $tarifarios,
		);

		$vista_externa = array(
			'title' => ucwords("vtex tarifarios"),
			'contenido_main' => $this->load->view('components/clientes/clientes_vtex/clientes_vtex_tarifario_list', $vista_interna, true)
		);

		$this->load->view('template/backend', $vista_externa);
	}


	// ------------------- FILTRO ----------------------
	function filtro()
	{
		$action  = $this->input->post('action');
		$searchIdTarifario  = $this->input->post('searchIdTarifario');

		$queryTarifarios = "SELECT id 'idTarifario', nombre 'nombreTarifario'  
		FROM vtex_tarifarios";

		$tarifarios = $this->db->query($queryTarifarios)->result();


		if ($action == 'search') {

			$queryResult = "SELECT *
				FROM vtex_tarifarios
				WHERE id = '$searchIdTarifario'";

			$result = $this->db->query($queryResult)->row();

			$queryTarifariosPrecios = "SELECT *
			FROM vtex_tarifarios_precios
			WHERE id_vtex_tarifario = $searchIdTarifario AND activo = 1";

			$tarifario_precios = $this->db->query($queryTarifariosPrecios)->result();
		}

		if ($action == 'insert') {
			$result = new stdClass();
			$result->insert = true;
		}


		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'tarifarios' => $tarifarios,
			'result' => $result,
			'tarifario_precios' => $tarifario_precios
		);

		$vista_externa = array(
			'title' => ucwords("clientes vtex tarifarios"),
			'contenido_main' => $this->load->view('components/clientes/clientes_vtex/clientes_vtex_tarifario_list', $vista_interna, true)
		);

		$this->load->view('template/backend', $vista_externa);
	}


	// ========================  INSERT ======================
	function insert()
	{
		ini_set('memory_limit', '-1');
		//  DATOS FORM

		$action  = $this->input->post('action');
		$id = $this->input->post('id');
		$nombre = $this->input->post('nombre');
		$origen = $this->input->post('origen');
		$max_suma_aristas = $this->input->post('max_suma_aristas');
		$max_arista = $this->input->post('max_arista');
		$factor_volumetrico = $this->input->post('factor_volumetrico');
		$factor_peso_minimo = $this->input->post('factor_peso_minimo');
		$minimo_items = $this->input->post('minimo_items');
		$valor_minimo = $this->input->post('valor_minimo');
		$valor_maximo = $this->input->post('valor_maximo');

		//  CONFIG IMPORT EXCEL  

		$path = $_SERVER['DOCUMENT_ROOT'] . '/assets/upload/';
		$config['upload_path']   = $path;
		$config['allowed_types'] = 'xlsx|csv|xls';
		$config['max_size']      = 1024;
		$config['overwrite']     = true;

		$this->load->library('upload', $config);
		$this->upload->initialize($config);

		if (!$this->upload->do_upload('tarifario_precios')) {
			$errorFile = array('error' => $this->upload->display_errors());
		} else {
			$data = array('upload_data' => $this->upload->data());
		}

		//  FIN CONFIG IMPORT EXCEL

		if (empty($errorFile)) {
			try {
				$idTarifario = $this->db->query("SELECT UUID_SHORT() 'id'")->row()->id;

				$InsertTarifario = $this->db->query(
					"INSERT INTO vtex_tarifarios (id, nombre, origen, max_suma_aristas, max_arista, factor_volumetrico, factor_peso_minimo, minimo_items, valor_minimo, valor_maximo)
					VALUES ( ?,?,?,?,?,?,?,?,?,?)",
					array(
						$idTarifario,
						$nombre,
						$origen,
						$max_suma_aristas,
						$max_arista,
						$factor_volumetrico,
						$factor_peso_minimo,
						$minimo_items,
						$valor_minimo,
						$valor_maximo
					)
				);
			} catch (Exception $e) {
				echo "catch\n";
				print_r($e);
			}

			//Obtengo id tarifario insertado y agrego tarifario_precios
			$datosExcel = $this->_datosExcel($path, $data, $idTarifario);
			$this->db->insert_batch('vtex_tarifarios_precios', $datosExcel);
		}

		redirect(base_url() . 'clientes/clientes_vtex_tarifario/', 'refresh');
	}

	// ====================== EDIT ======================	

	function edit()
	{
		ini_set('memory_limit', '-1');
		$this->load->helper('vtex');
		//  DATOS FORM 

		$action  = $this->input->post('action');
		$id = $this->input->post('id');
		$nombre = $this->input->post('nombre');
		$origen = $this->input->post('origen');
		$max_suma_aristas = $this->input->post('max_suma_aristas');
		$max_arista = $this->input->post('max_arista');
		$factor_volumetrico = $this->input->post('factor_volumetrico');
		$factor_peso_minimo = $this->input->post('factor_peso_minimo');
		$minimo_items = $this->input->post('minimo_items');
		$valor_minimo = $this->input->post('valor_minimo');
		$valor_maximo = $this->input->post('valor_maximo');

		//  CONFIG IMPORT EXCEL 

		$path = $_SERVER['DOCUMENT_ROOT'] . '/assets/upload/';
		$config['upload_path']   = $path;
		$config['allowed_types'] = 'xlsx|csv|xls';
		$config['max_size']      = 1024;
		$config['overwrite']     = true;

		$this->load->library('upload', $config);
		$this->upload->initialize($config);

		if (!$this->upload->do_upload('tarifario_precios')) {
			$errorFile = array('error' => $this->upload->display_errors());
		} else {
			$data = array('upload_data' => $this->upload->data());
		}

		//  FIN CONFIG IMPORT EXCEL


		try {
			$this->db->trans_begin();

			$updateTarifario = $this->db->query(
				"UPDATE vtex_tarifarios SET
						nombre = ?,
						origen = ?,
						max_suma_aristas = ?,
						max_arista = ?,
						factor_volumetrico = ?,
						factor_peso_minimo = ?,
						minimo_items = ?,
						valor_minimo = ?,
						valor_maximo = ?
					WHERE id = ?",
				array(
					$nombre,
					$origen,
					$max_suma_aristas,
					$max_arista,
					$factor_volumetrico,
					$factor_peso_minimo,
					$minimo_items,
					$valor_minimo,
					$valor_maximo,
					$id
				)
			);

			// Hay excel/tarifas nuevas
			if (empty($errorFile)) {
				// busco tarifas actuales
				$queryTarifariosPreciosOLD = "SELECT *
					FROM vtex_tarifarios_precios
					WHERE id_vtex_tarifario = $id AND activo = 1";
				$tarifario_preciosOLD = $this->db->query($queryTarifariosPreciosOLD)->result();

				// doy de baja las tarifas actuales
				$this->db->query("UPDATE vtex_tarifarios_precios set activo = 0 WHERE id_vtex_tarifario = '$id'");

				// extraigo los datos de excel 
				$datosExcel = $this->_datosExcel($path, $data, $id);

				// inserto tarifas nuevas
				$this->db->insert_batch('vtex_tarifarios_precios', $datosExcel);

				$queryTarifariosPreciosNuevos = "SELECT *
				FROM vtex_tarifarios_precios
				WHERE id_vtex_tarifario = $id AND activo = 1";

				$tarifario_preciosNuevos = $this->db->query($queryTarifariosPreciosNuevos)->result();

				//$datosExcel = json_decode(json_encode($datosExcel), true);
				//actualizo del lado de vtex las nuevas tarifas
				vtex_updateMasivoShippingPolicy($id,  $tarifario_preciosNuevos, $tarifario_preciosOLD);

				$this->db->trans_commit();
			} else {
				vtex_updateMasivoShippingPolicy($id);
				$this->db->trans_commit();
			}
		} catch (Exception $e) {
			$this->db->trans_rollback();
			show_error($e->getMessage());
		}

		redirect(base_url() . 'clientes/clientes_vtex_tarifario/', 'refresh');
	}





	private function _datosExcel($path, $data, $idTarifario)
	{
		if (!empty($data['upload_data']['file_name'])) {
			$import_xls_file = $data['upload_data']['file_name'];
		} else {
			$import_xls_file = 0;
		}
		$inputFileName = $path . $import_xls_file;

		try {
			$this->load->library('excel');

			$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
			$objReader = PHPExcel_IOFactory::createReader($inputFileType);

			$objPHPExcel = $objReader->load($inputFileName);
			$dataEnExel = $objPHPExcel->getActiveSheet()->toArray(null, false, true, false);
			$cols = count($dataEnExel[0]);

			//agrego los nombres a cada campo para el insert
			for ($i = 1; $i < count($dataEnExel); $i++) {
				for ($j = 1; $j <= $cols; $j++) {
					if (!empty($dataEnExel[$i][0])) {
						$dataQuery[$i]['id_vtex_tarifario'] = $idTarifario;
						$dataQuery[$i]['activo'] = 1;
						$dataQuery[$i][$dataEnExel[0][$j - 1]] = $dataEnExel[$i][$j - 1];
					} else {
						$i++;
					}
				}
			}
			return $dataQuery;
		} catch (Exception $e) {
			$error = array('error' => 'Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
		}
	}


	public function exportarAExcel()
	{
		ini_set('memory_limit', '-1');

		$idTarifario = $this->input->post('idToExport');

		$queryTarifariosPrecios = "SELECT *
			FROM vtex_tarifarios_precios
			WHERE id_vtex_tarifario = $idTarifario AND activo = 1";

		$this->load->database();

		$resultQuery = $this->db->query($queryTarifariosPrecios);
		$result = $resultQuery->result_array();
		$header = $resultQuery->list_fields();

		//load our new PHPExcel library
		$this->load->library('excel');
		//activate worksheet number 1
		$this->excel->setActiveSheetIndex(0);
		//name the worksheet
		$this->excel->getActiveSheet()->setTitle('Consulta Editable');
		//set cabeceras
		$this->excel->getActiveSheet()->fromArray(
			$header,   // The data to set
			NULL       // Array values with this value will not be set
		);
		// set cells values
		$this->excel->getActiveSheet()->fromArray(
			$result,   // The data to set
			NULL,         // Array values with this value will not be set
			'A2'         // Top left coordinate of the worksheet range where
			//    we want to set these values (default is A1)
		);

		$this->load->library('simplehtmldom_1_5/simple_html_dom');
		$this->excel->setActiveSheetIndex(0);
		$filename = "Tarifario_precios_{$idTarifario}.xls"; //save our workbook as this file name

		header('Content-Type: application/vnd.ms-excel'); //mime type

		header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name

		header('Cache-Control: max-age=0'); //no cache

		//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
		//if you want to save it as .XLSX Excel 2007 format

		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

		//force user to download the Excel file without writing it to server's HD
		ob_end_clean();
		$objWriter->save('php://output');
	}
}

/* End of file Clientes_vtex_tarifario.php */
/* Location: ./system/application/controllers/Clientes_vtex_tarifario.php */