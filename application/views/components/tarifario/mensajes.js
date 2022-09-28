/*-----------------------MENSAJES REGISTRO*/

//Este archivo contiene las funciones de mensajes de confirmacion y advertencia

function confirmacionMsj1() {
	valueComboOpcionesCliente_anterior = parseInt(valueComboCliente);
	valueComboCliente = document.getElementById("cliente").value;

	if(valueComboOpcionesCliente_anterior != valueComboCliente){
		if(valueComboOpcionesCliente_anterior == -1){
			cargar_servicios();
			ver_opciones();			
		}
		else{
			if (confirm("Si cambia de Cliente, perdera los datos cargados. ¿Desea continuar?.")) {
				visibilidad_boton_registro(false);
				cargar_servicios();
				ocultar_opciones();
				ocultar_zonas();
				ver_opciones();
			} else {
				valueComboCliente = valueComboOpcionesCliente_anterior;
				$('#cliente').val(valueComboCliente).trigger('change.select2');
			}			
		}	
	}
}

function confirmacionMsj2() {
	valueComboOpciones_anterior = parseInt(valueComboOpciones);
	valueComboOpciones = parseInt(document.getElementById("opciones_tarifario").value);

	if(valueComboOpciones_anterior != valueComboOpciones){
		
		if(valueComboOpciones_anterior == 0){
			cargar_campos1(true);
			visibilidad_boton_registro(false);

			comprobar_registros_existentes_cobranza_gestionFlota();
		}
		else{
			if (confirm("Si cambia el Tipo de Opcion, perdera los datos cargados. ¿Desea continuar?")) {
				ocultar_campos_costos_porcentajes();
				visibilidad_boton_registro(false);

				cargar_campos1(true);

				comprobar_registros_existentes_cobranza_gestionFlota();			
			} else {
				valueComboOpciones = valueComboOpciones_anterior;
				document.getElementById("opciones_tarifario").value = valueComboOpciones;
			}			
		}
	}
}


function confirmacionMsj3(valor_combo_id) {
	var nombreCampo = '';
	var valueZona = 0;

	var zonaAnterior;

	if(!valor_combo_id){
		zonaAnterior = zonaA_value;
		zonaA_value = document.getElementById("zonaA").value;

		if(zonaA_value != zonaAnterior){
			if(confirm("Si cambia alguna de las zonas se borraran los valores cargados a continuacion.")) {
				ocultar_campos_costos_porcentajes();
				cargar_campos1(false);		

				comprobar_registros_existentes_cobranza_gestionFlota();
				visibilidad_boton_registro(false);		
			}	
			else{
				zonaA_value = zonaAnterior;
				$('#zonaA').val(zonaA_value).trigger('change.select2');
			}		
		}
	}
	else{
		zonaAnterior = zonaB_value;
		zonaB_value = document.getElementById("zonaB").value;

		if(zonaB_value != zonaAnterior){
			if(confirm("Si cambia alguna de las zonas se borraran los valores cargados a continuacion.")) {
				ocultar_campos_costos_porcentajes();
				cargar_campos1(false);		

				comprobar_registros_existentes_cobranza_gestionFlota();
				visibilidad_boton_registro(false);			
			}	
			else{
				zonaB_value = zonaAnterior;
				$('#zonaB').val(zonaB_value).trigger('change.select2');
			}		
		}		
	}
}


function msj_advertencia1(resultado){
	switch(valueComboOpciones){
		case 8:
			alert('Avio:\nEsta ingresando valores incorrectos en el campo "Cantidad".\n-Las ubicaciones no pueden repetirse.');
			break;
		case 7:
			alert('Avio:\nEsta ingresando valores incorrectos en el campo "Vehiculo // Hora".\n-Dichos campos no pueden repetirse entre tarifas.');
			break;
		default:
			alert('Aviso:\nEsta ingresando valores errores en los campos "DESDE" y "HASTA".\n-Los valores de rangos (desde y hasta) no pueden repetirse entre si.\n-El campo "HASTA" no puede ser menor que el campo "DESDE"');
	}


	


		/*
					VALOR 1 - AMBOS CASOS DE ABAJO
		 VALOR 2 - CAMPO "HASTA" MENOR QUE CAMPO "desde"
				 VALOR 3 SUPERPOSICION DE CAMPO DE INICIO / FIN
			
		*/
}

function msjs_advertencia2(resultado){
	var linea = 0;
	var valor_campo = 0;
	var nombre = '';

	valor_campo = resultado.valor_campo;
	linea = resultado.linea;
	nombre = resultado.nombre;
//aclarar resultados en comentarios

	
	switch(valueComboOpciones){
		case 3:
			//alert('Aviso: \nEl valor ' + valor_campo + ' (' + nombre + ') ya se encuentra registrado.\nCambie este valor por favor.');
			break;
		/*case 6:
			alert('Aviso: \nEl valor ' + valor_campo + ' (' + nombre + ') del costo de palets ' + linea + ' ya se encuentra registrado.\nCambie este valor por favor.');
			break;*/
		case 7:
			var valor_texto = "";

			if(valor_campo == 1){
				valor_texto = "Hora Comun";
			}
			else{
				valor_texto = "Hora Especial";
			}

			alert('Aviso: \nEl valor ' + valor_texto + ' (' + nombre + ') del costo de gestion de flota ' + linea + ' ya se encuentra registrado.\nCambie este valor por favor.\nEs posible que otro usuario lo haya registrado recientemente.');
			break;
		case 8:
			alert('Aviso: \nEl valor ' + valor_campo + ' (' + nombre + ') del costo de warehouse ' + linea + ' ya se encuentra registrado.\nCambie este valor por favor.');
			break;
		default:
			alert('Aviso: \nEl rango de la tarifa ' + linea + ' abarca valores ya registrados.\nCambie este valor por favor.');
			
	}

	
}


function msj_cobranza_existente(){
	alert('Aviso:\nEste cliente ya posee registrado un porcentaje de Cobranza en estas zonas.\nPodra registrar si elimina este porcentaje de esa zona o cambia de zonas.');
}


function msj_valor_declarado_existente(){
	alert('Aviso:\nEste cliente ya posee registrado un porcentaje de Valor Declarado en estas zonas.\nPodra registrar si elimina este porcentaje de esa zona o cambia de zonas.');
}




function msj_gestion_flota_existente(){
	alert('Aviso:\nEste cliente ya posee registrados importes de Gestion de Flota en estas zonas.\nPodra registrar si elimina dichos importes de esa zona o cambia de zonas.');
}
/*
function msj_metro_cubico_existente(){
	alert('Aviso:\nEste cliente ya posee registrados importe de Metro Cubico en estas zonas.\nPodra registrar si elimina dicho importe de esa zona o cambia de zonas.');
}
*/


function mensaje_min(valor){
	switch(valueComboOpciones){
		case 1:
			alert('Aviso:\nEl/Los valores ingresados del rango de Kilos y el precio no pueden ser menores a ' + valor);
			break;
		case 2:
			alert('Aviso:\nEl/Los valores ingresados del rango de Bultos y el precio no pueden ser menores a ' + valor);
			break;		
		case 3:
			alert('Aviso:\nEl porcentaje de Cobranza no puede ser menor a ' + valor + '%');
			break;
		case 4:
			alert('Aviso:\nEl porcentaje de Valor Declarado no puede ser menor a ' + valor + '%');
			break;		
		case 5:
			alert('Aviso:\nEl/Los valores ingresados del rango de Metro Cubico y el precio no pueden ser menores a ' + valor);
			break;
		case 6:
			alert('Aviso:\nEl/Los valores ingresados del rango de Palets y el precio no pueden ser menores a ' + valor);
			break;	
		case 7:
			alert('Aviso:\nEl precio de Gestion de Flota no pueden ser menores a ' + valor);
			break;	
		case 8:
			alert('Aviso:\nLos valores ingresados en WareHouse no pueden ser negativos');
			break;									
	}
}

function mensaje_max(valor){
	switch(valueComboOpciones){
		case 1:
			alert('Aviso:\nEl/Los valores ingresados "HASTA" del rango de kilos no pueden ser mayores a ' + valor);
			break;
		case 3:
			alert('Aviso:\nEl porcentaje de Cobranza no puede ser mayor a ' + valor + '%');
			break;			
		case 6:
			alert('Aviso:\nEl/Los valores ingresados "HASTA" del rango de palets no pueden ser mayores a ' + valor);
			break;
	}	
}

function mensaje_decimales(){
	switch(valueComboOpciones){
		case 2:
			alert('Aviso:\nNo es posible ingresar valores decimales en los rangos DESDE y HASTA de Bultos.');
			break;
		case 8:
			alert('Aviso:\nNo es posible ingresar valores decimales de Cantidad de Warehouse.');
			break;
	}	
}



/*-----------------------MENSAJES EDICION*/

function mensaje_advertencia_campos(){
	alert('Aviso: No se le permite ingresar valores en 0 (Cero), ni tampoco valores Vacios (Sin valor).');
}


function mensaje_compracion_edicion(opciones_tarifario, comparacion_array){
	switch(opciones_tarifario){
		case 1:
			alert('Aviso:\nNo es posibile cargar el siguiente valor ' + comparacion_array.valor_campo + ' en el campo "' + comparacion_array.nombre + '" porque el rango incluye valores que ya se encuentran registrados de Kilos.');
			break;
		case 2:
			alert('Aviso:\nNo es posibile cargar el siguiente valor ' + comparacion_array.valor_campo + ' en el campo "' + comparacion_array.nombre + '" porque el rango incluye valores que ya se encuentran registrados de Bultos.');
			break;
		case 3:
			alert('Aviso:\nNo es posibile cargar el siguiente valor ' + comparacion_array.valor_campo + ' en el campo "' + comparacion_array.nombre + '" porque ya se encuentra registrado en otra tarifa de Cobraza.');
			break;
		case 4:
			alert('Aviso:\nNo es posibile cargar el siguiente valor ' + comparacion_array.valor_campo + ' en el campo "' + comparacion_array.nombre + '" porque ya se encuentra registrado en otra tarifa de Valor Declarado.');
			break;												
		case 5:
			alert('Aviso:\nNo es posibile cargar el siguiente valor ' + comparacion_array.valor_campo + ' en el campo "' + comparacion_array.nombre + '" porque el rango incluye valores que ya se encuentran registrados de Metro Cubico.');
			break;	
		case 6:
			alert('Aviso:\nNo es posibile cargar el siguiente valor ' + comparacion_array.valor_campo + ' en el campo "' + comparacion_array.nombre + '" porque el rango incluye valores que ya se encuentran registrados de Palets.');
			break;	
		case 8:
			alert('Aviso:\nNo es posibile cargar el siguiente valor ' + comparacion_array.valor_campo + ' en el campo "' + comparacion_array.nombre + '" porque ya se encuentra registrado en otra tarifa de Warehouse.');
			break;															
			
	}
}


function mensaje_error_comparacion_rangos(){
	alert('Aviso:\nEl valor "Hasta" no puede ser menor que el valor "Desde"');
}