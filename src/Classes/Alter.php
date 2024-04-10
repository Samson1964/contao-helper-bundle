<?php

namespace Schachbulle\ContaoHelperBundle\Classes;

class Alter
{

	function __construct()
	{
	}

	/**
	 * Funktion Jahre
	 *
	 * Ermittelt das Alter in Jahren aufgrund eines (Geburts-)Datums
	 *
	 * @string    string       TT.MM.JJJJ oder MM.JJJJ oder JJJJ
	 * @return    integer      Alter in Jahren
	 */
	function Jahre($string)
	{
		$col = explode('.', trim($string)); // String mit Datum zerlegen
		if(count($col) == 1)
		{
			// Nur JJJJ übergeben
			$geburtstag = $col[0].'0101';
		}
		elseif(count($col) == 2)
		{
			// Nur MM.JJJJ übergeben
			$geburtstag = $col[1].$col[0].'01';
		}
		elseif(count($col) == 3)
		{
			// TT.MM.JJJJ übergeben
			$geburtstag = $col[2].$col[1].$col[0];
		}
		else
		{
			return false;
		}

		$heute = date('Ymd');
		$alter = floor(($heute - $geburtstag) / 10000);
		return $alter;
	}
}
