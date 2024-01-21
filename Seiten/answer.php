<?php
// Überprüfen, ob das Formular per POST-Methode gesendet wurde
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Datenbankverbindung herstellen (Konfigurationsdatei einbinden)
    include 'config.php';

    // Sicherstellen, dass die POST-Variablen gesetzt sind
    if (isset($_POST['problem_id'], $_POST['antwort'])) {
        // Sicherheitsmaßnahme: Problem-ID und Antworttext vor SQL-Injections schützen
        $problem_id = mysqli_real_escape_string($db, $_POST['problem_id']);
        $antwort = mysqli_real_escape_string($db, $_POST['antwort']);

        // Vorbereiten der SQL-Abfrage zum Einfügen der Antwort in die Datenbank
        $insert_query = "INSERT INTO antworten (problem_id, antwort_text) VALUES (?, ?)";
        $stmt = $db->prepare($insert_query);

        if ($stmt) {
            // Binden der Parameter an die vorbereitete Abfrage
            $stmt->bind_param("is", $problem_id, $antwort);
            if ($stmt->execute()) {
                echo "Antwort erfolgreich gesendet.";
                // Optional: Weiterleitung zurück zur Hauptseite
                // header('Location: mitarbeiter.php');
            } else {
                echo "Fehler beim Senden der Antwort: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Fehler beim Vorbereiten der Abfrage: " . $db->error;
        }
    } else {
        echo "Fehlende erforderliche Eingabedaten.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Antwort auf Ticket</title>
    <link rel="stylesheet" href="../Seiten/Styles/answer_edit.css">
</head>
<body>
<form method="post">
    <!-- Verstecktes Feld zur Übertragung der Problem-ID -->
    <input type="hidden" name="problem_id" value="<?php echo isset($_GET['problemId']) ? htmlspecialchars($_GET['problemId']) : ''; ?>">

    <!-- Textbereich für die Antwort -->
    <label for="antwort">Antwort:</label>
    <textarea name="antwort" required></textarea>

    <!-- Submit-Button zum Senden der Antwort -->
    <input type="submit" value="Antwort senden">
</form>
</body>
</html>
