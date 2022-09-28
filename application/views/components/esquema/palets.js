function crearFilaPalets(contenedor){
    //PARA OBTENER EL SUB ESQUEMA CON EL CUAL TRABAJAR
    const sub_esquema = `sub_esquema_${contenedor}`
    
    let indiceAnterior = esquema[`${sub_esquema}`].indicePalets;
    esquema[`${sub_esquema}`].indicePalets = esquema[`${sub_esquema}`].indicePalets + 1

    let indice = esquema[`${sub_esquema}`].indicePalets;

    contenedorPalets = document.getElementById(`rangos_valor_palets_${contenedor}`)
    const id = contenedorPalets.id

    //PARA DESABILITAR LOS BOTONES DE ELIMINAR AL CREARSE UNO NUEVO
    if(indice > 1){

        let inputDesde = document.getElementById(`rinicio_palets_${contenedor}_${indiceAnterior}`)
        let inputHasta = document.getElementById(`rfin_palets_${contenedor}_${indiceAnterior}`)
        let inputPrecio = document.getElementById(`precio_palets_${contenedor}_${indiceAnterior}`)
        let inputTipo = document.getElementById(`opciones_tipo_palets_${contenedor}_${indiceAnterior}`)

        if(inputDesde.value != '' && inputHasta.value != '' && inputPrecio.value != '' && inputTipo.value != 0){

            if(parseInt(inputHasta.value) < parseInt(inputDesde.value)){
                alert("El campo Hasta debe ser mayor o igual que el campo Desde")
                inputHasta.value = ''
                inputHasta.focus()
                esquema[`${sub_esquema}`].indicePalets = esquema[`${sub_esquema}`].indicePalets - 1;
                return;
            }
            
            const btns = document.getElementsByClassName('eliminar_fila_palets')

            for (let item of btns) {
                item.setAttribute('disabled', true);
            }

        }else{
            alert('Debe completar todos los campos para continuar')
            esquema[`${sub_esquema}`].indicePalets = esquema[`${sub_esquema}`].indicePalets - 1;
            return;
        }


    }

    let html = `
                        <div class="row campoDinamico_palets">
							<div class="col-xs-3">
								<div class="form-group">
									<label>Desde</label>
									<input id="rinicio_palets_${contenedor}_${indice}" 
                                    ${indice > 1 ? 'readonly' : ''} 
                                    value="${indice > 1 ? getValorFilaAnteriorPalets(contenedor, indice, 'rfin_palets') : ''}"
                                    placeholder="Ej.: 10" type="number" name="rinicio_palets_${contenedor}_${indice}" 
                                    class="form-control filtro-group">
								</div>
							</div>
							<div class="col-xs-3">
								<div class="form-group">
									<label>Hasta</label>
									<input id="rfin_palets_${contenedor}_${indice}" onkeydown="noPuntoComa(event)" onblur="validarMaximoPalets(${contenedor}, ${indice})" onkeyup="modificarHastaPalets(event, ${contenedor} ,${indice})" placeholder="Ej.: 20" type="number" name="rfin_palets_${contenedor}_${indice}" class="form-control filtro-group">
								</div>
							</div>
							<div class="col-xs-3">
								<div class="form-group">
									<label>Precio</label>
									<input style="" placeholder="$" type="number" id="precio_palets_${contenedor}_${indice}" name="precio_palets_${contenedor}_${indice}" class="form-control filtro-group" step="0.01">
								</div>
							</div>
							<div class="col-xs-2">
								<div class="form-group">
									<label>Tipo</label>
									<select id="opciones_tipo_palets_${contenedor}_${indice}" name="opciones_tipo_palets_${contenedor}_${indice}" onchange="" class="form-control filtro-group">
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
                                    <a id="btn_eliminar_palets_${contenedor}_${indice}" onclick="eliminarFilaPalets(event, ${contenedor})" href="javascript:void(0);" class="eliminar_fila_palets agregar_campos btn btn-danger btn-submit" title="Remove field">-</a>
                                </div>
                            </div>
						</div>

    `

    contenedorPalets.insertAdjacentHTML('beforeend', html);
}

function getValorFilaAnteriorPalets(contenedor, indice, id){
    indice = indice - 1;
    let value = document.getElementById(`${id}_${contenedor}_${indice}`).value

    return `${parseInt(value) + 1}`
}

function validarMaximoPalets(contenedor, indice){
    let inputDesde = document.getElementById(`rinicio_palets_${contenedor}_${indice}`)
    let inputHasta = document.getElementById(`rfin_palets_${contenedor}_${indice}`)

    if(parseInt(inputHasta.value) < parseInt(inputDesde.value) && inputHasta.value != ''){
        alert("El campo Hasta debe ser mayor o igual que el campo Desde")
        inputHasta.value = ''
        inputHasta.focus();
    }
            
}

function modificarHastaPalets(event, contenedor, indice){
    const valor = event.target.value;
    
    if(valor != '' && valor > 0){
        
        let inputDesde = document.getElementById(`rinicio_palets_${contenedor}_${indice + 1}`)
        let inputHasta = document.getElementById(`rfin_palets_${contenedor}_${indice + 1}`)
        
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

function eliminarFilaPalets(event, contenedor){
    event.preventDefault();

    //ELIMINO LA FILA
    event.target.parentNode.parentNode.parentNode.remove()
    //DISMINUYO EL CONTADOR
    esquema[`sub_esquema_${contenedor}`].indicePalets = esquema[`sub_esquema_${contenedor}`].indicePalets - 1;
    const indicePalets = esquema[`sub_esquema_${contenedor}`].indicePalets
    //TOMO EL BOTON QUE SE ENCUENTRA EN LA FILA SUPERIOR Y LO ACTIVO

    if(indicePalets > 0){
        //SI EL INDICE ES MAYOR QUE CERO SIGNIFICA QUE HAY UN BOTON PARA ACTIVAR
        const btnAnterior = document.getElementById(`btn_eliminar_palets_${contenedor}_${indicePalets}`)
        btnAnterior.removeAttribute('disabled');
    }else{
        //SI EL INDICE ES CERO OCULTO DIRECTAMENTE EL PANEL
        $(`#rangos_valor_palets_${contenedor}`).css('display', 'none')
    }
}

function noPuntoComa(event) {
    let e = event || window.event;
    let key = e.keyCode || e.which;

    if ( key === 110 || key === 190 || key === 188 ) {     
       e.preventDefault();     
    }
}