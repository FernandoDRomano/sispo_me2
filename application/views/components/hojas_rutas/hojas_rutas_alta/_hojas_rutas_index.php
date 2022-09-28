<div class="col-xs-12">
  <div class="ibox-content">
    <div class="panel blank-panel">
      <div class="panel-heading">
        <div id="tabs_hdr" class="panel-options">
          <ul class="nav nav-tabs">
            <li><a href="<?=base_url('hojas_rutas/hojas_rutas')?>">Hojas de Ruta</a></li>
            <?php if($this->is_admin()): ?>
              <li><a href="<?=base_url('hojas_rutas/hojas_rutas/archivadas')?>">Hojas de Ruta Archivadas</a></li>
            <?php endif; ?>
            <li class="active"><a data-toggle="tab" href="#tab-1">Nueva Hoja de Ruta</a></li>
            <li><a href="<?=base_url('hojas_rutas/hojas_rutas_alta/hojas_rutas_alta_masiva_add')?>">Hojas de Ruta (Alta masiva)</a></li>
          </ul>
        </div>
      </div>
      <div class="panel-body">
        <div class="tab-content">
          <div id="tab-1" class="tab-pane active">

            <?php echo form_open(current_url(), array('id'=>"form-hdr")); ?>
              <input type="hidden" name="estado"/>
              <div id="errores"></div>
              <div>
                <div id="tabs_hdr" class="tabs-container grey">
                  <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#tab-1-1" aria-expanded="false">Cartero</a></li>
                    <li><a data-toggle="tab" href="#tab-1-2" aria-expanded="true">Distribuidor</a></li>
                  </ul>
                  <div class="tab-content">
                    <div id="tab-1-1" class="tab-pane active">
                      <div class="panel-body">
                        <div class="row">
                          <div class="col-xs-6">
                            <div class="form-group">
                              <label for="cartero">Cartero</label>
                              <input id="cartero" type="text" class="form-control filtro-group" placeholder="Cartero" autocomplete="off"/>
                              <input id="cartero_id" name="cartero_id" type="hidden"/>
                            </div>
                          </div>
                          <div class="col-xs-6">
                            <div class="form-group">
                              <label for="zona">Zona</label>
                              <input id="zona" type="text" class="form-control" placeholder="Zona" autocomplete="off" required/>
                              <input id="zona_id" name="zona_id" type="hidden"/>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div id="tab-1-2" class="tab-pane">
                      <div class="panel-body">
                        <div class="row">
                          <div class="col-xs-6">
                            <div class="form-group">
                              <label for="distribuidor_id">Distribuidor</label>
                              <select id="distribuidor_id" name="distribuidor_id" class="form-control filtro-group" >
                                <option value="" selected>Seleccione</option>
                                <?php foreach ($distribuidores as $f) { ?>
                                  <option value="<?php echo $f->id ?>"><?php echo $f->apellido_nombre ?></option>
                                <?php } ?>
                              </select>
                            </div>
                          </div>
                          <div class="col-xs-6">
                            <div class="form-group">
                              <label for="transporte_id">Transporte</label>
                              <select id="transporte_id" name="transporte_id" class="form-control" required >
                                <option value="" selected>Seleccione</option>
                                <?php foreach ($transportes as $f) { ?>
                                  <option value="<?php echo $f->id ?>"><?php echo $f->nombre ?></option>
                                <?php } ?>
                              </select>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-xs-12">
                    <div class="form-group">
                      <label for="fecha_entrega">Fecha entrega</label>
                      <input  id="fecha_entrega" name="fecha_entrega" type="text" class="form-control datepicker" placeholder="Fecha entrega" value="<?php echo date('d-m-Y') ?>" required autocomplete="off" />
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-xs-12">
                    <div class="form-group">
                      <label for="observaciones">Observaciones</label>
                      <textarea id="observaciones" name="observaciones" class="form-control" placeholder="Observaciones" rows="3"></textarea>
                    </div>
                  </div>
                </div>
                <hr>
                <div class="tabs-container grey">
                  <ul id="tabs_piezas" class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#tab-2-1" aria-expanded="false">Agregar Piezas Normales</a></li>
                    <li><a data-toggle="tab" href="#tab-2-2" aria-expanded="true">Agregar Piezas Simples</a></li>
                  </ul>
                  <input id="hdr_id" name="hdr_id" type="hidden" value="0" />
                  <div class="tab-content">
                    <div id="tab-2-1" class="tab-pane active">
                      <div class="panel-body">
                        <div class="row">
                          <div class="col-xs-6">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xs-12">
                                      <div class="form-group">
                                        <label class="radio-inline"><input type="radio" name="buscar_por" value="1">Pieza ID</label>
                                        <label class="radio-inline"><input type="radio" name="buscar_por" value="2" checked="checked">Código Externo</label>
                                        <br/>
                                        <input id="piezas_ids" name="piezas_ids" type="hidden" value="" />
                                        <input id="codigo_barra" name="codigo_barra" type="text" class="form-control" placeholder="Codigo barra" autocomplete="off" />
                                        <input id="cantidad_servicios" name="cantidad_servicios" type="hidden" value="0" />
                                        <input id="contador" name="contador" type="hidden" value="0" />
                                        <input id="contador_aux" name="contador_aux" type="hidden" value="0" />
                                        <input id="codigo_barra_id" name="codigo_barra_id" type="hidden" value="0" />
                                      </div>
                                      <div class="alert alert-warning" id="visitado" style="display:none">
                                        <strong>Advertencia</strong>
                                        El domicilio de la pieza que intenta agregar ya fue visitado <b><span id="visitado_numero"></span></b> veces
                                      </div>
                                    </div>
                                </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div id="tab-2-2" class="tab-pane">
                      <div class="panel-body">
                        <div class="row">
                          <div class="col-xs-3">
                            <div class="form-group">
                              <div class="row">
                                <div class="col-xs-12">
                                  <div class="form-group">
                                    <input id="codigo_barra_simple" name="codigo_barra_simple" type="text" class="form-control" placeholder="Codigo barra del comprobante" autocomplete="off" />
                                  </div>
                                  <div class="alert alert-warning" id="visitado" style="display:none">
                                    <strong>Advertencia</strong>
                                    El domicilio de la pieza que intenta agregar ya fue visitado <b><span id="visitado_numero"></span></b> veces
                                  </div>
                                </div>
                                </div>
                            </div>
                          </div>
                        <div class="col-xs-6">
                             <div class="form-group">
                               <select id="servicio_id" name="servicio_id" class="form-control" disabled="disabled" placeHolder="Servicios...">
                                 <option value="">Seleccione</option>
                               </select>
                             </div>
                        </div>                            
                          <div class="col-xs-3">
                            <div class="form-group">
                              <div class="form-group" id="cantidad_piezas" name="cantidad_piezas" style="" disabled="disabled">
                                <input type="number" min="0" max="0" step="1" class="form-control"  id="cantidad" name="cantidad" placeHolder="Cantidad de piezas"/> &nbsp;
                                <input  id="cargados" type="hidden" value="" />
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>                
                <hr>
                <div class="control-group text-right">
                 <div class="controls">
                   <span id="loadding" ><i style="font-size:24px" class="fa fa-spinner fa-spin"></i></span>
                   <a class="btn btn-primary agregar-grilla"><i class="fa fa-arrow-down"></i> Agregar</a>
                   <!--<a class="btn btn-success btn-submit" data-action="create" data-estado="<?=Hoja::ESTADO_INICIADA?>"><i class='fa fa-floppy-o'></i> Guardar</a>-->
                   <a class="btn btn-success btn-submit" data-action="create" data-estado="<?=Hoja::ESTADO_CERRADA?>"><i class='fa fa-share'></i> Cerrar</a>
                 </div>
               </div>
              </div>
              <table class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
                <thead>
                  <tr>
                    <th>H.D.R</th>
                    <th>Pieza</th>
                    <th>Servicio</th>
                    <th>Descripcion</th>
                    <th>Tipo</th>
                    <th class="col-xs-1">&nbsp;</th>
                  </tr>
                </thead>
                <tbody id="body-grilla">

                </tbody>
              </table>
            <?php echo form_close(); ?>
            <div id="dialog-message" title="Estados Condicionales"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="/resources/demos/style.css">
<script type="text/javascript">
    var PIEZA_TIPO_SIMPLE = 1;
    var PIEZA_TIPO_NORMAL = 2

    $(document).ready(function() {
      <?php include "script.js"; ?>

      $('#loadding').hide();
    $("#codigo_barra").keyup(function (e) {
        var codigo_barra = '"'+$("#codigo_barra").val()+'"';
        var pieza_id = 0;
        var buscar_por = $('input:radio[name=buscar_por]:checked').val();
        if (e.which == 13) {
            if (buscar_por === 2){ //Solo busca piezas iguales cuando se coloca un CODIGO EXTERNO de lo contrario solo debe encontrar 1 pieza
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url()."ajax/hojas_rutas/traerPiezasIguales" ?>',
                    data: 'codigo_barra='+$('#codigo_barra').val(),
                    cache: false,
                    datatype:'json',
                    beforeSend: function() {
                        //$('#errores').html('<h3><i class="fa fa-spinner fa-spin"></i></h3>');
                        //$('.btn').addClass('disabled');
                    },
                    success: function(respuesta){
                                var array_codigos = [];
                                var codigos_iguales = $.parseJSON(respuesta);
                                if(!codigos_iguales.status){
                                     $.each(codigos_iguales.grilla,function(key,value){
                                        array_codigos.push('<strong>C.I:</strong> '+value.numero+' <strong>Código:</strong> '+value.barcode_externo+' <strong>ID:</strong> '+value.pieza_id+'<br/>');
                                        //pieza_id = value.pieza_id;
                                    })
                                }else{
                                    var message = $("<label id='pieza-normal-message' style='color:red;margin-left:10px;font-weight:normal;line-height:.8em;font-size:10px'>"+codigos_iguales.status+"</label>");
                                    $('input#codigo_barra').after(message);
                                    $( "#codigo_barra" ).focus();
                                    $( "#codigo_barra" ).val('');
                                    $( "#pieza-normal-message" ).show();
                                    $( "#pieza-normal-message" ).delay(1500).fadeOut('slow');
                                    $('#codigo_barra_id').val(0);
                                    $('#codigo_barra').val('');
                                    $('#codigo_barra').focus();
                                }

                                if (array_codigos.length == 1){
                                    pieza_id = codigos_iguales.grilla[0].pieza_id;
                                }
                                if (array_codigos.length > 1){
                                     bootbox.alert({ 
                                        size: "small",
                                        title: "Existen Codigos Iguales",
                                        message: array_codigos, 
                                        callback: function(){ setTimeout("$('#codigo_barra').focus();$('#codigo_barra').val('');", 0); }
                                      })
                                      array_codigos.length = 0;
                                }else{
                                    if (pieza_id > 0){
                                        $('#codigo_barra').val(pieza_id);
                                        $('.agregar-grilla').click();
                                        array_codigos.length = 0;
                                    }
                                }
                            }
                        })
                    }else{
                        //var pieza_id = 0;
                        //$('#codigo_barra').val(pieza_id);
                        $('.agregar-grilla').click();
                    }
                }
            });
    
$('#codigo_barra_simple').blur(function() {
    if ($('#codigo_barra_simple').val() !== 0){
        $.getJSON('<?php echo base_url()."ajax/servicios/getServiciosPorComprobante" ?>/'+$("#codigo_barra_simple").val(),function(data){
                $("#servicio_id").empty();
                if (data.status){
                    var message = $("<label id='pieza-simple-message' style='color:red;margin-left:10px;font-weight:normal;line-height:.8em;font-size:10px'>El comrobante no tiene un servicio asociado</label>");
                    $('input#codigo_barra_simple').after(message);
                    $( "#codigo_barra_simple" ).focus();
                    $( "#codigo_barra_simple" ).val('');
                    $( "#pieza-simple-message" ).show();
                    $( "#pieza-simple-message" ).delay(1000).fadeOut('slow');
                }else{
                    $("#servicio_id").append('<option value="-1">Seleccionar...</option>');
                     $.each(data, function(id,value){
                        $("#servicio_id").removeAttr('disabled');
                        $("#servicio_id").append('<option value="'+value.comprobante_servicio_id+'">'+value.nombre+'</option>');
                        $("#servicio_id").show();
                    });
                }
            
        });
    }
});

$('#servicio_id').change(function(event) {
    var codigo = $('#codigo_barra_simple').val();
    var servicio_id = $('#servicio_id').val();
    if (codigo > 0 && servicio_id > 0) {
        $('#codigo_barra_simple').val(codigo);
        $('#codigo_barra_simple_id').val(codigo);
        //Traigo la cantidad de items que tiene este cod de barras si es simple o normal
        $.ajax({
            type: "POST",
            url: '<?php echo base_url()."ajax/hojas_rutas/traerPiezasSimplesPorComprobanteEstadosYServicio" ?>',
            data: 'codigo_barra='+$('#codigo_barra_simple').val()+'&servicio_id='+$('#servicio_id').val(),
             cache: false,
            datatype:'json',
            beforeSend: function() {
                //$('#errores').html('<h3><i class="fa fa-spinner fa-spin"></i></h3>');
                $('.btn').addClass('disabled');
            },
            success: function(respuesta){
               $('.btn').removeClass('disabled');
               var p = $.parseJSON(respuesta);
               comprobantes_piezas_simples = p;
               cantidad_disponible = comprobantes_piezas_simples.length;
               //if (typeof(cantidad_disponible) !== 'undefined'){
                $('#contador_aux_simple').val(cantidad_disponible);
                $('#cantidad_servicios').val(cantidad_disponible);
                $('#comprobantes_piezas_simples').val(comprobantes_piezas_simples);
                 if (p.status !== 'none'){
                     $("#cantidad").removeAttr('disabled');
                     $('#cantidad_servicios_simples').val(p.length);
                     $('#cantidad').attr('max',parseInt(p.length));
                     $('#cantidad').attr('min',0);
                     $('#cantidad').val(p.length);
                 }else{
               // }else{
                     $("#cantidad").attr('disabled',true);
                     $('#cantidad_servicios_simples').val(0);
                     $('#cantidad').attr('max',parseInt(0));
                     $('#cantidad').attr('min',0);
                     $('#cantidad').val(0);
               }
                //window.location.reload();
            }
        });
    }
});

$('#cartero').typeahead({
        source: <?=json_encode($carteros) ?>,
        displayField: 'apellido_nombre',
        onSelect: function(item) {
            $('#transporte_id').val("");
            $('#distribuidor_id').val("");
            $('#cartero_id').val('');
            $('#cartero').val('');
            if (item.value) {
                $('#cartero_id').val(item.value);
            }
        }
    });

    $('#zona').typeahead({
        source: <?=json_encode($zonas) ?>,
        displayField: 'nombre',
        onSelect: function(item) {
            $('#zona_id').val('');
            $('#zona').val('');
            if (item.value) {
                $('#zona_id').val(item.value);
            }
        }
    });


});

$('.agregar-grilla').click(function(event){
        var valido = false;
        if($('#cartero_id').val() > 0 && $('#zona_id').val() > 0){
            valido = true;
        }
        if($('#distribuidor_id').val() > 0 && $('#transporte_id').val() > 0){
            valido = true;
        }
        
        if (!valido){
            $('#errores').html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Error</strong> Debe seleccionar un CARTERO O DISTRIBUIDOR.</div>');
            return;
        }
        event.preventDefault();
        var tab_selected = $('#tabs_piezas .active a').attr('href');
        if (tab_selected == '#tab-2-2'){
            if ($('#codigo_barra_simple').val() != '' && $('#servicio_id').val() > 0 &&  $('#cantidad').val() != '' ){
                agregar_grilla_piezas_simples();
            }else{
                $('#errores').html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Error</strong> Debe seleccionar un servicio.</div>');
            }
        }else{
            agregar_grilla();
        }
    });

var agregar_grilla = function(){
    var form_data = new FormData();                  
    var other_data = $('#form-hdr').serializeArray();
    $.each(other_data,function(key,input){
        form_data.append(input.name,input.value);
    });
    $.ajax({
        type: "POST",
        url: '<?php echo base_url()."ajax/hojas_rutas/getPiezaNormal" ?>',
        data: form_data,//'codigo_barra='+$('#codigo_barra').val()+'&piezas_ids='+$('#piezas_ids').val(),
        cache: false,
        processData: false,
        contentType: false,                
        datatype:'json',
        beforeSend: function() {
            //$('#errores').html('<h3><i class="fa fa-spinner fa-spin"></i></h3>');
            //$('.btn').addClass('disabled');
        },
        success: function(respuesta){
                var pieza = $.parseJSON(respuesta);

                if (pieza.respuesta) {//alert(JSON.stringify(pieza.respuesta));
                    mensaje_array = pieza.respuesta.split(";");

                    mensaje_detalle = "";
                    for(i=0 ; i < mensaje_array.length ; i++){
                        mensaje_detalle += mensaje_array[i]+"</br>";
                    }

                    mensaje = '<p>'+
                              '  '+
                              mensaje_detalle+
                              '</p>'
                    $("#dialog-message").append(mensaje);

                    $( "#dialog-message" ).dialog({
                        minWidth: 400,
                        modal: true,
                        buttons: {
                            ACEPTAR: function() {
                                $("#dialog-message").html("");
                                $( this ).dialog( "close" );
                            }
                        }
                        });
                    return;
                }

                if (!pieza.status){
                    $('#hdr_id').val(pieza[1]);
                    var inputs = '<input type="hidden" name="piezas[]" value="'+pieza[0].pieza_id+'">';
                    var html = '<tr>'+
                                '<td class="">'+pieza[1]+'</td>'+
                                '<td class="barcodear">'+pieza[0].pieza_id+'</td>'+
                                '<td>'+pieza[0].servicio+'</td>'+
                                '<td><strong>'+pieza[0].destinatario+'</strong><br/>'+pieza[0].domicilio+" "+pieza[0].codigo_postal+" "+pieza[0].localidad+'</td>'+
                                '<td>Cantidad: 1</td>'+
                                '<td>Normal</td>'+
                                '<td><a href="javascript:;" onclick="eliminarFila(this,'+pieza[0].pieza_id+')" class="btn btn-danger"><i class="fa fa-trash-o"></i></a></td>'+
                                '</tr>';
                    if ($('#piezas_ids').val() == ''){
                        $('#piezas_ids').val(pieza[0].pieza_id);
                    }else{
                        $('#piezas_ids').val($('#piezas_ids').val()+','+pieza[0].pieza_id);
                    }

                    $('#body-grilla').append(html);
                    $('#body-grilla').append(inputs);
                    $('#codigo_barra').val('');
                    barcodear();
                }else{
                    var message = $("<label id='pieza-normal-message' style='color:red;margin-left:10px;font-weight:normal;line-height:.8em;font-size:10px'>"+pieza.status+"</label>");
                    $('input#codigo_barra').after(message);
                    $( "#codigo_barra" ).focus();
                    $( "#codigo_barra" ).val('');
                    $( "#pieza-normal-message" ).show();
                    $( "#pieza-normal-message" ).delay(1500).fadeOut('slow');
                    $('#codigo_barra_id').val(0);
                    $('#codigo_barra').val('');
                    $('#codigo_barra').focus();
                }
        }
    });
}

var eliminarFila = function (btn,pieza_id){

    var form_data = new FormData();                  
    form_data.append('pieza_id',pieza_id);
    $.ajax({
                type: "POST",
                url: '<?php echo base_url()."ajax/hojas_rutas/delete_pieza_normal" ?>',
                data: form_data,
                cache: false,
                processData: false,
                contentType: false,
                datatype:'json',
                beforeSend: function() {
                    $('#loadding').show();
                    $('.btn').addClass('disabled');
                },
                success: function(respuesta){
                        var hdr = $.parseJSON(respuesta);
                        if (hdr.respuesta > 0){
                            $(btn).parent().parent().remove();
                             var piezas_ids_array = $("#piezas_ids").val().split(',');
                            $("#piezas_ids").val('');
                            $.each(piezas_ids_array, function(j,value){
                                if (value != pieza_id){
                                    if ($("#piezas_ids").val() == '') {
                                        $("#piezas_ids").val(value);
                                    }else{
                                        $("#piezas_ids").val(','+value);
                                    }
                                }
                           });
                           piezas_ids_array.empty;
                            $('.btn').removeClass('disabled');
                            $('#loadding').hide();
                            $('#errores').html('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Exito</strong> La pieza '+pieza_id+' fue eliminada.</div>');
                        }else{
                            $('#errores').html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Error</strong> Hubo problemas en la eliminación.</div>');
                        }
                    }
        });
};

var agregar_grilla_piezas_simples = function(){
    var cantidad = $('#cantidad').val();
    if (cantidad == 0){ $('#cantidad').focus();}
    
    if (cantidad > 0) {
        var form_data = new FormData();                  
        var other_data = $('#form-hdr').serializeArray();
        $.each(other_data,function(key,input){
            form_data.append(input.name,input.value);
        });
        $.ajax({
                type: "POST",
                url: '<?php echo base_url()."ajax/hojas_rutas/add_piezas_simples" ?>',
                data: form_data,
                cache: false,
                processData: false,
                contentType: false,
                datatype:'json',
                beforeSend: function() {
                    $('#loadding').show();
                    $('.btn').addClass('disabled');
                },
                success: function(respuesta){
                        var hdr = $.parseJSON(respuesta);
                        if (!hdr.status){
                            $('#hdr_id').val(hdr[0].hdr_id);
                            //alert($('#hdr_id').val());
                            var html = '<tr>'+
                                            '<td class="barcodear">HDR: '+hdr[0].hdr_id+'</td>'+
                                            '<td class=""> --- </td>'+
                                            '<td>'+hdr[0].cliente+'</td>'+
                                            '<td>'+hdr[0].servicio+'</td>'+
                                            '<td>'+hdr[0].piezas+' piezas</td>'+
                                            '<td>'+hdr[0].tipo_pieza+'</td>'+
                                            '<td><a href="javascript:;" onclick="eliminarFilaSimple(this,'+hdr[0].hdr_id+','+hdr[0].servicio_id+','+hdr[0].piezas+')" class="btn btn-danger"><i class="fa fa-trash-o"></i></a></td>'+
                                        '</tr>';
                               // alert(html);
                            $('#body-grilla').prepend(html);
                        }else{
                            $('#cantidad').val('0');
                            $('#cantidad').attr('disabled',true);
                        }
                        $('#codigo_barra_simple').val('');
                        $('#servicio_id').val('-1');
                        $('#servicio_id').attr('disabled',true);
                        $('#cantidad').val('');
                        $('#cantidad').attr('disabled',true);
                        $('#loadding').hide();
                        $('.btn').removeClass('disabled');
                    }
        });
   }     
}

$("#form-comprobante").validate({
        rules: {
            cartero_id: {require_from_group: [1, ".filtro-group"]},
            distribuidor_id: {require_from_group: [1, ".filtro-group"]},
            zona_id: {required: true},
            fecha_entrega: {required: true},
        },
        messages: {
            cartero_id: "Campo requerido.",
            distribuidor_id: "Campo requerido.",
            zona_id: "Campo requerido.",
            fecha_entrega: "Campo requerido.",
        },
        highlight: function(element) {
            $(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function(element) {
            $(element).closest('.form-group').removeClass('has-error');
        },
        errorElement: 'span',
        errorClass: 'help-block',
        errorPlacement: function(error, element) {
            if(element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        }
    });
    
    $('.btn-submit').click(function(event){
        event.preventDefault();
        $('input[name=estado]').val($(this).data('estado'));
//        $('input[name="piezas"]').each(function(i){
//            alert($(this).val());
//        });
        if($(this).data('action') == "create")
        {   $('#cantidad').attr('min',0);
            if($("#body-grilla").children().length > 0)
            {
                if($('#form-comprobante').valid())
                {
                    $('.btn').addClass('disabled');
                    $('#form-comprobante').submit();
                }
            }
            else
            {
                $('#errores').html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Error</strong> Debe agregar por lo menos una pieza.</div>');
            }
        }
        else
        { 
            if (parseInt($('#cantidad').val()) === parseInt(0)){
                $('#cantidad').attr('max',1);
                $('#cantidad').val(parseInt(1));
            }
            if($('#form-comprobante').valid())
            {
                $('.btn').addClass('disabled');
                $('#form-comprobante').submit();
            }
        }
    });
    
var eliminarFilaSimple = function (btn,hoja_ruta_id, servicio_id, cantidad){
    var form_data = new FormData();                  
    form_data.append('hoja_ruta_id',hoja_ruta_id);
    form_data.append('servicio_id',servicio_id);
    form_data.append('cantidad',cantidad);

    $.ajax({
                type: "POST",
                url: '<?php echo base_url()."ajax/hojas_rutas/delete_piezas_simples" ?>',
                data: form_data,
                cache: false,
                processData: false,
                contentType: false,
                datatype:'json',
                beforeSend: function() {
                    $('#loadding').show();
                    $('.btn').addClass('disabled');
                },
                success: function(respuesta){
                        var hdr = $.parseJSON(respuesta);
                        if (hdr.respuesta > 0){
                            $(btn).parent().parent().remove();
                            $('#loadding').hide();
                            $('#errores').html('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Exito</strong> Se eliminaron '+hdr.respuesta+' registros.</div>');
                        }else{
                            $('#errores').html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Error</strong> Hubo problemas en la eliminación.</div>');
                        }
                        $('#codigo_barra_simple').val('');
                        $('#servicio_id').val('-1');
                        $('#servicio_id').attr('disabled',true);
                        $('#cantidad').val('');
                        $('#cantidad').attr('disabled',true);
                        $('#loadding').hide();
                        $('.btn').removeClass('disabled');
                    }
        });
};

</script>
