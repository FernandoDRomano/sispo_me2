<div class="col-xs-12">
    <div class="ibox-content">
        <div class="text-right">            
            <?php if($permisos_efectivos->insert==1) { ?> <a href="<?php echo base_url().'liquidaciones_detalle/add/' ?>" class="btn btn-success"><i class="fa fa-plus"></i> Nueva Categoria</a><?php } ?><hr>
        </div>
        <table id="results" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
            <thead>
                <tr>
                    <th class="col-xs-1"><a href="#">ID</a></th>
                    <th><a href="#">Liquidacion_id</a></th>
<th><a href="#">Comprobante_ingreso</a></th>
<th><a href="#">Remito_cliente</a></th>
<th><a href="#">Fecha</a></th>
<th><a href="#">Servicio</a></th>
<th><a href="#">Cantidad</a></th>
<th><a href="#">Precio</a></th>
<th><a href="#">Create</a></th>
<th><a href="#">Update</a></th>
                    <th class="col-xs-2">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
              <?php foreach ($results as $result) { ?>
                  <tr>
                    <td><?php echo $result->id ?></td>
                    <td><?php echo $result->liquidacion_id ?></td>
<td><?php echo $result->comprobante_ingreso ?></td>
<td><?php echo $result->remito_cliente ?></td>
<td><?php echo $result->fecha ?></td>
<td><?php echo $result->servicio ?></td>
<td><?php echo $result->cantidad ?></td>
<td><?php echo $result->precio ?></td>
<td><?php echo $result->create ?></td>
<td><?php echo $result->update ?></td>
                    <td>
                      <div class="btn-group">
                        <a data-toggle="modal" href="<?php echo base_url().'liquidaciones_detalle/view/'.$result->id ?>" data-target="#myModal" class="btn btn-info"><i class="fa fa-search"></i></a>
                        <?php if($permisos_efectivos->update==1) { ?><a href="<?php echo base_url().'liquidaciones_detalle/edit/'.$result->id ?>" class="btn btn-success"><i class="fa fa-edit"></i></a><?php } ?>
                        <?php if($permisos_efectivos->delete==1) { ?><a onClick="eleminarRegistro('<?php echo base_url().'liquidaciones_detalle/delete/'.$result->id ?>')" href="#" class="btn btn-danger"><i class="fa fa-trash-o"></i></a><?php } ?>
                      </div>
                    </td>
                  </tr>
              <?php } ?>
            </tbody>
        </table>
    </div>
</div>