<?PHP

//*================ Funktionen für Feiertage und Wochenenden:

function getHolidays($year = null)
{
    global $arbeitstage;

    if ($year === null) {
        $year = intval(date('Y'));
    }

    $easterDate  = easter_date($year);
    $easterDay   = date('j', $easterDate);
    $easterMonth = date('n', $easterDate);
    $easterYear   = date('Y', $easterDate);

    $holidays = array();
    // feste:
    $holidays[date('d.m.Y', mktime(0, 0, 0, 1,  1,  $year))] = 1; // Neujahr
    $holidays[date('d.m.Y', mktime(0, 0, 0, 5,  1,  $year))] = 1; // 1. Mai
    $holidays[date('d.m.Y', mktime(0, 0, 0, 10,  3,  $year))] = 1; // Tag der deutschen Einheit 
    $holidays[date('d.m.Y', mktime(0, 0, 0, 11,  1,  $year))] = 1; // Allerheiligen
    $holidays[date('d.m.Y', mktime(0, 0, 0, 12,  24,  $year))] = 1; // Heiligabend
    $holidays[date('d.m.Y', mktime(0, 0, 0, 12,  25,  $year))] = 1; // 1. Weihnachtstag
    $holidays[date('d.m.Y', mktime(0, 0, 0, 12,  26,  $year))] = 1; // 2. Weihnachtstag
    $holidays[date('d.m.Y', mktime(0, 0, 0, 12,  31,  $year))] = 1; // Silvester

    // Sondertage:
    $holidays[date('d.m.Y', mktime(0, 0, 0, 10,  1,  2021))] = 1; // wg. Corona etc., nur 2021

    // bewegliche Feiertage
    // Karfreitag
    $holidays[date('d.m.Y', strtotime('-2 days', mktime(0, 0, 0, $easterMonth, $easterDay,  $easterYear)))] = 1;
    // Ostersonntag
    $holidays[date('d.m.Y', mktime(0, 0, 0, $easterMonth, $easterDay,  $easterYear))] = 1;
    // Ostermontag
    $holidays[date('d.m.Y', strtotime('+1 days', mktime(0, 0, 0, $easterMonth, $easterDay,  $easterYear)))] = 1;


    // Christi Himmelfahrt (= ostern+ 39)
    $holidays[date('d.m.Y', strtotime('+39 days', mktime(0, 0, 0, $easterMonth, $easterDay,  $easterYear)))] = 1;

    // Pfingsten (= ostern + 49)
    $holidays[date('d.m.Y', strtotime('+49 days', mktime(0, 0, 0, $easterMonth, $easterDay,  $easterYear)))] = 1;
    // Pfingsten (= ostern + 50)
    $holidays[date('d.m.Y', strtotime('+50 days', mktime(0, 0, 0, $easterMonth, $easterDay,  $easterYear)))] = 1;

    // Frohnleichnam (= ostern + 60)
    $holidays[date('d.m.Y', strtotime('+60 days', mktime(0, 0, 0, $easterMonth, $easterDay,  $easterYear)))] = 1;



    // Wochenenden:
    $day = date('w', mktime(0, 0, 0, 1,  1,  $year));
    $sun = (7 - $day) % 7;
    $sat = ($sun === 0 ? 6 : $sun - 1);

    $startyear = mktime(0, 0, 0, 1,  1,  $year);
    for ($i = 0; $i < 52; $i++) {
        $holidays[date('d.m.Y', strtotime('+' . $sat + 7 * $i . ' days', $startyear))] = 2;
        $holidays[date('d.m.Y', strtotime('+' . $sun + 7 * $i . ' days', $startyear))] = 2;
    }
    //$dofyear = ((($year/4-floor($year/4)) > 0) ? 365 : 366 ); // valid up to 2100 ...
    $dofyear = (date('L') ? 366 : 365);     # !!
    $arbeitstage[$year] = $dofyear - count($holidays);
    return $holidays;
}
