<div class="col-xs-12">
    <div class="ibox-content">
        <div class="text-right">            
            <?php if($permisos_efectivos->insert==1) { ?> <a href="<?php echo base_url().'liquidaciones/liquidaciones_carteros/add/' ?>" class="btn btn-success"><i class="fa fa-plus"></i> Nueva Liquidacion</a><?php } ?><hr>
        </div>
        <form action="<?=base_url('liquidaciones/liquidaciones_carteros/filtro')?>" method="post" id="form-filtro">
            <div class="row">
              <div class="col-xs-2">
                <div class="form-group">
                  <label for="empresa">Nro. Liquidación</label>
                  <input type="text" class="form-control" id="liquidacion_cartero_id" name="liquidacion_cartero_id"/>
                </div>
              </div>
              <div class="col-xs-3" style="padding-top:23px">
                <div class="btn-group">
                  <button type="submit" class="btn btn-success" title="Filtrar"><i class="fa fa-filter"></i></button>
                  <a href="<?=base_url('liquidaciones/liquidaciones_carteros')?>" class="btn btn-primary" title="Reinciar"><i class="fa fa-refresh"></i></a>
                </div>
              </div>
            </div>
          </form>
        <table id="results" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
            <thead>
                <tr>
                    <th class="col-xs-1"><a href="#">ID</a></th>
                    <th><a href="#">Sucursal_id</a></th>
                    <th><a href="#">Periodo_desde</a></th>
                    <th><a href="#">Periodo_hasta</a></th>
                    <th><a href="#">Create</a></th>
                    <th><a href="#">Update</a></th>
                    <th class="col-xs-2">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
              <?php foreach ($results as $result) { ?>
                  <tr>
                    <td><?php echo $result->id ?></td>
                    <td><?php echo $result->sucursal_id ?></td>
                    <td><?php echo $result->periodo_desde ?></td>
                    <td><?php echo $result->periodo_hasta ?></td>
                    <td><?php $f = strtotime($result->create);echo $result->create!=''? date('d-m-Y',$f ):''; ?></td>
                    <td><?php $f = strtotime($result->update);echo $result->update!=''? date('d-m-Y',$f ):''; ?></td>
                    <td>
                      <div class="btn-group">
                        <?php if($permisos_efectivos->delete==1) { ?><a href="<?php echo base_url().'liquidaciones/liquidaciones_carteros/exportarAExcel/'.$result->id?>" class="btn btn-info"><i class="fa fa-file-o"></i></a><?php } ?>
                        <?php if($permisos_efectivos->delete==1) { ?><a onClick="eleminarRegistro('<?php echo base_url().'liquidaciones/liquidaciones_carteros/delete/'.$result->id ?>')" href="#" class="btn btn-danger"><i class="fa fa-trash-o"></i></a><?php } ?>
                      </div>
                    </td>
                  </tr>
              <?php } ?>
            </tbody>
        </table>
    </div>
</div>