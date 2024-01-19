<?php
session_start();
$db = mysqli_connect("localhost", "root", "", "Projektaufgabe");
$user = $_SESSION["anmeldename"];
if (!$db) {
    die("Verbindung zur Datenbank fehlgeschlagen: " . mysqli_connect_error());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Willkommen</title>
    <link rel="stylesheet" href="Styles/welcomekundestyles.css">
    <style>
        .problem-row {
            border: 1px solid #ccc;
            padding: 10px;
            margin: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            background-color: #0f3460;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 4px;
        }

        .problem-row:hover {
            background-color: #4ecca3;
            border-radius: 4px;
        }

        .delete-button {
            background-color: darkred;
            color: #ffffff;
            padding: 5px 10px;
            border: none;
            cursor: pointer;
            display: inline-list-item;
            border-radius: 4px;
        }
    </style>

    <script>
        function openProblemPopup(problemId) {
            // Öffnen Sie ein Popup-Fenster, um das Problem mit der angegebenen Problem-ID zu bearbeiten
            var width = 600;
            var height = 400;
            var left = (screen.width - width) / 2;
            var top = (screen.height - height) / 2;
            var url = "editproblem.php?problemId=" + problemId;
            var popup = window.open(url, "_blank", "width=" + width + ",height=" + height + ",left=" + left + ",top=" + top);
            if (!popup || popup.closed || typeof popup.closed == 'undefined') {
                alert("Bitte deaktivieren Sie Ihren Popup-Blocker, um das Problem zu bearbeiten.");
            } else {
                // Überwachen Sie das Popup-Fenster und führen Sie eine Aktion aus, wenn es geschlossen wird
                var timer = setInterval(function () {
                    if (popup.closed) {
                        clearInterval(timer);
                        window.opener.location.reload(); // Aktualisieren Sie die Seite, die das Popup geöffnet hat
                        closePopup(); // Schließen Sie das Popup
                    }
                }, 1000);
            }
        }

        // Schließe das Popup
        function closePopup() {
            window.close();
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

    <h2>Willkommen, <?php echo $user; ?></h2>
    <b>Folgende Anfragen hast du eingestellt und liegen den Mitarbeitern noch vor: </b>

    <?php
    // Abfrage aller Beiträge des aktuellen Benutzers
    $query = "SELECT problem_id, betreff, bearbeitungsstatus FROM problemforum WHERE user = '$user'";
    $result = mysqli_query($db, $query);
    if (!$result) {
        die("Abfrage fehlgeschlagen: " . mysqli_error($db));
    }
    $num = mysqli_num_rows($result);

    // Durchlaufen der Ergebnisdaten und Anzeigen der Beiträge des aktuellen Benutzers
    for ($i = 0; $i < $num; $i++) {
        $line = mysqli_fetch_row($result);
        $problem_id = $line[0];
        $betreff = $line[1];
        $bearbeitungsstatus = $line[2];

        // Konvertieren Sie den numerischen Bearbeitungsstatus in Text
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

        // Hier ist der separate Löschen-Button für jede Problemzeile
        echo "<button class='delete-button' onclick='deleteProblem($problem_id)'>Löschen</button>";
    }
    ?>
    <a href="../Problembehandlung/neuesproblem.html">Neues Problem erstellen</a>

</div>

<!--leider funktioniert das löschen noch nicht-->

<script>
    function deleteProblem(problemId) {
        if (confirm("Sind Sie sicher, dass Sie dieses Problem löschen möchten?")) {
            // Hier AJAX verwenden, um das Problem zu löschen
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "deleteproblem.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var response = xhr.responseText;
                    if (response === "success") {
                        alert("Das Problem wurde erfolgreich gelöscht.");
                        window.opener.location.reload(); // Aktualisieren Sie die Seite, die das Popup geöffnet hat
                        window.close(); // Schließen Sie das Popup
                    } else {
                        alert("Fehler beim Löschen des Problems: " + response);
                    }
                }
            };
            xhr.send("problemId=" + problemId);
        }
    }

</script>
</body>
</html>
