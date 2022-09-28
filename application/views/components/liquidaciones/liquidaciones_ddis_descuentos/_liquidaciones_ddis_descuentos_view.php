<div class="modal-content">
    <div class="modal-header modal-header-primary">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i></button>
        <h3><i class="fa fa-search"></i> <?php echo form_hidden('id',$result->id) ?></h3>
    </div>
    <div class="modal-body">
        <div class="form-horizontal">       
            <?php echo form_hidden('id',$result->id) ?>
            
                <div class="form-group">
                    <b>Concepto:</b> <?php echo $result->concepto ?>
                </div>

                <div class="form-group">
                    <b>Importe:</b> <?php echo $result->importe ?>
                </div>    
                <div class="form-group">
                    <b>Tipo concepto:</b> <?php echo $result->tipo_concepto ?>
                </div> 
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-primary pull-left" data-dismiss="modal">Cerrar</button>
    </div>
</div>