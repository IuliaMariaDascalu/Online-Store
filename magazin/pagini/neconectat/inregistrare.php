<h1>Inregistrare</h1>
<form method="post">
    <table>
        <tr>
            <td>Email</td>
            <td>
                <input type="email" name="email_utilizator"/>
            </td>
        </tr>
        <tr>
            <td>Parola</td>
            <td>
                <input type="password" name="pass"/>
            </td>
        </tr>
        <tr>
            <th colspan="2">
                <input type="submit" name="inregistrare" value="Inregistrare"/>
            </th>
        </tr>
    </table>
</form>
<?php
/*pentru prelucarea unui formular verificam daca s-a trimis formularul
 * verificam o cheie daca e setata
 */
if (isset($_POST['inregistrare'])) {
    $email = $_POST['email_utilizator'];
    $parola = $_POST['pass'];
    /*am salvat ce s-a trimis pe formular in doua variabile*/
     
    $rezultatInregistrare = inregistrareUtilizator($email, $parola);
    if ($rezultatInregistrare) {
       // print '<div style="color: green">Te-ai inregistrat cu succes</div>';
    $_SESSION['user']=$email;//autologin
    $_SESSION['welcome']= "Salut, $email, te-ai inregistrat cu succes!";
    header('location:index.php');
     //cand se inregistreaza se incarca templatul conectat
    } else {
        print '<div style="color: red">Eroare la inregistrare</div>';
    }
}

