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
/* verificare functie din sql_functions
var_dump(adaugaProdus('produs de test', 85, 'test.jpg'));
 * */
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
    
    //pret este de tip text, validez ca pret trebuie sa aiba valoare numerica
    
    if (!is_numeric($pret)) {
        $erori[] = 'Pretul nu poate fi un text';
    }
    
    //verific daca am erori, daca au aparut nu mai merg mai departe la validare fisier
    if (!empty($erori)) {
        print 'Au aparut niste erori: ';
        print '<ul>';
        foreach ($erori as $eroare) {
            print "<li> $eroare </li>";
        }
        print '</ul>';
        return; //opreste executia
    }
    
   // verificare: print 'am trecut de if';
    
    //fisierele au o variabila dedicata $_FILES
    
    if (isset($_FILES['img'])) {
        
        if ($_FILES['img']['error'] == 0) {//am un fisier si nu am nicio eroare
            //verific ca s-a adaugat o imagine si nu alt tip de fisier
            //accespt jpg jpeg bmp gif png
            //validam tipul fisierului
            switch ($_FILES['img']['type']) {
                case 'image/jpg':
                case 'image/jpeg':
                case 'image/png':
                case 'image/bmp':
                case 'image/gif':
                    //img in format acceptat, incerc sa o salvez
                    //$_FILES['img']['name']- nume_din_pc_user.jpg
                    //daca doi utilizatori folosesc poze cu acelasi nume prima poza pusa va fi inlocuita cu cea incarcata a doua oara care are acelasi nume
                    // concatenam la inceput un id unic fiecarei imagini pentru a ne asigura ca imaginea are un nume unic
                    //pastram name-ul pt ca are si extensia 
                    $numeImagine = uniqid() . $_FILES['img']['name'];
                    //salvam pe server prima data - mut din folderul temp in alt folder definit de noi
                    //daca avem mai multe categorii de imagini de exemplu putem face mai multe foldere
                    //folosim move_uploaded_file pentru a muta
                    $salvareServer = move_uploaded_file($_FILES['img']['tmp_name'], 'imagini/' . $numeImagine);
                //                                      pe cine mut                  unde mut
                    
                    if ($salvareServer) {
                        //merg mai departe si salvez in BD
                        $salvareBD = adaugaProdus($denumire, $pret, $numeImagine);
                        if ($salvareBD) {
                            print 'Produs adaugat cu succes';
                        } else {
                            //stergerea unui fisier
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
               //niciun fisier, adaug produs fara imagine
               $rezultatAdaugareProdus = adaugaProdus($denumire, $pret, NULL);
               if ($rezultatAdaugareProdus) {
                   print 'Produs adaugat cu succes';
               } else {
                   print 'Eroare la salvarea in baza de date';
               }
           } else { //a aparut o eroare, alta decat 0 sau 4- no file
               print "A aparut o eroare " . $phpFileUploadedErrors[$_FILES['img']['error']];
           }
        }
    }
    
}