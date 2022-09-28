<div class="col-xs-12">
    <div class="ibox-content">
        <div class="text-right">
            <?php if($permisos_efectivos->insert==1) { ?> <a href="<?php echo base_url().'piezas/piezas_talonarios/add/' ?>" class="btn btn-success"><i class="fa fa-plus"></i> Nuevo Talonario</a><?php } ?><hr>
        </div>
        <table id="results" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
            <thead>
                <tr>
                    <th class="col-xs-1"><a href="#">N°</a></th>
                    <th><a href="#">Responsable</a></th>
                    <th><a href="#">Etiquetas total</a></th>
                    <th><a href="#">Etiquetas disponibles</a></th>
                    <th class="col-xs-2 no-sort">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
              <?php foreach ($results as $result) { ?>
                  <tr>
                    <td><?php echo sprintf("%06d", $result->id) ?></td>
                    <td><?php echo $result->apellido.', '.$result->nombre.'<br>Sucursal: '.$result->sucursal ?></td>
                    <td><?php echo $result->etiquetas_total ?></td>
                    <td><?php echo $result->etiquetas_disponibles ?></td>
                    <td>
                      <div class="btn-group">
                        <?php if($permisos_efectivos->update==1) { ?><a href="<?php echo base_url().'piezas/piezas_talonarios/edit/'.$result->id ?>" class="btn btn-success"><i class="fa fa-edit"></i></a><?php } ?>
                        <?php if($permisos_efectivos->update==1) { ?><a href="<?php echo base_url().'piezas/piezas_talonarios/exportarAPdf/'.$result->id ?>" class="btn btn-success"><i class="fa fa-print"></i></a><?php } ?>
                       <!-- <?php //if($permisos_efectivos->delete==1) { ?><a onClick="//eleminarRegistro('<?php //echo base_url().'piezas/piezas_talonarios/delete/'.$result->id ?>')" href="#" class="btn btn-danger"><i class="fa fa-trash-o"></i></a><?php //} ?>-->
                      </div>
                    </td>
                  </tr>
              <?php } ?>
            </tbody>
        </table>
    </div>
</div>