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
// Datenbankverbindung herstellen
$db = new mysqli("localhost", "root", "", "projektaufgabe");
if ($db->connect_error) {
    die("Verbindung fehlgeschlagen: " . $db->connect_error);
}

// Sicherstellen, dass problemId ein gültiger numerischer Wert ist
$problem_id = isset($_GET['problemId']) && is_numeric($_GET['problemId']) ? $_GET['problemId'] : 0;
$error_message = "";
$success_message = "";
$aktuellerBetreff = "";

// Überprüfen, ob das Formular per POST-Methode gesendet wurde
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['bearbeiten'])) {
        $betreff = mysqli_real_escape_string($db, $_POST['betreff']);
        $query = "UPDATE problemforum SET betreff = ? WHERE problem_id = ?";

        // Vorbereiten der SQL-Abfrage zum Aktualisieren des Betreffs
        $stmt = $db->prepare($query);
        $stmt->bind_param("si", $betreff, $problem_id);

        if ($stmt->execute()) {
            $success_message = 'Änderungen wurden erfolgreich gespeichert.';
        } else {
            $error_message = 'Fehler beim Speichern der Änderungen.';
        }
        $stmt->close();
    } elseif (isset($_POST['loeschen'])) {
        $query = "DELETE FROM problemforum WHERE problem_id = ?";

        // Vorbereiten der SQL-Abfrage zum Löschen des Beitrags
        $stmt = $db->prepare($query);
        $stmt->bind_param("i", $problem_id);

        if ($stmt->execute()) {
            $success_message = 'Beitrag erfolgreich gelöscht.';
        } else {
            $error_message = 'Fehler beim Löschen des Beitrags.';
        }
        $stmt->close();
    }
} else {
    // Abfrage des aktuellen Betreffs aus der Datenbank
    $query = "SELECT betreff FROM problemforum WHERE problem_id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $problem_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $aktuellerBetreff = htmlspecialchars($row['betreff']);
    }
    $stmt->close();
}
?>

<form id="editForm" method="post">
    <!-- Verstecktes Feld zur Übertragung der Problem-ID -->
    <input type="hidden" name="problem_id" value="<?php echo htmlspecialchars($problem_id); ?>">
    <label for="betreff">Betreff:</label>
    <input type="text" name="betreff" value="<?php echo htmlspecialchars($aktuellerBetreff); ?>">
    <input type="submit" name="bearbeiten" value="Änderungen speichern">
    <input type="submit" name="loeschen" value="Beitrag löschen" onclick="return confirm('Möchten Sie diesen Beitrag wirklich löschen?');">
</form>

<div id="success_message" style="color: green;"><?php echo htmlspecialchars($success_message); ?></div>
<div id="error_message" style="color: red;"><?php echo htmlspecialchars($error_message); ?></div>

</body>
</html>
