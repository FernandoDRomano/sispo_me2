//Este archivo contiene las funciones de grabado (para registrar en la base de datos)

//1-Se verifica que los campos no se pisen
//2-Se comprueba que los datos no esten registrados
//3-Se los registra

function grabar_parte1(){
	noSubmit();

	var resultado_campos_registrados = -1;

	var resultado_campos_superpuestos = verificar_repeticion_superpuestos(matriz1);
	//console.log(resultado_campos_superpuestos);

	if(resultado_campos_superpuestos != 0){
		msj_advertencia1(resultado_campos_superpuestos);
	}
	else{
		//console.log('antes de ajax');
		ajax_campos_repeditos(matriz1);
	}
}


function grabar_parte2(resultado_campos_registrados){
	//console.log(resultado_campos_registrados);
	resultado_evaluacion_campos_registrados = Object.keys(resultado_campos_registrados.comparacion).length;
	//console.log(resultado_evaluacion_campos_registrados);

	if(resultado_evaluacion_campos_registrados != 0){
		msjs_advertencia2(resultado_campos_registrados.comparacion);
	}
	else{
		cargar_input_cantidad_campos();

	//console.log('antes de grabar');
		document.getElementById("formUsuario").submit();

	}
}


function noSubmit(){
	event.preventDefault(); 
}


function verificar_repeticion_superpuestos(matriz){

	var resultado = 0;

	var count_finInicio_erroneo = 0;
	var count_valorIngresado = 0;


	if (valueComboOpciones == 8) {
		for (x = 0; x < matriz.length; x++) {
			var cantidadA = 0;
			var precioA = 0;

			for (y = 0; y < matriz[x].length; y++) {
				switch (y) {
					case 0:
						cantidadA = parseFloat(matriz[x][y]);
						break;
					case 1:
						precioA = parseFloat(matriz[x][y]);
						break;
				}
			}

			if (cantidadA != 0 && precioA != 0) {

				// Evaluo si los campos se repiten
				for (w = 0; w < matriz.length; w++) {

					if (x != w) {
						var cantidadB = 0;
						var finB = 0;

						for (z = 0; z < matriz[w].length; z++) {
							switch (z) {
								case 0:
									cantidadB = matriz[w][z];
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
	}
		

	if (valueComboOpciones == 1 || valueComboOpciones == 2 || valueComboOpciones == 6 || valueComboOpciones == 5) {
		for (x = 0; x < matriz.length; x++) {
			var inicioA = 0;
			var finA = 0;
			var precioA = 0;
			var tipo_opcion = 0;

			for (y = 0; y < matriz[x].length; y++) {
				switch (y) {
					case 0:
						inicioA = parseFloat(matriz[x][y]);
						break;
					case 1:
						finA = parseFloat(matriz[x][y]);
						break;
					case 2:
						precioA = parseFloat(matriz[x][y]);
						break;
					case 3:
						tipo_opcion = parseFloat(matriz[x][y]);
				}
			}

			if (inicioA != 0 && finA != 0 && precioA != 0 && tipo_opcion != 0) {
				// Evaluo si se ingreso un Campos Hasta menor que un Campos Desde
				if (finA < inicioA) {
					count_finInicio_erroneo = count_finInicio_erroneo + 1;
				}

				// Evaluo si los campos se repiten
				for (w = 0; w < matriz.length; w++) {
					if (x != w) {
						var inicioB = 0;
						var finB = 0;

						for (z = 0; z < matriz[w].length; z++) {
							switch (z) {
								case 0:
									inicioB = matriz[w][z];
									break;
								case 1:
									finB = matriz[w][z];
									break;
								case 2:
									//precio = matriz[w][z];
									break;
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
			resultado = 1; // VALOR 1 - AMBOS CASOS DE ABAJO
		} else {
			if (count_finInicio_erroneo > 0) {
				resultado = 2; // VALOR 2 - CAMPO "HASTA" MENOR QUE CAMPO "desde"
			} else {
				if (count_valorIngresado > 0) {
					resultado = 3; // VALOR 3 SUPERPOSICION DE CAMPO DE INICIO / FIN
				}
			}
		}

	}


	if (valueComboOpciones == 7) {
		for (x = 0; x < matriz.length; x++) {
			var tipo_opcionA = 0;
			var precioA = 0;

			for (y = 0; y < matriz[x].length; y++) {
				switch (y) {
					case 0:
						tipo_opcionA = parseFloat(matriz[x][y]);
						break;
					case 1:
						precioA = parseFloat(matriz[x][y]);
						break;
				}
			}

			if (tipo_opcionA != 0 && precioA != 0) {

				// Evaluo si los campos se repiten
				for (w = 0; w < matriz.length; w++) {
					if (x != w) {
						var precioB = 0;
						var tipo_opcionB = 0;

						for (z = 0; z < matriz[w].length; z++) {
							switch (z) {
								case 0:
									tipo_opcionB = matriz[w][z];
									break;
								case 1:
									precioB = matriz[w][z];
									break;
							}

						}

						if (tipo_opcionA != 0 && tipo_opcionB != 0) {
							if (tipo_opcionA == tipo_opcionB) {
								count_valorIngresado = count_valorIngresado + 1;
								break;
							}
						}
					}
				}
			}
		}

		if (count_valorIngresado > 0) {
			resultado = 3; // VALOR 3 SUPERPOSICION DE CAMPO DE INICIO / FIN
		}	

	}


	return resultado;	
}



function ajax_campos_repeditos(matriz){
	cargar_variables_zona();
	cargar_variable_cliente();

	var id_tarifa = null;

    var parametros = {"cliente":valueComboCliente,"tipo_opcion":valueComboOpciones,"zonaA":zonaA_value,"zonaB":zonaB_value,"matriz":matriz,"id_tarifa":id_tarifa};

    $.post("<?php echo base_url('tarifarioT/tarifario_seleccion_opciones/ajax_validar_campos_grabados') ?>", {
				parametros
			},
			function(data, status) {
				data = JSON.parse(data);
				//console.log(data);
				grabar_parte2(data);
			}
		);
}