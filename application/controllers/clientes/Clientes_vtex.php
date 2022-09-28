<?php

class Clientes_vtex extends CI_Controller
{

	private $permisos;

	function __construct()
	{
		parent::__construct();
		$this->permisos = $this->permisos_lib->control();
		$this->load->helper('url');
		$this->load->helper('vtex');
	}

	function index()
	{
		$queryClientesVtex = "SELECT id 'idCliente', nombre 'nombreCliente'  
		FROM `flash_clientes` 
		WHERE nombre_fantasia LIKE 'VTEX/%'";

		$clientesVtex = $this->db->query($queryClientesVtex)->result();

		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'clientesVtex' => $clientesVtex,
		);

		$vista_externa = array(
			'title' => ucwords("clientes vtex"),
			'contenido_main' => $this->load->view('components/clientes/clientes_vtex/clientes_vtex_list', $vista_interna, true)
		);

		$this->load->view('template/backend', $vista_externa);
	}

	function filtro()
	{
		$action  = $this->input->post('action');
		$idCliente = $this->input->post('searchIdCliente');

		// Lista de clientes de vtex
		$queryClientesVtex = "SELECT id 'idCliente', nombre 'nombreCliente'  
			FROM `flash_clientes` 
			WHERE nombre_fantasia LIKE 'VTEX/%'";
		$clientesVtex = $this->db->query($queryClientesVtex)->result();

		// Lista de tarifarios
		$queryTarifarios = "SELECT id 'idTarifario', nombre 'nombreTarifario'  
		FROM vtex_tarifarios";
		$tarifarios = $this->db->query($queryTarifarios)->result();

		// Lista todos los clientes 

		if ($action == 'search') {

			$queryResult = "SELECT 
				fc.id 'idCliente', 
				fc.nombre 'nombreCliente',
				SUBSTRING_INDEX(fc.nombre_fantasia, '/', -1) as 'cuentaCliente',
				fc.observaciones 'info',
				fca.api_key as 'appKey',
				fca.secret_key as 'token'
				FROM flash_clientes fc
				INNER JOIN flash_clientes_api fca on fca.cliente_id = fc.id
				WHERE fc.id = $idCliente";

			$result = $this->db->query($queryResult)->row();

			list($result->rolTienda, $result->idMuelle, $result->tarifario) = explode('/', $result->info);
		}

		if ($action == 'insert') {
			$queryClientes = "SELECT 
				fc.id 'idCliente', 
				fc.nombre 'nombreCliente'
				FROM flash_clientes fc
				WHERE nombre_fantasia NOT LIKE 'VTEX/%'";

			$clientes = $this->db->query($queryClientes)->result();
			$result = new stdClass();
			$result->insert = true;
		}


		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'clientesVtex' => $clientesVtex,
			'cliente' => $clientes,
			'tarifarios' => $tarifarios,
			'result' => $result,
		);

		$vista_externa = array(
			'title' => ucwords("clientes vtex"),
			'contenido_main' => $this->load->view('components/clientes/clientes_vtex/clientes_vtex_list', $vista_interna, true)
		);

		$this->load->view('template/backend', $vista_externa);
	}



	function insert()
	{
		$idCliente = $this->input->post('IdCliente');
		$cuentaCliente = $this->input->post('cuentaCliente');
		$appKey = $this->input->post('appKey');
		$token = $this->input->post('token');
		$rolTienda = $this->input->post('rolTienda');
		$idMuelle = $this->input->post('idMuelle');
		$tarifario = $this->input->post('tarifario');
		$obs = implode('/', array_map("trim", [$rolTienda, $idMuelle, $tarifario]));

		// ==============  EMPIEZA TRANSACCION
		$this->db->trans_begin();

		$queryUpdateCliente = "UPDATE flash_clientes 
			SET 
			nombre_fantasia = 'VTEX/$cuentaCliente',
			observaciones = '$obs'
			WHERE id = '$idCliente'";

		$UpdateCliente = $this->db->query($queryUpdateCliente);

		$queryUpdateClienteApi = "UPDATE flash_clientes_api 
			SET 
			api_key = '$appKey',
			secret_key = '$token'
			WHERE cliente_id = '$idCliente'";

		$UpdateClienteApi = $this->db->query($queryUpdateClienteApi);

		if ($this->db->affected_rows() == 0) {
			$queryInsertClienteApi = "INSERT INTO flash_clientes_api (cliente_id,api_key,secret_key) VALUES($idCliente,'$appKey','$token')";

			$InsertClienteApi = $this->db->query($queryInsertClienteApi);
		}

		if ($UpdateCliente && ($UpdateClienteApi || $InsertClienteApi)) {
			$queryTarifario = "SELECT *
			FROM vtex_tarifarios
			WHERE id = '$tarifario'";

			$resultTarifario = $this->db->query($queryTarifario)->row();

			$queryTarifariosPrecios = "SELECT *
			FROM vtex_tarifarios_precios
			WHERE id_vtex_tarifario = $tarifario AND activo = 1";

			$tarifario_precios = $this->db->query($queryTarifariosPrecios)->result();

			if (!empty($resultTarifario) && !empty($tarifario_precios)) {
				try {
					vtex_createFeed($cuentaCliente, $appKey, $token, $rolTienda);
					vtex_createShippingPolicy($cuentaCliente, $appKey, $token, $resultTarifario, 'POST');
					vtex_updateFreightModel($cuentaCliente, $appKey, $token, $tarifario, $tarifario_precios, 'POST');
					vtex_addShippingPolicyToDock($cuentaCliente, $appKey, $token, $idMuelle, $tarifario);
					$this->db->trans_commit();
				} catch (\Throwable $e) {
					$this->db->trans_rollback();
					show_error($e->getMessage());
				}
				//TERMINA TRANSACCION
			}
		}

		redirect(base_url() . 'clientes/clientes_vtex/', 'refresh');
	}


	function update()
	{
		$idCliente = $this->input->post('IdCliente');
		$cuentaCliente = $this->input->post('cuentaCliente');
		$appKey = $this->input->post('appKey');
		$token = $this->input->post('token');
		$rolTienda = $this->input->post('rolTienda');
		$idMuelle = $this->input->post('idMuelle');
		$tarifario = $this->input->post('tarifario');
		$obs = implode('/', array_map("trim", [$rolTienda, $idMuelle, $tarifario]));

		// ==============  EMPIEZA TRANSACCION ===============
		$this->db->trans_begin();

		//DATOS ANTES UPDATE 
		$queryDatosOld = "SELECT 
			fc.id 'idCliente', 
			fc.nombre 'nombreCliente',
			SUBSTRING_INDEX(fc.nombre_fantasia, '/', -1) as 'cuentaCliente',
			fc.observaciones 'info',
			fca.api_key as 'appKey',
			fca.secret_key as 'token'
			FROM flash_clientes fc
			INNER JOIN flash_clientes_api fca on fca.cliente_id = fc.id
			WHERE fc.id = $idCliente";
		$datosOld = $this->db->query($queryDatosOld)->row();
		list($datosOld->rolTienda, $datosOld->idMuelle, $datosOld->tarifario) = explode('/', $datosOld->info);


		// UPDATE DATOS NUEVOS
		$queryUpdateCliente = "UPDATE flash_clientes 
			SET 
			nombre_fantasia = 'VTEX/$cuentaCliente',
			observaciones = '$obs'
			WHERE id = '$idCliente'";
		$UpdateCliente = $this->db->query($queryUpdateCliente);

		$queryUpdateClienteApi = "UPDATE flash_clientes_api 
			SET 
			api_key = '$appKey',
			secret_key = '$token'
			WHERE cliente_id = '$idCliente'";
		$UpdateClienteApi = $this->db->query($queryUpdateClienteApi);

		$queryTarifario = "SELECT *
			FROM vtex_tarifarios
			WHERE id = '$tarifario'";

		$resultTarifario = $this->db->query($queryTarifario)->row();

		$queryTarifariosPrecios = "SELECT *
			FROM vtex_tarifarios_precios
			WHERE id_vtex_tarifario = $tarifario AND activo = 1";

		$tarifario_precios = $this->db->query($queryTarifariosPrecios)->result();

		try {
			if ($datosOld->rolTienda != $rolTienda) {
				vtex_createFeed($cuentaCliente, $appKey, $token, $rolTienda);
			}

			// si se cambia el muelle, se usa el nuevo directamente
			if ($tarifario != $datosOld->tarifario) {
				vtex_deleteShippingPolicyById($cuentaCliente, $appKey, $token, $datosOld->tarifario);
				vtex_createShippingPolicy($cuentaCliente, $appKey, $token, $resultTarifario, 'POST');
				vtex_updateFreightModel($cuentaCliente, $appKey, $token, $tarifario, $tarifario_precios, 'POST');
				vtex_deleteShippingPolicyFromDock($cuentaCliente, $appKey, $token, $datosOld->idMuelle, $tarifario);
				vtex_addShippingPolicyToDock($cuentaCliente, $appKey, $token, $idMuelle, $tarifario);
			} elseif ($idMuelle != $datosOld->idMuelle) {
				vtex_deleteShippingPolicyFromDock($cuentaCliente, $appKey, $token, $datosOld->idMuelle, $tarifario);
				vtex_addShippingPolicyToDock($cuentaCliente, $appKey, $token, $idMuelle, $tarifario);
			}
			$this->db->trans_commit();
		} catch (Exception $e) {
			$this->db->trans_rollback();
			show_error($e->getMessage());
		}

		redirect(base_url() . 'clientes/clientes_vtex/', 'refresh');
	}

	private function _updateMuelle()
	{
	}
}

/* End of file clientes_vtex.php */
/* Location: ./system/application/controllers/clientes_vtex.php */