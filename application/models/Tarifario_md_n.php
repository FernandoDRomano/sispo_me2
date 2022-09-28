<?php 

class Tarifario_md_n extends CI_Model {
    
    function insertDetalleKilos($zonaA, $zonaB, $desde, $hasta, $precio, $bandera){
        
        $data = array(
            "zonaA" => $zonaA,
            "zonaB" => $zonaB,
            "desde_cant_unid_kg" => $desde,
            "hasta_cant_unid_kg" => $hasta,
            "precio_Corte_kg" => $precio,
            "bandera_Corte_kg" => $bandera,
        );

        $this->db->insert('flash_servicios_tarifas_kg', $data);

        $insert_id = $this->db->insert_id();

        return  $insert_id;
    }

    function insertDetallesBultos($zonaA, $zonaB, $desde, $hasta, $precio, $bandera){
        
        $data = array(
            "zonaA" => $zonaA,
            "zonaB" => $zonaB,
            "desde_cant_unid_bultos" => $desde,
            "hasta_cant_unid_bultos" => $hasta,
            "precio_Corte_bultos" => $precio,
            "bandera_Corte_Bultos" => $bandera,
        );

        $this->db->insert('flash_servicios_tarifas_bultos', $data);

        $insert_id = $this->db->insert_id();

        return  $insert_id;
    }

    function insertDetallesMetroCubico($zonaA, $zonaB, $desde, $hasta, $precio, $bandera){
        
        $data = array(
            "zonaA" => $zonaA,
            "zonaB" => $zonaB,
            "desde_cantidad_metro_cubico" => $desde,
            "hasta_cantidad_metro_cubico" => $hasta,
            "precio_mc" => $precio,
            "bandera_bloque_unidad" => $bandera,
        );

        $this->db->insert('flash_servicios_tarifas_metro_cubico', $data);

        $insert_id = $this->db->insert_id();

        return  $insert_id;
    }

    function insertDetallesPalets($zonaA, $zonaB, $desde, $hasta, $precio, $bandera){
        
        $data = array(
            "zonaA" => $zonaA,
            "zonaB" => $zonaB,
            "desde_cantidad_palets" => $desde,
            "hasta_cantidad_palets" => $hasta,
            "precio_palets" => $precio,
            "bandera_palets" => $bandera,
        );

        $this->db->insert('flash_servicios_tarifas_palets', $data);

        $insert_id = $this->db->insert_id();

        return  $insert_id;
    }

    /**
     * CONSULTAS AJAX
     */

    function validarReglaKilos($cliente_id, $zonaA, $zonaB, $desde, $hasta){
    
        $queryKgDesde = $this->db
                ->select('COUNT(*)')
                ->join('flash_servicios_tarifas_kg K',  'K.servicio_paqueteria_id = P.id', 'inner')
                ->where('P.cliente_id = ' . $cliente_id)
                ->where('K.zonaA = ' . $zonaA)
                ->where('K.zonaB = ' . $zonaB)
                ->where('K.desde_cant_unid_kg <= ' . $desde)
                ->where('K.hasta_cant_unid_kg >= ' . $desde)
                ->get('flash_servicios_paqueteria_nuevo P');			

        $query = $queryKgDesde->result();

        print_r($query);

        /*

        $queryKgHasta = $this->db
                ->select('COUNT(*)')
                ->join('flash_servicios_tarifas_kg K',  'K.servicio_paqueteria_id = P.id', 'inner')
                ->where('P.cliente_id = ' . $cliente_id)
                ->where('K.zonaA = ' . $zonaA)
                ->where('K.zonaB = ' . $zonaB)
                ->where('K.desde_cant_unid_kg <= ' . $hasta)
                ->where('K.hasta_cant_unid_kg >= ' . $hasta)
                ->get('flash_servicios_paqueteria_nuevo P');			

        $query = $queryKgHasta->result();

        */

        return $query;
    }


}