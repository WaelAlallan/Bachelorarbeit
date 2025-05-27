<?php
session_start();

use Classes\Daten;

require_once dirname(__FILE__, 3) . "/vendor/autoload.php";

if (!isset($_SESSION['loggedin'])) {    # if this session variable isn't set, this means the user isn't loggedin
   header('location: ../Login/login.php');
}
require_once "../Verwaltung/tabellendaten.php";
require_once '../holidays.php';


if (isset($_GET['logout'])) {          # destroy the session, and unset the session variables if loggedout
   unset($_SESSION['loggedin']);
   unset($_SESSION['username']);
   session_destroy();
   header("location: ../Login/login.php");
}

$daten = new Daten();
$holidays = getHolidays(); # feiertage in ein Array für JS speichern
$holidays_JS = array();

$i = 0;
foreach ($holidays as $key => $value) {
   $holidays_JS[$i] = $key;
   $i++;
}
?>

<!DOCTYPE html>
<html class="js no-touchevents objectfit object-fit picture no-sso mjylim idc0_334" lang="de">

<head>
   <meta http-equiv="content-type" content="text/html; charset=UTF-8">
   <meta charset="utf-8">
   <title>IVV5 - Urluabsantrag</title>
   <meta http-equiv="x-ua-compatible" content="IE=edge">
   <meta name="author" content="Westfälische Wilhelms-Universität Münster">
   <meta name="publisher" content="Westfälische Wilhelms-Universität Münster">
   <meta name="copyright" content="© 2021 IVV5">
   <meta name="description" content="IVV 5">

   <link href="https://www.uni-muenster.de/IVV5/en/index.shtml" rel="alternate" hreflang="en">
   <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
   <link href="../layout/main.css" rel="stylesheet" type="text/css">
   <link href="../layout/primary.css" rel="stylesheet" type="text/css">
   <link href="../layout/secondary.css" rel="stylesheet" type="text/css">
   <link href="../layout/webformulare.css" rel="stylesheet" type="text/css">
   <link href="../layout/print.css" rel="stylesheet" type="text/css" media="print">
   <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

   <link href="https://www.uni-muenster.de/IVV5/impressum.html" rel="copyright">
   <link href="https://www.uni-muenster.de/uv/wwuaz/unilist/" rel="index">
   <link href="https://www.uni-muenster.de/wwu/suche/" rel="search">
   <link href="https://www.uni-muenster.de/imperia/md/content/allgemein/farbunabhaengig/favicon.ico" rel="shortcut icon">

   <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
   <script src="../layout/modernizr.js"></script> <!--  -->
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
   <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

</head>

<body id="mnav" class="layout2017">
   <div class="cookies" id="cookies" style="display: block;">
      <div class="inner">
         <p>Diese <span lang="en">Website</span> verwendet <span lang="en">Cookies</span>. Wenn Sie die <span lang="en">Website</span> weiter nutzen, gehen wir davon aus, dass Sie hiermit einverstanden
            sind.</p><button>ok</button><a class="int" href="https://www.uni-muenster.de/de/datenschutzerklaerung.html">Datenschutzhinweis</a>
      </div>
   </div>

   <div class="wrapper" id="top">

      <header>
         <div id="logos" role="banner"><a href="https://www.uni-muenster.de/de/" class="wwulogo svg"><img src="../layout/wwu.svg" alt="WWU Münster" id="logo" class="university-logo svg" width="332" height="96"></a><a href="https://www.uni-muenster.de/IVV5/"><img src="../layout/ivv5_logo_zuschnitt_rgb.png" alt="IVV5 Home" title="IVV5 Home" id="sublogo" class="secondary-logo"></a></div>
         <div id="skipnav" class="noprint">
            <ul>
               <li><a href="#inhalt">zum Inhalt</a></li>
               <li><a href="#hauptnavigation">zur Hauptnavigation</a></li>
               <li><a href="#subnavigation">zur Subnavigation</a></li>
            </ul>
         </div>
         <div class="nav-mobile-menu">
            <nav class="nav-mobile"><a href="#mnav" class="nav-mobile-toggle"></a><a href="#none" class="nav-mobile-toggle active"></a></nav>
            <div class="nav-search-mobile"><a class="nav-search-mobile-toggle cse" href="#sear"></a><a class="nav-search-mobile-toggle active" href="#none"></a></div>
         </div>
      </header>
      <div class="nav-container row">
         <nav class="nav-main six columns">
            <div class="nav-search cse" role="search">
               <form accept-charset="UTF-8" method="get" action="//www.uni-muenster.de/suche/de.cgi"><label for="query">Suche:
                  </label><input id="submitButton" class="nav-search-button" type="submit" value="Los"><input id="query" type="search" name="q" class="cse" placeholder="Stichwort"></form>
            </div>
            <ul id="hauptnavigation" class="nav-first-level">
               <li class="nav-item-main"><a href="https://www.uni-muenster.de/IVV5/service/index.shtml">Service</a>
                  <div style="display: none" data-menu="/IVV5/service/index.shtml" class="nav-main-rollout">
                     <nav class="nav-2015 nav-partial">
                        <ul class="nav-second-level nav-column">
                           <li class="nav-item"><a href="https://www.uni-muenster.de/IVV5/service/firststeps.shtml">Erste Schritte</a></li>
                           <li class="nav-item"><a href="https://www.uni-muenster.de/IVV5/service/selfservice.shtml">SelfService</a> </li>
                           <li class="nav-item"><a href="https://www.uni-muenster.de/IVV5/Organisation/oeffnungszeiten.shtml">Büros + Öffnungszeiten</a></li>
                        </ul>
                     </nav>
                  </div>
               </li>
               <li class="nav-item-main"><a href="https://www.uni-muenster.de/IVV5WS/DocWiki/doku.php?id=public:ivv5news">Neuigkeiten</a> </li>
               <li class="nav-item-main"><a href="https://www.uni-muenster.de/IVV5/service/fiirststeps.shtml">Erste Schritte</a>
                  <div style="display: none" data-menu="/IVV5/service/fiirststeps.shtml" class="nav-main-rollout"></div>
               </li>
               <li class="nav-item-main"><span class="nav-level-toggle"></span><a href="https://www.uni-muenster.de/IVV5/Organisation/organisation.shtml">Die IVV5</a>
                  <div style="display: none" data-menu="/IVV5/Organisation/organisation.shtml" class="nav-main-rollout">
                     <nav class="nav-2015 nav-partial">
                        <ul class="nav-second-level nav-column">
                           <li class="nav-item"><a href="https://www.uni-muenster.de/IVV5/staffonly/index.shtml">Staff Only</a></li>
                           <li class="nav-item"><a href="https://www.uni-muenster.de/IVV5/Organisation/werwaswo.shtml">Wer Was Wo</a></li>
                           <li class="nav-item"><a href="https://www.uni-muenster.de/IVV5/Organisation/oeffnungszeiten.shtml">Öffnungszeiten</a> </li>
                        </ul>
                     </nav>
                  </div>
               </li>
               <li class="nav-item-main"><a href="../Verwaltung/verwaltung.php">Verwaltung</a> </li>
            </ul>
         </nav>
         <div class="wrapper-complement-nav six columns">
            <nav class="nav-audience-container"></nav>
            <nav class="nav-language-container">
               <!-- english version... -->
               <?php if (!isset($_GET['logout'])) : ?>
                  <a href="urlaubsantrag.php?logout='1'" style="color: black; font-size: 17px;">Abmelden</a>
               <?php endif ?>
            </nav>
         </div>
      </div>
      <div class="content row">

         <section id="inhalt" class="main four columns" role="main">
            <!-- WWU_Flex.perl -->
            <article class="module extended">
               <div class="module-content">

                  <form onsubmit="return validate2();" id="urlaubsformular" method="post" action="generate_urlaub.php" enctype="multipart/form-data">
                     <h2 class="ueberschrift">Urlaubsantrag</h2>
                     <p>Hier können Sie einen Urlaubsantrag stellen. Dazu füllen Sie bitte dieses Webformular aus und klicken Sie abschließend auf Absenden!</p>
                     <p>Ihre Angaben: </p>
                     <div id="urlaubsform">
                        <p>
                           <label for="name">Name, Vorname:</label>
                           <input type="text" name="name" class="namenfeld_urlaub" id="namenfeld_urlaub" value="<?php echo $daten->nachname . ', ' . $daten->vorname; ?>" disabled>
                        </p>
                        <label>Ich beantrage Erholungsurlaub </label><br><br>

                        <span class="urlaub_span1">
                           <label for="urlaub_beginn">vom / am </label>
                           <input type="date" name="urlaub_beginn" id="urlaub_beginn" onclick="change()" onchange="calc_urlaub();" readonly required>

                        </span>
                        <span class="urlaub_span1">
                           <label for="urlaub_ende" id="urlaub_ende_label">bis einschl.</label>
                           <input type="date" name="urlaub_ende" id="urlaub_ende" onclick="change()" onchange="calc_urlaub();" readonly required>
                        </span>
                        <span class="urlaub_span2">
                           <label for="urlaub_dauer">insgesamt: </label>
                           <input type="text" name="urlaub_dauer" id="urlaub_dauer" disabled>
                           <label for="">Arbeitstage. </label>
                        </span>
                        <span class="urlaub_span2">
                           <label for="vertreter">Vertreter/in: </label>
                           <input type="text" name="vertreter" id="vertreter" onclick="change()" required>
                        </span>
                        <label id="alert2" class="alert">Bitte geben Sie eine/n Vertreter/in an!</label>

                        <span class="urlaub_span3">
                           <input type="text" name="urlaub_anspruch" id="urlaub_anspruch" value="<?php echo $daten->urlaubsanspruch; ?>" disabled>
                           <label for="urlaub_anspruch"> Arbeitstage Jahresurlaub stehen mir zu</label>
                        </span>
                        <span class="urlaub_span4">
                           <input type="text" name="urlaub_rest" id="urlaub_rest" value="<?php echo $daten->resturlaub; ?>" disabled>
                           <label for="urlaub_rest"> Arbeitstag/e Resturlaub stehen mir aus dem Vorjahr zu</label>
                        </span>
                        <span class="urlaub_span4">
                           <input type="text" name="urlaub_erhalten" id="urlaub_erhalten" value="<?php echo $daten->urlaub_erhalten; ?>" disabled>
                           <label for="urlaub_erhalten">Arbeitstag/e Jahresurlaub habe ich bereits erhalten</label>
                        </span>
                        <span class="urlaub_span4">
                           <input type="text" name="urlaub_saldo" id="urlaub_saldo" value="<?php echo $daten->urlaubssaldo; ?>" disabled>
                           <label for="urlaub_saldo"> Verbleibende Urlaubstag</label>
                        </span>
                     </div>
                     <p>
                        <label id="alert1" class="alert">Bitte geben Sie Angaben zu Ihrem Urlaub ein!</label>
                        <label id="alert3" class="alert">Bitte überprüfen Sie Ihre Angaben! Urlaubsbeginn muss vor Urlaubsende sein.</label>
                        <label id="alert4" class="alert">So viele Urlaubstage steht Ihnen nicht zu. Bitte geben Sie ein anderes Datum ein!</label>
                        <label id="alert5" class="alert">Achtung: für mind. einen Tag liegt bereits einen Urlaub vor. Bitte überprüfen Sie Ihre Angaben!</label>
                        <label id="alert6" class="alert">Achtung: Sie können keinen Urlaub beantragen für Tage, an denen Sie krankgemeldet sind!</label>
                     </p>

                     <!-- dialog -->
                     <div id="body-overlay"></div>

                     <div class="dialog" id="signieren-dialog">
                        <a role="button" id="dialog-schliessen-button" onclick="dialogSchliessen('signieren-dialog')">
                           <i class="far fa-window-close"></i>
                        </a>
                        <h2>Digital unterschreiben!</h2>
                        <p id="">Bitte geben Sie Ihre digitale ID-Datei und das zugehörige Kennwort der digitalen ID ein, um digital unterschreiben zu können.</p>
                        <p id="ID">
                           <label>Digitale ID-Datei</label><br><br>
                           <label for="digital_ID" id="upload_btn">Datei auswählen</label>
                           <input type="file" name="digital_ID" id="digital_ID" accept=".p12, .pfx" onchange="change2()">
                           <span id="datei_ausgewaehlt">Keine Datei ausgewählt.</span>
                        </p>
                        <p class="password-container">
                           <label for="ID_password">Passwort</label>
                           <input type="password" name="ID_password" class="password-field" id="ID_password" placeholder="Passwort" onclick="change2()">
                           <i class="bi bi-eye-slash" id="password_toggle"> </i>
                        </p>
                        <p>
                           <label id="dialog_alert1" class="alert">Bitte geben Sie Ihre digitale ID und das zugehörige Passwort ein!</label>
                           <label id="dialog_alert2" class="alert">Das Passwort ist falsch! Bitte geben Sie ein richtiges Passwort ein.</label>
                        </p>
                        <input type="submit" id="submit_btn" value="Digital signieren & Absenden"></input>
                     </div>

                     <div class="row">
                        <div class="three columns"><input type="button" value="signieren & Absenden" onclick="validate()"></div>
                        <div class="three columns"><input type="reset" value="Löschen"></div>
                     </div>
                  </form>

               </div>
            </article>
         </section>


         <script>
         </script>
         <script src="../js/validation_urlaub.js"></script>
         <script src="../js/dialoge.js"></script>


         <aside class="module complement two columns nav-apps-container">
            <ul class="nav-apps">
               <li class="nav-app active"><a class="nav-app-links toggle" href="#">Hotline</a>
                  <section class="module-content">
                     <ul class="center">
                        <li>Telefon.: (83-) 31311</li>
                        <li><a href="mailto:ivv5hotline@uni-muenster.de" class="ext" target="_blank" title=":: Link öffnet neues Fenster">Email:
                              ivv5hotline@uni-muenster.de</a></li>
                        <li>Büros / Office Rooms</li>
                        <li><a href="mailto:ivv5hotline@uni-muenster.de" class="ext" target="_blank" title=":: Link öffnet neues Fenster">Einsteinstraße 62, Raum 105</a>
                        </li>
                        <li><a href="mailto:ivv5hotline@uni-muenster.de" class="ext" target="_blank" title=":: Link öffnet neues Fenster">Fliednerstraße 21, Raum 21b</a>
                        </li>
                        <li><a href="mailto:ivv5hotline@uni-muenster.de" class="ext" target="_blank" title=":: Link öffnet neues Fenster">Horstmarer Landweg 62b, VG 9</a>
                        </li>
                        <li><a href="https://www.uni-muenster.de/IVV5/Organisation/oeffungszeiten.shtml" class="int">Öffnungszeiten</a></li>
                     </ul>
                  </section>
               </li>
               <li class="nav-app"><a class="nav-app-links toggle" href="#">Computerlabs </a>
                  <section class="module-content">
                     <ul class="center">
                        <li><a href="http://uvlsf.uni-muenster.de/qisserver/rds?state=wplan&amp;act=Raum&amp;pool=Raum&amp;P.subc=plan&amp;raum.rgid=17936&amp;idcol=raum.rgid&amp;idval=17936&amp;raum.dtxt=140&amp;purge=n&amp;getglobal=n&amp;text=Fliednerstr.+21+-+Fl+140++%28ADV-Kleinrechneranl.-raum%29" class="int">Computerlab-Belegung Fliednerstraße (Raum 2140)</a></li>
                        <li><a href="https://www.google.com/calendar/embed?src=6jvh6pif6c78m0j40pqtjns7to%40group.calendar.google.com&amp;ctz=Europe/Berlin" class="ext" target="_blank" title=":: Link öffnet neues Fenster">Computerlab-Belegung
                              Einsteinstraße (SR-A, Raum 124)</a></li>
                        <li><a href="https://www.uni-muenster.de/studium/orga/pcpools.html" class="int">Übersicht Computerlabs</a></li>
                     </ul>
                  </section>
               </li>
               <li class="nav-app active"><a class="nav-app-links toggle" href="#">(Selbst-)Hilfe</a>
                  <section class="module-content">
                     <ul class="center">
                        <li><a href="https://www.uni-muenster.de/IVV5WS/DocWiki/doku.php?id=public:einstieg" class="int">IVV5-Wiki</a></li>
                        <li><a href="https://www.uni-muenster.de/ZIVwiki/bin/view/Anleitungen/WebHome" class="int">ZIV-Wiki</a></li>
                        <li><a href="https://www.uni-muenster.de/ZIV/Software/Beratung/index.html" class="int">ZIV Softwareberatung</a></li>
                        <li><a href="https://www.uni-muenster.de/meinZIV" class="int">MeinZIV</a></li>
                        <li><a href="https://ivv5dienste.uni-muenster.de/" class="int">Selfservice der
                              IVV5 (WOL, Drucker)</a></li>
                     </ul>
                  </section>
               </li>
               <li class="nav-app"><a class="nav-app-links toggle" href="#">Nützliches</a>
                  <section class="module-content">
                     <ul class="center">
                        <li><a href="https://www.uni-muenster.de/ZIV" class="int">ZIV</a></li>
                        <li><a href="https://www.uni-muenster.de/ZIV/DasZIV/Ordnungen/" class="int">Ordnungen + Richtlinien</a></li>
                        <li><a href="https://www.uni-muenster.de/IVV5WS/DocWiki/doku.php?id=public:netzuganggaeste" class="int">Netzzugang für Gäste</a></li>
                        <li><a href="https://www.uni-muenster.de/imperia/md/content/informatik/becker/ivv5/bericht20.pdf" class="int">Jahresbericht (2020)</a></li>
                     </ul>
                  </section>
               </li>
               <li class="nav-app"><a class="nav-app-links toggle" href="#">Software</a>
                  <section class="module-content">
                     <ul class="center">
                        <li><a href="https://www.uni-muenster.de/IVV5WS/DocWiki/doku.php?id=public:zugriff_auf_microsoft-software_im_rahmen_von_msdnaa" class="ext" target="_blank" title=":: Link öffnet neues Fenster">Azure
                              Dev Tools for Teaching (MS-Software)</a></li>
                        <li><a href="https://www.uni-muenster.de/IVV5WS/DocWiki/doku.php?id=public:zugriff_auf_microsoft-software_im_rahmen_von_msdnaa" class="int">Dokumentation zu Azure Dev Tools for Teaching</a></li>
                        <li><a href="https://www.uni-muenster.de/ZIV/CoCoS/Software.html" class="int">Software für wiss, Rechnen</a></li>
                        <li><a href="https://www.uni-muenster.de/IVV5WS/DocWiki/doku.php?id=public:informationen_zum_tex-system" class="int">TeX</a></li>
                        <li><a href="https://nrw.asknet.de/cgi-bin/catalog/ml=DE" class="ext" target="_blank" title=":: Link öffnet neues Fenster">AskNet,
                              Lizenzprogramme</a></li>
                     </ul>
                  </section>
               </li>
               <li class="nav-app"><a class="nav-app-links toggle" href="#">Webinterfaces für EMail</a>
                  <section class="module-content">
                     <ul class="center">
                        <li><a href="https://permail.uni-muenster.de/" class="int">perMail</a></li>
                        <li><a href="https://owa.wwu.de/" class="int">Outlook Web Access (eXchange)</a>
                        </li>
                     </ul>
                  </section>
               </li>
            </ul>
         </aside>

      </div>
      <footer>
         <div class="row top"><a class="nav-slideup six columns" href="#top">nach oben</a></div>
         <div class="row upper">
            <aside class="module two columns">
               <h2>Kontakt</h2>
               <address class="h-card"><span class="p-org">Westfälische Wilhelms-Universität
                     Münster</span><br><span class="p-name">IVV5</span><br>
                  <p class="p-adr h-adr"><a href="https://www.uni-muenster.de/uv/wwuaz/lageplan/1151" class="p-street-adress">Einsteinstr. 62</a><br><span class="p-postal-code">48149</span> <span class="p-locality">Münster</span></p>
                  Tel: <span class="p-tel">0251 - 83 31311</span><br>Fax: <span class="p-tel-fax">+49 251
                     83-33755</span><br><a class="u-email" href="mailto:ivv5hotline@uni-muenster.de">ivv5hotline@uni-muenster.de</a>
               </address>
            </aside>
            <div class="two columns">&nbsp;</div><!-- Beginn WWU-Claim-->
            <div class="module two columns not-on-smartphone">
               <div class="claim"><a href="https://www.uni-muenster.de/de/" title="Startseite der WWU" class="claim">wissen.leben</a></div>
            </div><!-- Ende WWU-Claim -->
         </div>
         <div class="row lower">
            <nav class="nav-footer module module three columns">
               <ul>
                  <li class="js" style="width: 91.9667px; max-width: 91.9667px;"><a href="https://www.uni-muenster.de/IVV5/impressum.html">Impressum</a></li>
                  <li class="js" style="width: 164.617px; max-width: 164.617px;"><a href="https://www.uni-muenster.de/de/datenschutzerklaerung.html">Datenschutzhinweis</a>
                  </li>
                  <li class="js" style="width: 132.3px; max-width: 132.3px;"><a href="https://www.uni-muenster.de/IVV5WS/DocWiki/doku.php?id=public:barrierfree">Barrierefreiheit</a>
                  </li>
               </ul>
            </nav>
            <div class="module module-content three columns">
               <p>© 2021 IVV5</p>
            </div>
         </div>
      </footer>
   </div>

   <!--   <script src="../layout/jquery.js"></script>   
   <script src="../layout/main.js"></script>
   <script src="../layout/lazysizes.js"></script>  -->

</body>

</html>