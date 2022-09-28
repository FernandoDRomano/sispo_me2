<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class frontend_lib{
	
	public function __construct(){
		
	}

	public function __get($var){
		return get_instance()->$var;
	}	

	public function configuraciones($titulo, $metadata){
		$titulos = $this->codegen_model->row('configuraciones','*','id = 1');
		$meta = $this->codegen_model->row('configuraciones','*','id = 2');
		$contacto = $this->codegen_model->row('configuraciones','*','id = 3');
		$descripcion = $this->codegen_model->row('configuraciones','*','id = 13');
		$direccion = $this->codegen_model->row('configuraciones','*','id = 7');
		$facebook = $this->codegen_model->row('configuraciones','*','id = 10');
		$twitter = $this->codegen_model->row('configuraciones','*','id = 11');
		$google_plus = $this->codegen_model->row('configuraciones','*','id = 12');
		
		$configuraciones = array(
			'title' => $titulos->valor." - ".$titulo,
			'nombre_sitio' => $titulos->valor,
			'email_contacto' => $contacto->valor,
			'copyright' => '2015',
			'descripcion' => $descripcion->valor,
			'autor' => 'marcelo floriani',
			'keywords' => $meta->valor.' '.$metadata,
			'direccion' => $direccion->valor,
			'facebook' => $facebook->valor,
			'twitter' => $twitter->valor,
			'google_plus' => $google_plus->valor
		);
		return $configuraciones;
	}

	public function loadIdioma(){
		if ($this->session->userdata('idioma')) {
			return $this->session->userdata('idioma');
		}else{
			$sesion_data = array('idioma' => 'spanish');
            $this->session->set_userdata($sesion_data);
            return $this->session->userdata('idioma');
		}
	}

	public function validarSession(){
		if (!$this->session->userdata('cliente_id')){
			redirect(base_url().'registrarse');
		}else{
			$this->log('read', '');
		}
	}

	public function log(){
		$level = 'INFO';
		$msj = $this->uri->uri_string().": fue accedida por el usuario ".$this->session->userdata('cliente_usuario');
  
  		$filepath = 'application/logs/frontend/log-'.date('Y-m-d').'.php';
  		$message  = '';

  		if ( ! file_exists($filepath)){
   			$message .= "<"."?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?".">\n\n";
  		}

  		if ( ! $fp = @fopen($filepath, FOPEN_WRITE_CREATE) ){
   			return FALSE;
  		}

		$message .= $level.' '.(($level == 'INFO') ? ' -' : '-').' '.date('Y-m-d H:i:s'). ' --> '.$msj."\n";

  		flock($fp, LOCK_EX);
  		fwrite($fp, $message);
  		flock($fp, LOCK_UN);
  		fclose($fp);

  		@chmod($filepath, FILE_WRITE_MODE);
	}

	public function enviarEmail($data, $vista, $titulo, $email_destino, $email_origen, $remitente){
		$datos['dato'] = $data;
		$mensaje = $this->load->view($vista, $datos, true);
		$this->load->library('email');
		$config['mailtype'] = 'html';
		$this->email->initialize($config);		
		$this->email->from($email_origen, $remitente);
		$this->email->to($email_destino);
		$this->email->subject($titulo);
		$this->email->message($mensaje);
		$this->email->send();	
	}

}