<?php 

class Plantilla_md extends CI_Model {

    function getPlantillas($buscar = null){

        if(is_null($buscar) || $buscar == ''){
            $query = $this->db
                        ->select('p.id, p.nombre, p.descripcion')
                        ->get('flash_plantillas_paqueteria p');
            
            return $query->result();
        }

        $query = $this->db
                    ->select('p.id, p.nombre, p.descripcion')
                    ->like('p.nombre', $buscar)
                    ->or_like('p.descripcion', $buscar)
                    ->get('flash_plantillas_paqueteria p');
            
        return $query->result();
    }
    
    function getPlantilla($id){
        if(!empty($id)){
            $query = $this->db
                    ->select('p.id, p.nombre, p.descripcion')
                    ->where('p.id = ', $id)
                    ->get('flash_plantillas_paqueteria p');

            return $query->row();
        }
    }

    function insertDetalleKilos($desde, $hasta, $precio, $bandera, $plantilla_id){
        
        $data = array(
            "desde_cant_unid_kg" => $desde,
            "hasta_cant_unid_kg" => $hasta,
            "precio" => $precio,
            "bandera" => $bandera,
            "plantilla_id" => $plantilla_id,
        );

        $this->db->insert('flash_plantilla_detalle_tarifa_kg', $data);
    }

    function insertDetalleBultos($desde, $hasta, $precio, $bandera, $plantilla_id){
        
        $data = array(
            "desde_cant_unid_bultos" => $desde,
            "hasta_cant_unid_bultos" => $hasta,
            "precio" => $precio,
            "bandera_Corte_Bultos" => $bandera,
            "plantilla_id" => $plantilla_id,
        );

        $this->db->insert('flash_plantilla_detalle_tarifa_bultos', $data);
    }

    function insertDetalleDistancia($desde, $hasta, $precio, $plantilla_id){
        
        $data = array(
            "desde" => $desde,
            "hasta" => $hasta,
            "precio" => $precio,
            "plantilla_id" => $plantilla_id,
        );

        $this->db->insert('flash_plantilla_detalle_tarifa_distancia', $data);
    }

    function insertDetalleMetroCubico($desde, $hasta, $precio, $bandera, $plantilla_id){
        
        $data = array(
            "desde_cantidad_metro_cubico" => $desde,
            "hasta_cantidad_metro_cubico" => $hasta,
            "precio" => $precio,
            "bandera_bloque_unidad" => $bandera,
            "plantilla_id" => $plantilla_id,
        );

        $this->db->insert('flash_plantilla_detalle_tarifa_metro_cubico', $data);
    }

    function insertDetallePalets($desde, $hasta, $precio, $bandera, $plantilla_id){
        
        $data = array(
            "desde_cantidad_palets" => $desde,
            "hasta_cantidad_palets" => $hasta,
            "precio" => $precio,
            "bandera_palets" => $bandera,
            "plantilla_id" => $plantilla_id,
        );

        $this->db->insert('flash_plantilla_detalle_tarifa_palets', $data);
    }

    function insertDetalleGestionFlota($opcion_vehiculos, $opcion_horas, $precio_rango, $plantilla_id){
        
        $data = array(
            "tipo_vehiculo" => $opcion_vehiculos,
            "tipo_hora" => $opcion_horas,
            "precio" => $precio_rango,
            "plantilla_id" => $plantilla_id,
        );

        $this->db->insert('flash_plantilla_detalle_tarifa_gestion_flota', $data);
    }

    function insertDetalleWareHouse($desde, $hasta, $precio, $plantilla_id){
        
        $data = array(
            "desde" => $desde,
            "hasta" => $hasta,
            "precio" => $precio,
            "plantilla_id" => $plantilla_id,
        );

        $this->db->insert('flash_plantilla_detalle_tarifa_warehouse', $data);
    }

    function insertDetalleCobranza($valor, $tipo, $plantilla_id){
        
        $data = array(
            "valor" => $valor,
            "tipo" => $tipo,
            "plantilla_id" => $plantilla_id,
        );

        $this->db->insert('flash_plantilla_detalle_tarifa_cobranza', $data);
    }

    function insertDetalleValorDeclarado($valor, $tipo, $plantilla_id){
        
        $data = array(
            "valor" => $valor,
            "tipo" => $tipo,
            "plantilla_id" => $plantilla_id,
        );

        $this->db->insert('flash_plantilla_detalle_tarifa_valor_declarado', $data);
    }

    function insertDetallePesoAforado($valor, $plantilla_id){
        
        $data = array(
            "valor" => $valor,
            "plantilla_id" => $plantilla_id,
        );

        $this->db->insert('flash_plantilla_detalle_tarifa_peso_aforado', $data);
    }

    function updateDetalleKilos($id, $desde, $hasta, $precio, $bandera, $plantilla_id){
        
        $data = array(
            "desde_cant_unid_kg" => $desde,
            "hasta_cant_unid_kg" => $hasta,
            "precio" => $precio,
            "bandera" => $bandera,
        );

        $this->db->update('flash_plantilla_detalle_tarifa_kg', $data, "id = " . $id);
    
        //$this->db->where('id', $id);
        //$this->db->update('flash_plantilla_detalle_tarifa_kg', $data);

    }

}