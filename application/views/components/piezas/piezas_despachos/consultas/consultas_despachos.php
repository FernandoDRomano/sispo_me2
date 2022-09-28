<div class="col-xs-12">
    <div class="ibox-content">
        <form action="<?=base_url('piezas/despachos/consultas_despachos_filtro')?>" method="post" id="form-filtro">
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
                        <label for="dias">Mayor a:</label>
                        <input type="text" id="dias" name="dias" class="form-control filtro-group"  autocomplete="off" value="<?=$dias?>">
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
                    <th><a href="#">Cliente</a></th>
                    <th><a href="#">Fecha Ing.</a></th>
                    <th><a href="#">Despacho</a></th>
                    <th><a href="#">Fecha</a></th>
                    <th><a href="#">Origen</a></th>
                    <th><a href="#">Destino</a></th>
                    <th><a href="#">Servicio</a></th>
                    <th><a href="#">Cantidad</a></th>
                    <th><a href="#">Estados</a></th>
                    <th><a href="#">Demora</a></th>
                </tr>
            </thead>
            <tbody>
              <?php foreach ($results as $result) {
                  $start_ts = strtotime($result->fecha_ingreso);
                  $end_ts = strtotime($result->create);
                  $diff = $end_ts - $start_ts;
                  $diff = round($diff / 86400);
                  if ($dias != ''){
                    if ($diff > $dias){?>
                    <tr>
                      <td><?php echo $result->cliente ?></td>
                      <td><?php echo $result->fecha_ingreso ?></td>
                      <td><?php echo $result->despacho_id ?></td>
                      <td><?php echo $result->create ?></td>
                      <td><?php echo $result->origen ?><br></td>
                      <td><?php echo $result->destino ?></td>
                      <td><?php echo $result->servicio?></td>
                      <td><?php echo $result->piezas ?></td>
                      <td><?php echo $result->estado ?></td>
                      <td><?php echo $diff ?></td>
                    </tr>
            <?php   }
                  }else{?>
                    <tr>
                      <td><?php echo $result->cliente ?></td>
                      <td><?php echo $result->fecha_ingreso ?></td>
                      <td><?php echo $result->despacho_id ?></td>
                      <td><?php echo $result->create ?></td>
                      <td><?php echo $result->origen ?><br></td>
                      <td><?php echo $result->destino ?></td>
                      <td><?php echo $result->servicio?></td>
                      <td><?php echo $result->piezas ?></td>
                      <td><?php echo $result->estado ?></td>
                      <td><?php echo $diff ?></td>
                    </tr>
             <?php       } 
                  }?>
            </tbody>
        </table>
    </div>
</div>
<form method='post' action='<?php echo base_url('piezas/despachos/exportar_consulta_despachos')?>' id='exportAExcel'>
    <input type='hidden' id='excel_desde' name='excel_desde' value="">
    <input type='hidden' id='excel_hasta' name='excel_hasta' value="">
    <input type='hidden' id='excel_dias' name='excel_dias' value="">
</form>
<script type="text/javascript">
$(document).ready(function() {
        $('.exportarAExcel').click(function(event) {
            $('#excel_desde').val($('#periodo_desde').val());
            $('#excel_hasta').val($('#periodo_hasta').val());
            $('#excel_dias').val($('#dias').val());
            $('#exportAExcel').submit();
        });
    });
</script>