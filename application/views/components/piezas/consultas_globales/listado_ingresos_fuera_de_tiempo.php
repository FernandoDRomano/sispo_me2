<div class="col-xs-12">
    <div class="ibox-content">
        <form class="form_listado_ingresos_fuera_tiempo" id="form_listado_ingresos_fuera_tiempo" name="form_listado_ingresos_fuera_tiempo" action="<?php echo base_url('consultas/Listado_ingresos_fuera_de_tiempo/filter')?>" method="post" enctype="multipart/form-data">
	<input type='hidden' id='enviar_form' name='enviar_form' value="1">
        <div class="row">
            <div class="col-xs-4">
                <div class="form-group">
                    <label for="dias">Mayor a</label>
                    <input id="dias" name="dias" type="text" class="form-control spinner" value="<?php echo $filtro?>"  required autocomplete="off" />
                    <span id="loadding" ><i style="font-size:24px" class="fa fa-spinner fa-spin"></i></span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-6">
                <div class="control-group">
                  <div class="controls">
                    <?php echo form_button(array('type'  =>'submit','value' =>'Guardar','name'  =>'submit','class' =>'btn btn-success','id' => 'filtrar'), "<i class='fa fa-filter'></i> Aceptar"); ?> 
                    <a class="btn btn-primary exportarAExcel" href="javascript:;"><i class="fa fa-arrow-down"></i> Exportar a Excel</a>
                  </div>
                </div>
                
            </div>
        </div>
        <br>
        <div id="procesando" name="procesando"></div>
        <!--<div id="grilla"  style="overflow-x: auto;height: 500px">-->
            <table id="results" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
                <thead>
                    <tr>
                     <th style="text-align:center">Sucursal</th>
                     <th style="text-align:center">Cliente</th>
                     <th style="text-align:center">Servicio</th>
                     <th style="text-align:center">C.I.</th>
                     <th style="text-align:center">Fecha Ingreso</th>
                     <th style="text-align:center">Piezas/CI</th>
                     <th style="text-align:center">Piezas/Servicio</th>
                     <th style="text-align:center">Rendidas</th>
                     <th style="text-align:center">Pendientes</th>
                     <th style="text-align:center">Fecha Ult. Rendición</th>
                    </tr>
               </thead>
               <tbody>
               <?php foreach($results as $value) {?>
                    <tr>
                        <td><?php echo $value->sucursal?></td>
                        <td><?php echo $value->cliente?></td>
                        <td><?php echo $value->servicio?></td>
                        <td><?php echo $value->numero?></td>
                        <td><?php echo $value->fecha_pedido?></td>
                        <td style="text-align:right"><?php echo $value->total_comprobante?></td>
                        <td style="text-align:right"><?php echo $value->cantidad_servicio?></td>
                        <td style="text-align:right"><?php echo $value->piezas_rendidas?></td>
                        <td style="text-align:right"><?php echo $value->piezas_pendientes?></td>
                        <td style="text-align:left;padding-left: 0px"><?php echo $value->fecha_ultima_rendicion?></td>
                    </tr>
                <?php }?>
                </tbody>
            </table>
        <!--</div>-->
    </form>
    </div>
</div>
<form method='post' action='<?php echo base_url('consultas/Listado_ingresos_fuera_de_tiempo/exportarAExcel')?>' id='exportarAExcel'>
    <input type='hidden' id='excel_dias' name='excel_dias' value="">
</form>    
<script type="text/javascript">
$(document).ready(function() {
    $('#loadding').hide();
    $('#procesando').hide();
    
    $('.aceptar').click(function(event) {
        $('#grilla').html('');
        $('#loadding').show();
        $('.btn-primary').hide();
        var other_data = $('#form_listado_ingresos_fuera_tiempo').serializeArray();
        var form_data = new FormData();
        $.each(other_data,function(key,input){
            form_data.append(input.name,input.value);
        });
         $.ajax({
                url: '<?php echo base_url()."ajax/consultas/getListadoIngresosFueraTiempo" ?>',
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,                
                type: 'post',
                success: function(data){
                    $('#loadding').hide();
                    $('.btn-primary').show();//alert(JSON.stringify(data));
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
                                                '<th style="text-align:center">C.I.</th>'+
                                                '<th style="text-align:center">Fecha Ingreso</th>'+
                                                '<th style="text-align:center">Piezas/CI</th>'+
                                                '<th style="text-align:center">Piezas/Servicio</th>'+
                                                '<th style="text-align:center">Rendidas</th>'+
                                                '<th style="text-align:center">Pendientes</th>'+
                                                '<th style="text-align:center">Fecha Ult. Rendición</th>'+
                                               '</tr>'+
                                          '</thead>'+
                                          '<tbody>';
//                                          var suma_col_1 = 0;
//                                          var suma_col_2 = 0;
//                                          var suma_col_3 = 0;
                                            $.each(data['datos'], function(index, element) {//alert(JSON.stringify(element['cantidad']));
                                              var pendientes  = parseInt(element['pendientes']);
//                                              suma_col_1 += parseInt(element['cantidad']);
//                                              suma_col_2 += parseInt(element['ventas']);
//                                              suma_col_3 += precio;
                                             //if (pendientes > parseInt(0)){
                                                html_filas += '<tr>'+
                                                                  '<td>'+element['sucursal']+'</td>'+
                                                                  '<td>'+element['cliente']+'</td>'+
                                                                  '<td>'+element['servicio']+'</td>'+
                                                                  '<td>'+element['numero']+'</td>'+
                                                                  '<td>'+element['fecha_pedido']+'</td>'+
                                                                  '<td style="text-align:right">'+element['total_comprobante']+'</td>'+
                                                                  '<td style="text-align:right">'+element['cantidad_servicio']+'</td>'+
                                                                  '<td style="text-align:right">'+element['piezas_rendidas']+'</td>'+
                                                                  '<td style="text-align:right">'+element['piezas_pendientes']+'</td>'+
                                                                  '<td style="text-align:center">'+element['fecha_ultima_rendicion']+'</td>'+
                                                              '</tr>';
                                                i++;
                                              //}
                                            });
                           //html_filas += '<tr style="font-weight:bold"><td>TOTALES</td><td style="text-align:right">'+suma_col_1+'</td><td style="text-align:right">'+suma_col_2+'</td><td style="text-align:right">'+suma_col_3.toFixed(3)+'</td></tr>';                 
                           html += html_filas+'</tbody></table>';
                        $('#grilla').append(html);
                    }
                }
            });
    });
    
    $('.exportarAExcel').click(function(event) {
        $('#excel_dias').val($('#dias').val());
        $('#exportarAExcel').submit();
    });
});
          
</script>
