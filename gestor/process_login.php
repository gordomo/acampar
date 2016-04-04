<?php
include_once '../includes/db_connect.php';
include_once '../includes/functions.php';
 
sec_session_start(); // Our custom secure way of starting a PHP session.

//die(var_dump($mysqli));
if (isset($_POST['email'], $_POST['password'])) 
{
    $email = $_POST['email'];
    $password = $_POST['password']; 
    $stmt = $mysqli->prepare("SELECT pass FROM usuarios WHERE email = ? LIMIT 1");
    $stmt->bind_param('s', $email);
    
    $stmt->execute();   // Execute the prepared query.
    $stmt->store_result();
    
    if ($stmt->num_rows == 1) 
    {
        // If the user exists get variables from result.
        $stmt->bind_result($passwordDB);
        $stmt->fetch();
        
        if ($password == $passwordDB) 
        {
            // Logged In!!!! 
            $_SESSION['user_login_checked'] = true;
            header('Location: index.php');
            exit();
        }
        else
        {
            // Not logged in 
            $_SESSION['user_login_checked'] =  false;
            header("Location: login.php?error=1");
        }
    }
    else 
    {
        // Not logged in 
        $_SESSION['user_login_checked'] =  false;
        header('Location: login.php?error=1');
    }
} 
else if (isset($_GET['logout'])) 
{
    $_SESSION['user_login_checked'] =  false;
    header('Location: login.php');
}
else 
{
    // The correct POST variables were not sent to this page. 
    echo 'Invalid Request';
}