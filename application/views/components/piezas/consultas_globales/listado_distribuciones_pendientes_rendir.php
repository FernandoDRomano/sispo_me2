<div class="col-xs-12">
    <div class="ibox-content">
        <form action="<?=base_url('consultas/Listado_distribucion/consultas_distribucion_filtro')?>" method="post" id="form-filtro">
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
            <div class="row">
                <div class="col-xs-3">
                    <div class="form-group">
                        <label for="dias">Mayor a  (cantidad de días)</label>
                        <input id="mayor_a" name="mayor_a" type="text" class="form-control spinner" value="<?php echo $mayor_a==""?0:$mayor_a ?>"  required autocomplete="off" />
                    </div>
                </div>
                <div class="col-xs-3">
                    <div class="form-group">
                        <label for="dias">Menor a (cantidad de días)</label>
                        <input id="menor_a" name="menor_a" type="text" class="form-control spinner" value="<?php echo $menor_a==""?0:$menor_a ?>"  required autocomplete="off" />
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
                    <th><a href="#">Fecha Ing.</a></th>
                    <th><a href="#">Pieza</a></th>
                    <th><a href="#">C.I.</a></th>
                    <th><a href="#">Cliente</a></th>
                    <th><a href="#">Servicio</a></th>
                    <th><a href="#">Cartero</a></th>
                    <th><a href="#">H.R.</a></th>
                    <th><a href="#">Fecha Creación</a></th>
                    <th><a href="#">Destinatario</a></th>
                    <th><a href="#">Domicilio</a></th>
                    <th><a href="#">C.P.</a></th>
                    <th><a href="#">Estado</a></th>
                </tr>
            </thead>
            <tbody>
              <?php foreach ($results as $result) { ?>
                  <tr>
                    <td><?php echo $result->fecha_ingreso ?>
                    <td><?php echo $result->pieza_id ?></td>
                    <td><?php echo $result->numero ?></td>
                    <td><?php echo $result->cliente ?></td>
                    <td><?php echo $result->servicio ?></td>
                    <td><?php echo $result->cartero ?></td>
                    <td><?php echo $result->hoja_ruta_id ?></td>
                    <td><?php echo $result->fecha_creacion ?></td>
                    <td><?php echo $result->destinatario ?></td>
                    <td><?php echo $result->domicilio ?></td>
                    <td><?php echo $result->codigo_postal ?></td>
                    <td><?php echo $result->estado ?></td>
                  </tr>
              <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<form method='post' action='<?php echo base_url('consultas/Listado_distribucion/exportar_consulta_distribucion')?>' id='exportAExcel'>
    <input type='hidden' id='excel_desde' name='excel_desde' value="">
    <input type='hidden' id='excel_hasta' name='excel_hasta' value="">
    <input type='hidden' id='excel_sucursal_id' name='excel_sucursal_id' value="">
    <input type='hidden' id='excel_mayor_a' name='excel_mayor_a' value="">
    <input type='hidden' id='excel_menor_a' name='excel_menor_a' value="">
</form>
<script type="text/javascript">
$(document).ready(function() {
        $('.exportarAExcel').click(function(event) {
            $('#excel_desde').val($('#periodo_desde').val());
            $('#excel_hasta').val($('#periodo_hasta').val());
            $('#excel_sucursal_id').val($('#sucursal_id').val());
            $('#excel_mayor_a').val($('#mayor_a').val());
            $('#excel_menor_a').val($('#menor_a').val());
            $('#exportAExcel').submit();
        });
    });
</script>