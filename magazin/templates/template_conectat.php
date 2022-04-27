<nav id="meniu">
    <ul>
        <li><a href="index.php">Profil</a></li>
        <?php if ($_SESSION['user'] == 'admin@magazinonline.ro') {?>
        <li><a href="index.php?page=1">Adauga produs</a></li>
        <?php } ?>
        <li><a href="index.php?page=2">Lista produse</a></li>
        <li><a href="index.php?page=3">Cos cumparaturi</a></li>
        <li><a href="index.php?page=4">Comenzile mele</a></li>
        <li><a href="index.php?logout">Logout</a></li>
    </ul> 
</nav>
<?php
//daca logout este setat pe GET fac session_destroy si un redirect catre index

if (isset($_GET['logout'])) {
    session_destroy();
    header("location:index.php");//redirectionare    
}

if (isset($_SESSION['welcome'])) {
    print $_SESSION['welcome'];//il afisez doar prima oara cand ajung aici din login
    unset($_SESSION['welcome']);   
}
if (isset($_GET['page'])) {
    $page = $_GET['page'];
    switch ($page) {
        case 1:
            require_once 'pagini/conectat/adauga_produs.php';
            break;
        case 2:
            require_once 'pagini/conectat/lista_produse.php';
            break;
        case 3:
            require_once 'pagini/conectat/cos_cumparaturi.php';
            break;
        case 4:
            require_once 'pagini/conectat/comenzi.php';
            break;
        case 'thank-you':
            require_once 'pagini/conectat/thankyou.php';
            break;
        case 'produs':
            require_once 'pagini/conectat/produs.php';
            break;
        default:
             require_once 'pagini/eroare.php';
            break;
    }
} else {
    require_once 'pagini/conectat/profil.php';
}


