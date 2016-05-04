<?php
include_once '../includes/db_connect.php';
include_once '../includes/functions.php';

sec_session_start();

if (login_check($mysqli) == true) {
    $logged = 'in';
} else {
    $logged = 'out';
}

if ($logged == 'out') {
    header("Location: login.php");
    exit();
}

$textoSeguimiento = getSeguimientoSatelital($mysqli);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">

        <title>Administración Acampar Trek</title>

        <!-- Bootstrap core CSS -->
        <link href="assets/css/bootstrap.css" rel="stylesheet">
        <!--external css-->
        <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet" />

        <!-- Custom styles for this template -->
        <link href="assets/css/style.css" rel="stylesheet">
        <link href="assets/css/style-responsive.css" rel="stylesheet">
        <link href="assets/css/table-responsive.css" rel="stylesheet">
        <script src="../js/jquery.js"></script>
        <script src="assets/js/main.js"></script>
        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>

    <body>

        <section id="container" >
            <?php include_once 'header.php'; ?>
            <?php include_once 'sidebar.php'; ?>
            <section id="main-content">
                <section class="wrapper">
                    <h3><i class="fa fa-angle-right"></i>TEXTO SEGUIMIENTO SATELITAL</h3>
                    <div class="row mt">
                        <div class="col-lg-12">
                            <div class="form-panel">
                                <h4><i class="fa fa-angle-right"></i>Edicion</h4>
                                <section id="editor_grilla_nueva">
                                    <form id='edicion' class="form" method="POST" action="adminController.php">
                                        <input type="hidden" value="editSeguimientoSatalital" name="action" id="action">
                                        <div class="form-group">
                                            <label class="col-sm-2 col-sm-2 control-label">Texto: </label>
                                            <input value="<?= $textoSeguimiento['texto'] ?>" type="text" name="texto" id="descripcion" class="form-control" required="true">
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-success">Editar</button>
                                            <img class="newImgCargando" src="assets/img/preloader.gif">
                                            <span class="newImgCargando">Cargando...</span>
                                        </div>
                                    </form>
                                </section>
                            </div><!-- /content-panel -->
                        </div>
                    </div>

                </section>
            </section>
        </section>