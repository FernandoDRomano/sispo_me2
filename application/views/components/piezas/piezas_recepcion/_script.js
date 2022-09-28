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
    
    
    recibir_vista : function (id)
    {
        if ($('#cantidad').val()){
            var tipo_id = '1';//Piezas Simples
            var cantidad = $('#cantidad').val();
            var elementos = $('.simple');
            var size = $('.simple').size();
            var piezas = [];
            for(var i=0; i<size; i++){
                pieza_id = $(elementos[i]).attr('id').substr(10);
                piezas.push(pieza_id);
            };
        }else{
            var elementos = $('.info');
            var size = $('.info').size();
            var piezas = [];
            var tipo_id = '2';//Piezas Normales
            for(var i=0; i<size; i++){
                pieza_id = $(elementos[i]).attr('id').substr(10);
                piezas.push(pieza_id);
            };
        }
        this.estado(id, this.ESTADO_RECIBIDO, piezas, tipo_id, cantidad, function(data){
                location.reload();
            });
    },
    recibir : function (id)
    {
        this.estado(id, this.ESTADO_RECIBIDO, function(data){
            $('#row-despacho-'+id).find('a.btn-recibir').attr('disabled','disabled');
            $('#row-despacho-'+id).find('a.btn-archivar, a.btn-verificar').removeAttr('disabled');
            $('#row-despacho-'+id).find('[data-field=estado]').html(flash.despachos.ESTADO_RECIBIDO_NOMBRE);
        });
    },
    recibir_sin_verificar : function (id)
    {
        $('#loadding'+id).show();
        this.estado_sin_verificar(id, this.ESTADO_RECIBIDO, function(data){
            $('#row-despacho-'+id).find('a.btn-recibir').attr('disabled','disabled');
            $('#row-despacho-'+id).find('a.btn-archivar, a.btn-verificar').removeAttr('disabled');
            $('#row-despacho-'+id).find('[data-field=estado]').html(flash.despachos.ESTADO_RECIBIDO_NOMBRE);
            $('#loadding'+id).hide();
            window.location = location;
        });
    },
    archivar : function(id)
    {
        this.estado(id, this.ESTADO_ARCHIVADO_DESTINO, function(data){
            $('#row-despacho-'+id).find('a.btn-recibir, a.btn-verificar, a.btn-archivar').attr('disabled','disabled');
            $('#row-despacho-'+id).find('[data-field=estado]').html(flash.despachos.ESTADO_ARCHIVADO_DESTINO_NOMBRE);
        });
    },
    estado : function(id, estado, piezas, tipo_id, cantidad, callback)
    {
        $.ajax({
            type: "POST",
            dataType : "json",
            url: '<?=base_url()?>piezas/despachos/piezas_estado',
            data: {
                despacho_id : id,
                estado : estado,
                piezas : piezas,
                tipo_id: tipo_id,
                cantidad: cantidad
            },
            success: function(data){
                    callback(data);
            }
        });
    },
    estado_sin_verificar : function(id,estado, callback)
    {
        $.ajax({
            type: "POST",
            dataType : "json",
            url: '<?=base_url()?>piezas/despachos/piezas_estado_sin_verificar',
            data: {
                despacho_id : id,
                estado : estado,
            },
            success: function(data){
                    callback(data);
            }
        });
    },
    checkear : function()
    {
        $("#error_barcode, #error_estado").hide();
        var barcode = $('#codigo_barra').val();
        if(!barcode)
            return;

        var verificando = false;
        for(var i in this.piezas)
        {
            var pieza = this.piezas[i];
            if(pieza.barcode == barcode || pieza.barcode_externo == barcode)
            {
                if(pieza.estado_id == <?=Pieza::ESTADO_EN_TRNSITO?>)
                {
                    this.verificar(pieza.id);
                }
                else
                {
                    $("#error_estado").show();
                }
                verificando = true;
                break;
            }
        }
        if(!verificando)
        {
            $("#error_barcode").show();
        }
    },
    verificar : function(id, comprobante_ingreso_id, id_cantidad=null, servicio_id=null)//pieza_id o comprobante_id
    {   
        cantidad_recibida_simples = id_cantidad != null?$('#cantidad_'+id_cantidad).val():null;
        $.ajax({
            type: "POST",
            dataType : "json",
            url: '<?=base_url()?>piezas/recepcion/verificar_pieza',
            data: {
                despacho_id : <?=isset($despacho)?$despacho->id:0?>,
                id : id,
                cantidad_recibida_simples: cantidad_recibida_simples,
                servicio_id : servicio_id,
                comprobante_ingreso_id : comprobante_ingreso_id
            },
            success: function(data){ //alert(JSON.stringify(data));
                if (id_cantidad != null){
                    $('#row-pieza-'+ id_cantidad).addClass('info');
                    $('#cantidad_'+ id_cantidad).attr('disabled', 'disabled');
                    $('#row-pieza-'+ id_cantidad +' a.btn-verificar').attr('disabled', 'disabled');
                }
                if (id_cantidad == null){
                    $('#row-pieza-'+ id).addClass('info');
                    $('#cantidad_'+ id).attr('disabled', 'disabled');
                    $('#row-pieza-'+ id +' a.btn-verificar').attr('disabled', 'disabled');
                }
                $('#row-pieza-'+ id +' td[data-field=estado]').html('Verificada');
                
                for(var i in flash.despachos.piezas)
                {
                    if(flash.despachos.piezas[i].id == id)
                    {
                        flash.despachos.piezas[i].estado_id = <?=Pieza::ESTADO_EN_GESTION?>;
                    }
                }
                
                if(data.despacho_verificado)
                {
                    $(".btn-recibir").removeAttr("disabled");
                    $('[data-field=despacho_estado]').html('Verificado');
                }
            }
        });
    },

};
;