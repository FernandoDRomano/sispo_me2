<?php
class Administrador extends CI_Controller {
	function __construct() {
		parent::__construct();
	}
	public function Responder($json = null){
	    if($json) {
		    $resp = json_encode($json);
		    $resp = new stdClass();
            $resp->Respuesta = new stdClass();
            $resp->Respuesta->Datos = $json;
		    echo json_encode($resp);
		}else{
            echo ('{"Respuesta":{"Datos":[]}}');  
		}
	}
	
	public function AjaxControlingresos(){
		//var_dump($_POST);
		$FechaInicial = str_replace('/', '-', $this->input->post('FechaInicial')).' 00:00:00';
		$FechaFinal = str_replace('/', '-', $this->input->post('FechaFinal')).' 23:59:59';
	    //print_r($FechaInicial);
		//return;
		$query = $this->db->query("
			SELECT count(fp.id) as 'Total'
			FROM flash_piezas as fp
			left join flash_comprobantes_ingresos_servicios as fcis on fcis.id = fp.servicio_id
			left join flash_comprobantes_ingresos as fci on fci.id = fcis.comprobante_ingreso_id
			left join flash_sucursales as fsuc on fsuc.id = fci.sucursal_id
			WHERE fp.create > '$FechaInicial' and fp.create < '$FechaFinal'
		");
		$result = $query->result();
		
		if(count($result)>0){
			$total = $result[0]->Total;
			if(!$total>0){
			    $this->Responder();
			    //echo ('{"Respuesta":{"Datos":[1]}}');
				return;
			}
		}else{
		    $this->Responder();
			echo ('{"Respuesta":{"Datos":[]}}');
			return;
		}
		
		
		
		$result = 
		$query = $this->db->query("
			SELECT fsuc.nombre as 'Sucursal',  count(fp.id) as 'Piezas Ingresadas', concat(count(fp.id)*100/($total),'%') as 'Total'
			FROM flash_piezas as fp
			left join flash_comprobantes_ingresos_servicios as fcis on fcis.id = fp.servicio_id
			left join flash_comprobantes_ingresos as fci on fci.id = fcis.comprobante_ingreso_id
			left join flash_sucursales as fsuc on fsuc.id = fci.sucursal_id
			WHERE fp.create > '$FechaInicial' and fp.create < '$FechaFinal'
			group by fsuc.nombre
			union
			SELECT 'Todas' as 'Sucursal',  $total as 'Piezas Ingresadas', '100%' as 'Total'
			
		");
		$json = $query->result();
		$this->Responder($json);
	}
	
	
	
	public function AjaxControlingresosgrafica(){
		$FechaInicial = str_replace('/', '-', $this->input->post('FechaInicial')).' 00:00:00';
		$FechaFinal = str_replace('/', '-', $this->input->post('FechaFinal')).' 23:59:59';
	    
		$query = $this->db->query("
			SELECT fsuc.nombre as 'Sucursal',  count(fp.id) as 'Piezas Ingresadas'
			FROM flash_piezas as fp
			left join flash_comprobantes_ingresos_servicios as fcis on fcis.id = fp.servicio_id
			left join flash_comprobantes_ingresos as fci on fci.id = fcis.comprobante_ingreso_id
			left join flash_sucursales as fsuc on fsuc.id = fci.sucursal_id
			WHERE fp.create > '$FechaInicial' and fp.create < '$FechaFinal'
			group by fsuc.nombre
		");
		$json = $query->result();
		$this->Responder($json);
	}
	
	public function AjaxSucursales(){
		$query = $this->db->query("
			SELECT s.id as 'id', s.nombre as 'nombre'
            FROM flash_sucursales as s
            order by s.id asc
		");
		$json = $query->result();
		$this->Responder($json);
	}
	
	public function AjaxClientes(){
		//$Sucursales = $this->input->post('$Sucursales');
		$query = $this->db->query("
			SELECT fc.id as 'id', fc.nombre as 'nombre'
            FROM flash_clientes as fc
            where fc.cliente_estado_id = 1
            ORDER BY fc.nombre ASC
		");
		$json = $query->result();
		$this->Responder($json);
	}
	
	public function AjaxIngresosPorClientesListado(){
	    //RECOGER LOS CAMPOS DEL REQUEST
		$FechaInicial = str_replace('/', '-', $this->input->post('FechaInicial')).' 00:00:00';
		$FechaFinal = str_replace('/', '-', $this->input->post('FechaFinal')).' 23:59:59';
		$Sucursal = $this->input->post('Sucursales');
		
		$query = $this->db->query("
		    SELECT 
                  cl.nombre AS 'CLIENTE',
                  
                  SUM(IF(p.tipo_id = 2, 1, 0)) AS 'TOTAL ACUSE',
                  SUM(IF(
                      (ci.cliente_id != 30 AND pev.pieza_estado_id = 2 AND pev.id = 13 AND p.tipo_id = 2)
                          OR
                      (ci.cliente_id = 30 AND pev.pieza_estado_id = 2 AND p.estado_id IN (31, 13) AND p.tipo_id = 2)
                      , 1, 0)) AS 'ENTREGADAS ACUSE',
                  SUM(IF(
                      (ci.cliente_id != 30 AND pev.pieza_estado_id = 2 AND pev.id != 13 AND p.tipo_id = 2)
                          OR
                      ( ci.cliente_id = 30 AND pev.pieza_estado_id = 2 AND p.estado_id NOT IN (31, 13) AND p.tipo_id = 2)
                      , 1, 0)) AS 'FALLIDAS ACUSE',
                  SUM(IF(pev.pieza_estado_id != 2 AND p.tipo_id = 2, 1, 0)) AS 'PENDIENTE ACUSE',
                            
                  SUM(IF(p.tipo_id = 1, 1, 0)) AS 'TOTAL SIMPLE',
                  SUM(IF(pev.pieza_estado_id = 2 AND pev.id = 13 AND p.tipo_id = 1, 1, 0)) AS 'ENTREGADAS SIMPLE',
                  SUM(IF(
                      ci.cliente_id != 30 AND pev.pieza_estado_id = 2 AND pev.id != 13 AND p.tipo_id = 1
                         , 1, 0)) AS 'FALLIDAS SIMPLE',
                  SUM(IF(pev.pieza_estado_id != 2 AND p.tipo_id = 1, 1, 0)) AS 'PENDIENTE SIMPLE',
                  
                  IFNULL( COUNT(p.id), 0) AS 'TOTAL'
                            
            FROM flash_piezas AS p 
            LEFT JOIN flash_comprobantes_ingresos_servicios AS cis ON cis.id = p.servicio_id 
            LEFT JOIN flash_comprobantes_ingresos AS ci ON cis.comprobante_ingreso_id = ci.id 
            LEFT JOIN flash_sucursales AS suc ON suc.id = ci.sucursal_id
            LEFT JOIN flash_clientes AS cl ON cl.id = ci.cliente_id 
            LEFT JOIN flash_piezas_estados_variables AS pev ON pev.id = p.estado_id
            WHERE ( p.create BETWEEN '$FechaInicial' AND '$FechaFinal' ) AND (suc.id = '$Sucursal' OR 0 = '$Sucursal')
            GROUP BY cl.id
            ORDER BY CLIENTE, TOTAL DESC
		");

		//FORMATEAR Y DEVOLVER LOS DATOS
		$json = $query->result();
		$this->Responder($json);
	    
	}
	
	public function AjaxIngresosCarterosPorSucursalListado(){
	    //RECOGER LOS CAMPOS DEL REQUEST
		$FechaInicial = str_replace('/', '-', $this->input->post('FechaInicial')).' 00:00:00';
		$FechaFinal = str_replace('/', '-', $this->input->post('FechaFinal')).' 23:59:59';
		$Sucursal = $this->input->post('Sucursales');
        
        $query = $this->db->query("
            SELECT 
            	CAR.apellido_nombre as 'NOMBRE', 
                
                SUM(IF(P.tipo_id = 2, 1, 0)) AS 'TOTAL ACUSE',
                SUM(IF(PEV.pieza_estado_id = 2 AND PEV.id = 13 AND P.tipo_id = 2, 1, 0)) AS 'ENTREGADAS ACUSE',
                SUM(IF(PEV.pieza_estado_id = 2 AND PEV.id != 13 AND P.tipo_id = 2, 1, 0)) AS 'FALLIDAS ACUSE',
                SUM(IF(PEV.pieza_estado_id = 3 AND P.tipo_id = 2, 1, 0)) AS 'ORGANIZATIVO ACUSE',
                SUM(IF(PEV.pieza_estado_id = 4 AND PEV.id = 2 AND P.tipo_id = 2, 1, 0)) AS 'DISTRIBUCIÓN ACUSE',
                
                SUM(IF(P.tipo_id = 1, 1, 0)) AS 'TOTAL SIMPLE',
                SUM(IF(PEV.pieza_estado_id = 2 AND PEV.id = 13 AND P.tipo_id = 1, 1, 0)) AS 'ENTREGADAS SIMPLE',
                SUM(IF(PEV.pieza_estado_id = 2 AND PEV.id != 13 AND P.tipo_id = 1, 1, 0)) AS 'FALLIDAS SIMPLE',
                SUM(IF(PEV.pieza_estado_id = 3 AND P.tipo_id = 1, 1, 0)) AS 'ORGANIZATIVO SIMPLE',
                SUM(IF(PEV.pieza_estado_id = 4 AND PEV.id = 2 AND P.tipo_id = 1, 1, 0)) AS 'DISTRIBUCIÓN SIMPLE',
            	
                COUNT(*) AS 'TOTAL' 
                
            FROM flash_sucursales_carteros AS CAR
            LEFT JOIN flash_hojas_rutas AS HDR ON HDR.cartero_id = CAR.id
            LEFT JOIN flash_subpiezas AS SUBP ON SUBP.hoja_ruta_id = HDR.id
            LEFT JOIN flash_piezas_estados_variables AS PEV ON PEV.id = SUBP.pieza_estado_id
            LEFT JOIN flash_sucursales AS SUC ON SUC.id = CAR.sucursal_id
            LEFT JOIN flash_piezas AS P ON P.id = SUBP.pieza_id
            
            WHERE (HDR.create BETWEEN '$FechaInicial' AND '$FechaFinal' ) AND (SUC.id = '$Sucursal' OR 0 = '$Sucursal')
            
            GROUP BY NOMBRE
            ORDER BY NOMBRE

        ");
        
        
		//FORMATEAR Y DEVOLVER LOS DATOS
		$json = $query->result();
		$this->Responder($json);
	}
	
	public function AjaxIngresosDeSucursalPorClientes(){
	    
	    
		$Sucursal = $this->input->post('Sucursal');
		
		//$Hoy = date('Y-m-d h:i:s', mktime(date('h'), date('i'), date('s'), date('m') , date('d') , date('Y')));
		$Hoy = date('Y-m-d 23:59:59', mktime(date('h'), date('i'), date('s'), date('m') , date('d') , date('Y')));
		
		$InicioEsteMes = date('Y-m-01');
		$UnMesAnterior = date("Y-m-d", mktime(0, 0, 0, date('m') - 1, '01', date('Y')));
		$DosMesAnterior = date("Y-m-d", mktime(0, 0, 0, date('m') - 2, '01', date('Y')));
		$TresMesAnterior = date("Y-m-d", mktime(0, 0, 0, date('m') - 3, '01', date('Y')));
		set_time_limit(0);
		
		$query = $this->db->query("
			SET lc_time_names = 'es_ES';
		");
		//$json = $query->result();
		
		$query = $this->db->query("
			SELECT DATE_FORMAT('$TresMesAnterior','%M') as 'A',DATE_FORMAT('$DosMesAnterior','%M') as 'B',DATE_FORMAT('$UnMesAnterior','%M') as 'C',DATE_FORMAT('$InicioEsteMes','%M') as 'D'
		");
		$json = $query->result();
		
		$columnas;
		$columnas[] = $json[0]->A;
		$columnas[] = $json[0]->B;
		$columnas[] = $json[0]->C;
		$columnas[] = $json[0]->D;
		
		$query = $this->db->query("
			SELECT fcli.nombre as 'cliente'
			#, count(fp.id) as 'Piezas'
			, sum(if(DATE_FORMAT(fp.create, '%M') = '$columnas[0]',1,0)) as '$columnas[0]'
			, sum(if(DATE_FORMAT(fp.create, '%M') = '$columnas[1]',1,0)) as '$columnas[1]'
			, sum(if(DATE_FORMAT(fp.create, '%M') = '$columnas[2]',1,0)) as '$columnas[2]'
			, sum(if(DATE_FORMAT(fp.create, '%M') = '$columnas[3]',1,0)) as '$columnas[3]'
			, sum(1) as 'Total'
			FROM flash_piezas as fp
			left join flash_comprobantes_ingresos as fci on fci.id = fp.comprobante_ingreso_id
			left join flash_clientes as fcli on fcli.id = fci.cliente_id
			WHERE fp.create > '$TresMesAnterior' and fp.create < '$Hoy'
			and 
			(
			    fci.sucursal_id = '$Sucursal'
			    or 
			    '0' = '$Sucursal'
			)
			group by fcli.nombre
			
			union
			
			SELECT 'todos' as 'cliente'
			, sum(if(DATE_FORMAT(fp.create, '%M') = '$columnas[0]',1,0)) as '$columnas[0]'
			, sum(if(DATE_FORMAT(fp.create, '%M') = '$columnas[1]',1,0)) as '$columnas[1]'
			, sum(if(DATE_FORMAT(fp.create, '%M') = '$columnas[2]',1,0)) as '$columnas[2]'
			, sum(if(DATE_FORMAT(fp.create, '%M') = '$columnas[3]',1,0)) as '$columnas[3]'
			, sum(1) as 'Total'
			FROM flash_piezas as fp
			left join flash_comprobantes_ingresos as fci on fci.id = fp.comprobante_ingreso_id
			left join flash_clientes as fcli on fcli.id = fci.cliente_id
			WHERE fp.create > '$TresMesAnterior' and fp.create < '$Hoy'
			and 
			(
			    fci.sucursal_id = '$Sucursal'
			    or 
			    '0' = '$Sucursal'
			)
		");
		
		$json = $query->result();
		
		
		
		
		$this->Responder($json);
	}
	
}


/* End of file Piezas.php */
/* Location: ./system/application/controllers/Piezas.php */