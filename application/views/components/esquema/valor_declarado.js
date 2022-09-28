function crearFilaValorDeclarado(contenedor){
    //PARA OBTENER EL SUB ESQUEMA CON EL CUAL TRABAJAR
    const sub_esquema = `sub_esquema_${contenedor}`
    
    esquema[`${sub_esquema}`].indiceValorDeclarado = esquema[`${sub_esquema}`].indiceValorDeclarado + 1
    let indice = esquema[`${sub_esquema}`].indiceValorDeclarado;
        
    contenedorValorDeclarado = document.getElementById(`rangos_valor_declarado_${contenedor}`)
    const id = contenedorValorDeclarado.id

    //PARA DESABILITAR LOS BOTONES DE ELIMINAR AL CREARSE UNO NUEVO
    if(indice > 1){
        alert("Solo se puede agregar una regla de valor declarado")
        esquema[`${sub_esquema}`].contenedorValorDeclarado = esquema[`${sub_esquema}`].contenedorValorDeclarado - 1
        return;
    }

    let html = `
                        <div class="row campoDinamico_valor_declarado">

                            <div class="col-xs-3">
								<div class="form-group">
									<label>Tipo</label>
                                    <select id="tipo_valor_declarado_${contenedor}_${indice}" name="tipo_valor_declarado_${contenedor}_${indice}" class="form-control filtro-group">
                                        <option value="">Seleccione</option>
                                        <option value="porcentaje">Porcentaje</option>
                                        <option value="monto">Monto Fijo</option>                                    
                                    </select>
								</div>
							</div>

							<div class="col-xs-3">
								<div class="form-group">
									<label>Valor</label>
									<input style="" placeholder="Ingrese su valor" type="number" id="valor_declarado_${contenedor}_${indice}" name="valor_declarado_${contenedor}_${indice}" class="form-control filtro-group" step="0.01">
								</div>
							</div>
                            
                            <div class="col-xs-1">
                                <div class="form-group">
                                    <br>
                                    <a id="btn_eliminar_valor_declarado_${contenedor}_${indice}" onclick="eliminarFilaValorDeclarado(event, ${contenedor})" href="javascript:void(0);" class="eliminar_fila_valor_declarado agregar_campos btn btn-danger btn-submit" title="Remove field">-</a>
                                </div>
                            </div>
						</div>

    `

    contenedorValorDeclarado.insertAdjacentHTML('beforeend', html);
}

function eliminarFilaValorDeclarado(event, contenedor){
    event.preventDefault();

    //ELIMINO LA FILA
    event.target.parentNode.parentNode.parentNode.remove()
    //DISMINUYO EL CONTADOR
    esquema[`sub_esquema_${contenedor}`].indiceValorDeclarado = esquema[`sub_esquema_${contenedor}`].indiceValorDeclarado - 1;

    $(`#rangos_valor_declarado_${contenedor}`).css('display', 'none')
}