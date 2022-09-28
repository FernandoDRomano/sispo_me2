<?php


function detalle_control(Request $request)
{
    $id_recorrido = $request->id;
    $fecha = $request->fecha;
    // dd($id);

    $recorrido = DB::connection('Sispo')->select(DB::raw("SELECT
            grupo.id as 'grupo_id',
            grupo.fecha_creacion,
            grupo.fecha_ejecucion,
            grupo.distancia,
            carteros.apellido_nombre as cartero,
            IF(grupo.estado = 0, 'Abierto', 'Cerrado') as estado,
            piezas.pieza_id,
            piezas.id,
            piezas.destinatario,
            piezas.domicilio,
            cor.latitud as 'latitud',
            cor.longitud as 'longitud',
            orden_recorrido

        FROM sispoc5_ruteo.grupo_asignacion as grupo
        INNER JOIN sispoc5_ruteo.asignacion as asi on asi.grupo_asignacion_id = grupo.id
        INNER JOIN sispoc5_ruteo.piezas on piezas.id = asi.pieza_id
        INNER JOIN sispoc5_ruteo.coordenadas as cor on cor.pieza_id= piezas.id
        LEFT JOIN sispoc5_gestionpostal.flash_sucursales_carteros as carteros on carteros.id = grupo.id_cartero

        WHERE grupo.id = '$id_recorrido'
    "));

    $recorrido_estimado1 = $recorrido;

    $recorrido = null;

    /*reco 2--------------------------------------------------------------------------------*/

    $recorrido = DB::connection('Sispo')->select(DB::raw("
        SELECT cgps.*, fsc.apellido_nombre as cartero, DATE(cgps.FechaLocal) as fecha

        FROM sispoc5_gestionpostal.cartero_gps as cgps

        LEFT JOIN sispoc5_gestionpostal.flash_sucursales_carteros as fsc on fsc.id = cgps.cartero_id
        
        INNER JOIN (
            SELECT cgps0.FechaLocal, cgps0.id
            FROM sispoc5_gestionpostal.cartero_gps as cgps0
            WHERE cgps0.recorrido = '$id_recorrido' and DATE(cgps0.FechaLocal) = '$fecha'
            -- group by cgps0.FechaLocal
        ) as sub ON cgps.id = sub.id

        WHERE cgps.recorrido = '$id_recorrido' and DATE(cgps.FechaLocal) = '$fecha'
        
        -- and mod(cgps.id,2) = 0
    "));
    
   // dd($recorrido);

    $distancia_tiempo = DB::connection('Sispo')->select(DB::raw("
        SELECT SUM(Distance) as distancia, TIMEDIFF(MAX(cgps.FechaLocal), MIN(cgps.FechaLocal)) as tiempo

        FROM sispoc5_gestionpostal.cartero_gps as cgps

        WHERE cgps.recorrido = '$id_recorrido' and DATE(cgps.FechaLocal) = '$fecha'

    "));


    $distancia = $distancia_tiempo[0]->distancia;
    $distancia = $distancia * 0.1;
    $format_number = number_format($distancia, 2);

    $tiempo_real = $distancia_tiempo[0]->tiempo;


    // dd($format_number);

    $recorrido_real2 = $recorrido;

    $recorrido = null;

    /*reco 3--------------------------------------------------------------------------------*/
    $piezas = DB::select(DB::raw("
        SELECT ga.*, p.pieza_id as 'piezaID'
        FROM grupo_asignacion as ga
        LEFT JOIN asignacion as a ON a.grupo_asignacion_id = ga.id
        LEFT JOIN piezas as p ON p.id = a.pieza_id
        WHERE ga.id = '$id_recorrido'
    "));


    $piezas_id = array_column($piezas, 'piezaID');
    $piezas_id_string = implode("','", $piezas_id);

   $piezas_id_string = substr($piezas_id_string, 1);
    $piezas_id_string = substr($piezas_id_string, 1);
    
    $piezas_id_string = $piezas_id_string . "'";
  //  $piezas_id_string = substr($piezas_id_string, 0, -1);
  //  $piezas_id_string = substr($piezas_id_string, 0, -1);

    
    //dd('//' . $piezas_id_string . '//');
    
    $novedades_p = DB::connection('Sispo')->select(DB::raw("
        SELECT 
            fp.id as 'pieza'
            , fp.destinatario
            , fp.domicilio
            , fe.id as 'id_estado'
            , fe.nombre as 'nombre_estado'
            , sub1.Latitud as 'lat'
            , sub1.Longitud as 'lng'
            , asig.orden_recorrido as 'orden'
            , sub1.Fecha as fecha

        from (
            select fp.*
            from sispoc5_gestionpostal.flash_piezas as fp
            inner join sispoc5_gestionpostal.flash_comprobantes_ingresos as ci on fp.comprobante_ingreso_id = ci.id
            where fp.tipo_id = 2
            and fp.id in ($piezas_id_string)
            and ci.cliente_id <> 30
        ) as fp
        left join(
            SELECT pe.id, pe.idPieza
            FROM sispoc5_Ocasa.Piezas_Estados as pe
            inner JOIN sispoc5_Ocasa.EstadoEntregaApp as eea ON pe.idEstados = eea.id
            inner JOIN sispoc5_Ocasa.EstadoEntregaAppGestionPostal as ee on ee.IdEstadoEntregaApp = eea.id
            inner join sispoc5_gestionpostal.flash_piezas_estados_variables as fe on ee.IdEstadoEntregaGestionPostal = fe.id
            WHERE fe.pieza_estado_id = 2
            and pe.idPieza in ($piezas_id_string)
            GROUP BY pe.idPieza
        ) as sub ON fp.id = sub.idPieza
        
        inner join (
            SELECT fp.id, pe.idEstados, pe.Fecha, pe.Latitud, pe.Longitud
            FROM sispoc5_gestionpostal.flash_piezas as fp
            INNER JOIN sispoc5_Ocasa.Piezas_Estados as pe ON fp.id = pe.idPieza
            INNER JOIN (
                SELECT pe.idPieza, MAX(pe.Fecha) as 'Fecha'
                FROM sispoc5_gestionpostal.flash_piezas as fp
                INNER JOIN sispoc5_Ocasa.Piezas_Estados as pe ON fp.id = pe.idPieza
                WHERE 1
                and fp.id in ($piezas_id_string)
                GROUP BY pe.idPieza
            ) as sub2 ON pe.idPieza = sub2.idPieza and pe.Fecha = sub2.Fecha
            WHERE 1
        ) as sub1 ON fp.id = sub1.id
        
        inner JOIN sispoc5_Ocasa.EstadoEntregaApp as eea ON sub1.idEstados = eea.id
        inner JOIN sispoc5_Ocasa.EstadoEntregaAppGestionPostal as ee on ee.IdEstadoEntregaApp = eea.id
        inner join sispoc5_gestionpostal.flash_piezas_estados_variables as fe on ee.IdEstadoEntregaGestionPostal = fe.id
        left join flash_sucursales as suc on suc.id = fp.sucursal_id

        left join sispoc5_ruteo.piezas as piezas_ruteo ON fp.id = piezas_ruteo.pieza_id
        left join sispoc5_ruteo.asignacion as asig ON piezas_ruteo.id = asig.pieza_id
        
        WHERE fp.tipo_id = 2
        and (sub1.Longitud <> 0 and sub1.Latitud <> 0)
        and (sub1.Longitud LIKE '%.%' and sub1.Latitud LIKE '%.%')

        union 
        
        SELECT 
            fp.id as 'pieza'
            , fp.destinatario
            , fp.domicilio
            , fe.id as 'id_estado'
            , fe.nombre as 'nombre_estado'
            , sub1.Latitud as 'lat'
            , sub1.Longitud as 'lng'
            , asig.orden_recorrido as 'orden'
            , sub1.Fecha as fecha
            
        from (
            select fp.*
            from sispoc5_gestionpostal.flash_piezas as fp
            inner join sispoc5_gestionpostal.flash_comprobantes_ingresos as ci on fp.comprobante_ingreso_id = ci.id
            where fp.tipo_id = 2
            and fp.id in ($piezas_id_string)
        
            and ci.cliente_id = 30
        ) as fp
        
        left join(
            SELECT pe.id, pe.idPieza
            
            FROM sispoc5_Banco.PiezasEstados as pe
            inner join sispoc5_Banco.Estados as e on pe.idEstados = e.Id
            inner join sispoc5_Banco.EstadosBancarios as eb on e.id = eb.Id
            inner join sispoc5_gestionpostal.flash_piezas_estados_variables as fe on eb.Sispoid = fe.id
            WHERE fe.pieza_estado_id = 2
            and pe.idPieza in ($piezas_id_string)
        
            GROUP BY pe.idPieza
        ) as sub ON fp.id = sub.idPieza
        
        inner join (
            SELECT fp.id, pe.idEstados, pe.Fecha, pe.Latitud, pe.Longitud
            FROM sispoc5_gestionpostal.flash_piezas as fp
            INNER JOIN sispoc5_Banco.PiezasEstados as pe ON fp.id = pe.idPieza
            INNER JOIN (
                SELECT pe.idPieza, MAX(pe.Fecha) as 'Fecha'
                FROM sispoc5_gestionpostal.flash_piezas as fp
                INNER JOIN sispoc5_Banco.PiezasEstados as pe ON fp.id = pe.idPieza
                WHERE 1
                and fp.id in ($piezas_id_string)
        
                GROUP BY pe.idPieza
            ) as sub2 ON pe.idPieza = sub2.idPieza and pe.Fecha = sub2.Fecha
            WHERE 1
        
        ) as sub1 ON fp.id = sub1.id
        
        inner join sispoc5_Banco.Estados as e on sub1.idEstados = e.Id
        inner join sispoc5_Banco.EstadosBancarios as eb on e.id = eb.Id
        inner join sispoc5_gestionpostal.flash_piezas_estados_variables as fe on eb.Sispoid = fe.id
        left join flash_sucursales as suc on suc.id = fp.sucursal_id

        left join sispoc5_ruteo.piezas as piezas_ruteo ON fp.id = piezas_ruteo.pieza_id
        left join sispoc5_ruteo.asignacion as asig ON piezas_ruteo.id = asig.pieza_id

        WHERE
        fp.tipo_id = 2
        and (sub1.Longitud <> 0 and sub1.Latitud <> 0)
        and (sub1.Longitud LIKE '%.%' and sub1.Latitud LIKE '%.%')
    "));
    
    //dd($novedades_p);

    $estados_n = DB::connection('Sispo')->select(DB::raw("
        SELECT 
            fp.id as 'pieza'
            , fp.destinatario
            , fp.domicilio
            , fe.id as 'id_estado'
            , fe.nombre as 'nombre_estado'
            , sub1.Latitud as 'lat'
            , sub1.Longitud as 'lng'
            , asig.orden_recorrido as 'orden'
            , sub1.Fecha as fecha
            , count(fe.id) as cantidad

        from (
            select fp.*
            from sispoc5_gestionpostal.flash_piezas as fp
            inner join sispoc5_gestionpostal.flash_comprobantes_ingresos as ci on fp.comprobante_ingreso_id = ci.id
            where fp.tipo_id = 2
            and fp.id in ($piezas_id_string)
            and ci.cliente_id <> 30
        ) as fp
        left join(
            SELECT pe.id, pe.idPieza
            FROM sispoc5_Ocasa.Piezas_Estados as pe
            inner JOIN sispoc5_Ocasa.EstadoEntregaApp as eea ON pe.idEstados = eea.id
            inner JOIN sispoc5_Ocasa.EstadoEntregaAppGestionPostal as ee on ee.IdEstadoEntregaApp = eea.id
            inner join sispoc5_gestionpostal.flash_piezas_estados_variables as fe on ee.IdEstadoEntregaGestionPostal = fe.id
            WHERE fe.pieza_estado_id = 2
            and pe.idPieza in ($piezas_id_string)
            GROUP BY pe.idPieza
        ) as sub ON fp.id = sub.idPieza
        
        inner join (
            SELECT fp.id, pe.idEstados, pe.Fecha, pe.Latitud, pe.Longitud
            FROM sispoc5_gestionpostal.flash_piezas as fp
            INNER JOIN sispoc5_Ocasa.Piezas_Estados as pe ON fp.id = pe.idPieza
            INNER JOIN (
                SELECT pe.idPieza, MAX(pe.Fecha) as 'Fecha'
                FROM sispoc5_gestionpostal.flash_piezas as fp
                INNER JOIN sispoc5_Ocasa.Piezas_Estados as pe ON fp.id = pe.idPieza
                WHERE 1
                and fp.id in ($piezas_id_string)
                GROUP BY pe.idPieza
            ) as sub2 ON pe.idPieza = sub2.idPieza and pe.Fecha = sub2.Fecha
            WHERE 1
        ) as sub1 ON fp.id = sub1.id
        
        inner JOIN sispoc5_Ocasa.EstadoEntregaApp as eea ON sub1.idEstados = eea.id
        inner JOIN sispoc5_Ocasa.EstadoEntregaAppGestionPostal as ee on ee.IdEstadoEntregaApp = eea.id
        inner join sispoc5_gestionpostal.flash_piezas_estados_variables as fe on ee.IdEstadoEntregaGestionPostal = fe.id
        left join flash_sucursales as suc on suc.id = fp.sucursal_id

        left join sispoc5_ruteo.piezas as piezas_ruteo ON fp.id = piezas_ruteo.pieza_id
        left join sispoc5_ruteo.asignacion as asig ON piezas_ruteo.id = asig.pieza_id
        
        WHERE fp.tipo_id = 2
        and (sub1.Longitud <> 0 and sub1.Latitud <> 0)
        and (sub1.Longitud LIKE '%.%' and sub1.Latitud LIKE '%.%')

        group by fe.id

        union 
        
        SELECT 
            fp.id as 'pieza'
            , fp.destinatario
            , fp.domicilio
            , fe.id as 'id_estado'
            , fe.nombre as 'nombre_estado'
            , sub1.Latitud as 'lat'
            , sub1.Longitud as 'lng'
            , asig.orden_recorrido as 'orden'
            , sub1.Fecha as fecha
            , count(fe.id) as cantidad
            
        from (
            select fp.*
            from sispoc5_gestionpostal.flash_piezas as fp
            inner join sispoc5_gestionpostal.flash_comprobantes_ingresos as ci on fp.comprobante_ingreso_id = ci.id
            where fp.tipo_id = 2
            and fp.id in ($piezas_id_string)
        
            and ci.cliente_id = 30
        ) as fp
        
        left join(
            SELECT pe.id, pe.idPieza
            
            FROM sispoc5_Banco.PiezasEstados as pe
            inner join sispoc5_Banco.Estados as e on pe.idEstados = e.Id
            inner join sispoc5_Banco.EstadosBancarios as eb on e.id = eb.Id
            inner join sispoc5_gestionpostal.flash_piezas_estados_variables as fe on eb.Sispoid = fe.id
            WHERE fe.pieza_estado_id = 2
            and pe.idPieza in ($piezas_id_string)
        
            GROUP BY pe.idPieza
        ) as sub ON fp.id = sub.idPieza
        
        inner join (
            SELECT fp.id, pe.idEstados, pe.Fecha, pe.Latitud, pe.Longitud
            FROM sispoc5_gestionpostal.flash_piezas as fp
            INNER JOIN sispoc5_Banco.PiezasEstados as pe ON fp.id = pe.idPieza
            INNER JOIN (
                SELECT pe.idPieza, MAX(pe.Fecha) as 'Fecha'
                FROM sispoc5_gestionpostal.flash_piezas as fp
                INNER JOIN sispoc5_Banco.PiezasEstados as pe ON fp.id = pe.idPieza
                WHERE 1
                and fp.id in ($piezas_id_string)
        
                GROUP BY pe.idPieza
            ) as sub2 ON pe.idPieza = sub2.idPieza and pe.Fecha = sub2.Fecha
            WHERE 1
        
        ) as sub1 ON fp.id = sub1.id
        
        inner join sispoc5_Banco.Estados as e on sub1.idEstados = e.Id
        inner join sispoc5_Banco.EstadosBancarios as eb on e.id = eb.Id
        inner join sispoc5_gestionpostal.flash_piezas_estados_variables as fe on eb.Sispoid = fe.id
        left join flash_sucursales as suc on suc.id = fp.sucursal_id

        left join sispoc5_ruteo.piezas as piezas_ruteo ON fp.id = piezas_ruteo.pieza_id
        left join sispoc5_ruteo.asignacion as asig ON piezas_ruteo.id = asig.pieza_id

        WHERE
        fp.tipo_id = 2
        and (sub1.Longitud <> 0 and sub1.Latitud <> 0)
        and (sub1.Longitud LIKE '%.%' and sub1.Latitud LIKE '%.%')

        group by fe.id
    "));


    $recorrido_novedades3 = $novedades_p;

    return view('Seguimiento/control_comparativa', ['recorrido_estimado1' => $recorrido_estimado1, 'recorrido_real2' => $recorrido_real2, 'recorrido_novedades3' => $recorrido_novedades3, 'estados' => $estados_n, 'distancia_real' => $format_number, 'tiempo_real' => $tiempo_real]);
}