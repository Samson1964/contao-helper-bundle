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
	var $mandatory = '<span class="mandatory">*</span>';

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

		$this->fields[] = $arrParam['name'];

		// Wenn POST-Daten da sind, dann als value eintragen, ansonsten Formularvorgabewert nehmen
		if(\Input::post($arrParam['name'])) $value = \Input::post($arrParam['name']);
		else $value = $arrParam['value'];

		switch($arrParam['typ'])
		{
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
				$string .= '<div class="widget widget-textarea">';
				$string .= '<label for="'.$arrParam['name'].'">'.$arrParam['label'].'##mandatory##</label>';
				$string .= '<textarea name="'.$arrParam['name'].'" id="'.$arrParam['name'].'" class="textarea '.$arrParam['class']. '" rows="'.$arrParam['rows'].'" cols="'.$arrParam['cols'].'"##required##>'.$value.'</textarea>';
				$string .= '</div>';
				break;
			case 'select':
				$string = '<div class="widget widget-select '.$arrParam['class'].'">';
				$string .= '<label for="'.$arrParam['name'].'">'.$arrParam['label'].'##mandatory##</label>';
				$string .= '<select name="'.$arrParam['name'].'" id="'.$arrParam['name'].'" class="select '.$arrParam['class'].'"##required##>';
				if($arrParam['options'])
				{
					foreach($arrParam['options'] as $key => $value)
					{
						$string .= '<option value="'.$key.'">'.$value.'</option>';
					}
				}
				$string .= '</select>';
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
		if($arrParam['mandatory'])
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
}
