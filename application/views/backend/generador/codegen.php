<div class="col-xs-12">
    <div class="ibox-content">        
        <form action="<?php echo current_url();?>" method="post" class="well" >
            <div class="text-left">
                <div class="row">
                    <div class="col-xs-4">
                        <div class="form-group">
                            <?php $db_tables = $this->db->list_tables(); echo form_dropdown('table',$db_tables,'','class="form-control chosen-select"'); ?>
                        </div>
                    </div>
                    <div class="col-xs-3">
                        <div class="form-group">
                            <input type="submit" name="table_data" value="Explorar" class="btn btn-primary"/>
                        </div>  
                    </div>
                </div>
            </div>
        </form>
        <form action="<?php echo current_url();?>" method="post" class="form-inline" >
            <?php if(isset($alias)){ ?>
                <input type="hidden" name="table" value="<?php echo $table ?>" />
                <div class="form-group">
                    <label for="controller">Controlador</label>
                    <input type="text" name="controller" value="<?php echo $table ?>" class="form-control" required />
                </div>
                <div class="form-group">
                    <label for="view">Vista</label>
                    <input type="text" name="view" value="<?php echo $table ?>" class="form-control" required />
                </div>
                <div class="form-group">
                    <label for="validation">Validacion</label>
                    <input type="text" name="validation" value="<?php echo $table ?>" class="form-control" required />
                </div>
                <div class="form-group">
                    <label for="carpeta">Folder</label>
                    <input type="text" name="carpeta" value="components" class="form-control" required />
                </div>
                <div class="form-group">
                    <label for="generate"> </label>
                    <input type="submit" name="generate" value="Generar" class="btn btn-primary" />
                </div>
                <hr>
                <h3>Informacion de la Tabla</h3>
                <table id="" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
                    <thead>
                        <tr>
                            <th>Campo</th>
                            <th>Tipo de Dato</th>
                            <th>Label</th>
                            <th>Tipo</th>
                            <th>Reglas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            //p($alias);
                
                            $type = array(
                                'exclude'  =>'No incluir',
                                'text' => 'Input',
                                'password' => 'Password',
                                'textarea' => 'Textarea' , 
                                'dropdown' => 'Dropdown',
                                'email' => 'Email',
                                'number' => 'Number',
                                'url' => 'Url',
                                'radio' => 'Radio',
                                'checkbox' => 'Check',
                                'file' => 'File'
                            );
                
                            $sel = '';
                            if(isset($alias)){
                                foreach($alias as $a){
                                    $email_default = FALSE;
                        ?>
                            <tr>
                                <td><?php echo $a->Field; ?></td>
                                <td><?php echo $a->Type ?></td>
                                <td><input id="<?php echo "field[".$a->Field."]"; ?>" required type="text" name="<?php echo "field[".$a->Field."]"; ?>" value="<?php echo ucfirst($a->Field); ?>" class="form-control" /></td>
                                <td>
                                    <?php          
                                        if(strpos($a->Type,'enum') !== false){
                                            echo ' <br>Enum Values (CSV): <input size="50" type="text" value="'.htmlspecialchars ("'0'=>'Value','1'=>'Another Value'").'" name="'.$a->Field.'default">';
                                            $sel = 'dropdown';
                                        }elseif(strpos($a->Type,'blob') !== false || strpos($a->Type,'text') !== false){
                                            $sel = 'textarea';
                                        }else if($a->Key == 'PRI'){
                                            $sel = 'exclude';
                                            echo form_hidden('primaryKey',$a->Field);
                                        }else if(strpos($a->Field,'password') !== false){
                                            $sel = 'password';
                                        }else if(strpos($a->Field,'email') !== false){
                                            $email_default = TRUE;
                                        }else{
                                             $sel = 'text';
                                        } 
                                    ?>
                                    <select name="<?php echo "type[".$a->Field."][]"; ?>" class="form-control">
                                        <option value="exclude">No incluir</option>
                                        <option value="text" <?php if($sel=='text') echo 'selected' ?>>Input</option>
                                        <option value="password" <?php if($sel=='password') echo 'selected' ?>>Password</option>
                                        <option value="textarea" <?php if($sel=='textarea') echo 'selected' ?>>Textarea</option>
                                        <option value="dropdown" <?php if($sel=='dropdown') echo 'selected' ?>>Dropdown</option>
                                        <option value="email" <?php if($sel=='email') echo 'selected' ?>>Email</option>
                                        <option value="number" <?php if($sel=='number') echo 'selected' ?>>Number</option>
                                        <option value="url" <?php if($sel=='url') echo 'selected' ?>>Url</option>
                                        <option value="radio" <?php if($sel=='radio') echo 'selected' ?>>Radio</option>
                                        <option value="checkbox" <?php if($sel=='checkbox') echo 'selected' ?>>Check</option>
                                        <option value="file" <?php if($sel=='file') echo 'selected' ?>>File</option>
                                    </select>
                                </td>
                                <td>
                                    <select name="<?php echo "rules[".$a->Field."][]"; ?>" class="form-control">
                                        <option value="no requerido">No requerido</option>
                                        <option value="required">Requerido</option>
                                    </select>
                                </td>
                            </tr>
                      <?php } } ?>
                    </tbody>
                </table>
            <?php } ?>
        </form>
    </div>
</div>