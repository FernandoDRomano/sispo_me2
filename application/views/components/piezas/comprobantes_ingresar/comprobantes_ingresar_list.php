<div class="col-xs-12">
  <div class="ibox-content">
    <?=form_open(base_url().'piezas/comprobantes_ingresar/add', array('id'=>"form-comprobante"));?>
      <div id="errores"></div>
      <div>
        <div class="row">
          <div class="col-xs-4">
            <div class="form-group">
              <label for="numero">Numero</label>
              <input id="numero_id" name="numero_id" type="hidden" value="<?=$numeros->id?>" />
              <input id="numero" name="numero" type="hidden" value="<?=$numeros->numero?>" />
              <input id="numero_2" name="numero_2" type="text" class="form-control" placeholder="Numero" autocomplete="off" required disabled value="<?=$numeros->numero?>" />
            </div>
          </div>
          <div class="col-xs-4">
            <div class="form-group">
              <label for="sucursal_id">Sucursal</label>
              <?php if($this->is_user()): ?>
                <select id="sucursal_id" name="sucursal_id" class="form-control" required readonly>
                    <option value="<?=$this->usuario->sucursal->id?>"><?=$this->usuario->sucursal->nombre?></option>
                </select>
              <?php else: ?>
                <select id="sucursal_id" name="sucursal_id" class="form-control" required >
                  <?php foreach($sucursales as $f): ?>
                    <option value="<?=$f->id?>"><?=$f->nombre?></option>
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
              <input id="cliente_id" name="cliente_id" type="hidden" value="0" />
              <input id="cliente" name="cliente" type="text" class="form-control" placeholder="Escriba el nombre del cliente" autocomplete="off" required />
            </div>
          </div>
          <div class="col-xs-4">
            <div class="form-group">
              <label for="departamento_id">Departamento</label>
              <select id="departamento_id" name="departamento_id" class="form-control">
              </select>
            </div>
          </div>
          <div class="col-xs-4">
            <div class="form-group">
              <label for="fecha_pedido">Fecha pedido</label>
              <input id="fecha_pedido" name="fecha_pedido" type="text" class="form-control datepicker" placeholder="Fecha pedido" value="<?=date('d-m-Y')?>" required autocomplete="off" disabled/>
            </div>
          </div>
        </div>
        <hr>
        <div id="errorServicios"></div>
        <div class="row">
          <div class="col-xs-4">
            <div class="form-group">
              <label for="servicio_id">Servicio</label>
              <input type="hidden" name="cantidad_servicios" id="cantidad_servicios" value="0">
              <input type="hidden" name="cantidad_servicios_c" id="cantidad_servicios_c" value="0">
              <input id="servicio_id" name="servicio_id" type="hidden" value="0" />
<!--              <input id="servicio" name="servicio" type="text" class="form-control" placeholder="Escriba el nombre del servicio" autocomplete="off" />-->
              <select id='servicio' name='servicio' value="" class="form-control"></select>
            </div>
          </div>
          <div class="col-xs-4">
            <div class="form-group">
              <label for="cantidad">Cantidad</label>
              <input id="cantidad" name="cantidad" type="number" class="form-control" placeholder="Cantidad" min="1" step="1" autocomplete="off" required value="" />
            </div>
          </div>
          <div class="col-xs-4">
            <div class="form-group">
              <label for="remito">Remito</label>
              <input id="remito" name="remito" type="text" class="form-control" placeholder="Remito" autocomplete="off" />
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-xs-12">
            <div class="control-group text-right">
              <div class="controls">
                <a class="btn btn-primary agregar-grilla" href="javascript:;"><i class="fa fa-arrow-down"></i> Agregar</a>
                <a class="btn btn-success btn-submit" href="javascript:;"><i class='fa fa-floppy-o'></i> Guardar</a>
                <a class="btn btn-danger" href="<?=base_url()?>piezas/comprobantes_ingresar"><i class="fa fa-arrow-circle-left"></i> Volver</a>
              </div>
            </div>
          </div>
        </div>
        <hr>
      </div>
      <table class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
        <thead>
          <tr>
            <th><a href="#">Servicio</a></th>
            <th><a href="#">Cantidad</a></th>
            <th><a href="#">Remito</a></th>
            <th class="col-xs-1">&nbsp;</th>
          </tr>
        </thead>
        <tbody id="body-grilla">

        </tbody>
      </table>
    <?=form_close();?>
  </div>
</div>

<script type="text/javascript">
  <?php include 'script.js'; ?>
      
  $('#servicio').change(function(event) {
        if($('#servicio').val() === 'Result not Found' || $('#servicio').val() === '' || $('#servicio_id').val() === 0)
        {
            $('#servicio').val('').focus();
            $('#servicio_id').val(0);
        }else{
            if($('#servicio').val() === "2"){//El servicio es paqueteria solo acepta UNA pieza por paquete
                $('#cantidad').val(1);
                $('#cantidad').attr("disabled",true);
            }else{
                $('#cantidad').val(0);
                $('#cantidad').attr("disabled",false);
            }
        }
    });
</script>