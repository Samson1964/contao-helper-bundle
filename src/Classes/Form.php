<?php

namespace Schachbulle\ContaoHelperBundle\Classes;

class Form
{

	var $action;
	var $method;
	var $content;
	var $enctype;
	var $fields;
	var $formdata;
	var $mandatory = '<span class="mandatory">* Pflichtfeld</span>';

	function __construct($action="", $method="POST", $enctype="multipart/form-data")
	{
		$this->action = $action;
		$this->method = $method;
		$this->enctype = $enctype;
		$this->fields = array();
		$this->content = '<form action="'.$action.'" method="'.$method.'" enctype="'.$enctype.'"><div class="formbody">';
	}

	function addField($arrParam)
	{

		// Variablen initialisieren
		if(!isset($arrParam['name'])) $arrParam['name'] = '';
		if(!isset($arrParam['value'])) $arrParam['value'] = '';
		if(!isset($arrParam['class'])) $arrParam['class'] = '';
		if(!isset($arrParam['label'])) $arrParam['label'] = '';
		if(!isset($arrParam['rows'])) $arrParam['rows'] = '';
		if(!isset($arrParam['cols'])) $arrParam['cols'] = '';

		$this->fields[] = $arrParam['name'];

		// Wenn POST-Daten da sind, dann als value eintragen, ansonsten Formularvorgabewert nehmen
		if(\Input::post($arrParam['name'])) $value = \Input::post($arrParam['name']);
		else $value = $arrParam['value'];

		switch($arrParam['typ'])
		{
			case 'fieldset':
				// typ = fieldset
				// label = wenn leer, wird das fieldset beendet, ansonsten begonnen
				$string = $arrParam['label'] ? '<fieldset><legend>'.$arrParam['label'].'</legend>' : '</fieldset>';
				break;
			case 'hidden':
				$string = '<input type="hidden" name="'.$arrParam['name'].'" value="'.$arrParam['value'].'">';
				break;
			case 'text':
				$string = '<div class="widget widget-text '.$arrParam['class'].'">';
				$string .= '<label for="'.$arrParam['name'].'">'.$arrParam['label'].'##mandatory##</label>';
				$string .= '<input type="text" name="'.$arrParam['name'].'" id="'.$arrParam['name'].'" class="text '.$arrParam['class']. '" value="'.$value.'"##required##>';
				$string .= '</div>';
				break;
			case 'explanation':
				$string = '<div id="'.$arrParam['name']. '" class="widget widget-explanation explanation '.$arrParam['class']. '">'.$arrParam['label'].'</div>';
				break;
			case 'submit':
				$string = '<div class="widget widget-submit">';
				$string .= '<button type="submit" id="'.$arrParam['name'].'" value="'.$arrParam['value'].'" class="submit '.$arrParam['class']. '">'.$arrParam['label'].'</button>';
				$string .= '</div>';
				break;
			case 'textarea':
				$string = '<div class="widget widget-textarea">';
				$string .= '<label for="'.$arrParam['name'].'">'.$arrParam['label'].'##mandatory##</label>';
				$string .= '<textarea name="'.$arrParam['name'].'" id="'.$arrParam['name'].'" class="textarea '.$arrParam['class']. '" rows="'.$arrParam['rows'].'" cols="'.$arrParam['cols'].'"##required##>'.$value.'</textarea>';
				$string .= '</div>';
				break;
			case 'select':
				$string = '<div class="widget widget-select '.$arrParam['class'].'">';
				$string .= '<label for="'.$arrParam['name'].'">'.$arrParam['label'].'##mandatory##</label>';
				$string .= '<select name="'.$arrParam['name'].'" id="'.$arrParam['name'].'" class="select '.$arrParam['class'].'"##required##>';
				$tiefe = self::arrayTiefe($arrParam['options']);
				//echo '<pre>';
				//print_r($arrParam['options']);
				//echo $tiefe;
				//echo '</pre>';
				if($arrParam['options'])
				{
					// Array mit Gruppennamen
					if($tiefe > 1)
					{
						foreach($arrParam['options'] as $gruppenname => $turnier)
						{
							$string .= '<optgroup label="'.$gruppenname.'">';
							foreach($arrParam['options'][$gruppenname] as $key => $value)
							{
								$string .= '<option value="'.$key.'">'.$value.'</option>';
							}
							$string .= '</optgroup>';
						}
					}
					// Array ohne Gruppennamen
					else
					{
						foreach($arrParam['options'] as $key => $value)
						{
							$string .= '<option value="'.$key.'">'.$value.'</option>';
						}
					}
				}
				$string .= '</select>';
				$string .= '</div>';
				break;
			case 'radio':
				$string = '<div class="widget widget-select '.$arrParam['class'].'">';
				$string .= '<label for="'.$arrParam['name'].'">'.$arrParam['label'].'##mandatory##</label>';
				$tiefe = self::arrayTiefe($arrParam['options']);
				if($arrParam['options'])
				{
					// Array mit Gruppennamen
					if($tiefe > 1)
					{
						foreach($arrParam['options'] as $gruppenname => $turnier)
						{
							$string .= '<p><b>'.$gruppenname.'</b></p>';
							foreach($arrParam['options'][$gruppenname] as $key => $value)
							{
								$string .= '<input type="radio" name="'.$arrParam['name'].'" id="turnier'.$key.'" class="radio '.$arrParam['class'].'" value="'.$key.'"> <label for="turnier'.$key.'">'.$value.'</label>';
							}
						}
					}
					// Array ohne Gruppennamen
					else
					{
						foreach($arrParam['options'] as $key => $value)
						{
							$string .= '<input type="radio" name="'.$arrParam['name'].'" id="turnier'.$key.'" class="radio '.$arrParam['class'].'" value="'.$key.'"> <label for="turnier'.$key.'">'.$value.'</label>';
						}
					}
				}
				$string .= '</div>';
				break;

			case 'checkbox':
				$string = '<div class="widget widget-checkbox '.$arrParam['class'].'">';
				$string .= '<input type="checkbox" name="'.$arrParam['name'].'" class="checkbox '.$arrParam['class']. '" value="'.$arrParam['value'].'">';
				$string .= '&nbsp;<label for="'.$arrParam['name'].'">'.$arrParam['label'].'##mandatory##</label>';
				$string .= '</div>';
				break;
		}
		// Pflichtfelder ersetzen
		if(isset($arrParam['mandatory']))
		{
			$string = str_replace('##mandatory##', $this->mandatory, $string);
			$string = str_replace('##required##', ' required', $string);
		}
		else
		{
			$string = str_replace('##mandatory##', '', $string);
			$string = str_replace('##required##', '', $string);
		}
		$this->content .= $string;
	}

	function generate()
	{
		return $this->content .= '</div></form>';
	}

	function validate()
	{
		if(\Input::post('FORM_SUBMIT'))
		{
			$this->formdata = array();
			foreach($this->fields as $feldname)
			{
				//echo 'Feldname = '.$feldname.' Wert = '.\Input::post($feldname).'<br>';
				$this->formdata[$feldname] = \Input::post($feldname);
			}
			return true;
		}
		else return false;
	}

	function fetchAll()
	{
		return $this->formdata;
	}

	/**
	 * Funktion arrayTiefe
	 * =======================
	 * Ermittelt die Verschachtelungstiefe eines Arrays
	 * Diese Funktion verwendet Rekursion, um die Tiefe des Arrays zu ermitteln. Zunächst wird die Variable $max_ Depth auf 1 initialisiert. Dann durchläuft es das Array und prüft, ob jeder Wert ein Array ist. Wenn dies der Fall ist, ruft es die Funktion rekursiv auf, um die Tiefe des Subarrays abzurufen und zu bestimmen, ob die Tiefe des Subarrays größer als $max_ Depth ist. Wenn dies der Fall ist, wird die Variable $max_ Depth auf eine tiefere Tiefe aktualisiert. Schließlich geben wir die Variable $max_ Depth zurück, die die Tiefe des Arrays darstellt. 
	 *
	 * @return array
	 */
	function arrayTiefe($array) 
	{
	
		$max_depth = 1;
		foreach($array as $value) 
		{
			if(is_array($value)) 
			{
				$depth = self::arrayTiefe($value) + 1;
				if ($depth > $max_depth) 
				{
					$max_depth = $depth;
				}
			}
		}
		return $max_depth;
	}
	
}
