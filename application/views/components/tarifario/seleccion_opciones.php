<script type="text/javascript">
	<?php include 'funciones_none_block.js'; ?>
  	<?php include 'mensajes.js'; ?>
  	<?php include 'seteo_campos.js'; ?>
  	<?php include 'revision_campos_duplicados.js'; ?>
  	<?php include 'sumar_restar_secciones_campos.js'; ?>
  	<?php include 'grabar.js'; ?>
</script>

<form method="post" id="formUsuario" name="n_formUsuario" action="<?= base_url('tarifarioT/tarifario_seleccion_opciones/registrar') ?>">
	<div class="col-xs-12">
		<div class="wrapper wrapper-content animated fadeInRight">

			<div class="row">
				<div class="col-xs-12">

					<div class="ibox-content">
						<div class="row flex">
							<div class="col-xs-5 form-group">
								<!-- Obtengo lista del clientes al cargar Pagina. Al seleccionar un cliente (llamamamos a la funcion mostrarServicio), se habilita el campo de Opciones (en 0), y se muestra el servicio asociado 2 tambien.	-->
								<label>Cliente: </label><br>
								<select id="cliente" name="cliente" class="select-2 form-control filtro-group" onchange="confirmacionMsj1()">
									<option value="0">...</option>
									<?php
									if (count($clientes) > 0) {
										foreach ($clientes as $result) {
											echo ('<option value=' . $result->id . '>' . $result->nombre . '</option>');
										}
									} else {
										echo ('<option>No existen resultados.</option>');
									}
									?>
								</select>
							</div>
							<div id="secc_servicio" name="secc_servicio" class="col-xs-5 form-group">
								<label>Servicio: </label>
								<label id="servicio" class="form-control filtro-group"></label>
							</div>
							<input type="button" name="edit_registros" onclick="verRegistros()" value="Tarifas" class="btn btn-success col-xs-2">
						</div>
					</div>


					<!-- Luego de seleccionar el cliente, se muestra la lista de opciones (bultos, kilos, etc) para seleccionar, (Al elegir llamamos a la funcion habilitacionCamposA) -->
					<div class="ibox-content" id="seccion_opciones_tarifario" name="seccion_opciones_tarifario" style="display: none;">
						<div class="row">
							<div class="col-xs-6">
								<div class="form-group">
									<label>Opciones del tarifario:</label>
									<select id="opciones_tarifario" name="opciones_tarifario" class="form-control filtro-group" onchange="confirmacionMsj2()">
										<option value="0">...</option>
										<option value="1">Kilos (Kg)</option>
										<option value="2">Bultos (unidades)</option>
										<option value="3">Cobranza (%)</option>
										<option value="4">Valor Declarado</option>
										<option value="5">Metro Cubico</option>
										<option value="6">Palets</option>
										<option value="7">Gestión de Flota</option>
										<option value="8">WareHouse</option>
									</select>
								</div>
							</div>
						</div>
					</div>

					<div class="ibox-content" id="ubicaciones" name="ubicaciones" style="display: none;">
						<div class="row">
							<div class="col-xs-12">
								<label>Ubicaciones</label><br>
								<small>Si no elegije las ubicaciones, el sistema elijira por defecto "San Miguel de Tucuman (Capital - Tucuman)".</small>
							</div>
						</div><br>
						<div class="row">
							<div class="col-xs-5">
								<div class="form-group">
									<label>Zona A</label><br>
									<select id="zonaA" name="zonaA" class="select-2 form-control filtro-group" style="width:100%;" onchange="confirmacionMsj3(0)">
										<?php
										/*foreach ($zonas as $result) {
											if ($result->Trabaja) {
												echo ('<option value=' . $result->id_provincia_bandera . '>' . $result->provincia . '  (' . $result->ubicacion . ')</option>');
											}
										}*/

										foreach ($zonas_ as $zona) {
											echo ('<option value=' . $zona->id . '>' . $zona->nombre .'</option>');
										}
										?>
									</select>
								</div>
							</div>
							<div class="col-xs-5">
								<div class="form-group">
									<label>Zona B</label><br>
									<select id="zonaB" name="zonaB" class="select-2 form-control filtro-group" style="width:100%;" onchange="confirmacionMsj3(1)">
										<?php
										/*foreach ($zonas as $result) {
											if ($result->Trabaja) {
												echo ('<option value=' . $result->id_provincia_bandera . '>' . $result->provincia . '  (' . $result->ubicacion . ')</option>');
											}
										}*/

										foreach ($zonas_ as $zona) {
											echo ('<option value=' . $zona->id . '>' . $zona->nombre .'</option>');
										}

										?>
									</select>
								</div>
							</div>
						</div>
					</div>

<!--Campos de Kilos-->
					<div class="ibox-content" id="rangos_precios_kilos" name="rangos_precios_kilos" style="display: none">
						<div class="row campoDinamico_kilos">
							<label>Ingrese los valores por favor.</label><br><br>
							<div class="col-xs-3">
								<div class="form-group">
									<label>Desde</label>
									<input id="rinicio_kilos1" placeholder="Ej.: 10" type="number" name="rinicio_kilos1" onkeyup="verificar_duplicado_campos()" class="form-control filtro-group">
								</div>
							</div>
							<div class="col-xs-3">
								<div class="form-group">
									<label>Hasta</label>
									<input id="rfin_kilos1" placeholder="Ej.: 20" type="number" name="rfin_kilos1" onkeyup="verificar_duplicado_campos()" class="form-control filtro-group">
								</div>
							</div>
							<div class="col-xs-3">
								<div class="form-group">
									<label>Precio</label>
									<input style="" placeholder="$" type="number" id="precio_kilos1" name="precio_kilos1" onkeyup="verificar_duplicado_campos()" class="form-control filtro-group" step="0.01">
								</div>
							</div>
							<div class="col-xs-2">
								<div class="form-group">
									<label>Tipo</label>
									<select id="opciones_tipo_kilos1" name="opciones_tipo_kilos1" onchange="verificar_duplicado_campos()" class="form-control filtro-group">
										<option value="0">...</option>
										<option value="1">Bloque</option>
										<option value="2">Unidad</option>
									</select>
								</div>
							</div>
							<!--Boton + (para agregar campos) -->
							<div class="col-xs-1">
								<div class="form-group">
									<br>
									<a href="javascript:void(0);" class="agregar_campos btn btn-success btn-submit" title="Add field">+</a>
								</div>
							</div>
						</div>
					</div>
<!--fin de Campos de Kilos-->


<!--Campos de Bultos-->
					<div class="ibox-content" id="rangos_precios_bultos" name="rangos_precios_bultos" style="display: none">
						<div class="row campoDinamico_bultos">
							<label>Ingrese los valores por favor.</label><br><br>
							<div class="col-xs-3">
								<div class="form-group">
									<label>Desde</label>
									<input id="rinicio_bultos1" placeholder="Ej.: 10" type="number" name="rinicio_bultos1" onkeyup="verificar_duplicado_campos()" class="form-control filtro-group">
								</div>
							</div>
							<div class="col-xs-3">
								<div class="form-group">
									<label>Hasta</label>
									<input id="rfin_bultos1" placeholder="Ej.: 20" type="number" name="rfin_bultos1" onkeyup="verificar_duplicado_campos()" class="form-control filtro-group">
								</div>
							</div>
							<div class="col-xs-3">
								<div class="form-group">
									<label>Precio</label>
									<input style="" placeholder="$" type="number" id="precio_bultos1" name="precio_bultos1" onkeyup="verificar_duplicado_campos()" class="form-control filtro-group" step="0.01">
								</div>
							</div>
							<div class="col-xs-2">
								<div class="form-group">
									<label>Tipo</label>
									<select id="opciones_tipo_bultos1" name="opciones_tipo_bultos1" onchange="verificar_duplicado_campos()" class="form-control filtro-group">
										<option value="0">...</option>
										<option value="1">Bloque</option>
										<option value="2">Unidad</option>
									</select>
								</div>
							</div>
							<!--Boton + (para agregar campos) -->
							<div class="col-xs-1">
								<div class="form-group">
									<br>
									<a href="javascript:void(0);" class="agregar_campos btn btn-success btn-submit" title="Add field">+</a>
								</div>
							</div>
						</div>
					</div>

<!--fin de Campos de Bultos-->


<!-- Campos de Cobranza -->
					<div class="ibox-content" id="rangos_cobranza" name="rangos_cobranza" style="display: none">
						<div class="row campoDinamico_cobranza">
							<label>Ingrese los valores por favor.</label><br><br>
							<div class="col-xs-3">
								<div class="form-group">
									<label>Porcentaje</label>
									<input style="" placeholder="%" type="number" id="porcentaje_cobranza1" name="porcentaje_cobranza1" onkeyup="verificar_duplicado_campos()" class="form-control filtro-group" step="0.01">
								</div>
							</div>
							<!--Boton + (para agregar campos) -->
							<!--<div class="col-xs-1">
								<div class="form-group">
									<br>
									<a href="javascript:void(0);" class="agregar_campos btn btn-success btn-submit" title="Add field">+</a>
								</div>
							</div>-->
						</div>
					</div>
<!-- fin de Campos de Cobranza -->

<!-- Campos de Valor Declarado -->
					<div class="ibox-content" id="rangos_valor_declarado" name="rangos_cobranza" style="display: none">
						<div class="row campoDinamico_valor_declarado">
							<label>Ingrese los valores por favor.</label><br><br>
							<div class="col-xs-3">
								<div class="form-group">
									<label>Porcentaje</label>
									<input style="" placeholder="%" type="number" id="porcentaje_valor_declarado1" name="porcentaje_valor_declarado1" onkeyup="verificar_duplicado_campos()" class="form-control filtro-group" step="0.01">
								</div>
							</div>
							<!--Boton + (para agregar campos) -->
							<!--<div class="col-xs-1">
								<div class="form-group">
									<br>
									<a href="javascript:void(0);" class="agregar_campos btn btn-success btn-submit" title="Add field">+</a>
								</div>
							</div>-->
						</div>
					</div>
<!-- fin de Campos de Valor Declarado -->



<!-- Campos de Metro Cubico -->
					<div class="ibox-content" id="metro_cubico_precio" name="metro_cubico_precio" style="display: none;">
						<div class="row campoDinamico_metro_cubico">
							<label>Ingrese los valores por favor.</label><br><br>
							<div class="col-xs-3">
								<div class="form-group">
									<label>Desde</label>
									<input id="rinicio_mc1" placeholder="Ej.: 10" type="number" name="rinicio_mc1" onkeyup="verificar_duplicado_campos()" class="form-control filtro-group">
								</div>
							</div>
							<div class="col-xs-3">
								<div class="form-group">
									<label>Hasta</label>
									<input id="rfin_mc1" placeholder="Ej.: 20" type="number" name="rfin_mc1" onkeyup="verificar_duplicado_campos()" class="form-control filtro-group">
								</div>
							</div>
							<div class="col-xs-3">
								<div class="form-group">
									<label>Precio</label>
									<input style="" placeholder="$" type="number" id="precio_mc1" name="precio_mc1" onkeyup="verificar_duplicado_campos()" class="form-control filtro-group" step="0.01">
								</div>
							</div>
							<div class="col-xs-2">
								<div class="form-group">
									<label>Tipo</label>
									<select id="opciones_tipo_mc1" name="opciones_tipo_mc1" onchange="verificar_duplicado_campos()" class="form-control filtro-group">
										<option value="0">...</option>
										<option value="1">Bloque</option>
										<option value="2">Unidad</option>
									</select>
								</div>
							</div>
							<!--Boton + (para agregar campos) -->
							<div class="col-xs-1">
								<div class="form-group">
									<br>
									<a href="javascript:void(0);" class="agregar_campos btn btn-success btn-submit" title="Add field">+</a>
								</div>
							</div>
						</div>
					</div>
<!-- fin de Campos de Metro Cubico -->


<!-- Campos de Palets -->
					<div class="ibox-content" id="rangos_precios_palets" name="rangos_precios_palets" style="display: none">
						<div class="row campoDinamico_palets">
							<label>Ingrese los valores por favor.</label><br><br>
							<div class="col-xs-3">
								<div class="form-group">
									<label>Desde</label>
									<input id="rinicio_palets1" placeholder="Ej.: 10" type="number" name="rinicio_palets1" onkeyup="verificar_duplicado_campos()" class="form-control filtro-group">
								</div>
							</div>
							<div class="col-xs-3">
								<div class="form-group">
									<label>Hasta</label>
									<input id="rfin_palets1" placeholder="Ej.: 20" type="number" name="rfin_palets1" onkeyup="verificar_duplicado_campos()" class="form-control filtro-group">
								</div>
							</div>
							<div class="col-xs-3">
								<div class="form-group">
									<label>Precio</label>
									<input style="" placeholder="$" type="number" id="precio_palets1" name="precio_palets1" onkeyup="verificar_duplicado_campos()" class="form-control filtro-group" step="0.01">
								</div>
							</div>
							<div class="col-xs-2">
								<div class="form-group">
									<label>Tipo</label>
									<select id="opciones_tipo_palets1" name="opciones_tipo_palets1" onchange="verificar_duplicado_campos()" class="form-control filtro-group">
										<option value="0">...</option>
										<option value="1">Bloque</option>
										<option value="2">Unidad</option>
									</select>
								</div>
							</div>
							<!--Boton + (para agregar campos) -->
							<div class="col-xs-1">
								<div class="form-group">
									<br>
									<a href="javascript:void(0);" class="agregar_campos btn btn-success btn-submit" title="Add field">+</a>
								</div>
							</div>
						</div>
					</div>

<!-- fin de Campos de Palets -->


<!-- Campos de Gestion de Flota -->
					<div class="ibox-content" id="horarios_gestion_flota" name="horarios_gestion_flota" style="display: none;">
						<div class="row campoDinamico_gestion_flota">
							<label>Ingrese los valores por favor.</label><br><br>
							<div class="col-xs-3">
								<div class="form-group" >
									<label>Vehiculo // Hora</label>
									<select id="opcion_horas_gf1" name="opcion_horas_gf1" onchange="verificar_duplicado_campos()" class="form-control filtro-group">
										<option value="0">...</option>
									</select>
								</div>
							</div>
							<div class="col-xs-3">
								<div class="form-group">
									<label>Precio</label>
									<input placeholder="$" type="number" id="precio_gf1" name="precio_gf1" onkeyup="verificar_duplicado_campos()" class="form-control filtro-group" step="0.01">
								</div>
							</div>
							<div class="col-xs-1">
								<div class="form-group">
									<br>
									<a href="javascript:void(0);" id="etiqueta_a_gf" style="width: 55%" class="agregar_campos btn btn-success btn-submit" title="Add field">+</a>
								</div>
							</div>
						</div>
					</div>
<!-- fin de Campos de Gestion de Flota -->

<!-- Campos de Warehouse -->
					<div class="ibox-content" id="cantidad_precio_warehouse" name="cantidad_precio_warehouse" style="display: none;">
						<div class="row campoDinamico_warehouse">
							<label>Ingrese los valores por favor.</label><br><br>
							<div class="col-xs-3">
								<div class="form-group">
									<label>Cantidad</label>
									<input id="cantidad_wh1" placeholder="Ej.: 10" type="number" name="cantidad_wh1" onkeyup="verificar_duplicado_campos()" class="form-control filtro-group">
								</div>
							</div>
							<div class="col-xs-3">
								<div class="form-group">
									<label>Precio</label>
									<input placeholder="$" type="number" id="precio_wh1" name="precio_wh1" onkeyup="verificar_duplicado_campos()" class="form-control filtro-group" step="0.01">
								</div>
							</div>
							<!--Boton + (para agregar campos) -->
							<div class="col-xs-1">
								<div class="form-group">
									<br>
									<a href="javascript:void(0);" class="agregar_campos btn btn-success btn-submit" title="Add field">+</a>
								</div>
							</div>
						</div>
					</div>
<!-- fin de Campos de WareHouse -->



<!-- Boton Grabado -->
					<div class="ibox-content" id="registro" name="registro" style="display: none">
						<div class="row">
							<label>Al terminar de ingregar los datos haga click en el boton "Grabar".</label><br>
							<p>El sistema verificara que los datos no se repitan, y que no se encuentren ya registrados.</p><br>
							<div class="col-xs-7">
								<div class="control-group">
									<div class="controls">
										<br>
										<input type="submit" onclick="grabar_parte1()" value="Grabar" style="margin-left: 200px" class="btn btn-success btn-submit">
									</div>
								</div>
							</div>
						</div>
					</div>
<!-- fin de Boton Grabado -->

<!--input cantidad campos -->
					<input hidden type="number" id="cantidad_campos" name="cantidad_campos">

<!--fin input cantidad campos -->
				</div>
			</div>
		</div>
	</div>
</form>


<script>
	var clientes_listado = <?php echo (json_encode($clientes)); ?>;
//var zonas = <?php //echo (json_encode($zonas)); ?>;
	//console.log(clientes_listado);

	var msj = <?php echo (json_encode($mensaje)); ?>;
	

	var zonas2 = <?php echo (json_encode($zonas_)); ?>;
	//console.log(zonas2);


	if(msj != null && msj != ''){
		alert('Aviso: \nLos datos ' + msj);
	}


	var valueComboCliente = -1;
	var valueComboOpciones = 0;
	var countX = 1;

	var matriz1 = [];

	var zonaA_value = 17;
	var zonaB_value = 17;

	$(document).ready(function() {
		//(1) agrego libreria a la class .select-2
		$('.select-2').select2();

		//(2) seteo zonas
		seteo_campos_zonas();
	});


	function verRegistros() {
		var id_cliente = parseInt(document.getElementById("cliente").value);

		if (id_cliente == 0) {
			alert('Debe seleccionar un cliente primero, para poder explorar sus tarifas cargadas');
		} else {
			window.location = "<?php echo base_url() ?>" + 'tarifarioT/tarifario_seleccion_opciones/listar?id=' + id_cliente + '&bandera_edicion=NULL&bandera_opcion_tarifario=0';
		}
	}	


	var options_gestion_flota = ``;



</script>
