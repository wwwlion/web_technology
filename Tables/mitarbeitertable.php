<?php
// Verbindung zur Datenbank herstellen
$db = mysqli_connect("localhost", "root", "");

// Datenbank auswählen
mysqli_select_db($db, "projektaufgabe");

// Tabelle "mitarbeiter" erstellen
$anfrage = "CREATE TABLE mitarbeiter (
    employe_id INT NOT NULL AUTO_INCREMENT,
    empusername VARCHAR(100),
    emppassword VARCHAR(100),
    empfirma VARCHAR(100),
    PRIMARY KEY (employe_id)
)";

mysqli_query($db, $anfrage);

// Datenbankverbindung schließen
mysqli_close($db);
?>

<!--
    Der Code stellt eine Verbindung zur Datenbank her und erstellt eine Tabelle mit dem Namen "mitarbeiter".
    Die Tabelle enthält drei Spalten: employe_id, empusername und emppassword.
    Die employe_id ist ein automatisch inkrementierender Primärschlüssel.
    Anschließend wird die Verbindung zur Datenbank geschlossen.
-->

<!--
    Hier könnte ein INSERT INTO Befehl eingefügt werden, um Datensätze in die Tabelle "mitarbeiter" einzufügen.
    Die empusername und emppassword Werte müssten dabei angegeben werden.
    Derzeit ist der INSERT INTO Befehl auskommentiert, da keine konkreten Werte angegeben sind.
-->
