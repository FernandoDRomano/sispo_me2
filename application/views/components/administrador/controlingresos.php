
<?php

    $user_row = $this->ion_auth->user()->row();
    //print_r($user_row);
    echo('<div class="col-md-12">');
    Elementos::CrearTitulo("Ingresos Generales");
    echo('<div class="col-md-12">');
    Elementos::CrearImputDeFecha('','FechaInicialGenerales','Fecha Inicial',4,false,"Fecha");
    Elementos::CrearImputDeFecha('','FechaFinalGenerales','Fecha Final',4,false,"Fecha");
    
    Elementos::SispoCrearBoton("BuscarIngresosGenerales();","12  text-right","Buscar","","","","btn btn-success","fa fa-search");
    Elementos::CrearTabladashboard("IngresosGenerales","12","","display:block",true,10,"display: none","text-align: right;",true,"","text-align: left;","white-space: unset;",2.1);
    echo('</div>');
    echo('<div class="container Grafica-1" id="GraficaIngresosGenerales" style="display: none;overflow: hidden;"></div>');
    echo('</div>');
    echo('<hr class="size2 hideline">');
    
    echo('<div class="col-md-12">');
    echo('<div class="col-md-12">');
	Elementos::CrearTitulo("Ingresos De Sucursal Por Clientes");
	Elementos::SispoCrearSelectt("Sucursales","Sucursal","6",false,'');
	
	Elementos::SispoCrearBoton("BuscarIngresosPorClientes();","12  text-right","Buscar","","","","btn btn-success","fa fa-search");
	Elementos::CrearTabladashboard("IngresosPorClientes","12","","display:block",true,50,"display: none","text-align: right;",true,"","text-align: left;","white-space: unset;",2.1);
    echo('</div>');
	
	echo('<div class="container Grafica-1" id="GraficaIngresosPorClientes" style="display: none;overflow: hidden;"></div>');
    echo('</div>');
    
	echo('<hr class="size2 hideline">');
	echo('<hr class="size2 hideline">');
	echo('<hr class="size2 hideline">');
	/*
	echo('<hr class="size2 hideline">');
	
	Elementos::CrearTitulo("Sqlserver");
	echo('<hr class="size2 hideline">');
	Elementos::SispoCrearBoton("sqlserver();","12","Buscar","","","","btn btn-large btn-block btn-primary","fa fa-search");
	echo('<div class="container Grafica-1" id="GraficaIngresosPorClientes" style="display: none;"></div>');
	*/
?>
<script>

    var us_nombre = <?php echo("'" . $user_row->nombre . " " . $user_row->apellido) . "'"?>;
    var UserId = <?php echo("'" .$user_row->user_id). "'"?>;
    var SucursalesDeUsuario = <?php echo('[' . $user_row->sucursal_id) . ']'?>;
    //var URLJS = "http://prueba.sispo.com.ar/ajax";
    const URLJS = `//${document.domain}/ajax`;
    var CSRF_TOKEN ="";
    var api_token ="";
    
	function BuscarSucursales(){
        filtro=["User","time","UserId","SucursalesDeUsuario"];
		filtroX=[us_nombre,Math.random(),UserId,SucursalesDeUsuario];
		var Parametros = ArraydsAJson(filtro,filtroX);
		Parametros = JSON.stringify(Parametros);// Manda Como Texto
		var Indices=["FechaInicial","FechaFinal"];
		var Objetos = ["FechaInicialGenerales","FechaFinalGenerales"];
		var ValoresDirectos = ArraydsAJson(Indices,Objetos);
		var Config = JSON.parse(`
		{
			"Elemento":"Sucursales",
			
			"Help":true,
			"DataAjax":` + Parametros + `,
			"ValoresDirectos": null,
			"MensajeEnFail":false,
			"TextoEnFail":"No Se Encontraron Resultados",
			"Ajax":"` + URLJS + `/Administrador/AjaxSucursales"
			
		}`);
		//format: '<b>{point.name}</b>: {point.percentage:.1f} %'
		//PastelGigante
		//"PointFormat":"<b>Ingresos En {point.name}</b> : <b>{point.value}<br>{point.Porcentaje:.1f}% Del Total activo",
		//Pastel
		//{series.name}:<p>{point.y}</p><br>{point.percentage:.1f}% Del Total activo"
		ElementoDesdeApi(Config);
    }
    BuscarSucursales();
    
    
	function BuscarIngresosGenerales(){
		if(!Needed("FechaInicialGenerales",1)){return;}
		if(!Needed("FechaFinalGenerales",1)){return;}
		filtro=["User","time","UserId","SucursalesDeUsuario"];
		filtroX=[us_nombre,Math.random(),UserId,SucursalesDeUsuario];
		var Parametros = ArraydsAJson(filtro,filtroX);
		Parametros = JSON.stringify(Parametros);// Manda Como Texto
		var Indices=["FechaInicial","FechaFinal"];
		var Objetos = ["FechaInicialGenerales","FechaFinalGenerales"];
		var ValoresDirectos = ArraydsAJson(Indices,Objetos);
		var Config = JSON.parse(`
		{
			"Elemento":"DivIngresosGenerales",
			"Text":"Ingresos Por Sucursales",
			"PointFormat":"{series.name}:<p>{point.y}</p><br>{point.percentage:.1f}% Del Total activo",
			"Grafica":"Pastel",
			"Help":true,
			"DataAjax":` + Parametros + `,
			"ValoresDirectos":` + ValoresDirectos + `,
			"MensajeEnFail":false,
			"TextoEnFail":"No Se Encontraron Resultados",
			"DescargaDeFiltro":0,
			"ConPaginado":false,
			"InterpreteHTML":true,
			"CrearAlCargarDatos":true,
			"DataTable":false,
			"Sispo":true,
			"Ajax":"` + URLJS + `/Administrador/AjaxControlingresos"
			
		}`);
		//format: '<b>{point.name}</b>: {point.percentage:.1f} %'
		//PastelGigante
		//"PointFormat":"<b>Ingresos En {point.name}</b> : <b>{point.value}<br>{point.Porcentaje:.1f}% Del Total activo",
		//Pastel
		//{series.name}:<p>{point.y}</p><br>{point.percentage:.1f}% Del Total activo"
		ElementoDesdeApi(Config);
		OnEndLoading("BuscarIngresosGeneralesGrafica();");
	}
	function BuscarIngresosGeneralesGrafica(){
		if(!Needed("FechaInicialGenerales",1)){return;}
		if(!Needed("FechaFinalGenerales",1)){return;}
		filtro=["User","time","UserId","SucursalesDeUsuario"];
		filtroX=[us_nombre,Math.random(),UserId,SucursalesDeUsuario];
		var Parametros = ArraydsAJson(filtro,filtroX);
		Parametros = JSON.stringify(Parametros);// Manda Como Texto
		var Indices=["FechaInicial","FechaFinal"];
		var Objetos = ["FechaInicialGenerales","FechaFinalGenerales"];
		var ValoresDirectos = ArraydsAJson(Indices,Objetos);
		var Config = JSON.parse(`
		{
			"Elemento":"GraficaIngresosGenerales",
			"Text":"Ingresos Por Sucursales",
			"PointFormat":"{series.name}:<p>{point.y}</p><br>{point.percentage:.1f}% Del Total activo",
			"Grafica":"Pastel",
			"Help":true,
			"DataAjax":` + Parametros + `,
			"ValoresDirectos":` + ValoresDirectos + `,
			"MensajeEnFail":false,
			"TextoEnFail":"No Se Encontraron Resultados",
			"DescargaDeFiltro":0,
			"ConPaginado":false,
			"InterpreteHTML":true,
			"CrearAlCargarDatos":true,
			"DataTable":false,
			"Sispo":true,
			"Ajax":"` + URLJS + `/Administrador/AjaxControlingresosgrafica"
			
		}`);
		
		//format: '<b>{point.name}</b>: {point.percentage:.1f} %'
		//PastelGigante
		//"PointFormat":"<b>Ingresos En {point.name}</b> : <b>{point.value}<br>{point.Porcentaje:.1f}% Del Total activo",
		//Pastel
		//{series.name}:<p>{point.y}</p><br>{point.percentage:.1f}% Del Total activo"
		Config.Elemento="GraficaIngresosGenerales";
		ElementoDesdeApi(Config);
		
	}
	
	function BuscarIngresosPorClientes(){
		//if(!Needed("FechaInicialClientes",1)){return;}
		//if(!Needed("FechaFinalClientes",1)){return;}
		filtro=["User","time","UserId","SucursalesDeUsuario"];
		filtroX=[us_nombre,Math.random(),UserId,SucursalesDeUsuario];
		var Parametros = ArraydsAJson(filtro,filtroX);
		Parametros = JSON.stringify(Parametros);// Manda Como Texto
		var Indices=["Sucursal"];
		var Objetos = ["Sucursales"];
		var ValoresDirectos = ArraydsAJson(Indices,Objetos);
		
		var Config = JSON.parse(`
		{
			"Elemento":"DivIngresosPorClientes",
			"Text":"Ingresos Por Sucursales",
			"PointFormat":"{series.name}:<p>{point.y}</p><br>{point.percentage:.1f}% Del Total activo",
			"Grafica":"Pastel",
			"Help":true,
			"DataAjax":` + Parametros + `,
			"ValoresDirectos":` + ValoresDirectos + `,
			"MensajeEnFail":false,
			"TextoEnFail":"No Se Encontraron Resultados",
			"DescargaDeFiltro":0,
			"ConPaginado":true,
			"InterpreteHTML":true,
			"CrearAlCargarDatos":true,
			"DataTable":false,
			"Sispo":true,
			"Ajax":"` + URLJS + `/Administrador/AjaxIngresosDeSucursalPorClientes"
			
		}`);
		//"ValoresDirectos":null,
		//format: '<b>{point.name}</b>: {point.percentage:.1f} %'
		//PastelGigante
		//"PointFormat":"<b>Ingresos En {point.name}</b> : <b>{point.value}<br>{point.Porcentaje:.1f}% Del Total activo",
		//Pastel
		//{series.name}:<p>{point.y}</p><br>{point.percentage:.1f}% Del Total activo"
		ElementoDesdeApi(Config);
		//OnEndLoading("BuscarIngresosGeneralesGrafica();");
	}
	
</script>