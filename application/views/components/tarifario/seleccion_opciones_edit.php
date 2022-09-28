<script type="text/javascript">
	<?php include 'mensajes.js'; ?>
  	<?php include 'seteo_campos.js'; ?>
  	<?php include 'revision_campos_duplicados.js'; ?>
  	<?php include 'edicion.js'; ?>
</script>
<a id="enlace" href="<?php echo base_url() . 'tarifarioT/tarifario_seleccion_opciones/listar?id='. $tarifa[0]->cliente . '&bandera_edicion=NULL&bandera_opcion_tarifario=0' ?>" class="btn btn-success" style="position: relative; right: -830px;">Regresar</a>	


<form method="post" id="form_edicion" name="form_edicion" action="<?= base_url('tarifarioT/tarifario_seleccion_opciones/edicion') ?>">
	<div class="col-xs-12">
	    <div class="ibox-content">

		    <div class="form-group">
	            <label>Zona A</label><br>
	            <select id="zonaA" name="zonaA" class="select-2 form-control filtro-group" style="width:300px;">
					<?php
						foreach ($lista_zonas as $zona) {
							echo ('<option value=' . $zona->id . '>' . $zona->nombre .'</option>');
						}
					?>
	            </select>
	            <br><br>
	        </div>
	        <div class="form-group">
	            <label>Zona B</label><br>
	            <select id="zonaB" name="zonaB" class="select-2 form-control filtro-group" style="width:300px;">
					<?php
						foreach ($lista_zonas as $zona) {
							echo ('<option value=' . $zona->id . '>' . $zona->nombre .'</option>');
						}
					?>
	            </select>
	            <br><br>
	        </div>  

	        <div id="campos_seccion"></div>


			<div class="row">
				<label>Al terminar la edicion de los valores presione el boton "Editar".</label><br> <!--FUNCION Q VALIDE LIMITES y TIPO DE DATOS-->
				<p>El sistema verificara que los datos no se repitan, y que no se encuentren ya registrados.</p><br>
				<div class="col-xs-7">
					<div class="control-group">
						<div class="controls">
							<br>
							<!--<input type="submit" value="Editar" style="margin-left: 200px" class="btn btn-success btn-submit">-->
							<button onClick="edicion_parte1()" style="margin-left: 200px" class="btn btn-success btn-submit">Editar</button>

						</div>
					</div>
				</div>
			</div>

	    </div>
	<div>

	<input hidden id="cliente" name="cliente" type="number">
	<input hidden id="opcion_tarifario" name="opcion_tarifario" type="number">
	<input hidden id="identificador_tarifa" name="identificador_tarifa" type="number">
</form>



<script type="text/javascript">
	var tarifa = <?php echo(json_encode($tarifa));?>;
	var id_cliente = tarifa[0].cliente;
	var opcion_elegida = parseInt(<?php echo(json_encode($opcion_tarifario));?>);
	var valueComboOpciones = opcion_elegida;
	var id_tarifa = tarifa[0].id;

	setear_id_cliente(id_cliente);
	setear_opcion_elegida(opcion_elegida);
	setear_codigo_tarifa(id_tarifa);

	var tarifa_zonaA = tarifa[0].zonaA;
	var tarifa_zonaB = tarifa[0].zonaB;
	var zonaA_value = 0;
	var zonaB_value = 0;

	setear_zonas_tarifa(tarifa_zonaA, tarifa_zonaB);

	//console.log(opcion_elegida);

	cargar_campos_edicion(opcion_elegida,tarifa);




</script>