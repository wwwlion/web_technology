<?php
session_start();
$db = mysqli_connect("localhost", "root", "", "Projektaufgabe");
$user = $_SESSION["anmeldename"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Willkommen</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
<div class="main">
    <div class="menu">
        <a href="../index.html">Start</a>
        <a href="../Register/register.php">Registrieren</a>
        <a href="../Login/login.php">Login</a>
    </div>

    <div class="content">
        <h2>Willkommen, <?php echo $user; ?></h2>

        <?php
        // Abfrage aller Beiträge mit dem Bearbeitungsstatus 1
        $query = "SELECT bezugs_id, user, betreff FROM problemforum WHERE bearbeitungsstatus = '1'";
        $result = mysqli_query($db, $query);
        $num = mysqli_num_rows($result);

        // Durchlaufen der Ergebnisdaten und Anzeigen der Lösungsvorschläge für die Beiträge des aktuellen Benutzers
        for ($i = $num - 1; $i >= 0; $i--) {
            $line = mysqli_fetch_row($result);

            if ($line[1] == $user) {
                $id = $line[0];

                // Abrufen des Lösungstexts für den Beitrag
                $queryant = "SELECT beitragstext FROM problemforum WHERE problem_id = '$id'";
                $antresult = mysqli_query($db, $queryant);
                $antline = mysqli_fetch_row($antresult);

                if ($antline !== null) {
                    echo "<p>Lieber $user, zu Ihrem Problem [<strong>$line[2]</strong>] schlagen wir die Lösung (<em>$antline[0]</em>) vor. Bitte bewerten Sie die Lösung.</p>";
                }
            }
        }
        ?>

        <div class="katlinks">
            <h4>Bitte wählen Sie eine Kategorie:</h4>
            <a href="problemseite_kunde.php?k=0">Internet</a><br>
            <a href="problemseite_kunde.php?k=1">Hardware</a><br>
            <a href="problemseite_kunde.php?k=2">Software</a><br>
        </div>

        <br><br>
        <a href="../Problembehandlung/newproblem.html">Hier neues Problem einstellen</a>
    </div>
</div>
</body>
</html>
