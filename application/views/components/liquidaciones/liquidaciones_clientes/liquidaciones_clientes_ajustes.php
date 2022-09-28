<div class="col-xs-12">
    <div class="ibox-content">
	<?php     
		echo form_open(current_url(), array('class'=>""));
		echo form_hidden('enviar_form','1');
	?>
        <input type="hidden" id="comprobante_ingreso_id" name="comprobante_ingreso_id" value="<?php echo $comprobante_ingreso_id ?>"/>
        <input type="hidden" id="comprobante_ingreso_servicio_id" name="comprobante_ingreso_servicio_id" value="<?php echo $comprobante_ingreso_servicio_id ?>"/>
        <div class="row">
            <div class="col-xs-6">
                        <div class="form-group">
                            <label for="liquidacion">Liquidacion</label>
                            <input  id="liquidacion_cliente_id" name="liquidacion_cliente_id" type="text" class="form-control" autocomplete="off" readonly value="<?php echo $liquidacion_cliente_id?>"/>
                            <label for="concepto">Concepto</label>
                            <select id="liquidacion_concepto_id" name="liquidacion_concepto_id" class="form-control"  required >
                                    <option value="-1"> Seleccionar </option>
                                    <?php foreach ($conceptos as $concepto): ?>
                                        <option value="<?= $concepto->id ?>"><?= $concepto->concepto ?></option>
                                    <?php endforeach; ?>
                            </select>
                            <label for="operacion">Operaci&oacute;n</label>
                            <input id="operacion_id" name="operacion_id" class="form-control"  value="" readonly="readonly" >
                            <label for="cantidad">Cantidad</label>
                            <input  id="cantidad" name="cantidad" type="text" class="form-control" autocomplete="off"  value=""/>
                            <label for="comentario">Comentario</label>
                            <input  id="cometario" name="comentario" type="text" class="form-control" autocomplete="off"  value=""/>
                        </div>
                    </div>
        </div>

		<div class="control-group">
		  <div class="controls">
		    <?php echo form_button(array('type'  =>'submit','value' =>'Guardar','name'  =>'submit','class' =>'btn btn-success'), "<i class='fa fa-floppy-o'></i> Guardar"); ?> 
		    <a class="btn btn-danger" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/edit/'.$liquidacion_cliente_id; ?>"><i class="fa fa-arrow-circle-left"></i> Volver</a>
		  </div>
		</div>
		<?php echo form_close(); ?>
	</div>
</div>
<script type="text/javascript">
    
    $('#modal-form').modal('show');

    $(document).ready(function() {
        $('#btn_borrar').click(function() {
            $( "#dialog-confirm" ).dialog({
                    resizable: false,
                    height:150,
                    modal: true,
                    buttons: {
                            'Si': function() {
                                $('#task').val('borrarCursoTaller');
                                $('#adminForm').submit();

                            },
                            'No': function() {
                                    $( this ).dialog( 'close' );
                            }
                    }
            });

        });
    
//        getServicios(); 
//
//        $('#grupo_servicio_id').change(function(event) {
//            getServicios();             
//        });
        
        $('#liquidacion_concepto_id').change(function(event) {
            $.ajax({
                    type: "POST",
                    url: '<?php echo base_url()."ajax/liquidaciones/getOperacion/" ?>'+$('#liquidacion_concepto_id').val(),
                    data: '',
                    cache: false,
                    datatype:'json',
                    beforeSend: function() {
                        //$('#errores').html('<h3><i class="fa fa-spinner fa-spin"></i></h3>');
                        //$('.btn').addClass('disabled');
                    },
                    success: function(data){
                                var respuesta = $.parseJSON(data);
                                var operacion = "";
                                if (respuesta.operacion === "S") operacion = "SUMA";
                                if (respuesta.operacion === "R") operacion = "RESTA";
                                $('#operacion_id').val(operacion);
                            }
                 });
        });
       
    });
</script>