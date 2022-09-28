<div class="modal-content">
    <div class="modal-header modal-header-primary">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i></button>
        <h3><i class="fa fa-search"></i> <?php echo form_hidden('id',$result[0]->id) ?></h3>
    </div>
    <div class="modal-body">
        <div class="form-horizontal">       
            <?php echo form_hidden('id',$result[0]->id) ?>
            
                <div class="form-group">
                    <b>Fecha_retirar:</b> <?php echo $result[0]->fecha_retirar ?>
                </div>

                <div class="form-group">
                    <b>Hora_retirar:</b> <?php echo $result[0]->hora_retirar ?>
                </div>

                <div class="form-group">
                    <b>Cliente:</b> <?php echo $result[0]->nombre_cliente ?>
                </div>

                <div class="form-group">
                    <b>Domicilio:</b> <?php echo $result[0]->domicilio ?>
                </div>

                <div class="form-group">
                    <b>Servicio:</b> <?php echo $result[0]->nombre_servicio ?>
                </div>

                <div class="form-group">
                    <b>Hora_limite_retirar:</b> <?php echo $result[0]->hora_limite_retirar ?>
                </div>

                <div class="form-group">
                    <b>Observaciones:</b> <?php echo $result[0]->observaciones ?>
                </div>

                <div class="form-group">
                    <b>Aviso:</b> <?php echo $result[0]->aviso ?>
                </div>

                <div class="form-group">
                    <b>Estado:</b> <?php if($result[0]->estado == 'P') echo 'PENDIENTE';if($result[0]->estado == 'R') echo 'RESERVADO';if($result[0]->estado == 'C') echo 'CANCELADO'; ?>
                </div>
                            
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-primary pull-left" data-dismiss="modal">Cerrar</button>
    </div>
</div>