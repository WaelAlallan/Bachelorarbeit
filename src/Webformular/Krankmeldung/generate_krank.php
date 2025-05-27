<?php
session_start();

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
require_once 'backend_krank.php';
require_once '../holidays.php';
require_once "../Verwaltung/tabellendaten.php";


$daten = new Daten();         # für Daten und Ergebnisse der Abfragen aus der DB
$name  = $daten->vorname . " " . $daten->nachname;
$uni_einrichtung = 'IV-Versorgungseinheit 5 (IVV5)';
$radiobutton1 = (isset($_POST["radiogroup1"]) && $_POST["radiogroup1"] == 'radiobtn1') ? "Ja" : "Off";
$radiobutton2 = (isset($_POST["radiogroup1"]) && $_POST["radiogroup1"] == 'radiobtn2') ? "Ja" : "Off";
$checkbox2 =    (isset($_POST["checkbox2"]) && $_POST["checkbox2"]) ? "Ja" : "Off";   # if checked, then 'Ja'/'Off' (FDF-values) 
$checkbox3 =    (isset($_POST["checkbox3"]) && $_POST["checkbox3"]) ? "Ja" : "Off";
$verlassen_um = $_POST["verlassen_um"];
$AU_beginn_tag = $_POST["AU_beginn_tag"];
$AU_bis =       $_POST["AU_bis"];
$bemerkungen =  htmlspecialchars(stripslashes(trim($_POST["bemerkungen"])));
$amtsbez = "";


if ($daten->amtsbezeichnung == "TM" || $daten->amtsbezeichnung == "AZUBI") {
   $dezernat = "3.4";
} elseif ($daten->amtsbezeichnung == "WM" || $daten->amtsbezeichnung == "SHK" || $daten->amtsbezeichnung == "SHB") {
   $dezernat = "3.3";
}

switch ($daten->amtsbezeichnung) {
   case 'TM':
      $amtsbez = "Technische/r Mitarbeiter/in (TM)";
      break;
   case 'AZUBI':
      $amtsbez = "Auszubildende/r (AZUBI)";
      break;
   case 'WM':
      $amtsbez = "Wissenschaftliche/r Mitarbeiter/in (WM)";
      break;
   case 'SHK':
      $amtsbez = "Studentische Hilfskraft (SHK)";
      break;
   case 'SHB':
      $amtsbez = "Studentische Hilfskraft mit Bachelor (SHB)";
      break;
}

# array mit den Daten zum Ausfüllen des PDFs. Keys sind Namen der Felder im ausfüllbaren PDF
$angaben = array(
   "Text1" => $uni_einrichtung,
   "Text2" => $daten->nachname . ", " . $daten->vorname,
   "Kontrollkästchen1" => $radiobutton1,
   "Kontrollkästchen2" => $radiobutton2,
   "Kontrollkästchen3" => $checkbox2,
   "Kontrollkästchen4" => $checkbox3,
   "Text9" => $verlassen_um,
   "Text3" => date('d.m.Y', strtotime($AU_beginn_tag)),
   "Text5" => $AU_bis != '' ? date('d.m.Y', strtotime($AU_bis)) : '',
   "Dropdown-Liste2" => $dezernat,
   "Amtsbez" =>  $amtsbez,
);



###### main-function
function main_krank()
{
   global $daten, $angaben, $bemerkungen,  $name, $AU_bis, $AU_beginn_tag;
   $mailerObj = new Mailer();
   $betreff = 'Neue Krankmeldung - ' . date('d.m.Y');
   $bis_str = $AU_bis == "" ? 'Keine' :  date('d.m.Y', strtotime($AU_bis));
   $bemerkungen_str = $bemerkungen == "" ? 'Keine' : $bemerkungen;


   if ($daten->aktuellkrank === 0) {
      #falls die daten erfolgreich in der DB gespeichert.
      if (save_in_db()) {
         $pdf_generator = new PdfGenerator();
         $pdf_result = $pdf_generator->generate_krank($angaben, $AU_bis);     # generate the filled pdf
         $filled_pdf = $pdf_result['pdf_name'];
         $path_to_file = $pdf_result['zielpath'];                          # wo die PDF gespeichert ist
         echo 'error message: ' . $pdf_result['error_msg'] . "<br><br>";
         echo "path_to_file: " . $path_to_file . "<br><br>";
         var_dump($filled_pdf);
         echo "<br>";

         # falls pdf erfolgreich erzeugt wurde, trage die Pdf-datei in die 1. Tabelle ein (zum signieren) 
         if ($pdf_result['output_msg'] === true) {
            insertRow(pathinfo($path_to_file, PATHINFO_BASENAME), date('d.m.Y'), $name, false);
         }


         # Email an den Vorgesetzten schicken und Benutzer zur 'Bestätigungsseite' weiterleiten
         $email_body = "Sehr geehrte*r Nutzer*in, <br><br> \r\rEs liegt eine neue Krankmeldung zur Bearbeitung vor! Folgende Person hat sich für den " . date('d.m.Y', strtotime($AU_beginn_tag)) . " krankgemeldet: <br><br> \r\r";
         $email_body .=  "Name: " . $name .  "<br> \rVoraussichtlich bis: " . $bis_str . "<br> \rBemerkungen: " . $bemerkungen_str . "<br><br> \r\r\rMit freundlichen Grüßen";
         $mailerObj->sendEmail($email_body, $betreff, $daten->kennung, $name, $path_to_file, $filled_pdf);
         header("Location: ../download.php?page=krank&link=" . $filled_pdf);    # Send a raw HTTP header (No output before sending headers!)
      } else {
      }
   } #
   elseif ($daten->aktuellkrank === 1) {

      if (save_in_db()) {
         $email_body2 = "Sehr geehrte*r Nutzer*in, <br><br> \r\rEs liegt eine neue Krankmeldung zur Bearbeitung vor! Folgende Person hat sich erneut krankgemeldet: <br><br> \r\rName: " . $name . "<br> \rVoraussichtlich bis: " . $bis_str . "<br> \rBemerkungen: " . $bemerkungen_str . "<br><br> \r\r\rMit freundlichen Grüßen";
         $mailerObj->sendEmail($email_body2, $betreff, $daten->kennung, $name);
         header("Location: ../download.php?page=krank");     
      } else {
      }
   }
   exit();
}





function save_in_db()
{
   global $daten, $AU_beginn_tag,  $AU_bis, $verlassen_um, $bemerkungen;

   $db = new Datenbank();        # DB-Obj erstellen und mit der DB verbinden
   $holidays = getHolidays();
   $holidays_2 = getHolidays(intval(date('Y') + 1));  // holidays des nächsten Jahres
   $AU =  (isset($_POST["checkbox3"]) && $_POST["checkbox3"] == true) ? 1 : 0;
   $arbeitsbeginn = new Datetime('08:00:00');
   $arbeitsende =  date_add(new Datetime('08:00:00'), new DateInterval('PT' . $daten->tagesarbeitszeit_in_min . 'M'));
   $von = $arbeitsbeginn->format('H:i:s');
   $bis = $arbeitsende->format('H:i:s');
   $zeitsumme = strtotime($bis) - strtotime($von);

   // falls aktuellkrank, dann der neue `AU_beginn_tag` = der nächste Werktag direkt nach dem Tag, an dem man zuletzt krankgemeldet ist 
   // (durch die Implementation & Validation ist sichergestellt das es kein Feiertag ist)
   $AU_beginn = $daten->aktuellkrank === 0 ? $AU_beginn_tag :  date_add(new Datetime($daten->aktuellkrank_bis), new DateInterval('P1D'))->format("Y-m-d");
   $AU_beginn_ = date('d.m.Y', strtotime($AU_beginn));

   # Daten zur Krakmeldung während der Arbeitszeit
   $bis_summe = $daten->get_bis_summe($AU_beginn_tag);
   $von1 = $bis_summe['bis'] == null ? $verlassen_um : $bis_summe['bis'];  # $bis_summe['bis'] <=> 'bis' der letzten Aufgabe an dem gegebenen Tag
   $gearbeitet_summe = $bis_summe['summe'];                               # summe der gearbeiteten Zeit am Tag
   $bis1_ =  strtotime($von1)  + ($daten->tagesarbeitszeit_in_min * 60) - $gearbeitet_summe; # 'bis' des letzten Eintrag + noch zu erbringende Arbeitszeit
   $bis1 = date('H:i:s', $bis1_);
   $zeitsumme1 = (strtotime($bis1) - strtotime($von1)) < 0 ? (strtotime($bis1) - strtotime($von1)) + 86400 : (strtotime($bis1) - strtotime($von1)); # 

   //*============= wenn vsl. Dauer ($AU_bis) nicht eingegeben ist, dann eintägige Krankmeldung (aktuell nicht krank).
   if ($AU_bis == '') {

      $krankObj_1 = new Krankmeldung($AU_beginn_tag, $AU_beginn_tag);
      $insert = NULL;

      # falls an dem ausgewählten Tag einen Urlaub vorliegt, dann lösche diesen Eintrag
      if ($krankObj_1->check('Urlaub')) {
         $delete = $db->execute_query("DELETE FROM `stundenzettel` WHERE `account` = ? and `aufgabe` = ? and `datum` = ? ;", $daten->kennung, 'Urlaub', $AU_beginn_tag);
         if (!$db->get_query()->errno) {
            echo "affectedRows (Urlaubstag gelöscht): " . $delete->affectedRows() . '<br>';
         } else {
            echo ("SQL-Query fehlgeschlagen (Urlaubstag konnte nicht gelöscht werden): " . $db->get_query()->error . "<br>");
         }
      }

      if (!vorhanden_in_DB($AU_beginn)  &&  !array_key_exists($AU_beginn_, $holidays)) { # falls Datum nicht in DB und kein Feiertag/WE ist
         if ($verlassen_um != '') {          # falls die Arbeit während der Arbeitszeit verlassen wurde

            $insert = $db->execute_query('INSERT INTO `stundenzettel`(`account`, `datum`, `aufgabe`, `von`, `bis`, `zeitsumme`, `AU`, `bemerkungen`) 
                     VALUES (?,?,?,?,?,?,?,?)', $daten->kennung, $AU_beginn, 'Krankmeldung', $von1, $bis1, $zeitsumme1, $AU, $bemerkungen);
         } #
         else {
            $insert = $db->execute_query('INSERT INTO `stundenzettel`(`account`, `datum`, `aufgabe`, `von`, `bis`, `zeitsumme`, `AU`, `bemerkungen`) 
                     VALUES (?,?,?,?,?,?,?,?)', $daten->kennung, $AU_beginn, 'Krankmeldung', $von, $bis, $zeitsumme, $AU, $bemerkungen);
         }

         # falls ein Eintrag erfolgreich in die DB gespeichert, aktualisiere `aktuellkrank` und bis wann man krank ist.
         if ($insert !== NULL  &&  $insert->affectedRows() === 1) {
            echo "affectedRows (insert): " . $insert->affectedRows() . '<br>';  # affectedRows()  
            $update = $db->execute_query("UPDATE `krank` SET `aktuellkrank` = ?, `bis` = ? WHERE `account` = ?;", 1, $AU_beginn, $daten->kennung);
            if ($update->affectedRows() === 1)
               return true;
         }
      }
      return false;     # false, wenn SQL-Abfragen fehlgeschlagen.
   }

   //*============== falls vsl. Dauer eingegeben wurde (entweder aktuell krank/ nicht krank).
   else {

      $krankObj_2 = new Krankmeldung($AU_beginn_tag, $AU_bis);
      $date1 = new DateTime($AU_beginn);
      $date2 = new DateTime($AU_bis);
      $dauer = $date1->diff($date2)->format('%r%a');
      $insert1 = NULL;
      $just_once = true;


      # falls an den ausgewählten Tagen einen Urlaub vorliegt, dann lösche die Urlaubseinträge
      if ($krankObj_2->check('Urlaub')) {
         $delete = $db->execute_query("DELETE FROM `stundenzettel` WHERE `account` = ? and `aufgabe` = ? and `datum` >= ? and `datum` <= ? ;", $daten->kennung, 'Urlaub', $AU_beginn_tag, $AU_bis);
         if (!$db->get_query()->errno) {
            echo "affectedRows (Urlaubstage gelöscht): " . $delete->affectedRows() . '<br>';
         } else {
            echo ("SQL-Query fehlgeschlagen (Urlaubstage konnten nicht gelöscht werden): " . $db->get_query()->error . "<br>");
         }
      }

      # über die eingegebene Dauer iterieren, und wenn das Datum weder in DB noch ein Feiertag/WE ist, speichere die Daten in der DB
      for ($i = 0; $i <= $dauer; $i++) {
         $db2 = new Datenbank();
         $intervall = new DateInterval('P' . $i . 'D');
         $datum = date_add(new DateTime($AU_beginn), $intervall)->format("Y-m-d");
         $datum_ = date('d.m.Y', strtotime($datum));
         if (vorhanden_in_DB($datum)  ||  array_key_exists($datum_, $holidays)  ||  array_key_exists($datum_, $holidays_2)) {
            continue;
         }

         # erster Eintrag falls die Arbeit während der Arbeitszeit verlassen wurde
         if ($verlassen_um != ''  &&  $just_once) {
            $insert1 = $db->execute_query('INSERT INTO `stundenzettel`(`account`, `datum`, `aufgabe`, `von`, `bis`, `zeitsumme`, `AU`, `bemerkungen`) 
                            VALUES (?,?,?,?,?,?,?,?)', $daten->kennung, $datum, 'Krankmeldung', $von1, $bis1, $zeitsumme1, $AU, $bemerkungen);      //? $von2...$bis2...
            $just_once = false;
         } #
         else {
            $insert1 = $db2->execute_query('INSERT INTO `stundenzettel`(`account`, `datum`, `aufgabe`, `von`, `bis`, `zeitsumme`, `AU`, `bemerkungen`) 
                               VALUES (?,?,?,?,?,?,?,?)', $daten->kennung, $datum, 'Krankmeldung', $von, $bis, $zeitsumme, $AU, $bemerkungen);
         }
      }

      if ($insert1 !== NULL  &&  $insert1->affectedRows() === 1) {
         echo "affectedRows (insert1): " . $insert1->affectedRows() . '<br>';
         if (update($AU_beginn_, $AU_bis, $holidays))
            return true;
      }
      return false;
   }
}




//* `aktuellkrank` setzen und falls 'bis_datum' in holidays ist, nimm den Werktag davor.
function update($von_datum, $bis_datum, $holidays)
{
   global $daten;
   $db_obj = new Datenbank();
   $bis_datum_  =  date('d.m.Y', strtotime($bis_datum));

   if (!array_key_exists($bis_datum_, $holidays)) {
      $update = $db_obj->execute_query("UPDATE `krank` SET `aktuellkrank` = ?, `bis` = ? WHERE `account` = ?;", 1, $bis_datum, $daten->kennung);
   }

   //* falls das vsl. bis_datum in holidays ist
   else {
      $vortag = date('d.m.Y', strtotime('-1 days', strtotime($bis_datum)));
      $stop = false;
      while ($vortag >= $von_datum  &&  !$stop) {
         if (!array_key_exists($vortag, $holidays)) {
            $datum = date('Y-m-d', strtotime($vortag));
            $update = $db_obj->execute_query("UPDATE `krank` SET `aktuellkrank` = ?, `bis` = ? WHERE `account` = ?;", 1, $datum, $daten->kennung);
            $stop = true;
         }
         $vortag = date('d.m.Y', strtotime('-1 days', strtotime($vortag)));
      }
   }
   if ($update->affectedRows() === 1) {
      return true;
   }
}



//* prüfe ob, für das eingegebene Datum eine KM vorliegt.
function vorhanden_in_DB($date)
{
   global $daten;
   $vorhanden_in_DB = false;
   $db_obj = new Datenbank();

   $check = $db_obj->execute_query("SELECT aufgabe FROM `stundenzettel` where datum = ? and account = ?  and aufgabe = ? ;", $date, $daten->kennung, 'Krankmeldung');
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







//** **/
echo '------------------------------------------------------';
echo '<br> AU_beginn_tag: ' . $AU_beginn_tag . '<br>';
echo 'AU_bis: ' . $_POST["AU_bis"] . '<br>';
echo 'AU_bis isset: ' . isset($AU_bis) . '<br>';
echo 'aktuellkrank: ' . var_dump($daten->aktuellkrank);
echo '<br>';
echo 'tagesarbeitszeit: ' . $daten->tagesarbeitszeit . '<br>';
echo 'taz in min: ' . $daten->tagesarbeitszeit_in_min . '<br>';
echo 'verlassen_um: ' . $verlassen_um . '<br>';
echo ($verlassen_um == '') . '<br>';
echo '--------------------------------------------------<br>';




main_krank();
//********************************/
