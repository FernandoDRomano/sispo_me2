<div class="col-xs-12">
    <div class="ibox-content">
        <form action="<?=base_url('hojas_rutas/consultas/exportarAExcel')?>" method="post" id="form_consultas">
            <div class="row">
                <div class="col-xs-3">
                    <div class="form-group">
                        <label for="periodo_desde">Periodo desde</label>
                        <input id="periodo_desde" name="periodo_desde" type="text" class="form-control datepicker" placeholder="Seleccione una fecha" value="<?php if ($periodo_desde){ $f = strtotime($periodo_desde);echo date('d-m-Y',$f );} ?>"  required autocomplete="off" />
                    </div>
                </div>
                <div class="col-xs-3">
                    <div class="form-group">
                        <label for="periodo_hasta">Periodo hasta</label>
                        <input id="periodo_hasta" name="periodo_hasta" type="text" class="form-control datepicker" placeholder="Seleccione una fecha" value="<?php if ($periodo_hasta){ $f = strtotime($periodo_hasta);echo date('d-m-Y',$f );} ?>"  required autocomplete="off" />
                    </div>
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
            <div class="text-right">
                <a class="btn btn-primary pendientes" href="javascript:;" ><i class="fa fa-arrow-down"></i> Pendientes</a>
                <a class="btn btn-success exportarAExcel" href="javascript:;" ><i class="fa fa-arrow-down"> </i>Exportar a Excel</a>
            </div>
            <div>&nbsp;</div>
            <div id="grilla"></div>
        </form>
    </div>

</div>
<form method='post' action='<?php echo base_url('hojas_rutas/Hojas_rutas_alta/exportarHDRPendientes')?>' id='exportAExcel'>
    <input type='hidden' id='excel_desde' name='excel_desde' value="">
    <input type='hidden' id='excel_hasta' name='excel_hasta' value="">
    <input type='hidden' id='excel_sucursal_id' name='excel_sucursal_id' value="">
</form>
<script type="text/javascript">
    $(document).ready(function() {
     //configurar();
     function configurar(){
//        $('#resultado').DataTable( {
//            "scrollY": 400,    
//            "scrollX": 500,
//            "searching": false,
//            "ordering": false,
//            "info": false,
//            "paging": false,
//        } );
        //$('#resultado_filter').hide();
//        $('#resultado_length').hide();
//        $('.dataTables_empty').hide();
        //$('#resultado_info').hide();
        //$('#resultado_paginate').hide();
     }

        $('.pendientes').click(function(event) {
                $('#grilla').html('');
                var other_data = $('#form_consultas').serializeArray();
                var form_data = new FormData();
                $.each(other_data,function(key,input){//alert(input.value);
                    form_data.append(input.name,input.value);
                });

                 $.ajax({
                             url: '<?php echo base_url()."ajax/hojas_rutas/getHDRPendientes" ?>',
                             dataType: 'json',
                             cache: false,
                             contentType: false,
                             processData: false,
                             data: form_data,                
                             type: 'post',
                             success: function(data){
                                 if (data.status){
                                     $('#grilla').html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>No se encontraron resultados.</div>');
                                 }else{
                                      $('#grilla').html('');
                                     var contador = 0;
                                     var cabecera = '<table class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">'+
                                                        '<thead>'+
                                                            '<tr>'+
                                                                '<th><a href="#">HDR ID</a></th>'+
                                                                '<th><a href="#">Cartero</a></th>'+
                                                                '<th><a href="#">Piezas</a></th>'+
                                                                '<th><a href="#">Fecha de Creación</a></th>'+
                                                            '</tr>'+
                                                        '</thead>'+
                                                        '<tbody>';
                                    var body = '<tbody id="body-grilla">';
                                    $('#cantidad_elementos').val(data['grilla'].length);
                                    $.each(data['grilla'], function(index, element) {
                                            body    += 
                                                    '<tr id="resultados" class="nueva"><input type="hidden" name="fila_'+contador+'" value="'+contador+'"/>'+
    //                                                    '<td class="barcodear">'+element['numero']+'</td><input type="hidden" id="numero_'+contador+'" name="numero_'+contador+'" value="'+element['numero']+'"/> '+
                                                        '<td class="">'+element['hdr_id']+'</td>'+
                                                        '<td class="">'+element['apellido_nombre']+'</td>'+
                                                        '<td class="">'+element['piezas']+'</td>'+
                                                        '<td class="">'+element['fecha_creacion']+'</td>'+
                                                    '</tr>';
                                         contador++;
                                     });
                                     var cierre = '</tbody></table>';
                                     $('#grilla').append(cabecera+body); 
                                    configurar();
                                 }
                             }
                         });
              }); 
    });
    
    $('.exportarAExcel').click(function(event) {
        $('#excel_desde').val($('#periodo_desde').val());
        $('#excel_hasta').val($('#periodo_hasta').val());
        $('#excel_sucursal_id').val($('#sucursal_id').val());
        $('#exportAExcel').submit();
    });
</script>