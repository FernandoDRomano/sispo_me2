
<!--        <div class="text-right">            
            <?php if($permisos_efectivos->insert==1) { ?> <a href="<?php echo base_url().'paqueteria_parametros/add/' ?>" class="btn btn-success"><i class="fa fa-plus"></i> Nueva Categoria</a><?php } ?><hr>
        </div>-->
        <table id="results" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
            <thead>
                <tr>
                    <th class="col-xs-1"><a href="#">ID</a></th>
                    <th><a href="#">Tipo de Paqueteria</a></th>
                    <th><a href="#">Parametro</a></th>
                    <th><a href="#">Valor</a></th>
                    <th><a href="#">Create</a></th>
                </tr>
            </thead>
            <tbody>
              <?php foreach ($paqueteria_parametros as $result) { ?>
                  <tr>
                    <td><?php echo $result->id ?></td>
                    <td><?php echo $result->tipo ?></td>
                    <td><?php echo $result->parametro ?></td>
                    <td><?php echo $result->valor ?></td>
                    <td><?php echo $result->create ?></td>
                  </tr>
              <?php } ?>
            </tbody>
        </table>
