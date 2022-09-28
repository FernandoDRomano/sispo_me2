//var numeros = <?=json_encode($numeros)?>;
//var piezas = <?=json_encode($piezas)?>;
var numeros_agregados = [];
var cantidad = 0;

$(function(){
    $("#codigo_barra").keyup(function (e) {
      if (e.which == 13) {
       $('#codigo_barra').blur();
      }
    });
});

$(function(){
    $("#cantidad").keyup(function (e) {
      if (e.which == 13) {
       $('.agregar-grilla').click();
      }
    });
});

$('#codigo_barra').blur(function(event) {
        if ($('#codigo_barra').val() > 0) {
            $('#codigo_barra_id').val(parseInt($('#codigo_barra').val()));
            //Traigo la cantidad de items que tiene este cod de barras si es simple o normal
            $("#servicio").empty();
            $.getJSON('<?php echo base_url()."ajax/Servicios/getServiciosNombresPorComprobante" ?>/'+$("#codigo_barra").val(),function(data){
                $("#servicio").append('<option value="-1">Seleccionar...</option>');
                $.each(data, function(id,value){
                    $("#servicio").append('<option value="'+value.id+'">'+value.nombre+'</option>');
                });
                $("#servicio").removeAttr('disabled');
                $("#servicio").focus();
            });
        }
});

$('#servicio').change(function(event) {
    $.ajax({
        type: "POST",
        url: '<?php echo base_url()."ajax/Piezas/traerSimplesDisponiblesPorComprobanteServicio" ?>',
        data: 'codigo_barra='+$('#codigo_barra').val()+'&servicio_id='+$('#servicio').val(),
        cache: false,
        datatype:'json',
        beforeSend: function() {
            //$('#errores').html('<h3><i class="fa fa-spinner fa-spin"></i></h3>');
            //$('.btn').addClass('disabled');
        },
        success: function(respuesta){
           var p = $.parseJSON(respuesta);
                if (p !== null){
                    $('#servicio_cantidad_disponible').val(p[0].cantidad);
                    $('#cantidad').attr('max',parseInt(p[0].cantidad));
                    $('#cantidad').attr('min',1);
                    $('#cantidad').val('');
                    $('#cantidad').focus();
                }
            //window.location.reload();
        }
    });
});

var typeahead_reset = function(){
   // $('#codigo_barra').typeahead('destroy');
//    typeahead_init();
};

$(document).ready(function() {

//    typeahead_init();
    
    $('#codigo_barra').change(function(event) {
//        if ($('#codigo_barra').val() == 'Result not Found' || $('#codigo_barra').val() == '' || $('#codigo_barra_id').val() == 0) {
//            $('#codigo_barra').val('').focus();
//            $('#codigo_barra_id').val(0);
//        }
    });

    $('.agregar-grilla').click(function(event) {
        var estado_id = $('#estado_id').val();
        var comprobante_id = $('#codigo_barra').val();
        var servicio_id = $('#servicio').val();
        var cantidad = $("#cantidad").val();
        //var id = $('#codigo_barra_id').val();
        var servicio_cantidad_disponible = $("#servicio_cantidad_disponible").val();
        //var cantidad_original = $("#cantidad_original").val();
        //var tipo_id = $("#tipo_id").val();
        if (parseInt(servicio_id) > 0 && parseInt(cantidad) > parseInt(0) && parseInt(cantidad) <= parseInt(servicio_cantidad_disponible)){
            var novedad = estado_id+','+comprobante_id+','+servicio_id+','+cantidad;
            var html = '<tr>'+
                            '<input type="hidden" name="novedades_simples[]" id="novedades_simples" value="'+novedad+'">'+
                            '<td>'+$( "#codigo_barra" ).val()+'</td>'+
                            '<td>'+$( "#servicio option:selected" ).text()+'</td>'+
                            //poner un input para recoger toda la info y mandar al server
                            '<td>'+$( "#estado_id option:selected" ).text()+'</td>'+
                            '<td> '+cantidad+'</td>'+
                        '</tr>';
            $('#body-grilla').append(html);
            cantidad = $('#servicio_cantidad_disponible').val()-cantidad;
                        $('#servicio_cantidad_disponible').val(cantidad);
                        $('#cantidad').attr('max',parseInt(cantidad));
                        $('#cantidad').attr('min',1);
                        $('#cantidad').val('');
                        $('.dataTables_empty').hide();
//            $.ajax({
//                type: "POST",
//                url: '<?php echo base_url()."ajax/Piezas/traerPiezasSimplesPorComprobante" ?>',
//                data: 'codigo_barra='+$('#codigo_barra_id').val()+'&cantidad='+$('#cantidad').val()+'&cargados='+$('#cargados').val(),
//                cache: false,
//                datatype:'json',
//                beforeSend: function() {
//                    //$('#errores').html('<h3><i class="fa fa-spinner fa-spin"></i></h3>');
//                    //$('.btn').addClass('disabled');
//                },
//                success: function(respuesta){
//                   var comprobantes_piezas_simples = $.parseJSON(respuesta); 
//                        if (comprobantes_piezas_simples !== null){
//                            var cont = 0;
//                            var html = '<tr>'+
//                                                    //'<input type="hidden" name="piezas" value="'+pieza.id+'">'+
//                                                    '<td class="barcodear"> - </td>'+
//                                                    '<td class="barcodear"> - </td>'+
//                                                    '<td>'+comprobantes_piezas_simples.piezas_simples[0].servicio+'</td>'+
//                                                    '<td> - </td>'+
//                                                    '<td>Simples</td>'+
//                                                    '<td> '+comprobantes_piezas_simples.piezas_simples.length+'</td>'+
//                                                    '</tr>';
//                            $('#body-grilla').append(html);
////                            $.each(p, function(i, item) {
////                                var descripcion = item.destinatario+', '+item.domicilio+', '+item.codigo_postal+', '+item.localidad
////                                maintable.row.add([
////                                    item.barcode,
////                                    '<input type="hidden" name="piezas[]" value="'+item.id+'">'+descripcion,
////                                    $("#estado_id option[value='"+$('#estado_id').val()+"']").text(),
////                                    '<a href="javascript:;" onclick="eliminarFila(this,'+item.id+')" class="btn btn-danger"><i class="fa fa-trash-o"></i></a>'
////                                ] ).draw( false );
////                                if ($('#cargados').val() == '') {
////                                    $('#cargados').val(item.id);
////                                }else{
////                                    $('#cargados').val($('#cargados').val()+','+item.id);
////                                }
////                                barcodear('#results tbody tr td:first-child');
////                                $('#inputs_cargados_estados').append('<input type="hidden" name="estado['+item.id+']" value="'+$('#estado_id').val()+'">');
////                            });
//                        cantidad = $('#servicio_cantidad_disponible').val()-cantidad;
//                        $('#servicio_cantidad_disponible').val(cantidad);
//                        $('#cantidad').attr('max',parseInt(cantidad));
//                        $('#cantidad').attr('min',1);
//                        $('#cantidad').val('');
//                    }
//                    //window.location.reload();
//                }
//            });
        }else{
            $('#errores').html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Error</strong> Cantidad de Piezas incorrecta.</div>');
        }
//        if (id > 0)
//        {
//            maintable.row.add([
//                $('#codigo_barra').val(),
//                '<input type="hidden" name="piezas[]" value="'+id+'"><input type="hidden" name="estado['+id+']" value="'+$('#estado_id').val()+'">'+piezas[id]['descripcion'],
//                $("#estado_id option[value='"+$('#estado_id').val()+"']").text(),
//                '<a href="javascript:;" onclick="eliminarFila(this,'+id+')" class="btn btn-danger"><i class="fa fa-trash-o"></i></a>'
//            ] ).draw( false );
//
//            barcodear('#results tbody tr td:first-child');
//            if (tipo_id == 2){//Quita del array de codigos disponibles el que acabo de agregar
//                for(var i=numeros.length-1; i>=0; i--)
//                {
//                  if(numeros[i]['id'] == id)
//                  {
//                    numeros_agregados[numeros_agregados.length] = numeros[i];
//                    numeros.splice(i,1);
//                  }
//                }
//                $('#codigo_barra_id').val(0);
//                $('#codigo_barra').val('');
//                $('#cantidad').val('');
//                $('#cantidad_original').val('');
//                typeahead_reset();
//            }else{
//                if (tipo_id == 1 && cantidad_original >= 1){alert(cantidad_original+' - '+cantidad); //No quita el numero para que pueda ser elegido nuevamente Envios SIMPLES
//                    cantidad = cantidad_original - cantidad;
//                    cantidad_original = cantidad;
//                    $("#cantidad_original").val(cantidad_original);
//                    $("#cantidad").val(cantidad);
//                    if(cantidad < 1){
//                        for(var i=numeros.length-1; i>=0; i--)
//                        {
//                          if(numeros[i]['id'] == id)
//                          {
//                            numeros_agregados[numeros_agregados.length] = numeros[i];
//                            numeros.splice(i,1);
//                          }
//                        }
//                        $('#codigo_barra_id').val(0);
//                        $('#codigo_barra').val('');
//                        $('#cantidad').val('');
//                        $('#cantidad_original').val('');
//                        $('#tipo_id').val('');
//                        typeahead_reset();
//                    }
//                }
//            }
//        }
    });

    $('.btn-submit').click(function(event) {
//        if(maintable.rows()[0].length > 0)
//        {
            $('.btn').addClass('disabled');
            $('#form-comprobante').submit();
//        }
//        else
//        {
//            $('#errores').html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Error</strong> Debe agregar por lo menos una pieza.</div>');
//        }
    });
});

function eliminarFila(btn, id)
{
    maintable.row($(btn).parents('tr')).remove().draw();

    for(var i=numeros_agregados.length-1; i>=0; i--)
    {
        if(numeros_agregados[i]['id'] == id)
        {
            numeros[numeros.length] = numeros_agregados[i];
            numeros_agregados.splice(i,1);
        }
    }
    var cargados =  $('#cargados').val();
    $('#cargados').val('');
    cargados = cargados.split(',');
    for (var i=0 ; i < cargados.length ; i++){
        if (cargados[i] != id){
            if ($('#cargados').val() == ''){
                $('#cargados').val(cargados[i]);
            }else{
                $('#cargados').val($('#cargados').val()+','+cargados[i]);
            }
        }
    }
    var cantidad = parseInt($('#servicio_cantidad_disponible').val())+1;
    $('#servicio_cantidad_disponible').val(cantidad);
    $('#cantidad').attr('max',parseInt(cantidad));
    $('#cantidad').attr('min',1);
    $('#cantidad').val('');

    typeahead_reset();
}