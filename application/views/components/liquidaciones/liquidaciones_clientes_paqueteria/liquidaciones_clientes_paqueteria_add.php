<div class="col-xs-12">
    <div class="ibox-content">
	<form class="form_liquidacion_cliente_paqueteria_add" id="form_liquidacion_cliente_paqueteria_add" name="form_liquidacion_cliente_paqueteria_add" action="<?php echo base_url('liquidaciones/liquidaciones_clientes_paqueteria/add')?>" method="post" enctype="multipart/form-data">
            <?php   echo form_hidden('enviar_form','1');?>
                    <input type='hidden' id='cantidad_elementos' name='cantidad_elementos' value="">
                    <input type='hidden' id='estado_id' name='estado_id' value="">
                    <input type='hidden' id='iva' name='iva' value="">
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="cliente_id">Cliente</label>
                                    <input id="cliente" type="text" class="form-control filtro-group" placeholder="Cliente" autocomplete="off"/>
                                    <input id="cliente_id" name="cliente_id" type="hidden" value=""/>
                                </div>
                            </div>
                            <div class="col-xs-3">
                                <div class="form-group">
                                  <label for="dpto">Dpto</label>
                                  <select id="departamento_id" name="departamento_id" class="form-control filtro-group">
                                    <option value="">Seleccione</option>
                                    <?php foreach ($departamentos as $departamento): ?>
                                      <option value="<?=$departamento->id ?>"><?=$departamento->nombre ?></option>
                                    <?php endforeach; ?>
                                  </select>
                                </div>
                              </div>
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
                                    <label for="factura">Factura</label>
                                    <input  id="factura" name="factura" type="text" class="form-control" placeholder="Factura" />
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                  <label for="paqueteria">Paqueteria</label>
                                  <select id="paqueteria_id" name="paqueteria_id" class="form-control filtro-group">
                                    <option value="">Seleccione</option>
                                    <?php foreach ($paqueterias as $paqueteria): ?>
                                      <option value="<?=$paqueteria->id ?>"><?=$paqueteria->nombre ?></option>
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
                        <div class="row">
                            <div class="col-xs-10">
                                <div class="form-group">
                                    <label for="obeservaciones">Obeservaciones</label>
                                    <input  id="obeservaciones" name="obeservaciones" type="text" class="form-control" placeholder="Obeservaciones" />
                                </div>
                            </div>
                        </div>
<!--                        <label> Usar este filtro solo para Clientes Pendientes</label>
                        <div class="row">
                            <div class="col-xs-3">
                                <div class="form-group">
                                    <label for="fecha_ci_desde">Fecha C.I. desde</label>
                                    <input id="fecha_ci_desde" name="fecha_ci_desde" type="text" class="form-control datepicker" placeholder="Seleccione una fecha" value="<?php if ($fecha_ci_hasta){ $f = strtotime($fecha_ci_hasta);echo date('d-m-Y',$f );} ?>"  autocomplete="off" />
                                </div>
                            </div>
                            <div class="col-xs-3">
                                <div class="form-group">
                                    <label for="fecha_ci_hasta">Fecha C.I. hasta</label>
                                    <input id="fecha_ci_hasta" name="fecha_ci_hasta" type="text" class="form-control datepicker" placeholder="Seleccione una fecha" value="<?php if ($fecha_ci_hasta){ $f = strtotime($fecha_ci_hasta);echo date('d-m-Y',$f );} ?>"  autocomplete="off" />
                                </div>
                            </div>
                        </div>                   -->
		<div class="control-group">
		  <div class="controls">
                    <a class="btn btn-primary factura_a" href="javascript:;" ><i class="fa fa-arrow-down"></i> Factura A</a>
<!--                    <a class="btn btn-primary factura_b" href="javascript:;" ><i class="fa fa-arrow-down"></i> Factura B</a>
                    <a class="btn btn-primary liquidacion_x" href="javascript:;" ><i class="fa fa-arrow-down"></i> Liquidación X</a>-->
                    <!--<a class="btn btn-primary pendientes" href="javascript:;" ><i class="fa fa-arrow-down"></i> Pendientes</a>-->
                   <!-- <a class="btn btn-primary exportarAPdf" href="javascript:;" ><i class="fa fa-arrow-down"></i> Exportar PDF</a>-->
                    <!--<a class="btn btn-primary clientesPendientes" href="javascript:;" ><i class="fa fa-arrow-down"></i> Clientes Pendientes</a>-->
                        <?php echo form_button(array('type'  =>'submit','value' =>'Guardar','name'  =>'submit','class' =>'btn btn-success','id' => 'guardar'), "<i class='fa fa-floppy-o'></i> Guardar"); ?> 
		    <a class="btn btn-danger" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2); ?>"><i class="fa fa-arrow-circle-left"></i> Volver</a>
		  </div>
		</div>
                <div>&nbsp;</div>
                <div id="grilla"></div>
                <div id="grilla_resumen"></div>
            </form>
	</div>

    <form method='post' action='<?php echo base_url('liquidaciones/liquidaciones_clientes/exportarAPdf')?>' id='exportAPdf'>
<!--        <input type='hidden' id='htmlLiquidacionSinIva' name='htmlLiquidacionSinIva' value="">
        <input type='hidden' id='htmlResumenLiquidacionSinIva' name='htmlResumenLiquidacionSinIva' value="">-->
        <input type='hidden' id='pdf_liquidacion_nro' name='pdf_liquidacion_nro' value="">
        <input type='hidden' id='pdf_cliente_id' name='pdf_cliente_id' value="">
        <input type='hidden' id='pdf_factura_nro' name='pdf_factura_nro' value="">
        <input type='hidden' id='pdf_periodo_desde' name='pdf_periodo_desde' value="">
        <input type='hidden' id='pdf_periodo_hasta' name='pdf_periodo_hasta' value="">
        <input type='hidden' id='pdf_sucursal' name='pdf_sucursal' value="">
        <input type='hidden' id='pdf_sucursal_id' name='pdf_sucursal_id' value="">
        <input type='hidden' id='pdf_cantidad_elementos' name='pdf_cantidad_elementos' value="">
        <input type='hidden' id='pdf_departamento' name='pdf_departamento' value="">
        <input type='hidden' id='pdf_departamento_id' name='pdf_departamento_id' value="">
        <input type='hidden' id='imprimir' name='imprimir' value="">
        <input type='hidden' id='pdf_fecha_ci_desde' name='pdf_fecha_ci_desde' value="">
        <input type='hidden' id='pdf_fecha_ci_hasta' name='pdf_fecha_ci_hasta' value="">
   </form>    
</div>
<script type="text/javascript">
    $(document).ready(function() {
        
        $('#cliente').typeahead({
            source: <?=json_encode($clientes) ?>,
            displayField: 'nombre',
            onSelect: function(item) {
                if (item.value) {
                    $('#cliente').val(item.nombre);
                    $('#cliente_id').val(item.value);
                    $("#iva").val("0.21");
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
                            //Traigo el tipo de Paqueteria
                            var url = '<?php echo base_url() ?>' + 'ajax/clientes/paqueteria/' + $('#cliente_id').val();
                            $.getJSON(url, function(data) {
                                $("#paqueteria_id").find("option").remove();                
                                var options = '';

                                if(!data.status){
                                    $.each(data, function(key, val) {
                                        options = options + "<option value='"+val.id+"'>"+ val.nombre +"</option>";
                                    });              
                                }else{
                                    options = options + "<option value='0' disabled>Sin resultados</option>";
                                }
                                $("#paqueteria_id").append(options);
                                $("#paqueteria_id").trigger("liszt:updated");
                            });
                        }else{
                             $("#departamento_id").find("option").remove();
                            $("#paqueteria_id").find("option").remove();
                        }
                        $('#departamento_id').removeClass('loading-ajax');
                        $('#paqueteria_id').removeClass('loading-ajax');
                    }
                }
            }
        });

        $('#cliente_id').change(function(event) {
            
        });
    
        $('.factura_b').click(function(event) {
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
                         url: '<?php echo base_url()."ajax/liquidaciones/getPiezasPendientesLiquidar" ?>',
                         dataType: 'json',
                         cache: false,
                         contentType: false,
                         processData: false,
                         data: form_data,                
                         type: 'post',
                         success: function(data){
                             if (data.status){
                                 $('#grilla').html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>No se encontraron resultados.</div>');
                             }else{
                                  $('#grilla').html('');
                                 var contador = 0;
                                 var cabecera = '<table class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">'+
                                                    '<thead>'+
                                                        '<tr>'+
                                                            '<th>Comprobante ing.</th>'+
                                                            '<th>Remito</th>'+
                                                            '<th>Fecha</th>'+
                                                            '<th>Servicio</th>'+
                                                            '<th>Cantidad</th>'+
                                                            '<th>Precio</th>'+
                                                            '<th>Importe</th>'+
                                                            '<th>Ajustes</th>'+
                                                        '</tr>'+
                                                    '</thead>';
                                var body = '<tbody id="body-grilla">';
                                var suma_total = 0;
                                var iva = $('#iva').val();
                                $('#cantidad_elementos').val(data['grilla'].length);
                                $.each(data['grilla'], function(index, element) {
                                      var precio_con_iva = ((parseFloat(element['precio']) * parseFloat(iva))+parseFloat(element['precio'])).toFixed(3);
                                      var importe = parseFloat(precio_con_iva) * parseInt(element['cantidad']);
                                      body    += '<tr id="resultados" class="nueva"><input type="hidden" name="fila_'+contador+'" value="'+contador+'"/>'+
                                                    '<td class="">'+element['numero']+'</td><input type="hidden" id="numero_'+contador+'" name="numero_'+contador+'" value="'+element['numero']+'"/> '+
                                                    '<td class="">'+element['remito']+'</td><input type="hidden" id="remito_'+contador+'" name="remito_'+contador+'" value="'+element['remito']+'"/> '+
                                                    '<td class="">'+element['fecha']+'</td>'+'</td><input type="hidden" id="fecha_'+contador+'" name="fecha_'+contador+'" value="'+element['fecha']+'"/> '+
                                                    '<input type="hidden" id="servicio_id_'+contador+'" name="servicio_id_'+contador+'" value="'+element['servicio_id']+'"/> '+
                                                    '<td class="">'+element['servicio_especial']+'</td>'+'</td><input type="hidden" id="servicio_'+contador+'" name="servicio_'+contador+'" value="'+element['servicio_especial']+'"/> '+
                                                    '<td class="">'+element['cantidad']+'</td>'+'</td><input type="hidden" id="cantidad_'+contador+'" name="cantidad_'+contador+'" value="'+element['cantidad']+'"/> '+
                                                    '<td class="">'+precio_con_iva+'</td>'+'</td><input type="hidden" id="precio_'+contador+'" name="precio_'+contador+'" value="'+parseFloat(element['precio']).toFixed(3)+'"/> '+
                                                    '<td class="">'+importe.toFixed(3)+'</td>'+'</td><input type="hidden" id="importe_'+contador+'" name="importe_'+contador+'" value="'+importe.toFixed(3)+'"/> '+
                                                    '<td><a href="javascript:;" onclick="agregarAjuste(this,'+element["numero"]+','+element["servicio_id"]+')" class="btn btn-success"><i class="fa fa-plus"></i></a></td>'+
                                                '</tr>';
                                    // }
 
                                     contador++;
                                     suma_total = parseFloat(suma_total)+importe;
                                 });
                                 var cierre = '</tbody></table>';
                                 var total = '<tr>'+
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
                                 $('#imprimir').val('facturab');
                             }
                         }
                     });
          }); 
          
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
                $("#pdf_sucursal_id").val($( "#sucursal_id" ).val());
                $("#pdf_departamento_id").val($( "#departamento_id" ).val());
                $("#pdf_cantidad_elementos").val($( "#cantidad_elementos" ).val());
                $("#pdf_fecha_ci_desde").val(jQuery("#pdf_fecha_ci_desde").val());
                $("#pdf_fecha_ci_hasta").val(jQuery("#pdf_fecha_ci_hasta").val());
                $('#exportAPdf').submit();
           });
           
           $('.factura_a').click(function(event) {
            //alert($('.form_liquidacion_add').serialize());
            $('#estado_id').val('1');//var form_data = new FormData();
            $('#grilla').html('');
            $('#grilla_resumen').html('');
            $('#guardar').removeAttr("disabled");
            $('.factura_b').removeAttr("disabled");
            $('.exportarAPdf').removeAttr("disabled");
            var other_data = $('#form_liquidacion_cliente_paqueteria_add').serializeArray();
            var form_data = new FormData();
            $.each(other_data,function(key,input){
                form_data.append(input.name,input.value);
            });

             $.ajax({
                         url: '<?php echo base_url()."ajax/liquidaciones/getLiquidacionClientePaqueteria" ?>',
                         dataType: 'json',
                         cache: false,
                         contentType: false,
                         processData: false,
                         data: form_data,                
                         type: 'post',
                         success: function(data){//alert(JSON.stringify(data));
                             if (data.status){
                                 $('#grilla').html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>No se encontraron resultados.</div>');
                             }else{
                                  $('#grilla').html('');
                                 var contador = 0;
                                 var cabecera = '<table class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">'+
                                                    '<thead>'+
                                                        '<tr>'+
                                                            '<th>Comprobante ing.</th>'+
                                                            '<th>Remito</th>'+
                                                            '<th style="width:100px">Fecha</th>'+
                                                            '<th>Descripcion</th>'+
                                                            '<th>Dimensiones</th>'+
                                                            '<th>Peso</th>'+
                                                            '<th>Codigo</th>'+
                                                            '<th>Bultos</th>'+
                                                            '<th>Codigo</th>'+
                                                            '<th>Valor Calculado</th>'+
                                                        '</tr>'+
                                                    '</thead>';
                                var body = '<tbody id="body-grilla">';
                                var suma_total = 0;
                                var iva_puro = $('#iva').val();
                                var i=0;
                                $('#cantidad_elementos').val(data['grilla'].length);//alert(JSON.stringify(data['grilla']));
                                $.each(data['grilla'], function(index, element) {
                                      var precio = data['valor_calculado'][i];
                                      var cant_disponible = 0;
                                      var cantidad =  element['bultos'];
                                      body    += '<tr id="resultados" class="nueva"><input type="hidden" name="fila_'+contador+'" value="'+element['comprobante_ingreso_id']+'|'+element['remito']+'|'+element['numero']+'|'+element['remito']+'|'+element['fecha']+'|'+element['servicio_id']+'|'+element['servicio_especial']+'|'+element['bultos']+'|'+cant_disponible+'|'+precio+'"/>'+
                                                    '<td class="">'+element['numero']+'</td>'+
                                                    '<td class="">'+element['remito']+'</td>'+
                                                    '<td style="width:100px">'+element['fecha']+'</td>'+
                                                    '<td class="">'+element['descripcion_paquete']+'</td>'+
                                                    '<td class="">'+element['dimensiones']+'</td>'+
                                                    '<td class="">'+element['peso']+'</td>'+
                                                    '<td class="">'+element['barcode_externo']+'</td>'+
                                                    '<td class="">'+element['bultos']+'</td>'+
                                                    '<td class="">'+element['barcode_externo']+'</td>'+
                                                    '<td style="width:80px;text-align:right">'+(parseFloat(data['valor_calculado'][i])).toFixed(3)+'</td>'+
                                                '</tr>';
                                     contador++;
                                     suma_total += parseFloat(data['valor_calculado'][i]);
                                     i++;
                                 });
                                 var iva_solo = suma_total * iva_puro;
                                 var total_con_iva = parseFloat(suma_total)+parseFloat(iva_solo);
                                 var cierre = '</tbody></table>';
                                 var total = '<tr>'+
                                                '<td class=""></td>'+
                                                '<td class=""></td>'+
                                                '<td class=""></td>'+
                                                '<td class=""></td>'+
                                                '<td class=""></td>'+
                                                '<td class=""></td>'+
                                                '<td class=""></td>'+
                                                '<td class=""></td>'+
                                                '<td style="text-align:right;font-weight: bold"><strong>Sub-Total:</td></strong>'+
                                                '<td style="text-align:right;font-weight: bold"><strong>'+suma_total.toFixed(3)+'</strong></td>'+
                                            '</tr>'+
                                            '<tr>'+
                                                '<td class=""></td>'+
                                                '<td class=""></td>'+
                                                '<td class=""></td>'+
                                                '<td class=""></td>'+
                                                '<td class=""></td>'+
                                                '<td class=""></td>'+
                                                '<td class=""></td>'+
                                                '<td class=""></td>'+
                                                '<td style="text-align:right;font-weight: bold"><strong>IVA:</td></strong>'+
                                                '<td style="text-align:right;font-weight: bold"><strong>'+iva_solo.toFixed(3)+'</strong></td>'+
                                            '</tr>'+
                                            '<tr>'+
                                                '<td class=""></td>'+
                                                '<td class=""></td>'+
                                                '<td class=""></td>'+
                                                '<td class=""></td>'+
                                                '<td class=""></td>'+
                                                '<td class=""></td>'+
                                                '<td class=""></td>'+
                                                '<td class=""></td>'+
                                                '<td style="text-align:right;font-weight: bold"><strong>Total:</td></strong>'+
                                                '<td style="text-align:right;font-weight: bold"><strong>'+total_con_iva.toFixed(3)+'</strong></td>'+
                                            '</tr>';
                                 $('#grilla').append(cabecera+body+total+cierre); 
                                 barcodear();
                                 //Tabla Resumen
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
                                 $('#imprimir').val('facturaa');
                             }
                         }
                     });
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
                         url: '<?php echo base_url()."ajax/liquidaciones/getLiquidacionClientesPendientes" ?>',
                         dataType: 'json',
                         cache: false,
                         contentType: false,
                         processData: false,
                         data: form_data,                
                         type: 'post',
                         success: function(data){
                             if (data.status){
                                 $('#grilla').html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>No se encontraron resultados.</div>');
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
                                                    '<input type="hidden" id="servicio_id_'+contador+'" name="servicio_id_'+contador+'" value="'+element['servicio_id']+'"/> '+
                                                    '<td class="">'+element['servicio_especial']+'</td>'+'</td><input type="hidden" id="servicio_'+contador+'" name="servicio_'+contador+'" value="'+element['servicio_especial']+'"/> '+
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
                                 $('#imprimir').val('pendientes');
                             }
                         }
                     });
                //}
                if ($('#cliente_id').val() === '' && ($('#periodo_desde').val() !== '' || $('#periodo_hasta').val() !== '') ){
                    $.ajax({
                         url: '<?php echo base_url()."ajax/liquidaciones/getPiezasPendientesLiquidar" ?>',
                         dataType: 'json',
                         cache: false,
                         contentType: false,
                         processData: false,
                         data: form_data,                
                         type: 'post',
                         success: function(data){
                             if (data.status){
                                 $('#errores').html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>No se encontraron resultados.</div>');
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
                                                    '<input type="hidden" id="servicio_id_'+contador+'" name="servicio_id_'+contador+'" value="'+element['servicio_id']+'"/> '+
                                                    '<td class="">'+element['servicio_especial']+'</td>'+'</td><input type="hidden" id="servicio_'+contador+'" name="servicio_'+contador+'" value="'+element['servicio_especial']+'"/> '+
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
                         url: '<?php echo base_url()."ajax/liquidaciones/getPiezasPendientesLiquidar" ?>',
                         dataType: 'json',
                         cache: false,
                         contentType: false,
                         processData: false,
                         data: form_data,                
                         type: 'post',
                         success: function(data){
                             if (data.status){
                                 $('#grilla').html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>No se encontraron resultados.</div>');
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
                                                    '<input type="hidden" id="servicio_id_'+contador+'" name="servicio_id_'+contador+'" value="'+element['servicio_id']+'"/> '+
                                                    '<td class="">'+element['servicio_especial']+'</td>'+'</td><input type="hidden" id="servicio_'+contador+'" name="servicio_'+contador+'" value="'+element['servicio_especial']+'"/> '+
                                                    '<td class="">'+element['cantidad']+'</td>'+'</td><input type="hidden" id="cantidad_'+contador+'" name="cantidad_'+contador+'" value="'+element['cantidad']+'"/> '+
                                                    '<td class="">'+precio.toFixed(3)+'</td>'+'</td><input type="hidden" id="precio_'+contador+'" name="precio_'+contador+'" value="'+precio.toFixed(3)+'"/> '+
                                                    '<td class="">'+importe.toFixed(3)+'</td>'+'</td><input type="hidden" id="importe_'+contador+'" name="importe_'+contador+'" value="'+importe.toFixed(3)+'"/> '+
                                                '</tr>';
                                    // }
 
                                     contador++;
                                     suma_total = parseFloat(suma_total)+importe;
                                 });
                                 var cierre = '</tbody></table>';
                                 var total = '<tr>'+
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
                                 $('#imprimir').val('liquidacionx');
                             }
                         }
                     });
          });
          
          $('.clientesPendientes').click(function(event) {
                $('.exportarAPdf').attr("disabled","disabled");
                $('#guardar').attr("disabled","disabled");
                $('#grilla').html('');
 
                var form_data = new FormData();                  
                var other_data = $('#form_liquidacion_add').serializeArray();
                $.each(other_data,function(key,input){
                    form_data.append(input.name,input.value);
                });
                $.ajax({
                         url: '<?php echo base_url()."ajax/liquidaciones/getLiquidacionesClientesPendientesXClientesXSucursal" ?>',
                         dataType: 'json',
                         cache: false,
                         contentType: false,
                         processData: false,
                         data: form_data,                
                         type: 'post',
                         success: function(data){
                             if (data.status){
                                 $('#grilla').html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>No se encontraron resultados.</div>');
                             }else{
                                  $('#grilla').html('');
                                 var contador = 0;
                                 var cabecera = '<table class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">'+
                                                    '<thead>'+
                                                        '<tr>'+
                                                            '<th>Cliente</th>'+
                                                            '<th>Departamento</th>'+
                                                        '</tr>'+
                                                    '</thead>';
                                var body = '<tbody id="body-grilla">';
                                var suma_total = 0;
                                $('#cantidad_elementos').val(data['grilla'].length);
                                $.each(data['grilla'], function(index, element) {
                                      body    += '<tr id="resultados" class="nueva"><input type="hidden" name="fila_'+contador+'" value="'+contador+'"/>'+
                                                    '<td>'+element['nombre']+'</td><input type="hidden" id="nombre_'+contador+'" name="nombre_'+contador+'" value="'+element['nombre']+'"/> '+
                                                    '<td>'+element['departamento']+'</td><input type="hidden" id="departamento_'+contador+'" name="departamento_'+contador+'" value="'+element['departamento']+'"/> '+
                                                '</tr>';
                                    // }
 
                                     contador++;
                                 });
                                 var cierre = '</tbody></table>';
                                 $('#grilla').append(cabecera+body+cierre); 
                                 //Tabla Resumen
                                 $('#grilla_resumen').html('');
                                 $('#imprimir').val('clientespendientes');
                             }
                         }
                     });
          });
    });
</script>