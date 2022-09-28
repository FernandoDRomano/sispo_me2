<div class="col-xs-12">
  <div class="ibox-content">
    <div class="panel blank-panel">
      <div class="panel-heading">
        <div class="panel-options">
          <ul class="nav nav-tabs">
            <li><a href="<?=base_url('hojas_rutas/hojas_rutas')?>">Hojas de Ruta</a></li>
            <?php if($this->is_admin()): ?>
              <li class="active"><a data-toggle="tab" href="#tab-1">Hojas de Ruta Archivadas</a></li>
            <?php endif; ?>
            <li><a href="<?=base_url('hojas_rutas/hojas_rutas_alta')?>">Nueva Hoja de Ruta</a></li>
          </ul>
        </div>
      </div>
      <div class="panel-body">
        <div class="tab-content">
          <div id="tab-1" class="tab-pane active">
              <form action="<?=base_url('hojas_rutas/hojas_rutas/filtro_archivadas')?>" method="post" id="form-filtro">
                <div class="row">
                    <div class="col-xs-3">
                        <div class="form-group">
                          <label for="sucursal">Sucursal</label>
                          <select id="sucursal_id" name="sucursal_id" class="form-control filtro-group">
                            <?php foreach ($sucursales as $sucursal): ?>
                              <option value="<?=$sucursal->id ?>"<?=$sucursal_id == $sucursal->id ? 'selected="selected"' : ''?>><?=$sucursal->nombre ?></option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                    </div>
                    <div class="col-xs-3" style="padding-top:23px">
                      <div class="btn-group">
                        <button type="submit" class="btn btn-success" title="Filtrar"><i class="fa fa-filter"></i></button>
                        <a href="<?=base_url('hojas_rutas/hojas_rutas')?>" class="btn btn-primary" title="Reinciar"><i class="fa fa-refresh"></i></a>
                      </div>
                    </div>
                </div>
            </form>
            <table id="results" class="table table-striped table-hover table-condensed table-bordered bootstrap-datatable">
              <thead>
                <tr>
                  <th class="col-xs-1">ID</th>
                  <th class="col-xs-7">Asignado</th>
                  <th class="col-xs-3">Fecha</th>
                  <th class="col-xs-1 no-sort"></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($hojas as $hoja): ?>
                  <tr id="row-hdr-<?=$hoja->id?>">
                    <td class="barcodear" data-href="<?=base_url('hojas_rutas/hojas_rutas_alta/hoja/'.$hoja->id)?>"><?=$hoja->barcode?></td>
                    <td>
                      <?php if($hoja->cartero): ?>
                        <b>Cartero:</b> <?=$hoja->cartero ? $hoja->cartero->apellido_nombre : "" ?><br>
                        <b>Zona:</b> <?=$hoja->zona ? $hoja->zona->nombre : "" ?>
                      <?php else: ?>
                        <b>Distribuidor:</b> <?=$hoja->distribuidor ? $hoja->distribuidor->apellido_nombre : "" ?><br>
                        <b>Transporte:</b> <?=$hoja->transporte ? $hoja->transporte->nombre : "" ?>
                      <?php endif; ?>
                    </td>
                    <td><?=$hoja->fecha_entrega->format('d-m-Y') ?></td>
                    <td>
                      <div class="btn-group">
                        <a href="<?=base_url('hojas_rutas/hojas_rutas_alta/hoja/'.$hoja->id)?>" class="btn btn-success btn-ver" title="Ver Hoja de Ruta"><i class="fa fa-search"></i></a>
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