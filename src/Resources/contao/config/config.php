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
 
$GLOBALS['TL_HOOKS']['replaceInsertTags'][] = array('Schachbulle\ContaoHelperBundle\Classes\Tags', 'Schachbund');

/**
 * -------------------------------------------------------------------------
 * Voreinstellungen (wenn noch nicht vorhanden)
 * -------------------------------------------------------------------------
 */
$GLOBALS['TL_CONFIG']['insert_verein_replaces'] = 'a:7:{i:0;a:2:{s:6:"search";s:12:"Schachverein";s:7:"replace";s:2:"SV";}i:1;a:2:{s:6:"search";s:5:"SABT+";s:7:"replace";s:0:"";}i:2;a:2:{s:6:"search";s:10:"Schachclub";s:7:"replace";s:2:"SC";}i:3;a:2:{s:6:"search";s:10:"Schachklub";s:7:"replace";s:2:"SK";}i:4;a:2:{s:6:"search";s:13:"Schachfreunde";s:7:"replace";s:2:"SF";}i:5;a:2:{s:6:"search";s:5:"+e.V.";s:7:"replace";s:0:"";}i:6;a:2:{s:6:"search";s:3:"+eV";s:7:"replace";s:0:"";}}';
