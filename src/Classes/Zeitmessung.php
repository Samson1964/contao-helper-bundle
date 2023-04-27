<?php

namespace Schachbulle\ContaoHelperBundle\Classes;

class Zeitmessung
{

	var $startzeit; // Speichert die Startzeit
	var $schleifen; // Schleifenzähler
	
	function __construct()
	{
		$this->schleifen = 0;
	}

	function Start()
	{
		$this->startzeit = microtime(true);
	}

	function Zaehler()
	{
		$this->schleifen++;
	}

	function Stop()
	{
		$dauer = microtime(true) - $this->startzeit;
		echo "Verarbeitung des Skripts: $dauer Sek. / $this->schleifen Durchläufe";
	}
}
