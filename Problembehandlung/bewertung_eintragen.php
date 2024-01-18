<!--HTML-Markup für die Seite, einschließlich des head-Tags und des CSS-Links sowie die Navigationsleiste--!>
<html>
<head>
    <link rel="stylesheet" href="styles_behandlung.css">
</head>
<body>
<div class="main">
    <div class="menu">
        <a href="../Register/register.php">Registrieren</a> <!-- Link zur Registrierungsseite -->
        <a href="../Login/login.php">Login</a> <!-- Link zur Login-Seite -->
    </div>
</div>
<!--PHP-Logik, Sitzung (Session) gestartet und eine Verbindung zur Datenbank hergestellt. Dann Werte der Variablen $id und $bewertung aus dem POST-Array geholt--!>
<?php
session_start();
$db = mysqli_connect("localhost", "root", "");
mysqli_select_db($db, "projektaufgabe");

$id = $_POST["problemid"];
$bewertung = "";
//Switch-Anweisung verwendet, um den Wert von $_POST["bewertung"] zu überprüfen. Basierend auf dem Wert wird der Variable $bewertung entweder "abgelehnt" (bei 0) oder "angenommen" (bei 1) zugewiesen.
switch ($_POST["bewertung"]) {
    case 0:
        $bewertung = "abgelehnt";
        break;
    case 1:
        $bewertung = "angenommen";
        break;
}
//ausführung einer SQL-Abfrage, um die Bewertung in der Datenbanktabelle "problemforum" zu aktualisieren
$query = "UPDATE problemforum SET `bewertung` = '$bewertung' WHERE `problemforum`.`problem_id` = '$id'";
mysqli_query($db, $query);

//aktuallisiert den bearbeitungsstatus des Problems in der Datenbanktabelle "problemforum" basierend auf der Bewertung

//Angenommen: bearbeitungsstatus 2 ; abgelehnt: bearbeitungsstatus 3
if ($bewertung == "angenommen") {
    $statusquery = "UPDATE problemforum SET bearbeitungsstatus = '2' WHERE problemforum.problem_id = '$id' ";
    $statusquery2 = "UPDATE problemforum SET bearbeitungsstatus = '2' WHERE problemforum.bezugs_id = '$id' ";
    mysqli_query($db, $statusquery);
    mysqli_query($db, $statusquery2);
}

if ($bewertung == "abgelehnt") {
    $statusquery = "UPDATE problemforum SET bearbeitungsstatus = '3' WHERE problemforum.problem_id = '$id' ";
    $statusquery2 = "UPDATE problemforum SET bearbeitungsstatus = '3' WHERE problemforum.bezugs_id = '$id' ";
    mysqli_query($db, $statusquery);
    mysqli_query($db, $statusquery2);
}
//datenbankverbindung wird geschlossen und der benutzer wird informiert. Link in die Kategorie wird angeboten.
mysqli_close($db);
echo "<div class='content'> <p>Vielen Dank für Ihre Bewertung!</p><br>";
echo "<a href='../Seiten/welcome_kunde.php'>Zurück zu den Kategorien</a></div>";
?>

</body>
</html>
