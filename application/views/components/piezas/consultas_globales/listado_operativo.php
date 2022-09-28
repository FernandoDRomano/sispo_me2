<div class="col-xs-12">
    <div class="ibox-content">
        <form class="form_listado_operativo" id="form_listado_operativo" name="form_listado_operativo" action="<?php echo base_url('consultas/add')?>" method="post" enctype="multipart/form-data">
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
<form method='post' action='<?php echo base_url('consultas/listado_operativo/exportarAExcel')?>' id='exportarAExcel'>
    <input type='hidden' id='excel_desde' name='excel_desde' value="">
    <input type='hidden' id='excel_hasta' name='excel_hasta' value="">
</form>    
<script type="text/javascript">
$(document).ready(function() {
    
    $('#procesando').hide();
    
    $('.aceptar').click(function(event) {
        $('#grilla').html('');
        var other_data = $('#form_listado_operativo').serializeArray();
        var form_data = new FormData();
        $.each(other_data,function(key,input){
            form_data.append(input.name,input.value);
        });
         $.ajax({
                url: '<?php echo base_url()."ajax/consultas/getListadoOperativo" ?>',
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
                        var cabecera = '<table width="2500px" border="1" style="table-layout: fixed;" class="table-striped table-hover table-condensed bootstrap-datatable table-bordered">'+
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
                        var color_fondo_1 = '#C8C0E7;';
                        var color_fondo_2 = '#E6E1F4;';
                        var color_fondo = color_fondo_1;
                        $.each(data['sucursales'], function(index, element) {
                            row_sucursales += '<th colspan="3" style="text-align:center;color-bac;background-color: '+color_fondo+'">'+element['nombre']+'</th>';
                            if(color_fondo === color_fondo_1){color_fondo = color_fondo_2;}else{color_fondo = color_fondo_1;}
                        });
                        //Segundo encabezado
                        cabecera += row_sucursales+'<th colspan="3" style="text-align:center">TOTAL</th></tr>'+
                                                   '<tr>'+
                                                        '<th>PRODUCTO</th>';
                        var color_fondo = color_fondo_1;
                        $.each(data['sucursales'], function(index, element) {
                            row_estados += '<th style="background-color: '+color_fondo+'">Ingresos</th><th style="background-color: '+color_fondo+'">Rendidos</th><th style="background-color: '+color_fondo+'">Pendientes</th>';
                            if(color_fondo === color_fondo_1){color_fondo = color_fondo_2;}else{color_fondo = color_fondo_1;}
                        });
                        //Filas de datos
                        cabecera += row_estados+'<th>Ingresos</th><th>Rendidos</th><th>Pendientes</th></tr></thead>';
                        
                        var total_columnas_ingresadas = 0;
                        var total_columnas_rendidas = 0;
                        var total_columnas_pendientes = 0;
                        var color_fondo = color_fondo_1;
                        $.each(data['grupos'], function(index, element) {
                            row_filas += '<tr><td>'+element['nombre']+'</td>';
                            $.each(data['sucursales'], function(index, element) {
                                total_filas_ingresadas += parseInt(data['ingresadas'][i]);
                                total_filas_rendidas += parseInt(data['rendidas'][i]);
                                total_filas_pendientes += (parseInt(data['ingresadas'][i]) - parseInt(data['rendidas'][i]));
//                                col_ingresadas[i] = parseInt(data['ingresadas'][i]);
//                                col_rendidas[i] = parseInt(data['rendidas'][i]);
//                                col_pendientes[i] = (parseInt(data['ingresadas'][i]) - parseInt(data['rendidas'][i]));//(parseInt(data['ingresadas'][i])-parseInt(data['rendidas'][i]));//parseInt(data['pendientes'][i]);
                                row_datos += '<td style="text-align:right;background-color: '+color_fondo+'">'+data['ingresadas'][i]+'</td>'+
                                            '<td style="text-align:right;background-color: '+color_fondo+'">'+data['rendidas'][i]+'</td>'+
                                            '<td style="text-align:right;background-color: '+color_fondo+'">'+(parseInt(data['ingresadas'][i]) - parseInt(data['rendidas'][i]))+'</td>';//<th>'+data['rendidas'][i]['cantidad']+'</th><th>'+data['pendientes'][i]['cantidad']+'</th>';
                                     if(color_fondo === color_fondo_1){color_fondo = color_fondo_2;}else{color_fondo = color_fondo_1;}
                            i++;
                            });
                            if(color_fondo === color_fondo_2){color_fondo = color_fondo_1;}else{color_fondo = color_fondo_2;};
                            //ULTIMA COLUMNA (TOTAL)
                            total_columnas_ingresadas += total_filas_ingresadas;
                            total_columnas_rendidas += total_filas_rendidas;
                            total_columnas_pendientes += total_filas_pendientes;
                           
                            row_datos_total += '<td style="text-align:right">'+total_filas_ingresadas+'</td>'+
                                        '<td style="text-align:right;">'+total_filas_rendidas+'</td>'+
                                        '<td style="text-align:right;">'+total_filas_pendientes+'</td>';
                            
                            //ULTIMA FILA (TOTAL)
                            
//                            $.each(data['sucursales'], function(index, element) {
//                                total_col_ingresadas[j] += col_ingresadas[j];
//                                total_col_rendidas[j] += col_rendidas[j];
//                                total_col_pendientes[j] += col_pendientes[j];
//                            });
//                            alert(JSON.stringify(total_col_pendientes));
                        //row_filas_contenido += row_filas+row_datos+row_datos_total+'</tr>';
                            row_filas_contenido += row_filas+row_datos+row_datos_total+'</tr>';
                            row_filas = '';
                            row_datos = '';
                            row_datos_total = '';
                            total_filas_ingresadas = 0;
                            total_filas_rendidas = 0;
                            total_filas_pendientes = 0;
                            j++;
                            //alert(row_filas);
                        });
                        //Suma de las columas totales que se ponen en la ultima fila
                        var total_columnas = "<td style='text-align:right;'>"+total_columnas_ingresadas+"</td>"+
                                                "<td style='text-align:right;'>"+total_columnas_rendidas+"</td>"+
                                                    "<td style='text-align:right;'>"+total_columnas_pendientes+"</td>";
                                            
                        //Columnas de TOTALES (ultima fila totales)
                        row_filas += '<tr style="font-weight:bold;"><td>TOTAL GENERAL</td>';
                        var color_fondo_index = 1;
                        $.each(data['totales_columnas'], function(index, element) {
                            row_datos += '<td style="text-align:right;background-color: '+color_fondo+'">'+element+'</td>';
                            if (color_fondo_index === 3){
                                if(color_fondo === color_fondo_1){color_fondo = color_fondo_2;}else{color_fondo = color_fondo_1;}
                                color_fondo_index = 0;
                            }
                            color_fondo_index++;
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
