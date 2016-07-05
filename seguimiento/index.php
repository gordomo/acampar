<?php
    include_once '../includes/db_connect.php';
    include_once '../includes/functions.php';
    $textoSeguimiento = getSeguimientoSatelital($mysqli);
?>    
<!DOCTYPE html>
<html lang="en" class="no-js">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Acampar Trek - Seguimiento</title>

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
                            <a href="../yacanto/" target="_blank" >Posta Yacanto</a>
                        </li>
                        <li>
                            <a class="page-scroll" href="../index.php#calendario">Calendario</a>
                        </li>
                        <li>
                            <a class="page-scroll" href="../index.php#empresa">La Empresa</a>
                        </li>
                        <li>
                            <a class="page-scroll" href="../index.php#contacto">Contacto</a>
                        </li>
                    </ul>
                </div><!-- /.navbar-collapse -->
            </nav>


        </header>



        <section id="empresa">
            <div class="container ">
                <div class="row ">
                    <div class="article col-sm-8 col-sm-offset-2">
                        <h2>Seguí los Viajes de Acampar Trek desde nuestra Web</h2>

                        <p><?= $textoSeguimiento['texto'] ?></p>
                        <iframe src="http://share.findmespot.com/shared/faces/viewspots.jsp?glId=0htyntSEtPVWRfFYvQq8mV4h4jDKYyaJh" width="750" height="600" frameborder="0" allowfullscreen="allowfullscreen"></iframe>


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

        <script src="../js/main.js"></script>


    </body>

</html>