<?php

$user = preiaUtilizatorDupaEmail($_SESSION['user']);
//var_dump(preiaComenziDupaUserId($user['id']));
$comenzi = preiaComenziDupaUserId($user['id']);

foreach ($comenzi as $index => $comanda) {
   // var_dump($comanda);die();
    print "Comanda #" . $comanda['id'] ." <br>";
    print $comanda['data_plasare'];
    print '<br>';
    $produse = json_decode($comanda['produse'], true);//a redevenit array
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