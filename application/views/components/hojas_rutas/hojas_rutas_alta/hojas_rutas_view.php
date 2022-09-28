<div class="col-xs-12">
  <div class="ibox-content">
    <div class="panel blank-panel">
      <div class="panel-heading">
        <div class="panel-options">
          <ul class="nav nav-tabs">
            <li><a href="<?=base_url('hojas_rutas/hojas_rutas')?>">Hojas de Ruta</a></li>
            <?php if($this->is_admin()): ?>
              <li><a href="<?=base_url('hojas_rutas/hojas_rutas/archivadas')?>">Hojas de Ruta Archivadas</a></li>
            <?php endif; ?>
            <li><a href="<?=base_url('hojas_rutas/hojas_rutas_alta')?>">Nueva Hoja de Ruta</a></li>
            <li class="active"><a data-toggle="tab" href="#tab-1">Ver Hoja de Ruta</a></li>
          </ul>
        </div>
      </div>
      <div class="panel-body">
        <div class="tab-content">
          <div id="tab-1" class="tab-pane active">
            <div class="row">
              <div class="col-xs-4">
                <div class="form-group">
                  <label for="fecha_envio">Codigo de Barra</label><br>
                  <div>
                    <div class="barcode barcode-bar"><?=$hoja->barcode?></div>
                    <div class="barcode-code"><?=$hoja->barcode?></div>
                  </div>
                </div>
              </div>
              <div class="col-xs-8">
                <?php if($hoja->cartero_id): ?>
                  <div class="row">
                    <div class="col-xs-6">
                      <b>Cartero:</b> <?=$hoja->cartero->apellido_nombre?>
                    </div>
                    <div class="col-xs-6">
                      <b>Zona:</b> <?=$hoja->zona->nombre?>
                    </div>
                  </div>
                <?php endif; ?>
                <?php if($hoja->distribuidor_id): ?>
                  <div class="row">
                    <div class="col-xs-6">
                      <b>Distribuidor:</b> <?=$hoja->distribuidor->apellido_nombre?>
                    </div>
                    <div class="col-xs-6">
                      <b>Transporte:</b> <?=$hoja->transporte->nombre?>
                    </div>
                  </div>
                <?php endif; ?>
                <br>
                <div class="row">
                  <div class="col-xs-6">
                    <b>Fecha entrega:</b> <?=$hoja->fecha_entrega->format('d-m-Y')?>
                  </div>
                  <div class="col-xs-6">
                    <b>Estado:</b>
                    <span data-hdr-field="estado"><?=$hoja->estado_nombre?></span>
                    <span data-hdr-field="fecha-baja"><?=$hoja->fecha_baja?$hoja->fecha_baja->format('d-m-Y'):''?></span>
                  </div>
                </div>
              </div>
            </div>
            <br>
            <?php if($hoja->observaciones): ?>
              <div class="row">
                <div class="col-xs-12">
                  <b>Observaciones</b><br>
                  <?=$hoja->observaciones?>
                </div>
              </div>
            <?php endif; ?>

            <hr>

            <?php if($hoja->estado == Hoja::ESTADO_CERRADA): ?>
              <div class="row">
                <div class="col-xs-12">
                  <div class="well" id="baja-boton">
                    <p><a href="javascript:;" id="btn-baja" onclick="flash.hdr.baja(<?=$hoja->id?>)" class="btn btn-warning" title="Dar de baja Hoja de Ruta" disabled="disabled"><i class="fa fa-check-square-o"></i> Dar de Baja</a></p>
                    <p>Para dar de baja una hoja de ruta todas las piezas normales deben estar
                    en estados de rendiciones o restauradas para poder ser asignadas a una nueva
                    hoja de ruta. Para las piezas simples no es necesario dar novedad.</p>
                  </div>
                  <div id="baja-confirma" class="alert alert-info" style="display:none">
                    <p>La hoja de ruta fue dada de baja con exito.</p>
                  </div>
                </div>
              </div>
            <?php endif; ?>
	    <div class="text-right">
                <a class="btn btn-success exportarAExcel" href="javascript:;" onclick="exportarAExcel()"><i class="fa fa-arrow-down"> </i>Exportar a Excel</a>
            </div>
            <br/>
            <table class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered" id="results">
              <thead>
                <tr>
                  <th>Pieza</th>
                  <th>Tipo</th>
                  <th>Cliente</th>
                  <th>Descripcion</th>
                  <th>Visitas</th>
                  <th>Estado</th>
                  <?php /*
                  <th class="col-xs-2">Novedad</th>
                  */?>
                </tr>
              </thead>
              <tbody id="body-grilla">
                <?php foreach($piezas as $i => $pieza): ?>
                  <tr id="row_<?=$pieza->pieza_id?>">
                    <td class="barcodear"><?=$pieza->barcode?></td>
                    <td><?=$pieza->tipo?> <?=$pieza->tipo_id == Pieza::TIPO_SIMPLE?'('.$pieza->cantidad.' piezas)':''?></td>
                    <td><?=$pieza->cliente?></td>
                    <td><?=$pieza->servicio?></td>
                    <td data-field="novedades"><?php echo ($pieza->novedades != null)?$pieza->novedades->count():""?></td>
                    <td data-field="estado"><?=$pieza->estado?></td>
                <?php endforeach; ?>
              </tbody>
            </table>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<form method='post' action='<?php echo base_url('hojas_rutas/hojas_rutas_alta/exportarDetalleHDRAExcel')?>' id='exportAExcel'>
    <input type='hidden' id='hdr_id' name='hdr_id' value="<?=$hoja->id?>">
</form>
<script type="text/javascript">
  <?php include VIEWPATH . 'components/hojas_rutas/hojas_rutas/script.js'; ?>
</script>
