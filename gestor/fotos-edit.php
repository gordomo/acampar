<?php
include_once 'includes/db_connect.php';
include_once 'includes/funciones.php';

sec_session_start();

if (login_check($mysqli) == true) {
    $logged = 'in';
} else {
    $logged = 'out';
}

if($logged == 'out'){
    header("Location: login.php");
    exit();
}

$fotos = getFotos($mysqli);
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    
    <title>Administración Vinci</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <!--external css-->
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
        
    <!-- Custom styles for this template -->
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/style-responsive.css" rel="stylesheet">
    <link href="assets/css/table-responsive.css" rel="stylesheet">
    <script src="../assets/jquery/jquery.min.js"></script>
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
                    <h3><i class="fa fa-angle-right"></i>FOTOS</h3>
                    <div class="row mt">
                        <div class="col-lg-12">
                            <div class="form-panel">
                                <h4><i class="fa fa-angle-right"></i> Nueva Foto</h4>
                                <section id="editor_grilla_nueva">
                                    <form class="form" enctype="multipart/form-data" method="POST" action="adminController.php">
                                        <input type="hidden" value="newFoto" name="action" id="action">
                                        <div class="form-group">
                                            <input type="file" accept="file_extension|image"  id="photo" name="photo" required autofocus>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 col-sm-2 control-label">Descripci&oacute;n: </label>
                                            <input type="text" class="form-control" required="true" name="desc" id="desc">
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 col-sm-2 control-label">Habilitado: </label>
                                            <select class="form-control" name="habilitado">
                                                <option value="1">Si</option>
                                                <option value="0">No</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                                <button type="submit" class="btn btn-success">Agregar Foto</button>
                                                <img class="newImgCargando" src="assets/img/preloader.gif">
                                                <span class="newImgCargando">Cargando...</span>
                                        </div>
                                    </form>
                                </section>
                            </div><!-- /content-panel -->
                        </div>
                    </div>
                    
                    <div class="row mt form-panel">
                        <div class="col-md-12">
                            <h4><i class="fa fa-angle-right"></i> Editar Fotos</h4>
                        </div>        
                        <section>
                                    <?php foreach ($fotos as $foto){ ?>
                                    <div class="edicionFotos">
                                        <form class="form" enctype="multipart/form-data" method="POST" action="adminController.php">
                                            <input type="hidden" value="editarFoto" name="action" id="action">
                                            <input type="hidden" value="<?= $foto['id'] ?>" name="id" id="id">
                                            <div class="form-group col-md-4" style="height: 200px; width: 200px">
                                                <img class="img-responsive" src="../<?= $foto['url_thumb'] ?>">
                                            </div>
                                            <div class="form-group">
                                                <input type="file" accept="file_extension|image"  id="photo" name="photo" autofocus>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-12 control-label">Descripci&oacute;n: </label>
                                                <input type="text" class="form-control" required="true" name="desc" id="desc" value="<?= $foto['desc'] ?>">
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-12 control-label">Habilitado: </label>
                                                <select class="form-control" name="habilitado">
                                                    <option value="1" <?php if($foto['habilitado'] == 1){echo 'selected';}?>>Si</option>
                                                    <option value="0" <?php if($foto['habilitado'] == 0){echo 'selected';}?>>No</option>
                                                </select>
                                            </div>
                                            <button type="submit" class="btn btn-success">Editar Foto</button>
                                            <a class="btn btn-danger borrar" href="adminController.php?action=borrarFoto&id=<?=$foto['id']?>">Eliminar Foto</a>
                                            <img style="display: none" class="editImgCargando<?=$foto['id']?>" src="assets/img/preloader.gif">
                                            <span style="display: none" class="editImgCargando<?=$foto['id']?>">Cargando...</span>
                                        </form>
                                    </div>
                                    <?php } ?>
                                </section>
                            </div>    
                </section>        
            </section><! --/wrapper -->