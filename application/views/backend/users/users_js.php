<script type="text/javascript">
    var telefono = new LiveValidation('telefono'); telefono.add( Validate.Numericality );
    var celular = new LiveValidation('celular'); celular.add( Validate.Numericality );
    var email = new LiveValidation('email'); email.add( Validate.Email );    

    function validarSi(){
        var Si = $('#username').val();
        var url = '<?php echo base_url()."backend/users/validarSi/" ?>';
        $.ajax({
            type: "POST",
            url: url,
            data: {username: Si},
            cache: false,
            success: function(respuesta){
                if (respuesta == 1) {
                    $('#errores').html('<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Error!</strong> El Si ingresado ya esta registrado.</div>');
                    $('#username').val('').focus();
                }else{
                    $('#errores').html('');
                }
            }
      });
    }

    function validarEmail(){
        var email = $('#email').val();
        var url = '<?php echo base_url()."backend/users/validarEmail/" ?>';
        $.ajax({
            type: "POST",
            url: url,
            data: {email: email},
            cache: false,
            success: function(respuesta){
                if (respuesta == 1) {
                    $('#errores').html('<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Error!</strong> El email ingresado ya esta registrado.</div>');
                    $('#email').val('').focus();
                }else{
                    $('#errores').html('');
                }
            }
      });
    }

    function deleteFoto(id){
      bootbox.confirm('Desea eliminar la imagen?', function(result) {
        if (result === true) {
            var url = '<?php echo base_url()."backend/ajax/deleteFoto" ?>';
            $.ajax({
                  type: "POST",
                  url: url,
                  data: {id: id},
                  cache: false,
                  success: function(){}
            });
        }    
      });
    }

    $(document).ready(function() {
        $('#password').reviewPassword({
            preventWeakSubmit: true
        });
    });
</script>