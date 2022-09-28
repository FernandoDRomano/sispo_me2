<div class="panel blank-panel">
      <div class="panel-heading">
        <div class="panel-options">
          <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#precios-lista">Precios de Lista</a></li>
            <li><a href="<?=base_url('precios/actualizacion_precios_especiales')?>">Precios Especiales</a></li>
          </ul>
        </div>
      </div>
<div class="panel-body">
    <div class="tab-content">
      <div id="precios-lista" class="tab-pane fade in active">
        <div class="col-xs-12" style="background: white;">
            <div class="ibox-content">
                <div class="text-right">
                    <?php if($permisos_efectivos->insert==1) { ?> <a href="<?php echo base_url().'precios/actualizacion_precios/add/' ?>" class="btn btn-success"><i class="fa fa-plus"></i> Actualizar precios</a><?php } ?><hr>
                </div>
                <table id="resultado1" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
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
                                <b>Creacion:</b> <?php echo $result->c_apellido ?>, <?php echo $result->c_nombre ?><br>
                                <b>Aprobacion:</b> <?php echo $result->a_apellido ?>, <?php echo $result->a_nombre ?>
                            </td>
                            <td>
                                <b>Creacion:</b> <?php echo $result->fecha_creacion_format ?><br>
                                <b>Aprobacion:</b> <?php echo $result->fecha_aprobacion_format ?>
                            </td>
                            <td>
                              <div class="btn-group">
                                <?php if($permisos_efectivos->update==1) { ?><a href="<?php echo base_url().'precios/actualizacion_precios/edit/'.$result->id ?>" class="btn btn-success"><i class="fa fa-edit"></i></a><?php } ?>
                              </div>
                            </td>
                          </tr>
                      <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
      </div>
        <div id="precios-especiales" class="tab-pane fade">
            <div class="col-xs-12" style="background: white;">
                <div class="ibox-content">
                    <?php include (dirname(__FILE__) .'../../actualizacion_precios_especiales/actualizacion_precios_especiales_list.php');?>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
     $('#resultado1').DataTable( {
        order: [[ 2, "desc" ]]
    } );
</script>