<div class="col-xs-12">
    <div class="ibox-content">
	<?php     
		echo form_open(current_url(), array('class'=>""));
		echo form_hidden('enviar_form','1');
	?>
		
                        <div class="form-group">
                            <label for="liquidacion_id">Liquidacion_id</label>
                            <input  id="liquidacion_id" name="liquidacion_id" type="text" class="form-control" placeholder="Liquidacion_id" />
                        </div>

                        <div class="form-group">
                            <label for="comprobante_ingreso">Comprobante_ingreso</label>
                            <input  id="comprobante_ingreso" name="comprobante_ingreso" type="text" class="form-control" placeholder="Comprobante_ingreso" />
                        </div>

                        <div class="form-group">
                            <label for="remito_cliente">Remito_cliente</label>
                            <input  id="remito_cliente" name="remito_cliente" type="text" class="form-control" placeholder="Remito_cliente" />
                        </div>

                        <div class="form-group">
                            <label for="fecha">Fecha</label>
                            <input  id="fecha" name="fecha" type="text" class="form-control" placeholder="Fecha" />
                        </div>

                        <div class="form-group">
                            <label for="servicio">Servicio</label>
                            <input  id="servicio" name="servicio" type="text" class="form-control" placeholder="Servicio" />
                        </div>

                        <div class="form-group">
                            <label for="cantidad">Cantidad</label>
                            <input  id="cantidad" name="cantidad" type="text" class="form-control" placeholder="Cantidad" />
                        </div>

                        <div class="form-group">
                            <label for="precio">Precio</label>
                            <input  id="precio" name="precio" type="text" class="form-control" placeholder="Precio" />
                        </div>

                        <div class="form-group">
                            <label for="create">Create</label>
                            <input  id="create" name="create" type="text" class="form-control" placeholder="Create" />
                        </div>

                        <div class="form-group">
                            <label for="update">Update</label>
                            <input  id="update" name="update" type="text" class="form-control" placeholder="Update" />
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
