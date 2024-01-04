<?php
session_start();
$user = $_SESSION["anmeldename"]; // Benutzername aus der Session holen
$empcheck = $_SESSION["employeecheck"]; // Mitarbeiterstatus aus der Session holen
?>

<html>
<head>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
<div class="main">
    <div class="menu">
        <a href="../index.html">Start</a> <!-- Link zur Startseite -->
        <a href="../Login/login.php">Login</a> <!-- Link zur Login-Seite -->
        <?php
        if ($empcheck) {
            echo "<a href='../Seiten/welcome_mitarbeiter.php'>Kategorien</a>"; // Link zu den Kategorien für Mitarbeiter
        } else {
            echo "<a href='../Seiten/welcome_kunde.php'>Kategorien</a>"; // Link zu den Kategorien für Kunden
        }
        ?>
    </div>
</div>
<h3>Das Problem</h3>
<div class="content" style="width: 400px">
    <?php
    $id = $_GET['problem_id']; // Problem-ID aus dem GET-Parameter holen
    $db = mysqli_connect("localhost", "root", ""); // Verbindung zur Datenbank herstellen
    mysqli_select_db($db, "projektaufgabe"); // Datenbank auswählen
    $query = "SELECT * FROM problemforum WHERE problem_id = '$id'"; // Query, um das Problem abzurufen
    $result = mysqli_query($db, $query); // Query ausführen
    $row = mysqli_fetch_row($result); // Erste Zeile des Ergebnisses holen

    echo "<b>Betreff: </b>" . $row[7] . "<br>"; // Betreff anzeigen
    echo "<b>von: </b>" . $row[3] . "<br>"; // Verfasser anzeigen
    echo "<b>Geschrieben am: </b>" . $row[5] . " um " . $row[6] . "<br><br><hr><br><br>"; // Datum und Uhrzeit anzeigen
    echo $row[8]; // Problemtext anzeigen

    if ($row[2] > 0) { // Wenn es Antworten zu diesem Problem gibt
        echo "<hr>";
        echo "<br><h3>Die Antworten auf diesen Beitrag:</h3>";
        echo "<table>";
        antwort_holen($db, $row[2]); // Funktion aufrufen, um die Antworten abzurufen
        echo "</table>";
    } else {
        if ($row[2] == 0) { // Wenn es keine Antworten gibt
            echo "<hr>";
            echo "<br><h4>Noch keine Antworten zu diesem Beitrag!</h4>";
        }
    }

    if ($empcheck) { // Wenn es sich um einen Mitarbeiter handelt
        echo "<br><hr><br>";
        echo "<a href='problem_replay.php?problem_id=$id'>Schreiben Sie hier eine Antwort auf diesen Beitrag</a>"; // Link zum Schreiben einer Antwort anzeigen
    }

    if (!$empcheck) { // Wenn es sich um einen Kunden handelt
        echo "<br><hr><br>";
        if ($row[3] == $user && $row[4] != 2) { // Wenn der Verfasser des Problems ist der angemeldete Benutzer und das Problem noch nicht als gelöst markiert wurde
            echo "<h4>Bitte bewerten Sie den Lösungsvorschlag:</h4>";
            echo "Den Lösungsvorschlag: <br>";
            $antwortid = $row[2];
            echo "<form method='post' action='bewertung_eintragen.php'>
                    <input type='radio' name='bewertung' value='1'>Annehmen <br>
                    <input type='radio' name='bewertung' value='0'>Ablehnen <br>
                    <input type='hidden' name='problemid' value='$antwortid'>
                    <input type='submit' value='Abschicken'>
                  </form>";
        }
    }

    function antwort_holen($db, $id) // Funktion zum Abrufen von Antworten
    {
        $query = "SELECT * FROM problemforum WHERE problem_id = '$id'";
        $result = mysqli_query($db, $query);
        $row = mysqli_fetch_row($result);
        ausgabe($db, $row); // Funktion aufrufen, um die Antwort anzuzeigen
    }

    function ausgabe($db, $datensatz) // Funktion zum Anzeigen einer Antwort
    {
        echo "<br><br<b>Betreff: </b>" . $datensatz[7] . "<br>"; // Betreff anzeigen
        echo "<b>von: </b>" . $datensatz[3] . "<br>"; // Verfasser anzeigen
        echo "<b>Geschrieben am: </b>" . $datensatz[5] . " um " . $datensatz[6] . "<br>"; // Datum und Uhrzeit anzeigen
        echo "<b>Status: </b>";
        if ($datensatz[10] == "null") {
            echo "Noch keine Bewertung";
        } else {
            echo $datensatz[10];
        }
        echo "<br><br><hr><br><br>";
        echo $datensatz[8]; // Antworttext anzeigen

        if ($datensatz[2] > 0 && $datensatz[9] == "false") { // Wenn es weitere Antworten gibt
            antwort_holen($db, $datensatz[2]); // Rekursiver Aufruf, um die weiteren Antworten abzurufen
        }
    }

    mysqli_close($db); // Datenbankverbindung schließen
    ?>
</div>
</body>
</html>
