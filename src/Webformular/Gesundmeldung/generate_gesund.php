<?php
session_start();
# import some classes
use Classes\PdfGenerator;   
use Classes\Mailer;
use Classes\Datenbank;
use Classes\Daten;

$php_errormsg = "";
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    exit();
}

define("ACCESSCHECK", true);    # um auf PdfGenerator.php zuzugreifen
require_once dirname(__FILE__, 3) . "/vendor/autoload.php";
require_once "../verwaltung/tabellendaten.php";  

 
$uni_einrichtung = 'IV-Versorgungseinheit 5 (IVV5)';
$wiederaufnahme = $_POST["wiederaufnahme"];
$daten = new Daten();


if ($daten->amtsbezeichnung == "TM" || $daten->amtsbezeichnung == "AZUBI") {
    $dezernat = "3.4";
} elseif ($daten->amtsbezeichnung == "WM" || $daten->amtsbezeichnung == "SHK" || $daten->amtsbezeichnung == "SHB") {
    $dezernat = "3.3";
}


# array mit den Daten zum Ausfüllen des PDFs. 
$angaben = array(
    "Text1" => $uni_einrichtung,
    "Text2" => $daten->nachname . ", " . $daten->vorname,
    "Text3" => date('d.m.Y', strtotime($wiederaufnahme)),
    "Dropdown-Liste2" => $dezernat,
);


# main-function
function main_gesund()
{

    global  $daten, $angaben, $wiederaufnahme, $db;
    $db = new Datenbank();  # or specific args
    $mailerObj = new Mailer();
    $name = $daten->vorname . " " . $daten->nachname;
    $betreff = 'Neue Gesundmeldung - ' . date('d.m.Y');

    if ($daten->aktuellkrank === 1 && $daten->aktuellkrank_bis != NULL) {

        # ggf. lösche Einträge ab dem eingegebenen Datum bis 'aktuellkrank_bis'-Datum, und setze `aktuellkrank` auf 0.
        $delete = $db->execute_query("DELETE FROM `stundenzettel` WHERE `account` = ? and `aufgabe` = ? and `datum` >= ? and `datum` <= ? ;", $daten->kennung, 'Krankmeldung', $wiederaufnahme, $daten->aktuellkrank_bis);
        if (!$db->get_query()->errno) {     # ??!!
            echo "affectedRows (delete): " . $delete->affectedRows() . '<br>';
        } else {
            echo ("SQL-Query fehlgeschlagen. " . $db->get_query()->error . "<br>");
        }
        $update = $db->execute_query("UPDATE `krank` SET `aktuellkrank` = ?, `bis` = NULL WHERE `account` = ?;", 0, $daten->kennung);


        $pdf_generator = new PdfGenerator();
        $pdf_result = $pdf_generator->generate_gesund($angaben);     # generate the filled pdf
        $filled_pdf = $pdf_result['pdf_name'];
        $path_to_file = $pdf_result['zielpath'];                     # Pfad des ausgefüllten PDFs 
        echo 'error message: ' . $pdf_result['error_msg'] . "<br><br>";
        echo "path_to_file: " . $path_to_file . "<br><br>";
        var_dump($filled_pdf);
        echo "<br>";

        # falls pdf erfolgreich erzeugt wurde, trage die Pdf-datei in die 1. Tabelle ein (zum signieren) 
        if ($pdf_result['output_msg'] === true) {
            insertRow(pathinfo($path_to_file, PATHINFO_BASENAME), date('d.m.Y'), $name, false);
        }

         # Vorgesetzten per Email informieren
        $email_body = "Sehr geehrte*r Nutzer*in, <br><br> \r\rEs liegt eine neue Gesundmeldung zur Bearbeitung vor! Folgende Person hat sich gesundgemeldet: <br><br> \r\rName: " . $name . "<br>\rTag der Wiederaufnahme des Dienstes: " . date('d.m.Y', strtotime($wiederaufnahme)) . "<br><br> \r\r\rMit freundlichen Grüßen";
        $mailerObj->sendEmail($email_body, $betreff, $daten->kennung, $name, $path_to_file, $filled_pdf);
        header("Location: ../download.php?page=gesund" );    # Send a raw HTTP header (No output before sending headers!)
        exit();
    } else { 
    }

    
}




main_gesund();
//*==========================================
