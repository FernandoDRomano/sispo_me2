<div class="col-xs-12">
    <!-- boton crear -->
    <div class="ibox-content">
        <div class="text-right">            
            <?php if($permisos_efectivos->insert==1) { ?> <a href="<?php echo base_url().'tarifario/Tarifario/create/' ?>" class="btn btn-success"><i class="fa fa-plus"></i> Asignar Tarifario</a><?php } ?>
        </div>
    </div>
    <!-- end boton crear -->
    
    <!-- filtro -->
    <div class="ibox-content">
        <form action="<?php echo base_url() ?>tarifario/Tarifario/filtro" method="post" id="form-filtro">
            <div class="row">
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="buscar">Campo Libre</label>
                        <input type="text" id="buscar" name="buscar" class="form-control filtro-group" placeholder="Nombre, descripci&#243;n" autocomplete="off">
                    </div>
                </div>
                <div class="col-xs-6 ">
                    <div class="form-group">
                        <button type="submit" class="btn btn-success" style="margin-top: 23px;">Filtrar</button>
                        <a href="<?php echo base_url() ?>tarifario/Tarifario" class="btn btn-primary" style="margin-top: 23px;"><i class="fa fa-refresh"></i> Reinciar</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- end filtro -->

    <div class="ibox-content">        
        <table id="results" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
            <thead>
                <tr>
                    <th><a href="#">Cliente</a></th>
                    <th><a href="#">Esquema</a></th>
                    <th><a href="#">Acciones</a></th>
                </tr>
            </thead>
            <tbody>
               
              <?php foreach ($results as $result) { ?>
                  <tr>
                    <td>                      
                      <?php echo $result->nombreCliente ?><br>
                    </td>
                    <td>
                      <?php echo $result->nombreEsquema ?><br>
                    </td>
                    <td>
                      <div class="btn-group">
                        <?php if($permisos_efectivos->update==1) { ?><a href="<?php echo base_url().'tarifario/Tarifario/edit/'.$result->id ?>" class="btn btn-success"><i class="fa fa-edit"></i></a><?php } ?>
                        <!-- <?php if($permisos_efectivos->delete==1) { ?><a onClick="eleminarRegistro('<?php echo base_url().'tarifario/Tarifario/delete/'.$result->id ?>')" href="#" class="btn btn-danger"><i class="fa fa-trash-o"></i></a><?php } ?> -->
                        <?php if($permisos_efectivos->delete==1) { ?><a onClick="validarQueElClienteNoTengaPaquetesSinLiquidarAntesDeQuitarTarifario(event, '<?= $result->idCliente ?>', '<?php echo base_url().'tarifario/Tarifario/delete/'.$result->id ?>')" href="#" class="btn btn-danger"><i class="fa fa-trash-o"></i></a><?php } ?>
                        </div>
                    </td>
                  </tr>
              <?php } ?>
              
            </tbody>
        </table>
    </div>

</div>

<script type="text/javascript">

    async function validarQueElClienteNoTengaPaquetesSinLiquidarAntesDeQuitarTarifario(e, cliente, tarifario){
        e.preventDefault()
        
        const dominio = document.domain;
        const url = `//${dominio}/tarifario/Tarifario/validarQueElClienteNotengaPaquetesSinLiquidarAntesDeQuitarElTarifarioConfigurado`;
        
        try {
            
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({cliente})
            });

            const data = await response.json();
            const resultado = data.data.cantidadPiezasSinLiquidar
            
            //console.log("Resultado: ", data.data.cantidadPiezasSinLiquidar)

            if(resultado > 0){
                alert("El cliente tiene piezas sin liquidar aún, no se le puede quitar el Esquema Tarifario hasta que se liquiden todas las piezas.");
                return;
            }

            eleminarRegistro(tarifario);

            //return false

        } catch (error) {
                console.error(error)
        }
    }

</script>