<div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div id="errores_popup"></div>
        <div class="col-xs-12" style="background: #FFF">
            <div class="ibox-content">
                <?php     
                        echo form_open(current_url(), array('id'=>"form-comprobantes-verificar"));
                        echo form_hidden('enviar_form','1');
//                        echo form_hidden('cliente_id',$cliente_id);
                ?>
                
                <input type='hidden' id='comprobante_ingreso_id' name='comprobante_ingreso_id' value='<?php echo $comprobante_ingreso_id?>'/>
                <div class="row">
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label for="codigo">Código</label>
                            <input class="form-control" type="text" placeholder="id pieza/codigo externo" id="codigo" name="codigo" maxlength="150" autocomplete="off">
                        </div>
                    </div>
                </div>
                <div class="control-group">
                  <div class="controls">
                    <a class="btn btn-success verificar" href="javascript:;" ><i class="fa fa-check"></i> Verificar</a>
                    <a class="btn btn-success verPiezasSinVerificar" href="javascript:;" ><i class="fa fa-table"></i> Finalizar</a>
                     <span class="button btn btn-danger" data-dismiss="modal" aria-label="Close"><i class="fa fa-crosshairs"></i> Cerrar</span>
<!--                    <a class="btn btn-danger" href="#" onclick="javascript:window.close()"><i class="fa fa-arrow-circle-left"></i> Volver</a>-->
                  </div>
                </div>
                <br/>
                <div class="row">
                    <div class="col-xs-12">
                        <div id="grilla1" class="overflow-x:auto">
                            <table id="resultado1" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
                                <thead>
                                  <tr>
                                    <th>Pieza</th>
                                    <th>Destinatario</th>
                                    <th>Domicilio</th>
                                    <th>Localidad</th>
                                    <th>Cod. Post.</th>
                                  </tr>
                                </thead>
                                 <tbody id='body-grilla1'></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php echo form_close(); ?>
        </div>
    </div>

<script type="text/javascript">
    $(document).ready(function() {
    configurar();
    function configurar(){
        $('#resultado1').DataTable( {
            "destroy": true,
            "scrollY": 200,    
            "scrollX": 300,
            "searching": false,
            "ordering": false,
            "info": false,
            "paging": false,
        } );
        
    }
         $("#form-comprobantes-verificar").bind("keypress", function (e) {
            if (e.keyCode == 13) {
                $('.verificar').click();
                return false;
            }
        });
        
        $(".verificar").click(function (e) {
            var other_data = $('#form-comprobantes-verificar').serializeArray();
            var form_data = new FormData();
            $.each(other_data,function(key,input){
                form_data.append(input.name,input.value);
            });
             $.ajax({
                         url: '<?php echo base_url()."ajax/piezas/verificar" ?>',
                         dataType: 'json',
                         cache: false,
                         contentType: false,
                         processData: false,
                         data: form_data,                
                         type: 'post',
                         success: function(data){
                             if (data.status !== 'Verificado'){
                                 $('#errores_popup').html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Error</strong> '+data.status+'</div>');
                                 $('#codigo').val('');
                                 $('#codigo').focus();
                             }else{
                                 $('#errores_popup').html('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong></strong> '+data.status+'</div>'); 
                                 $('#codigo').val('');
                                 $(".verPiezasSinVerificar").click();
                             }
                         }
                     });
          }); 
          
        $("#form-comprobantes-verificar").validate({
            rules: {
                codigo: {required: true},
                //nombre_servicio: {required: true},
            },
            messages: {
                codigo: "Campo requerido.",
                //nombre_servicio: "Campo requerido.",
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
        
        $(".verPiezasSinVerificar").click(function (e) {
            $('#grilla1').html('');
            $.ajax({
                         url: '<?php echo base_url()."ajax/piezas/getPiezasSinVerificar/".$comprobante_ingreso_id ?>',
                         dataType: 'json',
                         cache: false,
                         contentType: false,
                         processData: false,
                         data: '',                
                         type: 'post',
                         success: function(data){
                             if (!data.status){
                                 
                              var cabecera = "<table id='resultado1' class='table table-striped table-hover table-condensed bootstrap-datatable table-bordered'>"+
                                                "<thead>"+
                                                  "<tr>"+
                                                    "<th>Pieza</th>"+
                                                    "<th>Destinatario</th>"+
                                                    "<th>Domicilio</th>"+
                                                    "<th>Localidad</th>"+
                                                    "<th class='col-xs-1'>Cod. Post.</th>"+
                                                  "</tr>"+
                                                "</thead>";
                                        var body = "<tbody id='body-grilla1'>";
                                $.each(data, function(i, item) {
                                 

                                body += '<tr>'+
                                    '<td>'+item.id+'</td>'+
                                    '<td>'+item.destinatario+'</td>'+
                                    '<td>'+item.domicilio+'</td>'+
                                    '<td>'+item.localidad+'</td>'+
                                    '<td>'+item.codigo_postal+'</td>'+
                                    '</tr>';
                                    //$('#body-grilla').append(html);
                                });
                                var cierre = '</tbody></table>';
                                $('#grilla1').append(cabecera+body+cierre); 
                                configurar();
                             }else{
                                 $('#errores_popup').html('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong></strong> '+data.status+'</div>'); 
                                 $('#codigo').val('');
                             }
                         }
                     });

        });
        });
</script>