<?php
@session_start();

require_once dirname(__FILE__, 3) . "/vendor/autoload.php";
require_once 'login_backend.php';
?>

<!DOCTYPE html>
<html lang="de" class="no-js">

<head>
  <meta charset="utf-8">
  <meta http-equiv="Content-Security-Policy" content="default-src: https: data: 'unsafe-inline' 'unsafe-eval';" />
  <title>Anmeldung - Login</title>
  <meta http-equiv="content-type" content="text/html" />
  <meta http-equiv="x-ua-compatible" content="IE=edge" />
  <meta name="author" content="Westf&auml;lische Wilhelms-Universit&auml;t M&uuml;nster, WWU M&uuml;nster, Online-Redaktion" />
  <meta name="publisher" content="Westf&auml;lische Wilhelms-Universit&auml;t M&uuml;nster" />
  <meta name="copyright" content="&copy; 2020 " />
  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" />
  <link href="../layout/main.css" rel="stylesheet" type="text/css">
  <link href="../layout/webformulare.css" rel="stylesheet" type="text/css">
  <link href="primary.css" rel="stylesheet" type="text/css">
  <link href="secondary.css" rel="stylesheet" type="text/css">
  <link href="../layout/print.css" rel="stylesheet" type="text/css" media="print">
  <script src="../layout/modernizr.js"></script>
  <link href="https://www.uni-muenster.de/de/impressum.html" rel="copyright" />
  <link href="https://www.uni-muenster.de/uv/wwuaz/unilist" rel="index" />
  <link href="https://www.uni-muenster.de/wwu/suche/" rel="search" />
  <link href="https://www.uni-muenster.de/imperia/md/content/allgemein/farbunabhaengig/favicon.ico" rel="shortcut icon" />
  <script src="../layout/jquery.js"></script>
  <script src="../layout/main.js"></script>
  <script src="../layout/lazysizes.js"></script>
  <script type="text/javascript">
    /* <![CDATA[ */
    $(document).ready(function() {
      $("#httpd_username")
        .data("oldval", $("#httpd_username").val())
        .bind("input propertychange", function() {
          var n = $(this);
          var nv = n.val().toLowerCase();
          if (-1 != nv.search(/^([a-z]([a-z_][a-z0-9_]{0,6})?)?$/)) return n.val(nv).data("oldval", nv);
          if (-1 == nv.search(/^([a-z]([a-z_][a-z0-9_]{0,6})?)?$/i)) alert("Bitte geben Sie die WWU-Kennung ein, nicht die E-Mail-Adresse oder etwas anderes.\n\nPlease enter the WWU ID, not the e-mail address or anything else.");
          return n.val(n.data("oldval"));
        })
        .focus();
      $("#ssologindehrefxsso").attr("href", "https://xsso.uni-muenster.de" + window.location.pathname + window.location.search + window.location.hash);
      $("#ssologindehrefssso").attr("href", "https://ssso.uni-muenster.de" + window.location.pathname + window.location.search + window.location.hash);
      $("#ssologinenhrefxsso").attr("href", "https://xsso.uni-muenster.de" + window.location.pathname + window.location.search + window.location.hash);
      $("#ssologinenhrefssso").attr("href", "https://ssso.uni-muenster.de" + window.location.pathname + window.location.search + window.location.hash);
      $("#ssologinarticle1").removeClass("extended").addClass("short");
      $("#ssologinarticle2").css("display", "block");
    });
    /* ]]> */
  </script>
</head>

<body id="mnav" class="layout2017">
  <div class="wrapper" id="top">

    <header>
      <div id="logos" role="banner">
        <a href="https://www.uni-muenster.de/de/" class="wwulogo svg"><img src="../layout/wwu.svg" width="332" height="96" alt="WWU M&uuml;nster" id="logo" class="university-logo svg"></a>
      </div>
      <div id="skipnav" class="noprint">
        <ul>
          <li><a href="#inhalt">zum Inhalt</a></li>
        </ul>
      </div>

      <nav class="nav-language-container">
        <ul class="nav-language">
          <li><a href="#" title="Deutsch" hreflang="de"><abbr title="Deutsch">de</abbr></a></li>
          <li><a href="#" title="English" hreflang="en"><abbr title="English">en</abbr></a></li>
        </ul>
      </nav>
      <div class="nav-mobile-menu">
        <nav class="nav-mobile">
        </nav>
        <div class="nav-search-mobile">
        </div>
      </div>
    </header>
    <div class="nav-container row">
      <nav class="nav-main six columns">
        <div class="nav-search cse" role="search">
        </div>
      </nav>
      <div class="wrapper-complement-nav six columns">
        <nav class="nav-audience-container">
          <ul id="zielgruppennavigation" class="nav-audience zentral">
          </ul>
        </nav>
      </div>
    </div>
    <div class="content row">
      <div class="nav-breadcrumb six columns">
        <nav id="breadcrumb">
          <ul>
          </ul>
        </nav>
      </div>
      <section class="complement two columns">
        <nav class="module nav-level-nplusone nav-2015 nav-partial">
        </nav>
      </section>
      <section id="inhalt" class="main four columns" rel="main">
        <article class="module extended" id="ssologinarticle1">
          <div class="module-content">
            <div lang="de" xml:lang="de" class="only-when-de">
              <h2>Anmeldung</h2>
              <p>Um sich im Single-Sign-On-Bereich der WWU M&uuml;nster anzumelden, geben Sie bitte Ihre WWU-Kennung und Ihr WWU-Passwort ein.</p>
              <p>(Bei der Anmeldung wird ein technisch notwendiges Cookie gesetzt. Dieses enth&auml;lt Ihr Sitzungs-Ticket.)</p>
            </div>
            <div lang="en" xml:lang="en" class="only-when-en">
              <h2>Login</h2>
              <p>To log into the Single Sign On area of the WWU M&uuml;nster please enter your WWU ID and your WWU password.</p>
              <p>(When logging in, a technically necessary cookie is set. It contains your session ticket.)</p>
            </div>

            <form method="POST" action="" name="login">
              <?php include('errors.php'); ?>

              <p>&nbsp;<br />
                <span style="display:inline-block;min-width:50%;"><label for="httpd_username"><span class="only-when-de">WWU-Kennung:</span> <span class="only-when-en">WWU ID:</span></label></span>
                <input type="text" name="httpd_username" id="httpd_username" value="" required="required" minlength="2" pattern="^[a-z][a-z_][a-z0-9_]{0,6}$" /><br />
                <span style="display:inline-block;min-width:50%;"><label for="httpd_password"><span class="only-when-de">WWU-Passwort:</span> <span class="only-when-en">WWU password:</span></label></span>
                <input type="password" name="httpd_password" id="httpd_password" value="" required="required" minlength="1" /><br />
                <input type="submit" value="Anmelden / login" name="login" />
              </p>
            </form>
          </div>
        </article>
        <article class="module short" id="ssologinarticle2" style="display:none;">
          <div class="module-content">

            <div lang="de" xml:lang="de" class="only-when-de">
              <h2>Oder:</h2>
              <ul class="linkliste no-indent">
                <li>
                  <p><strong><a class="int" href="https://ssso.uni-muenster.de/" id="ssologindehrefssso">Anmeldung mit dem internationalen Single Sign-On</a></strong> (f&uuml;r Angeh&ouml;rige anderer Hochschulen usw.)</p>
                </li>
                <li>
                  <p><strong><a class="int" href="https://xsso.uni-muenster.de/" id="ssologindehrefxsso">Anmeldung mit einer digitalen ID (Zertifikat)</a></strong> (nur&nbsp;f&uuml;r&nbsp;WWU-Angeh&ouml;rige)</p>
                </li>
              </ul>
            </div>
            <div lang="en" xml:lang="en" class="only-when-en">
              <h2>Or:</h2>
              <ul class="linkliste no-indent">
                <li>
                  <p><strong><a class="int" href="https://ssso.uni-muenster.de/" id="ssologinenhrefssso">Login with the international Single Sign-On</a></strong> (for&nbsp;members of other universities etc.)</p>
                </li>
                <li>
                  <p><strong><a class="int" href="https://xsso.uni-muenster.de/" id="ssologinenhrefxsso">Login with a digital ID (certificate)</a></strong> (WWU&nbsp;members&nbsp;only)</p>
                </li>
              </ul>
            </div>
          </div>
        </article>
      </section>
      <aside class="module complement two columns nav-apps-container">
        <ul class="nav-apps">
          <li class="nav-app active only-when-de">
            <a class="nav-app-lectures toggle" href="#">Tipps</a>
            <section class="module-content">
              <ul class="center">
                <li><a href="https://www.uni-muenster.de/IT/PasswortVergessen" class="int" target="_blank" title=":: Link &ouml;ffnet neues Fenster">Passwort vergessen?</a></li>
                <li><a href="https://www.uni-muenster.de/WWUCA/de/howto-request.html" class="int" target="_blank" title=":: Link &ouml;ffnet neues Fenster">Wie erhalte ich eine digitale ID?</a></li>
                <li><a href="https://www.uni-muenster.de/WWUCA/de/howto-setup.html" class="int" target="_blank" title=":: Link &ouml;ffnet neues Fenster">Wie verwende ich eine digitale ID?</a></li>
              </ul>
            </section>
          </li>
          <li class="nav-app active only-when-en">
            <a class="nav-app-lectures toggle" href="#">Hints</a>
            <section class="module-content">
              <ul class="center">
                <li><a href="https://www.uni-muenster.de/IT/en/PasswortVergessen" class="int" target="_blank" title=":: Link opens new window">Lost your password?</a></li>
                <li><a href="https://www.uni-muenster.de/WWUCA/en/howto-request.html" class="int" target="_blank" title=":: Link opens new window">How do I get a digital ID?</a></li>
                <li><a href="https://www.uni-muenster.de/WWUCA/en/howto-setup.html" class="int" target="_blank" title=":: Link opens new window">How do I use a digital ID?</a></li>
              </ul>
            </section>
          </li>
          <li class="nav-app active">
            <a class="nav-app-links toggle" href="#">WWU IT Hotline</a>
            <section class="module-content">
              <ul class="center">
                <li>
                  &#x2706; +49 251 83-31600 (08:00 &ndash; 17:00)<br />
                  <a class="e_mail" href="mailto:it@wwu.de"><span class="e_mail u-email">it@wwu.de</span></a>
                </li>
              </ul>
            </section>
          </li>
        </ul>
      </aside>
    </div>
    <footer>
      <div class="row top">
        <a class="nav-slideup six columns" href="#top"><span class="only-when-de">nach oben</span> <span class="only-when-en">Top of page</span></a>
      </div>
      <div class="row upper">
        <aside class="module two columns only-when-de">
          <h2>Kontakt</h2>
          <address class="h-card">
            <span class="p-org">Westf&auml;lische Wilhelms-Universit&auml;t M&uuml;nster</span><br>
            <span class="p-name">Verwaltung</span><br>
            <p class="p-adr h-adr"><a href="https://www.uni-muenster.de/uv/wwuaz/lageplan/0351" class="p-street-adress">Schlossplatz 2</a><br> <span class="p-postal-code">48149</span> <span class="p-locality">M&uuml;nster</span></p>
            Tel: <span class="p-tel">+49 251 83-0</span><br>
            Fax: <span class="p-tel-fax">+49 251 83-24831</span><br>
            <a class="u-email" href="mailto:verwaltung@uni-muenster.de">verwaltung@uni-muenster.de</a>
          </address>
          <ul class="nav-sm">
            <li><a class="sm-ico youtube" href="https://www.youtube.com/wwumuenster" title="Youtube"><span class="hidden" lang="en">Youtube</span></a></li>
            <li><a class="sm-ico facebook" href="https://www.facebook.com/wwumuenster" title="Facebook"><span class="hidden" lang="en">Facebook</span></a></li>
            <li><a class="sm-ico twitter" href="https://twitter.com/WWU_Muenster" title="Twitter"><span class="hidden" lang="en">Twitter</span></a></li>
            <li><a class="sm-ico instagram" href="https://www.instagram.com/wwu_muenster/" title="Instagram"><span class="hidden" lang="en">Instagram</span></a></li>
          </ul>
        </aside>
        <aside class="module two columns only-when-en">
          <h2>Contact</h2>
          <address class="h-card">
            <span class="p-org">University of M&uuml;nster</span><br>
            <span class="p-name">Administration</span><br>
            <p class="p-adr h-adr"><a href="https://www.uni-muenster.de/uv/wwuaz/lageplan/0351" class="p-street-adress">Schlossplatz 2</a><br> <span class="p-postal-code">48149</span> <span class="p-locality">M&uuml;nster</span></p>
            Tel: <span class="p-tel">+49 251 83-0</span><br>
            Fax: <span class="p-tel-fax">+49 251 83-24831</span><br>
            <a class="u-email" href="mailto:verwaltung@uni-muenster.de">verwaltung@uni-muenster.de</a>
          </address>
          <ul class="nav-sm">
            <li><a class="sm-ico youtube" href="https://www.youtube.com/wwumuenster" title="Youtube"><span class="hidden" lang="en">Youtube</span></a></li>
            <li><a class="sm-ico facebook" href="https://www.facebook.com/wwumuenster" title="Facebook"><span class="hidden" lang="en">Facebook</span></a></li>
            <li><a class="sm-ico twitter" href="https://twitter.com/WWU_Muenster" title="Twitter"><span class="hidden" lang="en">Twitter</span></a></li>
            <li><a class="sm-ico instagram" href="https://www.instagram.com/wwu_muenster/" title="Instagram"><span class="hidden" lang="en">Instagram</span></a></li>
          </ul>
        </aside>
        <aside class="module two columns only-when-de">
          <h2 lang="en">Top-Links</h2>
          <ul class="linkliste">
            <li><a href="https://www.uni-muenster.de/uv/wwuaz/unilist" class="int">Uni A-Z</a></li>
            <li><a href="https://www.uni-muenster.de/suche/kontakt.cgi" class="int">Personensuche</a></li>
            <li><a href="https://www.uni-muenster.de/uv/wwuaz/lageplan" class="int">Lageplan</a></li>
            <li><a href="https://www.uni-muenster.de/wwu/fak_fb/" class="int">Fachbereiche</a></li>
            <li><a href="http://www.ulb.uni-muenster.de/" class="int">Uni-Bi&#173;bli&#173;o&#173;thek</a></li>
            <li><a href="https://studium.uni-muenster.de/qisserver/rds?state=wtree&amp;search=1&amp;category=veranstaltung.browse&amp;navigationPosition=lectures%2Clectureindex&amp;breadcrumb=lectureindex&amp;topitem=lectures&amp;subitem=lectureindex" class="int">Vor&#173;le&#173;sungs&#173;ver&#173;zeich&#173;nis</a></li>
            <li><a href="https://www.uni-muenster.de/studium/studierendensekretariat.html" class="int">Stu&#173;die&#173;ren&#173;den&#173;se&#173;kre&#173;ta&#173;ri&#173;at</a></li>
            <li><a href="https://www.uni-muenster.de/IT/" class="int">WWU IT</a></li>
            <li><a href="https://www.uni-muenster.de/InternationalOffice/" class="int">International Office</a></li>
            <li><a href="https://www.uni-muenster.de/de/weiterbildung/" class="int">Weiterbildung</a></li>
            <li><a href="https://www.uni-muenster.de/Rektorat/Stellen/" class="int">Stellenausschreibungen</a></li>
          </ul>
        </aside>
        <aside class="module two columns only-when-en">
          <h2 lang="en">Top-Links</h2>
          <ul class="linkliste">
            <li><a href="https://www.uni-muenster.de/uv/wwuaz/unilist/en" class="int">Institutions A-Z</a></li>
            <li><a href="https://www.uni-muenster.de/suche/contact.cgi" class="int">Person Search</a></li>
            <li><a href="https://www.uni-muenster.de/uv/wwuaz/lageplan/en" class="int">Campus Map</a></li>
            <li><a href="https://www.uni-muenster.de/en/institutions/departments/index.html" class="int">Faculties and Departments</a></li>
            <li><a href="https://www.ulb.uni-muenster.de/" class="int">University Library</a></li>
            <li><a href="https://studium.uni-muenster.de/qisserver/servlet/de.his.servlet.RequestDispatcherServlet?state=wtree&search=1&menuid=lectureindex&noDBAction=y&init=y" class="int">Course Overview</a></li>
            <li><a href="https://www.uni-muenster.de/en/studierendensekretariat.html" class="int">Student Admission Office</a></li>
            <li><a href="https://www.uni-muenster.de/IT/en/" class="int">WWU IT</a></li>
            <li><a href="https://www.uni-muenster.de/InternationalOffice/en/" class="int">International Office</a></li>
            <li><a href="https://www.uni-muenster.de/forschung/en/wissenschaftler/beratung/index.html" class="int">Welcome Centre</a></li>
            <li><a href="https://www.uni-muenster.de/Rektorat/en/Stellen/" class="int">Jobs and vacancies</a></li>
          </ul>
        </aside>
        <!-- Beginn WWU-Claim-->
        <div class="module two columns not-on-smartphone only-when-de">
          <div class="claim">
            <a href="https://www.uni-muenster.de/de/" title="Startseite der WWU" class="claim">wissen.leben</a>
          </div>
        </div>
        <div class="module two columns not-on-smartphone only-when-en">
          <div class="claim">
            <a href="https://www.uni-muenster.de/en/" title="Startseite der WWU" class="claim">living.knowledge</a>
          </div>
        </div>
        <!-- Ende WWU-Claim -->
      </div>
      <div class="row lower">
        <nav class="nav-footer module module three columns">
          <ul>
            <li>
              <a class="only-when-de" href="https://www.uni-muenster.de/uv/wwuaz/unilist">Index</a>
              <a class="only-when-en" href="https://www.uni-muenster.de/uv/wwuaz/unilist/en">Index</a>
            </li>
            <li>
              <a class="only-when-de" href="https://www.uni-muenster.de/de/sitemap.html">Site Map</a>
              <a class="only-when-en" href="https://www.uni-muenster.de/en/sitemap.html">Site Map</a>
            </li>
            <li>
              <a class="only-when-de" href="https://www.uni-muenster.de/de/impressum.html">Impressum</a>
              <a class="only-when-en" href="https://www.uni-muenster.de/en/impressum.html">Legal Disclosure</a>
            </li>
            <li>
              <a class="only-when-de" href="https://www.uni-muenster.de/de/datenschutzerklaerung.html">Datenschutzhinweis</a>
              <a class="only-when-en" href="https://www.uni-muenster.de/en/datenschutzerklaerung.html">Privacy Statement</a>
            </li>
            <li>
              <a class="only-when-de" href="https://www.uni-muenster.de/de/barrierefreiheit.html">Barrierefreiheit</a>
              <a class="only-when-en" href="https://www.uni-muenster.de/en/barrierefreiheit.html">Accessibility</a>
            </li>
          </ul>
        </nav>
        <div class="module module-content three columns">
          <p class="only-when-de">&copy; 2020 Universit&auml;t M&uuml;nster</p>
          <p class="only-when-en">&copy; 2020 University of M&uuml;nster</p>
        </div>
      </div>
    </footer>
  </div>
  <script>
    /* <![CDATA[ */
    var browserlang = (navigator.languages || [navigator.language] || [navigator.userLanguage] || ['en']).filter(function(x) {
      return /^(de|en)/.test(x);
    });
    if (browserlang.length && /^de/.test(browserlang[0])) {
      $(".nav-language-container a[hreflang='de']").addClass("current");
      $(".only-when-en").hide();
      $("a.wwulogo").attr("href", "https://www.uni-muenster.de/de/");
    } else {
      $(".nav-language-container a[hreflang='en']").addClass("current");
      $(".only-when-de").hide();
      $("a.wwulogo").attr("href", "https://www.uni-muenster.de/en/");
    }
    $(".nav-language-container a").click(function() {
      var lang = $(this).attr("hreflang");
      $(this).addClass("current").closest("li").siblings("li").find("a").removeClass("current");
      if (lang == 'de') {
        $(".only-when-en").hide();
        $(".only-when-de").show();
        $("a.wwulogo").attr("href", "https://www.uni-muenster.de/de/");
      } else {
        $(".only-when-de").hide();
        $(".only-when-en").show();
        $("a.wwulogo").attr("href", "https://www.uni-muenster.de/en/");
      }
    });
    /* Bugfix */
    $(document).ready(function() {
      $('.nav-footer li.js').css({
        'width': 'inherit',
        'max-width': 'inherit',
        'padding': '0 0.5em'
      });
    });
    /* ]]> */
  </script>
</body>

</html>