<div class="col-xs-12">
  <div class="ibox-content">
    <div class="panel blank-panel">
      <div class="panel-heading">
        <div class="panel-options">
          <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#tab-1">Lista de Despachos</a></li>
            <?php //if($this->is_admin()): ?>
              <li><a href="<?=base_url('piezas/recepcion/archivados')?>">Despachos Archivados</a></li>
            <?php// endif; ?>
          </ul>
        </div>
      </div>
      <div class="panel-body">
        <div class="tab-content">
          <div id="tab-1" class="active tab-pane">
           <!-- <div class="well">
              Para verificar piezas primero debe marcar como recibido el despacho
            </div>-->
            <table id="resultado" class="table table-hover table-condensed table-bordered" id="results">
              <thead>
                <tr>
                  <th class="col-xs-1">Codigo</th>
                  <th class="col-xs-2">Origen</th>
                  <th class="col-xs-2">Destino</th>
                  <th class="col-xs-2">Fecha Envio</th>
                  <th class="col-xs-1">Estado</th>
                  <th class="col-xs-1"  style="display:none">Create</th>
                  <th class="col-xs-3 no-sort">&nbsp;</th>
                </tr>
              </thead>
              <tbody id="body-grilla">
                <?php foreach ($despachos as $despacho): ?>
                  <tr id="row-despacho-<?=$despacho->id?>">
                    <td><a rel="tooltip" data-animation="false" data-original-title="<h2><span class='barcode'><?=$despacho->barcode?></span><br><?=$despacho->barcode?></s|>"><?=$despacho->barcode?></a></td>
                    <td><?=$despacho->sucursalOrigen->nombre?></td>
                    <td><?=$despacho->sucursalDestino->nombre?></td>
                    <td><?=$despacho->fecha_envio->format('d-m-Y')?></td>
                    <td data-field="estado"><?=$despacho->estadoNombre?></td>
                    <td style="display:none"><?=$despacho->create?></td>
                    <td>
                      <div class="btn-group">
                          <span id="loadding<?php echo $despacho->id?>" style="display:none" ><i style="font-size:24px" class="fa fa-spinner fa-spin"></i></span>
                        <a href="<?=base_url('piezas/recepcion/ver/'.$despacho->id)?>" class="btn btn-success btn-ver" title="Ver Despacho"><i class="fa fa-search"></i></a>
                        <a href="javascript:;" onclick="flash.despachos.recibir_sin_verificar(<?=$despacho->id?>)" class="btn btn-success btn-recibir" title="Recibir Despacho" <?=($despacho->estado == Despacho::ESTADO_RECIBIDO /*|| $despacho->estado == Despacho::ESTADO_VERIFICADO*/)?'disabled':''?>><i class="fa fa-truck"></i></a>
                        <!--<a href="<?//=base_url('piezas/recepcion/verificar/'.$despacho->id)?>" class="btn btn-success btn-verificar" title="Verificar Piezas" <?//=$despacho->estado == Despacho::ESTADO_VERIFICADO || $despacho->estado == Despacho::ESTADO_ENVIADO?'disabled':''?>><i class="fa fa-check-square-o"></i></a>-->
                        <a href="javascript:;" onclick="flash.despachos.archivar(<?=$despacho->id?>)" class="btn btn-info btn-archivar" title="Archivar Despacho" <?=$despacho->estado == Despacho::ESTADO_ENVIADO?'disabled':''?>><i class="fa fa-save"></i></a>
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
$('#resultado').DataTable( {
        order: [[ 5, "desc" ]]
} );
<?php include 'script.js'; ?>
</script>