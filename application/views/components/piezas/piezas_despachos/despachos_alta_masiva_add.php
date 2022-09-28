<div class="col-xs-12">
  <div class="ibox-content">
    <div class="panel blank-panel">
      <div class="panel-heading">
        <div class="panel-options">
          <ul class="nav nav-tabs">
          <li><a href="<?=base_url('piezas/despachos')?>">Lista de Despachos</a></li>
            <?php if($this->is_admin()): ?>
              <li><a href="<?=base_url('piezas/despachos/archivados')?>">Despachos Archivados</a></li>
            <?php endif; ?>
            <li><a href="<?=base_url('piezas/despachos/nuevo')?>">Nuevo Despacho</a></li>
            <li class="active"><a data-toggle="tab" href="#tab-2">Nuevo Despacho Masivo</a></li>
          </ul>
        </div>
      </div>
      <div class="panel-body">
        <?php if($this->session->flashdata('registro')): ?>
          <div class="row">
            <div class="col-md-12">
              <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                El despacho <b># <?=$this->session->flashdata('registro'); ?></b> fue guardado con exito.
              </div>
            </div>
          </div>
        <?php endif; ?>
        <div class="tab-content">
          <div id="tab-1" class="tab-pane active">
          <form class="form-pieza-masivo" action="<?php echo base_url('piezas/despachos/despacho_add_xls')?>" method="post" enctype="multipart/form-data">
              <input type="hidden" id="contador" name="contador" value="0">
              <input type="hidden" id="filename" name="filename" value="">
              <input type="hidden" id="cantidad_piezas" name="cantidad_piezas" value="0">
              <input type="hidden" id="piezas_ids" name="piezas_ids" value="">

                <div class="row">
                  <div class="col-xs-4">
                    <div class="form-group">
                      <label for="origen_id">Sucursal Origen</label>
                      <?php if($this->is_user()): ?>
                        <select id="origen_id" name="origen_id"  class="form-control" required>
                          <?php foreach($sucursal_origen as $sucursal): ?>
                            <option value="<?=$sucursal->id ?>"><?=$sucursal->nombre ?></option>
                          <?php endforeach; ?>
                        </select>
                      <?php else: ?>
                        <select id="origen_id" name="origen_id" class="form-control" required>
                          <?php foreach($sucursal_origen as $sucursal): ?>
                            <option value="<?=$sucursal->id ?>"><?=$sucursal->nombre ?></option>
                          <?php endforeach; ?>
                        </select>
                      <?php endif; ?>
                    </div>
                  </div>
                  <div class="col-xs-4">
                    <div class="form-group">
                      <label for="destino_id">Sucursal Destino</label>
                      <select id="destino_id" name="destino_id" class="form-control"  required >
                        <?php foreach($sucursales as $sucursal): ?>
                          <option value="<?=$sucursal->id ?>"><?=$sucursal->nombre ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-xs-4">
                    <div class="form-group">
                      <label for="transporte_id">Transporte</label>
                      <select id="transporte_id" name="transporte_id" class="form-control"  required >
                        <?php foreach ($transportes as $transporte): ?>
                          <option value="<?=$transporte->id ?>"><?=$transporte->nombre ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-xs-6">
                    <div class="form-group">
                      <label for="usuario_origen_id">Usuario Envio</label>
                      <?php if($this->is_user()): ?>
                        <select id="usuario_origen_id" name="usuario_origen_id" class="form-control" required>
                            <?php foreach($usuarios as $usuario): ?>
                                <option value="<?=$usuario->id ?>"><?=$usuario->apellido.', '.$usuario->nombre ?></option>
                            <?php endforeach; ?>
                        </select>
                      <?php else: ?>
                        <select id="usuario_origen_id" name="usuario_origen_id" class="form-control" required>
                          <?php foreach($usuarios as $usuario): ?>
                            <option value="<?=$usuario->id ?>"><?=$usuario->apellido.', '.$usuario->nombre ?></option>
                          <?php endforeach; ?>
                        </select>
                      <?php endif; ?>
                    </div>
                  </div>
                  <div class="col-xs-6">
                    <div class="form-group">
                      <label for="fecha_envio">Fecha Salida</label>
                      <input id="fecha_envio" name="fecha_envio" type="text" class="form-control datepicker" placeholder="Fecha Salida" value="<?=date('d-m-Y') ?>" required autocomplete="off" />
                    </div>
                  </div>
                </div>

              <!-- FORMULARIO -->

              <div class="col-xs-12">
      <div class="ibox-content">

      <div id="errores"></div>
      <div>

        <div class="row">  
            <div class="col-xs-6">
              <label for="numero">Seleccione el archivo XLS</label>  
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
                  <?php echo form_button(array('type'  =>'submit','value' =>'Guardar','name'  =>'submit','id'  =>'submit','class' =>'btn btn-success','disabled'=>'true'), "<i class='fa fa-floppy-o'></i> Guardar"); ?> 
                    <a class="btn btn-danger" href="<?=base_url().'piezas/despachos'?>"><i class="fa fa-arrow-left"></i> Volver</a>
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
            
              <!--FIN FORMULARIO -->

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<form method='post' action='<?php echo base_url('piezas/despachos/nuevoMasivoAdd')?>' id='cargarPiezasEnDespacho'>
    <input type='hidden' id='hdr_id_add' name='hdr_id_add' value="">
    <input type='hidden' id='path_piezas_add' name='path_piezas_add' value="">
</form>
<script type="text/javascript">
  $(document).ready(function() {
    $('#cargando').hide();
    $('#cargado').hide();
    //$('#submit').addAttr('disabled','disabled');
    $('.agregar-grilla-masivo').click(function(event) {
        $('#cargando').show();
        if($('.form-pieza-masivo').valid())
        {
            var file_data = $("#piezas_csv").prop("files")[0];   
            var form_data = new FormData();                  
            form_data.append("file", file_data);
            
            var other_data = $('.form-pieza-masivo').serializeArray();
            $.each(other_data,function(key,input){
                form_data.append(input.name,input.value);
            });
//            form_data.append("fields", other_data);
            
            $.ajax({
                        url: '<?php echo base_url()."ajax/Despachos/analizarXLS" ?>',
                        dataType: 'json',
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: form_data,                
                        type: 'post',
                        success: function(data){//alert(JSON.stringify(data));
                            if (data.error){
                                $('#errores').html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Error</strong>'+data.error+'</div>');
                                $('#cargando').hide();
                                $('#cargado').hide();
                            }else{
                                $('#piezas_ids').val(data['despacho']['piezas_ok']);
                                $('#filename').val(data['despacho']['filename']);
                                $('#cargando').hide();
                                $('#cargado').show();
                                $('#submit').removeAttr('disabled');
                                $('.agregar-grilla-masivo').attr('disabled',true);
                                var contador = 0;
                                var cantidad_piezas = data['despacho']['piezas_ok'];
                                $('#cantidad_piezas').val(cantidad_piezas);
                                var tabla = "<br/><table  id='results' class='table table-striped table-hover table-condensed bootstrap-datatable table-bordered'>"+
                                                "<tr>"+
                                                    "<td>Piezas Aceptadas (Cant.)</td>"+
                                                    "<td>Piezas Rechazadas</td>"+
                                                "</tr>"+
                                                "<tr>"+
                                                    "<td>"+(data['despacho']['piezas_ok']).length+"</td>"+
                                                    "<td>"+data['despacho']['piezas_nook']+"</td>"+
                                                "</tr>"+
                                            "</tabla>";
                                $("#tabla").append(tabla);
//                                  
                            }
                        }
             });
        }
    });
  });
</script>
