<link href="https://prueba.sispo.com.ar/assets/backend/css/estilos.css" rel="stylesheet">

<?php     
    $attributes = array('id' => 'formEnviar');
    echo form_open(base_url(). 'esquema/esquema/store/', $attributes);
?>    
    <div class="col-xs-12">
        <div class="ibox-content">    	
            
            <div class="row">

                <div class="col-xs-12">
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" name="nombre" id="nombre" placeholder="Ingrese el nombre" class="form-control">
                    </div>
                </div>

            </div>

            <div class="row">
                
                <div class="col-xs-12">
                    <div class="space-between">
                        <label class="font-weight-bold text-primary mb-0">Sub Esquemas</label>
                        <a onclick="agregarSubEsquema(event)" class="btn btn-info btn-sm">Agregar</a>
                    </div>
                </div>

                <div class="col-xs-12" id="contenedor_esquema">
                    
                </div>
            </div>

            

    	</div>
    </div>



    <!-- BOTONES PARA ENVIAR EL FORMULARIO -->
    <div class="col-xs-12">
        <div class="ibox-content">
            <div class="control-group">
              <div class="controls">
                <?php echo form_button(array('type'  =>'button' ,'id' => 'btnGuardar', 'onclick' => "guardar(event, 'store')" ,'value' =>'Guardar','class' =>'btn btn-success'), "<i class='fa fa-floppy-o'></i> Guardar"); ?> 
                <a class="btn btn-danger" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2); ?>"><i class="fa fa-arrow-circle-left"></i> Volver</a>
              </div>
            </div>
        </div>
    </div>
    <!-- END BOTONES PARA ENVIAR EL FORMULARIO -->

<script type="text/javascript">

//CONTADORES
let subEsquema = 0; 
/*
    EL ESQUEMA ES UN OBJETO QUE CONTENDRA SUB OBJETOS DE TIPO SUB_ESQUEMA_N,
    ESTO PARA QUE CADA SUB_ESQUEMA_N PUEDA CONTROLAR LOS DATOS REFERIDOS A SU 
    PLANTILLA. EJEMPLO:

    esquema = {
        "sub_esquema_1": {
            "indiceKilos": 0,
            "indiceBultos": 0,
            "indiceMetroCubico": 0,
            "indicePalets": 0,
            "indiceGestionFlota": 0,
            "indiceWareHouse": 0,
            "indiceValorDeclarado": 0,
            "indiceCobranza": 0,
            "indicePesoAforado": 0,
            "indiceDistancia": 0
        },
        "sub_esquema_2": {
            "indiceKilos": 0,
            "indiceBultos": 0,
            "indiceMetroCubico": 0,
            "indicePalets": 0,
            "indiceGestionFlota": 0,
            "indiceWareHouse": 0,
            "indiceValorDeclarado": 0,
            "indiceCobranza": 0,
            "indicePesoAforado": 0,
            "indiceDistancia": 0
        }
    }
*/
let esquema = {} 

//ELEMENTOS CONSTANTES
const contenedor_esquema = document.getElementById("contenedor_esquema")

/**
 * ESQUEMA
 */

<?php include 'esquema.js'; ?>

agregarSubEsquema(event);

/**
 * PLANTILLA
 */

<?php include 'plantilla.js'; ?>

/**
 * TARIFARIOS
 */

<?php include 'kilos.js'; ?>
<?php include 'bultos.js'; ?>
<?php include 'palets.js'; ?>
<?php include 'metros_cubicos.js'; ?>
<?php include 'warehouse.js'; ?>
<?php include 'gestion_flota.js'; ?>
<?php include 'valor_declarado.js'; ?>
<?php include 'cobranza.js'?>
<?php include 'peso_aforado.js'; ?>
<?php include 'distancia.js'; ?>

/**
 * ENVIAR FORMULARIO
 */

<?php include 'guardar.js'; ?>

</script>