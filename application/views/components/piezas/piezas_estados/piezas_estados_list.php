<div class="col-xs-12">
    <div class="ibox-content">
        <div class="text-right">
            <?php if($permisos_efectivos->insert==1) { ?> <a href="<?php echo base_url().'piezas/piezas_estados/add/' ?>" class="btn btn-success"><i class="fa fa-plus"></i> Nuevo Estado</a><?php } ?><hr>
        </div>
        <table id="results" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
            <thead>
                <tr>
                    <th class="col-xs-1"><a href="#">ID</a></th>
                    <th><a href="#">Nombre</a></th>
                    <th><a href="#">Descripcion</a></th>
                    <th class="col-xs-3 no-sort">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
              <?php foreach ($results as $result) { ?>
                  <tr>
                    <td><?php echo $result->id ?></td>
                    <td><?php echo $result->nombre ?></td>
                    <td><?php echo $result->descripcion ?></td>
                    <td>
                      <div class="btn-group">
                        <?php if($permisos_efectivos->insert==1) { ?><a href="<?php echo base_url().'piezas/piezas_estados/variables/'.$result->id ?>" class="btn btn-primary">Variables</a><?php } ?>
                        <?php if($permisos_efectivos->update==1) { ?><a href="<?php echo base_url().'piezas/piezas_estados/edit/'.$result->id ?>" class="btn btn-success"><i class="fa fa-edit"></i></a><?php } ?>
                      </div>
                    </td>
                  </tr>
              <?php } ?>
            </tbody>
        </table>
    </div>
</div>