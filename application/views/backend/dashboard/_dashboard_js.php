<SCRIPT TYPE="text/javascript">
function dashboardCargarAccesosDirectos(){
    var url = '<?php echo base_url()."backend/dashboard/accesos_directos"?>';
    $.ajax({
        type: "POST",
        url: url,
        data: {},
        cache: false,
        beforeSend: function(){
            $('#dashboard_accesos_directos').html('<div style="padding-top:110px;" class="text-center"><h2><i class="fa fa-refresh fa-spin"></i></h2></div>');
        },
        success: function(respuesta){
            $('#dashboard_accesos_directos').html(respuesta);
        }
    });
}

function dashboardCargarUltimosAccesos(){
    var url = '<?php echo base_url()."backend/dashboard/ultimos_accesos"?>';
    $.ajax({
        type: "POST",
        url: url,
        data: {},
        cache: false,
        beforeSend: function(){
            $('#dashboard_ultimos_accesos').html('<div style="padding-top:110px;" class="text-center"><h2><i class="fa fa-refresh fa-spin"></i></h2></div>');
        },
        success: function(respuesta){
            $('#dashboard_ultimos_accesos').html(respuesta);
        }
    });
}

$(document).ready(function(){
    dashboardCargarAccesosDirectos();
    dashboardCargarUltimosAccesos();
});
</SCRIPT>