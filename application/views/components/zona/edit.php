<?php     
    $attributes = array('id' => 'formEnviar');
    echo form_open(base_url(). 'zonas/zonas/update/'. $zona->id, $attributes);
?>    
    <div class="col-xs-12">
        <div class="ibox-content">    	
            
            <div class="row">

                <input type="hidden" name="zona_id" id="zona_id" value="<?php echo $zona->id; ?>">

                <div class="col-xs-12">
                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input value="<?php echo $zona->nombre ?>" type="text" name="nombre" id="nombre" class="form-control" placeholder="Nombre" required>
                    </div>
                </div>

                <div class="col-xs-12">
                    <div class="form-group">
                        <label for="descripcion">Descripción:</label>
                        <textarea name="descripcion" id="descripcion" cols="10" rows="5" class="form-control" placeholder="Descripción" required><?php echo $zona->descripcion ?></textarea>
                    </div>
                </div>

                <div class="col-xs-12">
                    <div class="form-group">
                        <label for="pais">PaÍs</label>
                        <select name="pais" disabled id="pais" class="form-control">
                            <option value="0">Seleccione</option>
                            <?php foreach($paises as $p) { ?>
                                <option value="<?php echo $p->id ?>" <?php echo $pais->id == $p->id ? 'selected' : '' ?> ><?php echo $p->nombre?></option>
                            <?php }?>
                        </select>
                    </div>
                </div>

                <div class="col-xs-12">
                    <div class="form-group">
                        <label for="provincia">Provincia</label>
                        <select name="provincia[]" multiple id="provincia" class="form-control" style="width: 100%">
                            <?php foreach($provincias as $p) { ?>
                                <option value="<?php echo $p->id ?>"
                                <?php foreach($provinciasSeleccionadas as $s) { ?>
                                     <?php echo $p->id == $s->id ? "disabled='disabled'" : '' ?> 
                                <?php }?>
                                ><?php echo $p->nombre?></option>
                            <?php }?>
                        </select>
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

    <div class="col-xs-12">

        <div class="ibox-content">        
            <h3>Localidades Cargadas</h3>
            <table id="resultados" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
                <thead>
                    <tr>
                        <!-- <th><a href="#">Provincia</a></th> -->
                        <th><a href="#">Localidad</a></th>
                        <th><a href="#">Acciones</a></th>
                    </tr>
                </thead>
                <tbody>
                
                <?php foreach ($detalles as $det) { ?>
                    <tr>
                        <!-- 
                        <td>                      
                            <?php echo $det->provincia ?><br>
                        </td>
                        -->
                        <td>
                            <?php echo $det->localidad ?><br>
                        </td>
                        <td>
                        <div class="btn-group">
                            <a href="#" class="btn btn-danger btn-delete" data-id="<?php echo base_url().'zonas/zonas/deleteAjax/'.$det->id ?>" ><i class="fa fa-trash-o" data-id="<?php echo base_url().'zonas/zonas/deleteAjax/'.$det->id ?>"></i></a>
                        </div>
                        </td>
                    </tr>
                <?php } ?>
                
                </tbody>
            </table>
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

//PROVINCIAS SELECCIONADAS DESDE EL BACKEND
let provinciasSelecionadas = <?php echo json_encode($provinciasSeleccionadas); ?>;
let localidadesSeleccionadas = <?php echo json_encode($localidadesSeleccionadas); ?>;

// DEFINICION DE DATATABLE
const table = $('#resultados').DataTable({
        "columnDefs": [{
          "targets"  : 'no-sort',
          "orderable": false,
        }]
});

//AL HACER CLICK EN EL BOTON DE ELIMINAR
table.on('click', 'a', function(event) {
    event.preventDefault();

    let $tr = $(this).closest('tr');

    const enlace = event.target.getAttribute('data-id');

    eliminarDetalle(enlace, $tr)

});

function actualizarLocalidadesSeleccionadas(id){
    const url = `<?php echo base_url() ?>zonas/zonas/getLocalidadesSeleccionadas/${id}`

    fetch(url)
        .then(resp => resp.json())
        .then(data => {
            localidadesSeleccionadas = data

            //getLocalidades()
        })
}


function eliminar(event, link){
    event.preventDefault();

    eliminarDetalle(link)
}

function eliminarDetalle(link, $tr){    
    
    bootbox.confirm({
        title: "Eliminar Registro",
        message: "¿Estas seguro de eliminar el registro?",
        buttons: {
            cancel: {
                label: '<i class="fa fa-times"></i> Cancel'
            },
            confirm: {
                label: '<i class="fa fa-check"></i> Confirm'
            }
        },
        callback: function (result) {
            if(result){
                
                fetch(link, {
                    method: 'DELETE'
                })
                .then(resp => resp.json())
                .then(data => {
                    if(data){
                        // Le pedimos al DataTable que borre la fila
                        table.row($tr).remove().draw(false);
                        //Actualizar de nuevo las localidades
                        const id = document.getElementById('zona_id').value
                        actualizarLocalidadesSeleccionadas(id)
                    }
                })
                
                
               //console.log("enlace: ", link)
            }
        }
    });

}

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
    
    for (let index = 0; index < provinciasSelecionadas.length; index++) {
        const element = provinciasSelecionadas[index];
        provincias.push(element.id)
    }

    provincias = provincias.toString()

    console.log(provincias)

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

                html +=  `
                        <option value="${data[index].idLocalidad},${data[index].idProvincia}"
                        `

                for(let j = 0; j < localidadesSeleccionadas.length; j++){
                    
                    if(data[index].idLocalidad == localidadesSeleccionadas[j].id){
                        html += ` disabled `
                    }

                }

                html += `
                        >
                            ${data[index].localidad} (${data[index].provincia})
                        </option>`
                                
            }

            document.getElementById('localidad').insertAdjacentHTML('beforeend', html);
            
        })
    
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
        limpiarContenedorProvincia();
        //LIMPIO EL SELECT ANTES DE CARGAR LOS DATOS NUEVOS
        document.getElementById('provincia').innerHTML = ''
        $('#provincia').val(null).trigger('change');
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

    $('#wrapper_provincias').css("display", "block");

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

    /*

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

    */

    const provinciasSeleccionadas = getCheckBox(1);
    const provinciasNoSeleccionadas = getCheckBox(2);

    document.getElementById('provincias_no_seleccionadas').value = provinciasNoSeleccionadas
    document.getElementById('provincias_seleccionadas').value = provinciasSeleccionadas

    $('#formEnviar').submit()
}

</script>