SELECT 
	T.id tarifario, 
    C.nombre cliente, 
    E.nombre esquema,
	IF(COUNT(K.id) > 0,TRUE,FALSE) kilos,
    IF(COUNT(B.id) > 0,TRUE,FALSE) bultos,
    IF(COUNT(P.id) > 0,TRUE,FALSE) palets,
    IF(COUNT(M.id) > 0,TRUE,FALSE) metrosCubicos,
    IF(COUNT(W.id) > 0,TRUE,FALSE) warehouse,
    IF(COUNT(G.id) > 0,TRUE,FALSE) gestionFlota,
    IF(COUNT(COB.id) > 0,TRUE,FALSE) cobranza,
    IF(COUNT(V.id) > 0,TRUE,FALSE) valorDeclarado,
    IF(COUNT(PA.id) > 0,TRUE,FALSE) pesoAforado
    
FROM flash_tarifario T

INNER JOIN flash_clientes C on C.id = T.cliente_id
INNER JOIN flash_esquema E on E.id = T.esquema_id
INNER JOIN flash_sub_esquema S ON S.esquema_id = E.id
LEFT JOIN flash_sub_esquema_kg K ON K.sub_esquema_id = S.id
LEFT JOIN flash_sub_esquema_bultos B ON B.sub_esquema_id = S.id
LEFT JOIN flash_sub_esquema_palets P ON P.sub_esquema_id = S.id
LEFT JOIN flash_sub_esquema_metros_cubicos M ON M.sub_esquema_id = S.id
LEFT JOIN flash_sub_esquema_warehouse W ON W.sub_esquema_id = S.id
LEFT JOIN flash_sub_esquema_gestion_flota G ON G.sub_esquema_id = S.id
LEFT JOIN flash_sub_esquema_valor_declarado V ON V.sub_esquema_id = S.id
LEFT JOIN flash_sub_esquema_cobranza COB ON COB.sub_esquema_id = S.id
LEFT JOIN flash_sub_esquema_peso_aforado PA ON PA.sub_esquema_id = S.id

WHERE C.id = 1