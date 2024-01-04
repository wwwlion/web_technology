<html>
<head>
    <link rel="stylesheet" href="../styles.css">
    <title>Problemforum - Antwort</title>
</head>
<body>
<div class="main">
    <div class="menu">
        <a href="../index.html">Start</a> <!-- Link zur Startseite -->
        <a href="../Login/login.php">Login</a> <!-- Link zur Login-Seite -->
        <a href="../Seiten/problemseite_kunde.php">Forum</a> <!-- Link zur Problemseite im Forum -->
    </div>
</div>
<div class="content">
    <div style="font-family: arial;">
        <h2>Ihre Antwort auf das Problem:</h2>

        <?php
        session_start();
        $id = $_GET['problem_id']; // Problem-ID aus dem GET-Parameter holen
        $db = mysqli_connect("localhost", "root", ""); // Verbindung zur Datenbank herstellen
        mysqli_select_db($db, "projektaufgabe"); // Datenbank auswählen
        $query = "SELECT * FROM problemforum WHERE problem_id = '$id'"; // Query, um das Problem abzurufen
        $result = mysqli_query($db, $query); // Query ausführen
        $row = mysqli_fetch_row($result); // Erste Zeile des Ergebnisses holen
        $betreff = "Re: " . $row[7]; // Betreff der Antwort erstellen
        ?>

        <form method="post" action="problem_replay_entry.php">
            <table border="0">
                <tr>
                    <td>Betreff-Zeile</td>
                    <td><input type="text" name="betreff" value="<?php echo $betreff; ?>"></td> <!-- Eingabefeld für den Betreff, vorbelegt mit dem erstellten Betreff -->
                </tr>
                <tr>
                    <td>Ihr Eintrag</td>
                    <td><textarea name="problemantwort" cols="40" rows="5"></textarea></td> <!-- Textbereich für die Antwort -->
                </tr>
                <tr>
                    <td>
                        <input type="hidden" name="problem_id" value="<?php echo $id; ?>"> <!-- Verstecktes Eingabefeld für die Problem-ID -->
                        <input type="submit" value="Abschicken"> <!-- Abschicken-Button -->
                        <input type="reset" value="Löschen"> <!-- Löschen-Button -->
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
</body>
</html>
