<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Datenbankverbindung
    $db = mysqli_connect("localhost", "root", "", "Projektaufgabe");

    $problem_id = $_POST['problem_id'];
    $betreff = $_POST['betreff'];

    // Betreff des Tickets aktualisieren
    $query = "UPDATE problemforum SET betreff = '$betreff' WHERE problem_id = '$problem_id'";
    mysqli_query($db, $query);

    // Weiterleitung oder Meldung nach dem Einreichen
    echo "Ticket aktualisiert.";
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
    <title>Ticket bearbeiten</title>
</head>
<body>
<form method="post">
    <input type="hidden" name="problem_id" value="<?php echo $problem_id; ?>">
    <label for="betreff">Betreff:</label>
    <input type="text" name="betreff" required>
    <input type="submit" value="Änderungen speichern">
</form>
</body>
</html>

