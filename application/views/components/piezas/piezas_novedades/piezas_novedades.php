<div class="col-xs-12">
    <div class="ibox-content">
        <?= form_open(current_url(), array('id' => "form-novedades-normales")); ?>
        <div id="errores"></div>
        <?php if ($this->session->flashdata('registro')): ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        Las piezas fueron actualizadas exitosamente.
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <div class="panel blank-panel">
            <div class="panel-heading">
                <div class="panel-options">
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#piezas_normales_novedades">Novedades Piezas Normales</a></li>
                     <!--   <li><a href="<?php /*echo base_url('piezas/novedades/piezas_simples_novedades')*/ ?>">Novedades Piezas Simples</a></li>-->
                    </ul>
                </div>
            </div>
            <div>
                <div >
                	<div class="row" ><!-- style="background: aliceblue;" -->
                      	<h3 class="text-center">Agregar piezas normales masivas</h3>
                      	<hr>
                		<div class="col-xs-6">
                          	<div class="form-group">
                              	<label for="estado_id">Estado masivo</label>
                              	<select id="estado_masivo_id" name="estado_masivo_id" class="form-control" required="">
                              	    <optgroup label="Estados Iniciales">
                                        <?php foreach ($estados_iniciales as $estado): ?>
                                            <option value="<?= $estado->id ?>"><?= $estado->nombre ?></option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                    
                                    <optgroup label="Estados de Rendiciones">
                                        <?php foreach ($estados as $estado): 
                                            if($estado->id == 31){
                                        ?>
                                            <option value="<?= $estado->id ?>"><?= $estado->nombre ?></option>
                                        <?php } endforeach; ?> 
                                            <option value="8">Domicilio Insuficiente</option>
                                            <option value="23">Zona No Atendida</option>
                                        Zona No Atendida
                                        
                                    </optgroup>
                                    
                                    
                                    <optgroup label="Estados Organizativos">
                                        <?php foreach ($estados_organizativos as $estado_organizativo): ?>
                                            <option value="<?= $estado_organizativo->id ?>"><?= $estado_organizativo->nombre ?></option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                    <optgroup label="Estados Despachos">
                                        <?php foreach ($estados_despachos as $estado_despacho): ?>
                                            <option value="<?= $estado_despacho->id ?>"><?= $estado_despacho->nombre ?></option>
                                        <?php endforeach; ?>
                                    </optgroup>
                              	</select>
                          	</div>
                      	</div>
                        <div class="col-xs-6">
                            <div class="col-xs-12">
                          		<div class="form-group">
                              		<label for="estado_masivo_id">Carga Masiva</label>
                              		<input type="file" id="PiezasMasivas" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                          		</div>
                          	</div>
                            <div>
                                <label class="radio-inline"><input type="radio" name="buscar_masiva_por" value="1">Pieza ID</label>
                                <label class="radio-inline"><input type="radio" name="buscar_masiva_por" value="2" checked="checked">C&oacute;digo Externo</label>
                            </div>
                            <div class="col-xs-12">
                    			<div class="form-group text-right">
                        			<a id='EnviarPiezasMasivas' class="btn btn-primary EnvioMasivoDePiezas" disabled style="display: none;"><i class="fa fa-arrow-down"></i>Agregar Todas Las Piezas</a>
                          		</div>
                    		</div>
                            <div class="col-xs-12">
                    			<div class="form-group text-right">
                        			<a id='EnviarPiezasMasivas2' class="btn btn-primary EnvioMasivoDePiezas2"  disabled ><i class="fa fa-arrow-down"></i>Agregar Todas Las Piezas</a>
                          		</div>
                    		</div>
                      	</div>
                	</div>
                </div>
                
                <div class="col-xs-12" style="display: grid;">
                    <p id='mensajemasivo'></p>
                </div>
                <table  id="TablaDeEesultado" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered" style="display: none;">
                    <thead>
                    <tr>
                    <th>Codigo Interno</th>
                    <th>Descripcion</th>
                    <th>Estado</th>
                    </thead>
                    <tbody id="body-grilla-TablaDeEesultado">
                    </tbody>
                </table>
                
                <div id="TablaDeErroreshide"  style="display: none;">
                    <spam style="color:red;margin-left:10px;font-weight:normal;line-height:.8em;font-size:16px">Piezas Duplicadas</spam>
                    <table  id="TablaDeErrores" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
                        
                        <thead>
                        <tr>
                        <th>id</th>
                        <th>barcode</th>
                        </thead>
                        <tbody id="body-grilla-TablaDeErrores">
                        </tbody>
                    </table>
                </div>
                <hr>
                <div class="row" ><!-- style="background: aliceblue;" -->
                    <h3 class="text-center">Agregar piezas normales</h3>
                    <hr>
                    <?php /*
                      <div class="col-xs-4">
                      <div class="form-group">
                      <label for="fecha_recepcion">Fecha</label>
                      <input  id="fecha_recepcion" name="fecha_recepcion" type="text" class="form-control datepicker" placeholder="Fecha Salida" value="<?=date('d-m-Y')?>" required autocomplete="off" />
                      </div>
                      </div>
                     */ ?>
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label for="estado_id">Estado</label>
                            <select id="estado_id" name="estado_id" class="form-control"  required >
                                <optgroup label="Estados Iniciales">
                                    <?php foreach ($estados_iniciales as $estado): ?>
                                        <option value="<?= $estado->id ?>"><?= $estado->nombre ?></option>
                                    <?php endforeach; ?>
                                </optgroup>
                                <optgroup label="Estados de Rendiciones">
                                    <?php foreach ($estados as $estado): ?>
                                        <option value="<?= $estado->id ?>"><?= $estado->nombre ?></option>
                                    <?php endforeach; ?>
                                </optgroup>
                                <optgroup label="Estados Organizativos">
                                    <?php foreach ($estados_organizativos as $estado_organizativo): ?>
                                        <option value="<?= $estado_organizativo->id ?>"><?= $estado_organizativo->nombre ?></option>
                                    <?php endforeach; ?>
                                </optgroup>
                                <optgroup label="Estados Despachos">
                                    <?php foreach ($estados_despachos as $estado_despacho): ?>
                                        <option value="<?= $estado_despacho->id ?>"><?= $estado_despacho->nombre ?></option>
                                    <?php endforeach; ?>
                                </optgroup>
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label for="fecha_recepcion">Codigo barra</label>
                            <input  id="codigo_barra" name="codigo_barra" type="text" class="form-control" placeholder="Codigo barra" autocomplete="off" />
                            <input  id="codigo_barra_id" name="piezas_id" type="hidden" value="0" />
                            <input  id="piezas_ids" name="piezas_id" type="hidden" value="" />
                            <input  id="cargados" name="cargados" type="hidden" value="" />
                            <input  id="ids_agregados" name="ids_agregados" type="hidden" value="" />
                        </div>
                        <div>
                            <label class="radio-inline"><input type="radio" name="buscar_por" value="1">Pieza ID</label>
                            <label class="radio-inline"><input type="radio" name="buscar_por" value="2" checked="checked">C&oacute;digo Externo</label>
							<label ><input  id="Anteponer" name="Anteponer" type="text" />Anteponer A Barcode</label>
                        </div>
                    </div>
                    <div class="col-xs-3" style="display:none">
                        <div class="form-group">
                            <label for="cantidad">Cantidad</label>
                            <input type="number" class="form-control" id="cantidad" name="cantidad" value=""/>
                            <input  id="cantidad_original" type="hidden" value="" />
                            <input  id="tipo_id" type="hidden" value="" />
                        </div>
                    </div>
                </div>
                    <div class="col-xs-12">
                        <div class="control-group text-right">
                            <div class="controls">
                                <a class="btn btn-primary agregar-grilla" href="javascript:;"><i class="fa fa-arrow-down"></i> Agregar</a>
                                
                                <!--<a class="btn btn-success btn-submit" href="javascript:;"><i class='fa fa-floppy-o'></i> Guardar</a>-->
                            </div>
                        </div>
                    </div>
                <hr>
            </div>
            <table  id="results" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
                <thead>
                    <tr>
                        <th>Codigo Interno</th>
                        <th>Descripcion</th>
                        <th>Estado</th>
                        <th class="col-xs-1 no-sort">&nbsp;</th>
                    </tr>
                </thead>
                <tbody id="body-grilla">
                </tbody>
            </table>
        </div>
        <?= form_close(); ?>
        <div id="dialog-message" title="Estados Condicionales"></div>
    </div>
</div>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="/resources/demos/style.css">

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/jszip.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/xlsx.js"></script>
<script type="text/javascript">
	var json_object;
	var piezas = [];
	function handleFileSelect(evt) {
		var input = this;
		var url = $(this).val();
		var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
		if(ext == ''){
		    document.getElementById('EnviarPiezasMasivas').setAttribute('disabled', '');
		    document.getElementById('EnviarPiezasMasivas2').setAttribute('disabled', '');
			piezas = [];
			return false;
		}
		if(ext != 'xlsx' && ext != 'xls'){
			//alert('La Extencion: .' + ext + ' No Se Admite');
			piezas = [];
			
        	var message = $("<label id='PiezasMasivas-message' style='color:red;margin-left:10px;font-weight:normal;line-height:.8em;font-size:16px'>" + 'La Extencion: .' + ext + ' No Se Admite' + "</label>");
            $('input#PiezasMasivas').after(message);
            $("#PiezasMasivas-message").show();
            $("#PiezasMasivas-message").delay(5000).fadeOut('slow');
		    document.getElementById('EnviarPiezasMasivas').setAttribute('disabled', '');
		    document.getElementById('EnviarPiezasMasivas2').setAttribute('disabled', '');
			return false;
		}else{
		    piezas = [];
			var files = evt.target.files; // FileList object
			var xl2json = new ExcelToJSON();
			xl2json.parseExcel(files[0]);
		}
	}
	var ExcelToJSON = function() {
		this.parseExcel = function(file) {
			var reader = new FileReader();
			reader.onload = function(e) {
				var data = e.target.result;
				var workbook = XLSX.read(data, {type: 'binary'});
				workbook.SheetNames.forEach(function(sheetName) {
					var XL_row_object = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[sheetName]);
					json_object = JSON.stringify(XL_row_object);
				})
                object = JSON.parse(json_object);
              	var columnas = object.length;
              	console.log(columnas);
              	var filas =0;
				for(var i in object){
					var key = i;
                    //console.log(object[i]);
					var val = object[i];
                    var columnas = Object.keys(val)
                    //console.log(columnas.length);
                    var t=0;
                
                    console.log(val);
                    console.log(columnas[0]);
                    if(filas==0){
                         piezas[0] = columnas[0];
                         piezas[filas+1] = val[columnas[0]];
                    }else{
                        piezas[filas+1] = val[columnas[0]];
                    }
                    
                    
                    /*
					if(filas==0){
					    const found = columnas.find(element => element == 'Pieza');
					    if(found == undefined){
                        	var message = $("<label id='PiezasMasivas-message' style='color:red;margin-left:16px;font-weight:normal;font-size:16px'>" + "Defina La Columna Pieza para Buscar Id O BarcodeExterno Dentro Del Documento" + "</label>");
                            $('input#PiezasMasivas').after(message);
                            $("#PiezasMasivas-message").show();
                            $("#PiezasMasivas-message").delay(5000).fadeOut('slow');
                            piezas = [];
		                    document.getElementById('EnviarPiezasMasivas').setAttribute('disabled', '');
                            return false;
					    }
					    //if(val)
					}
					for(var j in val){
                        t++;
                    	var sub_key = j;
						var sub_val = val[j];
                    	if(sub_key == 'Pieza'){
                        	piezas[i] = sub_val;
                            break;
						}
                        if(columnas.length == t){
                        	//console.log('Ocurio Un Error Al Intentar De Leer La Linea ' + ii);
                        	var ii= (i*1)+2;
                        	var message = $("<label id='PiezasMasivas-message' style='color:red;margin-left:10px;font-weight:normal;line-height:.8em;font-size:16px'>" + "Ocurio Un Error Al Intentar Leer La Linea " + ii + "</label>");
                            $('input#PiezasMasivas').after(message);
                            $("#PiezasMasivas-message").show();
                            $("#PiezasMasivas-message").delay(5000).fadeOut('slow');
                        	
                            piezas = [];
		                    document.getElementById('EnviarPiezasMasivas').setAttribute('disabled', '');
                            return false;
                        }
                        
					}
					*/
                    filas++;
				}
				//$("#EnviarPiezasMasivas").disabled = false;
		        document.getElementById('EnviarPiezasMasivas').removeAttribute('disabled');
		        document.getElementById('EnviarPiezasMasivas2').removeAttribute('disabled');
				
			};
			reader.onerror = function(ex) {
				console.log(ex);
			};
			reader.readAsBinaryString(file);
		};
	};
	document.getElementById('PiezasMasivas').addEventListener('change', handleFileSelect, false);
	
    var BultoDeConsultas = 50;
    $('.EnvioMasivoDePiezas').click(function(event) {
        
        const erroresentabla = document.getElementById('body-grilla-TablaDeErrores');
        erroresentabla.innerHTML = '';
        $('#TablaDeErroreshide').hide();
        
        const datosentabla = document.getElementById('body-grilla-TablaDeEesultado');
        datosentabla.innerHTML = '';
        $('#TablaDeEesultado').hide();
       console.log(piezas);
       
       /*
       var other_data = $('#form-novedades-normales').serializeArray();
        $.each(other_data,function(key,input){
            form_data.append(input.name,input.value);
        });
        */
        
        
        
        var buscar_por = $('input:radio[name=buscar_masiva_por]:checked').val();
        var estado_id = $('#estado_masivo_id option').filter(':selected').val();
        //console.log(other_data);
        //var form_data = new FormData();
        if(piezas != undefined ){
            if(piezas.length > 0){
                if(piezas.length >= 50){
                    for(var i=0;i<BultoDeConsultas;i++){
                        ajaxCall(i,piezas.length,buscar_por,estado_id);
                    }
                }else{
                    for(var i=0;i<piezas.length;i++){
                        ajaxCall(i,piezas.length,buscar_por,estado_id);
                    }
                    
                }
/*
                //for(var i=0;i<piezas.length;i++){
                    form_data = new FormData();
                    form_data.append("estado_id",$('#estado_masivo_id option').filter(':selected').val());
                    form_data.append("codigo_barra",piezas[i]);
                    var buscar_por = $('input:radio[name=buscar_masiva_por]:checked').val();
                    form_data.append("buscar_por",buscar_por);
                    form_data.append("piezas_length",piezas.length);
                    form_data.append("i",0);
                    
                    $.ajax({
                        type: "POST",
                        url: '<?php echo base_url() . "ajax/novedades/grabar_novedad_pieza_normal" ?>',
                        data: form_data,//'codigo_barra='+$('#codigo_barra').val()+'&piezas_ids='+$('#piezas_ids').val(),
                        cache: false,
                        processData: false,
                        contentType: false,                
                        datatype:'json',
                        beforeSend: function() {
                        },
                        success: function(respuesta) {
                            console.log(this.data.get("codigo_barra"));
                            
                            var estado = $('#estado_masivo_id').val();
                            var pieza = $.parseJSON(respuesta);
                            if (pieza.respuesta) {
                                
                                mensaje_array = pieza.respuesta.split(";");
                                mensaje_detalle = "";
                                for(i=0 ; i < mensaje_array.length ; i++){
                                    mensaje_detalle += mensaje_array[i]+"</br>";
                                }
                                
                                var inputs = '<input type="hidden"  name="TablaDeEesultadopiezas[]" value="'+ this.data.get("codigo_barra") +'-'+estado+'">';
                                var html = '<tr id="TablaDeEesultadotr_'+ this.data.get("codigo_barra") +'">' +
                                '<td>' + this.data.get("codigo_barra") + '</td>' +
                                '<td>' + mensaje_detalle + '</td>' +
                                '<td>' + '</td>' +
                                '</tr>';
                                if ($('#piezas_ids').val() === '') {
                                    $('#piezas_ids').val(pieza.pieza_id);
                                } else {
                                    $('#piezas_ids').val($('#piezas_ids').val() + ',' + pieza.pieza_id);
                                }
                                $('#body-grilla-TablaDeEesultado').append(html);
                                $('#body-grilla-TablaDeEesultado').append(inputs);
                                return;
                            }
                            if (!pieza.status) {
                                //$('#body-grilla-TablaDeEesultado').html('');
                                var inputs = '<input type="hidden"  name="TablaDeEesultadopiezas[]" value="'+pieza.pieza_id+'-'+estado+'">';
                                var html = '<tr id="TablaDeEesultadotr_'+pieza.pieza_id+'">' +
                                '<td>' + pieza.pieza_id + '</td>' +
                                '<td>' + pieza.domicilio + ' - ' + pieza.localidad_destino + ' - ' + pieza.codigo_postal_destino + '</td>' +
                                '<td>' + $("#estado_masivo_id option[value='" + $('#estado_masivo_id').val() + "']").text() + '</td>' +
                                '</tr>';
                                if ($('#piezas_ids').val() === '') {
                                    $('#piezas_ids').val(pieza.pieza_id);
                                } else {
                                    $('#piezas_ids').val($('#piezas_ids').val() + ',' + pieza.pieza_id);
                                }
                                $('#body-grilla-TablaDeEesultado').append(html);
                                $('#body-grilla-TablaDeEesultado').append(inputs);
                                //$('#codigo_barra').val('');
                                //barcodear();
                            } else {
                                var inputs = '<input type="hidden"  name="TablaDeEesultadopiezas[]" value="'+ this.data.get("codigo_barra") +'-'+estado+'">';
                                var html = '<tr id="TablaDeEesultadotr_'+ this.data.get("codigo_barra") +'">' +
                                '<td>' + this.data.get("codigo_barra") + '</td>' +
                                '<td>' + pieza.status + '</td>' +
                                '<td>' + '</td>' +
                                '</tr>';
                                if ($('#piezas_ids').val() === '') {
                                    $('#piezas_ids').val(pieza.pieza_id);
                                } else {
                                    $('#piezas_ids').val($('#piezas_ids').val() + ',' + pieza.pieza_id);
                                }
                                $('#body-grilla-TablaDeEesultado').append(html);
                                $('#body-grilla-TablaDeEesultado').append(inputs);
                            }
                        },
                        error: function(XMLHttpRequest, textStatus, errorThrown) { 
                            
                        }  
                    });
                    //console.log(piezas[i]);
                //}
            */
            }
        }
        //console.log(form_data.values());
        /*
        console.log(form_data);
        for (var value of form_data.values()) {
            console.log(value);
        }
        
        for (var keys of form_data.keys()) {
            console.log(keys);
        }
        */
        //"piezas_id"
        
    });
	
    $('.EnvioMasivoDePiezas2').click(function(event){
        
		//var data_codigo_barra = $('#codigo_barra').val();
        var buscar_por = $('input:radio[name=buscar_masiva_por]:checked').val();
        var estado_id = $('#estado_masivo_id option').filter(':selected').val();
        //console.log(other_data);
        //var form_data = new FormData();
        
        const erroresentabla = document.getElementById('body-grilla-TablaDeErrores');
        erroresentabla.innerHTML = '';
        $('#TablaDeErroreshide').hide();
        
        const datosentabla = document.getElementById('body-grilla-TablaDeEesultado');
        datosentabla.innerHTML = '';
        $('#TablaDeEesultado').hide();
        
        if(piezas != undefined ){
            var form_data = new FormData();
    	    form_data = new FormData();
            form_data.append("buscar_por",buscar_por);
            form_data.append("estado_id",estado_id);
            form_data.append("piezas",piezas);
            
            
            $.ajax({
                type: "POST",
                url: '<?php echo base_url() . "ajax/Novedades_masivas/grabar_novedad_masivas_pieza_normal" ?>',
                data: form_data,
                cache: false,
                processData: false,
                contentType: false,  
                datatype: 'json',
                beforeSend: function() {
                    //$('#errores').html('<h3><i class="fa fa-spinner fa-spin"></i></h3>');
                    //$('.btn').addClass('disabled');
                },
                success: function(respuesta) {
                    var array_codigos = [];
                    var resp = $.parseJSON(respuesta);
                    if(resp.status=='msj'){
                        var message = $("<label id='mensajecodigosrepetidos' style='margin-left:10px;font-weight:normal;line-height:.8em;font-size:18px'>" + resp.data + "</label>");
                        $('p#mensajemasivo').after(message);
                        $('#mensajecodigosrepetidos').show();
                        $('#mensajecodigosrepetidos').delay(1500).fadeOut('slow', function() { $(this).remove(); });
                    }
                    
                    if(resp.status=='susses'){
                        var resultados = $.parseJSON(respuesta);
                        var i=0;
                        var time = 100 * (resultados.data.length);
                        
                        $.each(resultados.data, function(key, value) {
                            var html = '<tr id="TablaDeEesultadotr_'+ value.id +'">' +
                            '<td>' + value.identificador + '</td>' +
                            '<td>' + value.Descripcion + '</td>' +
                            '<td>' + value.estado_id + '</td>' +
                            '</tr>';
                            $('#body-grilla-TablaDeEesultado').append(html);
                            $("#TablaDeEesultado").show();
                        })
                    }
                    
                    if(resp.status=='error'){
                        var codigos_iguales = $.parseJSON(respuesta);
                        var i=0;
                        var time = 100 * (codigos_iguales.data.length);
                        
                        $.each(codigos_iguales.data, function(key, value) {
                            var html = '<tr id="TablaDeErrorestr_'+ value.id +'">' +
                            '<td>' + value.id + '</td>' +
                            '<td>' + value.barcode_externo + '</td>' +
                            '</tr>';
                            $('#body-grilla-TablaDeErrores').append(html);
                            $("#TablaDeErroreshide").show();
                        })
                    }
                    return;
                }
            })
        }
    });
    
	function ajaxCall(i,piezas_length,buscar_por,estado_id){
	    //var buscar_por = $('input:radio[name=buscar_por]:checked').val();
	    var form_data = new FormData();
	    form_data = new FormData();
        form_data.append("estado_id",estado_id);
        form_data.append("codigo_barra",piezas[i]);
        //var buscar_por = $('input:radio[name=buscar_masiva_por]:checked').val();
        form_data.append("buscar_por",buscar_por);
        form_data.append("piezas_length",piezas.length);
        form_data.append("i",i);
	    
        $.ajax({
            type: "POST",
                url: '<?php echo base_url() . "ajax/novedades/grabar_novedad_pieza_normal" ?>',
                data: form_data,//'codigo_barra='+$('#codigo_barra').val()+'&piezas_ids='+$('#piezas_ids').val(),
                cache: false,
                processData: false,
                contentType: false,                
                datatype:'json',
                beforeSend: function() {
                },
                success: function(respuesta) {
                    console.log(this.data.get("codigo_barra"));
                    var estado = $('#estado_masivo_id').val();
                    var pieza = $.parseJSON(respuesta);
                    if (pieza.respuesta) {
                        
                        mensaje_array = pieza.respuesta.split(";");
                        mensaje_detalle = "";
                        for(i=0 ; i < mensaje_array.length ; i++){
                            mensaje_detalle += mensaje_array[i]+"</br>";
                        }
                        
                        var inputs = '<input type="hidden"  name="TablaDeEesultadopiezas[]" value="'+ this.data.get("codigo_barra") +'-'+estado+'">';
                        var html = '<tr id="TablaDeEesultadotr_'+ this.data.get("codigo_barra") +'">' +
                        '<td>' + this.data.get("codigo_barra") + '</td>' +
                        '<td>' + mensaje_detalle + '</td>' +
                        '<td>' + '</td>' +
                        '</tr>';
                        if ($('#piezas_ids').val() === '') {
                            $('#piezas_ids').val(pieza.pieza_id);
                        } else {
                            $('#piezas_ids').val($('#piezas_ids').val() + ',' + pieza.pieza_id);
                        }
                        $('#body-grilla-TablaDeEesultado').append(html);
                        $('#body-grilla-TablaDeEesultado').append(inputs);
                        var PiezasActual = (this.data.get("i")*1) + BultoDeConsultas;
                        console.log(PiezasActual);
                        if( PiezasActual < piezas.length ){
                            ajaxCall( PiezasActual, piezas.length, this.data.get("buscar_por"), this.data.get("estado_id") );
                        }
                        $("#TablaDeEesultado").show();
                        return;
                    }
                    if (!pieza.status) {
                        //$('#body-grilla-TablaDeEesultado').html('');
                        var inputs = '<input type="hidden"  name="TablaDeEesultadopiezas[]" value="'+pieza.pieza_id+'-'+estado+'">';
                        var html = '<tr id="TablaDeEesultadotr_'+pieza.pieza_id+'">' +
                        '<td>' + pieza.pieza_id + '</td>' +
                        '<td>' + pieza.domicilio + ' - ' + pieza.localidad_destino + ' - ' + pieza.codigo_postal_destino + '</td>' +
                        '<td>' + $("#estado_masivo_id option[value='" + $('#estado_masivo_id').val() + "']").text() + '</td>' +
                        '</tr>';
                        if ($('#piezas_ids').val() === '') {
                            $('#piezas_ids').val(pieza.pieza_id);
                        } else {
                            $('#piezas_ids').val($('#piezas_ids').val() + ',' + pieza.pieza_id);
                        }
                        $('#body-grilla-TablaDeEesultado').append(html);
                        $('#body-grilla-TablaDeEesultado').append(inputs);
                        $("#TablaDeEesultado").show();
                        //$('#codigo_barra').val('');
                        //barcodear();
                    } else {
                        var inputs = '<input type="hidden"  name="TablaDeEesultadopiezas[]" value="'+ this.data.get("codigo_barra") +'-'+estado+'">';
                        var html = '<tr id="TablaDeEesultadotr_'+ this.data.get("codigo_barra") +'">' +
                        '<td>' + this.data.get("codigo_barra") + '</td>' +
                        '<td>' + pieza.status + '</td>' +
                        '<td>' + '</td>' +
                        '</tr>';
                        if ($('#piezas_ids').val() === '') {
                            $('#piezas_ids').val(pieza.pieza_id);
                        } else {
                            $('#piezas_ids').val($('#piezas_ids').val() + ',' + pieza.pieza_id);
                        }
                        $('#body-grilla-TablaDeEesultado').append(html);
                        $('#body-grilla-TablaDeEesultado').append(inputs);
                        $("#TablaDeEesultado").show();
                    }
                    //console.log("Reenviando");
                    var PiezasActual = (this.data.get("i")*1) + BultoDeConsultas;
                        console.log(PiezasActual);
                    if( PiezasActual < piezas.length ){
                        ajaxCall( PiezasActual, piezas.length, this.data.get("buscar_por"), this.data.get("estado_id") );
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    var PiezasActual = (this.data.get("i")*1) + BultoDeConsultas;
                        console.log(PiezasActual);
                    if( PiezasActual < piezas.length ){
                        ajaxCall( PiezasActual, piezas.length, this.data.get("buscar_por"), this.data.get("estado_id") );
                    }
                }  
        });
	}
	
	
	
</script>


<script type="text/javascript">
<?php //include 'script.js';  ?>


$(document).ready(function() {
    $('.dataTables_empty').hide();
    $('#results_length').hide();
    $('#results_filter').hide();
    $('#results_info').hide();

         
 });
    codigos_iguales = [];
    $("#codigo_barra").keyup(function(e) {
        var codigo_barra = '"' + $("#codigo_barra").val() + '"';
        var pieza_id = 0;
        var buscar_por = $('input:radio[name=buscar_por]:checked').val();
		var data_codigo_barra = $('#codigo_barra').val();
		if($("#Anteponer").val() != ''){
			codigo_barra = '"' + $("#Anteponer").val() + $("#codigo_barra").val() + '"';
			data_codigo_barra = $("#Anteponer").val() + $("#codigo_barra").val();
			//alert(data_codigo_barra);
		}
		
        if (e.which == 13) {
            if (buscar_por === 2){ //Solo busca piezas iguales cuando se coloca un CODIGO EXTERNO de lo contrario solo debe encontrar 1 pieza
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url() . "ajax/piezas/traerPiezasIgualesParaNovedad" ?>',
                    data: 'codigo_barra=' + data_codigo_barra,
                    cache: false,
                    datatype: 'json',
                    beforeSend: function() {
                        //$('#errores').html('<h3><i class="fa fa-spinner fa-spin"></i></h3>');
                        //$('.btn').addClass('disabled');
                    },
                    success: function(respuesta) {
                        var array_codigos = [];
                        var codigos_iguales = $.parseJSON(respuesta);
                        if (!codigos_iguales.status) {
                            $.each(codigos_iguales.grilla, function(key, value) {
                                array_codigos.push('<strong>C.I:</strong> ' + value.numero + ' <strong>C車digo:</strong> ' + value.barcode_externo + ' <strong>ID:</strong> ' + value.pieza_id + '<br/>');
                                //pieza_id = value.pieza_id;
                            })
                        } else {
                            var message = $("<label id='pieza-normal-message' style='color:red;margin-left:10px;font-weight:normal;line-height:.8em;font-size:10px'>" + codigos_iguales.status + "</label>");
                            $('input#codigo_barra').after(message);
                            $("#codigo_barra").focus();
                            $("#codigo_barra").val('');
                            $("#pieza-normal-message").show();
                            $("#pieza-normal-message").delay(1500).fadeOut('slow');
                            $('#codigo_barra_id').val(0);
                            $('#codigo_barra').val('');
                            $('#codigo_barra').focus();
                        }

                        if (array_codigos.length == 1) {
                            pieza_id = codigos_iguales.grilla[0].pieza_id;
                        }
                        if (array_codigos.length > 1) {
                            bootbox.alert({
                                size: "small",
                                title: "Existen Codigos Iguales",
                                message: array_codigos,
                                callback: function() {
                                    setTimeout("$('#codigo_barra').focus();$('#codigo_barra').val('');", 0);
                                }
                            })
                            array_codigos.length = 0;
                        } else {
                            if (pieza_id > 0) {
                                $('#codigo_barra').val(pieza_id);
                                $('.agregar-grilla').click();
                                array_codigos.length = 0;
                            }
                        }
                    }
                })
            }else{
                //var pieza_id = 0;
                //$('#codigo_barra').val(pieza_id);
                $('.agregar-grilla').click();
            }
        }
    });
    
    
    
    $('.agregar-grilla').click(function(event) {
     var form_data = new FormData();
     
		if($("#Anteponer").val() != ''){
			$("#codigo_barra").val($("#Anteponer").val() + "" + $("#codigo_barra").val()) ;
			console.log($("#codigo_barra"));
		}
        var other_data = $('#form-novedades-normales').serializeArray();
        //console.log(other_data);
		
        $.each(other_data,function(key,input){
            form_data.append(input.name,input.value);
        });
        
        var buscar_por = $('input:radio[name=buscar_por]:checked').val();
       
        $.ajax({
            type: "POST",
            url: '<?php echo base_url() . "ajax/novedades/grabar_novedad_pieza_normal" ?>',
            data: form_data,//'codigo_barra='+$('#codigo_barra').val()+'&piezas_ids='+$('#piezas_ids').val(),
            cache: false,
            processData: false,
            contentType: false,                
            datatype:'json',
            beforeSend: function() {
                //$('#errores').html('<h3><i class="fa fa-spinner fa-spin"></i></h3>');
                //$('.btn').addClass('disabled');
            },
            success: function(respuesta) {
                //var pieza = $.parseJSON(respuesta);
                
                //codigo pa dafiti
            /*    data_codigo_barra = 'id='+$('#codigo_barra').val();
                
               $.ajax({
                    type: "GET",
                    url: 'http://boveda.sppflash.com.ar/api/dafiti/envios/',
                    data : data_codigo_barra,
                    cache: false,
                    processData: false,
                //    contentType: "application/json; charset=utf-8",           
                    datatype:'text',
                    
                });
                */
                
                var estado = $('#estado_id').val();
                var pieza = $.parseJSON(respuesta);
                
               // alert(JSON.stringify(vea[0]));
            
                if (pieza.respuesta) {
                    mensaje_array = pieza.respuesta.split(";");

                    mensaje_detalle = "";
                    for(i=0 ; i < mensaje_array.length ; i++){
                        mensaje_detalle += mensaje_array[i]+"</br>";
                    }

                    mensaje = '<p>'+
                              '  '+
                              mensaje_detalle+
                              '</p>'
                    $("#dialog-message").append(mensaje);

                    $( "#dialog-message" ).dialog({
                        minWidth: 400,
                        modal: true,
                        buttons: {
                            ACEPTAR: function() {
                                $("#dialog-message").html("");
                                $( this ).dialog( "close" );
                            }
                        }
                        });
                    return;
                }
                if (!pieza.status) {
                    $('#body-grilla').html('');
                    var inputs = '<input type="hidden"  name="piezas[]" value="'+pieza.pieza_id+'-'+estado+'">';
                    var html = '<tr id="tr_'+pieza.pieza_id+'">' +
                            '<td>' + pieza.pieza_id + '</td>' +
                            '<td>' + pieza.domicilio + ' - ' + pieza.localidad_destino + ' - ' + pieza.codigo_postal_destino + '</td>' +
                            '<td>' + $("#estado_id option[value='" + $('#estado_id').val() + "']").text() + '</td>' +
                            '</tr>';
                    if ($('#piezas_ids').val() === '') {
                        $('#piezas_ids').val(pieza.pieza_id);
                    } else {
                        $('#piezas_ids').val($('#piezas_ids').val() + ',' + pieza.pieza_id);
                    }

                    $('#body-grilla').append(html);
                    $('#body-grilla').append(inputs);
                    $('#codigo_barra').val('');
                    barcodear();
                } else {
                    var message = $("<label id='pieza-normal-message' style='color:red;margin-left:10px;font-weight:normal;line-height:.8em;font-size:10px'>" + pieza.status + "</label>");
                    $('input#codigo_barra').after(message);
                    $("#codigo_barra").focus();
                    $("#codigo_barra").val('');
                    $("#pieza-normal-message").show();
                    $("#pieza-normal-message").delay(1500).fadeOut('slow');
                    $('#codigo_barra_id').val(0);
                    $('#codigo_barra').val('');
                    $('#codigo_barra').focus();
                }
                
                
                
                
            }
            
            
            
        });
        
        
        
    });
    
    
    
   
    
    
    
    
   
</script>