<?php

namespace Classes;

@session_start();

require_once dirname(__FILE__, 2) . "/vendor/autoload.php";

if (!isset($_SESSION['loggedin'])) {    # if this session variable isn't set, this means the user isn't loggedin
}

use Classes\Datenbank;


class Daten
{
    protected $db;
    public $kennung;
    public $vorname;
    public $nachname;
    public $amtsbezeichnung;
    public $tagesarbeitszeit;
    public $tagesarbeitszeit_in_min;
    public $aktuellkrank;
    public $aktuellkrank_bis;
    public $urlaubsanspruch;
    public $resturlaub;
    public $urlaub_erhalten;
    public $urlaubssaldo;

    public function __construct()
    {
        $this->db  = new Datenbank();
        $this->kennung = $_SESSION['username'];
        $this->get_daten();
    }

    #======== Mitarbeiter- und Urlaubsdaten ermitteln
    private function get_daten()
    {
        $mitarbeiter = $this->db->execute_query("SELECT * FROM mitarbeiter WHERE account =? ;", $this->kennung);
        if ($mitarbeiter->numRows() == 1  &&  !$this->db->get_query()->errno) {
            $result = $mitarbeiter->fetchArray();
            $this->vorname = $result['vorname'];
            $this->nachname = $result['name'];
            $this->amtsbezeichnung = $result['vertragsart'];
            $this->urlaubsanspruch = $result['urlaubsanspruch'];
            $this->resturlaub = $result['resturlaub'];
            $this->tagesarbeitszeit = $result['wochenstunden'] / 5;
            $this->tagesarbeitszeit_in_min = ceil($this->tagesarbeitszeit * 60);
        } else {
            echo ("SQL-Query fehlgeschlagen. " . $this->db->get_query()->error . "<br>");
        }


        //=========== kranktabelle
        $output = $this->db->execute_query("SELECT `aktuellkrank`, `bis` FROM `krank` WHERE `account` = ?;", $this->kennung);
        if (!$this->db->get_query()->errno) {     # ??!!
            $output = $output->fetchArray();
            $this->aktuellkrank = $output['aktuellkrank'];
            $this->aktuellkrank_bis =  $output['bis'];
        } else {
            echo ("SQL-Query fehlgeschlagen. " . $this->db->get_query()->error . "<br>");
        }


        #========= Urlaubstage, die bereits genommen wurden
        $von = date('Y') . '-01-01';
        $bis = date('Y') . '-12-31';
        $output2 = $this->db->execute_query("SELECT count(`aufgabe`) as urlaub_erhalten FROM `stundenzettel` WHERE `account` = ? and `aufgabe` = ? 
                                 AND `datum` >= ? and `datum` <= ? ", $this->kennung, 'Urlaub', $von, $bis);
        if (!$this->db->get_query()->errno) {     # ??!!
            $output2 = $output2->fetchArray();
            $this->urlaub_erhalten = $output2['urlaub_erhalten'];
        } else {
            echo ("SQL-Query fehlgeschlagen. " . $this->db->get_query()->error . "<br>");
        }
        
        #========== aktueller urlaubssaldo
        $this->urlaubssaldo = $this->urlaubsanspruch + $this->resturlaub - $this->urlaub_erhalten;
    }

    //======================================
    # liefert 'bis' Attribut des letzten Eintrags am übergebenen Tag + Summe aller Einträge 
    function get_bis_summe($tag)
    {
        $db = new Datenbank();

        $data = $db->execute_query("SELECT MAX(bis), sum(zeitsumme) FROM stundenzettel WHERE account = ? AND datum = ? AND aufgabe != ? AND aufgabe != ?;", $this->kennung, $tag, 'Krankmeldung', 'Urlaub');
        if (!$db->get_query()->errno) {
            $data = $data->fetchArray();
            $result['bis'] = $data['MAX(bis)'];       # 'bis' der letzten Aufgabe an dem gegebenen Tag
            $result['summe'] = $data['sum(zeitsumme)']; # summe der gearbeiteten Zeit
        } else {
            echo ("SQL-Query fehlgeschlagen. " . $db->get_query()->error . "<br>");
        }
        return $result;
    }

    #==================================
    public function getVorgesetzte()
    {
        $dbObj = new Datenbank(); 

        $vorgesetzte = $dbObj->execute_query("SELECT `account`, `vorname`, `name` FROM `mitarbeiter` WHERE personaladmlevel = 4; ");
        if (!$dbObj->get_query()->errno) {
            $vorgesetzte = $vorgesetzte->fetchAll();
        } else {
            echo ("SQL-Query fehlgeschlagen. " . $dbObj->get_query()->error . "<br>");
        }
        return $vorgesetzte;
    }

    #===================================
    # check falls Mirarbeiter Personallevel 4 hat (ist vorgesetzte/r)
    public function istVorgesetzter($kennung)
    {
        $vorgesetzte = $this->getVorgesetzte();
        $result = false;

        for ($i = 0; $i < count($vorgesetzte); $i++) {
            if ($kennung  == $vorgesetzte[$i]['account']) {
                $result = true;
                break;
            }
        }
        return $result;
    }
}
//========================================================================
//========================================================================
