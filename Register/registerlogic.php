<?php
// Erstellen der DB-Verbindung
$db = mysqli_connect("localhost", "root", "", "projektaufgabe");

// Holen der übergebenen Variablen
$registername = $_GET["name"];
$registerpassword = $_GET["passwort"];
$registerfirma = $_GET["firma"];

// Prüfen ob Mitarbeiter oder nicht
if (isset($_GET["employeecheck"])) {
    // SQL-Anweisung zur Eintragung in die Tabelle "mitarbeiter"
    $statement = "INSERT INTO mitarbeiter (empusername, emppassword)
                  VALUES ('$registername', '$registerpassword')";
} else {
    // Eintragen als normaler User (member)
    $statement = "INSERT INTO member (memberusername, memberpassword)
                  VALUES ('$registername', '$registerpassword')";
}

// Ausführen der Statements
if (!mysqli_query($db, $statement)) {
    die("<b>Fehler bei der Datenbankanfrage: </b>" . mysqli_error($db));
}

// Automatische Weiterleitung an den Login
header("Location:../Login/login.php");
?>
