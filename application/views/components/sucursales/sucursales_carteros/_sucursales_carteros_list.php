<div class="col-xs-12">
  <div class="ibox-content">
        <div class="text-right">            
            <?php if($permisos_efectivos->insert==1) { ?> <a href="<?php echo base_url().'sucursales/sucursales_carteros/add/' ?>" class="btn btn-success"><i class="fa fa-plus"></i> Nuevo Cartero</a><?php } ?>
        </div>
    </div>
    <div class="ibox-content">
        <form action="<?php echo base_url() ?>sucursales/sucursales_carteros/filtro" method="post" id="form-filtro">
            <div class="row">
                <div class="col-xs-3">
                    <div class="form-group">
                        <label for="sucursal">Sucursales</label>
                        <select id="sucursal" name="sucursal" class="form-control filtro-group">
                            <option value="">Seleccione</option>
                            <?php foreach ($sucursales as $f) {?>
                                <option value="<?php echo $f->id ?>"><?php echo $f->nombre ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="buscar">Campo Libre</label>
                        <input type="text" id="buscar" name="buscar" class="form-control filtro-group" placeholder="Apellido, Nombre, Direccion, Localidad, Codigo Postal" autocomplete="off">
                    </div>
                </div>
                <div class="col-xs-3">
                    <div class="form-group">
                        <button type="submit" class="btn btn-success" style="margin-top: 23px;">Filtrar</button>
                        <a href="<?php echo base_url() ?>sucursales/sucursales_carteros" class="btn btn-primary" style="margin-top: 23px;"><i class="fa fa-refresh"></i> Reinciar</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="ibox-content">
        <table id="results" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
            <thead>
                <tr>
                    <th><a href="#">Sucursal</a></th>
                    <th><a href="#">Apellido nombre</a></th>
                    <th><a href="#">Datos</a></th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
              <?php foreach ($results as $result) { ?>
                  <tr>
                    <td><?php echo $result->sucursal ?></td>
                    <td>
                      <?php echo $result->apellido_nombre ?><br>
                      Estado: <?php if($result->activo == 1) {echo 'Activo';}else{echo 'Inactivo';} ?>
                    </td>
                    <td>
                      <i class="fa fa-map-marker"></i> <?php echo $result->domicilio ?> (CP: <?php echo $result->codigo_postal ?>)<br>
                      <i class="fa fa-globe"></i> <?php echo $result->localidad ?><br>
                      <i class="fa fa-phone-square"></i> <?php echo str_replace(",", "<br><i class='fa fa-phone-square'></i> ", $result->telefono); ?>
                    </td>
                    <td>
                      <div class="btn-group">
                        <?php if($permisos_efectivos->update==1) { ?><a href="<?php echo base_url().'sucursales/sucursales_carteros/edit/'.$result->id ?>" class="btn btn-success"><i class="fa fa-edit"></i></a><?php } ?>
                        <?php if($permisos_efectivos->delete==1) { ?><a onClick="eleminarRegistro('<?php echo base_url().'sucursales/sucursales_carteros/delete/'.$result->id ?>')" href="#" class="btn btn-danger"><i class="fa fa-trash-o"></i></a><?php } ?>
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
            sucursal: {require_from_group: [1, ".filtro-group"]},
            buscar: {require_from_group: [1, ".filtro-group"]}
        },
        messages: {
            sucursal: "Campo requerido.",
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