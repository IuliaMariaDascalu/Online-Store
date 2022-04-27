<?php
if (!isset($_SESSION['cos'])) {
    print '<h1>Nu ai avut produse in cos</h1>';
    print '<h3><a href="idex.php?page=2"> <--Inapoi la lista de produse</a></h3>';
    return;
}

$user = preiaUtilizatorDupaEmail($_SESSION['user']);
$cos = $_SESSION['cos'];

$produse=[];

foreach ($cos as $idProdus => $cantitate) {
   $produs = preiaPordusDupaId($idProdus);
   $detaliiProdus['denumire'] = $produs['denumire'];
   $detaliiProdus['pret'] = $produs['pret'];
   $detaliiProdus['cantitate'] = $cantitate;
   
   $produse[] = $detaliiProdus; 
}

$comanda = adaugaComanda($user['id'], date('Y-m-d'), json_encode($produse));
if ($comanda) {
    unset($_SESSION['cos']);
    ?>
<h1>Multumim pentru comanda!</h1>
<br>
<h3><a href="idex.php?page=2"> <--Inapoi la lista de produse</a></h3>
<br>
<h3><a href="index.php?page=4">Comenzile mele</a></h3>
    
<?php
} else {
 ?>
<h1>A aparut o eroare la procesarea comenzii</h1>
<br>
<h3><a href="index.php?page=3"> <--Inapoi la cos</a></h3>
    
<?php
}
?>
