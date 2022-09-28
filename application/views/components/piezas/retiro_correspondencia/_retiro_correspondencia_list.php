<div class="row">
    <div class="text-right">            
        <?php if($permisos_efectivos->insert==1) { ?> <a href="<?php echo base_url().'retiro_correspondencia/retiro_correspondencia/add/' ?>" class="btn btn-success"><i class="fa fa-plus"></i>Agregar retiro de correspondencia</a><?php } ?><hr>
    </div>
</div>
<ul class="nav nav-tabs">
  <li class="active"><a data-toggle="tab" href="#pendientes">Pendientes</a></li>
  <li><a data-toggle="tab" href="#recibidos">Recibidos</a></li>
  <li><a data-toggle="tab" href="#cancelados">Cancelados</a></li>
</ul>
<div class="tab-content">
  <div id="pendientes" class="tab-pane fade in active">
    <div class="col-xs-12">
        <div class="ibox-content">
                <table id="results-pendientes" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
                    <thead>
                        <tr>
                            <th class="col-xs-1"><a href="#">ID</a></th>
                            <th><a href="#">Cliente</a></th>
                            <th><a href="#">Domicilio</a></th>
                            <th><a href="#">Servicio</a></th>
                            <th><a href="#">Fecha de retiro</a></th>
                            <th><a href="#">Hora de retiro</a></th>
                            <th><a href="#">Hora limite de retirar</a></th>
                            <th><a href="#">Aviso</a></th>
                            <th><a href="#">Estado</a></th>
                            <th class="col-xs-2">&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($results_pendientes as $result) { ?>
                          <tr>
                            <td><?php echo $result->id ?></td>
                            <td><?php echo $result->nombre_cliente ?></td>
                            <td><?php echo $result->domicilio ?></td>
                            <td><?php echo $result->nombre_servicio ?></td>
                            <td><?php echo $result->fecha_retirar ?></td>
                            <td><?php echo $result->hora_retirar ?></td>
                            <td><?php echo $result->hora_limite_retirar ?></td>
                            <td><?php echo $result->aviso ?></td>
                            <td><?php if($result->estado == 'P'){
                                        echo 'PENDIENTE';
                                    } else {
                                                if($result->estado == 'R'){ echo 'RECIBIDO';
                                            } else {
                                                        if($result->estado == 'R') {
                                                                echo 'CANCELADO';
                                                        }
                                            }
                                    }?></td>
                            <td>
                              <div class="btn-group">
                                <a data-toggle="modal" href="<?php echo base_url().'retiro_correspondencia/retiro_correspondencia/view/'.$result->id ?>" data-target="#myModal" class="btn btn-info"><i class="fa fa-search"></i></a>
                                <?php if($permisos_efectivos->update==1) { ?><a href="<?php echo base_url().'retiro_correspondencia/retiro_correspondencia/edit/'.$result->id ?>" class="btn btn-success"><i class="fa fa-edit"></i></a><?php } ?>
                                <?php if($permisos_efectivos->delete==1) { ?><a onClick="eleminarRegistro('<?php echo base_url().'retiro_correspondencia/retiro_correspondencia/delete/'.$result->id ?>')" href="#" class="btn btn-danger"><i class="fa fa-trash-o"></i></a><?php } ?>
                              </div>
                            </td>
                          </tr>
                      <?php } ?>
                    </tbody>
                </table>
        </div>
    </div>
  </div>
    <div id="recibidos" class="tab-pane fade">
        <div class="col-xs-12">
            <div class="ibox-content">
                <table id="results-recibidos" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
                    <thead>
                        <tr>
                            <th class="col-xs-1"><a href="#">ID</a></th>
                            <th><a href="#">Cliente</a></th>
                            <th><a href="#">Domicilio</a></th>
                            <th><a href="#">Servicio</a></th>
                            <th><a href="#">Fecha de retiro</a></th>
                            <th><a href="#">Hora de retiro</a></th>
                            <th><a href="#">Hora limite de retirar</a></th>
                            <th><a href="#">Aviso</a></th>
                            <th><a href="#">Estado</a></th>
                            <th class="col-xs-2">&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($results_recibidos as $result) { ?>
                          <tr>
                            <td><?php echo $result->id ?></td>
                            <td><?php echo $result->nombre_cliente ?></td>
                            <td><?php echo $result->domicilio ?></td>
                            <td><?php echo $result->nombre_servicio ?></td>
                            <td><?php echo $result->fecha_retirar ?></td>
                            <td><?php echo $result->hora_retirar ?></td>
                            <td><?php echo $result->hora_limite_retirar ?></td>
                            <td><?php echo $result->aviso ?></td>
                            <td><?php if($result->estado == 'P'){
                                        echo 'PENDIENTE';
                                    } else {
                                                if($result->estado == 'R'){ echo 'RECIBIDO';
                                            } else {
                                                        if($result->estado == 'R') {
                                                                echo 'CANCELADO';
                                                        }
                                            }
                                    }?></td>
                            <td>
                              <div class="btn-group">
                                <a data-toggle="modal" href="<?php echo base_url().'retiro_correspondencia/retiro_correspondencia/view/'.$result->id ?>" data-target="#myModal" class="btn btn-info"><i class="fa fa-search"></i></a>
                                <?php if($permisos_efectivos->update==1) { ?><a href="<?php echo base_url().'retiro_correspondencia/retiro_correspondencia/edit/'.$result->id ?>" class="btn btn-success"><i class="fa fa-edit"></i></a><?php } ?>
                                <?php if($permisos_efectivos->delete==1) { ?><a onClick="eleminarRegistro('<?php echo base_url().'retiro_correspondencia/retiro_correspondencia/delete/'.$result->id ?>')" href="#" class="btn btn-danger"><i class="fa fa-trash-o"></i></a><?php } ?>
                              </div>
                            </td>
                          </tr>
                      <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div id="cancelados" class="tab-pane fade">
        <div class="col-xs-12">
            <div class="ibox-content">
                <table id="results-cancelados" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
                    <thead>
                        <tr>
                            <th class="col-xs-1"><a href="#">ID</a></th>
                            <th><a href="#">Cliente</a></th>
                            <th><a href="#">Domicilio</a></th>
                            <th><a href="#">Servicio</a></th>
                            <th><a href="#">Fecha de retiro</a></th>
                            <th><a href="#">Hora de retiro</a></th>
                            <th><a href="#">Hora limite de retirar</a></th>
                            <th><a href="#">Aviso</a></th>
                            <th><a href="#">Estado</a></th>
                            <th class="col-xs-2">&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($results_cancelados as $result) { ?>
                          <tr>
                            <td><?php echo $result->id ?></td>
                            <td><?php echo $result->nombre_cliente ?></td>
                            <td><?php echo $result->domicilio ?></td>
                            <td><?php echo $result->nombre_servicio ?></td>
                            <td><?php echo $result->fecha_retirar ?></td>
                            <td><?php echo $result->hora_retirar ?></td>
                            <td><?php echo $result->hora_limite_retirar ?></td>
                            <td><?php echo $result->aviso ?></td>
                            <td><?php if($result->estado == 'P'){
                                        echo 'PENDIENTE';
                                    } else {
                                                if($result->estado == 'R'){ echo 'RECIBIDO';
                                            } else {
                                                        if($result->estado == 'R') {
                                                                echo 'CANCELADO';
                                                        }
                                            }
                                    }?></td>
                            <td>
                              <div class="btn-group">
                                <a data-toggle="modal" href="<?php echo base_url().'retiro_correspondencia/retiro_correspondencia/view/'.$result->id ?>" data-target="#myModal" class="btn btn-info"><i class="fa fa-search"></i></a>
                                <?php if($permisos_efectivos->update==1) { ?><a href="<?php echo base_url().'retiro_correspondencia/retiro_correspondencia/edit/'.$result->id ?>" class="btn btn-success"><i class="fa fa-edit"></i></a><?php } ?>
                                <?php if($permisos_efectivos->delete==1) { ?><a onClick="eleminarRegistro('<?php echo base_url().'retiro_correspondencia/retiro_correspondencia/delete/'.$result->id ?>')" href="#" class="btn btn-danger"><i class="fa fa-trash-o"></i></a><?php } ?>
                              </div>
                            </td>
                          </tr>
                      <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('a[href="#pendientes"]').tab('show');
    });
    </script>