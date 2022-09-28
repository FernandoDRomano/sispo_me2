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
                

                <div class="control-group">
                    <b>Link:</b> <?php echo $result->link ?>
                </div>
                

                <div class="control-group">
                    <b>Estado:</b> <?php echo $result->estado ?>
                </div>
                

                <div class="control-group">
                    <b>Parent:</b> <?php echo $result->parent ?>
                </div>
                

                <div class="control-group">
                    <b>Icono:</b> <?php echo $result->iconpath ?>
                </div>
                
                
                <div class="control-group">
                    <b>Estado:</b> <?php echo $result->active ?>
                </div>
                

                <div class="control-group">
                    <b>Dashboard:</b> <?php echo $result->dashboard ?>
                </div>                            
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-primary pull-left" data-dismiss="modal">Cerrar</button>
    </div>
</div>