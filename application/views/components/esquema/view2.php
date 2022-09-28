<link href="https://prueba.sispo.com.ar/assets/backend/css/estilos.css" rel="stylesheet">

<?php     
    $attributes = array('id' => 'formEnviar');
    echo form_open(base_url(). 'esquema/esquema/update/' . $esquema->id, $attributes);
?>    
    <div class="col-xs-12">
        <div class="ibox-content">    	
            
            <div class="row">

                <div class="col-xs-12">
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" readonly name="nombre" id="nombre" value="<?php echo $esquema->nombre?>" placeholder="Ingrese el nombre" class="form-control">
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-xs-12" id="contenedor_esquema">

                </div>
                <div class="col-xs-12">
                    <div class="ibox-content">
                        <div class="control-group">
                            <div class="controls">
                                <a class="btn btn-danger" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2); ?>"><i class="fa fa-arrow-circle-left"></i> Volver</a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            

    	</div>
    </div>

<script type="text/javascript">

    const subEsquemas = <?php echo json_encode($subEsquemas);?>;
    const kilos = <?php echo json_encode($kilos);?>;
    const bultos = <?php echo json_encode($bultos);?>;
    const metrosCubicos = <?php echo json_encode($metrosCubicos);?>;
    const palets = <?php echo json_encode($palets);?>;
    const warehouse = <?php echo json_encode($warehouse);?>;
    const gestionFlota = <?php echo json_encode($gestionFlota);?>;
    const valorDeclarado = <?php echo json_encode($valorDeclarado);?>;
    const cobranza = <?php echo json_encode($cobranza);?>;
    const pesoAforado = <?php echo json_encode($pesoAforado);?>;
    const distancia = <?php echo json_encode($distancia);?>;
    const zonas = <?php echo json_encode($zonas);?>;
    //LA PLANTILLA SOLA LA TRAIGO PARA MOSTRAR EL NOMBRE, NO HAY REFERENCIA DIRECTA CON EL ESQUEMA
    const plantillas = <?php echo json_encode($plantillas);?>;
    
    let esquema = {}

    function crearEsquema(){
        esquema = {}
        //RECORRO LOS SUB ESQUEMAS
        for (let index = 0; index < subEsquemas.length; index++) {
            const element = subEsquemas[index];
            //ASIGNO EL ID PARA CADA SUB ESQUEMA
            esquema[`sub_esquema_${index}`] = {
                id: subEsquemas[index].id,
                zonaA: subEsquemas[index].zonaA,
                zonaB: subEsquemas[index].zonaB,
                plantilla_id: subEsquemas[index].plantilla_id
            }

            //ASIGNO LOS KILOS
            let auxK = [];

            for (let i = 0; i < kilos.length; i++) {
                const element = kilos[i];
                if(element.idSubEsquema == subEsquemas[index].id){
                    auxK.push(element)
                }
            }

            esquema[`sub_esquema_${index}`]['kilos'] = {
                kilos: auxK
            }

            //ASIGNO LOS BULTOS
            let auxB = [];

            for (let i = 0; i < bultos.length; i++) {
                const element = bultos[i];
                if(element.idSubEsquema == subEsquemas[index].id){
                    auxB.push(element)
                }
            }

            esquema[`sub_esquema_${index}`]['bultos'] = {
                bultos: auxB
            }

            //ASIGNO LOS PALETS
            let auxP = [];

            for (let i = 0; i < palets.length; i++) {
                const element = palets[i];
                if(element.idSubEsquema == subEsquemas[index].id){
                    auxP.push(element)
                }
            }

            esquema[`sub_esquema_${index}`]['palets'] = {
                palets: auxP
            }

            //ASIGNO LOS METROS CUBICOS
            let auxMC = [];

            for (let i = 0; i < metrosCubicos.length; i++) {
                const element = metrosCubicos[i];
                if(element.idSubEsquema == subEsquemas[index].id){
                    auxMC.push(element)
                }
            }

            esquema[`sub_esquema_${index}`]['metrosCubicos'] = {
                metrosCubicos: auxMC
            }

            //ASIGNO WAREHOUSE
            let auxW = [];

            for (let i = 0; i < warehouse.length; i++) {
                const element = warehouse[i];
                if(element.idSubEsquema == subEsquemas[index].id){
                    auxW.push(element)
                }
            }

            esquema[`sub_esquema_${index}`]['warehouse'] = {
                warehouse: auxW
            }

            //ASIGNO GESTION DE FLOTA
            let auxGF = [];

            for (let i = 0; i < gestionFlota.length; i++) {
                const element = gestionFlota[i];
                if(element.idSubEsquema == subEsquemas[index].id){
                    auxGF.push(element)
                }
            }

            esquema[`sub_esquema_${index}`]['gestionFlota'] = {
                gestionFlota: auxGF
            }

            //ASIGNO VALOR DECLARADO
            let auxVD = [];

            for (let i = 0; i < valorDeclarado.length; i++) {
                const element = valorDeclarado[i];
                if(element.idSubEsquema == subEsquemas[index].id){
                    auxVD.push(element)
                }
            }

            esquema[`sub_esquema_${index}`]['valorDeclarado'] = {
                valorDeclarado: auxVD
            }

            //ASIGNO VALOR COBRANZA
            let auxCob = [];

            for (let i = 0; i < cobranza.length; i++) {
                const element = cobranza[i];
                if(element.idSubEsquema == subEsquemas[index].id){
                    auxCob.push(element)
                }
            }

            esquema[`sub_esquema_${index}`]['cobranza'] = {
                cobranza: auxCob
            }

            //ASIGNO VALOR PESO AFORADO
            let auxPA = [];

            for (let i = 0; i < pesoAforado.length; i++) {
                const element = pesoAforado[i];
                if(element.idSubEsquema == subEsquemas[index].id){
                    auxPA.push(element)
                }
            }

            esquema[`sub_esquema_${index}`]['pesoAforado'] = {
                pesoAforado: auxPA
            }

            //ASIGNO LAS DISTANCIAS
            let auxDist = [];

            for (let i = 0; i < distancia.length; i++) {
                const element = distancia[i];
                if(element.idSubEsquema == subEsquemas[index].id){
                    auxDist.push(element)
                }
            }

            esquema[`sub_esquema_${index}`]['distancia'] = {
                distancia: auxDist
            }


        }

    }
    
    function construirVista(){
        let html = ``;
        //RECORRO LOS SUB ESQUEMAS
        const cantidad = Object.keys(esquema).length

        for (let index = 0; index < cantidad; index++) {
            let subEsquema = `sub_esquema_${index}`;

            html += `<h2 class="font-weight-bold text-uppercase" style="margin-bottom: 2rem;">Sub Esquema ${index + 1}</h2>`
            
            //ASIGNO EL NOMBRE DE LAS ZONAS
            for (let index = 0; index < zonas.length; index++) {
                const element = zonas[index];

                if(element.id == esquema[subEsquema].zonaA){
                    html += `<h4><b>Zona de origen: </b> ${element.nombre}</h4>`
                }
            }

            for (let index = 0; index < zonas.length; index++) {
                const element = zonas[index];

                if(element.id == esquema[subEsquema].zonaB){
                    html += `<h4><b>Zona de destino: </b> ${element.nombre}</h4>`
                }
            }

            //ASIGNO EL NOMBRE DE LA PLANTILLA
            for(let index = 0; index < plantillas.length; index++){
                const element = plantillas[index]

                if(element.id == esquema[subEsquema].plantilla_id){
                    html += `<h4 style="margin-bottom: 2rem;"><b>Plantilla: </b> ${element.nombre}</h4>`
                }
            }

            //RECORRO COBRANZA
            if(esquema[subEsquema].cobranza.cobranza.length > 0){

                for (let i = 0; i < esquema[subEsquema].cobranza.cobranza.length; i++) {
                    const element = esquema[subEsquema].cobranza.cobranza[i];
                    html += `
                            <div class="form-group">
                                <h4><b>Tarifa por cobranza</b></h4>
                                Tipo: ${element.tipo} <br>
                                Valor: ${element.valor}
                            </div>
                    `;
                }
                                
            }

            //RECORRO VALOR DECLARADO
            if(esquema[subEsquema].valorDeclarado.valorDeclarado.length > 0){

                for (let i = 0; i < esquema[subEsquema].valorDeclarado.valorDeclarado.length; i++) {
                    const element = esquema[subEsquema].valorDeclarado.valorDeclarado[i];
                    html += `
                            <div class="form-group">
                                <h4><b>Tarifa por valor declarado</b></h4>
                                Tipo: ${element.tipo} <br>
                                Valor: ${element.valor}
                            </div>
                    `;
                }
                                
            }

            //RECORRO VALOR DECLARADO
            if(esquema[subEsquema].pesoAforado.pesoAforado.length > 0){

                for (let i = 0; i < esquema[subEsquema].pesoAforado.pesoAforado.length; i++) {
                    const element = esquema[subEsquema].pesoAforado.pesoAforado[i];
                    html += `
                            <div class="form-group">
                                <h4><b>Tarifas por peso aforado</b></h4>
                                Valor: ${element.valor}
                            </div>
                    `;
                }
                            
            }

            //RECORRO LOS KILOS
            if(esquema[subEsquema].kilos.kilos.length > 0){
                html += `
                <h4><b>Tarifas por kilos</b></h4>
                    <table class="table table-sm table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>Desde</th>
                                <th>Hasta</th>
                                <th>Precio</th>
                                <th>Tipo</th>
                            </tr>
                        </thead>
                        <tbody>
                        `;

                for (let i = 0; i < esquema[subEsquema].kilos.kilos.length; i++) {
                    const element = esquema[subEsquema].kilos.kilos[i];
                    html += `
                            <tr>
                                <td> ${element.desde} </td>
                                <td> ${element.hasta} </td>
                                <td> ${element.precio} </td>
                                <td> ${element.bandera == 1 ? 'Bloque' : 'Unidad' } </td>
                            </tr>
                    `;
                }
                                
                html += `        
                        </tbody>
                    </table>

                    <hr>
                `;
            }

            //RECORRO LOS BULTOS
            if(esquema[subEsquema].bultos.bultos.length > 0){
                html += `
                <h4><b>Tarifas por bultos</b></h4>
                    <table class="table table-sm table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>Desde</th>
                                <th>Hasta</th>
                                <th>Precio</th>
                                <th>Tipo</th>
                            </tr>
                        </thead>
                        <tbody>
                        `;

                for (let i = 0; i < esquema[subEsquema].bultos.bultos.length; i++) {
                    const element = esquema[subEsquema].bultos.bultos[i];
                    html += `
                            <tr>
                                <td> ${element.desde} </td>
                                <td> ${element.hasta} </td>
                                <td> ${element.precio} </td>
                                <td> ${element.bandera == 1 ? 'Bloque' : 'Unidad' } </td>
                            </tr>
                    `;
                }
                                
                html += `        
                        </tbody>
                    </table>

                    <hr>
                `;
            }

            //RECORRO LOS METROS CUBICOS
            if(esquema[subEsquema].metrosCubicos.metrosCubicos.length > 0){
                html += `
                <h4><b>Tarifas por Metro cubico</b></h4>
                    <table class="table table-sm table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>Desde</th>
                                <th>Hasta</th>
                                <th>Precio</th>
                                <th>Tipo</th>
                            </tr>
                        </thead>
                        <tbody>
                        `;

                for (let i = 0; i < esquema[subEsquema].metrosCubicos.metrosCubicos.length; i++) {
                    const element = esquema[subEsquema].metrosCubicos.metrosCubicos[i];
                    html += `
                            <tr>
                                <td> ${element.desde} </td>
                                <td> ${element.hasta} </td>
                                <td> ${element.precio} </td>
                                <td> ${element.bandera == 1 ? 'Bloque' : 'Unidad' } </td>
                            </tr>
                    `;
                }
                                
                html += `        
                        </tbody>
                    </table>

                    <hr>
                `;
            }

            //RECORRO LOS PALETS
            if(esquema[subEsquema].palets.palets.length > 0){
                html += `
                <h4><b>Tarifas por palets</b></h4>
                    <table class="table table-sm table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>Desde</th>
                                <th>Hasta</th>
                                <th>Precio</th>
                                <th>Tipo</th>
                            </tr>
                        </thead>
                        <tbody>
                        `;

                for (let i = 0; i < esquema[subEsquema].palets.palets.length; i++) {
                    const element = esquema[subEsquema].palets.palets[i];
                    html += `
                            <tr>
                                <td> ${element.desde} </td>
                                <td> ${element.hasta} </td>
                                <td> ${element.precio} </td>
                                <td> ${element.bandera == 1 ? 'Bloque' : 'Unidad' } </td>
                            </tr>
                    `;
                }
                                
                html += `        
                        </tbody>
                    </table>

                    <hr>
                `;
            }

            //RECORRO LA GESTION DE FLOTA
            if(esquema[subEsquema].gestionFlota.gestionFlota.length > 0){
                html += `
                <h4><b>Tarifas por gesti&#243;n de flota</b></h4>
                    <table class="table table-sm table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>Tipo</th>
                                <th>Precio</th>
                            </tr>
                        </thead>
                        <tbody>
                        `;

                for (let i = 0; i < esquema[subEsquema].gestionFlota.gestionFlota.length; i++) {
                    const element = esquema[subEsquema].gestionFlota.gestionFlota[i];
                    html += `
                            <tr>
                                <td> 
                                    ${ element.tipo_vehiculo == 1 && element.tipo_hora == 1 ? 'Moto // Hora Comun' : '' }
                                    ${ element.tipo_vehiculo == 1 && element.tipo_hora == 2 ? 'Moto // Hora Especial' : '' }
                                    ${ element.tipo_vehiculo == 2 && element.tipo_hora == 1 ? 'Auto // Hora Comun' : '' }
                                    ${ element.tipo_vehiculo == 2 && element.tipo_hora == 2 ? 'Auto // Hora Especial' : '' }
                                    ${ element.tipo_vehiculo == 3 && element.tipo_hora == 1 ? 'Camioneta // Hora Comun' : '' }
                                    ${ element.tipo_vehiculo == 3 && element.tipo_hora == 2 ? 'Camioneta // Hora Especial' : '' }
                                    ${ element.tipo_vehiculo == 4 && element.tipo_hora == 1 ? 'Furgon // Hora Comun' : '' }
                                    ${ element.tipo_vehiculo == 4 && element.tipo_hora == 2 ? 'Furgon // Hora Especial' : '' }
                                    ${ element.tipo_vehiculo == 5 && element.tipo_hora == 1 ? 'Cami&#243;n // Hora Comun' : '' }
                                    ${ element.tipo_vehiculo == 5 && element.tipo_hora == 2 ? 'Cami&#243;n // Hora Especial' : '' }
                                </td>
                                <td> ${element.precio} </td>
                            </tr>
                    `;
                }
                                
                html += `        
                        </tbody>
                    </table>

                    <hr>
                `;
            }

            //RECORRO LOS WAREHOUSES
            if(esquema[subEsquema].warehouse.warehouse.length > 0){
                html += `
                <h4><b>Tarifas por warehouse</b></h4>
                    <table class="table table-sm table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>Desde</th>
                                <th>Hasta</th>
                                <th>Precio</th>
                            </tr>
                        </thead>
                        <tbody>
                        `;

                for (let i = 0; i < esquema[subEsquema].warehouse.warehouse.length; i++) {
                    const element = esquema[subEsquema].warehouse.warehouse[i];
                    html += `
                            <tr>
                                <td> ${element.desde} </td>
                                <td> ${element.hasta} </td>
                                <td> ${element.precio} </td>
                            </tr>
                    `;
                }
                                
                html += `        
                        </tbody>
                    </table>

                    <hr>
                `;
            }

            //RECORRO LAS DISTANCIAS
            if(esquema[subEsquema].distancia.distancia.length > 0){
                html += `
                <h4><b>Tarifas por distancia</b></h4>
                    <table class="table table-sm table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>Desde</th>
                                <th>Hasta</th>
                                <th>Precio</th>
                            </tr>
                        </thead>
                        <tbody>
                        `;

                for (let i = 0; i < esquema[subEsquema].distancia.distancia.length; i++) {
                    const element = esquema[subEsquema].distancia.distancia[i];
                    html += `
                            <tr>
                                <td> ${element.desde} </td>
                                <td> ${element.hasta} </td>
                                <td> ${element.precio} </td>
                            </tr>
                    `;
                }
                                
                html += `        
                        </tbody>
                    </table>

                    <hr>
                `;
            }

        }

        const contenedor = document.getElementById("contenedor_esquema")
        contenedor.insertAdjacentHTML("beforeend", html)

    }

    crearEsquema();
    construirVista();



</script>