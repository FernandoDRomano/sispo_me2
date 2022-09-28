<div class="col-xs-12">
    <div class="ibox-content">
        <?php
         echo form_open(base_url().'backend/logs/zip', array('class'=>"well"));
            echo form_hidden('enviar_form','1');
        ?>
        <h3>Filtro de descarga</h3>
        <div class="row">
            <div class="col-xs-4">
                <div class="form-group">
                    <label for="fecha_desde">Fecha desde<span class="required">*</span></label>
                    <input id="fecha_desde" required type="text" name="fecha_desde" value="<?php echo date('d-m-Y') ?>" class="form-control datepicker" placeholder="<?php echo date('Y-m-d') ?>" />
                </div>
            </div>
            <div class="col-xs-4">
                <div class="form-group">
                    <label for="fecha_hasta">Fecha hasta<span class="required">*</span></label>
                    <input id="fecha_hasta" required type="text" name="fecha_hasta" value="<?php echo date('d-m-Y') ?>" class="form-control datepicker" placeholder="<?php echo date('Y-m-d') ?>" />
                </div>
            </div>
            <div class="col-xs-4">
                <div class="control-group">
                  <div class="controls">
                    <?php echo form_button(array('type'  =>'submit','value' =>'Guardar','name'  =>'submit','class' =>'btn btn-success'), "<i class='fa fa-floppy-o'></i> Descargar"); ?>
                    <a class="btn btn-danger" href="<?php echo base_url().'backend/dashboard'; ?>"><i class="fa fa-arrow-circle-left"></i> Cancelar</a>
                  </div>
                </div>
            </div>
        </div>
        <?php echo form_close(); ?>

        <table id="results" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
            <thead>
                <tr>
                    <th><a href="#">Fecha del log</a></th>
                    <th class="col-xs-2">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($logs as $f) { ?>
                    <?php if ($f != 'index.html') {?>
                        <tr>
                            <?php
                                $array_breadcrumb = array('log-' => '', '.php' => '');
                                $value = str_replace(array_keys($array_breadcrumb), array_values($array_breadcrumb), $f);
                            ?>
                            <td><?php echo $value ?></td>
                            <td>
                                <div class="btn-group">
                                    <a href="<?php echo base_url().'backend/logs/txt/'.$value ?>" class="btn btn-success btn-sm"><i class="fa fa-floppy-o"></i> Descargar</a>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } ?>
            </tbody>
        </table>

    </div>
</div>