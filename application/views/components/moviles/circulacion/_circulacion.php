<?php
$user_row = $this->ion_auth->user()->row();
?>

<!-- LIBRERIAS DE MAPA -->
<meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no" />
<script src="https://maps.maplink.global"></script>


<div class="col-xs-12">
    <div class="ibox-content">
        <?php Elementos::CrearTitulo("Trabajando..."); ?>
        <br>
        <div class="row">
            <input hidden name="moviles" id="moviles">

            <div class="col-xs-12">
                <div class="form-group">
                    <label for="sucursal">Sucusal</label>
                    <select onchange="modificar_filtro()" name="sucursal" id="sucursal" class="form-control" style="width: 100%;">
                        <option value="0">Todas</option>
                        <?php foreach ($sucursales as $r) { ?>
                            <option value="<?php echo $r['id'] ?>"><?php echo $r['nombre'] ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div class="col-xs-12">
                <div class="form-group">
                    <label for="tipo_cartero">Tipo de Cartero</label>
                    <select onchange="modificar_filtro()" name="tipo_cartero" id="tipo_cartero" class="form-control" style="width: 100%;">
                        <option value="0">Todos</option>
                        <?php foreach ($tipos_carteros as $tipo) { ?>
                            <option value="<?php echo $tipo['id'] ?>"><?php echo $tipo['nombre'] ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div class="col-xs-12">
                <div class="form-group">
                    <label for="cartero">Cartero/s</label>
                    <select name="cartero" id="cartero" class="form-control" style="width: 100%;">
                        <option value="0">Todos</option>
                        <?php foreach ($carteros as $r) { ?>
                            <option value="<?php echo $r['id'] ?>"><?php echo $r['apellido_nombre'] ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div class="col-xs-12">
                <div class="form-group col-auto">
                    <label for="fecha">Fecha</label>
                    <input type="date" name="fecha" id="fecha" class="form-control" value="<?php echo $fecha?>">
                </div>
            </div>

            <div class="col-xs-12">
                <button class="btn btn-primary" onclick="buscar()">Buscar</button>
            </div>
        </div>
        <div id="mapaMapLink" style="width: 100%; height: 500px; margin: 20px auto; display: none;"></div>
        <div>

        </div>
    </div>


    <script src="<?php echo base_url() ?>assets/js/ExtencionesJS/moment.min.js"></script>
    <script src="<?php echo base_url() ?>assets/js/api_mapa_servicios/servicios_maplink.js"></script>

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

        const carteros = <?= json_encode($carteros) ?>;

        $('#tipo_cartero').select2();
        $('#sucursal').select2();
        $('#cartero').select2();

        const loading = $('#loading');

        function modificar_filtro() {
            const fSucursal = document.getElementById('sucursal').value
            const fTipoCartero = document.getElementById('tipo_cartero').value

            $('#cartero').empty()

            $('#cartero').append(new Option("Seleccione un Cartero", "0", false, false)).trigger('change')

            carteros.filter(c => {
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
        }

        function buscar(){
            console.log("buscando....")
            
            const cartero = document.getElementById('cartero').value;
            const fecha = document.getElementById('fecha').value;

            console.log("cartero: ", cartero)
            console.log("fecha: ", fecha)

            if (cartero == '' || cartero == '0' || fecha == '') {
                alert('Aviso:\nPara realizar una busqueda debe seleccione una fecha y un cartero.');
                return;
            }

            loading.css('display', 'block');

            sendPostRequest(cartero, fecha);
        }

        async function sendPostRequest(cartero, fecha){
            try {
                const resp = await axios.post('/moviles/circulacion/recorrido_cartero', {
                                cartero: cartero,
                                fecha: fecha
                            });

                response = resp.data;                
                
                if (response.status == true) {
                    
                    puntos_array = response.recorrido;

                    let recorridoMap = response.recorrido.map((lugar, index) => {
                        return {
                            'orden': index,
                            'lat': lugar.Latitude,
                            'lng': lugar.Longitude,
                            'hora': lugar.tiempo,
                            'id': lugar.id
                        }
                    });

                    console.log("recorridoMap: ", recorridoMap)

                    document.getElementById('mapaMapLink').style.display = 'block'
                    //PARA RENDERIZAR CORRECTAMENTE EL MAPA DE MAPLINK UNA VEZ QUE SE MUESTRA 
                    maplink.map.invalidateSize();
                    
                    graficar_recorrido_cartero(recorridoMap, false);          
                
                } 
                else {
                    alert('Aviso:\nNo se encontraron datos.');
                }
                            

            } catch (err) {
                // Handle Error Here
                console.error(err);
                
                alert('Aviso:\nOcurrio un error al consultar los datos. Por favor actualice la pagina y realice nuevamente la busqueda..')

            } finally{
                loading.css('display', 'none');
            }
        };

</script>