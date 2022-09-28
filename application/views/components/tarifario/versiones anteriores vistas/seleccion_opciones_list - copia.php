<div class="col-xs-12">
    <div class="ibox-content">
  		<a id="enlace" href="<?php echo base_url().'tarifarioT/tarifario_seleccion_opciones' ?>" class="btn btn-success" style="position: relative; right: -830px;">Regresar</a>	
  	</div>	
    <div class="form-group">
        <label>Seleccione el tipo de opcion para mostrar los datos cargados...</label>

<?php
    $kg = false;
    $bultos = false;
    $distancia = false;
    $cobranza = false;
    $metros = false;
    $palets = false;
    $gestion_flota = false;
    $wareHouse = false;
    $p_and_p = false;
    $extra = false;

    foreach ($lista_tarifas_kg as $result){ 
        $bandera_valores = $result->id;

        if(!is_null($bandera_valores) && !$kg){
            $kg = true;
        }        
    }

    foreach ($lista_tarifas_bultos as $result){ 
        $bandera_valores = $result->id;

        if(!is_null($bandera_valores) && !$bultos){
            $bultos = true;
        }
    }

    foreach ($lista_tarifas_palets as $result){ 
        $bandera_valores = $result->id;

        if(!is_null($bandera_valores) && !$palet){
            $palet = true;
        }        
    }

?>
        <select id="opciones_tarifario" name="" class="form-control filtro-group" onchange="mostrarTabla()">
            <option value="0">...</option>
            <?php if($kg){ ?><option value="1">Kilogramos</option> <?php } ?>
            <?php if($bultos){ ?> <option value="2">Bultos (unidades)</option> <?php } ?>
            <?php if($palet){ ?><option value="6">Palets</option> <?php } ?>

<!--
            
            <option value="1">Kilos (Kg)</option>
            
            <option value="3">Distancia (Km)</option>
            <option value="4">Cobranza (%)</option>
            <option value="5">Metros³</option>
            <option value="6">Palets</option>
            <option value="7">Gestión de Flota</option>
            <option value="8">WareHouse</option>
            <option value="9">Pick&Pack - Finish</option>
            <option value="10">Extra</option>
        -->
        </select>
<?php

?>        
    </div><br><br>


    <div id="tabla_Kg" class="ibox-content" style="display: none;">     
        <table id="results" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
            <thead>
                <tr>
                    <th><a href="#">Zona A</a></th>
                    <th><a href="#">Zona B</a></th>
                    <th><a href="#">Desde</a></th>
                    <th><a href="#">Hasta</a></th>
                    <th><a href="#">Precio</a></th>
                    <th><a href="#">Tipo</a></th>
                    <th class="col-xs-2">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
<?php 
                    foreach ($lista_tarifas_kg as $result) { 
                        if(!is_null($result->id)){
?>
                  <tr>
                    <td>
<?php   
                    foreach ($zonas as $zona) {
                       if($zona->id_provincia_bandera == $result->zonaA){
                            echo $zona->provincia . ' ('. $zona->ubicacion . ')';
                        }
                    }                   
?>
                    </td>
                    <td>
<?php 
                    foreach ($zonas as $zona) {
                       if($zona->id_provincia_bandera == $result->zonaB){
                            echo $zona->provincia . ' ('. $zona->ubicacion . ')';
                        }
                    }  
?>                        
                    </td>
                    <td><?php echo $result->desde_cant_unid_kg ?></td>
                    <td><?php echo $result->hasta_cant_unid_kg ?></td>
                    <td><?php echo $result->precio_Corte_kg ?></td>
                    <td><?php
                        $bandera_value_tipo = $result->bandera_Corte_kg;
                        if($bandera_value_tipo == 1){
                            echo 'Unidad';  
                        }
                        else{
                            if($bandera_value_tipo == 2){
                                echo 'Corte Minimo';
                            }
                            else{
                                echo 'Corte Comun';
                            }
                        }
                         
                    ?></td>
                    <td>
                        <div class="btn-group">
                   
                        <?php if($permisos_efectivos->update==1) { ?><a href="<?php echo base_url().'tarifarioT/tarifario_seleccion_opciones/edit/'.$result->paqueteria_tarifa ?>" class="btn btn-success"><i class="fa fa-edit"></i></a><?php } ?>

                            <?php if($permisos_efectivos->delete==1) { ?><a onClick="eleminarRegistro('<?php echo base_url().'tarifarioT/tarifario_seleccion_opciones/delete/'.$result->paqueteria_tarifa ?>')" href="#" class="btn btn-danger"><i class="fa fa-trash-o"></i></a><?php } ?>
                        </div>
                    </td>
                  </tr>

<?php    
                        }
                    } 
?>
            </tbody>            
        </table>    
    </div>

    <div id="tabla_bultos" class="ibox-content" style="display: none;">     
        <table id="results" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
            <thead>
                <tr>
                    <th><a href="#">Zona A</a></th>
                    <th><a href="#">Zona B</a></th>
                    <th><a href="#">Desde</a></th>
                    <th><a href="#">Hasta</a></th>
                    <th><a href="#">Precio</a></th>
                    <th><a href="#">Tipo</a></th>
                    <th class="col-xs-2">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
<?php 
                    foreach ($lista_tarifas_bultos as $result) { 
                        if(!is_null($result->id)){
?>
                  <tr>
                    <td>
<?php   
                    foreach ($zonas as $zona) {
                       if($zona->id_provincia_bandera == $result->zonaA){
                            echo $zona->provincia . ' ('. $zona->ubicacion . ')';
                        }
                    }                   
?>
                    </td>
                    <td>
<?php 
                    foreach ($zonas as $zona) {
                       if($zona->id_provincia_bandera == $result->zonaB){
                            echo $zona->provincia . ' ('. $zona->ubicacion . ')';
                        }
                    }  
?>                        
                    </td>
                    <td><?php echo $result->desde_cant_unid_bultos ?></td>
                    <td><?php echo $result->hasta_cant_unid_bultos ?></td>
                    <td><?php echo $result->precio_Corte_bultos ?></td>
                    <td><?php
                        $bandera_value_tipo = $result->bandera_Corte_Bultos;
                        if($bandera_value_tipo == 1){
                            echo 'Unidad';  
                        }
                        else{
                            if($bandera_value_tipo == 2){
                                echo 'Corte Minimo';
                            }
                            else{
                                echo 'Corte Comun';
                            }
                        }
                         
                    ?></td>
                    <td>
                        <div class="btn-group">
                   
                        <?php if($permisos_efectivos->update==1) { ?><a href="<?php echo base_url().'tarifarioT/tarifario_seleccion_opciones/edit/'.$result->paqueteria_tarifa ?>" class="btn btn-success"><i class="fa fa-edit"></i></a><?php } ?>

                            <?php if($permisos_efectivos->delete==1) { ?><a onClick="eleminarRegistro('<?php echo base_url().'tarifarioT/tarifario_seleccion_opciones/delete/'.$result->paqueteria_tarifa ?>')" href="#" class="btn btn-danger"><i class="fa fa-trash-o"></i></a><?php } ?>
                        </div>
                    </td>
                  </tr>

<?php    
                        }
                    } 
?>
            </tbody>            
        </table>    
    </div>

    <div id="tabla_palets" class="ibox-content" style="display: none;">     
        <table id="results" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
            <thead>
                <tr>
                    <th><a href="#">Zona A</a></th>
                    <th><a href="#">Zona B</a></th>
                    <th><a href="#">Cantidad</a></th>
                    <th><a href="#">Precio</a></th>
                    <th class="col-xs-2">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
<?php 
                    foreach ($lista_tarifas_palets as $result) { 
                        if(!is_null($result->id)){
?>
                  <tr>
                    <td>
<?php   
                    foreach ($zonas as $zona) {
                       if($zona->id_provincia_bandera == $result->zonaA){
                            echo $zona->provincia . ' ('. $zona->ubicacion . ')';
                        }
                    }                   
?>
                    </td>
                    <td>
<?php 
                    foreach ($zonas as $zona) {
                       if($zona->id_provincia_bandera == $result->zonaB){
                            echo $zona->provincia . ' ('. $zona->ubicacion . ')';
                        }
                    }  
?>                        
                    </td>
                    <td><?php echo $result->cantidad_palet ?></td>
                    <td><?php echo $result->precio_palet ?></td>
                    <td>
                        <div class="btn-group">
                   
                        <?php if($permisos_efectivos->update==1) { ?><a href="<?php echo base_url().'tarifarioT/tarifario_seleccion_opciones/edit/'.$result->paqueteria_tarifa ?>" class="btn btn-success"><i class="fa fa-edit"></i></a><?php } ?>


                            <?php if($permisos_efectivos->delete==1) { ?><a onClick="eleminarRegistro('<?php echo base_url().'tarifarioT/tarifario_seleccion_opciones/delete/'.$result->paqueteria_tarifa ?>')" href="#" class="btn btn-danger"><i class="fa fa-trash-o"></i></a><?php } ?>
                        </div>
                    </td>
                  </tr>

<?php    
                        }
                    } 
?>
            </tbody>            
        </table>    
    </div> 
  

</div>	


<script type="text/javascript">

    var zonas = <?php echo(json_encode($zonas));?>;




    function mostrarTabla(){
        deshabilitarTablas();

        var opcionElegidaTarifario = document.getElementById("opciones_tarifario").value;

        switch(opcionElegidaTarifario){
            case '1': 
                tabla_Kg.style.display = "block";            
                break
            case '2':
                tabla_bultos.style.display = "block";
                break
            case '3':

                break;
            case '6':
                tabla_palets.style.display = "block";
                break;
            default:
            //code block
            break;
        }
    }

    function deshabilitarTablas(){
        tabla_bultos.style.display = "none";
        tabla_palets.style.display = "none";
        tabla_Kg.style.display = "none";
    }

</script>