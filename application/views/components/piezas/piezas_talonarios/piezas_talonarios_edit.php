<div class="col-xs-12">
    <div class="ibox-content">
		<?php     
			echo form_open(current_url(), array('class'=>""));
			echo form_hidden('enviar_form','1');
		?>
			<?php echo form_hidden('id',$result->id) ?>
			<div class="row">
				<div class="col-xs-8">
					<div class="form-group">
		                <label for="responsable_id">Responsable<span class="required">*</span></label>
		                <select id="responsable_id" name="responsable_id" class="form-control chosen-select" required>
		                    <?php foreach ($responsables as $f) { ?>
		                        <option value="<?php echo $f->id ?>" <?php if($f->id == $result->responsable_id) echo "selected" ?>><?php echo $f->apellido.', '.$f->nombre ?></option>
		                    <?php } ?>
		                </select>
		            </div>
				</div>
				<div class="col-xs-4">
					<div class="form-group">
		                <label for="etiquetas_total">Etiquetas total</label>
		                <select id="etiquetas_total" name="etiquetas_total" class="form-control" required>
	                        <option value="1" <?php if ($result->etiquetas_total == 1)  echo 'selected'; ?>>1</option>
	                        <option value="2" <?php if ($result->etiquetas_total == 2)  echo 'selected'; ?>>2</option>
	                        <option value="3" <?php if ($result->etiquetas_total == 3)  echo 'selected'; ?>>3</option>
	                        <option value="4" <?php if ($result->etiquetas_total == 4)  echo 'selected'; ?>>4</option>
	                        <option value="5" <?php if ($result->etiquetas_total == 5)  echo 'selected'; ?>>5</option>
	                        <option value="50" <?php if ($result->etiquetas_total == 50)  echo 'selected'; ?>>50</option>
	                        <option value="100" <?php if ($result->etiquetas_total == 100)  echo 'selected'; ?>>100</option>
	                    </select>
		                <input  id="etiquetas_total" name="etiquetas_total" type="number" class="form-control" placeholder="Etiquetas total" value="" readonly />
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