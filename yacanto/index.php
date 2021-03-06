<?php
include_once '../includes/db_connect.php';
include_once '../includes/functions.php';
include_once '../includes/analyticstracking.php';
$resultado = getYacanto($mysqli);
if ($resultado['result'] == 'ok') {
    $datos = $resultado['categoria'];
}
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
        <link href="../css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom CSS -->
        <link href="../css/style.css" rel="stylesheet">
        <link href="../css/flexslider.css" rel="stylesheet" />
        <!-- Custom Fonts -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css" />
        <link href='https://fonts.googleapis.com/css?family=Product+Sans' rel='stylesheet' type='text/css'>

        <script src="../js/modernizr.custom.js"></script>

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

        <style>
            #map {
                width: 100%;
                height: 400px;
            }
        </style>
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
                <a class="navbar-brand" href="../index.php"><img src="../img/logos/logo.png" class="img-responsive center-block logo" alt=""></a>
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="navbar-collapse-1">

                    <ul class="nav navbar-nav navbar-left">
                        <li>
                            <a class="page-scroll" href="../index.php#tour">Nuestros Tours</a>
                        </li>
                        <li>
                            <a class="page-scroll" href="../index.php#promociones">Próximas Salidas</a>
                        </li>
                        <li>
                            <a class="page-scroll" href="../index.php#noticias">Noticias</a>
                        </li>
                        <li>
                            <a class="page-scroll" href="../index.php#facebook">Facebook</a>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a href="#" target="_blanck" >Posta Yacanto</a>
                        </li>
                        <li>
                            <a class="page-scroll" href="../index.php#calendario">Calendario</a>
                        </li>
                        <li>
                            <a class="page-scroll" href="../index.php#empresa">La Empresa</a>
                        </li>
                        <li>
                            <a class="page-scroll" href="#contacto">Contacto</a>
                        </li>
                    </ul>
                </div><!-- /.navbar-collapse -->
            </nav>
        </header>
        <div class="googleTranslate" style="text-align: right;">
            <div id="google_translate_element"></div>
            <script type="text/javascript">
                function googleTranslateElementInit() {
                  new google.translate.TranslateElement({pageLanguage: 'es', layout: google.translate.TranslateElement.InlineLayout.SIMPLE, autoDisplay: false}, 'google_translate_element');
                }
            </script><script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
        </div>
        <section>
            <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                <!-- Wrapper for slides -->
                <div class="carousel-inner">
                    <div class="item active">
                        <img src="../<?= $datos['foto'] ?>">
                        <div class="carousel-caption">
                            <h3><?= $datos['nombre'] ?></h3>
                            <h4><?= $datos['descripcion_corta'] ?></h4>
                        </div>   
                    </div>
                </div>
            </div> <!-- Carousel -->
        </section>      

        <section id="empresa">
            <div class="container ">
                <div class="row ">
                    <div class="article col-sm-8 col-sm-offset-2">
                        <p><?= $datos['descripcion'] ?></p>
                    </div>
                </div>
            </div>
        </section>
        <?php if ($datos['fotos_extras']) { ?>
            <section id="fotosExtras">
                <div class="container">
                    <div id="cbp-fwslider" class="cbp-fwslider">
                        <ul>
                            <?php
                            $i = 0;
                            foreach ($datos['fotos_extras'] as $slider) {
                                ?>
                                <li>
                                    <img src="../<?= $slider['url'] ?>" class="img-responsive"/>
                                </li>
                                <?php
                                $i++;
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </section>
<?php } ?>  
        <figure class="map">
            <div id="map"></div>
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
                            <input type="hidden" name="id_cat" id="id_cat" value="<?= $datos['id'] ?>" />
                            <input type="hidden" name="url" id="url" value="../includes/controller_ajax.php" />
                            
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
                                        <input type="email" class="form-control" placeholder=" Confirma Tu Mail | Confirm Your Email *" id="email_cons_conf" required data-validation-required-message="Please enter your email address.">
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
                            <li class="hidden-lg hidden-md"><a href="tel:+543415427965"><i class="fa fa-whatsapp" aria-hidden="true"></i></a></li>
                            <li><a href="https://twitter.com/Acampartrek"><i class="fa fa-twitter"></i></a></li>
                            <li><a href="https://www.facebook.com/acampartrek"><i class="fa fa-facebook"></i></a></li>
                            <li><a href="https://www.youtube.com/user/acampartrek"><i class="fa fa-youtube-play"></i></a></li>
                        </ul>
                        <span class="copyright">Copyright &copy; Acampartrek 2016</span>
                    </div>
                </div>
            </div>
        </footer>

        <!-- jQuery -->
        <script src="../js/jquery.js"></script>
        <!-- Bootstrap Core JavaScript -->
        <script src="../js/bootstrap.min.js"></script>

        <!-- Custom Theme JavaScript -->
        <script type="text/javascript" src="../js/jquery.flexslider-min.js"></script>
        <script src="../js/main.js"></script>
        <script src="../js/jquery.cbpFWSlider.min.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js"></script>
        <script>
            $(document).ready(function () {
                var mapDiv = document.getElementById('map');
                var map = new google.maps.Map(mapDiv, {
                    center: {lat: <?= $datos['lat'] ?>, lng: <?= $datos['long'] ?>},
                    zoom: 13,
                    //            disableDefaultUI: true,
                    disableDoubleClickZoom: true,
                    //            draggable: false,
                    fullscreenControl: false,
                    keyboardShortcuts: false,
                    scrollwheel: false,
                    mapTypeId: google.maps.MapTypeId.HYBRID
                });

                var flightPlanCoordinates = [
<?php foreach ($datos['polynes'] as $polynes) { ?>
                        {lat: <?= $polynes['lat'] ?>, lng: <?= $polynes['long'] ?>},
<?php } ?>

                ];
                var flightPath = new google.maps.Polyline({
                    path: flightPlanCoordinates,
                    geodesic: true,
                    strokeColor: '#FF0000',
                    strokeOpacity: 1.0,
                    strokeWeight: 2
                });

                flightPath.setMap(map);
            });
        </script>

    </body>

</html>