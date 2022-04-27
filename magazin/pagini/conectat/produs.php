<?php
$idProdus = $_GET['id'];
$produs = preiaPordusDupaId($idProdus);
$rating = preiaRatingMediuDupaIdProdus($idProdus);
print "<h1>" . $produs['denumire'] . " -Rating:" . round($rating['rating_mediu'], 2). "</h1>";
print "<h3> PRET: " . $produs['pret'] . "</h3>";
print'<br>';
?>
<img width="300px" 
        src="imagini/<?php print (!empty($produs['imagine'])) ? $produs['imagine'] : 'no-image-available.jpeg';?>"
        onerror ="this.onerror=null; this.src='imagini/no-image-available.jpeg'"
/>
<br>
<?php
//var_dump(preiaReviewDupaIdProdus($idProdus));
$reviews = preiaReviewDupaIdProdus($idProdus);

foreach ($reviews as $review) {
    if (empty($review['continut'])) {
        continue; //merg la urm element din arrayul reviews
        
    }
    $user = preiaUtilizatorDupaId($review['id_user']);
    print '<h3>' . $user['email'] . '</h3>';
    print '<p><i>' . $review['continut'] . '</i></p>';
    print '<hr>';
}
    
?>

<br><br><br>
<h2>Adauga review</h2>
<form method="post">
    <input type="radio" name="nota" value="1"/>1
    <input type="radio" name="nota" value="2"/>2
    <input type="radio" name="nota" value="3"/>3
    <input type="radio" name="nota" value="4"/>4
    <input type="radio" name="nota" value="5"/>5
    <br>
    <textarea cols="50" rows="10" name="review" placeholder="Adauga un review..."></textarea>
    <br>
    <input type="submit" name="add_review" value="Adauga"/>
</form>
<?php
if (isset($_POST['add_review'])) {
    $nota = $_POST['nota'];
    $textReview = $_POST['review'];
    $user = preiaUtilizatorDupaEmail($_SESSION['user']);
    $adaugaReview = adaugaReview($user['id'], $idProdus, $nota, $textReview);
    
    if ($adaugaReview) {
        header("location:index.php?page=produs&id=$idProdus");
        
    } else {
        print 'Eroare la adugare review';
    }
    
}
?>







