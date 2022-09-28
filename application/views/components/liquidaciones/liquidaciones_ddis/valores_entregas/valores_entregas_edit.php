<div class="col-xs-12">
    <div class="ibox-content">
       
                <form class="form_valores_entregas_edit" id="form_valores_entregas_edit" name="form_valores_entregas_edit" action="<?php echo base_url('liquidaciones/valores_entregas/edit/'.$result->valor_entrega_id)?>" method="post" enctype="multipart/form-data">
                    <input type='hidden' id='enviar_form' name='enviar_form' value="1">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label for="cliente_id_p">Cliente</label>
                                <input id="cliente_p" type="text" class="form-control filtro-group" placeholder="Cliente" autocomplete="off" value="<?php echo $result->cliente?>"/>
                                <input id="cliente_id_p" name="cliente_id_p" type="hidden" value="<?php echo $result->cliente_id?>"/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label for="servicios">Servicios</label>
                                <select id="servicio_id_p" name="servicio_id_p" class="form-control filtro-group">
                                    <option value="">Seleccione</option>
                                    
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label for="zona_p">Zona</label>
                                <input id="zona_p" type="text" class="form-control filtro-group" placeholder="Zona" autocomplete="off" value="<?php echo $result->zona?>"/>
                                <input id="zona_id_p" name="zona_id_p" type="hidden" value="<?php echo $result->zona_id?>"/>
                            </div>
                        </div>
                        <div class="col-xs-3">
                            <div class="form-group">
                                <label for="valor_p">Valor</label>
                                <input id="valor_p" name="valor_p" type="text" class="form-control" placeholder="0,000" value="<?php echo $result->valor?>"  required autocomplete="off" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label for="observaciones_p">Observaciones</label>
                                <input id="observaciones_p" name="observaciones_p" type="text" class="form-control" placeholder="Observaciones" value="<?php echo $result->observaciones?>"  required autocomplete="off" />
                            </div>
                        </div>
                    </div>
                    <hr/>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label for="cartero_p">Cartero</label>
                                <input id="cartero_p" name="cartero_p" type="text" class="form-control filtro-group" placeholder="Cartero" autocomplete="off" value="<?php echo $result->cartero?>"/>
                                <input id="cartero_id_p" name="cartero_id_p" type="hidden" value="<?php echo $result->cartero_id?>"/>
                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                      <div class="controls">
                        <?php echo form_button(array('type'  =>'submit','value' =>'Guardar','name'  =>'submit','class' =>'btn btn-success'), "<i class='fa fa-floppy-o'></i> Guardar"); ?> 
                        <a class="btn btn-danger" href="<?php echo base_url('/liquidaciones/valores_entregas/add'); ?>"><i class="fa fa-arrow-circle-left"></i> Volver</a>
                      </div>
                    </div>
                    <div>&nbsp;</div>
                </form>
                </div>
            </div>

<form method='post' action='<?php echo base_url('liquidaciones/liquidaciones_ddis/addFiltro')?>' id='filtro'>
    <input type='hidden' id='filtro_fecha_desde' name='filtro_fecha_desde' value="">
    <input type='hidden' id='filtro_fecha_hasta' name='filtro_fecha_hasta' value="">
    <input type='hidden' id='filtro_alta_desde' name='filtro_alta_desde' value="">
    <input type='hidden' id='filtro_alta_hasta' name='filtro_alta_hasta' value="">
    <input type='hidden' id='filtro_sucursal_id' name='filtro_sucursal_id' value="">
    <input type='hidden' id='filtro_enviar_form' name='filtro_enviar_form' value="">
    <input type='hidden' id='filtro_autorizador_id' name='filtro_autorizador_id' value="">
</form>   
<script type="text/javascript">
    
$(document).ready(function() {
               
    $('#cliente_p').typeahead({
            source: <?=json_encode($clientes) ?>,
            displayField: 'nombre',
            onSelect: function(item) {
                if (item.value) {
                    $('#cliente_p').val(item.text);
                    $('#cliente_id_p').val(item.value);
                    var seleccionado = '';
                    var url = '<?php echo base_url() ?>' + 'ajax/clientes/serviciosXCliente/' + $('#cliente_id_p').val();
                    $.getJSON(url, function(data) {
//                        $("#servicio_id").find("option").remove();                
                        var options = '';
                        if(!data.status){
                            options = options + "<option value='-1'>Ninguno</option>";
                            $.each(data, function(key, val) {
                                 if ($('#cliente_id_p').val() > 0){
                                     seleccionado = '<?php echo $servicio_id ?>' === val.id?'selected':'';
                                 }
                                options = options + "<option value='"+val.id+"' "+seleccionado+">"+ val.nombre +"</option>";
                            });              
                        }else{
                            options = options + "<option value='-1' disabled>Sin resultados</option>";
                        }
                        //Borro las opciones del select
                        $('#servicio_id_p option').each(function() {
                                $(this).remove();
                        });
                        $("#servicio_id_p").append(options);
//                        $("#servicio_id_p").val('').trigger('chosen:updated');
                    })
                }
            }
    }); 
    
    if ($('#cliente_id_p').val() > 0){
        var url = '<?php echo base_url() ?>' + 'ajax/clientes/serviciosXCliente/' + $('#cliente_id_p').val();
                    $.getJSON(url, function(data) {
//                        $("#servicio_id").find("option").remove();                
                        var options = '';

                        if(!data.status){
                            var seleccionado = '';
                            options = options + "<option value='-1'>Ninguno</option>";
                            $.each(data, function(key, val) {//alert($('#servicio_id').val()+'     '+val.id);
                               seleccionado = '<?php echo $servicio_id ?>' === val.id?'selected':'';
//                               alert(seleccionado);
                               options = options + "<option value='"+val.id+"' "+seleccionado+">"+ val.nombre +"</option>";
                            });              
                        }else{
                            options = options + "<option value='-1' disabled>Sin resultados</option>";
                        }
                        $("#servicio_id_p").append(options);
                        $("#servicio_id_p").trigger("liszt:updated");
                    })
                }
    
    
    $('#zona_p').typeahead({
        source: <?=json_encode($zonas) ?>,
        displayField: 'nombre',
        onSelect: function(item) {
            if (item.value) {
                $('#zona_p').val(item.text);
                $('#zona_id_p').val(item.value);
            }
        }
    });
    
    $('#cartero_p').typeahead({
        source: <?=json_encode($carteros) ?>,
        displayField: 'apellido_nombre',
        onSelect: function(item) {
            if (item.value) {
                $('#cartero_p').val(item.text);
                $('#cartero_id_p').val(item.value);
            }
        }
    });
    
    $('.filtro').click(function(event) {
        $('#filtro_fecha_desde').val($('#periodo_desde').val());
        $('#filtro_fecha_hasta').val($('#periodo_hasta').val());
        $('#filtro_alta_desde').val($('#periodo_alta_desde').val());
        $('#filtro_alta_hasta').val($('#periodo_alta_hasta').val());
        $('#filtro_sucursal_id').val($('#sucursal_id').val());
        $('#filtro_enviar_form').val($('#enviar_form').val());
        $('#filtro_autorizador_id').val($('#liquidacion_ddi_autorizador_id').val());
        $('#filtro').submit();
    });
});    
</script>
