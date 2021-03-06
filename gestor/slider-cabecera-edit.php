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

$sliderCabecera = getSliderCabecera($mysqli, false, true);
$categorias = getCategorias($mysqli);
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
                    <h3><i class="fa fa-angle-right"></i>SLIDER CABECERA</h3>
                    <div class="row mt">
                        <div class="col-lg-12">
                            <div class="form-panel">
                                <h4><i class="fa fa-angle-right"></i> Nueva Entrada</h4>
                                <section id="editor_grilla_nueva">
                                    <form id='newEntrada' class="form" enctype="multipart/form-data" method="POST" action="adminController.php">
                                        <input type="hidden" value="newSliderHeader" name="action" id="action">
                                        <div class="form-group">
                                            <label class="col-sm-2 col-sm-2 control-label">Titulo: </label>
                                            <input type="text" id="titulo" name="titulo" class="form-control" required="true">
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 col-sm-2 control-label">Descripción: </label>
                                            <input type="text" name="descripcion" id="descripcion" class="form-control" required="true">
                                        </div>
                                        <div class="form-group">
                                            <label class=" col-md-12 control-label">Imagen: </label>
                                            <input type="file" accept="file_extension|image"  id="photo" name="photo" autofocus>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-12">Excursiones Relacionadas: </label>
                                            <select id="categoria_relacionada" name="categoria_relacionada" style="width: 80%; margin-right: 10px;margin-bottom: 15px;border-radius: 5px;border-color: #CCCCCC;">
                                                <?php foreach ($categorias['categorias'] as $categoria) { ?>

                                                    <option value="<?= $categoria['id'] ?>"><?= $categoria['nombre'] ?></option>

                                                <?php } ?>
                                            </select>    
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-12">Habilitado: </label>
                                            <select id="habilitado" name="habilitado" style="width: 80%; margin-right: 10px;margin-bottom: 15px;border-radius: 5px;border-color: #CCCCCC;">
                                                <option value="1">Si</option>
                                                <option value="1">No</option>
                                            </select>    
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-success">Agregar Entrada</button>
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
                            <h4><i class="fa fa-angle-right"></i> Editar Entradas</h4>
                        </div>        
                        <section id="editor_grilla_nueva">
                            <?php foreach ($sliderCabecera['sliders'] as $slider) { ?>
                            <form id='editEntrada' class="form" enctype="multipart/form-data" method="POST" action="adminController.php" style="width: 45%; border: 1px solid; padding: 4px; float: left; margin: 5px">
                                    <input type="hidden" value="editSliderHeader" name="action" id="action">
                                    <input type="hidden" value="<?=$slider['id']?>" name="id_slider" id="id_slider">
                                    <input type="hidden" value="<?=$slider['url']?>" name="foto" id="foto<?=$slider['id']?>">
                                    <div class="form-group">
                                        <label class="col-sm-2 col-sm-2 control-label">Titulo: </label>
                                        <input type="text" id="titulo" name="titulo" class="form-control" required="true" value="<?=$slider['titulo']?>">
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 col-sm-2 control-label">Descripción: </label>
                                        <input type="text" name="descripcion" id="descripcion" class="form-control" required="true" value="<?=$slider['descripcion']?>">
                                    </div>
                                    <div class="form-group">
                                        <label class=" col-md-12 control-label">Imagen: </label>
                                        <input type="file" accept="file_extension|image"  id="photo" name="photo" autofocus>
                                        <img class="img-responsive" src="../<?=$slider['url']?>">
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Excursiones Relacionadas: </label>
                                        <select id="categoria_relacionada" name="categoria_relacionada" style="width: 80%; margin-right: 10px;margin-bottom: 15px;border-radius: 5px;border-color: #CCCCCC;">
                                            <?php foreach ($categorias['categorias'] as $categoria) { ?>

                                                <option <?php if($categoria['id'] == $slider['categoria_id']){echo "selected";}?> value="<?= $categoria['id'] ?>"><?= $categoria['nombre'] ?></option>

                                            <?php } ?>
                                        </select>    
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Habilitado: </label>
                                        <select id="habilitado" name="habilitado" style="width: 80%; margin-right: 10px;margin-bottom: 15px;border-radius: 5px;border-color: #CCCCCC;">
                                            <option <?php if("1" == $slider['habilitado']){echo "selected";}?> value="1">Si</option>
                                            <option <?php if("0" == $slider['habilitado']){echo "selected";}?> value="0">No</option>
                                        </select>    
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-success">Editar Entrada</button>
                                        <a class="btn btn-danger eliminar" id='<?=$slider['id']?>'>Eliminar</a>
                                        <img class="newImgCargando" src="assets/img/preloader.gif">
                                        <span class="newImgCargando">Cargando...</span>
                                    </div>
                                </form>
                            <?php } ?>
                        </section>
                </section>
            </section>
        </section>
        <script>

            $(document).ready(function ()
            {
                $("#ano").val(new Date().getFullYear());

                $(".eliminar").click(function ()
                {
                    var answer = confirm("Deseas eliminar este registro?");
                    if (answer)
                    {
                        $.ajax({
                            type: "POST",
                            url: "adminController.php",
                            data: {id: $(this).attr('id'), action: 'eliminarEntradaSliderCabecera'},
                            success: function (data)
                            {
                                if (data.result == 'ok')
                                {
                                    location.reload();
                                } else
                                {
                                    alert('error al procesar el requerimiento: ' + data.mensaje);
                                }
                            },
                            dataType: "json"
                        });
                    } else
                    {
                        // do nothing
                    }
                });
            });
        </script>