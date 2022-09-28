<div class="col-xs-12">
    <div class="ibox-content">
		<?php     
			echo form_open(current_url(), array('class'=>""));
			echo form_hidden('enviar_form','1');
		?>
			<?php echo form_hidden('id',$result->id) ?>
			
            <div class="form-group">
                <label for="sucursal_id">Sucursal</label>
                <select id="sucursal_id" name="sucursal_id" class="form-control chosen-select" >
                    <?php foreach ($sucursales as $f) { ?>
                        <option value="<?php echo $f->id ?>" <?php if($f->id == $result->sucursal_id) echo "selected" ?>><?php echo $f->nombre ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <label for="nombre">Nombre<span class="required">*</span></label>
                <input required id="nombre" name="nombre" type="text" class="form-control" placeholder="Nombre" value="<?php echo $result->nombre ?>" />
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