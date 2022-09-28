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
            <li class="active"><a data-toggle="tab" href="#tab-1">Archivo</a></li>
          </ul>
        </div>
      </div>
      <div class="panel-body">
        <div class="tab-content">
          <div id="tab-1" class="tab-pane active">
            <div class="well">
              <div class="row">
                <div class="col-xs-6"><b>Codigo</b>: <span class="barcodear"><?=str_pad($despacho->id, 6, "0", STR_PAD_LEFT);?></div>
                <div class="col-xs-6"><b>Estado</b>: <span data-field="despacho_estado"><?=Despacho::estadoNombre($despacho->estado)?></span></div>
                <div class="col-xs-6"><b>Sucursal Origen</b>: <?=$despacho->sucursalOrigen->nombre?></div>
                <div class="col-xs-6"><b>Sucursal Destino</b>: <?=$despacho->sucursalDestino->nombre?></div>
                <div class="col-xs-6"><b>Fecha Envio</b>: <?=$despacho->fecha_envio->format('d-m-Y')?></div>
                <?php if($despacho->fecha_recepcion ): ?>
                  <div class="col-xs-6"><b>Fecha Recepcion</b>: <?=$despacho->fecha_recepcion->format('d-m-Y')?></div>
                <?php else: ?>
                  <div class="col-xs-6" >
                  
                    <?php if(isset($pieza_tipo) && $pieza_tipo == Pieza::TIPO_SIMPLE){?> 
                        <input  id="cargados" type="hidden" value="" />
                    <?php }?>
                  </div>
                <?php endif; ?>
              </div>
            </div>
            <hr/>
            <div class="row">
              <div class="col-xs-12" style="margin-bottom: 20px;">
                <label class="radio-inline"><input type="radio" name="buscar_por" value="1">Pieza ID</label>
                <label class="radio-inline"><input type="radio" name="buscar_por" value="2" checked="checked">Código Externo</label>
              </div>
              
                <span  >
                <div class="col-xs-4" >
                  <span > <b>Verificar Pieza</b>: <input id="codigo_barra" name="codigo_barra" type="text" class="form-control" placeholder="Codigo barra" autocomplete="off" />
                </div>
                </span>
                
                <span id="loadding" style="float: left;">
                <div style="margin-top: 20px;">
                  <i style="font-size:24px" class="fa fa-spinner fa-spin">
                  </i>
                  </div>
                  </span>
            </div>
            <hr/>
            <table class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered" id="results">
              <thead>
                <tr>
                  <th class="col-xs-1">Comp.Ing.</th>
                  <th class="col-xs-1">Codigo</th>
                  <th class="col-xs-2">Codigo Externo</th>
                  <th class="col-xs-3">Descripción</th>
                  <th class="col-xs-2">Tipo</th>
                  <th class="col-xs-3">Estado</th>
                  <th class="col-xs-1 no-sort">&nbsp;</th>
                </tr>
              </thead>
              <tbody id="body-grilla">
              <?php foreach ($piezas as $pieza): ?>
                  <tr id="row-pieza-<?=$pieza->pieza_id?>" class="<?php if (isset($pieza->estado_id)) { echo $pieza->estado_id == Pieza::ESTADO_EN_GESTION||$pieza->estado_id == Pieza::ESTADO_PERDIDA?'info':'';}
                                                                echo isset($pieza_tipo) && $pieza_tipo == Pieza::TIPO_SIMPLE?' simple':''?>">
                    <td><?=$pieza->comprobante_ingreso?></td>
                    <td class="barcodear"><?=$pieza->barcode?></td>
                    <td class="barcodear"><?=$pieza->barcode_externo?></td>
                    <td><?=$pieza->descripcion?></td>
                    <td><?=$pieza->tipo_id == 1?"Simple":"Normal"?></td>
                    <td data-field="estado">
                            <?=$pieza->estado?>
                            <?php if ($pieza->tipo_id == Pieza::TIPO_SIMPLE?'disabled':''):?>
                                <input class="form-control" style="width:90px" type="number" min="0" max="<?=$pieza->cantidad ?>" step="1" id="cantidad_<?=$pieza->pieza_id?>" value="<?=$pieza->cantidad ?>" <?php echo isset($pieza->estado_id) && $pieza->estado_id == Pieza::ESTADO_EN_GESTION|| isset($pieza->estado_id) && $pieza->estado_id == Pieza::ESTADO_PERDIDA? 'disabled':''?>/>
                            <?php  endif;?>
                    </td>
                    <td>
                      <div class="btn-group">
                        <?php if ($pieza->tipo_id == Pieza::TIPO_NORMAL): ?>
                           <a href="javascript:;" onclick="flash.despachos.verificar(<?=$pieza->barcode_externo?>,<?=$despacho->comprobante_ingreso_id > 0?$despacho->comprobante_ingreso_id:0?> )" class="btn btn-success btn-verificar-<?=$pieza->pieza_id?>" title="Verificar Pieza" <?= isset($pieza->verifico_id) && $pieza->verifico_id != NULL ||isset($pieza->estado_id) && $pieza->estado_id == Pieza::ESTADO_PERDIDA ?'disabled':''?>><i class="fa fa-check-square-o"></i></a>
                        <?php else:  ?>
                            <a href="javascript:;" onclick="flash.despachos.verificar(<?=$pieza->comprobante_ingreso_id?>, <?=$pieza->comprobante_ingreso_id?>, <?=$pieza->pieza_id?>, <?=$pieza->servicio_id?>)" class="btn btn-success btn-verificar" title="Verificar Pieza" <?=isset($pieza->estado_id) && $pieza->estado_id == Pieza::ESTADO_EN_GESTION||isset($pieza->estado_id) && $pieza->estado_id == Pieza::ESTADO_PERDIDA?'disabled':''?>><i class="fa fa-check-square-o"></i></a>
                        <?php endif;?>
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
$(document).ready(function() {
  
    $('#loadding').hide();
    var table = $('#results').DataTable();
    $('#codigo_barra').on( 'keyup', function (e) {
    table
        .search( this.value )
        .draw();
        if (e.which == 13) {
          setTimeout(verificar('table'),1000) ;
        }
} );

    function verificar(table) {
      $('#codigo_barra').attr('readonly', 'readonly');
      $('#loadding').show();
      flash.despachos.verificar($("#codigo_barra").val(),0,null,null);
    };

  });
</script>