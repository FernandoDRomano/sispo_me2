<div class="col-xs-12">
  <div class="ibox-content">
    <form class="form-novedades" id="form-novedades" action="<?php echo base_url('piezas/novedades/grabar_novedades_piezas_simples')?>" method="post" enctype="multipart/form-data">
      <div id="errores"></div>
      <input type="hidden" id="comprobante_servicio_id" name="comprobante_servicio_id" value="-1">
      <?php if($this->session->flashdata('registro')): ?>
        <div class="row">
          <div class="col-md-12">
            <div class="alert alert-success">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              Las piezas fueron actualizadas exitosamente.
            </div>
          </div>
        </div>
      <?php endif; ?>
<div class="panel blank-panel">
      <div class="panel-heading">
        <div class="panel-options">
          <ul class="nav nav-tabs">
            <li><a href="<?=base_url('piezas/novedades')?>">Novedades Piezas Normales</a></li>
            <li class="active"><a data-toggle="tab" href="#piezas_simples_novedades">Novedades Piezas Simples</a></li>
          </ul>
        </div>
      </div>

      <div>
        <hr>
        <h3>Agregar piezas simples</h3>
        <hr>
        <div class="row">
          <?php /*
          <div class="col-xs-4">
            <div class="form-group">
              <label for="fecha_recepcion">Fecha</label>
              <input  id="fecha_recepcion" name="fecha_recepcion" type="text" class="form-control datepicker" placeholder="Fecha Salida" value="<?=date('d-m-Y')?>" required autocomplete="off" />
            </div>
          </div>
          */ ?>
          <div class="col-xs-2">
              <div class="form-group">
                 <label for="hoja_de_ruta">Hoja de Ruta</label>
                <input type="number" class="form-control" id="hdr_id" name="hdr_id" value=""/>
            </div>
        </div>
          <div class="col-xs-2">
            <div class="form-group">
              <label for="comprobante">Comprobante</label>
              <input  id="codigo_barra" name="codigo_barra" type="text" class="form-control" placeholder="Comprobante" autocomplete="off" />
            </div>
          </div>
          <div class="col-xs-3">
            <div class="form-group">
              <label for="servicios">Servicios</label>
              <select id="servicio" name="servicio" class="form-control"  required disabled >
                <optgroup label="Servicios">
                    <option value="-1"> Seleccionar...</option>
                </optgroup>
                <optgroup label="Estados Organizativos">
                  <option value="<?=Pieza::ESTADO_NO_RESPONDE?>"><?=Pieza::estadoNombre(Pieza::ESTADO_NO_RESPONDE)?></option>
                </optgroup>
              </select>
            </div>
          </div>            
        <div class="col-xs-2">
            <label for="numero">Cant. Disponible</label>
            <input id="servicio_cantidad_disponible" type="text" class="form-control" placeholder="Cantidad Disponible" value="0" readonly />
        </div>
         <div class="col-xs-2">
             <div class="form-group">
                 <label for="cantidad">Cant. Seleccionada</label>
                <input type="number" class="form-control" id="cantidad" name="cantidad" value="" min="0" max="0"/>
            </div>
         </div>
         <div class="col-xs-3">
            <div class="form-group">
              <label for="estado_id">Estado</label>
              <select id="estado_id" name="estado_id" class="form-control"  required >
                <optgroup label="Estados Iniciales">
                    <?php foreach ($estados_iniciales as $estado): ?>
                        <option value="<?= $estado->id ?>"><?= $estado->nombre ?></option>
                    <?php endforeach; ?>
                </optgroup>
                <optgroup label="Estados de Rendiciones">
                  <?php foreach($estados as $estado): ?>
                    <option value="<?=$estado->id?>"><?=$estado->nombre?></option>
                  <?php endforeach; ?>
                </optgroup>
                <optgroup label="Estados Organizativos">
                  <?php foreach($estados_organizativos as $estado_organizativo): ?>
                    <option value="<?=$estado_organizativo->id?>"><?=$estado_organizativo->nombre?></option>
                  <?php endforeach; ?>
                </optgroup>
                <optgroup label="Estados Despacho">
                  <?php foreach($estados_despachos as $estado_despacho): ?>
                    <option value="<?=$estado_despacho->id?>"><?=$estado_despacho->nombre?></option>
                  <?php endforeach; ?>
                </optgroup>
              </select>
            </div>
          </div>
          <div class="col-xs-12">
            <div class="control-group text-right">
              <div class="controls">
                <a class="btn btn-primary agregar-grilla" href="javascript:;"><i class="fa fa-arrow-down"></i> Agregar</a>
                <!--<a class="btn btn-success btn-submit" href="javascript:;"><i class='fa fa-floppy-o'></i> Guardar</a>-->
              </div>
            </div>
          </div>
        </div>
        <hr>
      </div>
      <div id="inputs_cargados_estados" name="inputs_cargados_estados"></div>
      
      <table id="results" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
        <thead>
          <tr>
            <th>C.I.</th>
            <th>Servicio</th>
            <th>Estado</th>
            <th>Cantidad</th>
          </tr>
        </thead>
        <tbody id="body-grilla">
        </tbody>
      </table>
    </div>
    </form>
  </div>
</div>

<script type="text/javascript">
  <?php include 'script_simple.js'; ?>
</script>