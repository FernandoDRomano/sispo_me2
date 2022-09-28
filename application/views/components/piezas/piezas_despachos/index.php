<div class="col-xs-12">
  <div class="ibox-content">
    <div class="panel blank-panel">
      <div class="panel-heading">
        <div class="panel-options">
          <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#tab-2">Lista de Despachos</a></li>
            <?php if($this->is_admin()): ?>
              <li><a href="<?=base_url('piezas/despachos/archivados')?>">Despachos Archivados</a></li>
            <?php endif; ?>
            <li><a href="<?=base_url('piezas/despachos/nuevo')?>">Nuevo Despacho</a></li>
            <li><a href="<?=base_url('piezas/despachos/nuevoMasivo')?>">Nuevo Despacho Masivo</a></li>
          </ul>
        </div>
      </div>
      <div class="panel-body">
        <?php if($this->session->flashdata('registro')): ?>
          <div class="row">
            <div class="col-md-12">
              <div class="alert alert-success" style="margin: 10px 20px 30px 20px;">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                El despacho <b><span class="barcodear"><?php echo $this->session->flashdata('registro') ?></span></b> fue guardado con exito.
              </div>
            </div>
          </div>
        <?php endif; ?>
        <div class="tab-content">
          <div id="tab-2" class="tab-pane active">
            <table class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered" id="results">
              <thead>
                <tr>
                  <th class="col-xs-1">Codigo</th>
                  <th class="col-xs-2">Origen</th>
                  <th class="col-xs-2">Destino</th>
                  <th class="col-xs-2">Fecha</th>
                  <th class="col-xs-1">Estado</th>
                  <th class="col-xs-1 no-sort">Cantidad</th>
                  <th class="col-xs-3 no-sort">&nbsp;</th>
                </tr>
              </thead>
              <tbody id="body-grilla">
                <?php foreach ($despachos as $despacho): 
                    $query_total = " SELECT COUNT(*) cantidad FROM flash_piezas_despacho_piezas WHERE despacho_id = $despacho->id ";
                    $exe_total = $this->db->query($query_total)->row();
                ?>
                  <tr id="row-despacho-<?=$despacho->id?>">
                    <td><a class="link-editar" <?=$despacho->estado == Despacho::ESTADO_CANCELADO?'':'href="'.base_url('piezas/despachos/editar/'.$despacho->id).'"'?> rel="tooltip" data-animation="false" data-original-title="<h1><span class='barcode'><?=$despacho->barcode?></span><br><?=$despacho->barcode?></h1>"><?=$despacho->barcode?></a></td>
                    <td><?=isset($despacho->sucursalOrigen->nombre)?$despacho->sucursalOrigen->nombre:""?></td>
                    <td><?=isset($despacho->sucursalDestino->nombre)?$despacho->sucursalDestino->nombre:""?></td>
                    <td><?=$despacho->create->format('d-m-Y')?></td>
                    <td data-field="estado"><?=$despacho->estadoNombre?></td>
                    <td><?php echo $exe_total->cantidad ?></td>
                    <td>
                      <div class="btn-group">
                        <a href="<?=base_url('piezas/despachos/editar/'.$despacho->id)?>" class="btn btn-success btn-editar" title="Editar Despacho" <?=$despacho->estado == Despacho::ESTADO_CANCELADO?'disabled':''?>><i class="fa fa-edit"></i></a>
                        <a href="javascript:;" onclick="flash.despachos.enviar(<?=$despacho->id?>)" class="btn btn-success btn-enviar" title="Enviar Despacho" <?=$despacho->estado == Despacho::ESTADO_CANCELADO?'disabled':''?>><i class="fa fa-truck"></i></a>
                        <a href="javascript:;" onclick="flash.despachos.borrarDespacho(<?=$despacho->id?>)" class="btn btn-danger btn-cancelar" title="Cancelar Despacho" <?=$despacho->estado == Despacho::ESTADO_CANCELADO?'disabled':''?>><i class="fa fa-times"></i></a>
                        <a href="javascript:;" onclick="flash.despachos.archivar(<?=$despacho->id?>)" class="btn btn-info btn-archivar" title="Archivar Despacho" <?=$despacho->estado == Despacho::ESTADO_INICIADO?'disabled':''?>><i class="fa fa-save"></i></a>
                        <a href="javascript:;" onclick="flash.despachos.imprimir(<?=$despacho->id?>)" class="btn btn-info btn-imprimir" title="Imprimir Despacho"><i class="fa fa-print"></i></a>
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
<form method='post' action='<?php echo base_url('piezas/despachos/exportarAPdf')?>' id='exportAPdf'>
    <input type='hidden' id='pdf_despacho_id' name='pdf_despacho_id' value="">
</form>
<form method='post' action='<?php echo base_url('piezas/despachos/borrarDespacho')?>' id='borrarDespacho'>
    <input type='hidden' id='despacho_delete_id' name='despacho_delete_id' value="">
</form> 
<script type="text/javascript">
  <?php include 'script_index.js'; ?>
</script>
