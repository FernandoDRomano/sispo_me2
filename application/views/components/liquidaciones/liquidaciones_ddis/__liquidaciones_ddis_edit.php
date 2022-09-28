<div class="col-xs-12">
    <div class="ibox-content">
        <div class="panel blank-panel">
            <div class="panel-heading">
              <div id="tabs_hdr" class="panel-options">
                <ul class="nav nav-tabs">
                      <li class="active"><a href="<?=base_url('liquidaciones/liquidaciones_ddis')?>">Liq. HDRs</a></li>
                      <li><a href="<?=base_url('liquidaciones/liquidaciones_ddis_acreditaciones_detalle/edit/'.$liquidacion->id)?>">Liq. Acreditaciones</a></li>
                      <li><a href="<?=base_url('liquidaciones/liquidaciones_ddis_descuentos_detalle/edit/'.$liquidacion->id)?>">Liq. Descuentos</a></li>
                </ul>
              </div>
            </div>
        <div class="panel-body">
            <div class="tab-content">
              <div id="tab-1" class="tab-pane active">
                <form class="form_liquidacion_carteros_add" id="form_liquidacion_carteros_add" name="form_liquidacion_ddis_add" action="<?php echo base_url('liquidaciones/liquidaciones_ddis/edit/'.$liquidacion->id)?>" method="post" enctype="multipart/form-data">
                    <input type='hidden' id='enviar_form' name='enviar_form' value="1">
                    <input type='hidden' id='liquidacion_ddi_id' name='liquidacion_ddi_id' value="<?php echo $liquidacion->id?>">
                    <div class="row">
                        <div class="col-xs-3">
                            <div class="form-group">
                              <label for="sucursal">Sucursal</label>
                              <select id="sucursal_id" name="sucursal_id" class="form-control filtro-group">
                                  <option value="<?=$sucursal->id ?>"><?=$sucursal->nombre ?></option>
                              </select>
                            </div>
                        </div>
                        <div class="col-xs-3">
                            <div class="form-group">
                                <label for="autorizadores">Autorizadores</label>
                              <select id="liquidacion_ddi_autorizador_id" name="liquidacion_ddi_autorizador_id" class="form-control filtro-group">
                                <option value="">Seleccione</option>
                                <?php foreach ($autorizadores as $autorizador): ?>
                                    <?php if($autorizador_id){ ?>
                                        <option value="<?=$autorizador->id ?>" <?php echo $autorizador!= '' && $autorizador->id == $autorizador_id?"selected":"" ?>><?php echo $autorizador->apellido.', '.$autorizador->nombre ?></option>
                                    <?php }else{ ?>
                                        <option value="<?=$autorizador->id ?>" <?php echo $autorizador!= '' && $autorizador->id == $results[0]->autorizador_id?"selected":"" ?>><?php echo $autorizador->apellido.', '.$autorizador->nombre ?></option> ?>  
                                    <?php } ?>
                                <?php endforeach; ?>
                              </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-3">
                            <div class="form-group">
                                <label for="periodo_desde">Periodo desde</label>
                                <input id="periodo_desde" name="periodo_desde" type="text" class="form-control datepicker" placeholder="Seleccione una fecha" value="<?php if ($periodo_desde){ $f = strtotime($periodo_desde);echo date('d-m-Y',$f );} ?>"  required autocomplete="off" />
                            </div>
                        </div>
                        <div class="col-xs-3">
                            <div class="form-group">
                                <label for="periodo_hasta">Periodo hasta</label>
                                <input id="periodo_hasta" name="periodo_hasta" type="text" class="form-control datepicker" placeholder="Seleccione una fecha" value="<?php if ($periodo_hasta){ $f = strtotime($periodo_hasta);echo date('d-m-Y',$f );} ?>"  required autocomplete="off" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-3">
                            <div class="form-group">
                                <label for="periodo_alta_desde">Periodo Alta desde</label>
                                <input id="periodo_alta_desde" name="periodo_alta_desde" type="text" class="form-control datepicker" placeholder="Seleccione una fecha" value="<?php if ($periodo_alta_desde){ $f = strtotime($periodo_alta_desde);echo date('d-m-Y',$f );} ?>"  required autocomplete="off" />
                            </div>
                        </div>
                        <div class="col-xs-3">
                            <div class="form-group">
                                <label for="periodo_alta_hasta">Periodo Alta hasta</label>
                                <input id="periodo_alta_hasta" name="periodo_alta_hasta" type="text" class="form-control datepicker" placeholder="Seleccione una fecha" value="<?php if ($periodo_alta_hasta){ $f = strtotime($periodo_alta_hasta);echo date('d-m-Y',$f );} ?>"  required autocomplete="off" />
                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                      <div class="controls">
                        <?php if($permisos_efectivos->delete==1) { ?><a onClick="actualizar_listado_hdrs_ddis_pendientes('<?php echo base_url().'liquidaciones/liquidaciones_ddis/addFiltroEdit/'?>')" href="#" class="btn btn-info"><i class="fa fa-refresh"></i>Actualizar</a><?php } ?>
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
                                <th>H.D.R</th>
                                <th>Cod. Servicio</th>
                                <th>Servicio</th>
                                <th>Cantidad</th>
                                <th>Precio Entrega</th>
                                <!--<th></th>-->
                            </tr>
                        </thead>
                        <tbody id="body-grilla">
                            <?php foreach ($results as $result) { ?>
                                <tr id="resultados" class="nueva">
                                    <td><?=$result->hdr_id?></td>
                                    <td><?=$result->servicio_id?></td>
                                    <td><?=$result->servicio?></td>
                                    <td><?=$result->cantidad_piezas?></td>
                                    <td style="text-align: right">$ <?=$result->precio_cliente?></td>
<!--                                    <td>
                                        <div class="btn-group">
                                          <?php if($permisos_efectivos->delete==1) { ?><a onClick="eleminarRegistro('<?php echo base_url().'liquidaciones_ddis_acreditaciones_detalle/delete/'.$result->id ?>')" href="#" class="btn btn-danger"><i class="fa fa-check-square-o"></i></a><?php } ?>
                                        </div>
                                    </td>-->
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
<form method='post' action='<?php echo base_url('liquidaciones/liquidaciones_ddis/addFiltroEdit')?>' id='filtro'>
    <input type='hidden' id='filtro_fecha_desde' name='filtro_fecha_desde' value="">
    <input type='hidden' id='filtro_fecha_hasta' name='filtro_fecha_hasta' value="">
    <input type='hidden' id='filtro_alta_desde' name='filtro_alta_desde' value="">
    <input type='hidden' id='filtro_alta_hasta' name='filtro_alta_hasta' value="">
    <input type='hidden' id='filtro_sucursal_id' name='filtro_sucursal_id' value="">
    <input type='hidden' id='filtro_enviar_form' name='filtro_enviar_form' value="">
    <input type='hidden' id='filtro_autorizador_id' name='filtro_autorizador_id' value="">
    <input type='hidden' id='filtro_liquidacion_ddi_id' name='filtro_liquidacion_ddi_id' value="">
</form>   
<script type="text/javascript">
    function actualizar_listado_hdrs_ddis_pendientes(link){
      bootbox.confirm("Esta accion recarga el listado de HDRs pentientes, para guardar el nuevo listado presione GUARDAR?", function(result) {
        if (result === true) {
                $('#filtro_fecha_desde').val($('#periodo_desde').val());
                $('#filtro_fecha_hasta').val($('#periodo_hasta').val());
                $('#filtro_alta_desde').val($('#periodo_alta_desde').val());
                $('#filtro_alta_hasta').val($('#periodo_alta_hasta').val());
                $('#filtro_sucursal_id').val($('#sucursal_id').val());
                $('#filtro_enviar_form').val($('#enviar_form').val());
                $('#filtro_autorizador_id').val($('#liquidacion_ddi_autorizador_id').val());
                $('#filtro_liquidacion_ddi_id').val($('#liquidacion_ddi_id').val());
                $('#filtro').submit();
        }
      });
    }
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
    
    
</script>