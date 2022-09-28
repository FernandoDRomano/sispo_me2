<div class="col-xs-12">
    <div class="ibox-content">
        <?php if($this->session->flashdata('registro')): ?>
            <div class="row">
              <div class="col-md-12">
                <div class="alert alert-success" style="margin: 10px 20px 30px 20px;">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  La liquidación nro: <b><span class="barcodear"><?php echo $this->session->flashdata('registro') ?></span></b> fue guardada con exito.
                </div>
              </div>
            </div>
          <?php endif; ?>
          <form action="<?=base_url('liquidaciones/liquidaciones/filtro')?>" method="post" id="form-filtro">
            <div class="row">
              <div class="col-xs-3">
                <div class="form-group">
                  <label for="desde">Desde</label>
                  <input id="fecha_desde" name="fecha_desde" type="text" class="form-control datepicker" placeholder="Seleccione una fecha" value="<?php /*if ($fecha_desde){ $f = strtotime($fecha_desde);echo date('d-m-Y',$f );}*/ ?>"   autocomplete="off" />
                </div>
              </div>
              <div class="col-xs-3">
                <div class="form-group">
                  <label for="fecha_hasta">Fecha hasta</label>
                  <input id="fecha_desde" name="fecha_hasta" type="text" class="form-control datepicker" placeholder="Seleccione una fecha" value="<?php /* if ($fecha_hasta) {$f = strtotime($fecha_hasta);echo date('d-m-Y',$f ); }*/?>"   autocomplete="off" />
                </div>
              </div>
            </div>
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
                    <label for="dpto">Dpto</label>
                    <select id="departamento_id" name="departamento_id" class="form-control filtro-group">
                      <option value="">Seleccione</option>>
                    </select>
                  </div>
                </div>
                <div class="col-xs-3">
                  <div class="form-group">
                    <label for="sucursal">Sucursal</label>
                    <select id="sucursal_id" name="sucursal_id" class="form-control filtro-group">
                      <option value="">Seleccione</option>
                      <?php foreach ($sucursales as $sucursal): ?>
                        <option value="<?=$sucursal->id ?>"<?=$sucursal_id == $sucursal->id ? 'selected="selected"' : ''?>><?=$sucursal->nombre ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
                <div class="col-xs-2" style="padding-top:23px">
                    <div class="btn-group">
                      <button type="submit" class="btn btn-success" title="Filtrar"><i class="fa fa-filter"></i></button>
                      <a href="<?=base_url('liquidaciones/liquidaciones')?>" class="btn btn-primary" title="Reinciar"><i class="fa fa-refresh"></i></a>
                    </div>
                </div>
            </div>
          </form>
        <div class="text-right">            
            <?php if($permisos_efectivos->insert==1) { ?> <a href="<?php echo base_url().'liquidaciones/liquidaciones/add/' ?>" class="btn btn-success"><i class="fa fa-plus"></i> Nueva Liquidación</a><?php } ?><hr>
        </div>
        <table id="results" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
            <thead>
                <tr>
                    <th class="col-xs-1"><a href="#">ID</a></th>
                    <th><a href="#">Cliente</a></th>
                    <th><a href="#">Departamento</a></th>
                    <th><a href="#">Nro</a></th>
                    <th><a href="#">Factura</a></th>
                    <th><a href="#">Perdiodo desde</a></th>
                    <th><a href="#">Perdiodo hasta</a></th>
                    <th><a href="#">Estado</a></th>
                    <th class="no-sort">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
              <?php foreach ($liquidaciones as $result) { 
                     $c = Cliente::where('id',$result->cliente_id)->get();
                     $d = ClienteDepartamento::where('id',$result->departamento_id)->get();
                     $c = $c->toArray();
                     $d = $d->toArray();?>
                  <tr>
                    <td><?php echo $result->id ?></td>
                    <td><?php echo $c[0]['nombre'] ?></td>
                    <td><?php echo $d[0]['nombre'] ?></td>
                    <td><?php echo $result->nro ?></td>
                    <td><?php echo $result->factura ?></td>
                    <td><?php $f = strtotime($result->periodo_desde);echo date('d-m-Y',$f) ?></td>
                    <td><?php $f1 = strtotime($result->periodo_hasta);echo date('d-m-Y',$f1) ?></td>
                    <td><?php if($result->estado_id == 0) echo "PENDIENTE";
                              if($result->estado_id == 1) echo "REALIZADA";
                              if($result->estado_id == 2) echo "ANULADA";?></td>
                    <td>
                      <div class="btn-group">
<!--                        <a data-toggle="modal" href="<?php echo base_url().'liquidaciones/liquidaciones/view/'.$result->id ?>" data-target="#myModal" class="btn btn-info"><i class="fa fa-search"></i></a>-->
                        <?php if($permisos_efectivos->update==1) { ?><a href="<?php echo base_url().'liquidaciones/liquidaciones/edit/'.$result->id ?>" class="btn btn-success"><i class="fa fa-edit"></i></a><?php } ?>
                        <?php if($permisos_efectivos->delete==1) { ?><a onClick="anularLiquidacion('<?php echo base_url().'liquidaciones/liquidaciones/anular/'.$result->id ?>')" href="#" class="btn btn-danger"><i class="fa fa-trash-o"></i></a><?php } ?>
                      </div>
                    </td>
                  </tr>
              <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<script type="text/javascript">
    function anularLiquidacion(link){
      bootbox.confirm("Desea anular la liquidación?", function(result) {
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
    
    $('#cliente').typeahead({
            source: <?=json_encode($clientes) ?>,
            displayField: 'nombre',
            onSelect: function(item) {
                if (item.value) {
                    $('#cliente').val(item.value);
                    $('#cliente_id').val(item.value);
                    if (item.value != 0){
                        var id = item.value;
                        $('#departamento_id').addClass('loading-ajax');
                        if (id != 0) {
                            var url = '<?php echo base_url() ?>' + 'ajax/clientes/departamentos/' + $('#cliente_id').val();
                            $.getJSON(url, function(data) {
                                $("#departamento_id").find("option").remove();                
                                var options = '';

                                if(!data.status){
                                    $.each(data, function(key, val) {
                                        options = options + "<option value='"+val.id+"'>"+ val.nombre +"</option>";
                                    });              
                                }else{
                                    options = options + "<option value='0' disabled>Sin resultados</option>";
                                }

                                $("#departamento_id").append(options);
                                $("#departamento_id").trigger("liszt:updated");
                            });
                        }else{
                            $("#departamento_id").find("option").remove();
                        }

                        $('#departamento_id').removeClass('loading-ajax');
                    }
                }
            }
        });
</script>