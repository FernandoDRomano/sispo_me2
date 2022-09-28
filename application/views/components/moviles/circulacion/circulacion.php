<?php
$user_row = $this->ion_auth->user()->row();
?>

<link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css" rel="stylesheet">

<!-- LIBRERIAS DE MAPA -->
<meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no" />
<script src="https://maps.maplink.global"></script>


<div class="col-xs-12">
    <div class="ibox-content">
        <?php Elementos::CrearTitulo("Mapa Hist&oacute;rico"); ?>
        <br>
        <div class="row">
            <input hidden name="moviles" id="moviles">

            <div class="col-xs-12">
                <div class="form-group">
                    <label for="pais">Países</label>
                    <select onchange="cambioPais(event)" name="pais" id="pais" class="form-control" style="width: 100%;">
                        <option value="0">Seleccione una opción</option>
                        <?php foreach ($paises as $p) { ?>
                            <option value="<?php echo $p['id']?>"><?php echo $p['nombre']?></option>
                        <?php }?>
                    </select>
                </div>
            </div>

            <div class="col-xs-12">
                <div class="form-group">
                    <label for="sucursal">Sucusal</label>
                    <select onchange="modificar_filtro(event)" name="sucursal" id="sucursal" class="form-control" style="width: 100%;">
                        <option value="">Seleccione una opción</option>
                    </select>
                </div>
            </div>

            <div class="col-xs-12">
                <div class="form-group">
                    <label for="tipo_cartero">Tipo de Cartero</label>
                    <select onchange="modificar_filtro(event)" name="tipo_cartero" id="tipo_cartero" class="form-control" style="width: 100%;">
                        <option value="">Seleccione una opción</option>
                        <?php foreach ($tipos_carteros as $tipo) { ?>
                            <option value="<?php echo $tipo['id']?>"><?php echo $tipo['nombre']?></option>
                        <?php }?>
                    </select>
                </div>
            </div>

            <div class="col-xs-12">
                <div class="form-group">
                    <label for="cartero">Cartero/s</label>
                    <select name="cartero" id="cartero" class="form-control" style="width: 100%;">
                        <option value="">Seleccione una opción</option>
                    </select>
                </div>
            </div>

            <div class="col-xs-6">
                <div class="form-group col-auto">
                    <label for="fecha_inicio">Fecha Inicio</label>
                    <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" value="<?php echo $fecha?>">
                </div>
            </div>

            <div class="col-xs-6">
                <div class="form-group col-auto">
                    <label for="fecha_fin">Fecha Fin</label>
                    <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" value="<?php echo $fecha?>">
                </div>
            </div>

            <div class="col-xs-12">
                <button class="btn btn-primary" onclick="buscar()"><i class="fa fa-search" aria-hidden="true"></i> Buscar</button>
            </div>
        </div>
        
        <div class="row" style="margin-top: 2rem; display:none;" id="tabla" >
            <div class="col-xs-12">
                <div class="ibox-content">    
                    <!-- id="results" -->    
                    <table id="movimientos" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered nowrap order-column">
                        <thead>
                            <tr>
                                <th><a href="#">Cantidad de puntos</a></th>
                                <th><a href="#">Fecha</a></th>
                                <th><a href="#">Acción</a></th>
                                <!-- <th class="col-xs-1">&nbsp;</th> -->
                            </tr>
                        </thead>
                        <tbody id="table-body">
                        
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div id="mapaMapLink" style="width: 100%; height: 500px; margin: 20px auto; display: none;"></div>
        
        <div>

        </div>
    </div>


    <script src="<?php echo base_url() ?>assets/js/ExtencionesJS/moment.min.js"></script>
    <script src="<?php echo base_url() ?>assets/js/api_mapa_servicios/servicios_maplink.js"></script>

    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>

    <!--axios -->
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

    <script>

        var us_nombre = <?php echo ("'" . $user_row->nombre . " " . $user_row->apellido) . "'" ?>;
        var UserId = <?php echo ("'" . $user_row->user_id) . "'" ?>;
        var SucursalesDeUsuario = <?php echo ('[' . $user_row->sucursal_id) . ']' ?>;

        //var URLJS = "http://prueba.sispo.com.ar/ajax";
        const URLJS = `//${document.domain}`;
        var CSRF_TOKEN = "";
        var api_token = "";

        $('#pais').select2();
        $('#tipo_cartero').select2({
            disabled: true
        });
        $('#sucursal').select2({
            disabled: true
        });
        $('#cartero').select2({
            disabled: true
        });

        let table = null

        $(document).ready(function() {

            table = $('#movimientos').DataTable({
                //scrollX: true,
                /*
                dom: 'Bfrtip',
                buttons: [
                    //'csv', 
                    'excel'
                ],
                */
                order: [[1, 'asc']],
                language: {
                    "info": "_TOTAL_ registros",
                    "search": "Buscar",
                    "paginate": {
                        "first": "Primero",
                        "last": "Último",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    },
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "emptyTable": "No hay datos",
                    "zeroRecords": "No hay coincidencias",
                    "infoEmpty": "",
                    "infoFiltered": "",
                    "lengthMenu": "Mostrar _MENU_ registros",
                }
            });
        
        });

        let sucursales = null
        let clientes = null
        let carteros = null
        let carteros_ids = null
        let banderaError = false
        let timerID = null

        const loading = $('#loading');

        async function cambioPais(event){
            const pais = event.target.value
            if(pais != "" && pais != 0){
                
                ocultarTablaYMapa()

                try {
                    loading.css('display', 'block');

                    sucursales = await getSucursales(pais)
                    carteros = await getCarteros(pais)

                    if(sucursales){
                        cargarSelect('sucursal', sucursales, 'id', 'nombre', 'Todas', true)
                    }

                    if(carteros){
                        cargarSelect('cartero', carteros, 'id', 'apellido_nombre', 'Todos', true)
                    }

                    console.log("sucursales", sucursales)
                    console.log("carteros", carteros)

                    //HABILITAR EL TIPO DE CARTERO POR DEFECTO
                    document.getElementById('tipo_cartero').disabled = false

                } catch (error) {
                    console.log(error)
                } finally{
                    loading.css('display', 'none');
                }
            }
        }
    
        function cargarSelect(select, options, id = 'id', value = "", optionInicial = "", habilitar = false){
            //Obtengo el select
            const selectHTML = document.getElementById(select)
            //Limpio las opciones
            selectHTML.innerHTML = ""
            
            $(`#${select}`).append(new Option(optionInicial, 0, false, false)).trigger('change')

            options.data.forEach(opt => {
                $(`#${select}`).append(new Option(opt[value], opt[id], false, false)).trigger('change')
            });


            if(habilitar){
                $(`#${select}`).prop("disabled", false);
            }
        }

        async function getSucursales(pais){
            try {
                const dominio = document.domain;
                const url = `//${dominio}/moviles/posicion/getSucursales`;

                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({pais})
                })

                const data = await response.json();

                if(data){
                    return data
                }

                return false

            } catch (error) {
                console.log(error)
            }
        }

        async function getCarteros(pais){
            try {
                const dominio = document.domain;
                const url = `//${dominio}/moviles/posicion/getCarteros`;

                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({pais})
                })

                const data = await response.json();

                if(data){
                    return data
                }

                return false

            } catch (error) {
                console.log(error)
            }
        }

        function validarSelectValido(){
            //const cliente = document.getElementById('cliente')
            const cartero = document.getElementById('cartero')
            const sucursal = document.getElementById('sucursal')
            const pais = document.getElementById('pais')

            if(pais.value == "" || pais.value == "0"){
                return false
            }

            return true
        }

        function modificar_filtro() {
            const fSucursal = document.getElementById('sucursal').value
            const fTipoCartero = document.getElementById('tipo_cartero').value

            $('#cartero').empty()

            $('#cartero').append(new Option("Seleccione un Cartero", "0", false, false)).trigger('change')

            if(carteros.data){
                carteros.data.filter(c => {
                        let tipo = true;
                        let suc = true;
                        if (fTipoCartero != 0) {
                            tipo = c.cartero_tipo_id == fTipoCartero
                        }
                        if (fSucursal != 0) {
                            suc = c.sucursal_id == fSucursal
                        }
                        return tipo && suc
                })
                .forEach(c => {
                    $('#cartero').append(new Option(c.apellido_nombre, c.id, false, false)).trigger('change')
                })
            }else{
                ocultarTablaYMapa()
            }

        }

        function buscar(){
            //AL BUSCAR LOS PUNTOS DE UN NUEVO CARTERO, SI O SI DEBO OCULTAR EL MAPA YA SEA QUE ENCUENTRE RESULTADOS O NO
            document.getElementById('mapaMapLink').style.display = 'none'
            
            const cartero = document.getElementById('cartero').value;
            const fecha_inicio = document.getElementById('fecha_inicio').value;
            const fecha_fin = document.getElementById('fecha_fin').value;
            const pais = document.getElementById('pais').value;

            if (cartero == '' || cartero == '0' || fecha_inicio == '' || fecha_fin == '') {
                ocultarTablaYMapa()
                alert('Aviso:\nPara realizar una busqueda debe seleccione las fechas y un cartero.');
                return;
            }

            loading.css('display', 'block');

            getMovimientosPorFecha(cartero, fecha_inicio, fecha_fin, pais);
        }

        async function getMovimientosPorFecha(cartero, fecha_inicio, fecha_fin, pais){
            
            try {
                const resp = await axios.post('/moviles/circulacion/obtener_movimiento_por_fecha', {
                                cartero: cartero,
                                fecha_inicio: fecha_inicio,
                                fecha_fin: fecha_fin,
                                pais: pais
                            });

                response = resp.data;    
                
                if (resp.status == 200 && response.length > 0) {
                    
                    //Limpiar las filas
                    table.clear().draw();
                    
                    response.forEach(el => {
                        //SE FORMATEA LA FECHA DE INICIO Y DE FIN CON EL FORMATO PARA REALIZAR LUEGO LA CONSULTA
                        fecha_inicio = fecha_fin = el.FechaLocal

                        const button = `
                                        <button class="btn btn-sm btn-info" onclick="sendPostRequest('${cartero}', '${fecha_inicio}', '${fecha_fin}', '${pais}')"><i class="fa fa-eye" aria-hidden="true"></i> Recorrido</button>
                                        <button class="btn btn-sm btn-success" onclick="getPiezasHDR('${cartero}', '${fecha_inicio}')"><i class="fa fa-eye" aria-hidden="true"></i> Piezas</button>
                                        `

                        //SE AGREGAN LAS FILAS AL DATATABLE, AL NO HACERLO DE ESTA MANERA NO SE ENTERA DE LOS CAMBIOS Y NO SE PUEDE REALIZAR NINGUNA BUSQUEDA
                        table.row.add([ el.cantidad, el.fecha, button]).draw(false);
                    });
                    
                    document.getElementById('tabla').style.display = "block"
                } 
                else {
                    ocultarTablaYMapa()
                    alert('Aviso:\nNo se encontraron datos.');
                }

            } catch (err) {
                ocultarTablaYMapa()
                console.error(err);
            } finally{
                loading.css('display', 'none');
            }
        }

        async function sendPostRequest(cartero, fecha_inicio, fecha_fin, pais){
            loading.css('display', 'block');

            try {
                const resp = await axios.post('/moviles/circulacion/recorrido_cartero', {
                                cartero: cartero,
                                fecha_inicio: fecha_inicio,
                                fecha_fin: fecha_fin,
                                pais: pais
                            });

                response = resp.data;                
                
                if (response.status == true) {
                    //PUNTOS DE RESPUESTA
                    puntos_array = response.recorrido;

                    let recorridoMap = response.recorrido.map((lugar, index) => {
                        return {
                            'orden': (index + 1),
                            'lat': lugar.Latitude,
                            'lng': lugar.Longitude,
                            'hora': lugar.tiempo,
                            'id': lugar.id,
                            'fecha': lugar.fecha
                        }
                    });

                    document.getElementById('mapaMapLink').style.display = 'block'
                    //PARA RENDERIZAR CORRECTAMENTE EL MAPA DE MAPLINK UNA VEZ QUE SE MUESTRA 
                    maplink.map.invalidateSize();
                    
                    graficar_recorrido_cartero(recorridoMap, false);          
                
                } 
                else {
                    ocultarTablaYMapa()
                    alert('Aviso:\nNo se encontraron datos.');
                }
                            

            } catch (err) {
                // Handle Error Here
                console.error(err);
                ocultarTablaYMapa()
                alert('Aviso:\nOcurrio un error al consultar los datos. Por favor actualice la pagina y realice nuevamente la busqueda..')

            } finally{
                loading.css('display', 'none');
            }
        };

        async function getPiezasHDR(cartero, fecha){
            loading.css('display', 'block');

            try {
                const resp = await axios.post('/moviles/circulacion/consulta_piezas_por_fecha_y_cartero', {
                                cartero: cartero,
                                fecha: fecha
                            });

                response = resp.data;     
                
                console.log("respuesta: ", response)
                
                
                if (response.length > 0) {
                    //PUNTOS DE RESPUESTA
                    puntos_array = response;

                    let piezas = puntos_array.map( pieza => {
                        return {
                            'id': pieza.id,
                            'lat': pieza.latitud,
                            'lng': pieza.longitud,
                            'destinatario': pieza.destinatario,
                            'descripcion': pieza.descripcion_paquete,
                            'domicilio': pieza.domicilio,
                            'localidad': pieza.localidad,
                            'provincia': pieza.provincia
                        }
                    });
                    
                    document.getElementById('mapaMapLink').style.display = 'block'
                    //PARA RENDERIZAR CORRECTAMENTE EL MAPA DE MAPLINK UNA VEZ QUE SE MUESTRA 
                    maplink.map.invalidateSize();
                    
                    graficar_marcadores(piezas);          
                    
                } 
                else {
                    document.getElementById('mapaMapLink').style.display = 'none'
                    alert('Aviso:\nNo se encontraron datos.');
                }
                
                            

            } catch (err) {
                // Handle Error Here
                console.error("getPiezasHDR: ", err);
                ocultarTablaYMapa()
                alert('Aviso:\nOcurrio un error al consultar los datos. Por favor actualice la pagina y realice nuevamente la busqueda..')

            } finally{
                loading.css('display', 'none');
            }
        }

        function ocultarTablaYMapa(){
            document.getElementById('mapaMapLink').style.display = 'none'
            document.getElementById('tabla').style.display = "none"
        }

</script>