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
		// Inserttag {{ftitel::id}}
		// Liefert zu einer DeWIS-ID den aktuellen Verein
		elseif($arrSplit[0] == 'verein' || $arrSplit[0] == 'cache_verein')
		{
			// Parameter angegeben?
			if(isset($arrSplit[1]))
			{
				$result = self::getPlayer($arrSplit[1]);
				return $result['verein'];
			}
			else
			{
				return '';
			}
		}
		// Inserttag {{figur::Name|Größe}}
		// Zeigt eine Schachfigur an
		elseif($arrSplit[0] == 'figur' || $arrSplit[0] == 'cache_figur')
		{
			// Parameter angegeben?
			if(isset($arrSplit[1]))
			{
				$figur = explode('|', $arrSplit[1]); // Name und Größe trennen
				switch($figur[0])
				{
					case 'wB':
						$datei = 'wP.png'; break;
					case 'wT':
						$datei = 'wR.png'; break;
					case 'wD':
						$datei = 'wQ.png'; break;
					case 'wK':
						$datei = 'wK.png'; break;
					case 'wS':
						$datei = 'wN.png'; break;
					case 'wL':
						$datei = 'wB.png'; break;

					case 'sB':
						$datei = 'bP.png'; break;
					case 'sT':
						$datei = 'bR.png'; break;
					case 'sD':
						$datei = 'bQ.png'; break;
					case 'sK':
						$datei = 'bK.png'; break;
					case 'sS':
						$datei = 'bN.png'; break;
					case 'sL':
						$datei = 'bB.png'; break;

					default:
						$datei = $figur[0].'_ungueltig';
				}
				// Größe zuweisen
				if($figur[1]) $groesse = $figur[1].'px';
				else $groesse = '16px';
				// Grafik zurückgeben
				return '<img src="bundles/contaohelper/chess/'.$datei.'" width="'.$groesse.'">';
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
			//echo "<pre>";
			//print_r($result);
			//echo "</pre>";
			$dwz = $result->member->rating;
			$elo = $result->member->elo;
			$titel = $result->member->fideTitle;
			$verein = $result->memberships[0]->club;
			return array
			(
				'dwz'    => $dwz,
				'elo'    => $elo,
				'titel'  => $titel,
				'verein' => $verein
			);
		}
		catch (SOAPFault $f)
		{
			print $f->faultstring;
		}
		return array();
	}
}
