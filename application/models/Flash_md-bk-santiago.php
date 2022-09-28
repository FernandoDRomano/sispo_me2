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
                            ->join('flash_iva i', 'c.flash_iva_id = i.id','left')
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
                            ->group_by('p.id')
                            ->get('flash_piezas p');
            //echo $this->db->last_query();die;
            return $query->result();
        }
        //Piezas que ya estan rendidas imprimir
        function getALLPiezasRendicionAgrupadas($id, $limite = null){
            $user_row = $this->ion_auth->user()->row();
            if (!$this->ion_auth->in_group(array(1, 2), $user_row->id)) $query = $this->db->where('u.sucursal_id', $user_row->sucursal_id);
            //if ($limite != NULL)  $query1 =  $this->db->limit(500, $limite);
            
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
                                        DATE_FORMAT(`n`.`update`, "%d-%m-%Y") AS `fecha_estado`,
                                        `hdr`.`id`             AS `hoja_ruta_id`,
                                        `fr`.`id`              AS `rendicion_id`,
                                        `d`.`despacho_id`,
                                        p.destinatario,
                                        p.domicilio,
                                        p.codigo_postal,
                                         1 as cantidad,
                                         p.barcode_externo,
                                         p.datos_varios,
                                         p.datos_varios_3')
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
            
            if ($limite != NULL)  $query2 =  $this->db->limit(500, $limite);
            
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
                                        DATE_FORMAT(`n`.`update`, "%d-%m-%Y") AS `fecha_estado`,
                                        hdr.id             AS hoja_ruta_id,
                                        fr.id              AS rendicion_id,
                                        d.despacho_id,
                                        p.destinatario,
                                        p.domicilio,
                                        p.codigo_postal,
                                        COUNT(*) AS cantidad,
                                        "-",
                                        "-",
                                        "-"')
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
        //    echo $this->db->last_query();die;
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
           // echo($this->db->last_query());die;
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
                            ->select('p.id pieza_id,p.tipo_id, p.domicilio, p.localidad, p.codigo_postal, svc.acuse acuse,
                                    s.*,
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

                                    p.destinatario,
                                    pp.descripcion_paquete, pp.dimensiones, pp.peso,pp.bultos')
                            ->join('flash_comprobantes_ingresos_servicios s', 's.id = p.servicio_id')
                            ->join('flash_servicios svc', 'svc.id = s.servicio_id')
                            ->join('flash_piezas_estados_variables pev', 'pev.id = p.estado_id')
                            ->join('flash_piezas_tipos pt', 'pt.id = p.tipo_id')
                            ->join('flash_piezas_paquetes pp', 'p.id = pp.pieza_id','left')
                            ->where('p.comprobante_ingreso_id = '.$comprobante_id)
                            ->where('p.tipo_id = '.Pieza::TIPO_NORMAL)
                            ->get('flash_piezas p');
            $join1 = $this->db->last_query();
            $query2 = $this->db
                            ->select('p.id pieza_id,p.tipo_id, p.domicilio, p.localidad, p.codigo_postal, svc.acuse acuse,
                                    s.*,
                                    (select count(id)
                                    from flash_piezas
                                    where comprobante_ingreso_id = '.$comprobante_id.'
                                    and tipo_id = '.Pieza::TIPO_SIMPLE.' and destinatario = "") AS cantidad_piezas,
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

                                    p.destinatario, "" descripcion_paquete,"" dimensiones, "" peso,"" bultos')
                            ->join('flash_comprobantes_ingresos_servicios s', 's.id = p.servicio_id')
                            ->join('flash_servicios svc', 'svc.id = s.servicio_id')
                            ->join('flash_piezas_estados_variables pev', 'pev.id = p.estado_id')
                            ->join('flash_piezas_tipos pt', 'pt.id = p.tipo_id')
                            ->where('p.comprobante_ingreso_id = '.$comprobante_id)
                            ->where('p.tipo_id = '.Pieza::TIPO_SIMPLE)
                            ->where('p.destinatario = ""')
                            ->group_by('s.servicio_id')
                            ->get('flash_piezas p');
            $join2 = $this->db->last_query();
            $query3 = $this->db
                            ->select('p.id pieza_id,p.tipo_id, p.domicilio, p.localidad, p.codigo_postal, svc.acuse acuse,
                                    s.*,
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

                                    p.destinatario, "" descripcion_paquete,"" dimensiones, "" peso,"" bultos')
                            ->join('flash_comprobantes_ingresos_servicios s', 's.id = p.servicio_id')
                            ->join('flash_servicios svc', 'svc.id = s.servicio_id')
                            ->join('flash_piezas_estados_variables pev', 'pev.id = p.estado_id')
                            ->join('flash_piezas_tipos pt', 'pt.id = p.tipo_id')
                            ->where('p.comprobante_ingreso_id = '.$comprobante_id)
                            ->where('p.tipo_id = '.Pieza::TIPO_SIMPLE)
                            ->where('p.destinatario <> ""')
                            ->get('flash_piezas p');
            $join3 = $this->db->last_query();
            $union_query = $this->db->query($join1.' UNION '.$join3.' UNION '.$join2/*.' LIMIT 10'*/);
    //        $result = $union_query->result();
//           echo($this->db->last_query());die;
            return $union_query->result();
        }

        function getServiciosPorComprobante($comprobante_id){
            $user_row = $this->ion_auth->user()->row();
            $query = $this->db
                            ->select('s.id, s.nombre, cis.disponible, cis.id comprobante_servicio_id, s.acuse acuse')
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

         function getDespachoPiezasAgrupadas($despacho_id){

            $query1 = 'SELECT   fp.*,
                                p.barcode barcode,
                                p.barcode_externo,
                                p.servicio_id,
                                COUNT(*) cantidad,
                                CONCAT((p.destinatario), (", "), (p.domicilio), (", "), ( p.codigo_postal), (", "), ( p.localidad)) AS descripcion,
                                ev.nombre AS estado,
                                ci.numero comprobante_ingreso,
                                p.tipo_id,
                                ci.id comprobante_ingreso_id,
                                "SIMPLE" as tipo,
                                p.verifico_id,
                                null AS verificado_por,
                                p.estado_id as estado_id
                        FROM flash_piezas_despacho_piezas fp
                        INNER JOIN flash_piezas p ON p.id = fp.pieza_id
                        LEFT JOIN flash_comprobantes_ingresos ci ON ci.id = p.comprobante_ingreso_id
                        INNER JOIN flash_piezas_estados_variables ev ON ev.id = p.estado_id
                        WHERE p.tipo_id = '.Pieza::TIPO_SIMPLE
                        .' AND fp.despacho_id = '.$despacho_id
                        .' AND p.destinatario = ""
                        GROUP BY comprobante_ingreso_id,servicio_id';

            //$join1 = $this->db->last_query();

            $query2 = 'SELECT   fp.*,
                                p.barcode barcode,
                                p.barcode_externo,
                                p.servicio_id,
                                1 as cantidad,
                                CONCAT((p.destinatario),(", "),(p.domicilio),(", "),( p.codigo_postal),(", "),( p.localidad)) as descripcion,
                                ev.nombre AS estado,
                                ci.numero comprobante_ingreso,
                                p.tipo_id,
                                ci.id comprobante_ingreso_id,
                                "NORMAL" AS tipo,
                                p.verifico_id,
                                fp.usuario_verifico_id AS verificado_por ,
                                p.estado_id as estado_id
                        FROM flash_piezas_despacho_piezas fp
                        INNER JOIN flash_piezas_despacho fd ON fd.id = fp.despacho_id
                        INNER JOIN flash_piezas p ON p.id = fp.pieza_id
                        LEFT JOIN flash_comprobantes_ingresos ci ON ci.id = p.comprobante_ingreso_id
                        INNER JOIN flash_piezas_estados_variables ev ON ev.id = p.estado_id
                        WHERE p.tipo_id = '.Pieza::TIPO_NORMAL
                        .' AND fp.despacho_id = '.$despacho_id ;

           //$join2 = $this->db->last_query();

           $query3 = 'SELECT    fp.*,
                                p.barcode barcode,
                                p.barcode_externo,
                                p.servicio_id,
                                1 as cantidad,
                                CONCAT((p.destinatario), (", "), (p.domicilio), (", "), ( p.codigo_postal), (", "), ( p.localidad)) AS descripcion,
                                ev.nombre AS estado,
                                ci.numero comprobante_ingreso,
                                p.tipo_id,
                                ci.id comprobante_ingreso_id,
                                "SIMPLE CON DATOS" AS tipo,
                                p.verifico_id,
                                NULL AS verificado_por,
                                p.estado_id as estado_id
                    FROM flash_piezas_despacho_piezas fp
                    INNER JOIN flash_piezas p ON p.id = fp.pieza_id
                    LEFT JOIN flash_comprobantes_ingresos ci ON ci.id = p.comprobante_ingreso_id
                    INNER JOIN flash_piezas_estados_variables ev ON ev.id = p.estado_id
                    WHERE p.tipo_id = '.Pieza::TIPO_SIMPLE
                    .' AND fp.despacho_id = '.$despacho_id
                    .' AND p.destinatario <> ""';

            //$join3 = $this->db->last_query();

            $union_query = $this->db->query($query1.' UNION '.$query2.' UNION '.$query3);
    //        $result = $union_query->result();
            //echo($this->db->last_query());die;
            return $union_query->result();
         }

    //     function getDespachoPiezasAgrupadas($despacho_id){
    //
    //        $query1 = $this->db
    //                       ->select('   fp.*,
    //                                    p.barcode barcode,
    //                                    p.barcode_externo,
    //                                    p.servicio_id,
    //                                    COUNT(*) cantidad,
    //                                    CONCAT((p.destinatario), (", "), (p.domicilio), (", "), ( p.codigo_postal), (", "), ( p.localidad)) AS descripcion,
    //                                    ev.nombre AS estado,
    //                                    ci.numero comprobante_ingreso,
    //                                    p.tipo_id,
    //                                    ci.id comprobante_ingreso_id')
    //                       ->join('flash_piezas p', 'p.id = fp.pieza_id')
    //                       ->join('flash_comprobantes_ingresos ci', 'ci.id = p.comprobante_ingreso_id','left')
    //                       ->join('flash_piezas_estados_variables ev', 'ev.id = p.estado_id')
    //                       ->where('p.tipo_id = '.Pieza::TIPO_SIMPLE)
    //                       ->where('fp.despacho_id = '.$despacho_id )
    //                       ->where('p.destinatario = ""')
    //                       ->group_by('comprobante_ingreso_id,servicio_id')
    //                       ->get('flash_piezas_despacho_piezas fp');
    //        $join1 = $this->db->last_query();
    //
    //        $query2 = $this->db
    //                       ->select('   fp.*,
    //                                    p.barcode barcode,
    //                                    p.barcode_externo,
    //                                    p.servicio_id,
    //                                    1 as cantidad,
    //                                    CONCAT((p.destinatario),(", "),(p.domicilio),(", "),( p.codigo_postal),(", "),( p.localidad)) as descripcion,
    //                                    ev.nombre AS estado,
    //                                    ci.numero comprobante_ingreso,
    //                                    p.tipo_id,
    //                                    ci.id comprobante_ingreso_id')
    //                        ->join('flash_piezas_despacho fd', 'fd.id = fp.despacho_id')
    //                       ->join('flash_piezas p', 'p.id = fp.pieza_id')
    //                       ->join('flash_comprobantes_ingresos ci', 'ci.id = p.comprobante_ingreso_id','left')
    //                       ->join('flash_piezas_estados_variables ev', 'ev.id = p.estado_id')
    //                       ->where('p.tipo_id = '.Pieza::TIPO_NORMAL)
    //                       ->where('fp.despacho_id = '.$despacho_id )
    //                       ->get('flash_piezas_despacho_piezas fp');
    //       $join2 = $this->db->last_query();
    //
    //       $query3 = $this->db
    //                       ->select('   fp.*,
    //                                    p.barcode barcode,
    //                                    p.barcode_externo,
    //                                    p.servicio_id,
    //                                    1 as cantidad,
    //                                    CONCAT((p.destinatario), (", "), (p.domicilio), (", "), ( p.codigo_postal), (", "), ( p.localidad)) AS descripcion,
    //                                    ev.nombre AS estado,
    //                                    ci.numero comprobante_ingreso,
    //                                    p.tipo_id,
    //                                    ci.id comprobante_ingreso_id')
    //                       ->join('flash_piezas p', 'p.id = fp.pieza_id')
    //                       ->join('flash_comprobantes_ingresos ci', 'ci.id = p.comprobante_ingreso_id','left')
    //                       ->join('flash_piezas_estados_variables ev', 'ev.id = p.estado_id')
    //                       ->where('p.tipo_id = '.Pieza::TIPO_SIMPLE)
    //                       ->where('fp.despacho_id = '.$despacho_id )
    //                       ->where('p.destinatario <> ""')
    //                       ->get('flash_piezas_despacho_piezas fp');
    //        $join3 = $this->db->last_query();
    //
    //        $union_query = $this->db->query($join1.' UNION '.$join2.' UNION '.$join3);
    ////        $result = $union_query->result();
    //      echo($this->db->last_query());die;
    //        return $union_query->result();
    //     }
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
    //        $user_row = $this->ion_auth->user()->row();
    //        if (!$this->ion_auth->in_group(array(1, 2), $user_row->id)) $query = $this->db->where('sc.sucursal_id', $user_row->sucursal_id);
            $liquidacionCartero  = $this->codegen_model->row('flash_liquidaciones_carteros','id,sucursal_id','id='.$liquidacion_carteros_id);

            $query = $this->db
                            ->select(' hdr.zona_id IdRecorrido, z.nombre zona,  lcd.servicio_id, s.nombre servicio, c.nombre cliente, lcd.precio_unitario precio,  sum(lcd.cantidad_piezas) piezas')
                            ->join('flash_liquidaciones_carteros_detalles lcd','lc.id = lcd.liquidacion_cartero_id')
                            ->join('flash_hojas_rutas hdr', 'lcd.hoja_de_ruta_id = hdr.id')
                            ->join('flash_sucursales_zonas z', 'hdr.zona_id = z.id')
                            ->join('flash_servicios s', 'lcd.servicio_id = s.id')
                            ->join('flash_clientes c', 'lcd.cliente_id = c.id')
                            ->where('liquidacion_cartero_id = '.$liquidacion_carteros_id)
                            ->group_by('z.id, lcd.servicio_id, lcd.cliente_id')
                            ->order_by('z.id')
                            ->get('flash_liquidaciones_carteros lc');
    //        $query = $this->db
    //                        ->select(' hdr.id hoja_ruta_id, z.id, z.nombre zona, cis.servicio_id, s.nombre servicio, c.nombre cliente, pe.precio, COUNT(*) suma_piezas')
    //                        ->join('flash_piezas p','p.id = sp.pieza_id')
    //                        ->join('flash_hojas_rutas hdr', 'hdr.id = sp.hoja_ruta_id')
    //                        ->join('flash_comprobantes_ingresos ci', ' ci.id = p.comprobante_ingreso_id')
    //                        ->join('flash_comprobantes_ingresos_servicios cis', ' cis.id = p.servicio_id AND ci.id = cis.comprobante_ingreso_id')
    //                        ->join('flash_servicios s', 's.id = cis.servicio_id')
    //                        ->join('flash_clientes c', ' c.id = ci.cliente_id')
    //                        ->join('flash_clientes_precios_especiales pe', 'pe.servicio_id = cis.servicio_id AND pe.cliente_id = ci.cliente_id')
    //                        ->join('flash_sucursales_carteros sc', 'sc.id = hdr.cartero_id')
    //                        ->join('flash_sucursales_zonas z', ' z.id = hdr.zona_id')
    //                        ->where('hdr.fecha_baja >= "'. $periodo_desde .' 00:00:00"')
    //                        ->where('hdr.fecha_baja <= "'. $periodo_hasta .' 23:59:59"')
    //                        ->where('hdr.sucursal_id ='. $liquidacionCartero->sucursal_id)
    //                        ->group_by('ci.cliente_id, p.servicio_id, hdr.zona_id')
    //                        ->order_by('z.id')->distinct()
    //                        ->get('flash_subpiezas sp');
            //echo($this->db->last_query());die;
            return $query->result();
        }

        function getInfoCarterosXCarteros($liquidacion_cartero_id,$periodo_desde,$periodo_hasta,$periodo_alta_desde,$periodo_alta_hasta){
    //        $user_row = $this->ion_auth->user()->row();
    //        if (!$this->ion_auth->in_group(array(1, 2), $user_row->id)) $query = $this->db->where('hdr.sucursal_id', $user_row->sucursal_id);
            $liquidacionCartero  = $this->codegen_model->row('flash_liquidaciones_carteros','id,sucursal_id','id='.$liquidacion_cartero_id);

            $query = $this->db
                            //->select('sc.id, sc.apellido_nombre cartero, s.nombre servicio, c.nombre cliente, hdr.id hoja_ruta, pe.precio, sum(distinct(p.id)) suma_piezas, tc.nombre tipo_cartero')
                            ->select('ct.nombre tipo_cartero,
                                        lcd.cartero_id, sc.apellido_nombre cartero,
                                        s.nombre servicio, c.nombre cliente, lcd.hoja_de_ruta_id hoja_ruta,
                                        lcd.precio_unitario precio, sum(lcd.cantidad_piezas) suma_piezas')
                            ->join('flash_liquidaciones_carteros_detalles lcd','lc.id = lcd.liquidacion_cartero_id')
                            ->join('flash_sucursales_carteros sc', 'lcd.cartero_id = sc.id')
                            ->join('flash_servicios s', 'lcd.servicio_id = s.id')
                            ->join('flash_clientes c', 'lcd.cliente_id = c.id')
                            ->join('flash_sucursales_carteros_tipos ct', 'sc.cartero_tipo_id = ct.id')
                            ->where('lcd.liquidacion_cartero_id = '. $liquidacion_cartero_id)
                            ->group_by('lcd.hoja_de_ruta_id, lcd.cartero_id, lcd.cliente_id, lcd.servicio_id')
                            ->order_by('sc.apellido_nombre')
                            ->get('flash_liquidaciones_carteros lc');

//            $query = $this->db
//                            //->select('sc.id, sc.apellido_nombre cartero, s.nombre servicio, c.nombre cliente, hdr.id hoja_ruta, pe.precio, sum(distinct(p.id)) suma_piezas, tc.nombre tipo_cartero')
//                            ->select('sc.id, sc.apellido_nombre cartero, s.nombre servicio, c.nombre cliente, hdr.id hoja_ruta, pe.precio, COUNT(distinct(p.id)) suma_piezas, tc.nombre tipo_cartero')
//                            ->join('flash_piezas p','p.id = sp.pieza_id')
//                            ->join('flash_hojas_rutas hdr', 'hdr.id = sp.hoja_ruta_id')
//                            ->join('flash_comprobantes_ingresos ci', 'ci.id = p.comprobante_ingreso_id')
//                            ->join('flash_comprobantes_ingresos_servicios cis', 'cis.id = p.servicio_id AND ci.id = cis.comprobante_ingreso_id')
//                            ->join('flash_servicios s', 's.id = cis.servicio_id')
//                            ->join('flash_clientes c', 'c.id = ci.cliente_id')
//                            ->join('flash_clientes_precios_especiales pe', 'pe.servicio_id = cis.servicio_id AND pe.cliente_id = ci.cliente_id	')
//                            ->join('flash_sucursales_carteros sc', 'sc.id = hdr.cartero_id')
//                            ->join('flash_sucursales_zonas z', ' z.id = hdr.zona_id')
//                            ->join('flash_sucursales_carteros_tipos tc', ' sc.cartero_tipo_id = tc.id','left')
//                            ->where('hdr.fecha_baja >= "'. $periodo_desde .' 00:00:00"')
//                            ->where('hdr.fecha_baja <= "'. $periodo_hasta .' 23:59:59"')
//                            ->where('hdr.create >= "'. $periodo_alta_desde .' 00:00:00"')
//                            ->where('hdr.create <= "'. $periodo_alta_hasta .' 23:59:59"')
//                            ->where('hdr.sucursal_id ='. $liquidacionCartero->sucursal_id)
//                            ->group_by('ci.cliente_id, p.servicio_id, hdr.cartero_id, hdr.id')
//                            ->order_by('sc.apellido_nombre')
//                            ->get('flash_subpiezas sp');
    //        $result = $query->result();
          // echo($this->db->last_query());die;
            return $query->result();
        }

        function getInfoCarterosXDevoluciones($liquidacion_cartero_id){
           // $user_row = $this->ion_auth->user()->row();
            //if (!$this->ion_auth->in_group(array(1, 2), $user_row->id)) $query = $this->db->where('hdr.sucursal_id', $user_row->sucursal_id);
            //$liquidacionCartero  = $this->codegen_model->row('flash_liquidaciones_carteros','id,sucursal_id','id='.$liquidacion_cartero_id);
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
                           // ->where('hdr.sucursal_id ='. $liquidacionCartero->sucursal_id)
                            ->group_by('n.estado_nuevo_id, s.id, sc.id')
                            ->order_by('sc.id, s.id, ev.id')
                            ->get('flash_hojas_rutas hdr');
            //$result = $query->result();
            //echo($this->db->last_query());die;
            return $query->result();
        }

        function getHojasRutasRendidas($liquidacion_cartero_id){
    //        $user_row = $this->ion_auth->user()->row();
    //        if (!$this->ion_auth->in_group(array(1, 2), $user_row->id)) $query = $this->db->where('sc.sucursal_id', $user_row->sucursal_id);
           // $liquidacionCartero  = $this->codegen_model->row('flash_liquidaciones_carteros','id,sucursal_id','id='.$liquidacion_cartero_id);
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
    //                        ->where('hdr.fecha_baja >= "'. $periodo_desde .'"')
    //                        ->where('hdr.fecha_baja <= "'. $periodo_hasta .'"')
                            ->group_by('hdr.id')
                            ->get('flash_hojas_rutas hdr');
            //$result = $query->result();
            //echo($this->db->last_query());die;
            return $query->result();
        }

        public function getConsultasGlobales($fecha_ingreso = '',$pieza_id = '', $barra_externa = '', $comprobante = '',$cliente = '', $servicio = '',
                $cartero = '', $hoja_ruta_id = '', $despacho_id = '', $sucursal = '',$estado = '',$destinatario = '',$domicilio = '',$codigo_postal = '',
                $localidad = '',$fecha_cambio_estado = '',$visitas = '',$rendicion_id = '',$recibio = '',$documento = '',$vinculo = '',$datos_varios_1 = '',
                $datos_varios_2 = '',$datos_varios_3 = '',$datos_varios_4 = '',$fecha_pieza_desde = '',$fecha_pieza_hasta = ''){
                /*
                if ($fecha_ingreso){
                    $fecha_ingreso = new DateTime($fecha_ingreso);
                    $query = $this->db->where('ci.create >= ', $fecha_ingreso->format('Y-m-d 00:00:00'));
                }
                 */
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
                if ($datos_varios_4 != '') $query = $this->db->like('p.datos_varios_3',$datos_varios_4 );

                if($fecha_pieza_desde){
                    $fecha_pieza_desde = new DateTime($fecha_pieza_desde);
                    $query = $this->db->where('p.create >= ', $fecha_pieza_desde->format('Y-m-d 00:00:00'));
                }

                if($fecha_pieza_hasta){
                    $fecha_pieza_hasta = new DateTime($fecha_pieza_hasta);
                    $query = $this->db->where('p.create <= ', $fecha_pieza_hasta->format('Y-m-d 23:59:00'));
                }
                /* Solucion temporal solo traer los 6 ultimos meses */
                $query = $this->db->where('ci.create >= date_sub(curdate(), interval 6 month)');
                /* END: Solucion temporal solo traer los 2 ultomos meses */


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
                                p.estado_id AS estado_actual_id ,
                                (CASE WHEN p.destinatario IS NULL THEN "" ELSE p.destinatario END) AS destinatario ,
                                (CASE WHEN p.domicilio IS NULL THEN "" ELSE p.domicilio END) AS domicilio ,
                                (CASE WHEN p.codigo_postal IS NULL THEN "" ELSE p.codigo_postal END) AS codigo_postal ,
                                (CASE WHEN p.localidad IS NULL THEN "" ELSE p.localidad END) AS localidad ,
                                (CASE WHEN n.update IS NULL THEN "" ELSE DATE_FORMAT(n.update,"%d-%m-%Y") END) AS fecha_cambio_estado ,
                                (CASE WHEN ev.nombre IS NULL THEN "" ELSE ev.nombre END) AS estado_actual ,
                                (CASE WHEN rp.rendicion_id IS NULL THEN "" ELSE rp.rendicion_id END) AS rendicion_id ,
                                (CASE WHEN p.recibio IS NULL THEN "" ELSE p.recibio END) AS recibio ,
                                (CASE WHEN p.documento IS NULL THEN "" ELSE p.documento END) AS documento ,
                                (CASE WHEN p.vinculo IS NULL THEN "" ELSE p.vinculo END) AS vinculo ,
                                (CASE WHEN p.datos_varios IS NULL THEN "" ELSE p.datos_varios END) AS datos_varios ,
                                (CASE WHEN p.datos_varios_1 IS NULL THEN "" ELSE p.datos_varios_1 END) AS datos_varios_1 ,
                                (CASE WHEN p.datos_varios_2 IS NULL THEN "" ELSE p.datos_varios_2 END) AS datos_varios_2 ,
                                (CASE WHEN p.datos_varios_3 IS NULL THEN "" ELSE p.datos_varios_3 END) AS datos_varios_3 ,
                                (CASE WHEN p.mail_dest IS NULL THEN "" ELSE p.mail_dest END) AS mail_dest ,
                                (CASE WHEN p.celular_dest IS NULL THEN "" ELSE p.celular_dest END) AS celular_dest ')
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
                    ->join('flash_piezas_estados_variables ev', 'ev.id = p.estado_id','left')
                    ->join('flash_rendiciones_piezas rp', 'p.id =rp.pieza_id','left')
                    ->where('p.tipo_id = 2')
                    //->or_where('p.tipo_id = 1 AND p.destinatario != ""')
		    ->group_by('p.id')
                    ->get('flash_piezas p');
//echo($this->db->last_query());die;
                return $query->result();
            }


       public function getConsultasGlobalesPaginado($fecha_ingreso = '',$pieza_id = '', $barra_externa = '', $comprobante = '',$cliente = '', $servicio = '',
                $cartero = '', $hoja_ruta_id = '', $despacho_id = '', $sucursal = '',$estado = '',$destinatario = '',$domicilio = '',$codigo_postal = '',
                $localidad = '',$fecha_cambio_estado = '',$visitas = '',$rendicion_id = '',$recibio = '',$documento = '',$vinculo = '',$datos_varios_1 = '',
                $datos_varios_2 = '',$datos_varios_3 = '',$datos_varios_4 = '',$fecha_pieza_desde = '', $fecha_pieza_hasta = '', $limit = 25, $start = 0){
                /*
                if ($fecha_ingreso){
                    $fecha_ingreso = new DateTime($fecha_ingreso);
                    $query = $this->db->where('ci.create >= ', $fecha_ingreso->format('Y-m-d 00:00:00'));
                }
                */

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
                if ($destinatario != '') $query = $this->db->like('p.destinatario',$destinatario ); //aqui
                if ($domicilio != '') $query = $this->db->like('p.domicilio',$domicilio ); //aqui
                if ($codigo_postal != '') $query = $this->db->like('p.codigo_postal',$codigo_postal ); //aqui
                if ($localidad != '') $query = $this->db->like('p.localidad',$localidad ); //aqui

                if($fecha_cambio_estado){
                    $fecha_cambio_estado = new DateTime($fecha_cambio_estado);
                    $query = $this->db->where('n.update >= ', $fecha_cambio_estado->format('Y-m-d 00:00:00'));
                }
                if ($rendicion_id != '') $query = $this->db->where('rp.rendicion_id = ',$rendicion_id );
                if ($recibio != '') $query = $this->db->like('p.recibio',$recibio ); //aqui
                if ($documento != '') $query = $this->db->like('p.documento',$documento ); //aqui
                if ($vinculo != '') $query = $this->db->like('p.vinculo',$vinculo ); //aqui
                if ($datos_varios_1 != '') $query = $this->db->like('p.datos_varios',$datos_varios_1 ); //aqui
                if ($datos_varios_2 != '') $query = $this->db->like('p.datos_varios_1',$datos_varios_2 ); //aqui
                if ($datos_varios_3 != '') $query = $this->db->like('p.datos_varios_2',$datos_varios_3 ); //aqui
                if ($datos_varios_4 != '') $query = $this->db->like('p.datos_varios_3',$datos_varios_4 ); //aqui

                if($fecha_pieza_desde){
                    $fecha_pieza_desde = new DateTime($fecha_pieza_desde);
                    $query = $this->db->where('p.create >= ', $fecha_pieza_desde->format('Y-m-d 00:00:00'));
                }
                if($fecha_pieza_hasta){
                    $fecha_pieza_hasta = new DateTime($fecha_pieza_hasta);
                    $query = $this->db->where('p.create <= ', $fecha_pieza_hasta->format('Y-m-d 23:59:00'));
                }
                
                /* Solucion temporal solo traer los 6 ultomos meses */
                $query = $this->db->where('ci.create >= date_sub(curdate(), interval 6 month)');
                /* END: Solucion temporal solo traer los 2 ultomos meses */


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
                                p.estado_id AS estado_actual_id ,
                                (CASE WHEN p.destinatario IS NULL THEN "" ELSE p.destinatario END) AS destinatario ,
                                (CASE WHEN p.domicilio IS NULL THEN "" ELSE p.domicilio END) AS domicilio ,
                                (CASE WHEN p.codigo_postal IS NULL THEN "" ELSE p.codigo_postal END) AS codigo_postal ,
                                (CASE WHEN p.localidad IS NULL THEN "" ELSE p.localidad END) AS localidad ,
                                (CASE WHEN n.update IS NULL THEN "" ELSE DATE_FORMAT(n.update,"%d-%m-%Y") END) AS fecha_cambio_estado ,
                                (CASE WHEN ev.nombre IS NULL THEN "" ELSE ev.nombre END) AS estado_actual ,
                                (CASE WHEN rp.rendicion_id IS NULL THEN "" ELSE rp.rendicion_id END) AS rendicion_id ,
                                (CASE WHEN p.recibio IS NULL THEN "" ELSE p.recibio END) AS recibio ,
                                (CASE WHEN p.documento IS NULL THEN "" ELSE p.documento END) AS documento ,
                                (CASE WHEN p.vinculo IS NULL THEN "" ELSE p.vinculo END) AS vinculo ,
                                (CASE WHEN p.datos_varios IS NULL THEN "" ELSE p.datos_varios END) AS datos_varios ,
                                (CASE WHEN p.datos_varios_1 IS NULL THEN "" ELSE p.datos_varios_1 END) AS datos_varios_1 ,
                                (CASE WHEN p.datos_varios_2 IS NULL THEN "" ELSE p.datos_varios_2 END) AS datos_varios_2 ,
                                (CASE WHEN p.mail_dest IS NULL THEN "" ELSE p.mail_dest END) AS mail_dest ,
                                (CASE WHEN p.celular_dest IS NULL THEN "" ELSE p.celular_dest END) AS celular_dest  ')
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
                    ->join('flash_piezas_estados_variables ev', 'ev.id = p.estado_id','left')
                    ->join('flash_rendiciones_piezas rp', 'p.id =rp.pieza_id','left')
                    ->where('p.tipo_id = 2')
                    ->where('s.grupo_id <> 4')
		    ->group_by('p.id')
                    ->limit($limit, $start)
                    ->get('flash_piezas p');
//echo($this->db->last_query());die;
                return $query->result();
            }

        public function getConsultasGlobalesTotal($fecha_ingreso,$pieza_id, $barra_externa, $comprobante,$cliente, $servicio,
                $cartero, $hoja_ruta_id, $despacho_id, $sucursal,$estado,$destinatario,$domicilio,$codigo_postal,
                $localidad,$fecha_cambio_estado,$visitas,$rendicion_id,$recibio,$documento,$vinculo,$datos_varios_1,$datos_varios_2,$datos_varios_3,$datos_varios_4,
                $fecha_pieza_desde,$fecha_pieza_hasta){
               
                /*
                if ($fecha_ingreso){
                    $fecha_ingreso = new DateTime($fecha_ingreso);
                    $query = $this->db->where('ci.create >= ', $fecha_ingreso->format('Y-m-d 00:00:00'));
                }
                 */
                 //echo "La consulta esta en reparación intente mas tarde";die;
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
                if ($datos_varios_4 != '') $query = $this->db->like('p.datos_varios_3',$datos_varios_4 );

                if($fecha_pieza_desde){
                    $fecha_pieza_desde = new DateTime($fecha_pieza_desde);
                    $query = $this->db->where('p.create >= ', $fecha_pieza_desde->format('Y-m-d 00:00:00'));
                }
                if($fecha_pieza_hasta){
                    $fecha_pieza_hasta = new DateTime($fecha_pieza_hasta);
                    $query = $this->db->where('p.create <= ', $fecha_pieza_hasta->format('Y-m-d 23:59:00'));
                }

                /* Solucion temporal solo traer los 2 ultomos meses */
                $query = $this->db->where('ci.create >= date_sub(curdate(), interval 6 month)');
                /* END: Solucion temporal solo traer los 2 ultomos meses */


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
                                p.estado_id AS estado_actual_id ,
                                (CASE WHEN p.destinatario IS NULL THEN "" ELSE p.destinatario END) AS destinatario ,
                                (CASE WHEN p.domicilio IS NULL THEN "" ELSE p.domicilio END) AS domicilio ,
                                (CASE WHEN p.codigo_postal IS NULL THEN "" ELSE p.codigo_postal END) AS codigo_postal ,
                                (CASE WHEN p.localidad IS NULL THEN "" ELSE p.localidad END) AS localidad ,
                                (CASE WHEN n.update IS NULL THEN "" ELSE DATE_FORMAT(n.update,"%d-%m-%Y") END) AS fecha_cambio_estado ,
                                (CASE WHEN ev.nombre IS NULL THEN "" ELSE ev.nombre END) AS estado_actual ,
                                (CASE WHEN rp.rendicion_id IS NULL THEN "" ELSE rp.rendicion_id END) AS rendicion_id ,
                                (CASE WHEN p.recibio IS NULL THEN "" ELSE p.recibio END) AS recibio ,
                                (CASE WHEN p.documento IS NULL THEN "" ELSE p.documento END) AS documento ,
                                (CASE WHEN p.vinculo IS NULL THEN "" ELSE p.vinculo END) AS vinculo ,
                                (CASE WHEN p.datos_varios IS NULL THEN "" ELSE p.datos_varios END) AS datos_varios ,
                                (CASE WHEN p.datos_varios_1 IS NULL THEN "" ELSE p.datos_varios_1 END) AS datos_varios_1 ,
                                (CASE WHEN p.datos_varios_2 IS NULL THEN "" ELSE p.datos_varios_2 END) AS datos_varios_2 ,
                                (CASE WHEN p.datos_varios_3 IS NULL THEN "" ELSE p.datos_varios_3 END) AS datos_varios_3 ')
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
                    ->join('flash_piezas_estados_variables ev', 'ev.id = p.estado_id','left')
                    ->join('flash_rendiciones_piezas rp', 'p.id =rp.pieza_id','left')
                    ->where('p.tipo_id = 2')
                    
                    ->from('flash_piezas p');
               $retorno = $query->count_all_results();
               //echo($this->db->last_query());die;
                return $retorno;
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

        function getPiezasPorServiciosPorComprobanteSinGroup($comprobante_id,$servicio_id, $limite = NULL){
            $user_row = $this->ion_auth->user()->row();
            if ($limite != NULL)  $query1 =  $this->db->limit(500, $limite);
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
                                    DATE_FORMAT(ci.create,"%d/%m/%Y") fecha_ingreso,
                                    p.mail_dest as email')
                            ->join('flash_comprobantes_ingresos_servicios cis', 'cis.id = p.servicio_id')
                            ->join('flash_servicios svc', 'svc.id = cis.servicio_id')
                            ->join('flash_comprobantes_ingresos ci', 'p.comprobante_ingreso_id = ci.id')
                            ->join('flash_piezas_estados_variables pev', 'pev.id = p.estado_id')
                            ->join('flash_piezas_tipos pt', 'pt.id = p.tipo_id')
                            ->join('flash_clientes c', 'ci.cliente_id = c.id')
                            ->join('flash_clientes_departamentos cd', 'ci.departamento_id = cd.id','left')
                            ->join('flash_clientes_precios_especiales pe', 'pe.cliente_id = ci.cliente_id AND pe.servicio_id = cis.servicio_id')
                            ->where('p.comprobante_ingreso_id = '.$comprobante_id)
                            ->where('p.servicio_id = '.$servicio_id)
                            ->where('(p.tipo_id = '.Pieza::TIPO_NORMAL.' OR p.tipo_id = '.Pieza::TIPO_SIMPLE.' AND p.destinatario <> "")')
                            ->get('flash_piezas p');
            $join1 = $this->db->last_query();

            //$union_query = $this->db->query($query1);

            //echo($this->db->last_query());die;
            return $query1->result();
        }

        function getPiezasPorServiciosPorComprobanteSinGroupLimite($comprobante_id,$servicio_id, $limite_inferior){
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
                            ->where('(p.tipo_id = '.Pieza::TIPO_NORMAL.' OR p.tipo_id = '.Pieza::TIPO_SIMPLE.' AND p.destinatario <> "")')
                            ->limit(500, $limite_inferior)
                            ->get('flash_piezas p');
            $join1 = $this->db->last_query();

            //$union_query = $this->db->query($query1);

            //echo($this->db->last_query());die;
            return $query1->result();
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

        public function getHDRXDistribuidor($desde = NULL, $hasta = NULL, $sin_grupo = NULL){
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
                if($sin_grupo){
                    $query = $this->db->where(' s.grupo_id <> 4 ');//ain finiahing
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
                //echo $this->db->last_query();die;
                return $query->result();
            }

            public function getUsers(){

                $query = 'select u.id, username, email, apellido ,nombre, g.name, u.active, CONCAT(apellido,", " ,nombre) apellidonombre, COUNT(l.login) AS contador
                        FROM users u
                        INNER JOIN users_groups ug ON u.id = ug.user_id
                        INNER JOIN groups g ON g.id = ug.group_id
                        LEFT JOIN login_attempts_errors l ON l.login = u.username
                        GROUP BY u.id';
                return $this->db->query($query)->result();
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

        function getCantidadPiezasXHDRXCarteros($comprobante_ingreso, $sucursal_id){
            ini_set('memory_limit','-1');
            set_time_limit(1800);
            $comprobante_ingreso_object = Comprobante::whereNumero($comprobante_ingreso)->first();

            $query = $this->db
                ->select('hdr.id hdr_id, sc.apellido_nombre cartero, s.nombre servicio , COUNT(*) piezas, hdr.create fecha_hdr, ci.numero,suc.nombre sucursal_cartero ')
                ->join('flash_comprobantes_ingresos_servicios cis', 'p.comprobante_ingreso_id = cis.comprobante_ingreso_id')
                ->join('flash_servicios s', 'cis.servicio_id = s.id')
                ->join('flash_comprobantes_ingresos ci', 'p.comprobante_ingreso_id = ci.id AND p.servicio_id = cis.id')
                ->join('flash_subpiezas sp', 'sp.pieza_id = p.id')
                ->join('flash_hojas_rutas hdr', 'sp.hoja_ruta_id = hdr.id')
                ->join('flash_sucursales_carteros sc', 'hdr.cartero_id = sc.id')
                ->join('flash_sucursales suc', 'suc.id = sc.sucursal_id')
                ->where('ci.id = '.$comprobante_ingreso_object->id)
                ->where('hdr.sucursal_id = '.$sucursal_id)
                ->where('s.grupo_id <> 4') //Sin finishing 
                ->group_by('hdr.id, sc.id')
                ->get('flash_piezas p');
            //echo $this->db->last_query();die;
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

            public function hdr_cartero_distribuidor($periodo_desde, $periodo_hasta, $sucursal, $sin_grupo=null){
                if ($periodo_desde){
                    $periodo_desde = new DateTime($periodo_desde);
                    $query = $this->db->where('hdr.fecha_entrega >= ', $periodo_desde->format('Y-m-d 00:00:00'));
                }
                if($periodo_hasta){
                    $periodo_hasta = new DateTime($periodo_hasta);
                    $query = $this->db->where('hdr.fecha_entrega <= ', $periodo_hasta->format('Y-m-d 23:59:59'));
                }
                if($sucursal){
                    $query = $this->db->where('sc.sucursal_id = ', $sucursal);
                }
                if($sin_grupo != null){
                    $query = $this->db->where(' s.`grupo_id` NOT IN (4) ');
                }
                $query = $this->db
                        ->select('  hdr.id hdr_id,
                                    DATE_FORMAT(hdr.fecha_entrega,"%d-%m-%Y") fecha_entrega,
                                    sc.apellido_nombre cartero,
                                    sct.nombre tipo,
                                    dist.apellido_nombre distribuidor,
                                    s.nombre servicio,
                                    COUNT(p.id) piezas,
                                    hdr.fecha_baja fecha_baja')
                        ->join('flash_sucursales_carteros sc', 'hdr.cartero_id = sc.id','left')
                        ->join('flash_distribuidores dist', 'hdr.distribuidor_id = dist.id','left')
                        ->join('flash_sucursales_carteros_tipos sct', 'sc.cartero_tipo_id = sct.id','left')
                        ->join('flash_subpiezas sp', 'hdr.id = sp.hoja_ruta_id')
                        ->join('flash_piezas p', 'sp.pieza_id = p.id')
                        ->join('flash_comprobantes_ingresos_servicios cis', 'p.servicio_id = cis.id')
                        ->join('flash_servicios s', 'cis.servicio_id = s.id')
                        ->group_by('hdr.id, s.id')
                        ->get('flash_hojas_rutas hdr');
                //echo $this->db->last_query();die;
                return $query->result();
            }
            
            public function getDespachos($periodo_desde, $periodo_hasta){
                if ($periodo_desde){
                    $periodo_desde = new DateTime($periodo_desde);
                    $query = $this->db->where('d.fecha_envio >= ', $periodo_desde->format('Y-m-d 00:00:00'));
                }
                if($periodo_hasta){
                    $periodo_hasta = new DateTime($periodo_hasta);
                    $query = $this->db->where('d.fecha_envio <= ', $periodo_hasta->format('Y-m-d 23:59:59'));
                }
                $query = $this->db
                        ->select('  d.id despacho_id , d.fecha_envio, so.nombre origen, sd.nombre destino, s.nombre servicio,  COUNT(*) piezas, ev.nombre estado')
                        ->join('flash_piezas_despacho_piezas pdp', 'd.id = pdp.despacho_id')
                        ->join('flash_sucursales so', 'd.origen_id = so.id')
                        ->join('flash_sucursales sd', 'd.destino_id = sd.id','left')
                        ->join('flash_piezas p', ' pdp.pieza_id = p.id')
                        ->join('flash_comprobantes_ingresos ci', 'p.comprobante_ingreso_id = ci.id')
                        ->join('flash_comprobantes_ingresos_servicios cis', 'p.comprobante_ingreso_id = p.comprobante_ingreso_id
                                                            AND p.servicio_id = cis.id')
                        ->join('flash_servicios s', 'cis.servicio_id = s.id')
                        ->join('flash_piezas_estados_variables ev', 'd.estado = ev.id')
                        ->group_by('pdp.despacho_id')
                        ->get('flash_piezas_despacho d');
                //echo $this->db->last_query();die;
                return $query->result();
            }

            public function getDespachosFechaCreacion($periodo_desde, $periodo_hasta, $dias=0){
                if ($periodo_desde){
                    $periodo_desde = new DateTime($periodo_desde);
                    $query = $this->db->where('d.create >= ', $periodo_desde->format('Y-m-d 00:00:00'));
                }
                if($periodo_hasta){
                    $periodo_hasta = new DateTime($periodo_hasta);
                    $query = $this->db->where('d.create <= ', $periodo_hasta->format('Y-m-d 23:59:59'));
                }
                $query = $this->db
                        ->select(' c.nombre cliente,DATE_FORMAT(ci.create,"%d-%m-%Y") fecha_ingreso,  d.id despacho_id , DATE_FORMAT(d.create,"%d-%m-%Y") create, so.nombre origen, sd.nombre destino, s.nombre servicio,  COUNT(*) piezas,
                                    ev.nombre estado')
                        ->join('flash_piezas_despacho_piezas pdp', 'd.id = pdp.despacho_id')
                        ->join('flash_sucursales so', 'd.origen_id = so.id')
                        ->join('flash_sucursales sd', 'd.destino_id = sd.id','left')
                        ->join('flash_piezas p', ' pdp.pieza_id = p.id')
                        ->join('flash_comprobantes_ingresos ci', 'p.comprobante_ingreso_id = ci.id')
                        ->join('flash_comprobantes_ingresos_servicios cis', 'p.comprobante_ingreso_id = p.comprobante_ingreso_id
                                                            AND p.servicio_id = cis.id')
                        ->join('flash_servicios s', 'cis.servicio_id = s.id')
                        ->join('flash_piezas_estados_variables ev', 'd.estado = ev.id')
                        ->join('flash_clientes c', 'ci.cliente_id = c.id')
                        ->where(' s.grupo_id <> 4')//FINISHING
                        ->group_by('pdp.despacho_id')
                        //->having('dias >= '.$dias)
                        ->get('flash_piezas_despacho d');
                //echo $this->db->last_query();die;
                return $query->result();
            }

            /*public function getCarterosPendientesLiquidar($periodo_desde, $periodo_hasta,$periodo_alta_desde, $periodo_alta_hasta, $sucursal_id){
                if($periodo_desde) $query = $this->db->where('hdr.fecha_baja >= ', $periodo_desde->format('Y-m-d 00:00:00'));
                if($periodo_hasta) $query = $this->db->where('hdr.fecha_baja <= ', $periodo_hasta->format('Y-m-d 23:59:59'));
                if($periodo_alta_desde) $query = $this->db->where('hdr.create >= ', $periodo_alta_desde->format('Y-m-d 00:00:00'));
                if($periodo_alta_hasta) $query = $this->db->where('hdr.create <= ', $periodo_alta_hasta->format('Y-m-d 23:59:59'));
                if($sucursal_id) $query = $this->db->where('sc.sucursal_id = ', $sucursal_id);

                $query = $this->db
                        ->select('  hdr.id AS hdr_id,
                                    DATE_FORMAT(ci.fecha_pedido,"%d-%m-%Y") AS fecha,
                                    sp.pieza_id,
                                    hdr.cartero_id AS cartero_id,
                                    sc.apellido_nombre,
                                    s.id AS codigo_servicio,
                                    s.nombre AS servicio,
                                    ci.cliente_id,
                                    c.nombre,
                                    pe.precio,
                                    COUNT(sp.pieza_id) AS cantidad_piezas,
                                    pe.precio as precio_cliente,
                                    (COUNT(sp.pieza_id) * pe.precio) AS precio_cartero,
                                    p.estado_id')
                        ->join('flash_piezas p', 'p.id = sp.pieza_id')
                        ->join('flash_hojas_rutas hdr', 'hdr.id = sp.hoja_ruta_id')
                        ->join('flash_comprobantes_ingresos ci', 'ci.id = p.comprobante_ingreso_id')
                        ->join('flash_comprobantes_ingresos_servicios cis', 'cis.id = p.servicio_id AND ci.id = cis.comprobante_ingreso_id')
                        ->join('flash_servicios s', 's.id = cis.servicio_id')
                        ->join('flash_clientes c', 'c.id = ci.cliente_id')
                        ->join('flash_clientes_precios_especiales pe', 'pe.servicio_id = cis.servicio_id AND pe.cliente_id = ci.cliente_id','left')
                        ->join('flash_sucursales_carteros sc', 'sc.id = hdr.cartero_id')
                        ->where('hdr.fecha_baja IS NOT NULL')
                        ->group_by('hdr.cartero_id, p.servicio_id,  hdr.id')
                        ->order_by('hdr.id')
                        ->get('flash_subpiezas sp');

                //echo $this->db->last_query();die;
                return $query->result();
            }*/

            public function getCarterosPendientesLiquidar($periodo_desde, $periodo_hasta,$periodo_alta_desde, $periodo_alta_hasta, $sucursal_id){
            $and= "";
            if($periodo_desde) $and .= ' AND hdr2.fecha_baja >= "'. $periodo_desde->format('Y-m-d 00:00:00').'"';
            if($periodo_hasta) $and .= ' AND hdr2.fecha_baja <= "'. $periodo_hasta->format('Y-m-d 23:59:59').'"';
            if($periodo_alta_desde) $and .= ' AND hdr2.create >= "'. $periodo_alta_desde->format('Y-m-d 00:00:00').'"';
            if($periodo_alta_hasta) $and .= ' AND hdr2.create <= "'. $periodo_alta_hasta->format('Y-m-d 23:59:59').'"';
            if($sucursal_id) $and .= ' AND sc2.sucursal_id = '. $sucursal_id;

            $subquery = "   SELECT sp2.id FROM flash_subpiezas sp2
	                    INNER JOIN flash_hojas_rutas hdr2 ON sp2.hoja_ruta_id = hdr2.id
	                    INNER JOIN `flash_sucursales_carteros` `sc2` ON `sc2`.`id` = `hdr2`.`cartero_id`
	                    inner join flash_piezas p on sp2.pieza_id = p.id
              			inner join flash_piezas_estados_variables ev2 on sp2.pieza_estado_id = ev2.id 
	                    WHERE hdr2.fecha_baja IS NOT NULL
	                    and ev2.pieza_estado_id = 2 
	                    ".$and."
                      	     group by sp2.pieza_id";

                if($periodo_desde) $query = $this->db->where('hdr.fecha_baja >= ', $periodo_desde->format('Y-m-d 00:00:00'));
                if($periodo_hasta) $query = $this->db->where('hdr.fecha_baja <= ', $periodo_hasta->format('Y-m-d 23:59:59'));
                if($periodo_alta_desde) $query = $this->db->where('hdr.create >= ', $periodo_alta_desde->format('Y-m-d 00:00:00'));
                if($periodo_alta_hasta) $query = $this->db->where('hdr.create <= ', $periodo_alta_hasta->format('Y-m-d 23:59:59'));
                if($sucursal_id) $query = $this->db->where('sc.sucursal_id = ', $sucursal_id);


                $query = $this->db
                        ->select('  hdr.id AS hdr_id,
                                    DATE_FORMAT(ci.fecha_pedido,"%d-%m-%Y") AS fecha,
                                    sp.pieza_id,
                                    hdr.cartero_id AS cartero_id,
                                    sc.apellido_nombre,
                                    s.id AS codigo_servicio,
                                    s.nombre AS servicio,
                                    ci.cliente_id,
                                    c.nombre,
                                    pe.precio,
                                    COUNT(sp.pieza_id) AS cantidad_piezas,
                                    pe.precio as precio_cliente,
                                    (COUNT(sp.pieza_id) * pe.precio) AS precio_cartero,
                                    p.estado_id')
                        ->join('flash_piezas p', 'p.id = sp.pieza_id')
                        ->join('flash_hojas_rutas hdr', 'hdr.id = sp.hoja_ruta_id')
                        ->join('flash_comprobantes_ingresos ci', 'ci.id = p.comprobante_ingreso_id')
                        ->join('flash_comprobantes_ingresos_servicios cis', 'cis.id = p.servicio_id')
                        ->join('flash_servicios s', 's.id = cis.servicio_id')
                        ->join('flash_clientes c', 'c.id = ci.cliente_id')
                        ->join('flash_clientes_precios_especiales pe', 'pe.servicio_id = cis.servicio_id AND pe.cliente_id = ci.cliente_id','left')
                        ->join('flash_sucursales_carteros sc', 'sc.id = hdr.cartero_id')
                        ->join('flash_piezas_estados_variables ev', 'ev.id = p.estado_id')
                        //->where('s.grupo_id NOT IN (4,14,9,5) ')    //Excluir Paqueteria
                        ->where('hdr.fecha_baja IS NOT NULL')
                        ->where('ev.pieza_estado_id = 2')
                        ->where('sp.id IN ('.$subquery.'  )')
                        ->group_by('hdr.cartero_id, p.servicio_id,  hdr.id')
                        ->order_by('hdr.id')
                        ->get('flash_subpiezas sp');

                //echo $this->db->last_query();die;
                return $query->result();
            }

            public function comprobantes_ingresos_fecha_sucursal($periodo_desde, $periodo_hasta, $sucursal, $cliente_id = -1){
                ini_set('memory_limit','-1');
                set_time_limit(1800);
                if ($periodo_desde){
                    $periodo_desde = new DateTime($periodo_desde);
                    $query = $this->db->where('ci.create >= ', $periodo_desde->format('Y-m-d 00:00:00'));
                }
                if($periodo_hasta){
                    $periodo_hasta = new DateTime($periodo_hasta);
                    $query = $this->db->where('ci.create <= ', $periodo_hasta->format('Y-m-d 23:59:59'));
                }
                if($sucursal && $sucursal > 0){
                    $query = $this->db->where('suc.id = ', $sucursal);
                } 
                if($cliente_id && $cliente_id > 0){
                    $query = $this->db->where('c.id = ', $cliente_id);
                }               
                $query = $this->db
                        ->select('  ci.numero numero,
                                    c.id cliente_id,
                                    c.nombre cliente,
                                    ct.nombre tipo,
                                    s.nombre servicio,
                                    cis.cantidad piezas,
                                    pe.`precio` precio,
	                                (cis.cantidad * pe.`precio`) subtotal,
                                    date_format(ci.create,"%d-%m-%Y") fecha_creacion,
                                    suc.nombre sucursal,
                                    cd.nombre departamento,
                                    g.nombre grupo')
                        ->join('flash_comprobantes_ingresos_servicios cis', 'ci.id = cis.comprobante_ingreso_id')
                        ->join('flash_servicios s', 'cis.servicio_id = s.id')
                        ->join('flash_clientes_precios_especiales pe', 'pe.servicio_id = s.id and pe.cliente_id = ci.cliente_id')
                        ->join('flash_clientes c', 'ci.cliente_id = c.id')
                        ->join('flash_clientes_tipos ct', 'c.tipo_cliente_id = ct.id')
                        ->join('flash_sucursales suc', 'ci.sucursal_id = suc.id')
                        ->join('flash_clientes_departamentos cd', 'ci.departamento_id = cd.id')
                        ->join('flash_servicios_grupos g', 's.grupo_id = g.id')
                        //->group_by('p.servicio_id')
                        ->get('flash_comprobantes_ingresos ci');
                //echo $this->db->last_query();die;
                return $query->result();
            }

        function getListaComprobantesGenerados(){
            $user_row = $this->ion_auth->user()->row();
            if (!$this->ion_auth->in_group(array(1, 2), $user_row->id)) $query = $this->db->where('hdr.sucursal_id', $user_row->sucursal_id);

            $query = $this->db
                            ->select('cig.id id, pt.id talonario_id,
                                        cig.numero numero,
                                        ptr.apellido apellido,
                                        ptr.nombre,
                                        cig.estado,
                                        cig.create')
                            ->join('flash_piezas_talonarios_responsables ptr', 'pt.responsable_id = ptr.id')
                            ->join('flash_comprobantes_ingresos_generados cig', 'cig.talonario_id = pt.id')
                            ->where('estado = 1')
                            ->get('flash_piezas_talonarios pt');
    //echo $this->db->last_query();die;
            return $query->result();
        }

        function getListaHojasRutas($sucursal_id, $limit = 25, $start = 0, $hdr_id = null)
        {
            $user_row = $this->ion_auth->user()->row();
            if (!$this->ion_auth->in_group(array(1, 2), $user_row->id)) $query = $this->db->where('hdr.sucursal_id', $user_row->sucursal_id);
	    if ($hdr_id != null) $query = $this->db->where('hdr.id', $hdr_id);
            $query = $this->db
                ->select('  hdr.id id,
                            sc.apellido_nombre cartero,
                            d.apellido_nombre distribuidor,
                            sz.nombre zona,
                            DATE_FORMAT(hdr.fecha_entrega,"%d-%m-%Y") fecha_entrega,
                            (CASE hdr.estado
                                    WHEN 1 THEN "INICIADA"
                                    WHEN 2 THEN "CERRADA"
                                    WHEN 3 THEN "BAJA"
                                    WHEN 4 THEN "ARCHIVADA"
                                    WHEN 5 THEN "CANCELADA"
                            END) AS estado_nombre,
                            hdr.estado estado,
                            t.nombre transporte ')
                ->join('flash_sucursales_carteros  sc', 'hdr.cartero_id = sc.id','left')
                ->join('flash_distribuidores  d', 'hdr.distribuidor_id = d.id','left')
                ->join('flash_sucursales_zonas sz', 'hdr.zona_id = sz.id','left')
                ->join('flash_transportes t', ' hdr.transporte_id = t.id','left')
                ->where('hdr.estado <> '.Hoja::ESTADO_ARCHIVADA)
                ->where('hdr.sucursal_id = '.$sucursal_id)
                ->order_by('hdr.create DESC')
                ->limit($limit, $start)
                ->get('flash_hojas_rutas hdr');
// echo($this->db->last_query());die;
            return $query->result();
        }

        function getListaHojasRutasTotal($sucursal_id, $hdr_id = null)
        {
            $user_row = $this->ion_auth->user()->row();
            if (!$this->ion_auth->in_group(array(1, 2), $user_row->id)) $query = $this->db->where('hdr.sucursal_id', $user_row->sucursal_id);
	    if ($hdr_id != null) $query = $this->db->where('hdr.id', $hdr_id);
            $query = $this->db
                ->select('  hdr.id id,
                            sc.apellido_nombre cartero,
                            d.apellido_nombre distribuidor,
                            sz.nombre zona,
                            DATE_FORMAT(hdr.fecha_entrega,"%d-%m-%Y") fecha_entrega,
                            (CASE hdr.estado
                                    WHEN 1 THEN "INICIADA"
                                    WHEN 2 THEN "CERRADA"
                                    WHEN 3 THEN "BAJA"
                                    WHEN 4 THEN "ARCHIVADA"
                                    WHEN 5 THEN "CANCELADA"
                            END) AS estado_nombre,
                            hdr.estado estado,
                            t.nombre transporte ')
                ->join('flash_sucursales_carteros  sc', 'hdr.cartero_id = sc.id','left')
                ->join('flash_distribuidores  d', 'hdr.distribuidor_id = d.id','left')
                ->join('flash_sucursales_zonas sz', 'hdr.zona_id = sz.id','left')
                ->join('flash_transportes t', ' hdr.transporte_id = t.id','left')
                ->where('hdr.estado <> '.Hoja::ESTADO_ARCHIVADA)
                ->where('hdr.sucursal_id = '.$sucursal_id)
                ->order_by('hdr.create DESC')
                ->from('flash_hojas_rutas hdr');

            $retorno = $query->count_all_results();
            return $retorno;
        }


        function getPiezasNormalesDisponiblesNovedades(){
            $user_row = $this->ion_auth->user()->row();
            $is_user = $this->ion_auth->in_group(array(3), $user_row->id);

            //Todas las piezas que no esten en estado de rendicion

            if(!$this->ion_auth->is_admin($user_row->id))  $query = $this->db->where('p.sucursal_id', $user_row->sucursal_id);

            $query = $this->db
                            ->select('p.*, s.id AS servicio_id, s.nombre AS nombre_servicio,p.estado_id,p.id,  c.numero as comprobante_nro')
                            ->join('flash_comprobantes_ingresos_servicios cis', 'cis.id = p.servicio_id')
                            ->join('flash_servicios s', 's.id = cis.servicio_id')
                            ->join('flash_piezas_estados_variables pev', 'pev.id = p.estado_id')
                            ->join('flash_comprobantes_ingresos c', 'c.id = p.comprobante_ingreso_id')
                            ->where('pev.pieza_estado_id NOT IN (2)')
                            ->where('p.tipo_id = '.Pieza::TIPO_NORMAL) //Solo trae las piezas normales
                            ->get('flash_piezas p');
    //        $result = $query->result();
            //echo($this->db->last_query());die;
            return $query->result();
         }

         public function getPiezasPendientesLiquidar($date_desde,$date_hasta,$departamento_id,$sucursal_id, $cliente_id){
                $cliente_id = $this->input->post('cliente_id');
                $periodo_desde = $date_desde != ''?$date_desde:false;
                $periodo_hasta = $date_hasta != ''?$date_hasta:false;
                if ($periodo_desde) $date_desde = new DateTime($periodo_desde);
                if ($periodo_hasta) $date_hasta = new DateTime($periodo_hasta);
                if($periodo_desde){
                    $query = $this->db->where('ci.create >= ', $date_desde->format('Y-m-d 00:00:00'));
                }
                if($periodo_hasta){
                    $query = $this->db->where('ci.create <= ', $date_hasta->format('Y-m-d 23:59:59'));
                }
                $departamento_id = $departamento_id != ''?$departamento_id:false;
                $sucursal_id = $sucursal_id != ''?$sucursal_id:false;
                if ($departamento_id){
                    $query = $this->db->where('ci.departamento_id =', (int)$departamento_id);
                }
                if ($sucursal_id){
                    $query = $this->db->where('ci.sucursal_id = ', (int)$sucursal_id);
                }

                $query = $this->db
                        ->select('  CONCAT(c.id,s.nombre,"") as unido,
                                    c.nombre,
                                    ci.numero,
                                    cis.remito,
                                    DATE_FORMAT(ci.create,"%d-%m-%Y") AS fecha,
                                    s.id AS servicio_id,
                                    s.nombre AS servicio,
                                    cis.cantidad AS cantidad,
                                    cpe.precio,
                                    (ci.cantidad * cpe.precio) AS importe ')
                        ->join('flash_comprobantes_ingresos_servicios cis', 'ci.id = cis.comprobante_ingreso_id')
                        ->join('flash_servicios s', 'cis.servicio_id = s.id')
                        ->join('flash_clientes_precios_especiales cpe', 'cpe.cliente_id = ci.cliente_id AND cis.servicio_id = cpe.servicio_id')
                        ->join('flash_clientes c', 'ci.cliente_id = c.id')
                        ->where('ci.cliente_id = '.$cliente_id)
                        ->where('ci.liquidacion_id = 0')
                        ->where('ci.cantidad IS NOT NULL')
                        ->order_by('ci.create ')
                        ->get('flash_comprobantes_ingresos ci');
                //echo $this->db->last_query();die;
                return $query->result();
            }

            public function getLiquidacionEdit($liquidacion_id){


                $query = $this->db
                        ->select('ld.*, ld.remito_cliente as remito, ld.comprobante_ingreso as numero, c.nombre')
                        ->join('flash_liquidaciones l', 'l.id = ld.liquidacion_id')
                        ->join('flash_clientes c', 'c.id = l.cliente_id')
                        ->where('ld.liquidacion_id = '.$liquidacion_id)
                        ->get('flash_liquidaciones_detalles ld');
                //echo $this->db->last_query()."<br/>";
                $query_resumen = $this->db
                        ->select('ld.servicio_id AS codigo, ld.servicio AS servicio, SUM(ld.cantidad) AS cantidad ')
                        ->where('ld.liquidacion_id = '.$liquidacion_id)
                        ->group_by('ld.servicio_id')
                        ->get('flash_liquidaciones_detalles ld');
                //echo $this->db->last_query();die;
                $json = $query->result();
                $json_resumen = $query_resumen->result();
                return array('grilla' => $json, 'resumen' => $json_resumen);
    //            if($json) echo json_encode(array('grilla' => $json,'resumen' => $json_resumen));
    //            else echo json_encode(array('status' => 'none'));
            }

            public function getPiezasSimplesNovedadesPorComprobante($estado_id,$comprobante_nro,$servicio_id,$cantidad,$limite_inferior){
                $user_row = $this->ion_auth->user()->row();
                $is_user = $this->ion_auth->in_group(array(3), $user_row->id);
                /*if(!$this->ion_auth->is_admin($user_row->id))*/  $query = $this->db->where('p.sucursal_id', $user_row->sucursal_id);
                    $Comprobante_ingreso_id =  Comprobante::whereNumero($comprobante_nro)->first();
                    $query = $this->db
                            ->select('p.id, p.estado_id')
                            ->join('flash_comprobantes_ingresos_servicios cis', 'cis.id = p.servicio_id ')
                            ->join('flash_piezas_estados_variables ev', 'p.estado_id = ev.id')
                            ->join('flash_rendiciones_piezas rp', 'rp.pieza_id = p.id','left')
                            ->where('p.comprobante_ingreso_id = '.$Comprobante_ingreso_id->id)
                            ->where('cis.servicio_id = '.$servicio_id)
                            ->where('p.tipo_id = '.Pieza::TIPO_SIMPLE)
                            ->where('ev.pieza_estado_id NOT IN (2)') //ESTADO CATEGORIA RENDICIONES
                            ->where('rp.id is null')
                            //CAMBIO_23_07_2017->where('p.estado_id IN ('.Pieza::ESTADO_EN_DISTRIBUCION.','.Pieza::ESTADO_EN_GESTION.','.Pieza::ESTADO_NO_RESPONDE.','.Pieza::ESTADO_EN_TRNSITO.')')
                            ->limit($cantidad, $limite_inferior)
                            ->get('flash_piezas p');
                    //echo $this->db->last_query()."<br>";die;
                    return $query->result_array();
            }

             public function getPiezasSimplesSinNovedades($estado_id,$comprobante_nro,$servicio_id,$cantidad,$limite_inferior){

                    $Comprobante_ingreso_id =  Comprobante::whereNumero($comprobante_nro)->first();
                    $this->db
                            ->select('p.id')
                            ->from('flash_piezas p')
                            ->join('flash_comprobantes_ingresos_servicios cis', 'cis.id = p.servicio_id ')
                            ->where('p.comprobante_ingreso_id = '.$Comprobante_ingreso_id->id)
                            ->where('cis.servicio_id = '.$servicio_id)
                            ->where('p.tipo_id = '.Pieza::TIPO_SIMPLE)
                            ->where('p.estado_id IN ('.Pieza::ESTADO_EN_DISTRIBUCION.','.Pieza::ESTADO_EN_GESTION.','.Pieza::ESTADO_NO_RESPONDE.','.Pieza::ESTADO_EN_TRNSITO.')')
                            ->limit($cantidad, $limite_inferior);


                    $subQuery =  $this->db->get_compiled_select();

                    $this->db->select('*')
                            ->from('flash_piezas_novedades')
                            ->where("id NOT IN ($subQuery)", NULL, FALSE)
                            ->get()
                            ->result();

                   // echo $this->db->last_query()."<br>";die;
            }

             public function getPiezasSimplesConNovedades($piezas){
                    foreach($piezas as $pieza){
                        $piezas_array[] = $pieza['id'];
                    }
                    $this->db->group_start();
                    $sale_ids_chunk = array_chunk($piezas_array,25);
                    foreach($sale_ids_chunk as $sale_ids)
                    {
                        $this->db->where_in('pieza_id', $sale_ids);
                    }
                    $this->db->group_end();

                    $query = $this->db
                            ->select('pieza_id, estado_actual_id')
                            ->get('flash_piezas_novedades');
                    return $query->result_array();
            }

            public function grabarDetalleRendicion($cliente_id,$departamento_id,$sucursal_id,$servicio_id,
                                                    $numero,$rendicion_id,$fecha_hasta, 
                                                    $sucursal_id_apostada = null, $fecha_desde = null, 
                                                    $excluir = null, $busqueda = null){
    //            $cliente_id = $cliente_id!=''?$cliente_id:false;
    //            $departamento_id = $departamento_id != ''?$departamento_id:false;
    //            $sucursal_id = $sucursal_id != ''?$sucursal_id:false;
    //            $servicio_id = $servicio_id != ''?$servicio_id:false;
    //            $numero = $numero != ''?$numero:false;
                
                $where = "";
                if($cliente_id){
                    $where .= ' AND ci.cliente_id = '. $cliente_id ;
                }
               // if($departamento_id){
                 //   $where .= ' AND ci.departamento_id = '. $departamento_id;
                //}
                if ($sucursal_id){
                    $where .= ' AND ci.sucursal_id ='. $sucursal_id;
                }
                if ($servicio_id){
                    $where .= ' AND cis.servicio_id = '. $servicio_id;
                }
                if ($numero){
                    $where .= ' AND ci.numero = '. $numero;
                }
                if ($fecha_hasta){
                    $where .= ' AND n.date_create <= "'. $fecha_hasta.'"';
                }
                if ($sucursal_id_apostada > 0){
                    $where .= ' AND p.sucursal_id ='. $sucursal_id_apostada;
                }
                if ($fecha_desde){
                    $where .= ' AND n.date_create >= "'. $fecha_desde.'"';
                }
                if ($excluir!=null){
                    $excluir = implode(",",$excluir);
                    $where .= ' AND p.id NOT IN ( '. $excluir.')';
                }
                if ($busqueda!=null){
                    $where .= " AND   (CONVERT(p.id,CHAR) LIKE '%".$busqueda."%' ESCAPE '!' 
                                OR  ci.numero LIKE '%".$busqueda."%' ESCAPE '!' 
                                OR  DATE_FORMAT(ci.create,'%d-%m-%Y') LIKE '%".$busqueda."%' ESCAPE '!'
                                OR  s.nombre LIKE '%".$busqueda."%' ESCAPE '!'
                                OR  p.barcode_externo LIKE '%".$busqueda."%' ESCAPE '!'
                                OR  e.nombre LIKE '%rue%' ESCAPE '!'
                                OR  p.destinatario LIKE '%".$busqueda."%' ESCAPE '!'
                                OR  p.domicilio LIKE '%".$busqueda."%' ESCAPE '!'
                                OR  p.codigo_postal LIKE '%".$busqueda."%' ESCAPE '!'
                                OR  recibio LIKE '%".$busqueda."%' ESCAPE '!'
                                OR  documento LIKE '%".$busqueda."%' ESCAPE '!'
                                OR  vinculo LIKE '%".$busqueda."%' ESCAPE '!'
                                OR  d.nombre LIKE '%".$busqueda."%' ESCAPE '!'
                                OR  p.datos_varios_3 LIKE '%".$busqueda."%' ESCAPE '!'
                                OR  CONCAT(u.apellido,' ',u.nombre) LIKE '%".$busqueda."%' ESCAPE '!')";
                }

                $sql = " INSERT  INTO flash_rendiciones_piezas (rendicion_id, pieza_id)
                            SELECT
                           $rendicion_id as rendicion_id,  p.id

                            FROM (SELECT * FROM flash_comprobantes_ingresos WHERE sucursal_id = $sucursal_id  AND departamento_id = $departamento_id) ci
                            JOIN `flash_piezas` `p` ON `ci`.`id` = `p`.`comprobante_ingreso_id`
                            JOIN `flash_comprobantes_ingresos_servicios` `cis` ON `cis`.`id` = `p`.`servicio_id`
                            JOIN `flash_servicios` `s` ON `s`.`id` = `cis`.`servicio_id`
                            JOIN `flash_piezas_estados_variables` `e` ON `e`.`id` = `p`.`estado_id`
                            JOIN `piezas_tracking_vw` `n` ON `n`.`pieza_id` = `p`.`id`
                            JOIN `flash_clientes_departamentos` `d` ON `d`.`id` = `ci`.`departamento_id`
                            JOIN `users` `u` ON `u`.`id` = `n`.`usuario_id`
                            /*FROM `flash_piezas` `p`
                            JOIN `flash_comprobantes_ingresos_servicios` `cis` ON `cis`.`id` = `p`.`servicio_id`
                            JOIN `flash_comprobantes_ingresos` `ci` ON `ci`.`id` = `cis`.`comprobante_ingreso_id`
                            JOIN `flash_piezas_estados_variables` `e` ON `e`.`id` = `p`.`estado_id`
                            JOIN `flash_piezas_novedades` `n` ON `n`.`pieza_id` = `p`.`id`
                            JOIN flash_servicios s ON s.id = cis.servicio_id
                            JOIN flash_clientes_departamentos d ON  d.id = ci.departamento_id*/
                          WHERE `p`.`id` NOT IN(SELECT
                                                  rp.pieza_id
                                                FROM flash_rendiciones_piezas AS rp
                                                WHERE rp.pieza_id IS NOT NULL)
                              AND `e`.`pieza_estado_id` = 2 "
                        .$where;

                //echo date("H:i:s")."<br/>";
                //echo "......".$sql;die;
                $query = $this->db->query($sql);

               // echo date("H:i:s")."<br/>";
                return array("cantidad_piezas" => $this->db->affected_rows());
            }

            public function precio_por_cliente_servicio($periodo_desde, $periodo_hasta){
                if ($periodo_desde){
                    $periodo_desde = new DateTime($periodo_desde);
                    $query = $this->db->where('l.periodo_desde >= ', $periodo_desde->format('Y-m-d 00:00:00'));
                }
                if($periodo_hasta){
                    $periodo_hasta = new DateTime($periodo_hasta);
                    $query = $this->db->where('l.periodo_hasta <= ', $periodo_hasta->format('Y-m-d 23:59:59'));
                }

                $query = $this->db
                        ->select('  ci.numero numero,
                                    ci.liquidacion_id liquidacion_id,
                                    suc.nombre sucursal,
                                    c.nombre cliente,
                                    s.nombre servicio,
                                    g.nombre grupo,
                                    DATE_FORMAT(ci.create, "%d-%m-%Y") fecha_creacion,
                                    sum(ld.cantidad) piezas,
                                    cd.nombre departamento,
                                    ld.precio precio,
                                    l.id liquidacion_id,
                                    l.factura factura')
                        ->join('flash_liquidaciones l', 'l.id = ld.liquidacion_id')
                        ->join('flash_servicios s', 'ld.servicio_id = s.id')
                        ->join('flash_servicios_grupos g', 's.grupo_id = g.id')
                        ->join('flash_sucursales suc', 'l.sucursal_id = suc.id')
                        ->join('flash_comprobantes_ingresos ci', 'ci.numero = ld.comprobante_ingreso')
                        ->join('flash_clientes c', 'l.cliente_id = c.id')
                        ->join('flash_clientes_departamentos cd', 'l.departamento_id = cd.id')
                        ->where('ci.liquidacion_id = l.id')
                        ->group_by('l.id, ci.id, l.cliente_id, s.id')
                        ->get('flash_liquidaciones_detalles ld');
                //echo $this->db->last_query();die;
                return $query->result();
            }

        function getPiezasPorHDRGroupSimples($hoja_ruta_id){
            $user_row = $this->ion_auth->user()->row();
            $query1 = $this->db
                            ->select(' c.nombre cliente, "" as cantidad, sp.pieza_id pieza_id, p.tipo_id, pt.nombre tipo , p.servicio_id servicio_id, s.nombre servicio, ev.nombre estado, p.barcode, p.destinatario, p.domicilio, p.localidad, p.codigo_postal ')
                            ->join('flash_subpiezas sp', 'hdr.id = sp.hoja_ruta_id')
                            ->join('flash_piezas p', 'sp.pieza_id = p.id')
                            ->join('flash_comprobantes_ingresos_servicios cis', 'p.servicio_id = cis.id')
                            ->join('flash_servicios s', 'cis.servicio_id = s.id')
                            ->join('flash_piezas_estados_variables ev', 'p.estado_id = ev.id')
                            ->join('flash_piezas_tipos pt', 'p.tipo_id = pt.id')
                            ->join('flash_comprobantes_ingresos ci', 'ci.id = p.comprobante_ingreso_id')
                            ->join('flash_clientes c', 'ci.cliente_id = c.id')
                            ->where('hdr.id = '.$hoja_ruta_id)
                            ->where('p.tipo_id = '.Pieza::TIPO_NORMAL)
                            ->get('flash_hojas_rutas hdr');
            $join1 = $this->db->last_query();
            $query2 = $this->db
                            ->select(' c.nombre cliente, count(*) cantidad, sp.pieza_id pieza_id, p.tipo_id, pt.nombre tipo , p.servicio_id servicio_id, s.nombre servicio, ev.nombre estado, p.barcode, p.destinatario, p.domicilio, p.localidad, p.codigo_postal ')
                            ->join('flash_subpiezas sp', 'hdr.id = sp.hoja_ruta_id')
                            ->join('flash_piezas p', 'sp.pieza_id = p.id')
                            ->join('flash_comprobantes_ingresos_servicios cis', 'p.servicio_id = cis.id')
                            ->join('flash_servicios s', 'cis.servicio_id = s.id')
                            ->join('flash_piezas_estados_variables ev', 'p.estado_id = ev.id')
                            ->join('flash_piezas_tipos pt', 'p.tipo_id = pt.id')
                            ->join('flash_comprobantes_ingresos ci', 'ci.id = p.comprobante_ingreso_id')
                            ->join('flash_clientes c', 'ci.cliente_id = c.id')

                            ->where('hdr.id = '.$hoja_ruta_id)
                            ->where('p.tipo_id = '.Pieza::TIPO_SIMPLE)
                            ->group_by('p.tipo_id, p.servicio_id')
                            ->get('flash_hojas_rutas hdr');
            $join2 = $this->db->last_query();

            $union_query = $this->db->query($join1.' UNION '.$join2);
    //        $result = $union_query->result();
    //       echo($this->db->last_query());die;
            return $union_query->result();
        }

        public function getDespachosList($sucursal_id = NULL){
    //            foreach($piezas as $pieza){
    //                $piezas_array[] = $pieza['id'];
    //            }
    //            $this->db->group_start();
    //            $sale_ids_chunk = array_chunk($piezas_array,25);
    //            foreach($sale_ids_chunk as $sale_ids)
    //            {
    //                $this->db->where_in('pieza_id', $sale_ids);
    //            }
    //            $this->db->group_end();
                if($sucursal_id != NULL) $query = $this->db->where('sd.id = ',$sucursal_id);

                $query = $this->db
                        ->select('  d.id id,
                                    d.origen_id,
                                    so.nombre sucursalOrigen,
                                    d.destino_id,
                                    sd.nombre sucursalDestino,
                                    DATE_FORMAT(d.fecha_envio,"%d-%m-%Y") fecha_envio,
                                    d.estado,
                                    (CASE d.estado WHEN 1 THEN "ESTADO INICIADO"
                                                    WHEN 2 THEN "ESTADO ENVIADO"
                                                    WHEN 3 THEN "ESTADO RECIBIDO"
                                                    WHEN 4 THEN "ESTADO VERIFICADO"
                                                    WHEN 5 THEN "ARCHIVADO EN ORIGEN"
                                                    WHEN 6 THEN "ARCHIVADO EN DESTINO"
                                    END) AS estadoNombre,
                                    d.create create')
                            ->join('flash_sucursales so', 'd.origen_id = so.id')
                            ->join('flash_sucursales sd', 'd.destino_id = sd.id')
                            ->order_by('d.id DESC')
                            ->get('flash_piezas_despacho d');

                return $query->result();
        }

            public function getRendicionesFechaRendicion($periodo_desde, $periodo_hasta){
                if ($periodo_desde){
                    $periodo_desde = new DateTime($periodo_desde);
                    $query = $this->db->where('r.create >= ', $periodo_desde->format('Y-m-d 00:00:00'));
                }
                if($periodo_hasta){
                    $periodo_hasta = new DateTime($periodo_hasta);
                    $query = $this->db->where('r.create <= ', $periodo_hasta->format('Y-m-d 23:59:59'));
                }
                $query = $this->db
                        ->select('suc.nombre sucursal, ci.numero, DATE_FORMAT(ci.create,"%d-%m-%Y") fecha_comprobante, c.nombre cliente, r.id rendicion_id, DATE_FORMAT(r.create,"%d-%m-%Y") fecha, count(*) piezas')
                        ->join('flash_clientes c', 'r.clientes_id = c.id')
                        ->join('users u', 'r.usuario_id = u.id')
                        ->join('flash_sucursales suc', 'u.sucursal_id = suc.id')
                        ->join('flash_rendiciones_piezas rp', 'r.id = rp.rendicion_id')
                        ->join('flash_piezas p', 'rp.pieza_id = p.id')
                        ->join('flash_comprobantes_ingresos ci', 'p.comprobante_ingreso_id = ci.id')
                        ->join('flash_comprobantes_ingresos_servicios cis', 'cis.id = p.servicio_id')
                        ->join('flash_servicios s', 'cis.servicio_id = s.id')
                        ->where('s.grupo_id <> 4') //sin finishing
                        ->group_by('rp.rendicion_id, ci.id')
                        ->order_by('c.nombre ASC')
                        ->get('flash_rendiciones r');
                //echo $this->db->last_query();die;
                return $query->result();
            }


            function getListadoFacturacionSucursal($periodo_desde,$periodo_hasta){
                $date_desde = new DateTime($periodo_desde);
                $date_hasta = new DateTime($periodo_hasta);
                //var_dump($_POST);die;
                if($periodo_desde){
                    $query_facturacion_sucursal = $this->db->where('l.create >= ', $date_desde->format('Y-m-d 00:00:00'));
                }
                if($periodo_hasta){
                    $query_facturacion_sucursal = $this->db->where('l.create <= ', $date_hasta->format('Y-m-d 23:59:59'));
                }
                $query_facturacion_sucursal = $this->db
                        ->select('  suc.nombre sucursal, SUM(ld.cantidad) cantidad, SUM(ld.cantidad*ld.precio) facturacion ')
                        ->join('flash_liquidaciones_detalles ld', 'l.id = ld.liquidacion_id')
                        ->join('flash_sucursales suc', 'suc.id = l.sucursal_id')
                        ->group_by('l.sucursal_id')
                        ->order_by('suc.nombre')
                        ->get('flash_liquidaciones l');
                //echo($this->db->last_query());die;
                return $query_facturacion_sucursal->result();
            }

            function getListadoPreciosClientes($periodo_desde,$periodo_hasta){
                $date_desde = new DateTime($periodo_desde);
                $date_hasta = new DateTime($periodo_hasta);
                //var_dump($_POST);die;
                if($periodo_desde){
                    $query_precios_clientes = $this->db->where('ci.create >= ', $date_desde->format('Y-m-d 00:00:00'));
                }
                if($periodo_hasta){
                    $query_precios_clientes = $this->db->where('ci.create <= ', $date_hasta->format('Y-m-d 23:59:59'));
                }
                    $query_precios_clientes = $this->db
                            ->select('  ci.sucursal_id sucursal,
                                        suc.nombre sucursal,
                                        ci.cliente_id,
                                        c.nombre cliente,
                                        s.nombre servicio,
                                        sg.nombre grupo ,
                                        sum(ci.cantidad) cantidad,
                                        pe.precio precio_unitario
                                    ')
                            ->join('flash_comprobantes_ingresos_servicios cis', 'ci.id = cis.comprobante_ingreso_id')
                            ->join('flash_servicios s', 'cis.servicio_id = s.id')
                            ->join('flash_clientes_precios_especiales pe', 'pe.servicio_id = s.id')
                            ->join('flash_clientes c', 'c.id = ci.cliente_id')
                            ->join('flash_servicios_grupos sg', 'sg.id = s.grupo_id')
                            ->join('flash_sucursales suc', 'suc.id = ci.sucursal_id')
                            ->group_by('ci.cliente_id, cis.servicio_id, ci.id')
                            ->order_by('suc.nombre, c.nombre')
                            ->get('flash_comprobantes_ingresos ci');
                    //echo($this->db->last_query());die;
                    return $json_precios_clientes = $query_precios_clientes->result();
            }

            function getListadoIngresosFueraTiempo($dias){

                $query_periodo_hasta =   'SELECT DATE_SUB(NOW(), INTERVAL '.$dias.' DAY) dias ';
                //$date_hasta = $this->db->result($query_periodo_hasta
                $date_hasta = $this->db->query($query_periodo_hasta)->result();
                //var_dump($_POST);die;
                $query_ingresos = $this->db
                            ->select('  suc.nombre sucursal, c.nombre cliente, s.nombre servicio,
                                    ci.numero, ci.fecha_pedido,ci.cantidad total_comprobante ,
                                    cis.cantidad cantidad_servicio,
                                    SUM((SELECT COUNT(pie.id) 
                                            FROM flash_piezas pie 
                                            INNER JOIN flash_rendiciones_piezas rp ON rp.pieza_id = pie.id
                                            WHERE  pie.comprobante_ingreso_id = ci.id
                                            AND rp.pieza_id = pie.id
                                            AND pie.servicio_id = cis.id
                                             )) piezas_rendidas,
                                    SUM((SELECT COUNT(pie.id) 
                                            FROM flash_piezas pie 
                                            LEFT JOIN flash_rendiciones_piezas rp ON rp.pieza_id = pie.id
                                            WHERE  pie.comprobante_ingreso_id = ci.id
                                            AND rp.pieza_id IS NULL
                                            AND pie.servicio_id = cis.id
                                             )) piezas_pendientes,
                                    ((SELECT MAX(r.create) 
                                            FROM flash_piezas pie 
                                            INNER JOIN flash_rendiciones_piezas rp ON rp.pieza_id = pie.id
                                            INNER JOIN flash_rendiciones r ON rp.rendicion_id = r.id
                                            WHERE  pie.comprobante_ingreso_id = ci.id
                                            AND rp.pieza_id = pie.id
                                            AND pie.servicio_id = cis.id
                                             )) fecha_ultima_rendicion,
                                             ci.sucursal_id, ci.cliente_id, cis.servicio_id')
                        ->join('flash_comprobantes_ingresos_servicios cis', 'ci.id = cis.comprobante_ingreso_id')
                        ->join('flash_sucursales suc', 'ci.sucursal_id = suc.id')
                        ->join('flash_clientes c', 'ci.cliente_id = c.id')
                        ->join('flash_servicios s', 'cis.servicio_id = s.id')
                        ->where('ci.create <= ', $date_hasta[0]->dias)
                        ->where('s.grupo_id <> 4')
                        ->group_by('cis.id, ci.id'/*'ci.sucursal_id, ci.id, s.id'*/)
                        ->order_by('suc.nombre, ci.numero, ci.fecha_pedido')
                    ->having(' piezas_pendientes > 0')
                        ->get('flash_comprobantes_ingresos ci');
                    //echo($this->db->last_query());die;
                    return $json_ingresos = $query_ingresos->result();
            }

            function getListadoVtasClientes($periodo_desde,$periodo_hasta ){
                $date_desde = new DateTime($periodo_desde);
                $date_hasta = new DateTime($periodo_hasta);
                //var_dump($_POST);die;
                if($periodo_desde){
                    $query_vtas_clientes = $this->db->where('l.create >= ', $date_desde->format('Y-m-d 00:00:00'));
                }
                if($periodo_hasta){
                    $query_vtas_clientes = $this->db->where('l.create <= ', $date_hasta->format('Y-m-d 23:59:59'));
                }
                    /*$query_vtas_clientes = $this->db
                            ->select('  c.nombre cliente,
                                        SUM(ld.cantidad) cantidad,
                                        SUM(ld.precio*ld.cantidad) ventas,
                                        (SUM(ld.precio*ld.cantidad))/SUM(ld.cantidad) precio_promedio
                                    ')
                            ->join('flash_liquidaciones l', 'l.id = ld.liquidacion_id')
                            ->join('flash_sucursales suc', 'l.sucursal_id = suc.id')
                            ->join('flash_clientes c', 'l.cliente_id = c.id')
                            ->group_by('c.id')
                            ->order_by('c.nombre')
                            ->get('flash_liquidaciones_detalles ld');*/
                            
                            $query_vtas_clientes = $this->db
                            ->select('  c.nombre cliente,
                                        SUM(lc.cant_a_liquidar) cantidad, 
                                        SUM(lc.precio*lc.cant_a_liquidar) ventas, 
                                        (SUM(lc.precio*lc.cant_a_liquidar))/SUM(lc.cant_a_liquidar) precio_promedio
                                    ')
                            ->join('flash_liquidaciones_clientes l', 'l.id = lc.liquidacion_cliente_id')
                            ->join('flash_sucursales suc', 'l.sucursal_id = suc.id')
                            ->join('flash_clientes c', 'l.cliente_id = c.id')
                            ->group_by('c.id')
                            ->order_by('c.nombre')
                            ->get('flash_liquidaciones_comprobantes lc');
                   //echo($this->db->last_query());die;
                    return $query_vtas_clientes->result();
            }

            function getListadoVtasProductos($periodo_desde,$periodo_hasta){
                $date_desde = new DateTime($periodo_desde);
                $date_hasta = new DateTime($periodo_hasta);
                //var_dump($_POST);die;
                if($periodo_desde){
                    $query_cantidad = $this->db->where('l.periodo_desde >= ', $date_desde->format('Y-m-d 00:00:00'));
                    //$query_ventas = $this->db->where('l.create >= ', $date_desde->format('Y-m-d 00:00:00'));
                }
                if($periodo_hasta){
                    $query_cantidad = $this->db->where('l.periodo_hasta <= ', $date_hasta->format('Y-m-d 23:59:59'));
                    //$query_ventas = $this->db->where('l.create <= ', $date_hasta->format('Y-m-d 23:59:59'));
                }
                    /*$query_cantidad = $this->db
                            ->select('  suc.id sucursal_id,
                                        suc.nombre sucursal,
                                        g.id grupo_id,
                                        g.nombre grupo,
                                        SUM(ld.cantidad) cantidad,
                                        SUM(ld.precio*ld.cantidad) ventas,
                                        (SUM(ld.precio*ld.cantidad))/SUM(ld.cantidad) precio_promedio')
                            ->join('flash_liquidaciones l', 'l.id = ld.liquidacion_id')
                            ->join('flash_servicios s', 'ld.servicio_id = s.id')
                            ->join('flash_servicios_grupos g', 's.grupo_id = g.id')
                            ->join('flash_sucursales suc', 'l.sucursal_id = suc.id')
                            ->join('flash_comprobantes_ingresos ci', ' ci.numero = ld.comprobante_ingreso')
                            ->where('ci.liquidacion_id = l.id')

                            ->group_by('l.sucursal_id, g.id')
                            ->order_by('suc.nombre,g.nombre')
                            ->get('flash_liquidaciones_detalles ld');*/
                            
                    $query_cantidad = $this->db
                            ->select('  suc.id sucursal_id, 
                                        suc.nombre sucursal, 
                                        g.id grupo_id,
                                        g.nombre grupo, 
                                        SUM(lc.cant_a_liquidar) cantidad,
                                        SUM(lc.precio*lc.cant_a_liquidar) ventas,
                                        (SUM(lc.precio*lc.cant_a_liquidar))/SUM(lc.cant_a_liquidar) precio_promedio
                                    ')
                            ->join('flash_liquidaciones_clientes l', 'l.id = lc.liquidacion_cliente_id')
                            ->join('flash_servicios s', 'lc.servicio_id = s.id')
                            ->join('flash_servicios_grupos g', 's.grupo_id = g.id')
                            ->join('flash_sucursales suc', 'l.sucursal_id = suc.id')
                            ->join('flash_comprobantes_ingresos ci', ' ci.id = lc.comprobante_ingreso_id')
                            ->where('ci.liquidacion_id = l.id')

                            ->group_by('l.sucursal_id, g.id')
                            ->order_by('suc.nombre,g.nombre')
                            ->get('flash_liquidaciones_comprobantes lc');
                    //echo($this->db->last_query());die;
                    if($periodo_desde){
                        $query_ventas = $this->db->where('l.create >= ', $date_desde->format('Y-m-d 00:00:00'));
                    }
                    if($periodo_hasta){
                        $query_ventas = $this->db->where('l.create <= ', $date_hasta->format('Y-m-d 23:59:59'));
                    }
                    /*$query_ventas = $this->db
                            ->select('  suc.id sucursal_id,
                                        suc.nombre sucursal,
                                        g.id grupo_id,
                                        g.nombre grupo,
                                        SUM(ld.cantidad) cantidad,
                                        SUM(ld.precio*ld.cantidad) ventas,
                                        (SUM(ld.precio*ld.cantidad))/SUM(ld.cantidad) precio_promedio')
                            ->join('flash_servicios s', 'ld.servicio_id = s.id')
                            ->join('flash_servicios_grupos g', 's.grupo_id = g.id')
                            ->join('flash_liquidaciones l', 'l.id = ld.liquidacion_id')
                            ->join('flash_sucursales suc', 'l.sucursal_id = suc.id')
                            ->group_by('l.sucursal_id, g.id')
                            ->order_by('suc.nombre,g.nombre')
                            ->get('flash_liquidaciones_detalles ld');*/
                    
                    $query_ventas = $this->db
                            ->select('  suc.id sucursal_id,
                                        suc.nombre sucursal,
                                        g.id grupo_id, 
                                        g.nombre grupo, 
                                        SUM(lc.cant_a_liquidar) cantidad,
                                        SUM(lc.precio*lc.cant_a_liquidar) ventas,
                                        (SUM(lc.precio*lc.cant_a_liquidar))/SUM(lc.cant_a_liquidar) precio_promedio
                                    ')
                            ->join('flash_servicios s', 'lc.servicio_id = s.id')
                            ->join('flash_servicios_grupos g', 's.grupo_id = g.id')
                            ->join('flash_liquidaciones_clientes l', 'l.id = lc.liquidacion_cliente_id')
                            ->join('flash_sucursales suc', 'l.sucursal_id = suc.id')
                            ->group_by('l.sucursal_id, g.id')
                            ->order_by('suc.nombre,g.nombre')
                            ->get('flash_liquidaciones_comprobantes lc');
                    //echo($this->db->last_query());die;
                    $query_grupos = $this->db
                            ->select('  id, nombre')
                            ->order_by('nombre')
                            ->get('flash_servicios_grupos');

                    $query_sucursales = $this->db
                            ->select('  id, nombre')
                            ->order_by('nombre')
                            ->get('flash_sucursales');
                    $json_cantidad = $query_cantidad->result();
                    $json_ventas = $query_ventas->result();
                    $json_grupos = $query_grupos->result();
                    $json_sucursales = $query_sucursales->result();
                    $cantidades = [];
                    $ventas = [];
                    $precios_promedio = [];
                    $tiene_cantidad =  false;
                    $tiene_ventas =  false;
                    $tiene_precio_promedio =  false;
                    //$tiene_pendiente =  false;
                    foreach ($json_grupos as $grupo) {
                        foreach ($json_sucursales as $sucursal) {
                            //echo $sucursal->nombre." - ".$grupo->nombre;
                            foreach ($json_cantidad as $cantidad) {
                                    if($grupo->id == $cantidad->grupo_id && $sucursal->id == $cantidad->sucursal_id){
                                        //echo " ".$ingreso->cantidad." - ";
                                        array_push($cantidades, $cantidad->cantidad);
                                        $tiene_cantidad = true; break;
                                    }
                            }
                            foreach ($json_ventas as $venta) {
                                    if($grupo->id == $venta->grupo_id && $sucursal->id == $venta->sucursal_id){
                                        //echo " ".$rendicion->cantidad." - ";
                                        array_push($ventas,$venta->ventas );
                                        $division = ($cantidades[count($cantidades)-1] > 0)?$ventas[count($ventas)-1] /$cantidades[count($cantidades)-1]:0;
                                        //echo " ".$resta."  ";
                                        array_push($precios_promedio, (round($division,3)));
                                        $tiene_ventas = TRUE;
                                        $tiene_precio_promedio = TRUE;
                                        break;
                                    }
                            }
                            if (!$tiene_cantidad) array_push($cantidades, '0');
                            if (!$tiene_ventas) array_push($ventas, '0');
                            if (!$tiene_precio_promedio) array_push($precios_promedio, '0');
                            //echo "<br/>";
                            $tiene_cantidad =  false;
                            $tiene_ventas =  false;
                            $tiene_precio_promedio =  false;
                        }
                    }

                    if($json_cantidad && $json_ventas)
                        return array('cantidades' => $cantidades,
                                                'ventas'=> $ventas,
                                                'precios_promedio'=> $precios_promedio,
                                                'sucursales'=> $json_sucursales,
                                                'grupos'=> $json_grupos);
                    else return json_encode(array('status' => 'none'));
            }

            function getListadoOperativo($periodo_desde, $periodo_hasta){
                $date_desde = new DateTime($periodo_desde);
                $date_hasta = new DateTime($periodo_hasta);
                //var_dump($_POST);die;
                if($periodo_desde){
                    $query_ingresos = $this->db->where('ci.create >= ', $date_desde->format('Y-m-d 00:00:00'));
                    //$query_rendidas = $this->db->where('ci.create >= ', $date_desde->format('Y-m-d 00:00:00'));
                }
                if($periodo_hasta){
                    $query_ingresos = $this->db->where('ci.create <= ', $date_hasta->format('Y-m-d 23:59:59'));
                    //$query_rendidas = $this->db->where('ci.create <= ', $date_hasta->format('Y-m-d 23:59:59'));
                }
                    $query_ingresos = $this->db
                            ->select('  suc.id sucursal_id,
                                        suc.nombre sucursal,
                                        g.id grupo_id,
                                        g.nombre producto,
                                        SUM(cis.cantidad ) cantidad
                                    ')
                            ->join('flash_comprobantes_ingresos_servicios cis', 'ci.id = cis.comprobante_ingreso_id')
                            ->join('flash_servicios s', 'cis.servicio_id = s.id')
                            ->join('flash_clientes c', 'ci.cliente_id = c.id')
                            ->join('flash_clientes_tipos ct', 'c.tipo_cliente_id = ct.id')
                            ->join('flash_sucursales suc', 'ci.sucursal_id = suc.id')
                            ->join('flash_clientes_departamentos cd', 'ci.departamento_id = cd.id')
                            ->join('flash_servicios_grupos g', 's.grupo_id = g.id')
                            ->group_by('ci.sucursal_id, g.id')
                            ->order_by('suc.nombre,g.nombre')
                            ->get('flash_comprobantes_ingresos ci');
    //                echo($this->db->last_query());die;
                    if($periodo_desde){
                        $query_rendidas = $this->db->where('ci.create >= ', $date_desde->format('Y-m-d 00:00:00'));
                    }
                    if($periodo_hasta){
                        $query_rendidas = $this->db->where('ci.create <= ', $date_hasta->format('Y-m-d 23:59:59'));
                    }
                    $query_rendidas = $this->db
                            ->select('  suc.id sucursal_id,
                                        suc.nombre sucursal,
                                        g.id grupo_id,
                                        g.nombre producto,
                                        COUNT(cis.cantidad ) cantidad
                                    ')
                            ->join('flash_comprobantes_ingresos ci', 'ci.id = p.comprobante_ingreso_id')
                            ->join('flash_comprobantes_ingresos_servicios cis', 'ci.id = cis.comprobante_ingreso_id')
                            ->join('flash_servicios s', 'cis.servicio_id = s.id')
                            ->join('flash_clientes c', 'ci.cliente_id = c.id')
                            ->join('flash_clientes_tipos ct', 'c.tipo_cliente_id = ct.id')
                            ->join('flash_sucursales suc', 'ci.sucursal_id = suc.id')
                            ->join('flash_clientes_departamentos cd', 'ci.departamento_id = cd.id')
                            ->join('flash_servicios_grupos g', 's.grupo_id = g.id')
                            ->join('flash_rendiciones_piezas rp', 'rp.pieza_id = p.id')
                            ->group_by('ci.sucursal_id, g.id')
                            ->order_by('suc.nombre,s.nombre')
                            ->get('flash_piezas p');
                    //echo($this->db->last_query());die;
                    $query_grupos = $this->db
                            ->select('  id, nombre')
                            ->order_by('nombre')
                            ->get('flash_servicios_grupos');

                    $query_sucursales = $this->db
                            ->select('  id, nombre')
                            ->order_by('nombre')
                            ->get('flash_sucursales');
                    $json_ingresos = $query_ingresos->result();
                    $json_rendidas = $query_rendidas->result();
                    $json_grupos = $query_grupos->result();
                    $json_sucursales = $query_sucursales->result();
                    $ingresadas = [];
                    $rendidas = [];
                    $pendientes = [];
                    $tiene_ingreso =  false;
                    $tiene_rendida =  false;
                    $tiene_pendiente =  false;
                    foreach ($json_grupos as $grupo) {
                        foreach ($json_sucursales as $sucursal) {
                            //echo $sucursal->nombre." - ".$grupo->nombre;
                            foreach ($json_ingresos as $ingreso) {
                                    if($grupo->id == $ingreso->grupo_id && $sucursal->id == $ingreso->sucursal_id){
                                        //echo " ".$ingreso->cantidad." - ";
                                        array_push($ingresadas, $ingreso->cantidad);
                                        $tiene_ingreso = true; break;
                                    }
                            }
                            foreach ($json_rendidas as $rendicion) {
                                    if($grupo->id == $rendicion->grupo_id && $sucursal->id == $rendicion->sucursal_id){
                                        //echo " ".$rendicion->cantidad." - ";
                                        array_push($rendidas,$rendicion->cantidad );
                                        $resta = $ingresadas[count($ingresadas)-1] - $rendidas[count($rendidas)-1];
                                        //echo " ".$resta."  ";
                                        array_push($pendientes, ($resta));
                                        $tiene_rendida = TRUE;
                                        $tiene_pendiente = TRUE;
                                        break;
                                    }
                            }
                            if (!$tiene_ingreso) array_push($ingresadas, '0');
                            if (!$tiene_rendida) array_push($rendidas, '0');
                            if (!$tiene_pendiente) array_push($pendientes, '0');
                            //echo "<br/>";
                            $tiene_ingreso =  false;
                            $tiene_rendida =  false;
                            $tiene_pendiente =  false;
                        }
                    }

                    if($json_rendidas && $json_ingresos)
                        return (array('ingresadas' => $ingresadas,
                                                'rendidas'=> $rendidas,
                                                'pendientes'=> $pendientes,
                                                'sucursales'=> $json_sucursales,
                                                'grupos'=> $json_grupos));
                    else echo json_encode(array('status' => 'none'));
            }

            function getListadoDistribucionPendientesRendir($periodo_desde,$periodo_hasta,$sucursal_id,$mayor_a,$menor_a){
                $date_desde = new DateTime($periodo_desde);
                $dias = 0;
                //$date_hasta = new DateTime($periodo_hasta);
                $query_periodo_hasta =   'SELECT DATE_SUB(NOW(), INTERVAL '.$dias.' DAY) dias ';
                //$date_hasta = $this->db->result($query_periodo_hasta
                $date_hasta = $this->db->query($query_periodo_hasta)->result();

                if($periodo_desde){
                    $query_distribucion = $this->db->where('ci.create >= ', $date_desde->format('Y-m-d 00:00:00'));
                }
                if($periodo_hasta){
                    $query_distribucion = $this->db->where('ci.create <= ', $date_hasta[0]->dias);
                }
                $query_distribucion = $this->db
                        ->select(' DATE_FORMAT(ci.create,"%d-%m-%Y") fecha_ingreso,
                                    p.id pieza_id,
                                    ci.numero,
                                    c.nombre cliente,
                                    s.nombre servicio,
                                    sc.apellido_nombre cartero,
                                    hdr.id hoja_ruta_id,
                                    DATE_FORMAT(hdr.create,"%d-%m-%Y") fecha_creacion,
                                    p.destinatario,
                                    p.domicilio,
                                    p.codigo_postal,
                                    ev.nombre estado,
                                    (DATEDIFF(ci.create,CURRENT_DATE())) * -1 as dias')
                        ->join('flash_subpiezas sp', 'hdr.id = sp.hoja_ruta_id')
                        ->join('flash_piezas p', 'sp.pieza_id = p.id')
                        ->join('flash_comprobantes_ingresos ci', 'p.comprobante_ingreso_id = ci.id')
                        ->join('flash_comprobantes_ingresos_servicios cis', 'cis.comprobante_ingreso_id = ci.id')
                        ->join('flash_servicios s', 'cis.servicio_id = s.id')
                        ->join('flash_clientes c', 'ci.cliente_id = c.id')
                        ->join('flash_sucursales_carteros sc', 'hdr.cartero_id = sc.id')
                        ->join('flash_piezas_estados_variables ev', 'p.estado_id = ev.id')
                        ->join('flash_rendiciones_piezas rp', 'rp.pieza_id = p.id', 'LEFT')
                        ->where('p.tipo_id = '.Pieza::TIPO_NORMAL)
                        ->where('rp.id IS NULL')
                        ->where('p.sucursal_id = '.$sucursal_id)
                        ->where(' s.grupo_id <> 4 ')
                        ->having('dias >= '.$mayor_a)
                        ->having('dias <= '.$menor_a)
                        ->get('flash_hojas_rutas hdr');
                //echo($this->db->last_query());die;
                return $query_distribucion->result();
            }

            function getListadoVentasContado($periodo_desde,$periodo_hasta,$sucursal_id){
                $date_desde = new DateTime($periodo_desde);
                $date_hasta = new DateTime($periodo_hasta);

                if($periodo_desde){
                    $query_ventas_contado = $this->db->where('ci.create >= ', $date_desde->format('Y-m-d 00:00:00'));
                }
                if($periodo_hasta){
                    $query_ventas_contado = $this->db->where('ci.create <= ', $date_hasta->format('Y-m-d 23:59:59'));
                }
                $query_ventas_contado = $this->db
                        ->select(' ci.numero, ci.fecha_pedido fecha_pedido, c.nombre cliente, s.nombre servicio, ci.cantidad, pe.precio, (ci.cantidad * pe.precio) facturacion ')
                        ->join('flash_comprobantes_ingresos_servicios cis', 'cis.comprobante_ingreso_id = ci.id')
                        ->join('flash_clientes_precios_especiales pe', 'ci.cliente_id = pe.cliente_id')
                        ->join('flash_clientes c', 'ci.cliente_id = c.id')
                        ->join('flash_servicios s', 'cis.servicio_id = s.id')
                        ->where('c.tipo_cliente_id = 1') //Clientes pago contado
                        ->where('ci.sucursal_id = '.$sucursal_id)
                        ->get('flash_comprobantes_ingresos ci');
               // echo($this->db->last_query());die;
                return $query_ventas_contado->result();
            }

            public function getHDRPendientes($periodo_desde,$periodo_hasta,$sucursal_id, $sin_grupo = null){
    //          var_dump($_POST);
                $date_desde = new DateTime($periodo_desde);
                $date_hasta = new DateTime($periodo_hasta);
                if($periodo_desde){
                    $query = $this->db->where('hdr.create >= ', $date_desde->format('Y-m-d 00:00:00'));
                }
                if($periodo_hasta){
                    $query = $this->db->where('hdr.create <= ', $date_hasta->format('Y-m-d 23:59:59'));
                }
                if ($sucursal_id){
                    $query = $this->db->where('hdr.sucursal_id = ', (int)$sucursal_id);
                }
		if ($sin_grupo){
                    $query = $this->db->where('s.`grupo_id` <> 4  ');//Sin finishing
                }
                $query = $this->db
                        ->select('  hdr.id hdr_id, sc.apellido_nombre, DATE_FORMAT(hdr.create,"%d-%m-%Y") fecha_creacion, COUNT(*) piezas ')
                        ->join('flash_subpiezas sp', 'hdr.id = sp.hoja_ruta_id','left')
                        ->join('flash_sucursales_carteros sc', 'sc.id = hdr.cartero_id')
                        ->join('flash_piezas p', 'sp.pieza_id = p.id')
                        ->join('flash_comprobantes_ingresos_servicios cis', 'p.servicio_id = cis.id')
                        ->join('flash_servicios s', 'cis.servicio_id = s.id')
                        ->where('hdr.fecha_baja IS NULL')
                        ->where('hdr.estado <> '.Hoja::ESTADO_CANCELADA)
                        ->group_by('hdr.id')
                        ->order_by('hdr.create ')
                        ->get('flash_hojas_rutas hdr');
                //echo $this->db->last_query();die;
                return $query->result();
            }

            public function getHDRPendientesXCarteros($periodo_desde,$periodo_hasta,$cartero_id, $sucursal_id){


                if ($cartero_id){
                    $query = $this->db->where('hdr2.cartero_id =', (int)$cartero_id);
                }
                if ($sucursal_id){
                    $query = $this->db->where('hdr2.sucursal_id = ', (int)$sucursal_id);
                }
                if($periodo_desde){
                    $date_desde = new DateTime($periodo_desde);
                    $query = $this->db->where('ci2.create >= ', $date_desde->format('Y-m-d 00:00:00'));
                }
                if($periodo_hasta){
                    $date_hasta = new DateTime($periodo_hasta);
                    $query = $this->db->where('ci2.create <= ', $date_hasta->format('Y-m-d 23:59:59'));
                }
                
                $query = $this->db
                 ->select('  c2.nombre cliente,
			sc2.apellido_nombre,
			sp2.pieza_id,
			s2.nombre servicio,
			hdr2.id hoja_ruta_id,
			p2.barcode_externo codigo_externo,
			DATE_FORMAT(hdr2.create, "%d-%m-%Y") fecha_creacion_hdr,
			DATE_FORMAT(ci2.create, "%d-%m-%Y") fecha_ingreso,
			ci2.numero,
			p2.destinatario, p2.domicilio, p2.localidad '
		    )
            ->join('flash_hojas_rutas hdr2', 'hdr2.id = sp2.hoja_ruta_id')
            ->join('flash_sucursales_carteros sc2', 'sc2.id = hdr2.cartero_id')
            ->join('flash_piezas p2', 'sp2.pieza_id = p2.id')
            ->join('flash_comprobantes_ingresos ci2', 'p2.comprobante_ingreso_id = ci2.id')
            ->join('flash_sucursales suc2', 'hdr2.sucursal_id = suc2.id')
            ->join('flash_clientes c2', 'c2.id = ci2.cliente_id')
            ->join('flash_comprobantes_ingresos_servicios cis2', 'cis2.id = p2.servicio_id')
            ->join('flash_servicios s2', 's2.id = cis2.servicio_id')
            ->join('flash_piezas_estados_variables ev2', 'ev2.id = sp2.pieza_estado_id')//porque busco las hdr pendientes independiente el estado actual de la pieza
            ->where('p2.tipo_id = ' . Pieza::TIPO_NORMAL)
            ->where('ev2.pieza_estado_id NOT IN ('.PiezaEstado::ESTADOS_RENDICIONES.','.PiezaEstado::ESTADOS_ORGANIZATIVOS.')')
            ->where('hdr2.estado NOT IN ('.Hoja::ESTADO_CANCELADA.')')
            ->where('s2.grupo_id <> 4') 
            ->order_by('sp2.pieza_id,sp2.id DESC')
            ->get('flash_subpiezas sp2');
            //echo($this->db->last_query());die;
            $consulta_interna = $this->db->last_query();
            $consulta_externa = "SELECT result.* 
				 FROM ( ".$consulta_interna ." ) result
	    			 GROUP BY result.pieza_id";
	    			// echo $consulta_externa;die;
        	$json_exe = $this->db->query($consulta_externa);
        	$json = $json_exe->result();

                return $json ;
            }


            public function getHDRPendientesXClientes($periodo_desde,$periodo_hasta,$cliente_id,$sucursal_id){
		ini_set('memory_limit','-1');
		set_time_limit(1800);
                if ($cliente_id){
                    $query = $this->db->where('ci.cliente_id =', (int)$cliente_id);
                }
                if ($sucursal_id){
                    $query = $this->db->where('(p.sucursal_id = '.(int)$sucursal_id.' OR ci.sucursal_id = '. (int) $sucursal_id.')' );
                    //$query = $this->db->or_where('ci.sucursal_id = ', (int) $sucursal_id);
                }
                if($periodo_desde){
                    $date_desde = new DateTime($periodo_desde);
                    $query = $this->db->where('ci.create >= ', $date_desde->format('Y-m-d 00:00:00'));
                }
                if($periodo_hasta){
                    $date_hasta = new DateTime($periodo_hasta);
                    $query = $this->db->where('ci.create <= ', $date_hasta->format('Y-m-d 23:59:59'));
                }

                $query = $this->db
                    ->select('suc.nombre sucursal, ci.cantidad cantidad,DATE_FORMAT(ci.create,"%d-%m-%Y") fecha_ingreso,
                                        ci.numero, c.nombre cliente, s.nombre servicio, p.id pieza_id, p.destinatario,
                                        p.domicilio, p.localidad, p.barcode_externo codigo_externo
                                        ')
                    ->join('flash_comprobantes_ingresos ci', 'p.comprobante_ingreso_id = ci.id')
                    ->join('flash_comprobantes_ingresos_servicios cis', 'p.servicio_id = cis.id')
                    ->join('flash_servicios s', 'cis.servicio_id = s.id')
                    ->join('flash_piezas_estados_variables ev', 'p.estado_id = ev.id')
                    ->join('flash_piezas_estados e', 'e.id = ev.pieza_estado_id')
                    ->join('flash_clientes c', 'ci.cliente_id = c.id')
                    ->join('flash_sucursales suc', 'ci.sucursal_id = suc.id')
                    ->where('p.tipo_id = ' . Pieza::TIPO_NORMAL)
                    ->where('e.id NOT IN (' . PiezaEstado::ESTADOS_RENDICIONES . ')')
                    ->where('ev.`pieza_estado_id` <> '.PiezaEstado::ESTADOS_ORGANIZATIVOS)
                    //->where('hdr.estado NOT IN ('.Hoja::ESTADO_CANCELADA.')')
                    ->where('s.grupo_id <> 4') // Sin finishing
                    ->get('flash_piezas p');
                    //echo $this->db->last_query();die;
    //            $query = $this->db
    //                    ->select('DATE_FORMAT(ci.create,"%d-%m-%Y") fecha_ingreso,
    //                            `ci`.`numero`,
    //                             c.nombre cliente,
    //                            `s`.`nombre` `servicio`,
    //                            `p`.`id` `pieza_id`,
    //                            `p`.`destinatario`,
    //                            `p`.`domicilio`,
    //                            `p`.`localidad`,
    //                            `p`.`barcode_externo` `codigo_externo`,
    //                            sp.hoja_ruta_id hoja_ruta_id')
    //                    ->join('flash_subpiezas sp', 'hdr.id = sp.hoja_ruta_id')
    //                    ->join('flash_piezas p', 'sp.pieza_id = p.id')
    //                    ->join('flash_comprobantes_ingresos ci', 'p.comprobante_ingreso_id = ci.id')
    //                    ->join('flash_comprobantes_ingresos_servicios cis', 'cis.comprobante_ingreso_id = ci.id')
    //                    ->join('flash_servicios s', 'cis.servicio_id = s.id')
    //                    ->join('flash_clientes c', 'ci.cliente_id = c.id')
    //                    ->join('flash_piezas_estados_variables ev', 'p.estado_id = ev.id')
    //                    ->join('flash_piezas_estados e', 'e.id = ev.pieza_estado_id')
    //                    ->where('p.tipo_id = '.Pieza::TIPO_NORMAL)
    //                    ->where('e.id IN (1,3)')
    //                    ->group_by('hdr.id, c.id')
    //                    ->get('flash_hojas_rutas hdr');

               //echo $this->db->last_query();die;
                return $query->result();
            }

            public function getEstadosVariablesCompletos(){
                $query = $this->db










                        ->select('e.id estado_id, e.nombre estado_nombre, ev.id estado_variable_id, ev.pieza_estado_id, ev.nombre estado_variable_nombre ')
                        ->join('flash_piezas_estados_variables ev', 'e.id = ev.pieza_estado_id')




                        ->get('flash_piezas_estados e');
               //echo $this->db->last_query();die;
                return $query->result();
            }

            public function getPiezasNormalesSinNovedadDesdeHDR($hoja_ruta_id){
                 $query = $this->db
                        ->select('p.id as piezas_id')
                        ->join('flash_subpiezas sp', 'hdr.id = sp.hoja_ruta_id')
                        ->join('flash_piezas p', 'p.id = sp.pieza_id')
                        ->join('flash_piezas_novedades n', 'n.pieza_id = p.id','left')
                        ->where('p.tipo_id = '.PIEZA_TIPO_NORMAL)


                        ->where('n.id IS NULL')
                        ->where('hdr.id = '.$hoja_ruta_id)
                        ->get('flash_hojas_rutas hdr');
                //echo $this->db->last_query();die;
                return $query->result();
            }

            public function getPiezasSimplesSinNovedadDesdeHDR($hoja_ruta_id){
                 $query = $this->db
                        ->select('p.id as piezas_id')
                        ->join('flash_subpiezas sp', 'hdr.id = sp.hoja_ruta_id')
                        ->join('flash_piezas p', 'p.id = sp.pieza_id')
                        ->join('flash_piezas_novedades n', 'n.pieza_id = p.id','left')
                        ->where('p.tipo_id = '.PIEZA_TIPO_SIMPLE)
                        ->where('n.id IS NULL')
                        ->where('hdr.id = '.$hoja_ruta_id)
                        ->get('flash_hojas_rutas hdr');
                //echo $this->db->last_query();die;
                return $query->result();
            }

            public function getPiezasSimplesSinNovedadDefinitivaDesdeHDR($hoja_ruta_id){
                 $query = $this->db
                        ->select('p.id as piezas_id')
                        ->join('flash_subpiezas sp', 'hdr.id = sp.hoja_ruta_id')
                        ->join('flash_piezas p', 'p.id = sp.pieza_id')
                        ->join('flash_piezas_novedades n', 'n.pieza_id = p.id','left')
                        ->where('p.tipo_id = '.PIEZA_TIPO_SIMPLE)
                        ->where('n.id IS NULL')
                        ->where('hdr.id = '.$hoja_ruta_id)
                        ->get('flash_hojas_rutas hdr');
                //echo $this->db->last_query();die;
                return $query->result();
            }

        public function getUserslogAll($username = null, $categoria = null, $descripcion = null, $limit = 25, $start = 0) {
            if ($username != '') $query = $this->db->like('u.username', $username);
            if ($categoria != '') $query = $this->db->like('ul.categoria', $categoria);
            if ($descripcion != '') $query = $this->db->like('ul.descripcion', $descripcion);
            $query = $this->db
                ->select('ul.id, u.username, ul.categoria, ul.descripcion, ul.origen, ul.destino, ul.create as fecha')
                ->join('users u', 'u.id = ul.user_id')
                ->order_by('ul.create asc,u.id desc')
                ->limit($limit, $start)
                ->get('users_log ul');
            return $query->result();
        }

        public function getUserslogCount($username = null, $categoria = null, $descripcion = null) {
            if ($username != '') $query = $this->db->like('u.username', $username);
            if ($descripcion != '') $query = $this->db->like('ul.descripcion', $descripcion);
            $query = $this->db
                ->select('ul.id, u.username, ul.categoria, ul.descripcion, ul.origen, ul.destino, ul.create as fecha')
                ->join('users u', 'u.id = ul.user_id')
                ->order_by('ul.create asc,u.id desc')
                ->from('users_log ul');
            $retorno = $query->count_all_results();
            return $retorno;
        }

        function getPiezasYEstadosPorHDR($hoja_ruta_id){
            $user_row = $this->ion_auth->user()->row();
            $query1 = $this->db
                            ->select(' c.nombre cliente, "" as cantidad, sp.pieza_id pieza_id, p.tipo_id, pt.nombre tipo , p.servicio_id servicio_id, s.nombre servicio, ev.nombre estado, p.barcode, p.destinatario, p.domicilio, p.localidad, p.codigo_postal ')
                            ->join('flash_subpiezas sp', 'hdr.id = sp.hoja_ruta_id')
                            ->join('flash_piezas p', 'sp.pieza_id = p.id')
                            ->join('flash_comprobantes_ingresos_servicios cis', 'p.servicio_id = cis.id')
                            ->join('flash_servicios s', 'cis.servicio_id = s.id')
                            ->join('flash_piezas_estados_variables ev', 'sp.pieza_estado_id = ev.id')
                            ->join('flash_piezas_tipos pt', 'p.tipo_id = pt.id')
                            ->join('flash_comprobantes_ingresos ci', 'ci.id = p.comprobante_ingreso_id')
                            ->join('flash_clientes c', 'ci.cliente_id = c.id')
                            ->where('hdr.id = '.$hoja_ruta_id)
                            ->where('p.tipo_id = '.Pieza::TIPO_NORMAL)
                            ->get('flash_hojas_rutas hdr');
            $join1 = $this->db->last_query();
            $query2 = $this->db
                            ->select(' c.nombre cliente, count(*) cantidad, sp.pieza_id pieza_id, p.tipo_id, pt.nombre tipo , p.servicio_id servicio_id, s.nombre servicio, ev.nombre estado, p.barcode, p.destinatario, p.domicilio, p.localidad, p.codigo_postal ')
                            ->join('flash_subpiezas sp', 'hdr.id = sp.hoja_ruta_id')
                            ->join('flash_piezas p', 'sp.pieza_id = p.id')
                            ->join('flash_comprobantes_ingresos_servicios cis', 'p.servicio_id = cis.id')
                            ->join('flash_servicios s', 'cis.servicio_id = s.id')
                            ->join('flash_piezas_estados_variables ev', 'sp.pieza_estado_id = ev.id')
                            ->join('flash_piezas_tipos pt', 'p.tipo_id = pt.id')
                            ->join('flash_comprobantes_ingresos ci', 'ci.id = p.comprobante_ingreso_id')
                            ->join('flash_clientes c', 'ci.cliente_id = c.id')
                            ->where('hdr.id = '.$hoja_ruta_id)
                            ->where('p.tipo_id = '.Pieza::TIPO_SIMPLE)
                            ->group_by('p.tipo_id, p.servicio_id')
                            ->get('flash_hojas_rutas hdr');
            $join2 = $this->db->last_query();

            $union_query = $this->db->query($join1.' UNION '.$join2);
    //        $result = $union_query->result();
           //echo($this->db->last_query());die;
            return $union_query->result();
        }				
        
    function getInfoDdisXZona($liquidacion_cartero_id,$periodo_desde,$periodo_hasta){
    //        $user_row = $this->ion_auth->user()->row();
    //        if (!$this->ion_auth->in_group(array(1, 2), $user_row->id)) $query = $this->db->where('sc.sucursal_id', $user_row->sucursal_id);

            $query = $this->db
                            ->select(' hdr.zona_id IdRecorrido, z.nombre zona,  lcd.servicio_id, s.nombre servicio, c.nombre cliente, lcd.precio_unitario precio,  sum(lcd.cantidad_piezas) piezas')
                            ->join('flash_liquidaciones_carteros_detalles lcd','lc.id = lcd.liquidacion_cartero_id')
                            ->join('flash_hojas_rutas hdr', 'lcd.hoja_de_ruta_id = hdr.id')
                            ->join('flash_sucursales_zonas z', 'hdr.zona_id = z.id')
                            ->join('flash_servicios s', 'lcd.servicio_id = s.id')
                            ->join('flash_clientes c', 'lcd.cliente_id = c.id')
                            ->where('liquidacion_cartero_id = '.$liquidacion_cartero_id)
                            ->where('s.grupo_id NOT IN (4,14,9,5) ')    //Excluir Paqueteria
                            ->group_by('z.id, lcd.servicio_id, lcd.cliente_id')
                            ->order_by('z.id')
                            ->get('flash_liquidaciones_carteros lc');
           //                 echo($this->db->last_query());die;
            return $query->result();
        }

    function getInfoDdisXDdis($liquidacion_cartero_id){
            $query = $this->db
                            //->select('sc.id, sc.apellido_nombre cartero, s.nombre servicio, c.nombre cliente, hdr.id hoja_ruta, pe.precio, sum(distinct(p.id)) suma_piezas, tc.nombre tipo_cartero')
                            ->select('  ct.nombre tipo_cartero,
                                        lcd.cartero_id, sc.apellido_nombre cartero,
                                        s.nombre servicio, c.nombre cliente, lcd.hoja_de_ruta_id hoja_ruta,
                                        sum(lcd.cantidad_piezas) suma_piezas')
                            ->join('flash_liquidaciones_carteros_detalles lcd','lc.id = lcd.liquidacion_cartero_id')
                            ->join('flash_sucursales_carteros sc', 'lcd.cartero_id = sc.id')
                            ->join('flash_servicios s', 'lcd.servicio_id = s.id')
                            ->join('flash_clientes c', 'lcd.cliente_id = c.id')
                            ->join('flash_sucursales_carteros_tipos ct', 'sc.cartero_tipo_id = ct.id')
                            ->where('s.grupo_id NOT IN (4,14,9,5) ')    //Excluir Paqueteria
                            ->where('lcd.liquidacion_cartero_id = '. $liquidacion_cartero_id)
                            ->group_by('lcd.hoja_de_ruta_id, lcd.cartero_id, lcd.cliente_id, lcd.servicio_id')
                            ->order_by('sc.apellido_nombre')
                            ->get('flash_liquidaciones_carteros lc');
                            //echo($this->db->last_query());die;
            return $query->result();
        }

    function getInfoDdisXDevoluciones($liquidacion_cartero_id){
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
                            ->where('s.grupo_id NOT IN (4,14,9,5) ')    //Excluir Paqueteria
                            ->where('n.estado_nuevo_id IN (4,5,6,7,8,9,10,11)')
                            ->where('lcd.liquidacion_cartero_id = '.$liquidacion_cartero_id)
                           // ->where('hdr.sucursal_id ='. $liquidacionCartero->sucursal_id)
                            ->group_by('n.estado_nuevo_id, s.id, sc.id')
                            ->order_by('sc.id, s.id, ev.id')
                            ->get('flash_hojas_rutas hdr');
            //echo($this->db->last_query());die;
            return $query->result();
        }
        
        function getHojasRutasRendidasDdis($liquidacion_ddi_id){

            $query = $this->db
                            ->select('hdr.id, sc.apellido_nombre, hdr.fecha_baja, DATE_FORMAT(hdr.create,"%d-%m-%Y") fecha_creacion')
                            ->join('flash_sucursales_carteros sc','hdr.cartero_id = sc.id')
                            ->join('flash_liquidaciones_ddis_hdrs_detalles lcd', 'lcd.hoja_de_ruta_id = hdr.id')
                            ->where('hdr.fecha_baja IS NOT NULL')
                            ->where('lcd.liquidacion_ddi_id = '.$liquidacion_ddi_id)
                            ->where('s.grupo_id IN (4,14,9,5) ')    //Excluir Paqueteria
                            ->group_by('hdr.id')
                            ->get('flash_hojas_rutas hdr');

           //echo($this->db->last_query());die;
            return $query->result();
        }
        
        function getHojasRutasDespachadasDdis($liquidacion_cartero_id){
            $user_row = $this->ion_auth->user()->row();
            if (!$this->ion_auth->in_group(array(1, 2), $user_row->id)) $query = $this->db->where('sc.sucursal_id', $user_row->sucursal_id);

            $query = $this->db
                            ->select('hdr.id, sc.apellido_nombre, hdr.fecha_baja, DATE_FORMAT(hdr.create,"%d-%m-%Y") fecha_creacion  ')
                            ->join('flash_sucursales_carteros sc','hdr.cartero_id = sc.id')
                            ->join('flash_liquidaciones_carteros_detalles lcd', 'lcd.hoja_de_ruta_id = hdr.id')
                            ->where('lcd.liquidacion_cartero_id = '.$liquidacion_cartero_id)
    //                        ->where('hdr.fecha_baja >= "'. $periodo_desde .'"')
    //                        ->where('hdr.fecha_baja <= "'. $periodo_hasta .'"')
                            ->group_by('hdr.id')
                            ->get('flash_hojas_rutas hdr');
            //$result = $query->result();
            //echo($this->db->last_query());die;
            return $query->result();
        }
        
        function getAcreditacionesDdis($liquidacion_ddi_id){
            $user_row = $this->ion_auth->user()->row();
//            if (!$this->ion_auth->in_group(array(1, 2), $user_row->id)) $query = $this->db->where('sc.sucursal_id', $user_row->sucursal_id);

            $query = "  SELECT lad.id acreditacion_ddi_detalle_id, c.apellido_nombre cartero, ac.concepto acreditacion,
                        CASE WHEN ac.tipo_concepto_id = 1 THEN 'GENERAL'
                             WHEN ac.tipo_concepto_id = 2 THEN 'INDIVIDUAL' END tipo_concepto, ac.importe, 
                             CONCAT(u.apellido,', ',u.nombre) autorizador,
                             DATE_FORMAT(lad.fecha_autorizacion,'%d-%m-%Y') fecha_autorizacion,
                             lad.autorizador_id,
                             l.id liquidacion_ddi_id
                        FROM flash_liquidaciones_ddis l
                        INNER JOIN flash_liquidaciones_ddis_acreditaciones_detalle lad ON lad.liquidacion_ddi_id = l.id
                        INNER JOIN flash_liquidaciones_ddis_autorizadores a ON lad.autorizador_id = a.id
                        INNER JOIN flash_liquidaciones_ddis_acreditaciones ac ON lad.acreditacion_id = ac.id
                        INNER JOIN flash_sucursales_carteros c ON lad.cartero_id = c.id
                        INNER JOIN users u ON a.user_id = u.id
                        WHERE l.id = ".$liquidacion_ddi_id;

//            echo($this->db->last_query());die;
            return $this->db->query($query)->result();
        }
        
        function getDescuentosDdis($liquidacion_ddi_id){
            $user_row = $this->ion_auth->user()->row();
            //if (!$this->ion_auth->in_group(array(1, 2), $user_row->id)) $query = $this->db->where('sc.sucursal_id', $user_row->sucursal_id);

            $query = "  SELECT lad.id descuento_ddi_detalle_id, c.apellido_nombre cartero, ac.concepto descuento,
                        CASE WHEN ac.tipo_concepto_id = 1 THEN 'GENERAL'
                             WHEN ac.tipo_concepto_id = 2 THEN 'INDIVIDUAL' END tipo_concepto, ac.importe, 
                             CONCAT(u.apellido,', ',u.nombre) apellido_nombre,
                             DATE_FORMAT(lad.fecha_autorizacion,'%d-%m-%Y') fecha_autorizacion,
                             lad.autorizador_id,
                             l.id liquidacion_ddi_id
                        FROM flash_liquidaciones_ddis l
                        INNER JOIN flash_liquidaciones_ddis_descuentos_detalle lad ON lad.liquidacion_ddi_id = l.id
                        INNER JOIN flash_liquidaciones_ddis_autorizadores a ON lad.autorizador_id = a.id
                        INNER JOIN flash_liquidaciones_ddis_descuentos ac ON lad.descuento_id = ac.id
                        INNER JOIN flash_sucursales_carteros c ON lad.cartero_id = c.id
                        INNER JOIN users u ON a.user_id = u.id
                        WHERE l.id = ".$liquidacion_ddi_id;
//                          echo $query;die;
//            echo($this->db->last_query());die;
            return $this->db->query($query)->result();
        }

            public function getListadoLiquidacionesClientes($periodo_desde, $periodo_hasta){
                if ($periodo_desde){
                    $periodo_desde = new DateTime($periodo_desde);
                    $query = $this->db->where('l.periodo_desde >= ', $periodo_desde->format('Y-m-d 00:00:00'));
                }
                if($periodo_hasta){
                    $periodo_hasta = new DateTime($periodo_hasta);
                    $query = $this->db->where('l.periodo_hasta <= ', $periodo_hasta->format('Y-m-d 23:59:59'));
                }

                $query = $this->db
                        ->select('  ci.numero numero,
                                    ld.liquidacion_cliente_id liquidacion_id,
                                    suc.nombre sucursal,
                                    c.nombre cliente,
                                    s.nombre servicio,
                                    g.nombre grupo,
                                    DATE_FORMAT(ci.create, "%d-%m-%Y") fecha_creacion,
                                    sum(ld.cant_a_liquidar) piezas,
                                    cd.nombre departamento,
                                    ld.precio precio,
                                    l.id liquidacion_cliente_id,
                                    l.factura factura')
                        ->join('flash_liquidaciones_clientes l', 'l.id = ld.liquidacion_cliente_id')
                        ->join('flash_servicios s', 'ld.servicio_id = s.id')
                        ->join('flash_servicios_grupos g', 's.grupo_id = g.id')
                        ->join('flash_sucursales suc', 'l.sucursal_id = suc.id')
                        ->join('flash_comprobantes_ingresos ci', 'ci.id = ld.comprobante_ingreso_id')
                        ->join('flash_clientes c', 'l.cliente_id = c.id')
                        ->join('flash_clientes_departamentos cd', 'l.departamento_id = cd.id')
                        ->where('ci.id = ld.comprobante_ingreso_id')
                        ->group_by('l.id, ci.id, l.cliente_id, s.id')
                        ->get('flash_liquidaciones_comprobantes ld');
//                echo $this->db->last_query();die;
                return $query->result();
            }
            
        function getliquidacionesDdisResumen($liquidacion_cartero_id){ //Quinta pestaña
            $user_row = $this->ion_auth->user()->row();
            //if (!$this->ion_auth->in_group(array(1, 2), $user_row->id)) $query = $this->db->where('sc.sucursal_id', $user_row->sucursal_id);

            $query =  $this->db
                            ->select('  ct.nombre tipo_cartero,
                                        lcd.cartero_id, sc.apellido_nombre cartero,
                                        s.nombre servicio, c.nombre cliente, lcd.hoja_de_ruta_id hoja_ruta,
                                        sum(lcd.cantidad_piezas) suma_piezas, c.id cliente_id,
                                        hdr.zona_id, z.nombre zona,
                                        lc.id liquidacion_cartero_id')
                            ->join('flash_liquidaciones_carteros_detalles lcd','lc.id = lcd.liquidacion_cartero_id')
                            ->join('flash_sucursales_carteros sc', 'lcd.cartero_id = sc.id')
                            ->join('flash_servicios s', 'lcd.servicio_id = s.id')
                            ->join('flash_clientes c', 'lcd.cliente_id = c.id')
                            ->join('flash_sucursales_carteros_tipos ct', 'sc.cartero_tipo_id = ct.id')
                            ->join('flash_hojas_rutas hdr', 'hdr.id = lcd.hoja_de_ruta_id')
                            ->join('flash_sucursales_zonas z', 'z.id = hdr.zona_id')
                            ->join('flash_ddis_valores_entregas ve', 've.cliente_id = lcd.cliente_id')
                            ->where('lcd.liquidacion_cartero_id = '. $liquidacion_cartero_id)
                            ->where('ve.habilitado = 1 ')
                            ->where('s.grupo_id NOT IN (4,14,9,5) ')    //Excluir Paqueteria
                            ->group_by('lcd.cartero_id')
                            //->order_by('sc.apellido_nombre')
                            ->get('flash_liquidaciones_carteros lc');
          //echo($this->db->last_query());die;
            return $query->result();
        }
        
    function getliquidacionesDdisResumenClientes($liquidacion_ddi_id){ //Quinta pestaña
            $user_row = $this->ion_auth->user()->row();
            //if (!$this->ion_auth->in_group(array(1, 2), $user_row->id)) $query = $this->db->where('sc.sucursal_id', $user_row->sucursal_id);
            $query = $this->db
                            //->select('sc.id, sc.apellido_nombre cartero, s.nombre servicio, c.nombre cliente, hdr.id hoja_ruta, pe.precio, sum(distinct(p.id)) suma_piezas, tc.nombre tipo_cartero')
                            ->select('ve.cliente_id, c.nombre cliente, ve.pago valor_entrega,
                                     ve.zona_id, ve.cartero_id cartero_id, ve.servicio_id, s.nombre servicio')
                            ->join('flash_clientes c','c.id = ve.cliente_id')
                            ->join('flash_servicios s','s.id = ve.servicio_id')
                            ->where('ve.habilitado = 1')
                            ->where('s.grupo_id NOT IN (4,14,9,5) ')    //Excluir Paqueteria
                            ->group_by('ve.cliente_id, ve.servicio_id')
                            ->order_by('c.nombre')
                            ->get('flash_ddis_valores_entregas ve');
                            
                           /* $query = $this->db
                                    ->select('lcd.cliente_id, c.nombre cliente,  lcd.servicio_id, s.nombre servicio')
                                    ->join('flash_liquidaciones_carteros_detalles lcd','lc.id = lcd.liquidacion_cartero_id')
                                    ->join('flash_clientes c','c.id = lcd.cartero_id')
                                    ->join('flash_servicios s','s.id = lcd.servicio_id')
                                    ->where('lc.id = '.$liquidacion_ddi_id)
                                    //->where('s.grupo_id NOT IN (4,14,9,5) ')    //Excluir Paqueteria
                                    ->group_by('lcd.cliente_id, lcd.servicio_id')
                                    ->order_by('c.nombre')
                                    ->get('flash_liquidaciones_carteros lc');
                            */
                                                      
            //echo($this->db->last_query());die;
            return $query->result();
        }
        
    public function getDdisPendientesLiquidar($periodo_desde, $periodo_hasta,$periodo_alta_desde, $periodo_alta_hasta, $sucursal_id){
            $and= "";
            if($periodo_desde) $and .= ' AND hdr2.fecha_baja >= "'. $periodo_desde->format('Y-m-d 00:00:00').'"';
            if($periodo_hasta) $and .= ' AND hdr2.fecha_baja <= "'. $periodo_hasta->format('Y-m-d 23:59:59').'"';
            if($periodo_alta_desde) $and .= ' AND hdr2.create >= "'. $periodo_alta_desde->format('Y-m-d 00:00:00').'"';
            if($periodo_alta_hasta) $and .= ' AND hdr2.create <= "'. $periodo_alta_hasta->format('Y-m-d 23:59:59').'"';
            if($sucursal_id) $and .= ' AND sc2.sucursal_id = '. $sucursal_id;

            $subquery = "   SELECT sp2.id FROM flash_subpiezas sp2
	                    INNER JOIN flash_hojas_rutas hdr2 ON sp2.hoja_ruta_id = hdr2.id
	                    INNER JOIN `flash_sucursales_carteros` `sc2` ON `sc2`.`id` = `hdr2`.`cartero_id`
	                    inner join flash_piezas p on sp2.pieza_id = p.id
              			inner join flash_piezas_estados_variables ev2 on sp2.pieza_estado_id = ev2.id 
	                    WHERE hdr2.fecha_baja IS NOT NULL
	                    and ev2.pieza_estado_id = 2 
	                    ".$and."
                      	     group by sp2.pieza_id";

                if($periodo_desde) $query = $this->db->where('hdr.fecha_baja >= ', $periodo_desde->format('Y-m-d 00:00:00'));
                if($periodo_hasta) $query = $this->db->where('hdr.fecha_baja <= ', $periodo_hasta->format('Y-m-d 23:59:59'));
                if($periodo_alta_desde) $query = $this->db->where('hdr.create >= ', $periodo_alta_desde->format('Y-m-d 00:00:00'));
                if($periodo_alta_hasta) $query = $this->db->where('hdr.create <= ', $periodo_alta_hasta->format('Y-m-d 23:59:59'));
                if($sucursal_id) $query = $this->db->where('sc.sucursal_id = ', $sucursal_id);


                $query = $this->db
                        ->select('  hdr.id AS hdr_id,
                                    DATE_FORMAT(ci.fecha_pedido,"%d-%m-%Y") AS fecha,
                                    sp.pieza_id,
                                    hdr.cartero_id AS cartero_id,
                                    sc.apellido_nombre,
                                    s.id AS codigo_servicio,
                                    s.nombre AS servicio,
                                    ci.cliente_id,
                                    c.nombre,
                                    pe.precio,
                                    COUNT(sp.pieza_id) AS cantidad_piezas,
                                    pe.precio as precio_cliente,
                                    (COUNT(sp.pieza_id) * pe.precio) AS precio_cartero,
                                    p.estado_id')
                        ->join('flash_piezas p', 'p.id = sp.pieza_id')
                        ->join('flash_hojas_rutas hdr', 'hdr.id = sp.hoja_ruta_id')
                        ->join('flash_comprobantes_ingresos ci', 'ci.id = p.comprobante_ingreso_id')
                        ->join('flash_comprobantes_ingresos_servicios cis', 'cis.id = p.servicio_id')
                        ->join('flash_servicios s', 's.id = cis.servicio_id')
                        ->join('flash_clientes c', 'c.id = ci.cliente_id')
                        ->join('flash_clientes_precios_especiales pe', 'pe.servicio_id = cis.servicio_id AND pe.cliente_id = ci.cliente_id','left')
                        ->join('flash_sucursales_carteros sc', 'sc.id = hdr.cartero_id')
                        ->join('flash_piezas_estados_variables ev', 'ev.id = p.estado_id')
                        ->where('s.grupo_id NOT IN (4,14,9,5) ')    //Excluir Paqueteria
                        ->where('hdr.fecha_baja IS NOT NULL')
                        ->where('ev.pieza_estado_id = 2')
                        ->where('sp.id IN ('.$subquery.'  )')
                        ->group_by('hdr.cartero_id, p.servicio_id,  hdr.id')
                        ->order_by('hdr.id')
                        ->get('flash_subpiezas sp');

                //echo $this->db->last_query();die;
                return $query->result();
            }


    }