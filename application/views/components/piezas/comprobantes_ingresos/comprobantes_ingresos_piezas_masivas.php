<div class="col-xs-12">
  <div class="ibox-content">
      <form class="form-pieza-masivo" action="<?php echo base_url('piezas/comprobantes_ingresos/piezas_masivas_add_xls')?>" method="post" enctype="multipart/form-data">
      <input type="hidden" id="contador" name="contador" value="0">
      <input type="hidden" id="filename" name="filename" value="">
      <input type="hidden" id="cantidad_piezas" name="cantidad_piezas" value="0">
      <div id="errores"></div>
      <div>
        <div class="row">
          <div class="col-xs-3">
            <div class="form-group">
              <label for="numero">Comprobante N°</label>
              <input id="numero" name="numero" type="text" class="form-control" placeholder="Numero" autocomplete="off" value="<?=$comprobante->numero?>" readonly/>
              <input type="hidden" name="comprobante_id" id="comprobante_id" value="<?=$comprobante->id?>">
            </div>
          </div>
          <div class="col-xs-3">
            <div class="form-group">
              <label for="servicio_id">Servicios</label>
              <?php foreach ($servicios as $servicio): ?>
                <input type="hidden" id="cantidad_servicios_<?=$servicio->comprobante_servicio_id?>" name="cantidad_servicios_<?=$servicio->comprobante_servicio_id?>" value="<?=$servicio->disponible?>">
              <?php endforeach; ?>
              <select id="servicio_id" name="servicio_id" class="form-control" >
                <option value="">Seleccione</option>
                <?php foreach ($servicios as $servicio): ?>
                  <option value="<?=$servicio->comprobante_servicio_id?>"><?=$servicio->nombre?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
            <div class="col-xs-3">
              <label for="numero">Cantidad Disponible</label>
              <input id="servicio_cantidad_disponible" name="servicio_cantidad_disponible" type="text" class="form-control" placeholder="Cantidad Disponible" value="0" readonly />
            </div>
        </div>
        <div class="row">  
            <div class="col-xs-6">
              <label for="numero">Seleccione el archivo CSV</label>  
              <input type="file" name="piezas_csv" id="piezas_csv" size="20" class="form-control" style="width: 100%"/>
            </div>
            <div style="margin-top: 22px">
                <a class="btn btn-primary agregar-grilla-masivo" href="javascript:;" ><i class='fa fa-upload'></i>Cargar Archivo</a>
<!--                <button type="submit" class="btn btn-success btn-submit" ><i class='fa fa-upload'></i> Cargar Archivo</button>-->
            </div>
        </div>
          <br>
          <div id="cargando" class="row">
              <div class="col-xs-6">
                  <label for="numero">Cargando archivo...</label><!--<img src="url(<?php echo base_url().'application/media/loading.gif' ?>)">-->
              </div>
          </div>  
          <div id="cargado" class="row">
              <div class="col-xs-6">
                  <label for="numero">Archivo Cargado!!!</label>
              </div>
          </div>  
        <div class="row">
          <div class="col-xs-12">
            <div class="control-group text-right">
              <div class="controls">
                  <?php echo form_button(array('type'  =>'submit','value' =>'Guardar','name'  =>'submit','id'  =>'submit','class' =>'btn btn-success'), "<i class='fa fa-floppy-o'></i> Guardar"); ?> 
                    <a class="btn btn-danger" href="<?=base_url().'piezas/comprobantes_ingresos'?>"><i class="fa fa-arrow-left"></i> Volver</a>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-xs-12">
            <div class="control-group text-right">
                <div id="tabla" name="tabla"></div>
            </div>
          </div>
        </div>
        <hr>
      </div>

    </form>
  </div>
</div>

<script type="text/javascript">
  <?php include 'script_piezas.js'; ?>
</script>