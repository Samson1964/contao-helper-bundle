<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package   fh-counter
 * @author    Frank Hoppe
 * @license   GNU/LGPL
 * @copyright Frank Hoppe 2014
 */

namespace Schachbulle\ContaoHelperBundle\Classes;

class Tags extends \Frontend
{

	public function Alter($strTag)
	{
		$arrSplit = explode('::', $strTag);

		if($arrSplit[0] != 'alter' && $arrSplit[0] != 'cache_alter') return false; // Nicht unser Inserttag

		// Parameter angegeben?
		if(isset($arrSplit[1]))
		{
			return self::getAlter($arrSplit[1]);
		}
		else
		{
			return 'Geburtstag fehlt!';
		}

	}

	function getAlter($string)
	{
		$heute = date('Ymd');
		$geburtstag = date('Ymd', mktime(0, 0, 0, (int)substr($string, 3, 2), (int)substr($string, 0, 2), (int)substr($string, 6, 4)));
		$alter = floor(($heute - $geburtstag) / 10000);
		return $alter;
	}

}
