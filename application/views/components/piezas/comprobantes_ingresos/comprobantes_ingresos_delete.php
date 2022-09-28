<div class="col-xs-12">
  <div class="ibox-content">
    <?=form_open(base_url().'piezas/comprobantes_ingresos/eliminar_comprobante_ingreso', array('id'=>"form-comprobante"));?>
      <div id="errores"></div>
      <?php if ($this->session->flashdata('registroOK')): ?>
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?=$this->session->flashdata('registroOK')?>
            </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('registroNOOK')): ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <strong>Error!</strong> <?=$this->session->flashdata('registroNOOK')?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
      <div>
        <div class="row">
          <div class="col-xs-4">
            <div class="form-group">
              <label for="numero">Numero</label>
              <input id="comprobante_ingreso_id" name="comprobante_ingreso_id" type="hidden" value="<?=$comprobante->id?>" />
              <input id="numero" name="numero" type="text" class="form-control" placeholder="Numero" autocomplete="off" required disabled value="<?=$comprobante->numero?>" />
            </div>
          </div>
          <div class="col-xs-4">
            <div class="form-group">
              <label for="sucursal_id">Sucursal</label>
              <?php if($this->is_user()): ?>
                <select id="sucursal_id" name="sucursal_id" class="form-control" required disabled="disabled">
                    <option value="<?=$this->usuario->sucursal->id?>"><?=$this->usuario->sucursal->nombre?></option>
                </select>
              <?php else: ?>
                <select id="sucursal_id" name="sucursal_id" class="form-control" required  disabled="disabled">
                  <?php foreach($sucursales as $f): ?>
                    <option value="<?=$f->id?>" <?php echo $f->id == $comprobante->sucursal_id?"selected":""?>><?=$f->nombre?></option>
                  <?php endforeach;?>
                </select>
              <?php endif; ?>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-xs-4">
            <div class="form-group">
              <label for="cliente_id">Cliente</label>
              <input id="cliente_id" name="cliente_id" type="hidden" value="<?php echo $comprobante->cliente_id?>" />
              <input id="cliente" name="cliente" type="text" class="form-control" placeholder="Escriba el nombre del cliente" autocomplete="off" required data-value="<?php echo $cliente->nombre?>" value="<?php echo $cliente->nombre?>"  disabled="disabled"/>
            </div>
          </div>
          <div class="col-xs-4">
            <div class="form-group">
              <label for="departamento_id">Departamento</label>
              <select id="departamento_id" name="departamento_id" class="form-control"  disabled="disabled">
                   <?php foreach($departamentos as $f): ?>
                    <option value="<?=$f->id?>" <?php echo $f->id == $comprobante->departamento_id?"selected":""?>><?=$f->nombre?></option>
                  <?php endforeach;?>
              </select>
            </div>
          </div>
          <div class="col-xs-4">
            <div class="form-group">
              <label for="fecha_pedido">Fecha pedido</label>
              <input id="fecha_pedido" name="fecha_pedido" type="text" class="form-control datepicker" placeholder="Fecha pedido" value="<?php $f = strtotime($comprobante->fecha_pedido);echo date('d-m-Y',$f) ?>" required autocomplete="off"  disabled="disabled"/>
            </div>
          </div>
        </div>
        <hr>
        
        <div class="row">
          <div class="col-xs-12">
            <div class="control-group text-right">
              <div class="controls">
                   <?php if (count($hojas_rutas) == 0 && count($despachos) == 0 && $habilitar_eliminar){?>
                        <a class="btn btn-success btn-eliminar" href="javascript:;"><i class='fa fa-floppy-o'></i> Eliminar</a>
                   <?php }?>
                <a class="btn btn-danger" href="<?=base_url()?>piezas/comprobantes_ingresos/eliminar_comprobantes_comprobar"><i class="fa fa-arrow-circle-left"></i> Volver</a>
              </div>
            </div>
          </div>
        </div>
        <hr>
      </div>
      <?php if (count($hojas_rutas) > 0){?>
      <div>
          El C.I. tiene <?php echo count($hojas_rutas)?> Hojas de rutas 
        <table class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
            <thead>
              <tr>
                <th><a href="#">Código</a></th>
                <th class="col-xs-2"><a href="#">Hoja de Ruta</a></th>
                <th>Sucursal</th>
              </tr>
            </thead>
            <tbody id="body-grilla">
                <?php foreach($hojas_rutas as $hoja_ruta): ?>
                <tr>
                  <td>
                      <?=$hoja_ruta->pieza_id?>
                  </td>
                  <td>
                      <?=$hoja_ruta->hoja_ruta_id?>
                  </td>
                  <td>
                      <?=$hoja_ruta->sucursal?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php }if (count($despachos) > 0){?>
      <div>
          El C.I. tiene <?php echo count($despachos)?> Despachos
          <table class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
            <thead>
              <tr>
                <th><a href="#">Código</a></th>
                <th class="col-xs-2"><a href="#">Despacho</a></th>
                <th>Sucursal</th>
              </tr>
            </thead>
            <tbody id="body-grilla">
                <?php foreach($despachos as $despacho): ?>
                <tr>
                  <td>
                      <?=$despacho->pieza_id?>
                  </td>
                  <td>
                      <?=$despacho->despacho_id?>
                  </td>
                  <td>
                      <?=$despacho->sucursal?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php }?>
      
        
    <?=form_close();?>
  </div>
</div>   
<script type="text/javascript">
  <?php include 'script_edit.js'; ?>
      $('.btn-eliminar').click(function(event) {
            if($('#form-comprobante').valid())
            {
                $('#form-comprobante').submit();
            }
    });
</script>