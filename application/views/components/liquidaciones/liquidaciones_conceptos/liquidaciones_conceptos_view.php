<div class="modal-content">
    <div class="modal-header modal-header-primary">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i></button>
        <h3><i class="fa fa-search"></i> <?php echo form_hidden('id',$result->id) ?></h3>
    </div>
    <div class="modal-body">
        <div class="form-horizontal">       
            <?php echo form_hidden('id',$result->id) ?>
            
                <div class="form-group">
                    <b>Concepto:</b> <?php echo $result->concepto ?>
                </div>

                <div class="form-group">
                    <b>Operacion:</b> <?php echo $result->operacion ?>
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