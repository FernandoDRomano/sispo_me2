<?php 

class Esquema_md extends CI_Model {

    function getEsquemas($buscar = null){

        if(is_null($buscar) || $buscar == ''){
            $query = $this->db
                        ->select('e.id, e.nombre')
                        ->get('flash_esquema e');
            
            return $query->result();
        }

        $query = $this->db
                    ->select('e.id, e.nombre')
                    ->like('e.nombre', $buscar)
                    ->get('flash_esquema e');
            
        return $query->result();
    }

    function insertEsquema($nombre, $user_id){
        $data = array(
            "nombre" => $nombre,
            "user_id" => $user_id,
        );

        $this->db->insert('flash_esquema', $data);

        $insert_id = $this->db->insert_id();

        return  $insert_id;
    }

    function updateEsquema($nombre, $user_id){
        $data = array(
            "nombre" => $nombre,
            "user_id" => $user_id,
        );

        $this->db->insert('flash_esquema', $data);

        $insert_id = $this->db->insert_id();

        return  $insert_id;
    }

    function insertSubEsquema($esquema_id, $zonaA, $zonaB, $plantilla_id){
        $data = array(
            "esquema_id" => $esquema_id,
            "zonaA" => $zonaA,
            "zonaB" => $zonaB, 
            "plantilla_id" => $plantilla_id
        );

        $this->db->insert('sub_esquema', $data);

        $insert_id = $this->db->insert_id();

        return  $insert_id;
    }
    
    function insertDetalleKilos($zonaA, $zonaB, $desde, $hasta, $precio, $bandera, $sub_esquema_id){
        
        $data = array(
            "zonaA" => $zonaA,
            "zonaB" => $zonaB,
            "desde" => $desde,
            "hasta" => $hasta,
            "precio" => $precio,
            "bandera" => $bandera,
            "sub_esquema_id" => $sub_esquema_id,
        );

        $this->db->insert('flash_sub_esquema_kg', $data);

        $insert_id = $this->db->insert_id();

        return  $insert_id;
    }

    function insertDetalleDistancia($zonaA, $zonaB, $desde, $hasta, $precio, $sub_esquema_id){
        
        $data = array(
            "zonaA" => $zonaA,
            "zonaB" => $zonaB,
            "desde" => $desde,
            "hasta" => $hasta,
            "precio" => $precio,
            "sub_esquema_id" => $sub_esquema_id,
        );

        $this->db->insert('flash_sub_esquema_distancia', $data);

        $insert_id = $this->db->insert_id();

        return  $insert_id;
    }

    function insertDetalleBultos($zonaA, $zonaB, $desde, $hasta, $precio, $bandera, $sub_esquema_id){
        
        $data = array(
            "zonaA" => $zonaA,
            "zonaB" => $zonaB,
            "desde" => $desde,
            "hasta" => $hasta,
            "precio" => $precio,
            "bandera" => $bandera,
            "sub_esquema_id" => $sub_esquema_id,
        );

        $this->db->insert('flash_sub_esquema_bultos', $data);

        $insert_id = $this->db->insert_id();

        return  $insert_id;
    }

    function insertDetalleMetroCubico($zonaA, $zonaB, $desde, $hasta, $precio, $bandera, $sub_esquema_id){
        
        $data = array(
            "zonaA" => $zonaA,
            "zonaB" => $zonaB,
            "desde" => $desde,
            "hasta" => $hasta,
            "precio" => $precio,
            "bandera" => $bandera,
            "sub_esquema_id" => $sub_esquema_id
        );

        $this->db->insert('flash_sub_esquema_metros_cubicos', $data);

        $insert_id = $this->db->insert_id();

        return  $insert_id;
    }

    function insertDetallePalets($zonaA, $zonaB, $desde, $hasta, $precio, $bandera, $sub_esquema_id){
        
        $data = array(
            "zonaA" => $zonaA,
            "zonaB" => $zonaB,
            "desde" => $desde,
            "hasta" => $hasta,
            "precio" => $precio,
            "bandera" => $bandera,
            "sub_esquema_id" => $sub_esquema_id,
        );

        $this->db->insert('flash_sub_esquema_palets', $data);

        $insert_id = $this->db->insert_id();

        return  $insert_id;
    }

    function insertDetalleWareHouse($zonaA, $zonaB, $desde, $hasta, $precio, $sub_esquema_id){
        
        $data = array(
            "zonaA" => $zonaA,
            "zonaB" => $zonaB,
            "desde" => $desde,
            "hasta" => $hasta,
            "precio" => $precio,
            "sub_esquema_id" => $sub_esquema_id,
        );

        $this->db->insert('flash_sub_esquema_warehouse', $data);

        $insert_id = $this->db->insert_id();

        return  $insert_id;
    }

    function insertDetalleGestionFlota($zonaA, $zonaB, $tipo_vehiculo, $tipo_hora, $precio, $sub_esquema_id){
        
        $data = array(
            "zonaA" => $zonaA,
            "zonaB" => $zonaB,
            "tipo_vehiculo" => $tipo_vehiculo,
            "tipo_hora" => $tipo_hora,
            "precio" => $precio,
            "sub_esquema_id" => $sub_esquema_id,
        );

        $this->db->insert('flash_sub_esquema_gestion_flota', $data);

        $insert_id = $this->db->insert_id();

        return  $insert_id;
    }

    function insertDetalleCobranza($zonaA, $zonaB, $valor, $tipo, $sub_esquema_id){
        
        $data = array(
            "zonaA" => $zonaA,
            "zonaB" => $zonaB,
            "valor" => $valor,
            "tipo" => $tipo,
            "sub_esquema_id" => $sub_esquema_id,
        );

        $this->db->insert('flash_sub_esquema_cobranza', $data);

        $insert_id = $this->db->insert_id();

        return  $insert_id;
    }

    function insertDetalleValorDeclarado($zonaA, $zonaB, $valor, $tipo, $sub_esquema_id){
        
        $data = array(
            "zonaA" => $zonaA,
            "zonaB" => $zonaB,
            "valor" => $valor,
            "tipo" => $tipo,
            "sub_esquema_id" => $sub_esquema_id,
        );

        $this->db->insert('flash_sub_esquema_valor_declarado', $data);

        $insert_id = $this->db->insert_id();

        return  $insert_id;
    }

    function insertDetallePesoAforado($zonaA, $zonaB, $valor, $sub_esquema_id){
        
        $data = array(
            "zonaA" => $zonaA,
            "zonaB" => $zonaB,
            "valor" => $valor,
            "sub_esquema_id" => $sub_esquema_id,
        );

        $this->db->insert('flash_sub_esquema_peso_aforado', $data);

        $insert_id = $this->db->insert_id();

        return  $insert_id;
    }

    function getSubEsquemas($ids){
        $query = $this->db
                        ->select('S.id AS idSubEsquema, K.zonaA AS zonaA, K.zonaB AS zonaB, K.id AS idKilo, K.desde, K.hasta, K.precio, K.bandera')
                        ->join('flash_sub_esquema_kg K', 'K.sub_esquema_id = S.id', 'inner')
                        ->where_in('S.id', $ids)
                        ->get('sub_esquema S');
        //print_r($this->db->last_query());
        return $query->result();
    }

    function getKilosPorSubEsquema($ids){
        $query = $this->db
                        ->select('S.id AS idSubEsquema, K.zonaA AS zonaA, K.zonaB AS zonaB, K.id AS idKilo, K.desde, K.hasta, K.precio, K.bandera')
                        ->join('flash_sub_esquema_kg K', 'K.sub_esquema_id = S.id', 'inner')
                        ->where_in('S.id', $ids)
                        ->get('sub_esquema S');
        //print_r($this->db->last_query());
        return $query->result();
    }

    function getDistanciaPorSubEsquema($ids){
        $query = $this->db
                        ->select('S.id AS idSubEsquema, D.zonaA AS zonaA, D.zonaB AS zonaB, D.id AS idDistancia, D.desde, D.hasta, D.precio')
                        ->join('flash_sub_esquema_distancia D', 'D.sub_esquema_id = S.id', 'inner')
                        ->where_in('S.id', $ids)
                        ->get('sub_esquema S');
        //print_r($this->db->last_query());
        return $query->result();
    }

    function getBultosPorSubEsquema($ids){
        $query = $this->db
                        ->select('S.id AS idSubEsquema, B.zonaA AS zonaA, B.zonaB AS zonaB, B.id AS idBulto, B.desde, B.hasta, B.precio, B.bandera')
                        ->join('flash_sub_esquema_bultos B', 'B.sub_esquema_id = S.id', 'inner')
                        ->where_in('S.id', $ids)
                        ->get('sub_esquema S');
        //print_r($this->db->last_query());
        return $query->result();
    }

    function getPaletsPorSubEsquema($ids){
        $query = $this->db
                        ->select('S.id AS idSubEsquema, P.zonaA AS zonaA, P.zonaB AS zonaB, P.id AS idPalet, P.desde, P.hasta, P.precio, P.bandera')
                        ->join('flash_sub_esquema_palets P', 'P.sub_esquema_id = S.id', 'inner')
                        ->where_in('S.id', $ids)
                        ->get('sub_esquema S');
        //print_r($this->db->last_query());
        return $query->result();
    }

    function getMetrosCubicosPorSubEsquema($ids){
        $query = $this->db
                        ->select('S.id AS idSubEsquema, M.zonaA AS zonaA, M.zonaB AS zonaB, M.id AS idMetroCubico, M.desde, M.hasta, M.precio, M.bandera')
                        ->join('flash_sub_esquema_metros_cubicos M', 'M.sub_esquema_id = S.id', 'inner')
                        ->where_in('S.id', $ids)
                        ->get('sub_esquema S');
        //print_r($this->db->last_query());
        return $query->result();
    }

    function getWarehousesPorSubEsquema($ids){
        $query = $this->db
                        ->select('S.id AS idSubEsquema, W.zonaA AS zonaA, W.zonaB AS zonaB, W.id AS idWarehouse, W.desde, W.hasta, W.precio')
                        ->join('flash_sub_esquema_warehouse W', 'W.sub_esquema_id = S.id', 'inner')
                        ->where_in('S.id', $ids)
                        ->get('sub_esquema S');
        //print_r($this->db->last_query());
        return $query->result();
    }

    function getGestionFlotasPorSubEsquema($ids){
        $query = $this->db
                        ->select('S.id AS idSubEsquema, G.zonaA AS zonaA, G.zonaB AS zonaB, G.id AS idGestionFlota, G.tipo_vehiculo, G.tipo_hora, G.precio')
                        ->join('flash_sub_esquema_gestion_flota G', 'G.sub_esquema_id = S.id', 'inner')
                        ->where_in('S.id', $ids)
                        ->get('sub_esquema S');
        //print_r($this->db->last_query());
        return $query->result();
    }

    function getCobranzasPorSubEsquema($ids){
        $query = $this->db
                        ->select('S.id AS idSubEsquema, C.zonaA AS zonaA, C.zonaB AS zonaB, C.id AS idCobranza, C.tipo, C.valor')
                        ->join('flash_sub_esquema_cobranza C', 'C.sub_esquema_id = S.id', 'inner')
                        ->where_in('S.id', $ids)
                        ->get('sub_esquema S');
        //print_r($this->db->last_query());
        return $query->result();
    }

    function getValorDeclaradosPorSubEsquema($ids){
        $query = $this->db
                        ->select('S.id AS idSubEsquema, V.zonaA AS zonaA, V.zonaB AS zonaB, V.id AS idValorDeclarado, V.tipo, V.valor')
                        ->join('flash_sub_esquema_valor_declarado V', 'V.sub_esquema_id = S.id', 'inner')
                        ->where_in('S.id', $ids)
                        ->get('sub_esquema S');
        //print_r($this->db->last_query());
        return $query->result();
    }

    function getPesoAforadosPorSubEsquema($ids){
        $query = $this->db
                        ->select('S.id AS idSubEsquema, P.zonaA AS zonaA, P.zonaB AS zonaB, P.id AS idPesoAforado, P.valor')
                        ->join('flash_sub_esquema_peso_aforado P', 'P.sub_esquema_id = S.id', 'inner')
                        ->where_in('S.id', $ids)
                        ->get('sub_esquema S');
        //print_r($this->db->last_query());
        return $query->result();
    }

    
    function comprobarQueNoEsteAsignadoAClientes($id){
        /*
        SELECT COUNT(*) FROM flash_tarifario WHERE esquema_id = 54
        */
        $query = $this->db
                        ->select('COUNT(*) as cantidad')
                        ->where('T.esquema_id = ' . $id)
                        ->get('flash_tarifario T');

        //return $this->db->last_query();
        return $query->row();
    }

}