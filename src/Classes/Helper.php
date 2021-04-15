<?php

namespace Schachbulle\ContaoHelperBundle\Classes;

class Helper
{

	protected static $monate = array
	(
		1  => 'Januar',
		2  => 'Februar',
		3  => 'März',
		4  => 'April',
		5  => 'Mai',
		6  => 'Juni',
		7  => 'Juli',
		8  => 'August',
		9  => 'September',
		10 => 'Oktober',
		11 => 'November',
		12 => 'Dezember'
	);

	/**
	 * Set an object property
	 *
	 * @param string $strKey   The property name
	 * @param mixed  $varValue The property value
	 *
	 * @throws \Exception If $strKey is unknown
	 */
	public function __set($strKey, $varValue)
	{
		switch ($strKey)
		{
			case 'setDate':
				return self::putDate($varValue);
				break;

			default:
				throw new \Exception(sprintf('Invalid argument "%s"', $strKey));
				break;
		}
	}

	/**
	 * @param integer       $startdate      Unix-Zeitstempel des Startdatums
	 * @param integer       $enddate        Unix-Zeitstempel des Endedatums
	 * @param boolean       $addtime        Uhrzeit gesetzt ja/nein
	 * @param integer       $starttime      Uhrzeit Terminanfang als Unix-Zeitstempel
	 * @param integer       $endtime        Uhrzeit Terminende als Unix-Zeitstempel
	 * @param stringr       $delimiter      Trennzeichen zwischen Datum und Uhrzeit
	 * @return string
	 */
	static function getEventdate($startdate = 0, $enddate = 0, $addtime = false, $starttime = 0, $endtime = 0, $delimiter = ',')
	{

		$datumstring = ''; // Datumsstring beginnen
		$uhrzeitstring = ''; // Datumsstring beginnen

		// Startdatum und Endedatum vergleichen
		if($startdate && $enddate)
		{
			if($startdate == $enddate)
			{
				// Start- und Endedatum sind gleich
				$datumstring = date("d.m.Y",$startdate);
			}
			else
			{
				// Start- und Endedatum sind ungleich
				$start[0] = date("d",$startdate); // Starttag
				$start[1] = date("m",$startdate); // Startmonat
				$start[2] = date("Y",$startdate); // Startjahr
				$ende[0] = date("d",$enddate); // Endetag
				$ende[1] = date("m",$enddate); // Endemonat
				$ende[2] = date("Y",$enddate); // Endejahr
				if($start[2] == $ende[2])
				{
					// gleiches Jahr
					$temp[0] = "";
					$temp[1] = $ende[2];
				}
				else
				{
					// unterschiedliches Jahr
					$temp[0] = $start[2];
					$temp[1] = $ende[2];
				}
				if($start[1] == $ende[1])
				{
					// gleicher Monat
					$temp[1] = $ende[1].".".$temp[1];
				}
				else
				{
					// unterschiedlicher Monat
					$temp[0] = $start[1].".".$temp[0];
					$temp[1] = $ende[1].".".$temp[1];
				}
				if($start[0] == $ende[0])
				{
					// gleicher Tag
					$temp[1] = $ende[0].".".$temp[1];
				}
				else
				{
					// unterschiedlicher Tag
					$temp[0] = $start[0].".".$temp[0];
					$temp[1] = $ende[0].".".$temp[1];
				}
				$datumstring = $temp[0]." - ".$temp[1];
			}
		}
		else
		{
			// Endedatum ist nicht gesetzt
			$datumstring = date("d.m.Y",$startdate);
		}

		// Uhrzeit gesetzt?
		if($addtime)
		{
			$startzeit = date('H:i', $starttime);
			$endezeit = date('H:i', $endtime);
			$uhrzeitstring = date('H:i', $starttime);
			if($endezeit && ($endezeit != $startzeit) && $endezeit != '01:00')
			{
				$uhrzeitstring .= ' - '.date('H:i', $endtime);
			}
		}

		$content = '<span class="date">'.$datumstring.'</span>';
		if($uhrzeitstring) $content .= $delimiter.' <span class="time">'.$uhrzeitstring.' Uhr </span>';
		return $content;

	}


	static function setDate($varValue)
	{
		return self::putDate($varValue);
	}

	/**
	 * Datumswert aus Datenbank umwandeln
	 * @param $varValue       int         JJJJMMTT / JJJJMM00 / JJJJ0000 / 0
	 * @return                string      TT.MM.JJJJ / MM.JJJJ / JJJJ / false
	 */
	static function getDate($varValue)
	{
		$laenge = strlen($varValue);
		$temp = '';
		if(self::is_digit($varValue))
		{
			switch($laenge)
			{
				case 8: // JJJJMMTT
					$tag = (int)substr($varValue,6,2);
					$monat = (int)substr($varValue,4,2);
					$jahr = (int)substr($varValue,0,4);
					break;
				case 6: // JJJJMM
					$tag = 0;
					$monat = (int)substr($varValue,4,2);
					$jahr = (int)substr($varValue,0,4);
					break;
				case 4: // JJJJ
					$tag = 0;
					$monat = 0;
					$jahr = (int)$varValue;
					break;
				case 1: // Auf 0 prüfen
					$tag = 0;
					$monat = 0;
					$jahr = 0;
					break;
				default: // anderer Wert
					return $varValue;
			}
			// Werte in Datum TT.MM.JJJJ, MM.JJJJ oder JJJJ umwandeln
			$temp = $jahr ? substr('0000'.$jahr,-4) : $temp; // JJJJ
			$temp = $monat ? substr('00'.$monat,-2).'.'.$temp : $temp; // MM
			$temp = $tag ? substr('00'.$tag,-2).'.'.$temp : $temp; // TT
			return $temp;
		}

		return $varValue; // Eingabewert ausgeben

	}

	/**
	 * Datumswert für Datenbank umwandeln
	 * @param $varValue       string      TT.MM.JJJJ / MM.JJJJ / JJJJ / anderer Wert
	 * @return                int         JJJJMMTT / JJJJMM00 / JJJJ0000 / 0
	 */
	static function putDate($varValue)
	{
		$laenge = strlen(trim($varValue));
		$temp = '';
		switch($laenge)
		{
			case 10: // TT.MM.JJJJ
				$temp = (int)substr($varValue,6,4).substr($varValue,3,2).substr($varValue,0,2);
				break;
			case 7: // MM.JJJJ
				$temp = (int)substr($varValue,3,4).substr($varValue,0,2).'00';
				break;
			case 4: // JJJJ
				$temp = (int)$varValue.'0000';
				break;
			default: // anderer Wert
				$temp = 0;
		}

		return $temp;
	}

	/**
	 * Datumswert JJJJMMTT / JJJJMM / JJJJ umwandeln (mit Monatsname)
	 * @param mixed
	 * @return mixed
	 */
	static function getDateString($varValue)
	{
		$datum = self::getDate($varValue);
		$laenge = strlen($datum);
		$temp = '';

		switch($laenge)
		{
			case 10: // TT.MM.JJJJ
				$temp = (substr($datum,0,2)+0).'. '.self::$monate[substr($datum,3,2)+0].' '.substr($datum,6,4);
				break;
			case 7: // MM.JJJJ
				$temp = self::$monate[substr($datum,0,2)+0].' '.substr($datum,3,4);
				break;
			case 4: // JJJJ
				$temp = $datum;
				break;
			default: // anderer Wert
				$temp = $varValue;
		}
		return $temp;
	}

	/**
	 * Datumswert für Datenbank umwandeln
	 * @param mixed
	 * @return mixed
	 */
	static function replaceCopyright($string)
	{
		// Nach Copyright per Regex suchen
		$found = preg_match("/(\[.+\])/",$string,$treffer,PREG_OFFSET_CAPTURE);
		if($found)
		{
			// Copyright gefunden, Länge und Position speichern
			$cplen = strlen($treffer[0][0]);
			$cppos = $treffer[0][1];
			// Copyright ersetzen
			$cpstr = str_replace("[","<div class=\"rechte\">",$treffer[0][0]);
			$cpstr = str_replace("]","</div>",$cpstr);
			// Restliche Bildunterschrift extrahieren
			$string = substr($string,0,$cppos).substr($string,$cppos+$cplen);
			// Copyright hinzufügen
			return $cpstr.$string;
		}

		return $string;
	}

	/**
	 * Holt aus der Bildunterschrift den String mit dem Copyright
	 * @param mixed
	 * @return mixed
	 */
	static function getCopyright($string)
	{
		static $begrenzer = array('[', ']');

		// Nach Copyright per Regex suchen
		$found = preg_match("/(\[.+\])/",$string,$treffer,PREG_OFFSET_CAPTURE);
		if($found)
		{
			$cpstr = str_replace($begrenzer, '', $treffer[0][0]); // Begrenzer entfernen und Copyright zurückgeben
		}
		else $cpstr = ''; // Kein Copyright

		return $cpstr;
	}

	/**
	 * Check input for existing only of digits (numbers)
	 * @author Tim Boormans <info@directwebsolutions.nl>
	 * @param $digit
	 * @return bool
	 */
	static function is_digit($digit)
	{
		if(is_int($digit))
		{
			return true; // 123
		}
		elseif(is_string($digit))
		{
			return ctype_digit($digit); // true "123"
		}
		else
		{
			// booleans, floats and others
			return false;
		}
	}

	/**
	 * Mehrdimensionales Array bequem sortieren
	 * $sorted = sortArrayByFields(
	 *     $data,
	 *     array(
	 *         'jahrgang' => SORT_DESC,
	 *         'nachname' => array(SORT_ASC, SORT_STRING)
	 *     )
	 * );
	 */
	static function sortArrayByFields($arr, $fields)
	{
		if(!is_array($arr)) return $arr; // Kein Array, Daten unverändert zurückgeben

		$sortFields = array();
		$args       = array();

		foreach ($arr as $key => $row)
		{
			foreach ($fields as $field => $order)
			{
				$sortFields[$field][$key] = $row[$field];
			}
		}

		foreach ($fields as $field => $order)
		{
			$args[] = $sortFields[$field];

			if (is_array($order))
			{
				foreach ($order as $pt)
				{
					$args[$pt];
				}
			}
			else
			{
				$args[] = $order;
			}
		}

		$args[] = &$arr;

		call_user_func_array('array_multisort', $args);

		return $arr;
	}

	static function is_utf8($str)
	{
		$strlen = strlen($str);
		for($i=0; $i<$strlen; $i++)
		{
			$ord = ord($str[$i]);
			if($ord < 0x80) continue; // 0bbbbbbb
			elseif(($ord&0xE0)===0xC0 && $ord>0xC1) $n = 1; // 110bbbbb (exkl C0-C1)
			elseif(($ord&0xF0)===0xE0) $n = 2; // 1110bbbb
			elseif(($ord&0xF8)===0xF0 && $ord<0xF5) $n = 3; // 11110bbb (exkl F5-FF)
			else return false; // ungültiges UTF-8-Zeichen
			for($c=0; $c<$n; $c++) // $n Folgebytes? // 10bbbbbb
				if(++$i===$strlen || (ord($str[$i])&0xC0)!==0x80)
					return false; // ungültiges UTF-8-Zeichen
		}
		return true; // kein ungültiges UTF-8-Zeichen gefunden
	}

	/**
	 * Führt Contao's Slug-Generator aus, um Umlaute und Sonderzeichen zu ersetzen
	 * siehe auch https://community.contao.org/de/showthread.php?75719-Optionen-f%C3%BCr-contao-slug
	 * @param string    String, der geglättet werden soll
	 * @return          fertiger String
	 */
	function generateAlias($string)
	{
		// Optionen für die Aliasgenerierung setzen
		$slugOptionen = (object)array
		(
			'setValidChars' => 'a-z0-9',
			'setLocale'     => 'de',
			'setDelimiter'  => '-'
		);
		// Alias generieren
		$varValue = \System::getContainer()->get('contao.slug')->generate($string, $slugOptionen);

		return $varValue;
	}

	function ANSI_gross($eingabe)
	{
	# --------------------------------------------------------
	# Wandelt eingabe in Großbuchstaben um, ersetzt dabei
	# Sonderzeichen in A-Z (z.B. é in E)
	# Ausgabe entspricht dann Feldname + '_g'
	# --------------------------------------------------------
		$mapping = array
			(32, 32, 32, 32, 32, 32, 32, 32, 32, 32, 32, 32, 32, 32, 32, 32,
			 32, 32, 32, 32, 32, 32, 32, 32, 32, 32, 32, 32, 32, 32, 32, 32,
			 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47,
			 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61, 62, 63,
			 64, 65, 66, 67, 68, 69, 70, 71, 72, 73, 74, 75, 76, 77, 78, 79,
			 80, 81, 82, 83, 84, 85, 86, 87, 88, 89, 90, 91, 92, 93, 94, 95,
			 96, 65, 66, 67, 68, 69, 70, 71, 72, 73, 74, 75, 76, 77, 78, 79,
			 80, 81, 82, 83, 84, 85, 86, 87, 88, 89, 90,123,124,125,126,127,
			 32, 32, 32, 32, 32, 32, 32, 32, 32, 32, 32, 32, 32, 32, 32, 32,
			 32, 32, 32, 32, 32, 32, 32, 32, 32, 32, 32, 32, 32, 32, 32, 32,
			 32, 32, 32, 32, 32, 32, 32, 32, 32, 32, 32, 32, 32, 32, 32, 32,
			 32, 32, 32, 32, 32, 32, 32, 32, 32, 32, 32, 32, 32, 32, 32, 32,
			 65, 65, 65, 65,196, 65, 32, 67, 69, 69, 69, 69, 73, 73, 73, 73,
			 32, 78, 79, 79, 79, 79,214, 32, 32, 85, 85, 85,220, 89, 32,223,
			 65, 65, 65, 65,196,197,198, 67, 69, 69, 69, 69, 73, 73, 73, 73,
			 32, 78, 79, 79, 79, 79,214, 32,216, 85, 85, 85,220, 89, 32, 89);
		$ausgabe = '';
		for ($i=0; $i<strlen($eingabe); $i++)
			$ausgabe .= chr($mapping[ord(substr($eingabe, $i, 1))]);

		$umlaute = array('Ä'=>'AE', 'Æ'=>'AE', 'Å'=>'AU', 'Ö'=>'OE', 'Ø'=>'OE', 'Ü'=>'UE', 'ß'=>'SS');
		$ausgabe = strtr($ausgabe, $umlaute);
		return $ausgabe;
	}

	function ANSItoUTF8($string)
	{
		$suchen = array('Â½', 'Ã¤');
		$ersetzen = array('½', 'ä');

		$neu = utf8_decode($string);
		//$string = str_replace($suchen, $ersetzen, $string);
		return "|$string|$neu|";
	}

	function NameDrehen($string)
	{
		// Konvertiert Namen der Form Nachname,Vorname,Titel nach Titel Vorname Name
		list($nname,$vname,$titel) = explode(',', $string);
		$result = $titel;
		if($result) $result .= ' '.$vname;
		else $result = $vname;
		if($result) $result .= ' '.$nname;
		else $result = $nname;
		return $result;
	}

	/**
	 * Funktion ArrayAufloesen
	 *
	 * @param     string          Bsp.: '1,3-7,8-9,34'
	 *
	 * @return    array           Bsp.: array('1','3','4','5','6','7','8','9','34')
	 */
	function StringToArray($string)
	{
		$array = explode(',', $string);

		$newArray = array();
		foreach($array as $item)
		{
			if(ctype_digit($item))
			{
				// Integerzahl direkt übernehmen
				$newArray[] = $item;
			}
			else
			{
				// String in der Form "Zahl-Zahl" auflösen
				$temp = explode("-", $item);
				for($x = $temp[0]; $x <= $temp[1]; $x++)
				{
					$newArray[] = $x;
				}
			}
		}
		return $newArray;
	}

	/**
	 * Hilfsfunktion:
	 * Kürzt einen String auf x Zeichen
	 *
	 * @return string
	 */
	function StringKuerzen($value, $anzahl)
	{
		if(mb_detect_encoding($value,'UTF-8, ISO-8859-1') === 'UTF-8')
		{
			# Der Turniername ist in UTF-8 kodiert und muß vor der Kürzung umgewandelt werden
			$value = utf8_decode($value);
		}

		// Gekürzten Turniernamen generieren und wieder in UTF-8 umwandeln
		$neu = (strlen($value) > $anzahl) ? substr($value,0,$anzahl) : $value;
		return utf8_encode($neu);

	}
}
