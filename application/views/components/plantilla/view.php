<div class="modal-content">
    <div class="modal-header modal-header-primary" style="display: flex; align-items: center; justify-content: space-between;">
        <h2><?php echo $plantilla->nombre ?></h2>
        <button type="button" class="close d-inline" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i></button>
    </div>
    <div class="modal-body">
        <div class="form-horizontal">       
            <?php echo form_hidden('id',$plantilla->id) ?>
                <?php if($plantilla->descripcion):?>
                    <div class="form-group">
                        <h4>
                            <b>Descripci&#243;n:</b>  <?php echo $plantilla->descripcion ?> 
                        </h4>
                    </div>   
                    
                <?php endif ?>
        
        </div>
                    
                    <!-- Cobranza -->
                    <?php if(count($cobranza) > 0): ?>
                        <div class="form-group">
                            <h4><b>Tarifa por cobranza</b></h4>
                            Tipo: <?php echo $cobranza->tipo ?> <br>
                            Valor: <?php echo $cobranza->valor ?> 
                        </div>

                        <hr>
                    <?php endif ?>
                    <!-- End Cobranza -->

                    <!-- Valor Declarado -->
                    <?php if(count($valorDeclarado) > 0): ?>
                        <div class="form-group">
                            <h4><b>Tarifa por valor declarado</b></h4>
                            Tipo: <?php echo $valorDeclarado->tipo ?> <br>
                            Valor: <?php echo $valorDeclarado->valor ?> 
                        </div>

                        <hr>
                    <?php endif ?>
                    <!-- End Valor Declarado -->

                    <!-- Peso Aforado -->
                    <?php if(count($pesoAforado) > 0): ?>
                        <div class="form-group">
                            <h4><b>Tarifas por peso aforado</b></h4>
                            Valor: <?php echo $pesoAforado->valor ?> 
                        </div>

                        <hr>
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
                                    <tr>
                                        <th> <?php echo $r->desde_cant_unid_kg ?> </th>
                                        <td> <?php echo $r->hasta_cant_unid_kg ?> </td>
                                        <td> <?php echo $r->precio ?> </td>
                                        <td> <?php echo $r->bandera == 1 ? 'Bloque' : 'Unidad' ?> </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>

                        <hr>

                    <?php endif ?>
                    <!-- End Kilos -->

                    <!-- Bultos -->
                    <?php if(count($bultos) > 0): ?>

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
                                        <th> <?php echo $r->desde_cant_unid_bultos ?> </th>
                                        <td> <?php echo $r->hasta_cant_unid_bultos ?> </td>
                                        <td> <?php echo $r->precio ?> </td>
                                        <td> <?php echo $r->bandera_Corte_Bultos == 1 ? 'Bloque' : 'Unidad' ?> </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>

                        <hr>

                    <?php endif ?>
                    <!-- End Bultos -->

                    <!-- Metro Cubico -->
                    <?php if(count($metrosCubicos) > 0): ?>

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
                                        <th> <?php echo $r->desde_cantidad_metro_cubico ?> </th>
                                        <td> <?php echo $r->hasta_cantidad_metro_cubico ?> </td>
                                        <td> <?php echo $r->precio ?> </td>
                                        <td> <?php echo $r->bandera_bloque_unidad == 1 ? 'Bloque' : 'Unidad' ?> </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>

                        <hr>

                    <?php endif ?>
                    <!-- End Metro Cubico -->

                    <!-- Palets -->
                    <?php if(count($palets) > 0): ?>

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
                                        <th> <?php echo $r->desde_cantidad_palets ?> </th>
                                        <td> <?php echo $r->hasta_cantidad_palets ?> </td>
                                        <td> <?php echo $r->precio ?> </td>
                                        <td> <?php echo $r->bandera_palets == 1 ? 'Bloque' : 'Unidad' ?> </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>

                        <hr>

                    <?php endif ?>
                    <!-- End Palets -->

                    <!-- Gestion Flota -->
                    <?php if(count($gestionFlota) > 0): ?>

                        <h4><b>Tarifas por gesti&#243;n de flota</b></h4>
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
                    <?php if(count($warehouse) > 0): ?>

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

                    <!-- Distancia -->
                    <?php if(count($distancia) > 0): ?>

                        <h4><b>Tarifas por Distancia</b></h4>
                        <table class="table table-sm table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>Desde</th>
                                    <th>Hasta</th>
                                    <th>Precio</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($distancia as $r) { ?>
                                    <tr>
                                        <th> <?php echo $r->desde ?> </th>
                                        <td> <?php echo $r->hasta ?> </td>
                                        <td> <?php echo $r->precio ?> </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>

                        <hr>

                    <?php endif ?>
                    <!-- End Distancia -->
                    

    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-primary pull-left" data-dismiss="modal">Cerrar</button>
    </div>
</div>