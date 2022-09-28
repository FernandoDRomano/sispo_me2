<?php

class Logs extends CI_Controller {

	private $permisos;

	function __construct() {
		parent::__construct();
		$this->permisos = $this->permisos_lib->control();

		$this->load->helper('directory');
		$this->load->helper('file');
		$this->load->library('zip');
		$this->load->helper('download');
	}

	function index(){
		$vista_interna = array(
			'permisos_efectivos' => $this->permisos,
			'logs' => $this->dir_map_sort(directory_map('./application/logs/backend/'))
		);

		$vista_externa = array(
			'title' => ucwords("logs"),
			'contenido_main' => $this->load->view('backend/logs/index', $vista_interna, true)
		);

		$this->load->view('template/backend', $vista_externa);
	}

	function txt($archivo){
		$cadena = read_file('./application/logs/backend/log-'.$archivo.'.php');
		$array = array("<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>" => '');
        $value = str_replace(array_keys($array), array_values($array), $cadena);
        $fecha = 'log-'.date('Y-m-d_h-m-s');
        $archivo_nombre = $fecha.'.txt';
		$nombre = $archivo_nombre;
		$this->zip->add_data($nombre, $value);
		$this->zip->archive('./uploads/zip/'.$fecha.'.zip');
		$this->zip->download($fecha.'.zip');
	}

	function zip(){
		$logs = $this->dir_map_sort(directory_map('./application/logs/backend/'));
		$desde = formatdate($this->input->post('fecha_desde'));
		$hasta = formatdate($this->input->post('fecha_hasta'));
		$archivo_descarga = 'log-'.date('Y-m-d_h-m-s');
		foreach ($logs as $f) {
			$array_breadcrumb = array('log-' => '', '.php' => '');
            $archivo = str_replace(array_keys($array_breadcrumb), array_values($array_breadcrumb), $f);

			if ($archivo >= $desde && $archivo <= $hasta) {
				$cadena = read_file('./application/logs/backend/log-'.$archivo.'.php');
				$array = array("<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>" => '');
				$value = str_replace(array_keys($array), array_values($array), $cadena);
        		$archivo_nombre = 'log-'.$archivo.'.txt';
				$this->zip->add_data($archivo_nombre, $value);
			}
		}

		$this->zip->archive('./uploads/zip/'.$archivo_descarga.'.zip');
		$this->zip->download($archivo_descarga.'.zip');

		redirect(base_url().'logs');
	}

	function dir_map_sort($array){
	    $items = array();
	    foreach ($array as $key => $val){
	        if (is_array($val)){
	            $items[$key] = (!empty($array)) ? dir_map_sort($val) : $val;
	        }else{
	            $items[$val] = $val;
	        }
	    }
	    ksort($items);
	    return $items;
	}

}

/* End of file categorias.php */
/* Location: ./system/application/controllers/logs.php */