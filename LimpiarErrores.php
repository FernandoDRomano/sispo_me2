<?php
function getDirContents($dir, &$results = array()) {
    $files = scandir($dir);
    $OldText="Deny from all";
    $NewText="allow from all";
    foreach ($files as $key => $value) {
        $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
        if (!is_dir($path)) {
            if($value == ".htaccess"){
                
                unlink($path);
                /*
                if(is_writeable($path)){
                    $FileContent = file_get_contents($path);
                    $results[] = "<p>" .$FileContent. "</p>";
                    $FileContent = str_replace($OldText, $NewText, $FileContent);
                    $results[] = "<p>" .$FileContent. "</p>";
                    
                    $gestor = fopen($path, "x+");
                    //fwrite($gestor, '$FileContent');
                    
                    
                    //$gestor = fopen(realpath($dir . DIRECTORY_SEPARATOR . '.' . $value), "x+");
                    if(fwrite($gestor, '$FileContent')){
                        fclose($fh);
                        $results[] = $path . "\n" . $FileContent. "\n";
                    }else{
                        $results[] = "<p>" .$path . " | no se Pudo Editar a | " . $FileContent. "\n". "</p>";
                    }
                    
                }else{
                        $results[] = "<p>" .$path . " | no se Pudo Editar" . "</p>";
                } 
                */
                /*
                $FileContent = file_get_contents($path);
                $FileContent2 = str_replace($OldText, $NewText, $FileContent);
                $gestor = fopen(realpath($dir . DIRECTORY_SEPARATOR . '1' . $value), "w");
                
                if(file_put_contents('.'.$gestor, $FileContent2) > 0){
                    $results[] = $path . "<p>" . $FileContent2 . "</p>";
                    //$Result["status"] = 'success';
                }
                $results[] = "<p>" . $value . "</p>" . "<p>" . $FileContent . "</p>" . "<p>" . $FileContent2 . "</p>";
                */
            }else{
            }
            //$results[] = $path . "\n" . $FileContent;
        } else if ($value != "." && $value != "..") {
            getDirContents($path, $results);
            //$results[] = $path;
        }
    }

    return $results;
}

var_dump(getDirContents('../noborrar_en_public_html/zonificacion'));