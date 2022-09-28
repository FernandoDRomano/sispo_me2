<div class="col-xs-12">
  <div class="ibox-content">
    <div class="panel blank-panel">
      <div class="panel-heading">
        <div id="tabs_hdr" class="panel-options">
          <ul class="nav nav-tabs">
            <li><a href="<?=base_url('hojas_rutas/hojas_rutas')?>">Hojas de Ruta</a></li>
            <?php if($this->is_admin()): ?>
              <li><a href="<?=base_url('hojas_rutas/hojas_rutas/archivadas')?>">Hojas de Ruta Archivadas</a></li>
            <?php endif; ?>
            <li class=""><a data-toggle="tab" href="#tab-1">Nueva Hoja de Ruta</a></li>
            <li  class="active"><a href="<?=base_url('hojas_rutas/hojas_rutas/hojas_rutas_alta_masiva_add')?>">Hojas de Ruta (Alta masiva)</a></li>
          </ul>
        </div>
      </div>
      <div class="panel-body">
        <div class="tab-content">
          <div id="tab-1" class="tab-pane active">

            <?php echo form_open(current_url(), array('id'=>"form-hdr")); ?>
              <input type="hidden" name="estado"/>
              <div id="errores"></div>
                <div class="row">
                  <div class="col-xs-4">
                    <div class="form-group">
                        <label for="hdr_id">Hoja de Ruta<span class="required">*</span></label>
                        <input type="text" id="hdr_id" name="hdr_id" class="form-control"/>
                    </div>
                  </div>
                </div>
                <hr>
                <div class="tabs-container grey">
                    <div class="row">  
                        <div class="col-xs-6">
                          <label for="numero">Seleccione el archivo CSV luego presione Cargar Archivo</label>  
                          <input type="file" name="piezas_csv" id="piezas_csv" size="20" class="form-control" style="width: 100%"/>
                        </div>
                        <div style="margin-top: 22px">
                            <span id="loadding" ><i style="font-size:24px" class="fa fa-spinner fa-spin"></i></span>
                            <a class="btn btn-primary agregar-grilla-masivo" href="javascript:;" ><i class='fa fa-upload'></i>Cargar Archivo</a>
            <!--                <button type="submit" class="btn btn-success btn-submit" ><i class='fa fa-upload'></i> Cargar Archivo</button>-->
                        </div>
                    </div>
                      <br>
                </div>                
                <hr>
                <div class="control-group text-right">
                 <div class="controls">
                   <a class="btn btn-primary agregar-piezas-hdr"><i class="fa fa-arrow-down"></i> Guardar</a>
                   <!--<a class="btn btn-success btn-submit" data-action="create" data-estado="<?=Hoja::ESTADO_INICIADA?>"><i class='fa fa-floppy-o'></i> Guardar</a>-->
                   <!--<a class="btn btn-success btn-submit" data-action="create" data-estado="<?=Hoja::ESTADO_CERRADA?>"><i class='fa fa-share'></i> Cerrar</a>-->
                 </div>
               </div>
              </div>
             <?php if($this->session->flashdata('info')): ?>
                <div class="row">
                  <div class="col-md-12">
                    <div class="alert alert-success" style="margin: 10px 20px 30px 20px;">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                      Cantidad de piezas leidas del CSV subido al servidor: <b><span class=""><?php echo $this->session->flashdata('piezas_leidas') ?></span></b>.<br>
                      Cantidad de piezas disponibles que coinciden con los Codigos: <b><span class=""><?php echo $this->session->flashdata('cantidad_piezas') ?></span></b>.<br>
                      Cantidad de piezas cargadas en HDR: <?php echo $this->session->flashdata('hdr_id') ?> <b><span class=""><?php echo $this->session->flashdata('piezas_insertadas_hdr') ?></span></b>.<br>
                      Cantidad de piezas actualizadas con estado EN DISTRIBUCION: <b><span class=""><?php echo $this->session->flashdata('piezas_actualizadas') ?></span></b>.<br>
                      PARA CARGAR OTRA HDR VUELVA A INGRESAR POR MENU.
                    </div>
                  </div>
                </div>
              <?php endif; ?>
              <div id="info"></div>
              <div id="tabla"></div>
            <?php echo form_close(); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<form method='post' action='<?php echo base_url('hojas_rutas/hojas_rutas_alta/hojas_rutas_alta_masiva_add')?>' id='cargarPiezasEnHdr'>
    <input type='hidden' id='hdr_id_add' name='hdr_id_add' value="">
    <input type='hidden' id='path_piezas_add' name='path_piezas_add' value="">
</form>
<script type="text/javascript">
$(document).ready(function() {
    $('#loadding').hide();
    $('.agregar-piezas-hdr').hide();
$('.agregar-grilla-masivo').click(function(event) {
        var valido = false;
        
        $('#tabla').html('');
        $('#info').html('');
        $('.agregar-piezas-hdr').hide(); 
        if($('#hdr_id').val() > 0 && $('#hdr_id').val() > 0){
            valido = true;
        }
        
        if (!valido){
            $('#errores').html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Error</strong> Debe seleccionar ua Hoja de Ruta.</div>');
            return;
        }
        event.preventDefault();
        $('#loadding').show();

//        if($('.form-hdr').valid())
//        {
            var file_data = $("#piezas_csv").prop("files")[0]; 
            var form_data = new FormData();                  
            form_data.append("file", file_data);
            
            var other_data = $('#form-hdr').serializeArray();
            $.each(other_data,function(key,input){
                form_data.append(input.name,input.value);
            });
//            form_data.append("fields", other_data);
            
            $.ajax({
                        url: '<?php echo base_url()."ajax/hojas_rutas/analizarCSV" ?>',
                        dataType: 'json',
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: form_data,                
                        type: 'post',
                        success: function(data){
                            $('#loadding').hide();
                            if (data.mensaje){
                                 if(data.mensaje == 'OK'){
                                     $('#hdr_id_add').val($('#hdr_id').val());
                                     $('#path_piezas_add').val(data.path);
                                     $('.agregar-piezas-hdr').show();
                                 }else{
                                    $('#errores').html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Error</strong>'+data.mensaje+'</div>');
                                    $('#cargando').hide();
                                    $('#cargado').hide();
                                }
                            }
                            if (data.info){
                                var info = "La cantidad de Codigos leidos desde el CSV de entrada es: "+data.info.piezas_leidas+"<br>"
                                        +"La cantidad de piezas disponibles existentes que coinciden con esos Codigos son: "+data.info.cantidad_piezas;
                                $("#info").append(info);
                            }
                            if (data.repetidos){
                                $('.agregar-piezas-hdr').hide(); 
                                var tabla = "<hr><p>Las siguientes piezas ya se encuentran en otras hojas de ruta. Debe eliminarla del CSV y volver a cargarlo</p><table  id='results' class='table table-striped table-hover table-condensed bootstrap-datatable table-bordered'>"+
                                                "<tr>"+
                                                    "<td>HDR</td>"+
                                                    "<td>Pieza</td>"+
                                                    "<td>Estado Pieza</td>"+
                                                  //  "<td>Estado HDR</td>"+
                                                "</tr>";
                                var detalle = "";
                                $.each(data.repetidos,function(clave,valor) {
                                    detalle +=   "<tr>"+
                                                    "<td>"+valor.hoja_ruta_id+"</td>"+
                                                    "<td>"+valor.barcode_externo+"</td>"+
                                                    "<td>"+valor.estado_pieza+"</td>"+
                                                   // "<td>"+valor.estado_hdr+"</td>"+
                                                "</tr>";
                                })
                                detalle +=    "</tabla>";
                                $("#tabla").append(tabla+detalle);
//                                  
                            }
                        }
             });
//        }
    });
    
    $('.agregar-piezas-hdr').click(function(event) {
        $('#cargarPiezasEnHdr').submit();
    });


$("#form-hdr").validate({
        rules: {
            hdr_id: {required: true},
        },
        messages: {
            hdr_id: "Campo requerido.",
        },
        highlight: function(element) {
            $(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function(element) {
            $(element).closest('.form-group').removeClass('has-error');
        },
        errorElement: 'span',
        errorClass: 'help-block',
        errorPlacement: function(error, element) {
            if(element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        }
    });

    var eliminarFilaSimple = function (btn,hoja_ruta_id, servicio_id, cantidad){
        var form_data = new FormData();                  
        form_data.append('hoja_ruta_id',hoja_ruta_id);
        form_data.append('servicio_id',servicio_id);
        form_data.append('cantidad',cantidad);

        $.ajax({
                    type: "POST",
                    url: '<?php echo base_url()."ajax/hojas_rutas/delete_piezas_simples" ?>',
                    data: form_data,
                    cache: false,
                    processData: false,
                    contentType: false,
                    datatype:'json',
                    beforeSend: function() {
                        $('#loadding').show();
                        $('.btn').addClass('disabled');
                    },
                    success: function(respuesta){
                            var hdr = $.parseJSON(respuesta);
                            if (hdr.respuesta > 0){
                                $(btn).parent().parent().remove();
                                $('#loadding').hide();
                                $('#errores').html('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Exito</strong> Se eliminaron '+hdr.respuesta+' registros.</div>');
                            }else{
                                $('#errores').html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Error</strong> Hubo problemas en la eliminación.</div>');
                            }
                            $('#codigo_barra_simple').val('');
                            $('#servicio_id').val('-1');
                            $('#servicio_id').attr('disabled',true);
                            $('#cantidad').val('');
                            $('#cantidad').attr('disabled',true);
                            $('#loadding').hide();
                            $('.btn').removeClass('disabled');
                        }
            });
    };

});
</script>
