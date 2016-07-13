<?php
include_once '../includes/db_connect.php';
include_once '../includes/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Grupo Veta SRL">
    <link rel="shortcut icon" href="images/favicon.png">
    <title>Noticias - Acampartrek</title>
    <link rel="stylesheet" href="stylesheets/363f9277.main.css"/>
    <link rel="stylesheet" href="stylesheets/simplePagination.css"/>
    <style type="text/css">
        .selection {
            display: none;
        }
    </style>
    <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
 
    <script type="text/javascript">
      WebFontConfig = {
        google: {
          families: ['Open+Sans:300,400,700:latin', 'Lato:700,900:latin']
        }
      };
      (function() {
        var wf = document.createElement('script');
        wf.src = ('https:' == document.location.protocol ? 'https' : 'http') +
          '://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
        wf.type = 'text/javascript';
        wf.async = 'true';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(wf, s);
      })();
    </script>
</head>

<body>
<header class="header push-down-45">
	<div class="container">
            <div class="logo pull-left">
                <a href="../index.php"><img src="../img/logos/LogoHorizontal.png" alt="Logo" width="352" height="140" /></a>
            </div>
		
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#readable-navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
		
            <nav class="navbar navbar-default hidden-md hidden-lg" role="navigation">
                <div class="collapse navbar-collapse" id="readable-navbar-collapse">
                    <ul class="navigation">
                        <li class="dropdown  active">
                            <a href="index.php" class="dropdown-toggle" data-toggle="dropdown">Inicio</a>
                        </li>
                        <li class="">
                            <a href="features.html" class="dropdown-toggle" data-toggle="dropdown">Facebook</a>
                        </li>
                        <li class="">
                            <a href="features.html" class="dropdown-toggle" data-toggle="dropdown">Twitter</a>
                        </li>
                    </ul>
                </div> 
            </nav>
		
<!--            <div class="hidden-xs hidden-sm">
                <a href="#" class="search__container  js--toggle-search-mode"> <span class="glyphicon  glyphicon-search"></span></a>
            </div>-->
	</div>
</header>
<div class="search-panel">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <form action="#">
                    <input type="text" class="search-panel__form  js--search-panel-text" placeholder="Que desea buscar?" />
                    <p class="search-panel__text">Presione Enter para ver los resultados y Esc para salir.</p>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="googleTranslate" style="text-align: right;">
            <div id="google_translate_element"></div>
            <script type="text/javascript">
                function googleTranslateElementInit() {
                  new google.translate.TranslateElement({pageLanguage: 'es', layout: google.translate.TranslateElement.InlineLayout.SIMPLE, autoDisplay: false}, 'google_translate_element');
                }
            </script><script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
        </div>
    <div class="row">
	<div class="col-xs-12 col-md-8" >
            <?php
            $noticias = getNoticias($mysqli, false, false);
            $tot_noticias = count($noticias['noticias']);
            $p=1;
            $i=1;
            ?>
            <div id="page-<?=$p?>" class="selection">
            <?php
                foreach ($noticias['noticias'] as $slider) {
            ?>
                    <div class="boxed sticky push-down-45">
                        <div class="meta">
                            <div class="wp-post-image">
                                <?php if($slider['url'] != '' ) { ?>
                                    <img class="wp-post-image" src="../<?=$slider['url']?>" alt="<?=$slider['titulo']?>" width="500" height="324" />
                                <?php } else if($slider['video'] != '' ) { echo $slider['video']; }?>
                            </div>
                            <div class="meta__container">
                                <div class="row">
                                    <div class="col-xs-12  col-sm-8">
                                        <div class="meta__info">
                                            <span class="meta__date"><span class="glyphicon glyphicon-calendar"></span> &nbsp; <?=$slider['fecha']?></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-12  col-sm-4">
                                        <div class="meta__comments">
                                            <span class="glyphicon glyphicon-comment"></span> &nbsp;
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-10 col-xs-offset-1">
                                <div class="post-content--front-page">
                                    <h2 class="front-page-title"><?=$slider['titulo']?></h2>
                                    <p><?=  substr($slider['texto'],0,450)?></p>
                                </div>
                                <a href="noticia.php?noticia=<?=$slider['id']?>">
                                    <div class="read-more">Continuar leyendo
                                        <div class="read-more__arrow">
                                            <span class="glyphicon  glyphicon-chevron-right"></span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
            <?php
                    if(fmod($i, 4) == 0){
                        $p++;
                        echo "</div><div id='page-".$p."' class='selection'>";
                    }
                    $i++;
                }
            ?>
            </div>
            <?php
            
            ?>
            
            <div id="paginacion"></div>
	</div>
		
            <div class="col-xs-12  col-md-4">
                <div class="sidebar  boxed  push-down-30">
                    <div class="row">
                        <div class="col-xs-10  col-xs-offset-1">
                            <div class="widget-featured-post  push-down-30">
                                <h6>Mas novedades</h6>
                            </div>
                            <?php
                            $noticias = getNoticias($mysqli, false, false, 3);
                            foreach ($noticias['noticias'] as $slider) {
                            ?>
                            <div class="widget-featured-post  push-down-30">
                                <a href="noticia.php?noticia=<?=$slider['id']?>"><img src="../<?=$slider['url']?>" alt="Featured post" width="293" height="127" style="padding-bottom:10px" /></a>
                                <b><?=$slider['titulo']?></b>
                                <p><?=substr($slider['texto'], 0, 150)?></p>
                                <a href="noticia.php?noticia=<?=$slider['id']?>">Ver mas..</a>
                            </div>
                            <?php
                            }
                            ?>
                            <div class="tags  widget-tags">
                                <h6>Tags</h6>
                                <hr/>
                                <a href="#" class="tags__link">Tech</a>
                                <a href="#" class="tags__link">Web</a>
                                <a href="#" class="tags__link">UI/UX</a>
                                <a href="#" class="tags__link">Tutorials</a>
                                <a href="#" class="tags__link">Workflow</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
	</div>
</div>

	<footer class="footer">
            <div class="container">
                <div class="col-xs-12 col-md-3">
                    <div class="widget-about  push-down-30">
                        <img src="../img/logos/LogoHorizontal.png" alt="Logo" width="176" height="70">
                        <br/>
                        <br/>
                        <span class="footer__text">Acampartrek® es un emprendimiento familiar que ha logrado reunir, durante más de 20 años de trayectoria, un notable Staff de guías, asistentes y colaboradores.</span>
                        <br/>
                        <br/>
                        <div class="social-icons  widget-social-icons">
                            <a href="#" class="social-icons__container"> <span class="zocial-facebook"></span> </a>
                            <a href="#" class="social-icons__container"> <span class="zocial-twitter"></span> </a>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-4">&nbsp;</div>
                <div class="col-xs-12 col-md-2">
                    <nav class="widget-navigation  push-down-30">
                        <h6>Navegación</h6>
                        <hr/>
                        <ul class="navigation">
                            <li><a href="../index.php">Inicio</a></li>
                            <li><a href="../index.php#tour">Nuestros Tours</a></li>
                            <li><a href="../index.php#promociones">Próximas Salidas</a></li>
                            <li><a href="../yacanto/">Posta Yacanto</a></li>
                            <li><a href="../index.php#calendario">Calendario</a> </li>
                            <li><a href="../index.php#empresa">La Empresa</a> </li>
                        </ul>
                    </nav>
                </div>
                <div class="col-xs-12 col-md-2">
                    <div class="widget-contact  push-down-30">
                        <h6>Contactanos</h6>
                        <hr />
                        <span class="widget-contact__text">
                        <span class="widget-contact__title">Acampar Trek</span>
                        <br/>(0341) 4351750 y <br/> (0341) 155427965,
                        <br/>consultas@acampartrek.com
                        <br/>Rosario, Argentina
                        </span>
                    </div>
                </div>
            </div>
	</footer>
	<footer class="copyrights">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12  col-sm-6">&nbsp;</div>
                    <div class="col-xs-12  col-sm-6">
                    </div>
                </div>
            </div>
	</footer>
        <script src="js/jquery.js"></script>
	<script src="js/main.js"></script>
        <script src="js/jquery.simplePagination.js"></script>
	<script type="text/javascript">
            /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
            var disqus_shortname = 'readablehtml'; // required: replace example with your forum shortname
            /* * * DON'T EDIT BELOW THIS LINE * * */
            (function() {
                var s = document.createElement('script');
                s.async = true;
                s.type = 'text/javascript';
                s.src = '//' + disqus_shortname + '.disqus.com/count.js';
                (document.getElementsByTagName('HEAD')[0] || document.getElementsByTagName('BODY')[0]).appendChild(s);
            }());
            
            
            $(function(){
                $("#paginacion").pagination({
                    items: <?=$tot_noticias?>,
                    itemsOnPage: 4,
                    cssStyle: 'light-theme',
                    onPageClick: function(pageNumber){test(pageNumber)}
                });
            });
            
            function test(pageNumber)
            {
                var page="#page-"+pageNumber;
                $('.selection').hide();
                $(page).show();
            }
            
            $(document).ready(function(){
                test(1);
            });
        </script>
</body>
</html>