<?php
// Start der Sitzung und Datenbankverbindung herstellen
session_start();
$db = new mysqli("localhost", "root", "", "projektaufgabe");
if ($db->connect_error) {
    die("Verbindung fehlgeschlagen: " . $db->connect_error);
}

// Verarbeitung von Antworten und Bearbeitungsanfragen
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['antwort_text'], $_POST['problem_id'])) {
        // Sicherheitsmaßnahmen: Escaping der Eingaben
        $antwort_text = $db->real_escape_string($_POST['antwort_text']);
        $problem_id = $db->real_escape_string($_POST['problem_id']);

        if (is_numeric($problem_id)) {
            $insert_query = "INSERT INTO antworten (problem_id, user, antwort_text) VALUES (?, ?, ?)";
            $stmt = $db->prepare($insert_query);

            if ($stmt) {
                $user = $db->real_escape_string($_SESSION['anmeldename']);
                $stmt->bind_param("iss", $problem_id, $user, $antwort_text);
                if ($stmt->execute()) {
                    echo 'success';
                } else {
                    echo 'error';
                }
                $stmt->close();
            }
            exit();
        } else {
            echo 'Ungültige Problem-ID.';
        }
    }
}

$kategorien = array(
    0 => 'Internet',
    1 => 'Hardware',
    2 => 'Software',
);

$statusFarben = array(
    0 => 'red',
    1 => 'yellow',
    2 => 'green',
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Problemseite - Mitarbeiter</title>
    <link rel="stylesheet" href="Styles/mitarbeiterstyles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
<div class="main">
    <div class="menu">
        <a href="../Login/login.html">Logout</a>
    </div>
</div>

<div class="content">
    <h2>Übersicht aller Tickets</h2>
    <table class="beitragstabelle" id="ticketTable">
        <!-- Tabellenkopf -->
        <tr>
            <th>Problem-ID</th>
            <th>Beitrag</th>
            <th>Kategorie</th>
            <th>Von</th>
            <th>Datum und Uhrzeit</th>
            <th>Status</th>
            <th>Aktionen</th>
        </tr>
        <?php
        // Abrufen und Anzeigen der Probleme aus der Datenbank
        $anfrage = $db->prepare("SELECT * FROM problemforum ORDER BY kategorie_id, datum, uhrzeit");
        $anfrage->execute();
        $ergebnis = $anfrage->get_result();

        while ($zeile = $ergebnis->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($zeile['problem_id']) . "</td>";
            echo "<td>" . htmlspecialchars($zeile['betreff']) . "</td>";
            echo "<td>" . htmlspecialchars($kategorien[$zeile['kategorie_id']]) . "</td>";
            echo "<td>" . htmlspecialchars($zeile['user']) . "</td>";
            echo "<td>" . htmlspecialchars($zeile['datum']) . " um " . htmlspecialchars($zeile['uhrzeit']) . "</td>";
            echo "<td>";
            echo "<select class='status-dropdown status-" . htmlspecialchars($statusFarben[$zeile['bearbeitungsstatus']]) . "' data-problem-id='" . $zeile['problem_id'] . "'>";
            echo "<option value='0'" . ($zeile['bearbeitungsstatus'] == 0 ? " selected" : "") . ">Offen</option>";
            echo "<option value='1'" . ($zeile['bearbeitungsstatus'] == 1 ? " selected" : "") . ">In Bearbeitung</option>";
            echo "<option value='2'" . ($zeile['bearbeitungsstatus'] == 2 ? " selected" : "") . ">Geschlossen</option>";
            echo "</select>";
            echo "</td>";
            echo "<td>";
            echo "<button class='action-button' onclick='openAnswerModal(" . $zeile['problem_id'] . ")'>Antworten</button>";
            echo "<button class='action-button' onclick='openEditModal(" . $zeile['problem_id'] . ")'>Bearbeiten</button>";
            echo "</td>";
            echo "</tr>";
        }
        $anfrage->close();
        ?>
    </table>
</div>

<!-- Antwort-Popup-Fenster -->
<div id="answerModal">
    <h3>Antworten auf Ticket</h3>
    <form id="answerForm">
        <input type="hidden" id="problemId" name="problem_id">
        <textarea id="antwortText" name="antwort_text" rows="4" cols="50" placeholder="Geben Sie Ihre Antwort hier ein"></textarea>
        <br>
        <input type="button" id="submitAnswer" value="Antwort absenden">
        <button class="close-button" onclick="closeAnswerModal()">Schließen</button>
    </form>
</div>

<!-- JavaScript-Funktionen für die Interaktion -->
<script>
    function openAnswerModal(problemId) {
        document.getElementById('problemId').value = problemId;
        document.getElementById('answerModal').style.display = 'block';
    }

    function closeAnswerModal() {
        document.getElementById('answerModal').style.display = 'none';
    }

    function openEditModal(problemId) {
        var url = "edit.php?problemId=" + problemId;
        var editWindow = window.open(url, "_blank", "width=400,height=300");
        var checkEditWindowClosed = setInterval(function() {
            if (editWindow.closed) {
                clearInterval(checkEditWindowClosed);
                location.reload();
            }
        }, 500);
    }

    // AJAX-Anfrage zur Aktualisierung des Bearbeitungsstatus
    $('.status-dropdown').change(function() {
        var problemId = $(this).data('problem-id');
        var neuerStatus = $(this).val();
        $.ajax({
            type: "POST",
            url: "status_update_handler.php",
            data: { problem_id: problemId, status: neuerStatus },
            success: function() {
                location.reload();
            }
        });
    });

    // AJAX-Anfrage zum Speichern der Antwort
    $('#submitAnswer').click(function() {
        var problemId = $('#problemId').val();
        var antwortText = $('#antwortText').val();
        $.ajax({
            type: "POST",
            url: "save_answer.php",
            data: { problem_id: problemId, antwort_text: antwortText },
            success: function(response) {
                if (response === 'success') {
                    alert('Antwort wurde gespeichert.');
                    location.reload();
                } else {
                    alert('Fehler beim Speichern der Antwort.');
                }
                closeAnswerModal();
            }
        });
    });
</script>

</body>
</html>
