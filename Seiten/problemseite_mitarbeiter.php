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
    <link rel="stylesheet" href="../styles.css">
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
            if ($datensatz['antwort'] == "false") {
                echo "&nbsp;";
            } else {
                echo "&nbsp;&nbsp;&nbsp;&nbsp;";
                echo "&nbsp;&nbsp;&nbsp;&nbsp;";
            }
            if ($datensatz['antwort'] == "false") {
                echo "<a href='../Problembehandlung/problem_read.php?problem_id={$datensatz['problem_id']}'>";
                echo $datensatz['betreff'];
                echo "</a>";
            } else {
                echo $datensatz['betreff'];
            }
            echo "</td>";
            echo "<td>{$datensatz['user']}</td>";
            echo "<td>{$datensatz['datum']} um {$datensatz['uhrzeit']}</td>";
            echo "</tr>";

            // Überprüfen, ob der Beitrag Antworten hat, und ggf. die Antworten anzeigen
            if ($datensatz['bezugs_id'] > 0) {
                antwort_holen($db, $datensatz['bezugs_id']);
            }
        }

        // Funktion zum Abrufen und Anzeigen von Antworten
        function antwort_holen($db, $id)
        {
            $anf = "SELECT * FROM problemforum WHERE problem_id = '$id'";
            $er = mysqli_query($db, $anf);

            // Schleife zum Durchlaufen der Ergebnisdaten und Anzeigen der Antworten
            while ($z = mysqli_fetch_assoc($er)) {
                ausgabe($db, $z);
            }
        }
        ?>
    </table>
</div>
</body>
</html>

