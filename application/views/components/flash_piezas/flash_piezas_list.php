<div class="col-xs-12">
    <div class="ibox-content">
        <div class="text-right">            
            <?php if($permisos_efectivos->insert==1) { ?> <a href="<?php echo base_url().'flash_piezas/add/' ?>" class="btn btn-success"><i class="fa fa-plus"></i> Nueva Categoria</a><?php } ?><hr>
        </div>
        <table id="results" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
            <thead>
                <tr>
                    <th class="col-xs-1"><a href="#">ID</a></th>
                    <th><a href="#">Usuario_id</a></th>
<th><a href="#">Servicio_id</a></th>
<th><a href="#">Tipo_id</a></th>
<th><a href="#">Sucursal_id</a></th>
<th><a href="#">Estado_id</a></th>
<th><a href="#">Comprobante_ingreso_id</a></th>
<th><a href="#">Cantidad</a></th>
<th><a href="#">Barcode</a></th>
<th><a href="#">Barcode_externo</a></th>
<th><a href="#">Destinatario</a></th>
<th><a href="#">Domicilio</a></th>
<th><a href="#">Codigo_postal</a></th>
<th><a href="#">Localidad</a></th>
<th><a href="#">Vista</a></th>
<th><a href="#">Recibio</a></th>
<th><a href="#">Documento</a></th>
<th><a href="#">Vinculo</a></th>
<th><a href="#">Datos_varios</a></th>
<th><a href="#">Datos_varios_1</a></th>
<th><a href="#">Datos_varios_2</a></th>
<th><a href="#">Verifico_id</a></th>
<th><a href="#">Rendicion_id</a></th>
<th><a href="#">Create</a></th>
<th><a href="#">Update</a></th>
                    <th class="col-xs-2">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
              <?php foreach ($results as $result) { ?>
                  <tr>
                    <td><?php echo $result->id ?></td>
                    <td><?php echo $result->usuario_id ?></td>
<td><?php echo $result->servicio_id ?></td>
<td><?php echo $result->tipo_id ?></td>
<td><?php echo $result->sucursal_id ?></td>
<td><?php echo $result->estado_id ?></td>
<td><?php echo $result->comprobante_ingreso_id ?></td>
<td><?php echo $result->cantidad ?></td>
<td><?php echo $result->barcode ?></td>
<td><?php echo $result->barcode_externo ?></td>
<td><?php echo $result->destinatario ?></td>
<td><?php echo $result->domicilio ?></td>
<td><?php echo $result->codigo_postal ?></td>
<td><?php echo $result->localidad ?></td>
<td><?php echo $result->vista ?></td>
<td><?php echo $result->recibio ?></td>
<td><?php echo $result->documento ?></td>
<td><?php echo $result->vinculo ?></td>
<td><?php echo $result->datos_varios ?></td>
<td><?php echo $result->datos_varios_1 ?></td>
<td><?php echo $result->datos_varios_2 ?></td>
<td><?php echo $result->verifico_id ?></td>
<td><?php echo $result->rendicion_id ?></td>
<td><?php echo $result->create ?></td>
<td><?php echo $result->update ?></td>
                    <td>
                      <div class="btn-group">
                        <a data-toggle="modal" href="<?php echo base_url().'flash_piezas/view/'.$result->id ?>" data-target="#myModal" class="btn btn-info"><i class="fa fa-search"></i></a>
                        <?php if($permisos_efectivos->update==1) { ?><a href="<?php echo base_url().'flash_piezas/edit/'.$result->id ?>" class="btn btn-success"><i class="fa fa-edit"></i></a><?php } ?>
                        <?php if($permisos_efectivos->delete==1) { ?><a onClick="eleminarRegistro('<?php echo base_url().'flash_piezas/delete/'.$result->id ?>')" href="#" class="btn btn-danger"><i class="fa fa-trash-o"></i></a><?php } ?>
                      </div>
                    </td>
                  </tr>
              <?php } ?>
            </tbody>
        </table>
    </div>
</div>