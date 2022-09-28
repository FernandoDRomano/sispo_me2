<?php     
    $attributes = array('id' => 'formEnviar');
    echo form_open(base_url(). 'zonas/zonas/store/', $attributes);
?>    
    <div class="col-xs-12">
        <div class="ibox-content">    	
            
            <div class="row">

                <div class="col-xs-12">
                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Nombre" required>
                    </div>
                </div>

                <div class="col-xs-12">
                    <div class="form-group">
                        <label for="descripcion">Descripción:</label>
                        <textarea name="descripcion" id="descripcion" cols="10" rows="5" class="form-control" placeholder="Descripción" required></textarea>
                    </div>
                </div>

                <div class="col-xs-12">
                    <div class="form-group">
                        <label for="pais">PaÍs</label>
                        <select name="pais" id="pais" class="form-control">
                            <option value="0">Seleccione</option>
                            <?php foreach($paises as $p) { ?>
                                <option value="<?php echo $p->id ?>"><?php echo $p->nombre?></option>
                            <?php }?>
                        </select>
                    </div>
                </div>

                <div class="col-xs-12">
                    <div class="form-group">
                        <label for="provincia">Provincia</label>
                        <select name="provincia[]" disabled multiple id="provincia" class="form-control" style="width: 100%"></select>
                    </div>
                </div>
                
                <!-- CONTENEDOR PARA LAS PROVINCIAS DINAMICAS -->
                <div class="col-xs-12" id="wrapper_provincias" style="display:none;">
                    <p>Seleccionar todas las localidades de las siguientes provincias: </p>
                    <div class="row" id="contenedor_provincias">
                    </div>
                </div>
                
                <hr>
                <!-- FINAL DEL CONTENEDOR PARA LAS PROVINCIAS DINAMICAS -->

                <div class="col-xs-9">
                    <div class="form-group">
                        <label for="localidad">Localidad</label>
                        <select name="localidad[]" disabled multiple id="localidad" class="form-control" style="width: 100%"></select>
                    </div>
                </div>
                <div class="col-xs-3">
                    <div class="form-group">
                        <button type="button" class="btn btn-primary" onclick="getLocalidades()">Mostrar</button>
                    </div>
                </div>

                <input type="hidden" multiple name="provincias_seleccionadas" id="provincias_seleccionadas">
                <input type="hidden" multiple name="provincias_no_seleccionadas" id="provincias_no_seleccionadas">

            </div>

    	</div>
    </div>


    <!-- Botones -->
    <div class="col-xs-12">
        <div class="ibox-content">
            <div class="control-group">
              <div class="controls">
                <?php echo form_button(array('type'  =>'button' ,'id' => 'btnGuardar', 'onclick' => 'guardar()' ,'value' =>'Guardar','class' =>'btn btn-success'), "<i class='fa fa-floppy-o'></i> Guardar"); ?> 
                <a class="btn btn-danger" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2); ?>"><i class="fa fa-arrow-circle-left"></i> Volver</a>
              </div>
            </div>
        </div>
    </div>
    <!-- end Botones -->

<script type="text/javascript">

function getProvincias(pais){
    const url = `<?php echo base_url() ?>zonas/zonas/getDepartamentos/${pais}`

    fetch(url)
        .then(resp => resp.json())
        .then(data => {
            //ACTIVO EL SELECT DE PROVINCIA CUANDO TENGO LOS DATOS
            $('#provincia').attr("disabled", false)
            //DISPLAY:block WRAPPER PROVINCIAS
            $('#wrapper_provincias').css("display", "block");
            
            let html = '';

            for (let index = 0; index < data.length; index++) {
                html += `<option value="${data[index].id}">${data[index].nombre}</option>`
            }

            document.getElementById('provincia').insertAdjacentHTML('beforeend', html);
        })
    
}

function getLocalidades(){
    let provincias = getCheckBox(2);

    provincias = provincias.toString()

    if(provincias != ""){
        const url = `<?php echo base_url() ?>zonas/zonas/getLocalidades?provincias=${provincias}`

        //LIMPIO EL SELECT ANTES DE CARGAR LOS DATOS NUEVOS
        document.getElementById('localidad').innerHTML = ''
        $('#localidad').val(null).trigger('change');
        
        fetch(url)
            .then(resp => resp.json())
            .then(data => {
                //CUANDO OBTENGO LAS LOCALIDADES ACTIVO EL SELECT
                $('#localidad').attr('disabled', false) 
                
                let html = '';

                for (let index = 0; index < data.length; index++) {
                    html += `<option value="${data[index].idLocalidad},${data[index].idProvincia}">${data[index].localidad} (${data[index].provincia})</option>`
                }

                document.getElementById('localidad').insertAdjacentHTML('beforeend', html);
                
            })
    }else{
        alert("No selecciono ninguna provincia")
    }
    
}

$(document).ready(function() {
    //INICIALIZANDO EL SELECT DE PAISES
    $('#pais').select2();

    //INICIALIZANDO EL SELECT DE PROVINCIAS
    $('#provincia').select2();

    //INICIALIZANDO EL SELECT DE LOCALIDADES
    $('#localidad').select2();

    //INICIANDO LOS SELECT DE LOCALIDAD ORIGEN Y DESTINO
    $('#localidad_origen').select2();
    $('#localidad_destino').select2();

    //ESCUCHANDO LOS CAMBIOS DE SELECT PAIS
    $('#pais').change(function(event){
        //DESACTIVO EL SELECT DE PROVINCIA HASTA QUE TENGA LOS DATOS DE LA PROVINCIA
        $('#provincia').attr("disabled", true)
        $('#localidad').attr("disabled", true)
        limpiarContenedorProvincia();
        //LIMPIO EL SELECT ANTES DE CARGAR LOS DATOS NUEVOS
        document.getElementById('provincia').innerHTML = ''
        $('#provincia').val(null).trigger('change');

        document.getElementById('localidad').innerHTML = ''
        $('#localidad').val(null).trigger('change');
        //DISPLAY:NONE WRAPPER PROVINCIAS
        $('#wrapper_provincias').css("display", "none");
        
        let pais = $('#pais').val()

        if(pais != "0"){
            getProvincias(pais)
        }

    })

    //ESCUCHANDO LOS CAMBIOS DE SELECT PROVINCIA
    $('#provincia').change(function(event){
        
        let provincias = $('#provincia').val()

        if(provincias != "0"){

            limpiarContenedorProvincia();

            const nodos = event.target.childNodes

            if(provincias){
                for(let i = 0; i < provincias.length; i++){
                    for (let index = 0; index < nodos.length; index++) {
                        const element = nodos[index];
                        if(provincias[i] == element.value){
                            crearInputProvincia(element.value, element.text)
                        }
                    }
                }    
            
                //NO REALIZAR LA BUSQUEDA AQUI, SINO CUANDO SE HAGA CLICK EN EL BOTON FILTRAR
                //getLocalidades(provincias)
            }

        }

    })
});

function getCheckBox(type){
    //TYPE 1 = checkboxs seleccionados
    //TYPE 2 = checkboxs no seleccionados

    let noSeleccionados = [];
    let seleccionados = [];

    //OBTENGO LOS CHECK SELECCIONADOS    
    $('input[type=checkbox]:checked').each(function() {
        //console.log("Checkbox " + $(this).prop("id") +  " (" + $(this).val() + ") Seleccionado");
        seleccionados.push( $(this).prop("id") )
    });

    //OBTENGO LOS CHECK NO SELECCIONADOS
    $('input[type=checkbox]:unchecked').each(function() {
        //console.log("Checkbox " + $(this).prop("id") +  " (" + $(this).val() + ") NO Seleccionado");
        noSeleccionados.push( $(this).prop("id") )
    });

    if(type == 1){
        return seleccionados
    }

    if(type == 2){
        return noSeleccionados
    }
}

function crearInputProvincia(id, nombre){
    let contenedor = document.getElementById('contenedor_provincias');

    let provincia = `
                <div class="col-6 col-xs-3">
                    <div class="form-group">
                        <label for="${id}">${nombre}</label>
                        <input type="checkbox" name="${id}" id="${id}" class="checkbox_provincia">
                        <input type="hidden" name="provincia_id_${id}" id="provincia_id_${id}" value="${id}">
                    </div>
                </div>
    `;

    contenedor.insertAdjacentHTML('beforeend', provincia)
}

function limpiarContenedorProvincia(){
    let contenedor = document.getElementById('contenedor_provincias');
    contenedor.innerHTML = ''
}

function guardar(){
    
    //VALIDACIÓN NOMBRE  
    let nombre = document.getElementById('nombre')
    if(nombre.value == "" || nombre.value.replace(/ /g, "").length == 0){
        alert("El nombre de la zona es requerido")
        nombre.value = "";
        nombre.focus();
        return;
    }

    //VALIDANDO EL PAÍS
    let pais = document.getElementById('pais')
    if(pais.value == "" || pais.value == "0"){
        alert("El país de la zona es requerido")
        pais.value = "";
        pais.focus();
        return;
    }

    //VALIDANDO LA PROVINCIA
    let provincia = document.getElementById('provincia')
    if(provincia.value == "" || provincia.value == "0"){
        alert("La provincia de la zona es requerida")
        provincia.value = "";
        provincia.focus();
        return;
    }

    const provinciasSeleccionadas = getCheckBox(1);
    const provinciasNoSeleccionadas = getCheckBox(2);

    //SI NO SE SELECCIONO NINGUNA PROVINCIA CON TODAS LAS LOCALIDADES, 
    //VALIDO QUE POR LO MENOS UNA LOCALIDAD SE BUSCO CON EL SELECT
    if(provinciasSeleccionadas.length == 0){

        //VALIDANDO LA LOCALIDAD
        let localidad = document.getElementById('localidad')
        if(localidad.value == "" || localidad.value == "0"){
            alert("La localidad de la zona es requerida")
            localidad.value = "";
            localidad.focus();
            return;
        }

    }

    console.log("Seleccionadas: ",provinciasSeleccionadas)
    console.log("No Seleccionadas: ",provinciasNoSeleccionadas)

    document.getElementById('provincias_no_seleccionadas').value = provinciasNoSeleccionadas
    document.getElementById('provincias_seleccionadas').value = provinciasSeleccionadas

    $('#formEnviar').submit()
}

</script>