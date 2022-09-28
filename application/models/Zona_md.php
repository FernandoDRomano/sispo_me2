<?php 

class Zona_md extends CI_Model {

    function getZonas($buscar = null){

        if(is_null($buscar) || $buscar == ''){
            $query = $this->db
                        ->select('z.id, z.nombre, z.descripcion')
                        ->get('flash_tarifario_zonas z');
            
            return $query->result();
        }

        $query = $this->db
                    ->select('z.id, z.nombre, z.descripcion')
                    ->like('z.nombre', $buscar)
                    ->or_like('z.descripcion', $buscar)
                    ->get('flash_tarifario_zonas z');
            
        return $query->result();
    }
    
    function getZona($id){
        if(!empty($id)){
            $query = $this->db
                    ->select('z.id, z.nombre, z.descripcion')
                    ->where('z.id = ', $id)
                    ->get('flash_tarifario_zonas z');

            return $query->row();
        }
    }

    function insertDetalleZonaProvinciaSinLocalidad($provincia_id, $zona_id){
        
        $data = array(
            "provincia_id" => $provincia_id,
            "localidad_id" => null,
            "zona_id" => $zona_id
        );

        $this->db->insert('flash_tarifario_zona_detalle', $data);
    }

    function insertDetalleZonaProvinciaConTodasLasLocalidades($provincia_id, $zona_id){
        //TRAIGO TODAS LAS LOCALIDADES DE ESA PROVINCIA
        $query = $this->db
                    ->select('L.id as id')
                    ->join('ubicacion_departamentos AS D', 'D.id = L.departamento_id', 'inner')
                    ->join('ubicacion_provincias AS P', 'P.id = D.provincia_id', 'inner')
                    ->where('P.id = ' . $provincia_id)
                    ->get('ubicacion_localidades as L');

        $localidades = $query->result();

        $masivo = array();

        foreach($localidades as $loc){
            $data = array ( 
                "provincia_id" => $provincia_id,
                "localidad_id" => $loc->id,
                "zona_id" => $zona_id
            );

            array_push($masivo, $data);
        }

        //INSERTAR BATCH
        $this->db->insert_batch('flash_tarifario_zona_detalle', $masivo);

    }

    function insertDetalleZonaConLocalidades($localidades, $zona_id){

        $tuplas = array();

        foreach($localidades as $loc){
            $row = explode(',', $loc);
            
            $data = array(
                "provincia_id" => $row[1],
                "localidad_id" => $row[0],
                "zona_id" => $zona_id
            );

            array_push($tuplas, $data);
        }
    
        //INSERTAR BATCH
        $this->db->insert_batch('flash_tarifario_zona_detalle', $tuplas);
    }

    function getDepartamentosPorPais($id){
        $query = $this->db
                        ->select('*')
                        ->where('p.pais_id = ' .$id)
                        ->get('ubicacion_provincias p');
            
        return $query->result();
    }

    function getDetalleZona($id){
        /*
            SELECT 
                D.zona_id AS zonaId, P.id AS idProvincia, P.nombre AS provincia, L.id AS idLocalidad, L.nombre AS localidad
                
            FROM flash_tarifario_zona_detalle D

            INNER JOIN ubicacion_provincias P ON P.id = D.provincia_id
            INNER JOIN ubicacion_localidades L ON L.id = D.localidad_id 

            WHERE D.zona_id = 3;

        */

        $query = $this->db
                        ->select('D.id AS id, D.zona_id AS zonaId, P.id AS idProvincia, P.nombre AS provincia, L.id AS idLocalidad, L.nombre AS localidad')
                        ->join('ubicacion_provincias P', 'P.id = D.provincia_id', 'inner')
                        ->join('ubicacion_localidades L', 'L.id = D.localidad_id', 'inner')
                        ->where('D.zona_id = ' . $id )
                        ->get('flash_tarifario_zona_detalle D');

        return $query->result();
    }

    function getPaisZona($id){
        /*
        SELECT PA.id AS id, PA.nombre as pais
                
        FROM flash_tarifario_zona_detalle D

        INNER JOIN ubicacion_provincias P ON P.id = D.provincia_id
        INNER JOIN flash_paises PA ON PA.id = P.pais_id 

        WHERE D.zona_id = 3 LIMIT 1;
        */

        $query = $this->db
                        ->select('PA.id AS id, PA.nombre as pais')
                        ->join('ubicacion_provincias P', 'P.id = D.provincia_id', 'inner')
                        ->join('flash_paises PA', 'PA.id = P.pais_id', 'inner')
                        ->where('D.zona_id = ' . $id )
                        ->limit(1)
                        ->get('flash_tarifario_zona_detalle D');

        return $query->row();
    }


    function getProvinciasSeleccionadas($id){
        /*
        SELECT P.id, P.nombre
                
        FROM flash_tarifario_zona_detalle D

        INNER JOIN ubicacion_provincias P ON P.id = D.provincia_id
        INNER JOIN flash_paises PA ON PA.id = P.pais_id 

        WHERE D.zona_id = 9
        GROUP BY P.nombre;
        */
        $query = $this->db
                        ->select('P.id, P.nombre')
                        ->join('ubicacion_provincias P', 'P.id = D.provincia_id', 'inner')
                        ->join('flash_paises PA', 'PA.id = P.pais_id', 'inner')
                        ->where('D.zona_id = ' . $id )
                        ->group_by('P.nombre')
                        ->get('flash_tarifario_zona_detalle D');

        return $query->result();
    }   

    function getLocalidadesSeleccionadas($id){
        /*
        SELECT L.id, L.nombre
                
        FROM flash_tarifario_zona_detalle D

        INNER JOIN ubicacion_localidades L ON L.id = D.localidad_id

        WHERE D.zona_id = 9
        */

        $query = $this->db
                        ->select('L.id, L.nombre')
                        ->join('ubicacion_localidades L', 'L.id = D.localidad_id', 'inner')
                        ->where('D.zona_id = ' . $id )
                        ->get('flash_tarifario_zona_detalle D');

        return $query->result();
    }

    function getProvinciasPorPaisZona($id){
        /*
        SELECT PA.id AS id, PA.nombre as pais
                
        FROM flash_tarifario_zona_detalle D

        INNER JOIN ubicacion_provincias P ON P.id = D.provincia_id
        INNER JOIN flash_paises PA ON PA.id = P.pais_id 

        WHERE D.zona_id = 3 LIMIT 1;
        */

        $query = $this->db
                        ->select('PA.id AS id, PA.nombre as pais')
                        ->join('ubicacion_provincias P', 'P.id = D.provincia_id', 'inner')
                        ->join('flash_paises PA', 'PA.id = P.pais_id', 'inner')
                        ->where('D.zona_id = ' . $id )
                        ->limit(1)
                        ->get('flash_tarifario_zona_detalle D');

        return $query->row();
    }

    function comprobarQueNoEsteCargadaEnUnEsquemaTarifario($id){
        /*
        SELECT COUNT(*) FROM sub_esquema WHERE zonaA = 22 or zonaB = 22
        */
        $query = $this->db
                        ->select('COUNT(*) as cantidad')
                        ->where('S.zonaA = ' . $id)
                        ->or_where('S.zonaB = '. $id)
                        ->get('sub_esquema S');

        //return $this->db->last_query();
        return $query->row();
    }

}