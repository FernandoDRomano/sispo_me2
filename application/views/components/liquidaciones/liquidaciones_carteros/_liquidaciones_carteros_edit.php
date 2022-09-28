<div class="col-xs-12">
    <div class="ibox-content">
		<?php     
			echo form_open(current_url(), array('class'=>""));
			echo form_hidden('enviar_form','1');
		?>
			<?php echo form_hidden('id',$result->id) ?>
			
                        <div class="form-group">
                            <label for="sucursal_id">Sucursal_id</label>
                            <input  id="sucursal_id" name="sucursal_id" type="text" class="form-control" placeholder="Sucursal_id" value="<?php echo $result->sucursal_id ?>" />
                        </div>

                        <div class="form-group">
                            <label for="periodo_desde">Periodo_desde</label>
                            <input  id="periodo_desde" name="periodo_desde" type="text" class="form-control" placeholder="Periodo_desde" value="<?php echo $result->periodo_desde ?>" />
                        </div>

                        <div class="form-group">
                            <label for="periodo_hasta">Periodo_hasta</label>
                            <input  id="periodo_hasta" name="periodo_hasta" type="text" class="form-control" placeholder="Periodo_hasta" value="<?php echo $result->periodo_hasta ?>" />
                        </div>

                        <div class="form-group">
                            <label for="create">Create</label>
                            <input  id="create" name="create" type="text" class="form-control" placeholder="Create" value="<?php echo $result->create ?>" />
                        </div>

                        <div class="form-group">
                            <label for="update">Update</label>
                            <input  id="update" name="update" type="text" class="form-control" placeholder="Update" value="<?php echo $result->update ?>" />
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