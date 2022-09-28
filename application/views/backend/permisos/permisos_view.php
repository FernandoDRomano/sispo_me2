<div class="modal-content">
    <div class="modal-header modal-header-primary">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i></button>
        <h3><i class="fa fa-search"></i> Permiso</h3>
    </div>
    <div class="modal-body">
        <div class="form-horizontal">       
            <div class="control-group">
                <b>Menu:</b> <?php echo $result->menu_id ?>
            </div>
            <div class="control-group">
                <b>Grupo:</b> <?php echo $result->group_id ?>
            </div>
            <div class="control-group">
                <b>Leer:</b> <?php echo $result->read ?>
            </div>
            <div class="control-group">
                <b>Insertar:</b> <?php echo $result->insert ?>
            </div>
            <div class="control-group">
                <b>Actualizar:</b> <?php echo $result->update ?>
            </div>
            <div class="control-group">
                <b>Eliminar:</b> <?php echo $result->delete ?>
            </div>
            <div class="control-group">
                <b>Exportar:</b> <?php echo $result->exportar ?>
            </div>
            <div class="control-group">
                <b>Imprimir:</b> <?php echo $result->imprimir ?>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-primary pull-left" data-dismiss="modal">Cerrar</button>
    </div>
</div>