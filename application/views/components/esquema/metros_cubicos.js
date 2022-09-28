function crearFilaMetroCubico(contenedor){
    //PARA OBTENER EL SUB ESQUEMA CON EL CUAL TRABAJAR
    const sub_esquema = `sub_esquema_${contenedor}`
    
    let indiceAnterior = esquema[`${sub_esquema}`].indiceMetroCubico;
    esquema[`${sub_esquema}`].indiceMetroCubico = esquema[`${sub_esquema}`].indiceMetroCubico + 1
    
    let indice = esquema[`${sub_esquema}`].indiceMetroCubico;
    
    contenedorMetroCubico = document.getElementById(`rangos_valor_metro_cubico_${contenedor}`)
    const id = contenedorMetroCubico.id

    //PARA DESABILITAR LOS BOTONES DE ELIMINAR AL CREARSE UNO NUEVO
    if(indice > 1){

        let inputDesde = document.getElementById(`rinicio_mc_${contenedor}_${indiceAnterior}`)
        let inputHasta = document.getElementById(`rfin_mc_${contenedor}_${indiceAnterior}`)
        let inputPrecio = document.getElementById(`precio_mc_${contenedor}_${indiceAnterior}`)
        let inputTipo = document.getElementById(`opciones_tipo_mc_${contenedor}_${indiceAnterior}`)

        if(inputDesde.value != '' && inputHasta.value != '' && inputPrecio.value != '' && inputTipo.value != 0){

            if(parseFloat(inputHasta.value) <= parseFloat(inputDesde.value)){
                alert("El campo Hasta debe ser mayor que el campo Desde")
                inputHasta.value = ''
                inputHasta.focus()
                esquema[`${sub_esquema}`].indiceMetroCubico = esquema[`${sub_esquema}`].indiceMetroCubico - 1;
                return;
            }
            
            //const btns = document.getElementsByClassName('eliminar_fila_metro_cubico')
            const btns = $(`#${id} .campoDinamico_metro_cubico .eliminar_fila_metro_cubico`)

            for (let index = 0; index < btns.length; index++) {
                const element = btns[index];
                element.setAttribute('disabled', true);
            }

        }else{
            alert('Debe completar todos los campos para continuar')
            esquema[`${sub_esquema}`].indiceMetroCubico = esquema[`${sub_esquema}`].indiceMetroCubico - 1;
            return;
        }


    }

    let html = `
                        <div class="row campoDinamico_metro_cubico">
							<div class="col-xs-3">
								<div class="form-group">
									<label>Desde</label>
									<input id="rinicio_mc_${contenedor}_${indice}" 
                                    ${indice > 1 ? 'readonly' : ''} 
                                    value="${indice > 1 ? getValorFilaAnteriorMetroCubico(contenedor, indice, 'rfin_mc') : ''}"
                                    placeholder="Ej.: 10" type="number" name="rinicio_mc_${contenedor}_${indice}" 
                                    class="form-control filtro-group">
								</div>
							</div>
							<div class="col-xs-3">
								<div class="form-group">
									<label>Hasta</label>
									<input id="rfin_mc_${contenedor}_${indice}" onblur="validarMaximoMetroCubico(${contenedor}, ${indice})" onkeyup="modificarHastaMetroCubico(event, ${contenedor}, ${indice})" placeholder="Ej.: 20" type="number" name="rfin_mc_${contenedor}_${indice}" class="form-control filtro-group">
								</div>
							</div>
							<div class="col-xs-3">
								<div class="form-group">
									<label>Precio</label>
									<input style="" placeholder="$" type="number" id="precio_mc_${contenedor}_${indice}" name="precio_mc_${contenedor}_${indice}" class="form-control filtro-group" step="0.01">
								</div>
							</div>
							<div class="col-xs-2">
								<div class="form-group">
									<label>Tipo</label>
									<select id="opciones_tipo_mc_${contenedor}_${indice}" name="opciones_tipo_mc_${contenedor}_${indice}" onchange="" class="form-control filtro-group">
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
                                    <a id="btn_eliminar_metro_cubico_${contenedor}_${indice}" onclick="eliminarFilaMetroCubico(event, ${contenedor})" href="javascript:void(0);" class="eliminar_fila_metro_cubico agregar_campos btn btn-danger btn-submit" title="Remove field">-</a>
                                </div>
                            </div>
						</div>

    `

    contenedorMetroCubico.insertAdjacentHTML('beforeend', html);
}

function getValorFilaAnteriorMetroCubico(contenedor, indice, id){
    indice = indice - 1;
    let value = document.getElementById(`${id}_${contenedor}_${indice}`).value

    return `${(parseFloat(value) + 0.01).toFixed(2)}`
}

function validarMaximoMetroCubico(contenedor, indice){
    let inputDesde = document.getElementById(`rinicio_mc_${contenedor}_${indice}`)
    let inputHasta = document.getElementById(`rfin_mc_${contenedor}_${indice}`)

    if(parseFloat(inputHasta.value) <= parseFloat(inputDesde.value) && inputHasta.value != ''){
        alert("El campo Hasta debe ser mayor que el campo Desde")
        inputHasta.value = ''
        inputHasta.focus();
    }
            
}

function modificarHastaMetroCubico(event, contenedor, indice){
    const valor = event.target.value;
    
    if(valor != '' && valor > 0){
        
        let inputDesde = document.getElementById(`rinicio_mc_${contenedor}_${indice + 1}`)
        let inputHasta = document.getElementById(`rfin_mc_${contenedor}_${indice + 1}`)
        
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

function eliminarFilaMetroCubico(event, contenedor){
    event.preventDefault();

    //ELIMINO LA FILA
    event.target.parentNode.parentNode.parentNode.remove()
    //DISMINUYO EL CONTADOR
    esquema[`sub_esquema_${contenedor}`].indiceMetroCubico = esquema[`sub_esquema_${contenedor}`].indiceMetroCubico - 1;
    const indiceMetroCubico = esquema[`sub_esquema_${contenedor}`].indiceMetroCubico
    //TOMO EL BOTON QUE SE ENCUENTRA EN LA FILA SUPERIOR Y LO ACTIVO

    if(indiceMetroCubico > 0){
        //SI EL INDICE ES MAYOR QUE CERO SIGNIFICA QUE HAY UN BOTON PARA ACTIVAR
        const btnAnterior = document.getElementById(`btn_eliminar_metro_cubico_${contenedor}_${indiceMetroCubico}`)
        btnAnterior.removeAttribute('disabled');
    }else{
        //SI EL INDICE ES CERO OCULTO DIRECTAMENTE EL PANEL
        $(`#rangos_valor_metro_cubico_${contenedor}`).css('display', 'none')
    }
}