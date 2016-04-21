<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

$sliders_cabecera = getSliderCabecera($mysqli, false, true);
$sliders_salidas = getSliderSalidas($mysqli,  false, true);
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
        <!--<link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">-->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css" />
        <link href='https://fonts.googleapis.com/css?family=Product+Sans' rel='stylesheet' type='text/css'>

        <script src="js/modernizr.custom.js"></script>

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
        <?php 
            $calendarios = getCalendario($mysqli);
        ?>
    </head>

    <body>
        <div id="fb-root"></div>
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
                <a class="navbar-brand" href="#"><img src="img/logos/logo.png" class="img-responsive center-block" alt=""></a>
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="navbar-collapse-1">

                    <ul class="nav navbar-nav navbar-left">
                        <li>
                            <a class="page-scroll" href="#tour">Nuestros Tours</a>
                        </li>
                        <li>
                            <a class="page-scroll" href="#promociones">Próximas Salidas</a>
                        </li>
                        <li>
                            <a class="page-scroll" href="#noticias">Noticias</a>
                        </li>
                        <li>
                            <a class="page-scroll" href="#facebook">Facebook</a>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a href="yacanto/index.html" target="_self" >Posta Yacanto</a>
                        </li>
                        <li>
                            <a class="page-scroll" href="#calendario">Calendario</a>
                        </li>
                        <li>
                            <a class="page-scroll" href="#empresa">La Empresa</a>
                        </li>
                        <li>
                            <a class="page-scroll" href="#contacto">Contacto</a>
                        </li>
                    </ul>
                </div><!-- /.navbar-collapse -->
            </nav>


        </header>

        <section id="carousel">
            <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                <!-- Wrapper for slides -->
                <div class="carousel-inner">
                    <?php 
                    $i = 0;
                    foreach ($sliders_cabecera['sliders'] as $slider) {?>
                    <div class="item <?php if($i == 0) { echo 'active'; }?>">
                        <img src="<?= $slider['url'] ?>" alt="..." class="img-responsive">
                        <div class="carousel-caption">
                            <h3><?= $slider['titulo'] ?></h3>
                            <h4><?= $slider['descripcion'] ?></h4>
                        </div>
                    </div>
                    <?php 
                        $i ++;
                    } ?>
                </div>
                <!-- Indicators -->
                <ol class="carousel-indicators">
                    <?php for ($index = 0; $index < $i; $index++) { ?>
                        <li data-target="#carousel-example-generic" data-slide-to="<?=$index?>" class="<?php if($index == 0) { echo 'active'; }?>"></li>
                    <?php } ?>
                </ol>
            </div> <!-- Carousel -->
        </section>
        <!-- tour Section -->
        <section id="tour">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <h2 class="section-heading">Nuestros Tour</h2>
                        <h3 class="section-subheading text-muted">Texto descriptivo, general del tipo de actividad, servicio que ofrece, intengibles que voy a experimentar </h3>
                    </div>
                </div>
                <?php
                $tours = getTours($mysqli);
                if ($tours['result']) {
                    ?>
                    <div class="row">
                        <?php
                        foreach ($tours['tours'] as $id => $data) {
                            ?>
                            <div class="col-md-4">
                                <div class="<?= $data['class_css'] ?> imagenesCirculares" id="<?= $data['id_css'] ?>">
                                    <h3><?= $data['nombre'] ?></h3>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>

                    <?php
                    //CATEGORIAS DE CADA TOUR
                    foreach ($tours['tours'] as $id => $data) {
                        ?>
                        <div class="row desplegable-<?=$data['id_css']?>">
                            <hr/>
                            <?php
                            if($data['id'] == '2'){
                            ?>
                            <div class="row">
                                <div class="col-md-12 col-xs-12 text-center" style="padding-bottom: 40px">
                                    <div class="col-md-4 col-xs-12 desplegadas" id="9"><img src="img/iconos/aconcagua.png" class="img-responsive" /> Aconcagua</div>
                                    <div class="col-md-4 col-xs-12 desplegadas" id="11"><img src="img/iconos/champaqui.png" class="img-responsive" /> Champaquí</div>
                                    <div class="col-md-4 col-xs-12 desplegadas" id="13"><img src="img/iconos/cumbres_argentinas.png" class="img-responsive" /> Montañas Argentinas</div>
                                </div>
                                <div class="col-md-12 col-xs-12 text-center" style="padding-bottom: 30px">
                                    <div class="col-md-4 col-xs-12 desplegadas" id="14"><img src="img/iconos/patagonia.png" class="img-responsive" /> Patagonia</div>
                                    <div class="col-md-4 col-xs-12 desplegadas" id="12"><img src="img/iconos/quebrada_del_condorito.png" class="img-responsive" /> Quebrada del Condorito</div>
                                    <div class="col-md-4 col-xs-12 desplegadas" id="10"><img src="img/iconos/sendas_incas.png" class="img-responsive" /> Sendas Incas</div>
                                </div>
                            </div>
                            <hr/>
                            <div class="row desplegable-individual">
                                <div class="col-md-6 col-xs-12 text-center">
                                    <b></b>
                                </div>
                                <div class="col-md-6 col-xs-12 text-left">
                                    <ol class="circle-list"></ol>
                                </div>
                            </div>
                            <?php
                            }else{
                            ?>
                            <div class="row">
                                <div class="col-md-6 col-xs-12 text-center" style="font: 40px 'trebuchet MS', 'lucida sans';padding: .4em; color:#FE7800">
                                    <div class="col-md-12">
                                        <b><?=ucfirst($data['nombre'])?></b>
                                    </div>
                                    <div class="col-md-12">
                                        <p class="tour-desc"><?=$data['descripcion']?></p>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xs-12 text-left" style="color:#FE7800">
                                    <ol class="circle-list">
                                <?php
                                    $categorias = getCategorias($mysqli, $data['id'], "0");
                                    foreach ($categorias['categorias'] as $id_cat => $data_cat) {
                                ?>
                                        <li><a href="tour.php?id=<?=$data_cat['id']?>"><?=$data_cat['nombre']?></a></li>
                                <?php
                                }
                                ?>
                                    </ol>
                                </div>
                            </div>
                            <?php
                            }
                            ?>
                        </div>
                        <?php
                    }
                } else {
                    echo $tours['mensaje'];
                }
                ?>
        </section>

        <section id="promociones">
            <div class="container">
                <div id="cbp-fwslider" class="cbp-fwslider">
                    <ul>
                        <?php 
                        $i = 0;
                        foreach ($sliders_salidas['sliders'] as $slider) {?>
                        <li>
                            <img src="<?=$slider['url']?>" class="img-responsive"/>
                            <div class="carousel-caption">
                                <h3><?=$slider['titulo']?></h3>
                                <h4><?=$slider['descripcion']?></h4>
                            </div>
                        </li>
                        <?php 
                            $i++;
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </section>


        <!-- Services Section -->
        <section id="noticias">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <h2 class="section-heading">Noticias</h2>
                        <h3 class="section-subheading text-muted">Texto descriptivo, general del tipo actividad, servicio que ofrece intangibles, que voy a experimentar.</h3>
                    </div>
                </div>
                <div class="row text-center">
                    <div class="flexslider">
                        <ul class="slides">
                            <?php
                            $noticias = getNoticias($mysqli, false, true);
                            foreach ($noticias['noticias'] as $slider) {
                            ?>
                            <li>
                                <div style="min-height:100px">
                                    <a href="#"><img class="img-responsive" src="<?=$slider['url']?>" alt="" style="margin: 0 auto;" width="350px" /></a>
                                </div>
                                <h3><?=$slider['titulo']?></h3>
                                <span>
                                    <a href="noticias/noticia.php?noticia=<?=$slider['id']?>">
                                        ver mas...
                                    </a>
                                </span>
                            </li>
                            <?php
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <section id='calendario'>
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <h2 class="section-heading">Calendario</h2>
                        <h3 class="section-subheading text-muted">Texto descriptivo, general del tipo actividad, servicio que ofrece intangibles, que voy a experimentar.</h3>
                    </div>
                </div>

                <div class="row">
                    <div class="nav-meses col-md-12">
                        <ul>
                            <li><a href="" id="mes1">Enero</a></li>
                            <li><a href="" id="mes2">Febrero</a></li>
                            <li><a href="" id="mes3">Marzo</a></li>
                            <li><a href="" id="mes4">Abril</a></li>
                            <li><a href="" id="mes5">Mayo</a></li>
                            <li><a href="" id="mes6">Junio</a></li>
                            <li><a href="" id="mes7">Julio</a></li>
                            <li><a href="" id="mes8">Agosto</a></li>
                            <li><a href="" id="mes9">Septiembre</a></li>
                            <li><a href="" id="mes10">Octubre</a></li>
                            <li><a href="" id="mes11">Noviembre</a></li>
                            <li><a href="" id="mes12">Diciembre</a></li>
                        </ul>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 fechas">
                            <div class='row vertical-align no-event' style="display: none">
                                <div class='col-md-1 text-left'>
                                    <i class='fa fa-calendar'></i>
                                </div>
                                <div class='col-md-11 text-left texto'>
                                    No existen eventos para este mes
                                </div>
                            </div>
                        <?php foreach ($calendarios as $calendario){ ?>
                            <div class='row vertical-align mes<?=$calendario['mes']?>' style="display: none">
                                <div class='col-md-1 text-left'>
                                    <i class='fa fa-calendar'></i>
                                </div>
                                <div class='col-md-3 text-left texto'>
                                    <?=$calendario['dias']?>
                                </div>
                                <div class='col-md-8 text-left'>
                                    <?php foreach ($calendario['id_excursiones'] as $id_excursiones) {
                                        $tour = getToursFromCategorias($mysqli, $id_excursiones);?>
                                    
                                        <a href="tour.php?id=<?=$tour['id']?>"><?=$tour['nombre']?></a>
                                        <br>
                                    <?php } ?>
                                </div>
                            </div>
                            <hr class="mes<?=$calendario['mes']?>" style="display: none">
                        <?php } ?>
                    </div>
                </div>
            </div>
        </section>

        <section id="facebook">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <div id="container" style="width:100%;">
                            <div id="fb-root">
                                <div class="fb-page" data-href="https://www.facebook.com/acampartrek/"  data-tabs="timeline" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><div class="fb-xfbml-parse-ignore"><blockquote cite="https://www.facebook.com/acampartrek/"><a href="https://www.facebook.com/acampartrek/">Acampar Trek</a></blockquote></div></div>
                            </div>
                            <script>(function (d, s, id) {
                                    var js, fjs = d.getElementsByTagName(s)[0];
                                    if (d.getElementById(id))
                                        return;
                                    js = d.createElement(s);
                                    js.id = id;
                                    js.src = "//connect.facebook.net/es_LA/sdk.js#xfbml=1&version=v2.5&appId=571843789627462";
                                    fjs.parentNode.insertBefore(js, fjs);
                                }(document, 'script', 'facebook-jssdk'));</script>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section id="empresa">
            <div class="container ">
                <div class="row ">
                    <div class="col-lg-12 text-center">
                        <h2 class="section-heading">Quienes somos?</h2>

                        <h3 class="section-subheading text-muted">Acampartrek® es un emprendimiento familiar
                            que ha logrado reunir, durante más de 20 años de trayectoria, un notable Staff de guías, asistentes y colaboradores que logran transmitir el amor y la
                            pasión por su profesión en cada actividad propuesta, con un trato personal y afectivo.
                        </h3>
                        <button type="button" class="btn btn-xl btn-submit-consulta" onclick="location.href='qsomos/index.html'">Logísticas y Condiciones</button>
                    </div>

                </div>

            </div>
        </section>

        <section id="satelital">
            <div class="container ">
                <div class="row ">
                    <div class="col-lg-12 text-center">
                        <h2 class="section-heading">Seguimiento Satelital</h2>
                    </div>
                </div>
                <div class="row ">
                    <div class="col-md-4">
                        <img class="img-responsive center-block" src="img/spot1.jpg" height="250" width="250">
                    </div>
                    <div class="col-md-4 text-center">
                        <button type="button" class="btn btn-xl btn-submit-consulta" onclick="location.href='seguimiento/index.html'">Seguí el viaje</button>
                    </div>
                    <div class="col-md-4">
                        <img class="img-responsive center-block" src="img/spot2.jpg" height="250" width="250">
                    </div>
                </div>

            </div>
        </section>


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
                                    <button type="button" class="btn btn-xl btn-submit-consulta" data-loading-text="Enviando...">Enviar mensaje</button>
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
                            <li><a href="#"><i class="fa fa-whatsapp"></i></a></li>
                            <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                            <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                            <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                        </ul>
                        <span class="copyright">Copyright &copy; Acampartrek 2016</span>
                    </div>

                </div>
            </div>
        </footer>



        <!-- jQuery -->
        <script src="js/jquery.js"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="js/bootstrap.min.js"></script>
        <script src="js/jquery.cbpFWSlider.min.js"></script>
        <!-- Custom Theme JavaScript -->
        <script type="text/javascript" src="js/jquery.flexslider-min.js"></script>

        <script src="js/main.js"></script>

    </body>

</html>
