<div class="col-xs-12">
    <div class="ibox-content">
	<form class="form_liquidacion_ddis_descuentos_add" id="form_liquidacion_ddis_descuentos_add" 
                name="form_liquidacion_ddis_descuentos_add" action="<?php echo base_url('liquidaciones/liquidaciones_ddis_descuentos/add')?>" method="post">
                    <?php   echo form_hidden('enviar_form','1');?>                              
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="cartero">Cartero</label>
                                    <input id="cartero" name="cartero" type="text" class="form-control filtro-group" placeholder="Cartero" autocomplete="off" value="<?php echo $cartero?>"/>
                                    <input id="cartero_id" name="cartero_id" type="hidden" value="<?php echo $cartero_id?>"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="concepto">Concepto</label>
                                    <input id="concepto" name="concepto" type="text" class="form-control filtro-group" placeholder="Concepto" autocomplete="off" />
                                </div>
                            </div>
                            <div class="col-xs-3">
                                <div class="form-group">
                                    <label for="importe">Importe</label>
                                    <input id="importe" name="importe" type="text" class="form-control filtro-group" placeholder="Importe" autocomplete="off"/>
                                </div>
                            </div>
                            <div class="col-xs-3">
                                <div class="form-group">
                                  <label for="tipo_concepto">Tipo de Concepto</label>
                                  <select id="tipo_concepto" name="tipo_concepto" class="form-control filtro-group" required>
                                      <option value="-1"> Seleccionar </option>
                                      <option value="G">GENERAL</option>
                                      <option value="I">INDIVIDUAL</option>
                                  </select>
                                </div>
                            </div> 
                        </div>
                        <div class="row">
                            <div class="col-xs-3">
                                <div class="form-group">
                                    <label for="valido_desde">Válido Desde</label>
                                    <input id="valido_desde" name="valido_desde" type="text" class="form-control datepicker" placeholder="Seleccione una fecha" value="<?php if ($valido_desde){ $f = strtotime($valido_desde);echo date('d-m-Y',$f );} ?>"  required autocomplete="off" />
                                </div>
                            </div>
                            <div class="col-xs-3">
                                <div class="form-group">
                                    <label for="valido_hasta">Válido Hasta</label>
                                    <input id="valido_hasta" name="valido_hasta" type="text" class="form-control datepicker" placeholder="Seleccione una fecha" value="<?php if ($valido_hasta){ $f = strtotime($valido_hasta);echo date('d-m-Y',$f );} ?>"  required autocomplete="off" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="autorizadores">Autorizadores</label><?php echo $autorizador_id ?>
                                  <select id="liquidacion_ddi_autorizador_id" name="liquidacion_ddi_autorizador_id" class="form-control filtro-group">
                                    <option value="">Seleccione</option>
                                    <?php foreach ($autorizadores as $autorizador): ?>
                                      <option value="<?=$autorizador->id ?>" <?php echo $autorizador!= '' && $autorizador->id == $autorizador_id?"selected":"" ?>><?php echo $autorizador->apellido.', '.$autorizador->nombre ?></option>
                                    <?php endforeach; ?>
                                  </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="observaciones">Observaciones</label>
                                        <input id="observaciones" name="observaciones" type="text" class="form-control filtro-group"  autocomplete="off" />
                                    </div>
                                </div>
                        </div>
                        <div class="control-group">
                            <div class="controls">
                                <?php echo form_button(array('type'  =>'submit','value' =>'Guardar','name'  =>'submit','class' =>'btn btn-success','id' => 'guardar'), "<i class='fa fa-floppy-o'></i> Guardar"); ?> 
                                <a class="btn btn-danger" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2); ?>"><i class="fa fa-arrow-circle-left"></i> Volver</a>
                            </div>
                        </div>
            </form>
	</div>
        <div class="ibox-content">
        <table id="results" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cartero</th>
                    <th>Concepto</th>
                    <th>Observaciones</th>
                    <th>Monto</th>
                    <th>F.Desde</th>
                    <th>F.Hasta</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="body-grilla">
                <?php foreach ($results as $result) { ?>
                    <tr id="resultados" class="nueva">
                        <td><?=$result->descuento_id?></td>
                        <td><?=$result->cartero?></td>
                        <td><?=$result->concepto?></td>
                        <td><?=$result->observaciones?></td>
                        <td style="text-align: right"><?=$result->importe?></td>
                        <td><?=$result->fecha_desde?></td>
                        <td><?=$result->fecha_hasta?></td>
                        <td>
                            <div class="btn-group">
                              <?php if($permisos_efectivos->update==1) { ?><a href="<?php echo base_url().'liquidaciones/liquidaciones_ddis_descuentos/autorizarDescuentosDdi/'.$result->descuento_id ?>" <?php echo $result->fecha_autorizacion!=NULL?'class="btn btn-primary"':'class="btn btn-danger"'?>><i class="fa fa-check-square-o"></i></a><?php } ?>
                              <?php if($permisos_efectivos->update==1) { ?><a data-toggle="modal" href="<?php echo base_url().'liquidaciones/liquidaciones_ddis_descuentos/edit/'.$result->descuento_id?>" data-target="#myModal" class="btn btn-success"><i class="fa fa-edit"></i></a><?php } ?>
                              <?php if($permisos_efectivos->delete==1) { ?><a onClick="eleminarRegistro('<?php echo base_url().'liquidaciones/liquidaciones_ddis_descuentos/delete/'.$result->descuento_id ?>')" href="#" class="btn btn-danger"><i class="fa fa-trash-o"></i></a><?php } ?>
                            </div>
                        </td>
                    </tr>
                <?php }?>
            </tbody>
        </table>
    </div>
</div>
<script type="text/javascript">
    $('#cartero').typeahead({
        source: <?=json_encode($carteros) ?>,
        displayField: 'apellido_nombre',
        onSelect: function(item) {
            if (item.value) {
                $('#cartero').val(item.value);
                $('#cartero_id').val(item.value);
                }
        }
    });
    
    $('#autorizadores').typeahead({
        source: <?=json_encode($autorizadores) ?>,
        displayField: 'apellidonombre',
        onSelect: function(item) {
            if (item.value) {
                $('#autorizador').val(item.value);
                $('#autorizator_id').val(item.value);
                }
        }
    }); 
    
    function eleminarRegistro(link){
      bootbox.confirm("¿Desea eliminar la rendición?", function(result) {
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

</script>