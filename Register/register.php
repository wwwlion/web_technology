<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
<!-- Menu Link -->
<div class="main">
    <div class="menu">
        <a href="../index.html">Start</a>
        <a href="../Login/login.php">Login</a>
    </div>
</div>
<!-- Anzeigen des Seitennamens -->
<div class="sitename">
    <h1>Register Site</h1>
</div>

<div class="formular">
    <!-- Öffnen der Tabelle -->
    <table border="1">
        <th>Registrieren</th>
        <tr>
            <td>
                <!-- Öffnen des Formulars mit action zur Registrierung -->
                <form method="get" action="registerlogic.php">
                    Ihr Username: <br>
                    <input required type="text" name="name"> <br> <br>
                    Ihr Passwort: <br>
                    <input required type="password" name="passwort"> <br> <br>
                    <!-- Check ob Mitarbeiter -->
                    <label>
                        <input type="radio" name="employeecheck">Mitarbeiter
                    </label>
                    <br> <br>
                    <!-- Abschicken zur Verarbeitung -->
                    <input type="submit" value="Registrieren">
                </form>
            </td>
        </tr>
    </table>
</div>

</body>
</html>
