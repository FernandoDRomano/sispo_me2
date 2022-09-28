<?php

class Novedades_masivas extends CI_Controller{
    public function __construct(){
        parent::__construct();
    }
    
	function CURL($method,$url, $data = null, $Bearer = 'abcd') {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		$postjson = json_encode($data);
		if(!empty($data)) {
			switch ($method) {
				case "POST":
					curl_setopt($ch, CURLOPT_POST, true);
					if ($data){
						curl_setopt($ch, CURLOPT_POSTFIELDS, $postjson);
					}
					break;
				case "PUT":
					curl_setopt($ch, CURLOPT_PUT, 1);
					break;
				default:
					if ($data){
						$url = sprintf("%s?%s", $url, http_build_query($data));
					}
			}
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
			$authorization = "Authorization: Bearer abcd"; // Prepare the authorisation token
			curl_setopt($ch, CURLOPT_HTTPHEADER , array("cache-control: no-cache", $authorization));
		} else{
		}
		$result = curl_exec($ch);
		$DataResultado = curl_getinfo($ch);
		$ResultadoDecode = json_decode($result, true);
		if($ResultadoDecode){
			$Respuesta = array_merge($DataResultado,json_decode($result, true));
		}else{
			$result = json_encode(array('json-data' => false));
			$Respuesta = array_merge($DataResultado,json_decode($result, true));
		}
		if( $Respuesta["http_code"] == 200 ){
			curl_close($ch);
			return $Respuesta;
		}else{
			curl_close($ch);
			return $Respuesta;
		}
		
	}
	
	
    function grabar_novedad_masivas_pieza_normal(){
        date_default_timezone_set('America/Argentina/Tucuman');
        /*
        print_r(date('Y-m-d H:i:s'));
        return;
        */
        $PHPRespuestaString ="";
        $buscar_por = (int)$this->input->post('buscar_por');
        $estado_id = '"'.$this->input->post('estado_id').'"';
        $piezas = $this->input->post('piezas');
        $piezas = explode(",", $piezas);
        $piezas = array_unique($piezas);
        $piezas = array_map( 'addslashes', $piezas );
        $Arraypiezas = $piezas;
        if(count($Arraypiezas)==0){
            return;
        }
        $piezasComa = implode(",", $piezas);
        $piezasTemp =  '(\''. implode("'),('", $piezas).'\')';
        
            
        $piezas =  '"'. implode("\",\"", $piezas).'"';
        $duplicadospieza;
        $pieza_no_disponibles;
        $Arrayduplicados=[];
        $Arraypieza_no_disponibles=[];
        $user_row = $this->ion_auth->user()->row();
        
        
        $sucursal = $user_row->sucursal_id;
        
        $query = "
            select pec.estado_condicional_id as 'estado_condicional_id'
            from flash_piezas_estados_variables_condicionales as pec
            where (($estado_id = pec.estado_id))
        ";
        $pec = $this->db->query($query)->result();
        $Requerimientos= true;
        $pecestados = "";
        if(count($pec)>0){
            foreach($pec as $val){
                if($pecestados!=""){
                    $pecestados = $pecestados . ',"' . $val->estado_condicional_id . '"';
                }else{
                    $pecestados = '"' . $val->estado_condicional_id . '"';
                }
            }
        }else{
            $pecestados =  '"0"';
        }
        
        //INICIO piezas Duplicadas
        if (intval($buscar_por) == 2){
            $query_piezas = "
                select p.id as 'id', p.estado_id as 'estado_id', p.barcode_externo as 'barcode_externo', p.barcode_externo as 'identificador'
                from flash_piezas p
                inner join flash_piezas_estados_variables as pe on p.estado_id = pe.id
                where p.barcode_externo in (".$piezas.")
                AND p.sucursal_id = $sucursal
                group by p.id
                order by p.barcode_externo asc
            ";
            $pieza = $this->db->query($query_piezas)->result();
            $duplicados = $this->obj_duplicated_to_array_unique($pieza,"identificador");
            if(count($duplicados)>0){
                $piezasduplicados =  '"'. implode("\",\"", $duplicados).'"';
                $query_piezas = "
                    select p.id as 'id', '' as 'estado_id', p.barcode_externo as 'barcode_externo', p.barcode_externo as 'identificador', concat('Existen ',count(p.barcode_externo),' piezas con el mismo CODIGO de BARRAS') as 'Descripcion'
                    from flash_piezas p
                    inner join flash_piezas_estados_variables as pe on p.estado_id = pe.id
                    where p.barcode_externo in (".$piezasduplicados.")
                    AND p.sucursal_id = $sucursal
                    group by p.barcode_externo
                    order by p.barcode_externo asc
                ";
                $duplicadospieza = $this->db->query($query_piezas)->result();
                $Arrayduplicados = $this->obj_to_array($duplicadospieza,"identificador");
                $Arrayduplicados = array_map( 'addslashes', $Arrayduplicados );
                
                $array = array_diff($Arraypiezas,$Arrayduplicados);
                $piezas = $array;
                if(count($array)==0){
                    echo json_encode(array('status' => 'susses','data' => $duplicadospieza));
                    return;
                }
                //$piezas =  '"'. implode("\",\"", $piezas).'"';
                $piezasTemp =  '(\''. implode("'),('", $piezas).'\')';
            }
        }
        //FINAL piezas Duplicadas
        
        
        //INICIO piezas no disponibles
        if (intval($buscar_por) == 1){
            $query_piezas_no_disponibles_insert = "
                create temporary table tmp (id varchar(80))
            ";
            $pieza_no_disponibles_insert = $this->db->query($query_piezas_no_disponibles_insert);
            $query_piezas_no_disponibles_insert = "
                insert into tmp (id)
                values $piezasTemp
            ";
            $pieza_no_disponibles_insert = $this->db->query($query_piezas_no_disponibles_insert);
            //$piezas_no_disponibles_insert = $this->db->query($query_piezas_no_disponibles_insert);
            $query_piezas_no_disponibles = "
                select tmp.id as 'id',tmp.id as 'barcode_externo', tmp.id as 'identificador','La pieza no esta disponible' as 'Descripcion', '' as 'estado_id'
                from tmp
                left join flash_piezas p on p.id = tmp.id AND p.sucursal_id = $sucursal
                and 
                (
                    p.estado_id in (" . $pecestados . ")
                    or 
                    0 in (" . $pecestados . ")
                )
                left join flash_piezas_estados_variables as pe on p.estado_id = pe.id
                left join flash_piezas_estados_variables_condicionales as pec on (($estado_id = pec.estado_id))
                where 1
                and p.id is null
            ";
            $pieza_no_disponibles = $this->db->query($query_piezas_no_disponibles)->result();
            //$Arraypieza_no_disponibles = $this->obj_duplicated_to_array_unique($pieza_no_disponibles,"identificador");
            $Arraypieza_no_disponibles = $this->obj_to_array($pieza_no_disponibles,"identificador");
            $Arraypieza_no_disponibles = array_map( 'addslashes', $Arraypieza_no_disponibles );
            $query_piezas_no_disponibles_insert = "
                DROP TEMPORARY TABLE tmp;
            ";
            $pieza_no_disponibles_insert = $this->db->query($query_piezas_no_disponibles_insert);
            
            //echo json_encode(array('status' => 'susses','data' => $pieza_no_disponibles));
        }
        if (intval($buscar_por) == 2){
            $query_piezas_no_disponibles_insert = "
                create temporary table tmp (id varchar(80))
            ";
            $pieza_no_disponibles_insert = $this->db->query($query_piezas_no_disponibles_insert);
            $query_piezas_no_disponibles_insert = "
                insert into tmp (id)
                values $piezasTemp
            ";
            $pieza_no_disponibles_insert = $this->db->query($query_piezas_no_disponibles_insert);
            //$piezas_no_disponibles_insert = $this->db->query($query_piezas_no_disponibles_insert);
            $query_piezas_no_disponibles = "
                select tmp.id as 'id',tmp.id as 'barcode_externo', tmp.id as 'identificador','La pieza no esta disponible' as 'Descripcion', '' as 'estado_id'
                from tmp
                left join flash_piezas p on p.barcode_externo = tmp.id AND p.sucursal_id = $sucursal
                and 
                (
                    p.estado_id in (" . $pecestados . ")
                    or 
                    0 in (" . $pecestados . ")
                )
                left join flash_piezas_estados_variables as pe on p.estado_id = pe.id
                left join flash_piezas_estados_variables_condicionales as pec on (($estado_id = pec.estado_id))
                where 1
                and p.id is null
                group by tmp.id
            ";
            $pieza_no_disponibles = $this->db->query($query_piezas_no_disponibles)->result();
            
            //$Arraypieza_no_disponibles = $this->obj_duplicated_to_array_unique($pieza_no_disponibles,"identificador");
            $Arraypieza_no_disponibles = $this->obj_to_array($pieza_no_disponibles,"identificador");
            
            $Arraypieza_no_disponibles = array_map( 'addslashes', $Arraypieza_no_disponibles );
            $query_piezas_no_disponibles_insert = "
                DROP TEMPORARY TABLE tmp;
            ";
            $pieza_no_disponibles_insert = $this->db->query($query_piezas_no_disponibles_insert);
            //echo json_encode(array('status' => 'susses','data' => $pieza_no_disponibles_insert));
        }
        //FINAL piezas no disponibles
        
        //INICIO Formateo Piezas A Buscar Sacando Duplicados Y No Disponibles
        if (intval($buscar_por) == 1){
            $array = array_diff($Arraypiezas,$Arraypieza_no_disponibles);
            $piezas = $array;
            if(count($array)==0){
                echo json_encode(array('status' => 'susses','data' => $pieza_no_disponibles));
                return;
            }
            $piezas =  '"'. implode("\",\"", $piezas).'"';
        }
        if (intval($buscar_por) == 2){
            $array = array_diff($Arraypiezas,$Arrayduplicados);
            $array = array_diff($array,$Arraypieza_no_disponibles);
            $piezas = $array;
            if(count($array)==0){
                $obj_merged = (object) array_merge((array) $pieza_no_disponibles, (array) $duplicadospieza);
                echo json_encode(array('status' => 'susses','data' => $obj_merged));
                return;
            }
            $piezas =  '"'. implode("\",\"", $piezas).'"';
        }
        //FINAL Formateo Piezas A Buscar Sacando Duplicados Y No Disponibles
        
        
        
        
        
        
        
        
        
        
        
        //Inicio Actualizo Novedades
        if (intval($buscar_por) == 1){
            $query_piezas = "
                INSERT INTO flash_piezas_novedades (pieza_id, cantidad, usuario_id,  estado_actual_id, estado_nuevo_id,create_user_id,update_user_id)  
                SELECT p.id, 1, ".$user_row->id.", p.estado_id,".$estado_id. ", ".$user_row->id.", ".$user_row->id."
                from flash_piezas p
                inner join flash_piezas_estados_variables as pe on p.estado_id = pe.id
                left join flash_piezas_estados_variables_condicionales as pec on (($estado_id = pec.estado_id)) and pec.estado_condicional_id = p.estado_id
                where p.id in (".$piezas.")
                AND p.sucursal_id = $sucursal
                ON DUPLICATE KEY UPDATE estado_actual_id= p.estado_id , estado_nuevo_id=" . $estado_id . ",flash_piezas_novedades.update=" . "'" . date('Y-m-d H:i:s') . "', flash_piezas_novedades.update_user_id=" .$user_row->id."
            ";
            $pieza = $this->db->query($query_piezas);
        }
        if (intval($buscar_por) == 2){
            $query_piezas = "
                INSERT INTO flash_piezas_novedades (pieza_id, cantidad, usuario_id,  estado_actual_id, estado_nuevo_id,create_user_id,update_user_id)
                select  p.id, 1, ".$user_row->id.", p.estado_id,".$estado_id. ", ".$user_row->id.", ".$user_row->id."
                from flash_piezas p
                inner join flash_piezas_estados_variables as pe on p.estado_id = pe.id
                left join flash_piezas_estados_variables_condicionales as pec on (($estado_id = pec.estado_id)) and pec.estado_condicional_id = p.estado_id
                where p.barcode_externo in (".$piezas.") and p.barcode_externo not like ''
                AND p.sucursal_id = $sucursal
                ON DUPLICATE KEY UPDATE estado_actual_id= p.estado_id , estado_nuevo_id=" . $estado_id . ",flash_piezas_novedades.update=" . "'" . date('Y-m-d H:i:s') . "', flash_piezas_novedades.update_user_id=" .$user_row->id."
            ";
            $pieza = $this->db->query($query_piezas);
        }
        //FINAL Actualizo Novedades
        
        //Inicio Actualizo Pieza
        if (intval($buscar_por) == 1){
            $query_update_piezas = "
                UPDATE flash_piezas
                inner JOIN(
                    select * 
                    from flash_piezas as p 
                    WHERE p.id in (".$piezas.")
                ) as p
                inner JOIN flash_comprobantes_ingresos_servicios as  cis ON cis.id = p.servicio_id
                inner JOIN flash_piezas_estados_variables as  ev ON p.estado_id = ev.id 
                inner join flash_piezas_novedades as n ON p.id = n.pieza_id
                SET
                flash_piezas.estado_id = n.estado_nuevo_id
                , flash_piezas.update_user_id = ".$user_row->id."
                , flash_piezas.update = '" . date('Y-m-d H:i:s') . "'
                
                WHERE p.id = flash_piezas.id
            ";
            $query = $this->db->query($query_update_piezas);
        }
        if (intval($buscar_por) == 2){
            $query_update_piezas = "
                UPDATE flash_piezas
                inner JOIN(
                    select * 
                    from flash_piezas as p 
                    WHERE p.barcode_externo in (".$piezas.") and p.barcode_externo not like ''
                ) as p
                inner JOIN flash_comprobantes_ingresos_servicios as  cis ON cis.id = p.servicio_id
                inner JOIN flash_piezas_estados_variables as  ev ON p.estado_id = ev.id 
                inner join flash_piezas_novedades as n ON p.id = n.pieza_id
                SET
                flash_piezas.estado_id = n.estado_nuevo_id
                , flash_piezas.update_user_id = ".$user_row->id."
                , flash_piezas.update = '" . date('Y-m-d H:i:s') . "'
                WHERE p.id = flash_piezas.id
            ";
            $query = $this->db->query($query_update_piezas);
        }
        //FINAL Actualizo Pieza
        
        
        //Inicio Actualizo Tracking
        if (intval($buscar_por) == 1){
            $query_update_piezas = "
                INSERT INTO flash_piezas_tracking 
                (
                    pieza_id
                    , usuario_id
                    , estado_id
                    , create_user_id
                    , update_user_id
                    , flash_piezas_tracking.update
                )
                SELECT 
                    p.id
                    ,".$user_row->id."
                    ,".$estado_id. "
                    , p.create_user_id
                    , ".$user_row->id."
                    , '" . date('Y-m-d H:i:s') . "'
                from flash_piezas p
                inner join flash_piezas_estados_variables as pe on p.estado_id = pe.id
                where p.id in (".$piezas.")
                AND p.sucursal_id = $sucursal
            ";
            $query = $this->db->query($query_update_piezas);
        }
        if (intval($buscar_por) == 2){
            $query_update_piezas = "
                INSERT INTO flash_piezas_tracking 
                (
                    pieza_id
                    , usuario_id
                    , estado_id
                    , create_user_id
                    , update_user_id
                    , flash_piezas_tracking.update
                )
                SELECT 
                    p.id
                    ,".$user_row->id."
                    ,".$estado_id. "
                    , p.create_user_id
                    , ".$user_row->id."
                    , '" . date('Y-m-d H:i:s') . "'
                from flash_piezas p
                inner join flash_piezas_estados_variables as pe on p.estado_id = pe.id
                where p.barcode_externo in (".$piezas.") and p.barcode_externo not like ''
                AND p.sucursal_id = $sucursal
            ";
            $query = $this->db->query($query_update_piezas);
        }
        //FINAL Actualizo Tracking
        
        
        
        
        //Inicio Actualizo Tracking
        if (intval($buscar_por) == 1){
            //
            $query_update_piezas = "
                UPDATE flash_subpiezas as sp
                inner join (
                    SELECT sp.*, p.estado_id as 'estado_id'
                    from flash_piezas p
                	inner join flash_subpiezas as sp on sp.pieza_id = p.id
                    inner join
                    (
                        select sp.pieza_id, max(sp.create)
                        from flash_piezas p
                        inner join flash_subpiezas as sp on sp.pieza_id = p.id
                        where p.id in (".$piezas.")
                        AND p.sucursal_id = 4
                        group by sp.pieza_id
                    )as usp on sp.pieza_id = usp.pieza_id
                )as usp on sp.pieza_id = usp.pieza_id
                SET sp.pieza_estado_id = usp.estado_id
                , sp.update =  '" . date('Y-m-d H:i:s') . "'
                WHERE sp.id = usp.id
            ";
            $query = $this->db->query($query_update_piezas);
        }
        if (intval($buscar_por) == 2){
            $query_update_piezas = "
                UPDATE flash_subpiezas as sp
                inner join (
                    SELECT sp.*, p.estado_id as 'estado_id'
                    from flash_piezas p
                	inner join flash_subpiezas as sp on sp.pieza_id = p.id
                    inner join
                    (
                        select sp.pieza_id, max(sp.create)
                        from flash_piezas p
                        inner join flash_subpiezas as sp on sp.pieza_id = p.id
                        where p.barcode_externo in (".$piezas.") and p.barcode_externo not like ''
                        AND p.sucursal_id = 4
                        group by sp.pieza_id
                    )as usp on sp.pieza_id = usp.pieza_id
                )as usp on sp.pieza_id = usp.pieza_id
                SET sp.pieza_estado_id = usp.estado_id
                , sp.update =  '" . date('Y-m-d H:i:s') . "'
                WHERE sp.id = usp.id
            ";
            $query = $this->db->query($query_update_piezas);
        }
        //FINAL Actualizo Tracking
        
        
        
        
        
        
        
        
        
        
        
        /*date('Y-m-d H:i:s')
        print_r(date('Y-m-d H:i:s'));
        return;
        */
        
        
        
        
        //INICIO Obtencion De Informacion De piezas 
        if (intval($buscar_por) == 1){
            $query_piezas = "
                select p.id as 'id', pe.nombre as 'estado_id', p.barcode_externo as 'barcode_externo', p.id as 'identificador', 'Pieza Actualizada' as 'Descripcion', ci.cliente_id as 'cliente_id'
                from flash_piezas p
                inner join flash_piezas_estados_variables as pe on p.estado_id = pe.id
                inner join flash_comprobantes_ingresos as ci on ci.id = p.comprobante_ingreso_id
                where p.id in (".$piezas.")
                AND p.sucursal_id = $sucursal
            ";
            $pieza = $this->db->query($query_piezas)->result();
        }
        if (intval($buscar_por) == 2){
            $query_piezas = "
                select p.id as 'id', pe.nombre as 'estado_id', p.barcode_externo as 'barcode_externo', p.barcode_externo as 'identificador', 'Pieza Actualizada' as 'Descripcion', ci.cliente_id as 'cliente_id'
                from flash_piezas p
                inner join flash_piezas_estados_variables as pe on p.estado_id = pe.id
                inner join flash_comprobantes_ingresos as ci on ci.id = p.comprobante_ingreso_id
                where p.barcode_externo in (".$piezas.")
                AND p.sucursal_id = $sucursal
            ";
            $pieza = $this->db->query($query_piezas)->result();
        }
        //FINAL Obtencion De Informacion De piezas 
        
        
        
        
        //INICIO Auditoria 
        $descripcion = 'Piezas: ' . $piezasComa . ' . Grabar Novedades a Piezas masivas';
        $sql_insert   = "
            INSERT INTO users_log
            (user_id, categoria, descripcion, origen, destino, create_user_id, update_user_id)
            VALUES ('$user_row->id','NOVEDADES MASIVAS', '$descripcion', '', '', '$user_row->id', '$user_row->id');
        ";
        $query_insert = $this->db->query($sql_insert);
        //FINAL Auditoria
        $PHPRespuesta;
        if(count($pieza)> 0){
            for($i=0;$i<count($pieza);$i++){
                if($pieza[$i]->cliente_id == 2288){
                    $Data = array('codigo' => $pieza[$i]->id,'estado_id' => $estado_id,'buscar_por' => 1,'UserId' => $user_row->id,'Plataforma' => '/home/sispoc5/public_html/application/controllers/ajax/Novedades_masivas.php');
            		$PHPRespuesta = $this->CURL("POST", "http://igalfer.sppflash.com.ar/api/Ripcord", $Data);
                }
                if ($pieza[$i]->cliente_id == 627 or $pieza[$i]->cliente_id == 1927){
            		if($estado_id>1){
                        $Data = array('codigo_barra' => $pieza[$i]->id,'estado_id' => $estado_id,'buscar_por' => 1,'UserId' => $user_row->id);// Modo Test Sispo , 'Sispo' => 'Sispo'
                		$PHPRespuesta = $this->CURL("POST", "http://sistema.sppflash.com.ar" ."/api/flame/ApisExternas/AjaxAdministrarEstadosDeSispo.php", $Data);//laravelapis.sppflash.com.ar
            		}
                }
            }
        }
        //print_r($query_update_piezas);
        $obj_merged = (object) array_merge((array) $pieza_no_disponibles, (array) $duplicadospieza);
        $obj_merged = (object) array_merge((array) $obj_merged, (array) $pieza);
        echo json_encode(array('status' => 'susses','data' => $obj_merged));
        return;
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        /*
        
        if (intval($buscar_por) == 2){
            $pieza = $this->codegen_model->get(
                'flash_piezas',
                'id',
                'barcode_externo in ('.$piezas.') AND sucursal_id = '.$user_row->sucursal_id
                );
        }
            $PHPRespuestaString ="";
            $codigo = (int)$this->input->post('codigo_barra');
            $codigo_str = '"'.$this->input->post('codigo_barra').'"';
            $CodigoReal = $this->input->post('codigo_barra');
            
            $estado_id = $this->input->post('estado_id');
            $buscar_por = $this->input->post('buscar_por');
            $user_row = $this->ion_auth->user()->row();
            //Traigo los datos de la pieza dependiendo si se busca por ID o CODIGO EXTERNO
            
            if (intval($buscar_por) == 1) $pieza = $this->codegen_model->get('flash_piezas','id, estado_id','id = '.$codigo.' AND sucursal_id = '.$user_row->sucursal_id);
            if (intval($buscar_por) == 2) $pieza = $this->codegen_model->get('flash_piezas','id','barcode_externo = '.$codigo_str.' AND sucursal_id = '.$user_row->sucursal_id);
            
            //Si la consulta trae mas de un ID de pieza solicita que ingrese el ID de la pieza
            if (count($pieza) > 1){
                echo json_encode(array('status' => 'Existen '.count($pieza)." piezas con el mismo CODIGO de BARRAS"));
                return;
            };
            // echo $this->db->last_query();
            if ($pieza == NULL){
                echo json_encode(array('status' => 'La pieza no esta disponible'));
                return;
            };

            if (count($pieza) == 1){
                $pieza = $pieza[0];
            };
            
            // load URL helper
            $this->load->helper('novedades');
            $respuesta = validar_novedad($pieza->estado_id,$estado_id);

            if ($respuesta == ""){
                
                 // Auditoria grabar novedades
                 $user_row = $this->ion_auth->user()->row();
                 $data = array(
                     'user_id' => $user_row->id,
                     'categoria' => 'NOVEDADES NORMAL',
                     'descripcion' => 'Pieza: ' . $pieza->id . '. Grabar Novedades a Piezas Normales RESPUESTA.' . $respuesta,
                     'origen' => '',
                     'destino' => '',
                 );
                 $this->codegen_model->add('users_log',$data);
                 // END: Auditoria
            
            $actualizada = false;
            // Si es que la pieza esta en una HDR busco la ultima HDR en la que está. Para actulizarle el estado
            $sql_get_ultima_hdr = " SELECT sp.id sp_id, sp.create, sp.hoja_ruta_id, sp.pieza_id FROM flash_subpiezas sp
                                    
                                    WHERE sp.pieza_id = $pieza->id
                                     ORDER BY sp.create DESC 
                                     LIMIT 1";
            //echo $sql_get_ultima_hdr;die;
            $ultima_hdr = $this->db->query($sql_get_ultima_hdr)->row();
            
            // Auditoria grabar novedades
            $user_row = $this->ion_auth->user()->row();
            $data = array(
                'user_id' => $user_row->id,
                'categoria' => 'NOVEDADES NORMAL',
                'descripcion' => 'Pieza: ' . $pieza->id . '. esta en HDR.' . $ultima_hdr->sp_id,
                'origen' => '',
                'destino' => '',
            );
            $this->codegen_model->add('users_log',$data);
            // END: Auditoria 
            
            //Una vez encontrada, actualizo el pieza_estado_id de la pieza dentro de la HDR para que luego se pueda
            //dar de baja a la HDR
            if ($ultima_hdr->sp_id != '' && $ultima_hdr->sp_id != NULL){
                $sql_update_novedad_subpiezas = " UPDATE flash_subpiezas sp
                                                    SET sp.pieza_estado_id = $estado_id
                                                     WHERE sp.id = $ultima_hdr->sp_id ";
                $this->db->query($sql_update_novedad_subpiezas);
                $actualizada = true;
            }

             // Auditoria grabar novedades
               $user_row = $this->ion_auth->user()->row();
               $data = array(
                   'user_id' => $user_row->id,
                   'categoria' => 'NOVEDADES NORMAL actualiza flash_subpiezas',
                   'descripcion' => 'Pieza: ' . $pieza->id . '. actualizada.' . $actualizada,
                   'origen' => '',
                   'destino' => '',
               );
               $this->codegen_model->add('users_log',$data);
               // END: Auditoria 
               
               
            $query = $this->db
                        ->select('p.id pieza_id, p.domicilio, p.localidad, p.codigo_postal, ev.nombre estado')
                        ->join('flash_piezas_estados_variables ev', ' p.estado_id = ev.id')
                        ->where('ev.pieza_estado_id <> '.PiezaEstado::ESTADOS_RENDICIONES)
                        ->where('p.id = '.$pieza->id)
                        ->where('p.sucursal_id = '.$user_row->sucursal_id)
                        ->get('flash_piezas p');
            //          echo $this->db->last_query();die;
            
            $json = $query->row();
            
            
            
            
//            echo $this->db->last_query();die;
            if($json){ 
                
                //Inserto o actualizo la novedad de la pieza
                $sql_insert   = "INSERT INTO flash_piezas_novedades (pieza_id, cantidad, usuario_id,  estado_actual_id, estado_nuevo_id)  
                                SELECT p.id, 1, ".$user_row->id.", p.estado_id,".$estado_id. "
                                FROM  flash_piezas p
                                    JOIN flash_comprobantes_ingresos_servicios cis ON cis.id = p.servicio_id
                                    JOIN flash_piezas_estados_variables ev ON p.estado_id = ev.id
                                     WHERE p.sucursal_id = ".$user_row->sucursal_id."
                                     AND p.id = ".$pieza->id."
                                         ON DUPLICATE KEY UPDATE estado_actual_id= p.estado_id , estado_nuevo_id=" . $estado_id . ",flash_piezas_novedades.update=" . "'" . date('Y-m-d H:i:s') . "'";
            
                $query_insert = $this->db->query($sql_insert);
                //actualizo el estado de la pieza en la tabla Piezas
                $query_update_piezas = "UPDATE flash_piezas p
                                        JOIN flash_comprobantes_ingresos_servicios cis ON cis.id = p.servicio_id
                                        JOIN flash_piezas_estados_variables ev ON p.estado_id = ev.id
                                        JOIN flash_piezas_novedades n ON p.id  = n.pieza_id
                                        SET p.estado_id = n.estado_nuevo_id
                                         WHERE p.id = ".$pieza->id;
                $query = $this->db->query($query_update_piezas);
                
                //Inserto novedad en tabla flash_piezas_tracking
                $sql_insert_tracking   = "INSERT INTO flash_piezas_tracking (pieza_id, usuario_id,  estado_id)  
                                 VALUES ($pieza->id, $user_row->id, $estado_id )";
                $json_insert_tracking = $this->db->query($sql_insert_tracking);
                //$this->escritura($pieza->id,$estado_id,'/home/sispoc5/public_html/application/controllers/ajax/Novedades.php');
                
                
                
                $sql_get_cliente = "
                                    SELECT ci.cliente_id as 'cliente'
                                    FROM flash_piezas as fp
                                    inner join flash_comprobantes_ingresos as ci on fp.comprobante_ingreso_id = ci.id
                                    where fp.id = $pieza->id
                                    LIMIT 1
                                ";
                //echo $sql_get_ultima_hdr;die;
                $clienteid = $this->db->query($sql_get_cliente)->row()->cliente;
                
                //Una vez encontrada, actualizo el pieza_estado_id de la pieza dentro de la HDR para que luego se pueda
                //dar de baja a la HDR
                if ($clienteid == 2288){
                    // ENVIO DE DATOS EN JSON A LA API DE igalfer  comienza aqui 
                    $Data = array('codigo' => $CodigoReal,'estado_id' => $estado_id,'buscar_por' => $buscar_por,'UserId' => $user_row->id,'Plataforma' => '/home/sispoc5/public_html/application/controllers/ajax/Novedades.php');
                    
                    
                    // Modo Test Sispo , 'Sispo' => 'Sispo'
            		$PHPRespuesta = $this->CURL("POST", "http://igalfer.sppflash.com.ar/api/Ripcord", $Data);
            		if($PHPRespuesta["http_code"] == 200){
            			$PHPRespuestaString = print_r($PHPRespuesta['Respuesta']['Datos'][1]['Extra'],true);
            			//$PHPRespuestaString = print_r($PHPRespuesta,true);
            		}else{
            			$PHPRespuestaString ="Error En Envio:" . print_r($PHPRespuesta,true);
            		}
                }
                if ($clienteid == 627 or $clienteid == 1927){
            		if($estado_id>1){
                        $Data = array('codigo_barra' => $CodigoReal,'estado_id' => $estado_id,'buscar_por' => $buscar_por,'UserId' => $user_row->id);// Modo Test Sispo , 'Sispo' => 'Sispo'
                		//print_r($Data);
                		$PHPRespuesta = $this->CURL("POST", "http://sistema.sppflash.com.ar" ."/api/flame/ApisExternas/AjaxAdministrarEstadosDeSispo.php", $Data);//laravelapis.sppflash.com.ar
                        //print_r($PHPRespuesta);
            		}
                }
                
                
                
                
                
                
                
                // Auditoria
                $user_row = $this->ion_auth->user()->row();
                $data = array(
                    'user_id' => $user_row->id,
                    'categoria' => 'NOVEDADES',
                    'descripcion' => 'Pieza: ' . $pieza->id . '. Grabar Novedades a Piezas Normales.' . $PHPRespuestaString,
                    'origen' => '',
                    'destino' => '',
                );
                $this->codegen_model->add('users_log',$data);
                // END: Auditoria 
                
                
                echo json_encode($json);
            }else{
                if ($actualizada) {
                    
                }else{
                    echo json_encode(array('status' => 'La pieza no esta disponible'));
                }
            }
            
            // ENVIO DE DATOS EN JSON A LA API DE DAFITI  hasta aca
            
            
                }else{
                    echo json_encode(array('respuesta' => $respuesta));
                }
            
        */
    }
    function obj_duplicated_to_array_unique($array, $mykey){
        $newlist = array();
        $i=0;
        foreach($array as $key => $val){
            if (is_object($val))
            $val = (array)$val;
            $newlist[$i]= $val[$mykey];
            $i++;
        }
        $unicos = array_unique(array_map("strtoupper", $newlist));
        $duplicados = array_diff_assoc($newlist, $unicos);
        
        $duplicados = array_unique(array_map("strtoupper", $duplicados));
        return $duplicados;
    }
    function obj_to_array($array, $mykey){
        $newlist = array();
        $i=0;
        foreach($array as $key => $val){
            if (is_object($val))
            $val = (array)$val;
            $newlist[$i]= $val[$mykey];
            $i++;
        }
        return $newlist;
    }
    
}

// End of file Piezas.php 
// Location: ./system/application/controllers/Piezas.php
