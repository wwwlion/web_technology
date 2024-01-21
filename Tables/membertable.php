<?php
// Verbindung zur Datenbank herstellen
$db = mysqli_connect("localhost", "root", "");

// Datenbank auswählen
mysqli_select_db($db, "projektaufgabe");

// Tabelle "member" erstellen
$anfrage = "CREATE TABLE member (
    member_id INT NOT NULL AUTO_INCREMENT,
    memberusername VARCHAR(100),
    memberpassword VARCHAR(100),
    memberfirma VARCHAR(100),
    memberemail VARCHAR(100),
    PRIMARY KEY (member_id)
)";
mysqli_query($db, $anfrage);

// Eintrag in die Tabelle "member" hinzufügen
$anfrage = "INSERT INTO member (memberusername, memberpassword, memberfirma) VALUES ('firstmember', 'testing' , 'testing')";
mysqli_query($db, $anfrage);

// Datenbankverbindung schließen
mysqli_close($db);
?>

<html>
<head>
    <title>Member Table</title>
</head>
<body>
<form action="membertable.php" method="get">
    <input type="radio" name="ar" value="1" checked> <!-- Radio-Button mit dem Wert 1, standardmäßig ausgewählt -->
    <input type="radio" name="ar" value="2"> <!-- Radio-Button mit dem Wert 2 -->
    <input type="submit" value="Abschicken"> <!-- Absenden-Button -->
</form>
</body>
</html>
