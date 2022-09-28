<div class="col-xs-12">
  <div class="ibox-content">
    <div class="text-right">
      <?php if($permisos_efectivos->insert==1) { ?> <a href="<?php echo base_url().'backend/users/add/' ?>" class="btn btn-primary"><i class="fa fa-plus"></i> Nuevo Usuario</a><?php } ?><hr>
    </div>
    <table id="results" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
        <thead>
            <tr>
                <th class="col-xs-1"><a href="#">ID</a></th>
                <th><a href="#">Usuario</a></th>
                <th><a href="#">Email</a></th>
                <th><a href="#">Apellido, Nombre</a></th>
                <th><a href="#">Grupo</a></th>
                <th><a href="#">Estado</a></th>
                <th class="col-xs-2">&nbsp;</th>
            </tr>
        </thead>
        <tbody>
          <?php
          foreach ($usuarios as $result) { ?>
          <tr>
            <td><?php echo $result->id ?></td>
            <td><?php echo $result->username ?></td>
            <td><?php echo $result->email ?></td>
            <td><?php echo $result->apellido.", ".$result->nombre ?></td>
            <td><?php echo $result->name ?></td>
            <td><?php if($result->active==1){?><i class="fa fa-check"></i><?php } else {?><i class="fa fa-times"></i><?php }?></td>
            <td>
                <div class="btn-group">
                    <a data-toggle="modal" href="<?php echo base_url().'backend/users/view/'.$result->id ?>" data-target="#myModal" class="btn btn-info"><i class="fa fa-search"></i></a>
                    <?php if($permisos_efectivos->update==1) { ?><a href="<?php echo base_url().'backend/users/edit/'.$result->id ?>" class="btn btn-success"><i class="fa fa-edit"></i></a><?php } ?>
                    <?php if($permisos_efectivos->delete==1) { ?><a onClick="eleminarUsuario('<?php echo base_url().'backend/users/delete/'.$result->id ?>')" href="#" class="btn btn-danger"><i class="fa fa-trash-o"></i></a><?php } ?>
                </div>
            </td>
          </tr>
          <?php } ?>
        </tbody>
    </table>
  </div>
</div>
<script type="text/javascript">
//function unlock(usuario_id){alert(usuario_id);
//    var url = '<?php echo base_url() ?>' + 'ajax/usuarios/unlock/' + usuario_id;
//    $.getJSON(url, function(data) {
//        $("#departamento_id").find("option").remove();                
//        var options = '';
//
//        if(!data.status){
//            $.each(data, function(key, val) {
//                options = options + "<option value='"+val.id+"'>"+ val.nombre +"</option>";
//            });              
//        }else{
//            options = options + "<option value='0' disabled>Sin resultados</option>";
//        }
//
//        $("#departamento_id").append(options);
//        $("#departamento_id").trigger("liszt:updated");
//    });
//};

function eleminarUsuario(link){
  var answer = confirm('Desea eliminar el registro?')
  if (answer){
    $.ajax({
            type: "GET",
            url: link,
            data: {},
            cache: false,
            success: function(){window.location.reload();}
          });
  }
  return false;
}
</script>