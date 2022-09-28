	var Debug = true;
	var imgDIR="IMAGENES/";
	/*
		Elementos=["NombrePrimeraVriable","...","NombreUltimaVariable"];
		ElementosTextos=["ValorPrimeraVariable","...","ValorUltimaVariable"];
	*/
	
	function ArraidNameValueToJSON(filtro,filtroX){
		var StringJson = "";
		if(Debug){
			if(filtro.length < filtroX.length){
				if (typeof $.bootstrapGrowl === "function") {
					$.bootstrapGrowl( "ArraidNameValueToJSON El Array De Datos Es Mayor Que El De Los Nombres" + "<br>" + 
					"El Dato (" + filtroX[filtroX.length - 1] + ") Sobra", {
						type: 'danger',//danger
						align: 'center',
						width: 'auto'
					});
				}
				return;
			}
			if(filtro.length > filtroX.length){
				if (typeof $.bootstrapGrowl === "function") {
					$.bootstrapGrowl( "ArraidNameValueToJSON El Array De Nombres Es Mayor Que El De Los Datos" + "<br>" + 
					"El Nombre (" + filtro[filtro.length - 1] + ") Sobra", {
						type: 'danger',//danger
						align: 'center',
						width: 'auto'
					});
				}
				return;
			}
		}
		StringJson = '[';
		for (var i=0; i<filtro.length && i<filtroX.length ; i++){
			if(i+1<filtro.length && i+1<filtroX.length){
				StringJson = StringJson + '{"' + filtro[i] + '":"' + filtroX[i] + '"},';
			}else{
				StringJson = StringJson + '{"' + filtro[i] + '":"' + filtroX[i] + '"}';
			}
		}
		StringJson = StringJson + ']';
		return StringJson//{"NombrePrimeraVriable":"ValorPrimeraVariable"},{"...":"..."},{"NombreUltimaVariable","ValorUltimaVariable"}
	}
	
	function ArrayNameValueJSONToPostString(DataAjax){
		DataText="";
		for (var Conti = 0; Conti < DataAjax.length; Conti++) {
			var Parametros = DataAjax[Conti];//
			var keys = Object.keys(Parametros);//console.log(keys[0]);
			var values = Object.values(Parametros);//console.log(values[0]);
			if(Conti == 0){
				DataText="?"+keys[0]+"="+values[0];
			}else{
				DataText=DataText+"&"+keys[0]+"="+values[0];
			}
		}
		//alert(DataText);
		return (DataText);
	}
	function JsonExtraerArraydDeValores(DataAjax){
		var Respuesta = new Array();
		DataText="";
		for (var Conti = 0; Conti < DataAjax.length; Conti++) {
			var Parametros = DataAjax[Conti];//
			var keys = Object.keys(Parametros);//console.log(keys[0]);
			var values = Object.values(Parametros);//console.log(values[0]);
			if(Conti == 0){
				//DataText="?"+keys[0]+"="+values[0];
				Respuesta.push(values[0]);
			}else{
				//DataText=DataText+"&"+keys[0]+"="+values[0];
				Respuesta.push(values[0]);
			}
		}
		return (Respuesta);
	}
	function JsonExtraerArrayd(DataAjax){
		var Respuesta = new Array();
		var RespuestaA = new Array();
		var RespuestaB = new Array();
		DataText="";
		for (var Conti = 0; Conti < DataAjax.length; Conti++) {
			var Parametros = DataAjax[Conti];//
			var keys = Object.keys(Parametros);//console.log(keys[0]);
			var values = Object.values(Parametros);//console.log(values[0]);
			if(Conti == 0){
				//DataText="?"+keys[0]+"="+values[0];
				RespuestaA.push(keys[0]);
				RespuestaB.push(values[0]);
			}else{
				//DataText=DataText+"&"+keys[0]+"="+values[0];
				RespuestaA.push(keys[0]);
				RespuestaB.push(values[0]);
			}
		}
		Respuesta[0] = RespuestaA;
		Respuesta[1] = RespuestaB;
		return (Respuesta);
	}
	
	function ChangeCompleteSelect(Config){
		
		//alert(Config.Reactivo);
		var ArraydRetorno;
		ElementTexto = document.getElementById(Config.TextoReactor);
		ElementTexto.innerHTML = "<b style='color:#FF0000;font-size: 10px;width: 90%;'>"+Config.TextoInicial+"</b>";
		$("#"+Config.Reactivo).change(function() {
			Loading();
			if (this.value == 0) {
				NotSussesDeleteSelect(Config.Reactor);
				ArraydChangeComplete = undefined;
				OnResultChangeCompleteSelect(ArraydChangeComplete,Config.Reactor);
				ElementTexto.innerHTML = "<b style='color:#FF0000;font-size: 10px;width: 90%;'>"+Config.TextoFail+"</b>";
				EndLoading();
			}
			else {
				ElementTexto.innerHTML = "";
				filtro=["id","time"];
				filtroX=[this.value,"0"];
				ArraydRetorno = undefined;
				ArraydChangeComplete = undefined;
				var StringJson = ArraidNameValueToJSON(filtro,filtroX);
				Config.DataAjax = JSON.parse(StringJson);
				AjaxArraydSelectAsync(Config);
			}
			var paragrap = document.getElementById("Paragrap");
			if (typeof(paragrap) != 'undefined' && paragrap != null){
				paragrap.innerHTML = "<b style='color: rgb(0, 0, 255);font-size:12px;'></b>";
			}
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
	
	
	function OnResultChangeCompleteSelect(ArraydChangeComplete,Elementos){
		ArraydRetorno = ArraydChangeComplete;
		if(ArraydRetorno!=false && ArraydRetorno!=undefined){
			arraydSend = ArraydRetorno.split(";");
			AderirSelect(Elementos,arraydSend);
			for(var i=1;i<Elementos.length;i++){
				$("#"+Elementos[i]).removeAttr("readonly", "");
			}
			EndLoading();
		}else{
			NotSussesDeleteSelect(Elementos);
			for(var i=1;i<Elementos.length;i++){
				$("#"+Elementos[i]).attr("readonly", "");
			}
			EndLoading();
		}
	}
	
	function downloadURI(uri, name) {
		var link = document.createElement("a");
		link.download = name;
		link.href = uri;
		document.body.appendChild(link);
		link.click();
		document.body.removeChild(link);
		delete link;
	}
	
	function AjaxConsultaYDescargaArchivo(Config){//DataNombre,Data,Ajax,Elementos,
		Loading();
		var paragrap = document.getElementById("Paragrap");
		var xhttp;
		xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function(){
			if (this.readyState == 4 && this.status == 200){
				var Resultado = this.responseText.trim();
				if(Resultado=="NULL"){
					//console.log(Resultado);
				}else{
					console.log(Config.Folder+Resultado);
					downloadURI(Config.Folder+Resultado, Config.NombreDelFichero);
					//window.open(Config.Folder+Resultado);
				}
				EndLoading();
			}else{
				if (this.readyState == 4 && this.status != 200){
					EndLoading();
					window.location="403forbidden";
				}
			}
		};
		DataText="";
		if(Config.ValoresDirectos != null){
			var arraydObjetos = new Array();
			arraydObjetos = JsonExtraerArraydDeValores(Config.ValoresDirectos);
			var TotalDeElementos = Config.DataAjax.length;
			for(var i = 0 ; i < Objetos.length ; i++){
				Config.DataAjax[TotalDeElementos + (i*1)] = JSON.parse("{\"" + Objetos[i] + "\":\"" + document.getElementById(Objetos[i]).value + "\"}");
			}
		}
		DataText = ArrayNameValueJSONToPostString(Config.DataAjax);
		var date = new Date();var DateNumber = date.getTime();
		if(DataText==''){
			DataText = DataText = DataText+'?'+'DateNumber='+DateNumber;
		}else{
			DataText = DataText+'&'+'DateNumber='+DateNumber;
		}
		console.log(Config.Ajax+DataText);
		xhttp.open("GET", Config.Ajax+DataText
		, true);
		xhttp.send();
	}
	
	var ArraydChangeComplete;
	function AjaxArraydSelectAsync(Config){//DataNombre,Data,Ajax,Elementos,
		Loading();
		var paragrap = document.getElementById("Paragrap");
		var xhttp;
		xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function(){
			if (this.readyState == 4 && this.status == 200){
				var Resultado = this.responseText.trim();
				if(Resultado=="NULL"){
					ArraydChangeComplete = undefined;
					OnResultChangeCompleteSelect(ArraydChangeComplete,Config.Reactor);
				}else{
					ArraydChangeComplete = Resultado;
					OnResultChangeCompleteSelect(ArraydChangeComplete,Config.Reactor);
				}
				EndLoading();
			}else{
				if (this.readyState == 4 && this.status != 200){
					EndLoading();
					window.location="403forbidden";
				}
			}
		};
		DataText="";
		DataText = ArrayNameValueJSONToPostString(Config.DataAjax);//console.log(Config);
		var date = new Date();var DateNumber = date.getTime();
		if(DataText==''){
			DataText = DataText+'?'+'DateNumber='+DateNumber;
		}else{
			DataText = DataText+'&'+'DateNumber='+DateNumber;
		}
		xhttp.open("GET", Config.Ajax+DataText
		, true);
		xhttp.send();
	}
	
	function Test(Config,i){
		//console.log(i);
		//console.log(Config);
		//Config.Reactor
		Config.Pagina = i-1;
		//console.log(Config);
		AutoTabla(Config,Config.Retorno);
		
	}
	
	function ActividadDePaginador(Config){
		//var selector = '#Paginador li';
		$("#"+Config.Paginador+" ul li").on('click', function(){
			$("#"+Config.Paginador+" ul li").removeClass('active');
			$(this).addClass('active');
		});
	}
	
	function FuncionDePaginado(Config, NumeroDeFilas){
		if(Config){
			if(Config.Paginador != undefined && Config.Paginador != ""){
				//alert("Aya");
				var Paginador = document.getElementById(Config.Paginador);
				Paginador.style.display="block";
				Paginador.innerHTML = "";
				var Ul = document.createElement("ul");
				
				Ul.id="Paginador";
				Ul.classList.add("pagination");
				
				var li = document.createElement("li");
				var ElementA = document.createElement("a");
				ElementA.Item = 1;
				ElementA.Config = Config;
				ElementA.onclick = function() {
					//this.classList.toggle("active");
					Test(this.Config,this.Item);
				};
				ElementA.innerHTML = "&laquo";
				li.appendChild(ElementA);
				Ul.appendChild(li);
				var ContadorTemporal=1;
				for(var i = 1; (i*1)-NumeroDeFilas < 0 ; i+=(Config.MaximoDeFilas*1), ContadorTemporal++){
					
					//alert(i + " " + NumeroDeFilas + " " + Config.MaximoDeFilas);
					var li = document.createElement("li");
					var ElementA = document.createElement("a");
					//ElementA.href="#";
					//ElementA.href="#"+Config.Reactor;
					ElementA.innerHTML = ContadorTemporal;
					ElementA.Item = ContadorTemporal;
					ElementA.Config = Config;
					ElementA.onclick = function() {
						//this.classList.toggle("active");
						Test(this.Config,this.Item);
					};
					li.appendChild(ElementA);
					Ul.appendChild(li);
				}
				
					//alert(i + " " + NumeroDeFilas + " " + Config.MaximoDeFilas);
				var li = document.createElement("li");
				var ElementA = document.createElement("a");
				//ElementA.href="#";
				ElementA.innerHTML = "&raquo;";
				ElementA.Item = ContadorTemporal-1;
				ElementA.Config = Config;
				ElementA.onclick = function() {
					//this.classList.toggle("active");
					Test(this.Config,this.Item);
				};
				li.appendChild(ElementA);
				Ul.appendChild(li);
				
				Paginador.appendChild(Ul);
			}
			ActividadDePaginador(Config);
		}
	}
	
	function AutoTabla(Config = false,Resultado){
		if(Config.TextoCheckbox == undefined){Config.TextoCheckbox = "";}
		var element =  document.getElementById(Config.HijoDeReactivo);
		if (typeof(element) != 'undefined' && element != null){
			element.remove();
		}
		var tabla = document.createElement("TABLE");
		tabla.setAttribute("id", Config.HijoDeReactivo);
		tabla.style.display="contents";
		//tabla.style.display="contents";
		
		if(Config.hidden){
			tabla.style.display="none";
		}
		//alert(Config.Reactor);
		var IidPadre = document.getElementById(Config.Reactor);
		if( typeof(IidPadre) == 'undefined' || IidPadre == null ){
			EndLoading;
			//alert("IidPadre: undefined or null");
			return;
		}
		IidPadre.appendChild(tabla);
		IidPadre.setAttribute("style", "width:auto;");
		var Filas = new Array();
		var Filas = Resultado.split(";");
		var item = new Array();
		Columnas = Filas[0].split("|");
		var fila1 = document.createElement("TR");
		fila1.setAttribute("id", "TR_Menues"+Config.HijoDeReactivo);
		
		var DiferenciaDeDatos;
		if(Config.Retorno != Resultado){
			DiferenciaDeDatos = true;
			Config.Filtro = "[]";
		}else{
			DiferenciaDeDatos = false;
		}
		
		if(Config.Scaner){
			var fila1 = document.createElement("TR");
			fila1.setAttribute("id", "Scaner"+Config.HijoDeReactivo);
			fila1.onkeyup = function(){
				var ArraydDeFiltros = new Array();
				var ArraydDeFiltrosValores = new Array();
				inputs = this.getElementsByTagName('input');
				for(var i = 0 ; i < inputs.length ; i++){
					if(inputs[i].value != ""){
						//alert("Columna=" + inputs[i].Columna + " value=" + inputs[i].value);
						ArraydDeFiltros.push(inputs[i].Columna);
						ArraydDeFiltrosValores.push(inputs[i].value);
					}
				}
				var Filtro = ArraidNameValueToJSON(ArraydDeFiltros,ArraydDeFiltrosValores);
				Config.Filtro = Filtro;
				fila1.Config = Config;
				//console.log(this.Config);
				//var LlamadoAFuncion = ;
				if(typeof(LlamadoAFuncion) != 'undefined'){
					clearTimeout(LlamadoAFuncion);
				}
				LlamadoAFuncion = setTimeout(function(){ Test(Config,1); this.focus();}, 3000);
			};
			if(DiferenciaDeDatos){
				item[0] = Filas[0].split("|");
				if(!Config.Filtro != null){
					//alert("Sin Filtro");
					for(var j = 0;j<item[0].length;j++){
						var Columna = document.createElement("TD");
						Columna.setAttribute("id", "Scaner"+j);
						var input = document.createElement("input");
						input.type="text";
						input.className="form-control form-control-sm";
						input.placeholder=item[0][j];
						input.Columna = j;
						Columna.appendChild(input);
						fila1.appendChild(Columna);
					}
					
				}
				document.getElementById(Config.HijoDeReactivo).appendChild(fila1);
			}
			if(!DiferenciaDeDatos){
				item[0] = Filas[0].split("|");
				if(Config.Filtro != null){
					//alert("Con Filtro");
					var JsonFiltros = JsonExtraerArrayd(JSON.parse(Config.Filtro));
					for(var j = 0;j<item[0].length;j++){
						var Columna = document.createElement("TD");
						Columna.setAttribute("id", "Scaner"+j);
						var input = document.createElement("input");
						input.type="text";
						input.className="form-control form-control-sm";
						input.placeholder=item[0][j];
						input.Columna = j;
						for(var k = 0;k<JsonFiltros[0].length;k++){
							if(JsonFiltros[0][k]==j){
								//console.log(JsonFiltros);
								//alert("JsonFiltros[0][k]" + JsonFiltros[0][k] + " k" + k + " j " + j);
								input.value = JsonFiltros[1][k];
								var Filtrar = true;
								
								for(var filas = 1 ; filas<Filas.length ; filas ++){
									item[filas] = Filas[filas].split("|");
									if(item[filas][j].indexOf(JsonFiltros[1][k]) >= 0 ){
										//alert("Item=" + item[filas][j] + " Filtro=" + JsonFiltros[1][k]);
										Filtrar = false;
										//break;
									}else{
										//alert("falso =" + Filas[filas]);
										Filas.splice(filas, 1);
										filas--;
									}
								}
								
								//if(Filtrar){
								//	Filas[filas].splice(i, 1); 
								//}
								
							}
						}
						//input.value = Config.Filtro[j];
						Columna.appendChild(input);
						fila1.appendChild(Columna);
					}
				}
				document.getElementById(Config.HijoDeReactivo).appendChild(fila1);
			}
		}
		if(Config.Retorno != undefined && Config.Retorno != Resultado  ){
			Config.Retorno = Resultado;
			Config.Pagina = 0;
			//alert("Pasa");
			//console.log(Config);
		}
		//console.log(Config);
		if(Config.Reactivo == undefined && DiferenciaDeDatos){
			Config.TextoCheckbox = "";
			if(Config.MaximizeBox){
				$("#"+Config.MaximizeElement).unbind('click');
				var HideElement = document.getElementById(Config.MaximizeElement);
				var content = HideElement.nextElementSibling;
				HideElement.innerHTML = "Desplegar Resultados";
				HideElement.setAttribute("readonly", "");
				content.style.maxHeight = null;
				content.style.backgroundColor = "#ffe0de";
			}
			if(Config.MaximizeBox){
				var HideElement = document.getElementById(Config.MaximizeElement);
				HideElement.removeAttribute("readonly", "");
				$("#"+Config.MaximizeElement).unbind('click');
				$("#"+Config.MaximizeElement).click(function(){
					this.classList.toggle("active");
					var $this = $(this);
					if($this.data('clicked')) {
						var content = this.nextElementSibling;
						if (content.style.maxHeight){
							this.innerHTML = "Desplegar Resultados";
							content.style.maxHeight = null;
							content.style.backgroundColor = "#ffe0de";
						} else {
							this.innerHTML = "Minimizar Resultados";
							content.style.maxHeight = content.scrollHeight + "px";
							content.style.backgroundColor = null;
						} 
					}else{
						$this.data('clicked', true);
						var content = this.nextElementSibling;
						if (content.style.maxHeight){
							this.innerHTML = "Desplegar Resultados";
							content.style.maxHeight = null;
							content.style.backgroundColor = "#ffe0de";
						} else {
							this.innerHTML = "Minimizar Resultados";
							content.style.maxHeight = content.scrollHeight + "px";
							content.style.backgroundColor = null;
						} 
					}
				});
			}
		}
		var Paginador = document.getElementById(Config.Paginador);
		if(Filas.length-1>Config.MaximoDeFilas && (typeof(Paginador) != 'undefined' && Paginador != null) ){
			//alert("Config.MaximoDeFilas=" + Config.MaximoDeFilas);
			if(DiferenciaDeDatos){
				FuncionDePaginado(Config, Filas.length );
			}
		}else{
			//alert("else");
			if(Config){
				if(Config.Paginador != undefined && Config.Paginador != ""){
					var Paginador = document.getElementById(Config.Paginador);
					if (typeof(Paginador) != 'undefined' && Paginador != null){
						Paginador.style.display="block";
						Paginador.innerHTML = "";
					}
				}
			}
		}
		if(Config.shortT){
			for(var iBN = 0;iBN<Columnas.length;iBN++){
				var Columna = document.createElement("TD");
				Columna.setAttribute("id", "TR_Menues_"+iBN);
				var Texto = document.createTextNode("Menu");
				Columna.appendChild(Texto);
				fila1.appendChild(Columna);
			}
			document.getElementById(Config.HijoDeReactivo).appendChild(fila1);
		}
		
		var i = 0;
		var fila = document.createElement("TR");
		fila.setAttribute("id", "TR_"+i);
		item[i] = Filas[i].split("|");
		for(var j = 0;j<item[i].length;j++){
			var Columna = document.createElement("TH");
			Columna.setAttribute("id", "TD_"+i);
			var Texto = document.createTextNode(item[i][j]);
			Columna.appendChild(Texto);
			fila.appendChild(Columna);
		}
		
		
		if(i==0 && Config.CheckBox){
			var Columna = document.createElement("TH");
			Columna.setAttribute("id", "TH_"+i);
			//<input type="checkbox" name="vehicle1" value="Bike">
			var Checkbox = document.createElement("input");
			Checkbox.type = "checkbox";
			Checkbox.name = "SelectAll";
			Checkbox.className="cb";
			Checkbox.value = i;
			Checkbox.onclick = function(){
				if(this.checked == true){
					//alert("Marcar Todo");
					var TextCheck;
					if(Config.IdCheckBox != undefined){
						TextCheck = document.getElementById(Config.IdCheckBox+"TextoCheckBoxAll");
					}else{
						TextCheck = document.getElementById("TextoCheckBoxAll");
					}
					TextCheck.innerHTML = "Desmarcar Todo";
					for(var f =1; f<i;f++){
						if(Config.IdCheckBox != undefined){
							if(document.getElementById(Config.IdCheckBox+"CheckBoxTabla"+f) == undefined){//Error 128973128391625312848717835481612228371263614237616298748234
								//alert(IdCheckBox+"CheckBoxTabla"+f+": undefined Error: 128973128391625312848717835481612228371263614237616298748234");
								return;
							}
							document.getElementById(Config.IdCheckBox+"CheckBoxTabla"+f).checked=true;
						}else{
							if(document.getElementById("CheckBoxTabla"+f) == undefined){//Error 182612377156346290483896452634819438103742657
								//alert(IdCheckBox+"CheckBoxTabla"+f+": undefined Error: 182612377156346290483896452634819438103742657");
								return;
							}
							document.getElementById("CheckBoxTabla"+f).checked=true;
						}
					}
				}else{
					var TextCheck;
					if(Config.IdCheckBox != undefined){
						TextCheck = document.getElementById(Config.IdCheckBox+"TextoCheckBoxAll");
					}else{
						TextCheck = document.getElementById("TextoCheckBoxAll");
					}
					TextCheck.innerHTML = "Marcar Todo";
					for(var f =1; f<i;f++){
						if(Config.IdCheckBox != undefined){
							if(document.getElementById(Config.IdCheckBox+"CheckBoxTabla"+f) == undefined){//error 1892644258429346234810283891284763409274568920
								//alert(IdCheckBox+"CheckBoxTabla"+f+": undefined Error: 1892644258429346234810283891284763409274568920'48209374716234");
								return;
							}
							document.getElementById(Config.IdCheckBox+"CheckBoxTabla"+f).checked=false;
						}else{
							if(document.getElementById("CheckBoxTabla"+f) == undefined){//error 298364626348982341729038781725646828309283478238479821348234
								//alert(IdCheckBox+"CheckBoxTabla"+f+": undefined Error: 298364626348982341729038781725646828309283478238479821348234'48209374716234");
								return;
							}
							document.getElementById("CheckBoxTabla"+f).checked=false;
						}
					}
				}
			};
			Columna.appendChild(Checkbox);
			var TextCheck = document.createElement("b");
			TextCheck.innerHTML = "Marcar Todo";
			if(Config.IdCheckBox != undefined){
				TextCheck.id = Config.IdCheckBox+"TextoCheckBoxAll";
			}else{
				TextCheck.id = "TextoCheckBoxAll";
			}
			Columna.appendChild(TextCheck);
			fila.appendChild(Columna);
		}
		
		document.getElementById(Config.HijoDeReactivo).appendChild(fila);
		
		
		
		var DescuentoDeFila = 0;
		if(Config.Pagina>0){
			DescuentoDeFila =  -(Config.Pagina*Config.MaximoDeFilas);
		}
		//console.log(Config);
		for(var i = (Config.Pagina*Config.MaximoDeFilas)+1; i<Filas.length && i<= ((Config.Pagina+1)*Config.MaximoDeFilas) ; i++){
			var fila = document.createElement("TR");
			fila.setAttribute("id", "TR_"+(i+DescuentoDeFila));
			item[i] = Filas[i].split("|");
			for(var j = 0;j<item[i].length;j++){
				if(i==0){
					var Columna = document.createElement("TH");
				}else{
					var Columna = document.createElement("TD");
				}
				Columna.setAttribute("id", "TD_"+(i+DescuentoDeFila));
				
				if( item[i][j].search("LinckEnTabla=") == 0 ){
					var Link = document.createElement("a");
					Link.href = item[i][j].replace("LinckEnTabla=", "");
					Link.innerHTML = "Ir A Google Maps";
					Link.target="_blank";
					Columna.appendChild(Link);
					fila.appendChild(Columna);
					
				}
				 
				//alert("search('EmergenteEnTabla=')" + item[i][j]);
				if( item[i][j].search("EmergenteEnTabla=") == 0 ){
					var submit = document.createElement("input");
					submit.type = "submit";
					submit.value = "Ver Datos De Pieza";
					//submit.className="btn btn-default";
					submit.className="btn btn-secondary";
					submit.valueAjax = item[i][j].replace("EmergenteEnTabla=", "");//.replace("FLASH ", "");
					submit.valueFila = (i+DescuentoDeFila);
					submit.setAttribute("data-toggle", "modal");
					submit.setAttribute("data-target", "#ModalDatos");
					
					submit.onclick = function(){
						if(this.valueAjax !=""){
							var ArraydDestino = this.valueAjax.replace(/[(]/g, "").replace(/[)]/g, "").split("[,]");
							
							var valueFila = this.valueFila;
							if(ArraydDestino.length>0){
								var Alert="";
								for(var JSMJSTEST = 0 ;JSMJSTEST<ArraydDestino.length; JSMJSTEST++){
									Alert=Alert+"("+JSMJSTEST+") " + ArraydDestino[JSMJSTEST];
								}
								//alert(Alert);
								var IdPieza = ArraydDestino[0];
								var BarcodeExterno = ArraydDestino[1];
								var Destinatario = ArraydDestino[2];
								var Documento = ArraydDestino[3];
								var Domicilio = ArraydDestino[4];
								var Estado = ArraydDestino[5];
								var FechaUltimoEstado = ArraydDestino[6];
								var Receptor = ArraydDestino[7];
								var DocumentoReceptor = ArraydDestino[8];
								var Parentesco = ArraydDestino[9];
								document.getElementById("DatosDePieza").innerHTML = IdPieza;
								document.getElementById("ApellidoYNombre").value = Destinatario;
								document.getElementById("Domicilio").value = Domicilio;
								document.getElementById("NumPiezaCorreo").value = BarcodeExterno;
								document.getElementById("EstadoPieza").value = Estado;
								document.getElementById("FechaEstadoPieza").value = FechaUltimoEstado;
								document.getElementById("recibio").value = Receptor;
								document.getElementById("documento").value = DocumentoReceptor;
								document.getElementById("vinculo").value = Parentesco;
								
								//GeoCodificar(id,calle,localidad,pais,valueFila);
								var NumeroDePieza = document.getElementById("NumeroDePieza");
								var Desde = document.getElementById("Desde");
								var value = document.getElementById("value");
								var Hasta = document.getElementById("Hasta");
								var Destinatario = document.getElementById("Destinatario");
								
								filtro=["UserId","IdPieza","time","NoMemory"];
								filtroX=[UserId,IdPieza,time,NoMemory];
								var Parametros = ArraidNameValueToJSON(filtro,filtroX);
								var pagina = 0;
								//var MaximoDeFilas = document.getElementById("CantidadDeResultadosEnTabla").value;
								var MaximoDeFilas = 50;
								if(MaximoDeFilas>0){}else{MaximoDeFilas=1;}
								
								var Config = JSON.parse(`
								{
									"Reactivo":null,
									"HijoDeReactivo":"TablaDeDetalle",
									"Reactor":"MainTablaDetalle",
									"TextoCheckbox":"",
									"CheckBox":false,
									"hidden":false,
									"shortT":false,
									"Scaner":false,
									"MaximoDeFilas":"` + MaximoDeFilas + `",
									"MaximizeBox":true,
									"MaximizeElement":"MaximizeBoxDetalle",
									"Paginador":"DivPaginadorDetalle",
									"Pagina":"` + pagina + `",
									"DataAjax":` + Parametros + `,
									"Retorno":"Limpiar Tabla",
									"Ajax":"ConsultasClientes/AjaxGetEstadosDePiezas.php",
									"Iniciar":true
								}
								`);
								AjaxTabla(Config);
								//console.log(Config);
							}
						}
					};
					Columna.appendChild(submit);
					fila.appendChild(Columna);
					
				}
				
				
				if( item[i][j].search("SubmitEnTabla=") == 0 ){
					var submit = document.createElement("input");
					submit.type = "submit";
					submit.value = "Geocodificar";
					submit.className="Geocodificar btn btn-info";
					submit.valueDestino = item[i][j].replace("SubmitEnTabla=", "").replace("FLASH ", "");
					submit.valueFila = (i+DescuentoDeFila);
					
					submit.setAttribute("data-toggle", "modal");
					submit.setAttribute("data-target", "#CalleAGPS");
					
					submit.onclick = function(){
						if(this.valueDestino !=""){
							var ArraydDestino = this.valueDestino.replace(/[(]/g, "").replace(/[)]/g, "").split(",");
							var valueFila = this.valueFila;
							if(ArraydDestino.length>0){
								var id = ArraydDestino[0];
								var calle = ArraydDestino[1];
								var localidad = ArraydDestino[2];
								var pais = ArraydDestino[3];
								GeoCodificar(id,calle,localidad,pais,valueFila);
							}
						}
					};
					Columna.appendChild(submit);
					fila.appendChild(Columna);
					
				}
				if( item[i][j].search("EmergenteEnTabla=") == 0 || item[i][j].search("SubmitEnTabla=") == 0 || item[i][j].search("LinckEnTabla=") == 0 ){
					
				}else{
					var Texto = document.createTextNode(item[i][j]);
					Columna.appendChild(Texto);
					fila.appendChild(Columna);
				}
			}
			if(i==0 && Config.CheckBox){
				var Columna = document.createElement("TH");
				Columna.setAttribute("id", "TH_"+(i+DescuentoDeFila));
				//<input type="checkbox" name="vehicle1" value="Bike">
				var Checkbox = document.createElement("input");
				Checkbox.type = "checkbox";
				Checkbox.name = "SelectAll";
				Checkbox.className="cb";
				Checkbox.value = i;
				Checkbox.onclick = function(){
					if(this.checked == true){
						//alert("Marcar Todo");
						var TextCheck = document.getElementById("TextoCheckBoxAll");
						TextCheck.innerHTML = "Desmarcar Todo";
						for(var f =1; f<i;f++){
							document.getElementById("CheckBoxTabla"+f).checked=true;
						}
					}else{
						var TextCheck = document.getElementById("TextoCheckBoxAll");
						TextCheck.innerHTML = "Marcar Todo";
						for(var f =1; f<i;f++){
							document.getElementById("CheckBoxTabla"+f).checked=false;
						}
					}
				};
				Columna.appendChild(Checkbox);
				var TextCheck = document.createElement("b");
				TextCheck.innerHTML = "Marcar Todo";
				TextCheck.id = "TextoCheckBoxAll";
				Columna.appendChild(TextCheck);
				fila.appendChild(Columna);
			}
			
			if(i>0 && Config.CheckBox){
				var Columna = document.createElement("TD");
				//Columna.setAttribute("id", "TD_"+i);
				Columna.setAttribute("id", "TD_"+(i+DescuentoDeFila));
				
				var Checkbox = document.createElement("input");
				Checkbox.type = "checkbox";
				//Checkbox.id = "CheckBoxTabla"+i;
				if(Config.IdCheckBox != undefined){
					Checkbox.id = Config.IdCheckBox+"CheckBoxTabla"+(i+DescuentoDeFila);
				}else{
					Checkbox.id = "CheckBoxTabla"+(i+DescuentoDeFila);
				}
				//Checkbox.id = "CheckBoxTabla"+(i+DescuentoDeFila);
				Checkbox.className="cb";
				//Checkbox.value = i;
				Checkbox.value = (i+DescuentoDeFila);
				Checkbox.checked=false;
				Columna.appendChild(Checkbox);
				var TextCheck = document.createTextNode(Config.TextoCheckbox);
				Columna.appendChild(TextCheck);
				fila.appendChild(Columna);
			}
			
			document.getElementById(Config.HijoDeReactivo).appendChild(fila);
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
	
	
	function AjaxArraidToSelectSync(SelectId,Ajax,Sync = true){
		Loading();
		var time = 0;
		var xhttp;
		xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function(){
			if (this.readyState == 4 && this.status == 200){
				var Resultado = this.responseText.trim();
				//alert("Resultado Encontrado:" + Resultado);
				AderirSelect(SelectId,Resultado.split(";"))
				EndLoading();
			}else{if(this.readyState == 4){
					window.location="403forbidden";
					EndLoading();
				}
			}
		};
		var date = new Date();var DateNumber = date.getTime();
		var DataText = DataText+'&'+'DateNumber='+DateNumber;
		xhttp.open("GET", Ajax+
		"?Time="+
		time+
		DataText
		, Sync);
		xhttp.send();
	}
	
	function AderirSelect(SelectId,Arrayd) {
		
		
		var x = document.getElementById(SelectId);
		if(x == null){return;}
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
			
			//alert("Resultado Encontrado");
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
			x.removeAttribute("readonly", "");
			//x.attr("readonly");
			//x.attr("readonly") = 'false'
		}else{
			//alert("Sin Resultados");
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
	function AjaxArraidToSelect(SelectId,Ajax){
		Loading();
		var time = 0;
		var xhttp;
		xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function(){
			if (this.readyState == 4 && this.status == 200){
				var Resultado = this.responseText.trim();
				//alert("Resultado Encontrado:" + Resultado);
				AderirSelect(SelectId,Resultado.split(";"))
				EndLoading();
			}else{if(this.readyState == 4){
					window.location="403forbidden";
					EndLoading();
				}
			}
		};
		var date = new Date();var DateNumber = date.getTime();
		var DataText = DataText+'&'+'DateNumber='+DateNumber;
		xhttp.open("GET", Ajax+
		"?Time="+
		time+
		DataText
		, true);
		xhttp.send();
	}
	
	
	//Config.ElementId Config.ElementTexto Config.Value Config.TextoInicial
	function ChangeValue(Config){
		//alert(Config.ElementTexto);
		ElementTexto = document.getElementById(Config.ElementTexto);
		ElementTexto.innerHTML = "<b style='color:#FF0000;font-size: 10px;width: 90%;'>"+Config.TextoInicial+"</b>";
		if(this.tagName=="INPUT"){
			//alert(this.tagName);
			if($("#"+Config.ElementId).attr('type') == "text"){
				this.value = this.value.replace(/[^a-zA-Z0-9ññÑÑ  ]+/g, "");
			}
			if($("#"+Config.ElementId).attr('type') == "number"){
				this.value = this.value.replace(/[^0-9,,..]+/g, "");
			}
		}else{
			//alert(this.tagName);
		}
		$("#"+Config.ElementId).change(function() {
			if (this.value < Config.Value) {
				Config.ElementTexto.innerHTML = "<b style='color:#FF0000;font-size: 10px;width: 90%;'>"+Config.TextoInicial+"</b>";
			}
			else {
				//alert(this.value );
				ElementTexto.innerHTML = "";
			}
			var paragrap = document.getElementById("Paragrap");
			if (typeof(paragrap) != 'undefined' && paragrap != null){
				paragrap.innerHTML = "<b style='color: rgb(0, 0, 255);font-size:12px;'></b>";
			}
			//paragrap.innerHTML = "<b style='color: rgb(0, 0, 255);font-size:12px;'></b>";
		});
	}
	
	
	//#
	function CopiarObjeto(src) {
		return Object.assign({}, src);
	}
	var focused_element = null;
	function ObtenerFoco(){
		if(
			document.hasFocus() &&
			document.activeElement !== document.body &&
			document.activeElement !== document.documentElement
		){
			focused_element = document.activeElement;
		}
	}
	
	function RuTablaBoton(Config = false){
		$("#"+Config.Select).val('0');
		$("#"+Config.Select).val('1');
		$("#"+Config.Select).trigger('change');
		//RuTablaDesdeSelect(Config);
		$("#"+Config.Select).Config = Config;
		//$("#"+Config.Select).change();
	}
	
	
	function RuTablaDesdeSelect(Config = false){
		$("#"+Config.Select).change(function() {
			if (this.value == 0) {
				var DivDeTabla =  document.getElementById(Config.DivDeTabla);
				DivDeTabla.innerHTML = "";
				var DivPaginador =  document.getElementById(Config.DivPaginador);
				DivPaginador.innerHTML = "";
				if(Config.RuMaximizeBox){
					$("#"+Config.MaximizeElement).unbind('click');
					var HideElement = document.getElementById(Config.MaximizeElement);
					var content = HideElement.nextElementSibling;
					HideElement.innerHTML = "Desplegar Resultados";
					HideElement.setAttribute("readonly", "");
					content.style.maxHeight = null;
					content.style.backgroundColor = "#ffe0de";
				}
			}
			return;
		});
		$("#"+Config.Select).change(function() {
			//console.log(Config);
			if (this.value == 0) {return;}
			if(this.Config == undefined){
				this.Config = CopiarObjeto(Config);
			}
			Config = CopiarObjeto(this.Config);
			if(Config == false){return;}
			if(Config.Select == undefined){return;}
			if(Config.Tabla == undefined){return;}
			if(Config.DivDeTabla == undefined){return;}
			if( Config.RuCheckBox == true && Config.RuTextoCheckbox == undefined){Config.RuTextoCheckbox = "Falta Config.TextoCheckbox";}
			if(Config.RuCheckBox == undefined){Config.RuCheckBox = false;}
			if(Config.RuMaximizeBox == undefined){}
			if(Config.DivPaginador == undefined){}
			if(Config.ElementoMaximoDeFilas == undefined){
			}else{
				Config.MaximoDeFilas = document.getElementById(Config.ElementoMaximoDeFilas).value;
				if(Config.MaximoDeFilas == 0 ){Config.MaximoDeFilas = 5000}
			}
			/*
			filtro=["id","time"];
			filtroX=[this.value,"0"];
			if(Config.MaximoDeFilas<=5000){
				filtro.push("MaximoDeFilas");
				filtroX.push(Config.MaximoDeFilas);
			}
			if(Config.MaximoDeFilas>1){
				filtro.push("Pagina");
				var Temp = Config.Pagina*Config.MaximoDeFilas;
				filtroX.push(Temp);
			}
			*/
			//var StringJson = ArraidNameValueToJSON(filtro,filtroX);
			//Config.DataAjax = JSON.parse(StringJson);
			RuAjaxTabla(Config);
			if(Config.MaximizeBox){
				var HideElement = document.getElementById(Config.MaximizeElement);
				HideElement.removeAttribute("readonly", "");
				$("#"+Config.MaximizeElement).unbind('click');
				$("#"+Config.MaximizeElement).click(function(){
					this.classList.toggle("active");
					var $this = $(this);
					if($this.data('clicked')) {
						var content = this.nextElementSibling;
						if (content.style.maxHeight){
							this.innerHTML = "Desplegar Resultados";
							content.style.maxHeight = null;
							content.style.backgroundColor = "#ffe0de";
						} else {
							this.innerHTML = "Minimizar Resultados";
							content.style.maxHeight = content.scrollHeight + "px";
							content.style.backgroundColor = null;
						} 
					}else{
						$this.data('clicked', true);
						var content = this.nextElementSibling;
						if (content.style.maxHeight){
							this.innerHTML = "Desplegar Resultados";
							content.style.maxHeight = null;
							content.style.backgroundColor = "#ffe0de";
						} else {
							this.innerHTML = "Minimizar Resultados";
							content.style.maxHeight = content.scrollHeight + "px";
							content.style.backgroundColor = null;
						} 
					}
				});
			}
		});
	}
	
	function RuAjaxTabla(Config = false){
		Loading();
		var xhttp;
		xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function(){
			if (this.readyState == 4 && this.status == 200){
				var Resultado = this.responseText.trim();
				if(Resultado=="Sin Resultados" || Resultado=="NULL"){
					var element =  document.getElementById(Config.HijoDeReactivo);
					if (typeof(element) != 'undefined' && element != null){
						element.remove();
					}
					if (typeof $.bootstrapGrowl === "function") {
						$.bootstrapGrowl("Sin Resultados", {
							type: 'danger',//danger
							align: 'center',
							width: 'auto'
						});
					}
				}else{
					RuAutoTabla(Config,Resultado);
					if (typeof $.bootstrapGrowl === "function") {
						$.bootstrapGrowl("Resultado Encontrado", {
							type: 'success',//danger
							align: 'center',
							width: 'auto'
						});
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
		
		
		if(Config.ValoresDirectos != null){
			var arraydObjetos = new Array();
			arraydObjetos = JsonExtraerArraydDeValores(Config.ValoresDirectos);
			var TotalDeElementos = Config.DataAjax.length;
			for(var i = 0 ; i < Objetos.length ; i++){
				
				Config.DataAjax[TotalDeElementos + (i*1)] = JSON.parse("{\"" + Objetos[i] + "\":\"" + document.getElementById(Objetos[i]).value + "\"}");
				//alert(Objetos[i]);
			}
		}
		//console.log(Config.DataAjax);
		//Config.DataAjax.Add(pair.Key)
		
		DataText = ArrayNameValueJSONToPostString(Config.DataAjax);
		var date = new Date();var DateNumber = date.getTime();
		if(DataText==''){
			DataText = DataText = DataText+'?'+'DateNumber='+DateNumber;
		}else{
			DataText = DataText+'&'+'DateNumber='+DateNumber;
		}
		console.log(Config.Ajax+DataText);
		xhttp.open("GET", Config.Ajax+DataText
		, true);
		xhttp.send();
	}
	
	function RuFuncionDePaginado(Config){
		var ConfigT = CopiarObjeto(Config);
		if(ConfigT.DivPaginador != undefined && ConfigT.DivPaginador != ""){
			var DivPaginador = document.getElementById(ConfigT.DivPaginador);
			DivPaginador.style.display="block";
			DivPaginador.innerHTML = "";
			var Ul = document.createElement("ul");
			Ul.id="Paginador";
			Ul.classList.add("pagination");
			var ContadorTemporal=1;
			var NumeroDeFilas = Config.Resultado.length;
			if(true){
				for(var i = 1; (i*1)-NumeroDeFilas < 0 ; i+=(ConfigT.MaximoDeFilas*1), ContadorTemporal++){
					var li = document.createElement("li");
					li.id = ContadorTemporal;
					var ElementA = document.createElement("a");
					ElementA.innerHTML = ContadorTemporal;
					ElementA.Item = ContadorTemporal;
					ElementA.ConfigT = ConfigT;
					ElementA.onclick = function() {
						var idDiv = this.parentElement.parentElement.id;
						$("#"+idDiv+">li.active").removeClass("active");
						ConfigT.Pagina = this.Item*1;
						var li = this.parentElement;
						li.classList.toggle("active");
						ConfigT.RuInicio = false;
						RuAutoTabla(ConfigT,"");
					};
					li.appendChild(ElementA);
					Ul.appendChild(li);
				}
				DivPaginador.appendChild(Ul);
			}
			
		}
	}
	
	function RuCrearTablaDesdeDiv(Config){
		var ConfigT = CopiarObjeto(Config);
		var ConfigTResultados = CopiarObjeto(document.getElementById(Config.DivDeTabla).Config);
		ConfigT.Resultado = Object.values(ConfigTResultados.Resultado);
		var tabla = document.createElement("TABLE");
		tabla.setAttribute("id", ConfigT.Tabla);
		if(document.getElementById("Escaner") == undefined){
			var Titulo = document.createElement("TR");
			Titulo.setAttribute("id", "Escaner");
			//Titulo.setAttribute("style", "Escaner");
			if(ConfigT.ConFiltro && ConfigT.RuFiltrado != true ){
				for(var j = 0;j<ConfigT.Titulos.length;j++){
					var Columna = document.createElement("TD");
					Columna.setAttribute("id", "Escaner"+j);
					var input = document.createElement("input");
					input.type="text";
					
					input.className="form-control form-control-sm";
					input.placeholder=ConfigT.Titulos[j];
					input.Columna = j;
					input.setAttribute("id", "Input_Escaner"+j);
					if(ConfigT.ConfigFiltro != 'undefined' && ConfigT.ConfigFiltro != null ){
						for(var k = 0 ; k < ConfigT.ConfigFiltro.ArraydDeFiltros.length ; k++ ){
							if(j==ConfigT.ConfigFiltro.ArraydDeFiltros[k]){
								input.value = ConfigT.ConfigFiltro.ArraydDeFiltrosValores[k];
							}
						}
					}
					
					var label = document.createElement("label");
					var span = document.createElement("span");
					var b = document.createElement("b");
					label.className="control-label";
					span.className="info";
					span.setAttribute("aria-required", "true");
					b.innerHTML = ConfigT.Titulos[j];
					span.appendChild(b);
					label.appendChild(span);
					Columna.appendChild(label);
					
					
					//TD_0
					Columna.appendChild(input);
					Titulo.onkeyup = function(){
						var ArraydDeFiltros = new Array();
						var ArraydDeFiltrosValores = new Array();
						inputs = this.getElementsByTagName('input');
						for(var i = 0 ; i < inputs.length ; i++){
							if(inputs[i].value != ""){
								ArraydDeFiltros.push(inputs[i].Columna);
								ArraydDeFiltrosValores.push(inputs[i].value);
							}
						}
						var Filtro = ArraidNameValueToJSON(ArraydDeFiltros,ArraydDeFiltrosValores);
						const ConfigFiltro = new Object;
						ConfigFiltro.ArraydDeFiltros = ArraydDeFiltros;
						ConfigFiltro.ArraydDeFiltrosValores = ArraydDeFiltrosValores;
						ConfigT.RuFiltrado = true;
						ConfigT.RuInicio = false;
						ConfigT.ConfigFiltro = ConfigFiltro;
						RuAutoTabla(ConfigT,"");
					};
					Titulo.appendChild(Columna);
				}
				DivDeTabla.appendChild(Titulo);
			}
		}
		if(ConfigT.ConfigFiltro != 'undefined' && ConfigT.ConfigFiltro != null ){
			if(ConfigT.ConfigFiltro.ArraydDeFiltros != 'undefined'){
				if(ConfigT.ConfigFiltro.ArraydDeFiltros.length>0 && ConfigT.ConfigFiltro.ArraydDeFiltrosValores.length>0){
					var Textos = Object.values(ConfigT.ConfigFiltro.ArraydDeFiltrosValores);
					var Columnas = Object.values(ConfigT.ConfigFiltro.ArraydDeFiltros);
					for(var i = 0 ; i < ConfigT.Resultado.length ; i++ ){
						var ElementoEnFila = true;
						var FilasActivas = new Array();
						for(var j = 0 ; j < Columnas.length ; j++ ){
							FilasActivas[j] = false;
						}
						for(var j = 0 ; j < Columnas.length ; j++ ){
							if(ConfigT.Resultado[i][Columnas[j]].toLowerCase().indexOf(Textos[j].toLowerCase() )>=0){
								//ElementoEnFila = true;
								FilasActivas[j] = true;
								//console.log("ConfigT.Resultado[i][Columnas[j]]:" + ConfigT.Resultado[i][Columnas[j]] + " Textos:" + Textos[j]);
								//console.log(Textos);
							}
						}
						for(var j = 0 ; j < FilasActivas.length ; j++ ){
							if(FilasActivas[j] == false){
								ElementoEnFila = false;
							}
						}
						if(!ElementoEnFila){
							ConfigT.Resultado.splice(i,1);
							i--;
						}
					}
				}
			}
		}
		if(ConfigT.RuConPaginado){
			var DivPaginador = document.getElementById(ConfigT.Paginador);
			var MaximoDeFilas = document.getElementById(ConfigT.ElementoMaximoDeFilas).value;
			ConfigT.MaximoDeFilas = MaximoDeFilas;
			var CantidadDeFilas = ConfigT.Resultado.length;
			RuFuncionDePaginado(ConfigT);
		}
		if(ConfigT.Resultado.length>0){
			var Resultados = Object.values(ConfigT.Resultado);
			if(ConfigT.Pagina == 0){
				Resultados = Resultados.splice(ConfigT.MaximoDeFilas*(ConfigT.Pagina),ConfigT.MaximoDeFilas);
				ConfigT.Resultado = Resultados;
			}else{
				Resultados = Resultados.splice(ConfigT.MaximoDeFilas*(ConfigT.Pagina-1),ConfigT.MaximoDeFilas);
				ConfigT.Resultado = Resultados;
			}
		}
		
		
		var Titulos = document.createElement("TR");
		for(var i = 0 ; i <ConfigT.Titulos.length ; i++ ){
			var Columna = document.createElement("TH");
			Columna.setAttribute("id", "Titulos_TH"+i);
			Columna.Numero = i;
			var Texto = document.createTextNode(ConfigT.Titulos[i]);
			Columna.appendChild(Texto);
			Titulos.appendChild(Columna);
		}
		
		//$( document ).width();
		
		
		
		//Check Bocks Inicial
		if(Config.CheckBox){
			var Columna = document.createElement("TH");
			Columna.setAttribute("id", "TH_"+i);
			//<input type="checkbox" name="vehicle1" value="Bike">
			var Checkbox = document.createElement("input");
			Checkbox.type = "checkbox";
			Checkbox.name = "SelectAll";
			Checkbox.className="cb";
			Checkbox.value = i;
			Checkbox.onclick = function(){
				if(this.checked == true){
					//alert("Marcar Todo");
					var TextCheck;
					if(Config.IdCheckBox != undefined){
						TextCheck = document.getElementById(Config.IdCheckBox+"TextoCheckBoxAll");
					}else{
						TextCheck = document.getElementById("TextoCheckBoxAll");
					}
					TextCheck.innerHTML = "Desmarcar Todo";
					for(var f =1; true;f++){
						if(document.getElementById(Config.IdCheckBox+"CheckBoxTabla"+f) == undefined){//Error 128973128391625312848717835481612228371263614237616298748234
							return;
							//alert(Config.IdCheckBox+"CheckBoxTabla"+f+": undefined Error: 128973128391625312848717835481612228371263614237616298748234");
						}
						document.getElementById(Config.IdCheckBox+"CheckBoxTabla"+f).checked=true;
					}
				}else{
					var TextCheck;
					if(Config.IdCheckBox != undefined){
						TextCheck = document.getElementById(Config.IdCheckBox+"TextoCheckBoxAll");
					}else{
						TextCheck = document.getElementById("TextoCheckBoxAll");
					}
					TextCheck.innerHTML = "Marcar Todo";
					for(var f =1; true;f++){
						if(document.getElementById(Config.IdCheckBox+"CheckBoxTabla"+f) == undefined){//error 298364626348982341729038781725646828309283478238479821348234
							return;
							//alert(Config.IdCheckBox+"CheckBoxTabla"+f+": undefined Error: 298364626348982341729038781725646828309283478238479821348234'48209374716234");
						}
						document.getElementById(Config.IdCheckBox+"CheckBoxTabla"+f).checked=false;
					}
				}
			};
			Columna.appendChild(Checkbox);
			var TextCheck = document.createElement("b");
			TextCheck.innerHTML = "Marcar Todo";
			if(Config.IdCheckBox != undefined){
				TextCheck.id = Config.IdCheckBox+"TextoCheckBoxAll";
			}else{
				TextCheck.id = "TextoCheckBoxAll";
			}
			Columna.appendChild(TextCheck);
		}
		Titulos.appendChild(Columna);
		tabla.appendChild(Titulos);
		
		
		for(var i = 0 ; i <ConfigT.Resultado.length ; i++ ){
			var fila = document.createElement("TR");
			for(var j = 0;j<ConfigT.Resultado[i].length;j++){
				if( ConfigT.Resultado[i][j].search("EmergenteEnTabla=") == 0 ){
					var submit = document.createElement("input");
					submit.type = "submit";
					submit.value = ConfigT.TextoEnBotonEmergente;
					//submit.className="btn btn-default";
					submit.className="btn btn-secondary";
					submit.valueAjax = ConfigT.Resultado[i][j].replace("EmergenteEnTabla=", "");//.replace("FLASH ", "");
					submit.valueFila = (i);
					submit.setAttribute("data-toggle", "modal");
					submit.setAttribute("data-target", "#ModalDatos");
					
					submit.onclick = function(){
						if(this.valueAjax !=""){
							//var ArraydDestino = this.valueAjax.split("[,]");	
							//var ArraydDestino = this.valueAjax.trim().replace(/[\[(\]]/g, "").replace(/[\[)\]]/g, "").split(",");
							
							var StringDestino = this.valueAjax.trim().replace(/[\[(\]]/g, "").replace(/[\[)\]]/g, "").trim();
							//ArraydDestino = ArraydDestino.split("),(");
							//ArraydDestino = ArraydDestino.replace(/[(]/g, "").replace(/[)]/g, "");
							var ArraydDestino = new Array();
							ArraydDestino = StringDestino.split(",");
							
							//var ArraydDestino = ArraydDestino.replace(/[(]/g, "").replace(/[)]/g, "").split("[,]");
							var valueFila = this.valueFila;
							if(ArraydDestino.length>0){
								var Alert="";
								for(var JSMJSTEST = 0 ;JSMJSTEST<ArraydDestino.length; JSMJSTEST++){
									Alert=Alert+"("+JSMJSTEST+") " + ArraydDestino[JSMJSTEST];
								}
								//alert(Alert);
								var ElementosDeEmergente = JsonExtraerArraydDeValores(ConfigT.ElementosDeEmergente);
								//ConfigT.ElementosDeEmergente
								for (var Elementos = 0 ; Elementos < ElementosDeEmergente.length ; Elementos++){
									//console.log(ElementosDeEmergente[Elementos]);
									ArraydDestino[Elementos] = ArraydDestino[Elementos].trim();
									document.getElementById(ElementosDeEmergente[Elementos]).innerHTML = ArraydDestino[Elementos];
									document.getElementById(ElementosDeEmergente[Elementos]).value = ArraydDestino[Elementos];
									//alert(ArraydDestino[Elementos]);
								}
								filtro=[ConfigT.IdentificadorDeEmergente,"UserId","time","NoMemory"];
								filtroX=[ArraydDestino[0],UserId,time,NoMemory];
								var Parametros = ArraidNameValueToJSON(filtro,filtroX);
								var pagina = 0;
								var Ajax = ConfigT.AjaxDeEmergente;
								//var MaximoDeFilas = document.getElementById("CantidadDeResultadosEnTabla").value;
								var MaximoDeFilas = 50;
								if(MaximoDeFilas>0){}else{MaximoDeFilas=1;}
								
								var Config = JSON.parse(`
								{
									"Reactivo":null,
									"HijoDeReactivo":"TablaDeDetalle",
									"Reactor":"MainTablaDetalle",
									"TextoCheckbox":"",
									"CheckBox":false,
									"hidden":false,
									"shortT":false,
									"Scaner":false,
									"MaximoDeFilas":"` + MaximoDeFilas + `",
									"MaximizeBox":true,
									"MaximizeElement":"MaximizeBoxDetalle",
									"Paginador":"DivPaginadorDetalle",
									"Pagina":"` + pagina + `",
									"DataAjax":` + Parametros + `,
									"Retorno":"Limpiar Tabla",
									"Ajax":"` + Ajax + `",
									"Iniciar":true
								}
								`);
								AjaxTabla(Config);
								//console.log(Config);
							}
						}
					};
					var Columna = document.createElement("TD");
					Columna.setAttribute("id", "TD_"+i);
					Columna.appendChild(submit);
					fila.appendChild(Columna);
					
				}else{
					var Columna = document.createElement("TD");
					Columna.setAttribute("id", "TD_"+i);
					var Texto = document.createTextNode(ConfigT.Resultado[i][j]);
					Columna.appendChild(Texto);
					fila.appendChild(Columna);
					
				}
				
				
			}
			if(Config.CheckBox){
				var Columna = document.createElement("TD");
				Columna.setAttribute("id", "TD_"+(i+1));
				var Checkbox = document.createElement("input");
				Checkbox.type = "checkbox";
				if(Config.IdCheckBox != undefined){
					Checkbox.id = Config.IdCheckBox+"CheckBoxTabla"+(i+1);
				}else{
					Checkbox.id = "CheckBoxTabla"+(i+1);
				}
				Checkbox.className="cb";
				Checkbox.value = (i);
				Checkbox.checked=false;
				Columna.appendChild(Checkbox);
				var TextCheck = document.createTextNode(Config.TextoCheckbox);
				Columna.appendChild(TextCheck);
				fila.appendChild(Columna);
			}
			tabla.appendChild(fila);
		}
		
			
		
		DivDeTabla.appendChild(tabla);
		
		
		for(var i = 0 ; i <ConfigT.Titulos.length ; i++ ){
			var Tabla = document.getElementById(ConfigT.Tabla);
			
			new ResizeSensor(Tabla, function(){
				//console.log('Changed to ' + Tabla.clientWidth);
				var Columnas = Tabla.rows[0].cells.length ;
				
				for(var i = 0; i < Columnas ; i++){
					var columna = Tabla.rows[0].cells[i];
					var elementoEscaner = document.getElementById("Escaner"+i);
					if(elementoEscaner != null && elementoEscaner != undefined && typeof(elementoEscaner) != 'undefined'){
						elementoEscaner.width = columna.clientWidth;
					}
					//console.log(columna);
					//console.log('columna to ' + columna.clientWidth);
				}
			});
		}
		
		$('.select2').select2();
	}
	
	function RuAutoTabla(Config = false,Resultado){
		var ConfigT = CopiarObjeto(Config);
		var Inicial = ConfigT.RuInicio;
		var element =  document.getElementById(ConfigT.Tabla);
		if (typeof(element) != 'undefined' && element != null){
			element.remove();
		}
		var DivDeTabla =  document.getElementById(ConfigT.DivDeTabla);
		var DivDeTabla = document.getElementById(ConfigT.DivDeTabla);
		if( typeof(DivDeTabla) == 'undefined' || DivDeTabla == null ){//#1231243466676856364224
			EndLoading;
			return;
		}else{
		}
		var Filtrado = ConfigT.RuFiltrado;
		var Paginado = ConfigT.RuConPaginado;
		if(DivDeTabla.Config  == 'undefined' || DivDeTabla.Config == null){
			var Filas = Resultado.split(";");
			var Columnas = new Array();
			for(var i = 0; i < Filas.length ; i++ ){
				Columnas[i] = Filas[i].split("|");
			}
			ConfigT.Titulos = Columnas[0];
			Columnas.shift();
			ConfigT.Resultado = Columnas;
			DivDeTabla.Config = CopiarObjeto(ConfigT);
		}else{
			if(ConfigT.RuInicio){
				var Filas = Resultado.split(";");
				var Columnas = new Array();
				for(var i = 0; i < Filas.length ; i++ ){
					Columnas[i] = Filas[i].split("|");
				}
				ConfigT.Titulos = Columnas[0];
				Columnas.shift();
				ConfigT.Resultado = Columnas;
				DivDeTabla.Config = ConfigT;
			}
		}
		var Filtrado = ConfigT.RuFiltrado;
		var Paginado = ConfigT.RuConPaginado;
		if(!Inicial){
			if(Filtrado){
			}
			if(Paginado){
			}
			RuCrearTablaDesdeDiv(ConfigT);
		}else{
			RuCrearTablaDesdeDiv(ConfigT);
		}
		return;
	}
	//#
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	function ChangeCompleteTableCheckbox(Config = false){
		var Select = Config.Reactivo;
		
		if(Config){
			var stringConfig = JSON.stringify(Config);
		}else{
		}
		if(Config.TextoCheckbox == undefined){Config.TextoCheckbox = "";}
		$("#"+Select).change(function() {
			if (this.value == 0) {
				var element =  document.getElementById(Config.HijoDeReactivo);
				if (typeof(element) != 'undefined' && element != null){
					element.remove();
					if(Config.MaximizeBox){
						$("#"+Config.MaximizeElement).unbind('click');
						var HideElement = document.getElementById(Config.MaximizeElement);
						var content = HideElement.nextElementSibling;
						HideElement.innerHTML = "Desplegar Resultados";
						HideElement.setAttribute("readonly", "");
						content.style.maxHeight = null;
						content.style.backgroundColor = "#ffe0de";
					}
				}
			}
			else {
				filtro=["id","time"];
				filtroX=[this.value,"0"];
				if(Config.MaximoDeFilas<=5000){
					filtro.push("MaximoDeFilas");
					filtroX.push(Config.MaximoDeFilas);
				}
				if(Config.MaximoDeFilas>1){
					filtro.push("Pagina");
					var Temp = Config.Pagina*Config.MaximoDeFilas;
					filtroX.push(Temp);
				}
				var StringJson = ArraidNameValueToJSON(filtro,filtroX);
				Config.DataAjax = JSON.parse(StringJson);
				AjaxTabla(Config);//filtro,filtroX,Config.HijoDeReactivo,Config.Reactor,Config.Ajax,true,Config.TextoCheckbox,
				if(Config.MaximizeBox){
					var HideElement = document.getElementById(Config.MaximizeElement);
					HideElement.removeAttribute("readonly", "");
					$("#"+Config.MaximizeElement).unbind('click');
					$("#"+Config.MaximizeElement).click(function(){
						this.classList.toggle("active");
						var $this = $(this);
						if($this.data('clicked')) {
							var content = this.nextElementSibling;
							if (content.style.maxHeight){
								this.innerHTML = "Desplegar Resultados";
								content.style.maxHeight = null;
								content.style.backgroundColor = "#ffe0de";
							} else {
								this.innerHTML = "Minimizar Resultados";
								content.style.maxHeight = content.scrollHeight + "px";
								content.style.backgroundColor = null;
							} 
						}else{
							$this.data('clicked', true);
							var content = this.nextElementSibling;
							if (content.style.maxHeight){
								this.innerHTML = "Desplegar Resultados";
								content.style.maxHeight = null;
								content.style.backgroundColor = "#ffe0de";
							} else {
								this.innerHTML = "Minimizar Resultados";
								content.style.maxHeight = content.scrollHeight + "px";
								content.style.backgroundColor = null;
							} 
						}
					});
				}
			}
			var paragrap = document.getElementById("Paragrap");
			if (typeof(paragrap) != 'undefined' && paragrap != null){
				paragrap.innerHTML = "<b style='color: rgb(0, 0, 255);font-size:12px;'></b>";
			}
		});
	}
	//Config.TextoCheckbox Config,Resultado Config.DataAjax Config.Ajax Config.HijoDeReactivo Config.Reactor Config.CheckBox
	function AjaxTabla(Config = false){//DataNombre,Data,NuevoNombre,Div,Ajax,checkbox,TextoCheckbox,
		if(Config.TextoCheckbox == undefined){Config.TextoCheckbox = "";}
		Loading();
		var xhttp;
		xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function(){
			if (this.readyState == 4 && this.status == 200){
				var Resultado = this.responseText.trim();
				if(Resultado=="Sin Resultados" || Resultado=="NULL"){
					var element =  document.getElementById(Config.HijoDeReactivo);
					if (typeof(element) != 'undefined' && element != null){
						element.remove();
					}
					if (typeof $.bootstrapGrowl === "function") {
						$.bootstrapGrowl("Sin Resultados", {
							type: 'danger',//danger
							align: 'center',
							width: 'auto'
						});
					}
				}else{
					
					AutoTabla(Config,Resultado);//Config.HijoDeReactivo,Config.Reactor,Resultado,false,false,Config.CheckBox,Config.TextoCheckbox,Config
					if (typeof $.bootstrapGrowl === "function") {
						$.bootstrapGrowl("Resultado Encontrado", {
							type: 'success',//danger
							align: 'center',
							width: 'auto'
						});
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
		DataText = ArrayNameValueJSONToPostString(Config.DataAjax);//console.log(Config);
		
		var date = new Date();var DateNumber = date.getTime();
		if(DataText==''){
			DataText = DataText = DataText+'?'+'DateNumber='+DateNumber;
		}else{
			DataText = DataText+'&'+'DateNumber='+DateNumber;
		}
		//alert("PAsa");
		//console.log(Config.Ajax+DataText);
		//console.log(Config.DataAjax);
		
		xhttp.open("GET", Config.Ajax+DataText
		, true);//false sin asincronia
		xhttp.send();
	}
	
	//Config
	function Needed(ElementId,DigitosMinimos,MSJ="Complete El Campo"){
		VirtualElementId = document.getElementById(ElementId);
		//ElementTexto.innerHTML = "<b style='color:#FF0000;font-size: 10px;width: 90%;'>"+TextoInicial+"</b>";
		if(VirtualElementId == null){
			if (typeof $.bootstrapGrowl === "function") {
				$.bootstrapGrowl( "El Elemento " + ElementId + " No Existe", {
					type: 'danger',//danger
					align: 'center',
					width: 'auto'
				});
			}
			return false;
		}
		if (VirtualElementId.value.length <= 0) {
			if(VirtualElementId.tagName=="INPUT"){
				if(VirtualElementId.getAttribute('type') == "file"){
					if (typeof $.bootstrapGrowl === "function") {
						$.bootstrapGrowl( "Seleccione Archivo", {
							type: 'danger',//danger
							align: 'center',
							width: 'auto'
						});
					}
					VirtualElementId.focus();
				}else{
					if (typeof $.bootstrapGrowl === "function") {
						$.bootstrapGrowl( MSJ, {
							type: 'danger',//danger
							align: 'center',
							width: 'auto'
						});
					}
					VirtualElementId.focus();
				}
			}else{
				if (typeof $.bootstrapGrowl === "function") {
					$.bootstrapGrowl( MSJ, {
						type: 'danger',//danger
						align: 'center',
						width: 'auto'
					});
				}
				VirtualElementId.focus();
			}
			
			return false;
		}
		else {
			if(VirtualElementId.value.length<DigitosMinimos){
				if (typeof $.bootstrapGrowl === "function") {
					$.bootstrapGrowl( "Faltan: " + (  DigitosMinimos - VirtualElementId.value.length ) + " Digitos En Este Campo", {
						type: 'danger',//danger
						align: 'center',
						width: 'auto'
					});
				}
				VirtualElementId.focus();
				return false;
			}else{
				var opciones = VirtualElementId.getElementsByTagName('option');
				if(VirtualElementId.tagName=="INPUT"){
					if(VirtualElementId.getAttribute('type') == "textCorreo"){
						if(!ValidateEmail(VirtualElementId.value)){
							if (typeof $.bootstrapGrowl === "function") {
								$.bootstrapGrowl( "Formato No Permitido", {
									type: 'danger',//danger
									align: 'center',
									width: 'auto'
								});
							}
							VirtualElementId.focus();
							return false;
						}else{
							return true;
						}
					}
				}
				if(opciones.length>0){
					if(VirtualElementId.value==0){
						if (typeof $.bootstrapGrowl === "function") {
							$.bootstrapGrowl( "Seleccione Una Opcion", {
								type: 'danger',//danger
								align: 'center',
								width: 'auto'
							});
						}
						VirtualElementId.focus();
						VirtualElementId.willValidate = true;
						//console.log(document.getElementsByTagName('select'));
						return false;
						
					}else{
						return true;
					}
				}
				return true;
			}
			
		}
	}
	
	//(function(){var c;c=jQuery;c.bootstrapGrowl=function(f,a){var b,e,d;a=c.extend({},c.bootstrapGrowl.default_options,a);b=c("<div>");b.attr("class","bootstrap-growl alert");a.type&&b.addClass("alert-"+a.type);a.allow_dismiss&&(b.addClass("alert-dismissible"),b.append('<button class="close" data-dismiss="alert" type="button"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>'));b.append(f);a.top_offset&&(a.offset={from:"top",amount:a.top_offset});d=a.offset.amount;c(".bootstrap-growl").each(function(){return d= Math.max(d,parseInt(c(this).css(a.offset.from))+c(this).outerHeight()+a.stackup_spacing)});e={position:"body"===a.ele?"fixed":"absolute",margin:0,"z-index":"9999",display:"none"};e[a.offset.from]=d+"px";b.css(e);"auto"!==a.width&&b.css("width",a.width+"px");c(a.ele).append(b);switch(a.align){case "center":b.css({left:"50%","margin-left":"-"+b.outerWidth()/2+"px"});break;case "left":b.css("left","20px");break;default:b.css("right","20px")}b.fadeIn();0<a.delay&&b.delay(a.delay).fadeOut(function(){return c(this).alert("close")}); return b};c.bootstrapGrowl.default_options={ele:"body",type:"info",offset:{from:"top",amount:20},align:"right",width:250,delay:4E3,allow_dismiss:!0,stackup_spacing:10}}).call(this);
	$(document).ready(function(){
		var altura = $('.menu').height();
		$(window).on('scroll', function(){
			//var altura = $('.menu').offset().top + $('.menu').height();
			//if ( $(window).scrollTop() > 0 ){
			if ( (document.body.offsetHeight - window.innerHeight) > altura &&  $(window).scrollTop() > 0){
				$('.menu').addClass('floating-menu');
				//alert("" + window.innerHeight +" "+ window.scrollY + " " + document.body.offsetHeight + "altura" + altura);
			} else {
					if ( (document.body.offsetHeight - window.innerHeight) > altura){
					$('.menu').removeClass('floating-menu');
					//$('.menu').addClass('floating-menu');
					//alert("" + window.innerHeight +" "+ window.scrollY + " " + document.body.offsetHeight + "altura" + altura);
				}
			}
		});
	});
	
	function AjaxLogOut(Ajax){
		var xhttp;
		xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function(){
		};
		xhttp.open("GET", Ajax
		, true);
		xhttp.send();
	}
	
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
		var date = new Date();var DateNumber = date.getTime();
		var DataText = DataText+'&'+'DateNumber='+DateNumber;
		xhttp.open("GET", Ajax+
		"?Time="+
		time+
		DataText
		, false);
		xhttp.send();
	}
	
	//Config.ElementId Config.ElementTexto Config.TextoInicial Config.Ajax Config.Elementos Config.ElementosTextos
	function ChangeCompleteBlock(Config){//ElementId,ElementTexto,TextoInicial,Ajax,Elementos,ElementosTextos){
		var ArraydRetorno;
		var Elementos = JsonExtraerArraydDeValores(Config.Elementos);
		var ElementosTextos = JsonExtraerArraydDeValores(Config.ElementosTextos);
		
		ElementTexto = document.getElementById(Config.ElementTexto);
		ElementTexto.innerHTML = "<b style='color:#FF0000;font-size: 10px;width: 90%;'>"+Config.TextoInicial+"</b>";
		$("#"+Config.ElementId).change(function() {
			if (this.value == 0) {
				ElementTexto.innerHTML = "<b style='color:#FF0000;font-size: 10px;width: 90%;'>"+Config.TextoInicial+"</b>";
				for(var i=0;i<Elementos.length ;i++){
					var ElementoTemporal = document.getElementById(Elementos[i]);
					//alert(ElementoTemporal.tagName);
					if(ElementoTemporal.tagName=='IMG'){
						ElementoTemporal.src = imgDIR+Config.ImagenPredefinida;
						ElementoTemporal.style.width = '100%'
						ElementoTemporal.style.height = 'auto'
					}
					if(ElementoTemporal.tagName=='P'){
						ElementoTemporal.innerHTML = "";
					}
					if(ElementoTemporal.tagName=='SELECT'){
						ElementoTemporal.setAttribute("readonly", "");
					}
					if(ElementoTemporal.type=="checkbox"){
						if(this.value=="1"){
							ElementoTemporal.checked = true;
						}else{
							if(this.value=="0"){
								ElementoTemporal.checked = false;
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
				
				AjaxArraydChangeComplete(filtro,filtroX,Config.Ajax);
				ArraydRetorno = ArraydChangeComplete;
				if(ArraydRetorno!=false){
					for(var i=0;i<ArraydRetorno.length && i<Elementos.length ;i++){
						var ElementoTemporal = document.getElementById(Elementos[i]);
						//alert(ElementoTemporal.tagName);
						if(ElementoTemporal.tagName=='SELECT'){
							//alert(ElementoTemporal.tagName);
							if(ArraydRetorno[i]=='0'){
								ElementoTemporal.setAttribute("readonly", "");
								ElementoTemporal.value = "";
							}else{
								if(ArraydRetorno[i]=='1'){
									ElementoTemporal.removeAttribute("readonly", "");
								}
							}
						}else{
							if(ElementoTemporal.tagName=='IMG'){
							//alert(ElementoTemporal.tagName);
								//alert(imgDIR+ArraydRetorno[i]);
								ElementoTemporal.src = imgDIR+ArraydRetorno[i];
								ElementoTemporal.style.width = '100%'
								ElementoTemporal.style.height = 'auto'
							}else{
								if(ElementoTemporal.tagName=='P'){
							//alert(ElementoTemporal.tagName);
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
			if (typeof(paragrap) != 'undefined' && paragrap != null){
				paragrap.innerHTML = "<b style='color: rgb(0, 0, 255);font-size:12px;'></b>";
			}
			//paragrap.innerHTML = "<b style='color: rgb(0, 0, 255);font-size:12px;'></b>";
		});
	}
	///////////////////////////////////////////////////////////////////////////
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
		
		var date = new Date();var DateNumber = date.getTime();
		if(DataText==''){
			DataText = DataText+'?'+'DateNumber='+DateNumber;
		}else{
			DataText = DataText+'&'+'DateNumber='+DateNumber;
		}
		xhttp.open("GET", Ajax+DataText
		, false);
		xhttp.send();
	}
	///////////////////////////////////////////////////////////////////////////
	
	//Config
	function NumeroSolido(Config){//ElementId
		//alert("");
		$("#"+Config.Elemento).keyup(function() {
			//alert(this.tagName);
			if(this.tagName=="INPUT"){
				if($("#"+Config.Elemento).attr('type') == "text"){
					this.value = this.value.replace(/[^a-zA-Z0-9ññÑÑ  ]+/g, "");
				}
				if($("#"+Config.Elemento).attr('type') == "number"){
					this.value = this.value.replace(/[^0-9,,..]+/g, "");
				}
				if($("#"+Config.Elemento).attr('type') == "numberNoFloat"){
					this.value = this.value.replace(/[^0-9]+/g, "");
				}
				if($("#"+Config.Elemento).attr('type') == "NumeroSolido"){
					this.value = this.value.replace(/[^0-9]+/g, "");
				}
			}
		});
	}
	
	$.fn.setCursorPosition = function(pos) {
		this.each(function(index, elem) {
			if (elem.setSelectionRange) {
				elem.setSelectionRange(pos, pos);
			}else{
				if(elem.createTextRange){
					var range = elem.createTextRange();
					range.collapse(true);
					range.moveEnd('character', pos);
					range.moveStart('character', pos);
					range.select();
				}
			}
		});
		return this;
	};
	//Config.ElementId Config.ElementTexto Config.DigitosMinimos Config.TextoInicial Config.TextoMenor
	
	function ValidateEmail(mail){
		if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail)){
			return (true);
		}
		//alert("You have entered an invalid email address!")
		return (false)
	}
	function setInputSelection(input, startPos, endPos){
		if (input.setSelectionRange){
			input.focus();
			input.setSelectionRange(startPos, endPos);
		} else if (input.createTextRange){
			var range = input.createTextRange();
			range.collapse(true);
			range.moveEnd('character', endPos);
			range.moveStart('character', startPos);
			range.select();
		}
	}
	function Texto(Config){
		var ElementId = Config.Elemento;
		var ElementTexto = Config.ElementoTexto;
		var DigitosMinimos = Config.DigitosMinimos;
		var TextoInicial = Config.TextoInicial;
		var TextoMenor = Config.TextoMenor;
		
		ElementTexto = document.getElementById(ElementTexto);
		VirtualElementId = document.getElementById(ElementId);
		//alert(ElementId);
		//ElementTexto.innerHTML = "<b style='color:#FF0000;font-size: 10px;width: 90%;'>"+TextoInicial+"</b>";
		if(VirtualElementId == null){return;}
		if (VirtualElementId.value.length <= 0) {
			ElementTexto.innerHTML = "<b style='color:#FF0000;font-size: 10px;width: 90%;'>"+TextoInicial+"</b>";
		}
		else {
			if(VirtualElementId.value.length<DigitosMinimos){
				ElementTexto.innerHTML = "<b style='color:#FF0000;font-size: 10px;width: 90%;'>"+TextoMenor+"</b>";
			}else{
				ElementTexto.innerHTML = "";
			}
		}
		if($("#"+ElementId).attr('type') == "textCorreo"){ //()
			$("#"+ElementId).val("@gmail.com");
		
			var clearButton = document.getElementById(ElementId);
			var previousActiveElement = null;
			clearButton.onmousedown = function() {
				previousActiveElement = document.activeElement;
			};
			clearButton.onclick = function() {
				if (previousActiveElement && previousActiveElement != this) {
					//previousActiveElement.value = "";
					//alert(clearButton.value +"");
					//$("#"+ElementId).setCursorPosition(Posision)
					setInputSelection(clearButton, 0, 0);
				}
			};
		}
		
		$("#"+ElementId).keyup(function(e) {
			var NombreOriginal=this.value;
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
					this.value = this.value.replace(/[^a-zA-Z0-9ññÑÑ  @@..__\-]+/g, "");
				}
				if($("#"+ElementId).attr('type') == "numberNoFloat"){
					this.value = this.value.replace(/[^0-9]+/g, "");
				}
				if($("#"+ElementId).attr('type') == "CodigoPostal"){
					this.value = this.value.replace(/[^0-9]+/g, "");
					//this.value = this.value.substring(0,4);
				}
				if($("#"+ElementId).attr('type') == "password"){
					this.value = this.value.replace(/[^a-zA-Z0-9ññÑÑ  ]+/g, "");
				}
				if($("#"+ElementId).attr('type') == "Celular"){
					this.value = this.value.replace(/[^0-9 ()]+/g, "");
				}
			}
			if (this.value.length <= 0) {
				if(NombreOriginal!=this.value){
					ElementTexto.innerHTML = "<b style='color:#FF0000;font-size: 10px;width: 90%;'>Caracter No Permitido. "+TextoInicial+"</b>";
				}else{
					ElementTexto.innerHTML = "<b style='color:#FF0000;font-size: 10px;width: 90%;'>"+TextoInicial+"</b>";
				}
			}
			else {
				if(this.value.length<DigitosMinimos){
					if(NombreOriginal!=this.value){
						ElementTexto.innerHTML = "<b style='color:#FF0000;font-size: 10px;width: 90%;'>Caracter No Permitido. "+TextoMenor+"</b>";
					}else{
						ElementTexto.innerHTML = "<b style='color:#FF0000;font-size: 10px;width: 90%;'>"+TextoMenor+"</b>";
					}
				}else{
					ElementTexto.innerHTML = "";
					if(NombreOriginal!=this.value){
						ElementTexto.innerHTML = "<b style='color:#FF0000;font-size: 10px;width: 90%;'>Caracter No Permitido</b>";
					}
				}
			}
			
			if(this.tagName=="INPUT"){
				if($("#"+ElementId).attr('type') == "CodigoPostal"){
					NombreOriginal=this.value;
					this.value = this.value.substring(0,4);
				}
				if(NombreOriginal!=this.value){
					if (typeof $.bootstrapGrowl === "function") {
						$.bootstrapGrowl( "<p style='white-space: nowrap;'>Limite De Caracteres Exedidos</p>", {
							type: 'danger',//danger
							align: 'center',
							width: 'auto'
						});
					}
				}
			}
			
			
			var paragrap = document.getElementById("Paragrap");
			if (typeof(paragrap) != 'undefined' && paragrap != null){
				paragrap.innerHTML = "<b style='color: rgb(0, 0, 255);font-size:12px;'></b>";
			}
			
			
			if(this.tagName=="INPUT"){
				if($("#"+ElementId).attr('type') == "textCorreo"){
					var Posision = e.target.selectionStart;
					//evento.preventDefault();
					//if(this.value.length<3){
					//	this.value = this.value.replace(/[^0-9]+/g, "");
					//}
					var original = this.value;
					
					if(e.keyCode != 37 && e.keyCode != 39) {
						if(!ValidateEmail(original)){
							ElementTexto.innerHTML = "<b style='color:#FF0000;font-size: 10px;width: 90%;'>El Correo Electronico Requiere La Forma:<b>(XXX@XXXX.com)</b> o <b>(XXX@XXXX.com.XX)</b></b>";
							/*
							if (typeof $.bootstrapGrowl === "function") {
								$.bootstrapGrowl( "<p>El Correo Electronico Requiere La Forma:</p><p><b>(XXX@XXXX.com)</b></p>", {
									type: 'danger',//danger
									align: 'center',
									width: 'auto'
								});
							}
							*/
						}else{
							ElementTexto.innerHTML = "";
						}
						/*
						if(this.value.length>6 && this.value.length<=10){
							this.value = "(" + this.value.substring(0,3) + ") " + this.value.substring(3,6) + " " + this.value.substring(6,this.value.length);
						}
						if(this.value.length>3 && this.value.length<=6){
							this.value = "(" + this.value.substring(0,3) + ") " + this.value.substring(3,this.value.length);
						}
						switch(this.value.substring(Posision-1,Posision)){
							case " ":
								$("#"+ElementId).setCursorPosition(Posision+1);
							break;
							case "(":
								$("#"+ElementId).setCursorPosition(Posision+1);
							break;
							case ")":
								$("#"+ElementId).setCursorPosition(Posision+2);
							break;
							default:
								if(Posision==4 && this.value.length == 7){
									$("#"+ElementId).setCursorPosition(this.value.length);
								}else{
									$("#"+ElementId).setCursorPosition(Posision);
								}
						}
						*/
					}else{
						
					}
				}
			}
			if(this.tagName=="INPUT"){
				if($("#"+ElementId).attr('type') == "Celular"){
					var Posision = e.target.selectionStart;
					//evento.preventDefault();
					//if(this.value.length<3){
					//	this.value = this.value.replace(/[^0-9]+/g, "");
					//}
					if(e.keyCode != 37 && e.keyCode != 39) {
						this.value = this.value.replace(/[^0-9]+/g, "");
						if(this.value.length>10){
							if (typeof $.bootstrapGrowl === "function") {
								$.bootstrapGrowl( "<p>El Numero De Celular Esta Compuesto Por:</p><p><b>codigo De Area 3 Digitos:(XXX)</b></p> <p>y</p> <p><b>Numero Personal 7 Digitos:XXX XXXX</b></p>", {
									type: 'danger',//danger
									align: 'center',
									width: 'auto'
								});
							}
							this.value = "(" + this.value.substring(0,3) + ") " + this.value.substring(3,6) + " " + this.value.substring(6,10);
						}
						if(this.value.length>6 && this.value.length<=10){
							this.value = "(" + this.value.substring(0,3) + ") " + this.value.substring(3,6) + " " + this.value.substring(6,this.value.length);
						}
						if(this.value.length>3 && this.value.length<=6){
							this.value = "(" + this.value.substring(0,3) + ") " + this.value.substring(3,this.value.length);
						}
						switch(this.value.substring(Posision-1,Posision)){
							case " ":
								$("#"+ElementId).setCursorPosition(Posision+1);
							break;
							case "(":
								$("#"+ElementId).setCursorPosition(Posision+1);
							break;
							case ")":
								$("#"+ElementId).setCursorPosition(Posision+2);
							break;
							default:
								if(Posision==4 && this.value.length == 7){
									$("#"+ElementId).setCursorPosition(this.value.length);
								}else{
									$("#"+ElementId).setCursorPosition(Posision);
								}
						}
					}else{
						
					}
				}
			}
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
						this.value = this.value.replace(/[^a-zA-Z0-9ññÑÑ  @@..__\-]+/g, "");
					}
				}
				ElementTexto.innerHTML = "";
			}
			var paragrap = document.getElementById("Paragrap");
			if (typeof(paragrap) != 'undefined' && paragrap != null){
				paragrap.innerHTML = "<b style='color: rgb(0, 0, 255);font-size:12px;'></b>";
			}
			//paragrap.innerHTML = "<b style='color: rgb(0, 0, 255);font-size:12px;'></b>";
		});
	}
	
	//Config.ElementId Config.ElementTexto Config.TextoInicial 
	function Change(Config){
		var ElementId = Config.ElementId;
		var ElementTexto = Config.ElementTexto;
		var TextoInicial = Config.TextoInicial;
		
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
			if (typeof(paragrap) != 'undefined' && paragrap != null){
				paragrap.innerHTML = "<b style='color: rgb(0, 0, 255);font-size:12px;'></b>";
			}
			//paragrap.innerHTML = "<b style='color: rgb(0, 0, 255);font-size:12px;'></b>";
		});
	}
	
	//Config = false
	function ChangeCompleteTable(Config = false){//ElementId,Ajax,Hijo,Padre){//
		var ElementId = Config.Reactivo;
		var Ajax = Config.Ajax;
		var Hijo = Config.HijoDeReactivo;
		var Padre = Config.Reactor;
		//var ArraydRetorno;
		
		$("#"+ElementId).change(function() {
			if (this.value == 0) {
				var element =  document.getElementById(Config.HijoDeReactivo);
				if (typeof(element) != 'undefined' && element != null){
					element.remove();
					if(Config.MaximizeBox){
						$("#"+Config.MaximizeElement).unbind('click');
						var HideElement = document.getElementById(Config.MaximizeElement);
						var content = HideElement.nextElementSibling;
						HideElement.innerHTML = "Desplegar Resultados";
						content.style.maxHeight = null;
						content.style.backgroundColor = "#ffe0de";
					}
				}
			}
			else {
				filtro=["id","time"];
				filtroX=[this.value,"0"];
				AjaxTabla(Config);
				if(Config.MaximizeBox){
					$("#"+Config.MaximizeElement).unbind('click');
					$("#"+Config.MaximizeElement).click(function(){
						this.classList.toggle("active");
						var $this = $(this);
						if($this.data('clicked')) {
							var content = this.nextElementSibling;
							if (content.style.maxHeight){
								this.innerHTML = "Desplegar Resultados";
								content.style.maxHeight = null;
								content.style.backgroundColor = "#ffe0de";
							} else {
								this.innerHTML = "Minimizar Resultados";
								content.style.maxHeight = content.scrollHeight + "px";
								content.style.backgroundColor = null;
							} 
						}else{
							$this.data('clicked', true);
							var content = this.nextElementSibling;
							if (content.style.maxHeight){
								this.innerHTML = "Desplegar Resultados";
								content.style.maxHeight = null;
								content.style.backgroundColor = "#ffe0de";
							} else {
								this.innerHTML = "Minimizar Resultados";
								content.style.maxHeight = content.scrollHeight + "px";
								content.style.backgroundColor = null;
							} 
						}
					});
				}
			}
			var paragrap = document.getElementById("Paragrap");
			if (typeof(paragrap) != 'undefined' && paragrap != null){
				paragrap.innerHTML = "<b style='color: rgb(0, 0, 255);font-size:12px;'></b>";
			}
			//paragrap.innerHTML = "<b style='color: rgb(0, 0, 255);font-size:12px;'></b>";
		});
	}
	
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////
	function TablaCheckBoxToAjaxLoading(Config = null){
		Loading();
		setTimeout(function() {
			TablaCheckBoxToAjax(Config);
		}, 1000);
	}
	function TablaCheckBoxToAjax(Config = null){
		//console.log("Inicia");
		var DivTabla = document.getElementById(Config.Reactor);
		var Tabla = DivTabla.getElementsByTagName("table");
		if (typeof(Tabla[0]) != 'undefined' && Tabla[0] != null){
			//console.log("Tabla Definida");
			Tabla = Tabla[0];
			//var Lineas = table.rows.length;
			var Lineas = Tabla.rows.length;
			var arraid = new Array();
			
			var Pre = "(";
			var Pos = ")";
			var id = "";
			var piezas = new Array();
			
			var TextUpload = document.getElementById(Config.DivDeCarga);
			TextUpload.innerHTML = Config.TextoDeDivDeCarga;
			var LoadBar = document.getElementById("LoadBar");
			FloatLoadBar = 0 ;
			FloatNoLoadBar = 0 ;
			LoadBar.style.width= FloatLoadBar+"%";
			var CantidadDeTildados = 0;
			for (var i = 1 ; i < Lineas; i++){
				//console.log("Lineas Mayor Que 1");
				var CheckBs;
				if(Config.IdCheckBox != undefined){
					CheckBs = document.getElementById(Config.IdCheckBox+"CheckBoxTabla"+i);
					//console.log(Config.IdCheckBox+"CheckBoxTabla"+i);
					//Checkbox.id = Config.IdCheckBox+"CheckBoxTabla"+(i+DescuentoDeFila);
				}else{
					CheckBs = document.getElementById("CheckBoxTabla"+i);
					//console.log(Config.IdCheckBox+"CheckBoxTabla"+i);
					//Checkbox.id = "CheckBoxTabla"+(i+DescuentoDeFila);
				}
				if(CheckBs.checked){
					CantidadDeTildados++;
				}
			}
			//console.log(Config);
			//console.log(CantidadDeTildados);
			//console.log(CantidadDeTildados);
			for (var i = 1 ; i < Lineas; i++){
				var CheckBs;
				if(Config.IdCheckBox != undefined){
					CheckBs = document.getElementById(Config.IdCheckBox+"CheckBoxTabla"+i);
					//Checkbox.id = Config.IdCheckBox+"CheckBoxTabla"+(i+DescuentoDeFila);
				}else{
					CheckBs = document.getElementById("CheckBoxTabla"+i);
					//Checkbox.id = "CheckBoxTabla"+(i+DescuentoDeFila);
				}
				//var CheckBs = document.getElementById("CheckBoxTabla"+i);
				if(CheckBs.checked){
					var Id = Tabla.rows[i].cells[0].innerHTML;
					//alert(Id);
					Elementos=["id"];
					ElementosTextos=[Id];
					if(Config){
						if(Config.Parametros != undefined){
							for (var Conti = 0; Conti < Config.Parametros.length; Conti++) {
								var Parametros = Config.Parametros[Conti];//
								var keys = Object.keys(Parametros);
								//console.log(keys[0]);
								var values = Object.values(Parametros)
								//console.log(values[0]);
								
								Elementos.push(keys[0])
								ElementosTextos.push(values[0])
							}
						}
					}
					//alert(CantidadDeTildados+" "+Elementos+" "+ElementosTextos+" "+Config.Ajax+" "+i);
					AjaxParts(CantidadDeTildados,Elementos,ElementosTextos,Config.Ajax,i);
				}
			}
		}
		//EndLoading();
	}
	/////////////////////////////////////////////////////////////////////////////////////////////////////
	
	var FloatLoadBar = 0 ;
	var FloatNoLoadBar = 0 ;
	var RequestMem = new Array();
	function AjaxParts(CountData,DataNombre,Data,Ajax,i = 1){
		Loading();
		var xhttp;
		xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function(){
			if (this.readyState == 4 && this.status == 200){
				var Resultado = this.responseText.trim();
				if(Resultado=="Sin Resultados" || Resultado=="NULL" || Resultado=="Elude"){
					FloatNoLoadBar++;
				}else{
					if(Resultado=="Susses"){
						FloatLoadBar++;
					}else{
						if (typeof $.bootstrapGrowl === "function") {
							$.bootstrapGrowl( Resultado +"", {
								type: 'success',//danger
								align: 'center',
								width: 'auto'
							});
						}
						FloatLoadBar++;
					}
				}
				var PorcentajePositivo = FloatLoadBar / CountData * 100;
				var PorcentajeNegativo = FloatNoLoadBar / CountData* 100;
				var LoadBar = document.getElementById("LoadBar");
				var PorcentajeTotal=PorcentajePositivo+PorcentajeNegativo;
				LoadBar.style.width= PorcentajeTotal+"%";
				if( (FloatLoadBar+FloatNoLoadBar)==CountData ){
					if (typeof $.bootstrapGrowl === "function") {
						$.bootstrapGrowl( PorcentajePositivo +"% Cargado", {
							type: 'success',//danger
							align: 'center',
							width: 'auto'
						});
					}
					EndLoading();
				}
				if( (FloatLoadBar+FloatNoLoadBar)==CountData && FloatNoLoadBar>0){
					if (typeof $.bootstrapGrowl === "function") {
						$.bootstrapGrowl( PorcentajeNegativo+"% No Cargado", {
							type: 'danger',//danger
							align: 'center',
							width: 'auto'
						});
					}
					EndLoading();
				}
				
			}else{
				if (this.readyState == 4 && this.status != 200){
					setTimeout(function() {
						xhttp.open("GET", RequestMem[i]
						, true);//false sin asincronia
						xhttp.send();
					}, 60000);
					console.clear();
					if(this.status == 0){
						
						//console.log(xhttp);
						//console.log(i);
					}else{
						FloatNoLoadBar++;
					}
					var PorcentajePositivo = FloatLoadBar / CountData* 100;
					/*
					if (typeof $.bootstrapGrowl === "function") {
						$.bootstrapGrowl( this.status, {
							type: 'danger',//danger
							align: 'center',
							width: 'auto'
						});
					}
					*/
					if( (FloatLoadBar+FloatNoLoadBar)==CountData ){
						if (typeof $.bootstrapGrowl === "function") {
							$.bootstrapGrowl( PorcentajePositivo+"% Cargado", {
								type: 'success',//danger
								align: 'center',
								width: 'auto'
							});
						}
						EndLoading();
					}
					var PorcentajeNegativo = FloatNoLoadBar / CountData* 100;
					if( (FloatLoadBar+FloatNoLoadBar)==CountData && FloatNoLoadBar>0){
						if (typeof $.bootstrapGrowl === "function") {
							$.bootstrapGrowl( PorcentajeNegativo+"% Cargado", {
								type: 'danger',//danger
								align: 'center',
								width: 'auto'
							});
						}
						EndLoading();
					}
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
		
		var date = new Date();var DateNumber = date.getTime();
		if(DataText==''){
			DataText = DataText+'?'+'DateNumber='+DateNumber;
		}else{
			DataText = DataText+'&'+'DateNumber='+DateNumber;
		}
		RequestMem[i] = Ajax+DataText;
		xhttp.open("GET", Ajax+DataText
		, true);//false sin asincronia
		xhttp.send();
	}
	
	function NotSussesDeleteSelect(SelectId) {
		var x = document.getElementById(SelectId);
		var Alength = x.options.length;
		//alert(""+SelectId);
		//alert(x.options.length);
		while (x.options.length) {
			x.remove(0);
		}
		
		var option = document.createElement("option");
		option.value = "0";
		//option.text = "Seleccione";
		x.setAttribute("readonly", "");
		//x.add(option);
		
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//														Funciones Sin Testear
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function BuscarExtencion(input, expect) {
		var parts = input.split('.');
		if(parts.length>0){
			if (parts[parts.length-1] === expect){
				return(true);
			}else{
				return(false);
			}
		}else{
			return(false);
		}
	}
	function BuscarExtencionString(input) {
		var parts = input.split('.');
		return(parts[parts.length-1]);
	}
	function OnChangeDeleteChildremHiddenElement(idElemento,idDivColumnItem,idItemVisible = ""){
		$("#"+idElemento).change(function() {
			Loading();
			if(idItemVisible != ""){
				var ItemButtonUploadDB = document.getElementById(idItemVisible);
				ItemButtonUploadDB.style.display="none";
				
			}
			setTimeout(function() {
				OnChangeDeleteChildremAsync(idDivColumnItem);
			}, 1000);
			
		});
	}
	function OnChangeDeleteChildremAsync(idDivColumnItem){
		var DivTabla = document.getElementById(idDivColumnItem);
		if (typeof(DivTabla) != 'undefined' && DivTabla != null){
			var Tabla = DivTabla.getElementsByTagName("table");
			if (typeof(Tabla[0]) != 'undefined' && Tabla[0] != null){
				Tabla[0].remove();
			}
		}
		EndLoading();
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	function DeleteSelect(SelectId) {
		var x = document.getElementById(SelectId);
		if(x==null){return;}
		var Alength = x.options.length;
		//alert(""+SelectId);
		//alert(x.options.length);
		while (x.options.length) {
			x.remove(0);
		}
		
		var option = document.createElement("option");
		option.value = "0";
		option.text = "Seleccione";
		x.setAttribute("readonly", "");
		x.add(option);
		
	}
	
	
	
	
	
	
	
	
	
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
		
		var date = new Date();var DateNumber = date.getTime();
		if(DataText==''){
			DataText = DataText+'?'+'DateNumber='+DateNumber;
		}else{
			DataText = DataText+'&'+'DateNumber='+DateNumber;
		}
		xhttp.open("GET", Ajax+DataText
		, false);
		xhttp.send();
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
			if (typeof(paragrap) != 'undefined' && paragrap != null){
				paragrap.innerHTML = "<b style='color: rgb(0, 0, 255);font-size:12px;'></b>";
			}
			//paragrap.innerHTML = "<b style='color: rgb(0, 0, 255);font-size:12px;'></b>";
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
			if (typeof(paragrap) != 'undefined' && paragrap != null){
				paragrap.innerHTML = "<b style='color: rgb(0, 0, 255);font-size:12px;'></b>";
			}
			//paragrap.innerHTML = "<b style='color: rgb(0, 0, 255);font-size:12px;'></b>";
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
			if (typeof(paragrap) != 'undefined' && paragrap != null){
				paragrap.innerHTML = "<b style='color: rgb(0, 0, 255);font-size:12px;'></b>";
			}
			//paragrap.innerHTML = "<b style='color: rgb(0, 0, 255);font-size:12px;'></b>";
		});
	}
	
	//Config.Elementos Config.DataNombre Config.Data Config.Ajax
	
	function AjaxItem(Config){//Elementos,DataNombre,Data,Ajax
		var Elementos = Config.Elementos;
		var DataNombre = Config.DataNombre;
		var Data = Config.Data;
		var Ajax = Config.Ajax;
		
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
						//alert(concat);
						
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
		
		var date = new Date();var DateNumber = date.getTime();
		if(DataText==''){
			DataText = DataText+'?'+'DateNumber='+DateNumber;
		}else{
			DataText = DataText+'&'+'DateNumber='+DateNumber;
		}
		xhttp.open("GET", Ajax+DataText
		, true);//false sin asincronia
		xhttp.send();
	}
	
	
	//eval("my script here")
	function AjaxParagrapAndCall(DataNombre,Data,Ajax,Funcion){
		Loading();
		var paragrap = document.getElementById("Paragrap");
		var xhttp;
		xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function(){
			if (this.readyState == 4 && this.status == 200){
				var Resultado = this.responseText.trim();
				if(Resultado=="Sin Resultados"){
					if (typeof $.bootstrapGrowl === "function") {
						$.bootstrapGrowl(Resultado, {
							type: 'danger',//danger
							align: 'center',
							width: 'auto'
						});
					}
				}else{
					if(Resultado=="Elude"){
						
					}else{
						EndLoading();
						//paragrap.innerHTML = ;
						if (typeof $.bootstrapGrowl === "function") {
							$.bootstrapGrowl(Resultado, {
								type: 'success',//danger
								align: 'center',
								width: 'auto'
							});
							var fn = window['Hablar']; 
							if(typeof fn === 'function') {
								Hablar(Resultado);
							}
						}else{
							if (typeof(paragrap) != 'undefined' && paragrap != null){
								paragrap.innerHTML = Resultado;
							}
						}
					}
				}
				EndLoading();
				eval(Funcion);
			}else{
				if (this.readyState == 4 && this.status != 200){
					EndLoading();
					if (typeof $.bootstrapGrowl === "function") {
						$.bootstrapGrowl("Error Al Intentar Sacar Los Datos", {
							type: 'danger',//danger
							align: 'center',
							width: 'auto'
						});
					}
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
		
		var date = new Date();var DateNumber = date.getTime();
		if(DataText==''){
			DataText = DataText+'?'+'DateNumber='+DateNumber;
		}else{
			DataText = DataText+'&'+'DateNumber='+DateNumber;
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
					if (typeof $.bootstrapGrowl === "function") {
						$.bootstrapGrowl(Resultado, {
							type: 'danger',//danger
							align: 'center',
							width: 'auto'
						});
					}
				}else{
					if(Resultado=="Elude"){
						
					}else{
						EndLoading();
						//paragrap.innerHTML = ;
						if (typeof $.bootstrapGrowl === "function") {
							$.bootstrapGrowl(Resultado, {
								type: 'success',//danger
								align: 'center',
								width: 'auto'
							});
						}else{
							if (typeof(paragrap) != 'undefined' && paragrap != null){
								paragrap.innerHTML = Resultado;
							}
						}
					}
				}
				EndLoading();
			}else{
				if (this.readyState == 4 && this.status != 200){
					EndLoading();
					if (typeof $.bootstrapGrowl === "function") {
						$.bootstrapGrowl("Error Al Intentar Sacar Los Datos", {
							type: 'danger',//danger
							align: 'center',
							width: 'auto'
						});
					}
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
		
		var date = new Date();var DateNumber = date.getTime();
		if(DataText==''){
			DataText = DataText+'?'+'DateNumber='+DateNumber;
		}else{
			DataText = DataText+'&'+'DateNumber='+DateNumber;
		}
		xhttp.open("GET", Ajax+DataText
		, true);//false sin asincronia
		xhttp.send();
	}
	var ReturnAjaxWait = 0;
	function AjaxWaitValue(DataNombre,Data,Ajax){//
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
		
		var date = new Date();var DateNumber = date.getTime();
		if(DataText==''){
			DataText = DataText+'?'+'DateNumber='+DateNumber;
		}else{
			DataText = DataText+'&'+'DateNumber='+DateNumber;
		}
		xhttp.open("GET", Ajax+DataText
		, false);//false sin asincronia
		xhttp.send();
	}
	
	
	
	
	
	
	
	
	
	
	function setVisible(selector, visible) {
		document.querySelector(selector).style.display = visible ? 'block' : 'none';
	}
	
	
	var CountLoading = 0 ;
	var TotalDeConsultas = 0;
	function Loading(){
		CountLoading ++;
		setVisible('#loading', true);
		if(CountLoading > 1){
			var loadingText = document.getElementById("loadingText");
			//loadingText.innerHTML = "Faltan " + CountLoading + " Consultas";
			if(TotalDeConsultas > 0){
				loadingText.innerHTML = "Transacciones En Curso, Aguarde Un Instante";//, "  + CountLoading + " Transacciones Completadas De " + TotalDeConsultas + "";
			}else{
				loadingText.innerHTML = "Transacciones En Curso, Aguarde Un Instante";
			}
		}
	}
	
	function EndLoading(){
		CountLoading--;
		if(CountLoading <= 0){
			$('#loading').children('.progress').remove();
			setVisible('#loading', false);
			TotalDeConsultas = 0;
			CountLoading = 0;
		}else{
			if(CountLoading == 1){
				var loadingText = document.getElementById("loadingText");
				//loadingText.innerHTML = "Falta " + CountLoading + " Consulta";
				loadingText.innerHTML = "Transaccion En Curso, Aguarde Un Instante";
			}else{
				var loadingText = document.getElementById("loadingText");
				//loadingText.innerHTML = "Faltan " + CountLoading + " Consultas";
				if(TotalDeConsultas > 0){
					loadingText.innerHTML = "Transacciones En Curso, Aguarde Un Instante";//, "  + CountLoading + " Transacciones Completadas De " + TotalDeConsultas + "";
				}else{
					loadingText.innerHTML = "Transacciones En Curso, Aguarde Un Instante";
				}
				
			}
		}
		
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
				paragrap.innerHTML = "<b style='color: rgb(0, 0, 255);font-size:12px;'></b>";
			// * /
	}
	*/
	
	//var GlobalConfig = false;
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
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
	
	
	
	
	
	
	