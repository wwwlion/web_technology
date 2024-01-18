<?php
session_start(); // Start der Sitzung, um auf Sitzungsvariablen zugreifen zu können
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Willkommen</title>
    <link rel="stylesheet" href="styles_seiten.css"> <!-- Einbindung eines Stylesheets -->
</head>
<body>
<div class="main">
    <div class="menu">
        <a href="../Index/index.html">Start</a> <!-- Link zur Startseite -->
        <a href="../Register/register.php">Registrieren</a> <!-- Link zur Registrierungsseite -->
        <a href="../Login/login.php">Login</a> <!-- Link zur Login-Seite -->
    </div>

    <div class="content">
        <h2>Willkommen, Mitarbeiter <?php echo $_SESSION["anmeldename"];?> von  <?php echo$firma = $_SESSION["firma"]; ?></h2> <!-- Anzeige des Mitarbeiternamens aus der Sitzungsvariablen -->

        <div class="katlinks">
            <h4>Bitte wählen Sie eine Kategorie:</h4>
            <a href="problemseite_mitarbeiter.php?k=0">Internet</a><br> <!-- Link zur Problemseite für die Kategorie "Internet" -->
            <a href="problemseite_mitarbeiter.php?k=1">Hardware</a><br> <!-- Link zur Problemseite für die Kategorie "Hardware" -->
            <a href="problemseite_mitarbeiter.php?k=2">Software</a><br> <!-- Link zur Problemseite für die Kategorie "Software" -->
        </div>
    </div>
</div>
</body>
</html>
