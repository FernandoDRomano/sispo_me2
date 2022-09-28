//Como LLamar A LA Funcion De AutoCompletado De Elementos Select

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/*
//Estos Elementos Son Fijos, Ejemplo Id De Logueo
filtro=["UserId"];
filtroX=["1"];
var Parametros = ArraydsAJson(filtro,filtroX);
Parametros = JSON.stringify(Parametros);

//Estos Elementos Son Vaiables, Se Pasa Id Para Enviar A Ajax PHP E Id De Los Elementos Para Sacar Los Valores En El Momento De Correo El Completado De Los Datos
var Indices=["FechaI","FechaF"];
var Objetos = ["FechaI","FechaF"];
var ValoresDirectos = ArraydsAJson(Indices,Objetos);//Manda Como Objeto En SelectDesdeConsulta Se Transforma En Terxto

//Los Valores Se Mandan En Un ObjetoJS Como Se Muestra.
	"Elemento":"Select1",//Nombre Del Elemento Select, Que Sera El Que Se Rellenara.
	"DataAjax":` + Parametros + `,//Parametros Fijos Que Forman Parte De Los Valores Mandados A Ajax PHP Para La Consulta
	"ValoresDirectos":` + ValoresDirectos + `,//Parametros Variables Que Forman Parte De Los Valores Mandados A Ajax PHP Para La Consulta
	"MensajeEnFail":"true",//Configuracion De Mensaje De Error En Caso De No Encontrar Datos Tras Realizar La Consulta
	"TextoEnFail":"No Se Encontraron Resultados Para Seleccionar",//Texto A Mostrar Como Msj De Error En Caso De No Encontrar Datos Tras Realizar La Consulta
	"Ajax":"` + URLJS + `/api/flame/Testselect/AjaxArraidToSelect.php"//URL De Archivo AjaxPHP Que Se Ejecuta Para Obtener Datos Para Select.
}`);
//"ValoresDirectos":null,//Si No Se Requieren Valores Variables Mandamos null 
//"ValoresDirectos":` + ValoresDirectos + `,//Si Se Requieren Valores Variables Mandamos Con Este Formato 
ElementoDesdeApi(Config);
*/
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function SelectDesdeConsulta(Config){//Funcion De Auto Completado De Elemento Select
	var x = document.getElementById(Config.SelectId);
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
		//console.log(PostData);
	}else{
		var PostData = {};
		PostData = jsonConcat(PostData, JSON.parse(Config.DataAjax));
		//console.log(PostData);
	}
	$.ajax({
		type:"GET",
		url:Config.Ajax,
		data: PostData,
		success:function(Resultado){
			//console.log(Resultado);
			
			var Resultado = Resultado.trim();
			if(Resultado=="NULL" || Resultado=="" || ( Resultado.indexOf("Error:") == 0 ) ){
				LimpiarSelect(Config);
				if(Config.MensajeEnFail){
					if(typeof $.bootstrapGrowl === "function") {
						$.bootstrapGrowl(Config.TextoEnFail,{
							type: 'danger',
							align: 'center',
							width: 'auto'
						});
					}
				}
				if((Resultado.indexOf("Error:") == 0) ){
					//alert(Resultado.indexOf("Error:"));
					Resultado = Resultado.replace("Error:", "");
					//alert(Resultado);
					if(typeof $.bootstrapGrowl === "function") {
						$.bootstrapGrowl(Resultado,{
							type: 'danger',
							align: 'center',
							width: 'auto'
						});
					}
				}
				EndLoading();
			}else{
				AderirOpcionesDeSelectDesdeArrayd(StringAArrayd2d(Resultado),Config);
				EndLoading();
			}
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			if (XMLHttpRequest.readyState == 4) {
				if(typeof $.bootstrapGrowl === "function") {
					$.bootstrapGrowl("Error-" + XMLHttpRequest.status + " (" + XMLHttpRequest.statusText + ")",{
						type: 'danger',
						align: 'center',
						width: 'auto'
					});
				}
				EndLoading();
			}else{
				if (XMLHttpRequest.readyState == 0) {
					
					if(typeof $.bootstrapGrowl === "function") {
						$.bootstrapGrowl("Sin Coneccion A Internet Reintentando Conectar",{
							type: 'danger',
							align: 'center',
							width: 'auto'
						});
					}
					
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
function StringAArrayd2d(String){
	if(String!=false && String!=undefined){
		var arrayd1d = String.split(";");
		var Arrayd2d = new Array();
		for(var i=0;i<arrayd1d.length;i++){
			Arrayd2d[i] = arrayd1d[i].split("|");
		}
		return(Arrayd2d);
	}
}
function LimpiarSelect(Config) {
	var x = document.getElementById(Config.SelectId);
	if(x.options != undefined){
		while (x.options.length) {
			x.remove(0);
		}
	}
	x.setAttribute("readonly", "");
}
function AderirOpcionesDeSelectDesdeArrayd(Arrayd2d,Config){
	LimpiarSelect(Config);
	if(Arrayd2d != undefined){
		for(var i=0;i<Arrayd2d.length;i++){
			if(Arrayd2d[i].length != 2){
				if(Config.MensajeEnFail){
					if(typeof $.bootstrapGrowl === "function") {
						$.bootstrapGrowl(Config.TextoEnFail,{
							type: 'danger',
							align: 'center',
							width: 'auto'
						});
					}
				}
				return;
			}
		}
		var Select = document.getElementById(Config.SelectId);
		var option = document.createElement("option");
		option.value = "0";
		option.text = "Seleccione";
		Select.add(option);
		for(var i=0;i<Arrayd2d.length;i++){
			var ValueArrayd = Arrayd2d[i][0];
			var TextArrayd = Arrayd2d[i][1];
			var OpcionArrayd = document.createElement("option");
			OpcionArrayd.value = ValueArrayd;
			OpcionArrayd.text = TextArrayd;
			Select.add(OpcionArrayd);
		}
		Select.removeAttribute("readonly", "");
	}
}






function SelectBovedaDesdeConsulta(Config){//Funcion De Auto Completado De Elemento Select
	var x = document.getElementById(Config.SelectId);
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
		//console.log(PostData);
	}else{
		var PostData = {};
		PostData = jsonConcat(PostData, JSON.parse(Config.DataAjax));
		//console.log(PostData);
	}
	$.ajax({
		type:"POST",
		url:Config.Ajax,
		data: PostData,
		contentType : 'application/json',
		success:function(Resultado){
			
			/*
			console.log("->");
			console.log(Resultado);
			console.log("->");
			*/
			var Resultado = Resultado.trim();
			if(Resultado=="NULL" || Resultado=="" || ( Resultado.indexOf("Error:") == 0 ) ){
				LimpiarSelect(Config);
				if(Config.MensajeEnFail){
					if(typeof $.bootstrapGrowl === "function") {
						$.bootstrapGrowl(Config.TextoEnFail,{
							type: 'danger',
							align: 'center',
							width: 'auto'
						});
					}
				}
				if((Resultado.indexOf("Error:") == 0) ){
					//alert(Resultado.indexOf("Error:"));
					Resultado = Resultado.replace("Error:", "");
					//alert(Resultado);
					if(typeof $.bootstrapGrowl === "function") {
						$.bootstrapGrowl(Resultado,{
							type: 'danger',
							align: 'center',
							width: 'auto'
						});
					}
				}
				EndLoading();
			}else{
				AderirOpcionesDeSelectDesdeArrayd(StringAArrayd2d(Resultado),Config);
				EndLoading();
			}
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			console.log(XMLHttpRequest);
			
			if (XMLHttpRequest.readyState == 4) {
				if(typeof $.bootstrapGrowl === "function") {
					$.bootstrapGrowl("Error-" + XMLHttpRequest.status + " (" + XMLHttpRequest.statusText + ")",{
						type: 'danger',
						align: 'center',
						width: 'auto'
					});
				}
				EndLoading();
			}else{
				if (XMLHttpRequest.readyState == 0) {
					
					if(typeof $.bootstrapGrowl === "function") {
						$.bootstrapGrowl("Sin Coneccion A Internet Reintentando Conectar",{
							type: 'danger',
							align: 'center',
							width: 'auto'
						});
					}
					
					$.ajax(this);
					console.clear();
					return;
				}
				else {
				}
			}
		},
		//dataType: "json" // El tipo de datos esperados del servidor. Valor predeterminado: Intelligent Guess (xml, json, script, text, html).
	})
}









