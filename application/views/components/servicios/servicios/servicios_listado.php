<div class="col-xs-12">
    <div class="ibox-content">
        <div class="text-right">
            <a href="<?php echo base_url() ?>servicios/servicios/exportarAExcel" class="btn btn-primary" ><i class="fa fa-table"></i> Exportar a Excel</a>
        </div>
    </div>
    <div class="ibox-content">
        <table id="results" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
            <thead>
                <tr>
                    <th><a href="#">Código</a></th>
                    <th><a href="#">Grupo</a></th>
                    <th><a href="#">Servicio</a></th>
                    <th><a href="#">Precio</a></th>
                </tr>
            </thead>
            <tbody>
              <?php foreach ($results as $result) { ?>
                  <tr>
                    <td><?php echo $result->id ?></td>
                    <td><?php echo $result->servicioGrupo->nombre ?></td>
                    <td><?php echo $result->nombre ?></td>
                    <td><?php echo $result->precio ?></td>
                  </tr>
              <?php } ?>
            </tbody>
        </table>
    </div>
</div>