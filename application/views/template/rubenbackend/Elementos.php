<?php
	class Elementos{
		
		public static function CURL($method,$url, $data = null, $Bearer = 'abcd') {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			$postjson = json_encode($data);
			if(!empty($data)) {
				switch ($method) {
					case "POST":
						curl_setopt($ch, CURLOPT_POST, true);
						if ($data){
							curl_setopt($ch, CURLOPT_POSTFIELDS, $postjson);
						}
						break;
					case "PUT":
						curl_setopt($ch, CURLOPT_PUT, 1);
						break;
					default:
						if ($data){
							$url = sprintf("%s?%s", $url, http_build_query($data));
							//print_r($url);
						}
				}
				curl_setopt($ch, CURLOPT_URL, $url);
				//echo("<P>Con Datos De Envio</P>");
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
				//curl_setopt($ch, CURLOPT_HTTPHEADER , array("cache-control: no-cache"));
				$authorization = "Authorization: Bearer " . $Bearer; // Prepare the authorisation token
				curl_setopt($ch, CURLOPT_HTTPHEADER , array("cache-control: no-cache", "Accept: application/json", "Content-Type: application/json", $authorization));
				
			} else{
				//echo("<P>Sin Datos De Envio</P>");
			}
			$result = curl_exec($ch);
			/*
			print_r("<p>");
			print_r(count(array_keys(json_decode($result, true))));
			print_r(array_keys(json_decode($result, true)));
			print_r(json_decode($result, true));
			print_r("</p>");
			*/
			//if(gettype($result) == "string"){$result = json_encode(array('error' => true));}
			$DataResultado = curl_getinfo($ch);
			//print_r($DataResultado);
			//print_r(json_decode($result, true));
			$ResultadoDecode = json_decode($result, true);
			//print_r($ResultadoDecode['Respuesta']);
			if($ResultadoDecode){
				$Respuesta = array_merge($DataResultado,json_decode($result, true));
			}else{
				$result = json_encode(array('json-data' => false));
				$Respuesta = array_merge($DataResultado,json_decode($result, true));
			}
			//print_r($Respuesta['Respuesta']);
			if( $Respuesta["http_code"] == 200 ){
				curl_close($ch);
				return $Respuesta;
			}else{
				curl_close($ch);
				return $Respuesta;
			}
		}
		
		public static function StartFormFile($Tamaño, $URL){
			echo('
			<div class="col-sm-' . $Tamaño . '">
				<form  class="SubaDeImagenes" method="post" enctype="multipart/form-data" action="' . $URL . '">
			');
		}
		
		public static function EndFormFile($id, $Formatos, $MultipesFicheros, $Tamaño, $Texto, $Comentario,$Funcion, $TextoImagen = "" ){
			if($TextoImagen == ""){
				$TextoImagen = 'Seleccione Archivos (' . $Formatos . ')';
			}
			echo('
					<div>
						<label class="SubaDeImagenes" for="' . $id . '" style="width: 100%;">' . $TextoImagen . '</label>
						<input class="SubaDeImagenes" type="file" id="' . $id . '" name="image_uploads[]" accept="' . $Formatos . '" ' .$MultipesFicheros . '>
					</div>
					<div class="preview" >
						<p></p>
					</div>
					<div>
			');
				Elementos::CrearBoton('PostImagenDeFichero(this)',$Tamaño,$Texto,$Comentario,$Funcion);
			echo('
					<!-- <button class="buttonSubaDeImagenes" onClick="' . $Funcion . '">Subir</button> -->
					</div>
				</form>
			</div>
			');
		}
		
		public static function CrearFormularioCheck($id,$Tamaño,$Texto,$Comentario,$iclass,$Valores,$PrimeroSeleccionado = false){
			echo('
			<div class="col-md-' . $Tamaño . '" style="">
				<fieldset id="' . $id . '">
			');
			for($i=0;$i<count($Valores);$i++){
				if($i==0 and $PrimeroSeleccionado){
					$checked='checked="checked"';
				}else{$checked='';}
				echo('
					<label style="display: block;" class="' . $iclass . '">
						<input  id="' . $id . '-' . $i . '" type="checkbox" value="' . $i . '" ' . $checked. '>' . $Valores[$i] . '
					</label>
				');
			}
			echo('
				</fieldset>
			</div>
			');
		
		}
		
		public static function CrearInputUploadFiles($Id,$ListadoId,$FicherosPermitidos,$Funcion,$Tamaño,$Filtro){
			echo('
			<div class="col-md-' . $Tamaño . '" funcion="' . $Funcion . '" Filtro="' . $Filtro. '">
				<label class="file">
					<input class="InputUploadFiles" type="file" id="' . $Id . '" multiple accept="' . $FicherosPermitidos . '" >
					<span class="SpanFile">...</span>
				</label>
				<div class="ListaDeArchivos" id="' . $ListadoId . '"></div>
			</div>
			');
			/*
			
				<div class="input-group">
				</div>
			*/
		}
		
		public static function StartHide($Id,$value = ""){
			echo('
			<div id="' . $Id . '" Style="display:none" value="' . $value . '">
				
			');
		}
		
		public static function EndHide(){
			echo('
			</div>
			');
		}
		
		public static function CrearSelectt($Id,$Texto,$Tamaño,$Extra = false){
			$ConfiguracionExtra = "";
			if($Extra != false){
				$ConfiguracionExtra = $Extra;
			}
			echo('
			<div class="col-md-' . $Tamaño . '">
				<div class="form-group">
					<label class="control-label">' . $Texto . '
						<span class="required" aria-required="true">*</span>
						<b id="BoltText' . $Id . '"></b><!-- BoltTextCliente -->
					</label>
					<div>
						<select id="' . $Id . '" name="' . $Id . '" class="form-control select1-Borrado select1-hidden-accessible-Borrado" tabindex="-1" aria-hidden="true" '. $ConfiguracionExtra .'>
						</select>
					</div>
				</div>
			</div>
			');
		}
		
		public static function CrearSelectt2($Id,$Texto,$Tamaño,$Extra = false,$requiered = false){
			$ConfiguracionExtra = "";
			$ValorRequerido ="";
			if($Extra != false){
				$ConfiguracionExtra = $Extra;
			}
			if($requiered){
				$ValorRequerido ="*";
			}
			echo('
				<div class="col-md-' . $Tamaño . '">
					<div class="form-group">
						<label class="control-label">' . $Texto . '
							<span class="required" aria-required="true">' . $ValorRequerido . '</span>
							<b id="BoltText' . $Id . '"></b><!-- BoltTextCliente -->
						</label>
						<div>
						<select id="' . $Id . '" name="' . $Id . '" class="select-2 form-control select1-Borrado  select1-hidden-accessible-Borrado" '. $ConfiguracionExtra .'>
							</select>
						</div>
					</div>
				</div>
			');
		}
		
		public static function CrearIniRow($Tamaño,$style = ""){
			echo('
			<div class="col-md-' . $Tamaño . '" style="' . $style . '">
			');
		}
		
		public static function CrearFinRow(){
			echo('
			</div>
			');
		}
		
		public static function CrearImput($Id,$Tipo,$Texto,$Tamaño,$Extra = false,$requiered = false,$autojs = true,$Valor = ''){
			$Password = "";
			$ValorRequerido ="";
			if($Tipo == "password"){
				$Password = 'autocomplete="new-password"';
			}
			$ConfiguracionParaNumerico = "";
			if($Extra != false){
				$ConfiguracionParaNumerico = $Extra;
			}
			if($requiered){
				$ValorRequerido ="*";
			}
			$Info = "";
			if($Tipo=="Celular"){
				$Info = "Codigo De Area + Numero Personal 10 Digitos";
			}
			echo('
				<div class="col-md-' . $Tamaño . '" style="">
					<div class="form-group">
						<label class="control-label">' . $Texto . '
							<span class="required" aria-required="true">' . $ValorRequerido . '</span>
							<b style="color:#0000FF;font-size: 10px;width: 90%;">' . $Info . '</b>
							<b id="BoltText' . $Id . '"><b style="color:#FF0000;font-size: 10px;width: 90%;"></b></b>
						</label>
						<div>
							<input ' . $Password . ' placeholder="' . $Texto . '" type="' . $Tipo . '" id="' . $Id . '" name="' . $Id . '" class="form-control" ' . $ConfiguracionParaNumerico .' value="' . $Valor . '">
						</div>
					</div>
				</div>
			');
			if($autojs){
				echo("
					<script>
						var Config = JSON.parse(`{
							\"Elemento\":\"" . $Id . "\",
							\"ElementoTexto\":\"BoltText" . $Id . "\",
							\"DigitosMinimos\":\"1\",
							\"TextoInicial\":\"\",
							\"TextoMenor\":\"\"
						}`);
						Texto(Config);
					</script>
				");
			}
		}
		
		public static function CrearLeftTitulo($Titulo){
			echo('
			<div class="col-md-12">
				<div class="form-group" style="width: fit-content;">
					<h1 class="tituloFormulario form-section" id="" >' . $Titulo . '</h1>
				</div>
			</div>
			');
		}
		
		public static function Creardashboard($Iddashboard,$IdValor,$Texto,$Tamaño,$IdUncolor,$Sizable){
			echo('
				<div class="col-sm-' . $Tamaño . ' col-xs-12 pointer">
					<div id="' . $Iddashboard . '" class="dashboard-stat purple-plum MaximixedTable" Uncolor="' . $IdUncolor . '" Sizable="' . $Sizable . '" >
						<div class="visual">
							<i class="fa fa-globe"></i>
						</div>
						<div class="details">
							<div id="' . $IdValor . '" class="number">0</div>
							<div class="desc">' . $Texto . '</div>
						</div>
					</div>
				</div>
			');
		}
		
		public static function CrearInputUploadFilesRaw($Id,$ListadoId,$FicherosPermitidos,$Funcion,$Tamaño,$Filtro,$FiltroDesde,$FiltroHasta,$Config = null,$ReadDesde = 0, $ReadHasta = 0){
			echo('
			<div id="DivContenedor' . $Id . '" class="col-md-' . $Tamaño . '" funcion="' . $Funcion . '" Columnas="' . $Filtro. '" ColumnasDesde="' . $FiltroDesde. '"  ColumnasHasta="' . $FiltroHasta. ' " ReadDesde="' . $ReadDesde. ' " ReadHasta="' . $ReadHasta. ' " >
				<label class="file">
					<input class="InputUploadFilesRaw" type="file" id="' . $Id . '" multiplex accept="' . $FicherosPermitidos . '" >
					<span class="SpanFile">...</span>
				</label>
				<div class="ListaDeArchivos" id="' . $ListadoId . '"></div>
				<DATA id="fileinfo' . $Id . '"></DATA>
			</div>
			');
			echo('<script>var Config = JSON.parse(`');
			print_r($Config);
			echo('`); document.getElementById("DivContenedor' . $Id . '").Config = Config; </script>');//console.log(Config);
		}
		
		public static function CerrarDesplegableConTitulo(){
			echo('
				<hr class="size1 hideline">
				</div>
			</div>
			');
		}
		
		public static function CrearTabladashboard($Elementos,$Tamaño,$TituloDeTabla,$Display = "display: none",$Info = true,$NumeroDeFilas = 100,$StyleNumeroDeFilasDiv = "", $StyleDescargasDiv = "", $FullVertical = true, $StyleDivPrimario = "",$StylePaginador = "",$StyleTabla = "",$OrdenDeTitulos = 2){
			$IdTabladashboard = "Sizable" . $Elementos;
			$IdDiv = "Div" . $Elementos;
			$IdPaginador = "DivPaginador" . $Elementos;
			$IdMaximoDeFilas = "MaximoDeFilas" . $Elementos;
			$IdTabla = "Tabla" . $Elementos;
			if($FullVertical){
				$classportletlight = "portlet light";
			}else{
				$classportletlight = "";
			}
			//			<hr>
			//				<span class="caption-helper">Los Datos Mostrados Contienen Resultados De La Fecha Buscada.</span>
			echo('
			<div class="col-sm-' . $Tamaño . ' " style="overflow-x:auto;' . $StyleDivPrimario . '">
				<div class="' . $classportletlight . '" id="' . $IdTabladashboard . '" style="'. $Display . '">
				
			');
			if($TituloDeTabla!=""){
				echo('
					<div class="portlet-title">
						<div class="caption caption-md">
							<i class="icon-bar-chart theme-font hide"></i>
							<span class="caption-subject theme-font bold uppercase"></span>
							<span class="caption-helper" style="border-bottom: 2px solid #0068a9!important;">' . $TituloDeTabla . '</span>
						</div>
				');
				if($Info){
					echo('
						<div class="actions">
							<a class="TextoSombreado" href="javascript:HideFullScreen(\'Sizable\');" data-original-title="" title="">
							<B style="color:#3030ff;">Volver</B>
							</a>
							<a class="btn btn-circle btn-icon-only btn-default fullscreen" href="javascript:HideFullScreen(\'Sizable\');" data-original-title="" title="">
							</a>
						</div>
					');
				}
				echo('
					</div>
				');
			}
			
			
			
			$MaximoDeFilas =
			'<div class="col-md-12 MaximoDeFilas" style="height: max-content;' . $StyleNumeroDeFilasDiv . '" hidden>
				<b>Mostrar</b>
				<input placeholder="" type="numberNoFloat" id="' . $IdMaximoDeFilas . '" " value="' . $NumeroDeFilas . '" style="max-width: 86px;">
				<b> registros</b>
			</div>';
			$DescargaDeTabla = 
			'<div class="col-sm-12 DescargaDeTabla" style="height: max-content;' . $StyleDescargasDiv . '" hidden>
				<label class="control-label"> Exportar Tabla A Archivo
					<span class="required" aria-required="true"></span>
				</label>
				<div class="btn-group">
					<button style="border-color: black;" onclick="DescargarTablaXLSX(\'' . $IdDiv . '\')" class="btn" title="XLSX Para Exel">
						<span>
							<i class="fas fa-file-excel" style="color:green;"></i>
						</span>
					</button>
					<button style="border-color: black;" onclick="DescargarTablaCSV(\'' . $IdDiv . '\')" class="btn" title="CSV Para Exel">
						<span>
							<i class="fas fa-file-csv" style="color:blue;"></i>
						</span>
					</button>
					<button style="border-color: black;" onclick="DescargarTablaPDF(\'' . $IdDiv . '\')" class="btn" title="PDF Para Adove">
						<span>
							<i class="fas fa-file-pdf" style="color:red;"></i>
						</span>
					</button>
				</div>
			</div>';
			$Paginador = 
			'<div class="col-sm-12 Paginador" id="' . $IdPaginador . '" style="' . $StylePaginador . '" hidden>
			</div>';
			
			$PaginadorLineas = '
			<div class="PaginadorLineas" id="DivPaginadorLineas" style="">
				<p id="TextoPaginadorLineas"></p><!-- Mostrando 1 de 100 Resultados -->
			</div>';
			
			if($OrdenDeTitulos < 1 or $OrdenDeTitulos > 3){
				$OrdenDeTitulos = 2;
			}
			
			$Contenido = $MaximoDeFilas . ' ' . $Paginador . ' ' . $DescargaDeTabla;
			
			if($OrdenDeTitulos == 1){
				$Grid = "display: grid;grid-template-columns: 1fr 1fr 1fr 1fr;text-align: center;";
			}
			if($OrdenDeTitulos == 2){
				$Grid = "display: grid;grid-template-columns: 1fr 1fr;grid-template-rows: 1fr 1fr;";
				$Contenido = $MaximoDeFilas . ' ' . $DescargaDeTabla . ' ' . $Paginador;
			}
			if($OrdenDeTitulos == 3){
				$Grid = "display: grid;grid-template-columns: 1fr 1fr 1fr;text-align: center;";
			}
			if($OrdenDeTitulos == 2.1){
				$Grid = "display: flex;grid-template-columns: 1fr 1fr 1fr;text-align: center;";
			}
			if($OrdenDeTitulos == 3.1){
				$Grid = "display: flex;grid-template-columns: 1fr 1fr 1fr;text-align: center;";
				$Contenido = $MaximoDeFilas . ' ' . $DescargaDeTabla . ' ' . $Paginador;
			}
			
			echo('
					<div class="col-sm-12" id="' . $IdDiv . '" >
						<hr class="size2 hideline">
						<div class="Agrupador" style="' . $Grid . 'background: #FFF;align-items: center;" >
							' . $Contenido . '
						</div>
						' . $PaginadorLineas . '
						<table id="' . $IdTabla . '" style="white-space: nowrap;font-size: 12;' . $StyleTabla . ';" class="table table-hover table-condensed bootstrap-datatable table-bordered">
						</table>
					</div>
					
				</div>
			</div>
			');
		}
		
		public static function CrearBoton($Funcion,$Tamaño,$Texto,$Comentario,$id = "",$StyleDiv = "",$StyloDeBoton = "btn btn-large btn-block btn-tertiary"){
			//fas fa-search
			echo('
			<div class="col-md-' . $Tamaño . '" style="' . $StyleDiv . '">
				<div class="span9 btn-block">
					<button id = "' . $id . '" class="'. $StyloDeBoton .'" type="button" onclick="' . $Funcion . '">
						<i class=""></i>
						' . $Texto . '
					</button>
					<div class="col-md-12" style= "text-align:center; color:#0000C0; font-size:10px;">' . $Comentario . '</div>
				</div>
			</div>
			');
		}
		
		public static function CrearImputDeFecha($id,$IdImput,$Texto,$Tamaño,$Extra = false,$calendario = "FechaHoraMinuto"){
			$ConfiguracionParaNumerico = "";
			if($Extra != false){
				$ConfiguracionParaNumerico = $Extra;
			}
			echo('
				<div class="col-sm-' . $Tamaño . '">
					<div class="form-group">
						<label class="control-label">' . $Texto . ':
							<span class="required" aria-required="true">*</span>
							<b id="BoltText' . $IdImput . '"></b>
						</label>
						<div class="input-group ' . $calendario . '" id="' . $id . '">
							<input type="text" class="form-control" id="' . $IdImput . '" name="' . $IdImput . '" value="01/01/2025 00:00"/ ' . $ConfiguracionParaNumerico .'>
							<span class="input-group-addon">
								<span class="glyphicon glyphicon-calendar"></span>
							</span>
						</div>
					</div>
				</div>
			');
		}
		
		public static function CrearDesplegableConTitulo($Titulo,$Id){
			echo('
			<div class="btn-minimize-CajaDeGrupos" for="' . $Id . '">
				<div class="col-md-12">
					<div class="form-group">
						<h1 class="tituloFormulario-Desplegable form-section" id="" style="text-align: center;">' . $Titulo . '</h1>
					</div>
				</div>
			</div>
			<div id="" style="background: aliceblue;border-bottom-right-radius: 20px 20px!important;border-bottom-left-radius: 20px 20px!important;border: 2px solid rgba(0, 0, 0, .2);">
				<div class="CajaDeGrupos" id="' . $Id . '">
			');
		}
		
		public static function CrearTitulo($Titulo){
			echo('
			<div class="col-md-12">
				<div class="form-group">
					<h1 class="tituloFormulario form-section" id="" style="text-align: center;">' . $Titulo . '</h1>
				</div>
			</div>
			');
		}
		
		public static function SispoCrearImput($Id,$Tipo,$Texto,$Tamaño,$Extra = false,$requiered = false,$autojs = true,$Valor = '',$placeholder = ''){
			$Password = "";
			$ValorRequerido ="";
			if($Tipo == "password"){
				$Password = 'autocomplete="new-password"';
			}
			$ConfiguracionParaNumerico = "";
			if($Extra != false){
				$ConfiguracionParaNumerico = $Extra;
			}
			if($requiered){
				$ValorRequerido ="*";
			}
			$Info = "";
			if($Tipo=="Celular"){
				$Info = "Codigo De Area + Numero Personal 10 Digitos";
			}
			echo('
				<div class="col-md-' . $Tamaño . '" style="">
					<div class="form-group">
						<label class="control-label">' . $Texto . '
							<span class="required" aria-required="true">' . $ValorRequerido . '</span>
							<b style="color:#0000FF;font-size: 10px;width: 90%;">' . $Info . '</b>
							<b id="BoltText' . $Id . '"><b style="color:#FF0000;font-size: 10px;width: 90%;"></b></b>
						</label>
						<div>
							<input ' . $Password . ' placeholder="' . $placeholder . '" type="' . $Tipo . '" id="' . $Id . '" name="' . $Id . '" class="form-control" ' . $ConfiguracionParaNumerico .' value="' . $Valor . '">
						</div>
					</div>
				</div>
			');
			if($autojs){
				echo("
					<script>
						var Config = JSON.parse(`{
							\"Elemento\":\"" . $Id . "\",
							\"ElementoTexto\":\"BoltText" . $Id . "\",
							\"DigitosMinimos\":\"1\",
							\"TextoInicial\":\"\",
							\"TextoMenor\":\"\"
						}`);
						Texto(Config);
					</script>
				");
			}
		}
		
		public static function SispoCrearSelectt($Id,$Texto,$Tamaño,$Extra = false,$span = ''){
			$ConfiguracionExtra = "";
			if($Extra != false){
				$ConfiguracionExtra = $Extra;
			}
			echo('
			<div class="col-md-' . $Tamaño . '">
				<div class="form-group">
					<label class="control-label">' . $Texto . '
						<span class="required" aria-required="true">' . $span . '</span>
						<b id="BoltText' . $Id . '"></b><!-- BoltTextCliente -->
					</label>
					<div>
						<select id="' . $Id . '" name="' . $Id . '" class="form-control select1-Borrado select1-hidden-accessible-Borrado" tabindex="-1" aria-hidden="true" '. $ConfiguracionExtra .'>
						</select>
					</div>
				</div>
			</div>
			');
		}
		public static function SispoDashboardAccesosDirectosIni($QueryMenus){
			$Menuactivo = true;
			$subMenuActivo = true;
			echo('
			<div id="dashboard_accesos_directos">
				<div class="row">
					<div class="col-xs-3">
						<ul class="nav nav-tabs" role="tablist" id="myTab">
			');
			for($i=0;$i<count($QueryMenus);$i++){
				if($QueryMenus[$i]->parent == 0 and $QueryMenus[$i]->dashboard == 1){
					if($Menuactivo){
						echo('
								<li class="active"><a href="#tabAccesoDirecto_'. $QueryMenus[$i]->id .'" role="tab" data-toggle="tab">' . $QueryMenus[$i]->descripcion . '</a></li>
						');
						$Menuactivo = !$Menuactivo;
					}else{
						echo('
									<li class=""><a href="#tabAccesoDirecto_'. $QueryMenus[$i]->id .'" role="tab" data-toggle="tab">' . $QueryMenus[$i]->descripcion . '</a></li>
						');
					}
				}
			}
			echo('
						</ul>
					</div>
					<div class="col-xs-9">
						<div class="tab-content miTab">
			');
			$Menuactivo = true;
			for($i=0;$i<count($QueryMenus);$i++){
				if($QueryMenus[$i]->parent == 0 and $QueryMenus[$i]->dashboard == 1){
					if($Menuactivo){
						echo('
							<div class="tab-pane active" id="tabAccesoDirecto_'.$QueryMenus[$i]->id.'">
								<div class="row" style="padding: 30px;">
						');
						$Menuactivo = !$Menuactivo;
					}else{
						echo('
							<div class="tab-pane" id="tabAccesoDirecto_'.$QueryMenus[$i]->id.'">
								<div class="row" style="padding: 30px;">
						');
					}
					for($j=0;$j<count($QueryMenus);$j++){
						if($QueryMenus[$i]->id == $QueryMenus[$j]->parent and $QueryMenus[$j]->dashboard == 1){
							$Tempurl = url('/');
							echo('
									<div class="col-xs-4">
										<a href="' . $Tempurl . '/SubMenues/' . $QueryMenus[$j]->link . '" class="btn btn-icon btn-block">
											<i class="' . $QueryMenus[$j]->iconpath . ' fa-3x"></i><p>'. $QueryMenus[$j]->descripcion .'</p>
										</a>
									</div>
							');
						}
					}
					echo('
				
								</div>
							</div>
					');
				}
			}
			echo('
						</div>
					</div>
				</div>
				<style type="text/css">
					.nav.nav-tabs li {
						width: 100%;
					}
					.nav-tabs {
						border-bottom: 0;
						border-right: 1px solid #ddd;
						padding-right: 5px;
					}
				</style>
			</div>
			');
		}
		public static function SispoDashboardAccesosDirectosFin(){
			
		}
		
		
		
		public static function SispoCrearBoton($Funcion,$Tamaño,$Texto,$Comentario,$id = "",$StyleDiv = "",$StyloDeBoton = "btn btn-large btn-block btn-primary",$icono=''){
			//fas fa-search
			echo('
			<div class="col-md-' . $Tamaño . '" style="' . $StyleDiv . '">
				<div class="span9 btn-block">
					<button id = "' . $id . '" class="'. $StyloDeBoton .'" type="button" onclick="' . $Funcion . '">
						<i class="' . $icono . '"></i>
						' . $Texto . '
					</button>
					<div class="col-md-12" style= "text-align:center; color:#0000C0; font-size:10px;">' . $Comentario . '</div>
				</div>
			</div>
			');
		}
		
		public static function SispoCrearBotonLinealSecundario($Funcion,$Tamaño,$Texto,$Comentario,$id = "",$StyleDiv = "",$StyloDeBoton = "btn btn-large btn-block btn-primary",$icono=''){
			//fas fa-search
			echo('
			<button id = "' . $id . '" class="'. $StyloDeBoton .'" type="button" onclick="' . $Funcion . '">
				<i class="' . $icono . '"></i>
				' . $Texto . '
			</button>
			');
		}
		
		public static function SispoCrearBotonLinckLineal($href,$Texto,$StyloDeBoton = "btn btn-large btn-block btn-primary",$icono=''){
			//fas fa-search
			echo('
				<a href="' . $href . '" class="'. $StyloDeBoton .'"><i class="' . $icono . '"></i>' . $Texto . '</a>
			');
		}
		
		
		public static function SispoCrearMenu($Titulo,$URL,$Icono,$PermisosFicherosDeMenues){
			if(array_search(explode('/',$URL)[0], $PermisosFicherosDeMenues) !== false){
				echo(
					'<li>' .
					'	<a href="' . url('SubMenues') . '/' . $URL . '">' .
					'		<i class="' . $Icono . '"></i>' .
					'		<span class="title">' . $Titulo . '</span>' .
					'		<span class="selected"></span>' .
					'	</a>' .
					'</li>'
				);
			}
		}
		
		
		public static function SispoCrearMenuRecursivo($QueryMenus){
			for($i = 0; $i < count($QueryMenus);$i++){
				if($QueryMenus[$i]['pariente']){//padre
					echo(
						'<li class="">' .
						'	<a href="#">' .
						'		<i class="' . $QueryMenus[$i]['iconpath'] . '"></i>' .
						'		<span class="nav-label">' . $QueryMenus[$i]['descripcion'] . '</span>' .
						'		<span class="fa arrow"></span>' .
						'	</a>' .
						'	<ul class="nav nav-second-level collapse" style="height: auto;">'
					);
					Elementos::SispoCrearMenuRecursivo($QueryMenus[$i]['hijos']);
					echo(
						'	</ul>' .
						'</li>'
					);
					
				}else{//hijo o menu unico
					$color = 'style=""';
					if(view()->exists( '/SubMenues/' . $QueryMenus[$i]['link'] )){
						$color = 'style="color: aquamarine;"';
					}else{
					    //print_r(Storage::disk('views')->files(''));
					}
					if($QueryMenus[$i]['parent'] == 0){//menu unico
						echo('
								<li>
									<a href="/SubMenues/' . $QueryMenus[$i]['link'] . '">
										<i class="' . $QueryMenus[$i]['iconpath'] . '"></i>
										<span class="title" ' . $color . '>' . $QueryMenus[$i]['descripcion'] . '</span>
										<span class=""></span>
									</a>
								</li>
							');
					}else{//hijo
						echo('
							<li>
								<a href="/SubMenues/' . $QueryMenus[$i]['link'] . '" ' . $color . ' >
									<i class="" ' . $QueryMenus[$i]['iconpath'] . '""></i> ' . $QueryMenus[$i]['descripcion'] . '
								</a>
							</li>
						');
					}
					
				}
			}
/*
			$Encontrados =  array_intersect($URLConPermisos, $PermisosFicherosDeMenues);
			if(count($Encontrados) > 0 ){
				echo(
					'<li class="">' .
					'	<a href="#">' .
					'		<i class="' . $Icono . '"></i>' .
					'		<span class="nav-label">' . $Titulo . '</span>' .
					'		<span class="fa arrow"></span>' .
					'	</a>' .
					'	<ul class="nav nav-second-level collapse" style="height: auto;">'
				);
				for($i=0; $i < count($TituloSubMenues); $i++){
					$IconoSub="";
					if($Iconos!=null){
						$IconoSub=$Iconos[$i];
					}
					Elementos::SispoCrearSubMenu($TituloSubMenues[$i],$URLSubMenues[$i],$PermisosFicherosDeMenues,$IconoSub);
				}
				echo(
					'	</ul>' .
					'</li>'
				);
			}else{
			}
			*/
		}
		
		
		
		
		
		
		
		public static function SispoCrearMainMenu($id,$Titulo,$Icono,$TituloSubMenues,$URLSubMenues,$PermisosFicherosDeMenues,$Iconos = null){
			$URLConPermisos = $URLSubMenues;
			for($i = 0; $i < count($URLSubMenues);$i++){
				$URLConPermisos[$i] = explode('/',$URLSubMenues[$i])[0];
			}
			$Encontrados =  array_intersect($URLConPermisos, $PermisosFicherosDeMenues);
			if(count($Encontrados) > 0 ){
				echo(
					'<li class="">' .
					'	<a href="#">' .
					'		<i class="' . $Icono . '"></i>' .
					'		<span class="nav-label">' . $Titulo . '</span>' .
					'		<span class="fa arrow"></span>' .
					'	</a>' .
					'	<ul class="nav nav-second-level collapse" style="height: auto;">'
				);
				for($i=0; $i < count($TituloSubMenues); $i++){
					$IconoSub="";
					if($Iconos!=null){
						$IconoSub=$Iconos[$i];
					}
					Elementos::SispoCrearSubMenu($TituloSubMenues[$i],$URLSubMenues[$i],$PermisosFicherosDeMenues,$IconoSub);
				}
				echo(
					'	</ul>' .
					'</li>'
				);
			}else{
			}
		}
		
		public static function SispoCrearSubMenu($Titulo,$URL,$PermisosFicherosDeMenues,$IconoSub){
			if(array_search(explode('/',$URL)[0], $PermisosFicherosDeMenues) !== false){
				echo(
					'<li>' .
					'	<a href="' . url('SubMenues') . '/' . $URL . '">' .
					'	<i class="' . $IconoSub . '"></i>' .
					$Titulo .
					'	</a>' .
					'</li>'
				);
			}
		}
		
		/*
		<li class="">
			<a href="#"><i class="fa fa-cog"></i> <span class="nav-label">Clientes</span><span class="fa arrow"></span></a>
			<ul class="nav nav-second-level   collapse">
			<li><a href="https://sispo.com.ar/clientes/clientes"><i class="fa fa-cog"></i> Clientes</a></li>
			</ul>
		</li>
		*/
		
		
		
		
		
		
		
		public static function CrearMenu($Titulo,$URL,$Icono,$PermisosFicherosDeMenues){
			if(array_search(explode('/',$URL)[0], $PermisosFicherosDeMenues) !== false){
				echo(
					'<a class="list-group-item list-group-item-action sidebar text-light" href="' . url('SubMenues') . '/' . $URL . '">' .
					'<i class="' . $Icono . '"></i> ' .
					'<font style="vertical-align: inherit;"> ' .
					'<font style="vertical-align: inherit;">' .
					$Titulo .
					'</font>' .
					'</font>' .
					'</a>'
				);
			}
		}
		
		public static function CrearMainMenu($id,$Titulo,$Icono,$TituloSubMenues,$URLSubMenues,$PermisosFicherosDeMenues){
			$URLConPermisos = $URLSubMenues;
			for($i = 0; $i < count($URLSubMenues);$i++){
				$URLConPermisos[$i] = explode('/',$URLSubMenues[$i])[0];
			}
			$Encontrados =  array_intersect($URLConPermisos, $PermisosFicherosDeMenues);
			if(count($Encontrados) > 0 ){
				echo(
					'<a class="nav-item">' .
						'<a id="' . $id . '" class="list-group-item list-group-item-action sidebar text-light" href="#' . $id . '" data-toggle="collapse" data-target="#collapse' . $id . '" aria-expanded="true" aria-controls="collapsePages">' .
							'<i class="' . $Icono . '"></i>' .
							'<span>' . $Titulo . '</span>' .
						'</a>' .
						'<div id="collapse' . $id . '" class="SubMenu collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">'
				);
				for($i=0; $i < count($TituloSubMenues); $i++){
					Elementos::CrearSubMenu($TituloSubMenues[$i],$URLSubMenues[$i],$PermisosFicherosDeMenues);
				}
				echo(
						'</div>' .
					'</a>'
				);
			}else{
			}
		}
		
		public static function CrearSubMenu($Titulo,$URL,$PermisosFicherosDeMenues){
			if(array_search(explode('/',$URL)[0], $PermisosFicherosDeMenues) !== false){
				echo(
					'<a class="ElementosDeSubMenu collapse list-group-item list-group-item-action text-light collapse-item" href="' . url('SubMenues') . '/' . $URL . '">' .
						'<i></i>' . $Titulo .
					'</a>'
				);
			}
		}
		
	}
?>