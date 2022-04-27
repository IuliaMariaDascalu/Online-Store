<h1> My profile</h1>

 <?php
 $user = preiaUtilizatorDupaEmail($_SESSION['user']);
 print "Adresa email: " . $user['email'];
 print "<br>";
 print "<h3>Schimba parola</h3>";
 ?>
<form method="post">
    
    Parola actuala <input type="password" name="actuala"/> <br>
    Parola noua <input type="password" name="noua"/><br>
    <input type="submit" name="schimba" value="schimba"/>

</form>
<?php
if (isset($_POST['schimba'])) {
    $oldPass = $_POST['actuala'];
    $newPass = $_POST['noua'];
    
    
    if (md5($oldPass) == $user['parola']) {
     $rezultatActualizare = actualizeazaParola($_SESSION['user'], $newPass);
   
     print $rezultatActualizare ? 'Parola actualizata cu succes' : 'Eroare la actualizare';
    } else{
        print 'Parola actuala nu este corecta!';
    }
        
}
