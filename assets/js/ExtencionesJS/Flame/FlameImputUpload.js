
$(document).ready(function() {
	$('.InputFiles').change(function(){
		var input = this;
		var fileList = this.parentElement.parentElement.getElementsByClassName("ListaDeArchivos")[0];
		var SpanFile = this.parentElement.getElementsByClassName("SpanFile")[0];
		
		fileList.innerHTML = "";
		var Titulo;
		
		if(input.files.length == 0 ){
			SpanFile.innerHTML = "...";
			return;
		}
		if(input.files.length > 1 ){
			Titulo = document.createElement("b");
			var TextoTitulo = document.createTextNode('Archivos Seleccionados:');
			Titulo.appendChild(TextoTitulo);
			fileList.appendChild(Titulo);
			SpanFile.innerHTML = input.files.length + " Archivos";
		}
		if(input.files.length == 1 ){
			Titulo = document.createElement("b");
			var TextoTitulo = document.createTextNode('Archivo Seleccionado:');
			Titulo.appendChild(TextoTitulo);
			fileList.appendChild(Titulo);
			SpanFile.innerHTML = "1 Archivo";
		}
		var ul = document.createElement("ul");
		ul.className="IconFiles";
		
		for (var i = 0; i < input.files.length; ++i) {
			var li = document.createElement("li");
			li.className="IconFiles";
			var ic = document.createElement("i");
			ic.className="fas fa-file-alt";
			li.appendChild(ic);
			var Fichero = document.createTextNode(input.files.item(i).name);
			li.appendChild(Fichero);
			ul.appendChild(li);
		}
		var DivContenedor = this.parentElement.parentElement;
		DivContenedor.XLSXCOLUMNAS = DivContenedor.getAttribute("Filtro");
		fileList.appendChild(ul);
		//console.log(DivContenedor);
		EcxelADataDivYTabla(DivContenedor);
		//console.log("Datos Fueras" + DivContenedor.data[0]);
		//ProcessExcel(DivContenedor.data[0]);
		
	});
});

function EcxelADataDivYTabla(Div) {
	//console.log(Div);
	Loading();
	Div.data = new Array;
	var fileUpload = Div.getElementsByTagName("label")[0].getElementsByTagName("input")[0];
	//var fileUpload = document.getElementById("UploadXLSX");
	if (typeof (FileReader) != "undefined") {
		var reader = new Array;
		reader[0] = new FileReader();
		//For Browsers other than IE.
		if (reader[0].readAsBinaryString) {
			var CantidadDeLoads = 0 ;
			for(var i = 0 ; i<fileUpload.files.length ; i++){
				
				reader[i] = new FileReader();
				reader[i].onload = function (e) {
					CantidadDeLoads ++;
					Div.data.push(e.target.result);
					//console.log("Datos Adentro" + Div.data);
					if(CantidadDeLoads == fileUpload.files.length){
						//ProcessExcel(Div.data);
						CrearTablaDesdeEcxelADataDivYTabla(Div);
					}
				};
				reader[i].readAsBinaryString(fileUpload.files[i]);
			}
		}
		
	}else{
		alert("This browser does not support HTML5.");
	}
};




			
function CrearTablaDesdeEcxelADataDivYTabla(Div){
	var Config = Div.Config;
	//console.log(Config);
	var data = Div.data;
	
	var excelRows=new Array;
	var ExelKeys=new Array;
	for(var i = 0 ; i<data.length ; i++){
		var workbook = XLSX.read(data[i], {
			type: 'binary',
			cellDates: true,
			dateNF:'yyyy-mm-dd'
		});
		
		
		//Fetch the name of First Sheet.
		var firstSheet = workbook.SheetNames[0];

		
		var headers = [];
		var sheet = workbook.Sheets[firstSheet];
		var range = XLSX.utils.decode_range(sheet['!ref']);
		var C, R = range.s.r;
		for (C = range.s.c; C <= range.e.c; ++C) {
			var cell = sheet[XLSX.utils.encode_cell({c: C, r: R})];
			var hdr = "UNKNOWN " + C;
			if (cell && cell.t) {
				hdr = XLSX.utils.format_cell(cell);
			}else{
				//hdr = XLSX.utils.format_cell("");
			}
			headers.push(hdr);
		}
		for(var t2=range.s.r; t2 <= range.e.r ; t2++){
			var ArrayForRow =  {};
			for(var t=range.s.c; t <= range.e.c ; t++){
				if(t2==0){
					ExelKeys.push(XLSX.utils.format_cell(sheet[XLSX.utils.encode_cell({c: t, r: t2})]));
				}
				else{
					ArrayForRow[XLSX.utils.format_cell(sheet[XLSX.utils.encode_cell({c: t, r: 0})])] = XLSX.utils.format_cell(sheet[XLSX.utils.encode_cell({c: t, r: t2})]);
				}
			}
			if(t2>0){
				excelRows.push(ArrayForRow);
			}
		}
		
		/*
		console.log(ExelKeys);
		console.log(excelRows);
		*/
		//Read all rows from First Sheet into an JSON array.
		//excelRows.push( XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet]) );
		//console.log( XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet]) );
		/*
		for(var j = 0 ; j < XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet]).length ; j++){
			excelRows.push(XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet])[j]);
			if(j==0){
				for (var key in XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet])[j]) {
					ExelKeys.push(key);
				}
			}
		}
		
		console.log(ExelKeys);
		console.log(excelRows);
		*/
		
	}
	
	var Resultado = new Array;
	for (var i = 0; i < ExelKeys.length; i++) {
		if(i+1==ExelKeys.length){
			if(ExelKeys[i].indexOf("__EMPTY") == 0){
				Resultado += "" + ";";
			}else{
				Resultado += ExelKeys[i] + ";";
			}
		}else{
			if(ExelKeys[i].indexOf("__EMPTY") == 0){
				Resultado += "" + "|";
			}else{
				Resultado += ExelKeys[i] + "|";
			}
		}
	}
	//console.log(typeof excelRows[0]["fecha de contrato de locación"].getMonth != 'undefined');
	//if(excelRows[0]){}
	for (var i = 0; i < excelRows.length; i++) {
		for(var j = 0; j < ExelKeys.length; j++){
			if(j+1==ExelKeys.length){
				if(excelRows[i][ExelKeys[j]] == undefined){
					Resultado += "";
					//console.log("excelRows[i][ExelKeys[j]] == undefined i j " + i  + " " +j);
				}else{
					if(typeof excelRows[i][ExelKeys[j]].getDate != 'undefined' && typeof excelRows[i][ExelKeys[j]].getMonth != 'undefined' && typeof excelRows[i][ExelKeys[j]].getFullYear != 'undefined' && typeof excelRows[i][ExelKeys[j]].getHours != 'undefined' && typeof excelRows[i][ExelKeys[j]].getMinutes != 'undefined' && typeof excelRows[i][ExelKeys[j]].getSeconds != 'undefined'){ 
						var d = excelRows[i][ExelKeys[j]];
						if(d.getHours() == 0 && d.getMinutes() == 0){
							Resultado += "" + d.getDate() +"/"+ d.getMonth() +"/"+ d.getFullYear();
						}else{
							Resultado += "" + d.getDate() +"/"+ d.getMonth() +"/"+ d.getFullYear() + " " + d.getHours() + ":" + d.getMinutes() + ":" + d.getSeconds();
						}
						
					}else{
						if(typeof excelRows[i][ExelKeys[j]].getDate != 'undefined' && typeof excelRows[i][ExelKeys[j]].getMonth != 'undefined' && typeof excelRows[i][ExelKeys[j]].getFullYear != 'undefined'){
							var d = excelRows[i][ExelKeys[j]];
							Resultado += "" + d.getDate() +"/"+ d.getMonth() +"/"+ d.getFullYear() ;
						}else{
							
							Resultado += excelRows[i][ExelKeys[j]];
						}
					}
					//console.log(excelRows[ExelKeys[j]]);
				}
				if(i+1!=excelRows.length){
					Resultado += ";" ;
				}
			}else{
				if(excelRows[i][ExelKeys[j]] == undefined){
					Resultado += "";
					//console.log("excelRows[i][ExelKeys[j]] == undefined i j " + i  + " " +j);
				}else{
						if(typeof excelRows[i][ExelKeys[j]].getDate != 'undefined' && typeof excelRows[i][ExelKeys[j]].getMonth != 'undefined' && typeof excelRows[i][ExelKeys[j]].getFullYear != 'undefined' && typeof excelRows[i][ExelKeys[j]].getHours != 'undefined' && typeof excelRows[i][ExelKeys[j]].getMinutes != 'undefined' && typeof excelRows[i][ExelKeys[j]].getSeconds != 'undefined'){ 
						var d = excelRows[i][ExelKeys[j]];
						if(d.getHours() == 0 && d.getMinutes() == 0){
							Resultado += "" + d.getDate() +"/"+ d.getMonth() +"/"+ d.getFullYear();
						}else{
							Resultado += "" + d.getDate() +"/"+ d.getMonth() +"/"+ d.getFullYear() + " " + d.getHours() + ":" + d.getMinutes() + ":" + d.getSeconds();
						}
					}else{
						if(typeof excelRows[i][ExelKeys[j]].getDate != 'undefined' && typeof excelRows[i][ExelKeys[j]].getMonth != 'undefined' && typeof excelRows[i][ExelKeys[j]].getFullYear != 'undefined'){
							var d = excelRows[i][ExelKeys[j]];
							Resultado += "" + d.getDate() +"/"+ d.getMonth() +"/"+ d.getFullYear() ;
						}else{
							Resultado += excelRows[i][ExelKeys[j]];
						}
					}
				}
				Resultado += "|" ;
			}
			
		}
	}
	/*
	console.log("excelRows.length");
	console.log(excelRows.length);
	console.log("ExelKeys.length");
	console.log(ExelKeys.length);
	console.log("");
	console.log("");
	console.log("");
	console.log("");
	*/
	if(excelRows==0){
		if(typeof $.bootstrapGrowl === "function" && Config.ErrorDeLectura != null && Config.ErrorDeLectura != "" ) {
			$.bootstrapGrowl(Config.ErrorDeLectura,{
				type: 'info',
				delay: "15000",
				align: 'right',
				width: 'auto'
			});
		}
		EndLoading();
		return
	}
	var Arrayd1D = Resultado.split(";");
	var Arrayd2D = new Array();
	for(var i = 0 ; i < Arrayd1D.length ; i++ ){
		Arrayd2D[i] = Arrayd1D[i].split("|");
	}
	var ObjJSON = JSON.parse(JSON.stringify(Arrayd2D));
	Config.Resultado = ObjJSON;
	//console.log(Config);
	FormatearDatosParaTabla(Config);
	//EndLoading();
	EndLoading();
}

function ProcessExcel(data) {
	//Read the Excel File data.
	//console.log(data);
	var excelRows=new Array;
	var ExelKeys=new Array;
	for(var i = 0 ; i<data.length ; i++){
		var workbook = XLSX.read(data[i], {
			type: 'binary',
			cellDates: true,
			dateNF: 'dd/mm/aaaa'
		});
		//Fetch the name of First Sheet.
		var firstSheet = workbook.SheetNames[0];

		//Read all rows from First Sheet into an JSON array.
		//excelRows.push( XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet]) );
		for(var j = 0 ; j < XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet]).length ; j++){
			excelRows.push(XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet])[j]);
			if(j==0){
				for (var key in XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet])[j]) {
					ExelKeys.push(key);
					
				}
			}
		}
		//console.log(ExelKeys);
		//console.log(excelRows);
	}
	
	//Create a HTML Table element.
	var table = document.createElement("table");
	table.border = "1";
	
	var headerCell;
	var row = table.insertRow(-1);
	
	for(var i=0; i<ExelKeys.length; i++ ){
		headerCell = document.createElement("TH");
		if(ExelKeys[i].indexOf("__EMPTY") == 0){
			headerCell.innerHTML = "";
		}else{
			headerCell.innerHTML = ExelKeys[i];
		}
		row.appendChild(headerCell);
	}
	
	//Add the data rows from Excel file.
	for (var i = 0; i < excelRows.length; i++) {
		
		var row = table.insertRow(-1);
		for(var j = 0; j < ExelKeys.length; j++){
			//Add the data cells.
			var cell = row.insertCell(-1);
			//var Str = ExelKeys[j];
			if(excelRows[i][ExelKeys[j]] == undefined){
				cell.innerHTML = "";
			}else{
				cell.innerHTML = excelRows[i][ExelKeys[j]];
			}
		}
	}
	var dvExcel = document.getElementById("dvExcel");
	dvExcel.innerHTML = "";
	dvExcel.appendChild(table);
};





$(document).ready(function() {
	$('.InputUploadFiles').change(function(){
		
		var input = this;
		var fileList = this.parentElement.parentElement.getElementsByClassName("ListaDeArchivos")[0];
		var SpanFile = this.parentElement.getElementsByClassName("SpanFile")[0];
		
		fileList.innerHTML = "";
		var Titulo;
		
		if(input.files.length == 0 ){
			SpanFile.innerHTML = "...";
			return;
		}
		if(input.files.length > 1 ){
			Titulo = document.createElement("b");
			var TextoTitulo = document.createTextNode('Archivos Seleccionados:');
			Titulo.appendChild(TextoTitulo);
			fileList.appendChild(Titulo);
			SpanFile.innerHTML = input.files.length + " Archivos Seleccionados";
		}
		if(input.files.length == 1 ){
			Titulo = document.createElement("b");
			var TextoTitulo = document.createTextNode('Archivo Seleccionado:');
			Titulo.appendChild(TextoTitulo);
			fileList.appendChild(Titulo);
			SpanFile.innerHTML = "1 Archivo Seleccionado";
		}
		var ul = document.createElement("ul");
		ul.className="IconFiles";
		
		for (var i = 0; i < input.files.length; ++i) {
			var li = document.createElement("li");
			li.className="IconFiles";
			var ic = document.createElement("i");
			ic.className="fas fa-file-alt";
			li.appendChild(ic);
			var Fichero = document.createTextNode(input.files.item(i).name);
			li.appendChild(Fichero);
			ul.appendChild(li);
		}
		/*
		var divBoton = document.createElement("div");
		divBoton.className = "col-md-12";
		divBoton.setAttribute = "float: none";
		*/
		
		var ElementBoton = document.createElement("button");
		var funcion = this.parentElement.parentElement.getAttribute("funcion");
		var DivContenedor = this.parentElement.parentElement;
		DivContenedor.XLSXCOLUMNAS = DivContenedor.getAttribute("Filtro");
		//console.log(input.files);
		//console.log(input.files[0]);
		
		ElementBoton.onclick = function() {
			Loading();
			setTimeout(function(){ window[funcion](DivContenedor);},1000);
		};
		ElementBoton.className = "btn btn-large btn-block btn-primary";
		var Elementi = document.createElement("i");
		var TextoBoton;
		if(input.files.length == 1 ){
			TextoBoton = document.createTextNode("Subir Archivo");
		}else{
			TextoBoton = document.createTextNode("Subir Archivos");
		}
		Elementi.appendChild(TextoBoton);
		ElementBoton.appendChild(Elementi);
		//divBoton.appendChild(ElementBoton);
		ul.appendChild(ElementBoton);
		fileList.appendChild(ul);
		//console.log(DivContenedor);
		FicherosADataDiv(DivContenedor);
		console.log(DivContenedor.data);
	});
	
});














function FicherosADataDiv(Div) {
	//console.log(Div);
	Div.data = new Array;
	var fileUpload = Div.getElementsByTagName("label")[0].getElementsByTagName("input")[0];
	//var fileUpload = document.getElementById("UploadXLSX");
	if (typeof (FileReader) != "undefined") {
		var reader = new Array;
		reader[0] = new FileReader();
		//For Browsers other than IE.
			if (reader[0].readAsBinaryString) {
				for(var i = 0 ; i<fileUpload.files.length ; i++){
					reader[i] = new FileReader();
					reader[i].onload = function (e) {
						Div.data.push(e.target.result);
						//console.log(Div.data);
					};
					reader[i].readAsBinaryString(fileUpload.files[i]);
				}
			}
	}else{
		alert("This browser does not support HTML5.");
	}
};
/*
function ProcessExcel(data) {
	//Read the Excel File data.
	var workbook = XLSX.read(data, {
		type: 'binary'
	});

	//Fetch the name of First Sheet.
	var firstSheet = workbook.SheetNames[0];

	//Read all rows from First Sheet into an JSON array.
	var excelRows = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet]);

	//Create a HTML Table element.
	var table = document.createElement("table");
	table.border = "1";

	//Add the header row.
	var row = table.insertRow(-1);

	//Add the header cells.
	var headerCell = document.createElement("TH");
	headerCell.innerHTML = "Id";
	row.appendChild(headerCell);

	headerCell = document.createElement("TH");
	headerCell.innerHTML = "Cartero";
	row.appendChild(headerCell);

	headerCell = document.createElement("TH");
	headerCell.innerHTML = "Country";
	row.appendChild(headerCell);

	//Add the data rows from Excel file.
	for (var i = 0; i < excelRows.length; i++) {
		//Add the data row.
		var row = table.insertRow(-1);

		//Add the data cells.
		var cell = row.insertCell(-1);
		cell.innerHTML = excelRows[i].Id;

		cell = row.insertCell(-1);
		cell.innerHTML = excelRows[i].Cartero;

		cell = row.insertCell(-1);
		cell.innerHTML = excelRows[i].Country;
	}

	var dvExcel = document.getElementById("dvExcel");
	dvExcel.innerHTML = "";
	dvExcel.appendChild(table);
};
*/