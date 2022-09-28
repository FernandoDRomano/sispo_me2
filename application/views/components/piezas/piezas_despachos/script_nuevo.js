var comprobantes_piezas_simples = '';
var cantidad_disponible = '';
var piezas;
var piezas_agregadas;
var numeros_agregados;
var numeros;

var despacho_ids = <?=json_encode($despacho_ids)?>;
    //[{"id":35,"barcode":"000035"}]
    var numeros = <?=json_encode($numeros)?>;
    //{"35":{"id":35,"descripcion":""}};
    var piezas = <?=json_encode($piezas)?>;
    piezas_agregadas = {};
    numeros_agregados = [];
//    var comprobantes_piezas_simples = '';
//    var cantidad_disponible = '';
//render: function (items) { TYPEHEAD.JS Modificado

//Controlar los codigos externos iguales
codigos_iguales = [];
$(function(){
      $("#codigo_barra").keyup(function (e) {
        item = $("#codigo_barra").val();
        item_id = $("#codigo_barra_id").val();
        if (e.which == 13) {
            $.each(numeros, function(i, numero) {
                codigo_externo = (item).substr((item).indexOf('-',0)+1).trim();
//                alert(numero.barcode+"    "+codigo_externo);
                if (numero.barcode == codigo_externo){
                   codigos_iguales.push('<strong>C.I:</strong> '+numero.comprobante_nro+' <strong>Código:</strong> '+numero.barcode+' <strong>ID:</strong> '+numero.id+'<br/>');
                   item_id = numero.id;
                   //alert(codigos_iguales+ '<strong>C.I:</strong> '+numero.comprobante_nro+' <strong>C贸digo:</strong> '+numero.barcode+' <strong>ID:</strong> '+numero.id+'<br/>');
                }
            });
            
            if (codigos_iguales.length == 0){
                item_id = item;
            }
            
            if (codigos_iguales.length > 1){
                 bootbox.alert({ 
                    size: "small",
                    title: "Existen Codigos Iguales",
                    message: codigos_iguales, 
                    callback: function(){ setTimeout("$('#codigo_barra').focus();$('#codigo_barra').val('');", 0); }
                  })
                  codigos_iguales.length = 0;
            }else{
                if (item_id > 0){
                    $('#codigo_barra_id').val(item_id);
                    $('.agregar-grilla').click();
                    codigos_iguales.length = 0;
                }
            }
            //$('.agregar-grilla').click();
        }
      });
    });

$('.agregar-grilla').click(function(event){
       // event.preventDefault();
        var tab_selected = $('#tabs_piezas .active a').attr('href');
        if (tab_selected == '#tab-2-2'){
            if ($('#codigo_barra_simple').val() != '' && $('#cantidad').val() != '' ){
                agregar_grilla_piezas_simples();
            }
        }else{
                agregar_grilla();
            }
    });
//$(document).ready(function() {

    //[{"id":0,"name":3}]
    

//    $('#despacho_id').typeahead({
//            source: despacho_ids,
//            onSelect: function(item)
//            {
//                    $('#numero_id').val(0);
//                    if (item.value) {
//                            $('#numero_id').val(item.value);
//                    }
//            }
//    });


    //typeahead_init();

//    $('#codigo_barra').change(function(event) {
////        if ($('#codigo_barra').val() == 'Result not Found' || $('#codigo_barra').val() == '' || $('#codigo_barra_id').val() == 0) {
//            $('#codigo_barra').val('').focus();
//             $('.agregar-grilla').click();
////            $('#codigo_barra_id').val(0);
////        }
//    });

//    $(function(){
//      $("#codigo_barra").keyup(function (e) {
//        if (e.which == 13) {
//         $('.agregar-grilla').click();
//        }
//      });
//    });
    
    $(function(){
      $("#codigo_barra_simple").keyup(function (e) {
        if (e.which == 13) {
            $('#servicio_id').removeAttr('disabled');
            $('#servicio_id').get(0).focus();
        }
      });
    });
    
    
    var agregar_grilla = function(){
        var pieza_id = $('#codigo_barra_id').val();
        var cantidad_servicios = $('#cantidad_servicios').val();
        var contador = $('#contador').val();
        $.each(piezas, function (index, value) {
            if(value['barcode_externo'] == $.trim($('#codigo_barra').val())){
                $('#codigo_barra_id').val(value['id']);
            }
        });
        if ($('#codigo_barra_id').val() == parseInt(0)){
            $('#codigo_barra_id').val(parseInt($('#codigo_barra').val()));
        }
        if ($('#codigo_barra_id').val() > 0) {
            if(!($('#grilla-servicio-'+$('#codigo_barra_id').val()).length))
            {
                var total = parseInt(cantidad_servicios) + parseInt(1);
                var total_c = parseInt(contador) + parseInt(1);
//                var pieza_id = $('#codigo_barra_id').val();
                 if (piezas[pieza_id] === undefined){
                    var message = $("<label id='pieza-normal-message' style='color:red;margin-left:10px;font-weight:normal;line-height:.8em;font-size:10px'>La pieza no esta disponible</label>");
                    $('input#codigo_barra_id').after(message);
                    $( "#codigo_barra" ).focus();
                    $( "#codigo_barra" ).val('');
                    $( "#pieza-normal-message" ).show();
                    $( "#pieza-normal-message" ).delay(1000).fadeOut('slow');
                    return;
                }
                var inputs = '<div id="grilla-servicio-div-'+pieza_id+'">'+
                        '<input type="hidden" name="pieza[]" value="'+pieza_id+'">'+
                        '</div>';
                var html = '<tr id="grilla-servicio-'+pieza_id+'">'+
                        '<td class="barcodear">'+piezas[pieza_id].barcode+'</td>'+
                        '<td class="barcodear">'+piezas[pieza_id].barcode_externo+'</td>'+
                        '<td class="">'+piezas[pieza_id].nombre_servicio+'</td>'+ //Nombre del servicio
                        '<td>'+piezas[pieza_id].descripcion+'</td>'+
                        '<td><a href="javascript:;" onclick="eliminarFila('+pieza_id+')" class="btn btn-danger"><i class="fa fa-trash-o"></i></a></td>'+
                        '</tr>';

                $('#body-grilla').append(inputs);
                $('#body-grilla').append(html);
                barcodear();
                $('#cantidad_servicios').val(total);
                $('#contador').val(total_c);

                var barcode = $('#codigo_barra_id').val();
                var aux = $('#contador_aux').val();

                if (barcode > aux) $('#contador_aux').val(barcode);

                piezas_agregadas[pieza_id] = piezas[pieza_id];
                delete piezas[pieza_id];
                for(var i=numeros.length-1; i>=0; i--)
                {
                    if(numeros[i]['id'] == pieza_id)
                    {
                        numeros_agregados[numeros_agregados.length] = numeros[i];
                        numeros.splice(i,1);
                    }
                }
                $('#codigo_barra').typeahead('destroy');
               // typeahead_init();
            }
        }
        $('#codigo_barra_id').val(0);
        $('#codigo_barra').val('');
    };
    
    var agregar_grilla_piezas_simples = function(){
        var cantidad = $('#cantidad').val();
        var cargados = $('#cargados').val();
        var cargados_array  = cargados.split(',');
        var cont = 0;
        for (var i = 0; i < comprobantes_piezas_simples.length; i++){
            //var pieza_id = comprobantes_piezas_simples[i].id;
            var pieza = comprobantes_piezas_simples[i];
            if($.inArray( pieza.id, cargados_array ) == -1 && cont < cantidad){
                var descripcion = pieza.destinatario+', '+pieza.domicilio+', '+pieza.localidad+', '+pieza.codigo_postal;
                if (pieza.id > 0) {
                    
                     var inputs = '<div id="grilla-servicio-div-'+pieza.id+'">'+
                        '<input type="hidden" name="pieza[]" value="'+pieza.id+'">'+
                        '</div>';
                var html = '<tr id="grilla-servicio-'+pieza.id+'">'+
                        '<td class="barcodear">'+pieza.barcode+'</td>'+
                        '<td class="barcodear">'+pieza.barcode_externo+'</td>'+
                        '<td class="barcodear">'+pieza.servicio+'</td>'+ //Nombre del servicio
                        '<td>'+descripcion+'</td>'+
                        '<td><a href="javascript:;" onclick="eliminarFilaSimple('+pieza.id+')" class="btn btn-danger"><i class="fa fa-trash-o"></i></a></td>'+
                        '</tr>';

                $('#body-grilla').append(inputs);
                $('#body-grilla').append(html);
                barcodear();
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

//                $('#body-grilla').append(html);
//                barcodear();
                cont++;
            }
        }
        $('#codigo_barra_id').val(0);
        $('#codigo_barra').val('');
        $('#cantidad').val(parseInt($('#contador_aux_simple').val())- cantidad);
        $('#contador_aux_simple').val($('#cantidad').val());
        $('#cantidad').attr('max',$('#cantidad').val());
        $('#cantidad').attr('min',0);
       // $('#cantidad_piezas').hide();
    };

    $("#form-comprobante").validate({
        rules: {
            origen_id: {required: true},
            destino_id: {required: true},
        },
        messages: {
            origen_id: "Campo requerido.",
            destino_id: "Campo requerido.",
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

    var submit_form = function(){
        var cantidad = $('#cargados').val().split(',').length;
        if ( cantidad > 0) {
            $('#cantidad').attr('min',0);//Necesario para pasar la validacion
            if($('#form-comprobante').valid()){
                $("select[disabled]").removeAttr("disabled");
                $('.btn').addClass('disabled');
                $('#form-comprobante').submit();
            }
        }else{
            $('#errores').html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Error</strong> Debe agregar por lo menos una pieza.</div>');
        }
    };

    $('.btn-enviar').click(function(event) {
        $('input[name=enviar]').val(1);
        submit_form();
    });

    $('.btn-submit').click(function(event) {
        $('input[name=enviar]').val('');
        submit_form();
    });
//});

function eliminarFila(id){
    $('#grilla-servicio-div-'+id).remove();
    $('#grilla-servicio-'+id).remove();
    var cantidad_servicios_c = $('#cantidad_servicios').val();
    var total_c = parseInt(cantidad_servicios_c) - parseInt(1);
    $('#cantidad_servicios').val(total_c);

    for(var i=numeros_agregados.length-1; i>=0; i--)
    {
        if(numeros_agregados[i]['id'] == id)
        {
            numeros[numeros.length] = numeros_agregados[i];
            numeros_agregados.splice(i,1);
        }
    }
    piezas[id] = piezas_agregadas[id];
    delete piezas_agregadas[id];

    $('#codigo_barra').typeahead('destroy');
    typeahead_init();
}

function eliminarFilaSimple(id){
    $('#grilla-servicio-div-'+id).remove();
    $('#grilla-servicio-'+id).remove();
    var cantidad_servicios_c = $('#cantidad_servicios').val();
    var total_c = parseInt(cantidad_servicios_c) - parseInt(1);
    $('#cantidad_servicios').val(total_c);

    for(var i=numeros_agregados.length-1; i>=0; i--)
    {
        if(numeros_agregados[i]['id'] == id)
        {
            numeros[numeros.length] = numeros_agregados[i];
            numeros_agregados.splice(i,1);
        }
    }
    piezas[id] = piezas_agregadas[id];
    delete piezas_agregadas[id];

    var cargados_aux = '';
    cargados_aux = $('#cargados').val();
    cargados_aux = cargados_aux.split(',');
    var array = '';
    $.each( cargados_aux, function( key, value ) {
        if (value != id){
            if(array == ''){
                array = value;
            }else{
                array = array+','+value;
            }
        }
    });

    $('#codigo_barra').typeahead('destroy');
    $('#cargados').val('');
    $('#cargados').val(array);
    $('#cantidad').val(parseInt($('#cantidad').val()) + 1);
    $('#contador_aux_simple').val($('#cantidad').val());
    $('#cantidad').attr('max',$('#cantidad').val());
    $('#cantidad').attr('min',1);
    typeahead_init();
}

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
//    typeahead_init();
    //$('#cantidad_piezas').hide();
//};;