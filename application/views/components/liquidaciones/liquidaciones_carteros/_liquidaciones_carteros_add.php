<div class="col-xs-12">
    <div class="ibox-content">
    <form class="form_liquidacion_carteros_add" id="form_liquidacion_carteros_add" name="form_liquidacion_carteros_add" action="<?php echo base_url('liquidaciones/liquidaciones_carteros/add')?>" method="post" enctype="multipart/form-data">
        <input type='hidden' id='enviar_form' name='enviar_form' value="1">
        <div class="row">
            <div class="col-xs-3">
                <div class="form-group">
                  <label for="sucursal">Sucursal</label>
                  <select id="sucursal_id" name="sucursal_id" class="form-control filtro-group">
                    <option value="">Seleccione</option>
                    <?php foreach ($sucursales as $sucursal): ?>
                      <option value="<?=$sucursal->id ?>" <?php echo $sucursal_id!= '' && $sucursal_id == $sucursal->id?"selected":"" ?>><?=$sucursal->nombre ?></option>
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
        <div class="control-group">
          <div class="controls">
            <a class="btn btn-primary filtro" href="javascript:;" ><i class="fa fa-arrow-down"></i> Aceptar</a>
            <?php echo form_button(array('type'  =>'submit','value' =>'Guardar','name'  =>'submit','class' =>'btn btn-success'), "<i class='fa fa-floppy-o'></i> Guardar"); ?> 
            <a class="btn btn-danger" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2); ?>"><i class="fa fa-arrow-circle-left"></i> Volver</a>
          </div>
        </div>
        <div>&nbsp;</div>
        <div id="grilla"></div>
    </form>
    </div>
    <div class="ibox-content">
        <table id="results" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
            <thead>
                <tr>
                    <th>H.D.R</th>
                    <th>Fecha</th>
                    <th>Cod. Servicio</th>
                    <th>Servicio</th>
                    <th>Cantidad</th>
                    <th>Precio Clientes</th>
                </tr>
            </thead>
            <tbody id="body-grilla">
                <?php foreach ($results as $result) { ?>
                    <tr id="resultados" class="nueva">
                        <td><?=$result->hdr_id?></td>
                        <td><?=$result->fecha?></td>
                        <td><?=$result->codigo_servicio?></td>
                        <td><?=$result->servicio?></td>
                        <td><?=$result->cantidad_piezas?></td>
                        <td style="text-align: right">$ <?=$result->precio_cliente?></td>
                    </tr>
                <?php }?>
            </tbody>
        </table>
    </div>
</div>
<form method='post' action='<?php echo base_url('liquidaciones/liquidaciones_carteros/addFiltro')?>' id='filtro'>
    <input type='hidden' id='filtro_fecha_desde' name='filtro_fecha_desde' value="">
    <input type='hidden' id='filtro_fecha_hasta' name='filtro_fecha_hasta' value="">
    <input type='hidden' id='filtro_sucursal_id' name='filtro_sucursal_id' value="">
    <input type='hidden' id='filtro_enviar_form' name='filtro_enviar_form' value="">
</form>   
<script type="text/javascript">
    $('.filtro').click(function(event) {
        $('#filtro_fecha_desde').val($('#periodo_desde').val());
        $('#filtro_fecha_hasta').val($('#periodo_hasta').val());
        $('#filtro_sucursal_id').val($('#sucursal_id').val());
        $('#filtro_enviar_form').val($('#enviar_form').val());
        $('#filtro').submit();
    });
    
//    $('.aceptar').click(function(event) {
//            //alert($('.form_liquidacion_add').serialize());
//            $('#grilla').html('');
//            $('#guardar').removeAttr("disabled");
//            $('.exportarAExcel').removeAttr("disabled");
//            var other_data = $('#form_liquidacion_carteros_add').serializeArray();
//            var form_data = new FormData();
//            $.each(other_data,function(key,input){
//                form_data.append(input.name,input.value);
//            });
//
//             $.ajax({
//                         url: '<?php echo base_url()."ajax/liquidaciones/getCarterosPendientesLiquidar" ?>',
//                         dataType: 'json',
//                         cache: false,
//                         contentType: false,
//                         processData: false,
//                         data: form_data,                
//                         type: 'post',
//                         success: function(data){
//                             if (data.status){
//                                 $('#errores').html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Error</strong> No dispone la cantidad ingresada para este servicio.</div>');
//                             }else{
//                                  $('#grilla').html('');
//                                 var contador = 0;
//                                 var cabecera = '<table class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">'+
//                                                    '<thead>'+
//                                                        '<tr>'+
//                                                            '<th>H.D.R</th>'+
//                                                            '<th>Fecha</th>'+
//                                                            '<th>Cod. Servicio</th>'+
//                                                            '<th>Servicio</th>'+
//                                                            '<th>Cantidad</th>'+
//                                                            '<th>Precio Clientes</th>'+
                                                            //'<th>Precio Cartero</th>'+
//                                                        '</tr>'+
//                                                    '</thead>';
//                                var body = '<tbody id="body-grilla">';
//                                var suma_total = 0;
//                                alert(data['grilla'].length);
//                                $('#cantidad_elementos').val(data['grilla'].length);
//                                $.each(data['grilla'], function(index, element) {
//                                     
//                                      body    += '<tr id="resultados" class="nueva"><input type="hidden" name="fila_'+contador+'" value="'+contador+'"/>'+
//                                                    '<input type="hidden" id="cartero_id_'+contador+'" name="cartero_id_'+contador+'" value="'+element['cartero_id']+'"/>'+
//                                                    '<input type="hidden" id="hoja_de_ruta_'+contador+'" name="hoja_de_ruta_'+contador+'" value="'+element['hdr_id']+'"/>'+
//                                                    '<input type="hidden" id="estado_id_'+contador+'" name="estado_id_'+contador+'" value="'+element['estado_id']+'"/>'+
//                                                    '<input type="hidden" id="estado_nombre_'+contador+'" name="estado_nombre_'+contador+'" value="'+element['estado_nombre']+'"/>'+
//                                                    '<input type="hidden" id="cliente_id_'+contador+'" name="cliente_id_'+contador+'" value="'+element['cliente_id']+'"/>'+
//                                                    '<td class="barcodear">'+element['hdr_id']+'</td><input type="hidden" id="hdr_id_'+contador+'" name="hdr_id_'+contador+'" value="'+element['hdr_id']+'"/> '+
//                                                    '<td class="">'+element['fecha']+'</td>'+'</td><input type="hidden" id="fecha_'+contador+'" name="fecha_'+contador+'" value="'+element['fecha']+'"/> '+
//                                                    '<td class="">'+element['codigo_servicio']+'</td>'+'</td><input type="hidden" id="codigo_servicio_'+contador+'" name="codigo_servicio_'+contador+'" value="'+element['codigo_servicio']+'"/> '+
//                                                    '<td class="">'+element['servicio']+'</td>'+'</td><input type="hidden" id="servicio_'+contador+'" name="servicio_'+contador+'" value="'+element['servicio']+'"/> '+
//                                                    '<td class="">'+element['cantidad_piezas']+'</td>'+'</td><input type="hidden" id="cantidad_piezas_'+contador+'" name="cantidad_piezas_'+contador+'" value="'+element['cantidad_piezas']+'"/> '+
//                                                    '<td class="">'+element['precio_cliente']+'</td>'+'</td><input type="hidden" id="precio_cliente_'+contador+'" name="precio_cliente_'+contador+'" value="'+element['precio_cliente']+'"/> '+
                                                   // '<td class="">'+element['precio_cartero']+'</td>'+'</td><input type="hidden" id="precio_cartero_'+contador+'" name="precio_cartero_'+contador+'" value="'+element['precio_cartero']+'"/> '+
//                                                '</tr>';
//                                    // }
// 
//                                     contador++;
//                                     suma_total = parseFloat(suma_total)+parseFloat(element['precio_cartero']);
//                                 });
//                                 var cierre = '</tbody></table>';
//                                 var total = '<tr>'+
//                                                '<td class=""></td>'+
//                                                '<td class=""></td>'+
//                                                '<td class=""></td>'+
//                                                '<td class=""></td>'+
//                                                '<td class=""></td>'+
//                                                '<td class=""><strong>Total</td></strong>'+
//                                                '<td class=""><strong>'+suma_total.toFixed(2)+'</strong></td>'+
//                                            '</tr>';
//                                 $('#grilla').append(cabecera+body/*+total*/+cierre); 
//                                 barcodear();
//                                 //Tabla Resumen
//                                 
//                             }
//                         }
//                     });
//          }); 
</script>