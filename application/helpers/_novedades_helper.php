 <?php 
 
    function validar_novedad($novedad_actual_id,$novedad_nueva_id) {
        $mensaje = "";

        $CI = get_instance();
        
        $query = $CI->db
                            ->select('ev.pieza_estado_id as pieza_esstado_id, ev.nombre as nombre, ev.sucursal_id as sucursal_id, ev.condicionales as condicionales')
                            ->where('ev.id = '.$novedad_nueva_id)
                            ->get('flash_piezas_estados_variables ev');
        $result = $query->row();
        

        if ($result->condicionales == null){ return $mensaje;};

        $condiciones = explode("-",$result->condicionales);
        
        foreach($condiciones as $condicion){
            if($novedad_actual_id == $condicion){
                $mensaje = "";
                break;
            }else{
                if($mensaje == ""){
                    $mensaje = "Para poner esta Novedad la pieza debe estar en: ;";
                }else{
                    $query = $CI->db
                                ->select('nombre')
                                ->where('id = '.$condicion)
                                ->get('flash_piezas_estados_variables');
                    $result1 = $query->row();

                    $mensaje .= " ".$condicion." : ".$result1->nombre.";";
                }
            }
        }
       /* $query = $CI->db
                            ->select('d.id nro_despacho, d.destino_id, suc.nombre sucursal, c.nombre cliente, s.nombre servicio , COUNT(*) cantidad_piezas, ci.numero')
                            ->join('flash_piezas_despacho_piezas pd', 'd.id = pd.despacho_id')
                            ->join('flash_piezas p', 'p.id = pd.pieza_id')
                            ->join('flash_comprobantes_ingresos_servicios cis', 'cis.id = p.servicio_id')
                            ->join('flash_comprobantes_ingresos ci', 'p.comprobante_ingreso_id = ci.id')
                            ->join('flash_servicios s', 'cis.servicio_id = s.id')
                            ->join('flash_clientes c', 'ci.cliente_id = c.id')
                            ->join('flash_sucursales suc', 'd.destino_id = suc.id')
                            ->where('d.id = '.$novedad_id)
                            ->group_by('s.id, c.id')
                            ->get('flash_piezas_despacho d');
         $query->result();*/
        return $mensaje;
    }
?>