$(window).on("scroll", function () {

    if ($(window).scrollTop() > $(".navbar-default").position().top)
    {
        $(".navbar-default").addClass("navbar-fixed-top");
        //$(".navbar-brand img").attr("src","img/logos/LogoHorizontal.png");
        //$(".navbar-brand img").css("width", "85px");
    } else
    {
        $(".navbar-default").removeClass("navbar-fixed-top");
        // $(".navbar-brand img").attr("src","img/logos/logo.png");
        //$(".navbar-brand img").removeAttr('style');
    }
});

$('.navbar-toggle').click(function () {

    $('.collapse').toggle('fast');

});

$('.imagenesCirculares').hover(function () {

    $(this).find('h3').addClass('noDisplay');

}, function () {

    $(this).find('h3').removeClass('noDisplay');

});

$(function () {
    $('a[href*="#"]:not([href="#"])').click(function () {
        if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
            var target = $(this.hash);
            var desfasaje = 0;
            if($('#nav-header').height() == 0)
            {
                desfasaje = 50;
            }
            else
            {
                desfasaje = 200;
            }
            
            target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
            if (target.length) {
                $('html, body').animate({
                    scrollTop: target.offset().top - desfasaje
                }, 1000);
                return false;
            }
        }
    });
});

$('#trekking').click(function () {
    $('.desplegable-ciclo').hide();
    $('.desplegable-ciclo-individual').hide();
    $('.desplegable-cabalgatas').hide();
    $('.desplegable-cabalgatas-individual').hide();
    $('.desplegable-trekking').show('slow');

    $('html, body').animate({
        scrollTop: $('.desplegable-trekking').offset().top - 100
    }, 1000);
});

$('#ciclo').click(function () {
    $('.desplegable-trekking').hide();
    $('.desplegable-trekking-individual').hide();
    $('.desplegable-cabalgatas').hide();
    $('.desplegable-cabalgatas-individual').hide();
    $('.desplegable-ciclo').show('slow');

    $('html, body').animate({
        scrollTop: $('.desplegable-ciclo').offset().top - 100
    }, 1000);
});

$('#cabalgatas').click(function () {
    $('.desplegable-trekking').hide();
    $('.desplegable-trekking-individual').hide();
    $('.desplegable-ciclo').hide();
    $('.desplegable-ciclo-individual').hide();
    $('.desplegable-cabalgatas').show('slow');

    $('html, body').animate({
        scrollTop: $('.desplegable-cabalgatas').offset().top - 100
    }, 1000);
});

$('.desplegadas').click(function ()
{
    $(".desplegable-individual ol").html("");
    var titulo = $(this).text();
    var id_categoria = $(this).attr("id");
    
    $.ajax({
        url: "includes/controller_ajax.php",
        type: "POST",
        data: {option: 'get_categorias_hijas', id_categoria: id_categoria},
        dataType: "json",
        success: function (data) {
            if (data.result === 'ok') 
            {
                console.log(data);
                $(".desplegable-individual b").html(titulo);
//                $(".tour-desc").html(descripcion);
                $('.desplegable-individual').show('fast');
                $('html, body').animate({
                    scrollTop: $('.desplegable-individual').offset().top - 200
                }, 1000);

                $.each(data.categorias, function(i, val)
                {
                    $(".desplegable-individual ol").append("<li><a href='tour.php?id=" + val.id + "'>" + val.nombre + "</a></li>");
                });
            }
            else
            {
                console.log(data.mensaje);
            }
        }
    });
});


$(document).on('click', "li.subcat-trekking", function ()
{
    $('.btn-submit').button('reset');
    $('#mensaje_contacto').html('');
    $('#form-consulta')[0].reset();
    $(".img-cat-trekking").html('');
    $(".info-cat-trekking").html('');

    id_categoria = this.id;

    $.ajax({
        url: "includes/controller_ajax.php",
        type: "POST",
        data: {option: 'get_info_categoria', id_categoria: id_categoria},
        dataType: "json",
        success: function (data) {
            if (data.result) {
                $("#categoria").val(id_categoria);

                $(".img-cat-trekking").html("<img src='" + data.categoria.foto + "' class='img-responsive' width='400px' />");
                $(".info-cat-trekking").html(data.categoria.desc);
                $('html, body').animate({
                    scrollTop: $('.info-cat-trekking').offset().top - 100
                }, 1000);
            }
        }
    });
});

$('.btn-submit').click(function () {
    $(this).button('Enviando..');
    $.ajax({
        type: "POST",
        url: "includes/controller_ajax.php",
        data: {
            "option": "enviar_consulta",
            "nombre": $('#nombre').val(),
            "email": $('#email').val(),
            "phone": $('#phone').val(),
            "consulta": $('#consulta').val(),
            "categoria": $('#categoria').val()
        },
        dataType: 'json',
        success: function (data)
        {
            $('.btn-submit').button('reset');
            if (data.result) {
                $('#mensaje_contacto').html("<div class='mail-success-cat'>" + data.mensaje + "</div>");
                $('#form-consulta')[0].reset();
            } else {
                $('#mensaje_contacto').html("<div class='mail-error-cat'>" + data.mensaje + "</div>");
            }
        },
        error: function (data)
        {
            $('.btn-submit').button('reset');
            $('#mensaje_contacto').html("<div class='mail-error-cat'>" + data.mensaje + "</div>");
        }
    });
});

$('.btn-submit-consulta').click(function () {
    $(this).button('loading');
    $.ajax({
        type: "POST",
        url: "includes/controller_ajax.php",
        data: {
            "option": "enviar_consulta_index",
            "nombre": $('#name').val(),
            "email": $('#email_cons').val(),
            "phone": $('#phone_cons').val(),
            "consulta": $('#message').val(),
            "id_cat": $('#id_cat').val()
        },
        dataType: 'json',
        success: function (data)
        {
            $('.btn-submit-consulta').button('reset');
            if (data.result) {
                $('#success').html("<div class='mail-success'>" + data.mensaje + "</div>");
                $('#contactForm')[0].reset();
            } else {
                $('#success').html("<div class='mail-error'>" + data.mensaje + "</div>");
            }
        },
        error: function (data)
        {
            $('.btn-submit-consulta').button('reset');
            $('#success').html("<div class='mail-error'>" + data.mensaje + "</div>");
        }
    });
});

//calendario
$(".nav-meses ul li a").click(function (e) {
    e.preventDefault();
    $(".nav-meses ul li a").each(function () {
        $(this).removeClass("active");
        $("."+$(this).attr('id')).hide();
    });
    $(this).addClass("active");
    
    if($("."+$(this).attr('id')).length > 0)
    {
        $(".no-event").hide();
        $("."+$(this).attr('id')).show();
    }
    else
    {
        $(".no-event").show();
    }
    
});

//$('.nav-meses ul li a').click(function ()
//{
//    $(".fechas").html("");
//    $.getJSON("calendario/" + this.id + ".json", function (data) {
//        $.each(data, function (i, val) {
//            $(".fechas").append("<div class='row vertical-align'><div class='col-md-1 text-left'><i class='fa fa-calendar'></i></div><div class='col-md-3 text-left texto'>" + i + "</div><div class='col-md-8 text-left'>" + val + "</div></div><hr/>");
//        });
//    }).fail(function (jqxhr, textStatus, error) {
//        var err = textStatus + ", " + error;
//        console.log("Request Failed: " + err);
//    });
//});

$(document).ready(function () {
    var d = new Date();
    var month = d.getMonth() + 1;
    $('#mes' + month).addClass('active');
    if($('.mes' + month).length > 0)
    {
        $(".no-event").hide();
        $('.mes' + month).show();
    }
    else
    {
        $(".no-event").show();
    }
    $("#carousel-example-generic").carousel({interval: 2000});
});



$(function () {
    /*
     - how to call the plugin:
     $( selector ).cbpFWSlider( [options] );
     - options:
     {
     // default transition speed (ms)
     speed : 500,
     // default transition easing
     easing : 'ease'
     }
     - destroy:
     $( selector ).cbpFWSlider( 'destroy' );
     */

    $('#cbp-fwslider').cbpFWSlider();

});

$(window).load(function () {
    $('.flexslider').flexslider({
        animation: "slide",
        animationLoop: false,
        itemWidth: 210,
        itemMargin: 30,
        minItems: 2,
        maxItems: 3,
        start: function (slider) {
            $('body').removeClass('loading');
        }
    });
});

/*
 * REMEMBER TO CHANGE TO YOUR APP ID AND CHANGE data-href TO SUIT YOU
 */
//(function(d, s, id) {
//  var js, fjs = d.getElementsByTagName(s)[0];
//  if (d.getElementById(id)) return;
//  js = d.createElement(s); js.id = id;
//  js.src = "//connect.facebook.net/en_GB/all.js#xfbml=1&appId=ADD YOUR APP ID HERE";
//  fjs.parentNode.insertBefore(js, fjs);
//}(document, 'script', 'facebook-jssdk'));
//$(window).bind("load resize", function(){
//  var container_width = $('#container').width();
//    $('#container').html('<div class="fb-like-box" ' +
//    'data-href="https://web.facebook.com/acampartrek/?fref=ts"' +
//    ' data-width="' + container_width + '" data-height="480" data-show-faces="true" ' +
//    'data-stream="true" data-header="true"></div>');
//    FB.XFBML.parse( );
//});

/*$('.desplegable-ciclo').click(function()
 {
 $('.desplegable-ciclo-individual').show('fast');
 $('html, body').animate({
 scrollTop: $('.desplegable-ciclo-individual').offset().top - 100
 }, 1000);
 });*/

/*$('.desplegable-trekking').click(function()
 {
 $("#append-li").html("");
 $.getJSON( "json/"+this.id+".json", function( data ){
 $.each( data, function( i, val ) {
 if(i == 'nombre'){
 $("#apend-h3").html(val);
 }else{
 $("#append-li").append( "<li class='alert alert-success'><a href='#'>" + val + "</li>" );
 }
 });
 });
 $('.desplegable-trekking-individual').show('fast');
 $('html, body').animate({
 scrollTop: $('.desplegable-trekking-individual').offset().top - 100
 }, 1000);
 });*/
