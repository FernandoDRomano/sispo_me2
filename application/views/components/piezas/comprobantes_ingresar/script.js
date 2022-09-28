$(document).ready(function() {
    var clientes = <?=json_encode($clientes)?>;

    $('#cliente').typeahead({
        source: clientes,
        onSelect: function(item) {
            $('#cliente_id').val(0);
            if (item.value)
            {
                $('#cliente_id').val(item.value);
                if (item.value > 0){
                    $("#servicio").empty();
                    $("#servicio").append('<option value="-1">Seleccione...</option>');
                    $.getJSON('<?php echo base_url()."ajax/precios/precios_especiales_x_cliente" ?>/'+$("#cliente_id").val(),function(data){
                        $.each(data, function(id,value){
                            if (value.servicio_id != undefined){
                                $("#servicio").append('<option value="'+value.servicio_id+'">'+value.nombre+'</option>');
                            }
                        });
                    });
                }
            }
        }
    });

    $('#cliente').change(function(event) {
        if($('#cliente').val() == 'Result not Found' || $('#cliente').val() == '' || $('#cliente_id').val() == 0)
        {
            $('#cliente').val('').focus();
            $('#cliente_id').val(0);
        }
    });

//    var servicios = [<?php foreach ($servicios as $f) echo "{ id: ".$f->id.", name: '".$f->nombre."'}, "?>];
//
//    $('#servicio').typeahead({
//        source: servicios,
//        onSelect: function(item) {
//            $('#servicio_id').val(0);
//            if (item.value)
//            {
//                $('#servicio_id').val(item.value);
//            }
//        }
//    });

    //getDepartamentos();

    $('#cliente').change(function(event) {
        getDepartamentos();
    });

    $('.agregar-grilla').click(function(event) {
        var agregar = true;
        var cantidad_servicios = $('#cantidad_servicios').val();
        var cantidad_servicios_c = $('#cantidad_servicios_c').val();
        if($('#servicio').val() > 0 )
        {   if($('#servicio').val() === "2" ) { //2= servicio de paqueteria
                if ( $('tr').length === 1){ //==1 que solo esta el encabezado de la tabla, si tiene alguna fila no permite cargar el servicio de paqueteria
                    $('.agregar-grilla').attr("disabled",true);
                    agregar = true;
                }else{
                    alert('El servicio PAQUETERIA no admite la carga de otros servicios en el CI');
                    agregar = false;
                }
            }
            if (agregar){
                $('#errorServicios').html('');
                if ($('#departamento_id').val() <= 0 ){ $('#errorServicios').html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Error</strong> El cliente debe pertenecer a algún departamento.</div>');return;}
                var servicio = $('#servicio').val();
                var servicio_txt = $( "#servicio option:selected" ).text();
                var cantidad = $('#cantidad').val();
                var remito = $('#remito').val();
                var total = parseInt(cantidad_servicios) + parseInt(1);
                var total_c = parseInt(cantidad_servicios_c) + parseInt(1);
                var inputs = '<input type="hidden" name="servicio_'+total+'" id="servicio_'+total+'" value="'+servicio+'"><input type="hidden" name="cantidad_'+total+'" id="cantidad_'+total+'" value="'+cantidad+'"><input type="hidden" name="remito_'+total+'" id="remito_'+total+'" value="'+remito+'">';
                var html = '<tr id="grilla-servicio-'+total+'" valor="servicio_'+total+'"><td>'+servicio_txt+inputs+'</td><td>'+cantidad+'</td><td>'+remito+'</td><td><a href="javascript:;" onclick="eliminarFila(\'grilla-servicio-'+total+'\')" class="btn btn-danger"><i class="fa fa-trash-o"></i></a></td></tr>';

                if ($("#grilla-servicio-"+parseInt(cantidad_servicios)).length >  0){
                    $("#grilla-servicio-"+parseInt(cantidad_servicios)).before(html);
                }else{
                    $('#body-grilla').append(html);
                }
                $('#cantidad_servicios').val(total);
                $('#cantidad_servicios_c').val(total_c);
            }
                
        }
        else
        {
            $('#errorServicios').html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Error</strong> Debe agregar por lo menos un servicio.</div>');
        }

        $('#cantidad').val(1);
        $('#remito').val('');
        $('#servicio').val('');
        //$('#servicio').val(0);
    });

    $("#form-comprobante").validate({
        rules: {
            numero: {required: true},
            empresa_id: {required: true},
            sucursal_id: {required: true},
            cliente: {required: true},
            fecha_pedido: {required: true}
        },
        messages: {
            numero: "Campo requerido.",
            empresa_id: "Campo requerido.",
            sucursal_id: "Campo requerido.",
            cliente: "Campo requerido.",
            fecha_pedido: "Campo requerido."
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

    $('.btn-submit').click(function(event) {
        if ($('#cantidad_servicios_c').val() > 0)
        {
            if($('#form-comprobante').valid())
            {
                $('#form-comprobante').submit();
            }
        }
        else
        {
            $('#errores').html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Error</strong> Debe agregar por lo menos un servicio.</div>');
        }
    });
});

function getDepartamentos()
{
    if(!$('#cliente_id').val() || $('#cliente_id').val() == '0')
        return;

    var url = '<?=base_url()?>' + 'ajax/clientes/departamentos/' + $('#cliente_id').val();
    $.getJSON(url, function(data) {
        //$("#departamento_id").find("option").remove();
        var options = '';

        if(!data.status)
        {
            $.each(data, function(key, val) {
                options += "<option value='"+val.id+"'>"+ val.nombre +"</option>";
            });
        }
        else
        {
            options += "<option value='0' selected>Sin resultados</option>";
        }

        $("#departamento_id").html(options);
        //$("#departamento_id").trigger("liszt:updated");
    });
}

function eliminarFila(id)
{
    var servicio_id = $('#'+id).attr('valor');
    
    if($('#'+servicio_id).val() === "2" ) {
        $('.agregar-grilla').attr("disabled",false);
        $('#cantidad').val(0);
        $('#cantidad').attr("disabled",false);
    }
    $('#'+id).remove();
    var cantidad_servicios_c = $('#cantidad_servicios_c').val();
    var total_c = parseInt(cantidad_servicios_c) - parseInt(1);
    $('#cantidad_servicios_c').val(total_c);
    
    
}