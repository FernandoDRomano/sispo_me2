$(document).ready(function() {
    
    $('#submit').attr('disabled',true);
    
    $('#servicio_id').change(function(event) {
        var id = $(this).val();
        var cantidad = $('#cantidad_servicios_'+id).val();
        if(id && parseInt(cantidad))
        {
            $('#servicio_cantidad_disponible').val(cantidad);
            $('#tipo_id').attr("disabled",false);
            if ($('option:selected').attr('servicio_id') === '2'){//Servicio de paqueteria
                $('#tipo_id').val(2);
                $('#tipo_id').change();
                $('#tipo_id').attr("disabled",true);
            }
        }
        else
        {
            $('#tipo_id').attr("disabled",true);
            $('#piezas-normales, #piezas-simples, #piezas-all').hide();
            $('a.agregar-grilla').attr("disabled",true);
        }
        //Traigo los links para la impresion si es que los tuviera
        $.getJSON('<?=base_url()?>' + 'ajax/piezas/getLinksImpresionAcusesEtiquetas/' + $('#comprobante_id').val()+'/'+$('#servicio_id').val(), function(data) {
            //$("#estado_id").find("option").remove();
            var links_acuses = 'Acuses: ';
            var links_etiquetas = 'Etiquetas: ';
            if(!data.status){
                var i = 0;
                //var indices = cargados_aux.split(',');
                $.each(data['links'], function(key, val) {
                    links_acuses = links_acuses + '<a href="javascript:imprimir_acuse('+val+');" >Libro ('+i+')</a>  ';
                    links_etiquetas = links_etiquetas + '<a href="javascript:imprimir_etiqueta('+val+');" >Libro ('+i+')</a>  ';
                    i++;
                });
            }

            $("#div_acuses").append(links_acuses);
            $("#div_etiquetas").append(links_etiquetas);
            //$("#estado_id").trigger("liszt:updated");
            //$('#estado_id').removeClass('loading-ajax-select');
        });
    });

    $('#grupo_estado_id').change(function(event) {
        $('#estado_id').addClass('loading-ajax-select');
        $.getJSON('<?=base_url()?>' + 'ajax/piezas/estados/' + $('#grupo_estado_id').val(), function(data) {
            $("#estado_id").find("option").remove();
            var options = '';

            if(!data.status){
                options = options + "<option value=''>Seleccione</option>";
                $.each(data, function(key, val) {
                    options = options + "<option value='"+val.id+"'>"+ val.nombre +"</option>";
                });
            }else{
                options = options + "<option value=''>Sin resultados</option>";
            }

            $("#estado_id").append(options);
            $("#estado_id").trigger("liszt:updated");
            $('#estado_id').removeClass('loading-ajax-select');
        });
    });

    $('#paqueteria').hide();

    $('#tipo_id').change(function(event) {
        var valor = $(this).val();
        if (valor == 1) {
            $('#piezas-normales').hide();
            $('#piezas-simples, #piezas-all').show();
            $('#cantidad').attr('max',$('#servicio_cantidad_disponible').val()).val(1);
            $('a.agregar-grilla').attr("disabled",false);
        }else if(valor == 2) {
            $('#piezas-normales, #piezas-all').show();
            $('#piezas-simples').hide();
            $('#cantidad').val(1);
            $('a.agregar-grilla').attr("disabled",false);
//            alert($('option:selected').attr('servicio_id'));
            if ($('option:selected').attr('servicio_id') === '2'){//Servicio de paqueteria
                $('#paqueteria').show();
            }
        }else{
            $('#piezas-normales, #piezas-simple, #piezas-all').hide();
            $('#cantidad').val(1);
            $('a.agregar-grilla').attr("disabled",true);
        }
    });

    $(".form-pieza").validate({
        rules: {
            servicio_id: {required: true},
            tipo_id: {required: true},
        },
        messages: {
            servicio_id: "Campo requerido.",
            tipo_id: "Campo requerido.",
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

    $('.agregar-grilla').click(function(event) {
        if($('.form-pieza').valid())
        {
            $(".dataTables_empty").hide();
            var servicio_id = $('#servicio_id').val();
            var cantidad_actual = $('#cantidad_servicios_' + servicio_id).val();
            var acuse = $('#servicio_acuse_' + servicio_id).val();
            var acuse_str = parseInt(acuse)===0?"Simple":"Normal";

            if (parseInt(acuse)!=0 && $('#barcode_externo').val() === ""){
                 alert("Las piezas NORMALES deben tener CODIGO de BARRAS");
                 return;
            }

            if (cantidad_actual > 0)
            {
                $('#errores').html('');
                var contador = $('#contador').val();
                var cantidad = $('#cantidad').val();
                var nuevo_contador = parseInt(contador) + 1;
                var cantidad_nueva = parseInt(cantidad_actual) - parseInt(cantidad);
                if (cantidad_nueva >= 0)
                {
                    $('#cantidad_servicios_' + servicio_id).val(cantidad_nueva);
                    $('#servicio_cantidad_disponible').val(cantidad_nueva);
                    $('#contador').val(nuevo_contador);
                    var tipo_id = $('#tipo_id').val();
                    var inputs = '<div id="grilla-servicio-div-'+nuevo_contador+'">'+
                        '<input type="hidden" name="fila[]" value="'+nuevo_contador+'">'+
                        '<input type="hidden" id="servicio_id_'+nuevo_contador+'" name="servicio_id['+nuevo_contador+']" value="'+servicio_id+'">'+
                        '<input type="hidden" id="cantidad_'+nuevo_contador+'" name="cantidad['+nuevo_contador+']" value="'+$('#cantidad').val()+'">'+
                        '<input type="hidden" name="tipo_id['+nuevo_contador+']" value="'+tipo_id+'">'+
                        '<input type="hidden" name="barcode_externo['+nuevo_contador+']" value="'+$('#barcode_externo').val()+'">'+
                        '<input type="hidden" name="destinatario['+nuevo_contador+']" value="'+$('#destinatario').val()+'">'+
                        '<input type="hidden" name="domicilio['+nuevo_contador+']" value="'+$('#domicilio').val()+'">'+
                        '<input type="hidden" name="codigo_postal['+nuevo_contador+']" value="'+$('#codigo_postal').val()+'">'+
                        '<input type="hidden" name="localidad['+nuevo_contador+']" value="'+$('#localidad').val()+'">'+
                        '<input type="hidden" name="datos_varios['+nuevo_contador+']" value="'+$('#datos_varios').val()+'">'+
                        '<input type="hidden" name="datos_varios_1['+nuevo_contador+']" value="'+$('#datos_varios_1').val()+'">'+
                        '<input type="hidden" name="datos_varios_2['+nuevo_contador+']" value="'+$('#datos_varios_2').val()+'">'+
                        '<input type="hidden" name="datos_varios_3['+nuevo_contador+']" value="'+$('#datos_varios_3').val()+'">'+
                        '<input type="hidden" name="dimensiones['+nuevo_contador+']" value="'+$('#dimensiones').val()+'">'+
                        '<input type="hidden" name="peso['+nuevo_contador+']" value="'+$('#peso').val()+'">'+
                        '<input type="hidden" name="bulto['+nuevo_contador+']" value="'+$('#bulto').val()+'">'+
                        '<input type="hidden" name="servicio_base_id['+nuevo_contador+']" value="'+$('option:selected').attr('servicio_id')+'">'+
                        '<input type="hidden" name="descripcion_paquete['+nuevo_contador+']" value="'+$('#descripcion_paquete').val()+'">'+
                        '<input type="hidden" name="email_dest['+nuevo_contador+']" value="'+$('#email_dest').val()+'">'+
                        '<input type="hidden" name="celular_dest['+nuevo_contador+']" value="'+$('#celular_dest').val()+'">'+
                        '</div>';
                    var html = '<tr id="grilla-servicio-'+nuevo_contador+'" class="nueva">'+
                        '<td></td>'+
                        '<td class="barcodear">'+$('#barcode_externo').val()+'</td>'+
                        '<td><b>Servicio: </b> '+$('#servicio_id :selected').text()+'<br>'+
                        '<b>Tipo: </b> '+acuse_str+' - '+$('#tipo_id :selected').text()+'<br>';
                    if(parseInt(acuse) === 0)
                        html += '<b>Cantidad de piezas: </b> '+$('#cantidad').val();
                        html += '</td><td>';

                    if(parseInt(acuse) === 1 || (parseInt(acuse) === 0 && $('#tipo_id').val() === 2)) //Que sea simple pero con datos
                    {
                        html += '<b>Destinatario: </b> '+$('#destinatario').val()+'<br>'+
                            '<b>Domicilio: </b> '+$('#domicilio').val()+'<br>'+
                            '<b>Codigo Postal: </b> '+$('#codigo_postal').val()+'<br>'+
                            '<b>Localidad: </b> '+$('#localidad').val()+'<br>';
                    }
                    else
                    {
                        html += '<b>Datos varios: </b> '+$('#datos_varios').val();
                    }
                    if ($('option:selected').attr('servicio_id') === '2'){//Servicio de paqueteria
                        html += '<b style="font-weight: bold;text-decoration: underline ">Paqueteria </b> <br>'+
                            '<b>Desc.: </b> '+$('#descripcion_paquete').val()+'<br>'+
                            '<b>Dimensiones: </b> '+$('#dimensiones').val()+' (cm)<br>'+
                            '<b>Peso: </b> '+$('#peso').val()+' (Kg)<br>'+
                            '<b>Bulto: </b> '+$('#bulto').val()+' (Unidades)<br>';
                    }
                    html += '</td>'+
                        '<td><a href="javascript:;" onclick="eliminarFila(\''+nuevo_contador+'\')" class="btn btn-danger eliminar-fila-btn"><i class="fa fa-trash-o"></i></a></td>'+
                        '</tr>';
                        
                    $('#body-grilla').prepend(html);
                    $('#body-grilla').prepend(inputs);
                    
                    barcodear();

                    $('#destinatario').val('');
                    $('#domicilio').val('');
                    $('#codigo_postal').val('');
                    $('#localidad').val('');
                    $('#barcode_externo').val('');
                    $('#datos_varios').val('');
                    $('#datos_varios_1').val('');
                    $('#datos_varios_2').val('');
                    $('#datos_varios_3').val('');
                    $('#email_dest').val('');
                    $('#celular_dest').val('');
                    $('#cantidad').val(1);

                    $('.btn-submit').attr("disabled",false);
                }
                else
                {
                    $('#errores').html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Error</strong> No dispone la cantidad ingresada para este servicio.</div>');
                }
            }
            else
            {
                $('#errores').html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Error</strong> El servicio seleccionado ya esta completo.</div>');
            }
        }
    });
/*
    $('.btn-submit').click(function(event) {
        $.ajax({
            type: "POST",
            url: '<?=base_url()?>piezas/comprobantes_ingresos/piezas_add',
            data: $('.form-pieza').serialize(),
            cache: false,
            beforeSend: function() {
                $('#errores').html('<h3><i class="fa fa-spinner fa-spin"></i></h3>');
                $('.btn').addClass('disabled');
            },
            success: function(respuesta){
                window.location.reload();
            }
        });
    });
*/
});

function eliminarFila(id){//id = contador
    var servicio_id = $('#servicio_id_'+id).val();
    var servicio = $('#cantidad_servicios_'+servicio_id).val();
    var cantidad = $('#cantidad_'+id).val();
    var total_s = parseInt(servicio) + parseInt(cantidad);
    $('#cantidad_servicios_'+servicio_id).val(total_s);
    $('#servicio_cantidad_disponible').val(0);
    $('#servicio_id').prop('selectedIndex',0);

    $('#grilla-servicio-'+id).remove();
    $('#grilla-servicio-div-'+id).remove();
    if(!$('#body-grilla tr.nueva').length)
        $('.btn-submit').attr("disabled",true);
}

  function archivar_comprobante(id)
  {
    $.ajax({
      type: "POST",
      url: '<?=base_url("piezas/Comprobantes_ingresos/archivar")?>',
      data: {
        comprobante_id : id,
        estado : 1
      },
      datatype: "json",
      success: function(){window.location.reload();//function(response){
//          var json = $.parseJSON(response); // create an object with the key of the array
//            if (json.success === true)
//                $("#div_resultado").load(location.href + " #div_resultado"); //Refresca el div
      }
    });
  }
    $('#cargando').hide();
    $('#cargado').hide();
   // $('#submit').addAttr('disabled','-1');
    $('.agregar-grilla-masivo').click(function(event) {
        $('#cargando').show();
        if($('.form-pieza-masivo').valid())
        {
            var file_data = $("#piezas_csv").prop("files")[0];   
            var form_data = new FormData();                  
            form_data.append("file", file_data);
            
            var other_data = $('.form-pieza-masivo').serializeArray();
            $.each(other_data,function(key,input){
                form_data.append(input.name,input.value);
            });
//            form_data.append("fields", other_data);
            
            $.ajax({
                        url: '<?php echo base_url()."ajax/Piezas/analizarXls" ?>',
                        dataType: 'json',
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: form_data,                
                        type: 'post',
                        success: function(data){//alert(JSON.stringify(data));
                            if (data.error){
                                $('#errores').html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Error</strong>'+data.error+'</div>');
                                $('#cargando').hide();
                                $('#cargado').hide();
                            }else{
                                $('#filename').val(data['servicio']['filename']);
                                $('#cargado').show();
                                $('#submit').removeAttr('disabled');
                                $('.agregar-grilla-masivo').attr('disabled',true);
                                var contador = 0;
                                var cantidad_piezas = data['servicio']['cantidad_piezas'];
                                $('#cantidad_piezas').val(cantidad_piezas);
                                var tabla = "<br/><table  id='results' class='table table-striped table-hover table-condensed bootstrap-datatable table-bordered'>"+
                                                "<tr>"+
                                                    "<td>Servicio</td>"+
                                                    "<td>Cantidad de Piezas</td>"+
                                                "</tr>"+
                                                "<tr>"+
                                                    "<td>"+data['servicio']['nombre']+"</td>"+
                                                    "<td>"+data['servicio']['cantidad_piezas']+"</td>"+
                                                "</tr>"+
                                            "</tabla>";
                                $("#tabla").append(tabla);
//                                  
                            }
                        }
             });
        }
    });
    
    function borrar(indice) {
        $('#grilla-servicio-'+indice).remove();
        var contador = $('#contador').val();
        contador--;
        $('#contador').val(contador);
    };
    
    $('.imprimir-acuse').click(function(event) {
        $('#impresion_tipo').val('acuse');
        $('#pdf_comprobante_ingreso_id').val($('#comprobante_id').val());
        $('#pdf_servicio_id').val($('#servicio_id').val());
        $('#exportarAPdf').attr('target', '_blank').submit();
    });
    
    $('.imprimir-etiquetas').click(function(event) {
        $('#impresion_tipo').val('etiquetas');
        $('#pdf_comprobante_ingreso_id').val($('#comprobante_id').val());
        $('#pdf_servicio_id').val( $('#servicio_id').val());
        $('#exportarAPdf').attr('target', '_blank').submit();
    });
    
    function imprimir_acuse(pdf_limite_inferior){
        $('#impresion_tipo').val('acuse');
        $('#pdf_comprobante_ingreso_id').val($('#comprobante_id').val());
        $('#pdf_servicio_id').val($('#servicio_id').val());
        $('#pdf_limite_inferior').val(pdf_limite_inferior);
        $('#exportarAPdf').attr('target', '_blank').submit();
    }
  
    function imprimir_etiqueta(pdf_limite_inferior){
          $('#impresion_tipo').val('etiqueta');
          $('#pdf_comprobante_ingreso_id').val($('#comprobante_id').val());
          $('#pdf_servicio_id').val($('#servicio_id').val());
          $('#pdf_limite_inferior').val(pdf_limite_inferior);
          $('#exportarAPdf').attr('target', '_blank').submit();
      }