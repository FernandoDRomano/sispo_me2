<div class="col-xs-12">
    <div class="ibox-content">
        <div class="text-right">            
            <?php if($permisos_efectivos->insert==1) { ?> <a href="<?php echo base_url().'sucursales/sucursales/add/' ?>" class="btn btn-success"><i class="fa fa-plus"></i> Nueva Sucursal</a><?php } ?><hr>
        </div>
        <table id="results" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
            <thead>
                <tr>
                    <th><a href="#">Nombre</a></th>
                    <th><a href="#">Descripcion</a></th>
                    <th class="col-xs-2">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
              <?php foreach ($results as $result) { ?>
                  <tr>
                    <td><?php echo $result->nombre ?></td>
                    <td><?php echo $result->descripcion ?></td>
                    <td>
                      <div class="btn-group">
                        <?php if($permisos_efectivos->update==1) { ?><a href="<?php echo base_url().'sucursales/sucursales/edit/'.$result->id ?>" class="btn btn-success"><i class="fa fa-edit"></i></a><?php } ?>
                        <?php if($permisos_efectivos->delete==1) { ?><a onClick="eleminarRegistro('<?php echo base_url().'sucursales/sucursales/delete/'.$result->id ?>')" href="#" class="btn btn-danger"><i class="fa fa-trash-o"></i></a><?php } ?>
                      </div>
                    </td>
                  </tr>
              <?php } ?>
            </tbody>
        </table>
    </div>
</div>