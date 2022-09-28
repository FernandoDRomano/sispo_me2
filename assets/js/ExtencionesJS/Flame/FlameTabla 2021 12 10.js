//helper
/*
	var ConfigSample = JSON.parse(`
	{
		"Elemento":"DivIngresadas",
		"Help":true,
		"DataAjax":` + Parametros + `,
		"ValoresDirectos":` + ValoresDirectos + `,
		"MensajeEnFail":false,
		"TextoEnFail":"No Se Encontraron Resultados",
		"ConFiltro":"true",
		"CrearAlCargarDatos":true,
		"EsconderElementos":[` + EsconderElementos + `],
		"DataTable":false,
		"ConPaginado":true,
		
		"AddCheckbox":true,
		
		"AddImput":true,
		"EsconderElementosImput":[` + EsconderElementosImput + `],
		"ImputsAderidosTitulo":"Barcode A Establecer(,)Seleccione",
		"ImputsAderidostype":` + ImputsAderidostype + `,
		
		"BotonParaFuncion":"VerDetallesDePiezas",
		"TextoDeBotonParaFuncion":"Ver Datos De Pieza",
		"ClasseDeBotonParaFuncion":"btn btn-block btn-primary",
		"ClasseDeIconoParaFuncion":"fas fa-eye",
		"EstiloDeIconoParaFuncion":"font-size: 24px;color: #ffffff;",
		
		
		
		"Ajax":"` + URLJS + `/api/flame/Clientes/AjaxClientesencontrados.php"
		
	}`);
*/
	ConfigHelp = {
		"Elemento":"Div Que Contiene La Tabla.",
		"Ajax":"URL del controlador.*No esconder No Es Compatible Con DataTable",
		"ConFiltro":"Se Aplica Filto De Columnas.*No esconder No Es Compatible Con DataTable",
		"ConPaginado":"Se Aplica Numero De Paginas.*No esconder No Es Compatible Con DataTable",
		"CrearAlCargarDatos":"Mostrar Los Datos Cuando Se Realiza La Consulta.",
		"DataAjax":"",
		"DivContenedor":"Div Que Contiene La Tabla.",
		"ElementoMaximoDeFilas":"Elemento Que Indica La Maxima Cantidad De Filas De La Tabla.",
		"Help":"Mostrar Ayuda.",
		"MaximoDeFilas":"Cantidad Maxima De Filas De La Tabla.",
		"MensajeEnFail":"True Si Se Mostrara Mensaje Cuando Falle.",
		"Pagina":"Pagina Actual.",
		"Paginador":"Elemento Que Contiene El Paginador.",
		"Resultado":"Values De Datos En Respuesta.",
		"TextoEnFail":"Texto Si Falla La Consulta.",
		"Titulos":"Key De Datos En Respuesta.",
		"ValoresDirectos":"Variables A Enviar Para La Consulta.",
		"EsconderElementos":'define los numeros de columnas *No esconder No Es Compatible Con DataTable',
		"FiltroIniciado":".",
		"AddImput":".",
		"ImputsAderidosTitulo":".",
		"ClasseDeBotonParaFuncion":".",
		"TextoDeBotonParaFuncion":".",
		"BotonParaFuncion":"Funcion A Llamar.",
		"ClasseDeIconoParaFuncion":"",
		"EstiloDeIconoParaFuncion":"",
		"ConfigFiltro":".",
		"ConfigFiltro.ArraydDeFiltros":"",
		"ConfigFiltro.ArraydDeFiltrosValores":"",
		"Filtrado":"",
		"Filtro":"",
		"FiltroIniciado":"",
		"ResultadoFiltrado":"",
		"RuInicio":"",
		"FilasResultadosDeFiltros":"",
		"ValoresDeInputs":"",
		"AddCheckbox":"La Tabla Se Puede Seleccionar",
		"":"",
		"":"",
		"":"",
		"":"",
		"":"",
		"":"",
		"":"",
		
	};
//resetea tabla
	function ResetTable(DivDeTabla){//Elimino Los Componentes De La Tabla
		var tabla = DivDeTabla.getElementsByTagName('table')[0];
		var ElementosDeTabla = tabla.children;
		i = 0;
		while( typeof(ElementosDeTabla[i]) != 'undefined' ){
			if((ElementosDeTabla[i].classList.contains("Escaner") && ElementosDeTabla[i].tagName == 'TR')){
				i++;
			}else{
				ElementosDeTabla[i].remove();
			}
		}
		/*
		for (i = 0; ElementosDeTabla.length > 3;) {
			if( !(ElementosDeTabla[i].classList.contains("Escaner") && ElementosDeTabla[i].tagName == 'TR')){
				//console.log(ElementosDeTabla[i]);
				ElementosDeTabla[i].remove();
			}else{
				i++
				//console.log('else');
				//console.log(ElementosDeTabla[i]);
			}
		}
		console.log(tabla);
		ElementosDeTabla = tabla.children;
		console.log(ElementosDeTabla.length);
		*/
	}
//resetea tabla
	function ReiniciarTable(DivDeTabla){//Elimino Los Componentes De La Tabla
		var tabla = DivDeTabla.getElementsByTagName('table')[0];
		var ElementosDeTabla = tabla.children;
		while( typeof(ElementosDeTabla[0]) != 'undefined' ){
			ElementosDeTabla[0].remove();
		}
		
		
	}
//Establece El Paginador Desde El Elemento
	function GetPaginador(Config){//Obtengo Datos De Paginador
		var DivDeTabla =  document.getElementById(Config.DivContenedor);
		var Paginador = DivDeTabla.getElementsByClassName('Paginador')[0];
		if( typeof(Paginador) == 'undefined' || Paginador == null ){
			Config.Paginador = "";
			Config.ConPaginado = false;
		}else{
			Config.Paginador = Paginador.id;
			Config.ConPaginado = true;
			/*
			var object1 = {key1: "value1", key2: "value2"};
			var object2 = {key2:"value4", key3: "value3", key4: undefined};
			var obj3 = Object.assign(object1, object2);
			console.log(obj3);
			*/
		}
	}
	function GetMaximoDeFilas(Config){//Obtengo Datos Del Paginador
		var DivDeTabla =  document.getElementById(Config.DivContenedor);
		var DivMaximoDeFilas = DivDeTabla.getElementsByClassName('MaximoDeFilas')[0];
		if( typeof(DivMaximoDeFilas) == 'undefined' || DivMaximoDeFilas == null ){
			Config.MaximoDeFilas = 0;
		}else{
			var MaximoDeFilas = DivMaximoDeFilas.getElementsByTagName('input')[0];
			Config.ElementoMaximoDeFilas = MaximoDeFilas.id;
			Config.MaximoDeFilas = MaximoDeFilas.value;
		}
	}
	
	function FormatearDatosParaTabla(Config = false){
		
		//Muestro Informacion Para El Programador
		if(Config.Help == true){
			//console.log(Config);
			//console.log(ConfigHelp);
		}
		
		//Verifica Si Existe El Elemento Que Contiene La Tabla
		var DivDeTabla =  document.getElementById(Config.DivContenedor);
		if( typeof(DivDeTabla) == 'undefined' || DivDeTabla == null ){
			EndLoading;
			return;
		}else{
		}
		if(Config.AddCheckbox != null){
			var PreDivFullCheckbox = document.getElementsByClassName("DivFullCheckbox");
			if( !(typeof(PreDivFullCheckbox[0]) == 'undefined') ){
				//console.log(PreDivFullCheckbox);
				//console.log(PreDivFullCheckbox[0]);
				PreDivFullCheckbox[0].remove();
			}
			//console.log("173");
			//console.log(PreDivFullCheckbox);
			//console.log(PreDivFullCheckbox[0]);
			if(Config.AddCheckbox == true){
				
				var DivFullCheckbox = document.createElement("DIV");
				DivFullCheckbox.classList.add("col-sm-12");
				DivFullCheckbox.classList.add("DivFullCheckbox");
				DivFullCheckbox.setAttribute("text-align", "right");
				var ImputCheckbox = document.createElement("INPUT");
				ImputCheckbox.type = "checkbox";
				ImputCheckbox.setAttribute("style", "vertical-align: middle;");
				ImputCheckbox.style.width = "33px";
				ImputCheckbox.style.height = "33px";
				var el = $(ImputCheckbox);
				el.data('checked', 1);
				el.prop('indeterminate', true);
				/*
				ImputCheckbox.indeterminate = true;
				ImputCheckbox.checked = false;
				*/
				var ParagrapImputCheckbox = document.createElement("B");
				var Texto = document.createTextNode("Selector De Filas");
				ParagrapImputCheckbox.appendChild(Texto);
				
				ImputCheckbox.onclick = function(){
//Loading();
					var el = $(this);
					switch (el.data('checked')) {
						case 0:
							el.data('checked', 1);
							el.prop('indeterminate', true);
						break;
						case 1:
							el.data('checked', 2);
							el.prop('indeterminate', false);
							el.prop('checked', true);
						break;
						default:
							el.data('checked', 0);
							el.prop('indeterminate', false);
							el.prop('checked', false);
					}
					var DivDeTabla = this;var temp =0;
					do{
						DivDeTabla = DivDeTabla.parentElement;
						temp++;
					}
					while (! ((DivDeTabla.classList.contains("DivFullCheckbox") && DivDeTabla.tagName == 'DIV') && temp < 100));
					DivDeTabla = DivDeTabla.parentElement.parentElement;
					var ContadorDeFilas = DivDeTabla.Config.Resultado.length;
					var tabla = DivDeTabla.getElementsByTagName('table')[0];
					var Tbody = tabla.getElementsByTagName('TBODY')[0];
					var Filas = tabla.getElementsByTagName('TR');
					switch (el.data('checked')) {
						case 0:
							//console.log("0");
							for(var i=0;i<ContadorDeFilas;i++){
								//console.log(i);
								var filaconclick = true;
								var FilaSeleccionable = 0;
								if(DivDeTabla.Config.FilasSeleccionables != null){
									if(!(typeof(DivDeTabla.Config.SelectFila[i]) == 'undefined') ){
										for(var k=0; k<DivDeTabla.Config.FilasSeleccionables.length;k++){
											if( DivDeTabla.Config.ValoresFilasSeleccionables[k] == DivDeTabla.Config.Resultado[i][DivDeTabla.Config.FilasSeleccionables[k]]){
												//console.log(fila.value);
												filaconclick = false;
												FilaSeleccionable = k;
												break;
											}
										}
									}
								}
								if(filaconclick){
									if(!(typeof(DivDeTabla.Config.SelectFila[i]) == 'undefined') ){
										delete DivDeTabla.Config.SelectFila[i];
									}
									for(var j=0;j<Filas.length;j++){
										Filas[j].classList.remove("active");
									}
								}
							}
						break;
						case 1:
							//console.log("1");
							for(var i=0;i<ContadorDeFilas;i++){
								if(!(typeof(DivDeTabla.Config.SelectFila[i]) == 'undefined') ){
									delete DivDeTabla.Config.SelectFila[i];
								}
							}
							for(var i=0;i<Filas.length;i++){
								Filas[i].classList.remove("active");
							}
							for(var i=0;i<DivDeTabla.Config.SelectFilaManual.length;i++){
								DivDeTabla.Config.SelectFila[i] = true;
							}
							for(var i=0;i<Filas.length;i++){
								if( (Filas[i].value) in DivDeTabla.Config.SelectFilaManual ){
									DivDeTabla.Config.SelectFila[Filas[i].value] = true;
									Filas[i].classList.add("active");
								}
							}
						break;
						default:
							//console.log("default");
							for(var i=0;i<ContadorDeFilas;i++){
								var filaconclick = true;
								var FilaSeleccionable = 0;
								if(DivDeTabla.Config.FilasSeleccionables != null){
									if((typeof(DivDeTabla.Config.SelectFila[i]) == 'undefined') ){
										for(var k=0; k<DivDeTabla.Config.FilasSeleccionables.length;k++){
											if( DivDeTabla.Config.ValoresFilasSeleccionables[k] == DivDeTabla.Config.Resultado[i][DivDeTabla.Config.FilasSeleccionables[k]]){
												filaconclick = false;
												FilaSeleccionable = k;
												break;
											}
										}
									}
								}
								if(filaconclick){
									DivDeTabla.Config.SelectFila[i] = true;
								}
							}
							for(var i=0;i<Filas.length;i++){
								if(DivDeTabla.Config.SelectFila[Filas[i].value]){
									Filas[i].classList.add("active");
								}
							}
					}
//setTimeout(function() {EndLoading();},500);
				};
				DivFullCheckbox.appendChild(ImputCheckbox);
				DivFullCheckbox.appendChild(ParagrapImputCheckbox);
				var Agrupador = DivDeTabla.getElementsByClassName('Agrupador')[0];
				Agrupador.appendChild(DivFullCheckbox);
			}
		}
		//Relleno Los Datos De Config Y Le Asigno Al Div
		var tabla = DivDeTabla.getElementsByTagName('table')[0];
		ReiniciarTable(DivDeTabla);
		
		if(Config.ConPaginado != undefined){
			if(Config.ConPaginado){
				GetPaginador(Config);
				GetMaximoDeFilas(Config);
			}
		}
		
		Config.Titulos = Config.Resultado[0];//Obtengo Datos De Resultado Para Los Titulos
		Config.Resultado.shift();//Elimino La Primera Linea De Los Resultados Para Mantener Por Aparte El Titulo Y La Data
		DivDeTabla.Config = CopiarObjeto(Config);//Asigno Al Div
		DivDeTabla.Config.ValoresDeInputs = {};//
		DivDeTabla.Config.SelectFila = {};//
		DivDeTabla.Config.SelectFilaManual = {};//
		
		//Muestro Elementos Ocultos Segun La Configuracion
		var DivPaginador = DivDeTabla.getElementsByClassName('Paginador')[0];
		if(DivDeTabla.Config.ConPaginado){
			DivPaginador.removeAttribute("hidden");
		}
		var DivMaximoDeFilas = DivDeTabla.getElementsByClassName('MaximoDeFilas')[0];
		//console.log(DivMaximoDeFilas);
		if(DivDeTabla.Config.MaximoDeFilas>0){
			DivMaximoDeFilas.removeAttribute("hidden");
		}
		var DivDescargaDeTabla = DivDeTabla.getElementsByClassName('DescargaDeTabla')[0];
		if(DivDeTabla.Config.Resultado.length>0){
			DivDescargaDeTabla.removeAttribute("hidden");
		}
		
		//Genero Paginador
		if( !(typeof(DivMaximoDeFilas) == 'undefined' || DivMaximoDeFilas == null) ){
			var MaximoDeFilas = DivMaximoDeFilas.getElementsByTagName('input')[0];
		}
		if( !(typeof(MaximoDeFilas) == 'undefined' || MaximoDeFilas == null) ){
			MaximoDeFilas.onkeyup = function(){
				//var DivDeTabla = this.parentElement.parentElement;
				var DivDeTabla = this;var temp =0;
				do{
					DivDeTabla = DivDeTabla.parentElement;
					temp++;
					//console.log(DivDeTabla);
				}
				while (! ((DivDeTabla.classList.contains("MaximoDeFilas") && DivDeTabla.tagName == 'DIV') && temp < 100));
				DivDeTabla = DivDeTabla.parentElement.parentElement;
				
				DivDeTabla.Config.MaximoDeFilas = this.value;
				var tabla = DivDeTabla.getElementsByTagName('table')[0];
				var ElementosDeTabla = tabla.getElementsByTagName("tr");
				var SaltarElemento = 0;
				if(DivDeTabla.Config != null){
					ResetTable(DivDeTabla);
					/*
					while(ElementosDeTabla.length > SaltarElemento){
						if(ElementosDeTabla[SaltarElemento].className == "Escaner"){SaltarElemento++;}
						else{ElementosDeTabla[SaltarElemento].remove();}
					}
					*/
					DivDeTabla.Config.Pagina = 1;
					ActualizarTabla(Config.DivContenedor);
				}
			};
		}
		if(Config.CrearAlCargarDatos){
			Config.Pagina = 1;
			CrearTabla(Config);
		}
	}
	
	function CrearTabla(Config){
		var DivDeTabla =  document.getElementById(Config.DivContenedor);
		DivDeTabla.Config.Pagina = 1;
		if( typeof(DivDeTabla) == 'undefined' || DivDeTabla == null ){
			EndLoading;
			return;
		}
		var tabla = DivDeTabla.getElementsByTagName('table')[0];
		ReiniciarTable(DivDeTabla);
		/*
		var ElementosDeTabla = tabla.getElementsByTagName("tr");
		var SaltarElemento = 0;
		if(DivDeTabla.Config != null){
			while(ElementosDeTabla.length > SaltarElemento){
				if(ElementosDeTabla[SaltarElemento].className == "Escaner"){SaltarElemento++;}
				else{ElementosDeTabla[SaltarElemento].remove();}
			}
		}
		*/
		if(Config.ConFiltro && Config.FiltroIniciado === undefined){
			FiltroEnTabla(Config);
		}else{
		}
		if(Config.ConPaginado){
			if(Config.MaximoDeFilas>0){
				var DivPaginador = document.getElementById(Config.Paginador);
				var MaximoDeFilas = document.getElementById(Config.ElementoMaximoDeFilas).value;
				Config.MaximoDeFilas = MaximoDeFilas;
				var CantidadDeFilas = Config.Resultado.length;
				CrearPaginador(Config);
			}else{
				Config.MaximoDeFilas = 100000;
				var CantidadDeFilas = Config.Resultado.length;
				CrearPaginador(Config);
			}
		}
		//Genero Titilos
		var TheadTitulos = document.createElement("thead");
		var Titulos = document.createElement("TR");
		for(var i = 0 ; i <Config.Titulos.length ; i++ ){
			if(Config.EsconderElementos != undefined){
				if(Config.EsconderElementos.find(element => element === i) != undefined){
					var Columna = document.createElement("TH");
					Columna.setAttribute("id", "Titulos_TH"+i);
					Columna.Numero = i;
					Columna.style.display="none";
					var Texto = document.createTextNode(Config.Titulos[i]);
					Columna.appendChild(Texto);
					Titulos.appendChild(Columna);
				}else{
					var Columna = document.createElement("TH");
					Columna.setAttribute("id", "Titulos_TH"+i);
					Columna.Numero = i;
					var Texto = document.createTextNode(Config.Titulos[i]);
					Columna.appendChild(Texto);
					Titulos.appendChild(Columna);
				}
			}else{
				var Columna = document.createElement("TH");
				Columna.setAttribute("id", "Titulos_TH"+i);
				Columna.Numero = i;
				var Texto = document.createTextNode(Config.Titulos[i]);
				Columna.appendChild(Texto);
				Titulos.appendChild(Columna);
			}
			if(Config.AddImput && i+1 == Config.Titulos.length){
				var ArrayTitulosInputs = Config.ImputsAderidosTitulo.split("(,)");
				for(var t = 0; t<ArrayTitulosInputs.length;t++){
					if(Config.EsconderElementosImput != undefined){
						if(Config.EsconderElementosImput.find(element => element === t) != undefined){
							var Columna = document.createElement("TH");
							Columna.setAttribute("id", "Titulos_TH"+i+"-"+t);
							Columna.Numero = i;
							Columna.style.display="none";
							var Texto = document.createElement("P");
							Texto.innerHTML = ArrayTitulosInputs[t];
							Columna.appendChild(Texto);
							Titulos.appendChild(Columna);
						}else{
							var Columna = document.createElement("TH");
							Columna.setAttribute("id", "Titulos_TH"+i+"-"+t);
							Columna.Numero = i;
							var Texto = document.createElement("P");
							Texto.innerHTML = ArrayTitulosInputs[t];
							Columna.appendChild(Texto);
							Titulos.appendChild(Columna);
						}
					}else{
						var Columna = document.createElement("TH");
						Columna.setAttribute("id", "Titulos_TH"+i+"-"+t);
						Columna.Numero = i;
						var Texto = document.createElement("P");
						Texto.innerHTML = ArrayTitulosInputs[t];
						Columna.appendChild(Texto);
						Titulos.appendChild(Columna);
					}
				}
			}
			
			if(Config.BotonParaFuncion != null && i+1 == Config.Titulos.length){
				//console.log(Config.TextoDeBotonParaFuncion);
				var Columna = document.createElement("TH");
				Columna.setAttribute("id", "Titulos_TH"+i+"-Boton");
				Columna.Numero = i;
				//Columna.style.display="none";
				var Texto = document.createElement("P");
				Texto.innerHTML = Config.TextoDeColumnaDeBotonParaFuncion;
				Columna.appendChild(Texto);
				Titulos.appendChild(Columna);
			}
		}
		TheadTitulos.appendChild(Titulos);
		tabla.appendChild(TheadTitulos);
		
		//Creo Paginado
		if(Config.ConPaginado){
			
			var TbodyTitulos = document.createElement("tbody");
			for(var i = (Config.Pagina - 1)*Config.MaximoDeFilas ; i <Config.Resultado.length && i < (Config.Pagina)*Config.MaximoDeFilas ; i++ ){
				var fila = document.createElement("TR");
				fila.value = i;
				if(Config.SelectFila != undefined){
					if(Config.SelectFila[fila.value] != undefined){
						fila.classList.add("active");
					}
				}
				for(var j = 0; j < Config.Resultado[i].length;j++){
					if( Config.Resultado[i][j].search("EmergenteEnTabla=") == 0 ){
					}
					if(Config.EsconderElementos != undefined){
						if(Config.EsconderElementos.find(element => element === j) != undefined){
							var Columna = document.createElement("TD");
							Columna.setAttribute("id", "TD_"+i);
							Columna.style.display="none";
							var Texto = document.createTextNode(Config.Resultado[i][j]);
							Columna.appendChild(Texto);
							fila.appendChild(Columna);
						}else{
							var Columna = document.createElement("TD");
							Columna.setAttribute("id", "TD_"+i);
							var Texto = document.createTextNode(Config.Resultado[i][j]);
							Columna.appendChild(Texto);
							fila.appendChild(Columna);
						}
					}else{
						var Columna = document.createElement("TD");
						Columna.setAttribute("id", "TD_"+i);
						var Texto = document.createTextNode(Config.Resultado[i][j]);
						Columna.appendChild(Texto);
						fila.appendChild(Columna);
					}
					if(Config.AddImput && j+1 == Config.Resultado[i].length){
						var ArrayTitulosInputs = Config.ImputsAderidosTitulo.split("(,)");
						for(var t = 0; t<ArrayTitulosInputs.length;t++){
							var Columna = document.createElement("TD");
							Columna.setAttribute("id", "TD_Imput"+i+"-"+t);
							var Input = document.createElement("INPUT");
							Input.className= "form-control form-control-sm";
							Input.style.color="black";
							Input.onkeyup = function() {
								var DivDeTabla = this;var temp =0;
								do{
									DivDeTabla = DivDeTabla.parentElement;
									temp++;
									//console.log(DivDeTabla);
								}
								while (! (DivDeTabla.tagName == 'TBODY') && temp < 100);
								DivDeTabla = DivDeTabla.parentElement.parentElement;
								
								//var DivDeTabla = this.parentElement.parentElement.parentElement.parentElement;
								var idx = this.parentElement.id;
								var idxvalue = this.value;
								//console.log(DivDeTabla.Config.ValoresDeInputs);
								DivDeTabla.Config.ValoresDeInputs[idx] = idxvalue;
							};
							Columna.appendChild(Input);
							fila.appendChild(Columna);
						}
					}
					if(Config.BotonParaFuncion != null && j+1 == Config.Resultado[i].length){
						var Columna = document.createElement("TD");
						Columna.setAttribute("id", "TD_Boton"+i);
						var Boton = document.createElement("BUTTON");
						Boton.className= Config.ClasseDeBotonParaFuncion;
						var TextoDeBoton = document.createTextNode("" + Config.TextoDeBotonParaFuncion);
						Boton.id = "Boton-" + i;
						Boton.Data = i;
						Boton.onclick = function(){
							var DivDeTabla = this;var temp =0;
							do{
								DivDeTabla = DivDeTabla.parentElement;
								temp++;
								//console.log(DivDeTabla);
							}
							while (! (DivDeTabla.tagName == 'TBODY') && temp < 100);
							DivDeTabla = DivDeTabla.parentElement.parentElement;
							
							//var DivDeTabla = this.parentElement.parentElement.parentElement.parentElement;
							eval(Config.BotonParaFuncion)(this);
					};
					var Icono = document.createElement("I");
					Icono.className= "" + Config.ClasseDeIconoParaFuncion;
					Icono.style="" + Config.EstiloDeIconoParaFuncion;
					Boton.appendChild(Icono);
					Boton.appendChild(TextoDeBoton);
						Columna.appendChild(Boton);
						fila.appendChild(Columna);
					}
				}
				
				if(Config.AddCheckbox != null){
					if(Config.AddCheckbox == true){
					
						//console.log(fila.value);
						//console.log(DivDeTabla.Config.FilasSeleccionables);
						//console.log(DivDeTabla.Config);
						var filaconclick = true;
						var FilaSeleccionable = 0;
						if(Config.FilasSeleccionables != null){
							//console.log(Config.AddCheckbox);
							//console.log("".__LINE__);
							for(var k=0; k<Config.FilasSeleccionables.length;k++){
								if( Config.ValoresFilasSeleccionables[k] == Config.Resultado[fila.value][Config.FilasSeleccionables[k]]){
									//console.log(fila.value);
									filaconclick = false;
									FilaSeleccionable = k;
									break;
								}
							}
						}
						if(filaconclick){
							fila.onclick = function(e){
								if(e.target !== this){
									if(typeof e.target.onclick === "function"){
										return;
									}
								}
								this.classList.toggle('active');
								var DivDeTabla = this;var temp =0;
								do{
									DivDeTabla = DivDeTabla.parentElement;
									temp++;
								}
								while (! (DivDeTabla.tagName == 'TBODY') && temp < 100);
								DivDeTabla = DivDeTabla.parentElement.parentElement;
								
								if(this.classList.contains("active")){
									DivDeTabla.Config.SelectFila[this.value] = true;
									DivDeTabla.Config.SelectFilaManual = CopiarObjeto(DivDeTabla.Config.SelectFila);
								}else{
									delete DivDeTabla.Config.SelectFila[this.value];
									DivDeTabla.Config.SelectFilaManual = CopiarObjeto(DivDeTabla.Config.SelectFila);
								}
							};
						}else{
							var Celdas = fila.getElementsByTagName('td');
							for(var k=0;k<Celdas.length;k++){
								Celdas[k].style.backgroundColor = Config.ColorDeFilasSeleccionables[FilaSeleccionable];
							}
						}
					}
				}
				
				/*
				fila.addEventListener('change', (event) => {
					
					if(event.currentTarget.checked){
						alert("Marcar Todo");
						//$('input[name="totalCost"]').val(10);
					}else{
						//calculate();
						alert("Desmarcar Todo");
					}
					alert("Linea");
				})*/
				
				TbodyTitulos.appendChild(fila);
				tabla.appendChild(TbodyTitulos);
			}
		}else{
			var TbodyTitulos = document.createElement("tbody");
			for(var i = 0 ; i <Config.Resultado.length ; i++ ){
				var fila = document.createElement("TR");
				fila.value = i;
				if(Config.SelectFila != undefined){
					if(Config.SelectFila[fila.value] != undefined){
						fila.classList.add("active");
					}
				}
				for(var j = 0;j<Config.Resultado[i].length;j++){
					if( Config.Resultado[i][j].search("EmergenteEnTabla=") == 0 ){
					}
					if(Config.EsconderElementos != undefined){
						if(Config.EsconderElementos.find(element => element === j) != undefined){
							var Columna = document.createElement("TD");
							Columna.setAttribute("id", "TD_"+i);
							Columna.style.display="none";
							var Texto = document.createTextNode(Config.Resultado[i][j]);
							Columna.appendChild(Texto);
							fila.appendChild(Columna);
						}else{
							var Columna = document.createElement("TD");
							Columna.setAttribute("id", "TD_"+i);
							var Texto = document.createTextNode(Config.Resultado[i][j]);
							Columna.appendChild(Texto);
							fila.appendChild(Columna);
						}
					}else{
						var Columna = document.createElement("TD");
						Columna.setAttribute("id", "TD_"+i);
						var Texto = document.createTextNode(Config.Resultado[i][j]);
						Columna.appendChild(Texto);
						fila.appendChild(Columna);
					}
					if(Config.AddImput && j+1 == Config.Resultado[i].length){
						var ArrayTitulosInputs = Config.ImputsAderidosTitulo.split("(,)");
						for(var t = 0; t<ArrayTitulosInputs.length;t++){
							var Columna = document.createElement("TD");
							Columna.setAttribute("id", "TD_Imput"+i+"-"+t);
							var Input = document.createElement("INPUT");
							Input.className= "form-control form-control-sm";
							Input.style.color="black";
							Input.onkeyup = function() {
								var DivDeTabla = this;var temp =0;
								do{
									DivDeTabla = DivDeTabla.parentElement;
									temp++;
									//console.log(DivDeTabla);
								}
								while (! (DivDeTabla.tagName == 'TBODY') && temp < 100);
								DivDeTabla = DivDeTabla.parentElement.parentElement;
								
								//var DivDeTabla = this.parentElement.parentElement.parentElement.parentElement;
								var idx = this.parentElement.id;
								var idxvalue = this.value;
								//console.log(DivDeTabla.Config.ValoresDeInputs);
								DivDeTabla.Config.ValoresDeInputs[idx] = idxvalue;
							};
							Columna.appendChild(Input);
							fila.appendChild(Columna);
						}
					}
					if(Config.BotonParaFuncion != null && j+1 == Config.Resultado[i].length){
						var Columna = document.createElement("TD");
						Columna.setAttribute("id", "TD_Boton"+i);
						var Boton = document.createElement("BUTTON");
						Boton.className= Config.ClasseDeBotonParaFuncion;
						var TextoDeBoton = document.createTextNode("" + Config.TextoDeBotonParaFuncion);
						Boton.id = "Boton-" + i;
						Boton.Data = i;
						Boton.onclick = function(){
							var DivDeTabla = this;var temp =0;
							do{
								DivDeTabla = DivDeTabla.parentElement;
								temp++;
								//console.log(DivDeTabla);
							}
							while (! (DivDeTabla.tagName == 'TBODY') && temp < 100);
							DivDeTabla = DivDeTabla.parentElement.parentElement;
							
							//var DivDeTabla = this.parentElement.parentElement.parentElement.parentElement;
							eval(Config.BotonParaFuncion)(this);
						};
						var Icono = document.createElement("I");
						Icono.className= "" + Config.ClasseDeIconoParaFuncion;
						Icono.style= "" + Config.EstiloDeIconoParaFuncion;
						Boton.appendChild(Icono);
						Boton.appendChild(TextoDeBoton);
						Columna.appendChild(Boton);
						fila.appendChild(Columna);
					}
				}
				if(Config.AddCheckbox != null){
					if(Config.AddCheckbox == true){
						var filaconclick = true;
						var FilaSeleccionable = 0;
						//console.log("743");
						if(Config.FilasSeleccionables != null){
							//console.log(Config.AddCheckbox);
							//console.log("".__LINE__);
							for(var k=0; k<Config.FilasSeleccionables.length;k++){
								if( Config.ValoresFilasSeleccionables[k] == Config.Resultado[fila.value][Config.FilasSeleccionables[k]]){
									//console.log(fila.value);
									filaconclick = false;
									FilaSeleccionable = k;
									break;
								}
							}
						}
						if(filaconclick){
							fila.onclick = function(e){
								if(e.target !== this){
									//console.log(e.target);
									if(typeof e.target.onclick === "function"){
										//console.log(e.target.onclick);
										return;
									}
								}
								this.classList.toggle('active');
								var DivDeTabla = this;var temp =0;
								do{
									DivDeTabla = DivDeTabla.parentElement;
									temp++;
								}
								while (! (DivDeTabla.tagName == 'TBODY') && temp < 100);
								DivDeTabla = DivDeTabla.parentElement.parentElement;
								if(this.classList.contains("active")){
									DivDeTabla.Config.SelectFila[this.value] = true;
									DivDeTabla.Config.SelectFilaManual = CopiarObjeto(DivDeTabla.Config.SelectFila);
									
								}else{
									delete DivDeTabla.Config.SelectFila[this.value];
									DivDeTabla.Config.SelectFilaManual = CopiarObjeto(DivDeTabla.Config.SelectFila);
								}
								//console.log(DivDeTabla.Config.SelectFila);
								//console.log("526");
							};
						}else{
							var Celdas = fila.getElementsByTagName('td');
							for(var k=0;k<Celdas.length;k++){
								Celdas[k].style.backgroundColor = Config.ColorDeFilasSeleccionables[FilaSeleccionable];
							}
						}
						
					}
				}
				TbodyTitulos.appendChild(fila);
				tabla.appendChild(TbodyTitulos);
			}
		}
		DivDeTabla.appendChild(tabla);
		
		if(Config.DataTable){
			var JqueryTablaId = '#' + (tabla.id);
			$(JqueryTablaId).DataTable(
				{
					"destroy": true,
				}
			);
		}
	}
	function FiltroEnTabla(Config){
		var DivDeTabla =  document.getElementById(Config.DivContenedor);
		var tabla = DivDeTabla.getElementsByTagName('table')[0];
		//ResetTable(tabla);
		ReiniciarTable(DivDeTabla);
		var Titulo = document.createElement("TR");
		Titulo.className = "Escaner";
		Titulo.setAttribute("id", "Escaner");
		for(var j = 0;j<Config.Titulos.length;j++){
			var Columna = document.createElement("TD");
			if(Config.EsconderElementos != undefined){
				if(Config.EsconderElementos.find(element => element === j) != undefined){
					Columna.style.display="none";
				}
			}
			Columna.setAttribute("id", "Escaner"+j);
			var input = document.createElement("input");
			input.type="text";
			input.className="form-control form-control-sm";
			input.style.color="black";
			
			input.placeholder=Config.Titulos[j];
			input.Columna = j;
			input.setAttribute("id", "Input_Escaner"+j);
			if(Config.ConfigFiltro != 'undefined' && Config.ConfigFiltro != null ){
				for(var k = 0 ; k < Config.ConfigFiltro.ArraydDeFiltros.length ; k++ ){
					if(j==Config.ConfigFiltro.ArraydDeFiltros[k]){
						input.value = Config.ConfigFiltro.ArraydDeFiltrosValores[k];
					}
				}
			}
			var label = document.createElement("label");
			var span = document.createElement("span");
			var b = document.createElement("b");
			label.className="control-label";
			span.className="info";
			span.setAttribute("aria-required", "true");
			b.innerHTML = Config.Titulos[j];
			span.appendChild(b);
			label.appendChild(span);
			Columna.appendChild(label);
			Columna.appendChild(input);
			Titulo.onkeyup = function(){
				var ArraydDeFiltros = new Array();
				var ArraydDeFiltrosValores = new Array();
				inputs = this.getElementsByTagName('input');
				for(var i = 0 ; i < inputs.length ; i++){
					if(inputs[i].value != ""){
						ArraydDeFiltros.push(inputs[i].Columna);
						ArraydDeFiltrosValores.push(inputs[i].value);
					}
				}
				var Filtro = ArraidNameValueToJSON(ArraydDeFiltros,ArraydDeFiltrosValores);
				DivDeTabla.Config.Filtrado = true;
				DivDeTabla.Config.Filtro = Filtro;
				DivDeTabla.Config.Pagina = 1;
				ActualizarTabla(DivDeTabla.Config.DivContenedor)
			};
			Titulo.appendChild(Columna);
		}
		
		if(Config.AddImput){
			var ArrayTitulosInputs = Config.ImputsAderidosTitulo.split("(,)");
			for(var t = 0; t<ArrayTitulosInputs.length;t++){
				
				var Columna = document.createElement("TD");
				if(Config.EsconderElementos != undefined){
					if(Config.EsconderElementos.find(element => element === j) != undefined){
						Columna.style.display="none";
					}
				}
				Columna.setAttribute("id", "EscanerRelleno" + t);
				var label = document.createElement("label");
				label.className="control-label";
				var span = document.createElement("span");
				span.className="info";
				span.setAttribute("aria-required", "true");
				//var b = document.createElement("b");
				//b.innerHTML = ArrayTitulosInputs[t];
				//span.appendChild(b);
				label.appendChild(span);
				Columna.appendChild(label);
				
				Titulo.appendChild(Columna);
			}
		}
		
		
		if(Config.BotonParaFuncion){
			var Columna = document.createElement("TD");
			if(Config.EsconderElementos != undefined){
				if(Config.EsconderElementos.find(element => element === j) != undefined){
					Columna.style.display="none";
				}
			}
			Columna.setAttribute("id", "EscanerRellenoBoton");
			var label = document.createElement("label");
			label.className="control-label";
			var span = document.createElement("span");
			span.className="info";
			span.setAttribute("aria-required", "true");
			label.appendChild(span);
			Columna.appendChild(label);
			Titulo.appendChild(Columna);
		}
		
		DivDeTabla.Config.FiltroIniciado = true;
		tabla.appendChild(Titulo);
		DivDeTabla.appendChild(tabla);
	}

	function CrearPaginador(Config){
		//console.log(Config);
		var Config = CopiarObjeto(Config);
		if(Config.Paginador != undefined && Config.Paginador != ""){
			var DivPaginador = document.getElementById(Config.Paginador);
			DivPaginador.innerHTML = "";
			var Ul = document.createElement("ul");
			Ul.id="Paginador";
			Ul.classList.add("pagination");
			var ContadorTemporal=1;
			if(Config.Filtrado){
				var NumeroDeFilas = Config.ResultadoFiltrado.length;
			}else{
				var NumeroDeFilas = Config.Resultado.length;
			}
			if(true){
				var CantidadDePaginas = (NumeroDeFilas/Config.MaximoDeFilas*1);
				var li = document.createElement("li");
				li.id = 1;
				li.style = "display:contents" ;
				var ElementA = document.createElement("a");
				ElementA.innerHTML = "&Lang;";
				ElementA.Item = 1;
				ElementA.onclick = function() {
					//var DivDeTabla = this.parentElement.parentElement.parentElement.parentElement;
					var DivDeTabla = this.parentElement;var temp =0;
					do{
						DivDeTabla = DivDeTabla.parentElement;
						temp++;
						//console.log(temp);
					}
					while (! ((DivDeTabla.classList.contains("Paginador") && DivDeTabla.tagName == 'DIV') && temp < 100));
					DivDeTabla = DivDeTabla.parentElement.parentElement;
					
					DivDeTabla.Config.Pagina = this.Item*1;
					var idDiv = this.parentElement.parentElement.id;
					var lis = this.parentElement.parentElement.getElementsByTagName("li");
					for( var i = 0 ; i < lis.length ; i++ ){
						if( !(i > (1 - 5) && i < (1 + 5)) && (i > 0) && (i < lis.length*1 -1)){
							lis[i].style = "display:none" ;
						}else{
							lis[i].style = "display:contents" ;
						}
					}
					$("#"+idDiv+">li.active").removeClass("active");
					var li = this.parentElement;
					if(li.id = DivDeTabla.Config.Pagina){
						li.className ="active";
					}
					Config.RuInicio = false;
					ActualizarTabla(Config.DivContenedor);
				};
				li.appendChild(ElementA);
				Ul.appendChild(li);
				for(var i = 1; (i*1)-NumeroDeFilas <= 0 ; i+=(Config.MaximoDeFilas*1), ContadorTemporal++){
					var li = document.createElement("li");
					li.id = ContadorTemporal;
					if( !(ContadorTemporal > (Config.Pagina*1 - 5) && ContadorTemporal < (Config.Pagina*1 + 5)) ){
						li.style = "display:none" ;
					}else{
						li.style = "display:contents" ;
					}
					var ElementA = document.createElement("a");
					ElementA.innerHTML = ContadorTemporal;
					ElementA.Item = ContadorTemporal;
					ElementA.Config = Config;
					ElementA.onclick = function() {
						var DivDeTabla = this.parentElement;var temp =0;
						do{
							DivDeTabla = DivDeTabla.parentElement;
							temp++;
							//console.log(temp);
						}
						while (! ((DivDeTabla.classList.contains("Paginador") && DivDeTabla.tagName == 'DIV') && temp < 100));
						DivDeTabla = DivDeTabla.parentElement.parentElement;
						
						//console.log(DivDeTabla.classList.contains("Paginador"));
						//console.log(DivDeTabla);
						
						//var DivDeTabla = this.parentElement.parentElement.parentElement.parentElement;
						//console.log(DivDeTabla.Config);
						DivDeTabla.Config.Pagina = this.Item*1;
						var idDiv = this.parentElement.parentElement.id;
						var lis = this.parentElement.parentElement.getElementsByTagName("li");
						for( var i = 0 ; i < lis.length ; i++ ){
							if( !(i > (this.Item*1 - 5) && i < (this.Item*1 + 5)) && (i > 0) && (i < lis.length*1 -1)){
								lis[i].style = "display:none" ;
							}else{
								lis[i].style = "display:contents" ;
							}
						}
						$("#"+idDiv+">li.active").removeClass("active");
						var li = this.parentElement;
						Config.RuInicio = false;
						ActualizarTabla(Config.DivContenedor);
					};
					if(li.id == Config.Pagina){
						li.className = "active";
					}
					li.appendChild(ElementA);
					Ul.appendChild(li);
				}
				var li = document.createElement("li");
				li.id = ContadorTemporal;
				li.style = "display:contents" ;
				var ElementA = document.createElement("a");
				ElementA.innerHTML = "&Rang;";
				ElementA.Item = ContadorTemporal;
				ElementA.onclick = function() {
					//var DivDeTabla = this.parentElement.parentElement.parentElement.parentElement;
					var DivDeTabla = this.parentElement;var temp =0;
					do{
						DivDeTabla = DivDeTabla.parentElement;
						temp++;
						//console.log(temp);
					}
					while (! ((DivDeTabla.classList.contains("Paginador") && DivDeTabla.tagName == 'DIV') && temp < 100));
					DivDeTabla = DivDeTabla.parentElement.parentElement;
					var idDiv = this.parentElement.parentElement.id;
					var lis = this.parentElement.parentElement.getElementsByTagName("li");
					DivDeTabla.Config.Pagina = lis.length-2;
					for( var i = 0 ; i < lis.length ; i++ ){
						if( !(i > ( (lis.length-2) - 5) && i < ( (lis.length-2) + 5)) && (i > 0) && (i < lis.length -2)){
							lis[i].style = "display:none" ;
						}else{
							lis[i].style = "display:contents" ;
						}
					}
					$("#"+idDiv+">li.active").removeClass("active");
					var li = this.parentElement;
					if(li.id = DivDeTabla.Config.Pagina){
						li.className ="active";
					}
					Config.RuInicio = false;
					ActualizarTabla(Config.DivContenedor);
				};
				li.appendChild(ElementA);
				Ul.appendChild(li);
				DivPaginador.appendChild(Ul);
			}
		}
	}

	function ActualizarTabla(div){
		var DivDeTabla =  document.getElementById(div);
		if( typeof(DivDeTabla) == 'undefined' || DivDeTabla == null ){
			EndLoading;
			alert("Reporte Este Error: ActualizarTabla - typeof(DivDeTabla) == 'undefined'");
			return;
		}
		Config = DivDeTabla.Config;
		if(Config.ConFiltro && Config.FiltroIniciado === undefined){
			FiltroEnTabla(Config);
		}else{
			if(Config.Filtrado){
				var Llaves = new Array;
				var Valores = new Array;
				var ResultadoFiltrado = new Array;
				var FilasResultadosDeFiltros = new Array;
				for(var i = 0 ; i < jQuery.parseJSON(Config.Filtro).length ; i++){
					Llaves[i] = Object.keys( jQuery.parseJSON(Config.Filtro)[i] )[0]*1;
					Valores[i] = jQuery.parseJSON(Config.Filtro)[i][Llaves[i]];
				}
				var Encontrado;
				var Encontrados = 0;
				for(var i = 0 ; i < Config.Resultado.length ; i++){
					Encontrado = true;
					for(var j = 0 ; j < Llaves.length ; j++){
						if(Config.Resultado[i][Llaves[j]].toLowerCase().indexOf(""+Valores[j].toLowerCase()) == -1){
							Encontrado = false;
						}
					}
					if(Encontrado){
						FilasResultadosDeFiltros[Encontrados] = i;
						ResultadoFiltrado[Encontrados] = Config.Resultado[i];
						Encontrados++;
					}
				}
				Config.ResultadoFiltrado = ResultadoFiltrado;
				Config.FilasResultadosDeFiltros=FilasResultadosDeFiltros;
			}
		}
		var tabla = DivDeTabla.getElementsByTagName('table')[0];
		//ResetTable(tabla);
		var ElementosDeTabla = tabla.getElementsByTagName("tr");
		var SaltarElemento = 0;
		if(DivDeTabla.Config != null){
			ResetTable(DivDeTabla);
			/*
			while(ElementosDeTabla.length > SaltarElemento){
				if(ElementosDeTabla[SaltarElemento].className == "Escaner"){SaltarElemento++;}
				else{ElementosDeTabla[SaltarElemento].remove();}
			}
			*/
		}
		var TheadTitulos = document.createElement("thead");
		var Titulos = document.createElement("TR");
		for(var i = 0 ; i <Config.Titulos.length ; i++ ){
			/*
			//Checkbox
			if(Config.AddCheckbox && i == 0){
				var Columna = document.createElement("TH");
				Columna.setAttribute("id", "Titulos_TH"+i+"AddCheckbox");
				var Texto = document.createTextNode("Seleccione");
				var Input = document.createElement("INPUT");
				Input.className= "form-control form-control-sm";
				Input.type = "checkbox";
				
				Input.addEventListener('change', (event) => {
					if(event.currentTarget.checked){
						alert("Marcar Todo");
						//$('input[name="totalCost"]').val(10);
					}else{
						//calculate();
						alert("Desmarcar Todo");
					}
				})
				Columna.appendChild(Texto);
				Columna.appendChild(Input);
				Titulos.appendChild(Columna);
			}
			*/
			//
			var Columna = document.createElement("TH");
			Columna.setAttribute("id", "Titulos_TH"+i);
			Columna.Numero = i;
			if(Config.EsconderElementos != undefined){
				if(Config.EsconderElementos.find(element => element === i) != undefined){
					Columna.style.display="none";
				}
			}
			var Texto = document.createTextNode(Config.Titulos[i]);
			Columna.appendChild(Texto);
			Titulos.appendChild(Columna);
			if(Config.AddImput && i+1 == Config.Titulos.length){
				var ArrayTitulosInputs = Config.ImputsAderidosTitulo.split("(,)");
				for(var t = 0; t<ArrayTitulosInputs.length;t++){
					var Columna = document.createElement("TH");
					Columna.setAttribute("id", "Titulos_TH"+i+"-"+t);
					Columna.Numero = i;
					if(Config.EsconderElementosImput != undefined){
						if(Config.EsconderElementosImput.find(element => element === t) != undefined){
							Columna.style.display="none";
						}
					}
					var Texto = document.createElement("P");
					Texto.innerHTML = ArrayTitulosInputs[t];
					Columna.appendChild(Texto);
					Titulos.appendChild(Columna);
				}
			}
			
			if(Config.BotonParaFuncion != null && i+1 == Config.Titulos.length){
				//console.log(Config.TextoDeBotonParaFuncion);
				var Columna = document.createElement("TH");
				Columna.setAttribute("id", "Titulos_TH"+i+"-Boton");
				Columna.Numero = i;
				//Columna.style.display="none";
				var Texto = document.createElement("P");
				Texto.innerHTML = Config.TextoDeColumnaDeBotonParaFuncion;
				Columna.appendChild(Texto);
				Titulos.appendChild(Columna);
			}
		}
		TheadTitulos.appendChild(Titulos);
		tabla.appendChild(TheadTitulos);
		
		if(Config.ConPaginado){
			if(Config.MaximoDeFilas>0){
				var DivPaginador = document.getElementById(Config.Paginador);
				DivPaginador.innerHTML = "";
				var MaximoDeFilas = document.getElementById(Config.ElementoMaximoDeFilas).value;
				Config.MaximoDeFilas = MaximoDeFilas;
				if(Config.ResultadoFiltrado != undefined){
					var CantidadDeFilas = Config.ResultadoFiltrado.length;
				}else{
					var CantidadDeFilas = Config.Resultado.length;
					Config.Filtrado = false;
				}
				CrearPaginador(Config);
			}else{
				Config.MaximoDeFilas = 100000;
				if(Config.ResultadoFiltrado != undefined){
					var CantidadDeFilas = Config.ResultadoFiltrado.length;
				}else{
					var CantidadDeFilas = Config.Resultado.length;
					Config.Filtrado = false;
				}
				CrearPaginador(Config);
			}
		}
		
		if(Config.ConPaginado){
			if(Config.Filtrado){
				var TbodyTitulos = document.createElement("tbody");
				for(var i = (Config.Pagina - 1)*Config.MaximoDeFilas ; i <Config.ResultadoFiltrado.length && i < (Config.Pagina)*Config.MaximoDeFilas ; i++ ){
					var fila = document.createElement("TR");
					fila.value = Config.FilasResultadosDeFiltros[i];
					if(Config.SelectFila != undefined){
						if(Config.SelectFila[fila.value] != undefined){
							fila.classList.add("active");
						}
					}
					for(var j = 0;j<Config.ResultadoFiltrado[i].length;j++){
						/*
						if(Config.AddCheckbox && j == 0){
							var Columna = document.createElement("TD");
							Columna.setAttribute("id", "TD_Checkbox"+Config.FilasResultadosDeFiltros[i]+"-"+t);
							var Input = document.createElement("INPUT");
							Input.className= "form-control form-control-sm";
							Input.type = "checkbox";
							Input.onkeyup = function() {
							};
							Columna.appendChild(Input);
							fila.appendChild(Columna);
						}
						*/
						
						
						
						if( Config.ResultadoFiltrado[i][j].search("EmergenteEnTabla=") == 0 ){
						}
						var Columna = document.createElement("TD");
						Columna.setAttribute("id", "TD_"+i);
						if(Config.EsconderElementos != undefined){
							if(Config.EsconderElementos.find(element => element === j) != undefined){
								Columna.style.display="none";
							}
						}
						var Texto = document.createTextNode(Config.ResultadoFiltrado[i][j]);
						Columna.appendChild(Texto);
						fila.appendChild(Columna);
						
						
						
						if(Config.AddImput && j+1 == Config.Resultado[i].length){
							/*
							console.log("Ini Filtro");
							console.log(j+1);
							console.log(Config.Resultado[i]);
							console.log(Config.FilasResultadosDeFiltros[i]);
							console.log("Fin Filtro");
							*/
							var ArrayTitulosInputs = Config.ImputsAderidosTitulo.split("(,)");
							for(var t = 0; t<ArrayTitulosInputs.length;t++){
								var Columna = document.createElement("TD");
								Columna.setAttribute("id", "TD_Imput"+Config.FilasResultadosDeFiltros[i]+"-"+t);
								var Input = document.createElement("INPUT");
								Input.className= "form-control form-control-sm";
								Input.style.color="black";
								if(Config.ValoresDeInputs["TD_Imput"+Config.FilasResultadosDeFiltros[i]+"-"+t] != undefined ){
									Input.value = Config.ValoresDeInputs["TD_Imput"+Config.FilasResultadosDeFiltros[i]+"-"+t];
								}
								Input.onkeyup = function() {
									var DivDeTabla = this;var temp =0;
									do{
										DivDeTabla = DivDeTabla.parentElement;
										temp++;
										//console.log(DivDeTabla);
									}
									while (! (DivDeTabla.tagName == 'TBODY') && temp < 100);
									DivDeTabla = DivDeTabla.parentElement.parentElement;
									
									//var DivDeTabla = this.parentElement.parentElement.parentElement.parentElement;
									var idx = this.parentElement.id;
									var idxvalue = this.value;
									//console.log(DivDeTabla.Config.ValoresDeInputs);
									DivDeTabla.Config.ValoresDeInputs[idx] = idxvalue;
								};
								Columna.appendChild(Input);
								fila.appendChild(Columna);
							}
						}
						if(Config.BotonParaFuncion != null && j+1 == Config.Resultado[i].length){
							var Columna = document.createElement("TD");
							Columna.setAttribute("id", "TD_Boton"+i);
							var Boton = document.createElement("BUTTON");
							Boton.className= Config.ClasseDeBotonParaFuncion;
							var TextoDeBoton = document.createTextNode("" + Config.TextoDeBotonParaFuncion);
							Boton.id = "Boton-" + Config.FilasResultadosDeFiltros[i];
							Boton.Data = Config.FilasResultadosDeFiltros[i];
							Boton.onclick = function(){
								var DivDeTabla = this.parentElement.parentElement.parentElement.parentElement;
								eval(Config.BotonParaFuncion)(this);
							};
							var Icono = document.createElement("I");
							Icono.className= "" + Config.ClasseDeIconoParaFuncion;
							Icono.style="" + Config.EstiloDeIconoParaFuncion;
							Boton.appendChild(Icono);
							Boton.appendChild(TextoDeBoton);
							Columna.appendChild(Boton);
							fila.appendChild(Columna);
						}
					}
					if(Config.AddCheckbox != null){
						if(Config.AddCheckbox == true){
							var filaconclick = true;
							var FilaSeleccionable = 0;
							if(Config.FilasSeleccionables != null){
							//console.log(Config.AddCheckbox);
							//console.log("".__LINE__);
								for(var k=0; k<Config.FilasSeleccionables.length;k++){
									if( Config.ValoresFilasSeleccionables[k] == Config.Resultado[fila.value][Config.FilasSeleccionables[k]]){
										//console.log(fila.value);
										filaconclick = false;
										FilaSeleccionable = k;
										break;
									}
								}
							}
							if(filaconclick){
								fila.onclick = function(e){
									if(e.target !== this){
										//console.log(e.target);
										if(typeof e.target.onclick === "function"){
											//console.log(e.target.onclick);
											return;
										}
									}
									this.classList.toggle('active');
									var DivDeTabla = this;var temp =0;
									do{
										DivDeTabla = DivDeTabla.parentElement;
										temp++;
									}
									while (! (DivDeTabla.tagName == 'TBODY') && temp < 100);
									DivDeTabla = DivDeTabla.parentElement.parentElement;
									if(this.classList.contains("active")){
										DivDeTabla.Config.SelectFila[this.value] = true;
										DivDeTabla.Config.SelectFilaManual = CopiarObjeto(DivDeTabla.Config.SelectFila);
										
									}else{
										delete DivDeTabla.Config.SelectFila[this.value];
										DivDeTabla.Config.SelectFilaManual = CopiarObjeto(DivDeTabla.Config.SelectFila);
									}
									//console.log(DivDeTabla.Config.SelectFila);
									//console.log("999");
								};
								
							}else{
								var Celdas = fila.getElementsByTagName('td');
								for(var k=0;k<Celdas.length;k++){
									Celdas[k].style.backgroundColor = Config.ColorDeFilasSeleccionables[FilaSeleccionable];
								}
							}
						}
					}
					TbodyTitulos.appendChild(fila);
					tabla.appendChild(TbodyTitulos);
				}
			}else{
				var TbodyTitulos = document.createElement("tbody");
				for(var i = (Config.Pagina - 1)*Config.MaximoDeFilas ; i <Config.Resultado.length && i < (Config.Pagina)*Config.MaximoDeFilas ; i++ ){
					var fila = document.createElement("TR");
					fila.value = i;
					if(Config.SelectFila != undefined){
						if(Config.SelectFila[fila.value] != undefined){
							fila.classList.add("active");
						}
					}
					for(var j = 0;j<Config.Resultado[i].length;j++){
						if( Config.Resultado[i][j].search("EmergenteEnTabla=") == 0 ){
						}
						var Columna = document.createElement("TD");
						Columna.setAttribute("id", "TD_"+i);
						if(Config.EsconderElementos != undefined){
							if(Config.EsconderElementos.find(element => element === j) != undefined){
								Columna.style.display="none";
							}
						}
						var Texto = document.createTextNode(Config.Resultado[i][j]);
						Columna.appendChild(Texto);
						fila.appendChild(Columna);
						if(Config.AddImput && j+1 == Config.Resultado[i].length){
							var ArrayTitulosInputs = Config.ImputsAderidosTitulo.split("(,)");
							for(var t = 0; t<ArrayTitulosInputs.length;t++){
								var Columna = document.createElement("TD");
								Columna.setAttribute("id", "TD_Imput"+i+"-"+t);
								var Input = document.createElement("INPUT");
								Input.className= "form-control form-control-sm";
								Input.style.color="black";
								if(Config.ValoresDeInputs["TD_Imput"+i+"-"+t] != undefined ){
									Input.value = Config.ValoresDeInputs["TD_Imput"+i+"-"+t];
								}
								Input.onkeyup = function() {
									var DivDeTabla = this.parentElement.parentElement.parentElement.parentElement;
									var idx = this.parentElement.id;
									var idxvalue = this.value;
									//console.log(DivDeTabla.Config.ValoresDeInputs);
									DivDeTabla.Config.ValoresDeInputs[idx] = idxvalue;
								};
								Columna.appendChild(Input);
								fila.appendChild(Columna);
							}
						}
						if(Config.BotonParaFuncion != null && j+1 == Config.Resultado[i].length){
							var Columna = document.createElement("TD");
							Columna.setAttribute("id", "TD_Boton"+i);
							if(Config.EsconderElementos != undefined){
								if(Config.EsconderElementos.find(element => element === j) != undefined){
									Columna.style.display="none";
								}
							}
							var Boton = document.createElement("BUTTON");
							Boton.className= Config.ClasseDeBotonParaFuncion;
							var TextoDeBoton = document.createTextNode("" + Config.TextoDeBotonParaFuncion);
							Boton.id = "Boton-" + i;
							Boton.Data = i;
							Boton.onclick = function(){
								var DivDeTabla = this.parentElement.parentElement.parentElement.parentElement;
								eval(Config.BotonParaFuncion)(this);
							};
							var Icono = document.createElement("I");
							Icono.className= "" + Config.ClasseDeIconoParaFuncion;
							Icono.style="" + Config.EstiloDeIconoParaFuncion;
							Boton.appendChild(Icono);
							Boton.appendChild(TextoDeBoton);
							Columna.appendChild(Boton);
							fila.appendChild(Columna);
						}
					}
					if(Config.AddCheckbox != null){
						if(Config.AddCheckbox == true){
							var filaconclick = true;
							var FilaSeleccionable = 0;
							if(Config.FilasSeleccionables != null){
								//console.log(Config.AddCheckbox);
								//console.log("".__LINE__);
								for(var k=0; k<Config.FilasSeleccionables.length;k++){
									if( Config.ValoresFilasSeleccionables[k] == Config.Resultado[fila.value][Config.FilasSeleccionables[k]]){
										//console.log(fila.value);
										filaconclick = false;
										FilaSeleccionable = k;
										break;
									}
								}
							}
							if(filaconclick){

								fila.onclick = function(e){
									if(e.target !== this){
										//console.log(e.target);
										if(typeof e.target.onclick === "function"){
											//console.log(e.target.onclick);
											return;
										}
									}
									this.classList.toggle('active');
									var DivDeTabla = this;var temp =0;
									do{
										DivDeTabla = DivDeTabla.parentElement;
										temp++;
									}
									while (! (DivDeTabla.tagName == 'TBODY') && temp < 100);
									DivDeTabla = DivDeTabla.parentElement.parentElement;
									if(this.classList.contains("active")){
										DivDeTabla.Config.SelectFila[this.value] = true;
										DivDeTabla.Config.SelectFilaManual = CopiarObjeto(DivDeTabla.Config.SelectFila);
										
									}else{
										delete DivDeTabla.Config.SelectFila[this.value];
										DivDeTabla.Config.SelectFilaManual = CopiarObjeto(DivDeTabla.Config.SelectFila);
									}
									//console.log(DivDeTabla.Config.SelectFila);
									//console.log("1083");
								};

							}else{
								var Celdas = fila.getElementsByTagName('td');
								for(var k=0;k<Celdas.length;k++){
									Celdas[k].style.backgroundColor = Config.ColorDeFilasSeleccionables[FilaSeleccionable];
								}
							}
							
						}
					}
					TbodyTitulos.appendChild(fila);
					tabla.appendChild(TbodyTitulos);
				}
			}
		}else{
			var TbodyTitulos = document.createElement("tbody");
			for(var i = 0 ; i <Config.Resultado.length ; i++ ){
				var fila = document.createElement("TR");
				for(var j = 0;j<Config.Resultado[i].length;j++){
					if( Config.Resultado[i][j].search("EmergenteEnTabla=") == 0 ){
					}
					var Columna = document.createElement("TD");
					Columna.setAttribute("id", "TD_"+i);
					if(Config.EsconderElementos != undefined){
						if(Config.EsconderElementos.find(element => element === j) != undefined){
							Columna.style.display="none";
						}
					}
					var Texto = document.createTextNode(Config.Resultado[i][j]);
					Columna.appendChild(Texto);
					fila.appendChild(Columna);
				}
				if(Config.AddCheckbox != null){
					if(Config.AddCheckbox == true){
						var filaconclick = true;
						var FilaSeleccionable = 0;
						if(Config.FilasSeleccionables != null){
							//console.log(Config.AddCheckbox);
							//console.log("".__LINE__);
							for(var k=0; k<Config.FilasSeleccionables.length;k++){
								if( Config.ValoresFilasSeleccionables[k] == Config.Resultado[fila.value][Config.FilasSeleccionables[k]]){
									//console.log(fila.value);
									filaconclick = false;
									FilaSeleccionable = k;
									break;
								}
							}
						}
						if(filaconclick){
							fila.onclick = function(e){
								if(e.target !== this){
									//console.log(e.target);
									if(typeof e.target.onclick === "function"){
										//console.log(e.target.onclick);
										return;
									}
								}
								this.classList.toggle('active');
								var DivDeTabla = this;var temp =0;
								do{
									DivDeTabla = DivDeTabla.parentElement;
									temp++;
								}
								while (! (DivDeTabla.tagName == 'TBODY') && temp < 100);
								DivDeTabla = DivDeTabla.parentElement.parentElement;
								if(this.classList.contains("active")){
									DivDeTabla.Config.SelectFila[this.value] = true;
									DivDeTabla.Config.SelectFilaManual = CopiarObjeto(DivDeTabla.Config.SelectFila);
								}else{
									delete DivDeTabla.Config.SelectFila[this.value];
									DivDeTabla.Config.SelectFilaManual = CopiarObjeto(DivDeTabla.Config.SelectFila);
								}
								//console.log(DivDeTabla.Config.SelectFila);
								//console.log("1122");
							};


						}else{
							var Celdas = fila.getElementsByTagName('td');
							for(var k=0;k<Celdas.length;k++){
								Celdas[k].style.backgroundColor = Config.ColorDeFilasSeleccionables[FilaSeleccionable];
							}
						}
					}
				}
				TbodyTitulos.appendChild(fila);
				tabla.appendChild(TbodyTitulos);
			}
		}
		
		if(Config.DataTable){
			var JqueryTablaId = '#' + (tabla.id);
			$(JqueryTablaId).DataTable(
				{
					"destroy": true,
				}
			);
		}
	}

	function TablaDataTable(Config = false){
		//console.log(Config);
		//console.log(JSON.parse(DataTable));
		var DivDeTabla =  document.getElementById(Config.DivContenedor);
		var tabla = DivDeTabla.getElementsByTagName('table')[0];
		//ResetTable(tabla);
		var NombreDeTabla = '#' + tabla.id;
		if ( $.fn.dataTable.isDataTable( NombreDeTabla ) ) {
			var table = $(NombreDeTabla).DataTable();
			table.destroy();
			$(NombreDeTabla).empty();
		}
		var ElementoTheadDeTabla = tabla.getElementsByTagName("thead");
		var ElementoTbodyDeTabla = tabla.getElementsByTagName("tbody");
		if(ElementoTheadDeTabla != null){
			while(ElementoTheadDeTabla.length > 0){
				ElementoTheadDeTabla[0].remove();
			}
		}
		if(ElementoTbodyDeTabla != null){
			while(ElementoTbodyDeTabla.length > 0){
				ElementoTbodyDeTabla[0].remove();
			}
		}
		var THEAD = document.createElement("THEAD");
		var Titulos = document.createElement("TR");
		for(var i = 0 ; i <Config.Titulos.length ; i++ ){
			var Columna = document.createElement("TH");
			Columna.setAttribute("id", "Titulos_TH"+i);
			Columna.Numero = i;
			var Link = document.createElement("A");
			Link.setAttribute("href", "#");
			Link.style.whiteSpace = "break-spaces";
			var Texto = document.createTextNode(Config.Titulos[i]);
			Link.appendChild(Texto);
			Columna.appendChild(Link);
			Titulos.appendChild(Columna);
		}
		THEAD.appendChild(Titulos);
		tabla.appendChild(THEAD);
		var TBODY = document.createElement("TBODY");
		for(var i = 0 ; i <Config.Resultado.length ; i++ ){
			var fila = document.createElement("TR");
			for(var j = 0;j<Config.Resultado[i].length;j++){
				{
					var Columna = document.createElement("TD");
					Columna.setAttribute("id", "TD_"+i);
					var Texto = document.createTextNode(Config.Resultado[i][j]);
					Columna.appendChild(Texto);
					fila.appendChild(Columna);
				}
			}
			TBODY.appendChild(fila);
			tabla.appendChild(TBODY);
		}
		DivDeTabla.appendChild(tabla);
		if ( $.fn.dataTable.isDataTable( NombreDeTabla ) ) {
			table = $(NombreDeTabla).DataTable( {
				"columnDefs": [{
				  "targets"  : 'no-sort',
				  "orderable": false,
				}]
			} );
			//console.log("Edit");
		}
		else {
			table = $(NombreDeTabla).DataTable( {
				"columnDefs": [{
				  "targets"  : 'no-sort',
				  "orderable": false,
				}]
			} );
			//console.log("ini");
		}
		return;
	}










