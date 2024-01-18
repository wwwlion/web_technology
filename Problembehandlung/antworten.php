<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Datenbankverbindung
    $db = mysqli_connect("localhost", "root", "", "Projektaufgabe");

    $problem_id = $_POST['problem_id'];
    $antwort = $_POST['antwort'];

    // Antwort in die Datenbank einfügen (Annahme: es gibt eine Spalte für Antworten)
    $query = "UPDATE problemforum SET antwort = '$antwort' WHERE problem_id = '$problem_id'";
    mysqli_query($db, $query);

    // Weiterleitung oder Meldung nach dem Einreichen
    echo "Antwort gespeichert.";
    // Optional: Weiterleitung zurück zur Hauptseite
    // header('Location: welcome_mitarbeiter.php');
}

// Problem-ID aus der URL holen
$problem_id = $_GET['problem_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Antwort auf Ticket</title>
</head>
<body>
<form method="post">
    <input type="hidden" name="problem_id" value="<?php echo $problem_id; ?>">
    <label for="antwort">Antwort:</label>
    <textarea name="antwort" required></textarea>
    <input type="submit" value="Antwort senden">
</form>
</body>
</html>

