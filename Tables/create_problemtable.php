<?php
// Datenbankverbindung
$db = mysqli_connect("localhost", "root", "") or die("<b>Zur Zeit kein Connect zum Datenbankserver!</b>");
mysqli_select_db($db, "projektaufgabe") or die("<b>Datenbank konnte nicht angesprochen werden</b>");

// SQL-Abfrage zum Erstellen der Tabelle "problemforum" (einschließlich der Spalte 'benachrichtigung_email')
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
    antwort VARCHAR(255),
    bewertung VARCHAR(100),
    benachrichtigung_email VARCHAR(255) -- Hinzugefügte Spalte für die E-Mail
)";
$query = "CREATE TABLE IF NOT EXISTS antworten (
    antwort_id INT AUTO_INCREMENT PRIMARY KEY,
    problem_id INT,
    antwort_text TEXT,
    bewertung VARCHAR(100)
)";

// Tabelle erstellen
mysqli_query($db, $query) or die("<b>Fehler bei der Datenbankanfrage</b>");

mysqli_close($db);
?>
