<div class="modal-content">
    <div class="modal-header modal-header-primary">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i></button>
        <h3><i class="fa fa-search"></i> <?php echo form_hidden('id',$result->id) ?></h3>
    </div>
    <div class="modal-body">
        <div class="form-horizontal">       
            <?php echo form_hidden('id',$result->id) ?>
            
                <div class="form-group">
                    <b>Estado:</b> <?php echo $result->cliente_estado_id ?>
                </div>

                <div class="form-group">
                    <b>Tipo:</b> <?php echo $result->tipo_cliente_id ?>
                </div>

                <div class="form-group">
                    <b>Nombre:</b> <?php echo $result->nombre ?>
                </div>

                <div class="form-group">
                    <b>Nombre fantasia:</b> <?php echo $result->nombre_fantasia ?>
                </div>

                <div class="form-group">
                    <b>IVA:</b> <?php echo $result->iva ?>
                </div>

                <div class="form-group">
                    <b>Cuit:</b> <?php echo $result->cuit ?>
                </div>

                <div class="form-group">
                    <b>Domicilio:</b> <?php echo $result->domicilio ?>
                </div>

                <div class="form-group">
                    <b>Localidad:</b> <?php echo $result->localidad ?>
                </div>

                <div class="form-group">
                    <b>Provincia:</b> <?php echo $result->provincia ?>
                </div>

                <div class="form-group">
                    <b>Codigo postal:</b> <?php echo $result->codigo_postal ?>
                </div>

                <div class="form-group">
                    <b>Telefonos:</b> <?php echo $result->telefonos ?>
                </div>

                <div class="form-group">
                    <b>Fecha_ingreso:</b> <?php echo $result->fecha_ingreso ?>
                </div>

                <div class="form-group">
                    <b>Observaciones:</b> <?php echo $result->observaciones ?>
                </div>

                <div class="form-group">
                    <b>Ejecutivo comercial:</b> <?php echo $result->ejecutivo_comercial ?>
                </div>                             
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-primary pull-left" data-dismiss="modal">Cerrar</button>
    </div>
</div>