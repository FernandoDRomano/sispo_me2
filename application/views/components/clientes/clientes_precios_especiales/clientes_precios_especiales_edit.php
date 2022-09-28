<div class="col-xs-12">
    <div class="ibox-content">
		<?php     
			echo form_open(current_url(), array('class'=>""));
			echo form_hidden('enviar_form','1');
		?>
			<?php echo form_hidden('id',$result->id) ?>                    

            <div class="row">
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="cliente_id">Cliente</label>
                        <select id="cliente_id" name="cliente_id" class="form-control chosen-select" >
                            <?php foreach ($clientes as $f) { ?>
                                <option value="<?php echo $f->id ?>" <?php if($f->id == $result->cliente_id) echo "selected" ?>><?php echo $f->nombre ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="nombre">Nombre<span class="required">*</span></label>
                        <input required id="nombre" name="nombre" type="text" class="form-control" placeholder="Nombre" value="<?php echo $result->nombre ?>" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-5">
                    <div class="form-group">
                        <label for="grupo_servicio_id">Grupos</label>
                        <select id="grupo_servicio_id" name="grupo_servicio_id" class="form-control" >
                            <?php foreach ($grupos as $f) { ?>
                                <option value="<?php echo $f->id ?>" <?php if($f->id == $result->grupo_servicio_id) echo "selected" ?>><?php echo $f->nombre ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-xs-5">
                    <div class="form-group">
                        <label for="servicio_id">Servicio</label>
                        <select id="servicio_id" name="servicio_id" class="form-control" >

                        </select>
                    </div>
                </div>                
                <div class="col-xs-2">
                    <div class="form-group">
                        <label for="precio">Precio<span class="required">*</span></label>
                        <input required id="precio" name="precio" type="number" class="form-control" placeholder="Precio" value="<?php echo $result->precio ?>" step="0.01" min="0" />
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="activo">Activo</label>
                <div class="radio i-checks"><label> <input type="radio" name="activo" id="activo" class="form-control" value="1" <?php if($result->activo == 1) echo "checked" ?>> Si</label></div>
                <div class="radio i-checks"><label> <input type="radio" name="activo" id="activo" class="form-control" value="0" <?php if($result->activo == 0) echo "checked" ?>> No</label></div>
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
    function getServicios(){
        var url = '<?php echo base_url() ?>' + 'ajax/servicios/servicios/' + $('#grupo_servicio_id').val();
        $.getJSON(url, function(data) {
            $("#servicio_id").find("option").remove();
            var servicio = '<?php echo $result->servicio_id ?>';
            var options = '';

            if(!data.status){
                $.each(data, function(key, val) {
                    if (val.id == servicio) options = options + "<option value='"+val.id+"' selected>"+ val.nombre +"</option>";
                    else options = options + "<option value='"+val.id+"'>"+ val.nombre +"</option>";
                });              
            }else{
                options = options + "<option value='0' disabled>Sin resultados</option>";
            }

            $("#servicio_id").append(options);
            $("#servicio_id").trigger("liszt:updated");
        });
    }

    $(document).ready(function() {
        getServicios(); 

        $('#grupo_servicio_id').change(function(event) {
            getServicios();             
        });
    });
</script>