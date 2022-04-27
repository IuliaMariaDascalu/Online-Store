<h1>Cos cumparaturi</h1>
<br>
<?php

if(!isset($_SESSION['cos']) || empty($_SESSION['cos'])) {
    print 'Cosul tau este gol';
    return;
}


?>
<table>
    <tr>
        <th>Denumire</th>
        <th>Pret</th>
        <th>Cantitate</th>
        <th></th>
    </tr>
    <?php 
    $total = 0;
    foreach ($_SESSION['cos'] as $idProdus => $cantitate) {   
    $produs = preiaPordusDupaId($idProdus);
    $total = $total + $produs['pret']* $cantitate;
    
?>
    <tr>
        <td><?php print $produs['denumire']; ?></td>
        <td><?php print $produs['pret']; ?></td>
        <td><?php print $cantitate; ?></td>
        <td><a href="index.php?page=3&sterge_cos=<?php print $idProdus; ?>">Sterge</a></td>
    </tr>
    <?php } ?>
</table>
<br>
<br>
<h3><i><?php print "Pret total: $total" ;?></i></h3>


<a href="index.php?page=thank-you">Finalizeaza comanda</a>
