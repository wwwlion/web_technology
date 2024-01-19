<?php
// Verarbeitung von Antworten
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Antwort- und Bearbeitungslogik hier einfügen

    // Beispiel: Antwort in die Datenbank einfügen
    $problem_id = $_POST['problem_id'];
    $antwort = $_POST['antwort'];

    // Datenbankverbindung herstellen (z.B. $db = mysqli_connect(...))
    // Antwort in die Datenbank einfügen (Annahme: es gibt eine Spalte für Antworten)
    // ...

    // Erfolgsmeldung oder Weiterleitung anzeigen
    echo "Antwort erfolgreich gesendet.";
    // Optional: Weiterleitung zurück zur Hauptseite
    // header('Location: mitarbeiter.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="../Seiten/Styles/answer_edit.css">
<head>
    <meta charset="UTF-8">
    <title>Antwort auf Ticket</title>
</head>
<body>
<form method="post">
    <input type="hidden" name="problem_id" value="<?php echo $_GET['problemId']; ?>">
    <label for="antwort">Antwort:</label>
    <textarea name="antwort" required></textarea>
    <input type="submit" value="Antwort senden">
</form>
</body>
</html>
