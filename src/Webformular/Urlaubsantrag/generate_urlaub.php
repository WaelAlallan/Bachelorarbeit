<?php
session_start();

use Classes\PdfGenerator;       # import some classes
use Classes\Mailer;
use Classes\Datenbank;
use Classes\Daten;

$php_errormsg = "";
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    exit();
}

define("ACCESSCHECK", true);    # um auf PdfGenerator.php zuzugreifen 
require_once dirname(__FILE__, 3) . "/vendor/autoload.php";
require_once 'backend_urlaub.php';
require_once "../Verwaltung/tabellendaten.php";
require_once '../holidays.php';


$daten = new Daten();
$urlaub_beginn = $_POST["urlaub_beginn"];       # Beginn-datum des Urlaubs
$urlaub_ende = $_POST["urlaub_ende"];           # End-datum
$vertreter =  htmlspecialchars(stripslashes(trim($_POST["vertreter"])));
$digital_ID = $_FILES["digital_ID"]["tmp_name"];
$ID_password = $_POST["ID_password"];
//*=======
$object = new Urlaub($urlaub_beginn, $urlaub_ende);
$object->calc_dauer();          # basierend auf Urlaubsdaten berechne die Urlaubsdauer und wie viel der Mitarbeiter noch Urlaub hätte


# array mit den Daten zum Ausfüllen des PDFs. Keys sind Namen der Felder in dem PDF
$angaben = array(
    "Text1" => $daten->nachname . ", " . $daten->vorname,
    "Text3" => date('d.m.Y', strtotime($urlaub_beginn)),
    "Text4" => date('d.m.Y', strtotime($urlaub_ende)),
    "Text5" => $object->get_urlaubsdauer(),
    "Text6" => $vertreter,
    "Text8" => $daten->urlaubsanspruch,
    "Text9" => $daten->resturlaub,
    "Text10" => $daten->urlaub_erhalten,
    "Text11" => $object->get_urlaubssaldo_neu(),
);



function main_urlaub()
{
    global  $urlaub_beginn, $urlaub_ende, $angaben, $daten, $digital_ID, $ID_password;
    $name = $daten->vorname . " " . $daten->nachname;
    $mailerObj = new Mailer();
    $betreff = 'Neuer Urlaubsantrag - ' . date('d.m.Y');

    if (save_in_db()) {         #falls die daten erfolgreich in der DB gespeichert.
        $pdf_generator = new PdfGenerator();
        $pdf_result = $pdf_generator->generate_urlaub($angaben);     # generate the filled pdf
        $filled_pdf = $pdf_result['pdf_name'];
        $path_to_file = $pdf_result['zielpath'];                    # Pfad des ausgefüllten PDFs 
        echo 'error message: ' . $pdf_result['error_msg'] . "<br><br>";
        echo "path_to_file: " . $path_to_file . "<br><br>";
        var_dump($filled_pdf);


        // signieren falls pdf erfolgreich erzeugt wurde
        if ($pdf_result['output_msg'] === true) {
            $command = "cd " . generated_files_dir;
            $command .= "  &&  java -jar " . JSignPdfjar . "  -kst PKCS12 -ksf " . $digital_ID . " -ksp " . $ID_password . " --bg-path " . background_img .
                " --bg-scale -1 -llx 365 -lly 333 -urx 520 -ury 280 --visible-signature --out-suffix _S" . " " . $path_to_file . "  2>&1";

            $output =  exec($command, $out, $result_code);
            echo '<br><pre>';
            # var_dump($out);
            echo '</pre><br>';
            var_dump($result_code); # 0 means no errors
            echo '<br>';
            var_dump($output);

            if ($result_code === 0) {
                echo '<br>successfully signed!<br>';
                # unlink($path_to_file);    //? lösche ausgefüllte (unsignierte) pdf-Datei, falls sie erfolgreich signiert wurde
            } else {
                echo "<br>Das Signieren ist fehlgeschlagen: " . $output;
            }
        } else {
            echo "<br>zu signierende Datei wurde nicht gefunden!" . $pdf_result['error_msg'];
        }

        $signed_pdf = pathinfo($path_to_file, PATHINFO_FILENAME) . "_S.pdf";
        insertRow($signed_pdf, date('d.m.Y'), $name, false);  # vom Mitarbeiter signierte Datei als Eintrag in die 1. Tabelle hinzufügen

        # Email schicken und Benutzer zu einer Bestätigungsseite weiterleiten
        $email_body = "Sehr geehrte*r Nutzer*in, <br><br> \r\rEs liegt einen neuen Urlaubsantrag zur Bearbeitung vor! Folgende Person hat einen Urlaubsantrag gestellt: <br><br> \r\r";
        $email_body .= "Name: " . $name . "<br> \rvom: " . date('d.m.Y', strtotime($urlaub_beginn)) . "<br> \rbis einschl.: " . date('d.m.Y', strtotime($urlaub_ende)) . "<br><br><br> \r\r\rMit freundlichen Grüßen";
        $mailerObj->sendEmail($email_body, $betreff, $daten->kennung, $name, generated_files_dir . '/' . $signed_pdf, $signed_pdf);

        header("Location: ../download.php?page=urlaub&link=" . $signed_pdf);    # Send a raw HTTP header (No output before sending headers!)
    } # 
    else {
    }
    exit();
}



function save_in_db()
{
    global $urlaub_beginn, $urlaub_ende, $daten;
    $db = new Datenbank();
    $arbeitsbeginn = new Datetime('08:00:00');
    $arbeitsende =  date_add(new Datetime('08:00:00'), new DateInterval('PT' . $daten->tagesarbeitszeit_in_min . 'M'));
    $von = $arbeitsbeginn->format('H:i:s');
    $bis = $arbeitsende->format('H:i:s');
    $zeitsumme = strtotime($bis) - strtotime($von);
    $date1 = new DateTime($urlaub_beginn);
    $date2 = new DateTime($urlaub_ende);
    $dauer = $date1->diff($date2)->format('%r%a');
    $holidays = getHolidays();
    $holidays_2 = getHolidays(intval(date('Y') + 1));  // holidays des nächsten Jahres

    # über die eingegebene Dauer iterieren, und wenn das Datum weder in DB noch ein Feiertag/WE ist, speichere die Daten in der DB
    for ($i = 0; $i <= $dauer; $i++) {
        $intervall = new DateInterval('P' . $i . 'D');
        $datum = date_add(new DateTime($urlaub_beginn), $intervall)->format("Y-m-d");

        $datum_ = date('d.m.Y', strtotime($datum));
        if (array_key_exists($datum_, $holidays)  ||  array_key_exists($datum_, $holidays_2)  ||  vorhanden_in_DB($datum)) {
            continue;
        }

        $insert = $db->execute_query('INSERT INTO `stundenzettel`(`account`, `datum`, `aufgabe`, `von`, `bis`, `zeitsumme`) 
                                    VALUES (?,?,?,?,?,?)', $daten->kennung, $datum, 'Urlaub', $von, $bis, $zeitsumme);
    }

    echo "affectedRows (insert): " . $insert->affectedRows() . '<br>';
    if ($insert->affectedRows() === 1) {
        return true;
    }
    return false;
}



// prüfe ob, für das eingegebene Datum einen Urlaub vorliegt.
function vorhanden_in_DB($datum)
{
    global $daten;
    $db_obj = new Datenbank();
    $vorhanden_in_DB = false;

    $check = $db_obj->execute_query("SELECT aufgabe FROM `stundenzettel` where datum = ? and account = ?  and aufgabe = ? ;", $datum, $daten->kennung, 'Urlaub');
    if (!$db_obj->get_query()->errno) {
        if ($check->numRows() == 1) {
            $check = $check->fetchArray();
            if (array_key_exists('aufgabe', $check)) {
                $vorhanden_in_DB = true;
            }
        }
    } else {
        echo ("SQL-Query fehlgeschlagen....... " . $db_obj->get_query()->error . "<br>");
    }
    return $vorhanden_in_DB;
}



main_urlaub();
//*********************************/