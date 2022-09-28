<?php

class Userslog extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        // load Pagination library
        $this->load->library('pagination');

        // load URL helper
        $this->load->helper('url');
    }

    public function index()
    {
        $vista_interna = null;
        if (!is_null($this->input->post('username'))) {
            $this->session->set_userdata('filtroUsersLogUserName', $this->input->post('username'));
            $username             = $this->input->post('username');
        } else {
            $username = $this->session->userdata('filtroUsersLogUserName');
        }
        if (!is_null($this->input->post('categoria'))) {
            $this->session->set_userdata('filtroUsersLogCategoria', $this->input->post('categoria'));
            $categoria             = $this->input->post('categoria');
        } else {
            $categoria = $this->session->userdata('filtroUsersLogCategoria');
        }
        if (!is_null($this->input->post('descripcion'))) {
            $this->session->set_userdata('filtroUsersLogDescripcion', $this->input->post('descripcion'));
            $descripcion             = $this->input->post('descripcion');
        } else {
            $descripcion = $this->session->userdata('filtroUsersLogDescripcion');
        }
        if (!is_null($this->input->post('procesar'))) {
            $this->session->set_userdata('filtroUsersLogProcesar', $this->input->post('procesar'));
            $procesar = $this->input->post('procesar');
        } else {
            $procesar = $this->session->userdata('filtroUsersLogProcesar');
        }

        if ($procesar != '1') {
            $vista_interna["time_total"] = "Proceso aún no realizado. Faltan aplicar filtros.";
            $vista_interna["results"] = null;
        } else {
            // load db and model
            $this->load->database();
            $time_start = microtime(true);
            // init params
            $params = array();
            $limit_per_page = 50;
            $start_index = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

            $total_records = $this->flash_md->getUserslogCount($username, $categoria, $descripcion);

            if ($total_records > 0) {
                // get current page records
                $vista_interna["results"] =  $this->flash_md->getUserslogAll($username, $categoria, $descripcion, $limit_per_page, $start_index);

                $config['base_url'] = base_url() . 'backend/userslog/index';
                $config['total_rows'] = $total_records;
                $config['per_page'] = $limit_per_page;
                $config["uri_segment"] = 4;
                $config['num_links'] = 20;
                $config['page_query_string'] = FALSE;
                $config['full_tag_open'] = "<ul class='pagination'>";
                $config['full_tag_close'] ="</ul>";
                $config['num_tag_open'] = '<li>';
                $config['num_tag_close'] = '</li>';
                $config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
                $config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
                $config['next_tag_open'] = "<li>";
                $config['next_tagl_close'] = "</li>";
                $config['prev_tag_open'] = "<li>";
                $config['prev_tagl_close'] = "</li>";
                $config['first_tag_open'] = "<li>";
                $config['first_tagl_close'] = "</li>";
                $config['last_tag_open'] = "<li>";
                $config['last_tagl_close'] = "</li>";

                $this->pagination->initialize($config);

                // build paging links
                $vista_interna["links"] = $this->pagination->create_links();
            }
            $time_end = microtime(true);
            $time_total = number_format($time_end - $time_start, 2);

            $vista_interna["time_total"] = "Proceso ejecutado en $time_total seg. Total de registro $total_records.";
        }

        $this->data->vista_interna = $vista_interna;
        $this->title('Log de Usuarios');
        $this->view = 'backend/userslog/users_list';
        $this->template('template/backend');
    }

    public function view($id)
    {
        $vista_interna = array(
            'permisos_efectivos' => $this->permisos,
            'result'             => $this->codegen_model->row('users', '*', 'id = ' . $id),
        );

        $vista_externa = array(
            'title'          => ucwords("users"),
            'contenido_main' => $this->load->view('backend/users/users_view', $vista_interna, true),
        );

        $this->load->view('template/view', $vista_externa);
    }

}

/* End of file users.php */
/* Location: ./system/application/controllers/users.php */
