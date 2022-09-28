<?php
    $attributes = array('id' => 'clientes');
    echo form_open_multipart(current_url(), $attributes);
//    echo form_hidden('enviar_form','1');
//    echo form_hidden('id',$result->id);
?>

<style>
    .hr{
        border-color: #a8a8a8;
        margin-top: 10px;
    }

    .tituloFormulario {
        color: #697882;
        font-size: 22px;
        font-weight: 400;
        border-bottom: 2px solid #ADCE9D!important;
        padding-bottom: 7px;    
        margin-bottom: 2rem;
        margin-top: 1.3rem;
    }
</style>

<ul class="nav nav-tabs">
  <li class="active"><a data-toggle="tab" href="#datos-principales">Datos Principales</a></li>
  <li><a data-toggle="tab" href="#servicios-asociados">Servicios Asociados</a></li>
  <li><a data-toggle="tab" href="#parametros-paqueteria">Parametros de Paqueteria</a></li>
</ul>

<input  id="enviar_form" name="enviar_form" type="hidden" value="1" />
<input  id="id" name="id" type="hidden" value="<?php echo $result->id ?>" />
<div class="tab-content">
  <div id="datos-principales" class="tab-pane fade in active">
    <!--
    <div class="col-xs-12">
        <div class="ibox-content">
            <div class="row">
                <div class="col-xs-12">
                    <h3 class="tituloFormulario">Cliente</h3>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="cliente_estado_id">Estado</label>
                        <select id="cliente_estado_id" name="cliente_estado_id" class="form-control chosen-select" >
                            <?php foreach ($estados as $f) { ?>
                                <option value="<?php echo $f->id ?>" <?php if($f->id == $result->cliente_estado_id) echo "selected" ?>><?php echo $f->nombre ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="tipo_cliente_id">Tipo</label>
                        <select id="tipo_cliente_id" name="tipo_cliente_id" class="form-control chosen-select" >
                            <?php foreach ($tipos as $f) { ?>
                                <option value="<?php echo $f->id ?>" <?php if($f->id == $result->tipo_cliente_id) echo "selected" ?>><?php echo $f->nombre ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="nombre">Nombre<span class="required">*</span></label>
                        <input required id="nombre" name="nombre" type="text" class="form-control" placeholder="Nombre" value="<?php echo $result->nombre ?>" />
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="nombre_fantasia">Nombre fantasia</label>
                        <input  id="nombre_fantasia" name="nombre_fantasia" type="text" class="form-control" placeholder="Nombre fantasia" value="<?php echo $result->nombre_fantasia ?>" />
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="iva">IVA</label>
                        <?php// echo var_dump($iva);die;?>
                        <select id="iva_id" name="iva_id" class="form-control chosen-select" >
                            <?php foreach ($iva as $f) { ?>
                                <option value="<?php echo $f->id ?>" <?php if($f->id == $result->flash_iva_id) echo "selected" ?>><?php echo $f->descripcion ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="cuit">Cuit</label>
                        <input  id="cuit" name="cuit" type="text" class="form-control" placeholder="Cuit" value="<?php echo $result->cuit ?>" />
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="codigo_postal">Codigo postal</label>
                        <input  id="codigo_postal" name="codigo_postal" type="text" class="form-control" placeholder="Codigo postal" value="<?php echo $result->codigo_postal ?>" />
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="provincia">Provincia</label>
                        <input  id="provincia" name="provincia" type="text" class="form-control" placeholder="Provincia" value="<?php echo $result->provincia ?>" autocomplete="off" />
                        <input id="provincia_id" name="provincia_id" type="hidden" value="0" />
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="localidad">Localidad</label>
                        <input  id="localidad" name="localidad" type="text" class="form-control" placeholder="Localidad" value="<?php echo $result->localidad ?>" autocomplete="off" />
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="domicilio">Domicilio</label>
                        <input  id="domicilio" name="domicilio" type="text" class="form-control" placeholder="Domicilio" value="<?php echo $result->domicilio ?>" />
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="telefonos">Telefonos</label>
                        <input  id="telefonos" name="telefonos" type="text" class="form-control tags" placeholder="Telefonos" value="<?php echo $result->telefonos ?>" />
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="fecha_ingreso">Fecha ingreso</label>
                        <input  id="fecha_ingreso" name="fecha_ingreso" type="text" class="form-control datepicker" placeholder="Fecha ingreso" value="<?php echo $result->fecha_ingreso ?>" />
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="ejecutivo_comercial">Ejecutivo comercial</label>
                        <select id="ejecutivo_comercial" name="ejecutivo_comercial" class="form-control chosen-select" >
                            <option value="-1"><?php echo '' ?></option>
                            <?php foreach ($ejecutivo_comercial as $f) { ?>
                                <option value="<?php echo $f->id ?>" <?php if($f->id == $result->ejecutivo_comercial) echo "selected" ?>><?php echo $f->username ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="flash_paqueteria_tipo_id">Tipo Paqueteria</label>
                        <select id="flash_paqueteria_tipo_id" name="flash_paqueteria_tipo_id" class="form-control chosen-select">
                            <?php  foreach ($paqueteria_tipos as $f) { ?>
                                <option value="<?php echo $f->id ?>" <?php if($f->id == $result->flash_paqueteria_tipo_id) echo "selected" ?>>
                                    <?php echo $f->tipo ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="observaciones">Observaciones</label>
                <textarea  id="observaciones" name="observaciones" class="form-control" placeholder="Observaciones"><?php echo $result->observaciones ?></textarea>
            </div>
    	</div>
    </div>

    -->

    <div class="col-xs-12">
        <div class="ibox-content">    	
            <div class="row">
                <div class="col-xs-12">
                    <h3 class="tituloFormulario">Cliente</h3>
                </div>
                <!--
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="cliente_estado_id">Estado:</label>
                        <select id="cliente_estado_id" name="cliente_estado_id" class="form-control chosen-select" >
                            <?php foreach ($estados as $f) { ?>
                                <option value="<?php echo $f->id ?>"><?php echo $f->nombre ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                -->
        
                <div class="col-xs-12">
                    <div class="form-group">
                        <label for="nombre">Nombre / Razón Social: <span class="required">*</span></label>
                        <input required id="nombre" name="nombre" type="text" class="form-control" placeholder="Nombre" value="<?php echo $result->nombre ?>" />
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="nombre_fantasia">Nombre fantasia:</label>
                        <input  id="nombre_fantasia" name="nombre_fantasia" type="text" class="form-control" placeholder="Nombre fantasia" value="<?php echo $result->nombre_fantasia ?>" />
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="telefonos">Telefonos:</label>
                        <input  id="telefonos" name="telefonos" type="text" class="form-control tags" placeholder="Telefonos" value="<?php echo $result->telefonos ?>" />
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="fecha_ingreso">Fecha ingreso:</label>
                        <input  id="fecha_ingreso" name="fecha_ingreso" type="text" class="form-control datepicker" placeholder="Fecha ingreso" value="<?php echo $result->fecha_ingreso ?>" />
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="ejecutivo_comercial">Ejecutivo comercial: </label>
                        <input type="text" name="ejecutivo_comercial" id="ejecutivo_comercial" class="form-control" placeholder="Ingrese el ejecutivo comercial" value="<?php echo $result->ejecutivo_comercial ?>">
                        <!-- 
                        <label for="ejecutivo_comercial">Ejecutivo comercial: </label>
                        <select id="ejecutivo_comercial" name="ejecutivo_comercial" class="form-control chosen-select" >
                            <option value="-1"><?php echo '' ?></option>
                            <?php foreach ($ejecutivo_comercial as $f) { ?>
                                <option value="<?php echo $f->id ?>"><?php echo $f->username ?></option>
                            <?php } ?>
                        </select>
                        -->
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="tipo_cliente_id">Tipo:</label>
                        <select id="tipo_cliente_id" name="tipo_cliente_id" class="form-control chosen-select" >
                            <?php foreach ($tipos as $f) { ?>
                                <option value="<?php echo $f->id ?>" <?php echo $f->id == $result->tipo_cliente_id ? 'selected' : ''; ?>><?php echo $f->nombre ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <!--
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="cliente_estado_id">Estado</label>
                        <select id="cliente_estado_id" name="cliente_estado_id" class="form-control chosen-select" >
                            <option value="1" <?php if($result->cliente_estado_id == 1) echo "selected" ?>>Activo</option>
                            <option value="2" <?php if($result->cliente_estado_id == 2) echo "selected" ?>>Inactivo</option>
                        </select>
                    </div>
                </div>
                -->
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="flash_paqueteria_tipo_id">Tipo Paqueteria</label>
                        <select id="flash_paqueteria_tipo_id" name="flash_paqueteria_tipo_id" class="form-control chosen-select">
                            <?php  foreach ($paqueteria_tipos as $f) { ?>
                                <option value="<?php echo $f->id ?>" <?php if($f->id == $result->flash_paqueteria_tipo_id) echo "selected" ?>>
                                    <?php echo $f->tipo ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="col-xs-12">
                    <h3 class="tituloFormulario">Ubicación</h3>
                </div>
                <div class="col-xs-12">
                    <div class="form-group">
                        <label for="pais">País: <span class="required">*</span></label>
                        <select onchange="cargarProvincias(event)" name="pais" id="pais" class="form-control">
                            <option value="0">Seleccione una opción</option>
                            <?php foreach($paises as $p) { ?>
                                <option value="<?php echo $p->id ?>" <?php echo $result->pais == $p->id ? 'selected' : '' ;?>><?php echo $p->nombre?></option>
                            <?php }?>
                        </select>
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="provincia">Provincia / Departamento: <span class="required">*</span></label>
                        <select onchange="cargarLocalidades(event)" name="provincia" id="provincia" class="form-control">
                            <option value="0">Seleccione una opción</option>
                            <?php foreach($provincias as $p) { ?>
                                <option value="<?php echo $p->id ?>" <?php echo $result->provincia == $p->id ? 'selected' : '' ;?>><?php echo $p->nombre?></option>
                            <?php }?>
                        </select>
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="localidad">Localidad / Municipio: <span class="required">*</span></label>
                        <select name="localidad" id="localidad" class="form-control">
                            <option value="0">Seleccione una opción</option>
                            <?php foreach($localidades as $l) { ?>
                                <option value="<?php echo $l->id ?>" <?php echo $result->localidad == $l->id ? 'selected' : '' ;?>><?php echo $l->nombre?></option>
                            <?php }?>
                        </select>
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="codigo_postal">Codigo postal: </label>
                        <input  id="codigo_postal" name="codigo_postal" type="text" class="form-control" placeholder="Codigo postal" value="<?php echo $result->codigo_postal ?>"/>
                    </div>
                </div>
                <div class="col-xs-8">
                    <div class="form-group">
                        <label for="domicilio">Calle: <span class="required">*</span></label>
                        <input  id="domicilio" name="domicilio" type="text" class="form-control" placeholder="Calle" value="<?php echo $result->domicilio?>"/>
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="altura">Altura: <span class="required">*</span></label>
                        <input  id="altura" name="altura" type="text" class="form-control" placeholder="Altura" value="<?php echo $result->altura ?>"/>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="esquina_1">Esquina: </label>
                        <input  id="esquina_1" name="esquina_1" type="text" class="form-control" placeholder="" value="<?php echo $result->esquina_1 ?>"/>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="esquina_2">Esquina 2: </label>
                        <input  id="esquina_2" name="esquina_2" type="text" class="form-control" placeholder="" value="<?php echo $result->esquina_2 ?>"/>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="piso">Piso: </label>
                        <input  id="piso" name="piso" type="text" class="form-control" placeholder="" />
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="departamento">Departamento/Oficina: </label>
                        <input  id="departamento" name="departamento" type="text" class="form-control" placeholder="" value="<?php echo $result->departamento ?>"/>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="form-group">
                        <label for="observaciones">Observaciones: </label>
                        <textarea  id="observaciones" name="observaciones" class="form-control" placeholder="Observaciones"><?php echo $result->observaciones ?></textarea>
                    </div>
                </div>
    		
                <div class="col-xs-12">
                    <h3 class="tituloFormulario">Datos Fiscales</h3>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="iva">Condición fiscal: <span class="required">*</span></label>
                        <select id="iva_id" name="iva_id" class="form-control chosen-select" >
                            <?php foreach ($iva as $f) { ?>
                                <option value="<?php echo $f->id ?>" <?php echo $f->id == $result->flash_iva_id ? 'selected' : ''; ?>><?php echo $f->descripcion ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="cuit">CUIT / CUIL / otro: <span class="required">*</span></label>
                        <input  id="cuit" name="cuit" type="text" class="form-control" placeholder="Cuit" value="<?php echo $result->cuit ?>" />
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="dni">DNI: <span class="required">*</span></label>
                        <input  id="dni" name="dni" type="text" class="form-control" placeholder="DNI" value="<?php echo $result->dni ?>" />
                    </div>
                </div>

                
            
    	    </div>
        </div>
    </div>


    <div class="col-xs-12">
        <div class="ibox-content">
            <div class="row">
                <div class="col-xs-12">
                    <h3 class="tituloFormulario">Usuario</h3>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="username">Nombre de usuario: <span class="required">*</span></label>
                        <input disabled type="text" name="username" id="username" class="form-control" placeholder="Ingrese el nombre del usuario" value="<?php echo $user->username; ?>">
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="email">Email: <span class="required">*</span></label>
                        <input disabled type="email" name="email" id="email" class="form-control" placeholder="Ingrese el email del usuario" value="<?php echo $user->email; ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xs-12">
        <div class="ibox-content">
            <div class="row">
                <div class="col-xs-12">
                    <h3 class="tituloFormulario">Contacto</h3>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="contacto_contacto">Contacto<span class="required">*</span></label>
                        <input required id="contacto_contacto" name="contacto_contacto" type="text" class="form-control" placeholder="Contacto" value="<?php echo $contacto->contacto ?>" />
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="contacto_telefonos">Telefonos</label>
                        <input  id="contacto_telefonos" name="contacto_telefonos" type="text" class="form-control tags" placeholder="Telefonos" value="<?php echo $contacto->telefonos ?>" />
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="contacto_emails">Emails</label>
                        <input  id="contacto_emails" name="contacto_emails" type="text" class="form-control" placeholder="Emails" data-role="tagsinput" value="<?php echo $contacto->emails ?>" />
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="responsable_responsable">Responsable<span class="required">*</span></label>
                        <input required id="responsable_responsable" name="responsable_responsable" type="text" class="form-control" placeholder="Responsable" value="<?php echo $responsable->responsable ?>" />
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="responsable_telefonos">Telefonos</label>
                        <input  id="responsable_telefonos" name="responsable_telefonos" type="text" class="form-control tags" placeholder="Telefonos" value="<?php echo $responsable->telefonos ?>" />
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="responsable_emails">Emails</label>
                        <input  id="responsable_emails" name="responsable_emails" type="text" class="form-control" placeholder="Emails" data-role="tagsinput" value="<?php echo $responsable->emails ?>" />
                    </div>
                </div>

                <div class="col-xs-12">
                    <h3 class="tituloFormulario">Archivo</h3>
                </div>
                <div class="col-xs-12">
                    <div class="form-group">
                        <label for="archivo">Archivo</label>
                        <input onchange="ocultarPdf('archivo_pdf')" type="file" name="archivo" id="archivo" class="form-control" accept="application/pdf">
                    </div>
                </div>

                <?php if($result->archivo){ ?>
                    <div class="col-xs-12" id="archivo_pdf">
                        <!--
                        <div class="form-group" style="position: relative;">
                            <iframe src="http://docs.google.com/gview?url=<?php echo base_url() .'uploads/clientes/'. $result->archivo?>&embedded=true" style="width:100%; height:450px;" frameborder="0" ></iframe>
                        </div>
                        -->
                        <embed src="<?php echo '/uploads/clientes/'. $result->archivo?>" type="application/pdf" width="100%" height="450px" />
                    </div>
                <?php } ?>

            </div>
        </div>
    </div>

    <div class="col-xs-12">
        <div class="ibox-content">
            <div class="control-group">
                <div class="controls">
                    <?php echo form_button(array('type'  =>'submit','value' =>'Guardar Cambios','name'  =>'submit','class' =>'btn btn-success'), "<i class='fa fa-floppy-o'></i> Guardar"); ?>
                    <a class="btn btn-danger" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2); ?>"><i class="fa fa-arrow-circle-left"></i> Volver</a>
                </div>
            </div>
        </div>
    </div>
    </div>
    <div id="servicios-asociados" class="tab-pane fade">
        <div class="col-xs-12">
            <div class="ibox-content">
                <div class="control-group">
                    <div class="controls">
                        <div style="text-align: right">
                            <a data-toggle="modal" href="<?php echo base_url().'clientes/clientes/addPreciosEspeciales/'.$result->id ?>" data-target="#myModal" class="btn btn-info"><i class="fa fa-plus"></i>Vincular Servicios</a>
                        </div>
                        <?php include 'clientes_servicios_list.php';?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="parametros-paqueteria" class="tab-pane fade">
        <div class="col-xs-12">
            <div class="ibox-content">
                <div class="row">
                    <div class="col-xs-4">
                        <div class="form-group">
                            <label for="parametro_paqueteria">Pararmetro<span class="required">*</span></label>
                            <select id="parametro_paqueteria" name="parametro_paqueteria" class="form-control chosen-select">
                                <?php foreach ($paqueteria_tipos_select as $f) { ?>
                                    <option value="<?php echo $f ?>">
                                        <?php echo $f ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-4" id="rango1">
                        <div class="form-group">
                            <label for="rango_desde">Desde</label>
                            <input  id="rango_desde" name="rango_desde" type="text" class="form-control tags"/>
                        </div>
                    </div>
                    <div class="col-xs-4" id="rango2">
                        <div class="form-group">
                            <label for="rango_hasta">Hasta</label>
                            <input  id="rango_hasta" name="rango_hasta" type="text" class="form-control tags"/>
                        </div>
                    </div>
                    <div class="col-xs-2">
                        <div class="form-group">
                            <label for="valor_parametro">Valor Parametro</label>
                            <input  id="valor_parametro" name="valor_parametro" type="text" class="form-control tags"/>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-4">
                        <div class="control-group">
                            <a onclick="javascript:grabar_parametros();" class="btn btn-info" style="margin-top: 20px;" href="#"><i class="fa fa-arrow-circle-left"></i> Grabar</a>
                            <a onclick="javascript:borrar_parametros();" class="btn btn-info" style="margin-top: 20px;" href="#"><i class="fa fa-arrow-circle-left"></i> Borrar Param.</a>
                        </div>
                    </div>
                </div>
                
                        <?php include 'paqueteria_parametros/paqueteria_parametros_list.php';?>
            </div>
        </div>
    </div>
</div>
<?php echo form_close(); ?>
<form method='post' action='<?php echo base_url('clientes/clientes/addParametrosPaqueteria')?>' id='parametro_form'>
    <input type='hidden' id='cliente_id_form' name='cliente_id_form' value="">
    <input type='hidden' id='paqueteria_tipo_id_form' name='paqueteria_tipo_id_form' value="">
    <input type='hidden' id='parametro_paqueteria_form' name='parametro_paqueteria_form' value="">
    <input type='hidden' id='valor_parametro_form' name='valor_parametro_form' value="">
    <input type='hidden' id='parametro_paqueteria_tab' name='parametro_paqueteria_tab' value="parametros-paqueteria">
    <input type='hidden' id='rango_min_form' name='rango_min_form' value="1">
    <input type='hidden' id='rango_max_form' name='rango_max_form' value="">
    <input type='hidden' id='enviar_form' name='enviar_form' value="1">
</form>   
<form method='post' action='<?php echo base_url('clientes/clientes/deleteParametrosPaqueteria')?>' id='borrar_parametro_form'>
    <input type='hidden' id='cliente_id_form_delete' name='cliente_id_form_delete' value="">
    <input type='hidden' id='parametro_paqueteria_tab' name='parametro_paqueteria_tab' value="parametros-paqueteria">
</form> 
<script type="text/javascript">

    const nombre = document.getElementById('nombre')
    const condicion_fiscal = document.getElementById('iva_id')
    const cuit = document.getElementById('cuit')
    const dni = document.getElementById('dni')
    const pais = document.getElementById('pais')
    const provincia = document.getElementById('provincia')
    const localidad = document.getElementById('localidad')
    const domicilio = document.getElementById('domicilio')
    const altura = document.getElementById('altura')
    const contacto = document.getElementById('contacto_contacto')
    const responsable = document.getElementById('responsable_responsable')
    const form = document.getElementById('clientes')

    function ocultarPdf(id){
        const element = document.getElementById(id)
        if(element){
            //document.getElementById(id).style.display = 'none'
            element.style.display = 'none'
        }
    }

    function validar(){
        event.preventDefault()
        
        if(nombre.value.length == 0){
            alert("El Nombre/Razón Social del cliente no puede estar vacio")
            nombre.focus()
            return;  
        }

        if(condicion_fiscal.value == 0 || condicion_fiscal.value == ""){
            alert("Debe seleccionar la Condición fiscal")
            condicion_fiscal.focus()
            return;  
        }

        if(cuit.value.length == 0){
            alert("El CUIT/CUIL del cliente no puede estar vacio")
            cuit.focus()
            return;  
        }

        if(dni.value.length == 0){
            alert("El DNI del cliente no puede estar vacio")
            dni.focus()
            return;  
        }

        if(pais.value == 0 || pais.value == ""){
            alert("Debe seleccionar el país")
            pais.focus()
            return;  
        }

        if(provincia.value == 0 || provincia.value == ""){
            alert("Debe seleccionar la provincia/departamento")
            provincia.focus()
            return;  
        }

        if(localidad.value == 0 || localidad.value == ""){
            alert("Debe seleccionar el municion/localidad")
            localidad.focus()
            return;  
        }

        if(domicilio.value.length == 0){
            alert("El domicilio del cliente no puede estar vacio")
            domicilio.focus()
            return;  
        }

        if(altura.value.length == 0){
            alert("La altura del cliente no puede estar vacio")
            altura.focus()
            return;  
        }

        if(contacto.value.length == 0){
            alert("El contacto del cliente no puede estar vacio")
            contacto.focus()
            return;  
        }

        if(responsable.value.length == 0){
            alert("El responsable del cliente no puede estar vacio")
            responsable.focus()
            return;  
        }

        console.log("todos los campos completos...")

        document.getElementById('clientes').submit()
    }

    async function cargarProvincias(event){
        try {
            const provincia = document.getElementById('provincia')
            provincia.innerHTML = "<option value='0'>Seleccione una opción</option>"
            provincia.disabled = true
                
            const localidad = document.getElementById('localidad')
            localidad.innerHTML = "<option value='0'>Seleccione una opción</option>"
            localidad.disabled = true
            
            const pais = event.target.value
            const url = `<?php echo base_url() ?>zonas/zonas/getDepartamentos/${pais}`
            
            const resp = await fetch(url)
            const data = await resp.json()
            
            if(data.length > 0){
                let html = '';
                
                for (let index = 0; index < data.length; index++) {
                    html += `<option value="${data[index].id}">${data[index].nombre}</option>`
                }
                
                provincia.insertAdjacentHTML('beforeend', html);
                provincia.disabled = false
            }else{
                provincia.innerHTML = "<option value='0' disabled>No existen provincias/departamentos cargados</option>"
                provincia.disabled = false
            }

        } catch (error) {
            console.log(error)
        }
    }

    async function cargarLocalidades(event){
        try {
            const localidad = document.getElementById('localidad')
            localidad.innerHTML = "<option value='0'>Seleccione una opción</option>"
            localidad.disabled = true

            const provincia = event.target.value
            const url = `<?php echo base_url() ?>zonas/zonas/getLocalidades?provincias=${provincia}`

            const resp = await fetch(url)
            const data = await resp.json()
            
            if(data){                
                let html = '';
                
                for (let index = 0; index < data.length; index++) {
                    html += `<option value="${data[index].idLocalidad}">${data[index].localidad}</option>`
                }
                
                document.getElementById('localidad').insertAdjacentHTML('beforeend', html);
                localidad.disabled = false
            }

        } catch (error) {
            console.log(error)
        }
    }

    function grabar_parametros(){
        $('#cliente_id_form').val($('#id').val());
        $('#paqueteria_tipo_id_form').val($('#flash_paqueteria_tipo_id').val());
        $('#parametro_paqueteria_form').val($('#parametro_paqueteria').val());
        $('#valor_parametro_form').val($('#valor_parametro').val());
        $('#rango_min_form').val($('#rango_desde').val());
        $('#rango_max_form').val($('#rango_hasta').val());
        $('#parametro_form').submit();
    }
    
    function borrar_parametros(){
        $('#cliente_id_form_delete').val($('#id').val());
        $('#paqueteria_tipo_id_form').val($('#flash_paqueteria_tipo_id').val());
        $('#parametro_paqueteria_form').val($('#parametro_paqueteria').val());
        $('#valor_parametro_form').val($('#valor_parametro').val());
        $('#borrar_parametro_form').submit();
    }
$(document).ready(function() {

    //INICIALIZANDO EL SELECT DE PAISES
    $('#pais').select2();

    //INICIALIZANDO EL SELECT DE PROVINCIAS
    $('#provincia').select2();

    //INICIALIZANDO EL SELECT DE LOCALIDADES
    $('#localidad').select2();

    var tab_name = '<?php echo $tab?>';
    if (tab_name)
        $('a[href="#'+tab_name+'"]').tab('show');

    $('#contacto_emails').on('beforeItemAdd', function(event) {
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if (!regex.test(event.item)){
            event.cancel = true;
            var message = $("<label id='contacto-email-message' style='color:red;margin-left:10px;font-weight:normal;line-height:.8em;font-size:10px'>El email tiene un formato inválido</label>");
            $('input#contacto_emails').after(message);
            $( "#contacto-email-message" ).show();
            $( "#contacto-email-message" ).delay(1000).fadeOut('slow');
            //$('#contacto-email-message').remove();
        }
    });

     $('#responsable_emails').on('beforeItemAdd', function(event) {
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if (!regex.test(event.item)){
            event.cancel = true;
            var message = $("<label id='responsable-email-message' style='color:red;margin-left:10px;font-weight:normal;line-height:.8em;font-size:10px'>El email tiene un formato inválido</label>");
            $('input#responsable_emails').after(message);
            $('#responsable-email-message').show();
            $('#responsable-email-message').delay(1000).fadeOut('slow');
            //$('#contacto-email-message').remove();
        }
    });

    $('#cuit').mask('99-99999999-9');

    $('#cuit').blur(function(event) {
        cuit = $('#cuit').val();
        flag = true;
        if (typeof (cuit) === 'undefined')
           flag = true;
        cuit = cuit.toString().replace(/[-_]/g, "");
        if (cuit === '')
            flag = true; //No estamos validando si el campo esta vacio, eso queda para el "required"
        if (cuit.length !== 11)
            flag = false;
        else {
            var mult = [5, 4, 3, 2, 7, 6, 5, 4, 3, 2];
            var total = 0;
            for (var i = 0; i < mult.length; i++) {
                            total += parseInt(cuit[i]) * mult[i];
            }
            var mod = total % 11;
            var digito = mod === 0 ? 0 : mod === 1 ? 9 : 11 - mod;
        }
        flag = digito === parseInt(cuit[10]);
        if (!flag){
            var message = $("<label id='cuit-message' style='color:red;margin-left:10px;font-weight:normal;line-height:.8em;font-size:10px'>El CUIT no es correcto</label>");
            $('input#cuit').after(message);
            $('#cuit-message').show();
            $('#cuit-message').delay(2000).fadeOut('slow');
            $('#cuit').val('');
        }

    })

    if('<?php echo $result->provincia ?>' !== '')
        buscarProvincia('<?php echo $result->provincia ?>');

    var provincias = [<?php foreach ($provincias as $f) echo "{ id: ".$f->id.", name: '".$f->nombre."'}, " ?>];

    $('#provincia').typeahead({
        source: provincias,
        onSelect: function(item) {
            $('#provincia_id').val(0);
            $('#localidad').val('');

            if (item.value) {
                $('#provincia_id').val(item.value);
            }
        }
    });

    $('#provincia').change(function(event) {
        if ($('#provincia').val() == 'Result not Found' || $('#provincia').val() == '' || $('#provincia_id').val() == 0) {
            $('#provincia').val('').focus();
            $('#provincia_id').val(0);
        }
    });

    $('#localidad').typeahead({
        displayField: 'nombre',
        ajax: {
            url: '<?php echo base_url()."ajax/ubicacion/departamentos" ?>',
            method: "GET",
            loadingClass: "loading-ajax",
            preDispatch: function (query) {
                var provincia = $('#provincia_id').val();
                return {
                    search: query,
                    provincia: provincia
                }
            }
        },
        onSelect: function(item) {

        }
    });
    
    if ($('#parametro_paqueteria').val() === 'rango'){
        $('#rango1').show();
        $('#rango2').show();
    }else{
        $('#rango1').hide();
        $('#rango2').hide();
    }
            
    $('#parametro_paqueteria').change(function(event){
        if ($('#parametro_paqueteria').val() === 'rango'){
            $('#rango1').show();
            $('#rango2').show();
        }else{
            $('#rango1').hide();
            $('#rango2').hide();
        }
    })
});

function buscarProvincia(provincia){
    $.ajax({
        type: "GET",
        url: '<?php echo base_url()."ajax/ubicacion/provincia" ?>/' + provincia,
        data: {},
        cache: false,
        success: function(respuesta){
            var p = jQuery.parseJSON(respuesta);
            $('#provincia_id').val(p.id);
        }
    });
}
</script>