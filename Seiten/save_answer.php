<?php
session_start();
$db = new mysqli("localhost", "root", "", "projektaufgabe");
if ($db->connect_error) {
    die("Verbindung fehlgeschlagen: " . $db->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['antwort_text']) && isset($_POST['problem_id'])) {
        $antwort_text = $_POST['antwort_text'];
        $problem_id = $_POST['problem_id'];

        $user = $_SESSION['anmeldename'];

        $insert_query = "INSERT INTO antworten (problem_id, user, antwort_text) VALUES (?, ?, ?)";
        $stmt = $db->prepare($insert_query);

        if ($stmt) {
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
?>
