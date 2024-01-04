<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>

<div class="main">
    <div class="menu">
        <a href="../index.html">Start</a> <!-- Link zur Startseite -->
        <a href="../Register/register.php">Registrieren</a> <!-- Link zur Registrierungsseite -->
    </div>
</div>

<div class="sitename">
    <h1>Login-Seite</h1> <!-- Überschrift "Login-Seite" -->
</div>

<div class="fehlertext">
    <?php if (isset($_GET["f"]) && $_GET["f"] == 1): ?>
        <p class='fehler'>Fehlerhafte Anmeldedaten</p> <!-- Fehlermeldung bei fehlerhaften Anmeldedaten -->
    <?php endif; ?>
</div>

<div class="formular">
    <table border="1">
        <th>Anmelden</th> <!-- Überschrift für das Anmeldeformular -->
        <tr>
            <td>
                <form method="get" action="loginlogic.php">
                    <label for="username">Ihr Benutzername:</label> <br> <!-- Eingabefeld für den Benutzernamen -->
                    <input required type="text" id="username" name="name"> <br> <br>
                    <label for="password">Ihr Passwort:</label> <br> <!-- Eingabefeld für das Passwort -->
                    <input required type="password" id="password" name="passwort"> <br> <br>
                    <label for="employeecheck">
                        <input type="radio" id="employeecheck" name="employeecheck"> Mitarbeiter <!-- Auswahl für Mitarbeiterstatus -->
                    </label>
                    <br> <br>
                    <input type="submit" value="Login"> <!-- Login-Button -->
                </form>
            </td>
        </tr>
    </table>
</div>

</body>
</html>
