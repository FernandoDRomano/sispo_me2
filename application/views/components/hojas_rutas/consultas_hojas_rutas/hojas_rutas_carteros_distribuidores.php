<div class="col-xs-12">
    <div class="ibox-content">
        <form action="<?=base_url('hojas_rutas/consultas_hojas_rutas/hdr_cartero_distribuidor_filtro')?>" method="post" id="form-filtro">
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
                      <label for="sucursal">Sucursal</label>
                      <select id="sucursal_id" name="sucursal_id" class="form-control filtro-group">
                        <?php foreach ($sucursales as $sucursal): ?>
                          <option value="<?=$sucursal->id ?>"<?=$sucursal_id == $sucursal->id ? 'selected="selected"' : ''?>><?=$sucursal->nombre ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                </div>
            </div>
            <div class="text-right">
                <?php echo form_button(array('type'  =>'submit','value' =>'Consultar','name'  =>'submit','class' =>'btn btn-success','id' => 'consultar'), "<i class='fa fa-search'></i> Consultar"); ?> 
                <a class="btn btn-success exportarAExcel" href="javascript:;" ><i class="fa fa-arrow-down"> </i>Exportar a Excel</a>
            </div>
        </form>
    </div>
    <div class="ibox-content">
        <table id="results" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
            <thead>
                <tr>
                    <th><a href="#">HDR</a></th>
                    <th><a href="#">Fecha</a></th>
                    <th><a href="#">Cartero</a></th>
                    <th><a href="#">Servicio</a></th>
                    <th><a href="#">Cantidad</a></th>
                    <th><a href="#">Fecha Baja</a></th>
<!--                    <th class="col-xs-1">&nbsp;</th>-->
                </tr>
            </thead>
            <tbody>
              <?php foreach ($results as $result) { ?>
                  <tr>
                      <td><?php echo $result->hdr_id ?></td>
                      <td><?php echo $result->fecha_entrega ?></td>
                    <td>
                      Nombre: <?php echo $result->cartero ?><br>
                      Tipo: <?php echo $result->tipo ?>
                    </td>
                    <td><?php echo $result->servicio ?></td>
                    <td><?php echo $result->piezas ?></td>
                    <td><?php echo $result->fecha_baja ?></td>
                  </tr>
              <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<form method='post' action='<?php echo base_url('hojas_rutas/consultas_hojas_rutas/exportar_hoja_de_ruta_carteros')?>' id='exportAExcel'>
    <input type='hidden' id='excel_desde' name='excel_desde' value="">
    <input type='hidden' id='excel_hasta' name='excel_hasta' value="">
    <input type='hidden' id='excel_sucursal_id' name='excel_sucursal_id' value="">
</form>
<script type="text/javascript">
$(document).ready(function() {
        $('.exportarAExcel').click(function(event) {
            $('#excel_desde').val($('#periodo_desde').val());
            $('#excel_hasta').val($('#periodo_hasta').val());
            $('#excel_sucursal_id').val($('#sucursal_id').val());
            $('#exportAExcel').submit();
        });
    });
</script>