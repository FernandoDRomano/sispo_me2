class funcionesglobales{
	
	public static function CrearArraydConBalores($Size,$value){
		$array;
		for($i=0;$i<$Size;$i++){
			$array[$i] = $value;
		}
		return $array;
	}
	
	public static function InsertarValoresEnArrayd($array,$arrayInsert){
        $Keys = array_keys($arrayInsert);
		for($i=0;$i<count($Keys);$i++){
			$array[$Keys[$i]] = $arrayInsert[$Keys[$i]];
		}
		return $array;
	}
	
	public static function SaveFile($DB,$Columnas,$Formato,$Reemplazos,$StringSize = [],$OrdenPorFecha = false){
		$NombreDeArchivo = $Formato['NombreDeArchivo'];
		$EXTENCION = $Formato['EXTENCION'];
		$path = $Formato['path'];
		$Subpath = $Formato['Subpath'];
		$FinDeTexto = $Formato['FinDeTexto'];
		$FinDeLinea = $Formato['FinDeLinea'];
		
		if(!is_dir($path)){mkdir($path);}
		$path = $path . $Subpath;
		if(!is_dir($path)){mkdir($path);}
		if($OrdenPorFecha){
			$path = $path . date('Y', time()) . '/';
			if(!is_dir($path)){mkdir($path);}
			$path = $path . date('m', time()) . '/';
			if(!is_dir($path)){mkdir($path);}
			$path = $path . date('d', time()) . '/';
			if(!is_dir($path)){mkdir($path);}
			
			$FileAndFolder = $path . $NombreDeArchivo . ' ' . date('H', time()) . 'Horas '. date('i', time()) .'Minutos '. date('s', time()) .'Segundos '. $EXTENCION;
			$File = fopen($FileAndFolder, "w");
			/*
			$Sizes = ["10","50","10","3","25","50","15","50","10","2","2","50","20","20","12","10","10","9","3","20"];
			date_default_timezone_set($default_timezone);
			$ResultadoAnterior = false;
			*/
		}else{
			$FileAndFolder = $path . $NombreDeArchivo . $EXTENCION;
			$File = fopen($FileAndFolder, "w");
		}
		
		
		$ReemplazoDe = $Reemplazos['ReemplazoDe'];
		$ReemplazoA = $Reemplazos['ReemplazoA'];
		
		
		if(count($StringSize)> 0){
			$Sizes = $StringSize['Sizes'];
			$relleno = $StringSize['relleno'];
			$Alineacion = $StringSize['Alineacion'];
			$result = $DB;
			$result = funcionesglobales::BDLargaAArrayTabla($DB,$Columnas);
			//$result = $DB;
			
			$Keys = array_keys($result);
			$Lineas = count($result[$Keys[0]]);
			
			for($i=0;$i<$Lineas; $i++){
				for($j=0;$j<count($Columnas); $j++){
					$str = $result[$Columnas[$j]][$i];
					if($ReemplazoDe[$j] != ''){
						$str = str_replace($ReemplazoDe[$j],$ReemplazoA[$j],$str);
					}
					if($j+1 == count($Columnas)){
						$str = funcionesglobales::StringSize($str,$Sizes[$j],'UTF-8',$relleno[$j],$Alineacion[$j],$FinDeLinea);
					}else{
						$str = funcionesglobales::StringSize($str,$Sizes[$j],'UTF-8',$relleno[$j],$Alineacion[$j],$FinDeTexto);
					}
					fwrite($File,$str);
				}
				fwrite($File, ''.PHP_EOL.'' );
			}
			
			/*
			for($i=0;$i<$Lineas; $i++){
				for($j=0;$j<count($Keys); $j++){
					if(in_array($Keys[$j], $Columnas)){
						
					}
					
					$str = $result[$Keys[$j]][$i];
					if($ReemplazoDe[$j] != ''){
						$str = str_replace($ReemplazoDe[$j],$ReemplazoA[$j],$str);
					}
					if($j+1 == count($Keys)){
						$str = funcionesglobales::StringSize($str,$Sizes[$j],'UTF-8',$relleno[$j],$Alineacion[$j],$FinDeLinea);
					}else{
						$str = funcionesglobales::StringSize($str,$Sizes[$j],'UTF-8',$relleno[$j],$Alineacion[$j],$FinDeTexto);
					}
					fwrite($File,$str);
				}
				fwrite($File, ''.PHP_EOL.'' );
			}
			*/
		}else{
			$result = funcionesglobales::BDAArrayTabla($DB,$Columnas);
		}
		
		return true;
	}
	
	
	public static function StringSize($str,$size,$modo,$Relleno,$LugarDeRelleno,$FinalDeLinea){
		$strT ;
		if(mb_detect_encoding($str, "auto") === "UTF-8"){
			//$strT = mb_substr( str_pad($str,$size,$Relleno,$LugarDeRelleno),0,$size,"ASCII");
			$str = funcionesglobales::ToASCIITilde($str);
			$strT = mb_substr( str_pad($str,$size,$Relleno,$LugarDeRelleno),0,$size,"UTF-8") . $FinalDeLinea ;
		}else{
			$strT = mb_substr( str_pad($str,$size,$Relleno,$LugarDeRelleno),0,$size,$modo) . $FinalDeLinea ;
		}
		//$strT = $strT . "(".mb_detect_encoding($str, "auto").")";
		return $strT;
	}
	
	public static function ToASCIITilde($str) { 
		$a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ'); 
		//$b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o'); 
		$b = array('a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'd', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'a', 'a', 'a', 'a', 'a', 'a', 'c', 'c', 'c', 'c', 'c', 'c', 'c', 'c', 'd', 'd', 'd', 'd', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'g', 'g', 'g', 'g', 'g', 'g', 'g', 'g', 'h', 'h', 'h', 'h', 'i', 'i', 'i', 'i', 'i', 'i', 'i', 'i', 'i', 'i', 'ij', 'ij', 'j', 'j', 'k', 'k', 'l', 'l', ' Lv', 'l', 'l', 'l', 'l', 'l', 'l', 'l', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'oe', 'oe', 'r', 'r', 'r', 'r', 'r', 'r', 's', 's', 's', 's', 's', 's', 's', 's', 't', 't', 't', 't', 't', 't', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'w', 'w', 'y', 'y', 'y', 'z', 'z', 'z', 'z', 'z', 'z', 's', 'f', 'o', 'o', 'u', 'u', 'a', 'a', 'i', 'i', 'o', 'o', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'a', 'a', 'ae', 'ae', 'o', 'o');
		return str_replace($a, $b, $str); 
	}
	
	public static function Declarar($ArrayValues,$result){
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$Keys = array_keys($result);
		$ExistenLosDatos = funcionesglobales::AllArraySearchInArray($ArrayValues, $result);
		if($ExistenLosDatos!=false){
			$Keys = array_keys($result);
			for($i = 0; count($Keys)>$i;$i++){
				$Key = $Keys[$i];
				$Values = $Key;
				global $$Values;
				$$Values = $result[$Key];
			}
		}
		//$resp[] = ['flashpiezas_TipoDeEstado' => $flashpiezas_TipoDeEstado];
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	}
	
	public static function AllArraySearchInArray($ArrayValues,$Inresult){
		//$Keys = array_keys($Inresult);
		if(count($ArrayValues)>0){
			for($i=0;$i<count($ArrayValues);$i++){
				$resp = array_key_exists($ArrayValues[$i], $Inresult);
				//$resp = array_search($ArrayValues[$i], $Keys);
				if($resp==false){return false;}
			}
			return true;
		}else{return false;}
	}
	public static function ArrayQueryIn($Array) {
        $Resp = "";
		for ($i =0 ; count($Array) > $i ; $i++) {
			if($i == 0) {
				$Resp = $Resp . "(";
			}else{
				$Resp = $Resp . ",";
			}
			$Resp = $Resp . "'" . $Array[$i] . "'";
			if($i+1 == count($Array)) {
				$Resp = $Resp . ")";
			}
		}
		return $Resp;
    }
    public static function TablaToStringQueryIn($Array,$Columna) {
        $Resp = "";
		for ($i =0 ; count($Array) > $i ; $i++) {
			$valor = $Array[$i];
			if($i == 0) {
				$Resp = $Resp . "(";
			}else{
				$Resp = $Resp . ",";
			}
			$Resp = $Resp . "'" . $valor[$Columna] . "'";
			if($i+1 == count($Array)) {
				$Resp = $Resp . ")";
			}
		}
		return $Resp;
    }
	
    public static function ArrayStringQueryIn($Array,$keys) {
        $Resp;
		for($i=0; $i<count($keys);$i++){
			$key=$keys[$i];
			for($j=0; $j<count($Array[$key]);$j++){
				$valor = $Array[$key][$j];
				if($j==0){
					$Resp[$key] = "(";
				}else{
					$Resp[$key] = $Resp[$key] . ",";
				}
				$Resp[$key] = $Resp[$key] . "'" . $valor . "'";
				if($j+1 == count($Array[$key])) {
					$Resp[$key] = $Resp[$key] . ")";
				}
			}
		}
		return $Resp;
    }
	public static function StartConcatStringInArray($Array,$keys,$concatenar,$MantenerNulus = 'true') {
		//($Barras,$keys, ['','01'],['','CF']);
		$Resp;
		for($i=0; $i<count($keys);$i++){
			$key=$keys[$i];
			for($j=0; $j<count($Array[$key]);$j++){
				if($concatenar[$i] != '' ){
					if($Array[$key][$j] == '' && $MantenerNulus){
						$Resp[$key][$j] = $Array[$key][$j];
					}else{
						$Resp[$key][$j]= $concatenar[$i] . $Array[$key][$j];
						//$Res[$key] = $Array[$key];
					}
				}else{
					$Resp[$key][$j] = $Array[$key][$j];
				}
			}
		}
		return $Resp;
    }
	
	public static function ArrayReplaceStringOnColumnas($Array,$keys,$replace,$ReplaceFor) {
		//($Barras,$keys, ['','01'],['','CF']);
		$Resp;
		for($i=0; $i<count($keys);$i++){
			$key=$keys[$i];
			for($j=0; $j<count($Array[$key]);$j++){
				if($replace[$i] != ''){
					$Resp[$key][$j]= str_replace(strtolower($replace[$i]),strtolower($ReplaceFor[$i]),strtolower($Array[$key][$j]));
					//$Resp[$key] = $Array[$key];
				}else{
					$Resp[$key][$j] = $Array[$key][$j];
				}
				//$Resp[$key] = substr_replace($Array[$key], $replace[$i], $From,$To);
			}
		}
		return $Resp;
    }
	
	public static function ReplaceStringOnEspecificColumns($DB,$ColumnasRequeridas,$replace,$ReplaceFor) {
		//($Barras,$keys, ['','01'],['','CF']);
		$DB = json_encode($DB);
        $DB = json_decode($DB, true);
        if( $DB == null or $ColumnasRequeridas == null){
            return;
        }
		$DBTemporal = array();
        $ColumnasReq = count($ColumnasRequeridas);
		$NumeroDeFilas = count($DB);
        $CantidadDeColumnas = count($DB[0]);
		$Columnas = array_keys($DB[0]);
		
		if(count($ColumnasRequeridas) <= 0){
			return $DB;
		}
		
		$NDCR = array();
		for($i=0;$i<$CantidadDeColumnas;$i++){
			$NDCR[$i] = -1;
			for($j=0;$j<$ColumnasReq;$j++){
				if($Columnas[$i] == $ColumnasRequeridas[$j]){
					$NDCR[$i] = $j;
				}
			}
		}
		for($i=0;$i<$NumeroDeFilas;$i++){
			
			$DBTemporal[$i] = array();
			$filaTemporal = array();
			for($j=0;$j<$CantidadDeColumnas;$j++){
				if($NDCR[$j] >= 0){
					//$filaTemporal[$Columnas[$j]] = $DB[$i][$Columnas[$j]];
					
					//$filaTemporal[$Columnas[$j]] = str_replace(strtolower($replace[$NDCR[$j]]),strtolower($ReplaceFor[$NDCR[$j]]),strtolower($DB[$i][$Columnas[$j]]));
					$filaTemporal[$Columnas[$j]] =$DB[$i][$Columnas[$j]];
					for($k=0;$k<count($replace[$NDCR[$j]]);$k++){
						$filaTemporal[$Columnas[$j]] = 
						str_replace(
							$replace[$NDCR[$j]][$k],
							$ReplaceFor[$NDCR[$j]][$k],
							$filaTemporal[$Columnas[$j]]
						);
					}
					//Log::info($NumerosDeColumnasRequeridas[$j]);
					//Log::info($j);
				}else{
					
					$filaTemporal[$Columnas[$j]] = $DB[$i][$Columnas[$j]];
				}
			}
			$DBTemporal[$i] = $filaTemporal;
		}
		
		/*
		for($i=0;$i<$CantidadDeColumnas;$i++){
			
			$ColumnaEncontrada = false;
			$ColumnaDeEncontro = 0;
			for($j=0;$j<$ColumnasReq;$j++){
				if($Columnas[$i] == $ColumnasRequeridas[$j]){
					$ColumnaEncontrada = true;
					$ColumnaDeEncontro = $j;
				}
			}
			if(!$ColumnaEncontrada){
				$DBTemporal[$Columnas[$i]] = array();
				$filaTemporal = array();
				for($j=0;$j<$NumeroDeFilas;$j++){
					//$filaTemporal[$Columnas] = array();
					//$test=$DB[$j][$Columnas[$i]];
					$filaTemporal[$j] = $DB[$j][$Columnas[$i]];
					//Log::info($filaTemporal[$j]);
				}
				$DBTemporal[$Columnas[$i]] = $filaTemporal;
			}else{
				
				$DBTemporal[$ColumnasRequeridas[$ColumnaDeEncontro]] = array();
				$filaTemporal = array();
				for($j=0;$j<$NumeroDeFilas;$j++){
					//$Resp[$key][$j]= str_replace(strtolower($replace[$ColumnaDeEncontro]),strtolower($ReplaceFor[$ColumnaDeEncontro]),strtolower($DB[$j][$ColumnasRequeridas[$i]]));
					//$filaTemporal[$j] = $DB[$j][$ColumnasRequeridas[$i]];
					$filaTemporal[$j] = str_replace(strtolower($replace[$ColumnaDeEncontro]),strtolower($ReplaceFor[$ColumnaDeEncontro]),strtolower($DB[$j][$ColumnasRequeridas[$ColumnaDeEncontro]]));
					Log::info($filaTemporal[$j]);
				}
				$DBTemporal[$ColumnasRequeridas[$ColumnaDeEncontro]] = $filaTemporal;
			}
			
		}
		*/
        return $DBTemporal;
		
		/*
		if(count($ColumnasRequeridas) >= 0){
			for($i=0;$i<$ColumnasReq;$i++){
				$DBTemporal[$ColumnasRequeridas[$i]] = array();
				$filaTemporal = array();
				for($j=0;$j<$NumeroDeFilas;$j++){
					//$filaTemporal[$ColumnasRequeridas] = array();
					$test=$DB[$j][$ColumnasRequeridas[$i]];
					$filaTemporal[$j] = $DB[$j][$ColumnasRequeridas[$i]];
				}
				$DBTemporal[$ColumnasRequeridas[$i]] = $filaTemporal;
				//array_push($DBTemporal[$ColumnasRequeridas[$i]],$filaTemporal);
			}
		}
		*/
		return $Resp;
    }
	
    public static function ArrayConcatStringOnColumnas($Array,$keys,$replace,$From,$To) {
		$Resp;
		for($j=0; $j<count($Array);$j++){
			for($i=0; $i<count($keys);$i++){
				$key=$keys[$i];
				$Resp[$key] = substr_replace($Array[$key], $replace[$i], $From,$To);
			}
		}
		return $Resp;
    }
	public static function getUserIP() {
		if( array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER) && !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ) {
			if (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',')>0) {
				$addr = explode(",",$_SERVER['HTTP_X_FORWARDED_FOR']);
				return trim($addr[0]);
			} else {
				return $_SERVER['HTTP_X_FORWARDED_FOR'];
			}
		}
		else {
			return $_SERVER['REMOTE_ADDR'];
		}
	}
    public static function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    public static function Log($URL,$File,$Metod,$IdDeError,$Msj){
        Log::channel('daily')->info('Log-------------------------------------------------------------------------');
        Log::channel('daily')->info('URL:' . $IdDeError);
        Log::channel('daily')->info('URL:' . $URL);
        Log::channel('daily')->info('Metodo:' . $File);
        Log::channel('daily')->info('Linea:' . $Metod);
        Log::channel('daily')->info('Mensaje:' . $Msj);
        Log::channel('daily')->info('Mensaje:' . $Msj);
        Log::channel('daily')->info('----------------------------------------------------------------------------');
        Log::channel('daily')->info('');
    }
    public static function UnionDeTabla($DB1,$DB2){
        return json_encode(array_merge($DB1,$DB2));
    }
    public static function num2alpha($n) {
        $r = '';
        for ($i = 1; $n >= 0 && $i < 10; $i++) {
        $r = chr(0x41 + ($n % pow(26, $i) / pow(26, $i - 1))) . $r;
        $n -= pow(26, $i);
        }
        return $r;
    }

    public static function UnionDeConsultasRetornadas($DB,$DB2){
        $DB = json_encode($DB);
        $DB2 = json_encode($DB2);
        $DB = json_decode($DB, true);
        $DB2 = json_decode($DB2, true);
        if( $DB == null or $DB2 == null){
            return;
        }
        if ( is_array($DB)) {
            if(count($DB)==0){
                if ( is_array($DB[0])) {
                    $DB = $DB[0];
                }
            }
        }
        if ( is_array($DB2)) {
            if(count($DB2)==0){
                if ( is_array($DB2[0])) {
                    $DB2 = $DB2[0];
                }
            }
        }
        $DBTemporal = $DB2;
        $Filas = count($DB);
        $Columnas = count($DB[0]);
        $Keys = array_keys($DB[0]);
        $FilaInicial = count($DB2);
        if( $Filas >= 0){
            for($i=0;$i<count($DB);$i++){//filas a agregar
                array_push($DBTemporal,$DB[$i]);
            }
        }
        return funcionesglobales::super_unique($DBTemporal);
        //return $DBTemporal;
    }
    
    public static function BDAArrayTabla($DB,$ColumnasRequeridas){
        $DB = json_encode($DB);
        $DB = json_decode($DB, true);
        if( $DB == null or $ColumnasRequeridas == null){
            return;
        }
        $DBTemporal = array();
        //print_r(array_keys($DB[0]));
        //echo("\n\n\n\n\n");
        $Filas = count($DB);
        $Columnas = count($DB[0]);
        $ColumnasReq = count($ColumnasRequeridas);
        
        $Keys = array_keys($DB[0]);
        if( $Filas >= 0 and count($ColumnasRequeridas) >= 0){
            for($i=0;$i<count($DB);$i++){
                for($j=0;$j<$ColumnasReq;$j++){
                    for($k=0;$k<$Columnas;$k++){
                        if($ColumnasRequeridas[$j] == $Keys[$k]){
                            if(!isset($DBTemporal[$Keys[$k]])){
                                $DBTemporal[$Keys[$k]] = array();
                            }
                            array_push($DBTemporal[$Keys[$k]],$DB[$i][$Keys[$k]]);
                        }
                    }
                }
            }
        }
        return $DBTemporal;
    }
	
	//public static function BDLargaAArrayTabla($DB,$ColumnasRequeridas){
	
	public static function BDLargaAArrayTabla($DB,$ColumnasRequeridas){
		//$test = $DB[0]->PIEZANUME;
		$DB = json_encode($DB);
        $DB = json_decode($DB, true);
        if( $DB == null or $ColumnasRequeridas == null){
            return;
        }
		Log::info(print_r($DB,true));
		
		$DBTemporal = array();
		$Keys = array_keys($DB);
        $ColumnasReq = count($ColumnasRequeridas);
		$NumeroDeFilas = count($DB);
		
		if(count($ColumnasRequeridas) >= 0){
			for($i=0;$i<$ColumnasReq;$i++){
				$DBTemporal[$ColumnasRequeridas[$i]] = array();
				$filaTemporal = array();
				for($j=0;$j<$NumeroDeFilas;$j++){
					//$filaTemporal[$ColumnasRequeridas] = array();
					$test=$DB[$j][$ColumnasRequeridas[$i]];
					$filaTemporal[$j] = $DB[$j][$ColumnasRequeridas[$i]];
				}
				$DBTemporal[$ColumnasRequeridas[$i]] = $filaTemporal;
				//array_push($DBTemporal[$ColumnasRequeridas[$i]],$filaTemporal);
			}
		}
        return $DBTemporal;
		
        $DB = json_encode($DB);
        $DB = json_decode($DB, true);
        if( $DB == null or $ColumnasRequeridas == null){
            return;
        }
        $DBTemporal = array();
        //print_r(array_keys($DB[0]));
        //echo("\n\n\n\n\n");
        $Filas = count($DB);
        $Columnas = count($DB[0]);
        
        $Keys = array_keys($DB[0]);
        if( $Filas >= 0 and count($ColumnasRequeridas) >= 0){
            for($i=0;$i<count($DB);$i++){
                for($j=0;$j<$ColumnasReq;$j++){
                    for($k=0;$k<$Columnas;$k++){
                        if($ColumnasRequeridas[$j] == $Keys[$k]){
                            if(!isset($DBTemporal[$Keys[$k]])){
                                $DBTemporal[$Keys[$k]] = array();
                            }
                            array_push($DBTemporal[$Keys[$k]],$DB[$i][$Keys[$k]]);
                        }
                    }
                }
            }
        }
        return $DBTemporal;
    }
	
	
	
	
    public static function BDAArray($DB,$ColumnasRequeridas){
        $DB = json_encode($DB);
        $DB = json_decode($DB, true);
        if( $DB == null or $ColumnasRequeridas == null){
            return;
        }
        $DBTemporal = array();
        //print_r(array_keys($DB[0]));
        //echo("\n\n\n\n\n");
        $Filas = count($DB);
        $Columnas = count($DB[0]);
        $ColumnasReq = count($ColumnasRequeridas);
        
        $Keys = array_keys($DB[0]);
        if( $Filas >= 0 and count($ColumnasRequeridas) >= 0){
            for($i=0;$i<count($DB);$i++){
                for($j=0;$j<$ColumnasReq;$j++){
                    for($k=0;$k<$Columnas;$k++){
                        if($ColumnasRequeridas[$j] == $Keys[$k]){
                            $DBTemporal[$i][$j] = $DB[$i][$Keys[$k]]; 
                        }
                    }
                }
            }
        }
        return $DBTemporal;
    }
	
    public static function BDASimpleArray($DB,$ColumnasRequeridas){
        $DB = json_encode($DB);
        $DB = json_decode($DB, true);
        if( $DB == null or $ColumnasRequeridas == null){
            return;
        }
        $DBTemporal = array();
		foreach ($DB as $Col) {
			$Columnas = (array)$Col;
			$Keys = array_keys($Columnas);
			if(funcionesglobales::AllArraySearchInArray($ColumnasRequeridas,$Columnas)){
				$DBTemporal[$Columnas[$Keys[0]]] = $Columnas[$Keys[1]];
			}
		}
        return $DBTemporal;
    }
	
    public static function ApiRes($data,$codigo = 200){
        $result = [
            'error' => false,
            'codigo' => $codigo,
            'data' => $data,
        ];
        return ($result);
    }
    public static function ApiResFail($data,$codigo = 400){
        $result = [
            'error' => true,
            'codigo' => $codigo,
            'data' => $data,
        ];
        return ($result);
    }
    public static function ApiResDirectFail($data = "Error Indefinido",$codigo = 400){
        $Error[] = [
            'error' => $data,
        ];
        $result = [
            'error' => true,
            'codigo' => $codigo,
            'data' => $Error,
        ];
        return ($result);
    }
    public static function array_to_object($arr) {
        $arrObject = array();
        foreach ($arr as $array) {
            $object = new \stdClass();
            foreach ($array as $key => $value) {
                $object->$key = $value;
            }
            $arrObject[] = $object;
        }
    
        return $arrObject;
    }
    public static function GeneradorDeMenues(&$QueryMenues, $parent = 0){
		$Menues = [];
		if($parent==0){
			for($i=0,$interno=0;$i<count($QueryMenues);$i++){
		    
				$ArrayLink = explode("/", $QueryMenues[$i]->link);
				foreach ($ArrayLink as &$valor) {
					$valor = ucfirst($valor);
				}
				$QueryMenues[$i]->link = implode('/', $ArrayLink);
				
				if($QueryMenues[$i]->parent == 0){
					$Ansestro = $QueryMenues[$i]->id;
					$Menues[$interno]['hijos'] = funcionesglobales::GeneradorDeMenues($QueryMenues,$Ansestro);
					if(count($Menues[$interno]['hijos'])==0){
						$Menues[$interno] = [];
						$Menues[$interno]['id'] = $QueryMenues[$i]->id;
						$Menues[$interno]['link'] = ucfirst($QueryMenues[$i]->link);
						$Menues[$interno]['descripcion'] = $QueryMenues[$i]->descripcion;
						$Menues[$interno]['iconpath'] = $QueryMenues[$i]->iconpath;
						$Menues[$interno]['estado'] = $QueryMenues[$i]->estado;
						$Menues[$interno]['parent'] = $QueryMenues[$i]->parent;
						$Menues[$interno]['iconpath'] = $QueryMenues[$i]->iconpath;
						$Menues[$interno]['active'] = $QueryMenues[$i]->active;
						$Menues[$interno]['dashboard'] = $QueryMenues[$i]->dashboard;
						$Menues[$interno]['orden'] = $QueryMenues[$i]->orden;
						$Menues[$interno]['pariente'] = false;
					}else{
						$Menues[$interno]['id'] = $QueryMenues[$i]->id;
						$Menues[$interno]['link'] = ucfirst($QueryMenues[$i]->link);
						$Menues[$interno]['descripcion'] = $QueryMenues[$i]->descripcion;
						$Menues[$interno]['iconpath'] = $QueryMenues[$i]->iconpath;
						$Menues[$interno]['estado'] = $QueryMenues[$i]->estado;
						$Menues[$interno]['parent'] = $QueryMenues[$i]->parent;
						$Menues[$interno]['iconpath'] = $QueryMenues[$i]->iconpath;
						$Menues[$interno]['active'] = $QueryMenues[$i]->active;
						$Menues[$interno]['dashboard'] = $QueryMenues[$i]->dashboard;
						$Menues[$interno]['orden'] = $QueryMenues[$i]->orden;
						$Menues[$interno]['pariente'] = true;
					}

					$interno++;
				}
			}
			return $Menues;
		}else{
			for($i=0,$interno=0;$i<count($QueryMenues);$i++){
				$ArrayLink = explode("/", $QueryMenues[$i]->link);
				foreach ($ArrayLink as &$valor) {
					$valor = ucfirst($valor);
				}
				$QueryMenues[$i]->link = implode('/', $ArrayLink);
				if($QueryMenues[$i]->parent == $parent){
					$Ansestro = $QueryMenues[$i]->id;
					$Menues[$interno]['hijos'] = funcionesglobales::GeneradorDeMenues($QueryMenues,$Ansestro);
					if(count($Menues[$interno]['hijos'])==0){
						$Menues[$interno] = [];
						
						$Menues[$interno]['id'] = $QueryMenues[$i]->id;
						$Menues[$interno]['link'] = ucfirst($QueryMenues[$i]->link);
						$Menues[$interno]['descripcion'] = $QueryMenues[$i]->descripcion;
						$Menues[$interno]['iconpath'] = $QueryMenues[$i]->iconpath;
						$Menues[$interno]['estado'] = $QueryMenues[$i]->estado;
						$Menues[$interno]['parent'] = $QueryMenues[$i]->parent;
						$Menues[$interno]['iconpath'] = $QueryMenues[$i]->iconpath;
						$Menues[$interno]['active'] = $QueryMenues[$i]->active;
						$Menues[$interno]['dashboard'] = $QueryMenues[$i]->dashboard;
						$Menues[$interno]['orden'] = $QueryMenues[$i]->orden;
						$Menues[$interno]['pariente'] = false;
					}else{
						$Menues[$interno]['id'] = $QueryMenues[$i]->id;
						$Menues[$interno]['link'] = ucfirst($QueryMenues[$i]->link);
						$Menues[$interno]['descripcion'] = $QueryMenues[$i]->descripcion;
						$Menues[$interno]['iconpath'] = $QueryMenues[$i]->iconpath;
						$Menues[$interno]['estado'] = $QueryMenues[$i]->estado;
						$Menues[$interno]['parent'] = $QueryMenues[$i]->parent;
						$Menues[$interno]['iconpath'] = $QueryMenues[$i]->iconpath;
						$Menues[$interno]['active'] = $QueryMenues[$i]->active;
						$Menues[$interno]['dashboard'] = $QueryMenues[$i]->dashboard;
						$Menues[$interno]['pariente'] = true;
					}
					$interno++;
				}
			}
			return $Menues;
		}
		return ($Menues);
	}
    public static function super_unique($array)
    {
        $result = array_map("unserialize", array_unique(array_map("serialize", $array)));
        foreach ($result as $key => $value)  {
            if ( is_array($value) and count($value)>0) {
              $result[$key] = funcionesglobales::super_unique($value);
            }
        }
        return $result;
    }
    
    public static function merge_arrays($arr1, $arr2) {
        $arr1 = (array)$arr1;
        $arr2 = (array)$arr2;
        $output = array_merge($arr1, $arr2);
        sort($output);
        return funcionesglobales::super_unique($output);
    }


    public static function DatosFaltantesEnGetJson($Requiered,$Keys){
        $faltandatos = false;
        $ErrorGet = array();
        if(count($Keys)==0 and count($Requiered)!=0){
            $ErrorGet[] = [
                'error' => 'faltan ' . 'Dodos Los Campos',
            ];
            for($cont = 0 ; $cont < count($Requiered);$cont++){
                $ErrorGet[] = [
                    'error' => 'falta ' . $Requiered[$cont],
                ];
            }
            if( count($ErrorGet) ){
                if(APP_DEBUG){
                    $result = [
                        'codigo' => 400,
                        'data' => $ErrorGet,
                    ];
                    return ($result);
                }else{
                    $result = [
                        'codigo' => 400,
                        'data' => $ErrorGet,
                    ];
                    return ($result);
                }
            }
            return $ErrorGet;
        }
        for($cont = 0 ; $cont < count($Requiered);$cont++){
            if(!in_array($Requiered[$cont],$Keys)){
                $faltandatos = true;
                $ErrorGet[] = [
                    'error' => 'falta ' . $Requiered[$cont],
                ];
            }
        }
        if( count($ErrorGet) ){
            if(APP_DEBUG){
                $result = [
                    'codigo' => 400,
                    'data' => $ErrorGet,
                ];
                return ($result);
            }else{
                $result = [
                    'codigo' => 400,
                    'data' => $ErrorGet,
                ];
                return ($result);
            }
        }
        return $ErrorGet;
    }
    public static function DatosFaltantesEnGet($Requiered,$Keys){
        $faltandatos = false;
        $ErrorGet = array();
        if(count($Keys)==0 and count($Requiered)!=0){
            $ErrorGet[] = [
                'error' => 'faltan ' . 'Dodos Los Campos',
            ];
            for($cont = 0 ; $cont < count($Requiered);$cont++){
                $ErrorGet[] = [
                    'error' => 'falta ' . $Requiered[$cont],
                ];
            }
            if( count($ErrorGet) ){
                if(APP_DEBUG){
                    abort(404);
                }else{
                    $result = [
                        'codigo' => 400,
                        'data' => $ErrorGet,
                    ];
                    return ($result);
                }
            }
            return $ErrorGet;
        }
        for($cont = 0 ; $cont < count($Requiered);$cont++){
            if(!in_array($Requiered[$cont],$Keys)){
                $faltandatos = true;
                $ErrorGet[] = [
                    'error' => 'falta ' . $Requiered[$cont],
                ];
            }
        }
        if( count($ErrorGet) ){
            if(APP_DEBUG){
                abort(500);
            }else{
                $result = [
                    'codigo' => 400,
                    'data' => $ErrorGet,
                ];
                return ($result);
            }
        }
        return $ErrorGet;
    }
    public static function ArrayAString($Array){
        $tmpArr = array();
        foreach ($Array as $Fila) {
            $tmpArr[] = implode('|', $Fila);
        }
        $result = implode(';', $tmpArr);
        return $result;
    }
	/*
	function StringSize($str,$size,$modo,$Relleno,$LugarDeRelleno,$FinalDeLinea){
		$strT = mb_substr( str_pad($str,$size,$Relleno,$LugarDeRelleno),0,$size,$modo) . $FinalDeLinea ;
		return $strT;
	}
	*/
}