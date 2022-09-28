//////////////////////////////////////
//////////// COBRANZA ////////////////
//////////////////////////////////////

SELECT COUNT(*) AS cantidad
FROM flash_servicios_paqueteria_nuevo P
INNER JOIN flash_servicios_tarifas_cobranza C ON C.servicio_paqueteria_id = P.id
WHERE P.cliente_id = 1 AND C.zonaA = 2 AND C.zonaB = 10

//////////////////////////////////////
//////////// VALOR DECLARADO /////////
//////////////////////////////////////

SELECT COUNT(*)
FROM flash_servicios_paqueteria_nuevo P
INNER JOIN flash_servicios_tarifas_valor_declarado D ON D.servicio_paqueteria_id = P.id
WHERE P.cliente_id = 1 AND D.zonaA = 2 AND D.zonaB = 10

//////////////////////////////////////
//////////// PESO AFORADO ////////////
//////////////////////////////////////

SELECT COUNT(*)
FROM flash_servicios_paqueteria_nuevo P
INNER JOIN flash_servicios_tarifas_peso_aforado A ON A.servicio_paqueteria_id = P.id
WHERE P.cliente_id = 1 AND A.zonaA = 2 AND A.zonaB = 10

//////////////////////////////////////
//////////////// KILOS ///////////////
//////////////////////////////////////

-- VERIFACIÓN KILOS DESDE

-- SELECT P.id as idPaqueteria, K.id AS idKilo, K.zonaA, K.zonaB, K.desde_cant_unid_kg, K.hasta_cant_unid_kg, K.precio_Corte_kg, K.bandera_Corte_kg
SELECT COUNT(*)
FROM flash_servicios_paqueteria_nuevo P
INNER JOIN flash_servicios_tarifas_kg K ON K.servicio_paqueteria_id = P.id
WHERE 
    P.cliente_id = 1 
    AND K.zonaA = 2 
    AND K.zonaB = 10
    AND K.desde_cant_unid_kg <= $DESDE AND K.hasta_cant_unid_kg >= $DESDE

-- VERIFACION KILOS HASTA

SELECT COUNT(*)
FROM flash_servicios_paqueteria_nuevo P
INNER JOIN flash_servicios_tarifas_kg K ON K.servicio_paqueteria_id = P.id
WHERE 
    P.cliente_id = 1 
    AND K.zonaA = 2 
    AND K.zonaB = 10
    AND K.desde_cant_unid_kg <= $HASTA AND K.hasta_cant_unid_kg >= $HASTA

-- IGUALACION

SELECT P.id as idPaqueteria, K.id AS idKilo, K.zonaA, K.zonaB, K.desde_cant_unid_kg, K.hasta_cant_unid_kg, K.precio_Corte_kg, K.bandera_Corte_kg
FROM flash_servicios_paqueteria_nuevo P
INNER JOIN flash_servicios_tarifas_kg K ON K.servicio_paqueteria_id = P.id
WHERE 
    P.cliente_id = 1 
    AND K.zonaA = 2 
    AND K.zonaB = 10
    AND K.desde_cant_unid_kg >= $DESDE 
    AND K.hasta_cant_unid_kg <= $HASTA

//////////////////////////////////////
//////////////// BULTOS //////////////
//////////////////////////////////////

SELECT COUNT(*)
FROM flash_servicios_paqueteria_nuevo P
INNER JOIN flash_servicios_tarifas_bultos B ON B.servicio_paqueteria_id = P.id
WHERE 
    P.cliente_id = 1 
    AND B.zonaA = 2 
    AND B.zonaB = 10
    AND B.desde_cant_unid_bultos <= $DESDE AND B.hasta_cant_unid_bultos >= $DESDE

SELECT COUNT(*)
FROM flash_servicios_paqueteria_nuevo P
INNER JOIN flash_servicios_tarifas_bultos B ON B.servicio_paqueteria_id = P.id
WHERE 
    P.cliente_id = 1 
    AND B.zonaA = 2 
    AND B.zonaB = 10
    AND B.desde_cant_unid_bultos <= $HASTA AND B.hasta_cant_unid_bultos >= $HASTA

SELECT COUNT(*)
FROM flash_servicios_paqueteria_nuevo P
INNER JOIN flash_servicios_tarifas_bultos B ON B.servicio_paqueteria_id = P.id
WHERE 
    P.cliente_id = 1 
    AND B.zonaA = 2 
    AND B.zonaB = 10
    AND B.desde_cant_unid_bultos >= $DESDE AND B.hasta_cant_unid_bultos <= $HASTA

//////////////////////////////////////
//////////////// PALETS //////////////
//////////////////////////////////////

SELECT COUNT(*)
FROM flash_servicios_paqueteria_nuevo P
INNER JOIN flash_servicios_tarifas_palets Pa ON Pa.servicio_paqueteria_id = P.id
WHERE 
    P.cliente_id = 1 
    AND Pa.zonaA = 2 
    AND Pa.zonaB = 10
    AND Pa.desde_cantidad_palets <= $DESDE AND Pa.hasta_cantidad_palets >= $DESDE

SELECT COUNT(*)
FROM flash_servicios_paqueteria_nuevo P
INNER JOIN flash_servicios_tarifas_palets Pa ON Pa.servicio_paqueteria_id = P.id
WHERE 
    P.cliente_id = 1 
    AND Pa.zonaA = 2 
    AND Pa.zonaB = 10
    AND Pa.desde_cantidad_palets <= $HASTA AND Pa.hasta_cantidad_palets >= $HASTA

SELECT COUNT(*)
FROM flash_servicios_paqueteria_nuevo P
INNER JOIN flash_servicios_tarifas_palets Pa ON Pa.servicio_paqueteria_id = P.id
WHERE 
    P.cliente_id = 1 
    AND Pa.zonaA = 2 
    AND Pa.zonaB = 10
    AND Pa.desde_cantidad_palets >= $DESDE AND Pa.hasta_cantidad_palets <= $HASTA

//////////////////////////////////////
///////// METROS CUBICOS /////////////
//////////////////////////////////////

SELECT COUNT(*)
FROM flash_servicios_paqueteria_nuevo P
INNER JOIN flash_servicios_tarifas_metro_cubico M ON M.servicio_paqueteria_id = P.id
WHERE 
    P.cliente_id = 1 
    AND M.zonaA = 2 
    AND M.zonaB = 10
    AND M.desde_cantidad_metro_cubico <= $DESDE AND M.hasta_cantidad_metro_cubico >= $DESDE

SELECT COUNT(*)
FROM flash_servicios_paqueteria_nuevo P
INNER JOIN flash_servicios_tarifas_metro_cubico M ON M.servicio_paqueteria_id = P.id
WHERE 
    P.cliente_id = 1 
    AND M.zonaA = 2 
    AND M.zonaB = 10
    AND M.desde_cantidad_metro_cubico <= $HASTA AND M.hasta_cantidad_metro_cubico >= $HASTA

SELECT COUNT(*)
FROM flash_servicios_paqueteria_nuevo P
INNER JOIN flash_servicios_tarifas_metro_cubico M ON M.servicio_paqueteria_id = P.id
WHERE 
    P.cliente_id = 1 
    AND M.zonaA = 2 
    AND M.zonaB = 10
    AND M.desde_cantidad_metro_cubico >= $DESDE AND M.hasta_cantidad_metro_cubico <= $HASTA

//////////////////////////////////////
///////////// WAREHOUSE //////////////
//////////////////////////////////////

SELECT COUNT(*)
FROM flash_servicios_paqueteria_nuevo P
INNER JOIN flash_servicios_tarifas_warehouse W ON W.servicio_paqueteria_id = P.id
WHERE 
    P.cliente_id = 1 
    AND W.zonaA = 2 
    AND W.zonaB = 10
    AND W.rinicio_wh <= $DESDE AND W.rfin_wh >= $DESDE

SELECT COUNT(*)
FROM flash_servicios_paqueteria_nuevo P
INNER JOIN flash_servicios_tarifas_warehouse W ON W.servicio_paqueteria_id = P.id
WHERE 
    P.cliente_id = 1 
    AND W.zonaA = 2 
    AND W.zonaB = 10
    AND W.rinicio_wh <= $HASTA AND W.rfin_wh >= $HASTA

SELECT COUNT(*)
FROM flash_servicios_paqueteria_nuevo P
INNER JOIN flash_servicios_tarifas_warehouse W ON W.servicio_paqueteria_id = P.id
WHERE 
    P.cliente_id = 1 
    AND W.zonaA = 2 
    AND W.zonaB = 10
    AND W.rinicio_wh >= $DESDE AND W.rfin_wh <= $HASTA


//////////////////////////////////////
///////////// GESTION F. /////////////
//////////////////////////////////////

SELECT COUNT(*)
FROM flash_servicios_paqueteria_nuevo P
INNER JOIN flash_servicios_tarifas_gestion_flota G ON G.servicio_paqueteria_id = P.id
WHERE 
    P.cliente_id = 1 
    AND G.zonaA = 2 
    AND G.zonaB = 10
    AND G.tipo_vehiculo = $TIPO_VEHICULO
    AND G.tipo_hora = $TIPO_HORA