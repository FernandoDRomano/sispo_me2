var FechaHDR = `
		function PonerFechaHDR(){
			var HDR = document.getElementById("HDR");
			if(HDR.value==""){
				if (typeof $.bootstrapGrowl === "function") {
					$.bootstrapGrowl( "Ponga HDR Para Poner Fecha", {
						type: 'danger',//danger
						align: 'center',
						width: 'auto'
					});
				}
				return;
			}
			var Fecha = document.getElementById("Fecha");
			if(Fecha.value==""){
				 if (typeof $.bootstrapGrowl === "function") {
					$.bootstrapGrowl( "Ponga Fecha Para HDR", {
						type: 'danger',//danger
						align: 'center',
						width: 'auto'
					});
				}
				return;
			}
			filtro=["HDR","Fecha","NoMemory"];
			filtroX=[HDR.value,Fecha.value,NoMemory];
			AjaxParagrap(filtro,filtroX,"HTMLS/AjaxPonerFechaAHDR.php")
		}
		jQuery(document).ready(function() {
			Texto("HDR","BoltTextHDR",5,"Ponga HDR Para Editar La Fecha.","Ponga HDR Para Editar La Fecha.");
		});
	`;