<div class="col-xs-12">
    <div class="ibox-content">
        <form class="form_listado_vtas_productos" id="form_listado_vtas_productos" name="form_listado_vtas_productos" action="<?php echo base_url('consultas/add')?>" method="post" enctype="multipart/form-data">
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
<form method='post' action='<?php echo base_url('consultas/listado_vtas_productos/exportarAExcel')?>' id='exportarAExcel'>
    <input type='hidden' id='excel_desde' name='excel_desde' value="">
    <input type='hidden' id='excel_hasta' name='excel_hasta' value="">
</form>    
<script type="text/javascript">
$(document).ready(function() {
    
    $('#procesando').hide();
    
    $('.aceptar').click(function(event) {
        $('#grilla').html('');
        var other_data = $('#form_listado_vtas_productos').serializeArray();
        var form_data = new FormData();
        $.each(other_data,function(key,input){
            form_data.append(input.name,input.value);
        });
         $.ajax({
                url: '<?php echo base_url()."ajax/consultas/getListadoVtasProductos" ?>',
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,                
                type: 'post',
                success: function(data){//alert(JSON.stringify(data));
                    if (data.status){ alert("No se encontro resultados");
                        //$("#limite_inferior").val(0);
                        //setTimeout(procesarRendicionesSimples(), 300);
                       //$("#procesando").html("Procesados..."+parseInt($("#registros_procesaros").val())+" registros");
                       //$('.exportarAExcel').attr('disabled','disabled'); 
                       //$('#grilla').html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>No se encontraron resultados.</div>');
                    }else{
                          var contador = 0;
                        var cabecera = '<table width="4500px" border="1" style="table-layout: fixed;" class="table-striped table-hover table-condensed bootstrap-datatable table-bordered">'+
                                           '<thead>'+
                                               '<tr>'+
                                               '<th width="10%">MES</th>';
                        var row_sucursales = "";
                        var row_estados = "";
                        var row_filas = "";
                        var row_filas_contenido = "";
                        var row_datos = "";
                        var row_datos_total = "";
                        var total_filas_ingresadas = 0;
                        var total_filas_rendidas = 0;
                        var total_filas_pendientes = 0;
                        var col_ingresadas = [];
                        var col_rendidas = [];
                        var col_pendientes = [];
                        var total_col_ingresadas = [];
                        var total_col_rendidas = [];
                        var total_col_pendientes = [];
                        var i = 0;
                        var j = 0;
                        //Primer encabezado
                        $.each(data['sucursales'], function(index, element) {
                            row_sucursales += '<th style="text-align:center;width: 15%;" colspan="3" >'+element['nombre']+'</th>';
                        });
                        //Segundo encabezado
                        cabecera += row_sucursales+'<th colspan="3" style="text-align:center;width: 15%;">TOTAL</th></tr>'+
                                                   '<tr>'+
                                                        '<th>PRODUCTO</th>';
                        $.each(data['sucursales'], function(index, element) {
                            row_estados += '<th >Piezas</th><th>Ventas</th><th>Precio Promedio</th>';
                        });
                        //Filas de datos
                        cabecera += row_estados+'<th>Piezas</th><th>Ventas</th><th>Precio Promedio</th></tr></thead>';
                        
                        var total_columnas_ingresadas = 0;
                        var total_columnas_rendidas = 0;
                        var total_columnas_pendientes = 0;
                        var ultima_fila_totales = [] ;
                        contador_horizontal = 0;
                        contador_vertical = 0;
                        $.each(data['grupos'], function(index, element) {
                            row_filas += '<tr><th style="font-weight:bold;">'+element['nombre']+'</th>';
                            $.each(data['sucursales'], function(index, element11) {
                                total_filas_ingresadas += parseInt(data['cantidades'][i]);
                                total_filas_rendidas += parseFloat(data['ventas'][i]);
                                total_filas_pendientes += parseFloat(data['precios_promedio'][i]);
                                col_ingresadas[i] = parseInt(data['cantidades'][i]);
                                col_rendidas[i] = parseFloat(data['ventas'][i]);
                                col_pendientes[i] = parseFloat(data['precios_promedio'][i]);
                                var division = parseFloat(0);
                                if (parseInt(data['cantidades'][i]) > parseInt(0)){
                                    //alert(parseFloat(data['ventas'][i])+"   "+parseInt(data['cantidades'][i]));
                                    division = parseFloat(data['ventas'][i])/parseInt(data['cantidades'][i]);
                                }else{
                                    division = parseFloat(0);
                                }
                                row_datos += '<th style="text-align:right;font-weight:normal">'+data['cantidades'][i]+'</th>'+
                                            '<th  style="text-align:right;font-weight:normal">'+parseFloat(data['ventas'][i]).toFixed(3)+'</th>'+
                                            '<th  style="text-align:right;font-weight:normal">'+parseFloat(division).toFixed(3)+'</th>';
                                if( j == 0){
                                    ultima_fila_totales[contador_horizontal] = parseInt(data['cantidades'][i]);contador_horizontal++;
                                    ultima_fila_totales[contador_horizontal] = parseFloat(data['ventas'][i]);contador_horizontal++;
                                    ultima_fila_totales[contador_horizontal] = parseFloat(division);contador_horizontal++;
                                    //alert(parseFloat(division).toFixed(3));
                                }
                                if( j > 0){
                                    ultima_fila_totales[contador_horizontal] += parseInt(data['cantidades'][i]);contador_horizontal++;
                                    ultima_fila_totales[contador_horizontal] += parseFloat(data['ventas'][i]);contador_horizontal++;
                                    ultima_fila_totales[contador_horizontal] += parseFloat(division);contador_horizontal++;
                                    //alert(JSON.stringify(ultima_fila_totales));
                                }
                            i++;
                            //contador_horizontal++;
                            });
                            //ULTIMA COLUMNA (TOTAL)
                            total_columnas_ingresadas += total_filas_ingresadas;
                            total_columnas_rendidas += total_filas_rendidas;
                            total_columnas_pendientes += total_filas_pendientes;
                            //alert(parseFloat(total_filas_rendidas).toFixed(3)/total_filas_ingresadas);
                            total_promedio = total_filas_rendidas != 0 && total_filas_ingresadas != 0? (parseFloat(total_filas_rendidas)/total_filas_ingresadas).toFixed(3):0;
                            row_datos_total += '<th>'+total_filas_ingresadas+'</th>'+
                                                '<th style="text-align:right">'+parseFloat(total_filas_rendidas).toFixed(3)+'</th>'+
                                                '<th style="text-align:right">'+parseFloat(total_promedio).toFixed(3)+'</th>';
                            
                            total_filas_ingresadas = 0;
                            total_filas_rendidas = 0;
                            total_filas_pendientes = 0;
                            
                            //ULTIMA FILA (TOTAL)
                            $.each(data['sucursales'], function(index, element) {
                                if(total_col_ingresadas.length == 0){
                                    //alert("primero");
                                    //alert(col_ingresadas[j]);
                                    total_col_ingresadas[j] = col_ingresadas[j];
                                     total_col_rendidas[j] = col_rendidas[j];
                                    total_col_pendientes[j] = col_pendientes[j];
                                    //alert(total_col_ingresadas[j]);
                                }else{//alert("despues");
                                    //alert(col_ingresadas[j]);
                                    total_col_ingresadas[j] += col_ingresadas[j];
                                    total_col_rendidas[j] += col_rendidas[j];
                                    total_col_pendientes[j] += col_pendientes[j];
                                    //alert(total_col_ingresadas[j]);
                                }
                            });
                            //alert(JSON.stringify(total_col_ingresadas));
                        //row_filas_contenido += row_filas+row_datos+row_datos_total+'</tr>';
                            row_filas_contenido += row_filas+row_datos+row_datos_total+'</tr>';
                            row_filas = '';
                            row_datos = '';
                            row_datos_total = '';
                            total_filas_ingresadas = 0;
                            total_filas_rendidas = 0;
                            total_filas_pendientes = 0;
                            j++;
                            contador_horizontal = 0;
                            //alert(element['nombre']+"   "+JSON.stringify(ultima_fila_totales));
                            //alert(row_filas);
                        });
                        
                        //Suma de las columas totales que se ponen en la ultima fila
                        var total_columnas = "<td>"+total_columnas_ingresadas+"</td>"+
                                                "<td style='text-align:right'>"+total_columnas_rendidas.toFixed(3)+"</td>"+
                                                    "<td style='text-align:right'>"+total_columnas_pendientes.toFixed(3)+"</td>";
                        //Columnas de TOTALES
                        row_filas += '<tr style="font-weight:bold;"><td>TOTAL GENERAL</td>';
                        //$.each(data['totales_columnas'], function(index, element) {
                        $.each(ultima_fila_totales, function(index, element) {
                            if (parseInt(element) != element){ element = element.toFixed(3)}
                            row_datos += '<td style="text-align:right">'+element+'</td>';
                        });
                        
                        row_filas_contenido += row_filas+row_datos+total_columnas+'</tr>';
                        cabecera += row_filas_contenido+
                            +'<tbody></tbody></table>';
                        
                        $('#grilla').append(cabecera);
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
