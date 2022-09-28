<!--GUI de Paqueteria, donde ingresa los valores y se calculan los montos-->
<style>
	label.error {
		font-size: 1.2rem;
		font-weight: normal;
		color: red;
	}
</style>

<form action="<?= base_url('tarifarioT/tarifario_carga/add') ?>" method="post" id="form-paquete">
	<div class="col-xs-12">
		<div class="ibox-content">
			<div class="row">
				<div class="col-xs-6">
					<div class="form-group">
						<label for="cliente">Cliente</label>
						<input id="cliente" name="cliente" type="text" class="form-control filtro-group" placeholder="Cliente" autocomplete="off" value="<?php echo $cliente ?>" />
						<input id="cliente_id" name="cliente_id" type="hidden" value="<?php echo $cliente_id ?>" />
					</div>
				</div>
				<div class="col-xs-6">
					<div class="form-group">
						<label for="servicio">Servicio</label>
						<select id="servicio" name="servicio" class="form-control filtro-group" onchange="cambioServicio()">
							<option disabled="" selected="" value="">Seleccione</option>
						</select>
					</div>
				</div>
			</div>
		</div>
		<div class="ibox-content">
			<div id="paqueteria">
				<div class="row">
					<div class="col-xs-3">
						<label for="zonaA">zonaA</label>
						<select id="zonaA" name="zonaA" class="form-control filtro-group" onchange="cambioZona()" disabled>
							<option disabled selected="" value="">Seleccione</option>
						</select>
					</div>
					<div class="col-xs-3">
						<label for="zonaB">zonaB</label>
						<select id="zonaB" name="zonaB" class="form-control filtro-group" onchange="cambioZona()" disabled>
							<option disabled selected="" value="">Seleccione</option>
						</select>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-xs-3">
						<label for="peso">Peso (Kg)</label>
						<input disabled id="peso" name="peso" type="number" step="0.01" class="form-control" placeholder="Peso" value="" />
					</div>
					<div class="col-xs-3">
						<label for="bulto">Bulto (unidades)</label>
						<input disabled id="bulto" name="bulto" type="number" step="1" class="form-control" placeholder="Bulto" value="1" />
					</div>
					<div class="col-xs-3">
						<label for="palet">Palet (unidades)</label>
						<input disabled id="palet" name="palet" type="number" step="1" class="form-control" placeholder="palet" value="1" />
					</div>
				</div>


			</div>
			<hr>
			<div id="piezas-paquetes">
				<div class="row">
					<div class="col-xs-6">
						<label for="descripcion_paquete">Descripcion del paquete</label>
						<input id="descripcion_paquete" name="descripcion_paquete" type="text" class="form-control" placeholder="Ej. Zapatillas run-runner" value="" />
					</div>
					<div class="col-xs-6">
						<label for="dimensiones">Dimensiones(AltoXLargoXProfund. en cm.) </label>
						<input id="dimensiones" name="dimensiones" type="text" class="form-control" placeholder="50x15x20" value="" />
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-xs-6">
						<div class="form-group">
							<label for="destinatario">Destinatario</label>
							<input id="destinatario" name="destinatario" type="text" class="form-control" placeholder="Destinatario" autocomplete="off" />
						</div>
					</div>
					<div class="col-xs-6">
						<div class="form-group">
							<label for="domicilio">Domicilio</label>
							<input id="domicilio" name="domicilio" type="text" class="form-control" placeholder="Domicilio" autocomplete="off" />
						</div>
					</div>
					<div class="col-xs-3">
						<div class="form-group">
							<label for="codigo_postal">CP</label>
							<input id="codigo_postal" name="codigo_postal" type="text" class="form-control" placeholder="CP" autocomplete="off" />
						</div>
					</div>
					<div class="col-xs-3">
						<div class="form-group">
							<label for="localidad">Localidad</label>
							<input id="localidad" name="localidad" type="text" class="form-control" placeholder="Localidad" autocomplete="off" />
						</div>
					</div>
					<div class="col-xs-3">
						<div class="form-group">
							<label for="barcode_externo">Codigo Barras Externo</label>
							<input id="barcode_externo" name="barcode_externo" type="text" class="form-control" placeholder="Codigo Barras Externo" autocomplete="off" />
						</div>
					</div>
				</div>
			</div>
			<hr>
			<div id="piezas-all">
				<div class="row">
					<div class="col-xs-12">
						<div class="form-group">
							<label for="datos_varios">Datos Varios</label>
							<textarea id="datos_varios" name="datos_varios" class="form-control" placeholder="Datos Varios" rows="3"></textarea>
						</div>
					</div>
					<div class="col-xs-12">
						<div class="form-group">
							<label for="datos_varios">Datos Varios 1</label>
							<textarea id="datos_varios_1" name="datos_varios_1" class="form-control" placeholder="Datos Varios 1" rows="3"></textarea>
						</div>
					</div>
					<div class="col-xs-12">
						<div class="form-group">
							<label for="datos_varios">Datos Varios 2</label>
							<textarea id="datos_varios_2" name="datos_varios_2" class="form-control" placeholder="Datos Varios 2" rows="3"></textarea>
						</div>
					</div>
				</div>
			</div>
			<hr>
			<div class="row">
				<div class="col-xs-12">
					<div class="control-group text-right">
						<div class="controls">
							<button class="btn btn-primary agregar-grilla" type="submit"><i class="fa fa-arrow-down"></i> Agregar</button>
							<!--                <B class="btn btn-primary imprimir-acuse" target="_blank" href="javascript:;" ><i class="fa fa-arrow-down"></i> Imprimir Acuse</B>
                <a class="btn btn-primary imprimir-etiquetas" href="javascript:;" ><i class="fa fa-arrow-down"></i> Imprimir Etiquetas</a>-->
							<a class="btn btn-danger" href="<?= base_url() . 'piezas/comprobantes_ingresos' ?>"><i class="fa fa-arrow-left"></i> Volver</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="ibox-content">
			<table id="resultado" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
				<thead>
					<tr class="header_table">
						<th>Comprobante Ingreso</th>
						<th>Paquete</th>
						<th>Descripcion</th>
						<th>Peso</th>
						<th>Dimensiones</th>
						<th>Bultos</th>
						<th>Palets</th>
						<th>ZonaA</th>
						<th>ZonaB</th>
					</tr>
				</thead>
				<tbody id="body-grilla">
					<?php $class = "";
					if ($paquetes) :
						foreach ($paquetes as $value) : ?>
							<tr <?php echo $class; ?>>
								<td><?= $value->comprobante_ingreso ?></td>
								<td><?= $value->pieza_id ?></td>
								<td><?= $value->descripcion_paquete ?></td>
								<td><?= $value->peso ?></td>
								<td><?= $value->dimensiones ?></td>
								<td><?= $value->bultos ?></td>
								<td><?= $value->palets ?></td>
								<td><?= $value->zonaA ?></td>
								<td><?= $value->zonaB ?></td>
							</tr>
					<?php endforeach;
					endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</form>

<script type="text/javascript">
	let tarifario;

	function buscarTarifario() {
		$('#servicio').empty();
		$('#servicio').append(new Option('Paqueteria', '2', true))
		$('#paqueteria :input').prop('disabled', true)

		const servicio = $('#servicio').val()
		const cliente_id = $('#cliente_id').val();
		$.get("<?php echo base_url('tarifarioT/Tarifario_carga/getTarifarios') ?>", {
				cliente_id,
				servicio
			},
			function(data, status) {
				data = JSON.parse(data)
				habilitarZonas(data)
				tarifario = data;
			});
	}

	function habilitarZonas(tarifario) {
		$('#zonaA').empty();
		$('#zonaA').append(new Option('Seleccione', 0))
		tarifario.zonasA.forEach(zona => {
			$('#zonaA').append(new Option(zona.nombre, zona.id))
		});

		$('#zonaB').empty();
		$('#zonaB').append(new Option('Seleccione', 0))
		tarifario.zonasB.forEach(zona => {
			$('#zonaB').append(new Option(zona.nombre, zona.id))
		});
		$('#zonaA').prop("disabled", false);
		$('#zonaB').prop("disabled", false);
	}


	function cambioZona() {
		const zonaA = $('#zonaA').val()
		const zonaB = $('#zonaB').val()
		if (zonaA != 0 && zonaB != 0) {
			const kg = tarifario.kg.findIndex(t => (t.zonaA == zonaA && t.zonaB == zonaB)) == -1
			const bultos = tarifario.bultos.findIndex(t => (t.zonaA == zonaA && t.zonaB == zonaB)) == -1
			const palets = tarifario.palets.findIndex(t => (t.zonaA == zonaA && t.zonaB == zonaB)) == -1

			$('#peso').prop("disabled", kg);
			$('#bulto').prop("disabled", bultos);
			$('#palet').prop("disabled", palets);
		}
	}

	$(document).ready(function() {
		$('#resultado').DataTable({
			"order": [
				[1, "desc"]
			]
		});

		$("#form-paquete :input").prop("autocomplete", 'off');

		// buscador de clientes. Cuando se selecciona se busca servicios
		$('#cliente').typeahead({
			source: <?= json_encode($clientes) ?>,
			displayField: 'nombre',
			onSelect: function(item) {
				$('#cliente_id').val('');
				$('#cliente').val('');
				if (item.value) {
					$('#cliente_id').val(item.value);
					buscarTarifario(item.value);
				}
			}
		});

		$('.filter').click(function(event) {
			$('#form_bdp').submit();
		});

		// VALIDACION 
		//kg
		$.validator.addMethod(
			"rangoKg",
			function(value, element) {
				value = Number(value)
				let valid = false
				const zonaA = $('#zonaA').val()
				const zonaB = $('#zonaB').val()
				tarifario.kg.forEach(row => {
					if (Number(row.desde_cant_unid_kg) <= value && Number(row.hasta_cant_unid_kg) >= value && row.zonaA == zonaA && row.zonaB == zonaB) valid = true
				});
				return valid
			},
			"Peso fuera del rango de tarifario"
		);
		//bultos
		$.validator.addMethod(
			"rangoBultos",
			function(value, element) {
				value = Number(value)
				let valid = false
				const zonaA = $('#zonaA').val()
				const zonaB = $('#zonaB').val()
				tarifario.bultos.forEach(row => {
					if (Number(row.desde_cant_unid_bultos) <= value && Number(row.hasta_cant_unid_bultos) >= value && row.zonaA == zonaA && row.zonaB == zonaB) valid = true
				});
				return valid
			},
			"Cantidad fuera del rango de tarifario"
		);
		//palets
		$.validator.addMethod(
			"rangoPalets",
			function(value, element) {
				let valid = false
				value = Number(value)
				const zonaA = $('#zonaA').val()
				const zonaB = $('#zonaB').val()
				tarifario.palets.forEach(row => {
					if (Number(row.cantidad_palet) == value && row.zonaA == zonaA && row.zonaB == zonaB) valid = true
				});
				return valid
			},
			"Cantidad de palets fuera del rango de tarifario"
		);
		$.validator.addMethod(
			"zonaElegida",
			function(value, element) {
				return ($('#zonaA').val() != 0 && $('#zonaB').val() != 0)
			},
			"Seleccione zonas primero"
		);


		$("#form-paquete").validate({
			submitHandler: function(form) {
				form.submit();
			},
			rules: {
				peso: {
					zonaElegida: true,
					rangoKg: true,
					required: true
				},
				bulto: {
					zonaElegida: true,
					rangoBultos: true,
					required: true,
				},
				palet: {
					zonaElegida: true,
					rangoPalets: true,
					required: true,
				},
			},
		});
	});
</script>



<?php
/*


<form method="post">
	<div class="col-xs-12">
		<div class="wrapper wrapper-content animated fadeInRight">
			<div class="row">
				<div class="col-xs-12">
					<div class="ibox-content">
						<div class="row">
							<div class="col-xs-6">
								<div class="form-group">
									<label>Cliente:</label>
									<select class="select-2" id="combobox_clientes" name="c_combobox_clientes">
										<option value="0"></option>
										<?php
										if ($data) {
											foreach ($client as $result) {
												$cadena_nombre = $result->nombre;
												$cadena_nombre_edit = str_replace(' ', '_', $cadena_nombre);

												echo ('<option value=' . $cadena_nombre_edit . '>' . $result->nombre . '</option>');
											}
										} else {
											echo ('<option>No existen resultados</option>');
										}
										?>
									</select>
								</div>
							</div>
							<div class="col-xs-5">
								<div class="form-group">
									<label>Opciones de Calculo: </label>
									<select id="selectOpcionCalculo" name="c_selectOpcionCalculo" class="form-control filtro-group" onchange="">
										<option value="0">...</option>
										<option value="1">Kilos (Kg)</option>
										<option value="2">Bultos (unidades)</option>
										<option value="3">Distancia (Km)</option>
										<option value="4">Cobranza (%)</option>
										<option value="5">Metros³</option>
										<option value="6">Palets</option>
										<option value="7">Gestión de Flota</option>
										<option value="8">WareHouse</option>
										<option value="9">Pick&Pack - Finish</option>
										<option value="10">Extra</option>
									</select>
								</div>
							</div>
							<div class="col-xs-1">
								<div class="form-group">
									<br>
									<input type="submit" name="btn_buscar" value="Filtrar" class="btn btn-success btn-submit">
								</div>
							</div>
						</div>
					</div>
					<div class="ibox-content" id="seccion_calculo" name="c_seccion_calculo" style="display: none">
						<div class="row">
							<div class="col-xs-6">
								<div class="form-group">

									<?php
									echo ('<br><br><label>Cliente: </label>');
									foreach ($nombre_cliente as $n) {
										echo ('<p>' . $n->nombre . '</p>');
									}

									echo ('<label>Calculo por: </label>');

									//switch

									if ($tipo_calculo == 2) {
										echo ('<p> Cantidad de Bultos</p><br><br>');
									}
									?>
									<div class="col-xs-5">
										<div class="form-group">
											<label style="">Cantidad: </label>
											<input placeholder="..." type="number" step="any" name="c_cantidad" id="cantidad" class="form-control filtro-group" onkeyup="calcular()">
										</div>
									</div>
									<div class="col-xs-5">
										<div class="form-group">
											<label>Costo: $ </label>
											<label id="precio" name="c_precio" class="form-control filtro-group">
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
	</div>
</form>


<script type="text/javascript">
	var $nombreCliente = <?php echo (json_encode($nombre_cliente)); ?>;

	if ($nombreCliente != '') {
		seccion_calculo.style.display = "block"
	}

	function calcular() {
		var campo = document.getElementById('cantidad').value;
		var tarifas = <?php echo (json_encode($tarifas)); ?>;

		for (var i = 0; i < tarifas.length; i++) {
			var inicio = parseFloat(tarifas[i].desde_cant_unid_bultos);
			var fin = parseFloat(tarifas[i].hasta_cant_unid_bultos);
			var precio = parseFloat(tarifas[i].precio_Corte_bultos);

			var bandera = parseInt(tarifas[i].bandera_Corte_Bultos);

			if (campo != '') {
				if (campo >= inicio) {
					if (campo <= fin) {

						if (bandera == 1) {
							// En caso de ser 1 es el valor de la unidad             
							var calculo_precio = precio * campo;
							document.getElementById('precio').innerHTML = calculo_precio;
						} else {
							//En caso de ser 2 o 3 es un CORTE
							document.getElementById('precio').innerHTML = precio;
						}

						break;
					} else {
						document.getElementById('precio').innerHTML = '';
					}
				} else {
					document.getElementById('precio').innerHTML = '';
				}
			} else {
				document.getElementById('precio').innerHTML = '';
			}
		}
	}


	// Funcion Select2 -> Ingresar valores en el Cbox para buscar, filtrando las opciones 
	$(document).ready(function() {
		$('.select-2').select2();
	});
</script>
*/
?>