<div class="col-xs-12">
    <div class="ibox-content">
        <?php     
          echo form_open(current_url(), array('class'=>""));
          echo form_hidden('enviar_form','1');
          echo form_hidden('id',$result->id);
        ?>
            <div class="form-group">
                <label for="menu_id">Menu<span class="required">*</span></label>                                
                <select id="menu_id" required name="menu_id" class="chosen-select form-control">
                  <?php foreach ($menus as $f) { ?>
                    <option value="<?php echo $f->id ?>" <?php if($f->id == $result->menu_id) echo "selected" ?>><?php echo $f->descripcion ?></option>
                  <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label for="group_id">Grupo<span class="required">*</span></label>                                
                <select id="group_id" required name="group_id" class="chosen-select form-control">
                  <?php foreach ($grupos as $f) { ?>
                    <option value="<?php echo $f->id ?>" <?php if($f->id == $result->group_id) echo "selected" ?>><?php echo $f->name ?></option>
                  <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label for="read">Leer<span class="required">*</span></label>                                
                <label class="radio-inline">
                  <input type="radio" name="read" id="read" value="1" <?php if($result->read==1) echo "checked" ?> /> Si
                </label>
                <label class="radio-inline">
                  <input type="radio" name="read" id="read" value="0" <?php if($result->read==0) echo "checked" ?> /> No
                </label>
            </div>
            <div class="form-group">
                <label for="insert">Insertar<span class="required">*</span></label>                                
                <label class="radio-inline">
                  <input type="radio" name="insert" id="insert" value="1" <?php if($result->insert==1) echo "checked" ?> /> Si
                </label>
                <label class="radio-inline">
                  <input type="radio" name="insert" id="insert" value="0" <?php if($result->insert==0) echo "checked" ?> /> No
                </label>
            </div>
            <div class="form-group">
                <label for="update">Actualizar<span class="required">*</span></label>                                
                <label class="radio-inline">
                  <input type="radio" name="update" id="update" value="1" <?php if($result->update==1) echo "checked" ?> /> Si
                </label>
                <label class="radio-inline">
                  <input type="radio" name="update" id="update" value="0" <?php if($result->update==0) echo "checked" ?> /> No
                </label>
            </div>
            <div class="form-group">
                <label for="delete">Eliminar<span class="required">*</span></label>                                
                <label class="radio-inline">
                  <input type="radio" name="delete" id="delete" value="1" <?php if($result->delete==1) echo "checked" ?> /> Si
                </label>
                <label class="radio-inline">
                  <input type="radio" name="delete" id="delete" value="0" <?php if($result->delete==0) echo "checked" ?> /> No
                </label>
            </div>
            <div class="form-group">
                <label for="exportar">Exportar<span class="required">*</span></label>                                
                <label class="radio-inline">
                  <input type="radio" name="exportar" id="exportar" value="1" <?php if($result->exportar==1) echo "checked" ?> /> Si
                </label>
                <label class="radio-inline">
                  <input type="radio" name="exportar" id="exportar" value="0" <?php if($result->exportar==0) echo "checked" ?> /> No
                </label>
            </div>
            <div class="form-group">
                <label for="imprimir">Imprimir<span class="required">*</span></label>                                
                <label class="radio-inline">
                  <input type="radio" name="imprimir" id="imprimir" value="1" <?php if($result->imprimir==1) echo "checked" ?> /> Si
                </label>
                <label class="radio-inline">
                  <input type="radio" name="imprimir" id="imprimir" value="0" <?php if($result->imprimir==0) echo "checked" ?> /> No
                </label>
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