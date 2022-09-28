<div class="col-xs-12">
    <div class="ibox-content">
    	<a class="btn btn-primary aceptar" href="javascript:;" style="visibility:hidden"><i class="fa fa-arrow-down"></i> Imprimir</a>
    	<br/>
        <div id="div_pdf"></div>
        <div id="div_excel"></div>
        <br/>
        <input type="hidden" id="rendicion_id" name="rendicion_id" value="<?php echo $rendicion_id?>">
        <div class="table-responsive">   
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Codigo barra</th>
                        <th>Codigo barra externo</th>
                        <th>Cantidad</th>
                        <th>Usuario</th>
                        <th>Comprobante</th>
                        <th>Servicio</th>
                        <th>Estado</th>
                        <th>Tipo</th>
                        <th>Hoja de ruta</th>
                        <th>Rendicion</th>
                        <th>Despacho</th>
                        <th>Destinatario</th>
                        <th>Domicilio</th>
                        <th>Codigo postal</th>
                        <th>Documento</th>
                        <th>Recibió</th>
                        <th>Vínculo</th>
                        <th>Datos varios</th>
                        <th>Fecha de cambio estado</th>
                        <th>Fecha de rendicion</th>
                        <th>Fecha de Creacion</th>
                        <th>Fecha de Actualizacion</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($piezas as $f) { ?>
                        <tr>                                
                            <td><div rel='tooltip' data-original-title='<h1><span class="barcode"><?php echo $f->barcode ?></span><br><?php echo $f->barcode ?></h1>'><?php echo $f->barcode ?></div></td>
                            <td><div rel='tooltip' data-original-title="<h1><span class='barcode'><?php echo $f->barcode_externo; ?></span><br><?php echo $f->barcode_externo ?></h1>"><?php echo $f->barcode_externo ?></div></td>   
                            <td><?php echo $f->cantidad ?></td>   
                            <td><?php echo $f->usuario_apellido.', '.$f->usuario_nombre ?></td>   
                            <td><?php echo $f->comprobante ?></td>   
                            <td><?php echo $f->servicio ?></td>   
                            <td><?php echo $f->estado ?></td>   
                            <td><?php echo $f->tipo ?></td>   
                            <td><?php echo $f->hoja_ruta_id ?></td>   
                            <td><?php echo $f->rendicion_id ?></td>   
                            <td><?php echo $f->despacho_id ?></td>   
                            <td><?php echo $f->destinatario ?></td>   
                            <td><?php echo $f->domicilio ?></td>   
                            <td><?php echo $f->codigo_postal ?></td>  
                            <td><?php echo $f->documento ?></td>  
                            <td><?php echo $f->recibio ?></td>  
                            <td><?php echo $f->vinculo ?></td>  
                            <td><?php echo $f->datos_varios ?></td>   
                            <td><?php echo $f->fecha_estado ?></td>   
                            <td><?php echo $f->fecha_rendicion ?></td>   
                            <td><?php echo $f->create ?></td>   
                            <td><?php echo $f->update ?></td>   
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<form method="post" action="<?php echo base_url('piezas/rendiciones/exportar')?>" id="exportar">
    <input type='hidden' id='exportar_rendicion_id' name='exportar_rendicion_id' value="">
    <input type='hidden' id='exportar_impresion_tipo' name='exportar_impresion_tipo' value="">
    <input type='hidden' id='exportar_limite_inferior' name='exportar_limite_inferior' value="">
</form>   
<style type="text/css">
table {
    font-size: 12px;
}

.table-responsive {
  width: 100%;
  margin-bottom: 15px;
  overflow-x: auto;
  overflow-y: hidden;
  -webkit-overflow-scrolling: touch;
  -ms-overflow-style: -ms-autohiding-scrollbar;
  border: 1px solid #ddd;
}

.table-responsive>.table>thead>tr>th, .table-responsive>.table>tbody>tr>th, .table-responsive>.table>tfoot>tr>th, .table-responsive>.table>thead>tr>td, .table-responsive>.table>tbody>tr>td, .table-responsive>.table>tfoot>tr>td {
  white-space: nowrap;
}

.fancybox-skin{
    height: 350px;
}

.fancybox-opened{
    height: 350px;
}

.fancybox-inner{
    height: 350px;
}

.fancybox-wrap fancybox-desktop fancybox-type-iframe fancybox-opened{
    height: 350px;
}
</style>
<script type="text/javascript">
$(document).ready(function() {
    $('.fancybox-skin').removeAttr('style');
    $('.aceptar').prop("style").visibility="visible"
    //Traigo los links para la impresion si es que los tuviera
    $('.aceptar').click(function(event) {
        //Traigo los links para la impresion si es que los tuviera
        $.getJSON('<?=base_url()?>' + 'ajax/rendiciones/getLinksImpresionRendiciones/' + $('#rendicion_id').val(), function(data) {
            //$("#estado_id").find("option").remove();
            var links_pdf = 'PDF: ';
            var links_excel = 'Excel: ';
            if(!data.status){
                var i = 0;
                //var indices = cargados_aux.split(',');
                $.each(data['links'], function(key, val) {
                    links_pdf = links_pdf + '<a href="javascript:imprimir_pdf('+val+');" >PDF('+i+')</a>  ';
                    links_excel = links_excel + '<a href="javascript:imprimir_excel('+val+');" >XLS('+i+')</a>  ';
                    i++;
                });
            }

            $("#div_pdf").append(links_pdf);
            $("#div_excel").append(links_excel);
        });
    });
});

   
    function imprimir_pdf(pdf_limite_inferior){
        $('#exportar_impresion_tipo').val('pdf');
        $('#exportar_rendicion_id').val($('#rendicion_id').val());
        $('#exportar_limite_inferior').val(pdf_limite_inferior);
        $('#exportar').attr('target', '_blank').submit();
    }
  
    function imprimir_excel(pdf_limite_inferior){
          $('#exportar_impresion_tipo').val('excel');
          $('#exportar_rendicion_id').val($('#rendicion_id').val());
          $('#exportar_imite_inferior').val(pdf_limite_inferior);
          $('#exportar').attr('target', '_blank').submit();
      }
</script>