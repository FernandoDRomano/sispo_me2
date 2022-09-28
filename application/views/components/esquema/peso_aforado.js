function crearFilaPesoAforado(contenedor){
    //PARA OBTENER EL SUB ESQUEMA CON EL CUAL TRABAJAR
    const sub_esquema = `sub_esquema_${contenedor}`
    
    esquema[`${sub_esquema}`].indicePesoAforado = esquema[`${sub_esquema}`].indicePesoAforado + 1
    let indice = esquema[`${sub_esquema}`].indicePesoAforado;
    
    contenedorPesoAforado = document.getElementById(`rangos_peso_aforado_${contenedor}`)
    const id = contenedorPesoAforado.id

    //PARA DESABILITAR LOS BOTONES DE ELIMINAR AL CREARSE UNO NUEVO
    if(indice > 1){
        alert("Solo se puede agregar una regla de peso aforado")
        esquema[`sub_esquema_${contenedor}`].indicePesoAforado = esquema[`sub_esquema_${contenedor}`].indicePesoAforado - 1;
        return;
    }

    let html = `
                        <div class="row campoDinamico_peso_aforado">
							<div class="col-xs-3">
								<div class="form-group">
									<label>Valor</label>
									<input style="" placeholder="Valor" type="number" id="valor_peso_aforado_${contenedor}_${indice}" name="valor_peso_aforado_${contenedor}_${indice}" class="form-control filtro-group" step="1">
								</div>
							</div>
                            <div class="col-xs-1">
                                <div class="form-group">
                                    <br>
                                    <a id="btn_eliminar_peso_aforado_${contenedor}_${indice}" onclick="eliminarFilaPesoAforado(event, ${contenedor})" href="javascript:void(0);" class="eliminar_fila_peso_aforado agregar_campos btn btn-danger btn-submit" title="Remove field">-</a>
                                </div>
                            </div>
						</div>

    `

    contenedorPesoAforado.insertAdjacentHTML('beforeend', html);
}

function eliminarFilaPesoAforado(event, contenedor){
    event.preventDefault();

    //ELIMINO LA FILA
    event.target.parentNode.parentNode.parentNode.remove()
    //DISMINUYO EL CONTADOR
    esquema[`sub_esquema_${contenedor}`].indicePesoAforado = esquema[`sub_esquema_${contenedor}`].indicePesoAforado - 1;

    $(`#rangos_peso_aforado_${contenedor}`).css('display', 'none')
}
