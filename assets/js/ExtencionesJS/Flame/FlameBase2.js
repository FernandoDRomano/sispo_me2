//Inabilita O Abilita Boton Si Se Acepta Terminos Y Condiciones

$(document).ready(function(){
	$('#Terminos').on( 'click', function() {
		var $Check = $( this );
		if($('#'+$Check.attr("avilitar")) != null){
			$('#'+$Check.attr("avilitar")).toggleClass("disabled");
		}
	});
});

//Funcion Que Busca En Aray2 Todos Los Elementos De Aray1 Si No Encuentra 1 Retorna El Elemento De Lo Contrario Retorna -1
function ArrayEnArray(Aray1,Aray2){
	for(var i = 0 ; i < Aray1.length ; i++){
		if(Aray2.includes(Aray1[i]) || Aray1[i] == ""){
			//alert(Aray1[i] + " Encontrado");
		}else{
			//alert(Aray1[i] + " No Encontrado");
			return i;
		}
	}
	return -1;
}


//Funcion Que Concadena Objetos JSON Que Estan En Un Array
function ArraydJsonConcatenar(obj1,obj2){
	//console.log(obj2);
	Arrayd = new Array;
	for(var i = 0 ; i < obj1.length ; i++){
		Arrayd.push(obj1[i]);
	}
	for(var i = 0 ; i < obj2.length ; i++){
		Arrayd.push(obj2[i]);
	}
	//console.log(Arrayd);
	return Arrayd;
}

//Funcion Que Concadena Objetos JSON
function jsonConcat(o1, o2){//Requiere Objeto JSON
	for (var key in o2) {
		o1[key] = o2[key];
	}
	return o1;//Retorna Objeto JSON
}

//Funcion Que Concadena Arrayds Js A String JSON
function ArraydsAJson(ArraydKeys,ArraydValues){ //Requiere Arrayds Js
	//console.log(ArraydKeys);
	//console.log(ArraydValues);
	var obj = {};
	for (var i = 0; i < ArraydKeys.length; i++) {
		obj[ArraydKeys[i]] = ArraydValues[i];
	}
	//console.log(JSON.stringify(obj));
	return(JSON.stringify(obj));// Retorna String JSON
}

function DataAJsonValores(Obj){
	
}
//Funcion Que Extrae Los Valores De Un Objeto JSON, Dado Esos Valores Busca Los Objetos Y Guarda Los Valores De Los Objetos.
function JsonElementosAJsonValores(Obj){//Requiere Objeto JSON
	var x = new Array();
	var y = new Array();
	for (var key in Obj) {
		/*
		console.log(key);
		console.log(Obj[key]);
		console.log(document.getElementById(Obj[key]).value);
		console.log(document.getElementById(Obj[key]).innerHTML);
		*/
		if(document.getElementById(Obj[key]) == null ){
			alert("Elemento: " + Obj[key] + " Inexistente")
		}else{
			var Elemento = document.getElementById(Obj[key]);
			if(Elemento.tagName == 'DATA') {
					var inf = Elemento.fileinfo;
					for(var keys in inf){
						x.push(keys);
						y.push(Elemento.fileinfo[keys]);
					}
			}else{
				if(Elemento.tagName == 'TABLE') {
					//console.log(Elemento.parentElement.Config);
					//x.push("Tabla");
					var ColumnasAgregadas = 0;
					if(Elemento.parentElement.Config.ImputsAderidosTitulo != undefined){
						var ColumnasAgregadas = Elemento.parentElement.Config.ImputsAderidosTitulo.split("(,)").length ;
						//alert(Elemento.parentElement.Config.Titulos.length + ColumnasAgregadas + " Elemento.parentElement.Config.Titulos.length:" + Elemento.parentElement.Config.Titulos.length + " ColumnasAgregadas:" + ColumnasAgregadas);
					}
					for(var i = 0 ; i < Elemento.parentElement.Config.Titulos.length + (ColumnasAgregadas); i++){
						if(Elemento.parentElement.Config.Titulos[i] != undefined){
							x.push(Elemento.parentElement.Config.Titulos[i]);
						}else{
							x.push(Elemento.parentElement.Config.ImputsAderidosTitulo.split("(,)")[i-Elemento.parentElement.Config.Titulos.length]);
						}
						
						var ArraydDeTabla = new Array;
						
						//console.log(Elemento.parentElement.Config);
						for(var j = 0; j<Elemento.parentElement.Config.Resultado.length ; j++ ){
							if(i>=Elemento.parentElement.Config.Titulos.length){
								ArraydDeTabla.push(Elemento.parentElement.Config.ValoresDeInputs["TD_Imput"+j+"-"+(i-Elemento.parentElement.Config.Titulos.length)]);
							}else{
								ArraydDeTabla.push(Elemento.parentElement.Config.Resultado[j][i]);
							}
							
						}
						
						
						/*
						if(Elemento.parentElement.Config.ResultadoFiltrado == null){
							
						}else{
							for(var j = 0; j<Elemento.parentElement.Config.ResultadoFiltrado.length ; j++ ){
								if(i>=Elemento.parentElement.Config.Titulos.length){
									ArraydDeTabla.push(Elemento.parentElement.Config.ValoresDeInputs["TD_Imput"+j+"-"+(i-Elemento.parentElement.Config.Titulos.length)]);
								}else{
									ArraydDeTabla.push(Elemento.parentElement.Config.ResultadoFiltrado[j][i]);
								}
								
							}
						}
						*/
						y.push(ArraydDeTabla);
					}
					//console.log(Elemento.parentElement.Config);
					//console.log(y);
					
					//for(var i = 0; i< Elemento.){}
				}else{
					x.push(key);
					if(Elemento.value == undefined || Elemento.tagName == 'DIV'){
						var keys = Object.keys(Obj);
						var values = Object.values(Obj);
						y.push( Elemento.innerHTML );
					}else{
					//console.log('TABLE:' + key);
						y.push( Elemento.value );
					}
				}
			}
		}
	}
	var JsonValores = ArraydsAJson(x,y)
	
	//console.log(x);
	//if(y==undefined){y[0]="";}
	//console.log(y);
	//console.log(JsonValores);
	//return JSON.stringify(JsonValores);
	return JsonValores; //Retorna Objeto JSON Con Valores De Los Objetos
}


//Funciones De Fecha
$(function () {//Elementos Con Clase FechaHoraMinuto Ejecutan Automaticamente Elemento De Fecha Con Formato 31/12/2020 23:59
	$('.FechaHoraMinuto').datetimepicker({
		format: 'DD/MM/YYYY HH:mm',locale: 'ru',
		date: new Date()
	});
});
$(function () {//Elementos Con Clase FechaHoraMinuto Ejecutan Automaticamente Elemento De Fecha Con Formato 2020/12/31
	$('.Fecha').datetimepicker({
		format: 'YYYY/MM/DD',locale: 'ru',
		date: new Date()
	});
});
$(function () {
	$('.FechaHoraMinutoOpen').datetimepicker({//Elementos Con Clase FechaHoraMinuto Ejecutan Automaticamente Elemento De Fecha Con Formato 31/12/2020 23:59:00
		format: 'DD/MM/YYYY HH:mm:00',locale: 'ru',//,
		inline: true,
		sideBySide: true
	})
});
$(function () {
	$('.FechaFull').datetimepicker({//Elementos Con Clase FechaHoraMinuto Ejecutan Automaticamente Elemento De Fecha Con Formato 31/12/2020 23:59:59
		format: 'DD/MM/YYYY HH:mm:ss',locale: 'ru'
	})
});
$(function () {
	$('.FechaFullRegular').datetimepicker({//Elementos Con Clase FechaHoraMinuto Ejecutan Automaticamente Elemento De Fecha Con Formato 2020/12/31 23:59:59
		format: 'YYYY/MM/DD HH:mm:ss',locale: 'ru'
	})
});