var PIEZA_TIPO_SIMPLE = 1;
var PIEZA_TIPO_NORMAL = 2

$(document).ready(function() {
    //typeahead_init();

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

//    $('#codigo_barra').change(function(event){
//        if ($('#codigo_barra').val() == 'Result not Found' || $('#codigo_barra').val() == '' || $('#codigo_barra_id').val() == 0)
//        {
//            $('#codigo_barra').val('').focus();
//            $('#codigo_barra_id').val(0);
//            $('#cantidad_piezas').hide();
//        }
//    });

    $('.agregar-grilla').click(function(event){
        event.preventDefault();
        var tab_selected = $('#tabs_piezas .active a').attr('href');
        if (tab_selected == '#tab-2-2'){
            if ($('#codigo_barra_simple').val() != '' && $('#cantidad').val() != '' ){
                agregar_grilla_piezas_simples();
            }
        }else{
            agregar_grilla();
        }
    });

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
        {
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

});

var agregar_grilla = function(){
    var pieza_id = parseInt($('#codigo_barra_id').val());
    var pieza = piezas[pieza_id];
    var bandera = true;
    $.each(numeros_agregados, function(i, item) {
        if (item.id == pieza_id) bandera = false;
    });
    if (bandera){
        if (pieza_id > 0) {
            if(pieza.tipo == PIEZA_TIPO_NORMAL)
            {
                var inputs = '<input type="hidden" name="piezas[]" value="'+pieza_id+'">';
                var html = '<tr>'+
                            '<td class="barcodear">'+$('#codigo_barra').val()+'</td>'+
                            '<td>'+inputs+piezas[pieza_id]['servicio']+'</td>'+
                            '<td>'+piezas[pieza_id]['descripcion']+'</td>'+
                            '<td>Normal</td>'+
                            '<td><a href="javascript:;" onclick="eliminarFila(this,'+pieza_id+',1)" class="btn btn-danger"><i class="fa fa-trash-o"></i></a></td>'+
                            '</tr>';
                pieza.cantidad = pieza.cantidad - 1;
            }
    //        else if(pieza.tipo == PIEZA_TIPO_SIMPLE)
    //        {
    //            var cantidad = $('#cantidad').val();
    //
    //            var inputs = '<input type="hidden" name="cantidad['+pieza_id+'][]" value="'+cantidad+'">'+
    //                '<input type="hidden" name="piezas[]" value="'+pieza_id+'">';
    //
    //            var html = '<tr>'+
    //                '<td class="barcodear">'+$('#codigo_barra').val()+'</td>'+
    //                '<td>'+inputs+piezas[pieza_id]['servicio']+'</td>'+
    //                '<td>'+piezas[pieza_id]['descripcion']+'</td>'+
    //                '<td>Simple ('+cantidad+')</td>'+
    //                '<td><a href="javascript:;" onclick="eliminarFila(this,'+pieza_id+',false,'+cantidad+')" class="btn btn-danger"><i class="fa fa-trash-o"></i></a></td>'+
    //                '</tr>';
    //            pieza.subpieza[pieza.subpieza.length] = cantidad;
    //            pieza.cantidad = pieza.cantidad - cantidad;
    //        }
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

            $('#body-grilla').append(html);
            $('#body-grilla').append(inputs);
            barcodear();
        }
        $('#codigo_barra_id').val(0);
        $('#codigo_barra').val('');
        //$('#cantidad_piezas').hide();
    }else{
        alert("La pieza ya se encuentra en la Hoja de Ruta");
        $('#codigo_barra_id').val(0);
        $('#codigo_barra').val('');
        $('#codigo_barra').focus();
    }
};

var agregar_grilla_piezas_simples = function(){
    
    var cantidad = $('#cantidad').val();
    var cargados = $('#cargados').val();
    var cargados_array  = cargados.split(',');

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

//var typeahead_init = function(){
//    $('#codigo_barra').blur(function() {
//            $('#visitado').hide();
//            $('#errores').html('');
//            var codigo = parseInt($('#codigo_barra').val());
//            if (codigo > 0) {
//                $('#codigo_barra_id').val(codigo);
//                if(!piezas[codigo])
//                {
//                    $('#errores').html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Error</strong> La pieza que intenta agregar no existe o no está disponible.</div>');
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

    $('#codigo_barra_simple').blur(function() { 
            var codigo = $('#codigo_barra_simple').val();
            if (codigo > 0) {
                $('#codigo_barra_simple').val(codigo);
                $('#codigo_barra_simple_id').val(codigo);
                //Traigo la cantidad de items que tiene este cod de barras si es simple o normal
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url()."ajax/Piezas/traerPiezasSimplesPorComprobanteYEstados" ?>',
                    data: 'codigo_barra='+$('#codigo_barra_simple').val()+'&estados_id=<?php echo Pieza::ESTADO_EN_GESTION.','.Pieza::ESTADO_EN_DISTRIBUCION.','. Pieza::ESTADO_NO_RESPONDE.','. Pieza::ESTADO_EN_TRNSITO?>'+
                            '&cantidad='+$('#cantidad').val()+'&cargados='+$('#cargados').val(),
                    cache: false,
                    datatype:'json',
                    beforeSend: function() {
                        //$('#errores').html('<h3><i class="fa fa-spinner fa-spin"></i></h3>');
                        //$('.btn').addClass('disabled');
                    },
                    success: function(respuesta){
                       var p = $.parseJSON(respuesta);
                       comprobantes_piezas_simples = p;
                       cantidad_disponible = comprobantes_piezas_simples.length;
                       $('#contador_aux_simple').val(cantidad_disponible);
                        if (p !== null){
                            $('#cantidad_servicios_simples').val(p.length);
                            $('#cantidad').attr('max',parseInt(p.length));
                            $('#cantidad').attr('min',1);
                            $('#cantidad').val(p.length);
                        }
                        //window.location.reload();
                    }
                });
            }
        });
    