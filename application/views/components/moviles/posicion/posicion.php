<?php 
    $user_row = $this->ion_auth->user()->row();
?>

<!-- LIBRERIAS DE MAPA -->
<meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no" />
<script src="https://maps.maplink.global"></script>


<div class="col-xs-12">
    <div class="ibox-content">
        <?php Elementos::CrearTitulo("Mapa Online"); ?>
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
                    <label for="sucursal">Sucursal</label>
                    <select onchange="cambioSucursal(event)" name="sucursal" id="sucursal" class="form-control" style="width: 100%;">
                        <option value="">Seleccione una opción</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-12">
                <div class="form-group">
                    <label for="tipo_cartero">Tipo de Cartero</label>
                    <select name="tipo_cartero" id="tipo_cartero" class="form-control" style="width: 100%;">
                        <option value="0">Todos</option>
                        <?php foreach ($tipos_carteros as $tipo) { ?>
                            <option value="<?php echo $tipo['id']?>"><?php echo $tipo['nombre']?></option>
                        <?php }?>
                    </select>
                </div>
            </div>
            <div class="col-xs-12">
                <div class="form-group">
                    <label for="cartero">Cartero/s</label>
                    <select onchange="cambioCartero(event)" name="cartero" id="cartero" class="form-control" style="width: 100%;">
                        <option value="">Seleccione una opción</option>
                    </select>
                </div>
            </div>
            
            <div class="col-xs-12">
                <div class="form-group">
                    <label for="cliente">Clientes</label>
                    <select onchange="cambioCliente(event)" name="cliente" id="cliente" class="form-control" style="width: 100%;">
                        <option value="">Seleccione una opción</option>
                    </select>
                </div>
            </div>
            
            <div class="col-xs-12">
                <button class="btn btn-primary" onclick="mostrar_carteros(0,0)"><i class="fa fa-eye" aria-hidden="true"></i> Visualizar</button>
                <!-- <button class="btn btn-warning" onclick="limpiar_filtros()"><i class="fa fa-refresh" aria-hidden="true"></i> Limpiar Filtros</button> -->
            </div>
         </div> 
        <div id="mapaMapLink" style="width: 100%; height: 500px; margin: 20px auto; display: none;"></div>
        <div>
        <h4 class="stat-cards-info__num ml-4"></h4>
           <?php 
                Elementos::CrearTabladashboard("DatosMoviles","12","","display:block",true,100,"text-align: left;","text-align: right;",true,"","text-align: left;","white-space: unset;");
            ?>
        </div>
    </div>
</div>


<script src="<?php echo base_url() ?>assets/js/ExtencionesJS/moment.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/api_mapa_servicios/servicios_maplink.js"></script>

<script>
    var us_nombre = <?php echo("'" . $user_row->nombre . " " . $user_row->apellido) . "'"?>;
    var UserId = <?php echo("'" .$user_row->user_id). "'"?>;
    var SucursalesDeUsuario = <?php echo('[' . $user_row->sucursal_id) . ']'?>;

    //var URLJS = "http://prueba.sispo.com.ar/ajax";
    const URLJS = `//${document.domain}`;
    var CSRF_TOKEN ="";
    var api_token ="";

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
    $('#cliente').select2({
        disabled: true
    });

    const loading = $('#loading');

    let sucursales = null
    let clientes = null
    let carteros = null
    let carteros_ids = null
    let banderaError = false
    let timerID = null

    async function cambioPais(event){
        const pais = event.target.value
        if(pais != "" && pais != 0){
            try {
                loading.css('display', 'block');

                sucursales = await getSucursales(pais)
                clientes = await getClientes(pais)
                carteros = await getCarteros(pais)

                if(sucursales){
                    cargarSelect('sucursal', sucursales, 'id', 'nombre', 'Todas', true)
                }

                if(clientes){
                    cargarSelect('cliente', clientes, 'id', 'nombre', 'Seleccione una opción', true)
                }

                if(carteros){
                    cargarSelect('cartero', carteros, 'id', 'apellido_nombre', 'Todos', true)
                }

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

    async function getClientes(pais){
        try {
            const dominio = document.domain;
            const url = `//${dominio}/moviles/posicion/getClientes`;

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

    function cambioSucursal(event){
        const sucursal = event.target.value
        if(sucursal != 0){
            document.getElementById('cartero').disabled = true
            document.getElementById('cliente').disabled = true
        }else{
            document.getElementById('cartero').disabled = false
            document.getElementById('cliente').disabled = false
        }
    }

    function cambioCartero(event){
        const cartero = event.target.value
        if(cartero != 0){
            document.getElementById('tipo_cartero').disabled = true
            document.getElementById('sucursal').disabled = true
        }else{
            document.getElementById('tipo_cartero').disabled = false
            document.getElementById('sucursal').disabled = false
        }
    }

    function cambioCliente(event){
        const cliente = event.target.value

        carteros_ids = null

        if(cliente != 0){
            document.getElementById('tipo_cartero').disabled = true
            document.getElementById('sucursal').disabled = true
            document.getElementById('cartero').disabled = true
        }else{
            document.getElementById('tipo_cartero').disabled = false
            document.getElementById('sucursal').disabled = false
            document.getElementById('cartero').disabled = false
        }
    }

    function validarSelectValido(){
        const cliente = document.getElementById('cliente')
        const cartero = document.getElementById('cartero')
        const sucursal = document.getElementById('sucursal')
        const pais = document.getElementById('pais')

        if(pais.value == "" || pais.value == "0"){
            return false
        }

        return true
    }

    async function getCarterosPorCliente(cliente){
        try {
            const dominio = document.domain;
            const url = `//${dominio}/moviles/posicion/getCarterosPorCliente`;

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({cliente})
            })

            const data = await response.json();

            console.log(data)

            if(data){
                return data.data
            }

            return false

        } catch (error) {
            console.log("getCarterosPorCliente: ", error)
        }
    }

    function limpiar_filtros(){
        console.log("limpiando filtros")
        /*
        const cliente = document.getElementById('cliente')
        const cartero = document.getElementById('cartero')
        const sucursal = document.getElementById('sucursal')
        const pais = document.getElementById('pais')
        const tipo_cartero = document.getElementById('tipo_cartero')
        */

        //Reestablecer la seleccion de pais
        $("#pais").val("0");
        $("#pais").trigger('change');
        //Limpiar y desabilitar los selects
        document.getElementById('cartero').innerHTML = ''
        $("#cartero").append(new Option("Seleccione una opción", 0, false, false)).trigger('change')
        $("#cartero").val("0");
        $("#cartero").trigger('change');
        //$("#cartero").prop("disabled", true);
        document.getElementById('cartero').disabled = true

        document.getElementById('sucursal').innerHTML = ''
        $("#sucursal").append(new Option("Seleccione una opción", 0, false, false)).trigger('change')
        $("#sucursal").val("0");
        $("#sucursal").trigger('change');
        //$("#sucursal").prop("disabled", true);
        document.getElementById('sucursal').disabled = true

        document.getElementById('cliente').innerHTML = ''
        $("#cliente").append(new Option("Seleccione una opción", 0, false, false)).trigger('change')
        $("#cliente").val("0");
        $("#cliente").trigger('change');
        $("#cliente").prop("disabled", true);
        //cliente.disabled = true

        //Tipo de cartero solo desabilitar
        $("#tipo_cartero").val("0");
        $("#tipo_cartero").trigger('change');
        $("#tipo_cartero").prop("disabled", true);
        //tipo_cartero.disabled = true
    }

    async function mostrar_carteros(origen_llamado,cartero){
        banderaError = false;
        clearTimeout(timerID);

        console.log("Entro a mostrar_carteros")
        //VALIDO QUE POR LO MENOS EL PAIS SE SELECCIONE
        if(!validarSelectValido()){
            banderaError = true
            console.log("No paso la validacion de select")
            alert('Debe seleccionar el país')
            return; 
        }

        let id_cartero = 0;
        
        if(!origen_llamado){
            id_cartero = document.getElementById("cartero").value;    
        }else{
            id_cartero = cartero;
        }

        //SI ES origen_llamado = 1 SIGNIFICA QUE QUIERO ENCONTRAR LA POSICION ACTUAL 
        //Y NO ME INTERESA SABER SI SE SELECCIONO UN CLIENTE ANTES O NO
        if(origen_llamado != 1){

            //VERIFICO SI SE SELECCIONO UN CLIENTE
            const cliente = document.getElementById('cliente').value
            if(cliente != "0"){
                try {
                    //SE SELECCIONO UN CLIENTE, TENGO QUE BUSCAR LOS CARTEROS ASOCIADOS AL CLIENTE
                    console.log("Cliente seleccionado: ", cliente)
                    const ids = await getCarterosPorCliente(cliente)
                    carteros_ids = []
    
                    if(ids.length > 0){
                        ids.forEach(el => carteros_ids.push(el.idCartero))
                        carteros_ids = carteros_ids.toString()
    
                        //AQUI ASIGNO LOS IDS A LA PETICION
                        id_cartero = carteros_ids
                    }else{
                        banderaError = true
                        alert("No se encuentran carteros con HDR haciendo recorridos para este cliente")
                        document.getElementById('mapaMapLink').style.display = 'none'
                        document.getElementById('DivDatosMoviles').style.display = 'none'
                        return
                    }
    
                } catch (error) {
                    banderaError = true
                    console.log(error)
                }
            }

        }
        
        let valores = 0;

        if(id_cartero == 0){
            let id_provincia = document.getElementById("sucursal").value;
            let id_tipo = document.getElementById("tipo_cartero").value;
          
            parametros = {"id_provincia":id_provincia,"id_tipo":id_tipo};
            valores = id_provincia + '_' + id_tipo;
        }
        else{
            parametros = {"id_cartero":id_cartero};
            valores = id_cartero;
        }
        
        
       // console.log(valores);
        
        document.getElementById("moviles").value = valores;
        
        console.log("parametros: ", parametros)
        console.log("valores: ", valores)
        //return; 

        buscar_datos_carteros(origen_llamado,cartero);
    }

    function buscar_datos_carteros(origen_llamado,cartero){

        filtro=["IdUsuario","User","time","SucursalesDeUsuario"];
        filtroX=[UserId,"","0",SucursalesDeUsuario];
        var Parametros = ArraydsAJson(filtro,filtroX);
        Parametros = JSON.stringify(Parametros);// Manda Como Texto
        var Indices=["moviles", "pais"];
        var Objetos = ["moviles", "pais"];
        
        var ValoresDirectos = ArraydsAJson(Indices,Objetos);//Manda Como Objeto En SelectDesdeConsulta Se Transforma En Terxto

        var EsconderElementos=["6","7"];

        var BotonsParaTabla = [
            {
                Columna:''
                ,Funciones:
                [
                    'VerUbicacionCartero'
                ]
                , Texto:
                [
                    "Ubicacion"//Ver Datos De Pieza1
                ]
                , Classe:
                [
                    'btn btn-primary'

                ]
                , Icono:
                [
                    'fa fa-edit'
                ]
                , EstiloDeIcono:
                [
                    ''
                ]
            }
        ];

        var Config = JSON.parse(`{
            "Elemento":"DivDatosMoviles",
            "ConFiltro":true,
            "ConPaginado":true,
            "DataAjax":` + Parametros + `,
            "ValoresDirectos":` + ValoresDirectos + `,
            "MensajeEnFail":true,
            "TextoEnFail":"No Se Encontraron Resultados",
            "EsconderElementos":[` + EsconderElementos + `],
            "DescargaDeFiltro":0,
            
            "BotonsParaTabla":` + JSON.stringify(BotonsParaTabla) + `,

            "AddCheckbox":true,
            "CrearAlCargarDatos":true,
            "Ajax":"` + URLJS + `/moviles/posicion/ajax_datos_moviles"
            
        }`);


        ElementoDesdeApi(Config);
        
        buscar_datos_mapa(origen_llamado,cartero);
    }

    async function buscar_datos_mapa(origen_llamado, cartero){

        let id_cartero = 0;

        if(!origen_llamado){
            id_cartero = document.getElementById("cartero").value;    
        }
        else{
            id_cartero = cartero;
        }

        //SI EXISTE carteros_ids ES QUE EL CLIENTE FUE SELECCIONADO Y SON SUS CARTEROS
        if(carteros_ids){
            id_cartero = carteros_ids
        }

        console.log("BUSCAR DATOS MAPA carteros_ids: ", carteros_ids)
        console.log("BUSCAR DATOS MAPA: ", id_cartero)

        let pais = document.getElementById('pais').value

        if(id_cartero == 0){
            let id_provincia = document.getElementById("sucursal").value;
            let id_tipo = document.getElementById("tipo_cartero").value;
        
            parametros = {"id_provincia":id_provincia,"id_tipo":id_tipo};
        }
        else{
            parametros = {"id_cartero":id_cartero};
        }        
        
        
        $.ajax({
                data:{parametros:parametros, pais:pais},
                url:"/moviles/posicion/carteros_posiciones",
                type: "POST",
                success: function(response){
                    
                    response = JSON.parse(response)
                    
                    if(response != 'error'){
                        
                        console.log("Respuesta del servidor: ", response);

                        if(response[0] && response[0].length > 0){

                            var posiciones_carteros2 = response[0].map(function(campo) { //campos seteados
                                return {
                                    'lat': campo.Latitude,
                                    'lng': campo.Longitude,
                                    'nombre_cartero': campo.nombre,
                                    'id': 0,
                                    'destinatario': '',
                                    'domicilio': '',
                                    'orden': '',
                                    'fecha_hora': campo.FechaLocal,
                                }
                            });
                            
                            console.log(posiciones_carteros2);

                            document.getElementById('mapaMapLink').style.display = 'block'
                            document.getElementById('DivDatosMoviles').style.display = 'block'
                            //PARA RENDERIZAR CORRECTAMENTE EL MAPA DE MAPLINK UNA VEZ QUE SE MUESTRA 
                            maplink.map.invalidateSize();
                    
                            
                            graficar_carteros(posiciones_carteros2)
                            
                            //REPETIR X VECES LA ACTUALIZACION DEL MAP
                            
                            timerID = setTimeout(async () => {
                                try {
                                    if(!banderaError){
                                        console.log("Repitiendo llamada")
                                        await buscar_datos_mapa(origen_llamado, cartero)
                                    }
                                } catch (error) {
                                    console.log("setInterval:", error)
                                }
                            }, 10000);
                            
                            
                            
                        }else{
                            banderaError = true
                            alert('Aviso:\nEl/Los carteros seleccionados no registran datos de posicion en el dia de hoy.')
                            document.getElementById('mapaMapLink').style.display = 'none'
                            document.getElementById('DivDatosMoviles').style.display = 'none'
                        }
                        

                        //console.log(response[1]);
                        
                        if(response[1] > 1){
                            document.getElementById('cantidad_total').innerHTML = response[1];
                            document.getElementById('cantidad_logueada').innerHTML = response[0].length;
                        }
                    }

                    if(response=="error")
                    {
                        banderaError = true
                        alert('No se encontraron datos');
                        document.getElementById('mapaMapLink').style.display = 'none'
                        document.getElementById('DivDatosMoviles').style.display = 'none'
                    }                
                    
                },

                error: function (xhr, ajaxOptions, thrownError) {
                    banderaError = true
                    alert(xhr.status);
                    alert(thrownError);
                    document.getElementById('mapaMapLink').style.display = 'none'
                    document.getElementById('DivDatosMoviles').style.display = 'none'
                }
            });  
            

               
    }

    function VerUbicacionCartero(e){
        var id = document.getElementById("DivDatosMoviles").Config.Resultado[e.Data][6];
        mostrar_carteros(1,id);
    }

</script>