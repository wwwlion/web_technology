<?php
session_start();
$db = mysqli_connect("localhost", "root", "", "Projektaufgabe");
$user = $_SESSION["anmeldename"];

// Überprüfen, ob die Formular-Daten gesendet wurden
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["problem_id"])) {
    $problem_id = $_POST["problem_id"];
    $neuerBetreff = $_POST["neuerBetreff"];
    $neuerBeitragstext = $_POST["neuerBeitragstext"];

    // Aktualisieren der Daten in der Datenbank
    $updateQuery = "UPDATE problemforum SET betreff = '$neuerBetreff', beitragstext = '$neuerBeitragstext' WHERE problem_id = '$problem_id'";
    mysqli_query($db, $updateQuery);

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
        $problem_id = $_GET["problemId"];

        // Abrufen der Problem-Daten aus der Datenbank
        $query = "SELECT problem_id, betreff, beitragstext FROM problemforum WHERE problem_id = '$problem_id' AND user = '$user'";
        $result = mysqli_query($db, $query);
        $row = mysqli_fetch_assoc($result);

        if ($row) {
            $betreff = $row["betreff"];
            $beitragstext = $row["beitragstext"];
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
    } else {
        echo "<p>Problem-ID nicht angegeben.</p>";
    }
    ?>
</div>
</body>
</html>
