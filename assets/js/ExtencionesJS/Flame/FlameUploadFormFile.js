function UploadFormFile(e){
	var form = e;
	//console.log(form);
	//var $form = $('.SubaDeImagenes');
	
	var droppedFiles = false;
	if(isAdvancedUpload){
		Loading();
		var ajaxData = new FormData(form);
		ajaxData.append('TestPost', "true");
		$.ajax({
			url: form.getAttribute('action'),
			type: "POST",
			data: ajaxData,
			//dataType: 'json',//CORS
			cache: false,
			contentType: false,
			processData: false,
			//crossDomain: true,
			
			//headers: {
			//	'Access-Control-Allow-Origin': '*'
			//},
			
			complete: function() {
			},
			success: function(Resultado) {
				//console.log(Resultado);
				var Resultado = Resultado.trim();
				if(Resultado=="NULL" || Resultado=="" || ( Resultado.indexOf("Error:") == 0 ) ){
					if((Resultado.indexOf("Error:") == 0) ){
						Resultado = Resultado.replace("Error:", "");
						if(typeof $.bootstrapGrowl === "function") {
							$.bootstrapGrowl(Resultado,{
								type: 'danger',
								align: 'center',
								width: 'auto'
							});
						}
					}
				}else{
					if(typeof $.bootstrapGrowl === "function" && Resultado!= "") {
						$.bootstrapGrowl(Resultado,{
							type: 'success',
							align: 'center',
							width: 'auto'
						});
					}
				}
				EndLoading();
			},
			error:function(Resultado){
				//console.log(Resultado);
				if(typeof $.bootstrapGrowl === "function") {
					$.bootstrapGrowl("Ocurrio Un Error Al Intentar Mandar Los Datos",{
						type: 'danger',
						align: 'center',
						width: 'auto'
					});
				}
				EndLoading();
			}
		});
	}
}