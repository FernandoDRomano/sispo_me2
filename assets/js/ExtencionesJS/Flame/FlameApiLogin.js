function FlameGrowlFail(Msj){
	if(typeof $.bootstrapGrowl === "function") {
		$.bootstrapGrowl(Msj,{
			type: 'danger',
			align: 'center',
			width: 'auto'
		});
	}
}
function FlameGrowlSuccess(Msj){
	if(typeof $.bootstrapGrowl === "function") {
		$.bootstrapGrowl(Msj,{
			type: 'success',
			align: 'center',
			width: 'auto'
		});
	}
}

//Coloca El Dato Obtenido De La Consulta En El Elemento <p>
function ApiAutoParrafo(Config,DatosJson){
	if(DatosJson != undefined){
		var Elemento = document.getElementById(Config.Elemento);
		if(DatosJson[0] != null){
			if(DatosJson.length>1){
				console.log("El Elemento No Puede Contener El Arrayd De Datos");
				console.log(DatosJson);
				console.log(DatosJson.length);
			}else{
				var keys = Object.keys(DatosJson[0]);
				//console.log(DatosJson);
				//console.log(DatosJson.length);
				//console.log(keys);
				//console.log(DatosJson[0][keys[0]]);
				Elemento.innerHTML = DatosJson[0][keys[0]];
				Elemento.value = DatosJson[0][keys[0]];
			}
		}else{
			Elemento.innerHTML = '';
			Elemento.value = '';
		}
	}
}

//Coloca El Dato Obtenido De La Consulta En El Elemento <input>
function ApiAutoInput(Config,DatosJson){
	if(DatosJson != undefined){
		var Elemento = document.getElementById(Config.Elemento);
		if(DatosJson[0] != null){
			if(DatosJson.length>1){
				console.log("El Elemento No Puede Contener El Arrayd De Datos");
				console.log(DatosJson);
				console.log(DatosJson.length);
			}else{
				var keys = Object.keys(DatosJson[0]);
				//console.log(DatosJson);
				//console.log(DatosJson.length);
				//console.log(keys);
				//console.log(DatosJson[0][keys[0]]);
				Elemento.value = DatosJson[0][keys[0]];
			}
		}else{
			Elemento.value = '';
		}
	}
}


//Muestra Un Mensje Con El Dato Obtenido
function ApiAutoGrowl(Config,DatosJson){
	if(DatosJson != undefined){
		if(DatosJson[0] != null){
			if(DatosJson.length>1){
				console.log("El Grow No Puede Mostrar El Arrayd De Datos");
				console.log(DatosJson);
				console.log(DatosJson.length);
			}else{
				var keys = Object.keys(DatosJson[0]);
				console.log(DatosJson);
				console.log(DatosJson.length);
				console.log(keys);
				console.log(DatosJson[0][keys[0]]);
				FlameGrowlSuccess(DatosJson[0][keys[0]]);
			}
		}else{
			console.log(DatosJson);
				console.log(DatosJson.length);
		}
	}
}

//Coloca El Dato Obtenido De La Consulta En El Elemento <select>
function ApiAutoSelect(Config,DatosJson){
	var x = document.getElementById(Config.Elemento);
	if(x.options != undefined){
		while (x.options.length) {
			x.remove(0);
		}
	}
	x.setAttribute("readonly", "");
	
	if(DatosJson != undefined){
		if(DatosJson[0] != null){
			var keys = Object.keys(DatosJson);
			//console.log(DatosJson);
			//console.log(DatosJson.length);
			//console.log(keys);
			
			for(var i=0;i<DatosJson.length;i++){
				var keys = Object.keys(DatosJson[i]);
				if(keys.length != 2){ 
					//Select Solo Puede Tener 2 Columnas Resultantes
					return;
				}
			}
			
			var Select = document.getElementById(Config.Elemento);
			var option = document.createElement("option");
			option.value = "0";
			option.text = "Seleccione";
			Select.add(option);
			for(var i=0;i<DatosJson.length;i++){
				var keys = Object.keys(DatosJson[i]);
				//console.log(keys);
				//console.log(DatosJson[i][keys[0]]);
				var ValueArrayd = DatosJson[i][keys[0]];
				var TextArrayd = DatosJson[i][keys[1]];
				var OpcionArrayd = document.createElement("option");
				OpcionArrayd.value = ValueArrayd;
				OpcionArrayd.text = TextArrayd;
				Select.add(OpcionArrayd);
			}
			Select.removeAttribute("readonly", "");
		}else{
			
		}
	}
}

function AddCustomSelect(Config,DatosJson){
	var jsonResultado = DatosJson;
	//console.log(jsonResultado);
	var filas = jsonResultado.length;
	var keys = [];
	for(var i=0;i<filas;i++){
		for(var k in jsonResultado[i]){
			if(keys.indexOf(k) == -1){
				keys.push(k);
			}
		}
	}
	//console.log(keys);
	var Arrayd2D = new Array();
	Arrayd2D.push(keys);
	for(var i = 0 ; i < filas ; i++ ){
		var Arrayd1D = new Array();
		for(var j = 0 ; j < keys.length ; j++ ){
			Arrayd1D[j] = "" + jsonResultado[i][keys[j]];
		}
		Arrayd2D.push(Arrayd1D);
	}
	//console.log(Arrayd2D);
	var ObjJSON = JSON.parse(JSON.stringify(Arrayd2D));
	Config.Resultado = ObjJSON;
	Config.DivContenedor = Config.Elemento;
	//console.log(Arrayd2D);
	var UL = document.getElementById(Config.Elemento);
	for(var i = 1;i<Arrayd2D.length;i++){
		//Config.
		var LI = document.createElement("LI");
		LI.setAttribute("role", "option");
		LI.setAttribute("tabindex", "-1");
		
		var STRONGTITULOS = document.createElement("strong");
		STRONGTITULOS.setAttribute("style", "font-size: 2em;");
		var STRONGCONTENIDO = document.createElement("strong");
		STRONGCONTENIDO.setAttribute("style","font-size: 12px;color: darkgray;");
		STRONGCONTENIDO.setAttribute("hidden","");
		STRONGCONTENIDO.className = "data";
		STRONGTITULOS.innerHTML = Arrayd2D[i][0];
		STRONGCONTENIDO.innerHTML = Arrayd2D[i][1];//'\"' + Arrayd2D[i][1] + '\"'
		LI.appendChild(STRONGTITULOS);
		LI.appendChild(STRONGCONTENIDO);
		UL.appendChild(LI);
	}
	
	var DivDeEscondibles = document.getElementById("PanelDeEscondibles");
		console.log(DivDeEscondibles);
	if(DivDeEscondibles != undefined){
		for(var i = 1;i<Arrayd2D.length;i++){
			//Config.
			var DivEscondibles = document.createElement("div");
			DivEscondibles.className = "col-md-3 Escondibles";
			DivEscondibles.setAttribute("style","display:none");
			
			var Link = document.createElement("A");
			Link.href = Boveda+"/storage/app/Formularios/"+UserId+"/"+Arrayd2D[i][0]+".xlsx";
			Link.setAttribute("download", "");
			Link.appendChild(document.createTextNode("Plantilla Para Este Formulario"));
			//Plantilla Para Este Formulario
			
			var IMG = document.createElement("IMG");
			IMG.src = "../xlsx-512.png";
			IMG.alt = "Documento Base";
			IMG.setAttribute("style","width: 52px;");
			
			Link.appendChild(IMG);
			DivEscondibles.appendChild(Link);
			DivDeEscondibles.appendChild(DivEscondibles);
		}
	}else{
	}
	if(typeof customSelect === "function"){
		if(document.getElementById("myCustomSelect") != undefined){
			customSelect('#myCustomSelect', {statusSelector: '#custom-select-status'});
		}
	}
	
}



//Coloca El Dato Obtenido De La Consulta En El Elemento <div> Contenedor De Una Tabla
function ApiAutoTabla(Config,DatosJson){
	//console.log(DatosJson);
	var jsonResultado = DatosJson;
	var filas = jsonResultado.length;
	//console.log(filas);
	var keys = [];
	for(var i=0;i<filas;i++){
		for(var k in jsonResultado[i]){
			if(keys.indexOf(k) == -1){
				keys.push(k);
			}
		}
	}
	//console.log(keys);
	var Arrayd2D = new Array();
	Arrayd2D.push(keys);
	for(var i = 0 ; i < filas ; i++ ){
		var Arrayd1D = new Array();
		for(var j = 0 ; j < keys.length ; j++ ){
			Arrayd1D[j] = "" + jsonResultado[i][keys[j]];
		}
		Arrayd2D.push(Arrayd1D);
	}
	//console.log(Arrayd2D);
	var ObjJSON = JSON.parse(JSON.stringify(Arrayd2D));
	//console.log(ObjJSON);
	Config.ConFiltro = true;
	Config.Resultado = ObjJSON;
	Config.DivContenedor = Config.Elemento;
	//console.log(Config);
	FormatearDatosParaTabla(Config);
	//FlameSelectJqueryAderirOpciones(Resultado,Config);
}

//Crea Barra De Carga Antes De Ejecutar El Pedido A La Api
function CrearBarraDeCarga(Config){
	var loading = document.getElementById("loading");
	var div = document.createElement("div");
	div.className="progress";
	div.setAttribute("style","height: 10px; margin-bottom: 2px;");
	var divLoading = document.createElement("div");
	divLoading.id="BarraDeCarga_"+Config.Elemento;
	divLoading.className="progress-bar progress-bar-striped active";
	divLoading.setAttribute("role","progressbar");
	divLoading.setAttribute("aria-valuenow", "1");
	divLoading.setAttribute("aria-valuemin", "0");
	divLoading.setAttribute("aria-valuemax", "100");
	divLoading.setAttribute("style", "width:1%");
	divLoading.setAttribute("style","height: 10px; margin-bottom: 2px; background-color: #0068a9;");
	div.appendChild(divLoading);
	loading.appendChild(div);
}

//Completa La Barra De Carga Tras Obtener Respuesta De Api
function CompletarBarraDeCarga(Config){
	percentComplete = parseInt( (100), 10);
	$('#BarraDeCarga_'+Config.Elemento).data("aria-valuenow",percentComplete);
	$('#BarraDeCarga_'+Config.Elemento).css("width",percentComplete+'%');
}

//Envia Datos A Una Api Con Los Valores En Config Y Genera 
function ElementoDesdeApi(Config){//Funcion De Auto Completado De Elemento Genericos
	Loading();
	if(Config.ValoresDirectos != null){
		var jsonValoresDirectos = JsonElementosAJsonValores(Config.ValoresDirectos);
		var PostData = {};
		//console.log(jsonValoresDirectos);
		//console.log(Config.DataAjax);
		PostData = jsonConcat(PostData, JSON.parse(jsonValoresDirectos));
		PostData = jsonConcat(PostData, JSON.parse(Config.DataAjax));
	}else{
		var PostData = {};
		PostData = jsonConcat(PostData, JSON.parse(Config.DataAjax));
	}
	CrearBarraDeCarga(Config)
	localStorage.setItem('token', 'Bearer abcd');
	
	$.ajax({
		type:"POST",
		url:Config.Ajax,
		data: JSON.stringify(PostData),
		contentType: "application/json; charset=utf-8",
		headers: {'Authorization': localStorage.getItem('token')},
		success:function(Resultado){
			CompletarBarraDeCarga(Config);
			if(Config.Elemento!=undefined){
				if(Config.Elemento!=''){
					var TipoDeElemento = document.getElementById(Config.Elemento).tagName;
				}else{
					var TipoDeElemento = 'GROWL';
				}
			}else{
				var TipoDeElemento = 'GROWL';
			}
			var RespuestaAElemento = Resultado['Respuesta'];
			/*
			console.log(RespuestaAElemento);
			console.log(Config.Ajax);
			*/
			if(TipoDeElemento!=undefined){
				switch(TipoDeElemento){
					case 'P':
						ApiAutoParrafo(Config,RespuestaAElemento['Datos']);
					break;
					case 'INPUT':
						ApiAutoInput(Config,RespuestaAElemento['Datos']);
					break;
					case 'GROWL':
						ApiAutoGrowl(Config,RespuestaAElemento['Datos']);
					break;
					case 'SELECT':
						ApiAutoSelect(Config,RespuestaAElemento['Datos']);
					break;
					case 'DIV':
						ApiAutoTabla(Config,RespuestaAElemento['Datos']);
					break;
					case 'UL':
						if(document.getElementById(Config.Elemento).id == 'custom-select-list'){
							AddCustomSelect(Config,RespuestaAElemento['Datos']);
						}
					break;
					
					default:
						console.log("El Elemento No Se Definio");
						console.log(RespuestaAElemento['Elemento']);
					break;
				}
			}else{
				console.log("Elemento No Definido");
			}
			//console.log(RespuestaAElemento);
			setTimeout(function() {EndLoading();},500);
			if(Config.Reload){
			    if(Config.NewURL != '' || Config.NewURL != null){
			        if(Config.NewURL == 'back'){
			            window.history.back();
			        }
			        setTimeout(function() {
			            //location.replace(Config.NewURL);
			            window.location.href = Config.NewURL;
			        },3000);
			    }else{
				    setTimeout(function() {location.reload();},1000);
			    }
			}
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			CompletarBarraDeCarga(Config);
			setTimeout(function() {EndLoading();},500);
			switch  (XMLHttpRequest.status){
				case 500:
					FlameGrowlFail("Ocurrio Un Error Del Lado Del Servidor");
				break;
				case 404:
					FlameGrowlFail("<p>No Se Encontro Respuesta</p><p><b>Verifique Su Coneccion A Interner E Intente Nuevamente</b></p>");
				break;
				case 401:
					FlameGrowlFail("<p>Su Cuenta Caduco.</p><p>Salga Para Reiniciar Sus Credenciales</p> <nav class='navbar navbar-expand-lg btn-outline-secondary border-bottom mr-auto'><a href='/clienteflash/' class='btn mx-1 my-1 px-1 py-1 align-middle' style='color: yellow;'> <b style='font-size: small;color: yellow;'>Salir</b><i class='fas fa-sign-out-alt'></i></a></nav>");
				break;
				default:
					var Msj = XMLHttpRequest.responseJSON['Msj'];
					if(Config.MensajeEnFail){
						if(Config.TextoEnFail!=""){
							FlameGrowlFail(Config.TextoEnFail);
						}else{
							if(Msj!=null){
								FlameGrowlFail(Msj);
							}else{
								//Agregar Numeros De Errores Que Surjan Sin Mensajes De Retorno Para Definie Mensajes Fijos
								//500, 404, 
								console.log(XMLHttpRequest);
								console.log(textStatus);
								console.log(errorThrown);
							}
						}
					}
				break;
			}
		}
	})
}