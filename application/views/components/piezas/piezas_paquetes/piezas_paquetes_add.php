<div class="col-xs-12">
  <div class="ibox-content">
<!--    <div class="well">
      Al cargar piezas simples cuyo stock se va a enviar a diferentes sucursales, crear una pieza
      por cada sucursal con la cantidad correspondiente. En este caso recomendamos agregar en
      "Datos Varios" el destino donde tiene que ser luego despachado el stock de la pieza.
    </div>-->
    <?=form_open(base_url('piezas/piezas_paquetes/add'), array('class'=>"form-paquete", 'id'=>'form-paquete'))?>
      <input type="hidden" id="contador" value="0">
      <div id="errores"></div>
      <?php if($this->session->flashdata('registro')): ?>
      <div class="row">
            <div class="col-md-12">
              <div class="alert alert-success" style="margin: 10px 20px 30px 20px;">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <p><?php echo $this->session->flashdata('mensaje') ?></p>
                <p><?php echo $this->session->flashdata('mensaje_descripcion') ?></p>
              </div>
            </div>
        </div>
      <?php endif; ?>
      <div>
        <div class="row">
          <div class="col-xs-6">
            <div class="form-group">
                <label for="cliente_id">Cliente</label>
                <input id="cliente_id" name="cliente_id" type="hidden" value="<?=isset($cliente_id)?$cliente_id:'';?>" />
                <input id="cliente" name="cliente" type="text" class="form-control" placeholder="Escriba el nombre del cliente" autocomplete="off" required value="<?=isset($cliente_id)?$cliente_id:'';?>" />
            </div>
            <div id="div_acuses"></div>
            <div id="div_etiquetas"></div>  
          </div>
          <div class="col-xs-6">
            <div class="form-group">
                <label for="paqueteria_tipo">Tipo Paqueteria</label>
                <input class="form-control" id="paqueteria_tipo" name="paqueteria_tipo" type="text" value="<?=isset($paqueteria_tipo)?$paqueteria_tipo:'';?>" readonly="true" />
            </div>
            <div id="div_acuses"></div>
            <div id="div_etiquetas"></div>  
          </div>
        </div>
        <div id="paqueteria" class="row">
            <div  class="row">
                <div class="col-xs-6" style="margin-left: 15px">
                     <label for="descripcion_paquete">Descripcion del paquete</label>
                     <input id="descripcion_paquete" name="descripcion_paquete" type="text" class="form-control" placeholder="Ej. Zapatillas run-runner" value=""/>
                 </div>
            </div>
            <br>
            <div class="col-xs-5">
                 <label for="dimensiones">Dimensiones(AltoXLargoXProfund. en cm.) </label>
                 <input id="dimensiones" name="dimensiones" type="text" class="form-control" placeholder="50x15x20" value="" />
             </div>
            <div class="col-xs-3">
                 <label for="peso">Peso (Kg)</label>
                 <input id="peso" name="peso" type="text" class="form-control" placeholder="Peso" value="" />
             </div>
            <div class="col-xs-3">
                 <label for="bulto">Bulto (unidades)</label>
                 <input id="bulto" name="bulto" type="text" class="form-control" placeholder="Bulto" value="1"/>
             </div>
        </div>    
        <div id="piezas-paquetes">
          <hr>
          <div class="row">
            <div class="col-xs-5">
              <div class="form-group">
                <label for="destinatario">Destinatario</label>
                <input  id="destinatario" name="destinatario" type="text" class="form-control" placeholder="Destinatario" autocomplete="off" />
              </div>
            </div>
            <div class="col-xs-5">
              <div class="form-group">
                <label for="domicilio">Domicilio</label>
                <input  id="domicilio" name="domicilio" type="text" class="form-control" placeholder="Domicilio" autocomplete="off" />
              </div>
            </div>
            <div class="col-xs-2">
              <div class="form-group">
                <label for="codigo_postal">CP</label>
                <input  id="codigo_postal" name="codigo_postal" type="text" class="form-control" placeholder="CP" autocomplete="off" />
              </div>
            </div>
            <div class="col-xs-6">
              <div class="form-group">
                <label for="localidad">Localidad</label>
                <input  id="localidad" name="localidad" type="text" class="form-control" placeholder="Localidad" autocomplete="off" />
              </div>
            </div>
            <div class="col-xs-6">
              <div class="form-group">
                <label for="barcode_externo">Codigo Barras Externo</label>
                <input  id="barcode_externo" name="barcode_externo" type="text" class="form-control" placeholder="Codigo Barras Externo" autocomplete="off" />
              </div>
            </div>
          </div>
        </div>
        <div class="row" id="piezas-all">
          <div class="col-xs-12">
            <div class="form-group">
              <label for="datos_varios">Datos Varios</label>
              <textarea id="datos_varios" name="datos_varios" class="form-control" placeholder="Datos Varios" rows="3"></textarea>
            </div>
          </div>
          <div class="col-xs-12">
            <div class="form-group">
              <label for="datos_varios">Datos Varios 1</label>
              <textarea id="datos_varios_1" name="datos_varios_1" class="form-control" placeholder="Datos Varios 1" rows="3"></textarea>
            </div>
          </div>
          <div class="col-xs-12">
            <div class="form-group">
              <label for="datos_varios">Datos Varios 2</label>
              <textarea id="datos_varios_2" name="datos_varios_2" class="form-control" placeholder="Datos Varios 2" rows="3"></textarea>
            </div>
          </div>
        </div>
        <hr>
        <div class="row">
          <div class="col-xs-12">
            <div class="control-group text-right">
              <div class="controls">
                <a class="btn btn-primary agregar-grilla" href="javascript:;"><i class="fa fa-arrow-down"></i> Agregar</a>
<!--                <a class="btn btn-primary imprimir-acuse" target="_blank" href="javascript:;" ><i class="fa fa-arrow-down"></i> Imprimir Acuse</a>
                <a class="btn btn-primary imprimir-etiquetas" href="javascript:;" ><i class="fa fa-arrow-down"></i> Imprimir Etiquetas</a>-->
                <a class="btn btn-danger" href="<?=base_url().'piezas/comprobantes_ingresos'?>"><i class="fa fa-arrow-left"></i> Volver</a>
              </div>
            </div>
          </div>
        </div>
        <hr>
      </div>
      <table id="resultado" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
        <thead>
          <tr class="header_table">
            <th>Comprobante Ingreso</th>
            <th>Paquete</th>
            <th>Descripcion</th>
            <th>Peso</th>
            <th>Dimensiones</th>
            <th>Bultos</th>
          </tr>
        </thead>
        <tbody id="body-grilla">
          <?php $class="";
            foreach ($paquetes as $value):?>
                    <tr <?php echo $class;?>>
                      <td><?= $value->comprobante_ingreso?></td>
                      <td><?= $value->pieza_id?></td>
                      <td><?=$value->descripcion_paquete?></td>
                      <td><?=$value->peso?></td>
                      <td><?=$value->dimensiones?></td>
                      <td><?=$value->bultos?></td>
                    </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?=form_close();?>
  </div>
</div>
<form method='post' action='<?php echo base_url('piezas/piezas_paquetes/getPaqueteriaXCliente')?>' id='getListPaqueteriaXCliente'>
    <input type='hidden' id='list_cliente_id' name='list_cliente_id' value="">
    <input type='hidden' id='list_cliente' name='list_cliente' value="">
</form>   
<script type="text/javascript">
  <?php /*include 'script_piezas.js';*/ ?>
  var clientes = <?=json_encode($clientes)?>;
    $('#resultado').DataTable( {
        "order": [[ 1, "desc" ]]
    } );
    
    $('#cliente').typeahead({
        source: clientes,
        onSelect: function(item) {
            $('#cliente_id').val(0);
            if (item.value)
            {
                $('#cliente_id').val(item.value);
                $('#list_cliente_id').val(item.value);
                $('#list_cliente').val(item.text);
                $('#getListPaqueteriaXCliente').submit();
            }
        }
    });
    
    $('.agregar-grilla').click(function(event) {
        if ($("#barcode_externo").val() == ""){
          alert("El paquete debe tener CODIGO DE BARRAS");
        }else{
          $('#form-paquete').submit();
        }
    });
    

   
</script>