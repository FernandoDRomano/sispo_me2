var ScriptConsultaGlobalTuenti = `
	function Buscar(){
		NoMemory++;
		var paragrap = document.getElementById("Paragrap");
		paragrap.innerHTML = "";
		var FechaI = document.getElementById("FechaI");
		var FechaF = document.getElementById("FechaF");
		if(FechaI.value!='' && FechaF.value!=''){// && Transportista.value!='' && Cliente.value!=''
			filtro=["UserId","FechaI","FechaF","time","NoMemory"];
			filtroX=[UserId,FechaI.value,FechaF.value,time,NoMemory];
			AjaxTabla(filtro,filtroX,"TablaMain","MainTabla","HTMLS/AjaxConsultaGlobalTuenti.php",false);//AjaxTuentiGPS.php
		}else{
			//alert(FechaInicialDeFiltro.value + " " + FechaFinalDeFiltro.value);// + " " + Transportista.value  + " " +  Cliente.value
			//alert("Se Requiere Fecha Para Busqueda");
			
			//var paragrap = document.getElementById("Paragrap");
			//paragrap.innerHTML = "Se Requiere Fecha Inicial Y FinalPara Busqueda";
			if (typeof $.bootstrapGrowl === "function") {
				$.bootstrapGrowl( "Se Requiere Fecha Inicial Y FinalPara Busqueda", {
					type: 'danger',//danger
					align: 'center',
					width: 'auto'
				});
			}
			return;
			
		}
		
	}
`;