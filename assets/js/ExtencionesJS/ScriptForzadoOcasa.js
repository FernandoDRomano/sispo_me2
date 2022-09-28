var ScriptForzadoOcasa = `
	AjaxArraidToSelect("EstadoDeLaPieza","HTMLS/AjaxListarEstadosAPP.php");
	AjaxArraidToSelect("Vinculo","HTMLS/AjaxListarVinculo.php");
	
	
	jQuery(document).ready(function() {
		Change("IdOBarcode","BoltTextIdOBarcode","Declare La Pieza Como Codigo De Barra (Barcode) O Por Identificador (id).");
		Elementos=["Vinculo","ApellidoYNombres","DNI"];
		ElementosTextos=["BoltTextVinculo","BoltTextApellidoYNombres","BoltTextDNI"];
		ChangeCompleteSelect("EstadoDeLaPieza","BoltTextEstadoDeLaPieza","Seleccione Para Verificar Estados","HTMLS/AjaxListarVinculo.php",Elementos,ElementosTextos);
	});
	
	function PonerEstado(){
			NoMemory++;
			var paragrap = document.getElementById("Paragrap");
			paragrap.innerHTML = "";
			var Barcode = document.getElementById("Barcode");
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
				paragrap.innerHTML = "<b>Seleccione Estado De La Pieza.</b>";
				EstadoDeLaPieza.focus();
				return;
			}
			
			if(EstadoDeLaPieza.value=="Entregado"){
				if(Vinculo.value==0){
					paragrap.innerHTML = "<b>Seleccione Vínculo.</b>";
					Vinculo.focus();
					return;
				}
				if(ApellidoYNombres.value==""){
					paragrap.innerHTML = "<b>Ponga El Apellido Y Los Nombres.</b>";
					Vinculo.focus();
					return;
				}
				if(DNI.value==""){
					paragrap.innerHTML = "<b>Ponga El DNI.</b>";
					Vinculo.focus();
					return;
				}
				if(FechaI.value==""){
					paragrap.innerHTML = "<b>Ponga La Fecha De La Visita.</b>";
					Vinculo.focus();
					return;
				}
				
				filtro=[IdOBarcode.value,"vinculo","recibio","documento","time" ,"fecha","versionAPP","Lat","Lng","Exactitud","Altitude","tipo","Estados","NoMemory"];
				filtroX=[Barcode.value,Vinculo.value,ApellidoYNombres.value,DNI.value,FechaI.value,FechaI.value,"0","-26.8329531","-65.2199226","1","1.0","CentroDeControl", EstadoDeLaPieza.value,NoMemory];
				AjaxParagrap(filtro,filtroX,"HTMLS/AjaxForzadoOcasa.php");
				
			}else{
				filtro=[IdOBarcode.value,"vinculo","recibio","documento","time" ,"fecha","versionAPP","Lat","Lng","Exactitud","Altitude","tipo","Estados","NoMemory"];
				filtroX=[Barcode.value,Vinculo.value,ApellidoYNombres.value,DNI.value,FechaI.value,FechaI.value,"0","-26.8329531","-65.2199226","1","1.0","CentroDeControl", EstadoDeLaPieza.value,NoMemory];
				AjaxParagrap(filtro,filtroX,"HTMLS/AjaxForzadoOcasa.php");
			}
			
		}
		
`;