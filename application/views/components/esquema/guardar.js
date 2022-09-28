function guardar(event, ruta = null){
    event.preventDefault()

    const subEsquemas = document.getElementsByClassName("sub-esquema")
    if(!validarSubEsquemas(subEsquemas)){
        return;
    }

    guardarDatos(ruta)
}

function guardarDatos(ruta){
    //CONTAR LOS SUB ESQUEMAS PARA ENVIAR AL BACKEND
    const cantidadSubEsquemas = document.getElementsByClassName('sub-esquema').length
    const esquemas = document.getElementsByClassName('sub-esquema')

    const data = formatearData(esquemas, cantidadSubEsquemas);

    const dominio = document.domain;

    let url = '';

    if(ruta){
        url = `//${dominio}/esquema/esquema/${ruta}`;
    }

    fetch(url, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
         body: JSON.stringify(data)
    })
    .then(resp => resp.json())
    .then(data => {
        if(data.status == 201 || data.status == 204){
            console.log(data)
            window.location.replace(`//${dominio}/esquema/Esquema`)
        }

        if(data.status == 500){
            const errors = data.errors
            errors.forEach(el => {
                alert(el)
                console.error(el)
            })
        }

    })
    .catch(error => console.error(error))
}

function formatearData(esquemas, cantidadSubEsquemas){

    let data = {
        nombre: document.getElementById("nombre").value,
        cantidad: cantidadSubEsquemas
    }

    for (let index = 0; index < esquemas.length; index++) {
        const element = esquemas[index];
        
        let cantidadKilos = $(`#${element.id} .campoDinamico_kilos`).length
        let cantidadBultos = $(`#${element.id} .campoDinamico_bultos`).length
        let cantidadMetroCubico = $(`#${element.id} .campoDinamico_metro_cubico`).length
        let cantidadPalets = $(`#${element.id} .campoDinamico_palets`).length
        let cantidadGestionFlota = $(`#${element.id} .campoDinamico_gestion_flota`).length
        let cantidadWareHouse = $(`#${element.id} .campoDinamico_warehouse`).length
        let cantidadCobranza = $(`#${element.id} .campoDinamico_cobranza`).length
        let cantidadValorDeclarado = $(`#${element.id} .campoDinamico_valor_declarado`).length
        let cantidadPesoAforado = $(`#${element.id} .campoDinamico_peso_aforado`).length
        let cantidadDistancia = $(`#${element.id} .campoDinamico_distancia`).length

        data[`sub_esquema_${index}`] = {
            idSubEsquema: element.id[element.id.length - 1],
            zonaA: $(`#${element.id} .zona-origen`).val(),
            zonaB: $(`#${element.id} .zona-destino`).val(),
            plantilla_id: $(`#plantilla_${index + 1}`).val(),
            
            cantidadKilos,
            cantidadBultos,
            cantidadMetroCubico,
            cantidadPalets,
            cantidadGestionFlota,
            cantidadWareHouse,
            cantidadCobranza,
            cantidadValorDeclarado,
            cantidadPesoAforado,
            cantidadDistancia
        }

        if(cantidadKilos > 0){
            let arr = []

            
            for(let i = 0; i < cantidadKilos; i++){

                let kilos = {
                    desde: $(`#rinicio_kilos_${element.id[element.id.length - 1]}_${i + 1}`).val(),
                    hasta: $(`#rfin_kilos_${element.id[element.id.length - 1]}_${i + 1}`).val(),
                    precio: $(`#precio_kilos_${element.id[element.id.length - 1]}_${i + 1}`).val(),
                    bandera: $(`#opciones_tipo_kilos_${element.id[element.id.length - 1]}_${i + 1}`).val(),
                }

                arr.push(kilos)
            }
            
            data[`sub_esquema_${index}`]['kilos'] = { 
                kilos: arr
            }
            
        }

        if(cantidadBultos > 0){
            let arr = []

            
            for(let i = 0; i < cantidadBultos; i++){

                let bultos = {
                    desde: $(`#rinicio_bultos_${element.id[element.id.length - 1]}_${i + 1}`).val(),
                    hasta: $(`#rfin_bultos_${element.id[element.id.length - 1]}_${i + 1}`).val(),
                    precio: $(`#precio_bultos_${element.id[element.id.length - 1]}_${i + 1}`).val(),
                    bandera: $(`#opciones_tipo_bultos_${element.id[element.id.length - 1]}_${i + 1}`).val(),
                }

                arr.push(bultos)
            }
            
            data[`sub_esquema_${index}`]['bultos'] = { 
                bultos: arr
            }
            
        }

        if(cantidadMetroCubico > 0){
            let arr = []

            
            for(let i = 0; i < cantidadMetroCubico; i++){

                let metro = {
                    desde: $(`#rinicio_mc_${element.id[element.id.length - 1]}_${i + 1}`).val(),
                    hasta: $(`#rfin_mc_${element.id[element.id.length - 1]}_${i + 1}`).val(),
                    precio: $(`#precio_mc_${element.id[element.id.length - 1]}_${i + 1}`).val(),
                    bandera: $(`#opciones_tipo_mc_${element.id[element.id.length - 1]}_${i + 1}`).val(),
                }

                arr.push(metro)
            }
            
            data[`sub_esquema_${index}`]['metrosCubicos'] = { 
                metrosCubicos: arr
            }
            
        }

        if(cantidadPalets > 0){
            let arr = []

            
            for(let i = 0; i < cantidadPalets; i++){

                let palets = {
                    desde: $(`#rinicio_palets_${element.id[element.id.length - 1]}_${i + 1}`).val(),
                    hasta: $(`#rfin_palets_${element.id[element.id.length - 1]}_${i + 1}`).val(),
                    precio: $(`#precio_palets_${element.id[element.id.length - 1]}_${i + 1}`).val(),
                    bandera: $(`#opciones_tipo_palets_${element.id[element.id.length - 1]}_${i + 1}`).val(),
                }

                arr.push(palets)
            }
            
            data[`sub_esquema_${index}`]['palets'] = { 
                palets: arr
            }
            
        }

        if(cantidadGestionFlota > 0){
            let arr = []

            
            for(let i = 0; i < cantidadGestionFlota; i++){

                let gestionFlota = {
                    tipo: $(`#opcion_horas_gf_${element.id[element.id.length - 1]}_${i + 1}`).val(),
                    precio: $(`#precio_gf_${element.id[element.id.length - 1]}_${i + 1}`).val(),
                }

                arr.push(gestionFlota)
            }
            
            data[`sub_esquema_${index}`]['gestionFlota'] = { 
                gestionFlota: arr
            }
            
        }

        if(cantidadWareHouse > 0){
            let arr = []

            
            for(let i = 0; i < cantidadWareHouse; i++){

                let warehouse = {
                    desde: $(`#rinicio_wh_${element.id[element.id.length - 1]}_${i + 1}`).val(),
                    hasta: $(`#rfin_wh_${element.id[element.id.length - 1]}_${i + 1}`).val(),
                    precio: $(`#precio_wh_${element.id[element.id.length - 1]}_${i + 1}`).val(),
                }

                arr.push(warehouse)
            }
            
            data[`sub_esquema_${index}`]['warehouse'] = { 
                warehouse: arr
            }
            
        }

        if(cantidadCobranza > 0){

                let cobranza = {
                    tipo: $(`#tipo_cobranza_${element.id[element.id.length - 1]}_1`).val(),
                    valor: $(`#valor_cobranza_${element.id[element.id.length - 1]}_1`).val(),
                }

            data[`sub_esquema_${index}`]['cobranza'] = { 
                cobranza
            }
            
        }

        if(cantidadValorDeclarado > 0){
            
                let valorDeclarado = {
                    tipo: $(`#tipo_valor_declarado_${element.id[element.id.length - 1]}_1`).val(),
                    valor: $(`#valor_declarado_${element.id[element.id.length - 1]}_1`).val(),
                }
            
            data[`sub_esquema_${index}`]['valorDeclarado'] = { 
                valorDeclarado
            }
            
        }

        if(cantidadPesoAforado > 0){

                let pesoAforado = {
                    valor: $(`#valor_peso_aforado_${element.id[element.id.length - 1]}_1`).val(),
                }

            data[`sub_esquema_${index}`]['pesoAforado'] = { 
                pesoAforado
            }
            
        }

        if(cantidadDistancia > 0){
            let arr = []

            
            for(let i = 0; i < cantidadDistancia; i++){

                let distancia = {
                    desde: $(`#rinicio_distancia_${element.id[element.id.length - 1]}_${i + 1}`).val(),
                    hasta: $(`#rfin_distancia_${element.id[element.id.length - 1]}_${i + 1}`).val(),
                    precio: $(`#precio_distancia_${element.id[element.id.length - 1]}_${i + 1}`).val(),
                }

                arr.push(distancia)
            }
            
            data[`sub_esquema_${index}`]['distancia'] = { 
                distancia: arr
            }
            
        }

    }

    return data;
}

function validarSubEsquemas(subEsquemas){
    //VALIDANDO CAMPOS LLENOS
    for (let index = 0; index < subEsquemas.length; index++) {
        const element = subEsquemas[index];
        const id = element.id

        if(!validarNombreEsquema()){
            return false;
        }
        if(!validarZonas(id)){
            return false;
        }

        if(!validarPlantilla(id)){
            return false;
        }
        if(!validarPlantilla(id)){
            return false;
        }
        if(!validarKilos(id)){
            return false;
        }
        if(!validarBultos(id)){
            return false;
        }
        if(!validarPalets(id)){
            return false;
        }
        if(!validarMetrosCubicos(id)){
            return false;
        }
        if(!validarWarehouse(id)){
            return false;
        }
        if(!validarGestionFlota(id)){
            return false;
        }
        if(!validarCobranza(id)){
            return false;
        }
        if(!validarValorDeclarado(id)){
            return false;
        }
        if(!validarPesoAforado(id)){
            return false;
        }
        if(!validarDistancia(id)){
            return false;
        }

    }
    
    if(!validarZonasDistintasEnSubEsquemas(subEsquemas)){
        return false;
    }

    return true;

    console.log("todo validado....")
}

function validarZonasDistintasEnSubEsquemas(subEsquemas){
    let zonas = llenarZonasEsquema(subEsquemas, '.select-zonas')
    const repetidos = contarElementosRepetidos(zonas)

    for (const property in repetidos) {
        if(repetidos[property] > 1){
            const nombresZonas = obtenerNombreZona(property)
            alert(`Error: No se puede poner dos o más sub esquemas con los mismos destinos y origenes. ORIGEN: ${nombresZonas[0]} - DESTINO: ${nombresZonas[1]}
            `)
            return false; 
        }
    }

    return true;
} 

function obtenerNombreZona(tupla){
    const values = tupla.split(',')
    let nombres = []
    
    zonasCompletas.forEach(element => {
        if(element.id == values[0]){
            nombres.push(element.nombre)
        }  
        
        if(element.id == values[1]){
            nombres.push(element.nombre)
        }  
    });

    return nombres
}

function contarElementosRepetidos(zonas){
    var repetidos = {}

    zonas.forEach(numero => {
        repetidos[numero] = (repetidos[numero] || 0) + 1;
    });

    return repetidos
}

function llenarZonasEsquema(subEsquemas, clase){
    let zonas = []

    for (let index = 0; index < subEsquemas.length; index++) {
        const element = subEsquemas[index];
        const id = element.id

        const zonasAB = $(`#${id} ${clase}`)

        let aux = []

        for (let index = 0; index < zonasAB.length; index++) {
            const element = zonasAB[index];
            //zonas.push(element.value)    
            aux.push(element.value)
        }

        zonas.push(aux)

    }

    return zonas
}

function validarNombreEsquema(){
    const nombre = document.getElementById("nombre")

    if(nombre.value.trim() == ""){
        alert("Debe ingresar un nombre")
        nombre.focus()
        return false;
    }

    return true;
}

function validarZonas(id){
    const zonas = $(`#${id} .select-zonas`)

    for (let index = 0; index < zonas.length; index++) {
        const element = zonas[index];
        if(element.value == "" || element.value == "0"){
            alert("Debe seleccionar la zona")
            element.focus();
            return false;
        }
    }

    return true;
}

function validarPlantilla(id){
    const zonas = $(`#${id} .select-plantilla`)

    for (let index = 0; index < zonas.length; index++) {
        const element = zonas[index];
        if(element.value == "" || element.value == "0"){
            alert("Debe seleccionar una plantilla")
            element.focus();
            return false;
        }
    }

    return true;
}

function validarKilos(id){
    const cantidadKilos = $(`#${id} .campoDinamico_kilos`).length
    //obtener el contenedor del sub esquema
    const contenedor = id[id.length - 1]

    if( cantidadKilos > 0 ){

        for (let index = 1; index <= cantidadKilos; index++) {

            let inputDesde = document.getElementById(`rinicio_kilos_${contenedor}_${index}`)
            let inputHasta = document.getElementById(`rfin_kilos_${contenedor}_${index}`)
            let inputPrecio = document.getElementById(`precio_kilos_${contenedor}_${index}`)
            let inputTipo = document.getElementById(`opciones_tipo_kilos_${contenedor}_${index}`)

            if(inputDesde.value == ""){
                alert("El valor desde de tarifa kilos es requerido")
                inputDesde.value = "";
                inputDesde.focus();
                return false;
            }

            if(inputDesde.value <= 0){
                alert("El valor desde de tarifa kilos debe ser mayor a cero")
                inputDesde.value = "";
                inputDesde.focus();
                return false;
            }

            if(inputHasta.value == ""){
                alert("El valor hasta de tarifa kilos es requerido")
                inputHasta.value = "";
                inputHasta.focus();
                return false;
            }

            if(inputHasta.value <= 0){
                alert("El valor hasta de tarifa kilos debe ser mayor a cero")
                inputHasta.value = "";
                inputHasta.focus();
                return false;
            }

            if(inputPrecio.value == ""){
                alert("El precio de tarifa kilos es requerido")
                inputPrecio.value = "";
                inputPrecio.focus();
                return false;
            }

            if(inputPrecio.value <= 0){
                alert("El precio de tarifa kilos debe ser mayor a cero")
                inputPrecio.value = "";
                inputPrecio.focus();
                return false;
            }

            if(inputTipo.value == "" || inputTipo.value == "0"){
                alert("El tipo de tarifa kilos es requerido")
                inputTipo.focus();
                return false;
            }

        }

    }

    return true;
}

function validarDistancia(id){
    const cantidadDistancia = $(`#${id} .campoDinamico_distancia`).length
    //obtener el contenedor del sub esquema
    const contenedor = id[id.length - 1]

    if( cantidadDistancia > 0 ){

        for (let index = 1; index <= cantidadDistancia; index++) {

            let inputDesde = document.getElementById(`rinicio_distancia_${contenedor}_${index}`)
            let inputHasta = document.getElementById(`rfin_distancia_${contenedor}_${index}`)
            let inputPrecio = document.getElementById(`precio_distancia_${contenedor}_${index}`)

            if(inputDesde.value == ""){
                alert("El valor desde de tarifa distancia es requerido")
                inputDesde.value = "";
                inputDesde.focus();
                return false;
            }

            if(inputDesde.value <= 0){
                alert("El valor desde de tarifa distancia debe ser mayor a cero")
                inputDesde.value = "";
                inputDesde.focus();
                return false;
            }

            if(inputHasta.value == ""){
                alert("El valor hasta de tarifa distancia es requerido")
                inputHasta.value = "";
                inputHasta.focus();
                return false;
            }

            if(inputHasta.value <= 0){
                alert("El valor hasta de tarifa distancia debe ser mayor a cero")
                inputHasta.value = "";
                inputHasta.focus();
                return false;
            }

            if(inputPrecio.value == ""){
                alert("El precio de tarifa distancia es requerido")
                inputPrecio.value = "";
                inputPrecio.focus();
                return false;
            }

            if(inputPrecio.value <= 0){
                alert("El precio de tarifa distancia debe ser mayor a cero")
                inputPrecio.value = "";
                inputPrecio.focus();
                return false;
            }

        }

    }

    return true;
}

function validarBultos(id){
    const cantidadBultos = $(`#${id} .campoDinamico_bultos`).length
    //obtener el contenedor del sub esquema
    const contenedor = id[id.length - 1]

    if( cantidadBultos > 0 ){

        for (let index = 1; index <= cantidadBultos; index++) {

            let inputDesde = document.getElementById(`rinicio_bultos_${contenedor}_${index}`)
            let inputHasta = document.getElementById(`rfin_bultos_${contenedor}_${index}`)
            let inputPrecio = document.getElementById(`precio_bultos_${contenedor}_${index}`)
            let inputTipo = document.getElementById(`opciones_tipo_bultos_${contenedor}_${index}`)

            if(inputDesde.value == ""){
                alert("El valor desde de tarifa bultos es requerido")
                inputDesde.value = "";
                inputDesde.focus();
                return false;
            }

            if(inputDesde.value <= 0){
                alert("El valor desde de tarifa bultos debe ser mayor a cero")
                inputDesde.value = "";
                inputDesde.focus();
                return false;
            }

            if(inputDesde.value % 1 != 0){
                alert("El valor desde de tarifa bultos no puede ser decimal")
                inputDesde.value = "";
                inputDesde.focus();
                return false;
            }

            if(inputHasta.value == ""){
                alert("El valor hasta de tarifa bultos es requerido")
                inputHasta.value = "";
                inputHasta.focus();
                return false;
            }

            if(inputHasta.value <= 0){
                alert("El valor hasta de tarifa bultos debe ser mayor a cero")
                inputHasta.value = "";
                inputHasta.focus();
                return false;
            }

            if(inputHasta.value % 1 != 0){
                alert("El valor hasta de tarifa bultos no puede ser decimal")
                inputHasta.value = "";
                inputHasta.focus();
                return false;
            }

            if(inputPrecio.value == ""){
                alert("El precio de tarifa bultos es requerido")
                inputPrecio.value = "";
                inputPrecio.focus();
                return false;
            }

            if(inputPrecio.value <= 0){
                alert("El precio de tarifa bultos debe ser mayor a cero")
                inputPrecio.value = "";
                inputPrecio.focus();
                return false;
            }

            if(inputTipo.value == "" || inputTipo.value == "0"){
                alert("El tipo de tarifa bultos es requerido")
                inputTipo.focus();
                return false;
            }

        }

    }

    return true;
}

function validarMetrosCubicos(id){
    const cantidadMetroCubico = $(`#${id} .campoDinamico_metro_cubico`).length
    //obtener el contenedor del sub esquema
    const contenedor = id[id.length - 1]

    if( cantidadMetroCubico > 0 ){

        for (let index = 1; index <= cantidadMetroCubico; index++) {

            let inputDesde = document.getElementById(`rinicio_mc_${contenedor}_${index}`)
            let inputHasta = document.getElementById(`rfin_mc_${contenedor}_${index}`)
            let inputPrecio = document.getElementById(`precio_mc_${contenedor}_${index}`)
            let inputTipo = document.getElementById(`opciones_tipo_mc_${contenedor}_${index}`)

            if(inputDesde.value == ""){
                alert("El valor desde de tarifa metro cubico es requerido")
                inputDesde.value = "";
                inputDesde.focus();
                return false;
            }

            if(inputDesde.value <= 0){
                alert("El valor desde de tarifa metro cubico debe ser mayor a cero")
                inputDesde.value = "";
                inputDesde.focus();
                return false;
            }

            if(inputHasta.value == ""){
                alert("El valor hasta de tarifa metro cubico es requerido")
                inputHasta.value = "";
                inputHasta.focus();
                return false;
            }

            if(inputHasta.value <= 0){
                alert("El valor hasta de tarifa metro cubico debe ser mayor a cero")
                inputHasta.value = "";
                inputHasta.focus();
                return false;
            }

            if(inputPrecio.value == ""){
                alert("El precio de tarifa metro cubico es requerido")
                inputPrecio.value = "";
                inputPrecio.focus();
                return false;
            }

            if(inputPrecio.value <= 0){
                alert("El precio de tarifa metro cubico debe ser mayor a cero")
                inputPrecio.value = "";
                inputPrecio.focus();
                return false;
            }

            if(inputTipo.value == "" || inputTipo.value == "0"){
                alert("El tipo de tarifa metro cubico es requerido")
                inputTipo.focus();
                return false;
            }

        }

    }

    return true;

}

function validarPalets(id){
    const cantidadPalets = $(`#${id} .campoDinamico_palets`).length
    //obtener el contenedor del sub esquema
    const contenedor = id[id.length - 1]

    if( cantidadPalets > 0 ){

        for (let index = 1; index <= cantidadPalets; index++) {

            let inputDesde = document.getElementById(`rinicio_palets_${contenedor}_${index}`)
            let inputHasta = document.getElementById(`rfin_palets_${contenedor}_${index}`)
            let inputPrecio = document.getElementById(`precio_palets_${contenedor}_${index}`)
            let inputTipo = document.getElementById(`opciones_tipo_palets_${contenedor}_${index}`)

            if(inputDesde.value == ""){
                alert("El valor desde de tarifa palets es requerido")
                inputDesde.value = "";
                inputDesde.focus();
                return false;
            }

            if(inputDesde.value <= 0){
                alert("El valor desde de tarifa palets debe ser mayor a cero")
                inputDesde.value = "";
                inputDesde.focus();
                return false;
            }

            if(inputDesde.value % 1 != 0){
                alert("El valor desde de tarifa palets no puede ser decimal")
                inputDesde.value = "";
                inputDesde.focus();
                return false;
            }

            if(inputHasta.value == ""){
                alert("El valor hasta de tarifa palets es requerido")
                inputHasta.value = "";
                inputHasta.focus();
                return false;
            }

            if(inputHasta.value <= 0){
                alert("El valor hasta de tarifa palets debe ser mayor a cero")
                inputHasta.value = "";
                inputHasta.focus();
                return false;
            }

            if(inputHasta.value % 1 != 0){
                alert("El valor hasta de tarifa palets no puede ser decimal")
                inputHasta.value = "";
                inputHasta.focus();
                return false;
            }

            if(inputPrecio.value == ""){
                alert("El precio de tarifa palets es requerido")
                inputPrecio.value = "";
                inputPrecio.focus();
                return false;
            }

            if(inputPrecio.value <= 0){
                alert("El precio de tarifa palets debe ser mayor a cero")
                inputPrecio.value = "";
                inputPrecio.focus();
                return false;
            }

            if(inputTipo.value == "" || inputTipo.value == "0"){
                alert("El tipo de tarifa palets es requerido")
                inputTipo.focus();
                return false;
            }

        }

    }

    return true;

}

function validarWarehouse(id){
    const cantidadWarehouse = $(`#${id} .campoDinamico_warehouse`).length
    //obtener el contenedor del sub esquema
    const contenedor = id[id.length - 1]

    if( cantidadWarehouse > 0 ){

        for (let index = 1; index <= cantidadWarehouse; index++) {

            let inputDesde = document.getElementById(`rinicio_wh_${contenedor}_${index}`)
            let inputHasta = document.getElementById(`rfin_wh_${contenedor}_${index}`)
            let inputPrecio = document.getElementById(`precio_wh_${contenedor}_${index}`)

            if(inputDesde.value == ""){
                alert("El valor desde de tarifa warehouse es requerido")
                inputDesde.value = "";
                inputDesde.focus();
                return false;
            }

            if(inputDesde.value <= 0){
                alert("El valor desde de tarifa warehouse debe ser mayor a cero")
                inputDesde.value = "";
                inputDesde.focus();
                return false;
            }

            if(inputDesde.value % 1 != 0){
                alert("El valor desde de tarifa warehouse no puede ser decimal")
                inputDesde.value = "";
                inputDesde.focus();
                return false;
            }

            if(inputHasta.value == ""){
                alert("El valor hasta de tarifa warehouse es requerido")
                inputHasta.value = "";
                inputHasta.focus();
                return false;
            }

            if(inputHasta.value <= 0){
                alert("El valor hasta de tarifa warehouse debe ser mayor a cero")
                inputHasta.value = "";
                inputHasta.focus();
                return false;
            }

            if(inputHasta.value % 1 != 0){
                alert("El valor hasta de tarifa warehouse no puede ser decimal")
                inputHasta.value = "";
                inputHasta.focus();
                return false;
            }

            if(inputPrecio.value == ""){
                alert("El precio de tarifa warehouse es requerido")
                inputPrecio.value = "";
                inputPrecio.focus();
                return false;
            }

            if(inputPrecio.value <= 0){
                alert("El precio de tarifa warehouse debe ser mayor a cero")
                inputPrecio.value = "";
                inputPrecio.focus();
                return false;
            }


        }

    }

    return true;

}

function validarGestionFlota(id){
    const cantidadGestionFlota = $(`#${id} .campoDinamico_gestion_flota`).length
    //obtener el contenedor del sub esquema
    const contenedor = id[id.length - 1]

    if( cantidadGestionFlota > 0 ){

        for (let index = 1; index <= cantidadGestionFlota; index++) {

            let inputPrecio = document.getElementById(`precio_gf_${contenedor}_${index}`)
            let inputTipo = document.getElementById(`opcion_horas_gf_${contenedor}_${index}`)

            if(inputTipo.value == "" || inputTipo.value == 0){
                alert("El vehiculo/hora de tarifa gestión de flota es requerido")
                inputTipo.focus();
                return false;
            }

            if(inputPrecio.value == ""){
                alert("El precio de tarifa gestión de flota es requerido")
                inputPrecio.value = "";
                inputPrecio.focus();
                return false;
            }

            if(inputPrecio.value <= 0){
                alert("El precio de tarifa gestión de flota debe ser mayor a cero")
                inputPrecio.value = "";
                inputPrecio.focus();
                return false;
            }

        }

    }

    return true;
}

function validarCobranza(id){
    const cantidadCobranza = $(`#${id} .campoDinamico_cobranza`).length
    //obtener el contenedor del sub esquema
    const contenedor = id[id.length - 1]

    let indice = 1;

    if( cantidadCobranza > 0 ){

        if(document.getElementById(`tipo_cobranza_${contenedor}_${indice}`).value == "" || document.getElementById(`tipo_cobranza_${contenedor}_${indice}`).value == "0"){
            alert("Debe seleccionar el tipo que tendra la cobranza")
            document.getElementById(`tipo_cobranza_${contenedor}_${indice}`).focus();
            return false;
        }

        if(document.getElementById(`tipo_cobranza_${contenedor}_${indice}`).value == "" || document.getElementById(`tipo_cobranza_${contenedor}_${indice}`).value == "0"){
            alert("Debe seleccionar el tipo que tendra la cobranza")
            document.getElementById(`tipo_cobranza_${contenedor}_${indice}`).focus();
            return false;
        }

        if(document.getElementById(`valor_cobranza_${contenedor}_${indice}`).value == ""){
            alert("El valor de cobranza es requerido")
            document.getElementById(`valor_cobranza_${contenedor}_${indice}`).focus();
            return false;
        }

        if(document.getElementById(`valor_cobranza_${contenedor}_${indice}`).value <= 0){
            alert("El valor de cobranza debe ser mayor a cero")
            document.getElementById(`valor_cobranza_${contenedor}_${indice}`).value = ''
            document.getElementById(`valor_cobranza_${contenedor}_${indice}`).focus();
            return false;
        }
        
    }

    return true;
}

function validarValorDeclarado(id){
    const cantidadValorDeclarado = $(`#${id} .campoDinamico_valor_declarado`).length
    //obtener el contenedor del sub esquema
    const contenedor = id[id.length - 1]

    let indice = 1;

    if( cantidadValorDeclarado > 0 ){

        if(document.getElementById(`tipo_valor_declarado_${contenedor}_${indice}`).value == "" || document.getElementById(`tipo_valor_declarado_${contenedor}_${indice}`).value == "0"){
            alert("Debe seleccionar el tipo que tendra la cobranza")
            document.getElementById(`tipo_valor_declarado_${contenedor}_${indice}`).focus();
            return false;
        }

        if(document.getElementById(`tipo_valor_declarado_${contenedor}_${indice}`).value == "" || document.getElementById(`tipo_valor_declarado_${contenedor}_${indice}`).value == "0"){
            alert("Debe seleccionar el tipo que tendra la cobranza")
            document.getElementById(`tipo_valor_declarado_${contenedor}_${indice}`).focus();
            return false;
        }

        if(document.getElementById(`valor_declarado_${contenedor}_${indice}`).value == ""){
            alert("El valor de cobranza es requerido")
            document.getElementById(`valor_declarado_${contenedor}_${indice}`).focus();
            return false;
        }

        if(document.getElementById(`valor_declarado_${contenedor}_${indice}`).value <= 0){
            alert("El valor de cobranza debe ser mayor a cero")
            document.getElementById(`valor_declarado_${contenedor}_${indice}`).value = ''
            document.getElementById(`valor_declarado_${contenedor}_${indice}`).focus();
            return false;
        }
        
    }

    return true;
}

function validarPesoAforado(id){
    const cantidadPesoAforado = $(`#${id} .campoDinamico_peso_aforado`).length
    //obtener el contenedor del sub esquema
    const contenedor = id[id.length - 1]

    let indice = 1;

    if( cantidadPesoAforado > 0 ){

        if(document.getElementById(`valor_peso_aforado_${contenedor}_${indice}`).value == ""){
            alert("El valor de peso aforado es requerido")
            document.getElementById(`valor_peso_aforado_${contenedor}_${indice}`).focus();
            return false;
        }

        if(document.getElementById(`valor_peso_aforado_${contenedor}_${indice}`).value <= 0){
            alert("El valor de peso aforado debe ser mayor a cero")
            document.getElementById(`valor_peso_aforado_${contenedor}_${indice}`).value = ''
            document.getElementById(`valor_peso_aforado_${contenedor}_${indice}`).focus();
            return false;
        }
        
    }

    return true;
}
