<?php

class CookieManager {
    // Setzt ein Cookie
    public static function setCookie($name, $value, $expiry, $path = '/', $domain = '', $secure = false, $httponly = true) {
        setcookie($name, $value, time() + $expiry, $path, $domain, $secure, $httponly);
    }

    // Holt den Wert eines Cookies
    public static function getCookie($name) {
        return $_COOKIE[$name] ?? false;
    }

    // Löscht ein Cookie
    public static function deleteCookie($name, $path = '/', $domain = '', $secure = false, $httponly = true) {
        if (isset($_COOKIE[$name])) {
            setcookie($name, '', time() - 3600, $path, $domain, $secure, $httponly);
            unset($_COOKIE[$name]);
        }
    }

    // Prüft, ob ein Cookie existiert
    public static function isCookieSet($name) {
        return isset($_COOKIE[$name]);
    }
}

// Verwendung der Klasse
CookieManager::setCookie("user", "John Doe", 3600); // Setzt ein Cookie für 1 Stunde
$user = CookieManager::getCookie("user"); // Holt den Wert des Cookies
CookieManager::deleteCookie("user"); // Löscht das Cookie
$isCookieSet = CookieManager::isCookieSet("user"); // Prüft, ob das Cookie gesetzt ist

?>
