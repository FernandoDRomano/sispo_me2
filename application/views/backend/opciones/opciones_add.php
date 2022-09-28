<div class="col-xs-12">
    <div class="ibox-content">
        <?php
            echo form_open_multipart(current_url(), array('class'=>""));
            echo form_hidden('enviar_form','1');
        ?>
            <div class="form-group">
                <label for="nombre">Nombre<span class="required">*</span></label>
                <input id="nombre"  type="text" name="nombre" value="" class="form-control" placeholder="nombre" required />
            </div>
            

            <div class="form-group">
                <label for="descripcion">Descripcion</label>
                <input id="descripcion"  type="text" name="descripcion" value="" class="form-control" placeholder="descripcion" />
            </div>
                                            
            <div class="control-group">
              <div class="controls">
                <?php echo form_button(array('type'  =>'submit','value' =>'Guardar','name'  =>'submit','class' =>'btn btn-success'), 'Guardar'); ?> 
                <a class="btn btn-danger" href="<?php echo base_url().'backend/'.$this->uri->segment(2); ?>"><i class="icon-circle-arrow-left icon-white"></i> Volver</a>
              </div>
            </div>

        <?php echo form_close(); ?>
    </div>
</div>