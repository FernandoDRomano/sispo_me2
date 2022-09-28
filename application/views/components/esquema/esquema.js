/**
 * ESQUEMA
 */

//SALTA ERROR EN EL EDITOR, PERO SI FUNCIONA
let zonasCompletas = <?php echo json_encode($zonas);?>;

 function agregarSubEsquema(event){
    subEsquema++;

    esquema[`sub_esquema_${subEsquema}`] = {
        indiceKilos: 0,
        indiceBultos: 0,
        indiceMetroCubico: 0,
        indicePalets: 0,
        indiceGestionFlota: 0,
        indiceWareHouse: 0,
        indiceValorDeclarado: 0,
        indiceCobranza: 0,
        indicePesoAforado: 0,
        indiceDistancia: 0
    }

    let html = `
                    <div class="row sub-esquema" id="sub_esquema_${subEsquema}">
                        <div class="cabecera">
                            <a href="#" onclick="minimizarEsquema(event)" class="btn btn-sm btn-warning text-white">Minimizar</a>
                            <a href="#" onclick="eliminarEsquema(event)" class="btn btn-sm btn-danger text-white">Eliminar</a>
                        </div>

                        <div class="cuerpo">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="zona_origen_${subEsquema}">Zona origen</label>
                                    <select name="zona_origen_${subEsquema}" id="zona_origen_${subEsquema}" class="form-control select-zonas zona-origen" style="width: 100%">
                                        <option value="0">Seleccione</option>
                                        <?php foreach($zonas as $z) { ?>
                                            <option value="<?php echo $z->id ?>"><?php echo $z->nombre ?></option>
                                        <?php }?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="zona_destino_${subEsquema}">Zona destino</label>
                                    <select name="zona_destino_${subEsquema}" id="zona_destino_${subEsquema}" class="form-control select-zonas zona-destino" style="width: 100%">
                                        <option value="0">Seleccione</option>
                                        <?php foreach($zonas as $z) { ?>
                                            <option value="<?php echo $z->id ?>"><?php echo $z->nombre ?></option>
                                        <?php }?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label for="plantilla_${subEsquema}">Plantillas</label>
                                    <select onchange="getPlantilla(event,${subEsquema})" name="plantilla_${subEsquema}" id="plantilla_${subEsquema}" class="form-control select-plantilla" style="width: 100%">
                                        <option value="0">Seleccione</option>
                                        <?php foreach($plantillas as $p) { ?>
                                            <option value="<?php echo $p->id ?>"><?php echo $p->nombre ?></option>
                                        <?php }?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-xs-12" id="contenedor_plantilla_${subEsquema}">
                               
                            </div>

                        </div>

                    </div>
    `;

    contenedor_esquema.insertAdjacentHTML("beforeend", html)
}

function eliminarEsquema(event){
    event.preventDefault()

    event.target.parentNode.parentNode.remove()
}

function minimizarEsquema(event){
    event.preventDefault()

    event.target.parentNode.nextSibling.nextSibling.lastChild.previousSibling.classList.toggle("d-none")
    
    if(event.target.text == "Minimizar"){
        event.target.text = "Maximizar"
    }else{
        event.target.text = "Minimizar"
    }

}
