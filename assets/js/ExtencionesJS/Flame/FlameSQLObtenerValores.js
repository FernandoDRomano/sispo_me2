
var ResultadoSyncObtenerValorDeConsulta="";
function SyncObtenerValorDeConsulta(Config = false){
	ResultadoSyncObtenerValorDeConsulta="";
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
		//type:"GET",
		type: "POST",
		url:Config.Ajax,
		data: PostData,
		async: false,
		
		//url: form.getAttribute('action'),
		//data: ajaxData,
		//dataType: 'json',//CORS
		cache: false,
		//contentType: false,
		//processData: false,
		
		success:function(Resultado){
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
			}else{
				ResultadoSyncObtenerValorDeConsulta=Resultado;
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