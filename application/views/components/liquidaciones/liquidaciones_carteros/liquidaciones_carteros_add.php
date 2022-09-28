<div class="col-xs-12">
    <div class="ibox-content">
    <form class="form_liquidacion_carteros_add" id="form_liquidacion_carteros_add" name="form_liquidacion_carteros_add" action="<?php echo base_url('liquidaciones/liquidaciones_carteros/add')?>" method="post" enctype="multipart/form-data">
        <input type='hidden' id='enviar_form' name='enviar_form' value="1">
        <div class="row">
            <div class="col-xs-3">
                <div class="form-group">
                  <label for="sucursal">Sucursal</label>
                  <select id="sucursal_id" name="sucursal_id" class="form-control filtro-group">
                    <option value="">Seleccione</option>
                    <?php foreach ($sucursales as $sucursal): ?>
                      <option value="<?=$sucursal->id ?>" <?php echo $sucursal_id!= '' && $sucursal_id == $sucursal->id?"selected":"" ?>><?=$sucursal->nombre ?></option>
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
            <a class="btn btn-primary filtro" href="javascript:;" ><i class="fa fa-arrow-down"></i> Aceptar</a>
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
                    <th>Fecha</th>
                    <th>Cod. Servicio</th>
                    <th>Servicio</th>
                    <th>Cantidad</th>
                    <th>Precio Clientes</th>
                </tr>
            </thead>
            <tbody id="body-grilla">
                <?php foreach ($results as $result) { ?>
                    <tr id="resultados" class="nueva">
                        <td><?=$result->hdr_id?></td>
                        <td><?=$result->fecha?></td>
                        <td><?=$result->codigo_servicio?></td>
                        <td><?=$result->servicio?></td>
                        <td><?=$result->cantidad_piezas?></td>
                        <td style="text-align: right">$ <?=$result->precio_cliente?></td>
                    </tr>
                <?php }?>
            </tbody>
        </table>
    </div>
</div>
<form method='post' action='<?php echo base_url('liquidaciones/liquidaciones_carteros/addFiltro')?>' id='filtro'>
    <input type='hidden' id='filtro_fecha_desde' name='filtro_fecha_desde' value="">
    <input type='hidden' id='filtro_fecha_hasta' name='filtro_fecha_hasta' value="">
    <input type='hidden' id='filtro_alta_desde' name='filtro_alta_desde' value="">
    <input type='hidden' id='filtro_alta_hasta' name='filtro_alta_hasta' value="">
    <input type='hidden' id='filtro_sucursal_id' name='filtro_sucursal_id' value="">
    <input type='hidden' id='filtro_enviar_form' name='filtro_enviar_form' value="">
</form>   
<script type="text/javascript">
    $('.filtro').click(function(event) {
        $('#filtro_fecha_desde').val($('#periodo_desde').val());
        $('#filtro_fecha_hasta').val($('#periodo_hasta').val());
        $('#filtro_alta_desde').val($('#periodo_alta_desde').val());
        $('#filtro_alta_hasta').val($('#periodo_alta_hasta').val());
        $('#filtro_sucursal_id').val($('#sucursal_id').val());
        $('#filtro_enviar_form').val($('#enviar_form').val());
        $('#filtro').submit();
    });
    
</script>