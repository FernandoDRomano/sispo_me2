<form method="post">
	<!-- 		Combo box - Nombres de Clientes 		-->
	<div>
		<label>Cliente:</label>
		<select id="cliente_id" name="cliente" onchange="ShowSelected();">
			<?php
			if ($data) {
				echo ('<option hidden selected>Seleccione un cliente</option>');
				foreach ($client as $result) {
					echo ('<option value=' . $result->id . '>' . $result->nombre . '</option>');
				}
			} else {
				echo ('<option>No existen resultados</option>');
			}
			?>
		</select>
	</div>
	<br><br>

	<!-- 		Sección para seleccionar las opciones de calculo para el armado del Tarifario 		-->
	<div>
		<div id="cboxes" name="n_Cboxes" style="display: none;">
			<p>Seleccione la/las opciones para calcular el Precio.</p>
			<br>
			<div>
				<i>Calcular por:</i>
				<br><br>
			</div>
			<!-- 		Checkbox / Opciones "Cantidad-Peso-Distancia" 		-->
			<label style="margin-bottom: 25px; margin-left: 25px; margin-right: 25px;"><input type="checkbox" id="cbox1" onclick="limpiarCampos()" onchange="cambiar()"> Bultos (Cantidad)</label>
			<label style="margin-bottom: 25px; margin-left: 25px; margin-right: 25px;"><input type="checkbox" id="cbox2" onchange="cambiar()"> Peso (Kilogramos)</label>
			<label style="margin-bottom: 25px; margin-left: 25px; margin-right: 25px;"><input type="checkbox" id="cbox3" onchange="cambiar()"> Distancia</label>
			<label style="margin-bottom: 25px; margin-left: 25px; margin-right: 25px;"><input type="checkbox" id="cbox4" onchange="cambiar()"> Combinar</label>
			<br>
		</div>

		<!-- 		Inputs de texto para ingresar los valores de rangos para "CANTIDAD DE BULTOS" y su Precio 		-->
		<div name="RP_bultos" style="display: none" id="cantidadBultos">

			<div class="campos_texto_cantidad_add">
				<br>
				<p>Ingrese los rangos de cantidades y el precio correspondiente.</p>
				<br>
				<label>Cantidad: </label>
				<label>
					<input id="rinicio1_cantidad" style="margin-right: 20px;" placeholder="Desde.." type="number" name="c_rinicio1_cantidad">
					<input id="rfin1_cantidad" style="margin-right: 20px;" placeholder="Hasta.." type="number" name="c_rfin1_cantidad">
				</label>
				<label>$</label>
				<input style="" placeholder="Costo.." type="number" id="precio1_cantidad" name="c_precio1_cantidad">
				<a style="margin-left: 35px; border: solid 4px; font-size: 20px;" href="javascript:void(0);" class="add_button_cantidad" title="Add field">+</a>
			</div>

		</div>

		<!-- 		Inputs de texto para ingresar los valores de rangos para "KILOS (en cuanto a peso)" y su Precio 		-->
		<div name="RP_Peso" style="display: none" id="kilosPeso">
			<div class="campos_texto_peso_add">
				<br>
				<p>Ingrese los rangos de los pesos para kilogramos y el precio correspondiente.</p>
				<br>
				<label style="margin-left: 240px; margin-right: 230px;">Rango</label> <label>Precio</label>
				<br>
				<label>Cantidad: </label>
				<label>
					<input id="rinicio1_peso" style="margin-right: 20px;" placeholder="Desde.." type="number" name="c_rinicio1_peso">
					<input id="rfin1_peso" style="margin-right: 20px;" placeholder="Hasta.." type="number" name="c_fin1_peso">
				</label>
				<label>$</label>
				<input style="" placeholder="Costo.." type="number" id="precio1_peso" name="c_precio1_peso">
				<a style="margin-left: 35px; border: solid 4px; font-size: 20px;" href="javascript:void(0);" class="add_button_peso" title="Add field">+</a>
			</div>
		</div>

		<!-- 		Inputs de texto para ingresar los valores de rangos para "LA DISTANCIA" y su Precio 		-->
		<div name="RP_distancia" style="display: none" id="distanciaCalculo">
			<div class="campos_texto_distancia_add">
				<br>
				<p>Ingrese los rangos de las distancias y el precio correspondiente.</p>
				<br>
				<label style="margin-left: 240px; margin-right: 230px;">Rango</label> <label>Precio</label>
				<br>
				<label>Cantidad: </label>
				<label>
					<input id="rinicio1_distancia" style="margin-right: 20px;" placeholder="Desde.." type="number" name="c_rinicio1_distancia">
					<input id="rfin1_distancia" style="margin-right: 20px;" placeholder="Hasta.." type="number" name="c_rfin1_distancia">
				</label>
				<label>$</label>
				<input style="" placeholder="Costo.." type="number" name="precio1_distancia">

				<label style="margin-left: 35px;">
					<input type="submit" value="+" style="width: 40px; font-size: 20px;">
				</label>
			</div>
		</div>


		<!-- 		Inputs de texto para ingresar los valores para "COMBINAR" y su Precio 		-->
		<div name="RsP_combinados" style="display: none;" id="combinadoCalculo">
			<br>
			<p>Ingrese los valores y el precio.</p>
			<br>
			<label style="margin-left: 50px; margin-right: 150px;">Cantidad</label> <label style="margin-right: 150px;">Peso (kg.)</label> <label style="margin-right: 190px;">Distancia</label> <label>Precio</label>
			<br>
			<label>
				<input id="" style="margin-right: 20px;" placeholder="..." type="number" name="">
				<input id="" style="margin-right: 20px;" placeholder="..." type="number" name="">
				<input id="" style="margin-right: 50px;" placeholder="..." type="number" name="">
				<label>$</label>
				<input id="" style="" placeholder="..." type="number" name="">
			</label>

			<label style="margin-left: 35px;">
				<input type="submit" value="+" style="width: 40px; font-size: 20px;">
			</label>
		</div>
	</div>
	<br>

	<!-- 		Input (Boton) para registrar los datos en BDs.		-->
	<div id="cargaRegistro" name="registrar" style="display: none;">
		<input style="margin-top: 20px; margin-left: 400px;" type="submit" value="Registrar">
	</div>

	<div>
		<label>
			<?php
			print_r($msj);

			echo ('<br>');

			print_r($resultado);
			?>
		</label>
	</div>
</form>

<!--TEST 

<div class="campos_texto_add">
    <div>
        <br>
        <label>Cantidad: </label>
        <label>
            <input id="rinicio1_cantidad" style="margin-right: 20px;" placeholder="Desde.." type="text" name="c_rinicio1_cantidad">
            <input id="rfin1_cantidad" style="margin-right: 20px;" placeholder="Hasta.." type="text" name="c_rfin1_cantidad">
        </label>
        <label>$</label>
        <input style="" placeholder="Costo.." type="text" name="precio1_cantidad">
        <a href="javascript:void(0);" class="add_button" title="Add field">ADD</a>
    </div>
</div>


TEST -->



<script type="text/javascript">
	//		Obtiene ID del Cliente que se muetras en el ComboBox		//
	function ShowSelected() {
		/* Para obtener el valor */
		var id_cliente = document.getElementById("cliente_id").value;

		cboxes.style.display = "block";

		//crear array para registro

		//alert(id_cliente);

		/* Para obtener el texto */
		//var combo = document.getElementById("producto");
		//var selected = combo.options[combo.selectedIndex].text;
		//alert(selected);
	}


	//		Habilita/Deshabilita campos para ingresar los Rangos y los precios de todas las Opciones (cantidad, peso, distancia, combinados)		//
	function cambiar() {
		var bultos = document.getElementById("cbox1").checked;
		var peso = document.getElementById("cbox2").checked;
		var distancia = document.getElementById("cbox3").checked;
		var combinacion = document.getElementById("cbox4").checked;

		if (bultos) {
			cantidadBultos.style.display = "block";
			cargaRegistro.style.display = "block";
		} else {
			cantidadBultos.style.display = "none";
			cargaRegistro.style.display = "none";
		}

		if (peso) {
			kilosPeso.style.display = "block";

			if (cargaRegistro.style.display = "none") {
				cargaRegistro.style.display = "block";
			}
		} else {
			kilosPeso.style.display = "none";
		}
		if (distancia) {
			distanciaCalculo.style.display = "block";

			if (cargaRegistro.style.display = "none") {
				cargaRegistro.style.display = "block";
			}
		} else {
			distanciaCalculo.style.display = "none";
		}
		if (combinacion) {
			combinadoCalculo.style.display = "block";

			if (cargaRegistro.style.display = "none") {
				cargaRegistro.style.display = "block";
			}
		} else {
			combinadoCalculo.style.display = "none";
		}
	}


	//

	function limpiarCampos() {
		//document.getElementById("miForm").reset();
	}


	//	Funcionamiento boton Agregar campos/Quitar campos para CANTIDAD DE BULTOS -- Campos dinamicos	//
	$(document).ready(function() {
		var maxField = 10; // Declaro la cantidad maximas de fields que se pueden agregar
		var addButtonCantidad = $('.add_button_cantidad'); // Declaro boton para incrementar
		var wrapper = $('.campos_texto_cantidad_add'); // Input field wrapper

		//  var fieldHTML = '<div><br><label>Cantidad: </label><label><input id="rinicio1_cantidad" style="margin-left: 4px; margin-right: 20px;" placeholder="Desde.." type="text" name="c_rinicio1_cantidad"><input id="rfin1_cantidad" style="margin-right: 20px;" placeholder="Hasta.." type="text" name="c_rfin1_cantidad"></label><label style="margin-left: 10px;">$</label><input style="" placeholder="Costo.." type="text" name="precio1_cantidad"><a style="margin-left: 40px; border: solid 4px; font-size: 20px;" href="javascript:void(0);" class="remove_button" title="Remove field">-</a></div>'; //New input field html 

		var x = 1; // Declaro el contador en 1, porque existe una linea de campos al seleccionar
		$(addButtonCantidad).click(function() { // Agregar al presionar el boton
			if (x < maxField) { // Evalua la cantidad max. de field que se permite cargar
				x++;

				var fieldHTML = '<div><br><label>Cantidad: </label><label><input id="rinicio' + x + '_cantidad" style="margin-left: 4px; margin-right: 20px;" placeholder="Desde.." type="number" name="c_rinicio' + x + '_cantidad"><input id="rfin' + x + '_cantidad" style="margin-right: 20px;" placeholder="Hasta.." type="number" name="c_rfin' + x + '_cantidad"></label><label style="margin-left: 10px;">$</label><input style="" placeholder="Costo.." type="number" id="precio' + x + '_cantidad" name="c_precio' + x + '_cantidad"><a style="margin-left: 40px; border: solid 4px; font-size: 20px;" href="javascript:void(0);" class="remove_button_cantidad" title="Remove field">-</a></div>'; // field

				$(wrapper).append(fieldHTML); // Agregar field html
			}
		});
		$(wrapper).on('click', '.remove_button_cantidad', function(e) { // Remover al presionar boton
			e.preventDefault();
			$(this).parent('div').remove(); // Eliminar field html
			x--;
		});
	});


	//	Funcionamiento boton Agregar campos/Quitar campos para CANTIDAD DE BULTOS 	//
	$(document).ready(function() {
		var maxField = 10;
		var addButtonPeso = $('.add_button_peso');
		var wrapper = $('.campos_texto_peso_add');

		var x = 1;
		$(addButtonPeso).click(function() {
			if (x < maxField) {
				x++;

				var fieldHTML = '<div><br><label>Cantidad: </label><label><input id="rinicio' + x + '_peso" style="margin-left: 4px; margin-right: 20px;" placeholder="Desde.." type="number" name="c_rinicio' + x + '_peso"><input id="rfin' + x + '_peso" style="margin-right: 20px;" placeholder="Hasta.." type="number" name="c_rfin' + x + '_peso"></label><label style="margin-left: 10px;">$</label><input style="" placeholder="Costo.." type="number" id="precio' + x + '_peso" name="c_precio' + x + '_peso"><a style="margin-left: 40px; border: solid 4px; font-size: 20px;" href="javascript:void(0);" class="remove_button_peso" title="Remove field">-</a></div>';

				$(wrapper).append(fieldHTML);
			}
		});
		$(wrapper).on('click', '.remove_button_peso', function(e) {
			e.preventDefault();
			$(this).parent('div').remove();
			x--;
		});
	});


	// ?? mandar datos a base de datos - CREAR REGISTRO - --- php antes que JS //
	function ArmadoRegistro() {
		/*var id_servicio = 10;
		var id_grupo = 20;

		var ir1 = document.getElementById("cantidadBultos").getElementByName("ir1").value;
*/
		//var a = document.getElementsByTagName('RP_bultos').value;
		var a = document.getElementById("divTest").getElementsByTagName('input');

		console.log(a);

		console.log(a[0].value);

		//alert(a);
	}
</script>