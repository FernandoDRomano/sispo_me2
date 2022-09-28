
<?php

    $user_row = $this->ion_auth->user()->row();
    //print_r($user_row);
    echo('<div class="col-md-12">');
    Elementos::CrearTitulo("Medicion de Clientes");
    echo('<div class="col-md-12">');
    Elementos::CrearImputDeFecha('','FechaInicial','Fecha Inicial',4,false,"Fecha");
    Elementos::CrearImputDeFecha('','FechaFinal','Fecha Final',4,false,"Fecha");
    echo('<hr class="size2 hideline">');
	Elementos::SispoCrearSelectt("Sucursales","Sucursal","4",false,'');
    Elementos::SispoCrearBoton("Buscar();","12  text-right","Buscar","","","","btn btn-success","fa fa-search");
    Elementos::CrearTabladashboard("IngresosPorClientes","12","","display:block",true,10,"display: none","text-align: right;",true,"","text-align: left;","white-space: unset;",2.1);
    echo('</div>');
    echo('<div class="container Grafica-1" id="GraficaIngresosPorClientes" style="display: none;overflow: hidden;"></div>');
    echo('</div>');
    echo('<hr class="size2 hideline">');
    
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
		console.log(Parametros)
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

		ElementoDesdeApi(Config);
    }
    BuscarSucursales();
    
	function Buscar(){
		//if(!Needed("FechaInicialClientes",1)){return;}
		//if(!Needed("FechaFinalClientes",1)){return;}
		filtro=["User","time","UserId","SucursalesDeUsuario"];
		filtroX=[us_nombre,Math.random(),UserId,SucursalesDeUsuario];
		var Parametros = ArraydsAJson(filtro,filtroX);
		Parametros = JSON.stringify(Parametros);// Manda Como Texto
		var Indices=["FechaInicial","FechaFinal","Sucursales"];//,"Clientes"
		var Objetos = ["FechaInicial","FechaFinal","Sucursales"];//,"Clientes"
		var ValoresDirectos = ArraydsAJson(Indices,Objetos);
		
		var Config = JSON.parse(`
		{
			"Elemento":"DivIngresosPorClientes",
			"Text":"Ingresos Por Sucursales",
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
			"Ajax":"` + URLJS + `/Administrador/AjaxIngresosPorClientesListado"
			
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