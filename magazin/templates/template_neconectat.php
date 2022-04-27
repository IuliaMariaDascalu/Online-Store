<nav id="meniu">
    <ul>
        <li><a href="index.php">Conectare</a></li>
        <li><a href="index.php?page=1">Inregistrare</a></li>
    </ul>
</nav>
<!-- Conectare este pagina mea de home, pagina care se deschide cand intru in aplicatie
page este cheie -->
<section id="continut">

    <?php
   /* verificam  daca atributul page este setat in URL
page este un atribut din querystringil gasesc pe variabila superglobala $_GET
    * Daca page este setat stocam valoarea lui intr-o variabila
    *  */
    if (isset($_GET['page'])) {
        $page=$_GET['page'];
        if ($page == 1) {
            require_once 'pagini/neconectat/inregistrare.php';    
        } else {
            /*aici page are o valoare dar nu o valoare cunoscuta de aplicatia mea*/
        require_once 'pagini/eroare.php';  
        }
    } else {
        require_once 'pagini/neconectat/conectare.php'; 
    }
    
    ?>    
</section>