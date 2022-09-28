<?php 

class Posicion_md extends CI_Model{

    function provincia_carteros()
    {
        /*
        $prov_carteros = DB::connection('Sispo')->select(DB::raw("
            SELECT c.sucursal_id as id, s.nombre as nombre

            from sispoc5_gestionpostal.flash_sucursales_carteros as c
            left join sispoc5_gestionpostal.flash_sucursales as s on s.id = c.sucursal_id

            WHERE c.activo = 1
            GROUP by c.sucursal_id
        "));

        return $prov_carteros;
        */

        $query = "SELECT c.sucursal_id as id, s.nombre as nombre
                from sispoc5_gestionpostal.flash_sucursales_carteros as c
                left join sispoc5_gestionpostal.flash_sucursales as s on s.id = c.sucursal_id

                WHERE c.activo = 1
                GROUP by c.sucursal_id";

        $resultado = $this->db->query($query);
        $prov_carteros = $resultado->result_array();

        return $prov_carteros;

    }

    function get_sucursales($pais){
        if(!empty($pais)){
            $query = "SELECT * FROM flash_sucursales s WHERE s.pais_id = $pais";

            $resultado = $this->db->query($query);
            $sucursales = $resultado->result_array();

            return $sucursales;
        }
    }

    function get_clientes($pais){
        if(!empty($pais)){
            $query = "SELECT * FROM flash_clientes C WHERE C.pais = $pais";

            $resultado = $this->db->query($query);
            $clientes = $resultado->result_array();

            return $clientes;
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

    function get_carteros_por_cliente($cliente){
        if(!empty($cliente)){
            $query = "SELECT CAR.id idCartero, CAR.apellido_nombre nombreCartero, HDR.id idHDR, HDR.estado, HDR.fecha_entrega 
            FROM flash_clientes C 
            LEFT JOIN flash_comprobantes_ingresos COB ON COB.cliente_id = C.id
            LEFT JOIN flash_piezas P ON P.comprobante_ingreso_id = COB.id
            LEFT JOIN flash_hojas_rutas HDR ON HDR.id = P.hoja_ruta_id
            LEFT JOIN flash_sucursales_carteros CAR ON CAR.id = HDR.cartero_id
            WHERE C.id = $cliente AND HDR.estado = 1 AND HDR.fecha_entrega = CURDATE()
            GROUP BY CAR.id
            ORDER BY CAR.id ASC";

            $resultado = $this->db->query($query);
            $carteros = $resultado->result_array();

            return $carteros;
        }
    }

    function carteros_listado()
    {
        /*
        $consulta_carteros = DB::connection('Sispo')->select(DB::raw("
			SELECT c.*, c.id as cartero_id
			FROM flash_sucursales_carteros as c
			WHERE c.activo = 1
			order by c.apellido_nombre
		"));		

        return $consulta_carteros;
        */

        $query = "SELECT c.*, c.id as cartero_id
        FROM flash_sucursales_carteros as c
        WHERE c.activo = 1
        order by c.apellido_nombre";

        $resultado = $this->db->query($query);
        $consulta_carteros = $resultado->result_array();

        return $consulta_carteros;
    }

    function tipos_carteros()
    {
        /*
        $consulta_tipo_carteros = DB::connection('Sispo')->select(DB::raw("
            SELECT *
            FROM flash_sucursales_carteros_tipos
        "));

        return $consulta_tipo_carteros;
        */

        $query = "SELECT *
        FROM flash_sucursales_carteros_tipos";

        $resultado = $this->db->query($query);
        $consulta_tipo_carteros = $resultado->result_array();

        return $consulta_tipo_carteros;
    }

    function clientes(){
        $query = "SELECT id, nombre, pais FROM flash_clientes";

        $resultado = $this->db->query($query);
        $clientes = $resultado->result_array();

        return $clientes;
    }

    function paises(){
        $query = "SELECT * FROM flash_paises";

        $resultado = $this->db->query($query);
        $paises = $resultado->result_array();

        return $paises;
    }

}