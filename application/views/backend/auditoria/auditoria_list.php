<div class="col-xs-12">
  <div class="ibox-content">
    <table id="results" class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Consulta</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
          <?php 
          foreach ($results as $result) { ?>
          <tr>
            <td><?php echo $result->id ?></td>
            <td><?php echo $result->nombre_usuario ?></td>
            <td><?php echo $result->consulta ?></td>
            <td><?php echo $result->fecha ?></td>
          </tr>
          <?php } ?>
        </tbody>
    </table>
  </div>
</div>