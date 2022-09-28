<?php


class Piezas_talonarios extends CI_Controller {

	private $permisos;
	
	function __construct() {
		parent::__construct();
		$this->permisos = $this->permisos_lib->control();
		$this->load->model('flash_md');
	}	
	
	function index(){
                $sucursal = null;
                $user_row = $this->ion_auth->user()->row();
                if (!$this->ion_auth->in_group(array(1, 2), $user_row->id)) $sucursal = $user_row->sucursal_id;
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'results' => $this->flash_md->getTalonarios(NULL)//$sucursal
		);

		$vista_externa = array(			
			'title' => ucwords("talonarios"),
			'contenido_main' => $this->load->view('components/piezas/piezas_talonarios/piezas_talonarios_list', $vista_interna, true)
		);		
		
		$this->load->view('template/backend', $vista_externa);
	}

	function add(){
		if ($this->input->post('enviar_form')){
			if ($this->input->post('etiquetas_total') > 0) {
				$data = array(
					'responsable_id' => $this->input->post('responsable_id'),
					'etiquetas_total' => $this->input->post('etiquetas_total'),
					'etiquetas_disponibles' => $this->input->post('etiquetas_total'),
					'create' => date("Y-m-d H:i:s"),
					'update' => date("Y-m-d H:i:s"),
				);
				$talonario = $this->codegen_model->add('flash_piezas_talonarios',$data);

				for ($i=1; $i <= $this->input->post('etiquetas_total') ; $i++) { 
					$data = array('talonario_id' => $talonario, 'create' => date("Y-m-d H:i:s"), 'update' => date("Y-m-d H:i:s"),);
					$comprobante = $this->codegen_model->add('flash_comprobantes_ingresos_generados',$data);

					$data = array('numero' => sprintf("%06d", $comprobante).sprintf("%06d", rand(100000, 999999)));
					$this->codegen_model->edit('flash_comprobantes_ingresos_generados',$data,'id',$comprobante);
				}
			}
			redirect(base_url().'piezas/piezas_talonarios');
		}		  
   
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'responsables' => $this->codegen_model->get('flash_piezas_talonarios_responsables', '*', '')
		);

		$vista_externa = array(			
			'title' => ucwords("talonarios"),
			'contenido_main' => $this->load->view('components/piezas/piezas_talonarios/piezas_talonarios_add', $vista_interna, true)
		);		  
   
		$this->load->view('template/backend', $vista_externa);
	}	

	function edit($id){
		if ($this->input->post('enviar_form')){
			$data = array(
					'responsable_id' => $this->input->post('responsable_id')
				);
			$this->codegen_model->edit('flash_piezas_talonarios',$data,'id',$this->input->post('id'));
			redirect(base_url().'piezas/piezas_talonarios');
		}

		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'result' => $this->codegen_model->row('flash_piezas_talonarios','*','id = '.$id),
			'responsables' => $this->codegen_model->get('flash_piezas_talonarios_responsables', '*', '')
		);

		$vista_externa = array(			
			'title' => ucwords("talonarios"),
			'contenido_main' => $this->load->view('components/piezas/piezas_talonarios/piezas_talonarios_edit', $vista_interna, true)
		);		  
   
		$this->load->view('template/backend', $vista_externa);
	}

	function view($id){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'result' => $this->codegen_model->row('flash_piezas_talonarios','*','id = '.$id)
		);

		$vista_externa = array(			
			'title' => ucwords("talonarios"),
			'contenido_main' => $this->load->view('components/piezas/piezas_talonarios/piezas_talonarios_view', $vista_interna, true)
		);		  
   
		$this->load->view('template/fancybox', $vista_externa);
	}

	function json($id){
		$json = $this->codegen_model->row('flash_piezas_talonarios','*','id = '.$id);
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}

	function json_all(){
		$json = $this->codegen_model->get('flash_piezas_talonarios','*','');
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}
	
	function delete($id){
		$this->codegen_model->delete('flash_piezas_talonarios','id',$id);             
	}
        
        public function exportarAPDF($talonario_id)
        {
            $this->load->library('simplehtmldom_1_5/simple_html_dom'); 
            $this->load->library('Pdf_noheader_nofooter');  
            
            $comprobantes = $this->flash_md->getComprobantesGenerados($talonario_id);
            foreach ($comprobantes as $value) {
                $data = array(
                        'estado' => ComprobanteGenerado::ESTADO_IMPRESO
                );
                $this->codegen_model->edit('flash_comprobantes_ingresos_generados',$data,'id',$value->id);
            }
            //Set custom header data
            $custom_layout = array(23, 40);
            $pdf = new Pdf_noheader_nofooter('L', 'mm', $custom_layout, true, 'UTF-8', false); //: portrait, L:landscape

            $pdf->SetTitle('Talonario de comprobantes'); 
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
                //$style = '{font-size:5px}';
                $style = array(
                        'border'=>false,
                        //'position' => 'absolute',
//                        'align' => 'C',
//                        'stretch' => false,
                        'fitwidth' => true,
//                        'cellfitalign' => '',
//                        'border' => false,
                        //'hpadding' => '10',
                        //'vpadding' => '20',
//                        'fgcolor' => array(0,0,0),
//                        'bgcolor' => array(255,255,255),
                        'text' => false,
                       // 'font' => 'helvetica',
                        'fontsize' => 12,
                        'stretchtext' => 4
                        );
                foreach($comprobantes as $value){
                    $pdf->AddPage('L', $custom_layout, false, false);
//                    $y = 5;
//                    $x = $pdf->GetX();
//                    $y = $pdf->GetY();
                   // $pdf->write1DBarcode(4455, 'C39', 5, 5, 105, 5, 0.1, '', 'M');
                    //$pdf->write1DBarcode($value->numero, 'C39', 3, $ypos+5, 200, 5, 0.15, '', 'M');//277
                    //$pdf->write1DBarcode($value->numero, 'C39', 3, $ypos+5, '', 7, 0.15, '', 'M');//278
                    //$pdf->write1DBarcode($value->numero, 'C39', 3, $ypos+5, 140, 8, 0.1, '', 'M');//282
                   // $pdf->write1DBarcode(30, 'C39', 3, $ypos+5, 190, 5, 0.16, $style, 'M');
                  // $pdf->write1DBarcode($value->numero, 'C39', 3, $ypos+10, 190, 5, 0.15, '', 'M');//LEE
                  //  $pdf->write1DBarcode(15, 'C39', $xpos, $ypos, 50, 5, 0.4, $style, 'S');
                // The width is set to the the same as the cell containing the name.  
                // The Y position is also adjusted slightly.
                //$pdf->write1DBarcode($value->numero, 'C39', $xpos, $ypos, 105, 5, 0.4, $style, 'M');
                //Reset X,Y so wrapping cell wraps around the barcode's cell.
                //$pdf->SetXY($x,$y);
    //            $pdf->Cell(105, 51, $c->nome, 1, 0, 'C', FALSE, '', 0, FALSE, 'C', 'B');
                   // $params = $pdf->serializeTCPDFtagParameters(array(10, 'C39', '', '', 40, 10, 0.2, array()));
//                    $xpos = 0;
//                    $ypos = 50;
                    $pdf->SetX(2);
                    $pdf->SetY(3);
                    $table_contenido  = '<table height="40px" border="" align="center" padding="0">
                                            <tr>
                                                <td style="font-family:3of9barcode;font-size:12px;">*'.$value->numero.'*</td>
                                            </tr>
                                            <tr>
                                                <td style="font-size:12px;">'.$value->numero.'</td>
                                            </tr>
                                            <tr>
                                                <td style="font-size:8px;text-align:center;">www.correoflash.com</td>
                                            </tr>
                                            <tr>
                                                <td style="font-size:7px;text-align:center;line-height:15px;">('.trim($value->talonario_id).')</td>
                                            </tr>
                                    </table>';      
                   $pdf->writeHTML($table_contenido, true, false, true, false, '');
            }

//                echo $html.$table_contenido.$cierre_table;die;

                $pdf->Output('talonario.pdf', 'D');
        }
}

/* End of file piezas_talonarios.php */
/* Location: ./system/application/controllers/piezas_talonarios.php */