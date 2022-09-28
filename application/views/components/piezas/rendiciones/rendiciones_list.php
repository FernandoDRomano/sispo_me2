<div class="col-xs-12">
    <div class="ibox-content">
        <div class="text-right">
            <?php if($permisos_efectivos->insert==1): ?>
              <a href="<?php echo base_url().'piezas/rendiciones/add/' ?>" class="btn btn-success"><i class="fa fa-plus"></i> Nueva Rendicion</a>
              <a data-toggle="modal" href="<?php echo base_url().'piezas/rendiciones/recibio/'?>" data-target="#myModal" class="btn btn-info"> <i class="fa fa-plus"></i>Recibió</a>
            <?php endif; ?>
            <hr>
        </div>
        <?php if($this->session->flashdata('registroOK')): ?>
          <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            La rendición nro: <b><span class="barcodear"><?php echo $this->session->flashdata('registroOK') ?></span></b> fue guardada con exito.
          </div>
        <?php endif; ?>
        <?php if($this->session->flashdata('registroKO')): ?>
          <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            La rendicion no se ha creado, no tiene piezas para realizarla.
          </div>
        <?php endif; ?>
        <form action="<?=base_url('piezas/rendiciones/filtro')?>" method="post" id="form-filtro">
            <div  class="row">
                <div class="col-xs-4">
                  <div class="form-group">
                    <label for="cliente">Clientes</label>
                    <input id="cliente" type="text" class="form-control filtro-group" placeholder="Cliente" autocomplete="off"/>
                    <input id="cliente_id" name="cliente_id" type="hidden" value=""/>
                  </div>
                </div>
                <div class="col-xs-3">
                  <div class="form-group">
                    <label for="nro_rendicion">Nro. Rendición</label>
                    <input id="rendicion_id" name="rendicion_id" class="form-control filtro-group"/>
                  </div>
                </div>
                <div class="col-xs-2" style="padding-top:23px">
                    <div class="btn-group">
                      <button type="submit" class="btn btn-success" title="Filtrar"><i class="fa fa-filter"></i></button>
                      <a href="<?=base_url('piezas/rendiciones')?>" class="btn btn-primary" title="Reinciar"><i class="fa fa-refresh"></i></a>
                    </div>
                </div>
            </div>
        </form>
        <table id="results" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
            <thead>
                <tr>
                    <th class="col-xs-1"><a href="#">ID</a></th>
                    <th><a href="#">Cliente</a></th>
                    <th><a href="#">Departamento</a></th>
                    <th><a href="#">Usuario</a></th>
                    <th><a href="#">Piezas</a></th>
                    <th><a href="#">Fecha</a></th>
                    <th class="col-xs-1 no-sort">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
              <?php foreach ($rendiciones as $rendicion): ?>
                  <tr>
                    <td><?php echo $rendicion->id ?></td>
                    <td><?php echo ($rendicion->nombre_fantasia!='')?$rendicion->nombre_fantasia:$rendicion->cliente ?></td>
                    <td><?php echo $rendicion->departamento ?></td>
                    <td><?php echo $rendicion->usuario_apellido.', '.$rendicion->usuario_nombre ?></td>
                    <td><?php echo $rendicion->piezas ?></td>
                    <td><?php echo $rendicion->create ?></td>
                    <td>
                      <div class="btn-group">
                        <a href="<?php echo base_url().'piezas/rendiciones/detalle/'.$rendicion->id ?>" class="btn btn-success" ><i class="fa fa-eye"></i></a>
                        <?php if($permisos_efectivos->delete==1) { ?><a onClick="eliminarRendicion('<?php echo base_url().'piezas/rendiciones/delete/'.$rendicion->id?>')" href="#" class="btn btn-danger"><i class="fa fa-trash-o"></i></a><?php } ?>
                        <?php/* if($permisos_efectivos->delete==1) { ?><a href="<?php echo base_url().'piezas/rendiciones/exportarAPDF/'.$rendicion->id?>" class="btn btn-info"><i class="fa fa-print"></i></a><?php } */?>
                        <?php/* if($permisos_efectivos->delete==1) { ?><a href="<?php echo base_url().'piezas/rendiciones/exportarAExcel/'.$rendicion->id?>" class="btn btn-info">xls</a><?php } */?>
                      </div>
                    </td>
                  </tr>
              <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div> 
<script type="text/javascript">
    $('#cliente').typeahead({
            source: <?=json_encode($clientes) ?>,
            displayField: 'nombre',
            onSelect: function(item) {
                if (item.value) {
                    $('#cliente').val(item.value);
                    $('#cliente_id').val(item.value);
                }
            }
        });
        
function eliminarRendicion(link){
      bootbox.confirm("Desea eliminar la rendición?", function(result) {
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
</script>