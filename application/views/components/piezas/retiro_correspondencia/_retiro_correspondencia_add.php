<div class="col-xs-12">
    <div class="ibox-content">
	<?php     
		echo form_open(current_url(), array('class'=>""));
		echo form_hidden('enviar_form','1');
                echo form_input(array('name' => 'cliente_id', 'type'=>'hidden', 'id' =>'cliente_id', 'value' =>''));
                echo form_input(array('name' => 'servicio_id', 'type'=>'hidden', 'id' =>'servicio_id', 'value' =>''));
	?>
        <div class="row">
            <div class="col-xs-6">
                <div class="form-group">
                        <label for="cliente">Cliente</label>
                        <input class="form-control typeahead" type="text" placeholder="Cliente" id="cliente" name="cliente" autocomplete="off" value="">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-6">
                 <div class="form-group">
                     <label for="domicilio">Domicilio</label>
                     <input  id="domicilio" name="domicilio" type="text" class="form-control" placeholder="Domicilio" />
                 </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-6">
                <div class="form-group">
                    <label for="servicio">Servicio</label>
                    <select id='servicio' name='servicio' class="form-control"></select>
                   <!--<input  id="servicio" name="servicio" type="text" class="form-control" placeholder="Servicio" autocomplete="off" value="<?php echo $result->servicio ?>" />-->
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-4">
                <div class="form-group">
                    <label for="fecha_retirar">Fecha de retiro</label>
                    <input  id="fecha_retirar" name="fecha_retirar" type="text" class="form-control  datepicker" placeholder="Fecha_retirar" value="<?=date('d-m-Y') ?>" required autocomplete="off" />
                </div>
            </div>
            <div class="col-xs-4">
                <div class="form-group">
                    <label for="hora_retirar">Hora de retiro</label>
                    <input  id="hora_retirar" name="hora_retirar" type="text" class="form-control input-small" placeholder="09:00" />
                </div>
            </div>   
            <div class="col-xs-4">
                <div class="form-group">
                    <label for="hora_limite_retirar">Hora limite de retiro</label>
                    <input  id="hora_limite_retirar" name="hora_limite_retirar" type="text" class="form-control" placeholder="10:00" />
                </div>
            </div>
        </div>
  <div class="row">
            <div class="col-xs-8">
                <div class="form-group">
                    <label for="observaciones">Observaciones</label>
                    <textarea  id="observaciones" name="observaciones" class="form-control" placeholder="Observaciones"></textarea>
                </div>
            </div>
            <div class="col-xs-2">
                <div class="form-group">
                    <label for="aviso">Aviso</label>
                    <select id="aviso" name="aviso" class="form-control" placeholder=""><b>Estado:</b>
                        <option value="SI">SI</option>
                        <option value="NO">NO</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-2">
                <div class="form-group">
                    <label for="estado">Estado</label>
                    <select id="estado" name="estado" class="form-control" placeholder="Estado"><b>Estado:</b>
                        <option value="P" >Pendientes</option>
                        <option value="R">Recibida</option>
                        <option value="C">Cancelada</option>
                    </select>
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
    
    $('#hora_retirar').mask("99:99");
    $('#hora_limite_retirar').mask("99:99");
     
    var nombres = [<?php foreach ($servicios as $f) echo "{ id: ".$f->id.", name: '".$f->nombre."'}, " ?>];
    
    $('#nombre_servicio').typeahead({
        source: nombres,
        onSelect: function(item) {            
            if (item.value) {
                $('#nombre_servicio').val(item.value);
                $('#servicio_id').val(item.value);
            }
        }
    });
    
    var clientes = [<?php foreach ($clientes as $f) echo "{ id: ".$f->id.", name: '".$f->nombre.' - '.$f->nombre_fantasia."'}, " ?>];
    $('#cliente').val(<?php $cliente_id ?>);
    $('#cliente').typeahead({
        source: clientes,
        onSelect: function(item) {
            if (item.value) {
                    $('#cliente_id').val(item.value);
                    $('#cliente').val(item.value);
                }
            }
    });
    
    $('#cliente').change(function(event) {
        if ($('#cliente').val() == 'Result not Found' || $('#cliente').val() == '' || $('#cliente_id').val() == 0) {
            $('#cliente').val('').focus();
            $('#cliente_id').val(0);
        }
        if ($("#cliente_id").val() != 0){
            $("#servicio").empty();
            $.getJSON('<?php echo base_url()."ajax/retiro_correspondencia/servicios_x_cliente" ?>/'+$("#cliente_id").val(),function(data){
                $.each(data, function(id,value){
                    $("#servicio").append('<option value="'+value.servicio_id+'">'+value.nombre+'</option>');
                });
            });
        }
    });
    
    
//    $('#servicio').typeahead({
//        displayField: 'nombre',
//        ajax: {
//            url: '<?php echo base_url()."ajax/retiro_correspondencia/servicios_x_cliente" ?>',
//            method: "GET",
//            loadingClass: "loading-ajax",
//            //data: $('#cliente_id').val(),
//            preDispatch: function (query) {
//                var cliente = $('#cliente_id').val();
//                return {
//                    search: query,
//                    cliente_id: cliente
//                }
//            }
//        },
//        onSelect: function(item) {
//            $('#servicio_id').val(item.value);
//        }
//    });
    
});
</script>