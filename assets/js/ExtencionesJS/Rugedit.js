

var ScriptTime=`
	$(function () {
		$('.FechaHoraMinuto').datetimepicker({
			format: 'YYYY/MM/DD HH:mm',locale: 'ru',
			date: new Date()
		});
		
	});
	$(function () {
		$('.Fecha').datetimepicker({
			format: 'YYYY/MM/DD',locale: 'ru',
			date: new Date()
		});
		
	});
`;

var ScriptTimeOpen=`
	$(function () {
		$('.FechaHoraMinutoOpen').datetimepicker({
			format: 'DD/MM/YYYY HH:mm:00',locale: 'ru',//,
			inline: true,
			sideBySide: true
		})
	});
`;

var ScriptTimeFull =`
	$(function () {
		$('.FechaFull').datetimepicker({
			format: 'DD/MM/YYYY HH:mm:ss',locale: 'ru'
		})
	});
`;

var ScriptTimeFullRegular =`
	$(function () {
		$('.FechaFullRegular').datetimepicker({
			format: 'YYYY/MM/DD HH:mm:ss',locale: 'ru'
		})
	});
`;


var ScriptTablaMuestra =`
	//filtro=["FechaI","FechaF","time","NoMemory"];
	//filtroX=[FechaI.value,FechaF.value,"0",NoMemory];
	filtro=["time","NoMemory"];
	filtroX=["0",NoMemory];
	AjaxTabla(filtro,filtroX,"TablaMain","MainTabla","HTMLS/AjaxTabla.php",false);
`;

var ScriptDescargarTablas=`
	function DescargarTablas(id,NombreDeArchivo){
		Loading();
		setTimeout(
			function(){
				var DivTabla = document.getElementById(id);
				if (typeof(DivTabla) != 'undefined' && DivTabla != null){
					var Tabla = DivTabla.getElementsByTagName("table");
					if (typeof(Tabla[0]) != 'undefined' && Tabla[0] != null){
						var LoadConfig = { raw:'true' };
						var workbook = XLSX.utils.table_to_book(document.getElementById(id),LoadConfig);
						//console.log(workbook);
						if(typeof require !== 'undefined') XLSX = require('xlsx');
						//var wopts = { bookType:'xlsx', bookSST:false, type:'binary', format_cell:'true' };
						var wopts = { format_cell:'true' };
						XLSX.writeFile(workbook, NombreDeArchivo+'.xlsx', wopts );//'format_cell = true'
					}else{
						$.bootstrapGrowl("La Tabla No Existe,Cree La Tabla Para Poder Descargar.", {
							type: 'danger',//danger
							align: 'center',
							width: 'auto'
						});
					}
				}
				EndLoading();
			}, 100
		);
		
		
	}
`;
var ScriptC1 = `
	function Buscar(){
			NoMemory++;
			var paragrap = document.getElementById("Paragrap");
			paragrap.innerHTML = "";
			var FechaI = document.getElementById("FechaI");
			var FechaF = document.getElementById("FechaF");
			if(FechaI.value!='' && FechaF.value!=''){
			filtro=["FechaI","FechaF","time","NoMemory"];
			filtroX=[FechaI.value,FechaF.value,"0",NoMemory];
			AjaxTabla(filtro,filtroX,"TablaMain","MainTabla","HTMLS/AjaxC1.php",false);
		}else{
			var paragrap = document.getElementById("Paragrap");
			paragrap.innerHTML = "Se Requiere Fecha Inicial Y Final Para Busqueda";
		}
	}
`;
var ScriptC2 = `
	function Buscar(){
		var paragrap = document.getElementById("Paragrap");
		paragrap.innerHTML = "";
		var FechaI = document.getElementById("FechaI");
		var FechaF = document.getElementById("FechaF");
		
		//alert(Fecha.value);
		if(FechaI.value!='' && FechaF.value){// && Transportista.value!='' && Cliente.value!=''
			filtro=["FechaI","FechaF","time"];
			filtroX=[FechaI.value,FechaF.value,time];
			AjaxTabla(filtro,filtroX,"TablaMain","MainTabla","http://misenvios.com.ar:8081/MisAjax/AjaxC2",false);
		}else{
			//alert(FechaInicialDeFiltro.value + " " + FechaFinalDeFiltro.value);// + " " + Transportista.value  + " " +  Cliente.value
			//alert("Se Requiere Fecha Para Busqueda");
			var paragrap = document.getElementById("Paragrap");
			paragrap.innerHTML = "Se Requiere Fecha Inicial Y Final Para Busqueda";
		}
	}
`;
var ScriptC3 = `
	function Buscar(){
		NoMemory++;
		var paragrap = document.getElementById("Paragrap");
		paragrap.innerHTML = "";
		
		var FechaI = document.getElementById("FechaI");
		var FechaF = document.getElementById("FechaF");
		if(FechaI.value!='' && FechaF.value!=''){// && Transportista.value!='' && Cliente.value!=''
			filtro=["UserId","FechaI","FechaF","time","NoMemory"];
			filtroX=[1,FechaI.value,FechaF.value,time,NoMemory];// var UserId = 1 ocasa
			AjaxTabla(filtro,filtroX,"TablaMain","MainTabla","HTMLS/AjaxC3.php",false);
		}else{
			//alert(FechaInicialDeFiltro.value + " " + FechaFinalDeFiltro.value);// + " " + Transportista.value  + " " +  Cliente.value
			//alert("Se Requiere Fecha Para Busqueda");
			var paragrap = document.getElementById("Paragrap");
			paragrap.innerHTML = "Se Requiere Fecha Inicial Y FinalPara Busqueda";
		}
		
	}
`;
var ScriptC4 = `
	function Buscar(){
		NoMemory++;
		var paragrap = document.getElementById("Paragrap");
		paragrap.innerHTML = "";
		
		var FechaI = document.getElementById("FechaI");
		var FechaF = document.getElementById("FechaF");
		if(FechaI.value!='' && FechaF.value!=''){// && Transportista.value!='' && Cliente.value!=''
			filtro=["UserId","FechaI","FechaF","time","NoMemory"];
			filtroX=[UserId,FechaI.value,FechaF.value,time,NoMemory];
			AjaxTabla(filtro,filtroX,"TablaMain","MainTabla","HTMLS/AjaxC4.php",false);
		}else{
			//alert(FechaInicialDeFiltro.value + " " + FechaFinalDeFiltro.value);// + " " + Transportista.value  + " " +  Cliente.value
			//alert("Se Requiere Fecha Para Busqueda");
			var paragrap = document.getElementById("Paragrap");
			paragrap.innerHTML = "Se Requiere Fecha Inicial Y FinalPara Busqueda";
		}
		
	}
`;



var ScriptC5 = `
	function Buscar(){
		NoMemory++;
		var paragrap = document.getElementById("Paragrap");
		paragrap.innerHTML = "";
		
		var FechaI = document.getElementById("FechaI");
		var FechaF = document.getElementById("FechaF");
		if(FechaI.value!='' && FechaF.value!=''){// && Transportista.value!='' && Cliente.value!=''
			filtro=["UserId","FechaI","FechaF","time","NoMemory"];
			filtroX=[UserId,FechaI.value,FechaF.value,time,NoMemory];
			AjaxTabla(filtro,filtroX,"TablaMain","MainTabla","HTMLS/AjaxC5.php",false);
		}else{
			//alert(FechaInicialDeFiltro.value + " " + FechaFinalDeFiltro.value);// + " " + Transportista.value  + " " +  Cliente.value
			//alert("Se Requiere Fecha Para Busqueda");
			var paragrap = document.getElementById("Paragrap");
			paragrap.innerHTML = "Se Requiere Fecha Inicial Y FinalPara Busqueda";
		}
		
	}
`;

var ScriptConsultaGlobal = `
	function Buscar(){
		NoMemory++;
		var paragrap = document.getElementById("Paragrap");
		paragrap.innerHTML = "";
		
		var FechaI = document.getElementById("FechaI");
		var FechaF = document.getElementById("FechaF");
		if(FechaI.value!='' && FechaF.value!=''){// && Transportista.value!='' && Cliente.value!=''
			filtro=["UserId","FechaI","FechaF","time","NoMemory"];
			filtroX=[UserId,FechaI.value,FechaF.value,time,NoMemory];
			AjaxTabla(filtro,filtroX,"TablaMain","MainTabla","HTMLS/AjaxConsultaGlobal.php",false);
		}else{
			//alert(FechaInicialDeFiltro.value + " " + FechaFinalDeFiltro.value);// + " " + Transportista.value  + " " +  Cliente.value
			//alert("Se Requiere Fecha Para Busqueda");
			var paragrap = document.getElementById("Paragrap");
			paragrap.innerHTML = "Se Requiere Fecha Inicial Y FinalPara Busqueda";
		}
		
	}
`;
