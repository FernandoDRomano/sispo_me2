function crearFilasPorDistancia(contenedor){
    //PARA OBTENER EL SUB ESQUEMA CON EL CUAL TRABAJAR
    const sub_esquema = `sub_esquema_${contenedor}`
    
    let indiceAnterior = esquema[`${sub_esquema}`].indiceDistancia;
    esquema[`${sub_esquema}`].indiceDistancia = esquema[`${sub_esquema}`].indiceDistancia + 1

    let indice = esquema[`${sub_esquema}`].indiceDistancia;

    contenedorDistancia = document.getElementById(`rangos_precios_distancia_${contenedor}`)
    const id = contenedorDistancia.id

    //PARA DESABILITAR LOS BOTONES DE ELIMINAR AL CREARSE UNO NUEVO
    if(indice > 1){

        let inputDesde = document.getElementById(`rinicio_distancia_${contenedor}_${indiceAnterior}`)
        let inputHasta = document.getElementById(`rfin_distancia_${contenedor}_${indiceAnterior}`)
        let inputPrecio = document.getElementById(`precio_distancia_${contenedor}_${indiceAnterior}`)

        if(inputDesde.value != '' && inputHasta.value != '' && inputPrecio.value != ''){

            if(parseFloat(inputHasta.value) <= parseFloat(inputDesde.value)){
                alert("El campo Hasta debe ser mayor que el campo Desde")
                inputHasta.value = ''
                inputHasta.focus()
                esquema[`${sub_esquema}`].indiceDistancia = esquema[`${sub_esquema}`].indiceDistancia - 1;
                return;
            }
            
            //const btns = document.getElementsByClassName('eliminar_fila_distancia')
            const btns = $(`#${id} .campoDinamico_distancia .eliminar_fila_distancia`)

            for (let index = 0; index < btns.length; index++) {
                const element = btns[index];
                element.setAttribute('disabled', true);
            }

        }else{
            alert('Debe completar todos los campos para continuar')
            esquema[`${sub_esquema}`].indiceDistancia = esquema[`${sub_esquema}`].indiceDistancia - 1;
            return;
        }


    }

    let html = `
            <div class="row campoDinamico_distancia align-items-center">
				
                <div class="col-xs-3">
					<div class="form-group">
						<label>Desde</label>
						<input id="rinicio_distancia_${contenedor}_${indice}"  
                                value="${indice > 1 ? getValorFilaAnteriorDistancia(contenedor, indice, 'rfin_distancia') : ''}"
                                ${indice > 1 ? 'readonly' : ''} 
                                placeholder="Ej.: 10" type="number" name="rinicio_distancia_${contenedor}_${indice}" 
                                class="form-control filtro-group">
					</div>
				</div>
							
                <div class="col-xs-3">
					<div class="form-group">
						<label>Hasta</label>
						<input id="rfin_distancia_${contenedor}_${indice}" onblur="validarMaximoDistancia(${contenedor}, ${indice})" onkeyup="modificarHastaDistancia(event, ${contenedor}, ${indice})" placeholder="Ej.: 20" type="number" name="rfin_distancia_${contenedor}_${indice}" class="form-control filtro-group">
					</div>
				</div>

				<div class="col-xs-3">
					<div class="form-group">
						<label>Precio</label>
						<input style="" placeholder="$" type="number" id="precio_distancia_${contenedor}_${indice}" name="precio_distancia_${contenedor}_${indice}" class="form-control filtro-group" step="0.01">
					</div>
				</div>
				
                <!--Boton - (para eliminar el campo) -->
				<div class="col-xs-1">
					<div class="form-group">
						<br>
						<a id="btn_eliminar_distancia_${contenedor}_${indice}" onclick="eliminarFilaDistancia(event, ${contenedor})" href="javascript:void(0);" class="eliminar_fila_distancia agregar_campos btn btn-danger btn-submit" title="Remove field">-</a>
					</div>
				</div>
			</div>
    `

    contenedorDistancia.insertAdjacentHTML('beforeend', html);
}

function getValorFilaAnteriorDistancia(contenedor, indice, id){
    indice = indice - 1;
    let value = document.getElementById(`${id}_${contenedor}_${indice}`).value

    return `${(parseFloat(value) + 0.01).toFixed(2)}`
}

function validarMaximoDistancia(contenedor, indice){
    let inputDesde = document.getElementById(`rinicio_distancia_${contenedor}_${indice}`)
    let inputHasta = document.getElementById(`rfin_distancia_${contenedor}_${indice}`)

    if(parseFloat(inputHasta.value) <= parseFloat(inputDesde.value) && inputHasta.value != ''){
        alert("El campo Hasta debe ser mayor que el campo Desde")
        inputHasta.value = ''
        inputHasta.focus();
    }
            
}

function modificarHastaDistancia(event, contenedor, indice){
    const valor = event.target.value;
    
    if(valor != '' && valor > 0){
        
        let inputDesde = document.getElementById(`rinicio_distancia_${contenedor}_${indice + 1}`)
        let inputHasta = document.getElementById(`rfin_distancia_${contenedor}_${indice + 1}`)
        
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

function eliminarFilaDistancia(event, contenedor){
    event.preventDefault();

    //ELIMINO LA FILA
    event.target.parentNode.parentNode.parentNode.remove()
    //DISMINUYO EL CONTADOR
    esquema[`sub_esquema_${contenedor}`].indiceDistancia = esquema[`sub_esquema_${contenedor}`].indiceDistancia - 1;
    const indiceDistancia = esquema[`sub_esquema_${contenedor}`].indiceDistancia
    //TOMO EL BOTON QUE SE ENCUENTRA EN LA FILA SUPERIOR Y LO ACTIVO

    if(indiceDistancia > 0){
        //SI EL INDICE ES MAYOR QUE CERO SIGNIFICA QUE HAY UN BOTON PARA ACTIVAR
        const btnAnterior = document.getElementById(`btn_eliminar_distancia_${contenedor}_${indiceDistancia}`)
        btnAnterior.removeAttribute('disabled');
    }else{
        //SI EL INDICE ES CERO OCULTO DIRECTAMENTE EL PANEL
        $(`#rangos_precios_distancia_${contenedor}`).css('display', 'none')
    }
}