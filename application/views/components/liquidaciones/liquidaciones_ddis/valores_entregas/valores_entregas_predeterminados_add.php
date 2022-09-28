<div class="col-xs-12">
    <div class="ibox-content">
        <div class="panel blank-panel">
            <div class="panel-heading">
                <div id="tabs_hdr" class="panel-options">
                  <ul class="nav nav-tabs">
                    <li><a href="<?=base_url('liquidaciones/valores_entregas/add')?>">Valores Especiales</a></li>
                    <li class="active"><a  data-toggle="tab" href="#tab-1" href="<?=base_url('liquidaciones/valores_entregas/add_predeterminado')?>">Valores Predeterminados</a></li>
                  </ul>
                </div>
              </div>
            <div class="panel-body">
                <div class="tab-content">
                    <div id="tab-1" class="tab-pane active">
                    <form class="form_valores_entregas_predeterminados_add" id="form_valores_entregas_predeterminados_add" name="form_valores_entregas_predeterminados_add" action="<?php echo base_url('liquidaciones/valores_entregas/add_predeterminado')?>" method="post" enctype="multipart/form-data">
                        <input type='hidden' id='enviar_form' name='enviar_form' value="1">
                        <div class="row">
                            <div class="col-xs-9">
                                <div class="form-group">
                                        <label for="cartero_id">Cartero</label>
                                        <input id="cartero" name="cartero" type="text" class="form-control filtro-group" placeholder="" autocomplete="off" value=""/>
                                        <input id="cartero_id" name="cartero_id" type="hidden" value=""/>
                                </div>
                            </div>
                            <div class="col-xs-3">
                                <div class="form-group">
                                    <label for="valor">Valor</label>
                                    <input id="valor" name="valor" type="text" class="form-control" placeholder="0.000" required autocomplete="off" value="<?php echo $valor?>"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                              <div class="form-group">
                                <label for="observaciones">Observaciones</label>
                                <textarea id="observaciones" name="observaciones" class="form-control" placeholder="" rows="3" <?php echo $observaciones?>></textarea>
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
                        <th>Valor</th>
                        <th>Habilitado</th>
                        <th style="width: 80px"></th>
                    </tr>
                </thead>
                <tbody id="body-grilla">
                    <?php foreach ($results as $result) { ?>
                        <tr id="resultados" class="nueva">
                            <th><?=$result->id?></th>
                            <td><?=$result->cartero?></td>
                            <td style="text-align: right">$ <?=$result->valor?></td>
                            <td><?=$result->habilitado?></td>
                            <td>
                                <div class="btn-group">
                                  <!--<?php if($permisos_efectivos->update==1) { ?><a data-toggle="modal" href="<?php echo base_url().'liquidaciones/valores_entregas/edit_predeterminado/'.$result->id?>" data-target="#myModal" class="btn btn-success"><i class="fa fa-edit"></i></a><?php } ?>-->
                                  <?php if($permisos_efectivos->delete==1) { ?><a onClick="javascript:eleminarRegistro('<?php echo base_url().'liquidaciones/valores_entregas/delete_predeterminado/'.$result->id ?>')" href="#" class="btn btn-danger"><i class="fa fa-trash-o"></i></a><?php } ?>
                                </div>
                            </td>
                        </tr>
                    <?php }?>
                </tbody>
            </table>
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
    function eleminarRegistro(link){
          bootbox.confirm("Desea eliminar el registro?", function(result) {
            if (result === true) {
                $.ajax({
                    type: "GET",
                    url: link,
                    data: {},
                    cache: false,
                    success: function(){window.location.reload();}
                });
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
   
    
    

});    
</script>
