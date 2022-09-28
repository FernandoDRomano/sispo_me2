<div class="modal-content">
    <div class="modal-header modal-header-primary">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i></button>
        <h3><i class="fa fa-search"></i> <?php echo form_hidden('id',$result->id) ?></h3>
    </div>
    <div class="modal-body">
        <div class="form-horizontal">       
            <?php echo form_hidden('id',$result->id) ?>
            
                <div class="form-group">
                    <b>Grupo:</b> <?php echo $result->grupo_id ?>
                </div>

                <div class="form-group">
                    <b>Nombre:</b> <?php echo $result->nombre ?>
                </div>

                <div class="form-group">
                    <b>Precio:</b> <?php echo $result->precio ?>
                </div>

                <div class="form-group">
                    <b>Acuse:</b> <?php echo $result->acuse ?>
                </div>

                <div class="form-group">
                    <b>Activo:</b> <?php echo $result->activo ?>
                </div>

                <div class="form-group">
                    <b>Web:</b> <?php echo $result->web ?>
                </div>

                <div class="form-group">
                    <b>Dias:</b> <?php echo $result->dias ?>
                </div>                             
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-primary pull-left" data-dismiss="modal">Cerrar</button>
    </div>
</div>