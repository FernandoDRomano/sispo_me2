<form method='post' action='<?php echo base_url('piezas/piezas/piezas_list')?>' id='form-piezas'>
<div class="col-xs-12">
    <div class="ibox-content">
        <?php if ($this->session->flashdata('mensaje')): ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <strong>Error!</strong> <span class="barcodear"><?=$this->session->flashdata('mensaje')?></span>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('mensajeOk')): ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <strong></strong> <span class="barcodear"><?=$this->session->flashdata('mensajeOk')?></span>
                    </div>
                </div>
            </div>
        <?php endif; ?>
      <div>
        <div class="row">
          <div class="col-xs-3">
            <div class="form-group">
              <label for="pieza_id">ID de la Pieza<span class="required">*</span></label>
              <input required id="pieza_id" name="pieza_id" type="text" class="form-control" autocomplete="off" value="<?=$piezas[0]->id?>"/>
            </div>
          </div>
          <div class="col-xs-4">
            <div class="control-group">
              <div class="controls" style="margin-top: 25px;">
                <button type="button" class="btn btn-success btn-submit" ><i class='fa fa-filter'></i> Buscar</button>
              </div>
            </div>
          </div>
        </div>
        <hr>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Codigo barra</th>
                        <th>Codigo barra externo</th>
                        <th>Destinatario</th>
                        <th>Domicilio</th>
                        <th>Codigo postal</th>
                        <th>Datos varios</th>
                        <th></th>
<!--                        <th>Cantidad</th>
                        <th>Usuario</th>
                        <th>Comprobante</th>
                        <th>Servicio</th>
                        <th>Estado</th>
                        <th>Tipo</th>
                        <th>Verifica</th>
                        <th>Hoja de ruta</th>
                        <th>Cartero</th>
                        <th>Rendicion</th>
                        <th>Despacho</th>
                        <th>Distribuidor</th>
                        <th>Fecha de cambio estado</th>
                        <th>Fecha de rendicion</th>
                        <th>Fecha de Creacion</th>
                        <th>Fecha de Actualizacion</th>-->
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($piezas as $f) { ?>
                        <tr>
                            <td><div rel='tooltip' data-original-title='<h1><span class="barcode"><?php echo $f->barcode ?></span><br><?php echo $f->barcode ?></h1>'><?php echo $f->barcode ?></div></td>
                            <td><div rel='tooltip' data-original-title='<h1><span class="barcode"><?php echo $f->barcode_externo ?></span><br><?php echo $f->barcode_externo ?></h1>'><?php echo $f->barcode_externo ?></div></td>
                            <td><?php echo $f->destinatario ?></td>
                            <td><?php echo $f->domicilio ?></td>
                            <td><?php echo $f->codigo_postal ?></td>
                            <td><?php echo $f->datos_varios ?></td>
                            <div class="btn-group">
                            <td>
                                <a href="<?=base_url('piezas/piezas/delete/'.$f->id)?>" class="btn btn-danger btn-borrar" title="Eliminar" <?=$f->estado == Despacho::ESTADO_INICIADO?'':''?>><i class="fa fa-trash-o"></i></a>
                            </td>
                            </div>
<!--                            <td><?php echo $f->cantidad ?></td>
                            <td><?php echo $f->usuario_apellido.', '.$f->usuario_nombre ?></td>
                            <td><?php echo $f->comprobante ?></td>
                            <td><?php echo $f->servicio ?></td>
                            <td><?php echo $f->estado ?></td>
                            <td><?php echo $f->tipo ?></td>
                            <td><?php echo $f->verifico ?></td>
                            <td><?php //echo $f->hoja_ruta_id ?></td>
                            <td><?php echo $f->cartero ?></td>
                            <td><?php echo $f->rendicion_id ?></td>
                            <td><?php echo $f->despacho_id ?></td>
                            <td><?php echo $f->distribuidor ?></td>
                            <td><?php echo $f->fecha_estado ?></td>
                            <td><?php echo $f->fecha_rendicion ?></td>
                            <td><?php echo $f->create ?></td>
                            <td><?php echo $f->update ?></td>-->
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
    </div>
    
</form>
<style type="text/css">
.table-responsive {
  width: 100%;
  margin-bottom: 15px;
  overflow-x: auto;
  overflow-y: hidden;
  -webkit-overflow-scrolling: touch;
  -ms-overflow-style: -ms-autohiding-scrollbar;
  border: 1px solid #ddd;
}

.table-responsive>.table>thead>tr>th, .table-responsive>.table>tbody>tr>th, .table-responsive>.table>tfoot>tr>th, .table-responsive>.table>thead>tr>td, .table-responsive>.table>tbody>tr>td, .table-responsive>.table>tfoot>tr>td {
  white-space: nowrap;
}
</style>
<script type="text/javascript">
    
    $('.btn-submit').click(function(event) {
        $('#form-piezas').submit();
    });
    
$('.agregar-grilla').click(function(event) {
    $.ajax({
            type: "POST",
            url: '<?=base_url()?>piezas/piezas/piezas_list',
            data: {
                pieza_id : pieza_id,
            },
            cache: false,
            success: function(response){
                callback(response);
            }
        });
    });
</script>