<style>
    .hr{
        border-color: #a8a8a8;
        margin-top: 10px;
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
        <div class="alert alert-info">
            <strong>Atención!</strong> solo se muestran los movimientos de los 3 últimos meses. <br />
            Debe aplicar los filtros antes de visualizar o exportar datos.
        </div>


        <hr />
        <form id="formConsultasGlobales" name="formConsultasGlobales" action="<?= base_url('piezas/consulta_global_cliente/index') ?>" method="post">
            <div class="row">
                <div class="col-xs-12">
                    <h3 class="tituloFormulario">Identificadores</h3>
                </div>
            </div>
            <div class="row">
                <!--
                <div class="col-xs-3">
                    <label>Fecha Ingreso</label>
                    <input type="text" id="fecha_ingreso" name="fecha_ingreso" class="form-control filtro" readonly="readonly"/>
                </div>
                -->
                <div class="col-xs-4">
                    <div class="form-group">
                        <label>Pieza Nº</label>
                        <input type="text" id="pieza_id" name="pieza_id" class="form-control filtro" value="<?php echo $this->session->userdata('filtroConsultaGlobalPiezaId') ? $this->session->userdata('filtroConsultaGlobalPiezaId') : '' ?>" />
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label>Barra Externa</label>
                        <input type="text" id="barra_externa" name="barra_externa" class="form-control filtro" value="<?php echo $this->session->userdata('filtroConsultaGlobalBarraExterna') ? $this->session->userdata('filtroConsultaGlobalBarraExterna') : '' ?>" />
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label>Nº Comprobante</label>
                        <input type="text" id="comprobante" name="comprobante" class="form-control filtro" value="<?php echo $this->session->userdata('filtroConsultaGlobalComprobante') ? $this->session->userdata('filtroConsultaGlobalComprobante') : '' ?>" />
                    </div>
                </div>
                <!--
                <div class="col-xs-3">
                    <label>Cliente</label>
                    <input type="text" id="cliente" name="cliente" class="form-control filtro" value="<?php echo $this->session->userdata('filtroConsultaGlobalCliente') ? $this->session->userdata('filtroConsultaGlobalCliente') : '' ?>" />
                </div>
                -->
                
                
                <!--
                <div class="col-xs-3">
                    <label>Despacho</label>
                    <input type="text" id="despacho_id" name="despacho_id" class="form-control filtro" value="<?php echo $this->session->userdata('filtroConsultaGlobalDespachoId') ? $this->session->userdata('filtroConsultaGlobalDespachoId') : '' ?>" />
                </div>
                -->
                

                <div class="col-xs-12">
                    <h3 class="tituloFormulario">Destinatario</h3>
                </div>

                <div class="col-xs-4">
                    <div class="form-group">
                        <label>Destinatario</label>
                        <input type="text" id="destinatario" name="destinatario" class="form-control filtro" value="<?php echo $this->session->userdata('filtroConsultaGlobalDestinatario') ? $this->session->userdata('filtroConsultaGlobalDestinatario') : '' ?>" />
                    </div>
                </div>

                <div class="col-xs-3">
                    <div class="form-group">
                        <label>Domicilio</label>
                        <input type="text" id="domicilio" name="domicilio" class="form-control filtro" value="<?php echo $this->session->userdata('filtroConsultaGlobalDomicilio') ? $this->session->userdata('filtroConsultaGlobalDomicilio') : '' ?>" />
                    </div>
                </div>
                <div class="col-xs-3">
                    <div class="form-group">
                        <label>Localidad</label>
                        <input type="text" id="localidad" name="localidad" class="form-control filtro" value="<?php echo $this->session->userdata('filtroConsultaGlobalLocalidad') ? $this->session->userdata('filtroConsultaGlobalLocalidad') : '' ?>" />
                    </div>
                </div>
                <div class="col-xs-2">
                    <div class="form-group">
                        <label>Cod. Postal</label>
                        <input type="text" id="codigo_postal" name="codigo_postal" class="form-control filtro" value="<?php echo $this->session->userdata('filtroConsultaGlobalCodigoPostal') ? $this->session->userdata('filtroConsultaGlobalCodigoPostal') : '' ?>" />
                    </div>
                </div>
               
                
                <!--
                <div class="col-xs-3">
                    <label>Recibió</label>
                    <input type="text" id="recibio" name="recibio" class="form-control filtro" value="<?php echo $this->session->userdata('filtroConsultaGlobalRecibio') ? $this->session->userdata('filtroConsultaGlobalRecibio') : '' ?>" />
                </div>
                <div class="col-xs-3">
                    <label>Documento</label>
                    <input type="text" id="documento" name="documento" class="form-control filtro" value="<?php echo $this->session->userdata('filtroConsultaGlobalDocumento') ? $this->session->userdata('filtroConsultaGlobalDocumento') : '' ?>" />
                </div>
                -->
                <div class="col-xs-12">
                    <h3 class="tituloFormulario">Datos Varios</h3>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label>Datos Varios 1</label>
                        <input type="text" id="datos_varios_1" name="datos_varios_1" class="form-control filtro" value="<?php echo $this->session->userdata('filtroConsultaGlobalDatosVarios1') ? $this->session->userdata('filtroConsultaGlobalDatosVarios1') : '' ?>" />
                    </div>
                </div>

                <div class="col-xs-4">
                    <div class="form-group">
                        <label>Datos Varios 2</label>
                        <input type="text" id="datos_varios_2" name="datos_varios_2" class="form-control filtro" value="<?php echo $this->session->userdata('filtroConsultaGlobalDatosVarios2') ? $this->session->userdata('filtroConsultaGlobalDatosVarios2') : '' ?>" />
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label>Datos Varios 3</label>
                        <input type="text" id="datos_varios_3" name="datos_varios_3" class="form-control filtro" value="<?php echo $this->session->userdata('filtroConsultaGlobalDatosVarios3') ? $this->session->userdata('filtroConsultaGlobalDatosVarios3') : '' ?>" />
                    </div>
                </div>

                <div class="col-xs-12">
                    <h3 class="tituloFormulario">Otros Filtros</h3>
                </div>

                <div class="col-xs-4">
                    <div class="form-group">
                        <label>Cartero</label>
                        <input type="text" id="cartero" name="cartero" class="form-control filtro" value="<?php echo $this->session->userdata('filtroConsultaGlobalCartero') ? $this->session->userdata('filtroConsultaGlobalCartero') : '' ?>" />
                    </div>
                </div>

                <div class="col-xs-4">
                    <div class="form-group">
                        <label>Hoja Ruta</label>
                        <input type="text" id="hoja_ruta_id" name="hoja_ruta_id" class="form-control filtro" value="<?php echo $this->session->userdata('filtroConsultaGlobalHojaRutaId') ? $this->session->userdata('filtroConsultaGlobalHojaRutaId') : '' ?>" />
                    </div>
                </div>

                <div class="col-xs-4">
                    <div class="form-group">
                        <label>Sucursal</label>
                        <input type="text" id="sucursal" name="sucursal" class="form-control filtro" value="<?php echo $this->session->userdata('filtroConsultaGlobalSucursal') ? $this->session->userdata('filtroConsultaGlobalSucursal') : '' ?>" />
                    </div>
                </div>
                <div class="col-xs-3">
                    <div class="form-group">
                        <label>Estado</label>
                        <input type="text" id="estado" name="estado" class="form-control filtro" value="<?php echo $this->session->userdata('filtroConsultaGlobalEstado') ? $this->session->userdata('filtroConsultaGlobalEstado') : '' ?>" />
                    </div>
                </div>

                <div class="col-xs-3">
                    <div class="form-group">
                        <label>Servicio</label>
                        <input type="text" id="servicio" name="servicio" class="form-control filtro" value="<?php echo $this->session->userdata('filtroConsultaGlobalServicio') ? $this->session->userdata('filtroConsultaGlobalServicio') : '' ?>" />
                    </div>
                </div>

                <div class="col-xs-3">
                    <div class="form-group">
                        <label>Fecha Cambio Estado</label>
                        <input type="text" id="fecha_cambio_estado" name="fecha_cambio_estado" class="form-control filtro" value="<?php echo $this->session->userdata('filtroConsultaGlobalFechaCambioEstado') ? $this->session->userdata('filtroConsultaGlobalFechaCambioEstado') : '' ?>" />
                    </div>
                </div>

                <div class="col-xs-3">
                    <div class="form-group">
                        <label>Rendición</label>
                        <input type="text" id="rendicion_id" name="rendicion_id" class="form-control filtro" value="<?php echo $this->session->userdata('filtroConsultaGlobalRendicionId') ? $this->session->userdata('filtroConsultaGlobalRendicionId') : '' ?>" />
                    </div>
                </div>

                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="fecha_pieza_desde">Creacion Pieza Desde</label>
                        <input id="fecha_pieza_desde" name="fecha_pieza_desde" type="text" class="form-control datepicker" placeholder="Seleccione una fecha" value="<?php echo $this->session->userdata('filtroConsultaGlobalFechaPiezaDesde') ? $this->session->userdata('filtroConsultaGlobalFechaPiezaDesde') : '' ?>" required autocomplete="off" />
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="fecha_pieza_hasta">Creacion Pieza Hasta</label>
                        <input id="fecha_pieza_hasta" name="fecha_pieza_hasta" type="text" class="form-control datepicker" placeholder="Seleccione una fecha" value="<?php echo $this->session->userdata('filtroConsultaGlobalFechaPiezaHasta') ? $this->session->userdata('filtroConsultaGlobalFechaPiezaHasta') : '' ?>" required autocomplete="off" />
                    </div>
                </div>

            </div>

            <hr />

            <div class="row">
                <div class="col-xs-12" style="display: flex; justify-content: end; gap: 1rem;">
                    <button onclick="buscarPiezas(event)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                    <button onclick="exportarExcel(event)" class="btn btn-success"><i class="fa fa-print"></i> Exportar Excel</button>
                </div>
            </div>

            <div class="row" style="display: none;">
                <div class="col-xs-3">
                </div>
                <div class="col-xs-3">
                </div>
                <div class="col-xs-3">
                </div>
                <div class="col-xs-3">
                    <div class="text-right">
                        <button id="btnFiltrar" name="btnFiltrar" class="btn btn-primary" type="button">Filtrar</button>
                    </div>
                </div>
            </div>
            <input type="hidden" id="procesar" name="procesar" value="0" />
        </form>

        <div class="row" style="display: none;">
            <div class="col-xs-3">
            </div>
            <div class="col-xs-3">
            </div>
            <div class="col-xs-3">
            </div>
            <div class="col-xs-3">
                <form action="<?= base_url('piezas/consulta_global_cliente/exportarAExcel') ?>" method="post" id="form_consultas_globales">
                    <div class="text-right">
                        <a id="exportarAExcel" class="btn btn-success exportarAExcel" href="javascript:;"><i class="fa fa-print"></i> Exportar a Excel</a>
                    </div>
                </form>
            </div>
        </div>


        <hr />

        <div class="alert alert-success">
            <?php echo $time_total; ?>
        </div>

        <?php if ($mostrar) { ?>
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <strong>No se encontraron resultados para su consulta</strong>
            </div>
        <?php }?>

        <div id="grilla" class="col-xs-12" style="overflow-x: scroll">
            <?php if (isset($links)) { ?>
                <?php echo $links ?>
            <?php } ?>
            <table id="resultado" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered" style="margin-bottom: 2rem; ">
                <thead>
                    <tr>
                        <!--<th class="col-xs-1"><a href="#"></a></th>-->
                        <th><a href="#">F. Ingreso</a></th>
                        <th><a href="#">Pieza Nro.</a></th>
                        <th><a href="#">Barra Externa</a></th>
                        <th><a href="#">Comprob. Nro.</a></th>
                        <th><a href="#">Descripci&oacute;n</a></th>
                        <th><a href="#">Cliente</a></th>
                        <th><a href="#">Servicio</a></th>
                        <th><a href="#">Cartero</a></th>
                        <th><a href="#">Hoja Ruta</a></th>
                        <th><a href="#">Despacho</a></th>
                        <th><a href="#">Sucursal</a></th>
                        <th><a href="#">Estado</a></th>
                        <th><a href="#">Destinatario</a></th>
                        <th><a href="#">Domicilio</a></th>
                        <th><a href="#">C&oacute;digo Postal</a></th>
                        <th><a href="#">Localidad</a></th>
                        <th><a href="#">F. Cambio Estado</a></th>
                        <!--<th><a href="#">Visitas</a></th>-->
                        <th><a href="#">Rendición</a></th>
                        <th><a href="#">Recibió</a></th>
                        <th><a href="#">Documento</a></th>
                        <th><a href="#">Vínculo</a></th>
                        <th><a href="#">Datos Varios</a></th>
                        <th><a href="#">Datos Varios 1</a></th>
                        <th><a href="#">Datos Varios 2</a></th>
                        <th><a href="#">Datos Varios 3</a></th>
                        <th><a href="#">Email</a></th>
                        <th><a href="#">Celular</a></th>
                        <th><a href="#">Remito</a></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($results)) { ?>
                        <?php foreach ($results as $data) { ?>
                            <tr id="resultados" class="nueva">
                                <!--<td class=""></td>-->
                                <td class=""><?php echo $data['fecha_ingreso'] ?></td>
                                <td class=""><?php echo $data['pieza_id'] ?></td>
                                <td class=""><?php echo $data['barcode_externo'] ?></td>
                                <td class=""><?php echo $data['comprobante'] ?></td>
                                <td class=""><?php echo $data['descripcion_paquete'] ?></td>
                                <td class=""><?php echo $data['cliente'] ?></td>
                                <td class=""><?php echo $data['servicio'] ?></td>
                                <td class=""><?php echo $data['cartero'] ?></td>
                                <td class=""><?php echo $data['hoja_ruta_id'] ?></td>
                                <td class=""><?php echo $data['despacho_id'] ?></td>
                                <td class=""><?php echo $data['sucursal'] ?></td>
                                <td class=""><?php echo $data['estado_actual'] ?></td>
                                <td class=""><?php echo $data['destinatario'] ?></td>
                                <td class=""><?php echo $data['domicilio'] ?></td>
                                <td class=""><?php echo $data['codigo_postal'] ?></td>
                                <td class=""><?php echo $data['localidad'] ?></td>
                                <td class=""><?php echo $data['fecha_cambio_estado'] ?></td>
                                <!--<td class=""></td>-->
                                <td class=""><?php echo $data['rendicion_id'] ?></td>
                                <td class=""><?php echo $data['recibio'] ?></td>
                                <td class=""><?php echo $data['documento'] ?></td>
                                <td class=""><?php echo $data['vinculo'] ?></td>
                                <td class=""><?php echo $data['datos_varios'] ?></td>
                                <td class=""><?php echo $data['datos_varios_1'] ?></td>
                                <td class=""><?php echo $data['datos_varios_2'] ?></td>
                                <td class=""><?php echo $data['datos_varios_3'] ?></td>
                                <td class=""><?php echo $data['mail_dest'] ?></td>
                                <td class=""><?php echo $data['celular_dest'] ?></td>
                                <td class=""><?php echo $data['remito'] ?></td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                </tbody>
            </table>
            <?php if (isset($links)) { ?>
                <?php echo $links ?>
            <?php } ?>
        </div>

    </div>
</div>

<script type="text/javascript">
    function buscarPiezas(event){
        event.preventDefault();
        document.getElementById('btnFiltrar').click()
    }

    function exportarExcel(event){
        event.preventDefault();
        document.getElementById('exportarAExcel').click()
    }

    $(document).ready(function() {
        $('#btnFiltrar').click(function(event) {
            $('#procesar').val('0');
            var resultadoFiltroValido = filtroValido();
            if (resultadoFiltroValido) {
                $('#procesar').val('1');
                $(".exportarAExcel").show();
                $('#formConsultasGlobales').submit();
            } else {
                alert('Operación cancelada. Debe completar los filtros.');
            }
        });

        $(".exportarAExcel").click(function() {
            $('#procesar').val('0');
            var resultadoFiltroValido = filtroValido();
            if (resultadoFiltroValido) {
                alert('Se iniciará el proceso de exportación. Puede demorar unos instante. Pulse ACEPTAR para continuar.');
                $('#form_consultas_globales').submit();
            } else {
                alert('Operación cancelada. Debe completar los filtros y pulsar FILTRAR.');
            }
        });
    });

    function filtroValido() {
        var retorno = false;
        if ($('#pieza_id').val() != '' && retorno == false) {
            retorno = true;
        }
        if ($('#barra_externa').val() != '' && retorno == false) {
            retorno = true;
        }
        if ($('#comprobante').val() != '' && retorno == false) {
            retorno = true;
        }
        /*
        if ($('#cliente').val() != '' && retorno == false) {
            retorno = true;
        }
        */
        if ($('#servicio').val() != '' && retorno == false) {
            retorno = true;
        }
        if ($('#cartero').val() != '' && retorno == false) {
            retorno = true;
        }
        if ($('#hoja_ruta_id').val() != '' && retorno == false) {
            retorno = true;
        }
        /*
        if ($('#despacho_id').val() != '' && retorno == false) {
            retorno = true;
        }
        */
        if ($('#sucursal').val() != '' && retorno == false) {
            retorno = true;
        }
        if ($('#estado').val() != '' && retorno == false) {
            retorno = true;
        }
        if ($('#domicilio').val() != '' && retorno == false) {
            retorno = true;
        }
        if ($('#codigo_postal').val() != '' && retorno == false) {
            retorno = true;
        }
        if ($('#localidad').val() != '' && retorno == false) {
            retorno = true;
        }
        if ($('#fecha_cambio_estado').val() != '' && retorno == false) {
            retorno = true;
        }
        if ($('#rendicion_id').val() != '' && retorno == false) {
            retorno = true;
        }
        /*
        if ($('#recibio').val() != '' && retorno == false) {
            retorno = true;
        }
        if ($('#documento').val() != '' && retorno == false) {
            retorno = true;
        }
        */
        if ($('#datos_varios_1').val() != '' && retorno == false) {
            retorno = true;
        }
        if ($('#datos_varios_2').val() != '' && retorno == false) {
            retorno = true;
        }
        if ($('#datos_varios_3').val() != '' && retorno == false) {
            retorno = true;
        }

        if ($('#fecha_pieza_desde').val() != '' && retorno == false) {
            retorno = true;
        }
        if ($('#fecha_pieza_hasta').val() != '' && retorno == false) {
            retorno = true;
        }

        return retorno;
    }
</script>