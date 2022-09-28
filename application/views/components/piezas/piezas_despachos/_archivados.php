<div class="col-xs-12">
  <div class="ibox-content">
    <div class="panel blank-panel">
      <div class="panel-heading">
        <div class="panel-options">
          <ul class="nav nav-tabs">
            <li><a href="<?=base_url('piezas/despachos')?>">Lista de Despachos</a></li>
            <li class="active"><a data-toggle="tab" href="#tab-1">Despachos Archivados</a></li>
            <li><a href="<?=base_url('piezas/despachos/nuevo')?>">Nuevo Despacho</a></li>
          </ul>
        </div>
      </div>
      <div class="panel-body">
        <div class="tab-content">
          <div id="tab-1" class="active tab-pane">
            <table class="table table-hover table-condensed table-bordered" id="results">
              <thead>
                <tr>
                  <th class="col-xs-3">Codigo</th>
                  <th class="col-xs-3">Origen</th>
                  <th class="col-xs-3">Destino</th>
                  <th class="col-xs-2">Fecha</th>
                  <th class="col-xs-1 no-sort"></th>
                </tr>
              </thead>
              <tbody id="body-grilla">
                <?php foreach ($despachos as $despacho): ?>
                  <tr id="row-despacho-<?=$despacho->id?>">
                    <td><a rel="tooltip" data-animation="false" data-original-title="<h2><span class='barcode'><?=$despacho->barcode?></span><br><?=$despacho->barcode?></s|>"><?=$despacho->barcode?></a></td>
                    <td><?=$despacho->sucursalOrigen->nombre?></td>
                    <td><?=$despacho->sucursalDestino->nombre?></td>
                    <td><?=$despacho->create->format('d-m-Y')?></td>
                    <td>
                      <a href="<?=base_url('piezas/despachos/ver/'.$despacho->id)?>" class="btn btn-success btn-ver" title="Ver Despacho"><i class="fa fa-search"></i></a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
