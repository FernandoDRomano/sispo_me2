<div class="col-xs-12">
    <div class="ibox-content">
        <?php     
            echo form_open(current_url(), array('class'=>""));
            echo form_hidden('enviar_form','1');
            echo form_hidden('id',$result->id);
        ?>
            <div class="form-group">
                <label for="name">Nombre<span class="required">*</span></label>                                
                <input id="name" required type="text" name="name" value="<?php echo $result->name ?>" class="form-control" />
            </div>
            <div class="form-group">
                <label for="description">Descripcion<span class="required">*</span></label>                                
                <input id="description" required type="text" name="description" value="<?php echo $result->description ?>" class="form-control" />
            </div>                          
            <div class="form-group">
                <div class="controls">
                    <?php echo form_button(array('type'  =>'submit','value' =>'Guardar Cambios','name'  =>'submit','class' =>'btn btn-success'), 'Guardar'); ?>
                    <a class="btn btn-danger" href="<?php echo base_url().'backend/'.$this->uri->segment(2); ?>"><i class="icon-circle-arrow-left icon-white"></i> Volver</a>
                </div>
            </div>
        <?php echo form_close(); ?>
    </div>
</div>