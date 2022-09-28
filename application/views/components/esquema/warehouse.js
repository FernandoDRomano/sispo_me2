function crearFilaWareHouse(contenedor){
    //PARA OBTENER EL SUB ESQUEMA CON EL CUAL TRABAJAR
    const sub_esquema = `sub_esquema_${contenedor}`
    
    let indiceAnterior = esquema[`${sub_esquema}`].indiceWareHouse;
    esquema[`${sub_esquema}`].indiceWareHouse = esquema[`${sub_esquema}`].indiceWareHouse + 1
    
    let indice = esquema[`${sub_esquema}`].indiceWareHouse;
    
    contenedorWarehouse = document.getElementById(`rangos_valor_warehouse_${contenedor}`)
    const id = contenedorWarehouse.id

    //PARA DESABILITAR LOS BOTONES DE ELIMINAR AL CREARSE UNO NUEVO
    if(indice > 1){

        let inputDesde = document.getElementById(`rinicio_wh_${contenedor}_${indiceAnterior}`)
        let inputHasta = document.getElementById(`rfin_wh_${contenedor}_${indiceAnterior}`)
        let inputPrecio = document.getElementById(`precio_wh_${contenedor}_${indiceAnterior}`)

        if(inputDesde.value != '' && inputHasta.value != '' && inputPrecio.value != ''){

            if(parseInt(inputHasta.value) <= parseInt(inputDesde.value)){
                alert("El campo Hasta debe ser mayor que el campo Desde")
                inputHasta.value = ''
                inputHasta.focus()
                esquema[`${sub_esquema}`].indiceWareHouse = esquema[`${sub_esquema}`].indiceWareHouse - 1;
                return;
            }
            
            //const btns = document.getElementsByClassName('eliminar_fila_wh')
            const btns = $(`#${id} .campoDinamico_warehouse .eliminar_fila_wh`)

            for (let index = 0; index < btns.length; index++) {
                const element = btns[index];
                element.setAttribute('disabled', true);
            }

        }else{
            alert('Debe completar todos los campos para continuar')
            esquema[`${sub_esquema}`].indiceWareHouse = esquema[`${sub_esquema}`].indiceWareHouse - 1;
            return;
        }


    }

    let html = `
                        <div class="row campoDinamico_warehouse">
                            
							<div class="col-xs-3">
								<div class="form-group">
									<label>Cantidad</label>
									<input id="rinicio_wh_${contenedor}_${indice}" 
                                    ${indice > 1 ? 'readonly' : ''} 
                                    value="${indice > 1 ? getValorFilaAnteriorWareHouse(contenedor, indice, 'rfin_wh') : ''}"
                                    placeholder="Ej.: 10" type="number" name="rinicio_wh_${contenedor}_${indice}" 
                                    class="form-control filtro-group">
								</div>
							</div>

                            <div class="col-xs-3">
                                <div class="form-group">
                                    <label>Hasta</label>
                                    <input id="rfin_wh_${contenedor}_${indice}" onkeydown="noPuntoComa(event)" onblur="validarMaximoWareHouse(${contenedor}, ${indice})" onkeyup="modificarHastaWareHouse(event, ${contenedor}, ${indice})" placeholder="Ej.: 20" type="number" name="rfin_wh_${contenedor}_${indice}" class="form-control filtro-group">
                                </div>
                            </div>

							<div class="col-xs-3">
								<div class="form-group">
									<label>Precio</label>
									<input placeholder="$" type="number" id="precio_wh_${contenedor}_${indice}" name="precio_wh_${contenedor}_${indice}" class="form-control filtro-group" step="0.01">
								</div>
							</div>
                            <!--Boton - (para eliminar el campo) -->
                            <div class="col-xs-1">
                                <div class="form-group">
                                    <br>
                                    <a id="btn_eliminar_wh_${contenedor}_${indice}" onclick="eliminarFilaWareHouse(event, ${contenedor})" href="javascript:void(0);" class="eliminar_fila_wh agregar_campos btn btn-danger btn-submit" title="Remove field">-</a>
                                </div>
                            </div>

						</div>


    `

    contenedorWarehouse.insertAdjacentHTML('beforeend', html);
}

function getValorFilaAnteriorWareHouse(contenedor, indice, id){
    indice = indice - 1;
    let value = document.getElementById(`${id}_${contenedor}_${indice}`).value

    return `${parseInt(value) + 1}`
}

function validarMaximoWareHouse(contenedor, indice){
    let inputDesde = document.getElementById(`rinicio_wh_${contenedor}_${indice}`)
    let inputHasta = document.getElementById(`rfin_wh_${contenedor}_${indice}`)

    if(parseFloat(inputHasta.value) <= parseFloat(inputDesde.value) && inputHasta.value != ''){
        alert("El campo Hasta debe ser mayor que el campo Desde")
        inputHasta.value = ''
        inputHasta.focus();
    }
            
}

function modificarHastaWareHouse(event, contenedor, indice){
    const valor = event.target.value;
    
    if(valor != '' && valor > 0){
        
        let inputDesde = document.getElementById(`rinicio_wh_${contenedor}_${indice + 1}`)
        let inputHasta = document.getElementById(`rfin_wh_${contenedor}_${indice + 1}`)
        
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

function eliminarFilaWareHouse(event, contenedor){
    event.preventDefault();

    //ELIMINO LA FILA
    event.target.parentNode.parentNode.parentNode.remove()
    //DISMINUYO EL CONTADOR
    esquema[`sub_esquema_${contenedor}`].indiceWareHouse = esquema[`sub_esquema_${contenedor}`].indiceWareHouse - 1;
    const indiceWareHouse = esquema[`sub_esquema_${contenedor}`].indiceWareHouse
    //TOMO EL BOTON QUE SE ENCUENTRA EN LA FILA SUPERIOR Y LO ACTIVO

    if(indiceWareHouse > 0){
        //SI EL INDICE ES MAYOR QUE CERO SIGNIFICA QUE HAY UN BOTON PARA ACTIVAR
        const btnAnterior = document.getElementById(`btn_eliminar_wh_${contenedor}_${indiceWareHouse}`)
        btnAnterior.removeAttribute('disabled');
    }else{
        //SI EL INDICE ES CERO OCULTO DIRECTAMENTE EL PANEL
        $(`#rangos_valor_warehouse_${contenedor}`).css('display', 'none')
    }
}

function noPuntoComa(event) {
    let e = event || window.event;
    let key = e.keyCode || e.which;

    if ( key === 110 || key === 190 || key === 188 ) {     
       e.preventDefault();     
    }
}