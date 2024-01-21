<?php
session_start();

// Überprüfen, ob ein Fehlerparameter übergeben wurde
$loginFailed = isset($_GET['f']) && $_GET['f'] == 1;

// Herstellen der Verbindung zur Datenbank
$db = mysqli_connect("localhost", "root", "", "projektaufgabe");
if ($db->connect_error) {
    die("Verbindung fehlgeschlagen: " . $db->connect_error);
}

// Prüfen, ob die erforderlichen Variablen in der POST-Anfrage definiert sind
if (isset($_POST["name"]) && isset($_POST["passwort"])) {
    // Holen der Variablen und Vermeidung von SQL-Injection
    $name = mysqli_real_escape_string($db, $_POST["name"]);
    $password = mysqli_real_escape_string($db, $_POST["passwort"]);
    $employeecheck = isset($_POST["employeecheck"]) ? mysqli_real_escape_string($db, $_POST["employeecheck"]) : 0;

    // Überprüfung der Anmeldeinformationen für Mitarbeiter oder Kunden
    $table = ($employeecheck == 1) ? "mitarbeiter" : "member";
    $column = ($employeecheck == 1) ? "empusername" : "memberusername";
    $passwordColumn = ($employeecheck == 1) ? "emppassword" : "memberpassword";

    $statement = "SELECT $column FROM $table WHERE $column = ? AND $passwordColumn = ?";
    $stmt = mysqli_prepare($db, $statement);
    mysqli_stmt_bind_param($stmt, "ss", $name, $password);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $num = mysqli_num_rows($result);

    // Überprüfen, ob die Anmeldedaten gültig sind
    if ($num == 1) {
        // Speichern von Benutzerdaten in der Sitzung
        $_SESSION["anmeldename"] = $name;
        $_SESSION["employeecheck"] = $employeecheck;

        // Weiterleiten des Benutzers basierend auf seinem Status
        if ($employeecheck == 1) {
            header("Location:../Seiten/problemseite_mitarbeiter.php");
        } else {
            header("Location:../Seiten/welcome_kunde.php");
        }
        exit();
    } else {
        // Weiterleiten mit einem Fehlerparameter, wenn die Anmeldung fehlschlägt
        header("Location:login.html?f=1");
        exit();
    }
}
?>
