function crearFilasPorBultos(contenedor){
    //PARA OBTENER EL SUB ESQUEMA CON EL CUAL TRABAJAR
    const sub_esquema = `sub_esquema_${contenedor}`
    
    let indiceAnterior = esquema[`${sub_esquema}`].indiceBultos;
    esquema[`${sub_esquema}`].indiceBultos = esquema[`${sub_esquema}`].indiceBultos + 1

    let indice = esquema[`${sub_esquema}`].indiceBultos;

    contenedorBultos = document.getElementById(`rangos_precios_bultos_${contenedor}`)
    const id = contenedorBultos.id

    //PARA DESABILITAR LOS BOTONES DE ELIMINAR AL CREARSE UNO NUEVO
    if(indice > 1){

        let inputDesde = document.getElementById(`rinicio_bultos_${contenedor}_${indiceAnterior}`)
        let inputHasta = document.getElementById(`rfin_bultos_${contenedor}_${indiceAnterior}`)
        let inputPrecio = document.getElementById(`precio_bultos_${contenedor}_${indiceAnterior}`)
        let inputTipo = document.getElementById(`opciones_tipo_bultos_${contenedor}_${indiceAnterior}`)

        if(inputDesde.value != '' && inputHasta.value != '' && inputPrecio.value != '' && inputTipo.value != 0){

            if(parseInt(inputDesde.value) >= parseInt(inputHasta.value)){
                
                alert("El campo Hasta debe ser mayor que el campo Desde")
                inputHasta.value = ''
                inputHasta.focus()
                esquema[`${sub_esquema}`].indiceBultos = esquema[`${sub_esquema}`].indiceBultos - 1;
                return;
            }
            
            //const btns = document.getElementsByClassName('eliminar_fila_bultos')
            const btns = $(`#${id} .campoDinamico_bultos .eliminar_fila_bultos`)

            for (let index = 0; index < btns.length; index++) {
                const element = btns[index];
                element.setAttribute('disabled', true);
            }

        }else{
            alert('Debe completar todos los campos para continuar')
            esquema[`${sub_esquema}`].indiceBultos = esquema[`${sub_esquema}`].indiceBultos - 1;
            return;
        }


    }

    let html = `
                        <div class="row campoDinamico_bultos">
							
							<div class="col-xs-3">
								<div class="form-group">
									<label>Desde</label>
									<input id="rinicio_bultos_${contenedor}_${indice}" 
                                    ${indice > 1 ? 'readonly' : ''} 
                                    value="${indice > 1 ? getValorFilaAnteriorBulto(contenedor, indice, 'rfin_bultos') : ''}"
                                    placeholder="Ej.: 10" type="number" name="rinicio_bultos_${contenedor}_${indice}" 
                                    class="form-control filtro-group">
								</div>
							</div>
							<div class="col-xs-3">
								<div class="form-group">
									<label>Hasta</label>
									<input id="rfin_bultos_${contenedor}_${indice}" onkeydown="noPuntoComa(event)" onblur="validarMaximoBultos(${contenedor}, ${indice})" onkeyup="modificarHastaBultos(event, ${contenedor}, ${indice})" placeholder="Ej.: 20" type="number" name="rfin_bultos_${contenedor}_${indice}" class="form-control filtro-group">
								</div>
							</div>
							<div class="col-xs-3">
								<div class="form-group">
									<label>Precio</label>
									<input style="" placeholder="$" type="number" id="precio_bultos_${contenedor}_${indice}" name="precio_bultos_${contenedor}_${indice}" class="form-control filtro-group" step="0.01">
								</div>
							</div>
							<div class="col-xs-2">
								<div class="form-group">
									<label>Tipo</label>
									<select id="opciones_tipo_bultos_${contenedor}_${indice}" name="opciones_tipo_bultos_${contenedor}_${indice}" onchange="" class="form-control filtro-group">
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
                                    <a id="btn_eliminar_bultos_${contenedor}_${indice}" onclick="eliminarFilaBultos(event, ${contenedor})" href="javascript:void(0);" class="eliminar_fila_bultos agregar_campos btn btn-danger btn-submit" title="Remove field">-</a>
                                </div>
                            </div>
						</div>

    `

    contenedorBultos.insertAdjacentHTML('beforeend', html);
}

function getValorFilaAnteriorBulto(contenedor, indice, id){
    indice = indice - 1;
    let value = document.getElementById(`${id}_${contenedor}_${indice}`).value

    return `${parseInt(value) + 1}`
}

function validarMaximoBultos(contenedor, indice){
    let inputDesde = document.getElementById(`rinicio_bultos_${contenedor}_${indice}`)
    let inputHasta = document.getElementById(`rfin_bultos_${contenedor}_${indice}`)

    if(parseFloat(inputHasta.value) <= parseFloat(inputDesde.value) && inputHasta.value != ''){
        alert("El campo Hasta debe ser mayor que el campo Desde")
        inputHasta.value = ''
        inputHasta.focus();
    }
            
}

function modificarHastaBultos(event, contenedor, indice){
    const valor = event.target.value;
    
    if(valor != '' && valor > 0){
        
        let inputDesde = document.getElementById(`rinicio_bultos_${contenedor}_${indice + 1}`)
        let inputHasta = document.getElementById(`rfin_bultos_${contenedor}_${indice + 1}`)
        
        if(inputDesde){
            inputDesde.value = ''
            inputDesde.value = `${parseInt(valor) + 1}`

            if(parseFloat(inputHasta.value) <= parseFloat(inputDesde.value)){
                alert("El campo Hasta debe ser mayor que el campo Desde")
                inputHasta.value = ''
                inputHasta.focus();
            }
        }

    }
}

function eliminarFilaBultos(event, contenedor){
    event.preventDefault();

    //ELIMINO LA FILA
    event.target.parentNode.parentNode.parentNode.remove()
    //DISMINUYO EL CONTADOR
    esquema[`sub_esquema_${contenedor}`].indiceBultos = esquema[`sub_esquema_${contenedor}`].indiceBultos - 1;
    const indiceBultos = esquema[`sub_esquema_${contenedor}`].indiceBultos
    //TOMO EL BOTON QUE SE ENCUENTRA EN LA FILA SUPERIOR Y LO ACTIVO

    if(indiceBultos > 0){
        //SI EL INDICE ES MAYOR QUE CERO SIGNIFICA QUE HAY UN BOTON PARA ACTIVAR
        const btnAnterior = document.getElementById(`btn_eliminar_bultos_${contenedor}_${indiceBultos}`)
        btnAnterior.removeAttribute('disabled');
    }else{
        //SI EL INDICE ES CERO OCULTO DIRECTAMENTE EL PANEL
        $(`#rangos_precios_bultos_${contenedor}`).css('display', 'none')
    }
}

function noPuntoComa(event) {
    let e = event || window.event;
    let key = e.keyCode || e.which;

    if ( key === 110 || key === 190 || key === 188 ) {     
       e.preventDefault();     
    }
}