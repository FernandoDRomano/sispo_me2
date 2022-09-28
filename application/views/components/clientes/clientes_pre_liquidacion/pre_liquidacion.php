<style>
    #grilla {
        margin: 0 0 1rem;
        overflow: auto;
        overflow-y: auto;
        width: 100%;
    }

    .tituloFormulario {
        color: #697882;
        font-size: 22px;
        font-weight: 400;
        border-bottom: 2px solid #ADCE9D!important;
        padding-bottom: 7px;    
        margin-bottom: 2rem;
        margin-top: 1.6rem!important;
    }
</style>

<div class="col-xs-12">
    <div class="ibox-content">
        <form action="#" method="POST" id="form_pre_liquidacion">

            <input type='hidden' id='cantidad_elementos' name='cantidad_elementos' value="">
            <input type='hidden' id='estado_id' name='estado_id' value="">
            <input type='hidden' id='iva' name='iva' value="">
            <div class="row">
                <!--
            <div class="col-xs-6">
                <div class="form-group">
                    <label for="cliente_id">Cliente</label>
                    <input id="cliente" type="text" class="form-control filtro-group" placeholder="Cliente" autocomplete="off" />
                    <input id="cliente_id" name="cliente_id" type="hidden" value="" />
                </div>
            </div>
            -->

                <div class="col-xs-12">
                    <h3 class="tituloFormulario">Filtros</h3>
                </div>

                <input id="cliente_id" name="cliente_id" type="hidden" value="<?php echo $cliente->id; ?>" />

                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="periodo_desde">Periodo desde</label>
                        <input id="periodo_desde" name="periodo_desde" type="text" class="form-control datepicker" placeholder="Seleccione una fecha" value="<?php if ($periodo_desde) {
                                                                                                                                                                    $f = strtotime($periodo_desde);
                                                                                                                                                                    echo date('d-m-Y', $f);
                                                                                                                                                                } ?>" required autocomplete="off" />
                    </div>
                </div>

                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="periodo_hasta">Periodo hasta</label>
                        <input id="periodo_hasta" name="periodo_hasta" type="text" class="form-control datepicker" placeholder="Seleccione una fecha" value="<?php if ($periodo_hasta) {
                                                                                                                                                                    $f = strtotime($periodo_hasta);
                                                                                                                                                                    echo date('d-m-Y', $f);
                                                                                                                                                                } ?>" required autocomplete="off" />
                    </div>
                </div>

                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="dpto">Departamento</label>
                        <select id="departamento_id" name="departamento_id" class="form-control filtro-group" <?php echo count($departamentos) == 0 ? 'disabled' : ''; ?>>
                            <option value="">Seleccione</option>
                            <?php foreach ($departamentos as $departamento) : ?>
                                <option value="<?= $departamento->id ?>"><?= $departamento->nombre ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="sucursal">Sucursal</label>
                        <select id="sucursal_id" name="sucursal_id" class="form-control filtro-group">
                            <option value="">Seleccione</option>
                            <?php foreach ($sucursales as $sucursal) : ?>
                                <option value="<?= $sucursal->id ?>" <?= $sucursal_id == $sucursal->id ? 'selected="selected"' : '' ?>><?= $sucursal->nombre ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!--
        <div class="row">
            <div class="col-xs-3">
                <div class="form-group">
                    <label for="factura">Factura</label>
                    <input id="factura" name="factura" type="text" class="form-control" placeholder="Factura" />
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                    <label for="paqueteria">Paqueteria</label>
                    <select id="paqueteria_id" name="paqueteria_id" class="form-control filtro-group">
                        <option value="">Seleccione</option>
                        
                            <?php foreach ($paqueterias as $paqueteria) : ?>
                                <option value="<?= $paqueteria->id ?>"><?= $paqueteria->nombre ?></option>
                            <?php endforeach; ?>
                            
                    </select>
                </div>
            </div>
        </div>
        -->


            </div>
            <!--
        <div class="row">
            <div class="col-xs-10">
                <div class="form-group">
                    <label for="obeservaciones">Obeservaciones</label>
                    <input id="obeservaciones" name="obeservaciones" type="text" class="form-control" placeholder="Obeservaciones" />
                </div>
            </div>
        </div>
        -->

            <div class="control-group">
                <div class="controls">
                    <a class="btn btn-success factura_a" href="javascript:;"><i class="fa fa-eye"></i> Vista Previa</a>
                    <!-- <a class="btn btn-primary exportar_excel" href="javascript:;"><i class="fa fa-print"></i> Exportar Excel</a> -->
                </div>
            </div>
            <div>&nbsp;</div>
            <div id="error"></div>
            <div id="grilla"></div>
            <div id="grilla_resumen"></div>

    </div>

    </form>

    <!--
    <form method='post' action='<?php echo base_url('liquidaciones/liquidaciones_clientes/exportarAPdf') ?>' id='exportAPdf'>
        <input type='hidden' id='pdf_liquidacion_nro' name='pdf_liquidacion_nro' value="">
        <input type='hidden' id='pdf_cliente_id' name='pdf_cliente_id' value="">
        <input type='hidden' id='pdf_factura_nro' name='pdf_factura_nro' value="">
        <input type='hidden' id='pdf_periodo_desde' name='pdf_periodo_desde' value="">
        <input type='hidden' id='pdf_periodo_hasta' name='pdf_periodo_hasta' value="">
        <input type='hidden' id='pdf_sucursal' name='pdf_sucursal' value="">
        <input type='hidden' id='pdf_sucursal_id' name='pdf_sucursal_id' value="">
        <input type='hidden' id='pdf_cantidad_elementos' name='pdf_cantidad_elementos' value="">
        <input type='hidden' id='pdf_departamento' name='pdf_departamento' value="">
        <input type='hidden' id='pdf_departamento_id' name='pdf_departamento_id' value="">
        <input type='hidden' id='imprimir' name='imprimir' value="">
        <input type='hidden' id='pdf_fecha_ci_desde' name='pdf_fecha_ci_desde' value="">
        <input type='hidden' id='pdf_fecha_ci_hasta' name='pdf_fecha_ci_hasta' value="">
    </form>
    -->
</div>
<script type="text/javascript">
    const clientes = <?= json_encode($clientes); ?>;
    let esquema = []

    /**
     * SETEO VALOR DEL IVA, DEBERIA SETEARLO EN UN FUTURO USANDO LA CONFIGURACIÓN TRIBUTARIA DEL CLIENTE
     */
    $("#iva").val("0.21");

    $(document).ready(function() {

        /*
        $('.exportar_excel').click(function(event){
            $.ajax({
                url: '<?php echo base_url() . "clientes/clientes/exportarExcel" ?>',
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                data: esquema,
                type: 'post',
                success: function(data) { //alert(JSON.stringify(data));
                    console.log(data)
                }
            });
        })
        */

        $('.factura_a').click(function(event) {

            if ($('#periodo_desde').val() == "") {
                alert("Debe seleccionar el periodo inicio");
                return;
            }

            if ($('#periodo_hasta').val() == "") {
                alert("Debe seleccionar el periodo hasta");
                return;
            }

            //alert($('.form_liquidacion_add').serialize());
            $('#estado_id').val('1'); //var form_data = new FormData();
            $('#grilla').html('');
            $('#error').html('');
            $('#grilla_resumen').html('');
            $('#guardar').removeAttr("disabled");
            $('.factura_b').removeAttr("disabled");
            $('.exportarAPdf').removeAttr("disabled");
            var other_data = $('#form_pre_liquidacion').serializeArray();
            var form_data = new FormData();
            $.each(other_data, function(key, input) {
                form_data.append(input.name, input.value);
            });
            //console.log("haciendo petición...")
            $.ajax({
                url: '<?php echo base_url() . "ajax/liquidaciones/getLiquidacionClientePaqueteria" ?>',
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function(data) { //alert(JSON.stringify(data));
                    console.log("respuesta del servidor: ", data)
                    //console.log("Valor calculado: ", data.valor_calculado[0])

                    if (data.grilla.length == 0) {
                        $('#error').prepend('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>No se encontraron resultados!</div>');
                        //alert('El cliente no tiene configurado correctamente el esquema tarifario, revise por favor!');
                        //document.getElementById("guardar").disabled = true
                        return;
                    }

                    if (data.errores) {
                        data.errores.forEach(el => {
                            console.log("error: ", el)
                            $('#error').prepend(`<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>${el}</div>`);
                            //document.getElementById("guardar").disabled = true
                        })
                    }


                    if (data.valor_calculado) {
                        data.valor_calculado.forEach(el => {
                            if (el == 0 || el == null) {
                                $('#error').prepend('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>El cliente no tiene configurado correctamente el esquema tarifario, revise por favor!</div>');
                                //alert('El cliente no tiene configurado correctamente el esquema tarifario, revise por favor!');
                                //document.getElementById("guardar").disabled = true
                            }
                        })
                    }


                    if (data['grilla'].length == undefined) {
                        $('#grilla').html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>No se encontraron resultados.</div>');
                        return;
                    }

                    if (data.status) {
                        $('#grilla').html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>No se encontraron resultados.</div>');
                    } else {

                        esquema = []

                        $('#grilla').html('');
                        var contador = 0;
                        var cabecera = '<table class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">' +
                            '<thead>' +
                            '<tr>' +
                            '<th>Comprobante ing.</th>' +
                            '<th>Remito</th>' +
                            '<th>Codigo</th>' +
                            '<th style="width:100px">Fecha</th>' +
                            '<th>Descripcion</th>' +
                            '<th>Dimensiones</th>' +
                            '<th>Peso</th>' +
                            '<th>Peso Volum&eacute;trico</th>' +
                            '<th>Bultos</th>' +
                            '<th>Palets</th>' +
                            '<th>Metros Cubicos</th>' +
                            '<th>Gesti&oacute;n de Flota</th>' +
                            '<th>Precio Unitario</th>' +
                            '<th>Valor Declarado</th>' +
                            '<th>Cobranza</th>' +
                            '<th>Valor Calculado</th>' +
                            '</tr>' +
                            '</thead>';
                        var body = '<tbody id="body-grilla">';
                        var suma_total = 0;
                        var iva_puro = $('#iva').val();
                        var i = 0;
                        $('#cantidad_elementos').val(data['grilla'].length); //alert(JSON.stringify(data['grilla']));
                        $.each(data['grilla'], function(index, element) {

                            var precio = data['valor_calculado'][i];
                            var cant_disponible = 0;

                            /* AGREGADO */
                            let cantidad = 0;
                            let dimensiones = element['dimensiones'] ? element['dimensiones'] : '-';

                            let precio_unitario = 0;
                            let precio_valor_declarado = 0;
                            let precio_cobranza = 0;

                            let peso = null;

                            let nombre_gestion_flota = element['nombre_gestion_flota'] ?? '';
                            let cantidad_hora = element['cantidad_hora'] ?? '';

                            if (data['precios_unitario']) {
                                precio_unitario = data['precios_unitario'][i];
                            }

                            if (data['valor_declarado']) {
                                precio_valor_declarado = data['valor_declarado'][i];
                            }

                            if (data['cobranza']) {
                                precio_cobranza = data['cobranza'][i];
                            }

                            console.log(element)

                            if (element['peso']) {
                                cantidad = 1
                                peso = element['peso']
                            }

                            if (element['bultos']) {
                                cantidad = element['bultos']
                            }

                            if (element['palets']) {
                                cantidad = element['palets']
                            }

                            if (element['metro_cubico']) {
                                cantidad = 1
                                peso = null
                            }

                            const obj = {
                                comprobante: element['numero'],
                                remito: element['remito'],
                                codigo: element['barcode_externo'],
                                fecha: element['create'],
                                descripcion: element['descripcion_paquete'],
                                dimensiones: `${dimensiones}`,
                                peso: `${ peso ?? '-'}`,
                                peso_volumetrico: element['peso_volumetrico'],
                                bulto: element['bultos'],
                                palet: element['palets'],
                                metro_cubico: element['metro_cubico'],
                                gestion_flota: `${nombre_gestion_flota ? nombre_gestion_flota + ' // ' + cantidad_hora + 'hs' : '-'}`, 
                                precio_unitario: `${ (parseFloat(data['precios_unitario'][i])).toFixed(2) }`,
                                valor_declarado: `${ data['valor_declarado'] ? parseFloat(data['valor_declarado'][i]).toFixed(2) : '-' }`,
                                cobranza: `${ data['cobranza'] ? parseFloat(data['cobranza'][i]).toFixed(2) : '-' }`,
                                valor_calculado: `${ (parseFloat(data['valor_calculado'][i])).toFixed(2) }`, 
                            }

                            esquema.push(obj)

                            body += `
                                <tr id="resultados" class="nueva"><input type="hidden" name="fila_${contador}" value="${element['comprobante_ingreso_id']}|${element['remito']}|${element['numero']}|${element['remito']}|${element['fecha']}|${element['servicio_id']}|${element['servicio_especial']}|${cantidad}|${cant_disponible}|${precio}|${precio_unitario}|${precio_valor_declarado}|${precio_cobranza}"/>
                                    <td class="text-center"> ${ element['numero']} </td>
                                    <td class="text-center"> ${ element['remito']} </td>
                                    <td class="text-center"> ${ element['barcode_externo'] ?? '-'} </td>
                                    <td class="text-center"> ${ element['fecha']} </td>
                                    <td class="text-center"> ${ element['descripcion_paquete']}</td>
                                    <td class="text-center"> ${dimensiones} </td>
                                    <td class="text-center"> ${ peso ?? '-'} </td>
                                    <td class="text-center"> ${ element['peso_volumetrico'] ?? '-'} </td>
                                    <td class="text-center"> ${ element['bultos'] ?? '-'} </td>
                                    <td class="text-center"> ${ element['palets'] ?? '-'} </td>
                                    <td class="text-center"> ${ element['metro_cubico'] ?? '-'} </td>
                                    <td class="text-center"> ${nombre_gestion_flota ? nombre_gestion_flota + ' // ' + cantidad_hora + 'hs' : '-'} </td>
                                    <td class="text-center"> ${ (parseFloat(data['precios_unitario'][i])).toFixed(2) } </td>
                                    <td class="text-center"> ${ data['valor_declarado'] ? parseFloat(data['valor_declarado'][i]).toFixed(2) : '-' } </td>
                                    <td class="text-center"> ${ data['cobranza'] ? parseFloat(data['cobranza'][i]).toFixed(2) : '-' } </td>
                                    <td class="text-center"> ${ (parseFloat(data['valor_calculado'][i])).toFixed(2) } </td>
                                </tr>
                            `;

                            contador++;
                            suma_total += parseFloat(data['valor_calculado'][i]);
                            i++;
                        });
                        var iva_solo = suma_total * iva_puro;
                        var total_con_iva = parseFloat(suma_total) + parseFloat(iva_solo);
                        var cierre = '</tbody></table>';
                        var total = '<tr>' +
                            '<td colspan="14" class=""></td>' +
                            '<td style="text-align:right;font-weight: bold"><strong>Sub-Total:</td></strong>' +
                            '<td style="text-align:right;font-weight: bold"><strong>' + suma_total.toFixed(2) + '</strong></td>' +
                            '</tr>' +
                            '<tr>' +
                            '<td colspan="14" class=""></td>' +
                            '<td style="text-align:right;font-weight: bold"><strong>IVA:</td></strong>' +
                            '<td style="text-align:right;font-weight: bold"><strong>' + iva_solo.toFixed(2) + '</strong></td>' +
                            '</tr>' +
                            '<tr>' +
                            '<td colspan="14" class=""></td>' +
                            '<td style="text-align:right;font-weight: bold"><strong>Total:</td></strong>' +
                            '<td style="text-align:right;font-weight: bold"><strong>' + total_con_iva.toFixed(2) + '</strong></td>' +
                            '</tr>';
                        $('#grilla').append(cabecera + body + total + cierre);
                        barcodear();

                        $('#imprimir').val('facturaa');
                    }
                }
            });
        });

        /*
        $('.exportarAPdf').click(function(event) {
            $("#htmlLiquidacionSinIva").val(jQuery("#grilla").html());
            $("#htmlResumenLiquidacionSinIva").val(jQuery("#grilla_resumen").html());
            $("#pdf_liquidacion_nro").val(jQuery("#nro").val());
            $("#pdf_cliente_id").val(jQuery("#cliente_id").val());
            $("#pdf_factura_nro").val(jQuery("#factura").val());
            $("#pdf_periodo_desde").val(jQuery("#periodo_desde").val());
            $("#pdf_periodo_hasta").val(jQuery("#periodo_hasta").val());
            $("#pdf_sucursal").val($("#sucursal_id option:selected").text());
            $("#pdf_departamento").val($("#departamento_id option:selected").text());
            $("#pdf_sucursal_id").val($("#sucursal_id").val());
            $("#pdf_departamento_id").val($("#departamento_id").val());
            $("#pdf_cantidad_elementos").val($("#cantidad_elementos").val());
            $("#pdf_fecha_ci_desde").val(jQuery("#pdf_fecha_ci_desde").val());
            $("#pdf_fecha_ci_hasta").val(jQuery("#pdf_fecha_ci_hasta").val());
            $('#exportAPdf').submit();
        });
        */

    });
</script>