<div class="col-xs-12">
    <div class="ibox-content">
		<?php     
			echo form_open(current_url(), array('class'=>""));
			echo form_hidden('enviar_form','1');
            echo form_hidden('id',$result->id);
		?>
            <div class="row">
                <div class="col-xs-2">
                    <div class="form-group">
                        <label for="codigo">Código</label>
                        <input  id="codigo" name="codigo" type="text" class="form-control" placeholder="codigo" maxlength="11" value="<?php echo $result->codigo ?>"/>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="grupo_id">Grupo</label>
                        <select id="grupo_id" name="grupo_id" class="form-control chosen-select" >
                            <?php foreach ($grupos as $f) { ?>
                                <option value="<?php echo $f->id ?>" <?php if($f->id == $result->grupo_id) echo "selected" ?>><?php echo $f->nombre ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="nombre">Nombre<span class="required">*</span></label>
                        <input required id="nombre" name="nombre" type="text" class="form-control" placeholder="Nombre" value="<?php echo $result->nombre ?>" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-3">
                    <div class="form-group">
                        <label for="precio">Precio</label>
                        <input  id="precio" name="precio" type="number" class="form-control" placeholder="Precio" step="0.01" min="0.01" value="<?php echo $result->precio ?>"/>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-2">
                    <div class="form-group">
                        <label for="acuse">Acuse</label>
                        <div class="radio i-checks"><label> <input type="radio" name="acuse" id="acuse" class="form-control" value="1" <?php if($result->acuse == 1) echo "checked" ?>> Si</label></div>
                        <div class="radio i-checks"><label> <input type="radio" name="acuse" id="acuse" class="form-control" value="0" <?php if($result->acuse == 0) echo "checked" ?>> No</label></div>
                    </div>
                </div>
                <div class="col-xs-2">
                    <div class="form-group">
                        <label for="activo">Activo</label>
                        <div class="radio i-checks"><label> <input type="radio" name="activo" id="activo" class="form-control" value="1" <?php if($result->activo == 1) echo "checked" ?>> Si</label></div>
                        <div class="radio i-checks"><label> <input type="radio" name="activo" id="activo" class="form-control" value="0" <?php if($result->activo == 0) echo "checked" ?>> No</label></div>
                    </div>
                </div>
                <div class="col-xs-2">
                    <div class="form-group">
                        <label for="web">Web</label>
                        <div class="radio i-checks"><label> <input type="radio" name="web" id="web" class="form-control" value="1" <?php if($result->web == 1) echo "checked" ?>> Si</label></div>
                        <div class="radio i-checks"><label> <input type="radio" name="web" id="web" class="form-control" value="0" <?php if($result->web == 0) echo "checked" ?>> No</label></div>
                    </div>
                </div>
                <div class="col-xs-2">
                    <div class="form-group">
                        <label for="pieza">Requiere pieza</label>
                        <div class="radio i-checks"><label> <input type="radio" name="pieza" id="pieza" class="form-control" value="1" <?php if($result->pieza == 1) echo "checked" ?>> Si</label></div>
                        <div class="radio i-checks"><label> <input type="radio" name="pieza" id="pieza" class="form-control" value="0" <?php if($result->pieza == 0) echo "checked" ?>> No</label></div>
                    </div>
                </div>
            </div>

			<div class="control-group">
				<div class="controls">
					<?php echo form_button(array('type'  =>'submit','value' =>'Guardar Cambios','name'  =>'submit','class' =>'btn btn-success'), "<i class='fa fa-floppy-o'></i> Guardar"); ?>
					<a class="btn btn-danger" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2); ?>"><i class="fa fa-arrow-circle-left"></i> Volver</a>
				</div>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    $('#codigo').blur(function(event) {
        $.ajax({
            type: "POST",
            url: '<?php echo base_url()."ajax/Servicios/codigoExistente" ?>',
            data: 'codigo='+$('#codigo').val(),
            cache: false,
            datatype:'json',
            beforeSend: function() {
                //$('#errores').html('<h3><i class="fa fa-spinner fa-spin"></i></h3>');
                //$('.btn').addClass('disabled');
            },
            success: function(respuesta){
                var obj = $.parseJSON(respuesta);
                if (obj !== null){
                    $("<span class='error-tool-tip'>El Código ya existe<span>").insertAfter($('#codigo'));
                   $('.error-tool-tip').delay(2000).fadeOut('slow');
                    $('#codigo').val('');
                }
                //window.location.reload();
            }
        });
    });
})
</script>