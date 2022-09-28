//var PIEZA_TIPO_SIMPLE = 1;
//var PIEZA_TIPO_NORMAL = 2

//$(document).ready(function() {
    //typeahead_init();
    
//    $('#cartero').typeahead({
//        source: <?=json_encode($carteros) ?>,
//        displayField: 'apellido_nombre',
//        onSelect: function(item) {
//            $('#transporte_id').val("");
//            $('#distribuidor_id').val("");
//            $('#cartero_id').val('');
//            $('#cartero').val('');
//            if (item.value) {
//                $('#cartero_id').val(item.value);
//            }
//        }
//    });

//    $('#zona').typeahead({
//        source: <?=json_encode($zonas) ?>,
//        displayField: 'nombre',
//        onSelect: function(item) {
//            $('#zona_id').val('');
//            $('#zona').val('');
//            if (item.value) {
//                $('#zona_id').val(item.value);
//            }
//        }
//    });

//    $('#codigo_barra').change(function(event){
//        if ($('#codigo_barra').val() == 'Result not Found' || $('#codigo_barra').val() == '' || $('#codigo_barra_id').val() == 0)
//        {
//            $('#codigo_barra').val('').focus();
//            $('#codigo_barra_id').val(0);
//            $('#cantidad_piezas').hide();
//        }
//    });

//    $('.agregar-grilla').click(function(event){
//        event.preventDefault();
//        var tab_selected = $('#tabs_piezas .active a').attr('href');
//        if (tab_selected == '#tab-2-2'){
//            if ($('#codigo_barra_simple').val() != '' && $('#servicio_id').val() > 0 &&  $('#cantidad').val() != '' ){
//                agregar_grilla_piezas_simples();
//            }else{
//                $('#errores').html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Error</strong> Debe seleccionar un servicio.</div>');
//            }
//        }else{
//            agregar_grilla();
//        }
//    });

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

    $('#distribuidor_id').change(function(){
        if($('#distribuidor_id').val() != "")
        {
            $('#zona').val("");
            $('#cartero').val("");
            $('#zona_id').val("");
            $('#cartero_id').val("");
        }
    });

//});

 
    
$(function(){
  $("#codigo_barra_simple").keyup(function (e) {
    if (e.which == 13) {
        $('#servicio_id').removeAttr('disabled');
        $('#servicio_id').get(0).focus();
    }
  });
});
    
//var agregar_grilla = function(){
////    var pieza_id = 0;
////    var pieza_id = parseInt($('#codigo_barra_id').val());
//    $.ajax({
//        type: "POST",
//        url: '<?php echo base_url()."ajax/pieza/getPieza" ?>',
//        data: 'codigo_barra='+$('#codigo_barra').val(),
//        cache: false,
//        datatype:'json',
//        beforeSend: function() {
//            //$('#errores').html('<h3><i class="fa fa-spinner fa-spin"></i></h3>');
//            //$('.btn').addClass('disabled');
//        },
//        success: function(respuesta){
//                    var codigos_iguales = $.parseJSON(respuesta);
//
//                    var array_codigos = [];
//                     $.each(codigos_iguales.grilla,function(key,value){
//                        array_codigos.push('<strong>C.I:</strong> '+value.numero+' <strong>Código:</strong> '+value.barcode_externo+' <strong>ID:</strong> '+value.pieza_id+'<br/>');
//                        pieza_id = value.numero;
//                    })
//                    if (array_codigos.length == 0){
//                        pieza_id = codigo_barra;
//                    }
//                    if (array_codigos.length > 1){
//                         bootbox.alert({ 
//                            size: "small",
//                            title: "Existen Codigos Iguales",
//                            message: array_codigos, 
//                            callback: function(){ setTimeout("$('#codigo_barra').focus();$('#codigo_barra').val('');", 0); }
//                          })
//                          array_codigos.length = 0;
//                    }else{
//                        if (pieza_id > 0){
//                            $('#codigo_barra_id').val(pieza_id);
//                            $('.agregar-grilla').click();
//                            array_codigos.length = 0;
//                        }
//                    }
//                }
//            })
//            
//            if(pieza.tipo == PIEZA_TIPO_NORMAL)
//            {
//                var inputs = '<input type="hidden" name="piezas[]" value="'+pieza_id+'">';
//                var html = '<tr>'+
//                            '<td class="barcodear">'+pieza_id+'</td>'+
//                            '<td>'+inputs+piezas[pieza_id]['servicio']+'</td>'+
//                            '<td>'+piezas[pieza_id]['descripcion']+'</td>'+
//                            '<td>Normal</td>'+
//                            '<td><a href="javascript:;" onclick="eliminarFila(this,'+pieza_id+',1)" class="btn btn-danger"><i class="fa fa-trash-o"></i></a></td>'+
//                            '</tr>';
//                if ($('#piezas_ids').val() == ''){
//                    $('#piezas_ids').val(pieza_id);
//                }else{
//                    $('#piezas_ids').val($('#piezas_ids').val()+','+pieza_id);
//                }
//                pieza.cantidad = pieza.cantidad - 1;
//            }
//    //        else if(pieza.tipo == PIEZA_TIPO_SIMPLE)
//    //        {
//    //            var cantidad = $('#cantidad').val();
//    //
//    //            var inputs = '<input type="hidden" name="cantidad['+pieza_id+'][]" value="'+cantidad+'">'+
//    //                '<input type="hidden" name="piezas[]" value="'+pieza_id+'">';
//    //
//    //            var html = '<tr>'+
//    //                '<td class="barcodear">'+$('#codigo_barra').val()+'</td>'+
//    //                '<td>'+inputs+piezas[pieza_id]['servicio']+'</td>'+
//    //                '<td>'+piezas[pieza_id]['descripcion']+'</td>'+
//    //                '<td>Simple ('+cantidad+')</td>'+
//    //                '<td><a href="javascript:;" onclick="eliminarFila(this,'+pieza_id+',false,'+cantidad+')" class="btn btn-danger"><i class="fa fa-trash-o"></i></a></td>'+
//    //                '</tr>';
//    //            pieza.subpieza[pieza.subpieza.length] = cantidad;
//    //            pieza.cantidad = pieza.cantidad - cantidad;
//    //        }
//            if(pieza.cantidad == 0)
//            {
//                for(var i=numeros.length-1; i>=0; i--)
//                {
//                  if(numeros[i]['id'] == pieza_id)
//                  {
//                    numeros_agregados[numeros_agregados.length] = numeros[i];
//                    numeros.splice(i,1);
//                  }
//                }
//
//                typeahead_reset();
//            }
//
//            $('#body-grilla').append(html);
//            $('#body-grilla').append(inputs);
//            barcodear();
//        }else{
//            var message = $("<label id='pieza-normal-message' style='color:red;margin-left:10px;font-weight:normal;line-height:.8em;font-size:10px'>La pieza no existe o no está disponible</label>");
//                    $('input#codigo_barra').after(message);
//                    $( "#codigo_barra" ).focus();
//                    $( "#codigo_barra" ).val('');
//                    $( "#pieza-normal-message" ).show();
//                    $( "#pieza-normal-message" ).delay(1000).fadeOut('slow');
//        }
//        $('#codigo_barra_id').val(0);
//        $('#codigo_barra').val('');
//        //$('#cantidad_piezas').hide();
//    }else{
//        var message = $("<label id='pieza-normal-message' style='color:red;margin-left:10px;font-weight:normal;line-height:.8em;font-size:10px'>La pieza ya se encuentra en la Hoja de Ruta</label>");
//                    $('input#codigo_barra').after(message);
//                    $( "#codigo_barra" ).focus();
//                    $( "#codigo_barra" ).val('');
//                    $( "#pieza-normal-message" ).show();
//                    $( "#pieza-normal-message" ).delay(1000).fadeOut('slow');
//        $('#codigo_barra_id').val(0);
//        $('#codigo_barra').val('');
//        $('#codigo_barra').focus();
//    }
//};

var agregar_grilla_piezas_simples = function(){

    if (cantidad == 0){ $('#cantidad').focus();}
    if (cantidad > 0) {
        var cont = 0;
        for (var i = 0; i < comprobantes_piezas_simples.length; i++){
            //var pieza_id = comprobantes_piezas_simples[i].id;
            var pieza = comprobantes_piezas_simples[i];
            if($.inArray( pieza.id, cargados_array ) == -1 && cont < cantidad){
                var descripcion = pieza.destinatario+', '+pieza.domicilio+', '+pieza.localidad+', '+pieza.codigo_postal;
                if (pieza.id > 0) {
                    var inputs =    '<input type="hidden" name="cantidad['+pieza.id+'][]" value="'+cantidad+'">'+
                                    '<input type="hidden" name="piezas[]" value="'+pieza.id+'">';

                    var html = '<tr>'+
                                '<input type="hidden" name="piezas" value="'+pieza.id+'">'+
                                '<td class="barcodear">'+pieza.barcode+'</td>'+
                                '<td>'+inputs+pieza.servicio+'</td>'+
                                '<td>'+descripcion+'</td>'+
                                '<td></td>'+
                                '<td><a href="javascript:;" onclick="eliminarFilaSimple(this,'+pieza.id+',false,'+cantidad+')" class="btn btn-danger"><i class="fa fa-trash-o"></i></a></td>'+
                                '</tr>';

                        if ($('#piezas_ids').val() == ''){
                            $('#piezas_ids').val(pieza.id);
                        }else{
                            $('#piezas_ids').val($('#piezas_ids').val()+','+pieza.id);
                        }
                    //pieza.subpieza[pieza.subpieza.length] = cantidad;
                }

                //Para que no se repitan los ids
                if ($('#cargados').val() == '') {
                    $('#cargados').val(pieza.id);
                }else{
                    $('#cargados').val($('#cargados').val()+','+pieza.id);
                }

                if(pieza.cantidad == 0)
                {
                    for(var i=numeros.length-1; i>=0; i--)
                    {
                      if(numeros[i]['id'] == pieza_id)
                      {
                        numeros_agregados[numeros_agregados.length] = numeros[i];
                        numeros.splice(i,1);
                      }
                    }

                    typeahead_reset();
                }
//                if ($('#grilla-servicio-div-'+parseInt(contador)).length >  0 ){
//                            $('#grilla-servicio-div-'+parseInt(contador)).before(inputs);
//                            $('#grilla-servicio-div-'+parseInt(contador)).before(html);
//                }else{
//                    if ($('.primera').length >  0){
//                        $('.primera').before(inputs);
//                        $('.primera').before(html);
//                        $('.primera').attr('class', 'odd');
//                    }else{
//                        $('#body-grilla').append(inputs);
//                        $('#body-grilla').append(html);
//                    }
//                }
                $('#body-grilla').append(html);
                barcodear();
                cont++;
            }
        }
        $('#codigo_barra_id').val(0);
        $('#codigo_barra').val('');
        $('#cantidad').val(parseInt($('#contador_aux_simple').val())- cantidad);
        $('#contador_aux_simple').val($('#cantidad').val());
        $('#cantidad').attr('max',$('#cantidad').val());
        $('#cantidad').attr('min',1);
       // $('#cantidad_piezas').hide();
    }
};

var typeahead_reset = function(){
//    $('#codigo_barra').typeahead('destroy');
//    typeahead_init();
};

//codigos_iguales = [];
//$(function(){
//      $("#codigo_barra").keyup(function (e) {
//        item = $("#codigo_barra").val();
//        item_id = $("#codigo_barra_id").val();
//        if (e.which == 13) {
//            $.each(numeros, function(i, numero) {
//                codigo_externo = (item).substr((item).indexOf('-',0)+1).trim();
////                alert(numero.barcode+"    "+codigo_externo);
//                if (numero.barcode == codigo_externo){
//                   codigos_iguales.push('<strong>C.I:</strong> '+numero.comprobante_nro+' <strong>Código:</strong> '+numero.barcode+' <strong>ID:</strong> '+numero.id+'<br/>');
//                   item_id = numero.id;
//                   //alert(codigos_iguales+ '<strong>C.I:</strong> '+numero.comprobante_nro+' <strong>C贸digo:</strong> '+numero.barcode+' <strong>ID:</strong> '+numero.id+'<br/>');
//                }
//            });
//            
//            if (codigos_iguales.length == 0){
//                item_id = item;
//            }
//            if (codigos_iguales.length > 1){
//                 bootbox.alert({ 
//                    size: "small",
//                    title: "Existen Codigos Iguales",
//                    message: codigos_iguales, 
//                    callback: function(){ setTimeout("$('#codigo_barra').focus();$('#codigo_barra').val('');", 0); }
//                  })
//                  codigos_iguales.length = 0;
//            }else{
//                if (item_id > 0){
//                    $('#codigo_barra_id').val(item_id);
//                    $('.agregar-grilla').click();
//                    codigos_iguales.length = 0;
//                }
//            }
//            //$('.agregar-grilla').click();
//        }
//      });
//    });
//Controlar los codigos externos iguales

//$('#codigo_barra').typeahead({
//        source: numeros,
//        displayField: 'barcode',
//        additional: 'comprobante_nro',
//        onSelect: function(item) {
//            $.each(numeros, function(i, numero) {
//                codigo_externo = (item.text).substr((item.text).indexOf('-',0)+1).trim();
//                if (numero.barcode == codigo_externo){
//                   codigos_iguales.push('<strong>C.I:</strong> '+numero.comprobante_nro+' <strong>C贸digo:</strong> '+numero.barcode+' <strong>ID:</strong> '+numero.id+'<br/>');
//                   //alert(codigos_iguales+ '<strong>C.I:</strong> '+numero.comprobante_nro+' <strong>C贸digo:</strong> '+numero.barcode+' <strong>ID:</strong> '+numero.id+'<br/>');
//                }
//            });
//            //alert(codigos_iguales);
//            if (codigos_iguales.length > 1){
//                 bootbox.alert({ 
//                    size: "small",
//                    title: "Existen Codigos Iguales",
//                    message: codigos_iguales, 
//                    callback: function(){ typeahead_reset(); setTimeout("$('#codigo_barra').focus();", 0); }
//                  })
//                  codigos_iguales.length = 0;
//            }else{
//                $('#codigo_barra_id').val(0);
//                $('#codigo_barra').val('');
//                if (item.value) {
//                    $('#codigo_barra_id').val(item.value);
//                    codigos_iguales.length = 0;
//                }
//            }
//        }
// });
 
//var typeahead_init = function(){
//    $('#codigo_barra').blur(function() {
//            $('#visitado').hide();
//            $('#errores').html('');
//            var codigo = parseInt($('#codigo_barra').val());
//            if (codigo > 0) {
//                $('#codigo_barra_id').val(codigo);
//                if(!piezas[codigo])
//                {
//                    $('#errores').html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Error</strong> La pieza que intenta agregar no existe o no est谩 disponible.</div>');
//                    return;
//                }
//                if(piezas[codigo].visitas >= 3)
//                {
//                    $("#visitado_numero").html(piezas[codigo].visitas);
//                    $("#visitado").show();
//                }
//                if(piezas[codigo]['tipo'] == PIEZA_TIPO_SIMPLE)
//                {
//                    $('#cantidad').attr({"max":piezas[codigo]['cantidad'],"min":1}).val(piezas[codigo]['cantidad']);
//                    $('#cantidad_piezas').find("span").html(piezas[codigo]['cantidad']);
//                    $('#cantidad_piezas').show();
//                }
//                else
//                {
//                }
//            }
//        });
//    });
//};

var eliminarFila = function (btn,pieza_id,subpieza_id,cantidad){

    $(btn).parent().parent().remove();

    for(var i=numeros_agregados.length-1; i>=0; i--)
    {
        if(numeros_agregados[i]['id'] == pieza_id)
        {
            numeros[numeros.length] = numeros_agregados[i];
            numeros_agregados.splice(i,1);
        }
    }
    for(var i in piezas)
    {
        if(i == pieza_id && piezas[i].subpieza)
        {
            for(var j in piezas[i].subpieza)
            {
                if(piezas[i].subpieza[j] == cantidad)
                {
                    piezas[i].cantidad = piezas[i].cantidad + cantidad;
                    delete(piezas[i].subpieza[j]);
                    break;
                }

            }
        }
    }

    if(subpieza_id)
    {
        $('#form-comprobante').prepend('<input type="hidden" name="deleted[]" value="'+subpieza_id+'"/>');
    }

    typeahead_reset();
    $('#cantidad_piezas').hide();
    
    var piezas_ids_array = $("#piezas_ids").val().split(',');
    $("#piezas_ids").val('');
    $.each(piezas_ids_array, function(j,value){
        if (value != pieza_id){
            if ($("#piezas_ids").val() == '') {
                $("#piezas_ids").val(pieza_id);
            }else{
                $("#piezas_ids").val(','+pieza_id);
            }
        }
   });
    $("#piezas_ids").val();
};

var eliminarFilaSimple = function (btn,pieza_id,subpieza_id,cantidad){

    $(btn).parent().parent().remove();

    for(var i=numeros_agregados.length-1; i>=0; i--)
    {
        if(numeros_agregados[i]['id'] == pieza_id)
        {
            numeros[numeros.length] = numeros_agregados[i];
            numeros_agregados.splice(i,1);
        }
    }
    for(var i in piezas)
    {
        if(i == pieza_id && piezas[i].subpieza)
        {
            for(var j in piezas[i].subpieza)
            {
                if(piezas[i].subpieza[j] == cantidad)
                {
                    piezas[i].cantidad = piezas[i].cantidad + cantidad;
                    delete(piezas[i].subpieza[j]);
                    break;
                }

            }
        }
    }

    if(subpieza_id)
    {
        $('#form-comprobante').prepend('<input type="hidden" name="deleted[]" value="'+subpieza_id+'"/>');
    }
    var cargados_aux = '';
    cargados_aux = $('#cargados').val();
    cargados_aux = cargados_aux.split(',');
    var array = '';
    $.each( cargados_aux, function( key, value ) {
        if (value != pieza_id){
            if(array == ''){
                array = value;
            }else{
                array = array+','+value;
            }
        }
    });
    $('#cargados').val('');
    $('#cargados').val(array);
    $('#cantidad').val(parseInt($('#cantidad').val()) + 1);
    $('#contador_aux_simple').val($('#cantidad').val());
    $('#cantidad').attr('max',$('#cantidad').val());
    $('#cantidad').attr('min',1);
    typeahead_reset();
    //$('#cantidad_piezas').hide();
};

//    $('#codigo_barra_simple').blur(function() { 
//            var codigo = $('#codigo_barra_simple').val();
//            if (codigo > 0) {
//                $('#codigo_barra_simple').val(codigo);
//                $('#codigo_barra_simple_id').val(codigo);
//                //Traigo la cantidad de items que tiene este cod de barras si es simple o normal
//                $.ajax({
//                    type: "POST",
//                    url: '<?php echo base_url()."ajax/Piezas/traerPiezasSimplesPorComprobanteYEstados" ?>',
//                    data: 'codigo_barra='+$('#codigo_barra_simple').val()+'&estados_id=<?php echo Pieza::ESTADO_EN_GESTION.','.Pieza::ESTADO_EN_DISTRIBUCION.','. Pieza::ESTADO_NO_RESPONDE.','. Pieza::ESTADO_EN_TRNSITO?>'+
//                            '&cantidad='+$('#cantidad').val()+'&cargados='+$('#cargados').val(),
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
//                        if (p !== null){
//                            $('#cantidad_servicios_simples').val(p.length);
//                            $('#cantidad').attr('max',parseInt(p.length));
//                            $('#cantidad').attr('min',1);
//                            $('#cantidad').val(p.length);
//                        }
//                        //window.location.reload();
//                    }
//                });
//            }
//        });
