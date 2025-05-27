<?php

use Classes\Datenbank;
use Classes\Daten;


require_once dirname(__FILE__, 3) . "/vendor/autoload.php";


# mit einem xmlhttp-Request gesendete Daten zur Krankmeldung
$start_date =  isset($_REQUEST['date1']) ?    $_REQUEST['date1'] : null;
$end_date =    isset($_REQUEST['date2']) ?    $_REQUEST['date2'] : null;


class Krankmeldung
{
    protected $datum1;
    protected $datum2;
    protected $start;
    protected $end;
    protected $dauer;     # get difference of 2 dates in days.
    protected $vorhanden = false;


    public function __construct($datum1, $datum2)
    {
        $this->datum1 = $datum1;
        $this->datum2 = $datum2;
        $this->start   = new DateTime($datum1);
        $this->end = new DateTime($datum2);
        $this->dauer =   $this->start->diff($this->end)->format('%r%a');
    }

    # überprüfe ob die übergebene Aufgabe in der DB vorliegt 
    public function check($aufgabe)
    {
        $daten = new Daten();
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
}




# falls nur Beginn-Datum eingegeben
if ($start_date != null && $end_date == null) {
    $obj = new Krankmeldung($start_date, $start_date);
    $vorhanden = $obj->check('Krankmeldung');
    $urlaub_vorhanden = $obj->check('Urlaub');
} 
# falls beide Daten eingegeben
elseif ($start_date != null  &&  $end_date != null) {
    $obj2 = new Krankmeldung($start_date, $end_date);
    $vorhanden = $obj2->check('Krankmeldung');
    $urlaub_vorhanden = $obj2->check('Urlaub');
} #
else {
    $vorhanden = false;
    $urlaub_vorhanden = false;
}


$result = array($vorhanden,  $urlaub_vorhanden);
$myJSON = json_encode($result);
echo $myJSON;      # ausgeben als Antwort des xmlhttp-Requests //*! NO output should come before!!!
