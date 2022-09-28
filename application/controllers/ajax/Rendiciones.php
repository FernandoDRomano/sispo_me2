<?php

class Rendiciones extends CI_Controller {

	function __construct() {
		parent::__construct();
	}

    public function getPiezasPendientesRendirNormales(){
        $cliente_id = $this->input->post('cliente_id')!=''?$this->input->post('cliente_id'):false;
        $departamento_id = $this->input->post('departamento_id') != ''?$this->input->post('departamento_id'):false;
        $servicio_id = $this->input->post('servicio_id') != ''?$this->input->post('servicio_id'):false;
        $numero = $this->input->post('numero') != ''?$this->input->post('numero'):false;
        $sucursal_id = $this->input->post('sucursal_id') != ''?$this->input->post('sucursal_id'):false;
        $limite_inferior = $this->input->post('limite_inferior') != ''?$this->input->post('limite_inferior'):false;
        $limite_inferior_simples = $this->input->post('limite_inferior_simples') != ''?$this->input->post('limite_inferior_simples'):false;
        $fecha_hasta = $this->input->post('fecha_hasta')!=''?$this->input->post('fecha_hasta'):false;
        $sucursal_id_apostada = $this->input->post('sucursal_id_apostada')!='' && $this->input->post('sucursal_id_apostada')>0 ?$this->input->post('sucursal_id_apostada'):false;
        $fecha_desde = $this->input->post('fecha_desde')!=''?$this->input->post('fecha_desde'):false;

        $busqueda = $this->input->post('busqueda')!=''?$this->input->post('busqueda'):false;
        
        if ($fecha_hasta){
            $fecha_hasta = new DateTime($fecha_hasta);
            $query = $this->db->where('n.date_create <= ', $fecha_hasta->format('Y-m-d 23:59:59'));
        }

        if ($fecha_desde){
            $fecha_desde = new DateTime($fecha_desde);
            $query = $this->db->where('n.date_create >= ', $fecha_desde->format('Y-m-d 00:00:00'));
        }

      
//            var_dump($_POST);die;
        if ($cliente_id) $query = $this->db->where('ci.cliente_id', $cliente_id);
        if ($departamento_id) $query = $this->db->where('ci.departamento_id', $departamento_id);
        if ($servicio_id) $query = $this->db->where('cis.servicio_id', $servicio_id);
        if ($numero) $query = $this->db->where('ci.numero', $numero);
        if ($sucursal_id) $query = $this->db->where('ci.sucursal_id = ', (int)$sucursal_id);
        if ($sucursal_id_apostada) $query = $this->db->where('p.sucursal_id = ', (int)$sucursal_id_apostada);
        
        if ($busqueda){
            $query = $this->db->group_start();
            $query = $this->db->like('CONVERT(p.id,char)',$busqueda);
            $query = $this->db->or_like('ci.numero',$busqueda);
            $query = $this->db->or_like('DATE_FORMAT(ci.create,"%d-%m-%Y")',$busqueda);
            $query = $this->db->or_like('s.nombre',$busqueda);
            $query = $this->db->or_like('p.barcode_externo',$busqueda);
            $query = $this->db->or_like('e.nombre',$busqueda);
            $query = $this->db->or_like('p.destinatario',$busqueda);
            $query = $this->db->or_like('p.domicilio',$busqueda);
            $query = $this->db->or_like('p.codigo_postal',$busqueda);
            $query = $this->db->or_like('recibio',$busqueda);
            $query = $this->db->or_like('documento',$busqueda);
            $query = $this->db->or_like('vinculo',$busqueda);
            $query = $this->db->or_like('d.nombre',$busqueda);
            $query = $this->db->or_like('CONCAT(u.apellido," ",u.nombre)',$busqueda);
            $query = $this->db->or_like('p.datos_varios_3',$busqueda);
            $query = $this->db->group_end();
            //$query = $this->db->where(' CONVERT(p.id,char)  LIKE "%'.$busqueda.'%"'); 
                           /*             OR ci.numero LIKE "%'.$busqueda.'%" 
                                        OR DATE_FORMAT(ci.create,"%d-%m-%Y") LIKE "%'.$busqueda.'%" 
                                        OR s.nombre LIKE "%'.$busqueda.'%" 
                                        OR p.barcode_externo LIKE "%'.$busqueda.'%" 
                                        OR e.nombre LIKE "%'.$busqueda.'%" 
                                        OR p.destinatario LIKE "%'.$busqueda.'%" 
                                        OR p.domicilio LIKE "%'.$busqueda.'%" 
                                        OR p.codigo_postal LIKE "%'.$busqueda.'%" 
                                        OR recibio LIKE "%'.$busqueda.'%" 
                                        OR documento LIKE "%'.$busqueda.'%" 
                                        OR vinculo LIKE "%'.$busqueda.'%" 
                                        OR d.nombre LIKE "%'.$busqueda.'%" 
                                        OR cantidad LIKE "%'.$busqueda.'%"  ' );
        */}
//            $limite_inferior = 0;
//            $cantidad = 1000;
//$subselect_apellido = " (SELECT us.apellido  FROM users us WHERE us.id = n.usuario_id) AS usuario_novedad_apellido ";
//$subselect_nombre = " (SELECT us.nombre  FROM users us WHERE us.id = n.usuario_id) AS usuario_novedad_nombre ";
        $query1 = $this->db
                ->select('  p.id AS pieza_id, 
                            ci.numero,
                            DATE_FORMAT(ci.create, "%d-%m-%Y") AS fecha,
                            s.nombre AS servicio,
                            p.barcode_externo,
                            e.nombre AS estado,
                            p.destinatario, 
                            p.domicilio, 
                            p.codigo_postal,
                            (CASE WHEN p.recibio IS NULL THEN "" ELSE p.recibio END) AS recibio, 
                            (CASE WHEN p.documento IS NULL THEN "" ELSE p.documento END) AS documento, 
                            (CASE WHEN p.vinculo IS NULL THEN "" ELSE p.vinculo END) AS vinculo,
                            (CASE WHEN n.date_create IS NOT NULL THEN DATE_FORMAT(n.date_create, "%d-%m-%Y") ELSE DATE_FORMAT(n.date_create, "%d-%m-%Y") END) AS `fecha_estado`,
                            d.nombre AS departamento, 
                            1 AS cantidad,
                            u.apellido user_apellido, u.nombre user_nombre,
                            p.datos_varios_3')
                ->join('flash_piezas p', 'ci.id = p.comprobante_ingreso_id')
                ->join('flash_comprobantes_ingresos_servicios cis', 'cis.id = p.servicio_id')
                ->join('flash_servicios s', 's.id = cis.servicio_id')
                ->join('flash_piezas_estados_variables e', 'e.id = p.estado_id')
                ->join('piezas_tracking_vw n', 'n.pieza_id = p.id')
                ->join('flash_clientes_departamentos d', 'd.id = ci.departamento_id')
                ->join('users u', 'u.id = n.usuario_id')

                ->where('p.id NOT IN (SELECT rp.pieza_id FROM flash_rendiciones_piezas AS rp WHERE rp.pieza_id IS NOT NULL)')
                ->where('e.pieza_estado_id = 2') //Estado: Rendiciones
                ->where('p.tipo_id = 2') //Normales
                ->limit(1000, $limite_inferior)
                ->get('(SELECT * FROM flash_comprobantes_ingresos WHERE sucursal_id = '.$sucursal_id.') ci');
        //echo $this->db->last_query()."<br/>";die;
        //if ($limite_inferior > 1000) {echo($this->db->last_query());die;}


        if ($query1->num_rows() > 0){
            $json = $query1->result();
        echo json_encode(array('grilla' => $json,'grilla1' => $this->db->last_query()));
        }else{
            echo json_encode(array('status' => 'end'));
        }


//            if($json) echo json_encode(array('grilla' => $json/*, 'resumen' => $json_resumen*/));
//            else echo json_encode(array('status' => 'none'));
}

        public function getPiezasPendientesRendirSimples(){
            $cliente_id = $this->input->post('cliente_id')!=''?$this->input->post('cliente_id'):false;
            $departamento_id = $this->input->post('departamento_id') != ''?$this->input->post('departamento_id'):false;
            $servicio_id = $this->input->post('servicio_id') != ''?$this->input->post('servicio_id'):false;
            $numero = $this->input->post('numero') != ''?$this->input->post('numero'):false;
            $sucursal_id = $this->input->post('sucursal_id') != ''?$this->input->post('sucursal_id'):false;
            $limite_inferior = $this->input->post('limite_inferior') != ''?$this->input->post('limite_inferior'):false;
            $fecha_hasta = $this->input->post('fecha_hasta')!=''?$this->input->post('fecha_hasta'):false;
            $sucursal_id_apostada = $this->input->post('sucursal_id_apostada')!='' && $this->input->post('sucursal_id_apostada')>0 ?$this->input->post('sucursal_id_apostada'):false;
            $fecha_desde = $this->input->post('fecha_desde')!=''?$this->input->post('fecha_desde'):false;

            if ($fecha_hasta){
                $fecha_hasta = new DateTime($fecha_hasta);
                $query = $this->db->where('n.update <= ', $fecha_hasta->format('Y-m-d 23:59:59'));
            }

            if ($fecha_desde){
                $fecha_desde = new DateTime($fecha_desde);
                $query = $this->db->where('n.update >= ', $fecha_desde->format('Y-m-d 00:00:00'));
            }

//            var_dump($_POST);die;
            if ($cliente_id) $query = $this->db->where('ci.cliente_id', $cliente_id);
            if ($departamento_id) $query = $this->db->where('ci.departamento_id', $departamento_id);
            if ($servicio_id) $query = $this->db->where('cis.servicio_id', $servicio_id);
            if ($numero) $query = $this->db->where('ci.numero', $numero);
            if ($sucursal_id) $query = $this->db->where('ci.sucursal_id = ', (int)$sucursal_id);
            if ($sucursal_id_apostada) $query = $this->db->where('p.sucursal_id = ', (int)$sucursal_id_apostada);

            if ($busqueda){
                $query = $this->db->group_start();
                $query = $this->db->like('CONVERT(p.id,char)',$busqueda);
                $query = $this->db->or_like('ci.numero',$busqueda);
                $query = $this->db->or_like('DATE_FORMAT(ci.create,"%d-%m-%Y")',$busqueda);
                $query = $this->db->or_like('s.nombre',$busqueda);
                $query = $this->db->or_like('p.barcode_externo',$busqueda);
                $query = $this->db->or_like('e.nombre',$busqueda);
                $query = $this->db->or_like('p.destinatario',$busqueda);
                $query = $this->db->or_like('p.domicilio',$busqueda);
                $query = $this->db->or_like('p.codigo_postal',$busqueda);
                $query = $this->db->or_like('recibio',$busqueda);
                $query = $this->db->or_like('documento',$busqueda);
                $query = $this->db->or_like('vinculo',$busqueda);
                $query = $this->db->or_like('d.nombre',$busqueda);
                $query = $this->db->or_like('p.datos_varios_3',$busqueda);
                //$query = $this->db->or_like('CONCAT(u.nombre," ",u.apellido)',$busqueda);
                $query = $this->db->group_end();
                //$query = $this->db->where(' CONVERT(p.id,char)  LIKE "%'.$busqueda.'%"'); 
                               /*             OR ci.numero LIKE "%'.$busqueda.'%" 
                                            OR DATE_FORMAT(ci.create,"%d-%m-%Y") LIKE "%'.$busqueda.'%" 
                                            OR s.nombre LIKE "%'.$busqueda.'%" 
                                            OR p.barcode_externo LIKE "%'.$busqueda.'%" 
                                            OR e.nombre LIKE "%'.$busqueda.'%" 
                                            OR p.destinatario LIKE "%'.$busqueda.'%" 
                                            OR p.domicilio LIKE "%'.$busqueda.'%" 
                                            OR p.codigo_postal LIKE "%'.$busqueda.'%" 
                                            OR recibio LIKE "%'.$busqueda.'%" 
                                            OR documento LIKE "%'.$busqueda.'%" 
                                            OR vinculo LIKE "%'.$busqueda.'%" 
                                            OR d.nombre LIKE "%'.$busqueda.'%" 
                                            OR cantidad LIKE "%'.$busqueda.'%"  ' );
            */}
            $subselect_apellido = " (SELECT us.apellido  FROM users us WHERE us.id = n.usuario_id) AS usuario_novedad_apellido ";
            $subselect_nombre = " (SELECT us.nombre  FROM users us WHERE us.id = n.usuario_id) AS usuario_novedad_nombre ";

                $query2 = $this->db
                    ->select(' p.id as pieza_id,
                                ci.numero,
                                DATE_FORMAT(ci.create,"%d-%m-%Y") AS fecha,
                                s.nombre AS servicio,
                                p.barcode_externo,
                                e.nombre AS estado,
                                p.destinatario,
                                p.domicilio,
                                p.codigo_postal,
                                (CASE WHEN p.recibio IS NULL THEN "" ELSE p.recibio END) AS recibio,
                                (CASE WHEN p.documento IS NULL THEN "" ELSE p.documento END) AS documento,
                                (CASE WHEN p.vinculo IS NULL THEN "" ELSE p.vinculo END) AS vinculo,
                                d.nombre as departamento,
                                (CASE WHEN n.update IS NOT NULL THEN DATE_FORMAT(n.update,"%d-%m-%Y") ELSE DATE_FORMAT(n.create,"%d-%m-%Y") END) as fecha_estado,
                                count(*) as cantidad,
                                '.$subselect_apellido.',
                                '.$subselect_nombre.'
                                ,p.datos_varios_3')
                    ->join('flash_comprobantes_ingresos_servicios cis', 'cis.id = p.servicio_id')
                    ->join('flash_comprobantes_ingresos ci', 'ci.id = cis.comprobante_ingreso_id')
                    ->join('flash_piezas_novedades n', 'n.pieza_id = p.id')
                    ->join('flash_servicios s', 's.id = cis.servicio_id')
                    ->join('flash_piezas_estados_variables e', 'e.id = p.estado_id')
                    ->join('flash_clientes_departamentos d', 'd.id = ci.departamento_id')
                    ->where('p.id NOT IN (SELECT rp.pieza_id FROM flash_rendiciones_piezas AS rp WHERE rp.pieza_id IS NOT NULL)')
                    ->where('e.pieza_estado_id = 2') //Estado: Rendiciones
                    ->where('p.tipo_id = 1') //Simples
                    ->group_by(' `ci`.`id`, `ci`.`cliente_id`, `cis`.`servicio_id`, p.estado_id' )
                    ->limit(5, $limite_inferior)
                    ->get('flash_piezas p');
                //echo $this->db->last_query();die;

                if ($query2->num_rows() > 0){
                    $json2 = $query2->result();
                     echo json_encode(array('grilla' => $json2,'grilla2' => $this->db->last_query()));
                }else{
                   echo json_encode(array('status' => 'end'));
                }


//            if($json) echo json_encode(array('grilla' => $json/*, 'resumen' => $json_resumen*/));
//            else echo json_encode(array('status' => 'none'));
	}

    public function recibio() {
        
        
        $codigo_str = $this->input->post('codigo');
        $codigo_int = (int)$this->input->post('codigo');
        $documento = $this->input->post('documento');
        $recibio = $this->input->post('recibio');
        $vinculo = $this->input->post('vinculo');
        $datos_varios_2 = $this->input->post('datos_varios_2');
        $codigo_tipo = "id";
        $pieza = $this->codegen_model->row('flash_piezas', '*', 'id = '.$codigo_int.' AND tipo_id = 2');
        if ($pieza == null) {
            $codigo_tipo = 'barcode_externo';
            $pieza = $this->codegen_model->row('flash_piezas', '*', 'barcode_externo like "'.$codigo_str.'" AND tipo_id = 2');
            if ($pieza == null) {
                echo json_encode(array('status' => 'La pieza no existe'));return;
            }
        }
        if ($pieza != null) {
        	$pieza_session['codigo']             = $this->input->post('codigo');
                $pieza_session['documento']             = $this->input->post('documento');
                $pieza_session['recibio']             = $this->input->post('recibio');
                $pieza_session['vinculo']             = $this->input->post('vinculo');
                $this->session->set_userdata('pieza_session', $pieza_session);

            $data = array(
                'documento' => $documento,
                'recibio' => $recibio,
                'vinculo' => $vinculo,
                'datos_varios_2' => $datos_varios_2,
                'update' => date('Y-m-d H:m:s')
            );
            $this->codegen_model->edit('flash_piezas', $data, 'id', $pieza->id);
            echo json_encode(array('status' => 'Guardado', 'pieza_session' => $pieza_session));
        }
    }
    
    public function getLinksImpresionRendiciones($rendicion_id)
    {
        $piezas = $this->flash_md->getALLPiezasRendicionAgrupadas($rendicion_id);
       // echo (count($piezas) / 500);
        $inicio = 0;
        $links = [];
        for ($i = 0; $i < count($piezas)/500 ; $i++) {
            array_push($links, $inicio);
            $inicio += 500;
        }
        if($piezas) echo json_encode(array('links' => $links));
            else echo json_encode(array('status' => 'No se econtraron piezas cargadas'));
        //var_dump(count($piezas));die;
    }
}

/* End of file Piezas.php */
/* Location: ./system/application/controllers/Piezas.php */