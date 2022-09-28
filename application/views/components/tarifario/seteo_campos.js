/*Funciones para View Insert: seleccion de opciones */

function cargar_variables_zona(){
	zonaA_value = document.getElementById("zonaA").value;
	zonaB_value = document.getElementById("zonaB").value;
}

function cargar_variable_cliente(){
	valueComboCliente = document.getElementById("cliente").value;
}

function cargar_input_cantidad_campos(){
	document.getElementById("cantidad_campos").value = countX;
}


function seteo_campos_zonas(){
	$('#zonaA').val('17').trigger('change.select2');
	$('#zonaB').val('17').trigger('change.select2');
}


function seteo_variable_options(datos) {
	options_gestion_flota = ``;

	let objetoJSON = {
			tarifa_1: {
				opcion_vehiculo: '1', opcion_hora: '1', option: `<option value="1">Moto // Hora Comun</option>`, bandera: true
			},
			tarifa_2: {
				opcion_vehiculo: '1', opcion_hora: '2', option: `<option value="2">Moto // Hora Especial</option>`, bandera: true
			},
			tarifa_3: {
				opcion_vehiculo: '2', opcion_hora: '1', option: `<option value="3">Auto // Hora Comun</option>`, bandera: true
			},
			tarifa_4: {
				opcion_vehiculo: '2', opcion_hora: '2', option: `<option value="4">Auto // Hora Especial</option>`, bandera: true
			},
			tarifa_5: {
				opcion_vehiculo: '3', opcion_hora: '1', option: `<option value="5">Camioneta // Hora Comun</option>`, bandera: true
			},
			tarifa_6: {
				opcion_vehiculo: '3', opcion_hora: '2', option: `<option value="6">Camioneta // Hora Especial</option>`, bandera: true
			},
			tarifa_7: {
				opcion_vehiculo: '4', opcion_hora: '1', option: `<option value="7">Furgon // Hora Comun</option>`, bandera: true
			},
			tarifa_8: {
				opcion_vehiculo: '4', opcion_hora: '2', option: `<option value="8">Furgon // Hora Especial</option>`, bandera: true
			},
			tarifa_9: {
				opcion_vehiculo: '5', opcion_hora: '1', option: `<option value="9">Camion // Hora Comun</option>`, bandera: true
			},
			tarifa_10: {
				opcion_vehiculo: '5', opcion_hora: '2', option: `<option value="10">Camion // Hora Especial</option>`, bandera: true
			},		
		}

	datos.forEach(function(el){

		Object.entries(objetoJSON).forEach(([key, value]) => {

		 	if(el.tipo_vehiculo == value.opcion_vehiculo && el.tipo_hora == value.opcion_hora){
		 		value.bandera = false;
		 	}

		});

	});

	Object.entries(objetoJSON).forEach(([key, value]) => {
		//console.log(value.option);
	 	if(value.bandera){
	 		options_gestion_flota = options_gestion_flota + value.option;
	 	}
	});	
	//console.log(options_gestion_flota);

	$('#opcion_horas_gf1').children('option:not(:first)').remove();

	var seccion = $('#opcion_horas_gf1');
	$(seccion).append(options_gestion_flota);
}




/*Funciones para View Edit: selecion de opciones edit */

function setear_id_cliente(id_cliente){
	document.getElementById("cliente").value = id_cliente;
}

function setear_opcion_elegida(opcion_elegida){
	document.getElementById("opcion_tarifario").value = opcion_elegida;
}

function setear_codigo_tarifa(id_tarifa){
	document.getElementById("identificador_tarifa").value = id_tarifa;
}

function setear_zonas_tarifa(registro_zonaA, registro_zonaB){
	$('#zonaA').val(registro_zonaA).trigger('change.select2');
	$('#zonaB').val(registro_zonaB).trigger('change.select2');
}


function cargar_campos_edicion(opcion_elegida,tarifa){
	var div = ``;
	var bandera = 0;

	//console.log(tarifa[0]);

	switch(opcion_elegida) {
		case 1:
			div = `
		        <div id="kilogramos" style="display: block;">
		            <div class="form-group">
		                <label for="desde">Desde<span class="required"> *</span></label>
		                <input required id="desde" step="0.01" name="desde" type="number" class="form-control" placeholder="Desde" value="` + tarifa[0].desde_cant_unid_kg + `" />
		            </div>
		            <div class="form-group">
		                <label for="hasta">Hasta<span class="required"> *</span></label>
		                <input required id="hasta" step="0.01" name="hasta" type="number" class="form-control" placeholder="Hasta" value="` + tarifa[0].hasta_cant_unid_kg + `" />
		            </div>
		            <div class="form-group">
		                <label for="precio">Precio<span class="required"> *</span></label>
		                <input required id="precio" step="0.01" name="precio" type="number" class="form-control" placeholder="Precio" value="` + tarifa[0].precio_Corte_kg + `" />
		            </div>        
		            <div class="form-group">
		                <label for="tipo">Tipo<span class="required">*</span></label>
		                <select required id="tipo" name="tipo" class="form-control">          
		                    <option value="1">Bloque</option>
		                    <option value="2">Unidad</option>
		                </select>
		            </div> 
		        </div> 
			`;

			bandera = parseInt(tarifa[0].bandera_Corte_kg);
			break;
		case 2:
			div = `
		        <div id="bultos" style="display: block;">
		            <div class="form-group">
		                <label for="desde">Desde<span class="required"> *</span></label>
		                <input required id="desde" name="desde" type="number" class="form-control" placeholder="Desde" value="` + tarifa[0].desde_cant_unid_bultos + `" />
		            </div>
		            <div class="form-group">
		                <label for="hasta">Hasta<span class="required"> *</span></label>
		                <input required id="hasta" name="hasta" type="number" class="form-control" placeholder="Hasta" value="` + tarifa[0].hasta_cant_unid_bultos + `" />
		            </div>
		            <div class="form-group">
		                <label for="precio">Precio<span class="required"> *</span></label>
		                <input required id="precio" step="0.01" name="precio" type="number" class="form-control" placeholder="Precio" value="` + tarifa[0].precio_Corte_bultos + `" />
		            </div>        
		            <div class="form-group">
		                <label for="tipo">Tipo<span class="required">*</span></label>
		                <select required id="tipo" name="tipo" class="form-control">          
		                    <option value="1">Bloque</option>
		                    <option value="2">Unidad</option>
		                </select>
		            </div> 
		        </div> 
			`;

			bandera = parseInt(tarifa[0].bandera_Corte_Bultos);
			break;	
		case 3:
			document.getElementById("zonaA").setAttribute("disabled", "disabled");
			document.getElementById("zonaB").setAttribute("disabled", "disabled");

			div = `
		        <div id="cobranza" style="display: block;">
		            <div class="form-group">
		                <label for="desde">Porcentaje<span class="required"> *</span></label>
		                <input required id="porcentaje" step="0.01" name="porcentaje" type="number" class="form-control" placeholder="Porcentaje" value="` + tarifa[0].porcentaje + `" />
		            </div>
		        </div> 
			`;

			break;	
		case 4:
			document.getElementById("zonaA").setAttribute("disabled", "disabled");
			document.getElementById("zonaB").setAttribute("disabled", "disabled");
		
			div = `
		        <div id="valor_declarado" style="display: block;">
		            <div class="form-group">
		                <label for="desde">Porcentaje<span class="required"> *</span></label>
		                <input required id="porcentaje" step="0.01" name="porcentaje" type="number" class="form-control" placeholder="Porcentaje" value="` + tarifa[0].porcentaje + `" />
		            </div>
		        </div> 
			`;

			break;	
		case 5:
			div = `
		        <div id="metro_cubico" style="display: block;">
		            <div class="form-group">
		                <label for="desde">Desde<span class="required"> *</span></label>
		                <input required id="desde" step="0.01" name="desde" type="number" class="form-control" placeholder="Desde" value="` + tarifa[0].desde_cantidad_metro_cubico + `" />
		            </div>
		            <div class="form-group">
		                <label for="hasta">Hasta<span class="required"> *</span></label>
		                <input required id="hasta" step="0.01" name="hasta" type="number" class="form-control" placeholder="Hasta" value="` + tarifa[0].hasta_cantidad_metro_cubico + `" />
		            </div>
		            <div class="form-group">
		                <label for="precio">Precio<span class="required"> *</span></label>
		                <input required id="precio" step="0.01" name="precio" type="number" class="form-control" placeholder="Precio" value="` + tarifa[0].precio_mc + `" />
		            </div>        
		            <div class="form-group">
		                <label for="tipo">Tipo<span class="required">*</span></label>
		                <select required id="tipo" name="tipo" class="form-control">          
		                    <option value="1">Bloque</option>
		                    <option value="2">Unidad</option>
		                </select>
		            </div> 
		        </div> 
			`;

			bandera = parseInt(tarifa[0].bandera_bloque_unidad);
			break;				
		case 6:
			div = `
		        <div id="palets" style="display: block;">
		            <div class="form-group">
		                <label for="desde">Desde<span class="required"> *</span></label>
		                <input required id="desde" name="desde" type="number" class="form-control" placeholder="Desde" value="` + tarifa[0].desde_cantidad_palets + `" />
		            </div>
		            <div class="form-group">
		                <label for="hasta">Hasta<span class="required"> *</span></label>
		                <input required id="hasta" name="hasta" type="number" class="form-control" placeholder="Hasta" value="` + tarifa[0].hasta_cantidad_palets + `" />
		            </div>
		            <div class="form-group">
		                <label for="precio">Precio<span class="required"> *</span></label>
		                <input required id="precio" step="0.01" name="precio" type="number" class="form-control" placeholder="Precio" value="` + tarifa[0].precio_palets + `" />
		            </div>        
		            <div class="form-group">
		                <label for="tipo">Tipo<span class="required">*</span></label>
		                <select required id="tipo" name="tipo" class="form-control">          
		                    <option value="1">Bloque</option>
		                    <option value="2">Unidad</option>
		                </select>
		            </div> 
		        </div> 
			`;

			bandera = parseInt(tarifa[0].bandera_palets);
			break;	
		case 7:
			document.getElementById("zonaA").setAttribute("disabled", "disabled");
			document.getElementById("zonaB").setAttribute("disabled", "disabled");


            var tipo_hora = (tarifa[0].tipo_hora == "1") ? 'Comun' : 'Especial';

            var tipo_vehiculo = '';
            switch(parseInt(tarifa[0].tipo_vehiculo)){
            	case 1:
            		tipo_vehiculo = 'Moto';
            		break;
            	case 2:
            		tipo_vehiculo = 'Auto';
            		break;
            	case 3:
            		tipo_vehiculo = 'Camioneta';
            		break;  
            	case 4:
            		tipo_vehiculo = 'Furgon';
            		break;
            	case 5:
            		tipo_vehiculo = 'Camion';
            		break;                  		              		                		
            }


			div = `
		        <div id="palets" style="display: block;">
		            <div class="form-group">
		                <label for="desde">Vehiculo<span class="required"> *</span></label>
		                <input required id="vehiculo" name="vehiculo" type="text" class="form-control" value=${tipo_vehiculo} disabled />
		            </div>
		            <div class="form-group">
		                <label for="hora">Hora<span class="required"> *</span></label>
		                <input required id="hora" name="hora" type="text" class="form-control" value=${tipo_hora} disabled />
		            </div>
		            <div class="form-group">
		                <label for="precio">Precio<span class="required"> *</span></label>
		                <input required id="precio" step="0.01" name="precio" type="number" class="form-control" placeholder="Precio" value="` + tarifa[0].precio_gf + `" />
		            </div>        
		        </div> 
			`;

			break;				
		case 8:
			div = `
		        <div id="palets" style="display: block;">
		            <div class="form-group">
		                <label for="desde">Cantidad<span class="required"> *</span></label>
		                <input required id="cantidad" name="cantidad" type="number" class="form-control" placeholder="Cantidad" value="` + tarifa[0].cantidad_wh + `" />
		            </div>
		            <div class="form-group">
		                <label for="precio">Precio<span class="required"> *</span></label>
		                <input required id="precio" step="0.01" name="precio" type="number" class="form-control" placeholder="Precio" value="` + tarifa[0].precio_wh + `" />
		            </div>        
		        </div> 
			`;
			break;														
	}


	var seccion0 = $('#campos_seccion');
	$(seccion0).append(div);	

	seteoBandera(opcion_elegida, bandera);
}



function seteoBandera(opcion_elegida, valor_bandera){

	 switch(opcion_elegida){
        case 1: 
            document.getElementById("tipo").value = valor_bandera;
            break;
        case 2:
        	document.getElementById("tipo").value = valor_bandera;
            break;
        case 5:
        	document.getElementById("tipo").value = valor_bandera;
            break;  
        case 6:
        	document.getElementById("tipo").value = valor_bandera;
            break;                        
	}

}