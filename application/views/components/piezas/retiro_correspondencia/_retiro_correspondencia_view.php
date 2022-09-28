<div class="modal-content">
    <div class="modal-header modal-header-primary">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i></button>
        <h3><i class="fa fa-search"></i> <?php echo form_hidden('id',$result->id) ?></h3>
    </div>
    <div class="modal-body">
        <div class="form-horizontal">       
            <?php echo form_hidden('id',$result->id) ?>
            
                <div class="form-group">
                    <b>Fecha_retirar:</b> <?php echo $result->fecha_retirar ?>
                </div>

                <div class="form-group">
                    <b>Hora_retirar:</b> <?php echo $result->hora_retirar ?>
                </div>

                <div class="form-group">
                    <b>Cliente:</b> <?php echo $result->cliente ?>
                </div>

                <div class="form-group">
                    <b>Domicilio:</b> <?php echo $result->domicilio ?>
                </div>

                <div class="form-group">
                    <b>Servicio:</b> <?php echo $result->servicio ?>
                </div>

                <div class="form-group">
                    <b>Hora_limite_retirar:</b> <?php echo $result->hora_limite_retirar ?>
                </div>

                <div class="form-group">
                    <b>Observaciones:</b> <?php echo $result->observaciones ?>
                </div>

                <div class="form-group">
                    <b>Aviso:</b> <?php echo $result->aviso ?>
                </div>

                <div class="form-group">
                    <b>Estado:</b> <?php echo $result->estado ?>
                </div>
                            
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-primary pull-left" data-dismiss="modal">Cerrar</button>
    </div>
</div>