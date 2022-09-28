<?php     
    $attributes = array('id' => 'formEnviar');
    echo form_open(base_url(). 'plantillas/plantillas/store/', $attributes);
?>    
    <div class="col-xs-12">
        <div class="ibox-content">    	
            
            <div class="row">
                <div class="col-xs-12">
                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Nombre" required>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="form-group">
                        <label for="descripcion">Descripción:</label>
                        <textarea name="descripcion" id="descripcion" cols="10" rows="5" class="form-control" placeholder="Descripción" required></textarea>
                    </div>
                </div>
            </div>

    	</div>
    </div>

    <!-- SELECT CON LAS OPCIONES -->
    <div class="col-xs-12">
        <div class="ibox-content" id="seccion_opciones_tarifario" name="seccion_opciones_tarifario">
		    <div class="row">
                <!-- OPCIONES -->
				<div class="col-xs-6">
					<div class="form-group">
						<label>Opciones del tarifario:</label>
						<select id="opciones_tarifario" name="opciones_tarifario" class="form-control filtro-group">
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
                            <option value="10">Distancia</option>
						</select>
					</div>
				</div>
                <!-- END OPCIONES -->

               <!-- BOTON AGREGAR -->
               <div class="col-xs-6">
                    <div class="form-group">
                        <a href="javascript:void(0);" id="btnAgregar" disabled class="btn btn-success">Agregar</a>
                    </div>
                </div>   
                <!-- END BOTON AGREGAR -->
			</div>				
		</div>
    </div>

    <!-- CONTENEDORES DE FILAS DE OPCIONES -->
    <div class="col-xs-12">
        
        <!--Campos de Kilos-->
		<div class="ibox-content" id="rangos_precios_kilos" name="rangos_precios_kilos" style="display:none;">
            <input type="hidden" name="cantidad_fila_kilos" id="cantidad_fila_kilos">
            <div class="row">
                <div class="col-xs-6">
                    <label>Sección tarifas por kilos</label><br><br>
                </div>
                <div class="col-xs-6 text-right">
                    <a href="javascript:void(0);" class="btn btn-sm btn-info" onclick="crearFilaPorKilos()">Agregar fila</a>
                </div>
            </div>

		</div>
        <!--fin de Campos de Kilos-->

        <!--Campos de Bultos-->
					<div class="ibox-content" id="rangos_precios_bultos" name="rangos_precios_bultos" style="display:none;">
                        <input type="hidden" name="cantidad_fila_bultos" id="cantidad_fila_bultos">
                        <div class="row">
                            <div class="col-xs-6">
                                <label>Sección tarifas por bultos</label><br><br>
                            </div>
                            <div class="col-xs-6 text-right">
                                <a href="javascript:void(0);" class="btn btn-sm btn-info" onclick="crearFilasPorBultos()">Agregar fila</a>
                            </div>
                        </div>
					</div>
        
        <!-- fin Campos Bultos -->

        <!-- Campos de Cobranza -->
					<div class="ibox-content" id="rangos_cobranza" name="rangos_cobranza" style="display:none;">
                        <input type="hidden" name="cantidad_fila_cobranza" id="cantidad_fila_cobranza">
                        <div class="row">
                            <div class="col-xs-6">
                                <label>Sección tarifas por cobranza</label><br><br>
                            </div>
                            <div class="col-xs-6 text-right">
                                <a href="javascript:void(0);" class="btn btn-sm btn-info" onclick="crearFilaCobranza()">Agregar fila</a>
                            </div>
                        </div>
					</div>
        <!-- fin de Campos de Cobranza -->

        <!-- Campos de Valor Declarado -->
					<div class="ibox-content" id="rangos_valor_declarado" name="rangos_valor_declarado" style="display:none;">
                        <input type="hidden" name="cantidad_fila_valor_declarado" id="cantidad_fila_valor_declarado">
                        <div class="row">
                            <div class="col-xs-6">
                                <label>Sección tarifas por valor declarado</label><br><br>
                            </div>
                            <div class="col-xs-6 text-right">
                                <a href="javascript:void(0);" class="btn btn-sm btn-info" onclick="crearFilaValorDeclarado()">Agregar fila</a>
                            </div>
                        </div>
					</div>
        <!-- fin de Campos de Valor Declarado -->

        <!-- Campos de Peso aforado -->
					<div class="ibox-content" id="rangos_peso_aforado" name="rangos_peso_aforado" style="display:none;">
                        <input type="hidden" name="cantidad_fila_peso_aforado" id="cantidad_fila_peso_aforado">
                        <div class="row">
                            <div class="col-xs-6">
                                <label>Sección tarifas Aforo</label><br><br>
                            </div>
                            <div class="col-xs-6 text-right">
                                <a href="javascript:void(0);" class="btn btn-sm btn-info" onclick="crearFilaPesoAforado()">Agregar fila</a>
                            </div>
                        </div>
					</div>
        <!-- fin de Campos de Peso aforado -->

        <!-- Campos de Metro Cubico -->
        <div class="ibox-content" id="rangos_valor_metro_cubico" name="rangos_valor_metro_cubico" style="display:none;">
            <input type="hidden" name="cantidad_fila_metro_cubico" id="cantidad_fila_metro_cubico">
            <div class="row">
                <div class="col-xs-6">
                    <label>Sección tarifas por metros cubicos</label><br><br>
                </div>
                <div class="col-xs-6 text-right">
                    <a href="javascript:void(0);" class="btn btn-sm btn-info" onclick="crearFilaMetroCubico()">Agregar fila</a>
                </div>
            </div>
		</div>
        <!-- fin de Campos de Metro Cubico -->

        <!-- Campos de Palets -->
					<div class="ibox-content" id="rangos_valor_palets" name="rangos_valor_palets" style="display:none;">
                        <input type="hidden" name="cantidad_fila_palets" id="cantidad_fila_palets">

                        <div class="row">
                            <div class="col-xs-6">
                                <label>Sección tarifas por palets</label><br><br>
                            </div>
                            <div class="col-xs-6 text-right">
                                <a href="javascript:void(0);" class="btn btn-sm btn-info" onclick="crearFilaPalets()">Agregar fila</a>
                            </div>
                        </div>
					</div>

        <!-- fin de Campos de Palets -->

        <!-- Campos de Gestion de Flota -->
					<div class="ibox-content" id="rangos_valor_gestion_flota" name="rangos_valor_gestion_flota" style="display:none;">
                        <input type="hidden" name="cantidad_fila_gestion_flota" id="cantidad_fila_gestion_flota">

						<div class="row">
                            <div class="col-xs-6">
                                <label>Sección tarifas por gestión de flota</label><br><br>
                            </div>
                            <div class="col-xs-6 text-right">
                                <a href="javascript:void(0);" class="btn btn-sm btn-info" onclick="crearFilaGestionFlota()">Agregar fila</a>
                            </div>
                        </div>

					</div>
        <!-- fin de Campos de Gestion de Flota -->

        <!-- Campos de Warehouse -->
					<div class="ibox-content" id="rangos_valor_warehouse" name="rangos_valor_warehouse" style="display:none;">
                        <input type="hidden" name="cantidad_fila_warehouse" id="cantidad_fila_warehouse">

                        <div class="row">
                            <div class="col-xs-6">
                                <label>Sección tarifas por WareHouse</label><br><br>
                            </div>
                            <div class="col-xs-6 text-right">
                                <a href="javascript:void(0);" class="btn btn-sm btn-info" onclick="crearFilaWareHouse()">Agregar fila</a>
                            </div>
                        </div>

					</div>
        <!-- fin de Campos de WareHouse -->

        <!--Campos de Distancia -->
		<div class="ibox-content" id="rangos_precios_distancia" name="rangos_precios_distancia" style="display:none;">
            <input type="hidden" name="cantidad_fila_distancia" id="cantidad_fila_distancia">
            <div class="row">
                <div class="col-xs-6">
                    <label>Sección tarifas por distancia</label><br><br>
                </div>
                <div class="col-xs-6 text-right">
                    <a href="javascript:void(0);" class="btn btn-sm btn-info" onclick="crearFilasPorDistancia()">Agregar fila</a>
                </div>
            </div>

		</div>
        <!--fin de Campos de Distancia -->

    </div>

    

    <!-- END CONTENEDORES DE FILAS DE OPCIONES -->

    <!-- Botones -->
    <div class="col-xs-12">
        <div class="ibox-content">
            <label>Al terminar de ingregar los datos haga click en el boton "Guardar".</label><br>
            <div class="control-group">
              <div class="controls">
                <?php echo form_button(array('type'  =>'button', 'onclick' => 'guardar(event)' ,'id' => 'btnGuardar' ,'value' =>'Guardar','class' =>'btn btn-success'), "<i class='fa fa-floppy-o'></i> Guardar"); ?> 
                <a class="btn btn-danger" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2); ?>"><i class="fa fa-arrow-circle-left"></i> Volver</a>
              </div>
            </div>
        </div>
    </div>
    <!-- end Botones -->

<script type="text/javascript">

let indiceKilos = 0;
let indiceBultos = 0;
let indiceMetroCubico = 0;
let indicePalets = 0;
let indiceGestionFlota = 0;
let indiceWareHouse = 0;
let indiceValorDeclarado = 0;
let indiceCobranza = 0;
let indicePesoAforado = 0;
let indiceDistancia = 0;

let contenedorKilos = document.getElementById('rangos_precios_kilos')
let contenedorBultos = document.getElementById('rangos_precios_bultos')
let contenedorCobranza = document.getElementById('rangos_cobranza')
let contenedorValorDeclarado = document.getElementById('rangos_valor_declarado')
let contenedorMetroCubico = document.getElementById('rangos_valor_metro_cubico')
let contenedorPalets = document.getElementById('rangos_valor_palets')
let contenedorGestionFlota = document.getElementById('rangos_valor_gestion_flota')
let contenedorWarehouse = document.getElementById('rangos_valor_warehouse')
let contenedorPesoAforado = document.getElementById('rangos_peso_aforado')
let contenedorDistancia = document.getElementById('rangos_precios_distancia')



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

function getValorFilaAnteriorDistancia(indice, id){
    indice = indice - 1;
    let value = document.getElementById(`${id}${indice}`).value

    return `${(parseFloat(value) + 0.01).toFixed(2)}`
}

function getValorFilaAnteriorKilo(indice, id){
    indice = indice - 1;
    let value = document.getElementById(`${id}${indice}`).value

    return `${value}.01`
}

function getValorFilaAnteriorBulto(indice, id){
    indice = indice - 1;
    let value = document.getElementById(`${id}${indice}`).value

    return `${parseInt(value) + 1}`
}

function getValorFilaAnteriorMetroCubico(indice, id){
    indice = indice - 1;
    let value = document.getElementById(`${id}${indice}`).value

    return `${value}.01`
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

function eliminarFilaDistancia(event){
    event.preventDefault();

    //ELIMINO LA FILA
    event.target.parentNode.parentNode.parentNode.remove()
    //DISMINUYO EL CONTADOR
    --indiceDistancia;
    //TOMO EL BOTON QUE SE ENCUENTRA EN LA FILA SUPERIOR Y LO ACTIVO

    if(indiceDistancia > 0){
        //SI EL INDICE ES MAYOR QUE CERO SIGNIFICA QUE HAY UN BOTON PARA ACTIVAR
        const btnAnterior = document.getElementById(`btn_eliminar_distancia_${indiceDistancia}`)
        btnAnterior.removeAttribute('disabled');
    }else{
        //SI EL INDICE ES CERO OCULTO DIRECTAMENTE EL PANEL
        $('#rangos_precios_distancia').css('display', 'none')
    }
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

function modificarHastaDistancia(event, indice){
    const valor = event.target.value;
    
    if(valor != '' && valor > 0){
        
        let inputDesde = document.getElementById(`rinicio_distancia${indice + 1}`)
        let inputHasta = document.getElementById(`rfin_distancia${indice + 1}`)
        
        if(inputDesde){
            inputDesde.value = ''
            inputDesde.value = `${(parseFloat(valor) + 0.01).toFixed(2)}`

            if(parseFloat(inputHasta.value) <= parseFloat(inputDesde.value)){
                alert("El campo Hasta debe ser mayor que el campo Desde")
                inputHasta.value = ''
                inputHasta.focus();
            }
        }

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

function validarMaximoDistancia(indice){
    let inputDesde = document.getElementById(`rinicio_distancia${indice}`)
    let inputHasta = document.getElementById(`rfin_distancia${indice}`)

    if(parseFloat(inputHasta.value) <= parseFloat(inputDesde.value) && inputHasta.value != ''){
        alert("El campo Hasta debe ser mayor que el campo Desde")
        inputHasta.value = ''
        inputHasta.focus();
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

function crearFilasPorDistancia(){
    let indiceAnterior = indiceDistancia;
    let indice = ++indiceDistancia;

    //PARA DESABILITAR LOS BOTONES DE ELIMINAR AL CREARSE UNO NUEVO
    if(indice > 1){

        let inputDesde = document.getElementById(`rinicio_distancia${indiceAnterior}`)
        let inputHasta = document.getElementById(`rfin_distancia${indiceAnterior}`)
        let inputPrecio = document.getElementById(`precio_distancia${indiceAnterior}`)

        if(inputDesde.value != '' && inputHasta.value != '' && inputPrecio.value != ''){

            if(parseFloat(inputDesde.value) >= parseFloat(inputHasta.value)){
                
                alert("El campo Hasta debe ser mayor que el campo Desde")
                inputHasta.value = ''
                inputHasta.focus()
                --indiceDistancia;
                return;
            }
            
            const btns = document.getElementsByClassName('eliminar_fila_distancia')

            for (let item of btns) {
                item.setAttribute('disabled', true);
            }

        }else{
            alert('Debe completar todos los campos para continuar')
            --indiceDistancia;
            return;
        }


    }

    let html = `
                        <div class="row campoDinamico_distancia">
							
							<div class="col-xs-3">
								<div class="form-group">
									<label>Desde</label>
									<input id="rinicio_distancia${indice}" 
                                    ${indice > 1 ? 'readonly' : ''} 
                                    value="${indice > 1 ? getValorFilaAnteriorDistancia(indice, 'rfin_distancia') : ''}"
                                    placeholder="Ej.: 10" type="number" name="rinicio_distancia${indice}" 
                                    class="form-control filtro-group">
								</div>
							</div>
							<div class="col-xs-3">
								<div class="form-group">
									<label>Hasta</label>
									<input id="rfin_distancia${indice}" onblur="validarMaximoDistancia(${indice})" onkeyup="modificarHastaDistancia(event, ${indice})" placeholder="Ej.: 20" type="number" name="rfin_distancia${indice}" class="form-control filtro-group">
								</div>
							</div>
							<div class="col-xs-3">
								<div class="form-group">
									<label>Precio</label>
									<input style="" placeholder="$" type="number" id="precio_distancia${indice}" name="precio_distancia${indice}" class="form-control filtro-group" step="0.01">
								</div>
							</div>
                            <!--Boton - (para eliminar el campo) -->
                            <div class="col-xs-1">
                                <div class="form-group">
                                    <br>
                                    <a id="btn_eliminar_distancia_${indice}" onclick="eliminarFilaDistancia(event)" href="javascript:void(0);" class="eliminar_fila_distancia agregar_campos btn btn-danger btn-submit" title="Remove field">-</a>
                                </div>
                            </div>
						</div>

    `

    contenedorDistancia.insertAdjacentHTML('beforeend', html);
}

function crearFilaPorKilos(){
    let indiceAnterior = indiceKilos;
    let indice = ++indiceKilos;

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

    //PARA DESABILITAR LOS BOTONES DE ELIMINAR AL CREARSE UNO NUEVO
    if(indice > 1){

        let inputDesde = document.getElementById(`rinicio_bultos${indiceAnterior}`)
        let inputHasta = document.getElementById(`rfin_bultos${indiceAnterior}`)
        let inputPrecio = document.getElementById(`precio_bultos${indiceAnterior}`)
        let inputTipo = document.getElementById(`opciones_tipo_bultos${indiceAnterior}`)

        if(inputDesde.value != '' && inputHasta.value != '' && inputPrecio.value != '' && inputTipo.value != 0){

            if(parseFloat(inputDesde.value) >= parseFloat(inputHasta.value)){
                
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
            inputDesde.value = `${parseInt(valor) + 1}`

            if(parseFloat(inputHasta.value) < parseFloat(inputDesde.value)){
                alert("El campo Hasta debe ser mayor o igual que el campo Desde")
                inputHasta.value = ''
                inputHasta.focus();
            }
        }

    }
}

function validarMaximoPalets(indice){
    let inputDesde = document.getElementById(`rinicio_palets${indice}`)
    let inputHasta = document.getElementById(`rfin_palets${indice}`)

    if(parseFloat(inputHasta.value) < parseFloat(inputDesde.value) && inputHasta.value != ''){
        alert("El campo Hasta debe ser mayor o igual que el campo Desde")
        inputHasta.value = ''
        inputHasta.focus();
    }
            
}

function crearFilaPalets(){
    let indiceAnterior = indicePalets;
    let indice = ++indicePalets;

    //PARA DESABILITAR LOS BOTONES DE ELIMINAR AL CREARSE UNO NUEVO
    if(indice > 1){

        let inputDesde = document.getElementById(`rinicio_palets${indiceAnterior}`)
        let inputHasta = document.getElementById(`rfin_palets${indiceAnterior}`)
        let inputPrecio = document.getElementById(`precio_palets${indiceAnterior}`)
        let inputTipo = document.getElementById(`opciones_tipo_palets${indiceAnterior}`)

        if(inputDesde.value != '' && inputHasta.value != '' && inputPrecio.value != '' && inputTipo.value != 0){

            if(parseFloat(inputHasta.value) < parseFloat(inputDesde.value)){
                alert("El campo Hasta debe ser mayor o igual que el campo Desde")
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
									<label>Tipo</label>
                                    <select id="tipo_cobranza${indice}" name="tipo_cobranza${indice}" class="form-control filtro-group">
                                        <option value="">Seleccione</option>
                                        <option value="porcentaje">Porcentaje</option>
                                        <option value="monto">Monto Fijo</option>                                    
                                    </select>
								</div>
							</div>
							<div class="col-xs-3">
								<div class="form-group">
									<label>Valor</label>
									<input style="" placeholder="Ingrese su valor" type="number" id="valor_cobranza${indice}" name="valor_cobranza${indice}" class="form-control filtro-group" step="0.01">
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
									<label>Tipo</label>
                                    <select id="tipo_valor_declarado${indice}" name="tipo_valor_declarado${indice}" class="form-control filtro-group">
                                        <option value="">Seleccione</option>
                                        <option value="porcentaje">Porcentaje</option>
                                        <option value="monto">Monto Fijo</option>                                    
                                    </select>
								</div>
							</div>

							<div class="col-xs-3">
								<div class="form-group">
									<label>Valor</label>
									<input style="" placeholder="Ingrese su valor" type="number" id="valor_declarado${indice}" name="valor_declarado${indice}" class="form-control filtro-group" step="0.01">
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


$(document).ready(function() {
    
    $('#btnAgregar').click(function() {
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
                    scrollTop: $("#rangos_valor_gestion_flota").offset().top
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

            case '10':

                $('#rangos_precios_distancia').css('display', 'block')
                crearFilasPorDistancia();

                $('html, body').animate({
                    scrollTop: $("#rangos_precios_distancia").offset().top
                }, 2000);

                break;

            default:
                console.log("Sale por el default")
                break;
        }
    });

    $('#opciones_tarifario').change(function(){
        if($('#opciones_tarifario').val() != 0){
            $('#btnAgregar').attr('disabled', false);
        }else{
            $('#btnAgregar').attr('disabled', true);
        }
    });

});

let btnGuardar = document.getElementById('btnGuardar')

function guardar(event){

    //campoDinamico_kilos
    const cantidadKilos = document.getElementsByClassName('campoDinamico_kilos').length
    const cantidadBultos = document.getElementsByClassName('campoDinamico_bultos').length
    const cantidadMetroCubico = document.getElementsByClassName('campoDinamico_metro_cubico').length
    const cantidadPalets = document.getElementsByClassName('campoDinamico_palets').length
    const cantidadGestionFlota = document.getElementsByClassName('campoDinamico_gestion_flota').length
    const cantidadWarehouse = document.getElementsByClassName('campoDinamico_warehouse').length
    const cantidadCobranza = document.getElementsByClassName('campoDinamico_cobranza').length
    const cantidadValorDeclarado = document.getElementsByClassName('campoDinamico_valor_declarado').length
    const cantidadPesoAforado = document.getElementsByClassName('campoDinamico_peso_aforado').length
    const cantidadDistancia = document.getElementsByClassName('campoDinamico_distancia').length

    document.getElementById('cantidad_fila_kilos').value = cantidadKilos
    document.getElementById('cantidad_fila_bultos').value = cantidadBultos
    document.getElementById('cantidad_fila_metro_cubico').value = cantidadMetroCubico
    document.getElementById('cantidad_fila_palets').value = cantidadPalets
    document.getElementById('cantidad_fila_gestion_flota').value = cantidadGestionFlota
    document.getElementById('cantidad_fila_warehouse').value = cantidadWarehouse
    document.getElementById('cantidad_fila_cobranza').value = cantidadCobranza
    document.getElementById('cantidad_fila_valor_declarado').value = cantidadValorDeclarado
    document.getElementById('cantidad_fila_peso_aforado').value = cantidadPesoAforado
    document.getElementById('cantidad_fila_distancia').value = cantidadDistancia

    //VALIDACIÓN PARA CAMPO COBRANZA
    if( cantidadCobranza > 0 ){

        if(document.getElementById('tipo_cobranza1').value == "" || document.getElementById('tipo_cobranza1').value == "0"){
            alert("Debe seleccionar el tipo que tendra la cobranza")
            document.getElementById('tipo_cobranza1').focus();
            return;
        }

        if(document.getElementById('tipo_cobranza1').value == "" || document.getElementById('tipo_cobranza1').value == "0"){
            alert("Debe seleccionar el tipo que tendra la cobranza")
            document.getElementById('tipo_cobranza1').focus();
            return;
        }

        if(document.getElementById('valor_cobranza1').value == ""){
            alert("El valor de cobranza es requerido")
            document.getElementById('valor_cobranza1').focus();
            return;
        }

        if(document.getElementById('valor_cobranza1').value <= 0){
            alert("El valor de cobranza debe ser mayor a cero")
            document.getElementById('valor_cobranza1').value = ''
            document.getElementById('valor_cobranza1').focus();
            return;
        }
        
    }

    //VALIDACIÓN PARA CAMPO VALOR DECLARADO
    if( cantidadValorDeclarado > 0 ){

        if(document.getElementById('tipo_valor_declarado1').value == "" || document.getElementById('tipo_valor_declarado1').value == "0"){
            alert("Debe seleccionar el tipo que tendra el valor declarado")
            document.getElementById('tipo_valor_declarado1').focus();
            return;
        }

        if(document.getElementById('tipo_valor_declarado1').value == "" || document.getElementById('tipo_valor_declarado1').value == "0"){
            alert("Debe seleccionar el tipo que tendra el valor declarado")
            document.getElementById('tipo_valor_declarado1').focus();
            return;
        }

        if(document.getElementById('valor_declarado1').value == ""){
            alert("El valor declarado es requerido")
            document.getElementById('valor_declarado1').focus();
            return;
        }

        if(document.getElementById('valor_declarado1').value <= 0){
            alert("El valor declarado debe ser mayor a cero")
            document.getElementById('valor_declarado1').value = ''
            document.getElementById('valor_declarado1').focus();
            return;
        }

    }

    //VALIDACIÓN PARA CAMPO VALOR DECLARADO
    if( cantidadPesoAforado > 0 ){

        if(document.getElementById('valor_peso_aforado1').value == ""){
            alert("El valor de peso aforado es requerido")
            document.getElementById('valor_peso_aforado1').focus();
            return;
        }

        if(document.getElementById('valor_peso_aforado1').value <= 0){
            alert("El valor de peso aforado debe ser mayor a cero")
            document.getElementById('valor_peso_aforado1').value = ''
            document.getElementById('valor_peso_aforado1').focus();
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

    //VALIDACION PARA CAMPO DISTANCIA
    if( cantidadDistancia > 0 ){

        for (let index = 1; index <= cantidadDistancia; index++) {

            let inputDesde = document.getElementById(`rinicio_distancia${index}`)
            let inputHasta = document.getElementById(`rfin_distancia${index}`)
            let inputPrecio = document.getElementById(`precio_distancia${index}`)
            let inputTipo = document.getElementById(`opciones_tipo_distancia${index}`)

            if(inputDesde.value == ""){
                alert("El valor desde de tarifa distancia es requerido")
                inputDesde.value = "";
                inputDesde.focus();
                return;
            }

            if(inputDesde.value <= 0){
                alert("El valor desde de tarifa distancia debe ser mayor a cero")
                inputDesde.value = "";
                inputDesde.focus();
                return;
            }

            if(inputHasta.value == ""){
                alert("El valor hasta de tarifa distancia es requerido")
                inputHasta.value = "";
                inputHasta.focus();
                return;
            }

            if(inputHasta.value <= 0){
                alert("El valor hasta de tarifa distancia debe ser mayor a cero")
                inputHasta.value = "";
                inputHasta.focus();
                return;
            }

            if(inputPrecio.value == ""){
                alert("El precio de tarifa distancia es requerido")
                inputPrecio.value = "";
                inputPrecio.focus();
                return;
            }

            if(inputPrecio.value <= 0){
                alert("El precio de tarifa distancia debe ser mayor a cero")
                inputPrecio.value = "";
                inputPrecio.focus();
                return;
            }

        }
    }

    //VALIDACIÓN NOMBRE  
    let nombre = document.getElementById('nombre')
    if(nombre.value == "" || nombre.value.replace(/ /g, "").length == 0){
        alert("El nombre de la plantilla es requerido")
        nombre.value = "";
        nombre.focus();
        return;
    }

    document.forms["formEnviar"].submit();
}


</script>