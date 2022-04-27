<?php
require_once 'functii/sql_functions.php';
//var_dump(preiaUtilizatorDupaEmail('test@yahoo.ro'));
session_start();

if (isset($_POST['conectare'])) {
    $email = $_POST['email_utilizator'];
    $parola = $_POST['pass'];
    //salvam pe sesiune datele utilizatorului sau un mesaj de eroare in functie de rezultatul conectarii
    $rezultateConectare = conectare($email, $parola);
 if ($rezultateConectare) {
     //daca introduc prima data credentiale gresite pe sesiune se seteaza fail login, iar daca  a doua oara ma conectez 
     //corect o sa se adauge si credetialele pe sesiune 
     //nu vreau sa le am pe amandoua pe sesiune, dupa conectarea corecta vreau ca fail login sa dispara de pe sesiune
     if (isset($_SESSION['fail_login'])) {
         unset($_SESSION['fail_login']);
     } 
     $_SESSION['user'] = $email; 
 } else {
     $_SESSION['fail_login'] = 'Eroare la conectare';
 }
    
}
//adaugare produse cos
//daca am in url cheia cos adaug produsul in cos
if (isset($_GET['cos'])) {
    $id = $_GET['cos'];
    //daca produsul este deja in cos si il mai adaug o data cresc cantitatea
    //$_SESSION['cos']['<id_produs>'] => produsul 3, cu camtitatea 7 :$_SESSION['cos'][3]=7
    if (isset($_SESSION['cos'][$id])) {
        $_SESSION['cos'][$id]++;
    } else { //daca nu il am in cos, il adaug cu cantitatea 1
        $_SESSION['cos'][$id] = 1;
    }
   
}
//stergere din cos cumparaturi

if (isset($_GET['sterge_cos'])) {
    $id = $_GET['sterge_cos'];
    //daca a, o cantitate >1 voi scadea cantitatea cu 1, daca cantitatea = 1 sterg produsul din cos
    if ($_SESSION['cos'][$id] > 1) {
        $_SESSION['cos'][$id]--;
    } else {
        unset($_SESSION['cos'][$id]);//l-am scos din sesiune
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
        
   //verificam daca utilizatorul este setat pe sesiune, daca este o sa incarc template conectate, altfel incarc template neconectat
        if (isset($_SESSION['user'])) {
            require_once 'templates/template_conectat.php';
        }else {
            require_once 'templates/template_neconectat.php';
        }
           ?>
    </body>
</html>