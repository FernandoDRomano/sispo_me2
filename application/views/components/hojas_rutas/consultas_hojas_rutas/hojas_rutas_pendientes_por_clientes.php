<div class="col-xs-12">
    <div class="ibox-content">
        <form action="<?=base_url('hojas_rutas/consultas/exportarAExcel')?>" method="post" id="form_consultas">
            <div class="row">
                <div class="col-xs-6">
                  <div class="form-group">
                    <label for="cliente">Cliente</label>
                    <input id="cliente" type="text" class="form-control filtro-group" placeholder="Cliente" autocomplete="off"/>
                    <input id="cliente_id" name="cliente_id" type="hidden"/>
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
                <span id="loadding"><i style="font-size:24px" class="fa fa-spinner fa-spin"></i></span>
                <a class="btn btn-primary pendientes button" href="javascript:;" ><i class="fa fa-arrow-down"></i> Pendientes</a>
                <a class="btn btn-success exportarAExcel button" href="javascript:;" ><i class="fa fa-arrow-down"> </i>Exportar a Excel</a>
            </div>
            <div>&nbsp;</div>
            <div id="grilla"   style="overflow-x: auto;height: 500px"></div>
        </form>
    </div>
</div>
<form method='post' action='<?php echo base_url('hojas_rutas/Hojas_rutas_alta/exportarHDRPendientesXClientes')?>' id='exportAExcel'>
    <input type='hidden' id='excel_desde' name='excel_desde' value="">
    <input type='hidden' id='excel_hasta' name='excel_hasta' value="">
    <input type='hidden' id='excel_sucursal_id' name='excel_sucursal_id' value="">
    <input type='hidden' id='excel_cliente_id' name='excel_cliente_id' value="">
</form>
<script type="text/javascript">
    $(document).ready(function() {
        $('#loadding').hide();
        $('#cliente').typeahead({
            source: <?=json_encode($clientes) ?>,
            displayField: 'nombre',
            onSelect: function(item) {
//                $('#transporte_id').val("");
//                $('#distribuidor_id').val("");
                $('#cliente_id').val('');
                $('#cliente').val('');
                if (item.value) {
                    $('#cliente_id').val(item.value);
                }
            }
        });
        
        $('.pendientes').click(function(event) {
                $('#grilla').html('');
                var other_data = $('#form_consultas').serializeArray();
                var form_data = new FormData();
                $.each(other_data,function(key,input){//alert(input.value);
                    form_data.append(input.name,input.value);
                });
                $('#loadding').show();
                $('.button').hide();
                 $.ajax({
                             url: '<?php echo base_url()."ajax/hojas_rutas/getHDRPendientesXClientes" ?>',
                             dataType: 'json',
                             cache: false,
                             contentType: false,
                             processData: false,
                             data: form_data,                
                             type: 'post',
                             success: function(data){
                                 $('#loadding').hide();
                                 $('.button').show();
                                 if (data.status){
                                     $('#grilla').html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>No se encontraron resultados.</div>');
                                 }else{
                                      $('#grilla').html('');
                                     var contador = 0;
                                     var cabecera = '<table class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">'+
                                                        '<thead>'+
                                                            '<tr>'+
                                                                '<th><a href="#">Sucursal</a></th>'+
                                                                '<th><a href="#">Fecha Ing.</a></th>'+
                                                                '<th><a href="#">C.I.</a></th>'+
                                                                '<th><a href="#">Cant. Total</a></th>'+
                                                                '<th><a href="#">Cliente</a></th>'+
                                                                '<th><a href="#">Servicio</a></th>'+
                                                                '<th><a href="#">Código</a></th>'+
                                                                '<th><a href="#">Pieza</a></th>'+
                                                                '<th><a href="#">Destinatario</a></th>'+
                                                                '<th><a href="#">Domicilio</a></th>'+
                                                                '<th><a href="#">Localidad</a></th>'+
                                                            '</tr>'+
                                                        '</thead>'+
                                                        '<tbody>';
                                    var body = '<tbody id="body-grilla">';
                                    $('#cantidad_elementos').val(data['grilla'].length);
                                    $.each(data['grilla'], function(index, element) {
                                            body    += 
                                                    '<tr id="resultados" class="nueva"><input type="hidden" name="fila_'+contador+'" value="'+contador+'"/>'+
                                                        '<td class="">'+element['sucursal']+'</td>'+'</td><input type="hidden" id="sucursal_'+contador+'" name="sucursal_'+contador+'" value="'+element['sucursal']+'"/> '+
                                                        '<td class="">'+element['fecha_ingreso']+'</td>'+'</td><input type="hidden" id="fecha_ingreso_'+contador+'" name="fecha_ingreso_'+contador+'" value="'+element['fecha_ingreso']+'"/> '+
                                                        '<td class="">'+element['numero']+'</td>'+'</td><input type="hidden" id="numero_'+contador+'" name="numero_'+contador+'" value="'+element['numero']+'"/> '+
                                                        '<td class="">'+element['cantidad']+'</td>'+'</td><input type="hidden" id="cantidad_'+contador+'" name="cantidad_'+contador+'" value="'+element['cantidad']+'"/> '+
                                                        '<td class="">'+element['cliente']+'</td>'+'</td><input type="hidden" id="cliente_'+contador+'" name="cliente_'+contador+'" value="'+element['cliente']+'"/> '+
                                                        '<td class="">'+element['servicio']+'</td>'+'</td><input type="hidden" id="servicio_'+contador+'" name="servicio_'+contador+'" value="'+element['servicio']+'"/> '+
                                                        '<td class="">'+element['codigo_externo']+'</td>'+'</td><input type="hidden" id="codigoexterno_'+contador+'" name="codigoexterno_'+contador+'" value="'+element['codigo_externo']+'"/> '+
                                                        '<td class="">'+element['pieza_id']+'</td>'+'</td><input type="hidden" id="pieza_id_'+contador+'" name="pieza_id_'+contador+'" value="'+element['pieza_id']+'"/> '+
                                                        '<td class="">'+element['destinatario']+'</td>'+'</td><input type="hidden" id="destinatario_'+contador+'" name="destinatario_'+contador+'" value="'+element['destinatario']+'"/> '+
                                                        '<td class="">'+element['domicilio']+'</td>'+'</td><input type="hidden" id="domicilio_'+contador+'" name="domicilio_'+contador+'" value="'+element['domicilio']+'"/> '+
                                                        '<td class="">'+element['localidad']+'</td>'+'</td><input type="hidden" id="localidad_'+contador+'" name="localidad_'+contador+'" value="'+element['localidad']+'"/> '+
                                                    '</tr>';

                                         contador++;
                                     });
                                     var cierre = '</tbody></table>';
                                     $('#grilla').append(cabecera+body); 
                                 }
                             }
                         });
              }); 
    });
    
     $('.exportarAExcel').click(function(event) {
        $('#excel_desde').val($('#periodo_desde').val());
        $('#excel_hasta').val($('#periodo_hasta').val());
        $('#excel_sucursal_id').val($('#sucursal_id').val());
        $('#excel_cliente_id').val($('#cliente_id').val());
        $('#excel_dias').val($('#dias').val());
        $('#exportAExcel').submit();
    });
</script>