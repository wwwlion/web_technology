<?php
// Sitzung starten, um auf Sitzungsvariablen zugreifen zu können
session_start();

// Verbindung zur Datenbank herstellen
$db = mysqli_connect("localhost", "root", "", "projektaufgabe");
if (!$db) {
    die("Verbindung zur Datenbank fehlgeschlagen: " . mysqli_connect_error());
}

// Benutzernamen aus der Sitzung abrufen und vor SQL-Injections schützen
$user = mysqli_real_escape_string($db, $_SESSION["anmeldename"]);

// Überprüfen, ob die POST-Variable "problemId" gesetzt ist
if (isset($_POST["problemId"])) {
    $problemId = mysqli_real_escape_string($db, $_POST["problemId"]);

    // SQL-Abfrage vorbereiten, um zu überprüfen, ob das Problem dem angemeldeten Benutzer gehört, um unberechtigtes Löschen zu verhindern
    $checkOwnershipQuery = "SELECT user FROM problemforum WHERE problem_id = ?";
    $stmt = mysqli_prepare($db, $checkOwnershipQuery);
    mysqli_stmt_bind_param($stmt, "i", $problemId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    // Wenn das Problem dem angemeldeten Benutzer gehört
    if ($row["user"] == $user) {
        // SQL-Abfrage vorbereiten, um das Problem aus der Datenbank zu löschen
        $deleteQuery = "DELETE FROM problemforum WHERE problem_id = ?";
        $deleteStmt = mysqli_prepare($db, $deleteQuery);
        mysqli_stmt_bind_param($deleteStmt, "i", $problemId);

        // Das Problem löschen und "success" als Antwort ausgeben, wenn das Löschen erfolgreich ist
        if (mysqli_stmt_execute($deleteStmt)) {
            echo "success";
        } else {
            // Fehlermeldung anzeigen, wenn das Löschen fehlschlägt
            echo "Fehler beim Löschen des Problems: " . mysqli_error($db);
        }
        mysqli_stmt_close($deleteStmt);
    } else {
        // Nachricht anzeigen, wenn der Benutzer nicht berechtigt ist, das Problem zu löschen
        echo "Sie sind nicht berechtigt, dieses Problem zu löschen.";
    }
    mysqli_stmt_close($stmt);
}
?>
