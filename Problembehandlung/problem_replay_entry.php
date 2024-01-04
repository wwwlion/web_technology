<?php
session_start();
$id = $_POST['problem_id']; // Problem-ID aus dem POST-Parameter holen
$user = $_SESSION['anmeldename']; // Benutzername aus der Session holen
$betreff = $_POST['betreff']; // Betreff der Antwort aus dem POST-Parameter holen
$problemantwort = $_POST['problemantwort']; // Antworttext aus dem POST-Parameter holen
$kategorienum = $_SESSION["kategorienum"]; // Kategorienummer aus der Session holen

// Datum und Uhrzeit generieren
$punkt = ".";
$datum = date("d.m.Y");
$zeit = date("H:i:s");

// Sonderzeichen beachten
$user = htmlspecialchars($user, ENT_QUOTES, 'UTF-8');
$betreff = htmlspecialchars($betreff, ENT_QUOTES, 'UTF-8');
$problemantwort = htmlspecialchars($problemantwort, ENT_QUOTES, 'UTF-8');
$problemantwort = nl2br($problemantwort);

// Verbindung zur Datenbank herstellen
$db = mysqli_connect("localhost", "root", "", "projektaufgabe");
if (!$db) {
    die("<b>Zur Zeit kein Connect zum Datenbankserver!</b>");
}

// Eintragen der Antwort in die Datenbank
$query = "INSERT INTO problemforum (kategorie_id, bezugs_id, user, bearbeitungsstatus, datum, uhrzeit, betreff, beitragstext, antwort, bewertung) 
          VALUES ('$kategorienum', '0', '$user', '1', '$datum', '$zeit', '$betreff', '$problemantwort', 'true', 'null')";

if (!mysqli_query($db, $query)) {
    die("<b>Fehler bei der Datenbankanfrage: </b>" . mysqli_error($db));
}

// Ermitteln der neuen Beitrags-ID der soeben eingetragenen Antwort
$antwort_id = mysqli_insert_id($db);

// Ursprungsbeitrag aktualisieren und Bezugs-ID mit der Antwort-ID setzen
$query = "UPDATE problemforum SET bezugs_id='$antwort_id', bearbeitungsstatus='1' WHERE problem_id = '$id'";
if (!mysqli_query($db, $query)) {
    die(mysqli_errno($db) . ": " . mysqli_error($db));
}

mysqli_close($db);
?>
