//Este archivo contiene las funciones de armado de la matriz de datos que se insertan (rangos-precios)
//y para chequear si se insertaron todos los datos

//1-Armado matriz
//2-Verificar que se cargaron todos los datos


function verificar_duplicado_campos(){
	matriz1 = [];
	matriz1 = armado_matriz();

	var bandera_campos_completos1 = verificar_campos_completos(matriz1);
	
	var bandera_min = false;
	bandera_min = verificarMinimosMaximos(matriz1,1);

	var bandera_Max = false;
	bandera_Max = verificarMinimosMaximos(matriz1,2);

	var bandera_enteros = false;
	bandera_enteros = verificarFormatoValores(matriz1);


	if(bandera_campos_completos1 && !bandera_min && !bandera_Max && !bandera_enteros){

		bandera_campos_completos1 = false;

		visibilidad_boton_registro(1);

	}
	else{
		visibilidad_boton_registro(0);
	}

}


function armado_matriz(){
	var matriz_valores = [];
		var matriz_valoresInterna = [];

		var array_ingresos = [];
		var matriz_ingresos_Externa = [];


		switch (valueComboOpciones) {
			case 1:
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
					$('.campoDinamico_kilos').children('div').children('div').find('input, select').each(function() {
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

								countNombresCampos = countNombresCampos + 1;

								break;
						}
					});

				break;
			case 2:
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
					$('.campoDinamico_bultos').children('div').children('div').find('input, select').each(function() {
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

								countNombresCampos = countNombresCampos + 1;

								break;
						}
					});
				
				break;	
			case 3:
				//Declaracion de Variables - Contadores
				var countRecorrido = 0;
				var countNombresCampos = 1;

				//Declaracion de Variables
				var valorCampoPorcentaje = 0;

				var banderaPorcentaje = false;

				var valoresIngresados = false;

				// La siguiente funcion recorre todos los campos de Input de columna en columna, y de fila en fila.
				$('.campoDinamico_cobranza').children('div').children('div').find('input').each(function() {
					countRecorrido = countRecorrido + 1;

					// El valor que toma el switch (countRecorrido), se refiere al numeo de columna en el cual esta la funcion.
					switch (countRecorrido) {
						case 1:
							valorCampoPorcentaje = $(this).val();

							if (valorCampoPorcentaje != 0 && valorCampoPorcentaje != '') {
								banderaPorcentaje = true;
							} else {
								banderaPorcentaje = false;
							}
							matriz_valoresInterna.push(valorCampoPorcentaje);

							if (banderaPorcentaje) {
								valoresIngresados = true;

								array_ingresos.push(valorCampoPorcentaje);
								matriz_ingresos_Externa.push(array_ingresos);
								array_ingresos = [];
							} else {
								valoresIngresados = false;
							}

							countRecorrido = 0;
							countNombresCampos = countNombresCampos + 1;

							break;
					}
				});

				break;				
			case 4:
//Declaracion de Variables - Contadores
				var countRecorrido = 0;
				var countNombresCampos = 1;

				//Declaracion de Variables
				var valorCampoPorcentaje = 0;

				var banderaPorcentaje = false;

				var valoresIngresados = false;

				// La siguiente funcion recorre todos los campos de Input de columna en columna, y de fila en fila.
				$('.campoDinamico_valor_declarado').children('div').children('div').find('input').each(function() {
					countRecorrido = countRecorrido + 1;

					// El valor que toma el switch (countRecorrido), se refiere al numeo de columna en el cual esta la funcion.
					switch (countRecorrido) {
						case 1:
							valorCampoPorcentaje = $(this).val();

							if (valorCampoPorcentaje != 0 && valorCampoPorcentaje != '') {
								banderaPorcentaje = true;
							} else {
								banderaPorcentaje = false;
							}
							matriz_valoresInterna.push(valorCampoPorcentaje);

							if (banderaPorcentaje) {
								valoresIngresados = true;

								array_ingresos.push(valorCampoPorcentaje);
								matriz_ingresos_Externa.push(array_ingresos);
								array_ingresos = [];
							} else {
								valoresIngresados = false;
							}

							countRecorrido = 0;
							countNombresCampos = countNombresCampos + 1;

							break;
					}
				});				
				break;	
			case 5:
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
					$('.campoDinamico_metro_cubico').children('div').children('div').find('input, select').each(function() {
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

								countNombresCampos = countNombresCampos + 1;

								break;
						}
					});
				break;	
			case 6:
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
					$('.campoDinamico_palets').children('div').children('div').find('input, select').each(function() {
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

								countNombresCampos = countNombresCampos + 1;

								break;
						}
					});			

				break;
			case 7:
				//Declaracion de Variables - Contadores
				var countRecorrido = 0;
				var countNombresCampos = 1;

				//Declaracion de Variables
				var valorCampoHoras = 0;
				var valorCampoPrecio = 0;

				var banderaOpcionHoras = false;
				var banderaPrecio = false;

				var valoresIngresados = false;

				// La siguiente funcion recorre todos los campos de Input de columna en columna, y de fila en fila.
				$('.campoDinamico_gestion_flota').children('div').children('div').find('input, select').each(function() {
					countRecorrido = countRecorrido + 1;

					// El valor que toma el switch (countRecorrido), se refiere al numeo de columna en el cual esta la funcion.
					switch (countRecorrido) {
						case 1:
							valorCampoHoras = $(this).val();

							if (valorCampoHoras != 0 && valorCampoHoras != '') {
								banderaOpcionHoras = true;
							} else {
								banderaOpcionHoras = false;
							}
							matriz_valoresInterna.push(valorCampoHoras);

							break;
						case 2:
							valorCampoPrecio = $(this).val();

							if (valorCampoPrecio != 0 && valorCampoPrecio != '') {
								banderaPrecio = true;
							} else {
								banderaPrecio = false;
							}
							matriz_valoresInterna.push(valorCampoPrecio);


							if (banderaOpcionHoras && banderaPrecio) {
								valoresIngresados = true;

								array_ingresos.push(valorCampoHoras, valorCampoPrecio);
								matriz_ingresos_Externa.push(array_ingresos);
								array_ingresos = [];
							} else {
								valoresIngresados = false;
							}

							countRecorrido = 0;
							countNombresCampos = countNombresCampos + 1;

							break;
					}
				});			

				break;						
			case 8:
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
				$('.campoDinamico_warehouse').children('div').children('div').find('input').each(function() {
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
							countNombresCampos = countNombresCampos + 1;

							break;
					}
				});			
				break;

		}
/*
		console.log(valoresIngresados);
	console.log('revision_campos_ll');
	console.log(matriz_ingresos_Externa);*/

	return matriz_ingresos_Externa;
}


function verificar_campos_completos(matriz1){
	var bandera_campos_completos = false;
	var cantidad_campos = 0;

	switch(valueComboOpciones){
		case 1:
			cantidad_campos = 4;
			break;
		case 2:
			cantidad_campos = 4;
			break;
		case 3:
			cantidad_campos = 1;
			break;	
		case 4:
			cantidad_campos = 1;
			break;	
		case 5:
			cantidad_campos = 4;
			break;						
		case 6:
			cantidad_campos = 4;
			break;
		case 7:
			cantidad_campos = 2;
			break;		
		case 8:
			cantidad_campos = 2;
			break;			
	}

	var cantidad_secciones = countX;

	var total_campos = cantidad_campos * cantidad_secciones;

	//console.log(matriz1);

	var contador_campos = 0;
	for(var i=0; i<matriz1.length; i++){
		for(var ii=0; ii<matriz1[i].length; ii++){
			contador_campos = contador_campos + 1;
		}
	}


	//console.log(total_campos + ' / ' + contador_campos);

	if(total_campos == contador_campos){
		bandera_campos_completos = true;
	}

	return bandera_campos_completos;
}

function comprobar_registros_existentes_cobranza_gestionFlota(){
	//verificacion de gestion de flota y warehouse
	if(valueComboOpciones == 7 || valueComboOpciones == 3 || valueComboOpciones == 4 || valueComboOpciones == 5){
		ajax_comprobacion_importes_registrados();
	}

}


function ajax_comprobacion_importes_registrados(){
	cargar_variables_zona();
	cargar_variable_cliente();

    var parametros = {"cliente":valueComboCliente,"tipo_opcion":valueComboOpciones,"zonaA":zonaA_value,"zonaB":zonaB_value};

    $.post("<?php echo base_url('tarifarioT/tarifario_seleccion_opciones/ajax_registros_unicos') ?>", {
				parametros
			},
			function(data, status) {
				data = JSON.parse(data);
			//	console.log(data.tarifas);

				if(data.tarifas > 0){
					switch(valueComboOpciones){
						case 3:
							visibilidad_cobranza(true);
							msj_cobranza_existente();
							break;
						case 4:
							visibilidad_valor_declarado(true);
							msj_valor_declarado_existente();					
							break;
					/*	case 5:
							visibilidad_metro_cubico(true);
							msj_metro_cubico_existente();					
							break;*/
						case 7:
							if(data.tarifas == 10){
								visibilidad_gestion_flota(true);
								msj_gestion_flota_existente();
							}
							break;
					}
				}

				if(valueComboOpciones == 7){
					seteo_variable_options(data.detalle);	
				}
				

			}
		);
}



function verificarMinimosMaximos(matriz1,opcion_mM){
	var bandera = false;

	var minKilos = 1;
	var maxKilos = 500;

	var minBultos = 1;

	var minCobranza = 1.5;
	var maxCobranza = 50;

	var minValorDeclarado = 0.01;

	var minMetroCubico = 0.01;

	var minPalets = 1;
	var maxPelets = 200;

	var minWareHouse = 1;

	var minPrecio = 0.01;
	
	if(opcion_mM == 1){
		switch(valueComboOpciones){
			case 1:

				for(var i = 0; i<matriz1.length; i++){
					var valorCampoInicio = parseFloat(matriz1[i][0]);
					var valorCampoFin = parseFloat(matriz1[i][1]);
					var valorCampoPrecio = parseFloat(matriz1[i][2]);

					if(valorCampoInicio < minKilos || valorCampoFin < minKilos || valorCampoPrecio < minPrecio){
						mensaje_min(minKilos);
						bandera = true;				
					}
				}

				break;
			case 2:

				for(var i = 0; i<matriz1.length; i++){
					var valorCampoInicio = parseFloat(matriz1[i][0]);
					var valorCampoFin = parseFloat(matriz1[i][1]);
					var valorCampoPrecio = parseFloat(matriz1[i][2]);

					if(valorCampoInicio < minBultos || valorCampoFin < minBultos || valorCampoPrecio < minPrecio){
						mensaje_min(minBultos);
						bandera = true;				
					}
				}

				break;			
			case 3:

				for(var i = 0; i<matriz1.length; i++){
					var valorCampo = parseFloat(matriz1[i][0]);

					if(valorCampo < minCobranza){
						mensaje_min(minCobranza);
						bandera = true;				
					}
				}

				break;
			case 4:

				for(var i = 0; i<matriz1.length; i++){
					var valorCampo = parseFloat(matriz1[i][0]);

					if(valorCampo < minValorDeclarado){
						mensaje_min(minValorDeclarado);
						bandera = true;				
					}
				}

				break;				
			case 5:

				for(var i = 0; i<matriz1.length; i++){
					var valorCampoInicio = parseFloat(matriz1[i][0]);
					var valorCampoFin = parseFloat(matriz1[i][1]);
					var valorCampoPrecio = parseFloat(matriz1[i][2]);

					if(valorCampoInicio < minMetroCubico || valorCampoFin < minMetroCubico || valorCampoPrecio < minPrecio){
						mensaje_min(minMetroCubico);
						bandera = true;				
					}
				}

				break;				
			case 6:

				for(var i = 0; i<matriz1.length; i++){
					var valorCampoInicio = parseFloat(matriz1[i][0]);
					var valorCampoFin = parseFloat(matriz1[i][1]);
					var valorCampoPrecio = parseFloat(matriz1[i][2]);

					if(valorCampoInicio < minPalets || valorCampoFin < minPalets || valorCampoPrecio < minPrecio){
						mensaje_min(minPalets);
						bandera = true;				
					}
				}

				break;	
			case 7:

				for(var i = 0; i<matriz1.length; i++){
					var valorCampoPrecio = parseFloat(matriz1[i][1]);

					if(valorCampoPrecio < minPrecio){
						mensaje_min(minPrecio);
						bandera = true;				
					}
				}

				break;	
			case 8:

				for(var i = 0; i<matriz1.length; i++){
					var valorCampoCantidad = parseFloat(matriz1[i][0]);
					var valorCampoPrecio = parseFloat(matriz1[i][1]);

					if(valorCampoCantidad < minWareHouse || valorCampoPrecio < minPrecio){
						mensaje_min(minPrecio);
						bandera = true;				
					}
				}

				break;											
		}

	}



	if(opcion_mM == 2){
		
		switch(valueComboOpciones){
			case 1:
				for(var i = 0; i<matriz1.length; i++){
					var valorCampo = parseFloat(matriz1[i][1]);

					if(valorCampo > maxKilos){
						mensaje_max(maxKilos);
						bandera = true;						
					}
				}

				break;
			case 3:
				for(var i = 0; i<matriz1.length; i++){
					var valorCampo = parseFloat(matriz1[i][0]);

					if(valorCampo > maxCobranza){
						mensaje_max(maxCobranza);
						bandera = true;						
					}
				}

				break;

			case 6:
				for(var i = 0; i<matriz1.length; i++){
					var valorCampo = parseFloat(matriz1[i][1]);

					if(valorCampo > maxPelets){
						mensaje_max(maxPelets);
						bandera = true;						
					}
				}

				break;				
		}

	}

	return bandera;
}


function verificarFormatoValores(matriz1){
	bandera = false

	switch(valueComboOpciones){
		case 2:

			for(var i = 0; i<matriz1.length; i++){

				var valorCampoDesde = matriz1[i][0];
				var valorCampoHasta = matriz1[i][1];

				if(valorCampoDesde.toString().indexOf('.') != -1 || valorCampoHasta.toString().indexOf('.') != -1){
					mensaje_decimales();
					bandera = true;
				}
			}

			break;
		case 8:

			for(var i = 0; i<matriz1.length; i++){
				var valorCampoCantidad = matriz1[i][0];

				if(valorCampoCantidad.toString().indexOf('.') != -1){
					mensaje_decimales();
					bandera = true;
				}
			}

			break;			
	}


	return bandera;
}