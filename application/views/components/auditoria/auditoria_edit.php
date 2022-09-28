<div class="col-xs-12">
    <div class="ibox-content">
		<?php     
			echo form_open(current_url(), array('class'=>""));
			echo form_hidden('enviar_form','1');
		?>
			<?php echo form_hidden('id',$result->id) ?>
			
                        <div class="form-group">
                            <label for="user_id">User_id</label>
                            <input  id="user_id" name="user_id" type="text" class="form-control" placeholder="User_id" value="<?php echo $result->user_id ?>" />
                        </div>

                        <div class="form-group">
                            <label for="consulta">Consulta</label>
                            <textarea  id="consulta" name="consulta" class="summernote form-control" placeholder="Consulta"><?php echo $result->consulta ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="fecha">Fecha</label>
                            <input  id="fecha" name="fecha" type="text" class="form-control" placeholder="Fecha" value="<?php echo $result->fecha ?>" />
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