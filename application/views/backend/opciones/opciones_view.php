<div class="modal-content">
    <div class="modal-header modal-header-primary">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i></button>
        <h3><i class="fa fa-search"></i> <?php echo $result->descripcion ?></h3>
    </div>
    <div class="modal-body">
        <div class="form-horizontal">       
            <div class="control-group">
                    <b>Descripcion:</b> <?php echo $result->descripcion ?>
                </div>
                

                <div class="form-group">
                    <b>Nombre:</b> <?php echo $result->nombre ?>
                </div>
                

                <div class="form-group">
                    <b>Descripcion:</b> <?php echo $result->descripcion ?>
                </div>                           
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-primary pull-left" data-dismiss="modal">Cerrar</button>
    </div>
</div>