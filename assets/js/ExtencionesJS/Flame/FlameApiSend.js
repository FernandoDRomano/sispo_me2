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
	if(DatosJson !== undefined){
		var Elemento = document.getElementById(Config.Elemento);
		if(DatosJson[0] !== null){
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
				//console.log("El Grow No Puede Mostrar El Arrayd De Datos");
				//console.log(DatosJson);
				//console.log(DatosJson.length);
				for( var i = 0 ; i < DatosJson.length ; i++){
					var keys = Object.keys(DatosJson[i]);
					FlameGrowlSuccess(DatosJson[i][keys[0]]);
				}
				
			}else{
				var keys = Object.keys(DatosJson[0]);
				/*
				console.log(DatosJson);
				console.log(DatosJson.length);
				console.log(keys);
				console.log(DatosJson[0][keys[0]]);
				*/
				FlameGrowlSuccess(DatosJson[0][keys[0]]);
			}
		}else{
			console.log(DatosJson);
			//console.log(DatosJson.length);
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
					//console.log("Select Solo Puede Tener 2 Columnas Resultantes Tiene:" + keys.length);
					//console.log(Config);
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
			Link.href = Boveda+"Formularios/"+UserId+"/"+Arrayd2D[i][0]+".xlsx";
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

function isNumeric(str) {
  if (typeof str != "string") return false // we only process strings!  
  return !isNaN(str) && // use type coercion to parse the _entirety_ of the string (`parseFloat` alone does not do this)...
         !isNaN(parseFloat(str)) // ...and ensure strings of whitespace fail
}

function ApiAutoGrafica(Config,DatosJson){
	//var data = DatosJson;
	var jsonResultado = DatosJson;
	//console.log(jsonResultado);
	if(jsonResultado.length==0){
		//GraficaIngresosGenerales
		document.getElementById(Config.Elemento).style.display = 'none';
	}else{
		document.getElementById(Config.Elemento).style.display = 'flex';
		
	}
	//if(){}
	if(Config.Grafica == "Pastel"){
		var filas = jsonResultado.length;
		var keys = [];
		for(var i=0;i<filas;i++){
			for(var k in jsonResultado[i]){
				if(keys.indexOf(k) == -1){
					keys.push(k);
				}
			}
		}
		var ArraydGrafica = new Array();
		for(var i = 0 ; i < filas ; i++ ){
			var Arrayd1D = new Array();
			for(var j = 0 ; j < keys.length ; j++ ){
		        if( isNumeric(jsonResultado[i][keys[j]]) ){
		            jsonResultado[i][keys[j]] = parseFloat(jsonResultado[i][keys[j]]);
		            if(Number.isInteger(parseFloat(jsonResultado[i][keys[j]]))){
		                console.log("Number.isInteger(parseFloat(jsonResultado[i][keys[j]]))");
		                Arrayd1D[j] = parseInt(jsonResultado[i][keys[j]]);
		            }else{
		                console.log("else");
				        Arrayd1D[j] = parseFloat(jsonResultado[i][keys[j]]);
		            }
		        }else{
		            console.log("typeof jsonResultado[i][keys[j]] == 'number'");
		            Arrayd1D[j] = jsonResultado[i][keys[j]];
		        }
		        
		        //console.log(Arrayd1D[j]);
			}
			ArraydGrafica.push(Arrayd1D);
		}
		
		//console.log(ArraydGrafica);
		var data = ArraydGrafica;
		var text = Config.Text;
		var pointFormat = Config.PointFormat;
		var info = keys[1];
		Highcharts.chart(Config.Elemento, {
			chart: {
				type: 'pie',
				options3d: {
					enabled: true,
					alpha: 30
				}
			},
			title: {
				text: text
			},
			subtitle: {
				text: text
			},
			accessibility: {
				point: {
					valueSuffix: '%'
				}
			},
			plotOptions: {
				pie: {
					innerSize: 30,
					depth: 100,
					showInLegend: true,
					allowPointSelect: true,
					cursor: 'pointer',
					dataLabels: {
						enabled: true,
						format: '{point.name}'
					}
				}
			},
			tooltip: {
				pointFormat: pointFormat,
			},
			series: [{
				name: info,
				data: data
			}]
		});
	}
	
	if(Config.Grafica == "PastelGigante"){
		var data = jsonResultado;
		var text = Config.Text;
		var pointFormat = Config.PointFormat;
		var filas = data.length;
		for(var i = 0 ; i < filas ; i++ ){
			/*
			if(typeof data[i].value == 'number'){
			}
			*/
			if(data[i].value == ""){
				delete data[i]["value"];
				data[i].Porcentaje = parseFloat(data[i].Porcentaje);
			}else{
				data[i].value = parseFloat(data[i].value);
				data[i].Porcentaje = parseFloat(data[i].Porcentaje);
				
			}
		}
		//console.log(data);
		Highcharts.chart(Config.Elemento, {
			chart: {
				height: '100%'
			},
			colors: ['transparent'].concat(Highcharts.getOptions().colors),
			title: {
				text: text
			},
			series: [{
				type: 'sunburst',
				data: data,
				name: 'Root',
				allowDrillToNode: true,
				cursor: 'pointer',
				dataLabels: {
					format: '{point.name}',
					filter: {
						property: 'innerArcLength',
						operator: '>',
						value: 1
					},
					rotationMode: 'circular'
				},
				levels: [{
					level: 1,
					levelIsConstant: false,
					dataLabels: {
						filter: {
							property: 'outerArcLength',
							operator: '>',
							value: 1
						}
					}
				}, {
					level: 2,
					colorByPoint: true
				}, {
					level: 3,
					colorByPoint: true
				},
				{
					level: 4,
					colorVariation: {
						key: 'brightness',
						to: -0.5
					}
				}]

			}],
			tooltip: {
				headerFormat: '',
				pointFormat: pointFormat
			}
		});
	}
}


//Coloca El Dato Obtenido De La Consulta En El Elemento <div> Contenedor De Una Tabla
function ApiAutoTabla(Config,DatosJson){
	//console.log(DatosJson);
	var jsonResultado = DatosJson;
	var filas = jsonResultado.length;
	//console.log(jsonResultado);
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
	Config.Resultado = ObjJSON;
	//console.log(Config.Resultado);
	Config.DivContenedor = Config.Elemento;
	//console.log(Config);
	
	//Config.ConFiltro = true;
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
function CompletarBarraDeCarga(Config,percentComplete = parseInt( (100), 0)){
	//percentComplete = parseInt( (100), 10);
	$('#BarraDeCarga_'+Config.Elemento).data("aria-valuenow",percentComplete);
	$('#BarraDeCarga_'+Config.Elemento).css("width",percentComplete+'%');
}
//var numerodefuncionesdetesteo=0;
//Envia Datos A Una Api Con Los Valores En Config Y Genera 
function ElementoDesdeApi(Config){//Funcion De Auto Completado De Elemento Genericos
	Loading();
	if(Config.ValoresDirectos != null){
		var jsonValoresDirectos = JsonElementosAJsonValores(Config.ValoresDirectos);
		var PostData = {_token: CSRF_TOKEN};
		//console.log(jsonValoresDirectos);
		//console.log(Config.DataAjax);
		PostData = jsonConcat(PostData, JSON.parse(jsonValoresDirectos));
		PostData = jsonConcat(PostData, JSON.parse(Config.DataAjax));
	}else{
		var PostData = {_token: CSRF_TOKEN};
		PostData = jsonConcat(PostData, JSON.parse(Config.DataAjax));
	}
	CrearBarraDeCarga(Config)
	localStorage.setItem('token', 'Bearer '+ api_token);
	
	
	////////////////////////////////////////////////////////////////////////////////////////////
	/*
	if(numerodefuncionesdetesteo==0){
		//var objs = Object.getOwnPropertyNames(ElementoDesdeApi);
		//
		//console.log(ElementoDesdeApi.length);
		//console.log(ElementoDesdeApi.name);
		//
		//console.log(ElementoDesdeApi.arguments);
		//
		//console.log(ElementoDesdeApi.caller);
		//console.log(ElementoDesdeApi.prototype);
		//
		//
		//for(var i in objs ){
		//  console.log(objs[i]);
		//}
		//
		//numerodefuncionesdetesteo++;
	}
	*/
	//////////////////////////////////////////////////////////////////////////////////////////
	/*
	var objs = Object.getOwnPropertyNames(arguments.callee.name);
	//console.log(ElementoDesdeApi.arguments);
	//console.log(arguments.callee.name);
	if(ElementoDesdeApi.arguments.length>0){
			console.log(ElementoDesdeApi.arguments[0]["Elemento"]);
		if(ElementoDesdeApi.arguments["Elemento"] != undefined){
		}
	}
	*/
	
	//Agregado para sispo
	if (typeof Config.Sispo !== 'undefined') {
        var form_data = new FormData();
        var item = PostData;
        for ( var key in item ) {
            form_data.append(key, item[key]);
        }
	    var ContentType = false;
	}else{
	    var form_data = JSON.stringify(PostData);
	    var ContentType = "application/json; charset=utf-8";
	}
    
	
	$.ajax({
		xhr: function() {
            var xhr = new window.XMLHttpRequest();
            // Upload progress
            xhr.upload.addEventListener("progress", function(evt){
                if (evt.lengthComputable) {
                    var percentComplete = (evt.loaded / evt.total) * 100;
                    var load = document.getElementById('loadingText');
                    var formattedNumber = (percentComplete.toString()).slice(0,5);
                    load.textContent="Enviando Datos " + formattedNumber + "%";
                    CompletarBarraDeCarga(Config,formattedNumber);
                    //Do something with upload progress
                    //console.log(percentComplete);
                    if(formattedNumber == 100){
                        load.textContent="Realizando Trabajo";
                        CompletarBarraDeCarga(Config,0);
                    }
                }
           }, false);
    
           // Download progress
           xhr.addEventListener("progress", function(evt){
               //console.log(evt);
               if (evt.lengthComputable) {
                    var percentComplete = (evt.loaded / evt.total) * 100;
                    // Do something with download progress
                    var load = document.getElementById('loadingText');
                    var formattedNumber = (percentComplete.toString()).slice(0,5);
                    load.textContent="Cargando Respuesta " + formattedNumber + "%";
                    //console.log(percentComplete);
                    CompletarBarraDeCarga(Config,formattedNumber);
               }
           }, false);
           return xhr;
        },
		type:"POST",
		url:Config.Ajax,
		data: form_data,//data: JSON.stringify(PostData),//Agregado para sispo
        datatype:'json',
        processData: false,
        contentType: false,
        cache: false,
		contentType: ContentType,
		headers: {'Authorization': localStorage.getItem('token')},
		success:function(Resultado, textStatus, xhr){
		    
		    //Agregado Para Sispo
            if (typeof Resultado === 'string' || Resultado instanceof String){
		        Resultado = JSON.parse(Resultado);
			    //console.log(Resultado);
            }else{
            }
		    
			
			
			
			console.log(xhr.getResponseHeader('REQUIRES_AUTH'));
			//FlameGrowlFail("<p>" + xhr.status + "</p>");
			CompletarBarraDeCarga(Config);
			//console.log("1");
			if(Config.Elemento!=undefined){
				if(Config.Elemento!=''){
					var TipoDeElemento = document.getElementById(Config.Elemento).tagName;
				}else{
					var TipoDeElemento = 'GROWL';
				}
			}else{
				var TipoDeElemento = 'GROWL';
			}
			//console.log("2");
			//console.log(Resultado);
			//console.log(Config.Ajax);
			var RespuestaAElemento = Resultado['Respuesta'];
			
			//console.log("3");
			if(RespuestaAElemento==undefined){
				//console.log("Resultado['Respuesta'] No Definido");
				//console.log(Resultado);
				if(Config.Ajax.includes("http://sistema.sppflash.com.ar/api/", 0)){
					//console.log("app/Http/Controllers/" + Config.Ajax.replace('http://sistema.sppflash.com.ar/api/', ''));
				}else{
					//console.log(Config.Ajax);
				}
				var dir = window.location.pathname.replace('/SubMenues', '')  ;
				var dir = ''+ 'C:/xampp/htdocs/sistema.sppflash.com.ar/public/js/ExtencionesJS' + dir;
				//console.log(dir);
			    //console.log("4");
				return;
			}else{
				if(RespuestaAElemento['Datos']==undefined){
					//console.log("Resultado['Respuesta']['Datos'] No Definido");
					//console.log(Resultado);
					//console.log(Config.Ajax);
					if(Config.Ajax.includes("http://sistema.sppflash.com.ar/api/", 0)){
						//console.log("app/Http/Controllers/" + Config.Ajax.replace('http://sistema.sppflash.com.ar/api/', ''));
					}else{
						//console.log(Config.Ajax);
					}
			        //console.log("5");
					return;
				}
			}
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
						
						//console.log(Resultado['Respuesta']['Datos']);
					break;
					case 'SELECT':
						ApiAutoSelect(Config,RespuestaAElemento['Datos']);
					break;
					case 'DIV':
						if(document.getElementById(Config.Elemento).className === 'number'){
							ApiAutoParrafo(Config,RespuestaAElemento['Datos']);
						}else{
							if(document.getElementById(Config.Elemento).classList.contains('Grafica-1') || document.getElementById(Config.Elemento).classList.contains('Grafica-2') || document.getElementById(Config.Elemento).classList.contains('Grafica-3') ){
								ApiAutoGrafica(Config,RespuestaAElemento['Datos']);
							}else{
								//console.log(document.getElementById(Config.Elemento).className)
								ApiAutoTabla(Config,RespuestaAElemento['Datos']);
							}
						}
					break;
					case 'UL':
						if(document.getElementById(Config.Elemento).id == 'custom-select-list'){
							AddCustomSelect(Config,RespuestaAElemento['Datos']);
						}
					break;
					
					default:
						//console.log("El Elemento No Se Definio");
						//console.log(RespuestaAElemento['Elemento']);
					break;
				}
			}else{
				//console.log("Elemento No Definido");
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
					//console.log(XMLHttpRequest);
					//console.log(textStatus);
					//console.log(errorThrown);
				break;
				case 302:
					FlameGrowlFail("<p>No Se Encontro Respuesta</p><p><b>Validacion De Api Incorrecta.</b></p>");
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
								if(typeof Msj === 'object' && Msj !== null){
									Msj = Msj['data'];
									//console.log(Msj);
									if(typeof Msj === 'object' && Msj !== null){
										for (var value of Msj) {
											//console.log(value['error']);
											FlameGrowlFail(value['error']);
										}
									}
								}else{
									//console.log(Msj);
									FlameGrowlFail(Msj);
								}
							}else{
								//Agregar Numeros De Errores Que Surjan Sin Mensajes De Retorno Para Definie Mensajes Fijos
								//500, 404, 
								//console.log(XMLHttpRequest);
								//console.log(textStatus);
								//console.log(errorThrown);
							}
						}
					}else{
						
					}
				break;
			}
		}
	})
}