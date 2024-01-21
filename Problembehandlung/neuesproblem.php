<?php
// Starte die Sitzung
session_start();

// Verbindung zur Datenbank herstellen
$db = mysqli_connect("localhost", "root", "", "projektaufgabe");
if ($db->connect_error) {
    die("Verbindung fehlgeschlagen: " . $db->connect_error);
}

// Überprüfen, ob die erforderlichen POST-Variablen im Formular definiert sind
if (isset($_POST['betreff'], $_POST["problembeschreibung"], $_POST["kategorie"])) {
    // Benutzername aus der Sitzung abrufen
    $user = $_SESSION["anmeldename"];

    // Daten aus dem Formular holen und vor SQL-Injection schützen
    $betreff = mysqli_real_escape_string($db, $_POST['betreff']);
    $problembeschreibung = mysqli_real_escape_string($db, $_POST["problembeschreibung"]);
    $kategorienumber = mysqli_real_escape_string($db, $_POST["kategorie"]);
    $benachrichtigung_email = isset($_POST["benachrichtigung_email"]) ? mysqli_real_escape_string($db, $_POST["benachrichtigung_email"]) : "";

    // Aktuelles Datum und Uhrzeit ermitteln
    $datum = date("Y-m-d");
    $zeit = date("H:i:s");

    // Datei-Upload verarbeiten, falls ein Screenshot hochgeladen wurde
    if (isset($_FILES['screenshot']) && $_FILES['screenshot']['error'] == 0) {
        $uploadVerzeichnis = 'uploads/';
        $uploadDatei = $uploadVerzeichnis . basename($_FILES['screenshot']['name']);

        // Sicherheitschecks für den Datei-Upload können hier hinzugefügt werden

        if (move_uploaded_file($_FILES['screenshot']['tmp_name'], $uploadDatei)) {
            // Datei wurde erfolgreich hochgeladen, hier können Sie den Dateipfad in der Datenbank speichern
        } else {
            // Fehler beim Hochladen der Datei
        }
    }

    // SQL-Abfrage zum Einfügen der Problembeschreibung in die Datenbank
    $query = "INSERT INTO problemforum (kategorie_id, bezugs_id, user, bearbeitungsstatus, datum, uhrzeit, betreff, beitragstext, antwort, bewertung, benachrichtigung_email)
              VALUES ('$kategorienumber', '0', '$user', '0', '$datum', '$zeit', '$betreff', '$problembeschreibung', 'false', NULL, '$benachrichtigung_email')";

    // SQL-Abfrage ausführen oder Fehlermeldung anzeigen
    mysqli_query($db, $query) or die("<b>Fehler bei der Datenbankanfrage</b>");

    // Datenbankverbindung schließen
    mysqli_close($db);

    // Nach erfolgreichem Absenden zur Willkommensseite für Kunden weiterleiten
    header("Location: ../Seiten/welcome_kunde.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ticket erstellen</title>
</head>
<body>
<form method="post" enctype="multipart/form-data">
    <!-- Weitere Formularfelder können hier hinzugefügt werden -->
    <label for="dateiUpload">Screenshot hochladen:</label>
    <input type="file" name="screenshot" id="dateiUpload">
    <input type="submit" value="Ticket erstellen">
</form>
</body>
</html>
