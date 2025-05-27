<?php


require_once dirname(__FILE__, 3) . "/vendor/autoload.php";


# liefert pdf Dateien aus generated files Verzeichnis
function get_pdfs()
{
    $pdf_files = array();
    $dir_path =  generated_files_dir;     # path for generated files folder

    $dh = opendir($dir_path);
    $i = 0;
    while (($file = readdir($dh)) !== false) {
        if (pathinfo($file, PATHINFO_EXTENSION) == 'pdf') {
            $pdf_files[$i] = $file;
            $i++;
        }
    }
    closedir($dh);
    return $pdf_files;
}


function get_tabelle1()
{
    # get die gespeicherten Daten zur 1. Tabelle aus dem .json file
    $tabellendaten1 = json_decode(file_get_contents('../Verwaltung/tabelle.json'), true) == null ? array() : json_decode(file_get_contents('../Verwaltung/tabelle.json'), true);
    $pdfs_in_dir = get_pdfs();

    #check if the pdf still in directory or has not been deleted/moved 
    for ($i = 0; $i < count($tabellendaten1); $i++) {

        if (!in_array($tabellendaten1[$i][1], $pdfs_in_dir)) { 
            array_splice($tabellendaten1, $i, 1);
        }
    }
    file_put_contents("../Verwaltung/tabelle.json", json_encode($tabellendaten1));
    return $tabellendaten1;
}


# liefert die Daten für die 2. Tabelle
function get_tabelle2()
{
    $tabellendaten1 = json_decode(file_get_contents('../Verwaltung/tabelle.json'), true) == null ? array() : json_decode(file_get_contents('../Verwaltung/tabelle.json'), true);
    $tabellendaten2 = array();
    $pdfs_in_dir = get_pdfs();  # liefert pdfs-dateien in generated files  
    $index = 0;
 
    for ($i = 0; $i < count($pdfs_in_dir); $i++) {
        if (str_contains($pdfs_in_dir[$i], "_signed")) {
            $tabellendaten2[$index] = array();
            $tabellendaten2[$index][0] = $pdfs_in_dir[$i];
            $tabellendaten2[$index][1] = date('d.m.Y', filectime(generated_files_dir . '/' . $pdfs_in_dir[$i]));

            # suche nach zugehöriger (unsignierter) Datei in Tabelle1, um Mitarbeiter zu ermitteln, der das Dokument geschickt hat (3. Eintrag in der 2. Tabelle)
            for ($j = 0; $j < count($tabellendaten1); $j++) {
                $str = substr($tabellendaten1[$j][1], 0, -4);   # entferne (.pdf) vom Dateiennamen um richtig zu vergleichen
                if (str_contains($pdfs_in_dir[$i], $str)) {
                    $tabellendaten2[$index][2] = $tabellendaten1[$j][3];
                }
            }
            $index++;
        }
    } 
    return $tabellendaten2;
}
 

# fügt einen Eintrag in die 1. Tabelle hinzu
function insertRow($datei, $datum, $von, $zweifach_signiert)
{
    $data = json_decode(file_get_contents('../Verwaltung/tabelle.json'), true) == null ? array() : json_decode(file_get_contents('../Verwaltung/tabelle.json'), true);
    $row = array();
    $row[0] = "";
    $row[1] = $datei;
    $row[2] = $datum;
    $row[3] = $von;
    $row[4] = $zweifach_signiert;

    $data[] = $row;
    $result = file_put_contents("../Verwaltung/tabelle.json", json_encode($data));
    return $result;
}
