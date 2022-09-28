<div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div id="errorServicios"></div>
        <div class="col-xs-12" style="background: #FFF">
            <div class="ibox-content">
                <?php     
                        echo form_open(current_url(), array('id'=>"form-servicios-especiales"));
                        echo form_hidden('enviar_form','1');
                        echo form_hidden('cliente_id',$cliente_id);
                ?>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label for="empresa">Empresa</label>
                            <input class="form-control typeahead" type="text" placeholder="Empresa" id="empresa" name="empresa" autocomplete="off">
                            <input type="hidden" id="empresa_id" name="empresa_id" value="">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-4">
                        <div class="form-group">
                            <label for="codigo">Código</label>
                            <input class="form-control typeahead" type="text" placeholder="Código" id="codigo" name="codigo" maxlength="11" autocomplete="off">
                            <input type="hidden" id="servicio_id" name="servicio_id" value="">
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label for="nombre_servicio">Nombre del Servicio</label>
                            <input class="form-control typeahead" type="text" placeholder="Nombre del Servicio" id="nombre_servicio" name="nombre_servicio"  autocomplete="off">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label for="nombre_especial_servicio">Servicio (nombre especial)</label>
                            <input id="nombre_especial_servicio" name="nombre_especial_servicio" class="form-control" />
                        </div>
                    </div>
                    <div class="col-xs-3">
                        <div class="form-group">
                            <label for="dias">Días</label>
                            <input required id="dias" name="dias" type="number" class="form-control" placeholder="Días" step="1" min="0" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-6">
                            <label for="activo">Usar precio de lista</label>
                            <div class="radio i-checks"><input type="checkbox" name="precio_lista" id="precio_lista" class="form-control" value="1"></div>
                        </div>
                    </div>
                    <div class="col-xs-3">
                        <div class="form-group">
                            <label for="precio">Precio</label>
                            <input required id="precio" name="precio" type="number" class="form-control" placeholder="Precio" step="0.01" min="0" />
                        </div>
                    </div>
                </div>
                <div class="control-group">
                  <div class="controls">
                    <?php echo form_button(array('type'  =>'submit','value' =>'Guardar','name'  =>'submit','class' =>'btn btn-success'), "<i class='fa fa-floppy-o'></i> Guardar"); ?> 
                     <span class="button btn btn-danger" data-dismiss="modal" aria-label="Close"><i class="fa fa-arrow-circle-left"></i> cancel</span>
<!--                    <a class="btn btn-danger" href="#" onclick="javascript:window.close()"><i class="fa fa-arrow-circle-left"></i> Volver</a>-->
                  </div>
                </div>
                <?php echo form_close(); ?>
        </div>
    </div>

<script type="text/javascript">
    $(document).ready(function() {
        
           
    var codigos = [<?php foreach ($servicios as $f) echo "{ id: ".$f->id.", name: '".trim($f->codigo)."'}, " ?>];
    var nombres = [<?php foreach ($servicios as $f) echo '{ id: '.$f->id.', name: "'.trim($f->nombre).'"}, ' ?>];
    var empresas = [<?php foreach ($empresas as $f) echo "{ id: ".$f->id.", name: '".trim($f->nombre)."'}, " ?>];

    $('#codigo').typeahead({
        source: codigos,
        onSelect: function(item) {
            if (item.value) {
                for (i=0 ; i < nombres.length ; i++){
                    if (parseInt(nombres[i].id) === parseInt(item.value)){
                        $('#codigo').val(item.value);
                        $('#nombre_servicio').val(nombres[i].name);
                        $('#servicio_id').val(item.value);
                    }
                }
            }
        }
    });
    
    $('#nombre_servicio').typeahead({
        source: nombres,
        onSelect: function(item) {            
            if (item.value) {
                for (i=0 ; i < codigos.length ; i++){
                    if (parseInt(codigos[i].id) === parseInt(item.value)){
                        $('#codigo').val(codigos[i].name);
                        $('#nombre_servicio').val(item.value);
                        $('#servicio_id').val(item.value);
                    }
                }
            }
        }
    });
    
    $('#empresa').typeahead({
        source: empresas,
        onSelect: function(item) {            
            if (item.value) {
                $('#empresa').val(item.value);
                $('#empresa_id').val(item.value);
            }
        }
    });
    
    $('#precio_lista').click(function() {
        if($('#precio_lista').is(':checked')){
          $('#precio').prop('disabled', true);
        }else{
          $('#precio').prop('disabled', false);
        }
    })
            
    $('#btn_borrar').click(function() {
        $( "#dialog-confirm" ).dialog({
                resizable: false,
                height:150,
                modal: true,
                buttons: {
                        'Si': function() {
                            $('#task').val('borrarCursoTaller');
                            $('#adminForm').submit();

                        },
                        'No': function() {
                                $( this ).dialog( 'close' );
                        }
                }
        });

    });
    
    $("#form-servicios-especiales").validate({
        rules: {
            servicio_id: {required: true},
            nombre_servicio: {required: true},
        },
        messages: {
            servicio_id: "Campo requerido.",
            nombre_servicio: "Campo requerido.",
        },
        highlight: function(element) {
            $(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function(element) {
            $(element).closest('.form-group').removeClass('has-error');
        },
        errorElement: 'span',
        errorClass: 'help-block',
        errorPlacement: function(error, element) {
            if(element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        }
    });
});
</script>