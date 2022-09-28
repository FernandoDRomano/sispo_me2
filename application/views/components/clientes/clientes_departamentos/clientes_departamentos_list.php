<div class="col-xs-12">
    <div class="ibox-content">
        <div class="text-right">            
            <?php if($permisos_efectivos->insert==1) { ?> <a href="<?php echo base_url().'clientes/clientes_departamentos/add/' ?>" class="btn btn-success"><i class="fa fa-plus"></i> Nuevo Departamento</a><?php } ?>
        </div>
    </div>
    <div class="ibox-content">
        <form action="<?php echo base_url() ?>clientes/clientes_departamentos/filtro" method="post" id="form-filtro">
            <div class="row">
                <div class="col-xs-9">
                    <div class="form-group">
                        <label for="buscar">Campo Libre</label>
                        <input type="text" id="buscar" name="buscar" class="form-control filtro-group" placeholder="Cliente, DPTO, nombre de contacto" autocomplete="off">
                    </div>
                </div>
                <div class="col-xs-3 text-right">
                    <div class="form-group">
                        <button type="submit" class="btn btn-success" style="margin-top: 23px;">Filtrar</button>
                        <a href="<?php echo base_url() ?>clientes/clientes_departamentos" class="btn btn-primary" style="margin-top: 23px;"><i class="fa fa-refresh"></i> Reinciar</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="ibox-content">
        <table id="results" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
            <thead>
                <tr>
                    <th><a href="#">Cliente / DPTO</a></th>
                    <th><a href="#">Nombre contacto</a></th>
                    <th><a href="#">Telefonos</a></th>
                    <th><a href="#">Emails</a></th>
                    <th class="col-xs-1">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
              <?php foreach ($results as $result) { ?>
                  <tr>
                    <td>
                      Cliente: <?php echo $result->cliente ?><br>
                      DPTO: <?php echo $result->nombre ?>
                    </td>
                    <td><?php echo $result->nombre_contacto ?></td>
                    <td><i class="fa fa-phone-square"></i> <?php echo str_replace(",", "<br><i class='fa fa-phone-square'></i> ", $result->telefonos); ?></td>
                    <td><i class="fa fa-envelope"></i> <?php echo str_replace(",", "<br><i class='fa fa-envelope'></i> ", $result->emails); ?></td>
                    <td>
                      <div class="btn-group">
                        <?php if($permisos_efectivos->update==1) { ?><a href="<?php echo base_url().'clientes/clientes_departamentos/edit/'.$result->id ?>" class="btn btn-success"><i class="fa fa-edit"></i></a><?php } ?>
                        <?php if($permisos_efectivos->delete==1) { ?><a onClick="eleminarRegistro('<?php echo base_url().'clientes/clientes_departamentos/delete/'.$result->id ?>')" href="#" class="btn btn-danger"><i class="fa fa-trash-o"></i></a><?php } ?>
                      </div>
                    </td>
                  </tr>
              <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $("#form-filtro").validate({
        rules: {
            buscar: {required: true}
        },
        messages: {      
            buscar: "Campo requerido.",
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