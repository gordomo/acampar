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

$empresa = getEmpresa($mysqli,  false, false);
$texto = 'No hay texto para Empresa';

if(isset($empresa['result']) && $empresa['result'] == 'ok'){
    $texto = (isset($empresa['nosotros']['texto'])) ?  $empresa['nosotros']['texto'] : '';
    $images[0] = (isset($empresa['nosotros']['images']) && $empresa['nosotros']['images'][0] != '') ?  $empresa['nosotros']['images'][0] : 'img/categorias/no-image.gif';
    $images[1] = (isset($empresa['nosotros']['images']) && $empresa['nosotros']['images'][1] != '') ?  $empresa['nosotros']['images'][1] : 'img/categorias/no-image.gif';
    $images[2] = (isset($empresa['nosotros']['images']) && $empresa['nosotros']['images'][2] != '') ?  $empresa['nosotros']['images'][2] : 'img/categorias/no-image.gif';
}

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
        <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
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
                    <h3><i class="fa fa-angle-right"></i>QUIENES SOMOS</h3>
                    <div class="row mt">
                        <div class="col-lg-12">
                            <div class="form-panel">
                                <h4><i class="fa fa-angle-right"></i>Edicion</h4>
                                <section id="editor_grilla_nueva">
                                    <form id='edicion' class="form" method="POST" enctype="multipart/form-data" action="adminController.php">
                                        <input type="hidden" value="editNosotros" name="action" id="action">
                                        <input type="hidden" value="<?=$images[0]?>" name="img_1" id="img_1">
                                        <input type="hidden" value="<?=$images[1]?>" name="img_2" id="img_2">
                                        <input type="hidden" value="<?=$images[2]?>" name="img_3" id="img_3">
                                        <div class="form-group">
                                            <label class="col-sm-2 col-sm-2 control-label">Texto: </label>
                                            <textarea name="texto" id="texto" rows="10" class="form-control" required="true"><?=$texto?>"</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label class=" col-md-12 control-label">Imagen 1: </label>
                                            <input type="file" accept="file_extension|image"  id="photo1" name="photo1" autofocus>
                                            <img class="img-responsive" src="../<?=$images[0]?>">
                                        </div>
                                        <div class="form-group">
                                            <label class=" col-md-12 control-label">Imagen 2: </label>
                                            <input type="file" accept="file_extension|image"  id="photo2" name="photo2" autofocus>
                                            <img class="img-responsive" src="../<?=$images[1]?>">
                                        </div>
                                        <div class="form-group">
                                            <label class=" col-md-12 control-label">Imagen 3: </label>
                                            <input type="file" accept="file_extension|image"  id="photo3" name="photo3" autofocus>
                                            <img class="img-responsive" src="../<?=$images[2]?>">
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
        
        <script>
            tinymce.init({
                selector: 'textarea',
                height: 500,
                plugins: [
                    "advlist autolink autosave link image lists charmap print preview hr anchor pagebreak spellchecker",
                    "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                    "table contextmenu directionality emoticons template textcolor paste fullpage textcolor colorpicker textpattern"
                ],
                toolbar1: "newdocument fullpage | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect",
                toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media code | insertdatetime preview | forecolor backcolor",
                toolbar3: "table | hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | spellchecker | visualchars visualblocks nonbreaking template pagebreak restoredraft",
            });
        </script>