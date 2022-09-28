<div class="col-xs-12">
    <div class="ibox-content">
        <form class="form_listado_precios_clientes" id="form_listado_precios_clientes" name="form_listado_precios_clientes" action="<?php echo base_url('consultas/add')?>" method="post" enctype="multipart/form-data">
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
<form method='post' action='<?php echo base_url('consultas/Listado_precios_clientes/exportarAExcel')?>' id='exportarAExcel'>
    <input type='hidden' id='excel_desde' name='excel_desde' value="">
    <input type='hidden' id='excel_hasta' name='excel_hasta' value="">
</form>    
<script type="text/javascript">
$(document).ready(function() {
    
    $('#procesando').hide();
    
    $('.aceptar').click(function(event) {
        $('#grilla').html('');
        var other_data = $('#form_listado_precios_clientes').serializeArray();
        var form_data = new FormData();
        $.each(other_data,function(key,input){
            form_data.append(input.name,input.value);
        });
         $.ajax({
                url: '<?php echo base_url()."ajax/consultas/getListadoPreciosClientes" ?>',
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,                
                type: 'post',
                success: function(data){//alert(JSON.stringify(data.consulta_escrita));
                    if (data.status){ alert("No se encontro resultados");
                    }else{
                        var i = 0;
                        var html_filas = '';
                        var html = '<table width="100%" border="1" style="table-layout: fixed;" class="table-striped table-hover table-condensed bootstrap-datatable table-bordered">'+
                                           '<thead>'+
                                               '<tr>'+
                                                '<th style="text-align:center">Sucursal</th>'+
                                                '<th style="text-align:center">Cliente</th>'+
                                                '<th style="text-align:center">Servicio</th>'+
                                                '<th style="text-align:center">Grupo</th>'+
                                                '<th style="text-align:center">Precio Unitario</th>'+
                                               '</tr>'+
                                          '</thead>'+
                                          '<tbody>';
                                            $.each(data['vtas_clientes'], function(index, element) {//alert(JSON.stringify(element['cantidad']));
                                              html_filas += '<tr>'+
                                                                  '<td>'+element['sucursal']+'</td>'+
                                                                  '<td>'+element['cliente']+'</td>'+
                                                                  '<td>'+element['servicio']+'</td>'+
                                                                  '<td>'+element['grupo']+'</td>'+
                                                                  '<td><span style="text-align:left">$</span><span style="float:right;text-align:right">'+element['precio_unitario']+'</span></td>'+
                                                              '</tr>';
                                                i++;
                                            });
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
