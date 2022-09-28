<div class="col-xs-12">
    <div class="ibox-content">
        <?php     
            echo form_open_multipart(current_url());
            echo form_hidden('enviar_form','1');
            echo form_hidden('id',$result->id);
        ?>
            <div id="errores"></div>
            <div class="row">
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="username">Usuario<span class="required">*</span></label>
                        <input id="username" required type="text" name="username" value="<?php echo $result->username ?>" disabled class="form-control" />
                    </div>
                    <div class="form-group">
                        <label for="email">Email<span class="required">*</span></label>
                        <input id="email" required type="text" name="email" value="<?php echo $result->email ?>"  disabled class="form-control" />
                    </div>
                    <div class="form-group">
                        <label for="nombre">Nombre<span class="required">*</span></label>
                        <input id="nombre" required type="text" name="nombre" value="<?php echo $result->nombre ?>" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label for="apellido">Apellido<span class="required">*</span></label>
                        <input id="apellido" required type="text" name="apellido" value="<?php echo $result->apellido ?>" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label for="telefono">Telefono</label>
                        <input id="telefono" type="text" name="telefono" value="<?php echo $result->telefono ?>" class="form-control" />
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="celular">Celular</label>
                        <input id="celular" type="text" name="celular" value="<?php echo $result->celular ?>" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label for="foto">Foto</label>
                        <?php if ($result->foto == "" || $result->foto == "0") { ?>
                            <input type="file" name="foto" id="foto" size="20" accept="image/*" class="form-control" />
                        <?php }else { ?>
                            <div style="width: 100%; height: 250px; background: url(<?php echo base_url().'uploads/users/'.$result->foto ?>) 100%/cover;">
                                <a class="btn btn-mini btn-danger pull-right" href="#" rel="tooltip" title="Eliminar Imagen" onClick="deleteFoto('<?php echo $result->id ?>', '1');"><i class="fa fa-trash-o"></i> Eliminar</a>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
                                                
            <div class="form-group">
                <div class="controls">
                    <?php echo form_button(array('type'  =>'submit','value' =>'Guardar Cambios','name'  =>'submit','class' =>'btn btn-success'), 'Guardar'); ?>
                    <a class="btn btn-danger" href="<?php echo base_url().'backend/dashboard' ?>"><i class="icon-circle-arrow-left icon-white"></i> Volver</a>
                </div>
            </div>
        <?php echo form_close(); ?>
    </div>
</div>
<?php $this->view('backend/users/users_js'); ?>