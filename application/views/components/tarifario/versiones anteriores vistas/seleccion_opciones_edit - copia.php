<div class="col-xs-12">
    <div class="ibox-content">
		
		<?php     
			echo form_open(current_url(), array('class'=>""));
			echo form_hidden('enviar_form','1');
		?>

	<?php

	if(!empty($tarifa_kg)){
		$lista = $tarifa_kg;
	}

	if(!empty($tarifa_bultos)){
		$lista = $tarifa_bultos;
	}

	if(!empty($tarifa_palets)){
		$lista = $tarifa_palets;
	}
	

	foreach ($lista as $reg) {
		echo form_hidden('id',$reg->id);
		echo form_hidden('cliente',$reg->cliente);
	?>
		
		<div class="form-group">
			<label>Zona A</label><br>
			<select id="zonaA" name="c_zonaA" class="select-2 form-control filtro-group" style="width:300px;">
            	<option value="1">...</option>
		<?php 				
				foreach ($lista_zonas as $lz) {
					echo ('<option value='.$lz->id_provincia_bandera.'>'.$lz->provincia. '  ('.$lz->ubicacion.')</option>');  
				}
		?>
            </select>
            <br><br>
		</div>
		<div class="form-group">
			<label>Zona B</label><br>
			<select id="zonaB" name="c_zonaB" class="select-2 form-control filtro-group" style="width:300px;">
            	<option value="1">...</option>
		<?php 				
				foreach ($lista_zonas as $lz) {
					echo ('<option value='.$lz->id_provincia_bandera.'>'.$lz->provincia. '  ('.$lz->ubicacion.')</option>');
				}
		?>
            </select>
            <br><br>
		</div>		

		<div id="kilogramos" style="display: block;">
	        <div class="form-group">
	            <label for="desde">Desde<span class="required">*</span></label>
	            <input required id="desde" name="c_desde" type="text" class="form-control" placeholder="Desde" value="<?php echo $reg->desde_cant_unid_kg ?>" />
	        </div>
	        <div class="form-group">
	            <label for="hasta">Hasta<span class="required">*</span></label>
	            <input required id="hasta" name="c_hasta" type="text" class="form-control" placeholder="Hasta" value="<?php echo $reg->hasta_cant_unid_kg ?>" />
	        </div>
	        <div class="form-group">
	            <label for="precio">Precio<span class="required">*</span></label>
	            <input required id="precio" name="c_precio" type="text" class="form-control" placeholder="Precio" value="<?php echo $reg->precio_Corte_kg ?>" />
	        </div>        
	        <div class="form-group">
	            <label for="tipo">Tipo<span class="required">*</span></label>
	            <select required id="tipo" name="c_tipo" class="form-control">
	            	<option value="0">...</option>           	
	            	<option value="1">Unidad</option>
	            	<option value="2">Corte Minimo</option>
	            	<option value="3">Corte Comun</option>
	            </select>
	        </div> 
        </div>  

		<div id="bultos" style="display: none;">
	        <div class="form-group">
	            <label for="desde">Desde<span class="required">*</span></label>
	            <input required id="desde" name="c_desde" type="text" class="form-control" placeholder="Desde" value="<?php echo $reg->desde_cant_unid_bultos ?>" />
	        </div>
	        <div class="form-group">
	            <label for="hasta">Hasta<span class="required">*</span></label>
	            <input required id="hasta" name="c_hasta" type="text" class="form-control" placeholder="Hasta" value="<?php echo $reg->hasta_cant_unid_bultos ?>" />
	        </div>
	        <div class="form-group">
	            <label for="precio">Precio<span class="required">*</span></label>
	            <input required id="precio" name="c_precio" type="text" class="form-control" placeholder="Precio" value="<?php echo $reg->precio_Corte_bultos ?>" />
	        </div>        
	        <div class="form-group">
	            <label for="tipo">Tipo<span class="required">*</span></label>
	            <select required id="tipo" name="c_tipo" class="form-control">
	            	<option value="0">...</option>           	
	            	<option value="1">Unidad</option>
	            	<option value="2">Corte Minimo</option>
	            	<option value="3">Corte Comun</option>
	            </select>
	        </div> 
        </div>  

       <div id="palets" style="display: none;">
	        <div class="form-group">
	            <label for="desde">Cantidad<span class="required">*</span></label>
	            <input required id="cantidad" name="c_cantidad" type="text" class="form-control" placeholder="Cantidad" value="<?php echo $reg->cantidad_palet ?>" />
	        </div>
	        <div class="form-group">
	            <label for="precio">Precio<span class="required">*</span></label>
	            <input required id="precio" name="c_precio" type="text" class="form-control" placeholder="Precio" value="<?php echo $reg->precio_palet ?>" />
	        </div>        
        </div>  

	<?php
	}
	?>
      
		<div class="control-group">
			<div class="controls">
				<?php echo form_button(array('type'  =>'submit','value' =>'Guardar Cambios','name'  =>'submit','class' =>'btn btn-success'), "<i class='fa fa-floppy-o'></i> Guardar"); ?>
				
				<a class="btn btn-danger" href="<?php
				foreach ($lista as $reg) {
				 echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/listar/'.$reg->cliente; 
				}
				 ?>"><i class="fa fa-arrow-circle-left"></i> Volver</a>
			</div>
		</div>

		<?php echo form_close(); ?>

	</div>
</div>


<script type="text/javascript">
	var valor_bandera_tipo = 0;
	var lista = null;

	var zona1 = 0;
	var zona2 = 0;


	var tarifa_kg = <?php echo(json_encode($tarifa_kg));?>;
	if(tarifa_kg.length !== 0){
		valor_bandera_tipo = 1; 
		lista = tarifa_kg;
	}	

	var tarifa_bultos = <?php echo(json_encode($tarifa_bultos));?>;
	if(tarifa_bultos.length !== 0){
		valor_bandera_tipo = 2; 
		lista = tarifa_bultos;
	}

	var tarifa_palets = <?php echo(json_encode($tarifa_palets));?>;
	if(tarifa_palets.length !== 0){
		valor_bandera_tipo = 6; 
		lista = tarifa_palets;
	}


	$(document).ready(function(){

		// 1)
		for (var i = 0; i < lista.length; i++) {
			zona1 = parseInt(lista[i].zonaA);
			zona2 = parseInt(lista[i].zonaB);

			switch(valor_bandera_tipo){
	            case 1: 
	            	bultos.remove();
	                palets.remove();  

	            	valor_bandera_tipo = parseInt(lista[i].bandera_Corte_kg);
	            	document.getElementById("tipo").value = valor_bandera_tipo;
	                kilogramos.style.display = "block";
	                break
	            case 2:
	                palets.remove();
	                kilogramos.remove();

	            	valor_bandera_tipo = parseInt(lista[i].bandera_Corte_Bultos);
	            	document.getElementById("tipo").value = valor_bandera_tipo;

	                bultos.style.display = "block";
	                break
	            case 3:

	                break;
	            case 6:
	            	bultos.remove();
	                kilogramos.remove();

	                palets.style.display = "block";
	                break;
	            default:
	            //code block
	            break;
        	}
        	
		}


		$('#zonaA').val(zona1).trigger('change.select2');
		$('#zonaB').val(zona2).trigger('change.select2');


		// 2)
		$('.select-2').select2();

	});


	var resultado_camposRepetidos = parseInt(<?php echo(json_encode($consulta));?>);

	if(!isNaN(resultado_camposRepetidos)){
		if(resultado_camposRepetidos != 0){
			alert('\nEsta ingresando valores en 0 o es posible que este ingresando 1 o 2 valores que ya se encuentran registrados en la base de datos. \nPor favor ingrese otros valores.');
		}			
	}
	
</script>