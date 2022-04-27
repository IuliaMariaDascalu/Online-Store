<?php

$user = preiaUtilizatorDupaEmail($_SESSION['user']);
$comenzi = preiaComenziDupaUserId($user['id']);

foreach ($comenzi as $index => $comanda) {
    print "Comanda #" . $comanda['id'] ." <br>";
    print $comanda['data_plasare'];
    print '<br>';
    $produse = json_decode($comanda['produse'], true);
    print 'Produse:<br>';
    print '<ol>';
    foreach ($produse as $produs) {
        print '<li>';
        print $produs['denumire'] . ' cantitate: ' . $produs['cantitate'] . ' pret/buc: ' . $produs['pret'];
        print '</li>';
    }
    print '</ol>';
    print '<hr>';
    
}
