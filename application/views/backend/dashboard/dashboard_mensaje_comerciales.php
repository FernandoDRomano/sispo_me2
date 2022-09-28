			<?php if(count($comercial) > 0):?>
        <div style="color: red;"><p>VENCIMIENTO de PRECIOS ESPECIALES DE CLIENTES</p></div>
        <table  class="table table-hover table-condensed table-bordered" id="results">
            <thead>
              <tr>
                <th class="col-xs-6">Cliente</th>
                <th class="col-xs-6">Fecha Vto.</th>
              </tr>
            </thead>
            <tbody id="body-grilla">
              <?php foreach ($clientes as $cliente): ?>
                <tr>
                  <td><?=$cliente->nombre?></td>
                  <td><?=$cliente->fecha_vto?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
          <div><p  style="color: red;">VENCIMIENTO de PRECIOS de SERVICIOS</p></div>
          <table  class="table table-hover table-condensed table-bordered" id="results">
            <thead>
              <tr>
                <th class="col-xs-6">Servicios</th>
                <th class="col-xs-6">Fecha Vto.</th>
              </tr>
            </thead>
            <tbody id="body-grilla">
              <?php foreach ($servicios as $servicio): ?>
                <tr>
                  <td><?=$servicio->nombre?></td>
                  <td><?=$servicio->fecha_vto?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
      <?php endif;?>

<style type="text/css">
.nav.nav-tabs li {
    width: 100%;
}

.nav-tabs {
    border-bottom: 0;
    border-right: 1px solid #ddd;
    padding-right: 5px;
}
</style>