<?php
session_start();
$db = new mysqli("localhost", "root", "", "projektaufgabe");
if ($db->connect_error) {
    die("Verbindung fehlgeschlagen: " . $db->connect_error);
}

// Verarbeitung von Antworten und Bearbeitungsanfragen
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['antwort_text']) && isset($_POST['problem_id'])) {
        $antwort_text = $_POST['antwort_text'];
        $problem_id = $_POST['problem_id'];

        $insert_query = "INSERT INTO antworten (problem_id, user, antwort_text) VALUES (?, ?, ?)";
        $stmt = $db->prepare($insert_query);

        if ($stmt) {
            $user = $_SESSION['anmeldename'];
            $stmt->bind_param("iss", $problem_id, $user, $antwort_text);
            if ($stmt->execute()) {
                echo 'success';
            } else {
                echo 'error';
            }
            $stmt->close();
        }
        exit();
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
    <style>
        /* Hintergrundfarbe für das Status-Dropdown-Menü ändern */
        .status-dropdown {
            padding: 5px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        /* Farbzuordnung für Status */
        .status-dropdown.status-rot { background-color: red; }
        .status-dropdown.status-gelb { background-color: yellow; }
        .status-dropdown.status-gruen { background-color: green; }

        .status-dropdown option {
            padding: 2px;
        }

        .action-button {
            padding: 5px 10px;
            background-color: #fff;
            margin: 5px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .action-button:hover {
            background-color: #fff;
        }

        /* Stil für das Antwort-Popup-Fenster */
        #answerModal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 20px;
            border: 1px solid #ccc;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
        }

        /* Stil für das Antwort-Formular im Popup */
        #answerModal form {
            margin-top: 10px;
        }

        /* Stil für den Schließen-Button im Popup */
        .close-button {
            background-color: #ccc;
            color: #fff;
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        /* Hinzugefügter CSS-Stil für das Antwort-Popup */
        #answerModal h3 {
            color: #333;
            font-size: 18px;
            margin-bottom: 10px;
        }

        #answerModal textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            resize: vertical;
        }

        #answerModal #submitAnswer {
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
            cursor: pointer;
            margin-top: 10px;
        }

        #answerModal #submitAnswer:hover {
            background-color: #0056b3;
        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
<div class="main">
    <div class="menu">
        <a href="../Login/login.php">Logout</a>
    </div>
</div>

<div class="content">
    <h2>Übersicht aller Tickets</h2>

    <table class="beitragstabelle" id="ticketTable">
        <tr>
            <th>Problem-ID</th>
            <th>Beitrag</th>
            <th>Kategorie</th>
            <th>Von</th>
            <th>Datum und Uhrzeit</th>
            <th>Status </th>
            <th>Aktionen</th>
        </tr>
        <?php
        $anfrage = $db->prepare("SELECT * FROM problemforum ORDER BY kategorie_id, datum, uhrzeit");
        $anfrage->execute();
        $ergebnis = $anfrage->get_result();

        while ($zeile = $ergebnis->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($zeile['problem_id']) . "</td>";
            echo "<td>" . htmlspecialchars($zeile['betreff']) . "</td>";
            echo "<td>" . htmlspecialchars($kategorien[$zeile['kategorie_id']]);
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

<script>
    function openAnswerModal(problemId) {
        // Setzen Sie die Problem-ID im Antwort-Formular
        document.getElementById('problemId').value = problemId;
        // Zeigen Sie das Antwort-Popup-Fenster an
        document.getElementById('answerModal').style.display = 'block';
    }

    function closeAnswerModal() {
        // Verbergen Sie das Antwort-Popup-Fenster
        document.getElementById('answerModal').style.display = 'none';
    }

    function openEditModal(problemId) {
        var url = "edit.php?problemId=" + problemId;
        var editWindow = window.open(url, "_blank", "width=400,height=300");

        // Überwachung, ob das Popup-Fenster geschlossen wurde
        var checkEditWindowClosed = setInterval(function() {
            if (editWindow.closed) {
                clearInterval(checkEditWindowClosed);
                location.reload(); // Aktualisiert die Hauptseite, wenn das Popup geschlossen wird
            }
        }, 500); // Überprüft alle 500 Millisekunden
    }

    // Event-Handler für Änderungen im Status-Dropdown
    $('.status-dropdown').change(function() {
        var problemId = $(this).data('problem-id');
        var neuerStatus = $(this).val();
        $.ajax({
            type: "POST",
            url: "status_update_handler.php",
            data: { problem_id: problemId, status: neuerStatus },
            success: function() {
                location.reload(); // Seite neu laden, um Änderungen anzuzeigen
            }
        });
    });

    // Event-Handler für das Absenden der Antwort
    $('#submitAnswer').click(function() {
        var problemId = $('#problemId').val();
        var antwortText = $('#antwortText').val();
        $.ajax({
            type: "POST",
            url: "save_answer.php", // Ersetzen Sie 'diesen_part_ersetzen_durch_save_answer.php' durch den tatsächlichen Dateinamen Ihrer PHP-Datei zum Speichern der Antwort
            data: { problem_id: problemId, antwort_text: antwortText },
            success: function(response) {
                if (response === 'success') {
                    alert('Antwort wurde gespeichert.');
                    location.reload(); // Seite neu laden, um die Antwort anzuzeigen
                } else {
                    alert('Fehler beim Speichern der Antwort.');
                }
                closeAnswerModal(); // Antwort-Popup schließen
            }
        });
    });
</script>

</body>
</html>
