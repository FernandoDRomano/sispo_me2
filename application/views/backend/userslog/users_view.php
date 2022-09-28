<div class="modal-content">
    <div class="modal-header modal-header-primary">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i></button>
        <h3><i class="fa fa-search"></i> <?php echo $result->apellido.', '.$result->nombre ?></h3>
    </div>
    <div class="modal-body">
        <div class="form-horizontal">       
            <div class="control-group">
                <b>Usuario:</b> <?php echo $result->username ?>
            </div>
            <div class="control-group">
                <b>Email:</b> <?php echo $result->email ?>
            </div>
            <div class="control-group">
                <b>Nombre:</b> <?php echo $result->nombre ?>
            </div>
            <div class="control-group">
                <b>Apellido:</b> <?php echo $result->apellido ?>
            </div>
            <div class="control-group">
                <b>Telefono:</b> <?php echo $result->telefono ?>
            </div>
            <div class="control-group">
                <b>Celular:</b> <?php echo $result->celular ?>
            </div>                              
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-primary pull-left" data-dismiss="modal">Cerrar</button>
    </div>
</div>