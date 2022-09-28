<?php

class Tarifario extends MY_Controller{
    
    public function __construct(){
        parent::__construct();
        $this->permisos = $this->permisos_lib->control();
        $this->load->model('Tarifario_f_md');
        $this->load->helper('url');
    }

    public function index(){
        // Armo Array de la vista interna, asignandole Permisos, el Cliente obtenido y la bandera Data //
        $vista_interna = array(
            'permisos_efectivos' => $this->permisos,
            'results' => $this->Tarifario_f_md->getTarifarios()
        );

        // Armo Array de la vista externa, agrego titulo y cargo la vista enviandole el Array de vista interna //
        $vista_externa = array(
            'title'          => ucwords("Tarifario"),
            'contenido_main' => $this->load->view('components/tarifarioF/tarifario_list', $vista_interna, true),
        );

        // Cargo vista //
        $this->load->view('template/backend', $vista_externa);

    }

    public function filtro($vista = NULL){
        if ($vista != null) $vista = '/'.$vista;
        if ($vista == null) $vista = '/tarifario_list';

        $vista_interna = array(
            'permisos_efectivos' => $this->permisos,
            'results' => $this->Tarifario_f_md->getTarifarios($this->input->post('buscar')),
        );

        $vista_externa = array(
            'title' => ucwords("Tarifario"),
            'contenido_main' => $this->load->view('components/tarifarioF'.$vista, $vista_interna, true)
        );

        $this->load->view('template/backend', $vista_externa);
    }

    function create(){
        $vista_interna = array(
            'permisos_efectivos' => $this->permisos,
            'esquemas' => $this->codegen_model->get('flash_esquema', '*'),
            'clientes' => $this->codegen_model->get('flash_clientes', '*')
        );
                
        $vista_externa = array(
            'title' => ucwords("Nuevo Tarifario"),
            'contenido_main' => $this->load->view('components/tarifarioF/create', $vista_interna, true)
        );

        $this->load->view('template/backend', $vista_externa);
    }

    function getClienteEsquema(){
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $request = json_decode($stream_clean);

        $resultado = $this->Tarifario_f_md->validarQueElclienteNoTengaEsquemaPrevio($request->cliente);

        echo json_encode(["cantidad" => $resultado->cantidad]);
    }

    function store(){
        $data["cliente_id"] = trim($this->input->post('cliente'));
        $data["esquema_id"] = trim($this->input->post('esquema'));
        $userSesion = $this->ion_auth->user()->row();
        $data['user_id'] = $userSesion->id;

        //INICIO TRANSACCIÓN
        $this->db->trans_start();

        //INSERT
        $this->codegen_model->add('flash_tarifario', $data);

        //FINALIZO TRANSACCION
        $this->db->trans_complete();

        redirect('/tarifario/Tarifario/index', 'refresh');
    }

    function edit($id){
        
        $vista_interna = array(
            'permisos_efectivos' => $this->permisos,
            'esquemas' => $this->codegen_model->get('flash_esquema', '*'),
            'clientes' => $this->codegen_model->get('flash_clientes', '*'),
            'tarifario' => $this->codegen_model->row('flash_tarifario', '*', 'id = ' . $id)
        );
                
        $vista_externa = array(
            'title' => ucwords("Editar Tarifario"),
            'contenido_main' => $this->load->view('components/tarifarioF/edit', $vista_interna, true)
        );

        $this->load->view('template/backend', $vista_externa);
        
    }

    function update($id){
        //SOLO ACTUALIZO EL ESQUEMA NO EL CLIENTE
        $data["esquema_id"] = trim($this->input->post('esquema'));
        $userSesion = $this->ion_auth->user()->row();
        $data['user_id'] = $userSesion->id;

        //INICIO TRANSACCIÓN
        $this->db->trans_start();

        //INSERT
        $this->codegen_model->edit('flash_tarifario', $data, 'id', $id);

        //FINALIZO TRANSACCION
        $this->db->trans_complete();

        redirect('/tarifario/Tarifario/index', 'refresh');
    }

    function delete($id){
        $this->codegen_model->delete('flash_tarifario','id',$id);
    }

    function validarQueElClienteNotengaPaquetesSinLiquidarAntesDeQuitarElTarifarioConfigurado(){
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $request = json_decode($stream_clean);

        $resultado = $this->Tarifario_f_md->getPaquetesSinLiquidarParaEsteTarifario($request->cliente);

        echo json_encode(["data" => $resultado]);
    }

}