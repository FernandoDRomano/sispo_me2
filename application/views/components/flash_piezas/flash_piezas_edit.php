<div class="col-xs-12">
    <div class="ibox-content">
		<?php     
			echo form_open(current_url(), array('class'=>""));
			echo form_hidden('enviar_form','1');
		?>
			<?php echo form_hidden('id',$result->id) ?>
			
                        <div class="form-group">
                            <label for="usuario_id">Usuario_id</label>
                            <input  id="usuario_id" name="usuario_id" type="text" class="form-control" placeholder="Usuario_id" value="<?php echo $result->usuario_id ?>" />
                        </div>

                        <div class="form-group">
                            <label for="servicio_id">Servicio_id</label>
                            <input  id="servicio_id" name="servicio_id" type="text" class="form-control" placeholder="Servicio_id" value="<?php echo $result->servicio_id ?>" />
                        </div>

                        <div class="form-group">
                            <label for="tipo_id">Tipo_id</label>
                            <input  id="tipo_id" name="tipo_id" type="text" class="form-control" placeholder="Tipo_id" value="<?php echo $result->tipo_id ?>" />
                        </div>

                        <div class="form-group">
                            <label for="sucursal_id">Sucursal_id</label>
                            <input  id="sucursal_id" name="sucursal_id" type="text" class="form-control" placeholder="Sucursal_id" value="<?php echo $result->sucursal_id ?>" />
                        </div>

                        <div class="form-group">
                            <label for="estado_id">Estado_id</label>
                            <input  id="estado_id" name="estado_id" type="text" class="form-control" placeholder="Estado_id" value="<?php echo $result->estado_id ?>" />
                        </div>

                        <div class="form-group">
                            <label for="comprobante_ingreso_id">Comprobante_ingreso_id</label>
                            <input  id="comprobante_ingreso_id" name="comprobante_ingreso_id" type="text" class="form-control" placeholder="Comprobante_ingreso_id" value="<?php echo $result->comprobante_ingreso_id ?>" />
                        </div>

                        <div class="form-group">
                            <label for="cantidad">Cantidad</label>
                            <input  id="cantidad" name="cantidad" type="text" class="form-control" placeholder="Cantidad" value="<?php echo $result->cantidad ?>" />
                        </div>

                        <div class="form-group">
                            <label for="barcode">Barcode</label>
                            <input  id="barcode" name="barcode" type="text" class="form-control" placeholder="Barcode" value="<?php echo $result->barcode ?>" />
                        </div>

                        <div class="form-group">
                            <label for="barcode_externo">Barcode_externo</label>
                            <input  id="barcode_externo" name="barcode_externo" type="text" class="form-control" placeholder="Barcode_externo" value="<?php echo $result->barcode_externo ?>" />
                        </div>

                        <div class="form-group">
                            <label for="destinatario">Destinatario</label>
                            <input  id="destinatario" name="destinatario" type="text" class="form-control" placeholder="Destinatario" value="<?php echo $result->destinatario ?>" />
                        </div>

                        <div class="form-group">
                            <label for="domicilio">Domicilio</label>
                            <input  id="domicilio" name="domicilio" type="text" class="form-control" placeholder="Domicilio" value="<?php echo $result->domicilio ?>" />
                        </div>

                        <div class="form-group">
                            <label for="codigo_postal">Codigo_postal</label>
                            <input  id="codigo_postal" name="codigo_postal" type="text" class="form-control" placeholder="Codigo_postal" value="<?php echo $result->codigo_postal ?>" />
                        </div>

                        <div class="form-group">
                            <label for="localidad">Localidad</label>
                            <input  id="localidad" name="localidad" type="text" class="form-control" placeholder="Localidad" value="<?php echo $result->localidad ?>" />
                        </div>

                        <div class="form-group">
                            <label for="vista">Vista</label>
                            <input  id="vista" name="vista" type="text" class="form-control" placeholder="Vista" value="<?php echo $result->vista ?>" />
                        </div>

                        <div class="form-group">
                            <label for="recibio">Recibio</label>
                            <input  id="recibio" name="recibio" type="text" class="form-control" placeholder="Recibio" value="<?php echo $result->recibio ?>" />
                        </div>

                        <div class="form-group">
                            <label for="documento">Documento</label>
                            <input  id="documento" name="documento" type="text" class="form-control" placeholder="Documento" value="<?php echo $result->documento ?>" />
                        </div>

                        <div class="form-group">
                            <label for="vinculo">Vinculo</label>
                            <input  id="vinculo" name="vinculo" type="text" class="form-control" placeholder="Vinculo" value="<?php echo $result->vinculo ?>" />
                        </div>

                        <div class="form-group">
                            <label for="datos_varios">Datos_varios</label>
                            <textarea  id="datos_varios" name="datos_varios" class="summernote form-control" placeholder="Datos_varios"><?php echo $result->datos_varios ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="datos_varios_1">Datos_varios_1</label>
                            <textarea  id="datos_varios_1" name="datos_varios_1" class="summernote form-control" placeholder="Datos_varios_1"><?php echo $result->datos_varios_1 ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="datos_varios_2">Datos_varios_2</label>
                            <textarea  id="datos_varios_2" name="datos_varios_2" class="summernote form-control" placeholder="Datos_varios_2"><?php echo $result->datos_varios_2 ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="verifico_id">Verifico_id</label>
                            <input  id="verifico_id" name="verifico_id" type="text" class="form-control" placeholder="Verifico_id" value="<?php echo $result->verifico_id ?>" />
                        </div>

                        <div class="form-group">
                            <label for="rendicion_id">Rendicion_id</label>
                            <input  id="rendicion_id" name="rendicion_id" type="text" class="form-control" placeholder="Rendicion_id" value="<?php echo $result->rendicion_id ?>" />
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