<?php
session_start();
$kategorienum = $_GET["k"]; // Kategorienummer aus dem GET-Parameter holen
$user = $_SESSION["anmeldename"]; // Benutzername aus der Session holen

switch ($kategorienum) {
    case 0:
        $kategorie = "Internet";
        break;
    case 1:
        $kategorie = "Hardware";
        break;
    case 2:
        $kategorie = "Software";
        break;
    default:
        $kategorie = "Problemauswahl";
        break;
}

$db=mysqli_connect("localhost","root","");
mysqli_select_db($db,"projektaufgabe");
?>

<html>
<head>
    <link rel="stylesheet" href="Styles/kundestyles.css">
</head>
<body>
<div class="main">
    <div class="menu">
        <a href="../index.html">Start</a>
        <a href="../Login/login.php">Login</a>
        <a href="welcome_kunde.php">Kategorien</a>
    </div>
</div>

<div class="content">
    <div class="kategorieschrift">
        <?php
        echo "<h2>$kategorie</h2>"; // Anzeige der Kategorieüberschrift
        ?>
    </div>

    <table style="border:black solid; border-width: 1px; border-style:inset"  width="500">
        <th>Beitrag</th>
        <th>von</th>
        <th>Datum und Uhrzeit</th>

        <?php
        // Holen aller Probleme mit Bearbeitungsstatus 1 und 2
        // Filter nach Kategorie
        $anfrage="SELECT * FROM problemforum WHERE bearbeitungsstatus = '1' OR bearbeitungsstatus = '2'";
        $ergebnis=mysqli_query($db,$anfrage);
        $anz=mysqli_num_rows($ergebnis);
        for ($a=$anz-1;$a>-1; $a--) {
            mysqli_data_seek ($ergebnis, $a);
            $zeile=mysqli_fetch_row($ergebnis);
            if ($zeile[9]=="false") { // Überprüfen, ob der Beitrag keine Antwort ist
                if ($zeile[1]==$kategorienum) { // Überprüfen, ob der Beitrag zur ausgewählten Kategorie gehört
                    ausgabe($db, $zeile); // Funktion zum Anzeigen des Beitrags aufrufen
                }
            }
        }
        print ("</table>");
        mysqli_close($db);

        function ausgabe( $db, $datensatz) {
            print ("<tr align='left'>");
            print ("<td>");
            if ($datensatz[9]=="false") {
                print ("&nbsp;");
            }
            else {
                print ("&nbsp;&nbsp;&nbsp;&nbsp;");
                print ("&nbsp;&nbsp;&nbsp;&nbsp;");
            }
            if ($datensatz[9] == "false") { // Überprüfen, ob der Beitrag keine Antwort ist
                print ("<a href='../Problembehandlung/problem_read.php?problem_id=");
                print ($datensatz[0]);
                print ("'>");
                print ($datensatz[7]); // Beitragstitel anzeigen
                print ("</a>");
            } else {
                print ("$datensatz[7]"); // Antwort anzeigen
            }
            print ("</td>");
            print ("<td>");
            print ($datensatz[3]); // Benutzername anzeigen
            print ("</td>");
            print ("<td>");
            print ($datensatz[5]); // Datum anzeigen
            print (" um ");
            print ($datensatz[6]); // Uhrzeit anzeigen
            print ("</td>");
            print ("</tr>");
            if ($datensatz[2]>0) {
                antwort_holen($db, $datensatz[2]); // Rekursiv alle Antworten zu diesem Beitrag anzeigen
            }
        }

        function antwort_holen( $db,$id) {
            $anf="SELECT * FROM problemforum WHERE problem_id='";
            $anf.=$id;
            $anf.="'";
            $er=mysqli_query($db, $anf);
            $z=mysqli_fetch_row($er);
            ausgabe($db,$z);
        }
        ?>
        <br> <br>
        <a href="../Problembehandlung/neuesproblem.html">Hier neues Problem einstellen</a>

</div>
</div>

</body>
</html>
