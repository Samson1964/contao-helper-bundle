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

	public function Schachbund($strTag)
	{
		$arrSplit = explode('::', $strTag);

		// Inserttag {{alter::TT.MM.JJJJ}}
		// Liefert zu einem Geburtstag das Alter in Jahren
		if($arrSplit[0] == 'alter' || $arrSplit[0] == 'cache_alter')
		{
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
		// Inserttag {{dwz::id}}
		// Liefert zu einer DeWIS-ID die aktuelle DWZ
		elseif($arrSplit[0] == 'dwz' || $arrSplit[0] == 'cache_dwz')
		{
			// Parameter angegeben?
			if(isset($arrSplit[1]))
			{
				$result = self::getPlayer($arrSplit[1]);
				return $result['dwz'];
			}
			else
			{
				return '';
			}
		}
		// Inserttag {{elo::id}}
		// Liefert zu einer DeWIS-ID die aktuelle Elo
		elseif($arrSplit[0] == 'elo' || $arrSplit[0] == 'cache_elo')
		{
			// Parameter angegeben?
			if(isset($arrSplit[1]))
			{
				$result = self::getPlayer($arrSplit[1]);
				return $result['elo'];
			}
			else
			{
				return '';
			}
		}
		// Inserttag {{ftitel::id}}
		// Liefert zu einer DeWIS-ID den aktuellen FIDE-Titel
		elseif($arrSplit[0] == 'ftitel' || $arrSplit[0] == 'cache_ftitel')
		{
			// Parameter angegeben?
			if(isset($arrSplit[1]))
			{
				$result = self::getPlayer($arrSplit[1]);
				return $result['titel'];
			}
			else
			{
				return '';
			}
		}
		else
		{
			return false; // Tag nicht dabei
		}

	}

	function getAlter($string)
	{
		$heute = date('Ymd');
		$geburtstag = date('Ymd', mktime(0, 0, 0, (int)substr($string, 3, 2), (int)substr($string, 0, 2), (int)substr($string, 6, 4)));
		$alter = floor(($heute - $geburtstag) / 10000);
		return $alter;
	}

	function getPlayer($id)
	{
		try
		{
			$client = new \SOAPClient( "https://dwz.svw.info/services/files/dewis.wsdl" );
			$result = $client->tournamentCardForId($id);
			$dwz = $result->member->rating;
			$elo = $result->member->elo;
			$titel = $result->member->fideTitle;
			return array
			(
				'dwz'   => $dwz,
				'elo'   => $elo,
				'titel' => $titel
			);
		}
		catch (SOAPFault $f)
		{
			print $f->faultstring;
		}
		return array();
	}
}
