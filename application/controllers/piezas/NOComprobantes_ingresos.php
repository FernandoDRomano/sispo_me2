<?php

use Illuminate\Database\Capsule\Manager as DB;

class Comprobantes_ingresos extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index($flag=null)
    {
        $query = Comprobante::orderBy('fecha_pedido', 'desc');
        if($this->is_user()){
            $query->where('sucursal_id', $this->usuario->sucursal_id);
        }
        $comprobantes = $query->get();
//        echo($this->db->last_query());die;
        $this->data([
            'comprobantes' => $comprobantes,
            'empresa_id' => false,
            'cliente_id' => false,
            'departamento_id' => false,
            'empresas' => Empresa::all(),
            'clientes' => Cliente::where('cliente_estado_id',Cliente::ESTADO_ACTIVO)->get(),
            'departamentos' => ClienteDepartamento::all(),
            'flag' => ($flag!=null)?$flag:'piezas'
        ]);

        $this->title('comprobantes de ingresos');
        $this->view = 'components/piezas/comprobantes_ingresos/comprobantes_ingresos_list';
        $this->template('template/backend');
    }

    public function filtro()
    {
        $empresa_id = $this->input->post('empresa');
        $cliente_id = $this->input->post('cliente');
        $departamento_id = $this->input->post('dpto');

        $query = Comprobante::orderBy('create','asc');
        if($this->is_user())
            $query->where('sucursal_id', $this->usuario->sucursal_id);

        if($empresa_id)
            $query->where('empresa_id', $empresa_id);

        if($cliente_id)
            $query->where('cliente_id', $cliente_id);

        if($departamento_id)
            $query->where('departamento_id', $departamento_id);

        $comprobantes = $query->get();


        $this->data([
            'comprobantes' => $comprobantes,
            'empresa_id' => $empresa_id ? $empresa_id : Empresa::whereDefault(1)->first()->id,
            'cliente_id' => $cliente_id ? $cliente_id : false,
            'departamento_id' => $departamento_id ? $departamento_id : false,
            'empresas' => Empresa::all(),
            'clientes' => Cliente::where('cliente_estado_id',Cliente::ESTADO_ACTIVO)->get(),
            'departamentos' => ClienteDepartamento::all(),
        ]);

        $this->title('comprobantes de ingresos');
        $this->view = 'components/piezas/comprobantes_ingresos/comprobantes_ingresos_list';
        $this->template('template/backend');
    }

    public function piezas($id=NULL, $flag=NULL)
   {
//        if($id == NULL) $id = $this->input->post('comprobante_id');
        if($flag == null) $flag = $this->input->post('flag');
        if ($id != NULL && $this->input->post('numero') == ''){ 
            $comprobante = Comprobante::findOrFail($id);
        }else{
            $numero = trim($this->input->post('numero'));
            $comprobante = Comprobante::whereNumero($numero)->first();
        }
        if($comprobante != null){
            $comprobante_servicios = $this->flash_md->getPiezasPorServiciosPorComprobante($comprobante->id);
            usort($comprobante_servicios, function($a, $b)
            {
                return strcmp($b->creacion, $a->creacion);
            });
        }else
            {
                $this->session->set_flashdata('registro', $numero);
                redirect(base_url('piezas/piezas/alta_piezas_masivas'));
            }
        $this->data([
            'comprobante' => $comprobante,
            'comprobante_servicios' => $comprobante_servicios,//ComprobanteServicio::whereComprobanteIngresoId($comprobante->id)->get(),
            'servicios' => $this->flash_md->getServiciosPorComprobante($comprobante->id),
            'estados' => PiezaEstado::all(),
            'tipos' => PiezaTipo::all(),
            'cliente' => Cliente::whereId($comprobante->cliente_id)->first()
        ]);

        if ($flag == 'piezas'){
            $this->title('comprobante '.$comprobante->numero.' - alta de piezas');
            $this->view = 'components/piezas/comprobantes_ingresos/comprobantes_ingresos_piezas';
        }
        if ($flag == 'piezas_masivas'){
            $this->title('comprobante '.$comprobante->numero.' - alta masiva de piezas');
            $this->view = 'components/piezas/comprobantes_ingresos/comprobantes_ingresos_piezas_masivas';
        }
        $this->template('template/backend');
    }

    public function piezas_add()
    {
        $servicio_id = $this->input->post('servicio_id');
        $tipo_id = $this->input->post('tipo_id');
        $cantidad = $this->input->post('cantidad');
        $barcode_externo = $this->input->post('barcode_externo');
        $destinatario = $this->input->post('destinatario');
        $domicilio = $this->input->post('domicilio');
        $codigo_postal = $this->input->post('codigo_postal');
        $localidad = $this->input->post('localidad');
        $datos_varios = $this->input->post('datos_varios');
        $datos_varios_1 = $this->input->post('datos_varios_1');
        $datos_varios_2 = $this->input->post('datos_varios_2');
        $comprobante_id = $this->input->post('comprobante_id');
        foreach($this->input->post('fila') as $i)
        {
            for($j=1 ; $j <= $cantidad[$i] ; $j++ ){
                $cantidad_modficado = 1;
                $pieza = Pieza::create([
                    'usuario_id' => $this->usuario->id,
                    'servicio_id' => $servicio_id[$i],
                    'tipo_id' => $tipo_id[$i],
                    'sucursal_id' => $this->usuario->sucursal_id,
                    'estado_id' => Pieza::ESTADO_EN_GESTION,
                    'cantidad' => $cantidad_modficado,
                    'comprobante_ingreso_id' => $comprobante_id,
                    'barcode_externo' => $barcode_externo[$i],
                    'destinatario' => $destinatario[$i],
                    'domicilio' => $domicilio[$i],
                    'codigo_postal' => $codigo_postal[$i],
                    'localidad' => $localidad[$i],
                    'datos_varios' => $datos_varios[$i],
                    'datos_varios_1' => $datos_varios_1[$i],
                    'datos_varios_2' => $datos_varios_2[$i],
                ]);
                $pieza->barcode = sprintf("%06d", $pieza->id);
                $pieza->save();
                $servicio = ComprobanteServicio::findOrFail($servicio_id[$i]);
                $servicio->disponible = $servicio->disponible - $cantidad_modficado;
                $servicio->save();
            }
        }
        $comprobante = Comprobante::findOrFail($this->input->post('comprobante_id'));

        $this->session->set_flashdata('registro', $comprobante->numero);
        redirect(base_url('piezas/comprobantes_ingresos/index/piezas'));
    }

    public function piezas_masivas_add()
    { 
        $filename = $this->input->post('filename');
        $path =$_SERVER['DOCUMENT_ROOT'] .'/assets/upload/';
        
        $file = fopen($path.$filename,"r");
        $cantidad_piezas = 0;
        $piezas = null;
        while(! feof($file))
          {
            $piezas[$cantidad_piezas] = (fgetcsv($file, 10000,";"));
            $cantidad_piezas++;
          }
          
        fclose($file);
        for($i=1; $i < count($piezas); $i++){
            for($j=0 ; $j < count($piezas[$i]) ; $j++){
                if($piezas[$i] != NULL)
                    $respuesta[$i][$j] = $piezas[$i][$j];
//                            var_dump($respuesta[$i][$j])."<br/>";
            } 
        }

        $comprobante_servicio = $this->codegen_model->row('flash_comprobantes_ingresos_servicios','*','id = '.$this->input->post('servicio_id'));
        $servicio = $this->codegen_model->row('flash_servicios','*','id = '.$comprobante_servicio->servicio_id);
        $respuesta['servicio']['nombre'] = $servicio->nombre;
    
        $contador = count($respuesta);
        $servicio_id = $this->input->post('servicio_id');
        $tipo_id = 2; //2=Piezas Normales 1=Piezas Simples
        $cantidad = $contador-1;
        //echo $contador;die;
        $comprobante_id = $comprobante_servicio->comprobante_ingreso_id;//$this->input->post('comprobante_id');
        set_time_limit(1800);
        ini_set('memory_limit','-1');
//        echo date("H:m:s")."<br/>";
        for($i = 1 ; $i < $contador ; $i++){
            $array_piezas[$i] = array(
                'usuario_id' => $this->usuario->id,
                'servicio_id' => $servicio_id,
                'tipo_id' => $tipo_id,
                'sucursal_id' => $this->usuario->sucursal_id,
                'estado_id' => Pieza::ESTADO_EN_GESTION,
                'comprobante_ingreso_id' => $comprobante_id,
                'cantidad' => 1,
                'barcode_externo' => $respuesta[$i][4],
                'destinatario' => $respuesta[$i][0],
                'domicilio' => $respuesta[$i][1],
                'codigo_postal' => $respuesta[$i][2],
                'localidad' => $respuesta[$i][3],
                'datos_varios' => $respuesta[$i][5],
                'datos_varios_1' => $respuesta[$i][6],
                'datos_varios_2' => $respuesta[$i][7],
            );
//            $pieza = Pieza::create([
//                'usuario_id' => $this->usuario->id,
//                'servicio_id' => $servicio_id,
//                'tipo_id' => $tipo_id,
//                'sucursal_id' => $this->usuario->sucursal_id,
//                'estado_id' => Pieza::ESTADO_EN_GESTION,
//                'comprobante_ingreso_id' => $comprobante_id,
//                'cantidad' => $cantidad,
//                'barcode_externo' => $respuesta[$i][4],
//                'destinatario' => $respuesta[$i][0],
//                'domicilio' => $respuesta[$i][1],
//                'codigo_postal' => $respuesta[$i][2],
//                'localidad' => $respuesta[$i][3],
//                'datos_varios' => $respuesta[$i][5],
//                'datos_varios_1' => $respuesta[$i][6],
//                'datos_varios_2' => $respuesta[$i][7],
//            ]);
            //$this->db->last_query(); die;
        }
//        echo date("H:m:s")."<br/>";
        $this->db->insert_batch('flash_piezas', $array_piezas);
        $piezas_guardadas = Pieza::whereComprobanteIngresoId($comprobante_id)->get();
        foreach ($piezas_guardadas as $key => $value)
        {
            $data[] = array(
                           'id' => $value->id ,
                           'barcode' => sprintf("%06d", $value->id) ,
                     );
        }
        $this->db->update_batch('flash_piezas', $data, 'id'); 
//        $pieza->barcode = sprintf("%06d", $pieza->id);
//        $pieza->save();
        $cantidad_piezas-- ; //Quito el encabezado
        $servicio = ComprobanteServicio::where('id',$servicio_id)->first();
        $servicio->disponible = $servicio->disponible - $cantidad_piezas;
        $servicio->save();
        $comprobante = Comprobante::findOrFail($comprobante_servicio->comprobante_ingreso_id);
        $this->session->set_flashdata('registro', $comprobante->numero);
//        echo date("H:m:s")."<br/>";
        redirect(base_url('piezas/comprobantes_ingresos/index/piezas_masivas'));
    }
    
    public function view($id)
    {
        $comprobante = Comprobante::findOrFail($id);
        $this->data->comprobante_servicios = ComprobanteServicio::whereComprobanteIngresoId($comprobante->id)->get();

        $this->title('comprobantes de ingresos');
        $this->view = 'components/piezas/comprobantes_ingresos/comprobantes_ingresos_view';
        $this->template('template/fancybox');
    }
    
    public function archivar()
    {
        $comprobante_id = $this->input->post('comprobante_id');
        $estado = $this->input->post('estado');
        $comprobante = Comprobante::findOrFail($comprobante_id);
        $comprobante->estado = $estado;
        $comprobante->save();
       
        if($estado == Comprobante::ESTADO_CANCELADA)
        {
            foreach ($comprobante->piezas as $pieza)
            {
                $comprobante->estado_id = Comprobante::ESTADO_EN_GESTION;
                $comprobante->save();
            }
        }

        $this->data->success = true;
        //echo var_dump($this->json());die;
//        $this->json();
         print json_encode($this->data);
        
    }
    
    public function exportarAPDF()
    {
        $comprobante_ingreso_id = $this->input->post('pdf_comprobante_ingreso_id');
        $impresion_tipo = $this->input->post('impresion_tipo');
        $servicio_id = $this->input->post('pdf_servicio_id');
        if ($impresion_tipo == 'acuse') $this->exportarAcuseAPDF ($comprobante_ingreso_id, $servicio_id);
        if ($impresion_tipo == 'etiquetas') $this->exportarEtiquetasAPDF ($comprobante_ingreso_id,  $servicio_id);
        
    }
    
    public function exportarAcuseAPDF($comprobante_ingreso_id, $servicio_id)
    {
        $this->load->library('simplehtmldom_1_5/simple_html_dom'); 
        $this->load->library('Pdf_noheader');  
        $piezas = $this->flash_md->getPiezasPorServiciosPorComprobanteSinGroup(/*44,71*/$comprobante_ingreso_id,  $servicio_id);
        //Set custom header data
        $custom_layout = array(220, 340);
        $pdf = new Pdf_noheader('P', 'mm', 'Legal', true, 'UTF-8', false); //: portrait, L:landscape
        
        $pdf->SetTitle('Acuse'); 
        $pdf->SetAutoPageBreak(true); 
        $pdf->SetAuthor('Author'); 
        $pdf->SetDisplayMode('real', 'default'); 
        $pdf->Write(5, 'CodeIgniter TCPDF Integration'); 

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once(dirname(__FILE__).'/lang/eng.php');
            $pdf->setLanguageArray($l);
        }

            // ---------------------------------------------------------

            // set default font subsetting mode
            $pdf->setFontSubsetting(true);

            // Set font
            // dejavusans is a UTF-8 Unicode font, if you only need to
            // print standard ASCII chars, you can use core fonts like
            // helvetica or times to reduce file size.
            $pdf->SetFont('dejavusans', '', 8, '', true);
            $pdf->SetMargins(2, 2, 2);
            // Add a page
            // This method has several options, check the source code documentation for more information.
            $pdf->AddPage('P', $custom_layout, false, false);
       
             // create some HTML content
            //$subtable = '<table border="1" cellspacing="6" cellpadding="4"><tr><td>a</td><td>b</td></tr><tr><td>c</td><td>d</td></tr></table>';
            //Para que aparezca un borde poner border:inset 0; en style
            $table_contenido = "";
            //$pdf->Image(APPPATH.'media/logo_flash.gif', 1, 1, 2.5, 0.6);
            $image_file = APPPATH.'media/logo_flash.gif';
            $image_square = APPPATH.'media/square.gif';
            $style = array(
                        'border'=>false,
                        'position' => 'absolute',
//                        'align' => 'C',
//                        'stretch' => false,
//                        'fitwidth' => true,
//                        'cellfitalign' => '',
//                        'border' => false,
                        'hpadding' => '10',
                        'vpadding' => '20',
//                        'fgcolor' => array(0,0,0),
//                        'bgcolor' => array(255,255,255),
                        'text' => true,
                        'font' => 'helvetica',
                        'fontsize' => 8,
//                        'stretchtext' => 4
                        );
           
            $table_contenido ='';
            $contador = 0;
            set_time_limit(180);
            foreach($piezas as $pieza){
//            for($i=0;$i<20;$i++){
//                $pieza = $piezas[0];
                if($contador > 4){
                    $pdf->AddPage('P', $custom_layout, false, false);
                    $contador = 0;
                } 

               $params = $pdf->serializeTCPDFtagParameters(array($pieza->pieza_id, 'C39', '', '', 40, 10, 0.4, array('position'=>'S', 'border'=>FALSE, 'padding'=>1, 'fgcolor'=>array(0,0,0), 'bgcolor'=>array(255,255,255), 'text'=>true, 'font'=>'helvetica', 'fontsize'=>7, 'stretchtext'=>4), ''));
               $table_contenido  = '<table width="720px" border="0" style="border-collapse: separate;border-spacing: 5px 0px;border-bottom-width: 0px solid;">
                                    <tr>
                                        <td width="132.3px" align="center" padding="0px" rowspan="5" style="margin-top:0px;margin-bottom:0px;"><img src="'.$image_file.'" width="94.5px" height="20px"/>
                                                                                           <label style="line-height:7px;font-size:7px;text-align:center">R.N.P.S.P. 687</label><br/>
                                                                                           <label style="line-height:7px;font-size:7px;text-align:center">Tel: 0381-4200922 PBX</label>
                                                                                           <label style="line-height:7px;font-size:7px;text-align:center">info@correoflash.com</label>
                                                                                           <label style="line-height:7px;font-size:7px;text-align:center">www.correoflash.com</label>
                                        </td>
                                        <td width="151.2px" height="10px"  style="font-size:7.5px;">Cliente:</td>
                                        <td width="132.3px" ></td>
                                        <td width="75.6px"></td>
                                        <td width="75.6px"></td>
                                        <td width="189px" rowspan="2"><tcpdf method="write1DBarcode" params="'.$params.'" /></td>
                                    </tr>
                                    <tr>
                                        
                                        <td width="100%" height="10px" style="font-size:8px" colspan="4">'.$pieza->cliente.' - '.$pieza->departamento.'</td>

                                        
                                    </tr>
                                    <tr>
                                        
                                        <td width="151.2px" height="10px" style="font-size:7.5px">'.$pieza->precio_especial_nombre.'</td>
                                        <td width="132.3px"></td>
                                        <td width="75.6px"></td>
                                        <td width="75.6px"></td>
                                        <td width="189px"></td>
                                    </tr>
                                    <tr>
                                        
                                        <td width="151.2px" height="8px" style="font-size:7.5px">DESTINATARIO</td>
                                        <td  colspan="3" width="100%">'.$pieza->destinatario.'</td>
                                        
                                    </tr>
                                    <tr>
                                        
                                        <td width="151.2px" height="8px" style="font-size:7.5px">DOMICILIO</td>
                                        <td width="100%" colspan="3" style="font-size:7.5px">'.$pieza->domicilio.'</td>
                                        <td width="75.6px"></td>
                                        <td width="75.6px"></td>
                                        <td width="189px"></td>
                                    </tr>
                                    <tr>
                                        <td width="132.3px" text-align="center"  rowspan="6" >
                                                                                <img src="'.$image_square.'" align="middle" width="9px" height="9px"/><label style="font-size:5.6px;margin-top:0px;margin-bottom:3px;">01-NO RESPONDE</label><br>
                                                                                <img src="'.$image_square.'" align="middle" width="9px" height="9px"/><label style="font-size:5.6px;margin-top:0px;margin-bottom:3px;">02-NO EXISTE EL Nº</label><br>
                                                                                <img src="'.$image_square.'" align="middle" width="9px" height="9px"/><label style="font-size:5.6px;margin-top:0px;margin-bottom:3px;">03-NO EXISTE DIR</label><br>
                                                                                <img src="'.$image_square.'" align="middle" width="9px" height="9px"/><label style="font-size:5.6px;margin-top:0px;margin-bottom:3px;">05-DEST DESC.</label><br>
                                                                                <img src="'.$image_square.'" align="middle" width="9px" height="9px"/><label style="font-size:5.6px;margin-top:0px;margin-bottom:3px;">06-DCLIO ABAND</label><br>
                                                                                <img src="'.$image_square.'" align="middle" width="9px" height="9px"/><label style="font-size:5.6px;margin-top:0px;margin-bottom:3px;">07-DCLIO INSUF</label><br>
                                                                                <img src="'.$image_square.'" align="middle" width="9px" height="9px"/><label style="font-size:5.6px;margin-top:0px;margin-bottom:3px;">08-SE MUDO</label><br>
                                                                                <img src="'.$image_square.'" align="middle" width="9px" height="9px"/><label style="font-size:5.6px;margin-top:0px;margin-bottom:3px;">10-FALLECIO</label><br>
                                                                                <img src="'.$image_square.'" align="middle" width="9px" height="9px"/><label style="font-size:5.6px;margin-top:0px;margin-bottom:3px;">12-SE NIEGA A REC</label><br>
                                                                                <img src="'.$image_square.'" align="middle" width="9px" height="9px"/><label style="font-size:5.6px;margin-top:0px;margin-bottom:3px;">OTROS ____________</label></td>
                                        <td width="151.2px" style="font-size:7.5px">CP LOCALIDAD</td>
                                        <td width="300px" colspan="3" style="font-size:8px">'.$pieza->codigo_postal.'   '.$pieza->localidad.'</td>
                                        <td width="189px" style="text-align:center;font-size:9px">.......................................<br/>       DNI</td>
                                    </tr>
                                    <tr>
                                        
                                        <td width="151.2px" height="8px"style="font-size:7.5px">DATOS VARIOS</td>
                                        <td width="300px"  colspan="3">'.$pieza->datos_varios.'</td>
                                        <td width="189px"></td>
                                    </tr>
                                    <tr>
                                        <td height="8px" width="151.2px" style="line-height:20px;font-size:7.5px;border-left-style:solid; 1px solid black;border-top-style:solid; 1px solid black;border-right-style:solid; 1px solid black;">FECHA 1 VISITA____/____/____</td>
                                        <td width="132.3px" width="151.2px" style="line-height:10px;font-size:7.5px;border-left-style:solid; 1px solid black;border-top-style:solid; 1px solid black;border-right-style:solid; 1px solid black;"><label style="text-align:center">DESCRIPCION DEL LUGAR</label><br/>FRENTE</td>
                                        <td width="75.6px" style="text-align:center;line-height:20px;font-size:7.5px;border-left-style:solid; 1px solid black;border-top-style:solid; 1px solid black;border-right-style:solid; 1px solid black;">LATERALES</td>
                                        <td width="75.6px" style="text-align:center;line-height:20px;font-size:7.5px;border-left-style:solid; 1px solid black;border-top-style:solid; 1px solid black;border-right-style:solid; 1px solid black;border-bottom-style:solid; 1px solid black;">DISTRIBUIDOR</td>
                                        <td width="189px"  style="text-align:center;font-size:9px"><br/>.......................................<br/>       ACLARACION</td>
                                    </tr>
                                    <tr>
                                        
                                        <td width="151.2px" style="line-height:20px;font-size:7.5px;border-left-style:solid; 1px solid black;border-top-style:solid; 1px solid black;border-right-style:solid; 1px solid black;">FECHA 2 VISITA____/____/____</td>
                                        <td width="132.3px" width="151.2px" style="line-height:20px;font-size:7.5px;border-left-style:solid; 1px solid black;border-top-style:solid; 1px solid black;border-right-style:solid; 1px solid black;">VENTANA</td>
                                        <td width="75.6px" style="text-align:center;line-height:20px;font-size:7.5px;border-left-style:solid; 1px solid black;border-top-style:solid; 1px solid black;border-right-style:solid; 1px solid black;border-bottom-style:solid; 1px solid black;"> DER   |   IZQ </td>
                                        <td width="75.6px" style="line-height:20px;font-size:7.5px;border-left-style:solid; 1px solid black;border-top-style:solid; 1px solid black;border-right-style:solid; 1px solid black;border-bottom-style:solid; 1px solid black;"></td>
                                        <td width="189px"></td>
                                    </tr>
                                    <tr>
                                        
                                        <td width="151.2px" cellpadding="10" style="line-height:20px;font-size:7.5px;border-left-style:solid; 1px solid black;border-top-style:solid; 1px solid black;border-right-style:solid; 1px solid black;border-bottom-style:solid; 1px solid black;"><br>FECHA 3 VISITA____/____/____</td>
                                        <td width="132.3px" width="151.2px" style="line-height:20px;font-size:7.5px;border-left-style:solid; 1px solid black;border-top-style:solid; 1px solid black;border-right-style:solid; 1px solid black;border-bottom-style:solid; 1px solid black;">PUERTA</td>
                                        <td width="75.6px" style="line-height:20px;font-size:7.5px;border-left-style:solid; 1px solid black;border-top-style:solid; 1px solid black;border-right-style:solid; 1px solid black;border-bottom-style:solid; 1px solid black;"></td>
                                        <td width="75.6px"></td>
                                        <td width="189px"></td>
                                    </tr>
                                    <tr>
                                        
                                        <td width="151.2px" style="line-height:10px;font-size:7.5px;">F ING'.$pieza->fecha_ingreso.'    CI '.$pieza->comprobante_numero.'</td>
                                        <td width="151.2px" style="line-height:10px;font-size:7.5px;">BARRA EXT  '.$pieza->barcode_externo.'</td>
                                        <td width="75.6px"></td>
                                        <td width="120px" style="text-align:center">...................................<br/>       vinculo</td>
                                        <td width="120px" style="text-align:center">...................................<br/>       firma</td>
                                    </tr></table><br/>';
               $contador++;
               $pdf->writeHTML($table_contenido, true, false, true, false, '');
            }
            $pdf->Output('acuse.pdf', 'I');
    }
    
    public function exportarEtiquetasAPDF($comprobante_ingreso_id,  $servicio_id)
    {
        $this->load->library('simplehtmldom_1_5/simple_html_dom'); 
        $this->load->library('Pdf_noheader_nofooter');  
        $piezas = $this->flash_md->getPiezasPorServiciosPorComprobanteSinGroup($comprobante_ingreso_id, $servicio_id);
        //Set custom header data
        $custom_layout = array(32, 65);
        $pdf = new Pdf_noheader_nofooter('L', 'mm', $custom_layout, true, 'UTF-8', false); //: portrait, L:landscape
        
        $pdf->SetTitle('Acuse'); 
        //$pdf->SetAutoPageBreak(true); 
        $pdf->SetAuthor('Author'); 
        $pdf->SetDisplayMode('real', 'default'); 
        $pdf->Write(5, 'CodeIgniter TCPDF Integration'); 

        // set default monospaced font
        //$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, 0);

        // set image scale factor
        //$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once(dirname(__FILE__).'/lang/eng.php');
            $pdf->setLanguageArray($l);
        }
            // ---------------------------------------------------------

            // set default font subsetting mode
            $pdf->setFontSubsetting(true);

            // Set font
            // dejavusans is a UTF-8 Unicode font, if you only need to
            // print standard ASCII chars, you can use core fonts like
            // helvetica or times to reduce file size.
            $pdf->SetFont('dejavusans', '', 8, '', true);
            $pdf->SetMargins(2,2, 2);
            // set Rotate
            $params = $pdf->serializeTCPDFtagParameters(array(90));
            // Add a page
            // This method has several options, check the source code documentation for more information.
            
       
             // create some HTML content
            //$subtable = '<table border="1" cellspacing="6" cellpadding="4"><tr><td>a</td><td>b</td></tr><tr><td>c</td><td>d</td></tr></table>';
            //Para que aparezca un borde poner border:inset 0; en style
            
            $table_contenido = "";
            //$pdf->Image(APPPATH.'media/logo_flash.gif', 1, 1, 2.5, 0.6);
            $image_file_rotate = APPPATH.'media/logo_flash_rotate.gif';
            $rnpsp687_rotate = APPPATH.'media/rnpsp687_rotate.gif';
           
           
            $table_contenido ='';
            $i = 0;
            foreach($piezas as $pieza){
                $pdf->AddPage('L', $custom_layout, false, false);
                $y = 5;
                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $xpos = 15;
                $ypos = 17;
            // The width is set to the the same as the cell containing the name.  
            // The Y position is also adjusted slightly.
            $pdf->write1DBarcode($pieza->pieza_id, 'C39', $xpos, $ypos, 105, 5, 0.4, $style, 'M');
            //Reset X,Y so wrapping cell wraps around the barcode's cell.
            $pdf->SetXY($x,$y);
//            $pdf->Cell(105, 51, $c->nome, 1, 0, 'C', FALSE, '', 0, FALSE, 'C', 'B');
               $table_contenido  = '<table width="140px" height="189px" border="0" style="">
                                    <tr>
                                        <td width="20px" rowspan="6">
                                            <img src="'.$image_file_rotate.'" width="15px" height="75.6px"/>
                                        </td>
                                        <td width="15px" rowspan="6">
                                            <img src="'.$rnpsp687_rotate.'" width="7.5px" height="56px"/>
                                        </td>
                                        <td width="140px" style="font-size:7px;line-height:10px" colspan="4">'.$pieza->destinatario.'</td>
                                    </tr>
                                    <tr>
                                        
                                        <td width="140px" style="font-size:7px;line-height:10px" colspan="4">'.$pieza->domicilio.'</td>

                                    </tr>
                                    
                                    <tr>
                                    
                                        <td width="140px" style="font-size:7px;line-height:10px" colspan="4">'.$pieza->codigo_postal.'  '.$pieza->localidad.'</td>

                                    </tr>
                                    <tr>
                                    
                                        <td  width="140px" style="font-size:7px;line-height:15px;text-align:right" colspan="4">'.$pieza->comprobante_numero.'</td>

                                    </tr>
                                    <tr>
                                       
                                        <td width="140px" style="font-size:7px;line-height:15px" colspan="4"align="right"></td>

                                    </tr>

                                    <tr>

                                        <td width="75px" colspan="2" style="line-height:15px;">'.$pieza->fecha_ingreso.'</td>

                                        <td width="65px" colspan="2" align="right">'.$pieza->pieza_id.'</td>

                                    </tr>
                                </table>';      
               $pdf->writeHTML($table_contenido, true, false, true, false, '');
            }
            
//            echo $html.$table_contenido.$cierre_table;die;
            
            $pdf->Output('etiquetas.pdf', 'I');
    }
    
    protected function view_index($view)
    {
        $this->view = $view;
    }
    
    public function modificar_comprobantes_comprobar()
    {
        if($this->input->is_post())
        {
            $numero = trim($this->input->post('numero'));
            $query = Comprobante::whereNumero($numero)->whereEstado(ComprobanteGenerado::ESTADO_USADO)->whereLiquidacionId(0);
            if($comprobante = $query->first())
            {
                redirect(base_url('piezas/comprobantes_ingresos/modificar_comprobantes_index/'.$comprobante->id));
            }
            else
            {
                $this->session->set_flashdata('registro', $numero);
            }
        }

        $this->title('Modificación de comprobantes de ingresos');
        $this->view_index('components/piezas/comprobantes_ingresos/comprobantes_ingresos_index');
        $this->template('template/backend');
    }
    
    public function modificar_comprobantes_index($comprobante_id)
    {
        $comprobante = Comprobante::findOrFail($comprobante_id);
        $comprobante_detalle = $this->flash_md->getComprobanteDetalle($comprobante->id); 

        $query_servicios = $this->db
                                    ->select('pe.cliente_id , pe.id, s.codigo, pe.nombre, pe.precio, pe.dias, pe.servicio_id')
                                    ->from('flash_servicios s')
                                    ->join('flash_clientes_precios_especiales pe', 's.id = pe.servicio_id')
                                    ->where('pe.cliente_id', $comprobante->cliente_id)
                                    ->get();
//        echo $this->db->last_query();die;
        $this->data([
            'comprobante' => $comprobante,
            'empresas' => Empresa::all(),
            'sucursales' => $this->is_user() ? Sucursal::whereId($this->usuario->sucursal_id)->get() : Sucursal::all(),
            'clientes' => Cliente::select('id', DB::raw('CONCAT(nombre," (",nombre_fantasia,")") as name'))->where('cliente_estado_id',Cliente::ESTADO_ACTIVO)->get(),
            'cliente' => Cliente::findOrFail($comprobante->cliente_id),
            'servicios' => $query_servicios->result(),//ClientePrecioEspecial::whereClienteId($comprobante->cliente_id)->get(),
            'departamentos' => ClienteDepartamento::whereClienteId($comprobante->cliente_id)->get(),
            'comprobante_detalle' => $comprobante_detalle,
        ]);

        $this->title('comprobantes de ingresos');
        $this->view = 'components/piezas/comprobantes_ingresos/comprobantes_ingresos_edit';
        $this->template('template/backend');
    }
    
    public function agregar_servicio()
    {
        $cantidad = 0;
        $etiqueta = $this->codegen_model->row('flash_comprobantes_ingresos_generados', '*', 'id = '.$this->input->post('numero_id'));
        $talonario = $this->codegen_model->row('flash_piezas_talonarios', '*', 'id = '.$etiqueta->talonario_id);
        $comprobante = Comprobante::findOrFail($this->input->post('comprobante_ingreso_id'));
        $fecha_pedido = $this->input->post('fecha_pedido');
        $fecha_pedido = new DateTime($fecha_pedido);
        $servicio_id = $this->input->post('servicio_'.$i);
        //Modificar flash_comprobantes_ingresos
        $data = array(
            //'id' => $comprobante->id,
            'cliente_id' => $this->input->post('cliente_id'),
            'empresa_id' => $this->input->post('empresa_id'),
            'sucursal_id' => $this->input->post('sucursal_id'),
            'departamento_id' => $this->input->post('departamento_id'),
            'fecha_pedido' => $fecha_pedido->format('Y-m-d 00:00:00'),
            'create' => date("Y-m-d H:i:s")
        );
        $this->codegen_model->edit('flash_comprobantes_ingresos',$data,'id',$comprobante->id);

        //Servicios Agregados
        if($this->input->post('cantidad_servicios') > 0)
        {
            for($i=1; $i <= $this->input->post('cantidad_servicios'); $i++)
            {
                if($this->input->post('servicio_'.$i))
                {
                        $data = array(
                            'comprobante_ingreso_id' => $comprobante->id,
                            'servicio_id' => $this->input->post('servicio_'.$i),
                            'cantidad' => $this->input->post('cantidad_'.$i),
                            'disponible' => $this->input->post('cantidad_'.$i),
                            'remito' => $this->input->post('remito_'.$i)!=''?$this->input->post('remito_'.$i):0
                        );
                        $this->codegen_model->add('flash_comprobantes_ingresos_servicios',$data);
                        $cantidad = $cantidad + $this->input->post('cantidad_'.$i);
                }
            }
        }
        
        //Solo modifico la cantidad de los servicios que se agregaron
        $data = array('cantidad' => $cantidad+$comprobante->cantidad);
        $this->codegen_model->edit('flash_comprobantes_ingresos',$data,'id',$comprobante->id);

        //Servicios a modificar la cantidad
        
        foreach ($_POST as $key => $value) {
            if (substr($key, 0,18) == 'cantidad_modificar'){
                $cantidad_array = split('_', $key);
                $data = array('cantidad'=>$value);
                $this->codegen_model->edit('flash_comprobantes_ingresos_servicios',$data,'id',$cantidad_array[2]);
            }
        }
        $this->session->set_flashdata('registroOK', $comprobante->numero);

        redirect(base_url().'piezas/comprobantes_ingresos/modificar_comprobantes_index/'.$comprobante->id);
    }
    
    function borrar_servicio($comprobante_ingreso_id,$servicio_id){
            $piezas = $this->flash_md->getPiezasPorServiciosPorComprobanteSinGroup($comprobante_ingreso_id,$servicio_id);
            foreach ($piezas as $pieza) {
                $this->codegen_model->delete('flash_piezas','id',  $pieza->id);
            }
            $this->codegen_model->delete('flash_comprobantes_ingresos_servicios','id',$comprobante_ingreso_id." AND servicio_id = ".$servicio_id);
//            echo $this->db->last_query()."<br/>";die;
            redirect(base_url().'/piezas/comprobantes_ingresos/modificar_comprobantes_index/'.$comprobante_ingreso_id);
    }
    
    function liberar_comprobante(){
            $comprobante_ingreso_id = $this->input->post('liberar_comprobante_id');
            $comprobante_ingreso = Comprobante::whereId($comprobante_ingreso_id)->first();
            $comprobante_servicios = ComprobanteServicio::whereComprobanteIngresoId($comprobante_ingreso_id)->get();
            foreach ($comprobante_servicios as $comprobante_servicio) {
                $piezas = Pieza::whereComprobanteIngresoId($comprobante_ingreso_id)->whereServicioId($comprobante_servicio->id)->get();
                foreach ($piezas as $pieza) {
                    //echo "Borrra pieza ".$pieza->id." del servicio ".$comprobante_servicio->id."<br/>";
                    $this->codegen_model->delete('flash_piezas','id',  $pieza->id);
                    //echo $this->db->last_query()."<br/>";
                }
                //echo "Borra el servicio ".$comprobante_servicio->id." <br/>";
                $this->codegen_model->deleteWhere('flash_comprobantes_ingresos_servicios','comprobante_ingreso_id',$comprobante_ingreso_id,'id',$comprobante_servicio->id);
//               echo $this->db->last_query().'<br/>';
            }
            // Borro el Comprobante de la tabla flash_comprobantes_servicios
            $this->codegen_model->deleteWhere('flash_comprobantes_ingresos','id',$comprobante_ingreso_id);
            //Vuelvo el comprobante generado al estado Impreso
            //echo "Libera el comprobante generado ".$comprobante_ingreso->numero." <br/>";die;
            $data = array('estado'=>  ComprobanteGenerado::ESTADO_IMPRESO);
            $this->codegen_model->edit('flash_comprobantes_ingresos_generados',$data,'numero',$comprobante_ingreso->numero);
            redirect(base_url().'piezas/comprobantes_ingresos/modificar_comprobantes_comprobar');
    }
    
        function verificar($comprobante_ingreso_id){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'result' => $this->flash_md->getPiezas($comprobante_ingreso_id),
                        'comprobante_ingreso_id' => $comprobante_ingreso_id
		);

		$vista_externa = array(
			'title' => ucwords("clientes"),
			'contenido_main' => $this->load->view('components/piezas/comprobantes_ingresos/comprobantes_verificar', $vista_interna, true)
		);

		$this->load->view('template/view', $vista_externa);
    }
    
}

/* End of file comprobantes_ingresos.php */
/* Location: ./system/application/controllers/comprobantes_ingresos.php */