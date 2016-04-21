<?php
include_once 'db_connect.php';
include_once 'functions.php';

switch ($_POST['option']){
   
    case "get_categorias_hijas":
        if(isset($_POST['id_categoria']) && $_POST['id_categoria'] != '')
        {
            echo json_encode(getCategorias($mysqli, "", $_POST['id_categoria']), true);
            exit();
            
        }
    break;
    
    case "get_info_categoria":
        if(isset($_POST['id_categoria']) && $_POST['id_categoria'] != ''){
            $resultado = getInfoCategoria($mysqli, $_POST['id_categoria']);
            if($resultado['result'] == 'true'){
                $retorno = array('result' => true, 'categoria'=>$resultado['categoria']);
            }else{
                $retorno = array('result' => false, 'mensaje' => $resultado['mensaje']);
            }
        }else{
            $retorno = array('result' => false, 'mensaje' => 'Error al seleccionar la categoria');
        }
        echo json_encode($retorno);
    break;
	
    case "get_categorias_inf":
	if(isset($_POST['cat_superior']) && $_POST['cat_superior'] != ''){
            $resultado = getCategorias($mysqli, '', $_POST['cat_superior']);
            if($resultado['result'] == 'true'){
                $retorno = array('result' => true, 'categorias'=>$resultado['categorias']);
            }else{
                $retorno = array('result' => false, 'mensaje' => $resultado['mensaje']);
            }
        }else{
            $retorno = array('result' => false, 'mensaje' => 'Error al seleccionar las categorias');
        }
        echo json_encode($retorno);
    break;
	
	case "enviar_consulta":
		if(isset($_POST['nombre']) && $_POST['nombre'] != '' && isset($_POST['email']) && $_POST['email'] != '' && isset($_POST['phone']) && $_POST['phone'] != ''){
			if(!filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)){
				$retorno = array('result' => false, 'mensaje' => "Email no valido.");
			}else{
				$nombre = $_POST['nombre'];
				$email = $_POST['email'];
				$phone = $_POST['phone'];
				$consulta = $_POST['consulta'];
				
				$categoria = getInfoCategoria($mysqli, $_POST['categoria']);
				$to = 'consultas@acampartrek.com';
				$email_subject = "Nueva consulta en el tour: {$categoria['categoria']['nombre']}";
				$email_body = "Datos del contacto:\n\n"."Nombre: {$nombre}\nEmail: {$email}\nTelefono: {$phone}\nConsulta:\n{$consulta}";
				$headers = "From: {$email}";
				
				if(mail($to,$email_subject,$email_body,$headers)){
					$retorno = array('result' => true, 'mensaje'=>"Mail enviado correctamente! Nos comunicaremos a la brevedad");
				}else{
					$retorno = array('result' => false, 'mensaje' => "Error al enviar el mail");
				}
			}
        }else{
            $retorno = array('result' => false, 'mensaje' => 'Faltan completar datos');
        }
        echo json_encode($retorno);
	break;
	
	case "enviar_consulta_index":
		if(isset($_POST['nombre']) && $_POST['nombre'] != '' && isset($_POST['email']) && $_POST['email'] != '' && isset($_POST['phone']) && $_POST['phone'] != '' && isset($_POST['consulta']) && $_POST['consulta'] != ''){
			if(!filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)){
				$retorno = array('result' => false, 'mensaje' => "Email no válido.");
			}else{
				$nombre = $_POST['nombre'];
				$email = $_POST['email'];
				$phone = $_POST['phone'];
				$consulta = $_POST['consulta'];
				
				$to = 'consultas@acampartrek.com';
				$email_subject = "Nueva consulta desde el formulario de contacto";
                                
                                $email_body = "";
                                
                                if($_POST['id_cat']){
                                    $categoria = getInfoCategoria($mysqli, $_POST['id_cat']);
                                    $email_subject = "Nueva consulta sobre un tour";
                                    $email_body = "Consulta sobre el tour: ".$categoria['categoria']['nombre']."\n\n";
                                }
                                
				$email_body .= "Datos del contacto:\n\n"."Nombre: {$nombre}\nEmail: {$email}\nTelefono: {$phone}\nConsulta:\n{$consulta}";
				$headers = "From: {$email}";
				
				if(mail($to,$email_subject,$email_body,$headers)){
					$retorno = array('result' => true, 'mensaje'=>"Mail enviado correctamente! Nos comunicaremos a la brevedad");
				}else{
					$retorno = array('result' => false, 'mensaje' => "Error al enviar el mail");
				}
			}
        }else{
            $retorno = array('result' => false, 'mensaje' => 'Faltan completar datos');
        }
        echo json_encode($retorno);
	break;
	
}

?>