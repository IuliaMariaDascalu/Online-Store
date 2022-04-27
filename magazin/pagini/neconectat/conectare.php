<h1>Conectare</h1>
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
                <input type="submit" name="conectare" value="Conectare"/>
            </th>
        </tr>
    </table>
</form>
<?php

if (isset($_SESSION['fail_login'])) {
    print $_SESSION['fail_login'];
}