<div class="col-xs-12">
    <div class="ibox-content">
      <?php     
        echo form_open(current_url(), array('class'=>""));
        echo form_hidden('enviar_form','1');
        echo form_button(array('type'  =>'submit','value' =>'Guardar Cambios','name'  =>'submit','class' =>'btn btn-success pull-right'), "<i class='fa fa-floppy-o'></i> Guardar");
      ?>
      <ul class="nav nav-tabs" role="tablist" id="myTab">
        <li class="active"><a href="#generales" role="tab" data-toggle="tab">Generales</a></li>
        <li><a href="#contacto" role="tab" data-toggle="tab">Contacto / Emails</a></li>
        <li><a href="#social_media" role="tab" data-toggle="tab">Social Media</a></li>
      </ul>
        <div class="tab-content miTab">           
          <div class="tab-pane active" id="generales">
            <div class="" style="padding: 30px;">
                <div class="form-group">
                  <label for="nombre_sitio">Nombre Sitio<span class="required">*</span></label>
                  <input id="nombre_sitio" required type="text" name="nombre_sitio" value="<?php echo $nombre_sitio->valor ?>" class="form-control" placeholder="nombre" />
                </div>
                <div class="form-group">
                  <label for="descripcion_sitio">Descripcion Sitio</label>
                  <textarea id="descripcion_sitio" name="descripcion_sitio" class="form-control"><?php echo $descripcion_sitio->valor ?></textarea>
                </div>
                <div class="form-group">
                  <label for="metadata">Metadata</label>
                  <textarea id="metadata" name="metadata" class="form-control"><?php echo $metadata->valor ?></textarea>
                </div>
              </div>
          </div>

          <div class="tab-pane" id="contacto">
            <div class="" style="padding: 30px;">            
              <div class="row">
                <div class="col-xs-12">
                  <hr><h3>Datos Emails</h3><hr>
                </div>                
                <div class="col-xs-6">
                  <div class="form-group">                
                    <label for="email">Email de contacto</label>
                    <div class="input-group">                  
                      <div class="input-group-addon"><i class="fa fa-envelope-o"></i></div>
                      <input id="email" type="text" name="email" value="<?php echo $email->valor ?>" class="form-control" placeholder="email" />
                    </div>                
                  </div>
                </div>
                <div class="col-xs-6">
                  <div class="form-group">
                    <label for="email_registro_usuario">Titulo email de registro de nuevo usuario</label>
                    <div class="input-group">                  
                      <div class="input-group-addon"><i class="fa fa-text-height"></i></div>
                      <input id="email_registro_usuario" type="text" name="email_registro_usuario" value="<?php echo $email_registro_usuario->valor ?>" class="form-control" placeholder="email_registro_usuario" />
                    </div>                
                  </div>
                </div>
                <div class="col-xs-6">
                  <div class="form-group">
                    <label for="remitente">Remitente</label>
                    <div class="input-group">                  
                      <div class="input-group-addon"><i class="fa fa-user"></i></div>
                      <input id="remitente" type="text" name="remitente" value="<?php echo $remitente->valor ?>" class="form-control" placeholder="remitente" />
                    </div>                    
                  </div>
                </div>                
              </div>                  
              <div class="row">
                <div class="col-xs-12">
                  <hr><h3>Datos contacto</h3><hr>
                </div>
                <div class="col-xs-4">
                  <div class="form-group">
                    <label for="direccion">Direccion</label>
                    <div class="input-group">                  
                      <div class="input-group-addon"><i class="fa fa-home"></i></div>
                      <input id="direccion" type="text" name="direccion" value="<?php echo $direccion->valor ?>" class="form-control" placeholder="direccion" />
                    </div>                    
                  </div>
                </div>
                <div class="col-xs-4">
                  <div class="form-group">
                    <label for="telefono">Telefono</label>
                    <div class="input-group">                  
                      <div class="input-group-addon"><i class="fa fa-phone"></i></div>
                      <input id="telefono" type="text" name="telefono" value="<?php echo $telefono->valor ?>" class="form-control" placeholder="telefono" />
                    </div>                    
                  </div>
                </div>
                <div class="col-xs-4">
                  <div class="form-group">
                    <label for="celular">Celular</label>
                    <div class="input-group">                  
                      <div class="input-group-addon"><i class="fa fa-mobile"></i></div>
                      <input id="celular" type="text" name="celular" value="<?php echo $celular->valor ?>" class="form-control" placeholder="celular" />
                    </div>                    
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-xs-12">
                  <hr><h3>Datos geolocalizacion</h3><hr>
                </div>
                <div class="col-xs-6">
                  <div class="form-group">
                  <label for="latitud">Latitud</label>
                  <div class="input-group">                  
                      <div class="input-group-addon"><i class="fa fa-map-marker"></i></div>
                      <input id="latitud" type="text" name="latitud" value="<?php echo $latitud->valor ?>" class="form-control" placeholder="latitud" />
                    </div>                  
                  </div>
                </div>
                <div class="col-xs-6">
                  <div class="form-group">
                  <label for="longitud">Longitud</label>
                  <div class="input-group">                  
                    <div class="input-group-addon"><i class="fa fa-map-marker"></i></div>
                      <input id="longitud" type="text" name="longitud" value="<?php echo $longitud->valor ?>" class="form-control" placeholder="longitud" />
                    </div>                  
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="tab-pane" id="social_media">
            <div class="" style="padding: 30px;">
              <div class="form-group">
                <label for="facebook">Facebook</label>
                <div class="input-group">                  
                  <div class="input-group-addon"><i class="fa fa-facebook"></i></div>
                  <input id="facebook" type="text" name="facebook" value="<?php echo $facebook->valor ?>" class="form-control" placeholder="facebook" />
                </div>                
              </div>
              <div class="form-group">
                <label for="twitter">Twitter</label>
                <div class="input-group">                  
                  <div class="input-group-addon"><i class="fa fa-twitter"></i></div>
                  <input id="twitter" type="text" name="twitter" value="<?php echo $twitter->valor ?>" class="form-control" placeholder="twitter" />
                </div>                
              </div>
              <div class="form-group">
                <label for="google_plus">Google+</label>
                <div class="input-group">                  
                  <div class="input-group-addon"><i class="fa fa-google-plus"></i></div>
                  <input id="google_plus" type="text" name="google_plus" value="<?php echo $google_plus->valor ?>" class="form-control" placeholder="google_plus" />
                </div>                
              </div>
            </div>
          </div>

        </div>
      <?php echo form_close(); ?>
    </div>
</div>