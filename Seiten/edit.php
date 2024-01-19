<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ticket bearbeiten</title>
    <link rel="stylesheet" href="../Seiten/Styles/answer_edit.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
<?php
$db = new mysqli("localhost", "root", "", "projektaufgabe");
if ($db->connect_error) {
    die("Verbindung fehlgeschlagen: " . $db->connect_error);
}

$problem_id = $_GET['problemId'];
$error_message = "";
$success_message = ""; // Initialisiere die Erfolgsmeldung
$aktuellerBetreff = ""; // Initialisiere den aktuellen Betreff

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['bearbeiten'])) {
        $betreff = $_POST['betreff'];
        $query = "UPDATE problemforum SET betreff = ? WHERE problem_id = ?";

        $stmt = $db->prepare($query);
        $stmt->bind_param("si", $betreff, $problem_id);

        if ($stmt->execute()) {
            $success_message = 'Änderungen wurden erfolgreich gespeichert.';
        } else {
            $error_message = 'Fehler beim Speichern der Änderungen.';
        }
    } elseif (isset($_POST['loeschen'])) {
        $query = "DELETE FROM problemforum WHERE problem_id = ?";

        $stmt = $db->prepare($query);
        $stmt->bind_param("i", $problem_id);

        if ($stmt->execute()) {
            $success_message = 'Beitrag erfolgreich gelöscht.';
        } else {
            $error_message = 'Fehler beim Löschen des Beitrags.';
        }
    }
} else {
    // Abfrage des aktuellen Betreffs aus der Datenbank
    $query = "SELECT betreff FROM problemforum WHERE problem_id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $problem_id);
    $stmt->execute();
    $stmt->bind_result($aktuellerBetreff);
    $stmt->fetch();
    $stmt->close();
}
?>

<form id="editForm" method="post">
    <input type="hidden" name="problem_id" value="<?php echo $problem_id; ?>">
    <label for="betreff">Betreff:</label>
    <input type="text" name="betreff" value="<?php echo $aktuellerBetreff; ?>">
    <input type="submit" name="bearbeiten" value="Änderungen speichern">
    <input type="submit" name="loeschen" value="Beitrag löschen" onclick="return confirm('Möchten Sie diesen Beitrag wirklich löschen?');">
</form>

<div id="success_message" style="color: green;"><?php echo $success_message; ?></div>
<div id="error_message" style="color: red;"><?php echo $error_message; ?></div>

</body>
</html>
