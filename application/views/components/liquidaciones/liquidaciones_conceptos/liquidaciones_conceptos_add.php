<div class="col-xs-12">
    <div class="ibox-content">
	<?php     
		echo form_open(current_url(), array('class'=>""));
		echo form_hidden('enviar_form','1');
	?>
		
                        <div class="form-group">
                            <label for="concepto">Concepto</label>
                            <input  id="concepto" name="concepto" type="text" class="form-control" placeholder="Concepto" />
                        </div>
                        <select id="operacion" name="operacion" class="form-control chosen-select" >
                                <option value="S">SUMA</option>
                                <option value="R">RESTA</option>
                        </select>
		<div class="control-group">
		  <div class="controls">
		    <?php echo form_button(array('type'  =>'submit','value' =>'Guardar','name'  =>'submit','class' =>'btn btn-success'), "<i class='fa fa-floppy-o'></i> Guardar"); ?> 
		    <a class="btn btn-danger" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2); ?>"><i class="fa fa-arrow-circle-left"></i> Volver</a>
		  </div>
		</div>
		<?php echo form_close(); ?>
	</div>
</div>
