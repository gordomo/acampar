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
                        <div class="col-md-12 formulario">
                                <h4><i class="fa fa-angle-right"></i> Nueva Entrada</h4>
                                <section id="editor_grilla_nueva">
                                    <form id='newEntrada' class="form" enctype="multipart/form-data" method="POST" action="adminController.php">
                                        <input type="hidden" value="newCategoria" name="action" id="action">
                                        <div class="col-md-12">
                                            <label>Nombre: </label>
                                            <input type="text" id="nombre" name="nombre" class="form-control" required="true">
                                        </div>
                                        <div class="col-md-12">
                                            <label class="">Imagen Principal: </label>
                                        </div>
                                        <div class="col-md-12">
                                            <input type="file"  accept="file_extension|image"  id="photo" name="photo" autofocus>
                                        </div>
                                        <div class="col-md-12" style="margin-top: 10px; margin-bottom: 10px">
                                            <label class="">Agregar otra </label>
                                            <button type="button" id="sumarImagen" class="btn btn-default" style="padding: 0px 12px;margin-left: 10px;"> + </button>
                                            <button type="button" id="restarImagen" class="btn btn-default" style="padding: 0px 12px;margin-left: 10px;"> - </button>
                                            <input name="cantidadImagenesExtras" id="cantidadImagenesExtras" value="0" type="hidden">
                                            <div id="imagenes"></div>
                                        </div>
                                        <div class="col-md-12">
                                            <label>Descripción Corta: </label>
                                            <input type="text" id="descCorta" name="descCorta" class="form-control" >
                                        </div>
                                        <div class="col-md-12">
                                            <label>Descripción: </label>
                                            <input type="text" id="desc" name="desc" class="form-control" >
                                        </div>
                                        <div class="col-md-12">
                                            <label class=" col-md-12 " >Coordenadas: </label>
                                            <label class=" col-md-12 " style="width: 50%; float: left">Latitud: </label>
                                            <label class=" col-md-12 " style="width: 50%; float: left">Longitud: </label>
                                            <input type="text" id="coordx" name="lat" class="form-control" style="width: 50%; float: left">
                                            <input type="text" id="coordy" name="long" class="form-control" style="width: 50%; float: left; margin-bottom: 10px">
                                        </div>
                                        <div class="col-md-12">
                                            <label>Categoria: </label>
                                            <select id="categoria" name="categoria" style="width: 100%;margin-right: 10px;margin-bottom: 15px;border-radius: 3px;border-color: #CCCCCC;">
                                                <option value="0">Seleccione una Categoria</option>
                                                <?php foreach ($tours['tours'] as $tour) { ?>

                                                    <option value="<?= $tour['id'] ?>"><?= $tour['nombre'] ?></option>

                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="col-md-12">
                                            <label>Tour Padre: </label>
                                            <select id="padre" name="padre" disabled="true" style="width: 100%;margin-right: 10px;margin-bottom: 15px;border-radius: 3px;border-color: #CCCCCC;">
                                                <option value="0">Tour Padre</option>
                                                <optgroup label="Principales" id="principales" style="display: none">
                                                    <option value="1">Aconcagua</option>
                                                    <option value="2">Champaquí</option>
                                                    <option value="3">Cumbres Argentinas</option>
                                                    <option value="4">Patagonia</option>
                                                    <option value="5">Quebrada del Condorito</option>
                                                    <option value="6">Sendas Incas</option>
                                                </optgroup>
                                                <optgroup label="Secundarias">
                                                    <?php foreach ($categorias['categorias'] as $categoria) { ?>

                                                        <option value="<?= $categoria['id'] ?>"><?= $categoria['nombre'] ?></option>

                                                    <?php } ?>
                                                </optgroup>
                                            </select>
                                        </div>
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-success">Agregar Entrada</button>
                                            <img class="newImgCargando" src="assets/img/preloader.gif">
                                            <span class="newImgCargando">Cargando...</span>
                                        </div>
                                    </form>
                                </section>
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
                                        <label class="">Tour padre: </label>
                                        <select id="padre_buscar" name="padre_buscar" >
                                            <option value="">seleccione una tour para filtrar</option>
                                                <?php foreach ($categorias_busqueda['categorias'] as $categoria) { ?>

                                                    <option <?php if($categoria['id'] == $padre_buscar){echo "selected";} ?> value="<?= $categoria['id'] ?>"><?= $categoria['nombre'] ?></option>

                                                <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="">Categoría: </label>
                                        <select id="categoria_buscar" name="categoria_buscar">
                                            <option value="">seleccione una categoria para filtrar</option>
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
                            <div class="col-md-12">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>
                                                Nombre
                                            </th>
                                            <th>
                                                Editar
                                            </th>
                                            <th>
                                                Borrar
                                            </th>
                                        </tr>
                                    </thead>
                                    <?php foreach ($categorias['categorias'] as $categoria) { ?>
                                    <tr>
                                        <td>
                                            <?= $categoria['nombre'] ?>
                                        </td>
                                        <td>
                                            <button class="btn btn-primary editar" id="<?= $categoria['id'] ?>"><i class="fa fa-edit"></i></button>
                                        </td>
                                        <td>
                                            <button class="btn btn-danger eliminar" id="<?= $categoria['id'] ?>"><i class="fa fa-trash-o"></i></button>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </table>
                            </div>
                        </section>
                    </div>
                </section>
            </section>
        </section>
        <script>
            $(".editar").click(function ()
            {
                window.location.href = "tour-edit.php?id="+$(this).attr('id');
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
                
            $("#sumarImagen").click(function()
            {
                var cantImagenesExtra = $("#cantidadImagenesExtras").val();
                cantImagenesExtra ++;
                $("#cantidadImagenesExtras").val(cantImagenesExtra);
                $('#imagenes').append('<input type="file" accept="file_extension|image"  id="photo'+cantImagenesExtra+'" name="photo'+cantImagenesExtra+'" autofocus>');
            });
            
            $("#restarImagen").click(function()
            {
                var cantImagenesExtra = $("#cantidadImagenesExtras").val();
                
                if(cantImagenesExtra != 0)
                {
                    $('#photo'+cantImagenesExtra).remove();
                    cantImagenesExtra --;
                    $("#cantidadImagenesExtras").val(cantImagenesExtra);
                }    
            });
            
            $("#categoria").change(function(){
                $("#padre").attr("disabled", false);
                if($(this).val() == 2)
                {
                    $("#principales").show();
                }
                else
                {
                    $("#principales").hide();
                }
            });
        </script>