<div class="modal-content">
    <div class="modal-header modal-header-primary">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i></button>
        <h3><i class="fa fa-search"></i> <?php echo form_hidden('id',$result->id) ?></h3>
    </div>
    <div class="modal-body">
        <div class="form-horizontal">       
            <?php echo form_hidden('id',$result->id) ?>
            
                <div class="form-group">
                    <b>Sucursal:</b> <?php echo $result->sucursal_id ?>
                </div>

                <div class="form-group">
                    <b>Apellido nombre:</b> <?php echo $result->apellido_nombre ?>
                </div>

                <div class="form-group">
                    <b>Domicilio:</b> <?php echo $result->domicilio ?>
                </div>

                <div class="form-group">
                    <b>Localidad:</b> <?php echo $result->localidad ?>
                </div>

                <div class="form-group">
                    <b>Codigo postal:</b> <?php echo $result->codigo_postal ?>
                </div>

                <div class="form-group">
                    <b>Telefono:</b> <?php echo $result->telefono ?>
                </div>

                <div class="form-group">
                    <b>Activo:</b> <?php echo $result->activo ?>
                </div>

                <div class="form-group">
                    <b>Fecha alta:</b> <?php echo $result->fecha_alta ?>
                </div>

                <div class="form-group">
                    <b>Fecha baja:</b> <?php echo $result->fecha_baja ?>
                </div>                             
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-primary pull-left" data-dismiss="modal">Cerrar</button>
    </div>
</div>