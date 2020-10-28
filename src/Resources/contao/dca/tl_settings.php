<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 *
 * Copyright (C) 2005-2013 Leo Feyer
 *
 * @package   fen
 * @author    Frank Hoppe
 * @license   GNU/LGPL
 * @copyright Frank Hoppe 2013
 */

/**
 * palettes
 */
$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] .= ';{insert_verein_legend:hide},insert_verein_replaces';

/**
 * fields
 */

$GLOBALS['TL_DCA']['tl_settings']['fields']['insert_verein_replaces'] = array
(
	'label'                               => &$GLOBALS['TL_LANG']['tl_settings']['insert_verein_replaces'],
	'exclude'                             => true,
	'inputType'                           => 'multiColumnWizard',
	'eval'                                => array
	(
		'tl_class'                        => 'long clr',
		'buttonPos'                       => 'middle',
		'buttons'                         => array
		(
			'copy'                        => 'system/themes/flexible/icons/copy.svg',
			'delete'                      => 'system/themes/flexible/icons/delete.svg',
			'move'                        => 'system/themes/flexible/icons/move.svg',
			'up'                          => 'system/themes/flexible/icons/up.svg',
			'down'                        => 'system/themes/flexible/icons/down.svg'
		),
		'columnFields'                    => array
		(
			'search' => array
			(
				'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['insert_verein_search'],
				'exclude'                 => true,
				'inputType'               => 'text',
			),
			'replace' => array
			(
				'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['insert_verein_replace'],
				'exclude'                 => true,
				'inputType'               => 'text',
			),
		)
	),
);
