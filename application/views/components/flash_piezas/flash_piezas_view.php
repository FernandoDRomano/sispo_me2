<div class="modal-content">
    <div class="modal-header modal-header-primary">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i></button>
        <h3><i class="fa fa-search"></i> <?php echo form_hidden('id',$result->id) ?></h3>
    </div>
    <div class="modal-body">
        <div class="form-horizontal">       
            <?php echo form_hidden('id',$result->id) ?>
            
                <div class="form-group">
                    <b>Usuario_id:</b> <?php echo $result->usuario_id ?>
                </div>

                <div class="form-group">
                    <b>Servicio_id:</b> <?php echo $result->servicio_id ?>
                </div>

                <div class="form-group">
                    <b>Tipo_id:</b> <?php echo $result->tipo_id ?>
                </div>

                <div class="form-group">
                    <b>Sucursal_id:</b> <?php echo $result->sucursal_id ?>
                </div>

                <div class="form-group">
                    <b>Estado_id:</b> <?php echo $result->estado_id ?>
                </div>

                <div class="form-group">
                    <b>Comprobante_ingreso_id:</b> <?php echo $result->comprobante_ingreso_id ?>
                </div>

                <div class="form-group">
                    <b>Cantidad:</b> <?php echo $result->cantidad ?>
                </div>

                <div class="form-group">
                    <b>Barcode:</b> <?php echo $result->barcode ?>
                </div>

                <div class="form-group">
                    <b>Barcode_externo:</b> <?php echo $result->barcode_externo ?>
                </div>

                <div class="form-group">
                    <b>Destinatario:</b> <?php echo $result->destinatario ?>
                </div>

                <div class="form-group">
                    <b>Domicilio:</b> <?php echo $result->domicilio ?>
                </div>

                <div class="form-group">
                    <b>Codigo_postal:</b> <?php echo $result->codigo_postal ?>
                </div>

                <div class="form-group">
                    <b>Localidad:</b> <?php echo $result->localidad ?>
                </div>

                <div class="form-group">
                    <b>Vista:</b> <?php echo $result->vista ?>
                </div>

                <div class="form-group">
                    <b>Recibio:</b> <?php echo $result->recibio ?>
                </div>

                <div class="form-group">
                    <b>Documento:</b> <?php echo $result->documento ?>
                </div>

                <div class="form-group">
                    <b>Vinculo:</b> <?php echo $result->vinculo ?>
                </div>

                <div class="form-group">
                    <b>Datos_varios:</b> <?php echo $result->datos_varios ?>
                </div>

                <div class="form-group">
                    <b>Datos_varios_1:</b> <?php echo $result->datos_varios_1 ?>
                </div>

                <div class="form-group">
                    <b>Datos_varios_2:</b> <?php echo $result->datos_varios_2 ?>
                </div>

                <div class="form-group">
                    <b>Verifico_id:</b> <?php echo $result->verifico_id ?>
                </div>

                <div class="form-group">
                    <b>Rendicion_id:</b> <?php echo $result->rendicion_id ?>
                </div>

                <div class="form-group">
                    <b>Create:</b> <?php echo $result->create ?>
                </div>

                <div class="form-group">
                    <b>Update:</b> <?php echo $result->update ?>
                </div>                             
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-primary pull-left" data-dismiss="modal">Cerrar</button>
    </div>
</div>