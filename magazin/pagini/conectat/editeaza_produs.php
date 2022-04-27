<?php

$idProdus = $_GET['editeaza'];
//verificare: print "produs de editat: $idProdus";
//editeaza produs este incarcat in lista produse cand editeaza este setat in url

//am nevoie de toate datele despre produs

$produs = preiaPordusDupaId($idProdus);
//var_dump($produs);
?>

<form method="post" enctype="multipart/form-data">
    <table>
        <tr>
            <td><label for="denumire" >Denumire</label></td>
            <td><input type="text" id="denumire" name="denumire" value="<?php print $produs['denumire'];?>"/></td>
        </tr>
        <tr>
            <td><label for="pret">Pret</label></td>
            <td><input type="text" id="pret" name="pret" value="<?php print $produs['pret'];?>"/></td>
        </tr>
        <tr>
            <td>
                <img width="150px" 
                src="imagini/<?php print (!empty($produs['imagine'])) ? $produs['imagine'] : 'no-image-available.jpeg';?>"
                onerror ="this.onerror=null; this.src='imagini/no-image-available.jpeg'"
                />
                       
                            
            </td>
        </tr>
        <tr>
            <td><label for="img">Imagine</label></td>
            <td><input type="file" id="img" name="img"/></td>
        </tr>
        <tr>
            <td><input type="submit" name="edit" value="Edit"/></td>
        </tr>
    </table>
</form>
<!--imaginea o sa se modifice doar daa este trimisa una noua, daca nu se trimite nimic atunci ramane imaginea care este
 deja pusa -->

<?php
if (isset($_POST['edit'])) {
    $denumire = $_POST['denumire'];
    $pret = $_POST['pret'];
    
    if (isset($_FILES['img'])) {
        if ($_FILES['img']['error'] == 0) {
            //continui cu prelucrarea imaginii-salvez noua imagine
            //verificam daca fisierul este o imagine-are format acceptat
           //1.validez tipul fisierului
           //2.ii asociem fisierului un nume unic
           //3.trebuie mutat din tmp intr-un folder- il salvez pe server
           //4.salvam numele fisierului in baza de date
                    
            switch ($_FILES['img']['type']) {
                case 'image/jpg';
                case 'image/jpeg';
                case 'image/png';
                case 'image/bmp';
                case 'image/gif';
                    //salvam imaginea
                    $numeImg = uniqid() . $_FILES['img']['name'];
                    $salvarePeServer = move_uploaded_file($_FILES['img']['tmp_name'], 'imagini/'. $numeImg);
                    
                    if($salvarePeServer) {
                        $editeazaBD = editeazaProdus($idProdus, $denumire, $pret, $numeImg);
                        if ($editeazaBD) {
                            //sterg imaginea veche de pe server pe care o avea produsul
                           if (!empty ($produs['imagine'])) {
                            unlink('imagini/' . $pordus['imagine']);
                           }
                            print 'Produs editat cu succes!';
                            header ("location: index.php?page=2");
                        } else {
                            unlink ('imagini/' . $numeImg);
                            print 'Nu s-a putut edita produsul!';
                        }
                        
                    } else {
                      
                        print 'Nu s-a putut salva pe server';
                    }
                                       
                    break;
                default:
                    print 'Fisierul selectat nu are un format acceptat';
                    break;
            }
        } else if ($_FILES['img']['error'] ==4 ){
            //editez produs dar nu schimb imaginea
            $editareProdus = editeazaProdus($idProdus, $denumire, $pret);
            if ($editareProdus) {
                //fac un refresh la pagina
                header("location: index.php?page=2");
            } else {
                print 'Eroare la editarea produsului';
            }
        } else {
            print 'Eroare la salvarea imaginii';
        }
            
    }
}



















