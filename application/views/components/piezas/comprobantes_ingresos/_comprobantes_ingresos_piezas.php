<div class="col-xs-12">
  <div class="ibox-content">
<!--    <div class="well">
      Al cargar piezas simples cuyo stock se va a enviar a diferentes sucursales, crear una pieza
      por cada sucursal con la cantidad correspondiente. En este caso recomendamos agregar en
      "Datos Varios" el destino donde tiene que ser luego despachado el stock de la pieza.
    </div>-->
    <?=form_open(base_url('piezas/comprobantes_ingresos/piezas_add'), array('class'=>"form-pieza"))?>
      <input type="hidden" id="contador" value="0">
      <div id="errores"></div>
      <div>
        <div class="row">
          <div class="col-xs-3">
            <div class="form-group">
              <label for="numero">Comprobante N°</label>
              <input id="numero" type="text" class="form-control" placeholder="Numero" autocomplete="off" value="<?=$comprobante->numero?>" readonly/>
              <input type="hidden" name="comprobante_id" id="comprobante_id" value="<?=$comprobante->id?>">
            </div>
          </div>
          <div class="col-xs-3">
            <div class="form-group">
              <label for="servicio_id">Servicios</label>
              <?php foreach ($servicios as $servicio): ?>
                <input type="hidden" id="cantidad_servicios_<?=$servicio->comprobante_servicio_id?>" value="<?=$servicio->disponible?>">
                <input type="hidden" id="servicio_acuse_<?=$servicio->comprobante_servicio_id?>" value="<?=$servicio->acuse?>">
              <?php endforeach; ?>
              <select id="servicio_id" class="form-control" >
                <option value="">Seleccione</option>
                <?php foreach ($servicios as $servicio): ?>
                  <option value="<?=$servicio->comprobante_servicio_id?>" servicio_id="<?=$servicio->id?>"><?=$servicio->nombre?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="col-xs-3">
            <label for="numero">Cantidad Disponible</label>
            <input id="servicio_cantidad_disponible" type="text" class="form-control" placeholder="Cantidad Disponible" value="0" readonly />
          </div>
          <div class="col-xs-3">
            <div class="form-group">
              <label for="tipo_id">Tipo</label>
              <select id="tipo_id" class="form-control" disabled="disabled">
                <option value="">Seleccione</option>
                <?php foreach ($tipos as $tipo): ?>
                  <option value="<?=$tipo->id?>"><?=$tipo->nombre?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="col-xs-6">
            <div class="form-group">
              <label for="cliente">Cliente:</label>
                <?php echo $cliente->nombre?>
            </div>
            <div id="div_acuses">
                
            </div>
            <div id="div_etiquetas">
                
            </div>  
            <div id="div_envios">
                
            </div>  
          </div>
        </div>
        <div id="piezas-simples" style="display:none">
          <hr>
          <div class="row">
            <div class="col-xs-4">
              <div class="form-group">
                <label for="cantidad">Cantidad de piezas</label>
                <input id="cantidad" type="number" class="form-control" placeholder="Destinatario" autocomplete="off" min="1" step="1" value="1" />
              </div>
            </div>
          </div>
        </div>
        <div id="paqueteria" class="row">
            <hr>
            <p style="font-weight: bold;margin-left: 15px;text-decoration: underline ">Paqueteria</p>
            <div  class="row">
                <div class="col-xs-6" style="margin-left: 15px">
                     <label for="descripcion_paquete">descripcion_paquete</label>
                     <input id="descripcion_paquete" type="text" class="form-control" placeholder="Ej. Zapatillas run-runner" value=""/>
                 </div>
            </div>
            <br>
            <div class="col-xs-5">
                 <label for="dimensiones">Dimensiones(AltoXLargoXProfund. en cm.) </label>
                 <input id="dimensiones" type="text" class="form-control" placeholder="50x15x20" value="" />
             </div>
            <div class="col-xs-3">
                 <label for="peso">Peso (Kg)</label>
                 <input id="peso" type="text" class="form-control" placeholder="Peso" value="" />
             </div>
            <div class="col-xs-3">
                 <label for="bulto">Bulto (unidades)</label>
                 <input id="bulto" type="text" class="form-control" placeholder="Bulto" value="1" disabled="true" />
             </div>
        </div>    
        <div id="piezas-normales" style="display:none">
          <hr>
          <div class="row">
            <div class="col-xs-5">
              <div class="form-group">
                <label for="destinatario">Destinatario</label>
                <input  id="destinatario" type="text" class="form-control" placeholder="Destinatario" autocomplete="off" />
              </div>
            </div>
            <div class="col-xs-5">
              <div class="form-group">
                <label for="domicilio">Domicilio</label>
                <input  id="domicilio" type="text" class="form-control" placeholder="Domicilio" autocomplete="off" />
              </div>
            </div>
            <div class="col-xs-2">
              <div class="form-group">
                <label for="codigo_postal">CP</label>
                <input  id="codigo_postal" type="text" class="form-control" placeholder="CP" autocomplete="off" />
              </div>
            </div>
            <div class="col-xs-6">
              <div class="form-group">
                <label for="localidad">Localidad</label>
                <input  id="localidad" type="text" class="form-control" placeholder="Localidad" autocomplete="off" />
              </div>
            </div>
            <div class="col-xs-6">
              <div class="form-group">
                <label for="barcode_externo">Codigo Barras Externo</label>
                <input  id="barcode_externo" type="text" class="form-control" placeholder="Codigo Barras Externo" autocomplete="off" />
              </div>
            </div>
          </div>
        </div>
        <div class="row" id="piezas-all" style="display:none">
          <div class="col-xs-12">
            <div class="form-group">
              <label for="datos_varios">Datos Varios</label>
              <textarea id="datos_varios" class="form-control" placeholder="Datos Varios" rows="3"></textarea>
            </div>
          </div>
          <div class="col-xs-12">
            <div class="form-group">
              <label for="datos_varios_1">Datos Varios 1</label>
              <textarea id="datos_varios_1" class="form-control" placeholder="Datos Varios 1" rows="3"></textarea>
            </div>
          </div>
          <div class="col-xs-12">
            <div class="form-group">
              <label for="datos_varios_2">Datos Varios 2</label>
              <textarea id="datos_varios_2" class="form-control" placeholder="Datos Varios 2" rows="3"></textarea>
            </div>
          </div>
          <div class="col-xs-12">
            <div class="form-group">
              <label for="datos_varios_3">Datos Varios 3</label>
              <textarea id="datos_varios_3" class="form-control" placeholder="Datos Varios 3" rows="3"></textarea>
            </div>
          </div>
        </div>
        <hr>
        <div class="row">
          <div class="col-xs-12">
            <div class="control-group text-right">
              <div class="controls">
                <a class="btn btn-primary agregar-grilla" href="javascript:;" disabled="disabled"><i class="fa fa-arrow-down"></i> Agregar</a>
                <button type="submit" class="btn btn-success btn-submit" disabled="disabled"><i class='fa fa-floppy-o'></i> Guardar</button>
<!--                <a class="btn btn-primary imprimir-acuse" target="_blank" href="javascript:;" ><i class="fa fa-arrow-down"></i> Imprimir Acuse</a>
                <a class="btn btn-primary imprimir-etiquetas" href="javascript:;" ><i class="fa fa-arrow-down"></i> Imprimir Etiquetas</a>-->
                <a class="btn btn-danger" href="<?=base_url().'piezas/comprobantes_ingresos'?>"><i class="fa fa-arrow-left"></i> Volver</a>
              </div>
            </div>
          </div>
        </div>
        <hr>
      </div>
      <table id="results" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
        <thead>
          <tr class="header_table">
            <th>Codigo</th>
            <th>Codigo Externo</th>
            <th>Pieza</th>
            <th>Detalle</th>
            <th class="col-xs-1">&nbsp;</th>
          </tr>
        </thead>
        <tbody id="body-grilla">
          <?php $class="";

            foreach ($comprobante_servicios as $comprobante_servicio):?>
<!--            Como la consulta tiene un count cuando no tiene resultados muestra un registro con nulls
                Entonces hago esta comprobación.-->
                <?php //if ($comprobante_servicio->servicio_nombre != ""):?>
                    <tr <?php echo $class;?>>
                      <td class="barcodear"><?=$comprobante_servicio->barcode?></td>
                      <td class="barcodear"><?=$comprobante_servicio->barcode_externo?></td>
                      <td>
                        <b>Servicio: </b> <?=$comprobante_servicio->servicio_nombre?><br>
                        <b>Estado: </b> <?=$comprobante_servicio->pieza_estado_nombre?><br>
                        <b>Tipo: </b> <?php echo ($comprobante_servicio->acuse == 1)?"Normal"." - ".$comprobante_servicio->pieza_tipo_nombre:"Simple"." - ".$comprobante_servicio->pieza_tipo_nombre?><br>
                        <?php if($comprobante_servicio->acuse == 1): ?>
                          <b>Codigo barras externo: </b> <?=$comprobante_servicio->barcode_externo?><br>
                        <?php else: ?>
                          <b>Cantidad de piezas : </b><?=$comprobante_servicio->cantidad_piezas;?>
                        <?php endif; ?>
                      </td>
                      <td>
                        <?php if($comprobante_servicio->acuse == 1 || ($comprobante_servicio->acuse == 0 && $comprobante_servicio->destinatario != '')): ?>
                          <b>Destinatario: </b> <?=$comprobante_servicio->destinatario?><br>
                          <b>Domicilio: </b> <?=$comprobante_servicio->domicilio?><br>
                          <b>Codigo Postal: </b> <?=$comprobante_servicio->codigo_postal?><br>
                          <b>Localidad: </b> <?=$comprobante_servicio->localidad?><br>
                        <?php endif; ?>
                        <b>Datos varios: </b> <?=$comprobante_servicio->datos_varios?>
                         <?php if($comprobante_servicio->servicio_id == 2): //2=paqueteria ?>
                        <br><b style="text-decoration: underline">Paqueteria</b><br>
                            <b>Descripcion: </b> <?=$comprobante_servicio->descripcion_paquete?><br>
                            <b>Dimensiones: </b> <?=$comprobante_servicio->dimensiones?><br>
                            <b>Peso: </b> <?=$comprobante_servicio->peso?><br>
                            <b>Bulto: </b> <?=$comprobante_servicio->bultos?><br>
                          <?php endif; ?>
                      </td>
                      <td >
                            <?php if($comprobante_servicio->servicio_id == 2): //2=paqueteria ?>
                                <a data-toggle="modal" href="<?php echo base_url().'piezas/comprobantes_ingresos/modificarDatosPaqueteria/'.$comprobante_servicio->pieza_id?>" data-target="#myModal" class="btn btn-info"><i class="fa fa-edit"></i></a>
                            <?php endif; ?>
                      </td>
                    </tr>
                    <?php //$class="";endif;?>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?=form_close();?>
  </div>
</div>
<form method='post' action='<?php echo base_url('piezas/comprobantes_ingresos/exportarAPdf')?>' id='exportarAPdf'>
    <input type='hidden' id='pdf_comprobante_ingreso_id' name='pdf_comprobante_ingreso_id' value="">
    <input type='hidden' id='impresion_tipo' name='impresion_tipo' value="">
    <input type='hidden' id='pdf_servicio_id' name='pdf_servicio_id' value="">
    <input type='hidden' id='pdf_limite_inferior' name='pdf_limite_inferior' value="">
</form>   
<script type="text/javascript">
  <?php include 'script_piezas.js'; ?>
</script>