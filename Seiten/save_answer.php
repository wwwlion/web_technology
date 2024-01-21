<?php
// Start der Sitzung und Datenbankverbindung herstellen
session_start();
$db = new mysqli("localhost", "root", "", "projektaufgabe");
if ($db->connect_error) {
    die("Verbindung fehlgeschlagen: " . $db->connect_error);
}

// Überprüfen, ob der Benutzer angemeldet ist und "anmeldename" in der Sitzung gespeichert ist
if (isset($_SESSION['anmeldename'])) {
    // Verarbeitung von Antworten
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['antwort_text']) && isset($_POST['problem_id'])) {
            // Escaping der Benutzereingaben zur Vermeidung von SQL-Injection
            $antwort_text = $db->real_escape_string($_POST['antwort_text']);
            $problem_id = $db->real_escape_string($_POST['problem_id']);

            $user = $db->real_escape_string($_SESSION['anmeldename']);

            // Sicherstellen, dass die Problem-ID numerisch ist
            if (is_numeric($problem_id)) {
                $insert_query = "INSERT INTO antworten (problem_id, user, antwort_text) VALUES (?, ?, ?)";
                $stmt = $db->prepare($insert_query);

                if ($stmt) {
                    // Binden der Parameter an das vorbereitete Statement
                    $stmt->bind_param("iss", $problem_id, $user, $antwort_text);
                    if ($stmt->execute()) {
                        echo 'success';
                    } else {
                        echo 'error';
                    }
                    $stmt->close();
                } else {
                    echo 'Fehler beim Vorbereiten der Anfrage: ' . $db->error;
                }
                exit();
            } else {
                echo 'Ungültige Problem-ID.';
            }
        }
    }
} else {
    echo 'Benutzer nicht angemeldet oder anmeldename nicht in der Sitzung gespeichert.';
}
?>
