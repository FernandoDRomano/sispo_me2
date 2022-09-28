<?php 

class Pieza_md_n extends CI_Model {
    
    function getMetodosTarifariosXCliente($cliente, $origen, $destino){
        /*
        SELECT 
        T.id as tarifario, 
        C.nombre as cliente, 
        E.nombre as esquema, 
        IF(COUNT(K.id) > 0, 'TRUE', 'FALSE') as kilos, 
        IF(COUNT(B.id) > 0, 'TRUE', 'FALSE') as bultos, 
        IF(COUNT(P.id) > 0, 'TRUE', 'FALSE') as palets, 
        IF(COUNT(M.id) > 0, 'TRUE', 'FALSE') as metrosCubicos, 
        IF(COUNT(W.id) > 0, 'TRUE', 'FALSE') as warehouse, 
        IF(COUNT(G.id) > 0, 'TRUE', 'FALSE') as gestionFlota, 
        IF(COUNT(COB.id) > 0, 'TRUE', 'FALSE') as cobranza, 
        IF(COUNT(V.id) > 0, 'TRUE', 'FALSE') as valorDeclarado, 
        IF(COUNT(PA.id) > 0, 'TRUE', 'FALSE') as pesoAforado,
        IF(COUNT(D.id) > 0, 'TRUE', 'FALSE')
        FROM flash_tarifario as T
        INNER JOIN flash_clientes as C ON C.id = T.cliente_id
        INNER JOIN flash_esquema as E ON E.id = T.esquema_id
        INNER JOIN sub_esquema as S ON S.esquema_id = E.id
        LEFT JOIN flash_sub_esquema_kg as K ON K.sub_esquema_id = S.id
        LEFT JOIN flash_sub_esquema_bultos as B ON B.sub_esquema_id = S.id
        LEFT JOIN flash_sub_esquema_palets as P ON P.sub_esquema_id = S.id
        LEFT JOIN flash_sub_esquema_metros_cubicos as M ON M.sub_esquema_id = S.id
        LEFT JOIN flash_sub_esquema_warehouse as W ON W.sub_esquema_id = S.id
        LEFT JOIN flash_sub_esquema_gestion_flota as G ON G.sub_esquema_id = S.id
        LEFT JOIN flash_sub_esquema_valor_declarado as V ON V.sub_esquema_id = S.id
        LEFT JOIN flash_sub_esquema_cobranza as COB ON COB.sub_esquema_id = S.id
        LEFT JOIN flash_sub_esquema_peso_aforado as PA ON PA.sub_esquema_id = S.id
        WHERE C.id = '1' AND 
        S.zonaB IN (
        SELECT 
            Z.id idZona
        FROM ubicacion_localidades L
        INNER JOIN flash_tarifario_zona_detalle ZD ON ZD.localidad_id = L.id
        INNER JOIN flash_tarifario_zonas Z ON Z.id = ZD.zona_id
        WHERE L.id = 3935
        )

        ========================================================
        ========== ALTERNATIVA CON LAS LOCALIDADES =============
        ========================================================
        PARA PODER IMPLEMENTAR ESTA SOLUCION ES NECESARIO QUE 
        NO SE REPITAN LAS LOCALIDADES ENTRE LAS DIFERENTES
        ZONAS DE LOS SUB ESQUEMAS, DENTRO DE UN ESQUEMA PRINCIPAL

        SELECT 
            T.id as tarifario, 
            C.nombre as cliente, 
            E.nombre as esquema, 
            IF(COUNT(K.id) > 0, 'TRUE', 'FALSE') as kilos, 
            IF(COUNT(B.id) > 0, 'TRUE', 'FALSE') as bultos, 
            IF(COUNT(P.id) > 0, 'TRUE', 'FALSE') as palets, 
            IF(COUNT(M.id) > 0, 'TRUE', 'FALSE') as metrosCubicos, 
            IF(COUNT(W.id) > 0, 'TRUE', 'FALSE') as warehouse, 
            IF(COUNT(G.id) > 0, 'TRUE', 'FALSE') as gestionFlota, 
            IF(COUNT(COB.id) > 0, 'TRUE', 'FALSE') as cobranza, 
            IF(COUNT(V.id) > 0, 'TRUE', 'FALSE') as valorDeclarado, 
            IF(COUNT(PA.id) > 0, 'TRUE', 'FALSE') as pesoAforado,
            IF(COUNT(D.id) > 0, 'TRUE', 'FALSE') as distancia
            FROM flash_tarifario as T
            INNER JOIN flash_clientes as C ON C.id = T.cliente_id
            INNER JOIN flash_esquema as E ON E.id = T.esquema_id
            INNER JOIN sub_esquema as S ON S.esquema_id = E.id
            LEFT JOIN flash_sub_esquema_kg as K ON K.sub_esquema_id = S.id
            LEFT JOIN flash_sub_esquema_bultos as B ON B.sub_esquema_id = S.id
            LEFT JOIN flash_sub_esquema_palets as P ON P.sub_esquema_id = S.id
            LEFT JOIN flash_sub_esquema_metros_cubicos as M ON M.sub_esquema_id = S.id
            LEFT JOIN flash_sub_esquema_warehouse as W ON W.sub_esquema_id = S.id
            LEFT JOIN flash_sub_esquema_gestion_flota as G ON G.sub_esquema_id = S.id
            LEFT JOIN flash_sub_esquema_valor_declarado as V ON V.sub_esquema_id = S.id
            LEFT JOIN flash_sub_esquema_cobranza as COB ON COB.sub_esquema_id = S.id
            LEFT JOIN flash_sub_esquema_peso_aforado as PA ON PA.sub_esquema_id = S.id
            LEFT JOIN flash_sub_esquema_distancia as D ON D.sub_esquema_id = S.id
            WHERE C.id = $cliente 
            AND 
            S.zonaA IN (
                SELECT 
                    Z.id idZona
                FROM ubicacion_localidades L
                INNER JOIN flash_tarifario_zona_detalle ZD ON ZD.localidad_id = L.id
                INNER JOIN flash_tarifario_zonas Z ON Z.id = ZD.zona_id
                WHERE L.id = $origen
            )
            AND
                S.zonaB IN (
                SELECT 
                    Z.id idZona
                FROM ubicacion_localidades L
                INNER JOIN flash_tarifario_zona_detalle ZD ON ZD.localidad_id = L.id
                INNER JOIN flash_tarifario_zonas Z ON Z.id = ZD.zona_id
                WHERE L.id = $destino
            )
     

        */
        if(!empty($cliente) && !empty($origen) && !empty($destino)){
            $query = "
            SELECT 
            T.id as tarifario, 
            C.nombre as cliente, 
            E.nombre as esquema, 
            IF(COUNT(K.id) > 0, 'TRUE', 'FALSE') as kilos, 
            IF(COUNT(B.id) > 0, 'TRUE', 'FALSE') as bultos, 
            IF(COUNT(P.id) > 0, 'TRUE', 'FALSE') as palets, 
            IF(COUNT(M.id) > 0, 'TRUE', 'FALSE') as metrosCubicos, 
            IF(COUNT(W.id) > 0, 'TRUE', 'FALSE') as warehouse, 
            IF(COUNT(G.id) > 0, 'TRUE', 'FALSE') as gestionFlota, 
            IF(COUNT(COB.id) > 0, 'TRUE', 'FALSE') as cobranza, 
            IF(COUNT(V.id) > 0, 'TRUE', 'FALSE') as valorDeclarado, 
            IF(COUNT(PA.id) > 0, 'TRUE', 'FALSE') as pesoAforado,
            IF(COUNT(D.id) > 0, 'TRUE', 'FALSE') as distancia
            FROM flash_tarifario as T
            INNER JOIN flash_clientes as C ON C.id = T.cliente_id
            INNER JOIN flash_esquema as E ON E.id = T.esquema_id
            INNER JOIN sub_esquema as S ON S.esquema_id = E.id
            LEFT JOIN flash_sub_esquema_kg as K ON K.sub_esquema_id = S.id
            LEFT JOIN flash_sub_esquema_bultos as B ON B.sub_esquema_id = S.id
            LEFT JOIN flash_sub_esquema_palets as P ON P.sub_esquema_id = S.id
            LEFT JOIN flash_sub_esquema_metros_cubicos as M ON M.sub_esquema_id = S.id
            LEFT JOIN flash_sub_esquema_warehouse as W ON W.sub_esquema_id = S.id
            LEFT JOIN flash_sub_esquema_gestion_flota as G ON G.sub_esquema_id = S.id
            LEFT JOIN flash_sub_esquema_valor_declarado as V ON V.sub_esquema_id = S.id
            LEFT JOIN flash_sub_esquema_cobranza as COB ON COB.sub_esquema_id = S.id
            LEFT JOIN flash_sub_esquema_peso_aforado as PA ON PA.sub_esquema_id = S.id
            LEFT JOIN flash_sub_esquema_distancia as D ON D.sub_esquema_id = S.id
            WHERE C.id = $cliente 
            AND 
            S.zonaA = $origen
            AND 
            S.zonaB = $destino";

            $resultado = $this->db->query($query);

            //return $this->db->last_query();
            return $resultado->row();
        }
    }

    function getLocalidadesPorProvincia($provincia, $zona){
        /*
        SELECT L.id, L.nombre
        FROM flash_tarifario_zona_detalle ZD
        INNER JOIN ubicacion_localidades L ON L.id = ZD.localidad_id
        INNER JOIN ubicacion_departamentos D ON D.id = L.departamento_id
        INNER JOIN ubicacion_provincias P ON P.id = D.provincia_id
        WHERE ZD.zona_id = 35 AND P.id = 23
         */

        if(!empty($provincia) && !empty($zona)){
            /*
            $query = "SELECT L.* 
                    FROM ubicacion_localidades L
                    INNER JOIN ubicacion_departamentos D ON D.id = L.departamento_id
                    INNER JOIN ubicacion_provincias P ON P.id = D.provincia_id
                    WHERE P.id = $provincia ";
            */
            $query = "SELECT L.id, L.nombre
            FROM flash_tarifario_zona_detalle ZD
            INNER JOIN ubicacion_localidades L ON L.id = ZD.localidad_id
            INNER JOIN ubicacion_departamentos D ON D.id = L.departamento_id
            INNER JOIN ubicacion_provincias P ON P.id = D.provincia_id
            WHERE ZD.zona_id = $zona AND P.id = $provincia";

            $resultado = $this->db->query($query);

            //return $this->db->last_query();
            return $resultado->result();

        }else{
            return "Faltan datos para realizar la consulta";
        }
    }

    function getClientes($pais){
        /*
         SELECT C.id, CONCAT(C.nombre," (",C.nombre_fantasia,")") as name FROM flash_clientes C 
        WHERE 
            C.pais = 1 and 
            C.cliente_estado_id = 1
         */

        if(!empty($pais)){
            $query = "SELECT C.id, CONCAT(C.nombre,' (',C.nombre_fantasia,')') as name FROM flash_clientes C 
                      WHERE 
                        C.pais = $pais 
                        and 
                        C.cliente_estado_id = 1";

            $resultado = $this->db->query($query);

            //return $this->db->last_query();
            return $resultado->result();

        }else{
            return "Faltan datos para realizar la consulta";
        }
    }

    function getGestionFlotaXCliente($cliente, $origen, $destino){
        if(!empty($cliente) && !empty($origen) && !empty($destino)){
            $query = $this->db
                    ->select('
                        T.id as tarifario, 
                        C.nombre as cliente, 
                        E.nombre as esquema,
                        G.tipo_vehiculo tipoVehiculo, 
                        G.tipo_hora horaVehiculo
                    ')
                    ->join('flash_clientes as C', 'C.id = T.cliente_id', 'inner')
                    ->join('flash_esquema as E', 'E.id = T.esquema_id', 'inner')
                    ->join('sub_esquema as S', 'S.esquema_id = E.id', 'inner')
                    ->join('flash_sub_esquema_gestion_flota as G', 'G.sub_esquema_id = S.id', 'left')
                    ->where('C.id = ', $cliente)
                    ->where('S.zonaA = ', $origen)
                    ->where('S.zonaB = ', $destino)
                    ->get('flash_tarifario as T');

            //return $this->db->last_query();
            return $query->result();
        }else{
            return "Faltan campos para realizar la consulta";
        }
    }

    function validarLocalidadEsteContenidaEnZonaDelEsquemaTarifario($cliente, $localidad, $opcion){
       
        if(!empty($cliente) && !empty($localidad) && !empty($opcion)){
            /*
            $query = "SELECT IF(COUNT(*) > 0, 'TRUE', 'FALSE') AS resultado 
                        FROM 
                        flash_tarifario T
                
                        INNER JOIN flash_esquema E ON E.id = T.esquema_id
                        INNER JOIN sub_esquema S ON S.esquema_id = E.id
                        INNER JOIN flash_clientes C ON C.id = T.cliente_id
                
                        WHERE
                        C.id = $cliente
                        AND 
                        S.zonaB IN (
                            SELECT 
                                Z.id idZona
                            FROM ubicacion_localidades L
                            INNER JOIN flash_tarifario_zona_detalle ZD ON ZD.localidad_id = L.id
                            INNER JOIN flash_tarifario_zonas Z ON Z.id = ZD.zona_id
                            WHERE L.id = $localidad
                        );";
            */

            $query = "SELECT IF(COUNT(*) > 0, 'TRUE', 'FALSE') AS resultado 
                        FROM 
                        flash_tarifario T
                
                        INNER JOIN flash_esquema E ON E.id = T.esquema_id
                        INNER JOIN sub_esquema S ON S.esquema_id = E.id
                        INNER JOIN flash_clientes C ON C.id = T.cliente_id
                
                        WHERE
                        C.id = $cliente ";

            if($opcion == 1){
                $query .= "AND 
                S.zonaA IN (
                    SELECT 
                        Z.id idZona
                    FROM ubicacion_localidades L
                    INNER JOIN flash_tarifario_zona_detalle ZD ON ZD.localidad_id = L.id
                    INNER JOIN flash_tarifario_zonas Z ON Z.id = ZD.zona_id
                    WHERE L.id = $localidad
                );";
            }

            if($opcion == 2){
                $query .= "AND 
                S.zonaB IN (
                    SELECT 
                        Z.id idZona
                    FROM ubicacion_localidades L
                    INNER JOIN flash_tarifario_zona_detalle ZD ON ZD.localidad_id = L.id
                    INNER JOIN flash_tarifario_zonas Z ON Z.id = ZD.zona_id
                    WHERE L.id = $localidad
                );";
            }

            $resultado = $this->db->query($query);

            //return $this->db->last_query();
            return $resultado->row();
        }

    }

    function validarNombreLocalidadEsteContenidaEnZonaDelEsquemaTarifario($cliente, $localidad){
        if(!empty($cliente) && !empty($localidad)){

            $query = "SELECT IF(COUNT(*) > 0, 'TRUE', 'FALSE') AS resultado 
            FROM flash_tarifario T
            INNER JOIN flash_esquema E ON E.id = T.esquema_id
            INNER JOIN sub_esquema S ON S.esquema_id = E.id
            INNER JOIN flash_clientes C ON C.id = T.cliente_id
            WHERE
            C.id = $cliente
            AND 
            S.zonaA IN (
                SELECT Z.id idZona
                FROM ubicacion_localidades L
                INNER JOIN flash_tarifario_zona_detalle ZD ON ZD.localidad_id = L.id
                INNER JOIN flash_tarifario_zonas Z ON Z.id = ZD.zona_id
                WHERE L.nombre LIKE '%$localidad%'
                GROUP BY Z.id
            )";
    
            $resultado = $this->db->query($query);
    
            //return $this->db->last_query();
            return $resultado->row();
        }
    }

    function validarZonaDelEsquemaTarifario($cliente, $zona, $opcion){
        /*
        
        SELECT IF(COUNT(*) > 0, 'TRUE', 'FALSE') AS resultado 
        FROM 
        flash_tarifario T

        INNER JOIN flash_esquema E ON E.id = T.esquema_id
        INNER JOIN sub_esquema S ON S.esquema_id = E.id
        INNER JOIN flash_clientes C ON C.id = T.cliente_id

        WHERE
        C.id = 1
        AND 
        S.zonaB = 2
        
        */

        if(!empty($cliente) && !empty($zona) && !empty($opcion)){

            $query = "SELECT IF(COUNT(*) > 0, 'TRUE', 'FALSE') AS resultado 
                        FROM 
                        flash_tarifario T
                
                        INNER JOIN flash_esquema E ON E.id = T.esquema_id
                        INNER JOIN sub_esquema S ON S.esquema_id = E.id
                        INNER JOIN flash_clientes C ON C.id = T.cliente_id
                
                        WHERE
                        C.id = $cliente ";

            if($opcion == 1){
                $query .= " AND 
                S.zonaA = $zona";
            }

            if($opcion == 2){
                $query .= " AND 
                S.zonaB = $zona";
            }

            $resultado = $this->db->query($query);

            //return $this->db->last_query();
            return $resultado->row();
        }

    }

    function getPaisPorZona($zona){
        /*
        
        SELECT PA.id, PA.nombre
        FROM flash_tarifario_zona_detalle ZD
        INNER JOIN ubicacion_localidades L ON L.id = ZD.localidad_id
        INNER JOIN ubicacion_departamentos D ON D.id = L.departamento_id
        INNER JOIN ubicacion_provincias P ON P.id = D.provincia_id
        INNER JOIN flash_paises PA ON PA.id = P.pais_id 
        WHERE ZD.zona_id = 31
        GROUP BY PA.nombre
        
        */

        if(!empty($zona)){

            $query = "SELECT PA.id, PA.nombre
            FROM flash_tarifario_zona_detalle ZD
            INNER JOIN ubicacion_localidades L ON L.id = ZD.localidad_id
            INNER JOIN ubicacion_departamentos D ON D.id = L.departamento_id
            INNER JOIN ubicacion_provincias P ON P.id = D.provincia_id
            INNER JOIN flash_paises PA ON PA.id = P.pais_id 
            WHERE ZD.zona_id = $zona
            GROUP BY PA.nombre";

            $resultado = $this->db->query($query);

            //return $this->db->last_query();
            return $resultado->result();
        }

    }

    function getProvinciasPorZona($zona){
        /*
        
        SELECT P.id, P.nombre
        FROM flash_tarifario_zona_detalle ZD
        INNER JOIN ubicacion_localidades L ON L.id = ZD.localidad_id
        INNER JOIN ubicacion_departamentos D ON D.id = L.departamento_id
        INNER JOIN ubicacion_provincias P ON P.id = D.provincia_id
        WHERE ZD.zona_id = 31
        GROUP BY P.nombre
        
        */

        if(!empty($zona)){

            $query = "SELECT P.id, P.nombre
            FROM flash_tarifario_zona_detalle ZD
            INNER JOIN ubicacion_localidades L ON L.id = ZD.localidad_id
            INNER JOIN ubicacion_departamentos D ON D.id = L.departamento_id
            INNER JOIN ubicacion_provincias P ON P.id = D.provincia_id
            WHERE ZD.zona_id = $zona
            GROUP BY P.nombre";

            $resultado = $this->db->query($query);

            //return $this->db->last_query();
            return $resultado->result();
        }

    }

    function getLocalidadesPorZona($zona){
        /*
        
        SELECT L.id, L.nombre
        FROM flash_tarifario_zona_detalle ZD
        INNER JOIN ubicacion_localidades L ON L.id = ZD.localidad_id
        WHERE ZD.zona_id = 35
        
        */

        if(!empty($zona)){
            
            $query = "SELECT L.id, L.nombre
            FROM flash_tarifario_zona_detalle ZD
            INNER JOIN ubicacion_localidades L ON L.id = ZD.localidad_id
            WHERE ZD.zona_id = $zona";
            
            $resultado = $this->db->query($query);

            //return $this->db->last_query();
            return $resultado->result();
        }

    }

    function validarWarehouseEnReglaEsquema($cliente, $zonaA, $zonaB, $warehouse){
        /*

        SELECT COUNT(*) cantidad, W.* 
        FROM flash_tarifario T
        INNER JOIN flash_clientes C ON T.cliente_id = C.id
        INNER JOIN flash_esquema E ON T.esquema_id = E.id
        INNER JOIN sub_esquema S ON S.esquema_id = E.id
        INNER JOIN flash_sub_esquema_warehouse W ON S.id = W.sub_esquema_id
        WHERE 
        C.id = 6
        AND
        S.zonaA = 30
        AND
        S.zonaB = 31
        AND W.desde <= 12 AND W.hasta >= 12

        */

        if(!empty($cliente) && !empty($zonaA) && !empty($zonaB) && !empty($warehouse)){

            $query = "SELECT COUNT(*) cantidad, W.* 
                    FROM flash_tarifario T
                    INNER JOIN flash_clientes C ON T.cliente_id = C.id
                    INNER JOIN flash_esquema E ON T.esquema_id = E.id
                    INNER JOIN sub_esquema S ON S.esquema_id = E.id
                    INNER JOIN flash_sub_esquema_warehouse W ON S.id = W.sub_esquema_id
                    WHERE 
                    C.id = $cliente
                    AND
                    S.zonaA = $zonaA
                    AND
                    S.zonaB = $zonaB
                    AND W.desde <= $warehouse AND W.hasta >= $warehouse";
            
            $resultado = $this->db->query($query);

            //return $this->db->last_query();
            return $resultado->row();
        }
    }

    function validarGestionFlotaEnReglaEsquema($cliente, $zonaA, $zonaB, $tipo_vehiculo, $tipo_hora){
        /*
            SELECT COUNT(*) cantidad, G.* 
            FROM flash_tarifario T
            INNER JOIN flash_clientes C ON T.cliente_id = C.id
            INNER JOIN flash_esquema E ON T.esquema_id = E.id
            INNER JOIN sub_esquema S ON S.esquema_id = E.id
            INNER JOIN flash_sub_esquema_gestion_flota G ON S.id = G.sub_esquema_id
            WHERE 
            C.id = 6
            AND
            S.zonaA = 30
            AND
            S.zonaB = 31
            AND G.tipo_vehiculo = 5 AND G.tipo_hora = 2
        */

        if(!empty($cliente) && !empty($zonaA) && !empty($zonaB) && !empty($tipo_vehiculo) && !empty($tipo_hora)){

            $query = "SELECT COUNT(*) cantidad, G.* 
                    FROM flash_tarifario T
                    INNER JOIN flash_clientes C ON T.cliente_id = C.id
                    INNER JOIN flash_esquema E ON T.esquema_id = E.id
                    INNER JOIN sub_esquema S ON S.esquema_id = E.id
                    INNER JOIN flash_sub_esquema_gestion_flota G ON S.id = G.sub_esquema_id
                    WHERE 
                    C.id = $cliente
                    AND
                    S.zonaA = $zonaA
                    AND
                    S.zonaB = $zonaB
                    AND G.tipo_vehiculo = $tipo_vehiculo AND G.tipo_hora = $tipo_hora";
            
            $resultado = $this->db->query($query);

            //return $this->db->last_query();
            return $resultado->row();
        }
    }   

    function validarKiloEnReglaEsquema($cliente, $zonaA, $zonaB, $peso){
        /*

        SELECT COUNT(*) cantidad, K.* 
        FROM flash_tarifario T
        INNER JOIN flash_clientes C ON T.cliente_id = C.id
        INNER JOIN flash_esquema E ON T.esquema_id = E.id
        INNER JOIN sub_esquema S ON S.esquema_id = E.id
        INNER JOIN flash_sub_esquema_kg K ON S.id = K.sub_esquema_id
        WHERE 
        C.id = 1
        AND
        S.zonaA = 22
        AND
        S.zonaB = 24
        AND K.desde <= 12 AND K.hasta >= 12

        */

        if(!empty($cliente) && !empty($zonaA) && !empty($zonaB) && !empty($peso)){

            $query = "SELECT COUNT(*) cantidad, K.* 
                    FROM flash_tarifario T
                    INNER JOIN flash_clientes C ON T.cliente_id = C.id
                    INNER JOIN flash_esquema E ON T.esquema_id = E.id
                    INNER JOIN sub_esquema S ON S.esquema_id = E.id
                    INNER JOIN flash_sub_esquema_kg K ON S.id = K.sub_esquema_id
                    WHERE 
                    C.id = $cliente
                    AND
                    S.zonaA = $zonaA
                    AND
                    S.zonaB = $zonaB
                    AND K.desde <= $peso AND K.hasta >= $peso";
            
            $resultado = $this->db->query($query);

            //return $this->db->last_query();
            return $resultado->row();
        }
    }

    function validarBultoEnReglaEsquema($cliente, $zonaA, $zonaB, $bulto){
        /*

        SELECT COUNT(*) cantidad, B.* 
        FROM flash_tarifario T
        INNER JOIN flash_clientes C ON T.cliente_id = C.id
        INNER JOIN flash_esquema E ON T.esquema_id = E.id
        INNER JOIN sub_esquema S ON S.esquema_id = E.id
        INNER JOIN flash_sub_esquema_bultos B ON S.id = B.sub_esquema_id
        WHERE 
        C.id = 1
        AND
        S.zonaA = 22
        AND
        S.zonaB = 27
        AND B.desde <= 9 AND B.hasta >= 9

        */

        if(!empty($cliente) && !empty($zonaA) && !empty($zonaB) && !empty($bulto)){

            $query = "SELECT COUNT(*) cantidad, B.* 
                    FROM flash_tarifario T
                    INNER JOIN flash_clientes C ON T.cliente_id = C.id
                    INNER JOIN flash_esquema E ON T.esquema_id = E.id
                    INNER JOIN sub_esquema S ON S.esquema_id = E.id
                    INNER JOIN flash_sub_esquema_bultos B ON S.id = B.sub_esquema_id
                    WHERE 
                    C.id = $cliente
                    AND
                    S.zonaA = $zonaA
                    AND
                    S.zonaB = $zonaB
                    AND B.desde <= $bulto AND B.hasta >= $bulto
                    ";
            
            $resultado = $this->db->query($query);

            //return $this->db->last_query();
            return $resultado->row();
        }
    }

    function validarPaletEnReglaEsquema($cliente, $zonaA, $zonaB, $palet){
        /*
            SELECT COUNT(*) cantidad, P.* 
            FROM flash_tarifario T
            INNER JOIN flash_clientes C ON T.cliente_id = C.id
            INNER JOIN flash_esquema E ON T.esquema_id = E.id
            INNER JOIN sub_esquema S ON S.esquema_id = E.id
            INNER JOIN flash_sub_esquema_palets P ON S.id = P.sub_esquema_id
            WHERE 
            C.id = 1
            AND
            S.zonaA = 22
            AND
            S.zonaB = 27
            AND P.desde <= 15 AND P.hasta >= 15
        */

        if(!empty($cliente) && !empty($zonaA) && !empty($zonaB) && !empty($palet)){

            $query = "SELECT COUNT(*) cantidad, P.* 
            FROM flash_tarifario T
            INNER JOIN flash_clientes C ON T.cliente_id = C.id
            INNER JOIN flash_esquema E ON T.esquema_id = E.id
            INNER JOIN sub_esquema S ON S.esquema_id = E.id
            INNER JOIN flash_sub_esquema_palets P ON S.id = P.sub_esquema_id
            WHERE 
            C.id = $cliente
            AND
            S.zonaA = $zonaA
            AND
            S.zonaB = $zonaB
            AND P.desde <= $palet AND P.hasta >= $palet
            ";
            
            $resultado = $this->db->query($query);

            //return $this->db->last_query();
            return $resultado->row();
        }
    }

    function validarMetroCubicoEnReglaEsquema($cliente, $zonaA, $zonaB, $metroCubico){
        /*
            SELECT COUNT(*) cantidad, M.* 
            FROM flash_tarifario T
            INNER JOIN flash_clientes C ON T.cliente_id = C.id
            INNER JOIN flash_esquema E ON T.esquema_id = E.id
            INNER JOIN sub_esquema S ON S.esquema_id = E.id
            INNER JOIN flash_sub_esquema_metros_cubicos M ON S.id = M.sub_esquema_id
            WHERE 
            C.id = 1
            AND
            S.zonaA = 22
            AND
            S.zonaB = 27
            AND M.desde <= 3 AND M.hasta >= 3
        */

        if(!empty($cliente) && !empty($zonaA) && !empty($zonaB) && !empty($metroCubico)){

            $query = "SELECT COUNT(*) cantidad, M.* 
            FROM flash_tarifario T
            INNER JOIN flash_clientes C ON T.cliente_id = C.id
            INNER JOIN flash_esquema E ON T.esquema_id = E.id
            INNER JOIN sub_esquema S ON S.esquema_id = E.id
            INNER JOIN flash_sub_esquema_metros_cubicos M ON S.id = M.sub_esquema_id
            WHERE 
            C.id = $cliente
            AND
            S.zonaA = $zonaA
            AND
            S.zonaB = $zonaB
            AND M.desde <= $metroCubico AND M.hasta >= $metroCubico";
            
            $resultado = $this->db->query($query);

            //return $this->db->last_query();
            return $resultado->row();
        }
    }

    /**
     * PIEZAS MASIVAS
     */
    function getPaisPorNombre($pais){
        if(!empty($pais)){
    
            $query = "SELECT * FROM flash_paises P WHERE P.nombre LIKE '%$pais%' LIMIT 1";

            $resultado = $this->db->query($query);

            //return $this->db->last_query();
            return $resultado->row();
        }
    }

    function getProvinciaPorNombre($provincia, $pais){
        if(!empty($provincia) && !empty($pais)){
    
            $query = "SELECT * FROM ubicacion_provincias PR 
            WHERE PR.nombre LIKE '%$provincia%' AND PR.pais_id = $pais
            LIMIT 1";

            $resultado = $this->db->query($query);

            //return $this->db->last_query();
            return $resultado->row();
        }
    }

    function getLocalidadPorNombre($localidad, $provincia){
        if(!empty($localidad) && !empty($provincia)){
    
            $query = "SELECT L.* FROM ubicacion_localidades L 
            INNER JOIN ubicacion_departamentos D ON D.id = L.departamento_id
            INNER JOIN ubicacion_provincias P ON P.id = D.provincia_id
            WHERE L.nombre LIKE '%$localidad%' AND P.id = $provincia LIMIT 1";

            $resultado = $this->db->query($query);

            //return $this->db->last_query();
            return $resultado->row();
        }
    }

    function getLocalidadPorNombreYZona($localidad, $zona){
        if(!empty($localidad) && !empty($zona)){
            $query = "SELECT L.*
                    FROM ubicacion_localidades L
                    INNER JOIN flash_tarifario_zona_detalle ZD ON ZD.localidad_id = L.id
                    INNER JOIN flash_tarifario_zonas Z ON Z.id = ZD.zona_id
                    WHERE L.nombre LIKE '%bella vista%' AND Z.id = 30
                    LIMIT 1";

            $resultado = $this->db->query($query);

            //return $this->db->last_query();
            return $resultado->row();
        }
    }

}