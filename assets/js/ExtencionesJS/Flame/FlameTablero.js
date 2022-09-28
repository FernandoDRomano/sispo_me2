//Como LLamar A LA Funcion De AutoCompletado De Graficos

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/*
<div class="col-sm-3 col-xs-12">
	<div id="divColor1" class="dashboard-stat purple-plum MaximixedTable" Uncolor="MaximixedTable" HideClass="Sizable" Sizable="SizableTarjetasIngresadas" onclick="javascript:VerItem('TablaTarjetasIngresadas','DivTarjetasIngresadas','SizableTarjetasIngresadas','1');">
		<div class="visual"><i class="fa fa-globe"></i></div>
		<div class="details">
			<div id="" class="number">0</div>
			<div class="desc">Tarjetas Ingresadas</div>
		</div>
	</div>
</div>

Creo Un Elemento De Clase (MaximixedTable)
Con Atributos 
	Uncolor="MaximixedTable" Elementos A Los Que Les Quitara Color De Foco (MaximixedTable) Si Solo Existe Un Conjunto Que Tendra El Foco,
		Para Agregar Mas Conjuntos Requiero Agregar Mas Clases al Elemento
	Sizable="SizableTarjetasIngresadas" Nombre De div Que Contiene La Tabla.
*/
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$(document).ready(function() {
	$( ".MaximixedTable" ).click(function() {
		HideAndFullScreen(this);
	});
});

function HideFullScreen(HideClass){
	//alert(HideClass);
	var Elements = document.getElementsByClassName(HideClass); //MaximixedTable
	//alert(Elements.length);
	for(var i =0 ; i < Elements.length ; i++){
		if(typeof Elements[i] === 'undefined'){
			//console.log(" Elements.length. Fuera De Rango");
			return;
		}
		var Sizable = Elements[i];
		//console.log(Sizable);
		Sizable.setAttribute("class", "portlet light");
		Sizable.style.display="none";
		
		var Divs = Sizable.getElementsByTagName("div");
		//console.log(Divs);
		for(var i = 0 ;i < Divs.length ;i++){
			if(Divs[i].Config != null){
				var tabla = Divs[i].getElementsByTagName('table')[0];
				//var tabla = document.getElementById(Divs[i].Config.Tabla);
				//console.log(tabla);
				if(typeof tabla !== 'undefined'){
					var ElementosDeTabla = tabla.getElementsByTagName("tr");
					
					var SaltarElemento = 0;
					while(ElementosDeTabla.length > SaltarElemento){
						if(ElementosDeTabla[SaltarElemento].className == "Escaner"){SaltarElemento++;}
						else{ElementosDeTabla[SaltarElemento].remove();}
					}
					/*
					while(ElementosDeTabla.length > 0){
						ElementosDeTabla[0].remove();
					}
					*/
				}
				
			}
		}
		
	}
}

function UncolorFullScreen(Uncolor){
	var Elements = document.getElementsByClassName(Uncolor);
	var colorFullScreen = Elements[0];
	while(Elements.length > 0 ){
		if(typeof Elements[0] === 'undefined'){
			return;
		}
		//colorFullScreen.className.includes(Uncolor)
		colorFullScreen.setAttribute("class", "dashboard-stat purple-plum");
		var Elements = document.getElementsByClassName(Uncolor);
		colorFullScreen = Elements[0];
	}
}

function HideAndFullScreen(e){
	Uncolor = e.getAttribute("Uncolor");
	UncolorFullScreen(Uncolor);
	//UncolorFullScreen(Uncolor);
	e.setAttribute("class", "dashboard-stat green-haze " + Uncolor);
	
	NombreSizable = e.getAttribute("Sizable");
	HideFullScreen("Sizable");
	if(NombreSizable == null){
		alert("Falta Atributo Sizable En Elemento De Classe MaximixedTable");
		return;
	}
	Sizable = document.getElementById(NombreSizable);
	if(typeof Sizable === 'undefined' || Sizable == null){
		alert("El Elemento " + NombreSizable + " No Existe");
		return;
	}
	Sizable.setAttribute("class", "portlet light portlet-fullscreen Sizable");
	Sizable.style.display="inline";
	
	var Divs = Sizable.getElementsByTagName("div");
	for(var i = 0 ;i < Divs.length ;i++){
		if(Divs[i].Config != null){
			CrearTabla(Divs[i].Config);
		}
	}
	
}











