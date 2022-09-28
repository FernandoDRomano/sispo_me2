<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
    <div id="errores"></div>
    <div class="col-xs-12" style="background: #FFF">
        <div class="ibox-content">
        <?php
        echo form_open(current_url(), array('id'=>"form-recibio"));
        echo form_hidden('enviar_form', '1');
        ?>
        <div class="row">
            <div class="col-xs-6">
                <div class="form-group">
                    <label for="codigo">Código</label>
                    <input class="form-control" type="text" placeholder="id pieza/codigo externo" id="codigo" name="codigo" maxlength="150" autocomplete="off">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-6">
                <div class="form-group">
                    <label for="documento">Documento</label>
                    <input class="form-control" type="text" placeholder="" id="documento" name="documento"  autocomplete="off">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-6">
                <div class="form-group">
                    <label for="recibio">Recibió</label>
                    <input id="recibio" name="recibio" class="form-control" />
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-6">
                <div class="form-group">
                    <label for="vinculo">Vínculo</label>
                    <input required id="vinculo" name="vinculo" class="form-control"/>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="form-group">
                    <label for="datos_varios_2">Datos Varios 2</label>
                    <input id="datos_varios_2" name="datos_varios_2" class="form-control"/>
                </div>
            </div>
        </div>

        <div class="control-group">
            <div class="controls">
            <a class="btn btn-success guardar" href="javascript:;" ><i class="fa fa-arrow-down"></i> Guardar</a>
                <span class="button btn btn-danger" data-dismiss="modal" aria-label="Close"><i class="fa fa-arrow-circle-left"></i> Volver</span>
            </div>
        </div>
        <?php echo form_close(); ?>
        <table class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
                <thead>
                  <tr>
                    <th>C&oacute;digo </th>
                    <th>Documento</th>
                    <th>Recibi&oacute;</th>
                    <th>V&iacute;nculo</th>
                  </tr>
                </thead>
                <tbody id="body-grilla">
                </tbody>
                </table>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {

    $('.guardar').click(function(event) {
        var other_data = $('#form-recibio').serializeArray();
        var form_data = new FormData();
        $.each(other_data,function(key,input){
            form_data.append(input.name,input.value);
        });
	$('#body-grilla').html("");
        $.ajax({
            url: '<?php echo base_url()."ajax/rendiciones/recibio" ?>',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function(data){
                if (data.status !== 'Guardado'){
                    $('#errores').html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Error</strong> '+data.status+'</div>');
                    $('#codigo').val('');
                    $('#codigo').focus();
                }else{
                    $('#errores').html('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong></strong> '+data.status+'</div>');
                    $('#codigo').val('');
                    $('#documento').val('');
                    $('#recibio').val('');
                    $('#vinculo').val('');
                    $('#datos_varios_2').val('');
                    $('#codigo').focus();
                    var html = '<tr>'+
                                                '<td class="">'+data.pieza_session.codigo+'</td>'+
                                                '<td>'+data.pieza_session.documento+'</td>'+
                                                '<td>'+data.pieza_session.recibio+'</td>'+
                                                '<td>'+data.pieza_session.vinculo+'</td>'+
                                            '</tr>';
                                 $('#body-grilla').append(html);  
                }
            }
        });
    });

    $("#form-recibio").validate({
        rules: {
            codigo: {required: true},
            //nombre_servicio: {required: true},
        },
        messages: {
            servicio_id: "Campo requerido.",
            //nombre_servicio: "Campo requerido.",
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
});
</script>