<?php     
    $attributes = array('id' => 'clientes');
    echo form_open(base_url('clientes/clientes/add/clientes_ctacte_edit'), $attributes);
    echo form_hidden('enviar_form','1');
    echo form_hidden('servicio_id','');
?>    
    <div class="col-xs-12">
        <div class="ibox-content">    	
            <div class="row">
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="cliente_estado_id">Estado</label>
                        <select id="cliente_estado_id" name="cliente_estado_id" class="form-control chosen-select" >
                            <?php foreach ($estados as $f) { ?>
                                <option value="<?php echo $f->id ?>"><?php echo $f->nombre ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="tipo_cliente_id">Tipo</label>
                        <select id="tipo_cliente_id" name="tipo_cliente_id" class="form-control chosen-select" >
                            <?php foreach ($tipos as $f) { ?>
                                <option value="<?php echo $f->id ?>" <?php echo $f->id == 2?"selected":"disabled='disabled'"?>><?php echo $f->nombre ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="nombre">Nombre<span class="required">*</span></label>
                        <input required id="nombre" name="nombre" type="text" class="form-control" placeholder="Nombre" />
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="nombre_fantasia">Nombre fantasia</label>
                        <input  id="nombre_fantasia" name="nombre_fantasia" type="text" class="form-control" placeholder="Nombre fantasia" />
                    </div>
                </div>
            </div>
    		
            <div class="row">
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="iva">IVA</label>
                        <select id="iva_id" name="iva_id" class="form-control chosen-select" >
                            <?php foreach ($iva as $f) { ?>
                                <option value="<?php echo $f->id ?>"><?php echo $f->descripcion ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="cuit">Cuit</label>
                        <input  id="cuit" name="cuit" type="text" class="form-control" placeholder="Cuit" />
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="codigo_postal">Codigo postal</label>
                        <input  id="codigo_postal" name="codigo_postal" type="text" class="form-control" placeholder="Codigo postal" />
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="provincia">Provincia</label>
                        <input id="provincia" name="provincia" type="text" class="form-control" placeholder="Provincia" autocomplete="off"/>
                        <input id="provincia_id" name="provincia_id" type="hidden" value="0" />
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="localidad">Localidad</label>
                        <input  id="localidad" name="localidad" type="text" class="form-control" placeholder="Localidad" autocomplete="off"/>
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="domicilio">Domicilio</label>
                        <input  id="domicilio" name="domicilio" type="text" class="form-control" placeholder="Domicilio" />
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="telefonos">Telefonos</label>
                        <input  id="telefonos" name="telefonos" type="text" class="form-control tags" placeholder="Telefonos" />
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="fecha_ingreso">Fecha ingreso</label>
                        <input  id="fecha_ingreso" name="fecha_ingreso" type="text" class="form-control datepicker" placeholder="Fecha ingreso" value="<?=  date('d-m-Y')?>" />
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="ejecutivo_comercial">Ejecutivo comercial</label>
                        <select id="ejecutivo_comercial" name="ejecutivo_comercial" class="form-control chosen-select" >
                            <option value="-1"><?php echo '' ?></option>
                            <?php foreach ($ejecutivo_comercial as $f) { ?>
                                <option value="<?php echo $f->id ?>"><?php echo $f->username ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="observaciones">Observaciones</label>
                <textarea  id="observaciones" name="observaciones" class="form-control" placeholder="Observaciones"></textarea>
            </div>
    	</div>
    </div>
    <div class="col-xs-12">
        <div class="ibox-content">
            <div class="row">
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="contacto_contacto">Contacto<span class="required">*</span></label>
                        <input required id="contacto_contacto" name="contacto_contacto" type="text" class="form-control" placeholder="Contacto" />
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="contacto_telefonos">Telefonos</label>
                        <input  id="contacto_telefonos" name="contacto_telefonos" type="text" class="form-control tags" placeholder="Telefonos" />
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="contacto_emails">Emails</label>
                        <input  id="contacto_emails" name="contacto_emails" type="text" class="form-control" placeholder="Emails" data-role="tagsinput" />
                    </div>
                </div>
            </div>     
        </div>
    </div>
    <div class="col-xs-12">
        <div class="ibox-content">
            <div class="row">
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="responsable_responsable">Responsable<span class="required">*</span></label>
                        <input required id="responsable_responsable" name="responsable_responsable" type="text" class="form-control" placeholder="Responsable" />
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="responsable_telefonos">Telefonos</label>
                        <input  id="responsable_telefonos" name="responsable_telefonos" type="text" class="form-control tags" placeholder="Telefonos" />
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="responsable_emails">Emails</label>
                        <input  id="responsable_emails" name="responsable_emails" type="text" class="form-control" placeholder="Emails" data-role="tagsinput"/>
                    </div>
                </div>
            </div>    
        </div>
    </div>
    <div class="col-xs-12">
        <div class="ibox-content">
            <div class="control-group">
              <div class="controls">
                <?php echo form_button(array('type'  =>'submit','value' =>'Guardar','name'  =>'submit','class' =>'btn btn-success'), "<i class='fa fa-floppy-o'></i> Guardar"); ?> 
                <a class="btn btn-danger" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/clientes_cta_cte'; ?>"><i class="fa fa-arrow-circle-left"></i> Volver</a>
              </div>
            </div>
        </div>
    </div>

<script type="text/javascript">
$(document).ready(function() {
    
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