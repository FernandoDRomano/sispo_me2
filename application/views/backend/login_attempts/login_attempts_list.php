<div class="col-xs-12">
  <div class="ibox-content">
    <table id="results" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
        <thead>
            <tr>
                <th class="col-xs-1"><a href="#">ID</a></th>
                <th><a href="#">Usuario</a></th>
                <th><a href="#">IP</a></th>
                <th><a href="#">Fecha</a></th>
            </tr>
        </thead>
        <tbody>
          <?php 
          foreach ($results as $result) { ?>
          <tr>
            <td><?php echo $result->id ?></td>
            <td><?php echo $result->nombre_usuario ?></td>
            <td><?php echo $result->ip_address ?></td>
            <td><?php echo $result->time ?></td>
          </tr>
          <?php } ?>
        </tbody>
    </table>
  </div>
</div>