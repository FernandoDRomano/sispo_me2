<style>
input[type=checkbox] {
display:none;
}
input[type=checkbox] + label
{
background: #0080FF;
height: 16px;
width: 16px;
display:inline-block;
padding: 0 0 0 0px;
}
input[type=checkbox]:checked + label
{
background: #999;
height: 16px;
width: 16px;
display:inline-block;
padding: 0 0 0 0px;
}
</style>

<div class="col-xs-12">
    <div class="ibox-content">
        <form class="form_rendicion_add" id="form_rendicion_add" name="form_rendicion_add" action="<?php echo base_url('piezas/rendiciones/add')?>" method="post" enctype="multipart/form-data">
	<input type='hidden' id='enviar_form' name='enviar_form' value="1">
        <input type='hidden' id='cantidad_elementos' name='cantidad_elementos' value="0">
        <input id="piezas_ids" name="piezas_ids" type="hidden" value="" />
        <input type='hidden' id='registros_procesados' name='registros_procesados' value="0">
        <input type='hidden' id='limite_inferior' name='limite_inferior' value="0">
        <input type='hidden' id='limite_inferior_normales' name='limite_inferior_normales' value="0">
        <div class="row">
            <div class="col-xs-6">	
                <div class="form-group">
                    <label for="cliente_id">Cliente*</label>
                    <select required id="cliente_id" name="cliente_id" class="form-control chosen-select" >
                        <option value="0">Seleccione</option>
                        <?php foreach ($clientes as $f) { ?>
                            <option value="<?php echo $f->id ?>"><?php echo $f->nombre ?> (<?php echo $f->nombre_fantasia ?>)</option>
                        <?php } ?>
                    </select>
                </div>
            </div>
             <div class="col-xs-6">
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
            <div class="col-xs-6">
                <div class="form-group">
                    <label for="departamento_id">Departamento*</label>
                    <select required id="departamento_id" name="departamento_id" class="form-control" >

                    </select>
                </div>
            </div>
              <div class="col-xs-6">
                <div class="form-group">
                  <label for="sucursal-actual">Sucursal Actual</label>
                  <select id="sucursal_id_apostada" name="sucursal_id_apostada" class="form-control filtro-group">
                      <option value="-1">Todas</option>
                    <?php foreach ($sucursales_apostadas as $sucursal): ?>
                      <option value="<?=$sucursal->id ?>"<?=$sucursal_id == $sucursal->id ? 'selected="selected"' : ''?>><?=$sucursal->nombre ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
      
        </div>
        <div class="row">
            <div class="col-xs-3">
                <div class="form-group">
                  <label for="servicio">Servicio</label>
                  <select id="servicio_id" name="servicio_id" class="form-control filtro-group">
                    <option value="">Seleccione</option>
                    <?php foreach ($servicios as $servicio): ?>
                      <option value="<?=$servicio->id ?>"<?=$servicio_id == $servicio->id ? 'selected="selected"' : ''?>><?=$servicio->nombre ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
            </div>
            <div class="col-xs-4">
                <div class="form-group">
                    <label for="numero">Ingrese el N° de comprobante</label>
                    <input id="numero" name="numero" type="text" class="form-control" placeholder="Numero" autocomplete="off"/>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-3">
                <div class="form-group">
                    <label for="fecha_desde">Fecha Desde</label>
                    <input id="fecha_desde" name="fecha_desde" type="text" class="form-control datepicker" placeholder="Seleccione una fecha" value="<?php if ($fecha_desde){ $f = strtotime($fecha_desde);echo date('d-m-Y',$f );} ?>"  required autocomplete="off" />
                </div>
            </div>
            <div class="col-xs-3">
                <div class="form-group">
                    <label for="fecha_hasta">Fecha límite</label>
                    <input id="fecha_hasta" name="fecha_hasta" type="text" class="form-control datepicker" placeholder="Seleccione una fecha" value="<?php if ($fecha_hasta){ $f = strtotime($fecha_hasta);echo date('d-m-Y',$f );} ?>"  required autocomplete="off" />
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-6">
                <div class="control-group">
                  <div class="controls">
                    <a class="btn btn-primary aceptar" href="javascript:;" ><i class="fa fa-arrow-down"></i> Aceptar</a>
                    <!--<a class="btn btn-primary exportarAExcel" href="javascript:;" disabled><i class="fa fa-arrow-down"></i> Exportar a Excel</a>-->
                    <?php echo form_button(array('type'  =>'submit','value' =>'Guardar','name'  =>'submit','class' =>'btn btn-success'), "<i class='fa fa-floppy-o'></i> Guardar"); ?> 
                    <a class="btn btn-danger" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2); ?>"><i class="fa fa-arrow-circle-left"></i> Volver</a>
                  </div>
                </div>
                
            </div>
        </div>
        <div class="row">
            <div class="col-xs-6">
                <div class="control-group">
                  <div class="controls">
                        <div id="procesando" name="procesando"></div>
                    </div>
                </div>
            </div>
            <div id="div_buscar" class="col-xs-6">
                <div class="control-group">
                  <div class="controls">
                  <span class="input-group-addon" id="basic" style="float:right;border-style:none;margin-left:0px;"> <i style="float:right" class="glyphicon glyphicon-search"></i></span>
                  <span class="input-group-addon" id="basic-addon1" style="width:45%;float:right;border-style:none;margin-right:0px;">  
                        <input id="busqueda" name="busqueda" type="text" value="" class="form-control" autocomplete="off" style="width:200px;"/>
                  </span>
                  
                    </div>
                </div>
            </div>
        </div>
        <div id="grilla"  style="overflow-x: auto;height: 500px"></div>
    </form>
    </div>
</div>
<form method='post' action='<?php echo base_url('piezas/rendiciones/exportarAExcel')?>' id='exportarAExcel'>
        <input type='hidden' id='htmlGrilla' name='htmlGrilla' value="">
        <input type='hidden' id='excel_cantidad_elementos' name='excel_cantidad_elementos' value="0">
   </form>    
<script type="text/javascript">
$(document).ready(function() {
    
    $('#procesando').hide();
    $('#div_buscar').hide();
    
    $("#busqueda").keypress(function(e) {
        if(e.which == 13) {
            e.preventDefault();
            $('#procesando').html("Procesados...0 registros");
            //$("#busqueda").attr('readonly', true);
            $('#procesando').show();
            $("#limite_inferior").val(0);
            $('#grilla').html('');
            $("#cantidad_elementos").val(0);
            procesarRendicionesNormales($("#busqueda").val());
        }
    });

    $('.aceptar').click(function(event) {
        //alert($('.form_liquidacion_add').serialize());
        $('#procesando').html("Procesados...0 registros");
        $('#procesando').show();
        $("#limite_inferior").val(0);
        $('#grilla').html('');
        $("#cantidad_elementos").val(0);
        procesarRendicionesNormales();
    });
    
    
    $('#cliente_id').change(function(event) {
        var id = $(this).val();
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
            //Traigo los servicios de ese cliente
            var url = '<?php echo base_url() ?>' + 'ajax/clientes/serviciosXCliente/' + $('#cliente_id').val();
            $.getJSON(url, function(data) {
                $("#servicio_id").find("option").remove();                
                var options = '';

                if(!data.status){
                    options = options + "<option value='0'>Ninguno</option>";
                    $.each(data, function(key, val) {
                        options = options + "<option value='"+val.id+"'>"+ val.nombre +"</option>";
                    });              
                }else{
                    options = options + "<option value='0' disabled>Sin resultados</option>";
                }

                $("#servicio_id").append(options);
                $("#servicio_id").trigger("liszt:updated");
                
            });
        }else{
            $("#departamento_id").find("option").remove();
            $("#servicio_id").find("option").remove();
        }

        $('#servicio_id').removeClass('loading-ajax');
    });
    
    $('.exportarAExcel').click(function(event) {
            $("#htmlGrilla").val(jQuery("#grilla").html());
//            $("#htmlResumenLiquidacionSinIva").val(jQuery("#grilla_resumen").html());
//            $("#pdf_liquidacion_nro").val(jQuery("#nro").val());
//            $("#pdf_cliente_id").val(jQuery("#cliente_id").val());
//            $("#pdf_factura_nro").val(jQuery("#factura").val());
//            $("#pdf_periodo_desde").val(jQuery("#periodo_desde").val());
//            $("#pdf_periodo_hasta").val(jQuery("#periodo_hasta").val());
//            $("#pdf_sucursal").val($( "#sucursal_id option:selected" ).text());
            $("#excel_cantidad_elementos").val($( "#cantidad_elementos" ).val());
//            $("#enviar_form").val('2');
            //alert(jQuery("#htmlLiquidacionSinIva").val());
            $('#exportarAExcel').submit();
       });
});

   function procesarRendicionesNormales(busqueda=null){

            var other_data = $('#form_rendicion_add').serializeArray();
            var form_data = new FormData();
            $.each(other_data,function(key,input){
                form_data.append(input.name,input.value);
            });
            if (busqueda!=null) {
                form_data.append('busqueda',busqueda);
            }
             $.ajax({
                         url: '<?php echo base_url()."ajax/rendiciones/getPiezasPendientesRendirNormales" ?>',
                         dataType: 'json',
                         cache: false,
                         contentType: false,
                         processData: false,
                         data: form_data,                
                         type: 'post',
                         success: function(data){//alert(JSON.stringify(data['grilla1']));
                             if (data.status === "end"){
                               // procesarRendicionesSimples();
                                 setTimeout(procesarRendicionesSimples(), 300);
                                //$("#procesando").html("Procesados..."+parseInt($("#registros_procesaros").val())+" registros");
                                //$('.exportarAExcel').attr('disabled','disabled'); 
                                //$('#grilla').html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>No se encontraron resultados.</div>');
                             }else{
                                 var contador = 0;
                                 var cabecera = '<table width="1000px" style="table-layout: fixed;" class="table-striped table-hover table-condensed bootstrap-datatable table-bordered">'+
                                                    '<thead>'+
                                                        '<tr>'+
                                                            '<th style="width:30px">Inc.</th>'+
                                                            '<th style="width:100px">Cant.</th>'+
                                                            '<th style="width:100px">Comp. ing.</th>'+
                                                            '<th style="width:100px">Fecha</th>'+
                                                            '<th style="width:250px">Servicio</th>'+
                                                            '<th style="width:70px">Pieza</th>'+
                                                            '<th style="width:150px">Externa</th>'+
                                                            '<th style="width:100px">Estado</th>'+
                                                            '<th style="width:100px">F.Estado</th>'+
                                                            '<th style="width:250px">Destinatario</th>'+
                                                            '<th style="width:250px">Domicilio</th>'+
                                                            '<th style="width:70px">C.P.</th>'+
                                                            '<th style="width:250px">Recibio</th>'+
                                                            '<th style="width:100px">Documento</th>'+
                                                            '<th style="width:70px">Vínvulo</th>'+
                                                            '<th style="width:250px">Departamento</th>'+
                                                            '<th style="width:250px">Usuario</th>'+
                                                        '</tr>'+
                                                    '</thead>';
                                var body = '<tbody id="body-grilla">';
                                $.each(data['grilla'], function(index, element) {
//                                     if($('#piezas_ids').val() == ''){
//                                         $('#piezas_ids').val(element['pieza_id']);
//                                     }else{
//                                         $('#piezas_ids').val($('#piezas_ids').val()+','+element['pieza_id']);
//                                     }
                                      body    += //'<input type="hidden" id="'+element['pieza_id']+'" name="pieza_id_'+contador+'" value="'+element['pieza_id']+'"/>'+
                                                    '<tr style="min-height:50px">'+
                                                    '<td><input type="checkbox" value="valuable" id="excluir_'+element['pieza_id']+'" name="excluir_'+element['pieza_id']+'" /><label for="excluir_'+element['pieza_id']+'"></label></td>'+
                                                    '<td>'+element['cantidad']+'</td>'+
                                                    '<td>'+element['numero']+'</td>'+
                                                    '<td>'+element['fecha']+'</td>'+
                                                    '<td>'+element['servicio']+'</td>'+
                                                    '<td>'+element['pieza_id']+'</td>'+
                                                    '<td>'+element['barcode_externo']+'</td>'+
                                                    '<td>'+element['estado']+'</td>'+'</td>'+
                                                    '<td>'+element['fecha_estado']+'</td>'+
                                                    '<td>'+element['destinatario']+'</td>'+
                                                    '<td>'+element['domicilio']+'</td>'+
                                                    '<td>'+element['codigo_postal']+'</td>'+
                                                    '<td>'+element['recibio']+'</td>'+
                                                    '<td>'+element['documento']+'</td>'+
                                                    '<td>'+element['vinculo']+'</td>'+
                                                    '<td>'+element['departamento']+'</td>'+
                                                    '<td>'+element['user_apellido']+' '+element['user_nombre'] +'</td>'+
                                                '</tr>';
                                    // }
 
//                                     contador++;
//                                     suma_total = parseFloat(suma_total)+parseFloat(element['importe']);
                                 });
                                 var cierre = '</tbody></table>';
                                 
                                 $('#grilla').append(cabecera+body+cierre);
                                 $('.exportarAExcel').removeAttr('disabled');
                                 var procesados = (parseInt($("#registros_procesados").val())+parseInt(data.grilla.length));
                                 $("#cantidad_elementos").val(parseInt($( "#cantidad_elementos" ).val())+parseInt(data.grilla.length)); //Para la tabla rendiciones
                                 $("#registros_procesados").val(procesados);
                                 $("#procesando").html("Procesando..."+procesados+" registros");
                                 $("#limite_inferior").val(procesados);
                                 //procesarRendicionesNormales();
                                 setTimeout(procesarRendicionesNormales(), 300);
                             }
                         }
                     });
          }; 
//          activar el filtro de las rendiciones de fecha limite
//          ver que pasaba que este exporta a excel con el metodo viejo
          
          
          function procesarRendicionesSimples(){
            var other_data = $('#form_rendicion_add').serializeArray();
            var form_data = new FormData();
            $.each(other_data,function(key,input){
                form_data.append(input.name,input.value);
            });
             $.ajax({
                         url: '<?php echo base_url()."ajax/rendiciones/getPiezasPendientesRendirSimples" ?>',
                         dataType: 'json',
                         cache: false,
                         contentType: false,
                         processData: false,
                         data: form_data,                
                         type: 'post',
                         success: function(data){//alert("SIMPLES "+JSON.stringify(data['grilla2']));
                             if (data.status === "end"){
                                $('#div_buscar').show();
                                 //alert("El proceso se completó con ");
                                //$("#procesando").html("Procesados..."+parseInt($("#registros_procesaros").val())+" registros");
                                //$('.exportarAExcel').attr('disabled','disabled'); 
                                $('#procesando').html('<div class="alert alert-success><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Proceso Completo. Registros procesados '+ $("#cantidad_elementos").val()+'</div>');
                             }else{
                                 var contador = 100;
                                 var cabecera = '<table "resultados_'+contador+'" width="1000px" style="table-layout: fixed;" class="table-striped table-hover table-condensed bootstrap-datatable table-bordered">'+
                                                    '<thead>'+
                                                        '<tr>'+
                                                            '<th style="width:30px">Inc.</th>'+
                                                            '<th style="width:100px">Cant.</th>'+
                                                            '<th style="width:100px">Comp. ing.</th>'+
                                                            '<th style="width:100px">Fecha</th>'+
                                                            '<th style="width:250px">Servicio</th>'+
                                                            '<th style="width:70px">Pieza</th>'+
                                                            '<th style="width:250px">Externa</th>'+
                                                            '<th style="width:100px">Estado</th>'+
                                                            '<th style="width:100px">F.Estado</th>'+
                                                            '<th style="width:250px">Destinatario</th>'+
                                                            '<th style="width:250px">Domicilio</th>'+
                                                            '<th style="width:70px">C.P.</th>'+
                                                            '<th style="width:250px">Recibio</th>'+
                                                            '<th style="width:100px">Documento</th>'+
                                                            '<th style="width:70px">Vínvulo</th>'+
                                                            '<th style="width:250px">Departamento</th>'+
                                                            '<th style="width:250px">Usuario</th>'+
                                                        '</tr>'+
                                                    '</thead>';
                                var body = '<tbody id="body-grilla">';
                                $.each(data['grilla'], function(index, element) {
                                    
//                                     if($('#piezas_ids').val() == ''){
//                                         $('#piezas_ids').val(element['pieza_id']);
//                                     }else{
//                                         $('#piezas_ids').val($('#piezas_ids').val()+','+element['pieza_id']);
//                                     }
                                      if (element['destinatario'] === '') element['pieza_id'] = "---";
                                      body    += //'<input type="hidden" id="'+element['pieza_id']+'" name="pieza_id_'+contador+'" value="'+element['pieza_id']+'"/>'+
                                                    '<tr  class="nueva">'+
                                                    '<td><input type="checkbox" value="valuable" id="excluir_'+element['pieza_id']+'" name="excluir_'+element['pieza_id']+'" /><label for="thing"></label></td>'+
                                                    //'<td><input type="checkbox" checked id="'+element['pieza_id']+'" name="pieza_id_'+contador+'" value="'+element['pieza_id']+'"/></td>'+
                                                    '<td>'+element['cantidad']+'</td>'+
                                                    '<td>'+element['numero']+'</td>'+
                                                    '<td>'+element['fecha']+'</td>'+
                                                    '<td>'+element['servicio']+'</td>'+
                                                    '<td>'+element['pieza_id']+'</td>'+
                                                    '<td>'+element['barcode_externo']+'</td>'+
                                                    '<td>'+element['estado']+'</td>'+'</td>'+
                                                    '<td>'+element['fecha_estado']+'</td>'+
                                                    '<td>'+element['destinatario']+'</td>'+
                                                    '<td>'+element['domicilio']+'</td>'+
                                                    '<td>'+element['codigo_postal']+'</td>'+
                                                    '<td>'+element['recibio']+'</td>'+
                                                    '<td>'+element['documento']+'</td>'+
                                                    '<td>'+element['vinculo']+'</td>'+
                                                    '<td>'+element['departamento']+'</td>'+
                                                    '<td>'+element['usuario_novedad_apellido']+' '+element['usuario_novedad_nombre'] +'</td>'+
                                                '</tr>';
                                    // }
 
                                     contador++;
//                                     suma_total = parseFloat(suma_total)+parseFloat(element['importe']);
                                 });
                                 var cierre = '</tbody></table>';
                                 
                                 $('#grilla').append(cabecera+body+cierre);
                                 $('.exportarAExcel').removeAttr('disabled');
                                 var procesados = (parseInt($("#registros_procesados").val())+parseInt(data.grilla.length));
                                 $("#cantidad_elementos").val(parseInt($( "#cantidad_elementos" ).val())+parseInt(data.grilla.length));//Para la tabla Rendiciones
                                 $("#registros_procesados").val(procesados);
                                 $("#procesando").html("Procesando..."+procesados+" registros");
                                 $("#limite_inferior").val((parseInt($("#limite_inferior").val())+parseInt(data.grilla.length)));
                                 
                                 setTimeout(procesarRendicionesSimples(), 300);
                             }
                         }
                     });
          }; 
          
</script>
