//Este archivo contiene las funciones para agregar o quitar campos y para renombrar dichos campos dinamicamente


$(document).ready(function() {
	var maxField = 10;
	var addButton = $('.agregar_campos');
	//var wrapper = $('#rangos_precios_bultos'); 

	$(addButton).click(function() {

		if (countX < maxField) {
			countX++;
		//	console.log(countX);

			switch (valueComboOpciones) {
				case 1: // Kilos
					var wrapper = $('#rangos_precios_kilos');
					var fieldHTML = '<div class="row campoDinamico_kilos campo_agregado"><div class="col-xs-3"><div class="form-group"><label>Desde</label><input id="rinicio_kilos' + countX + '" placeholder="Ej.: 10" type="number" name="rinicio_kilos' + countX + '" onkeyup="verificar_duplicado_campos()" class="form-control filtro-group" step="0.01"></div></div><div class="col-xs-3"><div class="form-group"><label>Hasta</label><input id="rfin_kilos' + countX + '" placeholder="Ej.: 20" type="number" name="rfin_kilos' + countX + '" onkeyup="verificar_duplicado_campos()" class="form-control filtro-group" step="0.01"></div></div><div class="col-xs-3"><div class="form-group"><label>Precio</label><input style="" placeholder="$" type="number" id="precio_kilos' + countX + '" name="precio_kilos' + countX + '" onkeyup="verificar_duplicado_campos()" class="form-control filtro-group" step="0.01"></div></div><div class="col-xs-2"><div class="form-group"><label>Tipo</label><select id="opciones_tipo_kilos' + countX + '" name="opciones_tipo_kilos' + countX + '" onchange="verificar_duplicado_campos()" class="form-control filtro-group"><option value="0">...</option><option value="1">Bloque</option><option value="2">Unidad</option></select></div></div><div class="col-xs-1"><div class="form-group"><br><a href="javascript:void(0);" class="remover_campos btn btn-success btn-submit" title="Remove field" name="c_signoMenos">-</a></div></div></div>';
					break;
				case 2: // Bultos
					var wrapper = $('#rangos_precios_bultos');
					var fieldHTML = '<div class="row campoDinamico_bultos campo_agregado"><div class="col-xs-3"><div class="form-group"><label>Desde</label><input id="rinicio_bultos' + countX + '" placeholder="Ej.: 10" type="number" name="rinicio_bultos' + countX + '" onkeyup="verificar_duplicado_campos()" class="form-control filtro-group"></div></div><div class="col-xs-3"><div class="form-group"><label>Hasta</label><input id="rfin_bultos' + countX + '" placeholder="Ej.: 20" type="number" name="rfin_bultos' + countX + '" onkeyup="verificar_duplicado_campos()" class="form-control filtro-group"></div></div><div class="col-xs-3"><div class="form-group"><label>Precio</label><input style="" placeholder="$" type="number" id="precio_bultos' + countX + '" name="precio_bultos' + countX + '" onkeyup="verificar_duplicado_campos()" class="form-control filtro-group" step="0.01"></div></div><div class="col-xs-2"><div class="form-group"><label>Tipo</label><select id="opciones_tipo_bultos' + countX + '" name="opciones_tipo_bultos' + countX + '" onchange="verificar_duplicado_campos()" class="form-control filtro-group"><option value="0">...</option><option value="1">Bloque</option><option value="2">Unidad</option></select></div></div><div class="col-xs-1"><div class="form-group"><br><a href="javascript:void(0);" class="remover_campos btn btn-success btn-submit" title="Remove field" name="c_signoMenos">-</a></div></div></div>';
					break;
				case 3: //Cobranza //Por el momento no se usa porque es unico por cliente
					var wrapper = $('#rangos_cobranza');
					
					break;
				case 4: // Valor Declarado // Por el momento no se usa porque es unico por cliente
					var wrapper = $('#rangos_valor_declarado');
				
					break;
				case 5:
					var wrapper = $('#metro_cubico_precio');
					var fieldHTML = '<div class="row campoDinamico_metro_cubico campo_agregado"><div class="col-xs-3"><div class="form-group"><label>Desde</label><input id="rinicio_mc' + countX + '" placeholder="Ej.: 10" type="number" name="rinicio_mc' + countX + '" onkeyup="verificar_duplicado_campos()" class="form-control filtro-group" step="0.01"></div></div><div class="col-xs-3"><div class="form-group"><label>Hasta</label><input id="rfin_mc' + countX + '" placeholder="Ej.: 20" type="number" name="rfin_mc' + countX + '" onkeyup="verificar_duplicado_campos()" class="form-control filtro-group" step="0.01"></div></div><div class="col-xs-3"><div class="form-group"><label>Precio</label><input style="" placeholder="$" type="number" id="precio_mc' + countX + '" name="precio_mc' + countX + '" onkeyup="verificar_duplicado_campos()" class="form-control filtro-group" step="0.01"></div></div><div class="col-xs-2"><div class="form-group"><label>Tipo</label><select id="opciones_tipo_mc' + countX + '" name="opciones_tipo_mc' + countX + '" onchange="verificar_duplicado_campos()" class="form-control filtro-group"><option value="0">...</option><option value="1">Bloque</option><option value="2">Unidad</option></select></div></div><div class="col-xs-1"><div class="form-group"><br><a href="javascript:void(0);" class="remover_campos btn btn-success btn-submit" title="Remove field" name="c_signoMenos">-</a></div></div></div>';
					//var fieldHTML = '<div class="row campoDinamico_metro_cubico campo_agregado"><div class="col-xs-3"><div class="form-group"><label>Medida</label><input id="valor_mc1" placeholder="Ej.: 10" type="number" name="valor_mc1" onkeyup="verificar_duplicado_campos()" class="form-control filtro-group">	</div></div><div class="col-xs-3"><div class="form-group"><label>Precio</label><input placeholder="$" type="number" id="precio_mc1" name="precio_mc1" onkeyup="verificar_duplicado_campos()" class="form-control filtro-group" step="0.01"></div></div><div class="col-xs-1"><div class="form-group"><br><a href="javascript:void(0);" class="remover_campos btn btn-success btn-submit" title="Remove field" name="c_signoMenos">-</a></div></div></div>'
					break;
				case 6: // Palets
					var wrapper = $('#rangos_precios_palets');
					//var fieldHTML = '<div class="row campoDinamico_palets campo_agregado"><div class="col-xs-3"><div class="form-group"><label>Cantidad</label><input id="cantidad_palets' + countX + '" placeholder="Ej.: 10" type="number" name="cantidad_palets' + countX + '" onkeyup="verificar_duplicado_campos()" class="form-control filtro-group"></div></div><div class="col-xs-3"><div class="form-group"><label>Precio</label><input placeholder="$" type="number" id="precio_palets' + countX + '" name="precio_palets' + countX + '" onkeyup="verificar_duplicado_campos()" class="form-control filtro-group" step="0.01"></div></div><div class="col-xs-1"><div class="form-group"><br><a href="javascript:void(0);" class="remover_campos btn btn-success btn-submit" title="Remove field" name="c_signoMenos">-</a></div></div></div>';
					var fieldHTML = '<div class="row campoDinamico_palets campo_agregado"><div class="col-xs-3"><div class="form-group"><label>Desde</label><input id="rinicio_palets' + countX + '" placeholder="Ej.: 10" type="number" name="rinicio_palets' + countX + '" onkeyup="verificar_duplicado_campos()" class="form-control filtro-group"></div></div><div class="col-xs-3"><div class="form-group"><label>Hasta</label><input id="rfin_palets' + countX + '" placeholder="Ej.: 20" type="number" name="rfin_palets' + countX + '" onkeyup="verificar_duplicado_campos()" class="form-control filtro-group"></div></div><div class="col-xs-3"><div class="form-group"><label>Precio</label><input style="" placeholder="$" type="number" id="precio_palets' + countX + '" name="precio_palets' + countX + '" onkeyup="verificar_duplicado_campos()" class="form-control filtro-group" step="0.01"></div></div><div class="col-xs-2"><div class="form-group"><label>Tipo</label><select id="opciones_tipo_palets' + countX + '" name="opciones_tipo_palets' + countX + '" onchange="verificar_duplicado_campos()" class="form-control filtro-group"><option value="0">...</option><option value="1">Bloque</option><option value="2">Unidad</option></select></div></div><div class="col-xs-1"><div class="form-group"><br><a href="javascript:void(0);" class="remover_campos btn btn-success btn-submit" title="Remove field" name="c_signoMenos">-</a></div></div></div>';
					break;
				case 7: // Gestion de Flota
					var wrapper = $('#horarios_gestion_flota');
					var fieldHTML = '<div class="row campoDinamico_gestion_flota campo_agregado"><div class="col-xs-3"><div class="form-group"><label>Vehiculo // Hora</label><select id="opcion_horas_gf' + countX + '" name="opcion_horas_gf' + countX + '" onchange="verificar_duplicado_campos()" class="form-control filtro-group"><option value="0">...</option></select></div></div><div class="col-xs-3"><div class="form-group"><label>Precio</label><input placeholder="$" type="number" id="precio_gf1" name="precio_gf1" onkeyup="verificar_duplicado_campos()" class="form-control filtro-group" step="0.01"></div></div><div class="col-xs-1"><div class="form-group"><br><a href="javascript:void(0);" class="remover_campos btn btn-success btn-submit" title="Remove field" name="c_signoMenos" onClick="visibilidad_etiqueta_mas_gf(true)">-</a></div></div></div>';
					break;					
				case 8: // Warehouse
					var wrapper = $('#cantidad_precio_warehouse');
					var fieldHTML = '<div class="row campoDinamico_warehouse campo_agregado"><div class="col-xs-3"><div class="form-group"><label>Cantidad</label><input id="posicion_wh' + countX + '" placeholder="Ej.: 10" type="number" name="posicion_wh' + countX + '" onkeyup="verificar_duplicado_campos()" class="form-control filtro-group"></div></div><div class="col-xs-3"><div class="form-group"><label>Precio</label><input placeholder="$" type="number" id="precio_wh' + countX + '" name="precio_wh' + countX + '" onkeyup="verificar_duplicado_campos()" class="form-control filtro-group" step="0.01"></div></div><div class="col-xs-1"><div class="form-group"><br><a href="javascript:void(0);" class="remover_campos btn btn-success btn-submit" title="Remove field" name="c_signoMenos">-</a></div></div></div>';

					break;
				default:
					console.log('..EN CONSTRUCCION...!');
				//	break;
			}

			$(wrapper).append(fieldHTML);

			if(valueComboOpciones == 7){
				$("#opcion_horas_gf" + countX).children('option:not(:first)').remove();
				
				var seccion = $("#opcion_horas_gf" + countX);
				$(seccion).append(options_gestion_flota);				
			}

			modificar_nombres_camposDinamicos();
		}
	});




	$(wrapper).on('click', '.remover_campos', function(e) {
		e.preventDefault();
		$(this).parent('div').parent('div').parent('div').remove();
		countX--;

		// console.log(countX);

		modificar_nombres_camposDinamicos();
	});

});


function modificar_nombres_camposDinamicos() {
	//Declaracion de Variables - Contadores
	var countRecorrido = 0;
	var countNombresCampos = 1;


	switch (valueComboOpciones) {
		case 1: //Kilos
				var nombreEdit1Id_inicio = 'rinicio_kilos';
				var nombreEdit1Id_fin = 'rfin_kilos';
				var nombreEdit1Id_precio = 'precio_kilos';
				var nombreEdit1Id_tipo = 'opciones_tipo_kilos';

				var nombreEdit2Id_tipo = '';
				var nombreEdit2Name_tipo = '';
				var nombreEdit2Id_inicio = '';
				var nombreEdit2Name_inicio = '';
				var nombreEdit2Id_fin = '';
				var nombreEdit2Name_fin = '';
				var nombreEdit2Id_precio = '';
				var nombreEdit2Name_precio = '';

				$('.campoDinamico_kilos').children('div').children('div').find('input, select').each(function() {
					countRecorrido = countRecorrido + 1;

					switch (countRecorrido) {
						case 1:
							nombreEdit2Id_inicio = nombreEdit1Id_inicio + countNombresCampos;
							$(this).attr('id', nombreEdit2Id_inicio);

							nombreEdit2Name_inicio = nombreEdit1Id_inicio + countNombresCampos;
							$(this).attr('name', nombreEdit2Name_inicio);
							break;
						case 2:
							nombreEdit2Id_fin = nombreEdit1Id_fin + countNombresCampos;
							$(this).attr('id', nombreEdit2Id_fin);

							nombreEdit2Name_fin = nombreEdit1Id_fin + countNombresCampos;
							$(this).attr('name', nombreEdit2Name_fin);
							break;
						case 3:
							nombreEdit2Id_precio = nombreEdit1Id_precio + countNombresCampos;
							$(this).attr('id', nombreEdit2Id_precio);

							nombreEdit2Name_precio = nombreEdit1Id_precio + countNombresCampos;
							$(this).attr('name', nombreEdit2Name_precio);
							break;
						case 4:
							nombreEdit2Id_tipo = nombreEdit1Id_tipo + countNombresCampos;
							$(this).attr('id', nombreEdit2Id_tipo);

							nombreEdit2Name_tipo = nombreEdit1Id_tipo + countNombresCampos;
							$(this).attr('name', nombreEdit2Name_tipo);

							countRecorrido = 0;
							countNombresCampos = countNombresCampos + 1;
							break;
					}
				});

			break;
		case 2: // Bultos
				var nombreEdit1Id_inicio = 'rinicio_bultos';
				var nombreEdit1Id_fin = 'rfin_bultos';
				var nombreEdit1Id_precio = 'precio_bultos';
				var nombreEdit1Id_tipo = 'opciones_tipo_bultos';

				var nombreEdit2Id_tipo = '';
				var nombreEdit2Name_tipo = '';
				var nombreEdit2Id_inicio = '';
				var nombreEdit2Name_inicio = '';
				var nombreEdit2Id_fin = '';
				var nombreEdit2Name_fin = '';
				var nombreEdit2Id_precio = '';
				var nombreEdit2Name_precio = '';

				$('.campoDinamico_bultos').children('div').children('div').find('input, select').each(function() {
					countRecorrido = countRecorrido + 1;

					switch (countRecorrido) {
						case 1:
							nombreEdit2Id_inicio = nombreEdit1Id_inicio + countNombresCampos;
							$(this).attr('id', nombreEdit2Id_inicio);

							nombreEdit2Name_inicio = nombreEdit1Id_inicio + countNombresCampos;
							$(this).attr('name', nombreEdit2Name_inicio);
							break;
						case 2:
							nombreEdit2Id_fin = nombreEdit1Id_fin + countNombresCampos;
							$(this).attr('id', nombreEdit2Id_fin);

							nombreEdit2Name_fin = nombreEdit1Id_fin + countNombresCampos;
							$(this).attr('name', nombreEdit2Name_fin);
							break;
						case 3:
							nombreEdit2Id_precio = nombreEdit1Id_precio + countNombresCampos;
							$(this).attr('id', nombreEdit2Id_precio);

							nombreEdit2Name_precio = nombreEdit1Id_precio + countNombresCampos;
							$(this).attr('name', nombreEdit2Name_precio);
							break;
						case 4:
							nombreEdit2Id_tipo = nombreEdit1Id_tipo + countNombresCampos;
							$(this).attr('id', nombreEdit2Id_tipo);

							nombreEdit2Name_tipo = nombreEdit1Id_tipo + countNombresCampos;
							$(this).attr('name', nombreEdit2Name_tipo);

							countRecorrido = 0;
							countNombresCampos = countNombresCampos + 1;
							break;
					}
				});

			break;
		case 3: //Cobranza
		/*	var nombreEdit1Id_porcentaje = 'porcentaje_cobranza';

			var nombreEdit2Id_porcentaje = '';
			var nombreEdit2Name_porcentaje = '';

			$('.campoDinamico_cobranza').children('div').children('div').find('input').each(function() {
				countRecorrido = countRecorrido + 1;

				switch (countRecorrido) {
					case 1:
						nombreEdit2Id_porcentaje = nombreEdit1Id_porcentaje + countNombresCampos;
						$(this).attr('id', nombreEdit2Id_porcentaje);

						nombreEdit2Name_porcentaje = nombreEdit1Id_porcentaje + countNombresCampos;
						$(this).attr('name', nombreEdit2Name_porcentaje);

						countRecorrido = 0;
						countNombresCampos = countNombresCampos + 1;

						break;
				}
			});
*/
			break;
		case 4:
		/*	var nombreEdit1Id_porcentaje = 'porcentaje_valor_declarado';

			var nombreEdit2Id_porcentaje = '';
			var nombreEdit2Name_porcentaje = '';

			$('.campoDinamico_cobranza').children('div').children('div').find('input').each(function() {
				countRecorrido = countRecorrido + 1;

				switch (countRecorrido) {
					case 1:
						nombreEdit2Id_porcentaje = nombreEdit1Id_porcentaje + countNombresCampos;
						$(this).attr('id', nombreEdit2Id_porcentaje);

						nombreEdit2Name_porcentaje = nombreEdit1Id_porcentaje + countNombresCampos;
						$(this).attr('name', nombreEdit2Name_porcentaje);

						countRecorrido = 0;
						countNombresCampos = countNombresCampos + 1;

						break;
				}
			});
*/
	
			break;
		case 5: // Metro Cubico
				var nombreEdit1Id_inicio = 'rinicio_mc';
				var nombreEdit1Id_fin = 'rfin_mc';
				var nombreEdit1Id_precio = 'precio_mc';
				var nombreEdit1Id_tipo = 'opciones_tipo_mc';

				var nombreEdit2Id_tipo = '';
				var nombreEdit2Name_tipo = '';
				var nombreEdit2Id_inicio = '';
				var nombreEdit2Name_inicio = '';
				var nombreEdit2Id_fin = '';
				var nombreEdit2Name_fin = '';
				var nombreEdit2Id_precio = '';
				var nombreEdit2Name_precio = '';

				$('.campoDinamico_metro_cubico').children('div').children('div').find('input, select').each(function() {
					countRecorrido = countRecorrido + 1;

					switch (countRecorrido) {
						case 1:
							nombreEdit2Id_inicio = nombreEdit1Id_inicio + countNombresCampos;
							$(this).attr('id', nombreEdit2Id_inicio);

							nombreEdit2Name_inicio = nombreEdit1Id_inicio + countNombresCampos;
							$(this).attr('name', nombreEdit2Name_inicio);
							break;
						case 2:
							nombreEdit2Id_fin = nombreEdit1Id_fin + countNombresCampos;
							$(this).attr('id', nombreEdit2Id_fin);

							nombreEdit2Name_fin = nombreEdit1Id_fin + countNombresCampos;
							$(this).attr('name', nombreEdit2Name_fin);
							break;
						case 3:
							nombreEdit2Id_precio = nombreEdit1Id_precio + countNombresCampos;
							$(this).attr('id', nombreEdit2Id_precio);

							nombreEdit2Name_precio = nombreEdit1Id_precio + countNombresCampos;
							$(this).attr('name', nombreEdit2Name_precio);
							break;
						case 4:
							nombreEdit2Id_tipo = nombreEdit1Id_tipo + countNombresCampos;
							$(this).attr('id', nombreEdit2Id_tipo);

							nombreEdit2Name_tipo = nombreEdit1Id_tipo + countNombresCampos;
							$(this).attr('name', nombreEdit2Name_tipo);

							countRecorrido = 0;
							countNombresCampos = countNombresCampos + 1;
							break;
					}
				});		
			break;

		case 6: // Palets
			var nombreEdit1Id_inicio = 'rinicio_palets';
			var nombreEdit1Id_fin = 'rfin_palets';
			var nombreEdit1Id_precio = 'precio_palets';
			var nombreEdit1Id_tipo = 'opciones_tipo_palets';

			var nombreEdit2Id_tipo = '';
			var nombreEdit2Name_tipo = '';
			var nombreEdit2Id_inicio = '';
			var nombreEdit2Name_inicio = '';
			var nombreEdit2Id_fin = '';
			var nombreEdit2Name_fin = '';
			var nombreEdit2Id_precio = '';
			var nombreEdit2Name_precio = '';

			$('.campoDinamico_palets').children('div').children('div').find('input, select').each(function() {
				countRecorrido = countRecorrido + 1;

				switch (countRecorrido) {
					case 1:
						nombreEdit2Id_inicio = nombreEdit1Id_inicio + countNombresCampos;
						$(this).attr('id', nombreEdit2Id_inicio);

						nombreEdit2Name_inicio = nombreEdit1Id_inicio + countNombresCampos;
						$(this).attr('name', nombreEdit2Name_inicio);
						break;
					case 2:
						nombreEdit2Id_fin = nombreEdit1Id_fin + countNombresCampos;
						$(this).attr('id', nombreEdit2Id_fin);

						nombreEdit2Name_fin = nombreEdit1Id_fin + countNombresCampos;
						$(this).attr('name', nombreEdit2Name_fin);
						break;
					case 3:
						nombreEdit2Id_precio = nombreEdit1Id_precio + countNombresCampos;
						$(this).attr('id', nombreEdit2Id_precio);

						nombreEdit2Name_precio = nombreEdit1Id_precio + countNombresCampos;
						$(this).attr('name', nombreEdit2Name_precio);
						break;
					case 4:
						nombreEdit2Id_tipo = nombreEdit1Id_tipo + countNombresCampos;
						$(this).attr('id', nombreEdit2Id_tipo);

						nombreEdit2Name_tipo = nombreEdit1Id_tipo + countNombresCampos;
						$(this).attr('name', nombreEdit2Name_tipo);

						countRecorrido = 0;
						countNombresCampos = countNombresCampos + 1;
						break;
				}
			});

			break;
	    case 7:
			var nombreEdit1Id_opcion_hora = 'opcion_horas_gf';
			var nombreEdit1Id_precio = 'precio_gf';

			var nombreEdit2Id_opcion_hora = '';
			var nombreEdit2Name_opcion_hora = '';
			var nombreEdit2Id_precio = '';
			var nombreEdit2Name_precio = '';

			$('.campoDinamico_gestion_flota').children('div').children('div').find('input, select').each(function() {
				countRecorrido = countRecorrido + 1;

				switch (countRecorrido) {
					case 1:
						nombreEdit2Id_opcion_hora = nombreEdit1Id_opcion_hora + countNombresCampos;
						$(this).attr('id', nombreEdit2Id_opcion_hora);

						nombreEdit2Name_opcion_hora = nombreEdit1Id_opcion_hora + countNombresCampos;
						$(this).attr('name', nombreEdit2Name_opcion_hora);
						break;
					case 2:
						nombreEdit2Id_precio = nombreEdit1Id_precio + countNombresCampos;
						$(this).attr('id', nombreEdit2Id_precio);

						nombreEdit2Name_precio = nombreEdit1Id_precio + countNombresCampos;
						$(this).attr('name', nombreEdit2Name_precio);


						countRecorrido = 0;
						countNombresCampos = countNombresCampos + 1;

						break;
				}
			});

	    	break;			
		case 8:
			var nombreEdit1Id_cantidad = 'cantidad_wh';
			var nombreEdit1Id_precio = 'precio_wh';

			var nombreEdit2Id_cantidad = '';
			var nombreEdit2Name_cantidad = '';
			var nombreEdit2Id_precio = '';
			var nombreEdit2Name_precio = '';

			$('.campoDinamico_warehouse').children('div').children('div').find('input').each(function() {
				countRecorrido = countRecorrido + 1;

				switch (countRecorrido) {
					case 1:
						nombreEdit2Id_cantidad = nombreEdit1Id_cantidad + countNombresCampos;
						$(this).attr('id', nombreEdit2Id_cantidad);

						nombreEdit2Name_cantidad = nombreEdit1Id_cantidad + countNombresCampos;
						$(this).attr('name', nombreEdit2Name_cantidad);
						break;
					case 2:
						nombreEdit2Id_precio = nombreEdit1Id_precio + countNombresCampos;
						$(this).attr('id', nombreEdit2Id_precio);

						nombreEdit2Name_precio = nombreEdit1Id_precio + countNombresCampos;
						$(this).attr('name', nombreEdit2Name_precio);


						countRecorrido = 0;
						countNombresCampos = countNombresCampos + 1;

						break;
				}
			});		
			break;
		default:
	}


	verificar_duplicado_campos();
}