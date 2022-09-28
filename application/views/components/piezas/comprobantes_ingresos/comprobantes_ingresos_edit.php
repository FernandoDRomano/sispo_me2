<div class="col-xs-12">
  <div class="ibox-content">
    <?=form_open(base_url().'piezas/comprobantes_ingresos/agregar_servicio', array('id'=>"form-comprobante"));?>
      <div id="errores"></div>
        <?php if ($this->session->flashdata('registroOk')): ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <strong>Exito!</strong> <?=$this->session->flashdata('registroOk')?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('mensaje_error')): ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <strong>Error!</strong> <?=$this->session->flashdata('mensaje_error')?>
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
                <select id="sucursal_id" name="sucursal_id" class="form-control" required >
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
              <input id="cliente" name="cliente" type="text" class="form-control" placeholder="Escriba el nombre del cliente" autocomplete="off" required data-value="<?php echo $cliente->nombre?>" value="<?php echo $cliente->nombre?>"/>
            </div>
          </div>
          <div class="col-xs-4">
            <div class="form-group">
              <label for="departamento_id">Departamento</label>
              <select id="departamento_id" name="departamento_id" class="form-control">
                   <?php foreach($departamentos as $f): ?>
                    <option value="<?=$f->id?>" <?php echo $f->id == $comprobante->departamento_id?"selected":""?>><?=$f->nombre?></option>
                  <?php endforeach;?>
              </select>
            </div>
          </div>
          <div class="col-xs-4">
            <div class="form-group">
              <label for="fecha_pedido">Fecha pedido</label>
              <input id="fecha_pedido" name="fecha_pedido" type="text" class="form-control datepicker" placeholder="Fecha pedido" value="<?php $f = strtotime($comprobante->fecha_pedido);echo date('d-m-Y',$f) ?>" required autocomplete="off"/>
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
              <!--<input id="servicio" name="servicio" type="text" class="form-control" placeholder="Escriba el nombre del servicio" autocomplete="off" />-->
              <select id='servicio' name='servicio' value="" class="form-control">
                  <?php foreach($servicios as $f): ?>
                    <option value="<?=$f->servicio_id?>"><?=$f->nombre?></option>
                  <?php endforeach;?>
              </select>
            </div>
          </div>
          <div class="col-xs-4">
            <div class="form-group">
              <label for="cantidad">Cantidad</label>
              <input id="cantidad" name="cantidad" type="number" class="form-control" placeholder="Cantidad" min="1" step="1" autocomplete="off" value="" />
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
                <!--<a href="javascript:;" onclick="javascript:liberar_comprobante(<?=$comprobante->id?>)" class="btn btn-info btn-liberar-comprobante" title="Archivar Comprobante"><i class="fa fa-trash-o"></i>Liberar C.I.</a>-->
                <a class="btn btn-danger" href="<?=base_url()?>piezas/comprobantes_ingresos/modificar_comprobantes_comprobar"><i class="fa fa-arrow-circle-left"></i> Volver</a>
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
            <th class="col-xs-2"><a href="#">Cantidad</a></th>
            <th><a href="#">Remito</a></th>
            <th>&nbsp;</th>
          </tr>
        </thead>
        <tbody id="body-grilla">
        <?php foreach($comprobante_detalle as $detalle): ?>
          <tr>
            <td>
              <?=$detalle->servicio?>
            </td>
            <td>
                <input type="number" id="cantidad_modificar_<?php echo $detalle->comprobante_ingreso_servicio_id?>" name="cantidad_modificar_<?php echo $detalle->comprobante_ingreso_servicio_id?>" value="<?=$detalle->cantidad_piezas?>" class="form-control" min="<?php echo $detalle->cantidad_piezas_carteros?>" step="1" autocomplete="off" <?php echo ($detalle->servicio_id == 2)?"disabled":""?>/>
            </td>
            <td>
              <?=$detalle->remito?>
            </td>
            <td>
              <div class="btn-group">

                  <a data-toggle="modal" href="<?php echo base_url().'piezas/comprobantes_ingresos/modificar_comprobantes_servicios/'.$detalle->comprobante_ingreso_servicio_id?>" data-target="#myModal" class="btn btn-info"><i class="fa fa-edit"></i></a>
                  <a data-toggle="modal" href="<?php echo base_url().'piezas/comprobantes_ingresos/generar_piezas_disponibles_servicios/'.$detalle->comprobante_ingreso_servicio_id?>" data-target="#myModal" class="btn btn-success"><i class="fa fa-plus"></i></a>
                  <!--<a href="javascript:;" onclick="modificarCantidad(<?=$detalle->comprobante_ingreso_servicio_id?>)" class="btn btn-primary modificar_servicio"><i class="fa fa-pencil"></i></a>-->
                  <!--<a href="javascript:;" onclick="modificarCantidad(<?=$detalle->comprobante_ingreso_servicio_id?>)" class="btn btn-primary modificar_servicio"><i class="fa fa-pencil"></i></a>-->
                  <!--<a href="javascript:;" onclick="eliminarFila()" class="btn btn-danger eliminar-fila-btn"><i class="fa fa-trash-o"></i></a>-->
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?=form_close();?>
  </div>
</div>
<form method='post' action='<?php echo base_url('piezas/comprobantes_ingresos/liberar_comprobante')?>' id='liberar_comprobante'>
    <input type='hidden' id='liberar_comprobante_id' name='liberar_comprobante_id' value="">
</form>    
<script type="text/javascript">
  <?php include 'script_edit.js'; ?>
</script>