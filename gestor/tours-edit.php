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
$padre_buscar = isset($_GET['padre_buscar']) ? $_GET['padre_buscar'] : '';
$categoria_buscar = isset($_GET['categoria_buscar']) ? $_GET['categoria_buscar'] : '';

$categorias = getCategorias($mysqli, $categoria_buscar, $padre_buscar);
$categorias_busqueda = getCategorias($mysqli);
$tours = getTours($mysqli);

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
                    <h3><i class="fa fa-angle-right"></i>TOURS</h3>
                    <div class="row mt">
                        <div class="col-md-12">
                            <div class="form-panel">
                                <h4><i class="fa fa-angle-right"></i> Nueva Entrada</h4>
                                <section id="editor_grilla_nueva">
                                    <form id='newEntrada' class="form" enctype="multipart/form-data" method="POST" action="adminController.php">
                                        <input type="hidden" value="newCategoria" name="action" id="action">
                                        <div class="form-group">
                                            <label class=" col-md-12 control-label">Nombre: </label>
                                            <input type="text" id="nombre" name="nombre" class="form-control" required="true">
                                        </div>
                                        <div class="form-group">
                                            <label class=" col-md-12 control-label">Imagen: </label>
                                            <input type="file" accept="file_extension|image"  id="photo" name="photo" autofocus>
                                        </div>
                                        <div class="form-group">
                                            <label class=" col-md-12 control-label">Descripción Corta: </label>
                                            <input type="text" id="descCorta" name="descCorta" class="form-control" >
                                        </div>
                                        <div class="form-group">
                                            <label class=" col-md-12 control-label">Descripción: </label>
                                            <input type="text" id="desc" name="desc" class="form-control" >
                                        </div>
                                        <div class="form-group">
                                            <label class=" col-md-12 control-label" >Coordenadas: </label>
                                            <label class=" col-md-12 control-label" style="width: 50%; float: left">Latitud: </label>
                                            <label class=" col-md-12 control-label" style="width: 50%; float: left">Longitud: </label>
                                            <input type="text" id="coordx" name="lat" class="form-control" style="width: 50%; float: left">
                                            <input type="text" id="coordy" name="long" class="form-control" style="width: 50%; float: left; margin-bottom: 10px">
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-12 control-label">Categoria: </label>
                                            <select id="categoria" name="categoria" style="width: 100%;margin-right: 10px;margin-bottom: 15px;border-radius: 3px;border-color: #CCCCCC;">
                                                <?php foreach ($tours['tours'] as $tour) { ?>

                                                    <option value="<?= $tour['id'] ?>"><?= $tour['nombre'] ?></option>

                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-12 control-label">Tour Padre: </label>
                                            <select id="padre" name="padre" style="width: 100%;margin-right: 10px;margin-bottom: 15px;border-radius: 3px;border-color: #CCCCCC;">
                                                <option value="0">Tour Padre</option>
                                                <?php foreach ($categorias['categorias'] as $categoria) { ?>

                                                    <option value="<?= $categoria['id'] ?>"><?= $categoria['nombre'] ?></option>

                                                <?php } ?>
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

                    <div class="row mt form-panel" id="editor_categorias">
                        <div class="col-md-12">
                            <h4><i class="fa fa-angle-right"></i> Editar Entradas</h4>
                        </div>
                        <div class="col-md-12" style="margin-bottom: 15px; margin-top: 50px">
                            <div class="col-md-2">
                                <i class="fa fa-angle-right"></i> Buscar 
                            </div>    
                            <div class="col-md-10">
                                <form action="tours-edit.php#editor_categorias" method="GET">
                                    <div class="col-md-4">
                                        <label class="control-label">Tour padre: </label>
                                        <select id="padre_buscar" name="padre_buscar">
                                            <option value="0">seleccione una tour para filtrar</option>
                                                <?php foreach ($categorias_busqueda['categorias'] as $categoria) { ?>

                                                    <option <?php if($categoria['id'] == $padre_buscar){echo "selected";} ?> value="<?= $categoria['id'] ?>"><?= $categoria['nombre'] ?></option>

                                                <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="control-label">Categoría: </label>
                                        <select id="categoria_buscar" name="categoria_buscar">
                                            <option value="0">seleccione una categoria para filtrar</option>
                                            <?php foreach ($tours['tours'] as $tour) { ?>

                                                <option <?php if($tour['id'] == $categoria_buscar){echo "selected";} ?> value="<?= $tour['id'] ?>"><?= $tour['nombre'] ?></option>

                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-success">buscar</button>
                                    </div>
                                    <div class="col-md-2">
                                        <a href="tours-edit.php#editor_categorias" class="btn btn-danger">limpiar</a>
                                    </div>
                                </form>
                            </div>
                        </div> 
                        <section>
                            <?php foreach ($categorias['categorias'] as $categoria) { ?>
                                <form id='newEntrada<?= $categoria['id'] ?>' class="form" enctype="multipart/form-data" method="POST" action="adminController.php" style="width: 32%;float:left;margin: 2px;border: 1px solid;padding: 2px;">
                                    <input type="hidden" value="editCategoria" name="action" id="action">
                                    <input type="hidden" value="no" name="borrarFoto" id="borrarFoto<?= $categoria['id'] ?>">
                                    <input type="hidden" value="<?= $categoria['id'] ?>" name="id" id="id">
                                    <input type="hidden" value="<?= $categoria['foto'] ?>" name="foto" id="foto<?= $categoria['id'] ?>">
                                    <div class="form-group">
                                        <label class=" col-md-12 control-label">Nombre: </label>
                                        <input type="text" value="<?= $categoria['nombre'] ?>" id="nombre" name="nombre" class="form-control" required="true">
                                    </div>
                                    <div class="form-group">
                                        <label class=" col-md-12 control-label">Imagen: </label>
                                        <input type="file" accept="file_extension|image"  id="photo<?= $categoria['id'] ?>" name="photo" autofocus>
                                        <div class="col-md-12" style="padding: 10px; min-height: 400px; max-height: 450px;">
                                            <img id="imgCat<?= $categoria['id'] ?>" class="img-responsive" src="../<?= $categoria['foto'] ?>" >
                                        </div>
                                        <a class="btn btn-warning borrarImagen" id="<?= $categoria['id'] ?>">Borrar Imagen</a>
                                    </div>
                                    <div class="form-group">
                                        <label class=" col-md-12 control-label">Descripción Corta: </label>
                                        <input type="text" value="<?=$categoria['descripcion_corta']?>" id="descCorta" name="descCorta" class="form-control" >
                                    </div>
                                    <div class="form-group">
                                        <label class=" col-md-12 control-label">Descripción: </label>
                                        <input type="text" value="<?=$categoria['descripcion']?>" id="desc" name="desc" class="form-control" >
                                    </div>
                                    <div class="form-group">
                                        <label class=" col-md-12 control-label">Coordenadas: </label>
                                        <label class=" col-md-12 control-label" style="width: 50%; float: left">Latitud: </label>
                                        <label class=" col-md-12 control-label" style="width: 50%; float: left">Longitud: </label>
                                        <input type="text" value="<?=$categoria['lat']?>" id="lat" name="lat" class="form-control" style="width: 50%; float: left">
                                        <input type="text" value="<?=$categoria['long']?>" id="long" name="long" class="form-control" style="width: 50%; float: left; margin-bottom: 10px">
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Categoria: </label>
                                        <select id="categoria" name="categoria" style="width: 80%;float: left; margin-right: 10px;margin-bottom: 15px;border-radius: 5px;border-color: #CCCCCC;">
                                            <?php foreach ($tours['tours'] as $tour) { ?>

                                                <option <?php if($categoria['id_tour'] == $tour['id']){echo "selected";}?> value="<?= $tour['id'] ?>"><?= $tour['nombre'] ?></option>

                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Tour Padre: </label>
                                        <select id="padre" name="padre" style="width: 80%;float: left; margin-right: 10px;margin-bottom: 15px;border-radius: 5px;border-color: #CCCCCC;">
                                            <option value="0">Sin Padre - Tour final</option>
                                            <?php 
                                                foreach ($categorias['categorias'] as $categoriaCombo) {
                                                    if($categoria['id'] != $categoriaCombo['id']){?>
                                                        <option <?php if($categoriaCombo['id'] == $categoria['cat_superior']){echo "selected";} ?> value="<?= $categoriaCombo['id'] ?>"><?= $categoriaCombo['nombre'] ?></option>
                                            <?php }
                                                } ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-success">Editar</button>
                                        <a class="btn btn-danger eliminar" id="<?= $categoria['id'] ?>" >Eliminar</a>
                                        <img class="newImgCargando" src="assets/img/preloader.gif">
                                        <span class="newImgCargando">Cargando...</span>
                                    </div>
                                </form>
                            <?php } ?>
                        </section>
                    </div>
                </section>
            </section>
        </section>
        <script>

            $(".borrarImagen").click(function()
            {
                var id = $(this).attr("id");
                $("#foto"+id).val("");
                $("#photo"+id).val("");
                $("#borrarFoto"+id).val("true");
                $("#newEntrada"+id).submit();
            });
        
                $(".eliminar").click(function ()
                {
                    var answer = confirm("Deseas eliminar este registro?");
                    if (answer)
                    {
                        $.ajax({
                            type: "POST",
                            url: "adminController.php",
                            data: {id: $(this).attr('id'), action: 'eliminarEntradaCategorias'},
                            success: function (data)
                            {
                                if (data.result == 'ok')
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
                });
        </script>