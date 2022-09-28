<div class="col-xs-12">
  <div class="ibox-content">
    <div class="panel blank-panel">
      <div class="panel-heading">
        <div class="panel-options">
          <ul class="nav nav-tabs">
            <li><a href="<?=base_url('piezas/recepcion')?>">Lista de Despachos</a></li>
            <?php if($this->is_admin()): ?>
              <li><a href="<?=base_url('piezas/recepcion/archivados')?>">Despachos Archivados</a></li>
            <?php endif; ?>
            <li class="active"><a data-toggle="tab" href="#tab-1">Verificar Piezas</a></li>
          </ul>
        </div>
      </div>
      <div class="panel-body">
        <div class="tab-content">
          <div id="tab-1" class="tab-pane active">
            <div class="well">
              <div class="row">
                <div class="col-xs-6"><b>ID Despacho</b>: <?=$despacho->id?></div>
                <div class="col-xs-6"><b>Estado</b>: <span data-field="despacho_estado"><?=$despacho->estadoNombre?></span></div>
                <div class="col-xs-6"><b>Sucursal Origen</b>: <?=$despacho->sucursalOrigen->nombre?></div>
                <div class="col-xs-6"><b>Sucursal Destino</b>: <?=$despacho->sucursalDestino->nombre?></div>
                <div class="col-xs-6"><b>Fecha Envio</b>: <?=$despacho->fecha_envio->format('d-m-Y')?></div>
                <div class="col-xs-6"><b>Fecha Recepcion</b>: <?=$despacho->fecha_recepcion->format('d-m-Y')?></div>
              </div>
            </div>
            <div>
              <div class="form-group">
                <label for="fecha_recepcion">Verificar Pieza por Codigo Barra</label>
                <input id="codigo_barra" type="text" class="form-control" placeholder="Codigo barra" autocomplete="off" onchange="flash.despachos.checkear()"/>
              </div>
              <div id="error_estado" class="alert alert-danger" style="display:none">
                <strong>Error!</strong>
                La pieza ya se encuentra verificada
              </div>
              <div id="error_barcode" class="alert alert-danger" style="display:none">
                <strong>Error!</strong>
                No se encuentra el codigo de barras ingresado en este despacho
              </div>
            </div>
            <hr/>
            <table class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered" id="results">
              <thead>
                <tr>
                  <th class="col-xs-2">Codigo</th>
                  <th class="col-xs-2">Codigo Externo</th>
                  <th class="col-xs-3">Descripción</th>
                  <th class="col-xs-1">Tipo</th>
                  <th class="col-xs-2">Estado</th>
                  <th class="col-xs-1 no-sort">&nbsp;</th>
                </tr>
              </thead>
              <tbody id="body-grilla">
                <?php foreach ($despacho->piezas as $pieza): ?>
                  <tr id="row-pieza-<?=$pieza->id?>" class="<?=$pieza->estado_id == Pieza::ESTADO_EN_GESTION?'info':''?>">
                    <td><a rel="tooltip" data-animation="false" data-original-title="<h2><span class='barcode'><?=$pieza->barcode?></span><br><?=$pieza->barcode?></h2>"><?=$pieza->barcode?></a></td>
                    <td><a rel="tooltip" data-animation="false" data-original-title="<h2><span class='barcode'><?=$pieza->barcode_externo?></span><br><?=$pieza->barcode_externo?></s|>"><?=$pieza->barcode_externo?></a></td>
                    <td><?=$pieza->descripcion?></td>
                    <td><?=$pieza->tipo->nombre?></td>
                    <td data-field="estado"><?=$pieza->estado->nombre?></td>
                    <td>
                      <div class="btn-group">
                        <a href="javascript:;" onclick="flash.despachos.verificar(<?=$pieza->id?>)" class="btn btn-success btn-verificar" title="Verificar Pieza" <?=$pieza->estado_id == Pieza::ESTADO_EN_GESTION?'disabled':''?>><i class="fa fa-check-square-o"></i></a>
                      </div>
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

<script type="text/javascript">
<?php include 'script.js'; ?>
</script>