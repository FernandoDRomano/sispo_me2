<div class="col-xs-12">
  <div class="ibox-content">
    <?=form_open(base_url('hojas_rutas/hojas_rutas/editar'), array('class'=>"form-hojas-rutas"));
        echo form_hidden('enviar_form','1');?>
      <div id="errores"></div>
        <?php if ($this->session->flashdata('registro')): ?>
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                Se eliminaron <strong><?php echo $this->session->flashdata('cantidad');?></strong> piezas simples del comprobante de ingresos <span class="barcodear"><?php echo $this->session->flashdata('registro')?></span><span> de la hoja de ruta <?php echo $this->session->flashdata('hdr_id')?></span>
            </div>
        <?php endif; ?>
      <div>
        <div class="row">
          <div class="col-xs-2">
            <div class="form-group">
              <label for="hoja de ruta">Hoja de Ruta</label>
              <input id="hoja_ruta_id" name="hoja_ruta_id" type="text" class="form-control" autocomplete="off" value=""/>
            </div>
          </div>            
          <div class="col-xs-3">
            <div class="form-group">
              <label for="numero">Comprobante N°</label>
              <input id="numero" name="numero" type="text" class="form-control" placeholder="Numero de comprobante" autocomplete="off" value=""/>
              <input type="hidden" name="comprobante_id" id="comprobante_id" value="<?=$comprobante->id?>">
            </div>
          </div>
          <div class="col-xs-3">
            <div class="form-group">
              <label for="servicio_id">Servicios</label>
              <select id="servicio_id" name="servicio_id" class="form-control" >
                <option value="">Seleccione</option>
              </select>
            </div>
          </div>
          <div class="col-xs-3">
            <label for="numero">Cantidad Disponible</label>
            <input id="cantidad" name="cantidad" type="text" class="form-control" value="0" readonly />
          </div>
        </div>   
        <hr>
        <div class="row">
          <div class="col-xs-12">
            <div class="control-group text-right">
              <div class="controls">
                <button type="submit" class="btn btn-success btn-submit" disabled><i class='fa fa-floppy-o'></i> Guardar</button>
              </div>
            </div>
          </div>
        </div>
        <hr>
      </div>
    <?=form_close();?>
  </div>
</div>
<form method='post' action='<?php echo base_url('piezas/comprobantes_ingresos/exportarAPdf')?>' id='exportarAPdf'>
    <input type='hidden' id='pdf_comprobante_ingreso_id' name='pdf_comprobante_ingreso_id' value="">
    <input type='hidden' id='impresion_tipo' name='impresion_tipo' value="">
    <input type='hidden' id='pdf_servicio_id' name='pdf_servicio_id' value="">
</form>   
<script type="text/javascript">
  <?php include 'script.js'; ?>
</script>