<?php

class MY_Input extends CI_Input
{
    public function is_post()
    {
        return $this->method(TRUE) == "POST" ? true : false;
    }

}

/* End of file MY_Input.php */
/* Location: ./system/application/core/MY_Input.php */