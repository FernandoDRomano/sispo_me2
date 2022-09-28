<div class="col-xs-12">
  <div class="ibox-content">
    <div class="text-right">
      <?php     
        echo form_open(current_url(), array('class'=>""));
        echo form_hidden('enviar_form','1');
      ?>

      <div class="form-group">
          <label for="nombre">Nombre<span class="required">*</span></label>
          <input id="nombre" required type="text" name="nombre" value="" class="form-control" placeholder="nombre" />
      </div>
                                          
      <div class="control-group">
        <div class="controls">
          <?php echo form_button(array('type'  =>'submit','value' =>'Guardar','name'  =>'submit','class' =>'btn btn-primary'), "Guardar"); ?> 
          <a class="btn btn-danger" href="<?php echo base_url().'backend/'.$this->uri->segment(2); ?>"><i class="fa fa-arrow-circle-left"></i> Volver</a>
        </div>
      </div>

      <?php echo form_close(); ?>
    </div>
    <table id="results" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
        <thead>
            <tr>
                <th class="col-xs-1"><a href="#">ID</a></th>
                <th><a href="#">Nombre</a></th>
                <th class="col-xs-2">&nbsp;</th>
            </tr>
        </thead>
        <tbody>
          <?php 
          foreach ($results as $result) { ?>
          <tr>
            <td><?php echo $result->id ?></td>
            <td><?php echo $result->nombre ?></td>
            <td>
              <div class="btn-group">
                <?php if($permisos_efectivos->delete==1) { ?><a onClick="eleminarOpcion('<?php echo base_url().'backend/opciones/deleteopcion/' ?>', '<?php echo $result->id ?>')" href="#" class="btn btn-danger"><i class="fa fa-trash-o"></i></a><?php } ?>
              </div>
            </td>
          </tr>
          <?php } ?>
        </tbody>
    </table>
  </div>
</div>

<script type="text/javascript">
function eleminarOpcion(link, id){
  bootbox.confirm("Deseas eliminar el registro?", function(result) {
    if (result === true) {
        $.ajax({
            type: "POST",
            url: link,
            data: {id: id},
            cache: false,
            success: function(){window.location.reload();}
        });
    }    
  });
}
</script>