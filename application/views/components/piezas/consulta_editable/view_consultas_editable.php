	<style>
		.flex {
			display: flex;
			justify-content: space-between;
			align-items: flex-start;
			flex-wrap: wrap;
		}

		.flex-col {
			display: flex;
			flex-direction: column;

		}

		.flex-item {
			min-width: auto;
			width: 49%;
		}

		input[type=checkbox] {
			transform: scale(1.25);
		}

		.bg-clr-primary {
			color: white;
			background: #428bca !important;
		}

		td {
			margin: 10px 50px;
			white-space: nowrap;
		}
	</style>
	<div class="col-xs-12">
		<div class="ibox-content">

			<form class="form-inline" id="formConsultasVariables" name="formConsultasVariables" action="<?= base_url('piezas/consulta_editable/index') ?>" method="post">
				<input id="query" type="text" name="query" hidden value="<?= $this->session->userdata('filtroConsultaGlobalDocumento') ?>">
				<div class="row">
					<div class="col-xs-12">
						<h3>Tablas</h3>
					</div>
				</div>
				<div id="addTabla-container" class="row">
					<div class="col-md-3 col-xs-9">
						<select id="addTabla-select" class="form-control">
							<?php foreach ($estructura as $tabla => $valor) : ?>
								<?php if ($valor['parent'] === 0) : ?>
									<option value="<?php echo $valor['index'] ?>"><?php echo $valor['nombre'] ?></option>
								<?php endif; ?>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="col-xs-3">
						<button type="button" id="addTabla-btn" class="btn btn-primary" onClick="addTabla()">Agregar tabla</button>
					</div>
				</div>
				<hr>
				<!-- GENERACION DE TABLAS -->
				<div id="contenido_variable" class="flex">
				</div>

				<!-- BOTON BUSCAR -->
				<div class="row" style="margin-bottom: 10px;">
					<div class="col-xs-9">
					</div>
					<div class="col-xs-3">
						<input hidden type="text" id="procesar" name="procesar" value="1" />
						<div class="text-right">
							<button id="btnFiltrar" name="btnFiltrar" class="btn btn-primary" type="submit">Buscar</button>
						</div>
					</div>
				</div>
			</form>

			<!-- TIEMPO -->
			<?php if (isset($links)) : ?>
				<div class="alert alert-success">
					<?= $time_total ?>
				</div>
			<?php endif; ?>

			<!-- CONSULTAS -->
			<?php /*
			<?php if (isset($consultas)) : ?>
				<div class="alert alert-warning">
					<pre><?= print_r($consultas); ?></pre>
				</div>
			<?php endif; ?>
			*/ ?>
			<!-- NO RESULTS -->
			<?php if (isset($noResult)) : ?>
				<div class="alert alert-danger">
					No se encontraron resultados para la busqueda
				</div>
			<?php endif; ?>



			<!-- TABLA -->
			<div id="grilla" class="col-xs-12 table-responsive" style="overflow-x: scroll">
				<div class="row" style="margin-bottom: 10px;   display: flex; align-items: center;">

					<!-- PAGINATION -->
					<div class="col-xs-10">
						<?php if (isset($links)) : ?>
							<?= $links ?>
						<?php endif; ?>
					</div>

					<!-- BOTON EXCEL -->
					<div class="col-xs-2">
						<form action="<?= base_url('piezas/consulta_editable/exportarAExcel') ?>" method="post" id="form_consultas_globales">
							<div class="text-right">
								<button class="btn btn-success exportarAExcel" type="submit" disabled><i class="fa fa-print"></i> Exportar a Excel</button>
							</div>
						</form>
					</div>
				</div>

				<!-- TABLA -->

				<table id="resultado" class="table table-striped table-hover  bootstrap-datatable table-bordered">
					<thead>
						<?php if (isset($consultas)) : ?>
							<tr class=" bg-success">
								<?php foreach ($consultas['header'] as $tabla => $col) : ?>
									<th colspan="<?= count($col) ?>" class=" bg-clr-primary text-center text-uppercase">
										<?= $tabla ?>
									</th>
								<?php endforeach; ?>
							</tr>
							<tr>
								<?php foreach ($consultas['header'] as $tabla => $col) : ?>
									<?php foreach ($col  as $campo) : ?>
										<th class="bg-clr-primary text-center text-uppercase"><?= $campo ?></th>
									<?php endforeach; ?>
								<?php endforeach; ?>
							</tr>
						<?php endif; ?>
					</thead>
					<tbody>
						<?php if (isset($results)) : ?>
							<?php foreach ($results as $row) : ?>
								<tr id="resultados" class="nueva">
									<?php foreach ($row as $col) : ?>
										<td class=""><?= $col ?></td>
									<?php endforeach; ?>
								</tr>
							<?php endforeach; ?>
						<?php endif; ?>
					</tbody>
				</table>

				<?php if (isset($links)) : ?>
					<?= $links ?>
				<?php endif; ?>
			</div>

			<div class="row">
			</div>

			<hr />
		</div>
	</div>



	<script>
		//info recibira la info de tablas y relaciones del backend
		// relaciones no es necesario aca.
		let tablas

		function addTabla(indexTabla, parent = 0) {
			//busco en la "tabla" si existe la tabla para crear y si no creada => la creo
			//se puede crear la tabla por parametro o tomando el valor actual del select
			indexTabla = (indexTabla != null) ? indexTabla : $('#addTabla-select').val()
			newTabla = tablas.find(t => t.index == indexTabla)

			newTabla.active = 1
			newTabla.parent = parent
			renderConsulta()
		}

		function renderConsulta() {
			$('#contenido_variable').empty()
			tablas.forEach(tabla => {
				if (tabla.active) {
					crearFormTabla(tabla)
				}
			});
		}

		function borrarTabla(idTabla) {
			if (idTabla.id != 'piezas') {
				tablas.forEach(t => {
					if (t.nombreDB == idTabla.id) {
						t.active = 0
					}
				})
				$('#' + idTabla.id).remove()
			}
		}

		// crea el formulario de tabla teniendo en cuenta los campos definidos
		// en la tabla modelo
		function crearFormTabla(tabla) {
			const parent = newTabla = tablas.find(t => t.index == tabla.parent)

			//CREO ELEMENTO PADRE QUE VA A CONTENER TABLA
			const container = `
				<div id="${tabla.nombreDB}" class="panel panel-success flex-item" >
					<div class="panel-heading" style="font-weight: bold; font-size: 1.5rem;">
						${tabla.index == 0 ? '' : (parent.nombre.toUpperCase() + ' <i class="glyphicon glyphicon-arrow-right"></i>' )}
						${tabla.nombre.toUpperCase()}
						<button type="button" class="btn btn-xs btn-danger pull-right" onClick="borrarTabla(${tabla.nombreDB})"> 
							<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
						</button>
					</div>
				</div>`


			let content = '<div class="panel-body flex-col">'

			const columnas = Object.values(tabla.columnas)
			columnas.forEach(columna => {
				if (columna.nombreDB == 'id') {
					content += `
					<div class="form-group">
						<label class="col-xs-4 text-right">Tiene ${tabla.nombre}</label>
						<div class="input-group col-xs-8"">
							<span class="input-group-addon">
								<input type="checkbox" ${columna.value != 'disabled' ? 'checked' : '' } class="" data-type="check" onChange="checkboxHandlerIds(this,event)" data-tabla="${tabla.nombre}" data-columna="${columna.nombreDB}">
							</span>
							<input 
								type="text" 
								data-type="text" 
								data-tabla="${tabla.nombre}" 
								data-columna="${columna.nombreDB}" 
								onChange="modificarConsulta(this,event)"
								${columna.value != 'disabled' ? '' : 'disabled' } 
								class="form-control filtro"  value="${columna.value != 'disabled' ? columna.value : '' }" autocomplete="off" placeholder="ID"/>
						</div>
					</div>
				`
				} else {
					if (columna.type == 'timestamp') {
						content += `
						<div class=" form-group">
							<label class="control-label col-xs-4 text-right" for="${tabla.nombre + '.' + columna.nombreDB}">${columna.nombre}</label>
							<div class="input-group input-daterange col-xs-8">
				
								<input type="text"
									class="form-control datepicker"
									onChange="modificarConsulta(this,event)"
									id="${tabla.nombre + '.' + columna.nombreDB}.desde"
									name="${tabla.nombre + '.' + columna.nombreDB}.desde"
									data-type="date"
									data-tabla="${tabla.nombre}"
									data-columna="${columna.nombreDB}"
									data-fecha="desde"
									placeholder="Desde"  value="${columna.desde}" autocomplete="off"/>
					
								<div class="input-group-addon">al</div>
								<input type="text"
									onChange="modificarConsulta(this,event)"
									class="form-control datepicker "
									id="${tabla.nombre + '.' + columna.nombreDB}.hasta"
									name="${tabla.nombre + '.' + columna.nombreDB}.hasta" data-tabla="${tabla.nombre}"
									data-type="date"
									data-columna="${columna.nombreDB}"
									data-fecha="hasta"
									placeholder="Hasta" value="${columna.hasta}" autocomplete="off"/>
							</div>
						</div>`
					} else if (columna.type == 'tabla') {

						content += `
						<div class="form-group" style="padding: 5px 0;">
							<label class="col-xs-4 text-right" data-type="date">${columna.nombre}</label>
							<button type="button" class="btn btn-primary btn-xs" onClick="addTabla(${columna.value}, ${tabla.index})">
								Agregar tabla
							</button>
						</div>
						`
					} else {
						content += `
						<div class="form-group">
							<label class="col-xs-4 text-right" data-type="date">${columna.nombre}</label>
							<div class="input-group col-xs-8">
								<span class="input-group-addon">
									<input onChange="checkboxHandleField(this,event)" type="checkbox" ${columna.value != 'disabled' ? 'checked' : '' } data-tabla="${tabla.nombre}" data-columna="${columna.nombreDB}">
								</span>
								<input type="text" 
									class="form-control filtro"
									id="${tabla.nombre + '.' + columna.nombreDB}" 
									name="${tabla.nombre + '.' + columna.nombreDB}" 
									onChange="modificarConsulta(this,event)" 
									data-type="text" 
									data-tabla="${tabla.nombre}" 
									data-columna="${columna.nombreDB}" 
									${columna.value != 'disabled' ? '' : 'disabled' }
									value="${columna.value != 'disabled' ? columna.value : ''}" autocomplete="off"/>
							</div>
						</div>`
					}
				}
			}) //fin FOREACH
			content += '</div>'
			$('#contenido_variable').append(container)
			$(`#${tabla.nombreDB}`).append(content)
			$('.datepicker').datepicker({
				format: 'yyyy-mm-dd'
			})


		}

		// lee cambios en los campos y hace el correspondiente cambio al json de consulta.
		function modificarConsulta(input) {
			const tabla = input.getAttribute('data-tabla')
			const columna = input.getAttribute('data-columna')
			const tipo = input.getAttribute('data-type')
			const value = input.value

			const indexTabla = tablas.findIndex((obj) => {
				return obj.nombre === tabla
			})

			if (tipo == 'date') {
				let range = input.getAttribute('data-fecha')
				tablas[indexTabla].columnas[columna][range] = value
			}
			if (tipo == 'text') {
				tablas[indexTabla].columnas[columna].value = value
			}
		}

		// activa o desactiva los campos de la tabla segun el checkbox
		function checkboxHandlerIds(checkbox) {
			const tabla = checkbox.getAttribute('data-tabla')
			const columna = checkbox.getAttribute('data-columna')

			const inputs = document.querySelectorAll(`input[data-tabla='${tabla}']`)


			const indexTabla = tablas.findIndex((t) => {
				return t.nombre === tabla
			})

			//Desactiva  los inputs de la tabla correspondiente
			if (checkbox.checked == true) {
				tablas[indexTabla].columnas['id'].value = ''

				inputs.forEach((element) => (element.disabled = false))
				$(`input[data-tabla='${tabla}'][type='checkbox']`).each(function() {
					this.checked = true
				})
			} else {
				inputs.forEach((element) => {
					element.disabled = true
					element.value = ''
				})
				//consultaTabla = emptyCols(consultaTabla)
			}

			checkbox.disabled = false
		}

		function checkboxHandleField(checkbox) {
			const tabla = checkbox.getAttribute('data-tabla')
			const columna = checkbox.getAttribute('data-columna')

			const index = tablas.findIndex((obj) => {
				return obj.nombre === tabla
			})

			checkbox = $(checkbox)
			if (checkbox.is(':checked') == true) {
				tablas[index].columnas[columna].value = ''
				checkbox.parent().next().prop('disabled', false);
				checkbox.parent().next().val('');
			} else {
				checkbox.parent().next().val('').prop('disabled', true);
				tablas[index].columnas[columna].value = 'disabled'
			}
		}
	</script>

	<script>
		$(document).ready(() => {
			//Recibe formato de las tablas del backend
			tablas = JSON.parse('<?php echo json_encode(array_values($estructura)); ?>');

			//Recibo la consulta que ya se efectuo 
			//consulta = JSON.parse('<?php echo json_encode($this->session->userdata('filtroConsultaEditable')); ?>');

			//Si ya se hizo una consulta entonces habilito el boton de exportal a excel
			const existeQuery = <?= empty($this->session->userdata('filtroConsultaEditableTotalRecords')) ? 0 : 1; ?>;
			if (existeQuery) {
				$('.exportarAExcel').prop("disabled", false);
			}
			//Inicializo con piezas
			addTabla(0)
			//$("input[data-tabla='piezas'][data-columna='id']").prop('checked', true).trigger('change')


			//Control si se hizo una consulta
			$('#formConsultasVariables').submit(function() {
				$('#query').val(JSON.stringify(tablas ?? ''))
				$('#procesar').val(1)
				this.submit()
			})
		})
	</script>