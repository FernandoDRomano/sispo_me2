<div class="col-xs-12">
    <div class="ibox-content">
        <div class="text-right">            
            <?php if($permisos_efectivos->insert==1) { ?> <a href="<?php echo base_url().'liquidaciones/liquidaciones_ddis/add/' ?>" class="btn btn-success"  style="visibility: hidden"><i class="fa fa-plus"></i> Nueva Liquidación</a><?php } ?><hr>
        </div>
        <table id="results" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
            <thead>
                <tr>
                    <th class="col-xs-1"><a href="#">ID</a></th>
                    <th><a href="#">Sucursal_id</a></th>
                    <th><a href="#">Periodo_desde</a></th>
                    <th><a href="#">Periodo_hasta</a></th>
                    <th><a href="#">Periodo_alta_desde</a></th>
                    <th><a href="#">Periodo_alta_hasta</a></th>
                    <th class="col-xs-2">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
              <?php foreach ($results as $result) { ?>
                  <tr>
                    <td><?php echo $result->id ?></td>
                    <td><?php echo $result->sucursal ?></td>
                    <td><?php echo $result->periodo_desde ?></td>
                    <td><?php echo $result->periodo_hasta ?></td>
                    <td><?php echo $result->periodo_alta_desde ?></td>
                    <td><?php echo $result->periodo_alta_hasta ?></td>
                    <td>
                      <div class="btn-group">
                        <?php if($permisos_efectivos->delete==1) { ?><a href="<?php echo base_url().'liquidaciones/liquidaciones_ddis/edit/'.$result->id?>" class="btn btn-info"><i class="fa fa-edit"></i></a><?php } ?>
                        <?php if($permisos_efectivos->delete==1) { ?><a href="<?php echo base_url().'liquidaciones/liquidaciones_ddis/exportarAExcel/'.$result->id?>" class="btn btn-info"><i class="fa fa-file-o"></i></a><?php } ?>
                        <?php if($permisos_efectivos->delete==1) { ?><a onClick="eleminarRegistro('<?php echo base_url().'liquidaciones/liquidaciones_ddis/delete/'.$result->id ?>')" href="#" class="btn btn-danger"><i class="fa fa-trash-o"></i></a><?php } ?>
                      </div>
                    </td>
                  </tr>
              <?php } ?>
            </tbody>
        </table>
    </div>
</div>