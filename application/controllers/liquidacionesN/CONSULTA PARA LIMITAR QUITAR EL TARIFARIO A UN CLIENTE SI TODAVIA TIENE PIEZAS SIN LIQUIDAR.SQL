SELECT COUNT(*) cantidadPiezasSinLiquidar
/*
    P.id piezaID,
    PP.peso peso, 
    PP.palets palets,
    PP.bultos bultos,
    PP.metro_cubico metroCubico,
	CI.id comprobanteIngresoID,
    CI.estado_liquidacion estadoLiquidacion,
    CI.liquidacion_id liquidacionID
*/
FROM flash_piezas P 
INNER JOIN flash_piezas_paquetes PP ON PP.pieza_id = P.id
INNER JOIN flash_comprobantes_ingresos CI ON CI.id = P.comprobante_ingreso_id
WHERE 
	CI.cliente_id = 2
    AND
    CI.liquidacion_id = 0
    AND 
    CI.estado_liquidacion = 0 OR CI.estado_liquidacion = 13 #ESTADO 13 ANULADO, SE PUEDE VOLVER A LIQUIDAR