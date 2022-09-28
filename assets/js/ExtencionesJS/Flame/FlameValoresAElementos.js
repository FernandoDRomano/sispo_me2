

function ValoresAElementos(Config){//Funcion De Auto Completado De Elemento Genericos
	Loading();
	if(Config.ValoresDirectos != null){
		var jsonValoresDirectos = JsonElementosAJsonValores(Config.ValoresDirectos);//JsonElementosAJsonValores FlameBase
		//console.log(Config.ValoresDirectos);
		//console.log(jsonValoresDirectos);
		
		var PostData = {};
		//console.log( JSON.parse(jsonValoresDirectos));
		//console.log( JSON.parse(Config.DataAjax));
		PostData = jsonConcat(PostData, JSON.parse(jsonValoresDirectos));//jsonConcat FlameBase
		PostData = jsonConcat(PostData, JSON.parse(Config.DataAjax));//jsonConcat FlameBase
		
		
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//Msivo
		if(Config.ArraydJsonPostTitulo != null ){
			var STROBJECT='{"' + Config.ArraydJsonPostTitulo + '":[]}';
			var obj = JSON.parse(STROBJECT);
			if(Config.ArraydJsonPost != null ){
				jsonValoresDirectos = JsonElementosAJsonValores(Config.ArraydJsonPost);//JsonElementosAJsonValores FlameBase
				//console.log(jsonValoresDirectos);
				obj[Config.ArraydJsonPostTitulo].push(JSON.parse(jsonValoresDirectos));
				//obj[Config.ArraydJsonPostTitulo].push(JSON.parse(jsonValoresDirectos));
				
				//Test De Envio Largo
				/*
				for(var i = 0 ; i < 1000 ; i++){
					obj[Config.ArraydJsonPostTitulo].push(JSON.parse(jsonValoresDirectos));
				}
				*/
				PostData = jsonConcat(PostData, obj);
			}
		}
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		//console.log(jsonValoresDirectos);
	}else{
		var PostData = {};
		PostData = jsonConcat(PostData, JSON.parse(Config.DataAjax));
		
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//Msivo
		if(Config.ArraydJsonPostTitulo != null ){
			var STROBJECT='{"' + Config.ArraydJsonPostTitulo + '":[]}';
			var obj = JSON.parse(STROBJECT);
			if(Config.ArraydJsonPost != null ){
				jsonValoresDirectos = JsonElementosAJsonValores(Config.ArraydJsonPost);//JsonElementosAJsonValores FlameBase
				obj[Config.ArraydJsonPostTitulo].push(JSON.parse(jsonValoresDirectos));
				//Test De Envio Largo
				/*
				for(var i = 0 ; i < 1000 ; i++){
					obj[Config.ArraydJsonPostTitulo].push(JSON.parse(jsonValoresDirectos));
				}
				*/
				PostData = jsonConcat(PostData, obj);
			}
		}
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//console.log(PostData);
	}
	//PostData = {data : JSON.stringify(PostData)};
	/*
	console.log(PostData);
	console.log(JSON.stringify(PostData));
	console.log(PostData);
	*/
	PostData = {js:JSON.stringify(PostData)};
	$.ajax({
		type:"POST",
		//crossDomain: true,
		dataType: 'jsonp',
        //contentType: "application/json; charset=utf-8",
		headers: {
			'Access-Control-Allow-Origin': '*'
		},
        cache: false,
		url:Config.Ajax,
		data: PostData,
		success:function(Resultado){
			//console.log(Resultado);
			var Resultado = Resultado[0].trim();
			var Resultado = Resultado.trim();
			if(Resultado=="NULL" || Resultado=="" || (Resultado.indexOf("Error:") == 0) ){
				if(typeof $.bootstrapGrowl === "function") {
					$.bootstrapGrowl(Resultado,{
						type: 'danger',
						align: 'center',
						width: 'auto'
					});
				}
				for(var i = 0 ; i<Config.Elementos.length ; i++){
					if(Config.Elementos[i] != ""){
						var Elemento = document.getElementById(Config.Elementos[i]);
						Elemento.innerHTML = "";
						Elemento.value = "";
					}
				}
				EndLoading();
			}else{
				var ArraidResultado = Resultado.split("|");
				if(Config.Elementos == undefined){
					if(typeof $.bootstrapGrowl === "function") {
						$.bootstrapGrowl(ArraidResultado[0],{
							type: 'success',
							align: 'center',
							width: 'auto'
						});
					}
				}else{
					if(Config.Elementos.length>0 && Config.Elementos[0] != ""){
						for(var i = 0 ; i<Config.Elementos.length ; i++){
							var Elemento = document.getElementById(Config.Elementos[i]);
							Elemento.innerHTML = "";
							Elemento.value = "";
						}
						
						for(var i = 0 ; i<Config.Elementos.length && i<ArraidResultado.length; i++){
							var Elemento = document.getElementById(Config.Elementos[i]);
							
							if(Elemento.tagName =="TABLE" && ArraidResultado[i].indexOf("TABLE:") == 0){
								ArraidResultado[i] = ArraidResultado[i].replace("TABLE:", "");
								
								var ArraidResultadoTabla = ArraidResultado[i].split("!");
								
								//console.log("ArraidResultadoTabla.length:" + ArraidResultadoTabla.length);
								for(var j = 0; j < ArraidResultadoTabla.length; j++){
									var row = document.createElement("tr");
									
									//console.log("j:" + j);
									//console.log("ArraidResultadoTabla[j]:" + ArraidResultadoTabla[j]);
									var ArraidResultadoTablaColumna = ArraidResultadoTabla[j].split("°");
									
									
									for (var k = 0; k < ArraidResultadoTablaColumna.length; k++) {
										var cell = document.createElement("td");
										var cellText = document.createTextNode(ArraidResultadoTablaColumna[k]);
										cell.appendChild(cellText);
										row.appendChild(cell);
									}
									
									Elemento.appendChild(row);
								}
							}else{
								Elemento.innerHTML = ArraidResultado[i];
								Elemento.value = ArraidResultado[i];
							}
						}
					}else{
						if(typeof $.bootstrapGrowl === "function") {
							$.bootstrapGrowl(ArraidResultado[0],{
								type: 'success',
								align: 'center',
								width: 'auto'
							});
						}
					}
					
				}
				
				
				
				/*
				if(typeof $.bootstrapGrowl === "function"){
					$.bootstrapGrowl( "" + "% Cargado Correctos",{
						type: 'success',
						align: 'center',
						width: 'auto'
					});
				}
				*/
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
		}
		//dataType: "text" // El tipo de datos esperados del servidor. Valor predeterminado: Intelligent Guess (xml, json, script, text, html).
	})
}