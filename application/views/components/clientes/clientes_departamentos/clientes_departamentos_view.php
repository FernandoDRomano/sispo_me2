<div class="modal-content">
    <div class="modal-header modal-header-primary">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i></button>
        <h3><i class="fa fa-search"></i> <?php echo form_hidden('id',$result->id) ?></h3>
    </div>
    <div class="modal-body">
        <div class="form-horizontal">       
            <?php echo form_hidden('id',$result->id) ?>
            
                <div class="form-group">
                    <b>Cliente:</b> <?php echo $result->cliente_id ?>
                </div>

                <div class="form-group">
                    <b>Nombre contacto:</b> <?php echo $result->nombre_contacto ?>
                </div>

                <div class="form-group">
                    <b>Telefonos:</b> <?php echo $result->telefonos ?>
                </div>

                <div class="form-group">
                    <b>Emails:</b> <?php echo $result->emails ?>
                </div>                             
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-primary pull-left" data-dismiss="modal">Cerrar</button>
    </div>
</div>