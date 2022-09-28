function DescargarTablaXLSX(DivContenedorDeTablaParaDescargar){
	var Div = document.getElementById(DivContenedorDeTablaParaDescargar);
	var wb = XLSX.utils.book_new();
        wb.Props = {
                Title: "SheetJS Tutorial",
                Subject: "Test",
                Author: "Red Stapler",
                CreatedDate: new Date(2017,12,19)
        };
        wb.SheetNames.push("Test Sheet");
		var TablaDeValores = new Array();
		TablaDeValores[0] = Div.Config.Titulos;
		if( (Div.Config.DescargaDeFiltro != undefined) && (Div.Config.ResultadoFiltrado != undefined)){
			for(var i = 0 ; i <= Div.Config.ResultadoFiltrado.length ; i++){
				TablaDeValores[i+1] = Div.Config.ResultadoFiltrado[i];
			}
		}else{
			for(var i = 0 ; i <= Div.Config.Resultado.length ; i++){
				TablaDeValores[i+1] = Div.Config.Resultado[i];
			}
		}
		var ws_data = TablaDeValores;
		var ws = XLSX.utils.aoa_to_sheet(ws_data);
        wb.Sheets["Test Sheet"] = ws;
        var wbout = XLSX.write(wb, {bookType:'xlsx',  type: 'binary'});
        function s2ab(s) {
  
                var buf = new ArrayBuffer(s.length);
                var view = new Uint8Array(buf);
                for (var i=0; i<s.length; i++) view[i] = s.charCodeAt(i) & 0xFF;
                return buf;
        }
		saveAs(new Blob([s2ab(wbout)],{type:"application/octet-stream"}), 'NuevoArchivo.xlsx');
		console.log("DescargarTablaXLSX");
}

function DescargarTablaPDF(DivContenedorDeTablaParaDescargar){
	var Div = document.getElementById(DivContenedorDeTablaParaDescargar);
	var doc = new jsPDF();
	//doc.autoTable({ html: '#my-table' });
	if( (Div.Config.DescargaDeFiltro != undefined) && (Div.Config.ResultadoFiltrado != undefined)){
		doc.autoTable({
			head: [Div.Config.Titulos],
			body: Div.Config.ResultadoFiltrado,
		});
	}else{
		doc.autoTable({
			head: [Div.Config.Titulos],
			body: Div.Config.Resultado,
		});
	}
	doc.save('NuevoArchivo.pdf');
}


function DescargarTablaCSV(DivContenedorDeTablaParaDescargar){
	var Div = document.getElementById(DivContenedorDeTablaParaDescargar);
	var wb = XLSX.utils.book_new();
        wb.Props = {
                Title: "SheetJS Tutorial",
                Subject: "Test",
                Author: "Red Stapler",
                CreatedDate: new Date(2017,12,19)
        };
        wb.SheetNames.push("Test Sheet");
		var TablaDeValores = new Array();
		TablaDeValores[0] = Div.Config.Titulos;
		
		if( (Div.Config.DescargaDeFiltro != undefined) && (Div.Config.ResultadoFiltrado != undefined)){
			for(var i = 0 ; i <= Div.Config.ResultadoFiltrado.length ; i++){
				TablaDeValores[i+1] = Div.Config.ResultadoFiltrado[i];
			}
		}else{
			for(var i = 0 ; i <= Div.Config.Resultado.length ; i++){
				TablaDeValores[i+1] = Div.Config.Resultado[i];
			}
		}
		var ws_data = TablaDeValores;
		var ws = XLSX.utils.aoa_to_sheet(ws_data);
        wb.Sheets["Test Sheet"] = ws;
        var wbout = XLSX.write(wb, {bookType:'csv',  type: 'binary'});
        function s2ab(s) {
  
                var buf = new ArrayBuffer(s.length);
                var view = new Uint8Array(buf);
                for (var i=0; i<s.length; i++) view[i] = s.charCodeAt(i) & 0xFF;
                return buf;
        }
		saveAs(new Blob([s2ab(wbout)],{type:"application/octet-stream"}), 'NuevoArchivo.csv');
}