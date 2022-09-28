<div class="col-xs-12">
    <div class="ibox-content">
		<?php     
			echo form_open(current_url(), array('class'=>""));
			echo form_hidden('enviar_form','1');
		?>
			<?php echo form_hidden('id',$result->id) ?>
			
            <div class="form-group">
                <label for="apellido_nombre">Apellido nombre<span class="required">*</span></label>
                <input required id="apellido_nombre" name="apellido_nombre" type="text" class="form-control" placeholder="Apellido nombre" value="<?php echo $result->apellido_nombre ?>" />
            </div>

            <div class="row">
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="domicilio">Domicilio</label>
                        <input  id="domicilio" name="domicilio" type="text" class="form-control" placeholder="Domicilio" value="<?php echo $result->domicilio ?>" />
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="localidad">Localidad</label>
                        <input  id="localidad" name="localidad" type="text" class="form-control" placeholder="Localidad" value="<?php echo $result->localidad ?>" />
                    </div>
                </div>
                <div class="col-xs-2">
                    <div class="form-group">
                        <label for="codigo_postal">Codigo postal</label>
                        <input  id="codigo_postal" name="codigo_postal" type="text" class="form-control" placeholder="Codigo postal" value="<?php echo $result->codigo_postal ?>" />
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="telefonos">Telefonos</label>
                <input  id="telefonos" name="telefonos" type="text" class="form-control tags" placeholder="Telefonos" value="<?php echo $result->telefonos ?>" />
            </div>

            <div class="form-group">
                <label for="activo">Activo</label>
                <div class="radio i-checks"><label> <input type="radio" name="activo" id="activo" class="form-control" value="1" <?php if($result->activo == 1) echo "checked" ?>> Si</label></div>
                <div class="radio i-checks"><label> <input type="radio" name="activo" id="activo" class="form-control" value="0" <?php if($result->activo == 0) echo "checked" ?>> No</label></div>
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