<div class="col-xs-12">
    <div class="ibox-content">
        <?php     
            echo form_open_multipart(current_url(), array('class'=>""));
            echo form_hidden('enviar_form','1');
        ?>
            <div id="errores"></div>
            <input type="hidden" name="id" id="id" value="<?php echo $result->id?>"/>
            <?php if ($this->session->flashdata('respuesta')): ?>
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    Los datos del usuario <strong><?php echo $this->session->flashdata('respuesta');?></strong> fueron modificados correctamente.
                </div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('noOk')): ?>
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    No se pudieron modificar los datos del usuario <strong><?php echo $this->session->flashdata('noOk');?></strong>.
                </div>
            <?php endif; ?>
            <div class="row">
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="group">Grupo<span class="required">*</span></label>
                        <!-- GRUPO 4 = CLIENTES | GRUPO 5 = TRANSPORTISTA -->
                        <select id="group" required name="group" class="chosen-select form-control" <?php echo $grupo->group_id == 4 || $grupo->group_id == 5 ? 'disabled' : ''; ?> >
                            <?php foreach ($grupos as $f) { ?>
                                <?php if($f->id == 1) { ?> 
                                    <?php if($issa == 1) { ?>
                                        <option value="<?php echo $f->id ?>" <?php if($f->id == $grupo->group_id) echo 'selected' ?>><?php echo $f->name ?></option>
                                    <?php } ?>
                                <?php }else{ ?>
                                    <option value="<?php echo $f->id ?>" <?php if($f->id == $grupo->group_id) echo 'selected' ?>><?php echo $f->name ?></option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="sucursal_id">Sucursal<span class="required">*</span></label>
                        <select id="sucursal_id" required name="sucursal_id" class="chosen-select form-control">
                            <option value="0">Seleccione una opci&oacute;n</option>
                            <?php foreach ($sucursales as $f) { ?>
                                <option value="<?php echo $f->id ?>" <?php if ($f->id == $result->sucursal_id) echo 'selected' ?>><?php echo $f->nombre ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="username">Usuario<span class="required">*</span></label>
                        <input id="username" required type="text" name="username" value="<?php echo $result->username ?>" disabled class="form-control" />
                    </div>
                    <div class="form-group">
                        <label for="email">Email<span class="required">*</span></label>
                        <input id="email" required type="text" name="email" value="<?php echo $result->email ?>"  disabled class="form-control" />
                    </div>
                    <?php echo $grupo->group_id == 4 || $grupo->group_id == 5 ? '' : '' ?>
                    <div class="form-group">
                        <label for="nombre">Nombre<span class="required">*</span></label>
                        <input <?php echo $grupo->group_id == 4 || $grupo->group_id == 5 ? 'readonly' : '' ?> id="nombre" required type="text" name="nombre" value="<?php echo $result->nombre ?>" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label for="apellido">Apellido<span class="required">*</span></label>
                        <input id="apellido" required type="text" name="apellido" value="<?php echo $result->apellido ?>" class="form-control" />
                    </div>                    
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="telefono">Telefono</label>
                        <input id="telefono" type="text" name="telefono" value="<?php echo $result->telefono ?>" class="form-control" />
                    </div>
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
                    <div class="form-group">
                        <label for="active">Estado</label>
                          <label class="radio-inline">
                            <input type="radio" name="active" id="active" value="1" <?php if ($result->active == 1) echo 'checked' ?> /> Habilitado
                          </label>
                          <label class="radio-inline">
                            <input type="radio" name="active" id="active" value="0" <?php if ($result->active == 0) echo 'checked' ?> /> Inhabilitado
                          </label>
                    </div>
                    <?php if($this->is_admin()): ?>
                     <div class="form-group">
                        <label for="nueva_contrasenia">Nueva Contrase&#241;a<span class="required">*</span></label>
                        <input id="new_password" type="text" name="new_password" value="" class="form-control" />
                        <span><a class="btn btn-danger resetPassword" href="javascript:;"><i class="icon-white fa-recycle"></i> Restablecer Contrase&#241;a</a></span>
                    </div>
                    <?php endif;?>
                </div>
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
<form method='post' action='<?php echo base_url().'backend/users/resetPassword'?>' id='resetPassword'>
    <input type='hidden' id='reset_user_name' name='reset_user_name' value="">
    <input type='hidden' id='reset_new_password' name='reset_new_password' value="">
    <input type='hidden' id='reset_id' name='reset_id' value="">
</form>
<?php $this->view('backend/users/users_js'); ?>

<script type="text/javascript">
    $(document).ready(function() {
        $('.resetPassword').click(function(event) {
            $('#reset_user_name').val($('#username').val());
            $('#reset_new_password').val($('#new_password').val());
            $('#reset_id').val($('#id').val());
            $('#resetPassword').submit();
        });
    });
</script>