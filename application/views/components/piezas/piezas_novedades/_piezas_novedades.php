<div class="col-xs-12">
    <div class="ibox-content">
        <?= form_open(current_url(), array('id' => "form-novedades-normales")); ?>
        <div id="errores"></div>
        <?php if ($this->session->flashdata('registro')): ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        Las piezas fueron actualizadas exitosamente.
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <div class="panel blank-panel">
            <div class="panel-heading">
                <div class="panel-options">
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#piezas_normales_novedades">Novedades Piezas Normales</a></li>
                        <li><a href="<?= base_url('piezas/novedades/piezas_simples_novedades') ?>">Novedades Piezas Simples</a></li>
                    </ul>
                </div>
            </div>

            <div>
                <hr>
                <h3>Agregar piezas normales</h3>
                <hr>
                <div class="row">
                    <?php /*
                      <div class="col-xs-4">
                      <div class="form-group">
                      <label for="fecha_recepcion">Fecha</label>
                      <input  id="fecha_recepcion" name="fecha_recepcion" type="text" class="form-control datepicker" placeholder="Fecha Salida" value="<?=date('d-m-Y')?>" required autocomplete="off" />
                      </div>
                      </div>
                     */ ?>
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label for="estado_id">Estado</label>
                            <select id="estado_id" name="estado_id" class="form-control"  required >
                                <optgroup label="Estados Iniciales">
                                    <?php foreach ($estados_iniciales as $estado): ?>
                                        <option value="<?= $estado->id ?>"><?= $estado->nombre ?></option>
                                    <?php endforeach; ?>
                                </optgroup>
                                <optgroup label="Estados de Rendiciones">
                                    <?php foreach ($estados as $estado): ?>
                                        <option value="<?= $estado->id ?>"><?= $estado->nombre ?></option>
                                    <?php endforeach; ?>
                                </optgroup>
                                <optgroup label="Estados Organizativos">
                                    <?php foreach ($estados_organizativos as $estado_organizativo): ?>
                                        <option value="<?= $estado_organizativo->id ?>"><?= $estado_organizativo->nombre ?></option>
                                    <?php endforeach; ?>
                                </optgroup>
                                <optgroup label="Estados Despachos">
                                    <?php foreach ($estados_despachos as $estado_despacho): ?>
                                        <option value="<?= $estado_despacho->id ?>"><?= $estado_despacho->nombre ?></option>
                                    <?php endforeach; ?>
                                </optgroup>
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label for="fecha_recepcion">Codigo barra</label>
                            <input  id="codigo_barra" name="codigo_barra" type="text" class="form-control" placeholder="Codigo barra" autocomplete="off" />
                            <input  id="codigo_barra_id" name="piezas_id" type="hidden" value="0" />
                            <input  id="piezas_ids" name="piezas_id" type="hidden" value="" />
                            <input  id="cargados" name="cargados" type="hidden" value="" />
                            <input  id="ids_agregados" name="ids_agregados" type="hidden" value="" />
                        </div>
                        <div>
                            <label class="radio-inline"><input type="radio" name="buscar_por" value="1">Pieza ID</label>
                            <label class="radio-inline"><input type="radio" name="buscar_por" value="2" checked="checked">Código Externo</label>
							<label ><input  id="Anteponer" name="Anteponer" type="text" />Anteponer A Barcode</label>
                        </div>
                    </div>
                    <div class="col-xs-3" style="display:none">
                        <div class="form-group">
                            <label for="cantidad">Cantidad</label>
                            <input type="number" class="form-control" id="cantidad" name="cantidad" value=""/>
                            <input  id="cantidad_original" type="hidden" value="" />
                            <input  id="tipo_id" type="hidden" value="" />
                        </div>
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
<?php //include 'script.js';  ?>
$(document).ready(function() {
    $('.dataTables_empty').hide();
    $('#results_length').hide();
    $('#results_filter').hide();
    $('#results_info').hide();
 });
    codigos_iguales = [];
    $("#codigo_barra").keyup(function(e) {
        var codigo_barra = '"' + $("#codigo_barra").val() + '"';
        var pieza_id = 0;
        var buscar_por = $('input:radio[name=buscar_por]:checked').val();
		var data_codigo_barra = $('#codigo_barra').val();
		if($("#Anteponer").val() != ''){
			codigo_barra = '"' + $("#Anteponer").val() + $("#codigo_barra").val() + '"';
			data_codigo_barra = $("#Anteponer").val() + $("#codigo_barra").val();
			//alert(data_codigo_barra);
		}
		
        if (e.which == 13) {
            if (buscar_por === 2){ //Solo busca piezas iguales cuando se coloca un CODIGO EXTERNO de lo contrario solo debe encontrar 1 pieza
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url() . "ajax/piezas/traerPiezasIgualesParaNovedad" ?>',
                    data: 'codigo_barra=' + data_codigo_barra,
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
                                array_codigos.push('<strong>C.I:</strong> ' + value.numero + ' <strong>C車digo:</strong> ' + value.barcode_externo + ' <strong>ID:</strong> ' + value.pieza_id + '<br/>');
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
     
		if($("#Anteponer").val() != ''){
			$("#codigo_barra").val($("#Anteponer").val() + "" + $("#codigo_barra").val()) ;
			console.log($("#codigo_barra"));
		}
        var other_data = $('#form-novedades-normales').serializeArray();
		
        $.each(other_data,function(key,input){
            form_data.append(input.name,input.value);
        });
        
        var buscar_por = $('input:radio[name=buscar_por]:checked').val();
       
        
        $.ajax({
            type: "POST",
            url: '<?php echo base_url() . "ajax/novedades/grabar_novedad_pieza_normal" ?>',
            data: form_data,//'codigo_barra='+$('#codigo_barra').val()+'&piezas_ids='+$('#piezas_ids').val(),
            cache: false,
            processData: false,
            contentType: false,                
            datatype:'json',
            beforeSend: function() {
                //$('#errores').html('<h3><i class="fa fa-spinner fa-spin"></i></h3>');
                //$('.btn').addClass('disabled');
            },
            success: function(respuesta) {
                //codigo pa dafiti
            /*    data_codigo_barra = 'id='+$('#codigo_barra').val();
                
               $.ajax({
                    type: "GET",
                    url: 'http://boveda.sppflash.com.ar/api/dafiti/envios/',
                    data : data_codigo_barra,
                    cache: false,
                    processData: false,
                //    contentType: "application/json; charset=utf-8",           
                    datatype:'text',
                    
                });
                */
                
                var estado = $('#estado_id').val();
                var pieza = $.parseJSON(respuesta);
                
                if (!pieza.status) {
                    $('#body-grilla').html('');
                    var inputs = '<input type="hidden"  name="piezas[]" value="'+pieza.pieza_id+'-'+estado+'">';
                    var html = '<tr id="tr_'+pieza.pieza_id+'">' +
                            '<td>' + pieza.pieza_id + '</td>' +
                            '<td>' + pieza.domicilio + ' - ' + pieza.localidad + ' - ' + pieza.codigo_postal + '</td>' +
                            '<td>' + $("#estado_id option[value='" + $('#estado_id').val() + "']").text() + '</td>' +
                            '</tr>';
                    if ($('#piezas_ids').val() === '') {
                        $('#piezas_ids').val(pieza.pieza_id);
                    } else {
                        $('#piezas_ids').val($('#piezas_ids').val() + ',' + pieza.pieza_id);
                    }

                    $('#body-grilla').append(html);
                    $('#body-grilla').append(inputs);
                    $('#codigo_barra').val('');
                    barcodear();
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