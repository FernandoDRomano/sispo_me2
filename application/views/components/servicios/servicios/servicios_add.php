<div class="col-xs-12">
    <div class="ibox-content">
	<?php
		echo form_open(current_url(), array('class'=>"", 'id'=>"servicios"));
		echo form_hidden('enviar_form','1');
	?>
        <div class="row">
            <div class="col-xs-2">
                <div class="form-group">
                    <label for="codigo">Código</label>
                    <input  id="codigo" name="codigo" type="text" class="form-control" placeholder="codigo" maxlength="11"/>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                    <label for="grupo_id">Grupo</label>
                    <select id="grupo_id" name="grupo_id" class="form-control chosen-select" >
                        <?php foreach ($grupos as $f) { ?>
                            <option value="<?php echo $f->id ?>"><?php echo $f->nombre ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                    <label for="nombre">Nombre<span class="required">*</span></label>
                    <input required id="nombre" name="nombre" type="text" class="form-control" placeholder="Nombre" />
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-3">
                <div class="form-group">
                    <label for="precio">Precio</label>
                    <input  id="precio" name="precio" type="number" class="form-control" placeholder="Precio" step="0.01" min="0.01" />
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-4">
                <div class="form-group">
                    <label for="acuse">Acuse</label>
                    <div class="radio i-checks"><label> <input type="radio" name="acuse" id="acuse" class="form-control" value="1" checked> Si</label></div>
                    <div class="radio i-checks"><label> <input type="radio" name="acuse" id="acuse" class="form-control" value="0" > No</label></div>
                </div>
            </div>
            <div class="col-xs-4">
                <div class="form-group">
                    <label for="activo">Activo</label>
                    <div class="radio i-checks"><label> <input type="radio" name="activo" id="activo" class="form-control" value="1" checked> Si</label></div>
                    <div class="radio i-checks"><label> <input type="radio" name="activo" id="activo" class="form-control" value="0" > No</label></div>
                </div>
            </div>
            <div class="col-xs-4">
                <div class="form-group">
                    <label for="web">Web</label>
                    <div class="radio i-checks"><label> <input type="radio" name="web" id="web" class="form-control" value="1" checked> Si</label></div>
                    <div class="radio i-checks"><label> <input type="radio" name="web" id="web" class="form-control" value="0" > No</label></div>
                </div>
            </div>
        </div>

		<div class="control-group">
		  <div class="controls">
		    <?php echo form_button(array('type'  =>'submit','value' =>'Guardar','name'  =>'submit','class' =>'btn btn-success'), "<i class='fa fa-floppy-o'></i> Guardar"); ?>
		    <a class="btn btn-danger" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2); ?>"><i class="fa fa-arrow-circle-left"></i> Volver</a>
		  </div>
		</div>
		<?php echo form_close(); ?>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    $('#codigo').blur(function(event) {
        $.ajax({
            type: "POST",
            url: '<?php echo base_url()."ajax/Servicios/codigoExistente" ?>',
            data: 'codigo='+$('#codigo').val(),
            cache: false,
            datatype:'json',
            beforeSend: function() {
                //$('#errores').html('<h3><i class="fa fa-spinner fa-spin"></i></h3>');
                //$('.btn').addClass('disabled');
            },
            success: function(respuesta){
                var obj = $.parseJSON(respuesta);
                if (obj !== null){
                    $("<span class='error-tool-tip'>El Código ya existe<span>").insertAfter($('#codigo'));
                   $('.error-tool-tip').delay(2000).fadeOut('slow');
                    $('#codigo').val('');
                }
                //window.location.reload();
            }
        });
    });
    
    $("#servicios").validate({
    rules: {
      empresa: {require_from_group: [1, ".filtro-group"]},
      cliente: {require_from_group: [1, ".filtro-group"]},
      dpto: {require_from_group: [1, ".filtro-group"]}
    },
    messages: {
      empresa: "Campo requerido.",
      cliente: "Campo requerido.",
      dpto: "Campo requerido.",
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
})
</script>