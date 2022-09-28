<div class="col-xs-12">
    <div class="ibox-content">
	<?php     
		echo form_open(current_url(), array('class'=>""));
		echo form_hidden('enviar_form','1');
	?>
        <div class="row">
          <div class="col-xs-4">
            <div class="form-group">
              <label for="pieza_id">Pieza ID</label>
              <input class="form-control" id="pieza_id" name="pieza_id" type="text" value="<?=$pieza_paquete->pieza_id?>" readonly="true" />
            </div>
          </div>
          <div class="col-xs-8">
            <div class="form-group">
              <label for="descripcion_paquete">Descripcion paquete</label>
              <input class="form-control" id="descripcion_paquete" name="descripcion_paquete" type="text" value="<?=$pieza_paquete->descripcion_paquete?>" readonly="true" />
            </div>
          </div>
        </div>
        <div class="row">
            <div class="col-xs-6">
                <div class="form-group">
                    <label for="dimensiones">Dimensiones (AltoxLargoxProfundidad en mtrs.)</label>
                    <input class="form-control" id="dimensiones" name="dimensiones" placeholder="0.6x0.4x0.5" type="text" value="<?=$pieza_paquete->dimensiones?>"/>
                </div>
            </div>
            <div class="col-xs-3">
            <div class="form-group">
              <label for="peso">Peso (Kg.)</label>
              <input id="peso" name="peso" type="text" value="<?=$pieza_paquete->peso?>" class="form-control"/>
            </div>
          </div>

          <div class="col-xs-2">
            <div class="form-group">
              <label for="bulto">Bulto (Unidades)</label>
              <input value="<?php echo $pieza_paquete->bultos?>" id="bulto" name="bulto" type="text" class="form-control" placeholder="" autocomplete="off"  />
            </div>
          </div>
        </div>

        <div class="control-group">
          <div class="controls">
            <?php echo form_button(array('type'  =>'submit','value' =>'Guardar','name'  =>'submit','class' =>'btn btn-success'), "<i class='fa fa-floppy-o'></i> Guardar"); ?> 
            <a class="btn btn-danger volver"><i class="fa fa-arrow-circle-left"></i> Volver</a>
          </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>
<form method='post' action='<?php echo base_url('piezas/comprobantes_ingresos/piezas')?>' id='volver'>
     <input type='hidden' id='numero' name='numero' value="<?php echo $comprobante_ingreso_numero ?>">
     <input type='hidden' id='flag' name='flag' value="piezas">
</form>    
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
    
    $('.volver').click(function(event) {
         
         $('#volver').submit();
    });
  
</script>