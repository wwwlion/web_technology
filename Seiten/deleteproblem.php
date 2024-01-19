<?php
session_start();
$db = mysqli_connect("localhost", "root", "", "projektaufgabe");
$user = $_SESSION["anmeldename"];

if (!$db) {
    die("Verbindung zur Datenbank fehlgeschlagen: " . mysqli_connect_error());
}

if (isset($_POST["problemId"])) {
    $problemId = $_POST["problemId"];

    // Überprüfen, ob das Problem dem angemeldeten Benutzer gehört, um unberechtigtes Löschen zu verhindern
    $checkOwnershipQuery = "SELECT user FROM problemforum WHERE problem_id = '$problemId'";
    $result = mysqli_query($db, $checkOwnershipQuery);
    $row = mysqli_fetch_assoc($result);

    if ($row["user"] == $user) {
        // Das Problem gehört dem angemeldeten Benutzer, daher können wir es löschen
        $deleteQuery = "DELETE FROM problemforum WHERE problem_id = '$problemId'";
        $deleteResult = mysqli_query($db, $deleteQuery);

        if ($deleteResult) {
            echo "success";
        } else {
            echo "Fehler beim Löschen des Problems: " . mysqli_error($db);
        }
    } else {
        echo "Sie sind nicht berechtigt, dieses Problem zu löschen.";
    }
}

