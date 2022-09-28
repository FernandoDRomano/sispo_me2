//Como LLamar A LA Funcion De AutoCompletado De Elementos Select

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/*
	
	//Estos Elementos Son Fijos, Ejemplo Id De Logueo
	filtro=["UserId"];
	filtroX=["1"];
	var Parametros = ArraydsAJson(filtro,filtroX);
	Parametros = JSON.stringify(Parametros);// Manda Como Texto

	//Estos Elementos Son Vaiables, Se Pasa Id Para Enviar A Ajax PHP E Id De Los Elementos Para Sacar Los Valores En El Momento De Correo El Completado De Los Datos
	var Indices=["FechaDesde","FechaHasta"];
	var Objetos = ["FechaDesde","FechaHasta"];
	var ValoresDirectos = ArraydsAJson(Indices,Objetos);//Manda Como Objeto En SelectDesdeConsulta Se Transforma En Terxto

	//Los Valores Se Mandan En Un ObjetoJS Como Se Muestra.
	var Config = JSON.parse(`
	{
		"Elemento":"TarjetasIngresadas",//Id De Elemento A Completar
		"DataAjax":` + Parametros + `,//Parametros Fijos Que Forman Parte De Los Valores Mandados A Ajax PHP Para La Consulta
		"ValoresDirectos":` + ValoresDirectos + `,//Parametros Variables Que Forman Parte De Los Valores Mandados A Ajax PHP Para La Consulta
		"MensajeEnFail":"true",//Configuracion De Mensaje De Error En Caso De No Encontrar Datos Tras Realizar La Consulta
		"TextoEnFail":"No Se Encontraron Resultados",//Texto A Mostrar Como Msj De Error En Caso De No Encontrar Datos Tras Realizar La Consulta
		"ValorDefault":"0",//valor Por Defecto Si No Se Encuentran Resultados
		"Ajax":"` + URLJS + `/api/flame/Testvalorunico/Ajaxvalorunico.php"//URL De Archivo AjaxPHP Que Se Ejecuta Para Obtener Datos Para Select.
	}`);
	//"ValoresDirectos":null,//Si No Se Requieren Valores Variables Mandamos null 
	//"ValoresDirectos":` + ValoresDirectos + `,//Si Se Requieren Valores Variables Mandamos Con Este Formato 
	ElementoDesdeApi(Config);
*/
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function ValorDesdeConsulta(Config){//Funcion De Auto Completado De Elemento Genericos
	var x = document.getElementById(Config.Elemento);
	if(x == null){
		if(typeof $.bootstrapGrowl === "function") {
			$.bootstrapGrowl("El Elemento No Existe",{
				type: 'danger',
				align: 'center',
				width: 'auto'
			});
		}
		return;
	}
	Loading();
	if(Config.ValoresDirectos != null){
		var jsonValoresDirectos = JsonElementosAJsonValores(Config.ValoresDirectos);//JsonElementosAJsonValores FlameBase
		var PostData = {};
		//console.log( JSON.parse(jsonValoresDirectos));
		//console.log( JSON.parse(Config.DataAjax));
		PostData = jsonConcat(PostData, JSON.parse(jsonValoresDirectos));//jsonConcat FlameBase
		PostData = jsonConcat(PostData, JSON.parse(Config.DataAjax));//jsonConcat FlameBase
		//console.log(jsonValoresDirectos);
	}else{
		var PostData = {};
		PostData = jsonConcat(PostData, JSON.parse(Config.DataAjax));
		//console.log(PostData);
	}
	
	var loading = document.getElementById("loading");
	var div = document.createElement("div");
	div.className="progress";
	div.setAttribute("style","height: 10px; margin-bottom: 2px;");
	var divLoading = document.createElement("div");
	divLoading.id="BarraDeCarga_"+Config.Elemento;
	//alert(divLoading.id);
	divLoading.className="progress-bar progress-bar-striped active";
	divLoading.setAttribute("role","progressbar");
	divLoading.setAttribute("aria-valuenow", "1");
	divLoading.setAttribute("aria-valuemin", "0");
	divLoading.setAttribute("aria-valuemax", "100");
	divLoading.setAttribute("style", "width:1%");
	divLoading.setAttribute("style","height: 10px; margin-bottom: 2px; background-color: #0068a9;");
	div.appendChild(divLoading);
	loading.appendChild(div);
	
	$.ajax({
		type:"GET",
		url:Config.Ajax,
		data: PostData,
		success:function(Resultado){
			percentComplete = parseInt( (100), 10);
			$('#BarraDeCarga_'+Config.Elemento).data("aria-valuenow",percentComplete);
			$('#BarraDeCarga_'+Config.Elemento).css("width",percentComplete+'%');
			var Resultado = Resultado.trim();
			if(Resultado=="NULL" || Resultado==""){
				if(Config.MensajeEnFail){
					if(typeof $.bootstrapGrowl === "function") {
						$.bootstrapGrowl(Config.TextoEnFail,{
							type: 'danger',
							align: 'center',
							width: 'auto'
						});
					}
				}
				var Elemento = document.getElementById(Config.Elemento);
				Elemento.innerHTML = Config.ValorDefault;
				Elemento.value = Config.ValorDefault;
				EndLoading();
			}else{
				var Elemento = document.getElementById(Config.Elemento);
				Elemento.innerHTML = Resultado;
				Elemento.value = Resultado;
				EndLoading();
			}
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			if (XMLHttpRequest.readyState == 4) {
				if(typeof $.bootstrapGrowl === "function") {
					$.bootstrapGrowl("Error" + XMLHttpRequest.status + " (" + XMLHttpRequest.statusText + ")",{
						type: 'danger',
						align: 'center',
						width: 'auto'
					});
				}
				EndLoading();
			}else{
				if (XMLHttpRequest.readyState == 0) {
					$.ajax(this);
					console.clear();
					return;
				}
				else {
				}
			}
		},
		dataType: "text" // El tipo de datos esperados del servidor. Valor predeterminado: Intelligent Guess (xml, json, script, text, html).
	})
}