<?php 

class Circulacion_md extends CI_Model{

    function tipos_carteros(){
        /*
        $consulta_tipo_carteros = DB::connection('Sispo')->select(DB::raw("
            SELECT *
            FROM flash_sucursales_carteros_tipos
        "));

        return $consulta_tipo_carteros;
        */

        $query = "SELECT * FROM flash_sucursales_carteros_tipos";

        $resultado = $this->db->query($query);
        $tipos_carteros = $resultado->result_array();

        return $tipos_carteros;

    }

    function carteros_listado(){
        /*
        $carteros = DB::connection('Sispo')->select(DB::raw("
                SELECT *
                FROM flash_sucursales_carteros
                WHERE activo = 1
                order by apellido_nombre
            "));
        */

        $query = "SELECT *
        FROM flash_sucursales_carteros
        WHERE activo = 1
        order by apellido_nombre";

        $resultado = $this->db->query($query);
        $consulta_carteros = $resultado->result_array();

        return $consulta_carteros;
    }

    function sucursales_listado(){
        /*
         $sucursales = DB::connection('Sispo')->select(DB::raw("
            SELECT *
            FROM flash_sucursales
            order by id
        "));
        */

        $query = "SELECT *
        FROM flash_sucursales
        order by id";
        

        $resultado = $this->db->query($query);
        $consulta_sucursales = $resultado->result_array();

        return $consulta_sucursales;
    }

    function paises(){
        $query = "SELECT * FROM flash_paises";

        $resultado = $this->db->query($query);
        $paises = $resultado->result_array();

        return $paises;
    }
    
    function get_sucursales($pais){
        if(!empty($pais)){
            $query = "SELECT * FROM flash_sucursales s WHERE s.pais_id = $pais";

            $resultado = $this->db->query($query);
            $sucursales = $resultado->result_array();

            return $sucursales;
        }
    }

    function get_carteros($pais){
        if(!empty($pais)){
            $query = "SELECT * FROM flash_sucursales_carteros C WHERE C.pais = $pais";

            $resultado = $this->db->query($query);
            $carteros = $resultado->result_array();

            return $carteros;
        }
    }

}