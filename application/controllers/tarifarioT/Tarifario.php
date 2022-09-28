<?php

 use Illuminate\Database\Capsule\Manager as DB;

class Tarifario extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $msj = 'Nota: Debe seleccionar un cliente.';

        if($this->input->is_post())
        {
            /*
            $usuario_id = trim($this->input->post('usuario_id'));
            $usuario_nombre = trim($this->input->post('nombre'))
            $cliente = $this->codegen_model->get('flash_clientes', '*','id = '. $usuario_id);
            */

            //Trabajo con form
            //Obtengo Datos Desde Form

            // Verificar Almenos 1 Dato A Insertar

            //Inserto En Mysql
            //Verifico Los Datos Guardados (Datos Del Post Y Mysql Y Se Comparan)
                //$Intentos =0 
                //si no Existe 1 Valor (Dio Error Al Insertar)
                //if Si Reintento Es Menor A 10
                    //Esperar 1 segundo
                    //Reintentar Insertar


            //Cargo Datos Desde Database
            //if count([])>0
            //Muestro Vista Resultante

            // Ocurrio Un Error Del Lado Del Servidor




            $rangoInicio1B =  trim($this->input->post('c_rinicio1_cantidad'));
            $rangoFin1B = trim($this->input->post('c_rfin1_cantidad'));
            $rangoPrecio1B = trim($this->input->post('c_precio1_cantidad'));

            //$variables = $rangoInicio1B . ' / ' . $rangoFin1B . ' / ' . $rangoPrecio1B;

            if(!empty($rangoInicio1B) && !empty($rangoFin1B) && !empty($rangoPrecio1B)){
                if($rangoInicio1B > 0 && $rangoFin1B > 0 && $rangoPrecio1B > 0){
                    $msj = 'm1: El usuario ingreso todos los valores. Todos los valores son mayores que 0.'; 

                    $bandera = 1;

                    $tarifa = array(
                        'desde_cant_unid_bultos' => $rangoInicio1B,
                        'hasta_cant_unid_bultos' => $rangoFin1B,
                        'precio_Corte_bultos' => $rangoPrecio1B,
                        'bandera_Corte_Bultos' => $bandera,
                    );
                    
                    $id_registro_tarifa = $this->codegen_model->add('flash_servicios_paqueteria_tarifas',$tarifa);


                    if(!empty($id_registro_tarifa)){
                        //$valor = $id_registro_tarifa;

                        $cliente_id = $this->input->post('cliente');

                        $userSesion = $this->ion_auth->user()->row();

                        $user = $userSesion->id;


                        $asociacion_dato = array(
                            'cliente_id' => $cliente_id,
                            'tarifario_id' => $id_registro_tarifa,
                            'create_user_id' => $user,
                        );

                        $resultado_consulta = $this->codegen_model->add('flash_servicios_paqueteria',$asociacion_dato);

                        if(!empty($resultado_consulta)){
                            $resultado_consulta = 'Se grabo correctamento.';
                        }
                    }    
                }
                else{
                    $msj = 'm2: El usuario ingreso todos los valores. Pero hay valores que son 0 o menores que 0.'; 
                }   
            }
            else
            {
                $msj = 'm3: El usuario ingreso todos los valores. Pero hay valores que son 0 o menores que 0.';
            }    
        }
        


//   Obtengo cliente (con todos sus datos) de la tabla flash_clientes   //
        $clientes = $this->codegen_model->get('flash_clientes', '*','');

// Compruebo si la variable obtuvo datos //
        if ($clientes) {
            $data = true;
        }else{
            $data = false;
        }

// Armo Array de la vista interna, asignandole Permisos, el Cliente obtenido y la bandera Data //
        $vista_interna = array(
            'permisos_efectivos' => $this->permisos,
            'client'             => $clientes,
            'data'             => $data,
            'msj'           => $msj,
            'resultado'     => $resultado_consulta,
            'id_cliente'    => $cliente_id,
            //'user'          => $user,
            //'resultado'       => $valor,
        );

// Armo Array de la vista externa, agrego titulo y cargo la vista enviandole el Array de vista interna //
        $vista_externa = array(
            'title'          => ucwords("Comerciales"),
            'contenido_main' => $this->load->view('components/tarifario/calculo', $vista_interna, true),
        );

// Cargo vista //
        $this->load->view('template/backend', $vista_externa);

          
        /*
        //$usuario_id = $this->usuario_id;
        $clientes = $this->clientes;
        
        $this->title('Tarifario');
        $this->view('components/tarifario/calculo');
        $this->template('template/backend');
        */
    }

    /*for($i =1;$i = 10 ;$i++){
        $nombre = "fr".1;
        $valor[$i] = $$nombre;
    }*/
}
