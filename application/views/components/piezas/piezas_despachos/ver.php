<div class="col-xs-12">
  <div class="ibox-content">
    <div class="panel blank-panel">
      <div class="panel-heading">
        <div class="panel-options">
          <ul class="nav nav-tabs">
            <li><a href="<?=base_url('piezas/despachos')?>">Lista de Despachos</a></li>
            <?php if($this->is_admin()): ?>
              <li><a href="<?=base_url('piezas/despachos/archivados')?>">Despachos Archivados</a></li>
            <?php endif; ?>
            <li class="active"><a data-toggle="tab" href="#tab-1">Archivo</a></li>
            <li><a href="<?=base_url('piezas/despachos/nuevo')?>">Nuevo Despacho</a></li>
          </ul>
        </div>
      </div>
      <div class="panel-body">
        <div class="tab-content">
          <div id="tab-1" class="tab-pane active">
            <div class="well">
              <div class="row">
                <div class="col-xs-6"><b>Codigo</b>: <span class="barcodear"><?=$despacho->barcode?></div>
                <div class="col-xs-6"><b>Estado</b>: <span data-field="despacho_estado"><?=$despacho->estadoNombre?></span></div>
                <div class="col-xs-6"><b>Sucursal Origen</b>: <?=$despacho->sucursalOrigen->nombre?></div>
                <div class="col-xs-6"><b>Sucursal Destino</b>: <?=$despacho->sucursalDestino->nombre?></div>
                <div class="col-xs-6"><b>Fecha Envio</b>: <?=$despacho->fecha_envio->format('d-m-Y')?></div>
                <div class="col-xs-6"><b>Fecha Recepcion</b>: <?=($despacho->fecha_recepcion!=NULL?$despacho->fecha_recepcion->format('d-m-Y'):'')?></div>
              </div>
            </div>
            <hr/>
            <table class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered" id="results">
              <thead>
                <tr>
                  <th class="col-xs-2">Codigo</th>
                  <th class="col-xs-2">Codigo Externo</th>
                  <th class="col-xs-3">Descripci¨®n</th>
                  <th class="col-xs-2">Tipo</th>
                  <th class="col-xs-2">Estado</th>
                </tr>
              </thead>
              <tbody id="body-grilla">
                <?php foreach ($despacho->piezas as $pieza): ?>
                  <tr>
                    <td class="barcodear"><?=$pieza->barcode?></td>
                    <td class="barcodear"><?=$pieza->barcode_externo?></td>
                    <td><?=$pieza->descripcion?></td>
                    <td><?=$pieza->tipo?></td>
                    <td data-field="estado"><?=$pieza->estado?></td>
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
