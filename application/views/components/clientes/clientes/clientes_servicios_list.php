<?php

?>
  <div id="div_resultado" class="ibox-content">
    <table id="resultado" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
      <thead>
        <tr>
          <th>Código</th>
          <th>Nombre</th>
          <th>Precio</th>
          <th>días</th>
          <th class="col-xs-2 no-sort">&nbsp;</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($servicios as $servicio):?>
          <tr>
            <td><a rel="tooltip" data-animation="false" data-original-title="<h2><span class='barcode'><?=$servicio->codigo?></span><br><?=$servicio->codigo?></h2>"><?=$servicio->codigo?></a></td>
            <td>
              <?php echo $servicio->nombre?>
            </td>
            <td>
                <span style="float: left">$</span> <span style="float: right"><?php echo $servicio->precio ?></span>
            </td>
            <td align="right">
              <?php echo $servicio->dias?>
            </td>
            <td>
              <div class="btn-group">
              <a href="<?=base_url('clientes/clientes/deleteServicioAsociado/'.$servicio->cliente_id.'/'.$servicio->id.'/servicios-asociados')?>" class="btn btn-success fancybox" title="Servicios"><i class="fa fa-trash-o"></i></a>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
