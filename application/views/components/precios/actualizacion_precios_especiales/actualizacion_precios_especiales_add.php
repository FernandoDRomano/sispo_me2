<div class="col-xs-12">
    <div class="ibox-content">
	<?php     
		echo form_open(current_url(), array('class'=>""));
		echo form_hidden('enviar_form','1');
               // echo form_input(array('name' => 'cliente_id', 'type'=>'hidden', 'id' =>'cliente_id', 'value' =>$clientes[0]->id));
                echo form_input(array('name' => 'servicios_count', 'type'=>'hidden', 'id' =>'servicios_count'));
	?>
        <div class="ibox-content">
            <div class="form-group">
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="cliente_id">Cliente<span class="required">*</span></label>
                        <select id="cliente_id" name="cliente_id" class="form-control"></select>
                    </div>
                </div>
            </div>

            <div class="control-group">
              <div class="controls text-right">
                <a class="btn btn-success btn-seleccionar" href="#"><i class="fa fa-arrow-circle-down"></i> Seleccionar</a>
                <?php echo form_button(array('type'  =>'submit','value' =>'Guardar','name'  =>'submit','class' =>'btn btn-success btn-confirmar disabled'), "<i class='fa fa-floppy-o'></i> Guardar"); ?> 
                <a class="btn btn-danger" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2); ?>"><i class="fa fa-arrow-circle-left"></i> Volver</a>
              </div>
            </div>
	</div>
        <div class="ibox-content" id="listado">	
            <div id="body-grilla-load"></div>
            <table class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
                <thead>
                    <tr>
                        <th class="col-xs-2"><a href="#">Nombre Real</a></th>
                        <th class="col-xs-1"><a href="#">Precio Lista</a></th>
                        <th class="col-xs-4"><a href="#">Nombre Especial</a></th>
                        <th class="col-xs-2"><a href="#">Precio Especial</a></th>
                        <th class="col-xs-1"><a href="#">Días</a></th>
                        <th class="col-xs-2"><a href="#">Estado</a></th>
                    </tr>
                </thead>
                <tbody id="body-grilla">

                </tbody>
            </table>
        </div>
		<div class="control-group">
		  <div class="controls">
		    <?php echo form_button(array('type'  =>'submit','value' =>'Guardar','name'  =>'submit','class' =>'btn btn-success'), "<i class='fa fa-floppy-o'></i> Guardar"); ?> 
		    <a class="btn btn-danger" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2); ?>"><i class="fa fa-arrow-circle-left"></i> Volver</a>
		  </div>
		</div>
		<?php echo form_close(); ?>
	</div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('#cliente_id').select2({
            placeholder: '-----seleccionar-----',
            ajax: {
              url: '<?php echo base_url()."ajax/clientes/autocompletarClientes" ?>',
              dataType: 'json',
              delay: 250,
              processResults: function (data) {//alert(JSON.stringify(data));
                return {
                  results: data
                };
              },
              cache: true
            }
          });
        

        $('#cliente_id').on("select2:select", function(e) {
            $.ajax({
                type: "POST",
                url: '<?php echo base_url()."ajax/Precios/getActualizacionesPendientesXCliente" ?>/'+$('#cliente_id').val(),
                data: '',
                cache: false,
                datatype:'json',
                beforeSend: function() {
                    //$('#errores').html('<h3><i class="fa fa-spinner fa-spin"></i></h3>');
                    //$('.btn').addClass('disabled');
                },
                success: function(respuesta){
                    var obj = $.parseJSON(respuesta);
                    if (obj !== null){
                        $("<span class='error-tool-tip' style='width:200px'><b>Existen Actualizaciones Pendientes</b><span>").insertBefore($('.btn-seleccionar'));
                       $('.error-tool-tip').delay(3000).fadeOut('slow');
                       $('.btn-seleccionar').attr("disabled", true);
                    }else{$('.btn-seleccionar').attr("disabled", false);}
                }
            });
        });
          
//        $('#cliente').typeahead({
//        source: clientes,
//        onSelect: function(item) {
//            if (item.value) {
//                    $('#cliente_id').val(item.value);
//                    $('#cliente').val(item.value);
//                        $.ajax({
//                            type: "POST",
//                            url: '<?php echo base_url()."ajax/Precios/getActualizacionesPendientesXCliente" ?>/'+item.value,
//                            data: '',
//                            cache: false,
//                            datatype:'json',
//                            beforeSend: function() {
//                                //$('#errores').html('<h3><i class="fa fa-spinner fa-spin"></i></h3>');
//                                //$('.btn').addClass('disabled');
//                            },
//                            success: function(respuesta){
//                                var obj = $.parseJSON(respuesta);
//                                if (obj !== null){
//                                    $("<span class='error-tool-tip' style='width:200px'><b>Existen Actualizaciones Pendientes</b><span>").insertBefore($('.btn-seleccionar'));
//                                   $('.error-tool-tip').delay(3000).fadeOut('slow');
//                                   $('.btn-seleccionar').attr("disabled", true);
//                                }else{$('.btn-seleccionar').attr("disabled", false);}
//                            }
//                        });
//                }
//            }
//    });
    
    $('.btn-seleccionar').click(function(event) {
            var id = $('#cliente_id').val();
            $('#body-grilla').html('');
            $('#body-grilla-load').html('<div class="text-center"><h3><i class="fa fa-spinner fa-spin"></i></h3></div>');
            
            // precio especial
            var url = '<?php echo base_url() ?>' + 'ajax/precios/precios_especiales_x_cliente/'+id;
            var options = '';
            $.getJSON(url, function(data) {
                $('#servicios_count').val(data.length);
                $.each(data, function(key, val) {
                            var seleccion = "";
                            if (val.activo == 0) {
                                seleccion = '<option value="0" selected>Inactivo</option>\n\
                                            <option value="1" >Activo</option>';
                            }else{
                                if (val.activo == 1) {
                                    seleccion = '<option value="0">Inactivo</option>\n\
                                                <option value="1" selected>Activo</option>';
                                }
                            }
                            options = '<tr id="grilla-servicio-'+val.id+'">\n\
                                        <td>'+val.nombre_servicio+'<br>('+val.codigo_servicio+')</td>\n\
                                        <td>'+val.precio_lista+'</td>\n\
                                        <td>\n\
                                        <div class="form-group">\n\
                                            <div class="input-group" style="width:100%">\n\
                                                <input type="text" class="form-control" id="nombre_'+val.id+'" name="nombre_'+val.id+'" value="'+val.nombre+'" required >\n\
                                            </div>\n\
                                        </td>\n\
                                        <td>\n\
                                        <div class="form-group">\n\
                                            <div class="input-group">\n\
                                                <div class="input-group-addon">$</div>\n\
                                                    <input type="number" class="form-control" id="precio_'+val.id+'" name="precio_'+val.id+'" value="'+val.precio+'" step="0.01" required >\n\
                                                </div>\n\
                                            </div>\n\
                                        </td>\n\
                                        <td>\n\
                                        <div class="form-group">\n\
                                            <div class="input-group">\n\
                                                    <input type="number" class="form-control" id="dias_'+val.id+'" name="dias_'+val.id+'" value="'+val.dias+'" step="1" required >\n\
                                            </div>\n\
                                        </td>\n\
                                        <td>\n\
                                        <div class="form-group">\n\
                                            <div class="input-group">\n\
                                                <select id="activo_'+val.id+'" name="activo_'+val.id+'" class="form-control chosen-select">\n\
                                                    '+seleccion+'\n\
                                                </select>\n\
                                            </div>\n\
                                        </td>\n\
                                    </tr>';
                        $("#body-grilla").append(options);
                });
            });

            $('.btn-confirmar').removeClass('disabled');
            
            $('#body-grilla-load').html('');
    });
});
</script>