<div class="col-xs-12">
  <div class="ibox-content">
<!--    <div class="well">
      Al cargar piezas simples cuyo stock se va a enviar a diferentes sucursales, crear una pieza
      por cada sucursal con la cantidad correspondiente. En este caso recomendamos agregar en
      "Datos Varios" el destino donde tiene que ser luego despachado el stock de la pieza.
    </div>-->
    <?=form_open(base_url('piezasN/Piezas_paquetes/add'), array('class'=>"form-paquete", 'id'=>'form-paquete'))?>
      <input type="hidden" id="contador" value="0">
      <div id="errores"></div>
      <?php if($this->session->flashdata('registro')): ?>
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
      <div>
        <div class="row">
          <div class="col-xs-12">
            <div class="form-group">
                <label for="cliente_id">Cliente</label>
                <input id="cliente_id" name="cliente_id" type="hidden" value="<?=isset($cliente_id)?$cliente_id:'';?>" />
                <input id="cliente" name="cliente" type="text" class="form-control" placeholder="Escriba el nombre del cliente" autocomplete="off" required value="<?=isset($cliente)?$cliente:'';?>" />
            </div>
            <div id="div_acuses"></div>
            <div id="div_etiquetas"></div>  
          </div>
          <div class="col-xs-12">
            <div class="form-group">
                <label for="departamento_id">Departamento</label>
                <select name="departamento_id" id="departamento_id" class="form-control">
                    <option value="0">Seleccione</option>
                    <?php foreach ($departamentos as $d) {
                        echo("<option value='$d->id'>$d->nombre</option>");
                    } ?>
                </select>
            </div>
            <div id="div_acuses"></div>
            <div id="div_etiquetas"></div>  
          </div>
        </div>

        <div id="paqueteria">

        </div>

        <div id="piezas-paquetes">

         <hr>

         <div class="row">
            <div class="col-xs-12">
                <div class="form-group">
                    <label for="zona_origen">Zona de Origen</label>
                    <select onchange="validarZona(event, 1)" name="zona_origen" id="zona_origen" class="form-control" style="width: 100%;">
                        <option value="0">Seleccione</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                    <label for="localidad_origen">Localidad de Origen</label>
                    <select disabled name="localidad_origen" id="localidad_origen" class="form-control" style="width: 100%;">
                        <option value="0">Seleccione</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                    <label for="codigo_postal_origen">CP Origen</label>
                    <input type="text" name="codigo_postal_origen" id="codigo_postal_origen" class="form-control" placeholder="CP de origen" autocomplete="off">
                </div>
            </div>
         </div>
          
         <hr>

         <div class="row">
            <div class="col-xs-6">
                <div class="form-group">
                    <label for="zona_destino">Zona de Destino</label>
                    <select disabled onchange="validarZona(event, 2)" name="zona_destino" id="zona_destino" class="form-control" style="width: 100%;">
                        <option value="0">Seleccione</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-6">
              <div class="form-group">
                <label for="localidad">Localidad de Destino</label>
                <select disabled id="localidad" name="localidad" class="form-control" style="width: 100%;">
                    <option value="0">Seleccione</option>
                </select>
              </div>
            </div>
          </div>
        </div>

        <div id="wrapper-tarifario" style="margin-bottom: 1.5rem;">
        </div>

        <div id="wrapper-paqueteria" style="margin-bottom: 1.5rem;">
        </div>

        <div class="row" id="piezas-all" style="display:none;">
            
            <div class="col-xs-12" id="groupDestinatario" style="display: none;">
                <div class="form-group">
                <label for="destinatario">Destinatario</label>
                <input  id="destinatario" name="destinatario" type="text" class="form-control" placeholder="Destinatario" autocomplete="off" />
                </div>
            </div>
            <div class="col-xs-6" id="groupDomicilio" style="display: none;">
                <div class="form-group">
                <label for="domicilio">Domicilio</label>
                <input  id="domicilio" name="domicilio" type="text" class="form-control" placeholder="Domicilio" autocomplete="off" />
                </div>
            </div>
            <div class="col-xs-6" id="groupCodigoPostal" style="display: none;">
                <div class="form-group">
                <label for="codigo_postal">CP Destino</label>
                <input  id="codigo_postal" name="codigo_postal" type="text" class="form-control" placeholder="CP" autocomplete="off" />
                </div>
            </div>

          <div class="col-xs-12" id="groupBarcodeExterno" style="display: none;">
              <div class="form-group">
                <label for="barcode_externo">Codigo Barras Externo</label>
                <input  id="barcode_externo" name="barcode_externo" type="text" class="form-control" placeholder="Codigo Barras Externo" autocomplete="off" />
              </div>
          </div>
          <div class="col-xs-12" id="groupDatosVarios1" style="display: none;">
            <div class="form-group">
              <label for="datos_varios">Datos Varios</label>
              <textarea id="datos_varios" name="datos_varios" class="form-control" placeholder="Datos Varios" rows="3"></textarea>
            </div>
          </div>
          <div class="col-xs-12" id="groupDatosVarios2" style="display: none;">
            <div class="form-group">
              <label for="datos_varios">Datos Varios 1</label>
              <textarea id="datos_varios_1" name="datos_varios_1" class="form-control" placeholder="Datos Varios 1" rows="3"></textarea>
            </div>
          </div>
          <div class="col-xs-12" id="groupDatosVarios3" style="display: none;">
            <div class="form-group">
              <label for="datos_varios">Datos Varios 2</label>
              <textarea id="datos_varios_2" name="datos_varios_2" class="form-control" placeholder="Datos Varios 2" rows="3"></textarea>
            </div>
          </div>
        </div>

        <hr>
        

        <div id="botones" class="row">
          <div class="col-xs-12">
            <div class="control-group text-right">
              <div class="controls">
                <a class="btn btn-primary agregar-grilla" onclick="validarCampos(event)" href="javascript:;"><i class="fa fa-arrow-down"></i> Agregar</a>
                <a class="btn btn-danger" href="<?=base_url().'piezas/comprobantes_ingresos'?>"><i class="fa fa-arrow-left"></i> Volver</a>
              </div>
            </div>
          </div>
        </div>

      </div>
      

      <hr>

      <table id="resultado" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
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
          <?php $class="text-center";
            foreach ($paquetes as $value):?>
                    <tr class="<?php echo $class;?>">
                      <td><?=$value->fecha_pedido?></td>
                      <td><?=$value->comprobante_ingreso?></td>
                      <td><?=$value->pieza_id?></td>
                      <td><?=$value->descripcion_paquete?></td>
                      <td><?=$value->peso?></td>
                      <td><?=$value->dimensiones?></td>
                      <td><?=$value->bultos?></td>
                      <td><?=$value->palets?></td>
                      <td><?=$value->metro_cubico?></td>
                      <td><?=$value->warehouse?></td>
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
                      <td><?=$value->cantidad_hora?></td>
                      <td><?=$value->monto_valor_declarado?></td>
                      <td><?=$value->monto_cobranza?></td>
                    </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?=form_close();?>
  </div>
</div>

<form method='post' action='<?php echo base_url('piezasN/piezas_paquetes/getPaqueteriaXCliente')?>' id='getListPaqueteriaXCliente'>
    <input type='hidden' id='list_cliente_id' name='list_cliente_id' value="">
    <input type='hidden' id='list_cliente' name='list_cliente' value="">
</form>   

<script type="text/javascript">
  let departamentos = <?= json_encode($departamentos); ?>;
  let cliente_id = <?= json_encode($cliente_id); ?>;
  let paquetes = <?= json_encode($paquetes); ?>;
  let tarifario = <?= json_encode($tarifario); ?>;

  const precioValorDeclaradoHtml = `
        <div class="col-xs-12 form-group">
            <label for="precioValorDeclarado">Valor Declarado</label>
            <input id="precioValorDeclarado" name="precioValorDeclarado" type="number" class="form-control" placeholder="Valor Declarado del Paquete"/>
        </div>
  `;

  const precioCobranzaHtml = `
        <div class="col-xs-12 form-group">
            <label for="precioCobranza">Cobranza</label>
            <input id="precioCobranza" name="precioCobranza" type="number" class="form-control" placeholder="Cobranza del Paquete"/>
        </div>
  `;


  /*
    CONFIGURACI??N PARA VALOR DECLARADO Y COBRANZA
  */
  let valorDeclarado = null;
  let cobranza = null;

  /*
    ELEMENTOS COMUNES DEL DOM
  */
  const groupDestinatario = document.getElementById('groupDestinatario')
  const groupDomicilio = document.getElementById('groupDomicilio')
  const groupCPDestino = document.getElementById('groupCodigoPostal')
  const groupBarcode = document.getElementById('groupBarcodeExterno')
  const groupDatosVarios1 = document.getElementById('groupDatosVarios1')
  const groupDatosVarios2 = document.getElementById('groupDatosVarios2')
  const groupDatosVarios3 = document.getElementById('groupDatosVarios3')

  let zonas = <?= json_encode($zonas);?>;
  
  $('#localidad').select2();
  $('#localidad_origen').select2();
  $('#zona_origen').select2();
  $('#zona_destino').select2();

  //cargarLocalidades();
  cargarZonas();

  var clientes = <?=json_encode($clientes)?>;
    $('#resultado').DataTable( {
        scrollX: true,
        "order": [[ 1, "desc" ]]
    } );
    
    $('#cliente').typeahead({
        source: clientes,
        onSelect: function(item) {
            $('#cliente_id').val(0);
            if (item.value)
            {
                $('#cliente_id').val(item.value);
                $('#list_cliente_id').val(item.value);
                $('#list_cliente').val(item.text);
                $('#getListPaqueteriaXCliente').submit();
            }
        }
    });
    
    //$('.agregar-grilla').click(function(event) {
    async function validarCampos(event){

        event.preventDefault()
        //Validaciones simples
        if ($("#cliente_id").val() == ""){
          alert("El paquete debe tener el CLIENTE");
          $("#cliente_id").focus();
          return;
        }

        if ($("#departamento_id").val() == "" || $("#departamento_id").val() == "0"){
          alert("El paquete debe tener el DEPARTAMENTO DEL CLIENTE");
          $("#departamento_id").focus();
          return;
        }

        if ($("#zona_origen").val() == "0"){
          alert("El paquete debe tener la ZONA DE ORIGEN");
          $("#zona_origen").focus();
          return;
        }

        if ($("#localidad_origen").val() == "0"){
          alert("El paquete debe tener la LOCALIDAD DE ORIGEN");
          $("#localidad_origen").focus();
          return;
        }

        if($("#codigo_postal_origen").val() == ""){
            alert("El paquete debe tener el CODIGO POSTAL DE ORIGEN");
            $("#codigo_postal_origen").focus();
            return;
        }

        if ($("#zona_destino").val() == "0"){
          alert("El paquete debe tener la ZONA DE DESTINO");
          $("#zona_destino").focus();
          return;
        }

        if ($("#localidad").val() == "0"){
          alert("El paquete debe tener la LOCALIDAD DE DESTINO");
          $("#localidad").focus();
          return;
        }

        if ($("#tarifario").val() == "0"){
          alert("Debe seleccionar el TARIFARIO");
          $("#tarifario").focus();
          return;
        }

        //PARA LOS CASOS PARTICULARES DE WAREHOUSE Y GESTION DE FLOTA
        if($("#tarifario").val() != "warehouse" && $("#tarifario").val() != "gestionFlota"){

            if($("#descripcion_paquete").val() == ""){
                alert("El paquete debe tener la DESCRIPCI??N");
                $("#descripcion_paquete").focus();
                return;
            }

            if ($("#destinatario").val() == ""){
                alert("El paquete debe tener el DESTINATARIO");
                $("#destinatario").focus();
                return;
            }

            if ($("#domicilio").val() == ""){
                alert("El paquete debe tener el DOMICILIO");
                $("#domicilio").focus();
                return;
            }

            if ($("#codigo_postal").val() == ""){
                alert("El paquete debe tener el CODIGO POSTAL");
                $("#codigo_postal").focus();
                return;
            }

            if ($("#barcode_externo").val() == ""){
                alert("El paquete debe tener el CODIGO DE BARRAS");
                $("#barcode_externo").focus();
                return;
            }

        }

        const tarifario = document.getElementById("tarifario").value
        const cliente = document.getElementById('cliente_id')
        const localidad = document.getElementById('localidad')
        const zonaA = document.getElementById('zona_origen')
        const zonaB = document.getElementById('zona_destino')

        let data = null
        let precio = null

        switch (tarifario) {
            case "kilos":
                const peso = document.getElementById("peso")
                if(peso.value == ""){
                    alert("El paquete debe tener el PESO")
                    peso.focus()
                    return;
                }

                const dimensionesP = document.getElementById("dimensiones")

                if(dimensionesP.value == ""){
                    alert("El paquete debe tener las DIMENSIONES")
                    dimensionesP.focus()
                    return;
                }

                data = await validarKilosEnEsquema(cliente.value, zonaA.value, zonaB.value, peso.value)
                console.log("resultado kilos:", data.data)

                if(data.data.cantidad == 0){
                    alert("El PESO del paquete excede los valores registrados en el esquema tarifario para este cliente.")
                    peso.value = ""
                    peso.focus()
                    return;
                }

                break;

            case "bultos":
                const bulto = document.getElementById("bulto")

                if(bulto.value == ""){
                    alert("El paquete debe tener el BULTO")
                    bulto.focus()
                    return;
                }

                data = await validarBultosEnEsquema(cliente.value, zonaA.value, zonaB.value, bulto.value)
                console.log("resultado bultos:", data.data)

                if(data.data.cantidad == 0){
                    alert("El BULTO del paquete excede los valores registrados en el esquema tarifario para este cliente.")
                    bulto.value = ""
                    bulto.focus()
                    return;
                }

                break;

            case "palets":
                const palets = document.getElementById("palets")
                if(palets.value == ""){
                    alert("El paquete debe tener el PALET")
                    palets.focus()
                    return;
                }

                data = await validarPaletsEnEsquema(cliente.value, zonaA.value, zonaB.value, palets.value)
                console.log("resultado palets:", data.data)

                if(data.data.cantidad == 0){
                    alert("El PALET del paquete excede los valores registrados en el esquema tarifario para este cliente.")
                    palets.value = ""
                    palets.focus()
                    return;
                }

                break;

            case "metrosCubicos":   
                const dimensiones = document.getElementById("dimensiones")
                const pesoV = document.getElementById("peso")
                
                if(pesoV.value == ""){
                    alert("El paquete debe tener el PESO")
                    pesoV.focus()
                    return;
                }

                if(dimensiones.value == ""){
                    alert("El paquete debe tener las DIMENSIONES")
                    dimensiones.focus()
                    return;
                }

                let volumen = dimensiones.value.split(/[xX]/)
                let metroCubico = 1;

                for (let index = 0; index < volumen.length; index++) {
                    const element = volumen[index];
                    console.log("elemento: ", element)
                    metroCubico = metroCubico * element
                }

                metroCubico = metroCubico / 1000000

                data = await validarMetrosCubicosEnEsquema(cliente.value, zonaA.value, zonaB.value, metroCubico)
                console.log("resultado metros cubicos:", data.data)

                if(data.data.cantidad == 0){
                    alert("Las DIMENSIONES del paquete (VOLUMEN) excede los valores registrados en el esquema tarifario para este cliente.")
                    dimensiones.value = ""
                    dimensiones.focus()
                    return;
                }

                break;

            case "warehouse":
                const warehouse = document.getElementById("warehouse")
                if(warehouse.value == ""){
                    alert("Se debe ingresar el Warehouse")
                    warehouse.focus()
                    return;
                }

                data = await validarWarehouseEnEsquema(cliente.value, zonaA.value, zonaB.value, warehouse.value)
                console.log("resultado warehouse:", data.data)

                if(data.data.cantidad == 0){
                    alert("El Warehouse excede los valores registrados en el esquema tarifario para este cliente.")
                    warehouse.value = ""
                    warehouse.focus()
                    return;
                }

                break;
        
            case "gestionFlota":
                const gestionFlota = document.getElementById("gestionFlota")
                if(gestionFlota.value == "" || gestionFlota.value == "0"){
                    alert("Se debe seleccionar la Flota")
                    gestionFlota.focus()
                    return;
                }

                const cantidadHora = document.getElementById('cantidad_horas')
                if(cantidadHora.value == "" || cantidadHora.value == "0"){
                    alert("Se debe seleccionar la cantidad de horas")
                    cantidadHora.focus()
                    return;
                }

                data = await validarGestionFlotaEnEsquema(cliente.value, zonaA.value, zonaB.value, gestionFlota.value)
                console.log("resultado Gestion de Flota:", data.data)

                if(data.data.cantidad == 0){
                    alert("La Flota seleccionada no se encuentra registrada en el esquema tarifario para este cliente.")
                    gestionFlota.value = "0"
                    gestionFlota.focus()
                    return;
                }

                break;
        

            default:
                break;
        }

        if(document.getElementById('precioValorDeclarado')){
            precio = document.getElementById('precioValorDeclarado').value
            if(valorDeclarado){
                if(precio == ""){
                    alert("El paquete debe tener el VALOR DECLARADO")
                    document.getElementById('precioValorDeclarado').focus()
                    return;
                }

                if(precio <= "0"){
                    alert("El VALOR DECLARADO del paquete debe ser mayo a cero")
                    document.getElementById('precioValorDeclarado').focus()
                    return;
                }
            }
        }

        if(document.getElementById('precioCobranza')){
            precio = document.getElementById('precioCobranza').value
            if(cobranza){
                if(precio == ""){
                    alert("El paquete debe tener la COBRANZA")
                    document.getElementById('precioCobranza').focus()
                    return;
                }

                if(precio <= "0"){
                    alert("La COBRANZA del paquete debe ser mayo a cero")
                    document.getElementById('precioCobranza').focus()
                    return;
                }
            }
        }

        console.log("Pasando la validaci??n simple")
        
        const resp = await enviar()
        if(resp.status == 201){
            window.location.reload()
        }
    }

    async function getMetodosTarifario(){
        const cliente_id = document.getElementById('cliente_id').value
        const origen = document.getElementById('zona_origen').value
        const destino = document.getElementById('zona_destino').value

        if(!cliente_id){
            console.log("No existe el id del cliente aun...")
            return;
        }

        const data = await getMetodosTarifariosXCliente(cliente_id, origen, destino)

        if(data['data'].tarifario){
            const resultado = formatearResultado(data)
            construirSelectTarifario(resultado)
        }else{
            alert("El cliente no tiene un tarifario con las zonas origen y destino definidas")
            $('#zona_destino').select2("val", "0");
            $('#localidad').prop('disabled', true);
            $('#localidad').select2("val", "0");
        }



        //console.log("Metodos disponibles para el cliente: ",resultado)
    }

    async function getMetodosTarifariosXCliente(cliente, origen, destino){
        const dominio = document.domain;
        const url = `//${dominio}/piezasN/Piezas_paquetes/getTarifarioXCliente`;
        
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({cliente, origen, destino})
            });

            const data = await response.json();

            if(data){
                return data
            }

            return false

        } catch (error) {
                console.error("getMetodosTarifariosXCliente: ", error)
        }

    }

    function formatearResultado(data){
        let tarifarios = {}

        if(data['data'].kilos == "TRUE"){
            tarifarios.kilos = true;
        }

        if(data['data'].bultos == "TRUE"){
            tarifarios.bultos = true;
        }

        if(data['data'].palets == "TRUE"){
            tarifarios.palets = true;
        }

        if(data['data'].warehouse == "TRUE"){
            tarifarios.warehouse = true;
        }

        if(data['data'].metrosCubicos == "TRUE"){
            tarifarios.metrosCubicos = true;
        }

        if(data['data'].gestionFlota == "TRUE"){
            tarifarios.gestionFlota = true;
        }

        if(data['data'].valorDeclarado == "TRUE"){
            //tarifarios.valorDeclarado = true;
            console.log("Tiene valor declarado configurado")
            valorDeclarado = true;
        }

        if(data['data'].cobranza == "TRUE"){
            //tarifarios.cobranza = true;
            console.log("Tiene cobranza configurado")
            cobranza = true;
        }

        if(data['data'].pesoAforado == "TRUE"){
            //tarifarios.pesoAforado = true;
            console.log("Tiene peso aforado configurado")
        }

        if(data['data'].distancia == "TRUE"){
            tarifarios.distancia = true;
        }
        
        return tarifarios;

    }

    function construirSelectTarifario(data){
        const contenedor = document.getElementById("wrapper-tarifario")
        contenedor.innerHTML = ""

        let html = `
        <hr>
        <div class="row">
        <div class="col-xs-12">
            <label for="tarifario">Opciones para tarifar al cliente</label>
            <select id="tarifario" class="form-control" onchange="crearCamposDinamicos(event)">
                <option value="0">Seleccione</option>
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
        document.getElementById("piezas-all").style.display = "block"
    }

    async function crearCamposDinamicos(event){
        const valor = document.getElementById("tarifario").value
        const contenedor = document.getElementById("wrapper-paqueteria")
        let html = "";

        if(valor == 0){
            return;
        }

        ocultarPanel()

        groupDestinatario.style.display = "none"
        groupDomicilio.style.display = "none"
        groupCPDestino.style.display = "none"
        groupBarcode.style.display = "none"
        groupDatosVarios1.style.display = "none"
        groupDatosVarios2.style.display = "none"
        groupDatosVarios3.style.display = "none"

        contenedor.innerHTML = ""

        switch (valor) {
            case 'kilos':
                html += crearVistaKilos(html);
                break;

            case 'bultos':
                html += crearVistaBultos(html)
                break;

            case 'palets':
                html += crearVistaPalets(html)
                break;

            case 'metrosCubicos':
                html += crearVistaMetrosCubicos(html)
                break;

            case 'warehouse':
                html += crearVistaWareHouse(html)
                break;

            case 'gestionFlota':
                html += await crearVistaGestionFlota(html)
                break;

            case 'distancia':
                html += await crearVistaDistancia(html)
                break;
            
            /*
            case 'valorDeclarado':
                //html += `<option value="${key}">Valor Declarado</option>`
                console.log("Crear vista valor declarado....")
                break;

            case 'cobranza':
                //html += `<option value="${key}">Cobranza</option>`
                console.log("Crear vista cobranza....")
                break;

            case 'pesoAforado':
                //html += `<option value="${key}">Aforo</option>`
                console.log("Crear vista peso aforado....")
                break;
            */

            default:
                break;
        }

        mostrarPanel()

        contenedor.insertAdjacentHTML('beforeend', html)
    }

    function crearVistaKilos(html){
        html += `
        <div class="row">

            <div  class="row">
                <div class="col-xs-12" style="margin-left: 15px; margin-top: 15px">
                     <label for="descripcion_paquete">Descripcion del paquete</label>
                     <input id="descripcion_paquete" name="descripcion_paquete" type="text" class="form-control" placeholder="Ej. Zapatillas run-runner" value=""/>
                 </div>
            </div>

            <br>

            ${ valorDeclarado ? precioValorDeclaradoHtml : '' }
            ${ cobranza ? precioCobranzaHtml : '' }

            <div class="col-xs-6">
                 <label for="peso">Peso (Kg)</label>
                 <input id="peso" name="peso" type="number" class="form-control" placeholder="Peso"/>
             </div>

             <div class="col-xs-6">
                 <label for="dimensiones">Dimensiones(AltoXLargoXProfund. en cm.) </label>
                 <input onkeydown="formatoMedidas(event)" id="dimensiones" name="dimensiones" type="text" class="form-control" placeholder="50x15x20" value="" />
            </div>
        
        </div> 
            `       

        groupDestinatario.style.display = "block"
        groupDomicilio.style.display = "block"
        groupCPDestino.style.display = "block"
        groupBarcode.style.display = "block"
        groupDatosVarios1.style.display = "block"
        groupDatosVarios2.style.display = "block"
        groupDatosVarios3.style.display = "block"
                
        return html
    }

    function crearVistaDistancia(html){
        html += `
        <div class="row">
            <div  class="row">
                <div class="col-xs-12" style="margin-left: 15px; margin-top: 15px">
                     <label for="descripcion_paquete">Descripcion del paquete</label>
                     <input id="descripcion_paquete" name="descripcion_paquete" type="text" class="form-control" placeholder="Ej. Zapatillas run-runner" value=""/>
                 </div>
            </div>
            <br>

            ${ valorDeclarado ? precioValorDeclaradoHtml : '' }
            ${ cobranza ? precioCobranzaHtml : '' }
        
        </div> 
        `      
        
        groupDestinatario.style.display = "block"
        groupDomicilio.style.display = "block"
        groupCPDestino.style.display = "block"
        groupBarcode.style.display = "block"
        groupDatosVarios1.style.display = "block"
        groupDatosVarios2.style.display = "block"
        groupDatosVarios3.style.display = "block"
                
        return html
    }

    function crearVistaBultos(html){
        html += `
        <div class="row">
            <div  class="row">
                <div class="col-xs-12" style="margin-left: 15px; margin-top: 15px">
                     <label for="descripcion_paquete">Descripcion del paquete</label>
                     <input id="descripcion_paquete" name="descripcion_paquete" type="text" class="form-control" placeholder="Ej. Zapatillas run-runner" value=""/>
                 </div>
            </div>
            <br>
            
            ${ valorDeclarado ? precioValorDeclaradoHtml : '' }
            ${ cobranza ? precioCobranzaHtml : '' }

            <div class="col-xs-12 form-group">
                <label for="bulto">Bulto (unidades)</label>
                <input id="bulto" name="bulto" type="number" onkeydown="noPuntoComa(event)" class="form-control" placeholder="Bulto"/>
            </div>

        
        </div> 
        `

        groupDestinatario.style.display = "block"
        groupDomicilio.style.display = "block"
        groupCPDestino.style.display = "block"
        groupBarcode.style.display = "block"
        groupDatosVarios1.style.display = "block"
        groupDatosVarios2.style.display = "block"
        groupDatosVarios3.style.display = "block"
        
        return html
    }

    function crearVistaPalets(html){
        html += `
        <div class="row">
            <div  class="row">
                <div class="col-xs-12" style="margin-left: 15px; margin-top: 15px">
                     <label for="descripcion_paquete">Descripcion del paquete</label>
                     <input id="descripcion_paquete" name="descripcion_paquete" type="text" class="form-control" placeholder="Ej. Zapatillas run-runner" value=""/>
                 </div>
            </div>
            <br>

            ${ valorDeclarado ? precioValorDeclaradoHtml : '' }
            ${ cobranza ? precioCobranzaHtml : '' }

            <div class="col-xs-12 form-group">
                <label for="palets">Palets (unidades)</label>
                <input id="palets" name="palets" type="number" onkeydown="noPuntoComa(event)" class="form-control" placeholder="Palets"/>
            </div>
        
        </div> 
        `

        groupDestinatario.style.display = "block"
        groupDomicilio.style.display = "block"
        groupCPDestino.style.display = "block"
        groupBarcode.style.display = "block"
        groupDatosVarios1.style.display = "block"
        groupDatosVarios2.style.display = "block"
        groupDatosVarios3.style.display = "block"
        
        return html
    }

    function crearVistaMetrosCubicos(html){
        html += `
        <div class="row">
            <div  class="row">
                <div class="col-xs-6" style="margin-left: 15px; margin-top: 15px">
                     <label for="descripcion_paquete">Descripcion del paquete</label>
                     <input id="descripcion_paquete" name="descripcion_paquete" type="text" class="form-control" placeholder="Ej. Zapatillas run-runner" value=""/>
                 </div>
            </div>
            <br>

            ${ valorDeclarado ? precioValorDeclaradoHtml : '' }
            ${ cobranza ? precioCobranzaHtml : '' }

            <div class="col-xs-6">
                 <label for="dimensiones">Dimensiones(AltoXLargoXProfund. en cm.) </label>
                 <input onkeydown="formatoMedidas(event)" id="dimensiones" name="dimensiones" type="text" class="form-control" placeholder="50x15x20" value="" />
            </div>

            <div class="col-xs-6">
                 <label for="peso">Peso (Kg)</label>
                 <input id="peso" name="peso" type="number" class="form-control" placeholder="Peso"/>
            </div>
        
        </div> 
        `

        groupDestinatario.style.display = "block"
        groupDomicilio.style.display = "block"
        groupCPDestino.style.display = "block"
        groupBarcode.style.display = "block"
        groupDatosVarios1.style.display = "block"
        groupDatosVarios2.style.display = "block"
        groupDatosVarios3.style.display = "block"
        
        return html
    }

    function crearVistaWareHouse(html){
        html += `
        <div class="row">
            <div  class="row">
                <div class="col-xs-12" style="margin-left: 15px; margin-top: 15px">
                     <label for="descripcion_paquete">Descripcion del paquete</label>
                     <input id="descripcion_paquete" name="descripcion_paquete" type="text" class="form-control" placeholder="Ej. Zapatillas run-runner" value=""/>
                 </div>
            </div>

            <br>

            <div class="col-xs-12">
                 <label for="warehouse">Warehouse (cantidad)</label>
                 <input id="warehouse" name="warehouse" type="text" class="form-control" placeholder="Warehouse"/>
            </div>
        
        </div> 
        `

        groupDatosVarios1.style.display = 'block'
        groupDatosVarios2.style.display = 'block'
        groupDatosVarios3.style.display = 'block'
        
        return html
    }

    async function crearVistaGestionFlota(html){

        const cliente_id = document.getElementById('cliente_id').value
        const origen = document.getElementById('zona_origen').value
        const destino = document.getElementById('zona_destino').value

        const data = await getGestionFlotaXCliente(cliente_id, origen, destino)

        console.log("data: ", data)

        html += `
        <div class="row">

            <div class="col-xs-6 form-group">
                <label style="margin-top: 15px" for="gestionFlota">Gesti&#243;n de Flota</label>
                <select id="gestionFlota" name="gestionFlota" class="form-control">
                    <option value="0">Seleccione</option>
                `
                
                for (let index = 0; index < data.data.length; index++) {
                    const element = data.data[index];
                    html += `${element.tipoVehiculo == 1 && element.horaVehiculo == 1 ? '<option value="1">Moto // Hora comun</option>' : ''}`
                    html += `${element.tipoVehiculo == 1 && element.horaVehiculo == 2 ? '<option value="2">Moto // Hora especial</option>' : ''}`
                    html += `${element.tipoVehiculo == 2 && element.horaVehiculo == 1 ? '<option value="3">Auto // Hora comun</option>' : ''}`
                    html += `${element.tipoVehiculo == 2 && element.horaVehiculo == 2 ? '<option value="4">Auto // Hora especial</option>' : ''}`
                    html += `${element.tipoVehiculo == 3 && element.horaVehiculo == 1 ? '<option value="5">Camioneta // Hora especial</option>' : ''}`
                    html += `${element.tipoVehiculo == 3 && element.horaVehiculo == 2 ? '<option value="6">Camioneta // Hora comun</option>' : ''}`
                    html += `${element.tipoVehiculo == 4 && element.horaVehiculo == 1 ? '<option value="7">Furgon // Hora comun</option>' : ''}`
                    html += `${element.tipoVehiculo == 4 && element.horaVehiculo == 2 ? '<option value="8">Furgon // Hora especial</option>' : ''}`
                    html += `${element.tipoVehiculo == 5 && element.horaVehiculo == 1 ? '<option value="9">Cami&#243;n // Hora comun</option>' : ''}`
                    html += `${element.tipoVehiculo == 5 && element.horaVehiculo == 2 ? '<option value="10">Cami&#243;n // Hora especial</option>' : ''}`
                }
                
        html += `    
                </select>
            </div>

            <div class="col-xs-6 form-group">
                <label style="margin-top: 15px" for="cantidad_horas">Cantidad de Horas</label>
                <select class="form-control" name="cantidad_horas" id="cantidad_horas">
                    <option value="0">Seleccione una opci&oacute;n</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                </select>
            </div>

            ${ valorDeclarado ? precioValorDeclaradoHtml : '' }
            ${ cobranza ? precioCobranzaHtml : '' }
        `

        groupDatosVarios1.style.display = 'block'
        groupDatosVarios2.style.display = 'block'
        groupDatosVarios3.style.display = 'block'

        return html
    }

    async function getGestionFlotaXCliente(cliente, origen, destino){
        const dominio = document.domain;
        const url = `//${dominio}/piezasN/Piezas_paquetes/getGestionFlotaXCliente`;
        
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({cliente, origen, destino})
            });

            const data = await response.json();

            if(data){
                return data
            }

            return false

        } catch (error) {
                console.error("getGestionFlotaXCliente: ", error)
        }
    }

    async function cargarLocalidades(zona, select){
       
        const data = await getLocalidades(zona)

        if(data){
            let html = ""
            for (let index = 0; index < data.data.length; index++) {
                const element = data.data[index];
                html += `<option value="${element.id}">${element.nombre}</option>`
            }
            document.getElementById(`${select}`).innerHTML = "<option value='0'>Seleccione</option>"
            document.getElementById(`${select}`).insertAdjacentHTML('beforeend', html)
        }
        
    }

    async function getLocalidades(zona){
        const dominio = document.domain;
        const url = `//${dominio}/piezasN/Piezas_paquetes/getLocalidadesPorZona`;
    
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({zona})
            });

            const data = await response.json();

            if(data){
                return data
            }

            return false

        } catch (error) {
                console.error("getLocalidades: ", error)
        }

    }

    function cargarZonas(){
        if(zonas){
            let html = ""
            for (let index = 0; index < zonas.length; index++) {
                const element = zonas[index];
                html += `<option value="${element.id}">${element.nombre}</option>`
            }
    
            document.getElementById("zona_origen").insertAdjacentHTML('beforeend', html)
            document.getElementById("zona_destino").insertAdjacentHTML('beforeend', html)
        }
    }

    function mostrarPanel(){
        document.getElementById("piezas-paquetes").style.display = "block"
        document.getElementById("piezas-all").style.display = "block"
        document.getElementById("botones").style.display = "block"
    }

    function ocultarPanel(){
        document.getElementById("piezas-paquetes").style.display = "none"
        document.getElementById("piezas-all").style.display = "none"
        document.getElementById("botones").style.display = "none"
    }

    async function validarLocalidad(event, opcion){
        event.preventDefault();
        //OPCION 
        // 1 = ORIGEN
        // 2 = DESTINO

        const cliente = document.getElementById('cliente_id')
        const localidad = event.target.value

        if(!cliente.value){
            alert("No selecciono el cliente")
            return;
        }

        if(localidad == "0"){
            return;
        }

        const data = await comprobarQueLaLocalidadSeEncuentreDentroDeLaZonaDelEsquema(cliente.value, localidad, opcion)

        console.log("Respuesta del servidor: ", data)
        
        document.getElementById("wrapper-tarifario").innerHTML = ""
        document.getElementById("wrapper-paqueteria").innerHTML = ""
        document.getElementById("piezas-all").style.display = "none"

        if(data['data'].resultado == "FALSE"){
            alert("La localidad elegida no se encuentra dentro del esquema tarifario del cliente")

            if(opcion == 1){
                $(`#${event.target.id}`).select2("val", "0");
                $(`#${event.target.id}`).focus()
                $('#localidad').prop('disabled', true);
                $('#localidad').select2("val", "0");
                return;
            }

            if(opcion == 2){
                $(`#${event.target.id}`).select2("val", "0");
                $(`#${event.target.id}`).focus()
                return;
            }

        }else{
            console.log("Paso validaci??n de localidad....")

            if(opcion == 1){
                $('#localidad').prop('disabled', false);
            }

            if(opcion == 2){
                getMetodosTarifario()
            }

        }


    }

    async function validarZona(event, opcion){
        event.preventDefault();
        //OPCION 
        // 1 = ORIGEN
        // 2 = DESTINO

        valorDeclarado = null;
        cobranza = null;

        const cliente = document.getElementById('cliente_id')
        const zona = event.target.value

        if(!cliente.value){
            alert("No selecciono el cliente")
            return;
        }

        if(zona == "0"){
            return;
        }

        const data = await comprobarQueLaZonaSeEncuentreDentroDelEsquema(cliente.value, zona, opcion)
        
        document.getElementById("wrapper-tarifario").innerHTML = ""
        document.getElementById("wrapper-paqueteria").innerHTML = ""
        document.getElementById("piezas-all").style.display = "none"

        if(data['data'].resultado == "FALSE"){
            alert("La zona elegida no se encuentra dentro del esquema tarifario del cliente")

            if(opcion == 1){
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

            if(opcion == 2){
                $(`#${event.target.id}`).select2("val", "0");
                $(`#${event.target.id}`).focus()
                $('#localidad').prop('disabled', true);
                $('#localidad').select2("val", "0");
                return;
            }


        }else{

            //PASO LA VALIDACION DE LA ZONA
            if(opcion == 1){
                $('#zona_destino').prop('disabled', false);
                $('#zona_destino').select2("val", "0");
                $('#localidad_origen').prop('disabled', false);
                cargarLocalidades(zona, 'localidad_origen')
            }
            
            if(opcion == 2){
                cargarLocalidades(zona, 'localidad')
                $('#localidad').prop('disabled', false);
                getMetodosTarifario()
            }


        }


    }

    async function comprobarQueLaLocalidadSeEncuentreDentroDeLaZonaDelEsquema(cliente, localidad, opcion){
        const dominio = document.domain;
        const url = `//${dominio}/piezasN/Piezas_paquetes/comprobarLocalidaDentroDeZonaEsquema`;
        
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({cliente, localidad, opcion})
            });

            const data = await response.json();

            if(data){
                return data
            }

            return false

        } catch (error) {
                console.error("comprobarQueLaLocalidadSeEncuentreDentroDeLaZonaDelEsquema: ", error)
        }

    }

    async function comprobarQueLaZonaSeEncuentreDentroDelEsquema(cliente, zona, opcion){
        const dominio = document.domain;
        const url = `//${dominio}/piezasN/Piezas_paquetes/comprobarZonaDentroDelEsquema`;
        
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({cliente, zona, opcion})
            });

            const data = await response.json();

            if(data){
                return data
            }

            return false

        } catch (error) {
                console.error("comprobarQueLaZonaSeEncuentreDentroDelEsquema: ", error)
        }

    }

    function noPuntoComa(event) {
        let e = event || window.event;
        let key = e.keyCode || e.which;

        if ( key === 110 || key === 190 || key === 188 ) {     
            e.preventDefault();     
        }
    }

    async function validarKilosEnEsquema(cliente, zonaA, zonaB, peso){
        const dominio = document.domain;
        const url = `//${dominio}/piezasN/Piezas_paquetes/comprobarKiloEnReglaEsquema`;
        
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({cliente, zonaA, zonaB, peso})
            });

            const data = await response.json();

            if(data){
                return data
            }

            return false

        } catch (error) {
                console.error("validarKilosEnEsquema: ", error)
        }
    }

    async function validarBultosEnEsquema(cliente,  zonaA, zonaB, bulto){
        const dominio = document.domain;
        const url = `//${dominio}/piezasN/Piezas_paquetes/comprobarBultoEnReglaEsquema`;
        
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({cliente,  zonaA, zonaB, bulto})
            });

            const data = await response.json();

            if(data){
                return data
            }

            return false

        } catch (error) {
                console.error("validarBultosEnEsquema: ", error)
        }
    }

    async function validarPaletsEnEsquema(cliente,  zonaA, zonaB, palet){
        const dominio = document.domain;
        const url = `//${dominio}/piezasN/Piezas_paquetes/comprobarPaletEnReglaEsquema`;
        
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({cliente,  zonaA, zonaB, palet})
            });

            const data = await response.json();

            if(data){
                return data
            }

            return false

        } catch (error) {
                console.error("validarPaletsEnEsquema: ", error)
        }
    }

    async function validarMetrosCubicosEnEsquema(cliente,  zonaA, zonaB, metroCubico){
        const dominio = document.domain;
        const url = `//${dominio}/piezasN/Piezas_paquetes/comprobarMetroCubicoEnReglaEsquema`;
        
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({cliente,  zonaA, zonaB, metroCubico})
            });

            const data = await response.json();

            if(data){
                return data
            }

            return false

        } catch (error) {
                console.error("validarMetrosCubicosEnEsquema: ", error)
        }
    }

    async function validarWarehouseEnEsquema(cliente, zonaA, zonaB, warehouse){
        const dominio = document.domain;
        const url = `//${dominio}/piezasN/Piezas_paquetes/comprobarWarehouseEnReglaEsquema`;
        
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({cliente, zonaA, zonaB, warehouse})
            });

            const data = await response.json();

            if(data){
                return data
            }

            return false

        } catch (error) {
                console.error("validarWarehouseEnEsquema: ", error)
        }
    }

    async function validarGestionFlotaEnEsquema(cliente, zonaA, zonaB, gestionFlota){
        const dominio = document.domain;
        const url = `//${dominio}/piezasN/Piezas_paquetes/comprobarGestionFlotaEnReglaEsquema`;
        
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({cliente, zonaA, zonaB, gestionFlota})
            });

            const data = await response.json();

            if(data){
                return data
            }

            return false

        } catch (error) {
            console.error("validarGestionFlotaEnEsquema: ", error)
        }
    }
   
    async function enviar(){

        console.log("enviando datos...")

        const dominio = document.domain;
        const url = `//${dominio}/piezasN/Piezas_paquetes/add`;

        $('select[name="localidad_destino"] option:selected').text()

        const datos = {
            cliente_id: document.getElementById('cliente_id').value,
            departamento_id: document.getElementById("departamento_id").value,
            //localidad_destino: document.getElementById('localidad').value,
            //localidad_origen: document.getElementById('localidad_origen').value,
            localidad_destino: $('select[name="localidad"] option:selected').text(),
            localidad_origen: $('select[name="localidad_origen"] option:selected').text(),
            descripcion_paquete: $("#descripcion_paquete").val(),
            destinatario: $("#destinatario").val(),
            domicilio: $("#domicilio").val(),
            codigo_postal_destino: $("#codigo_postal").val(),
            codigo_postal_origen: $("#codigo_postal_origen").val(),
            barcode_externo: $("#barcode_externo").val(),
            datos_varios: $("#datos_varios").val(),
            datos_varios_1: $("#datos_varios_1").val(),
            datos_varios_2: $("#datos_varios_2").val(),
            zonaA:  $("#zona_origen").val(),
            zonaB:  $("#zona_destino").val(),
        }

        const tarifario = document.getElementById("tarifario").value

        switch (tarifario) {
            case "kilos":
                const peso = document.getElementById("peso")
                datos.peso = peso.value

                const dimensionesP = document.getElementById("dimensiones")
                datos.dimensiones = dimensionesP.value

                break;

            case "bultos":
                const bulto = document.getElementById("bulto")
                datos.bulto = bulto.value
                break;

            case "palets":
                const palets = document.getElementById("palets")
                datos.palet = palets.value
                break;

            case "metrosCubicos":   
                const dimensiones = document.getElementById("dimensiones")
                const pesoV = document.getElementById("peso")

                let volumen = dimensiones.value.split(/[xX]/)
                let metroCubico = 1;

                for (let index = 0; index < volumen.length; index++) {
                    const element = volumen[index];
                    console.log("elemento: ", element)
                    metroCubico = metroCubico * element
                }

                metroCubico = metroCubico / 1000000

                datos.peso = pesoV.value
                datos.metroCubico = metroCubico
                datos.dimensiones = dimensiones.value

                break;
        
            case "warehouse":
                const warehouse = document.getElementById('warehouse')
                datos.warehouse = warehouse.value
                break;

            case "gestionFlota":
                const gestionFlota = document.getElementById('gestionFlota')
                const cantidadHora = document.getElementById('cantidad_horas')

                datos.gestionFlota = gestionFlota.value
                datos.cantidadHora = cantidadHora.value
            break;

            default:
                break;
        }

        if(valorDeclarado){
            datos.precioValorDeclarado = document.getElementById('precioValorDeclarado').value
        }

        if(cobranza){
            datos.precioCobranza = document.getElementById('precioCobranza').value
        }

        console.log(datos)

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(datos)
            });

            const data = await response.json();

            if(data){
                return data
            }

            return false

        } catch (error) {
                console.error("enviar: ", error)
        }
        
    }

    function formatoMedidas(event) {
        let e = event || window.event;
        let key = e.keyCode || e.which;

        if ( 
             key == 8 || // para borrar 
             key == 48 || key == 96 || // 1 
             key == 49 || key == 97 || // 2
             key == 50 || key == 98 || // 3
             key == 51 || key == 99 || // 4
             key == 52 || key == 100 || // 5
             key == 53 || key == 101 || // 6
             key == 54 || key == 102 || // 7
             key == 55 || key == 103 || // 8 
             key == 56 || key == 104 || // 9
             key == 57 || key == 105 || // 10
             key == 88 // xX
        ) {
        }else{
            e.preventDefault();     
        }
    }
    
</script>