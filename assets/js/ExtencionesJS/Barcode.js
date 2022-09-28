var Imagenes = new Array();
function BarcodeSet(Texto,Cantidad, Margen){
	
	//console.log(Texto);
	var Barcode = document.getElementById("barcode");
	//Barcode.
	JsBarcode("#barcode",Texto);
	var svg = document.querySelector("svg");
	var svgData = new XMLSerializer().serializeToString(svg);
	canvas = document.createElement("canvas");
	var ctx = canvas.getContext("2d");
	var img = document.createElement("img");
	img.setAttribute("src","data:image/svg+xml;base64,"+btoa(svgData));
	img.onload = function(){
		ctx.drawImage(img,0,0);
		Imagenes.push(canvas.toDataURL("image/png"));
		//console.log(canvas.toDataURL("image/png"));
	};
	Element.prototype.remove = function(){
		this.parentElement.removeChild(this);
	}
	NodeList.prototype.remove = HTMLCollection.prototype.remove = function(){
		for(var i = this.length - 1; i >= 0; i--) {
			if(this[i] && this[i].parentElement) {
				this[i].parentElement.removeChild(this[i]);
			}
		}
	}
	//document.getElementById("barcode").remove();
	for(var i=0;i<Cantidad;i++){
		var oImg = document.createElement("img");
		oImg.setAttribute('src', img.src);
		oImg.setAttribute('alt', 'na');
		oImg.setAttribute('align', "left");
		oImg.setAttribute('class', "ImagenBC");
		
		var YaExiste = '#' + Texto + '';
		if ($(YaExiste).length){
			if(typeof $.bootstrapGrowl === "function") {
				$.bootstrapGrowl("<p>Antes De Crear Mas Codigos De Barra Requiere Limpiar Pantalla</p> <p>Concluya Su Trabajo <b>Limpie Pantalla</b> y Luego Podra Crear Nuevos Codigos De Barra<p>",{
					type: 'danger',
					align: 'center',
					width: 'auto'
				});
			}else{
			alert("Antes De Crear Mas Codigos De Barra Requiere Limpiar Pantalla, Concluya Su Trabajo Limpie Pantalla y Luego Podra Crear Nuevos Codigos De Barra");
			}
			//location.reload();
			return;
		}
		oImg.id = Texto;
		/*
		var Barcode = document.createElement("a");
		Barcode.appendChild(oImg);
		*/
		var Salto = document.createElement("br");
		var SaltoF = document.createElement("br");
		var LinkBotonBarcode = document.getElementById("LinkBotonCodigoDeBarra");
		LinkBotonBarcode.appendChild(oImg);
	}
}


