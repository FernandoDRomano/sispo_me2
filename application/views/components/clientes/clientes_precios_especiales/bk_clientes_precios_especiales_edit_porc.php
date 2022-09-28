<div class="col-xs-12">
    <div class="ibox-content">
        <form action="<?php echo base_url() ?>clientes/clientes_precios_especiales/filtroPreciosEspecialesPorcentajes" method="post" id="form-filtro">
                <div class="row">
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label for="cliente_id">Cliente</label>
                            <select id="cliente_id" name="cliente_id" class="form-control chosen-select" >
                                <?php foreach ($clientes as $f) { ?>
                                    <option value="<?php echo $f->id?>" <?php echo $f->id == $cliente_id?"selected":""?> ><?php echo $f->nombre ?></option>
                                <?php } ?>
                            </select>
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
        <form action="<?php echo base_url() ?>clientes/clientes_precios_especiales/guardarPreciosEspecialesPorcentajes" method="post" id="form-filtro">                            
            <div class="row">
                    <input type="hidden" id="cliente_id" name="cliente_id" value="<?php if($cliente_id){echo $cliente_id;}?>"/>
                    <div class="col-xs-3">
                        <div class="form-group">
                            <label for="precio">Porcentaje %<span class="required">*</span></label>
                            <input  id="porcentaje" name="porcentaje" type="number" class="form-control" placeholder="porcentaje" step="0.01" min="" />
                        </div>
                    </div>
                    <div class="col-xs-3">
                        <div class="form-group">
                        <label for="fecha_vto">Fecha Vto.</label>
                        <input  id="fecha_vto" name="fecha_vto" type="text" class="form-control datepicker" placeholder="Fecha Vto." value="<?php echo date('d-m-Y') ?>" required autocomplete="off" />
                        </div>
                  </div>
                    <div class="col-xs-3">
                        <div class="form-group">
                            <button type="submit" class="btn btn-success" style="margin-top: 23px;">Guardar</button>
                            <a class="btn btn-danger" style="margin-top: 23px;" href="<?php echo base_url().'backend/dashboard'; ?>"><i class="fa fa-arrow-circle-left"></i> Volver</a>
                        </div>
                    </div>
                </div>
            

            <table id="results3" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
                <thead>
                    <tr>
                        <th><a href="#">Cliente</a></th>
                        <th><a href="#">Grupo</a></th>
                        <th><a href="#">Servicio</a></th>
                        <th><a href="#">Precio</a></th>
                        <th><input type="checkbox" id="check_all" name="check_all"/></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($results as $result) { /*var_dump($result);die;*/?>
                    <tr>
                        <td><?php echo $result->cliente ?></td>
                        <td><?php echo $result->grupo ?></td>
                        <td><?php echo $result->servicio ?></td>
                        <td>$ <?php echo $result->precio ?></td>
                        <td class="col-xs-1">
                        <input type="checkbox"  name="servicios_id[]" value="<?php echo $result->servicio_id ?>"/>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </form>
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

    $('#check_all').click(function () {    
        $('input:checkbox').prop('checked', this.checked);    
    });
});
</script>

    
<script type="text/javascript">
    function getServicios(){
        var url = '<?php echo base_url() ?>' + 'ajax/servicios/servicios/' + $('#grupo_servicio_id').val();
        $.getJSON(url, function(data) {
            $("#servicio_id").find("option").remove();
            var options = '';

            if(!data.status){
                $.each(data, function(key, val) {
                    options = options + "<option value='"+val.id+"'>"+ val.nombre +"</option>";
                });              
            }else{
                options = options + "<option value='0' disabled>Sin resultados</option>";
            }

            $("#servicio_id").append(options);
            $("#servicio_id").trigger("liszt:updated");
        });
    }

    $(document).ready(function() {
        getServicios(); 

        $('#grupo_servicio_id').change(function(event) {
            getServicios();             
        });
    });
</script>