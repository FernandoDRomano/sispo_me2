//Este archivo contiene las funciones para cargar (mostrar) los campos a medida que se eligen las opciones
//y para ocultar los campos tambien



function cargar_servicios(){
	var id_cliente = parseInt(document.getElementById("cliente").value);

	if (id_cliente == 0) {
		document.getElementById('servicio').innerHTML = '...';
		seccion_opciones_tarifario.style.display = "none";		
	}
	else{
		for (var i = 0; i < clientes_listado.length; i++) {
			var idCliente_lista = parseInt(clientes_listado[i].id);
			var servicio = clientes_listado[i].servicio;

			if (id_cliente == idCliente_lista) {
				document.getElementById('servicio').innerHTML = servicio;
				
				document.getElementById("opciones_tarifario").value = "0";
				valueComboOpciones = 0;

				seccion_opciones_tarifario.style.display = "block";
			}
		}		
	}
}


function cargar_campos1(zonas){
	if(zonas){
		//seteo_campos_zonas();
		visibilidad_zonas(true);	
	}


	switch (valueComboOpciones) {
		case 1:
			$('#rinicio_kilos1').val("");
			$('#rfin_kilos1').val("");
			$('#precio_kilos1').val("");
			document.getElementById("opciones_tipo_kilos1").value = "0";

			rinicio_kilos1.step = "0.01";
			rfin_kilos1.step = "0.01";

			rangos_precios_kilos.style.display = "block";
			break;
		case 2: // Bultos	
			$('#rinicio_bultos1').val("");
			$('#rfin_bultos1').val("");
			$('#precio_bultos1').val("");
			document.getElementById("opciones_tipo_bultos1").value = "0";

			//console.log('entre en opcion visible bultos');
			rangos_precios_bultos.style.display = "block";

			break;
		case 3: //Cobranza
			visibilidad_cobranza(false);

			$('#porcentaje_cobranza1').val("");

			rangos_cobranza.style.display = "block";

			break;
		case 4: // Valor declarado
			visibilidad_valor_declarado(false);

			$('#porcentaje_valor_declarado1').val("");

			rangos_valor_declarado.style.display = "block";

			break;
		case 5: // Metro Cubico
			$('#rinicio_mc1').val("");
			$('#rfin_mc1').val("");
			$('#precio_mc1').val("");
			document.getElementById("opciones_tipo_mc1").value = "0";

			rinicio_mc1.step = "0.01";
			rfin_mc1.step = "0.01";

			//visibilidad_metro_cubico(false);
			metro_cubico_precio.style.display = "block";

			break;			
		case 6: // Palets
			$('#rinicio_palets1').val("");
			$('#rfin_palets1').val("");
			$('#precio_palets1').val("");
			document.getElementById("opciones_tipo_palets1").value = "0";

			rangos_precios_palets.style.display = "block";

			break;
		case 7://Gestion de Flota
			visibilidad_gestion_flota(false);

			document.getElementById("opcion_horas_gf1").value = "0";
			$('#precio_gf1').val("");

			horarios_gestion_flota.style.display = "block";
			break;
		case 8://WareHouse
			$('#cantidad_wh1').val("");
			$('#precio_wh1').val("");

			cantidad_precio_warehouse.style.display = "block";
			break;
	}
}



function visibilidad_zonas(boolean){
	if(boolean){
		ubicaciones.style.display = "block";	
	}
	else{
		ubicaciones.style.display = "none";
	}
}

function visibilidad_boton_registro(boolean){
	if(boolean){
		registro.style.display = "block";
	}
	else{
		registro.style.display = "none";
	}

}


function ver_opciones(){
	document.getElementById("opciones_tarifario").value = "0";
	seccion_opciones_tarifario.style.display = "block";
}

function ocultar_opciones(){
	document.getElementById("opciones_tarifario").value = "0";
	seccion_opciones_tarifario.style.display = "none";

	ocultar_campos_costos_porcentajes();
}

function ocultar_zonas(){
	ubicaciones.style.display = "none";
}


function ocultar_campos_costos_porcentajes(){
	$('#rangos_precios_kilos').find('.campo_agregado').each(function() {
		$(this).remove();
		countX--;
	});

	rangos_precios_kilos.style.display = "none";

	$('#rangos_precios_bultos').find('.campo_agregado').each(function() {
		$(this).remove();
		countX--;
	});

	rangos_precios_bultos.style.display = "none";	

	$('#rangos_cobranza').find('.campo_agregado').each(function() {
		$(this).remove();
		countX--;
	});	

	rangos_cobranza.style.display = "none";	

	$('#rangos_precios_palets').find('.campo_agregado').each(function() {
		$(this).remove();
		countX--;
	});	

	rangos_precios_palets.style.display = "none";

	$('#horarios_gestion_flota').find('.campo_agregado').each(function() {
		$(this).remove();
		countX--;
	});	

	horarios_gestion_flota.style.display = "none";	

	$('#cantidad_precio_warehouse').find('.campo_agregado').each(function() {
		$(this).remove();
		countX--;
	});	

	cantidad_precio_warehouse.style.display = "none";		


	$('#rangos_valor_declarado').find('.campo_agregado').each(function() {
		$(this).remove();
		countX--;
	});	

	rangos_valor_declarado.style.display = "none";	


	$('#metro_cubico_precio').find('.campo_agregado').each(function() {
		$(this).remove();
		countX--;
	});	

	metro_cubico_precio.style.display = "none";				
}


function visibilidad_etiqueta_mas_gf(boolean){
	if(boolean){
		etiqueta_a_gf.style.display = "block";
	}
	else{
		etiqueta_a_gf.style.display = "none";
	}
	
}


function visibilidad_cobranza(boolean){
	if(boolean){
		document.getElementById("porcentaje_cobranza1").disabled = true;
	}else{
		document.getElementById("porcentaje_cobranza1").disabled = false;
	}
}


function visibilidad_valor_declarado(boolean){
	if(boolean){
		document.getElementById("porcentaje_valor_declarado1").disabled = true;
	}else{
		document.getElementById("porcentaje_valor_declarado1").disabled = false;
	}
}


function visibilidad_metro_cubico(boolean){
	if(boolean){
		document.getElementById("precio_mc1").disabled = true;
	}else{
		document.getElementById("precio_mc1").disabled = false;
	}
}


function visibilidad_gestion_flota(boolean){
	if(boolean){
		document.getElementById("opcion_horas_gf1").disabled = true;
		document.getElementById("precio_gf1").disabled = true;
		etiqueta_a_gf.style.display = "none";		
	}else{
		document.getElementById("opcion_horas_gf1").disabled = false;
		document.getElementById("precio_gf1").disabled = false;
		etiqueta_a_gf.style.display = "block";
	}
}