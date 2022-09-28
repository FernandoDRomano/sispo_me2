<div class="col-xs-12">
    <div class="ibox-content">
	<?php     
		echo form_open(current_url(), array('class'=>""));
		echo form_hidden('enviar_form','1');
	?>
        <input type="hidden" id="comprobante_ingreso_servicio_id" name="comprobante_ingreso_servicio_id" value="<?=$comprobante_servicio->comprobante_ingreso_servicio_id  ?>"/>
        <div class="row">
          <div class="col-xs-4">
            <div class="form-group">
              <label for="numero">Numero</label>
              <input id="comprobante_ingreso_id" name="comprobante_ingreso_id" type="hidden" value="<?=$comprobante_servicio->comprobante_ingreso_id?>" />
              <input value="<?php echo $comprobante_servicio->numero?>" id="numero" name="numero" type="text" class="form-control" placeholder="Numero" autocomplete="off" disabled />
            </div>
          </div>
          <div class="col-xs-4">
            <div class="form-group">
              <label for="cantidad_total_ci">Cantidad Total del CI</label>
              <input id="cantidad_total_ci" name="cantidad_total_ci" type="text" value="<?=$comprobante_servicio->cantidad_total_ci?>" disabled class="form-control"/>
            </div>
          </div>
        </div>
        <div class="row">
            <div class="col-xs-6">
                <div class="form-group">
                    <label for="servicio_id">Servicio</label>
                    <select id="servicio_id_p" name="servicio_id_p" class="form-control"  required <?php echo ($comprobante_servicio->estado_liquidacion > 0)?"readonly":"" ?>>
                            <?php foreach ($servicios as $servicio): ?>
                                <option value="<?=$servicio->servicio_id?>" <?php echo ($servicio->servicio_id === $comprobante_servicio->servicio_id)?"selected":""?>><?=$servicio->servicio?></option>
                            <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="row">
          <div class="col-xs-4">
            <div class="form-group">
              <label for="cantidad_total_servicio_p">Cantidad total del Servicio</label>
              <input value="<?php echo $comprobante_servicio->cantidad_total_servicio?>" id="cantidad_total_servicio_p" name="cantidad_total_servicio_p" type="text" class="form-control" placeholder="Numero" autocomplete="off"  />
            </div>
          </div>
        </div>

        <div class="control-group">
          <div class="controls">
            <?php echo form_button(array('type'  =>'submit','value' =>'Guardar','name'  =>'submit','class' =>'btn btn-success'), "<i class='fa fa-floppy-o'></i> Guardar"); ?> 
            <a class="btn btn-danger" href="<?php echo base_url('piezas/comprobantes_ingresos/modificar_comprobantes_index/'.$comprobante_servicio->comprobante_ingreso_id ); ?>"><i class="fa fa-arrow-circle-left"></i> Volver</a>
          </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>
<script type="text/javascript">
    
    $('#modal-form').modal('show');

    $('#grupo_servicio_id').click(function(event) {
            getServicios();             
    });
    
    $('#servicio_id_p').change(function(event) {
            if ($("#servicio_id_p").val() === "2"){//servicio de paqueteria solo puede tener una pieza
                $("#cantidad_total_servicio_p").val("1");
                $("#cantidad_total_servicio_p").attr("readonly",true);
            }             
    });
  
</script>