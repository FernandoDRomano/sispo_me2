<div class="col-xs-12">
    <div class="ibox-content">
        <?php     
                echo form_open(current_url(), array('class'=>""));
                echo form_hidden('enviar_form','1');
                echo form_hidden('id',$result->id);
                echo form_input(array('name' => 'cliente_id', 'type'=>'hidden', 'id' =>'cliente_id', 'value' =>$cliente->id));
                echo form_input(array('name' => 'servicio_id', 'type'=>'hidden', 'id' =>'servicio_id', 'value' =>$servicio->id));
        ?>
        <div class="row">
            <div class="col-xs-6">	
                <div class="form-group">
                    <label for="cliente">Cliente</label>
                    <input  id="cliente" name="cliente" type="text" class="form-control" placeholder="Cliente" value="<?php echo $cliente->nombre;?>" disabled="disabled"/>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-6">        
                <div class="form-group">
                    <label for="domicilio">Domicilio</label>
                    <input  id="domicilio" name="domicilio" type="text" class="form-control" placeholder="Domicilio" value="<?php echo $result->domicilio ?>" />
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-6"> 
                <div class="form-group">
                    <label for="servicio">Servicio</label>
                    <select id='servicio' name='servicio' class="form-control">
                        <?php foreach($servicios as $f){?>
                            <option value='<?php echo $f->id ?>' <?php echo ($servicio->id == $f->id)?"selected":""?>><?php echo $f->nombre ?></option>
                        <?php } ?>
                    </select>
                </div>   
            </div>
        </div>
        <div class="row">
            <div class="col-xs-4">	
                <div class="form-group">
                    <label for="fecha_retirar">Fecha de Retiro</label>
                    <input id="fecha_retirar" name="fecha_retirar" type="text" class="form-control datepicker" placeholder="Fecha_retirar" value="<?php $f = strtotime($result->fecha_retirar);echo date('d-m-Y',$f ); ?>"  required autocomplete="off" />
                </div>
            </div>
            <div class="col-xs-4">
                <div class="form-group">
                    <label for="hora_retirar">Hora de Retiro</label>
                    <input  id="hora_retirar" name="hora_retirar" type="text" class="form-control  input-small" placeholder="10:00" value="<?php echo $result->hora_retirar ?>" required autocomplete="off" />
                </div>
            </div>
            <div class="col-xs-4">
                <div class="form-group">
                    <label for="hora_limite_retirar">Hora limite de retiro</label>
                    <input  id="hora_limite_retirar" name="hora_limite_retirar" type="text" class="form-control input-small" placeholder="13:15" value="<?php echo $result->hora_limite_retirar ?>" />
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-8">
                <div class="form-group">
                    <label for="observaciones">Observaciones</label>
                    <textarea  id="observaciones" name="observaciones" class="form-control" placeholder="Observaciones"><?php echo $result->observaciones ?></textarea>
                </div>
            </div>
            <div class="col-xs-2">
                <div class="form-group">
                    <label for="aviso">Aviso</label>
                    <select id="aviso" name="aviso" class="form-control" placeholder=""><b>Estado:</b>
                        <option value="SI" <?php if($result->aviso == "SI") echo 'selected' ?>>SI</option>
                        <option value="NO" <?php if($result->aviso == "NO") echo 'selected' ?>>NO</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-2">
                <div class="form-group">
                    <label for="estado">Estado</label>
                    <select id="estado" name="estado" class="form-control" placeholder="Estado"><b>Estado:</b>
                        <option value="P" <?php if($result->estado == "P") echo 'selected' ?>>Pendientes</option>
                        <option value="R" <?php if($result->estado == "R") echo 'selected' ?>>Recibida</option>
                        <option value="C" <?php if($result->estado == "C") echo 'selected' ?>>Cancelada</option>
                    </select>
                </div>
            </div>
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
    
    $('#hora_retirar').mask("99:99");
    $('#hora_limite_retirar').mask("99:99");
    
//    var servicios = [<?php foreach ($servicios as $f) echo "{ id: ".$f->id.", name: '".$f->nombre."'}, " ?>];
//
//        $('#servicio').typeahead({
//            source: servicios,
//            onSelect: function(item) {            
//                if (item.value) {
//                    $('#servicio').val(item.value);
//                    $('#servicio_id').val(item.value);
//                }
//            }
//        });
    });
</script>