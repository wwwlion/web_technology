<html>
<head>
    <link rel="stylesheet" href="../Register/styles_register.css">
</head>
<body>
<div class="main">
    <div class="menu">
        <a href="../Index/index.html">Start</a> <!-- Link zur Startseite -->
        <a href="../Login/login.php">Login</a> <!-- Link zur Login-Seite -->
        <a href="../Seiten/welcome_kunde.php">Kategorien</a> <!-- Link zu den Kategorien im Forum -->
    </div>
</div>
<div class="content">
    <?php
    session_start();
    $user = $_SESSION["anmeldename"]; //benutzername aus der session
    $betreff = $_POST['betreff']; // Betreff des beitrages aus dem Postarray
    $problembeschreibung = $_POST["problembeschreibung"]; // problembeschreibung aus postarray
    $kategorienumber = $_POST["kategorie"]; // Kategorienummer aus dem postarray

    $punkt = ".";
    $datum = date("d.m.Y"); //aktuelles datum einbinden
    $zeit = date("H:i:s"); // aktuelle Uhrzeit einbinden

    // hier wird der Name, Betreff sowie Beschreibung sicher/richtig behandelt, sowie umbrüche in der Problembeschreibung als HTML Break Tags dagestellt
    $user = htmlspecialchars($user, ENT_QUOTES, 'UTF-8');
    $betreff = htmlspecialchars($betreff, ENT_QUOTES, 'UTF-8');
    $problembeschreibung = htmlspecialchars($problembeschreibung, ENT_QUOTES, 'UTF-8');
    $problembeschreibung = nl2br($problembeschreibung);

    // Datenbank verbindung sowie auswahl der Datenbank
    $db = mysqli_connect("localhost", "root", "") or die("<b>Zur Zeit kein Connect zum Datenbankserver!</b>");
    mysqli_select_db($db, "projektaufgabe") or die("<b>Datenbank konnte nicht angesprochen werden</b>");

    // Beitrag in die Datenbank einfügen
    $query = "INSERT INTO problemforum (kategorie_id, bezugs_id, user, bearbeitungsstatus, datum, uhrzeit, betreff, beitragstext, antwort, bewertung) 
            VALUES ($kategorienumber, '0', '$user', '0', '$datum', '$zeit', '$betreff', '$problembeschreibung', 'false', NULL)";
    mysqli_query($db, $query) or die("<b>Fehler bei der Datenbankanfrage</b>");

    mysqli_close($db); //schließen der verbindung
    echo "<p>Vielen Dank für Ihren Beitrag!</p>"; //erfolgsmeldung
    echo "<a href='../Seiten/welcome_kunde.php'>Zurück zum Forumsüberblick</a>"; //link zum forumsüberblick
    ?>
</div>
</body>
</html>
