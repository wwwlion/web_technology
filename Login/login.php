<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles_login.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Gowun+Dodum&display=swap');
    </style>
</head>
<body>

<div class="main">
    <div class="menu">
        <a href="../Index/index.html">Start</a>
        <!--<a href="../Register/register.php">Registrieren</a>--!>
    </div>
</div>

<div class="sitename">
    <h1>Anmeldung</h1>
</div>

<div class="fehlertext">
    <?php if (isset($_GET["f"]) && $_GET["f"] == 1): ?>
        <p class='fehler'>Fehlerhafte Anmeldedaten</p>
    <?php endif; ?>
</div>

<div class="formular">
    <table border="0">

        <tr>
            <td>
                <form method="get" action="loginlogic.php">
                    <label for="username">Ihr Benutzername:</label> <br> <!-- Eingabefeld für den Benutzernamen -->
                    <input required type="text" id="username" name="name"> <br> <br>
                    <label for="password">Ihr Passwort:</label> <br> <!-- Eingabefeld für das Passwort -->
                    <input required type="password" id="password" name="passwort"> <br> <br>
                    <label for="employeecheck">
                        <select id="employeecheck" name="employeecheck">
                            <option value="no">Kein Mitarbeiter</option>
                            <option value="yes">Mitarbeiter</option>
                        </select>
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
