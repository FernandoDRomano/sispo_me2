<div class="col-xs-12">
    <?= form_open(current_url(), array('id' => "form-piezas")); ?>
        <div class="col-xs-6">
            <div class="form-group">
                <label for="fecha_recepcion">Codigo barra</label>
                <input  id="codigo_barra" name="codigo_barra" type="text" class="form-control" placeholder="Codigo barra" autocomplete="off" />
                <input  id="codigo_barra_id" name="piezas_id" type="hidden" value="0" />
            </div>
            <div>
                <label class="radio-inline"><input type="radio" name="buscar_por" value="1">Pieza ID</label>
                <label class="radio-inline"><input type="radio" name="buscar_por" value="2" checked="checked">Codigo Externo</label>
    			<label ><input  id="Anteponer" name="Anteponer" type="text" />Anteponer A Barcode</label>
            </div>
        </div>
        <div class="col-xs-12">
            <div class="control-group text-right">
                <div class="controls">
                    <a class="btn btn-primary Buscar" href="javascript:;"><i class="fa fa-arrow-down"></i> Buscar Datos</a>
                </div>
            </div>
        </div>
        
        
    	<div class="col-xs-12">
    	    
            <div class="col-xs-5">
                <div class="form-group">
                    <label for="Destinatario">Destinatario</label>
                    <input id="Destinatario" name="Destinatario" type="text" class="form-control" autocomplete="off" value="">
                </div>
          	</div>
            <div class="col-xs-5">
                <div class="form-group">
                    <label for="Domicilio">Domicilio</label>
                    <input id="Domicilio" name="Domicilio" type="text" class="form-control" autocomplete="off" value="">
                </div>
          	</div>
            <div class="col-xs-2">
                <div class="form-group">
                    <label for="CP">CP</label>
                    <input id="CP" name="CP" type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" class="form-control" autocomplete="off" value="">
                </div>
          	</div>
            <div class="col-xs-6">
                <div class="form-group">
                    <label for="Localidad">Localidad</label>
                    <input id="Localidad" name="Localidad" type="text" class="form-control" autocomplete="off" value="">
                </div>
          	</div>
            <div class="col-xs-6">
                <div class="form-group">
                    <label for="Barcode_externo">Barra Externa</label>
                    <input id="Barcode_externo" name="Barcode_externo" type="text" class="form-control" autocomplete="off" value="">
                </div>
          	</div>
            <div class="col-xs-6">
                <div class="form-group">
                    <label for="Datos_varios">Datos varios</label>
                    <input id="Datos_varios" name="Datos_varios" type="text" class="form-control" autocomplete="off" value="">
                </div>
          	</div>
            <div class="col-xs-6">
                <div class="form-group">
                    <label for="Datos_varios_1">Datos varios 1</label>
                    <input id="Datos_varios_1" name="Datos_varios_1" type="text" class="form-control" autocomplete="off" value="">
                </div>
          	</div>
            <div class="col-xs-6">
                <div class="form-group">
                    <label for="Datos_varios_2">Datos varios 2</label>
                    <input id="Datos_varios_2" name="Datos_varios_2" type="text" class="form-control" autocomplete="off" value="">
                </div>
          	</div>
            <div class="col-xs-6">
                <div class="form-group">
                    <label for="Datos_varios_3">Datos varios 3</label>
                    <input id="Datos_varios_3" name="Datos_varios_3" type="text" class="form-control" autocomplete="off" value="">
                </div>
          	</div>

        </div>
        <div class="col-xs-12">
            <div class="control-group text-right">
                <div class="controls">
                    <a class="btn btn-success agregar-grilla" href="javascript:;"><i class="fa fa-arrow-down"></i> Guardar</a>
                    
                    <!--<a class="btn btn-success btn-submit" href="javascript:;"><i class='fa fa-floppy-o'></i> Guardar</a>-->
                </div>
            </div>
        </div>
		
                
    <?= form_close(); ?>
</div>
    
        <?php if ($this->session->flashdata('codigo_barra')): ?>
        <script type="text/javascript">
            codigo_barra.value = $this->session->flashdata('codigo_barra') + '1';
        </script>
        <?php endif; ?>
</form>
<style type="text/css">
.table-responsive {
  width: 100%;
  margin-bottom: 15px;
  overflow-x: auto;
  overflow-y: hidden;
  -webkit-overflow-scrolling: touch;
  -ms-overflow-style: -ms-autohiding-scrollbar;
  border: 1px solid #ddd;
}

.table-responsive>.table>thead>tr>th, .table-responsive>.table>tbody>tr>th, .table-responsive>.table>tfoot>tr>th, .table-responsive>.table>thead>tr>td, .table-responsive>.table>tbody>tr>td, .table-responsive>.table>tfoot>tr>td {
  white-space: nowrap;
}
</style>



<script type="text/javascript">
    
    $('.Buscar').click(function(event) {
        var form_data = new FormData();
        
        if($("#Anteponer").val() != ''){
            $("#codigo_barra").val($("#Anteponer").val() + "" + $("#codigo_barra").val()) ;
            //console.log($("#codigo_barra"));
        }
        var other_data = $('#form-piezas').serializeArray();
        console.log(other_data);
        $.each(other_data,function(key,input){
            form_data.append(input.name,input.value);
        });
        var buscar_por = $('input:radio[name=buscar_por]:checked').val();
        //console.log(form_data);
        $.ajax({
            type: "POST",
            url: '<?php echo base_url() . "ajax/piezas/buscar_para_modificar" ?>',//
            cache: false,
            processData: false,
            contentType: false,
            data: form_data,
            datatype:'json',
            success: function(respuesta) {
                var pieza = $.parseJSON(respuesta);
                if (pieza.respuesta) {
                    console.log(pieza.respuesta);
                    $('#Destinatario').val(pieza.respuesta.destinatario);
                    $('#Domicilio').val(pieza.respuesta.domicilio);
                    $('#CP').val(pieza.respuesta.codigo_postal);
                    $('#Localidad').val(pieza.respuesta.localidad);
                    $('#Barcode_externo').val(pieza.respuesta.barcode_externo);
                    $('#Datos_varios').val(pieza.respuesta.datos_varios);
                    $('#Datos_varios_1').val(pieza.respuesta.datos_varios_1);
                    $('#Datos_varios_2').val(pieza.respuesta.datos_varios_2);
                    $('#Datos_varios_3').val(pieza.respuesta.datos_varios_3);
                }
                if (pieza.status) {
                    var message = $("<label id='pieza-normal-message' style='color:red;margin-left:10px;font-weight:normal;line-height:.8em;font-size:10px'>" + pieza.status + "</label>");
                    $('input#codigo_barra').after(message);
                    $("#pieza-normal-message").show();
                    $("#pieza-normal-message").delay(1500).fadeOut('slow');
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                var message = $("<label id='pieza-normal-message' style='color:red;margin-left:10px;font-weight:normal;line-height:.8em;font-size:10px'>" + 'Ocurrio Un Error Al Intentar Enviar Los Datos.' + "</label>");
                $('input#codigo_barra').after(message);
                $("#pieza-normal-message").show();
                $("#pieza-normal-message").delay(1500).fadeOut('slow');
            }  
        });
    });

    $('.agregar-grilla').click(function(event) {
     var form_data = new FormData();
     
		if($("#Anteponer").val() != ''){
			$("#codigo_barra").val($("#Anteponer").val() + "" + $("#codigo_barra").val()) ;
			//console.log($("#codigo_barra"));
		}
        var other_data = $('#form-piezas').serializeArray();
        console.log(other_data);
        $.each(other_data,function(key,input){
            form_data.append(input.name,input.value);
        });
        var buscar_por = $('input:radio[name=buscar_por]:checked').val();
        //console.log(form_data);
        $.ajax({
            type: "POST",
            url: '<?php echo base_url() . "ajax/piezas/modificar" ?>',//buscar_para_modificar
            cache: false,
            processData: false,
            contentType: false,
            data: form_data,
            datatype:'json',
            beforeSend: function() {
                //$('#errores').html('<h3><i class="fa fa-spinner fa-spin"></i></h3>');
                //$('.btn').addClass('disabled');
            },
            success: function(respuesta) {
                var pieza = $.parseJSON(respuesta);
                if (pieza.respuesta) {
                    console.log(pieza.respuesta);
                    $('#Destinatario').val(pieza.respuesta.destinatario);
                    $('#Domicilio').val(pieza.respuesta.domicilio);
                    $('#CP').val(pieza.respuesta.codigo_postal);
                    $('#Localidad').val(pieza.respuesta.localidad);
                    $('#Barcode_externo').val(pieza.respuesta.barcode_externo);
                    $('#Datos_varios').val(pieza.respuesta.datos_varios);
                    $('#Datos_varios_1').val(pieza.respuesta.datos_varios_1);
                    $('#Datos_varios_2').val(pieza.respuesta.datos_varios_2);
                    $('#Datos_varios_3').val(pieza.respuesta.datos_varios_3);
                    
                }
                if (pieza.status) {
                    var message = $("<label id='pieza-normal-message' style='color:red;margin-left:10px;font-weight:normal;line-height:.8em;font-size:10px'>" + pieza.status + "</label>");
                    $('input#codigo_barra').after(message);
                    $("#pieza-normal-message").show();
                    $("#pieza-normal-message").delay(1500).fadeOut('slow');
                }
                if (pieza.success) {
                    var message = $("<label id='pieza-normal-message' style='color:green;margin-left:10px;font-weight:normal;line-height:.8em;font-size:16px'>" + pieza.success + "</label>");
                    $('input#codigo_barra').after(message);
                    $("#pieza-normal-message").show();
                    $("#pieza-normal-message").delay(1500).fadeOut('slow');
                    
                    $('#Destinatario').val('');
                    $('#Domicilio').val('');
                    $('#CP').val('');
                    $('#Localidad').val('');
                    $('#Barcode_externo').val('');
                    $('#codigo_barra').val('');
                    $("#codigo_barra").focus();
                    $('#Datos_varios').val('');
                    $('#Datos_varios_1').val('');
                    $('#Datos_varios_2').val('');
                    $('#Datos_varios_3').val('');
                    
                    
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                var message = $("<label id='pieza-normal-message' style='color:red;margin-left:10px;font-weight:normal;line-height:.8em;font-size:10px'>" + 'Ocurrio Un Error Al Intentar Enviar Los Datos.' + "</label>");
                $('input#codigo_barra').after(message);
                $("#pieza-normal-message").show();
                $("#pieza-normal-message").delay(1500).fadeOut('slow');
            }
            
        });
    });
</script>