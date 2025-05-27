## BAWaelAlallan <br> Web-basierter Selfservice für die Bearbeitung von Krankmeldungen und Urlaubsanträgen

## Beschreibung & Ziel
Web-Anwendung zur Erstellung und Bearbeitung von Krank-, Geundmeldungen und Urlaubsanträgen mit der Funktion der digitalen Unterschrift.<br>
Die [IVV5](https://www.uni-muenster.de/IVV5/) nutzt für die Dokumentation der Arbeitszeit eine in php geschriebene, selbst programmierte Arbeitszeiterfassung. In dieser Arbeitszeiterfassung werden auch Urlaubstage und Krankheitstage verbucht. Die zugehörigen Prozesse sind nicht automatisiert. Durch diese Web-Anwendung werden diese Prozesse und das formularbasierte Verfahren für Krank-, Geundmeldungen und Urlaubsanträge automatisiert.<br>
Das Ziel ist neben der Automatisierung der Verbuchung von Urlaubs- und Krankheitstage ein System zu haben, wo die Mitarbeiter ihre Meldungen bzw. Anträge digital unterschreiben und abschicken können, anstatt dass man sich wie bisher per mail meldet.

## Nutzung
Die Nutzung ist relativ simple, man sollte nachdem man sich mit seiner Kennung eingeloggt hat in der Lage sein, die Felder eines Webformulars auszufüllen und wenn die Eingaben stimmen, könnte man das Formular abschicken. Bei Urlaubsanträgen sollte man den Antrag unterschreiben, daher sollte man zusätzlich seine digitale ID-Datei eingeben `(.p12 oder .pfx)` und das zugehörige Passwort. Dann werden die Vorgesetzten darüber informiert, dass es neue Dokumente im System gibt, die von Ihnen bearbeitet werden sollen.


## Voraussetzungen
* Für das Erstellen und Ausfüllen der PDF-Formulare wird [php-pdftk](https://github.com/mikehaertl/php-pdftk) benutzt. Das ist eine PHP-Bibliothek die basierend auf [PDFtk](https://www.pdflabs.com/tools/pdftk-the-pdf-toolkit/) verschiedene Bearbeitungswerkzeuge für PDF-Dokumente implementiert. Daher muss [PDFtk](https://www.pdflabs.com/tools/pdftk-the-pdf-toolkit/) schon installiert sein. Es könnte erforderlich sein (z.b. auf MacOS), den Pfad zum PDFtk Binary Beim Aufruf der pdf-Klasse `(website/vendor/mikehaertl/php-pdftk/src/Pdf.php)` als zweites Argument anzugeben. Das könnte wie folgt aussehen:

```php
use mikehaertl\pdftk\Pdf;

$pdf = new Pdf('/path/my.pdf', [
    'command' => '/some/other/path/to/pdftk',
    // or on most Windows systems:
    // 'command' => 'C:\Program Files (x86)\PDFtk\bin\pdftk.exe',
    'useExec' => true,  // May help on Windows systems if execution fails
]);
```
* Um digital signieren zu können (z.b beim Urlaubsantrag) muss man eine digitale ID-Datei haben `(.p12 oder .pfx)`, die ein Nutzerzertifikat und einen privaten Schlüssel beinhaltet. Eine digitale ID kann man u.a. im IT-Portal der [WWU IT](https://www.uni-muenster.de/IT/) beantragen. (Das digitale Signieren erfolgt mithilfe der [JSignPdf](https://github.com/intoolswetrust/jsignpdf) Bibliothek).
* Damit PDF-Dokumente erolgreich im System gespeichert werden können, sollte bei einigen Plattformen das Verzeichnis dafür ``(website/generated_files/)`` schreibbar sein.
* ``holidays.php`` nutzt u.a. Funktionen, die die Installation der [calender Extension](https://www.php.net/manual/en/book.calendar.php) von PHP erfordert. Daher sollte schon diese Extension auf dem Rechner installiert sein, auf dem die Anwendung läuft.
 
 
## Datenbank
Die SQL-Datei ``zeiterfassung_db.sql`` enthält Das Datenbank-Schema für die Anwendung bestehend aus drei Tabellen. In der Tabelle ``mitarbeiter`` werden u.a. persönliche Daten, Daten zum Urlaubsanspruch und Vertragsdaten eines Mitarbeitenden und seine Kennung und einen Passwort-Hash gespeichert (Eine Spalte für Passwort wird nicht benötigt wenn man das SSO der Uni zum Einloggen verwendet). Die ``krank`` Tabelle speichert, welche Mitarbeiter aktuell krank und bis wann sie krank sind. Die ``stundenzettel``-Tabelle enthält Eintragsdaten für u.a. Krank-, Geundmeldungen und Urlaubsanträge.


## Hinweise 
* Durch die vorhandenen ``.htaccess`` Dateien wird sichergestellt, dass nur Mitarbeiter der IVV5 (oder weitere Personengruppe) Zugriff auf die Web-Anwendung haben, und zwar nur auf die Seiten, auf die sie wirklich Zugriff benötigen. Sollte die Anwendung das SSO-System der WWU verwenden anstatt das herkömmliche Login-Verfahren, dann sind diese Dateien zu verwenden. In diesem Fall sind einige Anpassungen im Programm vorzunehmen z.B. die Nutzerkennung mit $_SERVER['REMOTE_USER'] ermitteln statt in der Variable $_SESSION zu speichern.
* Für das Versenden von Emails wird aktuell **secmail.uni-muenster.de** als Mailserver verwendet, der eine Authentifizierung erfordert. Der Username und Passwort dafür sollen in der PHP-Konfigurationsdatei (.ini) gespeichert werden um Emails versenden zu können.


