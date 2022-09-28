<div class="col-xs-12">
    <div class="ibox-content">
        <form action="<?=base_url('hojas_rutas/consultas/exportarAExcel')?>" method="post" id="form_hdr_por_distribuidor">
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
<!--            <div class="row">
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
            </div>-->
            <div class="text-right">
                <a class="btn btn-primary consultar" href="javascript:;" ><i class="fa fa-search"></i>Consultar</a>
                <a class="btn btn-success exportarAExcel" href="javascript:;" ><i class="fa fa-arrow-down"> </i>Exportar a Excel</a>
            </div>
            <div>&nbsp;</div>
            <div id="grilla"></div>
        </form>
    </div>
</div>
<form method='post' action='<?php echo base_url('hojas_rutas/consultas_hojas_rutas/exportarHDRDistribuidorAExcel')?>' id='exportAExcel'>
    <input type='hidden' id='excel_desde' name='excel_desde' value="">
    <input type='hidden' id='excel_hasta' name='excel_hasta' value="">
</form>
<script type="text/javascript">
$(document).ready(function() {
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

        $('.consultar').click(function(event) {
                $('#grilla').html('');
                var other_data = $('#form_hdr_por_distribuidor').serializeArray();
                var form_data = new FormData();
                $.each(other_data,function(key,input){//alert(input.value);
                    form_data.append(input.name,input.value);
                });

                 $.ajax({
                             url: '<?php echo base_url()."ajax/hojas_rutas/getHDRXDistribuidor/4" ?>',//sin finishing
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
                                                                '<th><a href="#">Nº Hoja de Ruta</a></th>'+
                                                                '<th><a href="#">Distribuidor</a></th>'+
                                                                '<th><a href="#">Fecha Creación</a></th>'+
                                                                '<th><a href="#">Cliente</a></th>'+
                                                                '<th><a href="#">Servicio</a></th>'+
                                                                '<th><a href="#">Sucursal</a></th>'+
                                                                '<th><a href="#">Cantidad</a></th>'+
                                                            '</tr>'+
                                                        '</thead>'+
                                                        '<tbody>';
                                    var body = '<tbody id="body-grilla">';
                                    $('#cantidad_elementos').val(data['grilla'].length);
                                    $.each(data['grilla'], function(index, element) {
                                            body    += 
                                                    '<tr id="resultados" class="nueva"><input type="hidden" name="fila_'+contador+'" value="'+contador+'"/>'+
                                                        '<td class="">'+element['hoja_ruta_id']+'</td>'+'</td><input type="hidden" id="fecha_ingreso_'+contador+'" name="fecha_ingreso_'+contador+'" value="'+element['fecha_ingreso']+'"/> '+
                                                        '<td class="">'+element['apellido_nombre']+'</td>'+'</td><input type="hidden" id="pieza_id_'+contador+'" name="pieza_id_'+contador+'" value="'+element['pieza_id']+'"/> '+
                                                        '<td class="">'+element['fecha_creacion']+'</td>'+'</td><input type="hidden" id="barcode_externo_'+contador+'" name="barcode_externo_'+contador+'" value="'+element['barcode_externo']+'"/> '+
                                                        '<td class="">'+element['cliente']+'</td>'+'</td><input type="hidden" id="barcode_externo_'+contador+'" name="barcode_externo_'+contador+'" value="'+element['barcode_externo']+'"/> '+
                                                        '<td class="">'+element['servicio']+'</td>'+'</td><input type="hidden" id="barcode_externo_'+contador+'" name="barcode_externo_'+contador+'" value="'+element['barcode_externo']+'"/> '+
                                                        '<td class="">'+element['sucursal']+'</td>'+'</td><input type="hidden" id="barcode_externo_'+contador+'" name="barcode_externo_'+contador+'" value="'+element['barcode_externo']+'"/> '+
                                                        '<td class="">'+element['cantidad']+'</td>'+'</td><input type="hidden" id="barcode_externo_'+contador+'" name="barcode_externo_'+contador+'" value="'+element['barcode_externo']+'"/> '+
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

        $('.exportarAExcel').click(function(event) {
            $('#excel_desde').val($('#periodo_desde').val());
            $('#excel_hasta').val($('#periodo_hasta').val());
            $('#exportAExcel').submit();
        });
    });
</script>