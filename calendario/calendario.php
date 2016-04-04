<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

$action = $_POST['action'];


switch ($action)
{
    case 'getActividadesDelMes':
    
        $mes = $_POST['mes'];
        
        echo getCalendario($mes);
        
    break;
}