<div class="col-xs-12">
    <div class="ibox-content">
        <form action="<?=base_url('piezas/consulta_bolsas_piezas/filter')?>" method="post" id="form_consulta_bolsas_piezas">
            <div class="row">
                <div class="col-xs-6">
                  <div class="form-group">
                    <label for="cliente">Cliente</label>
                    <input id="cliente" name="cliente" type="text" class="form-control filtro-group" placeholder="Cliente" autocomplete="off" value="<?php echo $cliente?>"/>
                    <input id="cliente_id" name="cliente_id" type="hidden" value="<?php echo $cliente_id?>"/>
                  </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-3">
                    <div class="form-group">
                        <label for="periodo_desde">Periodo desde</label>
                        <input id="periodo_desde" name="periodo_desde" type="text" class="form-control datepicker" placeholder="Seleccione una fecha" value="<?php if($periodo_desde) echo $periodo_desde->format('d-m-Y') ; ?>"  required autocomplete="off" />
                    </div>
                </div>
                <div class="col-xs-3">
                    <div class="form-group">
                        <label for="periodo_hasta">Periodo hasta</label>
                        <input id="periodo_hasta" name="periodo_hasta" type="text" class="form-control datepicker" placeholder="Seleccione una fecha" value="<?php if($periodo_hasta) echo $periodo_hasta->format('d-m-Y') ; ?>"  required autocomplete="off" />
                    </div>
                </div>
            </div>
            <div class="text-right">
                <a class="btn btn-primary filter" href="javascript:;" ><i class="fa fa-arrow-down"></i> Buscar</a>
                <a class="btn btn-success exportarAExcel" href="javascript:;" ><i class="fa fa-arrow-down"> </i>Exportar a Excel</a>
            </div>
            <div>&nbsp;</div>
            <div class="ibox-content" style="overflow-x: auto;height: 500px">        
                <table id="results" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Nro. Comp.</th>
                            <th>Fecha. Comp.</th>
                            <th>Servicio</th>
                            <th>Piezas/Servicio</th>
                            <?php foreach ($sucursales as $value) {
                                    $nombre_sucursal = (str_replace(" ", "",ucwords(strtolower($value->nombre))));
                                    $nombre_sucursal = (str_replace("Flash", "",$nombre_sucursal));
                                    $nombre_sucursal = (str_replace("-", "",$nombre_sucursal));  
                                    echo "<th>$nombre_sucursal</th>";
                            }?>
                            <th>P. con HDR</th>
                            <th>P. sin HDR</th>
                            <!--piezas en estado de rendicion (2) pero que no tienen hdr-->
                            <?php foreach ($sucursales as $value) {
                                    $nombre_sucursal_upper = (str_replace(" ", "",($value->nombre)));
                                    $nombre_sucursal_upper = (str_replace("FLASH", "",$nombre_sucursal_upper));
                                    $nombre_sucursal_upper = (str_replace("-", "",$nombre_sucursal_upper));
                                    echo "<th>$nombre_sucursal_upper</th>";
                            }?>
                        </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($results as $result) { ?>
                          <tr>
                            <td><?php echo $result['Cliente'] ?> </td>
                            <td><?php echo $result['NumeroComprobante']?></td>
                            <td><?php echo $result['FechaComprobante'] ?> </td>
                            <td><?php echo $result['Servicio']?></td>
                            <td><?php echo $result['Cantid_Piez_Servicio'] ?> </td>
                            <?php foreach ($sucursales as $value) {
                                    $nombre_sucursal = (str_replace(" ", "",ucwords(strtolower($value->nombre))));
                                    $nombre_sucursal = (str_replace("Flash", "",$nombre_sucursal));
                                    $nombre_sucursal = (str_replace("-", "",$nombre_sucursal));  
                                    echo "<th>$result[$nombre_sucursal]</th>";
                            }?>
                            <td><?php echo $result['Piez_Con_HR'] ?> </td>
                            <td><?php echo $result['Piez_Sin_HR']?></td>
                            <?php foreach ($sucursales as $value) {
                                    $nombre_sucursal_upper = (str_replace(" ", "",($value->nombre)));
                                    $nombre_sucursal_upper = (str_replace("FLASH", "",$nombre_sucursal_upper));
                                    $nombre_sucursal_upper = (str_replace("-", "",$nombre_sucursal_upper));
                                    echo "<th>$result[$nombre_sucursal_upper]</th>";
                            }?>
                            
                          </tr>
                      <?php } ?>
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</div>
<form method='post' action='<?php echo base_url('piezas/consulta_bolsas_piezas/exportarBolsaPiezasAExcel')?>' id='exportAExcel'>
    <input type='hidden' id='excel_desde' name='excel_desde' value="">
    <input type='hidden' id='excel_hasta' name='excel_hasta' value="">
    <input type='hidden' id='excel_cliente_id' name='excel_cliente_id' value="">
</form>
<script type="text/javascript">
    $(document).ready(function() {
        $('#cliente').typeahead({
            source: <?=json_encode($clientes) ?>,
            displayField: 'nombre',
            onSelect: function(item) {
                $('#cliente_id').val('');
                $('#cliente').val('');
                if (item.value) {
                    $('#cliente_id').val(item.value);
                }
            }
        });
        
        $('.filter').click(function(event) {
            $('#form_consulta_bolsas_piezas').submit();
        });    
    
     $('.exportarAExcel').click(function(event) {
        $('#excel_desde').val($('#periodo_desde').val());
        $('#excel_hasta').val($('#periodo_hasta').val());
        $('#excel_cliente_id').val($('#cliente_id').val());
        $('#exportAExcel').submit();
    });
    });
</script>