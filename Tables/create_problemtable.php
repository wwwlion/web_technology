<?php
// Datenbankverbindung herstellen oder Fehlermeldung ausgeben
$db = mysqli_connect("localhost", "root", "") or die("<b>Zur Zeit kein Connect zum Datenbankserver!</b>");

// Datenbank auswählen oder Fehlermeldung ausgeben
mysqli_select_db($db, "projektaufgabe") or die("<b>Datenbank konnte nicht angesprochen werden</b>");

// SQL-Abfrage zum Erstellen der Tabelle "problemforum"
$queryProblemforum = "CREATE TABLE IF NOT EXISTS problemforum (
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
    benachrichtigung_email VARCHAR(255)
)";
mysqli_query($db, $queryProblemforum) or die("<b>Fehler bei der Erstellung der Tabelle problemforum</b>");

// SQL-Abfrage zum Erstellen der Tabelle "antworten"
$queryAntworten = "CREATE TABLE IF NOT EXISTS antworten (
    antwort_id INT AUTO_INCREMENT PRIMARY KEY,
    problem_id INT,
    antwort_text TEXT,
    user VARCHAR(50),
    bewertung VARCHAR(100)
)";
mysqli_query($db, $queryAntworten) or die("<b>Fehler bei der Erstellung der Tabelle antworten</b>");

// Datenbankverbindung schließen
mysqli_close($db);
?>
