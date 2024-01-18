<?php
session_start();
$kategorienum = $_GET["k"];
$_SESSION["kategorienum"] = $_GET["k"];

// Bestimmen der Kategorie basierend auf der übergebenen Kategorie-ID
switch ($kategorienum) {
    case 0:
        $kategorie = "Internet";
        break;
    case 1:
        $kategorie = "Hardware";
        break;
    case 2:
        $kategorie = "Software";
        break;
    default:
        $kategorie = "Problemauswahl";
        break;
}
?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Problemseite - Mitarbeiter</title>
        <link rel="stylesheet" href="mitarbeiterstyles.css">
    </head>
<body>
    <div class="main">
        <div class="menu">
            <a href="../index.html">Start</a>
            <a href="../Login/login.php">Login</a>
            <a href="welcome_mitarbeiter.php">Kategorien</a>
        </div>
    </div>

<div class="content">
    <div class="kategorieschrift">
        <h4><?php echo $kategorie; ?></h4>
    </div>

    <table class="beitragstabelle">
        <tr>
            <th>Beitrag</th>
            <th>Von</th>
            <th>Datum und Uhrzeit</th>
            <th>Antworten</th>
            <th>Bearbeiten</th>
            <th>Status ändern</th>
        </tr>

<?php
// Verbindung zur Datenbank herstellen
$db = mysqli_connect("localhost", "root", "", "Projektaufgabe");

// Abfrage zum Abrufen aller Beiträge mit den Bearbeitungsstatus 0, 1 oder 3, die zur ausgewählten Kategorie gehören
$anfrage = "SELECT * FROM problemforum WHERE bearbeitungsstatus IN (0, 1, 3) AND kategorie_id = '$kategorienum'";
$ergebnis = mysqli_query($db, $anfrage);

// Schleife zum Durchlaufen der Ergebnisdaten und Anzeigen der Beiträge
while ($zeile = mysqli_fetch_assoc($ergebnis)) {
    ausgabe($db, $zeile);
}

// Funktion zum Anzeigen eines Beitrags und seiner Antworten
function ausgabe($db, $datensatz)
{
    echo "<tr>";
    echo "<td>";
    // ... Anzeige des Beitrags ...
    echo $datensatz['betreff'];
    echo "</td>";
    echo "<td>{$datensatz['user']}</td>";
    echo "<td>{$datensatz['datum']} um {$datensatz['uhrzeit']}</td>";

    // Link zum Antworten hinzufügen
    echo "<td><a href='antworten.php?problem_id={$datensatz['problem_id']}'>Antworten</a></td>";

    // Link zum Bearbeiten hinzufügen
    echo "<td><a href='bearbeiten.php?problem_id={$datensatz['problem_id']}'>Bearbeiten</a></td>";

    // Dropdown-Menü zum Ändern des Status
    echo "<td>";
    echo "<form action='status_aendern.php' method='post'>";
    echo "<input type='hidden' name='problem_id' value='{$datensatz['problem_id']}'>";
    echo "<select name='status'>";
    echo "<option value='0'>Offen</option>";
    echo "<option value='1'>In Bearbeitung</option>";
    echo "<option value='2'>Gelöst</option>";
    echo "</select>";
    echo "<input type='submit' value='Ändern'>";
    echo "</form>";
    echo "</td>";

    echo "</tr>";

    // Überprüfen, ob der Beitrag Antworten hat, und ggf. die Antworten anzeigen
    if ($datensatz['bezugs_id'] > 0) {
        antwort_holen($db, $datensatz['bezugs_id']);
    }
}

// Funktion zum Abrufen und Anzeigen von Antworten
function antwort_holen($db, $id) {
    $anf = "SELECT * FROM problemforum WHERE problem_id = '$id'";
    $er = mysqli_query($db, $anf);

    // Schleife zum Durchlaufen der Ergebnisdaten und Anzeigen der Antworten
    while ($z = mysqli_fetch_assoc($er)) {
        ausgabe($db, $z);
    }
}

// Schließen der Datenbankverbindung (optional, wird am Ende des Skripts automatisch geschlossen)
mysqli_close($db);
?>