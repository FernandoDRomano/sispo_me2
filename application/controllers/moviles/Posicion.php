<?php

require_once("./application/views/template/rubenbackend/Elementos.php");

class Posicion extends CI_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->permisos = $this->permisos_lib->control();
        $this->load->model('Posicion_md');
        $this->load->helper('url');
    }

    public function index(){
        $vista_interna = array(
                'permisos_efectivos' => $this->permisos,
                'carteros' => $this->Posicion_md->carteros_listado(),
                'tipos_carteros' => $this->Posicion_md->tipos_carteros(),
                'provincias' => $this->Posicion_md->provincia_carteros(),
                'clientes' => $this->Posicion_md->clientes(),
                'paises' => $this->Posicion_md->paises()
        );

        $vista_externa = array(			
                'title' => ucwords("Mapa Online"),
                'contenido_main' => $this->load->view('components/moviles/posicion/posicion', $vista_interna, true)
        );		

        $this->load->view('template/rubenbackend', $vista_externa);
    }

    public function getSucursales(){
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $request = json_decode($stream_clean);

        $sucursales = $this->Posicion_md->get_sucursales($request->pais);
        echo json_encode(["data" => $sucursales]);
    }

    public function getClientes(){
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $request = json_decode($stream_clean);

        $clientes = $this->Posicion_md->get_clientes($request->pais);
        echo json_encode(["data" => $clientes]);
    }

    public function getCarteros(){
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $request = json_decode($stream_clean);

        $carteros = $this->Posicion_md->get_carteros($request->pais);
        echo json_encode(["data" => $carteros]);
    }

    public function getCarterosPorCliente(){
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $request = json_decode($stream_clean);

        $carteros = $this->Posicion_md->get_carteros_por_cliente($request->cliente);
        echo json_encode(["data" => $carteros]);
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

    public function ajax_datos_moviles(){
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $request = json_decode($stream_clean);
        
        $parametros_recibidos = $request->moviles;
        $pais = $request->pais;
        
        $buscar_guion = strpos($parametros_recibidos, '_');
        $posicion_guion = strpos($parametros_recibidos, '_');

        $fecha_actual = date('Y/m/d');
        
        if($buscar_guion){
            $parametros = array(
                'id_provincia' => substr($parametros_recibidos, 0, $posicion_guion),
                'id_tipo' => substr($parametros_recibidos, $posicion_guion + 1, 10),
            );              
        }else{
            $parametros = array(
                'id_cartero' => $parametros_recibidos,
            );            
        }
        
        
        $string_ids_carteros = $this->carteros_seleccion($parametros, $pais);

        if(strlen($string_ids_carteros) == false){
            $this->Responder();
            return;
        }
        
        $fecha_actual_inicio = $fecha_actual . ' 00:00:00';
        $fecha_actual_fin = $fecha_actual . ' 23:59:59';
        
        $query = $this->db->query("
            select *
            from(
                select 
                    fsc.apellido_nombre as 'Cartero'
                    , date(max(gps.FechaLocal)) as 'Ultima Fecha'
                    #, gps.id as 'id'
                    , TIME(min(gps.FechaLocal)) as 'Primer Horario'
                    , TIME(max(gps.FechaLocal)) as 'Ultimo Horario'
                    , TIMEDIFF(max(gps.FechaLocal), min(gps.FechaLocal)) as 'Tiempo total'
                    -- , fsc.id
                    -- , sum(if(gps.Distance>0,gps.Distance,0)) as 'distance'
                    , cast((sum(if(gps.Distance>0,gps.Distance,0)) * 0.1) as decimal(10,2)) as 'Distancia realizada (Km.s)'
                    , fsc.id
                    , TIMEDIFF(max(gps.FechaLocal), min(gps.FechaLocal)) as 'mute'
            
            
                from sispoc5_gestionpostal_me2.cartero_gps as gps
                left join (#
                    select temp.cartero_id as 'cartero_id', temp.FechaLocal as 'FechaLocal',min(gps.Time) as 'Time'
                    from sispoc5_gestionpostal_me2.cartero_gps as gps
                    left join (
                        select gps.cartero_id as 'cartero_id', min(gps.FechaLocal) as 'FechaLocal'
                        from sispoc5_gestionpostal_me2.cartero_gps as gps
                        WHERE 
                        gps.FechaLocal >= '$fecha_actual_inicio' 
                        and gps.FechaLocal <= '$fecha_actual_fin'
                        and gps.cartero_id in ($string_ids_carteros)
            
                        group by gps.cartero_id
                    ) as temp on gps.cartero_id = temp.cartero_id and gps.FechaLocal = temp.FechaLocal
                    WHERE 
                    gps.FechaLocal >= '$fecha_actual_inicio' 
                    and gps.FechaLocal <= '$fecha_actual_fin'
                    and gps.cartero_id in ($string_ids_carteros)
                    group by gps.cartero_id
                )as temp on gps.cartero_id = temp.cartero_id and gps.FechaLocal = temp.FechaLocal and gps.Time = temp.Time 
                left join sispoc5_gestionpostal_me2.flash_sucursales_carteros as fsc on gps.cartero_id = fsc.id
                WHERE 
                gps.FechaLocal >= '$fecha_actual_inicio'
                and gps.FechaLocal <= '$fecha_actual_fin'
                and gps.cartero_id in ($string_ids_carteros)
                and temp.cartero_id is null
                and fsc.pais = '$pais'
                group by gps.cartero_id
            ) as respuesta
            where 1
            and respuesta.mute > '00-00-00 00:00:00'
        ");
                
        $resultado_consulta_datos = $query->result();
          
        /*
        $result = [
			'Respuesta' => ['Datos' => $resultado_consulta_datos]
		];
        */
        
        //var_dump($resultado_consulta_datos);
        
        //return $result;
        $this->Responder($resultado_consulta_datos);
        
    }
    
    public function carteros_seleccion($parametros, $pais){
         
         if (count($parametros) == 1) {
            
            $id_cartero = $parametros["id_cartero"];
        
            $query = $this->db->query("
                    SELECT id
                    FROM sispoc5_gestionpostal_me2.flash_sucursales_carteros
                    WHERE id in ($id_cartero) and activo = 1 and pais = '$pais'
             ");

             $consulta_carteros = $query->result_array();

         } else {
             $tipo_id = $parametros["id_tipo"];
             $provincia_id = $parametros["id_provincia"];

            $query = $this->db->query("
                SELECT id
                FROM sispoc5_gestionpostal_me2.flash_sucursales_carteros
                WHERE (sucursal_id = $provincia_id OR 0 = $provincia_id)
                AND (cartero_tipo_id = $tipo_id OR 0 = $tipo_id)
                AND activo = 1 and pais = '$pais'
            ");

            $consulta_carteros = $query->result_array();
         }
 
         $ids_carteros = array_column($consulta_carteros, 'id');
         $string_ids_carteros = implode(',', $ids_carteros);
 
         if(strlen($string_ids_carteros) > 0){
             return $string_ids_carteros;
         }

         return false;
     }

     public function carteros_posiciones()
    {
        //$stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        //$request = json_decode($stream_clean);
        
        $parametros_enviar = $this->input->post('parametros');
        $pais = $this->input->post('pais');

        /*
        $parametros = $request->parametros;
        $pais = $request->pais;
        
        if($parametros->cartero_id){
            $parametros_enviar = array(
                'id_cartero' => $parametros->cartero_id,
            );  
                      
        }else{
            $parametros_enviar = array(
                'id_provincia' => $parametros->id_provincia,
                'id_tipo' => $parametros->id_tipo,
            );  
        }
        */
        
        $string_ids_carteros = $this->carteros_seleccion($parametros_enviar, $pais);

        if($string_ids_carteros == false){
            $this->Responder();
            return;
        }

        $fecha_actual = date('Y/m/d');
        $fecha_actual_inicio = $fecha_actual . ' 00:00:00';
        $fecha_actual_fin = $fecha_actual . ' 23:59:59';

        $query1 = $this->db->query("
                select tabla2.*, fsc.apellido_nombre as nombre

                from sispoc5_gestionpostal_me2.cartero_gps as tabla2

                inner join (
                	select max(tabla1.id) as id
                	from sispoc5_gestionpostal_me2.cartero_gps as tabla1
                	group by tabla1.cartero_id
                ) as sub1 on sub1.id = tabla2.id

                left join sispoc5_gestionpostal_me2.flash_sucursales_carteros as fsc on fsc.id = tabla2.cartero_id

                WHERE tabla2.cartero_id in ($string_ids_carteros) and fsc.pais = '$pais'
                and tabla2.FechaLocal >= '$fecha_actual_inicio' and tabla2.FechaLocal <= '$fecha_actual_fin' 
        ");

        $posicion_carteros = $query1->result_array();

        $query2 = $this->db->query("
            select count(id) as cantidad

            from sispoc5_gestionpostal_me2.flash_sucursales_carteros

            WHERE id in ($string_ids_carteros) and pais = '$pais'
        ");

        $cantidad_carteros = $query2->result_array();

       // dd($posicion_carteros);
        
        $datos = array(
            0 => $posicion_carteros,
            1 => $cantidad_carteros[0]->cantidad,
        );
        

        echo json_encode($datos);
    }

}

