<div class="col-xs-12">
    <!-- boton crear -->
    <div class="ibox-content">
        <div class="text-right">            
            <?php if($permisos_efectivos->insert==1) { ?> <a href="<?php echo base_url().'esquema/esquema/create/' ?>" class="btn btn-success"><i class="fa fa-plus"></i> Nuevo Esquema</a><?php } ?>
        </div>
    </div>
    <!-- end boton crear -->
    
    <!-- filtro -->
    <div class="ibox-content">
        <form action="<?php echo base_url() ?>esquema/esquema/filtro" method="post" id="form-filtro">
            <div class="row">
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="buscar">Campo Libre</label>
                        <input type="text" id="buscar" name="buscar" class="form-control filtro-group" placeholder="Nombre" autocomplete="off">
                    </div>
                </div>
                <div class="col-xs-6 ">
                    <div class="form-group">
                        <button type="submit" class="btn btn-success" style="margin-top: 23px;">Filtrar</button>
                        <a href="<?php echo base_url() ?>esquema/esquema" class="btn btn-primary" style="margin-top: 23px;"><i class="fa fa-refresh"></i> Reinciar</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- end filtro -->

    <div class="ibox-content">        
        <table id="results" class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
            <thead>
                <tr>
                    <th><a href="#">Nombre</a></th>
                    <th><a href="#">Acciones</a></th>
                </tr>
            </thead>
            <tbody>
               
              <?php foreach ($results as $result) { ?>
                  <tr>
                    <td>                      
                      <?php echo $result->nombre ?><br>
                    </td>
                    <td>
                      <div class="btn-group">
                        <!-- <a data-toggle="modal" href="<?php echo base_url().'esquema/esquema/view/'.$result->id ?>" data-target="#myModal" class="btn btn-primary text-white"><i class="fa fa-search"></i></a> -->
                        <?php if($permisos_efectivos->read==1) { ?><a href="<?php echo base_url().'esquema/esquema/view/'.$result->id ?>" class="btn btn-primary text-white"><i class="fa fa-search"></i></a><?php } ?>
                        <?php if($permisos_efectivos->update==1) { ?><a href="<?php echo base_url().'esquema/esquema/edit/'.$result->id ?>" class="btn btn-success"><i class="fa fa-edit"></i></a><?php } ?>
                        <!-- <?php if($permisos_efectivos->delete==1) { ?><a onClick="eleminarRegistro('<?php echo base_url().'esquema/esquema/delete/'.$result->id ?>')" href="#" class="btn btn-danger"><i class="fa fa-trash-o"></i></a><?php } ?> -->
                        <?php if($permisos_efectivos->delete==1) { ?><a onClick="eliminar(event, <?php echo $result->id ?>)" href="#" class="btn btn-danger"><i class="fa fa-trash-o"></i></a><?php } ?>

                      </div>
                    </td>
                  </tr>
              <?php } ?>
              
            </tbody>
        </table>
    </div>

</div>

<script type="text/javascript">
    const permisos = <?php echo json_encode($permisos_efectivos)?>;

    async function eliminar(event, id){
        event.preventDefault();

        const resultado = confirm("¿Estas seguro de eliminar este registro?")
        
        if(resultado){
            const resp = await validarQueElEsquemaNoEsteCargadoEnNinguncliente(id)
            if(resp.data.cantidad > 0){
                alert("No se puede eliminar este esquema porque se encuentra asignado a uno o mas clientes")
            }else{
                console.log("Eliminar ....")
                eliminarRegistro(id)
            }
        }

    }

    async function eliminarRegistro(id){
        const dominio = document.domain;
        const url = `//${dominio}/esquema/esquema/delete/${id}`;

        let form = document.createElement('form');
        form.action = url;
        form.method = 'POST';

        // el formulario debe estar en el document para poder enviarlo
        document.body.append(form);

        form.submit();
    }

    async function validarQueElEsquemaNoEsteCargadoEnNinguncliente(id){
        const dominio = document.domain;
        const url = `//${dominio}/esquema/esquema/comprobarQueNoEsteAsignadoAClientes`;

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({id})
            });

            const data = await response.json();

            if(data){
                return data
            }

            return false

        } catch (error) {
            console.error("validarQueElEsquemaNoEsteCargadoEnNinguncliente: ", error)
        }
    }


</script>