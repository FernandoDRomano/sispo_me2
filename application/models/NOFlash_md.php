<?php
class Flash_md extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getClientes($estado=null, $tipo=null, $buscar=null){
        if($estado != null && $estado != 0) {
            if($estado != null && $estado != 0) $query = $this->db->where('c.cliente_estado_id', $estado);
            if($tipo != null && $tipo != 0) $query = $this->db->where('c.tipo_cliente_id', $tipo);
            if($buscar != null && $buscar != '') {
                $query = $this->db->like('c.nombre', $buscar);
                $query = $this->db->or_like('c.nombre_fantasia', $buscar);
                $query = $this->db->or_like('c.cuit', $buscar);
            }
        }

        else if($tipo != null && $tipo != 0) {
            if($estado != null && $estado != 0) $query = $this->db->where('c.cliente_estado_id', $estado);
            if($tipo != null && $tipo != 0) $query = $this->db->where('c.tipo_cliente_id', $tipo);
            if($buscar != null && $buscar != '') {
                $query = $this->db->like('c.nombre', $buscar);
                $query = $this->db->or_like('c.nombre_fantasia', $buscar);
                $query = $this->db->or_like('c.cuit', $buscar);
            }
        }

        else if($buscar != null && $buscar != '') {
            if($estado != null && $estado != 0) $query = $this->db->where('c.cliente_estado_id', $estado);
            if($tipo != null && $tipo != 0) $query = $this->db->where('c.tipo_cliente_id', $tipo);
            if($buscar != null && $buscar != '') {
                $query = $this->db->like('c.nombre', $buscar);
                $query = $this->db->or_like('c.nombre_fantasia', $buscar);
                $query = $this->db->or_like('c.cuit', $buscar);
            }
        }

        $query = $this->db
                        ->select('c.*, t.nombre AS tipo, e.nombre AS estado')
                        ->join('flash_clientes_tipos t', 't.id = c.tipo_cliente_id')
                        ->join('flash_clientes_estados e', 'e.id = cliente_estado_id')
                        ->get('flash_clientes c');
        return $query->result();
    }

    function getDepartamentos($buscar=null){
        if($buscar != null && $buscar != '') {
            $query = $this->db->like('c.nombre', $buscar);
            $query = $this->db->or_like('d.nombre', $buscar);
            $query = $this->db->or_like('d.nombre_contacto', $buscar);
        }

        $query = $this->db
                        ->select('d.*, c.nombre AS cliente')
                        ->join('flash_clientes c', 'c.id = d.cliente_id')
                        ->get('flash_clientes_departamentos d');
        return $query->result();
    }

    function getServicios($grupo=null, $buscar=null){
        if($grupo != null && $grupo != 0) $query = $this->db->where('s.grupo_id', $grupo);
        if($buscar != null && $buscar != '') $query = $this->db->like('s.nombre', $buscar);

        $query = $this->db
                        ->select('s.*, g.nombre AS grupo')
                        ->join('flash_servicios_grupos g', 'g.id = s.grupo_id')
                        ->get('flash_servicios s');
        $result = $query->result();
//        echo $this->db->last_query();die;
        return $query->result();
    }

    function getZonas($sucursal=null, $buscar=null){
        $user_row = $this->ion_auth->user()->row();
        if (!$this->ion_auth->in_group(array(1, 2), $user_row->id)) $query = $this->db->where('s.id', $user_row->sucursal_id);

        if($sucursal != null && $sucursal != 0) {
            if($sucursal != null && $sucursal != 0) $query = $this->db->where('z.sucursal_id', $sucursal);
            if($buscar != null && $buscar != '') $query = $this->db->like('z.nombre', $buscar);
        }

        else if($buscar != null && $buscar != '') $query = $this->db->like('z.nombre', $buscar);

        $query = $this->db
                        ->select('z.*, s.nombre AS sucursal')
                        ->join('flash_sucursales s', 's.id = z.sucursal_id')
                        ->get('flash_sucursales_zonas z');
        return $query->result();
    }

    function getCarteros($sucursal=NULL, $buscar=NULL, $cartero_tipo_id=NULL){
        $user_row = $this->ion_auth->user()->row();
        if (!$this->ion_auth->in_group(array(1, 2), $user_row->id)) $query = $this->db->where('s.id', $user_row->sucursal_id);

        if($sucursal != null && $sucursal != 0) {
            if($sucursal != null && $sucursal != 0) $query = $this->db->where('c.sucursal_id', $sucursal);
            if($buscar != null && $buscar != '') {
                $query = $this->db->like('c.apellido_nombre', $buscar);
                $query = $this->db->or_like('c.domicilio', $buscar);
                $query = $this->db->or_like('c.localidad', $buscar);
                $query = $this->db->or_like('c.codigo_postal', $buscar);
            }
        }

        else if($buscar != null && $buscar != '') {
            $query = $this->db->like('c.apellido_nombre', $buscar);
            $query = $this->db->or_like('c.domicilio', $buscar);
            $query = $this->db->or_like('c.localidad', $buscar);
            $query = $this->db->or_like('c.codigo_postal', $buscar);
        }
        
        if($buscar != null && $buscar != '') {
            $query = $this->db->like('c.apellido_nombre', $buscar);
            $query = $this->db->or_like('c.domicilio', $buscar);
            $query = $this->db->or_like('c.localidad', $buscar);
            $query = $this->db->or_like('c.codigo_postal', $buscar);
        }

        $cartero_tipo_id = $cartero_tipo_id != NULL? $query = $this->db->where('c.cartero_tipo_id', $cartero_tipo_id):'';
        
        $query = $this->db
                        ->select('c.*, s.nombre AS sucursal, t.nombre cartero_tipo')
                        ->join('flash_sucursales s', 's.id = c.sucursal_id')
                        ->join('flash_sucursales_carteros_tipos t', 't.id = c.cartero_tipo_id','left')
                        ->get('flash_sucursales_carteros c');
        return $query->result();
    }

    function getPreciosEspeciales($grupo=null, $servicio=null, $activo=null, $buscar=null){
        if($grupo != null && $grupo != 0) {
            if($grupo != null && $grupo != 0) $query = $this->db->where('p.grupo_servicio_id', $grupo);
            if($servicio != null && $servicio != 0) $query = $this->db->where('p.servicio_id', $servicio);
            if($activo != null && $activo != '') $query = $this->db->where('p.activo', $activo);
            if($buscar != null && $buscar != '') {
                $query = $this->db->like('c.nombre', $buscar);
                $query = $this->db->or_like('p.nombre', $buscar);
            }
        }

        else if($servicio != null && $servicio != 0) {
            if($grupo != null && $grupo != 0) $query = $this->db->where('p.grupo_servicio_id', $grupo);
            if($servicio != null && $servicio != 0) $query = $this->db->where('p.servicio_id', $servicio);
            if($activo != null && $activo != '') $query = $this->db->where('p.activo', $activo);
            if($buscar != null && $buscar != '') {
                $query = $this->db->like('c.nombre', $buscar);
                $query = $this->db->or_like('p.nombre', $buscar);
            }
        }

        else if($activo != null && $activo != '') {
            if($grupo != null && $grupo != 0) $query = $this->db->where('p.grupo_servicio_id', $grupo);
            if($servicio != null && $servicio != 0) $query = $this->db->where('p.servicio_id', $servicio);
            if($activo != null && $activo != '') $query = $this->db->where('p.activo', $activo);
            if($buscar != null && $buscar != '') {
                $query = $this->db->like('c.nombre', $buscar);
                $query = $this->db->or_like('p.nombre', $buscar);
            }
        }

        else if($buscar != null && $buscar != '') {
            if($grupo != null && $grupo != 0) $query = $this->db->where('p.grupo_servicio_id', $grupo);
            if($servicio != null && $servicio != 0) $query = $this->db->where('p.servicio_id', $servicio);
            if($activo != null && $activo != '') $query = $this->db->where('p.activo', $activo);
            if($buscar != null && $buscar != '') {
                $query = $this->db->like('c.nombre', $buscar);
                $query = $this->db->or_like('p.nombre', $buscar);
            }
        }
        $query = $this->db
                        ->select('p.*, c.nombre AS cliente, g.nombre AS grupo, s.nombre AS servicio')
                        ->join('flash_clientes c', 'c.id = p.cliente_id')
                        ->join('flash_servicios_grupos g', 'g.id = p.grupo_servicio_id')
                        ->join('flash_servicios s', 's.id = p.servicio_id')
                        ->get('flash_clientes_precios_especiales p');
        $result = $query->result();
//                     echo $this->db->last_query();die;
        return $result;
    }

    function getServiciosClientesALL(){
        $query = $this->db
                        ->select('c.nombre cliente, c.cuit cuit, i.descripcion iva, cpe.nombre servicio, cpe.precio')
                        ->join('flash_clientes_precios_especiales cpe', 'c.id = cpe.cliente_id','left')
                        ->join('flash_iva i', 'c.flash_iva_id = i.id')
                        ->group_by('c.id, cpe.id')
                        ->get('flash_clientes c');
        $result = $query->result();
//                     echo $this->db->last_query();die;
        return $result;
    }
    
    function getComprobantesIngresos($empresa=null, $cliente=null, $dpto=null){
        $user_row = $this->ion_auth->user()->row();
        if (!$this->ion_auth->in_group(array(1, 2), $user_row->id)) $query = $this->db->where('s.id', $user_row->sucursal_id);

        if($empresa != null && $empresa != 0) $query = $this->db->where('c.empresa_id', $empresa);
        if($cliente != null && $cliente != 0) $query = $this->db->where('c.cliente_id', $cliente);
        if($dpto != null && $dpto != 0) $query = $this->db->where('c.departamento_id', $dpto);

        $query = $this->db
            ->select('c.*, e.nombre AS empresa, s.nombre AS sucursal, cl.nombre AS cliente, d.nombre AS departamento')
            ->join('flash_empresas e', 'e.id = c.empresa_id', 'left')
            ->join('flash_sucursales s', 's.id = c.sucursal_id')
            ->join('flash_clientes cl', 'cl.id = c.cliente_id')
            ->join('flash_clientes_departamentos d', 'd.id = c.departamento_id')
            ->order_by('c.fecha_pedido')
            ->get('flash_comprobantes_ingresos c');
        return $query->result();
    }

    function getComprobanteIngresoServicios($id){
        $query = $this->db
                        ->select('c.*, s.nombre AS servicio')
                        ->join('flash_servicios s', 's.id = c.servicio_id')
                        ->where('c.comprobante_ingreso_id', $id)
                        ->get('flash_comprobantes_ingresos_servicios c');
        return $query->result();
    }

    function getResponsablesComprobantes($id=null){
        if($id != null) $query = $this->db->where('s.sucursal_id', $id);

        $query = $this->db
                        ->select('c.*, s.nombre AS sucursal')
                        ->join('flash_sucursales s', 's.id = c.sucursal_id')
                        ->get('flash_piezas_talonarios_responsables c');
        return $query->result();
    }

    function getTalonarios($sucursal=null){
        if($sucursal != null) $query = $this->db->where('c.sucursal_id', $sucursal);

        $query = $this->db
                        ->select('t.*, c.apellido, c.nombre, s.nombre AS sucursal')
                        ->join('flash_piezas_talonarios_responsables c', 'c.id = t.responsable_id')
                        ->join('flash_sucursales s', 's.id = c.sucursal_id')
                        ->get('flash_piezas_talonarios t');
         $result = $query->result();
//       echo $this->db->last_query();die;
        return $query->result();
    }

    function getPiezas($id){
        $query = $this->db
                        ->select('p.*,p.comprobante_ingreso_id comprobante_ingreso_id, s.nombre AS servicio, e.nombre AS estado, t.nombre AS tipo, v.nombre AS verifico')
                        ->join('flash_comprobantes_ingresos_servicios is', 'is.id = p.servicio_id')
                        ->join('flash_servicios s', 's.id = is.servicio_id')
                        ->join('flash_piezas_estados_variables e', 'e.id = p.estado_id')
                        ->join('flash_piezas_tipos t', 't.id = p.tipo_id')
                        ->join('opciones_variables v', 'v.id = p.verifico_id','left')
                        ->where('p.comprobante_ingreso_id', $id)
                        ->get('flash_piezas p');
//        $result = $query->result();
//                     echo $this->db->last_query();die;
        return $query->result();
    }

    function getALLPiezas($hoja_ruta=null){
        $user_row = $this->ion_auth->user()->row();
        if (!$this->ion_auth->in_group(array(1, 2), $user_row->id)) $query = $this->db->where('ca.sucursal_id', $user_row->sucursal_id);

        if ($hoja_ruta != null) $query = $this->db->where('p.hoja_ruta_id', $hoja_ruta);

        $query = $this->db
                        //->select('p.*, s.nombre AS servicio, e.nombre AS estado, t.nombre AS tipo, v.nombre AS verifico, ci.numero AS comprobante, u.apellido AS usuario_apellido, u.nombre AS usuario_nombre, ca.apellido_nombre AS cartero, di.apellido_nombre AS distribuidor')
                        ->select('p.*, s.nombre AS servicio, t.nombre AS tipo, v.nombre AS verifico, ci.numero AS comprobante, u.apellido AS usuario_apellido, u.nombre AS usuario_nombre, ca.apellido_nombre AS cartero, di.apellido_nombre AS distribuidor')
                        ->join('flash_comprobantes_ingresos_servicios is', 'is.id = p.servicio_id', 'left')
                        ->join('flash_comprobantes_ingresos ci', 'ci.id = is.comprobante_ingreso_id', 'left')
                        ->join('flash_servicios s', 's.id = is.servicio_id', 'left')
                        //->join('flash_piezas_estados_variables e', 'e.id = p.estado_id', 'left')
                        ->join('flash_piezas_tipos t', 't.id = p.tipo_id', 'left')
                        ->join('opciones_variables v', 'v.id = p.verifico_id', 'left')
                        ->join('users u', 'u.id = p.usuario_id', 'left')
                        ->join('flash_sucursales_carteros ca', 'ca.id = p.cartero_id', 'left')
                        ->join('flash_distribuidores di', 'di.id = p.distribuidor_id', 'left')
                        ->get('flash_piezas p');
        return $query->result();
    }

    function getPiezasRendicion($empresa, $departamento){
        $query = $this->db
                        ->select('p.*, c.empresa_id, c.departamento_id')
                        ->join('flash_comprobantes_ingresos c', 'c.id = p.comprobante_id')
                        ->where('p.estado_id !=', 1)
                        ->where('p.rendicion_id', null)
                        ->where('c.empresa_id', $empresa)
                        ->where('c.departamento_id', $departamento)
                        ->get('flash_piezas p');
        return $query->result();
    }

    function getRendiciones($cliente_id, $rendicion_id, $is_user){
        $user_row = $this->ion_auth->user()->row();
        if($cliente_id != '')
             $this->db->where('clientes_id', $cliente_id);

        if($is_user)
             $this->db->where('sucursal_id', $user_row->sucursal_id);

        if($rendicion_id != '')
             $this->db->where('r.id', $rendicion_id);
        
        $query = $this->db
                        ->select('r.*,DATE_FORMAT(r.create,"%d-%m-%Y") as create, c.nombre_fantasia,c.nombre cliente, d.nombre AS departamento, u.nombre AS usuario_nombre, u.apellido AS usuario_apellido')
                        ->join('flash_clientes c', 'c.id = r.clientes_id')
                        ->join('flash_clientes_departamentos d', 'd.id = r.departamento_id')
                        ->join('users u', 'u.id = r.usuario_id')
                        ->get('flash_rendiciones r');
//        $result = $query->result();
//                     echo $this->db->last_query();die;
        return $query->result();
    }
    
    //Piezas que ya estan rendidas para mostrar en pantalla NO IMPRIMIR
    function getALLPiezasRendicion($id){
        $user_row = $this->ion_auth->user()->row();
        if (!$this->ion_auth->in_group(array(1, 2), $user_row->id)) $query = $this->db->where('u.sucursal_id', $user_row->sucursal_id);

        $query = $this->db
                        ->select('`p`.*,
                                    `s`.`nombre`           AS `servicio`,
                                    `e`.`nombre`           AS `estado`,
                                    `t`.`nombre`           AS `tipo`,
                                    `ci`.`numero`          AS `comprobante`,
                                    DATE_FORMAT(ci.create, "%d-%m-%Y") AS fecha_comprobante,
                                    `u`.`apellido`         AS `usuario_apellido`,
                                    `u`.`nombre`           AS `usuario_nombre`,
                                    `ca`.`apellido_nombre` AS `cartero`,
                                    `di`.`apellido_nombre` AS `distribuidor`,
                                    `fr`.`create` as fecha_rendicion`,
                                    `n`.`create` as fecha_estado,
                                    hdr.id AS hoja_ruta_id,
                                    fr.id AS rendicion_id,
                                    d.despacho_id')
                        ->join('flash_comprobantes_ingresos_servicios is', 'is.id = p.servicio_id', 'left')
                        ->join('flash_comprobantes_ingresos ci', 'ci.id = is.comprobante_ingreso_id', 'left')
                        ->join('flash_servicios s', 's.id = is.servicio_id', 'left')
                        ->join('flash_piezas_estados_variables e', 'e.id = p.estado_id', 'left')
                        ->join('flash_piezas_tipos t', 't.id = p.tipo_id', 'left')
                        ->join('flash_piezas_despacho_piezas d', 'p.id = d.pieza_id', 'left')
//                        ->join('users u', 'u.id = p.usuario_id', 'left')
                        ->join('flash_subpiezas sp', 'sp.pieza_id = p.id', 'left')
                        ->join('flash_hojas_rutas hdr', 'hdr.id = sp.hoja_ruta_id', 'left')
                        ->join('flash_sucursales_carteros ca', 'ca.id = hdr.cartero_id', 'left')
                        ->join('flash_distribuidores di', 'di.id = hdr.distribuidor_id', 'left')
                        ->join('flash_rendiciones_piezas r', 'r.pieza_id = p.id')
                        ->join('flash_rendiciones fr', 'fr.id = r.rendicion_id')
                        ->join('flash_piezas_novedades n', 'n.pieza_id = p.id','left')
                        ->join('users u', 'u.id = fr.usuario_id', 'left')
                        ->where('r.rendicion_id', $id)
                        ->get('flash_piezas p');
//        $result = $query->result();
//        echo $this->db->last_query();die;
        return $query->result();
    }
    //Piezas que ya estan rendidas imprimir
    function getALLPiezasRendicionAgrupadas($id){
        $user_row = $this->ion_auth->user()->row();
        if (!$this->ion_auth->in_group(array(1, 2), $user_row->id)) $query = $this->db->where('u.sucursal_id', $user_row->sucursal_id);

        $query1 = $this->db
                        ->select('  p.id AS pieza_id,
                                    `s`.`nombre`           AS `servicio`,
                                    `e`.`nombre`           AS `estado`,
                                    `t`.`nombre`           AS `tipo`,
                                    `ci`.`numero`          AS `comprobante`,
                                    DATE_FORMAT(ci.create, "%d-%m-%Y") AS `fecha_comprobante`,
                                    `u`.`apellido`         AS `usuario_apellido`,
                                    `u`.`nombre`           AS `usuario_nombre`,
                                    `ca`.`apellido_nombre` AS `cartero`,
                                    `di`.`apellido_nombre` AS `distribuidor`,
                                    `fr`.`create`          AS `fecha_rendicion`,
                                    `n`.`create`           AS `fecha_estado`,
                                    `hdr`.`id`             AS `hoja_ruta_id`,
                                    `fr`.`id`              AS `rendicion_id`,
                                    `d`.`despacho_id`,
                                    p.destinatario,
                                    p.domicilio,
                                    p.codigo_postal,
                                     1')
                        ->join('flash_comprobantes_ingresos_servicios cis', 'cis.id = p.servicio_id','left')
                        ->join('flash_comprobantes_ingresos ci', 'ci.id = cis.comprobante_ingreso_id','left')
                        ->join('flash_servicios s', 's.id = cis.servicio_id','left')
                        ->join('flash_piezas_estados_variables e', 'e.id = p.estado_id','left')
                        ->join('flash_piezas_tipos t', 't.id = p.tipo_id','left')
                        ->join('flash_piezas_despacho_piezas d', 'p.id = d.pieza_id','left')
//                        ->join('users u', 'u.id = p.usuario_id','left')
                        ->join('flash_subpiezas sp', 'sp.pieza_id = p.id','left')
                        ->join('flash_hojas_rutas hdr', 'hdr.id = sp.hoja_ruta_id','left')
                        ->join('flash_sucursales_carteros ca', 'ca.id = hdr.cartero_id','left')
                        ->join('flash_distribuidores di', 'di.id = hdr.distribuidor_id','left')
                        ->join('flash_rendiciones_piezas r', 'r.pieza_id = p.id')
                        ->join('flash_rendiciones fr', 'fr.id = r.rendicion_id')
                        ->join('flash_piezas_novedades n', 'n.pieza_id = p.id','left')
                        ->join('users u', 'u.id = fr.usuario_id','left')
                        ->where('r.rendicion_id = '.$id)
                        ->where('p.tipo_id = '.Pieza::TIPO_NORMAL)
                        ->get('`flash_piezas` `p`');
        $join1 = $this->db->last_query();
        $query2 = $this->db
                        ->select('  CONCAT(MIN(p.id),"-",MAX(p.id)) AS pieza_id,
                                    s.nombre           AS servicio,
                                    e.nombre           AS estado,
                                    t.nombre           AS tipo,
                                    ci.numero          AS comprobante,
                                    DATE_FORMAT(ci.create, "%d-%m-%Y") AS fecha_comprobante,
                                    u.apellido         AS usuario_apellido,
                                    u.nombre           AS usuario_nombre,
                                    ca.apellido_nombre AS cartero,
                                    di.apellido_nombre AS distribuidor,
                                    fr.create          AS fecha_rendicion,
                                    n.create           AS fecha_estado,
                                    hdr.id             AS hoja_ruta_id,
                                    fr.id              AS rendicion_id,
                                    d.despacho_id,
                                    p.destinatario,
                                    p.domicilio,
                                    p.codigo_postal,
                                    COUNT(*) AS cantidad')
                        ->join('flash_comprobantes_ingresos_servicios cis', 'cis.id = p.servicio_id','left')
                        ->join('flash_comprobantes_ingresos ci', 'ci.id = cis.comprobante_ingreso_id','left')
                        ->join('flash_servicios s', 's.id = cis.servicio_id','left')
                        ->join('flash_piezas_estados_variables e', 'e.id = p.estado_id','left')
                        ->join('flash_piezas_tipos t', 't.id = p.tipo_id','left')
                        ->join('flash_piezas_despacho_piezas d', 'p.id = d.pieza_id','left')
//                        ->join('users u', 'u.id = p.usuario_id','left')
                        ->join('flash_subpiezas sp', 'sp.pieza_id = p.id','left')
                        ->join('flash_hojas_rutas hdr', 'hdr.id = sp.hoja_ruta_id','left')
                        ->join('flash_sucursales_carteros ca', 'ca.id = hdr.cartero_id','left')
                        ->join('flash_distribuidores di', 'di.id = hdr.distribuidor_id','left')
                        ->join('flash_rendiciones_piezas r', 'r.pieza_id = p.id')
                        ->join('flash_rendiciones fr', 'fr.id = r.rendicion_id')
                        ->join('flash_piezas_novedades n', 'n.pieza_id = p.id','left')
                        ->join('users u', 'u.id = fr.usuario_id','left')
                        ->where('r.rendicion_id = '.$id)
                        ->where('p.tipo_id = '.Pieza::TIPO_SIMPLE)
                        ->group_by('ci.numero')
                        ->get('flash_piezas p');
        $join2 = $this->db->last_query();
        
        $union_query = $this->db->query($join1.' UNION '.$join2);
//        $result = $union_query->result();
//       echo($this->db->last_query());die;
        return $union_query->result();
        
//        $query = $this->db
//                        ->select('`p`.*,
//                                    `s`.`nombre`           AS `servicio`,
//                                    `e`.`nombre`           AS `estado`,
//                                    `t`.`nombre`           AS `tipo`,
//                                    `ci`.`numero`          AS `comprobante`,
//                                    DATE_FORMAT(ci.create, "%d-%m-%Y") AS fecha_comprobante,
//                                    `u`.`apellido`         AS `usuario_apellido`,
//                                    `u`.`nombre`           AS `usuario_nombre`,
//                                    `ca`.`apellido_nombre` AS `cartero`,
//                                    `di`.`apellido_nombre` AS `distribuidor`,
//                                    `fr`.`create` as fecha_rendicion`,
//                                    `n`.`create` as fecha_estado,
//                                    hdr.id AS hoja_ruta_id,
//                                    fr.id AS rendicion_id,
//                                    d.despacho_id')
//                        ->join('flash_comprobantes_ingresos_servicios is', 'is.id = p.servicio_id', 'left')
//                        ->join('flash_comprobantes_ingresos ci', 'ci.id = is.comprobante_ingreso_id', 'left')
//                        ->join('flash_servicios s', 's.id = is.servicio_id', 'left')
//                        ->join('flash_piezas_estados_variables e', 'e.id = p.estado_id', 'left')
//                        ->join('flash_piezas_tipos t', 't.id = p.tipo_id', 'left')
//                        ->join('flash_piezas_despacho_piezas d', 'p.id = d.pieza_id', 'left')
//                        ->join('users u', 'u.id = p.usuario_id', 'left')
//                        ->join('flash_subpiezas sp', 'sp.pieza_id = p.id', 'left')
//                        ->join('flash_hojas_rutas hdr', 'hdr.id = sp.hoja_ruta_id', 'left')
//                        ->join('flash_sucursales_carteros ca', 'ca.id = hdr.cartero_id', 'left')
//                        ->join('flash_distribuidores di', 'di.id = hdr.distribuidor_id', 'left')
//                        ->join('flash_rendiciones_piezas r', 'r.pieza_id = p.id')
//                        ->join('flash_rendiciones fr', 'fr.id = r.rendicion_id')
//                        ->join('flash_piezas_novedades n', 'n.pieza_id = p.id','left')
//                        ->where('r.rendicion_id', $id)
//                        ->get('flash_piezas p');
////        $result = $query->result();
////        echo $this->db->last_query();die;
//        return $query->result();
    }
    
    function getRendicionesXComprobantes($id){
        $query = $this->db
                        ->select('ci.numero, DATE_FORMAT(p.create, "%d-%m-%Y") AS fecha, count(*) AS cantidad')
                        ->join('flash_rendiciones_piezas rp', 'r.id = rp.rendicion_id')
                        ->join('flash_piezas p', 'p.id = rp.pieza_id')
                        ->join('flash_comprobantes_ingresos ci', 'p.comprobante_ingreso_id = ci.id')
                        ->where('r.id = '.$id)
                        ->group_by('ci.numero')
                        ->get('flash_rendiciones r');
//        $result = $query->result();
//        echo $this->db->last_query();die;
        return $query->result();
    }
    
    function getActualizarPreciosEspeciales($id){
        $query = $this->db
                        ->select('c.*,c.activo as activo, e.nombre, cl.id as cliente_id, cl.nombre as cliente_nombre,
                                    s.nombre as nombre_servicio, s.codigo as codigo_servicio, s.precio as precio_lista')
                        ->join('flash_clientes_precios_especiales e', 'e.id = c.cliente_precio_especial_id')
                        ->join('flash_clientes cl', 'cl.id = e.cliente_id')
                        ->join('flash_servicios s', 's.id = e.servicio_id')
                        ->where('c.actualizacion_id', $id)
                        ->get('flash_actualizacion_precios_especiales c');
        return $query->result();
    }

    function getActualizarPreciosServicio($id){
        $query = $this->db
                        ->select('c.*, e.nombre')
                        ->join('flash_servicios e', 'e.id = c.servicio_id')
                        ->where('c.actualizacion_id', $id)
                        ->get('flash_actualizacion_precios_servicios c');
        return $query->result();
    }

    function getActualizarPrecios($tipo_id = null){
        $query = $this->db
                        ->select('p.*,  DATE_FORMAT(p.fecha_creacion,"%d-%m-%Y") AS fecha_creacion_format 
                                     ,  DATE_FORMAT(p.fecha_aprobacion,"%d-%m-%Y") AS fecha_aprobacion_format
                                     ,  DATE_FORMAT(p.fecha_rechazo,"%d-%m-%Y") AS fecha_rechazo_format
                                    ,   c.nombre AS c_nombre, c.apellido AS c_apellido
                                    ,   a.nombre AS a_nombre, a.apellido AS a_apellido
                                    ,   r.nombre AS r_nombre, r.apellido AS r_apellido
                                    , e.nombre AS tipo')
                        ->join('users c', 'c.id = p.usuario_creacion_id', 'left')
                        ->join('users a', 'a.id = p.usuario_aprobacion_id', 'left')
                        ->join('users r', 'r.id = p.usuario_rechazo_id', 'left')
                        ->join('opciones_variables e', 'e.id = p.tipo_id', 'left')
                        ->where('p.tipo_id = '.$tipo_id)
                        ->order_by('p.fecha_creacion', 'desc')
                        ->get('flash_actualizacion_precios p');
        return $query->result();
    }
    
    function getActualizarPreciosEspecialesPendientes($id=null){
        $query = $this->db
                        ->select('pe.*, c.nombre as cliente_nombre')
                        ->join('flash_clientes c', 'c.id = pe.cliente_id', 'inner')
                        ->where('pe.fecha_aprobacion', '0000-00-00 00:00:00')
                        ->get('flash_actualizacion_precios_especiales pe');
        return $query->result();
    }
    
    function getComprobantesGenerados($talonario_id = null){
        $user_row = $this->ion_auth->user()->row();
        if (!$this->ion_auth->in_group(array(1, 2), $user_row->id)) $query = $this->db->where('r.sucursal_id', $user_row->sucursal_id);
        if ($talonario_id != null) $query = $this->db->where('t.id', $talonario_id);
        
        $query = $this->db
                        ->select('c.*, r.sucursal_id')
                        ->join('flash_piezas_talonarios t', 't.id = c.talonario_id')
                        ->join('flash_piezas_talonarios_responsables r', 'r.id = t.responsable_id')
                        ->get('flash_comprobantes_ingresos_generados c');
        return $query->result();
    }

    function getComprobantesPiezasSimples($filtroEstado = false, $excluidas = NULL){
        ini_set('memory_limit','-1');
        $user_row = $this->ion_auth->user()->row();
        if (!$this->ion_auth->in_group(array(1, 2), $user_row->id)) $query = $this->db->where('r.sucursal_id', $user_row->sucursal_id);
        if ($filtroEstado) $query = $this->db->where('p.estado_id IN ('.Pieza::ESTADO_EN_GESTION.','. Pieza::ESTADO_EN_DISTRIBUCION.','. Pieza::ESTADO_NO_RESPONDE.','. Pieza::ESTADO_EN_TRNSITO.')');
        
//        if ($excluidas != null){
//            $this->db->group_start();
//            //$excluidas = implode(',',$excluidas);
//            $sale_ids_chunk = array_chunk($excluidas,25);
//            foreach($sale_ids_chunk as $sale_ids)
//            {
//                $this->db->where_not_in('p.id', $sale_ids);
//            }
//            $this->db->group_end();
//            //$query = $this->db->where('p.id NOT IN ('.$excluidas.')');
//        }
        $query = $this->db
                        ->select('DISTINCT(comprobante_ingreso_id),c.*, r.sucursal_id')
                        ->join('flash_comprobantes_ingresos c', 'c.id = p.comprobante_ingreso_id')
                        ->join('flash_piezas_talonarios t', 't.id = c.talonario_id')
                        ->join('flash_piezas_talonarios_responsables r', 'r.id = t.responsable_id')
                        ->where('tipo_id = '.Pieza::TIPO_SIMPLE)
                        ->get('flash_piezas p');
        //$result = $query->result();
        //echo($this->db->last_query());die;
        return $query->result();
    }
    
    function getComprobantesPiezasSimplesNovedades(){
        $user_row = $this->ion_auth->user()->row();
        //if ($filtroEstado) $query = $this->db->where('p.estado_id IN ('.Pieza::ESTADO_EN_GESTION.','. Pieza::ESTADO_EN_DISTRIBUCION.','. Pieza::ESTADO_NO_RESPONDE.','. Pieza::ESTADO_EN_TRNSITO.')');
        $excluidas = $this->db
                        ->select('ev.id') 
                        ->join('flash_piezas_estados_variables ev', 'e.id = ev.pieza_estado_id')
                        ->where('e.id = '.PiezaEstado::ESTADOS_RENDICIONES)
                        ->get('flash_piezas_estados e');
        $array_excluidas = [];
        foreach($excluidas->result() as $value){
            $array_excluidas[] = $value->id;
        }
        
        if (!$this->ion_auth->in_group(array(1, 2), $user_row->id)) $query = $this->db->where('r.sucursal_id', $user_row->sucursal_id);
        
        $query = $this->db
                        ->select('DISTINCT(comprobante_ingreso_id),c.*, r.sucursal_id')
                        ->join('flash_comprobantes_ingresos c', 'c.id = p.comprobante_ingreso_id')
                        ->join('flash_piezas_talonarios t', 't.id = c.talonario_id')
                        ->join('flash_piezas_talonarios_responsables r', 'r.id = t.responsable_id')
                        ->where('p.estado_id NOT IN ('.implode(',', $array_excluidas).')')
                        ->where('tipo_id = '.Pieza::TIPO_SIMPLE)
                        ->get('flash_piezas p');
        $result = $query->result();
//        echo($this->db->last_query());die;
        return $query->result();
    }
    
    function getEstadosPiezasSimples(){
        $user_row = $this->ion_auth->user()->row();
        $query = $this->db
                        ->select('*')
                        ->where('p.pieza_estado_id = '.PiezaEstado::ESTADOS_RENDICIONES)
                        ->where('p.id NOT IN ('.Pieza::ESTADO_ENTREGADA.','.
                                            Pieza::ESTADO_EN_DISTRIBUCION.','.   
                                            Pieza::ESTADO_EN_GESTION.','.   
                                            Pieza::ESTADO_EN_TRNSITO.','.   
                                            Pieza::ESTADO_OTRO.','.   
                                            Pieza::ESTADO_RECIBIDA.')')
                
                        ->get('flash_piezas_estados_variables p');
        return $query->result();
    }
    
    function getHojasRuta(){
        if ($this->ion_auth->isAdmin())
        {
            return Hoja::all();
        }
        else
        {
            $sucursal_id = $this->ion_auth->user()->row()->sucursal_id;
            return Hoja::join(Zona::TABLE, Hoja::TABLE.'.zona_id', '=', Zona::TABLE.'.id')
                ->where(Zona::TABLE.".sucursal_id", $sucursal_id)
                ->get();
        }
    }
    
    function getPiezasPorServiciosPorComprobante($comprobante_id){
        $user_row = $this->ion_auth->user()->row();
        $query1 = $this->db
                        ->select('s.*,
                                p.cantidad AS cantidad_piezas, 
                                svc.nombre AS servicio_nombre, 
                                pev.nombre AS pieza_estado_nombre,
                                pt.nombre AS pieza_tipo_nombre,
                                p.barcode AS barcode,
                                p.barcode_externo AS barcode_externo,
                                p.datos_varios,
                                p.datos_varios_1,
                                p.datos_varios_2,
                                p.create AS creacion,
                                s.create AS crea,
                                p.destinatario')
                        ->join('flash_comprobantes_ingresos_servicios s', 's.id = p.servicio_id')
                        ->join('flash_servicios svc', 'svc.id = s.servicio_id')
                        ->join('flash_piezas_estados pev', 'pev.id = p.estado_id')
                        ->join('flash_piezas_tipos pt', 'pt.id = p.tipo_id')
                        ->where('p.comprobante_ingreso_id = '.$comprobante_id)
                        ->where('p.tipo_id = '.Pieza::TIPO_NORMAL)
                        ->get('flash_piezas p');
        $join1 = $this->db->last_query();
        $query2 = $this->db
                        ->select('s.*,
                                (select count(id) 
                                from flash_piezas 
                                where comprobante_ingreso_id = '.$comprobante_id.'
                                and tipo_id = '.Pieza::TIPO_SIMPLE.') AS cantidad_piezas, 
                                svc.nombre AS servicio_nombre, 
                                pev.nombre AS pieza_estado_nombre,
                                pt.nombre AS pieza_tipo_nombre,
                                p.barcode AS barcode,
                                p.barcode_externo AS barcode_externo,
                                p.datos_varios,
                                p.datos_varios_1,
                                p.datos_varios_2,
                                p.create AS creacion,
                                s.create AS crea,
                                p.destinatario')
                        ->join('flash_comprobantes_ingresos_servicios s', 's.id = p.servicio_id')
                        ->join('flash_servicios svc', 'svc.id = s.servicio_id')
                        ->join('flash_piezas_estados_variables pev', 'pev.id = p.estado_id')
                        ->join('flash_piezas_tipos pt', 'pt.id = p.tipo_id')
                        ->where('p.comprobante_ingreso_id = '.$comprobante_id)
                        ->where('p.tipo_id = '.Pieza::TIPO_SIMPLE)
                        ->group_by('s.servicio_id')
                        ->get('flash_piezas p');
        $join2 = $this->db->last_query();
        
        $union_query = $this->db->query($join1.' UNION '.$join2);
//        $result = $union_query->result();
//       echo($this->db->last_query());die;
        return $union_query->result();
    }
    
    function getServiciosPorComprobante($comprobante_id){
        $user_row = $this->ion_auth->user()->row();
        $query = $this->db
                        ->select('s.id, s.nombre, cis.disponible, cis.id comprobante_servicio_id')
                        ->join('flash_servicios s', 's.id = cis.servicio_id')
                        ->where('cis.comprobante_ingreso_id = '.$comprobante_id)
                        ->get('flash_comprobantes_ingresos_servicios cis');
//        echo($this->db->last_query());die;
        return $query->result();
    }
    
     function getPiezasNormalesPorEstadoDisponiblesHDR($estado){
         ini_set('memory_limit','-1');
        $user_row = $this->ion_auth->user()->row();
//        $subquery = $this->db
//                    ->select('s.pieza_id, p.comprobante_ingreso_id, c.numero')
//                    ->join('flash_piezas p', 'p.id = s.pieza_id')
//                    ->join('flash_comprobantes_ingresos c', 'c.id = p.comprobante_ingreso_id')
//                    ->get('flash_subpiezas s');
//        
//        $subjson = $subquery->result();
//        //echo($this->db->last_query());die;
//        $array_excluidas = [];
//        foreach ($subjson as $value) {
//            $array_excluidas[] = $value->pieza_id;
//        }
//        $this->db->group_start();
//        $sale_ids_chunk = array_chunk($array_excluidas,25);
////            var_dump($sale_ids_chunk);die;
//        foreach($sale_ids_chunk as $sale_ids)
//        {
//            $this->db->where_not_in('p.id', $sale_ids);
//        }
//        $this->db->group_end();
//        $no_disponibles = "";
//            
//        foreach ($subjson as $value) {
//            if ($no_disponibles == ""){ $no_disponibles = $value->pieza_id;}
//            else{$no_disponibles = $no_disponibles.",".$value->pieza_id;}
//        }
//        
       // if ($no_disponibles=="") $no_disponibles=0;
            
        $query = $this->db
                        ->select('p.*, s.id AS servicio_id, s.nombre AS nombre_servicio,p.estado_id,p.id,  c.numero as comprobante_nro') 
                        ->join('flash_comprobantes_ingresos_servicios cis', 'cis.id = p.servicio_id')
                        ->join('flash_servicios s', 's.id = cis.servicio_id')
                        ->join('flash_piezas_estados_variables pev', 'pev.id = p.estado_id')
                        ->join('flash_comprobantes_ingresos c', 'c.id = p.comprobante_ingreso_id')
                        ->join('flash_subpiezas sp', 'p.id = sp.pieza_id', 'left')
                        ->where('pev.pieza_estado_id IN ('.$estado.')')
                        ->where('sp.id IS NULL')
                        ->get('flash_piezas p');
//        $result = $query->result();
        //echo($this->db->last_query());die;
        return $query->result();
     }
    
     function getPiezasNormalesPorEstadoDisponiblesNovedades($estado){
        $user_row = $this->ion_auth->user()->row();
        $is_user = $this->ion_auth->in_group(array(3), $user_row->id);
        
        if($is_user)
            $query = $this->db->where('p.sucursal_id', $user_row->sucursal_id);    
        
        $query = $this->db
                        ->select('p.*, s.id AS servicio_id, s.nombre AS nombre_servicio,p.estado_id,p.id,  c.numero as comprobante_nro') 
                        ->join('flash_comprobantes_ingresos_servicios cis', 'cis.id = p.servicio_id')
                        ->join('flash_servicios s', 's.id = cis.servicio_id')
                        ->join('flash_piezas_estados_variables pev', 'pev.id = p.estado_id')
                        ->join('flash_comprobantes_ingresos c', 'c.id = p.comprobante_ingreso_id')
                        ->where('pev.pieza_estado_id IN ('.$estado.')')
                        ->where('p.tipo_id = 2') //Solo trae las piezas normales
                        ->get('flash_piezas p');
//        $result = $query->result();
//        echo($this->db->last_query());die;
        return $query->result();
     }
     
     function getPiezasNormalesDisponiblesNovedades(){
        $user_row = $this->ion_auth->user()->row();
        $is_user = $this->ion_auth->in_group(array(3), $user_row->id);
        
        $excluidas = $this->db
                        ->select('ev.id') 
                        ->join('flash_piezas_estados_variables ev', 'e.id = ev.pieza_estado_id')
                        ->where('e.id = '.PiezaEstado::ESTADOS_RENDICIONES)
                        ->get('flash_piezas_estados e');
        $array_excluidas = [];
        foreach($excluidas->result() as $value){
            $array_excluidas[] = $value->id;
        }
        
        if(!$this->ion_auth->is_admin($user_row->id))  $query = $this->db->where('p.sucursal_id', $user_row->sucursal_id);  
        
        $query = $this->db
                        ->select('p.*, s.id AS servicio_id, s.nombre AS nombre_servicio,p.estado_id,p.id,  c.numero as comprobante_nro') 
                        ->join('flash_comprobantes_ingresos_servicios cis', 'cis.id = p.servicio_id')
                        ->join('flash_servicios s', 's.id = cis.servicio_id')
                        ->join('flash_piezas_estados_variables pev', 'pev.id = p.estado_id')
                        ->join('flash_comprobantes_ingresos c', 'c.id = p.comprobante_ingreso_id')
                        ->where('p.estado_id NOT IN ('.implode(',', $array_excluidas).')')
                        ->where('p.tipo_id = '.Pieza::TIPO_NORMAL) //Solo trae las piezas normales
                        ->get('flash_piezas p');
//        $result = $query->result();
        //echo($this->db->last_query());die;
        return $query->result();
     }
     
     function getDespachoPiezasAgrupadas($despacho_id){
         
        $query1 = $this->db
                       ->select('   fp.*,p.*, fd.*, 
                                    COUNT(*) cantidad,
                                    CONCAT((p.destinatario),(", "),(p.domicilio),(", "),( p.codigo_postal),(", "),( p.localidad)) as descripcion,
                                    ev.nombre AS estado,
                                    ci.numero comprobante_ingreso')
                       ->join('flash_piezas_despacho fd', 'fd.id = fp.despacho_id')
                       ->join('flash_piezas p', 'p.id = fp.pieza_id')
                       ->join('flash_comprobantes_ingresos ci', 'ci.id = p.comprobante_ingreso_id','left')
                       ->join('flash_piezas_estados_variables ev', 'ev.id = p.estado_id')
                       ->where('p.tipo_id = 1')
                       ->where('fd.id = '.$despacho_id )
                       ->group_by('comprobante_ingreso_id,servicio_id')
                       ->get('flash_piezas_despacho_piezas fp');
        $join1 = $this->db->last_query();
        
        $query2 = $this->db
                       ->select('   fp.*,p.*, fd.*, 
                                    "" as cantidad,
                                    CONCAT((p.destinatario),(", "),(p.domicilio),(", "),( p.codigo_postal),(", "),( p.localidad)) as descripcion,
                                    ev.nombre AS estado,
                                    ci.numero comprobante_ingreso')
                        ->join('flash_piezas_despacho fd', 'fd.id = fp.despacho_id')
                       ->join('flash_piezas p', 'p.id = fp.pieza_id')
                       ->join('flash_comprobantes_ingresos ci', 'ci.id = p.comprobante_ingreso_id','left')
                       ->join('flash_piezas_estados_variables ev', 'ev.id = p.estado_id')
                       ->where('p.tipo_id = 2')
                       ->where('fd.id = '.$despacho_id )
                       ->get('flash_piezas_despacho_piezas fp');
       $join2 = $this->db->last_query();
        
        $union_query = $this->db->query($join1.' UNION '.$join2);
//        $result = $union_query->result();
//       echo($this->db->last_query());die;
        return $union_query->result();
     }
/*
    function getPiezasDisponibles()
    {
        $user_row = $this->ion_auth->user()->row();
        if (!$this->ion_auth->in_group(array(1, 2), $user_row->id)) $query = $this->db->where('p.sucursal_id', $user_row->sucursal_id);

        $query = $this->db
            ->select('p.*')
            ->join('users u', 'u.id = p.usuario_id', 'left')
            //->where_in('p.estado_id', array(Pieza::ESTADO_EN_GESTION, Pieza::ESTADO_EN_DISTRIBUCION))
            //->where('p.rendicion_id', null)
            ->get('flash_piezas p');

        $piezas = $query->result();

        foreach ($piezas as $i => &$pieza)
        {
            if($pieza->estado_id == Pieza::ESTADO_EN_DISTRIBUCION)
            {
                $query = $this->db
                    ->select('sum(cantidad) as total', false)
                    ->where('pieza_id', $pieza->id)
                    ->get('flash_subpiezas');
                $distribuidas = $query->row('total');
                if($distribuidas == $pieza->cantidad)
                {
                    unset($piezas[$i]);
                }
                else
                {
                    $pieza->cantidad = $pieza->cantidad - $distribuidas;
                }
            }
        }
        return $piezas;
    }
*/
    function getPiezasNovedades()
    {
        return $this->codegen_model->get('flash_piezas','*','estado_id != 1 AND estado_id != 3');

        $user_row = $this->ion_auth->user()->row();
        if (!$this->ion_auth->in_group(array(1, 2), $user_row->id)) $query = $this->db->where('p.sucursal_id', $user_row->sucursal_id);

        $query = $this->db
            ->select('p.*')
            ->join('users u', 'u.id = p.usuario_id', 'left')
            ->where_in('p.estado_id', Pieza::ESTADO_EN_DISTRIBUCION)
            ->where('p.rendicion_id', null)
            ->get('flash_piezas p');

        $piezas = $query->result();

        foreach ($piezas as $i => &$pieza)
        {
            if($pieza->estado_id == Pieza::ESTADO_EN_DISTRIBUCION)
            {
                $query = $this->db
                    ->select('sum(cantidad) as total', false)
                    ->where('pieza_id', $pieza->id)
                    ->get('flash_hojas_rutas_piezas');
                $distribuidas = $query->row('total');
                if($distribuidas == $pieza->cantidad)
                {
                    unset($piezas[$i]);
                }
                else
                {
                    $pieza->cantidad = $pieza->cantidad - $distribuidas;
                }
            }
        }
        return $piezas;
    }
    
    function getRetiroCorrespondencia($estado = NULL, $sucursal_id = NULL){
        $user_row = $this->ion_auth->user()->row();
        if (!$this->ion_auth->in_group(array(1, 2), $user_row->id)) $query = $this->db->where('r.sucursal_id', $user_row->sucursal_id);
        if ($this->ion_auth->in_group(array(1, 2), $user_row->id)) $query = $this->db->where('r.sucursal_id', $sucursal_id);
        
        $query = $this->db
            ->select('r.*, DATE_FORMAT(r.fecha_retirar,"%d-%m-%Y") AS fecha_retirar,c.nombre AS nombre_cliente, pe.nombre AS nombre_servicio')
            ->join('flash_clientes c', 'c.id = r.cliente_id', 'left')
            ->join('flash_clientes_precios_especiales pe', 'pe.id = r.servicio_id','left')
            ->where('r.estado like "'.$estado.'"')
            ->order_by('r.fecha_retirar DESC')
            ->get('flash_retiro_correspondencia r');
//        $result = $query->result();
//        echo($this->db->last_query());die;
        return $query->result();
    }
    
    function getRetiroCorrespondenciaXCliente($estado = NULL, $cliente_id = NULL){
        $query = $this->db
            ->select('r.*, c.nombre AS nombre_cliente, pe.nombre AS nombre_servicio')
            ->join('flash_clientes c', 'c.id = r.cliente_id', 'left')
            ->join('flash_clientes_precios_especiales pe', 'pe.servicio_id = r.servicio_id','left')
            ->where('r.estado like "'.$estado.'"')
            ->where('r.cliente_id = "'.$cliente_id.'"')
            ->get('flash_retiro_correspondencia r');
        return $query->result();
    }
    
    function getViewRetiroCorrespondencia($id){
        $query = $this->db
            ->select('r.*, c.nombre AS nombre_cliente, pe.nombre AS nombre_servicio')
            ->join('flash_clientes c', 'c.id = r.cliente_id', 'left')   
            ->join('flash_clientes_precios_especiales pe', 'pe.id = r.servicio_id','left')
            ->where('r.id = "'.$id.'"')
            ->get('flash_retiro_correspondencia r');
        return $query->result();
    }
    
    function getInfoCarterosXZona($liquidacion_carteros_id,$periodo_desde,$periodo_hasta){
        $user_row = $this->ion_auth->user()->row();
        if (!$this->ion_auth->in_group(array(1, 2), $user_row->id)) $query = $this->db->where('sc.sucursal_id', $user_row->sucursal_id);

        $query = $this->db
                        ->select('z.id, z.nombre zona, cis.servicio_id, s.nombre servicio, c.nombre cliente, pe.precio, COUNT(*) suma_piezas')
                        ->join('flash_piezas p','p.id = sp.pieza_id')
                        ->join('flash_hojas_rutas hdr', 'hdr.id = sp.hoja_ruta_id')
                        ->join('flash_comprobantes_ingresos ci', ' ci.id = p.comprobante_ingreso_id')
                        ->join('flash_comprobantes_ingresos_servicios cis', ' cis.id = p.servicio_id AND ci.id = cis.comprobante_ingreso_id')
                        ->join('flash_servicios s', 's.id = cis.servicio_id')
                        ->join('flash_clientes c', ' c.id = ci.cliente_id')
                        ->join('flash_clientes_precios_especiales pe', 'pe.servicio_id = cis.servicio_id AND pe.cliente_id = ci.cliente_id')
                        ->join('flash_sucursales_carteros sc', 'sc.id = hdr.cartero_id')
                        ->join('flash_sucursales_zonas z', ' z.id = hdr.zona_id')
                        ->where('hdr.fecha_baja >= "'. $periodo_desde .'"')
                        ->where('hdr.fecha_baja <= "'. $periodo_hasta .'"')
                        ->group_by('ci.cliente_id, p.servicio_id, hdr.zona_id')
                        ->order_by('z.id')
                        ->get('flash_subpiezas sp');
       // $result = $query->result();
       // echo($this->db->last_query());die;
        return $query->result();
    }
    
    function getInfoCarterosXCarteros($liquidacion_cartero_id,$periodo_desde,$periodo_hasta){
        $user_row = $this->ion_auth->user()->row();
        if (!$this->ion_auth->in_group(array(1, 2), $user_row->id)) $query = $this->db->where('sc.sucursal_id', $user_row->sucursal_id);

        $query = $this->db
                        ->select('sc.id, sc.apellido_nombre cartero, s.nombre servicio, c.nombre cliente, hdr.id hoja_ruta, pe.precio, COUNT(distinct(p.id)) suma_piezas, tc.nombre tipo_cartero')
                        ->join('flash_piezas p','p.id = sp.pieza_id')
                        ->join('flash_hojas_rutas hdr', 'hdr.id = sp.hoja_ruta_id')
                        ->join('flash_comprobantes_ingresos ci', 'ci.id = p.comprobante_ingreso_id')
                        ->join('flash_comprobantes_ingresos_servicios cis', 'cis.id = p.servicio_id AND ci.id = cis.comprobante_ingreso_id')
                        ->join('flash_servicios s', 's.id = cis.servicio_id')
                        ->join('flash_clientes c', 'c.id = ci.cliente_id')
                        ->join('flash_clientes_precios_especiales pe', 'pe.servicio_id = cis.servicio_id AND pe.cliente_id = ci.cliente_id	')
                        ->join('flash_sucursales_carteros sc', 'sc.id = hdr.cartero_id')
                        ->join('flash_sucursales_zonas z', ' z.id = hdr.zona_id')
                        ->join('flash_sucursales_carteros_tipos tc', ' sc.cartero_tipo_id = tc.id','left')
                        ->where('hdr.fecha_baja >= "'. $periodo_desde .'"')
                        ->where('hdr.fecha_baja <= "'. $periodo_hasta .'"')
                        ->group_by('ci.cliente_id, p.servicio_id, hdr.cartero_id, hdr.id')
                        ->order_by('sc.apellido_nombre')
                        ->get('flash_subpiezas sp');
//        $result = $query->result();
//       echo($this->db->last_query());die;
        return $query->result();
    }
    
    function getInfoCarterosXDevoluciones($liquidacion_cartero_id){
        $user_row = $this->ion_auth->user()->row();
        if (!$this->ion_auth->in_group(array(1, 2), $user_row->id)) $query = $this->db->where('sc.sucursal_id', $user_row->sucursal_id);

        $query = $this->db
                        ->select('sc.id, sc.apellido_nombre cartero, s.id id_servicio, s.nombre servicio, n.estado_nuevo_id id_estado, ev.nombre,  count(distinct(sp.pieza_id)) suma_piezas')
                        ->join('flash_subpiezas sp',' hdr.id = sp.hoja_ruta_id')
                        ->join('flash_sucursales_carteros sc', 'hdr.cartero_id = sc.id')
                        ->join('flash_piezas_novedades n', 'n.pieza_id = sp.pieza_id')
                        ->join('flash_liquidaciones_carteros_detalles lcd', 'lcd.hoja_de_ruta_id = hdr.id')
                        ->join('flash_piezas p', 'sp.pieza_id = p.id')
                        ->join('flash_comprobantes_ingresos ci', 'p.comprobante_ingreso_id = ci.id')
                        ->join('flash_comprobantes_ingresos_servicios cis', 'ci.id = cis.comprobante_ingreso_id AND p.servicio_id = cis.id')
                        ->join('flash_servicios s', 's.id = cis.servicio_id')
                        ->join('flash_piezas_estados_variables ev', 'ev.id = n.estado_nuevo_id')
                        ->where('n.estado_nuevo_id IN (4,5,6,7,8,9,10,11)')
                        ->where('lcd.liquidacion_cartero_id = '.$liquidacion_cartero_id)
                        ->group_by('n.estado_nuevo_id, s.id, sc.id')
                        ->order_by('sc.id, s.id, ev.id')
                        ->get('flash_hojas_rutas hdr');
        //$result = $query->result();
        //echo($this->db->last_query());die;
        return $query->result();
    }
   
    function getHojasRutasRendidas($liquidacion_cartero_id){
        $user_row = $this->ion_auth->user()->row();
        if (!$this->ion_auth->in_group(array(1, 2), $user_row->id)) $query = $this->db->where('sc.sucursal_id', $user_row->sucursal_id);

        $query = $this->db
                        ->select('hdr.id, sc.apellido_nombre, hdr.fecha_baja, DATE_FORMAT(hdr.create,"%d-%m-%Y") fecha_creacion')
                        ->join('flash_sucursales_carteros sc','hdr.cartero_id = sc.id')
                        ->join('flash_liquidaciones_carteros_detalles lcd', 'lcd.hoja_de_ruta_id = hdr.id')
                        ->where('hdr.fecha_baja IS NOT NULL')
                        ->where('lcd.liquidacion_cartero_id = '.$liquidacion_cartero_id)
                        ->group_by('hdr.id')
                        ->get('flash_hojas_rutas hdr');
       // $result = $query->result();
      // echo($this->db->last_query());die;
        return $query->result();
    }
    
    function getHojasRutasDespachadas($liquidacion_cartero_id){
        $user_row = $this->ion_auth->user()->row();
        if (!$this->ion_auth->in_group(array(1, 2), $user_row->id)) $query = $this->db->where('sc.sucursal_id', $user_row->sucursal_id);

        $query = $this->db
                        ->select('hdr.id, sc.apellido_nombre, hdr.fecha_baja, DATE_FORMAT(hdr.create,"%d-%m-%Y") fecha_creacion  ')
                        ->join('flash_sucursales_carteros sc','hdr.cartero_id = sc.id')
                        ->join('flash_liquidaciones_carteros_detalles lcd', 'lcd.hoja_de_ruta_id = hdr.id')
                        ->where('lcd.liquidacion_cartero_id = '.$liquidacion_cartero_id)
                        ->group_by('hdr.id')
                        ->get('flash_hojas_rutas hdr');
        //$result = $query->result();
        //echo($this->db->last_query());die;
        return $query->result();
    }
    
    public function getConsultasGlobales($fecha_ingreso,$pieza_id, $barra_externa, $comprobante,$cliente, $servicio, 
            $cartero, $hoja_ruta_id, $despacho_id, $sucursal,$estado,$destinatario,$domicilio,$codigo_postal,
            $localidad,$fecha_cambio_estado,$visitas,$rendicion_id,$recibio,$documento,$vinculo,$datos_varios_1,$datos_varios_2,$datos_varios_3){
            if ($fecha_ingreso){
                $fecha_ingreso = new DateTime($fecha_ingreso);
                $query = $this->db->where('ci.create >= ', $fecha_ingreso->format('Y-m-d 00:00:00'));
            }
            if ($pieza_id != '') $query = $this->db->where('p.id =', (int)$pieza_id);
            if ($barra_externa != '') $query = $this->db->like('p.barcode_externo',$barra_externa );
            if ($comprobante != '') $query = $this->db->like('ci.numero',$comprobante );
            if ($cliente != '') $query = $this->db->like('c.nombre',$cliente );
            if ($servicio != '') $query = $this->db->like('s.nombre',$servicio );
            if ($cartero != '') $query = $this->db->like('sc.apellido_nombre',$cartero );
            if ($hoja_ruta_id != '') $query = $this->db->where('hdr.id = ',$hoja_ruta_id );
            if ($despacho_id != '') $query = $this->db->where('d.id = ',$despacho_id );
            if ($sucursal != '') $query = $this->db->like('suc.nombre',$sucursal );
            if ($estado != '') $query = $this->db->like('ev.nombre',$estado );
            if ($destinatario != '') $query = $this->db->like('p.destinatario',$destinatario );
            if ($domicilio != '') $query = $this->db->like('p.domicilio',$domicilio );
            if ($codigo_postal != '') $query = $this->db->like('p.codigo_postal',$codigo_postal );
            if ($localidad != '') $query = $this->db->like('p.localidad',$localidad );
            
            if($fecha_cambio_estado){
                $fecha_cambio_estado = new DateTime($fecha_cambio_estado);
                $query = $this->db->where('n.update >= ', $fecha_cambio_estado->format('Y-m-d 00:00:00'));
            }
            if ($rendicion_id != '') $query = $this->db->where('rp.rendicion_id = ',$rendicion_id );
            if ($recibio != '') $query = $this->db->like('p.recibio',$recibio );
            if ($documento != '') $query = $this->db->like('p.documento',$documento );
            if ($vinculo != '') $query = $this->db->like('p.vinculo',$vinculo );
            if ($datos_varios_1 != '') $query = $this->db->like('p.datos_varios',$datos_varios_1 );
            if ($datos_varios_2 != '') $query = $this->db->like('p.datos_varios_1',$datos_varios_2 );
            if ($datos_varios_3 != '') $query = $this->db->like('p.datos_varios_2',$datos_varios_3 );

           $query = $this->db
                            ->select(' (CASE WHEN ci.create IS NULL THEN "" ELSE DATE_FORMAT(ci.create,"%d-%m-%Y") END) AS fecha_ingreso ,
                                        (CASE WHEN p.id IS NULL THEN "" ELSE p.id END) AS pieza_id ,
                                        (CASE WHEN p.barcode_externo IS NULL OR p.barcode_externo = "" THEN "" ELSE p.barcode_externo END) AS barcode_externo ,
                                        (CASE WHEN ci.numero IS NULL THEN "" ELSE ci.numero END) AS comprobante ,
                                        (CASE WHEN c.nombre IS NULL THEN "" ELSE c.nombre END) AS cliente ,
                                        (CASE WHEN s.nombre IS NULL THEN "" ELSE s.nombre END) AS servicio ,
                                        (CASE WHEN sc.apellido_nombre IS NULL THEN "" ELSE sc.apellido_nombre END) AS cartero ,
                                        (CASE WHEN hdr.id IS NULL THEN "" ELSE hdr.id END) AS hoja_ruta_id ,
                                        (CASE WHEN d.id IS NULL THEN "" ELSE d.id END) AS despacho_id ,
                                        (CASE WHEN suc.nombre IS NULL THEN "" ELSE suc.nombre END) AS sucursal ,
                                        (CASE WHEN n.estado_nuevo_id IS NULL THEN "" ELSE n.estado_nuevo_id END) AS estado_nuevo_id ,
                                        (CASE WHEN p.destinatario IS NULL THEN "" ELSE p.destinatario END) AS destinatario ,
                                        (CASE WHEN p.domicilio IS NULL THEN "" ELSE p.domicilio END) AS domicilio ,
                                        (CASE WHEN p.codigo_postal IS NULL THEN "" ELSE p.codigo_postal END) AS codigo_postal ,
                                        (CASE WHEN p.localidad IS NULL THEN "" ELSE p.localidad END) AS localidad ,
                                        (CASE WHEN n.update IS NULL THEN "" ELSE DATE_FORMAT(n.update,"%d-%m-%Y") END) AS fecha_cambio_estado ,
                                        (CASE WHEN ev.nombre IS NULL THEN "" ELSE ev.nombre END) AS estado_nuevo ,
                                        (CASE WHEN rp.rendicion_id IS NULL THEN "" ELSE rp.rendicion_id END) AS rendicion_id ,
                                        (CASE WHEN p.recibio IS NULL THEN "" ELSE p.recibio END) AS recibio ,
                                        (CASE WHEN p.documento IS NULL THEN "" ELSE p.documento END) AS documento ,
                                        (CASE WHEN p.vinculo IS NULL THEN "" ELSE p.vinculo END) AS vinculo ,
                                        (CASE WHEN p.datos_varios IS NULL THEN "" ELSE p.datos_varios END) AS datos_varios ,
                                        (CASE WHEN p.datos_varios_1 IS NULL THEN "" ELSE p.datos_varios_1 END) AS datos_varios_1 ,
                                        (CASE WHEN p.datos_varios_2 IS NULL THEN "" ELSE p.datos_varios_2 END) AS datos_varios_2 ')
                            ->join('flash_comprobantes_ingresos ci', 'ci.id = p.comprobante_ingreso_id')
                            ->join('flash_clientes c', 'c.id = ci.cliente_id')
                            ->join('flash_comprobantes_ingresos_servicios cis', 'cis.id = p.servicio_id')
                            ->join('flash_servicios s', 'cis.servicio_id = s.id')
                            ->join('flash_subpiezas sp', 'sp.pieza_id = p.id','left')
                            ->join('flash_hojas_rutas hdr', 'hdr.id = sp.hoja_ruta_id','left')
                            ->join('flash_sucursales_carteros sc', 'sc.id = hdr.cartero_id','left')
                            ->join('flash_piezas_despacho_piezas pd', 'pd.pieza_id = p.id','left')
                            ->join('flash_piezas_despacho d', 'd.id = pd.despacho_id','left')
                            ->join('flash_sucursales suc', 'suc.id = p.sucursal_id')
                            ->join('flash_piezas_novedades n', 'n.pieza_id = p.id','left')
                            ->join('flash_piezas_estados_variables ev', 'ev.id = n.estado_nuevo_id','left')
                            ->join('flash_rendiciones_piezas rp', 'p.id =rp.pieza_id','left')
                            ->where('p.tipo_id = 2')
                            
//                            ->where('liquidacion_id = 0')
//                            ->group_by('cis.servicio_id')
//                            ->order_by('ci.create ')
//                            ->having('cantidad > 0 ')
                            ->get('flash_piezas p');
//           $result = $query->result();
//            echo $this->db->last_query();die;
            return $query->result();
            
	}
        
        function getSucursales(){
            if ($this->ion_auth->isAdmin())
            {
                return Sucursal::all();
            }
            else
            {
                $sucursal_id = $this->ion_auth->user()->row()->sucursal_id;
                return Sucursal::whereId($sucursal_id);
            }
        }
        
        function getPiezasPorServiciosPorComprobanteSinGroup($comprobante_id,$servicio_id){
        $user_row = $this->ion_auth->user()->row();
        $query1 = $this->db
                        ->select('cis.*,
                                p.id pieza_id,
                                svc.nombre AS servicio_nombre, 
                                pev.nombre AS pieza_estado_nombre,
                                pt.nombre AS pieza_tipo_nombre,
                                p.barcode AS barcode,
                                p.barcode_externo AS barcode_externo,
                                p.datos_varios,
                                p.datos_varios_1,
                                p.datos_varios_2,
                                p.create AS creacion,
                                p.domicilio,
                                p.localidad,
                                cis.create AS crea,
                                p.destinatario,
                                c.nombre cliente,
                                ci.numero comprobante_numero,
                                p.codigo_postal codigo_postal,
                                pe.nombre precio_especial_nombre,
                                cd.nombre as departamento,
                                DATE_FORMAT(ci.create,"%d/%m/%Y") fecha_ingreso')
                        ->join('flash_comprobantes_ingresos_servicios cis', 'cis.id = p.servicio_id')
                        ->join('flash_servicios svc', 'svc.id = cis.servicio_id')
                        ->join('flash_comprobantes_ingresos ci', 'p.comprobante_ingreso_id = ci.id')
                        ->join('flash_piezas_estados pev', 'pev.id = p.estado_id')
                        ->join('flash_piezas_tipos pt', 'pt.id = p.tipo_id')
                        ->join('flash_clientes c', 'ci.cliente_id = c.id')
                        ->join('flash_clientes_departamentos cd', 'ci.departamento_id = cd.id','left')
                        ->join('flash_clientes_precios_especiales pe', 'pe.cliente_id = ci.cliente_id AND pe.servicio_id = cis.servicio_id')
                        ->where('p.comprobante_ingreso_id = '.$comprobante_id)
                        ->where('p.servicio_id = '.$servicio_id)
                        ->where('p.tipo_id = '.Pieza::TIPO_NORMAL)
                        ->get('flash_piezas p');
        $join1 = $this->db->last_query();
        
        $union_query = $this->db->query($join1);

//       echo($this->db->last_query());die;
        return $union_query->result();
    }
    
    function getLiquidacionesCarteros($liquidacion_cartero_id){
        $user_row = $this->ion_auth->user()->row();
        if (!$this->ion_auth->in_group(array(1, 2), $user_row->id)) $query = $this->db->where('sucursal_id', $user_row->sucursal_id);
        if($liquidacion_cartero_id != NULL)  $this->db->where('id', $liquidacion_cartero_id);
        
        $query = $this->db
                        ->select('*')
                        ->get('flash_liquidaciones_carteros');
        //$result = $query->result();
        //echo($this->db->last_query());die;
        return $query->result();
    }
    
    function getALLPiezasDespachos($despacho_id){
        $user_row = $this->ion_auth->user()->row();
        $query = $this->db
                        ->select('d.id nro_despacho, d.destino_id, suc.nombre sucursal, c.nombre cliente, s.nombre servicio , COUNT(*) cantidad_piezas, ci.numero')
                        ->join('flash_piezas_despacho_piezas pd', 'd.id = pd.despacho_id')
                        ->join('flash_piezas p', 'p.id = pd.pieza_id')
                        ->join('flash_comprobantes_ingresos_servicios cis', 'cis.id = p.servicio_id')
                        ->join('flash_comprobantes_ingresos ci', 'p.comprobante_ingreso_id = ci.id')
                        ->join('flash_servicios s', 'cis.servicio_id = s.id')
                        ->join('flash_clientes c', 'ci.cliente_id = c.id')
                        ->join('flash_sucursales suc', 'd.destino_id = suc.id')
                        ->where('d.id = '.$despacho_id)
                        ->group_by('s.id, c.id')
                        ->get('flash_piezas_despacho d');
        return $query->result();
    }
    
    function getPiezasHDRDistribuidor($hoja_ruta_id){
        $user_row = $this->ion_auth->user()->row();
        $query1 = $this->db
                        ->select('  hdr.id, 
                                    ci.numero comprobante_ingreso, 
                                    s.nombre servicio, 
                                    p.id pieza_id, 
                                    p.barcode_externo, 
                                    p.destinatario, 
                                    p.domicilio, 
                                    p.localidad,
                                    p.codigo_postal,
                                    hdr.observaciones,
                                    1 cantidad,
                                    p.tipo_id,
                                    d.apellido_nombre distribuidor')
                        ->join('flash_subpiezas sp', 'hdr.id = sp.hoja_ruta_id')
                        ->join('flash_piezas p', 'sp.pieza_id = p.id')
                        ->join('flash_comprobantes_ingresos ci', 'p.comprobante_ingreso_id = ci.id')
                        ->join('flash_comprobantes_ingresos_servicios cis', 'p.servicio_id = cis.id')
                        ->join('flash_servicios s', 'cis.servicio_id = s.id')
                        ->join('flash_distribuidores d', 'hdr.distribuidor_id = d.id')
                        ->where('hdr.distribuidor_id IS NOT NULL')
                        ->where('p.tipo_id = '.Pieza::TIPO_NORMAL)
                        ->where('hdr.id = '.$hoja_ruta_id)
                        ->get('flash_hojas_rutas hdr');
        $join1 = $this->db->last_query();
        $query2 = $this->db
                        ->select('  hdr.id, 
                                    ci.numero comprobante_ingreso, 
                                    s.nombre servicio, 
                                    p.id pieza_id, 
                                    p.barcode_externo, 
                                    p.destinatario, 
                                    p.domicilio, 
                                    p.localidad,
                                    p.codigo_postal,
                                    hdr.observaciones,
                                    COUNT(*) cantidad,
                                    p.tipo_id,
                                    d.apellido_nombre distribuidor')
                        ->join('flash_subpiezas sp', 'hdr.id = sp.hoja_ruta_id')
                        ->join('flash_piezas p', 'sp.pieza_id = p.id')
                        ->join('flash_comprobantes_ingresos ci', 'p.comprobante_ingreso_id = ci.id')
                        ->join('flash_comprobantes_ingresos_servicios cis', 'p.servicio_id = cis.id')
                        ->join('flash_servicios s', 'cis.servicio_id = s.id')
                        ->join('flash_distribuidores d', 'hdr.distribuidor_id = d.id')
                        ->where('hdr.distribuidor_id IS NOT NULL')
                        ->where('p.tipo_id = '.Pieza::TIPO_SIMPLE)
                        ->where('hdr.id = '.$hoja_ruta_id)
                        ->group_by('s.id')
                        ->get('flash_hojas_rutas hdr');
        $join2 = $this->db->last_query();
        
        $union_query = $this->db->query($join1.' UNION '.$join2);
//        $result = $union_query->result();
//       echo($this->db->last_query());die;
        return $union_query->result();
    }
    
    function getPiezasNormalesHDRCarteros($hoja_ruta_id){
        $user_row = $this->ion_auth->user()->row();
        $query = $this->db
                        ->select('  hdr.id, ci.numero comprobante_ingreso, 
                                    s.nombre servicio, 
                                    p.id pieza_id, 
                                    p.barcode_externo, 
                                    p.destinatario, 
                                    p.domicilio, 
                                    p.localidad,
                                    p.codigo_postal,
                                    hdr.observaciones,
                                    count(*) cantidad,
                                    p.tipo_id,
                                    c.id cartero_id,
                                    c.apellido_nombre cartero,
                                    ci.create fecha_comprobante')
                        ->join('flash_subpiezas sp', 'hdr.id = sp.hoja_ruta_id')
                        ->join('flash_piezas p', 'sp.pieza_id = p.id')
                        ->join('flash_comprobantes_ingresos ci', 'p.comprobante_ingreso_id = ci.id')
                        ->join('flash_comprobantes_ingresos_servicios cis', 'p.servicio_id = cis.id')
                        ->join('flash_servicios s', 'cis.servicio_id = s.id')
                        ->join('flash_sucursales_carteros c', 'hdr.cartero_id = c.id')
                        ->where('hdr.cartero_id IS NOT NULL')
                        ->where('p.tipo_id = '.Pieza::TIPO_NORMAL)
                        ->where('hdr.id = '.$hoja_ruta_id)
                        ->order_by('p.domicilio')
                        ->group_by('ci.id, s.id')
                        ->get('flash_hojas_rutas hdr');
        // $result = $query->result();
//       echo($this->db->last_query());die;
        return $query->result();
    }
    
    function getPiezasNormalesHDRCarterosAuditoria($hoja_ruta_id){
        $user_row = $this->ion_auth->user()->row();
        $query = $this->db
                        ->select('  hdr.id, ci.numero comprobante_ingreso, 
                                    s.nombre servicio, 
                                    p.id pieza_id, 
                                    p.barcode_externo, 
                                    p.destinatario, 
                                    p.domicilio, 
                                    p.localidad,
                                    p.codigo_postal,
                                    hdr.observaciones,
                                    count(*) cantidad,
                                    p.tipo_id,
                                    c.id cartero_id,
                                    c.apellido_nombre cartero,
                                    ci.create fecha_comprobante')
                        ->join('flash_subpiezas sp', 'hdr.id = sp.hoja_ruta_id')
                        ->join('flash_piezas p', 'sp.pieza_id = p.id')
                        ->join('flash_comprobantes_ingresos ci', 'p.comprobante_ingreso_id = ci.id')
                        ->join('flash_comprobantes_ingresos_servicios cis', 'p.servicio_id = cis.id')
                        ->join('flash_servicios s', 'cis.servicio_id = s.id')
                        ->join('flash_sucursales_carteros c', 'hdr.cartero_id = c.id')
                        ->where('hdr.cartero_id IS NOT NULL')
                        ->where('p.tipo_id = '.Pieza::TIPO_NORMAL)
                        ->where('hdr.id = '.$hoja_ruta_id)
                        ->order_by('p.domicilio')
                        ->group_by('ci.id, s.id, p.id')
                        ->get('flash_hojas_rutas hdr');
        // $result = $query->result();
      // echo($this->db->last_query());die;
        return $query->result();
    }
    
    function getPiezasSimplesHDRCarteros($hoja_ruta_id){
        $user_row = $this->ion_auth->user()->row();
        $query = $this->db
                        ->select('  hdr.id,  ci.numero comprobante_ingreso, 
                                    s.nombre servicio, 
                                    p.id pieza_id, 
                                    p.barcode_externo, 
                                    p.destinatario, 
                                    p.domicilio, 
                                    p.localidad,
                                    p.codigo_postal,
                                    hdr.observaciones,
                                    COUNT(*) cantidad,
                                    p.tipo_id,
                                    c.id cartero_id,
                                    c.apellido_nombre cartero,
                                    ci.create fecha_comprobante,
                                    z.nombre zona,
                                    clie.nombre cliente')
                        ->join('flash_subpiezas sp', 'hdr.id = sp.hoja_ruta_id')
                        ->join('flash_piezas p', 'sp.pieza_id = p.id')
                        ->join('flash_comprobantes_ingresos ci', 'p.comprobante_ingreso_id = ci.id')
                        ->join('flash_comprobantes_ingresos_servicios cis', 'p.servicio_id = cis.id')
                        ->join('flash_servicios s', 'cis.servicio_id = s.id')
                        ->join('flash_sucursales_carteros c', 'hdr.cartero_id = c.id')
                        ->join('flash_sucursales_zonas z', 'hdr.zona_id = z.id')
                        ->join('flash_clientes clie', 'ci.cliente_id = clie.id')
                        ->where('hdr.cartero_id IS NOT NULL')
                        ->where('p.tipo_id = '.Pieza::TIPO_SIMPLE)
                        ->where('hdr.id = '.$hoja_ruta_id)
                        ->group_by('ci.id,s.id')
                        ->get('flash_hojas_rutas hdr');
//        $result = $union_query->result();
//       echo($this->db->last_query());die;
        return $query->result();
    }
    
    function getComprobanteDetalle($comprobante_id){
        $user_row = $this->ion_auth->user()->row();
        $query = $this->db
                        ->select('`ci`.`id`           `comprobante_ingreso_id`,
                                `ci`.`numero`,
                                `s`.`id`            `servicio_id`,
                                `s`.`nombre`        `servicio`,
                                SUM(`cis`.`cantidad`)    `cantidad_piezas`,
                                (	SELECT COUNT(p.id) 
                                      FROM flash_piezas p 
                                      INNER JOIN flash_subpiezas sp ON sp.pieza_id = p.id 
                                      INNER JOIN flash_comprobantes_ingresos_servicios ciser ON p.comprobante_ingreso_id = ciser.comprobante_ingreso_id AND p.servicio_id = ciser.id
                                      INNER JOIN flash_comprobantes_ingresos cing ON p.comprobante_ingreso_id = cing.id
                                      WHERE p.comprobante_ingreso_id = '.$comprobante_id.'
                                      AND ciser.servicio_id = cis.servicio_id
                                      AND cis.remito = ciser.remito
                                ) cantidad_piezas_carteros,
                                `cis`.`remito`,
                                cis.id comprobante_ingreso_servicio_id')
                        ->join('flash_comprobantes_ingresos_servicios cis', 'ci.id = cis.comprobante_ingreso_id')
                        ->join('flash_servicios s', 'cis.servicio_id = s.id')
                       // ->join('flash_piezas p', 'ci.id = p.comprobante_ingreso_id')
                        ->where('ci.id = '.$comprobante_id)
                        ->group_by('cis.id, servicio_id')
                        ->get('flash_comprobantes_ingresos ci');
        //echo $this->db->last_query();die;
        return $query->result();
    }
    
    public function getCantidadPiezasSimplesPorHDR($numero,$hdr_id,$servicio_id){
        $query = $this->db
                ->select('p.id pieza_id')
                ->join('flash_subpiezas sp', 'hdr.id = sp.hoja_ruta_id')
                ->join('flash_piezas p', 'sp.pieza_id = p.id')
                ->join('flash_comprobantes_ingresos ci', 'p.comprobante_ingreso_id = ci.id')
                ->where('p.tipo_id = 1')
                ->where('ci.numero = '.$numero)
                ->where('p.servicio_id = '.$servicio_id)
                ->where('sp.hoja_ruta_id = '.$hdr_id)
                ->get('flash_hojas_rutas hdr');
//            echo $this->db->last_query();die;
        return $query->result();
    }
    
    public function getHDRXDistribuidor($desde = NULL, $hasta = NULL){
            $periodo_desde = $desde != NULL?$desde:false;
            $periodo_hasta = $hasta != NULL?$hasta:false;
            $date_desde = new DateTime($periodo_desde);
            $date_hasta = new DateTime($periodo_hasta);
            if($periodo_desde){
                $query = $this->db->where('hdr.create >= ', $date_desde->format('Y-m-d 00:00:00'));
            }
            if($periodo_hasta){
                $query = $this->db->where('hdr.create <= ', $date_hasta->format('Y-m-d 23:59:59'));
            }
            
            $query = $this->db
                    ->select('hdr.id hoja_ruta_id,
                            d.apellido_nombre,
                            DATE_FORMAT(hdr.create,"%d-%m-%Y")    fecha_creacion,
                            COUNT(sp.hoja_ruta_id )    cantidad,
                            c.nombre cliente,
                            s.nombre servicio,
                            suc.nombre sucursal')
                    ->join('flash_subpiezas sp', 'hdr.id = sp.hoja_ruta_id')
                    ->join('flash_distribuidores d', 'hdr.distribuidor_id = d.id')
                    ->join('flash_piezas p', 'sp.pieza_id = p.id')
                    ->join('flash_comprobantes_ingresos_servicios cis', 'p.servicio_id = cis.id')
                    ->join('flash_comprobantes_ingresos ci', 'ci.id = p.comprobante_ingreso_id')
                    ->join('flash_servicios s', 's.id = cis.servicio_id')
                    ->join('flash_clientes c', 'ci.cliente_id = c.id')
                    ->join('flash_sucursales suc', 'hdr.sucursal_id = suc.id')
                    ->where('hdr.distribuidor_id IS NOT NULL')
                    ->group_by('hdr.id, hdr.distribuidor_id, cis.servicio_id, ci.cliente_id, p.sucursal_id')
                    ->get('flash_hojas_rutas hdr');
//            echo $this->db->last_query();die;
            return $query->result();
	} 
        
        public function getUsers(){

            $query = $this->db
                    ->select('u.id, username, email, apellido ,nombre, g.name, u.active, COUNT(l.login) AS contador')
                    ->join('users_groups ug', 'u.id = ug.user_id')
                    ->join('groups g', 'g.id = ug.group_id')
                    ->join('login_attempts_errors l', 'l.login = u.username','left')
                    ->group_by('u.id')
                    ->get('users u');
//            echo $this->db->last_query();die;
            return $query->result();
	}
        
        public function getEstadosVariables($grupo_id){
            $query = $this->db
                    ->select('ev.*')
                    ->join('flash_piezas_estados_variables ev', 'e.id = ev.pieza_estado_id')
                    ->where('e.id = '.$grupo_id)
                    ->get('flash_piezas_estados e');
//            echo $this->db->last_query();die;
            return $query->result();
        }
        
    function getCantidadPiezasXHDRXCarteros($comprobante_ingreso){
//        $user_row = $this->ion_auth->user()->row();
//        if (!$this->ion_auth->in_group(array(1, 2), $user_row->id)) $query = $this->db->where('s.id', $user_row->sucursal_id);

        $query = $this->db
            ->select('hdr.id hdr_id, sc.apellido_nombre cartero, s.nombre servicio , COUNT(*) piezas ')
            ->join('flash_comprobantes_ingresos_servicios cis', 'p.comprobante_ingreso_id = cis.comprobante_ingreso_id')
            ->join('flash_servicios s', 'cis.servicio_id = s.id')
            ->join('flash_comprobantes_ingresos ci', 'p.comprobante_ingreso_id = ci.id AND p.servicio_id = cis.id')
            ->join('flash_subpiezas sp', 'sp.pieza_id = p.id')
            ->join('flash_hojas_rutas hdr', 'sp.hoja_ruta_id = hdr.id')
            ->join('flash_sucursales_carteros sc', 'hdr.cartero_id = sc.id')
            ->where('ci.numero = '.$comprobante_ingreso)
            ->group_by('hdr.id, sc.id')
            ->get('flash_piezas p');
//        echo $this->db->last_query();die;
        return $query->result();
    }
    
    function getPiezasPorHDR($hdr){
        $user_row = $this->ion_auth->user()->row();
        $query = $this->db
                        ->select('p.id,p.estado_id,p.*,s.id servicio_id,s.nombre servicio')
                        ->join('flash_subpiezas sp', 'p.id = sp.pieza_id')
                        ->join('flash_comprobantes_ingresos_servicios cis', 'p.comprobante_ingreso_id = cis.comprobante_ingreso_id AND p.servicio_id = cis.id')
                        ->join('flash_servicios s', 'cis.servicio_id = s.id')
                        ->where('sp.hoja_ruta_id = '.$hdr)
                        ->get('flash_piezas p');
//        echo $this->db->last_query();die;
        return $query->result();
    }
    
    function getPiezasPorDespacho($despacho){
        $user_row = $this->ion_auth->user()->row();
        $query = $this->db
                        ->select('p.id,p.estado_id,p.*,s.id servicio_id,s.nombre servicio')
                        ->join('flash_piezas_despacho_piezas pd', 'p.id = pd.pieza_id')
                        ->join('flash_comprobantes_ingresos_servicios cis', 'p.comprobante_ingreso_id = cis.comprobante_ingreso_id AND p.servicio_id = cis.id')
                        ->join('flash_servicios s', 'cis.servicio_id = s.id')
                        ->where('pd.despacho_id = '.$despacho)
                        ->get('flash_piezas p');
//        echo $this->db->last_query();die;
        return $query->result();
    }
}