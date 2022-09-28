<?php
class Tarifario_md extends CI_Model
{

	function __construct()
	{
		parent::__construct();
	}
	/**
	 * Verifica si tiene tarifarios personalizados
	 *
	 * @param [type] $idCliente
	 * @return bool
	 */
	function tieneTarifarioPersonalizado($idCliente)
	{
		$tieneQuery = $this->db
			->select('id')
			->where('fsp.cliente_id = ' . $idCliente)
			->get('flash_servicios_paqueteria fsp');

		return ($tieneQuery !== FALSE && $tieneQuery->num_rows() > 0);
	}

	function getTarifasKgPorCliente($idCliente, $zonaA = null, $zonaB = null)
	{
		if ($zonaA) {
			$this->db->where('fspt.zonaA = ' . $zonaA);
		}
		if ($zonaB) {
			$this->db->where('fspt.zonaB = ' . $zonaB);
		}

		$query = $this->db
			->select('fsp.id paqueteria_tarifa, fspt.id, fc.id cliente, fspt.desde_cant_unid_kg, fspt.hasta_cant_unid_kg, fspt.precio_Corte_kg, fspt.bandera_Corte_kg, fspt.zonaA, fspt.zonaB')
			->join('flash_servicios_paqueteria fsp', 'fc.id = fsp.cliente_id', 'inner')
			->join('flash_servicios_tarifas_kg fspt', 'fsp.tarifario_kg_id = fspt.id', 'inner')
			->where('fc.id = ' . $idCliente)
			->order_by('bandera_Corte_kg', 'DESC')
			->get('flash_clientes fc');
		if ($query !== FALSE &&  $query->num_rows() > 0) {
			return  $query->result();
		} else {
			return [];
		}
	}

	function getTarifasBultosPorCliente($idCliente, $zonaA = null, $zonaB = null)
	{
		if ($zonaA) {
			$this->db->where('fspt.zonaA = ' . $zonaA);
		}
		if ($zonaB) {
			$this->db->where('fspt.zonaB = ' . $zonaB);
		}
		//2) Bultos
		$query = $this->db
			->select('fsp.id paqueteria_tarifa, fspt.id, fc.id cliente, fspt.desde_cant_unid_bultos, fspt.hasta_cant_unid_bultos, fspt.precio_Corte_bultos, fspt.bandera_Corte_Bultos, fspt.zonaA, fspt.zonaB')
			->join('flash_servicios_paqueteria fsp', 'fc.id = fsp.cliente_id', 'inner')
			->join('flash_servicios_tarifas_bultos fspt', 'fsp.tarifario_bultos_id = fspt.id', 'inner')
			->where('fc.id = ' . $idCliente)
			->get('flash_clientes fc');

		if ($query !== FALSE &&  $query->num_rows() > 0) {
			return  $query->result();
		} else {
			return [];
		}
	}



	function getTarifasPaletsPorCliente($idCliente, $zonaA = null, $zonaB = null)
	{
		if ($zonaA) {
			$this->db->where('fspt.zonaA = ' . $zonaA);
		}
		if ($zonaB) {
			$this->db->where('fspt.zonaB = ' . $zonaB);
		}
		// 6) Palets
		$query = $this->db
			->select('fsp.id paqueteria_tarifa, fspt.id, fc.id cliente, fspt.desde_cantidad_palets, fspt.hasta_cantidad_palets, fspt.precio_palets, fspt.bandera_palets, fspt.zonaA, fspt.zonaB')
			->join('flash_servicios_paqueteria fsp', 'fc.id = fsp.cliente_id', 'inner')
			->join('flash_servicios_tarifas_palets fspt', 'fsp.tarifario_palets_id = fspt.id', 'inner')
			->where('fc.id = ' . $idCliente)
			->get('flash_clientes fc');

		if ($query !== FALSE &&  $query->num_rows() > 0) {
			return  $query->result();
		} else {
			return [];
		}
	}

	function getTarifasCobranzaPorCliente($idCliente, $zonaA = null, $zonaB = null)
	{
		if ($zonaA) {
			$this->db->where('fspt.zonaA = ' . $zonaA);
		}
		if ($zonaB) {
			$this->db->where('fspt.zonaB = ' . $zonaB);
		}
		// 4 Cobranza
		$query = $this->db
			->select('fsp.id paqueteria_tarifa, fspt.id, fc.id cliente,  fspt.porcentaje, fspt.zonaA, fspt.zonaB')
			->join('flash_servicios_paqueteria fsp', 'fc.id = fsp.cliente_id', 'inner')
			->join('flash_servicios_tarifas_cobranza fspt', 'fsp.tarifario_cobranza_id = fspt.id', 'inner')
			->where('fc.id = ' . $idCliente)
			->get('flash_clientes fc');
		if ($query !== FALSE &&  $query->num_rows() > 0) {
			return  $query->result();
		} else {
			return [];
		}
	}


	function getTarifasValorDeclaroPorCliente($idCliente, $zonaA = null, $zonaB = null)
	{
		if ($zonaA) {
			$this->db->where('fspt.zonaA = ' . $zonaA);
		}
		if ($zonaB) {
			$this->db->where('fspt.zonaB = ' . $zonaB);
		}

		$query = $this->db
			->select('fsp.id paqueteria_tarifa, fspt.id, fc.id cliente,  fspt.porcentaje, fspt.zonaA, fspt.zonaB')
			->join('flash_servicios_paqueteria fsp', 'fc.id = fsp.cliente_id', 'inner')
			->join('flash_servicios_tarifas_valor_declarado fspt', 'fsp.tarifario_valor_declarado_id = fspt.id', 'inner')
			->where('fc.id = ' . $idCliente)
			->get('flash_clientes fc');
		if ($query !== FALSE &&  $query->num_rows() > 0) {
			return  $query->result();
		} else {
			return [];
		}
	}


	function getTarifasWarehousePorCliente($idCliente, $zonaA = null, $zonaB = null)
	{
		if ($zonaA) {
			$this->db->where('fspt.zonaA = ' . $zonaA);
		}
		if ($zonaB) {
			$this->db->where('fspt.zonaB = ' . $zonaB);
		}

		$query = $this->db
			->select('fsp.id paqueteria_tarifa, fspt.id, fc.id cliente,  fspt.cantidad_wh, fspt.precio_wh, fspt.zonaA, fspt.zonaB')
			->join('flash_servicios_paqueteria fsp', 'fc.id = fsp.cliente_id', 'inner')
			->join('flash_servicios_tarifas_warehouse fspt', 'fsp.tarifario_warehouse_id = fspt.id', 'inner')
			->where('fc.id = ' . $idCliente)
			->get('flash_clientes fc');
		if ($query !== FALSE &&  $query->num_rows() > 0) {
			return  $query->result();
		} else {
			return [];
		}
	}

	function getTarifasMetroCubicoPorCliente($idCliente, $zonaA = null, $zonaB = null)
	{
		if ($zonaA) {
			$this->db->where('fspt.zonaA = ' . $zonaA);
		}
		if ($zonaB) {
			$this->db->where('fspt.zonaB = ' . $zonaB);
		}

		$query = $this->db
			->select('fsp.id paqueteria_tarifa, fspt.id, fc.id cliente,  fspt.desde_cantidad_metro_cubico, fspt.hasta_cantidad_metro_cubico, fspt.precio_mc, fspt.bandera_bloque_unidad, fspt.zonaA, fspt.zonaB')
			->join('flash_servicios_paqueteria fsp', 'fc.id = fsp.cliente_id', 'inner')
			->join('flash_servicios_tarifas_metro_cubico fspt', 'fsp.tarifario_metro_cubico_id = fspt.id', 'inner')
			->where('fc.id = ' . $idCliente)
			->get('flash_clientes fc');
		if ($query !== FALSE &&  $query->num_rows() > 0) {
			return  $query->result();
		} else {
			return [];
		}
	}


	function getTarifasGestionFlotaPorCliente($idCliente, $zonaA = null, $zonaB = null)
	{
		if ($zonaA) {
			$this->db->where('fspt.zonaA = ' . $zonaA);
		}
		if ($zonaB) {
			$this->db->where('fspt.zonaB = ' . $zonaB);
		}
		// 4 Cobranza
		$query = $this->db
			->select('fsp.id paqueteria_tarifa, fspt.id, fc.id cliente,  fspt.precio_gf, fspt.tipo_hora, fspt.tipo_vehiculo, fspt.zonaA, fspt.zonaB')
			->join('flash_servicios_paqueteria fsp', 'fc.id = fsp.cliente_id', 'inner')
			->join('flash_servicios_tarifas_gestion_flota fspt', 'fsp.tarifario_gestion_flota_id = fspt.id', 'inner')
			->where('fc.id = ' . $idCliente)
			->get('flash_clientes fc');
		if ($query !== FALSE &&  $query->num_rows() > 0) {
			return  $query->result();
		} else {
			return [];
		}
	}

	function getTarifasZonas()
	{

		$ci = &get_instance();
		//$ci->dbt = $ci->load->database('tarifario', TRUE);

		//$query_zonas = $ci->dbt
		$query_zonas = $this->db
			->select('tp.ID, tp.Nombre provincia, tl.*, IF(tl.es_capital,"Capital","Interior") ubicacion, concat(tp.ID, 0, tl.es_capital) id_provincia_bandera', FALSE)
			->join('tarifario_provincia tp', 'tp.ID = tl.tarifario_provincia_id', 'left')
			->where('tp.Trabaja = 1')
			->where('tp.ID IS NOT NULL')
			->group_by('tp.Nombre')
			->group_by('tl.es_capital')
			->get('tarifario_localidad tl');
		$lista_zonas = $query_zonas->result();

		return $lista_zonas;
	}

	function getTarifasZonas2()
	{
		$query_zonas = $this->db
			->select('idDistrito id, Nombre nombre')
			->get('tarifario_distrito');
		$lista_zonas2 = $query_zonas->result();

		return $lista_zonas2;
	}


	function getZonaBySucursal($idSucursal)
	{
		// TODO
		if ($idSucursal == 4) {
			$res =  new stdClass();
			$res->id = 2401;
			$res->nombre = 'zona 1';
			$res->sucursal = 'FLASH TUCUMAN';
			return $res;
		}
	}

	function getZonasCP()
	{
		// TODO
		$res = [];
		$res[] = (object) ['id' => 2401, 'nombre' => 'zona 1', 'CP' => 4000];
		$res[] = (object) ['id' => 2000, 'nombre' => 'zona 2', 'CP' => 4600];
		return $res;
	}



	function getClientesServicios()
	{

		$query = $this->db
			->select('fc.id, fc.nombre, fs.nombre servicio')
			->join('flash_clientes_precios_especiales fcps', 'fcps.cliente_id = fc.id', 'inner')
			->join('flash_servicios fs', 'fcps.servicio_id = fs.id', 'inner')
			->order_by('fc.nombre', 'asc')
			->where('fs.id = 2')
			->get('flash_clientes fc');
		if ($query !== FALSE &&  $query->num_rows() > 0) {
			return  $query->result();
		} else {
			return null;
		}
	}


	function getComparacionTarifasBultos_desde($cliente_id, $desde_cantidad, $zonaA, $zonaB, $id=null)
	{
		if ($id) {
			$this->db->where('fspt.id != ' . $id);
		}		

		$query_comparacion_desdeCantidad = $this->db
			->select('count(fspt.id) as cantidad')
			->join('flash_servicios_paqueteria fsp', 'fsp.tarifario_bultos_id = fspt.id', 'left')
			->where('fspt.desde_cant_unid_bultos <= ' . $desde_cantidad)
			->where('fspt.hasta_cant_unid_bultos >= ' . $desde_cantidad)
			->where('fspt.zonaA = ' . $zonaA)
			->where('fspt.zonaB = ' . $zonaB)
			->where('fsp.cliente_id = ' . $cliente_id)
			->get('flash_servicios_tarifas_bultos fspt');

		$query_comparacionA = $query_comparacion_desdeCantidad->result();

		return $query_comparacionA;
	}

	function getComparacionTarifasBultos_hasta($cliente_id, $hasta, $zonaA, $zonaB, $id=null)
	{
		if ($id) {
			$this->db->where('fspt.id != ' . $id);
		}	

		$query_comparacion_hastaCantidad = $this->db
			->select('count(fspt.id) as cantidad')
			->join('flash_servicios_paqueteria fsp', 'fsp.tarifario_bultos_id = fspt.id', 'left')
			->where('fspt.desde_cant_unid_bultos <= ' . $hasta)
			->where('fspt.hasta_cant_unid_bultos >= ' . $hasta)
			->where('fspt.zonaA = ' . $zonaA)
			->where('fspt.zonaB = ' . $zonaB)
			->where('fsp.cliente_id = ' . $cliente_id)
			->get('flash_servicios_tarifas_bultos fspt');

		$query_comparacionB = $query_comparacion_hastaCantidad->result();

		return $query_comparacionB;
	}

	function getComparacionTarifasBultos_igualacion($cliente_id, $desde, $hasta, $zonaA, $zonaB, $id=null)
	{
		if ($id) {
			$this->db->where('fspt.id != ' . $id);
		}

		$query_comparacion_hastaCantidad = $this->db
			->select('count(fspt.id) as cantidad')
			->join('flash_servicios_paqueteria fsp', 'fsp.tarifario_bultos_id = fspt.id', 'left')
			->where('fspt.desde_cant_unid_bultos >= ' . $desde) 
			->where('fspt.hasta_cant_unid_bultos <= ' . $hasta) 
			->where('fspt.zonaA = ' . $zonaA)
			->where('fspt.zonaB = ' . $zonaB)
			->where('fsp.cliente_id = ' . $cliente_id)
			->get('flash_servicios_tarifas_bultos fspt');

		$query_comparacionC = $query_comparacion_hastaCantidad->result();

		return $query_comparacionC;
	}

	function getComparacionTarifasKilos_desde($cliente_id, $desde_cantidad, $zonaA, $zonaB, $id=null)
	{
		if ($id) {
			$this->db->where('fspt.id != ' . $id);
		}

		$query_comparacion_desdeCantidad = $this->db
			->select('count(fspt.id) as cantidad')
			->join('flash_servicios_paqueteria fsp', 'fsp.tarifario_kg_id = fspt.id', 'left')
			->where('fspt.desde_cant_unid_kg <= ' . $desde_cantidad)
			->where('fspt.hasta_cant_unid_kg >= ' . $desde_cantidad)
			->where('fspt.zonaA = ' . $zonaA)
			->where('fspt.zonaB = ' . $zonaB)
			->where('fsp.cliente_id = ' . $cliente_id)
			->get('flash_servicios_tarifas_kg fspt');

		$query_comparacionA = $query_comparacion_desdeCantidad->result();

		return $query_comparacionA;
	}

	function getComparacionTarifasKilos_hasta($cliente_id, $hasta, $zonaA, $zonaB, $id=null)
	{
		if ($id) {
			$this->db->where('fspt.id != ' . $id);
		}

		$query_comparacion_hastaCantidad = $this->db
			->select('count(fspt.id) as cantidad')
			->join('flash_servicios_paqueteria fsp', 'fsp.tarifario_kg_id = fspt.id', 'left')
			->where('fspt.desde_cant_unid_kg <= ' . $hasta)
			->where('fspt.hasta_cant_unid_kg >= ' . $hasta)
			->where('fspt.zonaA = ' . $zonaA)
			->where('fspt.zonaB = ' . $zonaB)
			->where('fsp.cliente_id = ' . $cliente_id)
			->get('flash_servicios_tarifas_kg fspt');

		$query_comparacionB = $query_comparacion_hastaCantidad->result();

		return $query_comparacionB;
	}


	function getComparacionTarifasKilos_igualacion($cliente_id, $desde, $hasta, $zonaA, $zonaB, $id=null)
	{
		if ($id) {
			$this->db->where('fspt.id != ' . $id);
		}

		$query_comparacion_hastaCantidad = $this->db
			->select('count(fspt.id) as cantidad')
			->join('flash_servicios_paqueteria fsp', 'fsp.tarifario_kg_id = fspt.id', 'left')
			->where('fspt.desde_cant_unid_kg >= ' . $desde) 
			->where('fspt.hasta_cant_unid_kg <= ' . $hasta) 
			->where('fspt.zonaA = ' . $zonaA)
			->where('fspt.zonaB = ' . $zonaB)
			->where('fsp.cliente_id = ' . $cliente_id)
			->get('flash_servicios_tarifas_kg fspt');

		$query_comparacionC = $query_comparacion_hastaCantidad->result();

		return $query_comparacionC;
	}


	function getComparacionTarifasCobranza($cliente_id, $porcentaje, $zonaA, $zonaB, $id=null)
	{
		if ($id) {
			$this->db->where('fsct.id != ' . $id);
		}

		$query_comparacion = $this->db
			->select('count(fsct.id) as cantidad')
			->join('flash_servicios_paqueteria fsp', 'fsp.tarifario_cobranza_id = fsct.id', 'left')
			->where('fsct.porcentaje = ' . $porcentaje)
			->where('fsct.zonaA = ' . $zonaA)
			->where('fsct.zonaB = ' . $zonaB)
			->where('fsp.cliente_id = ' . $cliente_id)
			->get('flash_servicios_tarifas_cobranza fsct');

		$query_comparacionA = $query_comparacion->result();

		return $query_comparacionA;
	}


	function getComparacionTarifasValorDeclarado($cliente_id, $porcentaje, $zonaA, $zonaB, $id=null)
	{
		if ($id) {
			$this->db->where('fsct.id != ' . $id);
		}

		$query_comparacion = $this->db
			->select('count(fsct.id) as cantidad')
			->join('flash_servicios_paqueteria fsp', 'fsp.tarifario_valor_declarado_id = fsct.id', 'left')
			->where('fsct.porcentaje = ' . $porcentaje)
			->where('fsct.zonaA = ' . $zonaA)
			->where('fsct.zonaB = ' . $zonaB)
			->where('fsp.cliente_id = ' . $cliente_id)
			->get('flash_servicios_tarifas_valor_declarado fsct');

		$query_comparacionA = $query_comparacion->result();

		return $query_comparacionA;
	}


	function getComparacionTarifasMetroCubico_desde($cliente_id, $desde_cantidad, $zonaA, $zonaB, $id=null)
	{
		if ($id) {
			$this->db->where('fspt.id != ' . $id);
		}		

		$query_comparacion = $this->db
			->select('count(fspt.id) as cantidad')
			->join('flash_servicios_paqueteria fsp', 'fsp.tarifario_metro_cubico_id = fspt.id', 'left')
			->where('fspt.desde_cantidad_metro_cubico <= ' . $desde_cantidad)
			->where('fspt.hasta_cantidad_metro_cubico >= ' . $desde_cantidad)
			->where('fspt.zonaA = ' . $zonaA)
			->where('fspt.zonaB = ' . $zonaB)
			->where('fsp.cliente_id = ' . $cliente_id)
			->get('flash_servicios_tarifas_metro_cubico fspt');

		$query_comparacionA = $query_comparacion->result();

		return $query_comparacionA;
	}

	function getComparacionTarifasMetroCubico_hasta($cliente_id, $hasta_cantidad, $zonaA, $zonaB, $id=null)
	{
		if ($id) {
			$this->db->where('fspt.id != ' . $id);
		}

		$query_comparacion = $this->db
			->select('count(fspt.id) as cantidad')
			->join('flash_servicios_paqueteria fsp', 'fsp.tarifario_metro_cubico_id = fspt.id', 'left')
			->where('fspt.desde_cantidad_metro_cubico <= ' . $hasta_cantidad)
			->where('fspt.hasta_cantidad_metro_cubico >= ' . $hasta_cantidad)
			->where('fspt.zonaA = ' . $zonaA)
			->where('fspt.zonaB = ' . $zonaB)
			->where('fsp.cliente_id = ' . $cliente_id)
			->get('flash_servicios_tarifas_metro_cubico fspt');

		$query_comparacionB = $query_comparacion->result();

		return $query_comparacionB;
	}


	function getComparacionTarifasMetroCubico_igualacion($cliente_id, $desde, $hasta, $zonaA, $zonaB, $id=null)
	{
		if ($id) {
			$this->db->where('fspt.id != ' . $id);
		}

		$query_comparacion_hastaCantidad = $this->db
			->select('count(fspt.id) as cantidad')
			->join('flash_servicios_paqueteria fsp', 'fsp.tarifario_metro_cubico_id = fspt.id', 'left')
			->where('fspt.desde_cantidad_metro_cubico >= ' . $desde) 
			->where('fspt.hasta_cantidad_metro_cubico <= ' . $hasta) 
			->where('fspt.zonaA = ' . $zonaA)
			->where('fspt.zonaB = ' . $zonaB)
			->where('fsp.cliente_id = ' . $cliente_id)
			->get('flash_servicios_tarifas_metro_cubico fspt');

		$query_comparacionC = $query_comparacion_hastaCantidad->result();

		return $query_comparacionC;
	}


	function getComparacionTarifasPalets_desde($cliente_id, $desde_cantidad, $zonaA, $zonaB, $id=null)
	{
		if ($id) {
			$this->db->where('fspt.id != ' . $id);
		}

		$query_comparacion = $this->db
			->select('count(fspt.id) as cantidad')
			->join('flash_servicios_paqueteria fsp', 'fsp.tarifario_palets_id = fspt.id', 'left')
			->where('fspt.desde_cantidad_palets <= ' . $desde_cantidad)
			->where('fspt.hasta_cantidad_palets >= ' . $desde_cantidad)
			->where('fspt.zonaA = ' . $zonaA)
			->where('fspt.zonaB = ' . $zonaB)
			->where('fsp.cliente_id = ' . $cliente_id)
			->get('flash_servicios_tarifas_palets fspt');

		$query_comparacionA = $query_comparacion->result();

		return $query_comparacionA;
	}

	function getComparacionTarifasPalets_hasta($cliente_id, $hasta_cantidad, $zonaA, $zonaB, $id=null)
	{
		if ($id) {
			$this->db->where('fspt.id != ' . $id);
		}

		$query_comparacion = $this->db
			->select('count(fspt.id) as cantidad')
			->join('flash_servicios_paqueteria fsp', 'fsp.tarifario_palets_id = fspt.id', 'left')
			->where('fspt.desde_cantidad_palets <= ' . $hasta_cantidad)
			->where('fspt.hasta_cantidad_palets >= ' . $hasta_cantidad)
			->where('fspt.zonaA = ' . $zonaA)
			->where('fspt.zonaB = ' . $zonaB)
			->where('fsp.cliente_id = ' . $cliente_id)
			->get('flash_servicios_tarifas_palets fspt');

		$query_comparacionB = $query_comparacion->result();

		return $query_comparacionB;
	}


	function getComparacionTarifasPalets_igualacion($cliente_id, $desde, $hasta, $zonaA, $zonaB, $id=null)
	{
		if ($id) {
			$this->db->where('fspt.id != ' . $id);
		}

		$query_comparacion_hastaCantidad = $this->db
			->select('count(fspt.id) as cantidad')
			->join('flash_servicios_paqueteria fsp', 'fsp.tarifario_palets_id = fspt.id', 'left')
			->where('fspt.desde_cantidad_palets >= ' . $desde) 
			->where('fspt.hasta_cantidad_palets <= ' . $hasta) 
			->where('fspt.zonaA = ' . $zonaA)
			->where('fspt.zonaB = ' . $zonaB)
			->where('fsp.cliente_id = ' . $cliente_id)
			->get('flash_servicios_tarifas_palets fspt');

		$query_comparacionC = $query_comparacion_hastaCantidad->result();

		return $query_comparacionC;
	}


	function getComparacionTarifasGestionFlota($cliente_id, $opcion_vehiculo, $opcion_hora, $zonaA, $zonaB)
	{
		$query_comparacion = $this->db
			->select('count(fsgft.id) as cantidad')
			->join('flash_servicios_paqueteria fsp', 'fsp.tarifario_gestion_flota_id = fsgft.id', 'left')
			->where('fsgft.tipo_vehiculo = ' . $opcion_vehiculo)
			->where('fsgft.tipo_hora = ' . $opcion_hora)
			->where('fsgft.zonaA = ' . $zonaA)
			->where('fsgft.zonaB = ' . $zonaB)
			->where('fsp.cliente_id = ' . $cliente_id)
			->get('flash_servicios_tarifas_gestion_flota fsgft');

		$query_comparacionA = $query_comparacion->result();

		return $query_comparacionA;
	}



	function getComparacionTarifasWareHouse($cliente_id, $cantidad, $zonaA, $zonaB, $id=null)
	{
		if ($id) {
			$this->db->where('fswt.id != ' . $id);
		}

		$query_comparacion = $this->db
			->select('count(fswt.id) as cantidad')
			->join('flash_servicios_paqueteria fsp', 'fsp.tarifario_warehouse_id = fswt.id', 'left')
			->where('fswt.cantidad_wh = ' . $cantidad)
			->where('fswt.zonaA = ' . $zonaA)
			->where('fswt.zonaB = ' . $zonaB)
			->where('fsp.cliente_id = ' . $cliente_id)
			->get('flash_servicios_tarifas_warehouse fswt');

		$query_comparacionA = $query_comparacion->result();

		return $query_comparacionA;
	}


	function getTarifaKilos($id)
	{
		$query_tarifa = $this->db
			->select('fsp.id id_tarifa_paqueteria, fsp.cliente_id as cliente, tarifa.*')
			->join('flash_servicios_paqueteria fsp',  'fsp.tarifario_kg_id = tarifa.id', 'inner')
			->where('tarifa.id = ' . $id)
			->get('flash_servicios_tarifas_kg tarifa');			

		$query = $query_tarifa->result();

		return $query;
	}	

	function getTarifaBultos($id)
	{
		$query_tarifa = $this->db
			->select('fsp.id id_tarifa_paqueteria, fsp.cliente_id as cliente, tarifa.*')
			->join('flash_servicios_paqueteria fsp',  'fsp.tarifario_bultos_id = tarifa.id', 'inner')
			->where('tarifa.id = ' . $id)
			->get('flash_servicios_tarifas_bultos tarifa');			

		$query = $query_tarifa->result();

		return $query;
	}	

	function getTarifaCobranza($id)
	{
		$query_tarifa = $this->db
			->select('fsp.id id_tarifa_paqueteria, fsp.cliente_id as cliente, tarifa.*')
			->join('flash_servicios_paqueteria fsp',  'fsp.tarifario_cobranza_id = tarifa.id', 'inner')
			->where('tarifa.id = ' . $id)
			->get('flash_servicios_tarifas_cobranza tarifa');			

		$query = $query_tarifa->result();

		return $query;
	}		

	function getTarifaValorDeclarado($id)
	{
		$query_tarifa = $this->db
			->select('fsp.id id_tarifa_paqueteria, fsp.cliente_id as cliente, tarifa.*')
			->join('flash_servicios_paqueteria fsp',  'fsp.tarifario_valor_declarado_id = tarifa.id', 'inner')
			->where('tarifa.id = ' . $id)
			->get('flash_servicios_tarifas_valor_declarado tarifa');			

		$query = $query_tarifa->result();

		return $query;
	}	


	function getTarifaMetroCubico($id)
	{
		$query_tarifa = $this->db
			->select('fsp.id id_tarifa_paqueteria, fsp.cliente_id as cliente, tarifa.*')
			->join('flash_servicios_paqueteria fsp',  'fsp.tarifario_metro_cubico_id = tarifa.id', 'inner')
			->where('tarifa.id = ' . $id)
			->get('flash_servicios_tarifas_metro_cubico tarifa');			

		$query = $query_tarifa->result();

		return $query;
	}	
	
	function getTarifaPalets($id)
	{
		$query_tarifa = $this->db
			->select('fsp.id id_tarifa_paqueteria, fsp.cliente_id as cliente, tarifa.*')
			->join('flash_servicios_paqueteria fsp',  'fsp.tarifario_palets_id = tarifa.id', 'inner')
			->where('tarifa.id = ' . $id)
			->get('flash_servicios_tarifas_palets tarifa');			

		$query = $query_tarifa->result();

		return $query;
	}

	function getTarifaGestionFlota($id)
	{
		$query_tarifa = $this->db
			->select('fsp.id id_tarifa_paqueteria, fsp.cliente_id as cliente, tarifa.*')
			->join('flash_servicios_paqueteria fsp',  'fsp.tarifario_gestion_flota_id = tarifa.id', 'inner')
			->where('tarifa.id = ' . $id)
			->get('flash_servicios_tarifas_gestion_flota tarifa');			

		$query = $query_tarifa->result();

		return $query;
	}

	function getTarifaWareHouse($id)
	{
		$query_tarifa = $this->db
			->select('fsp.id id_tarifa_paqueteria, fsp.cliente_id as cliente, tarifa.*')
			->join('flash_servicios_paqueteria fsp',  'fsp.tarifario_warehouse_id = tarifa.id', 'inner')
			->where('tarifa.id = ' . $id)
			->get('flash_servicios_tarifas_warehouse tarifa');			

		$query = $query_tarifa->result();

		return $query;
	}	



	/**
	 * Obtiene el precio de un paquete dado el tarifario personalizado de un cliente
	 *
	 * @param [type] $idCliente
	 * @param [type] $paquete [$zonaA,$zonaB,$peso,...] fila de la tabla piezas_paquetes
	 * @return void
	 */
	function getPrecioPaquete($cliente_id, $paquete)
	{
		bcscale(2);
		$precio = 0;

		// ================================ PESO ===============================

		$tarifarioKg = $this->getTarifasKgPorCliente($cliente_id, $paquete['zonaA'], $paquete['zonaB']);
		if (!empty($tarifarioKg)) {

			// se comprara peso real vs peso aforado (alto cm x largo cm x ancho cm ) / 3500,
			// tomándose el valor mayor para el calculo 

			list($alto, $largo, $ancho) = explode('x', $paquete['dimensiones']);
			$pesoAforado = ($alto * $largo * $ancho) / 3500;
			$peso = $pesoAforado > $paquete['peso'] ?  $pesoAforado : $paquete['peso'];

			foreach ($tarifarioKg as $t) {
				if ($t->bandera_Corte_kg == 2 && $peso >= $t->desde_cant_unid_kg && $peso <= $t->hasta_cant_unid_kg) {
					$precio = bcadd($precio, $t->precio_Corte_kg);
				}
				if ($t->bandera_Corte_kg == 3 && $peso >= $t->desde_cant_unid_kg && $peso <= $t->hasta_cant_unid_kg) {
					$precio = bcadd($precio, bcmul($peso, $t->precio_Corte_kg));
				}
			}
		}

		// ================================ BULTOS ===============================	

		$tarifarioBultos = $this->getTarifasBultosPorCliente($cliente_id, $paquete['zonaA'], $paquete['zonaB']);

		if (!empty($tarifarioBultos)) {
			$bultos = $paquete['bultos'];

			foreach ($tarifarioBultos as $t) {
				if ($t->bandera_Corte_Bultos == 2 && $bultos >= $t->desde_cant_unid_bultos && $bultos <= $t->hasta_cant_unid_bultos) {
					$precio = bcadd($precio, $t->precio_Corte_bultos);
				}
				if ($t->bandera_Corte_Bultos == 3 && $bultos >= $t->desde_cant_unid_bultos && $bultos <= $t->hasta_cant_unid_bultos) {
					$precio = bcadd($precio, bcmul($bultos, $t->precio_Corte_bultos));
				}
			}
		}

		// ================================ PALETS ===============================	

		$tarifarioPalets = $this->getTarifasPaletsPorCliente($cliente_id, $paquete['zonaA'], $paquete['zonaB']);

		if (!empty($tarifarioPalets)) {

			foreach ($tarifarioPalets as $t) {
				if ($paquete['palets'] == $t->cantidad_palet) {
					$precio = bcadd($precio, $t->precio_palet);
				}
			}
		}





		// ================================ COBRANZA ===============================	ULTIMO SIEMPRE

		$tarifarioCobranza = $this->getTarifasCobranzaPorCliente($cliente_id, $paquete['zonaA'], $paquete['zonaB']);

		if (!empty($tarifarioCobranza)) {

			foreach ($tarifarioCobranza as $t) {
				if ($precio >= $t->desde_monto_cobranza && $precio <= $t->hasta_monto_cobranza) {
					$porcentaje = bcadd(bcdiv($t->precio_Corte_cobranza, 100), 1);
					$precio = bcmul($precio, $porcentaje);
				}
			}
		}



		return $precio;

		/* $cobranza = $this->getTarifasCobranzaPorCliente($cliente_id);

		if (!empty($cobranza)) {
		} */
	}
}
