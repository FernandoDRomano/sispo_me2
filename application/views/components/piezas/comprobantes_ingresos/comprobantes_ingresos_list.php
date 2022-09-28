<div class="col-xs-12">
  <div class="ibox-content">

    <?php if($this->session->flashdata('registro')): ?>
      <div class="row">
        <div class="col-md-12">
          <div class="alert alert-success" style="margin: 10px 20px 30px 20px;">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            El comprobante de ingreso <b><span class="barcodear"><?php echo $this->session->flashdata('registro') ?></span></b> fue guardado con exito.
          </div>
        </div>
      </div>
    <?php endif; ?>
    <form action="<?=base_url('piezas/comprobantes_ingresos/filtro')?>" method="post" id="form-filtro">
      <div class="row">
        <div class="col-xs-3">
          <div class="form-group">
            <label for="empresa">Empresa</label>
            <select id="empresa" name="empresa" class="form-control filtro-group" disabled>
              <option value="">Seleccione</option>
              <?php foreach ($empresas as $empresa): ?>
                <option value="<?=$empresa->id ?>" <?=$empresa_id == $empresa->id ? 'selected="selected"' : ''?>><?=$empresa->nombre ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="col-xs-3">
          <div class="form-group">
            <div class="form-group">
                <label for="cliente_id">Cliente<span class="required">*</span></label>
                <select id="cliente_id" name="cliente_id" class="form-control  filtro-group"></select>
            </div> 
          </div>
        </div>
        <div class="col-xs-3">
          <div class="form-group">
            <label for="dpto">Dpto</label>
            <select id="dpto" name="dpto" class="form-control filtro-group">
              <option value="">Seleccione</option>
              <?php foreach ($departamentos as $departamento): ?>
                <option value="<?=$departamento->id ?>"<?=$departamento_id == $departamento->id ? 'selected="selected"' : ''?>><?=$departamento->nombre ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="col-xs-3" style="padding-top:23px">
          <div class="btn-group">
            <button type="submit" class="btn btn-success" title="Filtrar"><i class="fa fa-filter"></i></button>
            <a href="<?=base_url('piezas/comprobantes_ingresos')?>" class="btn btn-primary" title="Reinciar"><i class="fa fa-refresh"></i></a>
          </div>
        </div>
      </div>
    </form>
  </div>
  <div id="div_resultado" class="ibox-content">
    <table id="results" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
      <thead>
        <tr>
          <th>Numero</th>
          <th>Sucursal</th>
          <th>Cliente</th>
          <th>Fecha</th>
          <th>Disponibles</th>
          <th style="display: none">create</th>
          <th class="col-xs-2 no-sort">&nbsp;</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($comprobantes as $comprobante): ?>
          <tr>
            <td><a rel="tooltip" data-animation="false" data-original-title="<h2><span class='barcode'><?=$comprobante->numero?></span><br><?=$comprobante->numero?></h2>"><?=$comprobante->numero?></a></td>
            <td>
              <?=$comprobante->empresa->nombre?><br>
              Sucursal <?=$comprobante->sucursal->nombre?>
            </td>
            <td>
              <?=$comprobante->cliente->nombre?><br>
              <?php if($comprobante->clienteDepartamento): ?>
                <b>Dpto:</b> <?=$comprobante->clienteDepartamento->nombre?>
              <?php endif; ?>
            </td>
            <td>
              <?=$comprobante->fecha_pedido->format('d-m-Y')?>
            </td>
            <td>
              <?=$comprobante->disponible?> de <?=$comprobante->cantidad?>
            </td>
            <td style="display: none">
              <?=$comprobante->create?>
            </td>
            <td>
              <div class="btn-group">
              <a href="<?=base_url('piezas/comprobantes_ingresos/view/'.$comprobante->id)?>" class="btn btn-success fancybox" data-fancybox-type="iframe" title="Servicios"><i class="fa fa-truck"></i></a>
              <?php if ($flag == 'piezas'){?>
              <a href="<?=base_url('piezas/comprobantes_ingresos/piezas/'.$comprobante->id.'/'.$flag)?>" class="btn btn-primary" title="Piezas"><i class="fa fa-indent"></i></a>
              <?php } ?>
              <?php if ($flag == 'piezas_masivas'){?>
              <a href="<?=base_url('piezas/comprobantes_ingresos/piezas/'.$comprobante->id.'/'.$flag)?>" class="btn btn-primary" title="Piezas"><i class="fa fa-indent"></i></a>
              <?php } ?>
              <a href="javascript:;" onclick="javascript:archivar_comprobante(<?=$comprobante->id?>)" class="btn btn-info btn-archivar" title="Archivar Comprobante" <?=$comprobante->estado == Hoja::ESTADO_INICIADA || $comprobante->estado == Hoja::ESTADO_CERRADA?'disabled="disabled"':''?>><i class="fa fa-save"></i></a>
              <a data-toggle="modal" href="<?php echo base_url().'piezas/comprobantes_ingresos/verificar/'.$comprobante->id?>" data-target="#myModal" class="btn btn-info"> <i class="fa fa-check"></i></a>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<script type="text/javascript">
  <?php include 'script_piezas.js'; ?>
</script>
<script type="text/javascript">
$(document).ready(function() {
    
    $('#resultado').DataTable( {
        order: [[ 5, "desc" ]]
    } );
    
  $("#form-filtro").validate({
    rules: {
      empresa: {require_from_group: [1, ".filtro-group"]},
      cliente: {require_from_group: [1, ".filtro-group"]},
      dpto: {require_from_group: [1, ".filtro-group"]}
    },
    messages: {
      empresa: "Campo requerido.",
      cliente: "Campo requerido.",
      dpto: "Campo requerido.",
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
});

$('#cliente_id').select2({
    placeholder: 'Seleccione',
    ajax: {
      url: '<?php echo base_url()."ajax/clientes/autocompletarClientes" ?>',
      dataType: 'json',
      delay: 250,
      processResults: function (data) {
        return {
          results: data
        };
      },
      cache: true
    }
});

$('#cliente_id').change(function(event) {
    if ($('#cliente_id').val() === 0) {
        $('#cliente_id').val(0);
    }else{
            $.getJSON('<?=base_url()?>' + 'ajax/clientes/departamentos/' + $('#cliente_id').val(), function(data) {
                $("#dpto").find("option").remove();
                var options = '';

                if(!data.status){
                    options = options + "<option value=''>Seleccione</option>";
                    $.each(data, function(key, val) {
                        options = options + "<option value='"+val.id+"'>"+ val.nombre +"</option>";
                    });
                }else{
                    options = options + "<option value=''>Sin resultados</option>";
                }

                $("#dpto").append(options);
                $("#dpto").trigger("liszt:updated");
                $('#dpto').removeClass('loading-ajax-select');
            });
          }
});
</script>