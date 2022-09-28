<div class="modal-content">
    <div class="modal-header modal-header-primary">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i></button>
        <h3><i class="fa fa-search"></i> <?php echo form_hidden('id',$result->id) ?></h3>
    </div>
    <div class="modal-body">
        <div class="form-horizontal">       
            <?php echo form_hidden('id',$result->id) ?>
            
                <div class="form-group">
                    <b>Sucursal_id:</b> <?php echo $result->sucursal_id ?>
                </div>

                <div class="form-group">
                    <b>Periodo_desde:</b> <?php echo $result->periodo_desde ?>
                </div>

                <div class="form-group">
                    <b>Periodo_hasta:</b> <?php echo $result->periodo_hasta ?>
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