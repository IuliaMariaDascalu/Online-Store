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

function preiaUtilizatorDupaEmail($adresaEmail) 
{
    $link = conectareBd();
    $query = "SELECT * FROM utilizator WHERE email = '$adresaEmail'";
    $result = mysqli_query($link, $query);
    $utilizator = mysqli_fetch_array($result, MYSQLI_ASSOC);
  
    return $utilizator;//daca nu gaseste user-> null eroare->false gaseste user->array

}


function clearData($input, $link)
{
    $input = trim($input);
    $input = htmlspecialchars($input);
    $input = stripslashes($input);
    $input = mysqli_real_escape_string($link, $input);

    return $input;
}


function inregistrareUtilizator($email, $pass)
{
   $link = conectareBd();
   $email = clearData($email, $link);
   $pass = clearData($pass, $link);
   //encodare parola
   $pass = md5($pass);
   
   //verific daca exista un utilizator cu email-ul trimis
   $user = preiaUtilizatorDupaEmail($email);
   if ($user) {
       return false;
   }
   $query = "INSERT INTO utilizator VALUES(NULL, '$email', '$pass')";
   return mysqli_query($link, $query);     
}

  function conectare ($email, $parola) 
  {
      
      $link = conectareBd();
      $email = clearData($email, $link);
      $parola = clearData($parola, $link);
      
      $user = preiaUtilizatorDupaEmail($email);
      
      if ($user) { 
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

function preiaProduse($sort = NULL)
{
    $link = conectareBd();
    $query = "SELECT * FROM produs ";
    if (!empty($sort)) {
        $query .= "ORDER BY $sort";
    }
    $rezultat = mysqli_query($link, $query);
    
    $produse = mysqli_fetch_all($rezultat, MYSQLI_ASSOC);
    
    return $produse;
}

function preiaPordusDupaId ($id)
{
    $link = conectareBd();
    $id = clearData($id, $link);
    
    $query = "SELECT * FROM produs WHERE id='$id'";
    
    $rezultat = mysqli_query($link, $query);
    $produs = mysqli_fetch_array($rezultat, MYSQLI_ASSOC);

    return $produs;
}


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
    
    $rez = mysqli_query($link, $query);
    $produse = mysqli_fetch_all($rez, MYSQLI_ASSOC);
    
    return $produse;
}

function adaugaComanda ($userId, $data, $produse)
{
    $link = conectareBd();
    
    $query = "INSERT INTO comanda VALUES(NULL, $userId, '$data', '$produse')";
    //var_dump($query);die();
    return mysqli_query($link, $query);
}

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
