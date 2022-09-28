function crearFilaCobranza(contenedor){
    //PARA OBTENER EL SUB ESQUEMA CON EL CUAL TRABAJAR
    const sub_esquema = `sub_esquema_${contenedor}`
    
    esquema[`${sub_esquema}`].indiceCobranza = esquema[`${sub_esquema}`].indiceCobranza + 1
    let indice = esquema[`${sub_esquema}`].indiceCobranza;
    
    contenedorCobranza = document.getElementById(`rangos_cobranza_${contenedor}`)
    const id = contenedorCobranza.id

    //PARA DESABILITAR LOS BOTONES DE ELIMINAR AL CREARSE UNO NUEVO
    if(indice > 1){
        alert("Solo se puede agregar una regla de cobranza")
        esquema[`${sub_esquema}`].indiceCobranza = esquema[`${sub_esquema}`].indiceCobranza - 1
        return;
    }

    let html = `
                        <div class="row campoDinamico_cobranza">
                            <div class="col-xs-3">
								<div class="form-group">
									<label>Tipo</label>
                                    <select id="tipo_cobranza_${contenedor}_${indice}" name="tipo_cobranza_${contenedor}_${indice}" class="form-control filtro-group">
                                        <option value="">Seleccione</option>
                                        <option value="porcentaje">Porcentaje</option>
                                        <option value="monto">Monto Fijo</option>                                    
                                    </select>
								</div>
							</div>
							<div class="col-xs-3">
								<div class="form-group">
									<label>Valor</label>
									<input style="" placeholder="Ingrese su valor" type="number" id="valor_cobranza_${contenedor}_${indice}" name="valor_cobranza_${contenedor}_${indice}" class="form-control filtro-group" step="0.01">
								</div>
							</div>
                            <div class="col-xs-1">
                                <div class="form-group">
                                    <br>
                                    <a id="btn_eliminar_cobranza__${contenedor}_${indice}" onclick="eliminarFilaCobranza(event, ${contenedor})" href="javascript:void(0);" class="eliminar_fila_cobranza agregar_campos btn btn-danger btn-submit" title="Remove field">-</a>
                                </div>
                            </div>
						</div>

    `

    contenedorCobranza.insertAdjacentHTML('beforeend', html);
}

function eliminarFilaCobranza(event, contenedor){
    event.preventDefault();

    //ELIMINO LA FILA
    event.target.parentNode.parentNode.parentNode.remove()
    //DISMINUYO EL CONTADOR
    esquema[`sub_esquema_${contenedor}`].indiceCobranza = esquema[`sub_esquema_${contenedor}`].indiceCobranza - 1;

    $(`#rangos_cobranza_${contenedor}`).css('display', 'none')
}
