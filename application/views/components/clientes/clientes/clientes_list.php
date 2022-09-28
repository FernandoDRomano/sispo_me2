<!--
    https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css
    https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css
-->
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
            <?php if($permisos_efectivos->insert==1) { ?> <a href="<?php echo base_url().'clientes/clientes/add/' ?>" class="btn btn-success"><i class="fa fa-plus"></i> Nuevo cliente</a><?php } ?>
        </div>
    </div>
    <div class="ibox-content">
        <form action="<?php echo base_url() ?>clientes/clientes/filtro" method="post" id="form-filtro">
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
                        <select id="tipo" name="tipo" class="form-control filtro-group">
                            <option value="">Seleccione</option>
                            <?php foreach ($tipos as $f) {?>
                                <option value="<?php echo $f->id ?>"><?php echo $f->nombre ?></option>
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
        <!-- id="results" -->    
        <table id="clientes" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered nowrap order-column">
            <thead>
                <tr>
                    <th><a href="#">Cliente</a></th>
                    <th><a href="#">Usuario</a></th>
                    <th><a href="#">Email</a></th>
                    <th><a href="#">Ubicacion</a></th>
                    <th><a href="#">Fecha de Ingreso</a></th>
                    <th><a href="#">Telefonos</a></th>
                    <th><a href="#">Observaciones</a></th>
                    <th><a href="#">Ejecutivo comercial</a></th>
                    <th><a href="#">Acciones</a></th>
                    <!-- <th class="col-xs-1">&nbsp;</th> -->
                </tr>
            </thead>
            <tbody>
              <?php foreach ($results as $result) { ?>                  
                   <tr>
                    <td style="width: 150px;">                      
                      <?php echo $result->nombre ?> | <br>
                      <?php echo $result->estado ?> | <?php echo $result->tipo ?><br>
                      <?php echo $result->iva ?><br>
                      <?php echo $result->cuit ?>
                    </td>
                    <td>
                      <?php echo $result->username ?>
                    </td>
                    <td>
                      <?php echo $result->email ?>
                    </td>
                    <td>
                      <?php echo "$result->domicilio $result->altura" ?> <br>
                      CP: <?php echo $result->codigo_postal ?> <br>
                      <?php echo $result->localidadNombre ?> <br>
                      <?php echo $result->provinciaNombre ?> <br>
                      <?php echo $result->paisNombre ?> <br>
                    </td>
                    <td>
                        <?php  
                            // Creating timestamp from given date
                            $timestamp = strtotime($result->fecha_ingreso );
                             
                            // Creating new date format from that timestamp
                            $new_date = date("d-m-Y", $timestamp);
                            echo $new_date; 
                        ?>
                    </td>
                    <td><i class="fa fa-phone-square"></i> <?php echo str_replace(",", "<br><i class='fa fa-phone-square'></i> ", $result->telefonos); ?></td>
                    <td><?php echo $result->observaciones ?></td>
                    <td><?php echo $this->codegen_model->row('users', 'username', 'id like "'.$result->ejecutivo_comercial.'"')->username ?></td>
                    <td>
                      <div class="btn-group">
                        <?php if($permisos_efectivos->update==1) { ?>
                            <?php if($result->cliente_estado_id != 2) { ?>
                                <a href="<?php echo base_url().'clientes/clientes/edit/'.$result->id ?>" class="btn btn-success"><i class="fa fa-edit"></i></a>
                            <?php } ?>
                        <?php } ?>
                        <?php if($permisos_efectivos->delete==1) { ?>
                            <?php if($result->cliente_estado_id != 2) { ?>
                                <a onClick="eleminarRegistro('<?php echo base_url().'clientes/clientes/delete/'.$result->id ?>')" href="#" class="btn btn-danger"><i class="fa fa-trash-o"></i></a>
                            <?php } else { ?>
                                <a onClick="activarCliente('<?php echo base_url().'clientes/clientes/changeStatus/'.$result->id ?>')" href="#" class="btn btn-primary"><i class="fa fa-check"></i></a>
                            <?php } ?>
                        <?php } ?>
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

function activarCliente(link){
    bootbox.confirm("Desea activar este cliente?", function(result) {
        if (result === true) {
            $.ajax({
                type: "GET",
                url: link,
                data: {},
                cache: false,
                success: function(){window.location.reload();}
            });
        }
    });
}  

$(document).ready(function() {

    $('#clientes').DataTable( {
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