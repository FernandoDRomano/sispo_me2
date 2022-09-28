if(!flash) var flash={};

flash.hdr = {
  init : function(){
    if(this.hoja_id)
      this.check_baja();
  },
  hoja_id : <?=isset($hoja)?$hoja->id:'false'?>,
  estados : {
    INICIADA : <?=Hoja::ESTADO_INICIADA?>,
    CERRADA : <?=Hoja::ESTADO_CERRADA?>,
    BAJA : <?=Hoja::ESTADO_BAJA?>,
    ARCHIVADA : <?=Hoja::ESTADO_ARCHIVADA?>,
    CANCELADA : <?=Hoja::ESTADO_CANCELADA?>,
  },
  nombre : {
    '<?=Hoja::ESTADO_INICIADA?>'  : '<?=Hoja::estadoNombre(Hoja::ESTADO_INICIADA)?>',
    '<?=Hoja::ESTADO_CERRADA?>'   : '<?=Hoja::estadoNombre(Hoja::ESTADO_CERRADA)?>',
    '<?=Hoja::ESTADO_BAJA?>'      : '<?=Hoja::estadoNombre(Hoja::ESTADO_BAJA)?>',
    '<?=Hoja::ESTADO_ARCHIVADA?>' : '<?=Hoja::estadoNombre(Hoja::ESTADO_ARCHIVADA)?>',
    '<?=Hoja::ESTADO_CANCELADA?>' : '<?=Hoja::estadoNombre(Hoja::ESTADO_CANCELADA)?>',
  },
  baja_tabla : function (hoja_id)
  {
    $('.btn-group') .hide();
    $('#loadding_'+hoja_id).show();
    this.save_baja(hoja_id, function(data){
      if(data.baja)
      {
        var row = $('#row-hdr-' + hoja_id);
        row.find('a.btn-baja').attr('disabled', 'disabled');
        row.find('a.btn-archivar').removeAttr('disabled');
        row.find('[data-field=estado]').html(data.estado);
      }
      if(data.mensaje)
          $('#errores').html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Error </strong> '+data.mensaje+' </div>');
    });
  },
  baja : function (hoja_id)
  {
    this.save_baja(hoja_id, function(data){
      if(data.baja)
      {
        $('#baja-boton, #baja-confirma').toggle();
        $('[data-hdr-field=estado]').html(data.estado);
      }
    });
  },
  save_baja : function (hoja_id, callback)
  {
    $.ajax({
      type: "POST",
      dataType: "json",
      url: '<?=base_url("hojas_rutas/hojas_rutas/baja")?>',
      data: {
        hoja_id : hoja_id
      }
    })
    .done(function(data){
      $('#loadding_'+hoja_id).hide();
      $('.btn-group') .show();
      callback(data);
    });
  },
  check_baja : function ()
  {
    $.ajax({
      dataType: "json",
      url: '<?=base_url("hojas_rutas/hojas_rutas/check_baja")?>',
      data: {
        hoja_id : this.hoja_id
      }
    })
    .done(function(data){
      if(data.baja)
        $('#btn-baja').removeAttr("disabled");
    });
  },
  novedad_guardar : function (subpieza_id)
  {
    var estado = $('#nuevo_estado_'+subpieza_id).val();
    if(estado)
    {
      $('#novedad_guardar_'+subpieza_id).removeAttr('disabled');
    }
    else
    {
      $('#novedad_guardar_'+subpieza_id).attr('disabled','disabled');
    }
  },
  novedad : function (subpieza_id)
  {
    var estado = $('#nuevo_estado_'+subpieza_id).val();
    $.ajax({
      type: "POST",
      dataType: "json",
      url: '<?=base_url("hojas_rutas/hojas_rutas/novedad")?>',
      data: {
        subpieza_id : subpieza_id,
        estado : estado
      }
    })
    .done(function(data){
      $("#row_"+subpieza_id+" [data-field=estado]").html(data.estadoNombre);
      $("#row_"+subpieza_id+" [data-field=novedades]").html(data.novedades);
      flash.hdr.check_baja();
    });
  },
  cerrar : function (id)
  {
    this.estado(id, this.estados.CERRADA, function(response){
      $('#row-hdr-'+id).find('a.btn-cerrar, a.btn-cancelar, a.btn-archivar').attr('disabled','disabled');
      $('#row-hdr-'+id).find('a.btn-baja').removeAttr('disabled');
      $('#row-hdr-'+id).find('[data-field=estado]').html(flash.hdr.nombre[flash.hdr.estados.CERRADA]);
    });
  },
  cancelar : function(id)
  {
    this.estado(id, this.estados.CANCELADA, function(response){
      $('#row-hdr-'+id).find('a.btn-cerrar, a.btn-cancelar').attr('disabled','disabled');
      $('#row-hdr-'+id).find('a.btn-archivar').removeAttr('disabled');
      $('#row-hdr-'+id).find('[data-field=estado]').html(flash.hdr.nombre[flash.hdr.estados.CANCELADA]);
    });
  },
  archivar : function(id)
  {
    this.estado(id, this.estados.ARCHIVADA, function(response){
      $('#row-hdr-'+id).find('a.btn-ver, a.btn-cerrar, a.btn-cancelar, a.btn-archivar').attr('disabled','disabled');
      $('#row-hdr-'+id).find('[data-field=estado]').html(flash.hdr.nombre[flash.hdr.estados.ARCHIVADA]);
    });
  },
  estado : function(id, estado, callback)
  {
    $.ajax({
      type: "POST",
      url: '<?=base_url("hojas_rutas/hojas_rutas/estado")?>',
      data: {
        hoja_id : id,
        estado : estado
      },
      success: function(response){
          callback(response);
      }
    });
  },
  imprimir : function(id)
    {
        $('#pdf_auditoria').val($('#auditoria_'+id).is(':checked'));
        $('#pdf_hoja_ruta_id').val(id);
        $('#exportAPdf').submit();
    }
};

$(function(){
  flash.hdr.init();
});

$('#numero').blur(function(event) {
    getServicios($('#numero').val());
});

function getServicios(numero)
{
    var url = '<?=base_url()?>' + 'ajax/hojas_rutas/serviciosPorComprobante/' + numero;
    $.getJSON(url, function(data) {
        var options = '<option value="">Seleccione</option>';

        if(!data.status)
        {
            $.each(data, function(key, val) {
                options += "<option value='"+val.id+"'>"+ val.nombre +"</option>";
            });
        }
        else
        {
            options += "<option value='0' selected>Sin resultados</option>";
        }

        $("#servicio_id").html(options);
        //$("#departamento_id").trigger("liszt:updated");
    });
}

$('#servicio_id').change(function(event) {
    var hdr_id = $("#hoja_ruta_id").val();
    var numero = $("#numero").val();
    var servicio_id = $("#servicio_id").val();
    var url = '<?=base_url()?>' + 'ajax/hojas_rutas/getCantidadPiezasSimplesPorHDR/' + numero + '/'+hdr_id+'/'+servicio_id;
        $.getJSON(url, function(data) {
                     if(!data.status)
                    {
                        $.each(data, function(key, val) {
                            $('#cantidad').removeAttr('readonly');
                            $('.btn-submit').removeAttr('disabled');
                            $("#cantidad").val(val.cantidad);
                            $('#cantidad').attr('max',val.cantidad);
                            $('#cantidad').attr('min',1);
                        });
                    }
                    else
                    {
                        $.each(data, function(key, val) {
                            $("#numero").val(0);
                            $('#cantidad').attr('max',0);
                            $('#cantidad').attr('min',0);
                        });
                    }
                
        });
});
function exportarAExcel(){
    $('#exportAExcel').submit();
};