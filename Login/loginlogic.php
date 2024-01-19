<?php
// Starten einer Session
session_start();

// Herstellen der Verbindung zur Datenbank
$db = mysqli_connect("localhost", "root", "", "projektaufgabe");

// Prüfen, ob die erforderlichen Variablen in der GET-Anfrage definiert sind
if (isset($_GET["name"]) && isset($_GET["passwort"])) {
    // Holen der Variablen
    $name = $_GET["name"];
    $password = $_GET["passwort"];

    // Prüfen ob Name und Passwort gesetzt
    if (!empty($name) && !empty($password)) {
        if (isset($_GET["employeecheck"]) && $_GET["employeecheck"] == 1) {
            // Überprüfung der Anmeldeinformationen für Mitarbeiter
            $statement = "SELECT empusername, emppassword FROM mitarbeiter WHERE empusername LIKE '" . $name . "' AND emppassword LIKE '" . $password . "'";
            $result = mysqli_query($db, $statement);
            $num = mysqli_num_rows($result);
            if ($num == 1) {
                // Weiterleitung zu einer Willkommensseite für Mitarbeiter
                $_SESSION["anmeldename"] = $name;
                $_SESSION["employeecheck"] = 1;
                header("Location:../Seiten/problemseite_mitarbeiter.php");
                exit();
            }
        } else {
            // Überprüfung der Anmeldeinformationen für Kunden
            $statement = "SELECT memberusername, memberpassword FROM member WHERE memberusername LIKE '" . $name . "' AND memberpassword LIKE '" . $password . "'";
            $result = mysqli_query($db, $statement);
            $num = mysqli_num_rows($result);
            if ($num == 1) {
                // Weiterleitung zu einer Willkommensseite für Kunden
                $_SESSION["anmeldename"] = $name;
                $_SESSION["employeecheck"] = 0;
                header("Location:../Seiten/welcome_kunde.php");
                exit();
            }
        }
    }
}

// Weiterleitung zur Login-Seite mit Fehlermeldung (f=1)
header("Location:login.php?f=1");
exit();
?>
