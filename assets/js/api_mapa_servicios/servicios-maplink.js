    //ACCESS TOKEN
    let token = "";
    let tiempoToken = "";

    //VISUALIZACI脫N DEL MAPA
    const apiKey = "oIJWIpHGl2ejGPWJbgXV4tm3CXIN2GAi";
    //const maplink = new MaplinkMap(apiKey, "mapa");
    const mapaMapLink = document.getElementById("mapaMapLink")

    let maplink = null
    let L = null
    let mapLeft = null

    if(mapaMapLink != null){

        maplink = new MaplinkMap(apiKey, "mapaMapLink");

        /*
            LAS VARIABLES L Y mapLeft SE UTILIZAN PARA HACER REFERENCIA A LA LIBRERIA QUE USA POR DEBAJO
            LA API DE MAPLINK. ESTA LIBRERIA ES leafletjs, Y ES LA CUAL CONTIENE TODOS LOS METODOS Y FUNCIONES ORIGINALES
        */

        L = maplink.L
        mapLeft = maplink.map
    }

    const mapaMapLink2 = document.getElementById("mapaMapLink2")
    let maplink2 = null
    let L2 = null
    let mapLeft2 = null

    if(mapaMapLink2 != null){

        maplink2 = new MaplinkMap(apiKey, "mapaMapLink2");

        /*
            LAS VARIABLES L Y mapLeft SE UTILIZAN PARA HACER REFERENCIA A LA LIBRERIA QUE USA POR DEBAJO
            LA API DE MAPLINK. ESTA LIBRERIA ES leafletjs, Y ES LA CUAL CONTIENE TODOS LOS METODOS Y FUNCIONES ORIGINALES
        */

        L2 = maplink2.L
        mapLeft2 = maplink2.map

    }


    /*
        VARIABLES EN DONDE SE GUARDARAN LAS LINEAS Y LOS MARCADORES QUE SE GRAFICARAN EN EL MAPA
        Y DE ESTA MANERA TENGO LA REFENRECIA
    */
    let linea = null;
    let arrayLinea = [];
    let marcadores = [];

    let visualizacion = null;

    let contador = 9;

    let idProblema = null

    //PARA OBTENER EL TOKEN DEL LOCALSTORAGE
    async function get_LocalStorage(){
        const obj = JSON.parse(localStorage.getItem('token_mapLink'))
        if(obj){
            token = obj.token
            tiempoToken = obj.tiempoToken
        }else{
            await get_token()
        }
    }

    //LLAMO LA FUNCI脫N AL CARGAR EL ARCHIVO
    get_LocalStorage()


    async function inicializar_token(){

        try{

            if(token == ""){
                await get_token();
            }else if(tiempoToken == ""){
                await get_token();
            }else{

                var fecha1 = moment(tiempoToken);
                var fecha2 = moment();

                let segundos = fecha2.diff(fecha1, 'seconds');

                //hora = 3600 segundos
                if(segundos >= 3600){
                     await get_token();
                }


            }

        }catch(e){
            console.log(e)
        }


    }

    function limpiar_mapa(linea, marcadores){
        //console.log(linea)
        //console.log(marcadores)
        //console.log(arrayLinea)

        //ELIMINAR LINEA
        if(linea){
            mapLeft.removeLayer(linea)
        }

        if(arrayLinea.length > 0){
            for(let x = 0; x < arrayLinea.length ; x++){
                for(let j = 0; j < arrayLinea[x].length; j++){
                    mapLeft.removeLayer(arrayLinea[x][j])
                }
            }

            arrayLinea = []
        }

        //ELIMINAR LOS MARCADORES
        if(marcadores.length > 0){
            for(let i = 0; i < marcadores.length ; i++){
                mapLeft.removeLayer(marcadores[i])
            }

            marcadores = []
        }
    }

    async function cargar_ruta(resultado, arrayPiezas, totalDistancia, totalDuracion, tiempo_distancia){

        try{

            visualizacion = 0;

            if(resultado.length > 0){

                //ELIMINAR LINEA Y MARCADORES
                limpiar_mapa(linea, marcadores);

                //CONSTRUIR NUEVAMENTE LAS LINEAS Y LOS MARCADORES
                const routes = [resultado];

                //SE ASIGNA A LA VARIABLE linea PARA TENER LA REFERENCIA Y PODER ELIMINARLO AL ACTUALIZAR EL MAPA
                linea = maplink.lines(routes, {
                    color: "red"
                });

                //NUEVO
                const piezasIndices = obtener_indice_de_las_pieza_en_la_ruta_optima(arrayPiezas, resultado)

                let index = 0

                const piezas = piezasIndices.map( el => {
                    let indice = ''

                    if(el.pieza.destinatario != "Punto Inicio" && el.pieza.destinatario != "Punto Fin"){
                        indice = index + 1
                        index++
                    }

                    return {
                            latitude: el.pieza.lat,
                            longitude: el.pieza.lng,
                            destinatario: el.pieza.destinatario,
                            domicilio: el.pieza.domicilio,
                            indice: indice,
                            id: el.pieza.pieza
                    }
                })

                for (pieza of piezas) {

                    const marker = maplink.marker({
                        latitude: pieza.latitude, longitude: pieza.longitude
                    },{
                        popup: `<p>${pieza.indice ? 'Orden: ' + pieza.indice : ''}</p>
                                <p>Domicilio: ${pieza.domicilio}</p>
                                <p>Destinatario: ${pieza.destinatario}</p>
                                <p>${pieza.id > 100 ? 'Pieza ID: ' + pieza.id : ''}</p>`

                    });
                    //<p>Orden: ${pieza.indice}</p>
                    //<p>Pieza ID: ${pieza.id}</p>
                    //SE LO GUARDAR A LOS marker EN EL ARRAY DE marcadores PARA TENER LA REFERENCIA PARA PODER ELIMINARLOS AL ACTUALIZAR EL MAPA
                    marcadores.push(marker)
                }

                //FIN DE LO NUEVO

                if(tiempo_distancia){
                    estimacion_tiempo_distancia(totalDuracion, totalDistancia)
                }

                visualizacion = 1;

                detener_loading()

            }else{
                alert("Aviso: \nNo se puede visualizar el camino generado.")

                detener_loading()
            }

        }catch (err){
            console.log("cargar_ruta", err)
        }


    }

    function graficar_marcadores(arrayPiezas){
        if(arrayPiezas.length > 0){
            //VERIFICAR SI ESTA LINEA NO CAUSARA PROBLEMAS....
            limpiar_mapa(linea, marcadores);

            for (pieza of arrayPiezas) {

                const marker = maplink.marker({
                    latitude: pieza.lat, longitude: pieza.lng
                },
                {
                    popup: `<p style="margin: 0.5rem 0rem;">Pieza: ${pieza.id}</p>
                            <p style="margin: 0.5rem 0rem;">Destinatario: ${pieza.destinatario}</p>
                            <p style="margin: 0.5rem 0rem;">Descripción: ${pieza.descripcion}</p>
                            <p style="margin: 0.5rem 0rem;">Domicilio: ${pieza.domicilio}</p>
                            <p style="margin: 0.5rem 0rem;">Localidad: ${pieza.localidad}</p>
                            <p style="margin: 0.5rem 0rem;">Provincia: ${pieza.provincia}</p>
                            `,
                });

                marcadores.push(marker)
            }

            const arrayLatLng = arrayPiezas.map(el => {
                return [
                    parseFloat(el.lat), parseFloat(el.lng)
                ]
            })

            /*
                EL METODO fitBounds SE UTILIZA PARA CENTRAR EL MAPA EN BASE A LOS MARCADORES.
                DOCUMENTACIÓN: Sets a map view that contains the given geographical bounds with the maximum zoom level possible.
            */
            maplink.map.fitBounds([
                arrayLatLng
            ]);


        }else{
            console.log("No existen piezas para mostrar")
        }
    }

    function graficar_marcador(marcador){
        if(marcador){
            //VERIFICAR SI ESTA LINEA NO CAUSARA PROBLEMAS....
            limpiar_mapa(linea, marcadores);

            const marker = maplink.marker({
                latitude: marcador.latitud, longitude: marcador.longitud
            },
            {
                popup: `
                        <p>Domicilio: ${marcador.domicilio}</p>
                        <p>Localidad: ${marcador.localidad}</p>
                        <p>Provincia: ${marcador.provincia}</p>
                        <p>País: ${marcador.pais}</p>
                        `,
            });

            marcadores.push(marker)

            const arrayLatLng =  [ parseFloat(marcador.latitud), parseFloat(marcador.longitud) ]

            /*
                EL METODO fitBounds SE UTILIZA PARA CENTRAR EL MAPA EN BASE A LOS MARCADORES.
                DOCUMENTACIÓN: Sets a map view that contains the given geographical bounds with the maximum zoom level possible.
            */
            maplink.map.fitBounds([
                arrayLatLng
            ]);


        }else{
            console.log("No existen piezas para mostrar")
        }
    }

    function graficar_carteros(arrayCarteros){

        if(arrayCarteros.length > 0){
            //VERIFICAR SI ESTA LINEA NO CAUSARA PROBLEMAS....
            limpiar_mapa(linea, marcadores);

            const carteros = arrayCarteros.map((el, index) => {
                return{
                    indice: index,
                    nombre: el.nombre_cartero,
                    fecha_hora: el.fecha_hora,
                    latitude: el.lat,
                    longitude: el.lng,
                }
            })


            var carteroIcon = L.icon({
                iconUrl: '/assets/js/api_mapa_servicios/orange-dot.png',
                iconSize: [30,30], // size of the icon
            });

            for (cartero of carteros) {
                

                if(cartero.indice % 2 == 0){
                    console.log("entro en par")
                    marker = maplink.marker({
                        latitude: cartero.latitude, longitude: cartero.longitude
                    },
                    {
                        popup: `<p>Cartero: ${cartero.nombre}</p>
                                <p>Fecha/Hora: ${cartero.fecha_hora}</p>
                                `,
                    });
                    

                }else{
                    console.log("entro en impar")
                    marker = maplink.marker({
                        latitude: cartero.latitude, longitude: cartero.longitude
                    },
                    {
                        popup: `<p>Cartero: ${cartero.nombre}</p>
                                <p>Fecha/Hora: ${cartero.fecha_hora}</p>
                                `,
                        icon: carteroIcon
                    });

                }
                

                marcadores.push(marker)
            }

            const arrayLatLng = arrayCarteros.map(el => {
                return [
                    parseFloat(el.lat), parseFloat(el.lng)
                ]
            })

            /*
                EL METODO fitBounds SE UTILIZA PARA CENTRAR EL MAPA EN BASE A LOS MARCADORES.
                DOCUMENTACIÓN: Sets a map view that contains the given geographical bounds with the maximum zoom level possible.
            */
            maplink.map.fitBounds([
                arrayLatLng
            ]);


        }else{
            console.log("No existen carteros para mostrar")
        }
    }

    function graficar_recorrido_cartero(arrayPuntos, eventoClick = true){
        console.log("graficar_recorrido_cartero: ", arrayPuntos)
        if(arrayPuntos.length > 0){
            //VERIFICAR SI ESTA LINEA NO CAUSARA PROBLEMAS....
            limpiar_mapa(linea, marcadores);

            const ruta = arrayPuntos.map(el => {
                return {
                    latitude: parseFloat(el.lat),
                    longitude: parseFloat(el.lng)
                }
            })

            
            linea = maplink.line(ruta, {
                color: "red"
            })

            arrayLinea.push(linea)
            

            const puntos = arrayPuntos.map(el => {
                return{
                    orden: el.orden,
                    latitude: el.lat,
                    longitude: el.lng,
                    hora: el.hora,
                    id: el.id,
                    fecha: el.fecha
                }
            })

            for (punto of puntos) {

                const marker = maplink.marker({
                    latitude: punto.latitude, longitude: punto.longitude
                },
                {
                    popup: `
                            <p>Orden de recorrido: ${punto.orden}</p>
                            <p>Fecha: ${punto.fecha}</p>
                            `,
                })

                if(eventoClick){
                    marker.properties.id = ""
                    marker.properties.orden = ""
                    marker.properties.hora = ""

                    marker.on('click', (ev) => {
                        //const id = punto.id
                        //console.log(`Click en el marcador ${id}`)
                        console.log(ev);
                        console.log(ev.layer.properties);
                    });
                }

                marcadores.push(marker)
            }

            const arrayLatLng = arrayPuntos.map(el => {
                return [
                    parseFloat(el.lat), parseFloat(el.lng)
                ]
            })

            /*
                EL METODO fitBounds SE UTILIZA PARA CENTRAR EL MAPA EN BASE A LOS MARCADORES.
                DOCUMENTACIÓN: Sets a map view that contains the given geographical bounds with the maximum zoom level possible.
            */
            maplink.map.fitBounds([
                arrayLatLng
            ]);


        }else{
            console.log("No existen puntos para mostrar")
        }
    }

    function graficar_rutas_de_control(recorridoEstimado, recorridoReal, puntosNovedadesPiezas){

        limpiar_mapa(linea, marcadores);

        if(recorridoEstimado.rutaOptima.length > 0){

            const pEstimadas = recorridoEstimado.piezas.map(el => {
                return{
                    latitude: el.lat,
                    longitude: el.lng,
                    destinatario: el.destinatario,
                    domicilio: el.domicilio,
                    indice: el.orden,
                    id: el.pieza
                }
            })

            //CONSTRUIR NUEVAMENTE LAS LINEAS Y LOS MARCADORES
            const routes1 = [recorridoEstimado.rutaOptima];

            //SE ASIGNA A LA VARIABLE linea PARA TENER LA REFERENCIA Y PODER ELIMINARLO AL ACTUALIZAR EL MAPA
            linea = maplink.lines(routes1, {
                color: "#FA322A"
            });

            arrayLinea.push(linea)

            for (pieza of pEstimadas) {

                const marker = maplink.marker({
                    latitude: pieza.latitude, longitude: pieza.longitude
                },
                {
                    popup: `<p>${pieza.indice ? 'Orden: ' + pieza.indice : ''}</p>
                            <p>Domicilio: ${pieza.domicilio}</p>
                            <p>Destinatario: ${pieza.destinatario}</p>
                            <p>${pieza.id > 100 ? 'Pieza ID: ' + pieza.id : ''}</p>
                            `,
                });

                marcadores.push(marker)
            }


        }


        if(recorridoReal.length > 0){

            const pReal = recorridoReal.map(el => {
                return {
                        latitude: el.lat,
                        longitude: el.lng,
                        fecha: el.fecha
                }
            })

            //CONSTRUIR NUEVAMENTE LAS LINEAS Y LOS MARCADORES
            const routes2 = [pReal];

            //SE ASIGNA A LA VARIABLE linea PARA TENER LA REFERENCIA Y PODER ELIMINARLO AL ACTUALIZAR EL MAPA
            linea = maplink.lines(routes2, {
                color: "#2AC6FA"
            });

            arrayLinea.push(linea)

            var carteroIcon = L.icon({
                iconUrl: '/img/red.png',
                iconSize: [30,30], // size of the icon
            });

            for (pieza of pReal) {

                const marker1 = maplink.marker({
                    latitude: pieza.latitude, longitude: pieza.longitude
                },
                {
                    popup: `<strong>Fecha / Hora: ${pieza.fecha}</strong>`,
                    icon: carteroIcon
                });

                marcadores.push(marker1)

            }
        }

        if(puntosNovedadesPiezas.length > 0){

            const pNovedades = puntosNovedadesPiezas.map(el => {
                return {
                        destinatario: el.destinatario,
                        domicilio: el.domicilio,
                        estado: el.estado,
                        fecha_hora: el.fecha_hora,
                        id: el.id,
                        id_estado: el.id_estado,
                        latitude: el.lat,
                        longitude: el.lng,
                        orden: el.orden,
                        pieza: el.pieza,

                }
            })

            var greenIcon = L.icon({
                iconUrl: '/img/location-dot-solid.svg',
                iconSize: [30,30], // size of the icon
            });

            for (el of pNovedades) {

                const marker2 = maplink.marker({
                    latitude: el.latitude, longitude: el.longitude
                },
                {
                    popup: `
                        <p>${el.orden ? 'Orden: ' + el.orden : ''}</p>
                        <p>Domicilio: ${el.domicilio}</p>
                        <p>Destinatario: ${el.destinatario}</p>
                        <p>Pieza: ${el.pieza}</p>
                        <p>Estado: ${el.estado}</p>
                        <p>Fecha/Hora: ${el.fecha_hora}</p>
                    `,
                    icon: greenIcon
                });

                marcadores.push(marker2)

            }
        }

    }

    function graficar_ruta_novedades(arrayPiezas){

        if(arrayPiezas.length > 0){

            console.log("Las piezas llegadas son: ", arrayPiezas)

            //ELIMINAR LINEA Y MARCADORES
            limpiar_mapa(linea, marcadores, arrayLinea);

            const piezas = arrayPiezas.map(el => {
                return {
                        destinatario: el.destinatario,
                        domicilio: el.domicilio,
                        estado: el.estado,
                        fecha_hora: el.fecha_hora,
                        id: el.id,
                        id_estado: el.id_estado,
                        latitude: el.lat,
                        longitude: el.lng,
                        orden: el.orden,
                        pieza: el.pieza
                }
            })

            //console.log("las piezas son: ", piezas)

            //CONSTRUIR NUEVAMENTE LAS LINEAS Y LOS MARCADORES
            //const routes = [piezas];

            //SE ASIGNA A LA VARIABLE linea PARA TENER LA REFERENCIA Y PODER ELIMINARLO AL ACTUALIZAR EL MAPA
            //linea = maplink.lines(routes, {
            //    color: "green"
            //});

            arrayLinea.push(linea)


            //console.log("puntos de graficar_ruta", piezas)

            for (pieza of piezas) {

                const marker = maplink.marker({
                    latitude: pieza.latitude, longitude: pieza.longitude
                },
                {
                    popup: `
                        <p>Orden: ${pieza.orden} </p>
                        <p>Domicilio: ${pieza.domicilio}</p>
                        <p>Destinatario: ${pieza.destinatario}</p>
                        <p>Pieza: ${pieza.pieza}</p>
                        <p>Estado: ${pieza.estado}</p>
                        <p>Fecha/Hora: ${pieza.fecha_hora}</p>
                    `
                });

                //SE LO GUARDAR A LOS marker EN EL ARRAY DE marcadores PARA TENER LA REFERENCIA PARA PODER ELIMINARLOS AL ACTUALIZAR EL MAPA
                marcadores.push(marker)
            }

            //console.log(maplink)


        }else{
            alert("Aviso: \nNo se puede visualizar el camino generado.")

        }



    }

    function graficar_ruta(arrayPiezas){

        if(arrayPiezas.length > 0){

            //ELIMINAR LINEA Y MARCADORES
            limpiar_mapa(linea, marcadores);

            const piezas = arrayPiezas.map(el => {
                return {
                        latitude: el.lat,
                        longitude: el.lng,
                        fecha: el.fecha_hora
                }
            })

            //CONSTRUIR NUEVAMENTE LAS LINEAS Y LOS MARCADORES
            const routes = [piezas];

            //SE ASIGNA A LA VARIABLE linea PARA TENER LA REFERENCIA Y PODER ELIMINARLO AL ACTUALIZAR EL MAPA
            linea = maplink.lines(routes, {
                color: "red"
            });

            //console.log("puntos de graficar_ruta", piezas)

            for (pieza of piezas) {

                const marker = maplink.marker({
                    latitude: pieza.latitude, longitude: pieza.longitude
                },
                {
                    popup: `<strong>Fecha / Hora: ${pieza.fecha}</strong>`
                });

                //SE LO GUARDAR A LOS marker EN EL ARRAY DE marcadores PARA TENER LA REFERENCIA PARA PODER ELIMINARLOS AL ACTUALIZAR EL MAPA
                marcadores.push(marker)
            }

            //console.log(maplink)


        }else{
            alert("Aviso: \nNo se puede visualizar el camino generado.")

        }



    }

    function estimacion_tiempo_distancia(tiempo, distancia){
        distancia = (distancia / 1000).toFixed(2);

        tiempo = moment.utc(tiempo * 1000).format("HH:mm:ss");


        document.getElementById("distancia").value = distancia;
        document.getElementById("tiempo").value = tiempo;
    }


    async function get_token(){
        var myHeaders = new Headers();
        myHeaders.append("Content-Type", "application/x-www-form-urlencoded");

        var urlencoded = new URLSearchParams();
        urlencoded.append("client_id", "oIJWIpHGl2ejGPWJbgXV4tm3CXIN2GAi");
        urlencoded.append("client_secret", "eUGJJdtNnLHyRMtj");

        var requestOptions = {
          method: 'POST',
          headers: myHeaders,
          body: urlencoded,
          redirect: 'follow'
        };

        try{
            const resp = await fetch("https://api.maplink.global/oauth/client_credential/accesstoken?grant_type=client_credentials", requestOptions)
            let result = await resp.text()

            console.log(result);
            result = JSON.parse(result);

            token = result.access_token;
            tiempoToken = moment();

            //ELIMINAR TOKEN PRIMERO
            localStorage.removeItem('token_mapLink');

            //AGREGADO
            const obj = {
                token: result.access_token,
                tiempoToken: moment()
            }

            localStorage.setItem('token_mapLink', JSON.stringify(obj)) //LO GUARDO EN EL LOCALSTORAGE

        }catch (e){
            console.log(e)
        }

    }

    async function post_trip_problema(piezas, tiempoDistancia, retornarSolucion = 0){

        try{
            await inicializar_token()

            var myHeaders = new Headers();
            myHeaders.append("Content-Type", "application/json");
            myHeaders.append("Authorization", `Bearer ${token}`);

            let arrayPiezas = [];


            for(let i = 0; i < piezas.length; i++){
                const pieza = {
                    latitude: parseFloat(piezas[i].lat),
                    longitude: parseFloat(piezas[i].lng),
                    siteId: piezas[i].destinatario + ' - ' + piezas[i].domicilio + ' - ' + i
                }

                arrayPiezas.push(pieza);
            }

            //console.log("array de piezas post_trip", arrayPiezas)

            var raw = JSON.stringify({
              "profileName": "MAPLINK",
              "points": arrayPiezas,
              "callback": {
                "url": "https://ruteodesarrollo.sppflash.com.ar/webhook"
              },
              "startDate": 0,
              "calculationMode": "THE_SHORTEST"
            });


            var requestOptions = {
              method: 'POST',
              headers: myHeaders,
              body: raw,
              redirect: 'follow'
            };

            //console.log(requestOptions)

            const response = await fetch("https://api.maplink.global/trip/v1/problems", requestOptions)
            let result = await response.text()

            result = JSON.parse(result)
            const id = result.id;
            console.log("id: ",id)

            if(id){

                //NUEVO
                let bandera = true
                let respuesta = null

                while(bandera){
                    contador--
                    if(contador == 0){
                        alert("Aviso: \nError por parte del Proveedor del servicio de generación de ruta. Intente nuevamente dentro de unos minutos por favor.")
                        //alert("Aviso: \nEl sistema de generación de ruta no se encuentra disponible en este momento. Intente nuevamente más tarde.")
                        break;
                    }

                    respuesta = await state_problem(id)

                    if(respuesta){
                        bandera = false
                        contador = 9
                        //idProblema = id
                        //AGREGADO
                        if(retornarSolucion){
                            const respuesta = await get_trip_solucion(id, piezas, tiempoDistancia, retornarSolucion);
                            return respuesta

                        }else{
                            await get_trip_solucion(id, piezas, tiempoDistancia, retornarSolucion);
                        }

                        setTimeout(function(){ }, 1000);
                    }
                }


            }else{
                console.log("No esta el id en test 1")
            }


        }catch (e){
            console.log(e)
        }


    }



    async function state_problem(id){

        try {

                const resp = await axios.post('/checked_status_problem', {
                            "_token": CSRF_TOKEN,
                            id:id
                        });

                response = await resp.data;

                console.log(response.data[0])

                if(response.data[0]){
                    return true;
                }else{
                    return false
                }



        } catch (err) {
            console.error(err);
        }

    }

    /*
        FUNCION QUE OBTIENE LA SOLUCION DEL PROBLEMA, ESTA FUNCION PUEDE TOMAR DOS CAMINOS
            1) DEVOLVER LOS RESULTADOS DE LA SOLUCION (retornarSolucion = 1)
            2) EJECUTAR LA FUNCIÓN PARA GRAFICAR EL MAPA (cargar_ruta) (retornarSolucion = 0)

        EL CAMINO QUE TOME DEPENDE DE LA VARIABLE retornarSolucion
    */
    async function get_trip_solucion(id, piezas, tiempoDistancia, retornarSolucion){

        if(id){

            try{

                await inicializar_token()

                var myHeaders = new Headers();
                myHeaders.append("Authorization", `Bearer ${token}`);

                var requestOptions = {
                  method: 'GET',
                  headers: myHeaders,
                  redirect: 'follow'
                };

                const response = await fetch("https://api.maplink.global/trip/v1/solutions/" + id, requestOptions)
                let result = await response.text()

                result = JSON.parse(result)

                console.log(result)

                resultado = result.legs
                totalDistancia = result.totalDistance
                totalDuracion = result.totalNominalDuration

                  rutaOptima = []

                  for (let i = 0; i < resultado.length; i++) {
                    const tramo = resultado[i].points
                    for (let j = 0; j < tramo.length; j++) {
                      rutaOptima.push(tramo[j])
                    }
                  }

                  idProblema = id

                  console.log("la solucion dice: ", idProblema)

                  // AGREGANDO
                  if(retornarSolucion){

                    return {
                        result,
                        rutaOptima
                    }

                  }else{
                      await cargar_ruta(rutaOptima, piezas, totalDistancia, totalDuracion, tiempoDistancia)
                  }

            }catch (err){
                console.log(err)
            }

        }else{
            console.log("No tengo el id en get_trip_solucion")
        }

    }


    /*
        FUNCION QUE PERMITE OBTENER LAS PIEZAS ORDENADAS EN BASE AL RECORRIDO OPTIMO
    */
    function obtener_indice_de_las_pieza_en_la_ruta_optima(piezas, puntosRecorrido){
        let arrayP = []
        let arrayFinal = []
        let distancia = 0;

        //console.log("ruta de puntos mapLink: ", puntosRecorrido)

        //RECORREMOS LAS PIEZAS PRIMERO
        for (var i = 0; i < piezas.length; i++) {
            //POR CADA PIEZA RECORREMOS TODOS LOS PUNTOS DE LA RUTA OPTIMA
            for (var j = 0; j < puntosRecorrido.length; j++) {
                distancia = getDistanciaMetros(piezas[i].lat, piezas[i].lng, puntosRecorrido[j].latitude, puntosRecorrido[j].longitude)
                const obj = {
                    pieza: piezas[i],
                    puntoRecorrido: puntosRecorrido[j],
                    indice: j,
                    distancia
                }

                arrayP.push(obj)
            }

            //console.log(arrayP);

            //OBTENGO EL OBJETO QUE CONTIENE LA MENOR DISTANCIA CONSIDERANDO LA POSICIÓN DE LA PIEZA Y LA DISTANCIA DE LOS PUNTOS DEL RECORRIDO OPTIMO
            var res = Math.min.apply(Math, arrayP.map(function(o) { return o.distancia; }));
            const mostrar = arrayP.find(el => el.distancia == res)

            //console.log("ruta de puntos: ", arrayP)

            arrayP = []

            //arrayFinal CONTIENE TODOS LAS PIEZAS CON SU PUNTO MAS CERCANO DE LA RUTA OPTIMA
            arrayFinal.push(mostrar)
        }

        //ORDENO EL ARRAY SEGUN EL INDICE DE LA RUTA
        arrayFinal.sort(function (a, b) {
          if (a.indice > b.indice) {
            return 1;
          }
          if (a.indice < b.indice) {
            return -1;
          }

          return 0;
        });

        console.log("Puntos pieza en recorrido", arrayFinal)

        let orden = 0

        /*
        ESTA PARTE SOLO ES UTILIZADA EN EL ARCHIVO asignacion.blade.php, NECESITABAMOS OBTENER SOLO EL ORDEN DE LA PIEZA CON SU ID
         LUEGO LE ASIGNAMOS A UNA VARIABLE GLOBAL QUE TIENE ESTA VISTA QUE SE LLAMA piezasOrden
        */
        if(typeof piezasOrden != undefined){
            const aux = arrayFinal.map(el => {
               orden++;

               if(el.pieza.destinatario == "Punto Inicio"){
                   return{
                       id: el.pieza.id,
                       orden: 1
                   }

               }

               if(el.pieza.destinatario == "Punto Fin"){
                   orden--
                   return{
                       id: el.pieza.id,
                       orden: arrayFinal.length
                   }
               }

               //orden = orden + 1

               return{
                   id: el.pieza.id,
                   orden: orden
               }
            });

            piezasOrden = aux
        }

        return arrayFinal;
    }

    /*
        FUNCION QUE CALCULA LA DISTANCIA ENTRE DOS PUNTOS GEOGRAFICOS, Y LA RETORNA EN METROS
    */
    function getDistanciaMetros(lat1,lon1,lat2,lon2){
      rad = function(x) {return x*Math.PI/180;}
      var R = 6371; //Radio de la tierra en km
      var dLat = rad( lat2 - lat1 );
      var dLong = rad( lon2 - lon1 );
      var a = Math.sin(dLat/2) * Math.sin(dLat/2) + Math.cos(rad(lat1)) *
      Math.cos(rad(lat2)) * Math.sin(dLong/2) * Math.sin(dLong/2);
      var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));

      //aquí obtienes la distancia en metros por la conversion 1Km =1000m
      var d = R * c * 1000;
      return d ;
    }

    function ordenarPiezasParaMapLink(piezas){
		let inicio = null
		let inicial = null
		let final = null

		const arrayP = []
		let distancias = []

		//QUITAMOS EL INICIO
		if (piezas[0].id == "01" || piezas[0].destinatario == "Punto Inicio") {
			//arrayP.push(piezas[0])
			inicio = inicial = piezas.shift()
		}

		//QUITAMOS EL FINAL
		if (piezas[piezas.length - 1].id == "02" || piezas[piezas.length].destinatario == "Punto Fin") {
			//arrayP.push(piezas[piezas.length - 1])
			final = piezas.pop()
		}

		let longitud = [...piezas].length

		let i = 0;
		while (i < longitud) {

			if(inicio){
				//console.log("entro por inicio")

				for (var x = 0; x < piezas.length; x++) {
					let dist = getDistanciaMetros(inicio.lat, inicio.lng, piezas[x].lat, piezas[x].lng)

					const obj = {
						distancia: dist,
						pieza: piezas[x]
					}

					distancias.push(obj)
				}

				var res = Math.min.apply(Math, distancias.map(function(o) { return o.distancia; }));
        		const mostrar = distancias.find(el => el.distancia == res)

				//ELIMINAR EL INDICE DE PIEZAS
				var indice = piezas.findIndex(el => el.lat == mostrar.pieza.lat && el.lng == mostrar.pieza.lng)
				piezas.splice(indice, 1)

        		arrayP.push(mostrar)

        		distancias = []
				inicio = null

			}else{
				//console.log("ya no entro por inicio")

				for (var j = 0; j < piezas.length; j++) {
					let dist = getDistanciaMetros(arrayP[i - 1].pieza.lat, arrayP[i - 1].pieza.lng, piezas[j].lat, piezas[j].lng)
					//console.log("distancia: ", dist)
					const obj = {
						distancia: dist,
						pieza: piezas[j]
					}

					distancias.push(obj)
				}

				var res = Math.min.apply(Math, distancias.map(function(o) { return o.distancia; }));
        		const mostrar = distancias.find(el => el.distancia == res)

				//ELIMINAR EL INDICE DE PIEZAS
				var indice = piezas.findIndex(el => el.lat == mostrar.pieza.lat && el.lng == mostrar.pieza.lng)
				piezas.splice(indice, 1)

        		arrayP.push(mostrar)
        		distancias = []
			}

			i++
		}

		//console.log(arrayP)

		const arrayFinal = arrayP.map(el => {
			return{
				destinatario: el.pieza.destinatario,
				domicilio: el.pieza.domicilio,
				id: el.pieza.id,
				lat: el.pieza.lat,
				lng: el.pieza.lng,
				pieza: el.pieza.pieza
			}
		})

		arrayFinal.unshift(inicial)
		arrayFinal.push(final)

		return arrayFinal
	}
