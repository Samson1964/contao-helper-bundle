# Hilfsfunktionen Changelog

## Version 1.8.0 (2025-05-03)

* Fix: Warning: Undefined array key "mandatory" in src/Classes/Form.php (line 81) 
* Fix: Warning: Undefined array key "name" in src/Classes/Form.php (line 28) 
* Fix: Warning: Undefined array key "label" in src/Classes/Form.php (line 71) 
* Fix: Warning: Undefined variable $string in src/Classes/Form.php:65
* Add: Form-Klasse Input-Feld fieldset
* Add: Form-Klasse Select-Feld um optgroup erweitert

## Version 1.7.3 (2025-03-14)

* Fix: PHP Warning array_multisort(): Argument #1 is expected to be an array or a sort flag in Helper.php on line 360
* Fix: PHP-Warnung in Tags.php
* Add: Cache-Unterstützung in Funktion getPlayer in Klasse Tags

## Version 1.7.2 (2024-04-10)

* Change: Verbesserungen der Form-Klasse
* Add: Klasse Alter, um zu einem Datum das Alter in Jahren zurückzugeben

## Version 1.7.1 (2023-11-17)

* Change: elo-Inserttag ist jetzt verlinkt mit der FIDE-Karteikarte

## Version 1.7.0 (2023-04-27)

* Add: Zeitmessung-Klasse

## Version 1.6.4 (2022-11-11)

* Fix: Warning in PHP 8: Undefined array key "path" in Classes/Cache:59

## Version 1.6.3 (2022-11-11)

* Change: Abhängigkeit PHP-Version aufgehoben

## Version 1.6.2 (2022-05-16)

* Add: Abfrage, ob das Dewis-Bundle abgeschaltet ist. Falls ja, wird ein Leerstring bei den Tags zurückgeliefert. Verhindert fatal Error "Could not connect to host" bei Nichterreichbarkeit svw.info.

## Version 1.6.1 (2021-06-23)

* Change: Funktion getAlter ergänzt, damit auch JJJJ oder MM.JJJJ funktioniert

## Version 1.6.0 (2021-04-15)

* Add: Funktion Helper::generateAlias (zum Bereinigen von Strings mit Umlauten und Sonderzeichen)

## Version 1.5.5 (2021-04-09)

* Fix: Classes/Helper.php - Funktion getEventdate: Endezeit bei Domainfactory korrekt, bei Hetzner 01:00 - gepatcht

## Version 1.5.4 (2021-04-09)

* Fix: Classes/Helper.php - Funktion getEventdate: CSS-Klassen für Datum und Uhrzeit und Trennzeichen ergänzt

## Version 1.5.3 (2021-03-23)

* Fix: Classes/Tags.php - SOAPClient-Aufruf ersetzt durch aktuellen Aufruf aus contao-dewis-bundle

## Version 1.5.2 (2021-03-18)

* Add: Classes/Form.php - id und class bei allen Elementen, auch explanation

## Version 1.5.1 (2021-01-20)

* Add: Uhrzeitausgabe in Helper-Funktion getEventdate

## Version 1.5.0 (2020-11-26)

* Add: Funktion getEventdate (aus Samson/Helper übernommen)
* Fix: getEventdate mit falscher Anzeige bei gleichem Start- und Endetag

## Version 1.4.1 (2020-10-28)

* Fix Insert-Tag verein: Ersetzungen werden jetzt nicht nach Groß- und Kleinschreibung unterschieden

## Version 1.4.0 (2020-10-28)

* Change: Verfeinerung Insert-Tag verein aus Version 1.3.6 - Autom. Kürzen des Vereinsnamens entfernt
* Neu in den Voreinstellungen: Ersetzungen, die für System -> Einstellungen vorgegeben werden
* Neu beim Insert-Tag verein: Weitere Ersetzungen können unter System -> Einstellungen konfiguriert werden

## Version 1.3.6 (2020-10-19)

* Change: Insert-Tag verein verfeinert - Autom. Kürzen des Vereinsnamens, sowie Begrenzung auf x Zeichen
* Change: Insert-Tag ftitel verfeinert - Option lang hinzugefügt, um Titel auszuschreiben
* Fix: Tags.php in UTF8 codiert

## Version 1.3.5 (2020-09-28)

* Add: Helper-Funktion getDateString, um kompatibel mit Chronik-Bundle zu bleiben

## Version 1.3.4 (2020-07-15)

* getDate und putDate verbessert

## Version 1.3.3 (2020-07-02)

* Fix: Insert-Tag Figuren - Ausrichtung text-bottom

## Version 1.3.2 (2020-07-02)

* Fix: Debug-Zeilen entfernt und UTF8 bei changelog

## Version 1.3.1 (2020-07-02)

* Fix: Insert-Tag für Figuren - / statt | als Trennzeichen, da | intern von Contao verwendet wird

## Version 1.3.0 (2020-06-23)

* Add: Insert-Tag für Figuren

## Version 1.2.3 (2020-06-23)

* Fix: PHP Warning: Invalid argument supplied for foreach() in contao-helper-bundle/src/Classes/Helper.php on line 181
* Fix: PHP Warning: array_multisort(): Argument #1 is expected to be an array or a sort flag in contao-helper-bundle/src/Classes/Helper.php on line 208

## Version 1.2.2 (2020-06-22)

* Add: Insert-Tag verein

## Version 1.2.1 (2020-06-18)

* Add: Insert-Tag dwz
* Add: Insert-Tag elo
* Add: Insert-Tag ftitel

## Version 1.2.0 (2020-06-18)

* Add: Insert-Tag alter
* de.yml entfernt

## Version 1.1.0 (2020-05-22)

* Add: Funktion StringKuerzen

## Version 1.0.0 (2020-05-22)

* Add: Funktion StringToArray (aus chesstable -> ArrayAufloesen)

## Version 0.0.9 (2020-05-17)

* Add: Funktion NameDrehen in Helper-Klasse

## Version 0.0.8 (2020-05-16)

* Add: Funktion is_utf8 in Helper-Klasse

## Version 0.0.7 (2020-05-13)

* Add: Form.php erweitert um die Ausgabe von Feldwerten

## Version 0.0.6 (2020-05-08)

* Add: Formular-Klasse Form

## Version 0.0.5 (2020-05-05)

* Add: Funktion sortArrayByFields in Helper-Klasse

## Version 0.0.4 (2020-04-06)

* Fix: Anpassung der Cache-Klasse bzgl. Cache-Ordner (system/cache/schachbulle)

## Version 0.0.3 (2020-04-06)

* Add: Cache-Klasse

## Version 0.0.2 (2020-04-03)

* Add: Funktion getCopyright

## Version 0.0.1 (2020-04-03)

* Initialversion als Contao-4-Bundle auf der Grundlage verschiedener Funktionen alter Contao-3-Erweiterungen
