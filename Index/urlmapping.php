<?php
// URL extrahieren
$request = $_SERVER['REQUEST_URI'];

// Einfaches Routing basierend auf der angeforderten URL
switch ($request) {
    case '/register':
        // Wenn '/register' angefordert wird, lade die Registrierungsseite
        require __DIR__ . '/Register/register.html';
        break;
    case '/login':
        // Wenn '/login' angefordert wird, lade die Login-Seite
        require __DIR__ . '/Login/login.html';
        break;
    case '/mitarbeiter':
        // Wenn '/mitarbeiter' angefordert wird, lade die Mitarbeiterproblemseite
        require __DIR__ . '/Seiten/problemseite_mitarbeiter.php';
        break;
    case '/kunden':
        // Wenn '/kunden' angefordert wird, lade die Kundenproblemseite
        require __DIR__ . '/Seiten/problemseite_kunden.php';
        break;
    case '/ticket':
        // Wenn '/ticket' angefordert wird, lade die Seite zur Erstellung eines neuen Tickets
        require __DIR__ . '/Problembehandlung/neuesproblem.php';
        break;
    case '/willkommen':
        // Wenn '/willkommen' angefordert wird, lade die Willkommensseite für Kunden
        require __DIR__ . '/Seiten/welcome_kunde.php';
        break;
    // Weitere Routen können hier hinzugefügt werden, falls benötigt
    default:
        // Wenn keine passende Route gefunden wird, setze den HTTP-Statuscode auf 404
        http_response_code(404);
        // Lade eine 404-Fehlerseite
        require __DIR__ . '/views/404.php';
        break;
}
