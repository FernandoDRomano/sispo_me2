<div class="col-xs-12">
  <div class="ibox-content">
        <div class="alert alert-info">
            <strong>Atención!</strong> solo se muestran los movimientos de los 2 últimos meses. <br/>
            Debe aplicar los filtros antes de visualizar o exportar datos.
        </div>


        <hr/>
        <form id="formUsersLog" name="formUsersLog" action="<?=base_url('backend/userslog/index')?>" method="post">
            <div class="row">
                <div class="col-xs-12">
                    <h3>Filtros</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-3">
                    <label>Usuario</label>
                    <input type="text" id="username" name="username" class="form-control filtro" value="<?php echo $this->session->userdata('filtroUsersLogUserName')?$this->session->userdata('filtroUsersLogUserName'):'' ?>"/>
                </div>
                <div class="col-xs-3">
                    <label>Categoria</label>
                    <select id="categoria" name="categoria" class="form-control filtro" >
                        <option value="" <?php echo $this->session->userdata('filtroUsersLogCategoria')==''?'selected':'' ?>>Seleccione un valor...</option>
                        <option value="COMPROBANTES" <?php echo $this->session->userdata('filtroUsersLogCategoria')=='COMPROBANTES'?'selected':'' ?>>COMPROBANTES</option>
                        <option value="DESPACHOS" <?php echo $this->session->userdata('filtroUsersLogCategoria')=='DESPACHOS'?'selected':'' ?>>DESPACHOS</option>
                        <option value="NOVEDADES" <?php echo $this->session->userdata('filtroUsersLogCategoria')=='NOVEDADES'?'selected':'' ?>>NOVEDADES</option>
                        <option value="PIEZAS" <?php echo $this->session->userdata('filtroUsersLogCategoria')=='PIEZAS'?'selected':'' ?>>PIEZAS</option>
                        <option value="RENDICIONES" <?php echo $this->session->userdata('filtroUsersLogCategoria')=='RENDICIONES'?'selected':'' ?>>RENDICIONES</option>
                        <option value="HOJAS DE RUTAS" <?php echo $this->session->userdata('filtroUsersLogCategoria')=='HOJAS DE RUTAS'?'selected':'' ?>>HOJAS DE RUTAS</option>
                    </select>
                </div>
                <div class="col-xs-3">
                    <label>Descripción</label>
                    <input type="text" id="descripcion" name="descripcion" class="form-control filtro" value="<?php echo $this->session->userdata('filtroUsersLogDescripcion')?$this->session->userdata('filtroUsersLogDescripcion'):'' ?>"/>
                </div>
            </div>
            <hr/>
            <div class="row">
                <div class="col-xs-3">
                </div>
                <div class="col-xs-3">
                </div>
                <div class="col-xs-3">
                </div>
                <div class="col-xs-3">
                    <div class="text-right">
                        <button id="btnFiltrar" name="btnFiltrar" class="btn btn-primary" type="button">Filtrar</button>
                    </div>
                </div>
            </div>
            <input type="hidden" id="procesar" name="procesar" value="0" />
        </form>

        <hr/>
        <div class="alert alert-success">
          <?php echo $vista_interna['time_total']; ?>
        </div>

                <?php if (isset($vista_interna['links'])) { ?>
                    <?php echo $vista_interna['links'] ?>
                <?php } ?>
                <table id="resultado" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
        <thead>
            <tr>
                <th><a href="#">ID</a></th>
                <th class="col-xs-1"><a href="#">Usuario</a></th>
                <th class="col-xs-1"><a href="#">Fecha</a></th>
                <th class="col-xs-1"><a href="#">Categoria</a></th>
                <th><a href="#">Descripción</a></th>
            </tr>
        </thead>
                <tbody>
                    <?php if (isset($vista_interna['results'])) { ?>
                        <?php foreach ($vista_interna['results'] as $data) { ?>
                            <tr id="resultados" class="nueva">
                                <td class=""><?php echo $data->id ?></td>
                                <td class=""><?php echo $data->username ?></td>
                                <td class=""><?php echo $data->fecha ?></td>
                                <td class=""><?php echo $data->categoria ?></td>
                                <td class=""><?php echo $data->descripcion ?></td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                </tbody>
    </table>
                <?php if (isset($vista_interna['links'])) { ?>
                    <?php echo $vista_interna['links'] ?>
                <?php } ?>

  </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $('#btnFiltrar').click(function () {
        $('#procesar').val('0');
        var resultadoFiltroValido = filtroValido();
        if (resultadoFiltroValido) {
            $('#procesar').val('1');
            $('#formUsersLog').submit();
        } else {
            alert('Operación cancelada. Debe completar los filtros.');
        }
    });
});

function filtroValido() {
    var retorno = false;
    if($('#username').val() != '' && retorno == false ){
        retorno = true;
    }
    if($('#categoria').val() != '' && retorno == false ){
        retorno = true;
    }
    return retorno;
}
</script>