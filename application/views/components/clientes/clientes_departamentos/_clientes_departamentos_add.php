<div class="col-xs-12">
    <div class="ibox-content">
	<?php     
		echo form_open(current_url(), array('class'=>""));
		echo form_hidden('enviar_form','1');
	?>
		
        <div class="form-group">
            <label for="cliente_id">Cliente<span class="required">*</span></label>
            <input id="cliente" name="cliente" type="text" class="form-control" placeholder="Cliente" autocomplete="off"/>
            <input id="cliente_id" name="cliente_id" type="hidden" value="0" />
        </div>

        <div class="form-group">
            <label for="nombre">Nombre<span class="required">*</span></label>
            <input required id="nombre" name="nombre" type="text" class="form-control" placeholder="Nombre" />
        </div>

        <div class="form-group">
            <label for="nombre_contacto">Nombre contacto<span class="required">*</span></label>
            <input required id="nombre_contacto" name="nombre_contacto" type="text" class="form-control" placeholder="Nombre contacto" />
        </div>

        <div class="form-group">
            <label for="telefonos">Telefonos</label>
            <input  id="telefonos" name="telefonos" type="text" class="form-control" placeholder="Telefonos" data-role="tagsinput"/>
        </div>

        <div class="form-group">
            <label for="emails">Emails</label>
            <input  id="emails" name="emails" type="text" class="form-control" placeholder="Emails" data-role="tagsinput"/>
        </div>
		<div class="control-group">
		  <div class="controls">
		    <?php echo form_button(array('type'  =>'submit','value' =>'Guardar','name'  =>'submit','class' =>'btn btn-success'), "<i class='fa fa-floppy-o'></i> Guardar"); ?> 
		    <a class="btn btn-danger" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2); ?>"><i class="fa fa-arrow-circle-left"></i> Volver</a>
		  </div>
		</div>
		<?php echo $clientes; ?>
		<?php echo form_close(); ?>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    
    $('#emails').on('beforeItemAdd', function(event) {
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if (!regex.test(event.item)){
            event.cancel = true;
            var message = $("<label id='email-message' style='color:red;margin-left:10px;font-weight:normal;line-height:.8em;font-size:10px'>El email tiene un formato inválido</label>");
            $('input#emails').after(message);
            $( "#email-message" ).show();
            $( "#email-message" ).delay(1000).fadeOut('slow');
            //$('#contacto-email-message').remove();
        }
    });
    
    $('#telefonos').on('beforeItemAdd', function(event) {
        if (event.item.length < 1
                || isNaN(event.item)
                || event.item.length < 9
                || event.item.length > 11){
            event.cancel = true;
            var message = $("<label id='telefonos-message' style='color:red;margin-left:10px;font-weight:normal;line-height:.8em;font-size:10px'>El teléfono tiene un formato inválido</label>");
            $('input#telefonos').after(message);
            $( "#telefonos-message" ).show();
            $( "#telefonos-message" ).delay(1000).fadeOut('slow');
            //$('#contacto-email-message').remove();
        }
    });
    
    var clientes = <?php echo $clientes?>; 

    $('#cliente').typeahead({
        source: clientes,
        onSelect: function(item) {            
            $('#cliente_id').val(0);
            if (item.value) {
                $('#cliente_id').val(item.value);
            }
        }
    });

    $('#cliente').change(function(event) {
        if ($('#cliente').val() == 'Result not Found' || $('#cliente').val() == '' || $('#cliente_id').val() == 0) {
            $('#cliente').val('').focus();
            $('#cliente_id').val(0);
        }
    });
});
</script>