<?php

$idProdus = $_GET['editeaza'];
$produs = preiaPordusDupaId($idProdus);
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

<?php
if (isset($_POST['edit'])) {
    $denumire = $_POST['denumire'];
    $pret = $_POST['pret'];
    
    if (isset($_FILES['img'])) {
        if ($_FILES['img']['error'] == 0) {        
            switch ($_FILES['img']['type']) {
                case 'image/jpg';
                case 'image/jpeg';
                case 'image/png';
                case 'image/bmp';
                case 'image/gif';
                    
                    $numeImg = uniqid() . $_FILES['img']['name'];
                    $salvarePeServer = move_uploaded_file($_FILES['img']['tmp_name'], 'imagini/'. $numeImg);
                    
                    if($salvarePeServer) {
                        $editeazaBD = editeazaProdus($idProdus, $denumire, $pret, $numeImg);
                        if ($editeazaBD) {
                           
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
            
            $editareProdus = editeazaProdus($idProdus, $denumire, $pret);
            if ($editareProdus) {
                header("location: index.php?page=2");
            } else {
                print 'Eroare la editarea produsului';
            }
        } else {
            print 'Eroare la salvarea imaginii';
        }
            
    }
}
