<div class="col-xs-12">
    <div class="ibox-content">
	<form class="form_liquidacion_ddis_descuentos_edit" id="form_liquidacion_ddis_descuentos_edit" 
                name="form_liquidacion_ddis_descuentos_edit" action="<?php echo base_url('liquidaciones/liquidaciones_ddis_descuentos/edit')?>" method="post">
                    <?php   echo form_hidden('enviar_form','1');?>
                    <?php   echo form_hidden('descuento_id_p',$result->descuento_id);?>    
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label for="cartero">Cartero</label>
                                    <input id="cartero_p" name="cartero_p" type="text" class="form-control filtro-group" placeholder="Cartero" autocomplete="off" value="<?php echo $result->cartero?>"/>
                                    <input id="cartero_id_p" name="cartero_id_p" type="hidden" value="<?php echo $result->cartero_id?>"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label for="concepto">Concepto</label>
                                    <input id="concepto" name="concepto_p" type="text" class="form-control filtro-group" placeholder="Concepto" autocomplete="off" value="<?php echo $result->concepto?>"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                  <label for="tipo_concepto">Tipo de Concepto</label>
                                  <select id="tipo_concepto_p" name="tipo_concepto_p" class="form-control filtro-group" required>
                                      <option value="-1"> Seleccionar </option>
                                      <option value="G" <?php echo $result->tipo_concepto == 'GENERAL'?"selected":"" ?>>GENERAL</option>
                                      <option value="I" <?php echo $result->tipo_concepto == 'INDIVIDUAL'?"selected":"" ?>>INDIVIDUAL</option>
                                  </select>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="importe">Importe</label>
                                    <input id="importe_p" name="importe_p" type="text" class="form-control filtro-group" placeholder="Importe" autocomplete="off" value="<?php echo $result->importe?>"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="fecha_desde_p">Válido Desde</label>
                                    <input id="fecha_desde_p" name="fecha_desde_p" type="text" class="form-control datepicker" placeholder="Seleccione una fecha" value="<?php if ($result->fecha_desde){ $f = strtotime($result->fecha_desde);echo date('d-m-Y',$f );} ?>"  required autocomplete="off" />
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="fecha_hasta_p">Válido Hasta</label>
                                    <input id="fecha_hasta_p" name="fecha_hasta_p" type="text" class="form-control datepicker" placeholder="Seleccione una fecha" value="<?php if ($result->fecha_hasta){ $f = strtotime($result->fecha_hasta);echo date('d-m-Y',$f );} ?>"  required autocomplete="off" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="autorizadores">Autorizadores</label>
                                    <select id="liquidacion_ddi_autorizador_id_p" name="liquidacion_ddi_autorizador_id_p" class="form-control filtro-group">
                                      <option value="">Seleccione</option>
                                      <?php foreach ($autorizadores as $autorizador): ?>
                                        <option value="<?=$autorizador->id ?>" <?php echo $autorizador!= '' && $autorizador->id == $result->autorizador_id?"selected":"" ?>><?php echo $autorizador->apellido.', '.$autorizador->nombre ?></option>
                                      <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="observaciones">Observaciones</label>
                                        <input id="observaciones_p" name="observaciones_p" type="text" class="form-control filtro-group"  autocomplete="off" value="<?php echo $result->observaciones ?>"/>
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
</div>
<script type="text/javascript">
    $('#cartero_p').typeahead({
        source: <?=json_encode($carteros) ?>,
        displayField: 'apellido_nombre',
        onSelect: function(item) {
            if (item.value) {
                $('#cartero_p').val(item.value);
                $('#cartero_id_p').val(item.value);
                }
        }
    });
    
    $('#autorizadores_p').typeahead({
        source: <?=json_encode($autorizadores) ?>,
        displayField: 'apellidonombre',
        onSelect: function(item) {
            if (item.value) {
                $('#autorizador_p').val(item.value);
                $('#autorizator_id_p').val(item.value);
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