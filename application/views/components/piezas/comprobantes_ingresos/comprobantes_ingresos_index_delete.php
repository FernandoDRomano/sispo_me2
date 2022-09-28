<div class="col-xs-12">
    <div class="ibox-content">
        <?php echo form_open(current_url(), array('id'=>"form-comprobante-eliminar")); ?>

            <div id="errores"></div>

            <?php if ($this->session->flashdata('registroOK')): ?>
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    Se guardaron correctamente los datos para el comprobante <span class="barcodear"><?=$this->session->flashdata('registroOK')?></span>
                </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('registro')): ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <strong>Error!</strong> El numero de comprobante <span class="barcodear"><?=$this->session->flashdata('registro')?></span> incorrecto o no está disponible.
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div>
                <div class="row">
                    <div class="col-xs-4">
                        <div class="form-group">
                            <label for="numero">Ingrese el N° de comprobante</label>
                            <input id="numero" name="numero" type="text" class="form-control" placeholder="Numero" autocomplete="off" required />
                        </div>
                    </div>
                    <div class="col-xs-2">
                        <div class="control-group text-right">
                            <div class="controls">
                                <button class="btn btn-success btn-submit" style="margin-top: 23px;"><i class='fa fa-search'></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
            </div>
        <?php echo form_close(); ?>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {

    $("#form-comprobante").validate({
        rules: {
            numero: {required: true}
        },
        messages: {
            numero: "Campo requerido."
        },
        highlight: function(element) {
            $(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function(element) {
            $(element).closest('.form-group').removeClass('has-error');
        },
        errorElement: 'span',
        errorClass: 'help-block',
        errorPlacement: function(error, element) {
            if(element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        }
    });
});
</script>