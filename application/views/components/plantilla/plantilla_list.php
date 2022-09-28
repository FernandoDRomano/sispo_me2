<div class="col-xs-12">
    <!-- boton crear -->
    <div class="ibox-content">
        <div class="text-right">            
            <?php if($permisos_efectivos->insert==1) { ?> <a href="<?php echo base_url().'plantillas/plantillas/create/' ?>" class="btn btn-success"><i class="fa fa-plus"></i> Nueva plantilla</a><?php } ?>
        </div>
    </div>
    <!-- end boton crear -->
    
    <!-- filtro -->
    <div class="ibox-content">
        <form action="<?php echo base_url() ?>plantillas/plantillas/filtro" method="post" id="form-filtro">
            <div class="row">
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="buscar">Campo Libre</label>
                        <input type="text" id="buscar" name="buscar" class="form-control filtro-group" placeholder="Nombre, descripci&#243;n" autocomplete="off">
                    </div>
                </div>
                <div class="col-xs-6 ">
                    <div class="form-group">
                        <button type="submit" class="btn btn-success" style="margin-top: 23px;">Filtrar</button>
                        <a href="<?php echo base_url() ?>plantillas/plantillas" class="btn btn-primary" style="margin-top: 23px;"><i class="fa fa-refresh"></i> Reinciar</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- end filtro -->

    <div class="ibox-content">        
        <table id="results" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
            <thead>
                <tr>
                    <th><a href="#">Nombre</a></th>
                    <th><a href="#">Descripción</a></th>
                    <th><a href="#">Acciones</a></th>
                </tr>
            </thead>
            <tbody>
               
              <?php foreach ($results as $result) { ?>
                  <tr>
                    <td>                      
                      <?php echo $result->nombre ?><br>
                    </td>
                    <td>
                      <?php echo $result->descripcion ?><br>
                    </td>
                    <td>
                      <div class="btn-group">
                        <a data-toggle="modal" href="<?php echo base_url().'plantillas/plantillas/view/'.$result->id ?>" data-target="#myModal" class="btn btn-primary text-white"><i class="fa fa-search"></i></a>
                        <?php if($permisos_efectivos->update==1) { ?><a href="<?php echo base_url().'plantillas/plantillas/edit/'.$result->id ?>" class="btn btn-success"><i class="fa fa-edit"></i></a><?php } ?>
                        <?php if($permisos_efectivos->delete==1) { ?><a onClick="eleminarRegistro('<?php echo base_url().'plantillas/plantillas/delete/'.$result->id ?>')" href="#" class="btn btn-danger"><i class="fa fa-trash-o"></i></a><?php } ?>
                      </div>
                    </td>
                  </tr>
              <?php } ?>
              
            </tbody>
        </table>
    </div>

</div>

<script type="text/javascript">

</script>