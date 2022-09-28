<?php

/**
 * Crear/update feed para la tienda
 *
 * @param [type] $nombreTienda
 * @param [type] $apiKey
 * @param [type] $apiSecret
 * @param [type] $rolTienda 'M' / 'F'
 * @return 
 */
function vtex_createFeed($nombreTienda, $apiKey, $apiSecret, $rolTienda)
{
	if ($rolTienda == 'M') {
		$rolTienda = 'Marketplace';
	} elseif ($rolTienda == 'F') {
		$rolTienda = 'Fulfillment';
	}

	$evento = 'invoiced';

	$configFeed = array(
		"filter" => array(
			"type" => "FromOrders",
			"expression" => "status = \"{$evento}\" and origin=\"{$rolTienda}\"",
			"disableSingleFire" => false
		),
		"queue" => array(
			"visibilityTimeoutInSeconds" => 240,
			"messageRetentionPeriodInSeconds" => 1209600
		)
	);

	$configFeed = json_encode($configFeed, true);

	$curl = curl_init();

	curl_setopt_array($curl, array(
		CURLOPT_URL => "https://{$nombreTienda}.vtexcommercestable.com.br/api/orders/feed/config",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS => $configFeed,
		CURLOPT_HTTPHEADER => array(
			"Content-Type: application/json",
			"X-VTEX-API-AppKey: {$apiKey}",
			"X-VTEX-API-AppToken: {$apiSecret}",
		),
	));
	$response = curl_exec($curl);
	$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	curl_close($curl);

	if ($httpcode < 400) {
		return true;
	} else {
		throw new Exception('error en ' . __FUNCTION__  . '  status: ' . $httpcode . ' response: ' . $response);
	}
}

/**
 * Crea un politica de envio para el la tienda del cliente 
 *
 * @param [type] $nombreTienda
 * @param [type] $apiKey
 * @param [type] $apiSecret
 * @param Object $tarifario fila de tabla vtex_tarifarios
 * @return bool si tiene exito o no
 */
function vtex_createShippingPolicy($nombreTienda, $apiKey, $apiSecret, $tarifario)
{
	$data = [
		"id" => $tarifario->id,
		"name" => $tarifario->nombre,
		"shippingMethod" => "Estandar",
		"weekendAndHolidays" => [
			"saturday" => true,
			"sunday" => false,
			"holiday" => false
		],
		"maxDimension" => [
			"largestMeasure" => $tarifario->max_arista,
			"maxMeasureSum" => $tarifario->max_suma_aristas
		],
		"cubicWeightSettings" => [
			"volumetricFactor" => $tarifario->factor_volumetrico,
			"minimunAcceptableVolumetricWeight" => $tarifario->factor_peso_minimo
		],
		"numberOfItemsPerShipment" => $tarifario->minimo_items,
		"minimumValueAceptable" => $tarifario->valor_minimo,
		"maximumValueAceptable" => $tarifario->valor_maximo,
		"modalSettings" => [
			"modals" => [
				"MATTRESSES",
				"TIRES",
				"GLASS",
				"ELECTRONICS",
				"FURNITURE",
				"LIQUID",
				"WHITE_GOODS"
			],
			"useOnlyItemsWithDefinedModal" => false
		],
		"businessHourSettings" => [
			"carrierBusinessHours" => [
				[
					"openingTime" => "09:00:00",
					"closingTime" => "18:00:00",
					"dayOfWeek" => 1
				],
				[
					"openingTime" => "09:00:00",
					"closingTime" => "18:00:00",
					"dayOfWeek" => 2
				],
				[
					"openingTime" => "09:00:00",
					"closingTime" => "18:00:00",
					"dayOfWeek" => 3
				],
				[
					"openingTime" => "09:00:00",
					"closingTime" => "18:00:00",
					"dayOfWeek" => 4
				],
				[
					"openingTime" => "09:00:00",
					"closingTime" => "18:00:00",
					"dayOfWeek" => 5
				],
				[
					"openingTime" => "09:00:00",
					"closingTime" => "18:00:00",
					"dayOfWeek" => 6
				]
			],
			"isOpenOutsideBusinessHours" => true
		],
		"deliveryScheduleSettings" => [
			"useDeliverySchedule" => false,
			"dayOfWeekForDelivery" => [
				"deliveryRanges" => [
					"deliveryCapacity" => [
						"maxValue" => "0.0"
					]
				]
			]
		],
		"isActive" => true
	];

	$data = json_encode($data, true);

	$curl = curl_init();

	curl_setopt_array($curl, array(
		CURLOPT_URL => "https://{$nombreTienda}.vtexcommercestable.com.br/api/logistics/pvt/shipping-policies",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS => $data,
		CURLOPT_HTTPHEADER => array(
			"Content-Type: application/json",
			"X-VTEX-API-AppKey: {$apiKey}",
			"X-VTEX-API-AppToken: {$apiSecret}",
		),
	));

	$response = curl_exec($curl);
	$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	curl_close($curl);

	if ($httpcode < 400) {
		return true;
	} else {
		throw new Exception('error en ' . __FUNCTION__  . '  status: ' . $httpcode . ' response: ' . $response);
	}
}

/**
 * Actualizar politica de envio/tarifario en vtex
 *
 * @param [type] $nombreTienda
 * @param [type] $apiKey
 * @param [type] $apiSecret
 * @param [type] $tarifario
 * @return bool
 */
function vtex_updateShippingPolicy($nombreTienda, $apiKey, $apiSecret, $tarifario)
{
	$data = [
		"id" => $tarifario->id,
		"name" => $tarifario->nombre,
		"shippingMethod" => "Estandar",
		"weekendAndHolidays" => [
			"saturday" => true,
			"sunday" => false,
			"holiday" => false
		],
		"maxDimension" => [
			"largestMeasure" => $tarifario->max_arista,
			"maxMeasureSum" => $tarifario->max_suma_aristas
		],
		"cubicWeightSettings" => [
			"volumetricFactor" => $tarifario->factor_volumetrico,
			"minimunAcceptableVolumetricWeight" => $tarifario->factor_peso_minimo
		],
		"numberOfItemsPerShipment" => $tarifario->minimo_items,
		"minimumValueAceptable" => $tarifario->valor_minimo,
		"maximumValueAceptable" => $tarifario->valor_maximo,
		"modalSettings" => [
			"modals" => [
				"MATTRESSES",
				"TIRES",
				"GLASS",
				"ELECTRONICS",
				"FURNITURE",
				"LIQUID",
				"WHITE_GOODS"
			],
			"useOnlyItemsWithDefinedModal" => false
		],
		"businessHourSettings" => [
			"carrierBusinessHours" => [
				[
					"openingTime" => "09:00:00",
					"closingTime" => "18:00:00",
					"dayOfWeek" => 1
				],
				[
					"openingTime" => "09:00:00",
					"closingTime" => "18:00:00",
					"dayOfWeek" => 2
				],
				[
					"openingTime" => "09:00:00",
					"closingTime" => "18:00:00",
					"dayOfWeek" => 3
				],
				[
					"openingTime" => "09:00:00",
					"closingTime" => "18:00:00",
					"dayOfWeek" => 4
				],
				[
					"openingTime" => "09:00:00",
					"closingTime" => "18:00:00",
					"dayOfWeek" => 5
				],
				[
					"openingTime" => "09:00:00",
					"closingTime" => "18:00:00",
					"dayOfWeek" => 6
				]
			],
			"isOpenOutsideBusinessHours" => true
		],
		"deliveryScheduleSettings" => [
			"useDeliverySchedule" => false,
			"dayOfWeekForDelivery" => [
				"deliveryRanges" => [
					"deliveryCapacity" => [
						"maxValue" => "0.0"
					]
				]
			]
		],
		"isActive" => true
	];

	$data = json_encode($data, true);

	$curl = curl_init();

	curl_setopt_array($curl, array(
		CURLOPT_URL => "https://{$nombreTienda}.vtexcommercestable.com.br/api/logistics/pvt/shipping-policies/{$tarifario->id}",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'PUT',
		CURLOPT_POSTFIELDS => $data,
		CURLOPT_HTTPHEADER => array(
			"Content-Type: application/json",
			"X-VTEX-API-AppKey: {$apiKey}",
			"X-VTEX-API-AppToken: {$apiSecret}",
		),
	));

	$response = curl_exec($curl);
	$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	curl_close($curl);

	if ($httpcode < 400) {
		return true;
	} else {
		throw new Exception('error en ' . __FUNCTION__  . '  status: ' . $httpcode . ' response: ' . $response);
	}
}


/**
 * Borra una politica de envio por id
 *
 * @param [type] $nombreTienda
 * @param [type] $apiKey
 * @param [type] $apiSecret
 * @param [type] $idTarifario id de politica de envio
 * @return void
 */
function vtex_deleteShippingPolicyById($nombreTienda,  $apiKey,  $apiSecret,  $idTarifario)
{
	if (empty($idTarifario)) {
		return true;
	}

	$curl = curl_init();

	curl_setopt_array($curl, array(
		CURLOPT_URL => "https://{$nombreTienda}.vtexcommercestable.com.br/api/logistics/pvt/shipping-policies/{$idTarifario}",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'DELETE',
		CURLOPT_HTTPHEADER => array(
			"X-VTEX-API-AppKey: {$apiKey}",
			"X-VTEX-API-AppToken: {$apiSecret}",
		),
	));

	$response = curl_exec($curl);
	$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	curl_close($curl);

	if ($httpcode <= 400) {
		return true;
	} else {
		throw new Exception('error en ' . __FUNCTION__  . '  status: ' . $httpcode . ' response: ' . $response);
	}
}

/**
 * Envia planillas de tarifas de envio. Dependiendo del metodo borra
 * o inserta la planilla recibida
 *
 * @param [type] $nombreTienda
 * @param [type] $apiKey
 * @param [type] $apiSecret
 * @param [Object] $tarifario_precios array con resultados de tabla vtex_tarifarios_precios
 * @param [type] $method 'POST' / 'DELETE'
 * @return bool
 */
function vtex_updateFreightModel($nombreTienda,   $apiKey,   $apiSecret, $idTarifario, $tarifario_precios,  $method)
{
	if ($method == NULL) {
		$method = 'POST';
	}

	if ($method == "POST") {
		foreach ($tarifario_precios as $rango) {
			$rango->operationType = 1;
		}
	}

	if ($method == "DELETE") {
		foreach ($tarifario_precios as $rango) {
			$rango->operationType = 3;
		}
	}

	$data = json_encode($tarifario_precios, true);

	$curl = curl_init();

	curl_setopt_array($curl, array(

		CURLOPT_URL => "https://{$nombreTienda}.vtexcommercestable.com.br/api/logistics/pvt/configuration/freights/{$idTarifario}/values/update",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_POSTFIELDS => $data,
		CURLOPT_HTTPHEADER => array(
			"Content-Type: application/json",
			"X-VTEX-API-AppKey: {$apiKey}",
			"X-VTEX-API-AppToken: {$apiSecret}",
		),
	));

	$response = curl_exec($curl);
	$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	curl_close($curl);

	if ($httpcode < 400) {
		return true;
	} else {
		throw new Exception('error en ' . __FUNCTION__  . '  status: ' . $httpcode . ' response: ' . $response);
	}
}


/**
 * Agrega la politica de envio con id $idTarifario al muelle de $idMuelle 
 *
 * @param [type] $nombreTienda
 * @param [type] $apiKey
 * @param [type] $apiSecret
 * @param [type] $idMuelle
 * @param [type] $idTarifario
 * @return bool
 */
function vtex_addShippingPolicyToDock($nombreTienda, $apiKey, $apiSecret, $idMuelle, $idTarifario)
{
	$data = vtex_getDockById($nombreTienda, $apiKey, $apiSecret, $idMuelle);


	if ($data != false) {

		$data['freightTableIds'][] = $idTarifario;
		$data = json_encode($data, true);

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://{$nombreTienda}.vtexcommercestable.com.br/api/logistics/pvt/configuration/docks",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => $data,
			CURLOPT_HTTPHEADER => array(
				"Content-Type: application/json",
				"X-VTEX-API-AppKey: {$apiKey}",
				"X-VTEX-API-AppToken: {$apiSecret}",
			),
		));

		$response = curl_exec($curl);
		$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);

		if ($httpcode < 400) {
			return true;
		} else {
			throw new Exception('error en ' . __FUNCTION__  . '  status: ' . $httpcode . ' response: ' . $response);
		}
	}
}


/**
 * Borra una politica de envio(idTarifario) de un muelle(idMulle)
 *
 * @param [type] $nombreTienda
 * @param [type] $apiKey
 * @param [type] $apiSecret
 * @param [type] $idMuelle
 * @param [type] $idTarifario
 * @return void
 */
function vtex_deleteShippingPolicyFromDock($nombreTienda, $apiKey, $apiSecret, $idMuelle, $idTarifario)
{
	$data = vtex_getDockById($nombreTienda, $apiKey, $apiSecret, $idMuelle);

	if ($data != false) {

		$data['freightTableIds'] = array_filter($data['freightTableIds'], function ($idT) use ($idTarifario) {
			return $idT != $idTarifario;
		});

		$data = json_encode($data, true);


		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://{$nombreTienda}.vtexcommercestable.com.br/api/logistics/pvt/configuration/docks",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => $data,
			CURLOPT_HTTPHEADER => array(
				"Content-Type: application/json",
				"X-VTEX-API-AppKey: {$apiKey}",
				"X-VTEX-API-AppToken: {$apiSecret}",
			),
		));

		$response = curl_exec($curl);
		$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);



		if ($httpcode < 400) {
			return true;
		} else {
			throw new Exception('error en ' . __FUNCTION__  . '  status: ' . $httpcode . ' response: ' . $response);
		}
	}
}


/**
 * Busca la informacion del muelle. Retorna los datos o falso en caso de error
 *
 * @param [type] $nombreTienda
 * @param [type] $apiKey
 * @param [type] $apiSecret
 * @param [type] $idMuelle
 * @return string
 */
function vtex_getDockById($nombreTienda, $apiKey, $apiSecret, $idMuelle)
{

	$curl = curl_init();

	curl_setopt_array($curl, array(
		CURLOPT_URL => "https://{$nombreTienda}.vtexcommercestable.com.br/api/logistics/pvt/configuration/docks/{$idMuelle}",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'GET',
		CURLOPT_HTTPHEADER => array(
			"Content-Type: application/json",
			"X-VTEX-API-AppKey: {$apiKey}",
			"X-VTEX-API-AppToken: {$apiSecret}",
		),
	));

	$response = curl_exec($curl);
	$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	curl_close($curl);

	$response = json_decode($response, true);
	if ($httpcode < 400) {
		return $response;
	} else {
		throw new Exception('error en ' . __FUNCTION__  . '  status: ' . $httpcode . ' response: ' . $response);
	}
}

/**
 * Actualiza las politicas de envio de todos los clientes
 * actualiza tambien los precios dependiendo del segundo parametro
 *
 * @param [type] $idTarifario
 * @param Obejct $preciosNuevos 
 * @param Object $preciosViejos Se modificaron los precios? true/false
 * @return bool
 */
function vtex_updateMasivoShippingPolicy($idTarifario, $preciosNuevos = NULL, $preciosViejos = NULL)
{
	try {
		$CI = &get_instance();

		// TARIFRIO A ACTUALIZAR
		$queryTarifario = "SELECT *
		FROM vtex_tarifarios
		WHERE id = '$idTarifario'";
		$tarifario = $CI->db->query($queryTarifario)->row();

		// CLIENTES A ACTUALIZAR
		$queryClientes = "SELECT 
		fc.id 'idCliente', 
		fc.nombre 'nombreCliente',
		SUBSTRING_INDEX(fc.nombre_fantasia, '/', -1) as 'cuentaCliente',
		fc.observaciones 'info',
		fca.api_key as 'appKey',
		fca.secret_key as 'token'
		FROM flash_clientes fc
		INNER JOIN flash_clientes_api fca on fca.cliente_id = fc.id
		WHERE nombre_fantasia LIKE 'VTEX/%'";

		$clientes = $CI->db->query($queryClientes)->result();

		foreach ($clientes as $c) {
			list($c->rolTienda, $c->idMuelle, $c->tarifario) = explode('/', $c->info);
			// Si c tiene el tarifario que se actualizo
			if ($c->tarifario == $idTarifario) {
				vtex_updateShippingPolicy($c->cuentaCliente, $c->appKey, $c->token, $tarifario);
				if (!empty($preciosNuevos)) {
					vtex_updateFreightModel($c->cuentaCliente, $c->appKey, $c->token, $idTarifario, $preciosViejos, 'DELETE');
					vtex_updateFreightModel($c->cuentaCliente, $c->appKey, $c->token, $idTarifario, $preciosNuevos, 'POST');
				}
			}
		}
	} catch (\Throwable $e) {
		throw $e;
	}
}
