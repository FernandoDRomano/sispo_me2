<div class="modal-content">
    <div class="modal-header modal-header-primary">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i></button>
        <h3><?php echo lang('forgot_password_heading');?></h3>
    </div>
    <?php echo form_open("backend/auth/forgot_password");?>
	    <div class="modal-body">
	        <div class="">       
				<p><?php echo sprintf(lang('forgot_password_subheading'), $identity_label);?></p>
				<div class="form-group">
                  	<label for="email"><?php echo sprintf(lang('forgot_password_email_label'), $identity_label);?></label>
                  	<input type="text" name="email" value="" id="email" class="form-control" placeholder="Ingrese su email">
                </div>
	        </div>
	    </div>
	    <div class="modal-footer">
	    	<div class="btn-group">
	    		<input type="submit" name="submit" value="Enviar" class="btn btn-success">
	        	<button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
	    	</div>
	    </div>
    <?php echo form_close();?>   
</div>