<div class="col-xs-12">
    <div class="ibox-content">
        <form action="<?php echo base_url() ?>clientes/clientes_precios_especiales_historico/filtro" method="post" id="form-filtro">
                <div class="row">
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label for="cliente_id">Cliente</label>
                            <select id="cliente_id" name="cliente_id" class="form-control chosen-select" >
                            <option value="-1" <?php echo isset($cliente_id) && "-1" == $cliente_id?"selected":""?>> Seleccionar un Cliente </option>
                                <?php foreach ($clientes as $f) { ?>
                                    <option value="<?php echo $f->id?>" <?php echo isset($cliente_id) && $f->id == $cliente_id?"selected":""?> ><?php echo $f->nombre ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-3">
                        <div class="form-group">
                            <button type="submit" class="btn btn-success" style="margin-top: 23px;">Filtrar</button>
                            <a href="<?php echo base_url() ?>clientes/clientes_precios_especiales_historico" class="btn btn-primary" style="margin-top: 23px;"><i class="fa fa-refresh"></i> Reinciar</a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-3">
                            <div class="form-group">
                            <label for="fecha_desde">Fecha Desde.</label>
                            <input  id="fecha_desde" name="fecha_desde" type="text" class="form-control datepicker data-date-end-date='0d'" placeholder="Fecha Desde." value="<?php echo isset($fecha_desde)?$fecha_desde: date('d-m-Y') ?>" required autocomplete="off" />
                            </div>
                    </div>
                    <div class="col-xs-3">
                            <div class="form-group">
                            <label for="fecha_hasta">Fecha Hasta.</label>
                            <input  id="fecha_hasta" name="fecha_hasta" type="text" class="form-control datepicker data-date-end-date='0d'" placeholder="Fecha Hasta." value="<?php echo isset($fecha_hasta)?$fecha_hasta: date('d-m-Y')?>" required autocomplete="off" />
                            </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label for="servicio_id">Servicio</label>
                            <select id="servicio_id" name="servicio_id" class="form-control chosen-select" >
                                <option value="-1" <?php echo isset($servicio_id) && "-1" == $servicio_id?"selected":""?>> Seleccionar un Servicio </option>
                                <?php foreach ($servicios as $f) { ?>
                                    <option value="<?php echo $f->id?>" <?php echo  isset($cliente_id) && $f->id  == $servicio_id?"selected":""?> ><?php echo $f->nombre ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
        </form>
        <div class="row">
            <div class="col-xs-12" style="text-align: right;">
                <a class="btn btn-primary exportarAExcel button" href="javascript:;" ><i class="fa fa-arrow-down"> </i>Exportar a Excel</a>
            </div>
        </div>
        <form action="<?php echo base_url() ?>clientes/clientes_precios_especiales/guardarPreciosEspecialesPorcentajes" method="post" id="form-filtro">   
            

            <table id="results3" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
                <thead>
                    <tr>
                        <th><a href="#">Cliente</a></th>
                        <th><a href="#">Servicio</a></th>
                        <th><a href="#">Fecha Vto.</a></th>
                        <th><a href="#">Ultima Actualizacion</a></th>
                        <th><a href="#">Precio Anterior</a></th>
                        <th><a href="#">Precio Nuevo</a></th>
                        <th><a href="#">Usuario</a></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($results as $result) { /*var_dump($result);die;*/?>
                    <tr>
                        <td><?php echo $result->cliente ?></td>
                        <td><?php echo $result->servicio ?></td>
                        <td><?php echo $result->fecha_vto ?></td>
                        <td><?php echo $result->ultima_actualizacion ?></td>
                        <td><?php echo $result->precio_anterior ?></td>
                        <td><?php echo $result->precio_nuevo ?></td>
                        <td><?php echo $result->username ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </form>
    </div>
</div>
<form method='post' action='<?php echo base_url('clientes/clientes_precios_especiales_historico/exportarAExcel')?>' id='exportAExcel'>
    <input type='hidden' id='excel_desde' name='excel_desde' value="">
    <input type='hidden' id='excel_hasta' name='excel_hasta' value="">
    <input type='hidden' id='excel_servicio_id' name='excel_servicio_id' value="">
    <input type='hidden' id='excel_cliente_id' name='excel_cliente_id' value="">
    <input type='hidden' id='excel_servicios_list' name='excel_servicios_list' value="">
</form>
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

    $('#check_all').click(function () {    
        $('input:checkbox').prop('checked', this.checked);    
    });
});
</script>

    
<script type="text/javascript">
    $(document).ready(function() {

   $('#cliente_id').change(function(event) {
        var url = '<?php echo base_url() ?>' + 'ajax/servicios/getServiciosPorCliente/' + $('#cliente_id').val();
        $.getJSON(url, function(data) {
            $("#servicio_id").find("option").remove();
            
            var options = '';

            if(!data.status){
                options = options + "<option value='-1' > Seleccionar un Servicio </option>";
                $.each(data, function(key, val) {
                    options = options + "<option value='"+val.id+"'>"+ val.nombre +"</option>";
                });              
            }else{
                options = options + "<option value='0' disabled> Sin resultados </option>";
            }

            $("#servicio_id").append(options);
            $("#servicio_id").trigger("chosen:updated");
        });
        
    });

    $('.exportarAExcel').click(function(event) {
        $('#excel_desde').val($('#fecha_desde').val());
        $('#excel_hasta').val($('#fecha_hasta').val());
        $('#excel_servicio_id').val($('#servicio_id').val());
        $('#excel_cliente_id').val($('#cliente_id').val());
         $('#exportAExcel').submit();
    });
});

 /*   var newdate = new Date();

newdate.setDate(newdate.getDate() + 182); // minus the date


var nd = new Date(newdate);

alert(nd.getFullYear());
alert(nd.getMonth());
alert(nd.getDate());
    var endYear = new Date(nd.getFullYear(), nd.getMonth(), nd.getDate());
    alert(endYear);
    $( "#fecha_vto" ).datepicker( "option", "maxDate", "+1m" );*/
</script>