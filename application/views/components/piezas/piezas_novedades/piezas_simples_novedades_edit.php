<div class="col-xs-12">
  <div class="ibox-content">
    <form class="form-novedades-simples" id="form-novedades-simples" action="<?php echo base_url('piezas/novedades/grabar_novedades_piezas_simples')?>" method="post" enctype="multipart/form-data">
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
            <li><a href="<?=base_url('piezas/novedades/index_edit')?>">Novedades Piezas Normales</a></li>
            <li class="active"><a data-toggle="tab" href="#piezas_simples_novedades">Novedades Piezas Simples</a></li>
          </ul>
        </div>
      </div>

      <div>
        <hr>
        <h3>Agregar piezas simples</h3>
        <hr>
        <div class="row">
          <div class="col-xs-2">
            <div class="form-group">
              <label for="comprobante">Comprobante</label>
              <input  id="codigo_barra" name="codigo_barra" type="text" class="form-control" placeholder="Comprobante" autocomplete="off" />
            </div>
          </div>
          <div class="col-xs-3">
            <div class="form-group">
              <label for="servicios">Servicios</label>
              <select id="servicio_id" name="servicio_id" class="form-control"  required disabled >
                <optgroup label="Servicios">
                    <option value="-1"> Seleccionar...</option>
                </optgroup>
                <optgroup label="Estados Organizativos">
                  <option value="<?=Pieza::ESTADO_NO_RESPONDE?>"><?=Pieza::estadoNombre(Pieza::ESTADO_NO_RESPONDE)?></option>
                </optgroup>
              </select>
            </div>
          </div>            
          <div class="col-xs-12">
            <div class="control-group text-right">
              <div class="controls">
                <a class="btn btn-primary agregar-grilla" href="javascript:;"><i class="fa fa-arrow-down"></i> Agregar</a>
              </div>
            </div>
          </div>
        </div>
        <hr>
      </div>
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
  $('#codigo_barra').blur(function(event) {
        if ($('#codigo_barra').val() > 0) {
            $('#codigo_barra_id').val(parseInt($('#codigo_barra').val()));
            //Traigo la cantidad de items que tiene este cod de barras si es simple o normal
            $("#servicio_id").empty();
            $.getJSON('<?php echo base_url()."ajax/Servicios/getServiciosNombresPorComprobante" ?>/'+$("#codigo_barra").val(),function(data){
                $("#servicio_id").append('<option value="-1">Seleccionar...</option>');
                $.each(data, function(id,value){
                    $("#servicio_id").append('<option value="'+value.comprobante_servicio_id+'">'+value.nombre+'</option>');
                });
                $("#servicio_id").removeAttr('disabled');
                $("#servicio_id").focus();
            });
        }
});

$('.agregar-grilla').click(function(event) {
     var form_data = new FormData();                  
        var other_data = $('#form-novedades-simples').serializeArray();
        $.each(other_data,function(key,input){
            form_data.append(input.name,input.value);
        });
        var buscar_por = $('input:radio[name=buscar_por]:checked').val();
        $.ajax({
            type: "POST",
            url: '<?php echo base_url() . "ajax/novedades/traerNovedadesPiezasSimples" ?>',
            data: form_data,
            cache: false,
            processData: false,
            contentType: false,                
            datatype:'json',
            beforeSend: function() {
                //$('#errores').html('<h3><i class="fa fa-spinner fa-spin"></i></h3>');
                //$('.btn').addClass('disabled');
            },
            success: function(respuesta) {
                var pieza = $.parseJSON(respuesta);
                if (!pieza.status) {
                    $('#body-grilla').html('');
                    
                    var html = "";
                    $.each(pieza['novedades'], function(index, element) {
                        var linea_modificacion = '<a data-toggle="modal" href="<?php echo base_url().'piezas/novedades/editNovedadesPiezasSimples/'?>'+element['comprobante_ingreso_id']+'/'+element['servicio_id']+'/'+element['estado_id']+'" data-target="#myModal" class="btn btn-info"><i class="fa fa-edit"></i></a>';
                        html += '<tr>' +
                                '<td>' + element['numero'] + '</td>' +
                                '<td>' + element['servicio'] + '</td>' +
                                '<td>' + element['estado'] + '</td>' +
                                '<td>' + element['piezas'] + '</td>' +
                                '<td>' + linea_modificacion + '</td>' +
                                '</tr>';
                    });
                    $('#body-grilla').append(html);
                    $('#codigo_barra').val('');
                } else {
                    var message = $("<label id='pieza-normal-message' style='color:red;margin-left:10px;font-weight:normal;line-height:.8em;font-size:10px'>" + pieza.status + "</label>");
                    $('input#codigo_barra').after(message);
                    $("#codigo_barra").focus();
                    $("#codigo_barra").val('');
                    $("#pieza-normal-message").show();
                    $("#pieza-normal-message").delay(1500).fadeOut('slow');
                    $('#codigo_barra_id').val(0);
                    $('#codigo_barra').val('');
                    $('#codigo_barra').focus();
                }
            }
        });
    });
</script>