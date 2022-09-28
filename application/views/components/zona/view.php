 
    <div class="col-xs-12">
        <div class="ibox-content">    	
            
            <div class="row">

                <input type="hidden" name="zona_id" id="zona_id" value="<?php echo $zona->id; ?>">

                <div class="col-xs-12">
                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input readonly value="<?php echo $zona->nombre ?>" type="text" name="nombre" id="nombre" class="form-control" placeholder="Nombre" required>
                    </div>
                </div>

                <div class="col-xs-12">
                    <div class="form-group">
                        <label for="descripcion">Descripción:</label>
                        <textarea readonly name="descripcion" id="descripcion" cols="10" rows="5" class="form-control" placeholder="Descripción" required><?php echo $zona->descripcion ?></textarea>
                    </div>
                </div>

                <div class="col-xs-12">
                    <div class="form-group">
                        <label for="pais">PaÍs</label>
                        <select name="pais" disabled id="pais" class="form-control">
                            <option value="0">Seleccione</option>
                            <?php foreach($paises as $p) { ?>
                                <option value="<?php echo $p->id ?>" <?php echo $pais->id == $p->id ? 'selected' : '' ?> ><?php echo $p->nombre?></option>
                            <?php }?>
                        </select>
                    </div>
                </div>

                <div class="col-xs-12">
                    <div class="form-group">
                        <label for="provincia">Provincia</label>
                        <select name="provincia[]" disabled multiple id="provincia" class="form-control" style="width: 100%">
                            <?php foreach($provincias as $p) { ?>
                                <option value="<?php echo $p->id ?>"
                                <?php foreach($provinciasSeleccionadas as $s) { ?>
                                     <?php echo $p->id == $s->id ? "selected" : '' ?> 
                                <?php }?>
                                ><?php echo $p->nombre?></option>
                            <?php }?>
                        </select>
                    </div>
                </div>

            </div>

    	</div>
    </div>

    <div class="col-xs-12">

        <div class="ibox-content">        
            <h3>Localidades Cargadas</h3>
            <table id="results" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
                <thead>
                    <tr>
                        <th><a href="#">Provincia</a></th>
                        <th><a href="#">Localidad</a></th>
                    </tr>
                </thead>
                <tbody>
                
                <?php foreach ($detalles as $det) { ?>
                    <tr>
                        <td>                      
                            <?php echo $det->provincia ?><br>
                        </td>
                        <td>
                            <?php echo $det->localidad ?><br>
                        </td>
                    </tr>
                <?php } ?>
                
                </tbody>
            </table>
        </div>

    </div>


    <!-- Botones -->
    <div class="col-xs-12">
        <div class="ibox-content">
            <div class="control-group">
              <div class="controls">
                <a class="btn btn-primary" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2); ?>"><i class="fa fa-arrow-circle-left"></i> Volver</a>
              </div>
            </div>
        </div>
    </div>
    <!-- end Botones -->

<script type="text/javascript">
$(document).ready(function() {
    //INICIALIZANDO EL SELECT DE PAISES
    $('#pais').select2();

    //INICIALIZANDO EL SELECT DE PROVINCIAS
    $('#provincia').select2();

});
</script>