<div class="col-xs-12">
	<?php     
		echo form_open(current_url(), array('class'=>""));
		echo form_hidden('enviar_form','1');
		echo form_hidden('id',$result->id);
                echo form_input(array('name' => 'tipo_id', 'type'=>'hidden', 'id' =>'tipo_id', 'value' => $result->tipo_id));
	?>
	    <div class="ibox-content">		
                <div class="control-group">
                    <div class="controls text-right">
                        <a class="btn btn-primary <?php if($result->usuario_aprobacion_id != null && $result->fecha_aprobacion != null) echo 'disabled'?>" href="<?php echo base_url().'precios/actualizacion_precios/aprobacion/'.$result->id; ?>"><i class="fa fa-check"></i> Aprobar</a>
                        <button name="submit" type="submit" value="Guardar Cambios" class="btn btn-success <?php if($result->usuario_aprobacion_id != null && $result->fecha_aprobacion != null) echo 'disabled'?>"><i class="fa fa-floppy-o"></i> Guardar</button>
                        <a class="btn btn-danger" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2); ?>"><i class="fa fa-arrow-circle-left"></i> Volver</a>					
                    </div>
                </div>		
            </div>
            <div class="ibox-content" id="listado">	
	    	<div id="body-grilla-load"></div>
	    	<table class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
	            <thead>
	                <tr>
	                    <th><a href="#">Servicio / Precio Especial</a></th>
	                    <th><a href="#">Precio Actual</a></th>
	                    <th class="col-xs-3"><a href="#">Precio Nuevo</a></th>
	                </tr>
	            </thead>
	            <tbody id="body-grilla">
	            	<?php foreach ($servicios as $f) {?>
	            		<tr id="grilla-servicio-<?php echo $f->id?>">
	            			<td><?php echo $f->nombre?></td>
	            			<td>$ <?php echo $f->precio_actual?></td>
	            			<td>
	            				<div class="form-group">
	            					<div class="input-group">
	            						<div class="input-group-addon">$</div>
	            						<input type="number" class="form-control" id="input_<?php echo $f->id?>" name="input_<?php echo $f->id?>" value="<?php echo $f->precio_nuevo?>" step="0.01" required <?php if($result->usuario_aprobacion_id != null && $result->fecha_aprobacion != null) echo 'disabled' ?> >
	            					</div>
	            				</div>
	            			</td>
	            		</tr>
	            	<?php } ?>
	            </tbody>
	        </table>
		</div>
	<?php echo form_close(); ?>
</div>