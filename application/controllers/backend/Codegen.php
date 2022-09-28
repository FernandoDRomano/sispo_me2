<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Codegen extends CI_Controller {


    function index(){
        $data = '';
        $this->load->library('form_validation');
		$this->load->database();
		$this->load->helper('url');
        $this->load->helper('file');

        if ($this->input->post('table_data') || !$_POST){
            $this->form_validation->set_rules('table', 'Table', 'required');
            if ($this->form_validation->run()){
                $table = $this->db->list_tables();
                $data['table'] = $table[$this->input->post('table')];
                $result = $this->db->query("SHOW FIELDS from " . $data['table']);
                $data['alias'] = $result->result();
            }

            $datos_plantilla["title"] = ucwords("codegen");
            $datos_plantilla["contenido_main"] = $this->load->view('backend/generador/codegen', $data, true);            
            $this->load->view('template/backend', $datos_plantilla);

        }else if ($this->input->post('generate')){
            if(!file_exists('./application/views/'.$this->input->post('carpeta').'/'.$this->input->post('view'))){
                if(!mkdir('./application/views/'.$this->input->post('carpeta').'/'.$this->input->post('view'), 0777)){
                    die('Fallo al crear las carpetas...');
                }
            }
                
            $all_files = array(
                'application/config/form_validation.php',
                'application/controllers/'.ucfirst($this->input->post('controller')).'.php',
                'application/models/codegen_model.php',
                'application/views/'.$this->input->post('carpeta').'/'.$this->input->post('view').'/'.$this->input->post('view').'_add.php',
                'application/views/'.$this->input->post('carpeta').'/'.$this->input->post('view').'/'.$this->input->post('view').'_edit.php',
                'application/views/'.$this->input->post('carpeta').'/'.$this->input->post('view').'/'.$this->input->post('view').'_list.php',
                'application/views/'.$this->input->post('carpeta').'/'.$this->input->post('view').'/'.$this->input->post('view').'_view.php'
            );

            $err = 0;

            $rules = $this->input->post('rules');
            $label = $this->input->post('field');
            $type = $this->input->post('type');
                
            foreach($label as $k => $v){
                if($k != 'id'){
                    $grilla_head[] = '<th><a href="#">'.$v.'</a></th>';
                    $grilla_body[] = '<td><?php echo $result->'.$k.' ?></td>';
                }

                if($type[$k][0] != 'exclude'){
                    $labels[] = $v;
                    $form_fields[] = $k;
                }

                if($rules[$k][0] == 'required'){
                    $required = '<span class="required">*</span>';        
                    $required_attr = 'required';
                }else{
                    $required = '';
                    $required_attr = '';
                }

                if($type[$k][0] != 'exclude'){
                    //(tipo_campo, campo, label, required, required_attr);
                    $add_form[] = $this->input_add($type[$k][0], $k, $v, $required, $required_attr);
                    $edit_form[] = $this->input_edit($type[$k][0], $k, $v, $required, $required_attr);
                    $view_form[] = $this->input_view($type[$k][0], $k, $v, $required, $required_attr);
                    $controller_form_data[] = "'".$k."' => \$this->input->post('".$k."')";
                    $controller_form_editdata[] = "'".$k."' => \$this->input->post('".$k."')";
                    $fields_list[] = $k;
                }
            }

            $fields_list[] = $this->input->post('primaryKey');
            $fields = implode(',',$fields_list);

            ////////////////////
            // carpetas
            ////////////////////
            $c_path = 'application/controllers/';
            $m_path = 'application/models/'; 
            $v_path = 'application/views/'.$this->input->post('carpeta').'/'.$this->input->post('view').'/';                              

            ////////////////////
            // controller
            ////////////////////
            $controller = file_get_contents('application/views/backend/generador/controller.php');
            $search = array('{controller_name}', '{view}', '{table}','{validation_name}','{data}','{edit_data}','{controller_name_l}','{primaryKey}','{fields_list}','{carpeta}');
            $replace = array(
                ucfirst($this->input->post('controller')), 
                $this->input->post('view'),
                $this->input->post('table'),
                $this->input->post('validation'),
                implode(','."\n\t\t\t\t\t",$controller_form_data),
                implode(','."\n\t\t\t\t\t",$controller_form_editdata),
                $this->input->post('controller'),
                $this->input->post('primaryKey'),
                $fields,
                $this->input->post('carpeta').'/'.$this->input->post('view')
            );
            $c_content = str_replace($search, $replace, $controller);
            $file_controller = $c_path.ucfirst($this->input->post('controller')).'.php';
            
            ////////////////////
            // view
            ////////////////////
            $list_v = file_get_contents('application/views/backend/generador/list.php');
            $list_search = array('{controller_name_l}', '{grilla_head}','{grilla_body}');
            $list_replace = array($this->input->post('controller'), implode("\n",$grilla_head),implode("\n",$grilla_body));
            $list_content = str_replace($list_search,$list_replace,$list_v);
                
            $add_v = file_get_contents('application/views/backend/generador/add.php');                
            $add_content = str_replace('{forms_inputs}',implode("\n",$add_form),$add_v);
                
            $edit_v = file_get_contents('application/views/backend/generador/edit.php');
            $edit_search = array('{forms_inputs}','{primary}');
            $edit_replace = array(implode("\n",$edit_form),'<?php echo form_hidden(\''.$this->input->post('primaryKey').'\',$result->'.$this->input->post('primaryKey').') ?>');                
            $edit_content = str_replace($edit_search,$edit_replace,$edit_v);

            $view_v = file_get_contents('application/views/backend/generador/view.php');
            $view_search = array('{forms_inputs}','{primary}');
            $view_replace = array(implode("\n",$view_form),'<?php echo form_hidden(\''.$this->input->post('primaryKey').'\',$result->'.$this->input->post('primaryKey').') ?>');
            $view_content = str_replace($view_search,$view_replace,$view_v);
                
            $write_files = array(
                'Controller' => array($file_controller, $c_content),
                'view_edit'  => array($v_path.$this->input->post('view').'_edit.php', $edit_content),
                'view_list'  => array($v_path.$this->input->post('view').'_list.php', $list_content),
                'view_add'  => array($v_path.$this->input->post('view').'_add.php', $add_content),
                'view_view'  => array($v_path.$this->input->post('view').'_view.php', $view_content)
            );
            
            foreach($write_files as $wf){
                if($this->writefile($wf[0],$wf[1])){
                    $err++;
                    echo $this->writefile($wf[0],$wf[1]);
                }
            }
                                                    
            if($err >0){
                exit;
            }else{
                $data['list_content'] = $list_content;
                $data['add_content'] = $add_content;
                $data['edit_content'] = $edit_content;
                $data['controller'] = $c_content;
                $data['view_content'] = $view_content;            
            } 
            
            $datos_plantilla["title"] = ucwords("codegen");
            $datos_plantilla["contenido_main"] = $this->load->view('backend/generador/done', $data, true);
            $this->load->view('template/backend', $datos_plantilla);  
        }
    }
    
    function fexist($path){
        if (file_exists($path)){
            return $path.' - File exists <br>';                    
        }else{
            return false;
        }        
    }
    
    function writefile($file,$content){        
        if (!write_file($file, $content)){
            return $file. ' - Unable to write the file';
        } else{
            return false;
        }
    }

    function input_add($tipo_campo, $campo, $label, $required, $required_attr){
        switch ($tipo_campo) {
            case 'textarea':
                $input = '
                        <div class="form-group">
                            <label for="'.$campo.'">'.$label.$required.'</label>
                            <textarea '.$required_attr.' id="'.$campo.'" name="'.$campo.'" class="summernote form-control" placeholder="'.$label.'"></textarea>
                        </div>';
                break;

            case 'dropdown':
                $input = '
                        <div class="form-group">
                            <label for="'.$campo.'">'.$label.$required.'</label>
                            <select id="'.$campo.'" name="'.$campo.'" class="form-control chosen-select" '.$required_attr.'>
                                <?php foreach ($'.$campo.' as $f) { ?>
                                    <option value="<?php echo $f->id ?>"><?php echo $f->id ?></option>
                                <?php } ?>
                            </select>
                        </div>';
                break;

            case 'radio':
                $input = '
                        <div class="form-group">
                            <label for="'.$campo.'">'.$label.$required.'</label>
                            <div class="radio i-checks"><label> <input type="'.$tipo_campo.'" name="'.$campo.'" id="'.$campo.'" class="form-control" value="1" checked> Si</label></div>
                            <div class="radio i-checks"><label> <input type="'.$tipo_campo.'" name="'.$campo.'" id="'.$campo.'" class="form-control" value="0" > No</label></div>
                        </div>';
                break;

            case 'checkbox':
                $input = '
                        <div class="form-group">
                            <label for="'.$campo.'">'.$label.$required.'</label>
                            <div class="checkbox i-checks"><label> <input id="'.$campo.'" name="'.$campo.'" type="'.$tipo_campo.'"  class="form-control" value="1" checked> Si</label></div>
                            <div class="checkbox i-checks"><label> <input id="'.$campo.'" name="'.$campo.'" type="'.$tipo_campo.'"  class="form-control" value="0"> No</label></div>
                        </div>';
                break;

            case 'file':
                $input = '
                        <div class="form-group">
                            <label for="'.$campo.'">'.$label.$required.'</label>
                            <input '.$required_attr.' id="'.$campo.'" name="'.$campo.'" type="'.$tipo_campo.'" class="form-control" />
                        </div>';
                break;

            default:
                $input = '
                        <div class="form-group">
                            <label for="'.$campo.'">'.$label.$required.'</label>
                            <input '.$required_attr.' id="'.$campo.'" name="'.$campo.'" type="'.$tipo_campo.'" class="form-control" placeholder="'.$label.'" />
                        </div>';
                break;
        }

        return $input;
    }

    function input_edit($tipo_campo, $campo, $label, $required, $required_attr){
        switch ($tipo_campo) {
            case 'textarea':
                $input = '
                        <div class="form-group">
                            <label for="'.$campo.'">'.$label.$required.'</label>
                            <textarea '.$required_attr.' id="'.$campo.'" name="'.$campo.'" class="summernote form-control" placeholder="'.$label.'"><?php echo $result->'.$campo.' ?></textarea>
                        </div>';
                break;

            case 'dropdown':
                $input = '
                        <div class="form-group">
                            <label for="'.$campo.'">'.$label.$required.'</label>
                            <select id="'.$campo.'" name="'.$campo.'" class="form-control chosen-select" '.$required_attr.'>
                                <?php foreach ($'.$campo.' as $f) { ?>
                                    <option value="<?php echo $f->id ?>" <?php if($f->id == $result->'.$campo.') echo "selected" ?>><?php echo $f->id ?></option>
                                <?php } ?>
                            </select>
                        </div>';
                break;

            case 'radio':
                $input = '
                        <div class="form-group">
                            <label for="'.$campo.'">'.$label.$required.'</label>
                            <div class="radio i-checks"><label> <input type="'.$tipo_campo.'" name="'.$campo.'" id="'.$campo.'" class="form-control" value="1" <?php if($f->campo == 1) echo "checked" ?>> Si</label></div>
                            <div class="radio i-checks"><label> <input type="'.$tipo_campo.'" name="'.$campo.'" id="'.$campo.'" class="form-control" value="0" <?php if($f->campo == 0) echo "checked" ?>> No</label></div>
                        </div>';
                break;

            case 'checkbox':
                $input = '
                        <div class="form-group">
                            <label for="'.$campo.'">'.$label.$required.'</label>
                            <div class="checkbox i-checks"><label> <input id="'.$campo.'" name="'.$campo.'" type="'.$tipo_campo.'"  class="form-control" value="1" <?php if($f->campo == 1) echo "checked" ?>> Si</label></div>
                            <div class="checkbox i-checks"><label> <input id="'.$campo.'" name="'.$campo.'" type="'.$tipo_campo.'"  class="form-control" value="0" <?php if($f->campo == 0) echo "checked" ?>> No</label></div>
                        </div>';
                break;

            case 'file':
                $input = '
                        <div class="form-group">
                            <label for="'.$campo.'">'.$label.'</label>
                            <input id="'.$campo.'" name="'.$campo.'" type="'.$tipo_campo.'" class="form-control" />
                        </div>';
                break;

            default:
                $input = '
                        <div class="form-group">
                            <label for="'.$campo.'">'.$label.$required.'</label>
                            <input '.$required_attr.' id="'.$campo.'" name="'.$campo.'" type="'.$tipo_campo.'" class="form-control" placeholder="'.$label.'" value="<?php echo $result->'.$campo.' ?>" />
                        </div>';
                break;
        }

        return $input;
    }

    function input_view($tipo_campo, $campo, $label, $required, $required_attr){
        $input = '
                <div class="form-group">
                    <b>'.$label.':</b> <?php echo $result->'.$campo.' ?>
                </div>';

        return $input;
    }
}

/* End of file codegen.php */
/* Location: ./application/controllers/codegen.php */