<?php     
    $attributes = array('id' => 'formEnviar');
    echo form_open(base_url(). 'tarifario/Tarifario/update/' . $tarifario->id , $attributes);
?>    
    <div class="col-xs-12">
        <div class="ibox-content">    	
            
            <div class="row">
                <div class="col-xs-12">
                    <div class="form-group">
                        <label for="cliente">Clientes:</label>
                        <select name="cliente" id="cliente" class="form-control select-input" disabled>
                            <option value="0">Seleccione</option>
                            <?php foreach ($clientes as  $c) { ?>
                                <option 
                                    value="<?php echo $c->id;?>" 
                                    <?php echo $tarifario->cliente_id == $c->id ? 'selected' : ''; ?>
                                    >
                                    <?php echo $c->nombre?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="form-group">
                        <label for="esquema">Esquemas:</label>
                        <select name="esquema" id="esquema" class="form-control select-input">
                            <option value="0">Seleccione</option>
                            <?php foreach ($esquemas as  $e) { ?>
                                <option 
                                    value="<?php echo $e->id;?>"
                                    <?php echo $tarifario->esquema_id == $e->id ? 'selected' : ''; ?>
                                    >
                                    <?php echo $e->nombre?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>

    	</div>
    </div>

    <!-- Botones -->
    <div class="col-xs-12">
        <div class="ibox-content">
            <label>Al terminar de ingregar los datos haga click en el boton "Guardar".</label><br>
            <div class="control-group">
              <div class="controls">
                <?php echo form_button(array('type'  =>'button', 'onclick' => 'guardar(event)' ,'id' => 'btnGuardar' ,'value' =>'Guardar','class' =>'btn btn-success'), "<i class='fa fa-floppy-o'></i> Guardar"); ?> 
                <a class="btn btn-danger" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2); ?>"><i class="fa fa-arrow-circle-left"></i> Volver</a>
              </div>
            </div>
        </div>
    </div>
    <!-- end Botones -->

<script type="text/javascript">

$(document).ready(function() {
    $('.select-input').select2();
});


async function guardar(event){
    event.preventDefault();

    if(!validar()){
        return;
    }

    const cliente = document.getElementById("cliente").value
    const esquema = document.getElementById("esquema").value

    console.log("editando...")
    document.forms["formEnviar"].submit();
}

function validar(){
    const cliente = document.getElementById("cliente")
    const esquema = document.getElementById("esquema")

    if(cliente.value == "0" || cliente.value == ""){
        cliente.focus();
        alert("Debe seleccionar un cliente");
        return false;
    }

    if(esquema.value == "0" || esquema.value == ""){
        esquema.focus();
        alert("Debe seleccionar un esquema");
        return false;
    }

    return true
}


</script>