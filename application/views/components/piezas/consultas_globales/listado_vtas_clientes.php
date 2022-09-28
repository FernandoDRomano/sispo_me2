<div class="col-xs-12">
    <div class="ibox-content">
        <form class="form_listado_vtas_clientes" id="form_listado_vtas_clientes" name="form_listado_vtas_clientes" action="<?php echo base_url('consultas/add')?>" method="post" enctype="multipart/form-data">
	<input type='hidden' id='enviar_form' name='enviar_form' value="1">
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
            <div class="col-xs-6">
                <div class="control-group">
                  <div class="controls">
                    <a class="btn btn-primary aceptar" href="javascript:;" ><i class="fa fa-arrow-down"></i> Aceptar</a>
                    <a class="btn btn-primary exportarAExcel" href="javascript:;"><i class="fa fa-arrow-down"></i> Exportar a Excel</a>
                  </div>
                </div>
                
            </div>
        </div>
        <div id="procesando" name="procesando"></div>
        <div id="grilla"  style="overflow-x: auto;height: 500px"></div>
    </form>
    </div>
</div>
<form method='post' action='<?php echo base_url('consultas/listado_vtas_clientes/exportarAExcel')?>' id='exportarAExcel'>
    <input type='hidden' id='excel_desde' name='excel_desde' value="">
    <input type='hidden' id='excel_hasta' name='excel_hasta' value="">
</form>    
<script type="text/javascript">
$(document).ready(function() {
    
    $('#procesando').hide();
    
    $('.aceptar').click(function(event) {
        $('#grilla').html('');
        var other_data = $('#form_listado_vtas_clientes').serializeArray();
        var form_data = new FormData();
        $.each(other_data,function(key,input){
            form_data.append(input.name,input.value);
        });
         $.ajax({
                url: '<?php echo base_url()."ajax/consultas/getListadoVtasClientes" ?>',
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,                
                type: 'post',
                success: function(data){//alert(JSON.stringify(data));
                    if (data.status){ alert("No se encontro resultados");
                    }else{
                        var i = 0;
                        var html_filas = '';
                        var html = '<table width="100%" border="1" style="table-layout: fixed;" class="table-striped table-hover table-condensed bootstrap-datatable table-bordered">'+
                                           '<thead>'+
                                               '<tr>'+
                                                '<th>MES</th>'+
                                                '<th colspan="3" style="text-align:center">Ventas</th>'+
                                               '</tr>'+
                                               '<tr>'+
                                                '<th style="text-align:center">Clientes</th>'+
                                                '<th style="text-align:center">Piezas</th>'+
                                                '<th style="text-align:center">Ventas</th>'+
                                                '<th>Precio Promedio</th>'+
                                               '</tr>'+
                                          '</thead>'+
                                          '<tbody>';
                                          var suma_col_1 = 0;
                                          var suma_col_2 = 0;
                                          var suma_col_3 = 0;
                                            $.each(data['vtas_clientes'], function(index, element) {//alert(JSON.stringify(element['precio_cantidad']));
                                              var precio  = parseFloat(element['precio_promedio']);
                                              suma_col_1 += parseFloat(element['cantidad']);
                                              suma_col_2 += parseInt(element['ventas']);
                                              suma_col_3 += precio;
                                              html_filas += '<tr><td>'+element['cliente']+'</td><td style="text-align:right">'+element['cantidad']+'</td><td style="text-align:right">'+element['ventas']+'</td><td style="text-align:right">'+precio.toFixed(3)+'</td></tr>';
                                              i++;
                                            });
                           html_filas += '<tr style="font-weight:bold"><td>TOTALES</td><td style="text-align:right">'+suma_col_1+'</td><td style="text-align:right">'+suma_col_2+'</td><td style="text-align:right">'+suma_col_3.toFixed(3)+'</td></tr>';                 
                           html += html_filas+'</tbody></table>';
                        $('#grilla').append(html);
                    }
                }
            });
    });
    
    $('.exportarAExcel').click(function(event) {
        $('#excel_desde').val($('#periodo_desde').val());
        $('#excel_hasta').val($('#periodo_hasta').val());
        $('#exportarAExcel').submit();
    });
});
</script>
