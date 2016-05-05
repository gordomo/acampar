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
$id = isset($_GET['id']) ? $_GET['id'] : '';

$categorias = getCategorias($mysqli, '', '', $id);
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
                    <div class="row mt form-panel" id="editor_categorias">
                        <div class="col-md-12">
                            <h4><i class="fa fa-angle-right"></i> Editar Entrada</h4>
                        </div>
                        <section>
                            <?php foreach ($categorias['categorias'] as $categoria) { ?>
                                <form id='newEntrada<?= $categoria['id'] ?>' class="form" enctype="multipart/form-data" method="POST" action="adminController.php" style="width: 32%;float:left;margin: 2px;border: 1px solid;padding: 2px;">
                                    <input type="hidden" value="editCategoria" name="action" id="action">
                                    <input type="hidden" value="0" name="cantImagenesExtras" id="cantImagenesExtras<?= $categoria['id'] ?>">
                                    <input type="hidden" value="0" name="borrarFoto" id="borrarFoto<?= $categoria['id'] ?>">
                                    <input type="hidden" value="<?= $categoria['id'] ?>" name="id" id="id">
                                    <input type="hidden" value="<?= $categoria['foto'] ?>" name="foto" id="foto<?= $categoria['id'] ?>">
                                    <div class="form-group">
                                        <label class=" col-md-12 ">Nombre: </label>
                                        <input type="text" value="<?= $categoria['nombre'] ?>" id="nombre" name="nombre" class="form-control" required="true">
                                    </div>
                                    <div class="form-group">
                                        <label class=" col-md-12 ">Imagen Principal: </label>
                                        <input type="file" accept="file_extension|image"  id="photo<?= $categoria['id'] ?>" name="photo" autofocus>
                                        <div class="col-md-12" style="margin: 15px 0px;">
                                            <img id="imgCat<?= $categoria['id'] ?>" class="img-responsive" src="../<?= $categoria['foto'] ?>" >
                                        </div>
                                        <a class="btn btn-warning borrarImagen" id="<?= $categoria['id'] ?>">Borrar Imagen</a>
                                    </div>
                                    <div class="form-group">
                                        <label class=" col-md-12 ">Imagenes Extra: </label>
                                        <?php foreach($categoria['fotos_extras'] as $fotoExtra){ ?>
                                        <div class=" col-md-12 ">
                                            <div class="col-md-12" style="margin: 15px 0px;">
                                                <img id="imgCatExtra<?= $fotoExtra['id'] ?>" class="img-responsive" src="../<?= $fotoExtra['url'] ?>" >
                                            </div>
                                            <input class="col-md-12" type="file" accept="file_extension|image"  id="photoExtra<?= $fotoExtra['id'] ?>" name="photoExtra<?= $fotoExtra['id'] ?>" autofocus>
                                            <a class="col-md-12 btn btn-warning borrarImagenExtra" data-id-form="<?=$categoria['id']?>" style="margin: 10px 0px;" id="<?= $fotoExtra['id'] ?>">Borrar</a>
                                        </div>    
                                        <?php } ?>
                                        <div class=" col-md-12 ">
                                            <a class="col-md-12 btn btn-default agregarImagenExtra" id="<?= $categoria['id'] ?>" style="margin: 10px 0px;" >Agregar otra imagen</a>
                                            <div id="imagenesExtras<?= $categoria['id'] ?>"></div>
                                        </div> 
                                        
                                    </div>
                                    <div class="form-group">
                                        <label class=" col-md-12 " style="margin-top: 15px">Descripción Corta: </label>
                                        <input type="text" value="<?=$categoria['descripcion_corta']?>" id="descCorta" name="descCorta" class="form-control" >
                                    </div>
                                    <div class="form-group">
                                        <label class=" col-md-12 ">Descripción: </label>
                                        <input type="text" value="<?=$categoria['descripcion']?>" id="desc" name="desc" class="form-control" >
                                    </div>
                                    <div class="form-group">
                                        <label class=" col-md-12 ">Coordenadas: </label>
                                        <label class=" col-md-12 " style="width: 50%; float: left">Latitud: </label>
                                        <label class=" col-md-12 " style="width: 50%; float: left">Longitud: </label>
                                        <input type="text" value="<?=$categoria['lat']?>" id="lat" name="lat" class="form-control" style="width: 50%; float: left">
                                        <input type="text" value="<?=$categoria['long']?>" id="long" name="long" class="form-control" style="width: 50%; float: left; margin-bottom: 10px">
                                    </div>
                                    <div class="form-group">
                                        <label class=" col-md-12 ">Puntos del recorrido: </label>
                                        <label class=" col-md-12 " style="width: 50%; float: left">Latitud: </label>
                                        <label class=" col-md-12 " style="width: 50%; float: left">Longitud: </label>
                                        <div class="col-md-12" id="puntosRecorridos" style="margin-top: 10px;">
                                            <?php 
                                                $i = 1;
                                                if($categoria['polynes'] != null){
                                                foreach ($categoria['polynes'] as $punto){
                                            ?>
                                                <div class="col-md-12 puntosRecorrido<?=$i?>" style="margin-bottom: 2px">
                                                    <div class="col-md-5">
                                                        <input type="text" value="<?=$punto['lat']?>" id="<?=$i?>" name="lat<?=$i?>" class="form-control lat">
                                                    </div>    
                                                    <div class="col-md-5">
                                                        <input type="text" value="<?=$punto['long']?>" id="long<?=$i?>" name="long<?=$i?>" class="form-control long">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <a href='javascript:eliminarPuntoRecorrido(<?=$i?>);' id="<?=$i?>" class="btn btn-default eliminarPuntoRecorrido"> - </a>
                                                    </div>    
                                                </div>    
                                            <?php 
                                                $i++;
                                                } }
                                            ?>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="col-md-12">
                                                <div class="col-md-10">
                                                    <a class="btn btn-default agregarPunto" style="width: 100%"> + </a>
                                                </div>    
                                            </div>    
                                        </div>
                                        <input name="idPuntosRecorridos" id="idPuntosRecorridos" type="hidden">
                                        <input name="cantPuntosRecorridos" id="cantPuntosRecorridos" value="<?=$i?>" type="hidden">
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
                                            <option value="1" <?php if(1 == $categoria['cat_superior']){echo "selected";} ?>>Aconcagua</option>
                                            <option value="2" <?php if(2 == $categoria['cat_superior']){echo "selected";} ?>>Champaquí</option>
                                            <option value="3" <?php if(3 == $categoria['cat_superior']){echo "selected";} ?>>Cumbres Argentinas</option>
                                            <option value="4" <?php if(4 == $categoria['cat_superior']){echo "selected";} ?>>Patagonia</option>
                                            <option value="5" <?php if(5 == $categoria['cat_superior']){echo "selected";} ?>>Quebrada del Condorito</option>
                                            <option value="6" <?php if(6 == $categoria['cat_superior']){echo "selected";} ?>>Sendas Incas</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-success">Editar</button>
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
                $("#borrarFoto"+id).val("1");
                $("#newEntrada"+id).submit();
            });
            
            $(".borrarImagenExtra").click(function()
            {
                var id = $(this).attr("id");
                var id_form = $(this).attr("data-id-form");
                
                $("#newEntrada"+id_form+" input[name=action]" ).val("borrarFotoExtra");
                $("#newEntrada"+id_form+" input[name=id]" ).val(id);
                
                $("#newEntrada"+id_form).submit();
                
            });
            
            $(".agregarImagenExtra").click(function()
            {
                var id = $(this).attr("id");
                var cantImgExt = $('#cantImagenesExtras'+id).val();
                cantImgExt ++;
                $('#cantImagenesExtras'+id).val(cantImgExt);
                $('#imagenesExtras'+id).append('<input type="file" accept="file_extension|image"  id="photoExtraNueva'+cantImgExt+'" name="photoExtraNueva'+cantImgExt+'" autofocus>');
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
            
            function eliminarPuntoRecorrido(id)
            {
                $('.puntosRecorrido'+id).remove();
            };
            
            $(".agregarPunto").click(function(){
                
                var cantPuntos = $("#cantPuntosRecorridos").val();
                cantPuntos ++;
                $("#cantPuntosRecorridos").val(cantPuntos);
                
                $("#puntosRecorridos").append(  "<div class='col-md-12 puntosRecorrido" + cantPuntos + "' style='margin-bottom: 2px'>" + 
                                                    "<div class='col-md-5'>" +
                                                        "<input type='text' id='"+ cantPuntos + "' name='lat"+ cantPuntos + "' class='form-control lat'> "+
                                                    "</div>" +
                                                    "<div class='col-md-5'>"+
                                                        "<input type='text' id='long"+ cantPuntos + "' name='long"+ cantPuntos + "' class='form-control long '>" +
                                                    "</div>" +
                                                    "<div class='col-md-2'>"+
                                                        "<a href='javascript:eliminarPuntoRecorrido("+cantPuntos+");' id="+ cantPuntos + " class='btn btn-default eliminarPuntoRecorrido'> - </a>" +
                                                    "</div>    "+
                                                "</div>");
                                                
                
            });
            
            $(".form").submit(function(){
                
                var puntos = [];
                
                $.each( $(".lat"), function() {
                        
                        puntos.push({lat:$(this).val().trim(),long:$('#long'+$(this).attr('id')).val().trim()});
                        
                });
                $("#idPuntosRecorridos").val(JSON.stringify(puntos));
                
                $(".form").submit();
                
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
                
                $("#categoria").change(function() 
                {
                    if($(this).val() == 2)
                    {
                        $("#padre").attr("disabled", false);
                    }
                    else
                    {
                        $("#padre").val(0);
                        $("#padre").attr("disabled", true);
                    }
                });
        </script>