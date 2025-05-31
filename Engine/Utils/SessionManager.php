<?php /*VERIFICA SI EL USUARIO ESTA AUTENTICADO*/
session_start();

   if(isset($_SESSION['timeAccess']) || isset($_SESSION['authuser'])) {

    $inactive = 600; //10 min
    $sessionLife = time() - $_SESSION['timeAccess'];

        if($sessionLife > $inactive)
        {
            session_unset();
            session_destroy();              
            header("Location: ../index.php");
            exit();
        }
       
    }else{
        header('location: ../index.php');
        exit();
    }
$_SESSION['timeAccess'] = time();
?>