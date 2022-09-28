<div class="col-xs-12">
    <div class="ibox-content">
        <div class="text-right">            
            <?php if($permisos_efectivos->insert==1) { ?> <a href="<?php echo base_url().'liquidaciones/liquidaciones_conceptos/add/' ?>" class="btn btn-success"><i class="fa fa-plus"></i> Nueva Categoria</a><?php } ?><hr>
        </div>
        <table id="results" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
            <thead>
                <tr>
                    <th class="col-xs-1"><a href="#">ID</a></th>
                    <th><a href="#">Concepto</a></th>
                    <th><a href="#">Operacion</a></th>
                    <th><a href="#">F. Creación</a></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
              <?php foreach ($results as $result) { ?>
                  <tr>
                    <td><?php echo $result->id ?></td>
                    <td><?php echo $result->concepto ?></td>
                    <td><?php echo $result->operacion == "R"?"RESTA":"SUMA"; ?></td>
                    <td><?php echo $result->create ?></td>
                    <td>
                      <div class="btn-group">
                        <a data-toggle="modal" href="<?php echo base_url().'liquidaciones/liquidaciones_conceptos/view/'.$result->id ?>" data-target="#myModal" class="btn btn-info"><i class="fa fa-search"></i></a>
                        <?php if($permisos_efectivos->update==1) { ?><a href="<?php echo base_url().'liquidaciones/liquidaciones_conceptos/edit/'.$result->id ?>" class="btn btn-success"><i class="fa fa-edit"></i></a><?php } ?>
                        <?php if($permisos_efectivos->delete==1) { ?><a onClick="eleminarRegistro('<?php echo base_url().'liquidaciones/liquidaciones_conceptos/delete/'.$result->id ?>')" href="#" class="btn btn-danger"><i class="fa fa-trash-o"></i></a><?php } ?>
                      </div>
                    </td>
                  </tr>
              <?php } ?>
            </tbody>
        </table>
    </div>
</div>