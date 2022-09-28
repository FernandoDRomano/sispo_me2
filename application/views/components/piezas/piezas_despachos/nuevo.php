<div class="col-xs-12">
  <div class="ibox-content">
    <div class="panel blank-panel">
      <div class="panel-heading">
        <div class="panel-options">
          <ul class="nav nav-tabs">
          <li><a href="<?=base_url('piezas/despachos')?>">Lista de Despachos</a></li>
            <?php if($this->is_admin()): ?>
              <li><a href="<?=base_url('piezas/despachos/archivados')?>">Despachos Archivados</a></li>
            <?php endif; ?>
            <li class="active"><a data-toggle="tab" href="#tab-1">Nuevo Despacho</a></li>
            <li><a href="<?=base_url('piezas/despachos/nuevoMasivo')?>">Nuevo Despacho Masivo</a></li>
          </ul>
        </div>
      </div>
      <div class="panel-body">
        <?php if($this->session->flashdata('registro')): ?>
          <div class="row">
            <div class="col-md-12">
              <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                El despacho <b># <?=$this->session->flashdata('registro'); ?></b> fue guardado con exito.
              </div>
            </div>
          </div>
        <?php endif; ?>
        <div class="tab-content">
          <div id="tab-1" class="tab-pane active">
            <?=form_open(current_url(), array('id'=>"form-despacho")); ?>
              <input type="hidden" name="enviar" value=""/>
              <input type="hidden" name="despacho_id" id="despacho_id" value="<?php echo isset($despacho)? $despacho->id:""?>"/>
              <div id="errores"></div>
              <div>
                <div class="row">
                  <div class="col-xs-4">
                    <div class="form-group">
                      <label for="origen_id">Sucursal Origen</label>
                      <?php if($this->is_user()): ?>
                        <select id="origen_id" name="origen_id"  class="form-control" required>
                          <?php foreach($sucursal_origen as $sucursal): ?>
                            <option value="<?=$sucursal->id ?>"><?=$sucursal->nombre ?></option>
                          <?php endforeach; ?>
                        </select>
                      <?php else: ?>
                        <select id="origen_id" name="origen_id" class="form-control" required>
                          <?php foreach($sucursal_origen as $sucursal): ?>
                            <option value="<?=$sucursal->id ?>"><?=$sucursal->nombre ?></option>
                          <?php endforeach; ?>
                        </select>
                      <?php endif; ?>
                    </div>
                  </div>
                  <div class="col-xs-4">
                    <div class="form-group">
                      <label for="destino_id">Sucursal Destino</label>
                      <select id="destino_id" name="destino_id" class="form-control"  required >
                        <?php foreach($sucursales as $sucursal): ?>
                          <option value="<?=$sucursal->id ?>"><?=$sucursal->nombre ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-xs-4">
                    <div class="form-group">
                      <label for="transporte_id">Transporte</label>
                      <select id="transporte_id" name="transporte_id" class="form-control"  required >
                        <?php foreach ($transportes as $transporte): ?>
                          <option value="<?=$transporte->id ?>"><?=$transporte->nombre ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-xs-6">
                    <div class="form-group">
                      <label for="usuario_origen_id">Usuario Envio</label>
                      <?php if($this->is_user()): ?>
                        <select id="usuario_origen_id" name="usuario_origen_id" class="form-control" required>
                            <?php foreach($usuarios as $usuario): ?>
                                <option value="<?=$usuario->id ?>"><?=$usuario->apellido.', '.$usuario->nombre ?></option>
                            <?php endforeach; ?>
                        </select>
                      <?php else: ?>
                        <select id="usuario_origen_id" name="usuario_origen_id" class="form-control" required>
                          <?php foreach($usuarios as $usuario): ?>
                            <option value="<?=$usuario->id ?>"><?=$usuario->apellido.', '.$usuario->nombre ?></option>
                          <?php endforeach; ?>
                        </select>
                      <?php endif; ?>
                    </div>
                  </div>
                  <div class="col-xs-6">
                    <div class="form-group">
                      <label for="fecha_envio">Fecha Salida</label>
                      <input id="fecha_envio" name="fecha_envio" type="text" class="form-control datepicker" placeholder="Fecha Salida" value="<?=date('d-m-Y') ?>" required autocomplete="off" />
                    </div>
                  </div>
                </div>
                <hr>
                <div class="tabs-container grey">
                  <ul id="tabs_piezas" class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#tab-2-1" aria-expanded="false">Agregar Piezas Normales</a></li>
                    <li><a data-toggle="tab" href="#tab-2-2" aria-expanded="true">Agregar Piezas Simples</a></li>
                  </ul>
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
                          <div class="col-xs-4">
                            <div class="form-group">
                              <div class="row">
                                <div class="col-xs-12">
                                  <div class="form-group">
                                    <input id="piezas_ids" name="piezas_ids" type="hidden" value="" />
                                    <input id="piezas_simples_ids" name="piezas_simples_ids" type="hidden" value="" />
                                    <input id="codigo_barra_simple" name="codigo_barra_simple" type="text" class="form-control" placeholder="Codigo barra del comprobante" autocomplete="off" />
                                    <input id="cantidad_servicios_simples" name="cantidad_servicios_simples" type="hidden" value="0" />
                                    <input id="contador_simple" name="contador_simple" type="hidden" value="0" />
                                    <input id="contador_aux_simple" name="contador_aux_simple" type="hidden" value="0" />
                                    <input id="codigo_barra_simple_id" name="codigo_barra_simple_id" type="hidden" value="0" />
                                    <input id="comprobantes_piezas_simples" name="comprobantes_piezas_simples" type="hidden" value="0" />
                                  </div>
                                  <div class="alert alert-warning" id="visitado" style="display:none">
                                    <strong>Advertencia</strong>
                                    El domicilio de la pieza que intenta agregar ya fue visitado <b><span id="visitado_numero"></span></b> veces
                                  </div>
                                </div>
                                </div>
                            </div>
                          </div>
                            <div class="col-xs-5">
                                <div class="form-group">
                                  <select id="servicio_id" class="form-control" disabled="disabled" placeHolder="Servicios...">
                                    <option value="">Seleccione</option>
                                  </select>
                                </div>
                              </div>
                          <div class="col-xs-3">
                            <div class="form-group">
                              <div class="form-group" id="cantidad_piezas" style="">
                                <input type="number" min="0" max="" step="1" class="form-control" id="cantidad" placeHolder="Cantidad de piezas"/> &nbsp;
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
                <div class="row">
                  <div class="col-xs-12 text-center">
                    <div class="control-group text-right">
                      <div class="controls">
                        <span id="loadding" ><i style="font-size:24px" class="fa fa-spinner fa-spin"></i></span>
                        <a class="btn btn-primary agregar-grilla"><i class="fa fa-arrow-down"></i> Agregar</a>
                        <!--<a class="btn btn-success btn-submit" href="javascript:;"><i class='fa fa-floppy-o'></i> Guardar</a>-->
                        <a class="btn btn-success btn-enviar" href="javascript:;"><i class='fa fa-truck'></i> Enviar</a>
                      </div>
                    </div>
                  </div>
                </div>
                <hr>
              </div>
              <table class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
                <thead>
                  <tr>
                    <th class="col-xs-1">Despacho</th>
                    <th class="col-xs-1">Codigo</th>
                    <th class="col-xs-2">Codigo Externo</th>
                    <th class="col-xs-3">Servicio</th>
                    <th class="col-xs-5">Descripción</th>
                    <th class="col-xs-1">Tipo</th>
                    <th class="col-xs-1">Cantidad</th>
                  </tr>
                </thead>
                <tbody id="body-grilla">
                </tbody>
              </table>
            <?=form_close(); ?>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">

<?php //include 'script_nuevo.js'; ?>
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
                                        $('#codigo_barra').val(barcode_externo);
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
    if ($('#codigo_barra_simple').val() != 0){
        $("#servicio_id").empty();
        $.getJSON('<?php echo base_url()."ajax/servicios/getServiciosPorComprobante" ?>/'+$("#codigo_barra_simple").val(),function(data){
                if (data.status){
                    alert("El comprobante no tiene servicios asociados");
                    $("#servicio_id").attr("disabled","disabled");
                    $("#codigo_barra_simple").val("");
                    $("#codigo_barra_simple").focus();
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
            if (codigo > 0) {
                $('#codigo_barra_simple').val(codigo);
                $('#codigo_barra_simple_id').val(codigo);
                //Traigo la cantidad de items que tiene este cod de barras si es simple o normal
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url()."ajax/despachos/traerPiezasSimplesPorComprobanteEstadosYServicio" ?>',
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
                       //$('#comprobantes_piezas_simples').val(comprobantes_piezas_simples);
                        if (!p.status){
                            comprobantes_piezas_simples = p;
                            cantidad_disponible = comprobantes_piezas_simples.length;//Aqui tendria que ir el CONTADOR
                            $('#contador_aux_simple').val(cantidad_disponible);
                            $('#cantidad_servicios').val(cantidad_disponible);
                            $('#cantidad_servicios_simples').val(p.length);
                            $('#cantidad').attr('max',parseInt(p.length));
                            $('#cantidad').attr('min',0);
                            $('#cantidad').val(p.length);
                        }else{
                            $('#cantidad_servicios_simples').val(0);
                            $('#cantidad').attr('max',parseInt(0));
                            $('#cantidad').attr('min',0);
                            $('#cantidad').val(0);
                            $("#cantidad").addAttr('disabled');
                        }
                        //window.location.reload();
                    }
                });
            }
        });

$('.agregar-grilla').click(function(event){
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
    var other_data = $('#form-despacho').serializeArray();
    $.each(other_data,function(key,input){
        form_data.append(input.name,input.value);
    });
    var buscar_por = $('input:radio[name=buscar_por]:checked').val();
    $.ajax({
        type: "POST",
        url: '<?php echo base_url()."ajax/Despachos/grabarPiezaNormal" ?>',
        data: form_data,
        processData: false,
        contentType: false,                
        datatype:'json',
        beforeSend: function() {
            $('#loadding').show();
            $('.btn').addClass('disabled');
        },
        success: function(respuesta){//alert(JSON.stringify(respuesta));
                $('#loadding').hide();
                $('.btn').removeClass('disabled');
                var pieza = $.parseJSON(respuesta);
                if (!pieza.status){
                    $('#despacho_id').val(pieza[1]); //grabo la var hidden para que el proximo ingreso pnga la pieza en el mismo despacho
                    var inputs = '<input type="hidden" name="piezas[]" value="'+pieza[0].pieza_id+'">';
                    var html = '<tr>'+
                                '<td class="barcodear">'+pieza[1]+'</td>'+
                                '<td class="barcodear">'+pieza[0].pieza_id+'</td>'+
                                '<td class="barcodear">'+pieza[0].barcode_externo+'</td>'+
                                '<td>'+pieza[0].servicio+'</td>'+
                                '<td><strong>'+pieza[0].destinatario+'</strong><br/>'+pieza[0].domicilio+" "+pieza[0].codigo_postal+" "+pieza[0].localidad+'</td>'+
                                '<td>Normal</td>'+
                                '<td>1</td>'+
                                '<td><a href="javascript:;" onclick="eliminarFila(this,'+pieza[0].pieza_id+','+pieza[1]+')" class="btn btn-danger"><i class="fa fa-trash-o"></i></a></td>'+
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

var eliminarFila = function (btn,pieza_id,despacho_id){
    var form_data = new FormData();                  
    form_data.append('pieza_id',pieza_id);    
    form_data.append('despacho_id',despacho_id);
    $.ajax({
            type: "POST",
            url: '<?php echo base_url()."ajax/Despachos/delete_pieza_normal" ?>',
            data: form_data,
            processData: false,
            contentType: false,                
            datatype:'json',
            beforeSend: function() {
                $('#loadding').show();
                $('.btn').addClass('disabled');
            },
            success: function(respuesta){
                    if (respuesta.respuesta) alert(respuesta.respuesta);
                    $('#loadding').hide();
                    $('.btn').removeClass('disabled');
                    var pieza = $.parseJSON(respuesta);
                    $(btn).parent().parent().remove();
            }
        });
};

var agregar_grilla_piezas_simples = function(){
    var form_data = new FormData();                  
    var other_data = $('#form-despacho').serializeArray();
    $.each(other_data,function(key,input){
        form_data.append(input.name,input.value);
    });
    form_data.append("cantidad",$('#cantidad').val());
    form_data.append("servicio_id",$('#servicio_id').val());
    var cantidad = $('#cantidad').val();
    if (cantidad === 0){ $('#cantidad').focus();}
    if (cantidad > 0) 
        //Traigo las piezas que tiene este comprobante limitandome a la cantidad que se requieren
        $.ajax({
                type: "POST",
                url: '<?php echo base_url()."ajax/despachos/grabarPiezasSimples" ?>',
                data: form_data,
                processData: false,
                contentType: false,                
                datatype:'json',
                beforeSend: function() {
                    //$('#errores').html('<h3><i class="fa fa-spinner fa-spin"></i></h3>');
                    $('.btn').addClass('disabled');
                },
                success: function(respuesta){
                        var resultado = $.parseJSON(respuesta);
                        if (!resultado.status){
                            $('#despacho_id').val(resultado[0].despacho_id);
                            var html = '<tr>'+
                                                    //'<input type="hidden" name="piezas" value="'+pieza.id+'">'+
                                                    '<td class="barcodear"> '+resultado[0].despacho_id+' </td>'+
                                                    '<td class="barcodear"> - </td>'+
                                                    '<td class="barcodear"> - </td>'+
                                                    '<td>'+resultado[0].servicio+'</td>'+
                                                    '<td> - </td>'+
                                                    '<td>Simples</td>'+
                                                    '<td> '+resultado[0].cantidad+'</td>'+
                                                    '<td><a href="javascript:;" onclick="eliminarFilaSimples(this,'+resultado[0].servicio_id+','+resultado[0].despacho_id+')" class="btn btn-danger"><i class="fa fa-trash-o"></i></a></td>'+
                                                    '</tr>';
                            $('#body-grilla').append(html);
                            $('.btn').removeClass('disabled');
//                           
                        }
                }
        });
        
        $('#codigo_barra_id').val(0);
        $('#codigo_barra').val('');
        $('#cantidad').val(parseInt($('#contador_aux_simple').val())- cantidad);
        $('#contador_aux_simple').val($('#cantidad').val());
        $('#cantidad').attr('max',$('#cantidad').val());
        $('#cantidad').attr('min',1);
       // $('#cantidad_piezas').hide();
    }

var eliminarFilaSimples = function (btn,servicio_id,despacho_id){
        var form_data = new FormData();                  
        form_data.append('servicio_id',servicio_id);    
        form_data.append('despacho_id',despacho_id);
        $.ajax({
                type: "POST",
                url: '<?php echo base_url()."ajax/Despachos/delete_piezas_simples" ?>',
                data: form_data,
                processData: false,
                contentType: false,                
                datatype:'json',
                beforeSend: function() {
                    $('#loadding').show();
                    $('.btn').addClass('disabled');
                },
                success: function(respuesta){
                        $('#loadding').hide();
                        $('.btn').removeClass('disabled');
                        //var pieza = $.parseJSON(respuesta);
                        $(btn).parent().parent().remove();
                }
            });
    };
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
//var eliminarFilaSimple = function (btn,pieza_id,subpieza_id,cantidad){
//
//    $(btn).parent().parent().remove();
//
//    for(var i=numeros_agregados.length-1; i>=0; i--)
//    {
//        if(numeros_agregados[i]['id'] == pieza_id)
//        {
//            numeros[numeros.length] = numeros_agregados[i];
//            numeros_agregados.splice(i,1);
//        }
//    }
//    for(var i in piezas)
//    {
//        if(i == pieza_id && piezas[i].subpieza)
//        {
//            for(var j in piezas[i].subpieza)
//            {
//                if(piezas[i].subpieza[j] == cantidad)
//                {
//                    piezas[i].cantidad = piezas[i].cantidad + cantidad;
//                    delete(piezas[i].subpieza[j]);
//                    break;
//                }
//
//            }
//        }
//    }
//
//    if(subpieza_id)
//    {
//        $('#form-comprobante').prepend('<input type="hidden" name="deleted[]" value="'+subpieza_id+'"/>');
//    }
//    var cargados_aux = '';
//    cargados_aux = $('#cargados').val();
//    cargados_aux = cargados_aux.split(',');
//    var array = '';
//    $.each( cargados_aux, function( key, value ) {
//        if (value != pieza_id){
//            if(array == ''){
//                array = value;
//            }else{
//                array = array+','+value;
//            }
//        }
//    });
//    $('#cargados').val('');
//    $('#cargados').val(array);
//    $('#cantidad').val(parseInt($('#cantidad').val()) + 1);
//    $('#contador_aux_simple').val($('#cantidad').val());
//    $('#cantidad').attr('max',$('#cantidad').val());
//    $('#cantidad').attr('min',1);
//    typeahead_reset();
//    //$('#cantidad_piezas').hide();
//};

//            var codigo = $('#codigo_barra_simple').val();
//            if (codigo > 0) {
//                $('#codigo_barra_simple').val(codigo);
//                $('#codigo_barra_simple_id').val(codigo);
//                //Traigo la cantidad de items que tiene este cod de barras si es simple o normal
//                $.ajax({
//                    type: "POST",
//                    url: '<?php echo base_url()."ajax/Piezas/traerPiezasSimplesPorComprobanteYEstados" ?>',
//                    data: 'codigo_barra='+$('#codigo_barra_simple').val()+'&estados_id=<?php echo Pieza::ESTADO_EN_GESTION.','.Pieza::ESTADO_EN_DISTRIBUCION.','. Pieza::ESTADO_NO_RESPONDE.','. Pieza::ESTADO_EN_TRNSITO?>',
//                    cache: false,
//                    datatype:'json',
//                    beforeSend: function() {
//                        //$('#errores').html('<h3><i class="fa fa-spinner fa-spin"></i></h3>');
//                        //$('.btn').addClass('disabled');
//                    },
//                    success: function(respuesta){
//                       var p = $.parseJSON(respuesta);
//                       comprobantes_piezas_simples = p;
//                       cantidad_disponible = comprobantes_piezas_simples.length;
//                       $('#contador_aux_simple').val(cantidad_disponible);
//                       $('#cantidad_servicios').val(cantidad_disponible);
//                       $('#comprobantes_piezas_simples').val(comprobantes_piezas_simples);
//                        if (p !== null){
//                            $('#cantidad_servicios_simples').val(p.length);
//                            $('#cantidad').attr('max',parseInt(p.length));
//                            $('#cantidad').attr('min',0);
//                            $('#cantidad').val(p.length);
//                        }
//                        //window.location.reload();
//                    }
//                });
//            }
//    });        
</script>
