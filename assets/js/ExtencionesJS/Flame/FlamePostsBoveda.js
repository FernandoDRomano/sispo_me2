function FlameSelectJqueryLimpiarSelect(Config) {
	var x = document.getElementById(Config.SelectId);
	if(x.options != undefined){
		while (x.options.length) {
			x.remove(0);
		}
	}
	x.setAttribute("readonly", "");
}
function FlameSelectJqueryAderirOpciones(Resultado,Config){
	if(Resultado != undefined){
		var keys = Object.keys(Resultado);
		for(var i=0;i<Resultado.length;i++){
			var keys = Object.keys(Resultado[i]);
			if(keys.length != 2){ 
				FlameMensajeEnFail(Config);
				return;
			}
		}
		var Select = document.getElementById(Config.SelectId);
		var option = document.createElement("option");
		option.value = "0";
		option.text = "Seleccione";
		Select.add(option);
		for(var i=0;i<Resultado.length;i++){
			var keys = Object.keys(Resultado[i]);
			var ValueArrayd = Resultado[i][keys[0]];
			var TextArrayd = Resultado[i][keys[1]];
			var OpcionArrayd = document.createElement("option");
			OpcionArrayd.value = ValueArrayd;
			OpcionArrayd.text = TextArrayd;
			Select.add(OpcionArrayd);
		}
		Select.removeAttribute("readonly", "");
	}
}

function FlameMensajeEnFail(Config){
	if(Config.MensajeEnFail){
		if(typeof $.bootstrapGrowl === "function") {
			$.bootstrapGrowl(Config.TextoEnFail,{
				type: 'danger',
				align: 'center',
				width: 'auto'
			});
		}
	}
}
function FlameSelectJquery(Config){
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
		var jsonValoresDirectos = JsonElementosAJsonValores(Config.ValoresDirectos);
		var PostData = {};
		PostData = jsonConcat(PostData, JSON.parse(jsonValoresDirectos));//jsonConcat FlameBase
		PostData = jsonConcat(PostData, JSON.parse(Config.DataAjax));//jsonConcat FlameBase
	}else{
		var PostData = {};
		PostData = jsonConcat(PostData, JSON.parse(Config.DataAjax));
	}
	FlameSelectJqueryLimpiarSelect(Config);
	//console.log(JSON.stringify(PostData));
	$.ajax({
		type:"POST",
		url:Config.Ajax,
		data: JSON.stringify(PostData),
		contentType: "application/json; charset=utf-8",
		success:function(Resultado){
			if(Resultado.length>0){
				var MensajeNull = Resultado["NULL"];
				var MensajeDeError = Resultado["Error:"];
				if(!MensajeNull == null){
					FlameMensajeEnFail(Config);
					EndLoading();
					return;
				}
				if(!MensajeDeError == null){
					FlameMensajeEnFail(Config);
					EndLoading();
					return;
				}
				if(MensajeNull == null && MensajeDeError == null){
					FlameSelectJqueryAderirOpciones(Resultado,Config);
				}
			}else{
				FlameMensajeEnFail(Config);
			}
			EndLoading();
			return;
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
	})
}


/*
*
*/

function TablaDesdeBoveda(Config = false){
	var x = document.getElementById(Config.DivContenedor);
	if(x == null){
		if(typeof $.bootstrapGrowl === "function") {
			$.bootstrapGrowl("El Elemento " + Config.DivContenedor + " No Existe",{
				type: 'danger',
				align: 'center',
				width: 'auto'
			});
		}
		return;
	}
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
	
	var loading = document.getElementById("loading");
	var div = document.createElement("div");
	div.className="progress";
	div.setAttribute("style","height: 10px; margin-bottom: 2px;");
	var divLoading = document.createElement("div");
	divLoading.id="BarraDeCarga_"+Config.DivContenedor;
	divLoading.className="progress-bar progress-bar-striped active";
	divLoading.setAttribute("role","progressbar");
	divLoading.setAttribute("aria-valuenow", "1");
	divLoading.setAttribute("aria-valuemin", "0");
	divLoading.setAttribute("aria-valuemax", "100");
	divLoading.setAttribute("style", "width:1%");
	divLoading.setAttribute("style","height: 10px; margin-bottom: 2px; background-color: #0068a9;");
	div.appendChild(divLoading);
	loading.appendChild(div);
	
	//console.log(JSON.stringify(PostData));
	console.log(PostData);
	$.ajax({
		type:"POST",
		url:Config.Ajax,
		data: JSON.stringify(PostData),
		contentType: "application/json; charset=utf-8",
		success:function(Resultado){
			percentComplete = parseInt( (100), 10);
			$('#BarraDeCarga_'+Config.DivContenedor).data("aria-valuenow",percentComplete);
			$('#BarraDeCarga_'+Config.DivContenedor).css("width",percentComplete+'%');
			
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////
			if(Resultado.length>0){
				var MensajeNull = Resultado["NULL"];
				var MensajeDeError = Resultado["Error:"];
				if(!MensajeNull == null){
					FlameMensajeEnFail(Config);
					EndLoading();
					return;
				}
				if(!MensajeDeError == null){
					FlameMensajeEnFail(Config);
					EndLoading();
					return;
				}
				if(MensajeNull == null && MensajeDeError == null){
					//console.log(Resultado.length);
					//console.log(JSON.parse(Resultado)[0]);
					var jsonResultado = JSON.parse(Resultado);
					var filas = jsonResultado.length;
					
					var keys = [];
					for(var i=0;i<filas;i++){
						for(var k in jsonResultado[i]){
							if(keys.indexOf(k) == -1){
								keys.push(k);
							}
						}
					}
					
					var Arrayd2D = new Array();
					Arrayd2D.push(keys);
					for(var i = 0 ; i < filas ; i++ ){
						var Arrayd1D = new Array();
						for(var j = 0 ; j < keys.length ; j++ ){
							Arrayd1D[j] = "" + jsonResultado[i][keys[j]];
						}
						Arrayd2D.push(Arrayd1D);
					}
					
					var ObjJSON = JSON.parse(JSON.stringify(Arrayd2D));
					Config.Resultado = ObjJSON;
					//console.log(Config);
					FormatearDatosParaTabla(Config);
					//FlameSelectJqueryAderirOpciones(Resultado,Config);
				}
			}else{
				FlameMensajeEnFail(Config);
			}
			EndLoading();
			return;
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////
/*
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
				var DivDeTabla =  document.getElementById(Config.DivContenedor);
				DivDeTabla.Config = undefined;
				var Paginador = DivDeTabla.getElementsByClassName('Paginador')[0];
				Paginador.innerHTML = "";
				
				if( typeof(DivDeTabla) == 'undefined' || DivDeTabla == null ){
					EndLoading;
					return;
				}
				
				var tabla = DivDeTabla.getElementsByTagName('table')[0];
				var ElementosDeTabla = tabla.getElementsByTagName("tr");
				var SaltarElemento = 0;
				
				if( typeof(ElementosDeTabla) != 'undefined' && ElementosDeTabla != null ){
					while(ElementosDeTabla.length > SaltarElemento){
						if(ElementosDeTabla[SaltarElemento].className == "Escaner"){SaltarElemento++;}
						else{ElementosDeTabla[SaltarElemento].remove();}
					}
				}
				if( typeof(ElementosDeTabla) != 'undefined' && ElementosDeTabla != null ){
					while(ElementosDeTabla.length > 0){
						ElementosDeTabla[0].remove();
					}
				}
				EndLoading();
			}else{
				console.log("Else");
				var NuevoMetodoDeTabla = false;
				if(Resultado.split("(;)").length > 1 && Resultado.split("(|)").length > 1){
					NuevoMetodoDeTabla = true;
				}
				if(NuevoMetodoDeTabla){
					var Arrayd1D = Resultado.split("(;)");
				}else{
					var Arrayd1D = Resultado.split(";");
				}
				var Arrayd2D = new Array();
				for(var i = 0 ; i < Arrayd1D.length ; i++ ){
					if(NuevoMetodoDeTabla){
						Arrayd2D[i] = Arrayd1D[i].split("(|)");
					}else{
						Arrayd2D[i] = Arrayd1D[i].split("|");
					}
					
				}
				var ObjJSON = JSON.parse(JSON.stringify(Arrayd2D));
				Config.Resultado = ObjJSON;
				FormatearDatosParaTabla(Config);
				EndLoading();
			}
*/
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
		dataType: "text"
	})
}






