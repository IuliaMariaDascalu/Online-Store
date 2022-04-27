<?php
$sort = NULL;
//var_dump(preiaProduse());die();
    if (isset($_POST['sort'])) {
        $sort = $_POST['sortare'];
    }
    ?>
<h1>Lista produse</h1>
<form method="post">
    <input type="text" name="kw" placeholder="Cauta produse..."/>
    <input type="submit" name="cauta" value="Cauta"/>
</form>
<br>
<a href="index.php?page=2&reseteaza">Reseteaza cautarea</a>
<br>
<form method="post">
    <select name="sortare">
        <option value="pret ASC" <?php if ($sort == 'pret ASC') print 'selected';?>>Pret crescator</option>
        <option value="pret DESC" <?php if ($sort == 'pret DESC') print 'selected';?>>Pret descrecator</option>
    </select>
    <input type="submit" name="sort" value="sorteaza"/>
</form>

    <?php
   
if (isset($_GET['reseteaza'])) {
    
    setcookie('keyword', '', time()-1);
    $produse = preiaProduse($sort);
    
} else {
   if (isset($_POST['cauta'])) {
      $keyword = $_POST['kw'];
      $produse = preiaProduseDupaKeyword($keyword);
      setcookie('keyword', $keyword, time()+24*60*60);
    //am salvat in cookie keywordul
    
    } else if (isset($_COOKIE['keyword'])) {
        //nu am trimis cautare dar exista cookie
        $keyword = $_COOKIE['keyword']; //preiau valoarea din cookie
        $produse = preiaProduseDupaKeyword($keyword);

    }else {
        $produse = preiaProduse($sort);
    }
}

if (count($produse) == 0) {
    print 'Nu sunt produse in stoc';
    return;
}
?>

<table>
    <tr>
        <th>Imagine</th>
        <th>Denumire</th>
        <th>Pret</th>
        <th>Rating</th>
        <th></th>
        <th></th>
    </tr>
    <?php foreach ($produse as $produs) {
        $rating = preiaRatingMediuDupaIdProdus($produs['id']);
    ?>
    
               <!--iterez prin produse -->
               <tr>
                   <td>
                       <img width="50px" 
                            src="imagini/<?php print (!empty($produs['imagine'])) ? $produs['imagine'] : 'no-image-available.jpeg';?>"
                            onerror ="this.onerror=null; this.src='imagini/no-image-available.jpeg'"
                            />
                       <!--  folosim un cod de java script pentru cazul in care poza pusa pentru un produs nu este gasita pe server, iar in cazul asta sa se puna poza pentru no image-->
                            
                   </td>
                   <td>
                       <a href="index.php?page=produs&id=<?php print $produs['id'];?>"> <?php print $produs['denumire'];?></a>
                   </td>
                   <td>
                       <?php print $produs['pret'];?>
                   </td>
                   <td><?php print $rating['rating_mediu'] >0 ? round($rating['rating_mediu'], 2) : 'N/A';?></td>
                   <td><a href="index.php?page=2&cos=<?php print $produs['id'];?>">Adauga in cos</a></td>
                    <?php if ($_SESSION['user'] == 'admin@magazinonline.ro') {?>
                    <td><a href="index.php?page=2&sterge=<?php print $produs['id'];?>">Sterge</a></td>
                   <td><a href="index.php?page=2&editeaza=<?php print $produs['id'];?>">Editeaza</a></td>
                    <?php } ?>

                 <!-- Dam click pe editeaza, se pune in query string editeaza=id
                 iar in aceeasi pagina daca am pe get editeaza fac require once la pagina editeaza produs
                 -->  
               </tr>
    <?php } ?>
               <!--cosul de cumparaturi il salvez la nivel pe server(la nivel de sesiune), nu la nivel de BD pentru ca am multe update-uri, stergeri etc-->
      
</table>
<?php 
//var_dump(stergeProdusDupaId(9));
//die();

 if (isset($_GET['sterge']) && $_SESSION['user'] == 'admin@magazinonline.ro') {
     $id = $_GET['sterge'];
     $stergereBd = stergeProdusDupaId($id);
     if ($stergereBd) {
         //daca s-a sters vreau sa il sterg si din cos
         unset ($_SESSION['cos'][$id]);
         header ("location: index.php?page=2");
     } else {
         //daca nu s-a putut efectua stergerea ii dau un mesaj
        print 'Eroare la stergerea produsului'; 
     }
 }
 
 if (isset($_GET['editeaza']) && $_SESSION['user'] == 'admin@magazinonline.ro') {
     require_once 'pagini/conectat/editeaza_produs.php';
     
 }
 ?>