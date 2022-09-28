<div class="col-xs-12">
    <div class="ibox-content">
        <div class="row">
            <form action="<?php echo base_url() ?>clientes/clientes_vtex_tarifario/filtro" method="post" id="formSearch">
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="searchIdTarifario">Nombre Tarifario</label>
                        <select id="searchIdTarifario" name="searchIdTarifario" class="form-control filtro-group" required>
                            <option disabled selected value="">Seleccione</option>

                            <?php foreach ($tarifarios as $t) : ?>
                                <option value="<?= $t->idTarifario ?>"><?= $t->nombreTarifario ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-xs-3">
                    <div class="form-group">
                        <button type="submit" name="action" value="search" class="btn btn-success" style="margin-top: 23px;"><i class="fa fa-search"></i> Buscar</button>
                    </div>
                </div>
            </form>

            <!-- Form Insert -->
            <!-- BOTON INSRTAR SOLO SI SE TIENE PERMISOS -->
            <?php if ($permisos_efectivos->insert == 1) : ?>
                <form action="<?php echo base_url() ?>clientes/clientes_vtex_tarifario/filtro" method="post" id="formInsert">
                    <div class="col-xs-3"></div>
                    <div class="col-xs-2">
                        <div class="form-group">
                            <button type="submit" name="action" value="insert" form="formInsert" class="btn btn-primary" style="margin-top: 23px;"><i class="fa fa-plus"> </i> Insertar</button>
                        </div>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
    <br>
    <?php if (!empty($result)) : ?>
        <?php if ($result->insert == true && $permisos_efectivos->insert == 1) : ?>
            <h3>Insertar nuevo tarifario</h3>
        <?php elseif ($permisos_efectivos->update == 1) : ?>
            <h3>Editar tarifario</h3>
        <?php endif; ?>


        <div class="ibox-content">
            <a target="_blank" class="pull-right" href="<?= base_url('assets/upload/InstruccionesVtex.pdf'); ?>" download="Instrucciones Vtex"><i class="fa fa-info-circle"></i> Ayuda</a>
            <br>
            <form action="<?= base_url('clientes/clientes_vtex_tarifario/exportarAExcel') ?>" method="post" id="formExport">
            </form>
            <form method="post" id="formEdit" enctype='multipart/form-data'>

                <!-- ID, NOMBRE y ORIGEN -->
                <div class="row">
                    <div class="col-xs-4">
                        <div class="form-group">
                            <label for="id">Id tarifario</label>
                            <input type="text" id="id" name="id" class="form-control filtro-group" value="<?= $result->id ?>" readonly>
                        </div>
                    </div>

                    <div class="col-xs-4">
                        <div class="form-group">
                            <label for="nombre">Nombre tarifario</label>
                            <input type="text" id="nombre" name="nombre" placeholder="Correo Flash [nombre]" class="form-control filtro-group" value="<?= $result->nombre ?>">
                            <small>Debe empezar con el nombre "Correo Flash "</small>
                        </div>
                    </div>

                    <div class="col-xs-4">
                        <div class="form-group">
                            <label for="origen">Origen</label>
                            <input type="text" id="origen" name="origen" class="form-control filtro-group" value="<?= $result->origen ?>">
                        </div>
                    </div>
                </div>
                <br>

                <!-- DIMENSIONES  -->
                <div class="row">
                    <div class="col-xs-4">
                        <div class="form-group">
                            <label for="max_suma_aristas">Maxima suma de aristas (cm)</label>
                            <input type="number" step="0.0001" id="max_suma_aristas" name="max_suma_aristas" class="form-control filtro-group" value="<?= $result->max_suma_aristas ?>">
                        </div>
                    </div>
                    <div class=" col-xs-4">
                        <div class="form-group">
                            <label for="max_arista">Maxima arista (cm)</label>
                            <input type="number" step="0.0001" id="max_arista" name="max_arista" class="form-control filtro-group" value="<?= $result->max_arista ?>">
                        </div>
                    </div>
                </div>
                <br>

                <!-- FACTOR CUBICO -->
                <div class="row">
                    <div class="col-xs-4">
                        <div class="form-group">
                            <label for="factor_volumetrico">Factor Volumetrico</label>
                            <input type="number" step="0.0001" id="factor_volumetrico" name="factor_volumetrico" class="form-control filtro-group" value="<?= $result->factor_volumetrico ?>">
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="form-group">
                            <label for="factor_peso_minimo">Factor de peso mínimo</label>
                            <input type="number" step="0.0001" id="factor_peso_minimo" name="factor_peso_minimo" class="form-control filtro-group" value="<?= $result->factor_peso_minimo ?>">
                        </div>
                    </div>
                </div>
                <br>

                <!-- ITEMS Y PRECIO MIN MAX -->
                <div class="row">
                    <div class="col-xs-4">
                        <div class="form-group">
                            <label for="minimo_items">Mínimo de ítems</label>
                            <input type="number" step="0.0001" id="minimo_items" name="minimo_items" class="form-control filtro-group" value="<?= $result->minimo_items ?>">
                        </div>
                    </div>
                    <div class=" col-xs-4">
                        <div class="form-group">
                            <label for="valor_minimo">Valor mínimo</label>
                            <input type="number" step="0.0001" id="valor_minimo" name="valor_minimo" class="form-control filtro-group" value="<?= $result->valor_minimo ?>">
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="form-group">
                            <label for="valor_maximo">Valor máximo</label>
                            <input type="number" step="0.0001" id="valor_maximo" name="valor_maximo" class="form-control filtro-group" value="<?= $result->valor_maximo ?>">
                        </div>
                    </div>
                </div>
                <br>



                <h3>Costos de envio</h3><br>
                <!-- BOTONES -->
                <div class="row">

                    <!-- EXPORTAR -->
                    <div class="col-xs-6 ">

                        <a href="<?= base_url('assets/upload/ModeloTarifario.xls'); ?>" download="modelo_tarifario_precios" class="btn btn-success">Modelo Excel</a>
                        <!-- IMPORTACION -->
                        <?php if ($permisos_efectivos->insert == 1 ||  $permisos_efectivos->update == 1) : ?>
                            <label class="btn btn-success" for="tarifario_precios">
                                <input type="file" id="tarifario_precios" name="tarifario_precios" style="display:none" onchange="$('#upload-file-info').text(this.files[0].name)">
                                <i class="fa fa-upload" aria-hidden="true"></i> Importar Excel
                            </label>
                            <span class='label label-info' id="upload-file-info"></span>
                        <?php endif; ?>

                        <?php if ($permisos_efectivos->exportar == 1) : ?>
                            <button type="submit" form="formExport" style="display: inline-block;" class="btn btn-success exportarAExcel" name="idToExport" value="<?= $result->id ?>" <?= $result->insert == true ? 'disabled' : '' ?>>
                                <i class="fa fa-print"></i>
                                Exportar a Excel
                            </button>
                        <?php endif; ?>
                    </div>

                    <div class="col-xs-3"></div>


                    <!-- INSERTAR -->
                    <?php if ($result->insert == true && $permisos_efectivos->insert == 1) : ?>
                        <div class="col-xs-3">
                            <div class="form-group">
                                <button type="submit" form="formEdit" formaction="<?php echo base_url() ?>clientes/clientes_vtex_tarifario/insert" name="action" value="insert" class="btn btn-primary pull-right">
                                    <i class="fa fa-save" aria-hidden="true"></i>
                                    Guardar
                                </button>
                            </div>
                        </div>

                        <!-- EDITAR -->
                    <?php elseif ($permisos_efectivos->update == 1) : ?>
                        <div class="col-xs-3">
                            <div class="form-group">
                                <button type="submit" form="formEdit" formaction="<?php echo base_url() ?>clientes/clientes_vtex_tarifario/edit" name="action" value="edit" class="btn btn-primary pull-right">
                                    <i class="fa fa-save" aria-hidden="true"></i>
                                    Guardar
                                </button>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </form>

        </div>
        <?php if (!empty($tarifario_precios)) : ?>

            <div class="ibox-content">

                <div id="grilla" class="col-xs-12" style="overflow-x: scroll">
                    <div class="row" style="margin-bottom: 10px;   display: flex; align-items: center;">

                        <table id="results" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
                            <thead>
                                <tr>
                                    <th>id tarifario</th>
                                    <th>activo</th>
                                    <th>ZipCodeStart</th>
                                    <th>ZipCodeEnd</th>
                                    <th>PolygonName</th>
                                    <th>WeightStart</th>
                                    <th>WeightEnd</th>
                                    <th>AbsoluteMoneyCost</th>
                                    <th>PricePercent</th>
                                    <th>PriceByExtraWeight</th>
                                    <th>MaxVolume</th>
                                    <th>TimeCost</th>
                                    <th>Country</th>
                                    <th>MinimumValueInsurance</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tarifario_precios as $tp) : ?>

                                    <tr>
                                        <td><?= $tp->id_vtex_tarifario ?> </td>
                                        <td><?= $tp->activo ?> </td>
                                        <td><?= $tp->ZipCodeStart ?> </td>
                                        <td><?= $tp->ZipCodeEnd ?> </td>
                                        <td><?= $tp->PolygonName ?> </td>
                                        <td><?= $tp->WeightStart ?> </td>
                                        <td><?= $tp->WeightEnd ?> </td>
                                        <td><?= $tp->AbsoluteMoneyCost ?> </td>
                                        <td><?= $tp->PricePercent ?> </td>
                                        <td><?= $tp->PriceByExtraWeight ?> </td>
                                        <td><?= $tp->MaxVolume ?> </td>
                                        <td><?= $tp->TimeCost ?> </td>
                                        <td><?= $tp->Country ?> </td>
                                        <td><?= $tp->MinimumValueInsurance ?> </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row"></div>

            </div>
        <?php endif; ?>
</div>



<?php endif; ?>
</div>

<script>
    const permisoEdit = <?= ($permisos_efectivos->update == 1 || $permisos_efectivos->insert == 1) ? "true" : "false" ?>;
    $(document).ready(() => {
        $("#formEdit :input").prop("required", true).prop("autocomplete", 'off');
        $("#tarifario_precios").prop("required", false);
        if (!permisoEdit) {
            $("#formEdit :input").prop("disabled", true);
        }
    })
</script>