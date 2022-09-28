<div class="modal-content">
    <div class="modal-header modal-header-primary">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i></button>
        <h3><i class="fa fa-search"></i> <?php echo form_hidden('id',$result->id) ?></h3>
    </div>
    <div class="modal-body">
        <div class="form-horizontal">       
            <?php echo form_hidden('id',$result->id) ?>
            
                <div class="form-group">
                    <b>Liquidacion_id:</b> <?php echo $result->liquidacion_id ?>
                </div>

                <div class="form-group">
                    <b>Comprobante_ingreso:</b> <?php echo $result->comprobante_ingreso ?>
                </div>

                <div class="form-group">
                    <b>Remito_cliente:</b> <?php echo $result->remito_cliente ?>
                </div>

                <div class="form-group">
                    <b>Fecha:</b> <?php echo $result->fecha ?>
                </div>

                <div class="form-group">
                    <b>Servicio:</b> <?php echo $result->servicio ?>
                </div>

                <div class="form-group">
                    <b>Cantidad:</b> <?php echo $result->cantidad ?>
                </div>

                <div class="form-group">
                    <b>Precio:</b> <?php echo $result->precio ?>
                </div>

                <div class="form-group">
                    <b>Create:</b> <?php echo $result->create ?>
                </div>

                <div class="form-group">
                    <b>Update:</b> <?php echo $result->update ?>
                </div>                             
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-primary pull-left" data-dismiss="modal">Cerrar</button>
    </div>
</div>