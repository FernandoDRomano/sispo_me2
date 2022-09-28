function edicion_parte1(){
	event.preventDefault(); 

	var bandera_campos_correctos = true;

	var bandera_minimos = false;
	var bandera_maximos = false;
	var bandera_formato_campos = false;
	var bandera_comparacion_rangos = true;

	switch(opcion_elegida){
		case 1:
				var desde = parseFloat(document.getElementById("desde").value);
				var hasta =  parseFloat(document.getElementById("hasta").value);
				var precio =  parseFloat(document.getElementById("precio").value);
				var tipo =  parseInt(document.getElementById("tipo").value);

				if(desde > hasta){
					bandera_comparacion_rangos = false;
				}

				datos = [desde, hasta, precio, tipo];

				datos.forEach(function(el) {

					if(el == 0 || el < 0 || isNaN(el)){
						bandera_campos_correctos = false;
					}

				});
			break;
		case 2:
				var desde = parseFloat(document.getElementById("desde").value);
				var hasta =  parseFloat(document.getElementById("hasta").value);
				var precio =  parseFloat(document.getElementById("precio").value);
				var tipo =  parseInt(document.getElementById("tipo").value);

				if(desde > hasta){
					bandera_comparacion_rangos = false;
				}

				datos = [desde, hasta, precio, tipo];

				datos.forEach(function(el) {

					if(el == 0 || el < 0 || isNaN(el)){
						bandera_campos_correctos = false;
					}

				});
			break;	
		case 3:
				var porcentaje = parseFloat(document.getElementById("porcentaje").value);

				datos = [porcentaje];

				datos.forEach(function(el) {

					if(el == 0 || el < 0 || isNaN(el)){
						bandera_campos_correctos = false;
					}

				});
			break;	
		case 4:
				var porcentaje = parseFloat(document.getElementById("porcentaje").value);

				datos = [porcentaje];

				datos.forEach(function(el) {

					if(el == 0 || el < 0 || isNaN(el)){
						bandera_campos_correctos = false;
					}

				});
			break;
		case 5:
				var desde = parseFloat(document.getElementById("desde").value);
				var hasta =  parseFloat(document.getElementById("hasta").value);
				var precio =  parseFloat(document.getElementById("precio").value);
				var tipo =  parseInt(document.getElementById("tipo").value);

				if(desde > hasta){
					bandera_comparacion_rangos = false;
				}

				datos = [desde, hasta, precio, tipo];

				datos.forEach(function(el) {

					if(el == 0 || el < 0 || isNaN(el)){
						bandera_campos_correctos = false;
					}

				});
			break;
		case 6:
				var desde = parseFloat(document.getElementById("desde").value);
				var hasta =  parseFloat(document.getElementById("hasta").value);
				var precio =  parseFloat(document.getElementById("precio").value);
				var tipo =  parseInt(document.getElementById("tipo").value);

				if(desde > hasta){
					bandera_comparacion_rangos = false;
				}

				datos = [desde, hasta, precio, tipo];

				datos.forEach(function(el) {

					if(el == 0 || el < 0 || isNaN(el)){
						bandera_campos_correctos = false;
					}

				});
			break;	
		case 7:
				var precio =  parseFloat(document.getElementById("precio").value);

				datos = [precio];

				datos.forEach(function(el) {

					if(el == 0 || el < 0 || isNaN(el)){
						bandera_campos_correctos = false;
					}

				});
			break;					
		case 8:
				var cantidad = parseFloat(document.getElementById("cantidad").value);
				var precio =  parseFloat(document.getElementById("precio").value);

				datos = [cantidad, precio];

				datos.forEach(function(el) {

					if(el == 0 || el < 0 || isNaN(el)){
						bandera_campos_correctos = false;
					}

				});
			break;														
	}



	var matriz = [];
	matriz = [datos];
	bandera_minimos = verificarMinimosMaximos(matriz,1);
	bandera_maximos = verificarMinimosMaximos(matriz,2);
	bandera_formato_campos = verificarFormatoValores(matriz);

 	if(bandera_comparacion_rangos){

		if(!bandera_formato_campos){

			if(!bandera_minimos){

				if(!bandera_maximos){

					if(bandera_campos_correctos){

						if(opcion_elegida == 7){ //Se utiliza bifurcacion porque en Gestion de flota solamente se edita el precio de una tarifa.
												 //es decir que no se modifican datos de zona u otro que tenga que ser comparado en la base de datos.
							document.getElementById("form_edicion").submit();
						}
						else{
							matriz = [datos];

							cargar_variables_zona();
							//console.log(id_cliente, opcion_elegida, zonaA_value, zonaB_value, matriz);

						    var parametros = {"cliente":id_cliente,"tipo_opcion":opcion_elegida,"zonaA":zonaA_value,"zonaB":zonaB_value,"matriz":matriz,"id_tarifa":id_tarifa};

						    $.post("<?php echo base_url('tarifarioT/tarifario_seleccion_opciones/ajax_validar_campos_grabados') ?>", {
										parametros
									},
									function(data, status) {
										data = JSON.parse(data);
										//console.log(data);
										edicion_parte2(data);
									}
								);	
						}		
					}
					else{
						mensaje_advertencia_campos();
					}

				}

			}

		}

 	}
 	else {
 		mensaje_error_comparacion_rangos();
 	}

}



function edicion_parte2(resultado_campos_registrados){
	resultado_evaluacion_campos_registrados = Object.keys(resultado_campos_registrados.comparacion).length;
	//console.log(resultado_evaluacion_campos_registrados);

	if(resultado_evaluacion_campos_registrados != 0){
		 mensaje_compracion_edicion(opcion_elegida, resultado_campos_registrados.comparacion);
	}
	else{		
		//console.log('antes de editar');
		document.getElementById("form_edicion").submit();
	}

}
