<?php

class Users extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
    }

    function index()
    {
        //$this->data->usuarios = Usuario::all();
        $this->data->usuarios = $this->flash_md->getUsers();
        $this->title('Usuarios');
        $this->view = 'backend/users/users_list';
        $this->template('template/backend');
    }

    function add(){
        if ($this->input->post('enviar_form')){
            $username = $this->input->post('username');
            $email    = $this->input->post('email');
            $password = $this->input->post('password');

            if (!empty($_FILES['foto']['tmp_name'])) {
                $img = $this->backend_lib->imagen_upload('foto', 'users');
            }else{
                $img = '';
            }
            
            $additional_data = array(
                'sucursal_id' => $this->input->post('sucursal_id'),
                'nombre' => ucwords(strtolower($this->input->post('nombre'))),
                'apellido' => ucwords(strtolower($this->input->post('apellido'))),
                'telefono'  => $this->input->post('telefono'),
                'celular' => $this->input->post('celular'),
                'foto' => $img,
                'active' => $this->input->post('active'),
                'idioma' => 'spanish',
            );
            $group_us = $this->input->post('group');
            $group = array($group_us);
            $this->ion_auth->register($username, $password, $email, $additional_data, $group);
            redirect(base_url()."backend/users");
        }

        $vista_interna = array(
            'permisos_efectivos' => $this->permisos,
            'issa' => $this->issa,
            'grupos' => $this->sudaca_backend_md->groups_getAll(),
            'sucursales' => $this->codegen_model->get('flash_sucursales', 'id, nombre', '')
        );

        $vista_externa = array(
            'title' => ucwords("users"),
            'contenido_main' => $this->load->view('backend/users/users_add', $vista_interna, true)
        );

        $this->load->view('template/backend', $vista_externa);
    }

    function edit($id){
        if ($this->input->post('enviar_form')){
            $id = $this->input->post('id');
            $user = $this->ion_auth->user($id)->row();

            if (!empty($_FILES['foto']['tmp_name'])) {
                $img = $this->backend_lib->imagen_upload('foto', 'users');
            }else{
                $foto = $this->codegen_model->row('users','*','id = '.$user->id);
                $img = $foto->foto;
            }

            $data = array(
                'sucursal_id' => $this->input->post('sucursal_id'),
                'nombre' => ucwords(strtolower($this->input->post('nombre'))),
                'apellido' => ucwords(strtolower($this->input->post('apellido'))),
                'telefono'  => $this->input->post('telefono'),
                'celular' => $this->input->post('celular'),
                'foto' => $img,
                'active' => $this->input->post('active'),
                'idioma' => 'spanish'
            );

            $this->ion_auth->update($user->id, $data);
            
            if(!is_null($this->input->post('group'))){
                $this->ion_auth->remove_from_group(NULL, $user->id);
                $this->ion_auth->add_to_group($this->input->post('group'), $user->id);
            }

            redirect(base_url()."backend/users", 'refresh');
        }

        $vista_interna = array(
            'permisos_efectivos' => $this->permisos,
            'issa' => $this->issa,
            'grupos' => $this->sudaca_backend_md->groups_getAll(),
            'grupo' => $this->codegen_model->row('users_groups','*','user_id = '.$id),
            'result' => $this->codegen_model->row('users','*','id = '.$id),
            'sucursales' => $this->codegen_model->get('flash_sucursales', 'id, nombre', '')
        );

        $vista_externa = array(
            'title' => ucwords("users"),
            'contenido_main' => $this->load->view('backend/users/users_edit', $vista_interna, true)
        );

        $this->load->view('template/backend', $vista_externa);
    }

    function view($id){
        $vista_interna = array(
            'permisos_efectivos' => $this->permisos,
            'result' => $this->codegen_model->row('users','*','id = '.$id)
        );

        $vista_externa = array(
            'title' => ucwords("users"),
            'contenido_main' => $this->load->view('backend/users/users_view', $vista_interna, true)
        );

        $this->load->view('template/view', $vista_externa);
    }

    function delete($ID){
        $user = $this->ion_auth->user($ID)->row();
        $data = array(
                'active' => 0,
            );

        $this->ion_auth->update($user->id, $data);
        //$this->codegen_model->delete('users','id',$ID);
        redirect(base_url().'backend/users/');
    }

    function validarUsuario(){
        if (!$this->ion_auth->username_check($this->input->post('username'))){
            echo "0";
        }
        else echo "1";
    }

    function validarEmail(){
        if (!$this->ion_auth->email_check($this->input->post('email'))){
            echo "0";
        }
        else echo "1";
    }
    
    function resetPassword(){
        $user_row = $this->ion_auth->user()->row();
        $usuario = $this->input->post('reset_user_name');
        $usuario_id = $this->input->post('reset_id');
        if (!$this->ion_auth->in_group(array(1, 2), $user_row->id)){
            echo "0";
        } else {
            if ($this->ion_auth->reset_password($usuario,$this->input->post('reset_new_password'))) {
                $vista_interna = array();
                $vista_externa = array(
                    'title' => ucwords("users"),
                    'contenido_main' => $this->load->view('backend/users/users_view', $vista_interna, true),
                );
                $this->session->set_flashdata('respuesta',$usuario);
                $this->codegen_model->delete('login_attempts_errors','login',$usuario);

                $email = $this->codegen_model->row('users','*','id = ' . $usuario_id)->email;                
                $mensaje = 'Su contraseña fue reinicializada. El administrador se comunicara con ud. Por favor, cambie su contraseña.';
                $this->backend_lib->enviarEmail($mensaje, $email);
                
                redirect(base_url()."backend/users/edit/".$usuario_id, 'refresh');
            } else {
                $vista_externa = array(
                    'title' => ucwords("users"),
                    'contenido_main' => $this->load->view('backend/users/users_view', $vista_interna, true),
                );
                $this->session->set_flashdata('noOk',$usuario);
                redirect(base_url()."backend/users/edit/".$usuario_id, 'refresh');
            }
        }
    }
}

/* End of file users.php */
/* Location: ./system/application/controllers/users.php */