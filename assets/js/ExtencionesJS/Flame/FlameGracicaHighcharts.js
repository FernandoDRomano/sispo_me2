//Como LLamar A LA Funcion De AutoCompletado De Graficos

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/*
//Estos Elementos Son Fijos, Ejemplo Id De Logueo
filtro=["User","time"];
filtroX=["1","0"];
var Parametros = ArraydsAJson(filtro,filtroX);
Parametros = JSON.stringify(Parametros);// Manda Como Texto

//Estos Elementos Son Vaiables, Se Pasa Id Para Enviar A Ajax PHP E Id De Los Elementos Para Sacar Los Valores En El Momento De Correo El Completado De Los Datos
var Indices=["FechaDesde","FechaHasta"];
var Objetos = ["FechaDesde","FechaHasta"];
var ValoresDirectos = ArraydsAJson(Indices,Objetos);//Manda Como Objeto En SelectDesdeConsulta Se Transforma En Terxto

//Los Valores Se Mandan En Un ObjetoJS Como Se Muestra.
var Config = JSON.parse(`
{
	"DivDeGrafica":"GraficaGestion",//Nombre Del Elemento Div, Que Sera El Que Se Rellenara.
	"DataAjax":` + Parametros + `,//Parametros Fijos Que Forman Parte De Los Valores Mandados A Ajax PHP Para La Consulta
	"ValoresDirectos":` + ValoresDirectos + `,//Parametros Variables Que Forman Parte De Los Valores Mandados A Ajax PHP Para La Consulta
	"MensajeEnFail":"true",//Configuracion De Mensaje De Error En Caso De No Encontrar Datos Tras Realizar La Consulta
	"TextoEnFail":"No Se Encontraron Resultados Para Graficar","No Se Encontraron Resultados Para Graficar",//Texto A Mostrar Como Msj De Error En Caso De No Encontrar Datos Tras Realizar La Consulta
	
	"PronombraData":"%,%,%",//Caracter O Texto Que Acompañara A Los Valores De Las Graficas Ejemplo $,%,Dolares,Pesos Arg
	"typeData":"column,column,column",//Tipo De Grafica column = Columnas , spline = Lineas
	"ColorData":"#2143a2,#4C8219,#000000",//Color De Linea O Columna
	"NombresDeVariables":"Tarjetas En Gestion,Tarjetas Entregadas,Solicitadas Por El Cliente",//Nombres De Elementos En Las Graficas
	"Titulo":"Grafica Porcentual De Estados De Piezas Por Fecha",//Titulo Superior
	"SubTituloLateral":"Porcentaje De Estados/Ingresadas)",//Subtitulo Lateral
	"Ymin":"100",// Tamaño Minimo De Eje y
	"Ymax":"0",// Tamaño Maximo De Eje y
	"SubTitulo":"Automatico",//Subtitulo Automatico = Calcula Fechas min y Max 
	
	"Ajax":"` + URLJS + `/api/flame/Tablerodegestiones/AjaxGetFullData.php"
}`);
//"ValoresDirectos":null,//Si No Se Requieren Valores Variables Mandamos null 
//"ValoresDirectos":` + ValoresDirectos + `,//Si Se Requieren Valores Variables Mandamos Con Este Formato 
GraficaDesdeConsulta(Config);

*/
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


//Inicio Flame Grafica
function GraficaDesdeConsulta(Config){
	var x = document.getElementById(Config.DivDeGrafica);
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
			var Resultado = Resultado.trim();
			if(Resultado=="NULL" || Resultado==""){
				LimpiarGrafica(Config);
				if(Config.MensajeEnFail){
					if(typeof $.bootstrapGrowl === "function") {
						$.bootstrapGrowl(Config.TextoEnFail,{
							type: 'danger',
							align: 'center',
							width: 'auto'
						});
					}
				}
				EndLoading();
			}else{
				
				var Arrayd1D = Resultado.split(";");
				var Arrayd2D = new Array();
				for(var i = 0 ; i < Arrayd1D.length ; i++ ){
					Arrayd2D[i] = Arrayd1D[i].split("|");
				}
				var ObjJSON = JSON.parse(JSON.stringify(Arrayd2D));
				Config.Resultado = ObjJSON;
				FormatearDatosParaGrafica(Config);
				//GraficaAsyncrona(Config);
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
					/*
					if(typeof $.bootstrapGrowl === "function") {
						$.bootstrapGrowl("Sin Coneccion A Internet Reintentando Conectar",{
							type: 'danger',
							align: 'center',
							width: 'auto'
						});
					}
					*/
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
//Fin Flame Grafica


function Grafica(Contenedor,Titulo,SubTitulo,Ynombre,ArraydX,Ymin,Ymax,typeData,NombresDeVariables,PronombraData,PuntoData,ColorData){
	var chart = Highcharts.chart(Contenedor, {
		title: {
			text: Titulo
		},
		subtitle: {
			text: SubTitulo
		},
		yAxis: {
			title: {text: Ynombre},
			baseline: 1,
			tickInterval: 1,
			min: Ymin,
			startOnTick: false,
			endOnTick: false,
		},
		legend: {
			layout: 'horizontal',
		},
		plotOptions: {
			series: {
				label: {
					connectorAllowed: false
				},
				pointStart: 0
			}
		},
		xAxis: {
			categories: ArraydX,
			tickInterval: 1,
			showEmpty: false
		},
	});
	chart.name = false;
	var enableDataLabels = true,
	enableMarkers = true,
	color = false;
	var d = new Date();
	var t=d.getTime();
	enableMarkers = !enableMarkers;
	var ArraydNombresDeVariables = NombresDeVariables.split(",");
	var ArraydPronombraData = PronombraData.split(",");
	var ArraydtypeData = typeData.split(",");
	var ArraydColorData = ColorData.split(",");
	for (var cont=0;cont<ArraydNombresDeVariables.length;cont++){
		chart.addSeries({name: ArraydNombresDeVariables[cont]});
		chart.series[cont].update({type: ArraydtypeData[cont]});
		chart.series[cont].update({allowPointSelect: 'false'});
		chart.series[cont].update({tooltip: {valueSuffix: ArraydPronombraData[cont]}});
	}
	var XYPuntoData = [];
	XYPuntoData = PuntoData
	for(var cont=0;cont<XYPuntoData.length;cont++){
		for (var cont2=0;cont2<ArraydNombresDeVariables.length;cont2++){
			chart.series[cont2].addPoint( [ parseInt(XYPuntoData[cont][0])-1 , parseFloat(XYPuntoData[cont][cont2+1]) ] );
		}
	}
	for (var cont=0;cont<ArraydColorData.length;cont++){
		chart.series[cont].update({color: ArraydColorData[cont]});
	}
}

function LimpiarGrafica(Config){
	var DivGrafica = document.getElementById(Config.DivDeGrafica);
	while (DivGrafica.firstChild) {
		DivGrafica.removeChild(DivGrafica.firstChild);
	}
}



function FormatearDatosParaGrafica(Config){
	//console.log("GraficaAsyncrona: Formato De objeto js Requerido");
	//console.log(Config);
	//console.log("Valores Solo Requeridos Para Consultas Sin PHP, No Se Requiere En Laravel (DataAjax , ValoresDirectos ,  MensajeEnFail , TextoEnFail , Ajax)");
	NombreDiv = Config.DivDeGrafica;
	DataString = Config.Resultado;
	var value = Config.Resultado;
	PronombraData=Config.PronombraData;
	typeData=Config.typeData;
	ColorData=Config.ColorData;
	NombresDeVariables = Config.NombresDeVariables;
	var Titulo = Config.Titulo;
	var SubTituloLateral = Config.SubTituloLateral;
	var Ymin = Config.Ymin;
	var Ymax = Config.Ymax;
	var SubTitulo = Config.SubTitulo;
	var DivGrafica = document.getElementById(NombreDiv);
	while (DivGrafica.firstChild) {
		DivGrafica.removeChild(DivGrafica.firstChild);
	}
	if(DataString==undefined || DataString=="NULL" || DataString.length<=0){
		$.bootstrapGrowl("Sin Resultados.", {
			type: 'danger',
			align: 'center',
			width: 'auto'
		});
		return;
	}
	for(var i=0;i<value.length;i++){
		if(value[i].length>1){
			for(var j=2;j < value[i].length;j++){
				
				if(Ymin*1 > value[i][j]*1 ){
					Ymin= (value[i][j]*1);
				}
				if(Ymax*1 < value[i][j]*1 ){
					Ymax= value[i][j]*1;
				}
			}
		}
	}
	Ymin=0;
	var ArraydX = [];
	for(var i=0;i<value.length;i++){
		if(value[i].length>1){
			ArraydX.push(value[i][1]);
		}
	}
	for(var i=0;i<value.length;i++){
		value[i].splice(1,1);
		DataString[i]=value[i];
	}
	if(SubTitulo == "Automatico"){
		var SubTitulo = "Desde Fecha (" + ArraydX[0] + " Hasta " + ArraydX[ArraydX.length-1] + ")" ;
	}
	Grafica(NombreDiv,Titulo,SubTitulo,SubTituloLateral,ArraydX,Ymin,Ymax,typeData,NombresDeVariables,PronombraData,DataString,ColorData);
}