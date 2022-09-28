var ScriptLoadXLS=`
	//var OrdenEsperado = ["Novedad Texto","Fecha Novedad","Nro Pieza","Fecha Alta","Nro Orden","Destinatario","Domicilio","C.Postal","Localidad","Obs","Producto","Hoja de Ruta","Nro Rendicion","Fecha Rendicion","Sucursal","Bulto","importe","Fecha Entrega","Fecha Entreda 2","Fecha Entrega 3","Kilos","Reembolso","Obs a Carga","C.Costo"];
	//var OrdenEsperado = ["Obs","Novedad Texto","FechaVirtual","Nro Pieza","Destinatario","Domicilio","C.Postal","Localidad","Fecha Alta","parentesco","nombreapellido","documento"
	
	//var OrdenEsperado = ["Obs","parentesco","nombreapellido","documento","Fecha Rendicion","FechaVirtual","Latitud","Longitid","Exactitud","Altitud","Novedad Texto"];
	var OrdenEsperado = ["idPieza","parentesco","nombreapellido","documento","FechaRendicion","Latitud","Longitid","Exactitud","Altitud","Novedad"];//,"FechaRendicion","FechaVirtual"
	//var ArraydTabla = ["idPieza","parentesco","nombreapellido","documento","Fecha Rendicion","Latitud","Longitid","Exactitud","Altitud"];//,"FechaRendicion","FechaVirtual"
	//var OrdenEsperado = ["Obs","Novedad Texto","FechaVirtual","Nro Pieza","Destinatario","Domicilio","C.Postal","Localidad","Fecha Alta","parentesco","nombreapellido","documento"
	//,"Nro Orden","Obs","Producto","Hoja de Ruta","Nro Rendicion","Fecha Rendicion","Sucursal","Bulto","importe","Fecha Entrega","Fecha Entreda 2","Fecha Entrega 3","Kilos","Reembolso","Obs a Carga","C.Costo"
	
	
	
	jQuery('input[type=file]').change(function(){
		var filename = jQuery(this).val().split('\\\\').pop();
		var idname = jQuery(this).attr('id');
		var idDivColumnItem = jQuery(this).attr('DivColumnItem');
		var idButtonUpload = jQuery(this).attr('ButtonUpload');
		
		//alert(idDivColumnItem);
		//console.log(jQuery(this));
		//console.log(filename);
		//console.log(idname);
		jQuery('span.'+idname).next().find('span').html(filename);
		XLSSeleccionarColumnas(idname,idDivColumnItem,idButtonUpload);
	});
	function XLSSeleccionarColumnas(id,idDivColumnItem,idButtonUpload) {
		var ItemButtonUpload = document.getElementById(idButtonUpload);
		var fileUpload = document.getElementById(id);
		
        var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$/;
        if ( BuscarExtencion(fileUpload.value.toLowerCase(),"xls") || BuscarExtencion(fileUpload.value.toLowerCase(),"xlsx") || BuscarExtencion(fileUpload.value.toLowerCase(),"csv") ) {
            if (typeof (FileReader) != "undefined") {
                var reader = new FileReader();
                if (reader.readAsBinaryString) {
                    reader.onload = function (e) {
                        if(SacarColunnas(e.target.result,idDivColumnItem)){
						}else{
							ItemButtonUpload.style.display = "none";
						}
                    };
                    reader.readAsBinaryString(fileUpload.files[0]);
                } else {
                    reader.onload = function (e) {
                        var data = "";
                        var bytes = new Uint8Array(e.target.result);
                        for (var i = 0; i < bytes.byteLength; i++) {
                            data += String.fromCharCode(bytes[i]);
                        }
                        if(SacarColunnas(data,idDivColumnItem)){
							
						}else{
							ItemButtonUpload.style.display = "none";
						}
                    };
                    reader.readAsArrayBuffer(fileUpload.files[0]);
                }
				ItemButtonUpload.style.display = "inline-block";
				/*
				$.bootstrapGrowl("bootstrapGrowl success.", {
					type: 'success',
					align: 'center',
					width: 'auto'
				});
				*/
            } else {
                //alert("This browser does not support HTML5.");
				
				ItemButtonUpload.style.display = "none";
				$.bootstrapGrowl("El Explorador No Soporta HTML5", {
					type: 'danger',//danger
					align: 'center',
					width: 'auto'
				});
            }
        } else {
			ItemButtonUpload.style.display = "none";
			$.bootstrapGrowl("Seleccione Documento De Exel Valido", {
				type: 'danger',//danger
				align: 'center',
				width: 'auto'
			});
            //alert("Documento De Exel No Valido.");
        }
	}
	function SacarColunnas(data,idDivColumnItem) {
        var workbook = XLSX.read(data, {
            type: 'binary', cellDates:true, cellStyles:true
        });
        var firstSheet = workbook.SheetNames[0];
		console.log(firstSheet);
		
        var excelRows = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet], {blankRows: false, defval: '-'});
        var table = document.createElement("table");
		table.id="TablaDeSelects"+idDivColumnItem;
        table.border = "1";
        var row = table.insertRow(-1);
        var headerCell = document.createElement("TH");
		var myJSON = excelRows[0];
		var ArraydNombreDeColumna = new Array();
		var i=0;
		for (x in myJSON) {
			if(x=="__EMPTY"){break;}
			ArraydNombreDeColumna[i] = x;
			i++;
		}
		//alert(ArraydNombreDeColumna.length);
		if(ArraydNombreDeColumna.length>30){
			$.bootstrapGrowl("La Cantidad Maxima De Columnas A Cargar Es De 30", {
				type: 'danger',//danger
				align: 'center',
				width: 'auto'
			});
			return false;
		}
		for (var ii=0;ii<ArraydNombreDeColumna.length;ii++) {
			headerCell = document.createElement("TH");
			//<label><input type="checkbox" id="cbox1" value="first_checkbox"> Este es mi primer checkbox</label><br>
			var CorrectorDeFecha = document.createElement("input");
			CorrectorDeFecha.type="checkbox";
			CorrectorDeFecha.id="CorrectorDeFecha"+ii;
			
			CorrectorDeFecha.style="transform: scale(2)";
			//CorrectorDeFecha.innerHTML="Corrector De Fechas";
			headerCell.appendChild(CorrectorDeFecha);
			var Texto = document.createElement("b");
			Texto.setAttribute("style", "");
			//var Text = document.createTextNode("Reparar Fecha");
			//Texto.appendChild(Text);
			headerCell.appendChild(Texto);
			
			var SelectheaderCell = document.createElement("select");
			SelectheaderCell.setAttribute("class", "form-control select1-Borrado select1-hidden-accessible-Borrado");
			var x = document.getElementsByTagName("BODY")[0];
			SelectheaderCell.style="min-width: " + ((x.scrollWidth - 30)/(ArraydNombreDeColumna.length+1)) +"px;";
			//SelectheaderCell.style="width: fit-content;";
			
			
			
			for(var j = 0; j < ArraydNombreDeColumna.length ; j++){
				var OpcionheaderCell = document.createElement("option");
				OpcionheaderCell.value = ArraydNombreDeColumna[j];
				OpcionheaderCell.text = ArraydNombreDeColumna[j];
				if(ii==j){
					OpcionheaderCell.selected = "true"
				}
				SelectheaderCell.add(OpcionheaderCell);
			}
			headerCell.appendChild(SelectheaderCell);
			row.appendChild(headerCell);
			
			/*
			for(var TempCont = ii; TempCont<OrdenEsperado.length;TempCont++){
				for(var j = 0; j < ArraydNombreDeColumna.length ; j++){
					//alert("OrdenEsperado[j]:"+OrdenEsperado[j]+" ArraydNombreDeColumna[ii]:"+ArraydNombreDeColumna[ii]+" ii"+ii+",jj"+j);
					if(ArraydNombreDeColumna[j]==OrdenEsperado[TempCont]){
						//alert("OrdenEsperado[j]:"+OrdenEsperado[j]+" ArraydNombreDeColumna[ii]:"+ArraydNombreDeColumna[ii]+" j"+j);
						SelectheaderCell.selectedIndex = j;
					}else{
						//alert("OrdenEsperado[j]:"+OrdenEsperado[j]+" ArraydNombreDeColumna[ii]:"+ArraydNombreDeColumna[ii]+" j"+j);
					}
				}
			}
			*/
			/*
			for(var TempCont = 0; TempCont<ArraydNombreDeColumna.length;TempCont++){
				for(var j = 0; j < OrdenEsperado.length ; j++){
					//alert("OrdenEsperado[j]:"+OrdenEsperado[j]+" ArraydNombreDeColumna[ii]:"+ArraydNombreDeColumna[ii]+" ii"+ii+",jj"+j);
					if(ArraydNombreDeColumna[ii]==OrdenEsperado[j]){
						//alert("OrdenEsperado[j]:"+OrdenEsperado[j]+" ArraydNombreDeColumna[ii]:"+ArraydNombreDeColumna[ii]+" j"+j);
						SelectheaderCell.selectedIndex = ii;
					}else{
						//alert("OrdenEsperado[j]:"+OrdenEsperado[j]+" ArraydNombreDeColumna[ii]:"+ArraydNombreDeColumna[ii]+" j"+j);
					}
				}
			}
			*/
		}
		
		
		var ArraydSelect = table.getElementsByTagName("select");
		//alert("Cantidad De Select En Tabla:" + ArraydSelect.length);
		var ColumnasEncontradas = 0 ;
		for (var i = 0 ; i<ArraydNombreDeColumna.length;i++) {//9
			for(var j = 0; j < OrdenEsperado.length ; j++){//0
					if(ArraydNombreDeColumna[i]==OrdenEsperado[j]){
						var PreIndex = ArraydSelect[i].selectedIndex;
						ArraydSelect[j].selectedIndex = i;
						ArraydSelect[i].selectedIndex = PreIndex;
						ColumnasEncontradas++;
						//alert("Original:" + ArraydNombreDeColumna[i] + "i" + i + " Esperado:" + OrdenEsperado[j] + "j" + j + " PreIndex:" + PreIndex);
					}else{
					}
				}
		}
		for (var i = ColumnasEncontradas-1 ; i<ArraydSelect.length;i++) {//9
			if(ArraydSelect[i].value != OrdenEsperado[i] ){
				ArraydSelect[i].selectedIndex = -1;
			}
		}
		
		var indice = 0;
		for (var i = 0 ; i<ArraydSelect.length;i++) {
			var SelectValue = ArraydNombreDeColumna[i];
			if(OrdenEsperado.indexOf(SelectValue) > -1){
				continue;
			}else{
				//alert("i:"+i+" indice:"+indice);
				//ArraydSelect[indice+ColumnasEncontradas].selectedIndex = i;
				indice++;
			}
		}
		
		
        var dvExcel = document.getElementById(idDivColumnItem);
        dvExcel.innerHTML = "";
		
		var PreTabla = document.createElement("h4");
		var TextPreTabla = document.createTextNode("Ponga El Orden Correcto Y Marque Las Columnas Que Contienen Fecha Para Auto Reparar Errores De Formato.");
		PreTabla.className="tituloFormulario form-section";
		PreTabla.appendChild(TextPreTabla);
		dvExcel.appendChild(PreTabla);
		
        dvExcel.appendChild(table);
		return true;
    };
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	function BuscarExtencion(input, expect) {
		var parts = input.split('.');
		if(parts.length>0){
			if (parts[parts.length-1] === expect){
				return(true);
			}else{
				return(false);
			}
		}else{
			return(false);
		}
	}
	function Upload(id,idDivColumnItem) {
        var fileUpload = document.getElementById(id);
        var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$/;
        if ( BuscarExtencion(fileUpload.value.toLowerCase(),"xls") || BuscarExtencion(fileUpload.value.toLowerCase(),"xlsx") || BuscarExtencion(fileUpload.value.toLowerCase(),"csv") ) {
            
			//var idDivColumnItem = jQuery(this).attr('DivColumnItem');
			
			if (typeof (FileReader) != "undefined") {
                var reader = new FileReader();
                if (reader.readAsBinaryString) {
                    reader.onload = function (e) {
                        ProcessExcel(e.target.result,idDivColumnItem);
                    };
                    reader.readAsBinaryString(fileUpload.files[0]);
                } else {
                    reader.onload = function (e) {
                        var data = "";
                        var bytes = new Uint8Array(e.target.result);
                        for (var i = 0; i < bytes.byteLength; i++) {
                            data += String.fromCharCode(bytes[i]);
                        }
                        ProcessExcel(data,idDivColumnItem);
                    };
                    reader.readAsArrayBuffer(fileUpload.files[0]);
                }
            } else {
				$.bootstrapGrowl("El Explorador No Soporta HTML5", {
					type: 'danger',//danger
					align: 'center',
					width: 'auto'
				});
            }
        } else {
			$.bootstrapGrowl("Seleccione Documento De Exel Valido", {
				type: 'danger',//danger
				align: 'center',
				width: 'auto'
			});
        }
    };
	function ExcelDateToJSDate(serial) {
		var utc_days  = Math.floor(serial - 25568);
		var utc_value = utc_days * 86400;                                        
		var date_info = new Date(utc_value * 1000);
		var fractional_day = serial - Math.floor(serial) + 0.0000001;
		var total_seconds = Math.floor(86400 * fractional_day);
		var seconds = total_seconds % 60;
		total_seconds -= seconds;
		var hours = Math.floor(total_seconds / (60 * 60));
		var minutes = Math.floor(total_seconds / 60) % 60;
		var DateFormat = new Date(date_info.getFullYear(), date_info.getMonth(), date_info.getDate(), hours, minutes, seconds);
		return moment(DateFormat).format('DD/MM/YYYY hh:mm:ss');
	}
    function ProcessExcel(data,idDivColumnItem) {
        var workbook = XLSX.read(data, {
            type: 'binary'
        });
        var firstSheet = workbook.SheetNames[0];
        var excelRows = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet]);
        var table = document.createElement("table");
        table.border = "1";
        var row = table.insertRow(-1);
        var headerCell = document.createElement("TH");
		
		
		//const arrayOfObj = Object.entries(excelRows).map((e) => ( { [e[0]]: e[1] } ));
		var myJSON = excelRows[0];
		var ArraydNombreDeColumna = new Array();
		var i=0;
		//alert("TablaDeSelects"+idDivColumnItem+" "+"TablaDeSelectsdivExcelColumnas");
        var TablaColumnaItem = document.getElementById("TablaDeSelectsdivExcelColumnas");//"TablaDeSelects"+idDivColumnItem);
		var Hijos = TablaColumnaItem.getElementsByTagName("select");
		for (var i=0;i<Hijos.length;i++) {
			//alert(Hijos[i].value);
			ArraydNombreDeColumna[i] = Hijos[i].value;
		}
		for (x in myJSON) {
			//ArraydNombreDeColumna[i] = x;
			i++;
		}
		//var StringAlert="";
		for (var ii=0;ii<ArraydNombreDeColumna.length;ii++) {
			headerCell = document.createElement("TH");
			headerCell.innerHTML = ArraydNombreDeColumna[ii];
			row.appendChild(headerCell);
		}
        for (var i = 0; i < excelRows.length; i++) {
            var row = table.insertRow(-1);
			for(var j=0;j<ArraydNombreDeColumna.length;j++){
				var identificador = ArraydNombreDeColumna[j];
				if(excelRows[i][identificador]!=undefined){
					var cell = row.insertCell(-1);
					var CheckBox = document.getElementById("CorrectorDeFecha"+j);
					
					if( (
					/*
					identificador.toLowerCase().indexOf("fecha")>=0 ||
					identificador.toLowerCase().indexOf("date")>=0 ||
					identificador.toLowerCase().indexOf("time")>=0 ||
					identificador.toLowerCase().indexOf("tiempo")>=0 ||
					identificador.toLowerCase().indexOf("time")>=0 
					*/
					CheckBox.checked
					) /*&& (Number.isInteger(excelRows[i][identificador] / 1))*/ ){
						//alert("Fecha Rota:" + excelRows[i][identificador] + "Fecha Reparada" + ExcelDateToJSDate(excelRows[i][identificador]) );
						cell.innerHTML = ExcelDateToJSDate(excelRows[i][identificador]);
					}else{
						cell.innerHTML = excelRows[i][identificador];
					}
					//cell.innerHTML = excelRows[i].Motivo;
				}else{
					var cell = row.insertCell(-1);
					cell.innerHTML = "";
					//cell.innerHTML = excelRows[i].Motivo;
				}
			}
        }
        var dvExcel = document.getElementById("dvExcel");
        dvExcel.innerHTML = "";
        dvExcel.appendChild(table);
    };
`;