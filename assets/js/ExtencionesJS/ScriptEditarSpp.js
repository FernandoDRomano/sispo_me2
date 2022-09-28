var ScriptEditarSpp = `
	function Buscar(){
		NoMemory++;
		var paragrap = document.getElementById("Paragrap");
		paragrap.innerHTML = "";
		var IdOBarcode = document.getElementById("IdOBarcode");
		var Barcode = document.getElementById("Barcode");
		if(!Needed("IdOBarcode",0)){
			return;
		}
		if(!Needed("Barcode",0)){
			return;
		}
		
		if(IdOBarcode.value!='0' && Barcode.value!=''){// && Transportista.value!='' && Cliente.value!=''
			filtro=[IdOBarcode.value,"time","NoMemory"];
			filtroX=[Barcode.value,"0",NoMemory];
			AjaxTabla(filtro,filtroX,"TablaMain","MainTabla","http://misenvios.com.ar:8081/MisAjax/AjaxAsignadaAceptada",false);
		}else{
			//alert(FechaInicialDeFiltro.value + " " + FechaFinalDeFiltro.value);// + " " + Transportista.value  + " " +  Cliente.value
			//alert("Se Requiere Fecha Para Busqueda");
			var paragrap = document.getElementById("Paragrap");
			paragrap.innerHTML = "Se Requiere Datos";
		}
	}
	
	function EditarEstadoSppMenosUnDia(){
		NoMemory++;
		var paragrap = document.getElementById("Paragrap");
		paragrap.innerHTML = "";
		var Estado = document.getElementById("Estado");
		if(!Needed("Estado",0)){
			return;
		}
		var funcion = 'Buscar()';
		filtro=["Estado","time","NoMemory"];
		filtroX=[Estado.value,"0",NoMemory];
		AjaxParagrapAndCall(filtro,filtroX,"http://misenvios.com.ar:8081/MisAjax/AjaxEditarEstadoSppMenosUnDia",funcion);
	}
	
	function EditarEstadoSppMasUnDia(){
		NoMemory++;
		var paragrap = document.getElementById("Paragrap");
		paragrap.innerHTML = "";
		var Estado = document.getElementById("Estado");
		if(!Needed("Estado",0)){
			return;
		}
		var funcion = 'Buscar()';
		filtro=["Estado","time","NoMemory"];
		filtroX=[Estado.value,"0",NoMemory];
		AjaxParagrapAndCall(filtro,filtroX,"http://misenvios.com.ar:8081/MisAjax/AjaxEditarEstadoSppMasUnDia",funcion);
	}
	
	
	function EditarEstadoSppMenosUnaHora(){
		NoMemory++;
		var paragrap = document.getElementById("Paragrap");
		paragrap.innerHTML = "";
		var Estado = document.getElementById("Estado");
		if(!Needed("Estado",0)){
			return;
		}
		var funcion = 'Buscar()';
		filtro=["Estado","time","NoMemory"];
		filtroX=[Estado.value,"0",NoMemory];
		AjaxParagrapAndCall(filtro,filtroX,"http://misenvios.com.ar:8081/MisAjax/AjaxEditarEstadoSppMenosUnaHora",funcion);
	}
	
	function EditarEstadoSppMasUnaHora(){
		NoMemory++;
		var paragrap = document.getElementById("Paragrap");
		paragrap.innerHTML = "";
		var Estado = document.getElementById("Estado");
		if(!Needed("Estado",0)){
			return;
		}
		var funcion = 'Buscar()';
		filtro=["Estado","time","NoMemory"];
		filtroX=[Estado.value,"0",NoMemory];
		AjaxParagrapAndCall(filtro,filtroX,"http://misenvios.com.ar:8081/MisAjax/AjaxEditarEstadoSppMasUnaHora",funcion);
	}
`;