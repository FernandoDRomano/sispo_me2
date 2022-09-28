<?php
class Consulta_editable extends CI_Controller
{

	private $permisos;
	private $estructura = [
		'flash_piezas' => [
			'index' => 0,
			'active' => 1,
			'parent' => 0,
			'nombreDB' => 'flash_piezas',
			'nombre' => 'piezas',
			'columnas' => [
				'id' => [
					'type' => 'int',
					'nombreDB' => 'id',
					'nombre' => 'id',
					'value' => ''
				],
				'create' =>  [
					'type' => 'timestamp',
					'nombreDB' => 'create',
					'nombre' => 'create',
					'desde' => '',
					'hasta' => ''
				],
				'destinatario' =>  [
					'type' => 'varchar',
					'nombreDB' => 'destinatario',
					'nombre' => 'destinatario',
					'value' => ''
				],
				'barcode_externo' =>  [
					'type' => 'varchar',
					'nombreDB' => 'barcode_externo',
					'nombre' => 'barcode externo',
					'value' => ''
				],
				'domicilio' => [
					'type' => 'varchar',
					'nombreDB' => 'domicilio',
					'nombre' => 'domicilio',
					'value' => ''
				],
				'localidad' => [
					'type' => 'varchar',
					'nombreDB' => 'localidad',
					'nombre' => 'localidad',
					'value' => ''
				],
				'codigo_postal' => [
					'type' => 'varchar',
					'nombreDB' => 'codigo_postal',
					'nombre' => 'CP',
					'value' => ''
				],
				'servicio_id' => [
					'type' => 'tabla',
					'nombreDB' => 'servicio_id',
					'nombre' => 'comprobante servicio',
					'value' => '5'
				],
				'comprobante_ingreso_id' => [
					'type' => 'tabla',
					'nombreDB' => 'comprobante_ingreso_id',
					'nombre' => 'comprobante ingreso',
					'value' => '6'
				],
				'estado_id' => [
					'type' => 'tabla',
					'nombreDB' => 'flash_piezas_estados_variables',
					'nombre' => 'estados',
					'value' => '13'
				],
			],
			'relaciones' => [],
		],
		'flash_piezas_tracking' => [
			'index' => 1,
			'active' => 0,
			'parent' => 0,
			'nombreDB' => 'flash_piezas_tracking',
			'nombre' => 'tracking',
			'columnas' => [
				'id' => [
					'type' => 'int',
					'nombreDB' => 'id',
					'nombre' => 'id',
					'value' => ''
				],
				'create' =>  [
					'type' => 'timestamp',
					'nombreDB' => 'create',
					'nombre' => 'create',
					'desde' => '',
					'hasta' => ''
				],
				'estado_id' => [
					'type' => 'tabla',
					'nombreDB' => 'flash_piezas_estados_variables',
					'nombre' => 'estados',
					'value' => '13'
				],

			],
			'relaciones' => [
				0 => 'LEFT JOIN flash_piezas_tracking on flash_piezas_tracking.pieza_id = flash_piezas.id',
			]
		],
		'flash_hojas_rutas' => [
			'index' => 2,
			'active' => 0,
			'parent' => 0,
			'nombreDB' => 'flash_hojas_rutas',
			'nombre' => 'hojas rutas',
			'columnas' => [
				'id' => [
					'type' => 'int',
					'nombreDB' => 'id',
					'nombre' => 'id',
					'value' => ''
				],
				'create' =>  [
					'type' => 'timestamp',
					'nombreDB' => 'create',
					'nombre' => 'create',
					'desde' => '',
					'hasta' => ''
				],
				'fecha_baja' =>  [
					'type' => 'timestamp',
					'nombreDB' => 'fecha_baja',
					'nombre' => 'baja',
					'desde' => '',
					'hasta' => ''
				],
				'fecha_entrega' =>  [
					'type' => 'timestamp',
					'nombreDB' => 'fecha_entrega',
					'nombre' => 'entrega',
					'desde' => '',
					'hasta' => ''
				],
				'sucursal_id' => [
					'type' => 'tabla',
					'nombreDB' => 'sucursal_id',
					'nombre' => 'sucursal',
					'value' => '15'
				],
				'cartero_id' => [
					'type' => 'tabla',
					'nombreDB' => 'cartero_id',
					'nombre' => 'cartero',
					'value' => '16'
				],
			],
			'relaciones' => [
				0 => 'LEFT JOIN flash_hojas_rutas ON flash_hojas_rutas.id = flash_piezas.hoja_ruta_id',
			],
		],
		'flash_piezas_despacho' => [
			'index' => 3,
			'active' => 0,
			'parent' => 0,
			'nombreDB' => 'flash_piezas_despacho',
			'nombre' => 'despachos',
			'columnas' => [
				'id' => [
					'type' => 'int',
					'nombreDB' => 'id',
					'nombre' => 'id',
					'value' => ''
				],
				'create' =>  [
					'type' => 'timestamp',
					'nombreDB' => 'create',
					'nombre' => 'create',
					'desde' => '',
					'hasta' => ''
				],
				'fecha_envio' =>  [
					'type' => 'timestamp',
					'nombreDB' => 'fecha_envio',
					'nombre' => 'fecha envio',
					'desde' => '',
					'hasta' => ''
				],
				'origen_id' => [
					'type' => 'int',
					'nombreDB' => 'origen_id',
					'nombre' => 'origen',
					'value' => ''
				],
				'destino_id' => [
					'type' => 'int',
					'nombreDB' => 'destino_id',
					'nombre' => 'destino',
					'value' => ''
				],
				'transporte_id' => [
					'type' => 'tabla',
					'nombreDB' => 'transporte_id',
					'nombre' => 'transporte',
					'value' => '17'
				],
			],
			'relaciones' => [
				0 => 'LEFT JOIN flash_piezas_despacho_piezas ON flash_piezas_despacho_piezas.pieza_id = flash_piezas.id LEFT JOIN  flash_piezas_despacho ON  flash_piezas_despacho.id = flash_piezas_despacho_piezas.despacho_id',
			],
		],
		'flash_rendiciones' => [
			'index' => 4,
			'active' => 0,
			'parent' => 0,
			'nombreDB' => 'flash_rendiciones',
			'nombre' => 'rendiciones',
			'columnas' => [
				'id' => [
					'type' => 'int',
					'nombreDB' => 'id',
					'nombre' => 'id',
					'value' => ''
				],
				'create' =>  [
					'type' => 'timestamp',
					'nombreDB' => 'create',
					'nombre' => 'create',
					'desde' => '',
					'hasta' => ''
				],
				'piezas' => [
					'type' => 'int',
					'nombreDB' => 'piezas',
					'nombre' => 'piezas',
					'value' => ''
				],
				'clientes_id' => [
					'type' => 'tabla',
					'nombreDB' => 'clientes_id',
					'nombre' => 'cliente',
					'value' => '12'
				],
			],
			'relaciones' => [
				0 => 'LEFT JOIN flash_rendiciones_piezas ON flash_rendiciones_piezas.pieza_id = flash_piezas.id LEFT JOIN flash_rendiciones ON  flash_rendiciones.id = flash_rendiciones_piezas.rendicion_id',
			],
		],
		'flash_comprobantes_ingresos_servicios' => [
			'index' => 5,
			'active' => 0,
			'parent' => null,
			'nombreDB' => 'flash_comprobantes_ingresos_servicios',
			'nombre' => 'comprobantes servicios',
			'columnas' => [
				'id' => [
					'type' => 'int',
					'nombreDB' => 'id',
					'nombre' => 'id',
					'value' => ''
				],
				'cantidad' => [
					'type' => 'int',
					'nombreDB' => 'cantidad',
					'nombre' => 'cantidad',
					'value' => ''
				],
				'disponible' => [
					'type' => 'int',
					'nombreDB' => 'disponible',
					'nombre' => 'disponible',
					'value' => ''
				],
				'remito' => [
					'type' => 'int',
					'nombreDB' => 'remito',
					'nombre' => 'remito',
					'value' => ''
				],
				'servicio_id' => [
					'type' => 'tabla',
					'nombreDB' => 'flash_servicios',
					'nombre' => 'servicios',
					'value' => '11'
				],
				'comprobante_ingreso_id' => [
					'type' => 'tabla',
					'nombreDB' => 'comprobante_ingreso_id',
					'nombre' => 'comprobante ingreso',
					'value' => '6'
				],
			],
			'relaciones' => [
				0 => 'LEFT JOIN flash_comprobantes_ingresos_servicios ON flash_comprobantes_ingresos_servicios.id = flash_piezas.servicio_id ',
			],
		],
		'flash_comprobantes_ingresos' => [
			'index' => 6,
			'active' => 0,
			'parent' => null,
			'nombreDB' => 'flash_comprobantes_ingresos',
			'nombre' => 'comprobantes ingresos',
			'columnas' => [
				'id' => [
					'type' => 'int',
					'nombreDB' => 'id',
					'nombre' => 'id',
					'value' => ''
				],
				'create' =>  [
					'type' => 'timestamp',
					'nombreDB' => 'create',
					'nombre' => 'create',
					'desde' => '',
					'hasta' => ''
				],
				'fecha_pedido' =>  [
					'type' => 'timestamp',
					'nombreDB' => 'fecha_pedido',
					'nombre' => 'fecha pedido',
					'desde' => '',
					'hasta' => ''
				],
				'numero' => [
					'type' => 'int',
					'nombreDB' => 'numero',
					'nombre' => 'numero',
					'value' => ''
				],
				'cantidad' => [
					'type' => 'int',
					'nombreDB' => 'cantidad',
					'nombre' => 'cantidad',
					'value' => ''
				],
				'sucursal_id' => [
					'type' => 'tabla',
					'nombreDB' => 'flash_sucursales',
					'nombre' => 'sucursal',
					'value' => '15'
				],
				'clientes_id' => [
					'type' => 'tabla',
					'nombreDB' => 'clientes_id',
					'nombre' => 'cliente',
					'value' => '12'
				],
			],
			'relaciones' => [
				0 => 'LEFT JOIN flash_comprobantes_ingresos ON flash_comprobantes_ingresos.id = flash_piezas.comprobante_ingreso_id',
				5 => 'LEFT JOIN flash_comprobantes_ingresos ON flash_comprobantes_ingresos.id = flash_comprobantes_ingresos_servicios.comprobante_ingreso_id',
			],
		],
		'flash_servicios' => [
			'index' => 11,
			'active' => 0,
			'parent' => null,
			'nombreDB' => 'flash_servicios',
			'nombre' => 'servicios',
			'columnas' => [
				'id' => [
					'type' => 'int',
					'nombreDB' => 'id',
					'nombre' => 'id',
					'value' => ''
				],
				'nombre' => [
					'type' => 'varchar',
					'nombreDB' => 'nombre',
					'nombre' => 'nombre',
					'value' => ''
				],
				'precio' => [
					'type' => 'varchar',
					'nombreDB' => 'precio',
					'nombre' => 'precio',
					'value' => ''
				],
			],
			'relaciones' => [
				5 => 'LEFT JOIN flash_servicios on flash_servicios.id = flash_comprobantes_ingresos_servicios.servicio_id',
			],
		],
		'flash_clientes' => [
			'index' => 12,
			'active' => 0,
			'parent' => null,
			'nombreDB' => 'flash_clientes',
			'nombre' => 'clientes',
			'columnas' => [
				'id' => [
					'type' => 'int',
					'nombreDB' => 'id',
					'nombre' => 'id',
					'value' => ''
				],
				'nombre' => [
					'type' => 'varchar',
					'nombreDB' => 'nombre',
					'nombre' => 'nombre',
					'value' => ''
				],
				'nombre_fantasia' => [
					'type' => 'varchar',
					'nombreDB' => 'nombre_fantasia',
					'nombre' => 'nombre fantasia',
					'value' => ''
				],
				'cuit' => [
					'type' => 'varchar',
					'nombreDB' => 'cuit',
					'nombre' => 'cuit',
					'value' => ''
				],
			],
			'relaciones' => [
				4 => 'LEFT JOIN flash_clientes on flash_clientes.id = flash_rendiciones.clientes_id',
				6 => 'LEFT JOIN flash_clientes on flash_clientes.id = flash_comprobantes_ingresos.cliente_id',
			],
		],
		'flash_piezas_estados_variables' => [
			'index' => 13,
			'active' => 0,
			'parent' => null,
			'nombreDB' => 'flash_piezas_estados_variables',
			'nombre' => 'estados',
			'columnas' => [
				'id' => [
					'type' => 'int',
					'nombreDB' => 'id',
					'nombre' => 'id',
					'value' => ''
				],
				'nombre' => [
					'type' => 'varchar',
					'nombreDB' => 'nombre',
					'nombre' => 'nombre',
					'value' => ''
				],
			],
			'relaciones' => [
				0 => 'LEFT JOIN flash_piezas_estados_variables on flash_piezas_estados_variables.id = flash_piezas.estado_id',
				1 => 'LEFT JOIN flash_piezas_estados_variables on flash_piezas_estados_variables.id = flash_piezas_tracking.estado_id',
			],
		],
		'flash_sucursales_carteros' => [
			'index' => 16,
			'active' => 0,
			'parent' => null,
			'nombreDB' => 'flash_sucursales_carteros',
			'nombre' => 'carteros',
			'columnas' => [
				'id' => [
					'type' => 'int',
					'nombreDB' => 'id',
					'nombre' => 'id',
					'value' => ''
				],
				'apellido_nombre' => [
					'type' => 'varchar',
					'nombreDB' => 'apellido_nombre',
					'nombre' => 'apellido y nombre',
					'value' => ''
				],
				'domicilio' => [
					'type' => 'varchar',
					'nombreDB' => 'domicilio',
					'nombre' => 'domicilio',
					'value' => ''
				],
				'localidad' => [
					'type' => 'varchar',
					'nombreDB' => 'localidad',
					'nombre' => 'localidad',
					'value' => ''
				],
				'telefono' => [
					'type' => 'varchar',
					'nombreDB' => 'telefono',
					'nombre' => 'telefono',
					'value' => ''
				],
				'observaciones' => [
					'type' => 'varchar',
					'nombreDB' => 'observaciones',
					'nombre' => 'observaciones',
					'value' => ''
				],
				'sucursal_id' => [
					'type' => 'tabla',
					'nombreDB' => 'flash_sucursales',
					'nombre' => 'sucursal',
					'value' => '15'
				],
			],
			'relaciones' => [
				2 => 'LEFT JOIN flash_sucursales_carteros on flash_sucursales_carteros.id = flash_hojas_rutas.cartero_id',
			],
		],
		'flash_sucursales' => [
			'index' => 15,
			'active' => 0,
			'parent' => null,
			'nombreDB' => 'flash_sucursales',
			'nombre' => 'sucursales',
			'columnas' => [
				'id' => [
					'type' => 'int',
					'nombreDB' => 'id',
					'nombre' => 'id',
					'value' => ''
				],
				'nombre' => [
					'type' => 'varchar',
					'nombreDB' => 'nombre',
					'nombre' => 'nombre',
					'value' => ''
				],
				'descripcion' => [
					'type' => 'varchar',
					'nombreDB' => 'descripcion',
					'nombre' => 'descripcion',
					'value' => ''
				],
			],
			'relaciones' => [
				0 => 'LEFT JOIN flash_sucursales on flash_sucursales.id = flash_piezas.sucursal_id',
				2 => 'LEFT JOIN flash_sucursales on flash_sucursales.id = flash_hojas_rutas.sucursal_id',
				6 => 'LEFT JOIN flash_sucursales on flash_sucursales.id = flash_comprobantes_ingresos.sucursal_id',
				16 => 'LEFT JOIN flash_sucursales on flash_sucursales.id = flash_sucursales_carteros.sucursal_id',
			],
		],
		'flash_transportes' => [
			'index' => 17,
			'active' => 0,
			'parent' => null,
			'nombreDB' => 'flash_transportes',
			'nombre' => 'transportes',
			'columnas' => [
				'id' => [
					'type' => 'int',
					'nombreDB' => 'id',
					'nombre' => 'id',
					'value' => ''
				],
				'nombre' => [
					'type' => 'varchar',
					'nombreDB' => 'nombre',
					'nombre' => 'nombre',
					'value' => ''
				],
				'descripcion' => [
					'type' => 'varchar',
					'nombreDB' => 'descripcion',
					'nombre' => 'descripcion',
					'value' => ''
				],
			],
			'relaciones' => [
				3 => 'LEFT JOIN flash_transportes on flash_transportes.id = flash_piezas_despacho.transporte_id',
			],
		],

	];


	public function __construct()
	{
		parent::__construct();
		$this->permisos = $this->permisos_lib->control();
		// load Pagination library
		$this->load->library('pagination');

		// load URL helper
		$this->load->helper('url');
	}

	public function index()
	{
		//header('Content-type: application/json');
		// $data = file_get_contents('php://input');

		ini_set('memory_limit', '-1');
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
		);

		// Uso una bandera para saber si la consulta es ingresada o es leida de la session.
		// Si es leida de la session no hace falta volver a calcular el numero total de resultados.
		$procesar = $this->input->post('procesar') || $this->uri->segment(4) || $this->session->userdata('filtroConsultaEditable');

		if (!is_null($this->input->post('query'))) {
			$data = $this->input->post('query');
			$this->session->set_userdata('filtroConsultaEditable', $data);
			$newParameters = 1;
		} else {
			$data = $this->session->userdata('filtroConsultaEditable');
			$newParameters = 0;
		}


		if (!is_null($data) && $procesar) {
			// CONSULTA
			$consultaJson = json_decode($data, true);
			$consultaSql = $this->_crearQuery($consultaJson, $this->estructura);

			$this->load->database();
			$time_start = microtime(true);

			$limit_per_page = 50;
			$start_index = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
			$resultQuery = $this->db->query($consultaSql['datos'] . "LIMIT $start_index, $limit_per_page");

			$vista_interna['consultas'] = $consultaSql;
			/* 		echo "<pre>";
			print_r($consultaSql);
			echo "</pre>";
			die; */

			if ($newParameters == 1) {
				$result = $this->db->query($consultaSql['count']);
				$total_records = 0;
				if ($result) {
					$total_records = $this->db->query($consultaSql['count'])->row()->count;
				}
				$this->session->set_userdata('filtroConsultaEditableTotalRecords', $total_records);
			} else {
				$total_records = $this->session->userdata('filtroConsultaEditableTotalRecords');
			}


			if (empty($resultQuery) || empty($total_records)) {
				$vista_interna['noResult'] = true;
			} else {

				$vista_interna['headerTable'] = $resultQuery->list_fields();	//campos header
				$vista_interna['results'] = $resultQuery->result();			 	//resultado
				//$resultQuery->freeResult();

				//PAGINACION
				$config['base_url'] = base_url() . 'piezas/consulta_editable/index';
				$config['total_rows'] = $total_records;
				$config['per_page'] = $limit_per_page;
				$config["uri_segment"] = 4;
				$config['num_links'] = 20;
				$config['page_query_string'] = FALSE;
				$config['full_tag_open'] = "<ul class='pagination'>";
				$config['full_tag_close'] = "</ul>";
				$config['num_tag_open'] = '<li>';
				$config['num_tag_close'] = '</li>';
				$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
				$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
				$config['next_tag_open'] = "<li>";
				$config['next_tagl_close'] = "</li>";
				$config['prev_tag_open'] = "<li>";
				$config['prev_tagl_close'] = "</li>";
				$config['first_tag_open'] = "<li>";
				$config['first_tagl_close'] = "</li>";
				$config['last_tag_open'] = "<li>";
				$config['last_tagl_close'] = "</li>";

				$this->pagination->initialize($config);
				$vista_interna["links"] = $this->pagination->create_links();

				$time_end = microtime(true);
				$time_total = number_format($time_end - $time_start, 2);
				$vista_interna["time_total"] = "Proceso ejecutado en $time_total seg. Total de registro $total_records.";
			}
		}



		$vista_interna['estructura'] = $data ? json_decode($data, true) : $this->estructura;

		$vista_externa = array(
			'title'          => ucwords("piezas"),
			'contenido_main' => $this->load->view('components/piezas/consulta_editable/view_consultas_editable', $vista_interna, true),
		);

		$this->load->view('template/backend', $vista_externa);
	}



	// Crea la query a partir del json(data) recibido y la estructura
	// genera dos consultas consulta una para traer el count de resultados
	// y otra para traer los datos, 
	private function _crearQuery($data, $estructura)
	{
		$header = [];
		$select = "";
		$from = "";
		$where = "";

		foreach ($data as $tablaQuery) {
			if ($tablaQuery['active']) {

				$nombreTablaDB = $tablaQuery['nombreDB'];



				//FROM
				if ($nombreTablaDB != 'flash_piezas') {
					/* echo "hola\n";
					echo $tablaQuery['active'] ? '1' : 0;
					print_r($tablaQuery['index']);
					print_r($tablaQuery['parent']); */
					$from .= $tablaQuery['relaciones'][$tablaQuery['parent']] . ' ';
				}

				foreach ($tablaQuery['columnas'] as $colName => $campos) {

					$type = $campos['type'];
					$nombre =  $campos['nombre'];
					$nombreDB =  $campos['nombreDB'];
					$value =  $campos['value'];

					if ($type != 'tabla' and $value != 'disabled') {
						//SELECT
						$select .= "{$nombreTablaDB}.{$nombreDB} '{$tablaQuery['nombre']} {$nombre}' , ";
						$header[$tablaQuery['nombre']][] = $nombre;


						//WHERE
						// POR FECHA 
						if ($type == 'timestamp') {
							if ($campos['desde'] != '') {
								$where .= "{$nombreTablaDB}.{$nombreDB} >= '{$campos['desde']}' AND ";
							}
							if ($campos['hasta'] != '') {
								$where .= "{$nombreTablaDB}.{$nombreDB} <= '{$campos['hasta']}' AND ";
							}
							//POR VALOR
						} else if ($type == 'varchar' and $value != '') {
							$where .= "{$nombreTablaDB}.{$nombreDB} LIKE '%{$value}%' AND ";
						} else {
							if ($value == 'null') {
								$where .= "{$nombreTablaDB}.{$nombreDB} IS NULL AND ";
							} else if ($value != '') {
								$where .= "{$nombreTablaDB}.{$nombreDB} = {$value} AND ";
							}
						}
					}
				}
			}
		}

		$selectCount = "SELECT COUNT(*) as 'count' ";

		if (!empty($select)) {
			$select = "SELECT " . substr_replace($select, "", -2);
		}
		if (!empty($where)) {
			$where = " WHERE " . substr_replace($where, "", -4);
		}

		$from = " FROM flash_piezas " . $from;
		$querys = [
			'datos' => $select . $from . $where,
			'count' => $selectCount . $from . $where,
			'header' => $header
		];

		return $querys;
	}


	public function exportarAExcel()
	{
		ini_set('memory_limit', '-1');

		$data = $this->session->userdata('filtroConsultaEditable');
		$consultaJson = json_decode($data, true);
		$consultaSql = $this->_crearQuery($consultaJson, $this->estructura);

		$this->load->database();

		$resultQuery = $this->db->query($consultaSql['datos']);
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
		$filename = 'Consultas_editable.xls'; //save our workbook as this file name

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

/* End of file piezas.php */
/* Location: ./system/application/controllers/piezas.php */