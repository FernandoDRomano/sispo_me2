<?php

class MY_Controller extends CI_Controller {

    protected $permisos;
    protected $issa;
    protected $usuario;

    protected $data;
    protected $title;
    protected $view;
    protected $template;

    public function __construct()
    {
        parent::__construct();
        $this->data = new stdClass;
        $this->permisos = $this->permisos_lib->control();
        $this->issa = $this->permisos_lib->isSuperAdmin();
        $this->usuario = Usuario::find($this->ion_auth->user()->row()->id);
        $this->load->usuario = $this->usuario;
    }

    public function is_admin()
    {
        return $this->ion_auth->in_group(array(1, 2), $this->usuario->id);
    }

    public function is_user()
    {
        return !$this->is_admin();
    }
    
    public function is_acceso_sucursales()
	{
		return $this->ion_auth->in_group(28, $this->usuario->id);
	}


    protected function data($var, $value = null)
    {
        if(is_array($var))
        {
            foreach ($var as $name => $value)
            {
                $this->data->$name = $value;
            }
        }
        else
        {
            $this->data->$var = $value;
        }
    }

    protected function title($title)
    {
        $this->title = $title;
    }

    protected function view($view)
    {
        $this->view = $view;
    }

    protected function template($template, $render = true)
    {
        $this->template = $template;

        if($render)
        {
            $this->render();
        }
    }

    protected function render()
    {
        $this->data->permisos_efectivos = $this->permisos_lib->control();

        $this->load->view($this->template, [
            'title' => ucwords($this->title),
            'contenido_main' => $this->load->view($this->view, $this->data, true)
        ]);
    }

    protected function json()
    {
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($this->data));
    }
}

function print_e($var)
{
    echo "<pre>";
    print_r($var);
    exit("</pre>");
}

/* End of file MY_Controller.php */
/* Location: ./system/application/core/MY_Controller.php */