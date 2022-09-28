if(!flash) var flash={};

flash.despachos = {
    ESTADO_INICIADO : <?=Despacho::ESTADO_INICIADO?>,
    ESTADO_ENVIADO : <?=Despacho::ESTADO_ENVIADO?>,
    ESTADO_RECIBIDO : <?=Despacho::ESTADO_RECIBIDO?>,
    ESTADO_VERIFICADO : <?=Despacho::ESTADO_VERIFICADO?>,
    ESTADO_ARCHIVADO_ORIGEN : <?=Despacho::ESTADO_ARCHIVADO_ORIGEN?>,
    ESTADO_ARCHIVADO_DESTINO : <?=Despacho::ESTADO_ARCHIVADO_DESTINO?>,
    ESTADO_CANCELADO : <?=Despacho::ESTADO_CANCELADO?>,
    ESTADO_INICIADO_NOMBRE : '<?=Despacho::estadoNombre(Despacho::ESTADO_INICIADO)?>',
    ESTADO_ENVIADO_NOMBRE : '<?=Despacho::estadoNombre(Despacho::ESTADO_ENVIADO)?>',
    ESTADO_RECIBIDO_NOMBRE : '<?=Despacho::estadoNombre(Despacho::ESTADO_RECIBIDO)?>',
    ESTADO_VERIFICADO_NOMBRE : '<?=Despacho::estadoNombre(Despacho::ESTADO_VERIFICADO)?>',
    ESTADO_ARCHIVADO_ORIGEN_NOMBRE : '<?=Despacho::estadoNombre(Despacho::ESTADO_ARCHIVADO_ORIGEN)?>',
    ESTADO_ARCHIVADO_DESTINO_NOMBRE : '<?=Despacho::estadoNombre(Despacho::ESTADO_ARCHIVADO_DESTINO)?>',
    ESTADO_CANCELADO_NOMBRE : '<?=Despacho::estadoNombre(Despacho::ESTADO_CANCELADO)?>',
    enviar : function (id)
    {
        this.confirm('¿Confirma enviar el despacho?', function(){
            flash.despachos.estado(id, flash.despachos.ESTADO_ENVIADO, function(response){
                $('#row-despacho-'+id).find('a.btn-editar, a.btn-enviar, a.btn-cancelar, a.btn-archivar').attr('disabled','disabled');
                $('#row-despacho-'+id).find('[data-field=estado]').html(flash.despachos.ESTADO_ENVIADO_NOMBRE);
            });
        });
    },
    cancelar : function(id)
    {
        this.confirm('¿Confirma cancelar el despacho?', function(){
            flash.despachos.estado(id, flash.despachos.ESTADO_CANCELADO, function(response){
                $('#row-despacho-'+id).find('a.btn-editar, a.btn-enviar, a.btn-cancelar').attr('disabled','disabled');
                $('#row-despacho-'+id).find('a.link-editar').removeAttr('href');
                $('#row-despacho-'+id).find('a.btn-archivar').removeAttr('disabled');
                $('#row-despacho-'+id).find('[data-field=estado]').html(flash.despachos.ESTADO_CANCELADO_NOMBRE);
            });
        });
    },
    archivar : function(id)
    {
        this.confirm('¿Confirma archivar el despacho?', function(){
            flash.despachos.estado(id, flash.despachos.ESTADO_ARCHIVADO_ORIGEN, function(response){
                $('#row-despacho-'+id).find('a.btn-editar, a.btn-enviar, a.btn-cancelar, a.btn-archivar').attr('disabled','disabled');
                $('#row-despacho-'+id).find('[data-field=estado]').html(flash.despachos.ESTADO_ARCHIVADO_ORIGEN_NOMBRE);
            });
        });
    },
    estado : function(id, estado, callback)
    {
        $.ajax({
            type: "POST",
            url: '<?=base_url()?>piezas/despachos/piezas_estado',
            data: {
                despacho_id : id,
                estado : estado
            },
            cache: false,
            success: function(response){
                callback(response);
            }
        });
    },
    confirm : function(text, callback)
    {
        $('#confimModal').modal();
        $('#confimModal div.modal-body').html(text);
        callback_confirm_modal = callback;
        $('body').off('click', '#confimModal button.btn-primary', callback_confirm_modal_function);
        $('body').on('click', '#confimModal button.btn-primary', callback_confirm_modal_function);
    },
    imprimir : function(id)
    {
        $('#pdf_despacho_id').val(id);
        $('#exportAPdf').submit();
    }
};
callback_confirm_modal = false;
var callback_confirm_modal_function = function()
{
    callback_confirm_modal();
    $('#confimModal').modal('hide');
};