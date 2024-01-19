<?php
// Datenbankverbindung herstellen (ersetzen Sie diese Informationen durch Ihre eigenen)
$db = new mysqli("localhost", "root", "", "Projektaufgabe");
if ($db->connect_error) {
    die("Verbindung fehlgeschlagen: " . $db->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Problem-ID und den neuen Status aus dem POST-Daten erhalten
    $problemId = $_POST['problem_id'];
    $neuerStatus = $_POST['status'];

    // SQL-Abfrage zum Aktualisieren des Status
    $updateQuery = "UPDATE problemforum SET bearbeitungsstatus = ? WHERE problem_id = ?";

    // Prepared Statement vorbereiten
    $stmt = $db->prepare($updateQuery);

    if ($stmt === false) {
        die("Fehler beim Vorbereiten der Abfrage: " . $db->error);
    }

    // Parameter binden
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
}

// Datenbankverbindung schließen
$db->close();
?>
