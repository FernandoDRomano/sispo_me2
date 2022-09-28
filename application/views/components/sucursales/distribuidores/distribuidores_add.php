<div class="col-xs-12">
    <div class="ibox-content">
	<?php     
		echo form_open(current_url(), array('class'=>""));
		echo form_hidden('enviar_form','1');
	?>
		
        <div class="form-group">
            <label for="apellido_nombre">Apellido nombre<span class="required">*</span></label>
            <input required id="apellido_nombre" name="apellido_nombre" type="text" class="form-control" placeholder="Apellido nombre" />
        </div>

        <div class="row">
            <div class="col-xs-6">
                <div class="form-group">
                    <label for="domicilio">Domicilio</label>
                    <input  id="domicilio" name="domicilio" type="text" class="form-control" placeholder="Domicilio" />
                </div>
            </div>
            <div class="col-xs-4">
                <div class="form-group">
                    <label for="localidad">Localidad</label>
                    <input  id="localidad" name="localidad" type="text" class="form-control" placeholder="Localidad" />
                </div>
            </div>
            <div class="col-xs-2">
                <div class="form-group">
                    <label for="codigo_postal">Codigo postal</label>
                    <input  id="codigo_postal" name="codigo_postal" type="text" class="form-control" placeholder="Codigo postal" />
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="telefonos">Telefonos</label>
            <input  id="telefonos" name="telefonos" type="text" class="form-control tags" placeholder="Telefonos" />
        </div>

        <div class="form-group">
            <label for="activo">Activo</label>
            <div class="radio i-checks"><label> <input type="radio" name="activo" id="activo" class="form-control" value="1" checked> Si</label></div>
            <div class="radio i-checks"><label> <input type="radio" name="activo" id="activo" class="form-control" value="0" > No</label></div>
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
