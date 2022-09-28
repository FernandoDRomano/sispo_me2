<div class="col-xs-12">
    <div class="ibox-content">
        <div class="panel blank-panel">
            <div class="panel-heading">
              <div id="tabs_hdr" class="panel-options">
                <ul class="nav nav-tabs">
                      <li><a href="<?=base_url('liquidaciones/liquidaciones_ddis')?>">Liq. HDRs</a></li>
                      <li class="active"><a href="<?=base_url('liquidaciones/liquidaciones_ddis_acreditaciones_detalle/edit/'.$liquidacion_ddi_id)?>">Liq. Acreditaciones</a></li>
                      <li><a href="<?=base_url('liquidaciones/liquidaciones_ddis_descuentos_detalle/edit/'.$liquidacion_ddi_id)?>">Liq. Descuentos</a></li>
                </ul>
              </div>
            </div>
        <div class="panel-body">
            <div class="tab-content">
              <div id="tab-1" class="tab-pane active">
                <form class="form_liquidaciones_ddis_acreditaciones_detalles_edit" id="form_liquidaciones_ddis_acreditaciones_detalles_edit" name="form_liquidaciones_ddis_acreditaciones_detalles_edit" action="<?php echo base_url('liquidaciones/liquidaciones_ddis_acreditaciones_detalle/edit/'.$liquidacion_ddi_id)?>" method="post">
                    <input type='hidden' id='enviar_form' name='enviar_form' value="1">
                    <input type='hidden' id='liquidacion_ddi_id' name='liquidacion_ddi_id' value="<?php echo $liquidacion_ddi_id ?>">
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label for="cartero">Cartero</label>
                                <input id="carteros" name="carteros" type="text" class="form-control filtro-group" placeholder="Carteros" autocomplete="off" value=""/>
                                <input id="cartero_id" name="cartero_id" type="hidden" value=""/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label for="cliente">Acreditaciones</label>
                              <select id="acreditacion_id" name="acreditacion_id" class="form-control filtro-group">
                                <option value="">Seleccione</option>
                                <?php foreach ($acreditaciones as $acreditacion): ?>
                                  <option value="<?=$acreditacion->id ?>"><?php echo $acreditacion->concepto ?></option>
                                <?php endforeach; ?>
                              </select>
                            </div>
                        </div>
                        <div class="col-xs-2">
                                <div class="form-group">
                                  <label for="tipo_concepto_id">Tipo de Concepto</label>
                                  <select id="tipo_concepto_id" name="tipo_concepto_id" class="form-control filtro-group" required>
                                      <option value="-1"> Seleccionar </option>
                                      <option value="1">GENERAL</option>
                                      <option value="2">INDIVIDUAL</option>
                                  </select>
                                </div>
                        </div> 
                        <div class="col-xs-3">
                            <div class="form-group">
                                <label for="importe">Importe</label>
                                <input id="importe" name="importe" type="text" class="form-control"  value=""  required autocomplete="off" />
                            </div>
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
                        <div class="col-xs-3">
                            <div class="form-group">
                                <label for="observaciones">Observaciones</label>
                                <input id="observaciones" name="observaciones" type="text" class="form-control"  value=""  autocomplete="off" />
                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                      <div class="controls">
<!--                        <a class="btn btn-primary filtro" href="javascript:;" ><i class="fa fa-arrow-down"></i> Aceptar</a>-->
                        <?php echo form_button(array('type'  =>'submit','value' =>'Guardar','name'  =>'submit','class' =>'btn btn-success'), "<i class='fa fa-floppy-o'></i> Guardar"); ?> 
                        <a class="btn btn-danger" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2); ?>"><i class="fa fa-arrow-circle-left"></i> Volver</a>
                      </div>
                    </div>
                    <div>&nbsp;</div>
                    <div id="grilla"></div>
                </form>
                </div>
                <div class="ibox-content">
                    <table id="results" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
                        <thead>
                            <tr>
                                <th>id</th>
                                <th>Cartero</th>
                                <th>Acreditación</th>
                                <th>Tipo</th>
                                <th>Importe</th>
                                <th>Autorizador</th>
                                <th>F. Autorización</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="body-grilla">
                            <?php foreach ($results as $result) { ?>
                                <tr id="resultados" class="nueva">
                                    <td><?=$result->acreditacion_ddi_detalle_id?></td>
                                    <td><?=$result->cartero?></td>
                                    <td><?=$result->acreditacion?></td>
                                    <td><?=$result->tipo_concepto?></td>
                                    <td style="text-align: right"><?=$result->importe?></td>
                                    <td><?=$result->apellido_nombre?></td>
                                    <td><?=$result->fecha_autorizacion?></td>
                                    <td>
                                        <div class="btn-group">
                                          <?php if($permisos_efectivos->update==1) { ?><a href="<?php echo base_url().'liquidaciones/liquidaciones_ddis_acreditaciones_detalle/autorizarAcreditacionDdi/'.$result->liquidacion_ddi_id.'/'.$result->acreditacion_ddi_detalle_id ?>" <?php echo $result->fecha_autorizacion!=NULL?'class="btn btn-success"':'class="btn btn-danger"'?>><i class="fa fa-check-square-o"></i></a><?php } ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php }?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
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
   function autorizar_acreditacion_ddi(acreditacion_ddi_detalle_id){
        $.ajax({
            type: "POST",
            url: '<?php echo base_url()."ajax/liquidaciones/autorizarAcreditacionDdi" ?>',
            data: 'acreditacion_ddi_detalle_id='+acreditacion_ddi_detalle_id,
            cache: false,
            datatype:'json',
            beforeSend: function() {
                //$('#errores').html('<h3><i class="fa fa-spinner fa-spin"></i></h3>');
                //$('.btn').addClass('disabled');
            },
            success: function(respuesta){alert(JSON.stringify(respuesta));
                var obj = $.parseJSON(respuesta);
                if (!obj.status){
                    $('#check_'+acreditacion_ddi_detalle_id).attr('class', 'btn btn-primary');
                }else{alert("nada");
                    $("<span class='error-tool-tip'>Error al autorizar <span>").insertAfter($('#check_'+acreditacion_ddi_detalle_id));
                    $('.error-tool-tip').delay(2000).fadeOut('slow');
                }
                //window.location.reload();
            }
        });
    };
</script>
<script type="text/javascript">
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
    
    $('#carteros').typeahead({
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
    
    $('#tipo_concepto_id').change(function(event) {
        $.ajax({
            type: "POST",
            url: '<?php echo base_url()."ajax/liquidaciones/getAcreditacionGeneral" ?>',
            data: 'acreditacion_id='+$('#acreditacion_id').val()+'&tipo_concepto_id='+$('#tipo_concepto_id').val(),
            cache: false,
            datatype:'json',
            beforeSend: function() {
                //$('#errores').html('<h3><i class="fa fa-spinner fa-spin"></i></h3>');
                //$('.btn').addClass('disabled');
            },
            success: function(respuesta){
                var obj = $.parseJSON(respuesta);
                if (!obj.status){
                    $('#importe').val(obj.grilla[0].importe);
                }else{
                    $("<span class='error-tool-tip'>No existe valor para estos datos <span>").insertAfter($('#importe'));
                    $('.error-tool-tip').delay(2000).fadeOut('slow');
                }
                //window.location.reload();
            }
        });
    });
</script>