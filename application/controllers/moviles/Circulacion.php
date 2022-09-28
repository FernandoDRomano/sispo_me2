<?php

require_once("./application/views/template/rubenbackend/Elementos.php");

class Circulacion extends CI_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->permisos = $this->permisos_lib->control();
        $this->load->model('Circulacion_md');
        $this->load->helper('url');
    }

    public function index(){
        $vista_interna = array(
            'permisos_efectivos' => $this->permisos,
            'tipos_carteros' => $this->Circulacion_md->tipos_carteros(),
            'paises' => $this->Circulacion_md->paises(),
            'fecha' => date('Y-m-d')
        );

        $vista_externa = array(			
            'title' => ucwords("Mapa Hist&oacute;rico"),
            'contenido_main' => $this->load->view('components/moviles/circulacion/circulacion', $vista_interna, true)
        );		

        $this->load->view('template/rubenbackend', $vista_externa);
    }

    public function getSucursales(){
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $request = json_decode($stream_clean);

        $sucursales = $this->Circulacion_md->get_sucursales($request->pais);
        echo json_encode(["data" => $sucursales]);
    }

    public function getCarteros(){
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $request = json_decode($stream_clean);

        $carteros = $this->Circulacion_md->get_carteros($request->pais);
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

    public function recorrido_cartero()
    {
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $request = json_decode($stream_clean);
    
        $cartero_id = $request->cartero;
        $fechaI = $request->fecha_inicio;
        $fechaF = $request->fecha_fin;
        $pais = $request->pais;

        $fecha_inicio = $fechaI . ' 00:00:00';
        $fecha_fin = $fechaF . ' 23:59:59';
        
        $detalle_circulacion = $this->consulta_detalle_circulacion ($fecha_inicio, $fecha_fin, $cartero_id, $pais);         
        
        if (!empty($detalle_circulacion)) {
           
            $data = [
                'status' => true,
                'recorrido' => $detalle_circulacion
            ];

            echo json_encode($data);

        } else {
            
            $data = [
                'status' => false,
            ];

            echo json_encode($data);
        }

        
    }

    public function consulta_detalle_circulacion ($fecha_inicio, $fecha_fin, $cartero_id, $pais) {
        $query = $this->db->query("
            SELECT cgps.id, cgps.Latitude, cgps.Longitude, date_format(cgps.FechaLocal, '%d/%m/%Y %H:%i:%s') fecha, date_format(cgps.FechaLocal, '%d/%m/%Y') FechaLocal, cgps.cartero_id, TIME(cgps.FechaLocal) as tiempo

            FROM sispoc5_gestionpostal_me2.cartero_gps as cgps

            LEFT JOIN sispoc5_gestionpostal_me2.flash_sucursales_carteros as fsc on fsc.id = cgps.cartero_id

            WHERE cgps.FechaLocal >= '$fecha_inicio' and cgps.FechaLocal <= '$fecha_fin'
            and cgps.cartero_id = '$cartero_id' and fsc.pais = '$pais'
            
            order by cgps.FechaLocal ASC
            
            limit 32000
        ");   
        
        $detalle_circulacion = $query->result_array();
        return $detalle_circulacion;
    }

    public function consulta_piezas_por_fecha_y_cartero(){
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $request = json_decode($stream_clean);

        $fecha = $request->fecha;
        $cartero = $request->cartero;

        $query = $this->db->query("
            SELECT P.id, PP.descripcion_paquete, P.destinatario, P.domicilio,PR.nombre as provincia, L.nombre as localidad, C.latitud, C.longitud
            FROM flash_hojas_rutas HDR
            INNER JOIN flash_subpiezas SP ON SP.hoja_ruta_id = HDR.id
            INNER JOIN flash_piezas P ON SP.pieza_id = P.id
            INNER JOIN ubicacion_provincias PR ON PR.id = P.provincia_destino
            INNER JOIN ubicacion_localidades L ON L.id = P.localidad_destino
            INNER JOIN flash_piezas_coordenadas C ON C.pieza_id = P.id  
            INNER JOIN flash_piezas_paquetes PP ON PP.pieza_id = P.id
            WHERE HDR.cartero_id = $cartero AND HDR.fecha_entrega = '$fecha'
        ");

        $piezas = $query->result_array();
        echo json_encode($piezas);
    }

    public function obtener_movimiento_por_fecha(){
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $request = json_decode($stream_clean);
    
        $cartero_id = $request->cartero;
        $fechaI = $request->fecha_inicio;
        $fechaF = $request->fecha_fin;
        $pais = $request->pais;

        $fecha_inicio = $fechaI . ' 00:00:00';
        $fecha_fin = $fechaF . ' 23:59:59';

        $query = $this->db->query("
            SELECT COUNT(*) cantidad, date_format(cgps.FechaLocal, '%d/%m/%Y') fecha, date_format(cgps.FechaLocal, '%Y-%m-%d') FechaLocal FROM cartero_gps cgps
            LEFT JOIN sispoc5_gestionpostal_me2.flash_sucursales_carteros as fsc on fsc.id = cgps.cartero_id
            WHERE 
                cgps.FechaLocal >= '$fecha_inicio' 
                and cgps.FechaLocal <= '$fecha_fin'
                and cgps.cartero_id = '$cartero_id' 
                and fsc.pais = '$pais' 
            GROUP BY fecha 
            ORDER BY fecha ASC
        ");

        $detalle_de_movimiento_fecha = $query->result_array();
        echo json_encode($detalle_de_movimiento_fecha);
    }

}

