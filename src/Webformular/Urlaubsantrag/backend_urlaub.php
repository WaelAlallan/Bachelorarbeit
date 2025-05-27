<?php

use Classes\Datenbank;
use Classes\Daten;

require_once dirname(__FILE__, 3) . "/vendor/autoload.php";
require_once '../holidays.php'; 


//* an dieses Skript wird ein Request geschickt u.a. zur Berechnung der Urlaubsdauer 

$daten = new Daten();
# mit einem xmlhttp-Request gesendete Urlaubsdaten
$date1 =   isset($_REQUEST['date1']) ?  $_REQUEST['date1'] : null;
$date2 =   isset($_REQUEST['date2']) ?  $_REQUEST['date2'] : null;


class Urlaub
{
    protected $datum1;      # Beginndatum
    protected $datum2;      # Enddatum
    protected $start;
    protected $end;
    protected $dauer;     # dauer zwischen den beiden eingegebenen Daten
    protected $vorhanden = false;
    protected $urlaubssaldo_neu = 0;
    protected $urlaubsdauer = 0;


    public function __construct($datum1, $datum2)
    {
        $this->datum1 = $datum1;
        $this->datum2 = $datum2;
        $this->start   = new DateTime($datum1);
        $this->end = new DateTime($datum2);
        $this->dauer =   $this->start->diff($this->end)->format('%r%a');  # get difference of 2 dates in days.
    }


    # berechne Urlaubsdauer basierend auf die eingegebenen Daten
    public function calc_dauer()
    {
        $daten = new Daten();
        $holidays = getHolidays();

        for ($i = 0; $i <= $this->dauer; $i++) {

            $intervall = new DateInterval('P' . $i . 'D');
            $datum = date_add(new DateTime($this->datum1), $intervall)->format("Y-m-d");    
            $datum_ = date('d.m.Y', strtotime($datum));
            if (array_key_exists($datum_, $holidays)) {
                continue;
            } else {
                $this->urlaubsdauer += 1;
            }
        }
        $this->urlaubssaldo_neu = $daten->urlaubssaldo - $this->urlaubsdauer;
    }



    # überprüfe ob die übergebene Aufgabe in der DB vorliegt 
    public function check($aufgabe)
    {
        global $daten;
        $db = new Datenbank();
        $this->vorhanden = false;

        for ($i = 0; $i <= $this->dauer; $i++) {
            $intervall = new DateInterval('P' . $i . 'D');
            $datum = date_add(new DateTime($this->datum1), $intervall)->format("Y-m-d");  

            $check = $db->execute_query("SELECT aufgabe FROM `stundenzettel` where datum = ? and account = ?  and aufgabe = ? ;", $datum, $daten->kennung, $aufgabe);
            if (!$db->get_query()->errno) {

                if ($check->numRows() == 1) {
                    $check = $check->fetchArray();
                    if (array_key_exists('aufgabe', $check)) {
                        $this->vorhanden = true;
                        break;
                    }
                }
            } else {
                echo ("SQL-Query fehlgeschlagen..." . $db->get_query()->error . "<br>");
            }
        }
        return $this->vorhanden;
    }


    public function get_urlaubssaldo_neu()
    {
        return $this->urlaubssaldo_neu;
    }

    public function get_urlaubsdauer()
    {
        return $this->urlaubsdauer;
    }
}



if ($date1 ===  null || $date2 === null) {
    $dauer =     0;
    $saldo_neu =  $daten->urlaubssaldo;
    $check_KM = false;
    $check_urlaub = false;
} else {
    $obj = new Urlaub($date1, $date2);
    $obj->calc_dauer();
    $dauer =      $obj->get_urlaubsdauer();
    $saldo_neu =  $obj->get_urlaubssaldo_neu();
    $check_KM = $obj->check('Krankmeldung');
    $check_urlaub = $obj->check('Urlaub');
}

$result = array($dauer, $saldo_neu, $check_KM, $check_urlaub);
$myJSON = json_encode($result);
echo $myJSON;     # ausgeben als Antwort des xmlhttp-Requests //*!  NO output should come before!!!
