<?php

include_once 'psl-config.php';

function getTours($mysqli) {
    $result = 'false';
    $tours = array();
    $error_msg = '';
    $prep_stmt = "SELECT id, nombre, class_css, id_css, descripcion FROM tours";
    $stmt = $mysqli->prepare($prep_stmt);
    if ($stmt) {
        $stmt->execute();
        $stmt->bind_result($id_tour, $nombre, $class_css, $id_css, $descripcion);
        while ($stmt->fetch()) {
            $tours[] = array('id' => $id_tour, 'nombre' => $nombre, 'class_css' => $class_css, 'id_css' => $id_css, "descripcion" => $descripcion);
        }
        $result = 'true';
    } else {
        $error_msg .= '* Error de base de datos.';
    }

    $stmt->close();

    $retorno = array('result' => $result, 'tours' => $tours, 'mensaje' => $error_msg);

    return $retorno;
}

function getYacanto($mysqli) {

    $result = "ko";
    $prep_stmt = "SELECT id, nombre, `foto`, `descripcion_corta`, `descripcion`, `id_tour`, `cat_superior`, `lat`, `long`, `polylines` FROM categorias WHERE nombre = ?";
    $error_msg = "";
    $categoria = array();

    $stmt = $mysqli->prepare($prep_stmt);
    if ($stmt) {
        $name = "Yacanto";
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($id, $nombre, $foto, $descripcion_corta, $descripcion, $id_tour, $cat_superior, $lat, $long, $polylines);
        $stmt->fetch();
        $categoria = array("id" => $id, "nombre" => $nombre, "foto" => $foto, "descripcion_corta" => $descripcion_corta, "descripcion" => $descripcion, "id_tour" => $id_tour, "cat_superior" => $cat_superior, "lat" => $lat, "long" => $long, "polynes" => json_decode($polylines, true));

        $result = 'ok';
    } else {
        $error_msg .= '* Error de base de datos.';
    }

    $stmt->close();

    if ($stmt2 = $mysqli->prepare("SELECT id, url FROM img_categorias WHERE id_categoria = ? and habilitada = 1")) {
        
        $stmt2->bind_param("i", $categoria['id']);
        if (!$stmt2->execute()) {
            $message = "Falló la ejecución: (" . $stmt->errno . ") " . $stmt->error;
            $result = "ko";
        }
    }

    $stmt2->bind_result($id_img_categoria, $url_img_categoria);

    while ($stmt2->fetch()) {
        $img_categorias[] = array("id" => $id_img_categoria, "url" => $url_img_categoria);
    }
    $categoria['fotos_extras'] = $img_categorias;

    $retorno = array('result' => $result, 'categoria' => $categoria, 'mensaje' => $error_msg);

    return $retorno;
}

function getCategorias($mysqli, $id_tour = '', $cat_superior = '', $id = '') {
    $result = 'ok';
    $categorias = array();
    $img_categorias = array();
    $error_msg = '';
    $types = array();
    $values = array();

    $prep_stmt = "SELECT id, nombre, `foto`, `descripcion_corta`, `descripcion`, `id_tour`, `cat_superior`, `lat`, `long`, `polylines` FROM categorias WHERE 1 = 1";

    if ($id_tour != '') {
        $prep_stmt .= " AND id_tour = ?";
        $types[] = "i";
        $values[] = $id_tour;
    }

    if ($cat_superior != '') {
        $prep_stmt .= " AND cat_superior = ?";
        $types[] = "i";
        $values[] = $cat_superior;
    }

    if ($id != '') {
        $prep_stmt .= " AND id = ?";
        $types[] = "i";
        $values[] = $id;
    }

    $prep_stmt .= " ORDER BY nombre";

    if ($stmt = $mysqli->prepare($prep_stmt)) {
        $bind[] = implode("", $types);
        foreach ($values as $value) {
            $bind[] = $value;
        }
        switch (count($bind)) {
            case 1:
                break;
            case 2:
                $stmt->bind_param($bind[0], $bind[1]);
                break;
            case 3:
                $stmt->bind_param($bind[0], $bind[1], $bind[2]);
                break;
        }
        if (!$stmt->execute()) {
            $message = "Falló la ejecución: (" . $stmt->errno . ") " . $stmt->error;
            $result = "ko";
        }

        $stmt->bind_result($id, $nombre, $foto, $descripcion_corta, $descripcion, $id_tour, $cat_superior, $lat, $long, $polynes);

        while ($stmt->fetch()) {
            $categorias[] = array("id" => $id, "nombre" => $nombre, "foto" => $foto, "descripcion_corta" => $descripcion_corta, "descripcion" => $descripcion, "id_tour" => $id_tour, "cat_superior" => $cat_superior, "lat" => $lat, "long" => $long, "polynes" => json_decode($polynes, true));
        }
        $stmt->close();

        for ($index = 0; $index < count($categorias); $index++) {
            if ($stmt2 = $mysqli->prepare("SELECT id, url, habilitada FROM img_categorias WHERE id_categoria = ?")) {
                $stmt2->bind_param("i", $categorias[$index]['id']);
                if (!$stmt2->execute()) {
                    $message = "Falló la ejecución: (" . $stmt->errno . ") " . $stmt->error;
                    $result = "ko";
                }
            }

            $stmt2->bind_result($id_img_categoria, $url_img_categoria, $habilitada_img_categoria);

            while ($stmt2->fetch()) {
                $img_categorias[] = array("id" => $id_img_categoria, "url" => $url_img_categoria, "habilitada" => $habilitada_img_categoria);
            }
            $categorias[$index]['fotos_extras'] = $img_categorias;
        }
    } else {
        $message = "Falló la preparacion: (" . $mysqli->errno . ") " . $mysqli->error;
        $result = "ko";
    }

    $retorno = array('result' => $result, 'categorias' => $categorias, 'mensaje' => $error_msg);

    return $retorno;
}

function getSliderCabecera($mysqli, $id_slider = false, $todas = false) {

    $result = 'ok';
    $sliders = array();
    $error_msg = '';

    $types = array();
    $values = array();

    $prep_stmt = "SELECT id, url, habilitado, titulo, descripcion, categoria_id FROM `slider_cabecera` WHERE 1 = 1";

    if ($id_slider) {
        $prep_stmt .= " AND id = ?";
        $types[] = "i";
        $values[] = $id_slider;
    }

    if (!$todas) {
        $prep_stmt .= " AND habilitado = ?";
        $types[] = "i";
        $values[] = "1";
    }

    if ($stmt = $mysqli->prepare($prep_stmt)) {
        $bind[] = implode("", $types);
        foreach ($values as $value) {
            $bind[] = $value;
        }
        switch (count($bind)) {
            case 1:
                break;
            case 2:
                $stmt->bind_param($bind[0], $bind[1]);
                break;
            case 3:
                $stmt->bind_param($bind[0], $bind[1], $bind[2]);
                break;
        }
        if (!$stmt->execute()) {
            $message = "Falló la ejecución: (" . $stmt->errno . ") " . $stmt->error;
            $result = "ko";
        }

        $stmt->bind_result($id, $url, $habilitado, $titulo, $descripcion, $categoria_id);

        while ($stmt->fetch()) {
            $sliders[] = array("id" => $id, "url" => $url, "habilitado" => $habilitado, "titulo" => $titulo, "descripcion" => $descripcion, "categoria_id" => $categoria_id);
        }

        $stmt->close();
    } else {
        $message = "Falló la preparacion: (" . $mysqli->errno . ") " . $mysqli->error;
        $result = "ko";
    }

    $retorno = array('result' => $result, 'sliders' => $sliders, 'mensaje' => $error_msg);

    return $retorno;
}

function getSliderSalidas($mysqli, $id_slider = false, $todas = false) {

    $result = 'ok';
    $sliders = array();
    $error_msg = '';

    $types = array();
    $values = array();

    $prep_stmt = "SELECT id, url, habilitado, titulo, descripcion, categoria_id FROM `slider_salidas` WHERE 1 = 1";

    if ($id_slider) {
        $prep_stmt .= " AND id = ?";
        $types[] = "i";
        $values[] = $id_slider;
    }

    if (!$todas) {
        $prep_stmt .= " AND habilitado = ?";
        $types[] = "i";
        $values[] = "1";
    }

    if ($stmt = $mysqli->prepare($prep_stmt)) {
        $bind[] = implode("", $types);
        foreach ($values as $value) {
            $bind[] = $value;
        }
        switch (count($bind)) {
            case 1:
                break;
            case 2:
                $stmt->bind_param($bind[0], $bind[1]);
                break;
            case 3:
                $stmt->bind_param($bind[0], $bind[1], $bind[2]);
                break;
        }
        if (!$stmt->execute()) {
            $message = "Falló la ejecución: (" . $stmt->errno . ") " . $stmt->error;
            $result = "ko";
        }

        $stmt->bind_result($id, $url, $habilitado, $titulo, $descripcion, $categoria_id);

        while ($stmt->fetch()) {
            $sliders[] = array("id" => $id, "url" => $url, "habilitado" => $habilitado, "titulo" => $titulo, "descripcion" => $descripcion, "categoria_id" => $categoria_id);
        }

        $stmt->close();
    } else {
        $message = "Falló la preparacion: (" . $mysqli->errno . ") " . $mysqli->error;
        $result = "ko";
    }

    $retorno = array('result' => $result, 'sliders' => $sliders, 'mensaje' => $error_msg);

    return $retorno;
}

function getNoticias($mysqli, $id = false, $todas = false, $limit = false, $id_no = false) {

    $result = 'ok';
    $noticias = array();
    $error_msg = '';

    $types = array();
    $values = array();

    $prep_stmt = "SELECT id, titulo, texto, fecha, url, habilitado, video FROM `noticias` WHERE 1 = 1";

    if ($id) {
        $prep_stmt .= " AND id = ?";
        $types[] = "i";
        $values[] = $id;
    }

    if (!$todas) {
        $prep_stmt .= " AND habilitado = ?";
        $types[] = "i";
        $values[] = "1";
    }

    if ($id_no) {
        $prep_stmt .= " AND id <> $id_no";
    }

    if ($limit) {
        $prep_stmt .= " limit " . $limit;
    }



    if ($stmt = $mysqli->prepare($prep_stmt)) {
        $bind[] = implode("", $types);
        foreach ($values as $value) {
            $bind[] = $value;
        }
        switch (count($bind)) {
            case 1:
                break;
            case 2:
                $stmt->bind_param($bind[0], $bind[1]);
                break;
            case 3:
                $stmt->bind_param($bind[0], $bind[1], $bind[2]);
                break;
        }
        if (!$stmt->execute()) {
            $message = "Falló la ejecución: (" . $stmt->errno . ") " . $stmt->error;
            $result = "ko";
        }

        $stmt->bind_result($id, $titulo, $texto, $fecha, $url, $habilitado, $video);

        while ($stmt->fetch()) {
            $noticias[] = array("id" => $id, "titulo" => $titulo, "texto" => $texto, "fecha" => $fecha, "url" => $url, "habilitado" => $habilitado, "video" => $video);
        }

        $stmt->close();
    } else {
        $message = "Falló la preparacion: (" . $mysqli->errno . ") " . $mysqli->error;
        $result = "ko";
    }

    $retorno = array('result' => $result, 'noticias' => $noticias, 'mensaje' => $error_msg);

    return $retorno;
}

function getInfoCategoria($mysqli, $id_categoria) {
    $result = 'false';
    $error_msg = '';
    $categoria = array();

    $prep_stmt = "SELECT id, nombre, `foto`, `descripcion_corta`, `descripcion`, `id_tour`, `cat_superior`, `lat`, `long` FROM categorias WHERE id=?";
    $stmt = $mysqli->prepare($prep_stmt);
    if ($stmt) {
        $stmt->bind_param("i", $id_categoria);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($id, $nombre, $foto, $descripcion_corta, $descripcion, $id_tour, $cat_superior, $lat, $long);
        $stmt->fetch();
        $categoria = array("id" => $id, "nombre" => $nombre, "foto" => $foto, "descripcion_corta" => $descripcion_corta, "descripcion" => $descripcion, "id_tour" => $id_tour, "cat_superior" => $cat_superior, "lat" => $lat, "long" => $long);

        $result = 'true';
    } else {
        $error_msg .= '* Error de base de datos.';
    }

    $stmt->close();

    $retorno = array('result' => $result, 'categoria' => $categoria, 'mensaje' => $error_msg);

    return $retorno;
}

function getEstadosUsers($mysqli) {
    $error_msg = '';
    $estados = array();

    $prep_stmt = "SELECT id, estado FROM estado_usuarios";
    $stmt = $mysqli->prepare($prep_stmt);
    if ($stmt) {
        $stmt->execute();
        $stmt->bind_result($id_estado, $estado);
        while ($stmt->fetch()) {
            $estados[] = array($id_estado, $estado);
        }
        $result = 'true';
    } else {
        $result = 'false';
        $error_msg .= '* Error de base de datos.<br/>';
    }

    $stmt->close();

    $retorno = array('result' => $result, 'estados' => $estados, 'mensaje' => $error_msg);

    return $retorno;
}

function getEstadoUser($mysqli, $id_user) {
    $error_msg = '';
    $result = '';

    $prep_stmt = "SELECT estado FROM usuarios WHERE id = ? LIMIT 1";
    $stmt = $mysqli->prepare($prep_stmt);
    if ($stmt) {
        $stmt->bind_param('s', $id_user);
        $stmt->execute();
        $stmt->bind_result($estado);
        $result = 'true';
    } else {
        $result = 'false';
        $error_msg .= 'Error de base de datos';
    }

    $stmt->close();

    $retorno = array('result' => $result, 'estado' => $estado, 'mensaje' => $error_msg);

    return $retorno;
}

function setSlider($mysqli, $url, $url_thumb, $texto, $link, $id_sitio = 0, $id_slider = '') {
    $result = 'false';

    if (isset($id_slider) && $id_slider != '') {
        $query_upd = "UPDATE sliders SET url=?, url_thumb=?, texto=?, link=?, id_sitio=? WHERE id=?";
        $stmt_upd = $mysqli->prepare($query_upd);
        if ($stmt_upd) {
            $stmt_upd->bind_param('sssssd', $url, $url_thumb, $texto, $link, $id_sitio, $id_slider);
            if (!$stmt_upd->execute()) {
                $error_msg .= 'El slider no pudo ser modificado';
            } else {
                $result = 'true';
                $error_msg .= 'Slider modificado con exito';
            }
        } else {
            $error_msg .= 'Error de base de datos';
        }
        $stmt_upd->close();
    } else {
        if ($insert_stmt = $mysqli->prepare("INSERT INTO sliders (url, url_thumb, texto, link, id_sitio) VALUES (?, ?, ?, ?, ?)")) {
            $insert_stmt->bind_param('sssss', $url, $url_thumb, $texto, $link, $id_sitio);
            // Execute the prepared query.
            if (!$insert_stmt->execute()) {
                $error_msg .= 'El slider no pudo ser agregado.' . $insert_stmt->error;
            } else {
                $result = 'true';
                $error_msg .= 'Slider agregado con exito!!';
            }
        } else {
            $error_msg .= '* Hubo un error al intentar agregar el slider';
        }
    }

    $retorno = array('result' => $result, 'mensaje' => $error_msg);

    return $retorno;
}

function getSliders($mysqli, $id_slider = '') {
    $error_msg = '';
    if (isset($id_slider) && $id_slider != '') {
        $prep_stmt = "SELECT url, url_thumb, texto, link FROM sliders WHERE id = ?";
        $stmt = $mysqli->prepare($prep_stmt);
        if ($stmt) {
            $stmt->bind_param("i", $id_slider);
            $stmt->execute();
            $stmt->bind_result($url, $url_thumb, $texto, $link);
            $stmt->fetch();
            $slider = array($url, $url_thumb, $texto, $link);

            $result = 'true';
        } else {
            $result = 'false';
            $error_msg .= '* Error de base de datos.<br/>';
        }

        $stmt->close();
    } else {
        $slider = array();

        $prep_stmt = "SELECT * FROM sliders";
        $stmt = $mysqli->prepare($prep_stmt);
        if ($stmt) {
            $stmt->execute();
            $stmt->bind_result($id, $url, $url_thumb, $texto, $link, $id_sitio);
            while ($stmt->fetch()) {
                $slider[] = array($id, $url, $url_thumb, $texto, $link, $id_sitio);
            }
            $result = 'true';
        } else {
            $result = 'false';
            $error_msg .= '* Error de base de datos.<br/>';
        }

        $stmt->close();
    }

    $retorno = array('result' => $result, 'slider' => $slider, 'mensaje' => $error_msg);

    return $retorno;
}

function removeSlider($mysqli, $id_slider) {
    $result = 'false';
    $id_slider = filter_var($id_slider, FILTER_VALIDATE_INT);
    if (!filter_var($id_slider, FILTER_VALIDATE_INT)) {
        $error_msg .= '* El slider no es válido o no existe';
    } else {
        $stmt = $mysqli->prepare("SELECT url, url_thumb FROM sliders WHERE id = ? LIMIT 1");
        if ($stmt) {
            $stmt->bind_param('i', $id_slider);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($url, $url_thumb);
            $stmt->fetch();

            if ($stmt->num_rows >= 1) {
                $stmt_del = $mysqli->prepare("DELETE FROM sliders WHERE id=?");
                if ($stmt_del) {
                    $stmt_del->bind_param('i', $id_slider);
                    if (!$stmt_del->execute()) {
                        $error_msg .= '* No se pudo eliminar el slider';
                    } else {
                        if (file_exists("../" . $url)) {
                            unlink("../" . $url);
                        }
                        if (file_exists("../" . $url_thumb)) {
                            unlink("../" . $url_thumb);
                        }
                        $result = 'true';
                    }
                }
            } else {
                $error_msg .= '* El slider no existe';
            }
        } else {
            $error_msg .= '* No se pudo seleccionar el slider para ser eliminado';
        }
    }

    $retorno = array('result' => $result, 'mensaje' => $error_msg);

    return $retorno;
}

function getNovedades($mysqli, $id_nov = '') {
    $result = 'false';
    $error_msg = '';
    if (isset($id_nov) && $id_nov != '') {
        $prep_stmt = "SELECT imagen, imagen2, imagen3, titulo, descripcion, fecha, link FROM novedades WHERE id = ?";
        $stmt = $mysqli->prepare($prep_stmt);
        if ($stmt) {
            $stmt->bind_param("i", $id_nov);
            $stmt->execute();
            $stmt->bind_result($imagen, $imagen2, $imagen3, $titulo, $descripcion, $fecha, $link);
            $stmt->fetch();
            $novedades = array('imagen' => $imagen, 'imagen2' => $imagen2, 'imagen3' => $imagen3, 'titulo' => $titulo, 'descripcion' => $descripcion, 'fecha' => $fecha, 'link' => $link);

            $result = 'true';
        } else {
            $error_msg .= '* Error de base de datos.<br/>';
        }

        $stmt->close();
    } else {
        $novedades = array();

        $prep_stmt = "SELECT id, imagen, imagen2, imagen3, titulo, descripcion, fecha, link, estado, autor, categoria FROM novedades";
        $stmt = $mysqli->prepare($prep_stmt);
        if ($stmt) {
            $stmt->execute();
            $stmt->bind_result($id, $imagen, $imagen2, $imagen3, $titulo, $descripcion, $fecha, $link, $estado, $autor, $categoria);
            while ($stmt->fetch()) {
                $novedades[] = array('id' => $id, 'imagen' => $imagen, 'imagen2' => $imagen2, 'imagen3' => $imagen3, 'titulo' => $titulo, 'descripcion' => $descripcion, 'fecha' => $fecha, 'link' => $link, 'estado' => $estado, 'autor' => $autor, 'categoria' => $categoria);
            }
            $result = 'true';
        } else {
            $error_msg .= '* Error de base de datos.<br/>';
        }

        $stmt->close();
    }

    return $novedades;
}

function removeNovedad($mysqli, $id_novedad) {
    $result = 'false';
    $id_novedad = filter_var($id_novedad, FILTER_VALIDATE_INT);
    if (!filter_var($id_novedad, FILTER_VALIDATE_INT)) {
        $error_msg .= '* La novedad no es válida o no existe';
    } else {
        $stmt = $mysqli->prepare("SELECT imagen, imagen2, imagen3 FROM novedades WHERE id = ? LIMIT 1");
        if ($stmt) {
            $stmt->bind_param('i', $id_novedad);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($imagen, $imagen2, $imagen3);
            $stmt->fetch();

            if ($stmt->num_rows >= 1) {
                $stmt_del = $mysqli->prepare("DELETE FROM novedades WHERE id=?");
                if ($stmt_del) {
                    $stmt_del->bind_param('i', $id_novedad);
                    if (!$stmt_del->execute()) {
                        $error_msg .= '* No se pudo eliminar la novedad';
                    } else {
                        if (file_exists("../images/novedades/" . $imagen)) {
                            unlink("../images/novedades/" . $imagen);
                        }
                        if (file_exists("../images/novedades/" . $imagen2)) {
                            unlink("../images/novedades/" . $imagen2);
                        }
                        if (file_exists("../images/novedades/" . $imagen3)) {
                            unlink("../images/novedades/" . $imagen3);
                        }
                        $result = 'true';
                    }
                }
            } else {
                $error_msg .= '* La novedad no existe';
            }
        } else {
            $error_msg .= '* No se pudo seleccionar la novedad para ser eliminada';
        }
    }

    $retorno = array('result' => $result, 'mensaje' => $error_msg);

    return $retorno;
}

function setSitio($mysqli, $id_usuario, $default = '', $nombre = '', $img_resumen = '', $descripcion = '', $email = '', $direccion = '', $latitud = '', $longitud = '', $telefono = '', $id_estado = 1, $categorias = '', $id_complejo = '', $landing = 0, $id_sitio = '') {
    $result = 'false';

    if (isset($id_sitio) && $id_sitio != '') {
        $query_upd = "UPDATE sliders SET url=?, url_thumb=?, texto=?, link=?, id_sitio=? WHERE id=?";
        $stmt_upd = $mysqli->prepare($query_upd);
        if ($stmt_upd) {
            $stmt_upd->bind_param('sssssd', $url, $url_thumb, $texto, $link, $id_sitio, $id_slider);
            if (!$stmt_upd->execute()) {
                $error_msg .= '* El slider no pudo ser modificado.<br/>';
            } else {
                $result = 'true';
                $error_msg .= 'Slider modificado con exito';
            }
        } else {
            $error_msg .= '* Error de base de datos - 274';
        }
        $stmt_upd->close();
    } else {
        if (isset($default) && $default != '') {
            $nombre = 'Nuevo Sitio';
            $img_resumen = '../sitos/default/default.jpg';
            $descripcion = 'Nuevo sitio web, por favor, modifique el siguiente contenido.';
            $email = '';
            $fecha_alta = date("Y-m-d");
            $id_estado = 1;
            $id_usuario = $id_usuario;
            $landing = $landing;
            $direccion = $latitud = $longitud = $telefono = $fecha_modificacion = $fecha_baja = $categorias = $id_complejo = '';
        } else {
            
        }
        if ($insert_stmt = $mysqli->prepare("INSERT INTO sitios SET nombre=?,img_resumen=?,descripcion=?,direccion=?,latitud=?,longitud=?,email=?,telefono=?,fecha_alta=?,fecha_modificacion=?,fecha_baja=?,id_estado=?,id_usuario=?,categorias=?,id_complejo=?,landing=?")) {
            $insert_stmt->bind_param('ssssssssssssssss', $nombre, $img_resumen, $descripcion, $direccion, $latitud, $longitud, $email, $telefono, $fecha_alta, $fecha_modificacion, $fecha_baja, $id_estado, $id_usuario, $categorias, $id_complejo, $landing);
            // Execute the prepared query.
            if (!$insert_stmt->execute()) {
                $error_msg .= 'El sitio no pudo ser creado.' . $insert_stmt->error;
            } else {
                $result = 'true';
                $error_msg .= 'Sitio creado con exito!!';
            }
        } else {
            $error_msg .= 'Hubo un error al intentar crear el sitio';
        }
    }

    $retorno = array('result' => $result, 'mensaje' => $error_msg);

    return $retorno;
}

function getSitios($mysqli, $id_sitio = '', $id_categoria = '') {
    $sitios = array();
    $result = 'false';
    $error_msg = '';
    if (isset($id_sitio) && $id_sitio != '') {
        $prep_stmt = "SELECT nombre, img_resumen, descripcion, direccion, latitud, longitud, email, telefono, fecha_alta, fecha_modificacion, fecha_baja, id_estado, id_usuario, categorias, id_complejo, landing FROM sitios WHERE id = ?";
        $stmt = $mysqli->prepare($prep_stmt);
        if ($stmt) {
            $stmt->bind_param("i", $id_sitio);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($nombre, $img_resumen, $descripcion, $direccion, $latitud, $longitud, $email, $telefono, $fecha_alta, $fecha_modificacion, $fecha_baja, $id_estado, $id_usuario, $categorias, $id_complejo, $landing);
            $stmt->fetch();

            $usuario = getUsers($mysqli, $id_usuario);

            $sitios = array('nombre' => $nombre, 'img_resumen' => $img_resumen, 'descripcion' => $descripcion, 'direccion' => $direccion, 'latitud' => $latitud, 'longitud' => $longitud, 'email' => $email, 'telefono' => $telefono, 'fecha_alta' => $fecha_alta, 'fecha_modificacion' => $fecha_modificacion, 'fecha_baja' => $fecha_baja, 'id_estado' => $id_estado, 'id_usuario' => $id_usuario, 'usuario' => $usuario[0]['usuario'], 'categorias' => $categorias, 'id_complejo' => $id_complejo, 'landing' => $landing);

            $result = 'true';

            $stmt->close();
        } else {
            $error_msg .= '* Error de base de datos.<br/>';
        }
    } elseif (isset($id_categoria) && $id_categoria != '') {
        $prep_stmt = "SELECT nombre, img_resumen, descripcion, direccion, latitud, longitud, email, telefono, fecha_alta, fecha_modificacion, fecha_baja, id_estado, id_usuario, categorias, id_complejo, landing FROM sitios WHERE categorias like '%{$id_categoria}%'";
        $stmt = $mysqli->prepare($prep_stmt);
        if ($stmt) {
            //$stmt->bind_param("i", $id_sitio);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($nombre, $img_resumen, $descripcion, $direccion, $latitud, $longitud, $email, $telefono, $fecha_alta, $fecha_modificacion, $fecha_baja, $id_estado, $id_usuario, $categorias, $id_complejo, $landing);
            $stmt->fetch();

            $usuario = getUsers($mysqli, $id_usuario);

            $sitios = array('nombre' => $nombre, 'img_resumen' => $img_resumen, 'descripcion' => $descripcion, 'direccion' => $direccion, 'latitud' => $latitud, 'longitud' => $longitud, 'email' => $email, 'telefono' => $telefono, 'fecha_alta' => $fecha_alta, 'fecha_modificacion' => $fecha_modificacion, 'fecha_baja' => $fecha_baja, 'id_estado' => $id_estado, 'id_usuario' => $id_usuario, 'usuario' => $usuario[0]['usuario'], 'categorias' => $categorias, 'id_complejo' => $id_complejo, 'landing' => $landing);

            $result = 'true';

            $stmt->close();
        } else {
            $error_msg .= 'Error de base de datos.';
        }
    } else {
        $prep_stmt = "SELECT id, nombre, img_resumen, descripcion, direccion, latitud, longitud, email, telefono, fecha_alta, fecha_modificacion, fecha_baja, id_estado, id_usuario, categorias, id_complejo, landing FROM sitios WHERE id_estado<>3";
        $stmt = $mysqli->prepare($prep_stmt);
        if ($stmt) {
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id, $nombre, $img_resumen, $descripcion, $direccion, $latitud, $longitud, $email, $telefono, $fecha_alta, $fecha_modificacion, $fecha_baja, $id_estado, $id_usuario, $categorias, $id_complejo, $landing);
            while ($stmt->fetch()) {
                $usuario = getUsers($mysqli, $id_usuario);
                $estado = getEstadosSitios($mysqli, $id_estado);
                $sitios[] = array('id' => $id, 'nombre' => $nombre, 'img_resumen' => $img_resumen, 'descripcion' => $descripcion, 'direccion' => $direccion, 'latitud' => $latitud, 'longitud' => $longitud, 'email' => $email, 'telefono' => $telefono, 'fecha_alta' => $fecha_alta, 'fecha_modificacion' => $fecha_modificacion, 'fecha_baja' => $fecha_baja, 'id_estado' => $id_estado, 'estado' => $estado['estados'], 'id_usuario' => $id_usuario, 'usuario' => $usuario[0]['usuario'], 'categorias' => $categorias, 'id_complejo' => $id_complejo, 'landing' => $landing);
            }
            $result = 'true';

            $stmt->close();
        } else {
            $error_msg .= '* Error de base de datos.<br/>';
        }
    }

    return $sitios;
}

function getSitiosUsuario($mysqli, $id_usuario) {
    $sitios = array();
    $result = 'false';
    $error_msg = '';

    if (isset($id_usuario) && $id_usuario != '') {
        $prep_stmt = "SELECT id, nombre, img_resumen, descripcion, direccion, latitud, longitud, email, telefono, fecha_alta, fecha_modificacion, fecha_baja, id_estado, categorias, id_complejo, landing FROM sitios WHERE id_usuario = ?";
        $stmt = $mysqli->prepare($prep_stmt);
        if ($stmt) {
            $stmt->bind_param("i", $id_usuario);
            if ($stmt->execute()) {
                $stmt->store_result();
                $stmt->bind_result($id, $nombre, $img_resumen, $descripcion, $direccion, $latitud, $longitud, $email, $telefono, $fecha_alta, $fecha_modificacion, $fecha_baja, $id_estado, $categorias, $id_complejo, $landing);
                $stmt->fetch();

                $estado_sitio = getEstadosSitios($mysqli, $id_estado);

                $sitios[] = array('id' => $id, 'nombre' => $nombre, 'img_resumen' => $img_resumen, 'descripcion' => $descripcion, 'direccion' => $direccion, 'latitud' => $latitud, 'longitud' => $longitud, 'email' => $email, 'telefono' => $telefono, 'fecha_alta' => $fecha_alta, 'fecha_modificacion' => $fecha_modificacion, 'fecha_baja' => $fecha_baja, 'id_estado' => $id_estado, 'estado' => $estado_sitio['estados'], 'categorias' => $categorias, 'id_complejo' => $id_complejo, 'landing' => $landing);

                $result = 'true';

                $stmt->close();
            } else {
                $error_msg .= 'No se pudo seleccionar el usuario -' . $stmt->error;
            }
        } else {
            $error_msg .= 'Error de base de datos';
        }
    } else {
        $error_msg .= 'No se pudo obtener el listado de sitios del cliente';
    }

    $retorno = array('result' => $result, 'sitios' => $sitios, 'mensaje' => $error_msg);

    return $retorno;
}

function removeSitiosUsuario($mysqli, $id_usuario) {
    $result = 'false';
    $id_usuario = filter_var($id_usuario, FILTER_VALIDATE_INT);
    if (!filter_var($id_usuario, FILTER_VALIDATE_INT)) {
        $error_msg .= 'El cliente ingresado no es válido o no existe';
    } else {
        $stmt = $mysqli->prepare("SELECT id FROM sitios WHERE id_usuario = ?");
        if ($stmt) {
            $stmt->bind_param('i', $id_usuario);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id);
            while ($stmt->fetch()) {
                $fecha_baja = date("Y-m-d");
                $stmt_del = $mysqli->prepare("UPDATE sitios SET id_estado=3, fecha_baja='{$fecha_baja}' WHERE id=?");
                if (!$stmt_del) {
                    $error_msg .= $mysqli->error;
                } else {
                    $stmt_del->bind_param('i', $id);
                    if (!$stmt_del->execute()) {
                        $error_msg .= 'No se pudo eliminar el sitio';
                    } else {
                        $result = 'true';
                        $stmt_del->close();
                    }
                }
            }
        } else {
            $error_msg .= 'No se pudo seleccionar el usuario para eliminar sus sitios';
        }
        $stmt->close();
    }

    $retorno = array('result' => $result, 'mensaje' => $error_msg);

    return $retorno;
}

function getEstadosSitios($mysqli, $id_estado = '') {
    $error_msg = '';
    $estados = array();

    if (isset($id_estado) && $id_estado != '') {
        $prep_stmt = "SELECT estado FROM estado_sitios WHERE id=?";
        $stmt = $mysqli->prepare($prep_stmt);
        if ($stmt) {
            $stmt->bind_param("i", $id_estado);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($estado);
            $stmt->fetch();
            $estados = $estado;
            $result = 'true';
        } else {
            $result = 'false';
            $error_msg .= 'Error de base de datos';
        }
        $stmt->close();
    } else {
        $prep_stmt = "SELECT id, estado FROM estado_sitios";
        $stmt = $mysqli->prepare($prep_stmt);
        if ($stmt) {
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id_estado, $estado);
            while ($stmt->fetch()) {
                $estados[] = array('id' => $id_estado, 'estado' => $estado);
            }
            $result = 'true';
        } else {
            $result = 'false';
            $error_msg .= 'Error de base de datos';
        }
        $stmt->close();
    }

    $retorno = array('result' => $result, 'estados' => $estados, 'mensaje' => $error_msg);
    return $retorno;
}

function editarSitioAdm($mysqli, $id_sitio, $categoria, $id_complejo, $id_estado, $eliminar = '') {
    $result = 'false';
    $error_msg = '';

    if (isset($eliminar) && $eliminar != '') {
        $fecha_baja = date("Y-m-d");
        $query_upd = "UPDATE sitios SET id_estado=3, fecha_baja='{$fecha_baja}' WHERE id=?";
        $stmt_upd = $mysqli->prepare($query_upd);
        if ($stmt_upd) {
            $stmt_upd->bind_param('d', $id_sitio);
            if (!$stmt_upd->execute()) {
                $error_msg .= 'El sitio no pudo ser modificado';
            } else {
                $result = 'true';
                $error_msg .= 'Sitio eliminado';
            }
        } else {
            $error_msg .= '* Error de base de datos - 786';
        }
        $stmt_upd->close();
    } else {
        $categorias = json_encode($categoria);
        if (isset($id_sitio) && $id_sitio != '') {
            $query_upd = "UPDATE sitios SET categorias=?, id_complejo=?, id_estado=? WHERE id=?";
            $stmt_upd = $mysqli->prepare($query_upd);
            if ($stmt_upd) {
                $stmt_upd->bind_param('sssd', $categorias, $id_complejo, $id_estado, $id_sitio);
                if (!$stmt_upd->execute()) {
                    $error_msg .= 'El sitio no pudo ser modificado';
                } else {
                    $result = 'true';
                    $error_msg .= 'Sitio modificado con exito';
                }
            } else {
                $error_msg .= 'Error de base de datos';
            }
            $stmt_upd->close();
        } else {
            $error_msg = 'Error al seleccionar el sitio';
        }
    }

    $retorno = array('result' => $result, 'mensaje' => $error_msg);

    return $retorno;
}

function getComplejos($mysqli, $id_complejo = '') {
    $error_msg = '';
    $complejos = array();

    if (isset($id_complejo) && $id_complejo != '') {
        $prep_stmt = "SELECT complejo FROM complejos WHERE id=?";
        $stmt = $mysqli->prepare($prep_stmt);
        if ($stmt) {
            $stmt->bind_param("i", $id_complejo);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($complejo);
            $stmt->fetch();
            $complejos = $complejo;
            $result = 'true';
        } else {
            $result = 'false';
            $error_msg .= 'Error de base de datos';
        }
        $stmt->close();
    } else {
        $stmt = $mysqli->prepare("SELECT id, complejo FROM complejos");
        if ($stmt) {
            $stmt->execute();
            $stmt->bind_result($id, $complejo);
            while ($stmt->fetch()) {
                $complejos[] = array('id' => $id, 'complejo' => $complejo);
            }
            $result = 'true';
        } else {
            $result = 'false';
            $error_msg .= 'Error de base de datos';
        }
        $stmt->close();
    }

    $retorno = array('result' => $result, 'complejos' => $complejos, 'mensaje' => $error_msg);

    return $retorno;
}

function sec_session_start() {
    $session_name = 'sec_session_id';   // Set a custom session name 
    $secure = SECURE;

    // This stops JavaScript being able to access the session id.
    $httponly = true;

    // Forces sessions to only use cookies.
    if (ini_set('session.use_only_cookies', 1) === FALSE) {
        header("Location: ../admin/error.php?err=Could not initiate a safe session (ini_set)");
        exit();
    }

    // Gets current cookies params.
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly);

    // Sets the session name to the one set above.
    session_name($session_name);

    session_start();            // Start the PHP session 
    session_regenerate_id();    // regenerated the session, delete the old one. 
}

function login($email, $password, $mysqli) {
    // Using prepared statements means that SQL injection is not possible. 
    if ($stmt = $mysqli->prepare("SELECT id, usuario, password, salt, id_tipo 
				  FROM usuarios 
                                  WHERE email = ? LIMIT 1")) {
        $stmt->bind_param('s', $email);  // Bind "$email" to parameter.
        $stmt->execute();    // Execute the prepared query.
        $stmt->store_result();

        // get variables from result.
        $stmt->bind_result($user_id, $username, $db_password, $salt, $tipo);
        $stmt->fetch();

        // hash the password with the unique salt.
        $password = hash('sha512', $password . $salt);
        if ($stmt->num_rows == 1) {
            // If the user exists we check if the account is locked
            // from too many login attempts 
            if (checkbrute($user_id, $mysqli) == true) {
                // Account is locked 
                // Send an email to user saying their account is locked 
                return false;
            } else {
                // Check if the password in the database matches 
                // the password the user submitted.
                if ($db_password == $password) {
                    // Password is correct!
                    // Get the user-agent string of the user.
                    $user_browser = $_SERVER['HTTP_USER_AGENT'];

                    // XSS protection as we might print this value
                    $user_id = preg_replace("/[^0-9]+/", "", $user_id);
                    $_SESSION['user_id'] = $user_id;

                    // XSS protection as we might print this value
                    $username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $username);

                    $_SESSION['username'] = $username;
                    $_SESSION['login_string'] = hash('sha512', $password . $user_browser);

                    $_SESSION['tipo'] = $tipo;

                    // Login successful. 
                    return true;
                } else {
                    // Password is not correct 
                    // We record this attempt in the database 
                    $now = time();
                    if (!$mysqli->query("INSERT INTO login_attempts(user_id, time) 
                                    VALUES ('$user_id', '$now')")) {
                        header("Location: ../admin/error.php?err=Database error: login_attempts");
                        exit();
                    }

                    return false;
                }
            }
        } else {
            // No user exists. 
            return false;
        }
    } else {
        // Could not create a prepared statement
        header("Location: ../admin/error.php?err=Database error: cannot prepare statement");
        exit();
    }
}

function checkbrute($user_id, $mysqli) {
    // Get timestamp of current time 
    $now = time();

    // All login attempts are counted from the past 2 hours. 
    $valid_attempts = $now - (2 * 60 * 60);

    if ($stmt = $mysqli->prepare("SELECT time 
                                  FROM login_attempts 
                                  WHERE user_id = ? AND time > '$valid_attempts'")) {
        $stmt->bind_param('i', $user_id);

        // Execute the prepared query. 
        $stmt->execute();
        $stmt->store_result();

        // If there have been more than 5 failed logins 
        if ($stmt->num_rows > 5) {
            return true;
        } else {
            return false;
        }
    } else {
        // Could not create a prepared statement
        header("Location: ../admin/error.php?err=Database error: cannot prepare statement");
        exit();
    }
}

function login_check() {
    return isset($_SESSION['user_login_checked']) ? $_SESSION['user_login_checked'] : false;
}

function esc_url($url) {

    if ('' == $url) {
        return $url;
    }

    $url = preg_replace('|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\\x80-\\xff]|i', '', $url);

    $strip = array('%0d', '%0a', '%0D', '%0A');
    $url = (string) $url;

    $count = 1;
    while ($count) {
        $url = str_replace($strip, '', $url, $count);
    }

    $url = str_replace(';//', '://', $url);

    $url = htmlentities($url);

    $url = str_replace('&amp;', '&#038;', $url);
    $url = str_replace("'", '&#039;', $url);

    if ($url[0] !== '/') {
        // We're only interested in relative links from $_SERVER['PHP_SELF']
        return '';
    } else {
        return $url;
    }
}

function getCalendario($mysqli, $mes = false) {
    if ($mes) {
        if ($stmt = $mysqli->prepare("SELECT * FROM calendario WHERE mes=?")) {
            /* ligar parámetros para marcadores */
            $stmt->bind_param("i", $mes);

            /* ejecutar la consulta */
            if (!$stmt->execute()) {
                $message = "Falló la ejecución: (" . $stmt->errno . ") " . $stmt->error;
                $result = "ko";

                return json_encode(array("result" => $result, "mensaje" => $message, "respuesta" => ""));
            }

            /* ligar variables de resultado */
            $resultado = $stmt->get_result();
            $calendario = $resultado->fetch_assoc();
            /* obtener valor */
            $stmt->fetch();
            /* cerrar sentencia */
            $stmt->close();

            return $calendario;
        }
    } else {
        if ($stmt = $mysqli->prepare("SELECT * FROM calendario")) {
            $calendarios = array();
            /* ejecutar la consulta */
            if (!$stmt->execute()) {
                $message = "Falló la ejecución: (" . $stmt->errno . ") " . $stmt->error;
                $result = "ko";

                return json_encode(array("result" => $result, "mensaje" => $message, "respuesta" => ""));
            }

            /* ligar variables de resultado */
            $stmt->execute();
            $stmt->bind_result($id, $mes, $ano, $id_excursiones, $dias);
            while ($stmt->fetch()) {
                $calendarios[] = array("id" => $id, "mes" => $mes, "ano" => $ano, "id_excursiones" => json_decode($id_excursiones), "dias" => $dias);
            }
            /* obtener valor */
            $stmt->fetch();
            /* cerrar sentencia */
            $stmt->close();

            return $calendarios;
        }
    }
}

function getToursFromCategorias($mysqli, $id = false) {
    if ($id) {
        if ($stmt = $mysqli->prepare("SELECT * FROM categorias WHERE id=?")) {
            /* ligar parámetros para marcadores */
            $stmt->bind_param("i", $id);

            /* ejecutar la consulta */
            if (!$stmt->execute()) {
                $message = "Falló la ejecución: (" . $stmt->errno . ") " . $stmt->error;
                $result = "ko";

                return json_encode(array("result" => $result, "mensaje" => $message, "respuesta" => ""));
            }

            /* ligar variables de resultado */
            $resultado = $stmt->get_result();
            $categorias = $resultado->fetch_assoc();
            /* obtener valor */
            $stmt->fetch();
            /* cerrar sentencia */
            $stmt->close();

            return $categorias;
        }
    } else {
        if ($stmt = $mysqli->prepare("SELECT * FROM categorias")) {
            $categorias = array();
            /* ejecutar la consulta */
            if (!$stmt->execute()) {
                $message = "Falló la ejecución: (" . $stmt->errno . ") " . $stmt->error;
                $result = "ko";

                return json_encode(array("result" => $result, "mensaje" => $message, "respuesta" => ""));
            }

            /* ligar variables de resultado */
            $stmt->execute();
            $stmt->bind_result($id, $nombre, $foto, $descripcion_corta, $descripcion, $coordenadas, $id_tour, $cat_superior);
            while ($stmt->fetch()) {
                $categorias[] = array("id" => $id, "nombre" => $nombre, "foto" => $foto, "descripcion_corta" => $descripcion_corta, "descripcion" => $descripcion, "coordenadas" => $coordenadas, "id_tour" => $id_tour, "cat_superior" => $cat_superior);
            }
            /* obtener valor */
            $stmt->fetch();
            /* cerrar sentencia */
            $stmt->close();

            return $categorias;
        }
    }
}

function getSeguimientoSatelital($mysqli) {
    $result = "ok";
    $message = 'ok';
    if ($stmt = $mysqli->prepare("SELECT texto FROM seguimiento")) {
        if (!$stmt->execute()) {
            $message = "Falló la ejecución: (" . $stmt->errno . ") " . $stmt->error;
            $result = "ko";

            return array("result" => $result, "mensaje" => $message, "respuesta" => "");
        }

        $stmt->execute();
        $stmt->bind_result($texto);
        $stmt->fetch();
        return array("result" => $result, "mensaje" => $message, "texto" => $texto);
    }
}
