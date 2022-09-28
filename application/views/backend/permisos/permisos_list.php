<div class="col-xs-12">
  <div class="ibox-content">
    <div class="text-right">
      <?php if($permisos_efectivos->insert==1) { ?> <a href="<?php echo base_url().'backend/permisos/add/' ?>" class="btn btn-primary"><i class="fa fa-plus"></i> Nuevo Permiso</a><?php } ?><hr>
    </div>
    <table id="results" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
        <thead>
            <tr>
                <th class="col-xs-1"><a href="#">ID</a></th>
                <th><a href="#">Menu</a></th>
                <th><a href="#">Grupo</a></th>
                <th class="col-xs-1"><a href="#">Leer</a></th>
                <th class="col-xs-1"><a href="#">Insertar</a></th>
                <th class="col-xs-1"><a href="#">Actualizar</a></th>
                <th class="col-xs-1"><a href="#">Eliminar</a></th>
                <th class="col-xs-1"><a href="#">Exportar</a></th>
                <th class="col-xs-1"><a href="#">Imprimir</a></th>
                <th class="col-xs-2">&nbsp;</th>
            </tr>
        </thead>
        <tbody>
          <?php 
          foreach ($results as $result) { ?>
          <tr>
            <td><?php echo $result->id ?></td>
            <td><?php echo $result->menu ?></td>
            <td><?php echo $result->grupo ?></td>
            <td><?php if($result->read==1){?><i class="fa fa-check"></i><?php } else {?><i class="fa fa-times"></i><?php }?></td>
            <td><?php if($result->insert==1){?><i class="fa fa-check"></i><?php } else {?><i class="fa fa-times"></i><?php }?></td>
            <td><?php if($result->update==1){?><i class="fa fa-check"></i><?php } else {?><i class="fa fa-times"></i><?php }?></td>
            <td><?php if($result->delete==1){?><i class="fa fa-check"></i><?php } else {?><i class="fa fa-times"></i><?php }?></td>
            <td><?php if($result->exportar==1){?><i class="fa fa-check"></i><?php } else {?><i class="fa fa-times"></i><?php }?></td>
            <td><?php if($result->imprimir==1){?><i class="fa fa-check"></i><?php } else {?><i class="fa fa-times"></i><?php }?></td>
            <td>
                <div class="btn-group">
                    <a data-toggle="modal" href="<?php echo base_url().'backend/permisos/view/'.$result->id ?>" data-target="#myModal" class="btn btn-info"><i class="fa fa-search"></i></a>
                    <?php if($permisos_efectivos->update==1) { ?><a href="<?php echo base_url().'backend/permisos/edit/'.$result->id ?>" class="btn btn-success"><i class="fa fa-edit"></i></a><?php } ?>
                    <?php if($permisos_efectivos->delete==1) { ?><a onClick="eleminarRegistro('<?php echo base_url().'backend/permisos/delete/'.$result->id ?>')" href="#" class="btn btn-danger"><i class="fa fa-trash-o"></i></a><?php } ?>
                </div>
            </td>
          </tr>
          <?php } ?>
        </tbody>
    </table>
  </div>
</div>