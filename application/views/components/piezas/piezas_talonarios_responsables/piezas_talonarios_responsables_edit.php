<div class="col-xs-12">
    <div class="ibox-content">
		<?php     
			echo form_open(current_url(), array('class'=>""));
			echo form_hidden('enviar_form','1');
		?>
			<?php echo form_hidden('id',$result->id) ?>
			
            <div class="form-group">
                <label for="sucursal_id">Sucursal<span class="required">*</span></label>
                <select id="sucursal_id" name="sucursal_id" class="form-control chosen-select" required>
                    <?php foreach ($sucursales as $f) { ?>
                        <option value="<?php echo $f->id ?>" <?php if($f->id == $result->sucursal_id) echo "selected" ?>><?php echo $f->nombre ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <label for="apellido">Apellido<span class="required">*</span></label>
                <input required id="apellido" name="apellido" type="text" class="form-control" placeholder="Apellido" value="<?php echo $result->apellido ?>" />
            </div>

            <div class="form-group">
                <label for="nombre">Nombre<span class="required">*</span></label>
                <input required id="nombre" name="nombre" type="text" class="form-control" placeholder="Nombre" value="<?php echo $result->nombre ?>" />
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