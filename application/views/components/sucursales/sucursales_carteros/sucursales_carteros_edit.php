<style>
    .hr {
        border-color: #a8a8a8;
        margin-top: 10px;
    }

    .tituloFormulario {
        color: #697882;
        font-size: 22px;
        font-weight: 400;
        border-bottom: 2px solid #ADCE9D !important;
        padding-bottom: 7px;
        margin-bottom: 2rem;
        margin-top: 1.3rem;
    }

    .btn-cerrar{
        position: absolute;
        top: 0;
        right: 0;
        padding: 10px;
        background-color: red;
        color: white;
        font-size: 1.2rem;
        font-weight: bold;
        cursor: pointer;
    }
</style>

<div class="col-xs-12">
    <div class="ibox-content">
		<?php     
            $attributes = array('id' => 'carteros');
            echo form_open_multipart(current_url(), $attributes);
			echo form_hidden('enviar_form','1');
		?>
			<?php echo form_hidden('id',$result->id) ?>
			
            <?php
                if(isset($MSJ)){
                    echo ('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . $MSJ . '</div>');
                }
            ?>

            <div class="row">

                <div class="col-xs-12">
                    <h3 class="tituloFormulario">Cartero</h3>
                </div>

                <div class="col-xs-12">
                    <div class="form-group">
                        <label for="apellido_nombre">Apellido nombre: <span class="required">*</span></label>
                        <input required id="apellido_nombre" name="apellido_nombre" type="text" class="form-control" placeholder="Apellido nombre" value="<?php echo $result->apellido_nombre ?>" />
                    </div>
                </div>

                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="email">Email: <span class="required">*</span></label>
                        <input required id="email" name="email" type="email" class="form-control" placeholder="Email" value="<?php echo $result->email ?>"/>
                    </div>
                </div>

                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="telefono">Telefono: <span class="required">*</span></label>
                        <input id="telefono" name="telefono" type="number" class="form-control tags" placeholder="Telefono" value="<?php echo $result->telefono ?>" />
                    </div>
                </div>

                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="cartero_tipo_id">Tipo de Cartero: <span class="required">*</span></label>
                        <select id="cartero_tipo_id" name="cartero_tipo_id" class="form-control chosen-select">
                            <option value="0">Seleccione una opci&oacute;n</option>
                            <?php foreach ($carteros_tipos as $f) { ?>
                                <option value="<?php echo $f->id ?>" <?php if($f->id == $result->cartero_tipo_id) echo "selected" ?>><?php echo $f->nombre ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="pais">Pa&iacute;s: <span class="required">*</span></label>
                        <select onchange="cargarProvincias(event); cargarSucursales(event);" name="pais" id="pais" class="form-control">
                            <option value="0">Seleccione una opci&oacute;n</option>
                            <?php foreach ($paises as $p) { ?>
                                <option value="<?php echo $p->id ?>" <?php if($p->id == $result->pais) echo "selected" ?>><?php echo $p->nombre ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="sucursal_id">Sucursal: <span class="required">*</span></label>
                        <select id="sucursal_id" name="sucursal_id" class="form-control">
                            <option value="0">Seleccione una opci&oacute;n</option>
                            <?php foreach ($sucursales as $f) { ?>
                                <option value="<?php echo $f->id ?>" <?php if($f->id == $result->sucursal_id) echo "selected" ?>><?php echo $f->nombre ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="col-xs-12">
                    <h3 class="tituloFormulario">Datos Fiscales</h3>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="condicion_fiscal">Condici&oacute;n fiscal: <span class="required">*</span></label>
                        <select id="condicion_fiscal" name="condicion_fiscal" class="form-control">
                            <option value="0">Seleccione una opci&oacute;n</option>
                            <?php foreach ($iva as $f) { ?>
                                <option value="<?php echo $f->id ?>" <?php if($f->id == $result->condicion_fiscal) echo "selected" ?> ><?php echo $f->descripcion ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-xs-8">
                    <div class="form-group">
                        <label for="dni_cuit_cuil">DNI / CUIT / CUIL / otro: <span class="required">*</span></label>
                        <input id="dni_cuit_cuil" name="dni_cuit_cuil" type="number" class="form-control" placeholder="DNI/CUIT/CUIL/otro" value="<?php echo $result->dni_cuit_cuil ?>"/>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="ingresos_brutos">Ingresos Brutos:</label>
                        <input id="ingresos_brutos" name="ingresos_brutos" type="text" class="form-control" placeholder="Ingresos Brutos" value="<?php echo $result->ingresos_brutos ?>" />
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="inicio_actividades">Inicio de Actividades (MM/AAAA):</label>
                        <input id="inicio_actividades" name="inicio_actividades" type="text" class="form-control" placeholder="MM/AAAA" value="<?php echo $result->inicio_actividades ?>" />
                    </div>
                </div>

                <div class="col-xs-12">
                    <h3 class="tituloFormulario">Ubicaci&oacute;n</h3>
                </div>

                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="provincia">Provincia / Departamento: <span class="required">*</span></label>
                        <select onchange="cargarLocalidades(event)" name="provincia" id="provincia" class="form-control">
                            <option value="0">Seleccione una opci&oacute;n</option>
                            <?php foreach($provincias as $p) { ?>
                                <option value="<?php echo $p->id ?>" <?php echo $result->provincia == $p->id ? 'selected' : '' ;?>><?php echo $p->nombre?></option>
                            <?php }?>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="localidad">Localidad / Municipio: <span class="required">*</span></label>
                        <select name="localidad" id="localidad" class="form-control">
                            <option value="0">Seleccione una opci&oacute;n</option>
                            <?php foreach($localidades as $l) { ?>
                                <option value="<?php echo $l->id ?>" <?php echo $result->localidad == $l->id ? 'selected' : '' ;?>><?php echo $l->nombre?></option>
                            <?php }?>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="domicilio">Calle: <span class="required">*</span></label>
                        <input id="domicilio" name="domicilio" type="text" class="form-control" placeholder="Calle" value="<?php echo $result->domicilio ?>" />
                    </div>
                </div>
                <div class="col-xs-3">
                    <div class="form-group">
                        <label for="altura">Altura: <span class="required">*</span></label>
                        <input id="altura" name="altura" type="number" class="form-control" placeholder="Altura" value="<?php echo $result->altura ?>" />
                    </div>
                </div>
                <div class="col-xs-3">
                    <div class="form-group">
                        <label for="codigo_postal">Codigo postal: <span class="required">*</span></label>
                        <input id="codigo_postal" name="codigo_postal" type="text" class="form-control" placeholder="Codigo postal" value="<?php echo $result->codigo_postal ?>" />
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="esquina_1">Esquina: </label>
                        <input id="esquina_1" name="esquina_1" type="text" class="form-control" placeholder="" value="<?php echo $result->esquina_1 ?>" />
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="esquina_2">Esquina 2: </label>
                        <input id="esquina_2" name="esquina_2" type="text" class="form-control" placeholder="" value="<?php echo $result->esquina_2 ?>" />
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="piso">Piso: </label>
                        <input id="piso" name="piso" type="text" class="form-control" placeholder="" value="<?php echo $result->piso ?>" />
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="departamento">Departamento/Oficina: </label>
                        <input id="departamento" name="departamento" type="text" class="form-control" placeholder="" value="<?php echo $result->departamento ?>" />
                    </div>
                </div>


                <div class="col-xs-12">
                    <h3 class="tituloFormulario">AFIP</h3>
                </div>

                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="afip_fecha_vencimiento">Fecha de Vencimiento:</label>
                        <input id="afip_fecha_vencimiento" name="afip_fecha_vencimiento" type="date" class="form-control" value="<?php echo $result->afip_fecha_vencimiento ?>" />
                    </div>
                </div>

                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="afip_imagen">Imagen</label>
                        <input onchange="ocultarImagen('img_afip_imagen')" type="file" name="afip_imagen" id="afip_imagen" class="form-control" accept="image/png,image/jpeg,image/jpg">
                    </div>
                </div>

                <input type="hidden" name="eliminar_afip_imagen" id="eliminar_afip_imagen">

                <?php if($result->afip_imagen){ ?>
                    <div class="col-xs-6" id="img_afip_imagen">
                        <div class="form-group">
                            <span onclick="eliminarImagen('eliminar_afip_imagen', 'img_afip_imagen')" class="btn-cerrar">X</span>
                            <img style="height: auto; width: 100%;" src="/uploads/carteros/<?php echo $result->afip_imagen?>" alt="afip_imagen_<?php echo $result->carnet_conductor_imagen ?>">
                        </div>
                    </div>
                <?php } ?>

                <div class="col-xs-12">
                    <h3 class="tituloFormulario">Carnet Conductor</h3>
                </div>

                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="carnet_conductor_fecha_vencimiento">Fecha de Vencimiento:</label>
                        <input id="carnet_conductor_fecha_vencimiento" name="carnet_conductor_fecha_vencimiento" type="date" class="form-control" value="<?php echo $result->carnet_conductor_fecha_vencimiento ?>" />
                    </div>
                </div>

                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="carnet_conductor_imagen">Imagen</label>
                        <input onchange="ocultarImagen('img_carnet_conductor_imagen')" type="file" name="carnet_conductor_imagen" id="carnet_conductor_imagen" class="form-control" accept="image/png,image/jpeg,image/jpg">
                    </div>
                </div>

                <input type="hidden" name="eliminar_carnet_conductor_imagen" id="eliminar_carnet_conductor_imagen">

                <?php if($result->carnet_conductor_imagen){ ?>
                    <div class="col-xs-6" id="img_carnet_conductor_imagen">
                        <div class="form-group" style="position: relative;">
                            <span onclick="eliminarImagen('eliminar_carnet_conductor_imagen', 'img_carnet_conductor_imagen')" class="btn-cerrar">X</span>
                            <img style="height: auto; width: 100%;" src="/uploads/carteros/<?php echo $result->carnet_conductor_imagen?>" alt="carnet_conductor_imagen_<?php echo $result->carnet_conductor_imagen ?>">
                        </div>
                    </div>
                <?php } ?>

                <div class="col-xs-12">
                    <h3 class="tituloFormulario">Otros</h3>
                </div>

                <div class="col-xs-12">
                    <div class="form-group">
                        <label for="activo">Activo</label>
                        <div style="display: flex; align-items: center;">
                            <div class="radio i-checks" style="margin-top: 0px;"><label> <input type="radio" name="activo" id="activo" class="form-control" value="1" <?php if($result->activo == 1) echo "checked" ?>> Si</label></div>
                            <div class="radio i-checks"><label> <input type="radio" name="activo" id="activo" class="form-control" value="0" <?php if($result->activo == 0) echo "checked" ?>> No</label></div>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12">
                    <div class="form-group">
                        <label for="productividad">Productividad:</label>
                        <input id="productividad" name="productividad" type="number" class="form-control" value="<?php echo $result->productividad ?>" />
                    </div>
                </div>

                <div class="col-xs-12">
                    <div class="form-group">
                        <label for="observaciones">Observaciones:</label>
                        <!-- <input id="observaciones" name="observaciones" type="text" class="form-control" /> -->
                        <textarea name="observaciones" id="observaciones" cols="5" rows="3" class="form-control"><?php echo $result->observaciones ?></textarea>
                    </div>
                </div>

            </div>


        <div class="control-group">
                <div class="controls">
                    <?php echo form_button(array('type'  =>'submit','value' =>'Guardar Cambios', 'onclick' => 'validar()' ,'name'  =>'btnSubmit','class' =>'btn btn-success'), "<i class='fa fa-floppy-o'></i> Guardar"); ?>
                    <a class="btn btn-danger" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2); ?>"><i class="fa fa-arrow-circle-left"></i> Volver</a>
                </div>
        </div>
        <?php echo form_close(); ?>
	</div>
</div>


<script>
    const nombre = document.getElementById('apellido_nombre')
    const email = document.getElementById('email')
    const telefono = document.getElementById('telefono')
    const tipo_cartero = document.getElementById('cartero_tipo_id')
    const sucursal = document.getElementById('sucursal_id')
    const condicion_fiscal = document.getElementById('condicion_fiscal')
    const dni = document.getElementById('dni_cuit_cuil')
    const pais = document.getElementById('pais')
    const provincia = document.getElementById('provincia')
    const localidad = document.getElementById('localidad')
    const domicilio = document.getElementById('domicilio')
    const codigo_postal = document.getElementById('codigo_postal')
    const altura = document.getElementById('altura')
    const form = document.getElementById('carteros')

    function ocultarImagen(id){+
        console.log("ocultarImagen: ", id)
        const element = document.getElementById(id)
        if(element){
            //document.getElementById(id).style.display = 'none'
            element.style.display = 'none'
        }
    }

    function eliminarImagen(input, id){
        const resp = confirm("Estas seguro de eliminar la imagen?")
        if(resp){
            document.getElementById(input).value = true;
            ocultarImagen(id);
        }
    }

    function validar() {
        event.preventDefault()

        if (nombre.value.length == 0) {
            alert("El apellido y nombre del cartero no puede estar vacio")
            nombre.focus()
            return;
        }

        if (email.value.length == 0) {
            alert("El email del cartero no puede estar vacio")
            email.focus()
            return;
        }

        if (telefono.value.length == 0) {
            alert("El telefono del cartero no puede estar vacio")
            telefono.focus()
            return;
        }

        if (tipo_cartero.value == 0 || tipo_cartero.value == "") {
            alert("Debe seleccionar el tipo de cartero")
            tipo_cartero.focus()
            return;
        }

        
        if (pais.value == 0 || pais.value == "") {
            alert("Debe seleccionar el país")
            pais.focus()
            return;
        }

        if (sucursal.value == 0 || sucursal.value == "") {
            alert("Debe seleccionar la sucursal del cartero")
            sucursal.focus()
            return;
        }

        if (condicion_fiscal.value == 0 || condicion_fiscal.value == "") {
            alert("Debe seleccionar la condición fiscal")
            condicion_fiscal.focus()
            return;
        }

        if (dni.value.length == 0) {
            alert("El DNI/CUIT/CUIL del cartero no puede estar vacio")
            dni.focus()
            return;
        }


        if (provincia.value == 0 || provincia.value == "") {
            alert("Debe seleccionar la provincia/departamento")
            provincia.focus()
            return;
        }

        if (localidad.value == 0 || localidad.value == "") {
            alert("Debe seleccionar el municion/localidad")
            localidad.focus()
            return;
        }

        if (domicilio.value.length == 0) {
            alert("El domicilio del cartero no puede estar vacio")
            domicilio.focus()
            return;
        }

        if (altura.value.length == 0) {
            alert("La altura del domicilio no puede estar vacio")
            altura.focus()
            return;
        }

        if (codigo_postal.value.length == 0) {
            alert("El codigo postal del cartero no puede estar vacio")
            codigo_postal.focus()
            return;
        }

        console.log("todos los campos completos...")

        document.getElementById('carteros').submit()
    }

    async function cargarSucursales(event){
        try {
            console.log("buscando sucursales....")
            sucursal.innerHTML = "<option value='0'>Seleccione una opci&oacute;n</option>"
            sucursal.disabled = true

            const pais = event.target.value
            const url = `<?php echo base_url() ?>sucursales/sucursales_carteros/getSucursales?pais=${pais}`

            const resp = await fetch(url)
            const data = await resp.json()

            if (data.length > 0) {
                let html = '';

                for (let index = 0; index < data.length; index++) {
                    html += `<option value="${data[index].id}">${data[index].nombre}</option>`
                }

                sucursal.insertAdjacentHTML('beforeend', html);
                sucursal.disabled = false
            } else {
                sucursal.innerHTML = "<option value='0' disabled>No existen sucursales cargadas</option>"
                sucursal.disabled = false
            }

        } catch (error) {
            console.log(error)
        }
    }

    async function cargarProvincias(event) {
        try {
            const provincia = document.getElementById('provincia')
            provincia.innerHTML = "<option value='0'>Seleccione una opci&oacute;n</option>"
            provincia.disabled = true

            const localidad = document.getElementById('localidad')
            localidad.innerHTML = "<option value='0'>Seleccione una opci&oacute;n</option>"
            localidad.disabled = true

            const pais = event.target.value
            const url = `<?php echo base_url() ?>zonas/zonas/getDepartamentos/${pais}`

            const resp = await fetch(url)
            const data = await resp.json()

            if (data.length > 0) {
                let html = '';

                for (let index = 0; index < data.length; index++) {
                    html += `<option value="${data[index].id}">${data[index].nombre}</option>`
                }

                provincia.insertAdjacentHTML('beforeend', html);
                provincia.disabled = false
            } else {
                provincia.innerHTML = "<option value='0' disabled>No existen provincias/departamentos cargados</option>"
                provincia.disabled = false
            }

        } catch (error) {
            console.log(error)
        }
    }

    async function cargarLocalidades(event) {
        try {
            const localidad = document.getElementById('localidad')
            localidad.innerHTML = "<option value='0'>Seleccione una opci&oacute;n</option>"
            localidad.disabled = true

            const provincia = event.target.value
            const url = `<?php echo base_url() ?>zonas/zonas/getLocalidades?provincias=${provincia}`

            const resp = await fetch(url)
            const data = await resp.json()

            if (data) {
                let html = '';

                for (let index = 0; index < data.length; index++) {
                    html += `<option value="${data[index].idLocalidad}">${data[index].localidad}</option>`
                }

                document.getElementById('localidad').insertAdjacentHTML('beforeend', html);
                localidad.disabled = false
            }

        } catch (error) {
            console.log(error)
        }
    }

    $(document).ready(function() {

        //INICIALIZANDO EL SELECT DE PAISES
        $('#pais').select2();

        //INICIALIZANDO EL SELECT DE PROVINCIAS
        $('#provincia').select2();

        //INICIALIZANDO EL SELECT DE LOCALIDADES
        $('#localidad').select2();

        //INICIALIZANDO EL SELECT DE CONDICION FISCAL
        $('#condicion_fiscal').select2();

        //INICIALIZANDO EL SELECT DE SUCURSALES
        $('#sucursal_id').select2();

        $('#cuit').mask('99-99999999-9');

        $('#cuit').blur(function(event) {
            cuit = $('#cuit').val();
            flag = true;
            if (typeof(cuit) === 'undefined')
                flag = true;
            cuit = cuit.toString().replace(/[-_]/g, "");
            if (cuit === '')
                flag = true; //No estamos validando si el campo esta vacio, eso queda para el "required"
            if (cuit.length !== 11)
                flag = false;
            else {
                var mult = [5, 4, 3, 2, 7, 6, 5, 4, 3, 2];
                var total = 0;
                for (var i = 0; i < mult.length; i++) {
                    total += parseInt(cuit[i]) * mult[i];
                }
                var mod = total % 11;
                var digito = mod === 0 ? 0 : mod === 1 ? 9 : 11 - mod;
            }
            flag = digito === parseInt(cuit[10]);
            if (!flag) {
                var message = $("<label id='cuit-message' style='color:red;margin-left:10px;font-weight:normal;line-height:.8em;font-size:10px'>El CUIT no es correcto</label>");
                $('input#cuit').after(message);
                $('#cuit-message').show();
                $('#cuit-message').delay(2000).fadeOut('slow');
                $('#cuit').val('');
            }

        })

    });
</script>