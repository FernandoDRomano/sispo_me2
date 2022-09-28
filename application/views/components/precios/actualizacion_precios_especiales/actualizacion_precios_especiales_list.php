<div class="panel blank-panel">
      <div class="panel-heading">
        <div class="panel-options">
          <ul class="nav nav-tabs">
            <li><a href="<?=base_url('precios/actualizacion_precios')?>">Precios de Lista</a></li>
            <li class="active"><a data-toggle="tab" href="#precios-especiales">Precios Especiales</a></li>
          </ul>
        </div>
      </div>
    <div class="panel-body">
        <div class="tab-content">
          <div id="precios-especiales" class="tab-pane fade in active">
            <div class="col-xs-12">
                <div class="ibox-content">
                    <?php if ($actualizaciones_caducas != null){?>
                    <div class="col-xs-6 alert alert-danger fade in" style="float: left">!Existen actualizaciones que caducaron</div>
                    <?php }?>
                    <div class="text-right">            
                        <?php if($permisos_efectivos->insert==1) { ?> <a href="<?php echo base_url().'precios/actualizacion_precios_especiales/add/' ?>" class="btn btn-success" <?php if ($actualizaciones_caducas != null) echo 'disabled' ;?>><i class="fa fa-plus"></i> Actualizar precios especiales</a><?php } ?><hr>
                    </div>
                    <table id="resultado" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
                        <thead>
                            <tr>
                                <th class="col-xs-1"><a href="#">ID</a></th>
                                <th><a href="#">Usuarios</a></th>
                                <th><a href="#">Fechas</a></th>
                                <th class="col-xs-1 no-sort">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                          <?php foreach ($results as $result) { ?>
                              <tr>
                                <td><?php echo $result->id ?></td>
                                <td>
                                    <b>Creación:</b> <?php echo $result->c_apellido ?>, <?php echo $result->c_nombre ?><br>
                                    <?php if($result->fecha_aprobacion != NULL){?>
                                        <b>Aprobación:</b> <?php echo $result->a_apellido ?>, <?php echo $result->a_nombre ?>  
                                    <?php }?>
                                    <?php if($result->fecha_rechazo != NULL){?>
                                        <b>Rechazo:</b> <?php  echo $result->r_apellido ?>, <?php echo $result->r_nombre ?> 
                                    <?php }?>
                                </td>
                                <td>
                                    <b>Creación:</b> <?php echo $result->fecha_creacion_format ?><br>
                                    <?php if($result->fecha_aprobacion != NULL){?>
                                        <b>Aprobación:</b> <?php  echo $result->fecha_aprobacion_format ?>
                                    <?php }?>
                                    <?php if($result->fecha_rechazo != NULL){?>
                                        <b>Rechazo:</b> <?php  echo $result->fecha_rechazo_format ?>
                                    <?php }?>
                                </td>
                                <td>
                                  <div class="btn-group">
                                    <?php if($permisos_efectivos->update==1) { ?><a href="<?php echo base_url().'precios/actualizacion_precios_especiales/edit/'.$result->id ?>" class="btn btn-success"><i class="fa fa-edit"></i></a><?php } ?>
                                  </div>
                                </td>
                              </tr>
                          <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
          </div>
        </div>
    </div>
</div>
<script type="text/javascript">
     $('#resultado').DataTable( {
        order: [[ 2, "desc" ]]
    } );
</script>