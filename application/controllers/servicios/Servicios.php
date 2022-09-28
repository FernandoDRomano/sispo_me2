<?php


class Servicios extends CI_Controller {

	private $permisos;

	function __construct() {
		parent::__construct();
		$this->permisos = $this->permisos_lib->control();
		$this->load->model('flash_md');
	}

	function index(){
            $this->load->helper(array('form','url'));
            $this->load->library('form_validation');
            $this->form_validation->set_rules('codigo','codigo',callback_codigo_check);
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'results' => array(),
			'grupos' => $this->codegen_model->get('flash_servicios_grupos', 'id, nombre', '')
		);

		$vista_externa = array(
			'title' => ucwords("servicios"),
			'contenido_main' => $this->load->view('components/servicios/servicios/servicios_list', $vista_interna, true)
		);
                if($this->form_validation->run() == FALSE){
                    $this->load->view('template/backend', $vista_externa);
                }  else {
                    $this->load->view('formsucces');
                }
		
	}
        
        public function codigo_check($str){
            if ($str == 'test'){
                $this->form_validation->set_message('codigo_check', 'The code exits');
                return false;
                
            }else{
                return true;
            }
        }
	function filtro(){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'results' => $this->flash_md->getServicios($this->input->post('grupo'), $this->input->post('buscar')),
			'grupos' => $this->codegen_model->get('flash_servicios_grupos', 'id, nombre', '')
		);

		$vista_externa = array(
			'title' => ucwords("servicios"),
			'contenido_main' => $this->load->view('components/servicios/servicios/servicios_list', $vista_interna, true)
		);

		$this->load->view('template/backend', $vista_externa);
	}

	function add(){
            
		if ($this->input->post('enviar_form')){
			$data = array(
					'grupo_id' => $this->input->post('grupo_id'),
					'nombre' => $this->input->post('nombre'),
					'precio' => $this->input->post('precio'),
					'acuse' => $this->input->post('acuse'),
					'activo' => $this->input->post('activo'),
					'web' => $this->input->post('web'),
					//'pieza' => $this->input->post('pieza'),
					'codigo' => $this->input->post('codigo'),
					'create' => date("Y-m-d H:i:s"),
				);
			$this->codegen_model->add('flash_servicios',$data);
			redirect(base_url().'servicios/servicios');
		}

		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'grupos' => $this->codegen_model->get('flash_servicios_grupos', 'id, nombre', '')
		);

		$vista_externa = array(
			'title' => ucwords("servicios"),
			'contenido_main' => $this->load->view('components/servicios/servicios/servicios_add', $vista_interna, true)
		);

		$this->load->view('template/backend', $vista_externa);
	}

	function edit($id){
		if ($this->input->post('enviar_form')){
			$data = array(
					'grupo_id' => $this->input->post('grupo_id'),
					'nombre' => $this->input->post('nombre'),
					'precio' => $this->input->post('precio'),
					'acuse' => $this->input->post('acuse'),
					'activo' => $this->input->post('activo'),
					'web' => $this->input->post('web'),
					//'pieza' => $this->input->post('pieza'),
                                        'codigo' => $this->input->post('codigo'),
					'update' => date('Y-m-d H:m:s')
				);
			$this->codegen_model->edit('flash_servicios',$data,'id',$this->input->post('id'));
			redirect(base_url().'servicios/servicios');
		}

		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'result' => $this->codegen_model->row('flash_servicios','*','id = '.$id),
			'grupos' => $this->codegen_model->get('flash_servicios_grupos', 'id, nombre', '')
		);

		$vista_externa = array(
			'title' => ucwords("servicios"),
			'contenido_main' => $this->load->view('components/servicios/servicios/servicios_edit', $vista_interna, true)
		);

		$this->load->view('template/backend', $vista_externa);
	}

	function view($id){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'result' => $this->codegen_model->row('flash_servicios','*','id = '.$id)
		);

		$vista_externa = array(
			'title' => ucwords("servicios"),
			'contenido_main' => $this->load->view('components/servicios/servicios/servicios_view', $vista_interna, true)
		);

		$this->load->view('template/fancybox', $vista_externa);
	}

	function json($id){
		$json = $this->codegen_model->row('flash_servicios','*','id = '.$id);
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}

	function json_all(){
		$json = $this->codegen_model->get('flash_servicios','*','');
		if($json) echo json_encode($json);
		else echo json_encode(array('status' => 'none'));
	}

	function delete($id){
		$this->codegen_model->delete('flash_servicios','id',$id);
	}
        
        function listado(){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'results' => Servicio::all(),
		);

		$vista_externa = array(
			'title' => ucwords("Listado de Servicios"),
			'contenido_main' => $this->load->view('components/servicios/servicios/servicios_listado', $vista_interna, true)
		);
                
                $this->load->view('template/backend', $vista_externa);
	}
        
        public function exportarAExcel(){
            $servicios = Servicio::all();
            //load our new PHPExcel library
            $this->load->library('excel');
            //activate worksheet number 1
            $this->excel->setActiveSheetIndex(0);
            //name the worksheet
            $this->excel->getActiveSheet()->setTitle('Listado de Servicios');
            //set cabeceras
            $this->excel->getActiveSheet()->setCellValue("A1",'Id');
            $this->excel->getActiveSheet()->setCellValue("B1",'Grupo');
            $this->excel->getActiveSheet()->setCellValue("C1",'Servicio');
            $this->excel->getActiveSheet()->setCellValue("D1",'Precio');
            
            // set cells values
            $this->load->library('simplehtmldom_1_5/simple_html_dom'); 
            $i = 2;
            foreach ($servicios as $value) {
                    // echo $trs->find('td',1)->innertext;
                    $this->excel->getActiveSheet()->setCellValue("A$i",$value->id);
                    $this->excel->getActiveSheet()->setCellValue("B$i",$value->servicioGrupo->nombre );
                    $this->excel->getActiveSheet()->setCellValue("C$i",$value->nombre);
                    $this->excel->getActiveSheet()->setCellValue("D$i",$value->precio);
                    $i++;
            }
            
            $this->excel->setActiveSheetIndex(0);
            $filename='Listado_de_servicios.xls'; //save our workbook as this file name

            header('Content-Type: application/vnd.ms-excel'); //mime type

            header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name

            header('Cache-Control: max-age=0'); //no cache

            //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
            //if you want to save it as .XLSX Excel 2007 format

            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5'); 

            //force user to download the Excel file without writing it to server's HD
            $objWriter->save('php://output');
    }
}

/* End of file servicios.php */
/* Location: ./system/application/controllers/servicios.php */