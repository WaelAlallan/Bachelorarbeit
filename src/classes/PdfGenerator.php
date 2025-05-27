<?php

namespace Classes;

use mikehaertl\pdftk\Pdf;

define("UPPER_DIR", dirname(__FILE__, 3));   # 'src Dir.'

# damit nicht auf diese Klassen durch browser zugegriffen wird
if (!defined("ACCESSCHECK")) {
    die("Zugriff nicht möglich");
}

class PdfGenerator
{

    # generate PDF for Krankmeldung
    public function generate_krank($angaben, $AU_bis)
    {
        $pdf_name = "Krankmeldung_" .  date("Ymd_His") . ".pdf";
        $zielpath = UPPER_DIR . "/generated_files/" . $pdf_name;
        $pdf_formular  = $AU_bis != '' ? new Pdf(UPPER_DIR . "/pdf-Templates/krankmeldung.pdf") : new Pdf(UPPER_DIR . "/pdf-Templates/krankmeldung_v2.pdf");

        # fillForm($data, $encoding = 'UTF-8', $dropXfa = true, $format = 'xfdf') {} Fill a PDF form --- @return Pdf 
        $output = $pdf_formular->fillForm($angaben)
            ->flatten()
            ->saveAs($zielpath);

        # Always check for errors
        $error = 'no errors';
        if ($output === false) {
            $error = $pdf_formular->getError();
        }
        $result = array('pdf_name' => $pdf_name, 'zielpath' =>  $zielpath, 'error_msg' => $error, 'output_msg' => $output);

        return $result;
    }


    # generate PDF for Gesundmeldung
    public function generate_gesund($angaben)
    {
        $pdf_name = "Gesundmeldung_" .  date("Ymd_His") . ".pdf";
        $zielpath = UPPER_DIR . "/generated_files/" . $pdf_name;
        $pdf_formular  = new Pdf(UPPER_DIR . "/pdf-Templates/gesundmeldung.pdf");

        $output = $pdf_formular->fillForm($angaben)
            ->flatten()
            ->saveAs($zielpath);

        # Always check for errors
        $error = 'no errors';
        if ($output === false) {
            $error = $pdf_formular->getError();
        }
        $result = array('pdf_name' => $pdf_name, 'zielpath' =>  $zielpath, 'error_msg' => $error, 'output_msg' => $output);

        return $result;
    }

    # generate PDF for Urlaubsantrag
    public function generate_urlaub($angaben)
    {
        $pdf_name = "Urlaubsantrag_" .  date("Ymd_His") . ".pdf";
        $zielpath = UPPER_DIR . "/generated_files/" . $pdf_name;
        $pdf_formular  = new Pdf(UPPER_DIR . "/pdf-Templates/urlaubsantrag.pdf");

        $output = $pdf_formular->fillForm($angaben)
            ->flatten()
            ->saveAs($zielpath);

        # Always check for errors
        $error = 'no errors';
        if ($output === false) {
            $error = $pdf_formular->getError();
        }
        $result = array('pdf_name' => $pdf_name, 'zielpath' =>  $zielpath, 'error_msg' => $error, 'output_msg' => $output);

        return $result;
    }
}
