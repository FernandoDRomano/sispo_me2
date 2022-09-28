<?php
require_once("./application/views/template/rubenbackend/Elementos.php");

class Medicioningresos extends CI_Controller
{
    
    private $permisos;
    public function __construct()
    {
        parent::__construct();
        $this->permisos = $this->permisos_lib->control();
        
    }
    public function index()
    {
        $piezas = [];
        if ($this->input->is_post('form-piezas')) {
            $piezas = $this->codegen_model->get('flash_piezas','*',' barcode_externo = '.$this->input->post('codigo_barra'));
        }
        
        $vista_interna = array(
                'permisos_efectivos' => $this->permisos,
                'piezas' =>$piezas,
                'sucursales'     => $sucursales,
        );
        
        $vista_externa = array(
            'title'          => ucwords("piezas"),
            'contenido_main' => $this->load->view('components/administrador/medicioningresos.php', $vista_interna, true),
        );
        $this->load->view('template/rubenbackend', $vista_externa);
    }
    
}
/* End of file piezas.php */
/* Location: ./system/application/controllers/piezas.php */
