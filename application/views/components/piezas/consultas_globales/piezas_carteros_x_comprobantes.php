<div class="col-xs-12">
    <div class="ibox-content">
        <form action="<?php echo base_url() ?>consultas/consulta_piezas_carteros/filtro" method="post" id="form-filtro">
            <div class="row">
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="comprobante">Comprobante</label>
                        <input type="text" id="comprobante_ingreso" name="comprobante_ingreso" class="form-control filtro-group"  autocomplete="off" value="<?=$comprobante_ingreso?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-3">
                        <div class="form-group">
                          <label for="sucursal">Sucursal</label>
                          <select id="sucursal_id" name="sucursal_id" class="form-control filtro-group">
                            <?php foreach ($sucursales as $sucursal): ?>
                              <option value="<?=$sucursal->id ?>"<?=$sucursal_id == $sucursal->id ? 'selected="selected"' : ''?>><?=$sucursal->nombre ?></option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                    </div>
                </div>
                <div class="col-xs-3">
                    <div class="form-group">
                        <button type="submit" class="btn btn-success" style="margin-top: 23px;">Filtrar</button>
                        <a href="<?php echo base_url() ?>consultas/consulta_piezas_carteros" class="btn btn-primary" style="margin-top: 23px;"><i class="fa fa-refresh"></i> Reinciar</a>
                    </div>
                </div>
                <div class="text-right">
                    <a class="btn btn-success exportarAExcel" href="javascript:;" ><i class="fa fa-arrow-down"> </i>Exportar a Excel</a>
                </div>
                
            </div>
        </form>
    </div>
    <div class="ibox-content">
        <table id="results" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
            <thead>
                <tr>
                    <th><a href="#">Suc. Cartero</a></th>
                    <th><a href="#">Fecha HDR</a></th>
                    <th><a href="#">HDR</a></th>
                    <th><a href="#">Cartero</a></th>
                    <th><a href="#">Servicio</a></th>
                    <th><a href="#">C.I.</a></th>
                    <th><a href="#">Piezas</a></th>
                </tr>
            </thead>
            <tbody>
              <?php foreach ($results as $result) { ?>
                  <tr>
                    <td>
                        <?php echo $result->sucursal_cartero ?>
                    </td>
                    <td>
                        <?php echo $result->fecha_hdr ?>
                    </td>
                    <td>
                        <?php echo $result->hdr_id ?>
                    </td>
                    <td>
                      <?php echo $result->cartero ?><br>
                    </td>
                    <td>
                      <?php echo $result->servicio ?>
                    </td>
                    <td>
                        <?php echo $result->numero ?>
                    </td>
                    <td>
                        <a target="_blank" href="<?php echo base_url('hojas_rutas/hojas_rutas_alta/hoja/'.$result->hdr_id.'#tab-1')?>"><?php echo $result->piezas ?></a>
                    </td>
                  </tr>
              <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<form method='post' action='<?php echo base_url('consultas/consulta_piezas_carteros/exportarPiezasXCarterosExcel')?>' id='exportAExcel'>
    <input type='hidden' id='excel_comprobante_numero' name='excel_comprobante_numero' value="">
    <input type='hidden' id='excel_sucursal_id' name='excel_sucursal_id' value="">
</form>
<script type="text/javascript">
$(document).ready(function() {
    $("#form-filtro").validate({
        rules: {
            comprobante_ingreso_id: {require_from_group: [1, ".filtro-group"]},
        },
        messages: {
            comprobante_ingreso: "Campo requerido.",
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
    
    $('.exportarAExcel').click(function(event) {
        $('#excel_comprobante_numero').val($('#comprobante_ingreso').val());
        $('#excel_sucursal_id').val($('#sucursal_id').val());
        $('#exportAExcel').submit();
    });
});
</script>