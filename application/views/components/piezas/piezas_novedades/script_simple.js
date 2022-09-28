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
                    $("#servicio").append('<option value="'+value.comprobante_servicio_id+'">'+value.nombre+'</option>');
                });
                $("#servicio").removeAttr('disabled');
                $("#servicio").focus();
            });
        }
});

$('#servicio').change(function(event) {
    $('#body-grilla').html("");
    $.ajax({
        type: "POST",
        url: '<?php echo base_url()."ajax/novedades/traerSimplesDisponiblesPorComprobanteServicio" ?>',
        data: 'codigo_barra='+$('#codigo_barra').val()+'&servicio_id='+$('#servicio').val()+'&hdr_id='+$('#hdr_id').val(),
        cache: false,
        datatype:'json',
        beforeSend: function() {
            //$('#errores').html('<h3><i class="fa fa-spinner fa-spin"></i></h3>');
            //$('.btn').addClass('disabled');
        },
        success: function(respuesta){
                var novedad = $.parseJSON(respuesta);
                //Traigo las novedades de este comprobante y servicio
                if (novedad.novedades.length > 0){
                    $.each(novedad.novedades, function (index, value) {
                        var html = '<tr>'+
                                        '<td>'+value.numero+'</td>'+
                                        '<td>'+value.servicio+'</td>'+
                                        '<td>'+value.estado+'</td>'+
                                        '<td>'+value.piezas+'</td>'+
                                    '</tr>';
                        $('#body-grilla').append(html);
                        $('.dataTables_empty').hide();
                    });
                }   
                    $('#servicio_cantidad_disponible').val(novedad.cantidad[0].cantidad);
                    $('#cantidad').attr('max',parseInt(novedad.cantidad[0].cantidad));
                    $('#cantidad').attr('min',1);
                    $('#cantidad').val('');
                    $('#cantidad').focus();
                
            //window.location.reload();
        }
    });
});

var typeahead_reset = function(){
   // $('#codigo_barra').typeahead('destroy');
//    typeahead_init();
};

$(document).ready(function() {

    $('#codigo_barra').change(function(event) {});

    $('.agregar-grilla').click(function(event) {
        var form_data = new FormData();                  
        var other_data = $('#form-novedades').serializeArray();
        $.each(other_data,function(key,input){
            form_data.append(input.name,input.value);
        });
        $.ajax({
            type: "POST",
            url: '<?php echo base_url()."ajax/novedades/grabar_novedades_piezas_simples" ?>',
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
                    $('#body-grilla').html('');
                    var novedad = $.parseJSON(respuesta);
                    if (!novedad.status){
                        if (novedad.novedades.length > 0){
                            $.each(novedad.novedades, function (index, value) {
                                var html = '<tr>'+
                                                '<td>'+value.numero+'</td>'+
                                                '<td>'+value.servicio+'</td>'+
                                                '<td>'+value.estado+'</td>'+
                                                '<td>'+value.piezas+'</td>'+
                                            '</tr>';
                                $('#body-grilla').append(html);
                                $('.dataTables_empty').hide();
                            });
                        }   
                            $('#servicio_cantidad_disponible').val('');
                            $('#servicio_cantidad_disponible').val('');
                            $("#servicio").empty();
                            $('#codigo_barra').val('');
                            $('#cantidad').val('');
                    }
            }
//        }else{
//            $('#errores').html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Error</strong> Cantidad de Piezas incorrecta.</div>');
//        }

        });
    });

    $('.btn-submit').click(function(event) {
            $('.btn').addClass('disabled');
            $('#form-comprobante').submit();
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