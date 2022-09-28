<?php

class Zonas extends MY_Controller{
    
    public function __construct(){
        parent::__construct();
        $this->permisos = $this->permisos_lib->control();
        $this->load->model('Zona_md');
        $this->load->helper('url');
    }

    public function index()
    {
        // Armo Array de la vista interna, asignandole Permisos, el Cliente obtenido y la bandera Data //
        $vista_interna = array(
            'permisos_efectivos' => $this->permisos,
            'results' => $this->Zona_md->getZonas()
        );

        // Armo Array de la vista externa, agrego titulo y cargo la vista enviandole el Array de vista interna //
        $vista_externa = array(
            'title'          => ucwords("Gesti&#243;n de Zonas Tarifario"),
            'contenido_main' => $this->load->view('components/zona/zona_list', $vista_interna, true),
        );

        // Cargo vista //
        $this->load->view('template/backend', $vista_externa);

    }

    public function filtro($vista = NULL){
        
        if ($vista != null) $vista = '/'.$vista;
        if ($vista == null) $vista = '/zona_list';

        $vista_interna = array(
            'permisos_efectivos' => $this->permisos,
            'results' => $this->Zona_md->getZonas($this->input->post('buscar')),
        );

        $vista_externa = array(
            'title' => ucwords("Gesti&#243;n de Zonas Tarifario"),
            'contenido_main' => $this->load->view('components/zona'.$vista, $vista_interna, true)
        );

        $this->load->view('template/backend', $vista_externa);
        
    }

    public function create(){
        $vista_interna = array(
            'permisos_efectivos' => $this->permisos,
            'paises' => $this->codegen_model->get('flash_paises', '*')
        );
                
        $vista_externa = array(
            'title' => ucwords("Nueva Zona"),
            'contenido_main' => $this->load->view('components/zona/create', $vista_interna, true)
        );

        $this->load->view('template/backend', $vista_externa);
    }

    public function store(){
        //DATOS
        $data['nombre'] = trim($this->input->post('nombre'));
        $data['descripcion'] = trim($this->input->post('descripcion'));
        $userSesion = $this->ion_auth->user()->row();
        $data['user_id'] = $userSesion->id;

        $localidades = $this->input->post('localidad');
        $provinciasSeleccionadas = $this->input->post('provincias_seleccionadas');
        //$provinciasNoSeleccionadas = $this->input->post('provincias_no_seleccionadas');

        //INSERTO LA ZONA
        $zona_id = $this->codegen_model->add('flash_tarifario_zonas', $data);

        //AL VENIR COMO CADENA LOS IDS LOS CONVIERTO A ARRAY
        if(!empty($provinciasSeleccionadas)){
            $provinciasSeleccionadas = explode(",", $provinciasSeleccionadas);

            //INSERTO LAS PROVINCIAS QUE SE SELECCIONARON CON TODAS SUS LOCALIDADES
            foreach ($provinciasSeleccionadas as $provincia) {
                $this->Zona_md->insertDetalleZonaProvinciaConTodasLasLocalidades($provincia, $zona_id);
            }
        }


        //if(!empty($provinciasNoSeleccionadas)){
        //    $provinciasNoSeleccionadas = explode(",", $provinciasNoSeleccionadas);
        //}
        

        //INSERTO LAS PROVINCIAS QUE SE SELECCIONARON PERO NO TIENEN LOCALIDADES
        //foreach ($provinciasNoSeleccionadas as $provincia) {
        //    $this->Zona_md->insertDetalleZonaProvinciaSinLocalidad($provincia, $zona_id);
        //}

        if(!empty($localidades)){
            $this->Zona_md->insertDetalleZonaConLocalidades($localidades, $zona_id);
        }

        redirect('/zonas/zonas/index', 'refresh');
    }

    public function edit($id){
        
        $vista_interna = array(
            'permisos_efectivos' => $this->permisos,
            'zona' => $this->codegen_model->row('flash_tarifario_zonas', '*', 'id = ' . $id),
            'detalles' => $this->Zona_md->getDetalleZona($id),
            'paises' => $this->codegen_model->get('flash_paises', '*'),
            'pais' => $this->Zona_md->getPaisZona($id),
            'provincias' => $this->codegen_model->get('ubicacion_provincias', '*', 'pais_id = ' . $this->Zona_md->getPaisZona($id)->id),
            'provinciasSeleccionadas' => $this->Zona_md->getProvinciasSeleccionadas($id),
            'localidadesSeleccionadas' => $this->Zona_md->getLocalidadesSeleccionadas($id)
        );
        
        
        $vista_externa = array(
            'title' => ucwords("Editar Zona"),
            'contenido_main' => $this->load->view('components/zona/edit', $vista_interna, true)
        );

        $this->load->view('template/backend', $vista_externa);
        
    }

    function update($id){
        //DATOS
        $data['nombre'] = trim($this->input->post('nombre'));
        $data['descripcion'] = trim($this->input->post('descripcion'));
        $userSesion = $this->ion_auth->user()->row();
        $data['user_id'] = $userSesion->id;

        $localidades = $this->input->post('localidad');
        $provinciasSeleccionadas = $this->input->post('provincias_seleccionadas');
        //$provinciasNoSeleccionadas = $this->input->post('provincias_no_seleccionadas');

        //ACTUALIZO LA ZONA
        $this->codegen_model->edit('flash_tarifario_zonas', $data, 'id', $id);

        //AL VENIR COMO CADENA LOS IDS LOS CONVIERTO A ARRAY
        if(!empty($provinciasSeleccionadas)){
            $provinciasSeleccionadas = explode(",", $provinciasSeleccionadas);

            //INSERTO LAS PROVINCIAS QUE SE SELECCIONARON CON TODAS SUS LOCALIDADES
            foreach ($provinciasSeleccionadas as $provincia) {
                $this->Zona_md->insertDetalleZonaProvinciaConTodasLasLocalidades($provincia, $id);
            }
        }

        if(!empty($localidades)){
            $this->Zona_md->insertDetalleZonaConLocalidades($localidades, $id);
        }

        redirect('/zonas/zonas/index', 'refresh');    
    }

    function view($id){
        $vista_interna = array(
            'permisos_efectivos' => $this->permisos,
            'zona' => $this->codegen_model->row('flash_tarifario_zonas', '*', 'id = ' . $id),
            'detalles' => $this->Zona_md->getDetalleZona($id),
            'paises' => $this->codegen_model->get('flash_paises', '*'),
            'pais' => $this->Zona_md->getPaisZona($id),
            'provincias' => $this->codegen_model->get('ubicacion_provincias', '*', 'pais_id = ' . $this->Zona_md->getPaisZona($id)->id),
            'provinciasSeleccionadas' => $this->Zona_md->getProvinciasSeleccionadas($id),
            'localidadesSeleccionadas' => $this->Zona_md->getLocalidadesSeleccionadas($id)
        );
        
        
        $vista_externa = array(
            'title' => ucwords("Detalle Zona"),
            'contenido_main' => $this->load->view('components/zona/view', $vista_interna, true)
        );

        $this->load->view('template/backend', $vista_externa);
    }

    function delete($id){
        $this->codegen_model->delete('flash_tarifario_zona_detalle','zona_id',$id);
        $this->codegen_model->delete('flash_tarifario_zonas','id',$id);

        redirect('/zonas/zonas/index', 'refresh');    
    }

    function deleteAjax($id){
        $this->codegen_model->delete('flash_tarifario_zona_detalle','id',$id);
        echo true;
    }

    function getLocalidadesSeleccionadas($id){
        $json = $this->Zona_md->getLocalidadesSeleccionadas($id);
        if($json){
            echo json_encode($json);
        }else{
            echo json_encode("");
        } 
    }

    function getDepartamentos($pais){
        $json = $this->Zona_md->getDepartamentosPorPais($pais);
		if($json){
            echo json_encode($json);
        }else{
            echo json_encode("");
        } 

    }

    function getLocalidades(){
        $provincias = $this->input->get('provincias');

        //$json = $this->codegen_model->get('ubicacion_localidades','*','departamento_id = '.$provincia);
        
        // SELECT P.id as idProvincia, P.nombre as provincia, L.id as idLocalidad, L.nombre as localidad
        // FROM ubicacion_localidades as L
        // INNER JOIN ubicacion_departamentos AS D ON D.id = L.departamento_id
        // INNER JOIN ubicacion_provincias AS P ON P.id = D.provincia_id
        // WHERE P.id IN (2,9,16,21,23)

        //CONSULTA MOMENTANEA HASTA TENER LA NUEVA ESTRUCTURA DE LA BASE DE DATOS CON PROVINCIAS Y LOCALIDADES
        $query = $this->db
                    ->select('P.id as idProvincia, P.nombre as provincia, L.id as idLocalidad, L.nombre as localidad')
                    ->join('ubicacion_departamentos AS D', 'D.id = L.departamento_id', 'inner')
                    ->join('ubicacion_provincias AS P', 'P.id = D.provincia_id', 'inner')
                    ->where('P.id IN (' . $provincias . ')')
                    ->get('ubicacion_localidades as L');

        $json = $query->result();

		if($json) echo json_encode($json);
    }

    function comprobarQueNoEsteCargadaEnUnEsquema(){
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $request = json_decode($stream_clean);

        $resultado = $this->Zona_md->comprobarQueNoEsteCargadaEnUnEsquemaTarifario($request->id);

        echo json_encode(["data" => $resultado]);
    }

}