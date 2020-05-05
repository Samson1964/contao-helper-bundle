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
}
