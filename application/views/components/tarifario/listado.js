function cargar_datos_opcion(opcion){
	let opcion_elegida;

	if(opcion == 0){
		opcion_elegida = document.getElementById("opciones_tarifario").value;
	}
	else{
		opcion_elegida = opcion;
	}
	ajax_datos_opcion(opcion_elegida);
}


function ajax_datos_opcion(opcion){
    var parametros = {"cliente":cliente_id,"opcion":opcion};

    $.post("<?php echo base_url('tarifarioT/tarifario_seleccion_opciones/ajax_listar_tarifas_opcion') ?>", {
				parametros
			},
			function(data, status) {
				data = JSON.parse(data);
				//console.log(data.tarifas);
				cargar_tabla(opcion, data.tarifas);
			}
		);
}


function cargar_tabla(opcion, tarifas){
	var opciones_tarifario = parseInt(opcion);
	borrar_tabla();

	//console.log(tarifas);//console.log(zonas);

	var div = `<div id="tabla" class="ibox-content" style="display: none;"></div>`;
	var table =  ``;
	var fila =  ``;

	var seccion0 = $('#seccion_tabla');
	$(seccion0).append(div);	


	if(opciones_tarifario != 0){
		seccion_tabla.style.display = "block";
	}else{
		seccion_tabla.style.display = "none";
	}

	switch(opciones_tarifario){	
		case 1: // Kilos

			table = `<table id="results" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
			            <thead>
			                <tr>
			                    <th><a href="#">Zona A</a></th>
			                    <th><a href="#">Zona B</a></th>
			                    <th><a href="#">Desde</a></th>
			                    <th><a href="#">Hasta</a></th>
			                    <th><a href="#">Precio</a></th>
			                    <th><a href="#">Tipo</a></th>
			                    <th class="col-xs-2">&nbsp;</th>
			                </tr>
			            </thead>
			            <tbody id="tbody">
			            </tbody>            
			        </table>`;

			var seccion1 = $('#tabla');
			$(seccion1).append(table);


			for(var i = 0; i < tarifas.length; i++){
			
                for(var y = 0; y < zonas.length; y++) {
                	if(zonas[y].id ==  tarifas[i].zonaA){
                		var zonaA = zonas[y].nombre;
                	}

                	if(zonas[y].id ==  tarifas[i].zonaB){
                		var zonaB = zonas[y].nombre;
                	}                	
                }

                var tipo_bandera = (tarifas[i].bandera_Corte_kg == 1) ? 'Bloque' : 'Unidad';


				fila = `<tr>
							<td>
							` + zonaA + `
							</td>
							<td>
							` + zonaB + `
							</td>
							<td>
							` + tarifas[i].desde_cant_unid_kg + `
							</td>
							<td>
							` + tarifas[i].hasta_cant_unid_kg + `
							</td>
							<td>
							` + tarifas[i].precio_Corte_kg + `
							</td>
							<td>
							` + tipo_bandera + `
							</td>	
		                    <td>
		                        <div class="btn-group">
			                        <a href="<?php echo base_url()?>tarifarioT/tarifario_seleccion_opciones/consulta?id=` + tarifas[i].id + `&opcion=` + opciones_tarifario + `" class="btn btn-success"><i class="fa fa-edit"></i></a>
			                        <button onclick="eliminacion(` + tarifas[i].id + `,` + opciones_tarifario + `)" class="btn btn-danger"><i class="fa fa-trash-o"></i></button>
		                        </div>
		                    </td>																		
						</tr>`;

						/**
						 <div class="btn-group">
			                        <a href="http://sispo.com.ar/tarifarioT/tarifario_seleccion_opciones/consulta?id=` + tarifas[i].id + `&opcion=` + opciones_tarifario + `" class="btn btn-success"><i class="fa fa-edit"></i></a>
			                        <button onclick="eliminacion(` + tarifas[i].id + `,` + opciones_tarifario + `)" class="btn btn-danger"><i class="fa fa-trash-o"></i></button>
		                        </div>
						 */

				var seccion2 = $('#tbody');
				$(seccion2).append(fila);	
			}				

			break;
		case 2: // Bultos

			table = `<table id="results" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
			            <thead>
			                <tr>
			                    <th><a href="#">Zona A</a></th>
			                    <th><a href="#">Zona B</a></th>
			                    <th><a href="#">Desde</a></th>
			                    <th><a href="#">Hasta</a></th>
			                    <th><a href="#">Precio</a></th>
			                    <th><a href="#">Tipo</a></th>
			                    <th class="col-xs-2">&nbsp;</th>
			                </tr>
			            </thead>
			            <tbody id="tbody">
			            </tbody>            
			        </table>`;

			var seccion1 = $('#tabla');
			$(seccion1).append(table);


			for(var i = 0; i < tarifas.length; i++){
			
                for(var y = 0; y < zonas.length; y++) {
                	if(zonas[y].id ==  tarifas[i].zonaA){
                		var zonaA = zonas[y].nombre;
                	}

                	if(zonas[y].id ==  tarifas[i].zonaB){
                		var zonaB = zonas[y].nombre;
                	}                	
                }

                var tipo_bandera = (tarifas[i].bandera_Corte_Bultos == 1) ? 'Bloque' : 'Unidad';


				fila = `<tr>
							<td>
							` + zonaA + `
							</td>
							<td>
							` + zonaB + `
							</td>
							<td>
							` + tarifas[i].desde_cant_unid_bultos + `
							</td>
							<td>
							` + tarifas[i].hasta_cant_unid_bultos + `
							</td>
							<td>
							` + tarifas[i].precio_Corte_bultos + `
							</td>
							<td>
							` + tipo_bandera + `
							</td>	
		                    <td>
		                        <div class="btn-group">
			                        <a href="<?php echo base_url()?>tarifarioT/tarifario_seleccion_opciones/consulta?id=` + tarifas[i].id + `&opcion=` + opciones_tarifario + `" class="btn btn-success"><i class="fa fa-edit"></i></a>
			                        <button onclick="eliminacion(` + tarifas[i].id + `,` + opciones_tarifario + `)" class="btn btn-danger"><i class="fa fa-trash-o"></i></button>
		                        </div>
		                    </td>																		
						</tr>`;
			
				var seccion2 = $('#tbody');
				$(seccion2).append(fila);	
			}				

			break;		
		case 3: // Cobranza

			table = `<table id="results" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
			            <thead>
			                <tr>
			                    <th><a href="#">Zona A</a></th>
			                    <th><a href="#">Zona B</a></th>
			                    <th><a href="#">Porcentaje</a></th>
			                    <th class="col-xs-2">&nbsp;</th>
			                </tr>
			            </thead>
			            <tbody id="tbody">
			            </tbody>            
			        </table>`;

			var seccion1 = $('#tabla');
			$(seccion1).append(table);


			for(var i = 0; i < tarifas.length; i++){
			
                for(var y = 0; y < zonas.length; y++) {
                	if(zonas[y].id ==  tarifas[i].zonaA){
                		var zonaA = zonas[y].nombre;
                	}

                	if(zonas[y].id ==  tarifas[i].zonaB){
                		var zonaB = zonas[y].nombre;
                	}                	
                }


				fila = `<tr>
							<td>
							` + zonaA + `
							</td>
							<td>
							` + zonaB + `
							</td>
							<td>
							` + tarifas[i].porcentaje + `
							</td>	
		                    <td>
		                        <div class="btn-group">
			                        <a href="<?php echo base_url()?>tarifarioT/tarifario_seleccion_opciones/consulta?id=` + tarifas[i].id + `&opcion=` + opciones_tarifario + `" class="btn btn-success"><i class="fa fa-edit"></i></a>
			                        <button onclick="eliminacion(` + tarifas[i].id + `,` + opciones_tarifario + `)" class="btn btn-danger"><i class="fa fa-trash-o"></i></button>
		                        </div>
		                    </td>																		
						</tr>`;
			
				var seccion2 = $('#tbody');
				$(seccion2).append(fila);	
			}				

			break;						
		case 4: // Valor declarado
			table = `<table id="results" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
			            <thead>
			                <tr>
			                    <th><a href="#">Zona A</a></th>
			                    <th><a href="#">Zona B</a></th>
			                    <th><a href="#">Porcentaje</a></th>
			                    <th class="col-xs-2">&nbsp;</th>
			                </tr>
			            </thead>
			            <tbody id="tbody">
			            </tbody>            
			        </table>`;

			var seccion1 = $('#tabla');
			$(seccion1).append(table);


			for(var i = 0; i < tarifas.length; i++){
			
                for(var y = 0; y < zonas.length; y++) {
                	if(zonas[y].id ==  tarifas[i].zonaA){
                		var zonaA = zonas[y].nombre;
                	}

                	if(zonas[y].id ==  tarifas[i].zonaB){
                		var zonaB = zonas[y].nombre;
                	}                	
                }


				fila = `<tr>
							<td>
							` + zonaA + `
							</td>
							<td>
							` + zonaB + `
							</td>
							<td>
							` + tarifas[i].porcentaje + `
							</td>	
		                    <td>
		                        <div class="btn-group">
			                        <a href="<?php echo base_url()?>tarifarioT/tarifario_seleccion_opciones/consulta?id=` + tarifas[i].id + `&opcion=` + opciones_tarifario + `" class="btn btn-success"><i class="fa fa-edit"></i></a>
			                        <button onclick="eliminacion(` + tarifas[i].id + `,` + opciones_tarifario + `)" class="btn btn-danger"><i class="fa fa-trash-o"></i></button>
		                        </div>
		                    </td>																		
						</tr>`;
			
				var seccion2 = $('#tbody');
				$(seccion2).append(fila);	
			}				

			break;	
		case 5: // Metro Cubico

				table = `<table id="results" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
			            <thead>
			                <tr>
			                    <th><a href="#">Zona A</a></th>
			                    <th><a href="#">Zona B</a></th>
			                    <th><a href="#">Desde</a></th>
			                    <th><a href="#">Hasta</a></th>
			                    <th><a href="#">Precio</a></th>
			                    <th><a href="#">Tipo</a></th>
			                    <th class="col-xs-2">&nbsp;</th>
			                </tr>
			            </thead>
			            <tbody id="tbody">
			            </tbody>            
			        </table>`;

			var seccion1 = $('#tabla');
			$(seccion1).append(table);


			for(var i = 0; i < tarifas.length; i++){
			
                for(var y = 0; y < zonas.length; y++) {
                	if(zonas[y].id ==  tarifas[i].zonaA){
                		var zonaA = zonas[y].nombre;
                	}

                	if(zonas[y].id ==  tarifas[i].zonaB){
                		var zonaB = zonas[y].nombre;
                	}                	
                }

                var tipo_bandera = (tarifas[i].bandera_bloque_unidad == 1) ? 'Bloque' : 'Unidad';


				fila = `<tr>
							<td>
							` + zonaA + `
							</td>
							<td>
							` + zonaB + `
							</td>
							<td>
							` + tarifas[i].desde_cantidad_metro_cubico + `
							</td>
							<td>
							` + tarifas[i].hasta_cantidad_metro_cubico+ `
							</td>
							<td>
							` + tarifas[i].precio_mc + `
							</td>
							<td>
							` + tipo_bandera + `
							</td>	
		                    <td>
		                        <div class="btn-group">
			                        <a href="<?php echo base_url()?>tarifarioT/tarifario_seleccion_opciones/consulta?id=` + tarifas[i].id + `&opcion=` + opciones_tarifario + `" class="btn btn-success"><i class="fa fa-edit"></i></a>
			                        <button onclick="eliminacion(` + tarifas[i].id + `,` + opciones_tarifario + `)" class="btn btn-danger"><i class="fa fa-trash-o"></i></button>
		                        </div>
		                    </td>																		
						</tr>`;

				var seccion2 = $('#tbody');
				$(seccion2).append(fila);	
			}				
		
			break;	
		case 6: // Palets

			table = `<table id="results" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
			            <thead>
			                <tr>
			                    <th><a href="#">Zona A</a></th>
			                    <th><a href="#">Zona B</a></th>
			                    <th><a href="#">Desde</a></th>
			                    <th><a href="#">Hasta</a></th>
			                    <th><a href="#">Precio</a></th>
			                    <th><a href="#">Tipo</a></th>
			                    <th class="col-xs-2">&nbsp;</th>
			                </tr>
			            </thead>
			            <tbody id="tbody">
			            </tbody>            
			        </table>`;

			var seccion1 = $('#tabla');
			$(seccion1).append(table);


			for(var i = 0; i < tarifas.length; i++){
			
                for(var y = 0; y < zonas.length; y++) {
                	if(zonas[y].id ==  tarifas[i].zonaA){
                		var zonaA = zonas[y].nombre;
                	}

                	if(zonas[y].id ==  tarifas[i].zonaB){
                		var zonaB = zonas[y].nombre;
                	}                	
                }

                var tipo_bandera = (tarifas[i].bandera_palets == 1) ? 'Bloque' : 'Unidad';


				fila = `<tr>
							<td>
							` + zonaA + `
							</td>
							<td>
							` + zonaB + `
							</td>
							<td>
							` + tarifas[i].desde_cantidad_palets + `
							</td>
							<td>
							` + tarifas[i].hasta_cantidad_palets + `
							</td>
							<td>
							` + tarifas[i].precio_palets + `
							</td>
							<td>
							` + tipo_bandera + `
							</td>	
		                    <td>
		                        <div class="btn-group">
			                        <a href="<?php echo base_url()?>tarifarioT/tarifario_seleccion_opciones/consulta?id=` + tarifas[i].id + `&opcion=` + opciones_tarifario + `" class="btn btn-success"><i class="fa fa-edit"></i></a>
			                        <button onclick="eliminacion(` + tarifas[i].id + `,` + opciones_tarifario + `)" class="btn btn-danger"><i class="fa fa-trash-o"></i></button>
		                        </div>
		                    </td>																		
						</tr>`;
			
				var seccion2 = $('#tbody');
				$(seccion2).append(fila);	
			}				

			break;			
		case 7: // Gestion de Flota

			table = `<table id="results" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
			            <thead>
			                <tr>
			                    <th><a href="#">Zona A</a></th>
			                    <th><a href="#">Zona B</a></th>
			                    <th><a href="#">Vehiculo</a></th>
			                    <th><a href="#">Hora</a></th>
			                    <th><a href="#">Precio</a></th>
			                    <th class="col-xs-2">&nbsp;</th>
			                </tr>
			            </thead>
			            <tbody id="tbody">
			            </tbody>            
			        </table>`;

			var seccion1 = $('#tabla');
			$(seccion1).append(table);


			for(var i = 0; i < tarifas.length; i++){
			
                for(var y = 0; y < zonas.length; y++) {
                	if(zonas[y].id ==  tarifas[i].zonaA){
                		var zonaA = zonas[y].nombre;
                	}

                	if(zonas[y].id ==  tarifas[i].zonaB){
                		var zonaB = zonas[y].nombre;
                	}                	
                }

                var tipo_hora = (tarifas[i].tipo_hora == 1) ? 'Comun' : 'Especial';
                var tipo_vehiculo = '';

                switch(parseInt(tarifas[i].tipo_vehiculo)){
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


				fila = `<tr>
							<td>
							` + zonaA + `
							</td>
							<td>
							` + zonaB + `
							</td>
							<td>
							` + tipo_vehiculo + `
							</td>
							<td>
							` + tipo_hora + `
							</td>	
							<td>
							` + tarifas[i].precio_gf + `
							</td>
		                    <td>
		                        <div class="btn-group">
			                        <a href="<?php echo base_url()?>tarifarioT/tarifario_seleccion_opciones/consulta?id=` + tarifas[i].id + `&opcion=` + opciones_tarifario + `" class="btn btn-success"><i class="fa fa-edit"></i></a>
			                        <button onclick="eliminacion(` + tarifas[i].id + `,` + opciones_tarifario + `)" class="btn btn-danger"><i class="fa fa-trash-o"></i></button>
		                        </div>
		                    </td>																		
						</tr>`;
			
				var seccion2 = $('#tbody');
				$(seccion2).append(fila);	
			}				

			break;		
		case 8: // WareHouse

			table = `<table id="results" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
			            <thead>
			                <tr>
			                    <th><a href="#">Zona A</a></th>
			                    <th><a href="#">Zona B</a></th>
			                    <th><a href="#">Cantidad</a></th>
			                    <th><a href="#">Precio</a></th>
			                    <th class="col-xs-2">&nbsp;</th>
			                </tr>
			            </thead>
			            <tbody id="tbody">
			            </tbody>            
			        </table>`;

			var seccion1 = $('#tabla');
			$(seccion1).append(table);


			for(var i = 0; i < tarifas.length; i++){
			
                for(var y = 0; y < zonas.length; y++) {
                	if(zonas[y].id ==  tarifas[i].zonaA){
                		var zonaA = zonas[y].nombre;
                	}

                	if(zonas[y].id ==  tarifas[i].zonaB){
                		var zonaB = zonas[y].nombre;
                	}                	
                }


				fila = `<tr>
							<td>
							` + zonaA + `
							</td>
							<td>
							` + zonaB + `
							</td>
							<td>
							` + tarifas[i].cantidad_wh + `
							</td>	
							<td>
							` + tarifas[i].precio_wh + `
							</td>
		                    <td>
		                        <div class="btn-group">
			                        <a href="<?php echo base_url()?>tarifarioT/tarifario_seleccion_opciones/consulta?id=` + tarifas[i].id + `&opcion=` + opciones_tarifario + `" class="btn btn-success"><i class="fa fa-edit"></i></a>
			                        <button onclick="eliminacion(` + tarifas[i].id + `,` + opciones_tarifario + `)" class="btn btn-danger"><i class="fa fa-trash-o"></i></button>
		                        </div>
		                    </td>																		
						</tr>`;
			
				var seccion2 = $('#tbody');
				$(seccion2).append(fila);	
			}				

			break;																					
	}


	$('#results').DataTable({});

	tabla.style.display = "block";
}



function borrar_tabla(){
	$( '#tabla' ). remove();
}



function eliminacion(id_registro, opcion_elegida){

	if (confirm("¿Esta seguro que desea eliminar este importe?")) {
    	var parametros = {"id":id_registro,"opcion":opcion_elegida};

    	$.post("<?php echo base_url('tarifarioT/tarifario_seleccion_opciones/ajax_eliminacion') ?>", {
				parametros
			},
			function(data, status) {
				data = JSON.parse(data);
				
				//console.log(data.estado);
				if(!data.estado){
					alert('El importe ' + data.msj);
				}
				else{
					ajax_datos_opcion(opcion_elegida);
					alert('El importe ' + data.msj);
				}
			}
		);
	}

}