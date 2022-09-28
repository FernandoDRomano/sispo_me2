<div class="col-xs-12">
    <div class="ibox-content">
        <form action="<?=base_url('piezas/consulta_piezas_clientes/filter')?>" method="post" id="form_consulta_piezas_clientes">
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
                <table id="results1" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
                    <thead>
                        <tr>
                            <th>Comprobante</th>
                            <th>Fecha Comp.</th>
                            <th>Servicio</th>
                            <th>Piezas/Servicio</th>
                            <th>Ult. rendicion</th>
                            <th>Estados</th>
                            <th>Piezas Rendidas</th>
                            <th>Piezas S/Rendir</th>
                        </tr>
                    </thead>
                    <tbody>
                      <?php $comprobante_id = 0;
                            $servicio_id = 0;
                            foreach ($results as $result) { ?>
                            <tr>
                                 <?php if($result->comprobante_ingreso_id != $comprobante_id) { ?>
                                    <td><?php echo $result->numero_comprobante?></td>
                                    <td><?php echo $result->fecha_comprobante?></td>
                                <?php $comprobante_id = $result->comprobante_ingreso_id;}else{ ?>
                                    <td></td>
                                    <td></td>
                                <?php } ?>
                                <?php if($result->servicio_id != $servicio_id) { ?>
                                <td><?php echo $result->servicio?></td>
                                <td><?php echo $result->piezas_por_servicio?></td>
                                
                                <td><?php $consulta = "  SELECT DATE_FORMAT(r.create,'%d-%m-%Y') fecha_rendicion FROM `flash_comprobantes_ingresos` ci
                                                        inner join flash_piezas p on ci.id = p.comprobante_ingreso_id
                                                        inner join flash_comprobantes_ingresos_servicios cis on p.servicio_id = cis.id
                                                        inner join flash_rendiciones_piezas rp on p.id = rp.pieza_id
                                                        inner join flash_rendiciones r on r.id = rp.rendicion_id
                                                        where ci.id = $result->comprobante_ingreso_id
                                                        and cis.servicio_id = $result->servicio_id  
                                                        and r.create is not null
                                                        ORDER BY `p`.`rendicion_id`  DESC LIMIT 1";

                                            $ultima_rendicion = $this->db->query($consulta)->row();
                                            echo $ultima_rendicion->fecha_rendicion;
                                ?></td>
                                <?php $servicio_id = $result->servicio_id;}else{ ?>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                <?php } ?>
                                <td style="text-align: right"><?php echo $result->estado ?> </td>
                                <td style="text-align: right">
                                    <?php    
                                            $consulta1 = "  SELECT COUNT(*) piezas_rendidas 
                                                            FROM `flash_comprobantes_ingresos` ci
                                                            INNER JOIN flash_piezas p ON ci.id = p.comprobante_ingreso_id
                                                            INNER JOIN flash_comprobantes_ingresos_servicios cis ON p.servicio_id = cis.id
                                                            INNER JOIN flash_rendiciones_piezas rp ON p.id = rp.pieza_id
                                                            WHERE ci.id = $result->comprobante_ingreso_id
                                                            AND cis.servicio_id = $result->servicio_id
                                                            AND p.estado_id = $result->estado_id
                                                            GROUP BY p.estado_id";

                                            $result_consulta1 = $this->db->query($consulta1)->row();
                                            echo $result_consulta1->piezas_rendidas == NULL?"0":$result_consulta1->piezas_rendidas;  
                                    ?> 
                                </td>
                                <td style="text-align: right">
                                <?php    
                                        $consulta2 = "  SELECT  COUNT(*) piezas_sin_rendir 
                                                        FROM `flash_comprobantes_ingresos` ci
                                                        INNER JOIN flash_piezas p ON ci.id = p.comprobante_ingreso_id
                                                        INNER JOIN flash_comprobantes_ingresos_servicios cis ON p.servicio_id = cis.id
                                                        LEFT JOIN flash_rendiciones_piezas rp ON p.id = rp.pieza_id
                                                        WHERE ci.id = $result->comprobante_ingreso_id
                                                        AND cis.servicio_id = $result->servicio_id
                                                        AND p.estado_id = $result->estado_id
                                                        AND rp.pieza_id IS NULL
                                                        GROUP BY p.estado_id";

                                        $result_consulta2 = $this->db->query($consulta2)->row();
                                        echo $result_consulta2->piezas_sin_rendir == NULL?"0":$result_consulta2->piezas_sin_rendir; 
                                ?> 
                                </td>
                          </tr>
                          <!--
                          <tr>
                            <td style="font-weight: bold"><?php echo $result->numero?></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td style="font-weight: bold"><?php echo "TOTAL: " ?></td>
                            <td style="font-weight: bold;text-align: right"><?php echo $result->piezas_con_estado ?> 
                            <td style="font-weight: bold;text-align: right"><?php echo $result->piezas_en_comprobante ?> </td>
                          </tr>-->
                          
                      <?php } ?>
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</div>
<form method='post' action='<?php echo base_url('piezas/consulta_piezas_clientes/exportarPiezasClientesAExcel')?>' id='exportAExcel'>
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
            $('#form_consulta_piezas_clientes').submit();
        });    
    
     $('.exportarAExcel').click(function(event) {
        $('#excel_desde').val($('#periodo_desde').val());
        $('#excel_hasta').val($('#periodo_hasta').val());
        $('#excel_cliente_id').val($('#cliente_id').val());
        $('#exportAExcel').submit();
    });
    });
</script>