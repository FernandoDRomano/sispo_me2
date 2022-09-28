<div class="modal-content">
    <div class="modal-header modal-header-primary">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i></button>
        <h3><i class="fa fa-search"></i> <?php echo form_hidden('id',$result->id) ?></h3>
    </div>
    <div class="modal-body">
        <div class="form-horizontal">       
            <?php echo form_hidden('id',$result->id) ?>
            
                <div class="form-group">
                    <b>Usuario_id:</b> <?php echo $result->usuario_id ?>
                </div>

                <div class="form-group">
                    <b>Cliente_id:</b> <?php echo $result->cliente_id ?>
                </div>

                <div class="form-group">
                    <b>Departamento_id:</b> <?php echo $result->departamento_id ?>
                </div>

                <div class="form-group">
                    <b>Sucursal_id:</b> <?php echo $result->sucursal_id ?>
                </div>

                <div class="form-group">
                    <b>Factura:</b> <?php echo $result->factura ?>
                </div>

                <div class="form-group">
                    <b>Periodo_desde:</b> <?php echo $result->periodo_desde ?>
                </div>

                <div class="form-group">
                    <b>Periodo_hasta:</b> <?php echo $result->periodo_hasta ?>
                </div>

                <div class="form-group">
                    <b>Obeservaciones:</b> <?php echo $result->obeservaciones ?>
                </div>

                <div class="form-group">
                    <b>Estado_id:</b> <?php echo $result->estado_id ?>
                </div>

                <div class="form-group">
                    <b>Iva:</b> <?php echo $result->iva ?>
                </div>

                <div class="form-group">
                    <b>Create:</b> <?php echo $result->create ?>
                </div>

                <div class="form-group">
                    <b>Update:</b> <?php echo $result->update ?>
                </div>

                <div class="form-group">
                    <b>Create_user_id:</b> <?php echo $result->create_user_id ?>
                </div>

                <div class="form-group">
                    <b>Update_user_id:</b> <?php echo $result->update_user_id ?>
                </div>                             
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-primary pull-left" data-dismiss="modal">Cerrar</button>
    </div>
</div>