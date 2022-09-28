	var Arrayd;
	function AjaxArraid(Ajax){
		var time = 0;
		var xhttp;
		xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function(){
			if (this.readyState == 4 && this.status == 200){
				var Resultado = this.responseText.trim();
				Arrayd = Resultado.split(";");
			}else{if(this.readyState == 4){
					window.location="403forbidden";
				}
			}
		};
		xhttp.open("GET", Ajax+
		"?Time="+
		time
		, false);
		xhttp.send();
	}
	function AjaxArraydChangeComplete(DataNombre,Data,Ajax){
		Loading();
		var paragrap = document.getElementById("Paragrap");
		var xhttp;
		xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function(){
			if (this.readyState == 4 && this.status == 200){
				EndLoading();
				var Resultado = this.responseText.trim();
				if(Resultado=="NULL"){
					return(false);
				}else{
					ArraydChangeComplete = Resultado.split("|");
					return(ArraydChangeComplete);
				}
			}else{
				if (this.readyState == 4 && this.status != 200){
					EndLoading();
					window.location="403forbidden";
				}
			}
		};
		DataText="";
		if(DataNombre.length>0){
			for(var cont=0;cont<=DataNombre.length;cont++){
				if(cont == 0){
					DataText="?"+DataNombre[cont]+"="+Data[cont];
				}else{
					DataText=DataText+"&"+DataNombre[cont]+"="+Data[cont];
				}
			}
		}
		xhttp.open("GET", Ajax+DataText
		, false);
		xhttp.send();
	}
	
	function DeleteSelect(SelectId) {
		var x = document.getElementById(SelectId);
		var Alength = x.options.length;
		//alert(""+SelectId);
		//alert(x.options.length);
		while (x.options.length) {
			x.remove(0);
		}
		
		var option = document.createElement("option");
		option.value = "0";
		option.text = "Seleccione";
		x.add(option);
		
	}
	function AderirSelect(SelectId,Arrayd) {
		
		
		var x = document.getElementById(SelectId);
		//DeleteSelect(SelectId);
		var Alength = x.options.length;
		while (x.options.length) {
			x.remove(0);
		}
		
		var option = document.createElement("option");
		option.value = "0";
		option.text = "Seleccione";
		x.add(option);
		if(Arrayd.length>0 && Arrayd[0]!="NULL"){
			//alert("("+Arrayd[0]+")")
			for(var i=0;i<Arrayd.length;i++){
				//alert(Arrayd);
				var Arrayd2d = Arrayd[i].split("|");
				if(Arrayd2d.length<1){break;}
				//alert(Arrayd2d[0]);
				var ValueArrayd = Arrayd2d[0];
				var TextArrayd = Arrayd2d[1];
				var OpcionArrayd = document.createElement("option");
				OpcionArrayd.value = ValueArrayd;
				OpcionArrayd.text = TextArrayd;
				x.add(OpcionArrayd);
			}
		}
		
		var inputs, index;
		inputs = document.getElementsByTagName('input');
		for (index = 0; index < inputs.length; ++index) {
			var readonly = $(inputs[index]).attr("readonly");
			if(readonly && readonly.toLowerCase()!=='false') {
				inputs[index].value="";
			}
		}
		
		var select;
		select = document.getElementsByTagName('select');
		for (index = 0; index < select.length; ++index) {
			var readonly = $(select[index]).attr("readonly");
			if(readonly && readonly.toLowerCase()!=='false') {
				select[index].value="";
				while(select[index].length){
					select[index].remove(0);
				}
			}
		}
		
	}
	
	
	
	function ChangeCompleteSelect(ElementId,ElementTexto,TextoInicial,Ajax,Elementos,ElementosTextos){
		var ArraydRetorno;
		ElementTexto = document.getElementById(ElementTexto);
		ElementTexto.innerHTML = "<b style='color:#FF0000;font-size: 10px;width: 90%;'>"+TextoInicial+"</b>";
		$("#"+ElementId).change(function() {
			if (this.value == 0) {
				DeleteSelect(Elementos);
				ElementTexto.innerHTML = "<b style='color:#FF0000;font-size: 10px;width: 90%;'>"+TextoInicial+"</b>";
			}
			else {
				ElementTexto.innerHTML = "";
				filtro=["id","time"];
				filtroX=[this.value,"0"];
				ArraydRetorno = undefined;
				ArraydChangeComplete = undefined;
				AjaxArraydSelect(filtro,filtroX,Ajax);
				ArraydRetorno = ArraydChangeComplete;
				if(ArraydRetorno!=false && ArraydRetorno!=undefined){
					arraydSend = ArraydRetorno.split(";");
					AderirSelect(Elementos[0],arraydSend);
				}else{
					DeleteSelect(Elementos);
				}
			}
			var paragrap = document.getElementById("Paragrap");
			paragrap.innerHTML = "<b style='color: rgb(0, 0, 255);font-size:12px;'></b>'";
		});
		
		
		var inputs, index;
		inputs = document.getElementsByTagName('input');
		for (index = 0; index < inputs.length; ++index) {
			var readonly = $(inputs[index]).attr("readonly");
			if(readonly && readonly.toLowerCase()!=='false') {
				inputs[index].value="";
			}
		}
		
		var select;
		select = document.getElementsByTagName('select');
		for (index = 0; index < select.length; ++index) {
			var readonly = $(select[index]).attr("readonly");
			if(readonly && readonly.toLowerCase()!=='false') {
				select[index].value="";
				while(select[index].length){
					select[index].remove(0);
				}
			}
		}
		
	}
	
	
	function NumeroSolido(ElementId){
		$("#"+ElementId).keyup(function() {
			if(this.tagName=="INPUT"){
				if($("#"+ElementId).attr('type') == "text"){
					this.value = this.value.replace(/[^a-zA-Z0-9ññÑÑ  ]+/g, "");
				}
				if($("#"+ElementId).attr('type') == "number"){
					this.value = this.value.replace(/[^0-9,,..]+/g, "");
				}
				if($("#"+ElementId).attr('type') == "numberNoFloat"){
					this.value = this.value.replace(/[^0-9]+/g, "");
				}
			}
		});
	}
	function Texto(ElementId,ElementTexto,DigitosMinimos,TextoInicial,TextoMenor){
		ElementTexto = document.getElementById(ElementTexto);
		ElementTexto.innerHTML = "<b style='color:#FF0000;font-size: 10px;width: 90%;'>"+TextoInicial+"</b>";
		$("#"+ElementId).keyup(function() {
			if(this.tagName=="INPUT"){
				if($("#"+ElementId).attr('type') == "text"){
					this.value = this.value.replace(/[^a-zA-Z0-9ññÑÑ  ]+/g, "");
				}
				if($("#"+ElementId).attr('type') == "number"){
					this.value = this.value.replace(/[^0-9,,..]+/g, "");
				}
				if($("#"+ElementId).attr('type') == "textWeb"){
					this.value = this.value.replace(/[^a-zA-Z0-9ññÑÑ  ..//]+/g, "");
				}
				if($("#"+ElementId).attr('type') == "textCorreo"){
					this.value = this.value.replace(/[^a-zA-Z0-9ññÑÑ  @@..]+/g, "");
				}
				
			}
			if (this.value.length <= 0) {
				ElementTexto.innerHTML = "<b style='color:#FF0000;font-size: 10px;width: 90%;'>"+TextoInicial+"</b>";
			}
			else {
				if(this.value.length<DigitosMinimos){
					ElementTexto.innerHTML = "<b style='color:#FF0000;font-size: 10px;width: 90%;'>"+TextoMenor+"</b>";
				}else{
					ElementTexto.innerHTML = "";
				}
			}
			var paragrap = document.getElementById("Paragrap");
			paragrap.innerHTML = "<b style='color: rgb(0, 0, 255);font-size:12px;'></b>'";
		});
		$("#"+ElementId).blur(function() {
			if(this.tagName=="INPUT"){
				this.value = this.value.trim();
				//alert("("+this.value+")");
			}
		});
		$("#"+ElementId).mouseup(function() {
			if (this.value == 0) {
				ElementTexto.innerHTML = "<b style='color:#FF0000;font-size: 10px;width: 90%;'>"+TextoInicial+"</b>";
			}
			else {
				if(this.tagName=="INPUT"){
					if($("#"+ElementId).attr('type') == "text"){
						this.value = this.value.replace(/[^a-zA-Z0-9ññÑÑ  ]+/g, "");
					}
					if($("#"+ElementId).attr('type') == "number"){
						this.value = this.value.replace(/[^0-9,,..]+/g, "");
					}
					if($("#"+ElementId).attr('type') == "textWeb"){
						this.value = this.value.replace(/[^a-zA-Z0-9ññÑÑ  ..//]+/g, "");
					}
					if($("#"+ElementId).attr('type') == "textCorreo"){
						this.value = this.value.replace(/[^a-zA-Z0-9ññÑÑ  @@..]+/g, "");
					}
				}
				ElementTexto.innerHTML = "";
			}
			var paragrap = document.getElementById("Paragrap");
			paragrap.innerHTML = "<b style='color: rgb(0, 0, 255);font-size:12px;'></b>'";
		});
	}
	function Change(ElementId,ElementTexto,TextoInicial){
		ElementTexto = document.getElementById(ElementTexto);
		ElementTexto.innerHTML = "<b style='color:#FF0000;font-size: 10px;width: 90%;'>"+TextoInicial+"</b>";
		$("#"+ElementId).change(function() {
			if (this.value == 0) {
				ElementTexto.innerHTML = "<b style='color:#FF0000;font-size: 10px;width: 90%;'>"+TextoInicial+"</b>";
			}
			else {
				ElementTexto.innerHTML = "";
			}
			var paragrap = document.getElementById("Paragrap");
			paragrap.innerHTML = "<b style='color: rgb(0, 0, 255);font-size:12px;'></b>'";
		});
	}
	
	var ArraydChangeComplete;
	
	
	function AjaxArraydSelect(DataNombre,Data,Ajax){
		Loading();
		var paragrap = document.getElementById("Paragrap");
		var xhttp;
		xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function(){
			if (this.readyState == 4 && this.status == 200){
				EndLoading();
				var Resultado = this.responseText.trim();
				if(Resultado=="NULL"){
					return(false);
				}else{
					ArraydChangeComplete = Resultado;//.split("|");
					return(Resultado);
				}
			}else{
				if (this.readyState == 4 && this.status != 200){
					EndLoading();
					window.location="403forbidden";
				}
			}
		};
		DataText="";
		if(DataNombre.length>0){
			for(var cont=0;cont<=DataNombre.length;cont++){
				if(cont == 0){
					DataText="?"+DataNombre[cont]+"="+Data[cont];
				}else{
					DataText=DataText+"&"+DataNombre[cont]+"="+Data[cont];
				}
			}
		}
		xhttp.open("GET", Ajax+DataText
		, false);
		xhttp.send();
	}
	function ChangeValue(ElementId,ElementTexto,Value,TextoInicial){
		ElementTexto = document.getElementById(ElementTexto);
		ElementTexto.innerHTML = "<b style='color:#FF0000;font-size: 10px;width: 90%;'>"+TextoInicial+"</b>";
		if(this.tagName=="INPUT"){
			//alert(this.tagName);
			if($("#"+ElementId).attr('type') == "text"){
				this.value = this.value.replace(/[^a-zA-Z0-9ññÑÑ  ]+/g, "");
			}
			if($("#"+ElementId).attr('type') == "number"){
				this.value = this.value.replace(/[^0-9,,..]+/g, "");
			}
		}else{
			//alert(this.tagName);
		}
		$("#"+ElementId).change(function() {
			if (this.value < Value) {
				ElementTexto.innerHTML = "<b style='color:#FF0000;font-size: 10px;width: 90%;'>"+TextoInicial+"</b>";
			}
			else {
				//alert(this.value );
				ElementTexto.innerHTML = "";
			}
			var paragrap = document.getElementById("Paragrap");
			paragrap.innerHTML = "<b style='color: rgb(0, 0, 255);font-size:12px;'></b>'";
		});
	}
	
	/*
	function AderirSelect(SelectId,Arrayd) {
		var x = document.getElementById(SelectId);
		var option = document.createElement("option");
		option.value = "0";
		option.text = "Seleccione";
		x.add(option);
		if(Arrayd.length>0 && Arrayd[0]!="NULL"){
			//alert("("+Arrayd[0]+")")
			for(var i=0;i<Arrayd.length;i++){
				var Arrayd2d = Arrayd[i].split("|");
				if(Arrayd2d.length<1){break;}
				var ValueArrayd = Arrayd2d[0];
				var TextArrayd = Arrayd2d[1];
				var OpcionArrayd = document.createElement("option");
				OpcionArrayd.value = ValueArrayd;
				OpcionArrayd.text = TextArrayd;
				x.add(OpcionArrayd);
			}
		}
	}
	*/
	
	
	
	
	
	
	function ChangeCompleteBlock(ElementId,ElementTexto,TextoInicial,Ajax,Elementos,ElementosTextos){
		var ArraydRetorno;
		ElementTexto = document.getElementById(ElementTexto);
		ElementTexto.innerHTML = "<b style='color:#FF0000;font-size: 10px;width: 90%;'>"+TextoInicial+"</b>";
		$("#"+ElementId).change(function() {
			if (this.value == 0) {
				ElementTexto.innerHTML = "<b style='color:#FF0000;font-size: 10px;width: 90%;'>"+TextoInicial+"</b>";
				for(var i=0;i<Elementos.length ;i++){
					var ElementoTemporal = document.getElementById(Elementos[i]);
					//alert(Elementos[i]);
					if(ElementoTemporal.tagName=='IMG'){
						ElementoTemporal.src = "";
						ElementoTemporal.style.width = '100%'
						ElementoTemporal.style.height = 'auto'
					}else{
						if(ElementoTemporal.tagName=='P'){
							ElementoTemporal.innerHTML = "";
						}else{
							ElementoTemporal.value = "";
							ElementoTemporal.removeAttribute("readonly", "");
							
						}
					}
				}
			}
			else {
				ElementTexto.innerHTML = "";
				filtro=["id","time"];
				filtroX=[this.value,"0"];
				ArraydRetorno = undefined;
				ArraydChangeComplete = undefined;
				AjaxArraydChangeComplete(filtro,filtroX,Ajax);
				ArraydRetorno = ArraydChangeComplete;
				if(ArraydRetorno!=false){
					for(var i=0;i<ArraydRetorno.length && i<Elementos.length ;i++){
						var ElementoTemporal = document.getElementById(Elementos[i]);
						if(ArraydRetorno[i]=='0'){
							ElementoTemporal.setAttribute("readonly", "");
							ElementoTemporal.value = "";
						}else{
							if(ArraydRetorno[i]=='1'){
								ElementoTemporal.removeAttribute("readonly", "");
							}else{
								if(ElementoTemporal.tagName=='IMG'){
									ElementoTemporal.src = imgDIR+ArraydRetorno[i];
									ElementoTemporal.style.width = '100%'
									ElementoTemporal.style.height = 'auto'
								}else{
									if(ElementoTemporal.tagName=='P'){
										ElementoTemporal.innerHTML = ArraydRetorno[i];
									}else{
										ElementoTemporal.value = ArraydRetorno[i];
									}
								}
								if(ElementosTextos[i]!="" && ArraydRetorno[i]!=""){
									var ElementosTextosTemporal = document.getElementById(ElementosTextos[i]);
									ElementosTextosTemporal.innerHTML ="";
								}
							}
						}
						
					}
				}
			}
			
			var inputs, index;
			inputs = document.getElementsByTagName('input');
			for (index = 0; index < inputs.length; ++index) {
				var readonly = $(inputs[index]).attr("readonly");
				if(readonly && readonly.toLowerCase()!=='false') {
					inputs[index].value="";
				}
			}
			
			var select;
			select = document.getElementsByTagName('select');
			for (index = 0; index < select.length; ++index) {
				var readonly = $(select[index]).attr("readonly");
				if(readonly && readonly.toLowerCase()!=='false') {
					select[index].value="";
					while(select[index].length){
						select[index].remove(0);
					}
				}
			}
			
			var paragrap = document.getElementById("Paragrap");
			paragrap.innerHTML = "<b style='color: rgb(0, 0, 255);font-size:12px;'></b>'";
		});
	}
	
	
	
	
	
	
	
	
	
	
	
	var imgDIR="";
	function ChangeComplete(ElementId,ElementTexto,TextoInicial,Ajax,Elementos,ElementosTextos){
		var ArraydRetorno;
		ElementTexto = document.getElementById(ElementTexto);
		ElementTexto.innerHTML = "<b style='color:#FF0000;font-size: 10px;width: 90%;'>"+TextoInicial+"</b>";
		$("#"+ElementId).change(function() {
			if (this.value == 0) {
				ElementTexto.innerHTML = "<b style='color:#FF0000;font-size: 10px;width: 90%;'>"+TextoInicial+"</b>";
			}
			else {
				ElementTexto.innerHTML = "";
				filtro=["id","time"];
				filtroX=[this.value,"0"];
				ArraydRetorno = undefined;
				ArraydChangeComplete = undefined;
				AjaxArraydChangeComplete(filtro,filtroX,Ajax);
				ArraydRetorno = ArraydChangeComplete;
				if(ArraydRetorno!=false){
					for(var i=0;i<ArraydRetorno.length && i<Elementos.length ;i++){
						//alert(ArraydRetorno[i]);
						var ElementoTemporal = document.getElementById(Elementos[i]);
						if(ElementoTemporal.tagName=='IMG'){
							ElementoTemporal.src = imgDIR+ArraydRetorno[i];
							ElementoTemporal.style.width = '100%'
							ElementoTemporal.style.height = 'auto'
						}else{
							if(ElementoTemporal.tagName=='P'){
								ElementoTemporal.innerHTML = ArraydRetorno[i];
							}else{
								if(ElementoTemporal.tagName=='SELECT'){
									ElementoTemporal.value = ArraydRetorno[i];
									$('#'+Elementos[i]).trigger("change");
								}else{
									
									if(ElementoTemporal.type=="number" && ArraydRetorno[i]== ""){
										ElementoTemporal.value = "0";
									}else{
										ElementoTemporal.value = ArraydRetorno[i];
									}
									if(ElementoTemporal.type=="checkbox"){
										if(ArraydRetorno[i]=="1"){
											ElementoTemporal.checked = true;
										}else{
											if(ArraydRetorno[i]=="0"){
												ElementoTemporal.checked = false;
											}
										}
									}
								}
							}
						}
						if(ElementosTextos[i]!="" && ArraydRetorno[i]!=""){
							var ElementosTextosTemporal = document.getElementById(ElementosTextos[i]);
							ElementosTextosTemporal.innerHTML ="";
						}
					}
				}
			}
			
			var inputs, index;
			inputs = document.getElementsByTagName('input');
			for (index = 0; index < inputs.length; ++index) {
				var readonly = $(inputs[index]).attr("readonly");
				if(readonly && readonly.toLowerCase()!=='false') {
					inputs[index].value="";
				}
			}
			
			var select;
			select = document.getElementsByTagName('select');
			for (index = 0; index < select.length; ++index) {
				var readonly = $(select[index]).attr("readonly");
				if(readonly && readonly.toLowerCase()!=='false') {
					select[index].value="";
					while(select[index].length){
						select[index].remove(0);
					}
				}
			}
			
			var paragrap = document.getElementById("Paragrap");
			paragrap.innerHTML = "<b style='color: rgb(0, 0, 255);font-size:12px;'></b>'";
		});
	}
	
	function ChangeCompleteCheck(ElementId,ElementTexto,TextoInicial,Ajax,Elementos,ElementosTextos){
		var ArraydRetorno;
		ElementTexto = document.getElementById(ElementTexto);
		ElementTexto.innerHTML = "<b style='color:#FF0000;font-size: 10px;width: 90%;'>"+TextoInicial+"</b>";
		$("#"+ElementId).change(function() {
			if (this.value == 0) {
				ElementTexto.innerHTML = "<b style='color:#FF0000;font-size: 10px;width: 90%;'>"+TextoInicial+"</b>";
				for(var i=0;i<Elementos.length ;i++){
						var ElementoTemporal = document.getElementById(Elementos[i]);
						if(ElementoTemporal.tagName=='IMG'){
							ElementoTemporal.src = "";
							ElementoTemporal.style.width = '100%'
							ElementoTemporal.style.height = 'auto'
						}else{
							
							if(ElementoTemporal.tagName=='P'){
								ElementoTemporal.innerHTML = "";
							}else{
								if(ElementoTemporal.type=="checkbox"){
									ElementoTemporal.checked = false;
									
								}else{
									ElementoTemporal.value = "";
								}
							}
						}
					}
			}
			else {
				ElementTexto.innerHTML = "";
				filtro=["id","time"];
				filtroX=[this.value,"0"];
				ArraydRetorno = undefined;
				ArraydChangeComplete = undefined;
				AjaxArraydChangeComplete(filtro,filtroX,Ajax);
				ArraydRetorno = ArraydChangeComplete;
				//alert(ArraydRetorno);
				if(ArraydRetorno!=false){
					for(var i=0;i<ArraydRetorno.length && i<Elementos.length ;i++){
						var ElementoTemporal = document.getElementById(Elementos[i]);
						if(ElementoTemporal.tagName=='IMG'){
							ElementoTemporal.src = imgDIR+ArraydRetorno[i];
							ElementoTemporal.style.width = '100%'
							ElementoTemporal.style.height = 'auto'
						}else{
							
							if(ElementoTemporal.tagName=='P'){
								ElementoTemporal.innerHTML = ArraydRetorno[i];
							}else{
								if(ElementoTemporal.type=="checkbox"){
									//alert("checkbox");
									if(ArraydRetorno[i]=="1"){
										//alert("1");
										ElementoTemporal.checked = true;
									}else{
										//alert(ArraydRetorno[i]);
										if(ArraydRetorno[i]=="0"){
											//alert("1");
											ElementoTemporal.checked = false;
										}else{
										}
									}
									
								}else{
									ElementoTemporal.value = ArraydRetorno[i];
								}
							}
						}
						if(ElementosTextos[i]!="" && ArraydRetorno[i]!=""){
							var ElementosTextosTemporal = document.getElementById(ElementosTextos[i]);
							ElementosTextosTemporal.innerHTML ="";
						}
					}
				}else{
					
				}
			}
			
			var paragrap = document.getElementById("Paragrap");
			paragrap.innerHTML = "<b style='color: rgb(0, 0, 255);font-size:12px;'></b>'";
		});
	}
	
	function ImputChangeComplete(ElementId,ElementTexto,TextoInicial,Ajax,Elementos,ElementosTextos){
		var ArraydRetorno;
		//ArraydChangeComplete=undefined;
		ElementTexto = document.getElementById(ElementTexto);
		ElementTexto.innerHTML = "<b style='color:#FF0000;font-size: 10px;width: 90%;'>"+TextoInicial+"</b>";
		$("#"+ElementId).keyup(function() {
			if (this.value == 0) {
				ElementTexto.innerHTML = "<b style='color:#FF0000;font-size: 10px;width: 90%;'>"+TextoInicial+"</b>";
			}
			else {
				ElementTexto.innerHTML = "";
				filtro=["id","time"];
				filtroX=[this.value,"0"];
				ArraydRetorno = undefined;
				ArraydChangeComplete = undefined;
				AjaxArraydChangeComplete(filtro,filtroX,Ajax);
				//alert(ArraydChangeComplete!=undefined);
				if(ArraydChangeComplete!=undefined){
					ArraydRetorno = ArraydChangeComplete;
					if(ArraydRetorno!=false){
						for(var i=0;i<ArraydRetorno.length;i++){
							var ElementoTemporal = document.getElementById(Elementos[i]);
							if(ElementoTemporal.tagName=='IMG'){
								ElementoTemporal.src = ArraydRetorno[i];
								ElementoTemporal.style.width = '100%'
								ElementoTemporal.style.height = 'auto'
							}else{
								if(ElementoTemporal.tagName=='P'){
									ElementoTemporal.innerHTML = ArraydRetorno[i];
								}else{
									ElementoTemporal.value = ArraydRetorno[i];
								}
							}
							if(ElementosTextos[i]!="" && ArraydRetorno[i]!=""){
								var ElementosTextosTemporal = document.getElementById(ElementosTextos[i]);
								ElementosTextosTemporal.innerHTML ="";
							}
						}
					}
				}else{
					for(var i=0;i<Elementos.length;i++){
						var ElementoTemporal = document.getElementById(Elementos[i]);
						if(ElementoTemporal.tagName=='IMG'){
								ElementoTemporal.src = "";
								ElementoTemporal.style.width = '1px'
								ElementoTemporal.style.height = 'auto'
							}else{
								if(ElementoTemporal.tagName=='P'){
									ElementoTemporal.innerHTML = "";
								}else{
									if(ElementoTemporal.tagName=='SELECT'){
										ElementoTemporal.value = 0;
									}else{
										//alert(ElementoTemporal.tagName);
										ElementoTemporal.value = "";
									}
								}
							}
					}
				}
			}
			var paragrap = document.getElementById("Paragrap");
			paragrap.innerHTML = "<b style='color: rgb(0, 0, 255);font-size:12px;'></b>'";
		});
	}
	
	function AjaxItem(Elementos,DataNombre,Data,Ajax){
		Loading();
		//var Item = document.getElementById(ItemName);
		var xhttp;
		xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function(){
			if (this.readyState == 4 && this.status == 200){
				var Resultado = this.responseText.trim();
				if(Resultado=="Sin Resultados"){
				}else{
					if(Resultado=="Elude"){
					}else{
						
						//alert(Resultado);
						var concat="";
						var Arrayd = Resultado.split(";");
						for(var i=0;i<Arrayd.length ;i++){
							concat = concat+"Resultado"+i+"= "+Arrayd[i]+"\n";
						}
						alert(concat);
						
						//Item.value = Resultado;
						
						if(Arrayd!=false){
							for(var i=0;i<Arrayd.length && i<Elementos.length ;i++){
								//alert(Arrayd[i]);
								var ElementoTemporal = document.getElementById(Elementos[i]);
								if(ElementoTemporal.tagName=='IMG'){
									ElementoTemporal.src = imgDIR+Arrayd[i];
									ElementoTemporal.style.width = '100%'
									ElementoTemporal.style.height = 'auto'
								}else{
									if(ElementoTemporal.tagName=='P'){
										ElementoTemporal.innerHTML = Arrayd[i];
										//alert(""+Arrayd[i]);
									}else{
										if(ElementoTemporal.tagName=='SELECT'){
											ElementoTemporal.value = Arrayd[i];
											$('#'+Elementos[i]).trigger("change");
										}else{
											
											if(ElementoTemporal.type=="number" && Arrayd[i]== ""){
												ElementoTemporal.value = "0";
											}else{
												ElementoTemporal.value = Arrayd[i];
											}
											if(ElementoTemporal.type=="checkbox"){
												if(Arrayd[i]=="1"){
													ElementoTemporal.checked = true;
												}else{
													if(Arrayd[i]=="0"){
														ElementoTemporal.checked = false;
													}
												}
											}
										}
									}
								}
								/*
								if(ElementosTextos[i]!="" && Arrayd[i]!=""){
									var ElementosTextosTemporal = document.getElementById(ElementosTextos[i]);
									ElementosTextosTemporal.innerHTML ="";
								}
								*/
							}
						}
					}
				}
				EndLoading();
			}else{
				if (this.readyState == 4 && this.status != 200){
					EndLoading();
				}
			}
		};
		DataText="";
		if(DataNombre.length>0){
			for(var cont=0;cont<=DataNombre.length;cont++){
				if(cont == 0){
					DataText="?"+DataNombre[cont]+"="+Data[cont];
				}else{
					DataText=DataText+"&"+DataNombre[cont]+"="+Data[cont];
				}
			}
		}
		xhttp.open("GET", Ajax+DataText
		, true);//false sin asincronia
		xhttp.send();
	}
	
	function AjaxParagrap(DataNombre,Data,Ajax){
		Loading();
		var paragrap = document.getElementById("Paragrap");
		var xhttp;
		xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function(){
			if (this.readyState == 4 && this.status == 200){
				var Resultado = this.responseText.trim();
				if(Resultado=="Sin Resultados"){
				}else{
					if(Resultado=="Elude"){
						
					}else{
						paragrap.innerHTML = Resultado;
					}
				}
				EndLoading();
			}else{
				if (this.readyState == 4 && this.status != 200){
					EndLoading();
				}
			}
		};
		DataText="";
		if(DataNombre.length>0){
			for(var cont=0;cont<=DataNombre.length;cont++){
				if(cont == 0){
					DataText="?"+DataNombre[cont]+"="+Data[cont];
				}else{
					DataText=DataText+"&"+DataNombre[cont]+"="+Data[cont];
				}
			}
		}
		xhttp.open("GET", Ajax+DataText
		, true);//false sin asincronia
		xhttp.send();
	}
	var ReturnAjaxWait = 0;
	function AjaxWaitValue(DataNombre,Data,Ajax){
		Loading();
		var paragrap = document.getElementById("Paragrap");
		var xhttp;
		xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function(){
			if (this.readyState == 4 && this.status == 200){
				var Resultado = this.responseText.trim();
				if(Resultado=="Sin Resultados"){
				}else{
					ReturnAjaxWait = Resultado;
				}
				EndLoading();
			}else{
				if (this.readyState == 4 && this.status != 200){
					EndLoading();
				}
			}
		};
		DataText="";
		if(DataNombre.length>0){
			for(var cont=0;cont<=DataNombre.length;cont++){
				if(cont == 0){
					DataText="?"+DataNombre[cont]+"="+Data[cont];
				}else{
					DataText=DataText+"&"+DataNombre[cont]+"="+Data[cont];
				}
			}
		}
		xhttp.open("GET", Ajax+DataText
		, false);//false sin asincronia
		xhttp.send();
	}
	
	function setVisible(selector, visible) {
		document.querySelector(selector).style.display = visible ? 'block' : 'none';
	}
	function Loading(){
		setVisible('#loading', true);
	}
	function EndLoading(){
		setVisible('#loading', false);
	}
	
	var Directorio = "Img";
	var NombreAnterior="";
	function timedText() {
		var file=document.getElementById("upfile").value;
		var x = document.getElementById("txt");
		if(NombreAnterior!=file){
			NombreAnterior=file;
			UpFile();
		}
		x.value=file;
		setTimeout(function(){timedText()}, 2000);
	}
	function getFile(){
		var file=document.getElementById("upfile").click();
		timedText();
	}
	function UpFile(){
		oData = new FormData(document.forms.namedItem("fileinfo"));
		oData.append("path" ,Directorio);
		var xhr = (window.XMLHttpRequest) ? new XMLHttpRequest() : new activeXObject("Microsoft.XMLHTTP");
		xhr.onreadystatechange = function(){
			if (this.readyState == 4 && this.status == 200){
				var ImgUrl=document.getElementById("ImgUrl");
				ImgUrl.innerHTML=this.responseText;
				var ImagenDeRetorno=document.getElementById("close-CSS");
				ImagenDeRetorno.src=this.responseText;
				ImagenDeRetorno.style.width = '100%'
				ImagenDeRetorno.style.height = 'auto'
			}
		};
		xhr.open( 'post', 'upload_file', true );
		xhr.send(oData);
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	/*
	function ChangeCompleteTableArrayd(IdArrayd, Ajax, Hijo, Padre){
			while( var i=0; i<arrayd.length; i++ ){
				document.getElementById(arrayd[i]+''+i).value="PUTO";
			}
			// / *
				if (this.value == 0) {
				}
				else {
					filtro=["id","time"];
					filtroX=[this.value,"0"];
					//ArraydRetorno = undefined;
					//ArraydChangeComplete = undefined;
					//AjaxArraydSelect(filtro,filtroX,Ajax);
					AjaxTabla(filtro,filtroX,Hijo,Padre,Ajax,false)
				}
				var paragrap = document.getElementById("Paragrap");
				paragrap.innerHTML = "<b style='color: rgb(0, 0, 255);font-size:12px;'></b>'";
			// * /
	}
	*/
	
	function ChangeCompleteTable(ElementId,Ajax,Hijo,Padre){
		//var ArraydRetorno;
		$("#"+ElementId).change(function() {
			if (this.value == 0) {
			}
			else {
				filtro=["id","time"];
				filtroX=[this.value,"0"];
				//ArraydRetorno = undefined;
				//ArraydChangeComplete = undefined;
				//AjaxArraydSelect(filtro,filtroX,Ajax);
				AjaxTabla(filtro,filtroX,Hijo,Padre,Ajax,false)
				/*
				ArraydRetorno = ArraydChangeComplete;
				if(ArraydRetorno!=false && ArraydRetorno!=undefined){
					
					arraydSend = ArraydRetorno.split(";");
					AderirSelect(Elementos[0],arraydSend);
				}
				*/
			}
			var paragrap = document.getElementById("Paragrap");
			paragrap.innerHTML = "<b style='color: rgb(0, 0, 255);font-size:12px;'></b>'";
		});
	}
	
	
	
	function AjaxTabla(DataNombre,Data,NuevoNombre,Div,Ajax,checkbox){
		Loading();
		var xhttp;
		xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function(){
			if (this.readyState == 4 && this.status == 200){
				var Resultado = this.responseText.trim();
				if(Resultado=="Sin Resultados"){
					
				}else{
					AutoTabla(NuevoNombre,Div,Resultado,false,false,checkbox);
				}
				EndLoading();
			}else{
				if (this.readyState == 4 && this.status != 200){
					//alert(this.readyState+" "+this.status);
					EndLoading();
				}
				//EndLoading();
			}
		};
		DataText="";
		if(DataNombre.length>0){
			for(var cont=0;cont<DataNombre.length;cont++){
				if(cont == 0){
					DataText="?"+DataNombre[cont]+"="+Data[cont];
				}else{
					DataText=DataText+"&"+DataNombre[cont]+"="+Data[cont];
				}
			}
		}
		xhttp.open("GET", Ajax+DataText
		, true);//false sin asincronia
		xhttp.send();
		//alert(Ajax+DataText);
	}
	//filtro=["time"];
	//filtroX=["0"];
	//AjaxTabla(filtro,filtroX,"HijoTablaDeDescuentos","TablaDeDescuentos","AjaxBuscarDescuentos",false);//.php
	function AutoTabla(id,idPadre,items,shortT,hidden,checkbox){
		var element =  document.getElementById(id);
		if (typeof(element) != 'undefined' && element != null){
			element.remove();
		}
		var tabla = document.createElement("TABLE");
		tabla.setAttribute("id", id);
		if(hidden){
			tabla.style.display="none";
		}
		var IidPadre = document.getElementById(idPadre);
		IidPadre.appendChild(tabla);
		IidPadre.setAttribute("style", "width:auto;");//height:80% overflow:auto;
		var Filas = new Array();
		var Filas = items.split(";");
		var item = new Array();
		Columnas = Filas[0].split("|");
		var fila1 = document.createElement("TR");
		fila1.setAttribute("id", "TR_Menues"+id);
		if(shortT){
			for(var iBN = 0;iBN<Columnas.length;iBN++){
				var Columna = document.createElement("TD");
				Columna.setAttribute("id", "TR_Menues_"+iBN);
				var Texto = document.createTextNode("Menu");
				fila1.appendChild(Columna);
			}
		}
		document.getElementById(id).appendChild(fila1);
		for(var i = 0;i<Filas.length;i++){
			var fila = document.createElement("TR");
			fila.setAttribute("id", "TR_"+i);
			item[i] = Filas[i].split("|");
			for(var j = 0;j<item[i].length;j++){
				if(i==0){
					var Columna = document.createElement("TH");
					Columna.setAttribute("id", "TD_"+i);
					var Texto = document.createTextNode(item[i][j]);
					Columna.appendChild(Texto);
				}else{
					var Columna = document.createElement("TD");
					if(item[0][j] == "Url"){
						Columna.setAttribute("id", "TD_"+i);
						Columna.setAttribute("data-href", "#");
						Columna.setAttribute("onclick","window.open('"+item[i][j]+"')");
						var Texto = document.createTextNode(item[i][j]);
						Columna.appendChild(Texto);
					}else{
						Columna.setAttribute("id", "TD_"+i);
						var Texto = document.createTextNode(item[i][j]);
						Columna.appendChild(Texto);
					}
				}
				fila.appendChild(Columna);
			}
			if(i==0 && checkbox){
			}
			if(i>0 && checkbox){
				var Columna = document.createElement("TD");
				Columna.setAttribute("id", "TD_"+i);
				var Checkbox = document.createElement("input");
				Checkbox.type = "checkbox";
				Checkbox.id = "Liquidar_"+i;
				Checkbox.className="cb";
				Checkbox.value = i;
				Checkbox.checked=false;
				Checkbox.onclick = function(){
					if(this.checked == false){
						for(var f =1; f<i;f++){
							document.getElementById("Liquidar_"+f).checked=false;
						}
					}
				};
				Columna.appendChild(Checkbox);
				var br = document.createElement("br");
				Columna.appendChild(br);
				var TextCheck = document.createTextNode("Facturar");
				Columna.appendChild(TextCheck);
				fila.appendChild(Columna);
			}
			document.getElementById(id).appendChild(fila);
		}
		while(true){
			var element =  document.getElementById("SpaceTable");
			if (typeof(element) != 'undefined' && element != null){
				element.remove();
			}else{
				break;
			}
		}
	}
	
	function LimpiarInputText(id,time){
		var x = document.getElementById("ListaDeImputs");
		while(x.firstChild){
			x.removeChild(x.firstChild);
		}
		document.getElementById("resultado1").innerHTML="";
		GlobalInput_HDR="";
		valorinicial_HDR="";
		resultado_HDR="";
		GlobalInput_Rendiciones="";
		valorinicialRendiciones="";
		resultado_Rendiciones="";
	}
	function CrearInputText(id,event){
		LimpiarInputText(1);
		event.value = parseFloat(event.value).toFixed(0);//0;
		if(event.value<0 ){
		}
		GlobalInput_HDR = event.value;
		for(var cont=0;cont<event.value;cont++){
			var x = document.createElement("input");
			x.id="inputText"+cont;
			x.className="form-control";
			var Br = document.createElement("Br");
			var y = document.getElementById(id);
			y.appendChild(x);
			y.appendChild(Br);
		}
	}
	
	