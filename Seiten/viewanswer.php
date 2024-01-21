<?php
// Verbindung zur Datenbank herstellen (verwenden Sie Ihre eigenen Verbindungsinformationen)
$db = mysqli_connect("localhost", "root", "", "Projektaufgabe");

if (!$db) {
    die("Verbindung zur Datenbank fehlgeschlagen: " . mysqli_connect_error());
}

// Überprüfen, ob eine Problem-ID übergeben wurde
if (isset($_GET['problemId'])) {
    // Verwendung von mysqli_real_escape_string, um SQL-Injection zu vermeiden
    $problemId = mysqli_real_escape_string($db, $_GET['problemId']);

    // Abfrage der Antwort für die gegebene Problem-ID
    $query = "SELECT antwort_text FROM antworten WHERE problem_id = ?";
    if ($stmt = $db->prepare($query)) {
        $stmt->bind_param("i", $problemId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result) {
            if ($row = $result->fetch_assoc()) {
                $antwort = $row['antwort_text'];
            } else {
                $antwort = "Es gibt keine Antwort für dieses Problem.";
            }
        } else {
            $antwort = "Fehler beim Abrufen der Antwort.";
        }
    } else {
        $antwort = "Fehler bei der Vorbereitung der Anfrage.";
    }
} else {
    $antwort = "Problem-ID wurde nicht übergeben.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Antwort anzeigen</title>
    <link rel="stylesheet" href="../Seiten/Styles/answer_edit.css"> <!-- CSS für Antwort-Popup hinzugefügt -->
</head>
<body>
<h2>Antwort anzeigen</h2>
<div id="answerText">
    <?php echo htmlspecialchars($antwort); ?>
</div>
</body>
</html>
