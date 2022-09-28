var numeros = <?=json_encode($numeros)?>;
var piezas = <?=json_encode($piezas)?>;
var numeros_agregados = [];
//var cantidad = 0;

//Controlar los codigos externos iguales
codigos_iguales = [];
//var typeahead_init = function()
//{
    $('#codigo_barra').typeahead({
        source: numeros,
        displayField: 'barcode',
        additional: 'comprobante_nro',
        onSelect: function(item) {
            $.each(numeros, function(i, numero) {
                codigo_externo = (item.text).substr((item.text).indexOf('-',0)+1).trim();
                if (numero.barcode == codigo_externo){
                   codigos_iguales.push('<strong>C.I:</strong> '+numero.comprobante_nro+' <strong>Código:</strong> '+numero.barcode+' <strong>ID:</strong> '+numero.id+'<br/>');
                   //alert(codigos_iguales+ '<strong>C.I:</strong> '+numero.comprobante_nro+' <strong>Código:</strong> '+numero.barcode+' <strong>ID:</strong> '+numero.id+'<br/>');
                }
            });
            //alert(codigos_iguales);
            if (codigos_iguales.length > 1){
                 bootbox.alert({ 
                    size: "small",
                    title: "Existen Codigos Iguales",
                    message: codigos_iguales, 
                    callback: function(){ setTimeout("$('#codigo_barra').val('');", 0);setTimeout("$('#codigo_barra').focus();", 0); }
                  })
                  codigos_iguales.length = 0;                  
            }else{
                $('#codigo_barra_id').val(0);
                $('#codigo_barra').val('');
                if (item.value) {
                    $('#codigo_barra_id').val(item.value);
                    codigos_iguales.length = 0;
                }
            }
        }
    });
//    $('#codigo_barra').blur(function() {
//        var item = $('#codigo_barra').val('');
//            if (item.value) {
//                $('#codigo_barra_id').val(parseInt(item.value));
//                //Traigo la cantidad de items que tiene este cod de barras si es simple o normal
//                $.ajax({
//                    type: "POST",
//                    url: '<?php echo base_url()."ajax/Piezas/traerCantidadPiezas" ?>',
//                    data: 'codigo_barra='+$('#codigo_barra_id').val(),
//                    cache: false,
//                    datatype:'json',
//                    beforeSend: function() {
//                        //$('#errores').html('<h3><i class="fa fa-spinner fa-spin"></i></h3>');
//                        //$('.btn').addClass('disabled');
//                    },
//                    success: function(respuesta){
//                       var p = $.parseJSON(respuesta);
//                            if (p !== null){ 
//                            $('#cantidad_original').val(p.cantidad);
//                            $('#cantidad').val(p.cantidad);
//                            $('#tipo_id').val(p.tipo_id);
//                        }
//                        //window.location.reload();
//                    }
//                });
//            }
////        }
//    });
//};

var typeahead_reset = function(){
//    $('#codigo_barra').typeahead('destroy');
//    typeahead_init();
};

$(document).ready(function() {

   // typeahead_init();
    
//    $('#codigo_barra').change(function(event) {
//        if ($('#codigo_barra').val() == 'Result not Found' || $('#codigo_barra').val() == '' || $('#codigo_barra_id').val() == 0) {
//            $('#codigo_barra').val('').focus();
//            $('#codigo_barra_id').val(0);
//        }
//    });
    
    $(function(){
      $("#codigo_barra").keyup(function (e) {
        if (e.which == 13) {
         $('.agregar-grilla').click();
        }
      });
    });
    
    $('.agregar-grilla').click(function(event) {
        var id = parseInt($('#codigo_barra_id').val());
//        var pieza_id = 0;
//        $.each(numeros, function (index, value) {
//          if(value['barcode'] == $.trim($('#codigo_barra').val())){
//              id = value['id'];
//              $('#codigo_barra_id').val(id);
//          }else{
//              if ($('#codigo_barra_id').val() == parseInt(0)){
//                    $('#codigo_barra_id').val(parseInt($('#codigo_barra').val()));
//                }
//          }
//      });
        //$('#codigo_barra_id').val(id);
        var tipo_id = $("#tipo_id").val();
        var ids_agregados = ($("#ids_agregados").val()).split(',');
        var no_existe = true;
        for (i=0; i<ids_agregados.length; i++){
            if(parseInt(id) === parseInt(ids_agregados[i])){ no_existe = false;}
        }
        
        if (no_existe)
        {
            maintable.row.add([
                $('#codigo_barra_id').val(),
                '<input type="hidden" name="piezas[]" value="'+id+'"><input type="hidden" name="estado['+id+']" value="'+$('#estado_id').val()+'">'+piezas[id].descripcion,
                $("#estado_id option[value='"+$('#estado_id').val()+"']").text(),
                '<a href="javascript:;" onclick="eliminarFila(this,'+id+')" class="btn btn-danger"><i class="fa fa-trash-o"></i></a>'
            ] ).draw( false );

            barcodear('#results tbody tr td:first-child');
            //Agrego al array de ids_agregados el id para no volver a seleccionarlo
                if ($('#ids_agregados').val() === '') $('#ids_agregados').val(id);
                else $('#ids_agregados').val($('#ids_agregados').val()+','+id)
                $('#codigo_barra_id').val(0);
                $('#codigo_barra').val('');
                $('#cantidad').val('');
                $('#cantidad_original').val('');
                //typeahead_reset();
                //Quitar el numero agregado
                for(var i=numeros.length-1; i>=0; i--)
                {
                  if(numeros[i]['id'] == id)
                  {
                    //numeros_agregados[numeros_agregados.length] = numeros[i];
                    numeros.splice(i,1);
                  }
                }
        }else{
            var message = $("<label id='novedad-normal-message' style='color:red;margin-left:10px;font-weight:normal;line-height:.8em;font-size:10px'>La pieza ya se encuentra agregada</label>");
                    $('input#codigo_barra').after(message);
                    $( "#codigo_barra" ).focus();
                    $( "#codigo_barra" ).val('');
                    $( "#novedad-normal-message" ).show();
                    $( "#novedad-normal-message" ).delay(1000).fadeOut('slow');
        }
    });

    $('.btn-submit').click(function(event) {
        $('#cargados').val($('#ids_agregados').val());
        if(maintable.rows()[0].length > 0)
        {
            $('.btn').addClass('disabled');
            $('#form-comprobante').submit();
        }
        else
        {
            $('#errores').html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Error</strong> Debe agregar por lo menos una pieza.</div>');
        }
    });
});

function eliminarFila(btn, id)
{
    maintable.row($(btn).parents('tr')).remove().draw();

    var ids_agregados = ($("#ids_agregados").val()).split(',');
    var ids_agregados_nuevos = '';
    for (i=0 ; i<ids_agregados.length; i++){
        if (parseInt(id) !== parseInt(ids_agregados[i])){
            if(ids_agregados_nuevos === '') ids_agregados_nuevos = parseInt(ids_agregados[i]);
            else ids_agregados_nuevos = ids_agregados_nuevos + ','+parseInt(ids_agregados[i]);
        }
    }
    $("#ids_agregados").val(ids_agregados_nuevos);

    typeahead_reset();
}