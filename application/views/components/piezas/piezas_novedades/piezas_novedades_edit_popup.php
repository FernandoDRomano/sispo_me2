<div class="col-xs-12">
    <div class="ibox-content">
	<?php     
		echo form_open(current_url(), array('class'=>""));
		echo form_hidden('enviar_form','1');
	?>
        <input type="hidden" id="pieza_id" name="pieza_id" value="<?php echo $pieza_id ?>"/>
        <div class="row">
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
        </div>

		<div class="control-group">
		  <div class="controls">
		    <?php echo form_button(array('type'  =>'submit','value' =>'Guardar','name'  =>'submit','class' =>'btn btn-success'), "<i class='fa fa-floppy-o'></i> Guardar"); ?> 
		    <a class="btn btn-danger" href="<?php echo base_url('piezas/novedades/index_edit'); ?>"><i class="fa fa-arrow-circle-left"></i> Volver</a>
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
    
        getServicios(); 

        $('#grupo_servicio_id').change(function(event) {
            getServicios();             
        });
    });
</script>