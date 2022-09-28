<div class="col-xs-12">
    <div class="ibox-content">
        <form action="<?=base_url('piezas/comprobantes_ingresos/consultas_comprobantes_ingresos_filtro')?>" method="post" id="form-filtro">
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
    
            <div  class="row">
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="cliente">Clientes</label>
                        <input id="cliente" type="text" class="form-control filtro-group" placeholder="Cliente" autocomplete="off" value="<?php echo  $cliente_nombre[0]->nombre?>"/>
                        <input id="cliente_id" name="cliente_id" type="hidden" value="<?=$cliente_id?>"/>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-3">
                    <div class="form-group">
                      <label for="sucursal">Sucursal</label>
                      <select id="sucursal_id" name="sucursal_id" class="form-control filtro-group">
                      <option value="-1" <?=$sucursal_id == -1 ? 'selected="selected"' : ''?>> Todas </option>
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
                    <th><a href="#">C.I.</a></th>
                    <th><a href="#">Cliente</a></th>
                    <th><a href="#">Tipo</a></th>
                    <th><a href="#">Departamento</a></th>
                    <th><a href="#">Servicio</a></th>
                    <th><a href="#">Grupo</a></th>
                    <th><a href="#">Piezas</a></th>
                    <th><a href="#">Precio</a></th>
                    <th><a href="#">Total</a></th>
                    <th><a href="#">Fecha Creación</a></th>
                    <th><a href="#">Sucursal</a></th>
                </tr>
            </thead>
            <tbody>
              <?php foreach ($results as $result) { ?>
                  <tr>
                    <td><?php echo $result->numero ?></td>
                    <td><?php echo $result->cliente ?></td>
                    <td><?php echo $result->tipo ?></td>
                    <td><?php echo $result->departamento ?></td>
                    <td><?php echo $result->servicio ?></td>
                    <td><?php echo $result->grupo ?></td>
                    <td><?php echo $result->piezas ?></td>
                    <td><span  style="float:left">$</span><span  style="float:right"><?php echo $result->precio ?></span></td>
                    <td><span  style="float:left">$</span><span  style="float:right"><?php echo $result->subtotal ?></span></td>
                    <td><?php echo $result->fecha_creacion ?></td>
                    <td><?php echo $result->sucursal ?></td>
                  </tr>
              <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<form method='post' action='<?php echo base_url('piezas/comprobantes_ingresos/exportar_consulta_comprobantes_ingresos')?>' id='exportAExcel'>
    <input type='hidden' id='excel_desde' name='excel_desde' value="">
    <input type='hidden' id='excel_hasta' name='excel_hasta' value="">
    <input type='hidden' id='excel_sucursal_id' name='excel_sucursal_id' value="">
    <input type='hidden' id='excel_cliente_id' name='excel_cliente_id' value="">
    <input type='hidden' id='excel_cliente_nombre' name='excel_cliente_nombre' value="">
</form>
<script type="text/javascript">
$(document).ready(function() {
        $('.exportarAExcel').click(function(event) {
            $('#excel_desde').val($('#periodo_desde').val());
            $('#excel_hasta').val($('#periodo_hasta').val());
            $('#excel_sucursal_id').val($('#sucursal_id').val());
            $('#excel_cliente_id').val($('#cliente_id').val());
            $('#excel_cliente_nombre').val($('#cliente_nombre').val());
            $('#exportAExcel').submit();
        });

        $('#cliente').typeahead({
            source: <?=json_encode($clientes) ?>,
            displayField: 'nombre',
            onSelect: function(item) {
                if (item.value) {
                    $('#cliente').val(item.value);
                    $('#cliente_id').val(item.value);
                    /*if (item.value != 0){
                        var id = item.value;
                        $('#departamento_id').addClass('loading-ajax');
                        if (id != 0) {
                            var url = '<?php echo base_url() ?>' + 'ajax/clientes/departamentos/' + $('#cliente_id').val();
                            $.getJSON(url, function(data) {
                                $("#departamento_id").find("option").remove();                
                                var options = '';

                                if(!data.status){
                                    $.each(data, function(key, val) {
                                        options = options + "<option value='"+val.id+"'>"+ val.nombre +"</option>";
                                    });              
                                }else{
                                    options = options + "<option value='0' disabled>Sin resultados</option>";
                                }

                          //     $("#departamento_id").append(options);
                            //    $("#departamento_id").trigger("liszt:updated");
                            });
                        }else{
                            //$("#departamento_id").find("option").remove();
                        }

                        //$('#departamento_id').removeClass('loading-ajax');
                    }*/
                }
            }
        });
    });
</script>