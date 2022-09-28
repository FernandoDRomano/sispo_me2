<div class="col-xs-12">
    <div class="ibox-content">
		<?php     
			echo form_open(current_url(), array('class'=>""));
			echo form_hidden('enviar_form','1');
            echo form_hidden('id',$result->id);
		?>		
            <div class="form-group">
                <label for="cliente_id">Cliente<span class="required">*</span></label>
                <input id="cliente" name="cliente" type="text" class="form-control" placeholder="Cliente" autocomplete="off"/>
                <input id="cliente_id" name="cliente_id" type="hidden" value="<?php echo $result->cliente_id ?>" />
            </div>

            <div class="form-group">
                <label for="nombre">Nombre<span class="required">*</span></label>
                <input required id="nombre" name="nombre" type="text" class="form-control" placeholder="Nombre" value="<?php echo $result->nombre ?>" />
            </div>
            
            <div class="form-group">
                <label for="nombre_contacto">Nombre contacto<span class="required">*</span></label>
                <input required id="nombre_contacto" name="nombre_contacto" type="text" class="form-control" placeholder="Nombre contacto" value="<?php echo $result->nombre_contacto ?>" />
            </div>

            <div class="form-group">
                <label for="telefonos">Telefonos</label>
                <input  id="telefonos" name="telefonos" type="text" class="form-control tags" placeholder="Telefonos" value="<?php echo $result->telefonos ?>" />
            </div>

            <div class="form-group">
                <label for="emails">Emails</label>
                <input  id="emails" name="emails" type="text" class="form-control tags" placeholder="Emails" value="<?php echo $result->emails ?>" />
            </div>

			<div class="control-group">
				<div class="controls">
					<?php echo form_button(array('type'  =>'submit','value' =>'Guardar Cambios','name'  =>'submit','class' =>'btn btn-success'), "<i class='fa fa-floppy-o'></i> Guardar"); ?>
					<a class="btn btn-danger" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2); ?>"><i class="fa fa-arrow-circle-left"></i> Volver</a>
				</div>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    buscarCliente('<?php echo $result->cliente_id ?>');

    var clientes = [<?php foreach ($clientes as $f) echo "{ id: ".$f->id.", name: '".$f->nombre."'}, " ?>]; 

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

function buscarCliente(cliente){
    $.ajax({
        type: "GET",
        url: '<?php echo base_url()."ajax/clientes/cliente" ?>/' + cliente,
        data: {},
        cache: false,
        success: function(respuesta){
            var p = jQuery.parseJSON(respuesta);
            $('#cliente').val(p.nombre);
        }
    });
}
</script>