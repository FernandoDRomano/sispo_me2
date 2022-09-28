<div class="col-xs-12">
    <div class="ibox-content">
	<form class="form_liquidacion_paqueteria_edit" id="form_liquidacion_paqueteria_edit" name="form_liquidacion_paqueteria_edit" action="<?php echo base_url('liquidaciones/liquidaciones_clientes_paqueteria/edit/'.$result->id)?>" method="post" enctype="multipart/form-data">
            <?php   echo form_hidden('enviar_form','1');?>
                    <input type='hidden' id='cantidad_elementos' name='cantidad_elementos' value="<?=count($result)?>">
                    <input type='hidden' id='estado_id' name='estado_id' value="<?=$result->estado_id?>">
                    <input type='hidden' id='liquidacion_cliente_id' name='liquidacion_cliente_id' value="<?=$result->id?>">
                    <input type='hidden' id='iva' name='iva' value="<?=$result->iva?>">
                    <input type='hidden' id='nro' name='nro' value="<?=$result->nro?>">
                    <!--Cambiar luego para que venga del server el tipo de factura que se esta pidiendo-->
                    <input type='hidden' id='imprimir' name='imprimir' value="facturaa">
                    
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="cliente_id">Cliente</label>
                                    <input id="cliente" type="text" class="form-control filtro-group" placeholder="Cliente" autocomplete="off" value="<?= Cliente::whereId($result->cliente_id)->first()->nombre?>" readonly/>
                                    <input id="cliente_id" name="cliente_id" type="hidden" value="<?=$result->cliente_id?>"/>
                                </div>
                            </div>
                            <div class="col-xs-3">
                                <div class="form-group">
                                  <label for="dpto">Dpto</label>
                                  <select id="departamento_id" name="departamento_id" class="form-control filtro-group" readonly>
                                    <option value="">Seleccione</option>
                                    <?php foreach ($departamentos as $departamento): ?>
                                      <option value="<?=$departamento->id ?>" <?= $departamento->id == $result->departamento_id ? 'selected="selected"' : ''?>><?=$departamento->nombre ?></option>
                                    <?php endforeach; ?>
                                  </select>
                                </div>
                              </div>
                              <div class="col-xs-3">
                                    <div class="form-group">
                                          <label for="sucursal">Sucursal</label>
                                          <select id="sucursal_id" name="sucursal_id" class="form-control filtro-group" readonly>
                                            <?php foreach ($sucursales as $sucursal): ?>
                                              <option value="<?=$sucursal->id ?>"<?= $sucursal->id == $result->sucursal_id ? 'selected="selected"' : ''?>><?=$sucursal->nombre ?></option>
                                            <?php endforeach; ?>
                                          </select>
                                    </div>
                                
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-3">
                                <div class="form-group">
                                    <label for="factura">Factura</label>
                                    <input  id="factura" name="factura" type="text" class="form-control" placeholder="Factura" value="<?=$result->factura != ''?$result->factura:''?>" <?php if($result->factura != '') echo "readonly";?>/>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                    <div class="form-group">
                                          <label for="paqueteria">Paqueteria</label>
                                          <select id="paqueteria_id" name="paqueteria_id" class="form-control filtro-group">
                                            <?php foreach ($paqueterias as $paqueteria): ?>
                                              <option value="<?=$paqueteria->id ?>" ><?=$paqueteria->nombre ?></option>
                                            <?php endforeach; ?>
                                          </select>
                                    </div>
                                </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-3">
                                <div class="form-group">
                                    <label for="periodo_desde">Periodo desde</label>
                                    <input id="periodo_desde" name="periodo_desde" type="text" class="form-control datepicker" placeholder="Seleccione una fecha" value="<?php if ($result->periodo_desde){ $f = strtotime($result->periodo_desde);echo date('d-m-Y',$f );} ?>"  required autocomplete="off" readonly/>
                                </div>
                            </div>
                            <div class="col-xs-3">
                                <div class="form-group">
                                    <label for="periodo_hasta">Periodo hasta</label>
                                    <input id="periodo_hasta" name="periodo_hasta" type="text" class="form-control datepicker" placeholder="Seleccione una fecha" value="<?php if ($result->periodo_hasta){ $f = strtotime($result->periodo_hasta);echo date('d-m-Y',$f );} ?>"  required autocomplete="off" readonly/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-10">
                                <div class="form-group">
                                    <label for="obeservaciones">Obeservaciones</label>
                                    <input  id="obeservaciones" name="obeservaciones" type="text" class="form-control" placeholder="Obeservaciones" value="<?=$result->observaciones?>" readonly/>
                                </div>
                            </div>
                        </div>
		<div class="control-group">
		  <div class="controls">
                      <div class="row">
                        <div class="col-xs-8" style="text-align: left">
<!--                               <a class="btn btn-primary factura_b" href="<?php echo base_url().'liquidaciones/liquidaciones_clientes/edit/'.$result->id ?>" ><i class="fa fa-arrow-down"></i> Factura A</a>
                               <a class="btn btn-primary factura_b" href="<?php echo base_url().'liquidaciones/liquidaciones_clientes/edit_factura_b/'.$result->id ?>" ><i class="fa fa-arrow-down"></i> Factura B</a>
                                  <a class="btn btn-primary liquidacion_x" href="<?php echo base_url().'liquidaciones/liquidaciones_clientes/edit_factura_x/'.$result->id ?>" ><i class="fa fa-arrow-down"></i> Liquidación X</a>
                                <a class="btn btn-primary pendientes" href="javascript:;" ><i class="fa fa-arrow-down"></i> Pendientes</a>-->
                               <a class="btn btn-primary exportarAPdf" href="javascript:;" ><i class="fa fa-arrow-down"></i> Exportar PDF</a>
                               <a class="btn btn-danger"  href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2); ?>"><i class="fa fa-arrow-circle-left"></i> Volver</a>
                        </div>
                        <div class="col-xs-4" style="text-align: right">    
                          <?php if($result->estado_id != 2){
                                    echo form_button(array('type'  =>'submit','value' =>'Guardar','name'  =>'submit','class' =>'btn btn-success','id' => 'guardar'), "<i class='fa fa-floppy-o'></i> Guardar"); ?> 
                          <?php }?>
                          <!--<a onClick="confirmarLiquidacion('<?php echo base_url().'liquidaciones/liquidaciones_clientes/confirmar/'.$result->id ?>')" href="#" class="btn btn-success" <?php echo $result->estado_id == 2?'disabled':''?>><i class="fa fa-check-square-o"></i>Confirmar Liquidación</a>-->
                        </div>
                    </div>
		</div>
                <div>&nbsp;</div>
                <div id="grilla">
                    <table class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
                        <thead>
                            <tr>
                                <th>Comprobante ing.</th>
                                <th>Remito</th>
                                <th style="width:100px">Fecha</th>
                                <th>Descripcion</th>
                                <th>Dimensiones</th>
                                <th>Peso</th>
                                <th>Bultos</th>
                                <th>Codigo</th>
                                <th>Valor Calculado</th>
                            </tr>
                        </thead>
                        <tbody id="body-grilla">
                        <?php   $suma_total = 0;
                                $contador = 0;
                                foreach ($detalles as $detalle) {?>
                                    <?php   //$signo = (double)$detalle->cant_a_liquidar < 0?-1:1;
                                            //$importe = (int)$detalle->cant_a_liquidar * (float)number_format($detalle->precio,3) *(int)$signo;
                                            //$importe_subtotal += (double)$importe;
                                            $estilo = $detalle->liquidacion_ajuste_id > 0?"color:red":"";
                                            ?>
                                    <tr id="resultados" class="nueva"><input type="hidden" name="fila_<?=$contador?>" value="<?=$contador?>"/>
                                        <td style="text-align:left;<?php echo $estilo?>"><?=$detalle->numero?></td>
                                        <td style="text-align:left;<?php echo $estilo?>"><?=$detalle->remito_cliente?></td>
                                        <td style="text-align:left;<?php echo $estilo?>"><?=$detalle->fecha?></td>
                                        <td style="text-align:left;<?php echo $estilo?>"><?=$detalle->descripcion_paquete?></td>
                                        <td style="text-align:right;<?php echo $estilo?>"><?=$detalle->dimensiones?></td>
                                        <td style="text-align:right;<?php echo $estilo?>"><?=$detalle->peso?></td>
                                        <td style="text-align:right;<?php echo $estilo?>"><?=$detalle->bultos?></td>
                                        <td style="text-align:right;<?php echo $estilo?>"><?=$detalle->codigo?></td>
                                        <td style="text-align:right;<?php echo $estilo?>"><?=$detalle->precio;?></td>
                                     </tr>
                                     <?php $contador++;
                                            $suma_total += $detalle->precio;
                                };
                                
                                $iva = $suma_total*0.21;
                                $importe_total = ($suma_total + $iva);
                                ?>
                                
                                     <tr>
                                         <td colspan="8" style="text-align:right;font-weight: bold">SUBTOTAL: </td>
                                         <td  style="text-align:right;font-weight: bold"><?=number_format($suma_total,3);?></td> 
                                     </tr>
                                     <tr>
                                        <td colspan="8" style="text-align:right;font-weight: bold">IVA: </td>
                                        <td style="text-align:right;font-weight: bold"><?=number_format($iva,3);?></td>  
                                     </tr>
                                     <tr>
                                         <td colspan="8" style="text-align:right;font-weight: bold">TOTAL: </td>
                                        <td style="text-align:right;font-weight: bold"><?=number_format($importe_total,3);?></td> 
                                     </tr>
                               
                                <?php if($tipo_factura == "B"){?>
                                     <tr>
                                         <td colspan="6" style="text-align:right;font-weight: bold">TOTAL: </td>
                                        <td style="text-align:right;font-weight: bold"><?=number_format($importe_subtotal,3);?></td> 
                                        <td></td>
                                     </tr>
                                <?php }?>
                                <?php if($tipo_factura == "X"){?>
                                     <tr>
                                         <td colspan="6" style="text-align:right;font-weight: bold">TOTAL: </td>
                                        <td style="text-align:right;font-weight: bold"><?=number_format($importe_subtotal,3);?></td> 
                                        <td></td>
                                     </tr>
                                <?php }?>
                        </tbody>
                    </table>
                </div>
                </div>
            </form>
	</div>
</div>
    <form method='post' action='<?php echo base_url('liquidaciones/liquidaciones_clientes_paqueteria/exportarAPdf')?>' id='exportAPdf'>
        <input type='hidden' id='htmlLiquidacionSinIva' name='htmlLiquidacionSinIva' value="">
        <input type='hidden' id='htmlResumenLiquidacionSinIva' name='htmlResumenLiquidacionSinIva' value="">
        <input type='hidden' id='pdf_liquidacion_nro' name='pdf_liquidacion_nro' value="">
        <input type='hidden' id='pdf_cliente_id' name='pdf_cliente_id' value="">
        <input type='hidden' id='pdf_factura_nro' name='pdf_factura_nro' value="">
        <input type='hidden' id='pdf_periodo_desde' name='pdf_periodo_desde' value="">
        <input type='hidden' id='pdf_periodo_hasta' name='pdf_periodo_hasta' value="">
        <input type='hidden' id='pdf_sucursal' name='pdf_sucursal' value="">
        <input type='hidden' id='pdf_cantidad_elementos' name='pdf_cantidad_elementos' value="">
        <input type='hidden' id='pdf_departamento' name='pdf_departamento' value="">
        <input type='hidden' id='pdf_sucursal_id' name='pdf_sucursal_id' value="">
        <input type='hidden' id='pdf_departamento_id' name='pdf_departamento_id' value="">
        <input type='hidden' id='pdf_imprimir' name='pdf_imprimir' value="">
        <input type='hidden' id='pdf_liquidacion_id' name='pdf_liquidacion_id' value="">
        <input type='hidden' id='pdf_iva_puro' name='pdf_iva_puro' value="">
        <input type='hidden' id='pdf_tipo_factura' name='pdf_tipo_factura' value="">
        <input type='hidden' id='pdf_paqueteria_id' name='pdf_paqueteria_id' value="">
   </form>    
</div>
<script type="text/javascript">
    function confirmarLiquidacion(link){
            bootbox.confirm("Al Confirmar la Liquidación, la misma no podrá volver a editarse, ¿desea continuar?", function(result) {
              if (result === true) {
                  $.ajax({
                      type: "GET",
                      url: link,
                      data: {},
                      cache: false,
                      success: function(data){window.location.reload();}
                  });
              }
            });
          }
          
    $(document).ready(function() {
        $('#cliente').typeahead({
            source: <?=json_encode($clientes) ?>,
            displayField: 'nombre',
            onSelect: function(item) {
                if (item.value) {
                    $('#cliente').val(item.value);
                    $('#cliente_id').val(item.value);
                    if (item.value != 0){
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

                                $("#departamento_id").append(options);
                                $("#departamento_id").trigger("liszt:updated");
                            });
                        }else{
                            $("#departamento_id").find("option").remove();
                        }

                        $('#departamento_id').removeClass('loading-ajax');
                    }
                }
            }
        });
    
//        $('.factura_b').click(function(event) {
//            $('#grilla').html('');
//            $('#grilla_resumen').html('');
//            $('#estado_id').val('0');//var form_data = new FormData();
//            $('#guardar').removeAttr("disabled");
//            $('.exportarAPdf').removeAttr("disabled");
//            var other_data = $('#form_liquidacion_add').serializeArray();
//            var form_data = new FormData();
//            $.each(other_data,function(key,input){
//                form_data.append(input.name,input.value);
//            });
//
//             $.ajax({
//                         url: '<?php echo base_url()."ajax/liquidaciones/getLiquidacionEdit" ?>',
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
//                                                            '<th>Comprobante ing.</th>'+
//                                                            '<th>Remito Cliente</th>'+
//                                                            '<th>Fecha</th>'+
//                                                            '<th>Servicio</th>'+
//                                                            '<th>Cantidad</th>'+
//                                                            '<th>Precio</th>'+
//                                                            '<th>Importe</th>'+
//                                                        '</tr>'+
//                                                    '</thead>';
//                                var body = '<tbody id="body-grilla">';
//                                var suma_total = 0;
//                                var iva_puro = $('#iva').val();
//                                $('#cantidad_elementos').val(data['grilla'].length);
//                                $.each(data['grilla'], function(index, element) {
//                                        var precio_con_iva = (parseFloat(element['precio']) + (parseFloat(element['precio']) * iva_puro)).toFixed(3);
//                                        var importe = (precio_con_iva * parseFloat(element['cantidad'])).toFixed(3);
//                                        body    += '<tr id="resultados" class="nueva"><input type="hidden" name="fila_'+contador+'" value="'+contador+'"/>'+
//                                                '<td class="barcodear">'+element['numero']+'</td><input type="hidden" id="numero_'+contador+'" name="numero_'+contador+'" value="'+element['numero']+'"/> '+
//                                                '<td class="">'+element['remito']+'</td>'+'</td><input type="hidden" id="remito_'+contador+'" name="remito_'+contador+'" value="'+element['remito']+'"/> '+
//                                                '<td class="">'+element['fecha']+'</td>'+'</td><input type="hidden" id="fecha_'+contador+'" name="fecha_'+contador+'" value="'+element['fecha']+'"/> '+
//                                                '<td class="">'+element['servicio']+'</td>'+'</td><input type="hidden" id="servicio_'+contador+'" name="servicio_'+contador+'" value="'+element['servicio']+'"/> '+
//                                                '<td class="">'+element['cantidad']+'</td>'+'</td><input type="hidden" id="cantidad_'+contador+'" name="cantidad_'+contador+'" value="'+element['cantidad']+'"/> '+
//                                                '<td class="">'+precio_con_iva+'</td>'+'</td><input type="hidden" id="precio_'+contador+'" name="precio_'+contador+'" value="'+element['precio']+'"/> '+
//                                                '<td class="">'+importe+'</td>'+'</td><input type="hidden" id="importe_'+contador+'" name="importe_'+contador+'" value="'+importe+'"/> '+
//                                            '</tr>';
// 
//                                     contador++;
//                                     suma_total = parseFloat(suma_total)+parseFloat(importe);
//                                 });
//                                 var cierre = '</tbody></table>';
//                                 var total = '<tr>'+
//                                                '<td class=""></td>'+
//                                                '<td class=""></td>'+
//                                                '<td class=""></td>'+
//                                                '<td class=""></td>'+
//                                                '<td class=""></td>'+
//                                                '<td class=""><strong>Total</td></strong>'+
//                                                '<td class=""><strong>'+suma_total.toFixed(3)+'</strong></td>'+
//                                            '</tr>';
//                                 $('#grilla').append(cabecera+body+total+cierre); 
//                                 barcodear();
//                                 //Tabla Resumen
//                                 $('#grilla_resumen').html('');
//
//                                 var cabecera_resumen = '<table class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered" style="width:50%">'+
//                                                    '<thead>'+
//                                                        '<tr>'+
//                                                            '<th>Código</th>'+
//                                                            '<th>Servicio</th>'+
//                                                            '<th>Cantidad</th>'+
//                                                        '</tr>'+
//                                                    '</thead>';
//                                var body_resumen = '<tbody id="body-grilla-resumen">';
//                                 $.each(data['resumen'], function(index, element) {
//                                     
//                                      body_resumen    += '<tr id="" class="nueva">'+
//                                                            '<td class="barcodear">'+element['codigo']+'</td>'+
//                                                            '<td class="">'+element['servicio']+'</td>'+
//                                                            '<td class="">'+element['cantidad']+'</td>'+
//                                                        '</tr>';
//                                 });
//                                 var cierre_resumen = '</tbody></table>';
//                                 $('#grilla_resumen').append(cabecera_resumen+body_resumen+cierre_resumen);
//                                 $('#imprimir').val('facturab');
//                             }
//                         }
//                     });
//          }); 
          
           $('.exportarAPdf').click(function(event) {
                $("#htmlLiquidacionSinIva").val(jQuery("#grilla").html());
                $("#htmlResumenLiquidacionSinIva").val(jQuery("#grilla_resumen").html());
                $("#pdf_liquidacion_nro").val(jQuery("#nro").val());
                $("#pdf_cliente_id").val(jQuery("#cliente_id").val());
                $("#pdf_factura_nro").val(jQuery("#factura").val());
                $("#pdf_periodo_desde").val(jQuery("#periodo_desde").val());
                $("#pdf_periodo_hasta").val(jQuery("#periodo_hasta").val());
                $("#pdf_sucursal").val($( "#sucursal_id option:selected" ).text());
                $("#pdf_departamento").val($( "#departamento_id option:selected" ).text());
                $("#pdf_cantidad_elementos").val($( "#cantidad_elementos" ).val());
                $("#pdf_sucursal_id").val($( "#sucursal_id" ).val());
                $("#pdf_departamento_id").val($( "#departamento_id" ).val());
                $("#pdf_liquidacion_id").val($( "#liquidacion_cliente_id" ).val());
                $("#pdf_iva_puro").val($( "#iva" ).val());
                $("#pdf_paqueteria_id").val($( "#paqueteria_id" ).val());
                $("#pdf_imprimir").val($( "#imprimir" ).val());
                $("#pdf_tipo_factura").val("<?php echo $tipo_factura?>");
                $('#exportAPdf').submit();
           });
           
           $('.pendientes').click(function(event) {
                $('.exportarAPdf').attr("disabled","disabled");
                $('#grilla').html('');
                $('#grilla_resumen').html('');
                $('#guardar').attr("disabled","disabled");
                $('#estado_id').val('');//var form_data = new FormData();
                var other_data = $('#form_liquidacion_add').serializeArray();
                var form_data = new FormData();
                $.each(other_data,function(key,input){
                    form_data.append(input.name,input.value);
                });
                var selector = '';
                //if ($('#cliente_id').val() !== '' && $('#periodo_desde').val() === '' && $('#periodo_hasta').val() === '' ){
                    $.ajax({
                         url: '<?php echo base_url()."ajax/liquidaciones/getLiquidacionEdit" ?>',
                         dataType: 'json',
                         cache: false,
                         contentType: false,
                         processData: false,
                         data: form_data,                
                         type: 'post',
                         success: function(data){
                             if (data.status){
                                 $('#errores').html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Error</strong> No dispone la cantidad ingresada para este servicio.</div>');
                             }else{
                                  $('#grilla').html('');
                                 var contador = 0;
                                 var cabecera = '<table class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">'+
                                                    '<thead>'+
                                                        '<tr>'+
                                                            '<th>Cliente</th>'+
                                                            '<th>Comprobante</th>'+
                                                            '<th>Servicio</th>'+
                                                            '<th>Fecha</th>'+
                                                            '<th>Cantidad Piezas</th>'+
                                                        '</tr>'+
                                                    '</thead>';
                                var body = '<tbody id="body-grilla">';
                                var suma_total = 0;
                                $('#cantidad_elementos').val(data['grilla'].length);
                                $.each(data['grilla'], function(index, element) {
                                     
                                      body    += '<tr id="resultados" class="nueva"><input type="hidden" name="fila_'+contador+'" value="'+contador+'"/>'+
                                                    '<td>'+element['nombre']+'</td><input type="hidden" id="nombre_'+contador+'" name="nombre_'+contador+'" value="'+element['nombre']+'"/> '+
                                                    '<td class="barcodear">'+element['numero']+'</td>'+'</td><input type="hidden" id="numero_'+contador+'" name="numero_'+contador+'" value="'+element['numero']+'"/> '+
                                                    '<td class="">'+element['servicio']+'</td>'+'</td><input type="hidden" id="servicio_'+contador+'" name="servicio_'+contador+'" value="'+element['servicio']+'"/> '+
                                                    '<td class="">'+element['fecha']+'</td>'+'</td><input type="hidden" id="fecha_'+contador+'" name="fecha_'+contador+'" value="'+element['fecha']+'"/> '+
                                                    '<td class="">'+element['cantidad']+'</td>'+'</td><input type="hidden" id="cantidad_'+contador+'" name="cantidad_'+contador+'" value="'+element['cantidad']+'"/> '+
                                                '</tr>';
                                    // }
 
                                     contador++;
                                     suma_total = parseFloat(suma_total)+parseFloat(element['importe']);
                                 });
                                 var cierre = '</tbody></table>';
                                 $('#grilla').append(cabecera+body+cierre); 
                                 barcodear();
                                 //Tabla Resumen
                                 $('#grilla_resumen').html('');
                             }
                         }
                     });
                //}
                if ($('#cliente_id').val() === '' && ($('#periodo_desde').val() !== '' || $('#periodo_hasta').val() !== '') ){
                    $.ajax({
                         url: '<?php echo base_url()."ajax/liquidaciones/getLiquidacionEdit" ?>',
                         dataType: 'json',
                         cache: false,
                         contentType: false,
                         processData: false,
                         data: form_data,                
                         type: 'post',
                         success: function(data){
                             if (data.status){
                                 $('#errores').html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Error</strong> No dispone la cantidad ingresada para este servicio.</div>');
                             }else{
                                  $('#grilla').html('');
                                 var contador = 0;
                                 var cabecera = '<table class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">'+
                                                    '<thead>'+
                                                        '<tr>'+
                                                            '<th>Cliente</th>'+
                                                            '<th>Comprobante</th>'+
                                                            '<th>Servicio</th>'+
                                                            '<th>Fecha</th>'+
                                                            '<th>Cantidad Piezas</th>'+
                                                        '</tr>'+
                                                    '</thead>';
                                var body = '<tbody id="body-grilla">';
                                var suma_total = 0;
                                var iva = 0;
                                $('#cantidad_elementos').val(data['grilla'].length);
                                $.each(data['grilla'], function(index, element) {
                                     
                                      body    += '<tr id="resultados" class="nueva"><input type="hidden" name="fila_'+contador+'" value="'+contador+'"/>'+
                                                    '<td>'+element['nombre']+'</td><input type="hidden" id="nombre_'+contador+'" name="nombre_'+contador+'" value="'+element['nombre']+'"/> '+
                                                    '<td class="barcodear">'+element['numero']+'</td>'+'</td><input type="hidden" id="numero_'+contador+'" name="numero_'+contador+'" value="'+element['numero']+'"/> '+
                                                    '<td class="">'+element['servicio']+'</td>'+'</td><input type="hidden" id="servicio_'+contador+'" name="servicio_'+contador+'" value="'+element['servicio']+'"/> '+
                                                    '<td class="">'+element['fecha']+'</td>'+'</td><input type="hidden" id="fecha_'+contador+'" name="fecha_'+contador+'" value="'+element['fecha']+'"/> '+
                                                    '<td class="">'+element['cantidad']+'</td>'+'</td><input type="hidden" id="cantidad_'+contador+'" name="cantidad_'+contador+'" value="'+element['cantidad']+'"/> '+
                                                '</tr>';
                                    // }
 
                                     contador++;
                                 });
                                 var cierre = '</tbody></table>';
                                 $('#grilla').append(cabecera+body+cierre); 
                                 barcodear();
                             }
                         }
                     });
                }
                
          });
          
        $('.liquidacion_x').click(function(event) {
            //alert($('.form_liquidacion_add').serialize());
            $('#grilla').html('');
            $('#grilla_resumen').html('');
            $('#estado_id').val('0');//var form_data = new FormData();
            $('#guardar').removeAttr("disabled");
            $('.exportarAPdf').removeAttr("disabled");
            var other_data = $('#form_liquidacion_add').serializeArray();
            var form_data = new FormData();
            $.each(other_data,function(key,input){
                form_data.append(input.name,input.value);
            });

             $.ajax({
                         url: '<?php echo base_url()."ajax/liquidaciones/getLiquidacionEdit" ?>',
                         dataType: 'json',
                         cache: false,
                         contentType: false,
                         processData: false,
                         data: form_data,                
                         type: 'post',
                         success: function(data){
                             if (data.status){
                                 $('#errores').html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Error</strong> No dispone la cantidad ingresada para este servicio.</div>');
                             }else{
                                  $('#grilla').html('');
                                 var contador = 0;
                                 var cabecera = '<table class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">'+
                                                    '<thead>'+
                                                        '<tr>'+
                                                            '<th>Comprobante ing.</th>'+
                                                            '<th>Remito Cliente</th>'+
                                                            '<th>Fecha</th>'+
                                                            '<th>Servicio</th>'+
                                                            '<th>Cantidad</th>'+
                                                            '<th>Precio</th>'+
                                                            '<th>Importe</th>'+
                                                        '</tr>'+
                                                    '</thead>';
                                var body = '<tbody id="body-grilla">';
                                var suma_total = 0;
                                $('#cantidad_elementos').val(data['grilla'].length);
                                $.each(data['grilla'], function(index, element) {
                                      var precio = parseFloat(element['precio']);
                                      var importe = precio * parseFloat(element['cantidad']);
                                      body    += '<tr id="resultados" class="nueva"><input type="hidden" name="fila_'+contador+'" value="'+contador+'"/>'+
                                                    '<td class="barcodear">'+element['numero']+'</td><input type="hidden" id="numero_'+contador+'" name="numero_'+contador+'" value="'+element['numero']+'"/> '+
                                                    '<td class="">'+element['remito']+'</td>'+'</td><input type="hidden" id="remito_'+contador+'" name="remito_'+contador+'" value="'+element['remito']+'"/> '+
                                                    '<td class="">'+element['fecha']+'</td>'+'</td><input type="hidden" id="fecha_'+contador+'" name="fecha_'+contador+'" value="'+element['fecha']+'"/> '+
                                                    '<td class="">'+element['servicio']+'</td>'+'</td><input type="hidden" id="servicio_'+contador+'" name="servicio_'+contador+'" value="'+element['servicio']+'"/> '+
                                                    '<td class="">'+element['cantidad']+'</td>'+'</td><input type="hidden" id="cantidad_'+contador+'" name="cantidad_'+contador+'" value="'+element['cantidad']+'"/> '+
                                                    '<td class="">'+precio.toFixed(3)+'</td>'+'</td><input type="hidden" id="precio_'+contador+'" name="precio_'+contador+'" value="'+precio+'"/> '+
                                                    '<td class="">'+importe.toFixed(3)+'</td>'+'</td><input type="hidden" id="importe_'+contador+'" name="importe_'+contador+'" value="'+importe+'"/> '+
                                                '</tr>';
                                    // }
 
                                     contador++;
                                     suma_total = parseFloat(suma_total)+parseFloat(importe);
                                 });
                                 var cierre = '</tbody></table>';
                                 var total = '<tr>'+
                                                '<td class=""></td>'+
                                                '<td class=""></td>'+
                                                '<td class=""></td>'+
                                                '<td class=""></td>'+
                                                '<td class=""></td>'+
                                                '<td class=""></td>'+
                                                '<td class=""><strong>Total</td></strong>'+
                                                '<td class=""><strong>'+suma_total.toFixed(3)+'</strong></td>'+
                                            '</tr>';
                                 $('#grilla').append(cabecera+body+total+cierre); 
                                 barcodear();
                                 //Tabla Resumen
                                 $('#grilla_resumen').html('');

                                 var cabecera_resumen = '<table class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered" style="width:50%">'+
                                                    '<thead>'+
                                                        '<tr>'+
                                                            '<th>Código</th>'+
                                                            '<th>Servicio</th>'+
                                                            '<th>Cantidad</th>'+
                                                        '</tr>'+
                                                    '</thead>';
                                var body_resumen = '<tbody id="body-grilla-resumen">';
                                 $.each(data['resumen'], function(index, element) {
                                     
                                      body_resumen    += '<tr id="" class="nueva">'+
                                                            '<td class="barcodear">'+element['codigo']+'</td>'+
                                                            '<td class="">'+element['servicio']+'</td>'+
                                                            '<td class="">'+element['cantidad']+'</td>'+
                                                        '</tr>';
                                 });
                                 var cierre_resumen = '</tbody></table>';
                                 $('#grilla_resumen').append(cabecera_resumen+body_resumen+cierre_resumen); 
                             }
                         }
                     });
          }); 
    });
    
    function agregarAjuste(comprobante_ingreso_id, servicio_id){
       alert(comprobante_ingreso_id +'   '+servicio_id);
    }
    
</script>