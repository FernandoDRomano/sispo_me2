<?php

class Recepcion extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $query = Despacho::whereIn('estado', [Despacho::ESTADO_ENVIADO, Despacho::ESTADO_RECIBIDO, Despacho::ESTADO_VERIFICADO, Despacho::ESTADO_PARCIAL])->orderBy('create','desc')->limit(1000);

        if($this->is_user())
        {
            set_time_limit(000);
            ini_set('memory_limit','-1');
            $query->whereDestinoId($this->usuario->sucursal_id);
        }

        $this->data->despachos = $query->get();

        $this->title("Recepcion");
        $this->view = 'components/piezas/piezas_recepcion/index';
        $this->template('template/backend');
    }

    public function archivados()
    {
        $this->data->despachos = Despacho::whereEstado(Despacho::ESTADO_ARCHIVADO_DESTINO)->get();

        $this->title("Despachos Archivados en Destino");
        $this->view = 'components/piezas/piezas_recepcion/archivados';
        $this->template('template/backend');
    }

    /*public function ver($despacho_id)
    {
    	ini_set('memory_limit', '-1');
    	
        $this->data->piezas = $this->flash_md->getDespachoPiezasAgrupadas($despacho_id);//Despacho::findOrFail($despacho_id);      
        $this->data->despacho = Despacho::findOrFail($despacho_id);//
        $pieza_tipo= Pieza::TIPO_NORMAL;
	if (count($this->data->despacho) > 0 && $despacho->piezas[0]['tipo_id'] == Pieza::TIPO_SIMPLE) $pieza_tipo= Pieza::TIPO_SIMPLE ;
	$this->data->pieza_tipo = $pieza_tipo;
        $this->title("Ver Despacho Archivado en Destino");
        $this->view = 'components/piezas/piezas_recepcion/ver';
        $this->template('template/backend');
    }*/
    
    public function ver($despacho_id)
    {
        ini_set('memory_limit', '-1');
//    	echo date("H:i:s")." <br/> ";
        $this->data->piezas = $this->flash_md->getDespachoPiezasAgrupadas($despacho_id);//Despacho::findOrFail($despacho_id);      
        $this->data->despacho = Despacho::findOrFail($despacho_id);

        $this->title("Ver Despacho en Destino");
        $this->view = 'components/piezas/piezas_recepcion/ver';
        $this->template('template/backend');
    }

    public function verificar($despacho_id)
    {
        if($this->input->is_post())
        {
            $this->post_verificar();
        }

        $this->data->despacho = Despacho::findOrFail($despacho_id);
        $this->title("Recepcion - Verificar Piezas");
        $this->view = 'components/piezas/piezas_recepcion/verificar';
        $this->template('template/backend');
    }

    public function verificar_pieza()
    {   
        //var_dump($_POST);die;
        $despacho_id = $this->input->post('despacho_id');
        $id = $this->input->post("id");//id puede ser pieza_id o comprobante_id dependiendo de si es simple o normal
        $cantidad_recibida_simples = $this->input->post('cantidad_recibida_simples');
        $servicio_id = $this->input->post('servicio_id');
        $comprobante_ingreso_id = $this->input->post('comprobante_ingreso_id');
        $buscar_por = $this->input->post('buscar_por');
        $user_row = $this->ion_auth->user()->row();
        $despacho = Despacho::findOrFail($despacho_id);
        $pieza_id = null;
        if($cantidad_recibida_simples == NULL || $cantidad_recibida_simples == ''){
            //Proceso de verificacion para piezas NORMALES
            $pieza = null;
            if ($buscar_por == "1" ){
                $aux =   $id;
                $pieza = Pieza::where('id',$aux)->first();
                //echo "uno";echo($this->db->last_query());die;
            }
            if ($buscar_por == "2" ){
                $aux =  strip_tags((string)$id);
                $pieza = Pieza::where('barcode_externo',$aux)->first();
                //echo "dos";echo($this->db->last_query());die;
            }
            if($pieza==null){
                $this->data->error = "No se encontró la pieza";
                return $this->json();
            }
            //echo($this->db->last_query());die;
            //Verifico la pieza dentro de su despacho
            $pieza_en_despacho = DespachoPieza::whereDespachoId($despacho_id)->wherePiezaId($pieza->id)->first();
            $pieza_en_despacho->usuario_verifico_id = $user_row->id;
            $pieza_en_despacho->save();
            $pieza_id = $pieza->id;
        }
        if($cantidad_recibida_simples > 0 ){
            $piezas_simples_ids = Pieza::join(DespachoPieza::TABLE, Pieza::TABLE.'.id', '=', DespachoPieza::TABLE.'.pieza_id')
                ->join(Despacho::TABLE, DespachoPieza::TABLE.'.despacho_id', '=', Despacho::TABLE.'.id')
                ->where(Pieza::TABLE.'.estado_id', Pieza::ESTADO_EN_TRNSITO)
                ->where('comprobante_ingreso_id',$comprobante_ingreso_id)
                ->where('servicio_id',$servicio_id)
                ->select(Pieza::TABLE.'.id')
                ->get();
        
            //$piezas_simples_ids = $this->codegen_model->get('flash_piezas','id','comprobante_ingreso_id = '.$id .' AND servicio_id = '.$servicio_id);

            $cont = 0;
            for($i = 0; $i < count($piezas_simples_ids); $i++){
                if($cont < $cantidad_recibida_simples){
                    $pieza = Pieza::findOrFail($piezas_simples_ids[$i]->id);
                    $pieza->estado_id = Pieza::ESTADO_EN_GESTION;
                    $pieza->save();
                }else{
                    $pieza = Pieza::findOrFail($piezas_simples_ids[$i]->id);
                    $pieza->estado_id = Pieza::ESTADO_PERDIDA;
                    $pieza->save();
                }
                $cont++;
            }
        }
        //Consulto si el despacho tiene todavia piezas sin verificar
        //Consulto todas las piezas del despacho
        $verificado_completo = true;
        $despacho_piezas = DespachoPieza::whereDespachoId($despacho_id)->get();
        
        foreach ($despacho_piezas as $despacho_pieza) {
            $pieza_estado = Pieza::whereId($despacho_pieza->pieza_id)->first();
            //echo $pieza_estado->id."     ".$pieza_estado->estado_id."  <br/> ";
            if($pieza_estado->tipo_id == Pieza::TIPO_SIMPLE){
                if ($pieza_estado->estado_id != Pieza::ESTADO_EN_GESTION && $pieza_estado->estado_id != Pieza::ESTADO_PERDIDA ) $verificado_completo = false;
            }
            if($pieza_estado->tipo_id == Pieza::TIPO_NORMAL){
                if ($despacho_pieza->usuario_verifico_id == NULL ) $verificado_completo = false;
            }
        }
        
        
        if($verificado_completo)
        {
            $despacho->estado = Despacho::ESTADO_VERIFICADO;
            $despacho->save();
            $this->data->despacho_verificado = true;
        }
        $this->data->success = true;
        //Agrego para que termine cada verificacion
        $this->data->despacho_verificado_parcial = true;
        if($pieza_id != null){
            $this->data->pieza_id = $pieza_id;
        }
        ////
        $this->json();
    }

    public function piezas()
    {
        if($this->input->is_post())
        {
            $this->post_piezas();
        }

        if($this->is_user())
        {
            $disponibles = Pieza::join(DespachoPieza::TABLE, Pieza::TABLE.'.id', '=', DespachoPieza::TABLE.'.pieza_id')
                ->join(Despacho::TABLE, DespachoPieza::TABLE.'.despacho_id', '=', Despacho::TABLE.'.id')
                ->where(Despacho::TABLE.'.destino_id', $this->usuario->sucursal_id)
                ->where(Pieza::TABLE.'.estado_id', Pieza::ESTADO_EN_TRNSITO)
                ->select(Pieza::TABLE.'.*')
                ->get();
        }
        else
        {
            $disponibles = Pieza::whereEstadoId(Pieza::ESTADO_EN_TRNSITO)->get();
        }

        $piezas = [];
        $numeros = [];
        foreach ($disponibles as $pieza)
        {
            $numero = new stdClass;
            $numero->id = $pieza->id;
            $numero->barcode = $pieza->barcode;
            $numeros[] = $numero;

            if($pieza->barcode_externo)
            {
                $numero = new stdClass;
                $numero->id = $pieza->id;
                $numero->barcode = $pieza->barcode_externo;
                $numeros[] = $numero;
            }

            $p = new stdClass;
            $p->id = $pieza->id;
            $p->desc = $pieza->tipo_id == Pieza::TIPO_SIMPLE ? $pieza->datos_varios : $pieza->destinatario;
            $piezas[$pieza->id] = $p;
        }

        $this->data->piezas = $piezas;
        $this->data->numeros = $numeros;

        $this->title("Recepcion");
        $this->view = 'components/piezas/piezas_recepcion/piezas';
        $this->template('template/backend');
    }

    private function post_piezas()
    {
        foreach($this->input->post('piezas') as $pieza_id)
        {
            $pieza = Pieza::findOrFail($pieza_id);
            /*
            $despacho = $this->codegen_model->row('flash_piezas_despacho', '*', 'id = '.$pieza->despacho_id);
            $data = array(
                'recibidas' => $despacho->recibidas + 1,
                'fecha_recepcion' => date('Y-m-d'),
                'update' => date('Y-m-d H:m:s')
            );
            $this->codegen_model->edit('flash_piezas_despacho',$data,'id',$pieza->despacho_id);

            $data = array(
                'recepcion_estado_id' => RECEPCION_ESTADO_RECIBIDO,
                'estado_id' => 1,
                'cartero_id' => NULL,
                'distribuidor_id' => NULL,
                'fecha_estado' => date('Y-m-d'),
                'update' => date('Y-m-d H:m:s')
            );
            $this->codegen_model->edit('flash_piezas',$data,'id',$this->input->post('pieza_'.$i));
            $contador = $contador + 1;
            */
        }

        $this->session->set_flashdata('registro', 'precesado');
        redirect(base_url().'piezas/recepcion');
    }
}

/* End of file Recepcion.php */
/* Location: ./system/application/controllers/Recepcion.php */