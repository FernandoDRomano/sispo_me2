<div class="col-xs-12">
  <div class="ibox-content">
    <div class="panel blank-panel">
      <div class="panel-heading">
        <div class="panel-options">
          <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#tab-1">Hojas de Ruta</a></li>
            <?php //if($this->is_admin()): ?>
              <li><a href="<?=base_url('hojas_rutas/hojas_rutas/archivadas')?>">Hojas de Ruta Archivadas</a></li>
            <?php //endif; ?>
            <li><a href="<?=base_url('hojas_rutas/hojas_rutas_alta')?>">Nueva Hoja de Ruta</a></li>
            <li><a href="<?=base_url('hojas_rutas/hojas_rutas_alta/hojas_rutas_alta_masiva_add')?>">Hojas de Ruta (Alta masiva)</a></li>
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
            <form action="<?=base_url('hojas_rutas/hojas_rutas/index')?>" method="post" id="form-filtro">
                <div class="row">
                    <div class="col-xs-3">
                        <div class="form-group">
                          <label for="sucursal">Sucursal</label>
                          <select id="sucursal_id" name="sucursal_id" class="form-control filtro-group">
                                <option value="">Seleccione un valor...</option>
                                <?php foreach ($sucursales as $sucursal): ?>
                                    <option value="<?=$sucursal->id ?>" <?php echo $this->session->userdata('filtroHojasRutasSucursalId')==$sucursal->id?'selected':'' ?>>
                                        <?=$sucursal->nombre ?>
                                    </option>
                                <?php endforeach; ?>
                          </select>
                        </div>
                    </div>
                    <div class="col-xs-3">
                        <div class="form-group">
                          <label for="hdr_id">Nro Hoja de Ruta</label>
                          <input type="text" id="hdr_id" name="hdr_id" class="form-control filtro-group"  value="<?php echo $this->session->userdata('filtroHojasRutasHdrId')?>">
                        </div>
                    </div>
                    <div class="col-xs-3" style="padding-top:23px">
                      <div class="btn-group">
                        <button id="btnFiltrar" type="button" class="btn btn-success" title="Filtrar"><i class="fa fa-filter"></i></button>
                      </div>
                    </div>
                </div>
                <input type="hidden" id="procesar" name="procesar" value="0" />
            </form>

            <hr/>
            <div class="alert alert-success">
              <?php echo $time_total; ?>
            </div>

            <?php if (isset($links)) { ?>
                <?php echo $links ?>
            <?php } ?>
            <table id="resultado" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
              <thead>
                <tr>
                  <th class="col-xs-1">Codigo</th>
                  <th class="col-xs-4">Asignado</th>
                  <th class="col-xs-2">Fecha</th>
                  <th class="col-xs-1">Estado</th>
                  <th class="col-xs-1 no-sort">Cantidad</th>
                  <th class="col-xs-1" style="display:none">create</th>
                  <th class="col-xs-3"></th>
                 </tr>
              </thead>
              <tbody>
                <?php if (isset($hojas)) { ?>
                <?php foreach ($hojas as $hoja): 
                    $query_total = " SELECT COUNT(*) cantidad FROM flash_subpiezas WHERE hoja_ruta_id = $hoja->id ";
                    $exe_total = $this->db->query($query_total)->row();
                    ?>

                  <tr id="row-hdr-<?=$hoja->id?>">
                    <td class="barcodear" data-href="<?=base_url('hojas_rutas/hojas_rutas_alta/hoja/'.$hoja->id)?>"><?=$hoja->id?></td>
                    <td>
                      <?php if($hoja->cartero): ?>
                        <b>Cartero:</b> <?=$hoja->cartero ? $hoja->cartero : "" ?><br>
                        <b>Zona:</b> <?=$hoja->zona ? $hoja->zona : "" ?>
                      <?php else: ?>
                        <b>Distribuidor:</b> <?=$hoja->distribuidor ? $hoja->distribuidor : "" ?><br>
                        <b>Transporte:</b> <?=$hoja->transporte ? $hoja->transporte->nombre : "" ?>
                      <?php endif; ?>
                    </td>
                    <td><?=$hoja->fecha_entrega ?></td>
                    <td data-field="estado"><?=$hoja->estado_nombre?></td>
                    <td><?php echo $exe_total->cantidad ?></td>
                    <td style="display:none" data-field="create"><?=$hoja->fecha_entrega?></td>
                    <td>
                      <div id="loadding_<?=$hoja->id?>"  class="loadding"><span id="loadding"><i style="font-size:24px" class="fa fa-spinner fa-spin"></i></span></div>
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
                <?php } ?>
              </tbody>
            </table>
            <?php if (isset($links)) { ?>
                <?php echo $links ?>
            <?php } ?>

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
$(document).ready(function() {
    $('.loadding').hide();
    $('#btnFiltrar').click(function () {
        $('#procesar').val('0');
        var resultadoFiltroValido = filtroValido();
        if (resultadoFiltroValido) {
            $('#procesar').val('1');
            $('#form-filtro').submit();
        } else {
            alert('Operación cancelada. Debe completar los filtros.');
        }
    });
});
function filtroValido() {
    var retorno = false;
    if($('#sucursal_id').val() != '' && retorno == false ){
        retorno = true;
    }
    return retorno;
}
</script>

<script type="text/javascript">
  <?php include 'script.js'; ?>
</script>