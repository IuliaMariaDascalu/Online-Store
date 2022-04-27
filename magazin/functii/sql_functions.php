<?php
/*Conectarea la baza de date*/

function conectareBd($host = 'localhost',
                     $user = 'root',
                     $password='',
                     $database='magazin'
                     ) 
{
    return mysqli_connect($host, $user, $password, $database);
}

//verific daca adresa de email mai exista adresa de email in DB cand cineva incearca sa se inregistreze

function preiaUtilizatorDupaEmail($adresaEmail) 
{
    $link = conectareBd();
    //query preia date
    $query = "SELECT * FROM utilizator WHERE email = '$adresaEmail'";
    /*email- atributul din baza de date
     * adresaEmail-variabila trimisa in cadrul functiei
     */
    $result = mysqli_query($link, $query);//result set-structura specifica bazei de date
    //nu pot prelucra ca atare result set, asa ca il transformam in array
    $utilizator = mysqli_fetch_array($result, MYSQLI_ASSOC);
    
    return $utilizator;//daca nu gaseste user-> null eroare->false gaseste user->array

}

//avem nevoie de o functie care sa sanitizeze inputurile trimise de utilizator 

function clearData($input, $link)
{
    $input = trim($input);
    $input = htmlspecialchars($input);
    $input = stripslashes($input);
    $input = mysqli_real_escape_string($link, $input);

    return $input;
}

//functie pentru inregistrare 
//returneaza true - s-a facut inregistrarea cu succes
//false in 2 cazuri- exista deja email-ul sau a dat eroare insertul

function inregistrareUtilizator($email, $pass)
{
   $link = conectareBd();
   $email = clearData($email, $link);
   $pass = clearData($pass, $link);
   //encodare parola
   $pass = md5($pass);
   
   //verific daca exista un utilizator cu email-ul trimis
   $user = preiaUtilizatorDupaEmail($email);
   //array('status'=>false, 'msg'=>'exista deja adresa de email')
   if ($user) {
       return false;
   }
   
   $query = "INSERT INTO utilizator VALUES(NULL, '$email', '$pass')";
   //result=mysqli_query($link, $query)
   //daca result e false, returnez un array cu un mesaj similar cu cel de sus
   //daca e true returnez un array cu status true si un mesaj de succes
   return mysqli_query($link, $query);     
}

//functie de conectare(dupa ce validez datele, caut utilizatorul dupa email
//daca gasesc emailul in BD verific parola-daca este la fel ca cea din BD
// => credentiale corecte, utilizatoru redirectionat catre sectiunea pentru utilizator conectat
  function conectare ($email, $parola) 
  {
      
      $link = conectareBd();
      $email = clearData($email, $link);
      $parola = clearData($parola, $link);
      
      $user = preiaUtilizatorDupaEmail($email);
      
      if ($user) { //am gasit user, verific parola
         /* if (md5($pass) == $user['parola']) {
              return true;
          } else {
              return false;
          }*/
          //echivalent cu:
          return md5($parola) == $user['parola'];
          
      } 
      return false;
  }
       
  function actualizeazaParola ($email, $newPass)
  {
     $link = conectareBd();
     $newPass = clearData ($newPass, $link);
     $newPass = md5($newPass);
     
     $query = "UPDATE utilizator SET parola = '$newPass' WHERE email='$email'";
  
     return mysqli_query ($link, $query); //true sau false
     
  }

function adaugaProdus ($nume, $pret,$img)
{
    $link = conectareBd();
    $nume = clearData($nume, $link);
    $pret = clearData($pret, $link);
    $img = clearData($img, $link);
    
    $query = "INSERT INTO produs VALUES(NULL, '$nume', $pret, '$img')";
    
    return mysqli_query($link, $query);

}

//folosim fetch_all

function preiaProduse($sort = NULL)
{
    $link = conectareBd();
    $query = "SELECT * FROM produs ";
    if (!empty($sort)) {
        $query .= "ORDER BY $sort";
    }
    //var_dump($query);die();
    $rezultat = mysqli_query($link, $query);
    
    //obtinut un array de array-uri (fiecare array micut contine datele unei inregistrari)
    
    $produse = mysqli_fetch_all($rezultat, MYSQLI_ASSOC);
    
    return $produse;
}
//id este salvat pe sesiune
function preiaPordusDupaId ($id)
{
    $link = conectareBd();
    $id = clearData($id, $link);
    
    $query = "SELECT * FROM produs WHERE id='$id'";
    
    $rezultat = mysqli_query($link, $query);
    $produs = mysqli_fetch_array($rezultat, MYSQLI_ASSOC);

    return $produs;
}

//stergere produse din lista produse
function stergeProdusDupaId ($id) 
{
    $link = conectareBd();
    $id = clearData($id, $link);
    
    $query = "DELETE FROM produs WHERE id='$id'";
    
    return mysqli_query($link, $query);
}

function editeazaProdus ($id, $den, $pret, $img=NULL) 
{
    $link = conectareBd();
    $den = clearData($den, $link);
    $pret = clearData($pret, $link);
    $img = clearData($img, $link);
    
    if (!empty($img)) {
    $query = "UPDATE produs SET denumire = '$den', pret=$pret, imagine='$img' WHERE id = '$id'";
    } else {
        $query = "UPDATE produs SET denumire = '$den', pret=$pret WHERE id = '$id'";
    }
    return mysqli_query($link, $query);
}

function preiaProduseDupaKeyword ($keyword)
{
    $link = conectareBd();
    $keyword = clearData($keyword, $link);
    
    $query = "SELECT * FROM produs WHERE denumire LIKE '%$keyword%'";
    //%- wildcard (0 sau oricate caractere)
    //LIKE '%$keyword%' = denumirea trebuie sa contina keywordul
    //LIKE '%$laptop%' => laptop/un laptop/laptop lenovo/un laptop lenovo etc
    
    $rez = mysqli_query($link, $query);
    $produse = mysqli_fetch_all($rez, MYSQLI_ASSOC);
    
    return $produse;
}

function adaugaComanda ($userId, $data, $produse)
{
    $link = conectareBd();
    //datele vin din cosul de cumparaturi
    //o sa am un buton ,,finalizeaza comanda"
    //datele din cos sunt salvate pe sesiune, nu mai trebuie sanitizate inputurile
    
    $query = "INSERT INTO comanda VALUES(NULL, $userId, '$data', '$produse')";
    //var_dump($query);die();
    return mysqli_query($link, $query);
}
//o functie care preia comenzile dupa id utilizator

function preiaComenziDupaUserId ($userId) {
    $link = conectareBd();
    $query = "SELECT * FROM comanda WHERE id_user = $userId ORDER BY data_plasare DESC";
    $rezultat = mysqli_query($link, $query);
    
    $comenzi = mysqli_fetch_all($rezultat, MYSQLI_ASSOC);
    return $comenzi;
}
function adaugaReview ($userId, $produsId, $nota,  $textReview = NULL) 
{
    $link = conectareBd();
    $nota = clearData($nota, $link);
    $textReview = clearData($textReview, $link);
    
    $query = "INSERT INTO  review VALUES(NULL,'$textReview', $nota, $produsId, $userId) ";
    return mysqli_query($link, $query);
}

function preiaReviewDupaIdProdus ($idProdus) 
{
    $link = conectareBd();
    $query = "SELECT * FROM review WHERE id_produs = $idProdus";
    
    $rezultat = mysqli_query($link, $query);
    $reviews = mysqli_fetch_all($rezultat, MYSQLI_ASSOC);
    
    return $reviews;
}

function preiaUtilizatorDupaId($idUser) 
{
    $link = conectareBd();
    
    $query = "SELECT * FROM utilizator WHERE id = $idUser ";
    
    $result = mysqli_query($link, $query);
    $utilizator = mysqli_fetch_array($result, MYSQLI_ASSOC);
    
    return $utilizator;

}

function preiaRatingMediuDupaIdProdus($idProdus) 
{
    $link = conectareBd();
    $query = "SELECT AVG(rating) AS rating_mediu FROM review WHERE id_produs = $idProdus";
    //o sa folosesc cheia rating_mediu
    
    $result = mysqli_query($link, $query);
    $rating = mysqli_fetch_array($result, MYSQLI_ASSOC);
    
    return $rating;
}
