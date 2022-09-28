<!-- RECORRIDO DE SUB ESQUEMAS -->
<?php if(count($subEsquemas) > 0): ?>
            <?php foreach($subEsquemas as $s) { ?>
            
                <h2 class="font-weight-bold text-uppercase">Sub Esquema <?php echo $s->id ?></h2>
                <hr>

                <p><span class="font-weight-bold">Zona origen:</span> <?php echo "1" ?> </p>
                <p><span class="font-weight-bold">Zona destino:</span> <?php echo "1" ?> </p>

                <!-- Cobranza -->
                <?php if(count($cobranza) > 0): ?>
                    <?php foreach($cobranza as $r) { ?>

                        <?php if($r->idSubEsquema == $s->id): ?>
                            <div class="form-group">
                                <h4><b>Tarifa por cobranza</b></h4>
                                Tipo: <?php echo $r->tipo ?> <br>
                                Valor: <?php echo $r->valor ?> 
                            </div>
                        <?php endif ?>

                    <?php } ?>
                <?php endif ?>
                <!-- End Cobranza -->

                <!-- Valor Declarado -->
                <?php if(count($valorDeclarado) > 0): ?>
                    <?php foreach($valorDeclarado as $r) { ?>

                        <?php if($r->idSubEsquema == $s->id): ?>
                            <div class="form-group">
                                <h4><b>Tarifa por valor declarado</b></h4>
                                Tipo: <?php echo $r->tipo ?> <br>
                                Valor: <?php echo $r->valor ?> 
                            </div>
                        <?php endif ?>
                    
                    <?php } ?>
                <?php endif ?>
                <!-- End Valor Declarado -->

                <!-- Peso Aforado -->
                <?php if(count($pesoAforado) > 0): ?>
                    <?php foreach($pesoAforado as $r) { ?>
                        
                        <?php if($r->idSubEsquema == $s->id): ?>
                            <div class="form-group">
                                <h4><b>Tarifas por peso aforado</b></h4>
                                Valor: <?php echo $r->valor ?> 
                            </div>
                        <?php endif ?>

                    <?php } ?>
                <?php endif ?>
                <!-- End Peso Aforado -->

                <!-- Kilos -->
                <?php if(count($kilos) > 0): ?>

                    <h4><b>Tarifas por kilos</b></h4>
                    <table class="table table-sm table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>Desde</th>
                                <th>Hasta</th>
                                <th>Precio</th>
                                <th>Tipo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($kilos as $r) { ?>
                                <?php if($r->idSubEsquema == $s->id): ?>
                                    <tr>
                                        <th> <?php echo $r->desde ?> </th>
                                        <td> <?php echo $r->hasta ?> </td>
                                        <td> <?php echo $r->precio ?> </td>
                                        <td> <?php echo $r->bandera == 1 ? 'Bloque' : 'Unidad' ?> </td>
                                    </tr>
                                <?php endif ?>
                            <?php } ?>
                        </tbody>
                    </table>

                    <hr>

                <?php endif ?>
                <!-- End Kilos -->

                <!-- Bultos -->
                <?php if(count($bultos) > 0 && $bultos[0]->idSubEsquema == $s->id): ?>

                    <h4><b>Tarifas por bultos</b></h4>
                    <table class="table table-sm table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>Desde</th>
                                <th>Hasta</th>
                                <th>Precio</th>
                                <th>Tipo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($bultos as $r) { ?>
                                <tr>
                                    <th> <?php echo $r->desde ?> </th>
                                    <td> <?php echo $r->hasta ?> </td>
                                    <td> <?php echo $r->precio ?> </td>
                                    <td> <?php echo $r->bandera == 1 ? 'Bloque' : 'Unidad' ?> </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>

                    <hr>

                <?php endif ?>
                <!-- End Bultos -->

                <!-- Metro Cubico -->
                <?php if(count($metrosCubicos) > 0 && $metrosCubicos[0]->idSubEsquema == $s->id): ?>

                    <h4><b>Tarifas por Metro cubico</b></h4>
                    <table class="table table-sm table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>Desde</th>
                                <th>Hasta</th>
                                <th>Precio</th>
                                <th>Tipo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($metrosCubicos as $r) { ?>
                                <tr>
                                    <th> <?php echo $r->desde ?> </th>
                                    <td> <?php echo $r->hasta ?> </td>
                                    <td> <?php echo $r->precio ?> </td>
                                    <td> <?php echo $r->bandera == 1 ? 'Bloque' : 'Unidad' ?> </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>

                    <hr>

                <?php endif ?>
                <!-- End Metro Cubico -->

                <!-- Palets -->
                <?php if(count($palets) > 0 && $palets[0]->idSubEsquema == $s->id): ?>

                    <h4><b>Tarifas por palets</b></h4>
                    <table class="table table-sm table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>Desde</th>
                                <th>Hasta</th>
                                <th>Precio</th>
                                <th>Tipo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($palets as $r) { ?>
                                <tr>
                                    <th> <?php echo $r->desde ?> </th>
                                    <td> <?php echo $r->hasta ?> </td>
                                    <td> <?php echo $r->precio ?> </td>
                                    <td> <?php echo $r->bandera == 1 ? 'Bloque' : 'Unidad' ?> </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>

                    <hr>

                <?php endif ?>
                <!-- End Palets -->

                <!-- Gestion Flota -->
                <?php if(count($gestionFlota) > 0 && $gestionFlota[0]->idSubEsquema == $s->id): ?>

                    <h4><b>Tarifas por gestión de flota</b></h4>
                    <table class="table table-sm table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>Tipo</th>
                                <th>Precio</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($gestionFlota as $r) { ?>
                                <tr>
                                    <td> 
                                        <?php echo $r->tipo_vehiculo == 1 && $r->tipo_hora == 1 ? 'Moto // Hora Comun' : '' ?>   
                                        <?php echo $r->tipo_vehiculo == 1 && $r->tipo_hora == 2 ? 'Moto // Hora Especial' : '' ?>   
                                        <?php echo $r->tipo_vehiculo == 2 && $r->tipo_hora == 1 ? 'Auto // Hora Comun' : '' ?>   
                                        <?php echo $r->tipo_vehiculo == 2 && $r->tipo_hora == 2 ? 'Auto // Hora Especial' : '' ?>   
                                        <?php echo $r->tipo_vehiculo == 3 && $r->tipo_hora == 1 ? 'Camioneta // Hora Comun' : '' ?>   
                                        <?php echo $r->tipo_vehiculo == 3 && $r->tipo_hora == 2 ? 'Camioneta // Hora Especial' : '' ?>   
                                        <?php echo $r->tipo_vehiculo == 4 && $r->tipo_hora == 1 ? 'Furgon // Hora Comun' : '' ?>   
                                        <?php echo $r->tipo_vehiculo == 4 && $r->tipo_hora == 2 ? 'Furgon // Hora Especial' : '' ?>   
                                        <?php echo $r->tipo_vehiculo == 5 && $r->tipo_hora == 1 ? 'Camion // Hora Comun' : '' ?>   
                                        <?php echo $r->tipo_vehiculo == 5 && $r->tipo_hora == 2 ? 'Camion // Hora Especial' : '' ?>    
                                    </td>
                                    <td> <?php echo $r->precio ?> </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>

                    <hr>

                <?php endif ?>
                <!-- End Gestion Flota -->

                <!-- WareHouse -->
                <?php if(count($warehouse) > 0 && $warehouse[0]->idSubEsquema == $s->id): ?>

                    <h4><b>Tarifas por warehouse</b></h4>
                    <table class="table table-sm table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>Desde</th>
                                <th>Hasta</th>
                                <th>Precio</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($warehouse as $r) { ?>
                                <tr>
                                    <td> <?php echo $r->desde ?> </td>
                                    <td> <?php echo $r->hasta ?> </td>
                                    <td> <?php echo $r->precio ?> </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>

                <?php endif ?>
                <!-- End WareHouse -->

            
            <?php } ?>
        <?php endif?>