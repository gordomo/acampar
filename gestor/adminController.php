<?php
header("Content-Type: text/html;charset=utf-8");
include_once '../includes/db_connect.php';
include_once '../includes/resizeImage.php';

$action = isset($_POST['action']) ? $_POST['action'] : $_GET['action'];

switch ($action) {
//    ##############CALENDARIO INICIO########################
    case 'newCalendario':

        $result = "ok";
        $message = "Entrada agregada correctamente";
        $mes = $_POST["mes"];
        $ano = $_POST["ano"];
        $dias = $_POST["dias"];
        $excursiones = json_encode($_POST['items'], JSON_UNESCAPED_UNICODE);

        // prepare and bind
        if ($stmt = $mysqli->prepare("INSERT INTO calendario (`mes`, `ano`, `id_excursiones`, `dias`) values (?, ?, ?, ?)")) 
        {
            $stmt->bind_param("iiss", $mes, $ano, $excursiones, $dias);

            if (!$stmt->execute()) {
                $message = "Falló la ejecución: (" . $stmt->errno . ") " . $stmt->error;
                $result = "ko";
            }

            $stmt->close();
        } 
        else
        {
            $message = "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
            $result = "ko";
        }

        header("Location: calendario-edit.php?result=" . $result . "&mensaje=" . $message);
        break;


    case 'eliminarEntradaCalendario':

        $result = "ok";
        $message = "Imagen agregada correctamente";
        $id = $_POST['id'];
 
        if ($stmt = $mysqli->prepare("DELETE FROM calendario WHERE id = ?")) 
        {
            $stmt->bind_param("s", $id);

            if (!$stmt->execute())
            {
                $message = "Falló la ejecución: (" . $stmt->errno . ") " . $stmt->error;
                $result = "ko";
            }

            $stmt->close();
        }
        else
        {
            $message = "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
            $result = "ko";
        }
        
        echo json_encode(array("result"=>$result, "mensaje"=>$message));
        
        break;

    case "editCalendario":

        $result = "ok";
        $message = "Imagen borrada correctamente";
        
        $id = $_POST['id_entrada'];
        $mes = $_POST["mes"];
        $ano = $_POST["ano"];
        $dias = $_POST["dias"];
        $excursiones = json_encode($_POST['items'], JSON_UNESCAPED_UNICODE);

        if ($stmt = $mysqli->prepare("UPDATE calendario SET mes = ?, ano = ?, id_excursiones = ?, dias = ? WHERE id=?")) 
        {
            /* ligar parámetros para marcadores */
            $stmt->bind_param("iissi", $mes, $ano, $excursiones, $dias, $id);

            /* ejecutar la consulta */
            if (!$stmt->execute()) {
                $message = "Falló la ejecución: (" . $stmt->errno . ") " . $stmt->error;
                $result = "ko";
            }

            /* cerrar sentencia */
            $stmt->close();

        }
        else
        {
            $message = "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
            $result = "ko";
        }

        header("Location: calendario-edit.php?result=" . $result . "&mensaje=" . $message);
        break;
//################################## CALENDARIO FIN ###############################################
//################################## CATEGORIAS INICIO #########################################
        
    case "newCategoria":
        
        $result  = "ok";
        $message = "Categoria agregada correctamente";
        $nombre = $_POST['nombre'];
        $cantidadPuntosRecorridos = $_POST['cantidadPuntosRecorridos'];
        $descCorta = $_POST['descCorta'];
        $desc = $_POST['desc'];
        $lat = $_POST['lat'];
        $long = $_POST['long'];
        $categoria = $_POST['categoria'];
        $padre = $_POST['padre'];
        $ruta = (isset($_POST['foto']) && $_POST['foto'] !== '') ? $_POST['foto'] : "img/categorias/no-image.gif";
        $rutasExtras = array();
        $insertedID = 0;
        $puntosDelRecorrido = array();
        
        for ($i = 1; $i < $cantidadPuntosRecorridos; $i++) {
            $puntosDelRecorrido[] = array("lat"=>$_POST['lat'.$i], "long"=>$_POST['long'.$i]);
        }
        $puntosDelRecorridoJson = json_encode($puntosDelRecorrido, true);
        
        foreach ($_FILES as $key => $value) 
        {
            if(isset($value['name']) && $value['name'] != '')
            {
                //if no errors...
                if(!$value['error'])
                {
                    $valid_file = true;
                    //now is the time to modify the future file name and validate the file
                    $new_file_name = strtolower($value['name']); //rename file
                    $Length = 10;
                    $RandomString = substr(str_shuffle(md5(time())), 0, $Length);

                    $new_file_name = $RandomString . "_" .  str_replace(' ', '-', $new_file_name);
                    if($value['size'] > (6144000)) //can't be larger than 6 MB
                    {
                        $valid_file = false;
                        $message = 'Oops!  Your file\'s size is to large.';
                        $result  = "ko";
                        header("Location: tours-edit.php?result=" . $result . "&mensaje=" . $message);
                    }

                    $pos = strpos($value['type'], "image");
                    if ($pos === FALSE)
                    {
                        $valid_file = false;
                        $message = 'Oops!  El archivo no es una imagen.';
                        $result  = "ko";
                        header("Location: tours-edit.php?result=" . $result . "&mensaje=" . $message);
                    }
                    //if the file has passed the test
                    if($valid_file)
                    {
                        //move it to where we want it to be
                        $rutaTmp = '../img/categorias/'.$new_file_name;
                        //ruta de los thumbs

                        move_uploaded_file($value['tmp_name'], $rutaTmp);

                        if($key == "photo")
                        {
                            $ruta = substr($rutaTmp, 3);
                        }
                        else
                        {
                            $rutasExtras[] = substr($rutaTmp, 3);
                        }
                    }
                    //if there is an error...
                    else
                    {
                        //set that to be the returned message
                        $message = 'Ooops!  Your upload triggered the following error:  invalid file';
                        $result  = "ko";
                        header("Location: tours-edit.php?result=" . $result . "&mensaje=" . $message);
                    }
                }
                //if there is an error...
                else
                {
                    //set that to be the returned message
                    $message = 'Ooops!  Your upload triggered the following error:  '.$value['error'];
                    $result  = "ko";
                    header("Location: tours-edit.php?result=" . $result . "&mensaje=" . $message);
                }
            }
        }
        
        // prepare and bind
        if ($stmt = $mysqli->prepare("INSERT INTO categorias (`nombre`, `foto`, `descripcion_corta`, `descripcion`, `id_tour`, `cat_superior`, `lat`, `long`, `polylines`) values (?, ?, ?, ?, ?, ?, ?, ?, ?)")) 
        {
            $stmt->bind_param("ssssiisss", $nombre, $ruta, $descCorta, $desc, $categoria, $padre, $lat, $long, $puntosDelRecorridoJson);

            if (!$stmt->execute()) 
            {
                $message = "Falló la ejecución: (" . $stmt->errno . ") " . $stmt->error;
                $result = "ko";
                header("Location: tours-edit.php?result=" . $result . "&mensaje=" . $message);
            }
            $insertedID = $stmt->insert_id;
            $stmt->close();
        } 
        else
        {
            $message = "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
            $result  = "ko";
            header("Location: tours-edit.php?result=" . $result . "&mensaje=" . $message);
        }
        
        if($insertedID)
        {
            foreach ($rutasExtras as $rutaExtra)
            {
                if ($stmt = $mysqli->prepare("INSERT INTO img_categorias (`url`, `id_categoria`) values (?, ?)")) 
                {
                    $stmt->bind_param("si", $rutaExtra, $insertedID);

                    if (!$stmt->execute()) 
                    {
                        $message = "Falló la ejecución: (" . $stmt->errno . ") " . $stmt->error;
                        $result = "ko";
                    }

                    $stmt->close();
                }
                else
                {
                    $message = "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
                    $result  = "ko";
                }
            }    
        }
        
        header("Location: tours-edit.php?result=" . $result . "&mensaje=" . $message);
        break;
    
    case "borrarFotoExtra":
        
        $result = "ok";
        $message = "Categoria eliminada correctamente";
        $id = $_POST["id"];
        
        if ($stmt = $mysqli->prepare("SELECT url FROM img_categorias WHERE id=?")) 
        {
            /* ligar parámetros para marcadores */
            $stmt->bind_param("i", $id);
            /* ejecutar la consulta */
            if(!$stmt->execute())
            {
                $message = "Falló la ejecución: (" . $stmt->errno . ") " . $stmt->error;
                $result  = "ko";
            }

            /* ligar variables de resultado */
            $stmt->bind_result($foto);
                        
            /* obtener valor */
            $stmt->fetch();
            /* cerrar sentencia */
            $stmt->close();

            if(file_exists("../".$foto))
            {
                unlink("../".$foto);
            }
            
            if ($stmt2 = $mysqli->prepare("DELETE FROM img_categorias WHERE id = ?")) 
            {
                $stmt2->bind_param("s", $id);

                if (!$stmt2->execute())
                {
                    $message = "Falló la ejecución: (" . $stmt2->errno . ") " . $stmt2->error;
                    $result = "ko";
                }

                $stmt2->close();
            }
            else
            {
                $message = "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
                $result = "ko";
            }
            
        }
        
        echo json_encode(array("result"=>$result , "mensaje"=>$message));
        exit();
        break;
        
    case 'eliminarEntradaCategorias':

        $result = "ok";
        $message = "Categoria eliminada correctamente";
        $id = $_POST['id'];
        //chequeo si tiene hijos
        if ($stmt1 = $mysqli->prepare("SELECT * FROM categorias WHERE cat_superior = ?")) 
        {
            $stmt1->bind_param("s", $id);

            if (!$stmt1->execute())
            {
                $message = "Falló la ejecución: (" . $stmt1->errno . ") " . $stmt1->error;
                $result = "ko";
            }
            
            $stmt1->store_result();
            
            //si no tiene hijos prosigo
            if(!$stmt1->num_rows)
            {
                //recupero url de la foto principal
                if ($stmt2 = $mysqli->prepare("SELECT foto FROM categorias WHERE id = ?")) 
                {
                    $stmt2->bind_param("s", $id);

                    if (!$stmt2->execute())
                    {
                        $message = "Falló la ejecución: (" . $stmt2->errno . ") " . $stmt2->error;
                        $result = "ko";
                    }

                    $stmt2->bind_result($foto);
                    $stmt2->fetch();
                    //la elimino siempre y cuando sea distinta de no-image.gif
                    if($foto != null && file_exists("../".$foto) && strpos($foto, "no-image.gif") === false)
                    {
                        unlink("../".$foto);
                    }
                    //tengo que hacer lo mismo con las fotos extras
                    $stmt2->store_result();
                    $stmt2->close();
                    if ($stmt3 = $mysqli->prepare("SELECT url FROM img_categorias WHERE id_categoria = ?")) 
                    {
                        $stmt3->bind_param("s", $id);

                        if (!$stmt3->execute())
                        {
                            $message = "Falló la ejecución: (" . $stmt3->errno . ") " . $stmt3->error;
                            $result = "ko";
                        }

                        $stmt3->bind_result($url);
                        while ($stmt3->fetch()) 
                        {
                            //la elimino siempre y cuando sea distinta de no-image.gif
                            if(file_exists("../".$url) and strpos($url, "no-image.gif") === false)
                            {
                                unlink("../".$url);
                            }
                        }
                    }    
                    else
                    {
                        $message = "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
                        $result = "ko";
                        echo json_encode(array("result"=>$result, "mensaje"=>$message, "asd"=>"venimo4"));
                        exit();
                    }
                    //ahora borro el registro de la tabla categorias
                    $stmt3->close();
                    if ($stmt4 = $mysqli->prepare("DELETE FROM categorias WHERE id = ?")) 
                    {
                        $stmt4->bind_param("s", $id);

                        if (!$stmt4->execute())
                        {
                            $message = "Falló la ejecución: (" . $stmt4->errno . ") " . $stmt4->error;
                            $result = "ko";
                        }

                        $stmt4->close();
                    }
                    else
                    {
                        $message = "Falló la preparación 1: (" . $mysqli->errno . ") " . $mysqli->error;
                        $result = "ko";
                    }
                    
                    // y los registros de la tabla img_categorias, donde están las imagenes extras
                    if ($stmt5 = $mysqli->prepare("DELETE FROM img_categorias WHERE id_categoria = ?")) 
                    {
                        $stmt5->bind_param("s", $id);

                        if (!$stmt5->execute())
                        {
                            $message = "Falló la ejecución: (" . $stmt5->errno . ") " . $stmt5->error;
                            $result = "ko";
                        }

                        $stmt5->close();
                    }
                    else
                    {
                        $message = "Falló la preparación 2: (" . $mysqli->errno . ") " . $mysqli->error;
                        $result = "ko";
                    }
                }
                else
                {
                    $message = "Falló la preparación 3: (" . $mysqli->errno . ") " . $mysqli->error;
                    $result = "ko";
                }
            }
            else
            {
                $message = "El tour que intenta borrar tiene tours hijos asociados. Elimine primero todos estos tours antes de borrar al padre";
                $result = "ko";
            }
            
            $stmt1->close();
        }
        else
        {
            $message = "Falló la preparación 4: (" . $mysqli->errno . ") " . $mysqli->error;
            $result = "ko";
        }
        
        
        echo json_encode(array("result"=>$result, "mensaje"=>$message));
        exit();
        
        break;    
        
    case "editCategoria":
        $result  = "ok";
        $message = "Categoria editada correctamente";
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $descCorta = $_POST['descCorta'];
        $desc = $_POST['desc'];
        $lat = $_POST['lat'];
        $long = $_POST['long'];
        $categoria = $_POST['categoria'];
        $padre = $_POST['padre'];
        $ruta = (isset($_POST['foto']) && $_POST['foto'] !== '') ? $_POST['foto'] : "img/categorias/no-image.gif";
        $rutasExtras = array();
        $rutasExtrasNuevas = array();
        $borrarFoto = (isset($_POST['borrarFoto']) && $_POST['borrarFoto'] !== '') ? $_POST['borrarFoto'] : false;
        $idPuntosRecorridos = $_POST['idPuntosRecorridos'];

        if($borrarFoto)
        {
            if ($stmt = $mysqli->prepare("SELECT foto FROM categorias WHERE id=?"))
            {
                /* ligar parámetros para marcadores */
                $stmt->bind_param("i", $id);
                /* ejecutar la consulta */
                if(!$stmt->execute())
                {
                    $message = "Falló la ejecución: (" . $stmt->errno . ") " . $stmt->error;
                    $result  = "ko";
                }

                /* ligar variables de resultado */
                $stmt->bind_result($foto);
                        
                /* obtener valor */
                $stmt->fetch();
                /* cerrar sentencia */
                $stmt->close();

                if(file_exists("../".$foto) and strpos($foto, "no-image.gif") === false)
                {
                    unlink("../".$foto);
                }
            }
        }
        
        foreach ($_FILES as $key => $value) 
        {
            if(isset($value['name']) && $value['name'] != '')
            {
                //if no errors...
                if(!$value['error'])
                {
                    $valid_file = true;
                    //now is the time to modify the future file name and validate the file
                    $new_file_name = strtolower($value['name']); //rename file
                    $Length = 10;
                    $RandomString = substr(str_shuffle(md5(time())), 0, $Length);

                    $new_file_name = $RandomString . "_" .  str_replace(' ', '-', $new_file_name);
                    if($value['size'] > (6144000)) //can't be larger than 6 MB
                    {
                        $valid_file = false;
                        $message = 'Oops!  Your file\'s size is to large.';
                        $result  = "ko";
                        header("Location: tour-edit.php?result=" . $result . "&mensaje=" . $message . "&id=".$id);
                    }

                    $pos = strpos($value['type'], "image");
                    if ($pos === FALSE)
                    {
                        $valid_file = false;
                        $message = 'Oops!  El archivo no es una imagen.';
                        $result  = "ko";
                        header("Location: tour-edit.php?result=" . $result . "&mensaje=" . $message . "&id=".$id);
                    }
                    //if the file has passed the test
                    if($valid_file)
                    {
                        //move it to where we want it to be
                        $rutaTmp = '../img/categorias/'.$new_file_name;
                        //ruta de los thumbs

                        move_uploaded_file($value['tmp_name'], $rutaTmp);

                        if($key == "photo")
                        {
                            $ruta = substr($rutaTmp, 3);
                            if ($stmt = $mysqli->prepare("SELECT foto FROM categorias WHERE id=?")) 
                            {
                                /* ligar parámetros para marcadores */
                                $stmt->bind_param("i", $id);

                                /* ejecutar la consulta */
                                if(!$stmt->execute())
                                {
                                    $message = "Falló la ejecución: (" . $stmt->errno . ") " . $stmt->error;
                                    $result  = "ko";
                                }

                                /* ligar variables de resultado */
                                $stmt->bind_result($foto);

                                /* obtener valor */
                                $stmt->fetch();
                                /* cerrar sentencia */
                                $stmt->close();

                                if(file_exists("../".$foto) and strpos($foto, "no-image.gif") === false)
                                {
                                    unlink("../".$foto);
                                }
                            }
                        }
                        elseif (strpos($key, 'photoExtraNueva') !== false) 
                        {
                            if ($stmt = $mysqli->prepare("INSERT INTO img_categorias (id_categoria, url, habilitada) VALUES (?, ?, ?)")) 
                            {
                                /* ligar parámetros para marcadores */
                                $url = substr($rutaTmp, 3);
                                $hab = 1;
                                $stmt->bind_param("isi", $id, $url, $hab);

                                /* ejecutar la consulta */
                                if(!$stmt->execute())
                                {
                                    $message = "Falló la ejecución: (" . $stmt->errno . ") " . $stmt->error;
                                    $result  = "ko";
                                }

                                /* ligar variables de resultado */
                                $stmt->bind_result($foto);

                                /* obtener valor */
                                $stmt->fetch();
                                /* cerrar sentencia */
                                $stmt->close();
                            }    
                        }
                        else
                        {
                            $id_img_cat = substr($key, 10);
                            $rutasExtras[] = array("ruta"=>substr($rutaTmp, 3), "id"=>$id_img_cat);
                            
                            if ($stmt = $mysqli->prepare("SELECT url FROM img_categorias WHERE id=?")) 
                            {
                                /* ligar parámetros para marcadores */
                                
                                $stmt->bind_param("i", $id_img_cat);

                                /* ejecutar la consulta */
                                if(!$stmt->execute())
                                {
                                    $message = "Falló la ejecución: (" . $stmt->errno . ") " . $stmt->error;
                                    $result  = "ko";
                                }

                                /* ligar variables de resultado */
                                $stmt->bind_result($foto);

                                /* obtener valor */
                                $stmt->fetch();
                                /* cerrar sentencia */
                                $stmt->close();

                                if(file_exists("../".$foto))
                                {
                                    unlink("../".$foto);
                                }
                            }
                        }
                    }
                    //if there is an error...
                    else
                    {
                        //set that to be the returned message
                        $message = 'Ooops!  Your upload triggered the following error:  invalid file';
                        $result  = "ko";
                        header("Location: tour-edit.php?result=" . $result . "&mensaje=" . $message . "&id=".$id);
                    }
                }
                //if there is an error...
                else
                {
                    //set that to be the returned message
                    $message = 'Ooops!  Your upload triggered the following error:  '.$value['error'];
                    $result  = "ko";
                    header("Location: tour-edit.php?result=" . $result . "&mensaje=" . $message . "&id=".$id);
                }
            }
        }
        // prepare and bind
        if ($stmt = $mysqli->prepare("UPDATE categorias SET `nombre` = ?, `foto` = ?, `descripcion_corta` = ?, `descripcion` = ?, `id_tour` = ?, `cat_superior` = ?, `lat` = ?, `long` = ?, polylines = ? WHERE id = ?")) 
        {
            $stmt->bind_param("ssssiisssi", $nombre, $ruta, $descCorta, $desc, $categoria, $padre, $lat, $long, $idPuntosRecorridos, $id);

            if (!$stmt->execute()) 
            {
                $message = "Falló la ejecución: (" . $stmt->errno . ") " . $stmt->error;
                $result = "ko";
            }

            $stmt->close();
        }
        else
        {
            $message = "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
            $result  = "ko";
        }
        
        foreach ($rutasExtras as $rutaExtra)
        {
            if ($stmt = $mysqli->prepare("UPDATE img_categorias SET url = ? WHERE id = ?"))
            {
                $stmt->bind_param("si", $rutaExtra["ruta"],$rutaExtra["id"]);
                if (!$stmt->execute()) 
                {
                    $message = "Falló la ejecución: (" . $stmt->errno . ") " . $stmt->error;
                    $result = "ko";
                }
                $stmt->close();
            }
            else
            {
                $message = "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
                $result  = "ko";
            }
        }
        
        header("Location: tour-edit.php?result=" . $result . "&mensaje=" . $message . "&id=".$id);
        break;    
        
//################################## CATEGORIAS FIN #########################################
//################################## SLIDER HEADER INICIO #########################################
    case "newSliderHeader":
        
        $result  = "ok";
        $message = "Entrada agregada correctamente";
        
        $titulo = $_POST['titulo'];
        $descripcion = $_POST['descripcion'];
        $categoria_relacionada = $_POST['categoria_relacionada'];
        $habilitado = $_POST['habilitado'];
        
        if(isset($_FILES['photo']['name']) && $_FILES['photo']['name'] != '')
        {
            //if no errors...
            if(!$_FILES['photo']['error'])
            {
                $valid_file = true;
                //now is the time to modify the future file name and validate the file
                $new_file_name = strtolower($_FILES['photo']['name']); //rename file
                $Length = 10;
                $RandomString = substr(str_shuffle(md5(time())), 0, $Length);

                $new_file_name = $RandomString . "_" .  str_replace(' ', '-', $new_file_name);
                if($_FILES['photo']['size'] > (6144000)) //can't be larger than 6 MB
                {
                    $valid_file = false;
                    $message = 'Oops!  Your file\'s size is to large.';
                    $result  = "ko";
                    header("Location: slider-cabecera-edit.php?result=" . $result . "&mensaje=" . $message);
                    exit();
                }

                $pos = strpos($_FILES['photo']['type'], "image");
                if ($pos === FALSE)
                {
                    $valid_file = false;
                    $message = 'Oops!  El archivo no es una imagen.';
                    $result  = "ko";
                    header("Location: slider-cabecera-edit.php?result=" . $result . "&mensaje=" . $message);
                    exit();
                }
                //if the file has passed the test
                if($valid_file)
                {
                    //move it to where we want it to be
                    $ruta = '../img/slider_cabecera/'.$new_file_name;
                    //ruta de los thumbs

                    move_uploaded_file($_FILES['photo']['tmp_name'], $ruta);
                    
                    $ruta = substr($ruta, 3);
                    
                    // prepare and bind
                    if ($stmt = $mysqli->prepare("INSERT INTO slider_cabecera (url, habilitado, titulo, descripcion, categoria_id) values (?, ?, ?, ?, ?)")) 
                    {
                        $stmt->bind_param("sissi", $ruta, $habilitado, $titulo, $descripcion, $categoria_relacionada);

                        if (!$stmt->execute()) 
                        {
                            $message = "Falló la ejecución: (" . $stmt->errno . ") " . $stmt->error;
                            $result = "ko";
                            header("Location: slider-cabecera-edit.php?result=" . $result . "&mensaje=" . $message);
                            exit();
                        }

                        $stmt->close();
                    } 
                    else
                    {
                        $message = "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
                        $result  = "ko";
                        header("Location: slider-cabecera.php?result=" . $result . "&mensaje=" . $message);
                        exit();
                    }
                }
                //if there is an error...
                else
                {
                    //set that to be the returned message
                    $message = 'Ooops!  Your upload triggered the following error:  invalid file';
                    $result  = "ko";
                    header("Location: slider-cabecera-edit.php?result=" . $result . "&mensaje=" . $message);
                    exit();
                }
            }
            //if there is an error...
            else
            {
                //set that to be the returned message
                $message = 'Ooops!  Your upload triggered the following error:  '.$_FILES['photo']['error'];
                $result  = "ko";
                header("Location: slider-cabecera-edit.php?result=" . $result . "&mensaje=" . $message);
                exit();
            }
        }
        else
        {
            $result  = "ko";
            $message = "Ninguna imagen suministrada";
        }
        
        header("Location: slider-cabecera-edit.php?result=" . $result . "&mensaje=" . $message);
        break;
        
    case "eliminarEntradaSliderCabecera":
        
        $result  = "ok";
        $message = "Entrada eliminada correctamente";
        
        $id = $_POST['id'];
        
        if ($stmt1 = $mysqli->prepare("SELECT url FROM slider_cabecera WHERE id = ?")) 
        {
            $stmt1->bind_param("i", $id);

            if (!$stmt1->execute())
            {
                $message = "Falló la ejecución: (" . $stmt1->errno . ") " . $stmt1->error;
                $result = "ko";
                echo json_encode(array("result"=>$result, "mensaje"=>$message));
                exit();
            }
            
            $stmt1->bind_result($url);
            
            $stmt1->fetch();
            /* cerrar sentencia */
            $stmt1->close();

            if(file_exists("../".$url))
            {
                unlink("../".$url);
            }
            
            if ($stmt2 = $mysqli->prepare("DELETE FROM slider_cabecera WHERE id = ?")) 
            {
                $stmt2->bind_param("s", $id);

                if (!$stmt2->execute())
                {
                    $message = "Falló la ejecución: (" . $stmt2->errno . ") " . $stmt2->error;
                    $result = "ko";
                    echo json_encode(array("result"=>$result, "mensaje"=>$message));
                    exit();
                }

                $stmt2->close();
            }
            else
            {
                $message = "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
                $result = "ko";
                echo json_encode(array("result"=>$result, "mensaje"=>$message));
                exit();
            }
        }
        else
        {
            $message = "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
            $result = "ko";
            echo json_encode(array("result"=>$result, "mensaje"=>$message));
            exit();
        }
            
        echo json_encode(array("result"=>$result, "mensaje"=>$message));
        exit();
        
        break;
        
        case "editSliderHeader":
        
        $result  = "ok";
        $message = "Entrada editada correctamente";
        
        $id = $_POST['id_slider'];
        $titulo = $_POST['titulo'];
        $descripcion = $_POST['descripcion'];
        $categoria_relacionada = $_POST['categoria_relacionada'];
        $habilitado = $_POST['habilitado'];
        $ruta = (isset($_POST['foto']) && $_POST['foto'] !== '') ? $_POST['foto'] : "img/categorias/no-image.gif";
        
        if(isset($_FILES['photo']['name']) && $_FILES['photo']['name'] != '')
        {
            //if no errors...
            if(!$_FILES['photo']['error'])
            {
                $valid_file = true;
                //now is the time to modify the future file name and validate the file
                $new_file_name = strtolower($_FILES['photo']['name']); //rename file
                $Length = 10;
                $RandomString = substr(str_shuffle(md5(time())), 0, $Length);

                $new_file_name = $RandomString . "_" .  str_replace(' ', '-', $new_file_name);
                if($_FILES['photo']['size'] > (6144000)) //can't be larger than 6 MB
                {
                    $valid_file = false;
                    $message = 'Oops!  Your file\'s size is to large.';
                    $result  = "ko";
                    header("Location: slider-cabecera-edit.php?result=" . $result . "&mensaje=" . $message);
                    exit();
                }

                $pos = strpos($_FILES['photo']['type'], "image");
                if ($pos === FALSE)
                {
                    $valid_file = false;
                    $message = 'Oops!  El archivo no es una imagen.';
                    $result  = "ko";
                    header("Location: slider-cabecera-edit.php?result=" . $result . "&mensaje=" . $message);
                    exit();
                }
                //if the file has passed the test
                if($valid_file)
                {
                    //move it to where we want it to be
                    $ruta = '../img/slider_cabecera/'.$new_file_name;
                    //ruta de los thumbs

                    move_uploaded_file($_FILES['photo']['tmp_name'], $ruta);
                    
                    $ruta = substr($ruta, 3);
                    
                    if ($stmt1 = $mysqli->prepare("SELECT url FROM slider_cabecera WHERE id = ?")) 
                    {
                        $stmt1->bind_param("i", $id);

                        if (!$stmt1->execute())
                        {
                            $message = "Falló la ejecución: (" . $stmt1->errno . ") " . $stmt1->error;
                            $result = "ko";
                            header("Location: slider-cabecera-edit.php?result=" . $result . "&mensaje=" . $message);
                            exit();
                        }

                        $stmt1->bind_result($url);

                        $stmt1->fetch();
                        /* cerrar sentencia */
                        $stmt1->close();
                        
                        if(file_exists("../".$url))
                        {
                            unlink("../".$url);
                        }
                    }
                    else
                    {
                        $message = "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
                        $result = "ko";
                        header("Location: slider-cabecera-edit.php?result=" . $result . "&mensaje=" . $message);
                        exit();
                    }
                }
                //if there is an error...
                else
                {
                    //set that to be the returned message
                    $message = 'Ooops!  Your upload triggered the following error:  invalid file';
                    $result  = "ko";
                    header("Location: slider-cabecera-edit.php?result=" . $result . "&mensaje=" . $message);
                    exit();
                }
            }
            //if there is an error...
            else
            {
                //set that to be the returned message
                $message = 'Ooops!  Your upload triggered the following error:  '.$_FILES['photo']['error'];
                $result  = "ko";
                header("Location: slider-cabecera-edit.php?result=" . $result . "&mensaje=" . $message);
                exit();
            }
        }
        
        // prepare and bind
        if ($stmt = $mysqli->prepare("UPDATE slider_cabecera SET url = ?, habilitado = ?, titulo = ?, descripcion = ?, categoria_id = ? WHERE id = ?")) 
        {
            $stmt->bind_param("sissii", $ruta, $habilitado, $titulo, $descripcion, $categoria_relacionada, $id);

            if (!$stmt->execute()) 
            {
                $message = "Falló la ejecución: (" . $stmt->errno . ") " . $stmt->error;
                $result = "ko";
            }

            $stmt->close();
        } 
        else
        {
            $message = "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
            $result  = "ko";
        }
        
        header("Location: slider-cabecera-edit.php?result=" . $result . "&mensaje=" . $message);
        break;
//################################## SLIDER HEADER INICIO #########################################        
//################################## SLIDER SALIDAS INICIO #########################################
    case "newSliderSalidas":
        
        $result  = "ok";
        $message = "Entrada agregada correctamente";
        
        $titulo = $_POST['titulo'];
        $descripcion = $_POST['descripcion'];
        $categoria_relacionada = $_POST['categoria_relacionada'];
        $habilitado = $_POST['habilitado'];
        
        if(isset($_FILES['photo']['name']) && $_FILES['photo']['name'] != '')
        {
            //if no errors...
            if(!$_FILES['photo']['error'])
            {
                $valid_file = true;
                //now is the time to modify the future file name and validate the file
                $new_file_name = strtolower($_FILES['photo']['name']); //rename file
                $Length = 10;
                $RandomString = substr(str_shuffle(md5(time())), 0, $Length);

                $new_file_name = $RandomString . "_" .  str_replace(' ', '-', $new_file_name);
                if($_FILES['photo']['size'] > (6144000)) //can't be larger than 6 MB
                {
                    $valid_file = false;
                    $message = 'Oops!  Your file\'s size is to large.';
                    $result  = "ko";
                    header("Location: slider-salidas-edit.php?result=" . $result . "&mensaje=" . $message);
                    exit();
                }

                $pos = strpos($_FILES['photo']['type'], "image");
                if ($pos === FALSE)
                {
                    $valid_file = false;
                    $message = 'Oops!  El archivo no es una imagen.';
                    $result  = "ko";
                    header("Location: slider-salidas-edit.php?result=" . $result . "&mensaje=" . $message);
                    exit();
                }
                //if the file has passed the test
                if($valid_file)
                {
                    //move it to where we want it to be
                    $ruta = '../img/slider_salidas/'.$new_file_name;
                    //ruta de los thumbs

                    move_uploaded_file($_FILES['photo']['tmp_name'], $ruta);
                    
                    $ruta = substr($ruta, 3);
                    
                    // prepare and bind
                    if ($stmt = $mysqli->prepare("INSERT INTO slider_salidas (url, habilitado, titulo, descripcion, categoria_id) values (?, ?, ?, ?, ?)")) 
                    {
                        $stmt->bind_param("sissi", $ruta, $habilitado, $titulo, $descripcion, $categoria_relacionada);

                        if (!$stmt->execute()) 
                        {
                            $message = "Falló la ejecución: (" . $stmt->errno . ") " . $stmt->error;
                            $result = "ko";
                            header("Location: slider-salidas-edit.php?result=" . $result . "&mensaje=" . $message);
                            exit();
                        }

                        $stmt->close();
                    } 
                    else
                    {
                        $message = "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
                        $result  = "ko";
                        header("Location: slider-salidas.php?result=" . $result . "&mensaje=" . $message);
                        exit();
                    }
                }
                //if there is an error...
                else
                {
                    //set that to be the returned message
                    $message = 'Ooops!  Your upload triggered the following error:  invalid file';
                    $result  = "ko";
                    header("Location: slider-salidas-edit.php?result=" . $result . "&mensaje=" . $message);
                    exit();
                }
            }
            //if there is an error...
            else
            {
                //set that to be the returned message
                $message = 'Ooops!  Your upload triggered the following error:  '.$_FILES['photo']['error'];
                $result  = "ko";
                header("Location: slider-salidas-edit.php?result=" . $result . "&mensaje=" . $message);
                exit();
            }
        }
        else
        {
            $result  = "ko";
            $message = "Ninguna imagen suministrada";
        }
        
        header("Location: slider-salidas-edit.php?result=" . $result . "&mensaje=" . $message);
        break;
        
    case "eliminarEntradaSliderSalidas":
        
        $result  = "ok";
        $message = "Entrada eliminada correctamente";
        
        $id = $_POST['id'];
        
        if ($stmt1 = $mysqli->prepare("SELECT url FROM slider_salidas WHERE id = ?")) 
        {
            $stmt1->bind_param("i", $id);

            if (!$stmt1->execute())
            {
                $message = "Falló la ejecución: (" . $stmt1->errno . ") " . $stmt1->error;
                $result = "ko";
                echo json_encode(array("result"=>$result, "mensaje"=>$message));
                exit();
            }
            
            $stmt1->bind_result($url);
            
            $stmt1->fetch();
            /* cerrar sentencia */
            $stmt1->close();

            if(file_exists("../".$url))
            {
                unlink("../".$url);
            }
            
            if ($stmt2 = $mysqli->prepare("DELETE FROM slider_salidas WHERE id = ?")) 
            {
                $stmt2->bind_param("s", $id);

                if (!$stmt2->execute())
                {
                    $message = "Falló la ejecución: (" . $stmt2->errno . ") " . $stmt2->error;
                    $result = "ko";
                    echo json_encode(array("result"=>$result, "mensaje"=>$message));
                    exit();
                }

                $stmt2->close();
            }
            else
            {
                $message = "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
                $result = "ko";
                echo json_encode(array("result"=>$result, "mensaje"=>$message));
                exit();
            }
        }
        else
        {
            $message = "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
            $result = "ko";
            echo json_encode(array("result"=>$result, "mensaje"=>$message));
            exit();
        }
            
        echo json_encode(array("result"=>$result, "mensaje"=>$message));
        exit();
        
        break;
        
    case "editSliderSalidas":
        
        $result  = "ok";
        $message = "Entrada editada correctamente";
        
        $id = $_POST['id_slider'];
        $titulo = $_POST['titulo'];
        $descripcion = $_POST['descripcion'];
        $categoria_relacionada = $_POST['categoria_relacionada'];
        $habilitado = $_POST['habilitado'];
        $ruta = (isset($_POST['foto']) && $_POST['foto'] !== '') ? $_POST['foto'] : "img/categorias/no-image.gif";
        
        if(isset($_FILES['photo']['name']) && $_FILES['photo']['name'] != '')
        {
            //if no errors...
            if(!$_FILES['photo']['error'])
            {
                $valid_file = true;
                //now is the time to modify the future file name and validate the file
                $new_file_name = strtolower($_FILES['photo']['name']); //rename file
                $Length = 10;
                $RandomString = substr(str_shuffle(md5(time())), 0, $Length);

                $new_file_name = $RandomString . "_" .  str_replace(' ', '-', $new_file_name);
                if($_FILES['photo']['size'] > (6144000)) //can't be larger than 6 MB
                {
                    $valid_file = false;
                    $message = 'Oops!  Your file\'s size is to large.';
                    $result  = "ko";
                    header("Location: slider-salidas-edit.php?result=" . $result . "&mensaje=" . $message);
                    exit();
                }

                $pos = strpos($_FILES['photo']['type'], "image");
                if ($pos === FALSE)
                {
                    $valid_file = false;
                    $message = 'Oops!  El archivo no es una imagen.';
                    $result  = "ko";
                    header("Location: slider-salidas-edit.php?result=" . $result . "&mensaje=" . $message);
                    exit();
                }
                //if the file has passed the test
                if($valid_file)
                {
                    //move it to where we want it to be
                    $ruta = '../img/slider_salidas/'.$new_file_name;
                    //ruta de los thumbs

                    move_uploaded_file($_FILES['photo']['tmp_name'], $ruta);
                    
                    $ruta = substr($ruta, 3);
                    
                    if ($stmt1 = $mysqli->prepare("SELECT url FROM slider_salidas WHERE id = ?")) 
                    {
                        $stmt1->bind_param("i", $id);

                        if (!$stmt1->execute())
                        {
                            $message = "Falló la ejecución: (" . $stmt1->errno . ") " . $stmt1->error;
                            $result = "ko";
                            header("Location: slider-salidas-edit.php?result=" . $result . "&mensaje=" . $message);
                            exit();
                        }

                        $stmt1->bind_result($url);

                        $stmt1->fetch();
                        /* cerrar sentencia */
                        $stmt1->close();
                        
                        if(file_exists("../".$url))
                        {
                            unlink("../".$url);
                        }
                    }
                    else
                    {
                        $message = "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
                        $result = "ko";
                        header("Location: slider-salidas-edit.php?result=" . $result . "&mensaje=" . $message);
                        exit();
                    }
                }
                //if there is an error...
                else
                {
                    //set that to be the returned message
                    $message = 'Ooops!  Your upload triggered the following error:  invalid file';
                    $result  = "ko";
                    header("Location: slider-salidas-edit.php?result=" . $result . "&mensaje=" . $message);
                    exit();
                }
            }
            //if there is an error...
            else
            {
                //set that to be the returned message
                $message = 'Ooops!  Your upload triggered the following error:  '.$_FILES['photo']['error'];
                $result  = "ko";
                header("Location: slider-salidas-edit.php?result=" . $result . "&mensaje=" . $message);
                exit();
            }
        }
        
        // prepare and bind
        if ($stmt = $mysqli->prepare("UPDATE slider_salidas SET url = ?, habilitado = ?, titulo = ?, descripcion = ?, categoria_id = ? WHERE id = ?")) 
        {
            $stmt->bind_param("sissii", $ruta, $habilitado, $titulo, $descripcion, $categoria_relacionada, $id);

            if (!$stmt->execute()) 
            {
                $message = "Falló la ejecución: (" . $stmt->errno . ") " . $stmt->error;
                $result = "ko";
            }

            $stmt->close();
        } 
        else
        {
            $message = "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
            $result  = "ko";
        }
        
        header("Location: slider-salidas-edit.php?result=" . $result . "&mensaje=" . $message);
        break;
        //################################## SLIDER SALIDAS FIN #########################################
        //################################## NOTICIAS INICIO #########################################
    case "newNoticias":
        
        $result  = "ok";
        $message = "Entrada agregada correctamente";
        
        $titulo = (isset($_POST['titulo'])) ? $_POST['titulo'] : "";
        $texto = (isset($_POST['texto'])) ? $_POST['texto'] : "";
        $video = (isset($_POST['video'])) ? $_POST['video'] : "";
        $habilitado = (isset($_POST['habilitado'])) ? $_POST['habilitado'] : "";
        $fecha = date("Y-m-d");
        
        if(isset($_FILES['photo']['name']) && $_FILES['photo']['name'] != '')
        {
            //if no errors...
            if(!$_FILES['photo']['error'])
            {
                $valid_file = true;
                //now is the time to modify the future file name and validate the file
                $new_file_name = strtolower($_FILES['photo']['name']); //rename file
                $Length = 10;
                $RandomString = substr(str_shuffle(md5(time())), 0, $Length);

                $new_file_name = $RandomString . "_" .  str_replace(' ', '-', $new_file_name);
                if($_FILES['photo']['size'] > (6144000)) //can't be larger than 6 MB
                {
                    $valid_file = false;
                    $message = 'Oops!  Your file\'s size is to large.';
                    $result  = "ko";
                    header("Location: noticias-edit.php?result=" . $result . "&mensaje=" . $message);
                    exit();
                }

                $pos = strpos($_FILES['photo']['type'], "image");
                if ($pos === FALSE)
                {
                    $valid_file = false;
                    $message = 'Oops!  El archivo no es una imagen.';
                    $result  = "ko";
                    header("Location: noticias-edit.php?result=" . $result . "&mensaje=" . $message);
                    exit();
                }
                //if the file has passed the test
                if($valid_file)
                {
                    //move it to where we want it to be
                    $ruta = '../img/noticias/'.$new_file_name;
                    //ruta de los thumbs

                    move_uploaded_file($_FILES['photo']['tmp_name'], $ruta);
                    
                    $ruta = substr($ruta, 3);
                    
                    // prepare and bind
                    if ($stmt = $mysqli->prepare("INSERT INTO noticias (titulo, texto, fecha, url, habilitado, video) values (?, ?, ?, ?, ?, ?)")) 
                    {
                        $stmt->bind_param("ssssis", $titulo, $texto, $fecha, $ruta, $habilitado, htmlentities($video));

                        if (!$stmt->execute()) 
                        {
                            $message = "Falló la ejecución: (" . $stmt->errno . ") " . $stmt->error;
                            $result = "ko";
                            header("Location: noticias-edit.php?result=" . $result . "&mensaje=" . $message);
                            exit();
                        }

                        $stmt->close();
                    } 
                    else
                    {
                        $message = "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
                        $result  = "ko";
                        header("Location: noticias-edit.php?result=" . $result . "&mensaje=" . $message);
                        exit();
                    }
                }
                //if there is an error...
                else
                {
                    //set that to be the returned message
                    $message = 'Ooops!  Your upload triggered the following error:  invalid file';
                    $result  = "ko";
                    header("Location: noticias-edit.php?result=" . $result . "&mensaje=" . $message);
                    exit();
                }
            }
            //if there is an error...
            else
            {
                //set that to be the returned message
                $message = 'Ooops!  Your upload triggered the following error:  '.$_FILES['photo']['error'];
                $result  = "ko";
                header("Location: noticias-edit.php?result=" . $result . "&mensaje=" . $message);
                exit();
            }
        }
        else
        {
            $result  = "ko";
            $message = "Ninguna imagen suministrada";
        }
        
        header("Location: noticias-edit.php?result=" . $result . "&mensaje=" . $message);
        break;
        
    case "eliminarNoticia":
        
        $result  = "ok";
        $message = "Entrada eliminada correctamente";
        
        $id = $_POST['id'];
        
        if ($stmt1 = $mysqli->prepare("SELECT url FROM noticias WHERE id = ?")) 
        {
            $stmt1->bind_param("i", $id);

            if (!$stmt1->execute())
            {
                $message = "Falló la ejecución: (" . $stmt1->errno . ") " . $stmt1->error;
                $result = "ko";
                echo json_encode(array("result"=>$result, "mensaje"=>$message));
                exit();
            }
            
            $stmt1->bind_result($url);
            
            $stmt1->fetch();
            /* cerrar sentencia */
            $stmt1->close();

            if(file_exists("../".$url))
            {
                unlink("../".$url);
            }
            
            if ($stmt2 = $mysqli->prepare("DELETE FROM noticias WHERE id = ?")) 
            {
                $stmt2->bind_param("i", $id);

                if (!$stmt2->execute())
                {
                    $message = "Falló la ejecución: (" . $stmt2->errno . ") " . $stmt2->error;
                    $result = "ko";
                    echo json_encode(array("result"=>$result, "mensaje"=>$message));
                    exit();
                }

                $stmt2->close();
            }
            else
            {
                $message = "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
                $result = "ko";
                echo json_encode(array("result"=>$result, "mensaje"=>$message));
                exit();
            }
        }
        else
        {
            $message = "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
            $result = "ko";
            echo json_encode(array("result"=>$result, "mensaje"=>$message));
            exit();
        }
            
        echo json_encode(array("result"=>$result, "mensaje"=>$message));
        exit();
        
        break;
        
        case "editNoticias":
        
        $result  = "ok";
        $message = "Entrada editada correctamente";
        
        $id = $_POST['id'];
        $titulo = $_POST['titulo'];
        $texto = $_POST['texto'];
        $video = $_POST['video'];
        $habilitado = $_POST['habilitado'];
        $fecha = date("Y-m-d");
        $ruta = (isset($_POST['foto']) && $_POST['foto'] !== '') ? $_POST['foto'] : "img/categorias/no-image.gif";
        
        if(isset($_FILES['photo']['name']) && $_FILES['photo']['name'] != '')
        {
            //if no errors...
            if(!$_FILES['photo']['error'])
            {
                $valid_file = true;
                //now is the time to modify the future file name and validate the file
                $new_file_name = strtolower($_FILES['photo']['name']); //rename file
                $Length = 10;
                $RandomString = substr(str_shuffle(md5(time())), 0, $Length);

                $new_file_name = $RandomString . "_" .  str_replace(' ', '-', $new_file_name);
                if($_FILES['photo']['size'] > (6144000)) //can't be larger than 6 MB
                {
                    $valid_file = false;
                    $message = 'Oops!  Your file\'s size is to large.';
                    $result  = "ko";
                    header("Location: noticias-edit.php?result=" . $result . "&mensaje=" . $message);
                    exit();
                }

                $pos = strpos($_FILES['photo']['type'], "image");
                if ($pos === FALSE)
                {
                    $valid_file = false;
                    $message = 'Oops!  El archivo no es una imagen.';
                    $result  = "ko";
                    header("Location: noticias-edit.php?result=" . $result . "&mensaje=" . $message);
                    exit();
                }
                //if the file has passed the test
                if($valid_file)
                {
                    //move it to where we want it to be
                    $ruta = '../img/noticias/'.$new_file_name;
                    
                    move_uploaded_file($_FILES['photo']['tmp_name'], $ruta);
                    
                    $ruta = substr($ruta, 3);
                    
                    if ($stmt1 = $mysqli->prepare("SELECT url FROM noticias WHERE id = ?")) 
                    {
                        $stmt1->bind_param("i", $id);

                        if (!$stmt1->execute())
                        {
                            $message = "Falló la ejecución: (" . $stmt1->errno . ") " . $stmt1->error;
                            $result = "ko";
                            header("Location: noticias-edit.php?result=" . $result . "&mensaje=" . $message);
                            exit();
                        }

                        $stmt1->bind_result($url);

                        $stmt1->fetch();
                        /* cerrar sentencia */
                        $stmt1->close();
                        
                        if(file_exists("../".$url))
                        {
                            unlink("../".$url);
                        }
                    }
                    else
                    {
                        $message = "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
                        $result = "ko";
                        header("Location: noticias-edit.php?result=" . $result . "&mensaje=" . $message);
                        exit();
                    }
                }
                //if there is an error...
                else
                {
                    //set that to be the returned message
                    $message = 'Ooops!  Your upload triggered the following error:  invalid file';
                    $result  = "ko";
                    header("Location: noticias-edit.php?result=" . $result . "&mensaje=" . $message);
                    exit();
                }
            }
            //if there is an error...
            else
            {
                //set that to be the returned message
                $message = 'Ooops!  Your upload triggered the following error:  '.$_FILES['photo']['error'];
                $result  = "ko";
                header("Location: noticias-edit.php?result=" . $result . "&mensaje=" . $message);
                exit();
            }
        }
        
        // prepare and bind
        if ($stmt = $mysqli->prepare("UPDATE noticias SET titulo = ?, texto = ?, fecha = ?, url = ?, habilitado = ?, video = ? WHERE id = ?")) 
        {
            $stmt->bind_param("ssssisi", $titulo, $texto, $fecha, $ruta, $habilitado, htmlentities($video) ,$id);

            if (!$stmt->execute()) 
            {
                $message = "Falló la ejecución: (" . $stmt->errno . ") " . $stmt->error;
                $result = "ko";
            }

            $stmt->close();
        } 
        else
        {
            $message = "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
            $result  = "ko";
        }
        
        header("Location: noticias-edit.php?result=" . $result . "&mensaje=" . $message);
        break;
        
    case "editSeguimientoSatalital":
        
        $texto = $_POST['texto'];
        
        if ($stmt = $mysqli->prepare("UPDATE seguimiento SET texto = ?")) 
        {
            $stmt->bind_param("s", $texto);

            if (!$stmt->execute()) 
            {
                $message = "Falló la ejecución: (" . $stmt->errno . ") " . $stmt->error;
                $result = "ko";
            }

            $stmt->close();
        } 
        else
        {
            $message = "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
            $result  = "ko";
        }
        
        header("Location: seguimiento-satelital-edit.php?result=" . $result . "&mensaje=" . $message);
        break;
        
    case "editNosotros":
        
        $texto = $_POST['texto'];
        $img_1 = ($_POST['img_1'] == 'img/categorias/no-image.gif') ? '' : $_POST['img_1'];
        $img_2 = ($_POST['img_2'] == 'img/categorias/no-image.gif') ? '' : $_POST['img_2'];
        $img_3 = ($_POST['img_3'] == 'img/categorias/no-image.gif') ? '' : $_POST['img_3'];
        
        foreach ($_FILES as $key => $value) 
        {
            if(isset($value['name']) && $value['name'] != '')
            {
                //if no errors...
                if(!$value['error'])
                {
                    $valid_file = true;
                    //now is the time to modify the future file name and validate the file
                    $new_file_name = strtolower($value['name']); //rename file
                    $Length = 10;
                    $RandomString = substr(str_shuffle(md5(time())), 0, $Length);

                    $new_file_name = $RandomString . "_" .  str_replace(' ', '-', $new_file_name);
                    if($value['size'] > (6144000)) //can't be larger than 6 MB
                    {
                        $valid_file = false;
                        $message = 'Oops!  Your file\'s size is to large.';
                        $result  = "ko";
                        header("Location: nosotros-edit.php?result=" . $result . "&mensaje=" . $message);
                    }

                    $pos = strpos($value['type'], "image");
                    if ($pos === FALSE)
                    {
                        $valid_file = false;
                        $message = 'Oops!  El archivo no es una imagen.';
                        $result  = "ko";
                        header("Location: nosotros-edit.php?result=" . $result . "&mensaje=" . $message);
                    }
                    //if the file has passed the test
                    if($valid_file)
                    {
                        //move it to where we want it to be
                        $rutaTmp = '../img/qsomos/'.$new_file_name;
                        //ruta de los thumbs

                        move_uploaded_file($value['tmp_name'], $rutaTmp);

                        $ruta[$key] = substr($rutaTmp, 3);
                    }
                    //if there is an error...
                    else
                    {
                        //set that to be the returned message
                        $message = 'Ooops!  Your upload triggered the following error:  invalid file';
                        $result  = "ko";
                        header("Location: nosotros-edit.php?result=" . $result . "&mensaje=" . $message);
                    }
                }
                //if there is an error...
                else
                {
                    //set that to be the returned message
                    $message = 'Ooops!  Your upload triggered the following error:  '.$value['error'];
                    $result  = "ko";
                    header("Location: nosotros-edit.php?result=" . $result . "&mensaje=" . $message);
                }
            }
        }
        if(isset($ruta['photo1'])){
            $selectQuery = 'SELECT img_1 from nosotros';
            buscarYborrar($mysqli, $selectQuery);
            $img_1 = $ruta['photo1'];
        }
        if(isset($ruta['photo2'])){
            $selectQuery = 'SELECT img_2 from nosotros';
            buscarYborrar($mysqli, $selectQuery);
            $img_2 = $ruta['photo2'];
        }
        if(isset($ruta['photo3'])){
            $selectQuery = 'SELECT img_3 from nosotros';
            buscarYborrar($mysqli, $selectQuery);
            $img_3 = $ruta['photo3'];
        }
        
        if ($stmt = $mysqli->prepare("UPDATE nosotros SET texto = ?, img_1 = ?, img_2 = ?, img_3 = ?")) 
        {
            
            $stmt->bind_param("ssss", $texto, $img_1, $img_2, $img_3);

            if (!$stmt->execute()) 
            {
                $message = "Falló la ejecución: (" . $stmt->errno . ") " . $stmt->error;
                $result = "ko";
            }

            $stmt->close();
        } 
        else
        {
            $message = "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
            $result  = "ko";
        }
        
        header("Location: nosotros-edit.php?result=" . $result . "&mensaje=" . $message);
        break;
}

function buscarYborrar($mysqli, $selectQuery, $callback = "nosotros"){

    if ($stmt1 = $mysqli->prepare($selectQuery)) 
        {
            if (!$stmt1->execute())
            {
                $message = "Falló la ejecución: (" . $stmt1->errno . ") " . $stmt1->error;
                $result = "ko";
                header("Location: " . $callback ."-edit.php?result=" . $result . "&mensaje=" . $message);
                exit();
            }

            $stmt1->bind_result($url);

            $stmt1->fetch();
            /* cerrar sentencia */
            $stmt1->close();
            
            if($url != '' && file_exists("../".$url))
            {
                unlink("../".$url);
            }
         }
         else
         {
            $message = "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
            $result = "ko";
            header("Location: " . $callback ."-edit.php?result=" . $result . "&mensaje=" . $message);
            exit();
        }
}