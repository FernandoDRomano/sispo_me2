<div class="col-xs-12">
    <div class="ibox-content">
	<?php     
		echo form_open(current_url(), array('class'=>""));
		echo form_hidden('enviar_form','1');
	?>
        <div class="form-group">
            <label for="nombre">Nombre<span class="required">*</span></label>
            <input required id="nombre" name="nombre" type="text" class="form-control" placeholder="Nombre" />
        </div>

		<div class="control-group">
		  <div class="controls">
		    <?php echo form_button(array('type'  =>'submit','value' =>'Guardar','name'  =>'submit','class' =>'btn btn-success'), "<i class='fa fa-floppy-o'></i> Guardar"); ?> 
		    <a class="btn btn-danger" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2); ?>"><i class="fa fa-arrow-circle-left"></i> Volver</a>
		  </div>
		</div>
	<?php echo form_close(); ?>
	</div>
	<div class="ibox-content">
		<table id="results" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
            <thead>
                <tr>
                    <th class="col-xs-1"><a href="#">ID</a></th>
                    <th><a href="#">Nombre</a></th>
                </tr>
            </thead>
            <tbody>
              	<?php foreach ($variables as $f) { ?>
                  	<tr>
                    	<td><?php echo $f->id ?></td>
                    	<td><?php echo $f->nombre ?></td>
                  	</tr>
              	<?php } ?>
            </tbody>
        </table>
	</div>
</div>
