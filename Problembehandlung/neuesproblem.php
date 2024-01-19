<?php
session_start();
$user = $_SESSION["anmeldename"];

if (isset($_POST['betreff'], $_POST["problembeschreibung"], $_POST["kategorie"])) {
    $betreff = $_POST['betreff'];
    $problembeschreibung = $_POST["problembeschreibung"];
    $kategorienumber = $_POST["kategorie"];

    // Überprüfen, ob eine E-Mail-Adresse übermittelt wurde
    if (isset($_POST["benachrichtigung_email"])) {
        $benachrichtigung_email = $_POST["benachrichtigung_email"];
    } else {
        $benachrichtigung_email = ""; // Wenn keine E-Mail-Adresse angegeben ist, wird ein leerer String verwendet
    }

    // Datenbankverbindung
    $db = mysqli_connect("localhost", "root", "") or die("<b>Zur Zeit kein Connect zum Datenbankserver!</b>");
    mysqli_select_db($db, "projektaufgabe") or die("<b>Datenbank konnte nicht angesprochen werden</b>");

    // Sichere die Eingaben gegen SQL-Injektion
    $user = mysqli_real_escape_string($db, $user);
    $betreff = mysqli_real_escape_string($db, $betreff);
    $problembeschreibung = mysqli_real_escape_string($db, $problembeschreibung);
    $benachrichtigung_email = mysqli_real_escape_string($db, $benachrichtigung_email);

    $punkt = ".";
    $datum = date("d.m.Y");
    $zeit = date("H:i:s");

    // Beitrag in die Datenbank einfügen
    $query = "INSERT INTO problemforum (kategorie_id, bezugs_id, user, bearbeitungsstatus, datum, uhrzeit, betreff, beitragstext, antwort, bewertung, benachrichtigung_email)
            VALUES ('$kategorienumber', '0', '$user', '0', '$datum', '$zeit', '$betreff', '$problembeschreibung', 'false', NULL, '$benachrichtigung_email')";
    mysqli_query($db, $query) or die("<b>Fehler bei der Datenbankanfrage</b>");

    mysqli_close($db);

    // Weiterleitung zur gewünschten Seite
    header("Location: ../Seiten/welcome_kunde.php");
    exit;
}
?>
