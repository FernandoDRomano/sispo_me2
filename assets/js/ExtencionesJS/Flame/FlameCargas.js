


function get_header_row(sheet) {
    var headers = [];
    var range = XLSX.utils.decode_range(sheet['!ref']);
    var C, R = range.s.r; /* start in the first row */
    /* walk every column in the range */
    for(C = range.s.c; C <= range.e.c; ++C) {
        var cell = sheet[XLSX.utils.encode_cell({c:C, r:R})] /* find the cell in the first row */
        var hdr = "UNKNOWN " + C; // <-- replace with your desired default 
        if(cell && cell.t) hdr = XLSX.utils.format_cell(cell);
        headers.push(hdr);
    }
    return headers;
}


function CagarDataXLSXAJson(DivContenedor){
	if(typeof require !== 'undefined') {
		//console.log('hey');
		XLSX = require('xlsx');
	}
	var workbook;
	var sheet_name_list;
	var jsonData = {};
	var jsonFullData = new Array;
	
	var ArrayRequerido = DivContenedor.XLSXCOLUMNAS.split(",");
	//console.log(ArrayRequerido);
	if(DivContenedor.data.length > 0){
		for(var i = 0 ; i < DivContenedor.data.length ; i++){
			var File = DivContenedor.data[i];
			workbook = XLSX.read(File, {
				type: 'binary'
			})
			sheet_name_list = workbook.SheetNames;
			var Cabeceras = get_header_row(workbook.Sheets[sheet_name_list[0]],{defval:""});
			//console.log(Cabeceras);
			var Respuesta = ArrayEnArray(ArrayRequerido, Cabeceras);
			if( Respuesta != -1){
				if(typeof $.bootstrapGrowl === "function") {
					$.bootstrapGrowl("Columna:" + ArrayRequerido[Respuesta] + " No Encontrada En Archivo N°:" + (i+1),{
						type: 'danger',
						align: 'center',
						width: 'auto'
					});
				}
				EndLoading();
				return (false);
			}
			jsonFullData = ArraydJsonConcatenar(jsonFullData,XLSX.utils.sheet_to_json(workbook.Sheets[sheet_name_list[0]],{defval:""}));
		}
	}else{
		//console.log(DivContenedor.data);
		//console.log(DivContenedor);
		var File = DivContenedor.data[0];
		workbook = XLSX.read(File, {
			type: 'binary'
		})
		sheet_name_list = workbook.SheetNames;
		var Cabeceras = get_header_row(workbook.Sheets[sheet_name_list[0]],{defval:""});
		//console.log(Cabeceras);
		var Respuesta = ArrayEnArray(ArrayRequerido, Cabeceras);
		if( Respuesta != -1){
			if(typeof $.bootstrapGrowl === "function") {
				$.bootstrapGrowl("Columna:" + ArrayRequerido[Respuesta] + " No Encontrada En Archivo N°:" + (i+1),{
					type: 'danger',
					align: 'center',
					width: 'auto'
				});
			}
			EndLoading();
			return (false);
		}
		jsonFullData = XLSX.utils.sheet_to_json(workbook.Sheets[sheet_name_list[0]],{defval:""});
	}
	jsonData = jsonFullData;
	DivContenedor.JsonData = jsonData;
	return (true);
}
function JsonAFicheroUnificado(DivContenedor){
	jsonData = DivContenedor.JsonData;
	//console.log(jsonData);
	var ws =  XLSX.WorkSheet = XLSX.utils.json_to_sheet(jsonData);
	var wb = XLSX.WorkBook = XLSX.utils.book_new();
	XLSX.utils.book_append_sheet(wb, ws, 'test');
	XLSX.writeFile(wb, 'sample.xlsx');
}


function CargaDeXLSX(Data,Fin,EnvioActual,url){//Funcion De Auto Completado De Elemento Genericos
	TotalDeConsultas = Fin;
	Loading();
	if(EnvioActual == 1){
		var loading = document.getElementById("loading");
		var div = document.createElement("div");
		div.className="progress";
		div.setAttribute("style","height: 10px; margin-bottom: 2px;");
		var divLoading = document.createElement("div");
		divLoading.id="BarraDeCarga";
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
		RespuestasDeMultipleEnvioDeConsulta=0;
		RespuestasFallidasDeMultipleEnvioDeConsulta=0;
	}
	var PostData = Data;
	$.ajax({
		type:"GET",
		url: url,
		data: PostData,
		
		success:function(Resultado){
			//console.log(Resultado);
			var Resultado = Resultado[0].trim();
			//console.log(Resultado);
			
			percentComplete = parseInt( (RespuestasDeMultipleEnvioDeConsulta + RespuestasFallidasDeMultipleEnvioDeConsulta + 1)/(Fin)*100, 10);
			//console.log("CantidadDePosts:"+Config.CantidadDePosts +" RespuestasDeMultipleEnvioDeConsulta"+ RespuestasDeMultipleEnvioDeConsulta + " Resultado:" +percentComplete);
			//$('#BarraDeCarga').data("aria-valuenow",percentComplete);
			$('#BarraDeCarga').css("width",percentComplete+'%');
			var Resultado = Resultado.trim();
			if(Resultado=="NULL" || Resultado=="" || (Resultado.indexOf("Error:") == 0) ){
				RespuestasFallidasDeMultipleEnvioDeConsulta++;
				if(CountLoading == 1){
					if(typeof $.bootstrapGrowl === "function"){
						$.bootstrapGrowl( ((RespuestasDeMultipleEnvioDeConsulta)/(Fin)*100) + "% Cargado Correctos",{
							type: 'success',
							align: 'center',
							width: 'auto'
						});
					}
				}
				if((Resultado.indexOf("Error:") == 0) ){
					//alert(Resultado.indexOf("Error:"));
					Resultado.replace("Error:", "");
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
				RespuestasDeMultipleEnvioDeConsulta ++;
				if(CountLoading == 1){
					if(typeof $.bootstrapGrowl === "function"){
						$.bootstrapGrowl( ((RespuestasDeMultipleEnvioDeConsulta)/(Fin)*100) + "% Cargado Correctos",{
							type: 'success',
							align: 'center',
							width: 'auto'
						});
					}
				}
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
		crossDomain: true,
		dataType: 'jsonp',
        //contentType: "application/json; charset=utf-8",
		headers: {
			'Access-Control-Allow-Origin': '*'
		},
        cache: false
		//dataType: "text" // El tipo de datos esperados del servidor. Valor predeterminado: Intelligent Guess (xml, json, script, text, html).
	})
}















