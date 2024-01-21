<?php
session_start();
$db = mysqli_connect("localhost", "root", "", "Projektaufgabe");
if (!$db) {
    die("Verbindung zur Datenbank fehlgeschlagen: " . mysqli_connect_error());
}

$user = isset($_SESSION["anmeldename"]) ? mysqli_real_escape_string($db, $_SESSION["anmeldename"]) : '';

// Löschen von Problemen
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['problemId'])) {
    $problemId = mysqli_real_escape_string($db, $_POST['problemId']);
    $delete_query = "DELETE FROM problemforum WHERE problem_id = ?";
    $stmt = $db->prepare($delete_query);
    if ($stmt) {
        $stmt->bind_param("i", $problemId);
        if ($stmt->execute()) {
            echo 'success';
        } else {
            echo 'error';
        }
        $stmt->close();
    } else {
        echo 'error';
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Willkommen</title>
    <link rel="stylesheet" href="Styles/welcomekundestyles.css">
    <style>
        .answer-button {
            margin-top: 10px; /* Hinzugefügter Abstand zum Button */
        }
        .answer-text {
            margin-top: 10px; /* Hinzugefügter Abstand zur Antwort */
        }
    </style>
    <script>
        // JavaScript-Funktion zum Öffnen eines Popup-Fensters zur Bearbeitung des Problems
        function openProblemPopup(problemId) {
            // Code, um ein Popup-Fenster zu öffnen und die Problem-ID zu übergeben
            // ...

            // Hier wird ein Timer verwendet, um das Hauptfenster nach dem Speichern zu aktualisieren
            // ...
        }

        // JavaScript-Funktion zum Schließen des Popup-Fensters
        function closePopup() {
            // Code zum Schließen des Popup-Fensters
            // ...
        }

        // JavaScript-Funktion zum Löschen eines Problems
        function deleteProblem(problemId) {
            // Code zum Löschen eines Problems
            // ...

            // Nach dem Löschen das Hauptfenster neu laden
            location.reload(); // Aktualisieren Sie die Seite nach dem Löschen
        }

        // JavaScript-Funktion zum Anzeigen oder Ausblenden der Antwort zu einem Problem
        function showAnswer(problemId) {
            // Code zum Anzeigen oder Ausblenden der Antwort
            // ...
        }

        // JavaScript-Funktion zum Einreichen des Problemformulars
        function submitProblemForm() {
            // Code zum Einreichen des Problemformulars
            // ...

            // Nach dem Einreichen das Hauptfenster neu laden
            location.reload(); // Aktualisieren Sie die Seite nach dem Einreichen
        }
    </script>
</head>
<body>
<div class="main">
    <div class="menu">
        <a href="../Index/index.html">Logout</a>
    </div>
</div>

<div class="content">
    <h2>Willkommen, <?php echo htmlspecialchars($user); ?></h2>
    <b>Folgende Anfragen hast du eingestellt und liegen den Mitarbeitern noch vor: </b>

    <?php
    $query = "SELECT problem_id, betreff, bearbeitungsstatus FROM problemforum WHERE user = ?";
    if ($stmt = $db->prepare($query)) {
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($line = $result->fetch_assoc()) {
            $problem_id = htmlspecialchars($line['problem_id']);
            $betreff = htmlspecialchars($line['betreff']);
            $bearbeitungsstatus = htmlspecialchars($line['bearbeitungsstatus']);

            if ($bearbeitungsstatus == 0) {
                $bearbeitungsstatus_text = "Offen";
            } elseif ($bearbeitungsstatus == 1) {
                $bearbeitungsstatus_text = "In Bearbeitung";
            } elseif ($bearbeitungsstatus == 2) {
                $bearbeitungsstatus_text = "Geschlossen";
            } else {
                $bearbeitungsstatus_text = "Unbekannt";
            }

            echo "<div class='problem-row' onclick='openProblemPopup($problem_id)'>";
            echo "Problem-ID: $problem_id | Betreff: $betreff | Bearbeitungsstatus: $bearbeitungsstatus_text";
            echo "</div>";

            echo "<button class='delete-button' onclick='deleteProblem($problem_id)'>Löschen</button>";

            // Überprüfen Sie, ob für das Problem eine Antwort vorhanden ist
            $answer_query = "SELECT antwort_text FROM antworten WHERE problem_id = $problem_id";
            $answer_result = mysqli_query($db, $answer_query);

            if ($answer_result) {
                $answer_row = mysqli_fetch_row($answer_result);
                $answer_text = isset($answer_row[0]) ? $answer_row[0] : '';

                // Wenn eine Antwort vorhanden ist, zeigen Sie den Button zum Anzeigen der Antwort an
                if (!empty($answer_text)) {
                    echo "<button id='answerButton_$problem_id' class='answer-button' onclick='showAnswer($problem_id)'>Antwort anzeigen</button>";
                    echo "<div id='answerDiv_$problem_id' class='answer-text' style='display:none'>$answer_text</div>";
                }
            } else {
                // Hier können Sie eine Meldung ausgeben oder eine andere Aktion ausführen, wenn die Abfrage fehlschlägt
                echo "Fehler beim Abrufen der Antwort.";
            }

        }
    } else {
        die("Abfrage fehlgeschlagen: " . mysqli_error($db));
    }
    ?>
    <a href="../Problembehandlung/neuesproblem.html">Neues Problem erstellen</a>
</div>
</body>
</html>
