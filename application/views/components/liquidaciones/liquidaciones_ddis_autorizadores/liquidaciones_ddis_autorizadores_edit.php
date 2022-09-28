<div class="col-xs-12">
    <div class="ibox-content">
	<form class="form_liquidacion_ddis_autorizzadores_edit" id="form_liquidacion_ddis_autorizzadores_edit" name="form_liquidacion_ddis_autorizadores_edit" 
              action="<?php echo base_url('liquidaciones/liquidaciones_ddis_autorizadores/edit/'.$results->id)?>" method="post" >
            <?php   echo form_hidden('enviar_form','1');?>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="usuario_id">Usuario</label>
                                    <input id="usuario" type="text" class="form-control filtro-group" autocomplete="off" value="<?php echo $results->apellido.', '.$results->nombre?>"/>
                                    <input id="usuario_id" name="usuario_id" type="hidden" value="<?php echo $results->usuario_id?>"/>
                                </div>
                            </div>
                            <div class="col-xs-3">
                              <div class="form-group">
                                <label for="nivel_autorizacion">Nivel de Autorización</label>
                                <select id="nivel_autorizacion" name="nivel_autorizacion" class="form-control filtro-group">
                                    <option value="-1"> Seleccionar </option>
                                    <option value="1" <?php echo $results->nivel_autorizacion != '' && $results->nivel_autorizacion == 1?"selected":"" ?>>GERENCIA</option>
                                    <option value="2" <?php echo $results->nivel_autorizacion != '' && $results->nivel_autorizacion == 2?"selected":"" ?>>SUPERVISION</option>
                                </select>
                              </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-3">
                                    <div class="form-group">
                                      <label for="estado">Estado</label>
                                      <select id="estado" name="estado" class="form-control filtro-group" required>
                                          <option value="-1"> Seleccionar </option>
                                          <option value="1" <?php echo $results->estado != '' && $results->estado == 1?"selected":"" ?>>ACTIVO</option>
                                          <option value="0" <?php echo $results->estado != '' && $results->estado == 1?"selected":"" ?>>INACTIVO</option>
                                      </select>
                                    </div>
                            </div> 
                        </div> 
		<div class="control-group">
                    <div class="controls">
                        <?php echo form_button(array('type'  =>'submit','value' =>'Guardar','name'  =>'submit','class' =>'btn btn-success','id' => 'guardar'), "<i class='fa fa-floppy-o'></i> Guardar"); ?> 
                        <a class="btn btn-danger" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2); ?>"><i class="fa fa-arrow-circle-left"></i> Volver</a>
		  </div>
		</div>
            </form>
	</div>
    </div>  