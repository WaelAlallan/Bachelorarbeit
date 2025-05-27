<?php
session_start();
 
$php_errormsg = "";
if ($_SERVER["REQUEST_METHOD"] != "POST" || $_FILES["digital_ID"]["tmp_name"] == "" || $_POST["ID_password"] == "" || $_POST["files_to_sign"] == "") {
    exit();
} 
require_once dirname(__FILE__, 3) . "/vendor/autoload.php";
require_once "tabellendaten.php";


$digital_ID = $_FILES["digital_ID"]["tmp_name"];
$ID_password = $_POST["ID_password"];
$files_to_sign = $_POST["files_to_sign"];


# Die zu dignierenden Dateien in einem string speichern (als Argument zum signier-$command)
$files_to_sign = explode(",", $files_to_sign);
$sign_k = $sign_g = $sign_u = false;
$tosign_k = $tosign_g = $tosign_u = "";


# unterscheiden zwischen den zu signierenden Formulare um die Signatur an dem richtigen platz zu platzieren
for ($i = 0; $i < count($files_to_sign); $i++) {
    if (str_contains($files_to_sign[$i], 'Krankmeldung')) {
        $tosign_k .= "  " . '"' . $files_to_sign[$i] . '"'; # zu signierende Krankmeldungen als String
        $sign_k = true;
        $tosign_k_arr = array();                        # zu signierende Krankmeldungen als Array
        array_push($tosign_k_arr, $files_to_sign[$i]);
    }
    if (str_contains($files_to_sign[$i], 'Gesundmeldung')) {
        $tosign_g .= "  " . '"' . $files_to_sign[$i] . '"';
        $sign_g = true;
        $tosign_g_arr = array();
        array_push($tosign_g_arr, $files_to_sign[$i]);
    }
    if (str_contains($files_to_sign[$i], 'Urlaubsantrag')) {
        $tosign_u .= "  " . '"' . $files_to_sign[$i] . '"';
        $sign_u = true;
        $tosign_u_arr = array();
        array_push($tosign_u_arr, $files_to_sign[$i]);
    }
} 


$result_k = $result_g = $result_u = true;
$cd = "cd " . generated_files_dir; 

# signiere Krankmeldungen falls es welche ausgewählt wurde
if ($sign_k) {
    $command_k = $cd . "  &&  java -jar " . JSignPdfjar . " --append -kst PKCS12 -ksf " . $digital_ID . " -ksp " . $ID_password . " --bg-path " . background_img .
        " --bg-scale -1 -llx 375 -lly 333 -urx 530 -ury 278  --visible-signature" . $tosign_k . "  2>&1";

    $output1 =  exec($command_k, $out1, $result_code1);
    echo '<br>';
    var_dump($result_code1); # 0 means no errors
    echo '<br>';
    var_dump($output1);
    echo '<br>'; 

    if ($result_code1 === 0) { # falls erfolgreich signiert
        $result_k =  set_as_signed($tosign_k_arr);
        if ($result_k)
            echo "<br>Krankmeldungen wurden erfolgreich signiert und als solche in der Tabelle markiert";
        else
            echo "<br>es konnte nicht als signiert markiert werden";
    } else {
        echo "<br>Signieren von Krankmeldungen ist fehlgeschlagen: " . $output1;
    }
}


# signiere Gesundmeldungen falls es welche gibt
if ($sign_g) {
    $command_g = $cd . "  &&  java -jar " . JSignPdfjar . " --append -kst PKCS12 -ksf " . $digital_ID . " -ksp " . $ID_password . " --bg-path " . background_img .
        " --bg-scale -1 -llx 365 -lly 440 -urx 526 -ury 383  --visible-signature" . $tosign_g . "  2>&1";

    $output2 =  exec($command_g, $out2, $result_code2);
    echo '<br>';
    var_dump($result_code2); # 0 means no errors
    echo '<br>';
    var_dump($output2);
    echo '<br>';
    
    if ($result_code2 === 0) { # falls erfolgreich signiert
        $result_g = set_as_signed($tosign_g_arr);
        if ($result_g)
            echo "<br>Gesundmeldungen wurden erfolgreich signiert und als solche in der Tabelle markiert";
        else
            echo "<br>es konnte nicht als signiert markiert werden";
    } else {
        echo "<br>Signieren von Gesundmeldungen ist fehlgeschlagen: " . $output2;
    }
}


# signiere Urlaubsanträge falls es welche gibt
if ($sign_u) {
    $command_u = $cd . "  &&  java -jar " . JSignPdfjar . " --append -kst PKCS12 -ksf " . $digital_ID . " -ksp " . $ID_password . " --bg-path " . background_img .
        " --bg-scale -1 -llx 355 -lly 162 -urx 511 -ury 110  --visible-signature" . $tosign_u . "  2>&1";

    $output3 =  exec($command_u, $out3, $result_code3);
    echo '<br>'; 
    var_dump($result_code3); # 0 means no errors
    echo '<br>';
    var_dump($output3);
    echo '<br>'; 

    if ($result_code3 === 0) { # falls erfolgreich signiert
        $result_u = set_as_signed($tosign_u_arr);
        if ($result_u)
            echo "<br>Urlaubsanträge wurden erfolgreich signiert und als solche in der Tabelle markiert";
        else
            echo "<br>es konnte nicht als signiert markiert werden";
    } else {
        echo "<br>Signieren von Urlaubsanträge ist fehlgeschlagen: " . $output3;
    }
}



# if file is successfully signed, set as signed in Table 1
function set_as_signed(array $tosign)
{
    $tabellen_daten = json_decode(file_get_contents('../Verwaltung/tabelle.json'), true) == null ? array() : json_decode(file_get_contents('../Verwaltung/tabelle.json'), true);

    if ($tabellen_daten != NULL) {
        $counter = 0;
        for ($i = 0; $i < count($tosign); $i++) {
            for ($j = 0; $j < count($tabellen_daten); $j++) {
                if ($tosign[$i] == $tabellen_daten[$j][1]) {
                    $tabellen_daten[$j][4] = true;
                    $counter++;
                }
            }
        } 
        file_put_contents("../Verwaltung/tabelle.json", json_encode($tabellen_daten));
    }
    if ($counter == count($tosign)) {
        return true;     # Status aller Dateien wurde erfolgreich auf 'signiert' geändert
    }
    return false;
}


if ($result_k  &&  $result_g  &&  $result_u) {
    $_SESSION["return_msg"] = "Die gewünschte Dokumente sind erfolgreich signiert und als solche in der untenstehenden Tabelle markiert worden!";
    header("Location: verwaltung.php");    
    exit();
} else {
    $_SESSION['return_msg'] = "Es ist ein Fehler aufgetreten! Entweder das Signieren eines Dokuments ist fehlgeschlagen oder das Dokument konnte nicht als signiert in der untenstehenden Tabelle markiert werden: ";
    header("Location: verwaltung.php");    # $_SERVER['HTTP_REFERER']  
    exit();
}


 

 