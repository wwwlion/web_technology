<?php
// Datenbankverbindung herstellen (ersetzen Sie diese Informationen durch Ihre eigenen)
$db = new mysqli("localhost", "root", "", "Projektaufgabe");
if ($db->connect_error) {
    die("Verbindung fehlgeschlagen: " . $db->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Überprüfen, ob die erforderlichen POST-Variablen gesetzt sind
    if (isset($_POST['problem_id']) && isset($_POST['status'])) {
        // Problem-ID und den neuen Status aus dem POST-Daten sicher erhalten
        $problemId = $db->real_escape_string($_POST['problem_id']);
        $neuerStatus = $db->real_escape_string($_POST['status']);

        // Sicherstellen, dass die Eingaben numerisch sind
        if (is_numeric($problemId) && is_numeric($neuerStatus)) {
            // SQL-Abfrage zum Aktualisieren des Status
            $updateQuery = "UPDATE problemforum SET bearbeitungsstatus = ? WHERE problem_id = ?";

            // Prepared Statement vorbereiten
            $stmt = $db->prepare($updateQuery);

            if ($stmt === false) {
                die("Fehler beim Vorbereiten der Abfrage: " . $db->error);
            }

            // Parameter binden und Typen spezifizieren
            $stmt->bind_param("ii", $neuerStatus, $problemId);

            // Statement ausführen
            if ($stmt->execute()) {
                // Erfolg
                echo "Status erfolgreich aktualisiert.";
            } else {
                // Fehler bei der Ausführung
                echo "Fehler beim Aktualisieren des Status: " . $stmt->error;
            }

            // Statement schließen
            $stmt->close();
        } else {
            echo "Ungültige Eingabe.";
        }
    } else {
        echo "Erforderliche Daten nicht übermittelt.";
    }
}

// Datenbankverbindung schließen
$db->close();
?>
