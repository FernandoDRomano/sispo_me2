/*
    USO LA MISMA METODOLOGIA PARA LA GESTIÓN DE FLOTAS, YA QUE TENGO QUE CONTROLAR
    POR CADA SUB ESQUEMA QUE OPCIONES SE SELECCIONARON
*/
let gestionFlotaSel = {}
let gestionFlota = {}

function crearFilaGestionFlota(contenedor){  
    //PARA OBTENER EL SUB ESQUEMA CON EL CUAL TRABAJAR
    const sub_esquema = `sub_esquema_${contenedor}`
    
    let indiceAnterior = esquema[`${sub_esquema}`].indiceGestionFlota;
    esquema[`${sub_esquema}`].indiceGestionFlota = esquema[`${sub_esquema}`].indiceGestionFlota + 1

    let indice = esquema[`${sub_esquema}`].indiceGestionFlota;

    contenedorGestionFlota = document.getElementById(`rangos_valor_gestion_flota_${contenedor}`)
    const id = contenedorGestionFlota.id

    //PARA CREAR ESTRUCTURA DE ELEMENTOS SELECCIONADOS DE CADA GESTION DE FLOTA DIFERENTE
    const gf = `sub_esquema_${contenedor}`
    gestionFlotaSel[gf] = []
    gestionFlota[gf] = [
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
    
    //PARA DESABILITAR LOS BOTONES DE ELIMINAR AL CREARSE UNO NUEVO
    if(indice > 1){

        let inputPrecio = document.getElementById(`precio_gf_${contenedor}_${indiceAnterior}`).value
        let inputTipo = document.getElementById(`opcion_horas_gf_${contenedor}_${indiceAnterior}`).value

        if(inputPrecio != '' && inputTipo != 0){
            //const btns = document.getElementsByClassName('eliminar_fila_gestion_flota')
            const btns = $(`#${id} .campoDinamico_gestion_flota .eliminar_fila_gestion_flota`)

            for (let index = 0; index < btns.length; index++) {
                const element = btns[index];
                element.setAttribute('disabled', true);
            }

            const input_selects = $(`#${id} .campoDinamico_gestion_flota .select-tipo-vehiculo`)

            for (let index = 0; index < input_selects.length; index++) {
                const element = input_selects[index];
                element.classList.add('disabled')
            }

        }else{
            alert('Debe completar todos los campos para continuar')
            esquema[`${sub_esquema}`].indiceGestionFlota = esquema[`${sub_esquema}`].indiceGestionFlota - 1
            return;
        }


    }

    //let selects = document.getElementsByClassName('select-tipo-vehiculo')
    let selects = $(`#${id} .campoDinamico_gestion_flota .select-tipo-vehiculo`) 

    for (let index = 0; index < selects.length; index++) {
        const item = selects[index];
        gestionFlotaSel[gf].push(item.value)
    }

    let options = ''

    for (let index = 0; index < gestionFlotaSel[gf].length; index++) {
        const element = gestionFlotaSel[gf][index];

        for(let j = 0; j < gestionFlota[gf].length; j++){
            if(gestionFlota[gf][j].value == element){
                gestionFlota[gf][j].disabled = true;
            }
        }

    }

    for(let i = 0; i < gestionFlota[gf].length; i++){
        options += `<option style="${gestionFlota[gf][i].disabled ? 'background: #bcb7b7;' : '' }" ${gestionFlota[gf][i].disabled ? 'disabled' : ''} value="${gestionFlota[gf][i].value}">${gestionFlota[gf][i].name}</option>`
    }

    let html = `
                        <div class="row campoDinamico_gestion_flota">
							<div class="col-xs-6">
								<div class="form-group" >
									<label>Vehiculo // Hora</label>
									<select id="opcion_horas_gf_${contenedor}_${indice}" name="opcion_horas_gf_${contenedor}_${indice}" onchange="" class="form-control filtro-group select-tipo-vehiculo">
										<option value="0">Seleccione</option>
                                        ${options}
									</select>
                                    
								</div>
							</div>

							<div class="col-xs-3">
								<div class="form-group">
									<label>Precio</label>
									<input placeholder="$" type="number" id="precio_gf_${contenedor}_${indice}" name="precio_gf_${contenedor}_${indice}" class="form-control filtro-group" step="0.01">
								</div>
							</div>

                            <!--Boton - (para eliminar el campo) -->
                            <div class="col-xs-1">
                                <div class="form-group">
                                    <br>
                                    <a id="btn_eliminar_gestion_flota_${contenedor}_${indice}" onclick="eliminarFilaGestionFlota(event, ${contenedor})" href="javascript:void(0);" class="eliminar_fila_gestion_flota agregar_campos btn btn-danger btn-submit" title="Remove field">-</a>
                                </div>
                            </div>
						</div>

    `

    //gestionFlotaSeleccionado = [];
    gestionFlotaSel[gf] = []

    contenedorGestionFlota.insertAdjacentHTML('beforeend', html);

}


function eliminarFilaGestionFlota(event, contenedor){
    event.preventDefault();

    //ELIMINO LA FILA
    event.target.parentNode.parentNode.parentNode.remove()
    //DISMINUYO EL CONTADOR
    esquema[`sub_esquema_${contenedor}`].indiceGestionFlota = esquema[`sub_esquema_${contenedor}`].indiceGestionFlota - 1;
    const indiceGestionFlota = esquema[`sub_esquema_${contenedor}`].indiceGestionFlota
    //TOMO EL BOTON QUE SE ENCUENTRA EN LA FILA SUPERIOR Y LO ACTIVO

    if(indiceGestionFlota > 0){
        //SI EL INDICE ES MAYOR QUE CERO SIGNIFICA QUE HAY UN BOTON PARA ACTIVAR
        const btnAnterior = document.getElementById(`btn_eliminar_gestion_flota_${contenedor}_${indiceGestionFlota}`)
        btnAnterior.removeAttribute('disabled');

        const selectAnterior = document.getElementById(`opcion_horas_gf_${contenedor}_${indiceGestionFlota}`)
        //selectAnterior.removeAttribute('disabled');
        selectAnterior.classList.remove('disabled')

    }else{
        //SI EL INDICE ES CERO OCULTO DIRECTAMENTE EL PANEL
        $(`#rangos_valor_gestion_flota_${contenedor}`).css('display', 'none')
    }
}
