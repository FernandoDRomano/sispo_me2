<div class="col-xs-12">
    <div class="ibox-content">
	<?php     
		echo form_open(current_url(), array('class'=>""));
		echo form_hidden('enviar_form','1');
	?>
		<div class="row">
			<div class="col-xs-8">
				<div class="form-group">
                    <label for="responsable_id">Responsable<span class="required">*</span></label>
                    <select id="responsable_id" name="responsable_id" class="form-control chosen-select" required>
                        <?php foreach ($responsables as $f) { ?>
                            <option value="<?php echo $f->id ?>"><?php echo $f->apellido.', '.$f->nombre ?></option>
                        <?php } ?>
                    </select>
                </div>
			</div>
			<div class="col-xs-4">
				<div class="form-group">
                    <label for="etiquetas_total">Etiquetas total</label>
                    <select id="etiquetas_total" name="etiquetas_total" class="form-control" required>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
			</div>
		</div>                
		<div class="control-group">
		  <div class="controls">
		    <?php echo form_button(array('type'  =>'submit','value' =>'Guardar','name'  =>'submit','class' =>'btn btn-success'), "<i class='fa fa-floppy-o'></i> Guardar"); ?> 
		    <a class="btn btn-danger" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2); ?>"><i class="fa fa-arrow-circle-left"></i> Volver</a>
		  </div>
		</div>
		<?php echo form_close(); ?>
	</div>
</div>
