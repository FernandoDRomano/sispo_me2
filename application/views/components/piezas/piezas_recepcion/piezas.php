<div class="col-xs-12">
  <div class="ibox-content">
    <?php echo form_open(current_url(), array('id'=>"form-comprobante")); ?>
      <div id="errores"></div>
      <?php if ($this->session->flashdata('registro')) {?>
        <div class="row">
          <div class="col-md-12">
            <div class="alert alert-success">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              Las piezas fueron recibidas exitosamente.
            </div>
          </div>
        </div>
      <?php } ?>
      <div>
        <hr>
        <h3>Agregar piezas</h3>
        <hr>
        <div class="row">
          <div class="col-xs-4">
            <div class="form-group">
              <label for="fecha_recepcion">Fecha Recepcion</label>
              <input id="fecha_recepcion" name="fecha_recepcion" type="text" class="form-control datepicker" placeholder="Fecha Salida" value="<?php echo date('d-m-Y') ?>" required autocomplete="off" />
            </div>
          </div>
          <div class="col-xs-4">
            <div class="form-group">
              <label for="fecha_recepcion">Codigo barra</label>
              <input id="codigo_barra" name="codigo_barra" type="text" class="form-control" placeholder="Codigo barra" autocomplete="off" />
              <input id="cantidad_servicios" name="cantidad_servicios" type="hidden" value="0" />
              <input id="contador" name="contador" type="hidden" value="0" />
              <input id="contador_aux" name="contador_aux" type="hidden" value="0" />
              <input id="codigo_barra_id" name="codigo_barra_id" type="hidden" value="0" />
            </div>
          </div>
          <div class="col-xs-4">
            <div class="control-group" style="margin-top: 23px;">
              <div class="controls">
                <a class="btn btn-primary agregar-grilla" href="javascript:;"><i class="fa fa-arrow-down"></i> Agregar</a>
                <a class="btn btn-success btn-submit" href="javascript:;"><i class='fa fa-floppy-o'></i> Guardar</a>
              </div>
            </div>
          </div>
        </div>
        <hr>
      </div>
      <table class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
        <thead>
          <tr>
            <th class="col-xs-1">Pieza</th>
            <th class="col-xs-10">Descripción</th>
            <th class="col-xs-1">&nbsp;</th>
          </tr>
        </thead>
        <tbody id="body-grilla">

        </tbody>
      </table>
    <?php echo form_close(); ?>
  </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
  var numeros = <?=json_encode($numeros)?>;
  var piezas = <?=json_encode($piezas)?>;

  $('#codigo_barra').typeahead({
    source: numeros,
    displayField: 'barcode',
    onSelect: function(item) {
      $('#codigo_barra_id').val(0);
      $('#codigo_barra').val('');

      if (item.value) {
        $('#codigo_barra_id').val(item.value);
      }
    }
  });

  $('#codigo_barra').change(function(event) {
    if ($('#codigo_barra').val() == 'Result not Found' || $('#codigo_barra').val() == '' || $('#codigo_barra_id').val() == 0) {
      $('#codigo_barra').val('').focus();
      $('#codigo_barra_id').val(0);
    }
  });

  $('.agregar-grilla').click(function(event) {
    var cantidad_servicios = $('#cantidad_servicios').val();
    var contador = $('#contador').val();
    if ($('#codigo_barra_id').val() > 0) {
      if ($('#grilla-servicio-'+$('#codigo_barra_id').val()).length) {

      }else{
        var pieza_id = $('#codigo_barra_id').val();
        var total = parseInt(cantidad_servicios) + parseInt(1);
        var total_c = parseInt(contador) + parseInt(1);
        var inputs = '<div id="grilla-servicio-div-'+pieza_id+'">'+
          '<input type="hidden" name="pieza[]" value="'+pieza_id+'">'+
          '</div>';
        var html = '<tr id="grilla-servicio-'+pieza_id+'">'+
          '<td>'+pieza_id+'</td>'+
          '<td>'+piezas[pieza_id]['desc']+'</td>'+
          '<td><a href="javascript:;" onclick="eliminarFila(\''+pieza_id+'\')" class="btn btn-danger"><i class="fa fa-trash-o"></i></a></td>'+
          '</tr>';
        $('#body-grilla').append(inputs);
        $('#body-grilla').append(html);
        $('#cantidad_servicios').val(total);
        $('#contador').val(total_c);

        var barcode = $('#codigo_barra_id').val();
        var aux = $('#contador_aux').val();

        if (barcode > aux) $('#contador_aux').val(barcode);

      }
    }
    $('#codigo_barra_id').val(0);
    $('#codigo_barra').val('');
  });

  $('.btn-submit').click(function(event) {
    if ($('#cantidad_servicios').val() > 0) {
      //if($('#form-comprobante').valid()){
        $('.btn').addClass('disabled');
        $('#form-comprobante').submit();
      //}
    }else{
      $('#errores').html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Error</strong> Debe agregar por lo menos una pieza.</div>');
    }
  });
});

function eliminarFila(id){
  $('#grilla-servicio-div-'+id).remove();
  $('#grilla-servicio-'+id).remove();
  var cantidad_servicios_c = $('#cantidad_servicios').val();
  var total_c = parseInt(cantidad_servicios_c) - parseInt(1);
  $('#cantidad_servicios').val(total_c);
}
</script>