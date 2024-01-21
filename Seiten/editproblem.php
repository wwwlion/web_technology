<?php
// Start der Sitzung und Datenbankverbindung herstellen
session_start();
$db = mysqli_connect("localhost", "root", "", "Projektaufgabe");
if (!$db) {
    die("Verbindung fehlgeschlagen: " . mysqli_connect_error());
}

// Den angemeldeten Benutzer aus der Sitzung holen
$user = mysqli_real_escape_string($db, $_SESSION["anmeldename"]);

// Überprüfen, ob das Formular-Daten gesendet wurden
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["problem_id"])) {
    $problem_id = mysqli_real_escape_string($db, $_POST["problem_id"]);
    $neuerBetreff = mysqli_real_escape_string($db, $_POST["neuerBetreff"]);
    $neuerBeitragstext = mysqli_real_escape_string($db, $_POST["neuerBeitragstext"]);

    // Aktualisieren der Daten in der Datenbank mit Prepared Statement
    $updateQuery = "UPDATE problemforum SET betreff = ?, beitragstext = ? WHERE problem_id = ? AND user = ?";
    $stmt = mysqli_prepare($db, $updateQuery);
    mysqli_stmt_bind_param($stmt, "ssis", $neuerBetreff, $neuerBeitragstext, $problem_id, $user);

    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Schließen des Popup-Fensters nach dem Speichern
    echo "<script>window.close();</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Problem</title>
    <link rel="stylesheet" href="Styles/answer_edit.css">
</head>
<body>
<div class="main">
    <div class="menu">
        <!-- Hier können Sie Links oder Inhalte für das Popup-Fenster hinzufügen -->
    </div>
</div>

<div class="content">
    <?php
    if (isset($_GET["problemId"])) {
        $problem_id = mysqli_real_escape_string($db, $_GET["problemId"]);

        // Abrufen der Problem-Daten aus der Datenbank
        $query = "SELECT problem_id, betreff, beitragstext FROM problemforum WHERE problem_id = ? AND user = ?";
        $stmt = mysqli_prepare($db, $query);
        mysqli_stmt_bind_param($stmt, "is", $problem_id, $user);

        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            $betreff = htmlspecialchars($row["betreff"]);
            $beitragstext = htmlspecialchars($row["beitragstext"]);
            echo "<h2>Problem bearbeiten (ID: $problem_id)</h2>";
            echo "<form method='POST' action='editproblem.php'>";
            echo "<input type='hidden' name='problem_id' value='$problem_id'>";
            echo "<label for='neuerBetreff'>Neuer Betreff:</label><br>";
            echo "<input type='text' id='neuerBetreff' name='neuerBetreff' value='$betreff'><br>";
            echo "<label for='neuerBeitragstext'>Neuer Beitragstext:</label><br>";
            echo "<textarea id='neuerBeitragstext' name='neuerBeitragstext' rows='4' cols='50'>$beitragstext</textarea><br>";
            echo "<input type='submit' value='Speichern'>";
            echo "</form>";
        } else {
            echo "<p>Das Problem wurde nicht gefunden oder Sie haben keine Berechtigung, es zu bearbeiten.</p>";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "<p>Problem-ID nicht angegeben.</p>";
    }
    ?>
</div>
</body>
</html>
