<?php

// Verbindung zur Datenbank herstellen
$db = mysqli_connect("localhost", "root", "");  // Datenbankverbindung herstellen: Hostname, Benutzername, Passwort (leer)

mysqli_select_db($db, "projektaufgabe");  // Datenbank auswählen: Name der Datenbank

// SQL-Abfrage zum Erstellen der Tabelle "problemforum"
$query = "CREATE TABLE IF NOT EXISTS problemforum (
    problem_id INT AUTO_INCREMENT PRIMARY KEY,
    kategorie_id INT(1),
    bezugs_id INT,
    user VARCHAR(50),
    bearbeitungsstatus INT(1),
    datum VARCHAR(10),
    uhrzeit VARCHAR(10),
    betreff VARCHAR(255),
    beitragstext TEXT,
    antwort VARCHAR(10),
    bewertung VARCHAR(100)
)";

// Tabelle erstellen
mysqli_query($db, $query);  // SQL-Abfrage ausführen, um die Tabelle zu erstellen

// Verbindung zur Datenbank schließen
mysqli_close($db);

?>
