<?php
// Erstellen der DB-Verbindung
$db = mysqli_connect("localhost", "root", "", "projektaufgabe");
if (!$db) {
    die("Verbindung fehlgeschlagen: " . mysqli_connect_error());
}

// Holen der übergebenen Variablen und Sicherung gegen SQL-Injections
$registername = mysqli_real_escape_string($db, $_GET["name"]);
$registerpassword = mysqli_real_escape_string($db, $_GET["passwort"]);
$registerfirma = mysqli_real_escape_string($db, $_GET["firma"]); // Annahme: Die Firma wird auch verwendet

// Prüfen ob Mitarbeiter oder nicht
if (isset($_GET["employeecheck"]) && $_GET["employeecheck"] == "yes") {
    // SQL-Anweisung zur Eintragung in die Tabelle "mitarbeiter" mit Prepared Statements
    $statement = $db->prepare("INSERT INTO mitarbeiter (empusername, emppassword) VALUES (?, ?)");
    $statement->bind_param("ss", $registername, $registerpassword);
} else {
    // Eintragen als normaler User (member) mit Prepared Statements
    $statement = $db->prepare("INSERT INTO member (memberusername, memberpassword) VALUES (?, ?)");
    $statement->bind_param("ss", $registername, $registerpassword);
}

// Ausführen der Statements
if (!$statement->execute()) {
    die("<b>Fehler bei der Datenbankanfrage: </b>" . mysqli_error($db));
}
$statement->close();

// Automatische Weiterleitung an den Login
header("Location:../Login/login.html");
?>
