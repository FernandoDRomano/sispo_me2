<div class="col-xs-12">
    <div class="ibox-content">
		<?php     
			echo form_open(current_url(), array('class'=>""));
			echo form_hidden('enviar_form','1');
                        echo form_input(array('name' => 'actualizacion_precios_especiales_id', 
                                                'type'=>'hidden', 
                                                'id' =>'actualizacion_precios_especiales_id', 
                                                'value' =>$results->id));
		?>
                        <div class="form-group">
                            <div class="col-xs-6">
                                <div class="form-group">
                                        <label for="cliente">Cliente</label>
                                        <input class="form-control" type="text" id="cliente" name="cliente" value="<?php echo $results[0]->cliente_nombre ?>" disabled/>
                                        <input id="cliente_id" name="cliente_id" type="hidden" value="<?php echo $results[0]->cliente_id ?>" />
                                </div>
                            </div>
                            <div class="control-group">
                                    <div class="controls text-right">
                                        <a class="btn btn-primary <?php if($actualizacion_precios->usuario_aprobacion_id != null && $actualizacion_precios->fecha_aprobacion != null
                                                                           || $actualizacion_precios->usuario_rechazo_id != null && $actualizacion_precios->fecha_rechazo != null) echo 'disabled'?>" href="<?php echo base_url().'precios/actualizacion_precios_especiales/aprobacion/'.$actualizacion_precios->id; ?>"><i class="fa fa-check"></i> Aprobar</a>
                                        <a class="btn btn-warning <?php if($actualizacion_precios->usuario_aprobacion_id != null && $actualizacion_precios->fecha_aprobacion != null
                                                                            || $actualizacion_precios->usuario_rechazo_id != null && $actualizacion_precios->fecha_rechazo != null) echo 'disabled'?>" href="<?php echo base_url().'precios/actualizacion_precios_especiales/rechazo/'.$actualizacion_precios->id; ?>"><i class="fa fa-ban"></i> Rechazar</a>
                                        
                                            <button name="submit" type="submit" value="Guardar Cambios" class="btn btn-success <?php if($actualizacion_precios->usuario_aprobacion_id != null && $actualizacion_precios->fecha_aprobacion != null
                                                                                                                                        || $actualizacion_precios->usuario_rechazo_id != null && $actualizacion_precios->fecha_rechazo != null) echo 'disabled'?>"><i class="fa fa-floppy-o"></i> Guardar</button>
                                            <a class="btn btn-danger" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2); ?>"><i class="fa fa-arrow-circle-left"></i> Volver</a>					
                                    </div>
                            </div>	
                        </div>
                        <div class="ibox-content" id="listado">	
	    	<div id="body-grilla-load"></div>
	    	<table class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
	            <thead>
	                <tr>
                            <th class="col-xs-2"><a href="#">Nombre Real</a></th>
                            <th class="col-xs-1"><a href="#">Precio Lista</a></th>
	                    <th class="col-xs-4"><a href="#">Nombre Especial</a></th>
                            <th class="col-xs-2"><a href="#">Precio Especial</a></th>
                            <th class="col-xs-2"><a href="#">Días</a></th>
                            <th class="col-xs-1"><a href="#">Estado</a></th>
	                </tr>
	            </thead>
	            <tbody id="body-grilla">
	            	<?php foreach ($results as $f) {?>
	            		<tr id="grilla-servicio-<?php echo $f->id?>">
                                        <td>
                                            <?php echo $f->nombre_servicio?><br>
                                            (<?php echo $f->codigo_servicio?>)
                                        </td>
                                        <td>$ <?php echo $f->precio_lista?></td>
                                        <td>
                                            <div class="form-group">
                                                <div class="input-group" style='width:100%'>
                                                    <input type="text" class="form-control" id="nombre_<?php echo $f->id?>" name="nombre_<?php echo $f->id?>" value="<?php echo $f->nombre_nuevo?>" required <?php if($actualizacion_precios->usuario_aprobacion_id != null && $actualizacion_precios->fecha_aprobacion != null
                                                                                                                                                                                                                      || $actualizacion_precios->usuario_rechazo_id != null && $actualizacion_precios->fecha_rechazo != null) echo 'disabled' ?> >
                                                </div>
                                            </div>
	            			</td>
	            			<td>
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <div class="input-group-addon">$</div>
                                                    <input type="number" class="form-control" id="precio_<?php echo $f->id?>" name="precio_<?php echo $f->id?>" value="<?php echo $f->precio_nuevo?>" step="0.01" required <?php if($actualizacion_precios->usuario_aprobacion_id != null && $actualizacion_precios->fecha_aprobacion != null
                                                                                                                                                                                                                                    || $actualizacion_precios->usuario_rechazo_id != null && $actualizacion_precios->fecha_rechazo != null) echo 'disabled' ?> >
                                                </div>
                                            </div>
	            			</td>
	            			<td>
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <input type="number" class="form-control" id="dias_<?php echo $f->id?>" name="dias_<?php echo $f->id?>" value="<?php echo $f->dias_nuevo?>" step="1" required <?php if($actualizacion_precios->usuario_aprobacion_id != null && $actualizacion_precios->fecha_aprobacion != null
                                                                                                                                                                                                                           || $actualizacion_precios->usuario_rechazo_id != null && $actualizacion_precios->fecha_rechazo != null) echo 'disabled' ?> >
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <select id="activo_<?php echo $f->id?>" name="activo_<?php echo $f->id?>" class="form-control chosen-select" <?php if($actualizacion_precios->usuario_aprobacion_id != null && $actualizacion_precios->fecha_aprobacion != null
                                                                                                                                                                      || $actualizacion_precios->usuario_rechazo_id != null && $actualizacion_precios->fecha_rechazo != null) echo 'disabled' ?> >
                                                    <option value="0" <?php if($f->activo == 0) echo 'selected'?> >Inactivo</option>
                                                    <option value="1" <?php if($f->activo == 1) echo 'selected'?>>Activo</option>
                                                </select>
                                            </div>
                                        </td>
	            		</tr>
	            	<?php } ?>
	            </tbody>
	        </table>
		</div>
	<?php echo form_close(); ?>
</div>
</div>