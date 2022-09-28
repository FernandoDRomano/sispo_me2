<link href="https://prueba.sispo.com.ar/assets/backend/css/estilos.css" rel="stylesheet">

<?php     
    $attributes = array('id' => 'formEnviar');
    echo form_open(base_url(). 'esquema/esquema/update/' . $esquema->id, $attributes);
?>    
    <div class="col-xs-12">
        <div class="ibox-content">    	
            
            <div class="row">

                <div class="col-xs-12">
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" name="nombre" id="nombre" value="<?php echo $esquema->nombre?>" placeholder="Ingrese el nombre" class="form-control">
                    </div>
                </div>

            </div>

            <div class="row">
                
                <div class="col-xs-12">
                    <div class="space-between">
                        <label class="font-weight-bold text-primary mb-0">Sub Esquemas</label>
                        <a onclick="agregarSubEsquema(event)" class="btn btn-info btn-sm">Agregar</a>
                    </div>
                </div>

                <div class="col-xs-12" id="contenedor_esquema">

                </div>
            </div>

            

    	</div>
    </div>



    <!-- BOTONES PARA ENVIAR EL FORMULARIO -->
    <div class="col-xs-12">
        <div class="ibox-content">
            <div class="control-group">
              <div class="controls">
                <?php echo form_button(array('type'  =>'button' ,'id' => 'btnGuardar', 'onclick' => "guardar(event,'update/$esquema->id')" ,'value' =>'Guardar','class' =>'btn btn-success'), "<i class='fa fa-floppy-o'></i> Guardar"); ?> 
                <a class="btn btn-danger" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2); ?>"><i class="fa fa-arrow-circle-left"></i> Volver</a>
              </div>
            </div>
        </div>
    </div>
    <!-- END BOTONES PARA ENVIAR EL FORMULARIO -->

<script type="text/javascript">

//ELEMENTOS CONSTANTES
const contenedor_esquema = document.getElementById("contenedor_esquema")

//CONTADORES
//let subEsquema = <?php echo json_encode($subEsquemas)?>;

let subEsquema = 0;

let subEsquemas = <?php echo json_encode($subEsquemas);?>;
const kilos = <?php echo json_encode($kilos);?>;
const bultos = <?php echo json_encode($bultos);?>;
const metrosCubicos = <?php echo json_encode($metrosCubicos);?>;
const palets = <?php echo json_encode($palets);?>;
const warehouse = <?php echo json_encode($warehouse);?>;
const gestionFlotaT = <?php echo json_encode($gestionFlota);?>;
const valorDeclarado = <?php echo json_encode($valorDeclarado);?>;
const cobranza = <?php echo json_encode($cobranza);?>;
const distancia = <?php echo json_encode($distancia)?>;
const pesoAforado = <?php echo json_encode($pesoAforado);?>;
const zonas = <?php echo json_encode($zonas);?>;
const plantillas = <?php echo json_encode($plantillas);?>;


/*
    EL ESQUEMA ES UN OBJETO QUE CONTENDRA SUB OBJETOS DE TIPO SUB_ESQUEMA_N,
    ESTO PARA QUE CADA SUB_ESQUEMA_N PUEDA CONTROLAR LOS DATOS REFERIDOS A SU 
    PLANTILLA. EJEMPLO:

    esquema = {
        "sub_esquema_1": {
            "indiceKilos": 0,
            "indiceBultos": 0,
            "indiceMetroCubico": 0,
            "indicePalets": 0,
            "indiceGestionFlota": 0,
            "indiceWareHouse": 0,
            "indiceValorDeclarado": 0,
            "indiceCobranza": 0,
            "indicePesoAforado": 0,
            "indiceDistancia": 0
        },
        "sub_esquema_2": {
            "indiceKilos": 0,
            "indiceBultos": 0,
            "indiceMetroCubico": 0,
            "indicePalets": 0,
            "indiceGestionFlota": 0,
            "indiceWareHouse": 0,
            "indiceValorDeclarado": 0,
            "indiceCobranza": 0,
            "indicePesoAforado": 0,
            "indiceDistancia": 0
        }
    }
*/
let esquema = {} 
let esquemaAux = {}

/**
 * SOLO PARA EDICIÓN
*/

function crearEsquema(){
        esquemaAux = {}
        //RECORRO LOS SUB ESQUEMAS
        for (let index = 0; index < subEsquemas.length; index++) {
            const element = subEsquemas[index];
            //ASIGNO EL ID PARA CADA SUB ESQUEMA
            esquemaAux[`sub_esquema_${index}`] = {
                id: subEsquemas[index].id,
                zonaA: subEsquemas[index].zonaA,
                zonaB: subEsquemas[index].zonaB,
                plantilla_id: subEsquemas[index].plantilla_id
            }

            //ASIGNO LOS KILOS
            let auxK = [];

            for (let i = 0; i < kilos.length; i++) {
                const element = kilos[i];
                if(element.idSubEsquema == subEsquemas[index].id){
                    auxK.push(element)
                }
            }

            esquemaAux[`sub_esquema_${index}`]['kilos'] = {
                kilos: auxK
            }

            //ASIGNO LOS BULTOS
            let auxB = [];

            for (let i = 0; i < bultos.length; i++) {
                const element = bultos[i];
                if(element.idSubEsquema == subEsquemas[index].id){
                    auxB.push(element)
                }
            }

            esquemaAux[`sub_esquema_${index}`]['bultos'] = {
                bultos: auxB
            }

            //ASIGNO LOS PALETS
            let auxP = [];

            for (let i = 0; i < palets.length; i++) {
                const element = palets[i];
                if(element.idSubEsquema == subEsquemas[index].id){
                    auxP.push(element)
                }
            }

            esquemaAux[`sub_esquema_${index}`]['palets'] = {
                palets: auxP
            }

            //ASIGNO LOS METROS CUBICOS
            let auxMC = [];

            for (let i = 0; i < metrosCubicos.length; i++) {
                const element = metrosCubicos[i];
                if(element.idSubEsquema == subEsquemas[index].id){
                    auxMC.push(element)
                }
            }

            esquemaAux[`sub_esquema_${index}`]['metrosCubicos'] = {
                metrosCubicos: auxMC
            }

            //ASIGNO WAREHOUSE
            let auxW = [];

            for (let i = 0; i < warehouse.length; i++) {
                const element = warehouse[i];
                if(element.idSubEsquema == subEsquemas[index].id){
                    auxW.push(element)
                }
            }

            esquemaAux[`sub_esquema_${index}`]['warehouse'] = {
                warehouse: auxW
            }

            //ASIGNO GESTION DE FLOTA
            let auxGF = [];

            for (let i = 0; i < gestionFlotaT.length; i++) {
                const element = gestionFlotaT[i];
                if(element.idSubEsquema == subEsquemas[index].id){
                    auxGF.push(element)
                }
            }

            esquemaAux[`sub_esquema_${index}`]['gestionFlota'] = {
                gestionFlota: auxGF
            }

            //ASIGNO VALOR DECLARADO
            let auxVD = [];

            for (let i = 0; i < valorDeclarado.length; i++) {
                const element = valorDeclarado[i];
                if(element.idSubEsquema == subEsquemas[index].id){
                    auxVD.push(element)
                }
            }

            esquemaAux[`sub_esquema_${index}`]['valorDeclarado'] = {
                valorDeclarado: auxVD
            }

            //ASIGNO VALOR COBRANZA
            let auxCob = [];

            for (let i = 0; i < cobranza.length; i++) {
                const element = cobranza[i];
                if(element.idSubEsquema == subEsquemas[index].id){
                    auxCob.push(element)
                }
            }

            esquemaAux[`sub_esquema_${index}`]['cobranza'] = {
                cobranza: auxCob
            }

            //ASIGNO VALOR PESO AFORADO
            let auxPA = [];

            for (let i = 0; i < pesoAforado.length; i++) {
                const element = pesoAforado[i];
                if(element.idSubEsquema == subEsquemas[index].id){
                    auxPA.push(element)
                }
            }

            esquemaAux[`sub_esquema_${index}`]['pesoAforado'] = {
                pesoAforado: auxPA
            }

            //ASIGNO LAS DISTANCIAS
            let auxDist = [];

            for (let i = 0; i < distancia.length; i++) {
                const element = distancia[i];
                if(element.idSubEsquema == subEsquemas[index].id){
                    auxDist.push(element)
                }
            }

            esquemaAux[`sub_esquema_${index}`]['distancia'] = {
                distancia: auxDist
            }


        }

    }

crearEsquema()

/**
 * LA FUNCION CONSTRUIR VISTA TIENE POR OBJETIVO RENDERIZAR TODOS LOS SUB ESQUEMAS
 * CON SUS DETALLES, ESTO SE REALIZA A PARTIR DE LOS DATOS ENTREGADOS POR EL BACKEND 
 * CONSTRUYENDO UN OBJETO EN JAVASCRIPT E ITERANDOLO PARA IR FORMANDO CADA UNA DE LAS PARTES
 */
function construirVista(){

        let html = ``;
        //RECORRO LOS SUB ESQUEMAS
        const cantidad = Object.keys(esquemaAux).length

        for (let index = 0; index < cantidad; index++) {
            let subEsquemaAux = `sub_esquema_${index}`;

            //INICIALIZO LOS CONTADORES PARA CADA SUB ESQUEMA
            esquema[`sub_esquema_${index + 1}`] = {
                indiceKilos: esquemaAux[subEsquemaAux].kilos.kilos.length,
                indiceBultos: esquemaAux[subEsquemaAux].bultos.bultos.length,
                indiceMetroCubico: esquemaAux[subEsquemaAux].metrosCubicos.metrosCubicos.length,
                indicePalets: esquemaAux[subEsquemaAux].palets.palets.length,
                indiceGestionFlota: esquemaAux[subEsquemaAux].gestionFlota.gestionFlota.length,
                indiceWareHouse: esquemaAux[subEsquemaAux].warehouse.warehouse.length,
                indiceValorDeclarado: esquemaAux[subEsquemaAux].valorDeclarado.valorDeclarado.length,
                indiceCobranza: esquemaAux[subEsquemaAux].cobranza.cobranza.length,
                indicePesoAforado: esquemaAux[subEsquemaAux].pesoAforado.pesoAforado.length,
                indiceDistancia: esquemaAux[subEsquemaAux].distancia.distancia.length,
            }

            //VARIABLE subEsquema++ SE UTILIZA PARA IR IDENTIFICANDO CADA UNO DE LOS BLOQUES DE SUB ESQUEMA DE LA VISTA
            subEsquema++;

            html += `
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
                                        `
                                        
                                        for (let index = 0; index < zonas.length; index++) {
                                            const el = zonas[index];
                                            
                                            html += `
                                                <option value="${el.id}" ${el.id == esquemaAux[subEsquemaAux].zonaA ? 'selected' : ''}>${el.nombre}</option>
                                            `
                                        }
                                        
                
            html += `
                                    </select>
                                </div>
                            </div>

                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="zona_destino_${subEsquema}">Zona destino</label>
                                    <select name="zona_destino_${subEsquema}" id="zona_destino_${subEsquema}" class="form-control select-zonas zona-destino" style="width: 100%">
                                        <option value="0">Seleccione</option>
                                        `

                                        for (let index = 0; index < zonas.length; index++) {
                                            const el = zonas[index];
                                            
                                            html += `
                                                <option value="${el.id}" ${el.id == esquemaAux[subEsquemaAux].zonaB ? 'selected' : ''}>${el.nombre}</option>
                                            `
                                        }

            html += `               </select>
                                </div>
                            </div>

                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label for="plantilla_${subEsquema}">Plantillas</label>
                                    <select onchange="getPlantilla(event,${subEsquema})" name="plantilla_${subEsquema}" id="plantilla_${subEsquema}" class="form-control select-plantilla" style="width: 100%">
                                        <option value="0">Seleccione</option>
                                        `



                                        for (let index = 0; index < plantillas.length; index++) {
                                            const el = plantillas[index];
                                            html += `
                                                <option value="${el.id}" ${el.id == esquemaAux[subEsquemaAux].plantilla_id ? 'selected' : ''}>${el.nombre}</option>
                                            `
                                        }

            html += `               </select>
                                </div>
                            </div>

                            <div class="col-xs-12" id="contenedor_plantilla_${subEsquema}">

                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Opciones del tarifario:</label>
                                        <select id="opciones_tarifario_${subEsquema}" onchange="cambioSelect(event, ${subEsquema})" name="opciones_tarifario_${subEsquema}" class="form-control filtro-group">
                                            <option value="0">Seleccione</option>
                                            <option value="1">Kilos (Kg)</option>
                                            <option value="2">Bultos (unidades)</option>
                                            <option value="3">Cobranza</option>
                                            <option value="4">Valor Declarado</option>
                                            <option value="5">Metro Cubico</option>
                                            <option value="6">Palets</option>
                                            <option value="7">Gestión de Flota</option>
                                            <option value="8">WareHouse</option>
                                            <option value="9">Aforo</option>
                                            <option value="10">Distancia (km)</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <a href="javascript:void(0);" onclick="agregarDetalleTarifario(${subEsquema})" id="btnAgregar_${subEsquema}" disabled class="btn btn-success">Agregar</a>
                                    </div>
                                </div> 
                            `
            
          
            //COBRANZA
            html += `
            <div class="col-xs-12">
                <div class="ibox-content" id="rangos_cobranza_${subEsquema}" name="rangos_cobranza_${subEsquema}" style="${esquemaAux[subEsquemaAux].cobranza.cobranza.length > 0 ? '' : 'display:none;' }">
                    <input type="hidden" name="cantidad_fila_cobranza_${subEsquema}" id="cantidad_fila_cobranza_${subEsquema}">
                    <div class="row">
                        <div class="col-xs-6">
                            <label>Sección tarifas por cobranza</label><br><br>
                        </div>
                        <div class="col-xs-6 text-right">
                            <a href="javascript:void(0);" class="btn btn-sm btn-info" onclick="crearFilaCobranza(${subEsquema})">Agregar fila</a>
                        </div>
                    </div>
            `;

            if(esquemaAux[subEsquemaAux].cobranza.cobranza.length > 0){

                for (let i = 0; i < esquemaAux[subEsquemaAux].cobranza.cobranza.length; i++) {
                    const element = esquemaAux[subEsquemaAux].cobranza.cobranza[i];
                    
                    let indice = 1;

                    html += `
                    <div class="row campoDinamico_cobranza">

                            <div class="col-xs-3">
                                <div class="form-group">
                                    <label>Tipo</label>
                                    <select id="tipo_cobranza_${subEsquema}_${indice}" name="tipo_cobranza_${subEsquema}_${indice}" class="form-control filtro-group">
                                        <option value="">Seleccione</option>
                                        <option ${element.tipo == "porcentaje" ? "selected" : ""} value="porcentaje">Porcentaje</option>
                                        <option ${element.tipo == "monto" ? "selected" : ""} value="monto">Monto Fijo</option>                                    
                                    </select>
                                </div>
                            </div>

                            <div class="col-xs-3">
								<div class="form-group">
									<label>Valor</label>
									<input value="${element.valor}" placeholder="Ingrese su valor" type="number" id="valor_cobranza_${subEsquema}_${indice}" name="valor_cobranza_${subEsquema}_${indice}" class="form-control filtro-group" step="0.01">
								</div>
							</div>

                            <div class="col-xs-1">
                                <div class="form-group">
                                    <br>
                                    <a id="btn_eliminar_cobranza_${subEsquema}_${indice}" onclick="eliminarFilaCobranza(event, ${subEsquema})" href="javascript:void(0);" class="eliminar_fila_cobranza agregar_campos btn btn-danger btn-submit" title="Remove field">-</a>
                                </div>
                            </div>
                    </div>
                    `
                            
                }
            }
            
            html += `
                </div> 
            </div> 
            `
            //END COBRANZA

            //VALOR DECLARADO
            html += `
            <div class="col-xs-12">
                <div class="ibox-content" id="rangos_valor_declarado_${subEsquema}" name="rangos_valor_declarado_${subEsquema}" style="${ esquemaAux[subEsquemaAux].valorDeclarado.valorDeclarado.length > 0 ? '' : 'display:none;' }">
                    <input type="hidden" name="cantidad_fila_valor_declarado_${subEsquema}" id="cantidad_fila_valor_declarado_${subEsquema}">
                    <div class="row">
                        <div class="col-xs-6">
                            <label>Sección tarifas por valor declarado</label><br><br>
                        </div>
                        <div class="col-xs-6 text-right">
                            <a href="javascript:void(0);" class="btn btn-sm btn-info" onclick="crearFilaValorDeclarado(${subEsquema})">Agregar fila</a>
                        </div>
                    </div>
            `

            if(esquemaAux[subEsquemaAux].valorDeclarado.valorDeclarado.length > 0){

                for (let i = 0; i < esquemaAux[subEsquemaAux].valorDeclarado.valorDeclarado.length; i++) {
                    const element = esquemaAux[subEsquemaAux].valorDeclarado.valorDeclarado[i];
                    
                    let indice = 1;

                    html += `
                    <div class="row campoDinamico_valor_declarado">                                

                        <div class="col-xs-3">
                                <div class="form-group">
                                    <label>Tipo</label>
                                    <select id="tipo_valor_declarado_${subEsquema}_${indice}" name="tipo_valor_declarado_${subEsquema}_${indice}" class="form-control filtro-group">
                                        <option value="">Seleccione</option>
                                        <option ${element.tipo == "porcentaje" ? "selected" : ""} value="porcentaje">Porcentaje</option>
                                        <option ${element.tipo == "monto" ? "selected" : ""} value="monto">Monto Fijo</option>                                    
                                    </select>
                                </div>
                            </div>

                            <div class="col-xs-3">
								<div class="form-group">
									<label>Valor</label>
									<input value="${element.valor}" placeholder="Ingrese su valor" type="number" id="valor_declarado_${subEsquema}_${indice}" name="valor_declarado_${subEsquema}_${indice}" class="form-control filtro-group" step="0.01">
								</div>
							</div>

                        <div class="col-xs-1">
                            <div class="form-group">
                                <br>
                                <a id="btn_eliminar_valor_declarado_${subEsquema}_${indice}" onclick="eliminarFilaValorDeclarado(event, ${subEsquema})" href="javascript:void(0);" class="eliminar_fila_valor_declarado agregar_campos btn btn-danger btn-submit" title="Remove field">-</a>
                            </div>
                        </div>

                    </div>
                    `  
                }
            }

            html += `
                </div> 
            </div> 
            `
            //END VALOR DECLARADO

            //PESO AFORADO
            html += `
            <div class="col-xs-12">
                <div class="ibox-content" id="rangos_peso_aforado_${subEsquema}" name="rangos_peso_aforado_${subEsquema}" style="${ esquemaAux[subEsquemaAux].pesoAforado.pesoAforado.length > 0 ? '' : 'display:none;'}">
                    <input type="hidden" name="cantidad_fila_peso_aforado_${subEsquema}" id="cantidad_fila_peso_aforado_${subEsquema}">
                    <div class="row">
                        <div class="col-xs-6">
                            <label>Sección tarifas por peso aforado</label><br><br>
                        </div>
                        <div class="col-xs-6 text-right">
                            <a href="javascript:void(0);" class="btn btn-sm btn-info" onclick="crearFilaPesoAforado(${subEsquema})">Agregar fila</a>
                        </div>
                    </div>
            `

            if(esquemaAux[subEsquemaAux].pesoAforado.pesoAforado.length > 0){

                for (let i = 0; i < esquemaAux[subEsquemaAux].pesoAforado.pesoAforado.length; i++) {
                    const element = esquemaAux[subEsquemaAux].pesoAforado.pesoAforado[i];
                    
                    let indice = 1;

                    html += `
                    <div class="row campoDinamico_peso_aforado">
                            <div class="col-xs-3">
                                <div class="form-group">
                                    <label>Valor</label>
                                    <input value="${element.valor}" placeholder="Valor" type="number" id="valor_peso_aforado_${subEsquema}_${indice}" name="valor_peso_aforado_${subEsquema}_${indice}" class="form-control filtro-group" step="1">
                                </div>
                            </div>
                            <div class="col-xs-1">
                                <div class="form-group">
                                    <br>
                                    <a id="btn_eliminar_peso_aforado_${subEsquema}_${indice}"" onclick="eliminarFilaPesoAforado(event, ${subEsquema})" href="javascript:void(0);" class="eliminar_fila_peso_aforado agregar_campos btn btn-danger btn-submit" title="Remove field">-</a>
                                </div>
                            </div>
                    </div>
                    `  
                }
            }

            html += `
                </div> 
            </div> 
            `
            //END PESO AFORADO

            //KILOS
            html += `
            <div class="col-xs-12">
                <div class="ibox-content" id="rangos_precios_kilos_${subEsquema}" name="rangos_precios_kilos_${subEsquema}" style="${esquemaAux[subEsquemaAux].kilos.kilos.length > 0 ? '' : 'display:none;' }">
                    <input type="hidden" name="cantidad_fila_kilos_${subEsquema}" id="cantidad_fila_kilos_${subEsquema}">
                    <div class="row">
                        <div class="col-xs-6">
                            <label>Sección tarifas por kilos</label><br><br>
                        </div>
                        <div class="col-xs-6 text-right">
                            <a href="javascript:void(0);" class="btn btn-sm btn-info" onclick="crearFilaPorKilos(${subEsquema})">Agregar fila</a>
                        </div>
                    </div>
                `
                if(esquemaAux[subEsquemaAux].kilos.kilos.length > 0){

                    for (let i = 0; i < esquemaAux[subEsquemaAux].kilos.kilos.length; i++) {
                        const element = esquemaAux[subEsquemaAux].kilos.kilos[i];
                        
                        let indice = 1;

                        html += `
                        <div class="row campoDinamico_kilos align-items-center">
                            
                            <div class="col-xs-3">
                                <div class="form-group">
                                    <label>Desde</label>
                                    <input id="rinicio_kilos_${subEsquema}_${i + 1}"  
                                            value="${element.desde}"
                                            ${ (i+1) > 1 ? 'readonly' : ''}
                                            placeholder="Ej.: 10" type="number" name="rinicio_kilos_${subEsquema}_${i + 1}" 
                                            class="form-control filtro-group">
                                </div>
                            </div>
                                        
                            <div class="col-xs-3">
                                <div class="form-group">
                                    <label>Hasta</label>
                                    <input id="rfin_kilos_${subEsquema}_${i + 1}" value="${element.hasta}" onblur="validarMaximoKilos(${subEsquema}, ${i + 1})" onkeyup="modificarHastaKilos(event, ${subEsquema}, ${i + 1})" placeholder="Ej.: 20" type="number" name="rfin_kilos_${subEsquema}_${i + 1}" class="form-control filtro-group">
                                </div>
                            </div>

                            <div class="col-xs-3">
                                <div class="form-group">
                                    <label>Precio</label>
                                    <input style="" value="${element.precio}" placeholder="$" type="number" id="precio_kilos_${subEsquema}_${i + 1}" name="precio_kilos_${subEsquema}_${i + 1}" class="form-control filtro-group" step="0.01">
                                </div>
                            </div>
                                        
                            <div class="col-xs-2">
                                <div class="form-group">
                                    <label>Tipo</label>
                                    <select id="opciones_tipo_kilos_${subEsquema}_${i + 1}" name="opciones_tipo_kilos_${subEsquema}_${i + 1}" onchange="" class="form-control filtro-group">
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
                                    <a id="btn_eliminar_kilos_${subEsquema}_${i + 1}" 
                                        ${esquemaAux[subEsquemaAux].kilos.kilos.length > 1 && i != (esquemaAux[subEsquemaAux].kilos.kilos.length - 1) ? 'disabled' : ''}
                                        onclick="eliminarFilaKilos(event, ${subEsquema})" 
                                        href="javascript:void(0);" 
                                        class="eliminar_fila_kilos agregar_campos btn btn-danger btn-submit" 
                                        title="Remove field">-</a>
                                </div>
                            </div>
                        </div>
                        `
                        indice++
                    }
                }

            html += `
                </div> 
            </div> 
            `
            //END KILOS

            //BULTOS
            html += `
            <div class="col-xs-12">
                <div class="ibox-content" id="rangos_precios_bultos_${subEsquema}" name="rangos_precios_bultos_${subEsquema}" style="${esquemaAux[subEsquemaAux].bultos.bultos.length > 0 ? '' : 'display:none;' }">
                    <input type="hidden" name="cantidad_fila_bultos_${subEsquema}" id="cantidad_fila_bultos_${subEsquema}">
                    <div class="row">
                        <div class="col-xs-6">
                            <label>Sección tarifas por bultos</label><br><br>
                        </div>
                        <div class="col-xs-6 text-right">
                            <a href="javascript:void(0);" class="btn btn-sm btn-info" onclick="crearFilasPorBultos(${subEsquema})">Agregar fila</a>
                        </div>
                    </div>
                `

            if(esquemaAux[subEsquemaAux].bultos.bultos.length > 0){

                for (let i = 0; i < esquemaAux[subEsquemaAux].bultos.bultos.length; i++) {
                        const element = esquemaAux[subEsquemaAux].bultos.bultos[i];
                        
                        let indice = 1;

                        html += `
                            <div class="row campoDinamico_bultos">
                                    
                                    <div class="col-xs-3">
                                        <div class="form-group">
                                            <label>Desde</label>
                                            <input id="rinicio_bultos_${subEsquema}_${i + 1}" 
                                            value="${element.desde}"
                                            ${(i + 1) > 1 ? 'readonly' : ''}
                                            placeholder="Ej.: 10" type="number" name="rinicio_bultos_${subEsquema}_${i + 1}" 
                                            class="form-control filtro-group">
                                        </div>
                                    </div>
                                    <div class="col-xs-3">
                                        <div class="form-group">
                                            <label>Hasta</label>
                                            <input id="rfin_bultos_${subEsquema}_${i + 1}" value="${element.hasta}" onkeydown="noPuntoComa(event)" onblur="validarMaximoBultos(${subEsquema}, ${i + 1})" onkeyup="modificarHastaBultos(event, ${subEsquema}, ${i + 1})" placeholder="Ej.: 20" type="number" name="rfin_bultos_${subEsquema}_${i + 1}" class="form-control filtro-group">
                                        </div>
                                    </div>
                                    <div class="col-xs-3">
                                        <div class="form-group">
                                            <label>Precio</label>
                                            <input style="" value="${element.precio}" placeholder="$" type="number" id="precio_bultos_${subEsquema}_${i + 1}" name="precio_bultos_${subEsquema}_${i + 1}" class="form-control filtro-group" step="0.01">
                                        </div>
                                    </div>
                                    <div class="col-xs-2">
                                        <div class="form-group">
                                            <label>Tipo</label>
                                            <select id="opciones_tipo_bultos_${subEsquema}_${i + 1}" name="opciones_tipo_bultos_${subEsquema}_${i + 1}" onchange="" class="form-control filtro-group">
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
                                            <a id="btn_eliminar_bultos_${subEsquema}_${i + 1}" 
                                                ${esquemaAux[subEsquemaAux].bultos.bultos.length > 1 && i != (esquemaAux[subEsquemaAux].bultos.bultos.length - 1) ? 'disabled' : ''}
                                                onclick="eliminarFilaBultos(event, ${subEsquema})" 
                                                href="javascript:void(0);" 
                                                class="eliminar_fila_bultos agregar_campos btn btn-danger btn-submit" 
                                                title="Remove field">-</a>
                                        </div>
                                    </div>
                                </div>
                            `
                            indice++
                    }
                }

                html += `
                    </div> 
                </div> 
                `
            //END BULTOS

            //METROS CUBICOS
            html += `
            <div class="col-xs-12">
                <div class="ibox-content" id="rangos_valor_metro_cubico_${subEsquema}" name="rangos_valor_metro_cubico_${subEsquema}" style="${esquemaAux[subEsquemaAux].metrosCubicos.metrosCubicos.length > 0 ? '' : 'display:none;' }">
                <input type="hidden" name="cantidad_fila_metro_cubico_${subEsquema}" id="cantidad_fila_metro_cubico_${subEsquema}">
                <div class="row">
                    <div class="col-xs-6">
                        <label>Sección tarifas por metros cubicos</label><br><br>
                    </div>
                    <div class="col-xs-6 text-right">
                        <a href="javascript:void(0);" class="btn btn-sm btn-info" onclick="crearFilaMetroCubico(${subEsquema})">Agregar fila</a>
                    </div>
                </div>
                    `;
            
                if(esquemaAux[subEsquemaAux].metrosCubicos.metrosCubicos.length > 0){

                    for (let i = 0; i < esquemaAux[subEsquemaAux].metrosCubicos.metrosCubicos.length; i++) {
                            const element = esquemaAux[subEsquemaAux].metrosCubicos.metrosCubicos[i];
                            
                            let indice = 1;

                            html += `
                                <div class="row campoDinamico_metro_cubico">

                                    <div class="col-xs-3">
                                        <div class="form-group">
                                            <label>Desde</label>
                                            <input id="rinicio_mc_${subEsquema}_${i + 1}" 
                                            value="${element.desde}"
                                            ${ (i + 1) > 1 ? 'readonly' : ''}
                                            placeholder="Ej.: 10" type="number" name="rinicio_mc_${subEsquema}_${i + 1}" 
                                            class="form-control filtro-group">
                                        </div>
                                    </div>
                                    <div class="col-xs-3">
                                        <div class="form-group">
                                            <label>Hasta</label>
                                            <input id="rfin_mc_${subEsquema}_${i + 1}" value="${element.hasta}" onblur="validarMaximoMetroCubico(${subEsquema}, ${i + 1})" onkeyup="modificarHastaMetroCubico(event, ${subEsquema}, ${i + 1})" placeholder="Ej.: 20" type="number" name="rfin_mc_${subEsquema}_${i + 1}" class="form-control filtro-group">
                                        </div>
                                    </div>
                                    <div class="col-xs-3">
                                        <div class="form-group">
                                            <label>Precio</label>
                                            <input style="" value="${element.precio}" placeholder="$" type="number" id="precio_mc_${subEsquema}_${i + 1}" name="precio_mc_${subEsquema}_${i + 1}" class="form-control filtro-group" step="0.01">
                                        </div>
                                    </div>
                                    <div class="col-xs-2">
                                        <div class="form-group">
                                            <label>Tipo</label>
                                            <select id="opciones_tipo_mc_${subEsquema}_${i + 1}" name="opciones_tipo_mc_${subEsquema}_${i + 1}" onchange="" class="form-control filtro-group">
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
                                            <a id="btn_eliminar_metro_cubico_${subEsquema}_${i + 1}" 
                                                ${esquemaAux[subEsquemaAux].metrosCubicos.metrosCubicos.length > 1 && i != (esquemaAux[subEsquemaAux].metrosCubicos.metrosCubicos.length - 1) ? 'disabled' : ''}
                                                onclick="eliminarFilaMetroCubico(event, ${subEsquema})" href="javascript:void(0);" 
                                                class="eliminar_fila_metro_cubico agregar_campos btn btn-danger btn-submit" 
                                                title="Remove field">-</a>
                                        </div>
                                    </div>
                                </div>
                                `
                                indice++

                    }
                }

                html += `
                    </div> 
                </div> 
                `
            //END METROS CUBICOS

            //PALETS
            html += `
                <div class="col-xs-12">
                    <div class="ibox-content" id="rangos_valor_palets_${subEsquema}" name="rangos_valor_palets_${subEsquema}" style="${esquemaAux[subEsquemaAux].palets.palets.length > 0 ? '' : 'display:none;' }">
                        <input type="hidden" name="cantidad_fila_palets_${subEsquema}" id="cantidad_fila_palets_${subEsquema}">

                        <div class="row">
                            <div class="col-xs-6">
                                <label>Sección tarifas por palets</label><br><br>
                            </div>
                            <div class="col-xs-6 text-right">
                                <a href="javascript:void(0);" class="btn btn-sm btn-info" onclick="crearFilaPalets(${subEsquema})">Agregar fila</a>
                            </div>
                        </div>
            `;

            if(esquemaAux[subEsquemaAux].palets.palets.length > 0){

                for (let i = 0; i < esquemaAux[subEsquemaAux].palets.palets.length; i++) {
                        const element = esquemaAux[subEsquemaAux].palets.palets[i];
                        
                        let indice = 1;

                        html += `
                        <div class="row campoDinamico_palets">

                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label>Desde</label>
                                        <input id="rinicio_palets_${subEsquema}_${i + 1}" 
                                        value="${element.desde}"
                                        ${ (i + 1) > 1 ? 'readonly' : ''}
                                        placeholder="Ej.: 10" type="number" name="rinicio_palets_${subEsquema}_${i + 1}" 
                                        class="form-control filtro-group">
                                    </div>
                                </div>
                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label>Hasta</label>
                                        <input id="rfin_palets_${subEsquema}_${i + 1}" value="${element.hasta}" onkeydown="noPuntoComa(event)" onblur="validarMaximoPalets(${subEsquema}, ${i + 1})" onkeyup="modificarHastaPalets(event, ${subEsquema}, ${i + 1})" placeholder="Ej.: 20" type="number" name="rfin_palets_${subEsquema}_${i + 1}" class="form-control filtro-group">
                                    </div>
                                </div>
                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label>Precio</label>
                                        <input style="" value="${element.precio}" placeholder="$" type="number" id="precio_palets_${subEsquema}_${i + 1}" name="precio_palets_${subEsquema}_${i + 1}" class="form-control filtro-group" step="0.01">
                                    </div>
                                </div>
                                <div class="col-xs-2">
                                    <div class="form-group">
                                        <label>Tipo</label>
                                        <select id="opciones_tipo_palets_${subEsquema}_${i + 1}" name="opciones_tipo_palets_${subEsquema}_${i + 1}" onchange="" class="form-control filtro-group">
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
                                        <a id="btn_eliminar_palets_${subEsquema}_${i + 1}" 
                                            ${esquemaAux[subEsquemaAux].palets.palets.length > 1 && i != (esquemaAux[subEsquemaAux].palets.palets.length - 1) ? 'disabled' : ''}
                                            onclick="eliminarFilaPalets(event, ${subEsquema})" 
                                            href="javascript:void(0);" 
                                            class="eliminar_fila_palets agregar_campos btn btn-danger btn-submit" 
                                            title="Remove field">-</a>
                                    </div>
                                </div>
                            </div>
                        `

                        indice++


                }
            }

            html += `
                </div> 
            </div> 
            `
            //END PALETS

            //GESTION DE FLOTA
            html += `
            <div class="col-xs-12">
                <div class="ibox-content" id="rangos_valor_gestion_flota_${subEsquema}" name="rangos_valor_gestion_flota_${subEsquema}" style="${esquemaAux[subEsquemaAux].gestionFlota.gestionFlota.length > 0 ? '' : 'display:none;' }">
                    <input type="hidden" name="cantidad_fila_gestion_flota_${subEsquema}" id="cantidad_fila_gestion_flota_${subEsquema}">

                    <div class="row">
                        <div class="col-xs-6">
                            <label>Sección tarifas por gestión de flota</label><br><br>
                        </div>
                        <div class="col-xs-6 text-right">
                            <a href="javascript:void(0);" class="btn btn-sm btn-info" onclick="crearFilaGestionFlota(${subEsquema})">Agregar fila</a>
                        </div>
                    </div>
            `;

            if(esquemaAux[subEsquemaAux].gestionFlota.gestionFlota.length > 0){

                for (let i = 0; i < esquemaAux[subEsquemaAux].gestionFlota.gestionFlota.length; i++) {
                        const element = esquemaAux[subEsquemaAux].gestionFlota.gestionFlota[i];
                        
                        let indice = 1;

                        html += `
                        <div class="row campoDinamico_gestion_flota">

                                <div class="col-xs-6">
                                    <div class="form-group" >
                                        <label>Vehiculo // Hora</label>
                                        <select id="opcion_horas_gf_${subEsquema}_${i + 1}" name="opcion_horas_gf_${subEsquema}_${i + 1}" onchange="" class="form-control filtro-group select-tipo-vehiculo disabled">
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
                                        <input placeholder="Ingrese el precio" value="${element.precio}" type="number" id="precio_gf_${subEsquema}_${i + 1}" name="precio_gf_${subEsquema}_${i + 1}" class="form-control filtro-group" step="0.01">
                                    </div>
                                </div>

                                <!--Boton - (para eliminar el campo) -->
                                <div class="col-xs-1">
                                    <div class="form-group">
                                        <br>
                                        <a id="btn_eliminar_gestion_flota_${subEsquema}_${i + 1}" 
                                            ${esquemaAux[subEsquemaAux].gestionFlota.gestionFlota.length > 1 && i != (esquemaAux[subEsquemaAux].gestionFlota.gestionFlota.length - 1) ? 'disabled' : ''}
                                            onclick="eliminarFilaGestionFlota(event, ${subEsquema})" 
                                            href="javascript:void(0);" 
                                            class="eliminar_fila_gestion_flota agregar_campos btn btn-danger btn-submit" 
                                            title="Remove field">-</a>
                                    </div>
                                </div>
                            </div>
                    `

                    indice++

                }
            }

            html += `
                </div> 
            </div> 
            `
            //END GESTION DE FLOTA

            //WAREHOUSE
            html += `
            <div class="col-xs-12">
                <div class="ibox-content" id="rangos_valor_warehouse_${subEsquema}" name="rangos_valor_warehouse_${subEsquema}" style="${esquemaAux[subEsquemaAux].warehouse.warehouse.length > 0 ? '' : 'display:none;' }">
                    <input type="hidden" name="cantidad_fila_warehouse_${subEsquema}" id="cantidad_fila_warehouse_${subEsquema}">

                    <div class="row">
                        <div class="col-xs-6">
                            <label>Sección tarifas por WareHouse</label><br><br>
                        </div>
                        <div class="col-xs-6 text-right">
                            <a href="javascript:void(0);" class="btn btn-sm btn-info" onclick="crearFilaWareHouse(${subEsquema})">Agregar fila</a>
                        </div>
                    </div>
                `;

            if(esquemaAux[subEsquemaAux].warehouse.warehouse.length > 0){

                for (let i = 0; i < esquemaAux[subEsquemaAux].warehouse.warehouse.length; i++) {
                        const element = esquemaAux[subEsquemaAux].warehouse.warehouse[i];
                        
                        let indice = 1;

                        html += `
                        <div class="row campoDinamico_warehouse">
                            
                            <div class="col-xs-3">
                                <div class="form-group">
                                    <label>Cantidad</label>
                                    <input id="rinicio_wh_${subEsquema}_${i + 1}" 
                                    value="${element.desde}"
                                    ${(i + 1) > 1 ? 'readonly' : ''}
                                    placeholder="Ej.: 10" type="number" name="rinicio_wh_${subEsquema}_${i + 1}" 
                                    class="form-control filtro-group">
                                </div>
                            </div>

                            <div class="col-xs-3">
                                <div class="form-group">
                                    <label>Hasta</label>
                                    <input id="rfin_wh_${subEsquema}_${i + 1}" value="${element.hasta}" onkeydown="noPuntoComa(event)" onblur="validarMaximoWareHouse(${subEsquema}, ${i + 1})" onkeyup="modificarHastaWareHouse(event, ${subEsquema}, ${i + 1})" placeholder="Ej.: 20" type="number" name="rfin_wh_${subEsquema}_${i + 1}" class="form-control filtro-group">
                                </div>
                            </div>

                            <div class="col-xs-3">
                                <div class="form-group">
                                    <label>Precio</label>
                                    <input placeholder="$" value="${element.precio}" type="number" id="precio_wh_${subEsquema}_${i + 1}" name="precio_wh_${subEsquema}_${i + 1}" class="form-control filtro-group" step="0.01">
                                </div>
                            </div>
                            <!--Boton - (para eliminar el campo) -->
                            <div class="col-xs-1">
                                <div class="form-group">
                                    <br>
                                    <a id="btn_eliminar_wh_${subEsquema}_${i + 1}" 
                                        ${esquemaAux[subEsquemaAux].warehouse.warehouse.length > 1 && i != (esquemaAux[subEsquemaAux].warehouse.warehouse.length - 1 ) ? 'disabled' : ''}
                                        onclick="eliminarFilaWareHouse(event, ${subEsquema})" 
                                        href="javascript:void(0);" 
                                        class="eliminar_fila_wh agregar_campos btn btn-danger btn-submit" 
                                        title="Remove field">-</a>
                                </div>
                            </div>
                        </div>
                    `

                    indice++

                }
            }

            html += `
                </div> 
            </div> 
            `
            //END WAREHOUSE

            //DISTANCIA
            html += `
            <div class="col-xs-12">
                <div class="ibox-content" id="rangos_precios_distancia_${subEsquema}" name="rangos_precios_distancia_${subEsquema}" style="${esquemaAux[subEsquemaAux].distancia.distancia.length > 0 ? '' : 'display:none;' }">
                    <input type="hidden" name="cantidad_fila_distancia_${subEsquema}" id="cantidad_fila_distancia_${subEsquema}">
                    <div class="row">
                        <div class="col-xs-6">
                            <label>Sección tarifas por distancia</label><br><br>
                        </div>
                        <div class="col-xs-6 text-right">
                            <a href="javascript:void(0);" class="btn btn-sm btn-info" onclick="crearFilasPorDistancia(${subEsquema})">Agregar fila</a>
                        </div>
                    </div>
                `
                if(esquemaAux[subEsquemaAux].distancia.distancia.length > 0){

                    for (let i = 0; i < esquemaAux[subEsquemaAux].distancia.distancia.length; i++) {
                        const element = esquemaAux[subEsquemaAux].distancia.distancia[i];
                        
                        let indice = 1;

                        html += `
                        <div class="row campoDinamico_distancia align-items-center">
                            
                            <div class="col-xs-3">
                                <div class="form-group">
                                    <label>Desde</label>
                                    <input id="rinicio_distancia_${subEsquema}_${i + 1}"  
                                            value="${element.desde}"
                                            ${ (i+1) > 1 ? 'readonly' : ''}
                                            placeholder="Ej.: 10" type="number" name="rinicio_distancia_${subEsquema}_${i + 1}" 
                                            class="form-control filtro-group">
                                </div>
                            </div>
                                        
                            <div class="col-xs-3">
                                <div class="form-group">
                                    <label>Hasta</label>
                                    <input id="rfin_distancia_${subEsquema}_${i + 1}" value="${element.hasta}" onblur="validarMaximoDistancia(${subEsquema}, ${i + 1})" onkeyup="modificarHastaDistancia(event, ${subEsquema}, ${i + 1})" placeholder="Ej.: 20" type="number" name="rfin_distancia_${subEsquema}_${i + 1}" class="form-control filtro-group">
                                </div>
                            </div>

                            <div class="col-xs-3">
                                <div class="form-group">
                                    <label>Precio</label>
                                    <input style="" value="${element.precio}" placeholder="$" type="number" id="precio_distancia_${subEsquema}_${i + 1}" name="precio_distancia_${subEsquema}_${i + 1}" class="form-control filtro-group" step="0.01">
                                </div>
                            </div>
                            
                            <!--Boton - (para eliminar el campo) -->
                            <div class="col-xs-1">
                                <div class="form-group">
                                    <br>
                                    <a id="btn_eliminar_distancia_${subEsquema}_${i + 1}" 
                                        ${esquemaAux[subEsquemaAux].distancia.distancia.length > 1 && i != (esquemaAux[subEsquemaAux].distancia.distancia.length - 1) ? 'disabled' : ''}
                                        onclick="eliminarFilaDistancia(event, ${subEsquema})" 
                                        href="javascript:void(0);" 
                                        class="eliminar_fila_distancia agregar_campos btn btn-danger btn-submit" 
                                        title="Remove field">-</a>
                                </div>
                            </div>
                        </div>
                        `
                        indice++
                    }
                }

            html += `
                </div> 
            </div> 
            `
            //END DISTANCIA
                            
            //FINAL DEL CONTENEDOR DE SUB ESQUEMAS
            html += `
                            </div>

                        </div>

                    </div>
            `;

        }

        const contenedor_esquema = document.getElementById("contenedor_esquema")
        contenedor_esquema.insertAdjacentHTML("beforeend", html)

    }

construirVista()

/**
 * ESQUEMA
 */

<?php include 'esquema.js'; ?>

//agregarSubEsquema(event);

/**
 * PLANTILLA
 */

<?php include 'plantilla.js'; ?>

/**
 * TARIFARIOS
 */

<?php include 'kilos.js'; ?>
<?php include 'bultos.js'; ?>
<?php include 'palets.js'; ?>
<?php include 'metros_cubicos.js'; ?>
<?php include 'warehouse.js'; ?>
<?php include 'gestion_flota.js'; ?>
<?php include 'valor_declarado.js'; ?>
<?php include 'cobranza.js'?>
<?php include 'peso_aforado.js'; ?>
<?php include 'distancia.js'; ?>

/**
 * ENVIAR FORMULARIO
 */

<?php include 'guardar.js'; ?>

</script>