<?php     
    $attributes = array('id' => 'clientes');
    echo form_open_multipart(current_url(), $attributes);
    echo form_hidden('enviar_form','1');
    echo form_hidden('servicio_id','');
?>    
    <style>
        .hr{
            border-color: #a8a8a8;
            margin-top: 10px;
        }

        .tituloFormulario {
            color: #697882;
            font-size: 22px;
            font-weight: 400;
            border-bottom: 2px solid #ADCE9D!important;
            padding-bottom: 7px;    
            margin-bottom: 2rem;
            margin-top: 1.3rem;
        }
    </style>

    <div class="col-xs-12">
        <div class="ibox-content">    	
            <div class="row">
                <div class="col-xs-12">
                    <h3 class="tituloFormulario">Cliente</h3>
                </div>
                <!--
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="cliente_estado_id">Estado:</label>
                        <select id="cliente_estado_id" name="cliente_estado_id" class="form-control chosen-select" >
                            <?php foreach ($estados as $f) { ?>
                                <option value="<?php echo $f->id ?>"><?php echo $f->nombre ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                -->
        
                <div class="col-xs-12">
                    <div class="form-group">
                        <label for="nombre">Nombre / Razón Social: <span class="required">*</span></label>
                        <input required id="nombre" name="nombre" type="text" class="form-control" placeholder="Nombre" />
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="nombre_fantasia">Nombre fantasia:</label>
                        <input  id="nombre_fantasia" name="nombre_fantasia" type="text" class="form-control" placeholder="Nombre fantasia" />
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="telefonos">Telefonos:</label>
                        <input  id="telefonos" name="telefonos" type="text" class="form-control tags" placeholder="Telefonos" />
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="fecha_ingreso">Fecha ingreso:</label>
                        <input  id="fecha_ingreso" name="fecha_ingreso" type="text" class="form-control datepicker" placeholder="Fecha ingreso" value="<?=  date('d-m-Y')?>" />
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="ejecutivo_comercial">Ejecutivo comercial: </label>
                        <input type="text" name="ejecutivo_comercial" id="ejecutivo_comercial" class="form-control" placeholder="Ingrese el ejecutivo comercial">
                        <!-- 
                        <label for="ejecutivo_comercial">Ejecutivo comercial: </label>
                        <select id="ejecutivo_comercial" name="ejecutivo_comercial" class="form-control chosen-select" >
                            <option value="-1"><?php echo '' ?></option>
                            <?php foreach ($ejecutivo_comercial as $f) { ?>
                                <option value="<?php echo $f->id ?>"><?php echo $f->username ?></option>
                            <?php } ?>
                        </select>
                        -->
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="tipo_cliente_id">Tipo:</label>
                        <select id="tipo_cliente_id" name="tipo_cliente_id" class="form-control chosen-select" >
                            <?php foreach ($tipos as $f) { ?>
                                <option value="<?php echo $f->id ?>"><?php echo $f->nombre ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="col-xs-12">
                    <h3 class="tituloFormulario">Ubicación</h3>
                </div>
                <div class="col-xs-12">
                    <div class="form-group">
                        <label for="pais">País: <span class="required">*</span></label>
                        <select onchange="cargarProvincias(event)" name="pais" id="pais" class="form-control">
                            <option value="0">Seleccione una opción</option>
                            <?php foreach($paises as $p) { ?>
                                <option value="<?php echo $p->id ?>"><?php echo $p->nombre?></option>
                            <?php }?>
                        </select>
                        <!-- <input id="pais" name="pais" type="text" class="form-control" placeholder="Provincia" autocomplete="off"/>
                        <input id="pais_id" name="pais_id" type="hidden" value="0" />-->
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="provincia">Provincia / Departamento: <span class="required">*</span></label>
                        <select onchange="cargarLocalidades(event)" disabled name="provincia" id="provincia" class="form-control">
                            <option value="0">Seleccione una opción</option>
                        </select>
                        <!--<input id="provincia" name="provincia" type="text" class="form-control" placeholder="Provincia" autocomplete="off"/>
                        <input id="provincia_id" name="provincia_id" type="hidden" value="0" />-->
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="localidad">Localidad / Municipio: <span class="required">*</span></label>
                        <select disabled name="localidad" id="localidad" class="form-control">
                            <option value="0">Seleccione una opción</option>
                        </select>
                        <!-- <input  id="localidad" name="localidad" type="text" class="form-control" placeholder="Localidad" autocomplete="off"/> -->
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="codigo_postal">Codigo postal: </label>
                        <input  id="codigo_postal" name="codigo_postal" type="text" class="form-control" placeholder="Codigo postal" />
                    </div>
                </div>
                <div class="col-xs-8">
                    <div class="form-group">
                        <label for="domicilio">Calle: <span class="required">*</span></label>
                        <input  id="domicilio" name="domicilio" type="text" class="form-control" placeholder="Calle" />
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="altura">Altura: <span class="required">*</span></label>
                        <input  id="altura" name="altura" type="text" class="form-control" placeholder="Altura" />
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="esquina_1">Esquina: </label>
                        <input  id="esquina_1" name="esquina_1" type="text" class="form-control" placeholder="" />
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="esquina_2">Esquina 2: </label>
                        <input  id="esquina_2" name="esquina_2" type="text" class="form-control" placeholder="" />
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="piso">Piso: </label>
                        <input  id="piso" name="piso" type="text" class="form-control" placeholder="" />
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="departamento">Departamento/Oficina: </label>
                        <input  id="departamento" name="departamento" type="text" class="form-control" placeholder="" />
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="form-group">
                        <label for="observaciones">Observaciones: </label>
                        <textarea  id="observaciones" name="observaciones" class="form-control" placeholder="Observaciones"></textarea>
                    </div>
                </div>
    		
                <div class="col-xs-12">
                    <h3 class="tituloFormulario">Datos Fiscales</h3>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="iva">Condición fiscal: <span class="required">*</span></label>
                        <select id="iva_id" name="iva_id" class="form-control chosen-select" >
                            <?php foreach ($iva as $f) { ?>
                                <option value="<?php echo $f->id ?>"><?php echo $f->descripcion ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="cuit">CUIT / CUIL / otro: <span class="required">*</span></label>
                        <input  id="cuit" name="cuit" type="text" class="form-control" placeholder="Cuit" />
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="dni">DNI: <span class="required">*</span></label>
                        <input  id="dni" name="dni" type="text" class="form-control" placeholder="DNI" />
                    </div>
                </div>

                
            
    	    </div>
        </div>
    </div>

    <div class="col-xs-12">
        <div class="ibox-content">
            <div class="row">
                <div class="col-xs-12">
                    <h3 class="tituloFormulario">Usuario</h3>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="username">Nombre de usuario: <span class="required">*</span></label>
                        <input type="text" name="username" id="username" class="form-control" placeholder="Ingrese el nombre del usuario" required >
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="email">Email: <span class="required">*</span></label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="Ingrese el email del usuario" required >
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="password">Contraseña: <span class="required">*</span></label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Ingrese la contraseña">
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="password_confirm">Repetir Contraseña: <span class="required">*</span></label>
                        <input type="password" name="password_confirm" id="password_confirm" class="form-control" placeholder="Repetir la contraseña">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xs-12">
        <div class="ibox-content">
            <div class="row">
                <div class="col-xs-12">
                    <h3 class="tituloFormulario">Contacto</h3>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="contacto_contacto">Nombre y apellido: <span class="required">*</span></label>
                        <input required id="contacto_contacto" name="contacto_contacto" type="text" class="form-control" placeholder="Contacto" />
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="contacto_telefonos">Telefonos: </label>
                        <input  id="contacto_telefonos" name="contacto_telefonos" type="text" class="form-control tags" placeholder="Telefonos" />
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="contacto_emails">Emails: </label>
                        <input  id="contacto_emails" name="contacto_emails" type="text" class="form-control" placeholder="Emails" data-role="tagsinput" />
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="responsable_responsable">Responsable: <span class="required">*</span></label>
                        <input required id="responsable_responsable" name="responsable_responsable" type="text" class="form-control" placeholder="Responsable" />
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="responsable_telefonos">Telefonos: </label>
                        <input  id="responsable_telefonos" name="responsable_telefonos" type="text" class="form-control tags" placeholder="Telefonos" />
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="responsable_emails">Emails: </label>
                        <input  id="responsable_emails" name="responsable_emails" type="text" class="form-control" placeholder="Emails" data-role="tagsinput"/>
                    </div>
                </div>

                <div class="col-xs-12">
                    <h3 class="tituloFormulario">Archivo</h3>
                </div>
                <div class="col-xs-12">
                    <div class="form-group">
                        <label for="archivo">Archivo</label>
                        <input type="file" name="archivo" id="archivo" class="form-control" accept="application/pdf">
                    </div>
                </div>

            </div>     
        </div>
    </div>

    <div class="col-xs-12">
        <div class="ibox-content">
            <div class="control-group">
              <div class="controls">
                <?php echo form_button(array('onclick' => 'validar()', 'type'  =>'submit','value' =>'Guardar','name'  =>'btnSubmit','class' =>'btn btn-success'), "<i class='fa fa-floppy-o'></i> Guardar"); ?> 
                <a class="btn btn-danger" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2); ?>"><i class="fa fa-arrow-circle-left"></i> Volver</a>
              </div>
            </div>
        </div>
    </div>

<script type="text/javascript">

    const nombre = document.getElementById('nombre')
    const condicion_fiscal = document.getElementById('iva_id')
    const cuit = document.getElementById('cuit')
    const dni = document.getElementById('dni')
    const pais = document.getElementById('pais')
    const provincia = document.getElementById('provincia')
    const localidad = document.getElementById('localidad')
    const domicilio = document.getElementById('domicilio')
    const altura = document.getElementById('altura')
    const username = document.getElementById('username')
    const email = document.getElementById('email')
    const password = document.getElementById('password')
    const password_confirm = document.getElementById('password_confirm')
    const contacto = document.getElementById('contacto_contacto')
    const responsable = document.getElementById('responsable_responsable')
    const form = document.getElementById('clientes')

    function validar(){
        event.preventDefault()
        
        if(nombre.value.length == 0){
            alert("El Nombre/Razón Social del cliente no puede estar vacio")
            nombre.focus()
            return;  
        }

        if(condicion_fiscal.value == 0 || condicion_fiscal.value == ""){
            alert("Debe seleccionar la Condición fiscal")
            condicion_fiscal.focus()
            return;  
        }

        if(cuit.value.length == 0){
            alert("El CUIT/CUIL del cliente no puede estar vacio")
            cuit.focus()
            return;  
        }

        if(dni.value.length == 0){
            alert("El DNI del cliente no puede estar vacio")
            dni.focus()
            return;  
        }

        if(pais.value == 0 || pais.value == ""){
            alert("Debe seleccionar el país")
            pais.focus()
            return;  
        }

        if(provincia.value == 0 || provincia.value == ""){
            alert("Debe seleccionar la provincia/departamento")
            provincia.focus()
            return;  
        }

        if(localidad.value == 0 || localidad.value == ""){
            alert("Debe seleccionar el municion/localidad")
            localidad.focus()
            return;  
        }

        if(domicilio.value.length == 0){
            alert("El domicilio del cliente no puede estar vacio")
            domicilio.focus()
            return;  
        }

        if(altura.value.length == 0){
            alert("La altura del cliente no puede estar vacio")
            altura.focus()
            return;  
        }

        if(username.value.length == 0){
            alert("El nombre del usuario no puede estar vacio")
            username.focus()
            return;  
        }

        if(email.value.length == 0){
            alert("El email del usuario no puede estar vacio")
            email.focus()
            return;  
        }

        if(password.value.length == 0){
            alert("La contraseña no puede estar vacia")
            password.focus()
            return;
        }

        if(password_confirm.value.length == 0){
            alert("La confirmación de la contraseña no puede estar vacia")
            password_confirm.focus()
            return;
        }

        if(password.value != password_confirm.value){
            alert('Las contraseña y su confimación no coinciden')
            password.value = ""
            password_confirm.value = ""
            password.focus()
            return;
        }

        if(contacto.value.length == 0){
            alert("El contacto del cliente no puede estar vacio")
            contacto.focus()
            return;  
        }

        if(responsable.value.length == 0){
            alert("El responsable del cliente no puede estar vacio")
            responsable.focus()
            return;  
        }

        console.log("todos los campos completos...")

        document.getElementById('clientes').submit()
    }

    async function cargarProvincias(event){
        try {
            const provincia = document.getElementById('provincia')
            provincia.innerHTML = "<option value='0'>Seleccione una opción</option>"
            provincia.disabled = true
                
            const localidad = document.getElementById('localidad')
            localidad.innerHTML = "<option value='0'>Seleccione una opción</option>"
            localidad.disabled = true
            
            const pais = event.target.value
            const url = `<?php echo base_url() ?>zonas/zonas/getDepartamentos/${pais}`
            
            const resp = await fetch(url)
            const data = await resp.json()
            
            if(data.length > 0){
                let html = '';
                
                for (let index = 0; index < data.length; index++) {
                    html += `<option value="${data[index].id}">${data[index].nombre}</option>`
                }
                
                provincia.insertAdjacentHTML('beforeend', html);
                provincia.disabled = false
            }else{
                provincia.innerHTML = "<option value='0' disabled>No existen provincias/departamentos cargados</option>"
                provincia.disabled = false
            }

        } catch (error) {
            console.log(error)
        }
    }

    async function cargarLocalidades(event){
        try {
            const localidad = document.getElementById('localidad')
            localidad.innerHTML = "<option value='0'>Seleccione una opción</option>"
            localidad.disabled = true

            const provincia = event.target.value
            const url = `<?php echo base_url() ?>zonas/zonas/getLocalidades?provincias=${provincia}`

            const resp = await fetch(url)
            const data = await resp.json()
            
            if(data){                
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
    
    $('#contacto_emails').on('beforeItemAdd', function(event) {
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if (!regex.test(event.item)){
            event.cancel = true;
            var message = $("<label id='contacto-email-message' style='color:red;margin-left:10px;font-weight:normal;line-height:.8em;font-size:10px'>El email tiene un formato inválido</label>");
            $('input#contacto_emails').after(message);
            $( "#contacto-email-message" ).show();
            $( "#contacto-email-message" ).delay(1000).fadeOut('slow');
            //$('#contacto-email-message').remove();
        }
    });
    
     $('#responsable_emails').on('beforeItemAdd', function(event) {
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if (!regex.test(event.item)){
            event.cancel = true;
            var message = $("<label id='responsable-email-message' style='color:red;margin-left:10px;font-weight:normal;line-height:.8em;font-size:10px'>El email tiene un formato inválido</label>");
            $('input#responsable_emails').after(message);
            $('#responsable-email-message').show();
            $('#responsable-email-message').delay(1000).fadeOut('slow');
            //$('#contacto-email-message').remove();
        }
    });
    
    $('#cuit').mask('99-99999999-9');
    
    $('#cuit').blur(function(event) {
        cuit = $('#cuit').val();
        flag = true;
        if (typeof (cuit) === 'undefined')
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
        if (!flag){
            var message = $("<label id='cuit-message' style='color:red;margin-left:10px;font-weight:normal;line-height:.8em;font-size:10px'>El CUIT no es correcto</label>");
            $('input#cuit').after(message);
            $('#cuit-message').show();
            $('#cuit-message').delay(2000).fadeOut('slow');
            $('#cuit').val('');
        }
        
    })
    
    var provincias = [<?php foreach ($provincias as $f) echo "{ id: ".$f->id.", name: '".$f->nombre."'}, " ?>]; 

    $('#provincia').typeahead({
        source: provincias,
        onSelect: function(item) {            
            $('#provincia_id').val(0);
            $('#localidad').val('');

            if (item.value) {
                $('#provincia_id').val(item.value);
            }
        }
    });

    $('#provincia').change(function(event) {
        if ($('#provincia').val() == 'Result not Found' || $('#provincia').val() == '' || $('#provincia_id').val() == 0) {
            $('#provincia').val('').focus();
            $('#provincia_id').val(0);
        }
    });

    $('#localidad').typeahead({
        displayField: 'nombre',
        ajax: {
            url: '<?php echo base_url()."ajax/ubicacion/departamentos" ?>',
            method: "GET",
            loadingClass: "loading-ajax",
            preDispatch: function (query) {
                var provincia = $('#provincia_id').val();
                return {
                    search: query,
                    provincia: provincia
                }
            }
        },
        onSelect: function(item) {            
            
        }
    });

    function validarFormatoFecha(campo) {
        dia = campo.substring(0,2);
        mes = campo.substring(3,5);
        anio = campo.substring(6,10);
        mensaje = "";
        if (dia < 1 && dia >31) mensaje = 'Formato de fecha incorrecto';
        if (mes < 1 && mes >12) mensaje = 'Formato de fecha incorrecto';
        if (anio.length !== 4) mensaje = 'Formato de fecha incorrecto';
        if (mensaje !== ''){
            var message = $("<label id='cuit-message' style='color:red;margin-left:10px;font-weight:normal;line-height:.8em;font-size:10px'>El CUIT no es correcto</label>");
            $('input#cuit').after(message);
            $('#cuit-message').show();
            $('#cuit-message').delay(2000).fadeOut('slow');
            $('#fecha_ingreso').val('');
       }
    }
        
        $('#fecha_ingreso').blur(function(event) {
            var fecha_ingreso = $('#fecha_ingreso').val();
            var dia = fecha_ingreso.substring(0,2);
            var mes = fecha_ingreso.substring(3,5);
            var anio = fecha_ingreso.substring(6,10);
            var mensaje = "";
            if (dia < 1 && dia >31) mensaje = 'Formato de fecha incorrecto';
            if (mes < 1 && mes >12) mensaje = 'Formato de fecha incorrecto';
            if (anio.length !== 4) mensaje = 'Formato de fecha incorrecto';
            if (mensaje !== ''){
                var message = $("<label id='fecha-message' style='color:red;margin-left:10px;font-weight:normal;line-height:.8em;font-size:10px'>Fecha Incorrecta.</label>");
                $('input#fecha_ingreso').after(message);
                $('#fecha-message').show();
                $('#fecha-message').delay(2000).fadeOut('slow');
                $('#fecha_ingreso').val('');
           }
        });
});
</script>