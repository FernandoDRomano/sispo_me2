<?php

class MY_Loader extends CI_Loader
{
    public $usuario;

    public function is_admin()
    {
        return $this->ion_auth->in_group(array(1, 2), $this->usuario->id);
    }

    public function is_user()
    {
        return !$this->is_admin();
    }

}

/* End of file MY_Loader.php */
/* Location: ./system/application/core/MY_Loader.php */