<div class="col-xs-12">
    <div class="ibox-content">
        <div class="text-right">            
            <a href="<?php echo base_url().'clientes/clientes/add/clientes_ctacte_add' ?>" class="btn btn-success"><i class="fa fa-plus"></i> Nuevo cliente</a>
        </div>
    </div>
    <div class="ibox-content">
        <form action="<?php echo base_url() ?>clientes/clientes/filtro/clientes_ctacte_list" method="post" id="form-filtro">
            <div class="row">
                <div class="col-xs-3">
                    <div class="form-group">
                        <label for="estado">Estado</label>
                        <select id="estado" name="estado" class="form-control filtro-group">
                            <option value="">Seleccione</option>
                            <?php foreach ($estados as $f) {?>
                                <option value="<?php echo $f->id ?>"><?php echo $f->nombre ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-xs-3">
                    <div class="form-group">
                        <label for="tipo">Tipo</label>
                        <select id="tipo" name="tipo" class="form-control filtro-group" >
                            <option value="" disabled="disabled">Seleccione</option>
                            <?php foreach ($tipos as $f) {?>
                                <option value="<?php echo $f->id ?>" <?php echo $f->id == 2?"selected":"disabled='disabled'"?>><?php echo $f->nombre ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="buscar">Campo Libre</label>
                        <input type="text" id="buscar" name="buscar" class="form-control filtro-group" placeholder="Nombre, Nombre de Fantasia, CUIT" autocomplete="off">
                    </div>
                </div>
                <div class="col-xs-12 text-right">
                    <div class="form-group">
                        <button type="submit" class="btn btn-success" style="margin-top: 23px;">Filtrar</button>
                        <a href="<?php echo base_url() ?>clientes/clientes" class="btn btn-primary" style="margin-top: 23px;"><i class="fa fa-refresh"></i> Reinciar</a>
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
                    <th><a href="#">Ubicacion</a></th>
                    <th><a href="#">Telefonos</a></th>
                    <th><a href="#">Observaciones</a></th>
                    <th><a href="#">Ejecutivo comercial</a></th>
                    <th class="col-xs-1">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
              <?php foreach ($results as $result) { ?>
                  <tr>
                    <td>                      
                      <?php echo $result->nombre ?><br>
                      <?php echo $result->nombre_fantasia ?><br>
                      <?php echo $result->estado ?> | <?php echo $result->tipo ?><br>
                      <?php echo $result->iva ?><br>
                      <?php echo $result->cuit ?>
                    </td>
                    <td>
                      <?php echo $result->domicilio ?><br>
                      <?php echo $result->localidad ?><br>
                      <?php echo $result->provincia ?><br>
                      CP: <?php echo $result->codigo_postal ?>
                    </td>
                    <td><i class="fa fa-phone-square"></i> <?php echo str_replace(",", "<br><i class='fa fa-phone-square'></i> ", $result->telefonos); ?></td>
                    <td><?php echo $result->observaciones ?></td>
                    <td><?php echo $this->codegen_model->row('users', 'username', 'id like "'.$result->ejecutivo_comercial.'"')->username ?></td>
                    <td>
                      <div class="btn-group">
                        <a href="<?php echo base_url().'clientes/clientes/edit/'.$result->id.'/null/clientes_ctacte_edit' ?>" class="btn btn-success"><i class="fa fa-edit"></i></a>
                        <?php if($permisos_efectivos->delete==1) { ?><a onClick="eleminarRegistro('<?php echo base_url().'clientes/clientes/delete/'.$result->id ?>')" href="#" class="btn btn-danger"><i class="fa fa-trash-o"></i></a><?php } ?>
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
            estado: {require_from_group: [1, ".filtro-group"]},
            tipo: {require_from_group: [1, ".filtro-group"]},
            buscar: {require_from_group: [1, ".filtro-group"]}
        },
        messages: {
            estado: "Campo requerido.",
            tipo: "Campo requerido.",            
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