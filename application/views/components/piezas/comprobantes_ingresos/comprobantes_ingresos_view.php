<table class="table table-striped table-hover table-condensed bootstrap-datatable table-bordered">
    <thead>
        <tr>
            <th><a href="#">Servicio</a></th>
            <th><a href="#">Cantidad</a></th>
            <th><a href="#">Remito</a></th>
        </tr>
    </thead>
    <tbody id="body-grilla">
        <?php foreach ($comprobante_servicios as $comprobante_servicio) {?>
            <tr>
                <td><?=$comprobante_servicio->servicio->nombre?></td>
                <td><?=$comprobante_servicio->cantidad?></td>
                <td><?=$comprobante_servicio->remito?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>