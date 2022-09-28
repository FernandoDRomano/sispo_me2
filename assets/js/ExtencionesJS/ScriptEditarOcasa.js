var ScriptEditarOcasa = `
	AjaxArraidToSelect("EstadoDeLaPieza","HTMLS/AjaxListarEstadosAPP.php");
	AjaxArraidToSelect("Vinculo","HTMLS/AjaxListarVinculo.php");
	jQuery(document).ready(function() {
		Texto("Estado","BoltTextEstado",1,"Ponga El id De Estado Para Borrar O Editar.","Ponga El id De Estado Para Borrar O Editar");
		Change("IdOBarcode","BoltTextIdOBarcode","");
		Elementos=["Vinculo","ApellidoYNombres","DNI"];
		ElementosTextos=["BoltTextVinculo","BoltTextApellidoYNombres","BoltTextDNI"];
		ChangeCompleteSelect("EstadoDeLaPieza","BoltTextEstadoDeLaPieza","Seleccione Para Editr Estados","HTMLS/AjaxListarVinculo.php",Elementos,ElementosTextos);
	});
	function Buscar(){
		NoMemory++;
		var paragrap = document.getElementById("Paragrap");
		paragrap.innerHTML = "";
		var IdOBarcode = document.getElementById("IdOBarcode");
		var Barcode = document.getElementById("Barcode");
		if(IdOBarcode.value!='0' && Barcode.value!=''){// && Transportista.value!='' && Cliente.value!=''
			filtro=[IdOBarcode.value,"time","NoMemory"];
			filtroX=[Barcode.value,"0",NoMemory];
			AjaxTabla(filtro,filtroX,"TablaMain","MainTabla","HTMLS/AjaxGetEstadosOcasa.php",false);
		}else{
			//alert(FechaInicialDeFiltro.value + " " + FechaFinalDeFiltro.value);// + " " + Transportista.value  + " " +  Cliente.value
			//alert("Se Requiere Fecha Para Busqueda");
			var paragrap = document.getElementById("Paragrap");
			paragrap.innerHTML = "Se Requiere Datos";
		}
	}
	
	function BorrarEstado(){
		NoMemory++;
		var paragrap = document.getElementById("Paragrap");
		paragrap.innerHTML = "";
		var Estado = document.getElementById("Estado");
		if(Estado.value!='0' && Estado.value!=''){
			filtro=["Estado","time","NoMemory"];
			filtroX=[Estado.value,"0",NoMemory];
			AjaxParagrap(filtro,filtroX,"HTMLS/AjaxBorrarEstado.php")
		}else{
			if (typeof $.bootstrapGrowl === "function") {
				$.bootstrapGrowl("Requiere Id De Estado Para Borrar", {
					type: 'danger',//danger
					align: 'center',
					width: 'auto'
				});
			}
		}
	}
	
	
	function EditarEstado(){
		NoMemory++;
		var paragrap = document.getElementById("Paragrap");
		paragrap.innerHTML = "";
		var Barcode = document.getElementById("Barcode");
		var Estado = document.getElementById("Estado");
		var IdOBarcode = document.getElementById("IdOBarcode");
		var EstadoDeLaPieza = document.getElementById("EstadoDeLaPieza");
		var Vinculo = document.getElementById("Vinculo");
		var ApellidoYNombres = document.getElementById("ApellidoYNombres");
		var DNI = document.getElementById("DNI");
		var FechaI = document.getElementById("FechaI");
		if(IdOBarcode.value==0){
			paragrap.innerHTML = "<b>Seleccione ID O Barcode.</b>";
			IdOBarcode.focus();
			return;
		}
		
		if(EstadoDeLaPieza.value==0){
			$.bootstrapGrowl("<b>Seleccione Estado De La Pieza.</b>", {
				type: 'danger',//danger
				align: 'center',
				width: 'auto'
			});
			EstadoDeLaPieza.focus();
			return;
		}
		
		if(EstadoDeLaPieza.value=="Entregado"){
			if(Vinculo.value==0){
				$.bootstrapGrowl("<b>Seleccione Vínculo.</b>", {
					type: 'danger',//danger
					align: 'center',
					width: 'auto'
				});
				Vinculo.focus();
				return;
			}
			if(ApellidoYNombres.value==""){
				$.bootstrapGrowl("<b>Ponga El Apellido Y Los Nombres.</b>", {
					type: 'danger',//danger
					align: 'center',
					width: 'auto'
				});
				Vinculo.focus();
				return;
			}
			if(DNI.value==""){
				$.bootstrapGrowl("<b>Ponga El DNI.</b>", {
					type: 'danger',//danger
					align: 'center',
					width: 'auto'
				});
				Vinculo.focus();
				return;
			}
			if(FechaI.value==""){
				$.bootstrapGrowl("<b>Ponga La Fecha De La Visita.</b>", {
					type: 'danger',//danger
					align: 'center',
					width: 'auto'
				});
				Vinculo.focus();
				return;
			}
			filtro=["Estado",IdOBarcode.value,"vinculo","recibio","documento","time" ,"fecha","versionAPP","Lat","Lng","Exactitud","Altitude","tipo","Estados","NoMemory"];
			filtroX=[Estado.value,Barcode.value,Vinculo.value,ApellidoYNombres.value,DNI.value,FechaI.value,FechaI.value,"0","-26.8329531","-65.2199226","1","1.0","CentroDeControl", EstadoDeLaPieza.value,NoMemory];
			AjaxParagrap(filtro,filtroX,"HTMLS/AjaxEditarEstado.php");
		}else{
			if(FechaI.value==""){
				$.bootstrapGrowl("<b>Ponga La Fecha De La Visita.</b>", {
					type: 'danger',//danger
					align: 'center',
					width: 'auto'
				});
				Vinculo.focus();
				return;
			}
			filtro=["Estado",IdOBarcode.value,"vinculo","recibio","documento","time" ,"fecha","versionAPP","Lat","Lng","Exactitud","Altitude","tipo","Estados","NoMemory"];
			filtroX=[Estado.value,Barcode.value,Vinculo.value,ApellidoYNombres.value,DNI.value,FechaI.value,FechaI.value,"0","-26.8329531","-65.2199226","1","1.0","CentroDeControl", EstadoDeLaPieza.value,NoMemory];
			AjaxParagrap(filtro,filtroX,"HTMLS/AjaxEditarEstado.php");
		}
		
	}
	
`;