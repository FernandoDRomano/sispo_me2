// VOLVER ARRIBA
$(document).ready(function(){
     $(window).scroll(function () {
            if ($(this).scrollTop() > 50) {
                $('#back-to-top').fadeIn();
            } else {
                $('#back-to-top').fadeOut();
            }
        });
        $('#back-to-top').click(function () {
            /*$('#back-to-top').tooltip('hide');*/
            $('body,html').animate({
                scrollTop: 0
            }, 500);
            return false;
        });
});

function eleminarRegistro(link){
  bootbox.confirm("Deseas eliminar el registro?", function(result) {
    if (result === true) {
        $.ajax({
            type: "GET",
            url: link,
            data: {},
            cache: false,
            success: function(){window.location.reload();}
        });
    }
  });
}

function cerrarModal(){
  $('#myModal').removeData("modal");
}

$(document).ready(function() {
  $('#myModal').on('hidden.bs.modal', function () {
    $(this).removeData('bs.modal');
  });
});

function deleteImagen(id, url, tabla){
  bootbox.confirm("Deseas eliminar la imagen?", function(result) {
    if (result === true) {
        $.ajax({
              type: "POST",
              url: url,
              data: {id: id, tabla: tabla},
              cache: false,
              success: function(){window.location.reload();}
        });
    }
  });
}

// datatables
var maintable;
$(document).ready(function() {
    maintable = $('#results').DataTable({
        "columnDefs": [{
          "targets"  : 'no-sort',
          "orderable": false,
        }]
    });
    $('#results').on('draw.dt', function(){
        barcodear();
    });
});

// summernote
$(document).ready(function() {
  $('.summernote').summernote({
    height: 300
  });
});

// chosen
$(document).ready(function() {
  var config = {
    '.chosen-select'           : {},
    '.chosen-select-deselect'  : {allow_single_deselect:true},
    '.chosen-select-no-single' : {disable_search_threshold:10},
    '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
    '.chosen-select-width'     : {width:"95%"}
  }
  for (var selector in config) {
    $(selector).chosen(config[selector]);
  }
});

// checkbox
$(document).ready(function () {
    $('.i-checks').iCheck({
        checkboxClass: 'icheckbox_square-green',
        radioClass: 'iradio_square-green',
    });
});

// footer
$(document).ready(function () {
    //$("body").removeClass('boxed-layout');
    //$(".footer").addClass('fixed');
});

// tooltip
$(document).ready(function () {
    $("[rel='tooltip']").tooltip({
        container: 'body',
        html:true
    });
});

// template
$(document).ready(function () {
    /*$("body").removeClass("skin-0");
    $("body").removeClass("skin-1");
    $("body").removeClass("skin-2");
    $("body").removeClass("skin-3");
    $("body").addClass("skin-1");*/
    //$("body").addClass('mini-navbar');
});

$(document).ready(function() {
    $('.fancybox').fancybox({
        padding: 40,
        autoSize: true
    });

    $(".fancybox-youtube").click(function () {
        $.fancybox({
            hideOnContentClick: false,
            autoScale: false,
            transitionIn: 'none',
            transitionOut: 'none',
            href: this.href.replace(new RegExp("watch\\?v=", "i"), 'v/'),
            type: 'swf',
            padding:0,
            closeBtn:false,
            closeClick:true,
            swf: {
                wmode: 'transparent',
                allowfullscreen: 'true'
            }
        });
        return false;
    });

    $(".fancybox-vimeo").click(function (i) {
        var id = $(this).attr("title");
        var htmlcontent = "//player.vimeo.com/video/" + id;
        $.fancybox({
            hideOnContentClick: false,
            autoScale: false,
            transitionIn: 'none',
            transitionOut: 'none',
            href: htmlcontent,
            type: 'iframe',
            padding:0,
            closeBtn:false,
            closeClick:true
        });
        return false;
    });
});

// tags
$(document).ready(function () {
    $(".tags").tagsinput();
});


// datepicker
$(document).ready(function () {
    $(".datepicker").datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
        language: "es",
        orientation: "top auto",
        keyboardNavigation: false,
        todayHighlight: true
    });
});


$(document).ready(function() {
    //$("body").addClass('boxed-layout');
    $('#fixednavbar').prop('checked', false);
    $(".navbar-fixed-top").removeClass('navbar-fixed-top').addClass('navbar-static-top');
    $("body").removeClass('fixed-nav');
    $(".footer").removeClass('fixed');
    $('#fixedfooter').prop('checked', false);
});

var barcodear = function(){
    var selector = arguments[0] ? arguments[0] : ".barcodear";
    $(selector).each(function(){
        if($(this).find('a').length)
            return;

        var barcode = $(this).html();
        var href = $(this).data('href') ? 'href="'+$(this).data('href')+'"' : '';
        $(this).html('<a '+href+' rel="tooltip" data-animation="false" data-original-title="<h1><span class=\'barcode\'>'+barcode+'</span><br>'+barcode+'</h1>">'+barcode+'</a>');
        $("[rel='tooltip']").tooltip({
            container: 'body',
            html:true
        });
    });
};

$(function(){
    barcodear();
});


jQuery.extend(jQuery.validator.messages, {
    required: "Este campo es obligatorio.",
    remote: "Corrija este campo.",
    email: "Escriba una dirección de email valida.",
    url: "Escriba una url valida.",
    date: "Escriba una fecha valida.",
    dateISO: "Escriba una fecha valida (ISO).",
    number: "Escriba un numero valido.",
    digits: "Escriba solo digitos.",
    creditcard: "Please enter a valid credit card number.",
    equalTo: "Escriba el mismo valor otra vez.",
    accept: "Escriba un valor con una extención valida.",
    maxlength: jQuery.validator.format("Escriba mas de {0} caracteres."),
    minlength: jQuery.validator.format("Escriba menos de {0} caracteres."),
    rangelength: jQuery.validator.format("Escriba mas de {0} y menos de {1} caracteres."),
    range: jQuery.validator.format("Escribe un valor entre {0} y {1}."),
    max: jQuery.validator.format("Escriba un numero menor o igual a {0}."),
    min: jQuery.validator.format("Escriba un numero mayor o igual a {0}.")
});
;