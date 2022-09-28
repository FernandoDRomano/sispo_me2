<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class backend_lib{
	
	public function __construct(){
		
	}

	public function __get($var){
		return get_instance()->$var;
	}	

	public function getMenu($id_usuario){		
		$consulta_padres = $this->sudaca_md->getMenus($id_usuario);

		if ($consulta_padres) {
			foreach ($consulta_padres as $field) {
				$consulta_hijos = $this->sudaca_md->getSubmenus($id_usuario, $field->id);
				if ($consulta_hijos) {
					$submenus = '';
					$menu_activo = '';
					$menu_activo_collapse = '';
					foreach ($consulta_hijos as $result_dos) {
						if ($result_dos->parent == $field->id) {
							if ($this->uri->segment(1).'/'.$this->uri->segment(2) == $result_dos->link) {
								$submenus .= "<li class='active'><a href='". base_url().$result_dos->link."'><i class='".$result_dos->iconpath."'></i> ".$result_dos->descripcion."</a></li>";
								$menu_activo = 'active';
								$menu_activo_collapse = 'collapse in';
							}else{
								$submenus .= "<li><a href='". base_url().$result_dos->link."'><i class='".$result_dos->iconpath."'></i> ".$result_dos->descripcion."</a></li>";
							}
						}
					}
					
					echo "<li class='".$menu_activo."'>
						<a href='#'><i class='".$field->iconpath."'></i> <span class='nav-label'>".$field->descripcion."</span><span class='fa arrow'></span></a>
						<ul class='nav nav-second-level  ".$menu_activo_collapse."'>
						".$submenus."
						</ul>
					</li>";
				}else echo "<li><a href='".base_url().$field->link."'><i class='".$field->iconpath."'></i><span class='title'> ".$field->descripcion." </span><span class='selected'></span></a></li>";
			}
		}
	}

	public function log(){
		$level = 'INFO';
		$msj = $this->uri->uri_string().": fue accedida por el usuario ".$this->session->userdata('username');
  
  		$filepath = 'application/logs/backend/log-accesos-'.$this->session->userdata('username').'-'.date('Y-m-d').'.php';
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

	public function log_consultas($query){
		$level = 'INFO';
		$msj = 'Metodo: '.$this->uri->uri_string()." | Usuario: ".$this->session->userdata('username').' (ID: '.$this->session->userdata('user_id').') | Consulta: '.$query;
  
  		$filepath = 'application/logs/backend/log-consultas-'.$this->session->userdata('username').'-'.date('Y-m-d').'.php';
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

	public function imagen_upload($field_name, $carpeta){
		$this->load->library('upload');
		$this->load->library('image_lib');
		$config['upload_path'] = './uploads/'.$carpeta.'/'; 
		$config['file_name'] = md5(rand()).'.jpg';
		$config['overwrite'] = TRUE;
		$config["allowed_types"] = 'jpg|jpeg|png|gif';
		$this->upload->initialize($config);
		if (!$this->upload->do_upload($field_name)){
		    $error = array('error' => $this->upload->display_errors());
		}
		$config['image_library'] = 'gd2';
	    $config['source_image'] = $config['upload_path'].$config['file_name'];
	    $config['create_thumb'] = FALSE;
	    $config['maintain_ratio'] = TRUE;
	    $config['width']  = 800;
	    $config['height'] = 600;
	    $this->image_lib->clear();
	    $this->image_lib->initialize($config);
	    $this->image_lib->resize();

	    return $config['file_name'];
	}

	public function imagen_resize($path, $carpeta, $imagen, $width, $height){
		$this->load->library('image_lib');
		$config['image_library'] = 'gd2';
	    $config['source_image'] = $path.$imagen;
	    $config['create_thumb'] = FALSE;
	    $config['maintain_ratio'] = TRUE;
	    $config['width']  = $width;
	    $config['height'] = $height;
	    $config['new_image'] = $path.$carpeta.$imagen;
	    $this->image_lib->clear();
	    $this->image_lib->initialize($config);
	    $this->image_lib->resize();
	}

	public function enviarEmail($message, $email){		
            $adminEmail = 'info@sispo.com.ar';
            $headers    = "MIME-Version: 1.0\r\nFrom: $adminEmail\r\nReply-To: $adminEmail\r\nContent-Type: text/html; charset=utf-8";
            $message    = wordwrap($message, 120);
            $message    = str_replace("\n.", "\n..", $message);
            return mail($email, '=?UTF-8?B?' . base64_encode($subject) . '?=', $message, $headers);
		
	}
	
}