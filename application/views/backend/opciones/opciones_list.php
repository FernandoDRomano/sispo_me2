<div class="col-xs-12">
  <div class="ibox-content">
    <div class="text-right">
        <?php if($permisos_efectivos->insert==1) { ?> <a href="<?php echo base_url().'backend/opciones/add/' ?>" class="btn btn-primary"><i class="fa fa-plus"></i> Nueva Opcion</a><?php } ?><hr>
    </div>
    <table id="results" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
        <thead>
            <tr>
                <th class="col-xs-1"><a href="#">ID</a></th>
                <th><a href="#">nombre</a></th>
                <th><a href="#">descripcion</a></th>
                <th class="col-xs-2">&nbsp;</th>
            </tr>
        </thead>
        <tbody>
          <?php 
          foreach ($results as $result) { ?>
          <tr>
            <td><?php echo $result->id ?></td>
            <td><?php echo $result->nombre ?></td>
            <td><?php echo $result->descripcion ?></td>
            <td>
              <div class="btn-group">
                <a href="<?php echo base_url().'backend/opciones/opcion/'.$result->id ?>" class="btn btn-default"><i class="fa fa-cogs"></i></a>
                <a data-toggle="modal" href="<?php echo base_url().'backend/opciones/view/'.$result->id ?>" data-target="#myModal" class="btn btn-info"><i class="fa fa-search"></i></a>
                <?php if($permisos_efectivos->update==1) { ?><a href="<?php echo base_url().'backend/opciones/edit/'.$result->id ?>" class="btn btn-success"><i class="fa fa-edit"></i></a><?php } ?>            
              </div>
            </td>
          </tr>
          <?php } ?>
        </tbody>
    </table>
  </div>
</div>