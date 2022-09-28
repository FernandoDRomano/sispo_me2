<div class="col-xs-12">
    <div class="ibox-content">
        <div class="panel blank-panel">
            <div class="panel-heading">
                <div id="tabs_hdr" class="panel-options">
                  <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#tab-1" href="<?=base_url('liquidaciones/valores_entregas/add')?>">Valores Especiales</a></li>
                    <li><a href="<?=base_url('liquidaciones/valores_entregas/add_predeterminado')?>">Valores Predeterminados</a></li>
                  </ul>
                </div>
              </div>
            <div class="panel-body">
                <div class="tab-content">
                    <div id="tab-1" class="tab-pane active">
                    <form class="form_valores_entregas_add" id="form_valores_entregas_add" name="form_valores_entregas_add" action="<?php echo base_url('liquidaciones/valores_entregas/add')?>" method="post" enctype="multipart/form-data">
                        <input type='hidden' id='enviar_form' name='enviar_form' value="1">
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="cliente_id">Cliente</label>
                                    <input id="cliente" name="cliente" type="text" class="form-control filtro-group" placeholder="Cliente" autocomplete="off" value="<?php echo $cliente?>" required="true"/>
                                    <input id="cliente_id" name="cliente_id" type="hidden" value="<?php echo $cliente_id?>"/>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="servicios">Servicios</label>
                                    <select id="servicio_id" name="servicio_id" class="form-control filtro-group" required="true">
                                        <option value="">Seleccione</option>
                                        <?php foreach ($servicios as $servicio): ?>
                                          <option value="<?=$servicio->id ?>" <?php echo $servicio!= '' && $servicio->id == $servicio_id?"selected":"" ?>><?php echo $servicio->nombre ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="zona">Zona</label>
                                    <input id="zona" name="zona" type="text" class="form-control filtro-group" placeholder="Zona" autocomplete="off" value="<?php echo $zona?>" required="true"/>
                                    <input id="zona_id" name="zona_id" type="hidden" value="<?php echo $zona_id?>"/>
                                </div>
                            </div>
                            <div class="col-xs-3">
                                <div class="form-group">
                                    <label for="valor">Valor</label>
                                    <input id="valor" name="valor" type="text" class="form-control" placeholder="0.000" value=""  required autocomplete="off" required="true"/>
                                </div>
                            </div>
                        </div>
                        <hr/>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="cartero">Cartero</label>
                                    <input id="cartero" name="cartero" type="text" class="form-control filtro-group" placeholder="Cartero" autocomplete="off" value="<?php echo $cartero?>"/>
                                    <input id="cartero_id" name="cartero_id" type="hidden" value="<?php echo $cartero_id?>"/>
                                </div>
                            </div>
                        </div>
                        <div class="control-group">
                          <div class="controls">
                            <?php echo form_button(array('type'  =>'submit','value' =>'Guardar','name'  =>'submit','class' =>'btn btn-success'), "<i class='fa fa-floppy-o'></i> Agregar"); ?> 
                            <a class="btn btn-danger" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$this->uri->segment(3); ?>"><i class="fa fa-arrow-circle-left"></i> Volver</a>
                          </div>
                        </div>
                        <div>&nbsp;</div>
                    </form>
                </div>
            </div>
        </div>
            
                <div class="ibox-content">
                    <table id="results" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Cartero</th>
                                <th>Cliente</th>
                                <th>Servicio</th>
                                <th>Zona</th>
                                <th>Valor</th>
                                <th>Habilitado</th>
                                <th style="width: 80px"></th>
                            </tr>
                        </thead>
                        <tbody id="body-grilla">
                            <?php foreach ($results as $result) { ?>
                                <tr id="resultados" class="nueva">
                                    <th><?=$result->valor_entrega_id?></th>
                                    <td><?=$result->cartero?></td>
                                    <td><?=$result->cliente?></td>
                                    <td><?=$result->servicio?></td>
                                    <td><?=$result->zona?></td>
                                    <td style="text-align: right">$ <?=$result->valor?></td>
                                    <td><?=$result->habilitado?></td>
                                    <td>
                                        <div class="btn-group">
                                         <!-- <?php if($permisos_efectivos->update==1) { ?><a data-toggle="modal" href="<?php echo base_url().'liquidaciones/valores_entregas/edit/'.$result->valor_entrega_id?>" data-target="#myModal" class="btn btn-success"><i class="fa fa-edit"></i></a><?php } ?>-->
                                          <?php if($permisos_efectivos->delete==1) { ?><a onClick="eleminarRegistro('<?php echo $result->valor_entrega_id ?>')" href="#" class="btn btn-danger"><i class="fa fa-trash-o"></i></a><?php } ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php }?>
                        </tbody>
                    </table>
                </div>
            </div>
  
<form method='post' action='<?php echo base_url('liquidaciones/valores_entregas/delete')?>' id='form_delete'>
    <input type='hidden' id='filtro_liquidacion_ddi_id' name='filtro_liquidacion_ddi_id' value="">
</form>   
<script type="text/javascript">
    function eleminarRegistro(id){
      bootbox.confirm("Desea eliminar la rendición?", function(result) {
        if (result === true) {
            $('#filtro_liquidacion_ddi_id').val(id);
            $('#form_delete').submit();
        }
      });
    }    
$(document).ready(function() {
    $('#cliente').typeahead({
            source: <?=json_encode($clientes) ?>,
            displayField: 'nombre',
            onSelect: function(item) {
                if (item.value) {
                    $('#cliente').val(item.text);
                    $('#cliente_id').val(item.value);
                    var seleccionado = '';
                    var url = '<?php echo base_url() ?>' + 'ajax/clientes/serviciosXCliente/' + $('#cliente_id').val();
                    $.getJSON(url, function(data) {
//                        $("#servicio_id").find("option").remove();                
                        var options = '';
                        if(!data.status){
                            options = options + "<option value='-1'>Ninguno</option>";
                            $.each(data, function(key, val) {
                                 if ($('#cliente_id').val() > 0){
                                     seleccionado = '<?php echo $servicio_id ?>' === val.id?'selected':'';
                                 }
                                options = options + "<option value='"+val.id+"' "+seleccionado+">"+ val.nombre +"</option>";
                            });              
                        }else{
                            options = options + "<option value='-1' disabled>Sin resultados</option>";
                        }

                        $("#servicio_id").append(options);
                        $("#servicio_id").trigger("liszt:updated");
                    })
                }
            }
    }); 
    
    if ($('#cliente_id').val() > 0){
        var url = '<?php echo base_url() ?>' + 'ajax/clientes/serviciosXCliente/' + $('#cliente_id').val();
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

                        $("#servicio_id").append(options);
                        $("#servicio_id").trigger("liszt:updated");
                    })
                }
    
    
    $('#zona').typeahead({
        source: <?=json_encode($zonas) ?>,
        displayField: 'nombre',
        onSelect: function(item) {
            if (item.value) {
                $('#zona').val(item.text);
                $('#zona_id').val(item.value);
            }
        }
    });
    
    $('#cartero').typeahead({
        source: <?=json_encode($carteros) ?>,
        displayField: 'apellido_nombre',
        onSelect: function(item) {
            if (item.value) {
                $('#cartero').val(item.text);
                $('#cartero_id').val(item.value);
            }
        }
    });
   
     
    $(".form_valores_entregas_add").validate({
        rules: {
            cliente_id: {required: true},
            servicio_id: {required: true},
            zona_id: {required: true},
            valor: {required: true},
        
        },
        messages: {
            liquidacion_ddi_id: "Campo requerido.",
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
