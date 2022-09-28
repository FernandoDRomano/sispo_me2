<div class="col-xs-12">
    <div class="ibox-content">

        <div class="row">
            <!-- form Search -->
            <form action="<?php echo base_url() ?>clientes/clientes_vtex/filtro" method="post" id="formSearch">
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="searchIdCliente">Nombre cliente</label>
                        <select id="searchIdCliente" name="searchIdCliente" class="form-control filtro-group" required>
                            <option disabled selected value="">Seleccione</option>

                            <?php foreach ($clientesVtex as $c) : ?>
                                <option value="<?= $c->idCliente ?>"><?= $c->nombreCliente ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-xs-3">
                    <div class="form-group">
                        <button type="submit" name="action" value="search" class="btn btn-success" style="margin-top: 23px;">Buscar</button>
                    </div>
                </div>
            </form>

            <!-- Form Insert -->
            <!-- BOTON INSRTAR SOLO SI SE TIENE PERMISOS -->
            <?php if ($permisos_efectivos->insert == 1) : ?>
                <form action="<?php echo base_url() ?>clientes/clientes_vtex/filtro" method="post" id="formInsert">

                    <div class="col-xs-3"></div>
                    <div class="col-xs-2">
                        <div class="form-group">
                            <button type="submit" name="action" value="insert" form="formInsert" class="btn btn-primary" style="margin-top: 23px;"><i class="fa fa-plus"></i>Insertar</button>
                        </div>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
    <br>

    <!-- EDITAR / INSERTAR -->

    <?php if (!empty($result)) : ?>
        <?php if ($result->insert == true && $permisos_efectivos->insert == 1) : ?>
            <h3>Crear nuevo cliente Vtex</h3>
        <?php elseif ($permisos_efectivos->update == 1) : ?>
            <h3>Editar cliente vtex</h3>
        <?php endif; ?>

        <div class="ibox-content">
            <a target="_blank" class="pull-right" href="<?= base_url('assets/upload/InstruccionesVtex.pdf'); ?>" download="Instrucciones Vtex"><i class="fa fa-info-circle"></i> Ayuda</a>
            <br>

            <form method="post" id="formEdit">
                <?php if ($result->insert) : ?>
                    <div class="row">
                        <div class="col-xs-4">
                            <div class="form-group">
                                <label for="IdCliente">Nombre cliente</label>
                                <select id="IdCliente" name="IdCliente" class="form-control filtro-group" required>
                                    <option disabled selected value="">Seleccione</option>
                                    <?php foreach ($cliente as $c) : ?>
                                        <option value="<?= $c->idCliente ?>"><?= $c->nombreCliente ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                <?php else : ?>
                    <!-- ID, NOMBRE CLIENTE -->
                    <div class="row">
                        <div class="col-xs-4">
                            <div class="form-group">
                                <label for="IdCliente">Id cliente</label>
                                <input type="text" id="IdCliente" name="IdCliente" class="form-control filtro-group" readonly autocomplete="off" value="<?= $result->idCliente ?>">
                            </div>
                        </div>

                        <div class="col-xs-4">
                            <div class="form-group">
                                <label for="nombreCliente">Nombre cliente</label>
                                <input type="text" id="nombreCliente" name="nombreCliente" class="form-control filtro-group" readonly autocomplete="off" value="<?= $result->nombreCliente ?>">
                            </div>
                        </div>
                    </div>
                    <br>
                <?php endif; ?>

                <!-- NOMBRE CUENTA Y CREDENCIALES -->
                <div class="row">
                    <div class="col-xs-4">
                        <div class="form-group">
                            <label for="cuentaCliente">Nombre de cuenta Vtex</label>
                            <input type="text" id="cuentaCliente" name="cuentaCliente" class="form-control filtro-group" placeholder="" autocomplete="off" value="<?= $result->cuentaCliente ?>"">
                        </div>
                    </div>
                    <div class=" col-xs-4">
                            <div class="form-group">
                                <label for="appKey">Clave de aplicacion</label>
                                <input type="text" id="appKey" name="appKey" class="form-control filtro-group" placeholder="vtexappkey-[cuenta]-abdc1234" autocomplete="off" value="<?= $result->appKey ?>">
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <div class="form-group">
                                <label for="token">Token</label>
                                <input type="text" id="token" name="token" class="form-control filtro-group" placeholder="JEYHXOD..." autocomplete="off" value="<?= $result->token ?>">
                            </div>
                        </div>
                    </div>
                    <br>

                    <!-- ROL Y EVENTO -->
                    <div class="row">
                        <div class="col-xs-4">
                            <label for="">Rol de la tienda</label>
                            <div class="form-group">
                                <input type="radio" id="Fulfillment" name="rolTienda" value="F" <?= $result->rolTienda == 'F' ? 'checked' : '' ?>>
                                <label for="Fulfillment">Fulfillment</label>
                            </div>
                             <div class="form-group">
                                <input type="radio" id="Marketplace" name="rolTienda" value="M" <?= $result->rolTienda == 'M' ? 'checked' : '' ?>>
                                <label for="Marketplace">Marketplace</label>
                            </div>
                        </div>

                        <div class="col-xs-4">
                            <label for="">Evento</label>
                            <div class="form-group">
                                <input type="radio" id="invoiced" name="evento" value="invoiced" checked>
                                <label for="invoiced">Invoiced <small>(Facturado)</small></label>
                            </div>
                        </div>
                    </div>
                    <br>

                    <!-- MUELLE Y POLITICA DE ENVIO/TARIFARIO -->
                    <div class="row">
                        <div class="col-xs-4">
                            <div class="form-group">
                                <label for="idMuelle">Id muelle</label>
                                <input type="text" id="idMuelle" name="idMuelle" class="form-control filtro-group" placeholder="" autocomplete="off" value="<?= $result->idMuelle ?>">
                            </div>
                        </div>

                        <div class="col-xs-4">
                            <div class="form-group">
                                <label for="tarifario">Politica de envio / Tarifario</label>
                                <select id="tarifario" name="tarifario" class="form-control filtro-group">
                                    <option disabled selected value="null">Seleccione</option>
                                    <?php foreach ($tarifarios as $t) : ?>
                                        <option value="<?= $t->idTarifario ?>" <?= $result->tarifario == $t->idTarifario ? 'selected' : '' ?>><?= $t->nombreTarifario ?></option>
                                    <?php endforeach; ?>
                                </select>

                            </div>
                        </div>
                    </div>
                    <br>

                    <!-- BOTONES -->
                    <div class="row">
                        <?php if ($result->insert == true && $permisos_efectivos->insert == 1) : ?>
                            <div class="col-xs-3">
                                <div class="form-group">
                                    <button type="submit" formaction="<?php echo base_url() ?>clientes/clientes_vtex/insert" name="action" value="insert" class="btn btn-success" style="margin-top: 23px;">Insertar</button>
                                </div>
                            </div>
                        <?php elseif ($permisos_efectivos->update == 1) : ?>
                            <div class="col-xs-3">
                                <div class="form-group">
                                    <button type="submit" formaction="<?php echo base_url() ?>clientes/clientes_vtex/update" name="action" value="edit" class="btn btn-success" style="margin-top: 23px;">Editar</button>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
            </form>

        </div>
    <?php endif; ?>
</div>

<script>
    const permisoEdit = <?= ($permisos_efectivos->update == 1 || $permisos_efectivos->insert == 1) ? "true" : "false" ?>;
    $(document).ready(() => {
        $("#formEdit :input").prop("required", true).prop("autocomplete", 'off');


        if (!permisoEdit) {
            $("#formEdit :input").prop("disabled", true);
        }
    })
</script>