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
				// DWZ-Abfragen abgeschaltet?
				if($GLOBALS['TL_CONFIG']['dewis_switchedOff'])
				{
					return '';
				}
				else
				{
					$result = self::getPlayer($arrSplit[1]);
					return $result['dwz'];
				}
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
				// DWZ-Abfragen abgeschaltet?
				if($GLOBALS['TL_CONFIG']['dewis_switchedOff'])
				{
					return '';
				}
				else
				{
					$result = self::getPlayer($arrSplit[1]);
					return '<a href="http://ratings.fide.com/profile/'.$result['fideid'].'" target="_blank">'.$result['elo'].'</a>';
				}
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
				// DWZ-Abfragen abgeschaltet?
				if($GLOBALS['TL_CONFIG']['dewis_switchedOff'])
				{
					return '';
				}
				else
				{
					$result = self::getPlayer($arrSplit[1]);
					$titel = $result['titel'];
					// Lange Version des Titel ausgeben, wenn Parameter lang gesetzt ist
					if(isset($arrSplit[2]) && $arrSplit[2] = 'lang')
					{
						switch($titel)
						{
							case 'GM': $titel = 'Großmeister'; break;
							case 'WGM': $titel = 'Großmeisterin'; break;
							case 'IM': $titel = 'Internationaler Meister'; break;
							case 'WIM': $titel = 'Internationale Meisterin'; break;
							case 'FM': $titel = 'FIDE-Meister'; break;
							case 'WFM': $titel = 'FIDE-Meisterin'; break;
							case 'CM': $titel = 'Kandidatenmeister'; break;
							case 'WCM': $titel = 'Kandidatenmeisterin'; break;
							default:
						}
					}
					return $titel;
				}
			}
			else
			{
				return '';
			}
		}
		// Inserttag {{verein::id|Länge}}
		// Liefert zu einer DeWIS-ID den aktuellen Verein
		elseif($arrSplit[0] == 'verein' || $arrSplit[0] == 'cache_verein')
		{
			// Parameter angegeben?
			if(isset($arrSplit[1]))
			{
				// DWZ-Abfragen abgeschaltet?
				if($GLOBALS['TL_CONFIG']['dewis_switchedOff'])
				{
					return '';
				}
				else
				{
					$result = self::getPlayer($arrSplit[1]);
					$verein = $result['verein'];

					// Vereinsname kürzen
					$replaces = (array)unserialize($GLOBALS['TL_CONFIG']['insert_verein_replaces']); // Ersetzungen aus Einstellungen laden
					// + durch Leerzeichen ersetzen und Zielarrays füllen
					$search = array();
					$replace = array();
					for($x = 0; $x < count($replaces); $x++)
					{
						$search[] = str_replace('+', ' ', $replaces[$x]['search']);
						$replace[] = str_replace('+', ' ', $replaces[$x]['replace']);
					}
					// Ersetzungen ausführen, ohne Rücksicht Groß- und Kleinschreibung
					$verein = str_ireplace($search, $replace, $verein);

					// Vereinsname auf Länge trimmen, wenn gewünscht
					if($arrSplit[2]) $verein = substr($verein, 0, $arrSplit[2]);
					return $verein;
				}
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
				$figur = explode('/', $arrSplit[1]); // Name und Größe trennen
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
				$groesse = $figur[1] ? $figur[1].'px' : '16px';
				// Grafik zurückgeben
				return '<img src="bundles/contaohelper/chess/'.$datei.'" width="'.$groesse.'" style="vertical-align: text-bottom;">';
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

	/**
	 * Funktion getAlter
	 *
	 * Ermittelt das Alter in Jahren aufgrund eines (Geburts-)Datums
	 *
	 * @string    string       TT.MM.JJJJ oder MM.JJJJ oder JJJJ
	 * @return    integer      Alter in Jahren
	 */
	function getAlter($string)
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
		//$geburtstag = date('Ymd', mktime(0, 0, 0, (int)substr($string, 3, 2), (int)substr($string, 0, 2), (int)substr($string, 6, 4)));
		$alter = floor(($heute - $geburtstag) / 10000);
		return $alter;
	}

	function getPlayer($id)
	{
		try
		{
			$context = stream_context_create([
				'ssl' => [
					'verify_peer' => false,
					'verify_peer_name' => false,
					'allow_self_signed' => true
				]
			]);

			$client = new \SOAPClient(
				NULL,
				array(
					'location'           => 'https://dwz.svw.info/services/soap/index.php',
					'uri'                => 'https://soap',
					'style'              => SOAP_RPC,
					'use'                => SOAP_ENCODED,
					'connection_timeout' => 15,
					'stream_context'     => $context // Entfernt am 21.02.2019 da svw.info meldete: Error Fetching http body, No Content-Length, connection closed or chunked data
					// Wieder aktiviert am 23.03.2021 weil die Schnittstelle meldete: Could not connect to host
				)
			);
			$result = $client->tournamentCardForId($id);
			//echo "<pre>";
			//print_r($result);
			//echo "</pre>";
			$dwz = $result->member->rating;
			$elo = $result->member->elo;
			$titel = $result->member->fideTitle;
			$fideid = $result->member->idfide;
			$verein = $result->memberships[0]->club;
			return array
			(
				'dwz'    => $dwz,
				'elo'    => $elo,
				'titel'  => $titel,
				'fideid' => $fideid,
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
