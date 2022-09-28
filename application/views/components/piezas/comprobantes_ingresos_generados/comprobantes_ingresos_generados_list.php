<div class="col-xs-12">
    <div class="ibox-content">
        <div class="text-right">
            <?=form_open(base_url().'piezas/comprobantes_ingresos_generados/add', array('class'=>"")); ?>
              <div class="row">
                <div class="col-xs-offset-2 col-xs-4">
                    <div class="form-group text-left">
                        <select id="responsable_id" name="responsable_id" class="form-control chosen-select" required>
                            <?php foreach ($responsables as $f) { ?>
                                <option value="<?=$f->id ?>"><?=$f->apellido.', '.$f->nombre ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-xs-3">
                    <div class="form-group">
                        <select id="etiquetas_total" name="etiquetas_total" class="form-control" required>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-2">
                    <div class="control-group">
                      <div class="controls">
                        <?=form_button(array('type'  =>'submit','value' =>'Generar','name'  =>'submit','class' =>'btn btn-success'), "<i class='fa fa-floppy-o'></i> Generar"); ?>
                      </div>
                    </div>
                </div>
              </div>
            <?=form_close(); ?>
            <hr>
        </div>
        <table id="results" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
            <thead>
                <tr>
                    <th>Talonario N°</th>
                    <th>Etiqueta N°</th>
                    <th>Responsable</th>
                    <th>Estado</th>
                    <th style="display:none">create</th>
                    <th class="col-xs-1 no-sort">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
              <?php foreach ($comprobante_generados as $comprobante_generado) { ?>
                  <tr>
                    <td><?=sprintf("%06d", $comprobante_generado->talonario_id) ?></td>
                    <td><?=$comprobante_generado->numero ?></td>
                    <td><?=$comprobante_generado->apellido?>, <?=$comprobante_generado->nombre?></td>
                    <td data-field="estado"><?php   switch($comprobante_generado->estado) {
                                                            case 0: echo "USADO";
                                                                break;
                                                            case 1: echo "DISPONIBLE";
                                                                break;
                                                            case 2: echo "IMPRESO";
                                                                break;
                                                            default:
                                                                return "";
                                                            }?>
                    </td>
                    <td data-field="create" style="display:none"><?=$comprobante_generado->create?></td>
                    <td>
                      <div class="btn-group">
                          <a href="#" class="btn btn-success" title="Imprimir" onclick="flash.imprimir(this,<?=$comprobante_generado->id?>)" <?=$comprobante_generado->estado == ComprobanteGenerado::ESTADO_DISPONIBLE || $this->is_admin()?'':'disabled="disabled"'?>><i class='fa fa-print'></i></a>
                      </div>
                    </td>
                  </tr>
              <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript">
    function getEstadoNombre(estado_nombre){alert(estado_nombre);
        
    }
//    configurar();
//    function configurar(){
//        $('#resultado').DataTable( {
//            "destroy": true,
////            "scrollY": 200,    
////            "scrollX": 300,
//            "searching": true,
//            "ordering": true,
//            "info": false,
//            "paging": true,
//            "order": [[ 4,'desc' ]]
//        } );
//        
//    }
    
   /* $('#results').DataTable( {
        destroy : true,
        order: [[ 5,'desc' ]]
    } );
    
     $('#results').destroy();
     */
        if(!flash) var flash = {};

        flash.imprimir = function(btn, id){
            $.ajax({
                type: "POST",
                url: '<?=base_url('piezas/comprobantes_ingresos_generados/imprimir')?>',
                data: {
                    id : id
                },
                dataType : "json",
                cache: false,
                success: function(data){
                    if(data.estado)
                    {
                        $(btn).attr('disabled','disabled');
                        $(btn).parent().parent().parent().find("td[data-field=estado]").html(data.estado)
                    }
                }
            });
        };
    
</script>