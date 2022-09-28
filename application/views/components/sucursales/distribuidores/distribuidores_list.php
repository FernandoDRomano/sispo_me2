<div class="col-xs-12">
    <div class="ibox-content">
        <div class="text-right">            
            <?php if($permisos_efectivos->insert==1) { ?> <a href="<?php echo base_url().'sucursales/distribuidores/add/' ?>" class="btn btn-success"><i class="fa fa-plus"></i> Nuevo Distribuidor</a><?php } ?><hr>
        </div>
        <table id="results" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
            <thead>
                <tr>
                    <th><a href="#">Distribuidor</a></th>
                    <th><a href="#">Datos</a></th>
                    <th class="col-xs-2">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
              <?php foreach ($results as $result) { ?>
                  <tr>
                    <td>
                      <?php echo $result->apellido_nombre ?><br>
                      Estado: <?php if($result->activo == 1) {echo 'Activo';}else{echo 'Inactivo';} ?>
                    </td>
                    <td>
                      <i class="fa fa-map-marker"></i> <?php echo $result->domicilio ?> (CP: <?php echo $result->codigo_postal ?>)<br>
                      <i class="fa fa-globe"></i> <?php echo $result->localidad ?><br>
                      <i class="fa fa-phone-square"></i> <?php echo str_replace(",", "<br><i class='fa fa-phone-square'></i> ", $result->telefonos); ?>
                    </td>
                    <td>
                      <div class="btn-group">
                        <?php if($permisos_efectivos->update==1) { ?><a href="<?php echo base_url().'sucursales/distribuidores/edit/'.$result->id ?>" class="btn btn-success"><i class="fa fa-edit"></i></a><?php } ?>
                        <?php if($permisos_efectivos->delete==1) { ?><a onClick="eleminarRegistro('<?php echo base_url().'sucursales/distribuidores/delete/'.$result->id ?>')" href="#" class="btn btn-danger"><i class="fa fa-trash-o"></i></a><?php } ?>
                      </div>
                    </td>
                  </tr>
              <?php } ?>
            </tbody>
        </table>
    </div>
</div>