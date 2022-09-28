<div class="col-xs-12">
    <div class="ibox-content">
        <div class="text-right">            
            <?php if($permisos_efectivos->insert==1) { ?> <a href="<?php echo base_url().'clientes/clientes_precios_especiales/add/' ?>" class="btn btn-success"><i class="fa fa-plus"></i> Nuevo Precio</a><?php } ?>
        </div>
    </div>
    <div class="ibox-content">
        <form action="<?php echo base_url() ?>clientes/clientes_precios_especiales/filtro" method="post" id="form-filtro">
            <div class="row">
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="grupo">Grupo</label>
                        <select id="grupo" name="grupo" class="form-control filtro-group">
                            <option value="">Seleccione</option>
                            <?php foreach ($grupos as $f) {?>
                                <option value="<?php echo $f->id ?>"><?php echo $f->nombre ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="servicio">Servicio</label>
                        <select id="servicio" name="servicio" class="form-control filtro-group">
                            <option value="">Seleccione</option>
                            <?php foreach ($servicios as $f) {?>
                                <option value="<?php echo $f->id ?>"><?php echo $f->nombre ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="activo">Activo</label>
                        <select id="activo" name="activo" class="form-control filtro-group">
                            <option value="">Seleccione</option>
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-9">
                    <div class="form-group">
                        <label for="buscar">Campo Libre</label>
                        <input type="text" id="buscar" name="buscar" class="form-control filtro-group" placeholder="Cliente, Nombre del precio" autocomplete="off">
                    </div>
                </div>
                <div class="col-xs-3">
                    <div class="form-group">
                        <button type="submit" class="btn btn-success" style="margin-top: 23px;">Filtrar</button>
                        <a href="<?php echo base_url() ?>clientes/clientes_precios_especiales" class="btn btn-primary" style="margin-top: 23px;"><i class="fa fa-refresh"></i> Reinciar</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="ibox-content">
        <table id="results" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
            <thead>
                <tr>
                    <th><a href="#">Cliente</a></th>
                    <th><a href="#">Grupo</a></th>
                    <th><a href="#">Servicio</a></th>
                    <th><a href="#">Nombre</a></th>
                    <th><a href="#">Precio</a></th>
                    <th><a href="#">Activo</a></th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
              <?php foreach ($results as $result) { ?>
                  <tr>
                    <td><?php echo $result->cliente ?></td>
                    <td><?php echo $result->grupo ?></td>
                    <td><?php echo $result->servicio ?></td>
                    <td><?php echo $result->nombre ?></td>
                    <td>$ <?php echo $result->precio ?></td>
                    <td><?php if($result->activo == 1) {echo '<i class="fa fa-check"></i>';}else{echo '<i class="fa fa-times"></i>';} ?></td>
                    <td>
                      <div class="btn-group">
                        <?php if($permisos_efectivos->update==1) { ?><a href="<?php echo base_url().'clientes/clientes_precios_especiales/edit/'.$result->id ?>" class="btn btn-success"><i class="fa fa-edit"></i></a><?php } ?>
                        <?php if($permisos_efectivos->delete==1) { ?><a onClick="eleminarRegistro('<?php echo base_url().'clientes/clientes_precios_especiales/delete/'.$result->id ?>')" href="#" class="btn btn-danger"><i class="fa fa-trash-o"></i></a><?php } ?>
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
            grupo: {require_from_group: [1, ".filtro-group"]},
            servicio: {require_from_group: [1, ".filtro-group"]},
            buscar: {require_from_group: [1, ".filtro-group"]}
        },
        messages: {
            grupo: "Campo requerido.",
            servicio: "Campo requerido.",            
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