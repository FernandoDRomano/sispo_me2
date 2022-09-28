<section class="panel">
    <header class="panel-heading">Cambiar password</header>
    <div class="panel-body">
        <?php echo form_open("backend/auth/change_password");?>
           <?php if ($this->session->flashdata('message')){ ?>
              <?php print_r($this->session->flashdata('message')) ?>
          <?php } ?>
          <div class="col-xs-6">
            <div class="form-group">
                <label for="provinciaid"><?php echo "Password actual";?><span class="required">*</span></label>
                <?php echo form_input($old_password);?>
            </div>
            <div class="form-group">
                <label for="provinciaid"><?php echo "Nuevo passowrd";?><span class="required">*</span></label>
                <?php echo form_input($new_password);?>
            </div>
            <div class="form-group">
                <label for="provinciaid"><?php echo "Confirmar nuevo password";?><span class="required">*</span></label>
                <?php echo form_input($new_password_confirm);?>
            </div>

            <?php echo form_input($user_id);?>
            <?php echo form_button(array('type'  =>'submit','value' =>'Guardar','name'  =>'submit','class' =>'btn btn-success'), "Guardar"); ?> 
          </div>

        <?php echo form_close();?>
    </div>
</section>
<script type="text/javascript">
    var old = new LiveValidation('old'); old.add( Validate.Presence  );
    var newp = new LiveValidation('new'); 
        newp.add( Validate.Presence  ); 
        newp.add( Validate.Length, { minimum: 8, tooShortMessage: "Minimo 8 caracteres." } );
    var new_confirm = new LiveValidation('new_confirm'); 
        new_confirm.add( Validate.Presence  ); 
        new_confirm.add( Validate.Confirmation, { match: 'new' , failureMessage: "Debe ser igual al nuevo password." } );

    $(document).ready(function() {
        $('#new').reviewPassword({
            preventWeakSubmit: true
        });
    });
</script>