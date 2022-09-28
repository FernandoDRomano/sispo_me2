<div class="col-xs-12">
    <div class="ibox-content">
		<?php     
			echo form_open(current_url(), array('class'=>""));
			echo form_hidden('enviar_form','1');
		?>
			<?php echo form_hidden('id',$result->id) ?>
			
                        <div class="form-group">
                            <label for="cliente_id">Cliente</label>
                            <select id="cliente_id" name="cliente_id" class="form-control chosen-select" >
                                <?php foreach ($cliente_id as $f) { ?>
                                    <option value="<?php echo $f->id ?>" <?php if($f->id == $result->cliente_id) echo "selected" ?>><?php echo $f->id ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="departamento_id">Departamento</label>
                            <select id="departamento_id" name="departamento_id" class="form-control chosen-select" >
                                <?php foreach ($departamento_id as $f) { ?>
                                    <option value="<?php echo $f->id ?>" <?php if($f->id == $result->departamento_id) echo "selected" ?>><?php echo $f->id ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="usuario_id">Usuario</label>
                            <select id="usuario_id" name="usuario_id" class="form-control chosen-select" >
                                <?php foreach ($usuario_id as $f) { ?>
                                    <option value="<?php echo $f->id ?>" <?php if($f->id == $result->usuario_id) echo "selected" ?>><?php echo $f->id ?></option>
                                <?php } ?>
                            </select>
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