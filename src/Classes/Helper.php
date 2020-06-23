<?php

namespace Schachbulle\ContaoHelperBundle\Classes;

class Helper
{

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

	static function setDate($varValue)
	{
		return self::putDate($varValue);
	}

	/**
	 * Datumswert aus Datenbank umwandeln
	 * @param mixed
	 * @return mixed
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
					$temp = substr($varValue,6,2).'.'.substr($varValue,4,2).'.'.substr($varValue,0,4);
					break;
				case 6: // JJJJMM
					$temp = substr($varValue,4,2).'.'.substr($varValue,0,4);
					break;
				case 4: // JJJJ
					$temp = $varValue;
					break;
				case 1: // Auf 0 prüfen
					$temp = ($varValue == '0') ? '' : $varValue;
					break;
				default: // anderer Wert
					$temp = $varValue;
			}
			return $temp;
		}

		return $varValue;

	}

	/**
	 * Datumswert für Datenbank umwandeln
	 * @param mixed
	 * @return mixed
	 */
	static function putDate($varValue)
	{
		$laenge = strlen(trim($varValue));
		$temp = '';
		switch($laenge)
		{
			case 10: // TT.MM.JJJJ
				$temp = substr($varValue,6,4).substr($varValue,3,2).substr($varValue,0,2);
				break;
			case 7: // MM.JJJJ
				$temp = substr($varValue,3,4).substr($varValue,0,2);
				break;
			case 4: // JJJJ
				$temp = $varValue;
				break;
			default: // anderer Wert
				$temp = 0;
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
