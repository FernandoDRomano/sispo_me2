<div class="col-xs-12">
  <div class="ibox-content">
    <?=form_open(base_url('piezas/comprobantes_ingresos/piezas'), array('class'=>"form-pieza","id"=>"form-comprobante-ingreso"))?>
      <input type="hidden" id="contador" value="0">
      <div id="errores"></div>
      <?php if ($this->session->flashdata('registro')): ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <strong>Error!</strong> El numero de comprobante <span class="barcodear"><?=$this->session->flashdata('registro')?></span> incorrecto o no está disponible.
                    </div>
                </div>
            </div>
        <?php endif; ?>
      <div>
        <div class="row">
          <div class="col-xs-3">
            <div class="form-group">
              <label for="numero">Comprobante N°<span class="required">*</span></label>
              <input required id="numero" name="numero" type="text" class="form-control" placeholder="Numero" autocomplete="off" value=""/>
              <input type="hidden" name="comprobante_id" id="comprobante_id" value="<?=$comprobante->id?>">
              <input type="hidden" name="flag" id="flag" value="<?=$flag?>">
            </div>
          </div>
          <div class="col-xs-4">
            <div class="control-group">
              <div class="controls" style="margin-top: 25px;">
                <button type="button" class="btn btn-success btn-submit" ><i class='fa fa-check'></i> Aceptar</button>
              </div>
            </div>
          </div>
        </div>
        <hr>
      </div>
    <?=form_close();?>
  </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $("#form-pieza").validate({
        rules: {
            numero: {require_from_group: [1, ".filtro-group"]},
        },
        messages: {
            numero: "Campo requerido.",
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
});

$('.btn-submit').click(function(event) {
    $.getJSON('<?php echo base_url()."ajax/Piezas/comprobanteExistente" ?>/'+$("#numero").val(),function(data){
            if (data.status){
                $('#errores').html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Error</strong> El comprobante<strong> '+$("#numero").val()+ '</strong> no existe o no está disponible.</div>');
                $('#numero').val('');           
                $('#numero').focus();
            }else{
                $('#form-comprobante-ingreso').submit(); 
            }
        });
    })
</script>