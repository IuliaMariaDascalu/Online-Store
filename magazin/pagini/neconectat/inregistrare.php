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
if (isset($_POST['inregistrare'])) {
    $email = $_POST['email_utilizator'];
    $parola = $_POST['pass'];
     
    $rezultatInregistrare = inregistrareUtilizator($email, $parola);
    if ($rezultatInregistrare) {
    $_SESSION['user']=$email;//autologin
    $_SESSION['welcome']= "Salut, $email, te-ai inregistrat cu succes!";
    header('location:index.php');
    } else {
        print '<div style="color: red">Eroare la inregistrare</div>';
    }
}

