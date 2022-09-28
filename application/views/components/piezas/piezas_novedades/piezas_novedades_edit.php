<div class="col-xs-12">
    <div class="ibox-content">
        <?= form_open(current_url(), array('id' => "form-novedades-normales")); ?>
        <div id="errores"></div>
        <!--<input type="hidden" id ="pieza_id" name="pieza_id" value="<?php echo $data->pìeza_id?>"/>-->
        <?php if ($this->session->flashdata('registroOk')): ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        La novedad de la pieza fue actualizada exitosamente.
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <div class="panel blank-panel">
            <div class="panel-heading">
                <div class="panel-options">
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#piezas_normales_novedades">Editar Novedades Piezas Normale</a></li>
                     <!--   <li><a href="<?= base_url('piezas/novedades/index_edit_simples') ?>">Editar Novedades Piezas Simples</a></li>-->
                    </ul>
                </div>
            </div>

            <div>
                <hr>
                <h3>Editar piezas normales</h3>
                <hr>
                <div class="row">
                    <div class="col-xs-3">
                        <div class="form-group">
                            <label for="fecha_recepcion">Codigo barra</label>
                            <input  id="codigo_barra" name="codigo_barra" type="text" class="form-control" placeholder="Codigo barra" autocomplete="off" />
                        </div>
                        <div>
                            <label class="radio-inline"><input type="radio" name="buscar_por" value="1">Pieza ID</label>
                            <label class="radio-inline"><input type="radio" name="buscar_por" value="2" checked="checked">Código Externo</label>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="control-group text-right">
                            <div class="controls">
                                <a class="btn btn-primary agregar-grilla" href="javascript:;"><i class="fa fa-arrow-down"></i> Agregar</a>
                                <!--<a class="btn btn-success btn-submit" href="javascript:;"><i class='fa fa-floppy-o'></i> Guardar</a>-->
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
            </div>
            <table  id="results" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
                <thead>
                    <tr>
                        <th>Codigo Interno</th>
                        <th>Descripcion</th>
                        <th>Estado</th>
                        <th class="col-xs-1 no-sort">&nbsp;</th>
                    </tr>
                </thead>
                <tbody id="body-grilla">
                </tbody>
            </table>
        </div>
        <?= form_close(); ?>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $('.dataTables_empty').hide();
    $('#results_length').hide();
    $('#results_filter').hide();
    $('#results_info').hide();
 });

//    if ($("#pieza_id").val() !== null){
//        alert($("#pieza_id").val());
//        $('#buscar_por').val("1");alert("el valor es "+$('#buscar_por').val());
//        $('#codigo_barra').val($("#pieza_id").val());
//        alert("el valor es "+$('#codigo_barra').val());
//        $('.agregar-grilla').click();
//    }
    
    codigos_iguales = [];
    $("#codigo_barra").keyup(function(e) {
        var codigo_barra = '"' + $("#codigo_barra").val() + '"';
        var pieza_id = 0;
        var buscar_por = $('input:radio[name=buscar_por]:checked').val();
        if (e.which == 13) {
            if (buscar_por === 2){ //Solo busca piezas iguales cuando se coloca un CODIGO EXTERNO de lo contrario solo debe encontrar 1 pieza
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url() . "ajax/piezas/traerPiezasIgualesParaNovedad" ?>',
                    data: 'codigo_barra=' + $('#codigo_barra').val(),
                    cache: false,
                    datatype: 'json',
                    beforeSend: function() {
                        //$('#errores').html('<h3><i class="fa fa-spinner fa-spin"></i></h3>');
                        //$('.btn').addClass('disabled');
                    },
                    success: function(respuesta) {
                        var array_codigos = [];
                        var codigos_iguales = $.parseJSON(respuesta);
                        if (!codigos_iguales.status) {
                            $.each(codigos_iguales.grilla, function(key, value) {
                                array_codigos.push('<strong>C.I:</strong> ' + value.numero + ' <strong>Código:</strong> ' + value.barcode_externo + ' <strong>ID:</strong> ' + value.pieza_id + '<br/>');
                                //pieza_id = value.pieza_id;
                            })
                        } else {
                            var message = $("<label id='pieza-normal-message' style='color:red;margin-left:10px;font-weight:normal;line-height:.8em;font-size:10px'>" + codigos_iguales.status + "</label>");
                            $('input#codigo_barra').after(message);
                            $("#codigo_barra").focus();
                            $("#codigo_barra").val('');
                            $("#pieza-normal-message").show();
                            $("#pieza-normal-message").delay(1500).fadeOut('slow');
                            $('#codigo_barra_id').val(0);
                            $('#codigo_barra').val('');
                            $('#codigo_barra').focus();
                        }

                        if (array_codigos.length == 1) {
                            pieza_id = codigos_iguales.grilla[0].pieza_id;
                        }
                        if (array_codigos.length > 1) {
                            bootbox.alert({
                                size: "small",
                                title: "Existen Codigos Iguales",
                                message: array_codigos,
                                callback: function() {
                                    setTimeout("$('#codigo_barra').focus();$('#codigo_barra').val('');", 0);
                                }
                            })
                            array_codigos.length = 0;
                        } else {
                            if (pieza_id > 0) {
                                $('#codigo_barra').val(pieza_id);
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

    $('.agregar-grilla').click(function(event) {
     var form_data = new FormData();                  
        var other_data = $('#form-novedades-normales').serializeArray();
        $.each(other_data,function(key,input){
            form_data.append(input.name,input.value);
        });
        var buscar_por = $('input:radio[name=buscar_por]:checked').val();
        $.ajax({
            type: "POST",
            url: '<?php echo base_url() . "ajax/novedades/traerNovedadPiezaNormal" ?>',
            data: form_data,
            cache: false,
            processData: false,
            contentType: false,                
            datatype:'json',
            beforeSend: function() {
                //$('#errores').html('<h3><i class="fa fa-spinner fa-spin"></i></h3>');
                //$('.btn').addClass('disabled');
            },
            success: function(respuesta) {
                var pieza = $.parseJSON(respuesta);
                if (!pieza.status) {
                    $('#body-grilla').html('');
                    var linea_modificacion = '<a data-toggle="modal" href="<?php echo base_url().'piezas/novedades/editNovedadesPiezasNormales/'?>'+pieza.novedades[0]['pieza_id']+'" data-target="#myModal" class="btn btn-info"><i class="fa fa-edit"></i></a>';
                    var html = '<tr>' +
                            '<td>' + pieza.novedades[0]['pieza_id'] + '</td>' +
                            '<td>' + pieza.novedades[0]['domicilio'] + ' - ' + pieza.novedades[0]['localidad'] + ' - ' + pieza.novedades[0]['codigo_postal'] + '</td>' +
                            '<td>' + pieza.novedades[0]['estado'] + '</td>' +
                            '<td>' + linea_modificacion + '</td>' +
                            '</tr>';

                    $('#body-grilla').append(html);
                    $('#codigo_barra').val('');
                } else {
                    var message = $("<label id='pieza-normal-message' style='color:red;margin-left:10px;font-weight:normal;line-height:.8em;font-size:10px'>" + pieza.status + "</label>");
                    $('input#codigo_barra').after(message);
                    $("#codigo_barra").focus();
                    $("#codigo_barra").val('');
                    $("#pieza-normal-message").show();
                    $("#pieza-normal-message").delay(1500).fadeOut('slow');
                    $('#codigo_barra_id').val(0);
                    $('#codigo_barra').val('');
                    $('#codigo_barra').focus();
                }
            }
        });
    });
    
   
</script>