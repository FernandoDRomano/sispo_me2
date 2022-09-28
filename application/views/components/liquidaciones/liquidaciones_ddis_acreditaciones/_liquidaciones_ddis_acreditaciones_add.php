<div class="col-xs-12">
    <div class="ibox-content">
	<form class="form_liquidacion_ddis_acreditaciones_add" id="form_liquidacion_ddis_acreditaciones_add" 
                name="form_liquidacion_ddis_acreditaciones_add" action="<?php echo base_url('liquidaciones/liquidaciones_ddis_acreditaciones/add')?>" method="post">
                <?php   echo form_hidden('enviar_form','1');?>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="concepto">Concepto</label>
                                    <input id="concepto" name="concepto" type="text" class="form-control filtro-group" placeholder="Concepto" autocomplete="off" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="importe">Importe</label>
                                    <input id="importe" name="importe" type="text" class="form-control filtro-group" placeholder="Importe" autocomplete="off"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-3">
                                    <div class="form-group">
                                      <label for="tipo_concepto_id">Tipo de Concepto</label>
                                      <select id="tipo_concepto_id" name="tipo_concepto_id" class="form-control filtro-group" required>
                                          <option value="-1"> Seleccionar </option>
                                          <option value="1">GENERAL</option>
                                          <option value="2">INDIVIDUAL</option>
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