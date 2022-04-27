 <?php if ($_SESSION['user'] != 'admin@magazinonline.ro') {
            print 'Nu ai acces aici';
            return;
}
?>
<h1>Adauga produs</h1>
<form method="post" enctype="multipart/form-data">
    <table>
        <tr>
            <td><label for="denumire">Denumire</label></td>
            <td><input type="text" id="denumire" name="denumire"/></td>
        </tr>
        <tr>
            <td><label for="pret">Pret</label></td>
            <td><input type="text" id="pret" name="pret"/></td>
        </tr>
        <tr>
            <td><label for="img">Imagine</label></td>
            <td><input type="file" id="img" name="img"/></td>
        </tr>
        <tr>
            <td><input type="submit" name="adauga" value="Adauga"/></td>
        </tr>
    </table>
</form>
<?php

 $phpFileUploadedErrors =  array (0 => 'There is no error, the file uploaded with success',
    1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
    2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
    3 => 'The uploaded file was only partially uploaded',
    4 => 'No file was uploaded',
    6 => 'Missing a temporary folder',
    7 => 'Failed to write file to disk.',
    8 => 'A PHP extension stopped the file upload.',
);
if (isset($_POST['adauga'])) {
    $erori = [];
    $denumire = $_POST['denumire'];
    $pret = $_POST['pret'];
    if (strlen(trim($denumire))<3) {
        $erori[] = 'Denumirea trebuie sa aiba cel putin 3 caractere';
    }
    
    if (!is_numeric($pret)) {
        $erori[] = 'Pretul nu poate fi un text';
    }
    
    if (!empty($erori)) {
        print 'Au aparut niste erori: ';
        print '<ul>';
        foreach ($erori as $eroare) {
            print "<li> $eroare </li>";
        }
        print '</ul>';
        return; //opreste executia
    }
    
    
    if (isset($_FILES['img'])) {
        
        if ($_FILES['img']['error'] == 0) {
            switch ($_FILES['img']['type']) {
                case 'image/jpg':
                case 'image/jpeg':
                case 'image/png':
                case 'image/bmp':
                case 'image/gif':
                    $numeImagine = uniqid() . $_FILES['img']['name'];
                    $salvareServer = move_uploaded_file($_FILES['img']['tmp_name'], 'imagini/' . $numeImagine);
                    
                    if ($salvareServer) {
                        $salvareBD = adaugaProdus($denumire, $pret, $numeImagine);
                        if ($salvareBD) {
                            print 'Produs adaugat cu succes';
                        } else {
                            unlink('imagini/' . $numeImagine);
                            print 'Eroare la salvarea in  baza de date';
                        }
                    } else {
                        print 'Eroare la salvarea pe server';
                    }
                    break;
                default :
                    print "Fisierul nu are un format acceptat(jpg jpeg png bmp gif)";
                    break;
            }
        } else {
           if ( $_FILES['img']['error'] == 4) {
               $rezultatAdaugareProdus = adaugaProdus($denumire, $pret, NULL);
               if ($rezultatAdaugareProdus) {
                   print 'Produs adaugat cu succes';
               } else {
                   print 'Eroare la salvarea in baza de date';
               }
           } else { 
               print "A aparut o eroare " . $phpFileUploadedErrors[$_FILES['img']['error']];
           }
        }
    }
    
}
