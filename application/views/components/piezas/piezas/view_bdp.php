<style>
	td {
		white-space: nowrap;
	}
</style>

<div class="col-xs-12">
	<div class="ibox-content">
		<form action="<?= base_url('piezas/bdp/filter') ?>" method="post" id="form_bdp">
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
						<select id="servicio" name="servicio" class="form-control filtro-group">
							<option disabled="" selected="" value="">Seleccione</option>
						</select>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-3">
					<div class="form-group">
						<label for="periodo_desde">Periodo desde</label>
						<input id="periodo_desde" name="periodo_desde" type="text" class="form-control datepicker" placeholder="Seleccione una fecha" value="<?php if ($periodo_desde) echo $periodo_desde->format('d-m-Y'); ?>" required autocomplete="off" />
					</div>
				</div>
				<div class="col-xs-3">
					<div class="form-group">
						<label for="periodo_hasta">Periodo hasta</label>
						<input id="periodo_hasta" name="periodo_hasta" type="text" class="form-control datepicker" placeholder="Seleccione una fecha" value="<?php if ($periodo_hasta) echo $periodo_hasta->format('d-m-Y'); ?>" required autocomplete="off" />
					</div>
				</div>
			</div>
			<div class="text-right">
				<a class="btn btn-primary filter" href="javascript:;"><i class="fa fa-arrow-down"></i> Buscar</a>
				<a class="btn btn-success exportarAExcel" href="javascript:;"><i class="fa fa-arrow-down"> </i>Exportar a Excel</a>
			</div>
			<div>&nbsp;</div>
			<?php if (!empty($results)) : ?>
				<div class="ibox-content" style="overflow-x: auto;height: auto">
					<table id="results" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
						<thead>
							<tr>
								<?php foreach ($headers as $col) : ?>
									<th> <?= $col ?> </th>
								<?php endforeach; ?>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($results as $row) : ?>
								<tr>
									<?php foreach ($row as $value) : ?>
										<td><?= $value ?> </td>
									<?php endforeach; ?>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			<?php else : ?>
				<div class="alert alert-danger">
					No hay resultados.
				</div>
			<?php endif; ?>
		</form>
	</div>
</div>
<form method='post' action='<?php echo base_url('piezas/bdp/exportarBolsaPiezasAExcel') ?>' id='exportAExcel'>
	<input type='hidden' id='excel_desde' name='excel_desde' value="">
	<input type='hidden' id='excel_hasta' name='excel_hasta' value="">
	<input type='hidden' id='excel_cliente_id' name='excel_cliente_id' value="">
</form>
<script type="text/javascript">
	// busca los servicios del cliente y los agrega al select
	function buscarServicio($idCliente) {
		$.get("<?php echo base_url('piezas/bdp/getServiciosPorCliente') ?>", {
				idCliente: $idCliente
			},
			function(data, status) {
				$('#servicio').empty();
				$('#servicio').append(new Option('Seleccione', ''))
				data.forEach(({
					id,
					nombre
				}) => {
					$('#servicio').append(new Option(nombre, id))
				})
			});

	}

	$(document).ready(function() {
		// buscador de clientes. Cuando se selecciona se busca servicios
		$('#cliente').typeahead({
			source: <?= json_encode($clientes) ?>,
			displayField: 'nombre',
			onSelect: function(item) {
				$('#cliente_id').val('');
				$('#cliente').val('');
				if (item.value) {
					$('#cliente_id').val(item.value);
					buscarServicio(item.value);
				}
			}
		});

		$('.filter').click(function(event) {
			$('#form_bdp').submit();
		});

		$('.exportarAExcel').click(function(event) {
			$('#excel_desde').val($('#periodo_desde').val());
			$('#excel_hasta').val($('#periodo_hasta').val());
			$('#excel_cliente_id').val($('#cliente_id').val());
			$('#exportAExcel').submit();
		});



	});
</script>