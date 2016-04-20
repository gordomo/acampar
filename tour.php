<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
?>
<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Acampar Trek</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
   
    <!-- Custom CSS -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/flexslider.css" rel="stylesheet" />
    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Product+Sans' rel='stylesheet' type='text/css'>
    
    <script src="js/modernizr.custom.js"></script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>
      <!-- Header -->
    <header>
<nav class="navbar navbar-default" role="navigation">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>

    </div>
    <a class="navbar-brand" href="index.php"><img src="img/logos/logo.png" class="img-responsive center-block" alt=""></a>
    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="navbar-collapse-1">

      <ul class="nav navbar-nav navbar-left">
        <li>
            <a class="page-scroll" href="index.php#tour">Nuestros Tours</a>
        </li>
        <li>
            <a class="page-scroll" href="index.php#promociones">Próximas Salidas</a>
        </li>
        <li>
            <a class="page-scroll" href="index.php#noticias">Noticias</a>
        </li>
        <li>
            <a class="page-scroll" href="index.php#facebook">Facebook</a>
        </li>
    </ul>
    <ul class="nav navbar-nav navbar-right">
        <li>
            <a href="yacanto/index.html" target="_blanck" >Posta Yacanto</a>
        </li>
        <li>
            <a class="page-scroll" href="index.php#calendario">Calendario</a>
        </li>
        <li>
            <a class="page-scroll" href="index.php#empresa">La Empresa</a>
        </li>
        <li>
            <a class="page-scroll" href="index.php#contacto">Contacto</a>
        </li>
    </ul>
    </div><!-- /.navbar-collapse -->
</nav>
</header>

<?php
$resultado = getInfoCategoria($mysqli, $_GET['id']);
if($resultado['result'] == 'true'){
    $datos = $resultado['categoria'];
}
?>
 <section id="carousel">
    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
  <!-- Indicators -->
  <ol class="carousel-indicators">
    <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
    <li data-target="#carousel-example-generic" data-slide-to="1"></li>
    <li data-target="#carousel-example-generic" data-slide-to="2"></li>
  </ol>

  <!-- Wrapper for slides -->
  <div class="carousel-inner">
    <div class="item active">
      <img src="img/slider/slider1.jpg" alt="..." class="img-responsive">
      <div class="carousel-caption">
          <h3>Titulo</h3>
          <h4>Descripcion</h4>
      </div>
    </div>
    <div class="item">
        <img src="img/slider/slider2.jpg" alt="..." class="img-responsive">
        <div class="carousel-caption">
            <h3><?=$datos['nombre']?></h3>
            <h4>Descripcion</h4>
        </div>
    </div>
  </div>
  </div> <!-- Carousel -->
</section>
   
<section id="empresa">
    <div class="container ">
        <div class="row ">
            <div class="article col-sm-8 col-sm-offset-2">
                <h2><?=$datos['nombre']?></h2>
                <p><?=$datos['descripcion']?></p>
            </div>
        </div>
    </div>
</section>


<figure class="map">
    <div style='overflow:hidden;height:400px;width:100%;'><div id='gmap_canvas' style='height:400px;width:100%;'></div>
    <style>#gmap_canvas img{max-width:none!important;background:none!important}</style></div> 
</figure>


    <!-- Section Contacto  -->
    <section id="contacto">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="section-heading">Contacto</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <form name="sentMessage" id="contactForm" novalidate>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Tu Nombre | Your Name *" id="name" required data-validation-required-message="Please enter your name.">
                                    <p class="help-block text-danger"></p>
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control" placeholder="Tu Mail | Your Email *" id="email_cons" required data-validation-required-message="Please enter your email address.">
                                    <p class="help-block text-danger"></p>
                                </div>
                                    <div class="form-group">
                                        <input type="email" class="form-control" placeholder=" Confirma Tu Mail | Your Email *" id="email_cons" required data-validation-required-message="Please enter your email address.">
                                        <p class="help-block text-danger"></p>
                                    </div>
                                <div class="form-group">
                                    <input type="tel" class="form-control" placeholder="Tu número de teléfono | Your Phone *" id="phone_cons" required data-validation-required-message="Please enter your phone number.">
                                    <p class="help-block text-danger"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <textarea class="form-control" placeholder="Tu mensaje | Your Message *" id="message" required data-validation-required-message="Please enter a message." rows="10"></textarea>
                                    <p class="help-block text-danger"></p>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-lg-12 text-center">
                                <div id="success"></div>
                                <button type="button" class="btn btn-xl btn-submit-consulta">Enviar mensaje</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="row">
          
                <div class="col-md-12">

                    <ul class="list-inline social-buttons">
                        <li><a href="#"><i class="fa fa-twitter"></i></a>
                        </li>
                        <li><a href="#"><i class="fa fa-facebook"></i></a>
                        </li>
                        <li><a href="#"><i class="fa fa-linkedin"></i></a>
                        </li>
                    </ul>
                    <span class="copyright">Copyright &copy; Acampartrek 2016</span>
                </div>
             
            </div>
        </div>
    </footer>



    <!-- jQuery -->
    <script src="js/jquery.js"></script>
 <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&amp;amp;sensor=false"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
   
    <!-- Custom Theme JavaScript -->
    <script type="text/javascript" src="js/jquery.flexslider-min.js"></script>
    <script src="js/main.js"></script>

    <script src='https://maps.googleapis.com/maps/api/js?v=3.exp'></script>
    <a href='http://mapswebsite.net/es'>http://mapswebsite.net/es</a>
    <script type='text/javascript' src='https://embedmaps.com/google-maps-authorization/script.js?id=ecbfea3dff5104b3614ca3fd2456ff39559a417c'></script>
    <script type='text/javascript'>
        function init_map(){
                var myOptions = {zoom:11,center:new google.maps.LatLng(-34,-64),mapTypeId: google.maps.MapTypeId.TERRAIN};
                map = new google.maps.Map(document.getElementById('gmap_canvas'), myOptions);
                marker = new google.maps.Marker({map: map,position: new google.maps.LatLng(-34,-64)});
                infowindow = new google.maps.InfoWindow({content:'<strong><?=$datos['nombre']?></strong><br>-32.108617,-64.760455<br><br>'});
                google.maps.event.addListener(marker, 'click', function(){
                    infowindow.open(map,marker);
                });
                infowindow.open(map,marker);
            }
            google.maps.event.addDomListener(window, 'load', init_map);
    </script>
</body>

</html>