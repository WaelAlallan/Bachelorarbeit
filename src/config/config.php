<?php

#============ Datenbank - Zugangsdaten
if (!defined('DB_HOST')) {
    define('DB_HOST', 'localhost');
}
if (!defined('DB_USER')) {
    define('DB_USER', 'root');
}
if (!defined('DB_PASSWORD')) {
    define('DB_PASSWORD', '');
}
if (!defined('DB_NAME')) {
    define('DB_NAME', 'zeiterfassung_db');
}

#============= Mailserver
//? Wenn die Anwendung in Praxis eingesetzt wird, sollten diese Daten dann in der Konfigurationsdatei o.Ä. des Webservers stehen
if (!defined('SMTP_HOST')) {
    define('SMTP_HOST', 'secmail.uni-muenster.de');
}
if (!defined('SMTP_USERNAME')) {
    define('SMTP_USERNAME', 'w_alal01');
}
if (!defined('SMTP_PASSWORD')) {
    define('SMTP_PASSWORD', '');    // add password
}

#===================================
if (!defined('generated_files_dir')) {
    define('generated_files_dir', dirname(__FILE__, 3) . "/generated_files");
}
if (!defined('JSignPdfjar')) {
    define('JSignPdfjar', dirname(__FILE__, 2) . "/JSignPdf/JSignPdf.jar");
}
if (!defined('background_img')) {
    define('background_img', dirname(__FILE__, 3) . "/images/adobe_logo.png");
}

