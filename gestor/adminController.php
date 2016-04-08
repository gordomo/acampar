<?php

include_once '../includes/db_connect.php';
include_once '../includes/resizeImage.php';

header("Content-Type: text/html;charset=utf-8");
$action = isset($_POST['action']) ? $_POST['action'] : $_GET['action'];

switch ($action) {
//    ##############FOTOS INICIO########################
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
        $descCorta = $_POST['descCorta'];
        $desc = $_POST['desc'];
        $lat = $_POST['lat'];
        $long = $_POST['long'];
        $categoria = $_POST['categoria'];
        $padre = $_POST['padre'];
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
                    header("Location: tours-edit.php?result=" . $result . "&mensaje=" . $message);
                }

                $pos = strpos($_FILES['photo']['type'], "image");
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
                    $ruta = '../img/categorias/'.$new_file_name;
                    //ruta de los thumbs

                    move_uploaded_file($_FILES['photo']['tmp_name'], $ruta);
                    
                    $ruta = substr($ruta, 3);
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
                $message = 'Ooops!  Your upload triggered the following error:  '.$_FILES['photo']['error'];
                $result  = "ko";
                header("Location: tours-edit.php?result=" . $result . "&mensaje=" . $message);
            }
        }
        
        // prepare and bind
        if ($stmt = $mysqli->prepare("INSERT INTO categorias (`nombre`, `foto`, `descripcion_corta`, `descripcion`, `id_tour`, `cat_superior`, `lat`, `long`) values (?, ?, ?, ?, ?, ?, ?, ?)")) 
        {
            $stmt->bind_param("ssssiiss", $nombre, $ruta, $descCorta, $desc, $categoria, $padre, $lat, $long);

            if (!$stmt->execute()) 
            {
                $message = "Falló la ejecución: (" . $stmt->errno . ") " . $stmt->error;
                $result = "ko";
                header("Location: tours-edit.php?result=" . $result . "&mensaje=" . $message);
            }

            $stmt->close();
        } 
        else
        {
            $message = "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
            $result  = "ko";
            header("Location: tours-edit.php?result=" . $result . "&mensaje=" . $message);
        }
        
        header("Location: tours-edit.php?result=" . $result . "&mensaje=" . $message);
        break;
    
    
    case 'eliminarEntradaCategorias':

        $result = "ok";
        $message = "Categoria eliminada correctamente";
        $id = $_POST['id'];
        
        if ($stmt1 = $mysqli->prepare("SELECT * FROM categorias WHERE cat_superior = ?")) 
        {
            
            $stmt1->bind_param("s", $id);

            if (!$stmt1->execute())
            {
                $message = "Falló la ejecución: (" . $stmt1->errno . ") " . $stmt1->error;
                $result = "ko";
            }
            
            $stmt1->store_result();
            
            if(!$stmt1->num_rows)
            {
                if ($stmt2 = $mysqli->prepare("DELETE FROM categorias WHERE id = ?")) 
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
            else
            {
                $message = "El tour que intenta borrar tiene tours hijos asociados. Elimine primero todos estos tours antes de borrar al padre";
                $result = "ko";
            }
            
            $stmt1->close();
        }
        else
        {
            $message = "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
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
        $borrarFoto = (isset($_POST['borrarFoto']) && $_POST['borrarFoto'] !== '') ? $_POST['borrarFoto'] : false;
        
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
                    header("Location: tours-edit.php?result=" . $result . "&mensaje=" . $message);
                }

                $pos = strpos($_FILES['photo']['type'], "image");
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
                    $ruta = '../img/categorias/'.$new_file_name;
                    //ruta de los thumbs

                    move_uploaded_file($_FILES['photo']['tmp_name'], $ruta);
                    
                    $ruta = substr($ruta, 3);
                    
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
                $message = 'Ooops!  Your upload triggered the following error:  '.$_FILES['photo']['error'];
                $result  = "ko";
                header("Location: tours-edit.php?result=" . $result . "&mensaje=" . $message);
            }
        }
        
        // prepare and bind
        if ($stmt = $mysqli->prepare("UPDATE categorias SET `nombre` = ?, `foto` = ?, `descripcion_corta` = ?, `descripcion` = ?, `id_tour` = ?, `cat_superior` = ?, `lat` = ?, `long` = ? WHERE id = ?")) 
        {
            $stmt->bind_param("ssssiissi", $nombre, $ruta, $descCorta, $desc, $categoria, $padre, $lat, $long, $id);

            if (!$stmt->execute()) 
            {
                $message = "Falló la ejecución: (" . $stmt->errno . ") " . $stmt->error;
                $result = "ko";
                header("Location: tours-edit.php?result=" . $result . "&mensaje=" . $message);
            }

            $stmt->close();
        } 
        else
        {
            $message = "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
            $result  = "ko";
            header("Location: tours-edit.php?result=" . $result . "&mensaje=" . $message);
        }
        
        header("Location: tours-edit.php?result=" . $result . "&mensaje=" . $message);
        break;    
        
//################################## CATEGORIAS FIN #########################################
}
