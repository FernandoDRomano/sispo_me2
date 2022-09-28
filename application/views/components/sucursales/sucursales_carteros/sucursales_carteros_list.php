<link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css" rel="stylesheet">

<style>
    .buttons-excel{
        color: white!important;
        background-color: #1D6F42!important;
        border-color: #1D6F42!important;
    }

    .dataTables_scroll{
        margin-bottom: 1.5rem;
    }
</style>

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
                <div class="col-xs-3">
                    <div class="form-group">
                        <label for="tipo_de_cartero">Tipo de Cartero</label>
                        <select id="cartero_tipo_id" name="cartero_tipo_id" class="form-control filtro-group">
                            <option value="">Seleccione</option>
                            <?php foreach ($carteros_tipos as $f) {?>
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
        <table id="carteros" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered nowrap order-column">
            <thead>
                <tr>
                    <th><a href="#">Sucursal</a></th>
                    <th><a href="#">Apellido nombre</a></th>
                    <th><a href="#">Datos de contacto</a></th>
                    <th><a href="#">Datos Fiscales</a></th>
                    <th><a href="#">Tipo cartero</a></th>
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
                      <i class="fa fa-map-marker"></i> <?php echo $result->domicilio ?> <?php echo $result->altura ?> <br>
                      <i class="fa fa-globe"></i> <?php echo strtoupper($result->localidad) ?> (CP: <?php echo strtoupper($result->codigo_postal) ?>), <?php echo strtoupper($result->provincia) ?>, <?php echo strtoupper($result->pais) ?><br>
                      <i class="fa fa-phone-square"></i> <?php echo str_replace(",", "<br><i class='fa fa-phone-square'></i> ", $result->telefono); ?> <br>
                      <i class="fa fa-envelope"></i> <?php echo $result->email ?>
                    </td>
                    <td>
                        Tipo: <?php echo $result->condicionFiscal ?> | <br>
                        DNI/CUIT/CUIL: <?php echo $result->dni_cuit_cuil ?>
                    </td>
                    <td><?php echo $result->cartero_tipo ?></td>
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

<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>



<script type="text/javascript">
$(document).ready(function() {

    $('#carteros').DataTable( {
        scrollX: true,
        dom: 'Bfrtip',
        buttons: [
            //'csv', 
            'excel'
        ],
        language: {
                "info": "_TOTAL_ registros",
                "search": "Buscar",
                "paginate": {
                    "first": "Primero",
                    "last": "Último",
                    "next": "Siguiente",
                    "previous": "Anterior"
                },
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "emptyTable": "No hay datos",
                "zeroRecords": "No hay coincidencias",
                "infoEmpty": "",
                "infoFiltered": ""
            }
    } );


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