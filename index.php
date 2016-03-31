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
         <h3>Titulo</h3>
          <h4>Descripcion</h4>
      </div>
    </div>

  </div>
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
				if($tours['result']){
			?>
				<div class="row">
					<?php
						foreach($tours['tours'] as $id=>$data){
					?>
						<div class="col-md-4">
							<div class="<?=$data['class_css']?> imagenesCirculares" id="<?=$data['id_css']?>">
								<h3><?=$data['nombre']?></h3>
							</div>
						</div>
					<?php
						}
					?>
				</div>

				<?php
					//CATEGORIAS DE CADA TOUR
					foreach($tours['tours'] as $id=>$data){
				?>
					<div class="row desplegable-<?=$data['id_css']?>">
						<hr/>
						<div class="row">
						<?php
						$categorias = getCategorias($mysqli, $data['id']);
						foreach($categorias['categorias'] as $id_cat=>$data_cat){
						?>
							<div class="col-md-4">
								<div class="desplegadas" id="<?=$data_cat['id']?>">
									 <h3><?=$data_cat['nombre']?></h3>
								 </div>
							</div>
						<?php
						}
						?>
						</div>
					</div>
				<?php
					}
					//FIN CATEGORIAS DE CADA TOUR


					//INFORMACION DE CADA CATEGORIA
				?>
					<div class="row desplegable-individual">
						<div>
							<hr />
							<div class="col-md-4">
								 <div class="desplegadas-individual">
									 <h3></h3>
								 </div>
							</div>
							<div class="col-md-8 alert alert-success">
								<div class="img-info" style="color:#4B4C47"></div>
								<div class="info" style="color:#4B4C47"></div>
								<hr/>
								<div>
									<h4>Dejanos tu consulta</h4>
									<div id="mensaje_contacto"></div>
									<form class="form-horizontal style-form" id="form-consulta" method="POST">
										<input type="hidden" name="categoria" id="categoria" />
										<div class="form-group">
											<label class="col-sm-2 col-sm-2 control-label">Nombre</label>
											<div class="col-sm-10">
												<input type="text" name="nombre" id="nombre" class="form-control" />
											</div>
											<label class="col-sm-2 col-sm-2 control-label">E-mail</label>
											<div class="col-sm-10">
												<input type="email" name="email" id="email" class="form-control" />
											</div>
											<label class="col-sm-2 col-sm-2 control-label">Telefono</label>
											<div class="col-sm-10">
												<input type="text" name="phone" id="phone" class="form-control" />
											</div>
											<label class="col-sm-2 col-sm-2 control-label">Consulta</label>
											<div class="col-sm-10">
												<textarea name="consulta" id="consulta" class="form-control"></textarea>
											</div>
											<br/>
											<br/>
											<div class="col-sm-12">
												<button type="button" class="btn btn-default pull-right btn-submit">Enviar</button>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
			<?php
					//FIN INFO DE CADA CATEGORIA

				}else{
					echo $tours['mensaje'];
				}
			?>
    </section>

<section id="promociones">
    <div class="container">
            <div id="cbp-fwslider" class="cbp-fwslider">
                <ul>
                    <li><a href="#"><img src="img/slider/slider1.jpg" alt="img01"/></a></li>
                    <li><a href="#"><img src="img/slider/slider2.jpg" alt="img02"/></a></li>

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
						<li>
							<div style="min-height:100px">
								<a href="#"><img class="img-responsive" src="img/icon2.jpg" alt="" style="margin: 0 auto;"></a>
							</div>
							<h3>Titulo de la noticia</h3>
							<span>
								<a href="#">
									ver mas...
								</a>
							</span>
						</li>
						<li>
							<div style="min-height:100px">
								<a href="#"><img class="img-responsive" src="img/icon.jpg" alt="" style="margin: 0 auto;"></a>
							</div>
							<h3>Titulo de la noticia 2</h3>
							<span>
								<a href="#">
									ver mas...
								</a>
							</span>
						</li>
						<li>
							<div style="min-height:100px">
								<a href="#"><img class="img-responsive" src="img/icon1.jpg" alt="" style="margin: 0 auto;"></a>
							</div>
							<h3>Titulo de la noticia 3</h3>
							<span>
								<a href="#">
									ver mas...
								</a>
							</span>
						</li>
						<li>
							<div style="min-height:100px">
								<a href="#"><img class="img-responsive" src="img/icon.jpg" alt="" style="margin: 0 auto;"></a>
							</div>
							<h3>Titulo de la noticia 4</h3>
							<span>
								<a href="#">
									ver mas...
								</a>
							</span>
						</li>
						<li>
							<div style="min-height:100px">
								<a href="#"><img class="img-responsive" src="img/icon2.jpg" alt="" style="margin: 0 auto;"></a>
							</div>
							<h3>Titulo de la noticia 5</h3>
							<span>
								<a href="#">
									ver mas...
								</a>
							</span>
						</li>
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
                                <div class="fb-page" data-href="https://www.facebook.com/acampartrek/" data-tabs="timeline" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><div class="fb-xfbml-parse-ignore"><blockquote cite="https://www.facebook.com/acampartrek/"><a href="https://www.facebook.com/acampartrek/">Acampar Trek</a></blockquote></div></div>
                            </div>
                            <script>(function(d, s, id) {
                              var js, fjs = d.getElementsByTagName(s)[0];
                              if (d.getElementById(id)) return;
                              js = d.createElement(s); js.id = id;
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
                <button type="button" class="btn btn-xl btn-submit-consulta">Logísticas y Condiciones</button>
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
               <button type="button" class="btn btn-xl btn-submit-consulta">Seguí el viaje</button>
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

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.cbpFWSlider.min.js"></script>
    <!-- Custom Theme JavaScript -->
	  <script type="text/javascript" src="js/jquery.flexslider-min.js"></script>
	 
    <script src="js/main.js"></script>


</body>

</html>
