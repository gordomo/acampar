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

$calendarios = getCalendario($mysqli);
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
                    <h3><i class="fa fa-angle-right"></i>CALENDARIO</h3>
                    <div class="row mt">
                        <div class="col-lg-12">
                            <div class="form-panel">
                                <h4><i class="fa fa-angle-right"></i> Nueva Entrada</h4>
                                <section id="editor_grilla_nueva">
                                    <form id='newEntrada' class="form" enctype="multipart/form-data" method="POST" action="adminController.php">
                                        <input type="hidden" value="newCalendario" name="action" id="action">
                                        <div class="form-group">
                                            <label class="col-sm-2 col-sm-2 control-label">Mes: </label>
                                            <select class="form-control" name="mes" required="true">
                                                <option value="1">Enero</option>
                                                <option value="2">Febrero</option>
                                                <option value="3">Marzo</option>
                                                <option value="4">Abril</option>
                                                <option value="5">Mayo</option>
                                                <option value="6">Junio</option>
                                                <option value="7">Julio</option>
                                                <option value="8">Agosto</option>
                                                <option value="9">Septiembre</option>
                                                <option value="10">Octubre</option>
                                                <option value="11">Noviembre</option>
                                                <option value="12">Diciembre</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 col-sm-2 control-label">Año: </label>
                                            <input type="number" min="2000" id="ano" name="ano" class="form-control" required="true">
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 col-sm-2 control-label">Días: </label>
                                            <input type="text" name="dias" class="form-control" required="true">
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-12">Excursiones Relacionadas: </label>
                                            <select id="items" name="items[]" multiple style="width: 80%;float: left; margin-right: 10px;margin-bottom: 15px;border-radius: 5px;border-color: #CCCCCC;">
                                                <?php foreach ($categorias['categorias'] as $categoria) { ?>

                                                    <option value="<?= $categoria['id'] ?>"><?= $categoria['nombre'] ?></option>

                                                <?php } ?>
                                            </select>    
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-success">Agregar Entrada al calendario</button>
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
                            <?php foreach($calendarios as $calendario){?>
                            <form class="form editar-entrada-calendario" enctype="multipart/form-data" method="POST" action="adminController.php">
                                <input type="hidden" value="<?=$calendario['id']?>" name="id_entrada" id="id_entrada">
                                <input type="hidden" value="editCalendario" name="action" id="action">
                                <div class="form-group">
                                    <label class="col-sm-2 col-sm-2 control-label">Mes: </label>
                                    <select class="form-control" name="mes" required="true">
                                        <option value="1" <?php if($calendario['mes'] == 1) {echo 'selected';}?> >Enero</option>
                                        <option value="2" <?php if($calendario['mes'] == 2) {echo 'selected';}?> >Febrero</option>
                                        <option value="3" <?php if($calendario['mes'] == 3) {echo 'selected';}?> >Marzo</option>
                                        <option value="4" <?php if($calendario['mes'] == 4) {echo 'selected';}?> >Abril</option>
                                        <option value="5" <?php if($calendario['mes'] == 5) {echo 'selected';}?> >Mayo</option>
                                        <option value="6" <?php if($calendario['mes'] == 6) {echo 'selected';}?> >Junio</option>
                                        <option value="7" <?php if($calendario['mes'] == 7) {echo 'selected';}?> >Julio</option>
                                        <option value="8" <?php if($calendario['mes'] == 8) {echo 'selected';}?> >Agosto</option>
                                        <option value="9" <?php if($calendario['mes'] == 9) {echo 'selected';}?> >Septiembre</option>
                                        <option value="10" <?php if($calendario['mes'] == 10) {echo 'selected';}?> >Octubre</option>
                                        <option value="11" <?php if($calendario['mes'] == 11) {echo 'selected';}?> >Noviembre</option>
                                        <option value="12" <?php if($calendario['mes'] == 12) {echo 'selected';}?> >Diciembre</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 col-sm-2 control-label">Año: </label>
                                    <input type="number" min="2000" id="ano" name="ano" value="<?=$calendario['ano']?>" class="form-control" required="true">
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 col-sm-2 control-label">Días: </label>
                                    <input type="text" name="dias" class="form-control" value="<?=$calendario['dias']?>" required="true">
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Excursiones Relacionadas: </label>
                                    <select id="items" name="items[]" multiple style="width: 80%;float: left; margin-right: 10px;margin-bottom: 15px;border-radius: 5px;border-color: #CCCCCC;">
                                        <?php foreach ($categorias['categorias'] as $categoria) { ?>

                                            <option 
                                                <?php if(in_array($categoria['id'], $calendario['id_excursiones'])){?>
                                                selected="true" 
                                                <?php } ?>
                                                value="<?= $categoria['id'] ?>"><?= $categoria['nombre'] ?>
                                            </option>

                                        <?php } ?>
                                    </select>    
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success">Editar</button>
                                    <a class="btn btn-danger eliminar" id="<?=$calendario['id']?>" >Eliminar</a>
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
                        
                        $(".eliminar").click(function()
                        {
                            var answer = confirm("Deseas eliminar este registro?");
                            if (answer)
                            {
                                $.ajax({
                                        type: "POST",
                                        url: "adminController.php",
                                        data: {id: $(this).attr('id'), action: 'eliminarEntradaCalendario'},
                                        success: function(data)
                                        {
                                            if(data.result == 'ok')
                                            {
                                                location.reload();
                                            }
                                            else
                                            {
                                                alert('error al procesar el requerimiento: ' + data.mensaje);
                                            }
                                        },
                                        dataType: "json"
                                      });
                            }
                            else
                            {
                                // do nothing
                            }
                        });
                    });
                </script>