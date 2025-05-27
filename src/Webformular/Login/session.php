<?php
@session_start(); # soll auf jede Seite starten


# bei Inaktivität von mehr als 8 Stunden Session-daten löschen
if ((isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 3600 * 8))) {
    // letzter request war seit mehr als 8 Stunden
    session_unset();     // unset die $_SESSION Variablen  
    session_destroy();   // destroy alle in session gespeicherten Daten
    header('location: ./login.php');
}
$_SESSION['LAST_ACTIVITY'] = time();


# alle 2 Stunden erzeuge neue Session ID
if (!isset($_SESSION['CREATED'])) {
    $_SESSION['CREATED'] = time();
} else if (time() - $_SESSION['CREATED'] > 3600 * 2) {
    session_regenerate_id(true);    // Update the current session id with a newly generated one
    $_SESSION['CREATED'] = time();
}
