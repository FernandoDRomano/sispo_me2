var ScriptTerceros=`
		//var ArraydTabla = ["Obs","parentesco","nombreapellido","documento","FechaRendicion","FechaVirtual","Latitud","Longitid","Exactitud","Altitud","NovedadTexto"];
		var ArraydTabla = ["idPieza","parentesco","nombreapellido","documento","FechaRendicion","Latitud","Longitid","Exactitud","Altitud","Novedad"];//,"FechaRendicion","FechaVirtual"
		function UploadTabla(idDivTabla,arraydVars){
			//console.log(arraydVars);
			//return;
			var DivTabla = document.getElementById(idDivTabla);
			if (typeof(DivTabla) != 'undefined' && DivTabla != null){
				var Tabla = DivTabla.getElementsByTagName("table");
				if (typeof(Tabla[0]) != 'undefined' && Tabla[0] != null){
					var table = Tabla[0];
					var Lineas = table.rows.length;
					var TextUpload = document.getElementById("TextUpload");
					TextUpload.innerHTML="Subiendo";
					var LoadBar = document.getElementById("LoadBar");
					FloatLoadBar = 0 ;
					FloatNoLoadBar = 0 ;
					LoadBar.style.width= FloatLoadBar+"%";
					
					
					
					for (var i = 1 ; i < Lineas; i++){
						var Columnas = table.rows[i].cells.length;
						var Pre = "(";
						var Pos = ")";
						var id = "";
						var piezas = new Array();
						
						if(ArraydTabla.length > Columnas){
							return;
						}
						for(var j = 0 ; j < ArraydTabla.length; j++){
							
							
							var CheckBox = document.getElementById("CorrectorDeFecha"+j);
							if(CheckBox.checked){
								var fecha=table.rows[i].cells[j].innerHTML.replace(/[^0-9::]/g,'-');
								if(fecha.length==19){
									var arraydfecha = fecha.split("-");
									if(arraydfecha[0].length==2 && arraydfecha[1].length==2 && arraydfecha[2].length==4){
										var Reversse = fecha.substring(6, 10) + "-" + fecha.substring(3,5)+"-" + fecha.substring(0, 2)+" " + fecha.substring(11);
									}else{
										Reversse = fecha;
									}
								}
								if(fecha.length==10){// Formato MM DD YYYY
									var arraydfecha = fecha.split("-");
									if(arraydfecha[0].length==2 && arraydfecha[1].length==2 && arraydfecha[2].length==4){
										var Reversse = fecha.substring(6, 10) + "-" + fecha.substring(0, 2) + "-" + fecha.substring(3,5);
									}else{
										Reversse = fecha;
									}
									//alert(Reversse);
								}
								piezas.push(Reversse);
								//piezas=piezas + ",'" + Reversse + "'";
								
							}else{
								//piezas=piezas + ",'"+table.rows[i].cells[j].innerHTML + "'";
								piezas.push(table.rows[i].cells[j].innerHTML);
							}
						}
						//piezas=piezas+Pos;
						Elementos=arraydVars;
						ElementosTextos=piezas;
						//alert(Elementos);
						//alert(ElementosTextos);
						AjaxParts(Lineas-1,Elementos,ElementosTextos,"HTMLS/AjaxTablaToDB.php");
						
						//alert(piezas);
					}
				}else{
					$.bootstrapGrowl("Cárgue El Documento Exel A Una Tabla Para Verificar Su Contenido, Luego Podra Cargar La Tabla A Nuesta Base De Datos.", {
						type: 'danger',//danger
						align: 'center',
						width: 'auto'
					});
				}
			}
		}
	`;