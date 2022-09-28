<?php     
    $attributes = array('id' => 'formEnviar');
    echo form_open(base_url(). 'tarifarioN/tarifario/store/', $attributes);
?>    
    <div class="col-xs-12">
        <div class="ibox-content">    	
            
            <div class="row">

                <div class="col-xs-12">
                    <div class="form-group">
                        <label for="cliente">Clientes</label>
                        <select name="cliente" id="cliente" class="form-control" style="width: 100%">
                            <option value="0">Seleccione</option>
                            <?php foreach($clientes as $c) { ?>
                                <option value="<?php echo $c->id ?>"><?php echo "$c->nombre ($c->nombre_fantasia)" ?></option>
                            <?php }?>
                        </select>
                    </div>
                </div>

                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="zona_origen">Zona origen</label>
                        <select name="zona_origen" id="zona_origen" class="form-control" style="width: 100%">
                            <option value="0">Seleccione</option>
                            <?php foreach($zonas as $z) { ?>
                                <option value="<?php echo $z->id ?>"><?php echo $z->nombre ?></option>
                            <?php }?>
                        </select>
                    </div>
                </div>

                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="zona_destino">Zona destino</label>
                        <select name="zona_destino" id="zona_destino" class="form-control" style="width: 100%">
                            <option value="0">Seleccione</option>
                            <?php foreach($zonas as $z) { ?>
                                <option value="<?php echo $z->id ?>"><?php echo $z->nombre ?></option>
                            <?php }?>
                        </select>
                    </div>
                </div>

                <div class="col-xs-12">
                    <div class="form-group">
                        <label for="plantilla">Plantillas</label>
                        <select name="plantilla" id="plantilla" class="form-control" style="width: 100%">
                            <option value="0">Seleccione</option>
                            <?php foreach($plantillas as $p) { ?>
                                <option value="<?php echo $p->id ?>"><?php echo $p->nombre ?></option>
                            <?php }?>
                        </select>
                    </div>
                </div>


            </div>

            <div class="row">
                <div class="col-xs-12" id="contenedor_plantilla">

                </div>
            </div>

    	</div>
    </div>



    <!-- Botones -->
    <div class="col-xs-12">
        <div class="ibox-content">
            <div class="control-group">
              <div class="controls">
                <?php echo form_button(array('type'  =>'button' ,'id' => 'btnGuardar', 'onclick' => 'guardar()' ,'value' =>'Guardar','class' =>'btn btn-success'), "<i class='fa fa-floppy-o'></i> Guardar"); ?> 
                <a class="btn btn-danger" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2); ?>"><i class="fa fa-arrow-circle-left"></i> Volver</a>
              </div>
            </div>
        </div>
    </div>
    <!-- end Botones -->

<script type="text/javascript">

$(document).ready(function() {
    //INICIALIZANDO LOS SELECT 
    $('#cliente').select2();
    $('#plantilla').select2();
    $('#zona_origen').select2();
    $('#zona_destino').select2();

    //DETECTANDO EL CAMBIO EN EL SELECT DE PLANTILLA
    $('#plantilla').change(function(event){
        let plantilla = $('#plantilla').val()

        if(plantilla != "0"){
            console.log("Plantilla: ", plantilla)
            getDetallePlantilla(plantilla)
        }

    })

})

let indiceKilos = 0;
let indiceBultos = 0;
let indiceMetroCubico = 0;
let indicePalets = 0;
let indiceGestionFlota = 0;
let indiceWareHouse = 0;
let indiceValorDeclarado = 0;
let indiceCobranza = 0;
let indicePesoAforado = 0;

let contenedorKilos = null
let contenedorBultos = null
let contenedorCobranza = document.getElementById('rangos_cobranza')
let contenedorValorDeclarado = document.getElementById('rangos_valor_declarado')
let contenedorMetroCubico = null
let contenedorPalets = null
let contenedorGestionFlota = null
let contenedorWarehouse = null
let contenedorPesoAforado = document.getElementById('rangos_peso_aforado')


let gestionFlotaSeleccionado = []
const gestionFlota = [
    {value: 1, disabled: false, name: 'Moto // Hora comun'},
    {value: 2, disabled: false, name: 'Moto // Hora especial'},
    {value: 3, disabled: false, name: 'Auto // Hora comun'},
    {value: 4, disabled: false, name: 'Auto // Hora especial'},
    {value: 5, disabled: false, name: 'Camioneta // Hora comun'},
    {value: 6, disabled: false, name: 'Camioneta // Hora especial'},
    {value: 7, disabled: false, name: 'Furgon // Hora comun'},
    {value: 8, disabled: false, name: 'Furgon // Hora especial'},
    {value: 9, disabled: false, name: 'Camión // Hora comun'},
    {value: 10, disabled: false, name: 'Camión // Hora especial'},
]

function getDetallePlantilla(id){
    const url = `<?php echo base_url() ?>tarifarioN/tarifario/getDetallePlantilla/${id}`

    fetch(url)
        .then(resp => resp.json())
        .then(data => {
            console.log(data)

            indiceKilos = data.kilos.length
            indiceBultos = data.bultos.length
            indiceMetroCubico = data.metro_cubico.length
            indicePalets = data.palets.length
            indiceGestionFlota = data.gestion_flota.length
            indiceWareHouse = data.warehouse.length
            
            indiceValorDeclarado = data.valor_declarado ? 1 : 0
            indiceCobranza = data.cobranza ? 1 : 0
            indicePesoAforado = data.peso_aforado ? 1 : 0

            document.getElementById('contenedor_plantilla').innerHTML = ''
            
            let html = ''

            if(data.plantilla.nombre){
                html += `
                <div class="col-xs-12">
                    <div class="form-group">
                        <label>
                            Plantilla: 
                        </label>
                        ${data.plantilla.nombre}  
                    </div> 
                </div> 
                `
            }

            if(data.plantilla.descripcion){
                html += `
                <div class="col-xs-12">
                    <div class="form-group">
                        <label>
                            Descripción:  
                        </label>
                        ${data.plantilla.descripcion} 
                    </div> 
                </div> 
                `
            }

            if(data.cobranza){
                html += `
                    <div class="col-xs-12">
                        <div class="ibox-content" id="rangos_cobranza" name="rangos_cobranza" style="${data.cobranza ? '' : 'display:none;' }">
                            <input type="hidden" name="cantidad_fila_cobranza" id="cantidad_fila_cobranza">
                            <div class="row">
                                <div class="col-xs-6">
                                    <label>Sección tarifas por cobranza</label><br><br>
                                </div>
                                <div class="col-xs-6 text-right">
                                    <a href="javascript:void(0);" class="btn btn-sm btn-info" onclick="crearFilaCobranza()">Agregar fila</a>
                                </div>
                            </div>
                            <div class="row campoDinamico_cobranza">
                                    <div class="col-xs-3">
                                        <div class="form-group">
                                            <label>Porcentaje</label>
                                            <input value="${data.cobranza.porcentaje}" placeholder="%" type="number" id="porcentaje_cobranza0" name="porcentaje_cobranza0" class="form-control filtro-group" step="0.01">
                                        </div>
                                    </div>
                                    <div class="col-xs-1">
                                        <div class="form-group">
                                            <br>
                                            <a id="btn_eliminar_cobranza_0" onclick="eliminarFilaCobranza(event)" href="javascript:void(0);" class="eliminar_fila_cobranza agregar_campos btn btn-danger btn-submit" title="Remove field">-</a>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                `
            }

            if(data.valor_declarado){
                html += `
                    <div class="col-xs-12">
                        <div class="ibox-content" id="rangos_valor_declarado" name="rangos_valor_declarado" style="${ data.valor_declarado ? '' : 'display:none;' }">
                            <input type="hidden" name="cantidad_fila_valor_declarado" id="cantidad_fila_valor_declarado">
                            <div class="row">
                                <div class="col-xs-6">
                                    <label>Sección tarifas por valor declarado</label><br><br>
                                </div>
                                <div class="col-xs-6 text-right">
                                    <a href="javascript:void(0);" class="btn btn-sm btn-info" onclick="crearFilaValorDeclarado()">Agregar fila</a>
                                </div>
                            </div>
                            <div class="row campoDinamico_valor_declarado">                                
                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label>Porcentaje</label>
                                        <input value="${data.valor_declarado.porcentaje}" placeholder="%" type="number" id="porcentaje_valor_declarado0" name="porcentaje_valor_declarado0" class="form-control filtro-group" step="0.01">
                                    </div>
                                </div>
                                <div class="col-xs-1">
                                    <div class="form-group">
                                        <br>
                                        <a id="btn_eliminar_valor_declarado_0" onclick="eliminarFilaValorDeclarado(event)" href="javascript:void(0);" class="eliminar_fila_valor_declarado agregar_campos btn btn-danger btn-submit" title="Remove field">-</a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                `
            }

            if(data.peso_aforado){
                html += `
                    <div class="col-xs-12">
                        <div class="ibox-content" id="rangos_peso_aforado" name="rangos_peso_aforado" style="${data.peso_aforado ? '' : 'display:none;'}">
                            <input type="hidden" name="cantidad_fila_peso_aforado" id="cantidad_fila_peso_aforado">
                            <div class="row">
                                <div class="col-xs-6">
                                    <label>Sección tarifas por peso aforado</label><br><br>
                                </div>
                                <div class="col-xs-6 text-right">
                                    <a href="javascript:void(0);" class="btn btn-sm btn-info" onclick="crearFilaPesoAforado()">Agregar fila</a>
                                </div>
                            </div>
                            <div class="row campoDinamico_peso_aforado">
                                    <div class="col-xs-3">
                                        <div class="form-group">
                                            <label>Valor</label>
                                            <input value="${data.peso_aforado.valor}" placeholder="Valor" type="number" id="valor_peso_aforado0" name="valor_peso_aforado0" class="form-control filtro-group" step="1">
                                        </div>
                                    </div>
                                    <div class="col-xs-1">
                                        <div class="form-group">
                                            <br>
                                            <a id="btn_eliminar_peso_aforado_0"" onclick="eliminarFilaPesoAforado(event)" href="javascript:void(0);" class="eliminar_fila_peso_aforado agregar_campos btn btn-danger btn-submit" title="Remove field">-</a>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                `
            }

            if(data.kilos.length > 0){
                html += `
                <div class="col-xs-12">
                    <div class="ibox-content" id="rangos_precios_kilos" name="rangos_precios_kilos" style="${data.kilos.length > 0 ? '' : 'display:none;' }">
                        <input type="hidden" name="cantidad_fila_kilos" id="cantidad_fila_kilos">
                        <div class="row">
                            <div class="col-xs-6">
                                <label>Sección tarifas por kilos</label><br><br>
                            </div>
                            <div class="col-xs-6 text-right">
                                <a href="javascript:void(0);" class="btn btn-sm btn-info" onclick="crearFilaPorKilos()">Agregar fila</a>
                            </div>
                        </div>
                    `

                    let indice = 1

                    for (let index = 0; index < data.kilos.length; index++) {

                        let element = data.kilos[index]
                     
                        html += `
                    <div class="row campoDinamico_kilos align-items-center">
                        
                        <div class="col-xs-3">
                            <div class="form-group">
                                <label>Desde</label>
                                <input id="rinicio_kilos${indice}"  
                                        value="${element.desde_cant_unid_kg}"
                                        ${indice > 1 ? 'readonly' : ''}
                                        placeholder="Ej.: 10" type="number" name="rinicio_kilos${indice}" 
                                        class="form-control filtro-group">
                            </div>
                        </div>
                                    
                        <div class="col-xs-3">
                            <div class="form-group">
                                <label>Hasta</label>
                                <input id="rfin_kilos${indice}" value="${element.hasta_cant_unid_kg}" onblur="validarMaximoKilos(${indice})" onkeyup="modificarHastaKilos(event, ${indice})" placeholder="Ej.: 20" type="number" name="rfin_kilos${indice}" class="form-control filtro-group">
                            </div>
                        </div>

                        <div class="col-xs-3">
                            <div class="form-group">
                                <label>Precio</label>
                                <input style="" value="${element.precio}" placeholder="$" type="number" id="precio_kilos${indice}" name="precio_kilos${indice}" class="form-control filtro-group" step="0.01">
                            </div>
                        </div>
                                    
                        <div class="col-xs-2">
                            <div class="form-group">
                                <label>Tipo</label>
                                <select id="opciones_tipo_kilos${indice}" name="opciones_tipo_kilos${indice}" onchange="" class="form-control filtro-group">
                                    <option value="0">Seleccione</option>
                                    <option value="1" ${element.bandera == 1 ? 'selected' : ''} >Bloque</option>
                                    <option value="2" ${element.bandera == 2 ? 'selected' : ''} >Unidad</option>
                                </select>
                            </div>
                        </div>
                        
                        <!--Boton - (para eliminar el campo) -->
                        <div class="col-xs-1">
                            <div class="form-group">
                                <br>
                                <a id="btn_eliminar_kilos_${indice}" 
                                    ${data.kilos.length > 1 && index != (data.kilos.length - 1) ? 'disabled' : ''}
                                    onclick="eliminarFilaKilos(event)" 
                                    href="javascript:void(0);" 
                                    class="eliminar_fila_kilos agregar_campos btn btn-danger btn-submit" 
                                    title="Remove field">-</a>
                            </div>
                        </div>
                    </div>
                    `
                    indice++
                        
                    }
                

                html += `
                    </div> 
                </div> 
                `
            }

            if(data.bultos.length > 0){
                html += `
                <div class="col-xs-12">
                    <div class="ibox-content" id="rangos_precios_bultos" name="rangos_precios_bultos" style="${data.bultos.length > 0 ? '' : 'display:none;' }">
                        <input type="hidden" name="cantidad_fila_bultos" id="cantidad_fila_bultos">
                        <div class="row">
                            <div class="col-xs-6">
                                <label>Sección tarifas por bultos</label><br><br>
                            </div>
                            <div class="col-xs-6 text-right">
                                <a href="javascript:void(0);" class="btn btn-sm btn-info" onclick="crearFilasPorBultos()">Agregar fila</a>
                            </div>
                        </div>
                    `
                    let indice = 1

                    for (let index = 0; index < data.bultos.length; index++) {

                    let element = data.bultos[index]

                    html += `
                    <div class="row campoDinamico_bultos">
							
							<div class="col-xs-3">
								<div class="form-group">
									<label>Desde</label>
									<input id="rinicio_bultos${indice}" 
                                    value="${element.desde_cant_unid_bultos}"
                                    ${indice > 1 ? 'readonly' : ''}
                                    placeholder="Ej.: 10" type="number" name="rinicio_bultos${indice}" 
                                    class="form-control filtro-group">
								</div>
							</div>
							<div class="col-xs-3">
								<div class="form-group">
									<label>Hasta</label>
									<input id="rfin_bultos${indice}" value="${element.hasta_cant_unid_bultos}" onblur="validarMaximoBultos(${indice})" onkeyup="modificarHastaBultos(event, ${indice})" placeholder="Ej.: 20" type="number" name="rfin_bultos${indice}" class="form-control filtro-group">
								</div>
							</div>
							<div class="col-xs-3">
								<div class="form-group">
									<label>Precio</label>
									<input style="" value="${element.precio}" placeholder="$" type="number" id="precio_bultos${indice}" name="precio_bultos${indice}" class="form-control filtro-group" step="0.01">
								</div>
							</div>
							<div class="col-xs-2">
								<div class="form-group">
									<label>Tipo</label>
									<select id="opciones_tipo_bultos${indice}" name="opciones_tipo_bultos${indice}" onchange="" class="form-control filtro-group">
										<option value="0">Seleccione</option>
										<option value="1" ${element.bandera_Corte_Bultos == 1 ? 'selected' : ''} >Bloque</option>
										<option value="2" ${element.bandera_Corte_Bultos == 2 ? 'selected' : ''} >Unidad</option>
									</select>
								</div>
							</div>
                            <!--Boton - (para eliminar el campo) -->
                            <div class="col-xs-1">
                                <div class="form-group">
                                    <br>
                                    <a id="btn_eliminar_bultos_${indice}" 
                                        ${data.bultos.length > 1 && index != (data.bultos.length - 1) ? 'disabled' : ''}
                                        onclick="eliminarFilaBultos(event)" 
                                        href="javascript:void(0);" 
                                        class="eliminar_fila_bultos agregar_campos btn btn-danger btn-submit" 
                                        title="Remove field">-</a>
                                </div>
                            </div>
						</div>
                    `
                    indice++

                    }
                                    
                html += `
                    </div> 
                </div> 
                `
            }

            if(data.metro_cubico.length > 0){
                html += `
                <div class="col-xs-12">
                    <div class="ibox-content" id="rangos_valor_metro_cubico" name="rangos_valor_metro_cubico" style="${data.metro_cubico.length > 0 ? '' : 'display:none;' }">
                    <input type="hidden" name="cantidad_fila_metro_cubico" id="cantidad_fila_metro_cubico">
                    <div class="row">
                        <div class="col-xs-6">
                            <label>Sección tarifas por metros cubicos</label><br><br>
                        </div>
                        <div class="col-xs-6 text-right">
                            <a href="javascript:void(0);" class="btn btn-sm btn-info" onclick="crearFilaMetroCubico()">Agregar fila</a>
                        </div>
                    </div>
                        `;

                        let indice = 1

                        for (let index = 0; index < data.metro_cubico.length; index++) {

                        let element = data.metro_cubico[index]

                        html += `
                        <div class="row campoDinamico_metro_cubico">

							<div class="col-xs-3">
								<div class="form-group">
									<label>Desde</label>
									<input id="rinicio_mc${indice}" 
                                    value="${element.desde_cantidad_metro_cubico}"
                                    ${indice > 1 ? 'readonly' : ''}
                                    placeholder="Ej.: 10" type="number" name="rinicio_mc${indice}" 
                                    class="form-control filtro-group">
								</div>
							</div>
							<div class="col-xs-3">
								<div class="form-group">
									<label>Hasta</label>
									<input id="rfin_mc${indice}" value="${element.hasta_cantidad_metro_cubico}" onblur="validarMaximoMetroCubico(${indice})" onkeyup="modificarHastaMetroCubico(event, ${indice})" placeholder="Ej.: 20" type="number" name="rfin_mc${indice}" class="form-control filtro-group">
								</div>
							</div>
							<div class="col-xs-3">
								<div class="form-group">
									<label>Precio</label>
									<input style="" value="${element.precio}" placeholder="$" type="number" id="precio_mc${indice}" name="precio_mc${indice}" class="form-control filtro-group" step="0.01">
								</div>
							</div>
							<div class="col-xs-2">
								<div class="form-group">
									<label>Tipo</label>
									<select id="opciones_tipo_mc${indice}" name="opciones_tipo_mc${indice}" onchange="" class="form-control filtro-group">
										<option value="0">Seleccione</option>
										<option value="1" ${element.bandera_bloque_unidad == 1 ? 'selected' : ''} >Bloque</option>
										<option value="2" ${element.bandera_bloque_unidad == 2 ? 'selected' : ''} >Unidad</option>
									</select>
								</div>
							</div>
                            <!--Boton - (para eliminar el campo) -->
                            <div class="col-xs-1">
                                <div class="form-group">
                                    <br>
                                    <a id="btn_eliminar_metro_cubico_${indice}" 
                                        ${data.metro_cubico.length > 1 && index != (data.metro_cubico.length - 1) ? 'disabled' : ''}
                                        onclick="eliminarFilaMetroCubico(event)" href="javascript:void(0);" 
                                        class="eliminar_fila_metro_cubico agregar_campos btn btn-danger btn-submit" 
                                        title="Remove field">-</a>
                                </div>
                            </div>
						</div>
                        `
                        indice++

                        }
                                    
                html += `
                    </div>
                </div> 
                `
            }

            if(data.palets.length > 0){
                html += `
                <div class="col-xs-12">
                    <div class="ibox-content" id="rangos_valor_palets" name="rangos_valor_palets" style="${data.palets.length > 0 ? '' : 'display:none;' }">
                        <input type="hidden" name="cantidad_fila_palets" id="cantidad_fila_palets">

                        <div class="row">
                            <div class="col-xs-6">
                                <label>Sección tarifas por palets</label><br><br>
                            </div>
                            <div class="col-xs-6 text-right">
                                <a href="javascript:void(0);" class="btn btn-sm btn-info" onclick="crearFilaPalets()">Agregar fila</a>
                            </div>
                        </div>
                `;

                let indice = 1

                for (let index = 0; index < data.palets.length; index++) {

                    let element = data.palets[index]

                    html += `
                    <div class="row campoDinamico_palets">

							<div class="col-xs-3">
								<div class="form-group">
									<label>Desde</label>
									<input id="rinicio_palets${indice}" 
                                    value="${element.desde_cantidad_palets}"
                                    ${indice > 1 ? 'readonly' : ''}
                                    placeholder="Ej.: 10" type="number" name="rinicio_palets${indice}" 
                                    class="form-control filtro-group">
								</div>
							</div>
							<div class="col-xs-3">
								<div class="form-group">
									<label>Hasta</label>
									<input id="rfin_palets${indice}" value="${element.hasta_cantidad_palets}" onblur="validarMaximoPalets(${indice})" onkeyup="modificarHastaPalets(event, ${indice})" placeholder="Ej.: 20" type="number" name="rfin_palets${indice}" class="form-control filtro-group">
								</div>
							</div>
							<div class="col-xs-3">
								<div class="form-group">
									<label>Precio</label>
									<input style="" value="${element.precio}" placeholder="$" type="number" id="precio_palets${indice}" name="precio_palets${indice}" class="form-control filtro-group" step="0.01">
								</div>
							</div>
							<div class="col-xs-2">
								<div class="form-group">
									<label>Tipo</label>
									<select id="opciones_tipo_palets${indice}" name="opciones_tipo_palets${indice}" onchange="" class="form-control filtro-group">
										<option value="0">Seleccione</option>
										<option value="1" ${element.bandera_palets == 1 ? 'selected' : ''} >Bloque</option>
										<option value="2" ${element.bandera_palets == 2 ? 'selected' : ''} >Unidad</option>
									</select>
								</div>
							</div>
                            <!--Boton - (para eliminar el campo) -->
                            <div class="col-xs-1">
                                <div class="form-group">
                                    <br>
                                    <a id="btn_eliminar_palets_${indice}" 
                                        ${data.palets.length > 1 && index != (data.palets.length - 1) ? 'disabled' : ''}
                                        onclick="eliminarFilaPalets(event)" 
                                        href="javascript:void(0);" 
                                        class="eliminar_fila_palets agregar_campos btn btn-danger btn-submit" 
                                        title="Remove field">-</a>
                                </div>
                            </div>
						</div>
                    `

                    indice++

                    }
   
                                    
                html += `
                    </div> 
                </div> 
                `
            }

            if(data.gestion_flota.length > 0){
                html += `
                <div class="col-xs-12">
                    <div class="ibox-content" id="rangos_valor_gestion_flota" name="rangos_valor_gestion_flota" style="${data.gestion_flota.length > 0 ? '' : 'display:none;' }">
                        <input type="hidden" name="cantidad_fila_gestion_flota" id="cantidad_fila_gestion_flota">

						<div class="row">
                            <div class="col-xs-6">
                                <label>Sección tarifas por gestión de flota</label><br><br>
                            </div>
                            <div class="col-xs-6 text-right">
                                <a href="javascript:void(0);" class="btn btn-sm btn-info" onclick="crearFilaGestionFlota()">Agregar fila</a>
                            </div>
                        </div>
                `;

                let indice = 1;

                for (let index = 0; index < data.gestion_flota.length; index++) {

                let element = data.gestion_flota[index]

                html += `
                    <div class="row campoDinamico_gestion_flota">

							<div class="col-xs-6">
								<div class="form-group" >
									<label>Vehiculo // Hora</label>
									<select id="opcion_horas_gf${indice}" name="opcion_horas_gf${indice}" onchange="" class="form-control filtro-group select-tipo-vehiculo">
										<option value="0">Seleccione</option>
                                        <option value="1"  ${element.tipo_vehiculo == 1 && element.tipo_hora == 1 ? 'selected' : ''}  >Moto // Hora Comun</option>
                                        <option value="2"  ${element.tipo_vehiculo == 1 && element.tipo_hora == 2 ? 'selected' : ''}  >Moto // Hora Especial</option>
                                        <option value="3"  ${element.tipo_vehiculo == 2 && element.tipo_hora == 1 ? 'selected' : ''}  >Auto // Hora Comun</option>
                                        <option value="4"  ${element.tipo_vehiculo == 2 && element.tipo_hora == 2 ? 'selected' : ''}  >Auto // Hora Especial</option>
                                        <option value="5"  ${element.tipo_vehiculo == 3 && element.tipo_hora == 1 ? 'selected' : ''}  >Camioneta // Hora Comun</option>
                                        <option value="6"  ${element.tipo_vehiculo == 3 && element.tipo_hora == 2 ? 'selected' : ''}  >Camioneta // Hora Especial</option>
                                        <option value="7"  ${element.tipo_vehiculo == 4 && element.tipo_hora == 1 ? 'selected' : ''}  >Furgon // Hora Comun</option>
                                        <option value="8"  ${element.tipo_vehiculo == 4 && element.tipo_hora == 2 ? 'selected' : ''}  >Furgon // Hora Especial</option>
                                        <option value="9"  ${element.tipo_vehiculo == 5 && element.tipo_hora == 1 ? 'selected' : ''}  >Camion // Hora Comun</option>
                                        <option value="10"  ${element.tipo_vehiculo == 5 && element.tipo_hora == 2 ? 'selected' : ''}  >Camion // Hora Especial</option>
									</select>
								</div>
							</div>

							<div class="col-xs-3">
								<div class="form-group">
									<label>Precio</label>
									<input placeholder="$" value="${element.precio}" type="number" id="precio_gf${indice}" name="precio_gf${indice}" class="form-control filtro-group" step="0.01">
								</div>
							</div>

                            <!--Boton - (para eliminar el campo) -->
                            <div class="col-xs-1">
                                <div class="form-group">
                                    <br>
                                    <a id="btn_eliminar_gestion_flota_${indice}" 
                                        ${data.gestion_flota.length > 1 && index != (data.gestion_flota.length - 1) ? 'disabled' : ''}
                                        onclick="eliminarFilaGestionFlota(event)" 
                                        href="javascript:void(0);" 
                                        class="eliminar_fila_gestion_flota agregar_campos btn btn-danger btn-submit" 
                                        title="Remove field">-</a>
                                </div>
                            </div>
						</div>
                `

                indice++

                }
                                    
                html += `
                    </div> 
                </div> 
                `
            }

            if(data.warehouse.length > 0){
                html += `
                <div class="col-xs-12">
                    <div class="ibox-content" id="rangos_valor_warehouse" name="rangos_valor_warehouse" style="${data.warehouse.length > 0 ? '' : 'display:none;' }">
                        <input type="hidden" name="cantidad_fila_warehouse" id="cantidad_fila_warehouse">

                        <div class="row">
                            <div class="col-xs-6">
                                <label>Sección tarifas por WareHouse</label><br><br>
                            </div>
                            <div class="col-xs-6 text-right">
                                <a href="javascript:void(0);" class="btn btn-sm btn-info" onclick="crearFilaWareHouse()">Agregar fila</a>
                            </div>
                        </div>
                    `;

                    let indice = 1

                    for (let index = 0; index < data.warehouse.length; index++) {

                    let element = data.warehouse[index]

                    html += `
                        <div class="row campoDinamico_warehouse">
                            
							<div class="col-xs-3">
								<div class="form-group">
									<label>Cantidad</label>
									<input id="rinicio_wh${indice}" 
                                    value="${element.desde}"
                                    ${indice > 1 ? 'readonly' : ''}
                                    placeholder="Ej.: 10" type="number" name="rinicio_wh${indice}" 
                                    class="form-control filtro-group">
								</div>
							</div>

                            <div class="col-xs-3">
                                <div class="form-group">
                                    <label>Hasta</label>
                                    <input id="rfin_wh${indice}" value="${element.hasta}" onblur="validarMaximoWareHouse(${indice})" onkeyup="modificarHastaWareHouse(event, ${indice})" placeholder="Ej.: 20" type="number" name="rfin_wh${indice}" class="form-control filtro-group">
                                </div>
                            </div>

							<div class="col-xs-3">
								<div class="form-group">
									<label>Precio</label>
									<input placeholder="$" value="${element.precio}" type="number" id="precio_wh${indice}" name="precio_wh${indice}" class="form-control filtro-group" step="0.01">
								</div>
							</div>
                            <!--Boton - (para eliminar el campo) -->
                            <div class="col-xs-1">
                                <div class="form-group">
                                    <br>
                                    <a id="btn_eliminar_wh_${indice}" 
                                        ${data.warehouse.length > 1 && index != (data.warehouse.length - 1 ) ? 'disabled' : ''}
                                        onclick="eliminarFilaWareHouse(event)" 
                                        href="javascript:void(0);" 
                                        class="eliminar_fila_wh agregar_campos btn btn-danger btn-submit" 
                                        title="Remove field">-</a>
                                </div>
                            </div>
                        </div>
                    `

                    indice++

                    }
                                    
                html += `
                    </div> 
                </div> 
                `
            }


            /*
            if(data.plantilla.nombre){
                html += `
                <div class="col-xs-12">
                    <div class="form-group">
                        <label>
                            Plantilla: 
                        </label>
                        ${data.plantilla.nombre}  
                    </div> 
                </div> 
                `
            }

            if(data.plantilla.descripcion){
                html += `
                <div class="col-xs-12">
                    <div class="form-group">
                        <label>
                            Descripción:  
                        </label>
                        ${data.plantilla.descripcion} 
                    </div> 
                </div> 
                `
            }

            if(data.cobranza){
                html += `
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label>Tarifa por cobranza</label>
                            <p>Porcentaje: <span name="porcentaje_cobranza0" id="porcentaje_cobranza0">${data.cobranza.porcentaje}</span></p>
                        </div>

                        <hr>
                    </div>
                `
            }

            if(data.valor_declarado){
                html += `
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label>Tarifa valor declarado</label>
                            <p>Porcentaje: <span name="porcentaje_valor_declarado0" id="porcentaje_valor_declarado0">${data.valor_declarado.porcentaje}</span></p>
                        </div>

                        <hr>
                    </div>
                `
            }

            if(data.peso_aforado){
                html += `
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label>Tarifa por peso aforado</label>
                            <p>Porcentaje: <span name="valor_peso_aforado0" id="valor_peso_aforado0">${data.peso_aforado.valor}</span></p>
                        </div>

                        <hr>
                    </div>
                `
            }

            if(data.kilos.length > 0){
                html += `
                <div class="col-xs-12">
                    <label>Tarifas por kilos</label>
                        <table class="table table-sm table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>Desde</th>
                                    <th>Hasta</th>
                                    <th>Precio</th>
                                    <th>Tipo</th>
                                </tr>
                            </thead>
                            <tbody>
                            `;

                data.kilos.forEach((element, index) => {
                    html +=  ` 
                        <tr>
                            <td name="rinicio_kilos${index}" id="rinicio_kilos${index}" >${element.desde_cant_unid_kg}</td>
                            <td name="rfin_kilos${index}" id="rfin_kilos${index}" >${element.hasta_cant_unid_kg}</td>
                            <td name="precio_kilos${index}" id="precio_kilos${index}" ">${element.precio}</td>
                            <td name="opciones_tipo_kilos${index}" id="opciones_tipo_kilos${index}" >${element.bandera == 1 ? 'Bloque' : 'Unidad'}</td>
                        </tr>
                    `
                });
                                    
                html += `
                            </tbody>
                        </table>

                        <hr>
                </div> 
                `
            }

            if(data.bultos.length > 0){
                html += `
                <div class="col-xs-12">
                    <label>Tarifas por Bultos</label>
                        <table class="table table-sm table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>Desde</th>
                                    <th>Hasta</th>
                                    <th>Precio</th>
                                    <th>Tipo</th>
                                </tr>
                            </thead>
                            <tbody>
                            `;

                data.bultos.forEach((element, index) => {
                    html +=  ` 
                        <tr>
                            <td name="rinicio_bultos${index}" id="rinicio_bultos${index}">${element.desde_cant_unid_bultos}</td>
                            <td name="rfin_bultos${index}" id="rfin_bultos${index}">${element.hasta_cant_unid_bultos}</td>
                            <td name="precio_bultos${index}" id="precio_bultos${index}">${element.precio}</td>
                            <td name="opciones_tipo_bultos${index}" id="opciones_tipo_bultos${index}">${element.bandera_Corte_Bultos == 1 ? 'Bloque' : 'Unidad'}</td>
                        </tr>
                    `
                });
                                    
                html += `
                            </tbody>
                        </table>

                        <hr>
                </div> 
                `
            }

            if(data.metro_cubico.length > 0){
                html += `
                <div class="col-xs-12">
                    <label>Tarifas por metro cubico</label>
                        <table class="table table-sm table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>Desde</th>
                                    <th>Hasta</th>
                                    <th>Precio</th>
                                    <th>Tipo</th>
                                </tr>
                            </thead>
                            <tbody>
                            `;

                data.metro_cubico.forEach((element, index) => {
                    html +=  ` 
                        <tr>
                            <td name="rinicio_mc${index}" id="rinicio_mc${index}" >${element.desde_cantidad_metro_cubico}</td>
                            <td name="rfin_mc${index}" id="rfin_mc${index}" >${element.hasta_cantidad_metro_cubico}</td>
                            <td name="precio_mc${index}" id="precio_mc${index}" >${element.precio}</td>
                            <td name="opciones_tipo_mc${index}" id="opciones_tipo_mc${index}" >${element.bandera_bloque_unidad == 1 ? 'Bloque' : 'Unidad'}</td>
                        </tr>
                    `
                });
                                    
                html += `
                            </tbody>
                        </table>

                        <hr>
                </div> 
                `
            }

            if(data.palets.length > 0){
                html += `
                <div class="col-xs-12">
                    <label>Tarifas por Palets</label>
                        <table class="table table-sm table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>Desde</th>
                                    <th>Hasta</th>
                                    <th>Precio</th>
                                    <th>Tipo</th>
                                </tr>
                            </thead>
                            <tbody>
                            `;

                data.palets.forEach((element, index) => {
                    html +=  ` 
                        <tr>
                            <td name="rinicio_palets${index}" id="rinicio_palets${index}" >${element.desde_cantidad_palets}</td>
                            <td name="rfin_palets${index}" id="rfin_palets${index}" >${element.hasta_cantidad_palets}</td>
                            <td name="precio_palets${index}" id="precio_palets${index}" >${element.precio}</td>
                            <td name="opciones_tipo_palets${index}" id="opciones_tipo_palets${index}" >${element.bandera_palets == 1 ? 'Bloque' : 'Unidad'}</td>
                        </tr>
                    `
                });
                                    
                html += `
                            </tbody>
                        </table>

                        <hr>
                </div> 
                `
            }

            if(data.gestion_flota.length > 0){
                html += `
                <div class="col-xs-12">
                    <label>Tarifas por gestión de flota</label>
                        <table class="table table-sm table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>Tipo</th>
                                    <th>Precio</th>
                                </tr>
                            </thead>
                            <tbody>
                            `;

                data.gestion_flota.forEach((element, index) => {
                    html +=  ` 
                        <tr>
                            <td name="opcion_horas_gf${index}" id="opcion_horas_gf${index}" > 
                                ${element.tipo_vehiculo == 1 && element.tipo_hora == 1 ? 'Moto // Hora Comun' : ''} 
                                ${element.tipo_vehiculo == 1 && element.tipo_hora == 2 ? 'Moto // Hora Especial' : ''} 
                                ${element.tipo_vehiculo == 2 && element.tipo_hora == 1 ? 'Auto // Hora Comun' : ''} 
                                ${element.tipo_vehiculo == 2 && element.tipo_hora == 2 ? 'Auto // Hora Especial' : ''} 
                                ${element.tipo_vehiculo == 3 && element.tipo_hora == 1 ? 'Camioneta // Hora Comun' : ''} 
                                ${element.tipo_vehiculo == 3 && element.tipo_hora == 2 ? 'Camioneta // Hora Especial' : ''} 
                                ${element.tipo_vehiculo == 4 && element.tipo_hora == 1 ? 'Furgon // Hora Comun' : ''} 
                                ${element.tipo_vehiculo == 4 && element.tipo_hora == 2 ? 'Furgon // Hora Especial' : ''} 
                                ${element.tipo_vehiculo == 5 && element.tipo_hora == 1 ? 'Camion // Hora Comun' : ''} 
                                ${element.tipo_vehiculo == 5 && element.tipo_hora == 2 ? 'Camion // Hora Especial' : ''} 
                            </td>
                            <td name="precio_gf${index}" id="precio_gf${index}" >${element.precio}</td>
                        </tr>
                    `
                });
                                    
                html += `
                            </tbody>
                        </table>

                        <hr>
                </div> 
                `
            }

            if(data.warehouse.length > 0){
                html += `
                <div class="col-xs-12">
                    <label>Tarifas por warehouse</label>
                        <table class="table table-sm table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>Desde</th>
                                    <th>Hasta</th>
                                    <th>Precio</th>
                                </tr>
                            </thead>
                            <tbody>
                            `;

                data.warehouse.forEach((element, index) => {
                    html +=  ` 
                        <tr>
                            <td name="rinicio_wh${index}" id="rinicio_wh${index}" >${element.desde}</td>
                            <td name="rfin_wh${index}" id="rfin_wh${index}" >${element.hasta}</td>
                            <td name="precio_wh${index}" id="precio_wh${index}" >${element.precio}</td>
                        </tr>
                    `
                });
                                    
                html += `
                            </tbody>
                        </table>

                        <hr>
                </div> 
                `
            }
            */


            document.getElementById('contenedor_plantilla').insertAdjacentHTML('beforeend', html);
            
        })
}


function getValorFilaAnteriorKilo(indice, id){
    indice = indice - 1;
    let value = document.getElementById(`${id}${indice}`).value

    return `${parseFloat(value)}.01`
}

function getValorFilaAnteriorBulto(indice, id){
    indice = indice - 1;
    let value = document.getElementById(`${id}${indice}`).value

    return `${parseInt(value) + 1}`
}

function getValorFilaAnteriorMetroCubico(indice, id){
    indice = indice - 1;
    let value = document.getElementById(`${id}${indice}`).value

    return `${parseFloat(value)}.01`
}

function getValorFilaAnteriorPalets(indice, id){
    indice = indice - 1;
    let value = document.getElementById(`${id}${indice}`).value

    return `${parseInt(value) + 1}`
}

function getValorFilaAnteriorWareHouse(indice, id){
    indice = indice - 1;
    let value = document.getElementById(`${id}${indice}`).value

    return `${parseInt(value) + 1}`
}

function eliminarFilaKilos(event){
    event.preventDefault();

    //ELIMINO LA FILA
    event.target.parentNode.parentNode.parentNode.remove()
    //DISMINUYO EL CONTADOR
    --indiceKilos;
    //TOMO EL BOTON QUE SE ENCUENTRA EN LA FILA SUPERIOR Y LO ACTIVO

    if(indiceKilos > 0){
        //SI EL INDICE ES MAYOR QUE CERO SIGNIFICA QUE HAY UN BOTON PARA ACTIVAR
        const btnAnterior = document.getElementById(`btn_eliminar_kilos_${indiceKilos}`)
        btnAnterior.removeAttribute('disabled');
    }else{
        //SI EL INDICE ES CERO OCULTO DIRECTAMENTE EL PANEL
        $('#rangos_precios_kilos').css('display', 'none')
    }
}

function eliminarFilaBultos(event){
    event.preventDefault();

    //ELIMINO LA FILA
    event.target.parentNode.parentNode.parentNode.remove()
    //DISMINUYO EL CONTADOR
    --indiceBultos;
    //TOMO EL BOTON QUE SE ENCUENTRA EN LA FILA SUPERIOR Y LO ACTIVO

    if(indiceBultos > 0){
        //SI EL INDICE ES MAYOR QUE CERO SIGNIFICA QUE HAY UN BOTON PARA ACTIVAR
        const btnAnterior = document.getElementById(`btn_eliminar_bultos_${indiceBultos}`)
        btnAnterior.removeAttribute('disabled');
    }else{
        //SI EL INDICE ES CERO OCULTO DIRECTAMENTE EL PANEL
        $('#rangos_precios_bultos').css('display', 'none')
    }
}

function eliminarFilaMetroCubico(event){
    event.preventDefault();

    //ELIMINO LA FILA
    event.target.parentNode.parentNode.parentNode.remove()
    //DISMINUYO EL CONTADOR
    --indiceMetroCubico;
    //TOMO EL BOTON QUE SE ENCUENTRA EN LA FILA SUPERIOR Y LO ACTIVO

    if(indiceMetroCubico > 0){
        //SI EL INDICE ES MAYOR QUE CERO SIGNIFICA QUE HAY UN BOTON PARA ACTIVAR
        const btnAnterior = document.getElementById(`btn_eliminar_metro_cubico_${indiceMetroCubico}`)
        btnAnterior.removeAttribute('disabled');
    }else{
        //SI EL INDICE ES CERO OCULTO DIRECTAMENTE EL PANEL
        $('#rangos_valor_metro_cubico').css('display', 'none')
    }
}

function eliminarFilaPalets(event){
    event.preventDefault();

    //ELIMINO LA FILA
    event.target.parentNode.parentNode.parentNode.remove()
    //DISMINUYO EL CONTADOR
    --indicePalets;
    //TOMO EL BOTON QUE SE ENCUENTRA EN LA FILA SUPERIOR Y LO ACTIVO

    if(indicePalets > 0){
        //SI EL INDICE ES MAYOR QUE CERO SIGNIFICA QUE HAY UN BOTON PARA ACTIVAR
        const btnAnterior = document.getElementById(`btn_eliminar_palets_${indicePalets}`)
        btnAnterior.removeAttribute('disabled');
    }else{
        //SI EL INDICE ES CERO OCULTO DIRECTAMENTE EL PANEL
        $('#rangos_valor_palets').css('display', 'none')
    }
}

function eliminarFilaWareHouse(event){
    event.preventDefault();

    //ELIMINO LA FILA
    event.target.parentNode.parentNode.parentNode.remove()
    //DISMINUYO EL CONTADOR
    --indiceWareHouse;
    //TOMO EL BOTON QUE SE ENCUENTRA EN LA FILA SUPERIOR Y LO ACTIVO

    if(indiceWareHouse > 0){
        //SI EL INDICE ES MAYOR QUE CERO SIGNIFICA QUE HAY UN BOTON PARA ACTIVAR
        const btnAnterior = document.getElementById(`btn_eliminar_wh_${indiceWareHouse}`)
        btnAnterior.removeAttribute('disabled');
    }else{
        //SI EL INDICE ES CERO OCULTO DIRECTAMENTE EL PANEL
        $('#rangos_valor_warehouse').css('display', 'none')
    }
}

function eliminarFilaCobranza(event){
    event.preventDefault();

    //ELIMINO LA FILA
    event.target.parentNode.parentNode.parentNode.remove()
    //DISMINUYO EL CONTADOR
    --indiceCobranza;

    $('#rangos_cobranza').css('display', 'none')
}

function eliminarFilaValorDeclarado(event){
    event.preventDefault();

    //ELIMINO LA FILA
    event.target.parentNode.parentNode.parentNode.remove()
    //DISMINUYO EL CONTADOR
    --indiceValorDeclarado;

    $('#rangos_valor_declarado').css('display', 'none')
}

function eliminarFilaGestionFlota(event){
    event.preventDefault();

    //ELIMINO LA FILA
    event.target.parentNode.parentNode.parentNode.remove()
    //DISMINUYO EL CONTADOR
    --indiceGestionFlota;
    //TOMO EL BOTON QUE SE ENCUENTRA EN LA FILA SUPERIOR Y LO ACTIVO

    if(indiceGestionFlota > 0){
        //SI EL INDICE ES MAYOR QUE CERO SIGNIFICA QUE HAY UN BOTON PARA ACTIVAR
        const btnAnterior = document.getElementById(`btn_eliminar_gestion_flota_${indiceGestionFlota}`)
        btnAnterior.removeAttribute('disabled');
    }else{
        //SI EL INDICE ES CERO OCULTO DIRECTAMENTE EL PANEL
        $('#rangos_valor_gestion_flota').css('display', 'none')
    }
}

function modificarHastaKilos(event, indice){
    const valor = event.target.value;
    
    if(valor != '' && valor > 0){
        
        let inputDesde = document.getElementById(`rinicio_kilos${indice + 1}`)
        let inputHasta = document.getElementById(`rfin_kilos${indice + 1}`)
        
        if(inputDesde){
            inputDesde.value = ''
            inputDesde.value = `${valor}.01`

            if(parseFloat(inputHasta.value) <= parseFloat(inputDesde.value)){
                alert("El campo Hasta debe ser mayor que el campo Desde")
                inputHasta.value = ''
                inputHasta.focus();
            }
        }

    }
}

function validarMaximoKilos(indice){
    let inputDesde = document.getElementById(`rinicio_kilos${indice}`)
    let inputHasta = document.getElementById(`rfin_kilos${indice}`)

    if(parseFloat(inputHasta.value) <= parseFloat(inputDesde.value) && inputHasta.value != ''){
        alert("El campo Hasta debe ser mayor que el campo Desde")
        inputHasta.value = ''
        inputHasta.focus();
    }
            
}

function crearFilaPorKilos(){
    let indiceAnterior = indiceKilos;
    let indice = ++indiceKilos;
    
    contenedorKilos = document.getElementById('rangos_precios_kilos')

    //PARA DESABILITAR LOS BOTONES DE ELIMINAR AL CREARSE UNO NUEVO
    if(indice > 1){

        let inputDesde = document.getElementById(`rinicio_kilos${indiceAnterior}`)
        let inputHasta = document.getElementById(`rfin_kilos${indiceAnterior}`)
        let inputPrecio = document.getElementById(`precio_kilos${indiceAnterior}`)
        let inputTipo = document.getElementById(`opciones_tipo_kilos${indiceAnterior}`)

        if(inputDesde.value != '' && inputHasta.value != '' && inputPrecio.value != '' && inputTipo.value != 0){

            if(parseFloat(inputHasta.value) <= parseFloat(inputDesde.value)){
                alert("El campo Hasta debe ser mayor que el campo Desde")
                inputHasta.value = ''
                inputHasta.focus()
                --indiceKilos;
                return;
            }
            
            const btns = document.getElementsByClassName('eliminar_fila_kilos')

            for (let item of btns) {
                item.setAttribute('disabled', true);
            }

        }else{
            alert('Debe completar todos los campos para continuar')
            --indiceKilos;
            return;
        }


    }

    let html = `
            <div class="row campoDinamico_kilos align-items-center">
				
                <div class="col-xs-3">
					<div class="form-group">
						<label>Desde</label>
						<input id="rinicio_kilos${indice}"  
                                value="${indice > 1 ? getValorFilaAnteriorKilo(indice, 'rfin_kilos') : ''}"
                                ${indice > 1 ? 'readonly' : ''} 
                                placeholder="Ej.: 10" type="number" name="rinicio_kilos${indice}" 
                                class="form-control filtro-group">
					</div>
				</div>
							
                <div class="col-xs-3">
					<div class="form-group">
						<label>Hasta</label>
						<input id="rfin_kilos${indice}" onblur="validarMaximoKilos(${indice})" onkeyup="modificarHastaKilos(event, ${indice})" placeholder="Ej.: 20" type="number" name="rfin_kilos${indice}" class="form-control filtro-group">
					</div>
				</div>

				<div class="col-xs-3">
					<div class="form-group">
						<label>Precio</label>
						<input style="" placeholder="$" type="number" id="precio_kilos${indice}" name="precio_kilos${indice}" class="form-control filtro-group" step="0.01">
					</div>
				</div>
							
                <div class="col-xs-2">
					<div class="form-group">
						<label>Tipo</label>
						<select id="opciones_tipo_kilos${indice}" name="opciones_tipo_kilos${indice}" onchange="" class="form-control filtro-group">
							<option value="0">Seleccione</option>
							<option value="1">Bloque</option>
							<option value="2">Unidad</option>
						</select>
					</div>
				</div>
				
                <!--Boton - (para eliminar el campo) -->
				<div class="col-xs-1">
					<div class="form-group">
						<br>
						<a id="btn_eliminar_kilos_${indice}" onclick="eliminarFilaKilos(event)" href="javascript:void(0);" class="eliminar_fila_kilos agregar_campos btn btn-danger btn-submit" title="Remove field">-</a>
					</div>
				</div>
			</div>
    `

    contenedorKilos.insertAdjacentHTML('beforeend', html);
}

function modificarHastaBultos(event, indice){
    const valor = event.target.value;
    
    if(valor != '' && valor > 0){
        
        let inputDesde = document.getElementById(`rinicio_bultos${indice + 1}`)
        let inputHasta = document.getElementById(`rfin_bultos${indice + 1}`)
        
        if(inputDesde){
            inputDesde.value = ''
            inputDesde.value = `${valor}`

            if(parseFloat(inputHasta.value) <= parseFloat(inputDesde.value)){
                alert("El campo Hasta debe ser mayor que el campo Desde")
                inputHasta.value = ''
                inputHasta.focus();
            }
        }

    }
}

function validarMaximoBultos(indice){
    let inputDesde = document.getElementById(`rinicio_bultos${indice}`)
    let inputHasta = document.getElementById(`rfin_bultos${indice}`)

    if(parseFloat(inputHasta.value) <= parseFloat(inputDesde.value) && inputHasta.value != ''){
        alert("El campo Hasta debe ser mayor que el campo Desde")
        inputHasta.value = ''
        inputHasta.focus();
    }
            
}

function crearFilasPorBultos(){
    let indiceAnterior = indiceBultos;
    let indice = ++indiceBultos;

    contenedorBultos = document.getElementById('rangos_precios_bultos')

    //PARA DESABILITAR LOS BOTONES DE ELIMINAR AL CREARSE UNO NUEVO
    if(indice > 1){

        let inputDesde = document.getElementById(`rinicio_bultos${indiceAnterior}`)
        let inputHasta = document.getElementById(`rfin_bultos${indiceAnterior}`)
        let inputPrecio = document.getElementById(`precio_bultos${indiceAnterior}`)
        let inputTipo = document.getElementById(`opciones_tipo_bultos${indiceAnterior}`)

        if(inputDesde.value != '' && inputHasta.value != '' && inputPrecio.value != '' && inputTipo.value != 0){

            if(parseInt(inputDesde.value) >= parseInt(inputHasta.value)){
                
                alert("El campo Hasta debe ser mayor que el campo Desde")
                inputHasta.value = ''
                inputHasta.focus()
                --indiceBultos;
                return;
            }
            
            const btns = document.getElementsByClassName('eliminar_fila_bultos')

            for (let item of btns) {
                item.setAttribute('disabled', true);
            }

        }else{
            alert('Debe completar todos los campos para continuar')
            --indiceBultos;
            return;
        }


    }

    let html = `
                        <div class="row campoDinamico_bultos">
							
							<div class="col-xs-3">
								<div class="form-group">
									<label>Desde</label>
									<input id="rinicio_bultos${indice}" 
                                    ${indice > 1 ? 'readonly' : ''} 
                                    value="${indice > 1 ? getValorFilaAnteriorBulto(indice, 'rfin_bultos') : ''}"
                                    placeholder="Ej.: 10" type="number" name="rinicio_bultos${indice}" 
                                    class="form-control filtro-group">
								</div>
							</div>
							<div class="col-xs-3">
								<div class="form-group">
									<label>Hasta</label>
									<input id="rfin_bultos${indice}" onblur="validarMaximoBultos(${indice})" onkeyup="modificarHastaBultos(event, ${indice})" placeholder="Ej.: 20" type="number" name="rfin_bultos${indice}" class="form-control filtro-group">
								</div>
							</div>
							<div class="col-xs-3">
								<div class="form-group">
									<label>Precio</label>
									<input style="" placeholder="$" type="number" id="precio_bultos${indice}" name="precio_bultos${indice}" class="form-control filtro-group" step="0.01">
								</div>
							</div>
							<div class="col-xs-2">
								<div class="form-group">
									<label>Tipo</label>
									<select id="opciones_tipo_bultos${indice}" name="opciones_tipo_bultos${indice}" onchange="" class="form-control filtro-group">
										<option value="0">Seleccione</option>
										<option value="1">Bloque</option>
										<option value="2">Unidad</option>
									</select>
								</div>
							</div>
                            <!--Boton - (para eliminar el campo) -->
                            <div class="col-xs-1">
                                <div class="form-group">
                                    <br>
                                    <a id="btn_eliminar_bultos_${indice}" onclick="eliminarFilaBultos(event)" href="javascript:void(0);" class="eliminar_fila_bultos agregar_campos btn btn-danger btn-submit" title="Remove field">-</a>
                                </div>
                            </div>
						</div>

    `

    contenedorBultos.insertAdjacentHTML('beforeend', html);
}

function modificarHastaMetroCubico(event, indice){
    const valor = event.target.value;
    
    if(valor != '' && valor > 0){
        
        let inputDesde = document.getElementById(`rinicio_mc${indice + 1}`)
        let inputHasta = document.getElementById(`rfin_mc${indice + 1}`)
        
        if(inputDesde){
            inputDesde.value = ''
            inputDesde.value = `${valor}.01`

            if(parseFloat(inputHasta.value) <= parseFloat(inputDesde.value)){
                alert("El campo Hasta debe ser mayor que el campo Desde")
                inputHasta.value = ''
                inputHasta.focus();
            }
        }

    }
}

function validarMaximoMetroCubico(indice){
    let inputDesde = document.getElementById(`rinicio_mc${indice}`)
    let inputHasta = document.getElementById(`rfin_mc${indice}`)

    if(parseFloat(inputHasta.value) <= parseFloat(inputDesde.value) && inputHasta.value != ''){
        alert("El campo Hasta debe ser mayor que el campo Desde")
        inputHasta.value = ''
        inputHasta.focus();
    }
            
}

function crearFilaMetroCubico(){
    let indiceAnterior = indiceMetroCubico;
    let indice = ++indiceMetroCubico;
    contenedorMetroCubico = document.getElementById('rangos_valor_metro_cubico')
    //PARA DESABILITAR LOS BOTONES DE ELIMINAR AL CREARSE UNO NUEVO
    if(indice > 1){

        let inputDesde = document.getElementById(`rinicio_mc${indiceAnterior}`)
        let inputHasta = document.getElementById(`rfin_mc${indiceAnterior}`)
        let inputPrecio = document.getElementById(`precio_mc${indiceAnterior}`)
        let inputTipo = document.getElementById(`opciones_tipo_mc${indiceAnterior}`)

        if(inputDesde.value != '' && inputHasta.value != '' && inputPrecio.value != '' && inputTipo.value != 0){

            if(parseFloat(inputHasta.value) <= parseFloat(inputDesde.value)){
                alert("El campo Hasta debe ser mayor que el campo Desde")
                inputHasta.value = ''
                inputHasta.focus()
                --indiceMetroCubico;
                return;
            }
            
            const btns = document.getElementsByClassName('eliminar_fila_metro_cubico')

            for (let item of btns) {
                item.setAttribute('disabled', true);
            }

        }else{
            alert('Debe completar todos los campos para continuar')
            --indiceMetroCubico;
            return;
        }


    }

    let html = `
                        <div class="row campoDinamico_metro_cubico">
							<div class="col-xs-3">
								<div class="form-group">
									<label>Desde</label>
									<input id="rinicio_mc${indice}" 
                                    ${indice > 1 ? 'readonly' : ''} 
                                    value="${indice > 1 ? getValorFilaAnteriorMetroCubico(indice, 'rfin_mc') : ''}"
                                    placeholder="Ej.: 10" type="number" name="rinicio_mc${indice}" 
                                    class="form-control filtro-group">
								</div>
							</div>
							<div class="col-xs-3">
								<div class="form-group">
									<label>Hasta</label>
									<input id="rfin_mc${indice}" onblur="validarMaximoMetroCubico(${indice})" onkeyup="modificarHastaMetroCubico(event, ${indice})" placeholder="Ej.: 20" type="number" name="rfin_mc${indice}" class="form-control filtro-group">
								</div>
							</div>
							<div class="col-xs-3">
								<div class="form-group">
									<label>Precio</label>
									<input style="" placeholder="$" type="number" id="precio_mc${indice}" name="precio_mc${indice}" class="form-control filtro-group" step="0.01">
								</div>
							</div>
							<div class="col-xs-2">
								<div class="form-group">
									<label>Tipo</label>
									<select id="opciones_tipo_mc${indice}" name="opciones_tipo_mc${indice}" onchange="" class="form-control filtro-group">
										<option value="0">Seleccione</option>
										<option value="1">Bloque</option>
										<option value="2">Unidad</option>
									</select>
								</div>
							</div>
                            <!--Boton - (para eliminar el campo) -->
                            <div class="col-xs-1">
                                <div class="form-group">
                                    <br>
                                    <a id="btn_eliminar_metro_cubico_${indice}" onclick="eliminarFilaMetroCubico(event)" href="javascript:void(0);" class="eliminar_fila_metro_cubico agregar_campos btn btn-danger btn-submit" title="Remove field">-</a>
                                </div>
                            </div>
						</div>

    `

    contenedorMetroCubico.insertAdjacentHTML('beforeend', html);
}

function modificarHastaPalets(event, indice){
    const valor = event.target.value;
    
    if(valor != '' && valor > 0){
        
        let inputDesde = document.getElementById(`rinicio_palets${indice + 1}`)
        let inputHasta = document.getElementById(`rfin_palets${indice + 1}`)
        
        if(inputDesde){
            inputDesde.value = ''
            inputDesde.value = `${valor}`

            if(parseFloat(inputHasta.value) <= parseFloat(inputDesde.value)){
                alert("El campo Hasta debe ser mayor que el campo Desde")
                inputHasta.value = ''
                inputHasta.focus();
            }
        }

    }
}

function validarMaximoPalets(indice){
    let inputDesde = document.getElementById(`rinicio_palets${indice}`)
    let inputHasta = document.getElementById(`rfin_palets${indice}`)

    if(parseFloat(inputHasta.value) <= parseFloat(inputDesde.value) && inputHasta.value != ''){
        alert("El campo Hasta debe ser mayor que el campo Desde")
        inputHasta.value = ''
        inputHasta.focus();
    }
            
}

function crearFilaPalets(){
    let indiceAnterior = indicePalets;
    let indice = ++indicePalets;
    contenedorPalets = document.getElementById('rangos_valor_palets')


    //PARA DESABILITAR LOS BOTONES DE ELIMINAR AL CREARSE UNO NUEVO
    if(indice > 1){

        let inputDesde = document.getElementById(`rinicio_palets${indiceAnterior}`)
        let inputHasta = document.getElementById(`rfin_palets${indiceAnterior}`)
        let inputPrecio = document.getElementById(`precio_palets${indiceAnterior}`)
        let inputTipo = document.getElementById(`opciones_tipo_palets${indiceAnterior}`)

        if(inputDesde.value != '' && inputHasta.value != '' && inputPrecio.value != '' && inputTipo.value != 0){

            if(parseFloat(inputHasta.value) <= parseFloat(inputDesde.value)){
                alert("El campo Hasta debe ser mayor que el campo Desde")
                inputHasta.value = ''
                inputHasta.focus()
                --indicePalets;
                return;
            }
            
            const btns = document.getElementsByClassName('eliminar_fila_palets')

            for (let item of btns) {
                item.setAttribute('disabled', true);
            }

        }else{
            alert('Debe completar todos los campos para continuar')
            --indicePalets;
            return;
        }


    }

    let html = `
                        <div class="row campoDinamico_palets">
							<div class="col-xs-3">
								<div class="form-group">
									<label>Desde</label>
									<input id="rinicio_palets${indice}" 
                                    ${indice > 1 ? 'readonly' : ''} 
                                    value="${indice > 1 ? getValorFilaAnteriorPalets(indice, 'rfin_palets') : ''}"
                                    placeholder="Ej.: 10" type="number" name="rinicio_palets${indice}" 
                                    class="form-control filtro-group">
								</div>
							</div>
							<div class="col-xs-3">
								<div class="form-group">
									<label>Hasta</label>
									<input id="rfin_palets${indice}" onblur="validarMaximoPalets(${indice})" onkeyup="modificarHastaPalets(event, ${indice})" placeholder="Ej.: 20" type="number" name="rfin_palets${indice}" class="form-control filtro-group">
								</div>
							</div>
							<div class="col-xs-3">
								<div class="form-group">
									<label>Precio</label>
									<input style="" placeholder="$" type="number" id="precio_palets${indice}" name="precio_palets${indice}" class="form-control filtro-group" step="0.01">
								</div>
							</div>
							<div class="col-xs-2">
								<div class="form-group">
									<label>Tipo</label>
									<select id="opciones_tipo_palets${indice}" name="opciones_tipo_palets${indice}" onchange="" class="form-control filtro-group">
										<option value="0">Seleccione</option>
										<option value="1">Bloque</option>
										<option value="2">Unidad</option>
									</select>
								</div>
							</div>
                            <!--Boton - (para eliminar el campo) -->
                            <div class="col-xs-1">
                                <div class="form-group">
                                    <br>
                                    <a id="btn_eliminar_palets_${indice}" onclick="eliminarFilaPalets(event)" href="javascript:void(0);" class="eliminar_fila_palets agregar_campos btn btn-danger btn-submit" title="Remove field">-</a>
                                </div>
                            </div>
						</div>

    `

    contenedorPalets.insertAdjacentHTML('beforeend', html);
}

function modificarHastaWareHouse(event, indice){
    const valor = event.target.value;
    
    if(valor != '' && valor > 0){
        
        let inputDesde = document.getElementById(`rinicio_wh${indice + 1}`)
        let inputHasta = document.getElementById(`rfin_wh${indice + 1}`)
        
        if(inputDesde){
            inputDesde.value = ''
            inputDesde.value = `${valor}`

            if(parseFloat(inputHasta.value) <= parseFloat(inputDesde.value)){
                alert("El campo Hasta debe ser mayor que el campo Desde")
                inputHasta.value = ''
                inputHasta.focus();
            }
        }

    }
}

function validarMaximoWareHouse(indice){
    let inputDesde = document.getElementById(`rinicio_wh${indice}`)
    let inputHasta = document.getElementById(`rfin_wh${indice}`)

    if(parseFloat(inputHasta.value) <= parseFloat(inputDesde.value) && inputHasta.value != ''){
        alert("El campo Hasta debe ser mayor que el campo Desde")
        inputHasta.value = ''
        inputHasta.focus();
    }
            
}

function crearFilaWareHouse(){
    let indiceAnterior = indiceWareHouse;
    let indice = ++indiceWareHouse;
    contenedorWarehouse = document.getElementById('rangos_valor_warehouse')

    //PARA DESABILITAR LOS BOTONES DE ELIMINAR AL CREARSE UNO NUEVO
    if(indice > 1){

        let inputDesde = document.getElementById(`rinicio_wh${indiceAnterior}`)
        let inputHasta = document.getElementById(`rfin_wh${indiceAnterior}`)
        let inputPrecio = document.getElementById(`precio_wh${indiceAnterior}`)

        if(inputDesde.value != '' && inputHasta.value != '' && inputPrecio.value != ''){

            if(parseFloat(inputHasta.value) <= parseFloat(inputDesde.value)){
                alert("El campo Hasta debe ser mayor que el campo Desde")
                inputHasta.value = ''
                inputHasta.focus()
                --indiceWareHouse;
                return;
            }
            
            const btns = document.getElementsByClassName('eliminar_fila_wh')

            for (let item of btns) {
                item.setAttribute('disabled', true);
            }

        }else{
            alert('Debe completar todos los campos para continuar')
            --indiceWareHouse;
            return;
        }


    }

    let html = `
                        <div class="row campoDinamico_warehouse">
                            
							<div class="col-xs-3">
								<div class="form-group">
									<label>Cantidad</label>
									<input id="rinicio_wh${indice}" 
                                    ${indice > 1 ? 'readonly' : ''} 
                                    value="${indice > 1 ? getValorFilaAnteriorWareHouse(indice, 'rfin_wh') : ''}"
                                    placeholder="Ej.: 10" type="number" name="rinicio_wh${indice}" 
                                    class="form-control filtro-group">
								</div>
							</div>

                            <div class="col-xs-3">
                                <div class="form-group">
                                    <label>Hasta</label>
                                    <input id="rfin_wh${indice}" onblur="validarMaximoWareHouse(${indice})" onkeyup="modificarHastaWareHouse(event, ${indice})" placeholder="Ej.: 20" type="number" name="rfin_wh${indice}" class="form-control filtro-group">
                                </div>
                            </div>

							<div class="col-xs-3">
								<div class="form-group">
									<label>Precio</label>
									<input placeholder="$" type="number" id="precio_wh${indice}" name="precio_wh${indice}" class="form-control filtro-group" step="0.01">
								</div>
							</div>
                            <!--Boton - (para eliminar el campo) -->
                            <div class="col-xs-1">
                                <div class="form-group">
                                    <br>
                                    <a id="btn_eliminar_wh_${indice}" onclick="eliminarFilaWareHouse(event)" href="javascript:void(0);" class="eliminar_fila_wh agregar_campos btn btn-danger btn-submit" title="Remove field">-</a>
                                </div>
                            </div>

						</div>


    `

    contenedorWarehouse.insertAdjacentHTML('beforeend', html);
}

function crearFilaCobranza(){
    let indiceAnterior = indiceCobranza;
    let indice = ++indiceCobranza;

    //PARA DESABILITAR LOS BOTONES DE ELIMINAR AL CREARSE UNO NUEVO
    if(indice > 1){
        alert("Solo se puede agregar una regla de cobranza")
        --indiceCobranza;
        return;
    }

    let html = `
                        <div class="row campoDinamico_cobranza">
							<div class="col-xs-3">
								<div class="form-group">
									<label>Porcentaje</label>
									<input style="" placeholder="%" type="number" id="porcentaje_cobranza${indice}" name="porcentaje_cobranza${indice}" class="form-control filtro-group" step="0.01">
								</div>
							</div>
                            <div class="col-xs-1">
                                <div class="form-group">
                                    <br>
                                    <a id="btn_eliminar_cobranza_${indice}" onclick="eliminarFilaCobranza(event)" href="javascript:void(0);" class="eliminar_fila_cobranza agregar_campos btn btn-danger btn-submit" title="Remove field">-</a>
                                </div>
                            </div>
						</div>

    `

    contenedorCobranza.insertAdjacentHTML('beforeend', html);
}

function crearFilaValorDeclarado(){
    let indiceAnterior = indiceValorDeclarado;
    let indice = ++indiceValorDeclarado;

    //PARA DESABILITAR LOS BOTONES DE ELIMINAR AL CREARSE UNO NUEVO
    if(indice > 1){
        alert("Solo se puede agregar una regla de valor declarado")
        --indiceValorDeclarado;
        return;
    }

    let html = `
                        <div class="row campoDinamico_valor_declarado">
							<div class="col-xs-3">
								<div class="form-group">
									<label>Porcentaje</label>
									<input style="" placeholder="%" type="number" id="porcentaje_valor_declarado${indice}" name="porcentaje_valor_declarado${indice}" class="form-control filtro-group" step="0.01">
								</div>
							</div>
                            <div class="col-xs-1">
                                <div class="form-group">
                                    <br>
                                    <a id="btn_eliminar_valor_declarado_${indice}" onclick="eliminarFilaValorDeclarado(event)" href="javascript:void(0);" class="eliminar_fila_valor_declarado agregar_campos btn btn-danger btn-submit" title="Remove field">-</a>
                                </div>
                            </div>
						</div>

    `

    contenedorValorDeclarado.insertAdjacentHTML('beforeend', html);
}

function crearFilaGestionFlota(){
    let indiceAnterior = indiceGestionFlota;
    let indice = ++indiceGestionFlota;
    contenedorGestionFlota = document.getElementById('rangos_valor_gestion_flota')

    //PARA DESABILITAR LOS BOTONES DE ELIMINAR AL CREARSE UNO NUEVO
    if(indice > 1){

        let inputPrecio = document.getElementById(`precio_gf${indiceAnterior}`).value
        let inputTipo = document.getElementById(`opcion_horas_gf${indiceAnterior}`).value

        if(inputPrecio != '' && inputTipo != 0){
            
            const btns = document.getElementsByClassName('eliminar_fila_gestion_flota')

            for (let item of btns) {
                item.setAttribute('disabled', true);
            }

        }else{
            alert('Debe completar todos los campos para continuar')
            --indiceGestionFlota;
            return;
        }


    }

    let selects = document.getElementsByClassName('select-tipo-vehiculo')
        
        for (let item of selects) {
            gestionFlotaSeleccionado.push(item.value)
        }

    let options = ''

    for (let index = 0; index < gestionFlotaSeleccionado.length; index++) {
        const element = gestionFlotaSeleccionado[index];

        for(let j = 0; j < gestionFlota.length; j++){
            if(gestionFlota[j].value == element){
                gestionFlota[j].disabled = true;
            }
        }

    }

    for(let i = 0; i < gestionFlota.length; i++){
        options += `<option style="${gestionFlota[i].disabled ? 'background: #bcb7b7;' : '' }" ${gestionFlota[i].disabled ? 'disabled' : ''} value="${gestionFlota[i].value}">${gestionFlota[i].name}</option>`
    }

    let html = `
                        <div class="row campoDinamico_gestion_flota">
							<div class="col-xs-6">
								<div class="form-group" >
									<label>Vehiculo // Hora</label>
									<select id="opcion_horas_gf${indice}" name="opcion_horas_gf${indice}" onchange="" class="form-control filtro-group select-tipo-vehiculo">
										<option value="0">Seleccione</option>
                                        ${options}
									</select>
                                    
								</div>
							</div>

							<div class="col-xs-3">
								<div class="form-group">
									<label>Precio</label>
									<input placeholder="$" type="number" id="precio_gf${indice}" name="precio_gf${indice}" class="form-control filtro-group" step="0.01">
								</div>
							</div>

                            <!--Boton - (para eliminar el campo) -->
                            <div class="col-xs-1">
                                <div class="form-group">
                                    <br>
                                    <a id="btn_eliminar_gestion_flota_${indice}" onclick="eliminarFilaGestionFlota(event)" href="javascript:void(0);" class="eliminar_fila_gestion_flota agregar_campos btn btn-danger btn-submit" title="Remove field">-</a>
                                </div>
                            </div>
						</div>

    `

    gestionFlotaSeleccionado = [];

    contenedorGestionFlota.insertAdjacentHTML('beforeend', html);
}

function eliminarFilaPesoAforado(event){
    event.preventDefault();

    //ELIMINO LA FILA
    event.target.parentNode.parentNode.parentNode.remove()
    //DISMINUYO EL CONTADOR
    --indicePesoAforado;

    $('#rangos_peso_aforado').css('display', 'none')
}

function crearFilaPesoAforado(){
    let indiceAnterior = indicePesoAforado;
    let indice = ++indicePesoAforado;

    //PARA DESABILITAR LOS BOTONES DE ELIMINAR AL CREARSE UNO NUEVO
    if(indice > 1){
        alert("Solo se puede agregar una regla de peso aforado")
        --indicePesoAforado;
        return;
    }

    let html = `
                        <div class="row campoDinamico_peso_aforado">
							<div class="col-xs-3">
								<div class="form-group">
									<label>Valor</label>
									<input style="" placeholder="Valor" type="number" id="valor_peso_aforado${indice}" name="valor_peso_aforado${indice}" class="form-control filtro-group" step="1">
								</div>
							</div>
                            <div class="col-xs-1">
                                <div class="form-group">
                                    <br>
                                    <a id="btn_eliminar_peso_aforado_${indice}" onclick="eliminarFilaPesoAforado(event)" href="javascript:void(0);" class="eliminar_fila_peso_aforado agregar_campos btn btn-danger btn-submit" title="Remove field">-</a>
                                </div>
                            </div>
						</div>

    `

    contenedorPesoAforado.insertAdjacentHTML('beforeend', html);
}


//$(document).ready(function() {

    function agregarTarifario() {
        let tipo = $('#opciones_tarifario').val()

        switch (tipo) {
            case '1':
                $('#rangos_precios_kilos').css('display', 'block')
                crearFilaPorKilos()

                $('html, body').animate({
                    scrollTop: $("#rangos_precios_kilos").offset().top
                }, 2000);

                break;

            case '2':
                $('#rangos_precios_bultos').css('display', 'block')
                crearFilasPorBultos();
                
                $('html, body').animate({
                    scrollTop: $("#rangos_precios_bultos").offset().top
                }, 2000);

                break;

            case '3':
                $('#rangos_cobranza').css('display', 'block')

                crearFilaCobranza();

                $('html, body').animate({
                    scrollTop: $("#rangos_cobranza").offset().top
                }, 2000);

                break;

            case '4':
                $('#rangos_valor_declarado').css('display', 'block')

                crearFilaValorDeclarado();

                $('html, body').animate({
                    scrollTop: $("#rangos_valor_declarado").offset().top
                }, 2000);

                break;
        
            case '5':
                $('#rangos_valor_metro_cubico').css('display', 'block')
                crearFilaMetroCubico();

                $('html, body').animate({
                    scrollTop: $("#rangos_valor_metro_cubico").offset().top
                }, 2000);

                break;

            case '6':
                $('#rangos_valor_palets').css('display', 'block')
                crearFilaPalets();

                $('html, body').animate({
                    scrollTop: $("#rangos_valor_palets").offset().top
                }, 2000);

                break;

            case '7':
                $('#rangos_valor_gestion_flota').css('display', 'block')

                crearFilaGestionFlota();

                $('html, body').animate({
                    scrollTop: $("#rangos_valor_palets").offset().top
                }, 2000);

                break;

            case '8':
                $('#rangos_valor_warehouse').css('display', 'block')
                crearFilaWareHouse();

                $('html, body').animate({
                    scrollTop: $("#rangos_valor_warehouse").offset().top
                }, 2000);


                break;

            case '9':

                $('#rangos_peso_aforado').css('display', 'block')
                crearFilaPesoAforado();

                $('html, body').animate({
                    scrollTop: $("#rangos_peso_aforado").offset().top
                }, 2000);

                break;


            default:
                console.log("Sale por el default")
                break;
        }
    }

let btnGuardar = document.getElementById('btnGuardar')

function guardar(event){
    //VALIDANDO CLIENTE
    const cliente = document.getElementById('cliente')
    if(cliente.value == '0' || cliente.value == ''){
        alert("El cliente es requerido")
        document.getElementById('cliente').focus();
        return;
    }

    //VALIDANDO ZONA ORIGEN
    const zona_origen = document.getElementById('zona_origen')
    if(zona_origen.value == '0' || zona_origen.value == ''){
        alert("La zona origen es requerida")
        document.getElementById('zona_origen').focus();
        return;
    }

    //VALIDANDO ZONA DESTINO
    const zona_destino = document.getElementById('zona_destino')
    if(zona_destino.value == '0' || zona_destino.value == ''){
        alert("La zona destino es requerida")
        document.getElementById('zona_destino').focus();
        return;
    }

    //VALIDANDO PLANTILLA
    const plantilla = document.getElementById('plantilla')
    if(plantilla.value == '0' || plantilla.value == ''){
        alert("La plantilla es requerida")
        document.getElementById('plantilla').focus();
        return;
    }

    let cantidadKilos = 0
    let cantidadBultos = 0
    let cantidadMetroCubico = 0
    let cantidadPalets = 0
    let cantidadGestionFlota = 0
    let cantidadWarehouse = 0
    let cantidadCobranza = 0
    let cantidadValorDeclarado = 0
    let cantidadPesoAforado = 0


    //campoDinamico_kilos
    if(document.getElementsByClassName('campoDinamico_kilos')){
        cantidadKilos = document.getElementsByClassName('campoDinamico_kilos').length

        if(document.getElementById('cantidad_fila_kilos')){
            document.getElementById('cantidad_fila_kilos').value = cantidadKilos
        }

    }

    if(document.getElementsByClassName('campoDinamico_bultos')){
        cantidadBultos = document.getElementsByClassName('campoDinamico_bultos').length

        if(document.getElementById('cantidad_fila_bultos')){
            document.getElementById('cantidad_fila_bultos').value = cantidadBultos
        }
    }

    if(document.getElementsByClassName('campoDinamico_metro_cubico')){
        cantidadMetroCubico = document.getElementsByClassName('campoDinamico_metro_cubico').length

        if(document.getElementById('cantidad_fila_metro_cubico')){
            document.getElementById('cantidad_fila_metro_cubico').value = cantidadMetroCubico
        }
    }

    if(document.getElementsByClassName('campoDinamico_palets')){
        cantidadPalets = document.getElementsByClassName('campoDinamico_palets').length

        if(document.getElementById('cantidad_fila_palets')){
            document.getElementById('cantidad_fila_palets').value = cantidadPalets
        }
    }

    if(document.getElementsByClassName('campoDinamico_gestion_flota')){
        cantidadGestionFlota = document.getElementsByClassName('campoDinamico_gestion_flota').length

        if(document.getElementById('cantidad_fila_gestion_flota')){
            document.getElementById('cantidad_fila_gestion_flota').value = cantidadGestionFlota
        }
    }

    if(document.getElementsByClassName('campoDinamico_warehouse')){
        cantidadWarehouse = document.getElementsByClassName('campoDinamico_warehouse').length

        if(document.getElementById('cantidad_fila_warehouse')){
            document.getElementById('cantidad_fila_warehouse').value = cantidadWarehouse
        }
    }

    if(document.getElementsByClassName('campoDinamico_cobranza')){
        cantidadCobranza = document.getElementsByClassName('campoDinamico_cobranza').length

        if(document.getElementById('cantidad_fila_cobranza')){
            document.getElementById('cantidad_fila_cobranza').value = cantidadCobranza
        }
    }

    if(document.getElementsByClassName('campoDinamico_valor_declarado')){
        cantidadValorDeclarado = document.getElementsByClassName('campoDinamico_valor_declarado').length
        
        if(document.getElementById('cantidad_fila_valor_declarado')){
            document.getElementById('cantidad_fila_valor_declarado').value = cantidadValorDeclarado
        }
    }

    if(document.getElementsByClassName('campoDinamico_peso_aforado')){
        cantidadPesoAforado = document.getElementsByClassName('campoDinamico_peso_aforado').length

        if(document.getElementById('cantidad_fila_peso_aforado')){
            document.getElementById('cantidad_fila_peso_aforado').value = cantidadPesoAforado
        }
    }

    
    //VALIDACIÓN PARA CAMPO COBRANZA
    if( cantidadCobranza > 0 ){

        if(document.getElementById('porcentaje_cobranza0').value == ""){
            alert("El porcentaje de cobranza es requerido")
            document.getElementById('porcentaje_cobranza0').focus();
            return;
        }

        if(document.getElementById('porcentaje_cobranza0').value <= 0){
            alert("El porcentaje de cobranza debe ser mayor a cero")
            document.getElementById('porcentaje_cobranza0').value = ''
            document.getElementById('porcentaje_cobranza0').focus();
            return;
        }

    }

    //VALIDACIÓN PARA CAMPO VALOR DECLARADO
    if( cantidadValorDeclarado > 0 ){

        if(document.getElementById('porcentaje_valor_declarado0').value == ""){
            alert("El porcentaje de valor declarado es requerido")
            document.getElementById('porcentaje_valor_declarado0').focus();
            return;
        }

        if(document.getElementById('porcentaje_valor_declarado0').value <= 0){
            alert("El porcentaje de valor declarado debe ser mayor a cero")
            document.getElementById('porcentaje_valor_declarado0').value = ''
            document.getElementById('porcentaje_valor_declarado0').focus();
            return;
        }

    }

    //VALIDACIÓN PARA CAMPO VALOR DECLARADO
    if( cantidadPesoAforado > 0 ){

        if(document.getElementById('valor_peso_aforado0').value == ""){
            alert("El valor de peso aforado es requerido")
            document.getElementById('valor_peso_aforado0').focus();
            return;
        }

        if(document.getElementById('valor_peso_aforado0').value <= 0){
            alert("El valor de peso aforado debe ser mayor a cero")
            document.getElementById('valor_peso_aforado0').value = ''
            document.getElementById('valor_peso_aforado0').focus();
            return;
        }

    }

    //VALIDACIÓN PARA CAMPO KILOS
    if( cantidadKilos > 0 ){

        for (let index = 1; index <= cantidadKilos; index++) {

            let inputDesde = document.getElementById(`rinicio_kilos${index}`)
            let inputHasta = document.getElementById(`rfin_kilos${index}`)
            let inputPrecio = document.getElementById(`precio_kilos${index}`)
            let inputTipo = document.getElementById(`opciones_tipo_kilos${index}`)

            if(inputDesde.value == ""){
                alert("El valor desde de tarifa kilos es requerido")
                inputDesde.value = "";
                inputDesde.focus();
                return;
            }

            if(inputDesde.value <= 0){
                alert("El valor desde de tarifa kilos debe ser mayor a cero")
                inputDesde.value = "";
                inputDesde.focus();
                return;
            }

            if(inputHasta.value == ""){
                alert("El valor hasta de tarifa kilos es requerido")
                inputHasta.value = "";
                inputHasta.focus();
                return;
            }

            if(inputHasta.value <= 0){
                alert("El valor hasta de tarifa kilos debe ser mayor a cero")
                inputHasta.value = "";
                inputHasta.focus();
                return;
            }

            if(inputPrecio.value == ""){
                alert("El precio de tarifa kilos es requerido")
                inputPrecio.value = "";
                inputPrecio.focus();
                return;
            }

            if(inputPrecio.value <= 0){
                alert("El precio de tarifa kilos debe ser mayor a cero")
                inputPrecio.value = "";
                inputPrecio.focus();
                return;
            }

            if(inputTipo.value == "" || inputTipo.value == "0"){
                alert("El tipo de tarifa kilos es requerido")
                inputTipo.focus();
                return;
            }

        }

    }

    //VALIDACIÓN PARA METROS CUBICOS
    if( cantidadMetroCubico > 0 ){

        for (let index = 1; index <= cantidadMetroCubico; index++) {

            let inputDesde = document.getElementById(`rinicio_mc${index}`)
            let inputHasta = document.getElementById(`rfin_mc${index}`)
            let inputPrecio = document.getElementById(`precio_mc${index}`)
            let inputTipo = document.getElementById(`opciones_tipo_mc${index}`)

            if(inputDesde.value == ""){
                alert("El valor desde de tarifa metro cubico es requerido")
                inputDesde.value = "";
                inputDesde.focus();
                return;
            }

            if(inputDesde.value <= 0){
                alert("El valor desde de tarifa metro cubico debe ser mayor a cero")
                inputDesde.value = "";
                inputDesde.focus();
                return;
            }

            if(inputHasta.value == ""){
                alert("El valor hasta de tarifa metro cubico es requerido")
                inputHasta.value = "";
                inputHasta.focus();
                return;
            }

            if(inputHasta.value <= 0){
                alert("El valor hasta de tarifa metro cubico debe ser mayor a cero")
                inputHasta.value = "";
                inputHasta.focus();
                return;
            }

            if(inputPrecio.value == ""){
                alert("El precio de tarifa metro cubico es requerido")
                inputPrecio.value = "";
                inputPrecio.focus();
                return;
            }

            if(inputPrecio.value <= 0){
                alert("El precio de tarifa metro cubico debe ser mayor a cero")
                inputPrecio.value = "";
                inputPrecio.focus();
                return;
            }

            if(inputTipo.value == "" || inputTipo.value == "0"){
                alert("El tipo de tarifa metro cubico es requerido")
                inputTipo.focus();
                return;
            }

        }

    }

    //VALIDACIÓN PARA CAMPO BULTOS
    if( cantidadBultos > 0 ){

        for (let index = 1; index <= cantidadBultos; index++) {

            let inputDesde = document.getElementById(`rinicio_bultos${index}`)
            let inputHasta = document.getElementById(`rfin_bultos${index}`)
            let inputPrecio = document.getElementById(`precio_bultos${index}`)
            let inputTipo = document.getElementById(`opciones_tipo_bultos${index}`)

            if(inputDesde.value == ""){
                alert("El valor desde de tarifa bultos es requerido")
                inputDesde.value = "";
                inputDesde.focus();
                return;
            }

            if(inputDesde.value <= 0){
                alert("El valor desde de tarifa bultos debe ser mayor a cero")
                inputDesde.value = "";
                inputDesde.focus();
                return;
            }

            if(inputDesde.value % 1 != 0){
                alert("El valor desde de tarifa bultos no puede ser decimal")
                inputDesde.value = "";
                inputDesde.focus();
                return;
            }

            if(inputHasta.value == ""){
                alert("El valor hasta de tarifa bultos es requerido")
                inputHasta.value = "";
                inputHasta.focus();
                return;
            }

            if(inputHasta.value <= 0){
                alert("El valor hasta de tarifa bultos debe ser mayor a cero")
                inputHasta.value = "";
                inputHasta.focus();
                return;
            }

            if(inputHasta.value % 1 != 0){
                alert("El valor hasta de tarifa bultos no puede ser decimal")
                inputHasta.value = "";
                inputHasta.focus();
                return;
            }

            if(inputPrecio.value == ""){
                alert("El precio de tarifa bultos es requerido")
                inputPrecio.value = "";
                inputPrecio.focus();
                return;
            }

            if(inputPrecio.value <= 0){
                alert("El precio de tarifa bultos debe ser mayor a cero")
                inputPrecio.value = "";
                inputPrecio.focus();
                return;
            }

            if(inputTipo.value == "" || inputTipo.value == "0"){
                alert("El tipo de tarifa bultos es requerido")
                inputTipo.focus();
                return;
            }

        }

    }

    //VALIDACIÓN PARA CAMPO PALETS
    if( cantidadPalets > 0 ){

        for (let index = 1; index <= cantidadPalets; index++) {

            let inputDesde = document.getElementById(`rinicio_palets${index}`)
            let inputHasta = document.getElementById(`rfin_palets${index}`)
            let inputPrecio = document.getElementById(`precio_palets${index}`)
            let inputTipo = document.getElementById(`opciones_tipo_palets${index}`)

            if(inputDesde.value == ""){
                alert("El valor desde de tarifa palets es requerido")
                inputDesde.value = "";
                inputDesde.focus();
                return;
            }

            if(inputDesde.value <= 0){
                alert("El valor desde de tarifa palets debe ser mayor a cero")
                inputDesde.value = "";
                inputDesde.focus();
                return;
            }

            if(inputDesde.value % 1 != 0){
                alert("El valor desde de tarifa palets no puede ser decimal")
                inputDesde.value = "";
                inputDesde.focus();
                return;
            }

            if(inputHasta.value == ""){
                alert("El valor hasta de tarifa palets es requerido")
                inputHasta.value = "";
                inputHasta.focus();
                return;
            }

            if(inputHasta.value <= 0){
                alert("El valor hasta de tarifa palets debe ser mayor a cero")
                inputHasta.value = "";
                inputHasta.focus();
                return;
            }

            if(inputHasta.value % 1 != 0){
                alert("El valor hasta de tarifa palets no puede ser decimal")
                inputHasta.value = "";
                inputHasta.focus();
                return;
            }

            if(inputPrecio.value == ""){
                alert("El precio de tarifa palets es requerido")
                inputPrecio.value = "";
                inputPrecio.focus();
                return;
            }

            if(inputPrecio.value <= 0){
                alert("El precio de tarifa palets debe ser mayor a cero")
                inputPrecio.value = "";
                inputPrecio.focus();
                return;
            }

            if(inputTipo.value == "" || inputTipo.value == "0"){
                alert("El tipo de tarifa palets es requerido")
                inputTipo.focus();
                return;
            }

        }

    }

    //VALIDACIÓN PARA CAMPO WAREHOUSE
    if( cantidadWarehouse > 0 ){

        for (let index = 1; index <= cantidadWarehouse; index++) {

            let inputDesde = document.getElementById(`rinicio_wh${index}`)
            let inputHasta = document.getElementById(`rfin_wh${index}`)
            let inputPrecio = document.getElementById(`precio_wh${index}`)

            if(inputDesde.value == ""){
                alert("El valor desde de tarifa warehouse es requerido")
                inputDesde.value = "";
                inputDesde.focus();
                return;
            }

            if(inputDesde.value <= 0){
                alert("El valor desde de tarifa warehouse debe ser mayor a cero")
                inputDesde.value = "";
                inputDesde.focus();
                return;
            }

            if(inputDesde.value % 1 != 0){
                alert("El valor desde de tarifa warehouse no puede ser decimal")
                inputDesde.value = "";
                inputDesde.focus();
                return;
            }

            if(inputHasta.value == ""){
                alert("El valor hasta de tarifa warehouse es requerido")
                inputHasta.value = "";
                inputHasta.focus();
                return;
            }

            if(inputHasta.value <= 0){
                alert("El valor hasta de tarifa warehouse debe ser mayor a cero")
                inputHasta.value = "";
                inputHasta.focus();
                return;
            }

            if(inputHasta.value % 1 != 0){
                alert("El valor hasta de tarifa warehouse no puede ser decimal")
                inputHasta.value = "";
                inputHasta.focus();
                return;
            }

            if(inputPrecio.value == ""){
                alert("El precio de tarifa warehouse es requerido")
                inputPrecio.value = "";
                inputPrecio.focus();
                return;
            }

            if(inputPrecio.value <= 0){
                alert("El precio de tarifa warehouse debe ser mayor a cero")
                inputPrecio.value = "";
                inputPrecio.focus();
                return;
            }


        }

    }

    //VALIDACIÓN PARA CAMPO GESTION FLOTA
    if( cantidadGestionFlota > 0 ){

        for (let index = 1; index <= cantidadGestionFlota; index++) {

            let inputPrecio = document.getElementById(`precio_gf${index}`)
            let inputTipo = document.getElementById(`opcion_horas_gf${index}`)

            if(inputTipo.value == "" || inputTipo.value == 0){
                alert("El vehiculo/hora de tarifa gestión de flota es requerido")
                inputTipo.focus();
                return;
            }

            if(inputPrecio.value == ""){
                alert("El precio de tarifa gestión de flota es requerido")
                inputPrecio.value = "";
                inputPrecio.focus();
                return;
            }

            if(inputPrecio.value <= 0){
                alert("El precio de tarifa gestión de flota debe ser mayor a cero")
                inputPrecio.value = "";
                inputPrecio.focus();
                return;
            }


        }

    }

    //document.forms["formEnviar"].submit();
    console.log("validando todos los campos....")
    document.forms["formEnviar"].submit();
}

</script>