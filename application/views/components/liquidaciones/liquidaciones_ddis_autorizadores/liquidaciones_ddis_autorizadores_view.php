<div class="modal-content">
    <div class="modal-header modal-header-primary">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i></button>
        <h3><i class="fa fa-search"></i> <?php echo form_hidden('id',$result->id) ?></h3>
    </div>
    <div class="modal-body">
        <div class="form-horizontal">       
            <?php echo form_hidden('id',$result->id) ?>
            
                <div class="form-group">
                    <b>User_id:</b> <?php echo $result->apellido.', '.$result->nombre ?>
                </div>

                <div class="form-group">
                    <b>Nivel de Autorizacion:</b> <?php echo $result->nivel_autorizacion ?>
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