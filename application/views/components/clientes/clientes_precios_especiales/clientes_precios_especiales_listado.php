<div class="col-xs-12">
    <div class="ibox-content">
        <div class="text-right">            
            <a href="<?php echo base_url() ?>clientes/clientes_precios_especiales/exportarAExcel" class="btn btn-primary" ><i class="fa fa-table"></i> Exportar a Excel</a>
        </div>
    </div>
    <div class="ibox-content">
        <table id="results" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
            <thead>
                <tr>
                    <th><a href="#">Cliente</a></th>
                    <th><a href="#">CUIT</a></th>
                    <th><a href="#">IVA</a></th>
                    <th><a href="#">Servicio</a></th>
                    <th><a href="#">Precio</a></th>
                </tr>
            </thead>
            <tbody>
              <?php foreach ($results as $result) { ?>
                  <tr>
                    <td><?php echo $result->cliente ?></td>
                    <td><?php echo $result->cuit ?></td>
                    <td><?php echo $result->iva ?></td>
                    <td><?php echo $result->servicio ?></td>
                    <td>$ <?php echo $result->precio?></td>
                  </tr>
              <?php } ?>
            </tbody>
        </table>
    </div>
</div>
