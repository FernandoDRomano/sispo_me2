<div class="container">
    <div class="row">
        <div class="col-xs-6">
            <div class="form-signin form-signin-mensaje">                
                <div class="login-wrap">
                    <?php echo $texto; ?>
                </div>
            </div>
        </div>
        <div class="col-xs-6">
            <?php echo form_open("backend/auth/reset_password/".$code, 'class="form-signin"');?>
                <h2 class="form-signin-heading">
                    <img src="<?php echo base_url().'assets/public/brand.png' ?>" class="img-responsive center-block"><br>
                    <?php echo lang('reset_password_heading');?>                    
                </h2>
                <div class="login-wrap">                        
                    <div class="user-login-info">
                        <?php if ($this->session->flashdata('message')){ ?>
                            <?php print_r($this->session->flashdata('message')) ?>
                        <?php } ?>
                        <label for="new_password"><?php echo sprintf(lang('reset_password_new_password_label'), $min_password_length);?></label>
                        <input type="password" name="new" value="" id="new" pattern="^.{8}.*$" class="form-control" autofocus required>                        
                        <?php echo lang('reset_password_new_password_confirm_label', 'new_password_confirm');?>
                        <input type="password" name="new_confirm" value="" id="new_confirm" pattern="^.{8}.*$" class="form-control" required>
                        <?php echo form_input($user_id);?>
						<?php echo form_hidden($csrf); ?>
                    </div>
                    <button class="btn btn-lg btn-login btn-block" type="submit">Cambiar contraseña</button>            
                </div>
            <?php echo form_close();?>
        </div>
    </div>            
</div>