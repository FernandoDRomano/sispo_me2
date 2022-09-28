<div class="col-xs-12">
    <div class="ibox-content">
        <form action="<?=base_url('piezas/comprobantes_ingresos/consulta_precio_cliente_servicios_filtro')?>" method="post" id="form-filtro">
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
                    <th><a href="#">Liq.</a></th>
                    <th><a href="#">Provincia</a></th>
                    <th><a href="#">Cliente</a></th>
                    <th><a href="#">Departamento</a></th>
                    <th><a href="#">Servicio</a></th>
                    <th><a href="#">Grupo</a></th>
                    <th><a href="#">Fecha</a></th>
                    <th><a href="#">Fact. Nro.</a></th>
                    <th><a href="#">C.I.</a></th>
                    <th><a href="#">Piezas</a></th>
                    <th><a href="#">Precio Unitario</a></th>
                </tr>
            </thead>
            <tbody>
              <?php foreach ($results as $result) { ?>
                  <tr>
                    <td><?php echo $result->liquidacion_id ?>
                    <td><?php echo $result->sucursal ?></td>
                    <td><?php echo $result->cliente ?></td>
                    <td><?php echo $result->departamento ?></td>
                    <td><?php echo $result->servicio ?></td>
                    <td><?php echo $result->grupo ?></td>
                    <td><?php echo $result->fecha_creacion ?></td>
                    <td><?php echo $result->factura ?></td>
                    <td><?php echo $result->numero ?></td>
                    <td><?php echo $result->piezas ?></td>
                    <td style="text-align: right"><?php echo $result->precio ?> ($)</td>
                  </tr>
              <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<form method='post' action='<?php echo base_url('piezas/comprobantes_ingresos/exportar_consulta_precio_cliente_servicios')?>' id='exportAExcel'>
    <input type='hidden' id='excel_desde' name='excel_desde' value="">
    <input type='hidden' id='excel_hasta' name='excel_hasta' value="">
</form>
<script type="text/javascript">
$(document).ready(function() {
        $('.exportarAExcel').click(function(event) {
            $('#excel_desde').val($('#periodo_desde').val());
            $('#excel_hasta').val($('#periodo_hasta').val());
            $('#exportAExcel').submit();
        });
    });
</script>