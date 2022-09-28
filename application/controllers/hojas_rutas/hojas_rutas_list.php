<div class="col-xs-12">
  <div class="ibox-content">
    <div class="panel blank-panel">
      <div class="panel-heading">
        <div class="panel-options">
          <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#tab-1">Hojas de Ruta</a></li>
            <?php if($this->is_admin()): ?>
              <li><a href="<?=base_url('hojas_rutas/hojas_rutas/archivadas')?>">Hojas de Ruta Archivadas</a></li>
            <?php endif; ?>
            <li><a href="<?=base_url('hojas_rutas/hojas_rutas_alta')?>">Nueva Hoja de Ruta</a></li>
          </ul>
        </div>
      </div>
        <div id="errores"></div>
      <div class="panel-body">
        <div class="tab-content">
          <div id="tab-1" class="tab-pane active">

            <?php if($this->session->flashdata('registro')): ?>
              <div class="row">
                <div class="col-md-12">
                  <div class="alert alert-success" style="margin: 10px 20px 30px 20px;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    La hoja de ruta <b><span class="barcodear"><?php echo $this->session->flashdata('registro') ?></span></b> fue guardada con exito.
                  </div>
                </div>
              </div>
            <?php endif; ?>

            <table id="resultado" class="table table-striped table-hover table-condensed table-bordered bootstrap-datatable">
              <thead>
                <tr>
                  <th class="col-xs-1">Codigo</th>
                  <th class="col-xs-5">Asignado</th>
                  <th class="col-xs-2">Fecha</th>
                  <th class="col-xs-1">Estado</th>
                  <th class="col-xs-1" style="display:none">create</th>
                  <th class="col-xs-3 no-sort"></th>
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
                    <td data-field="estado"><?=$hoja->estado_nombre?></td>
                    <td style="display:none" data-field="create"><?=$hoja->create?></td>
                    <td>
                      <div class="btn-group">
                        <a href="<?=base_url('hojas_rutas/hojas_rutas_alta/hoja/'.$hoja->id)?>" class="btn btn-success btn-ver" title="Ver Hoja de Ruta"><i class="fa fa-indent"></i></a>
                        <a href="javascript:;" onclick="flash.hdr.baja_tabla(<?=$hoja->id?>)" class="btn btn-warning btn-baja" title="Dar de Baja Hoja de Ruta" <?php echo !$hoja->baja || $hoja->estado != Hoja::ESTADO_CERRADA?'disabled="disabled"':''?>><i class="fa fa-check-square-o"></i></a>
                        <a href="javascript:;" onclick="flash.hdr.cerrar(<?=$hoja->id?>)" class="btn btn-warning btn-cerrar" title="Cerrar Hoja de Ruta" <?=$hoja->estado == Hoja::ESTADO_CANCELADA || $hoja->estado == Hoja::ESTADO_CERRADA || $hoja->estado == Hoja::ESTADO_BAJA?'disabled="disabled"':''?>><i class="fa fa-share"></i></a>
                        <a href="javascript:;" onclick="flash.hdr.cancelar(<?=$hoja->id?>)" class="btn btn-danger btn-cancelar" title="Cancelar Hoja de Ruta" <?=$hoja->estado == Hoja::ESTADO_CANCELADA || $hoja->estado == Hoja::ESTADO_CERRADA || $hoja->estado == Hoja::ESTADO_BAJA?'disabled="disabled"':''?>><i class="fa fa-times"></i></a>
                        <a href="javascript:;" onclick="flash.hdr.archivar(<?=$hoja->id?>)" class="btn btn-info btn-archivar" title="Archivar Hoja de Ruta" <?=$hoja->estado == Hoja::ESTADO_INICIADA || $hoja->estado == Hoja::ESTADO_CERRADA?'disabled="disabled"':''?>><i class="fa fa-save"></i></a>
                        <a href="javascript:;" onclick="flash.hdr.imprimir(<?=$hoja->id?>)" class="btn btn-info btn-imprimir" title="Imprimir Hoja de Ruta" ><i class="fa fa-print"></i></a><input type="checkbox" name="auditoria_<?=$hoja->id?>" id="auditoria_<?=$hoja->id?>" text="Auditoria"/> Imprimir con Auditoria
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
<form method='post' action='<?php echo base_url('hojas_rutas/consultas_hojas_rutas/exportarAPdf')?>' id='exportAPdf'>
    <input type='hidden' id='pdf_hoja_ruta_id' name='pdf_hoja_ruta_id' value="">
    <input type='hidden' id='pdf_auditoria' name='pdf_auditoria' value="">
</form>
<script type="text/javascript">
    $('#resultado').DataTable( {
        order: [[ 4, "desc" ]]
    } );
  <?php include 'script.js'; ?>
</script>