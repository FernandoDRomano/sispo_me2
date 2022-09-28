<div class="col-xs-12">    
    <?php     
            echo form_open(current_url(), array('class'=>""));
            echo form_hidden('enviar_form','1');
    ?>
    <div class="ibox-content">
            <div class="control-group">
              <div class="controls text-right">
                <?php echo form_button(array('type'  =>'submit','value' =>'Guardar','name'  =>'submit','class' =>'btn btn-success btn-confirmar'), "<i class='fa fa-floppy-o'></i> Guardar"); ?> 
                <a class="btn btn-danger" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2); ?>"><i class="fa fa-arrow-circle-left"></i> Volver</a>
              </div>
            </div>
    </div>

    <div class="ibox-content" id="listado">	
	    	<div id="body-grilla-load"></div>
	    	<table class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
	            <thead>
	                <tr>
	                    <th><a href="#">Servicio</a></th>
	                    <th><a href="#">Precio</a></th>
	                    <th class="col-xs-3"><a href="#">Precio Nuevo</a></th>
	                </tr>
	            </thead>
	            <tbody id="body-grilla">
	            	<?php foreach ($servicios as $f) {?>
	            		<tr id="grilla-servicio-<?php echo $f->id?>">
	            			<td><?php echo $f->nombre?></td>
	            			<td>$ <?php echo $f->precio?></td>
	            			<td>
	            				<div class="form-group">
	            					<div class="input-group">
	            						<div class="input-group-addon">$</div>
	            						<input type="number" class="form-control" id="input_<?php echo $f->id?>" name="input_<?php echo $f->id?>" value="<?php echo $f->precio?>" step="0.01" required <?php if($result->usuario_aprobacion_id != null && $result->fecha_aprobacion != null) echo 'disabled' ?> >
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
								
<script type="text/javascript">
//	$(document).ready(function() {
//		$('.btn-seleccionar').click(function(event) {
//			var id = $('#tipo_id').val();
//			$('#body-grilla').html('');
//			$('#body-grilla-load').html('<div class="text-center"><h3><i class="fa fa-spinner fa-spin"></i></h3></div>');
//			if (id == 5) {
//				// precio especial
//				var url = '<?php echo base_url() ?>' + 'ajax/precios/precios_especiales/';
//	            var options = '';
//	            $.getJSON(url, function(data) {
//	            	$.each(data, function(key, val) {
//	            		options = '<tr id="grilla-servicio-'+val.id+'"><td>'+val.nombre+'</td><td>$ '+val.precio+'</td><td><div class="form-group"><div class="input-group"><div class="input-group-addon">$</div><input type="number" class="form-control" id="input_'+val.id+'" name="input_'+val.id+'" value="'+val.precio+'" step="0.01" required ></div></div></td></tr>';
//	            		$("#body-grilla").append(options);
//	            	});
//	            });	            
//                $('.btn-confirmar').removeClass('disabled');
//			}else if(id == 6){
//				// servicio
//				var url = '<?php echo base_url() ?>' + 'ajax/precios/servicios/';
//	            var options = '';
//	            $.getJSON(url, function(data) {
//	            	$.each(data, function(key, val) {
//	            		options = '<tr id="grilla-servicio-'+val.id+'"><td>'+val.nombre+'</td><td>$ '+val.precio+'</td><td><div class="form-group"><div class="input-group"><div class="input-group-addon">$</div><input type="number" class="form-control" id="input_'+val.id+'" name="input_'+val.id+'" value="'+val.precio+'" step="0.01" required ></div></div></td></tr>';
//	            		$("#body-grilla").append(options);
//	            	});
//	            });	            
//                $('.btn-confirmar').removeClass('disabled');
//			}else{
//				$('.btn-confirmar').addClass('disabled');				
//			}
//			$('#body-grilla-load').html('');
//		});
//	});
</script>