<style>
    .hr {
        border-color: #a8a8a8;
        margin-top: 10px;
    }

    .tituloFormulario {
        color: #697882;
        font-size: 22px;
        font-weight: 400;
        border-bottom: 2px solid #ADCE9D !important;
        padding-bottom: 7px;
        margin-bottom: 2rem;
        margin-top: 1.3rem;
    }
</style>

<link href="<?php echo base_url() ?>assets/rubenbackend/css/Styles/Styles/loading.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css" rel="stylesheet">

<!-- LIBRERIAS DE MAPA -->
<meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no" />
<script src="https://maps.maplink.global"></script>

<div id="loading" name="loading" style="display:none">
    <b id="loadingText" style="color: white;text-shadow: 4px 4px 8px #000000;"></b>
</div>

<div class="col-xs-12">
    <div class="ibox-content">
        <?= form_open_multipart(base_url('piezasN/Piezas_paquetes_masiva/uploadExcel'), array('class' => "form-paquete", 'id' => 'form-paquete')) ?>
        
        <input type="hidden" id="contador" value="0">
        
        <div id="errores"></div>
        
        <?php if ($this->session->flashdata('registro')) : ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-success" style="margin: 10px 20px 30px 20px;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <p><?php echo $this->session->flashdata('mensaje') ?></p>
                        <p><?php echo $this->session->flashdata('mensaje_descripcion') ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-xs-12">
                <h3 class="tituloFormulario">Cliente</h3>
            </div>
            <div class="col-xs-12">
                <div class="form-group">
                    <label for="pais">Pa&iacute;s: <span class="required">*</span></label>
                    <select id="pais_origen" name="pais_origen" onchange="cargarClientePorPais(event)" class="form-control">
                        <option value="0">Seleccione una opci&oacute;n</option>
                        <?php foreach($paises as $p) { ?>
                            <option value="<?php echo $p->id ?>" <?= isset($pais_origen) && $pais_origen == $p->id ? 'selected' : ''; ?> ><?php echo $p->nombre?></option>
                        <?php }?>
                    </select>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                    <label for="cliente_id">Cliente: <span class="required">*</span></label>
                    <input id="cliente_id" name="cliente_id" type="hidden" value="<?= isset($cliente_id) ? $cliente_id : ''; ?>" />
                    <input <?= isset($pais_origen) ? '' : 'disabled'; ?> id="cliente" name="cliente" type="text" class="form-control" placeholder="Escriba el nombre del cliente" autocomplete="off" required value="<?= isset($cliente) ? $cliente : ''; ?>" />
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                    <label for="departamento_id">Departamento: <span class="required">*</span></label>
                    <select <?= isset($pais_origen) ? '' : 'disabled'; ?> name="departamento_id" id="departamento_id" class="form-control">
                        <option value="0">Seleccione</option>
                        <?php foreach ($departamentos as $d) {
                            echo ("<option value='$d->id'>$d->nombre</option>");
                        } ?>
                    </select>
                </div>
            </div>
        </div>

        <div id="wrapper-paqueteria" style="margin-bottom: 1.5rem;">
        </div>

        <div id="piezas-paquetes">

            <hr>

            <div class="row">
                <div class="col-xs-12">
                    <h3 class="tituloFormulario">Origen</h3>
                </div>
                <div class="col-xs-12">
                    <div class="form-group">
                        <label for="zona_origen">Zona de Origen: <span class="required">*</span></label>
                        <select onchange="validarZona(event, 1)" name="zona_origen" id="zona_origen" class="form-control" style="width: 100%;">
                            <option value="0">Seleccione</option>
                        </select>
                    </div>
                </div>
                <!--
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="localidad_origen">Localidad de Origen: <span class="required">*</span></label>
                        <select disabled name="localidad_origen" id="localidad_origen" class="form-control" style="width: 100%;">
                            <option value="0">Seleccione</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="codigo_postal_origen">CP Origen: <span class="required">*</span></label>
                        <input type="text" name="codigo_postal_origen" id="codigo_postal_origen" class="form-control" placeholder="CP de origen" autocomplete="off">
                    </div>
                </div>
                -->
            </div>

            <hr>

            <div class="row">
                <div class="col-xs-12">
                    <h3 class="tituloFormulario">Destino</h3>
                </div>
                <div class="col-xs-12">
                    <div class="form-group">
                        <label for="zona_destino">Zona de Destino: <span class="required">*</span></label>
                        <select disabled onchange="validarZona(event, 2)" name="zona_destino" id="zona_destino" class="form-control" style="width: 100%;">
                            <option value="0">Seleccione</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div id="wrapper-tarifario" style="margin-bottom: 1.5rem;">
        </div>

        <hr>

        <?php 
            if(!is_null($cantidad_piezas)) {
                echo('<div class="alert alert-info" role="alert">
                    <a href="#" class="alert-link">Cantidad de Piezas: ' . $cantidad_piezas . '</a>
                </div>');
            }
        ?>

        <?php 
            if(!is_null($insertadas)) {
                echo('<div class="alert alert-success" role="alert">
                    <a href="#" class="alert-link">Piezas Insertadas: ' . $insertadas . '</a>
                </div>');
            }
        ?>

        <?php 
            if(!is_null($errores)){
                if(count($errores) > 0){
                    echo('<div class="alert alert-danger" role="alert">
                        <a href="#" class="alert-link">Piezas no insertadas: ' . count($errores) . ' </a>
                    </div>');

                    echo ('<ul class="list-group">');
                    foreach ($errores as $error) {
                        echo ('<a href="#" class="list-group-item list-group-item-danger">Fila: '. $error["fila"] . ' - Mensaje: ' . $error["mensaje"] . '</a>');
                    } 
                    echo ('</ul>');

                }else{
                    echo('<div class="alert alert-danger" role="alert">
                        <a href="#" class="alert-link">Piezas no insertadas: 0 </a>
                    </div>');
                }
            } 
        ?>
        
        <div id="inputFile" class="row" style="display: none;">
            <div class="col-xs-12">
                <div class="form-group">
                    <label for="archivo">Subir Archivo</label>
                    <input required type="file" name="archivo" id="archivo" class="form-control" accept="">
                    <input type="submit" value="Subir Archivo" class="btn btn-success">
                </div>
            </div>
        </div>
        
        
        <?= form_close(); ?>

        <table id="tabla-paquetes" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
            <thead>
                <tr class="header_table">
                    <th>Fecha</th>
                    <th>Comprobante Ingreso</th>
                    <th>Paquete</th>
                    <th>Descripcion</th>
                    <th>Peso</th>
                    <th>Dimensiones</th>
                    <th>Bultos</th>
                    <th>Palets</th>
                    <th>Metros Cubicos</th>
                    <th>Warehouse</th>
                    <th>Gestion Flota</th>
                    <th>Cantidad de Horas</th>
                    <th>Valor Declarado</th>
                    <th>Cobranza</th>
                </tr>
            </thead>
            <tbody id="body-grilla">
                <?php $class = "text-center";
                foreach ($paquetes as $value) : ?>
                    <tr class="<?php echo $class; ?>">
                        <td><?= $value->fecha_pedido ?></td>
                        <td><?= $value->comprobante_ingreso ?></td>
                        <td><?= $value->pieza_id ?></td>
                        <td><?= $value->descripcion_paquete ?></td>
                        <td><?= $value->peso ?></td>
                        <td><?= $value->dimensiones ?></td>
                        <td><?= $value->bultos ?></td>
                        <td><?= $value->palets ?></td>
                        <td><?= $value->metro_cubico ?></td>
                        <td><?= $value->warehouse ?></td>
                        <td>
                            <?php
                            echo $value->tipo_vehiculo == 1 && $value->tipo_hora == 1 ? 'Moto // Hora comun' : '';
                            echo $value->tipo_vehiculo == 1 && $value->tipo_hora == 2 ? 'Moto // Hora especial' : '';
                            echo $value->tipo_vehiculo == 2 && $value->tipo_hora == 1 ? 'Auto // Hora comun' : '';
                            echo $value->tipo_vehiculo == 2 && $value->tipo_hora == 2 ? 'Auto // Hora especial' : '';
                            echo $value->tipo_vehiculo == 3 && $value->tipo_hora == 1 ? 'Camioneta // Hora comun' : '';
                            echo $value->tipo_vehiculo == 3 && $value->tipo_hora == 2 ? 'Camioneta // Hora especial' : '';
                            echo $value->tipo_vehiculo == 4 && $value->tipo_hora == 1 ? 'Furgon // Hora comun' : '';
                            echo $value->tipo_vehiculo == 4 && $value->tipo_hora == 2 ? 'Furgon // Hora especial' : '';
                            echo $value->tipo_vehiculo == 5 && $value->tipo_hora == 1 ? 'Camion // Hora comun' : '';
                            echo $value->tipo_vehiculo == 5 && $value->tipo_hora == 2 ? 'Camion // Hora especial' : '';
                            ?>
                        </td>
                        <td><?= $value->cantidad_hora ?></td>
                        <td><?= $value->monto_valor_declarado ?></td>
                        <td><?= $value->monto_cobranza ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </div> <!-- .ibox-content -->
</div><!-- .col-xs-12 -->

<form method='post' action='<?php echo base_url('piezasN/piezas_paquetes_masiva/getPaqueteriaXCliente') ?>' id='getListPaqueteriaXCliente'>
    <input type='hidden' id='list_cliente_id' name='list_cliente_id' value="">
    <input type='hidden' id='list_cliente' name='list_cliente' value="">
    <input type="hidden" name="list_pais_id" id="list_pais_id" value="<?= $pais_origen ?>">
</form>

<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>

<script type="text/javascript">

    $('#tabla-paquetes').DataTable({
        scrollX: true,
        "order": [
            [1, "desc"]
        ],
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
                "infoFiltered": ""
            }
    });

    let departamentos = <?= json_encode($departamentos); ?>;
    let cliente_id = <?= json_encode($cliente_id); ?>;
    let paquetes = <?= json_encode($paquetes); ?>;
    let tarifario = <?= json_encode($tarifario); ?>;

    const loading = $('#loading');

    let zonas = <?= json_encode($zonas); ?>;

    $('#localidad').select2();
    $('#localidad_origen').select2();
    $('#zona_origen').select2();
    $('#zona_destino').select2();
    $('#provincia_destino').select2();
    $('#pais_destino').select2();

    //cargarLocalidades();
    cargarZonas();

    let clientes = null

    <?php if(isset($clientes)){?>
        clientes = <?php echo json_encode($clientes)?>;
    <?php } ?>

    async function cargarClientePorPais(event){
        loading.css('display', 'block');

        try {
            const pais = event.target.value
            
            document.getElementById('list_pais_id').value = pais
            
            clientes = null
            document.getElementById('cliente').value = ""
            autocomplete.data('typeahead').source = clientes;

            const url = `<?php echo base_url() ?>piezasN/piezas_paquetes/getClientes?pais=${pais}`

            const resp = await fetch(url)
            const data = await resp.json()
            
            console.log(data)

            if(data.data.length > 0){      
                //PARA CARGAR EL INPUT DE CLIENTES
                clientes = data.data
                autocomplete.data('typeahead').source = clientes;
                //HABILITO LOS INPUTS
                document.getElementById('cliente').disabled = false
            }

        } catch (error) {
            console.log("cargarClientePorPais: ", error)
        } finally{
            loading.css('display', 'none');
        }
    }

    const autocomplete = $('#cliente').typeahead({
        //source: clientes,
        onSelect: function(item) {
            $('#cliente_id').val(0);
            if (item.value) {
                $('#cliente_id').val(item.value);
                $('#list_cliente_id').val(item.value);
                $('#list_cliente').val(item.text);
                $('#getListPaqueteriaXCliente').submit();
            }
        }
    });

    <?php if(!empty($clientes)){ ?>
        autocomplete.data('typeahead').source = clientes;
    <?php }?>


    function validarCampos(event) {

    }

    async function getMetodosTarifario() {
        const cliente_id = document.getElementById('cliente_id').value
        const origen = document.getElementById('zona_origen').value
        const destino = document.getElementById('zona_destino').value

        if (!cliente_id) {
            console.log("No existe el id del cliente aun...")
            return;
        }

        const data = await getMetodosTarifariosXCliente(cliente_id, origen, destino)

        if (data['data'].tarifario) {
            const resultado = formatearResultado(data)
            construirSelectTarifario(resultado)
        } else {
            alert("El cliente no tiene un tarifario con las zonas origen y destino definidas")
            $('#zona_destino').select2("val", "0");
            $('#localidad').prop('disabled', true);
            $('#localidad').select2("val", "0");
        }



        //console.log("Metodos disponibles para el cliente: ",resultado)
    }

    function mostrarInputFile(){
        document.getElementById('inputFile').style.display = 'block'
    }

    function ocultarInputFile(){
        document.getElementById('inputFile').style.display = 'none'
    }

    async function getMetodosTarifariosXCliente(cliente, origen, destino) {
        loading.css('display', 'block');

        const dominio = document.domain;
        const url = `//${dominio}/piezasN/Piezas_paquetes/getTarifarioXCliente`;

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    cliente,
                    origen,
                    destino
                })
            });

            const data = await response.json();

            if (data) {
                return data
            }

            return false

        } catch (error) {
            console.error("getMetodosTarifariosXCliente: ", error)
        } finally{
            loading.css('display', 'none');
        }

    }

    function formatearResultado(data) {
        let tarifarios = {}

        if (data['data'].kilos == "TRUE") {
            tarifarios.kilos = true;
        }

        if (data['data'].bultos == "TRUE") {
            tarifarios.bultos = true;
        }

        if (data['data'].palets == "TRUE") {
            tarifarios.palets = true;
        }

        if (data['data'].warehouse == "TRUE") {
            tarifarios.warehouse = true;
        }

        if (data['data'].metrosCubicos == "TRUE") {
            tarifarios.metrosCubicos = true;
        }

        if (data['data'].gestionFlota == "TRUE") {
            tarifarios.gestionFlota = true;
        }

        if (data['data'].valorDeclarado == "TRUE") {
            //tarifarios.valorDeclarado = true;
            console.log("Tiene valor declarado configurado")
            valorDeclarado = true;
        }

        if (data['data'].cobranza == "TRUE") {
            //tarifarios.cobranza = true;
            console.log("Tiene cobranza configurado")
            cobranza = true;
        }

        if (data['data'].pesoAforado == "TRUE") {
            //tarifarios.pesoAforado = true;
            console.log("Tiene peso aforado configurado")
        }

        if (data['data'].distancia == "TRUE") {
            tarifarios.distancia = true;
        }

        return tarifarios;

    }

    function construirSelectTarifario(data) {
        const contenedor = document.getElementById("wrapper-tarifario")
        contenedor.innerHTML = ""

        let html = `
        <hr>
        <div class="row">
            <div class="col-xs-12">
                <h3 class="tituloFormulario">Opciones para tarifar al cliente</h3>
            </div>
            <div class="col-xs-12">
                <label for="tarifario">Opciones para tarifar al cliente</label>
                <select id="tarifario" class="form-control" onchange="mostrarArchivo(event)">
    
                    <option value="0">Seleccione</option>
    
        console.log("Tarifario seleccionado: ", event.target.value)
                    `;

        for (const key in data) {
            switch (key) {
                case 'kilos':
                    html += `<option value="${key}">Kilos</option>`
                    break;

                case 'bultos':
                    html += `<option value="${key}">Bultos</option>`
                    break;

                case 'palets':
                    html += `<option value="${key}">Palets</option>`
                    break;

                case 'metrosCubicos':
                    html += `<option value="${key}">Metros Cubicos</option>`
                    break;

                case 'warehouse':
                    html += `<option value="${key}">Warehouse</option>`
                    break;

                case 'gestionFlota':
                    html += `<option value="${key}">Gesti&#243;n de Flota</option>`
                    break;

                case 'distancia':
                    html += `<option value="${key}">Distancia</option>`
                    break;

                    /*
                    case 'valorDeclarado':
                        html += `<option value="${key}">Valor Declarado</option>`
                        break;

                    case 'cobranza':
                        html += `<option value="${key}">Cobranza</option>`
                        break;

                    case 'pesoAforado':
                        html += `<option value="${key}">Aforo</option>`
                        break;
                    */

                default:
                    break;
            }

        }

        html += "</select></div></div>"

        contenedor.insertAdjacentHTML('beforeend', html)
    }


    async function mostrarArchivo(event) {
        loading.css('display', 'block')

        console.log("Tarifario seleccionado: ", event.target.value)
        mostrarInputFile()

        loading.css('display', 'none')
    }

    async function cargarLocalidades(zona, select) {

        const data = await getLocalidades(zona)

        if (data) {
            let html = ""
            for (let index = 0; index < data.data.length; index++) {
                const element = data.data[index];
                html += `<option value="${element.id}">${element.nombre}</option>`
            }
            document.getElementById(`${select}`).innerHTML = "<option value='0'>Seleccione</option>"
            document.getElementById(`${select}`).insertAdjacentHTML('beforeend', html)
        }

    }

    async function cargarPaisDestinoPorZona(zona, select) {

        try {
            
            const data = await getPaisPorZona(zona)
    
            if (data) {
                let html = ""
                for (let index = 0; index < data.data.length; index++) {
                    const element = data.data[index];
                    html += `<option value="${element.id}">${element.nombre}</option>`
                }
                document.getElementById(`${select}`).innerHTML = ""
                document.getElementById(`${select}`).insertAdjacentHTML('beforeend', html)
            }

        } catch (error) {
            console.log("cargarPaisDestinoPorZona: ", error)
        }

    }

    async function cargarProvinciasDestinoPorZona(zona, select) {

        try {
            
            const data = await getProvinciasPorZona(zona)

            if (data) {
                let html = ""
                for (let index = 0; index < data.data.length; index++) {
                    const element = data.data[index];
                    html += `<option value="${element.id}">${element.nombre}</option>`
                }
                document.getElementById(`${select}`).innerHTML = "<option value='0'>Seleccione</option>"
                document.getElementById(`${select}`).insertAdjacentHTML('beforeend', html)
            }

        } catch (error) {
            console.log("cargarProvinciasDestinoPorZona: ", error)
        }

    }

    async function cargarLocalidadesPorProvincia(event){
        try {

            const provincia = event.target.value

            const data = await getLocalidadesPorProvincia(provincia)

            if (data) {
                let html = ""
                for (let index = 0; index < data.data.length; index++) {
                    const element = data.data[index];
                    html += `<option value="${element.id}">${element.nombre}</option>`
                }
                document.getElementById('localidad').innerHTML = "<option value=''>Seleccione una localidad</option>"
                document.getElementById('localidad').insertAdjacentHTML('beforeend', html)
                document.getElementById('localidad').disabled = false
            }
            
        } catch (error) {
            console.log("cargarLocalidadesPorProvincia: ", error)
        }
    }

    async function getLocalidades(zona) {
        const dominio = document.domain;
        const url = `//${dominio}/piezasN/Piezas_paquetes/getLocalidadesPorZona`;

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    zona
                })
            });

            const data = await response.json();

            if (data) {
                return data
            }

            return false

        } catch (error) {
            console.error("getLocalidades: ", error)
        }

    }

    async function getPaisPorZona(zona) {
        const dominio = document.domain;
        const url = `//${dominio}/piezasN/Piezas_paquetes/getPaisPorZona`;

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    zona
                })
            });

            const data = await response.json();

            if (data) {
                return data
            }

            return false

        } catch (error) {
            console.error("getPaisPorZona: ", error)
        }

    }

    async function getProvinciasPorZona(zona) {
        const dominio = document.domain;
        const url = `//${dominio}/piezasN/Piezas_paquetes/getProvinciasPorZona`;

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    zona
                })
            });

            const data = await response.json();

            if (data) {
                return data
            }

            return false

        } catch (error) {
            console.error("getProvinciasPorZona: ", error)
        }

    }

    async function getLocalidadesPorProvincia(provincia){
        loading.css('display', 'block')

        const dominio = document.domain;
        const url = `//${dominio}/piezasN/Piezas_paquetes/getLocalidadesPorProvincia`;

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    provincia: provincia,
                    zona: document.getElementById('zona_destino').value
                })
            });

            const data = await response.json();

            if (data) {
                return data
            }

            return false

        } catch (error) {
            console.error("getLocalidadesPorProvincia: ", error)
        } finally{
            loading.css('display', 'none')
        }
    }

    function cargarZonas() {
        if (zonas) {
            let html = ""
            for (let index = 0; index < zonas.length; index++) {
                const element = zonas[index];
                html += `<option value="${element.id}">${element.nombre}</option>`
            }

            document.getElementById("zona_origen").insertAdjacentHTML('beforeend', html)
            document.getElementById("zona_destino").insertAdjacentHTML('beforeend', html)
        }
    }

    async function validarZona(event, opcion) {
        event.preventDefault();
        //OPCION 
        // 1 = ORIGEN
        // 2 = DESTINO

        valorDeclarado = null;
        cobranza = null;

        const cliente = document.getElementById('cliente_id')
        const zona = event.target.value

        if (!cliente.value) {
            alert("No selecciono el cliente")
            return;
        }

        if (zona == "0") {
            return;
        }

        const data = await comprobarQueLaZonaSeEncuentreDentroDelEsquema(cliente.value, zona, opcion)

        document.getElementById("wrapper-tarifario").innerHTML = ""
        document.getElementById("wrapper-paqueteria").innerHTML = ""

        if (data['data'].resultado == "FALSE") {
            alert("La zona elegida no se encuentra dentro del esquema tarifario del cliente")

            if (opcion == 1) {
                $(`#${event.target.id}`).select2("val", "0");
                $(`#${event.target.id}`).focus()
                $('#zona_destino').prop('disabled', true);
                $('#zona_destino').select2("val", "0");
                $('#localidad_origen').prop('disabled', true);
                $('#localidad_origen').select2("val", "0");
                $('#localidad').prop('disabled', true);
                $('#localidad').select2("val", "0");
                return;
            }

            if (opcion == 2) {
                $(`#${event.target.id}`).select2("val", "0");
                $(`#${event.target.id}`).focus()
                $('#localidad').prop('disabled', true);
                $('#localidad').select2("val", "0");
                return;
            }


        } else {

            //PASO LA VALIDACION DE LA ZONA
            if (opcion == 1) {
                $('#zona_destino').prop('disabled', false);
                $('#zona_destino').select2("val", "0");
                $('#localidad_origen').prop('disabled', false);
                //cargarLocalidades(zona, 'localidad_origen')
            }

            if (opcion == 2) {
                //cargarLocalidades(zona, 'localidad')
                //$('#localidad').prop('disabled', false);
                
                //cargarPaisDestinoPorZona(zona, 'pais_destino', 1)
                //cargarProvinciasDestinoPorZona(zona, 'provincia_destino')

                getMetodosTarifario()
            }


        }


    }

    async function comprobarQueLaLocalidadSeEncuentreDentroDeLaZonaDelEsquema(cliente, localidad, opcion) {
        const dominio = document.domain;
        const url = `//${dominio}/piezasN/Piezas_paquetes/comprobarLocalidaDentroDeZonaEsquema`;

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    cliente,
                    localidad,
                    opcion
                })
            });

            const data = await response.json();

            if (data) {
                return data
            }

            return false

        } catch (error) {
            console.error("comprobarQueLaLocalidadSeEncuentreDentroDeLaZonaDelEsquema: ", error)
        }

    }

    async function comprobarQueLaZonaSeEncuentreDentroDelEsquema(cliente, zona, opcion) {
        loading.css('display', 'block')

        const dominio = document.domain;
        const url = `//${dominio}/piezasN/Piezas_paquetes/comprobarZonaDentroDelEsquema`;

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    cliente,
                    zona,
                    opcion
                })
            });

            const data = await response.json();

            if (data) {
                return data
            }

            return false

        } catch (error) {
            console.error("comprobarQueLaZonaSeEncuentreDentroDelEsquema: ", error)
        } finally{
            loading.css('display', 'none')
        }

    }

    function enviar() {

        

    }

</script>