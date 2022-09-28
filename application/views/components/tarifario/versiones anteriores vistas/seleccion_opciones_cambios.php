<!--GUI de Comerciales, donde elije que campos podran llenarse en Paqueteria-->
<style>
	.flex {
		display: flex;
		align-items: center;
		flex-wrap: nowrap;
	}
</style>


<form method="post" id="formUsuario" name="n_formUsuario">
	<div class="col-xs-12">
		<div class="wrapper wrapper-content animated fadeInRight">

			<div class="row">
				<div class="col-xs-12">

					<div class="ibox-content">
						<div class="row flex">
							<div class="col-xs-5 form-group">
								<!-- Obtengo lista del clientes al cargar Pagina. Al seleccionar un cliente (llamamamos a la funcion mostrarServicio), se habilita el campo de Opciones (en 0), y se muestra el servicio asociado 2 tambien.	-->
								<label>Cliente: </label><br>
								<select id="cliente" name="c_cliente" class="select-2 form-control filtro-group" onchange="confirmacionMsj1()">
									<option value="0"></option>
									<?php
									if ($datos_clientes) {
										foreach ($clientes as $result) {
											echo ('<option value=' . $result->id . '>' . $result->nombre . '</option>');
										}
									} else {
										echo ('<option>No existen resultados.</option>');
									}
									?>
								</select>
							</div>
							<div id="secc_servicio" name="c_secc_servicio" class="col-xs-5 form-group">
								<label>Servicio: </label>
								<label id="servicio" class="form-control filtro-group"></label>
							</div>
							<input type="button" name="edit_registros" onclick="verRegistros()" value="Tarifas" class="btn btn-success col-xs-2">
						</div>
					</div>


					<!-- Luego de seleccionar el cliente, se muestra la lista de opciones (bultos, kilos, etc) para seleccionar, (Al elegir llamamos a la funcion habilitacionCamposA) -->
					<div class="ibox-content" id="secc_opciones" name="c_secc_" style="display: none;">
						<div class="row">
							<div class="col-xs-6">
								<div class="form-group">
									<label>Opciones del tarifario:</label>
									<select id="selectCampos" name="c_selectCampos" class="form-control filtro-group" onchange="confirmacionMsj2()">
										<option value="0">...</option>
										<option value="1">Kilos (Kg)</option>
										<option value="2">Bultos (unidades)</option>
										<option value="3">WareHouse</option>
										<option value="4">Cobranza (%)</option>
										<option value="5">Gestión de Flota</option>
										<option value="6">Palets</option>
									</select>
								</div>
							</div>

						</div>
					</div>

					<!--Luego de seleccionar el tipo de Opcion, se habilita el campo de confirmacion (con opciones si/no). Llama a la funcion habilitacionCamposB. -->
					<div class="ibox-content" id="primera_confirmacion_datos" name="c_primera_confirmacion_datos" style="display: none;">
						<div class="row">
							<div class="col-xs-12">
								<label id="nombre_opcionTarifario"></label><br>
								<label for="radioA" class="radio-inline">
									<input type="radio" id="radioA" name="c_radio_eleccion" class="seleccionRespuesta" value="1" onclick="habilitacionCamposB(0)">
									Si
								</label>

								<label for="radioB" class="radio-inline">
									<input type="radio" id="radioB" name="c_radio_eleccion" class="seleccionRespuesta" value="0" onclick="habilitacionCamposB(0)">
									No
								</label>

							</div>
						</div>
					</div>

					<!--Luego de seleccionar la respuesta SI, se habilitan los campos de ZONA (Desde - Hasta que zona)-->
					<div class="ibox-content" id="ubicaciones" name="c_ubicaciones" style="display: none;">
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
									<select id="zonaA" name="c_zonaA" class="select-2 form-control filtro-group" style="width:100%;" onchange="confirmacionMsj3(0)">
										<?php
										foreach ($zonas as $result) {
											if ($result->Trabaja) {
												echo ('<option value=' . $result->id_provincia_bandera . '>' . $result->provincia . '  (' . $result->ubicacion . ')</option>');
											}
										}
										?>
									</select>
								</div>
							</div>
							<div class="col-xs-5">
								<div class="form-group">
									<label>Zona B</label><br>
									<select id="zonaB" name="c_zonaB" class="select-2 form-control filtro-group" style="width:100%;" onchange="confirmacionMsj3(1)">
										<?php
										foreach ($zonas as $result) {
											if ($result->Trabaja) {
												echo ('<option value=' . $result->id_provincia_bandera . '>' . $result->provincia . '  (' . $result->ubicacion . ')</option>');
											}
										}
										?>
									</select>
								</div>
							</div>
						</div>
					</div>



					<!-- PALETS -->
					<!--Luego de seleccionar la respuesta SI, se habilitan los campos Cantidad y Precio. (Funcion: revision de campos llenos) Mientras se completan los campos, constantemente se evalua si los campos estan llenos. Una vez que los campos estan llenos, se evalua si los datos que se acaban de escribir ya se encuentran en DBs.-->
					<div class="ibox-content" id="cantidad_precio" name="c_cantidad_precio" style="display: none;">
						<div class="row campoDinamico_2campos">
							<label>Ingrese los valores por favor.</label><br><br>
							<div class="col-xs-3">
								<div class="form-group">
									<label>Cantidad</label>
									<input id="cantidad1" placeholder="Ej.: 10" type="number" name="c_cantidad1" onkeyup="revisionCamposLlenos()" class="form-control filtro-group">
								</div>
							</div>
							<div class="col-xs-3">
								<div class="form-group">
									<label>Precio</label>
									<input placeholder="$" type="number" id="precioP1" name="c_precioP1" onkeyup="revisionCamposLlenos()" class="form-control filtro-group" step="0.01">
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


					<!-- WAREHOUSE -->
					<!--Luego de seleccionar la respuesta SI, se habilitan los campos Cantidad y Precio. (Funcion: revision de campos llenos) Mientras se completan los campos, constantemente se evalua si los campos estan llenos. Una vez que los campos estan llenos, se evalua si los datos que se acaban de escribir ya se encuentran en DBs.-->
					<div class="ibox-content" id="warehouse_precio" name="c_warehouse" style="display: none;">
						<div class="row campoDinamico_2campos">
							<div class="col-xs-6">
								<div class="form-group">
									<label>Tarifa única y plana por posición ocupada</label>
									<input placeholder="$" type="number" id="precioP1" name="c_precioP1" onkeyup="revisionCamposLlenos()" class="form-control filtro-group" step="0.01">
								</div>
							</div>

						</div>
					</div>


					<!---->

					<!--Luego de seleccionar la respuesta SI, se habilitan los campos de rangos (Desde - Hasta), Precio y Tipo (bandera). (Funcion: revision de campos llenos) Mientras se completan los campos, constantemente se evalua si los campos estan llenos. Una vez que los campos estan llenos, se evalua si los datos que se acaban de escribir ya se encuentran en DBs.-->
					<div class="ibox-content" id="rangos_precios_bultos" name="c_rangos_precios_bultos" style="display: none">
						<div class="row campoDinamico_4campos">
							<label>Ingrese los valores por favor.</label><br><br>
							<div class="col-xs-3">
								<div class="form-group">
									<label>Desde</label>
									<input id="rinicio1" placeholder="Ej.: 10" type="number" name="c_rinicio1" onkeyup="revisionCamposLlenos()" class="form-control filtro-group">
								</div>
							</div>
							<div class="col-xs-3">
								<div class="form-group">
									<label>Hasta</label>
									<input id="rfin1" placeholder="Ej.: 20" type="number" name="c_rfin1" onkeyup="revisionCamposLlenos()" class="form-control filtro-group">
								</div>
							</div>
							<div class="col-xs-3">
								<div class="form-group">
									<label>Precio</label>
									<input style="" placeholder="$" type="number" id="precio1" name="c_precio1" onkeyup="revisionCamposLlenos()" class="form-control filtro-group" step="0.01">
								</div>
							</div>
							<div class="col-xs-2">
								<div class="form-group">
									<label>Tipo</label>
									<select id="opciones_tipo1" name="c_opciones_tipo1" onchange="revisionCamposLlenos()" class="form-control filtro-group">
										<option value="0" disabled>Seleccione</option>
										<option value="1">Unidad</option>
										<option value="2">Corte Minimo</option>
										<option value="3">Corte Comun</option>
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
					<!--Al completar los campos se habilita Seccion msj y boton evaluar-->
					<div class="ibox-content" id="evaluacion" name="c_evaluacion" style="display: none">
						<div class="row">
							<label id="msjA" style=""></label><br><br>
							<div class="col-xs-7" id="evaluar" name="c_registrar" style="display: none">
								<div class="control-group">
									<div class="controls">
										<input id="boton_evaluar" name="b_boton_evaluar" class="btn btn-success" type="button" name="" value="Evaluar" style="margin-left: 200px">
									</div>
								</div>
							</div>
						</div>
					</div>
					<!--Si los campos son correctos, se habilita el boton registrar. El boton registrar graba los datos cargados. -->
					<div class="ibox-content" id="aviso_valor_registrado" style="display: none;">
						<div class="row">
							<div class="col-xs-8">
								<div class="col-xs-12">
									<div class="form-group">
										<label id="msjB"></label>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="ibox-content" id="registrar_cancelar" name="c_registrar" style="display: none">
						<div class="row">
							<label>Presione Grabar por favor.</label><br><br>
							<div class="col-xs-7">
								<div class="control-group">
									<div class="controls">
										<br>
										<input type="submit" value="Grabar" style="margin-left: 200px" class="btn btn-success btn-submit">
									</div>
								</div>
							</div>
						</div>
					</div>

					<!-- TEST: desde aqui, lo siguiente son display para evaluar variables y funcionamiento-->

					<div class="ibox-content" id="resulado_carga" style="display: none;">
						<div class="row">
							<div class="col-xs-8">
								<div class="col-xs-12">
									<div class="form-group">
										<label>
											<?php

											?>
										</label>
									</div>
								</div>
							</div>
						</div>
					</div>


					<div class="ibox-content" id="" style="display: none">
						<div class="row">
							<div class="col-xs-8">
								<div class="col-xs-12">

									<div class="form-group">

										<br><br><br><br><br><br><br><br><br><br><br><br>
										<label id="">TEST - AVISOS:</label>
										<label>
											<?php

											//echo('****');
											//print_r($test);
											?>
										</label>
									</div>

								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>


</form>



<script type="text/javascript">
	//Declaro V.G.: para Campos dinamicos (countX = cantidad campos)
	var countX = 1;
	//V.G. - Para Evaluar seleccion de CLiente
	var valueComboCliente = 0;
	//V.G. - Para Evaluar seleccion de Tipo de opcion	
	var valueComboOpciones = 0;
	var valueComboOpciones2 = 0;
	var valueComboZonaA = 0;
	var valueComboZonaB = 0;
	//V.G. - Bandera para saber que resultado mostrar previo al Boton de Grabar
	var banderaResultado = 0;
	// Definicion de limites maximo de valor Hasta para cada TIpo de opcion
	var limitea1 = 0;
	var limiteb1 = 0;
	var limitec1 = 0;

	var limitea2 = 1;
	var limiteb2 = 500000;
	var limitec2 = 0;

	var limiteDecimal = 0.01;


	// Evalua si se eligio el cliente. Si -> habilita los campos de opciones (bultos, kilos, etc) y muestra el servicio. // Call: deshabilitarConjuntoCampos()
	function mostrarServicio() {
		var id_cliente = parseInt(document.getElementById("cliente").value);
		deshabilitarConjuntoCampos(0);
		primera_confirmacion_datos.style.display = "none";

		if (id_cliente == 0) {
			document.getElementById('servicio').innerHTML = '...';
			secc_opciones.style.display = "none";
		} else {
			document.getElementById("selectCampos").value = "0";
			valueComboOpciones = 0;

			$('input[name=c_radio_eleccion]').attr('checked', false);

			var lista_clientes = <?php echo (json_encode($clientes)); ?>;

			for (var i = 0; i < lista_clientes.length; i++) {
				var idCliente_lista = parseInt(lista_clientes[i].id);
				var servicio = lista_clientes[i].servicio;

				if (id_cliente == idCliente_lista) {
					document.getElementById('servicio').innerHTML = servicio;
					secc_opciones.style.display = "block";
				}
			}
		}
	}

	//Evalua si se selecciono la Opcion. Si -> muestra el siguiente display (confirmacion). // Call: deshabilitarConjuntoCampos()
	function habilitacionCamposA() {
		deshabilitarConjuntoCampos(0);

		var seleccionCB_tipoOpcion = parseInt(document.getElementById("selectCampos").value);
		$('input[name=c_radio_eleccion]').attr('checked', false);

		if (seleccionCB_tipoOpcion == 0) {
			primera_confirmacion_datos.style.display = "none";
		} else {
			var combo = document.getElementById("selectCampos");
			var selected = combo.options[combo.selectedIndex].text;
			var mensaje = '¿Desea que el cliente pueda calcular su tarifario en base a '

			document.getElementById('nombre_opcionTarifario').innerHTML = mensaje + selected + '?';
			primera_confirmacion_datos.style.display = "block";
		}
	}

	//Se evalua si se marco el Radio de Pregunta. Si (marca opcion "si") -> se mustran los campos de rangos. // Call: deshabilitarConjuntoCampos()
	function habilitacionCamposB(ubicacionActive) {
		deshabilitarConjuntoCampos(ubicacionActive);

		var seleccion = parseInt($('input[class="seleccionRespuesta"]:checked').val());

		valueComboOpciones = parseInt(document.getElementById("selectCampos").value);

		if (seleccion == 1) {

			if (!ubicacionActive) {
				$('#zonaA').val('2401').trigger('change.select2');
				$('#zonaB').val('2401').trigger('change.select2');
				ubicaciones.style.display = "block";
			}

			switch (valueComboOpciones) {
				case 1:
					$('#rinicio1').val("");
					$('#rfin1').val("");
					$('#precio1').val("");
					document.getElementById("opciones_tipo1").value = "0";

					rinicio1.step = "0.01";
					rfin1.step = "0.01";

					rangos_precios_bultos.style.display = "block";
					break;
				case 2: // Bultos	
					$('#rinicio1').val("");
					$('#rfin1').val("");
					$('#precio1').val("");
					document.getElementById("opciones_tipo1").value = "0";

					rfin1.max = limiteb2;

					rangos_precios_bultos.style.display = "block";

					break;

				case 3: // Warehouse	
					$('#precio1').val("");
					warehouse_precio.style.display = "block";

					break;
				case 4:
					$('#rinicio1').val("");
					$('#rfin1').val("");
					$('#precio1').val("");
					document.getElementById("opciones_tipo1").value = "0";

					rinicio1.step = "0.01";
					rfin1.step = "0.01";

					precio1.placeholder = "%";

					rangos_precios_bultos.style.display = "block";
					break;
				case 6:
					$('#cantidad1').val("");
					$('#precioP1').val("");

					cantidad_precio.style.display = "block";

					break;
				default:

					break;
			}

		} else {
			deshabilitarConjuntoCampos(0);
		}
	}

	//	La funcion crea los nuevos botones de Rango, Precio y bandera. Los agrega en el formulario y lleva contador de campos. // Call: modificar_nombres_camposDinamicos()
	$(document).ready(function() {
		var maxField = 10;
		var addButton = $('.agregar_campos');
		//var wrapper = $('#rangos_precios_bultos'); 

		$(addButton).click(function() {

			if (countX < maxField) {
				countX++;

				switch (valueComboOpciones) {
					case 1:
						var wrapper = $('#rangos_precios_bultos');
						var fieldHTML = '<div class="row campoDinamico_4campos campo_agregado"><div class="col-xs-3"><div class="form-group"><label>Desde</label><input id="rinicio' + countX + '" placeholder="Ej.: 10" type="number" name="c_rinicio' + countX + '" onkeyup="revisionCamposLlenos()" class="form-control filtro-group" step="0.01"></div></div><div class="col-xs-3"><div class="form-group"><label>Hasta</label><input id="rfin' + countX + '" placeholder="Ej.: 20" type="number" name="c_rfin' + countX + '" onkeyup="revisionCamposLlenos()" class="form-control filtro-group" step="0.01"></div></div><div class="col-xs-3"><div class="form-group"><label>Precio</label><input style="" placeholder="$" type="number" id="precio' + countX + '" name="c_precio' + countX + '" onkeyup="revisionCamposLlenos()" class="form-control filtro-group" step="0.01"></div></div><div class="col-xs-2"><div class="form-group"><label>Tipo</label><select id="opciones_tipo' + countX + '" name="c_opciones_tipo' + countX + '" onchange="revisionCamposLlenos()" class="form-control filtro-group"><option value="0">...</option><option value="1">Unidad</option><option value="2">Corte Minimo</option><option value="3">Corte Comun</option></select></div></div><div class="col-xs-1"><div class="form-group"><br><a href="javascript:void(0);" class="remover_campos btn btn-success btn-submit" title="Remove field" name="c_signoMenos">-</a></div></div></div>';
						break;
					case 2: // Bultos
						var wrapper = $('#rangos_precios_bultos');
						var fieldHTML = '<div class="row campoDinamico_4campos campo_agregado"><div class="col-xs-3"><div class="form-group"><label>Desde</label><input id="rinicio' + countX + '" placeholder="Ej.: 10" type="number" name="c_rinicio' + countX + '" onkeyup="revisionCamposLlenos()" class="form-control filtro-group"></div></div><div class="col-xs-3"><div class="form-group"><label>Hasta</label><input id="rfin' + countX + '" placeholder="Ej.: 20" type="number" name="c_rfin' + countX + '" onkeyup="revisionCamposLlenos()" class="form-control filtro-group" max="' + limiteb2 + '"></div></div><div class="col-xs-3"><div class="form-group"><label>Precio</label><input style="" placeholder="$" type="number" id="precio' + countX + '" name="c_precio' + countX + '" onkeyup="revisionCamposLlenos()" class="form-control filtro-group" step="0.01"></div></div><div class="col-xs-2"><div class="form-group"><label>Tipo</label><select id="opciones_tipo' + countX + '" name="c_opciones_tipo' + countX + '" onchange="revisionCamposLlenos()" class="form-control filtro-group"><option value="0">...</option><option value="1">Unidad</option><option value="2">Corte Minimo</option><option value="3">Corte Comun</option></select></div></div><div class="col-xs-1"><div class="form-group"><br><a href="javascript:void(0);" class="remover_campos btn btn-success btn-submit" title="Remove field" name="c_signoMenos">-</a></div></div></div>';
						break;
					case 4:
						var wrapper = $('#rangos_precios_bultos');
						var fieldHTML = '<div class="row campoDinamico_4campos campo_agregado"><div class="col-xs-3"><div class="form-group"><label>Desde</label><input id="rinicio' + countX + '" placeholder="Ej.: 10" type="number" name="c_rinicio' + countX + '" onkeyup="revisionCamposLlenos()" class="form-control filtro-group" step="0.01"></div></div><div class="col-xs-3"><div class="form-group"><label>Hasta</label><input id="rfin' + countX + '" placeholder="Ej.: 20" type="number" name="c_rfin' + countX + '" onkeyup="revisionCamposLlenos()" class="form-control filtro-group" step="0.01"></div></div><div class="col-xs-3"><div class="form-group"><label>Precio</label><input style="" placeholder="%" type="number" id="precio' + countX + '" name="c_precio' + countX + '" onkeyup="revisionCamposLlenos()" class="form-control filtro-group" step="0.01"></div></div><div class="col-xs-2"><div class="form-group"><label>Tipo</label><select id="opciones_tipo' + countX + '" name="c_opciones_tipo' + countX + '" onchange="revisionCamposLlenos()" class="form-control filtro-group"><option value="0">...</option><option value="1">Unidad</option><option value="2">Corte Minimo</option><option value="3">Corte Comun</option></select></div></div><div class="col-xs-1"><div class="form-group"><br><a href="javascript:void(0);" class="remover_campos btn btn-success btn-submit" title="Remove field" name="c_signoMenos">-</a></div></div></div>';
						break;
					case 6:
						var wrapper = $('#cantidad_precio');
						var fieldHTML = '<div class="row campoDinamico_2campos campo_agregado"><div class="col-xs-3"><div class="form-group"><label>Cantidad</label><input id="cantidad' + countX + '" placeholder="Ej.: 10" type="number" name="c_cantidad' + countX + '" onkeyup="revisionCamposLlenos()" class="form-control filtro-group"></div></div><div class="col-xs-3"><div class="form-group"><label>Precio</label><input placeholder="$" type="number" id="precioP' + countX + '" name="c_precioP' + countX + '" onkeyup="revisionCamposLlenos()" class="form-control filtro-group" step="0.01"></div></div><div class="col-xs-1"><div class="form-group"><br><a href="javascript:void(0);" class="remover_campos btn btn-success btn-submit" title="Remove field" name="c_signoMenos">-</a></div></div></div>';
						break;
					default:
						console.log('..EN CONSTRUCCION...!');
						break;
				}

				$(wrapper).append(fieldHTML);
				modificar_nombres_camposDinamicos(true);
			}
		});
		$(wrapper).on('click', '.remover_campos', function(e) {
			e.preventDefault();
			$(this).parent('div').parent('div').parent('div').remove();
			countX--;

			// console.log(countX);

			modificar_nombres_camposDinamicos(false);
		});
	});


	// Funcionalidades al cargar la pagina:  *(1) Combo box Clientes (filtro) / *(2) Resultado consulta (despues de registrar)
	$(document).ready(function() {
		// 1)
		$('.select-2').select2();

		// 2)
		var resultadGrabado = <?php echo (json_encode($resultado_grabado)); ?>;
		var observacionGrabado = parseInt(<?php echo (json_encode($observacion_grabado)); ?>);

		if (resultadGrabado != '') {
			window.location = "<?php echo base_url() . 'tarifarioT/tarifario_seleccion_opciones/' ?>";
			if (observacionGrabado == 0) {
				alert('\nResultado: ' + 'Los datos ' + resultadGrabado);
			} else {
				alert('\nResultado: \n¡¡No se grabaron todos los datos!!.\nAlgunos Valores de los rangos que ingreso coiniciden con los valores de tarifas ya registradas.\nEs muy probable que otro usuario este realizando cargas de tarifas con valores identicos.\n-Ingrese valores de rangos distintos por favor.');
			}
		}


		//


	});




	// La funcion cuenta la cantidad de rangos, precio y bandera que existen. En base a eso declara y reasigna los nombres de ID - NAME de todos los campos; numerandolos de forma secuencial.
	// La funcion recibe un boolean para saber si lo presiono el Signo mas o el  Signo menos.
	// Se valua al final si estan llenos los campos que quedan o no, para mostrar el siguiente Display.
	// Call: revisionCamposLlenos()
	function modificar_nombres_camposDinamicos(boolean) {
		//Declaracion de Variables - Contadores
		var countRecorrido = 0;
		var countNombresCampos = 1;


		switch (valueComboOpciones) {
			case 6:

				//Declaracion de Variables (string)
				var nombreEdit1Id_cantidad = 'cantidad';
				var nombreEdit1Name_cantidad = 'c_cantidad';

				var nombreEdit1Id_precio = 'precioP';
				var nombreEdit1Name_precio = 'c_precioP';

				var nombreEdit2Id_cantidad = '';
				var nombreEdit2Name_cantidad = '';

				var nombreEdit2Id_precio = '';
				var nombreEdit2Name_precio = '';


				$('.campoDinamico_2campos').children('div').children('div').find('input').each(function() {
					countRecorrido = countRecorrido + 1;

					switch (countRecorrido) {
						case 1:
							nombreEdit2Id_cantidad = nombreEdit1Id_cantidad + countNombresCampos;
							$(this).attr('id', nombreEdit2Id_cantidad);

							nombreEdit2Name_cantidad = nombreEdit1Name_cantidad + countNombresCampos;
							$(this).attr('name', nombreEdit2Name_cantidad);
							break;
						case 2:
							nombreEdit2Id_precio = nombreEdit1Id_precio + countNombresCampos;
							$(this).attr('id', nombreEdit2Id_precio);

							nombreEdit2Name_precio = nombreEdit1Name_precio + countNombresCampos;
							$(this).attr('name', nombreEdit2Name_precio);


							countRecorrido = 0;
							countNombresCampos = countNombresCampos + 1;

							break;
						default:
							// code block
							break;
					}
				});

				break;
			default:

				if (valueComboOpciones == 1 || valueComboOpciones == 2 || valueComboOpciones == 3) {

					console.log(countNombresCampos);
					//Declaracion de Variables (string)
					var nombreEdit1Id_inicio = 'rinicio';
					var nombreEdit1Name_inicio = 'c_rinicio';

					var nombreEdit1Id_fin = 'rfin';
					var nombreEdit1Name_fin = 'c_rfin';

					var nombreEdit1Id_precio = 'precio';
					var nombreEdit1Name_precio = 'c_precio';

					var nombreEdit1Id_tipo = 'opciones_tipo';
					var nombreEdit1Name_tipo = 'c_opciones_tipo';

					var nombreEdit2Id_tipo = '';
					var nombreEdit2Name_tipo = '';

					var nombreEdit2Id_inicio = '';
					var nombreEdit2Name_inicio = '';

					var nombreEdit2Id_fin = '';
					var nombreEdit2Name_fin = '';

					var nombreEdit2Id_precio = '';
					var nombreEdit2Name_precio = '';


					$('.campoDinamico_4campos').children('div').children('div').find('input, select').each(function() {
						countRecorrido = countRecorrido + 1;

						console.log(countNombresCampos);

						switch (countRecorrido) {
							case 1:
								nombreEdit2Id_inicio = nombreEdit1Id_inicio + countNombresCampos;
								$(this).attr('id', nombreEdit2Id_inicio);

								nombreEdit2Name_inicio = nombreEdit1Name_inicio + countNombresCampos;
								$(this).attr('name', nombreEdit2Name_inicio);
								break;
							case 2:
								nombreEdit2Id_fin = nombreEdit1Id_fin + countNombresCampos;
								$(this).attr('id', nombreEdit2Id_fin);

								nombreEdit2Name_fin = nombreEdit1Name_fin + countNombresCampos;
								$(this).attr('name', nombreEdit2Name_fin);
								break;
							case 3:
								nombreEdit2Id_precio = nombreEdit1Id_precio + countNombresCampos;
								$(this).attr('id', nombreEdit2Id_precio);

								nombreEdit2Name_precio = nombreEdit1Name_precio + countNombresCampos;
								$(this).attr('name', nombreEdit2Name_precio);
								break;
							case 4:
								nombreEdit2Id_tipo = nombreEdit1Id_tipo + countNombresCampos;
								$(this).attr('id', nombreEdit2Id_tipo);

								nombreEdit2Name_tipo = nombreEdit1Name_tipo + countNombresCampos;
								$(this).attr('name', nombreEdit2Name_tipo);

								countRecorrido = 0;
								countNombresCampos = countNombresCampos + 1;
								break;
							default:
								// code block
								break;
						}
					});

				}
				break;
		}


		revisionCamposLlenos();
	}


	// Evalua si se ingresaron todos los campos y que no sean igual a 0. 
	// Si -> se arma la Matriz de Valores, y se la envia a la "Funcion evaluacionCamposExistentes".
	// Call: evaluacionCamposExistentes(matrizA) / habilitacionCamposC() / evaluacionCamposErroneos(matrizC)
	function revisionCamposLlenos() {
		var matriz_valores = [];
		var matriz_valoresInterna = [];

		var array_ingresos = [];
		var matriz_ingresos_Externa = [];


		switch (valueComboOpciones) {
			/*case 1: // Kilos

				break;
			case 2: // Bultos

				break;*/
			case 6:

				//Declaracion de Variables - Contadores
				var countRecorrido = 0;
				var countNombresCampos = 1;

				//Declaracion de Variables
				var valorCampoCantidad = 0;
				var valorCampoPrecio = 0;

				var banderaCantidad = false;
				var banderaPrecio = false;

				var valoresIngresados = false;

				// La siguiente funcion recorre todos los campos de Input de columna en columna, y de fila en fila.
				$('.campoDinamico_2campos').children('div').children('div').find('input').each(function() {
					countRecorrido = countRecorrido + 1;

					// El valor que toma el switch (countRecorrido), se refiere al numeo de columna en el cual esta la funcion.
					switch (countRecorrido) {
						case 1:
							valorCampoCantidad = $(this).val();

							if (valorCampoCantidad != 0 && valorCampoCantidad != '') {
								banderaCantidad = true;
							} else {
								banderaCantidad = false;
							}
							matriz_valoresInterna.push(valorCampoCantidad);

							break;
						case 2:
							valorCampoPrecio = $(this).val();

							if (valorCampoPrecio != 0 && valorCampoPrecio != '') {
								banderaPrecio = true;
							} else {
								banderaPrecio = false;
							}
							matriz_valoresInterna.push(valorCampoPrecio);


							if (banderaCantidad && banderaPrecio) {
								valoresIngresados = true;

								array_ingresos.push(valorCampoCantidad, valorCampoPrecio);
								matriz_ingresos_Externa.push(array_ingresos);
								array_ingresos = [];
							} else {
								valoresIngresados = false;
							}

							countRecorrido = 0;

							matriz_valoresInterna.push(valoresIngresados);

							//La matriz interna se carga con valor cantidad, precio (rango), tipo de valores cargados (bandera), y una variable booleana que marca si se cargaron todos los datos.
							matriz_valores.splice(countNombresCampos, 0, matriz_valoresInterna);
							matriz_valoresInterna = [];

							// El valor countNombresCampos, se refiere al numeo de fila de campos, en el cual se encuentra la funcion
							countNombresCampos = countNombresCampos + 1;

							break;
						default:
							// code block
					}
				});

				// Se llama a la funcion y se le envia la matriz_valores.
				var matriz_estados = [];
				matriz_estados = evaluacionCamposExistentes(matriz_valores);

				// Segun el resultado devulto (la nueva matriz), por la funcion, se llama a la funcion habilitacionCamposC.		
				if (matriz_estados) {

					camposErroneos = evaluacionCamposErroneos(matriz_ingresos_Externa);
					habilitacionCamposC(matriz_estados, camposErroneos);
				}


				break;
			default:
				if (valueComboOpciones == 1 || valueComboOpciones == 2 || valueComboOpciones == 4) {

					//Declaracion de Variables - Contadores
					var countRecorrido = 0;
					var countNombresCampos = 1;

					//Declaracion de Variables
					var valorCampoInicio = 0;
					var valorCampoFin = 0;
					var valorCampoPrecio = 0;
					var valorTipoOpcion = 0;

					var banderaTipoOpcion = false;
					var banderaInicio = false;
					var banderaFin = false;
					var banderaPrecio = false;

					var valoresIngresados = false;


					// La siguiente funcion recorre todos los campos de Input y Select, de columna en columna, y de fila en fila.
					$('.campoDinamico_4campos').children('div').children('div').find('input, select').each(function() {
						countRecorrido = countRecorrido + 1;

						// El valor que toma el switch (countRecorrido), se refiere al numeo de columna en el cual esta la funcion.
						switch (countRecorrido) {
							case 1:
								valorCampoInicio = $(this).val();

								if (valorCampoInicio != 0 && valorCampoInicio != '') {
									banderaInicio = true;
								} else {
									banderaInicio = false;
								}
								matriz_valoresInterna.push(valorCampoInicio);

								break;
							case 2:
								valorCampoFin = $(this).val();

								if (valorCampoFin != 0 && valorCampoFin != '') {
									banderaFin = true;
								} else {
									banderaFin = false;
								}
								matriz_valoresInterna.push(valorCampoFin);

								break;
							case 3:
								valorCampoPrecio = $(this).val();

								if (valorCampoPrecio != 0 && valorCampoPrecio != '') {
									banderaPrecio = true;
								} else {
									banderaPrecio = false;
								}
								matriz_valoresInterna.push(valorCampoPrecio);

								break;
							case 4:
								valorTipoOpcion = $(this).val();

								if (valorTipoOpcion != 0) {
									banderaTipoOpcion = true;
								} else {
									banderaTipoOpcion = false;
								}
								matriz_valoresInterna.push(valorTipoOpcion);


								if (banderaInicio && banderaFin && banderaPrecio && valorTipoOpcion != 0) {
									valoresIngresados = true;

									array_ingresos.push(valorCampoInicio, valorCampoFin, valorCampoPrecio, valorTipoOpcion);
									matriz_ingresos_Externa.push(array_ingresos);
									array_ingresos = [];
								} else {
									valoresIngresados = false;
								}

								countRecorrido = 0;

								matriz_valoresInterna.push(valoresIngresados);

								//La matriz interna se carga con valor de incio (de rango), valor de fin (de rango), precio (rango), tipo de valores cargados (bandera), y una variable booleana que marca si se cargaron todos los datos.
								matriz_valores.splice(countNombresCampos, 0, matriz_valoresInterna);
								matriz_valoresInterna = [];

								// El valor countNombresCampos, se refiere al numeo de fila de campos, en el cual se encuentra la funcion
								countNombresCampos = countNombresCampos + 1;

								break;
							default:
								// code block
						}
					});

					// Se llama a la funcion y se le envia la matriz_valores.
					var matriz_estados = [];
					matriz_estados = evaluacionCamposExistentes(matriz_valores);

					// Segun el resultado devulto (la nueva matriz), por la funcion, se llama a la funcion habilitacionCamposC.		
					if (matriz_estados) {

						camposErroneos = evaluacionCamposErroneos(matriz_ingresos_Externa);
						habilitacionCamposC(matriz_estados, camposErroneos);
					}

				}
				break;
		}

	}


	// Recibe la matriz de valores (matriz A), compara c/u de los valores que se cargadon con los registrados en la base de datos. Se carga en una nueva matriz. 
	// *En caso de que el valor exista, en la matriz se lo carga al valor y se marca con una bandera (en TRUE) para saber que el valor ya existe. 
	// Tambien se carga nuevamente el tipo de opcion y campos llenos.
	// Retorna la nueva matriz.
	function evaluacionCamposExistentes(matrizA) {
		//var tarifas = <?php echo (json_encode($tarifas)); ?>;

		var tarifas_kilogramos = <?php echo (json_encode($tarifas_kg)); ?>;
		var tarifas_bultos = <?php echo (json_encode($tarifas_bultos)); ?>;
		var tarifas_cobranza = <?php echo (json_encode($tarifas_cobranza)); ?>;
		var tarifas_palets = <?php echo (json_encode($tarifas_palets)); ?>;

		var id_cliente = document.getElementById("cliente").value;
		var zonaA = document.getElementById("zonaA").value;
		var zonaB = document.getElementById("zonaB").value;

		switch (valueComboOpciones) {
			case 1: // Kilos
				var inicio = 0;
				var fin = 0;
				var precio = 0;
				var tipo_opcion = 0;

				var valorIngresado = false;

				//var cantidadCampos = parseInt(document.getElementsByClassName("campoDinamico_4campos").length);

				matrizInternaEstados = [];
				matrizEstados = [];

				for (x = 0; x < matrizA.length; x++) {
					var estadoI = false;
					var estadoF = false;
					var valorExistenteInicio = 0;
					var valorExistenteFin = 0;

					for (y = 0; y < matrizA[x].length; y++) {
						switch (y) {
							case 0:
								inicio = matrizA[x][y];
								break;
							case 1:
								fin = matrizA[x][y];
								break;
							case 2:
								precio = matrizA[x][y];
								break;
							case 3:
								tipo_opcion = matrizA[x][y];
								break;
							case 4:
								valorIngresado = matrizA[x][y];
								break;
							default:
								//code block
								break;
						}

						if (inicio != 0 && fin != 0 && precio != 0 && tipo_opcion != 0) {
							for (var i = 0; i < tarifas_kilogramos.length; i++) {
								var idCliente_tarifa = parseInt(tarifas_kilogramos[i].cliente);
								var id_zonaA = tarifas_kilogramos[i].zonaA;
								var id_zonaB = tarifas_kilogramos[i].zonaB;

								if (idCliente_tarifa == id_cliente && id_zonaA == zonaA && id_zonaB == zonaB) {
									var inicio_tarifa = parseFloat(tarifas_kilogramos[i].desde_cant_unid_kg);
									var fin_tarifa = parseFloat(tarifas_kilogramos[i].hasta_cant_unid_kg);

									if (inicio >= inicio_tarifa && inicio <= fin_tarifa) {
										estadoI = true;
										valorExistenteInicio = inicio;
										break;
									}
									if (fin >= inicio_tarifa && fin <= fin_tarifa) {
										estadoF = true;
										valorExistenteFin = fin;
										break;
									}
								}
							}
						}
					}

					matrizInternaEstados.push(estadoI, valorExistenteInicio, estadoF, valorExistenteFin, tipo_opcion, valorIngresado);
					matrizEstados.splice(x, 0, matrizInternaEstados);
					matrizInternaEstados = [];
				}
				break;
			case 2: // Bultos
				var inicio = 0;
				var fin = 0;
				var precio = 0;
				var tipo_opcion = 0;

				var valorIngresado = false;

				//var cantidadCampos = parseInt(document.getElementsByClassName("campoDinamico_4campos").length);

				matrizInternaEstados = [];
				matrizEstados = [];

				for (x = 0; x < matrizA.length; x++) {
					var estadoI = false;
					var estadoF = false;
					var valorExistenteInicio = 0;
					var valorExistenteFin = 0;

					for (y = 0; y < matrizA[x].length; y++) {
						switch (y) {
							case 0:
								inicio = matrizA[x][y];
								break;
							case 1:
								fin = matrizA[x][y];
								break;
							case 2:
								precio = matrizA[x][y];
								break;
							case 3:
								tipo_opcion = matrizA[x][y];
								break;
							case 4:
								valorIngresado = matrizA[x][y];
								break;
							default:
								//code block
								break;
						}

						if (inicio != 0 && fin != 0 && precio != 0 && tipo_opcion != 0) {
							for (var i = 0; i < tarifas_bultos.length; i++) {
								var idCliente_tarifa = parseInt(tarifas_bultos[i].cliente);
								var id_zonaA = tarifas_bultos[i].zonaA;
								var id_zonaB = tarifas_bultos[i].zonaB;

								if (idCliente_tarifa == id_cliente && id_zonaA == zonaA && id_zonaB == zonaB) {
									var inicio_tarifa = parseFloat(tarifas_bultos[i].desde_cant_unid_bultos);
									var fin_tarifa = parseFloat(tarifas_bultos[i].hasta_cant_unid_bultos);

									if (inicio >= inicio_tarifa && inicio <= fin_tarifa) {
										estadoI = true;
										valorExistenteInicio = inicio;
										break;
									}
									if (fin >= inicio_tarifa && fin <= fin_tarifa) {
										estadoF = true;
										valorExistenteFin = fin;
										break;
									}
								}
							}
						}
					}

					matrizInternaEstados.push(estadoI, valorExistenteInicio, estadoF, valorExistenteFin, tipo_opcion, valorIngresado);
					matrizEstados.splice(x, 0, matrizInternaEstados);
					matrizInternaEstados = [];
				}

				break;
			case 4:
				var inicio = 0;
				var fin = 0;
				var precio = 0;
				var tipo_opcion = 0;

				var valorIngresado = false;

				//var cantidadCampos = parseInt(document.getElementsByClassName("campoDinamico_4campos").length);

				matrizInternaEstados = [];
				matrizEstados = [];

				for (x = 0; x < matrizA.length; x++) {
					var estadoI = false;
					var estadoF = false;
					var valorExistenteInicio = 0;
					var valorExistenteFin = 0;

					for (y = 0; y < matrizA[x].length; y++) {
						switch (y) {
							case 0:
								inicio = matrizA[x][y];
								break;
							case 1:
								fin = matrizA[x][y];
								break;
							case 2:
								precio = matrizA[x][y];
								break;
							case 3:
								tipo_opcion = matrizA[x][y];
								break;
							case 4:
								valorIngresado = matrizA[x][y];
								break;
							default:
								//code block
								break;
						}

						if (inicio != 0 && fin != 0 && precio != 0 && tipo_opcion != 0) {
							for (var i = 0; i < tarifas_cobranza.length; i++) {
								var idCliente_tarifa = parseInt(tarifas_cobranza[i].cliente);
								var id_zonaA = tarifas_cobranza[i].zonaA;
								var id_zonaB = tarifas_cobranza[i].zonaB;

								if (idCliente_tarifa == id_cliente && id_zonaA == zonaA && id_zonaB == zonaB) {
									var inicio_tarifa = parseFloat(tarifas_cobranza[i].desde_monto_cobranza);
									var fin_tarifa = parseFloat(tarifas_cobranza[i].hasta_monto_cobranza);

									if (inicio >= inicio_tarifa && inicio <= fin_tarifa) {
										estadoI = true;
										valorExistenteInicio = inicio;
										break;
									}
									if (fin >= inicio_tarifa && fin <= fin_tarifa) {
										estadoF = true;
										valorExistenteFin = fin;
										break;
									}
								}
							}
						}
					}

					matrizInternaEstados.push(estadoI, valorExistenteInicio, estadoF, valorExistenteFin, tipo_opcion, valorIngresado);
					matrizEstados.splice(x, 0, matrizInternaEstados);
					matrizInternaEstados = [];
				}
				break;
			case 6:

				var cantidad = 0;
				var precio = 0;

				var valorIngresado = false;

				matrizInternaEstados = [];
				matrizEstados = [];

				for (x = 0; x < matrizA.length; x++) {
					var estadoC = false;
					var valorExistenteCantidad = 0;

					for (y = 0; y < matrizA[x].length; y++) {
						switch (y) {
							case 0:
								cantidad = matrizA[x][y];
								break;
							case 1:
								precio = matrizA[x][y];
								break;
							case 2:
								valorIngresado = matrizA[x][y];
								break;
							default:
								//code block
								break;
						}


					}

					if (cantidad != 0 && precio != 0) {
						for (var i = 0; i < tarifas_palets.length; i++) {
							var idCliente_tarifa = parseInt(tarifas_palets[i].cliente);
							var id_zonaA = tarifas_palets[i].zonaA;
							var id_zonaB = tarifas_palets[i].zonaB;

							if (idCliente_tarifa == id_cliente && id_zonaA == zonaA && id_zonaB == zonaB) {

								var cantidad_tarifa = tarifas_palets[i].cantidad_palet;

								if (cantidad == cantidad_tarifa) {
									estadoC = true;
									valorExistenteCantidad = cantidad;
									break;

								}
							}
						}
					}


					matrizInternaEstados.push(estadoC, valorExistenteCantidad, valorIngresado);
					matrizEstados.splice(x, 0, matrizInternaEstados);
					matrizInternaEstados = [];
				}

				break;
			default:
				//code block
				break;
		}

		return matrizEstados;
	}


	function evaluacionCamposErroneos(matrizC) {

		var resultado = 0;

		var count_finInicio_erroneo = 0;
		var count_valorIngresado = 0;

		switch (valueComboOpciones) {
			/*case 1: // Kilos

				break;
			case 2: // Bultos

		    	
				break;*/
			case 6:
				for (x = 0; x < matrizC.length; x++) {
					var cantidadA = 0;
					var precioA = 0;

					for (y = 0; y < matrizC[x].length; y++) {
						switch (y) {
							case 0:
								cantidadA = parseFloat(matrizC[x][y]);
								break;
							case 1:
								precioA = parseFloat(matrizC[x][y]);
								break;
							default:
								//code block
								break;
						}
					}

					if (cantidadA != 0 && precioA != 0) {

						// Evaluo si los campos se repiten
						for (w = 0; w < matrizC.length; w++) {

							if (x != w) {
								var cantidadB = 0;
								var finB = 0;

								for (z = 0; z < matrizC[w].length; z++) {
									switch (z) {
										case 0:
											cantidadB = matrizC[w][z];
											break;
										case 1:
											//precio = matrizC[w][z];
											break;
										default:
											//code block
											break;
									}

								}

								if (cantidadB != 0) {
									if (cantidadA == cantidadB) {
										count_valorIngresado = count_valorIngresado + 1;
										break;
									}
								}

							}
						}
					}
				}

				if (count_valorIngresado > 0) {
					resultado = 3;
				}

				break;
			default:

				if (valueComboOpciones == 1 || valueComboOpciones == 2 || valueComboOpciones == 4) {
					for (x = 0; x < matrizC.length; x++) {
						var inicioA = 0;
						var finA = 0;
						var precioA = 0;
						var tipo_opcion = 0;

						for (y = 0; y < matrizC[x].length; y++) {
							switch (y) {
								case 0:
									inicioA = parseFloat(matrizC[x][y]);
									break;
								case 1:
									finA = parseFloat(matrizC[x][y]);
									break;
								case 2:
									precioA = parseFloat(matrizC[x][y]);
									break;
								case 3:
									tipo_opcion = parseFloat(matrizC[x][y]);
								default:
									//code block
									break;
							}
						}

						if (inicioA != 0 && finA != 0 && precioA != 0 && tipo_opcion != 0) {
							// Evaluo si se ingreso un Campos Hasta menor que un Campos Desde
							if (finA <= inicioA) {
								count_finInicio_erroneo = count_finInicio_erroneo + 1;
							}

							// Evaluo si los campos se repiten
							for (w = 0; w < matrizC.length; w++) {
								if (x != w) {
									var inicioB = 0;
									var finB = 0;

									for (z = 0; z < matrizC[w].length; z++) {
										switch (z) {
											case 0:
												inicioB = matrizC[w][z];
												break;
											case 1:
												finB = matrizC[w][z];
												break;
											case 2:
												//precio = matrizC[w][z];
												break;
											default:
										}


									}

									if (inicioB != 0 && finB != 0) {
										if (inicioA >= inicioB && inicioA <= finB) {
											count_valorIngresado = count_valorIngresado + 1;
											break;
										}
										if (finA >= inicioB && finA <= finB) {
											count_valorIngresado = count_valorIngresado + 1;
											break;
										}
									}
								}
							}
						}
					}

					if (count_finInicio_erroneo > 0 && count_valorIngresado > 0) {
						resultado = 1;
					} else {
						if (count_finInicio_erroneo > 0) {
							resultado = 2;
						} else {
							if (count_valorIngresado > 0) {
								resultado = 3;
							}
						}
					}

				}
				break;
		}

		return resultado;
	}


	// Recibe la matriz de Estados (matrizB). 
	// Recorre la matriz contando los campos completos y campos ingresados (inicio/fin).
	// Llama a la funcion habilitacion de camposD, pasandole los contadores.
	// Call: habilitacionCamposD()
	function habilitacionCamposC(matrizB, bandera_camposErroneos) {

		var banderaIngresoCampos = false;
		var contadorIncompletos = 0;

		var contadorValorIR = 0;
		var contadorValorFR = 0;

		switch (valueComboOpciones) {
			/*case 1: // Kilos

				break;
			case 2: // Bultos


				break;*/
			case 6:
				var estadoValorC = -1;

				for (x = 0; x < matrizB.length; x++) {
					for (y = 0; y < matrizB[x].length; y++) {
						switch (y) {
							case 0:
								estadoValorC = matrizB[x][y];
								break;
							case 1:
								var valorIngresadoC = matrizB[x][y];
								break;
							case 2:
								banderaIngresoCampos = matrizB[x][y];
								break;
							default:
								//code block
								break;
						}
					}

					if (!banderaIngresoCampos) {
						contadorIncompletos = contadorIncompletos + 1;
					}
					if (estadoValorC) {
						contadorValorIR = contadorValorIR + 1;
					}
				}

				break;
			default:
				if (valueComboOpciones == 1 || valueComboOpciones == 2 || valueComboOpciones == 4) {
					var estadoValorI = -1;
					var estadoValorF = -1;

					for (x = 0; x < matrizB.length; x++) {
						for (y = 0; y < matrizB[x].length; y++) {
							switch (y) {
								case 0:
									estadoValorI = matrizB[x][y];
									break;
								case 1:
									valorIngresadoI = matrizB[x][y];
									break;
								case 2:
									estadoValorF = matrizB[x][y];
									break;
								case 3:
									valorIngresadoF = matrizB[x][y];
									break;
								case 4:
									//	tipoOpcion = matrizB[x][y];
									break;
								case 5:
									banderaIngresoCampos = matrizB[x][y];
									break;
								default:
									//code block
									break;
							}
						}

						if (!banderaIngresoCampos) {
							contadorIncompletos = contadorIncompletos + 1;
						}
						if (estadoValorI) {
							contadorValorIR = contadorValorIR + 1;
						}
						if (estadoValorF) {
							contadorValorFR = contadorValorFR + 1;
						}
					}
				}
				break;
		}


		habilitacionCamposD(contadorIncompletos, contadorValorIR, contadorValorFR, bandera_camposErroneos);
	}


	// Recibe los contadores. Si estan todos en cero (0) habilita el boton de registrar. Caso contrario, muestra MSJ.
	// Luego al presionar el boton Evaluar, se toma el resultado de esta funcion.
	function habilitacionCamposD(contadorIncompletos, contadorValoresInicioRepetidos_cantidadRepetidos, contadorValoresFinRepetidos, bandera_camposErroneos) {
		document.getElementById('msjA').innerHTML = '';
		var mensajeProseguir = 'Si ya ingreso todos los datos presione Evaluar por favor. Comprobaremos si los datos son correctos.';

		registrar_cancelar.style.display = "none";


		switch (valueComboOpciones) {
			/*case 1: // Kilos

				break;
			case 2: // Bultos

				break;*/
			case 6:
				if (contadorIncompletos == 0) {
					if (bandera_camposErroneos == 0) {
						if (contadorValoresInicioRepetidos_cantidadRepetidos == 0) {
							banderaResultado = 1;
						} else {
							registrar_cancelar.style.display = "none";
							banderaResultado = 3;
						}
						evaluacion.style.display = "block";
						document.getElementById('msjA').innerHTML = mensajeProseguir;
						evaluar.style.display = "block";
					} else {
						evaluar.style.display = "none";
						evaluacion.style.display = "block";

						//console.log(bandera_camposErroneos);

						document.getElementById('msjA').innerHTML = 'Sr/a. USUARIO: Por favor verifique los datos porque se estan ingresando campos repetidos. Es posible lo siguiente: (*) Esta intentando ingresar los mismos valores en distintos rangos.';
					}
				} else {
					mensaje = 'Recuerde: Debe completar todos los campos para poder continuar con el registro.';
					document.getElementById('msjA').innerHTML = mensaje;
					evaluar.style.display = "none";
					evaluacion.style.display = "block";
				}

				break;
			default:
				if (valueComboOpciones == 1 || valueComboOpciones == 2 || valueComboOpciones == 4) {
					if (contadorIncompletos == 0) {
						if (bandera_camposErroneos == 0) {
							if (contadorValoresInicioRepetidos_cantidadRepetidos == 0) {
								if (contadorValoresFinRepetidos == 0) {
									banderaResultado = 1;
								} else {
									registrar_cancelar.style.display = "none";
									banderaResultado = 2;
								}
							} else {
								registrar_cancelar.style.display = "none";
								banderaResultado = 3;
							}
							evaluacion.style.display = "block";
							document.getElementById('msjA').innerHTML = mensajeProseguir;
							evaluar.style.display = "block";
						} else {
							evaluar.style.display = "none";
							evaluacion.style.display = "block";

							document.getElementById('msjA').innerHTML = 'Sr/a. USUARIO: Por favor verifique los datos porque se estan ingresando campos repetidos o erroreos. Es posible lo siguiente: (1) Esta ingresando valores repetidos en el campo Hasta y el campo Desde. (2) Esta ingresando un valor HASTA menor que un valor DESDE. (3) Esta intentando ingresar los mismos valores en distintos rangos.';
						}
					} else {

						mensaje = 'Recuerde: Debe completar todos los campos para poder continuar con el registro.';
						document.getElementById('msjA').innerHTML = mensaje;
						evaluar.style.display = "none";
						evaluacion.style.display = "block";
					}


				}
				break;
		}

	}

	// Deshabilita varios displays a la vez (Rangos y precio - Mensaje - Registrar).
	function deshabilitarConjuntoCampos(ubicacionActive) {

		if (!ubicacionActive) {
			ubicaciones.style.display = "none";
		}

		document.getElementById('msjB').innerHTML = '';
		aviso_valor_registrado.style.display = "none";
		registrar_cancelar.style.display = "none";
		evaluacion.style.display = "none";

		switch (valueComboOpciones) {
			case 6:
				cantidad_precio.style.display = "none";

				$('#cantidad_precio').find('.campo_agregado').each(function() {
					$(this).remove();
					countX--;
				});

				break;
			default:
				if (valueComboOpciones == 1 || valueComboOpciones == 2 || valueComboOpciones == 4) {
					rangos_precios_bultos.style.display = "none";

					$('#rangos_precios_bultos').find('.campo_agregado').each(function() {
						$(this).remove();
						countX--;
					});
				}
				break;
		}

	}



	// Se evalua el cliente que se seleccion en el Combo Box Cliente y se muestra un mensaje de Advertencian o no (por posible descarte de informacion cargada)	
	function confirmacionMsj1() {
		if (valueComboCliente == 0) {
			valueComboCliente = document.getElementById("cliente").value;
			mostrarServicio();
		} else {
			if (confirm("Si cambia de Cliente, perdera los datos cargados. ¿Desea continuar?")) {
				mostrarServicio();
			} else {
				document.getElementById("cliente").value = valueComboCliente;
			}
			valueComboCliente = document.getElementById("cliente").value;
		}
	}


	// Se evalua la opcion que se seleccion en el Combo Box Tipo de opcion y se muestra un mensaje de Advertencian o no (por posible descarte de informacion cargada)	
	function confirmacionMsj2() {
		if (valueComboOpciones == 0) {
			valueComboOpciones = document.getElementById("selectCampos").value;
			habilitacionCamposA();
		} else {
			if (confirm("Si cambia el Tipo de Opcion, perdera los datos cargados. ¿Desea continuar?")) {
				habilitacionCamposA();
			} else {
				document.getElementById("selectCampos").value = valueComboOpciones;
			}
			valueComboOpciones = document.getElementById("selectCampos").value;
		}
	}


	//  Se evalua la opcion que se seleccion en el Combo Box ZonaA - ZonaB y se muestra un mensaje de Advertencian o no (por posible descarte de informacion cargada)
	function confirmacionMsj3(valor_combo_id) {
		var nombreCampo = '';
		var valueZona = 0;

		if (!valor_combo_id) {
			nombreCampo = "zonaA";
			valueZona = document.getElementById(nombreCampo).value;
		} else {
			nombreCampo = "zonaB";
			valueZona = document.getElementById(nombreCampo).value;
		}

		if (valueZona != 2401) {
			if (confirm("Si cambia alguna de las zonas se borraran los valores cargados a continuacion.")) {
				habilitacionCamposB(1);
			} else {
				document.getElementById(nombreCampo).value = valueZona;
			}
			valueZona = document.getElementById(nombreCampo).value;
		}
	}


	// Al presionar el boton Evaluar se chequean la variable globale (modificada o no) y se prosigue
	$("#boton_evaluar").click(function() {
		switch (banderaResultado) {
			case 1:
				document.getElementById('msjA').innerHTML = 'Los datos ingresados son correctos.';
				evaluar.style.display = "none";

				registrar_cancelar.style.display = "block";
				break
			case 2:
				alert('\n AVISO: \n-Ingreso un campo de Fin que ya se encuentra registrado en la base. \n-Ingrese otro valor por favor.');
				break
			case 3:
				switch (valueComboOpciones) {
					case 1: // Kilos
						alert('\n Aviso: \n-Ingreso un campo de Inicio que ya se encuentra registrado en la base. \n-Ingrese otro valor por favor.');
						break;
					case 2: // Bultos
						alert('\n Aviso: \n-Ingreso un campo de Inicio que ya se encuentra registrado en la base. \n-Ingrese otro valor por favor.');
						break;
					case 4:
						alert('\n Aviso: \n-Ingreso un campo de Inicio que ya se encuentra registrado en la base. \n-Ingrese otro valor por favor.');
						break;
					case 6:
						alert('\n Aviso: \n-Ingreso un campo de Cantidad que ya se encuentra registrado en la base. \n-Ingrese otro valor por favor.');
						break;
					default:
						//code block
						break;
				}
				break;
			default:
				//code block
				break;
		}
	});


	// Al presionar boton Tarifas
	function verRegistros() {
		var id_cliente = parseInt(document.getElementById("cliente").value);

		if (id_cliente == 0) {
			alert('Debe seleccionar un cliente primero, para poder explorar sus tarifas cargadas');
		} else {
			window.location = "<?php echo base_url() ?>" + 'tarifarioT/tarifario_seleccion_opciones/listar/' + id_cliente;
		}
	}
</script>