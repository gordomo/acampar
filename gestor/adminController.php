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
//################################## CALENDARIO FIN ############################################
}
