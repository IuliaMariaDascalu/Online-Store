<?php
require_once 'functii/sql_functions.php';
session_start();

if (isset($_POST['conectare'])) {
    $email = $_POST['email_utilizator'];
    $parola = $_POST['pass'];
    $rezultateConectare = conectare($email, $parola);
 if ($rezultateConectare) {
     if (isset($_SESSION['fail_login'])) {
         unset($_SESSION['fail_login']);
     } 
     $_SESSION['user'] = $email; 
 } else {
     $_SESSION['fail_login'] = 'Eroare la conectare';
 }
    
}
if (isset($_GET['cos'])) {
    $id = $_GET['cos'];
    if (isset($_SESSION['cos'][$id])) {
        $_SESSION['cos'][$id]++;
    } else { 
        $_SESSION['cos'][$id] = 1;
    }
   
}

if (isset($_GET['sterge_cos'])) {
    $id = $_GET['sterge_cos'];
    if ($_SESSION['cos'][$id] > 1) {
        $_SESSION['cos'][$id]--;
    } else {
        unset($_SESSION['cos'][$id]);
    }
}
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="stylesheet" type="text/css" href="css/style.css"/>     
    </head>
    <body>
        <header id="banner"></header>
        <?php
        if (isset($_SESSION['user'])) {
            require_once 'templates/template_conectat.php';
        }else {
            require_once 'templates/template_neconectat.php';
        }
           ?>
    </body>
</html>
