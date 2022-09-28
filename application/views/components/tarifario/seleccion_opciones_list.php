<script type="text/javascript">
    <?php include 'listado.js'; ?>
</script>

<div class="col-xs-12">
    <div class="ibox-content">
  		<a id="enlace" href="<?php echo base_url().'tarifarioT/tarifario_seleccion_opciones' ?>" class="btn btn-success" style="position: relative; right: -830px;">Regresar</a>	
    <div class="form-group">
        <div class="row flex">
            <div class="col-xs-5 form-group">
                <label>Seleccione el tipo de opcion para mostrar los datos cargados...</label>
                <select id="opciones_tarifario" name="" class="form-control filtro-group" onchange="cargar_datos_opcion(0)">
                    <option value="0">...</option>
                    <?php if($opciones_configuradas[0]->tarifas_kg > 0){ ?><option value="1">Kilogramos</option> <?php } ?>
                    <?php if($opciones_configuradas[0]->tarifas_bultos > 0){ ?><option value="2">Bultos</option> <?php } ?>
                    <?php if($opciones_configuradas[0]->tarifas_cobranza > 0){ ?><option value="3">Cobranza (%)</option> <?php } ?>
                    <?php if($opciones_configuradas[0]->tarifas_valor_declarado > 0){ ?><option value="4">Valor Declarado</option> <?php } ?>
                    <?php if($opciones_configuradas[0]->tarifas_metro_cubico > 0){ ?><option value="5">Metro Cubico</option> <?php } ?>
                    <?php if($opciones_configuradas[0]->tarifas_palets > 0){ ?><option value="6">Palets</option> <?php } ?>
                    <?php if($opciones_configuradas[0]->tarifario_gflota > 0){ ?><option value="7">Gestion de Flota</option> <?php } ?>
                    <?php if($opciones_configuradas[0]->tarifario_warehouse > 0){ ?><option value="8">WareHouse</option> <?php } ?>
                </select>
            </div>
        </div>
    </div>
  	</div>	

    <br><br>
    <div id="seccion_tabla" class="ibox-content" style="display: none">
        <div id="tabla" class="ibox-content" style="display: none;"></div>
    </div>

</div>

<script type="text/javascript">
    var zonas = <?php echo(json_encode($zonas));?>;
    var opciones = <?php echo(json_encode($opciones_configuradas));?>;
    var cliente_id = <?php echo(json_encode($id_cliente));?>;
   // console.log(opciones[0].tarifario_gflota);

    var bandera_edicion = <?php echo(json_encode($bandera_edicion));?>;
    var bandera_opcion_tipo_tarifario = parseInt(<?php echo(json_encode($bandera_opcion_tarifario));?>);
    
    if(bandera_edicion != 'NULL'){
        bandera_edicion = parseInt(bandera_edicion);

        if(bandera_edicion){
            alert('Resultado:\n Edicion Exitosa de Tarifa');
        }
        else{
            alert('Resultado:\n Ocurrio un error, la Tarifa no se edito correctamente.');   
        }
       cargar_datos_opcion(bandera_opcion_tipo_tarifario);
    }   
</script>